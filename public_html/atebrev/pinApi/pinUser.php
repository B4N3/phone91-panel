<?php
/*
 * @author sudhir pandey <sudhir@hostnsoft.com>
 * @package Phone91
 * @description create pin user , if recharge skype and gtalk by pin then new user create and use pin balance  
 */
include_once '../function_layer.php';


if(isset($_REQUEST['type']) && isset($_REQUEST['emailId']) && isset($_REQUEST['pin']) && isset($_REQUEST['pin'])){
    
#account type : 1 for gtalk and 2 for skype 
$accountType = $_REQUEST['type'];

#emailid : for skype or gtalk
$emailId  = $_REQUEST['emailId'];

#pin
$pin = $_REQUEST['pin'];

#check given authentication key is valid or not
if($_REQUEST['authKey'] == "654d55ds2d5e87fd2s58w6"){
    echo $msg = createPinUser($accountType,$emailId,$pin);
}else
    echo $msg = "You have no permission for use this API.";
}else
echo "Please provide valid accountType, emailid, pin and AuthKey";    



function createPinUser($accountType,$emailId,$pin){
    
    $funobj = new fun();
    
    #check emailid is velide or not 
    if(preg_match("/[^a-zA-Z0-9\.\_\@\-\$]+/", $emailId)){
         return "emailId is not valid";
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
    $result = $funobj->selectData('*', $tableName, "email ='".$emailId."'");

    
        
    #get pin detail 
    $pinDetail = getPinDetail($pin);
    $pinDataDetail = json_decode($pinDetail,TRUE);  //$pinGenerator,$pinTariff,$pinCurrency,$pinBalance   
        
    if($pinDataDetail['status'] == "error"){
        return $pinDataDetail['msg'];
    }
    
    
    #check the resulting value exists or not 
    if($result->num_rows > 0)
    {
        $row = $result->fetch_array(MYSQL_ASSOC);
        $userid = $row['userId'];
        $userTariff = getUserTariff($userid);
        return rechargeByPin($userid,$userTariff,$pinDataDetail['pinTariff'],$pinDataDetail['batchId'],$pin,$pinDataDetail['pinBalance']);
    }
    
    $userData = createUser($pinDataDetail['pinGenerator'],$pinDataDetail['pinTariff'],$pinDataDetail['pinCurrency'],$pinDataDetail['pinBalance']); 
    $userDetail = json_decode($userData,TRUE);  
    
    if($userDetail['status'] == "error"){
        return $userDetail['msg'];
    }
    
   $userId = $userDetail['userId'];
    
   $data = array("email"=>$emailId,"userId"=>$userId);
   $resInsert = $funobj->insertData($data, $tableName);

    
   include_once("../classes/transaction_class.php");
   $transaction_obj = new transaction_class();
   $transaction_obj->addTransactional_sub($pinDataDetail['pinGenerator'],$userId,$pinDataDetail['pinBalance'],$pinDataDetail['pinBalance'],"Pin User",0,0,0,"Create and Recharge by Pin");
            
   
    #update pin status 
    $table = '91_pinDetails';
    $data=array("usedDate"=>date('Y-m-d H:i:s'),"status"=>1,"usedBy"=>$userId); 
    $condition = "pincode='".$pin."'";
    $funobj->db->update($table, $data)->where($condition);	
    $funobj->db->getQuery();
    $result = $funobj->db->execute(); 
 
    $msg = "successfully id recharge by pin.. ";
    return $msg;
}

function getPinDetail($pin){
    
    $funobj = new fun();
    
    # check pin valid or not 
    if(!isset($pin) || strlen($pin)<5)
        {
         return json_encode(array('status'=>'error','msg'=>'Invalide pin!')); 
        }	

    # get pin status (1 for used or 0 for unused).
    $table = '91_pinDetails';

    #selecting the item from table 91_pinDetails
    $funobj->db->select('*')->from($table)->where("pincode ='".$pin."'");
    $funobj->db->getQuery();

    #execute query
    $result=$funobj->db->execute();

    #check the resulting value exists or not 
    if($result->num_rows == 0)
      {
         return json_encode(array('status'=>'error','msg'=>'Invalide pin!')); 
      }

    $row = $result->fetch_array(MYSQL_ASSOC);
    if($row['status'] == 1){
        return json_encode(array('status'=>'error','msg'=>'pin already used by another user!')); 
    }
    
    $batchId = $row['batchId']; 
    
    $pinTable = '91_pin';
    $condition = "batchId = '" . $row['batchId'] ."'"; //userId= '".$userid."' or 
    $funobj->db->select('*')->from($pinTable)->where($condition);
    $batchResult = $funobj->db->execute();

    // processing the query result
    if ($batchResult->num_rows == 0) {	
        return json_encode(array('status'=>'error','msg'=>'You Have No Permission To Use This Pin!')); 

    }

    $batchDetail = $batchResult->fetch_array(MYSQL_ASSOC);

    if(strtotime(date('Y-m-d',strtotime($batchDetail['expiryDate']))) < strtotime(date('Y-m-d'))){
         return json_encode(array('status'=>'error','msg'=>'Pin are expired !')); 
    }

    #pin tariff id 
    $pinTariff = $batchDetail['tariffId'];
       
    #find pin currency (call function_layer.php function) 
    $pinCurrency = $funobj->getOutputCurrency($batchDetail['tariffId']);
    
    #pin Generator id 
    $pinGenerator = $batchDetail['userId'];
    
    #pin balance
    $pinBalance = $batchDetail['amountPerPin'];
    
    return json_encode(array('status'=>'success','pinGenerator'=>$pinGenerator,'pinTariff'=>$pinTariff,'pinCurrency'=>$pinCurrency,'pinBalance'=>$pinBalance,'batchId'=>$batchId)); 
    
}

function createUser($resellerId,$tariff_id,$currency_id,$balance)
    {
        $funobj = new fun();
        
        $table = '91_userLogin';
        $condition = "userId = '".$resellerId."'";
        $funobj->db->select('isBlocked,deleteFlag')->from($table)->where($condition);        
        $loginresult = $funobj->db->execute();
        if ($loginresult->num_rows > 0) {
            $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
            $blockUnblockStatus = $logindata['isBlocked'];
            $deleteFlag = $logindata['deleteFlag'];
        }
        else
        {
            return json_encode(array("status"=>"error","msg"=>"Error Unable to fetch the reseller details Please Try again"));
        }
        
      
     $userName = $funobj->createUsername($resellerId);
     $password = $funobj->createUsername($resellerId);     
        
      #insert userdetail into database       
      $data=array("name"=>$userName); 
      $personalTable = '91_personalInfo';
      #insert query (insert data into 91_personalInfo table )
      $personalResult = $funobj->insertData($data, $personalTable);

      #check data inserted or not 
      if(!$personalResult){
//        $this->sendErrorMail("sameer@hostnsoft.com", "Phone91 signup_class personal info table query fail : $qur ");
        return json_encode(array("status"=>"error","msg"=>"pin User not created !"));
          
      }
      
           
      $userid = $funobj->db->insert_id;
      
      #insert login detail into login table database 
      $loginTable = '91_userLogin';
      $data=array("userId"=>$userid,"userName"=>$userName,"password"=>$password,"isBlocked"=>$blockUnblockStatus,"deleteFlag"=>$deleteFlag,"type"=>5); 

      #insert query (insert data into 91_userLogin table )
      $loginResult = $funobj->insertData($data, $loginTable);

      #check data inserted or not 
      if(!$loginResult){
          $funobj->deleteData($personalTable, "userId = ".$userid);
//         $this->sendErrorMail("rahul@hostnsoft.com", "Phone91 signup_class userlogin  table query fail : $qur ");
         return json_encode(array("status"=>"error","msg"=>"pin User not created!"));
          
      }
      
      #get last chain id from user balance table  
      $lastchainId = $funobj->getlastChainId($resellerId);
      
      #new chain id (incremented id of lastchain id )
      $chainId = $funobj->newChainId($lastchainId);
      
      #insert login detail into login table database 
      $balanceTable = '91_userBalance';
     
      $data=array("userId"=>(int)$userid,"chainId"=>$chainId,"tariffId"=>(int)$tariff_id,"balance"=>$balance,"currencyId"=>(int)$currency_id,"callLimit"=>2,"resellerId"=>(int)$resellerId); 

      #insert query (insert data into 91_userLogin table )
      $balanceResult = $funobj->insertData($data, $balanceTable);
      if (!$balanceResult){
          $funobj->deleteData($personalTable, "userId = ".$userid);
          $funobj->deleteData($loginTable, "userId = ".$userid);
          return json_encode(array("status"=>"error","msg"=>"pin User not created!"));  
      }
      return json_encode(array("status"=>"success","userId"=>$userid));  
    }

   
    
    function rechargeByPin($userid,$userTariff,$pinTariff,$batchId,$pin,$pinAmount)
	{
           
            #get ResellerId of user 
            $funobj = new fun();
            $resellerId = $funobj->getResellerId($userid);
                        
            #find pin generateor id
            $pinTable = '91_pin';
            $condition = "batchId = '" . $batchId . "' and (userId= '".$resellerId."') "; //userId= '".$userid."' or 
            $funobj->db->select('*')->from($pinTable)->where($condition);
            $batchResult = $funobj->db->execute();

            // processing the query result
            if ($batchResult->num_rows == 0) {	
                return json_encode(array('status'=>'error','msg'=>'You Have No Permission To Use This Pin!')); 
                
            }
            
            
            #find pin and user currency (call function_layer.php function) 
            $pinCurr = $funobj->getOutputCurrency($pinTariff);
            $userCurr = $funobj->getOutputCurrency($userTariff);
            
            if($pinCurr != $userCurr){
                 return json_encode(array('status'=>'error','msg'=>'you can not use this pin because pin currency not match.')); 
            }
            
            $table = '91_pinDetails';
            
            #update pin status 
            $data=array("usedDate"=>date('Y-m-d H:i:s'),"status"=>1,"usedBy"=>$userid); 
            $condition = "pincode='".$pin."'";
            $funobj->db->update($table, $data)->where($condition);	
            $funobj->db->getQuery();
            $result = $funobj->db->execute();
            
            
            #recharge pin entry in transaction log 
            $amountPerPin = $pinAmount;
            
            
            include_once("../classes/transaction_class.php");
            $transaction_obj = new transaction_class();
            
            
            
            
            $getBalance = $transaction_obj->getClosingBalance($userid);
            $msg = $transaction_obj->addTransactional_sub($resellerId,$userid,$amountPerPin,$amountPerPin,"Pin User",$amountPerPin,0,$getBalance,"Recharge by Pin");
            
            #get current balance form 91_userBalance table
            $currBalance = $transaction_obj->getcurrentbalance($userid);
            $currentBalance = ((int)$currBalance + (int)$amountPerPin);
            #update current balance of user in userbalance table 
            $transaction_obj->updateUserBalance($userid,$currentBalance);
            
            
            if($result){
            return json_encode(array('status'=>'success','msg'=>'successfully recharge!')); 
            }else
            {
                return json_encode(array('status'=>'error','msg'=>'error in recharge by pin!')); 
            }
            
            
            
           
		
	} 
        
        
    function getUserTariff($userid){
        
      #get ResellerId of user 
      $funobj = new fun();  
      $loginTable = '91_userBalance';
      
      #get reseller id for user 
      $funobj->db->select('*')->from($loginTable)->where("userId = '" .$userid. "'");
      
      $result = $funobj->db->execute();
      if($result)
      {
        $row = $result->fetch_array(MYSQL_ASSOC);
        $userTariff = $row['tariffId'];
        return $userTariff;
      }
      else
          return false;
         
    }
  
    
    
?>