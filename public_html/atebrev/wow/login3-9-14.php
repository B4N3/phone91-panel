<?php 

//check for submit
if(isset($_REQUEST['submit']))
{
    error_reporting(-1);
    include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';

    include_once(CLASS_DIR."/account_manager_class.php");

    $acmObj=new Account_manager_class();

    $res = $acmObj->acmLogin($_REQUEST);
    $arrResp = json_decode($res,TRUE);

    if(is_array($arrResp))
    {
        echo $arrResp['msg'];
    }
}
////$subsite->get_res_id();
//
//if(isset($_SESSION['loginUrl']))
//{
//    header("Location: ".$_SESSION['loginUrl']);
//    unset($_SESSION['loginUrl']);
//    exit();
//}
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

<title><?php //echo $_SESSION['cname']; ?> Admin Login</title>
<script src="/js/jquery-1.9.1.min.js"></script> 
<script type="text/javascript" src="js/jquery.form.js"></script> 
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script language="javascript" type="text/javascript">
    
//function validate()
//{
//if(document.getElementById("login2").value=='')
//{
//	document.getElementById("err").innerHTML="Please Enter Login Name";
//	document.getElementById("login2").focus();
////alert("Please Enter Login Name");
//return false;
//}
//if(document.getElementById("pass").value=='')
//{
//	document.getElementById("err").innerHTML="Please Enter Password";
//	document.getElementById("pass").focus();	
////alert("Please Enter Password");
//return false;
//}
///*if(document.getElementById("terms").checked==false)
//{
//alert("Please Agree To The Terms Of Use");
//return false;
//}*/
//return true;
//}
</script>
<link rel="shortcut icon" href="/images/brand.ico" type="image/png">
</head>
<body class="bodyvt">

    <div class="cornerdiv">
	<div id="branding">    			
    	<div class="txtbg"></div>
        <p><?php //echo strtoupper($_SESSION['cname']); ?></p>                
	</div> 
<form id="AdminLogin" name="form1" method="post" action="#">	
	<label>User Name</label>
	<input type="text" name="uname" id="login2" value="" placeholder="username" tabindex="1"/><br />

<!--	<label>Password - <a href="#" tabindex="4">Forgot Password? </a></label>-->
        <label>Password</label>
	<input type="password" name="pwd" id="pass" value="" placeholder="********" tabindex="2"/><br />	    
<!--	<input type="checkbox" name="rememberMe" id="rememberMe" value="" tabindex="3"/>Remember Me<br />	    -->
	
    <input type="submit" name="submit" id="submitbutton" tabindex="4" value="Login"  onsubmit="return validateLogin()" class="awesome large green" />

	</form>
<!--    <a href="switch_panel.php?go=basic" class="loadBasic">Load Basic Html ( for slow connections )</a>-->
<div id="err"><?php //echo $_SESSION['msg']; $_SESSION['msg']=''; ?></div>
</div>

<script language="javascript" type="text/javascript">
function validateLogin(){
//$('#save').attr('disabled','disabled');
  $.validator.setDefaults({
  submitHandler: function() {
      
  }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#AdminLogin").validate({
                rules: {
                       
                        uname :{
                            required: true,
                            maxlength: 40,
                            minlength:5   
                        },
                        pwd:{
                            required: true,
                            maxlength: 30,
                            minlength:8
                        }
                       }
        })
        
    })
            //$("#loading").show();
            if($("#AdminLogin").valid())
                    return true; 
            else
                    return false;
}

//$(document).ready(function() { 
//
//		var options = { 
//                     
//                        url:"/action_layer.php?action=acmLogin", 
//			dataType: 'json',
//			type:'POST', 
//			beforeSubmit:  validateLogin,  // pre-submit callback 
//			success:     
//                                function(text)
//                                {
//                                    console.log(text);
//                                    var status ;
//                                    if(text.status ==1)
//                                        status = 'success';
//                                    else
//                                        status= 'error';
//                                    show_message(text.msg,status);
//                                   
//                                }
//		};
//                
//                
//                
//		$('#AdminLogin').ajaxForm(options); 
//	}); 
</script>
<!--<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>-->
</body>
</html>