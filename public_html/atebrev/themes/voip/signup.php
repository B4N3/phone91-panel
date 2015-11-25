<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';


//if(isset($_SESSION["signup_first_name"]))
//    $email = $_SESSION["signup_first_name"];

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

<?php include_once("signupForm.php"); ?>


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