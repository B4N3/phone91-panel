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
                <?php if($_SESSION['client_type'] == 2)
		{
			?>
		<li><a class="reseller-setting slideAndBack" href="#!setting.php|reseller-setting.php" >Reseller Settings</a></li>
                <?php

		}?>
		<li><a class="clicktocall_Setting slideAndBack" href="#!setting.php|clicktocall_Setting.php" >Click to call</a></li>
		<li><a class="common_setting slideAndBack" href="#!setting.php|commonSetting.php" >Common Setting</a></li>
		<?php if($_SESSION['client_type'] == 2)
		{
			?>
			<li><a class="payment_details slideAndBack" href="#!setting.php|payment-setting.php" >Payment Details</a></li>
		

			<?php


		}?>


		<li><div class="dline mrT mrB"></div></li>
		<li><a class="panel-pricing themeLink slideAndBack" href="#!setting.php|panel-pricing.php">Pricing</a></li>

		<?php if($_SESSION['client_type'] != 4)
		{
			?>
		<li><a class="buymore themeLink slideAndBack" href="#!setting.php|buymore.php" >Buy Now</a></li>

			<?php

		}
		?>
                <?php if($_SESSION['client_type'] == 3 && $_SESSION['resellerId'] == 2) { ?>
                <li><a class=" slideAndBack" href="#!setting.php|fund-transfer.php" >Fund transfer</a></li>
                <?php } ?>
	</ul>
			
</div>
      
<div id="rightsec" class="slideRight settingRight"> 
</div>
    
<script type="text/javascript">
dynamicPageName('Settings');
slideAndBack('.slideLeft','.slideRight');

</script>