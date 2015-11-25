<?php
//Include Common Configuration File First
include_once('config.php');
//Validate User Login by funtion
//Validate Login with the help of this function 
$funObj = new fun();

if(!$funObj->login_validate()){
        $funObj->redirect("index.php");
}

//Set User Tariff
$id_tariff = $_SESSION['id_tariff'];

//get currency id
$cid = $funObj->getOutputCurrency($id_tariff);

//get currency name
$currency = $funObj->getCurrencyViaApc($cid,1);        

//Remove this and call an fnction for this to grep default amount of payment.
if (isset($id_tariff)) //if terriff id set then set recharge and talktime
{
     
    $result = $funObj->getRechargeTalktime($id_tariff);
  
    $row1=  $result->fetch_array();
    $recharge1 = $row1[0];    
    $talktime1 = $row1[1];
    
    $row2=  $result->fetch_array();   
    $recharge2 = $row2[0];
    $talktime2 = $row2[1];
    
    $row3=  $result->fetch_array();   
    $recharge3 = $row3[0];
    $talktime3 = $row3[1];
} 

//free the obj space
$funObj = null;
unset($funObj);
//https://support.hostnsoft.com/payment/checkout/googlecheckout.php
?>
<!--Buy More Wrapper-->
 <a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>

<div class="setContainer buyNowContainer">
	<div class="buyMoreWrap" style="padding-top:20px;">
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Buy</a></li>
			<li><a href="#tabs-2">Recharge by PIN</a></li>
		</ul>    
		
		<div id="tabs-1" class="tabs">
			<form name="myform" id="myform" method="post" action="">
			<div id="chooseRechargeAmount">
				<p class="f12 mrB">Choose recharge amount</p>
				<ul class="pyopt ln clear" id="payprice">
					<li class="active" onclick="settalktime();" >
						<span class="amtSpan ic-16 correct"></span>
						<input type="hidden" class="hid" talktime="<?php echo $talktime1?>" recharge="<?php echo $recharge1 ?>">
						<p class="small"><?php echo $talktime1; ?> <?php echo $currency; ?></p>
						<!--<input type="radio" name="amount" />-->
					</li>
					<li onclick="settalktime();">
						<span class="amtSpan ic-16 "></span>
						<input type="hidden" class="hid" talktime="<?php echo $talktime2; ?>" recharge="<?php echo $recharge2 ?>">
						<p class="small"><?php echo $talktime2; ?> <?php echo $currency; ?></p>
						<!--<input type="radio" name="amount" />-->
					</li>
					<li onclick="settalktime();">
						<span class="amtSpan ic-16 "></span>
						<input type="hidden" class="hid" talktime="<?php echo $talktime3; ?>" recharge="<?php echo $recharge3; ?>">
						<p class="small"><?php echo $talktime3; ?> <?php echo $currency; ?></p>
						<!--<input type="radio" name="amount" />-->
					</li>
					<input type="hidden" name="currency" value="<?php echo $cid; ?>">
					<input type="hidden" name="currency_name" value="<?php echo $currency; ?>">
					<input type="hidden" name="paymentBy" id="paymentBy" value='creditDebit' >
					<input type="hidden" name="talktime" >
					<input type="hidden" name="recharge" >
					<input type="hidden" name="id_client" value="<?php echo $_SESSION['userid']; ?>">
					<input type="hidden" name="domain" value="<?php echo $_SERVER['HTTP_HOST']; ?>">
				</ul>
				<a class="btn btn-medium btn-primary" href="javascript:proceedToPayBy();" >Proceed</a>
			</div>
			<div id="payBy" style="display:none;">
				
					<div id="payAmountBox">						
						<a class="btn btn-medium btn-primary fr" href="javascript:backToAmount();" >Change</a>
						<div id="payAmount" class="fl">Pay <?php echo $talktime1.' '.$currency ?></div>						
						
					</div>
					<div id="buybox">
						<p class="mrT3 mrB f12 semi">Select payment mode</p>
						<ul class="pyopt ln clear" id="payby">
							<li  id="creditDebit" class="active" >Credit/Debit Card<span class="paySpan ic-16 correct"></span></li>
							<li  id="paypal" >Paypal<span class="paySpan ic-16"></span></li>
							<li  id="cashu">Cash U<span class="paySpan ic-16 "></span></li>
						</ul>
						<a class="btn btn-medium btn-primary" href="javascript:GetAll();" title="Pay">Pay</a>
						<?php if($_SESSION['id'] == 2){ ?>
						<!--<a class="btn mrT2 btn-medium btn-primary" href="http://voip91.com/share.php" target="_blank" title="share">share</a>-->
					<?php } ?>
					</div>						
			   
			</div>
			</form>
		</div>
		<div id="tabs-2" class="tabs">
			<div id="buybox">
					<p class="f12 mrB">Enter 10 digit PIN number</p>
					<div id="pinbox" class="clear">
						<input type="text" name="pin" id="pin" />
						<input class="btn  btn-medium btn-primary" type="button" name="recharge" id="recharge" value="Recharge" onclick="rechargeByPin();" title="Recharge"/>
					</div>
					<div class="notewrap mrT2 clear"> 
						<span class="ic-32 notif"></span> 
						<span class="noteinfo">If you are a Reseller and if you want to purchase PINs in bulk, please contact <a href="mailto:business@phone91.com">business@phone91.com</a></span> 
			   		</div>
				</div>
		</div>
	</div>
	
		   
			<!--<div class="leftBuyMore">
				
		   </div>
		   
		   <div class="rightBuyMore  fl"> 
		   <p class="smallDevice">or</p>
				
			</div>-->
			
	</div>
	<div id="buyMoreNote">
		<div class="buyMoreNoteClose"><span class="ic-24 close" onclick="$('#buyMoreNote').hide();"></span></div>
		<div class="mascot"></div>
		<div class="buyMoreNoteCnt">Hey, once you are done recharging your account, <a href="">share it on Facebook</a> and we will add 10% extra of your recharge amount in your account.</div>				
		<div class="cl"></div>
	</div>
	
</div>

<!--//Buy More Wrapper-->
<script type="text/javascript">
$( "#tabs" ).tabs();
dynamicPageName('Buy Credits')
slideAndBack('.slideLeft','.slideRight');

function proceedToPayBy(){
	$('#chooseRechargeAmount').hide();
	$('#payBy').show();
}
function backToAmount(){
	$('#chooseRechargeAmount').show();
	$('#payBy').hide();
}

$('#payprice li').click(function(){
	$('#payprice li').removeClass('active');
	$(this).addClass('active');
	$('#payprice li .amtSpan').removeClass('ic-16 correct');
	$('.amtSpan',this).addClass('ic-16 correct');
	$('#payAmount').html('Pay '+$('.small',this).html());
})

$('#payby li').click(function(){
	$('#payby li').removeClass('active');
	$(this).addClass('active');
	$('#payby li .paySpan').removeClass('ic-16 correct');
	$(this).children(".paySpan").addClass('ic-16 correct');
    $("#paymentBy").val($(this).prop("id"));
})
function settalktime()
{
  }
function GetAll()
{
        console.log(document.myform);
		$("#myform").attr("action","getOrder.php");
        $("#myform").attr("target","_blank");
        //set talktime and recharge
        document.myform.talktime.value=$('.correct.amtSpan').next().attr('talktime');
        document.myform.recharge.value=$('.correct.amtSpan').next().attr('recharge');

        $("#myform").submit();
} 
//created by sudhir pandey 
//creation date 02-09-2013
function rechargeByPin(){
    var pin = $('#pin').val(); 
    $.ajax({
         url : "action_layer.php?action=rechargeByPin",
         type: "POST", 
         data:{pin:pin},
         dataType: "json",
         success:function (text)
        {
          show_message(text.msg,text.status);
        }
    });
}
</script>
