<?php
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 08-aug-2013
 * @package Phone91
 * @details reseller manage clients  page 
 */
//Include Common Configuration File First
include_once('config.php');
if(!$funobj->check_reseller()){
     $funobj->redirect("index.php");
}
#include reseller_class.php file 
include_once CLASS_DIR.'reseller_class.php';
#create object of reseller_class
$resellerObj = new reseller_class();
#call function manageClients and return json data clientJson
$clientJson=$resellerObj->manageClients($_REQUEST, $_SESSION);
//var_dump($clientJson);
$clientData = json_decode($clientJson, true);   

# use to batch detail ..
//$bulkuser = $resellerObj->bulkUserBatch($_SESSION['userid']);
//$bulkData = json_decode($bulkuser, true);   
?>
<?php 
if($clientData["isSearchResult"]=="false") 
{
?>
<div class="commHeader">
	<div class="showAtFront">
		<a class="btn btn-medium btn-primary alC iconBtn fl mrR1 slideAndBack" title= "Add New Client" href="#!reseller-manage-clients.php|reseller-addnew-client.php">
				<span class="ic-24 addW"></span>
				<span class="iconBtnLbl">Add New Client</span>
		</a>
		<div class="fl" id="srchrow">
<!--			<div id="userType" class="fl">
				<input type="radio" id="single" name="radio" checked="checked"><label for="single">Single</label>
				<input type="radio" id="batch" name="radio"><label for="batch">Batch</label>				
			</div>		-->
			<input type="text" name="searchUser" onkeyup="advanceSearchUser($(this).val())" id="searchUser" placeholder="search User">
	<!--               <label class="srcLbl">
					<p class="fl">Showing <span>1000</span> results by <span>latest</span> whose balance is less than</p>
					<p class="fl showInfo"> 
							<span class="ic-8 close"></span>
							<span class="fl">1000</span>
							<span class="ic-8 arrow"></span>
							<p class="rowClose fl cp" title="Close"><span class="ic-16 close"></span></p>
					</p>
		   </label>-->
	<!--               <div class="sett pr">
				<div class="cp" onclick="uiDrop(this,'#showTrSett', 'true')">
					<i class="ic-16 dropsign"></i>
					<i class="ic-24 setting"></i>
				</div>
				<ul class="dropmenu boxsize ln" id="showTrSett">
					<li onclick="sendAction('mail');" title="Send Mail">Send Mail</li>
					<li onclick="sendAction('sms');" title="Send SMS">Send SMS</li>
					<li title="Delete">Delete</li>
					<li class="divider"></li>
					<li title="Export CSV">Export CSV</li>
					<li title="Export PDF">Export PDF</li>
					<li title="Export XlS">Export XlS</li>
				</ul>
			</div>-->
		</div>
	</div>
	<div class="showAtBack"><a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a></div>	
</div>	
	
	<div id="leftsec" class="slideLeft commLeftSec">
		<div class="innerSection">
			<?php }
				//if($clientJson["isSearchResult"]) 
				   //{ 
			 ?>
			<ul class="ln mngClntList commLeftList" id="clientList">
			<?php foreach($clientData['client'] as $clientDetails){?>
				<li class="group slideAndBack" onclick="window.location.href='#!reseller-manage-clients.php|reseller-client-setting.php?clientId=<?php echo $clientDetails["id"];?>'">                                    <div class="uiwrp cp">
								<i class="ic-16 notif"></i>
								<label><?php echo $clientDetails["uname"];?></label>
						</div>
						<h3 class="ellp font22 nameClient"><?php echo $clientDetails["name"];?></h3>
						<div class="uiwrp cp">
							<?php if($clientDetails["contact_no"] != ''){?>
							<i class="ic-16 correct"></i>
							<label><?php echo $clientDetails["contact_no"];?></label>
							<?php } ?>
						</div>
						<p class="tInfo">
								Tariff 
								<b><?php echo $clientDetails["planName"];?></b>
								<span class="sep">|</span>
								<span><?php echo round($clientDetails["balance"],2); ?></span>  <?php echo $clientDetails["id_currency_name"];?>
							        <?php if($clientDetails["client_type"] != ''){?>
                                                                <span class="sep">|</span>
                                                                Type : 
                                                                <b><?php echo $clientDetails["client_type"];?></b>
                                                                <?php }?>
						</p>
						<div class="actwrp">
								<div class="switch">
		<?php  if($clientDetails["blockUnblockStatus"] != 1){
										   $statusClass ="disabledR";
										   $Bstatus = "block";
										}else{
											$statusClass ="";
											$Bstatus = "unBlock";
										}
										?>
										<label onclick="changeUserStatus(this,<?php echo $clientDetails['id'];?> );" class="ic-sw enabledR <?php echo $statusClass; ?>"></label>
										<input type="checkbox" id="changeStatus<?php echo $clientDetails["id"];?>" style="display:none" checked="checked"  value ="<?php echo $Bstatus; ?>" />
								</div>
						</div>  
						 <span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag(<?php echo $clientDetails["id"];?>);" ></span> 
						<!-- <div class="callCount"></div>
						<div class="callCountHover dn">235 Clients</div>-->
			  </li>
			<?php }?>
				 
			
			</ul>
		
		   <?php //}
				 if($clientData["isSearchResult"]=="false") 
			{ ?>
		</div>	
	</div>
			
	<div id="rightsec" class="commRightSec slideRight bgW">
	</div>

	<div id="mail-dialog" class="dn actM" title="Send Mail">
		<div id="mail-inner">
			<p class="mrB">To</p>
			<p class="srchrow mrB"><strong>1825 IDs available to mail</strong></p>
			<div class="clear irow mrB2">
				<input id="useC" type="checkbox" />
				<label for="useC"><strong>Users (1705)</strong></label> &nbsp;
				<input id="resC" type="checkbox" class="mrL1" />
				<label for="resC" class="danger">(Contains 120 reseller ids)</label>
			</div>
			<p class="mrB">Subject</p>
			 <input type="text" />
			<p class="mrB mrT2">Mail Body (Even HTML can be put)</p>
			 <textarea class="rn"></textarea>
			<a class="mrT2 btn btn-medium btn-primary" href="javascript:void(0);" title="Send Mail">Send Mail</a>
		</div>
	</div>

	<div id="sms-dialog" class="dn actM" title="Send SMS">
		<div id="mail-inner">
			<p class="mrB">To</p>
			<p class="srchrow mrB"><strong>1825 IDs available to SMS</strong></p>
			<div class="clear irow mrB2">
				<input id="useC" type="checkbox" />
				<label for="useC"><strong>Users (1705)</strong></label>
				<input id="resC" type="checkbox" class="mrL1" />
				<label for="resC" class="danger">(Contains 120 reseller ids)</label>
			</div>
			<p class="mrB">Sender ID</p>
		   <input type="text" />
			<p class="mrB mrT2">Content (160 Character)</p>
			<textarea class="rn"></textarea>
			<a class="mrT2 btn btn-medium btn-primary" href="javascript:void(0);" title="Send SMS">Send SMS</a>
		</div>
	</div>

<?php }  ?>
<script type="text/javascript">
var globalTimeout = null;
$("#userType").buttonset();
dynamicPageName('Manage Clients')
slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');

function sendAction(action){
	$( "#"+action+"-dialog" ).dialog({ modal: true, resizable:false, width:600, height:440});
}



//$('#searchUser').keyup(function() {
function advanceSearchUser(keyword){
//  alert('Handler for .keyup() called.');
    var xhr;
    var searchUrl='controller/adminManageClientCnt.php';

    if( Object.prototype.toString.call( xhr ) === '[object Object]' ) {
        xhr.abort()
    }

    if(globalTimeout != null) 
    	clearTimeout(globalTimeout);
	globalTimeout=setTimeout(function(){

 xhr = $.ajax({
    type: "POST",
    dataType:"JSON",
    url: searchUrl,
    data:{'action':'getClientDetailReseller','q':keyword},
    success: function(msg){        
        var str ="";
       if(msg.hasOwnProperty('client') && (msg.client != undefined || msg.client != ""))
       {
           
        $.each(msg.client,function(key,value)
        {
            var contactNo = '';
            if(value.contact_no != ''){
              contactNo = '<i class="ic-16 correct"></i><label>'+value.contact_no+'</label>';
            }
            if(value.blockUnblockStatus != 1)
            {
                var statusClass ="disabledR";
                var Bstatus = "block";
            }
            else
            {
                var statusClass ="";
                var Bstatus = "unBlock";
            }
            
            var usertype = '';
            if(value.client_type != ''){ 
            usertype = '<span class="sep">|</span>Type :<b>'+value.client_type+'</b>';
            }
            
            var balance = parseInt(value.balance);
            
                    str += '<li class="group" onclick="window.location.href=\'#!reseller-manage-clients.php|reseller-client-setting.php?clientId='+value.id+'\'">\
                                    <div class="uiwrp cp">\
                                            	<i class="ic-16 notif"></i>\
                                                <label>'+value.uname+'</label>\
                                        </div>\
                                        <h3 class="ellp font22 nameClient">'+value.name+'</h3>\
                                        <div class="uiwrp cp">\
                                            '+contactNo+'\
                                        </div>\
                                        <p class="tInfo">Tariff \
                                                <b>'+value.planName+'</b>\
                                                <span class="sep">|</span>\
                                                <span>'+balance.toFixed(2)+'</span>\
                                            '+value.id_currency_name+'\
                                            '+usertype+'\
                                       </p> \
                                        <div class="actwrp">\
                                                <div class="switch">\
                    <label onclick="changeUserStatus(this,'+value.id+');" class="ic-sw enabledR '+statusClass+'"></label>\
                    <input type="checkbox" id="changeStatus'+value.id+'" style="display:none" checked="checked"  value ="'+Bstatus+'"/>\
                        </div> </div> \
                                         <span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag('+value.id+');" ></span> \
                              </li>';
        
            
//        }
            });
        }
        $("#clientList").html(str);
        }
    });




	},600)


   
    

//kill the request
//});
}

// created by sudhir pandey <sudhir@hostnsoft.com>
// creation date 19-09-2013
// function use for change user status (block or unblock)
function changeUserStatus(ths,userId){
  
  $(ths).toggleClass('disabledR');
  if($('#changeStatus'+userId).val() == "unBlock"){
     $('#changeStatus'+userId).val("block");
     var status = "block";
     
 }else{
     $('#changeStatus'+userId).val("unBlock");
     var status = "unBlock";
 }
 
   $.ajax({
                   url : "action_layer.php?action=changeUserStatus",
                   type: "POST", 
                   data:{userId:userId,status:status},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);

                   }
})
  

}

// created by sudhir pandey <sudhir@hostnsoft.com>
// creation date 19-09-2013
// function use for change user batch either block or unblock
function BatchBlockOrUnblock(ths,batchId){
  
  $(ths).toggleClass('disabledR');
  if($('#changeBatchStatus'+batchId).val() == "unBlock"){
     $('#changeBatchStatus'+batchId).val("block");
     var status = "block";
     
 }else{
     $('#changeBatchStatus'+batchId).val("unBlock");
     var status = "unBlock";
 }
 
   $.ajax({
                   url : "action_layer.php?action=BatchBlockOrUnblock",
                   type: "POST", 
                   data:{batchId:batchId,status:status},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                   }
})
  

}


function setdeleteFlag(userId){
 var status = "block";
 
 $.ajax({
                   url : "action_layer.php?action=setUserDeleteFlag",
                   type: "POST", 
                   data:{userId:userId,status:status},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);

                   }
})
  
}


function setBatchDeleteFlag(batchId){
var status = "block";
$.ajax({
                   url : "action_layer.php?action=setBatchDeleteFlag",
                   type: "POST", 
                   data:{batchId:batchId,status:status},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);

                   }
})
}

</script>          
