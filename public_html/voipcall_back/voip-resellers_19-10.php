<?php	include('../config.php');
if(isset($_REQUEST['submit']))
{
	$userid=$funobj->sql_safe_injection($_REQUEST['uname']);
	$pwd=$funobj->sql_safe_injection($_REQUEST['pwd']);
	$funobj->login_user($userid,$pwd);
	exit();	
}	
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>About Phone91.com |International calling with Phone91</title>
<meta name="keywords" content="Phone91 is a leading International call provider. Phone91 provides various medium for making cheap international calls and long distance calls." />
<meta name="description" content="cheap international calls,  long distance calls." />
<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]-->
<!--[if !IE]><!--><!-- COMMENT on 15 april <link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /> --><!--<![endif]-->
<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php include_once('../inc/voipcallhead.php'); ?>
<?php include_once('../inc/incHead.php'); ?>
<script language="javascript" type="text/javascript" src="../js/jquery.colorbox.js"></script>
<script type="text/javascript" src="../js/jcom.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
	$(".register").colorbox();//initilisation of colorbox
	});
</script>
</head>
<body>

	<!-- Header -->
		<?php include_once('../inc/incHeader.php'); ?>
	<!-- //Header --> 

	<!-- Features -->
	<div class="mainFeaturesWrapper">
	  <section id="featuresWrap" class="noBanner">
	    <section class="innerBanner pr">
	      <h1 class="mianHead fs1 whClr rglr">
	        <div class="fl">Associates</div>
	        <span class="f16 db fl mrL1" style="width:80%;"> Know more about associates</span> </h1>
	      <div class="cl db pa backLinks">
	        <?php include_once("../inc/login_header.php") ?>
	      </div>
	      <span class="clr"></span> </section>
	  </section>
	</div>
	<!-- Features --> 

	<!-- Container -->
	<section id="container bgWh">
	  <section class="innerContainer mar0auto pdT4 rglr footerPages fs4 lightGray">		
      		
 			<h3 class="fs2 rglr pdB innerpageHeading">VoIP reseller opportunity:</h3>
	    	<p class="pdB2">As to expand our business, we are looking for our channel partners who can resell our VoIP services to their region. Voices over 		                  Internet Protocol firmly know as VoIP is widely used in many countries as calling via VoIP is very cost effective. VoIP services are 
            	ideal for many individuals,  firms and big companies, call centers etc. VoIP is a process of sending your voice i.e. analog signal into
                 Digital signal over Internet. In this packets are routed as digital packets over data network. 
            </p>

		    <h3 class="fs2 rglr pdB innerpageHeading">Benefits of VoIP resellers.</h3>
			<ul class="listIng termsListing pdB1 mrB2 lightGray">
               <li>You get a white labeled solution with your own branding, i.e. you can manage your own clients. </li>
               <li>No Server installation, no technical knowledge required.</li>
               <li>You get can set your own price for selling; you may customize your plans as per your selling needs.</li>
               <li>Security does matter, and phone91 takes its security very sickly. </li>
            </ul>
			<h2 class="bluShade  taC">Start your own VoIP Company without taking any financial, marketing, technical risks.</h2>

 	  </section>
	</section>
	<!-- //Container --> 
	
	<!-- Footer -->
	<?php //include_once('../inc/footer.php');?>
	<?php include_once('../inc/incFooter.php');?>
	<!-- //Footer --> 