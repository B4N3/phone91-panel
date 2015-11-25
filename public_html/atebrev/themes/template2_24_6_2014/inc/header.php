<?php 
$test = $_SERVER['PHP_SELF'];
$arr=explode("/",$test); 
?>
<?php
include_once(_MANAGE_PATH_."generalData.php");

?>
<!---------header starts--------->
<div id="header">
	<div class="wrapper">
        <div id="logo">
            <img src="<?php echo _MANAGE_PATH_.$logoImage; ?>" alt="logo_image" height="60px"/>
        </div>
        
        <!--toplinks-->
        <div id="toplinks">
            <ul>
                    <li><a href="signUpWLabel.php" class="mrR1"><span></span> Sign Up</a></li>
                    <li><a href="loginWLabel.php">Sign In</a></li>
            </ul>
   		 </div>
        <div class="clR"></div>
   	 <!--//toplinks-->   

    <!----navigation---->  
        <div id="nav">
            <ul>
                <li><a <?php if(end($arr)=='index.php') {echo'class="active"';}?> href="index.php">Home</a></li>
                <li><a <?php if(end($arr)=='about.php') {echo'class="active"';}?> href="about.php">About Us</a></li>
                <li><a <?php if(end($arr)=='pricing.php') {echo'class="active"';}?> href="pricing.php">Pricing</a></li>
                <li><a <?php if(end($arr)=='contactUs.php') {echo'class="active"';}?> href="contactUs.php">Contact</a></li>
            </ul>
    	</div>
   <!----//navigation----> 
   
   	<!--Responsive Menus-->
        <div class="resizeNav dn pr"> 
           <a href="javascript:void(0)"  onclick="uiDrop(this,'#showMenu', 'true')" class="resizeIcn">|||</a>
           <div id="showMenu" style="display:none;" class="dropdwn">
                <a class="bgTrns <?php if(end(explode("/", $_SERVER['PHP_SELF'])) =='index.php'){echo 'active';} ?>" href="index.php">Home</a>
                <a class="bgTrns <?php if(end(explode("/", $_SERVER['PHP_SELF'])) =='about.php'){echo 'active';} ?>" href="about.php">About us</a>
                <a class="bgTrns <?php if(end(explode("/", $_SERVER['PHP_SELF'])) =='pricing.php'){echo 'active';} ?>" href="pricing.php#tab1">Pricing</a>
                <a class="bgTrns <?php if(end(explode("/", $_SERVER['PHP_SELF'])) =='contactUs.php'){echo 'active';} ?>" href="contactUs.php">Contact</a>
            </div>
        </div>
     <!--//Responsive Menus-->
     
        <div class="pageName"> 
           <?php
                 echo curPageName();
            ?>
     </div>
	</div>
</div>
<!--//header-->
<?php
	function curPageName() {
	$file = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	$filename = explode (".", $file);
	echo $filename [0];
}
?>