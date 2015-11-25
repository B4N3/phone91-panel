<?php	
include_once 'config.php';
include_once (ROOT_DIR.'/config/whiteLabelConfig.php');


if(is_dir(_THEME_PATH_))
{
    include_once(_THEME_PATH_.'/reseller.php');
    exit();
}
die("404 not found");
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
<title>Reseller with Phone91.com |International calling with Phone91</title>
<meta name="keywords" content="Phone91 is a leading International call provider. Phone91 provides various medium for making cheap international calls and long distance calls." />
<meta name="description" content="cheap international calls,  long distance calls." />
<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]-->
<!--[if !IE]><!--><!-- COMMENT on 15 april <link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /> --><!--<![endif]-->
<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery.colorbox.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
	
	$(".register").colorbox();//initilisation of colorbox
	
	});
</script>
<script type="text/javascript" src="../js/jcom.js"></script>
<?php include_once('inc/incHead.php'); ?>
</head>
<body>

	<!-- Header -->
		<?php include_once('inc/incHeader.php'); ?>
	<!-- //Header --> 

	<!-- Features -->
	<div class="mainFeaturesWrapper">
	  <section id="featuresWrap" class="noBanner">
	    <section class="innerBanner pr">
              <h1 class="mianHead">
                    <div>Phone91 reseller opportunity</div>
                    <span>As to expand our business, we are looking for our channel partners who can resell our VOIP services to their region.</span>
     		  </h1>
    		   <div class="met_short_split"><span></span></div>
	      <div class="cl db pa backLinks">
	        <?php include_once("inc/login_header.php") ?>
	      </div>
	      <span class="clr"></span> </section>
	  </section>
	</div>
	<!-- Features --> 

	<!-- Container -->
	<section id="container">
	  <section class="innerContainer  footerPages">		
      <h3 class="innerHeadIcn">Benefits of being a VOIP Reseller</h3>
      <ul class="listIng paddListing"> 
         	<li>You get a white label solution with your own branding, i.e. you can manage your own clients.</li>
            <li>You do not need any kind of server installation to get started.</li>
            <li>You get can set your own price for selling and you may also customize your plans as per your selling needs.</li>
            <li>Being a Reseller you get to toy with a variety of features like Manage funds, Manage clients, Resell via calling cards, and much more.</li>
            <li> Start your own VOIP Company without taking any financial, marketing or technical risks. All you need to get started is to Sign up or contact: <strong>business@phone91.com </strong></li>
         </ul>
      
		 <div class="desCript">
                <h3 class="innerHeadIcn">Curious? Go on!</h3>
                
                <ul class="listIng"> 
                <li><span>Is VOIP calling working in blocked countries ?</span><p>Yes! VOIP calling with Phone91 is 100% working in blocked countries as the calls are processed via Skype and Google talk (very soon possible through hangouts too). So you can talk seamlessly.</p></li>
		        
		        <li><span>What are the Reseller options available ?</span><p>You can become a Reseller by choosing either of the two options:</br>a. Calling cards (PIN).</br> b. Your own panel.</p></li>
		        
		        <li><span>What is calling card (PIN)?</span><p>Ever seen those scratch cards, where you get a number or code after scratching. Calling cards are very much similar to that. It is a number that has a certain amount. And with this PIN number you can recharge your account or you can resell it to other people.</p></li>
		                
		        <li><span> How can you become a Reseller via Calling cards option?</span><p>In this option, you need to purchase calling cards in bulk (more than 10) and you can then sell them one by one.</p></li>
		               
		        <li><span> How can we generate profit in bulk Calling cards purchase?</span><p> Suppose that the Calling cards price is 100 AED, so you get it in 90 AED only. The rest amount is your profit.</p>
		         </li>
		         
		        <li><span> Is customization in Calling cards price possible? </span><p>Yes, it is possible! If you require 57 AED's Calling card, you will get it.</p></li>
		         
		        <li><span>What is 'Own panel'? </span>
                	<p> In this option you have your own dedicated panel where you can set your own selling price and decide your profit.</p> </li>     
		 </ul>
		</div>

		<!--<h2 class="enjoyGtk small">For more details please contact: business@phone91.com </h2>-->

 	  </section>
	</section>
	<div>hello world</div>
	<!-- //Container --> 
	
	<!-- Footer -->
	<?php //include_once('/inc/footer.php');?>
	<?php include_once('inc/incFooter.php');?>
	<!-- //Footer --> 