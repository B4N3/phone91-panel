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
<title>Faq's about VoIP and Cheap calling cards, call overseas</title>
<meta name="keywords" content="Cheap calling cards, call overseas, calling overseas" />
<meta name="description" content="Get knowledge about VoIP services; know more about how to make cheap calls. You may call overseas with VoIP, anywhere the world." />

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
<h1>FAQs</h1>
	<div class="mcol">    
		<p>Most commonly questions which are misunderstood by many of us, if you feel any of your question or doubt remains unanswered do not hesitate to contact us.</p>

<h2>What is VoIP?</h2>
<p>VoIP stands for Voice over Internet Protocol or Voice Over IP. VoIP is the technique by which you convert your analog signal into digital data, and thereby transmitting it over Internet.</p>

<h2>How does VoIP work?</h2>
<p>A VoIP is a gateway that's converts your voice into data packets, which is then send over a broadband internet connection to our servers and which is forwarded to calling party.</p>
 
<h2>How can I use VoIP?</h2>
<p>You may use VoIP anywhere you want; you have to download any of our dialers i.e. either mobile or desktop dialers through which you may make a call. We also support calling through Gtalk.</p>


<h2>May I use mobile phone to call via VoIP?</h2>
<p>Yes you may use our mobile dialer to call through VoIP.</p>

<h2>May I call 911 or emergency services through phone91?</h2>
<p>No, you may not use our services to call either 911 or any of emergency services.</p>
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