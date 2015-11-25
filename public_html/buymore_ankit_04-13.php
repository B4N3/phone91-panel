<?php
//Include Common Configuration File First
include_once('config_ankit.php');
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
<div id="bmcnt" class="pd2">
	<!--Inner Container-->
	<div class="setContainer">
    	<div class="buyMoreWrap">
               <!--Left BuyMore side-->
                <div class="leftBuyMore">
                    <form name="myform" id="myform" method="post" action="">
                            <div id="buybox">
                                <h2 class="headSetting">Buy</h2>
                                <p class="mrT2 mrB f12 semi">Talktime</p>
                                <ul class="pyopt ln clear" id="payprice">
                                    <li onclick="settalktime();" >
                                        <span class="amtSpan ic-16 correct"></span>
                                        <input type="hidden" class="hid" talktime="<?php echo $talktime1?>" recharge="<?php echo $recharge1 ?>">
                                        <p class="small"> <?php echo $talktime1; ?> <?php echo $currency; ?></p>
                                        <!--<input type="radio" name="amount" />-->
                                    </li>
                                    <li onclick="settalktime();">
                                        <span class="amtSpan ic-16 "></span>
                                        <input type="hidden" class="hid" talktime="<?php echo $talktime2; ?>" recharge="<?php echo $recharge2 ?>">
                                        <p class="small"> <?php echo $talktime2; ?> <?php echo $currency; ?></p>
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
                                    <input type="hidden" name="paymentBy" id="paymentBy" value='paypal' >
                                    <input type="hidden" name="talktime" >
                                    <input type="hidden" name="recharge" >
                                    <input type="hidden" name="id_client" value="<?php echo $_SESSION['userid']; ?>">
                                    <input type="hidden" name="domain" value="<?php echo $_SERVER['HTTP_HOST']; ?>">
                                </ul>
                                <p class="mrT3 mrB f12 semi">Pay by</p>
                                <ul class="pyopt ln clear" id="payby">
                                    <li  id="paypal" >Paypal<span class="paySpan ic-16 correct"></span></li>
                                    <li  id="cashu">Cash U<span class="paySpan ic-16 "></span></li>
                                    <li  id="googleCheckout">Google Checkout<span class="paySpan ic-16 "></span></li>
                                </ul>
                                <a class="btn mrT2 btn-medium btn-primary" href="javascript:GetAll();" title="Pay">Pay</a>
                            </div>
                            <div id="oropt" class="pr"><span class="">or</span></div>
                   </form>
               </div>
               <!--Left BuyMore side-->
               <!--Right BuyMore side-->
               <div class="rightBuyMore  fl"> 
               <p class="smallDevice">or</p>
                    <div id="buybox">
                        <h2 class="headSetting">Recharge by Pin</h2>
                        <p class="mrT2 mrB f12 semi">Enter Pin</p>
                        <div id="pinbox" class="clear">
                            <input type="text" name="pin" id="pin" />
                            <input class="btn  btn-medium btn-primary" type="button" name="recharge" id="recharge" value="Recharge" onclick="rechargeByPin();" title="Recharge"/>
                        </div>
                    </div>
                </div>
                 <!--//Right BuyMore side-->
          </div>
	</div>
	<!--//Inner Container-->
</div>
<!--//Buy More Wrapper-->
<script type="text/javascript">
$('#payprice li').click(function(){
	$('#payprice li .amtSpan').removeClass('ic-16 correct');
	$(this).children(".amtSpan").addClass('ic-16 correct');
})
$('#payby li').click(function(){
	$('#payby li .paySpan').removeClass('ic-16 correct');
	$(this).children(".paySpan").addClass('ic-16 correct');
        $("#paymentBy").val($(this).prop("id"));
})
function settalktime()
{
  }
function GetAll()
{
        $("#myform").attr("action","getOrder_ankit.php");
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
<script type="text/javascript">
$(document).ready(function()
{
			$('.back').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "-757px"}, "slow");
						$('.slideLeft').fadeIn(2000);
				}
			});
	});
</script>