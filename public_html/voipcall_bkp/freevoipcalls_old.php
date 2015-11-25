<?php	include('../config.php');
if(isset($_REQUEST['submit']))
{
	$userid=$funobj->sql_safe_injection($_REQUEST['uname']);
	$pwd=$funobj->sql_safe_injection($_REQUEST['pwd']);
	$funobj->login_user($userid,$pwd);
	exit();	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Get free VoIP balance, free VoIP calling by referring your friends.</title>
<meta name="keywords" content="Free VoIP balance, how to get free balance for VoIP." />
<meta name="description" content="Get Free VoIP balance with phone91.com; you can get free VoIP balance by referring your friends and acquaintance to phone91.com" />

<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]--> 
<!--[if !IE]><!--><link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /><!--<![endif]--> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.colorbox.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
$(".register").colorbox();//initilisation of colorbox
});
</script>
<script type="text/javascript" src="../js/jcom.js"></script>
</head>
<body>
<div id="main">
<div id="header">
<div class="centerinner">
<div id="logo"></div>
<?php include_once("../inc/login_header.php") ?>
</div>
<div class="clf"></div>
</div>
<div id="middle">
<div class="centerinner">
<div id="midleft">
<?php include('../links.php');?>
</div>

<div id="midright">
<h1>Affiliates</h1>
	<div class="mcol">    
<p>You may get a free balance by referring your friends and family members. You get 25% of amount of first successful recharge. </p>

<p>You get rewarded for sharing the happiness with your friends and others, refer your friends and pals to phone91 and get 25% amount of their first recharge.</p>

<p>You may promote phone91 by placing banner on your blog/website. You may following banners and text for promoting Phone91</p>

<h2>FAQs:</h2>
<strong>1.	How many friends can I refer?</strong>
<p>You may refer unlimited number of friends, there is no limits in referring an friends.</p>

<strong>2.	Can I refer friends from other countries?</strong> 
<p>Yes, you may refer phone91 to unlimited number of friends regardless of there country.</p>

<strong>3.	How can I refer a friends/ Family?</strong>
<p>After login you will get an referral url with your userid, Anyone who signups with that userid will be considered as your refer.</p>

<strong>4.	How will I know if any of my referrals register for Phone91.com?</strong>
<p>After the successful registration of your referrals, you will get an notification in your user panel.</p>

<strong>5.	How can I use this free Talk time?</strong>
<p>As this amount will be directly added to your account, you will be able to use it as your normal balance.</p>

<strong>6.	How will my referral signup?</strong>
<p>Your referral will signup either by using your referral url or by using your user id.</p>

<strong>7.	If one of my friend already received referral link of yours and I also referred him, will I get credit?</strong>
<p>In this case your friend will receive two referral links, one by you and another by another referral. While registering whichever link he use, the respective referrer will get benefits.</p>

<p>Phone91 shall not be responsible for this scheme or alternate benefits under this scheme.</p>

</div>
</div>

<div class="clf"></div>
</div>
</div>
<?php include_once('../inc/footer.php');?>
</div><!--end main-->
</div><script type="text/javascript" src="../js/jquery.form.js"></script> 
</body>
</html>