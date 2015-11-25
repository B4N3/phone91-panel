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
<title>Fair usage policy for VoIP | VoIP Terms and conditions.</title>
<meta name="keywords" content="You may call to any phone anywhere in world. Usage of VoIP depends on the fair usage; you must read following terms and condition before making a call." />
<meta name="Call to phone, terms and conditions for phone." />

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

<h1>Privacy Policy</h1>

<div class="mcol">
<p>WALKOVER WEB SOLUTIONS LTD. offers its services via the website "www.Phone91.com".</p>
<p>WALKOVER WEB SOLUTIONS LTD. is responsible for the processing of your Personal Data, as defined below.</p>

<p class="dream">Definitions</p>
<p>For the purposes of this policy:</p>
<p>"Personal Data" means:</p>
<ul>
<li>Name, address, telephone number</li>
<li>IP-address</li>
<li>Payment Data</li>
<li>Call Data Records</li>
</ul>
<br />
<p>"Services" means all communication services provided by WALKOVER WEB SOLUTIONS LTD.</p>

<p class="dream">Collection of the Personal Data and use of collected Personal Data</p>
<p>WALKOVER WEB SOLUTIONS LTD. collects, uses and stores your Personal Data in accordance with the Luxembourg Law of 2 August 2002 on the Protection of Persons with regard to the Processing of Personal Data, as modified.</p>
<p>WALKOVER WEB SOLUTIONS LTD. uses your Personal Data for the provision of Services and billing purposes.</p>
<p>WALKOVER WEB SOLUTIONS LTD. may use your Personal Data to improve their Services.</p>
<p>WALKOVER WEB SOLUTIONS LTD. may use your Personal Data to defect misuse of its system and / or a customer account.</p>
<p>WALKOVER WEB SOLUTIONS LTD. may use the Personal Data to provide you with information relating to your account.</p>
<p>WALKOVER WEB SOLUTIONS LTD. may use the Personal Data for marketing purposes, unless you object to this. Thus, you may at any time and without charge, contact WALKOVER WEB SOLUTIONS LTD. at the above-mentioned address to stop any use of your Personal Data for advertising or solicitation purposes.</p>
<p>WALKOVER WEB SOLUTIONS LTD. and any partner involved in providing the Services will store your Personal Data no longer than the time necessary to provide Services and in any case no longer than the maximum period permitted by the local laws, rules and regulations on Personal Data protection.</p>

<p class="dream">Disclosure and sharing of your Personal Data</p>
<p>WALKOVER WEB SOLUTIONS LTD. ensures the confidentiality of your Personal Data and will never disclose them to third parties without your consent, apart from the partners involved in providing the Services.</p>
<p>However, these partners involved in providing the Services will only receive the Personal Data required to perform Services. WALKOVER WEB SOLUTIONS LTD. and its partners are prohibited from using your Personal Data for any other purposes.</p>
<p>Your Personal Data can be transmitted and stored in Luxembourg and in Switzerland, offering an adequate level of protection.</p>
<p>By using the Services provided by WALKOVER WEB SOLUTIONS LTD., you agree that your Personal Data can be transmitted to partners in Members States of European Union or in countries providing adequate protection for the provision of the Services.</p>
<p>Personal Data may additionally be communicated to any employee of WALKOVER WEB SOLUTIONS LTD. or any partner involved in providing the Services. The communication to these third parties is limited to data necessary for the performance of their tasks for the same purposes as the one of WALKOVER WEB SOLUTIONS LTD..</p>

<p class="dream">Security of your Personal Data</p>
<p>WALKOVER WEB SOLUTIONS LTD. uses standard security technologies and procedures to ensure the protection of your Personal Data against unauthorized access, use, disclosure or destruction.</p>
<p>WALKOVER WEB SOLUTIONS LTD. takes security measures, such as technical and organizational measures against unauthorised or unlawful access to your Personal Data and against accidental loss or destruction of, or damage to your Personal Data.</p>
<p>Any sensitive information, such as your credit card number are protected by encryption. The encrypted communication is established using Secure Sockets Layer (SSL) technology.</p>
<p>Indeed, SSL provides the secure exchange of data between two computers in order to ensure the confidentiality, integrity of exchanged information and authentication by recognition of the identity of the program, the person or company with which the Personal Data is exchanged.</p>

<p class="dream">Access to your Personal Data</p>
<p>You can request free access to your Personal Data processed and stored by WALKOVER WEB SOLUTIONS LTD..</p>
<p>Should you wish to access to, update, rectify your Personal Data or object at any time, for compelling and legitimate reasons relating to your special situation, the processing of any data on you, you may make a request in writing to the address indicated below:</p>
<p><b>walkover web solutions limited</b><br />
(Company No.:7348545)<br />
145-157 St John Street<br />
London - EC1V 4PY<br />
England<br />
</p>

<p class="dream">Cookies</p>
<p>WALKOVER WEB SOLUTIONS LTD. draws your attention to the fact that during the time of the connection to the "www.phone91.com" site, a cookie can be automatically installed.</p>
   
</div><!--end mcol-->

</div>
<div class="clf"></div>
</div>
</div>
<?php include_once('../inc/footer.php');?>
</div><!--end main-->
</div><script type="text/javascript" src="../js/jquery.form.js"></script> 
</body>
</html>