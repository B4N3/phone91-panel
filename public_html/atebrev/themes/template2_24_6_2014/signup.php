
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
<?php // include_once('inc/incHeader.php'); ?>
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
        <?php // include_once("inc/login_header.php") ?>
      </div>
      <span class="clr"></span> </section>
  </section>
</div>
<?php include_once(ROOT_DIR."signupForm.php"); ?>



<?php 

//include_once('inc/incFooter.php');
?>
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
<?php // include("analyticstracking.php") ?>
</body>
</html>