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

$counts = $resellerObj->getTotalUserResellerCount($_SESSION['userid']);

$userCount =0;
$resellerCount = 0;
if(isset($counts['status']) && $counts['status'] == 1)
{
	$userCount = $counts['counts']['userCount'];
	$resellerCount = $counts['counts']['resellerCount'];
}

?>
<?php 
if($clientData["isSearchResult"]=="false") 
{
?>
<div class="commHeader">
	<div class="showAtFront">
    	<div id="uType" class="fl">
            <label for="single" onclick="changeClient('single');">  
                <div id="usRes" class="checkFiltr">
                    <input type="radio" id="single" name="radio" class="mrR" checked>
                    Users | Resellers
                    <span class="u"><?php echo $userCount;?></span>
                    <span class="r"><?php echo $resellerCount;?></span>
                </div>
            </label>
            <label for="batch" onclick="changeClient('batch');">   
                <div id="batcha" class="checkFiltr">
                    <input type="radio" id="batch" name="radio" class="mrR">
                    Batches
                    <span class="b"><?php echo $bulkData['batchDetail']['totalBatches'];?></span>
                </div>
            </label>
        </div> 
    		
		<div class="fl" id="srchrow">
			
            <a class="btn btn-medium btn-blue alC iconBtn fl slideAndBack" title= "Add New Client" href="#!reseller-manage-clients.php|reseller-addnew-client.php">
				<span class="ic-24 addW"></span>
				<span class="iconBtnLbl">Add New Client</span>
			</a>
		</div>
<!--        <a class="btn btn-medium btn-primary alC iconBtn fr tour" href="javascript:void(0);" onclick="beforeTour(this)">Tour</a>-->
        
        <!--tabs (tabbing removed)-->
        <!--<div id="userType" class="fl">
				<input type="radio" id="single" name="radio" checked="checked"><label class="radioBtnSetLbl" for="single" onclick="changeClient('single');">Single</label>
				<input type="radio" id="batch" name="radio"><label class="radioBtnSetLbl" for="batch" onclick="changeClient('batch');">Batch</label>-->
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
			</div>
		</div>-->
	</div>
	<div class="showAtBack"><a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a></div>	
</div>	
	
	<div id="leftsec" class="slideLeft commLeftSec">
    	
        <div class="searchCont">
        	<input type="text" name="searchUser" onkeyup="advanceSearchUser($(this).val())" id="searchUser" placeholder="Search" class="allTypeUser w100Per">
			<input type="text" name="searchBulkUser" onkeyup="advanceSearchBulkUser($(this).val())" id="searchBulkUser" placeholder="Search Bulk User" class="allTypeUser w100Per dn">
        </div>
        
		<div class="innerSection allTypeUser" id="singleUser" style="top:53px;">
			<?php }
				
			 ?>
			<ul class="ln mngClntList" id="clientList">
			<?php foreach($clientData['client'] as $clientDetails){                          
                            $deleteClass = '';
                            $hideIcons = '';
                            if($clientDetails['deleteFlag'] != 0){
                                $deleteClass = "deleted";
                                $hideIcons = 'dn';
                            }?>
                            <li class="group slideAndBack <?php echo $deleteClass; ?>" id="clientLi<?php echo $clientDetails["id"];?>" data-id="<?php echo $clientDetails["id"];?>">
					<div class="uiwrp cp">
								<label><?php echo $clientDetails["uname"];?>
                                	<?php if(isset($clientDetails["client_type"]) && $clientDetails["client_type"] != ''){
									  if($clientDetails["client_type"] == 'reseller'){
										  $colourType = "gold";
									  }else{
										  $colourType = "green";
									  }
									
									?>
									<b class="<?php echo $colourType.' userType'.$clientDetails["id"]; ?> " ><?php echo $clientDetails["client_type"];?></b>
									<?php }?>
                                </label>
                                
						</div>
                                    <?php if($clientDetails['deleteFlag'] == 0){ ?>
						<span title="Login As" class="ic-24 login loginAs clientLi<?php echo $clientDetails["id"].' '.$hideIcons;?>" onclick="redirectLoginUrl(<?php echo $clientDetails["id"]; ?>);" ></span>
                                    <?php }
                                     ?>            
						<h3 class="ellp font22 nameClient"><?php echo $clientDetails["name"];?></h3>
						<div class="uiwrp cp">
							<?php if($clientDetails["contact_no"] != ''){?>
							<i class="ic-16 correct"></i>
							<label><?php echo $clientDetails["contact_no"];?></label>
							<?php } ?>
                                                        
						</div>
						<div class="tInfo pr">
                        		<div class="bal">
                                <span class="<?php echo $clientDetails["id"];?>changeBal" ><?php echo round($clientDetails["balance"],3); ?></span>  <?php echo $clientDetails["id_currency_name"];?></div>
								
								<b><?php echo $clientDetails["planName"];?></b>
                                     
						</div>
						<div class="actwrp action">
								<div class="switch clientLi<?php echo $clientDetails["id"].' '.$hideIcons;?>">
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
						<?php //if($clientDetails['deleteFlag'] == 0){ ?>							 
						 <!-- <span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag(<?php echo $clientDetails["id"];?>,this);" ></span> --> 
						<?php //}
                                                //else {?>
                                                <!--  <span title="Delete" class="cp actdelC" >Deleted</span> -->
                                                <?php //}?>
                                                 <!-- <div class="callCount"></div>
						<div class="callCountHover dn">235 Clients</div>-->
			  </li>
			<?php }?>
				 
			
			</ul>
		
		   
		</div>	
            
            <div class="innerSection allTypeUser dn" id="bulkUser" style="top:53px;">
			
			<ul class="ln mngClntList" id="bulkclientList">
			<?php 
                        if(isset($bulkData['batchDetail']['detail']))
                        foreach($bulkData['batchDetail']['detail'] as $bulkUserDetail){?>
                                <li class="group slideAndBack" id="clientLi<?php echo $bulkUserDetail["batchId"];?>" data-id="<?php echo $bulkUserDetail["batchId"];?>" onclick="window.location.href='#!reseller-manage-clients.php|batchname.php?batchId=<?php echo $bulkUserDetail["batchId"];?>'">
                                        <div class="uiwrp cp">
                                                <h3 class="ellp font22 batchName"><?php echo $bulkUserDetail["batchName"];?> <label>(<?php echo $bulkUserDetail["numberOfClients"];?>)</label></h3>
                                        </div>
                                        <div class="uiwrp cp">
                                                <label>Expiry: <?php echo date('Y-m-d',strtotime($bulkUserDetail["expiryDate"]));?></label>
                                        </div>
                                        <div class="tInfo">
                                        		
                                                 <b><?php echo $bulkUserDetail['tariffName'];?> </b> 
                                                 <div class="bal"><?php echo round($bulkUserDetail['batchBalance'],3)." ".$bulkUserDetail['currencyName'];?>/user</div>
                                        </div>
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
                                        <?php //if($bulkUserDetail['deleteStatus'] > 0){ ?>
                                        <!-- <span title="Delete" class="actdelC cp" >Deleted</span>  -->
                                        <?php //}else{?>
                                    <!--  <span title="Delete" class="ic-24 actdelC cp" onclick="setBatchDeleteFlag(<?php echo $bulkUserDetail["batchId"];?>);" ></span>  -->
                                    <?php //}?>
                                     <!--<div class="callCount"></div>
                                    <div class="callCountHover dn"><?php //echo $bulkUserDetail["numberOfClients"];?> Clients</div>-->
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

//$("#userType").buttonset();
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
	//$("#userType").buttonset('refresh');	
}

$('#clientList li').on('click tap',function(e){
	if(notActionBtn(e)){
		var id = $(this).attr('data-id');
		storage.setItem('clientId',id);	
		currentLi('#clientList li',$(this).attr('id'));

		if(!$(e.target).hasClass('loginAs'))
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

function batchClientTable(text)
{
	var str ='',loginAs='';
   	var url = window.location.hash;
  
  	$.each( text.detail, function(key, item ) {
                          
                          
         if(item.userName != undefined)                 
        {   
                        if(item.deleteFlag > 0)
                            loginAs='<span title="Login As" class="login loginAs" onclick=" javascript:void(0);" >Deleted</span>';
                        else if(item.blockUnblockStatus > 1)
                            loginAs = '<span title="Login As" class="login loginAs" onclick=" javascript:void(0);" >Blocked</span>    ';
                        else
                            loginAs = ' <span title="Login As" class="ic-24 login loginAs" onclick="redirectLoginUrl('+item.userId+')" ></span>';
                          
                          str += '<tr class="">\
                                    <td>'+loginAs+item.userName+'</td>\
                                    <td>'+item.password+'</td>\
                                    <td>'+item.balance+'</td>';
                            if(item.status == 1){
                             var checked='checked=checked';
                            }  else var checked='';
                          str += '<td align="center"><input type="checkbox" '+checked+' userid="'+item.userId+'" name="usedStatus" class ="usedStatus" onChange="changeStatus(this);" /></td>\
                        </tr>';
    } 
                      
                      });

	return str;
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


function setdeleteFlag(userId,btype){
 var status = btype;
 
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
                   		var blockType='block';
                       show_message(text.msg,text.status);
                       if(text.status == "success"){

                       		if(btype == 'block')
                       		{
                       			blockType = 'unBlock';
                       			$('#deleteButton').val('Retrive Account');
                       			$('#deleteButton').removeClass('btn-danger').addClass('btn-primary');
                       			$('#clientLi'+userId).addClass('deleted');
                       			$('#clientLi'+userId).addClass('deleted');
                       			$('.editInfo').addClass('dn');
                       			$('.clientLi'+userId).hide();
                       			$('#edInfo').css('pointer-events','none');
                       			$('#addReduceFund').css('pointer-events','none');
                       		}
                       		else
                       		{
                       			$('#deleteButton').val('Delete Account');		
                       			$('#deleteButton').removeClass('btn-primary').addClass('btn-danger');
                       			$('#clientLi'+userId).removeClass('deleted');
                       			$('.editInfo').removeClass('dn');
                       			$('.clientLi'+userId).show();
                       			$('#edInfo').css('pointer-events','auto');
                       			$('#addReduceFund').css('pointer-events','auto');
                       		}
                       			
                       		$("#deleteButton").attr("onclick","setdeleteFlag("+userId+",'"+blockType+"')");

                       		
                           
                       }

                   }
})
        }
}


function setBatchDeleteFlag(batchId,type){
if(type == 1)
    var status = "unblock";
else
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

                       if(text.status == 'success')
                       {
                           var blockType;
                           if(type == 1)
                           {
                               blockType = 0;
                               $('#deleteBatch').val('Delete Account');		
                               $('#deleteBatch').removeClass('btn-primary').addClass('btn-danger');
                           }
                           else
                           {
                               blockType = 1;
                                $('#deleteBatch').val('Retrive Account');
                                $('#deleteBatch').removeClass('btn-danger').addClass('btn-primary');
                           }
                           
                            $("#deleteBatch").attr("onclick","setBatchDeleteFlag("+batchId+",'"+blockType+"')");
                       		//show all batch clients deleted and there login as should not work
                       		$('.loginAs').html('Deleted').removeClass('ic-24').removeAttr('onclick');
                       }

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

    	var batchClientStr = '';
    	 if(data.batchClientDetail != undefined)
        {
       		batchClientStr+= batchClientTable(data.batchClientDetail);
       		 $( "#batchtbl tbody" ).html('');
              $( "#batchtbl tbody" ).html(batchClientStr);
              $("#batchtbl tbody tr:visible:even").addClass("even"); 
              $("#batchtbl tbody tr:visible:odd").addClass("odd");

        }

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
        
       
       $.each(data.batchDetail.detail,function(key,value)
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
						  <h3 class="ellp font22 batchName">'+value.batchName+'<label> ('+value.numberOfClients+')</label></h3>\
                      </div>\
                      <div class="uiwrp cp">\
                          <label>Expiry: '+value.expiryDate+'</label>\
                      </div>\
                      <div class="tInfo">\
						  <b>'+value.tariffName+'</b>\
						  <div class="bal">\
                          	<label>'+batchBalance.toFixed(2)+' '+value.currencyName+'/user</label>\
						  </div>\
                      </div>\
                      <div class="actwrp">\
						  <div class="switch">\
						  <label onclick="BatchBlockOrUnblock(this,'+value.batchId+')" class="ic-sw enabledR '+statusClass+'"></label>\
						  <input type="checkbox" id="changeBatchStatus'+value.batchId+'" style="display:none" checked="checked"  value ="'+bulkBstatus+'" />\
						  </div>\
                      </div>\
                      <div class="callCount"></div>\
                      <div class="callCountHover dn">'+value.numberOfClients+' Clients</div></li>';
            
        });
        
        if(str == ''){
          str += "<li>No Record found... </li>";  
        }

	
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
          var deleteClass = ''; 
          var hideIcons = '';
        $.each(msg.client,function(key,value)
        {
            var contactNo = '';
            if(value.contact_no != ''){
              contactNo = '<i class="ic-16 correct"></i><label>'+value.contact_no+'</label>';
            }
            var loginAs='';
            if(value.deleteFlag == 0){
            	hideIcons = '';
            	deleteClass = '';	
                var hrefLocation ='/controller/signUpController.php?call=loginAs&userId='+value.id+'&url='+url.substring(1);
                loginAs='<span title="Login As" class="ic-24 login loginAs clientLi'+value.id+'" onclick="redirectLoginUrl('+value.id+')"></span>';
            }
            else
            {
            	hideIcons = 'dn';
            	deleteClass = 'deleted';
            	loginAs='<span title="Login As" class="ic-24 login loginAs '+hideIcons+'" onclick="redirectLoginUrl('+value.id+')"></span>';

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
            usertype = value.client_type
            }
            

            if(usertype == 'user')
            	userClass = 'green';
            else
            	userClass = 'gold';
            var balance = parseFloat(value.balance);
            
                    str += '<li class="group slideAndBack '+deleteClass+'" id="clientLi'+value.id+'" data-id="'+value.id+'" onclick="window.location.hash=\'!reseller-manage-clients.php|reseller-client-setting.php?clientId='+value.id+'\'">\
                                    <div class="uiwrp cp">\
                                                <label>'+value.uname+' <b class="'+userClass+'">'+usertype+'</b></label>\
                                        </div>\
                                        '+loginAs+'<h3 class="ellp font22 nameClient">'+value.name+'</h3>\
                                        <div class="uiwrp cp">\
                                            '+contactNo+'\
                                        </div>\
                                        <div class="tInfo"> \
											<div class="bal"> \
												<span class="'+value.id+"changeBal"+'">'+balance.toFixed(2)+'</span>\
												'+value.id_currency_name+'\
											</div>\
											<b>'+value.planName+'</b> \
                                        </div> \
                                        <div class="actwrp action">\
                                                <div class="switch clientLi'+value.id+' '+hideIcons+'">\
                    <label onclick="changeUserStatus(this,'+value.id+');" class="ic-sw enabledR '+statusClass+'"></label>\
                    <input type="checkbox" id="changeStatus'+value.id+'" style="display:none" checked="checked"  value ="'+Bstatus+'"/>\
                        </div></div>\
                              </li>';
        
        	$('clientLi'+value.id).hide();
            
        //}
            });
            }else
                str += "<li>No Record found... </li>";


            return str;
}


var pageNo = <?php echo $pageNo; ?>;

if(pageNo == undefined || pageNo == '' || pageNo == null)
    pageNo = 1;
 
var pages =  <?php if(isset($clientData['pages'])) echo $clientData['pages'];else echo 1; ?>;
if(pages == undefined || pages == '' || pages == null)
   pages = 1;

//
loadMoreDetail(2,pages,'action_layer.php?action=loadMoreClientByPage','createDesign','clientList',function(str){	
	//$(str).each(function(){
		$('#clientList li').on('click tap',function(e){
			if(notActionBtn(e)){
				var id = $(this).attr('data-id');
				storage.setItem('clientId',id);
				currentLi('#clientList li',$(this).attr('id'));
                                if(!$(e.target).hasClass('loginAs'))
                                    window.location.hash='!reseller-manage-clients.php|reseller-client-setting.php?clientId='+id;
			}
		})
	//})
});

var Bulkpages =  <?php if(isset($bulkData['batchDetail']['pages']) && is_numeric($bulkData['batchDetail']['pages']) && $bulkData['batchDetail']['pages'] != 0) echo $bulkData['batchDetail']['pages'];else echo 1; ?>;
if(Bulkpages == undefined || Bulkpages == '' || Bulkpages == null)
   Bulkpages = 1;

var resId = <?php echo $resId; ?>;
loadMoreDetail(2,Bulkpages,'action_layer.php?action=loadMoreBatchClientByPage&resId='+resId+'&q='+$('#searchBulkUser').val(),'createBulkDesign','bulkclientList');
//kill the request


function beforeTour(ths)
{
//    $('#clientList').html('').scrollTo('top');
    $('#clientList').load('dummyClient.php');
    $('#rightsec').load('dummyClientDetails.php');
//window.location.hash = '!reseller-manage-clients.php|dummyClientDetails.php';

    setTimeout(function(){
        tour(1,ths);
    },1000);
}

//initialise tiptip for help icon
$(".loginAs").tipTip();


function redirectLoginUrl(key){

//var hrefLocation ='/controller/signUpController.php?call=loginAs&type=1&userId='+key+'&url='+url.substring(1);
//                        
// window.location.href=\''+hrefLocation+'\'
 $.ajax({
                   url : "/controller/signUpController.php?call=loginAs",
                   type: "POST", 
                   data:{type:1,userId:key},
                   dataType: "json",
                   success:function (text)
                   {
                       console.log(text);
                       if(text.status == "success"){
                       window.open('https://voice.utteru.com/loginAs.php?token='+text.token);
                       
                     }
                      
                   }
               })
 
 
}
</script>