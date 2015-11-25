<?php

/**
 * @AUTHOR : SAMEER RATHOD 
 * @DESC : CALL SHOP CLASS CONTAIN ALL THE FUCNTION FOR CALL SHOP FEATURE 
 * @FUNCTIONS INCLUDED : 
 *      #addCallShopUser : function adds the call shop user ie call shop is added as a normal user which cannot login but exist 
 *      #checkMessengerId : This function check if messenger id already exist or not
 *      #checkUserPlan : check if this plan belongs to the user or not 
 *      #deleteCallShopSystem : This is used to delete a system in the call shop
 *      #editCallShop : this function is used to edit the call shop entry  
 *      #editSystemDetails : Edit the system details 
 *      #addPlan  : it consist of functionality to add and edit paln and tariff for manage plan
 *      #searchTarriff  : function to search details within tariff table 
 *      #deletePlanTariffs  : delete the tariff and backit up before deleting 
 *      #deletePlan  : used to delete the plan and backit up before deleting 
 *      #editTariff  : function call individual tariff row is to be edited 
 *      #getUserId   : function fetch the id of the user who created the plan 
 *      #countData   : genral function used to count the data from tha table 
 *      #getPlans   : fetch the plan details from 91_plan table 
 *      #getTarrifDetails   : fetch the tariff details from 91_tariff table 
 *      #getPlanName   : fetch the plan name and output currency from 91_plan table 
 *      #getUserDefaultPlan   : fetch the default plan details of the user from 91_plan which is assigned by the reseller 
 */
include dirname(dirname(__FILE__)) . '/config.php';
#VALIDATE THAT USER LOGED IN OR NOT 
if (!$funobj->login_validate()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}
class callShop_class extends fun {
   
    public $userName;
    public $password;
    public $validateUnPFlag = FALSE;

    function validateUserNameNPassword()
    {
        if(preg_match("/[^a-zA-Z0-9\_\-]+/", $this->userName))
        {
            return json_encode(array("msg"=>"Error Invalid User Name","status"=>"error"));
        }

        if(preg_match("/[^a-zA-Z0-9]+/", $this->password))
        {
            return json_encode(array("msg"=>"Error Invalid password please try again","status"=>"error"));
        }
        $this->validateUnPFlag = TRUE;
        return 1;
    }
    function editCallshop($request,$sessionId)
    {
        $name = $request['name'];
        $tariffId = trim($request['selPlan']);
        $userid = trim($request['userId']);
        if(preg_match('/[^a-zA-Z0-9]+/', $name))
            return json_encode (array("msg"=>"Invalid Name please enter a proper name","status"=>"error"));
        if(is_null($tariffId) || !is_numeric($tariffId))
            return json_encode (array("msg"=>"Invalid plan please select a proper plan","status"=>"error"));
        if(is_null($userid) || !is_numeric($userid))
            return json_encode (array("msg"=>"Invalid call shop please select a call shop to edit","status"=>"error"));
            
        $resId = $this->getResellerId($userid);
        if($sessionId == "" || $resId != $sessionId)
            return json_encode (array("msg"=>"Invalid user please login with a valid user","status"=>"error"));
        
        if($request['name'] != "")
        {
            $data = array("name"=>$request['name']);
            $table = "91_personalInfo";
            $condition = "userId = ".$userid;

            if(!$this->updateData($data, $table,$condition))
                return json_encode (array("msg"=>"Error Updating Records please try again","status"=>"error"));
        }
        
        $dataTariff = array("tariffId"=>$tariffId);
        $tableTariff = "91_userBalance";
        $conditionTariff = "userId = ".$userid;
        
        $updRes = $this->updateData($dataTariff, $tableTariff,$conditionTariff);
        echo $this->querry;      
        if(!$updRes || $this->db->affected_rows < 1)
            return json_encode (array("msg"=>"Error Updating Records please try again","status"=>"error"));
        
        return json_encode (array("msg"=>"Updated Successfuly","status"=>"success"));
    }
    function checkMessengerId($messengerId) 
    {
        
        $email = $this->db->real_escape_string($messengerId);
        $table = '91_addCallShop';
        $this->db->select('messengerId')->from($table)->where("messengerId = '" . $messengerId . "' ");
        $result = $this->db->execute();
        // processing the query result
        if ($result->num_rows > 0) {
            return 0; //echo "Sorry username already in use";
        }
        else
            return 1;
    }
    function insertSystemDetails($request,$userId,$systemId = NULL)
    {
       $sip = 0;
       $messenger = 0;
       $table = "91_addCallShop";
       
       
       if(isset($request) && $request != NULL)
       {
           
            $systemName = trim($request['systemName']);
            $messengerId = trim($request['messengerId']);
            $messengerType = trim($request['messengerType']);
            $systemType = $request['sysID'];
            $callShopId = (int)$request['callShopId'];
            $data = array();   
            
            
            
            if($callShopId == "" || !is_numeric($callShopId))
            {
                return json_encode(array("msg"=>"Error please select a call shop","status"=>"error"));
            }
            
            if($systemName == "" || preg_match("/[^a-zA-Z0-9]+/", $systemName))
            {
                return json_encode(array("msg"=>"Error Invalid System Name","status"=>"error"));
            }
            
            
            
            if($this->validateUnPFlag == FALSE)
                return json_encode (array("msg"=>"Internal server error code 701 callshop invalid credential","status"=>"error"));
            
            
            if(!is_null($systemId))
            {
                if(($systemId == "" || preg_match("/[^0-9]+/", $systemId)))
                    return json_encode(array("msg"=>"Error Invalid System ","status"=>"error"));
                if( preg_match("/[^a-zA-Z0-9\_]+/", trim($request['userName'])))
                        return json_encode(array("msg"=>"Error Invalid User Name","status"=>"error"));
//                if($request['password']== "" )
//                        return json_encode(array("msg"=>"Error Invalid Password","status"=>"error"));
            }
            

            if(isset($systemType) && $systemType == "0")
            {

                $sip = 1;
                if(!is_null($systemId) && $request['userName'] !="" && $request['password'] != "")
                {
                   
                    $userName = $request['userName'];
                    $password = $request['password'];
                }
                else
                {   
                    $userName = "call_".rand(10000000,100000000);
                    $password = $this->randomNumber(12);
                }
                
                #validate the callshop sip user name 
                #pending 
                
                
                $data['userName']  = strtolower($userName);
                $data['password']  = $password;
            }
            else if(isset($systemType) && $systemType == "1")
            {
                include_once ('signup_class.php');
                $signupObj = new signup_class();
                
                
                if(preg_match("/[^a-zA-Z0-9\.\@\$]+/", $messengerId) || $messengerId == "" || !$this->checkMessengerId($messengerId) )
                {
                    return json_encode(array("msg"=>"Error Invalid messenger Id","status"=>"error"));
                }
                
                if($messengerType != 1 && $messengerType != 2)
                {
                    return json_encode(array("msg"=>"Error Invalid messenger Id Type","status"=>"error"));
                }
                $selResult = $this->selectData("*", $table," messengerId = '".$messengerId."'");
                if($selResult->num_rows > 0)
                    return json_encode(array("msg"=>"Error Messenger Id already assigned to other system ","status"=>"error"));
                
                $data['messengerId']  = $messengerId;
                $data['messengerType'] = $messengerType;
                $messenger = 1;
            }
            
            $data['systemName'] = $systemName;
            $data['type'] = $systemType;
            $data['callShopId'] = $callShopId;
            $data['resellerId'] = $userId;
            
            
           
           
            if(!is_null($systemId))
                $data['systemId'] = $systemId;
            
           foreach($data as $key => $value)
           {
               $data[$key] = $this->db->real_escape_string($value);
           }
            
            
            $resellerId = $this->getResellerId($callShopId);
            
            if($resellerId != $userId)
                return json_encode(array("msg"=>"Error Invalid User please try again","status"=>"error"));
            
            
            
            if(array_search('', $data) !== false)
                return json_encode(array("msg"=>"Error Invalid input given","status"=>"error"));
            
            $selCallResult = $this->selectData("systemName", $table," callShopId = '".$callShopId."' and systemName = '".$systemName."'");
            if( $selCallResult->num_rows > 0)
                    return json_encode(array("msg"=>"Error system name already Exist","status"=>"error"));
           
            $res = $this->insertData($data, $table);
           
            if($res)
            {
                $systemId = $this->lastInsertId;
                if($sip)
                {
                    
                    $userID = $callShopId;
                    $sipChainId = $this->getUserChainId($callShopId);
                    
                    $sipTable  = "91_verifiedSipId";

                    $condition = " on duplicate key update userName = '".$userName."',passwd ='". $password."'";
                    
                    $sql = "INSERT INTO ".$sipTable." (userId,userName,passwd,chainId,isCallShopUser) values('".$userId."','".$userName."','".$password."','".$sipChainId."',0) ".$condition;
                    $resSip = $this->db->query($sql);
                    
                    

                    if($resSip)
                    { 
                        ob_start();
                        $res = sip_delete($userName);
                        $res2 = sip_add($userName,$password);
                        ob_end_clean();
                        
                       
                        $msg = "System added successfully";
                        $status = "success";            
                    }
                    else
                    {
                        $conditionDelSip = " systemId = ".$systemId."";
                        $resDel = $this->deleteData($table, $conditionDelSip);
                    }
                }
                elseif($messenger)
                {
                    if($messengerType == 1)
                        $updateTable = "91_verifiedGtalkId";
                    elseif($messengerType == 2)
                        $updateTable = "91_verifiedSkypeId";
                    
                    $updateArr = array("isCallShopUser"=>1,"systemId"=>$systemId);
                    $updateCondition = "email = '".$messengerId."' and userId = '".$userId."'";
                    $updRes = $this->updateData($updateArr, $updateTable,$updateCondition);
                    if(!$updRes)
                    {
                        $msg = "Error Updating details";
                        $status = "error";            
                    }
                    else
                    {
                        $msg = "System added successfully";
                        $status = "success";            
                    }
                }
                    
            }
            else
            {
                $msg = "Error adding system";
                $status = "error";
            }
       }
       else
       {
           $msg = "Error Invalid input supplied";
            $status = "error";
       }
           return json_encode(array("msg"=>$msg,"status"=>$status));
    }
    function addCallShopUser($request,$userId)
    {
        $parm['username'] = trim($request['userName']);
        $parm['reseller_id'] = $userId;
        $tariff_id = $request['tariffId'];
        $balance = $request['balance'];
        $currency_id = $request['currencyId'];
        
        if(preg_match("/[^a-zA-Z0-9]/", $parm['username']))
                return json_encode(array("msg"=>"Invalid Name please enter a valid Call Shop Name","status"=>"error"));
        if($parm['reseller_id'] == "")
                return json_encode(array("msg"=>"Invalid User Please try again","status"=>"error"));
        if($balance == "" || !is_numeric($balance) || $balance < 0 || $balance > 1000000)
                return json_encode(array("msg"=>"Invalid balance please provide balance between 1-1000000","status"=>"error"));
        if($currency_id == "" || !is_numeric($currency_id) )
                return json_encode(array("msg"=>"Invalid Currency please Select a currency","status"=>"error"));
        if($tariff_id == "" || $tariff_id == "Select" || !$this->checkUserPlan($tariff_id,$userId))
                return json_encode(array("msg"=>"Invalid tariff plan please select a proper tariff","status"=>"error"));
        
        $password = $this->generatePassword(8,2);
        $uName = preg_replace('/[\s\_]+/', "", $parm['username']);
        $parm['uName'] = "callShop_".$_SESSION['id']."_".$uName."_".rand(10, 100);
        $parm['password'] = $password;
        $parm['client_type'] = 7;
        $parm['client_limit'] = 4;
        $parm['call_limit'] = 1;
//        $randomString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1) . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
        $parm['firstName'] = $request['userName'];

        include_once('signup_class.php');
        $signupClsObj = new signup_class();
        $result = $signupClsObj->createUser($parm,$tariff_id,$balance,$currency_id);
        
        if($result == 1)
            return json_encode(array("msg"=>"Sucessfuly created the user", "status"=>"success","userId"=>$signupClsObj->newUserId));
        else
            return $result;
    }
    
    function getCallShopDetails($userId,$keyword = null)
    {
        $keyword = trim($keyword);
        if($userId == "" || !is_numeric($userId))
            return json_encode(array("msg"=>"Invalid User","status"=>"error"));
        
        $table = "91_manageClient";
        $columns = " name,tariffId,currencyId,userId,planName,balance ";
        $condition  = " type = 7 and resellerId = ".$userId." and deleteFlag = 0 ";
        
        if($keyword != null)
        {
            if(preg_match('/[^a-zA-Z0-9]/', $keyword))
                    return json_encode(array("msg"=>"Invalid callshop please enter a valid name","status"=>"error"));
            $keyword = $this->db->real_escape_string($keyword);
            $condition  .= " and name like '".$keyword."%'";
        }
        
        $result = $this->selectData($columns, $table, $condition);
        
        if($result)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $data[] = $row;
            }
            return json_encode($data);
        }
        else 
        {
            return json_encode(array("msg"=>"No Record Found","error"));
        }
        
    }
    function checkUserPlan($tariffId,$userId)
    {
        $tariffId = $this->db->real_escape_string($tariffId);
        $userId = $this->db->real_escape_string($userId);
        
        
        $columns = "tariffId";
        $table = "91_plan";
        $condition = "tariffId = ".$tariffId." and userId = ".$userId."";
        $result = $this->selectData($columns, $table, $condition);
        $resultBalanceTable = $this->selectData($columns, "91_userBalance", $condition);
        if($result->num_rows > 0 || $resultBalanceTable->num_rows > 0)
            return 1;
        else
            return 0;
    }
    function getCallShopActiveCall($callShopId)
    {
        if(preg_match('/[^0-9]+/', $callShopId) || $callShopId == "")
                return json_encode (array("msg"=>"Invalid callshopId ","status"=>"error"));
        
        //call shop id wise is pending 
        $columns = "uniqueId,dialed_number,call_dial,call_start,id_client,call_type,systemId";
        $table = "91_currentcalls";
        $condition = "id_client = '".$callShopId."' and isCallShopUser = 1 order by call_dial DESC";
        $result = $this->selectData($columns, $table, $condition);
        
        
        if($result)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $row['duration'] = (strtotime($row['call_start']) - strtotime($row['call_dial']));
                $row['call_dial'] = date("Y-m-d h:i:s a",strtotime($row['call_dial']));
                $data[] = $row;
            }
            return json_encode($data);
        }
        else
        {
            return 0;
        }
    }
    
    function getVerifiedEmailIdDetails($userId,$fields = NULL)
    {
        $contact=array();
        if(is_null($fields))
            $fields = "*";
        $verifyGtalkEmail = '91_verifiedGtalkId'; 
        $verifySkypeEmail = '91_verifiedSkypeId'; 
        $this->db->select($fields)->from($verifyGtalkEmail)->where("userid = '" . $userId . "'");
        $resultGtalk = $this->db->execute();
        
        $this->db->select($fields)->from($verifySkypeEmail)->where("userid = '" . $userId . "'");
        $resultSkype = $this->db->execute();
        // processing the query result           
        if ($resultGtalk->num_rows > 0) {
            while($row= $resultGtalk->fetch_array(MYSQL_ASSOC)) {
                    $row['type'] = "1";
                    $contact[] = $row;
            }
        }
        if ($resultSkype->num_rows > 0) {
            while($row= $resultSkype->fetch_array(MYSQL_ASSOC)) {
                    $row['type'] = "2";
                    $contact[] = $row;
            }
        }

        return $contact ;
    }
    


//function getCallShopSystemList($userId,$callShopId,$systemIdString)
//{
//    
//    if(preg_match('/[^0-9]+/', $callShopId) || $callShopId =="")
//            return 0;
//        $columns = "*";
//        $table = "91_addCallShop";
//        $condition = "resellerId = '".$userId."%' and callShopId = '".$callShopId."' and systemId NOT IN ('".$systemIdString."')";
//        $result = $this->selectData($columns, $table, $condition);
//        
//        if($result)
//        {
//            while($row = $result->fetch_array(MYSQLI_ASSOC))
//            {
//                $response[] = $row;
//            }
//            return json_encode($response);
//        }
//        else
//        {
//            return 0;
//        } 
//}
function getCallShopSummary($callShopId,$resellerId,$type = 1,$systemId = NULL)
{
    if(!is_numeric($resellerId) || $resellerId == "")
        return json_encode(array("msg"=> "Error Fetching Details Invalid user","staus"=>"error"));
    
    if(!is_numeric($callShopId) || $callShopId == "")
        return json_encode(array("msg"=> "Error Fetching Details Invalid shopId","staus"=>"error"));
    
    if(!is_numeric($type) || $type == "")
        return json_encode(array("msg"=> "Error Fetching Details Invalid Type","staus"=>"error"));
    
    if($type == 1)
    {
        if(is_null($systemId))
            return json_encode(array("msg"=> "Error Fetching Details Invalid system","staus"=>"error"));
        $coloumn = "systemId,dialedNumber,date,duration,cost,currency";
        $condition = "resetFlag = 0 and callShopId = '".$callShopId."' and resellerId = ".$resellerId." and systemId = '".$systemId."' order by date DESC";
    }
    if($type == 2)
    {
        $coloumn = "*";
        $condition = "resetFlag = 0 and callShopId = '".$callShopId."' and resellerId = ".$resellerId." order by date DESC";
    }
    
    $result = $this->selectData($coloumn, '91_callShopSummary',$condition);
//    echo $this->querry;
    
//    var_dump($result);
    if($result)
    {
//        echo $result->num_rows;
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {   
                        
            $row['currency'] = $this->getCurrencyViaApc($row['currency'],1);
            
            $sytemIdArr[] = $row['systemId'];
            $reponse[$row['systemId']][] = $row;
        }
       
        if($type == 1)
        {
           
            return json_encode($reponse[$systemId]);
        }
        else if($type == 2)
        {
            $sytemIdArr = array_unique($sytemIdArr);
            
            $sytemIdStr = implode("','", $sytemIdArr);
            $callShopRes = $this->selectData("systemId,systemName,type,userName,resellerId,callShopId", "91_addCallShop","resellerId = ".$resellerId." and callShopId=".$callShopId." and isDeleted=0");//,"systemId IN ('".$sytemIdStr."')"
           
            if($callShopRes)
            {
                
                
                while($rowRes = $callShopRes->fetch_array(MYSQLI_ASSOC))
                {
                   $arr['duration'] = "";
                   $arr['cost'] = "";
                   
                    $arr['totalCall'] = count($reponse[$rowRes['systemId']]);
                    foreach($reponse[$rowRes['systemId']] as $resValue)
                    {
                        $arr['duration'] += $resValue['duration'];
                        $arr['cost'] += $resValue['cost'];
                    }
                    $arr['systemName'] = $rowRes['systemName'];
                    $arr['callRate'] = $reponse[$rowRes['systemId']][0]['callRate'];
                    $arr['lastNumber'] = $reponse[$rowRes['systemId']][0]['dialedNumber'];
                    $arr['lastdate'] = $reponse[$rowRes['systemId']][0]['date'];

                    $arr['currency'] = $reponse[$rowRes['systemId']][0]['currency'];
//                    $arr['currency'] = $this->getCurrencyViaApc($reponse[$rowRes['systemId']][0]['currency'],2);
                    
                    if($rowRes['type'] == 0)
                        $arr['typeDetails'] = $rowRes['userName'];
                    else
                        $arr['typeDetails'] =  $rowRes['messengerId'];
                    $callShopDetails[$rowRes['systemId']] = $arr;
                }
                
               
            }
            
          
            return json_encode($callShopDetails);
        }
    }
    else
        return false;
}
function getSystemDetails($systemId,$resellerId,$callShopId)
{
    if(preg_match('/[^0-9]+/', $systemId) || $systemId =="")
            return json_encode(array("msg"=>"Invalid system","status"=>"error"));
    if(preg_match('/[^0-9]+/', $resellerId) || $resellerId =="")
            return json_encode(array("msg"=>"Invalid user","status"=>"error"));
    if(preg_match('/[^0-9]+/', $callShopId) || $callShopId =="")
            return json_encode(array("msg"=>"Invalid callshop ","status"=>"error"));
    
    $systemId = $this->db->real_escape_string($systemId);    
    $resellerId = $this->db->real_escape_string($resellerId);    
    $condition = array("systemId = ".$systemId,"resellerId=".$resellerId,"callShopId=".$callShopId);
    $result = $this->selectData("*", "91_addCallShop",$condition);
    if($result)
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return json_encode($row);
    }
    else
        return json_encode (array("msg"=>"Error Fetching Details","status"=>"error"));
    
    
}

private function validateCallShop($systemId,$resellerId,$callShopId)
{
    if(preg_match('/[^0-9]+/', $systemId) || $systemId =="")
            return json_encode(array("msg"=>"Invalid system","status"=>"error"));
    if(preg_match('/[^0-9]+/', $resellerId) || $resellerId =="")
            return json_encode(array("msg"=>"Invalid user","status"=>"error"));
    if(preg_match('/[^0-9]+/', $callShopId) || $callShopId =="")
            return json_encode(array("msg"=>"Invalid callshop ","status"=>"error"));
    
    return 1;
}
function editSystemDetails($request,$systemId,$resellerId,$callShopId)
{
//    if(preg_match('/[^0-9]+/', $systemId) || $systemId =="")
//            return json_encode(array("msg"=>"Invalid system","status"=>"error"));
//    if(preg_match('/[^0-9]+/', $resellerId) || $resellerId =="")
//            return json_encode(array("msg"=>"Invalid user","status"=>"error"));
//    if(preg_match('/[^0-9]+/', $callShopId) || $callShopId =="")
//            return json_encode(array("msg"=>"Invalid callshop ","status"=>"error"));
    $response = $this->validateCallShop($systemId,$resellerId,$callShopId);
    $systemName = trim($request['systemName']);
    
    
    if($response != 1)
        return $response;
    
    if( $request['sysId'] == "1")
    {
        if(preg_match("/[^a-zA-Z0-9\.\@\$]+/", $messengerId) || $messengerId == "" || !$this->checkMessengerId($messengerId) )
        {
            return json_encode(array("msg"=>"Error Invalid messenger Id","status"=>"error"));
        }
    }
    else {
        $this->userName = $request['userName'];
        $this->password = $request['password'];
    
        $validateResult = $this->validateUserNameNPassword();
        if($validateResult != 1)
            return $validateResult;
    }
    
    $callShopId = $this->db->real_escape_string($callShopId);
    $resellerId = $this->db->real_escape_string($resellerId);
    $systemId = $this->db->real_escape_string($systemId);
    
    if(isset($request['userName']) && $request['userName'] != "" && $request['sysID'] == 0)
    {
        if(preg_match('/[^a-zA-Z0-9\_\-]+/', $request['userName']) || $request['userName'] == "")
                return json_encode (array("msg"=>"Invalid user name please don't try to edit the user name","status"=>"error"));
        
        $selectResult = $this->selectData("systemId", "91_addCallShop","systemId=".$systemId." and userName='".$request['userName']."' and resellerId=".$resellerId." and isdeleted=0");
        
        
        if(!$selectResult || $selectResult->num_rows < 1)
            return json_encode(array("msg"=>"Error you dont have the permission to change the user name","status"=>"error"));
    }
    
    
    if($systemName == "" || preg_match("/[^a-zA-Z0-9]+/", $systemName))
    {
        return json_encode(array("msg"=>"Error Invalid System Name","status"=>"error"));
    }
    
    
    $condition = "systemId = ".$systemId." and resellerId =".$resellerId." and callShopId=".$callShopId;
        $delResCallShop = $this->deleteData("91_addCallShop", $condition);
    if(!$delResCallShop)
        return json_encode(array("msg"=>"Error updating 101","status"=>"error"));
    
    $conditionSip = "systemId = ".$systemId." and isCallShopUser =1 and userId=".$callShopId;
        $delResSip = $this->deleteData("91_verifiedSipId", $conditionSip);
    if(!$delResSip)
        return json_encode(array("msg"=>"Error updating 101","status"=>"error"));
       
    

      $response =   $this->insertSystemDetails($request,$resellerId,$systemId);
    
    return $response;
}
function resetCallShopSummary($systemId,$callShopId,$resellerId)
{
    $response = $this->validateCallShop($systemId,$resellerId,$callShopId);
    if($response != 1)
        return $response;
    
    $callShopId = $this->db->real_escape_string($callShopId);
    $resellerId = $this->db->real_escape_string($resellerId);
    $systemId = $this->db->real_escape_string($systemId);
    
    $data = array("resetFlag"=>"1");
    $table = "91_callShopSummary";
    $condition = " callShopId =".$callShopId." and resellerId =".$resellerId." and systemId = ".$systemId."";
    $res = $this->updateData($data, $table ,$condition);
    if($res && $this->db->affected_rows > 0)
     return json_encode(array("msg"=>"Reset Successfull","status"=>"success"));
    else
     return json_encode(array("msg"=>"Nothing to Reset","status"=>"error"));
}

public function deleteCallShopSystem($callshopId,$resellerId,$type = 1,$systemId = NULL) {
    if(isset($systemId) && (preg_match(NOTNUM_REGX, $systemId) || $systemId == ""))
            return json_encode(array("msg"=>"Invalid System Please Try Again","status"=>"error"));
    
    if(preg_match(NOTNUM_REGX, $callshopId) || $callshopId == "")
            return json_encode(array("msg"=>"Invalid Call Shop Please Try Again","status"=>"error"));
    
    if(preg_match(NOTNUM_REGX, $resellerId) || $resellerId == "")
            return json_encode(array("msg"=>"Invalid Reseller Please Try Again","status"=>"error"));
    
    $condition  = "callshopId =  ".$callshopId." and resellerId = ".$resellerId."";
    if($type == 2  && $systemId != "")
        $condition  .= " and systemId = ".$systemId."";
    
    $data = array("isDeleted" => 1);
    $table = "91_addCallShop";
    $updRes = $this->updateData($data, $table,$condition);
    
    if(!$updRes)
        return json_encode(array("msg"=>"Error Deleting system please try again","status"=>"error"));
    else       
        return json_encode(array("msg"=>"Successfully deleted system code","status"=>"success"));
}


}

?>
