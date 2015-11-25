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

//get page number
if(!isset($_REQUEST['pageNo']) || !is_numeric($_REQUEST['pageNo']))
{
    $_REQUEST['pageNo'] = 1;
    $pageNo = 1;
}
    

#include reseller_class.php file 
include_once CLASS_DIR.'reseller_class.php';
#create object of reseller_class
$resellerObj = new reseller_class();


#call function manageClients and return json data clientJson
$clientJson=$resellerObj->manageClients($_REQUEST, $_SESSION);
//var_dump($clientJson);
$clientData = json_decode($clientJson, true);   


//get reseller id that will use in js
$resId = $_SESSION['userid'];

# use to batch detail ..
$bulkuser = $resellerObj->bulkUserBatch($_SESSION['userid']);
$bulkData = json_decode($bulkuser, true); 

$protocol = $funobj->getProtocol();
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
			<div id="userType" class="fl">
				<input type="radio" id="single" name="radio" checked="checked"><label class="radioBtnSetLbl" for="single" onclick="changeClient('single');">Single</label>
				<input type="radio" id="batch" name="radio"><label class="radioBtnSetLbl" for="batch" onclick="changeClient('batch');">Batch</label>				
			</div>		
			<input type="text" name="searchUser" onkeyup="advanceSearchUser($(this).val())" id="searchUser" placeholder="search User" class="allTypeUser">
			<input type="text" name="searchBulkUser" onkeyup="advanceSearchBulkUser($(this).val())" id="searchBulkUser" placeholder="search Bulk User" class="allTypeUser dn">
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
		<div class="innerSection allTypeUser" id="singleUser">
			<?php }
				
			 ?>
			<ul class="ln mngClntList commLeftList" id="clientList">
			<?php foreach($clientData['client'] as $clientDetails){?>
				<li class="group slideAndBack" id="clientLi<?php echo $clientDetails["id"];?>" data-id="<?php echo $clientDetails["id"];?>">
					<div class="uiwrp cp">
								<i class="ic-16 notif"></i>
								<label><?php echo $clientDetails["uname"];?></label>
						</div>
                                    <?php if($clientDetails['deleteFlag'] == 0){ ?>
						<span title="Login As" class="ic-24 login loginAs" onclick="var url = window.location.hash;window.location.href='/controller/signUpController.php?call=loginAs&userId=<?php echo $clientDetails['id']; ?>&url='+url.substring(1)" ></span>
                                    <?php }
                                     ?>            
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
								<span class="<?php echo $clientDetails["id"];?>changeBal" ><?php echo round($clientDetails["balance"],3); ?></span>  <?php echo $clientDetails["id_currency_name"];?>
							        <?php if(isset($clientDetails["client_type"]) && $clientDetails["client_type"] != ''){?>
                                                                <span class="sep">|</span>
                                                                Type : 
                                                                <b><?php echo $clientDetails["client_type"];?></b>
                                                                <?php }?>
						</p>
						<div class="actwrp action">
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
						<?php if($clientDetails['deleteFlag'] == 0){ ?>							 
						 <span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag(<?php echo $clientDetails["id"];?>,this);" ></span> 
						<?php }
                                                else {?>
                                                 <span title="Delete" class="cp actdelC" >Deleted</span>
                                                <?php }?>
                                                 <!-- <div class="callCount"></div>
						<div class="callCountHover dn">235 Clients</div>-->
			  </li>
			<?php }?>
				 
			
			</ul>
		
		   
		</div>	
            
            <div class="innerSection allTypeUser dn" id="bulkUser" >
			
			<ul class="ln mngClntList commLeftList" id="bulkclientList">
			<?php 
                        if(isset($bulkData['detail']))
                        foreach($bulkData['detail'] as $bulkUserDetail){?>
                                <li class="group slideAndBack" id="clientLi<?php echo $bulkUserDetail["batchId"];?>" data-id="<?php echo $bulkUserDetail["batchId"];?>" onclick="window.location.href='#!reseller-manage-clients.php|batchname.php?batchId=<?php echo $bulkUserDetail["batchId"];?>'">
                                        <div class="uiwrp cp">
                                                 <i class="ic-16 notif notifyBatch"></i>
                                                <h3 class="ellp font22 batchName"><?php echo $bulkUserDetail["batchName"];?> <label>(<?php echo $bulkUserDetail["numberOfClients"];?>)</label></h3>
                                        </div>
                                        <div class="uiwrp cp">
                                                <i class="ic-16 "></i>
                                                <label><?php echo date('Y-m-d',strtotime($bulkUserDetail["expiryDate"]));?></label>
                                                <label>Balance : <?php echo round($bulkUserDetail['batchBalance'],3)." ".$bulkUserDetail['currencyName'];?>/user</label>
                                        </div>
                                        <p class="tInfo">
                                        		Tariff
                                                 <b><?php echo $bulkUserDetail['tariffName'];?> </b> 
<!--                                                 <span class="sep">|</span>
                                                  <span>  No. of Client :  </span>-->
                                        </p>
                                  	   <div class="actwrp">
                                            <div class="switch">
						<?php if($bulkUserDetail['blockStatus'] != 1){
                                                        $statusClass ="disabledR";
                                                        $bulkBstatus = "block";
                                                    }else{
                                                        $statusClass ="";
                                                        $bulkBstatus = "unBlock";
                                                    }
                                               
                                                    ?>
                                    <label onclick="BatchBlockOrUnblock(this,<?php echo $bulkUserDetail["batchId"];?> );" class="ic-sw enabledR <?php echo $statusClass; ?>"></label>
                                                        <input type="checkbox" id="changeBatchStatus<?php echo $bulkUserDetail["batchId"];?>" style="display:none" checked="checked"  value ="<?php echo $bulkBstatus; ?>" />
                                              
                                            </div>
                                    	</div>
                                        <?php if($bulkUserDetail['deleteStatus'] > 0){ ?>
                                        <span title="Delete" class="actdelC cp" >Deleted</span> 
                                        <?php }else{?>
                                     <span title="Delete" class="ic-24 actdelC cp" onclick="setBatchDeleteFlag(<?php echo $bulkUserDetail["batchId"];?>);" ></span> 
                                    <?php }?>
                                     <div class="callCount"></div>
                                    <div class="callCountHover dn"><?php echo $bulkUserDetail["numberOfClients"];?> Clients</div>
                               	</li>
                            <?php } ?>          
				 
			
			</ul>
		
		   
		</div>	
            
	</div>
	<?php //}
                                 if($clientData["isSearchResult"]=="false") 
                            { ?>		
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
/* set active
clientType: "single"
clientId: "32488"
clientIdBatch: "2"
window['localStorage'] stores above values. this helps to get current state.
*/
var storage = window['localStorage'];

$("#userType").buttonset();
var globalTimeout = null;
dynamicPageName('Manage Clients')
slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');

if(storage.getItem('clientId')){
	currentLi('#clientList li','clientLi'+storage.getItem('clientId'))
	currentLi('#bulkclientList li','clientLi'+storage.getItem('clientIdBatch'))
	changeClient(storage.getItem('clientType'));
}

function currentLi(remove,add){
	$(remove).removeClass('active');
	$('#'+add).addClass('active');
	$('#'+storage.getItem('clientType')).attr('checked','checked');
	$("#userType").buttonset('refresh');	
}

$('#clientList li').on('click tap',function(e){
	if(notActionBtn(e)){
		var id = $(this).attr('data-id');
		storage.setItem('clientId',id);	
		currentLi('#clientList li',$(this).attr('id'));
		window.location.hash='!reseller-manage-clients.php|reseller-client-setting.php?clientId='+id;
	}
})

$('#bulkclientList li').click(function(){
	storage.setItem('clientIdBatch',$(this).attr('data-id'));
	currentLi('#bulkclientList li',$(this).attr('id'))
})

/**
 *@author Ankit patidar <ankitpatidar@hostnsoft.com>
 *@since 10/03/2014
 *@function for login as 
 */
function loginAs(userId)
{
    //make a ajax request to login as selected user
    $.ajax({
    type: "GET",
    dataType:"json",
    url: '/controller/signUpController.php',
    data:{call:'loginAs',userId:userId},
    success: function(data){
        console.log('login as feature');
     
console.log(data);
//    	var str = createBulkDesign(data);   
//        $("#bulkclientList").html(str);
//		
//		$('#bulkclientList li').click(function(){
//			currentLi('#bulkclientList li',$(this).attr('id'))
//		})
//		
        }
    });
    
}


function changeClient(clientType){
	var top=0;
    if(clientType == 'batch'){
        $('.allTypeUser').hide();
        $('#bulkUser').show();
        $('#searchBulkUser').show();
		storage.setItem('clientType',clientType);
		if(storage.getItem('clientIdBatch')){
        	window.location.hash = '!reseller-manage-clients.php|batchname.php?batchId='+storage.getItem('clientIdBatch');
			if($("#clientLi"+storage.getItem('clientIdBatch')).length > 0){
				top = $("#clientLi"+storage.getItem('clientIdBatch')).position().top;
				$('#bulkclientList').scrollTop(top-100);
			}
		}
		else
			$('#rightsec').empty();
			
    }
	else
	{
        $('.allTypeUser').hide();
        $('#singleUser').show();
        $('#searchUser').show();
		storage.setItem('clientType',clientType);
		if(storage.getItem('clientId')){
			window.location.hash = '!reseller-manage-clients.php|reseller-client-setting.php?clientId='+storage.getItem('clientId');
			if($("#clientLi"+storage.getItem('clientId')).length > 0){
				top = $("#clientLi"+storage.getItem('clientId')).position().top;
				$('#clientList').scrollTop(top-100);
			}
		}
		else
			$('#rightsec').empty();
	}	
}

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
        
       var str = createDesign(msg);
        $("#clientList").html(str);
		slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
		$('#clientList li').click(function(){
			currentLi('#clientList li',$(this).attr('id'))
		})	
        
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


function setdeleteFlag(userId,ths){
 var status = "block";
 
  var conf = confirm("are you sure you want to delete this user");
        if(conf == true)
        {
 $.ajax({
                   url : "action_layer.php?action=setUserDeleteFlag",
                   type: "POST", 
                   data:{userId:userId,status:status},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                           $(ths).hide();
                       }

                   }
})
        }
}


function setBatchDeleteFlag(batchId){
var status = "block";
var conf = confirm("are you sure you want to delete this batch");
        if(conf == true)
        {
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
}



function advanceSearchBulkUser(keyword){
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
    data:{'action':'getBulkClientDetail','q':keyword},
    success: function(data){ 

    	var str = createBulkDesign(data);   
        $("#bulkclientList").html(str);
		slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
		$('#bulkclientList li').click(function(){
			currentLi('#bulkclientList li',$(this).attr('id'))
		})
		
        }
    });




	},600)



//});
}

function createBulkDesign(data)
{
        var str = '';
        
        //validate data
        if(typeof data != 'object')
            return str;
        
        var statusClass='';
        var bulkBstatus='';
       $.each(data.detail,function(key,value)
        {
            if(value.blockStatus != 1){
                statusClass ="disabledR";
                bulkBstatus = "block";
            }else{
                statusClass ="";
                bulkBstatus = "unBlock";
            }
            
            var batchBalance = parseFloat(value.batchBalance);
            
            str +='<li class="group slideAndBack" id="clientLi'+value.batchId+'" onclick="window.location.href=\'#!reseller-manage-clients.php|batchname.php?batchId='+value.batchId+'\'">\
                    <div class="uiwrp cp">\
                      <i class="ic-16 notif notifyBatch"></i>\
                      <h3 class="ellp font22 batchName">'+value.batchName+'<label>('+value.numberOfClients+')</label></h3>\
                      </div>\
                      <div class="uiwrp cp">\
                      <i class="ic-16 "></i>\
                          <label>'+value.expiryDate+'</label>\
                          <label>Balance : '+batchBalance.toFixed(2)+' '+value.currencyName+'/user</label>\
                          </div>\
                          <p class="tInfo">\
                              Tariff\
                              <b>'+value.tariffName+'</b>\
                        </p>\
                        <div class="actwrp">\
                        <div class="switch">\
                        <label onclick="BatchBlockOrUnblock(this,'+value.batchId+')" class="ic-sw enabledR '+statusClass+'"></label>\
                        <input type="checkbox" id="changeBatchStatus'+value.batchId+'" style="display:none" checked="checked"  value ="'+bulkBstatus+'" />\
                        </div>\
                        </div>\
                        <span title="Delete" class="ic-24 actdelC cp" onclick="setBatchDeleteFlag('+value.batchId+');" ></span>\
                        <div class="callCount"></div>\
                        <div class="callCountHover dn">'+value.numberOfClients+' Clients</div></li>';
            
        });
	
	return str;
		
}

/**
 * @uses function to create design for manage client
 */
function createDesign(msg)
{
	var str ="";
        
        //validate msg
        if(typeof msg != 'object')
            return str;
        
       if(msg.hasOwnProperty('client') && (msg.client != undefined || msg.client != ""))
       {
          var url = window.location.hash; 
        $.each(msg.client,function(key,value)
        {
            var contactNo = '';
            if(value.contact_no != ''){
              contactNo = '<i class="ic-16 correct"></i><label>'+value.contact_no+'</label>';
            }
            var deleteFlage = '',loginAs='';
            if(value.deleteFlag == 0){
                
                var hrefLocation ='/controller/signUpController.php?call=loginAs&userId='+value.id+'&url='+url.substring(1);
                loginAs='<span title="Login As" class="ic-24 login loginAs" onclick="window.location.href=\''+hrefLocation+'\'"></span>';
				deleteFlage='<span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag('+value.id+',this);" ></span>';
            }
            else
                loginAs='<span title="Login As" class="ic-24 login loginAs" onclick="javascript:void(0);"></span>';
            
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
            
            var balance = parseFloat(value.balance);
            
                    str += '<li class="group slideAndBack" id="clientLi'+value.id+'" data-id="'+value.id+'" onclick="window.location.hash=\'!reseller-manage-clients.php|reseller-client-setting.php?clientId='+value.id+'\'">\
                                    <div class="uiwrp cp">\
                                            	<i class="ic-16 notif"></i>\
                                                <label>'+value.uname+'</label>\
                                        </div>\
                                        '+loginAs+'<h3 class="ellp font22 nameClient">'+value.name+'</h3>\
                                        <div class="uiwrp cp">\
                                            '+contactNo+'\
                                        </div>\
                                        <p class="tInfo">Tariff \
                                                <b>'+value.planName+'</b>\
                                                <span class="sep">|</span>\
                                                <span class="'+value.id+"changeBal"+'">'+balance.toFixed(2)+'</span>\
                                            '+value.id_currency_name+'\
                                            '+usertype+'\
                                       </p> \
                                        <div class="actwrp action">\
                                                <div class="switch">\
                    <label onclick="changeUserStatus(this,'+value.id+');" class="ic-sw enabledR '+statusClass+'"></label>\
                    <input type="checkbox" id="changeStatus'+value.id+'" style="display:none" checked="checked"  value ="'+Bstatus+'"/>\
                        </div> </div>'+deleteFlage+'\
                              </li>';
        
            
        //}
            });
            }


            return str;
}


var pageNo = <?php echo $pageNo; ?>;

if(pageNo == undefined || pageNo == '' || pageNo == null)
    pageNo = 1;
 
var pages =  <?php if(isset($clientData['pages'])) echo $clientData['pages'];else echo 1; ?>;
if(pages == undefined || pages == '' || pages == null)
   pages = 1;

loadMoreDetail(2,pages,'action_layer.php?action=loadMoreClientByPage','createDesign','clientList',function(str){	
	//$(str).each(function(){
		$('#clientList li').on('click tap',function(e){
			if(notActionBtn(e)){
				var id = $(this).attr('data-id');
				storage.setItem('clientId',id);
				currentLi('#clientList li',$(this).attr('id'));
				window.location.hash='!reseller-manage-clients.php|reseller-client-setting.php?clientId='+id;
			}
		})
	//})
});

var Bulkpages =  <?php if(isset($bulkData['pages']) && is_numeric($bulkData['pages']) && $bulkData['pages'] != 0) echo $bulkData['pages'];else echo 1; ?>;
if(Bulkpages == undefined || Bulkpages == '' || Bulkpages == null)
   Bulkpages = 1;

var resId = <?php echo $resId; ?>;
loadMoreDetail(2,Bulkpages,'action_layer.php?action=loadMoreBatchClientByPage&resId='+resId+'&q='+$('#searchBulkUser').val(),'createBulkDesign','bulkclientList');
//kill the request

</script>          
