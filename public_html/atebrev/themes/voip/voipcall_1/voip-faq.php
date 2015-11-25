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
<!-- <script language="javascript" type="text/javascript" src="../js/jquery.colorbox.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
	$(".register").colorbox();//initilisation of colorbox
	});
</script>
<script type="text/javascript" src="../js/jcom.js"></script> -->
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
	        <div class="fl">FAQs</div>
	        <span class="f16 db fl mrL1" style="width:89%;"> Phone91's FAQ's</span> </h1>
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
		
			
		  <p class="f16 blackCOlor pdB3">Most commonly questions which are misunderstood by many of us, 
          	if you feel any of your question or doubt remains unanswered do not hesitate to contact us.</p>
		
          <!-- Accodians -->
			 <div class="accordion">	
        
				 <h3 class="fs3 rglr pdB innerpageHeading active" id="tab1"><a href="javascript:void(0)">What is VoIP?</a></h3>
				 <div>
	             	  <p class="pdB3">VoIP stands for Voice over Internet Protocol or Voice Over IP. VoIP is the technique by 
	           	 	  which you convert your analog signal into digital data, and thereby transmitting it over Internet.</p>
				 </div>
	             
	             
	        
				<h3 class="fs3 rglr pdB innerpageHeading" id="tab2"><a href="javascript:void(0)">How does VoIP work?</a></h3>
	            <div style="display:none">
				  <p class="pdB3">
			        		A VoIP is a gateway that's converts your voice into data packets, which is then send over a broadband internet 
	           	  connection to our servers and which is forwarded to calling party.</p>
	            </div>
			 
	         
	         
				<h3 class="fs3 rglr pdB innerpageHeading" id="tab3"><a href="javascript:void(0)">How can I use VoIP?</a></h3>
	           	 <div style="display:none">
					<p class="pdB3">You may use VoIP anywhere you want; you have to download any of our dialers i.e. either mobile or desktop dialers through
		            	 which you may make a call. We also support calling through Gtalk.</p>
	             </div>
			
			
				<h3 class="fs3 rglr pdB innerpageHeading" id="tab4"><a href="javascript:void(0)">May I use mobile phone to call via VoIP?</a></h3>
				<div style="display:none;">	
	            	<p class="pdB3">Yes you may use our mobile dialer to call through VoIP.</p>
				</div>
	            
	            
				<h3 class="fs3 rglr pdB innerpageHeading" id="tab5"><a href="javascript:void(0)">May I call 911 or emergency services through phone91?</a></h3>
	            <div style="display:none;">            	
	      			 <p class="pdB3">No, you may not use our services to call either 911 or any of emergency services.</p>
	            </div>
	        
    	  </div>   <!-- //Accodians -->
	 </section>
	</section>
	<!-- //Container --> 
	
	<!-- Footer -->
	<?php //include_once('../inc/footer.php');?>
	<?php include_once('../inc/incFooter.php');?>
	<!-- //Footer --> 
	<!--  Accordians -->
	<script type="text/javascript">
	 $(document).ready(function(){        
	    
	    $(".accordion h3").click(function() {
	        $(this).toggleClass('active').next('div').slideToggle('fast')
	      });
	 
	});
	</script>
	