<?php

/* @AUTHOR : SAMEER RATHOD 
 * @DESC : PLAN CLASS CONTAIN ALL THE FUCNTION FOR MANAGE PLAN FEATURE 
 * @FUNCTIONS INCLUDED : 
 *      #insertPlan : function insert the plan details in the 91_plan table
 *      #searchExistingPlans : search the plan if it already exist or not 
 *      #uploadFile : upload the file to the server
 *      #getConvertedCurency : convert the currency into desired currency
 *      #importTariffRates : import the tariff rate from excel file 
 *      #getOutPutCurrency  : get the output currency by tariff id from 91_plan table 
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
        
        $data = array("name"=>$request['name']);
        $table = "91_personalInfo";
        $condition = "userId = ".$userid;
        
        if(!$this->updateData($data, $table,$condition))
            return json_encode (array("msg"=>"Error Updating Records please try again","status"=>"error"));
        
        $dataTariff = array("currencyId"=>$request['name']);
        $tableTariff = "91_userBalance";
        $conditionTariff = "userId = ".$userid;
        
        if(!$this->updateData($dataTariff, $tableTariff,$conditionTariff))
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
    function insertSystemDetails($request,$userId)
    {
       $table = "91_addCallShop";
       if(isset($request) && $request != NULL)
       {
            $systemName = trim($request['systemName']);
//            $balance = trim($request['balance']);
            $messengerId = trim($request['messengerId']);
            
            
            $userName = trim($request['userName']);
            $password = trim($request['password']);
            $systemType = $request['sysID'];
            $callShopId = (int)$request['callShopId'];
            $data = array();   
            
            
            
            if($callShopId == "" || !is_numeric($callShopId))
            {
                return json_encode(array("msg"=>"Error please select a call shop","status"=>"error"));
            }
            //$systemName == "" ||
            if($systemName == "" || preg_match("/[^a-zA-Z0-9]+/", $systemName))
            {
                return json_encode(array("msg"=>"Error Invalid System Name","status"=>"error"));
            }
//            if(preg_match("/[^0-9]+/", $balance) || $balance < 0 || $balance > 100000)
//            {
//                return json_encode(array("msg"=>"Error Invalid Balance","status"=>"error"));
//            }
            if(isset($systemType) && $systemType == "0")
            {
                if(preg_match("/[^a-zA-Z]+/", $userName) || $userName == "")
                {
                    return json_encode(array("msg"=>"Error Invalid User Name","status"=>"error"));
                }
                if(preg_match("/[^a-zA-Z0-9\@\_\-\{\}\$]+/", $password) || $password == "" || strlen($password) < 6 || strlen($password) < 18)
                {
                    return json_encode(array("msg"=>"Error Invalid Password must be 6-18 in length and must not contain any character other than (alphabets,numbers,@,$,{,},_,-)","status"=>"error"));
                }
                $data['userName']  = $userName;
                $data['password']  = $password;
            }
            else if(isset($systemType) && $systemType == "1")
            {
                include_once ('signup_class.php');
                $signupObj = new signup_class();
                
                if(preg_match("/[^a-zA-Z0-9\.\@\$]+/", $messengerId) || $messengerId == "" || !$this->checkMessengerId($messengerId) || !$signupObj->check_email_avail($messengerId))
                {
                    return json_encode(array("msg"=>"Error Invalid messenger Id","status"=>"error"));
                }
                $data['messengerId']  = $messengerId;
            }
            
            $data['systemName'] = $systemName;
            $data['type'] = $systemType;
            $data['callShopId'] = $callShopId;
            
            
            $resellerId = $this->getResellerId($callShopId);
            
            if($resellerId != $userId)
                return json_encode(array("msg"=>"Error Invalid User please try again","status"=>"error"));
            
            if(array_search('', $data) !== false)
                return json_encode(array("msg"=>"Error Invalid input given","status"=>"error"));
            
            
            
            $res = $this->insertData($data, $table);
            if($res)
            {
                $msg = "System added successfully";
                $status = "success";            
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
        if($tariff_id == "" || $tariff_id == "Select" || !checkUserPlan($tariff_id,$userId))
                return json_encode(array("msg"=>"Invalid tariff plan please select a proper tariff","status"=>"error"));
        
        $password = $this->generatePassword(8,2);
        $uName = preg_replace('/[\s\_]+/', "", $parm['username']);
        $parm['uName'] = "callShop_".$_SESSION['id']."_".$uName."_".rand(10, 100);
        $parm['password'] = $password;
        $parm['client_type'] = 7;
        $parm['client_limit'] = 4;
        
        include_once('signup_class.php');
        $signupClsObj = new signup_class();
        $result = $signupClsObj->createUser($parm,$tariff_id,$balance,$currency_id);
        if($result == 1)
            return json_encode(array("msg"=>"Sucessfuly created the user", "status"=>"success"));
        else
            return $result;
    }
    
    function getCallShopDetails($userId)
    {
        if($userId == "" || !is_numeric($userId))
            return json_encode(array("msg"=>"Invalid User","status"=>"error"));
        $table = "91_manageClient";
        $columns = " name,tariffId,currencyId,userId,planName,balance ";
        $condition  = " type = 7 and resellerId = ".$userId." and deleteFlag = 0 ";
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
        $columns = "tariffId";
        $table = "91_plan";
        $condition = "tariffId = ".$tariffId." and userId = ".$userId."";
        $result = $this->selectData($columns, $table, $condition);
        if($result->num_rows > 0)
            return 1;
        else
            return 0;
    }
    function getCallShopActiveCall($chainId)
    {
        //call shop id wise is pending 
        $columns = "uniqueId,dialed_number,call_dial,call_start,id_client,call_type";
        $table = "91_currentcalls";
        $condition = "id_chain LIKE '".$chainId."%' and call_type = 'callshop' order by call_dial DESC";
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
    
}
?>
