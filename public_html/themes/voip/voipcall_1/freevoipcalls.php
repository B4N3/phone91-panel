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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.colorbox.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
	$(".register").colorbox();//initilisation of colorbox
   });
</script>
<script type="text/javascript" src="../js/jcom.js"></script>
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
	        <div class="fl">Affiliates</div>
	        <span class="f16 db fl mrL1" style="width:83%;"> Phone91's affiliates</span> </h1>
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
        
 		 <ul class="listIng termsListing pdB1 mrB2 lightGray"> 
         	<li>You may get a free balance by referring your friends and family members. You get 25% of amount of first successful recharge. </li>
            <li>You get rewarded for sharing the happiness with your friends and others, refer your friends and pals to phone91 and get 
            25% amount of their first recharge.</li>
            <li>You may promote phone91 by placing banner on your blog/website. You may following banners and text for promoting Phone91</li>
         </ul>

		 <h3 class="fs2 rglr pdB innerpageHeading none">FAQs:</h3>

		 <ul class="listIng termsListing pdB1 mrB2 lightGray paddListing"> 
		 		
                <li><span class="blackCOlor f16">How many friends can I refer?</span><p>You may refer unlimited number of friends, 
                there is no limits in referring an friends.</p></li>
		        
		        <li><span class="blackCOlor f16">Can I refer friends from other countries?</span><p>Yes, you may refer phone91 to unlimited number 
                of friends regardless of there country.</p></li>
		        
		        <li><span class="blackCOlor f16">How can I refer a friends/ Family?</span><p>After login you will get an referral url with your userid, 
		        Anyone who signups with that userid will be considered as your refer.</p></li>
		                
		        <li><span class="blackCOlor f16">How will I know if any of my referrals register for Phone91.com?</span><p>After the successful registration 
                of your referrals, you will get an notification in your user panel.</p></li>
		               
		        <li><span class="blackCOlor f16">How can I use this free Talk time?</span><p>As this amount will be 
                 directly added to your account, you will be able to use it as your normal balance.</p>
		         </li>
		         
		        <li><span class="blackCOlor f16">How will my referral signup?</span><p>Your referral will signup either by using your referral url or
                  by using your user id.</p></li>
		         
		        <li><span class="blackCOlor f16">If one of my friend already received referral link of yours and I also referred him, will I get credit?</span><p>		                     In this case your friend will receive two referral links, one by you and another by another referral. While registering whichever 
                 	link he use, the respective referrer will get benefits.</p> </li>     
		 </ul>

		<h2 class="bluShade  taC">Phone91 shall not be responsible for this scheme or alternate benefits under this scheme.</h2>

 	  </section>
	</section>
	<!-- //Container --> 
	
	<!-- Footer -->
	<?php //include_once('../inc/footer.php');?>
	<?php include_once('../inc/incFooter.php');?>
	<!-- //Footer --> 