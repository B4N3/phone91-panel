<?php
/**
 * @last updated by Ankit Patidar <ankitpatidar@hostnsoft.com> on 24/10/2013
 * 
 * @Description response file to make payment with cashu payment gateway,
 * this file process order by validating order values and update user balance
 * tracker applied at each step of process 
 * 
 */

//include required files
include_once (dirname(dirname(__FILE__))."/classes/transaction_class.php");
include_once (dirname(dirname(__FILE__))."/paymentConfig.php");//include config file for payment
//get tracker time
$startTimTracker = date(DATEFORMAT);  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SUCCESS</title>
</head>

<body>
<center><strong>successful</strong></center> 
<?php


//apply error hanndling
try
{
  //include file to mail
  include(dirname(dirname(__FILE__)).'/sendmail.php');
  $mailerr = new MailAndErrorHandler();//create mailError object
  
  $funObj = new fun();//create fun class object

  $subject = "cashu payment gateway";
  $message = print_r($_REQUEST,1);
  if(!$mailerr->sendmail_mandrill($cashuErrorMails, $subject, $message,BUSINESSMAIL))
  {
      trigger_error('problem in mail:\nmailto'.print_r($errorMails,1).'\nsub:'.$subject.'\nmsg:'.$message);
  }
}
catch(Exception $e)//catch the exception
{
    trigger_error('problem in mail:'.print_r((array)$e,1).'\nmailto'.print_r($errorMails,1).'\nsub:'.$subject.'\nmsg:'.$message);
}

//set tracker msg
$trackMsg = 'user came to cashu payment page';

//call tracker for user payment details
$trackId = $funObj->paymentTracker(null, $startTimTracker, '',$trackMsg,$_REQUEST);

//get tracker time
$startTimTracker = date(DATEFORMAT);  

//get required values from request para
$recharge = $_REQUEST['amount'];

//get actual amount
$actUserCharge = base64_decode($_REQUEST['txt4']);

//get order detail array
$detailArr = json_decode(base64_decode($_REQUEST['txt1']),TRUE);

//get user currency
$userCurrency = $detailArr['userCurrency'];

//get order id
$orderId= base64_decode($_REQUEST['txt2']);

//get userIp
$ip= base64_decode($_REQUEST['txt5']);

//set tracker msg
$trackMsg = 'user payment details';
//details for tracker
$trackDtl = array('charge' =>$actUserCharge,
                  'usercurrency' => $userCurrency,
                  'gross amount' => $recharge,
                  'orderId' => $orderId,
                  'ip' => $ip);

//call tracker for user payment details
$trackId = $funObj->paymentTracker($trackId, $startTimTracker, '',$trackMsg,$trackDtl);

//get tracker time
$startTimTracker = date(DATEFORMAT);  
        
//check the order id
if(!isset($orderId))
{
    //log error
    trigger_error('order id not found '.json_encode($trackDtl));
    die('You are using invalid order number!!!');
}

 //apply select query to get order details by orderid
$orderRes = $funObj->selectData('*', '91_confirmOrder',"orderId='$orderId'");

//validate result
if(!$orderRes)
{
    //set tracker msg
   $trackMsg = 'cashu problem while get order detail';
   //details for tracker
   $trackDtl = array('charge' =>$actUserCharge,
                     'usercurrency' => $userCurrency,
                     'gross amount' => $recharge,
                     'orderID' => $orderId);

   trigger_error($trackMsg.'track detail:'.json_encode($trackDtl));
   //call tracker for user payment details
   $trackId = $funObj->paymentTracker($trackId, $startTimTracker, '',$trackMsg,$trackDtl);

   //free obj space
   unset($funObj);
   
   //stop execution
   die($trackMsg); 
}//end of result validate

//get detail array from mysql resourse
$row = mysqli_fetch_assoc($orderRes);

//get details from array,idClient,dbTalktime,dbRecharge,dbIp
$idClient = $row['clientId'];
$dbTalktime = $row['talktime'];
$dbRechare = $row['recharge'];
$dbIp = $row['ip'];


$convertCurrency = $detailArr['convertCurrency'];//get convertCurrency

//convert amount to respective  currency
if(isset($convertCurrency) and $convertCurrency != 'none') 
{ 
    //get converted amount
   $actUserCharge = $funObj->currencyConvert($userCurrency,$convertCurrency,$dbRechare);

   //get amount with 2 decimal points
   $actUserCharge = $funObj->getNumberWithTwoDecimal($actUserCharge);
} 
else//assign usr currency to convert currency
    $convertCurrency = $userCurrency;


 //get user detail
$result = $funObj->selectData('balance,currencyId,resellerId', '91_userBalance',"userId='$idClient'");

//if result not found then set tracker
if(!$result)
{
    //set tracker msg
    $trackMsg = "problem while get user balance in cashu payment gateway";

    //details for tracker
    $trackDtl['userId'] =  $idClient;

    trigger_error($trackMsg.' track detail:'.json_encode($trackDtl));
    //call tracker for user payment details
    $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);

    //free obj space
    unset($funObj);
    
    die($trackMsg);//stop the srcipt execution

}//end of result validation

//convert mysql resourse to array
$getUserinfo = mysqli_fetch_array($result);

//get details from array,balance,currency id,resellerId
$balance = $getUserinfo['balance'];
$cid = $getUserinfo['currencyId'];
$resellerId = $getUserinfo['resellerId']; 

//get talktime
$talktime = base64_decode($_REQUEST['txt3']);

//get transaction id
$txnId = $_REQUEST['trn_id'];

//get total balance after update
$totalBal = $balance+$dbTalktime;

 //set tracker msg
$trackMsg = 'user balance about to update';
//details for tracker
$trackDtl = array('charge' =>$actUserCharge,
                  'usercurrency' => $userCurrency,
                  'convertCurrency' => $convertCurrency,
                  'gross amount' => $recharge,
                  'balance' => $balance,
                  'total balance after recharge' => $totalBal,
                  'userIp' => $ip,
                  'txnId' => $txnId,
                  'talktime' => $talktime,
                  'resellerId' => $resellerId,
                  'userId' => $idClient,
                  'dbRechare' => $dbRechare,
                  'dbTalktime' => $dbTalktime,
                  'dbIP' => $dbIp);

//call tracker for user payment details
$trackId = $funObj->paymentTracker($trackId, $startTimTracker, $idClient,$trackMsg,$trackDtl);

//start time for tracker
$startTimTracker = date(DATEFORMAT);

//validate with ip  and recharge amount
if($dbIp == $ip and $recharge == $actUserCharge)
{
  
     //set tracker msg
    $trackMsg = 'user reached at before update condition';
     //call tracker

    $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
    
    //start time for tracker
    $startTimTracker = date(DATEFORMAT);
    
    //get currency id
    $currencyId = $funObj->getCurrency($userCurrency);
    
   //create transaction class object
    $tranxObj = new transaction_class();
    
    //get closing balance
    $closingBalance = $tranxObj->getClosingBalance($idClient);
    
     $tranxObj->updateUserBalance($idClient,$dbTalktime,'+'); //update balance
    
     //set fromUser and toUser
     $tranxObj->fromUser = $resellerId;
     $tranxObj->toUser = $idClient;
     
     $updatedClosingBal = $closingBalance-$dbRechare;
     
     //maintain transaction log
    $tranxObj->addTransactional_sub(0,$balance,'cashu',0,$dbRechare,$updatedClosingBal,'txnId:'.$txnId,$currencyId);

    $tranxObj->addTransactional_sub($dbTalktime,$totalBal,'voip',$dbRechare,0,$closingBalance,'txnId:'.$txnId,$currencyId);
    
    
   
    //$tranxObj->addTransactional($resellerId,$idClient,$recharge,$dbTalktime,'cashu',$orderId,'prepaid'); //maintain transaction log

    /**
    * @code to update payment status in confirmOrder table to one
    */
   //prepare update array
   $updateData = array('paymentId' => '',
                       'status' => 'done',
                       'rechargeTime' => date('Y-m-d H:i:s'));

   //code to update confirmorder table
   $funObj->updateData($updateData,'91_confirmOrder',"orderId ='".$orderId."'");
            
    
    
    //free the object space
    unset($tranxObj);

    //set tracker msg
    $trackMsg = 'user balance updated';
    //tracker for user details
    $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);

    
    
    //start time for tracker
    $startTimTracker = date(DATEFORMAT);
    
   
}  
else //set error msg 
    $error = 'problem in talktime calculation or amount calculation!!!';

if($error!="") //mail error
{

    //tracker for user details
    $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,'user balance not updated in cashu :'.$error,$trackDtl);
    
    //set details to mail
    $subject = "error occur while update payment detail phone91 cashU";
    $message = $sql."::".$error.'tt'.$talktime.'dbtt'.$dbTalktime.'dbc'.$dbRechare.'actr'.$actUserCharge;
    if(!$mailerr->sendmail_mandrill($cashuErrorMails, $subject, $message,BUSINESSMAIL))
    {
        trigger_error('problem in mail:\nmailto'.print_r($errorMails,1).'\nsub:'.$subject.'\nmsg:'.$message);
    }
}


//get user name
$userName = $detailArr['uname'];

//mail msg
$mailMsg="One phone91 user with id ".$userName."  did paypal payment of ".$recharge." ".$convertCurrency." and got recharged by ".$talktime." ".$userCurrency.' with trackId:'.$trackId;
   
//set mail details
$subject = "Phone91 cashU payment Successful";
$message = $mailMsg;

//mail on success
if(!$mailerr->sendmail_mandrill($cashuSuccessEmails, $subject, $message,BUSINESSMAIL))
{
    trigger_error('problem in mail:\nmailto'.print_r($cashuSuccessEmails,1).'\nsub:'.$subject.'\nmsg:'.$message);
}

//show success msg to user
echo "Updated data successfully\n"; 

include_once("../analyticstracking.php");
?>
</body>
</html>