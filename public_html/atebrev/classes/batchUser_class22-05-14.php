<?php

/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 14 may 2014
 * @package Phone91
 * @details class use for batch user function 
 */

include dirname(dirname(__FILE__)).'/config.php';

class batchUser_class extends fun //validation_class.php
{
    
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 14/05/2014
    #function use to create bulk client     
    function addNewClientBatch($request,$resellerId){

        #check username is blank or not
        if ($request['batchName'] == '' || $request['batchName'] == NULL) {
            return json_encode(array("status" => "error", "msg" => "Please insert Batch name ."));
        }
        
        #check valid batch name 
        if(!preg_match('/^[a-zA-Z_@-\s]+$/',$request['batchName']))
        {
          return json_encode(array('status'=>'error','msg'=>'Please Enter Valid batch name'));
        }
        else if(strlen($request['batchName']) < 5 || $request['batchName'] > 30)
        {
          return json_encode(array('status'=>'error','msg'=>'Please Enter Valid length for batch name Min:5,Max:30!!!'));
        }

        #check total no of client is valid or not 
        if (!preg_match("/^[0-9]{1,4}$/", $request['totalClients'])) {
            return json_encode(array("status" => "error", "msg" => "Total Number of client are not valid!"));
        }

        #check tariff paln is selected or not 
        if ($request['tariff'] == "" || strtolower($request['tariff'])== 'select') {
            return json_encode(array("status" => "error", "msg" => "Please Select Tariff Plan ! "));
        }
        
        #check total no of client is valid or not 
        if (!preg_match("/^[0-9]+$/", $request['tariff'])) {
            return json_encode(array("status" => "error", "msg" =>"Please Select Tariff Plan ! "));
        }

        
        #chech payment type is selected or not 
        if (strtolower($request['payTypeBulk']) == "select") {
            return json_encode(array("status" => "error", "msg" => "Please Select Payment Mode ! "));
        }
        if(!preg_match('/^[a-zA-Z_@-\s]+$/',$request['payTypeBulk']))
        {
          return json_encode(array('status'=>'error','msg'=>'Please select Valid payment Mode'));
        }
        
        
        #chech payment type is selected or not 
        if (strtotime($request['batchExpiry']) < strtotime(date("Y-m-d H:i:s"))) {
            return json_encode(array("status" => "error", "msg" => "Expiry Date Must be correct "));
        }

        #check total no of pins is numeric or not 
        if (!preg_match("/^[0-9]{1,4}$/", $request['balance'])) {
            return json_encode(array("status" => "error", "msg" => "4 digits Numeric value required in balance field ! "));
        }
        
        #check recharge amount value
         if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $request['rechargeAmt'])) {
            return json_encode(array("status" => "error", "msg" => "please enter valid recharge amount! "));
         }
         
         if (preg_match("/[^0-9]+/", $request['fundCurrency']) || strlen($request['fundCurrency']) < 1 ) {
            return json_encode(array("status" => "error", "msg" => "recharge amount currency is not valid ! "));
         }
         
         if (strtolower($request['payType']) == "select") {
            return json_encode(array("status" => "error", "msg" => "Please Select payment type !"));
        }
        
        #check partial currency and partial amount 
         if (strtolower($request['payType']) == "partial") {
            if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $request['partialAmt'])) {
                return json_encode(array("status" => "error", "msg" => "please enter valid partial amount! "));
             }
             
            if (preg_match("/[^0-9]+/", $request['partialCurrency']) || strlen($request['partialCurrency']) < 1 ) {
                return json_encode(array("status" => "error", "msg" => "partial currency is not valid ! "));
            }
             
         }
         
        $totalClient = $request['totalClients']; 
         
        #payment mode (cash,memo,bank).
        if(isset($request['payTypeBulk']) && $request['payTypeBulk'] == "Other"){
            if(!preg_match('/^[a-zA-Z_@-\s]+$/',$request['otherType']))
            {
            return json_encode(array('status'=>'error','msg'=>'Please select Valid other payment Mode'));
            }
            $paymentMode = $this->db->real_escape_string($request['otherType']);     
        }else
            $paymentMode = $request['payTypeBulk'];
        
        $bulkUserTable = '91_bulkUser';
        
        $this->db->select('*')->from($bulkUserTable)->where("batchName = '" . $request['batchName'] . "'");
        $this->db->getQuery();
        $result = $this->db->execute();
        if ($result->num_rows > 0){
            return json_encode(array("status"=>"error","msg"=>"Batch name already registered!"));
        }
        

        $data = array("batchName" => $request['batchName'],"numberOfClients"=>(int)$request["totalClients"],"expiryDate"=>$request["batchExpiry"],"userId"=>$resellerId,"batchBalance" =>$request['balance'],"listenTime" => 1);

        #insert query (insert data into 91_bulkUser table )
        $this->db->insert($bulkUserTable, $data);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        //var_dump($result);
        #check data inserted or not 
        if (!$result) {
            $this->sendErrorMail("sudhir@hostnsoft.com", "$bulkUserTable insert query fail : $qur ");
            return json_encode(array("status" => "error", "msg" => "add Batch process fail! please try again"));
        }
        $batchId = $this->db->insert_id;

                
        
        #get reseller block unblock status from login table 
        $table = '91_userLogin';
        $condition = "userId = '".$resellerId."'";
        $this->db->select('*')->from($table)->where($condition);
        $this->db->getQuery();
        $loginresult = $this->db->execute();
        if ($loginresult->num_rows > 0) {
        $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
        $blockUnblockStatus = $logindata['isBlocked'];
        $deleteFlag = $logindata['deleteFlag'];
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
                 
        include_once(CLASS_DIR."transaction_class.php");
        $transaction_obj = new transaction_class();    
            
        for($request['totalClients'];$request['totalClients']>0;$request['totalClients']--){
            $userName= $this->createUsername($batchId);
            $password= $this->createUsername($batchId);  
            
                $lastchainId = 0 ;
                
                #get last chain id from user balance table  
                $lastchainId = $this->getlastChainId($resellerId);
            
                if(!$lastchainId || $lastchainId == "" )
                    return json_encode (array("msg"=>"Internal sever error cant create user please try again 505","status"=>"error"));

                #new chain id (incremented id of lastchain id )
                $chainId = $this->newChainId($lastchainId);
            
                #insert userdetail into database 
                $personalTable = '91_personalInfo';
                $data = array("name" => $userName,"date"=>date('Y-m-d H:i:s'));

                #insert query (insert data into 91_personalInfo table )
                $this->db->insert($personalTable, $data);
                $qur = $this->db->getQuery();
                $result = $this->db->execute();
                //var_dump($result);
                #check data inserted or not 
                if (!$result) {
                    trigger_error('add user process fail!:'.$qur);
                    $this->sendErrorMail("Ankitpatidar@hostnsoft.com", "insert query fail : $qur ");
                    return json_encode(array("status" => "", "msg" => "add user process fail!"));
                }

                #user id 
                $userid = $this->db->insert_id;

                
        

                #insert login detail into login table database 
                $loginTable = '91_userLogin';
                $data = array("userId" => (int) $userid, "userName" => $userName, "password" => $password, "isBlocked" => $blockUnblockStatus,"deleteFlag"=>$deleteFlag, "type" => 4,"beforeLoginFlag"=>2);

                #insert query (insert data into 91_userLogin table )
                $this->db->insert($loginTable, $data);
                $qur = $this->db->getQuery();
                $result = $this->db->execute();
                //var_dump($result);
                #check data inserted or not 
                if (!$result) {
                  trigger_error('add user process fail!:'.$qur);
                    $this->deleteData($personalTable, "userId = ".$userid);
                    return json_encode(array("status" => "error", "msg" => "add user process fail !"));
                }



                #user balance from plan table  
                $balance = $request['balance'];
                #currency id 
                $currency_id = $this->getOutputCurrency($request['tariff']);
                #call limit 
                $call_limit = 2;
                
                
                                 
                #insert login detail into 91_userBalance table database 
                $loginTable = '91_userBalance';
                $data = array("userId" => (int) $userid,"chainId"=>$chainId , "tariffId" => (int) $request['tariff'], "balance" => $balance, "currencyId" => (int) $currency_id, "callLimit" => (int) $call_limit, "resellerId" => (int) $resellerId, "userBatchId"=>(int)$batchId ,"routeId"=>$userRoutDetail['routeId'],"isDialPlan"=>$userRoutDetail['isDialPlan'],"getMinuteVoice" => 1);
                #insert query (insert data into 91_userLogin table )

                trigger_error('user balance detail!:'.json_encode($data));
                $this->db->insert($loginTable, $data);
                $tempsql = $this->db->getQuery();
                $result = $this->db->execute();
                //var_dump($result);
                if (!$result) {
                  trigger_error('add user process fail!:'.$tempsql);
                    $this->deleteData($personalTable, "userId = ".$userid);
                    $this->deleteData($loginTable, "userId = ".$userid);
                    return json_encode(array("status" => "error", "msg" => "add user process fail!"));
                }
                 
          
        
                #enable user sip id
                $sipMsg = $this->enableSip($userid,1);
                $resultData = json_decode($sipMsg, TRUE);
                if($resultData['status'] != "success"){
                    trigger_error('user sip not enable in batch client ');
                }

                
               
        }
        
        $talktime = ($request['balance'] * $totalClient);
       
        if (strtolower($request['payType']) == "partial") {
           $this->batchUserTransaction($resellerId,$batchId,$talktime,$paymentMode,$request['rechargeAmt'],$request['fundCurrency'],$request['payType'],$request['partialCurrency'],$request['partialAmt']);    
         }else
           $this->batchUserTransaction($resellerId,$batchId,$talktime,$paymentMode,$request['rechargeAmt'],$request['fundCurrency'],$request['payType']);
        
       
              
        include_once(CLASS_DIR . "reseller_class.php");
        $res_obj=new reseller_class();
        $bulkuser = $res_obj->bulkUserBatch($_SESSION['userid']);
        $bulkData = json_decode($bulkuser, true); 
        if($result){
            return json_encode(array("status" => "success", "msg" => "successfully add bulk user","batchDetail"=>$bulkData['batchDetail']));
        }
}


/**
 * @author sudhir pandey <sudhir@hostnsoft.com>
 * @param int $fromUser  user who update batch transaction 
 * @param int $batchId  user batch id 
 * @param float $talktime balance who assign to all user 
 * @param string $paymentMode (cash,memo,bank)
 * @param float $receiveAmt  receiving amount 
 * @param number $currency  receiving amuount currency 
 * @param string $paymentType (prepaid, partial, postpaid).
 * @param number $partialCurrency 
 * @param float $partialAmt 
 */
function batchUserTransaction($fromUser,$batchId,$talktime,$paymentMode,$receiveAmt,$currency,$paymentType,$partialCurrency = 0,$partialAmt = 0){
         
        #value assign to debit and credit 
        $debit = $receiveAmt;
        $credit = 0;
        $closingBalance = $this->batchCurrencyCnvt($batchId,$currency,$receiveAmt);
        $description = "New Batch Created";
    
        #- Add transaction in case of voip91(payment type).
        $result = $this->batchTrans_sub($fromUser,$batchId,$talktime,"voip",$debit,$credit,$closingBalance,$description,$currency);    
        
       
        
        #- If type is prepaid (advance) 
        if($paymentType == "prepaid")
        {
            $closingBalance = 0;  
            $debit = 0;  $credit = $receiveAmt;
            #- Add transaction with given payment type (cash,memo,bank or other).
            $closingBalanceResult = $this->batchTrans_sub($fromUser,$batchId,0,$paymentMode,$debit,$credit,$closingBalance,$description,$currency);       
        }
        else if($paymentType == "partial")  #- If type is partial
        {
            $partialbal = $this->batchCurrencyCnvt($batchId,$partialCurrency,$partialAmt);
            $closingBalance = ((int)$closingBalance - (int)$partialbal);
            
            #- Add  partial transaction with given payment type (cash,memo,bank or other).
            $closingBalanceResult = $this->batchTrans_sub($fromUser,$batchId,0,$paymentMode,0,$partialAmt,$closingBalance,$description,$partialCurrency);
        }
        
        #- Update closing balance of user 
        $updateClosing = $this->updateBatchClosingBalance($batchId,$closingBalance);
        
        
}

/**
 * @author sudhir pandey <sudhir@hostnsoft.com>
 * @since 15-05-14
 * @desc function use to update transaction log of batch  
 */

function batchTrans_sub($fromUser,$batchId,$talktime,$paymentMode,$debit,$credit,$closingBalance,$description,$currency)
    {  
        
        
        #- Converting current debit and credit  amount To USers Base Currency.
        $debitCntAmt = $this->batchCurrencyCnvt($batchId,$currency,$debit);
        
        $creditCntAmt = $this->batchCurrencyCnvt($batchId,$currency,$credit);
         
                
        //$this->newClosingAmount = ((int)$debitCntAmt + (int)$creditCntAmt); 
        
        $paymentType = $this->db->real_escape_string($paymentMode);
        $description = $this->db->real_escape_string($description);
       
        
        #- Insert query (insert data into 91_tempEmails table )
        $data = array( "fromUser" => (int)$fromUser, 
                        "batchId" => $batchId, 
                        "date" => date('Y-m-d H:i:s'), 
                        "talktime" => $talktime, 
                        "credit" => $credit,
                        "debit" => $debit, 
                        "paymentType" => $paymentType, 
                        "closingBalance" => $closingBalance, 
                        "description" => $description, 
                        "currency" => $currency, 
                        "debitConverted" => $debitCntAmt, 
                        "creditConverted" => $creditCntAmt
                        ); 
        
        
        
        #- Add taransaction detail into taransation log table 
        $transactionlog = "91_batchTransactionLog";
        
        $res = $this->db->insert($transactionlog, $data);	
        $qur = $this->db->getQuery();
        
        $savedata = $this->db->execute();
        
        if(!$savedata)
        {
            trigger_error('problem while insert data in trasaction_log data:'.$qur);
            $this->sendErrorMail("sudhir@hostnsoft.com","insert query fail : $qur ");
            return 0;
        }
        return 1;
        
    }
    
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param int $batchId
     * @param int $currency
     * @param int $amount
     * @desc function use to convert amount into batch currency 
     */
    function batchCurrencyCnvt($batchId,$currency,$amount)
    {
        if( $amount == 0 )
             return 0;
        
        #- Getting currency Id.
        $batchDetail = $this->getBatchInternalDetail($batchId);
        
               
        if($currency != $batchDetail['currencyId'])
        {
            $paymentCurrency = $this->getCurrencyViaApc($currency, 1);
            $userCurrency = $this->getCurrencyViaApc( $batchDetail['currencyId'] , 1 );
            
            $debitCntAmt = $this->currencyConvert($paymentCurrency, $userCurrency, $amount); 
            
        }
        else
        {
            $debitCntAmt = $amount;
        }
        
        return $debitCntAmt;
    }
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 15-05-14
     * @desc get batch detail like tariff id and currency who related to batch user .
     * */
    function getBatchInternalDetail($batchId){
        
        #check total no of client is valid or not 
        if (!preg_match("/^[0-9]+$/", $batchId)) {
            return array();
        }
        #- Insert userdetail into database 
        $table = '91_manageClient';
        
        #- Condition For finding user detail
        $condition = "userBatchId = '" . $batchId . "' limit 1";

        $result = $this->selectData( '*', $table, $condition );

        if($result->num_rows > 0)  #- log error
        {
            $row = $result->fetch_array(MYSQL_ASSOC);
            isset($row['name'])? $name = $row['name'] : $name ='';
            isset($row['userName'])? $userName = $row['userName'] : $userName ='';
            isset($row['currencyId'])? $currencyId = $row['currencyId'] : $currencyId ='';
            isset($row['resellerId'])? $resellerId = $row['resellerId'] : $resellerId ='';
            isset($row['deleteFlag'])? $deleteFlag = $row['deleteFlag'] : $deleteFlag ='';
            
            return array( "name" => $name, "userName" => $userName , "currencyId" => $currencyId,"resellerId" =>$resellerId,"deleteFlag" =>$deleteFlag);
        }
        else
        {
          trigger_error('Problen while get details for manage client,condition:'.$condition);  
          return array();
        }
    
    }
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param int $batchId
     * @param float $amount
     * @desc function use to update batch closing balance 
     */
    function updateBatchClosingBalance($batchId,$amount)
    {
        
        #get user currency id and reseller id 
        $userDetail = $this->getBatchInternalDetail($batchid);
        
        $currencyId = $userDetail['currencyId'];
        $resellerid = $userDetail['resellerId'];
        
        #add taransaction detail into taransation log table 
        include_once(CLASS_DIR."transaction_class.php");
        
        $transaction_obj = new transaction_class();
        #get reseller converted amount (reseller get amount in his currency)
        $resellerCurrAmt = $transaction_obj->closingBalCurrencyCnvt($resellerid,$currencyId,$amount);
        
        #- Getting entry of this user in table exists or not.
        $batchExists = $this->getBatchClosingBalance($batchId,1);
        
        $table = '91_batchClosingBalance';
        
        #- If user closing balance present then update closing balance otherwise add closing balance into table
        if ($batchExists > 0) 
        {	    
            #update closing amount of user 
            $data = array("closingBalance" => $amount , "lastUpdate" => date('Y-m-d H:i:s') , "resellerCurrAmt" =>$resellerCurrAmt);   
            $condition = "batchId =". $batchId;
            $this->db->update( $table, $data )->where($condition);
        }
        else
        {
            #- Insert closing amount of user
            $data = array( "batchId" => (int)$batchId , "closingBalance" => $amount , "lastUpdate" => date('Y-m-d H:i:s'), "resellerCurrAmt" =>$resellerCurrAmt );
            $this->db->insert( $table, $data );
        }
        
        $query = $this->db->getQuery();
        $result = $this->db->execute();   
        
        if(!$result)
        {
            trigger_error('problem while get closing balance detail ,data:'.json_encode($data));
        }
    }
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param int $batchId
     * @param int $type
     * @desc function use to get batch closing balance 
     */
    function getBatchClosingBalance($batchId,$type = NULL)
    {
        #- Table name 
        $table = '91_batchClosingBalance';
        
        #- Condition to Find Closing Balance
        $condition = "batchId = '" . $batchId . "'";

        #- Function To Fetch Records( This Is Common function. currently fetching closingAmount )
        $result =  $this->selectData('closingBalance', $table, $condition );
        
        if(!$result)
            trigger_error('problem while get batch closing balance detail ,condition:'.$condition);
        
        #- Getting closingAmount
        if( $result->num_rows > 0 ) 
        {	
            if($type == 1)
                $balanceResponse = $result->num_rows ; #- returnin number of rows in some cases.
            else
            {
                while($row = $result->fetch_array(MYSQL_ASSOC) ) 
                {
                    $balanceResponse = $row["closingBalance"];
                }
            }
        }
        else #- No Records Found
        {
            $balanceResponse = 0;
        }

        return $balanceResponse;
    }

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 16-05-14
     * @desc function use to get batch transaction log detail . 
     */
    function getBatchTransaction($param,$userId){
        
       
      #- Get user currency id 
      $userDetail = $this->getBatchInternalDetail($param['batchId']);
            
      #- Get currency name 
      $currencyName = $this->getCurrencyViaApc( $userDetail['currencyId'] , 1 );  
        
      #- Table name   
      $table = '91_batchTransactionLog';
      
      $condition = "fromUser = '" .$userId. "' and batchId = '".$param['batchId']."'";
           
      $this->db->select('*')->from($table)->where($condition);
      $SQL= $this->db->getQuery();
      $result = $this->db->execute();
      if(!$result)
            trigger_error('Problem while get details from transaction log,condition:'.$condition);
      
      #- Check data total no of row is greater then 0 or not 
      if ($result->num_rows > 0)
      {
          while ($row = $result->fetch_array(MYSQL_ASSOC) ) 
          { 
                           
              $data['fromUser'] = $row['fromUser'];
              $data['batchId'] = $row['batchId'];
              $data['date'] = $row['date'];
              $data['talktime'] =  round($row['talktime'],2);
              $data['credit'] =  round($row['credit'],2);
              $data['debit'] =  round($row['debit'],2);
              $data['paymentType'] = $row['paymentType'];
              $data['closingBalance'] =  round($row['closingBalance'],2);
              $data['description'] = $row['description']; 
              $data['currency'] = $row['currency']; 
              
              $currencyViaApc = $this->getCurrencyViaApc($data['currency'] , 1);
              
              if($currencyViaApc == '' || $currencyViaApc == null)
              {
                 $data['currencyName'] = $currencyName;
              }
              else
                $data['currencyName'] = $currencyViaApc;
              
              if($currencyName == $data['currencyName'])
              {
                $data['creditActualCurrency'] = $data['credit']; 
                $data['debitActualCurrency'] =  $data['debit'];
              }
              else
              {
                $data['creditActualCurrency'] = round($row['creditConverted'],2);
                $data['debitActualCurrency'] = round($row['debitConverted'],2);
              }
              
              $transactionData['detail'][] = $data;
	}
      }
      else
      {
          $transactionData = array();
      }
      
//      //get total count
//      $this->db->select('*')->from($table)->where($condition);
//      $this->db->getQuery();
//      $resultCnt = $this->db->execute();
//      if(!$resultCnt)
//          trigger_error('problem while get total count in pagination!!! SQL:'.$this->db->getQuery());
//      $count = $resultCnt->num_rows;
//      $transactionData['totalCount'] = ceil($count/$limit);
      
      return json_encode($transactionData);
        
    }
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 16-05-2014
     * @details :: function use for add and reduce transaction log into transaction table.
     * @date  18/12/2013
     * @return : 
     */
    
    function addReduceBatchTransaction($parm,$userid,$type)
    {
        #- calling function To check validation
        $validateRes = $this->checkTransactionValidation($parm);
        
         if($validateRes != 1)
            return $validateRes;

        #- Checking permission for add transaction or not 
        $batchInfo = $this->getBatchInternalDetail($parm['batchId']);  
        $resellerId = $batchInfo['resellerId'];
        
        if($resellerId != $userid)
        {
          return json_encode(array("status" => "error", "msg" => "you have no permission for add transaction ."));
        }
        
        $paymentType = $parm['transType'];

        if($parm['transType'] == "Other")
        {
            $paymentType = $parm['transTypeOther'];
        }
        
        
        #- Find closing amount form 91_closingAmount table
        $getBalance = $this->getBatchClosingBalance($parm['batchId']);
       
        $clsamount = $this->batchCurrencyCnvt($parm['batchId'],$parm['currency'],$parm['amount']);
          
       
        #- Calculating closing balance
        #- Check for amount add or reduce in transaction 
        if($parm['status'] == "add")
        {
            $debit = 0;
            $credit = $parm['amount'];
            $closingBalance = ((double)$getBalance - (double)$clsamount);
        }
        else
        {
            $debit = $parm['amount'];
            $credit = 0;      
            $closingBalance = ((double)$getBalance + (double)$clsamount);
        }
        
        
        $result = $this->batchTrans_sub($userid,$parm['batchId'],0,$paymentType,$debit,$credit,$closingBalance,$parm['description'],$parm['currency']);    
       
        $updateClosing = $this->updateBatchClosingBalance($parm['batchId'],$closingBalance);
        
        if($result)
        {
            $transData = $this->getBatchTransaction($parm,$userid);
            
            $str = json_decode($transData,TRUE);
            return json_encode(array("status"=>"success","msg"=>"Successfully Transaction Updated !","str"=>$str));   
        }
    }
    
    
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 16-05-2014
     * @desc function use to check transaction log all parameter validate or not
     * @return int 0 or 1
     */
     function checkTransactionValidation($parm)
    {
        #- Checking for valid transaction type 
        if(isset($parm['transType']) && (preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $parm['transType']) || strlen(trim($parm['transType'])) < 1 || strlen(trim($parm['transType'])) > 55))
        {
           return json_encode(array("status"=>"error","msg"=>"please enter a valid Transaction Type must not containg any spacial character other than '@','_','-'"));
        }

        #- Checking for valid description 
        if(isset($parm['description']) && (preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $parm['description']) || strlen(trim($parm['description'])) < 1 || strlen(trim($parm['description'])) > 200))
        {
           return json_encode(array("status"=>"error","msg"=>"please enter a valid Description must not containg any spacial character other than '@','_','-'"));
        }

        #- Checking for valid amount 
        if(isset($parm['amount']) && (!preg_match('/^[0-9]+(\.[0-9]{1,4})?$/', $parm['amount'])))
        {
           return json_encode(array("status"=>"error","msg"=>"please enter a valid amount !"));
        }
        
        return 1;
        
    }
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param array $param:  batchname and expiry date 
     * @param int $userId
     * @param int $type
     * @since 17-05-2014
     * @desc function use to update batch detail 
     */
    function editBatchDetail($param,$userId,$type){
         
            $table='91_bulkUser';
            
            if (!preg_match("/^[a-zA-Z_@-]{2,20}$/",$param['batchName']))
            {  
                   return json_encode(array("status"=>"error","msg"=>"Please Enter valide batch name!"));
            }
            
            if(strtotime(date('Y-m-d H:i:s',strtotime($param['batchExpiry']))) < strtotime(date('Y-m-d H:i:s'))){
                return json_encode(array("status"=>"error","msg"=>"Please Select valid expiry date!"));
            }
            
            if(!preg_match("/^[0-9]+/",$param['batchId']) || $param['batchId'] == "")
                return json_encode (array("msg"=>"Invalid batch id","status"=>"error"));
            
            #- Checking permission for add transaction or not 
            $batchInfo = $this->getBatchInternalDetail($param['batchId']);  
            $resellerId = $batchInfo['resellerId'];

            if($resellerId != $userId)
            {
            return json_encode(array("status" => "error", "msg" => "you have no permission edit batch detail ."));
            }
            
//            if(isset($listenRemainingTime) && $listenRemainingTime =='on'){
//                $listenTime = 1;
//            }else
//                $listenTime = 0;
            
            #value for store in database 
            $data=array("batchName"=>$param['batchName'],"expiryDate"=>date('Y-m-d H:i:s',strtotime($param['batchExpiry']))); 
            $condition = "batchId=".$param['batchId']." ";
            $this->db->update($table, $data)->where($condition);	
            $qur = $this->db->getQuery();
            $result = $this->db->execute();

                   
            //log errors
            if(!$result || $this->db->affected_rows == 0){
                  trigger_error('problem while edit batch detail,query:'.$qur);
                     return json_encode(array("status"=>"error","msg"=>"batch not updated !"));
            }
            
            include_once(CLASS_DIR . "reseller_class.php");
            $res_obj=new reseller_class();
            $bulkuser = $res_obj->bulkUserBatch($_SESSION['userid']);
            $bulkData = json_decode($bulkuser, true); 
            
             return json_encode(array("status"=>"success","msg"=>"Batch Updated Successfully !","batchDetail"=>$bulkData['batchDetail']));
       
    }

}//end of class
$batchUser_obj	= new batchUser_class();//class object
?>