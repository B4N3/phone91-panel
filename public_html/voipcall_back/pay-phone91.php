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
<title>Pay Phone91.com |International calling with Phone91</title>
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
	      <h1 class="mianHead">
            <div>Ways to Pay</div>
            <span>Know more about pays</span>
       </h1>
       <div class="met_short_split"><span></span></div>
        <div class="cl db pa backLinks">
            <?php include_once("../inc/login_header.php") ?>
        </div>
	     <span class="clr"></span> </section>
	  </section>
	</div>
	<!-- Features --> 

	<!-- Container -->
	<section id="container ">
	  <section class="innerContainer footerPages">	
			
         <div class="payWrap">	
         			<div class="imagePay"></div>
                   
                   <div class="rightPayCont">
                        <h3 class="pay">Phone 91 provides you a range of options to pay for the services that you use.</h3>
                        <p class="payText">You can currently pay us by using multiple payment options like 
                                  <strong>PayPal or Google checkout.</strong>      
                         </p>
                         <span>Other such payment alternatives are yet to come.</span>
                   </div>
		</div>

 	  </section>
	</section>
	<!-- //Container --> 
	
	<!-- Footer -->
	<?php //include_once('../inc/footer.php');?>
	<?php include_once('../inc/incFooter.php');?>
	<!-- //Footer --> 