<?php
/**
 * @Author sudhir pandey <sudhir@hostnsoft.com>
 * @createdDate 31-07-2013
 * 
 */
include dirname(dirname(__FILE__)).'/config.php';
class transaction_class extends fun
{
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 31-07-2013
    #function use for find closing balance of user/reseller
    function getClosingBalance($id){
        #table name 
        $table = '91_closingAmount';
        
        #find closing amount of user 
        $this->db->select('*')->from($table)->where("userId = '" . $id . "'");
        $this->db->getQuery();
        $result = $this->db->execute();
        
        #variable balance use for store closing amount data
        if ($result->num_rows > 0) {	
	 while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
	    $balance = $row["closingAmount"];
         }
        }else
            $balance = 0;
        
        return $balance;
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 31-07-2013
    #function use for find closing balance of user/reseller
    function updateClosingBalance($id,$amount){
        #table name 
        $table = '91_closingAmount';
        
        #find closing amount of user 
        $this->db->select('*')->from($table)->where("userId = '" . $id . "'");
        $this->db->getQuery();
        $result = $this->db->execute();
        
        #if user closing balance present then update closing balance otherwise add closing balance into table
        if ($result->num_rows > 0) {	
            
	 #update closing amount of user 
         $data=array("closingAmount"=>$amount,"lastUpdate"=>date('Y-m-d H:i:s')); 
         $condition = "userId=".$id." ";
         $this->db->update($table, $data)->where($condition);	
         $qur = $this->db->getQuery();
         $results = $this->db->execute();
         
         
        }else
         {
          #insert closing amount of user
          $data=array("userId"=>(int)$id, "closingAmount"=>$amount,"lastUpdate"=>date('Y-m-d H:i:s'));
          $this->db->insert($table, $data);
          $qur = $this->db->getQuery();
          $results = $this->db->execute();   
         }
        
         if(!$results){
             $this->sendErrorMail("sudhir@hostnsoft.com","insert query fail : $qur ");
         }
    
    }
    
    function updateUserBalance($toUser,$currentBalance){
        
         $table = "91_userBalance";
         #update balance amount of user 
         $data=array("balance"=>$currentBalance); 
         $condition = "userId=".$toUser." ";
         $this->db->update($table, $data)->where($condition);	
         $qur = $this->db->getQuery();
         $results = $this->db->execute();
         if(!$results){
            $this->sendErrorMail("sudhir@hostnsoft.com","update query fail : $qur");
         }
    }
    
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 01/07/2013
    #function use for add Transaction entry into transactionLog table 
    function addTransactional($fromUser,$toUser,$amount,$talktime,$paymentType,$description,$type,$partialAmt = 0){
        
        #check amount limit if amount is greaterthen 1000 then mail send to admin 
        if($amount > 1000){
          $this->sendErrorMail("sudhir@hostnsoft.com","amount is greater then 1000 rs in transaction log .");
        }
        
        
        
        //find closing amount form 91_closingAmount table
        $getBalance = $this->getClosingBalance($toUser);
       
        #calculate closing balance
        $closingBalance = ((int)$getBalance + (int)$amount);
        
        #get current balance form 91_userBalance table
        $currBalance = $this->getcurrentbalance($toUser);
        $currentBalance = ((int)$currBalance + (int)$talktime);
        
        #add transaction in case of voip91(payment type).
        $result = $this->addTransactional_sub($fromUser,$toUser,$talktime,$currentBalance,"voip91",$amount,0,$closingBalance,$description);

        
        
        # if type is prepaid (advance) 
        if($type == "prepaid"){

            $closingBalance = ((int)$closingBalance - (int)$amount);
            
            #add transaction with given payment type (cash,memo,bank or other).
            $result2 = $this->addTransactional_sub($fromUser,$toUser,$talktime,$currentBalance,$paymentType,0,$amount,$closingBalance,$description);
                    
        }
        
        
        #if type is partial
        if($type == "partial"){
            
            $closingBalance = ((int)$closingBalance - (int)$partialAmt);
            
            #add  partial transaction with given payment type (cash,memo,bank or other).
            $result2 = $this->addTransactional_sub($fromUser,$toUser,$talktime,$currentBalance,$paymentType,0,$partialAmt,$closingBalance,$description);
                        
        }
        
        #update closing balance of user 
        $result3 = $this->updateClosingBalance($toUser,$closingBalance);
        
//        #update current balance of user in userbalance table 
//        $this->updateUserBalance($toUser,$currentBalance);
               
        if($result == 1 || $result2 == 1){
            return 1;
        } 
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    function addTransactional_sub($fromUser,$toUser,$amount,$currentBalance,$paymentType,$debit,$credit,$closingBalance,$description){
        #add taransaction detail into taransation log table 
        $transactionlog = "91_transactionLog";  
        
        $paymentType = $this->db->real_escape_string($paymentType);
        
        $description = $this->db->real_escape_string($description);
        
        $data=array("fromUser"=>(int)$fromUser,"toUser"=>$toUser,"date"=>date('Y-m-d H:i:s'),"amount"=>$amount,"currentBalance"=>$currentBalance,"debit"=>$debit,"credit"=>$credit,"paymentType"=>$paymentType,"closingBalance"=>$closingBalance,"description"=>$description); 
        
        #insert query (insert data into 91_tempEmails table )
        $this->db->insert($transactionlog, $data);	
        $qur = $this->db->getQuery();
        $savedata = $this->db->execute();
        if(!$savedata){
         $this->sendErrorMail("sudhir@hostnsoft.com","insert query fail : $qur ");
        return 0;
        }else
        return 1;
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 02-08-2013
    #function use for get current balance form 91_userbalance table 
    function getcurrentbalance($id){
        
        #table name 
        $table = '91_userBalance';
        
        #find current balance of user 
        $this->db->select('*')->from($table)->where("userId = '" . $id . "'");
        $this->db->getQuery();
        $result = $this->db->execute();
        
        #variable balance use for store current balance data
        if ($result->num_rows > 0) {	
	 while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
	    $currentBalance = $row["balance"];
         }
        }else
            $currentBalance = 0;
        
        return $currentBalance;
        
    }
    
    function sendErrorMail($email,$mailData){
        require('awsSesMailClass.php');
        $sesObj = new awsSesMail();
        $from="error@phone91.com";
        $subject="Phone91 Error Report";
        $to=$email;
        $message=$mailData;
        $response= $sesObj->mailAwsSes($to, $subject, $message, $from);
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 02-08-2013
    #function use for get transaction log detail 
    function getTransactionLogDetail($fromUser,$toUser){
      
      #table name   
      $table = '91_transactionLog';
      
      #get data form transaction log table where form user and touser are given
      $this->db->select('*')->from($table)->where("fromUser = '" .$fromUser. "' and toUser = '".$toUser."'");
      $this->db->getQuery();
      $result = $this->db->execute();
      
      #check data total no of row is greater then 0 or not 
      if ($result->num_rows > 0){
          while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
		    
              #from user
              $data['fromUser'] = $row['fromUser'];
              $data['toUser'] = $row['toUser'];
              $data['date'] = $row['date'];
              $data['amount'] = $row['amount'];
              $data['currentBalance'] = $row['currentBalance'];
              $data['credit'] = $row['credit'];
              $data['debit'] = $row['debit'];
              $data['paymentType'] = $row['paymentType'];
              $data['closingBalance'] = $row['closingBalance'];
              $data['description'] = $row['description'];
                         
              
              $transactionData[] = $data;
	}
        
      }else
          $transactionData = array();
      
      
      return json_encode($transactionData);
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 02-08-2013
    #function use for get transaction log detail 
    function getPersonalTransaction($userid){
      
      #table name   
      $table = '91_transactionLog';
      #get data form transaction log table where form user and touser are given
      $this->db->select('*')->from($table)->where("toUser = '".$userid."'");
      $this->db->getQuery();
      $result = $this->db->execute();
      
      #check data total no of row is greater then 0 or not 
      if ($result->num_rows > 0){
          while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
		    
              #from user
              $data['fromUser'] = $row['fromUser'];
              $data['toUser'] = $row['toUser'];
              $data['date'] = $row['date'];
              $data['amount'] = $row['amount'];
              $data['currentBalance'] = $row['currentBalance'];
              $data['credit'] = $row['credit'];
              $data['debit'] = $row['debit'];
              $data['paymentType'] = $row['paymentType'];
              $data['closingBalance'] = $row['closingBalance'];
              $data['description'] = $row['description'];
              
              $transactionData[] = $data;
	}
      }else
          $transactionData = array();
      
      return json_encode($transactionData);
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 06/08/2013
    #function use for add and reduce transaction log into transaction table 
    function addReduceTransaction($parm,$userid){
       
        #check for valid transaction type 
        if(isset($parm['transType']) && (preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $parm['transType']) || strlen(trim($parm['transType'])) < 1 || strlen(trim($parm['transType'])) > 55))
        {
            return json_encode(array("status"=>"error","msg"=>"please enter a valid Transaction Type must not containg any spacial character other than '@','_','-'"));
        }
        
        #check for valid description 
        if(isset($parm['description']) && (preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $parm['description']) || strlen(trim($parm['description'])) < 1 || strlen(trim($parm['description'])) > 200))
        {
            return json_encode(array("status"=>"error","msg"=>"please enter a valid Description must not containg any spacial character other than '@','_','-'"));
        }
        
        #check for valid amount 
        if(isset($parm['amount']) && (!preg_match('/^[0-9]+/', $parm['amount'])))
        {
            return json_encode(array("status"=>"error","msg"=>"please enter a valid amount !"));
        }
        
        
      $funobj = new fun();
      
      #check permission for add transaction or not 
      $resellerId = $funobj->getResellerId($parm['toUser']);  
      
      if($resellerId != $userid){
          return json_encode(array("status" => "error", "msg" => "you have no permission for add transaction ."));
      }
        
        $toUser = $parm['toUser'];
        $fromUser = $userid;
        $amount =$parm['amount'];
        $paymentType = $parm['transType'];
        
        //find closing amount form 91_closingAmount table
        $getBalance = $this->getClosingBalance($toUser);
                      
        #get current balance form 91_userBalance table
        $currentBalance = $this->getcurrentbalance($toUser);
        //$currentBalance = ((int)$currBalance + (int)$amount);
        
        #check for amount add or reduce in transaction 
        if($parm['status'] == "add"){
            $debit = 0;
            $credit = $amount;
            $closingBalance = ((int)$getBalance - (int)$amount);
        }else
        {
            $debit = $amount;
            $credit = 0;      
            $closingBalance = ((int)$getBalance + (int)$amount);
        }
        
        $description = $parm['description'];
        
        
        
         #add  partial transaction with given payment type (cash,memo,bank or other).
         $result = $this->addTransactional_sub($fromUser,$toUser,0,$currentBalance,$paymentType,$debit,$credit,$closingBalance,$description);
         
         #update closing balance of user 
         $result3 = $this->updateClosingBalance($toUser,$closingBalance);
         
         if($result == 1){
             $transData = $this->getTransactionLogDetail($fromUser,$toUser);
             $str = json_decode($transData,TRUE);
             return json_encode(array("status"=>"success","msg"=>"Successfully Transaction Updated !","str"=>$str));   
         }
        
    }
    
//    function transactionTabledesign($fromUser,$toUser){
//        
//        $transaction = $this->getTransactionLogDetail($fromUser,$toUser);
//        $transData = json_decode($transaction,TRUE);
//        $str = '<table width="100%" border="0" cellspacing="0" cellpadding="0" id="clientTrstable" class="cmntbl alR boxsize">
//        	<thead>
//                <tr>
//                    <th style="text-align:left !important">Date</th>
//                    <th style="text-align:left !important">Type</th>
//                    <th>Amount</th>
//                    <th>Balance</th>
//                    <th>Description</th>
//                    <th>Debit</th>
//                    <th>Credit</th>
//                    <th>Closing Balance</th>
//                </tr>
//            </thead>
//            <tbody>';
//                $totalCredit=0;$totalDebit=0;$totalClosingBalance=0;
//                foreach($transData as $trans) {
//                    
//                $str.='<tr class="">
//                    <td style="text-align:left !important">'.$trans['date'].'</td>
//                    <td style="text-align:left !important">'.$trans['paymentType'].'</td>
//                    <td>'.$trans['amount'].'</td>
//                    <td>'.$trans['currentBalance'].'</td>
//                    <td style="text-align:left !important">'.$trans['description'].'</td>
//                    <td><span class="debit">'.$trans['debit'].'</span></td>
//                    <td>'.$trans['credit'].'</td>
//                    <td>'.$trans['closingBalance'].'</td>
//                </tr>';
//                
//                $totalCredit = $totalCredit + $trans['credit'];
//                $totalDebit = $totalDebit + $trans['debit'];
//                $totalClosingBalance = $trans['closingBalance'];
//                
//                } 
//                $str.='<tr class="zerobal">
//                    <td colspan="100%"></td>
//                </tr>
//                <tr class="">
//                    <td style="text-align:left !important">&nbsp;</td>
//                    <td style="text-align:left !important"></td>
//                    <td></td>
//                    <td></td>
//                    <td style="text-align:left !important"></td>
//                    <td><span class="debit">'.$totalDebit.'</span></td>
//                    <td>'.$totalCredit.'</td>
//                    <td>'.$totalClosingBalance.'</td>
//                </tr>   
//                </tbody>
//            </table>';
//        
//                return $str;
//        
//    }
    
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 28/08/2013
    #function use for get reseller transaction log with user name 
    function getResellerTransaction($userid){
        
      #table name   
      $table = '91_transactionLog';
      #get data form transaction log table where form user and touser are given
      $this->db->select('*')->from($table)->where("fromUser = '" .$userid. "'");
      $this->db->getQuery();
      $result = $this->db->execute();
      
      #check data total no of row is greater then 0 or not 
      if ($result->num_rows > 0){
          while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
		    
              #from user
              $data['fromUser'] = $row['fromUser'];
              $data['toUser'] = $row['toUser'];
              $toUser = $row['toUser'];
              
              #function call for get name and userName of user form manageClient table  
              extract($this->getNameAndUserName($toUser)); // name and userName 
              $data['name'] = $name;
              $data['userName'] = $userName;
              
              $data['date'] = $row['date'];
              $data['amount'] = $row['amount'];
              $data['currentBalance'] = $row['currentBalance'];
              $data['credit'] = $row['credit'];
              $data['debit'] = $row['debit'];
              $data['paymentType'] = $row['paymentType'];
              $data['closingBalance'] = $row['closingBalance'];
              $data['description'] = $row['description'];
              
              $transactionData[] = $data;
	}
      }else
          $transactionData = array();
      
      return json_encode($transactionData);
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 28/08/2013
    #function use for get reseller transaction log with user name 
    function getNameAndUserName($toUser){
      
      #insert userdetail into database 
      $manageClient = '91_manageClient';
      $this->db->select('*')->from($manageClient)->where("userId = '" . $toUser . "'");
      $this->db->getQuery();  
      $result = $this->db->execute();
      $row = $result->fetch_array(MYSQL_ASSOC);
      isset($row['name'])? $name = $row['name'] : $name ='';
      isset($row['userName'])? $userName = $row['userName'] : $userName ='';
      return array("name"=>$name,"userName"=>$userName);
    }
    

}//end of class
?>