<?php
/**
 * @Author Ankit Patidar <ankitpatidar@hostnsoft.com>
 * @since 26/02/2013
 * @filesource 
 * @uses class contains common function to add/update department,add/update number to department and add/remove number to call list  
 * 
 */
include dirname(dirname(__FILE__)).'/config.php';
class clickToCall_plugin_class extends fun
{
      
        /**
         * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
         * @since 26/02/2014
         * @desc function use to add department
         * @param array $request,type:0 to add 1 to update
         * @param int $userid
         * 
         * @return json
         */
        function addUpdateDepartment($request,$session)
	{
           //apply validation on department name 
            if(!isset($request['deptName']) || $request['deptName']=='' || preg_match(NOTALPHABATESPACE_REGX,$request['deptName']))
               return json_encode(array('status' => 0,'msg' => 'Please enter a valid Department Name!!!'));
            
            //get department name in desired format ex. Maketing And Analysis
            $standardDeptName = $this->db->real_escape_string(ucwords(strtolower(trim($request['deptName']))));
            
            if(!isset($session['id']) || $session['id']=='' || $session['id'] == null )
                 return json_encode(array('status' => 0,'msg' => 'you do not have permission to add or update department!!!'));
            
            //get user id
            $userId = $session['id'];
            //prepare data to insert
           
            $data = array("deptName" => $standardDeptName,
                        "createdBy" => $userId        
                        );
            
            //table to perform operation
            $table = '91_departments';
          
            if(isset($request['type']) && $request['type'] == 1) //for update
            {
                //validate department id
                if(!isset($request['deptId']) || !is_numeric($request['deptId']) )
                    return json_encode(array('status' => 0,'msg' => 'It is not a valid department!!!'));
                else
                 $deptId = $request['deptId'];
                //condition
                $condition = 'deptId='.$deptId.' and createdBy='.$userId;

                $res = $this->updateData($data, $table,$condition);

                //validate result
                if(!$res)
                {
                    trigger_error('problem while department updation,details'.json_encode($data));
                    return json_encode(array('status' => 0,'msg' => 'problem while department updation!!!'));
                }

                //check for affected rows
                if($this->db->affected_rows > 0)
                {
                    return json_encode(array('status' => 1,'msg' => 'Department successfully updated!!!'));
                }
                else
                    return json_encode(array('status' => 0,'msg' => 'Please update department first!!!'));
                    
                
            } //end of if
            else //for add
            {
                
                //code to check for already exists department
                $this->db->select('*')->from($table)->where("deptName = '" . $standardDeptName . "' and createdBy=$userId");
                $qur = $this->db->getQuery();
                $result = $this->db->execute();

                ////log error
                if(!$result)
                    trigger_error('problem while get department details ,query:'.$qur);

                if ($result->num_rows > 0) 
                {
                    return json_encode(array("status" => 0, "msg" => "This Department name already registered!"));
                }
                
                //insert data 
                $res = $this->insertData($data, $table);
                
               
                if(!$res)
                {
                    trigger_error();
                    return json_encode(array('status' => 0,'msg' => 'Problem while saving department!!!'));
                }
                
                return json_encode(array('status' => 1,'msg' => 'Department successfully added!!!','lastDeptId' => $this->db->insert_id));
            } //end of else
            
            
        }
        
        /**
         * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
         * @since 26/02/2014
         * function to add update number to department
         */
        function addNumberToDept($request,$session)
        {
           
            //check department id
            if(!isset($request['deptId']) || !is_numeric($request['deptId']) || $request['deptId']== null || $request['deptId'] =='')
                return json_encode(array('status' => 0,'msg' => 'InValid Department!!!'));
            else if(!isset($session['id']) || !is_numeric($session['id']) || $session['id']== null || $session['id'] =='')
                return json_encode(array('status' => 0,'msg' => 'You have not perssion for this action!!!'));
                
            //apply validation on number
            if(!isset($request['number']) || $request['number']=='' || strlen($request['number']) < 8 || strlen($request['number']) > 22 )
               return json_encode(array('status' => 0,'msg' => 'Please enter a valid number!!!'));
            
            
            //get user id
            $userId = $session['id'];
            
            //get dept id
            $deptId = $request['deptId'];
            
            $number = trim($request['number']);
            
            //prepare data to insert
            $data = array("deptId" => $deptId,
                        "number" => "$number",
                        "active"=> 0,
                        "priority" => 0,
                        "userId" => $userId
                        );   
            
           
            $table = '91_deptNumbers';
           
            //code to check for already exists department
            $this->db->select('*')->from($table)->where("deptId =$deptId and number='$number'");
            $qur = $this->db->getQuery();
           
           $result = $this->db->execute();

            ////log error
            if(!$result)
                trigger_error('problem while get department details ,query:'.$qur);

            if ($result->num_rows > 0) 
            {
                return json_encode(array("status" => 0, "msg" => "This Number already exists in this department!"));
            }

             //insert data 
            $res = $this->insertData($data, $table);

            if(!$res)
            {
                trigger_error('problem while add number to department!!!,data:'.json_encode($data));
                return json_encode(array('status' => 0,'msg' => 'Problem while add number to department!!!'));
            }

            if($this->db->affected_rows > 0)
                return json_encode(array('status' => 1,'msg' => 'Number successfully added to Department!!!','lastNumId' => $this->db->insert_id));    
            else
                 return json_encode(array('status' => 0,'msg' => 'Problem while add number to department!!!'));
            
        } //end of function addNumberToDept
        
      
        /**
         * @author Ankit patidat <ankitpatidar@hostnsoft.com>
         * @since 26/02/2014
         * @uses it gives dept list
         * @param int userId
         */
        function getDeptNumberList($request,$session)
        {
//            if(empty($request))
//                $request['userId']= $session['id'];
            
           
//            if(!isset($request['userId']) || !is_numeric($request['userId']) || $request['userId']== null || $request['userId'] =='')
//                return json_encode(array('status' => 0,'msg' => 'InValid user!!!'));
//          
            if(!isset($session['id']))
            {
                 return json_encode(array('status' => 0,'msg' => 'InValid user!!!'));
            }
            
            $userId = $session['id'];
            
            $table = '91_departments';
             //code to check for already exists department
            $this->db->select('*')->from($table)->where("createdBy=$userId");
            $qur = $this->db->getQuery();
            $result = $this->db->execute();

    
            if(!$result)
                trigger_error('problem while get department details ,query:'.$qur);
            
            $this->db->select('*')->from('91_deptNumbers')->where("userId=$userId");
            $qur1 = $this->db->getQuery();
            $resultCount = $this->db->execute();
            
            if(!$resultCount)
            {
                trigger_error('problem while get department details ,query:'.$qur1);
                return json_encode(array('status' => 0,'msg' => 'Problem while get dept number details!!!'));
            }
            
            //get deptNumber count
            if($resultCount->num_rows)
            {
                while($resultRow = $resultCount->fetch_array(MYSQLI_ASSOC))
                {
                    $countArry[$resultRow['deptId']][]= array('id' =>$resultRow['sNo'],'no' =>$resultRow['number'] );//$resultRow['sNo'];
                    //$countArry[$resultRow['deptId']][]['no'] = $resultRow['number'];
                    unset($resultRow);
                }
            }
            
            
            if ($result->num_rows > 0) 
            {
                while($row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    $depts[$row['deptId']]=$row['deptName'];
                    unset($row);
                }
                
            }
 
            
            foreach($depts as $id => $name)
            {
                $data[$id]['name']=$name;
                
                if(isset($countArry[$id]))
                    $data[$id]['number']=$countArry[$id];
                else
                    $data[$id]['number']=array();
            }
            
             $detail['depts'] = $data;

            return json_encode($detail);
            
        } //end of function
       
       function getDeptNumberListold($request,$session)
        {
//            if(empty($request))
//                $request['userId']= $session['id'];
            
           
//            if(!isset($request['userId']) || !is_numeric($request['userId']) || $request['userId']== null || $request['userId'] =='')
//                return json_encode(array('status' => 0,'msg' => 'InValid user!!!'));
//          
            if(!isset($session['id']))
            {
                 return json_encode(array('status' => 0,'msg' => 'InValid user!!!'));
            }
            
            $userId = $session['id'];
            
            $table = '91_departments';
             //code to check for already exists department
            $this->db->select('*')->from($table)->where("createdBy=$userId");
            $qur = $this->db->getQuery();
            $result = $this->db->execute();

    
            if(!$result)
                trigger_error('problem while get department details ,query:'.$qur);
            
            $this->db->select('count(*) as count,deptId')->from('91_deptNumbers')->where("userId=$userId")->groupBy('deptId');
            $qur1 = $this->db->getQuery();
            $resultCount = $this->db->execute();
            
            if(!$resultCount)
            {
                trigger_error('problem while get department details ,query:'.$qur1);
                return json_encode(array('status' => 0,'msg' => 'Problem while get dept number details!!!'));
            }
            
            //get deptNumber count
            if($resultCount->num_rows)
            {
                while($resultRow = $resultCount->fetch_array(MYSQLI_ASSOC))
                {
                    $countArry[$resultRow['deptId']] = $resultRow['count'];
                    unset($resultRow);
                }
            }
            
            
            if ($result->num_rows > 0) 
            {
                while($row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    $depts[$row['deptId']]=$row['deptName'];
                    unset($row);
                }
                
            }
 
            foreach($depts as $id => $name)
            {
                $data[$id]['name']=$name;
                
                if(isset($countArry[$id]))
                    $data[$id]['count']=$countArry[$id];
                else
                    $data[$id]['count']=0;
            }
            
             $detail['depts'] = $data;
            return json_encode($detail);
            
        } //end of function
        
        /**
         * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
         * @since 26/02/2014
         * @uses to delete number from dept
         * 
         */
	function deleteNumberFromDept($request,$session)
        {
            
            //check department id
            if(!isset($request['deptId']) || !is_numeric($request['deptId']) || $request['deptId']== null || $request['deptId'] =='')
                return json_encode(array('status' => 0,'msg' => 'InValid Department!!!'));
            else if(!isset($request['numId']) || !is_numeric($request['numId']) || $request['numId']== null || $request['numId'] =='')
                return json_encode(array('status' => 0,'msg' => 'Invalid number selected!!!'));
            else if(!isset($session['id']) || !is_numeric($session['id']) || $session['id']== null || $session['id'] =='')
                return json_encode(array('status' => 0,'msg' => 'You have not perssion for this action!!!'));
                
            //apply validation on number
//            if(!isset($request['number']) || $request['number']=='' || preg_match(NOTMOBNUM_REGX,$request['number']) || strlen($request['number']) < 8 || strlen($request['number']) > 18 || !is_numeric($request['number']))
//               return json_encode(array('status' => 0,'msg' => 'This is not valid number!!!'));
            
            $table = '91_deptNumbers';

            $deptId = $request['deptId'];
            $userId = $session['id'];
            $numId=$request['numId'];
            
            $condition = 'userId='.$userId.' and deptId='.$deptId.' and sNo='.$numId;
            $isDeleted =  $this->deleteData($table,$condition);
            
            if(!$isDeleted)
            {
                trigger_error('problem while deletion,condition='.$condition);
                return json_encode(array('status' => 0,'msg' => 'problem while number deleletion!!!'));
            }
            else
                return json_encode(array('status' => 1,'msg' => 'number successfully deleted!!!'));
        }

     
	 /**
         * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
         * @since 27/02/2014
         * @uses to delete dept
         * 
         */
	function deleteDept($request,$session)
        {
            
            //check department id
            if(!isset($request['deptId']) || !is_numeric($request['deptId']) || $request['deptId']== null || $request['deptId'] =='')
                return json_encode(array('status' => 0,'msg' => 'InValid Department!!!'));
           
              //check for user permission
            else if(!isset($session['id']) || !is_numeric($session['id']) || $session['id']== null || $session['id'] =='')
                return json_encode(array('status' => 0,'msg' => 'You have not perssion for this action!!!'));
                
           
            $table = '91_deptNumbers';

            $deptId = $request['deptId'];
            $userId = $session['id'];
           
            //first delete all numbers for this department
            $condition = 'userId='.$userId.' and deptId='.$deptId;
            $isDeleted =  $this->deleteData($table,$condition);
            
            if(!$isDeleted)
            {
                trigger_error('problem while deletion,condition='.$condition);
                return json_encode(array('status' => 0,'msg' => 'problem while number deleletion for this department!!!'));
            }
            else //deleted department
            {
                $deptTable = '91_departments';
                
                $conditionDept = 'createdBy='.$userId.' and deptId='.$deptId;
                $isDeptDeleted =  $this->deleteData($deptTable,$conditionDept);
           
                if(!$isDeptDeleted)
                {
                    trigger_error('problem while dept deletion,condition='.$condition);
                    return json_encode(array('status' => 0,'msg' => 'problem while department deleletion!!!'));
                }
                else
                     return json_encode(array('status' => 1,'msg' => 'Department successfully deleted!!!'));
            }
               
        
       }
        
       /**
        * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
        * @since 27/02/2014
        * 
        */
       function getRandomNumberOfDept($deptId)
       {
           if(empty($deptId) || !is_numeric($deptId))
               return json_encode(array('status' => 0,'msg' => 'Enter valid department!!!'));
           
           $table = '91_deptNumbers';
           
           $this->db->select('number')->from('91_deptNumbers')->where("deptId=$deptId")->orderBy('RAND()')->limit(1);
           $qur = $this->db->getQuery();
           $result = $this->db->execute(); 

           if(!$result)
           {
               trigger_error('problem while get number,query='.$qur);
               return json_encode(array('status' => 0,'msg' => 'Problem while get number!!!'));
           }
           
           if($result->num_rows > 0)
           {
               
               $row = $result->fetch_array(MYSQLI_ASSOC);
              
               $number = $row['number'];
               return json_encode(array('status' => 1,'msg' => 'Number number found!!!','number' => $number));
           }
           else
               return json_encode(array('status' => 0,'msg' => 'Number not exists!!!'));
       }
       
       
        /**
        * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
        * @since 27/02/2014
        * 
        */
       function getNumbersOfDept($deptId)
       {
           if(empty($deptId) || !is_numeric($deptId))
               return json_encode(array('status' => 0,'msg' => 'Enter valid department!!!'));
           
           $table = '91_deptNumbers';
           
           $this->db->select('number')->from('91_deptNumbers')->where("deptId=$deptId");
           $qur = $this->db->getQuery();
           $result = $this->db->execute(); 

           if(!$result)
           {
               trigger_error('problem while get number,query='.$qur);
               return json_encode(array('status' => 0,'msg' => 'Problem while get numbers!!!'));
           }
           
           if($result->num_rows > 0)
           {
               
               while($row = $result->fetch_array(MYSQLI_ASSOC))
               {
                   $numbers[] = $row['number'];
                   unset($row);
               }
               
               return json_encode(array('status' => 1,'msg' => 'Number found!!!','numbers' => $numbers));
           }
           else
               return json_encode(array('status' => 0,'msg' => 'Number not exists!!!'));
       }
	
      /**
       * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
       * @since 28/02/2014
       * @uses to get depts by user id
       * @param int $userId
       */
       function getDeptsByUserId($userId)
       {
           if(empty($userId) || $userId == '' || !is_numeric($userId))
           {
               return json_encode(array('status' => 0,'msg' => 'not a valid number!!!'));
           }
           
           $table = '91_departments';
           
           $result = $this->selectData('deptId,deptName',$table,'createdBy='.$userId);
           
           if(!$result)
           {
               trigger_error('problem while get dept,createdBy='.$userId);
               return json_encode(array('status' => 0,'msg' => 'Problem while get department!!!'));
           }
           
           $deptNumTable = '91_deptNumbers';
           
           $data= array();
           
           if($result->num_rows > 0)
           {
               while($row = $result->fetch_array(MYSQLI_ASSOC))
               {
                   
                    $this->db->select('number')->from($deptNumTable)->where("deptId=".$row["deptId"]);
                    $qur = $this->db->getQuery();
                    $resultNum = $this->db->execute(); 

                    if(!$resultNum)
                    {
                        trigger_error('problem while get number,query='.$qur);
                        return json_encode(array('status' => 0,'msg' => 'Problem while get number!!!'));
                    }
          
                    if($resultNum->num_rows > 0)
                    {
                        $data[$row['deptId']] = $row['deptName'];
                    }
                   
                   
                   unset($row);
               }
              
               return json_encode(array('status' => 1,'msg' => 'departments found!!!','depts' => $data));
           }
           else
               return json_encode(array('status' => 0,'msg' => 'department not  found!!!'));
           
       }
       
        /**
         * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
         * @param array $request
         * @since 02/04/2014 
         */
       function addSchedule($request,$session)
       {
           
           if(!isset($request['day']) || !isset($request['fromHours']) || !isset($request['fromMinutes']) || !isset($request['toHours'] )|| !isset($request['toMinutes']))
           {
               trigger_error('Problem while saving schedule for number'.json_encode($request));
               return json_encode(array('status' => 0,'msg' => 'Problem while saving ' ));
           }
           
           if(!isset($request['userId']) || !is_numeric($request['userId']))
               return json_encode(array('status' => 0,'msg' => 'Invalis user!!!' ));
               
           
           if($request['userId'] != $session['userid'])
              return json_encode(array('status' => 0,'msg' => 'Permission Denied!!!' ));
               
           if(!isset($request['scheduleName']) || strlen($request['scheduleName'] > 50) || preg_match(NOTUSERNAME_EMAIL_REGX,$request['scheduleName']))
              return json_encode(array('status' => 0,'msg' => 'Please enter a valid schedule name!!!' ));
           
           $scheduleName = $this->db->real_escape_string($request['scheduleName']);
           $userId = $request['userId'];
           
           $data = array('scheduleName' => $scheduleName,
                         'createdBy' => $userId);
           
           $table = '91_schedules';
          
            //insert data 
           $res = $this->insertData($data, $table);
           
           if(!$res || ($this->db->affected_rows == 0))
           {
               trigger_error('Problem while schedule insert ,query:'.$this->querry);
               return json_encode(array('status' => 0,'msg' => 'schedule not inserted!!!' ));
           }
               
           $scheduleId = $this->db->insert_id;
           
           if($request['format'] == 12)
           {
               $format = 0;
           }
           else
               $format = 1;
           
           $countRecord = count($request['day']);
           $i=0;
           
           
           $sql = 'INSERT INTO 91_scheduleDetails (scheduleId,fromTime,toTime,scheduleDay,displayClock) VALUES ';
           
           //prepare query
           for($i=0 ; $i < $countRecord  ;$i++)
           {
               $fromTime = $this->db->real_escape_string($request['fromHours'][$i]).':'.$this->db->real_escape_string($request['fromMinutes'][$i]); 
               
               $toTime = $this->db->real_escape_string($request['toHours'][$i]).':'.$this->db->real_escape_string($request['toMinutes'][$i]);
                $sql .= "($scheduleId,'" .$fromTime. "','" .$toTime. "','" .$this->db->real_escape_string($request['day'][$i]). "','".$format."'),";
              
           }
           
          // $sqlExtend = "on DUPLICATE KEY UPDATE userId=VALUES(userId),number=VALUES(number),fromTime=VALUES(fromTime),toTime=VALUES(toTime),scheduleDay=VALUES(scheduleDay),displayClock=VALUES(displayClock)";
           
           $sql = substr($sql, 0, -1);
           
           //$sql.=$sql.$sqlExtend;
           if ($this->db->query($sql))
                return json_encode(array('status' => 1,'msg' => 'successfully scheduled!!!'));
           else
           {
               $this->deleteData($table,'scheduleId='.$scheduleId);
               trigger_error('problem in schedule insertion'.$this->db->error);
               return json_encode(array('status' => 0,'msg' => 'Problem while insertion!!!'));      
           }
           
       }
        
       /**
        * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
        * @param array $request it holds userid
        * @param type $session it holds session information
        * @since 5/4/2014
        * @uses function to get schedule list by user id
        * return json
        */
       function getScheduleList($request,$session)
       {
           //apply validation
            if(!isset($request['userId']) || $request['userId'] == '' || !is_numeric($request['userId']))
               return json_encode(array('status' => 0,'msg' => 'Invalid account!!!'));
           
           if($request['userId'] != $session['userid'])
               return json_encode(array('status' => 0,'msg' => 'Permission Denied!!!'));
           
           $userId = $this->db->real_escape_string($request['userId']);
           //$number = $this->db->real_escape_string($request['number']);
           
           $table = '91_schedules';
           
           $result = $this->selectData('*',$table,'createdBy='.$userId);
           
            if(!$result || $result->num_rows ==0)
           {
               trigger_error('problem while get shedules,userId='.$userId);
               return json_encode(array('status' => 0,'msg' => 'Problem while get shedules!!!'));
           }
           
           $data= array();
           
           //assign schedules information in usable format in array
           while($row = $result->fetch_array(MYSQLI_ASSOC))
           {
              $dataSchedule['scheduleId'] = $row['scheduleId']; 
              $dataSchedule['scheduleName'] = $row['scheduleName'];
              
              $data[] = $dataSchedule;
           
              unset($row);
              unset($dataSchedule);
           }
          
            return json_encode(array('status' => 1,'msg' => 'Schedules found successfully!!','schedules' => $data));
       }
       
       
       /**
        * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
        * @since 3/4/2014
        * @param array $request holds scheduleId to get schedule details
        * @param array $session holds all session information
        * @filesource
        * @return json
        */
       function getScheduleDetail($request,$session)
       {
           if(!isset($request['scheduleId']) || $request['scheduleId'] == '' || !is_numeric($request['scheduleId']))
               return json_encode(array('status' => 0,'msg' => 'Invalid schedule!!!'));
           
          if(!isset($session['userid']))
              return json_encode(array('status' => 0,'msg' => 'Not a valid user,Please try again or reload the page!!!'));
           
           $scheduleId = $this->db->real_escape_string($request['scheduleId']);
           //$number = $this->db->real_escape_string($request['number']);
           
           $table = '91_scheduleDetails';
           
           $result = $this->selectData('*',$table,'scheduleId='.$scheduleId);
           
           
           if(!$result || $result->num_rows ==0)
           {
               trigger_error('problem while get shedule details,scheduleId='.$scheduleId);
               return json_encode(array('status' => 0,'msg' => 'Problem while get shedule detail!!!'));
           }
           
           
           $data= array();
           
           //assign schedules information in usable format in array
           while($row = $result->fetch_array(MYSQLI_ASSOC))
           {
              $dataSchedule['sNo'] = $row['sNo']; 
              $dataSchedule['scheduleId'] = $row['scheduleId'];
              $dataSchedule['scheduleDay'] = $row['scheduleDay'];
              
//              if($row['displayClock'] == 0)
//              {    $dataSchedule['fromTime'] = date('h:i A',strtotime($row['fromTime']));
//                   $dataSchedule['toTime'] = date('h:i A',strtotime($row['toTime']));
//              
//              
//              }
//              else if($row['displayClock'] == 1)
//              {
                  $dataSchedule['fromHours'] = date('H',strtotime($row['fromTime']));
                  $dataSchedule['fromMinutes'] = date('i',strtotime($row['fromTime']));
                  $dataSchedule['toHours'] = date('H',strtotime($row['toTime']));
                  $dataSchedule['toHours'] = date('i',strtotime($row['toTime']));
                  
                  $dataSchedule['displayClock'] = $row['displayClock'];
              //}
              
              
              $data[] = $dataSchedule;
           
              unset($row);
              unset($dataSchedule);
           }
          
           return json_encode(array('status' => 1,'msg' => 'Schedule detail found successfully!!','scheduleDtl' => $data));
           
       }
       
       /**
        * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
        * @since 3/4/2014
        * @filesource
        * @uses function to delete schedule by schedule id
        * @param array $request it holds schedule id,userid,number
        * @param array $session it holds session user information
        * @return json
        */
        function deleteSchedule($request,$session)
        {
            //apply validation on all fields
            if(!isset($request['userId']) || $request['userId'] == '' || !is_numeric($request['userId']))
               return json_encode(array('status' => 0,'msg' => 'Invalid account!!!'));
           
           if(!isset($request['number']) || $request['number'] == '')
               return json_encode(array('status' => 0,'msg' => 'Not a valid number!!!'));
        
           if(!isset($request['scheduleId']) || $request['scheduleId'] == '' || !is_numeric($request['scheduleId']))
               return json_encode(array('status' => 0,'msg' => 'Not a schedule!!!'));
        
           if($request['userId'] != $session['userid'])
               return json_encode(array('status' => 0,'msg' => 'You don\'t have permission!!!'));
       
           
            $table = '91_numberscheduler';
           
            $scheduleId = $request['scheduleId'];
           $result = $this->deleteData($table,'sNo='.$scheduleId);
           
           if(!$result)
           {
               trigger_error('problem while get shedules,userId='.$userId .' and number="'.$number.'"');
               return json_encode(array('status' => 0,'msg' => 'Problem while delete shedules!!!'));
           }
           
           return json_encode(array('status' => 1,'msg' => 'Schedule deleted successfully!!!'));
           
           
        }
        
      
    
      
       
       
       

  
         
 }//end of class
?>