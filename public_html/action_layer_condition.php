<?php  include('config.php');
       include('dbconfig.php');
       include ('excel_reader2.php');
       include_once("classes/phonebook_class.php");
       
//if(!$funobj->login_validate()){
//        $funobj->redirect("index.php");
//}
 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'feedbak' && $_REQUEST['magic']==$_SESSION['captcha'])
{
     //Mix this with inner feed back
    $header= 'MIME-Version: 1.0' . "\n";

    $header .= 'Content-type: text/html; charset=iso-8859-1' . "\n";						

    if(strlen($_REQUEST['mailid'])>8) //if user have his or her email then mail send by user email
    {
            $header .= 'From: '.$_REQUEST['mailid']."\n"	;	
             $header .= "Reply-To: ".$_REQUEST['mailid']."\r\n";
    }
    else
    {
            $header .= 'To: business@phone91.com <business@phone91.com>'. "\n"	;
    }

    mail("business@phone91.com,shubh124421@gmail.com","Feedback","Name:".$_REQUEST['mailid']."\n
        Username/Number ".$_REQUEST["number"]."\n
        Message:".$_REQUEST['msg'],$header);
    $randomCaptcha = rand('100', '990');
    $_SESSION['captcha'] = $randomCaptcha;
    echo "Success"; 
}


if(isset($_GET['action']) && $_GET['action']=="delete_client")
{
	$id=$_REQUEST['id'];
	$type=$_REQUEST['type'];
	echo $funobj->delete_client($id,$type);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (15/07/2013)
#condition for delete contact number
if(isset($_GET['action']) && $_GET['action']=="deleteContact")
{
   $pbookobj = new phonebook_class();
   $userid = $_SESSION['userid'];
   echo $result = $pbookobj->deleteContact($_REQUEST,$userid);
    
//	$id=$funobj->sql_safe_injection($_REQUEST['id']);
//	$funobj->delete_address_book($id);
	exit();	
}


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (15/07/2013)
#condition for Edit contact number
if(isset($_GET['action']) && $_GET['action']=="showEditContact")
{
   
   $pbookobj = new phonebook_class();
   echo $result = $pbookobj->showEditContact($_REQUEST);
    
//	$id=$funobj->sql_safe_injection($_REQUEST['id']);
//	$name=$funobj->sql_safe_injection($_REQUEST['cname']);
//	$number=$funobj->sql_safe_injection($_REQUEST['cnumber']);
//	$funobj->save_address_book($id,$name,$number);
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="updateContact")
{
   
   $pbookobj = new phonebook_class();
   $userid = $_SESSION['userid'];
   echo $result = $pbookobj->updateContact($_REQUEST,$userid);
}   
if(isset($_GET['action']) && $_GET['action']=="sendSms")
{
	$nmbrs=$funobj->sql_safe_injection($_REQUEST['nmbrs']);
	$msg=$funobj->sql_safe_injection($_REQUEST['msg']);
	$dataSms['mobiles']=$nmbrs;
	$dataSms['message']=$msg;
	$dataSms['sender']=$funobj->user_contact();
	$curl_scraped_page='';
	$tariff=$funobj->get_currency($_SESSION['id_tariff']);
	$main_balance=$funobj->user_balance();
	$deductAmount='';
	if($tariff=='INR')
		$deductAmount=1.2;
	else if($tariff=='USD')
		$deductAmount=0.025;
	else if($tariff=='AE')
		$deductAmount=0.085;
	$new_balance=$main_balance-$deductAmount;
	
	if($dataSms['sender']!="" and $tariff!='' and $nmbrs!='' and ($main_balance!='' or $main_balance>0) and $new_balance>=0)
	{
		$sender=$dataSms['sender'];
		$curl_scraped_page=$funobj->SendSMSUSD($dataSms);
		
		//echo 'Main Balance='.$main_balance.' '.$tariff.'<br />';
		//echo 'New Balance='.$new_balance.' '.$tariff.'<br />';
		
		$con=$funobj->connect();
		
		$sqlUpd="update clientsshared set
					  account_state='".$new_balance."'
					  where
					  id_client='".$_SESSION['userid']."'";
		$resultServer=mysql_query($sqlUpd,$con) or die('Query error');
		//echo '<br />';
		
		//mysql_close($con);
		
		//$con = mysql_connect("localhost","root",'') or die(" Couldnot connect to the server ");
		//mysql_select_db("voipswitch",$con) or die(" Database Not Found ");
		$qry2="insert into voipswitch.smsreport set
				UserId='".$_SESSION['userid']."',
				Sender='".$sender."',
				Recipients='".$dataSms['mobiles']."',
				Message='".$dataSms['message']."',
				InsertDateTime=now(),
				CurlReturnValue='".$curl_scraped_page."',
				Status='Pending'";
		$result=mysql_query($qry2,$con) or die('Query error');
		mysql_close($con);
	}
	else
	{
		echo "Error In Code";
	}
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (12/07/2013)
#condition for add contact number
if(isset($_REQUEST['action']) && $_REQUEST['action']=="addContact")
{
   
   
   $userid = $_SESSION['userid'];
   $pbookobj = new phonebook_class();
   echo $result = $pbookobj->addContact($_REQUEST,$userid);
    
//	$name=$funobj->sql_safe_injection($_REQUEST['cname']);
//	$number=$funobj->sql_safe_injection($_REQUEST['cnumber']);
//	$funobj->add_address_book($name,$number);
//	exit();	
}


if(isset($_REQUEST['action']) && $_REQUEST['action']=="searchContact")
{
   $pbookobj = new phonebook_class();
   echo $result = $pbookobj->searchContact($_REQUEST);
}

if(isset($_GET['action']) && $_GET['action']=="login_user")
{
	$userid=$funobj->sql_safe_injection($_REQUEST['uname']);
	$pwd=$funobj->sql_safe_injection($_REQUEST['pwd']);
	$funobj->login_user($userid,$pwd);
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="feedback")
{
	$sub=$funobj->sql_safe_injection($_REQUEST['subject']);
	$dis=$funobj->sql_safe_injection($_REQUEST['discription']);
	if(strlen($sub)>5 && strlen($dis)>20)
		echo $funobj->user_feedback($sub,$dis);
	else
	echo "Please Provide Proper Information";
	exit();	
}
		
		
if(isset($_GET['action']) && $_GET['action']=="get_country")
{
	$country=$_REQUEST['q'];
	if(strlen($country)>5)
	echo $funobj->get_country_frm_num($country);
	else
	echo "";
	exit();	
}


		
		
if(isset($_GET['action']) && $_GET['action']=="logout")
{
	$funobj->logout();
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="check_avail")
{
    include_once("classes/signup_class.php");
    $a =$signup_obj->check_user_avail();
    echo $a;
    exit();
}
if(isset($_GET['action']) && $_GET['action']=="check_email_avail")
{
	include_once("classes/signup_class.php");
        $a =$signup_obj->check_email_avail($_REQUEST["email"]);
	echo $a;
	exit();	
}

if(isset($_GET['action']) && $_GET['action']=="verifyConfirmation")
{
	$a =$funobj->verifyCode($_REQUEST["code"]);
	echo $a;
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="reset_pwd")
{
    $userId=$funobj->verifyCode($_REQUEST["code"]);
    if($userId == base64_decode($_REQUEST["key"]))
    {
        $a =$funobj->change_pwd("", $_REQUEST["new_pwd"],$userid,1);
        echo $a;
    }
    exit();	
}
if(isset($_GET['action']) && $_GET['action']=="forget_pass")
{
	$username=trim($_REQUEST['uname']);
	echo $a = $funobj->forget_password($username,$_REQUEST["smsCall"]);
	exit();	
}

if(isset($_GET['action']) && $_GET['action']=="delete_smpp")
{
	$smsc_id=$_REQUEST['smsc_id'];
	$con=$fun->smpp_connect();														
	$search_qry="DELETE FROM smpp_setup_request where smsc_id like '$smsc_id' limit 1";
	$exe_qry=mysql_query($search_qry) or die(mysql_error());
	mysql_close($con);
}
/*
 * modified by:Balachandra<balachandra@hostnsoft.com>
 *  date: 29/07/2013
 */
if(isset($_GET['action']) && $_GET['action']=="change_pwd")
    {   
                
        #store the posted current password  in $curr_pwd
        $curr_pwd = $_REQUEST['curr_pwd'];
        
        #store the posted new password in $new_pwd
        $new_pwd = $_REQUEST['new_pwd'];
        
        #getting the session userid
        $userid = $_SESSION['userid'];
        
        #confirm password also stored in $confirm_pwd
	$confirm_pwd = $_REQUEST['confirm_pwd'];
        
        #required field validator 
        if($curr_pwd == "" || $new_pwd == "" || $userid== "" || $confirm_pwd =="")
        {
            echo json_encode(array('msgtype'=>'error','msg'=>'All fields are required please provide proper input'));		
            exit();
        }
        
        
        #comapre entered two new passwords
	if( $confirm_pwd === $new_pwd )
	{ 
            #not needed any more 
                #remove new lines and slashes in the variable;
//		$new_pwd = $funobj->sql_safe_injection($new_pwd);
                
                #$a is the variable to return a value from the function layer
                $a = $funobj->change_pwd($curr_pwd,$new_pwd,$userid);		
		echo $a;
		exit();	
	}
	else
	{
            //"new passwords do not match"
             echo json_encode(array('msgtype'=>'error','msg'=>'new passwords do not matching'));		
             exit();
	}
}

//not in use
if(isset($_GET['action']) && $_GET['action']=="change_emailid")
{
        $new_emailid=$_REQUEST['new_emailid'];
        $confirm_emailid=$_REQUEST['confirm_emailid'];
        if(     $confirm_emailid == $new_emailid)
        {
		$check = $funobj->isValidEmail($confirm_emailid);
		if($check){
                	$new_emailid=$funobj->sql_safe_injection($new_emailid);
                	$a = $funobj->change_emailid($new_emailid);
                	echo $a;
                	exit();
		}
		else{
			echo '2';
		}
        }
        else
        {
                echo '3';//"Password not matched";
                exit();
        }
}

if(isset($_GET['action']) && $_GET['action']=="delete_emailid"){
	$result = $funobj->delete_emailid();
	if($result){
		echo 1;
	}
}


if(isset($_GET['action']) && $_GET['action']=="resend_ecode"){
        $result = $funobj->resend_ecode();
        if($result){
                echo 1;
        }
}

if(isset($_GET['action']) && $_GET['action']=="search_rate")
{
	$a = $funobj->check_rate($_REQUEST['code']);
	echo $a;
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="signup")
{
	include_once("classes/signup_class.php");
        $signup_obj = new signup_class();
       	echo $msg=$signup_obj->sign_up($_REQUEST);
        	
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="update_profile")
{
	include_once("classes/profile_class.php");
	$check_parrent=$pro_obj->check_parent_reseller($_REQUEST['id']);
	if($pro_obj->check_admin() || $check_parrent)
	{
	echo $msg=$pro_obj->update_client_details();
	}
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="resetClientPassword")
{
    include_once("classes/reseller_class.php");
    $res_obj=new reseller_class();
    $check_parrent=$res_obj->checkParentReseller($_REQUEST,$_SESSION);
//    if($pro_obj->check_admin() || $check_parrent)
    if($check_parrent)
    {
        echo $msg=$res_obj->resetClientPassword($_REQUEST,$_SESSION);
    }
    exit();
}
if(isset($_GET['action']) && $_GET['action']=="updateStatus")
{
	include_once("classes/profile_class.php");
	$check_parrent=$pro_obj->check_parent_reseller($_REQUEST['cid']);
	if($pro_obj->check_admin() || $check_parrent)
	{
		 $pro_obj->updateSta($_REQUEST['cid'], $_REQUEST['cstatus']);//update user status recursively
		 echo "Client status updated.";
	}

	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="update_dialplan")
{
	include_once("classes/profile_class.php");
	echo $msg=$pro_obj->edit_default_route();
	exit();	
}
if(isset($_REQUEST['action']) && $_REQUEST['action']=="edit_details")
{
    include_once("classes/setting_class.php");
    $editobj=new setting_class();
    $userid=$_SESSION["userid"];
    echo $msg=$editobj->update_newdetails($_REQUEST,$userid);
    exit();
}

#created by sudhir pandey ( sudhir@hostnsoft.com)
#creation date 25/07/2013
#codition for add new emailid of login user 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="update_newEmail")
{
    # get new email id 
    $new_emailid=$_REQUEST['newEmail'];
    # get confirm email id 
    $confirm_emailid=$_REQUEST['confirmEmail'];
    
    #check both email id is same or not 
    if($new_emailid == $confirm_emailid){
        include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
        echo $cont_obj->addnew_emailid($new_emailid,$userid);
    }else
        echo json_encode(array('msgtype' => 'error', 'msg' => 'Email id not matched !'));
    
	exit();	
}


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (23/07/2013)
#condition for add new contact of login user 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="update_newcontact")
{
	include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
        #variable country_code use for country code     
       	$country_code = $_REQUEST['country_code'] ;
        //$code = substr($code, 1, strlen($code) - 1);
	$phone = $_REQUEST['contact_no'];
	echo $msg=$cont_obj->update_newcontact($country_code,$phone,$userid);
//        echo $msg1=$cont_obj->resellercheck($phone,$userid);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (25/07/2013)
#condition for verify email id  
if(isset($_REQUEST['action']) && $_REQUEST['action']=="verifyEmailid")
{
	include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
	echo $msg=$cont_obj->verifyEmailid($_REQUEST,$userid);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25-07-2013
#condition use for make default email  
if(isset($_REQUEST['action']) && $_REQUEST['action']=="makeDefaultemail")
{
        include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
	echo $msg=$cont_obj->makeDefaultemail($_REQUEST,$userid);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (23/07/2013)
#condition for verify mobile number 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="verifyNumber")
{
	include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
	echo $msg=$cont_obj->verifyNumber($_REQUEST,$userid);
	exit();	
}
#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 24-07-2013
#condition use for make default number 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="makeDefaultNumber")
{
        include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
	echo $msg=$cont_obj->makeDefaultNumber($_REQUEST,$userid);
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="rechargeByPin")
{
	include_once("classes/pin_class.php");
	$pin_obj=new pin_class();
	echo $msg=$pin_obj->rechargeByPin($_REQUEST,$_SESSION["userid"]);
	exit();	
}
#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (18/07/2013)
#condition for add pin batch
if(isset($_REQUEST['action']) && $_REQUEST['action']=="createPinBatch")
{
	error_reporting(-1);
	include_once("classes/pin_class.php");
	error_reporting(-1);
	$pin_obj=new pin_class();
	$userId=$_SESSION["id"];	
	echo $pin_obj->generateBatch($_REQUEST,$userId);
	exit();	
}
#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (19/07/2013)
#condition for add pin batch
if(isset($_REQUEST['action']) && $_REQUEST['action']=="editPinBatch")
{
	error_reporting(-1);
	include_once("classes/pin_class.php");
	error_reporting(-1);
	$pin_obj=new pin_class();
	$userId=$_SESSION["id"];	
	echo $msg=$pin_obj->editPinBatch($_REQUEST,$userId);
	exit();	
}


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (19/07/2013)
#condition for add pin batch
if(isset($_REQUEST['action']) && $_REQUEST['action']=="searchBatch")
{
	error_reporting(-1);
	include_once("classes/pin_class.php");
	error_reporting(-1);
	$pin_obj=new pin_class();
	$userId=$_SESSION["id"];	
	echo $msg=$pin_obj->searchBatch($_REQUEST,$userId);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (07-08-2013)
#condition for change pin batch status (enable / disable)
if(isset($_REQUEST['action']) && $_REQUEST['action']=="changeBatchAction")
{
    
	error_reporting(-1);
	include_once("classes/pin_class.php");
	error_reporting(-1);
	$pin_obj=new pin_class();
	$userId=$_SESSION["id"];	
	echo $msg=$pin_obj->changeBatchAction($_REQUEST,$userId);
	exit();	
}


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (12-08-2013)
#condition for change batch amount status (paid / unpaid).
if(isset($_REQUEST['action']) && $_REQUEST['action']=="pinBatchAmountStatus")
{
    
	error_reporting(-1);
	include_once("classes/pin_class.php");
	error_reporting(-1);
	$pin_obj=new pin_class();
	$userId=$_SESSION["id"];	
	echo $msg=$pin_obj->pinBatchAmountStatus($_REQUEST,$userId);
	exit();	
}

#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 02/08/2013
# deletion of user Email Id
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'deletephone')
 {
    #include the pin class for function
    include_once("classes/contact_class.php");
    
    #getting id of the contact number through the POST method
    $contactid=$_REQUEST['id'];
    #creating the new object of class contact_class   
    $contact_obj = new contact_class();
    
    #getting the userid by session. 
    $userId=$_SESSION["id"];
    
    #get the message after the action from deletephone function
    $msg= $contact_obj->deletephone($contactid,$userId);
    #display the message
    echo $msg;
    
    exit();
 }

#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 31/07/2013
# deletion of user Email Id
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteEmailId')
 {
    #include the pin class for function
    include_once("classes/contact_class.php");
    
    #getting the id of email address by POST method
    $emailid=$_REQUEST['emailId'];
    #creating the class object of contact_class    
    $email_obj = new contact_class();
    #getting the userid by SESSION
    $userId=$_SESSION["id"];
    
    #store the result in $msg for the action to be taken in deleteEmailId()
    $msg= $email_obj->deleteEmailId($emailid,$userId);
    echo $msg;
    exit();
 }
 
 #created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 07/08/2013
# deletion of user unverifiedEmail Id
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteunverifyemail')
 {
    #include the pin class for function
    include_once("classes/contact_class.php");
    
    #getting the id of email address by POST method
    $unverifyemail=$_REQUEST['unverifyid'];
    #creating the class object of contact_class    
    $unverifyemail_obj = new contact_class();
    #getting the userid by SESSION
    $userId=$_SESSION["id"];
    
    #store the result in $msg for the action to be taken in deleteEmailId()
    $msg= $unverifyemail_obj->deleteunverifyemail($unverifyemail,$userId);
    echo $msg;
    exit();
 }
#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 07/08/2013
# deletion of user unverified number
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteunverifyphone')
 {
    #include the pin class for function
    include_once("classes/contact_class.php");
    
    #getting the id of email address by POST method
    $tempid=$_REQUEST['tempid'];
   
    #creating the class object of contact_class    
    $temp_obj = new contact_class();
    #getting the userid by SESSION
    $userId=$_SESSION["id"];
    
    #store the result in $msg for the action to be taken in deleteEmailId()
    $msg= $temp_obj->deleteunverifyphone($tempid,$userId);
    echo $msg;
    exit();
 } 
#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 02/008/2013
# deletion for the unused pinBatch
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteBatchPin')
 {
    #include the pin class for function
    include_once("classes/pin_class.php");
    
    #get the batchid by POST method
    $batchid=$_REQUEST['batchid'];
    
    #create a function object to access data
    $pin_obj = new pin_class();
    
    #userid 
    $userId=$_SESSION["id"];
    
    #connecting to function deleteBatchPin
    echo $msg= $pin_obj->deleteBatchPin($batchid,$userId);
    exit();
 }

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25/07/2013
#condition for resend confirmation code for contact no verification
if(isset($_REQUEST['action']) && $_REQUEST['action']=="resendConfirm_code")
{
	include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
	echo $msg=$cont_obj->resendConfirm_code($_REQUEST,$userid);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25/07/2013
#condition for resend confirmation code by call for contact no verification
if(isset($_REQUEST['action']) && $_REQUEST['action']=="callmeConfirm_code")
{
	include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
	echo $msg=$cont_obj->callmeConfirm_code($_REQUEST,$userid);
	exit();	
}
#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25/07/2013
#condition for resend confirmation code by email for email verification
if(isset($_REQUEST['action']) && $_REQUEST['action']=="resendEmailConfirm_code")
{
	include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
	echo $msg=$cont_obj->resendEmailConfirm_code($_REQUEST,$userid);
	exit();	
}


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25/07/2013
#condition for add new client 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="addNewClient")
{
	include_once("classes/reseller_class.php");
        $client_obj = new reseller_class();
        $userid=$_SESSION["id"];	
	echo $msg=$client_obj->addNewClient($_REQUEST,$userid);
	exit();	
}

#created by Rahul Chordiya (rahul@hostnsoft.com)
#creation date 06/08/2013
#code for bulk user generate
if(isset($_REQUEST['action']) && $_REQUEST['action']=="addNewClientBatch")
{
	include_once("classes/signup_class.php");
        $client_obj = new signup_class();
        $userid=$_SESSION["id"];	
	echo $msg=$client_obj->addNewClientBatch($_REQUEST,$userid);
	exit();	
}


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 05/09/2013
#code for change bulk client status used or unused username and password . 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="changeBulkClientStatus")
{
	include_once("classes/reseller_class.php");
        $client_obj = new reseller_class();
        $userid=$_SESSION["id"];
        echo $msg=$client_obj->changeBulkClientStatus($_REQUEST,$userId);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 05/09/2013
#code for search bulk client username and password . 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="searchBulkClient")
{
	include_once("classes/reseller_class.php");
        $client_obj = new reseller_class();
        echo $msg=$client_obj->searchBulkClient($_REQUEST);
	exit();
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 05/08/2013
#condition for see call rate 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="seeCallRate")
{
	include_once("classes/call_class.php");
        $call_obj = new call_class();
        $userid=$_SESSION["id"];
        $tariff_id = $_SESSION["id_tariff"];
	echo $msg=$call_obj->seeCallRate($_REQUEST,$tariff_id);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 06-08-2013
#condition for add reduce transaction log into manageclient
if(isset($_REQUEST['action']) && $_REQUEST['action']=="addReduceTransaction")
{
	include_once("classes/transaction_class.php");
        $trans_obj = new transaction_class();
        $userid=$_SESSION["id"];
        $tariff_id = $_SESSION["id_tariff"];
	echo $msg=$trans_obj->addReduceTransaction($_REQUEST,$userid);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 06-08-2013
#condition for get transaction log detail.
if(isset($_REQUEST['action']) && $_REQUEST['action']=="getTransactionLog")
{
	include_once("classes/transaction_class.php");
        $trans_obj = new transaction_class();
        echo $msg=$trans_obj->getTransactionLogDetail($_REQUEST['fromuser'],$_REQUEST['touser']);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 06-08-2013
#condition for edit fund  add reduce uesr balance and amount 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="editFund")
{
	include_once("classes/reseller_class.php");
        $client_obj = new reseller_class();
        $userid=$_SESSION["id"];	
	echo $msg=$client_obj->editFund($_REQUEST,$userid);
        exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 07-08-2013
#condition for edit client info  
if(isset($_REQUEST['action']) && $_REQUEST['action']=="editClientInfo")
{
	include_once("classes/reseller_class.php");
        $client_obj = new reseller_class();
        $userid=$_SESSION["id"];	
	echo $msg=$client_obj->editClientInfo($_REQUEST,$userid);
        exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 16-09-2013
#condition use for set user status (block or unblock) with all chain users 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="changeUserStatus")
{
	include_once("classes/reseller_class.php");
        $client_obj = new reseller_class();
        $userid=$_SESSION["id"];	
        echo $msg=$client_obj->changeUserStatus($_REQUEST,$userid,"isBlocked");
        exit();	
}


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 19-09-2013
#condition use for set user Delete flage with all chain users 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="setUserDeleteFlag")
{
	include_once("classes/reseller_class.php");
        $client_obj = new reseller_class();
        $userid=$_SESSION["id"];	
        echo $msg=$client_obj->changeUserStatus($_REQUEST,$userid,"deleteFlag");
        exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 16-09-2013
#condition use for set batch user status (block or unblock) 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="BatchBlockOrUnblock")
{
	include_once("classes/reseller_class.php");
        $client_obj = new reseller_class();
        $userid=$_SESSION["id"];	
        echo $msg=$client_obj->BatchBlockOrUnblock($_REQUEST,$userid,"isBlocked");
        exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 16-09-2013
#condition use for set batch user Delete flag
if(isset($_REQUEST['action']) && $_REQUEST['action']=="setBatchDeleteFlag")
{
	include_once("classes/reseller_class.php");
        $client_obj = new reseller_class();
        $userid=$_SESSION["id"];	
        echo $msg=$client_obj->BatchBlockOrUnblock($_REQUEST,$userid,"deleteFlag");
        exit();	
}





if(isset($_GET['action']) && $_GET['action']=="searchClient")
{
	error_reporting(-1);
	include_once("classes/reseller_class.php");
	error_reporting(-1);
	$reseller_obj=new reseller_class();
	$userId=$_SESSION["id"];	
	if(isset($_REQUEST["term"]))
		$q=$_REQUEST["term"];
	else
		$q="";
	//echo $msg=$pin_obj->generateBatch($_REQUEST,$userId);
	echo $reseller_obj->searchChiildList($userId,$q);
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="changeResellerSettings")
{
	error_reporting(-1);
	include_once("classes/reseller_class.php");
	error_reporting(-1);
	$reseller_obj=new reseller_class();
	$userId=$_SESSION["id"];
        $userId=1;
	echo $reseller_obj->changeResellerSettings($_REQUEST,$userId);
	exit();	
}

/**
 * Update User Profile
 */

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateProfile'){
    $con = dbConnect();
    if($con){
	$update_sql = "UPDATE `user_profile` SET `name`='".$_REQUEST['name']."',
	    `dob`='".$_REQUEST['dob']."',`city`='".$_REQUEST['city']."',
	    `zip`='".$_REQUEST['zip']."',`country`='".$_REQUEST['country']."', 
	    `address`='".$_REQUEST['address']."',`sex`=".$_REQUEST['sex'].",
	    `ocupation`='".$_REQUEST['ocupation']."' WHERE userid=".$_SESSION['userid'];
	echo mysql_query($update_sql,$con);
	mysql_close($con);
    }
}

//change user setting for updates
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'changeSettings'){
	$con = dbConnect();
	$value = ($_REQUEST['value'] == 0)?1:0;
	$update_sql = "UPDATE profile_settings SET ".$_REQUEST['key']."=".$value." WHERE user_id =".$_SESSION['userid'];
	mysql_query($update_sql,$con);
	mysql_close($con);
 }
 
  
 /**
  * Add a new plan using
  * Imported File
  * Old existing Plans
  * New Plans
  */

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'add'){
    include_once("classes/plan_class.php");
    $plan_obj = new plan_class();
    echo $msg = $plan_obj->addPlan($_REQUEST, $_SESSION, $_FILES);
    exit();
}

/**
 * Editing a plan single detail
 */

if((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'edit_plan')) ){
    include_once("classes/plan_class.php");
    $plan_obj = new plan_class();
    echo $msg = $plan_obj->editPlan($_REQUEST, $_SESSION);
    exit();
}

/**
  * Deleting a plan single detail
 */

if((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete_plan') ) ){
    include_once("classes/plan_class.php");
    $plan_obj = new plan_class();
    echo $msg = $plan_obj->deletePlan($_REQUEST, $_SESSION);
    exit();
}

/**
 * Multiple deletion of plans and plan details 
 * Kept details in backup before deletion
 */

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_plans'){
    include_once("classes/plan_class.php");
    $plan_obj = new plan_class();
    echo $msg = $plan_obj->deletePlans($_REQUEST, $_SESSION);
    exit();
}


//change batch status
if(isset($_GET['action']) && $_GET['action']== "batch_status"){
    include_once("classes/pin_class.php");
    $pin_obj = new pin_class();
    echo $msg = $pin_obj->batchStatus($_REQUEST);
    exit();
}


/**
 * Update user fund
 */

if(isset($_GET['action']) && $_GET['action'] == "edit_funds"){
    
	$con = dbConnect();
	$insert_sql = "INSERT INTO `reseller_transaction`
		      ( `trans_fuserid`, `trans_tuserid`, `trans_amt`, `trans_crnt_amt`, `trans_type`) 
	       VALUES (".$_SESSION['userid'].",".$_REQUEST['to_id'].",".$_REQUEST['amount_transfer'].",".$_REQUEST['balance'].",'".$_REQUEST['type']."')";
	echo mysql_query($insert_sql, $con);
	
}

/**
 * Update user contact detail
 */
if(isset($_GET['action']) && $_GET['action']=="update_contactno"){
        include_once("classes/updateContact_class.php");
	$contobj = new updateContact_class();
	echo $update_contact = $contobj->changeContact($_REQUEST, $_SESSION);
}

/**
 * delete phone no
 */
if(isset($_GET['action']) && $_GET['action']=="delete_phoneno"){
        include_once("classes/updateContact_class.php");
	$contobj = new updateContact_class();
	echo $update_contact = $contobj->deleteContact($_REQUEST['phone_no'], $_SESSION['userid']);
}

/**
 * add new signup user
 */
//if(isset($_GET['action']) && $_GET['action']=="signup_user"){
//    $a = $funobj->signupUser($_REQUEST);
//
//}

//SUBSITE 

/**
 * add a new subsite
 */
if(isset($_GET['action']) && $_GET['action']=="add_subsite"){
   include_once("classes/subSite_class.php");
   $siteobj = new subSite_class();
   echo $result = $siteobj->addSubsite($_REQUEST, $_SESSION);
}

/**
 * edit a subsite
 */
if(isset($_GET['action']) && $_GET['action']=="edit_subsite"){
   include_once("classes/subSite_class.php");
   $siteobj = new subSite_class();
   echo $result = $siteobj->editSubsite($_POST);
}

/**
 * delete a subsite
 */
if(isset($_GET['action']) && $_GET['action']=="delete_subsite"){
   include_once("classes/subSite_class.php");
   $siteobj = new subSite_class();
   echo $result = $siteobj->deleteSubsite($_REQUEST['subsite_pid']);
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 26-09-2013
#login redirect from other domain 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="loginRedirect")
{
	$userid = $_REQUEST['uname'];
        $pwd = $_REQUEST['pwd'];
	$remember_me = $_REQUEST['rememberMe'];
        $host = $_REQUEST['domain']; 
        $_SESSION['currentHost'] = $host;
	$funobj->login_user($userid,$pwd,$remember_me,$host);
	exit();	
}


?>
