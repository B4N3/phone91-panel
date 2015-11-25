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
<title>Pricing Phone91.com |International calling with Phone91</title>
<meta name="keywords" content="Phone91 is a leading International call provider. Phone91 provides various medium for making cheap international calls and long distance calls." />
<meta name="description" content="cheap international calls,  long distance calls." />
<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]-->
<!--[if !IE]><!--><!-- COMMENT on 15 april <link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /> --><!--<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]-->
<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php include_once('../inc/voipcallhead.php'); ?>
<?php include_once('../inc/incHead.php'); ?>
<script type="text/javascript" src="../js/pricing.js"></script>
<script type="text/javascript" src="../js/cobj.js"></script>
</head>

<body>

	<!-- Header -->
	<?php include_once('../inc/incHeader.php'); ?>
	<!-- //Header -->

   <!-- Features -->
	<div class="mainFeaturesWrapper">
	    <section id="featuresWrap" class="noBanner">
	    	<section class="innerBanner pr">               
               <h1 class="mianHead"><div class="fl">Pricing</div>
               		<span>Know more about Pricing</span>
               </h1>
               <div class="met_short_split"><span></span></div>
               <div class="cl db pa backLinks"><?php include_once("../inc/login_header.php") ?></div>
            <span class="clr"></span>
            </section>
        </section>
   </div>
   <!-- Features -->            
           
   <!-- Container -->        
    <section id="container">
    	<section class="innerContainer mar0auto pdT4">
             <h3 class="gryClr fs2 rglr">See How Little It Costs To Call </h3>
        	 <div class="formGet pr">
                <input type="text" id="search" name="" value="Type your country name" class="fs3 bgTrans fl bdrN" onfocus="(this.value == 'Type your country name') && (this.value = '')"   onblur="(this.value == '') && (this.value = 'Type your country name')" onkeyup="searchPrice();"/>
                <input type="submit" title="Search" value="" />
                <span class="seachIcons db fl pa crs"></span>
                <span class="clr"></span>
             </div>
          <!--  <h4 class="get">Get Unlimited international calling at local rates And…a chance to surprise your 
          //   family/friends sitting at a faraway place.</h4>
    -->         
             <div id="stcnt">             	
                <div class="col4 fl">
                <aside class="national">
                  <img src="../images/flags/in.png" width="48" height="48" alt="" title="" class="fl" />
                  <div class="fl f10">
                        <a href="javascript:void(0)" class="db" id='india' val='in'>INDIA</a>                    
                        <span class="clr"></span>
                        <span class="grayClr2 db">1.26 <span class="fs4">cents/min</span></span>
                  </div>
                </aside> 
                </div>
                
                <div class="col4 fl">
            	<aside class="national">
                  <img src="../images/flags/ae.png" width="48" height="48" alt="" title="" class="fl" />
                 <div class="fl rglr f10 pdR5">
                    <a href="javascript:void(0)" class="db" id='uae'>UAE</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">16.34 <span class="fs4">cents/min</span></span>
                  </div>
                </aside> 
                </div>
                
                <div class="col4 fl">
                <aside class="national">
                   <img src="../images/flags/pk.png" width="48" height="48" alt="" title="" class="fl" />
                  <div class="fl  f10">
                    <a href="javascript:void(0)" class="db" id='pakistan'>Pakistan</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">11 <span class="fs4">cents/min</span></span>
                  </div>
                </aside>
                </div>
                
                <div class="col4 fl">
                <aside class="national">
                   <img src="../images/flags/kw.png" width="48" height="48" alt="" title="" class="fl" />
                 <div class="fl  f10">
                    <a href="javascript:void(0)" class="db" id='kuwait'>Kuwait</a>                    
                    <span class="clr"></span>
                  <span class="grayClr2 db">9.40 <span class="fs4">cents/min</span></span>
                  </div>
                </aside>
                </div>
                <span class="clr"></span>
            </div>
            
            <div id="internationalCall">
            	<!--dyamic content comes here don't change this-->
            </div>
            <div class="cl"></div>
        </section>
    </section>
  <!-- //Container -->           
 
 <!-- <script id="rate" type="text/template">
div.oh
	img.fl.db(src="/images/flags/#{flag}.png", width="48", height="48")
	div.fl.fs2.imgspace  Rates for #{country}
				span.fs5.prices USD/min
each rate, i in rates
	div.col4.fl
		aside.bgWh.taC
			div.title=i
			div.numb #{rate} 
div.clr
</script>		 -->
<!-- <script type="text/javascript">
    $(".lh48").click(function(){
        searchByCountry(this.id);
     });
</script>-->

<!-- Footer -->     
  <?php include_once('../inc/incFooter.php');?>
<!-- //Footer -->    