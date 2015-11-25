<?php
/* @author : sudhir pandey <sudhir@hostnsoft.com>
 * @created : 16/06/2014
 * @desc : class use to create calling card and pin user api 
 */
include dirname(dirname(__FILE__)).'/config.php';
class callingCard_class extends fun
{
    /**
     *@author sudhir pandey <sudhir@hostnsoft.com>
     *@created 16/06/2014
     *@desc function use to create pin user Api for server side (gtalk pin recharge *PIN#).
    **/
   function createPinUser($accountType,$emailId,$pin)
   {
        #check emailid is velide or not 
        if(preg_match("/[^a-zA-Z0-9\.\_\@\-\$]+/", $emailId)){
            return "emailId is not valid";
        }
        
        if (!preg_match("/^[0-9]+$/", $pin))
        {
            return "Please enter valid pin.";
        }
    
       if($accountType == 1)
      {
          $tableName = '91_verifiedGtalkId';
      }
      elseif($accountType == 2)
      {
          $tableName = '91_verifiedSkypeId';
         
      }else
          return "account type is not valid";
      
    #check user Name already exist 
    $result = $this->selectData('*', $tableName, "email ='".$emailId."'");

    
        
    #get pin detail 
    $pinDetail = $this->getPinDetail($pin);
    $pinDataDetail = json_decode($pinDetail,TRUE);  //$pinGenerator,$pinTariff,$pinCurrency,$pinBalance   
        
    if($pinDataDetail['status'] == "error"){
        return $pinDataDetail['msg'];
    }
    
    
    #check the resulting value exists or not 
    if($result->num_rows > 0)
    {
        $row = $result->fetch_array(MYSQL_ASSOC);
        $userid = $row['userId'];
        $userTariff = $this->getUserTariff($userid);
        return $this->rechargeByPin($userid,$userTariff,$pinDataDetail['pinTariff'],$pinDataDetail['batchId'],$pin,$pinDataDetail['pinBalance']);
        
   }
    
    $userData = $this->createUser($pinDataDetail['pinGenerator'],$pinDataDetail['pinTariff'],$pinDataDetail['pinCurrency'],$pinDataDetail['pinBalance']); 
    $userDetail = json_decode($userData,TRUE);  
    
    if($userDetail['status'] == "error"){
        return $userDetail['msg'];
    }
    
   $userId = $userDetail['userId'];
    
   $data = array("email"=>$emailId,"userId"=>$userId);
   $resInsert = $this->insertData($data, $tableName);

    
   include_once("transaction_class.php");
   $transactionObj = new transaction_class();
   
   $transactionObj->fromUser = $pinDataDetail['pinGenerator'];
   $transactionObj->toUser = $userId;
   
   $transactionObj->addTransactional_sub($pinDataDetail['pinBalance'],$pinDataDetail['pinBalance'],"Pin User",0,0,0,"Create and Recharge by Pin");
            
   
    #update pin status 
    $table = '91_pinDetails';
    $data=array("usedDate"=>date('Y-m-d H:i:s'),"status"=>1,"usedBy"=>$userId); 
    $condition = "pincode='".$pin."'";
    $this->db->update($table, $data)->where($condition);	
    $this->db->getQuery();
    $result = $this->db->execute(); 
 
    $msg = "successfully id recharge by pin.. ";
    return $msg;
}

/**
 * @author sudhir pandey <sudhir@hostnsoft.com>
 * @commented on 16/06/14
 * @desc function use to get pin detail 
 * @return type 
 */
function getPinDetail($pin,$resellerId=0){
    
    # check pin valid or not 
    if(!isset($pin) || strlen($pin)<5)
        {
         return json_encode(array('status'=>'error','msg'=>'Invalid pin!',"code"=>"603")); 
        }
        
    if (!preg_match("/^[0-9]+$/", $pin))
        {
            return "Please enter valid pin.";
        }
    
        
    # get pin status (1 for used or 0 for unused).
    $table = '91_pinDetails';

    #selecting the item from table 91_pinDetails
    $this->db->select('*')->from($table)->where("pincode ='".$pin."'");
    $this->db->getQuery();

    #execute query
    $result=$this->db->execute();

    #check the resulting value exists or not 
    if($result->num_rows == 0)
      {
         return json_encode(array('status'=>'error','msg'=>'Invalid pin!',"code"=>"604")); 
      }

    $row = $result->fetch_array(MYSQL_ASSOC);
    if($row['status'] == 1){
        return json_encode(array('status'=>'error','msg'=>'pin already used by another user!',"code"=>"605")); 
    }
    
    $batchId = $row['batchId']; 
    
    $pinTable = '91_pin';
    if($resellerId != 0){
        $condition = "batchId = '" . $row['batchId'] ."' and userId =".$resellerId; //userId= '".$userid."' or 
    }else
        $condition = "batchId = '" . $row['batchId'] ."'"; //userId= '".$userid."' or 
    $this->db->select('*')->from($pinTable)->where($condition);
    $batchResult = $this->db->execute();

    // processing the query result
    if ($batchResult->num_rows == 0) {	
        return json_encode(array('status'=>'error','msg'=>'You Have No Permission To Use This Pin!',"code"=>"606")); 

    }

    $batchDetail = $batchResult->fetch_array(MYSQL_ASSOC);

    if(strtotime(date('Y-m-d',strtotime($batchDetail['expiryDate']))) < strtotime(date('Y-m-d'))){
         return json_encode(array('status'=>'error','msg'=>'Pin are expired !',"code"=>"607")); 
    }

    #pin tariff id 
    $pinTariff = $batchDetail['tariffId'];
       
    #find pin currency (call function_layer.php function) 
    $pinCurrency = $this->getOutputCurrency($batchDetail['tariffId']);
    
    #pin Generator id 
    $pinGenerator = $batchDetail['userId'];
    
    #pin balance
    $pinBalance = $batchDetail['amountPerPin'];
    
    #get batch name 
    $batchName = $batchDetail['batchName'];
    
    return json_encode(array('status'=>'success','pinGenerator'=>$pinGenerator,'pinTariff'=>$pinTariff,'pinCurrency'=>$pinCurrency,'pinBalance'=>$pinBalance,'batchId'=>$batchId,'batchName' =>$batchName)); 
    
}

/**
 * @author sudhir pandey <sudhir@hostnsoft.com>
 * @param int $resellerId
 * @param int $tariff_id
 * @param int $currency_id
 * @param float $balance
 * @param string $userName
 * @return type json 
 */
function createUser($resellerId,$tariff_id,$currency_id,$balance,$userName = NULL)
    {
       
        if (!preg_match("/^[0-9]+$/", $resellerId))
        {
           return json_encode(array("status"=>"error","msg"=>"Reseller id not valid.","code"=>"611"));
        }
        
        if (!preg_match("/^[0-9]+$/", $tariff_id))
        {
           return json_encode(array("status"=>"error","msg"=>"Tariff id not valid.","code"=>"611"));
        }
        
        if (!preg_match("/^[0-9]+$/", $currency_id))
        {
           return json_encode(array("status"=>"error","msg"=>"Currency id not valid.","code"=>"611"));
        }
        
        if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $balance)) {
            return json_encode(array("status" => "error", "msg" => "Please enter valid talktime.","code"=>"611"));
        }
       
        
        $table = '91_userLogin';
        $condition = "userId = '".$resellerId."'";
        $this->db->select('isBlocked,deleteFlag')->from($table)->where($condition);        
        $loginresult = $this->db->execute();
        if ($loginresult->num_rows > 0) {
            $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
            $blockUnblockStatus = $logindata['isBlocked'];
            $deleteFlag = $logindata['deleteFlag'];
        }
        else
        {
            return json_encode(array("status"=>"error","msg"=>"Error Unable to fetch the reseller details Please Try again","code"=>"611"));
        }
        
     #select user name  
     if($userName != NULL){
         
         if(!preg_match('/^[a-zA-Z][a-zA-Z0-9\_\-\s]+$/', $userName)){
           return json_encode(array("status" => "error", "msg" => "Username is not valid , Must be alphanumeric, with at least 1 character."));
        }
        
         $userInfo = $this->getUserInformation($userName);
         if(count($userInfo) > 0){
            $userName = $this->createUsername($resellerId); 
         }else
            $userName = $userName; 
     }else       
     $userName = $this->createUsername($resellerId);
     
     $password = $this->createUsername($resellerId);     
        
      #insert userdetail into database       
      $data=array("name"=>$userName,"date"=>date('Y-m-d H:i:s')); 
      $personalTable = '91_personalInfo';
      #insert query (insert data into 91_personalInfo table )
      $personalResult = $this->insertData($data, $personalTable);

      #check data inserted or not 
      if(!$personalResult){
    //        $this->sendErrorMail("sameer@hostnsoft.com", "Phone91 signup_class personal info table query fail : $qur ");
        return json_encode(array("status"=>"error","msg"=>"pin User not created !","code"=>"612"));
          
      }
      
           
      $userid = $this->db->insert_id;
      
      #insert login detail into login table database 
      $loginTable = '91_userLogin';
      $data=array("userId"=>$userid,"userName"=>$userName,"password"=>$password,"isBlocked"=>$blockUnblockStatus,"deleteFlag"=>$deleteFlag,"type"=>5,"beforeLoginFlag"=>2); 

      #insert query (insert data into 91_userLogin table )
      $loginResult = $this->insertData($data, $loginTable);

      #check data inserted or not 
      if(!$loginResult){
          $this->deleteData($personalTable, "userId = ".$userid);
//         $this->sendErrorMail("rahul@hostnsoft.com", "Phone91 signup_class userlogin  table query fail : $qur ");
         return json_encode(array("status"=>"error","msg"=>"pin User not created!","code"=>"613"));
          
      }
      
      #get last chain id from user balance table  
      $lastchainId = $this->getlastChainId($resellerId);
      
      if(!$lastchainId || $lastchainId == "" ){
          $this->deleteData($personalTable, "userId = ".$userid);
          $this->deleteData($loginTable, "userId = ".$userid);
           return json_encode (array("msg"=>"Internal sever error cant create user please try again","status"=>"error","code"=>"614"));
      }
      
      #new chain id (incremented id of lastchain id )
      $chainId = $this->newChainId($lastchainId);
      
       if(strlen($chainId) < 1 || $chainId == NULL){
          $this->deleteData($personalTable, "userId = ".$userid);
          $this->deleteData($loginTable, "userId = ".$userid);
          return json_encode (array("msg"=>"Internal sever error cant create user please try again","status"=>"error","code"=>"615"));
       }
      
      #insert login detail into login table database 
      $balanceTable = '91_userBalance';
     
      $data=array("userId"=>(int)$userid,"chainId"=>$chainId,"tariffId"=>(int)$tariff_id,"balance"=>$balance,"currencyId"=>(int)$currency_id,"callLimit"=>2,"resellerId"=>(int)$resellerId); 

      #insert query (insert data into 91_userLogin table )
      $balanceResult = $this->insertData($data, $balanceTable);
      if (!$balanceResult){
          $this->deleteData($personalTable, "userId = ".$userid);
          $this->deleteData($loginTable, "userId = ".$userid);
          return json_encode(array("status"=>"error","msg"=>"pin User not created!","code"=>"616"));  
      }
      return json_encode(array("status"=>"success","userId"=>$userid));  
    }

    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param int $userid
     * @param int $userTariff
     * @param int $pinTariff
     * @param int $batchId
     * @param int $pin
     * @param float $pinAmount
     * @return type json 
     */
    function rechargeByPin($userid,$userTariff,$pinTariff,$batchId,$pin,$pinAmount)
	{
            
            if (!preg_match("/^[0-9]+$/", $userid))
            {
            return json_encode(array("status"=>"error","msg"=>"User id not valid.","code"=>"608"));
            }

            if (!preg_match("/^[0-9]+$/", $userTariff))
            {
            return json_encode(array("status"=>"error","msg"=>"User tariff id not valid.","code"=>"608"));
            }
            
            if (!preg_match("/^[0-9]+$/", $pinTariff))
            {
            return json_encode(array("status"=>"error","msg"=>"Pin tariff id not valid.","code"=>"608"));
            }
            
            if (!preg_match("/^[0-9]+$/", $batchId))
            {
            return json_encode(array("status"=>"error","msg"=>"Batch id not valid.","code"=>"608"));
            }
            
            if (!preg_match("/^[0-9]+$/", $pin))
            {
            return json_encode(array("status"=>"error","msg"=>"User tariff id not valid.","code"=>"608"));
            }
            
            if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $pinAmount)) {
                return json_encode(array("status" => "error", "msg" => "Please enter valid pin amount.","code"=>"608"));
            }
        
        
            #get ResellerId of user 
            $resellerId = $this->getResellerId($userid);
                        
            #find pin generateor id
            $pinTable = '91_pin';
            $condition = "batchId = '" . $batchId . "' and (userId= '".$resellerId."') "; //userId= '".$userid."' or 
            $this->db->select('*')->from($pinTable)->where($condition);
            $batchResult = $this->db->execute();

            // processing the query result
            if ($batchResult->num_rows == 0) {	
                return json_encode(array('status'=>'error','msg'=>'You Have No Permission To Use This Pin!',"code"=>"608")); 
                
            }
            
            
            #find pin and user currency (call function_layer.php function) 
            $pinCurr = $this->getOutputCurrency($pinTariff);
            $userCurr = $this->getOutputCurrency($userTariff);
            
            if($pinCurr != $userCurr){
                 return json_encode(array('status'=>'error','msg'=>'you can not use this pin because pin currency not match.',"code"=>"609")); 
            }
            
            $table = '91_pinDetails';
            
            #update pin status 
            $data=array("usedDate"=>date('Y-m-d H:i:s'),"status"=>1,"usedBy"=>$userid); 
            $condition = "pincode='".$pin."'";
            $this->db->update($table, $data)->where($condition);	
            $this->db->getQuery();
            $result = $this->db->execute();
            
            
            #recharge pin entry in transaction log 
            $amountPerPin = $pinAmount;
            
            
            include_once("transaction_class.php");
            $transactionObj = new transaction_class();
            
             #update current balance of user in userbalance table 
            $transactionObj->updateUserBalance($userid,$amountPerPin,'+');
            
            
            $getBalance = $transactionObj->getClosingBalance($userid);
            
            //set from user and toUser
            $transactionObj->fromUser = $resellerId;
            $transactionObj->toUser = $userid;
            
            $msg = $transactionObj->addTransactional_sub($amountPerPin,$amountPerPin,"Calling Card",$amountPerPin,0,$getBalance,"Recharge by Pin");
            
            
            $currentBal = $transactionObj->getcurrentbalance($userid);
            if($result){
            return json_encode(array('status'=>'success','msg'=>'successfully recharge!','currentBal'=>$currentBal)); 
            }else
            {
                return json_encode(array('status'=>'error','msg'=>'error in recharge by pin!' ,"code"=>"610")); 
            }
            
         	
	} 
        
     /**
      * @author sudhir pandey <sudhir@hostnsoft.com>
      * @param type $userid
      * @return boolean 
      */   
     function getUserTariff($userid){
        
         if (!preg_match("/^[0-9]+$/", $userid))
            {
            return false;
            }
         
        #get ResellerId of user 
        $loginTable = '91_userBalance';

        #get reseller id for user 
        $this->db->select('*')->from($loginTable)->where("userId = '" .$userid. "'");

        $result = $this->db->execute();
        if($result)
        {
            $row = $result->fetch_array(MYSQL_ASSOC);
            $userTariff = $row['tariffId'];
            return $userTariff;
        }
        else
            return false;

        }
        
   /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param int $to sms send to 
     * @param int $userId
     * @param int $currency currency id 
     * @param int $balance 
     */
    function callingCardSendSms($to,$userId,$currency,$balance,$currentBal = NULL){
        
        $tempparam['to'] = $to;
        $tempparam["password"] = "AWIKSeLIcPFHBS";
        $tempparam["apiId"] = "3468158";
        
        #get user detail 
        $userDetail = $this->getUserInformation($userId,1);
        
        $currencyName = $this->getCurrencyViaApc($currency,1);
        
        if($currentBal != NULL){
          $tempparam['text'] = "Your account has been successfully recharged. A/c credited for : ".$balance." ".$currencyName." current A/c Bal : ".round($currentBal,2)." ".$currencyName;  
        }else
        $tempparam['text'] = "Your account has been successfully Created in phone91 your userName is ".$userDetail['userName']." and password is ".$userDetail['password']." A/c Bal : ".$balance." ".$currencyName ;
        
         if (substr($to,0,2) == "91")
        {
            $nine['sender'] = "Phonee";
            $nine['mobiles'] = $to; // mobile number without 91
            $nine['message'] = $tempparam['text'];
            $this->SendSMS91($nine);
        }
        else
        $this->SendSMSUSDnew($tempparam);
        
    }
    
    function addVerifedNumber($userId,$countryCode,$tempNumber,$domainResellerId){
        
        $table = "91_verifiedNumbers";
        $key = $this->generatePassword(4);
        $data=array("userId"=>(int)$userId,"countryCode"=>(int)$countryCode,"verifiedNumber"=>$tempNumber,"verifiedDate"=>date('Y-m-d H:i:s'),"domainResellerId"=>$domainResellerId,"confirmCode"=>$key,"isDefault"=>1); 

        #insert query (insert data into 91_tempcontact table )
        $result = $this->insertData($data, $table);	
        if(!$result)
         return 0;
        else
         return 1;
                
    }
    
    function callingCardLog($senderId,$accessNumber,$cardNumber,$batchName,$description){
        
        $table = "91_callingCardLog";
        $data=array("senderId"=>$senderId,"accessNumber"=>$accessNumber,"cardNumber"=>$cardNumber,"date"=>date('Y-m-d H:i:s'),"batchName"=>$batchName,"description"=>$description); 
       
        #insert query (insert data into 91_tempcontact table )
        $result = $this->insertData($data, $table);	
        
        if(!$result)
         return 0;
        else
         return 1;
                
    }
    
    function updateTransLogAndPinStatus($resellerId,$userId,$pinBlanace,$pin,$batchName=''){
   
        include_once("transaction_class.php");
        $transactionObj = new transaction_class();

        $transactionObj->fromUser = $resellerId;
        $transactionObj->toUser = $userId;

        $transactionObj->addTransactional_sub($pinBlanace,$pinBlanace,"Calling Card",0,0,0,"Recharge by Pin",0,$batchName);


        #update pin status 
        $table = '91_pinDetails';
        $data=array("usedDate"=>date('Y-m-d H:i:s'),"status"=>1,"usedBy"=>$userId); 
        $condition = "pincode='".$pin."'";
        $this->db->update($table, $data)->where($condition);	
        $this->db->getQuery();
        $result = $this->db->execute(); 
        if(!$result)
        return 0;
        else
            return 1;
    }
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 16-06-14
     * @desc function use to get rate of country according to tariff plan 
     * @param $action  = 1 loadDetails {"Mobile":90.96,"Bsnl Mobile":90.96,"Other":152.71}  and 0 loadDetail {"india","pakistan"}  
     */
     function searchRate($country,$currency,$action=1){
         
        $isDetail = 0;

        if($action == 1)
            $isDetail=1;
        
        if(!preg_match('/^[a-zA-Z0-9\_\-\s]+/', $country))
        {
        return array();
        }   
    
    
	$cntry=0;
	$cntry = strtolower($country);
	$reg_ex = '/^0+[1-9]*/';
	if(preg_match($reg_ex,$cntry))								
	$mobile_no[$i]= preg_replace('/0*/','',$mobile_no[$i],1);
	
	if (!$cntry) return array();
	
	$search_qry;
        
        #check tariff id selected by user for search rates otherwise check session tariff id 
        if($currency!=''){
          
             if (!preg_match("/^[0-9]+$/", $currency)) {
                return array();
            }
               $cur=$currency;	
        }else
           $cur = 84;
                
        $dbh=$this->connecti();
        if(!is_numeric($cntry))
        {
            $search_qry="select distinct description as countryName,prefix,voiceRate,operator from 91_tariffs where (countryName like '".$cntry."%' || description like '".$cntry."%') and tariffId like '".$cur."' ";

            #find all rates in ascending order
            $search_qry.="ORDER BY voiceRate ASC";
        }
        else
        {
            // $search_qry="select distinct countryName,prefix,voiceRate,operator from 91_tariffs where tariffId like '".$cur."' and prefix like '".$cntry."%'";
            $cntry = (int)$cntry;
            $len_max=strlen($cntry);
            $search_qry="select distinct description as countryName,prefix,voiceRate,operator from 91_tariffs where tariffId like '".$cur."' and ( ";
            for ($exts_i = $len_max; $exts_i >= 1; $exts_i--) { //To Get The Number of digits from starting of mobile number entered
                $search_qry.="prefix='" . substr($cntry, 0, $exts_i) . "' OR ";
            }
            $search_qry = substr($search_qry, 0, -4);
            $search_qry = $search_qry. ")";
            
            #if contact no length is greter then 7 then find actual rate of contact no. 
            if($len_max > 7){
                $search_qry.=" order by length(prefix) desc limit 1";
            }else
                $search_qry.="ORDER BY voiceRate ASC";

            
        } 
        
        
        $exe_qry=mysqli_query($dbh,$search_qry) or die(mysqli_error());

        $final = array();
	if(mysqli_num_rows($exe_qry)>0)
	{
	    
		$ct=0;
		while($res=mysqli_fetch_array($exe_qry))
		{
			
                        #if action is not loadDetails then 1 oterwise 0 
			if($isDetail!=1)
			{
                                #item value is a country name 
                                $item=  trim($res['countryName']);
                                $final[]=$item;
                                                      
			}
			else
			{
			    #operator name 
                            $operat = $res['operator'];
                            
                            #if operator name is not present then set country name as a operator 
                            if(!isset($res['operator']))
					$operat=$res['countryName'];
                                
                                
			    $desc = $operat;
                            $item[$desc]=($res['voiceRate'] * 100);
			    $final=$item;
			}

		}
	}
	
	mysqli_close($dbh);
        
        if($isDetail==1) {   
            $a= "";
            
        #call function manageClients and return json data clientJson
        $curency=$this->getOutputCurrency($cur);
        $currencyName = $this->getCurrencyViaApc($curency,1);
        
        $final['currency'] = $currencyName;
            
        }else
            $final=  array_values(array_unique($final));

        
return $final;        
        
} 
     
    
    
    
    
}


?>
