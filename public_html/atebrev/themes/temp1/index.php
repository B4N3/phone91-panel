<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';

//User Login Submit Code
if(isset($_REQUEST['submit']))
{
        $userid = $_REQUEST['uname'];
        $pwd = $_REQUEST['pwd'];
	$remember_me = $_REQUEST['rememberMe'];
	$funobj->login_user($userid,$pwd,$remember_me);
	exit();	
}

//include Subsite Class
//include_once(CLASS_DIR."/subsite_class.php");
//$subsite=new subsite_class();

//Grep subsite reseller and set session of resellerID
//$subsite->setResellerSession($_SERVER['HTTP_HOST']);
//var_dump($_SESSION);

if($_SESSION['res_id']!=2&&$_SESSION['style']!='')
{
    //Redirect to login.php page for white lable site
	header("Location: login.php");
	exit();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--    Meta For google site verification -->
<meta name="google-site-verification" content="EXNE2Yl28ykKGBxuVVPOi5xak8uKFWQuf4-_8NlOrDQ" />
<title><?php echo _COMPANY_NAME_;?> | Gtalk to phone calls</title>
<meta name="keywords" content="<?php echo _HOME_KEYWORD_;?>" />
<meta name="description" content="<?php echo _HOME_DESCRIPTION_;?>" />

<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Include Head Page-->
<?php include_once(_THEME_PATH_.'/inc/incHead.php'); ?>
<!-- Signup validation Js -->
<script type="text/javascript" src="/js/cobj.js"></script>
</head>
<body>
    
<?php include_once(_THEME_PATH_.'/inc/incHeader.php'); ?>
<!-- //Header -->
<!-- Features -->
<?php
//Check if user already logged-in 
	if(!$funobj->login_validate()){?>
<!--div use to show notification  Don't delete as this is very useful for showing notification  -->

<div id="notification"> </div>
<div class="motion" style="display:none;"></div>
	<div class="mainFeaturesWrapper">
	    <section id="featuresWrap">
	    	<section class="wrapper pr">        	
	            <div class="searchFields fl col2">
	               <h1>Welcome to my Site</h1>
<!--	               <h2>Reseller line 2................</h2>-->
                   
	               <form method="POST" action="signup.php" id="signupForm" onsubmit="return register();">
                   	<div class="fields">
                    
                            <input type="text" id="username"   name="username" value="" onblur="check_user_exist()"  placeholder="Choose Username" />
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
                      	<input type="submit" title="Get Started and Create my Account" value="Get Started and Create my Account" name="submit" class="rdbtn"/>
                    </div>
                        
                    <div class="byigning">
                        <span>By signing up, you agree to the</span>
                        <a href="/voipcall/terms.php" class="blackShade whClr">Terms of Use</a>
                        <span>and</span>
                        <a href="/voipcall/privacy.php" class="blackShade whClr">Privacy Policy</a>
                    </div>  
	              </form>
	            </div>
	            <div class="bannerImg"><img src="<?php echo _THEME_PATH_.'/images/1.png';?>"   alt="" title="" /></div>
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
             <?php include_once("pricingInc.php"); ?>
            <script type="text/javascript" src="/js/priceInc.js"></script>
<!-- Header -->
<!--             <div class="head">See How Little It Costs To Call</div><span class="section-title-bullet"></span>-->
              
<!--        	 <div class="formGet pr">
                <input type="text" id="search" name="" placeholder="Type your country name" class="fs3 bgTrans fl bdrN" onfocus="(this.value == 'Type your country name') && (this.value = '')"   onblur="(this.value == '') && (this.value = 'Type your country name')" onkeyup="searchPrice();"/>
                <input type="submit" title="Search" value="" class="fr crs bgTrans bdrN" />
                <span class="seachIcons db fl pa crs" onclick="showprice()"></span>
                <span class="clr"></span>
               
             </div>
             <h4 class="get">Get Unlimited international calling at local rates And…a chance to surprise your 
             family/friends sitting at a faraway place.</h4>-->

                <span class="clr"></span>
            </div>
            
            <div id="internationalCall">
            	<!--dyamic content comes here don't change this-->
            </div>
            <div class="cl"></div>
        </section>
    </section>
     
    <?php include_once(_THEME_PATH_.'/inc/incFooter.php');?>
    <script type="text/javascript">
    
        
    
    renderBankDetails("");
    searchPrice(<?php echo _TARIFF_PLAN_; ?>);
        
   
    
    </script>

</body>
</html>