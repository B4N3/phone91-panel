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
<title>About Phone91.com |International calling with Phone91</title>
<meta name="keywords" content="Phone91 is a leading International call provider. Phone91 provides various medium for making cheap international calls and long distance calls." />
<meta name="description" content="cheap international calls,  long distance calls." />

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
<h1>What Phone91 Is?</h1>
<div class="mcol">
<p>Established in 2008, phone91 has become a leading Easy, Hassle free VoIP solution provider from India. It has provided with many VoIP options to call globally or for calling India, UAE etc. There are many ways to make a Internet call which includes Fring, Nimbuzz ,Gtalk. Etc.</p>

<h2>About VoIP Services:</h2>
<p>VoIP stands for Voice over Internet Protocol. In this technology Voice has been transmitted via Internet i.e. the voice single in converted into data signal and send via our ISP modems. Due to the cost effectiveness of VoIP it has shown a tremendous growth in both small as well large scale industries.</p>

<p>Calling to any phone with VoIP is very cost effective. It has benefits in calling to your residents, business and big companies. As it is a prepaid account, therefore you need not need to verify about extensive charges or billing, moreover you also get a second billing.  VoIP has got many benefits like it much better than calling cards. While using VoIP calling you get a simple and easy to use user panel, whereas other things are bear by phone91 like technical details, infrastructure, server management etc. </p>
<h2>Benefits of VoIP:</h2>
<ul>
<li>Companies do not need to change their phone systems. A gateway is used behind existing telephone system, which routes the calls over VoIP.</li>
<li>Quality calls over IP, when you ask for quality its similar to any other PSTN (Public switching telephone network).</li>
<li>Some user thinks that Installation is costly and technically complex to install. As an end user, installation and other things are bear by phone91.com. You will get a simple user panel which is very easy to use for calling.</li>
<li>Rates are similar to PSTN rates: This is not true, calls made via VoIP are very cheap than PSTN.</li>
</ul>
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