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
include_once(CLASS_DIR."/subsite_class.php");
$subsite=new subsite_class();

//Grep subsite reseller and set session of resellerID
$subsite->setResellerSession($_SERVER['HTTP_HOST']);
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
<title>Phone91 | Gtalk to phone calls</title>
<meta name="keywords" content="<?php echo _CONTACT_KEYWORD_;?>" />
<meta name="description" content="<?php echo _CONTACT_DESCRIPTION_;?>" />

<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Include Head Page-->
<?php include_once(_THEME_PATH_.'/inc/incHead.php'); ?>
<!-- Signup validation Js -->

</head>
<body>
<!-- Header -->
<?php include_once(_THEME_PATH_.'/inc/incHeader.php'); ?>
<!-- //Header -->
<!-- Features -->
<?php
//Check if user already logged-in 
	if(!$funobj->login_validate()){?>
<!--div use to show notification  Don't delete as this is very useful for showing notification  -->

<div id="notification"> </div>
<div class="motion" style="display:none;"></div>
	<div class="mainFeaturesWrapper" style="height:550px;">
	    <section id="featuresWrap">
	    	<section class="wrapper pr">        	
	            <div class="searchFields fl col2">
	               <h1><?php echo _CONTACT_BANNER_HEADING_; ?></h1>
	               <h2><?php echo _CONTACT_BANNER_SUB_HEADING_ ?></h2>
                   
	               <form method="POST" action="" id="contactForm" onsubmit=""  class="text2">
                   	<div >
                    <a class="textc"> Name : </a>
                            <input type="text" id=""   name="" value="" onblur="check_user_exist()"  placeholder="Name" style=" margin-bottom:10px !important;"  />
                            <div class="msg pa"></div>
		            <a class="textc" > Email : </a>
                    <input type="email" id=""  name="email" value="" placeholder="Email" onblur="" style=" margin-bottom:10px !important;" />
                 <a class="textc"> Phone Number : </a>
	                    <input  type="text" id=""  value="" name="phone" placeholder="Phone Number" onkeyup=""  style=" margin-bottom:10px !important;"  />
	                    <a class="textc"> Commnet : </a>
                        <textarea rows="4"></textarea>
                      	<input type="submit" title="Get Started and Create my Account" value="submit" name="submit" class="rdbtn"/>
                    </div>

	              </form>
	            </div>
	            <div class="contactpage fr" >
                <li> <strong>Email :</strong> <?php echo _CONTACT_EMAIL_; ?> </li>
                <li> <strong>Phone :</strong> <?php echo _CONTACT_PHONE_NO_; ?> </li>
<!--                <li> <strong>MSN :</strong> shubhendra@hostnsoft.com </li>
                <li> <strong>Skype :</strong> shubhendra@hostnsoft.com </li>
                <li> <strong>Gtalk :</strong> shubhendra@hostnsoft.com </li>-->
                
                </div>
	            <span class="clr"></span>            
	        </section>
	    </section>
	</div>
	<?php }?>
    <!-- //Features -->
    
    <!-- Container -->
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

                <span class="clr"></span>
            </div>
            
            <div id="internationalCall">
            	<!--dyamic content comes here don't change this-->
            </div>
            <div class="cl"></div>
        </section>
    </section>
     
    <?php include_once(_THEME_PATH_.'/inc/incFooter.php');?>
    

