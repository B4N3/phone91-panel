<?php
//include('config.php');
//include('dbcon.php');
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
//include transaction class
 include_once 'classes/transaction_class.php';
 $tranxObj = new transaction_class();
// Setup class
require_once('paypal.class.php');  // include the class file
$p = new paypal_class;             // initiate an instance of the class
//$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url#
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url


$http="http";
if($_SERVER['HTTP_HOST']=="voip91.com")
    $http="https";
$this_script = $http.'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$db = $funobj->connecti();

//$buninessEmail = 'ankkuboss2@gmail.com';
$buninessEmail = "Noreply@".(($_SERVER['HTTP_HOST']!='')?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME']).".com";
try
{
    include('sendmail.php');
   $mailerr = new MailAndErrorHandler();//create object
  
}
catch(Exception $e)
{
    $mailerr->errorHandler('problem in mail:'.print_r((array)$e));
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
        $p->add_field('business', 'payment@walkover.in');   //
         //$p->add_field('business','ankkuboss2@gmail.com');
        $p->add_field('return', $this_script.'?action=success');
        $p->add_field('cancel_return', $this_script.'?action=cancel');
        $p->add_field('notify_url', $this_script.'?action=ipn');
        // $p->add_field('item_name', 'Paypal Test Transaction');
       //  $p->add_field('amount', '1.99');
         
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
      $mailTo = array('AnkitPatidar@hostnsoft.com');
      $subject = "payment cancel in paypal Reverse update q of phone91";
      $message = print_r($_REQUEST,1);
      if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
      {
          $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
      }
       
        
  
      break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
   
       //send mail via mandrill
      $mailTo = array('AnkitPatidar@hostnsoft.com');
      $subject = "payment in ipn";
      $message = print_r($_REQUEST,1);
      if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
      {
          $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
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
         foreach ($p->ipn_data as $key => $value) 
         { 
             $body .= "\n$key: $value"; 
            
         }
         $payment_status = $p->ipn_data['payment_status'];
         $payer_email = $p->ipn_data['payer_email'];
         $txn_id = $p->ipn_data['txn_id'];
         $payment_date = $p->ipn_data['payment_date'];
         $mc_currency = $p->ipn_data['mc_currency'];
         //send mail via mandrill
          $mailTo = array('AnkitPatidar@hostnsoft.com');  //  your email
        
         $message = $body;
         if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
         {
                $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
         }
	 
	 //get required fields
        $reqPara = (array)json_decode(base64_decode($p->ipn_data['custom']));
        //  $reqPara = (array)json_decode(base64_decode($_REQUEST['custom'])); 
        //get orderId
        $orderId = trim(base64_decode($reqPara['orderId']));
        
        //get required details
        $actUserCharge = base64_decode($reqPara['charge']);
        $userCurrency= base64_decode($reqPara['userCurrency']); 
        $convertCurrency = base64_decode($reqPara['convertCurrency']);
        $recharge = $p->ipn_data['mc_gross'];
         //$recharge = base64_decode($reqPara['convertedAmt']);
        //$responseOid = trim(base64_decode($_POST[orderId]));
        //check order id
        if(!isset($orderId))
        {
            die('You are using invalide order number!!!');
        }
         
         //set table and condition
	$confirmOrdertable = 'confirmOrder';
        $where = "order_id='$orderId'";
	if($payment_status=='Refunded' || $payment_status=='Reversed' ||$payment_status=='Pending')
	{
            //update order to done
            $updateData = array('status'=> $payment_status);
          
            $query =  $tranxObj->db->update($confirmOrdertable,$updateData)->where($where);
            #get the query sentence
            $tranxObj->db->getQuery($query);
                
            #execute the query
            $updateResult = $tranxObj->db->execute($query);
            if(!$updateResult)
                $tranxObj->sendErrorMail("AnkitPatidar@hostnsoft.com",'Problem  in update recharge status  in confirm order in phone91 paypal:'.$query);
            
            $tranxObj->sendErrorMail("AnkitPatidar@hostnsoft.com",'phone91 paypal payment status:'.$payment_status.'payer_email:'.$payer_email.'transaction_id:'.$txn_id.'currency:'.$mc_currency.'amount:'.$recharge);
             $tranxObj->sendErrorMail("shubh124421@gmail.com",'phone91 paypal payment status:'.$payment_status.'payer_email:'.$payer_email.'transaction_id:'.$txn_id.'currency:'.$mc_currency.'amount:'.$recharge);
            
            die("payment status:".$payment_status);
//		$result=mysql_query("select id_client from payer_detail where paypal_email='".$id_client."'");
//		//$get_payer_info=mysql_fetch_array($result);
//		while($get_payer_info=mysql_fetch_array($result))
//		{
//			$to_be_block_user[]=$get_payer_info["id_client"];;
//		}
//		
//		$userChain=implode(",",$to_be_block_user);
//		  mail("AnkitPatidar@hostnsoft.com"," Block user list ","::".$userChain);
//			
	}
	
         
         //convert to respective currency
         if(isset($convertCurrency) and $convertCurrency != 'none') 
         { 
            $actUserCharge = ceil($funobj->currencyConvert($convertCurrency,$userCurrency,$recharge));
         } 
         else
             $convertCurrency = $userCurrency;
         
         //get details from db for order by order id
         $tranxObj->db->select('*')->from($confirmOrdertable)->where($where);
         $tranxObj->db->getQuery();
         $resultO = $tranxObj->db->execute();
         //send error mail
         if (!($resultO->num_rows > 0)) 
         {	
             $tranxObj->sendErrorMail("AnkitPatidar@hostnsoft.com",'Problem to fetch details in confirm order in phone91 paypal:'.$where); 
         }
        
        $row = mysqli_fetch_assoc($resultO);
       
        $id_client = $row['client_id'];
        $dbTalktime =$row['talktime'];
        $dbRechare = $row['recharge'];
        $dbIp = $row['ip'];
        
        //get details from userBalance table
        $useBaltable = '91_userBalance';
        $fields ="balance,currencyId,resellerId";
        $whereUb = "userId='$id_client'";
        $tranxObj->db->select($fields)->from($useBaltable)->where($whereUb);
        $tranxObj->db->getQuery();
        $userBalresult = $tranxObj->db->execute();
        
         if (!($userBalresult->num_rows > 0)) 
         {	
             $tranxObj->sendErrorMail("AnkitPatidar@hostnsoft.com",'Problem to fetch details in userbalance table  in phone91 paypal:'.$whereUb); 
         }
        
    
        $get_userinfo=  mysqli_fetch_assoc($userBalresult);
       
        $balance=$get_userinfo['balance'];
        
        $cid=$get_userinfo['currencyId'];
        $resellerId=$get_userinfo['resellerId'];
        $ip = base64_decode($reqPara['ip']);
        $totalBal = $balance+$dbTalktime;
        
        //get temprary amount
        $tempDecAmt = $actUserCharge-5;
        $tempIncrAmt = $actUserCharge+5;
        //$txn_id = $orderId;
        if($dbIp == $ip and ($dbRechare > $tempDecAmt or $dbRechare <$tempIncrAmt))//validate with ip and talktime and recharge amount
        {
            
            $tranxObj->updateUserBalance($id_client,$totalBal);//update balance
            $tranxObj->addTransactional($resellerId,$id_client,$actUserCharge,$dbTalktime,'paypal',$txn_id,'prepaid');//maintain trasaction log
           
            //update order to done
            $updateData = array('status'=>'done');
            $whereUP = "order_id ='$orderId'";
            $query =  $tranxObj->db->update($confirmOrdertable,$updateData)->where($whereUP);
            #get the query sentence
            $tranxObj->db->getQuery($query);
                
            #execute the query
            $updateResult = $tranxObj->db->execute($query);
            if(!$updateResult)
                $tranxObj->sendErrorMail("AnkitPatidar@hostnsoft.com",'Problem  in update recharge status  in confirm order in phone91 paypal:'.$query);
        }  
        else 
            $error = 'problem in talktime calculation or ip authentication!!!'.'dtt'.$dbTalktime.'dbip:'.$dbIp.'ip:'.$ip.'dbr:'.$dbRechare.'act'.$actUserCharge.'cc'.$convertCurrency;
        if($error!="")
        {
          
            //send mail via mandrill
            $mailTo = array('AnkitPatidar@hostnsoft.com');
            $subject = "update payment detail phone91";
            $message = $sql."::".$error;
            if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
            {
                $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
            }
            die( "Error while payment updation in paypal");
        }
        
        //$recharge = base64_decode($reqPara['recharge']);
        $userName = base64_decode($reqPara['uname']);
        if(!isset($mc_currency))
            $mc_currency = $convertCurrency;
	$mail_msg="One phone91 user with id ".$userName." and paypal emailid ".$payer_email." did paypal payment of ".$recharge." ".$mc_currency." and got recharged by ".$dbTalktime." ".$userCurrency.' tranx id:'.$txn_id;
	//send mail via mandrill
        
        $mailTo = array('AnkitPatidar@hostnsoft.com','shubh124421@gmail.com');
        $subject = "Phone91 Paypal payment";
        $message = $mail_msg;
        if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message,$buninessEmail))
        {
            $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
        }
        
   }
     break;
 }  
 
 
 
 
$arr = $_POST;

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
//       $id_client = mysqli_result($rs2,0,"id_client");
//       $reason = $arr['reason_code'];
//       //get user balance
//       $sqlForbal = "SELECT balance FROM userBalance WHERE userId='".$id_client."'";
//       $result=mysqli_query($db,$sqlForbal);
//       $get_userinfo=mysql_fetch_array($result);
//       $balance=$get_userinfo[balance];				
//       
//       //update balance
//       $errorU = $funobj->updateBalance($db,$id_client,$talktime,'sub');
//       if($errorU != 'success')
//           mail("ankkubosstest@gmail.com","error in balance updation phone91beta",$errorU);
//       
//       $totalBal = $balance-$talktime;
//       //insert details to transaction table
//       $sqlUpdatePayment = "insert into trasactions(userId,date,amount,description,transactionId,orderId,balance,paymentType) values('$id_client',now(),$actUserCharge,'$reason',1,'0','$orderId',$totalBal,'paypal')";
//       mysqli_query($db,$sqlUpdatePayment);
//
//   }  
//}
?>
<?php include_once("analyticstracking.php") ?>