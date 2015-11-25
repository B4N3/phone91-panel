<?php 


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Admin</title>
<link rel="stylesheet" type="text/css" href="css/vt2.css" />
<script src="/js/jquery-1.9.1.min.js"></script> 
<script type="text/javascript" src="js/jquery.form.js"></script> 
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
 <script type="text/javascript" src="js/sign_up.js"></script>
<script type="text/javascript" src="js/cobj.js"></script>
<script type="text/javascript" src="js/jcom.js"></script>
<style>
.error { font-size:12px; color:red; padding-bottom:5px;}
.popUpPanel { width:260px; padding:16px; border:2px solid #000; border-radius:10px; position:absolute; top:40px; right:0; background:#fff; z-index:9999 }
input[type="text"], .popUpPanel input[type="password"] {
background: #F7F7F7;
border: 1px solid #EEEEEE;
color: #858585;
height: 30px;
line-height: 30px;
padding: 0 5px;
width: 100%;
margin: 0 0 15px 0;
}
input, textarea, select, input[type="text"], input[type="password"] {

outline: none;

box-sizing: border-box;
font-family: 'Open Sans', sans-serif;
font-size: 14px;

}
.btn {
padding: 3px 15px 3px;
background-color: #296FA2;
font-size: 18px;
color: #FFF;
text-shadow: #134D77 0 1px 2px;
font-weight: normal;
transition: all 0.2s ease 0s;
}

</style>   

<link rel="shortcut icon" href="/images/brand.ico" type="image/png">
</head>
<body class="bodyvt">

    <div class="cornerdiv popUpPanel" style="display:block;top: 112px;right: 864px">
	<div id="branding">    			
    	<div class="txtbg"></div>
                    
	</div> 
        <form id="AdminCode" name="form1" method="post" action="#">	
	
        <label>Please Enter Verification Code :</label>
	<input type="password" name="code" id="code" value="" placeholder="****" tabindex="2"/><br />
       
	
    <input type="submit" name="submit" id="submitbutton" tabindex="4" value="Done"   class="fl mrB1 crs db bdrN btn rdbtn awesome large green" />

	</form>

<div id="err"><?php //echo $_SESSION['msg']; $_SESSION['msg']=''; ?></div>
</div>

<script language="javascript" type="text/javascript">
     $(document).ready(function() {
        // validate the comment form when it is submitted	
        $("#AdminCode").validate({
                rules: {
                       code:{
                            required: true,
                            maxlength: 4,
                            minlength:4
                        }
                       }
        })
        
    });
                      


function verifiedCode(){
     if($("#AdminCode").valid())
                    return true; 
            else
                    return false;
}

$(document).ready(function(){
    
    var options={
        
        url:"/controller/acmController.php?action=accountManagerCodeVerfy",
        type:"post",
        dataType:'json',
        beforeSubmit: verifiedCode,
        success: function(data){
           
           if(data.status == "success"){
               window.location = '/wow/index.php#!manage-client.php|manage-client-setting.php';
           }
           
        }
      
        
    }
    
    $("#AdminCode").ajaxForm(options);
    
});

</script>

</body>
</html>