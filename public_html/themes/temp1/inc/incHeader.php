<div id="progress" class="waiting" style=""><dt></dt><dd></dd></div>
<!-- TopHeader -->
<div class="wrapper">
    <!-- Header -->
   <header id="header">
        <a href="/index.php" title="Phone91" id="logotemp" class="fl" style="background-image: url('<?php echo _MANAGE_PATH_._LOGO_IMAGE_; ?>')"></a>
        <nav class="fl openRegu f16">
                <a class="thmClr" href="index.php" title="Home" id="home">Home</a>
                <a href="pricing.php" title="Pricing"  id="pricing">Pricing</a>
                
                <a href="contactUs.php" title="contact"  id="contact">Contact us</a>	                       
        </nav>
        <?php
        if(!$funobj->login_validate()){?>
        <?php if(isset($_SESSION['error'])){
            echo $_SESSION['error'];
            session_destroy('error');   }?>
         <aside class=" f16 fr temp1header" >

                    <form action="/index.php" method="POST" name="login" >
                                <input type="text" name="uname" placeholder="Username"  id=""  value=""  class="temponelogintext"/>
                                <input type="password" name="pwd" placeholder="Password"  id="" value="" class="temponelogintext"/>
                                <input type="submit" title="Sign In" name="submit" id=""  value="Sign In" class="temp1lobtn"/>
                                   
                              
                                <p style="margin-left:1%; font-size:15px; margin-top:3%"><a href="forget.php" >Forgot Password</a></p>
                                
                                
                               
                    </form>
                 
          
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
</script>