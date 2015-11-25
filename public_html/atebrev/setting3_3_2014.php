<?php
//Include Common Configuration File First
include_once('config.php');
if (!$funobj->login_validate() ) {
    $funobj->redirect("index.php");
}
?>

<div id="leftsec" class="slideLeft settingMenu">
	<h3 class="pdL3 pdT3">Settings</h3>
	<ul class="ln cntnav paTRBL" id="setmenu">
		<li><a class="phone slideAndBack" href="#!setting.php|phone.php">Manage Number(s)</a></li>
		<li><a class="email slideAndBack" href="#!setting.php|email.php" >Manage E-mail Accounts</a></li>
		<li><a class="register-ids slideAndBack" href="#!setting.php|register-ids.php">Add IM Accounts</a></li>
		<li><a class="personal slideAndBack" href="#!setting.php|personal.php" >My Profile</a></li>
		<li><a class="change-password slideAndBack" href="#!setting.php|change-password.php" >Change Password</a></li>
		<!--<li><a class="news-updates slideAndBack" href="#!setting.php|news-updates.php" >Get Updates</a></li>-->
		<li><a class="reseller-setting slideAndBack" href="#!setting.php|reseller-setting.php" >Reseller Settings</a></li>
		<li><div class="dline mrT mrB"></div></li>
		<li><a class="panel-pricing themeLink slideAndBack" href="#!setting.php|panel-pricing.php">Pricing</a></li>
		<li><a class="buymore themeLink slideAndBack" href="#!setting.php|buymore.php" >Buy Now</a></li>
	</ul>
			
</div>
      
<div id="rightsec" class="slideRight settingRight"> 
</div>
    
<script type="text/javascript">
dynamicPageName('Settings');
slideAndBack('.slideLeft','.slideRight');

</script>