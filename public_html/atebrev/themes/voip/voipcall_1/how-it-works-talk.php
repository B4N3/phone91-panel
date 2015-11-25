<?php	include('../config.php');
if(isset($_REQUEST['submit'])){
	$userid=$funobj->sql_safe_injection($_REQUEST['uname']);
	$pwd=$funobj->sql_safe_injection($_REQUEST['pwd']);
	$funobj->login_user($userid,$pwd);
	exit();	
} ?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>About Phone91.com |International calling with Phone91</title>
<meta name="keywords" content="Phone91 is a leading International call provider. Phone91 provides various medium for making cheap international calls and long distance calls." />
<meta name="description" content="cheap international calls,  long distance calls." />
<!--[if IE]><link rel="stylesheet" type="text/css" href="/css/phone91v2_ie.css" /><![endif]-->
<!-- <script type="text/javascript" src="/js/html5.js"></script> -->
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php include_once('../inc/voipcallhead.php'); ?>
<?php include_once('../inc/incHead.php'); ?>
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
        <div class="fl">How it works!</div>
        <span class="f16 db fl mrL1" style="width:75%">Know how will it work! </span></h1>
      <div class="cl db pa backLinks">
        <?php include_once("../inc/login_header.php") ?>
      </div>
      <span class="clr"></span> </section>
  </section>
</div>
<!-- Features --> 

<!-- Container -->
<section id="container bgWh">
  <section class="innerContainer mar0auto pdT4 rglr footerPages ">

    <aside class="fl leftSide rglr">
      	<ul class="ln" id="linkli">
            <li><a class="gtalk" href="how-it-works-gtalk.php">Gtalk</a></li>
            <li><a class="active talk" href="how-it-works-talk.php">Talk for Android</a></li>
            <li><a class="nimbuzz" href="how-it-works-nimbuzz.php">Nimbuzz</a></li>
            <li><a class="vtok" href="how-it-works-vtok.php">iPhone By Vtok</a></li>
            <li><a class="skype" href="#">Skype <span class="f11">(coming soon...)</span></a></li>
            <li><a class="msn" href="#">msn <span class="f11">(coming soon...)</span></a></li>
    	</ul>
    </aside>
    
   <aside class="fl rightSide rglr">
	  <div id="ajax_content">
	  	<?php  include('talk.php') ; ?> 
      </div>   
   </aside>
    <span class="clr"></span> 
   </section>
</section>
<!-- //Container --> 

<!-- Footer -->
<?php //include_once('../inc/footer.php');?>
<?php include_once('../inc/incFooter.php');?>
<!-- //Footer --> 
<script type="text/javascript" src="../js/jcom.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script>
</body>
</html>