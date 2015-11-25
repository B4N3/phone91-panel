<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
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
<meta name="keywords" content="Gtalk to phone calls, mobile to phone call, international phone calls, internet phone calls, long distance calling, international phone call, long distance calls, cheap calling cards, call overseas, calling overseas, cheap international phone calls, sip calls, VoIP international calls, mobile VoIP calls, cheap pc to phone calls." />
<meta name="description" content="voip91 is an international company providing phone calls and International phone calls. Better voice quality and hassle free billing. Test now." />

<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Include Head Page-->
<?php include_once('inc/incHead.php'); ?>
<!-- Signup validation Js -->
<script type="text/javascript" src="js/sign_up.js"></script>
<script type="text/javascript" src="js/pricing.js"></script>
<script type="text/javascript" src="js/cobj.js"></script>
<script type="text/javascript" src="js/jcom.js"></script>
</head>
<body>
<!-- Header -->
<?php include_once('inc/incHeader.php'); ?>
<!-- //Header -->
<!-- Features -->
<?php
//Check if user already logged-in 
	if(!$funobj->login_validate()){?>
	<style>
/*        #notification { position:absolute; top:0; left:0; right:0; z-index:999999999; color:#fff; font-size:20px; display:none; text-transform:capitalize;}
        #notification div { height:30px; padding:15px 30px; line-height:30px; }
        #notification i { margin:5px 5px 0 0; }
        #notification .success, #notification .warning, #notification .error, #notification .information { display: block; }
        #notification .success { background:#1fbaa6 }
        #notification .warning { background:#ffcc00; }
        #notification .error { background:#ff5c33; }
        #notification .information { background:#30a6d9; }*/
        .motion {  background: url("../images/Phone91-preloader.png") no-repeat;    bottom: 32px;    height: 174px;    position: absolute;    right: 330px;    width: 150px;    z-index: 9999999;}
    </style>
<!--div use to show notification  Don't delete as this is very useful for showing notification  -->

<div id="notification"> </div>
<div class="motion" style="display:none;"></div>
	<div class="mainFeaturesWrapper">
	    <section id="featuresWrap1">
	    	<section class="wrapper pr">        	
	            <div class="searchFields fl ">
	              <!-- First Sreen Strat-->
    <div id="firstScreen">      
                  <div class="s1"> Hey we are sorry you are having trouble with your password! </div>
				  
	             <!--  <div class="s2">Enter your number/username </div> -->
                   
                <form method="POST" id="forgetForm">
                    <div>
                        <input type="text" class="text1" id="forget"   name="forget"  placeholder="Enter your number/username" />
                        <div class="msg pa"></div>
		            
                        <input style="background-color:#EE7836;" type="button" onclick="forgotPassword($('#forget').val(),'SMS')" title="Verify By Message" value="Verify By Message" name="submit" class="forgetbutton"/>
                    
                      	<input style="background-color:#7AA300;" type="button"  onclick="forgotPassword($('#forget').val(),'CALL')" title="Verify By Call" value="Verify By Call" name="submit" class="forgetbutton"/>
                    </div>
                </form>
                    
    <div class="talkStp  alertScreenOne ntf ">
            <div class="steps">  
                <div class="st">Alert</div>
                <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px;" class="alertScreenOneDiv">  </div>
            </div>
        </div>  

<!--    <div class="talkStp dn">
                    <div class="steps"   >  
            <div class="st">Alert</div>
                 <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px">Sorry, username is not registered with us. You can register by <a href="signup.php" style="color: #030; font-size:24px;" >SignUP</a> !.</div>
            </div>
    </div>  

     In this case only disple this other are disply none
    <div class="talkStp dn"  >
	 	<div class="steps"   >  
	<div class="st">Alert</div>
             <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px">  You has exceeded the trial limit. Please try tomorrow  or contact support. We would be glad to assist you.</div>
        </div>
    </div>  -->
</div>
<!-- First Sreen End-->
<!-- Second Sreen Strat-->
<div id="secondScreen" class="dn">
    <div class="s1"> We have sent you a Confirmation code on <span id="contactNum">91999964XXX</span> </div>

                         <!--  <div class="s2">Enter your number/username </div> -->

                       <form method="POST" action="#" id="forgetForm" onsubmit="return register();">
                            <div >
                                <input type="text" class="text1" id="confirmationCode"   name="confirmationCode"  placeholder="Enter Confirmation code" />
                                <div class="msg pa"></div>
                                <input type="hidden" name="mobNum" id="mobNum" value="" />
                                <input type="hidden" name="verifyCode" id="verifyCode" value="" />

                                <input style="background-color:#EE7836;" type="button" onclick="verifyNumber($('#confirmationCode').val(),$('#mobNum').val())" title="CodeConfirm" value="Confirm" name="CodeConfirm" class="forgetbutton"/>
                        </div>
                        </form>
    <div class="talkStp"  >
        <div class="steps alertScreenTwo"   >  
            <div class="st">Message</div>
            <div class="alertScreenTwoDiv" style="display:table-cell; vertical-align:middle; height:110px; font-size:19px">  In case you entered a wrong number, you can always go <u> <strong onclick="backToOrigin($('#secondScreen'),$('#firstScreen'),$('.ntf'))">back</strong> </u> and change it!</div>
        </div>
<!--        <div class="steps"   >         
            <div class="st alertScreenTwo">Alert</div>
            <div class="alertScreenTwoDiv"style="display:table-cell; vertical-align:middle; height:110px; font-size:19px">  In case you entered a wrong number, you can always go <u> <strong>back</strong> </u> and change it!</div>
        </div>-->
    </div>
    </div>
</div>
                    
<!-- Second Sreen End-->
<!-- when username entered with have more then one verify numbers Sreen Strat-->
<div id="thirdScreen" class="dn">
    <div class="s1"> The Following numbers are associated with the usernames... </div>

                         <!--  <div class="s2">Enter your number/username </div> -->

                       <form method="POST" action="" id="forgetForm">
                            <div >
                                <select tabindex="1"  name="Numbers" id="forgetSelectBox" class="dropdown1" onchange="changeMobileNum($(this))">

                                </select>
                            <input style="background-color:#EE7836;" type="button" onclick="forgotPassword($('#forgetSelectBox').val(),'SMS',1)" title="Verify By SMS" value="Verify By SMS" name="submit" class="forgetbutton"/>
                            <input style="background-color:#7AA300;" type="button"  onclick="forgotPassword($('#forgetSelectBox').val(),'CALL',1)" title="Verify By Call" value="Verify By Call" name="submit" class="forgetbutton"/>
                        </div>
                         <div class="msg pa"></div>
                        </form>
        <div class="talkStp"  >
                    <div class="steps"   >  
            <div class="st messageScreenThird">Message</div>
            <div class="messageScreenThirdDiv" style="display:table-cell; vertical-align:middle; height:110px; font-size:19px">  Select the number whose passsword you wish to reset.</div>
            </div>
<!--            <div class="st alertScreenThird">Alert</div>
            <div class="alertScreenThirdDiv" style="display:table-cell; vertical-align:middle; height:110px; font-size:19px">  Select the number whose passsword you wish to reset.</div>-->
            </div>
    </div>

<!-- when username entered with have more then one verify numbers Sreen End -->
<!-- Reset password sreen start -->
<div id="fourthScreen" class="dn">
    <div class="s1"> TaDa<br> You Can Now reset your password. </div>

                         <!--  <div class="s2">Enter your number/username </div> -->

                       <form method="POST" action="signup.php" id="forgetForm" onsubmit="return register();">
                            <div >
                                <input type="password" class="password1" id="newPassword"   name="NewPassword"  placeholder="Enter New Password" />
                                <input type="password" class="password1" id="confirmPassword"   name="ReNewPassword"  placeholder="Once again" />
                                <input type="hidden" id="key" name="key" value="" />
                                <input type="hidden" id="userName" name="userName" value="" />
                                
                                <div class="msg pa"></div>

                                <input style="background-color:#EE7836;" type="button" onclick="resetPassword($('#verifyCode').val(),$('#mobNum').val(),$('#key').val(),$('#newPassword').val(),$('#confirmPassword').val());" title="ResetPasswordConfirm" value="Confirm" name="ResetPasswordConfirm" class="forgetbutton"/>

                        </div>
                        </form>

    <div class="talkStp alertScreenFour ntf">
                    <div class="steps "   >  
            <div class="st ">Alert</div>
            <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px;" class="alertScreenFourDiv">  </div>
            </div>
    </div>
</div>
<!-- Reset password sreen End -->

<!-- Password changed Sreen start -->
<div id="fifthScreen" class="dn">
    <div class="s1"> Password Changed ! </div>

    <div>
                    <div class="steps">  
            <div class="st1 "><img src="/images/phone91_mascot.png" alt="some_text"> </div>
            <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px;  " class="alertScreenFiveDiv"> You will redirected to your account Shortly. If not redirect in 5 sec please <u><strong onclick="signIn($('#userName').val(),$('#newPassword').val())">click here</strong> </u>.  </div>
            </div>
    </div>
</div>

<!-- Password change Sreen End -->

	            </div>
	           <!-- <div class="bannerImg"><img src="images/bannerimg_3.png" width="475" height="474" alt="" title="" /></div> -->
	            <span class="clr"></span>            
	        </section>
	    </section>
        
        
        
        
	</div>
<?php }?>
    <!-- //Features -->
<script type="text/javascript">
$('.ntf').hide();
function forgotPassword(userName,type,multiFlag)
{
    if(/[^a-zA-Z0-9@.]/.test(userName))
    {
        if(multiFlag == 1)
            $('.alertScreenThird').show();
        else
            $('.alertScreenOne').show();
            
        $('.alertScreenOneDiv,.alertScreenThirdDiv').html("Invalid user name please try again with a valid user name ");
        return false;
    }
    
    if(multiFlag == null )
        multiFlag = 0;
    
    $.ajax({
        url:"action_layer.php",
        type:"post",
        dataType:"json",
        data:{'action':"forget_pass",'uname':userName,'smsCall':type,"multiFlag":multiFlag},
        success: function(response)
        {
            console.log(response.type);
            
            if(response.type == "0")
            {
                $('.alertScreenOne').show();
                $('.alertScreenOneDiv').html(response.msg);
//                show_message(response.msg,response.status)
                return false;
            }
            if(response.type == "1")
            {
                $('.alertScreenOne').hide();
                $('#firstScreen').hide();
                $('#secondScreen').show();
                $('#thirdScreen').hide();
                
                console.log(response.contact[0]);
                var num = (response.contact[0].replace(response.contact[0].substr(2,6),"XXXX"));
                $('#mobNum').val(response.contact[0]);
                $('#contactNum').html(num);
            }
            else if(response.type == "2")
            {   
                $('.alertScreenOne').hide();
                $('#firstScreen').hide();
                $('#thirdScreen').show();
                var str = "";
                $('#mobNum').val(response.contact[0]); 
                $.each(response.contact,function(key,value){
                    str += '<option value="'+value+'">'+value+'</option>';
                })
                console.log(str);
               $('#forgetSelectBox').html(str);
            }
        }
    })
}
function changeMobileNum(ths)
{
   $('#mobNum').val(ths.val()); 
}

function verifyNumber(confirmCode,mobileNum)
{
    $('.alertScreenTwo').hide();
    
    if(confirmCode == "" || /[^0-9]/.test(confirmCode) || confirmCode.length != 4)
    {
        $('.alertScreenTwo').show();
        $('.alertScreenTwo .st').html("ALert");
        $('.alertScreenTwoDiv').html("Error Invalid verification code please try again with valid code");
        return false;
    }
    if(/[^0-9]/.test(mobileNum) || mobileNum == "")
    {
        $('.alertScreenTwo').show();
        $('.alertScreenTwo .st').html("ALert");
        $('.alertScreenTwoDiv').html("Error Invalid mobile number");
        return false;
    }
    
    
    $('#verifyCode').val(confirmCode);
    $.ajax({
        url:"action_layer.php",
        type:"post",
        dataType:"json",
        data:{'action':"verifyConfirmation",'code':confirmCode,'number':mobileNum},
        success: function(response)
        {
            
            if(response != 0)
            {
                $('#secondScreen').hide();
                $('#thirdScreen').hide();
                $('#fourthScreen').show();
                $('#key').val(response.key);
                $('#userName').val(response.userName);
            }
            else
            {
                $('.alertScreenTwo').show();
                $('.alertScreenTwo .st').html("ALert");
                $('.alertScreenTwoDiv').html("Error Invalid verification code please try again");
                
                show_message("Error Invalid verification code please try again later","status");
            }
            console.log(response);
        }
    })
}

function resetPassword(confirmCode,mobileNumber,key,newPwd,confirmPwd)
{
    var errorFlag = 0;
    var msg = "";
    $('.alertScreenFour').hide();
    if(newPwd != confirmPwd)
    {
      msg = "Both password did not matched.";
      errorFlag = 1;
    }
    
    if(newPwd == "" || confirmPwd == "" || /[^a-zA-Z0-9@.:!$]/.test(newPwd) || /[^a-zA-Z0-9@.:!$]/.test(confirmPwd))
    {
        msg = "Invalid input please provide valid password only (@,.,:,!,$,a-z,A-Z,0-9) are allowed";
        errorFlag = 1;
    }
    if(mobileNumber == "" || confirmCode == "")
    {
        msg = "Error invalid information please try again or contact provider";
        errorFlag = 1;
    }
    
    if(errorFlag == 1)
    {
        $('.alertScreenFour').show();
        $('.alertScreenFourDiv').html(msg);
        return false;
    }
    
   password = newPwd;
    $.ajax({
        url:"action_layer.php",
        type:"post",
        dataType:"json",
        data:{'action':"reset_pwd",'code':confirmCode,'mobNum':mobileNumber,'key':key,'new_pwd':newPwd,'confirmPwd':confirmPwd},
        success: function(response)
        {
            
            if(response.msgtype == "success")
            {
                $('#fourthScreen').hide();
                $('#fifthScreen').show();
                
                //$('#loginUserName').val($('#userName').val());
                //$('#loginPassword').val(password);
                //$('#loginSubmit').trigger("click");
                signIn($('#userName').val(),password);
            }
            console.log(response);

            
        }
    })
}

function signIn(userName,password)
{
    $('#loginUserName').val($('#userName').val());
    $('#loginPassword').val(password);
    $('#loginSubmit').trigger("click");
}

function backToOrigin(fromDiv,toDiv,gen)
{
    gen.hide();
    fromDiv.hide();
    toDiv.show();
}
</script>
    
    
    <!-- Container -->
    
    <?php include_once('inc/incFooter.php');?>
    
   