<?php
include("includes/config.php");
include("includes/functions.php");
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

// Setup class
require_once('paypal.class.php');  // include the class file
$p = new paypal_class;             // initiate an instance of the class
//$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
            
// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'process';  

switch ($_GET['action']) {
    
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
	  foreach($_POST as $key=>$value){
	  $p->add_field($key, $value);
	  }
      $return = $_REQUEST['url1'];
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
 		header("location:login.php");
      echo "<html><head><title>Canceled</title></head><body><h3>The order was canceled.</h3>";
      echo "</body></html>";
      
      break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
   
      // It's important to remember that paypal calling this script.  There
      // is no output here.  This is where you validate the IPN data and if it's
      // valid, update your database to signify that the user has payed.  If
      // you try and use an echo or printf function here it's not going to do you
      // a bit of good.  This is on the "backend".  That is why, by default, the
      // class logs all IPN data to a text file.
      
      if ($p->validate_ipn()) {
          
         // Payment has been recieved and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.
  
         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.
  
         // For this example, we'll just email ourselves ALL the data.
/*          $subject = 'Instant Payment Notification - Recieved Payment';
        $to = '';    //  your email
         $body =  "An instant payment notification was successfully recieved\n";
         $body .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
         $body .= " at ".date('g:i A')."\n\nDetails:\n";
*/         
         foreach ($p->ipn_data as $key => $value) { $body .= "\n$key: $value"; 
		 $payment_status = $p->ipn_data['payment_status'];
		 $payer_email = $p->ipn_data['payer_email'];
		 $txn_id = $p->ipn_data['txn_id'];
		 $payment_date = $p->ipn_data['payment_date'];
		 }
//         mail($to, $subject, $body);
  	
		 
		 $sql = " select id_client from payments where id=".$p->ipn_data['custom'];
		 $rs1 = mysql_query($sql);
		 $id_client = mysql_result($rs1,0,'id_client');
		 
		 
		 $result=mysql_query("select account_state,id_client,id_currency from clientsshared where id_client='".$id_client."'");
		$get_userinfo=mysql_fetch_array($result);
		$balance=$get_userinfo[account_state];
		$id_client=$get_userinfo[id_client];
		$id_currency=$get_userinfo[id_currency];
		$cid=$get_userinfo[id_currency];
		
		
if($cid==1)
{
	if($p->ipn_data['mc_gross']>0 && $p->ipn_data['mc_gross']<20)
	$talktime=(int) ($p->ipn_data['mc_gross']*0.9);
	if($p->ipn_data['mc_gross']>=20 && $p->ipn_data['mc_gross']<50)
	$talktime=(int) $p->ipn_data['mc_gross'];
	if($p->ipn_data['mc_gross']>=50)
	$talktime=(int) ($p->ipn_data['mc_gross']*1.1);
}
elseif($cid==2)
{
	$amount = (42*$p->ipn_data['mc_gross']);
	if($amount>0 && $amount<500)
	$talktime=(int) ($amount*0.88);
	if($amount>=500 && $amount<1000)
	$talktime=(int)$amount;
	if($amount>=1000)
	$talktime=(int) ($amount*1.1);
}
elseif($cid==3)
{
	$amount = (3.5*$p->ipn_data['mc_gross']);
	if($amount>0 && $amount<100)
	$talktime=(int) ($amount*0.96);
	if($amount>=100 && $amount<200)
	$talktime=(int)$amount;
	if($amount>=200)
	$talktime=(int) ($amount*1.1);
}
		
		 $sql = "update payments set money =".$talktime." where id=".$p->ipn_data['custom'] ;
		 mysql_query($sql);
		
		//$type="Automatic recharge by Paypal";	
	//	$query="update clientsshared set account_state='$rechargeamt' where login='$_SESSION[username]'";
	
		$sql = "update clientsshared set account_state=".($talktime+$balance)." where id_client='".$id_client."'";
		mysql_query($sql);
		
		$payer_detail_sql = "insert into payer_detail(id_client,paypal_email,paypal_transaction_no,date1) values(".$id_client.",'".$payer_email."','".$txn_id."',now())";	
		mysql_query($payer_detail_sql);
		}
      break;
 }     

 $arr = $_POST;
 
 if($arr['payment_status']=='Refunded'){
$msg = "";

		$orderID = $arr['custom'];
//		$amounT = $arr['mc_gross'];		
		$sql = " select * from payments where id =".$orderID;
		$rs2 = mysql_query($sql);
		$id_client = mysql_result($rs2,0,"id_client");
		$money = mysql_result($rs2,0,"money");
		$reason = $arr['reason_code'];
		
		$result=mysql_query("select account_state,id_client,id_currency from clientsshared where id_client='".$id_client."'");
		$get_userinfo=mysql_fetch_array($result);
		$balance=$get_userinfo[account_state];				

		$sqlUpdateClientTable = " update clientsshared set account_state = (account_state-".$money.") where id_client = ".$id_client;
		mysql_query($sqlUpdateClientTable);
		
		$result=mysql_query("select account_state,id_client,id_currency from clientsshared where id_client='".$id_client."'");
		$get_userinfo=mysql_fetch_array($result);
		$balance=$get_userinfo[account_state];				

				
		$sqlUpdatePayment = "insert into payments(id_client,client_type,money,data,type,description,actual_value,invoice_id) values('$id_client',32,$money,now(),3,'$reason',$balance,0)";
		mysql_query($sqlUpdatePayment);
		
						
	foreach($_POST as $key=>$value){
		
		// Get Values of Refunded Payments					
		
		$msg.="\n $key \t : \t $value ";



	}
 
 }  


?>