<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
if(isset($_REQUEST["submit"]))
{
    extract($_POST);
}

if(isset($_SESSION["signup_first_name"]))
    $email = $_SESSION["signup_first_name"];

$country = $funobj->countryArray();
include_once CLASS_DIR.'reseller_class.php';
$resellerObj = new reseller_class();
$currencyArray=$resellerObj->currencyArray();
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
        <h1><span> </span></h1>
        <div class="s1"> You and I beed your number to connect.</div>
        <form name="MobileVerification" id="signup" method="post" action="action_layer.php?action=signup" class="formmargin" >  
          <div class="signUpfields">  
    	
		<select tabindex="1"  name="location" id="location" class="besnlodrdo">
<?php 
                foreach($country as $key =>$countryNames){                
                echo "<option value='$key'>$countryNames</option>";
                }?>    
</select>
	
    <span></span>
   		
	 <input name='code' value="code" type="text" id="code"  class="besnlodrdocode" onkeyup="selectOption($(this).val())" />
	    		<input type="text" name='mobileNumber' class="besignnumber" id='mobileNumber' onFocus="if (this.value == 'Phone number') { this.value = ''; }" value="Phone number" />
                
                <input style="background-color:#EE7836;" type="submit" title="Verify By SMS" value="Verify By SMS" name="submit" class="besignbutton"/>
                        <input style="background-color:#7AA300;" type="submit"  title="Verify By Call" value="Verify By Call" name="submit" class="besignbutton"/>   
                </div >
    </form>
        
        <div class="talkStp"   >
	 	<div class="steps"   >  
	<div class="st">Message</div>
             <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px;  ">  We will send you a verification code accordingly.</div>
        </div>
</div>
        <!-- First Sreen End-->
        <!-- Second Sreen Strat-->
<div class="s1"> We have sent you a verification code on 91999964XXX </div>
				  
	             <!--  <div class="s2">Enter your number/username </div> -->
                   
                   <form method="POST" action="signup.php" id="forgetForm" onsubmit="return register();">
                   	<div >
                            <input type="text" class="text1" id="forget"   name="Confirmationcode"  placeholder="Enter Confirmation code" />
                            <div class="msg pa"></div>
		            
                      	<input style="background-color:#EE7836; color:#fff;"  type="submit" title="verificationConfirm" value="Done" name="verificationConfirm" class="forgetbutton"/>
                    </div>
                    </form>
                    <div class="talkStp"  >
	 	<div class="steps"   >  
	<div class="st">Message</div>
             <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px">  In case you entered a wroug number, you can always go <u> <strong>back</strong> </u> and change it!</div>
        </div>
</div>
                    
<!-- Second Sreen End-->
<!-- final Sreen Start-->
<div class="s1"> Hey, you are done!  :) </div>

<div  >
	 	<div class="steps"   >  
	<div class="st1"><img src="/images/phone91_mascot.png" alt="some_text"> </div>
             <div style="display:table-cell; vertical-align:middle; height:110px; font-size:19px;  "> You will redirected to your accout Shortly. If not redirect in 5 sec please <u><strong>click here</strong> </u>.  </div>
        </div>
</div>

<!-- final Sreen End-->
        
        
        
        <div class="cl db pa backLinks">
        <?php include_once("inc/login_header.php") ?>
      </div>
      <span class="clr"></span> </section>
  </section>
</div>

<div class="signupform form1">
	
    
    </div>
</div>


<?php include_once('inc/incFooter.php');?>
<img src="images/loading.gif" title="image" id="loading_img"  style="display:none" />
<script type="text/javascript" src="/js/jquery.form.js"></script>
<script type="text/javascript">
function selectOption(valu)
{
    $('#location option[value="'+valu+'"]').prop('selected',true);
}
 $(document).ready(function() { 
    
    var countryCode = $('#location').val();
    $('#code').val(countryCode);
     
 
    $('#username').focus(function(){
	 $(this).next().html('<input name="check" id="check_btn" type="button" class="small green awesome fltlt" onClick="check_user_exist(); return false;" value="Check Availablity" style="line-height:23px;" />');
    })
            // bind 'myForm' and provide a simple callback function 
            $('#signup').ajaxForm({ beforeSubmit: validate,dataType: "json",success: showResponse }); 
        
       
});
 <?php
        if(isset($_SESSION["signup_email"])){
            ?>
                $("#email").val("<?php echo $_SESSION["signup_email"];?>");
        <?php
        }?>

$("#location").on('change',function(event){
  $("#code").val($(this).val().replace(/ /g,''));
  
})
</script>
</body>
</html>