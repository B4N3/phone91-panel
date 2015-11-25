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
    function editFundOld($parm,$userid)
    {      
        
     
      
      if (preg_match("/[^0-9]+/", $parm['toUserEditFund']) || $parm['toUserEditFund'] == "" ) {
            return json_encode(array("status" => "error", "msg" => "Invalid client user please select a user to transfer fund"));
      }
      
      #check user have permission for edit fund or not 
      $resellerId = $this->getResellerId($parm['toUserEditFund']);  
      
      if($resellerId != $userid){
          return json_encode(array("status" => "error", "msg" => "you have no permission for edit fund ."));
      }
      
      if($parm['fundAmount'] < 0){
          return json_encode(array("status" => "error", "msg" => "please enter valid fund ."));
      }
        
      if($parm['balance'] < 0){
          return json_encode(array("status" => "error", "msg" => "please enter valid balance ."));
      }
      
      if (preg_match("/[^0-9]+/", $parm['fundAmount'])) {
            return json_encode(array("status" => "error", "msg" => "fund amount are not valid ! "));
      }
      
      if (preg_match("/[^0-9]+/", $parm['fundCurrency']) || strlen($parm['fundCurrency']) < 1 ) {
            return json_encode(array("status" => "error", "msg" => "fund currency is not valid ! "));
      }
      
      
        
       if (preg_match("/[^0-9]+/", $parm['balance'])) {
            return json_encode(array("status" => "error", "msg" => "please enter valid balance ! "));
        }  
      
       if($parm['pType'] == "partial"){
           
           if (preg_match("/[^0-9]+/", $parm['partialAmt'])) {
            return json_encode(array("status" => "error", "msg" => "please enter valid partial amount ! "));
            }
            
            if(strlen(trim($parm['partialAmt'])) > 5){
                return json_encode(array("status" => "error", "msg" => "please enter partial amount no more than 5 characters. ! "));
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
               return json_encode(array("status" => "error", "msg" => "description invalid user of quotes or < > are prohibited"));
       
       if(isset($parm['fundPaymentType']) && $parm['fundPaymentType'] == "Other")
       {
          if(preg_match("/[^a-zA-Z]+/",$parm['otherPaymentType']) || $parm['otherPaymentType'] == "")
                  return json_encode(array("status" => "error", "msg" => "please provide a valid other payment type value"));
       }
       
        
      #include transaction class   
      include_once("transaction_class.php");
      
      #object of transaction class
      $transaction_obj = new transaction_class();
      
      //********* update closing Amount 
      
      #get current closing Amount of user 
      $amount = $transaction_obj->getClosingBalance($parm['toUserEditFund']);  
      
      # variable fundAmount use to which amount will be update
      $fundAmount = $parm['fundAmount'];
      $talktime = $parm['balance'];
      #variable ptype : Payment Type (partial ,postpaid,prepaid) 
      $pType = $parm['pType'];
      
      
      
      //*** update balance of user 
      
      $balance = $transaction_obj->getcurrentbalance($parm['toUserEditFund']);
      
      #check balance add or reduce in currentbalance 
      if($parm['changefunderEditFund'] == "add"){
        #new updated amount current amount + given amount  
        $updatedBalance = $balance + $parm['balance']; 
      }else{
        $updatedBalance = $balance - $parm['balance']; 
        $talktime = ((int)-$parm['balance']); 
        
        
        // fund amount 
        $fundAmount = ((int)-$parm['fundAmount']);
        $pType = "";
        
      }
      
      
      
      //*** entry in transaction log table
      if($parm['fundPaymentType'] == "Other"){
        $fundpaymentType = $this->db->real_escape_string($parm['otherPaymentType']);     
      }else
        $fundpaymentType = $parm['fundPaymentType'];
      
      #add transaction in case of voip91(payment type advance).
      $result = $transaction_obj->addTransactional($userid,$parm['toUserEditFund'],$fundAmount,$talktime,$fundpaymentType,$parm['fundDescription'],$pType,$parm['partialAmt'],$parm['fundCurrency'],$parm['partialCurrency']);
      
      
      #update user balance table 91_userbalance table
      $transaction_obj->updateUserBalance($parm['toUserEditFund'],$updatedBalance); 
      
//      if($parm['changefunderEditFund'] != "add"){
//      $updatedAmount = ($amount + $transaction_obj->newClosingAmount);
//      #update user closing Amount into 91_closingAmount table
//      $transaction_obj->updateClosingBalance($parm['toUserEditFund'],$updatedAmount); 
//      }
      
      if($result == 1){
          return json_encode(array("status" => "success", "msg" => "successfully update user fund ."));
      }
      
      
    }

     function editFund($parm,$userid)
    {      
      
      if (preg_match("/[^0-9]+/", $parm['toUserEditFund']) || $parm['toUserEditFund'] == "" ) {
            return json_encode(array("status" => "error", "msg" => "Invalid client user please select a user to transfer fund"));
      }
      
      #check user have permission for edit fund or not 
      $resellerId = $this->getResellerId($parm['toUserEditFund']);  
      
      if($resellerId != $userid){
          return json_encode(array("status" => "error", "msg" => "you have no permission for edit fund ."));
      }
      
      if($parm['fundAmount'] < 0){
          return json_encode(array("status" => "error", "msg" => "please enter valid fund ."));
      }
        
      if($parm['balance'] < 0){
          return json_encode(array("status" => "error", "msg" => "please enter valid balance ."));
      }
      
      if (preg_match("/[^0-9]+/", $parm['fundAmount'])) {
            return json_encode(array("status" => "error", "msg" => "fund amount are not valid ! "));
      }
      
      if (preg_match("/[^0-9]+/", $parm['fundCurrency']) || strlen($parm['fundCurrency']) < 1 ) {
            return json_encode(array("status" => "error", "msg" => "fund currency is not valid ! "));
      }
      
      
        
       if (preg_match("/[^0-9]+/", $parm['balance'])) {
            return json_encode(array("status" => "error", "msg" => "please enter valid balance ! "));
        }  
      
       if($parm['pType'] == "partial"){
           
           if (preg_match("/[^0-9]+/", $parm['partialAmt'])) {
            return json_encode(array("status" => "error", "msg" => "please enter valid partial amount ! "));
            }
            
            if(strlen(trim($parm['partialAmt'])) > 5){
                return json_encode(array("status" => "error", "msg" => "please enter partial amount no more than 5 characters. ! "));
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
               return json_encode(array("status" => "error", "msg" => "description invalid user of quotes or < > are prohibited"));
       
       if(isset($parm['fundPaymentType']) && $parm['fundPaymentType'] == "Other")
       {
          if(preg_match("/[^a-zA-Z]+/",$parm['otherPaymentType']) || $parm['otherPaymentType'] == "")
                  return json_encode(array("status" => "error", "msg" => "please provide a valid other payment type value"));
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
      
      //free object space
      unset($transaction_obj);
      
      if($result == 1)
      {
          return json_encode(array("status" => "success", "msg" => "successfully update user fund ."));
      }
      else
          return json_encode(array("status" => "error", "msg" => "problem while update user fund ."));
      
    }
    #created by sudhir pandey <sudhir@hostnsoft.com> 
    #creation date 07/08/2013
    #function use to edit client information 
    function editClientInfo($parm,$userId){
        
         #table name 
         $table = "91_userBalance";
         
         if (!preg_match("/^[0-9]+$/", $parm['callLimit'])) {
            return json_encode(array("status" => "error", "msg" => "please enter valid call limit ! "));
        }
        
         if (!preg_match("/^[0-9]+$/", $parm['currenctTariff'])) {
            return json_encode(array("status" => "error", "msg" => "please select valid tariff ! "));
        }
        
        if(isset($parm['bandwidthLimit'])){
            $bandwidthLimit = $parm['bandwidthLimit'];
        }else
            $bandwidthLimit = 0;
        
         if (!preg_match("/^[0-9]+$/", $bandwidthLimit)) {
            return json_encode(array("status" => "error", "msg" => "please enter valid Bandwidth Limit ! "));
        }
        
       
        
      #check permission for add transaction or not 
      $resellerId = $this->getResellerId($parm['clientId']);  
      
      if($resellerId != $userId){
          return json_encode(array("status" => "error", "msg" => "you have no permission for edit client info  ."));
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
           
           return json_encode(array("status" => "success", "msg" => "successfully user information updated ."));
         }  else {
             
           return json_encode(array("status" => "", "msg" => "user information not update."));
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
    function getBatchDetail($batchId){
        #table name 
        $table = '91_bulkUser';
        $condition = "batchId = '".$batchId."' and userId = '".$_SESSION['userid']."'";
        $this->db->select('*')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log the error if error occur 
        if(!$result){
            trigger_error('problem while get batch detail ,query:'.$qur);
            
            
        }
         if ($result->num_rows > 0) {
         $row = $result->fetch_array(MYSQL_ASSOC);	    
             
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
        $this->db->select('*')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();  
        
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
             $userdata['blockUnblockStatus'] = $logindata['isBlocked'];
               }
             $userBatchData['userDetail'][] = $userdata;
          }     
        }else
             $userBatchData['userDetail'] = array(); 
        
      return json_encode($userBatchData);       
        
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 05/09/2013
    #function use to change bulk client status used or unused username and password . 
    function changeBulkClientStatus($parm,$resellerId){
       
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
    #creation date 29-07-2013
    #function use to add client detial
    function addNewClient($parm, $resellerId) {
        
               
        #check username is blank or not
        if ($parm['username'] == '' || $parm['username'] == NULL) {
            return json_encode(array("status" => "error", "msg" => "Please insert user name ."));
        }

        if(!preg_match('/^[a-zA-Z0-9\@\_\-\s]+$/', $parm['username'])){
           return json_encode(array("status" => "error", "msg" => "user name are not valid!"));
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

        #check email id is valid or not 
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $parm['email'])) {
            return json_encode(array("status" => "error", "msg" => "email id is not valid !"));
        }

        #check tariff paln is selected or not 
        if ($parm['tariff'] == "select") {
            return json_encode(array("status" => "error", "msg" => "Please Select Tariff Plan ! "));
        }

        #chech payment type is selected or not 
//        if ($parm['payType'] == "select") {
//            return json_encode(array("status" => "error", "msg" => "Please Select Payment Type ! "));
//        }

        #check total no of pins is numeric or not 
//        if (!preg_match("/^[0-9]+$/", $parm['clientBalance'])) {
//            return json_encode(array("status" => "error", "msg" => "Numeric value required in balance field ! "));
//        }
        
        if(!preg_match('/^[a-zA-Z0-9\@\_\-\!\$\(\)\?\[\]\{\}\s]+/', $parm['password'])){
           return json_encode(array("status" => "error", "msg" => "please enter a valid password must not containg any spacial character other than '@_-!$()[]{}?' "));
        }
        
        #check for user type is valid or not  (3 -> user or 2 -> reseller)
        if ($parm['userType'] != 2 && $parm['userType'] != 3) {
            return json_encode(array("status" => "error", "msg" => "Please Select User Type ! "));
        }

        //to check  phoneno already existes or not
        $table = '91_verifiedNumbers';
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
        
        
        #insert user detail in personal info table
        $personalTable = '91_personalInfo';
        $name = $this->db->real_escape_string($parm['username']);
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
        $data = array("userId" => (int) $userId, "userName" => $name, "password" => $pass, "isBlocked" => $blockUnblockStatus,"deleteFlag"=>$deleteFlag, "type" => $parm['userType']);

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
        $data = array("userId" => (int) $userId,"chainId"=>$chainId, "tariffId" => (int) $parm['tariff'], "balance" => 0, "currencyId" => (int) $currency_id, "callLimit" => (int) $call_limit, "resellerId" => (int) $resellerId);

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
        
       
        $resellerClient = $this->loadUsers(0, $resellerId, 0, 10, 3);
        return json_encode(array('status' => 'success', 'msg' => 'successsully client added',"resellerClient"=>$resellerClient));//,"resellerClient"=>$resellerClient
    }
    
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 04/09/2013
    #function use to find bulk client batch 
    function bulkUserBatch($resellerId){
        
        # table name for find resller plan name 
        $table = '91_manageClient';
        $condition = "userId = '".$resellerId."'";
        $this->db->select('*')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        if(!$result)
            trigger_error('problem while get details for manage client ,query:'.$qur);
        
         if ($result->num_rows > 0) {
             $row= $result->fetch_array(MYSQL_ASSOC);
             $planName = $row['planName'];
        }
        
        
        # get only 2 batch detail who created by current user  (login user batch detail)
        $table = '91_bulkUser';
        $condition = "userId = '".$resellerId."' order by batchId desc limit 0,2";
        $this->db->select('*')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        if(!$result)
            trigger_error('problem while get details for bulk user ,query:'.$qur);
        
         if ($result->num_rows > 0) {
          while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
		    
              
              #batch id 
              $data['batchId'] = $row['batchId'];
              #batch Name 
              $data['batchName'] = $row['batchName'];
              #no. of client in batch 
              $data['numberOfClients'] = $row['numberOfClients'];
              #batch expiry date 
              $data['expiryDate'] = $row['expiryDate'];
              #reslerId
              $data['resellerId'] = $row['userId'];
              #batch created date 
              $data['createDate'] = $row['createDate'];
              #plan name 
              $data['planName'] = $planName;
              #balance per user of batch 
              $data['batchBalance'] = $row['batchBalance'];
              
                     
              
              #get batch detail 
              extract($this->batchBlockUnblockStatus($row['batchId'])); // $blockUnblockStatus,$tariffId,$currencyId,$tariffName            
              
              $data['blockStatus'] = $blockUnblockStatus;
              $data['currencyName'] = $this->getCurrencyViaApc($currencyId);
              $data['tariffName'] = $tariffName;
              
              
              $userBatchData[] = $data;
            }
             
         }else $userBatchData = array();
        
         
      return json_encode($userBatchData);       
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 16-09-2013
    #function use to block  user batch (all client of given batch id) OR set Batch Delete flage
    # parm : type use either isBlock or deleteFlag, both are column name of userLogin table 
    function BatchBlockOrUnblock($parm,$resellerId,$type){
        
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
        $this->db->select('userId,tariffId,currencyId')->from($table)->where($condition);
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
              
         }
         
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
         
         # get user block status ..
         $table = '91_userLogin';
         $condition = "userId = '".$userId."'";
         $this->db->select('isBlocked')->from($table)->where($condition);
         $qur = $this->db->getQuery();
         $loginresult = $this->db->execute();
         
         //log errors
         if(!$loginresult)
            trigger_error('problem while get user login detail ,query:'.$qur);
         
         
         if ($loginresult->num_rows > 0) {
         $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
         $blockUnblockStatus = $logindata['isBlocked'];
          }
          
          return array("blockUnblockStatus"=>$blockUnblockStatus,"tariffId"=>$tariffId,"currencyId"=>$currencyId,"tariffName"=>$tariffName);
                
                
    }    
   
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 05/09/2013
    #function use to search bulk user username and password 
    function searchBulkClient($parm){
        
        #get batch user detail from 91_manageClient table 
        $table = '91_manageClient';
        $condition = "userBatchId = '".$parm['batchId']."' and userName like '%".$parm['searchData']."%'";
        $this->db->select('*')->from($table)->where($condition);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log errors
        if(!$result)
            trigger_error('problem while get manage client detail ,query:'.$qur);
        
         if ($result->num_rows > 0) {
          while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
              $userdata['userId'] = $row['userId'];
              $userdata['balance']= $row['balance'];
              $userdata['status']= $row['status'];
              $userdata['userName'] = $row['userName'];
              $userdata['password'] = $row['password'];  
               
              $bulkUserData[] = $userdata;
              
          }
         
          }else
              $bulkUserData[] = array();
          
          
        
        return json_encode($bulkUserData);       
    }
    
    
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 16-09-2013
    #function use to change user status (block or unblock) with all chain user  OR set Delete Flag of user 
    # parm : type use to either isBlock or deleteFlag both are column name of userLogin table 
    function changeUserStatus($parm,$resellerId,$type){
        
                
        #check user permission for update status  
        
        $checkId = $this->getResellerId($parm['userId']); 
        if($checkId != $resellerId)
        {
            return json_encode(array("status" => "error", "msg" => "you don't have  permission to update status."));
        }
        
        #find chain id of given user 
        $userChainId = $this->getUserChainId($parm['userId']);
        if($userChainId == false || $userChainId == "" || $userChainId == NULL)
            return json_encode(array("status" => "error", "msg" => "you don't have  permission to update status."));
        
        #condition for all user id where status are eiter block or unblock
        $conditionStr = "userId IN (".$parm['userId'];
        
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
        
        
        #update user status (Block or unblock) if block then isblock column incremented by 1 otherwise decremented by 1 
        
        #check status is block or unblock
        if($parm['status'] == "block")
        {
            
            $sql = "UPDATE 91_userLogin SET ".$type."=".$type." + 1 WHERE ".$conditionStr; 
            $this->accountManagerLog($parm['userId'],3,"unBlock","block",$resellerId,"update user Status");
     
        }
        else
        {
            
            $sql = "UPDATE 91_userLogin SET ".$type."=".$type." - 1 WHERE ".$conditionStr; 
            $this->accountManagerLog($parm['userId'],3,"block","unBlock",$resellerId,"update user Status");
     
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
                    $this->accountManagerLog($parm['userId'],1,"exist","deleted",$resellerId,"user Deleted");
     
                }
                else
                {
                    $sql = "UPDATE 91_userLogin SET isBlocked = isBlocked - 1 WHERE ".$conditionStr; 
                    $this->accountManagerLog($parm['userId'],1,"deleted","exist",$resellerId,"restore user");
     
                    
                }
                    
                     $result = $this->db->query($sql);
                     
                      //log errors
                    if(!$result)
                        trigger_error('problem while change delete status status ,query:'.$sql);
                return json_encode(array("status" => "success", "msg" => "User Delete Flag Set successfully ."));
            }
            else
             return json_encode(array("status" => "success", "msg" => "User status update successfully ."));
        }
        else 
        {
            //log errors
            trigger_error('problem while change user status ,query:'.$sql); 
            return json_encode(array("status" => "", "msg" => "user status not update.","sql"=>$sql));
        }
        
    
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 03/09/2013
    #function use to search client by contact number 
    function searchByNumber($q){
        $useridArray = array();
        $table = '91_verifiedNumbers';
        $condition = "isDefault = 1 and CONCAT(countryCode,verifiedNumber) like '%".$q."%'";
        
        $useridArray = $this->getUserIdByCondition($table,$condition);
        
        return $useridArray;
         
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 03/09/2013
    #function use to search client by email id  
    function searchByEmail($q){
        
        $table = '91_verifiedEmails';
        $condition = "default_email = 1 and email like '%".$q."%'";
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
        if(preg_match('/[^a-zA-Z0-9\@\_\-\$\.]+/', $q))
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
        $page_number = $page_number;
        $first_limit = 0;
        
        
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
                
              $result =  $this->allUserDetail($newcondition); 
            }
            else
              $result = $this->loadUsers($q, $userid, $first_limit, $limit, 3,$newcondition);

            $jade["isSearchResult"] = "true";
            $jade["searchQuery"] = $q;
            
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
                $result =  $this->allUserDetail(); 
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
        
        
        
        
        
        #get all data of client 
        foreach ($result as $row) 
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
          
            #get user manager name and id  (call function of function_layer)
            extract($this->getadminId($id));
            $data["managerId"] = $managerId;
            $data["managerName"] = $managerName;
            
            
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
        
     return json_encode($jade);
    }
    
    
    # sub function of manageClients use to get all user detail 
    function allUserDetail($condition = null)
    {
    
         if($condition != null)
         {
            $allcondition = $condition;
         }
         else
            $allcondition = '';
        
        $table = '91_manageClient';
        $this->db->select('*')->from($table)->where('type != 4 and type !=7 and type !=1 '.$allcondition.' order by userId  desc limit 0,30');
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log error
        if(!$result)
            trigger_error('problem while get manageClient details,query:'.$qur);
        
        
        $resultArray = array();
        if ($result->num_rows > 0) 
        {

            while ($row= $result->fetch_array(MYSQL_ASSOC) ) 
            {
                $resultArray[]=$row;					
            }
        }
        return $resultArray;
       
    }
    
    function loadUsers($q,$user_id,$start_limit,$limit,$sort,$checkCondition = null)
    {
            
            $condition='';
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
                }
                else
                    $condition = $checkCondition;
                
                
                
		$sql="Select * from 91_manageClient where resellerId='".$user_id."'and type != 4 and type !=7 and type!= 1 $condition order by ".$order." limit ".$start_limit.",".$limit;
		
                $result = $this->db->query($sql);
                
                 //log error
                if(!$result)
                    trigger_error('problem while get manageClient details,query:'.$sql);
        
                
                $resultArray = array();
                if ($result->num_rows > 0) 
                {
				
                    while ($row= $result->fetch_array(MYSQL_ASSOC) ) 
                    {
                        $resultArray[]=$row;					
                    }
                }
                return $resultArray;
	}
    
    function loadUserDetails($user_id, $fields = '*', $resellerId) 
    {

        $sql = "select " . $fields . " from 91_manageClient where userId='" . $user_id . "' and resellerId=" . $resellerId;
        $result = $this->db->query($sql);
//                var_dump($result);

        if ($result->num_rows > 0) 
        {

            while ($row = $result->fetch_array(MYSQL_ASSOC)) 
            {
                return $row;
            }
        }
        
        if (!$result)
        {
            //log error
            trigger_error('problem while get manageClient details,query:'.$sql);
            return ("Unable To Fetch User Data");
        }
//		return $result;
    }

    function sendErrorMail($email, $mailData) 
    {
        require('awsSesMailClass.php');
        $sesObj = new awsSesMail();
        $from = "support@phone91.com";
        $subject = "Phone91 Error Report";
        $to = $email;
        $message = $mailData;
        $response = $sesObj->mailAwsSes($to, $subject, $message, $from);
    }
    
   /**
    * @author  sudhir pandey <sudhir@hostnsoft.com>
    * @since 26/10/2013
    * @description function use to edit general setting of user like account manager and password 
    * @param  $parm => accountManager and password 
    */ 
   function editGeneralSetting($param,$userId)
   {
      
     
      #check permission for add transaction or not 
      $resellerId = $this->getResellerId($param['clientId']);  
      
      if($resellerId != $userId)
      {
          return json_encode(array("status" => "error", "msg" => "you have no permission for edit general setting ."));
      }
       
 
      
        #get account manager name 
        $managerName = $this->getuserName($param['accountManager']);
      
        $managerTable = "91_accountManager";
        $this->db->select('*')->from($managerTable)->where("userId = '" . $param['clientId'] . "'");
        $sql = $this->db->getQuery();
        
        $result = $this->db->execute();
        
        if(!$result)
            trigger_error('problem while get account manager details,query:'.$sql);
        
        if ($result->num_rows > 0) 
        {
            
            $data=array("managerId"=>$param['accountManager'],"managerName"=>$managerName); 
            $condition = "userId=".$param['clientId'] ." ";
            $this->db->update($managerTable, $data)->where($condition);	
            $sql = $this->db->getQuery();
            $results = $this->db->execute();
            
            
        }
        else
        {
            
            $data = array("userId" => $param['clientId'],"managerId" => $param['accountManager'],"managerName"=>$managerName);
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
    * @since 31/10/2013
    * @description function use to change user to reseller 
    */
   function changeUserToReseller($userId,$resellerId)
   {
       
      #check permission for change user to reseller
      $UserReseller = $this->getResellerId($userId);  
      
      if($UserReseller != $resellerId)
      {
          return json_encode(array("status" => "error", "msg" => "you have no permission for change user to reseller  ."));
      } 
       
       
      $table = '91_userLogin';
       
      #check given user are already reseller 
      $this->db->select('type')->from($table)->where("userId='". $userId ."' and type = 2" );
      $sql = $this->db->getQuery();
      $result = $this->db->execute();
      
       if(!$result)
            trigger_error('problem while get user login details,query:'.$sql);
      
      if ($result->num_rows > 0) 
      {
            return json_encode(array("status" => "error", "msg" => "sorry given user are already reseller!"));
      }
       
       $data = array("type"=>2);
       $condition = "userId=" . $userId . " ";
       $this->db->update($table, $data)->where($condition);
       $results = $this->db->execute();
       
       $this->accountManagerLog($userId,5,"User","Reseller",$resellerId,"update User to Reseller");
        
       if($results)
       {
           return json_encode(array("status" => "success", "msg" => "successfully User change to Reseller ."));
       }
       else
       {
           //log error
            trigger_error('problem while get user login details,condition:'.$condition);
            return json_encode(array("status" => "error", "msg" => "error in change user to reseller ."));
       }
   }
    
    
    
}

//end of class
?>