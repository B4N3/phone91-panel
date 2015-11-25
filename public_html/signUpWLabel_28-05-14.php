<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/whiteLabelConfig.php';

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */

?>

<?php include_once('inc/incHead.php'); ?>
<!-- Signup validation Js -->
<script type="text/javascript" src="/js/sign_up.js"></script>

<script type="text/javascript" src="/js/cobj.js"></script>
<script type="text/javascript" src="/js/jcom.js"></script>
</head>
<body>
<div id="progress" class="waiting" style=""><dt></dt><dd></dd></div>
<!-- TopHeader -->
<div class="wrapper">
    <!-- Header -->
   
<!--        <a href="/index.php" title="Phone91" id="logo" class="fl"></a>-->
<!--        <nav class="fl openRegu f16">
                <a class="thmClr" href="/index.php" title="Home" id="home">Home</a>
                <a class="" href="/voipcall/how-it-works-gtalk.php" title="How it works"  id="works">How it works</a>
                <a href="/voipcall/pricing.php" title="Pricing"  id="pricing">Pricing</a>
                <a href="/reseller.php" title="reseller"  id="reseller">Reseller</a>	                       
        </nav>-->
       
         <aside class="rightsecNav f16 fr">
            <div style="position:relative">
                
                       <div class="popUpPanel pr rglr" style="display:block;top: 112px;right: 392px;width: 331px" id="popwrap">
                         <div style="position:relative;bottom:7px;">Sign Up It's free</div>            
<!--                    <img src="/images/topArrow.jpg" width="18" height="15" alt="" title="" class="pa t r arrow"/>-->
                         <form method="POST" action="action_layer.php?action=signup" id="signupForm" onsubmit="return register();">
                        <input type="text" id="username" name="username" value="" onblur="check_user_exist()" placeholder="Choose username">
                        <label class="msg"></label>
                        <input type="password" id="password" name="password" value="" placeholder="Choose password" onblur="check_password_strength();">
                        <label class="msg"></label>
                        <input type="text" id="email" value="" name="email" placeholder="Email" onblur="check_email_exist()">
                        <label class="msg"></label>
                
<!--                                <input type="text" name="uname" placeholder="Username"
                                onfocus="(this.value == 'Username') && (this.value = '')"
                                onblur="(this.value == '') && (this.value = 'Username')" id="loginUserName"  value="<?php echo $_COOKIE['usern'];?>"/>-->
<!--                                <input type="password" name="pwd" placeholder="Password"
                                onfocus="(this.value == 'Passaword') && (this.value = '')"
                                onblur="(this.value == '') && (this.value = 'Passaword')" id="loginPassword" value="<?php echo $_COOKIE['passn'];?>"/>-->
                                
                                <div>
                                    <button id="sgbtn" class="fl mrB1 crs db bdrN btn rdbtn" type="submit" name="submit">Sign up</button>
<!--                                    <input type="submit" title="Sign In" name="submit" id="loginSubmit" class="fl mrB1 crs db bdrN btn rdbtn" value="Sign In"/>-->
<!--                                    <div class="rem">
                                         <input type="checkbox"  name="rememberMe" id="" checked= "<?php echo (isset($_COOKIE['usern']))? 'checked':'';?>"/>
                                         <span>Remember Me</span>
                                    </div>
                                     <span class="clr"></span>-->
                               </div>
                               <span class="clr"></span>
<!--                                <p class="borderBottom"><a href="#forgetpass.php" onclick="$('#forgotPassword').load('forgetpass.php')">Forgot Password</a></p>-->
                                <!--<p class="borderBottom"><a href="/forget.php" >Forgot Password</a></p>-->
<!--                                <div id="forgotPassword"></div>
                              -->
                                
                    </form>
                 </div>
          </div>
        </aside>
       
       
<!--           <a href="/userhome.php#!contact.php" title="My Account" class="macc whBtn" >My Account</a>-->
       	
        <span class="clr"></span>
  
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