<?php
//Include Common Configuration File First
include_once('config.php');
//Validate User Login by funtion
//Validate Reseller


$userSetting=$funobj->getResellerSetting($_SESSION['userid']);

if($userSetting['mobile']==1){
   $mobileChecked="checked=checked"; 
}else
    $mobileChecked = '';
if($userSetting['email']==1){
   $emailChecked="checked=checked"; 
}else
    $emailChecked='';
?>
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>

<div class="setContainer settRightSec">
	<!--<h2 class="headSetting">Reseller Settings</h2>-->
   <div class="whiteWrapp">
	<p class="mrB1 semi">Do you want to verify your client's Email account at the time of Sign-up?</p>
	<div class="clear resboxrow">
		<input class="changeResellerSettings" name="email" id="email" type="checkbox" <?php echo $emailChecked;?> title="Email"/> 
		<label for="email" title="Email">Email</label>
	</div>
<!--	<div class="clear resboxrow">
		<input name="" type="radio" value="" />
		<label for="" >Yes</label>
		<input name="" type="radio" value="" />
		<label for="" >No</label>
	</div>-->
	<div class="clear resboxrow">
		<input class="changeResellerSettings" name="mobile" id="mobile" type="checkbox" <?php echo $mobileChecked;?> title="Mobiles" /> 
		<label for="mobile" title="Mobiles">Mobiles</label>
	</div>
	</div>
</div>

<!--//Reseller  Wrapper-->
<script type="text/javascript">
dynamicPageName('Reseller Settings')
slideAndBack('.slideLeft','.slideRight');
    $(document).ready(function(){
    $(".changeResellerSettings").click(function(){
                var keyValue=0;
                if($(this).is(':checked'))
                    keyValue=1
		$.ajax({
		url:"action_layer.php?action=changeResellerSettings",
		type:"POST",
                dataType:'json',
		data:"key="+this.id+"&value="+keyValue,
//                $("#"+this.id).val(),
                success: function(msg)
                    {
                             $("#loading_img").hide();
                             show_message(msg.msg,msg.msg_type);
                    }
		})
            })
    });
</script>
