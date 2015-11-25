<?php
/**
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
 * @since 18/02/2014
 * @uses class contains the functions related to account manager activities
 */
include dirname(dirname(__FILE__)).'/config.php';
class Account_manager_class extends fun
{
    /**
     * define class variables here
     */
    
    var $toUser;
    var $fromUser;
    var $newClosingAmount;
    
 /**
  * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
  * @since 18/02/2014
  * @uses function to add accout manager
  * @filesource
  */
    function addAccountManager($request,$session)
    {
        //validation for permission
        if(!isset($session['isAdmin']) || $session['isAdmin'] != 1)
            return json_encode(array('status' => 0,'msg' => 'You have not permission to add account manager!!!'));
        //validate parameters
        if(!isset($request['fullName']) || preg_match(NOTALPHABATESPACE_REGX,$request['fullName']))
               return json_encode(array('status' => 0,'msg' => 'Please enter a valid full name!!!'));
        else if(!isset($request['username']) || preg_match(NOTUSERNAME_NORMAL_REGX,$request['username']))
               return json_encode(array('status' => 0,'msg' => 'Please enter a valid username!!!'));
        else if(!isset($request['password']) || preg_match(NOTPASSWORD_REGX,$request['password']))
               return json_encode(array('status' => 0,'msg' => 'Please enter a valid password only alpha numeric and following characters are allowed(@,$,},{,.,_,-,(,),],[,:)!!!'));
        else if(isset($request['cCode']) && preg_match(NOTMOBNUM_REGX,$request['cCode']))
               return json_encode(array('status' => 0,'msg' => 'Please select country!!!'));
        else if(isset($request['number']) && preg_match(NOTMOBNUM_REGX,$request['number']))
               return json_encode(array('status' => 0,'msg' => 'Please enter a valid number!!!'));
        else if(!isset($request['email']) || !preg_match(EMAIL_REGX,$request['email']))
               return json_encode(array('status' => 0,'msg' => 'Please enter a valid email!!!'));
            
         //to check  email address already exists or not 
        $table = '91_accountManagerDetails';
        
       
        //in case of update this section should not work
        if(!isset($request['type']))
            { $this->db->select('*')->from($table)->where("username = '" . trim($request['username']) . "'");
            $qur = $this->db->getQuery();
            $result = $this->db->execute();

            ////log error
            if(!$result)
                trigger_error('problem while get account manager details ,query:'.$qur);

            if ($result->num_rows > 0) {
                return json_encode(array("status" => 0, "msg" => "This username address already registered!"));
            }
         }
         //set features
         if(isset($request['editFund']) && $request['editFund'] == 'on' )
             $feature[] = array('featureName' => 'edit Fund','status' => 1);
         else
             $feature[] = array('featureName' => 'edit Fund','status' => 0);
         
          if(isset($request['readOnly']) && $request['readOnly'] == 'on' )
             $feature[] = array('featureName' => 'read Only','status' => 1);
         else
             $feature[] = array('featureName' => 'read Only','status' => 0);
         
         $userName = $this->db->real_escape_string(trim($request['username']));
         $fullName = $this->db->real_escape_string(trim($request['fullName']));
         
         //prepare data to insert in 91_accountManagerDetails
          $data = array("userName" => $userName,
                        "password" => $this->db->real_escape_string(trim($request['password'])),
                        "fullName" => $fullName,
                        "cCode" => $this->db->real_escape_string(trim($request['cCode'])),
                        "number" => $this->db->real_escape_string(trim($request['number'])),
                        "email" => $this->db->real_escape_string(trim($request['email'])),
                        "deleted" => 0,
                        "admin" => $session['id']        
                        );
          
         
          $table = "91_accountManagerDetails";
          $featureTable = '91_AcmFeatures';
         
         if(isset($request['type']) && $request['type'] == 1 ) //for update
         {
             //validate account id
             if(!isset($request['acmId']) || !is_numeric($request['acmId']) )
                return json_encode(array('status' => 0,'msg' => 'Please select account manager for update!!!'));
             else
                 $acmId = $request['acmId'];
             
             //condition
             $condition = 'acmId='.$acmId;
             
             $res = $this->updateWithEncryption($table,$data,$condition);
             
	    
             //validate result
             if(!$res)
             {
                 trigger_error('problem while account manager updation,details'.json_encode($data));
             }
             
             //update features
              foreach($feature as $value)
              {
                    $fCondition = 'acmId='.$acmId.' and featureName="'.$value['featureName'].'"';
                    $value['acmId'] = $acmId;
                    $resFet = $this->updateData($value, $featureTable,$fCondition);
                    unset($value);
                    if(!$resFet)
                    {
                        //$this->deleteData($table, "acmId = ".$acmId); //delete inserted details
                        trigger_error('problem while save feature details ,data array:'.json_encode($value));
                        return json_encode(array('status' => 0,'msg' => 'Problem while updation for features!!!'));
                    }
              }
             
             return json_encode(array('status' => 1,'msg' => 'account manager details successfully updated!!!'));
             
         }
         else //for add
         {
            //insert data 
            $res = $this->insertWithEncryption($table,$data);
            if($res)
            {
                //set last id
                $lastId = $this->db->insert_id;
                
               
                
                foreach($feature as $value)
                {
                    $value['acmId'] = $lastId;
                    $resFet = $this->insertData($value, $featureTable);
                    unset($value);
                    if(!$resFet)
                    {
                        $this->deleteData($table, "acmId = ".$lastId); //delete inserted details
                        trigger_error('problem while save feature details ,data array:'.json_encode($value));
                        return json_encode(array('status' => 0,'msg' => 'Problem while insertion for features!!!'));
                    }
                }
                
                $data['acmId'] = $lastId;
                
                return json_encode(array('status' => 1,'msg' => 'Account manager successfully added!!!','detail' => array($data)));
                
            }
            else
            {
                trigger_error('problem while save ACM details ,data array:'.json_encode($data));
                return json_encode(array('status' => 0,'msg' => 'Problem while insertion!!!'));
            }
            
        }
    } //end of addAccountManager function
  
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 18/02/2014
     * @uses check account manager username exists or not
     * @filesource
     */
  function checkAcmExists($request)
  {

    if(!isset($request['username']) || preg_match(NOTUSERNAME_NORMAL_REGX,$request['username']))
               return 0;


      if(isset($request['username']))
            $username = $this->db->real_escape_string($request['username']);
        
        $table = '91_accountManagerDetails';
        $this->db->select('username')->from($table)->where("username = '" . $username . "' ");

        $result = $this->db->execute();

        // processing the query result
        if ($result->num_rows > 0) {
            return 0; //echo "Sorry username already in use";
            exit();
        }
        else
        {
            return 1;
            exit();
        }
      
  }
  
 /**
  * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
  * @since 
  * @filesource
  */
    function allManagerList($request,$session)
    {
    
       
        $limit = 10;

        if($session['isAdmin'] != 1)
        {
            return json_encode(array('status' => 0,'msg' => "permission denied"));
        }
        
        if(isset($request['pageNo']) and is_numeric($request['pageNo']))
          $pageNo = $request['pageNo'];
        else
          $pageNo = 1;
         
        $skip = $limit*($pageNo - 1);
        
        $withQuery = '';
        //check of search string
        if(isset($request['q']) && $request['q'] != '')
        {
            $q = $this->db->real_escape_string($request['q']);
            $withQuery = " userName LIKE '%$q%' or fullName LIKE '%$q%' or email LIKE '%$q%' or number LIKE '%$q%' and";
            
        }
        
        /**get admin id 
         * 
         */
        $adminId = $session['id'];
        
        $table = '91_accountManagerDetails';
        $this->db->select('SQL_CALC_FOUND_ROWS deleted,admin,email,number,cCode,fullName,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,userName,acmId')->from($table)->where($withQuery.' admin='.$adminId.' and deleted=0 ')->limit($limit)->offset($skip);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
        $countRes = mysqli_fetch_assoc($resultCount);
        
       
        //log error
        if(!$result)
            trigger_error('problem while get account manager details,query:'.$qur);
        
        
        $resultArray = array();
        $resultArray['detail']= array();
        if ($result->num_rows > 0) 
        {

            while ($row= $result->fetch_array(MYSQLI_ASSOC) ) 
            {
                $resultArray['detail'][]=$row;					
            }
        }
        
        $pages = ceil($countRes['totalRows']/$limit);
        $resultArray['pages'] = $pages;
        
       
        return json_encode($resultArray);
       
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 18/02/2014
     * @uses function to get account manager admin id 
     * @filesource
     */
    function getAcmAdmin($acmId)
    {
        //validate acm id
        if(empty($acmId) || preg_match(NOTNUM_REGX, $acmId))
            return array();
        
        $table = '91_accountManagerDetails';
        
        $result = $this->selectData('deleted,admin,email,number,cCode,fullName,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,userName,acmId',$table,'acmId='.$acmId);
        
        if(!$result || ($result->num_rows == 0))
        {
            trigger_error('problem while getting admin from table!!');
            return 0;
        }
        
        $res = $result->fetch_array(MYSQLI_ASSOC);
        
        return $res;
    }
    
   /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 18/02/2014
     * @uses function to get account manager admin id 
     */
    function getAcmFetures($acmId)
    {
         //validate acm id
        if(empty($acmId) || preg_match(NOTNUM_REGX, $acmId))
            return 0;
        
        $table = '91_AcmFeatures';
        
        $result = $this->selectData('*',$table,'acmId='.$acmId);
        
        if(!$result || ($result->num_rows == 0))
        {
            trigger_error('problem while getting admin from table!!');
            return 0;
        }
        
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $resultArr[] = $row;
            unset($row);
        }
        
        return $resultArr;
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 19/02/2014
     * @uses To delete account manager
     */
    function deleteAcm($request,$session)
    {
        //validate account manager id
        if(empty($request['acmId']) && preg_match(NOTNUM_REGX, $request['acmId']))
            return json_encode(array('status' => 0,'msg' => 'Not a valid account manager!!!'));
        else
            $acmId = $this->db->real_escape_string($request['acmId']);
        
        //check for admin
        if(!isset($session['isAdmin']) || $session['isAdmin'] != 1 )
            return json_encode(array('status' => 0,'msg' => 'Permission denied!!!'));
        
        //get admin of this acm
        $acmDtl = $this->getAcmAdmin($acmId);
        
        //check for permission
        if($acmDtl['admin'] != $session['id'])
            return json_encode(array('status' => 0,'msg' => 'You dont have permission to delete this account manager!!!'));
        
        $table = '91_accountManagerDetails';
        
        $updateData = array('deleted' => 1);
        
        $condition = 'acmId='.$acmId;
        
        $updateRes = $this->updateData($updateData,$table,$condition);
        
        if(!$updateRes)
        {
            trigger_error('problem while delete account manager,condition:'.$condition);
            return json_encode(array('status' => 0,'msg' => 'Problem while delete account manager!!!'));
        }
        
       return json_encode(array('status' => 1,'msg' => 'Accuont manager successfully deleted!!!'));
        
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 19/02/2014
     * @uses function use to account manager login
     */
     function acmLogin($request)
    {
	
        //validate parameters
        if(empty($request['uname']) || preg_match(NOTUSERNAME_NORMAL_REGX,$request['uname']))
               return json_encode(array('status' => 0,'msg' => 'Please enter a valid username!!!'));
        else if(empty($request['pwd']) || preg_match(NOTPASSWORD_REGX,$request['pwd']))
               return json_encode(array('status' => 0,'msg' => 'Please enter a valid password!!!'));
        
        $table = '91_accountManagerDetails';
        
//	$ip = $this->getUserIP();
//	
//	if(TESTENVIRONMENT == 0 && $ip != '111.118.250.236')
//	{
//	    trigger_error('unauthorised try found,detail'.  json_encode($request));
//            return json_encode(array('status' => 0,'msg' => 'Permission denied!'));
//	}
	
	
        $condition = 'username="'.$this->db->real_escape_string($request['uname']).'" and password=AES_ENCRYPT("'.$request['pwd'].'","'.ENCRYPT_KEY.'") and deleted=0';
        
        $result = $this->selectData('*',$table,$condition);
        
		
		
        if(!$result)
        {
            trigger_error('problem while getting account manager details,condition:'.$condition);
            return json_encode(array('status' => 0,'msg' => 'Problem while login!!!'));
        }
        
	
	
	
        if($result->num_rows > 0)
        {
            
           $row = $result->fetch_array(MYSQLI_ASSOC);
           extract($row);          //acmId,userName,password,admin
           session_start();
           session_unset();
           session_destroy();
           session_start();
           
	    $adminNo = $cCode.$number;
         
            $code = $this->generatePassword();
           
		  //var_dump(CLASS_DIR);
		  //die(CLASS_DIR."sendSmsClass.php");
            include_once(CLASS_DIR."sendSmsClass.php");
	     
            $smsObj = new sendSmsClass();
            $param['to'] =   $adminNo;
            $param['text'] = "Enter this confirmation code " . $code . " to confirm your mobile number.";
			
            $smsObj->sendMessagesGlobal($param);
           
            $_SESSION['accountManagerId'] = $acmId;


            #update code into database
            $updateData = array('acmCode' => $code);
            $condition = 'acmId='.$acmId;
	      
            $result = $this->updateWithEncryption($table,$updateData,$condition);
	   
	  
            $this->redirect(ADMIN_DIR.'secureCode.php');
          
        }
        else
            return json_encode(array('status' => 0,'msg' => 'Invalid username or password!!!'));
        
    }
    
    function accountManagerCodeVerfy($param,$acmId){
        
	
	
	
           session_unset();
           session_destroy();
           session_start();
	
        if(empty($acmId) || preg_match(NOTNUM_REGX, $acmId))
        return json_encode(array("status" => "error","msg" => "Not a valid admin."));

        if(!preg_match('/^[0-9]{4}/', $param['code']))
        return json_encode(array("status" => "error","msg" => "please enter valid code."));

        $table = '91_accountManagerDetails';
       
        $qury = $this->db->select('fullName,userName,admin,cCode,number,AES_DECRYPT(acmCode,"'.ENCRYPT_KEY.'") as acmCode')->from($table)->where('acmId='.$acmId);
        $this->db->getQuery();

        #execute the query
        $result = $this->db->execute();

        if(!$result)
            return json_encode (array("msg"=>"Error in fetching user details","status"=>"error"));
        
        #fetching the array element and putting in a varible $pwd
        $row = $result->fetch_array(MYSQLI_ASSOC);
        
        if($row['acmCode'] == trim($param['code'])){
            
                # set session userid
                $_SESSION['acmId'] = $acmId;
                $_SESSION['acmAdmin'] = $row['admin'];

                $_SESSION['contactNo'] = $row['cCode'].$row['number'];
                $this->initiateSession($row['admin']);

                $_SESSION['isAdmin'] = 1;
                $_SESSION['username'] = $row['userName'];
                $_SESSION['name'] = $row['fullName'];
                
                return json_encode(array("status" => "success","msg" => "verification code successfully send."));
                
                               
        }else
            return json_encode(array("status" => "error","msg" => "Your verification code is not matched."));
        
        
    }
   
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com> 
     * @since 20/02/2014
     * @uses to change account manager password
     */
     function changeAcmPwd($currPwd, $newPwd) 
     {
       

        if(empty($_SESSION['acmId']))
            return json_encode(array("msgtype" => "error", 
                                    "msg" => "Session expired Please Login again!!!"));    
        #getting the session acm id
        $acmId = $_SESSION['acmId'];

       if(!preg_match('/^[a-zA-Z0-9\@\_\-\!\$\(\)\?\[\]\{\}\s]+/', $newPwd))
        {
           return json_encode(array("msgtype" => "error", 
                                    "msg" => "please enter a valid password must not containg any spacial character other than '@_-!$()[]{}?' "));
        }

        if(strlen($newPwd) > 25)
        {
           return json_encode(array("status" => "error", 
                                    "msg" => "Please enter password less then 25 character"));
        }
        
        #$table name of the table in database
        $table = '91_accountManagerDetails';
        
        
        #access password by database of the current account manager
        $this->db->select('userName,AES_ECRYPT(password,"'.ENCRYPT_KEY.'") as password')->from($table)->where("acmId = " . $acmId . "");
        $sQL  = $this->db->getQuery();

        #execute the query
        $result = $this->db->execute();

        if(!$result)
        {
            trigger_error('problem while getting account manager details,SQL:'.$sQL);
            return json_encode (array("msg"=>"Error in fetching user details","status"=>"error"));
        }
        #fetching the array element and putting in a varible $pwd
        $row = $result->fetch_array(MYSQLI_ASSOC);

        #store the particular column data
        $pwd1 = $row['password'];
//        $sipFlag = $row['sipFlag'];
//        $userName = $row['userName'];

        #check curr_pwd is equal to database user password
        if ($pwd1 != $currPwd) {
            #echo "Please enter correct password";
            return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Correct Password'));
        } else {
            $newPwd = $this->db->real_escape_string($newPwd);
            
            #data to pass in update command that is new password
            $data = array("password" => $newPwd);

            #update the table by new password corresponding to the userid
            //$query = $this->db->update($table, $data)->where("acmId = '" . $acmId . "' ");
	    $query = $this->updateWithEncryption($table,$data,"acmId = '" . $acmId . "' ");
            #get the query sentence
            $sQl = $this->db->getQuery($query);

            #execute the query
            $result1 = $this->db->execute();

            #if query executed then
            if ($result1) 
            {
                
                #echo "password changed successfully"
                return json_encode(array('msgtype' => 'success', 'msg' => 'Password Changed Successfully'));
            }
            #if query is not successfull    
            else 
            {
                trigger_error('problem while updating account manager password details,SQL:'.$sQl);
                #weak password please chose another one.
                return json_encode(array('msgtype' => 'error', 'msg' => 'Password Is Too Weak'));
                ;
            }
        }
        
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 20/02/2014
     * @uses function  to get privilege
     * @filesource
     * @param $acmId
     */
    function getAcmPrivilege($acmId=null)
    {
        //validate account manager id
        if(empty($acmId) || preg_match(NOTNUM_REGX, $acmId))
            return array();
        

        //get features from database
        $table = '91_AcmFeatures';
        $result = $this->selectData('*',$table,'acmId='.$acmId);
        
        //validate result
        if(!$result)
        {
            trigger_error('problem while getting features for acmId:'.$acmId);
            return array();
        }
        
        $data= array();
        //get feature and set in array
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $data[str_replace(' ', '', $row['featureName'])] = $row['status'];
        }
        
        return $data;
    }
    
    
      /**
     * @author Nidhi <nidhi@walkover.in>
     * @since 
     * @filesource
     */
    
    function allBlockUserList($request,$session)
    {
        $limit = 10;

        if(empty($session['isAdmin'])  || $session['isAdmin'] != 1)
        {
            return json_encode(array('status' => 0,'msg' => "permission denied"));
        }
        
        if(!empty($request['pageNo']) and is_numeric($request['pageNo']))
          $pageNo = $request['pageNo'];
        else
          $pageNo = 1;
         
        $skip = $limit*($pageNo - 1);
        
        /**get admin id 
         * 
         */
        $adminId = $session['id'];
        
        $table = '91_blockIp';
        
        $this->db->select('SQL_CALC_FOUND_ROWS *')->from($table)->orderBy('dateTime DESC')->limit($limit)->offset($skip);
        
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
        $countRes = mysqli_fetch_assoc($resultCount);
        
       
        //log error
        if(!$result)
            trigger_error('problem while getting block user details,query:'.$qur);
        
        
        $resultArray = array();
        $resultArray['detail'] = array();
        if ($result->num_rows > 0) 
        {

            while ($row= $result->fetch_array(MYSQLI_ASSOC) ) 
            {
                $accmanager =   $this->getAcmName($row['blockedBy']);
                if(!$accmanager)
                    $acmName = "Shubhendra Agrawal";
                else
                    $acmName = $accmanager['userName'];
                
               $row['acmName'] = $acmName;
                
                $resultArray['detail'][]=$row;					
            }
        }
        
        $pages = ceil($countRes['totalRows']/$limit);
        $resultArray['pages'] = $pages;
        
       
        return json_encode($resultArray);
       
    }
    
      /**
     * @author Nidhi <nidhi@walkover.in>
     * @since 18/02/2014
     * @uses function to get account manager admin id 
     * @filesource
     */
    function getAcmName($acmId)
    {
        //validate acm id
        if(empty($acmId) || preg_match(NOTNUM_REGX, $acmId))
            return 0;
        
        $table = '91_accountManagerDetails';
        
        $acmId = $this->db->real_escape_string($acmId);

        $result = $this->selectData('fullName,userName',$table,'acmId='.$acmId);
        
        if(!$result)
        {
            trigger_error('problem while getting admin from table!!');
            return 0;
        }
        
        $res = $result->fetch_array(MYSQLI_ASSOC);
        
        return $res;
    }
    
    function loginAcmValidate()
    {
       if(isset($_SESSION['acmId']))
           return 1;
       else
           return 0;
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @param type $acmId
     * @return array
     */
    function getAcmInfo($acmId)
    {

        //validate acm id
        if(empty($acmId) || preg_match(NOTNUM_REGX, $acmId))
            return array();

        $acmId = $this->db->real_escape_string($acmId);
        #$table name of the table in database
        $table = '91_accountManagerDetails';
        
        
        #access password by database of the current account manager
        $this->db->select('acmId,deleted,admin,AES_DECRYPT(acmCode,"'.ENCRYPT_KEY.'") as acmCode,email,number,cCode,fullName,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,userName,acmId')->from($table)->where("acmId = " . $acmId . "");
        
	 $sQL  = $this->db->getQuery();

        #execute the query
        $result = $this->db->execute();

        if($result && $result->num_rows > 0)
       {
           $userDetail = $result->fetch_array(MYSQLI_ASSOC);
       }
       else 
           $userDetail = array();
        
       return $userDetail;
    }
    
    /*
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 01/04/2014
     * @desc get all account manager id and name 
     */
    function getallAcccountManager($adminId,$type){
      
         //validate acm id
        if(empty($adminId) || preg_match(NOTNUM_REGX, $adminId))
            return array();

        if($type != 1)
        {
            return array();
        }
       
       
        $table = '91_accountManagerDetails';
        $this->db->select('*')->from($table)->where('admin='.$adminId.' and deleted=0');
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log error
        if(!$result)
            trigger_error('problem while get manageClient details,query:'.$qur);
       
        if ($result->num_rows > 0) 
        {
            while ($row= $result->fetch_array(MYSQL_ASSOC) ) 
            {
                $resultArray[$row['acmId']]=$row['userName'];					
            }
        }else
            $resultArray = array();
       
        return json_encode($resultArray);
       
  
    }
    
    /**
     * @author  sudhir pandey <sudhir@hostnsoft.com>
     * @since 11-12-2014
     * @desc function use to send sms to admin 
     * 
     */
    function accManagerForgetPass($adminName,$smsCall){
        
        if(empty($adminName) || preg_match(NOTUSERNAME_NORMAL_REGX,$adminName))
               return json_encode(array('status' => "error",'msg' => 'Please enter a valid username!!!'));
        
        $table = '91_accountManagerDetails';
        
	$condition = 'username="'.$this->db->real_escape_string($adminName).'" and deleted=0';
        
        $result = $this->selectData('*',$table,$condition);
        
		
		
        if(!$result)
        {
            trigger_error('problem while getting account manager details,condition:'.$condition);
            return json_encode(array('status' => "error",'msg' => 'Problem while login!!!'));
        }
        
        if($result->num_rows > 0)
        {
            
           $row = $result->fetch_array(MYSQLI_ASSOC);
           extract($row);          //acmId,userName,password,admin
           session_start();
           session_unset();
           session_destroy();
           session_start();
           
	    $adminNo = $cCode.$number;
         
            $code = $this->generatePassword();
           
		  //var_dump(CLASS_DIR);
		  //die(CLASS_DIR."sendSmsClass.php");
            include_once(CLASS_DIR."sendSmsClass.php");
	     
            $smsObj = new sendSmsClass();
            $param['to'] =   $adminNo;
            $param['text'] = "Enter this confirmation code " . $code . " to reset your password.";
			
            $smsObj->sendMessagesGlobal($param);
           
            $_SESSION['accountManagerId'] = $acmId;


            #update code into database
            $updateData = array('acmCode' => $code);
            $condition = 'acmId='.$acmId;
	      
            $result = $this->updateWithEncryption($table,$updateData,$condition);
	   
	  
           return json_encode(array('status' => "success",'msg' => 'confirmation code successfully send'));
          
        }
        else
            return json_encode(array('status' => "error",'msg' => 'Invalid username or password!!!'));
        
        
        
    }
    
    function verifiedForgetPass($code,$acmId){
        
        session_unset();
        session_destroy();
        session_start();

        if(empty($acmId) || preg_match(NOTNUM_REGX, $acmId))
        return json_encode(array("status" => "error","msg" => "Not a valid admin."));

        if(!preg_match('/^[0-9]{4}/', $code))
        return json_encode(array("status" => "error","msg" => "please enter valid code."));

        $table = '91_accountManagerDetails';
       
        $qury = $this->db->select('fullName,userName,admin,cCode,number,AES_DECRYPT(acmCode,"'.ENCRYPT_KEY.'") as acmCode')->from($table)->where('acmId='.$acmId);
        $this->db->getQuery();

        #execute the query
        $result = $this->db->execute();

        if(!$result)
            return json_encode (array("msg"=>"Error in fetching user details","status"=>"error"));
        
        #fetching the array element and putting in a varible $pwd
        $row = $result->fetch_array(MYSQLI_ASSOC);
        
        if($row['acmCode'] == trim($code)){
            
                # set session userid
                $_SESSION['forgotacmId'] = $acmId;
                return json_encode(array("status" => "success","msg" => "Successfully verified."));
                
                               
        }else
            return json_encode(array("status" => "error","msg" => "Your verification code is not matched."));
        
        
    }
    
    
    function resetAdminPassword($param,$acmId){
        
        session_unset();
        session_destroy();
        session_start();

        if(empty($acmId) || preg_match(NOTNUM_REGX, $acmId))
        return json_encode(array("status" => "error","msg" => "Not a valid admin."));
        
        if(!isset($param['new_pwd']) || preg_match(NOTPASSWORD_REGX,$param['new_pwd']))
        return json_encode(array('status' => "error",'msg' => 'Please enter a valid password only alpha numeric and following characters are allowed(@,$,},{,.,_,-,(,),],[,:)!!!'));
       
        $data = array("password" => $this->db->real_escape_string(trim($param['new_pwd'])));
        $table = "91_accountManagerDetails";
        $condition = 'acmId='.$acmId;
        $res = $this->updateWithEncryption($table,$data,$condition);
             
	if(!$res)
        {
            return json_encode(array('status' => "error",'msg' => 'There was an internal error, please try again.'));
        }
        
        return json_encode(array('status' => "success",'msg' => 'Password successfully updated.'));
        
    }
   
}//end of class
?>