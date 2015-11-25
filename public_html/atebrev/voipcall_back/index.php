<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
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
<meta name="google-site-verification" content="EXNE2Yl28ykKGBxuVVPOi5xak8uKFWQuf4-_8NlOrDQ" />
<meta name="google-site-verification" content="EXNE2Yl28ykKGBxuVVPOi5xak8uKFWQuf4-_8NlOrDQ" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Phone91 | Gtalk to phone calls</title>
<meta name="keywords" content="Gtalk to phone calls, mobile to phone call, international phone calls, internet phone calls, long distance calling, international phone call, long distance calls, cheap calling cards, call overseas, calling overseas, cheap international phone calls, sip calls, VoIP international calls, mobile VoIP calls, cheap pc to phone calls." />
<meta name="description" content="voip91 is an international company providing phone calls and International phone calls. Better voice quality and hassle free billing. Test now." />

<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php include_once('inc/incHead.php'); ?>
<style>

</style>
<script type="text/javascript" src="js/sign_up.js"></script>
<script type="text/javascript" src="js/pricing.js"></script>
<script type="text/javascript" src="js/cobj.js"></script>
</head>
<body>
<!-- Header -->
<?php include_once('inc/incHeader.php'); ?>
<!-- //Header -->
<!-- Features -->
<?php
	if(!$funobj->login_validate()){?>
    <div class="bar"></div>
	<div class="mainFeaturesWrapper">
	    <section id="featuresWrap">
	    	<section class="innerBanner pr">        	
	            <div class="searchFields fl col2">
	               <h1 class="fs1 whClr fwB shaddow rglr">Get 10% Extra on Call Rates</h1>
	               <h2 class="openRegu fs3 whClr">Sign up and Get 10% Extra on Call Rates</h2>
	               <form method="POST" action="signup.php" id="signupForm" onsubmit="return validate_data();">
                   	<div class="pr">
	                	<input type="text" id="username" class="openRegu f16" name="username" value="" onblur="check_user_exist()" onkeyup="check_user_exist()" placeholder="Choose Username" />
						<div class="msg pa"></div>
		            </div>
	                    <input type="password" id="password" class="openRegu f16 mrT3" name="password" value="" placeholder="Password" onblur="check_password_strength();"/>
	                     <div class="clr"></div>
                    <div class="pr">     	                	                    
	                    <input type="email" id="email" class="openRegu f16 mrT3"  value="" name="email" placeholder="Email Id" onblur="check_email_exist()"/>
				       <div class="msg pa"></div>
       				</div>                    
	                    
                        
                    <div class="pr mrT3"> 
                      	<input type="submit" title="Get Started and Create my Account" value="" name="submit" class="crs mrT bdrN"/>
                    </div>
                        
                    <div class="f11 openRegu underLine">
                        <span>By signing up, you agree to the</span>
                        <a href="/voipcall/terms.php" class="blackShade whClr">Terms of Use</a>
                        <span>and</span>
                        <a href="http://phone91.com/voipcall/privacy.php" class="blackShade whClr">Privacy Policy</a>
                    </div>
                    
                    <div class="fs2 db mrT1 taC">Or</div>        
                    <div class="signUp rglr taC" style="font-size:22px;">
                        <span>Sign Up with </span>
                        <a class="whClr mrL" href="login/login-fb.php" title="Sign Up with Facebook" target="_blank">Facebook</a>
                        <span class="mrL mrR">/</span> 
                        <a class="whClr" href="login/login-google.php" title="Google" target="_blank">Google</a>
                    </div>       
	              </form>
	            </div>
	            <div class="bannerImg fr pa mr3 r3"><img src="images/bannerimg.png" width="532" height="428" alt="" title="" /></div>
	            <span class="clr"></span>            
	        </section>
	    </section>
	</div>
	<?php }?>
    <!-- //Features -->
    
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
                  <img src="../images/flags/in.png" width="48" height="48" alt="" title="" class="fl mrT1 mrR5" />
                  <div class="fl rglr f10 pdR5">
                    <a href="javascript:void(0)" class="db lh48" id='india' val='in'>INDIA</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">0.0252 <span class="fs4 lh30">cents/min</span></span>
                  </div>
                </aside> 
                </div>
                
                <div class="col4 fl">
            	<aside class="national mrR1">
                  <img src="../images/flags/ae.png" width="48" height="48" alt="" title="" class="fl mrT1 mrR5" />
                 <div class="fl rglr f10 pdR5">
                    <a href="javascript:void(0)" class="db lh48" id='uae'>UAE</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">0.1584 <span class="fs4 lh30">cents/min</span></span>
                  </div>
                </aside> 
                </div>
                
                <div class="col4 fl">
                <aside class="national mrR1">
                   <img src="../images/flags/us.png" width="48" height="48" alt="" title="" class="fl mrT1 mrR5" />
                  <div class="fl rglr f10 pdR5">
                    <a href="javascript:void(0)" class="db lh48" id='usa'>USA</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">0.0120 <span class="fs4 lh30">cents/min</span></span>
                  </div>
                </aside>
                </div>
                
                <div class="col4 fl">
                <aside class="national">
                   <img src="../images/flags/gb.png" width="48" height="48" alt="" title="" class="fl mrT1 mrR5" />
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