<?php
/**
 * @author Rahul  Chordiya <rahul@hostnsoft.com>
 * @since 1.0    07-aug-2013
 * @package Phone91
 * @details User Home page after-login
 */
//include Common Config File
include_once 'config.php';

//Validate Login with the help of this function 
if(!$funobj->login_validate()){
        $funobj->redirect("index.php");
}

$redirectUrl = $funobj->getLandingPage($_SESSION["id"]);
if(!$redirectUrl && $redirectUrl != ""){
    echo "<script>window.location.hash='#".$redirectUrl."'</script>";
}

//Include Profile Class for user details
include_once(CLASS_DIR.'profile_class.php');
$profileobj = new profile_class();
//Call the function to get user balance
$balance = $profileobj->getUserBalance($_SESSION['id']);

//Grep user balance currency 
$currency =$profileobj->getCurrency($_SESSION['currencyId']);
//$isAdmin=0;
//if($_SESSION['isAdmin']==1){
//    $isAdmin=1;///Users/smartpushp/Downloads/bulksms.wsdl
//}


//only for testing
$isAdmin = 1;
$isReseller = ($funobj->check_reseller()) ? 1 : 0;
$loginAs = (isset($_SESSION['tempVar']['type'])) ? 1 : 0;

?>
<!DOCTYPE HTML>
<html>
    <head>
    <meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" >
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Phone91 My Panel</title>
	<link rel="stylesheet" type="text/css" href="css/panel_style.css"/>
	<link rel="stylesheet" type="text/css" href="css/ui-theme.css" />
	<link rel="stylesheet" type="text/css" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery.paginate.css" />
<!--[if IE 7]>  
<link rel="stylesheet" href="ie7.css">  
<![endif]-->
<style>
.flip-horizontal { -webkit-transform: matrix(-1, 0, 0, 1, 0, 0); -moz-transform: matrix(-1, 0, 0, 1, 0, 0); }
</style>
</head>
<body>
<?php 
	//include google analytic code just after body tag start
	include_once($_SERVER['DOCUMENT_ROOT'].'/analyticstracking.php'); 
?>

<script type="text/javascript">
    
    
	if(window.location.hash == "")
        window.location.hash = "!contact.php";
</script>

<div id="progress" class="waiting" style=""><dt></dt><dd></dd></div>

<div id="notification"> </div>
<div class="motion" style="display:none;"></div>
<!--Small Screen Header-->
<nav id="menu-left" class="dn">
      <ul>
            <li class="main"><span>User</span></li>
              <li><a href="#!contact.php" >Contacts</a></li>
              <li><a href="#!call-log.php" >Call Log</a></li>
              
              <li class="main mrT2" ><span>Reseller</span></li>
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
                  <p id="firstName" class="semi  mrL2 lh20"><?php echo $_SESSION['username'];?></p>
                  <p id="lastName" class="mrB1  mrL2  lh20"><?php echo $_SESSION['name'];?></p>          
             </li>            
			 <li><span>Balance <?php echo round($balance,3); ?> <?php echo $currency;?></span></li>             
			 <li><a  href="#!setting.php|buymore.php" >Buy Now</a> </li>
             <li><a href="#!transactions.php" >Transactions</a></li>
    		 <li  class="paddInner">
             	  <a href="javascript:void(0);"  class="fl text" onclick="window.location.href='#!setting.php|buymore.php'">  
                      <span class="ic-24 setting fl"></span> 
                      <span class="fl text">Settings</span>
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
          <div class="deskMenu  clear">
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
                <!--Logo Here-->
                <div id="logosec">
               		 <a href="/index.php"><img src="images/phone91-logo.jpg" height="46px" alt="" /></a>
               </div>
       		  <!--//Logo Here-->
             <!--User Menus-->
             <div id="usersec" class="pr">
  				  <div class="clear cp"  onclick="uiDrop(this,'#showUsType', 'true')">
                  		 <span class="ic-32 mrR1 icClass" id="uSign"></span> 
                         <span class="ic-32 mrR1 icClass" id="rSign" style="display: none;"></span> 
                         <span class="ic-32 mrR1 icClass" id="cSign" style="display: none;"></span> 
                         <span id="userTitle" class="mrR1">User</span> <span class="ic-16 mrT1 dropsign"></span> 
                  </div>
   				  <ul class="dropmenu boxsize ln" id="showUsType">
                      <li id="userMenuLi" class="UserTypeMenu" menuval="user" icId ="uSign" lPage ="#!contact.php" >User</li>
                      <?php 					  
					  if($isReseller)
					  {?>
                          <li id="resMenuLi" class="UserTypeMenu" onclick="getActiveCallJs()"menuval="reseller" icId ="rSign" lPage ="#!reseller-manage-clients.php" >Reseller</li>
                          <li id="cShopMenuLi" class="UserTypeMenu" menuval="callshop" icId ="cSign" lPage ="#!callshop.php" >CallShop</li>
                      <?php
					  }
                      ?>
 				 </ul>
    		</div>
           <!--//User Menus-->
        
          <!--Nav Menus-->
              <ul class="ln topmenu userTopMenu userMenuLi">
                    <li class="contactLi"><a class="contact bdrTrns" href="#!contact.php" >Contacts</a></li>
                    <li class="call-logLi"><a class="bdrTrns" href="#!call-log.php" >Call Log</a></li>                     
             </ul>
            <?php if($isAdmin==1){?>
             <ul class="ln topmenu userTopMenu resellerMenuLi" style="display: none">
                <!--<li><a class="contact bdrTrns" href="#!reseller-active-call.php" >Active Calls</a></li>-->                
                <li class="resellerManage"> <a class="bdrTrns prnt" href="javascript:void(0)" > <span class="fl" id="manageLbl">Manage</span> <span class="ic-16 dropsign"></span> </a>
                      <ul class="submenu ln">
                        <li><a class="bdrTrns" href="#!reseller-manage-clients.php" >Manage Clients</a></li>
                        <li><a class="bdrTrns" href="#!reseller-manage-plan.php" >Manage Plans</a></li>
                        <li><a class="bdrTrns" href="#!reseller-manage-pins.php" >Manage PINs</a></li>
                        <li><a class="bdrTrns" href="#!manage-websites.php" >Manage Website</a></li>
                  </ul>
                </li>
				<li class="resellerLog"> <a class="bdrTrns prnt" href="javascript:void(0)"> <span class="fl" id="logLbl" >Log</span> <span class="ic-16 dropsign"></span> </a>
                  <ul class="submenu ln" style="min-width:210px;">
                     <li><a class="bdrTrns" href="#!reseller-transactional-log.php" >Transaction Log</a></li>
                     <?php 
//                     if($_SESSION['id'] == 2 || $_SESSION['id'] == 32066 || $_SESSION['id'] == 31391) { ?>
                     <li><a class="bdrTrns" href="#!reseller-call-log.php" >Call Log</a></li>
                     <?php 
//                                 } 
                     ?>
                  </ul>
                </li>
                <li class="resellerActiveCall"><a class="contact bdrTrns" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>:8082" target="_blank" >Active Call</a></li>
                               
             </ul>
             <ul class="ln topmenu userTopMenu callshopMenuLi"  style="display:none">
                <!--<li><a class="contact bdrTrns" href="http://voip91.com:8082" >Active Call</a></li>
                <li><a class="bdrTrns" href="#!call-log.php" >Call Log</a></li>
                <li><a class="bdrTrns" href="" >Manage IDs</a></li>-->
             </ul>
          <?php }?>
          <!--//Nav Menus-->

        <!--Top Right-->
         <ul id="topRightAct" class="ln topmenu">
                <li class="bdrTrns pr setting">
      					<div class="clear cp" onclick="uiDrop(this,'#showSetting', 'true')"> 
                                    <span id="namewrap" class="fl">
                                            <p id="firstName" class="semi"><?php echo $_SESSION['username'];?></p>
                                            <?php if(isset($_SESSION['name']) && $_SESSION['name'] != ""){ ?>
                                            <p id="lastName"><?php echo $_SESSION['name'];?></p>
                                            <?php }else{ ?>
                                            <p id="lastName" class="themeClr">We miss your name!</p>
                                            <?php } ?>
                                     </span> 
                                            <?php if(isset($_SESSION['name']) && $_SESSION['name'] != ""){ ?>
                                                <span class="hasName"></span>
                                            <?php }else{ ?>
                                                <span class="pointToNoName"></span>
                                                <span class="noName"></span>
                                            <?php } ?>
                                     <!--<span class="ic-24 userico"></span> -->
                                     <span class="ic-16 dropsign"></span>
                        </div>
                        <ul class="dropmenu boxsize ln" id="showSetting">
                                <li class="clear" onclick="window.location.href='#!setting.php|buymore.php'"> 
                                        <span class="ic-24 setting"></span> <span >Settings</span>
                                </li>
                                <li class="clear"> 
                                        <span class="ic-24 logout"></span>
                                        <span onClick="var url = window.location.hash;window.location.href='/logout.php?url='+url.substring(1)" >Logout</span> 
                               </li>
      			  		</ul>
    			</li>
                <li class="bdrTrns balance">
                      <div id="balance">
					  <div class="semi">Balance <?php echo round($balance,3); ?> <?php echo $currency;?></div>
					  <a class="themeLink clrTrns" href="#!setting.php|panel-pricing.php" >Pricing</a><span class="mrL mrR">|</span><a class="themeLink clrTrns" href="#!setting.php|buymore.php" >Buy Now</a><span class="mrL mrR">|</span><a class="themeLink clrTrns" href="#!transactions.php" >Transactions</a> 
					  </div>
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

<script src="js/jquery-1.9.1.min.js"></script> 
<script src="js/jquery-ui-1.10.3.custom.min.js"></script> 
<script src="/js/highcharts.js"></script>
<script type="text/javascript" src="js/toastr.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script> 
<script type="text/javascript" src="js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="js/contact.js"></script> 
<script type="text/javascript" src="js/reseller.js"></script> 
<script type="text/javascript" src="js/website.js"></script>
<script type="text/javascript" src="js/jquery.quicksearch.js"></script> 
<script type="text/javascript" src="js/jquery.paginate.js"></script>
<script type="text/javascript" src="js/tiptip.js"></script>
<script type="text/javascript" src="js/panel.js"></script>

<?php if($_SESSION['client_type'] == 2){ ?>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/socket.io/0.9.16/socket.io.min.js"></script>
    
<?php } ?>

<script type="text/javascript">
var loadingInterval;
function motion(obj,col,width,height){
	var  bgPosX=0, bgPosY=0,r=1, c=1;
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
include_once(CLASS_DIR.'contact_class.php');

#get all contact detail 
$contactObj= new contact_class();

#find verified contact number
$vContactArr=$contactObj->getConfirmMobile($_SESSION["userid"]);
//var_dump($vContactArr);
if($vContactArr[0]==0){
    
    //if($_SESSION['client_type'] != 4)
        //echo "<script>location.href='#!setting.php|phone.php'</script>";

}
?>



<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>
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
</script>
<?php include_once("analyticstracking.php") ?>
</body>
</html>