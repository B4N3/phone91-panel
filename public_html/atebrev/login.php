<?php 
error_reporting(-1);
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
error_reporting(-1);
include_once(CLASS_DIR."/subsite_class.php");
error_reporting(-1);
$subsite=new subsite_class();
//$subsite->get_res_id();

if(isset($_SESSION['loginUrl']))
{
    header("Location: ".$_SESSION['loginUrl']);
    unset($_SESSION['loginUrl']);
    exit();
}
//if($_SESSION['res_id']!=2&&$_SESSION['style']!='')
//{
//	header("Location: index.php");
//	exit();
//}
//ob_start('ob_gzhandler');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo $_SESSION['cname']; ?> : Version 1.0 : Bulk SMS Solution : Bulk SMS Gateway</title>
<link rel="stylesheet" type="text/css" href="css/vt2.css" />
<script language="javascript" type="text/javascript">
function validate()
{
if(document.getElementById("login2").value=='')
{
	document.getElementById("err").innerHTML="Please Enter Login Name";
	document.getElementById("login2").focus();
//alert("Please Enter Login Name");
return false;
}
if(document.getElementById("pass").value=='')
{
	document.getElementById("err").innerHTML="Please Enter Password";
	document.getElementById("pass").focus();	
//alert("Please Enter Password");
return false;
}
/*if(document.getElementById("terms").checked==false)
{
alert("Please Agree To The Terms Of Use");
return false;
}*/
return true;
}
</script>
<link rel="shortcut icon" href="/images/brand.ico" type="image/png">
</head>
<body class="bodyvt">


<!--<div id="serverdown" style="height:100%; width:100%; position:absolute; top:0px; background:#000; z-index:99999; text-align:center; font-size:25px; filter:alpha(opacity=80); opacity: 0.8; -moz-opacity:0.8; color:#FFF;">
<br /><br /><br /><br /><br /><br /><br />
We are upgrading our server, website should be up in next 30 minutes.
</div>-->
<!--<div id="downtime" style="padding:5px; background: #c00; color: #fff; font-size:12px; font-family:Verdana, Geneva, sans-serif; text-align:center; border-bottom:1px dashed #999;">
  <b>Urgent Notice:</b> IP has been changed and New <i><b style="color:#FF0">IP is 66.7.194.59</b></i> Always change CNAME record in your control panel for branding. <i>For more info Ask your provider.</i>
</div>-->

<div class="cornerdiv">
	<div id="branding">    			
    	<div class="txtbg"></div>
        <p><?php echo strtoupper($_SESSION['cname']); ?></p>                
	</div> 
<form id="form1" name="form1" method="post" action="/">	
	<label>User Name</label>
	<input type="text" name="uname" id="login2" value="" tabindex="1"/><br />

	<label>Password - <a href="#" tabindex="4">Forgot Password? </a></label>
	<input type="password" name="pwd" id="pass" value="" tabindex="2"/><br />	    
	<input type="checkbox" name="rememberMe" id="rememberMe" value="" tabindex="3"/>Remember Me<br />	    
	
    <input type="submit" name="submit" id="submitbutton" tabindex="4" value="Login" onclick="return validate();" class="awesome large green" />

	</form>
<!--    <a href="switch_panel.php?go=basic" class="loadBasic">Load Basic Html ( for slow connections )</a>-->
<div id="err"><?php echo $_SESSION['msg']; $_SESSION['msg']=''; ?></div>
</div>

<script language="javascript" type="text/javascript">
<?php if(isset($_SESSION['msg'])){
?>
document.getElementById("login2").focus();
<?php }?>
</script>
<!--<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>-->
</body>
</html>