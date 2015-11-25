<?php
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 08-aug-2013
 * @package Phone91
 * @details reseller manage pin page 
 */
//Include Common Configuration File First
include_once('config.php');

if(!$funobj->check_reseller()){
     $funobj->redirect("index.php");
}

#include pin_class.php file 
include_once("classes/pin_class.php");

#create object of pin_class
$pin_obj = new pin_class();

#call getmypin function for all batch detail 
$pinJson=$pin_obj->getMyPin($_SESSION['userid']);

?>
<div class="commHeader">
	<div class="showAtFront">            
		<a title="Add PINns" class="btn btn-medium btn-primary alC iconBtn fl mrR1" 
		href="#!reseller-manage-pins.php|reseller-add-pins.php">
				<span class="ic-24 addW"></span>
				<span class="iconBtnLbl">Add PINs</span>
		</a>
         <div class="fl" id="srchrow">
		 	<input type="text" name="searchBatch" id="searchBatch" placeholder="search" class="searchPins"/>
             <!--<label>
                <p class="fl">Showing <span>1000</span> results by <span>latest</span> whose balance is less than</p>
                <p class="fl showInfo"> 
                        <span class="ic-8 close"></span>
                        <span class="fl">1000</span>
                        <span class="ic-8 arrow"></span>
                        </p><p title="Close" class="rowClose fl cp"><span class="ic-16 close"></span></p>
                <p></p>
            </label>-->
        </div>
	</div>
	<div class="showAtBack"><a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a></div>	
        
     
        
        
        <div id="leftsec" class="slideLeft commLeftSec">
			<div class="innerSection">			
				<ul class="ln cmnli-mngpin pinlist commLeftList">
					
					  <?php $pinData = json_decode($pinJson, true);   
					  foreach($pinData['myPins'] as $pinDetails){
					   if($pinDetails['action'] == 0){ $changepinAct = "stopR"; $PinActvalue = "stop"; }else{ $changepinAct=''; $PinActvalue = "play";}
					  if($pinDetails['amountStatus'] == 0){ $amountStatus = "unpaid"; $statusValue = "unpaid";}else{ $amountStatus = ''; $statusValue = "paid";}
					   ?>
					
					<li class="" onclick="showPinDetail(this,event);" batchid="<?php echo $pinDetails['id'];?>" >
						<div class="mpinrow clear">
							<p>
								<i class="ic-24 und"></i>
								<span class="ellp"><?php echo htmlentities($pinData["userName"]);?></span>
							</p>
							
							<p>
								<span>
									<label class="ic-24 playR cp <?php echo $changepinAct; ?>" for="changeAct" onclick="changePlayStop($(this));" batchId="<?php echo $pinDetails['id'];?>"></label>
									<input type="checkbox" checked="checked" style="display:none" id="changeAct<?php echo $pinDetails['id'];?>" name="changeAct" value ="<?php echo $PinActvalue; ?>">
								</span>
								<span class="biller">
									<label class="ic-bl paid cp <?php echo $amountStatus; ?>" for="changebillType" onclick="changebillType($(this));" batchId="<?php echo $pinDetails['id'];?>" ></label>
									<input type="checkbox" checked="checked" style="display:none" id="changebillType<?php echo $pinDetails['id'];?>" name="changebillType" value ="<?php echo $statusValue; ?>">
								</span>
							</p>
						</div>
						<h3 class="ellp"><?php echo htmlentities($pinDetails["batch_name"]);?></h3>
						<p class="dt">Tariff :  <?php echo $pinDetails["tariff"];?></p>
						<h3 class="mrT1"><?php echo $pinDetails["used_pin"];?>/<?php echo $pinDetails["total_pin"];?> <span>pins</span> | <?php echo $pinDetails["amountPerPin"];?> <span><?php echo $pinDetails["currency"];?>/pin</span></h3>
						<p class="exp">Expire on : <?php echo $pinDetails["expire_date"];?> </p>
					</li>
					
					  <?php }?>
					 <?php
	//					for($i=0; $i<=2; $i++){
	//						echo'
	//						<li class="" onclick="window.location.href=\'#!reseller-manage-pins.php|manage-pins.php\'">
	//                        <div class="mpinrow clear">
	//                        	<p>
	//                            	<i class="ic-24 und"></i>
	//                                <span class="ellp">Shubhendra Agrawal</span>
	//                            </p>
	//                            
	//                            <p>
	//                                <span>
	//                                    <label class="ic-24 playR cp" for="changeAct'.$i.'" onclick="$(this).toggleClass(\'stopR\')"></label>
	//                                    <input type="checkbox" checked="checked" style="display:none" id="changeAct'.$i.'">
	//                                </span>
	//                            	<span class="biller">
	//                                    <label class="ic-bl paid cp" for="changebillType'.$i.'" onclick="$(this).toggleClass(\'unpaid\')"></label>
	//                                    <input type="checkbox" checked="checked" style="display:none" id="changebillType'.$i.'">
	//                                </span>
	//                            </p>
	//                        </div>
	//                        <h3 class="ellp">vehicul</h3>
	//                        <p class="dt">Tariff consector</p>
	//                        <h3 class="mrT1">250/1000 <span>pins</span> | 0.23 <span>USD/pin</span></h3>
	//                        <p class="exp">Expire on 2016-10-31 00:00:00 | Postpaid</p>
	//                    </li>
	//						
	//						
	//						';
	//					}
					?>
				</ul>
			</div>
        </div>
        
        <div id="rightsec" class="slideRight mangePinsWrap commRightSec">
        </div>
        
<script>
//$('#leftsec,#rightsec').autoHeight({removeExtra:163});


//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 26-07-2013
//function use for show all pin detail by manage-pins.php file 
function showPinDetail(ths,e){
	$(ths).siblings().removeClass('selected');
        $(ths).addClass('selected');
        //get batchid
        var batchid = $(ths).attr('batchid');
    
    //if previous event class is not "action" then batchid send to manage pins.php file for show batch detail   
    if(!$(e.target).hasClass('action')){
    $.ajax({
	    url : "manage-pins.php",
	    type: "POST",
	    data: {batchid:batchid} ,
	   success: function(text) {
               //console.log(text);
		  $("#rightsec").html(text);
		}
	})
    }
 }
 
$(function() {
$('#searchBatch').keyup(function() {
    var data = $(this).val();
    $.ajax({
	   url : "action_layer.php?action=searchBatch",
	   type: "POST",
           dataType:"json",
	   data: {data:data} ,
	   success: function(text) {
           var str = batchlistDesign(text.batch,text.userName); 
           $('#leftsec ul').html('');
           $('#leftsec ul').html(str);    
  
		}
	})
    
    
    
})


})

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 12/08/2013
//function use for create design of batch pin list 
function batchlistDesign(text,userName){
var str ='';
$.each( text, function(key, item ) {
   if(item.action == 0){ var changepinAct = "stopR"; var PinActvalue = "stop"; }else{ var changepinAct=''; var PinActvalue = "play";}
   if(item.amountStatus == 0){ var amountStatus = "unpaid"; var statusValue = "unpaid";}else{ var amountStatus = ''; var statusValue = "paid";}
                          

     
        str += '<li class="" onclick="showPinDetail(this,event);" batchid="'+item.id+'" >\
                <div class="mpinrow clear">\
                <p>\
                	<i class="ic-24 und"></i>\
                        <span class="ellp">'+userName+'</span>\
                </p>\
                <p>\
                        <span>\
                        <label class="ic-24 playR cp '+changepinAct+'" for="changeAct" onclick="changePlayStop($(this));" batchId="'+item.id+'"></label>\
                        <input type="checkbox" checked="checked" style="display:none" id="changeAct'+item.id+'" name="changeAct" value ="'+PinActvalue+'">\
                        </span>\
                        <span class="biller">\
                        <label class="ic-bl paid cp '+amountStatus+'" for="changebillType" onclick="changebillType($(this));" batchId="'+item.id+'" ></label>\
                        <input type="checkbox" checked="checked" style="display:none" id="changebillType'+item.id+'" name="changebillType" value ="'+statusValue+'">\
                        </span>\
                </p>\
                </div>\
                <h3 class="ellp">'+item.batch_name+'</h3>\
                <p class="dt">Tariff :  '+item.tariff+'</p>\
                <h3 class="mrT1">'+item.used_pin+'/'+item.total_pin+'<span>pins</span> | '+item.amountPerPin+'<span>'+item.currency+'/pin</span></h3>\
                <p class="exp">Expire on : '+item.expire_date+'</p>\
                </li>';

     });
return str;

}

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 12/08/2013
//function use for create design of batch pin list 
function amountStatusPaid(ths,amountStatus){
 var batchId = $(ths).attr('batchId');
 //action use for change batch amount status paid / unpaid  
 
 $.ajax({
	   url : "action_layer.php?action=pinBatchAmountStatus",
	   type: "POST", dataType:"json",
	   data: {batchId:batchId,amountStatus:amountStatus} ,
	   success: function(text) {
               show_message(text.msg,text.status);
		}
	})


}

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 10-09-2013
//function use for change bill type (paid and unpaid).
function changebillType(ths){
var batchId = $(ths).attr('batchId');
 $(ths).toggleClass('unpaid');
 if($('#changebillType'+batchId).val() == "paid"){
     $('#changebillType'+batchId).val("unpaid");
     var amountStatus = 0;
     
 }else{
     $('#changebillType'+batchId).val("paid");
     var amountStatus = 1;
 }
 
 
 //action use for change batch amount status paid / unpaid  
 
 $.ajax({
	   url : "action_layer.php?action=pinBatchAmountStatus",
	   type: "POST", dataType:"json",
	   data: {batchId:batchId,amountStatus:amountStatus} ,
	   success: function(text) {
               show_message(text.msg,text.status);
		}
	})

 
 

}

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 10/09/2013
//function use for change icon play and stop 
function changePlayStop(ths){
var batchId = $(ths).attr('batchId');
 $(ths).toggleClass('stopR');
 if($('#changeAct'+batchId).val() == "play"){
     $('#changeAct'+batchId).val("stop");
     var batchAction = 0;
     
 }else{
     $('#changeAct'+batchId).val("play");
     var batchAction = 1;
 }
 
 
 
 //action use for enable pin batch 
 
 $.ajax({
	   url : "action_layer.php?action=changeBatchAction",
	   type: "POST", dataType:"json",
	   data: {batchId:batchId,batchAction:batchAction} ,
	   success: function(text) {
               show_message(text.msg,text.status);
		}
	})
 }
</script>

<script type="text/javascript">
$(document).ready(function()
{
			$('.slideLeft ul li, .reserrlerBtn').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "20px"}, "slow");
						$('.slideLeft').fadeOut('fast');
					}
			});

			$('.back').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "-1000px"}, "slow");
						$('.slideLeft').fadeIn(2000);
				}
			});
	});
</script>
