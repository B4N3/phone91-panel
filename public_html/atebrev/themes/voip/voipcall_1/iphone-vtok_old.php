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
<title>iPhone VoIP  call | Use iPhone dialer for long distance calling</title>
<meta name="keywords" content="iPhone voip, voip on iPhone, iPhone dialer" />
<meta name="description" content="Make international calls via iPhone mobile dialer(Vtok). Now you can use iPhone mobile dialer for making international calls. " />

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
<h1>Supported Dialers</h1>
	<div class="mcol">    
<Phone 91 proffers endless options to its users to stay connected with their near ones. Vtok is one such alternative. Being aware of the latest technology, we provide you multiple options to make VOIP calls. Phone 91 provides its own dialers as well as it supports all the open source dialers.</P>

<h2>Vtok</h2>
<p> Vtok lets you make voice calls and video calls from your IPhone. And what’s best is that calling from Vtok is FREE. Vtok supports 2G, 3G and GPRS internet to fulfill all the requirements to make international calls.
</p>

<p>Latest version of Vtok can be downloaded from the below link:   <a href="https://itunes.apple.com/us/app/vtok/id421102042?mt=8&ls=1">Dwonload Link</a></p>

<h2>Vtok settings for VOIP calling:</h2>

<p>Simply follow the below mentioned steps to keep in touch with your near ones who are at a place far away from you:</p>

<strong>Step 1:</strong>
<p>Install the latest version of “Vtok” in your phone.</p>
<img src="img/vtok-installation1.jpg" alt="Voip on iPhone by Vtok" width="300" />
<br />
<br />
<strong>Step2:</strong>
<p>Now simply Sign in to Vtok using your Google account.</p>
<img src="img/vtok-installation2.jpg" alt="Voip on iPhone by Vtok" width="300" />
<br />
<br />
<strong>Step3:</strong>
<p>Now invite: <strong>phone@phone91.com</strong> </p>
<img src="img/vtok-installation3.png" alt="Voip on iPhone by Vtok" width="300" />
<br />
<br />
<strong>Step4:</strong>
<p>OnA message like below would be displayed.</p>
<img src="img/vtok-installation4.png" alt="Voip on iPhone by Vtok"width="300" />
<br />
<br />
<strong>Step5:</strong>
<p>Hurray. Phone 91 will now appear in your chat list. And another message like below would be displayed.</p>
<img src="img/vtok-installation5.png" alt="Voip on iPhone by Vtok" width="300" />
<br />
<br />
<strong>Step6:</strong>
<p>Touch the “International calls” option. </p>
<img src="img/vtok-installation6.png" alt="Voip on iPhone by Vtok" width="300" />
<br />
<br />
<strong>Step7:</strong>
<p>Now touch the “Chat” option out of all the available options. 
<img src="img/vtok-installation7.png" alt="Voip on iPhone by Vtok" width="300" /> </p>
<br />

<strong>Step8:</strong>
<p> Touch the “International calls” option. </p>
<img src="img/vtok-installation8.png" alt="Voip on iPhone by Vtok" width="300" />
<br />
<br />
<strong>Step9:</strong>
<p>In the chat box, type: Register <your username> <your password></p>
<img src="img/vtok-installation9.png" alt="Voip on iPhone by Vtok" width="300" />
<br />
<br />
<strong>Step10:</strong>
<p>Registration completed! Now simply enter the Destination number you want to dial along with the Country code. </p>
<img src="img/vtok-installation10.png" alt="Voip on iPhone by Vtok" width="300" />
<br />
<br />
<strong>Step11:</strong>
<p>Go back by touch the “Back” option. </p>
<img src="img/vtok-installation11.png" alt="Voip on iPhone by Vtok" width="300" />
<br />
<br />
<strong>Step12:</strong>
<p> Now touch the “Voice” option out of all the available options. 
And enjoy VOIP calling in the quickest, easiest and economical way possible. </p>
<img src="img/vtok-installation12.png" alt="Voip on iPhone by Vtok" width="300" />
<br />

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