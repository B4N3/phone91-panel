<?php
/* @author : sameer
 * @created : 09-09-2013
 * @desc : 
 */
date_default_timezone_set('GMT');
include dirname(dirname(__FILE__)).'/config.php';

class didDivert_class extends fun
{
    function getDDCountryAndState() {
        $this->db->select("country,state")->from("91_didDivertPlan")->where(" 1 order by country");
        // $this->db->getQuery();
        $res = $this->db->execute();
        while($row = $res->fetch_array(MYSQLI_ASSOC))
        {
            if( !isset($data) ) {
                $data["country"] = $row['country'];
                $data['state'][] = $row['state'];
            } else if ($data["country"] == $row["country"]) {
                $data["state"][] = $row['state'];
            } else {
                $list[] = $data;
                $data['state'] = NULL;
                $data["country"] = $row['country'];
                $data['state'][] = $row['state'];
            }
        }
        $list[] = $data;

        // while($row = $res->fetch_array(MYSQLI_ASSOC))
        // {
        //     $data["country"]= $row['country'];
        //     $data['state'] = $row['state'];
        //     $list[] = $data;
        // }

        return $list;
    }
    function getDDPlanByCountryAndState($userId, $country, $state) {   
        $list = array();
        if ( preg_match(NOTCOUNTRY_REGX,$country) || preg_match(NOTCOUNTRY_REGX,$state) ) {
            return json_encode(array("status"=>"error", "msg"=>"Please enter valid info"));
        }
        
        $this->db->select("planId, planName, planRate, validity")->from("91_didDivertPlan")->where("country = '".$country."' and state = '".$state."'");
        $res = $this->db->execute();
        $userDetail = $this->getNameAndUserName($userId);
        $userCurrency = $userDetail['currencyId'];
        $Currency = $this->getCurrencyViaApc($userCurrency ,1 );
        while($row = $res->fetch_array(MYSQLI_ASSOC))
        {
            $data["planId"] = $row['planId'];
            $data["planName"] = $row['planName'];
            
           
            if("USD" != $Currency)
            {       

                $data["planRate"] = $this->currencyConvert("USD", $Currency, $row['planRate']); 
                $data['currency'] = $Currency;
            }
            else
            {
                $data["planRate"] = $row['planRate'];
                $data['currency'] = "USD";
            }

            $data["validity"] = $row['validity'];
            $list[] = $data;
            
        }
        return $list;
    }
    function getDDLongCodeDetails($userId) {
        $list = array();
        if( ! preg_match(NUM_REGX, $userId) ) {
            return json_encode(array("status"=>"error", "msg"=>"Please enter valid info"));
        }
        $this->db->select("assignId, sourceNo, destinationNo, longCodeNo, longCodeType, expiryDate, planId, renew")->from("91_didDivert")->where("userId = '".$userId."'");
        $res = $this->db->execute();
        while($row = $res->fetch_array(MYSQLI_ASSOC))
        {
            $data["assignId"] = $row['assignId'];
            $data["sourceNo"] = $row['sourceNo'];
            $data["destinationNo"] = $row['destinationNo'];
            $data["longCodeNo"] = $row['longCodeNo'];
            $data["longCodeType"] = $row['longCodeType'];
            $data["expiryDate"] = $row['expiryDate'];
            $data['planId'] = $row['planId'];
            $data['renew'] = $row['renew'];
            $list[] = $data;
        }
        return $list;
    }
    function getDDCallLog($userId, $longCodeNo, $sourceNo ) {
        $list = array();
        if( ! preg_match(NUM_REGX, $userId) || ! preg_match(PHNNUM_REGX, $longCodeNo) ) {
            return json_encode(array("status"=>"error", "msg"=>"Enter valid Parameter"));
        }
        $table = "91_didDivertCalls";
        $condition = "userId = '".$userId."' and longCodeNo = '".$longCodeNo."'";
        if ($sourceNo != '' && $sourceNo != NULL) {
            if ( ! preg_match(PHNNUM_REGX, $sourceNo) ) {
                return json_encode(array("status"=>"error", "msg"=>"Enter valid sourceNo"));
            }
            $condition.=" and sourceNo = '".$sourceNo."'";
        }
        $this->db->select("caller_id,sourceNo,destinationNo,callStart")->from($table)->where($condition);
        $res = $this->db->execute();
        while($row = $res->fetch_array(MYSQLI_ASSOC))
        {
            $data["caller_id"] = $row['caller_id'];
            $data["sourceNo"] = $row['sourceNo'];
            $data["destinationNo"] = $row['destinationNo'];
            $data['callStart'] = $row['callStart'];
            $list[] = $data;
        }
        return $list;
    }

    function changeDDDestinationNo($userId, $assignId, $newDestinationNo) {
        $list = array();
        if( ! preg_match(NUM_REGX, $userId) || ! preg_match(NUM_REGX, $assignId) || ! preg_match(PHNNUM_REGX, $newDestinationNo) ) {
            return json_encode(array("status"=>"error", "msg"=>"Enter valid Parameter"));
        }

        $data = array("destinationNo"=>$newDestinationNo);
        $table = "91_didDivert";
        $condition = "userId = '".$userId."' and assignId = '".$assignId."'";

        $updateRes = $this->updateData($data, $table, $condition);
        if($updateRes) {
            return json_encode(array("status"=>"success", "msg"=>"Destnation number successfully updated"));
        } else { 
            return json_encode(array("status"=>"error", "msg"=>"Destnation number could not be updated"));
        }

    }
    function changeDDSourceNo($userId, $assignId, $sourceNo ) {
        $list = array();
        if( ! preg_match(NUM_REGX, $userId) || ! preg_match(NUM_REGX, $assignId) || ! preg_match(PHNNUM_REGX, $sourceNo) ) {
            return json_encode(array("status"=>"error", "msg"=>"Please enter valid info"));
        }

        $data = array("sourceNo"=>$sourceNo);
        $table = "91_didDivert";
        $condition = "userId = '".$userId."' and assignId = '".$assignId."'";   
        
        $updateRes = $this->updateData($data, $table, $condition);
        if($updateRes) {
            return json_encode(array("status"=>"success", "msg"=>"Source number successfully updated"));
        } else { 
            return json_encode(array("status"=>"error", "msg"=>"Source number could not be updated"));
        }

    }
    function changeDDDetails($userId, $assignId, $type, $value) {

        if( ! preg_match(NUM_REGX, $userId) || ! preg_match(NUM_REGX, $type) || ! preg_match(NUM_REGX, $value) ) {
            return json_encode(array("status"=>"error", "msg"=>"Please enter valid info"));
        }
        $table = "91_didDivert";
        $condition = "userId = '".$userId."' and assignId = '".$assignId."'";  
        switch($type) {
            case 0:
                $data = array("destinationNo"=>$value);
                break;
            case 1:
                $data = array("sourceNo"=>$value);
                break;
            case 2:
                $data = array("renew"=>$value);
                break;
            default:
                return json_encode(array("status"=>"error", "msg"=>"Please enter valid info"));
        }
        $updateRes = $this->updateData($data, $table, $condition);
        if($updateRes) {
            return json_encode(array("status"=>"success", "msg"=>"Details successfully updated"));
        } else { 
            return json_encode(array("status"=>"error", "msg"=>"Details could not be updated"));
        }
    }
    function addDDForwardNo($userId, $longCodeNo, $sourceNo, $planId = 0) {
        $list = array();
        if( ! preg_match(NUM_REGX, $userId) || ! preg_match(PHNNUM_REGX, $longCodeNo) || ! preg_match(PHNNUM_REGX, $sourceNo) || ! preg_match(NUM_REGX, $planId) ) {
            return json_encode(array("status"=>"error", "msg"=>"Please enter valid info"));
        }

        $now = date("Y-m-d H:i:s");
        $assignId = "".rand(10000,99999).date(YmdHis);
        $data = array("longCodeNo"=>$longCodeNo, "sourceNo"=>$sourceNo, "userId"=>$userId, 'longCodeType' => 0, 'planId'=>$planId, "assignId"=>$assignId, 'assignDate' => $now );
        $table = "91_didDivert";
        $result = $this->insertData($data, $table);

        $content['assignId'] = $assignId;
        if($result) {
            return json_encode(array("status"=>"success", "msg"=>"Forward number successfully added","content"=>$content));
        } else { 
            return json_encode(array("status"=>"error", "msg"=>"Forward number could not be added"));
        }
        
    }
    
     function addDDDedicatedNo($userId, $planId) {
         $list = array();
         if( ! preg_match(NUM_REGX, $userId) || ! preg_match(NUM_REGX, $planId)  ) {
             return json_encode(array("status"=>"error", "msg"=>"Please enter valid info $userId and $planId"));
         }
         $this->db->select("planRate, validity")->from("91_didDivertPlan")->where("planId = '".$planId."'");
         $res = $this->db->execute();
         while($row = $res->fetch_array(MYSQLI_ASSOC))
         {
             $planRate = $row['planRate'];
             $validity = $row['validity'];
         }
         $userDetail = $this->getNameAndUserName($userId);
         $userCurrencyId = $userDetail['currencyId'];
         $userBalance = $userDetail['balance'];
         $userCurrency = $this->getCurrencyViaApc($userCurrencyId ,1 );
         if("USD" != $userCurrency)
         {
             $planRate = $this->currencyConvert("USD", $userCurrency, $planRate); 
         }
         
         
         if ($userBalance < $planRate ) {
             return json_encode(array("status"=>"error", "msg"=>"User does not have sufficient balance"));
         }
         
        include_once("transaction_class.php");
        $tranxObj = new transaction_class();
        $planRate = round($planRate,5);

        $updatedFlag =  $tranxObj->updateUserBalance($userId,$planRate,'-');
        
        if($updatedFlag == 0)
        { 
            return json_encode(array("status"=>"error", "msg"=>"User Balance not updated."));
        }


        $tranxObj->fromUser = 2;
        $tranxObj->toUser = $userId;
        $newBalance = $userBalance-$planRate;
   
    
        $tranxObj->addTransactional_sub($planRate,$newBalance,'DD DedicatedNo assign',0,0,0,'Plan Rate Deducted.');
        
        $subject = "DidDivert : user demanded access number.";
        $message = 'username:'.$userDetail['userName'].'userId:'.$userId.'planId:'.$planId. "deduct balance : ".$planRate;
        $this->sendErrorMail("mayank@hostnsoft.com", "DidDivert : user demanded access number.".$message); 
        

        $now = date("Y-m-d H:i:s");
        $assignId = "".rand(10000,99999).date(YmdHis);
        $table = '91_didDivert';
        $dataArr = array(
                                 'userId' => $userId,
                                 'assignId' => $assignId,
                                 'longCodeType' => 1,
                                 'planId' => $planId,
                                 'assignDate' => $now
                        );
                
        $result = $this->insertData($dataArr,$table);
        if(!$result) {
            $this->sendErrorMail("mayank@hostnsoft.com", "DidDivert-Failed: user demanded access number could not be inserted.".$message); 
            return json_encode(array("status"=>"error", "msg"=>"Plan could not be provided"));
        }

        $content[]['assignId'] = $assignId;
        return json_encode(array("status"=>"success", "msg"=>"Plan successfully processed","content"=>$content));
    }
    
    
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param type $param : 
     * @param type $userId admin user id 
     * @param type $userType user type 1 for admin 
     */
    function getUserDidDivertDetail($param,$userId,$userType){
        
        if($userType !=1){
             return json_encode(array("msg"=>"you have no permission to get did divert detail of user","status"=>"error"));
       }
        
       if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
       
       
       
       $Condition = "userId='".$param['userId']."'";
        
        $data = array();
        $result = $this->selectData("*", "91_didDivert",$Condition);
       
         if(!$result || $result->num_rows < 1)
        {
            return json_encode(array("msg"=>"Record not found.","status"=>"error"));
        }
	
	while($res = $result->fetch_array(MYSQLI_ASSOC)){
	
	$data['assignId'] = $res['assignId'];
        $data['sourceNo'] = $res['sourceNo'];
        $data['destinationNo'] = $res['destinationNo'];
        $data['longCodeNo'] = $res['longCodeNo'];
        if($res['longCodeType'] == 1){
            $data['longCodeType'] = "Dedicated";
        }else 
            $data['longCodeType'] = "Forward";
        $data['expiryDate'] = $res['expiryDate'];
        $data['planId'] = $res['planId'];
        
        $planDetail = $this->getDidcountryandStateviaPlan($res['planId']);
        $data['country'] = $planDetail['country'];
        $data['state'] = $planDetail['state'];
        
        if($res['renew'] == 1){
            $data['renew'] = "AutoRenew";
        }else 
            $data['renew'] = "RenewOff";
        
        
         if($res['isEnable'] == 1){
            $data['isEnable'] = "Enable";
        }else 
            $data['isEnable'] = "Disable";
        
	$didData[] = $data;
        }
       
       
       
        return json_encode(array("msg"=>"successfully record found.","status"=>"success","DidDivertDetail"=>$didData));
    
      
       
       
       
    }
    
    
    function getDidcountryandStateviaPlan($planId){
        
        $Condition = "planId='".$planId."'";
        
        $data = array();
        $result = $this->selectData("*", "91_didDivertPlan",$Condition);
       
         if(!$result || $result->num_rows < 1)
        {
            return $data;
        }
	
	$res = $result->fetch_array(MYSQLI_ASSOC);
	
	$data['country'] = $res['country'];
        $data['state']= $res["state"];
        $data['validity']=$res['validity'];
        
	return $data;
        
    }
    
    
    function getDidDivtViaAssignId($param,$userId,$userType){
        
          
        if($userType !=1){
             return json_encode(array("msg"=>"you have no permission to get did divert detail of user","status"=>"error"));
       }
        
       if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
       
       if(preg_match('/[^0-9]+/', $param['assignId']) || $param['assignId'] == "")
             return json_encode(array("msg"=>"Error Invalid Assign Id.","status"=>"error"));
       
       $Condition = "assignId='".$param['assignId']."'";
        
        $data = array();
        $result = $this->selectData("*", "91_didDivert",$Condition);
       
         if(!$result || $result->num_rows < 1)
        {
            return json_encode(array("msg"=>"Record not found.","status"=>"error"));
        }
	
	$res = $result->fetch_array(MYSQLI_ASSOC);
	
	$data['assignId'] = $res['assignId'];
        
        if($res['longCodeNo'] == null || $res['longCodeNo'] == ''){
            $data['longEx'] = 1;
        }else
            $data['longEx'] = 0;
        
        $data['longCodeNo'] = $res['longCodeNo'];
        $data['expiryDate'] = $res['expiryDate'];
        $data['planId'] = $res['planId'];
        $data['renew'] = $res['renew'];      
        $data['isEnable'] =$res['isEnable'];
               
	 return json_encode(array("msg"=>"Record found.","status"=>"success","DidDivtData"=>$data));
        
    }
    
    function editDidDivertDetail($param,$userId,$userType){
        
       if($userType !=1){
             return json_encode(array("msg"=>"you have no permission to get did divert detail of user","status"=>"error"));
       }
        
       if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
       
         
       if(preg_match('/[^0-9]+/', $param['assignId']) || $param['assignId'] == "")
             return json_encode(array("msg"=>"Error Invalid Assign Id.","status"=>"error"));
       
    
       
       if(preg_match('/[^0-9]+/', $param['renew']) || preg_match('/[^0-9]+/', $param['isEnable']))
             return json_encode(array("msg"=>"Error Please select valid option.","status"=>"error"));
       
    
       if(isset($param[expiryDate])){
           $data = array("expiryDate"=>$param[expiryDate],
                         "renew"=>$param['renew'],
                         "isEnable"=>$param['isEnable']);
       }else{
           
           $planDetail = $this->getDidcountryandStateviaPlan($param['planId']);
           
           $validity = $planDetail['validity'];
           $backtime =  date("Y-m-d H:i:s",strtotime(date('Y-m-d H:i:s')."- 5 hours"));
           $expiryDate = date("Y-m-d H:i:s",strtotime($backtime . "+ ".$validity." day"));
           
           $data = array("longCodeNo"=>$param['longcodeNo'],
                         "renew"=>$param['renew'],
                         "isEnable"=>$param['isEnable'],
                         "expiryDate"=>$expiryDate
                   );
       }
       
        
        $table = "91_didDivert";
        $condition = "assignId = '".$param['assignId']."'";

        $updateRes = $this->updateData($data, $table, $condition);
        if($updateRes) {
            return json_encode(array("status"=>"success", "msg"=>"successfully updated"));
        } else { 
            return json_encode(array("status"=>"error", "msg"=>"record not updated"));
        }
    

       
       
    }
    
    
    
}


?>
