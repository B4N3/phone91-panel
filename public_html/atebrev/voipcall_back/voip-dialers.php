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
<title>Android VoIP for call | Use Android dialer for long distance calling</title>
<meta name="keywords" content="Android voip, voip on android, android dialer, voip by mobile" />
<meta name="description" content="Make international calls via Android mobile dialer. Now you can use Android mobile dialer for making international calls. " />

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
<P>hone 91 proffers endless options to its users to stay connected with their near ones. Being aware of the latest technology, we provide you multiple options to make VOIP calls. Phone 91 provides its own dialers as well as it supports all the open source dialers. </P>

<h2>Android talk</h2>
<div>Talk for Android, or better, Gtalk for Android lets you make voice calls and video calls from your phone. And what’s best is that calling from Android talk is FREE. Talk for Android supports 2G, 3G and GPRS internet to fulfill all the requirements to make international calls.
</div>
</br>
<div>
Latest version of Talk for Android comes integrated into every Android phone.
</div>


<h2>Talk for Android settings for VOIP calling:</h2>
<div> 
<p>Simply follow the below mentioned steps to keep in touch with your near ones who are at a place far away from you:

</p>
</div>
<div>
<br />
<strong>Step 1:</strong> <br />
<p>Touch the "Menu" option in your phone. .</p>
<img src="img/call-from-android1.jpg" alt="Voip on Android" width="300" />
</div>

<div>
<br />
<strong>Step2:</strong> <br />
<p>Go to "Gtalk" application.</p>
<img src="img/call-from-android2.jpg" alt="Voip on Android" width="300" />
</div>

<div>
<br />
<strong>Step3:</strong> <br />
<p>Sign in to your "Gtalk" account.</p>
<img src="img/call-from-android3.jpg" alt="Voip on Android" width="300" />

</div>

<div>
<br />
<strong>Step4:</strong> <br />
<p>Now simply send chat invitation to <strong>phone@phone91.com</strong></p>
<img src="img/call-from-android4.jpg" alt="Voip on Android" width="300" />
 </div>
<br />
<div>
 <p><strong>Step5:</strong> </br>
 Phone 91 will now appear in your chat list.
<img src="img/call-from-android5.jpg" alt="Voip on Android" width="300" />
</p>
</div>
<div>
<br />
<strong>Step6:</strong> <br />
<p>Type “Register <username>  <password>” in your chat with Phone 91. Use the username and password you had used during the Sign up process. </p>
<img src="img/call-from-android6.jpg" alt="Voip on Android" width="300" />

<div>
<br />
<strong>Step7:</strong> <br />
<p>Now type the destination number that you want to dial along with the Country code.</p>
<img src="img/call-from-android7.jpg" alt="Voip on Android" width="300" />
</div>
<div>
<br />
<strong>Step8:</strong> <br />
<p>Have some patience and press the call button.</div>
<div>
And you are done! Start making calls to your family/friends right away. </p>

<img src="img/call-from-android8.jpg" alt="Voip on Android" width="300" />

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