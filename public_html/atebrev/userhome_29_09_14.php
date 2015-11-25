<!DOCTYPE HTML>

<html ng-app="phoneApp"><head>
	<?php include_once("analyticstracking.php"); ?>
	<script src="/js/bugsnag-2.min.js" data-apikey="f4ed737f2842fb9634e4f15c11af34ed"></script>
<script src="/js/basket.full.min.js" ></script>
<script type="text/javascript">  
    
    
    if(window.location.hash == "")
        window.location.hash = "!contact.php";
    
    var version = "0.1.1";
    function cacheControl(){

        if(version != localStorage.getItem('version')){
            localStorage.clear();
        }
        localStorage.setItem("version",version);
    }
    cacheControl();


    
    basket.require(
        { url: 'js/group.js' })
        .then(function(){
            basket.require(
//	    {url:'js/jquery-1.9.1.min.map'},
            { url: 'js/panel.js' },
        { url: 'js/website.js' },
        { url: 'js/tourjson.js' });
        })
        
    
    

    

</script>
<!--<script type="text/javascript" src="js/group.js"></script>
<script type="text/javascript" src="js/panel.js"></script>


<script type="text/javascript" src="js/website.js"></script>
<script type="text/javascript" src="js/tourjson.js"></script>-->

    <meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" >
    <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
	<title>Phone91 My Panel</title>
	<link rel="stylesheet" type="text/css" href="/css/panel_style.css"/>
    <link rel="stylesheet" type="text/css" href="/css/header.css"/>
    <link rel="stylesheet" type="text/css" href="/css/responsive.css"/>
	<link rel="stylesheet" type="text/css" href="/css/group.css" />
    <!--link for tour fonts-->
    <link href='https://fonts.googleapis.com/css?family=Handlee' rel='stylesheet' type='text/css'>
    <!--<link rel="stylesheet" type="text/css" href="css/ui-theme.css" />
	<link rel="stylesheet" type="text/css" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery.paginate.css" />-->
    <!--<script src="/js/angular.js"></script>-->  
    <!--[if IE 7]>  
		<link rel="stylesheet" href="ie7.css">  
    <![endif]-->
<style>
.flip-horizontal { -webkit-transform: matrix(-1, 0, 0, 1, 0, 0); -moz-transform: matrix(-1, 0, 0, 1, 0, 0); }
</style>
</head>
<body>
<?php 
/**
 * @author Rahul Chordiya <rahul@hostnsoft.com>
 * @since 1.0 07-aug-2013
 * @package Phone91
 * @details User Home page after-login
 */
//include Common Config File
//include_once 'config.php';
include_once(dirname(__FILE__).'/classes/contact_class.php');
include_once( $_SERVER['DOCUMENT_ROOT'].'/config/whiteLabelConfig.php');

//Validate Login with the help of this function 
if(!$funobj->login_validate()){
        $funobj->redirect("index.php");
}

if($_SESSION["client_type"] == 1)
{
    $funobj->redirect("/admin/index.php#!manage-client.php|manage-client-setting.php");
}

//Include Profile Class for user details

//Call the function to get user balance
$balanceArr = $funobj->getUserBalanceInfo($_SESSION['id']);
$balance = $balanceArr['balance'];
//Grep user balance currency 
$currency =$funobj->getCurrencyName($_SESSION['currencyId']);


//only for testing
$isAdmin = 1;
$isReseller = ($funobj->check_reseller()) ? 1 : 0;
$loginAs = (isset($_SESSION['tempVar']['type'])) ? 1 : 0;
include_once(_MANAGE_PATH_."generalData.php");

    //include google analytic code just after body tag start
include_once($_SERVER['DOCUMENT_ROOT'].'/analyticstracking.php'); 
?>

    
<?php if(isset($_SESSION['client_type']) && $_SESSION['client_type'] != '3')
{
    ?>
<!--<script type="text/javascript" src="js/reseller.js"></script>--> 
<script>
    basket.require(
        { url: 'js/reseller.js' }
        
    );

</script> 
<?php } ?>


<div id="progress" class="waiting" style=""><dt></dt><dd></dd></div>

<div id="notification"> </div>
<div class="motion" style="display:none;"></div>
<!--Small Screen Header-->
<nav id="menu-left" class="dn">
      <ul>
            <li class="main"><span>User</span></li>
              <li><a class="back" href="javascript:dynamicPageName('Contacts');" >Contacts</a></li>
              <li><a href="#!call-log.php" >Call Log</a></li>
              
              <li class="main mrT2"><span>Reseller</span></li>
              <li><a href="#!reseller-transactional-log.php" >Transaction Log</a></li>
              <li><a href="#!reseller-call-log.php" >Call Log</a></li>
              <li><a href="#!reseller-manage-clients.php" >Manage Client</a></li>
              <li><a href="#!reseller-manage-plan.php" >Manage Plans</a></li>
              <li><a href="#!reseller-manage-pins.php" >Manage PINs</a></li>
              <!--<li><a href="#!manage-websites.php" >Manage Website</a></li>-->
              <li class="main mrT2"><span><a href="#!callshop.php">CallShop</a></span></li>
              <!--<li><a  href="#!callshop.php" >Callshop Active Call</a></li>-->
               <!--<li><a  href="http://voip91.com:8082" >Active Call</a></li>-->
               <!--<li><a  href="#!call-log.php" >Call Log</a></li>-->
               <!--<li><a  href="" >Manage IDs</a></li>-->

   	 </ul>
</nav>
<nav id="menu-right" class="dn">
	<ul>
       		<li>
                  <p id="firstName" class="semi mrL2 lh20"><?php echo $_SESSION['username'];?></p>
                  <p id="lastName" class="mrB1 mrL2 lh20"><?php echo $_SESSION['name'];?></p>          
             </li>            
			 <li><span>Balance <?php echo round($balance,3); ?> <?php echo $currency;?></span></li>   
         <?php if(isset($_SESSION['client_type']) && $_SESSION['client_type'] != 4)
       { ?>             
			 <li><a  href="#!setting.php|buymore.php" >Buy Now</a> </li>
       <?php } ?>
             <li><a href="#!transactions.php" >Transactions</a></li>
    		 <li class="paddInner">
             	  <a href="javascript:void(0);" class="fl text back" onclick="window.location.href='#!setting.php|buymore.php'">  
                      <span class="ic-24 setting fl"></span> 
                      <span class="fl text">My Account</span>
                </a>
             </li>
             <li class="paddInner">   
                 <a href="javscript:void(0);">
                        <span class="ic-24 logout fl"></span>
                        <span onClick="var url = window.location.hash;window.location.href='/logout.php?url='+url.substring(1)" class="fl text">Logout</span>  
                  </a>
             </li>
        	 
        </ul>
</nav>
<!--//Small Screen Header-->
<!--Header-->
<div id="header" class="clear">
	 <a href="#menu-left" class="menuLeft"></a><span id="screen" style="position:absolute"></span>
            <a href="#menu-right" class="menuRight friends right"></a>
            <div id="dynamicPageName" class="pageName hidden-desktop"></div>
            
          <!-- Desktop Menus-->
          <div class="deskMenu clear">
				<?php $protocol = $funobj->getProtocol();
				if(isset($_SESSION['tempVar']['type']) && $_SESSION['tempVar']['type'] == 2)
				{				
				?>
				<a id="returnToReseller" class="themeBg" href="<?php echo $protocol.$_SERVER['HTTP_HOST'].'/controller/signUpController.php?call=loginAs&userId='.$_SESSION['tempVar']['mainUser']; ?>" title="Back to reseller"><span class="ic-32 ic-back"></span></a>
				<?php }
				else if(isset($_SESSION['tempVar']['type']) && $_SESSION['tempVar']['type'] == 1)
				{?>
                	<a id="returnToReseller" class="themeBg" href="<?php echo $protocol.$_SERVER['HTTP_HOST'].'/controller/signUpController.php?call=loginAs&type=1&userId='.$_SESSION['tempVar']['mainUser']; ?>" title="Back to admin"><span class="ic-32 ic-back"></span></a>
                                
                    <?php } ?>
                    
          <!--Menus-->
          	<ul class="topmenu">
                 <li id="usersec" class="pr">
                      <div class="clear cp"  onclick="uiDrop(this,'#showUsType', 'true')">
                             <span class="ic-32 mrR1 icClass" id="uSign"></span> 
                             <span class="ic-32 mrR1 icClass" id="rSign" style="display: none;"></span> 
                             <span class="ic-32 mrR1 icClass" id="cSign" style="display: none;"></span> 
                             <span class="ic-16 mrT1 dropsign"></span> 
                      </div>
                      <ul class="dropmenu boxsize ln" id="showUsType">
                          <li id="userMenuLi" class="UserTypeMenu bdrB" menuval="user" icId ="uSign" lPage ="#!contact.php" ><span class="ic-24 userico"></span>User</li>
                          <?php 					  
                          if($isReseller)
                          {?>
                              <li id="resMenuLi" class="UserTypeMenu bdrB" onclick="getActiveCallJs()"menuval="reseller" icId ="rSign" lPage ="#!reseller-manage-clients.php" ><span class="ic-24 resico"></span>Reseller</li>
                              <!--<li id="resMenuLi" class="UserTypeMenu" onclick="" menuval="reseller" icId ="rSign" lPage ="#!reseller-manage-clients.php" >Reseller</li>-->
                              <li id="cShopMenuLi" class="UserTypeMenu" menuval="callshop" icId ="cSign" lPage ="#!callshop.php" ><span class="ic-24 callshop"></span>CallShop</li>
                          <?php
                          }
                          ?>
                     </ul>
                </li>
             </ul>
             <ul class="ln topmenu userTopMenu userMenuLi">
                    <li class="contactLi"><a title="Contacts" class="contact bdrTrns" href="#!contact.php" ><i class="ic-32 mc"></i></a></li>
                    <li class="call-logLi"><a title="Call log" class="bdrTrns" href="#!call-log.php" ><i class="ic-32 cl"></i></a></li>                     
             </ul>
            <?php if($isAdmin==1){?>
             <ul class="ln topmenu userTopMenu resellerMenuLi" style="display: none">
                <li class="mngClnts"><a title="Manage clients" href="#!reseller-manage-clients.php" ><i class="ic-32 mc"></i></a></li>
                <li class="mngPlans"><a title="Manage plan" href="#!reseller-manage-plan.php" ><i class="ic-32 mpl"></i></a></li>
                <li class="mngPins"><a title="Manage pin" href="#!reseller-manage-pins.php" ><i class="ic-32 mpn"></i></a></li>
                <li class="mngWb"><a title="Manage website" href="#!manage-websites.php" ><i class="ic-32 mw"></i></a></li>
				<li class="trnsLog"><a title="Transaction log" href="#!reseller-transactional-log.php" ><i class="ic-32 tl"></i></a></li>
				 <?php 
//                     if($_SESSION['id'] == 2 || $_SESSION['id'] == 32066 || $_SESSION['id'] == 31391) { ?>
                 <li class="resellerLog"><a title="Call log" href="#!reseller-call-log.php" ><i class="ic-32 cl"></i></a></li>
                 <?php 
//                                 } 
                 ?>
                <li class="resellerActiveCall"><a title="Active calls" class="contact bdrTrns" href="#!reseller-active-call.php"><i class="ic-32 ac"></i></a><span>0</span></li>
                               
             </ul>
             <ul class="ln topmenu userTopMenu callshopMenuLi"  style="display:none">
                <!--<li><a class="contact bdrTrns" href="http://voip91.com:8082" >Active Call</a></li>
                <li><a class="bdrTrns" href="#!call-log.php" >Call Log</a></li>
                <li><a class="bdrTrns" href="" >Manage IDs</a></li>-->
             </ul>
          <?php }?>
          <!--//Nav Menus-->

        <!--Top Right-->
         <ul id="topRightAct" class="ln">
                <li class="bdrTrns pr setting" onclick="uiDrop(this,'#showSetting', 'true')">
      					<div class="clear cp"> 
                                <span id="namewrap" class="fl clear">
                                      <i class="ic-32 ph fl"></i><p id="firstName" class="semi fl"><?php echo preg_match('/^rename_/',$_SESSION['username'])?"We miss your name":$_SESSION['username'] ;?></p>
                                 </span>
                                 <!--<span class="ic-24 userico"></span> -->
                                 <span class="ic-16 dropsign mrT"></span>
                        </div>
                        <div class="dropmenu" id="showSetting" style="left:auto; right:0; color:#999; width:180px">
                        	
                            <div class="pd1 bdrB">
                                <p class="ddLit" id="lastName">
                                <?php if(isset($_SESSION['name']) && $_SESSION['name'] != ""){ ?>
                                    <?php echo $_SESSION['name'];?>
                                    <?php }else{ ?>
                                    We miss your name!
                                <?php } ?>
                                </p>
                            </div>
                            
                            <ul class="boxsize ln">
                                    <li class="clear" onclick="window.location.href='#!setting.php|buymore.php'"> 
                                            <span class="ic-24 setting"></span> <span>My Account</span>
                                    </li>
                                    <li class="clear" onclick="window.location.href='#!transactions.php'"> 
                                            <span class="ic-24 trans"></span> <span>My Transactions</span>
                                    </li>
                                    <li class="clear" onclick="window.location.href='#!setting.php|panel-pricing.php'"> 
                                            <span class="ic-24 price"></span> <span>Pricing</span>
                                    </li>
<!--                                    <li class="clear" onclick="window.location.href='#!clicktocall_addons.php'"> 
                                            <span class="ic-24 adns"></span> <span>Addons</span>
                                    </li>-->
                                    <li class="clear" onClick="var url = window.location.hash;window.location.href='/logout.php?url='+url.substring(1)"> 
                                            <span class="ic-24 logout"></span>
                                            <span>Logout</span> 
                                   </li>
                            </ul>
                            <!--Logo Here-->
                            <div id="logosec" class="pd1">
                            	<p class="f12">Powered by :</p>
                                 <a href="/index.php"><img src="<?php echo (isset($logoImage) && $logoImage != ''? $logoImage :((($_SERVER['HTTP_HOST'] == "voice.phone91.com")||($_SERVER['HTTP_HOST'] == "voip91.biz"))?'images/phone91-logo.jpg':'Your Logo Comes Here')) ?>" height="46px" alt="Your Logo Comes Here" /></a>
                           </div>
                           <!--//Logo Here-->
                        </div>
    			</li>
                <li class="bdrTrns balance">
                       <span class="db pd fl">Talktime</span>
                       <div id="balance"><?php echo round($balance,3); ?> <?php echo $currency;?></div>
                            <?php if(isset($_SESSION['client_type']) && $_SESSION['client_type'] != 4)
                            { ?>
                            <a class="db pd fl" href="#!setting.php|buymore.php" >Buy more</a>
                            <?php } ?>
                       <div id="dtLink" class=""></div>
   			 	</li>
        </ul>
        <!--//Top Right-->
    </div>
   <!-- //Desktop Menus-->
 </div>
<!--//Header-->
<!-- <input type="text" name="searchUser" id="searchUser">-->
<div id="container" class=""></div>
 
<!-- Include some useful javascript--> 

<?php if($_SESSION['client_type'] == 2){ ?>
    <!--<script src="/js/socket.io.min.js"></script>-->
    <script>
    basket.require(
        { url: '/js/socket.io.min.js' }    
    );
    </script>
    
<?php } ?>

<script type="text/javascript">
    
    


    
var loadingInterval;
function motion(obj,col,width,height){
	var bgPosX=0, bgPosY=0,r=1, c=1;
	if(loadingInterval)
	clearInterval(loadingInterval);
	loadingInterval = setInterval(function(){
	
	if(c == col){c = 1;bgPosX=0;r=1;bgPosY=0;}
	{$(obj).css({'background-position':bgPosX+'px '+bgPosY+'px'}); c++; bgPosX=bgPosX-width;}
	
	},120);		
}

var storage = window['localStorage'];

function menuHandler(){
    var menu = $(this).attr('menuval');
    var icId = $(this).attr('icId');
    var lPage = $(this).attr('lPage');
    var title = $(this).text();
    
    if(menu.length>1)
    {
      storage.setItem('menu', menu);
      storage.setItem('icId', icId);
      storage.setItem('lPage', lPage);
      storage.setItem('title', title);
    }
    
    $(".userTopMenu").hide();
    $(".icClass").hide();
    $("."+menu+"MenuLi").show();
    $("#"+icId+"").show();
    uiDrop(this,'#showUsType', false)
    $("#userTitle").text($(this).text());
    window.location.href=lPage;   
}

var loginAs = <?php echo $loginAs;?>;

$(function(){
	$(".UserTypeMenu").on("click",menuHandler);
	var isReseller = <?php echo $isReseller;?>;
	if(storage.getItem('menu')){
		var menu = storage.getItem('menu');
		var icId = storage.getItem('icId');
		var lPage = storage.getItem('lPage');
		var title = storage.getItem('title');
	
		if(!loginAs){
			$(".userTopMenu").hide();    
			$("."+menu+"MenuLi").show();
			$(".icClass").hide();
			$("#"+icId+"").show();    
			$("#userTitle").text(title);		
		}
		else
		{
			$(".userTopMenu").hide();    
			$(".userMenuLi").show();
			$(".icClass").hide();
			$("#uSign").show();    
			$("#userTitle").text('User');
		}
	}
});

</script>
<?php 

//Code To redirect user to phone setting page if user do not have any confirmed mobile number


#get all contact detail 
$contactObj= new contact_class();

#find verified contact number
$vContactArr=$contactObj->getConfirmMobile($_SESSION["userid"]);
//var_dump($vContactArr);
/*if($vContactArr[0]==0){    
    if($_SESSION['client_type'] != 4)
        echo "<script>location.href='#!setting.php|phone.php'</script>";
}*/
?>
<!--<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>-->
<script type="text/javascript">
	//	The menu on the left
	$(function() {
		$('nav#menu-left').mmenu();
	});

	//	The menu on the right
	$(function() {
		$('nav#menu-right').mmenu({
			position	: 'right',
			counters	: true,
			searchfield	: true
		});

		//	Click a menu-item
		var $confirm = $('#confirmation');
		$('#menu-right a').not( '.mm-subopen' ).not( '.mm-subclose' ).bind(
			'click.example',
			function( e )
			{
				e.preventDefault();
				$confirm.show().text( 'You clicked "' + $(this).text() + '"' );
				$('#menu-right').trigger( 'close' );
			}
		);
	});
        
         function getActiveCallJs()
       {
           console.log("called");
           $.getScript("/public/counter.js").done(function( script, textStatus ) {
    console.log( textStatus );
  });
       }
       
       $(document).ready(function(){
           if($('#userTitle').html() == "Reseller")
           {
               getActiveCallJs();
           }
           
       })
       
    var randomStr = '<?php echo $_SESSION['cookieValue']; ?>';

    if( randomStr != '')
    {
        
        localStorage.setItem('%$$#@!%$#%', randomStr );
    }
       
       
	//initialize tooltip
	$('.topmenu li a').tipTip();

// initialize fastclick
window.addEventListener('load', function() {
    FastClick.attach(document.body);
}, false);
</script>

<!--html + script for controlling the tour-->
<div class="tourBg"></div>


</body>
</html>
