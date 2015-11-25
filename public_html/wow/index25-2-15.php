<?php 
include dirname(dirname(__FILE__)) . '/config.php';
if (!$funobj->login_validate()) {
    $funobj->redirect("/index.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Phone 91 Panel</title>
<link rel="stylesheet" type="text/css" href="css/panel_style.css?q=<?php echo rand(0 , 1000); ?>" />
<link rel="stylesheet" type="text/css" href="css/ui-theme.css?q=<?php echo rand(0 , 1000); ?>" />
<style type='text/css'>
   
	#leftsec{
	    top:160;
	}
	/*======================================================================
  Selectric
======================================================================*/
.selectricWrapper {
  position: relative;
  margin: 0 0 10px;
  width: 300px;
  cursor: pointer;
}

.selectricResponsive {
  width: 100%;
}

.selectric {
  border: 1px solid #DDD;
  background: #F8F8F8;
  position: relative;
  border-radius: 2px;
}
.selectric .label {
  display: block;
  white-space: nowrap;
  overflow: hidden;
  margin: 0 30px 0 0;
  padding: 6px;
  font-size: 12px;
  line-height: 18px;
  color: #444;
  min-height: 18px;
}
.selectric .button {
  display: block;
  position: absolute;
  right: 0;
  top: 0;
  width: 30px;
  height: 30px;
  color: #BBB;
  text-align: center;
  font: 0/0 a;
  /* IE Fix */
  *font: 20px/30px Lucida Sans Unicode, Arial Unicode MS, Arial;
}
.selectric .button:after {
  content: " ";
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  margin: auto;
  width: 0;
  height: 0;
  border: 4px solid transparent;
  border-top-color: #BBB;
  border-bottom: none;
}

.selectricHover .selectric {
  border-color: #CCC;
}
.selectricHover .selectric .button {
  color: #888;
}
.selectricHover .selectric .button:after {
  border-top-color: #888;
}

.selectricOpen {
  z-index: 9999;
}
.selectricOpen .selectric {
  border-color: #CCC;
  background: #F0F0F0;
}
.selectricOpen .selectricItems {
  display: block;
}

.selectricDisabled {
  filter: alpha(opacity=50);
  opacity: 0.5;
  cursor: default;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}

.selectricHideSelect {
  position: relative;
  overflow: hidden;
  width: 0;
  height: 0;
}
.selectricHideSelect select {
  position: absolute;
  left: -100%;
  display: none;
}

.selectricInput {
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  overflow: hidden !important;
  clip: rect(0, 0, 0, 0) !important;
  margin: 0 !important;
  padding: 0 !important;
  width: 1px !important;
  height: 1px !important;
  outline: none !important;
  border: none !important;
  *font: 0/0 a !important;
  background: none !important;
}

.selectricTempShow {
  position: absolute !important;
  visibility: hidden !important;
  display: block !important;
}

/* Items box */
.selectricItems {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  background: #F9F9F9;
  border: 1px solid #CCC;
  z-index: -1;
  box-shadow: 0 0 10px -6px;
}
.selectricItems .selectricScroll {
  height: 100%;
  overflow: auto;
}
.selectricAbove .selectricItems {
  top: auto;
  bottom: 100%;
}
.selectricItems ul, .selectricItems li {
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 12px;
  line-height: 20px;
  min-height: 20px;
}
.selectricItems li {
  display: block;
  padding: 5px;
  border-top: 1px solid #FFF;
  border-bottom: 1px solid #EEE;
  color: #666;
  cursor: pointer;
}
.selectricItems li.selected {
  background: #EFEFEF;
  color: #444;
  border-top-color: #E0E0E0;
}
.selectricItems li:hover {
  background: #F0F0F0;
  color: #444;
}
.selectricItems li.disabled {
  background: #F5F5F5;
  color: #BBB;
  border-top-color: #FAFAFA;
  cursor: default;
}

.flag-24 { width:24px; height:22px; background-image:url(/images/flags-24x24.png); display:block; float:left; margin-right: 6px;}
.selectric{padding:5px 10px; margin-bottom:20px;
	  width: 60px;
}
.selectricItems{
    width: 232px; 
    height: 300px; 
    overflow: auto;
}

.codeInput{
    height: 20px;
width: 25px;
position: relative;
left: 80px;
}
    </style>
</head>
<body>
<!--Top Loading Progress Bar-->
<div id="progress" class="waiting" style=""><dt></dt> <dd></dd></div>
<div id="notification"></div>

<!--Small Screen Header-->
<nav id="menu-left" class="dn">
  <ul>
 	 <li title="User" class="main">Menus will come here</li>
  <!--  <li title="User" class="main"><span>User</span></li>
    <li><a href="#!contact.php" title="Contacts">Contacts</a></li>
    <li><a href="#!call-log.php" title="Call Log">Call Log</a></li>
    <li title="Reseller" class="main mrT2"><span>Reseller</span></li>
    <li><a href="#!reseller-transactional-log.php" title="Transaction Log">Transaction Log</a></li>
    <li><a href="#!reseller-manage-clients.php" title="Manage Client">Manage Client</a></li>
    <li><a href="#!reseller-manage-plan.php" title="Manage Plans">Manage Plans</a></li>
    <li><a href="#!reseller-manage-pins.php" title="Manage PINs">Manage PINs</a></li>
    <li><a href="#!manage-websites.php" title="Manage Website">Manage Website</a></li>
    <li title="CallShop" class="main mrT2"><span>CallShop</span></li>
    <li><a  href="#!callshop.php" title="Callshop Active Call">Callshop Active Call</a></li>
    <li><a  href="#!reseller-active-call.php" title="Active Call">Active Call</a></li>
    <li><a  href="#!call-log.php" title="Call Log">Call Log</a></li>
    <li><a  href="" title="Manage IDs">Manage IDs</a></li>-->
  </ul>
</nav>
<nav id="menu-right" class="dn">
  <ul>
  <li>Menus will come </li>
   <!--
    <li>
      <p id="firstName" class="semi  mrL2 lh20">Lovey</p>
      <p id="lastName" class="mrB1  mrL2  lh20">Gorakhpuriya</p>
    </li>
    <li  title="Settings" class="paddInner"> 
        <a href="javascript:void(0);"  class="fl text" onclick="window.location.href='#!setting.php|buymore.php'"> 
                <span title="Setting" class="fl text">Settings</span> 
        </a>
     </li>
    <li class="paddInner"> 
    	<a href="javscript:void(0);"> 
                <span title="Logout" class="fl text">Logout</span> 
        </a>
    </li>
    <li><span>Balance 999.987 USD</span></li>
    <li><a  href="#!setting.php|buymore.php" title="Buy Now">Buy Now</a> </li>
    <li><a href="#!transactions.php" title="Transactions">Transactions</a></li>-->
  </ul>
</nav>
<!--//Small Screen Header--> 

<ul class="leftBox">
        	<li><a href="http://phone91.com/" title="Phone91"><span class="ic-logo"></span></a></li>
            <li class="midchild clientWrapSearch" id="search">
            		<span class="ic-32 whsearchC"></span>
                        <!--Use for Client search and Sub Menus-->
<!--                        <div class="leftCmnbx clntSearch dn" id="clientSearch">
                        	 <div class="search">
                             	<input type="text" placeholder="Search" class="isInput100"/>
                              </div>
                            <div class="scrolll contentHolder">
                               <?php 
                               //// for($i = 0; $i <= 1; $i++) 
//								{
//								echo'
//							   <div>
//                                        <a href="javascript:void(0)" >Client Search</a>
//                                         <a href="javascript:void(0)">Deleted Client</a>
//                                        <a href="javascript:void(0)"> Idle Client</a>
//                                        <a href="javascript:void(0)"> New Singup</a> 
//                                </div>';
//								} 
                                                                ?>
                             </div>   
                        </div>-->
                        <!--//Use for Client search and Sub Menus-->
             </li>
  			  <li id="manage">
                 	<span class="ic-32 whmclint"></span>
                     <div class="leftCmnbx  dn">
                             <div>
                                <a href="#!manage-client.php|manage-client-setting.php" class="headingName" title="Manage Client">Manage Client</a>
								<?php if(!isset($_SESSION['acmId'])){ ?> 
                                <a href="#!manage-account-manager.php" class="headingName" title="Manage Client">Account Manager</a>   
                                   <?php } ?><!--<a href="javascript:void(0)" title="Deleted Client" class="headingName">Deleted Client</a>
                                <a href="javascript:void(0)" title="Idle Client" class="headingName"> Idle Client</a>
                                <a href="javascript:void(0)" title="New Singup" class="headingName"> New Singup</a>-->
                            </div>
                    </div>
            </li>
            
			<li id="route">
            	<span class="ic-32 whroute"></span>
                     <div class="leftCmnbx  dn">
                        <div>
                            <a href="#!manage-route.php|manage-route-setting.php" title="Route" class="headingName">Route</a>
                            <a href="#!route-credit-graph.php" title="Route" class="headingName">Route Credit Graph</a>
			    <a href="#!route-all-transactional-log.php" title="Route Transaction log" class="headingName">Route Transaction Log</a>
                            <!-- <a href="javascript:void(0)" title="Route Profit" class="headingName">Route Profit</a> -->
                        </div>
                        
                    </div>
            </li>
            
			<!--<li id="active">
            		<span class="ic-32 whactivecall"></span>
                     <div class="leftCmnbx  dn">
                        <div>
                                <a href="javascript:void(0)" title="Active Call" class="headingName">Active Call</a>
                                <a href="javascript:void(0)" title="Blcok IP, Numbers, E-mail ID" class="headingName">Blcok IP, Numbers, E-mail ID</a>
                        </div>
                    </div>
            </li>-->
            
            <li id="call">
            		<span class="ic-32 whcalllog"></span>
                     <div class="leftCmnbx  dn">
                          <div>
                                <!--<a href="#!call-log.php|call-log-setting.php" class="headingName" title="Call Log">Call Log</a>-->
                                <!--<a href="javascript:void(0)" title="Route Log" class="headingName">Route Log</a>-->
                                <a href="#!account-manager.php|edit-funds.php" class="headingName" title="A/c Manager Log">A/c Manager Log</a>
                                 <a href="#!callFailedErrorLog.php" class="headingName" title="Call Failed Error Log">Call Failed Error Log</a>
                                 <a href="#!call-details.php" class="headingName" title="Call Log Details">Call Log Details</a>
                                <!--<a href="javascript:void(0)" title="Call Failed Error Log" class="headingName">Call Failed Error Log</a>-->
                         </div>
                        
                     </div>
                    
            </li>
            
             <li id="tariff">
            		<span class="ic-32 whtarrif"></span>
                    <div class="leftCmnbx  dn">
                         <div>
                                   <a href="#!tariff-plan.php" title="Tariff Plan" class="headingName">Tariff Plan</a>
				   <a href="#!manage-pin.php" title="Manage PINs" class="headingName">Manage PIN</a>
                                    <a href="#!blockUser.php" title="Block IPs,Numbers,E-mail IDs" class="headingName">Block User</a>
                                    
                                     <a href="#!adminTransactionLog.php" title="Transaction Log" class="headingName">Transaction Log</a>
                                     <a href="#!adminAccessNumberLog.php" title="Access Number Log" class="headingName">Access Number Log</a>
                                     
                          </div>
                    </div>            
            </li>
            
			<!--<li id="pin">
            		<span class="ic-32 whpin"></span>
                    <div class="leftCmnbx  dn">
                        <div>
                                 <a href="javascript:void(0)" title="Recharge PIN" class="headingName">Recharge PIN</a>
                       </div>  
                   </div>
            </li>-->
            
			<li id="dialplan">
                <span class="ic-32 more"></span>
                <div class="leftCmnbx  dn">
                    <div>
                             <a href="#!dialplan.php" title="Dialplan" class="headingName">Dialplan</a>
                             <a href="#!active-calls.php" >Active Call</a>
                             <a href="#!callLog.php" title="callLog" class="headingName">Call Log</a>
                             <a href="#!countryGraph.php" title="country log" class="headingName">Country Log</a>
                             <!--<a href="#!manage-websites.php|add-website.php" title="Manage Websites" class="headingName">Manage Websites</a>-->
                   </div>  
               </div>
            </li>
	</ul>
<?php include_once ("inc/head.php"); ?>
<div id="container" class="">
    
    <div id="contentWrp" app-view-segment="0"></div>

</div>

<!-- For Make Web Page load Faster Javascript should be Include in footer...--> 
<script src="js/jquery-1.9.1.min.js"></script> 
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="js/toastr.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script> 
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/panel.js" ></script>    
<script type="text/javascript" src="js/jquery.quicksearch.js"></script> 
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script> 
<script src="/js/highcharts.js"></script>
 <script src="js/jquery.mousewheel.js"></script>
 <script src="js/jquery.paginate.js"></script>
<script src="/js/perfect-scrollbar.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="/js/reseller.js"></script>
<script type="text/javascript">
function current()
{
	$( document ).ready(function()
	{  
	  $("#leftsec ul li").click(function(){
		$("#leftsec ul li").removeClass("active");
		$(this).addClass("active");
	});
	
	/*Make Height of Content Div A ccroding to the Screen Resolution*/
	jQuery.fn.autoHeight = function( options ) {
 
	var defaults = {
		matchWith:document,		
		addExtra:0,
		removeExtra:0,
		after:function(){}
    };
 
    var opts = $.extend(defaults, options);
 
    return this.each(function() {		
    	var objH,diff,winH,newHeight,ths;
		ths = this;
		
		function setHeight()
		{
			objH = $(ths).height();
			diff = $(ths).outerHeight(true)-objH;
			winH = $(window).height();
			if(objH < winH)
			{
				opts.removeExtra = opts.removeExtra+diff;		
				newHeight = winH-opts.removeExtra;
				$(ths).css('height',newHeight);
			}
			opts.after.call();
		}
		
		setHeight();
		$( window ).resize(function(){
			setHeight();  			
		});
    });
};

//$('#leftsec').autoHeight({removeExtra:130});
//$('#rightsec').autoHeight({removeExtra:110});
});   
}

/*Replace Close and Add Button In the top search written by Lovey*/
function toggleAddClose()
{
$(document).ready(function() {
	$(".replaceBttn").click(function() {
		$(".cmnClssBtn").toggle();
		if ($(".replaceBttn").html() == '<p class="arBorder subpage fl cp sucsses cmniner " title="Close"><span class="ic-16 close"></span></p>')
		{
			$(".replaceBttn").html('<p class="arBorder cmniner secondry fl cp primary" title="Add"><span class="ic-16 add " id="addtariffbtn"></span></p>');
			//console.log("close");
		}
		else 
		{
			$(".replaceBttn").html('<p class="arBorder subpage fl cp sucsses cmniner " title="Close"><span class="ic-16 close"></span></p>');
			//console.log("add");
		}
	});
});
}

	var loadingInterval;
	function motion(obj,col,width,height){
	var  bgPosX=0, bgPosY=0,r=1, c=1;
	if(loadingInterval)
			clearInterval(loadingInterval);
	loadingInterval = setInterval(function(){
	//		console.log('X : '+bgPosX+' col : '+c+' Y : '+bgPosY+' Row : '+r);		
	if(c == col){c = 1;bgPosX=0;r=1;bgPosY=0;}
		{$(obj).css({'background-position':bgPosX+'px '+bgPosY+'px'}); c++; bgPosX=bgPosX-width;}
	},120);		
}

var storage = window['localStorage'];
function menuHandler() {
    p91Loader('start');
    var menu = $(this).attr('menuval');
    var icId = $(this).attr('icId');
    var lPage = $(this).attr('lPage');
    localStorage.setItem('menu', menu);
    localStorage.setItem('lPage', lPage);
    $(".userTopMenu").hide();
    $(".icClass").hide();
    $("."+menu+"MenuLi").show();
    $("#"+icId+"").show();
    uiDrop(this,'#showUsType', false)
    $("#userTitle").text($(this).text());
    window.location.href=lPage;
    p91Loader('stop');
}
$(function() {
	$(".UserTypeMenu").on("click",menuHandler);
});

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

<!--Js for Menus Selected and Make Heading as Dynamically accroding to Selected Page Written By Lovey-->
$(document).ready(function () {
    $("ul.leftBox li").on('click', function(e) {
		$("ul.leftBox li").removeClass("active");
		$(this).addClass("active");
		
        var id = $(this).attr("id");
        $('#' + id).siblings().find(".active").removeClass("active");
        $('#' + id).addClass("active");
        localStorage.setItem("selectedolditem", id);
		
		$('.leftCmnbx').hide();
		e.stopPropagation();
    	$(this).find(".leftCmnbx").show();
		
		$(this).find(".leftCmnbx a").show();
		$('.leftCmnbx a').click(function(event){
			$('.leftCmnbx').fadeOut(500);	
			event.stopPropagation();
		});	
		
		//For Show Menu as a heading in Top
		if (!$(this).hasClass("active")) {
			$('.leftCmnbx a').one('click', function() {
				 $(".headingTitle").text($(this).text());
			});
		}	

		$(document).on('click', function(e){
			if($('.leftCmnbx').has(e.target).length === 0){
				$('.leftCmnbx').fadeOut(500);	
			}
		});		
    });
    var selectedolditem = localStorage.getItem('selectedolditem');
    if (selectedolditem != null) {
        $('#' + selectedolditem).siblings().find(".active").removeClass("active");
        $('#' + selectedolditem).addClass("active");
		$(".headingTitle").text($('#' + selectedolditem).text());
    }
});


// design include inc/head.php
function showSmsDialog(type){
if(type == 'mail'){
    var title = "SEND MAIL";
}else
    var title = "SEND SMS";
$('#'+type+'-dialog').dialog({modal: true, resizable: true, width: 600, height: 400,title:title});
}

$(document).ready(function(){
   var options={
                 url: "/controller/adminManageClientCnt.php?action=sendBulkMail",
                 type: "post",
                 dataType:  'json',
	         beforeSubmit:  showMailRequest,  // pre-submit callback 
		 success: showMailResponse 
             };
                $('#bulkmailsend').ajaxForm(options);
                
 });
 $(document).ready(function(){
   var options={
                 url: "/controller/adminManageClientCnt.php?action=sendBulkSms",
                 type: "post",
                 dataType:  'json',
	         beforeSubmit:  showRequest,  // pre-submit callback 
		 success: showResponse 
             };
                $('#bulkSmsSend').ajaxForm(options);
                
 });
 
 function showMailRequest(formData, jqForm, options){
 $('#sendMail').attr('disabled','disabled');
     if($("#bulkmailsend").valid()){
             return true; 
     }else
             return false;
 }
 
 function showMailResponse(responseText, statusText, xhr, $form){
     show_message(responseText.msg,responseText.status);
                if(responseText.status == "success"){
                   
                    $(':input','#bulkmailsend')
                                    .not(':button, :submit, :reset, :hidden')
                                    .val('')
                                    .removeAttr('checked')
                                    .removeAttr('selected')
                                    .removeClass('valid error'); 
                                    
                     $('#mail-dialog').dialog('destroy');
                   }
//		consol.log(responseText.resellerClient);
                $('#sendMail').removeAttr('disabled');
		
 }
 $(document).ready(function() {
            $("#bulkmailsend").validate({ 
                    rules: {
                            subject :{
                            	required: true,
				minlength: 3,
                                maxlength: 30
                                     },
                            message :{
				required: true,
                                minlength: 3,
                                maxlength: 1000
				       }   
                           }
            })
          })  
 
 
 
 
 function showRequest(formData, jqForm, options){
     $('#sendSms').attr('disabled','disabled');
     if($("#bulkSmsSend").valid()){
             return true; 
     }else
             return false;
 }

function showResponse(responseText, statusText, xhr, $form){
   show_message(responseText.msg,responseText.status);
                if(responseText.status == "success"){
                   
                    $(':input','#bulkSmsSend')
                                    .not(':button, :submit, :reset, :hidden')
                                    .val('')
                                    .removeAttr('checked')
                                    .removeAttr('selected')
                                    .removeClass('valid error'); 
                                    
                     $('#sms-dialog').dialog('destroy');
                   }
                $('#sendSms').removeAttr('disabled');  
}
$(document).ready(function() {
            $("#bulkSmsSend").validate({ 
                    rules: {
                            senderId :{
                            	required: true,
				minlength: 3,
                                maxlength: 30
                                     },
                            content :{
				required: true,
                                minlength: 3,
                                maxlength: 160
				       }   
                           }
            })
          })  
</script>
</body>
</html>