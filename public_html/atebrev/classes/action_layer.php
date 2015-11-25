<?php  include('config.php');
       include('dbconfig.php');
       include ('excel_reader2.php');
       include_once("classes/phonebook_class.php");
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
   echo $result = $pbookobj->deleteContact($_REQUEST);
    
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
   $userid = $_SESSION['userId'];
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
   
   
   $userid = $_SESSION['userId'];
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
	$a =$funobj->check_user_avail();
	echo $a;
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="check_email_avail")
{
	$a =$funobj->check_email_avail($_REQUEST["email"]);
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
	$a =$funobj->resetPass($_REQUEST["new_pwd"],$userId);
	echo $a;
	exit();	
}
if(isset($_GET['action']) && $_GET['action']=="forget_pass")
{
	$username=$funobj->sql_safe_injection($_REQUEST['uname']);
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

if(isset($_GET['action']) && $_GET['action']=="change_pwd")
{
	$curr_pwd=$funobj->sql_safe_injection($_REQUEST['curr_pwd']);
	$new_pwd=$_REQUEST['new_pwd'];
	$confirm_pwd=$_REQUEST['confirm_pwd'];
	if(	$confirm_pwd == $new_pwd)
	{
		$new_pwd=$funobj->sql_safe_injection($new_pwd);
		$a = $funobj->change_pwd($curr_pwd,$new_pwd);		
		echo $a;
		exit();	
	}
	else
	{
		echo '3';//"Password not matched";
		exit();
	}
}

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
	/*$username=$funobj->sql_safe_injection($_REQUEST['username']);
	$location=$funobj->sql_safe_injection($_REQUEST['location']);
	$code=$funobj->sql_safe_injection($_REQUEST['code']);
	$mobileNumber=$funobj->sql_safe_injection($_REQUEST['mobileNumber']);
	$email=$funobj->sql_safe_injection($_REQUEST['email']);	
	$currency=$funobj->sql_safe_injection($_REQUEST['currency']);*/
	echo $a = $funobj->sign_up($_REQUEST);
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

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (23/07/2013)
#condition for add new contact of login user 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="update_newcontact")
{
	include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
	echo $msg=$cont_obj->update_newcontact($_REQUEST,$userid);
	exit();	
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date (23/07/2013)
#condition for varify mobile number 
if(isset($_REQUEST['action']) && $_REQUEST['action']=="varifyNumber")
{
	include_once("classes/contact_class.php");
        $cont_obj = new contact_class();
        $userid=$_SESSION["id"];	
	echo $msg=$cont_obj->varifyNumber($_REQUEST,$userid);
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
if(isset($_GET['action']) && $_GET['action']=="signup_user"){
    $a = $funobj->signupUser($_REQUEST);

}

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


?>
