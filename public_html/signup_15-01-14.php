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
<div class="motion" style="display:none;"></div>

<div class="mainFeaturesWrapper">
  <section id="featuresWrap" class="noBanner">
    <section class="innerBanner pr">
      <h1 class="mianHead fs1 whClr rglr">
        <div class="fl">Step 2</div>
        <span class="f16 db fl mrL1" style="width:87%">Sign Up</span></h1>
        <div class="cl db pa backLinks">
        <?php include_once("inc/login_header.php") ?>
      </div>
      <span class="clr"></span> </section>
  </section>
</div>

<div class="signupform form1">
	<div class="innerContainer mar0auto footerPages ">	
    <form class="mrB4" name="signup" id="signup" method="post" action="action_layer.php?action=signup">  
        <?php if(isset($_SESSION["signup_picture"])){?>
        <div class="mrT2 mrB2">
            <div class="fl pd userPhotos"><img src="<?php echo $_SESSION["signup_picture"];?>" title="image" id="loading_img" width="100" height="100"  /></div>
            <div class="fl pd lh25">
                <p>First Name:<b> <?php echo $_SESSION["signup_first_name"];?></b></p>         
                <p>Last Name:<b> <?php echo $_SESSION["signup_last_name"];?></b></p>
                <p>My Email: <b> <?php echo $_SESSION["signup_first_name"];?></b></p>
            </div>        
            <div class="cl"></div>
       </div>
        <?php }?> 
        
     <div class="signUpfields">  
		    <label class="fl db">Name:</label>
                    <input name="firstName" id="firstName" type="text" class="firstName fl" value=""/>
		       
             <span class="clr"></span>   
	</div>
        
     <div class="signUpfields">  
		    <label class="fl db">Choose Username:</label>
                    <input name="username" id="username" type="text" class="username fl" value="<?php echo $username;?>" onblur="check_user_exist(); return false;" onkeyup="check_user_exist()"/>
		    <div class="fl">
		    	<input name="check" id="check_btn" type="button" title="Check Availablity" class="small green awesome fltlt" onClick="check_user_exist(); return false;" value="Check Availablity"/>
                   </div>        
             <span class="clr"></span>   
	</div>
        
     <span class="clr"></span>
    
    <div class="signUpfields">   
    	<label class="mrT fl db">Choose your country:</label>
		<select tabindex="1"  name="location" id="location" class="uField valid">
<?php 
                foreach($country as $key =>$countryNames){                
                echo "<option value='$key'>$countryNames</option>";
                }?>    
</select>
	</div>
    <span class="clr"></span>
    
    
    <div class="signUpfields">   
   		 <label class="mrT fl db">Phone Number:</label>
	     <table cellpadding="0" cellspacing="0" border="0" class="fl">
	    	<tr>
                    <td><input name='code' value="code" type="text" id="code" style="width:100px" onkeyup="selectOption($(this).val())" /></td>
	    		<td style="padding:0 0 0 5px;"><input type="text" name='mobileNumber' id='mobileNumber' onFocus="if (this.value == 'Phone number') { this.value = ''; }" value="Phone number" /></td>
	    	</tr>
	    	<tr>
	    		<td colspan="2"><div id="moberror"></div></td>
	     	</tr>
		</table>
         <span class="clr"></span>
    </div>
    
      
    <div class="signUpfields">         
    	<label class="mrT fl db">Email:</label>
        <input type="text" name='email' id='email' onFocus="if (this.value=='Email address') { this.value=''; }"  value="<?php echo $email;?>" class="fl" onkeyup="check_email_exist()" />
   		 <div id="emailerror"></div>
         <span class="clr"></span>
    </div>
     
     
     
    <div class="signUpfields">  
			<label class="mrT fl db">Choose Password:</label>
			<input type="password" name='password' id='password' value="<?php echo $password;?>" class="fl" />
            <span class="clr"></span>
    </div>
     
    
    
    <div class="signUpfields">  
			<label class="mrT db fl">Re-Enter Password:</label>
			<input type="password" name='repassword' id='repassword' value="<?php echo $password;?>" class="fl"/>
             <span class="clr"></span>		
     </div>
     
     
    <div class="signUpfields">   
		<label class="mrT db fl">Choose Currency:</label>
     	<select name="currency" id="currency">      
             <option value="1">AED</option>
            <option value="63">INR</option>
            <option value="147">USD</option>
            
            <?php //                        foreach ( $currencyArray as $key => $value) {

                        //echo '<option value="'.$value["currencyId"].'" >'.$value["currency"].'</option>';
                       // }
                       ?>
        </select>
        <div style="display: none"></div>
         <span class="clr"></span>
    </div>  
   
        
    <div class="signUpfields">   
    	<label class="mrT fl db">User:</label>
     	<select name="client_type" id="client_type" class="fl">
            <option value="3" selected>User</option>
            <option value="2">Reseller</option>
        </select>
         <span class="clr"></span>
    </div>
    
    
    
    <div class="f11 openRegu underLine mrT1 mrB leftSignUP">
    
		    <span>By signing up, you agree to the</span>
		    
		    <a href="/voipcall/terms.php" class="bluShade">Terms of Use</a>
		    
		    <span>and</span>
		    
		    <a href="http://phone91.com/voipcall/privacy.php" class="bluShade">Privacy Policy</a>
    </div>
    
    <input type="submit" class="large blue awesome crs whClr" value="I Agree &amp; Register" id="signupSubmit" onfocus="this.blur();"/>
    
    </form>
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
//        check_email_exist =function()						
//        {
//                var email= $("#email").val();
//                email = jQuery.trim(email);
//                if(email.length >=6 && validateEmailv2(email))
//                {
//                        //$("#username").addClass("error_green");
//                        //$("#username").removeClass("error_red");
//                        //$("#check_btn").hide();
//                        $("#email").css({'background':'#fff url(images/loading.gif) no-repeat','background-position':'310px 10px'})
//                        //$("#loading_img").show();
//                        $.ajax({type: "GET",url: "action_layer.php?action=check_email_avail",data: { email: email},
//                        success: function(msg)
//                        { 
//                                $("#email").css({'background':'#fff'})
//                                //$("#loading_img").hide();
//                                if(msg==0) 
//                                {
////								alert();
//                                        $("#email").val();
//                                        $("#email").focus();
//                                        $("#email").next().removeClass("error_green").addClass("error_red").html("Already In use");	
//                                        $("#email").removeClass("error_green");
//                                        $("#email").addClass("error_red");
//                                }
//                                if(msg==1)
//                                {
//                                        $("#email").next().addClass("error_green").html("You can use this email. Available");	
//                                        $("#email").removeClass("error_red");
//                                        $("#email").addClass("error_green");
//                //			alert("");
//                                }
//                        }});	
//                }
//                else
//                {
//		    if(email.length <=6)
//                        $("#email").next().addClass("error_red").html("email Must Contain 5 character");
//		    else
//			$("#email").next().addClass("error_red").html("Email Address Must Be Valid");
//		    
//                        $("#email").addClass("error_red");
//                        $("#email").focus();
//                }
//        }
$("#location").on('change',function(event){
  $("#code").val($(this).val().replace(/ /g,''));
  
})
</script>
<?php include("analyticstracking.php") ?>
</body>
</html>