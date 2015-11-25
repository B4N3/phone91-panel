<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/whiteLabelConfig.php';

/*  * 
// *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
?>
<!--<div style="width:25%;float:left; margin-left: 50px;">
    <h3>Login</h3>
    <form action="/index2.php" method="POST" name="login">
                                <input type="text" name="uname" placeholder="Username"
                                id="loginUserName"  value=""/>
                                <input type="password" name="pwd" placeholder="Password"
                                id="loginPassword" value=""/>
                                
                                <div>
                                    <input type="submit" title="Sign In" name="submit" id="loginSubmit"  value="Sign In"/>
                                    <div class="rem">
                                         <input type="checkbox"  name="rememberMe" id="" checked= "<?php // echo (isset($_COOKIE['usern']))? 'checked':'';?>"/>
                                         <span>Remember Me</span>
                                    </div>
                                     <span class="clr"></span>
                               </div>
                               <span class="clr"></span>
                                <p class="borderBottom"><a href="#forgetpass.php" onclick="$('#forgotPassword').load('forgetpass.php')">Forgot Password</a></p>
                                <p class="borderBottom"><a href="/forget.php" >Forgot Password</a></p>
                                <div id="forgotPassword"></div>
                                <p class="taC or">Or</p>
                                <a href="/login/login-fb.php" title="Login with Facebook" class="fbLogin">Login with Facebook</a>
                                <a href="/login/login-google.php" title="Login with Google" class="googleLogin">Login with Google</a>
                    </form>
</div>-->
</head>
<?php include_once('inc/incHead.php'); ?>
<!-- Signup validation Js -->
<script type="text/javascript" src="js/sign_up.js"></script>
<script type="text/javascript" src="js/cobj.js"></script>
<script type="text/javascript" src="js/jcom.js"></script>
<style>
.error { font-size:12px; color:red; padding-bottom:5px;}
</style>
</head>
<body>
<div id="progress" class="waiting" style=""><dt></dt><dd></dd></div>
<!-- TopHeader -->
<div class="wrapper">
    <!-- Header -->
   <header id="header">
	<!--<a href="/index.php" title="Phone91" id="logo" class="fl"></a>-->
	<!--<nav class="fl openRegu f16">
                <a class="thmClr" href="/index.php" title="Home" id="home">Home</a>
                <a class="" href="/voipcall/how-it-works-gtalk.php" title="How it works"  id="works">How it works</a>
                <a href="/voipcall/pricing.php" title="Pricing"  id="pricing">Pricing</a>
                <a href="/reseller.php" title="reseller"  id="reseller">Reseller</a>	                       
     </nav>-->
       
         <aside class="rightsecNav f16 fr">
            <div style="position:relative">
                <!--<a href="/index.php">Sign up</a>-->
				<!--<a href="javascript:void(0)" onclick="js.uiDrop(this, '#popwrap', true)" title="Login">Login</a>-->
                <div class="popUpPanel pr rglr" style="display:block;top: 112px;right: 392px" id="popwrap">
                <p class="error"> <?php
						if(!$funobj->checkSession()){?>
						<?php if(isset($_SESSION['error'])){
							echo $_SESSION['error'];
							session_destroy('error');   }?>
       			 </p>
				<!--<img src="/images/topArrow.jpg" width="18" height="15" alt="" title="" class="pa t r arrow"/>-->
                    <form action="/index.php" method="POST" name="login">
                            <input type="text" name="uname" placeholder="Username"
                            onfocus="(this.value == 'Username') && (this.value = '')"
                            onblur="(this.value == '') && (this.value = 'Username')" id="loginUserName"  value="<?php echo $_COOKIE['usern'];?>"/>
                            <input type="password" name="pwd" placeholder="Password"
                            onfocus="(this.value == 'Passaword') && (this.value = '')"
                            onblur="(this.value == '') && (this.value = 'Passaword')" id="loginPassword" value="<?php echo $_COOKIE['passn'];?>"/>
                            <div>
                                <input type="submit" title="Sign In" name="submit" id="loginSubmit" class="fl mrB1 crs db bdrN btn rdbtn" value="Sign In"/>
                                <div class="rem">
                                     <input type="checkbox"  name="rememberMe" id="" checked= "<?php echo (isset($_COOKIE['usern']))? 'checked':'';?>"/>
                                     <span>Remember Me</span>
                                </div>
                                 <span class="clr"></span>
                           </div>
                           <span class="clr"></span>
                            <p class="borderBottom"><a href="#forgetpass.php" onClick="$('#forgotPassword').load('forgetpass.php')">Forgot Password</a></p>
                            <!--<p class="borderBottom"><a href="/forget.php" >Forgot Password</a></p>-->
                            <div id="forgotPassword"></div>
                    </form>
                 </div>
          </div>
        </aside>
        <?php }
        else{?>
           <a href="/userhome.php#!contact.php" title="My Account" class="macc whBtn" >My Account</a>
        <?php }?>	
        <span class="clr"></span>
    </header>
    <!-- //Header -->
</div>
<!-- //TopHeader -->
<!--Make menus items Current Selected Through Locak Storage HTML 5-->
<script type="text/javascript">
$(document).ready(function () {
//    $("#header  a").click(function () {
//        var id = $(this).attr("id");
//        $('#' + id).siblings().find(".active").removeClass("active");
//            //^ you forgot this
//        $('#' + id).addClass("active");
//        localStorage.setItem("selectedolditem", id);
//    });
//    var selectedolditem = localStorage.getItem('selectedolditem');
//    if (selectedolditem != null) {
//        $('#' + selectedolditem).siblings().find(".active").removeClass("active");
//        $('#' + selectedolditem).addClass("active");
//    }
});
</script></body>    