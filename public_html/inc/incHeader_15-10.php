<?php include_once($_SERVER['DOCUMENT_ROOT'].'/analyticstracking.php'); 
?>
<!-- TopHeader -->
<div class="wrapper">
    <!-- Header -->
   <header id="header">
        <a href="/index.php" title="Phone91" id="logo" class="fl"></a>
        <nav class="fl openRegu f16">
                <a class="thmClr" href="/index.php" title="Home" id="home">Home</a>
                <a class="" href="/voipcall/how-it-works-gtalk.php" title="How it works"  id="works">How it works</a>
                <a href="/voipcall/pricing.php" title="Pricing"  id="pricing">Pricing</a>	                       
        </nav>
        <?php
        if(!$funobj->login_validate()){?>
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
                                <input type="text" name="uname" placeholder="Username"
                                onfocus="(this.value == 'Username') && (this.value = '')"
                                onblur="(this.value == '') && (this.value = 'Username')" id="" class="rglr lh24 mrB1" value="<?php echo $_COOKIE['username'];?>"/>
                                <input type="password" name="pwd" placeholder="Password"
                                onfocus="(this.value == 'Passaword') && (this.value = '')"
                                onblur="(this.value == '') && (this.value = 'Passaword')" id="" class="rglr lh24 mrB1" value="<?php echo $_COOKIE['password'];?>"/>
                                <input type="submit" title="Sign In" name="submit" id="" class="fl mrB1 crs db bdrN btn rdbtn" value="Sign In"/>
                                <div class="fr">
                                     <input type="checkbox" class="fl db mrT mrR top" name="rememberMe" id="" 
                                     checked= "<?php echo (isset($_COOKIE['username']))? 'checked':'';?>"/>
                                     <span class="fl db">Remember Me</span>
                                     <span class="clr"></span>
                                </div>
                                <span class="clr"></span>
                                <p class="borderBottom pdB2 mrB2"><a href="#forgetpass.php" onclick="$('#forgotPassword').load('forgetpass.php')">Forgot Password</a></p>
                                <div id="forgotPassword"></div>
                                <p class="taC mrB2">Or</p>
                                <a href="/login/login-fb.php" title="Login with Facebook" class="fbLogin">Login with Facebook</a>
                                <a href="/login/login-google.php" title="Login with Google" class="googleLogin">Login with Google</a>
                    </form>
                 </div>
          </div>
        </aside>
        <?php }
        else{?>
           <a href="/userhome.php" title="My Account" class="whBtn round mrT1 fr" title="My Account">My Account</a>
        <?php }?>	
        <span class="clr"></span>
    </header>
    <!-- //Header -->
</div>
<!-- //TopHeader -->
<!--Make menus items Current Selected Through Locak Storage HTML 5-->
<script type="text/javascript">
$(document).ready(function () {
    $("#header nav a").click(function () {
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
</script>