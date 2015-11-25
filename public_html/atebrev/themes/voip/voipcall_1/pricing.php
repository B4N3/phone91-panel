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

<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]-->

<!--[if !IE]><!--><!-- COMMENT on 15 april <link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /> --><!--<![endif]-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<!-- <script language="javascript" type="text/javascript" src="../js/jquery.colorbox.js"></script>
<script language="javascript" type="text/javascript">

$(document).ready(function(){

$(".register").colorbox();//initilisation of colorbox

});</script> -->

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
               <h1 class="mianHead fs1 whClr rglr"><div class="fl">Pricing</div><span class="f16 db fl mrL1" style="width:86%;">Know more about Pricing</span></h1>
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
                <input type="submit" title="Search" value="" class="fr crs bgTrans bdrN" />
                <span class="seachIcons db fl pa crs"></span>
                <span class="clr"></span>
             </div>
             <h4 class="rglr grayClr2 f16 mrB1">Get Unlimited international calling at local rates And…a chance to surprise your 
             family/friends sitting at a faraway place.</h4>
             
             <div id="stcnt">             	
                <div class="col4 fl">
                <aside class="national mrR1">
                  <img src="../images/flags/in.png" width="48" height="48" alt="" title="" class="fl mrT1 mrR1" />
                  <div class="fl rglr f10 pdR5">
                    <a href="javascript:void(0)" class="db lh48" id='india' val='in'>INDIA</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">0.0252 <span class="fs4 lh30">cents/min</span></span>
                  </div>
                </aside> 
                </div>
                
                <div class="col4 fl">
            	<aside class="national mrR1">
                  <img src="../images/flags/ae.png" width="48" height="48" alt="" title="" class="fl mrT1 mrR1" />
                 <div class="fl rglr f10 pdR5">
                    <a href="javascript:void(0)" class="db lh48" id='uae'>UAE</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">0.1584 <span class="fs4 lh30">cents/min</span></span>
                  </div>
                </aside> 
                </div>
                
                <div class="col4 fl">
                <aside class="national mrR1">
                   <img src="../images/flags/us.png" width="48" height="48" alt="" title="" class="fl mrT1 mrR1" />
                  <div class="fl rglr f10 pdR5">
                    <a href="javascript:void(0)" class="db lh48" id='usa'>USA</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">0.0120 <span class="fs4 lh30">cents/min</span></span>
                  </div>
                </aside>
                </div>
                
                <div class="col4 fl">
                <aside class="national">
                   <img src="../images/flags/gb.png" width="48" height="48" alt="" title="" class="fl mrT1 mrR1" />
                 <div class="fl rglr f10 pdR5">
                    <a href="javascript:void(0)" class="db lh48" id='uk'>UK</a>                    
                    <span class="clr"></span>
                  <span class="grayClr2 db">0.0120 <span class="fs4 lh30">cents/min</span></span>
                  </div>
                </aside>
                </div>
                <span class="clr"></span>
            </div>
            
            <div id="internationalCall" class="mrB2">
            	<!--dyamic content comes here don't change this-->
            </div>
            <div class="cl"></div>
        </section>
    </section>
  <!-- //Container -->           
 
<script id="rate" type="text/template">
div.oh
	img.fl.db(src="/images/flags/#{flag}.png", width="48", height="48")
	div.fl.fs2.lh48.mrL1 Rates for #{country}
each rate, i in rates
	div.col4.fl
		aside.pd1.mrR1.mrB1.bgWh.taC
			div.fs3=i
			div.fs3.gryClr #{rate} 
				span.fs5 USD/min
div.clr
</script>		 
<script type="text/javascript">
    $(".lh48").click(function(){
        searchByCountry(this.id);
     });
</script>

<!-- Footer -->     
  <?php include_once('../inc/incFooter.php');?>
<!-- //Footer -->    
