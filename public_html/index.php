<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/whiteLabelConfig.php';

/**
 * @author: Rahul <rahul@hostnsoft.com>
 * @since 07 aug 2013
 * @package Phone91
 * @lastUpdate 
 */

//User Login Submit Code
if(isset($_REQUEST['submit']))
{
        $userid = $_REQUEST['uname'];
        $pwd = $_REQUEST['pwd'];
	$remember_me = $_REQUEST['rememberMe'];
        if(isset($_REQUEST['macAddress']))
        $usermacAddress = $_REQUEST['macAddress'];
        else
        $usermacAddress = NULL; 
        
        if(strtolower($userid) == 'voipreseller')
        {
           session_start(); session_unset();session_destroy();session_start();
           $_SESSION['error'] = "Invalid User Please try again later";
           header("location: http://".$_SERVER['HTTP_HOST']."?error=".$_SESSION['error']);
           exit();
        }
    
	$funobj->login_user($userid,$pwd,$remember_me,$_SERVER['HTTP_HOST'],'','' , '' , '' ,$usermacAddress);
	exit();	
}

//include Subsite Class
//include_once(CLASS_DIR."/subsite_class.php");
//$subsite=new subsite_class();

//Grep subsite reseller and set session of resellerID
//$userData = $subsite->setResellerSession($_SERVER['HTTP_HOST']);
//var_dump($_SESSION);



if(!is_dir(ROOT_DIR."/themes/"._DOMAIN_THEME_) || !file_exists(ROOT_DIR."/themes/"._DOMAIN_THEME_."/index.php"))
{
 
        include_once(ROOT_DIR."/loginb.php");
        exit();
}
else
{
 
    include_once(ROOT_DIR."/themes/"._DOMAIN_THEME_."/index.php");
//    include_once(ROOT_DIR."themes/"._DOMAIN_THEME_."/index.php");
    exit();
}

?>
<!--<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    Meta For google site verification 
<meta name="google-site-verification" content="EXNE2Yl28ykKGBxuVVPOi5xak8uKFWQuf4-_8NlOrDQ" />
<title>Phone91 | Gtalk to phone calls</title>
<meta name="keywords" content="Gtalk to phone calls, mobile to phone call, international phone calls, internet phone calls, long distance calling, international phone call, long distance calls, cheap calling cards, call overseas, calling overseas, cheap international phone calls, sip calls, VoIP international calls, mobile VoIP calls, cheap pc to phone calls." />
<meta name="description" content="voip91 is an international company providing phone calls and International phone calls. Better voice quality and hassle free billing. Test now." />

 <script type="text/javascript" src="js/html5.js"></script> 
[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]

 Include Head Page
<?php // include_once('inc/incHead.php'); ?>
 Signup validation Js 
<script type="text/javascript" src="js/sign_up.js"></script>
<script type="text/javascript" src="js/pricing.js"></script>
<script type="text/javascript" src="js/cobj.js"></script>
<script type="text/javascript" src="js/jcom.js"></script>
<script>

function formPost()
{
    console.log("1231");
    
    
    var formdata = $('#signupForm').serialize();
    $.ajax({
    url : "/controller/signUpController.php",
    type : "POST",
    data : formdata,
    dataType:"JSON",
    success: function(response)
    {
        show_message(response.msg,response.status);
        if(response.status == "success")
        {
            window.location.href = "/userhome.php";
        }
        
    }
    })
}

</script>
</head>
<body>
 Header 
<?php // include_once('inc/incHeader.php'); ?>
 //Header 
 Features 
<?php
//Check if user already logged-in 
//	if(!$funobj->checkSession()){?>
	<style>
        #notification { position:absolute; top:0; left:0; right:0; z-index:999999999; color:#fff; font-size:20px; display:none; text-transform:capitalize;}
        #notification div { height:30px; padding:15px 30px; line-height:30px; }
        #notification i { margin:5px 5px 0 0; }
        #notification .success, #notification .warning, #notification .error, #notification .information { display: block; }
        #notification .success { background:#1fbaa6 }
        #notification .warning { background:#ffcc00; }
        #notification .error { background:#ff5c33; }
        #notification .information { background:#30a6d9; }
        .motion {  background: url("../images/Phone91-preloader.png") no-repeat;    bottom: 32px;    height: 174px;    position: absolute;    right: 330px;    width: 150px;    z-index: 9999999;}
    </style>
div use to show notification  Don't delete as this is very useful for showing notification  
hehehehh
<div id="notification"> </div>
<div class="motion" style="display:none;"></div>
	<div class="mainFeaturesWrapper">
	    <section id="featuresWrap">
	    	<section class="wrapper pr">        	
	            <div class="searchFields fl col2">
	               <h1>Get 10% Extra on Call Rates</h1>
	               <h2>Sign up and Get 10% Extra on Call Rates</h2>
                   
                       <form method="POST" action="" id="signupForm" >
                   	<div class="fields">
                    
                            <input type="text" id="username"   name="username" value="" onblur="check_user_exist()"  placeholder="Choose Username" />
                            <input type="hidden" id="call"   name="call" value="signUpUser" />
                            <div class="msg pa"></div>
		            </div>
                    
	              <div class="fields">
                    <input type="password" id="password"  name="password" value="" placeholder="Password" onblur="check_password_strength();"/>
                   
	                <div class="msg pa"></div>
                 </div>   
                    
                  <div class="fields">
	                    <input type="email" id="email"  value="" name="email" placeholder="Email Id" onkeyup="check_email_exist()"/>
                        <div class="msg pa"></div>
                  </div>
                  
                  <div class="fields">
                      <input type="button" onclick="formPost()" title="Get Started and Create my Account" value="Get Started and Create my Account" name="submit" class="rdbtn"/>
                    </div>
                        
                    <div class="byigning">
                        <span>By signing up, you agree to the</span>
                        <a href="/voipcall/terms.php" class="blackShade whClr">Terms of Use</a>
                        <span>and</span>
                        <a href="/voipcall/privacy.php" class="blackShade whClr">Privacy Policy</a>
                    </div>
                    
                    <div class="fs2 db mrT1 taC">Or</div>        
                    <div class="signUp">
                        <span>Sign Up with </span>
                        <a class="whClr mrL" href="login/login-fb.php" title="Sign Up with Facebook" target="_blank">Facebook</a>
                        <span class="mrL mrR">/</span> 
                        <a class="whClr" href="login/login-google.php" title="Google" target="_blank">Google</a>
                    </div>       
	              </form>
	            </div>
	            <div class="bannerImg"><img src="images/bannerimg_3.png" width="475" height="474" alt="" title="" /></div>
	            <span class="clr"></span>            
	        </section>
	    </section>
	</div>
	<?php // }?>
     //Features 
    
     Container 
    <section id="container">
    	<section class="innerContainer mar0auto pdT4">
             <div class="head">See How Little It Costs To Call</div><span class="section-title-bullet"></span>
        	 <div class="formGet pr">
                <input type="text" id="search" name="" placeholder="Type your country name" class="fs3 bgTrans fl bdrN" onfocus="(this.value == 'Type your country name') && (this.value = '')"   onblur="(this.value == '') && (this.value = 'Type your country name')" onkeyup="searchPrice();"/>
                <input type="submit" title="Search" value="" class="fr crs bgTrans bdrN" />
                <span class="seachIcons db fl pa crs" onclick="showprice()"></span>
                <span class="clr"></span>
             </div>
             <h4 class="get">Get Unlimited international calling at local rates And…a chance to surprise your 
             family/friends sitting at a faraway place.</h4>
             
             <div id="stcnt">             	
                <div class="col4 fl">
                <aside class="national">
                  <img src="../images/flags/in.png" width="48" height="48" alt="" title="" class="fl" />
                  <div class="fl f10 ">
                    <a href="javascript:void(0)" class="db" id='india' val='in'>INDIA</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">1.26 <span class="fs4">cents/min</span></span>
                  </div>
                </aside> 
                </div>
                
                <div class="col4 fl">
            	<aside class="national">
                  <img src="../images/flags/ae.png" width="48" height="48" alt="" title="" class="fl" />
                 <div class="fl f10">
                    <a href="javascript:void(0)" class="db" id='uae'>UAE</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">15.84 <span class="fs4">cents/min</span></span>
                  </div>
                </aside> 
                </div>
                
                <div class="col4 fl">
                <aside class="national">
                   <img src="../images/flags/us.png" width="48" height="48" alt="" title="" class="fl" />
                  <div class="fl  f10">
                    <a href="javascript:void(0)" class="db" id='usa'>USA</a>                    
                    <span class="clr"></span>
                    <span class="grayClr2 db">1.2<span class="fs4">cents/min</span></span>
                  </div>
                </aside>
                </div>
                
                <div class="col4 fl">
                <aside class="national">
                   <img src="../images/flags/gb.png" width="48" height="48" alt="" title="" class="fl" />
                 <div class="fl  f10">
                    <a href="javascript:void(0)" class="db" id='uk'>UK</a>                    
                    <span class="clr"></span>
                  <span class="grayClr2 db">1.2 <span class="fs4">cents/min</span></span>
                  </div>
                </aside>
                </div>
                <span class="clr"></span>
            </div>
            
            <div id="internationalCall">
            	dyamic content comes here don't change this
            </div>
            <div class="cl"></div>
        </section>
    </section>
     <div class="howItsWorks mar0auto">
        <div class="head">See How It Works</div>
        <span class="section-title-bullet"></span>
         <div class="vidoesPhone"> 
             <iframe width="676" height="442" src="https://www.youtube.com/embed/VG2OnN_mMVo?rel=0" frameborder="0" allowfullscreen></iframe>
         </div>       
    </div>
    <?php // include_once('inc/incFooter.php');?>
    
    Jade template for rate search 
<script id="rate" type="text/template">
div.oh
	img.fl.db(src="/images/flags/#{flag}.png", width="48", height="48")
	div.fl.fs2.imgspace.mrL1 Rates for #{country}
			span.prices USD/min
each rate, i in rates
	div.col4.fl
		aside.pd1.mrR1.mrB1.bgWh.taC
			div.title=i
			div.numb #{rate} 
				
div.clr
</script>
<script type="text/javascript">
    $(".lh48").click(function(){
        searchByCountry(this.id);
     });

var storage = window['localStorage'];
 if(storage.getItem('menu')){
      storage.removeItem('menu');
}



</script>-->