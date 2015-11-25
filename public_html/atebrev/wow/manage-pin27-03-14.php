<?php
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 08-aug-2013
 * @package Phone91
 * @details reseller manage pin page 
 */
//Include Common Configuration File First
include dirname(dirname(__FILE__)) . '/config.php';

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}

//get page number
if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;

#include pin_class.php file 
include_once CLASS_DIR.'pin_class.php';
$userId = $_SESSION['userid'];

#create object of pin_class
$pin_obj = new pin_class();

#call getmypin function for all batch detail 
$pinJson=$pin_obj->getMyPin($_SESSION['userid'],$pageNo);
$pinData = json_decode($pinJson, true);

?>

<!--Quick Serach-->
<div class="quicKseachsec  subPageSrch">
    <div class="quickSearch">
             <span class="ic-16 search icon" title="Search"></span> 
             <input type="text" name="searchBatch" id="searchBatch" onkeyup="SearchPins($(this).val())"  placeholder="Search PINs" />
            <div class="replaceBttn fl">
                <a  onclick="PinDetailUrl();" title="Add" class="arBorder cmniner secondry fl cp primary">
               		 <span class="ic-16 add "></span>
               </a>
           </div>
    </div>
    <label class="searchAdd dn cmnClssBtn">
          <input type="text" id="search" placeholder="" class="fl" />             
          <input type="submit" value="Add" class="btn btn-medium btn-primary clear" title="Add" name="">
    </label>
</div>
<!--//Quick Serach-->

<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec">
    <ul class="mngClntList dialplan">
        <?php  foreach($pinData['myPins'] as $pinDetails){
                if($pinDetails['action'] == 0){ $changepinAct = "stopR"; $PinActvalue = "stop"; }else{ $changepinAct=''; $PinActvalue = "play";}
                if($pinDetails['amountStatus'] == 0){ $amountStatus = "unpaid"; $statusValue = "unpaid";}else{ $amountStatus = ''; $statusValue = "paid";}
                ?>

            <li class="" id="pinLi<?php echo $pinDetails['id'];?>" onclick="showPinDetail(this,event);" batchid="<?php echo $pinDetails['id'];?>" >
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
                    <p class="exp">Expire on : <?php echo date('d M Y',strtotime($pinDetails["expire_date"]));?> </p>
            </li>

                <?php }?>

     
<!--		<li onclick="window.location.href='#!manage-pin.php|manage-pin-details.php'" class="active">
			<div class="tariff">
				<h3 class="blackThmCrl">Testplan</h3>
				<p>Tariff: <span class="font15 blackThmCrl">retailINR</span></p>
				<p class="clear mrT1">
					<span class="font15 blackThmCrl">2/20</span> PINs <span class="font15 blackThmCrl">10</span> USD/PIN
				</p>
			</div>
		</li>-->
	</ul>
</div>
<!--//Left Section-->    

<!--Right  Section-->   
<div class= "slideRight" id="rightsec">
</div>
<!--//Right Section-->


<script type="text/javascript">
 var globalTimeout = null;
/* set active
planId: "458"
window['localStorage'] stores above value. this helps to get current state.
*/
var storage = window['localStorage'];
if(storage.getItem('pinId')){
	showPinDetail('#pinLi'+storage.getItem('pinId'),'');	
}
//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 26-07-2013
//function use for show all pin detail by manage-pins.php file 
function showPinDetail(ths,e){
    $(ths).siblings().removeClass('active');
    $(ths).addClass('active');
		
    //get batchid
    var batchid = $(ths).attr('batchid');
    storage.setItem('pinId',batchid);
    //if previous event class is not "action" then batchid send to manage pins.php file for show batch detail   
//	
//	if(!e.target){
//		if($(ths).length > 0){
//			var top = $(ths).position().top;
//			$('.pinlist').scrollTop(top-100);		
//		}
//	}
	
    if(!$(e.target).hasClass('action')){
    $.ajax({
	    url : "manage-pin-details.php",
	    type: "POST",
	    data: {batchid:batchid} ,
	   success: function(text) {          
		  $("#rightsec").html(text);
                  
                  //get page no
                  var page = $('#pageNo').val();
                  
                  var totalPage = $('#totalPage').val();
                  
                  //get batch id
                  var batchId =$('#batchId').val();
       
                  //call function for pagianation
                 // pinPagination(page,totalPage,batchId);
                  
		}
	});
    }
 }
 
 function SearchPins(keyword){
    var xhr;
    if( Object.prototype.toString.call( xhr ) === '[object Object]' ) {
        xhr.abort()
    }
    if(globalTimeout != null) 
    	clearTimeout(globalTimeout);
	globalTimeout=setTimeout(function(){
            
        
	 xhr =	$.ajax({
		   url : "/action_layer.php?action=searchBatch",
		   type: "POST",
                   dataType:"json",
		   data: {data:keyword} ,
		   success: function(data) {
			   var str = batchlistDesign(data); 
			   $('#leftsec ul').html('');
			   $('#leftsec ul').html(str);    
	  
			}
		
	})
        
        },600)
}

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 12/08/2013
//function use for create design of batch pin list 
function batchlistDesign(data)
{
    var str ='';

    if(typeof data != 'object')
        return str;

    var text = data.batch;
    var userName = data.userName;	
	
	$.each( text, function(key, item ) {
	   if(item.action == 0){ var changepinAct = "stopR"; var PinActvalue = "stop"; }else{ var changepinAct=''; var PinActvalue = "play";}
	   if(item.amountStatus == 0){ var amountStatus = "unpaid"; var statusValue = "unpaid";}else{ var amountStatus = ''; var statusValue = "paid";}
	                          

	     
	        str += '<li class="" id="pinLi'+item.id+'" onclick="showPinDetail(this,event);" batchid="'+item.id+'" >\
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
function PinDetailUrl(){
    
 window.location.hash = "";
 window.location.hash = "!manage-pin.php|add-pins.php";
 }
    
$(document).ready(function(){
});
</script>