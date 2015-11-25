<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
$randomStr = md5($funobj->randomNumber(24)) ;
?>
<div id="progress" class="waiting" style=""><dt></dt><dd></dd></div>
<!-- TopHeader -->
<div class="wrapper">
    <!-- Header -->
   <header id="header">
        <a href="/index.php" title="Phone91" id="logo" class="fl"></a>
        <nav class="fl openRegu f16">
                <a class="thmClr" href="/index.php" title="Home" id="home">Home</a>
                <a class="" href="/voipcall/how-it-works-gtalk.php" title="How it works"  id="works">How it works</a>
                <a href="/voipcall/pricing.php" title="Pricing"  id="pricing">Pricing</a>
                <a href="/reseller.php" title="reseller"  id="reseller">Reseller</a>	                       
        </nav>
        <?php
        if(!$funobj->checkSession()){?>
        <?php if(isset($_SESSION['error'])){
            echo $_SESSION['error'];
            session_destroy('error');   }?>
         <aside class="rightsecNav f16 fr">
            <div style="position:relative">
                <!--<a href="/index.php">Sign up</a>-->
                <a href="javascript:void(0)" onclick="js.uiDrop(this, '#popwrap', true)" title="Login">Login</a>
                 <div class="popUpPanel pr rglr" style="display:none;" id="popwrap">
                    <img src="/images/topArrow.jpg" width="18" height="15" alt="" title="" class="pa t r arrow"/>
                    <form action="/index.php" method="POST" name="login">
                        
                        <input type ="hidden" name="macAddress" id="macAddress" >
                                <input type="text" name="uname" placeholder="Username"
                                onfocus="(this.value == 'Username') && (this.value = '')"
                                onblur="(this.value == '') && (this.value = 'Username')" id="loginUserName"  value="<?php if(isset($_COOKIE['usern'])) echo $_COOKIE['usern'];?>"/>
                                <input type="password" name="pwd" placeholder="Password"
                                onfocus="(this.value == 'Passaword') && (this.value = '')"
                                onblur="(this.value == '') && (this.value = 'Passaword')" id="loginPassword" value="<?php if(isset($_COOKIE['usern'])) echo $_COOKIE['passn'];?>"/>
                                
                                <div>
                                    <input type="submit" title="Sign In" name="submit" id="loginSubmit" class="fl mrB1 crs db bdrN btn rdbtn" value="Sign In"/>
                                    <div class="rem">
                                         <input type="checkbox"  name="rememberMe" id="" checked= "<?php echo (isset($_COOKIE['usern']))? 'checked':'';?>"/>
                                         <span>Remember Me</span>
                                    </div>
                                     <span class="clr"></span>
                               </div>
                               <span class="clr"></span>
                                <p class="borderBottom"><a href="#forgetpass.php" onclick="$('#forgotPassword').load('forgetpass.php')">Forgot Password</a></p>
                                <!--<p class="borderBottom"><a href="/forget.php" >Forgot Password</a></p>-->
                                <div id="forgotPassword"></div>
<!--                                <p class="taC or">Or</p>
                                <a href="/login/login-fb.php" title="Login with Facebook" class="fbLogin">Login with Facebook</a>
                                <a href="/login/login-google.php" title="Login with Google" class="googleLogin">Login with Google</a>-->
                    </form>
                 </div>
          </div>
        </aside>
        <?php }
        else{
               if($_SERVER['HTTP_HOST'] != 'voice.phone91.com')
               { ?>
                    <script> window.location = "/userhome.php#!contact.php"; </script>   
         <?php }
               else
                { ?>
                   <a href="/userhome.php#!contact.php" title="My Account" class="macc whBtn" >My Account</a>
                   

               <?php }       
        
        }?>	
        <span class="clr"></span>
    </header>
    <!-- //Header -->
</div>
<!-- //TopHeader -->
<!--Make menus items Current Selected Through Locak Storage HTML 5-->
<script type="text/javascript">
$(document).ready(function () {
    $("#header  a").click(function () {
        var id = $(this).attr("id");
        $('#' + id).siblings().find(".active").removeClass("active");
            //^ you forgot this
        $('#' + id).addClass("active");
        localStorage.setItem("selectedolditem", id);
    });
    var selectedolditem = localStorage.getItem('selectedolditem');
    if (selectedolditem != null) {
        $('#' + selectedolditem).siblings().find(".active").removeClass("active");
        $('#' + selectedolditem).addClass("active");
    }
});

var macAdd = '<?php echo (isset($_COOKIE['%$$#@!%$#%']))? $_COOKIE['%$$#@!%$#%'] : ''; ?>';

if(macAdd.length > 1 && macAdd != '')
{
    $('#macAddress').val(macAdd);
}
else
{
   macAdd =  localStorage.getItem('%$$#@!%$#%');
   
    if(macAdd.length > 1 && macAdd != '')
    {
        $('#macAddress').val(macAdd);
    }
   
}

//$.cookie('%$$#@!%$#%', '<?php echo $randomStr; ?>');

//localStorage.setItem('%$$#@!%$#%',"<?php echo $randomStr; ?>");


</script>
<?php


?>