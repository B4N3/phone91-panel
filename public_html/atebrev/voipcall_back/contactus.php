<?php	include('../config.php');
if(isset($_REQUEST['submit'])){

	$userid=$funobj->sql_safe_injection($_REQUEST['uname']);

	$pwd=$funobj->sql_safe_injection($_REQUEST['pwd']);

	$funobj->login_user($userid,$pwd);

	exit();	
} ?>

<!DOCTYPE HTML>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contact Us Phone91.com |International calling with Phone91</title>
<meta name="keywords" content="Phone91 is a leading International call provider. Phone91 provides various medium for making cheap international calls and long distance calls." />
<meta name="description" content="cheap international calls,  long distance calls." />

<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]-->

<!--[if !IE]><!--><!-- COMMENT on 15 april <link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /> --><!--<![endif]-->
<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.colorbox.js"></script>
<script language="javascript" type="text/javascript">

$(document).ready(function(){

$(".register").colorbox();//initilisation of colorbox

});

</script>
<script type="text/javascript" src="../js/jcom.js"></script>


<?php include_once('../inc/incHead.php'); ?>
</head>

<body>

	<!-- Header -->
	<?php include_once('../inc/incHeader.php'); ?>
	<!-- //Header -->

   <!-- Features -->
	<div class="mainFeaturesWrapper">
	    <section id="featuresWrap" class="noBanner">
	    	<section class="innerBanner pr">               
              	 <h1 class="mianHead">
           			 <div>Contat Us</div>
            		 <span>Get In Touch With Us!</span>
       			</h1>
       			<div class="met_short_split"><span></span></div>
               <div class="cl db pa backLinks"><?php include_once("../inc/login_header.php") ?></div>
            	<span class="clr"></span>
            </section>
        </section>
   </div>
   <!-- Features -->            
           
   <!-- Container -->        
    <section id="container">
       <section class="innerContainer   innerPagePad">
       
        <?php //include('../links.php');?>
        
            <h2 class="contactuS">
            	<span>United Kingdom</span> 
                <br/>  walkover web solutions UK limited <br/>
                 145-157 St John Street <br/> London - EC1V 4PY  England <br/> Call us on <strong>7348545</strong>
           </h2>
         
             <div class="contactRiht">
             	GTalk:- <span>support@phone91.com</span> <br/>
                Mail:- <span>support@phone91.com</span>
             </div>

         <span class="clr"></span>
      </section>
   </section>
  <!-- //Container -->           
 
<!-- Footer -->   
  <?php //include_once('../inc/footer.php');?>
  <?php include_once('../inc/incFooter.php');?>
<!-- //Footer -->    

<script type="text/javascript" src="../js/jquery.form.js"></script>
</body>
</html>