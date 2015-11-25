<?php
session_start();
	$buisness = 'arpit__1245162853_biz@yahoo.com';
	$return = 'http://chapter7.in/testSite/testsite3/index.php';
	$cancel_return = 'http://chapter7.in/testSite/testsite3/index.php';
include("includes/functions.php");
 $userid = $_SESSION['userid'];
 $cid = getUserInfo('clientsshared','id_currency',$userid);
if($cid==1)
{
	$recharge1=10;
	$talktime1=9;
	$recharge2=20;
	$talktime2=20;
	$recharge3=50;
	$talktime3=55;
	$currency = "USD";
}
else if($cid==2)
{
	$recharge1=250;
	$talktime1=220;
	$recharge2=500;
	$talktime2=500;
	$recharge3=1000;
	$talktime3=1100;
	$currency = "INR";	
}
else if($cid==3)
{
	$recharge1=50;
	$talktime1=48;
	$recharge2=100;
	$talktime2=100;
	$recharge3=200;
	$talktime3=220;
	$currency = "AED";	
}


?>
<script language="javascript">

function GetPaypal() {

document.form10.action = "http://chapter7.in/testSite/testsite3/serverProcess/ReviewOrder.php";
document.form10.submit();
}


function settalktime(tt,r)
{
	
	document.form10.talktime.value=tt;
	document.form10.recharge.value=r;
}

</script>
<form name="form10" method="post" action="">
<div class="fieldwrapper">
	<div class="thefield">
<label class="sitecontent">
            <input type="radio" name="Payment" value="<?=$recharge1?>" id="Payment" onClick="settalktime('<?php echo $talktime1; ?>','<?php echo $recharge1; ?>');" checked/>
            Recharge <?=$recharge1?> <?=$currency?> - Talktime <?=$talktime1?> <?=$currency?></label>	  </div>
</div>


<div class="fieldwrapper">
	<div class="thefield">
		<label class="sitecontent">
            <input type="radio" name="Payment" value="<?=$recharge2?>" id="Payment" onClick="settalktime('<?php echo $talktime2; ?>','<?php echo $recharge2; ?>');"/>
            Recharge <?=$recharge2?> <?=$currency?> - Talktime <?=$talktime2?> <?=$currency?></label>
	</div>
</div>

<div class="fieldwrapper">
	<div class="thefield">
		<span class="sitecontent">
            <input type="radio" name="Payment" value="<?=$recharge3?>" id="Payment" onClick="settalktime('<?php echo $talktime3; ?>','<?php echo $recharge3; ?>');"/>
            Recharge <?=$recharge3?> <?=$currency?> - Talktime <?=$talktime3?> <?=$currency?></span>
	</div>
</div>
        
<div class="fieldwrapper">
	<label for="senderid" class="styled"></label>
	<div class="thefield">
      <input type="hidden" name="currency" value="<?=$cid?>">
      <input type="hidden" name="currency_name" value="<?=$currency?>">
      <input type="hidden" name="talktime" value="<?=$talktime1?>">
      <input type="hidden" name="recharge" value="<?=$recharge1?>">
      <input type="hidden" name="custom" value="<?php echo $_SESSION['userid'] ; ?>">
	  <input type="hidden" name="business" value="<?php echo $buisness ; ?>">
	  <input type="hidden" name="return" value="<?php echo $return ; ?>">
	  <input type="hidden" name="cancel_return" value="<?php echo $cancel_return ; ?>">

		<input class="btn2" type="button" id="sender" maxlength="" value="Next >" onclick="GetPaypal();"/>
	</div>
</div>
</form>
