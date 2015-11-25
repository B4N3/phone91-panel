<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */

include dirname(dirname(__FILE__)) . '/config.php';
class dialPlanClass extends fun{
    
    var $validatePlanNameFlag = false;
    var $msg = "";
    function validatePlanName($planName)
    {
        if(preg_match(NOTALPHANUM_REGX,$planName) || strlen($planName) < 3 || strlen($planName) > 40 )
        {
            $this->msg = "Error invalid plan name must be alpha numeric and not more then 40 characters.";
            return 0;
        }
        
        $this->validatePlanNameFlag = true;
        return 1;
    }
    function checkDialPlanExist($planName)
    {
        $planName =trim($planName);
        if(!$this->validatePlanName($planName) || $this->validatePlanNameFlag == false)
                return 0;
        $selRes = $this->selectData("planName", "91_dialPlanDetail","planName = '".$planName."'");
        
        
        if($selRes && $selRes->num_rows < 1 )
            return 1;
        else
        {
            $this->msg = "Error plan with this name already exist please try with another plan name";
            return 0;
        }
                
        
    }
    
    function addDialPlan($planName,$userId)
    {
        $planName = trim($planName);
        if(!$this->validatePlanName($planName) || $this->validatePlanNameFlag == false)
                return json_encode(array("msg"=>$this->msg,"status"=>"error"));
        
        if(preg_match(NOTNUM_REGX,$userId) || $userId == "")
                return json_encode(array("msg"=>"Error user please login again","status"=>"error"));
        
        
        if(!$this->checkDialPlanExist($planName))
                return json_encode(array("msg"=>$this->msg,"status"=>"error"));
        
        $data = array("planName"=>$planName,"userId"=>$userId);
        $table = "91_dialPlanDetail";
        $planInsertResult = $this->insertData($data, $table);
        
        if($planInsertResult && $this->db->affected_rows > 0)
        {
            $planId = $this->db->insert_id;
            $countryName = "Default"; 
            $this->addDialPlanCountry($countryName,$userId,$planId);
            return json_encode(array("msg"=>"successfuly added the plan name","status"=>"success","lastId"=>$this->db->insert_id));
        }
        else
            return json_encode(array("msg"=>"Error adding plan please try again","status"=>"error"));
        
    }
    
    function getPlanList($type = 1,$pageNo,$userId,$planName=NULL)
    {
        if(preg_match('/[^0-9]+/', $userId) || $userId == "")
                return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
        
        
        $condition = " userId=".$userId." ";
        $limit = 10;
        if($type == 1)
        {
            $columns = "*";
        }
        else
        {
            if(!$this->validatePlanName($planName) || $this->validatePlanNameFlag == false)
                return json_encode(array("msg"=>$this->msg,"status"=>"error"));
            $columns = "planId,userId,planName";
            
            $planName = $this->db->real_escape_string($planName);
            $condition .= " and planName LIKE '".$planName."%'";
        }
        if(!is_numeric($pageNo))
            return json_encode(array("msg"=>"Error Invalid page number","status"=>"error"));
        
        $skip = ($pageNo-1) * $limit;
        
        $table = "91_dialPlanDetail";
        $condition .= " limit ".$limit." OFFSET ".$skip;
       
        $selRes = $this->selectData($columns, $table,$condition);
     
        if(!$selRes || $selRes->num_rows < 1)
            return json_encode(array("msg"=>"No data found","status"=>"error"));
        else {
            $resultCount = $this->selectData('count(*) as totalRows', $table);
            if(!$resultCount)
                return json_encode (array("msg"=>"Error fetching total records","status"=>"error"));
            
            
        
            $countRes = $resultCount->fetch_array(MYSQLI_ASSOC);
            $count = ceil($countRes['totalRows']/$limit);
            while($row = $selRes->fetch_array(MYSQLI_ASSOC))
            {
                $data[] = $row;
            }
            return json_encode(array("data"=>$data,"count"=>$count));
        }
        
    }
    
    function addDialPlanCountry($countryName,$userId,$planId)
    {
        $countryName = strtolower(trim($countryName));
        $userId = trim($userId);
        $planId = trim($planId);
        if(preg_match(NOTALPHABATESPACE_REGX, $countryName) || $countryName == "" || strlen($countryName) > 30)
                return json_encode(array("msg"=>"Error Invalid country name only apphabet and space is allowed must not be more then 30 character","status"=>"error"));
        
        if(preg_match(NOTNUM_REGX, $userId) || $userId == "")
                return json_encode(array("msg"=>"Error Invalid user please login","status"=>"error"));
        
        if(preg_match(NOTNUM_REGX, $planId) || $planId == "")
                return json_encode(array("msg"=>"Error Invalid plan please select a plan","status"=>"error"));
        
        $data = array("dialPlanId"=>$planId,"country"=>$countryName,"userId"=>$userId);
        $table = "91_dialPlanCountryDetails";
        $selResult = $this->selectData("*",$table,"dialPlanId='".$planId."' and country='".$countryName."'");
        if(!$selResult)
            return json_encode (array("msg"=>"Error getting details please contact provider","status"=>"error"));
        elseif($selResult && $selResult->num_rows > 0)
            return json_encode (array("msg"=>"Country Name already exist please insert a unique country name","status"=>"error"));
        
        $result = $this->insertData($data,$table);
        
        if(!$result || $this->db->affected_rows < 1)
            return json_encode (array("msg"=>"Error processing data please contact provider","status"=>"error"));
        else
            return json_encode (array("msg"=>"Successfuly Added country name","status"=>"success","lastInsertId"=>$this->db->insert_id));
    }
    
    function getPrefixDetails($planId,$userId,$pageNumber,$search=NULL,$keyword=NULL)
    {
        $planId = trim($planId);
        $userId = trim($userId);
        $keyword = trim($keyword);
        $countryFlag = 0;
        $limit = 10;
        if(preg_match(NOTNUM_REGX, $userId) || $userId == "")
                return json_encode(array("msg"=>"Error Invalid user please login","status"=>"error"));
        
        if(preg_match(NOTNUM_REGX, $planId) || $planId == "")
                return json_encode(array("msg"=>"Error Invalid plan please select a plan","status"=>"error"));
        
        
        if(!is_null($keyword) && $search == 1 && (preg_match('/[^a-zA-Z0-9\s\+\*\#]+/',$keyword) || $keyword == ""))
                    return json_encode (array("msg"=>"Error Invalid country please try with proper country name","status"=>"error"));
        
        
        if (isset($pageNumber) && $pageNumber != "")
            $skip = ($pageNumber - 1) * $limit;
        else
            $skip = 0;
        
        
        $table = "91_dialPlanCountryDetails";
        if($search == 1 && !preg_match('/[^a-zA-Z\s]+/',$keyword))
        {      
            $conditon = "dialPlanId = ".$planId." and country like '".$keyword."%' and userId=".$userId."";
            $selRes = $this->selectData("slno,dialPlanId,country", $table,$conditon);
//            echo $this->querry;
            $countryFlag = 1;
        }
        else
        {
            $selRes = $this->selectData("slno,dialPlanId,country",$table,"dialPlanId = '".$planId."' and userId = '".$userId."' order by slno DESC");
            
        }
        if($selRes && $selRes->num_rows > 0)
        {
            
            while($row = $selRes->fetch_array(MYSQLI_ASSOC))
            {
                $data[$row['slno']] = $row;
                $countryArr[] = $row['slno']; 
                
            }
            
//        }
//        
//        if(is_array($data) && !empty($data))
//        {
            $conditionForRouteTable = "dialPlanId = '".$planId."'";
            
            if($search == 1 && $countryFlag == 1)
            {
                $commaSeprateCountryId = implode("','",$countryArr);
                $conditionForRouteTable .= " and country IN ('".$commaSeprateCountryId."')";
            }
            elseif ($search == 1) {
                $conditionForRouteTable .= " and userPrefix like ('".$keyword."%')";
            }
            $selRouteCountRes = $this->selectData("count(*) as totalRows","91_dialPlanRoute",$conditionForRouteTable);
//           echo $this->querry;
            if(!$selRouteCountRes)
                return json_encode(array("msg"=>"Error fetching details please try again","status"=>"error"));
            
            $totalRowsArr = $selRouteCountRes->fetch_array(MYSQLI_ASSOC);
            $totalRows = $totalRowsArr['totalRows'];
            
            
//            if($totalRows != 0)
            {

                $conditionForRouteTable .= " limit ".$skip.",".$limit;
                $selRouteRes = $this->selectData("*","91_dialPlanRoute",$conditionForRouteTable);
    //            echo $this->querry;
                
                logmonitor("phone91-query",$this->querry);
                if($selRouteRes)
                {
                    while($rowRoute = $selRouteRes->fetch_array(MYSQLI_ASSOC))
                    {
                            $tempArr[$rowRoute['country']] = 1;
                            $data[$rowRoute['country']]['prefix'][] = $rowRoute; 
                    }

                    if($search == 1 && $countryFlag != 1)
                        $data = array_intersect_key($data,$tempArr);


                }
                else
                    return json_encode(array("msg"=>"Error getting records","status"=>"error"));
            }
            
            $pages = ceil($totalRows / $limit);
            
        }
        elseif($selRes && $selRes->num_rows == 0)
        {
            return json_encode(array("msg"=>"No record found please add some data","status"=>"error"));
        }
        else
        {
            return json_encode(array("msg"=>"Error fetching details please try again later","status"=>"error"));
        }
        $data['count'] = $pages;
        
        return json_encode($data);
    }
    
    function addPrefixDetails($dialPlanId,$countryId,$prefix,$routeId,$userId)
    {
        if(preg_match(NOTNUM_REGX,$dialPlanId) || $dialPlanId == "")
                return json_encode(array("msg"=>"Error Invalid Dial Plan Id Please Select a proper dial plan","status"=>"error"));
        if(preg_match(NOTNUM_REGX,$countryId) || $countryId == "")
                return json_encode(array("msg"=>"Error Invalid Country Please Contact Provider","status"=>"error"));
        if(preg_match('/[^0-9\*\#\+]/',$prefix) || $prefix == "" || strlen($prefix) > 10)
                return json_encode(array("msg"=>"Error Invalid Prefix Only Number and (*,#,+) are allowed","status"=>"error"));
        if(preg_match(NOTNUM_REGX,$routeId) || $routeId == "")
                return json_encode(array("msg"=>"Error Invalid route Please Select a proper route","status"=>"error"));
        if(preg_match(NOTNUM_REGX,$userId) || $userId == "")
                return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
        
        $selRes = $this->selectData("planName","91_dialPlanDetail","id='".$dialPlanId."' and userId='".$userId."'");
        if(!$selRes || $selRes->num_rows < 1 )
            return json_encode(array("msg"=>"You dont have permission to edit this dialPlan","status"=>"error"));
        
        $data = array("dialPlanId"=>$dialPlanId,"userPrefix"=>$prefix,"routeId"=>$routeId,"country"=>$countryId);
        $table = "91_dialPlanRoute";
        
        $this->db->insert($table,$data)->onDuplicate("routeId='".$routeId."'");
//        echo $this->db->getQuery();
//        $this->db->addCommand('ON DUPLICATE KEY UPDATE',"routeId='".$routeId."', country='".$countryId."'");
        $insertRes = $this->db->execute();
        
        if($insertRes && $this->db->affected_rows > 0)
            return json_encode(array("msg"=>"Successfuly inserted data","status"=>"success"));
        else
            return json_encode(array("msg"=>"Error updating data please try again","status"=>"error"));
    }
    
    function deletePrefixDetails($dialPlanId,$serialNum,$userId)
    {
        if(preg_match(NOTNUM_REGX,$dialPlanId) || $dialPlanId == "")
                return json_encode(array("msg"=>"Error Invalid Dial Plan Id Please Select a proper dial plan","status"=>"error"));
        if(preg_match(NOTNUM_REGX,$serialNum) || $serialNum == "")
                return json_encode(array("msg"=>"Error Invalid Prefix please try again","status"=>"error"));
        if(preg_match(NOTNUM_REGX,$userId) || $userId == "")
                return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
        
        
        $selRes = $this->selectData("planName","91_dialPlanDetail","planId='".$dialPlanId."' and userId='".$userId."'");
        if(!$selRes || $selRes->num_rows < 1 )
            return json_encode(array("msg"=>"You dont have permission to edit this dialPlan","status"=>"error"));
        $table = "91_dialPlanRoute";
        $condition = "slno=".$serialNum." and dialPlanId=".$dialPlanId."";
        $delRes = $this->deleteData($table, $condition);
        if($delRes && $this->db->affected_rows > 0)
            return json_encode(array("msg"=>"Successfuly Deleted data","status"=>"success"));
        else
            return json_encode(array("msg"=>"Error deleting data please try again","status"=>"error"));
    }
    
    
    
    public function deleteDialPlan($planId, $userId) {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @desc : function is used to delete the plan from the table
         */
        
        if(preg_match('/[^0-9]+/', $planId) || $planId == "")
                return json_encode (array("msg"=> "Invalid tariff please select a proper tariff","staus"=>"error"));
        if(preg_match('/[^0-9]+/', $userId) || $userId == "")
                return json_encode (array("msg"=> "Invalid user please login","staus"=>"error"));
        
        $planId = $this->db->real_escape_string($planId);
        
        $condition = "id=" . $planId . "";
        $conditionRoute = "dialPlanId=" . $planId . "";
//        if($isAdmin != 1)
            $conditionWithUser = $condition." and userId=" . $userId;
            $conditionWithUserRoute = $conditionRoute." and userId=" . $userId;
        
        $selRes = $this->selectData("id", "91_dialPlanDetail", $conditionWithUser);
        if (!$selRes || $selRes->num_rows == 0) {
            $response['msg'] = "Error plan doesn't exist please contact provider";
            $response['status'] = "error";
            return json_encode($response);
        }
        
        $selCountryRes = $this->selectData("dialPlanId", "91_dialPlanCountryDetails", $conditionWithUserRoute);
        if(!$selCountryRes)
        {
            $response['msg'] = "Error Fetching details please contact provider";
            $response['status'] = "error";
            return json_encode($response);
        }
        elseif($selCountryRes->num_rows == 0)
        {
            $resultPlan = $this->deleteData('91_dialPlanDetail', $conditionWithUser);
            if(!$resultPlan)
            {
                $response['msg'] = "Error deleting data please try again";
                $response['status'] = "error";
                return json_encode($response);
            }
            else
            {
                $response['msg'] = "Successfuly deleted plan";
                $response['status'] = "success";
                return json_encode($response);
            }
        }
        
        $selRouteRes = $this->selectData("dialPlanId", "91_dialPlanRoute", $conditionRoute);
        if(!$selRouteRes)
        {
            $response['msg'] = "Error Fetching details please contact provider";
            $response['status'] = "error";
            return json_encode($response);
        }
        elseif($selRouteRes->num_rows == 0)
        {
            $resultPlanCountry = $this->deleteData('91_dialPlanCountryDetails', $conditionWithUserRoute);
            if(!$resultPlanCountry)
            {
                $response['msg'] = "Error deleting data please try again";
                $response['status'] = "error";
                return json_encode($response);
            }
            $resultPlan = $this->deleteData('91_dialPlanDetail', $conditionWithUser);
            if(!$resultPlan)
            {
                $response['msg'] = "Error deleting data please try again";
                $response['status'] = "error";
                return json_encode($response);
            }
            else
            {
                $response['msg'] = "Successfuly deleted plan";
                $response['status'] = "success";
                return json_encode($response);
            }
        }
        
        
        # insert plan in the backup table before deleting 
        $backUpQueryRes = $this->db->query("INSERT IGNORE INTO 91_backupDialPlanRoute SELECT * FROM 91_dialPlanRoute where " . $conditionRoute);
        
//       echo "INSERT IGNORE INTO 91_backupDialPlanRoute SELECT * FROM 91_dialPlanRoute where " . $conditionRoute;
        if($backUpQueryRes)
        {
            $resultTariff = $this->deleteData('91_dialPlanRoute', $conditionRoute);

            # if tariff is deleted succesfuly hten only delete the plan else not 
            
            if ($resultTariff && $this->db->affected_rows > 0) {
                
                
                if ($this->db->query("INSERT IGNORE INTO 91_backupDialPlanCountry SELECT * FROM 91_dialPlanCountryDetails where " . $conditionWithUserRoute)) {
                    $resultPlanCountry = $this->deleteData('91_dialPlanCountryDetails', $conditionWithUserRoute);
//                    echo $this->querry;
//                    $response['msg'] = "Plan Deleted Succesfuly";
//                    $response['status'] = "success";
                }
                if (!$resultPlanCountry) {
                    if($this->db->query("INSERT IGNORE INTO 91_dialPlanRoute SELECT * FROM 91_backupDialPlanRoute where " . $conditionRoute)){
                        $this->deleteData('91_backupDialPlanCountry', $conditionWithUserRoute);
                        $response['msg'] = "Error deleting plan please try again 100";
                        $response['status'] = "error";
                    }
                    else {
                        $response['msg'] = "Error deleting plan please try again 101";
                        $response['status'] = "error";
                    }
                    
                }
                
                
                if ($resultPlanCountry && $this->db->affected_rows > 0 && $this->db->query("INSERT IGNORE INTO 91_backupDialPlan SELECT * FROM 91_dialPlanDetail where " . $conditionWithUser)) {
                    $resultPlan = $this->deleteData('91_dialPlanDetail', $conditionWithUser);
                    echo $this->querry;
                    $response['msg'] = "Plan Deleted Succesfuly";
                    $response['status'] = "success";
                }
                # condtion if unable to delete the plan for any reason then ot will the tariif back from the back table 
                if (!$resultPlan) {
                    $this->db->query("INSERT IGNORE INTO 91_dialPlanRoute SELECT * FROM 91_backupDialPlanRoute where " . $conditionWithUserRoute);
                    $this->db->query("INSERT IGNORE INTO 91_dialPlanCountryDetails SELECT * FROM 91_backupDialPlanCountry where " . $conditionWithUserRoute);
                    $this->deleteData('91_backupDialPlan', $conditionWithUser);
                    $this->deleteData('91_backupDialPlanCountry', $conditionWithUserRoute);
                    $response['msg'] = "Error deleting plan please try again 108";
                    $response['status'] = "error";
                }
            } else {
                $response['msg'] = "Error deleting plan please try again 202";
                $response['status'] = "error";
            }
        }
        else {
                $response['msg'] = "Error deleting plan please try again 302";
                $response['status'] = "error";
            }
        return json_encode($response);
    }
    
   
    
}

?>