<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/whiteLabelConfig.php';
/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */

?>
<?php include_once('inc/incHead.php'); ?>
<!-- Signup validation Js -->
<!--<script type="text/javascript" src="/js/jquery.validate.min.js"></script>-->
<script type="text/javascript" src="/js/sign_up.js"></script>
<!--<script type="text/javascript" src="/js/cobj.js"></script>-->
<!--<script type="text/javascript" src="/js/jcom.js"></script>-->
<script type="text/javascript" src="/js/jquery.form.js"></script>
<style type="text/css">
.msg { font-size:12px}
#signupForm  input[type="text"],  #signupForm input[type="password"] { margin:15px 0 0 ;}
#sgbtn{  border: 0 none;    color: #858585;    font-family: 'Open Sans',sans-serif;    font-size: 14px;    outline: medium none;    padding:5px 10px; margin:15px 0 0 0 ; color:#fff;}
input[type="submit"]::-moz-focus-inner, input[type="button"]::-moz-focus-inner    {border : 0px; outline : none} 
</style>
</head>
<body>
<div id="progress" class="waiting" style=""><dt></dt><dd></dd></div>
<!-- TopHeader -->
<div class="wrapper">
    <!-- Header -->
            <!--<a href="/index.php" title="Phone91" id="logo" class="fl"></a>-->
            <!--<nav class="fl openRegu f16">
                <a class="thmClr" href="/index.php" title="Home" id="home">Home</a>
                <a class="" href="/voipcall/how-it-works-gtalk.php" title="How it works"  id="works">How it works</a>
                <a href="/voipcall/pricing.php" title="Pricing"  id="pricing">Pricing</a>
                <a href="/reseller.php" title="reseller"  id="reseller">Reseller</a>	                       
        </nav>-->
       
         <aside class="rightsecNav f16 fr">
            <div style="position:relative">
               <div class="popUpPanel pr rglr" style="display:block;top: 112px;right: 392px;width: 331px" id="popwrap">
               <div style="position:relative;bottom:7px;">Sign Up It's free</div>            
                <!--<img src="/images/topArrow.jpg" width="18" height="15" alt="" title="" class="pa t r arrow"/>-->
                 <form method="POST" action="javascript:;" id="signupForm" onSubmit="">
                 <input type="text" id="firstName"  name="firstName" value="" placeholder="First Name">
                <input type="text" id="lastName" name="lastName" value="" placeholder="Last Name">
                <input type="text" id="username" name="username" value="" onBlur="" placeholder="Choose username">
                <label class="msg"></label>
                <input type="password" id="password" name="password" value="" placeholder="Choose password" onBlur="">
                <label class="msg"></label>
                <input type="text" id="email" value="" name="email" placeholder="Email" /> <!-- onBlur="check_email_exist()" -->
                        <label class="msg"></label>
						
                                <div>
                                    <button id="sgbtn" class="fl mrB1 crs db bdrN btn rdbtn" type="submit" name="submit">Sign up</button>
								
                               </div>
                               <span class="clr"></span>
						
                    </form>
                 </div>
          </div>
        </aside>
		
        <span class="clr"></span>
    
</div>
<script type="text/javascript">
    
    
    function validateEmailReg(value, element)
{
   var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if(emailReg.test(value)){
	    return true;
	}else
	    return false;
}

function userNameValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
     if(/[^a-zA-Z0-9\@\.\_]+/.test(value))
         return false;
     else
         return true;
}

function checkuser(username)
{
    var ret = 0;
    $.ajax({type: "POST",url: "action_layer.php?action=check_avail",data: { "username": username},async:false,
    success: function(msg)
    { 
   
           console.log(msg); 
            if(msg==1)
            {
                    ret = 1;
            }
    }
    });
    return ret;
}
function checkUserExist(value, element)
{
    
   return checkuser(value);
//       return false;
//   else
//       return true;
    
}

function validateEmail(value, element)
{
    
    return checkEmailExist();
}
//function validateUser(value, element)
//{
//    check_user_exist();
//    console.log(usernameMsg);
//    if(parseInt(usernameMsg)){
//	    return true;
//	}else
//	    return false;
//}
function textOnlyValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
     if(/[^a-zA-Z ]+/.test(value))
         return false;
     else
         return true;
}
function passwd(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
     if(/[^a-zA-Z0-9\@\$\}\{\.\_\-\(\)\]\[\:]+/.test(value))
         return false;
     else
         return true;
}

$.validator.addMethod("textOnly", textOnlyValidation, "Please enter only alpha characters( a-z ).");
$.validator.addMethod("passwd", passwd, "Invalid password only ( a-z A-Z 0-9 @,$,{,},.,_,-,(,),[,],: ) are allowed");
$.validator.addMethod("validateEmail", validateEmail, "Already exist");
//$.validator.addMethod("validateUser", validateUser, "Invalid username please try different username");
$.validator.addMethod("validateEmailReg", validateEmailReg, "Invalid Email please enter a valid email");
$.validator.addMethod("checkUserName", userNameValidation, "Invalid user name");
$.validator.addMethod("checkUserExist", checkUserExist, "User name already exist");



  $('#signupForm').validate({
        
        onfocusout: function(element) { $(element).valid(); },
        rules: {
            firstName :{
                textOnly:true,
                required: true,
                minlength: 3,
                maxlength: 18

                        },
            lastName :{
                textOnly:true,
                required: true,
                minlength: 3,
                maxlength: 18

                        },
            password:{
                passwd:true,
                required:true,
                minlength:7,
                maxlength:18
            },
            email :{
                required:true,
                validateEmailReg : true,
                validateEmail: true
                    }, 
            username :{
                checkUserName:true,
                required:true,
                minlength:6,
                maxlength:25,
                checkUserExist:true
                    } 
            },
            onkeyup: false
           
        })

function validateSignUpForm()
{
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : function the all plan form before submitting for javascript validation
     **/
    console.log("called");
   
  
    if($("#signupForm").valid())
                return true; 
        else
        {
            //p91Loader('stop');
            return false;
        }
}



   var options = {
        url : "/controller/signUpController.php",
        dataType: "JSON",
        type: "POST",
//        beforeSubmit:validateSignUpForm,
        data : {"call":"signupWlabel"},
        success:function(response){
            console.log(response);
//            show_message(response.msg,response.status)
            if(response.status == "success")
                window.location.href = 'userhome.php#!contact.php';
            else
                show_message(response.msg,response.status)
        }
    }
    $(document).ready(function(){
    $('#signupForm').ajaxForm(options);
    });
</script>

</body>    