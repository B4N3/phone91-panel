<?php

/**
 * @author  Rahul <rahul@hostnsoft.com>
 * @modified by sudhir <sudhir@hostnsoft.com>
 * @since 03-08-2013
 * @package Phone91
 * @details class use to reseller panel (add client,add bulk user)  
 */

include dirname(dirname(__FILE__)) . '/config.php';

class reseller_class extends fun 
{
    
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 27/08/2013
    #function use to edit fund of user (add or reduce user balance) 
    function editFund($parm,$userid)
    {      
      
      if (preg_match("/[^0-9]+/", $parm['toUserEditFund']) || $parm['toUserEditFund'] == "" ) {
            return json_encode(array("status" => "error", "msg" => "Invalid client user please select a user to transfer fund"));
      }
      
      #check user have permission for edit fund or not 
      $resellerId = $this->getResellerId($parm['toUserEditFund']);  
      
      if($resellerId != $userid){
          return json_encode(array("status" => "error", "msg" => "You are not authorized to edit talktime."));
      }
      
      if($parm['fundAmount'] < 0){
          return json_encode(array("status" => "error", "msg" => "Please enter numbers only."));
      }
        
      if($parm['balance'] < 0){
          return json_encode(array("status" => "error", "msg" => "Please enter numbers only."));
      }
      
      if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $parm['fundAmount'])) {
            return json_encode(array("status" => "error", "msg" => "Maximum 3 digits are accepted after decimal."));
      }
      
      if (preg_match("/[^0-9]+/", $parm['fundCurrency']) || strlen($parm['fundCurrency']) < 1 ) {
            return json_encode(array("status" => "error", "msg" => "fund currency is not valid ! "));
      }
      
      
        
      if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $parm['balance'])) {
            return json_encode(array("status" => "error", "msg" => "Maximum 3 digits are accepted after decimal."));
        }  
      
      if(strlen(trim($parm['balance'])) > 8){
                return json_encode(array("status" => "error", "msg" => "Please do not enter more than 8 digits."));
            }  
       
      if(strlen(trim($parm['fundAmount'])) > 8){
                return json_encode(array("status" => "error", "msg" => "Please do not enter more than 8 digits."));
            }        
      
      if($parm['pType'] == "partial"){
           
           if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $parm['partialAmt'])) {
            return json_encode(array("status" => "error", "msg" => "Maximum 3 digits are accepted after decimal."));
            }
            
            if(strlen(trim($parm['partialAmt'])) > 8){
                return json_encode(array("status" => "error", "msg" => "Please do not enter more than 8 digits."));
            }
            
            if (preg_match("/[^0-9]+/", $parm['partialCurrency']) || strlen($parm['partialCurrency']) < 1 ) {
            return json_encode(array("status" => "error", "msg" => "partial currency is not valid ! "));
      }
            
       }
       
       if($parm['changefunderEditFund'] != "add" && $parm['changefunderEditFund'] != "reduce" )
       {
           return json_encode(array("status" => "error", "msg" => "Invalid input please contact support"));
       }
       
       if(preg_match("/[^a-zA-Z]+/",$parm['fundPaymentType']) || $parm['fundPaymentType'] == "")
               return json_encode(array("status" => "error", "msg" => "please select a proper fund type"));
       
//       if(preg_match("/[^a-zA-Z0-9\@\$\%\^\&\*\(\)\:\<\>\?\\\/\,\.\_\-\|]+/",$param['fundDescription']))
       if(preg_match("/[\"\'\#\<\>]+/",$parm['fundDescription']))
               return json_encode(array("status" => "error", "msg" => "Please enter a valid description."));
       
       if($parm['pType'] != 'postpaid'){
       if(isset($parm['fundPaymentType']) && $parm['fundPaymentType'] == "Other")
       {
          if(preg_match("/[^a-zA-Z\s]+/",$parm['otherPaymentType']) || $parm['otherPaymentType'] == "")
                  return json_encode(array("status" => "error", "msg" => "Please enter a proper other payment type."));
       }
       }
        
    
      
      # variable fundAmount use to which amount will be update
      $fundAmount = $parm['fundAmount'];
      $talktime = $parm['balance'];
      #variable ptype : Payment Type (partial ,postpaid,prepaid) 
      $pType = $parm['pType'];
      
     
      #check balance add or reduce in currentbalance 
      if($parm['changefunderEditFund'] != "add")
      {
            $talktime = ((int)-$parm['balance']); 

            // fund amount 
            $fundAmount = ((int)-$parm['fundAmount']);
            $pType = ""; 

            $sign = '-';
      }
      else
            $sign = '+';
      
      //*** entry in transaction log table
      if($parm['fundPaymentType'] == "Other")
      {
            $fundpaymentType = $this->db->real_escape_string($parm['otherPaymentType']);     
      }
      else
            $fundpaymentType = $parm['fundPaymentType'];
      
      #include transaction class   
      include_once("transaction_class.php");
      
      #object of transaction class
      $transaction_obj = new transaction_class();
      
      //set touser and from user
      $transaction_obj->toUser = $parm['toUserEditFund'];
      $transaction_obj->fromUser = $userid;
      
      #update user balance table 91_userbalance table
      $transaction_obj->updateUserBalance($parm['toUserEditFund'],$parm['balance'],$sign); 
      
      #add transaction in case of voip91(payment type advance).
      $result = $transaction_obj->addTransactional($fundAmount,$talktime,$fundpaymentType,$parm['fundDescription'],$pType,$parm['partialAmt'],$parm['fundCurrency'],$parm['partialCurrency']);
      $returnBalanace = $transaction_obj->getcurrentbalance($parm['toUserEditFund']);
      //free object space
      unset($transaction_obj);
      
      if($result == 1)
      {
          return json_encode(array("status" => "success", "msg" => "successfully update user fund .","balance"=>$returnBalanace));
      }
      else
          return json_encode(array("status" => "error", "msg" => "Problem while updating talktime. Please try again."));
      
    }
    #created by sudhir pandey <sudhir@hostnsoft.com> 
    #creation date 07/08/2013
    #function use to edit client information 
    function editClientInfo($parm,$userId,$type){
        
         #table name 
         $table = "91_userBalance";
         
         if (!preg_match("/^[0-9]+$/", $parm['callLimit'])) {
            return json_encode(array("status" => "error", "msg" => "Please enter numbers only."));
        }
        
         if (!preg_match("/^[0-9]+$/", $parm['currenctTariff'])) {
            return json_encode(array("status" => "error", "msg" => "please select valid tariff ! "));
        }
        
        if(isset($parm['bandwidthLimit'])){
            $bandwidthLimit = $parm['bandwidthLimit'];
        }else
            $bandwidthLimit = 0;
        
         if (!preg_match("/^[0-9]+$/", $bandwidthLimit)) {
            return json_encode(array("status" => "error", "msg" => "Please enter a valid Bandwidth Limit."));
        }
        
       if (!preg_match("/^[0-9]+$/", $parm['clientId'])) {
            return json_encode(array("status" => "error", "msg" => "Please enter a valid client Id."));
        }
        
      #check permission for add transaction or not 
      $resellerId = $this->getResellerId($parm['clientId']);  
      
      #if type =1 then admin and he has a permission for update 
      if($type != 1){
      if($resellerId != $userId){
          return json_encode(array("status" => "error", "msg" => "You are not authorized to edit client info."));
      }
      }
        
        #get currency id
       
        $currency_id = $this->getOutputCurrency($parm['currenctTariff']);
        
        
         #update balance amount of user 
         $data=array("callLimit"=>$parm['callLimit'],"tariffId"=>$parm['currenctTariff'],"currencyId"=>$currency_id,"bandwidthLimit"=>$bandwidthLimit); 
         $condition = "userId=".$parm['clientId']." ";
         $this->db->update($table, $data)->where($condition);	
         #get update sql query 
         $qur = $this->db->getQuery();
         $results = $this->db->execute();
         
         #update entry in adminLog table 
         $this->updateLog($parm,$userId);
         
         if($results)
         {
           trigger_error('problem while update balance ,query:'.$qur.' userId:'.$userId.' resellerId:'.$resellerId); //trigger error 
           
           return json_encode(array("status" => "success", "msg" => "User information updated successfully."));
         }  else {
             
           return json_encode(array("status" => "error", "msg" => "Please try updating again."));
         }
       
    }
    
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 23/10/2013
    #function use to add entry in admin log tabel for update call limit and tariff paln 
    function updateLog($parm,$takenBy){
       
       
        if($parm['oldCallLimit'] != $parm['callLimit']){
            
            $this->accountManagerLog($parm['clientId'],6,$parm['oldCallLimit'],$parm['callLimit'],$takenBy,"update call limit");
                      
        }
        
        if($parm['hideTariff'] != $parm['currenctTariff']){
            
            $this->accountManagerLog($parm['clientId'],2,$parm['hideTariff'],$parm['currenctTariff'],$takenBy,"update tariff plan");
            
        }
        
        #check bandwidth limit 
        if(isset($parm['bandwidthLimit'])){
            if($parm['oldbandwidthLimit'] != $parm['bandwidthLimit']){
            
            $this->accountManagerLog($parm['clientId'],7,$parm['oldbandwidthLimit'],$parm['bandwidthLimit'],$takenBy,"update bandwidth Limit");
            
        }
        }
        
        
        if($parm['oldmanager'] != $parm['accountManager']){
            $this->accountManagerLog($parm['clientId'],4,$parm['oldmanager'],$parm['accountManager'],$takenBy,"update manager");
            
        }
        
    }
    
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 04/09/2013
    #function use to get User batch detail by batchId 
    function getBatchDetail($batchId,$pageNo=1,$isadmin=0 , $export = 0){
        
         //take limit to show
        $limit = 15; 
        //get skip for pagination
        $skip = $limit*($pageNo-1); 
        
        #table name 
        $table = '91_bulkUser';
        if($isadmin == 1){
            $condition = "batchId = '".$batchId."'";
        }else
        $condition = "batchId = '".$batchId."' and userId = '".$_SESSION['userid']."'";
        $this->db->select('*')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log the error if error occur 
        if(!$result || $result->num_rows < 1){
            trigger_error('problem while get batch detail ,query:'.$qur);
            
            
        }
         if ($result->num_rows > 0) {
         $row = $result->fetch_array(MYSQLI_ASSOC);	    
             
              $userBatchData['batchId'] = $row['batchId'];
              $userBatchData['batchName'] = $row['batchName'];
              $userBatchData['numberOfClients'] = $row['numberOfClients'];
              $userBatchData['expiryDate'] = $row['expiryDate'];
              $userBatchData['resellerId'] = $row['userId'];
              $userBatchData['createDate'] = $row['createDate'];
            }
            
        #find user balance and user id for all user of given batch id     
        $table = '91_userBalance';
        $condition = "userBatchId = '".$batchId."'";
        if($export)
        {
             $this->db->select('*')->from($table)->where($condition); 
        }else
        $this->db->select('*')->from($table)->where($condition)->limit($limit)->offset($skip);
        
        $qur = $this->db->getQuery();
        $result = $this->db->execute();  
        
         //get total count
        $this->db->select('*')->from($table)->where($condition);
        $this->db->getQuery();
        $resultCnt = $this->db->execute();
        if(!$resultCnt)
            trigger_error('problem while get total count in pagination in batch detail!!! SQL:'.$this->db->getQuery());
        $count = $resultCnt->num_rows;
        $userBatchData['totalCount'] = ceil($count/$limit);
        
        //log the error if error occur 
        if(!$result)
            trigger_error('problem while get user Balance ,query:'.$qur);
        
        
        if ($result->num_rows > 0) {
          while ($data= $result->fetch_array(MYSQL_ASSOC) ) {
              $userdata['userId'] = $data['userId'];
              $userdata['balance']= $data['balance'];
              $userdata['status']= $data['status'];
              $userdata['currecyName'] = $this->getCurrencyViaApc($data['currencyId']);
              
              #find user name and password 
               $table = '91_userLogin';
               $condition = "userId = '".$data['userId']."'";
               $this->db->select('*')->from($table)->where($condition);
               $qur = $this->db->getQuery();
               $loginresult = $this->db->execute();
               
               //log the error if error occur 
               if(!$loginresult)
                    trigger_error('problem while get user Login ,query:'.$qur);
               
               if ($loginresult->num_rows > 0) {
               $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
              
             $userdata['userName'] = $logindata['userName'];
             $userdata['password'] = $logindata['password']; 
             $userdata['deleteFlag'] = $logindata['deleteFlag'];
             $userdata['blockUnblockStatus'] = $logindata['isBlocked'];
             $sipStatus = $logindata['sipFlag'];
               }
             $userBatchData['userDetail'][] = $userdata;
             $userBatchData['sipStatus'] = $sipStatus;

             unset($userdata);
             unset($data);
          }     
        }else
             $userBatchData['userDetail'] = array(); 
        
       
        
      return json_encode($userBatchData);       
        
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 05/09/2013
    #function use to change bulk client status used or unused username and password . 
    function changeBulkClientStatus($parm,$resellerId){
       
        if (!preg_match("/^[0-9]+$/", $parm['userId'])) {
            return json_encode(array("status" => "error", "msg" => "Please enter a valid user Id."));
        }
        
        #check user permission for update status  
        $checkId = $this->getResellerId($parm['userid']); 
        if($checkId != $resellerId){
            return json_encode(array("status" => "error", "msg" => "you have no permession for update status."));
        }
                
        #table name 
        $table = "91_userBalance";
        #update balance amount of user 
        $data=array("status"=>$parm['status']); 
        $condition = "userId=".$parm['userId']." ";
        $this->db->update($table, $data)->where($condition);	
        #get update sql query 
        $qur = $this->db->getQuery();
        $results = $this->db->execute();
        if($results){
             return json_encode(array("status" => "success", "msg" => "successfully status update."));
        }
        else
        {
            trigger_error('problem while get user balance ,query:'.$qur);
            return json_encode(array("status" => "error", "msg" => "problem while update user balance"));
        }
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 05/09/2013
    #code for enabel bulk client sip 
    function batchSipEnabel($param,$resellerId){
       
//        #check user permission for update status  
//        $checkId = $this->getResellerId($parm['userid']); 
//        if($checkId != $resellerId){
//            return json_encode(array("status" => "error", "msg" => "you have no permession for update status."));
//        }
        
        
        
        if(preg_match("/[^0-9]+/",$param['batchId']) || $param['batchId'] == "")
            return json_encode (array("msg"=>"Invalid batch id","status"=>"error"));
        
//        #get user id from batch id 
//        $creatorId = $this->getbatchCreatorId($param['batchId']);
//        
//        if($creatorId != $resellerId){
//           return json_encode(array("status" => "error", "msg" => "you have no permession for enable batch sip.")); 
//        }
        
        $updateFail = array();
        $table = '91_userBalance';
        $condition = "userBatchId = '".$param['batchId']."'";
        $this->db->select('*')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();  
        
        //log the error if error occur 
        if(!$result)
            trigger_error('problem while get user id ,query:'.$qur);
        
        
        if ($result->num_rows > 0) {
          while ($data= $result->fetch_array(MYSQL_ASSOC) ) {
               $msg =$this->enableSip($data['userId'],1);
               $resultData = json_decode($msg, TRUE);
               if($resultData['status'] != "success"){
                   $updateFail[] = $data['userId'];
               }
               
          }
          return json_encode (array("msg"=>"successfully Batch Sip Enable","status"=>"success")); 
        }else{
           return json_encode (array("msg"=>"Batch user sip not enable","status"=>"error")); 
        }
        
        
    }
    
    function getbatchCreatorId($batchId){
        
        $table = '91_bulkUser';
        $condition = "batchId = '".$batchId."'";
        $this->db->select('*')->from($table)->where($condition);
        $result = $this->db->execute();
        $userId='';
        //log the error if error occur 
        if(!$result){
            trigger_error('problem while get batch detail ,query:');
        }
         if ($result->num_rows > 0) {
         $row = $result->fetch_array(MYSQL_ASSOC);	    
             $userId = $row['userId'];
         }
         return $userId;
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 29-07-2013
    #function use to add client detial
    /*
     * 
     */
    function addNewClient($parm, $resellerId) {
        
               
        #check username is blank or not
        if ($parm['username'] == '' || $parm['username'] == NULL) {
            return json_encode(array("status" => "error", "msg" => "Please insert user name ."));
        }

        if(!preg_match('/^[a-zA-Z][a-zA-Z0-9\_\-\s]+$/', $parm['username'])){
           return json_encode(array("status" => "error", "msg" => "Username is not valid , Must be alphanumeric, with at least 1 character."));
        }
      
        
        #check country name is selected or not  
        if ($parm['country'] == "select_country") {
            return json_encode(array("status" => "error", "msg" => "Please Select Country Name"));
        }

        #remove all 0 form starting of number 
        $parm['contactNumber'] = ltrim($parm['contactNumber'],'0'); 
      
        #check contact no is valid or not 
        if (!preg_match("/^[0-9]{8,15}$/", $parm['contactNumber'])) {
            return json_encode(array("status" => "error", "msg" => "contact no. are not valid!"));
        }
        
        #check country code no is valid or not 
        if (!preg_match("/^[0-9]{1,4}$/", $parm['contactNo_code'])) {
            return json_encode(array("status" => "error", "msg" => "country code are not valid!"));
        }

        #check email id is valid or not 
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $parm['email'])) {
            return json_encode(array("status" => "error", "msg" => "email id is not valid !"));
        }

        #check tariff paln is selected or not 
        if (strtolower($parm['tariff']) == "select" || $parm['tariff']=='' || $parm['tariff'] ==0) {
            return json_encode(array("status" => "error", "msg" => "Please Select Tariff Plan ! "));
        }
        
        #check tariff plan no is valid or not 
        if (!preg_match("/^[0-9]{1,5}$/", $parm['tariff'])) {
            return json_encode(array("status" => "error", "msg" => "Tariff plan are not valid!"));
        }

        #chech payment type is selected or not 
//        if ($parm['payType'] == "select") {
//            return json_encode(array("status" => "error", "msg" => "Please Select Payment Type ! "));
//        }

        #check total no of pins is numeric or not 
//        if (!preg_match("/^[0-9]+$/", $parm['clientBalance'])) {
//            return json_encode(array("status" => "error", "msg" => "Numeric value required in balance field ! "));
//        }password
        
        if(!preg_match('/^[a-zA-Z0-9\@\_\-\!\$\(\)\?\[\]\{\}\s]+$/', $parm['password'])){
           return json_encode(array("status" => "error", "msg" => "please enter a valid password must not containg any spacial character other than '@_-!$()[]{}?' "));
        }
        
        #check for user type is valid or not  (3 -> user or 2 -> reseller)
        if ($parm['userType'] != 2 && $parm['userType'] != 3) {
            return json_encode(array("status" => "error", "msg" => "Please Select User Type ! "));
        }

        if(isset($parm['name'])){
            if(!preg_match('/^[a-zA-Z][a-zA-Z0-9\_\-\s]+$/', $parm['name'])){
                return json_encode(array("status" => "error", "msg" => "name is not valid , Must be alphanumeric, with at least 1 character."));
            }
            $name = $parm['name'];
        }else
         $name = $this->db->real_escape_string($parm['username']);
        
        //to check  phoneno already existes or not
        $table = '91_verifiedNumbers';
//        echo "verifiedNumber = '" . $parm['contactNumber'] . "' and countryCode = '" . $parm['contactNo_code'] . "'";
        $this->db->select('*')->from($table)->where("verifiedNumber = '" . $parm['contactNumber'] . "' and countryCode = '" . $parm['contactNo_code'] . "'");
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        
        //log error
        if(!$result)
            trigger_error('problem while get verify number details ,query:'.$qur);
        
        if ($result->num_rows > 0) {
            return json_encode(array("status" => "error", "msg" => "Phone number already in use by another user!"));
        }

        //to check  email address already exists or not 
        $table = '91_verifiedEmails';
        $this->db->select('*')->from($table)->where("email = '" . $parm['email'] . "'");
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        ////log error
        if(!$result)
            trigger_error('problem while get verify number details ,query:'.$qur);
        
        if ($result->num_rows > 0) {
            return json_encode(array("status" => "error", "msg" => "This email address already registered!"));
        }

        #check username already register
        $loginTable = '91_userLogin';
        $this->db->select('*')->from($loginTable)->where("userName = '" . $parm['username'] . "'");
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        ////log error 
        if(!$result)
            trigger_error('problem while get user login details ,query:'.$qur);
        
        if ($result->num_rows > 0) {
            return json_encode(array("status" => "error", "msg" => "sorry username already registered!"));
        }
        
        #get reseller block unblock status from login table 
        $table = '91_userLogin';
        $condition = "userId = '".$resellerId."'";
        $this->db->select('*')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $loginresult = $this->db->execute();
        
         ////log error
        if(!$loginresult)
            trigger_error('problem while get user login details ,query:'.$qur);
        
        if ($loginresult->num_rows > 0) {
        $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
        $blockUnblockStatus = $logindata['isBlocked'];
        $deleteFlag = $logindata['deleteFlag'];
         }else
        {
            return json_encode(array("status"=>"error","msg"=>"Error Unable to fetch the reseller details Please Try again"));
        }
        
        #get reseller rout id and dial plan id 
        $userRoutDetail = $this->getUserBalanceInfo($resellerId);
        
        #set route id if not get
        if($userRoutDetail['routeId'] == '' || $userRoutDetail['routeId'] == NULL){
            $userRoutDetail['routeId'] = 1;
        }
        #set dialplan if not get
        if($userRoutDetail['isDialPlan'] == '' || $userRoutDetail['isDialPlan'] == NULL){
            $userRoutDetail['isDialPlan'] = 0;
        }    
        
        
        #insert user detail in personal info table
        $personalTable = '91_personalInfo';
        
        $data = array("name" => $name,"date"=>date('Y-m-d H:i:s'));
        #insert query (insert data into 91_personalInfo table )
        $this->db->insert($personalTable, $data);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        #check data inserted or not 
        if (!$result) 
        {
             ////log error
             trigger_error('problem while get user personal info ,query:'.$qur);
             return json_encode(array("status" => "error", "msg" => "add user process fail! "));
        }

        #user id 
        $userId = $this->db->insert_id;

        #insert login detail into login table database 
        $loginTable = '91_userLogin';
        $pass = $this->db->real_escape_string($parm['password']);
        $data = array("userId" => (int) $userId, "userName" => $parm['username'], "password" => $pass, "isBlocked" => $blockUnblockStatus,"deleteFlag"=>$deleteFlag, "type" => $parm['userType'],"beforeLoginFlag"=>2);

        #insert query (insert data into 91_userLogin table )
        $this->db->insert($loginTable, $data);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        //var_dump($result);
        #check data inserted or not 
        if (!$result) 
        {
             ////log error
             trigger_error('problem while insert user personal info ,query:'.$qur);
            $this->deleteData($personalTable, "userId = ".$userId);
            return json_encode(array("status" => "error", "msg" => "add user process fail !"));
        }


        #user balance from plan table  
        $balance = $parm['clientBalance'];
        #puls 
        $puls = 60;
        
        
        #get currency id
       
        $currency_id = $this->getOutputCurrency($parm['tariff']);
        
        
        #call limit 
        $call_limit = 2;
        #payment type (cash,memo,bank).
//        if($parm['payType'] == "Other"){
//        $paymentType = $this->db->real_escape_string($parm['clientotherType']);     
//        }else
//        $paymentType = $parm['payType'];
        #description
        $description = '';
        
        
        
        
        #get last chain id from user balance table  
        $lastchainId = $this->getlastChainId($resellerId);
        
               
        #new chain id (incremented id of lastchain id )
        $chainId = $this->newChainId($lastchainId);
      
        
        #insert login detail into login table database 
        $balanceTable = '91_userBalance';
        $data = array("userId" => (int) $userId,"chainId"=>$chainId, "tariffId" => (int) $parm['tariff'], "balance" => 0, "currencyId" => (int) $currency_id, "callLimit" => (int) $call_limit, "resellerId" => (int) $resellerId,"routeId"=>$userRoutDetail['routeId'],"isDialPlan"=>$userRoutDetail['isDialPlan'],"getMinuteVoice"=>1);

        #insert query (insert data into 91_userLogin table )
        $this->db->insert($balanceTable, $data);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        if (!$result) 
        {
             ////log error
             trigger_error('problem while insert user balance info ,query:'.$qur);
            $this->deleteData($personalTable, "userId = ".$userId);
            $this->deleteData($loginTable, "userId = ".$userId);
            return json_encode(array("status" => "error", "msg" => "add user process fail!"));
        }

        #enable user sip id
        $sipMsg = $this->enableSip($userId,1);
        $resultData = json_decode($sipMsg, TRUE);
        if($resultData['status'] != "success"){
            trigger_error('user sip not enable in add new client ');
        }

        #variable country code and phone no use for store contact no into 91_tempcontact table
        $country_code = $parm['contactNo_code'];
        $phone = $parm['contactNumber'];
        #contact no. store into tempcomtact table
        include_once("contact_class.php");
        $contact_obj = new contact_class();
        $msg = $contact_obj->update_newcontact($country_code, $phone, $userId);



        #email id store into tempemail table and send varification code into email 
        
        $msg = $contact_obj->addnew_emailid($parm['email'], $userId);




         #add taransaction detail into taransation log table 
//        include_once("transaction_class.php");
//        $transactionObj = new transaction_class();
        
        /* CALL ADD TRANSACTIONAL FUNCTION FOR ADD TRANSACTION  : 
         * 
         * $resellerId : FromUser
         * $userid : toUser
         * $balance : amount for credit or debit
         * $balance : talktime amount 
         * $paymentType : cash,memo,bank
         * $description : description of transaction 
         * type : prepaid ,postpaid , partial
         * 
         */
        
//        $transactionObj->fromUser = $resellerId;
//        $transactionObj->toUser = $userId;
        
        #update current balance of user in userbalance table 
//        $transactionObj->updateUserBalance($userId,$balance,'+');
//        
//        $msg = $transactionObj->addTransactional($balance,$balance, $paymentType, $description, "prepaid"); //$fromUser,$toUser,$amount,$paymentType,$description,$type
        
        $resellerClient = $this->getUserDetails($userId,$resellerId); 
       
        return json_encode(array('status' => 'success', 'msg' => 'successsully client added',"resellerClient"=>$resellerClient));//,"resellerClient"=>$resellerClient
    }
    
    #function use to get single user detail 
    function getUserDetails($userId,$resellerId){
        
            $row = $this->loadUserDetails($userId,'*',$resellerId); 
        
            $id = $row['userId'];
            $blockUnblockStatus  = $row['isBlocked'];
            $deleteFlag = $row['deleteFlag'];

            $resellerDetail = $this->getUserBalanceInfo($resellerId);
            $resellerTariff = $resellerDetail['tariffId'];
            
            #find verified number of user 
            $contact_no = '';
            $temptable = '91_verifiedNumbers';
	    $this->db->select('countryCode,verifiedNumber')->from($temptable)->where("userId = '" . $id . "' and isDefault = 1");
            $qur = $this->db->getQuery();
	    $resultSel = $this->db->execute();
            
            //log error
            if(!$resultSel)
                trigger_error('problem while get verify numbers ,query:'.$qur);
            
	    if ($resultSel->num_rows > 0)
            {
                $norow= $resultSel->fetch_array(MYSQL_ASSOC); 
                $countryCode = $norow['countryCode'];
                $Number = $norow['verifiedNumber'];
                $contact_no = $countryCode."-".$Number;
            
            }

            #get user detail 
            $uname = $row['userName'];
            $name = $row['name'];
            $client_type = $row['type'];
            $id_currency = $row['currencyId'];
            
            if($resellerTariff == $row['tariffId']){
              $planName = "My Plan";
            }else            
            $planName = $row['planName'];
            $balance = $row['balance'];
          
            #assign all detail into data array 
            $data["id"] = $id;
            $data["name"] = $name;
            $data["uname"] = $uname;
            $data["planName"] = $planName;
            $data["contact_no"] = $contact_no;
            $data["blockUnblockStatus"] = $blockUnblockStatus;
            $data["balance"] = $balance;
            $data["deleteFlag"]=$deleteFlag;
          
            #get user manager name and id  (call function of function_layer)
            extract($this->getadminId($id));
            $data["managerId"] = $managerId;
            $data["managerName"] = $managerName;
            
            
            #get currency name according to currency id 
            $currency = $this->getCurrencyViaApc($id_currency,1);

            #add currency name into data array 
            $data["id_currency_name"] = $currency; 
            if ($client_type == 3)
                $data["client_type"] = "user";
            else if ($client_type == 2)
                $data["client_type"] = "reseller";
            else if ($client_type == 1)
                $data["client_type"] = "Admin";

           
        
        return $data;
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 04/09/2013
    #function use to find bulk client batch 
    function bulkUserBatch($resellerId,$q = null,$pageNo = 1,$allbatch = 0){
        

        # get only 2 batch detail who created by current user  (login user batch detail)
        $table = '91_bulkUser';

         $limit = 10;

        $skip = $limit*($pageNo-1);

        //code to search batch and batch user by user name
        if(is_numeric($q))
        {
            $manageClient = '91_manageClient';
            
            if($allbatch == 1){
            $condition = "userName LIKE '%$q%' and type=4";
             }else
            $condition = "userName LIKE '%$q%' and type=4 and resellerId=".$resellerId;

            $result = $this->selectData('DISTINCT userBatchId',$manageClient,$condition);

            if(!$result)
            {
              trigger_error('problem while get batch ids:query:'.$this->querry);
              return json_encode(array("msg"=>"problem while search,please reload and try again!!","status"=>"error"));     
            }   
           
            $batchIdArr = array();
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
              
              $batchIdArr[] = $row['userBatchId'];
              unset($row);
            }

           
            if(empty($batchIdArr))
            {
              return json_encode(array("msg"=>"No Batch found for this user!!","status"=>"error"));     
            }
            else
            {
                $batchCondition = '1 and batchId IN('.implode(',',$batchIdArr).')';

                $this->db->select('SQL_CALC_FOUND_ROWS *')->from($table)->where($batchCondition)->limit($limit)->offset($skip);
                $qur = $this->db->getQuery();
                $result = $this->db->execute();

                $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
                $countRes = mysqli_fetch_assoc($resultCount);

                if(!$result)
                    trigger_error('problem while get details for bulk user ,query:'.$qur);
                
                //get batch data in desired format
                $userBatchData['detail'] = $this->generateBatchDetailArray($result);
                
                $userBatchData['pages'] = ceil($countRes['totalRows']/$limit);
                $userBatchData['totalBatches'] = $countRes['totalRows'];

                if(count($batchIdArr) > 1)
                  return json_encode(array('batchDetail' => $userBatchData));
                else
                {
                     
                  $param['searchData'] = $q;
                  $param['batchId']  = $batchIdArr[0];

                  $batchClientJson = $this->searchBulkClient($param);
                  $batchClientArr = json_decode($batchClientJson,TRUE);
                 
                  return json_encode(array('batchDetail' => $userBatchData,'batchClientDetail' => $batchClientArr));
                } 


            }


        }
        else if($q != null){
        if(preg_match('/[^a-zA-Z0-9\@\_\-\$\.]+/', $q))
        {
            return json_encode(array("msg"=>"Error Invalid data please provide a valid data","status"=>"error"));
        }
        $q = $this->db->real_escape_string($q);
        }

       

//        # table name for find resller plan name 
//        $table = '91_manageClient';
//        $condition = "userId = '".$resellerId."'";
//        $this->db->select('*')->from($table)->where($condition);
//        $qur = $this->db->getQuery();
//        $result = $this->db->execute();
//        
//        if(!$result)
//            trigger_error('problem while get details for manage client ,query:'.$qur);
//        
//         if ($result->num_rows > 0) {
//             $row= $result->fetch_array(MYSQLI_ASSOC);
//             $planName = $row['planName'];
//        }
        
        
        
        if($q != null){
          $subCondition =" and lower(batchName) like '".$q."%'";
        }else
          $subCondition = '';  
        
        
        
        if($allbatch == 1){
            $condition = "1 ".$subCondition."order by batchId desc";
        }else
            $condition = "userId = '".$resellerId."'".$subCondition."order by batchId desc";
        
        $this->db->select('SQL_CALC_FOUND_ROWS *')->from($table)->where($condition)->limit($limit)->offset($skip);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();

        $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
        $countRes = mysqli_fetch_assoc($resultCount);

        
        if(!$result)
            trigger_error('problem while get details for bulk user ,query:'.$qur);
        
        //get batch data in desired format
        $userBatchData['detail'] = $this->generateBatchDetailArray($result);
        
         $userBatchData['pages'] = ceil($countRes['totalRows']/$limit);
         $userBatchData['totalBatches'] = $countRes['totalRows'];


         $batchDetail['batchDetail'] = $userBatchData;

      return json_encode($batchDetail);       
        
    }


    /**
    *@author Ankit Patidar <ankitpatidar@hostnsoft.com>
    *@since 30/04/2014
    *@filesource
    *@param object
    *@uses function to get batch data array from mysql object to array
    *@return Array
    */
    function generateBatchDetailArray($result)
    {
       if ($result->num_rows > 0) {
          while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
        
              
              #batch id 
              $data['batchId'] = $row['batchId'];
              #batch Name 
              $data['batchName'] = $row['batchName'];
              #no. of client in batch 
              $data['numberOfClients'] = $row['numberOfClients'];
              #batch expiry date 
              $data['expiryDate'] = date('Y-m-d',strtotime($row['expiryDate']));
              #reslerId
              $data['resellerId'] = $row['userId'];
              #batch created date 
              $data['createDate'] = $row['createDate'];
              #plan name 
             // $data['planName'] = $planName;
              #balance per user of batch 
              $data['batchBalance'] = $row['batchBalance'];
              
                     
              
              #get batch detail 
              extract($this->batchBlockUnblockStatus($row['batchId'])); // $blockUnblockStatus,$tariffId,$currencyId,$tariffName            
              
              $data['blockStatus'] = $blockUnblockStatus;
              $data['currencyName'] = $this->getCurrencyViaApc($currencyId);
              $data['tariffName'] = $tariffName;
              $data['deleteStatus'] = $deleteStatus;
              
              
              $userBatchData[] = $data;
              unset($data);
            }
             
         }else $userBatchData = array();

         return $userBatchData;

    }



    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 16-09-2013
    #function use to block  user batch (all client of given batch id) OR set Batch Delete flage
    # parm : type use either isBlock or deleteFlag, both are column name of userLogin table 
    function BatchBlockOrUnblock($parm,$resellerId,$type){

        
        if (!preg_match("/^[0-9]+$/", $parm['batchId'])) {
            return json_encode(array("status" => "error", "msg" => "Please select valid batch."));
        } 
        
        if (!preg_match("/^[0-9]+$/", $resellerId)) {
            return json_encode(array("status" => "error", "msg" => "Reseller id is not valid."));
        } 
        
        
        #check permission for change batch block unblock status 
        $table = '91_bulkUser';
        $condition = "batchId = '".$parm['batchId']."'";
        $this->db->select('*')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log error
        if(!$result)
            trigger_error('problem while get bulk user detail ,query:'.$qur);
        
         if ($result->num_rows > 0) {
         $row = $result->fetch_array(MYSQL_ASSOC);	    
         $batchCreator = $row['userId'];
         }
         
         if($batchCreator != $resellerId){
             return json_encode(array("status" => "error", "msg" => "you have no permession for update status."));
         }
        
        $conditionStr = "userId IN (";
        
        #find all child user id of given user
        $table = '91_userBalance';
        $condition = "userBatchId= '" . $parm['batchId'] . "'";
        $this->db->select('userId')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();  
        
        //log error
        if(!$result)
            trigger_error('problem while get user balance ,query:'.$qur);
        
        if ($result->num_rows > 0) {
          while ($data= $result->fetch_array(MYSQL_ASSOC) ) {
            $conditionStr .= $data['userId'].",";
          }
        }
        $conditionStr = substr($conditionStr,0,-1);
        $conditionStr .=")";
        
        
        #check status is block or unblock
        if($parm['status'] == "block"){
        $sql = "UPDATE 91_userLogin SET ".$type."=".$type." + 1 WHERE ".$conditionStr; 
        }else
        $sql = "UPDATE 91_userLogin SET ".$type."=".$type." - 1 WHERE ".$conditionStr; 
        
        
        $result = $this->db->query($sql);
        
        
        if($result)
        {
            if($type == "deleteFlag")
            {
                return json_encode(array("status" => "success", "msg" => "User Batch Delete flag set successfully ."));
            }
            else
             return json_encode(array("status" => "success", "msg" => "User Batch status update successfully ."));
        }
        else 
        {
            //log errors
           trigger_error('problem while block user ,query:'.$sql); 
           return json_encode(array("status" => "", "msg" => "user Batch status not update.".$sql));
        }
 
        
    }
    
    
    # created by sudhir pandey (sudhir@hostnsoft.com)
    # creation date 16-09-2013
    # function use to check batch status is block or unblock 
    function batchBlockUnblockStatus($batchId){
        
        #find userid for check user status is block or unblock   
        $table = '91_userBalance';
        $condition = "userBatchId = '".$batchId."' LIMIT 1";
        $this->db->select('userId,tariffId,currencyId,resellerId')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log errors
        if(!$result)
            trigger_error('problem while get user balance ,query:'.$qur);
        
        if ($result->num_rows > 0) {
             $data = $result->fetch_array(MYSQL_ASSOC);
              $userId = $data['userId'];
              $tariffId = $data['tariffId'];
              $currencyId = $data['currencyId'];
              $resellerId = $data['resellerId'];
              
         }
         
        #get reseller tariff id 
        $resellerDetail = $this->getUserBalanceInfo($resellerId);
         
        if($resellerDetail['tariffId'] == $tariffId){
             $tariffName = "My Plan"; 
        }else
        {          
            $table = '91_plan';
            $condition = "tariffId = ".$tariffId."";
            $this->db->select('planName')->from($table)->where($condition);
            $qur = $this->db->getQuery();
            $result = $this->db->execute(); 

            //log errors
            if(!$result)
                trigger_error('problem while get plan detail ,query:'.$qur);

            if ($result->num_rows > 0) {
                $data = $result->fetch_array(MYSQL_ASSOC);
                $tariffName = $data['planName'];
            }
        }
         # get user block status ..
         $table = '91_userLogin';
         $condition = "userId = '".$userId."'";
         $this->db->select('isBlocked,deleteFlag')->from($table)->where($condition);
         $qur = $this->db->getQuery();
         $loginresult = $this->db->execute();
         
         //log errors
         if(!$loginresult)
            trigger_error('problem while get user login detail ,query:'.$qur);
         
         
         if ($loginresult->num_rows > 0) {
         $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
         $blockUnblockStatus = $logindata['isBlocked'];
         $deleteStatus = $logindata['deleteFlag'];
          }
          
          return array("blockUnblockStatus"=>$blockUnblockStatus,"tariffId"=>$tariffId,"currencyId"=>$currencyId,"tariffName"=>$tariffName,"deleteStatus"=>$deleteStatus);
                
                
    }    
   
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 05/09/2013
    #function use to search bulk user username and password 
    function searchBulkClient($parm){
        
        
        
        $bulkUserData = array();
        if (!preg_match("/^[0-9]+$/", $parm['batchId'])) {
            return jsone_encode($bulkUserData);
        }  
        
        if(!preg_match('/^[a-zA-Z0-9\@\_\-\s]+$/', $parm['searchData'])){
           return json_encode($bulkUserData);
        }
        
        if(isset($parm['pageNo']) && is_numeric($parm['pageNo']))
            $pageNo = $parm['pageNo'];
        else 
            $pageNo = 1;
            
        $limit = 15;
        $skip = $limit*($pageNo-1);    
        
        #get batch user detail from 91_manageClient table 
        $table = '91_manageClient';
        $condition = "userBatchId = '".$parm['batchId']."' and userName like '%".$parm['searchData']."%'";
        $this->db->select('SQL_CALC_FOUND_ROWS *')->from($table)->where($condition)->limit($limit)->offset($skip);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        
        $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
        if(!$resultCount)
        {
            trigger_error('problem while get result,query:');
            $countRes['totalRows']=0;
         }
         else
            $countRes = mysqli_fetch_assoc($resultCount);
        
         
         $bulkUserData['pages'] = ceil($countRes['totalRows']/$limit);
        //log errors
        if(!$result)
            trigger_error('problem while get manage client detail ,query:'.$qur);
        

          $bulkUserData['detail'] = $this->getBatchClientsInArry($result);
  

        return json_encode($bulkUserData);       
    }
    
    /**
    *@author ANkit patidar <ankitpatidar@hostnsoft.com>
    *@since 30/04/2014
    *@param object mysql resource
    *@uses function to get batch client array from object
    *@return array
    */
    function getBatchClientsInArry($result)
    {

       if ($result->num_rows > 0) 
       {
           while ($row= $result->fetch_array(MYSQLI_ASSOC) ) 
           {
              $userdata['userId'] = $row['userId'];
              $userdata['balance']= round($row['balance'],3);
              $userdata['status']= $row['status'];
              $userdata['userName'] = $row['userName'];
              $userdata['password'] = $row['password'];  
              $userdata['deleteFlag'] = $row['deleteFlag'];
             $userdata['blockUnblockStatus'] = $row['isBlocked'];
               
              $bulkUserData[] = $userdata;
              unset($row);
              unset($userdata);
          }
         
       }else
              $bulkUserData = array();
          
          
        
        return $bulkUserData;       

    }


    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 16-09-2013
    #function use to change user status (block or unblock) with all chain user  OR set Delete Flag of user 
    # parm : type use to either isBlock or deleteFlag both are column name of userLogin table 
    function changeUserStatus($parm,$sessionId,$type){
        
           
        
        #check user permission for update status  
        
        $checkId = $this->getResellerId($parm['userId']); 
        if($checkId != $sessionId)
        {
            return json_encode(array("status" => "error", "msg" => "you don't have  permission to update status.","statusNumber" => "0","errorCode" => "205"));
        }
        
        #get reseller All user list in condition 
        $conditionStr = $this->getResellerAllUser($parm['userId']);
                       
        #update user status (Block or unblock) if block then isblock column incremented by 1 otherwise decremented by 1 
        
        #check status is block or unblock
        if($parm['status'] == "block")
        {
            
            $sql = "UPDATE 91_userLogin SET ".$type."=".$type." + 1 WHERE ".$conditionStr; 
            $this->accountManagerLog($parm['userId'],3,"unBlock","block",$sessionId,"update user Status");
     
        }
        else
        {
            
            $sql = "UPDATE 91_userLogin SET ".$type."=".$type." - 1 WHERE ".$conditionStr; 
            $this->accountManagerLog($parm['userId'],3,"block","unBlock",$sessionId,"update user Status");
     
        }
        
        $result = $this->db->query($sql);
        
        
        if($result)
        {
            if($type == "deleteFlag")
            {
                #if delete flage set then user blocked status also change 
                if($parm['status'] == "block")
                {	 
                    $sql = "UPDATE 91_userLogin SET isBlocked = isBlocked + 1 WHERE ".$conditionStr; 
                    $this->accountManagerLog($parm['userId'],1,"exist","deleted",$sessionId,"user Deleted");
     
                }
                else
                {
                    $sql = "UPDATE 91_userLogin SET isBlocked = isBlocked - 1 WHERE ".$conditionStr; 
                    $this->accountManagerLog($parm['userId'],1,"deleted","exist",$sessionId,"restore user");
     
                    
                }
                    
                     $result = $this->db->query($sql);
                     
                      //log errors
                    if(!$result)
                        trigger_error('problem while change delete status status ,query:'.$sql);
                    
                return json_encode(array("status" => "success", "msg" => "User Delete Flag Set successfully .","statusNumber" => "1"));
            }
            else
             return json_encode(array("status" => "success", "msg" => "User status update successfully .","statusNumber" => "1"));
        }
        else 
        {
            //log errors
            trigger_error('problem while change user status ,query:'.$sql); 
            return json_encode(array("status" => "error", "msg" => "user status not update.","statusNumber" => "0","errorCode" => "206"));
        }
        
    
        
    }
    
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 14-01-2014
    #function return condition for change all user status where reseller is same 
    function getResellerAllUser($userId){
        
        #find chain id of given user 
        $userChainId = $this->getUserChainId($userId);
        if($userChainId == false || $userChainId == "" || $userChainId == NULL)
            return 0;
        
        
        #condition for all user id where status are eiter block or unblock
        $conditionStr = "userId IN (".$userId;
        
        #find all child user id of given user
        $table = '91_userBalance';
        $condition = "chainId like '" . $userChainId . "%' ";
        $this->db->select('userId')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();  
        
        //log errors
        if(!$result)
            trigger_error('problem while get user balance detail ,query:'.$qur);
        
        if ($result->num_rows > 0) 
        {
            while($data= $result->fetch_array(MYSQL_ASSOC) ) 
            {
                $conditionStr .= ",".$data['userId'];
            }
        }
        
        $conditionStr .=")";
        
        return $conditionStr;
        
    }
    
    function getRemainingMinutesStatus($userVar,$from,$to)
    {
        if($from =='userId' && (preg_match(NOTNUM_REGX,$userVar) || $userVar == "" || is_null($userVar)))
              return 0;
      else if($from =='chainId' && ($userVar == "" || is_null($userVar)))
              return 0;
        
        $userVar = $this->db->real_escape_string($userVar);
        $condition = $from."= '" . $userVar . "' ";

        #find userId of given name 
        $manageClient = "91_userBalance";
        $userinfo = $this->selectData($to, $manageClient,$condition);
        
        if ($userinfo->num_rows > 0) 
        {
            $user = $userinfo->fetch_array(MYSQLI_ASSOC);
        
            return $user[$to];      
        } 
        return 0;
        
        
    }
    
    
    
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 14-01-2014
    #function use to set status of listen remaining minutes during the call
    function listenRemainMinutes($userId,$resellerId,$currStatus = 0){
        
        if(!is_numeric($userId) || !is_numeric($resellerId))
        {
            return json_encode(array("status" => "error", "msg" => "you don't have  permission to update status. Invalid user Account"));
        } 
        
       #condition for change all user status where reseller is same 
       $conditionStr = $this->getResellerAllUser($userId);
       
       if($conditionStr == '0'){
            return json_encode(array("status" => "error", "msg" => "you don't have  permission to update status !"));
      }
       
       $table = '91_userBalance';
       
       
       if($currStatus == 0)
        $data = array("getMinuteVoice"=>1);
       else
          $data = array("getMinuteVoice"=>0);
       
       $this->db->update($table, $data)->where($conditionStr);
      
       
       $results = $this->db->execute();
       if(!$results){
             return json_encode(array("status" => "error", "msg" => "user Listen Remaining minutes are not updated !"));
       }else
             return json_encode(array("status" => "success", "msg" => "successfully Listen Remaining minutes status are update."));
  
        
    }
    
    /**
     * @author Ankit Patidar <Ankitpatidar@hostnsoft.com>
     * @since 29-04-2014
     * @function use to set status of listen remaining minutes for batch 
     */
    
    function UpdateListenRemainStatusForBatch($userId='',$batchId='',$currStatus = 0)
    {
        
        if(!is_numeric($userId) || !is_numeric($batchId) || preg_match(NOTNUM_REGX,$userId) || preg_match(NOTNUM_REGX,$batchId))
        {
            return json_encode(array("status" => "error", "msg" => "you don't have  permission to update status. Invalid user Account"));
        } 
        
       #condition for change all user status where reseller is same 
       $batchCreator = $this->getbatchCreatorId($batchId);

        if($batchCreator != $userId)
            return json_encode(array("status" => "error", "msg" => "you don't have  permission to update status. Invalid user Account!!!"));          

       $table = '91_userBalance';
       $conditionStr = 'userBatchId='.$batchId;
       
       if($currStatus == 0)
            $data = array("getMinuteVoice"=> (int)1);
       else
            $data = array("getMinuteVoice"=>(int) 0);
       
       $this->db->update($table, $data)->where($conditionStr);
      
       $qur = $this->db->getQuery();
       $results = $this->db->execute();
       if(!$results || $this->db->affected_rows ==0)
       {
            trigger_error('problem while batch listen time update,query:'.$qur);
            return json_encode(array("status" => "error", "msg" => "user Listen Remaining minutes are not updated !"));
       }

       $table = '91_bulkUser';
       $condition = 'batchId='.$batchId;

       if($currStatus == 0)
        $data = array("listenTime"=>1);
       else
          $data = array("listenTime"=>0);
       
       $this->db->update($table, $data)->where($condition);
      
       
       $resultRes = $this->db->execute();
        if(!$resultRes || $this->db->affected_rows ==0)
        {
            trigger_error('problem while batch listen time update'.$this->db->getQuery());
             return json_encode(array("status" => "error", "msg" => "user Listen Remaining minutes are not updated !"));
        }


        return json_encode(array("status" => "success", "msg" => "successfully Listen Remaining minutes status are update."));
        
    }

    #created by Ankit Patidar <Ankitpatidar@hostnsoft.com>
    #creation date 29-04-2014
    #function use to get status of listen remaining minutes for batch
    function getListenRemainingTimeStatusForBatch($userId='',$batchId='')
    {
        if(!is_numeric($userId) || !is_numeric($batchId) || preg_match(NOTNUM_REGX,$userId) || preg_match(NOTNUM_REGX,$batchId))
        {
            return json_encode(array("status" => 0, "msg" => "You don't have  permission to update status. Invalid user Account"));
        } 
        
       #condition for change all user status where reseller is same 
       $batchCreator = $this->getbatchCreatorId($batchId);

        if($batchCreator != $userId)
            return json_encode(array("status" => 0, "msg" => "You don't have  permission to update status. Invalid user Account!!!")); 

       $table = '91_bulkUser';
       $condition = 'batchId='.$batchId;         

       $result = $this->selectData('listenTime', $table,$condition);

       if(!$result || $result->num_rows == 0)
       {
          trigger_error('problem while get listen status,query:'.$this->querry);
           return json_encode(array("status" => 0, "msg" => "Problem while getting status!!!")); 
       }

       $row = $result->fetch_array(MYSQLI_ASSOC);
        
       return json_encode(array("status" => 1, "msg" => "Listen time status successfully found!!!",'listenStatus' => $row['listenTime']));  


    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 03/09/2013
    #function use to search client by contact number 
    function searchByNumber($q){
        $useridArray = array();
        $table = '91_verifiedNumbers';
        $condition = "CONCAT(countryCode,verifiedNumber) like '%".$q."%'";
        
        $useridArray = $this->getUserIdByCondition($table,$condition);
        
        return $useridArray;
         
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 03/09/2013
    #function use to search client by email id  
    function searchByEmail($q){
        
        $table = '91_verifiedEmails';
        $condition = "email like '%".$q."%'";
        $useridArray = $this->getUserIdByCondition($table,$condition);
         
         return $useridArray;
         
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 03/09/2013
    #function use to get client id by condition and given table name   
    function getUserIdByCondition($table,$condition){
        
        $this->db->select('userId')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
         //log errors
        if(!$result)
            trigger_error('problem while get user id ,query:'.$qur);
        
        
        if ($result->num_rows > 0) {
         while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
             $useridArray[] = $row['userId'];
         }
        }else
            $useridArray = array();

        return $useridArray;
    }

    function checkParentReseller($request, $session) {
        /**
         * @author Rahul
         * @since 03 Aug 2013
         * @param array $request Contains ["id"]//Client Id
         * @param array $request Contains ["password"] new password to set
         * @param array $session Contains Reseller Id 
         */
        $table = '91_userBalance';
        $this->db->select('*')->from($table)->where("userId = '" . $request['clientId'] . "' and resellerId=" . $session['id']);
        $sql = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log errors
        if(!$result)
            trigger_error('problem while get user balance detail ,query:'.$sql);
        
        if ($result->num_rows > 0) {
            return true;
        } else {
            die("You Are Not Authorized To View This Page");
        }
    }

    function resetClientPassword($request, $session) 
    {
        /**
         * @author Rahul
         * @since 03 Aug 2013
         * @param array $request Contains ["id"]//Client Id
         * @param array $request Contains $request["newPass"] new password to set
         * @param array $session Contains Reseller Id 
         */
        
      
      if (!preg_match("/^[0-9]+$/", $request['clientId'])) {
            return json_encode(array("status" => "error", "msg" => "Please enter a valid client Id."));
        }  
      
      if(preg_match(NOTPASSWORD_REGX, $request["newPass"]))
        {
           return json_encode(array("status" => "error", "msg" => "please enter a valid password must not containg any spacial character other than '@_-!$()[]{}?' "));
        }
        
      #check permission for add transaction or not 
      $resellerId = $this->getResellerId($request['clientId']);  
      
      if($session['client_type'] != 1){
      if($resellerId != $session['id'])
      {
          return json_encode(array("status" => "error", "msg" => "you have no permission for reset password."));
      }
      }
        
        
        $newPass = $this->db->real_escape_string($request["newPass"]);
        $table = '91_userLogin';
        $this->db->select('password')->from($table)->where("userId = " . $request['clientId']."");
        $sql = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log errors
        if(!$result)
            trigger_error('problem while get user login detail ,query:'.$sql);
        
        
        if ($result->num_rows > 0) 
        {
             $res = $result->fetch_array(MYSQL_ASSOC);
             $oldPassword = $res['password'];
        } 

        
        $data = array("password" => $newPass);
        $condition = " userId=" . $request['clientId'] . " ";

        $this->db->update($table, $data)->where($condition);
        $sql = $this->db->getQuery();
        $result = $this->db->execute();
        
        #add password log 
       
        if($oldPassword != $newPass)
        {
            $this->accountManagerLog($request['clientId'],8,$oldPassword,$newPass,$session['id'],"change password");
        }
        
        #enable user sip id
        $sipMsg = $this->enableSip($request['clientId'],1);
        $resultData = json_decode($sipMsg, TRUE);
        if($resultData['status'] != "success"){
            trigger_error('user sip not enable in add new client ');
        }
        
        if ($result) 
        {
            $response["msg"] = "Update Sccessfully";
            $response["msg_type"] = "success";
        } 
        else 
        {
             //log errors
            if(!$result)
              trigger_error('problem while update password ,query:'.$sql);
//              $response[]="";	
            $response["msg"] = "Update";
            $response["msg_type"] = "error";
        }
        return json_encode($response);
    }
    
    
    function searchChiildList($userId, $q) 
    {
        //$userid
        $limit = 30;
        if (isset($page_number)) 
        {
            $start = ($page_number - 1) * $limit;
        }
        else
            $start = 0;
        if (strlen($q) < 1) 
        {
            $returnResult["value"] = "Empty Query";
            $returnResult["lable"] = "Empty Query";
            $response[] = $returnResult;
            return json_encode($response);
        }
        
        $table = "91_manageClient";
        $this->db->select('*')->from($table)->where("resellerId = '" . $userId . "' and userName like '" . $q . "%' ")->limit($limit)->offset($start);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
       
        //log errors
        if(!$result)
            trigger_error('problem while get details from manage client ,query:'.$qur);
        
        // processing the query result
        if ($result->num_rows > 0) 
        {
            while ($row = $result->fetch_array(MYSQL_ASSOC)) 
            {
                $returnResult["lable"] = $row["userId"];
                $returnResult["value"] = $row["userName"];
                $response[] = $returnResult;
            }
        } 
        else 
        {
            $response[] = "";
        }
        return json_encode($response);
    }

    function changeResellerSettings($request, $userid) 
    {

        $key = $request['key'];
        $value = $request['value'];
        
        if (($key == 'mobile' || $key == 'email') && ($value == 1 || $value == 0)) 
        {
            $table = '91_resellerSetting';
            $data = array($key => $value);
            $condition = " userId=" . $userid . " ";
            
             
            //code to find user in table
            $resultData = $this->selectData('*',$table,$condition);
            
            //check num rows
            if($resultData->num_rows < 1)
            {
                //prepare data to insert
                $dataArr = array(
                                 'userId' => $userid,
                                 'email' => 1,
                                 'mobile' => 0);
                
                $result = $this->insertData($dataArr,$table);
                
                if(!$result)
                    trigger_error('problem while insert reseller setting: '.json_encode($data));
                
            }
            
            $this->db->update($table, $data)->where($condition);
            $qur = $this->db->getQuery();
            $result = $this->db->execute();
            
            if ($result) 
            {
                $response["msg"] = "Update Successfully";
                $response["msg_type"] = "success";
            }
            else
            {
                //log error
                trigger_error('problem while update reseller setting ,query:'.$qur);
                $response["msg"] = "Error updating data please try again";
                $response["msg_type"] = "error";
            }
            
        }
        else {
//              $response[]="";	
            $response["msg"] = "Error invalid input provided please contact support";
            $response["msg_type"] = "error";
        }
        return json_encode($response);
    }

    

    #modify by sudhir pandey <sudhir@hostnsoft.com>\
    #modification date 11/10/2013
    #function use to get client detail (search by $q {emailid ,contactno} OR show all client detail)
    function manageClients($request, $session, $allclient = null) 
    {
         
        $q = $request['q'];
        if(preg_match('/[^a-zA-Z0-9\@\_\-\$\.\s]+/', $q))
        {
            return json_encode(array("msg"=>"Error Invalid data please provide a valid data","status"=>"error"));
        }
        #user id 
        $userid = $session["userid"];
//        extract($request);
       

        #array for store all record detail 
        $jade["isSearchResult"] = "false";
        $jade["searchQuery"] = "";

        $limit = 10;

        if(isset($request['pageNo']) and is_numeric($request['pageNo']))
          $page_number = $request['pageNo'];
        else
          $page_number = 1;


        $first_limit = $limit*($page_number - 1);
        
        
        
        # search by email and contact no if $q value is given from request 
        if (isset($q) and trim($q) != '') 
        {
            $q = strtolower($q);
            
            if(is_numeric($q))            
            {
               $useridArray = $this->searchByNumber($q);
               $condition  = implode(",", $useridArray);  
               $newcondition = " and userId IN (".$condition.")";
            }
            elseif(strpos($q, '@'))
            {
                $useridArray = $this->searchByEmail($q);
                $condition  = implode(",", $useridArray);  
                $newcondition = " and userId IN (".$condition.")";
            }
            else
            {
                $newcondition ="and lower(userName) like '".$q."%'";
            }
            $q = $this->db->real_escape_string($q);
            #check for admin or client
            if($allclient != null)
            {
//              $allUserCondition ="and lower(userName) like '%".$q."%'";
                
              $result =  $this->allUserDetail($newcondition,$first_limit,$limit,1); 
            }
            else
              $result = $this->loadUsers($q, $userid, $first_limit, $limit, 0,$newcondition);

            $jade["isSearchResult"] = "true";
            $jade["searchQuery"] = $q;
            

             #check result is empty or not 
            if(count($result['detail']) <= 0)
            {
                
               $newcondition ="and lower(name) like '".$q."%'";
               if($allclient != null)
               {
                    $result =  $this->allUserDetail($newcondition,$first_limit,$limit,1); 
               }
               else
                    $result = $this->loadUsers($q, $userid, $first_limit, $limit,0,$newcondition);
             }

//            #check result is empty or not 
//            if(count($result) <= 0)
//            {
//                #check for $q is number or email id  
//                if(preg_match('/^[0-9]+$/',$q))
//                {
//                   # search by contact number  
//                   $useridArray = $this->searchByNumber($q); 
//                }
//                else
//                {
//                   # search by email id 
//                    $useridArray = $this->searchByEmail($q); 
//                }
//
//               $condition  = implode(",", $useridArray);  
//               $newcondition = " and userId IN (".$condition.")";
//               
//               if($allclient != null)
//               {
//                    $result =  $this->allUserDetail($newcondition); 
//               }
//               else
//                    $result = $this->loadUsers($q, $userid, $first_limit, $limit, 3,$newcondition);
//                        
//                    
//            }
            
        }
        else 
        {
            #condition to fetch all client detail 
            if($allclient != null)
            {
                $result =  $this->allUserDetail('',$first_limit,$limit); 
                if(isset($q))
                {
                    $jade["isSearchResult"] = "true";
                }
            }
            else
            {
                if(isset($q))
                {
                    $jade["isSearchResult"] = "true";
                }
                $result = $this->loadUsers(0, $userid, $first_limit, $limit, 3);
            }
        }
        
        #get reseller tariff id 
        $resellerDetail = $this->getUserBalanceInfo($userid);
        
        
        #get all data of client 
        foreach ($result['detail'] as $row) 
        {
            
            $id = $row['userId'];
            
//            #find user status block or unblock
//            $table = '91_userLogin';
//            $condition = "userId = '".$row['userId']."'";
//            $this->db->select('*')->from($table)->where($condition);
//            $loginresult = $this->db->execute();
//            if ($loginresult->num_rows > 0) {
//            $logindata = $loginresult->fetch_array(MYSQL_ASSOC);

            $blockUnblockStatus  = $row['isBlocked'];
            $deleteFlag = $row['deleteFlag'];

            #find verified number of user 
            $contact_no = '';
            $temptable = '91_verifiedNumbers';
	    $this->db->select('countryCode,verifiedNumber')->from($temptable)->where("userId = '" . $id . "' and isDefault = 1");
            $qur = $this->db->getQuery();
	    $resultSel = $this->db->execute();
            
            //log error
            if(!$resultSel)
                trigger_error('problem while get verify numbers ,query:'.$qur);
            
	    if ($resultSel->num_rows > 0)
            {
                $norow= $resultSel->fetch_array(MYSQL_ASSOC); 
                $countryCode = $norow['countryCode'];
                $Number = $norow['verifiedNumber'];
                $contact_no = $countryCode."-".$Number;
            
            }

            #get user detail 
            $uname = $row['userName'];
            $name = $row['name'];
            $client_type = $row['type'];
            $id_currency = $row['currencyId'];
            
            if($resellerDetail['tariffId'] == $row['tariffId']){
             $planName = "My Plan"; 
            }else            
             $planName = $row['planName'];
            
            $balance = $row['balance'];
          
            #assign all detail into data array 
            $data["id"] = $id;
            $data["name"] = $name;
            $data["uname"] = $uname;
            $data["planName"] = $planName;
            $data["contact_no"] = $contact_no;
            $data["blockUnblockStatus"] = $blockUnblockStatus;
            $data["balance"] = $balance;
            $data["deleteFlag"]=$deleteFlag;
          
            #get user manager name and id  (call function of function_layer)
            $managerId =  $this->getadminId($id);
            $data["managerId"] = $managerId;
            if($managerId != 0){
            $acmDetail = $this->getAcmName($managerId);
            $data["managerName"] = $acmDetail['userName'];
            }else
            $data["managerName"] = "";
            
            
            #get currency name according to currency id 
            $currency = $this->getCurrencyViaApc($id_currency,1);
//            if ($id_currency == 147) 
//            {
//                $currency = "USD";
//            }
//            else if ($id_currency == 63) 
//            {
//                $currency = "INR";
//            } 
//            else if ($id_currency == 1) 
//            {
//                $currency = "AED";
//            }

            #add currency name into data array 
            $data["id_currency_name"] = $currency; 
            if ($client_type == 3)
                $data["client_type"] = "user";
            else if ($client_type == 2)
                $data["client_type"] = "reseller";
            else if ($client_type == 1)
                $data["client_type"] = "Admin";

           
            $jade["client"][] = $data;
        }
        
        $jade['pages'] = ceil($result['totalCount']/$limit);
        
     return json_encode($jade);
    }
    
    
    # sub function of manageClients use to get all user detail 
    function allUserDetail($condition = null, $start_limit=0,$limit=10,$sort=0)
    {
    
        if($sort==1)
            $order="lower(userName)";
        else
            $order="userId desc";
        
         if($condition != null)
         {
            $allcondition = $condition;
         }
         else
            $allcondition = '';
        
        $table = '91_manageClient';
        $this->db->select('SQL_CALC_FOUND_ROWS *')->from($table)->where('type != 4 and type !=7 and type !=1 '.$allcondition.' order by '.$order.' limit '.$start_limit.','.$limit);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
       
        //log error
        if(!$result)
            trigger_error('problem while get manageClient details,query:'.$qur);
        
         $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
         if(!$resultCount){
            trigger_error('problem while get result,query:');
            $countRes['totalRows']=0;
         }else
         $countRes = mysqli_fetch_assoc($resultCount);
        
         
        $resultArray = array();
        if ($result->num_rows > 0) 
        {

            while ($row= $result->fetch_array(MYSQL_ASSOC) ) 
            {
                $resultArray['detail'][]=$row;					
            }
        }
        
        if(isset($countRes['totalRows']) && $countRes['totalRows'] !=''){
        $resultArray['totalCount'] = $countRes['totalRows'];
        }else
         $resultArray['totalCount'] =0;   
        
        return $resultArray;
       
    }
    
    function loadUsers($q,$user_id,$start_limit,$limit,$sort,$checkCondition = null)
    {
            
            $condition='';
            $qryPart = '';
                if($sort==0)
			$order="lower(userName)";
		else if($sort==1)
			$order="balance";
		else if($sort==2)
			$order="balance desc";	
		else if($sort==3)
			$order="  userId  desc ";
                
                if($checkCondition == null)
                {
                    if(isset($q) &&  strlen($q)>1)
                        $condition=" and lower(userName) like '".$q."%' ";
                        $qryPart.='SQL_CALC_FOUND_ROWS';
                   
                }
                else
                {
                    $condition = $checkCondition;
                }
                
                
		$sql="Select $qryPart * from 91_manageClient where resellerId='".$user_id."' and type != 4 and type !=7 and type!= 1 $condition order by ".$order." limit ".$start_limit.",".$limit;
		
                $result = $this->db->query($sql);
               
               
                
                $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
                $countRes = mysqli_fetch_assoc($resultCount);
                    
                
                 //log error
                if(!$result)
                    trigger_error('problem while get manageClient details,query:'.$sql);
        
                
                $resultArray = array();
                if ($result->num_rows > 0) 
                {
				
                    while ($row= $result->fetch_array(MYSQLI_ASSOC) ) 
                    {
                        $resultArray['detail'][]=$row;					
                    }
                }
                
                $resultArray['totalCount'] = $countRes['totalRows'];
               
                return $resultArray;
	}
    
    function loadUserDetails($user_id, $fields = '*', $resellerId) 
    {

        $user_id =$this->db->real_escape_string($user_id);    
        $resellerId =  $this->db->real_escape_string($resellerId);    
        $table = '91_manageClient';
        $condition = "userId='" . $user_id . "' and resellerId=" . $resellerId;
        $this->db->select($fields)->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
       
        if ($result->num_rows > 0) 
        {
          $row = $result->fetch_array(MYSQL_ASSOC);
        }
        
        if (!$result)
                trigger_error('problem while get manageClient details,query:');

        
        return $row;
        
    }

   
    
   /**
    * @author  sudhir pandey <sudhir@hostnsoft.com>
    * @since 26/10/2013
    * @description function use to edit general setting of user like account manager and password 
    * @param  $parm => accountManager and password 
    */ 
   function editGeneralSetting($param,$userId,$type)
   {
      
      
     
      #check permission for add transaction or not 
      $resellerId = $this->getResellerId($param['clientId']);  
      
      if($type != 1){
      if($resellerId != $userId)
      {
          return json_encode(array("status" => "error", "msg" => "you have no permission for edit general setting ."));
      }
      }
 
      
       
      
        $managerTable = "91_accountManager";
        $this->db->select('*')->from($managerTable)->where("userId = '" . $param['clientId'] . "'");
        $sql = $this->db->getQuery();
        
        $result = $this->db->execute();
        
        if(!$result)
            trigger_error('problem while get account manager details,query:'.$sql);
        
        if ($result->num_rows > 0) 
        {
            
            $data=array("managerId"=>$param['accountManager']); 
            $condition = "userId=".$param['clientId'] ." ";
            $this->db->update($managerTable, $data)->where($condition);	
            $sql = $this->db->getQuery();
            $results = $this->db->execute();
            
            
        }
        else
        {
            
            $data = array("userId" => $param['clientId'],"managerId" => $param['accountManager']);
            $this->db->insert($managerTable, $data);
            $sql = $this->db->getQuery();
            $results = $this->db->execute(); 
            
        }
            
        
       
        #update entry in adminLog table 
        $this->updateLog($param,$userId);

        if($results)
        {
          return json_encode(array("status" => "success", "msg" => "successfully user information updated ."));
        }
        else 
        {
          //log error  
          trigger_error('problem while get account manager details,query:'.$sql);  
          return json_encode(array("status" => "", "msg" => "user information not update."));
        }  
       
   }
    
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @param type $userId
    * @param type $resellerId
    * @param type $type 2 for change user to reseller or 3 for change reseller to user 
    * @return type json data msg and status
    */
   function changeUserTypeStatus($param,$session){
      
//       if($param['userType'] !=2 && $param['userType']!=3){
//          return json_encode(array("status" => "error", "msg" => "please select valid user type")); 
//       }
       
       
      if (!preg_match("/^[0-9]+$/", $param['userId'])) {
           return json_encode(array("status" => "error", "msg" => "UserId not valid."));
        }  
        
      if (!preg_match("/^[0-9]+$/", $param['userType'])) {
           return json_encode(array("status" => "error", "msg" => "userType not valid."));
        }    
        
      #check permission for change user to reseller
      $UserReseller = $this->getResellerId($param['userId']);  
      
      if($session['client_type'] != 1){
      if($UserReseller != $session['userid'])
      {
          return json_encode(array("status" => "error", "msg" => "you have no permission for change user type."));
      } 
      }
      
      #change reseller to user 
      if($param['userType'] == 3){
          
          #check reseller have any client or not if reseller have no any client then change status reseller to user 
          #otherwise reseller will not be change to user status 
          $allUsers = $this->getChainAllUser($param['userId']);
          
          if(count($allUsers) > 1){
              return json_encode(array("status" => "error", "msg" => "There are many users below reseller so you can't change reseller status."));
          }
          
      }
       
       $table = '91_userLogin';

       $data = array("type"=>$param['userType']);
       $condition = "userId=" . $param['userId'] . " ";
       $this->db->update($table, $data)->where($condition);
       $results = $this->db->execute();
       
       if($param['userType'] == 3){
        $detail = "update reseller to user";   
        $this->accountManagerLog($param['userId'],5,"Reseller","User",$UserReseller,$detail);
       }else{
         $detail = "update User to Reseller";
         $this->accountManagerLog($param['userId'],5,"User","Reseller",$UserReseller,$detail);  
       }
       if($results)
       {
           $msg = "successfully ".$detail;
           return json_encode(array("status" => "success", "msg" => $msg));
       }
       else
       {
           //log error
            trigger_error('problem while get user login details,condition:'.$condition);
            return json_encode(array("status" => "error", "msg" => "error in change user type status ."));
       }
   }
   
   
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since 31/10/2013
    * @description function use to change user to reseller 
    */
//   function changeUserToReseller($userId,$resellerId,$type)
//   {
//       
//      #check permission for change user to reseller
//      $UserReseller = $this->getResellerId($userId);  
//      
//      if($type != 1){
//      if($UserReseller != $resellerId)
//      {
//          return json_encode(array("status" => "error", "msg" => "you have no permission for change user to reseller  ."));
//      } 
//      }
//       
//      $table = '91_userLogin';
//       
//      #check given user are already reseller 
//      $this->db->select('type')->from($table)->where("userId='". $userId ."' and type = 2" );
//      $sql = $this->db->getQuery();
//      $result = $this->db->execute();
//      
//       if(!$result)
//            trigger_error('problem while get user login details,query:'.$sql);
//      
//      if ($result->num_rows > 0) 
//      {
//            return json_encode(array("status" => "error", "msg" => "sorry given user are already reseller!"));
//      }
//       
//       $data = array("type"=>2);
//       $condition = "userId=" . $userId . " ";
//       $this->db->update($table, $data)->where($condition);
//       $results = $this->db->execute();
//       
//       $this->accountManagerLog($userId,5,"User","Reseller",$resellerId,"update User to Reseller");
//        
//       if($results)
//       {
//           return json_encode(array("status" => "success", "msg" => "successfully User change to Reseller ."));
//       }
//       else
//       {
//           //log error
//            trigger_error('problem while get user login details,condition:'.$condition);
//            return json_encode(array("status" => "error", "msg" => "error in change user to reseller ."));
//       }
//   }
//   
   
       /**
     * @author Nidhi <nidhi@walkover.in>
     */
    function addFeedBackAndRequirements($parm)
    {
        if (strlen($parm['fullName']) < 1  ||strlen($parm['emailId']) < 1 || strlen($parm['contactNo']) < 1 || strlen($parm['message']) < 1 ) 
        {
           return json_encode(array("status"=>"error",
                                    "message"=>"Incomplete From!"));  
        }
        
        if (!preg_match("/^[0-9]{8,15}$/", $parm['contactNo'])) 
        {
            return json_encode(array("status" => "error", 
                              "message" => "contact no. is not valid!"));
        }
        
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $parm['emailId'])) 
        {
            return json_encode(array("status" => "error", 
                                     "message" => "email id is not valid !"));
        }
       
        if(!preg_match('/^[a-zA-Z0-9\@\_\-\s]*[A-Za-z][a-zA-Z0-9\@\_\-\s]*$/', $parm['fullName']))
        {
           return json_encode(array("status" => "error",
                                    "message" => "Please enter valid Name!"));
        }
        
       if(!preg_match('/[a-zA-Z0-9\@\_\-\s]*[A-Za-z][a-zA-Z0-9\@\_\-\s]$/', $parm['message']))
        {
           return json_encode(array("status" => "error",
                                    "message" => "Please enter valid Message!"));
        }
        
        if($parm['relatedTo'] == '0')
        {
             return json_encode(array("status" => "error",
                                    "message" => "Please enter valid Related to !"));
        }

        $messageResponse = "";
        switch($parm['type'])
        {
            case '0':
               
                break;
            
            case '3':
                $message = "Thank you for sharing with us! We will get back to you shortly";
                    $reqParam['fullName'] = $parm['fullName'] ;
                    $reqParam['emailId'] = $parm['emailId'] ;
                    $reqParam['contactNo'] = $parm['contactNo'] ;
                    $reqParam['relatedTo'] = $parm['relatedTo'] ;
                    $reqParam['message'] = $parm['message'] ;
                    
//                   $messageResponse =  $this->contactTemplate();
                    $subject = 'Your query at Phone91';
                    
                break;
            
            case '2':
                    
                    if($parm['resellerVia'] == '0')
                    {
                        return json_encode(array("status" => "error",
                                       "message" => "Please Select field - you wish to be reseller via!"));
                    }
                    
                    if($parm['resellerVia'] == 'callingcards'){
                    if (!preg_match("/^((\d+(\.\d *)?)|((\d*\.)?\d+))$/", $parm['estimatedVolume'])) 
                    {
                        return json_encode(array("status" => "error", 
                                "message" => "Please Enter Valid estimated Volume!"));
                    }
                    
                    $reqParam['estimatedVolume'] = $parm['estimatedVolume'] ." ".$parm['dealCurrency'];
                    }else
                    $reqParam['estimatedVolume'] = $parm['volume'] ." ".$parm['dealCurrency'];
                    
                    $reqParam['resellerVia'] = $parm['resellerVia'] ;
                    $reqParam['fullName'] = $parm['fullName'] ;
                    $reqParam['emailId'] = $parm['emailId'] ;
                    $reqParam['contactNo'] = $parm['contactNo'] ;
                    $reqParam['message'] = $parm['message'] ;
                    
                    
                    $messageResponse =  $this->resellerTemplate();
                    
                    //whitelabelsolutions
                    if($parm['resellerVia'] == 'whitelabelsolutions')
                    {
                        if(count($parm['country']) < 1 || count($parm['overAllVolume']) < 1 || count($parm['callrate']) < 1 )
                        {
                             return json_encode(array("status" => "error", 
                                "message" => "Please Enter Valid overall Volume and rate!"));
                        }
                        
                        $reqParam['country'] = array();
                        foreach($parm['country'] as $key=>$val)
                        {
                            if(!preg_match('/^[a-zA-Z0-9\@\_\-\s]*[A-Za-z][a-zA-Z0-9\@\_\-\s]*$/', $val))
                            {
                                return json_encode(array("status" => "error",
                                        "message" => "Please Select Valid Country Name!"));
                            }
                            else 
                            {
                                $reqParam['country'][] = $val;
                            }
                            
                        }
                        
                        $reqParam['overallVolume'] = array();
                        foreach($parm['overAllVolume'] as $key=>$val)
                        {
                           if(!preg_match('/^((\d+(\.\d *)?)|((\d*\.)?\d+))$/', $val))
                           {
                               return json_encode(array("status" => "error",
                                       "message" => "Please enter valid overall volume!"));
                           }
                           else 
                           {
                               $reqParam['overallVolume'][] = $val;
                           }

                        }
                        
                        
                        $reqParam['callrate'] = array();
                        foreach($parm['callrate'] as $key=>$val)
                        {
                            
                           if(!preg_match('/^((\d+(\.\d *)?)|((\d*\.)?\d+))$/', $val))
                           {
                               return json_encode(array("status" => "error",
                                       "message" => "Please enter valid Call Rate!"));
                           }
                           else 
                           {
                               $reqParam['callrate'][] = $val;
                           }

                        }
                       
                        //print_r($reqParam);
                        
                    }
                    $subject = 'Your query to become a reseller';
                    $message = "Reseller Request Sent Successfully";
                break;
                
                 case '1':
                     $message = "Admin Request Sent Successfully";
                     
            
                    
                    if (!preg_match("/^((\d+(\.\d *)?)|((\d*\.)?\d+))$/", $parm['volume'])) 
                    {
                        return json_encode(array("status" => "error", 
                                "message" => "Please Enter Valid estimated Volume!"));
                    }
                    
                    if($parm['volume'] < 800000)
                    {
                        $table = "Your estimated volume is very low. you can try our reseller pannel";
                        $this->sendMail( $table , $parm['emailId']  ); 
                    }
                    
                    
                    $reqParam['estimatedVolume'] = $parm['volume'] ;
                  
                    $reqParam['fullName'] = $parm['fullName'] ;
                    $reqParam['emailId'] = $parm['emailId'] ;
                    $reqParam['contactNo'] = $parm['contactNo'] ;
                    $reqParam['message'] = $parm['message'] ;
                    $reqParam['currency'] = $parm['dealCurrency'] ;
                    
                    //dealCurrency
                    //whitelabelsolutions
                  //  if($parm['resellerVia'] == 'whitelabelsolutions')
                    {
                        if(count($parm['country']) < 1 || count($parm['overAllVolume']) < 1 || count($parm['callrate']) < 1 )
                        {
                             return json_encode(array("status" => "error", 
                                "message" => "Please Enter Valid overall Volume and rate!"));
                        }
                        
                        $reqParam['country'] = array();
                        
                       
                        
                        foreach($parm['country'] as $key=>$val)
                        {
                            if(!preg_match('/^[a-zA-Z0-9\@\_\-\s]*[A-Za-z][a-zA-Z0-9\@\_\-\s]*$/', $val))
                            {
                                return json_encode(array("status" => "error",
                                        "message" => "Please Select Valid Country Name!"));
                            }
                            else 
                            {
                                $reqParam['country'][] = $val;
                            }
                            
                        }
                        
                        $reqParam['overallVolume'] = array();
                        foreach($parm['overAllVolume'] as $key=>$val)
                        {
                           if(!preg_match('/^((\d+(\.\d *)?)|((\d*\.)?\d+))$/', $val))
                           {
                               return json_encode(array("status" => "error",
                                       "message" => "Please enter valid overall volume!"));
                           }
                           else 
                           {
                               $reqParam['overallVolume'][] = $val;
                           }

                        }
                        
                        
                        $reqParam['callrate'] = array();
                        foreach($parm['callrate'] as $key=>$val)
                        {
                            
                           if(!preg_match('/^((\d+(\.\d *)?)|((\d*\.)?\d+))$/', $val))
                           {
                               return json_encode(array("status" => "error",
                                       "message" => "Please enter valid Call Rate!"));
                           }
                           else 
                           {
                               $reqParam['callrate'][] = $val;
                           }

                        }
                       
//                      $messageResponse =  $this->adminTemplate();
                        
                    }
                     
                     $subject = 'Your query to take admin panel on rent';
                     break;
                
        }
        
        

        
        
        
        $table = '<div>';$volDetail='';
        foreach($reqParam as $key=>$value)
        {
            $volDetail='';
            if(is_array($value)) 
            {
                for($i=0;$i<count($value);$i++){
                $volDetail.='<div><span><b>'.$reqParam['country'][$i].'</b></span> : <span>'.$reqParam['overallVolume'][$i].'min </span> <span>'.$reqParam['callrate'][$i]." ".$parm['dealCurrency'].'</span></div><div></div>';
                }
//                $table.='<div><span><b>'.$key.'</b></span> : </br> <span>';
//               foreach($value as $key=> $val)
//               {
//                   $table.= '&nbsp;&nbsp;'.$val.'&nbsp;&nbsp;';
//               }
//                    $table.='</span></div><div>&nbsp;</div>';
            }
            else
                $table.='<div><span><b>'.$key.'</b></span> - <span>'.$value.'</span></div><div></div>';
        }
        $table.=$volDetail;
        $table.='</div>';
        
       
        //$this->sendMail($table, "support@phone91.com");
       $response = $this->sendErrorMail($reqParam['emailId'],$messageResponse,"support@phone91.com",$subject);
       //$response = $this->sendMail($messageResponse , $reqParam['emailId'] ,$subject );
       $this->sendErrorMail("support@phone91.com",$table,$reqParam['emailId'],'Reseller Query');
       //$this->sendMail($table, "sudhir@hostnsoft.com" , 'verify user email');
       
        //$result = $db_obj->mongo_insert("feedBack",$reqParam);
        
         $response = array( "status" => "success" , "message" => $message );
//        if(!$result)
//        {
//            $response = array( "status" => "error" , "message" => "An Error Occoured Please Try Again" );
//        }
//        else 
//        {
//           
//        }
        
        
        
        
        return json_encode($response);
      
    }
    
    
    function resellerTemplate()
    {
        $mailData = <<<EOF

        <html xmlns="http://www.w3.org/1999/xhtml" style="background:#fff">
        <body style="background:#fff; padding:0; margin:0; font:12px Verdana, Geneva, sans-serif; font-size:14px; color:#999; line-height:22px">
        <!--Main wrapper-->
        <div style="width:625px; margin:0 auto;  background:#fff;">
        <!--Header-->
        <div style="height:5px;background-color:#FFCD53;"><span style="height:5px;background-color:#296FA2; width:100px; display:block"></span></div>
        <!--Mid content-->
        <div>
        <h1 style="color:#296FA2; font-weight:normal; text-align:left">Hey,</h1>
        <div style="padding:20px 0;">
                 We are happy to know that you are interested in our Reseller panel. On the basis of the details you have shared with us, one 
        of our representatives will get in touch with you within 24 hours.
                </div>
        </div>

        <div>
        Till then, find out what's new on Phone91 at  <a href="#"  style="color:#296FA2;  text-decoration:none">Facebook</a>, <a href="#"  style="color:#296FA2;  text-decoration:none">Twitter</a> and our blog - <a href="#"  style="color:#296FA2;  text-decoration:none">Phone 91</a>. You never know when you find something interesting. May be a running offer.
        </div>

        <div style=" font-size:15px;  margin-top:20px;">
        Stay closer<br/>
        Team Phone91
        </div>
        </div>
        <!--//Main wrapper-->
        </body>
        </html>
EOF;
        return $mailData;
    }
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param type $param : subject , message , and send to user or reseller 
     * @param type $session : user id , login user detail
     */
    function sendBulkMail($param,$session){
       
        #validation for subject 
        if($param['subject'] == '' || $param['subject'] == NULL){
             return json_encode(array("status" => "error", "msg" => "subject field is required !"));
        }
        
        if(strlen(trim($param['subject'])) < 3 && strlen(trim($param['subject'])) > 30 ){
             return json_encode(array("status" => "error", "msg" => "please enter valid subject min length 3 and max 30 characters !"));
        }
        
        #validation for mail 
        
        if($param['message'] == '' || $param['message'] == NULL){
             return json_encode(array("status" => "error", "msg" => "message field is required !"));
        }
        
        if(strlen(trim($param['message'])) < 3 && strlen(trim($param['message'])) > 1000 ){
             return json_encode(array("status" => "error", "msg" => "please enter valid message min length 3 and max 1000 characters !"));
        }
        
        #check check box value to send sms to user or reseller 
        if(!isset($param['sendUser']) && !isset($param['sendReseller'])){
             return json_encode(array("status" => "error", "msg" => "please checked atleast one check box either user or reseller !"));
        }
        
        
        #use variable to get user and reseller user id
        $useridArray = array();$reselleridArray=array();
        
        #check mail send only user
        if(isset($param['sendUser']) && $param['sendUser'] == 'on'){
             $useridArray = $this->searchByUserType(3,$session['id']);
       }
        
        #check mail send only reseller
        if(isset($param['sendReseller']) && $param['sendReseller'] == 'on'){
             $reselleridArray = $this->searchByUserType(2,$session['id']);
        }
        
        $newArray = array_merge($useridArray,$reselleridArray);
        #mail send user and reseller both 
        $condition  = implode(",", $newArray);  
        $newcondition = " and userId IN (".$condition.")";
        
        #get all verified email id of user
        $emailArray = $this->getAllVerifiedEmail($newcondition);
        
              
        $remainId = array_diff($newArray, $emailArray['userId']);
//        print_r($newArray);
//        print_r($remainId);
//        print_r($emailArray['email']);
        
        $tempCondition  = implode(",", $remainId);  
        $tempCondition = "userid IN (".$tempCondition.")";
        #get all temp email id of user
        $tempEmailArray = $this->getAllTempEmail($tempCondition);
        
        $finalEmailArray = array_merge($emailArray['email'],$tempEmailArray['email']);
        
        $subject = $param['subject'];
        $message = $param['message'];
        if(isset($session['acmEmail'])){
         $from = $session['acmEmail'];   
        }else
        $from = 'support@phone91.com'; // admin email id 
        include_once '../sendmail.php';
        
        $mail = new MailAndErrorHandler();
        
        //call function to send mail
        if(!$mail->sendmail_mandrill($finalEmailArray, $subject, $message, $from,'','',array(),'',1)){
                trigger_error('problem while send mail ,backtrace:'. json_encode(debug_backtrace()));
              return json_encode(array("status" => "error", "msg" => "Mail sending failed!"));
    
        }
        //free object space
        unset($mail);
        
        return json_encode(array("status" => "success", "msg" => "successfully mail send..!"));
        
  
    }
    
     /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param type $param : subject , message , and send to user or reseller 
     * @param type $session : user id , login user detail
     */
    function sendBulkSms($param,$session){
       
        #validation for subject 
        if($param['senderId'] == '' || $param['senderId'] == NULL){
             return json_encode(array("status" => "error", "msg" => "sender Id field is required !"));
        }
        
        if(strlen(trim($param['senderId'])) < 3 && strlen(trim($param['senderId'])) > 30 ){
             return json_encode(array("status" => "error", "msg" => "please enter valid sender id min length 3 and max 30 characters !"));
        }
        
        #validation for mail 
        
        if($param['content'] == '' || $param['content'] == NULL){
             return json_encode(array("status" => "error", "msg" => "content field is required !"));
        }
        
        if(strlen(trim($param['content'])) < 3 && strlen(trim($param['content'])) > 160 ){
             return json_encode(array("status" => "error", "msg" => "please enter valid content min length 3 and max 160 characters !"));
        }
        
        #check check box value to send sms to user or reseller 
        if(!isset($param['sendUserSms']) && !isset($param['sendResellerSms']) && !isset($param['sendAllChain'])){
             return json_encode(array("status" => "error", "msg" => "please checked atleast one check box !"));
        }
        
        
        
        #use variable to get user and reseller user id
        $useridArray = array();$reselleridArray=array();
        
        if(isset($param['sendAllChain']) && $param['sendAllChain'] == 'on'){
            $newArray = $this->getChainAllUser($session['id']);
            
        }else
        {
            #check mail send only user
            if(isset($param['sendUserSms']) && $param['sendUserSms'] == 'on'){
                $useridArray = $this->searchByUserType(3,$session['id']);
            }

            #check mail send only reseller
            if(isset($param['sendResellerSms']) && $param['sendResellerSms'] == 'on'){
                $reselleridArray = $this->searchByUserType(2,$session['id']);
            }

            $newArray = array_merge($useridArray,$reselleridArray);
            
        }
        
        if(count($newArray) < 1){
             return json_encode(array("status" => "error", "msg" => "you have no any user or reseller !"));
        }
        
        #mail send user and reseller both 
        $condition  = implode(",", $newArray);  
        $newcondition = "userId IN (".$condition.")";
           
        #get all verified email id of user
        $numberArray = $this->getAllVerifiedNumber($newcondition);
        
        $remainId = array_diff($newArray, $numberArray['userId']);
        
        
        $tempUser  = implode(",", $remainId);  
        $tempcondition = "userId IN (".$tempUser.")";
        
        $tempNumberArray = $this->getAllTempNumber($tempcondition);
        
        
        
         $nineIndiaArray = array_merge($numberArray['number'],$tempNumberArray['number']);
        
         $usdArray = array_merge($numberArray['usdNumber'],$tempNumberArray['usdNumber']);
         
        
         $newarr = array_chunk($nineIndiaArray,90);
          
         foreach($newarr as $subarr){
            $nine['sender'] = $param['senderId'];
            $nine['message'] = $param['content'];
            $nine['mobiles'] = implode(",", $subarr);
            $this->SendSMS91($nine);
            $nine = array();
           // echo $nine['mobiles'];
         }
         
         $newUsdarr = array_chunk($usdArray,90);
        
         foreach($newUsdarr as $subarr){
          $d['sender'] = $param['senderId'];
          $d['message'] = $param['content'];
          $d['mobiles'] = implode(",", $subarr);
          $this->SendSMSUSD($d);
          $d = array();
          //echo $d['mobiles'];
         }

         return json_encode(array("status" => "success", "msg" => "successfully SMS send..!"));
    }
    
   
    function getChainAllUser($userId){
        
        #find chain id of given user 
        $userChainId = $this->getUserChainId($userId);
        if($userChainId == false || $userChainId == "" || $userChainId == NULL)
            return array();
        
        #find all child user id of given user
        $table = '91_userBalance';
        $condition = "chainId like '" . $userChainId . "%' ";
        $this->db->select('userId')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();  
        
        //log errors
        if(!$result)
            trigger_error('problem while get user balance detail ,query:'.$qur);
        
        if ($result->num_rows > 0) 
        {
            while($data= $result->fetch_array(MYSQL_ASSOC) ) 
            {
                $useridArray[] = $data['userId'];
            }
        }else        
        $useridArray = array();
        
        return $useridArray;
        
    }
    
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 03/09/2013
    #function use to search client by type
    function searchByUserType($type,$reseller){
        
        $table = '91_manageClient';
        $condition = "type = ".$type." and resellerId =".$reseller;
        $useridArray = $this->getUserIdByCondition($table,$condition);
        return $useridArray;
         
    }
    
    #created by sudhir pandey 
    #creation date 21-02-14
    #get all verified email of all user or reseller   
    function getAllVerifiedEmail($condition){
        
        $table = '91_verifiedEmails';
        $newcondition = "default_email = 1 ".$condition;
        $this->db->select('email,userId')->from($table)->where($newcondition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        if(!$result)
            trigger_error('problem while get user id ,query:'.$qur);
        
        
        if ($result->num_rows > 0) {
         while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
             $useridArray['userId'][] = $row['userId'];
             $useridArray['email'][] = $row['email'];
         }
        }else
            $useridArray = array();
        
         return $useridArray;
    }
    
    #created by sudhir pandey 
    #creation date 21-02-14
    #get all verified number of all user or reseller   
    function getAllVerifiedNumber($condition){
        
        $table = '91_verifiedNumbers';
        $newcondition = "isDefault = 1 and ".$condition;
        $this->db->select('userId,countryCode,verifiedNumber')->from($table)->where($newcondition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        if(!$result)
            trigger_error('problem while get user id ,query:'.$qur);
        
        
        if ($result->num_rows > 0) {
         while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
             
             if($row['countryCode'] == '91'){
                 $useridArray['number'][]= $row['verifiedNumber'];
             }else
                 $useridArray['usdNumber'][] = $row['countryCode'].$row['verifiedNumber'];
             
             $useridArray['userId'][] = $row['userId']; 
         }
        }else
            $useridArray = array();
        
         return $useridArray;
    }
    
    function getAllTempNumber($condition){
         $table = '91_tempNumbers';
        $this->db->select('DISTINCT userId,countryCode,tempNumber')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        if(!$result)
            trigger_error('problem while get user id ,query:'.$qur);
        
        if ($result->num_rows > 0) {
         while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
             
             if($row['countryCode'] == '91'){
                 $useridArray['number'][]= $row['tempNumber'];
             }else
                 $useridArray['usdNumber'][] = $row['countryCode'].$row['tempNumber'];
             
             $useridArray['userId'][] = $row['userId']; 
         }
        }else
            $useridArray = array();
        
         return $useridArray;
    }
    
    function getAllTempEmail($condition){
        $table = '91_tempEmails';
        $this->db->select('DISTINCT userid,email')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        if(!$result)
            trigger_error('problem while get user id ,query:'.$qur);
        
        
        if ($result->num_rows > 0) {
         while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
             $useridArray['userid'][] = $row['userId'];
             $useridArray['email'][] = $row['email'];
         }
        }else
            $useridArray = array();
        
         return $useridArray;
    }
    
    /*
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 08-04-2014
     * @desc function use to get all reaseller name of any user 
     */
    function showChainDetail($param,$session){
        # check for admin or not 
        if($session['client_type'] != 1){
             return json_encode(array("status" => "error", "msg" => "you have no permission to get user reseller name!"));
        }
        
        #check user id is valid or not 
        if(preg_match("/[^0-9]+/",$param['userId']) || $param['userId'] == "")
            return json_encode (array("msg"=>"Invalid user id","status"=>"error"));
        
        #get user chain id from user id 
        $chainId = $this->getUserChainId($param['userId']);
        
        if($chainId == 0 || $chainId == '' || $chainId == NULL){
            return json_encode(array("msg"=>"chain id not found","status"=>"error"));
        }
        
        $chainId = substr($chainId,0,-4);
        $data = array();
        while (strlen($chainId) >= 4) {
            
            $table = '91_manageClient';
            $condition = "chainId ='" . $chainId . "'";
            $this->db->select('*')->from($table)->where($condition);
            $result = $this->db->execute();         
        
            if($result && $result->num_rows > 0)
            {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $data[$row['userId']] = $row['userName'];
            }
            
        $chainId = substr($chainId,0,-4);
        }
        
       return json_encode($data);
        
    }

    /**
    *@author Ankit Patidar <ankitpatidar@hostnsoft.com>
    *@since 29/04/2014
    *@filesource
    *@param int $userId
    *@uses function to get user and reseller count 
    *@return Array 
    */
    function getTotalUserResellerCount($userId)
    {
        if(!is_numeric($userId))
        {
          return  array('status' => 0,'msg' => 'Invalid user!!!' );
        }

        if($_SESSION['id'] != $userId)
          return  array('status' => 0,'msg' => 'Invalid login please login again!!!' );

        $table = '91_manageClient';

        //code to get user count
        $conditionUser = 'type=3 and resellerId='.$userId;
        $result = $this->selectData('count(userId) as totalUser',$table,$conditionUser);
        if(!$result)
        {
          trigger_error('problem while get user count!!!query:'.$this->querry);
          return  array('status' => 0,'msg' => 'problem while get user count!!!' );          

        }
        $resultUser = $result->fetch_array(MYSQLI_ASSOC);
        $countArr= array();
        $countArr['userCount'] = $resultUser['totalUser'];


        //code to get reseller count
        $conditionUser = 'type=2 and resellerId='.$userId;
        $resultRes = $this->selectData('count(userId) as totalReseller',$table,$conditionUser);
        if(!$resultRes)
        {
          trigger_error('problem while get user count!!!query:'.$this->querry);
          return  array('status' => 0,'msg' => 'problem while get user count!!!' );          
        }
        $resultReseller = $resultRes->fetch_array(MYSQLI_ASSOC);
        $countArr['resellerCount'] = $resultReseller['totalReseller'];

        return  array('status' => 1,'msg' => 'record found!!!','counts' => $countArr );


    }
    
}

//end of class
?>