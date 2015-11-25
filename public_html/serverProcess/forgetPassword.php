<?php
include("includes/config.php");
include("includes/functions.php");
include('smsconfig.php');

$username=$_REQUEST['uid'];
$smsuser=$_REQUEST['suser'];
$smspwd=$_REQUEST['pwd'];
$domain=$_REQUEST['d'];
$result=mysql_query("select id_client,password from clientsshared where login='$username'");
		$res=mysql_num_rows($result);
		$get_userinfo=mysql_fetch_array($result);
		$uid=$get_userinfo[id_client];
		$pwd=$get_userinfo[password];
			
		if($res==0) {
		
			echo 100;
			exit;
        }else {
		
		$result=mysql_query("select contact_no,cntry_code from contact where userid=".$uid);
		
		$get_userinfo=mysql_fetch_array($result);
		$contact_no=$get_userinfo[contact_no];
		$code=$get_userinfo[cntry_code];
$contact=$code.$contact_no;
$nine[sender] = $domain;
$nine[user] = $smsuser; // mobile number without 91
$nine[password] = $smspwd; // sms text for usd
$nine[mobiles] = $contact; // mobile number without 91
$nine[message] = "your password is: ".$pwd; // sms text for usd

//Call function

	SendSMS($nine);

echo 200;	
exit;
		}

?>
