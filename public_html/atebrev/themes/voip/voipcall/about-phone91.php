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
<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" >

<title>About Phone91.com |International calling with Phone91</title>
<meta name="keywords" content="Phone91 is a leading International call provider. Phone91 provides various medium for making cheap international calls and long distance calls." />
<meta name="description" content="cheap international calls,  long distance calls." />
<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]-->
<!--[if !IE]><!--><!-- COMMENT on 15 april <link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /> --><!--<![endif]-->
<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- 
<script language="javascript" type="text/javascript" src="../js/jquery.colorbox.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
	$(".register").colorbox();//initilisation of colorbox
	});
</script>
<script type="text/javascript" src="../js/jcom.js"></script> -->
<?php //include_once('../inc/voipcallhead.php'); ?>
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
                    <div>What Phone91 Is?</div>
                    <span>About Phone91</span>
     		  </h1>
    		   <div class="met_short_split"><span></span></div>
      		   <div class="cl db pa backLinks"><?php // include_once("../inc/login_header.php") ?></div>
            </section>
        </section>
   </div>
   <!-- Features -->            
    
	<!-- Container -->        
    <section id="container">
       <section class="innerContainer   footerPages">
			
            	<div class="desCript">
                      <h3 class="innerHeadIcn">Phone91</h3>    
                        <p>Established in 2008, phone91 has become a leading Easy, Hassle free VoIP solution provider 
                                from India. It has provided with many VoIP options to call globally or for calling India, UAE etc. There are many ways to make 
                                a Internet call which includes Mosip, Itel ,Gtalk. Etc.</p>
                </div>
               
              <div class="desCript">
                    <h3 class="innerHeadIcn">About VoIP Services</h3>
                    <p>VoIP stands for Voice over Internet Protocol. In this technology Voice has been transmitted via 
                    Internet i.e. the voice single in converted  into data signal and send via our ISP modems. Due to the cost effectiveness of VoIP it 
                    has shown a tremendous growth in both small as  well large scale industries.</p>
    
                    <p>Calling to any phone with VoIP is very cost effective. It has benefits in calling to your residents, 
                    business and big companies. As it is a prepaid account, therefore you need not need to verify about extensive charges or billing, 
                    moreover you also get a second billing. VoIP has got many benefits like it much better than calling cards. While using VoIP calling 
                    you get a simple and easy to use user panel, whereas other things are bear by phone91 like technical details, infrastructure, server 
                    management etc.</p>
               </div>
               
				<div class="desCript">
                    <h3 class="innerHeadIcn">Benefits of VoIP</h3>
                     <ul class="listIng">
                            <li>Companies do not need to change their phone systems. A gateway is used behind existing telephone system, which routes 
                            the calls over VoIP.</li>
                            <li>Quality calls over IP, when you ask for quality its similar to any other PSTN (Public switching telephone network).</li>
                            <li>Some user thinks that Installation is costly and technically complex to install. As an end user, installation and 
                            other things are bear by phone91.com. You will get a simple user panel which is very easy to use for calling.</li>
                            <li>Rates are similar to PSTN rates: This is not true, calls made via VoIP are very cheap than PSTN.</li>
                        </ul>
				</div>
	   </section>
    </section>
  <!-- //Container -->           
 
	<!-- Footer -->   
	  <?php //include_once('../inc/footer.php');?>
	  <?php include_once('../inc/incFooter.php');?>
	<!-- //Footer -->    
	<script type="text/javascript" src="../js/jquery.form.js"></script>
