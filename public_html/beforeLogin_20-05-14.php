<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if (!isset($_SESSION['id'])) {
    $funobj->redirect("/index.php");
}

if (isset($_REQUEST["submit"])) {
    extract($_POST);
}

if (isset($_SESSION["signup_first_name"]))
    $email = $_SESSION["signup_first_name"];

$country = $funobj->countryCodes();

?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta https-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Phone91, Register!</title>
        <link  href="<?php echo CSSURL; ?>font.css" rel="stylesheet" type="text/css"/>
        <link  href="<?php echo CSSURL; ?>style.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="<?php echo JSURL; ?>jquery-1.9.1.min.js"></script>
        <!--<script language="javascript" type="text/javascript" src="<?php echo JSURL; ?>jquery.colorbox-min.js"></script>-->
        <!-- <script type="text/javascript" src="js/html5.js"></script> -->
        <!--[if IE]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript" src="<?php echo JSURL; ?>jquery.form.js"></script> 
        <script type="text/javascript" src="<?php echo JSURL; ?>sign_up.js"></script>
        
        <script>window.location.hash = ""</script>

        <!--[if IE]>
          <style type="text/css">
          input {
          filter:chroma(color=#000000);   
          }
          </style>
          <![endif]-->
        <?php include_once('inc/incHead.php'); ?>                                
        <style type="text/css">
            /*    #notification { position:absolute; top:0; left:0; right:0; z-index:999; color:#fff; font-size:20px; display:none; text-transform:capitalize;}
                #notification div { height:30px; padding:15px 30px; line-height:30px; }
                #notification i { margin:5px 5px 0 0; }
                #notification .success, #notification .warning, #notification .error, #notification .information { display: block; }
                #notification .success { background:#1fbaa6 }
                #notification .warning { background:#ffcc00; }
                #notification .error { background:#ff5c33; }
                #notification .information { background:#30a6d9; }
                .motion {  background: url("../images/Phone91-preloader.png") no-repeat;    bottom: 32px;    height: 174px;    position: absolute;    right: 330px;    width: 150px;    z-index: 9999999;}*/
        </style>
    </head>
    <body>
        
        <?php include_once('inc/incHeader.php'); ?>
        <!--div use to show notification  Don't delete as this is very useful for showing notification  -->
        <div id="notification"> </div>
        <div class="mainFeaturesWrapper">
            <section id="featuresWrap1" class="noBanner">
                <section class="innerBanner pr" >
                    <!-- First Sreen Strat-->
                 
                 <div id="firstScreen" >
                    <form id="currencyForm">
                        <select id="currencySelectBox">
                            <option value="147">USD</option>
                            <option value="63">INR</option>
                      <option value="108">NZD</option>
                            <option value="1">AED</option>
                        </select>
                        <input onclick="updateUserCurrency($('#currencySelectBox').val());" class="forgetbutton" style="background-color:#EE7836; color:#fff;" type="button" id="currencyButton" name="currencyButton" value="Done" />
                    </form>
                </div>
                    <div id="secondScreen" class="dn"> 
                    <h1><span> </span></h1>
                    <div class="s1"> You and I need your number to connect.</div>
                    <form name="MobileVerification"  class="formmargin" >  
                        <div class="signUpfields">  

                            <select tabindex="1"  name="location" id="location" class="besnlodrdo">
                                <?php
                                foreach ($country as $key => $countryNames) {
                                    echo "<option value='$key'>$countryNames</option>";
                                }
                                ?>    
                            </select>

                            <span></span>

                            <input name='countryCode' value="" type="text" id="code"  class="besnlodrdocode" onkeyup="selectOption($(this).val())" />
                            <input type="text" name='mobileNumber' class="besignnumber" id='mobileNumber' placeholder="Phone number" value="" />

                            <input style="background-color:#EE7836;" type="button" onclick="saveMobileNumber('SMS')" title="Verify By SMS" value="Verify By SMS" name="submit" class="besignbutton"/>
                            <input style="background-color:#7AA300;" type="button" onclick="saveMobileNumber('CALL')" title="Verify By Call" value="Verify By Call" name="submit" class="besignbutton"/>   
                        </div >
                    </form>

                    <div class="talkStp"   >
                        <div class="steps"   >  
                            <div class="st">Message</div>
                            <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px;  ">  We will send you a verification code accordingly.</div>
                        </div>
                    </div>
                </div>
                    <!-- First Sreen End-->
                    
                <div id="thirdScreen" class="dn">
                    <!-- Second Sreen Strat-->
                    <div class="s1"> We have sent you a verification code on <span id="mobNum">91999964XXX</span> </div>

                    <!--  <div class="s2">Enter your number/username </div> -->

                    <form method="POST" action="">
                        <div >
                            <input type="text" class="text1" id="confirmationCode"   name="confirmationCode"  placeholder="Enter Confirmation code" />
                            <div class="msg pa"></div>

                            <input style="background-color:#EE7836; color:#fff;"  type="button" onclick="confirmMobileNumber($('#confirmationCode').val())" title="verificationConfirm" value="Done" name="verificationConfirm" class="forgetbutton"/>
                        </div>
                    </form>
                    <div class="talkStp"  >
                        <div class="steps"   >  
                            <div class="st">Message</div>
                            <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px">  In case you entered a wrong number, you can always go <u> <strong onclick="toggleDiv('#secondScreen','#thirdScreen')">back</strong> </u> and change it!</div>
                        </div>
                    </div>
                </div>
               
                    <!-- Second Sreen End-->
                <div id="fourthScreen" class="dn">
                    <!-- final Sreen Start-->
                    <div class="s1"> Hey, you are done!  :) </div>
                    <?php include_once("signUpGoogleCheckout.php") ?>
                    <div>
                        <div class="steps" >  
                            <div class="st1"><img src="/images/phone91_mascot.png" alt="some_text"> </div>
                            <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px;  "> You will redirected to your accout Shortly. If not redirect in 5 sec please <u><strong>click here</strong> </u>.  </div>
                        </div>
                    </div>

                    <!-- final Sreen End-->

                </div>

                    <div class="cl db pa backLinks">
                        <?php include_once("inc/login_header.php") ?>
                    </div>
                    <span class="clr"></span> </section>
            </section>
        </div>

        <div class="signupform form1">


        </div>
    </div>


    <?php include_once('inc/incFooter.php'); ?>
    <img src="images/loading.gif" title="image" id="loading_img"  style="display:none" />
    <script type="text/javascript" src="/js/jquery.form.js"></script>
    <script type="text/javascript">
        function toggleDiv(showDiv,hideDiv)
        {
            $(hideDiv).hide();
            $(showDiv).show();
        }
                        function selectOption(valu)
                        {
                            $('#location option[value="' + valu + '"]').prop('selected', true);
                        }
                        $(document).ready(function() {

                            var countryCode = $('#location').val();
                            console.log(countryCode);
                            $('#code').val(countryCode);


                            $('#username').focus(function() {
                                $(this).next().html('<input name="check" id="check_btn" type="button" class="small green awesome fltlt" onClick="check_user_exist(); return false;" value="Check Availablity" style="line-height:23px;" />');
                            })
                            // bind 'myForm' and provide a simple callback function 
                            $('#signup').ajaxForm({beforeSubmit: validate, dataType: "json", success: showResponse});


                        });
                        
                        
                        function saveMobileNumber(carierType)
                        {
                            var mobileNumber = $('#mobileNumber').val();
                            var code = $('#code').val();
                            if(/[^0-9]/.test(mobileNumber) || !(/^[0-9]{8,18}$/.test(mobileNumber)))
                            {
                                show_message("Invalid mobile number please enter a valid mobile number","error");
                                return false; 
                            }
                            if(/[^0-9]/.test(code) || code === undefined || code == "")
                            {
                                show_message("Invalid country code please select a country","error");
                                return false;
                            }
                            $.ajax({
                                url:"controller/signUpController.php",
                                type:"post",
                                dataType:"json",
                                data:{"call":"verifyContactNumber","mobileNumber":mobileNumber,"countryCode":code,"carrierType":carierType},
                                success:function(response)
                                {
                                    
                                    if(response.status=="success")
                                    {
                                        $('#mobNum').html(response.data);
                                        
                                        $('#secondScreen').hide();
                                        $('#thirdScreen').show();
                                        show_message(response.msg,response.status);
                                    }
                                    else
                                    {
                                        show_message(response.msg,response.status);
                                    }
                                }
                            })
                        
                        
                        }
                        
                        function confirmMobileNumber(key)
                        {
                            if(/[^0-9]/.test(key) || key.length < 1 || key.length > 5)
                            {
                                show_message("Invalid confrim code only numeric values are allowed of 1-5 length","error")
                                return false;
                            }
                            $.ajax({
                                url:"controller/signUpController.php",
                                type:"post",
                                dataType:"json",
                                data:{"call":"verifyNumber","key":key},
                                success:function(response)
                                {
                                    if(response.msgtype=="success")
                                    {
                                         
                                        $('#thirdScreen').hide();
                                        $('#fourthScreen').show();                                        
                                        show_message(response.msg,response.msgtype);
                                        setTimeout(function(){window.location.href = "/userhome.php"},2000);
                                    }
                                    else
                                    {
                                        show_message(response.msg,response.msgtype);
                                    }
                                }
                            })
                        }
                        
                        function updateUserCurrency(currencyId)
                        {
                            if(/[^0-9]/.test(currencyId) || currencyId == "")
                            {
                                show_message("Invalid currency please select a valid currency","error")
                                return false;
                            }
                            $.ajax({
                                url:"controller/signUpController.php",
                                type:"post",
                                dataType:"json",
                                data:{"call":"updateCurrency","currencyId":currencyId},
                                success:function(response)
                                {
                                    if(response.status=="success")
                                    {
//                                        $('#thirdScreen').hide();                                        
//                                        $('#fourthScreen').show();
                                          $('#firstScreen').hide(); 
                                          $('#secondScreen').show();   
                                        show_message(response.msg,response.status);
                                        //setTimeout(function(){window.location.href = "/userhome.php"},3000);
                                    }
                                    else
                                    {
                                        show_message(response.msg,response.status);
                                    }
                                }
                            })
                        }
<?php
if (isset($_SESSION["signup_email"])) {
    ?>
                            $("#email").val("<?php echo $_SESSION["signup_email"]; ?>");
    <?php
}
?>

                        $("#location").on('change', function(event) {
                            $("#code").val($(this).val().replace(/ /g, ''));

                        })
                        
<?php 
//if($_SESSION['loginFlag'] == 0)
//{
?>
//$('#firstScreen').show(); 
//$('#thirdScreen').show();     
<?    
//}
//else
if($_SESSION['loginFlag'] == 1){ ?>
    $('#firstScreen').hide(); 
    $('#secondScreen').show(); 
<?php } ?>
    
    </script>
</body>
</html>