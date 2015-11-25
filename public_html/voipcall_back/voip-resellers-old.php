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
<title>VoIP resellers | VoIP | Phone91 reseller</title>
<meta name="keywords" content="VoIP resellers, VoIP, Phone91 reseller" />
<meta name="description" content="VoIP resellers, you can resell phone91 VoIP and earn money. White label solutions also available for VoIP resellers. Start your business as VoIP reseller." />

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
<h1>Associates</h1>
	<div class="mcol">    
<h2>VoIP reseller opportunity:</h2>
<p>As to expand our business, we are looking for our channel partners who can resell our VoIP services to their region. Voices over Internet Protocol firmly know as VoIP is widely used in many countries as calling via VoIP is very cost effective. VoIP services are ideal for many individuals, firms and big companies, call centers etc. VoIP is a process of sending your voice i.e. analog signal into Digital signal over Internet.  In this packets are routed as digital packets over data network. </p>
<br /><br />
<strong>Benefits of VoIP resellers.</strong>
<ul>
<li>You get a white labeled solution with your own branding, i.e. you can manage your own clients. </li>
<li>No Server installation, no technical knowledge required.</li>
<li>You get can set your own price for selling; you may customize your plans as per your selling needs.</li>
<li>Security does matter, and phone91 takes its security very sickly. </li>
</ul>
<p>Start your own VoIP Company without taking any financial, marketing, technical risks.</p>

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