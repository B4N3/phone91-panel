<?php
/**
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com> 
 * @file for process order and redirect to payment gateway site and after paypal validation ,update the user balance
 * @tracker set at each condition
 * @last updated by  Ankit Patidar <ankitpatidar@hostnsoft.com> on 24/10/2013
 */
//include required files
include_once ("classes/transaction_class_ankit.php");

//set default email
$defEmail1 = 'AnkitPatidar@hostnsoft.com';

//set paypal bussiness mail
$paypalBusinessMail = 'ankkuboss2@gmail.com';//payment@walkover.in
//get tracker time
$startTimTracker = date('d-m-Y H:i:s'); 
//create object for function layer
$funObj = new fun();

// Setup class
require_once('paypal.class_ankit.php');  // include the class file
$p = new paypal_class;             // initiate an instance of the class
$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url#
//$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url

//set protocol
$http="http";
if($_SERVER['HTTP_HOST']=="voip91.com")
    $http="https";

//get callback url
$thisScript = $http.'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

//set tracker msg
$trackMsg = 'user came to payment page';
//details for tracker
$trackDtl = array('return url' =>$thisScript);

//call tracker
$trackId = $funObj->paymentTracker(null, $startTimTracker,'',$trackMsg,$trackDtl);

//get tracker time
$startTimTracker = date('d-m-Y H:i:s'); 


/*  PHP Paypal IPN Integration Class Demonstration File
 *  4.16.2005 - Micah Carrick, email@micahcarrick.com
 *
 *  This file demonstrates the usage of paypal.class.php, a class designed  
 *  to aid in the interfacing between your website, paypal, and the instant
 *  payment notification (IPN) interface.  This single file serves as 4 
 *  virtual pages depending on the "action" varialble passed in the URL. It's
 *  the processing page which processes form data being submitted to paypal, it
 *  is the page paypal returns a user to upon success, it's the page paypal
 *  returns a user to upon canceling an order, and finally, it's the page that
 *  handles the IPN request from Paypal.
 *
 *  I tried to comment this file, aswell as the acutall class file, as well as
 *  I possibly could.  Please email me with questions, comments, and suggestions.
 *  See the header of paypal.class.php for additional resources and information.
*/

//get business email
$buninessEmail = "Noreply@".(($_SERVER['HTTP_HOST']!='')?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME']).".com";

//apply exception handling
try
{
    //include file for perform mail
    include('sendmail.php');
    $mailErr = new MailAndErrorHandler();//create object
  
}
catch(Exception $e)
{
    $mailErr->errorHandler('problem in mail object creation:'.print_r((array)$e,1));
    
    //free object space ]
    $mailErr = null;
    unset($mailErr);
    
    //stop script execution
    die('problem while object creation');
}

// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'process';  
switch ($_GET['action'])
{
    case 'process':      // Process and order...
      // There should be no output at this point.  To process the POST data,
      // the submit_paypal_post() function will output all the HTML tags which
      // contains a FORM which is submited instantaneously using the BODY onload
      // attribute.  In other words, don't echo or printf anything when you're
      // going to be calling the submit_paypal_post() function.
 
      // This is where you would have your form validation  and all that jazz.
      // You would take your POST vars and load them into the class like below,
      // only using the POST values instead of constant string expressions.
 
      // For example, after ensureing all the POST variables from your custom
      // order form are valid, you might have:
      //
      // $p->add_field('first_name', $_POST['first_name']);
      // $p->add_field('last_name', $_POST['last_name']);
       
        foreach($_POST as $key=>$value)
        {
                $p->add_field($key,$value); 
        }
      
        $p->add_field('business',$paypalBusinessMail);
        $p->add_field('return', $thisScript.'?action=success');
        $p->add_field('cancel_return', $thisScript.'?action=cancel');
        $p->add_field('notify_url', $thisScript.'?action=ipn');
        // $p->add_field('item_name', 'Paypal Test Transaction');
       //  $p->add_field('amount', '1.99');
         
        //tracker for user details
        $trackId = $funObj->paymentTracker($trackId, $startTimTracker,'','user under process order');
        
        //free object space
        $funObj = null;
        unset($funObj);
        
       $p->submit_paypal_post(); // submit the fields to paypal
       //$p->dump_fields();      // for debugging, output a table of all the fields
        break;
      
   case 'success':      // Order was successful...
   
      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST 
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.  
      
      //tracker for user details
      $trackId = $funObj->paymentTracker($trackId, $startTimTracker,'','user payment successful');
       
      $funObj = null;
      unset($funObj);
       
      header("location:login.php");
      echo "<html><head><title>Success</title></head><body><h3>Thank you for your order.</h3>";
      foreach ($_POST as $key => $value) { echo "$key: $value<br>"; }
      echo "</body></html>";
      
      // You could also simply re-direct them to another page, or your own 
      // order status page which presents the user with the status of their
      // order based on a database (which can be modified with the IPN code 
      // below).
      
      break;
      
   case 'cancel':       // Order was canceled...
      // The order was canceled before being completed.
        $_SESSION["msg"]="Payment Cancel By User";
        $_SESSION["msgtype"]="error";
 	header("location:index.php");
        echo "<html><head><title>Canceled</title></head><body><h3>The order was canceled.</h3>";
        echo "</body></html>";
       
        //send mail via mandrill
        $mailTo = array($defEmail1);
        $subject = "payment cancel in paypal Reverse update q of phone91";
        $message = print_r($_REQUEST,1);
        if(!$mailErr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
        {
             $mailErr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
        }
       
      //tracker for user details
        $trackId = $funObj->paymentTracker($trackId, $startTimTracker,'','paymnet cancel by user',$_REQUEST);  
  
        //free object space
        $funObj = null;
        unset($funObj);
        
        $mailErr = null;
        unset($mailErr);
        
      break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
   
       //tracker for user details
       $trackId = $funObj->paymentTracker($trackId, $startTimTracker,'','user enter ipn validation section',$_REQUEST);
       
       //start time for tracker
       $startTimTracker = date('d-m-Y H:i:s');
       //send mail via mandrill
       $mailTo = array($defEmail1);
       $subject = "payment in ipn";
       $message = print_r($_REQUEST,1);
       if(!$mailErr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
       {
          $mailErr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
       }
       
       /**
          *  @code to apply validation
          * 1.For one paypal id only one payment can be done in one day
          * @for done status
          */
         
         //get record by payerEmail
         $resultPaypal = $funObj->selectData('paymentId,status','confirmOrder',"paymentId='".$_REQUEST['payer_email']."' and status='done' and paymentBy= 0 and DATE( `recharge_time` ) = DATE( NOW( ) ) ");
         
         //get rows
         $getRows = $resultPaypal->num_rows;
             
         //apply condition for count,for one paypal id in one day
         if($getRows > 0)
         {
            //tracker for user details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,'','Maximum payment for this paypal id done for today',$_REQUEST); 
            
            //stop and exit the script
            die('Maximum payment for this paypal id done for today');
            
            exit();
         }
	
       
       // It's important to remember that paypal calling this script.  There
       // is no output here.  This is where you validate the IPN data and if it's
       // valid, update your database to signify that the user has payed.  If
       // you try and use an echo or printf function here it's not going to do you
       // a bit of good.  This is on the "backend".  That is why, by default, the
       // class logs all IPN data to a text file.
       
     if ($p->validate_ipn()) 
     {
         // Payment has been recieved and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.
         // 
         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.
         // For this example, we'll just email ourselves ALL the data.
         
         $subject = 'Instant Payment Notification - Recieved Payment';
         //$to = 'payphone91@gmail.com';    //  your email
		 $body =  "An instant payment notification was successfully recieved\n";
         $body .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
         $body .= " at ".date('g:i A')."\n\nDetails:\n";
         
         //collect response
         foreach ($p->ipn_data as $key => $value) 
         { 
             $body .= "\n$key: $value"; 
            
         }
         
         $paymentStatus = $p->ipn_data['payment_status'];
         $payerEmail = $p->ipn_data['payer_email'];
         $txnId = $p->ipn_data['txn_id'];
         $paymentDate = $p->ipn_data['payment_date'];
         $mcCurrency = $p->ipn_data['mc_currency'];
         //send mail via mandrill
          $mailTo = array($defEmail1);  //  your email
        
         $message = $body;
         
         //mail response
         if(!$mailErr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
         {
                $mailErr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
         }
	 
         
         
        
	
//	if($paymentStatus=='Refunded' || $paymentStatus=='Reversed' ||$paymentStatus=='Pending')
//	{
//		$result=mysql_query("select id_client from payer_detail where paypal_email='".$idClient."'");
//		//$get_payer_info=mysql_fetch_array($result);
//		while($get_payer_info=mysql_fetch_array($result))
//		{
//			$to_be_block_user[]=$get_payer_info["id_client"];;
//		}
//		
//		$userChain=implode(",",$to_be_block_user);
//		  mail("AnkitPatidar@hostnsoft.com"," Block user list ","::".$userChain);
//			
//	}
	
         
        //get array for required fields
        $reqPara = json_decode(base64_decode($p->ipn_data['custom']),TRUE);
        
        //get orderId
        $orderId = trim(base64_decode($reqPara['orderId']));
        
        //get charge amount
        $actUserCharge = base64_decode($reqPara['charge']);
        
        //get usercurrency
        $userCurrency= base64_decode($reqPara['userCurrency']); 
        
        //get convert currency
        $convertCurrency = base64_decode($reqPara['convertCurrency']);
        
        //get recharge value
        $recharge = $p->ipn_data['mc_gross'];
         
        //set tracker msg
        $trackMsg = 'user payment details';
        //details for tracker
        $trackDtl = array('charge' =>$actUserCharge,
                          'usercurrency' => $userCurrency,
                          'convertCurrency' => $convertCurrency,
                          'gross amount' => $recharge);

        //call tracker for user payment details
        $trackId = $funObj->paymentTracker($trackId, $startTimTracker, '',$trackMsg,$trackDtl);
         
        //get tracker time
        $startTimTracker = date('d-m-Y H:i:s');  
        
        //check the order id
        if(!isset($orderId))
        {
            die('You are using invalide order number!!!');
        }
        
        //apply select query to get order details by orderid
        $rs1 = $funObj->selectData('*', 'confirmOrder',"order_id='$orderId'");
        
        //validate result
        if(!$rs1)
        {
            //set tracker msg
           $trackMsg = 'problem while get order detail';
           //details for tracker
           $trackDtl = array('charge' =>$actUserCharge,
                             'usercurrency' => $userCurrency,
                             'convertCurrency' => $convertCurrency,
                             'gross amount' => $recharge,
                             'orderID' => $orderId);

           //call tracker for user payment details
           $trackId = $funObj->paymentTracker($trackId, $startTimTracker, '',$trackMsg,$trackDtl);
           
           //free obj space
           $funObj = null;
           unset($funObj);
           //stop execution
           die('problem while get order detail'); 
        }//end of result validate
        
        //get detail array
        $row = mysqli_fetch_assoc($rs1);
        
        //get order details from array
        $idClient = $row['client_id'];
        $dbTalktime =$row['talktime'];
        $dbRechare = $row['recharge'];
        $dbIp = $row['ip'];
        
        
         /**
          *  @code to apply validation
          * 1.For one ip address two payment can be done,
          * @for done status
          */
        
         //get record by payerEmail
         $resultPaypalIp = $funObj->selectData('paymentId,status','confirmOrder','ip='.$dbIp.' and status=done and paymentBy=0 and DATE(recharge_time)= DATE(NOW())');
         
         //get rows
         $getRowsIp = $resultPaypalIp->num_rows;
         
         //apply condition for count,for one ip address two times in one day
         if($getRowsIp > 1)
         {
            //tracker for user details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,'','Maximum attempts for this ip done for today',$trackDtl); 
            
            //stop and exit the script
            die('Maximum attempts for this ip done for today');
            
            exit();
         }
        
         //convert to respective currency
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
            $trackMsg = "problem while get user balance";
            
            //details for tracker
            $trackDtl = array('userId' => $idClient);

            //call tracker for user payment details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
            //free obj space
            $funObj = null;
            unset($funObj);
            
            die($trackMsg);//stop the srcipt execution
             
        }//end of result validation
         
        //get detail array
        $get_userinfo=mysqli_fetch_array($result);
       
        //get details from array balance,currency id,reseller id
        $balance=$get_userinfo['balance'];
        $cid=$get_userinfo['currencyId'];
        $resellerId=$get_userinfo['resellerId'];
        
        //get user ip
        $ip = base64_decode($reqPara['ip']);//get ip
        
        //calculate total balance
        $totalBal = $balance+$dbTalktime;
        
         //set tracker msg
        $trackMsg = 'user balance about to update';
        //details for tracker
        $trackDtl = array('charge' =>$actUserCharge.'||'.gettype($actUserCharge),
                          'usercurrency' => $userCurrency,
                          'convertCurrency' => $convertCurrency,
                          'gross amount' => $recharge.'||'.gettype($actUserCharge),
                          'balance' => $balance,
                          'total balance after recharge' => $totalBal,
                          'userIp' => $ip,
                          'txnId' => $txnId,
                          'resellerId' => $resellerId,
                          'userId' => $idClient,
                          'dbRechare' => $dbRechare,
                          'dbTalktime' => $dbTalktime,
                          'dbIP' => $dbIp);

        //call tracker for user payment details
        $trackId = $funObj->paymentTracker($trackId, $startTimTracker, $idClient,$trackMsg,$trackDtl);
         
        //start time for tracker
        $startTimTracker = date('d-m-Y H:i:s');
        //$txnId = $orderId;
        if($dbIp == $ip and $recharge == $actUserCharge)//validate with ip and talktime and recharge amount
        {
              //set tracker msg
            $trackMsg = 'user reached at before update condition';
             //call tracker
            
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
            //create transaction class obj
            $tranxObj = new transaction_class();
          
           
            $tranxObj->addTransactional($resellerId,$idClient,$dbRechare,$dbTalktime,'paypal',$txnId,'prepaid');//maintain transaction log    
            $tranxObj->updateUserBalance($idClient,$totalBal);//update balance
            
            //free the object space
            $tranxObj = null;
            unset($tranxObj);
                
            /**
             * @code to update payment status in confirmOrder table to one
             */
            //prepare update array
            $updateData = array('paymentId' => $payerEmail,
                                'status' => 'done',
                                'recharge_time' => date('Y-m-d H:i:s'));
            
            //code to update confirmorder table
            $funObj->updateData($updateData,'confirmOrder',"order_id ='".$orderId."'");
            
            
            //set tracker msg
            $trackMsg = 'user balance updated';
             //tracker for user details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
            //start time for tracker
            $startTimTracker = date('d-m-Y H:i:s');

        }  
        else 
            $error = 'problem in talktime calculation or ip authentication!!!'.'dtt'.$dbTalktime.'dbip:'.$dbIp.'ip:'.$ip.'dbr:'.$dbRechare.'act'.$actUserCharge.'cc'.$convertCurrency;
        if($error!="")
        {
          
             //tracker for user details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,'user balance not updated',$trackDtl);
            
            //send mail via mandrill
            $mailTo = array($defEmail1);
            $subject = "update payment detail phone91";
            $message = $sql."::".$error;
            if(!$mailErr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
            {
                $mailErr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
            }
            
            //free obj space
            $funObj = null;
            unset($funObj);

            $mailErr = null;
            unset($mailErr);
            //stop the script execution
            die( "Error while payment updation in paypal");
        }
        
        //get currency details
        $result = $funObj->selectData('name', 'currency_names',"id='$cid'");
        
         //if result not found then set tracker
        if(!$result)
        {
            
            $trackMsg = "problem while get currency name";
            
            //details for tracker
            $trackDtl = array('userId' => $idClient,
                              'cid' => $cid);

            //call tracker for user payment details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
             //free obj space
            $funObj = null;
            unset($funObj);
            
            //stop the script execution
            die($trackMsg);
             
        }//end of result validation
        
        //get array
        $get_userinfo=mysqli_fetch_array($result);
  
        //get currency
        $currency=$get_userinfo['name'];
        
        //get user name
        $userName = base64_decode($reqPara['uname']);
        
        //get payment currency
        if(!isset($mcCurrency))
            $mcCurrency = $convertCurrency;
        
        //set mail msg
	$mail_msg="One phone91 user with id ".$userName." and paypal emailid ".$payerEmail." did paypal payment of ".$recharge." ".$mcCurrency." and got recharged by ".$dbTalktime." ".$userCurrency.' tranx id:'.$txnId;
        
	//send mail via mandrill
        $mailTo = array($defEmail1);//,'shubh124421@gmail.com'
        $subject = "Phone91 Paypal payment";
        $message = $mail_msg;
        if(!$mailErr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
        {
            $mailErr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
        }
        
   }//end of ipn validation
   
   //free object space
   $funObj = null;
   unset($funObj);
   
   $mailErr = null;
   unset($mailErr);
   
   $p = null;
   unset($p);
   
     break;
 }//end of switch case  
 
//$arr = $_POST;

 //$arr=$https_POST_VARS;
//if(isset($arr['payment_status']))
//{
//   if($arr['payment_status']=='Refunded' || $arr['payment_status']=='Reversed' || $arr['payment_status']=='Pending' )
//   {
//       $msg = "";
//        $orderId = base64_decode($arr['orderId']);
////		$amounT = $arr['mc_gross'];		
//       $sql = " SELECT * FROM confirmOrder WHERE order_id =".$orderId;
//       $rs2 = mysqli_query($db,$sql);
//       $idClient = mysqli_result($rs2,0,"id_client");
//       $reason = $arr['reason_code'];
//       //get user balance
//       $sqlForbal = "SELECT balance FROM userBalance WHERE userId='".$idClient."'";
//       $result=mysqli_query($db,$sqlForbal);
//       $get_userinfo=mysql_fetch_array($result);
//       $balance=$get_userinfo[balance];				
//       
//       //update balance
//       $errorU = $funobj->updateBalance($db,$idClient,$talktime,'sub');
//       if($errorU != 'success')
//           mail("ankkubosstest@gmail.com","error in balance updation phone91beta",$errorU);
//       
//       $totalBal = $balance-$talktime;
//       //insert details to transaction table
//       $sqlUpdatePayment = "insert into trasactions(userId,date,amount,description,transactionId,orderId,balance,paymentType) values('$idClient',now(),$actUserCharge,'$reason',1,'0','$orderId',$totalBal,'paypal')";
//       mysqli_query($db,$sqlUpdatePayment);
//
//   }  
//}
?>
<?php include_once("analyticstracking.php") ?>