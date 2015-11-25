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
<h1>Contact Us</h1>
	<div class="mcol">
        <h2>India</h2>
        <p><strong>Walkover Web Solutions Pvt. Ltd.</strong><br />
        505, Capt. C. S. Naidu Arcade,<br />
        Near Greater Kailash Hospital, 10/2 Old Palasia, INDORE, Madhya Pradesh, India-452002</p>
        
        <p>Email: support@phone91.com</p>
        <p>Telephone: +91 9977389948</p>        
       <p> </p>
 
    <p> </p>
<p> </p>
<p> </p>
        <p> </p>
 
    <p> </p><br />
<p> </p><br />
<p> </p>
        <h2>United Kingdom</h2>
        <p>walkover web solutions uk limited</p>
        <p>(Company No.:7348545)</p>
        <p>145-157 St John Street</p>
        <p>London - EC1V 4PY</p>
        <p>England</p>
        
        <h2>Seychelles</h2>
        <p>Caper limited</p>
        <p>Second Floor, Capital City Independence Avenue</p>
        <p>P.O. Box 1312</p>
        <p>Victoria, Mahé Seychelles</p>
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