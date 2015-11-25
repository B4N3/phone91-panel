<?php
//Include Common Configuration File First
include_once('config.php');
//Validate User Login by funtion
//Validate Login with the help of this function 
$funObj = new fun();

if(!$funObj->login_validate()){
        $funObj->redirect("index.php");
}


 if(isset($_SESSION['client_type']) && $_SESSION['client_type'] == 4)
 {
 		$funobj->redirect("/userhome.php#!setting.php|phone.php"); 
 }


//get user tariff
 $userDetailArr = $funobj->getUserBalanceInfo($_SESSION['userid']);

if(empty($userDetailArr))
{
	echo 'your session has been expied Please login again!!!';
	exit();
}
//Set User Tariff
$id_tariff = $userDetailArr['tariffId'];

//get currency id
$cid = $funObj->getOutputCurrency($id_tariff);

//get currency name
$currency = $funObj->getCurrencyViaApc($cid,1);      

$hideClass = 0;
//Remove this and call an fnction for this to grep default amount of payment.
if (isset($id_tariff)) //if terriff id set then set recharge and talktime
{
     
    $result = $funObj->getRechargeTalktime($id_tariff);
  
    if($result->num_rows == 0)
        $hideClass = 1;
    
   
    
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
    	
    	
    	$recharge[] = $row['recharge'];
    	$talktime[] = $row['talktime'];

    	unset($row);
        
    }
} 

//get domain reseller id
$domainResId = $funObj->getDomainResellerId($_SERVER['HTTP_HOST']);


if(!$domainResId)
	$hideClass = 0;

//create commonFuncition class object
$commObj = new commonFunction();

$PGdetail = $commObj->getPGDetails($domainResId);

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
                    <?php if($hideClass != 1){?>
			<li><a href="#tabs-1">Buy</a></li>
                    <?php } ?>
			<li><a href="#tabs-2">Recharge by PIN</a></li>
		</ul>    
		
            <div id="tabs-1" class="tabs <?php echo ($hideClass == 1)?'dn':'';?>">
			<form name="myform" id="myform" method="post" action="">
			<div id="chooseRechargeAmount">
				<p class="f12 mrB">Choose recharge amount</p>
				<ul class="pyopt ln clear" id="payprice">
					<?php 
					$active = 'active';
					$correct = 'correct';
					foreach ($recharge as $key => $value) {
						# code...
					?>
					<li class="<?php echo $active;?>" onclick="settalktime(this);" >
						<span class="amtSpan ic-16 <?php echo $correct; ?>"></span>
						<input type="hidden" class="hid" talktime="<?php echo $talktime[$key]; ?>" recharge="<?php echo $value; ?>">
						<p class="small" smallAmt="<?php echo $talktime[$key]; ?>" ><?php echo $talktime[$key]; ?> <?php echo $currency; ?></p>
						<!--<input type="radio" name="amount" />-->
					</li>
					<?php
					$active = '';
					$correct = '';
					 } ?>
					
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
						<div id="payAmount" amount="<?php echo $talktime[0];?>" currency="<?php echo $currency ?>" class="fl">Pay <?php echo $talktime[0].' '.$currency ?></div>						
						
					</div>
					<div id="buybox">
						<p class="mrT3 mrB f12 semi">Select payment mode</p>
						<ul class="pyopt ln clear" id="payby">
							<?php 
							$dnClass = '';
							if(isset($domainResId) && $domainResId == 2 )
							{?>
                                                    <li  id="creditDebit" class="active nonstripe" onclick="paymentButton(this)" >Credit/Debit Card<span class="paySpan ic-16 correct"></span></li>
							<?php }

							if(isset($PGdetail['status']) && $PGdetail['status'] == 1)
							{ ?>
							<li  id="paypal"  class='nonstripe' onclick="paymentButton(this)">Paypal<span class="paySpan ic-16"></span></li>
							<?php }
							else
							{
								$dnClass = 'dn';
								echo 'Contact your provider to make Recharge!!!';
							}
								 
							if(isset($domainResId) && $domainResId == 2 )
							{

							?>
                                                        <li  id="cashu" class="nonstripe" onclick="paymentButton(this)">Cash U<span class="paySpan ic-16"></span></li>
                                                        <?php if($_SESSION['username'] == 'sudhirwowsudhir') {?>
                                                        <li  id="strip" class="stripe" onclick="paymentButton(this)">Stripe<span class="paySpan ic-16"></span></li>
                                                        <?php } } 

							?>
						</ul>

						<div id="normalPay" class="<?php echo $dnClass;?>"><a class="btn btn-medium btn-primary" href="javascript:GetAll();" title="Pay">Pay</a></div>
                                                <?php if($_SESSION['username'] == 'sudhirwowsudhir') { ?> 
                                                <div class="stripbtn" id="stripePay" >
                                                    
                                                    <a id="customButton" class="btn btn-medium btn-primary" href='javascript:;' onclick="saveOrder();">Purchase</a>
                                                    <input type="hidden" id="orderId" name="orderId"/>
                                                </div>
                                                <?php } ?>
						<?php if($_SESSION['domain'] == 'phone91.com'){ ?>
                                <a class="btn mrT2 btn-medium btn-primary" href="/share.php" target="_blank" title="share">share</a>
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
						<span class="noteinfo">If you are a Reseller and if you want to purchase PINs in bulk, please contact <?php if($_SESSION['resellerId'] == '2' || $_SESSION['id'] == '2') {?><a href="mailto:business@phone91.com">business@phone91.com</a><?php }else echo 'your service provider';?></span> 
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
    <?php if($_SESSION['domain'] == 'phone91.com'){ ?>
	<div id="buyMoreNote">
		<div class="buyMoreNoteClose"><span class="ic-24 close" onclick="$('#buyMoreNote').hide();"></span></div>
		<div class="mascot"></div>
		<div class="buyMoreNoteCnt">Hey, once you are done recharging your account, <a href="">share it on Facebook</a> and we will add 10% extra of your recharge amount in your account.</div>				
		<div class="cl"></div>
	</div>
    <?php } ?>	
</div>

<!--//Buy More Wrapper-->
<script src="/js/checkout.js"></script>

<script type="text/javascript">
   
$( "#tabs" ).tabs();
$('#stripePay').hide();
function paymentButton(ths)
{
    if($(ths).hasClass('nonstripe'))
    {
        $('#normalPay').show();
        $('#stripePay').hide();
    }
    else
    {
        $('#normalPay').hide();
        $('#stripePay').show();
    }
}

var handler = StripeCheckout.configure({
    key: 'pk_live_rzmIEcHzKbPrdBvLv9VFUWWb',
    currency:'INR',
    token: function(token) {
        console.log(token);
        var orderId = $('#orderId').val();
        $.ajax({
                 url: "/controller/paymentController.php?action=paymentResponse",
                 type:'POST',
                 data: {token:token,orderId:orderId},
                 dataType:'json',
                 success:function(text){
                     if(text.status == 'success'){
                         $('#balance').html(text.updateBalance);
                     }
                     show_message(text.msg,text.status);
                     console.log("success");
                 }
  });
      // Use the token to create the charge with a server-side script.
      // You can access the token ID with `token.id`
    }
  });
  
  
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
        $('#payAmount').attr('amount',$('.small',this).attr('smallAmt'));
})

$('#payby li').click(function(){
	$('#payby li').removeClass('active');
	$(this).addClass('active');
	$('#payby li .paySpan').removeClass('ic-16 correct');
	$(this).children(".paySpan").addClass('ic-16 correct');
    $("#paymentBy").val($(this).prop("id"));
})
function settalktime(ths)
{
    
    console.log($(ths).find('input').attr('talktime'));
  }
function GetAll()
{
        console.log(document.myform);
		$("#myform").attr("action","getOrder.php");
        $("#myform").attr("target","_blank");
        //set talktime and recharge
        console.log(document.myform.talktime.value=$('.correct.amtSpan').next().attr('talktime'));
        console.log(document.myform.recharge.value=$('.correct.amtSpan').next().attr('recharge'));
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




 

  function saveOrder(){
    // Open Checkout with further options
    var talktime = $('#payAmount').attr('amount');
   var currency = $('#payAmount').attr('currency');
    
    $.ajax({
                 url: "/controller/paymentController.php?action=saveOrderDetail",
                 type:'POST',
                 data: {'talktime':talktime,
                        //'amount':amount
                        },
                 dataType:'json',
                 success:function(text){
                     
                     if(text.status == "success"){
                         $('#orderId').val(text.orderId);
                     handler.open({
                     name: 'phone91 recharge',
                     description: 'Phone91 ('+talktime+' '+currency+')',
                     amount: 20*100,
                     currency:currency
                    });
                }
                 }
    
        });   
    //e.preventDefault();
  }
</script>
