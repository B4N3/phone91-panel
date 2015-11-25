<?php 
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 04-sep-2013
 * @package Phone91
 * @details reseller manage clients - batchname  page 
 */
//Include Common Configuration File First
include_once('config.php');
if(!$funobj->check_reseller()){
     $funobj->redirect("index.php");
}

//get page number
if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;


#include reseller_class.php file 
include_once CLASS_DIR.'reseller_class.php';
#create object of reseller_class
$resellerObj = new reseller_class();
#get batchid from request 
$batchId = $_REQUEST['batchId'];
$batchUserData =  $resellerObj->getBatchDetail($batchId,$pageNo);
$batchData = json_decode($batchUserData, true);    



if(!isset($batchData['batchName'])){
       $funobj->redirect('userhome.php#!reseller-manage-clients.php|reseller-addnew-client.php');
}


//get listen time status
$listenTime = 0;
$listenChecked = '';
$listenDisabled = 'disabledR';
$listenOn = '0';
$resJson = $resellerObj->getListenRemainingTimeStatusForBatch($_SESSION['id'],$batchId);

$res = json_decode($resJson,TRUE);
if(isset($res['status']) && $res['status'] == 1 && $res['listenStatus'] == 1) 
{
    $listenTime = $res['listenStatus'];
    $listenChecked = 'checked';
    $listenDisabled='';
    $listenOn = '1';
}

/*
 [batchId] => 9
    [batchName] => forth batch
    [numberOfClients] => 2
    [expiryDate] => 2013-09-26 00:00:00
    [resellerId] => 2
    [createDate] => 2013-09-04 17:07:48
    [userDetail] => Array
        (
            [0] => Array
                (
                    [userId] => 31208
                    [balance] => 10.000000
                    [userName] => 
                    [password] => 
                )
        )*/
?>
<!--Batch  Main Wrapper-->
<div id="batchWrap">

    <div id="tabs">
        
        <div class="fr btname">
             <h3><span><?php echo $batchData['batchName'];?></span></h3>

             <p class="batchInfo mrB"> 
                    <span>Created on: <?php echo $batchData['createDate']; ?></span>
             </p>
        </div> 
        
        <ul>	
            <li><a href="#tabs-1"><span class="hideInTablet">Batch</span> Info</a></li>
            <li onclick ="getbatchTransactionLog(<?php echo $batchId; ?>)"><a href="#tabs-2"><span class="hideInTablet">Transaction</span> Log</a></li>
            <li onclick="deleteBatchStatus(<?php echo $batchId;?>);"><a href="#tabs-3"><span  class="hideInTablet">Edit</span> Batch</a></li>
        </ul>
        
        <!--Tabs 1 wrap-->
        <div id="tabs-1" class="tabs">
              <div class="clear srchrowSec mrB1">
                <input type="text" placeholder="Search" id="searchUserName" class="fl"/>
                <input type="hidden" id="batchId" value="<?php echo $batchId; ?>"/>
                <?php if($batchData['sipStatus'] != 1){?>
                <a style="color:rgb(14, 14, 70);font-size:14px" id="blkSipEnb" onclick="enableSip(<?php echo $batchId; ?>);">Click here to Enable Sip </a>
                <?php }?>
                
              	<div class="exportbtn">
                   <h3 class="ligt mrR fl">Export : </h3>
                   <a target="_blank" href="/controller/adminManageClientCnt.php?batchId=<?php echo $batchId; ?>&type=csv&action=exportBatchUser" ><input class="btn btn-medium" type="button" value="CSV"/></a>
                   <a target="_blank" href="/controller/adminManageClientCnt.php?batchId=<?php echo $batchId; ?>&type=xlsx&action=exportBatchUser" ><input class="btn btn-medium" type="button" value="XLS"/></a>
              	</div>
                <!--<div class="sett pr">
                       <div class="cp" onclick="uiDrop(this,'#showDwSett', 'true')"> <i class="ic-24 setting"></i> <i class="ic-16 dropsign"></i> </div>
                     <ul class="dropmenu boxsize ln" id="showDwSett">
                        <li title="Export CSV">Export CSV</li>
                        <li  title="Export PDF">Export PDF</li>
                        <li  title="Export XlS">Export XlS</li>
                      </ul>
                      <select name="" class="selectPage">
                            <option>100</option>
                            <option>150</option>
                            <option>200</option>
                            <option>250</option>
                      </select>
                </div>--> 
              </div>
              
             <div class="flip-scroll">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" id="batchtbl" class="cmntbl boxsize">
                    <thead>
                        <tr>
                            <th width="25%">User</th>
                            <th width="25%">Password <a id="showAllP" class="addmorelink fr">Show All</a><a id="hideAllP" class="addmorelink fr dn">Hide All</a></th>
                            <th width="25%">Balance</th>
                            <th width="25%">Sold?</th>
                            <th width="25%">Block/Unblock</th>
                        </tr>
                    </thead>
                    <tbody>
                          <?php foreach($batchData['userDetail'] as $userDetail) {?>
                          <tr class="">
                            <td>
                            <?php if($userDetail['deleteFlag'] > 0){ ?>
                                <span title="Login As" class="login loginAs" onclick=" javascript:void(0);" >Deleted</span>    
                            <?php }
                                        elseif($userDetail['blockUnblockStatus'] > 1) {?>
                                                    <span title="Login As" class="login loginAs" onclick=" javascript:void(0);" >Blocked</span>    
                                        <?php }
                            
                                  else {?>
                                       <span title="Login As" class="ic-24 login loginAs" onclick="var url = window.location.hash;window.location.href='/controller/signUpController.php?call=loginAs&userId=<?php echo $userDetail['userId']; ?>&url='+url.substring(1)" ></span>              
                                        <?php }
                                        ?>       
                                        
                            <?php echo $userDetail['userName']; ?>
                            
                            </td>
                            <td>
                            	<div class="hideThis">
                                    <span>**********</span>
                                    <a class="themeLink fr">Show</a>
                                </div>
                            	<div class="showThis dn">
                                    <span><?php echo $userDetail['password']; ?></span>
                                    <a class="themeLink fr">Hide</a>
                                </div>
                            </td>
                            <td><?php echo round($userDetail['balance'],3); ?></td>
                            <?php if($userDetail['status']==1){
                                            $checked='checked=checked';
                                        }  else $checked='';?>
                            <td class="ynchk">
                                <input type="checkbox" <?php echo $checked;?> userid="<?php echo $userDetail['userId']; ?>" name="usedStatus" class ="usedStatus mrL mrR" onChange="changeStatus(this);" />
                             </td>
                             
                             <td >
                                 
                              <?php  if($userDetail["blockUnblockStatus"] != 1){
                                                $statusClass ="disabledR";
                                                $Bstatus = "block";
                                            }else{
                                                    $statusClass ="";
                                                    $Bstatus = "unBlock";
                                            }
                                            ?>
                                    <label onclick="changeUserStatus(this,<?php echo $userDetail['userId'];?> );" class="ic-sw enabledR <?php echo $statusClass; ?>"></label>
                                    <input type="checkbox" id="changeStatus<?php echo $userDetail['userId'];?>" style="display:none" checked="checked"  value ="<?php echo $Bstatus; ?>" />
                            
                             </td>
                          </tr>
                          <?php } ?>
                    </tbody>
                </table>
                <div id="pagination" class="mrT2"></div>
    			<!--<h3>pagination come here</h3>-->
        	</div><!--flip scroll ends-->
        </div><!--Tabs 1 wrap ends-->
        
        <!--Tabs 2 wrap-->
        <div id="tabs-2" class="tabs">
            <div class="" id="batchtransaction">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl boxsize" id="batchTrstable">
                    <thead>
                        <tr>
                            <th width="15%">Date</th>
                            <th width="10%">Type</th>
                            <th width="10%" class="alR">Talktime</th>
                            <th width="25%">Description</th>
                            <th width="15%" class="alR">Debit</th>
                            <th width="15%" class="alR">Credit</th>
                            <th width="15%" class="alR">Closing Balance</th>
                        </tr>
                    </thead>
          			<tbody><td class="f15" colspan="2">No Transactions..!</td></tbody>
                </table>
            </div>
        
        <!-- Bootm Actions Wrapper-->
        <h3 class="mrT3 mrB">Add your money transactions here!</h3>
        <div class="transMoney clear fl">
        
        	<div class="transHd clear">
            	
                <div class="fl">
                	<input class="mrR fl addReduceRadio" type="radio" name="money" id="batchreceived" value="add"/><label for="received" class="cp fl">Received Money</label><i title="Money you have received from <?php echo $batchData['batchName'];?>" class="ic-16 help fl mrL mrR2"></i>
                </div>
                <div class="fl">
                	<input class="mrR fl addReduceRadio" type="radio" name="money" id="batchgiving" value="reduce"/><label for="giving" class="cp fl">Giving Money</label>
                	<i title="Money you are giving to <?php echo $batchData['batchName']?>" class="ic-16 help fl mrL"></i>
                </div>
            </div>
            
            <div id="batchaddReduceFund" class="transInner" style="<?php //echo $deletedStyle;?>">
                <div class="actionDiv">
                    <p class="mrB">Type</p>
                    
                      <div class="fields">          
                            <select name="transType" id="transType">
                                <option value="Cash">Cash</option>
                                <option value="Memo">Memo</option>
                                <option value="Bank">Bank</option>
                                <option value="Other">Other</option>
                            </select>
                      </div>
                      <input type="hidden" name="batchId" value="<?php echo $batchId; ?>" id="batchId"/>                
                </div>
                
                <div class="dn actionDiv" id="transotherType">
                    <p class="mrB">Enter Type</p>
                    <div class="fields">
                    <input type="text" name="transTypeOther"  id="transTypeOther" />
                    </div>
                </div>
                
                <div class="actionDiv mDevice">
                    <div class="sporow clear fields">
                        <!--<span class="funder">
                            <label onclick="toggleState($(this),'Trans');" for="changefunder" class="ic-32 bigfadder cp"></label>
                            <input type="checkbox" id="changefunderTrans" style="display:none" checked="checked" value="add" />
                        </span>-->
                        <p class="mrB">&nbsp;</p>
                        <input type="text" name="transAmount" id="transAmount" placeholder="Amount"/>
                        <select name="currency" id="currency" class="currencyList">
                             <!--  do not remove currencylist class -->
                        </select>                    
                    </div>				
                </div>
                
                <div class="actionDiv">
                    <p class="mrB">Description</p>
                    <div class="fields">
                        <textarea name="description" id="description"></textarea>
                    </div>
                </div>
                
                <div class="actionDiv">
                    <p class="mrB additionalTransLbl">&nbsp;</p>
                    <div class="fields">
                    <input type="button" class="btn btn-mini btn-blue" name="additionalTrans" id="batchAdditionalTrans" value="ADD TRANSACTION" onclick="addBatchAdditionTransaction();"/>
                    </div>
                </div>
            </div>
            
        </div>
    	<!-- //Bootm Actions Wrapper-->
            
            
        </div><!--Tabs 2 wrap ends-->
        
        <!--Tabs 3 wrap-->
        <div id="tabs-3" class="tabs">
        	<div id="rightwrap">
                <form id="editBatchDetailform">
                    <div class="fields">
                        <label>Batch Name</label>
                        <input type="text" name="batchName" id="batchName" value="<?php echo htmlentities($batchData['batchName']);?>" />
                        <input type="hidden" name="batchId" value="<?php echo $batchId; ?>">
                    </div>
                   
                    <div class="fields">
                        <label>Batch Expiry</label>
                         <input type="text" id="batchExpiry"  name="batchExpiry" value="<?php echo date('Y-m-d',strtotime($batchData['expiryDate'])); ?>" />
                    </div>
                    
                    <button title="Update" type="submit" class="btn btn-medium btn-blue mrB2">Update</button>
                    <div class="borderMid"></div>
                    
                    <table class="mrT2 mrB2" border="1" bordercolor="#ddd">
                    	<tr><td class="pd">
                         <!--<input id="batchListenStatus" type="checkbox" <?php echo $listenChecked; ?> onchange="changeBatchRemainTimeStatus(<?php echo $_SESSION['id'];?>,<?php echo $batchId;?>)";>-->Listen remaining time
                    	</td>
                        <td class="pdT pdL pdR">
                            <label id="listenRem" class="ic-sw enabledR cp <?php echo $listenDisabled;?>" onclick="changeBatchRemainTimeStatus(<?php echo $_SESSION['id'];?>,<?php echo $batchId;?>)"></label>
                            <input id="batchListenStatus" value="<?php echo $listenOn;?>" type="checkbox" <?php echo $listenChecked; ?> style="display:none">
                        </td>
                    </table>
                </form>
             </div>
             
             <div id="delBtWrap" class="fr">
                <input style="width:120px" id="deleteBatch" type="button" title="Delete" value="" class="btn btn-medium btn-danger" onclick="">
             </div>
        </div><!--Tabs 3 wrap ends-->
        
  </div><!--#tabs ends-->
</div><!--//Batch  Main Wrapper ends-->

<script>
    
    var globalBatchSearch = null;
$( document ).ready(function() {
$("#batchtbl tbody tr:visible:even").addClass("even"); 
$("#batchtbl tbody tr:visible:odd").addClass("odd");
$('.currencyList').append(currencyList); 
 $("#batchExpiry").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate:0,
            dateFormat:"yy-mm-dd"
    });

if(globalBatchSearch != null)
    clearTimeout(globalBatchSearch);

globalBatchSearch  = setTimeout(function(){
    
    $('#searchUserName').keyup(function() {
 var batchId = $('#batchId').val();
 var searchData = $(this).val();
 $.ajax({
                   url : "action_layer.php?action=searchBulkClient",
                   type: "POST", 
                   data:{batchId:batchId,searchData:searchData},
                   dataType: "json",
                   success:function (text)
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
                            loginAs = ' <span title="Login As" class="ic-24 login loginAs" onclick="window.location.href=\'/controller/signUpController.php?call=loginAs&userId='+item.userId+'&url='+url.substring(1)+'\'" ></span>';
                          
                          str += '<tr class="">\
                                    <td>'+loginAs+item.userName+'</td>\
                                    <td>\
										<div class="hideThis">\
											<span>**********</span>\
											<a class="themeLink fr">Show</a>\
										</div>\
										<div class="showThis dn">\
											<span>'+item.password+'</span>\
											<a class="themeLink fr">Hide</a>\
										</div>\
									</td>\
                                    <td>'+item.balance+'</td>';
                            if(item.status == 1){
                             var checked='checked=checked';
                            }  else var checked='';
                          str += '<td align="center"><input type="checkbox" '+checked+' userid="'+item.userId+'" name="usedStatus" class ="usedStatus" onChange="changeStatus(this);" /></td>\
                        </tr>';
    } 
                      
                      });
                      
                      $( "#batchtbl tbody" ).html('');
                      $( "#batchtbl tbody" ).html(str);
                      $("#batchtbl tbody tr:visible:even").addClass("even"); 
                      $("#batchtbl tbody tr:visible:odd").addClass("odd");
                      hideShow();

                   }
    })
});
    
    
    
},800);


});
function changeStatus(ths){
    var userId = $(ths).attr('userid');
    var status = 0;
     if ($(ths).is(':checked')) {
        status = 1;                  
        } else {
        status = 0;
     } 
    $.ajax({
                   url : "action_layer.php?action=changeBulkClientStatus",
                   type: "POST", 
                   data:{userId:userId,status:status},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                   }
    })
}

function changeBatchRemainTimeStatus(userId,batchId){
     
        var status = $('#batchListenStatus').val();
        
        if(status == 1)
            $('#batchListenStatus').val('0');
        else
            $('#batchListenStatus').val('1');
  
    $.ajax({
                   url : "/controller/adminManageClientCnt.php?action=changeBulkClientListenTimeStatus",
                   type: "POST", 
                   data:{userId:userId,batchId:batchId,status:status},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
					   if(text.status == "success"){

                          if($('#listenRem').hasClass('disabledR'))
                          {
                               $('#listenRem').removeClass('disabledR');
                          }
                          else
                          {
                                $('#listenRem').addClass('disabledR');
                          }
                           //$('#listenRemainingMin').hide();
                       }
                   }
    })
}



function enableSip(batchId){
    $.ajax({
                   url : "action_layer.php?action=batchSipEnabel",
                   type: "POST", 
                   data:{batchId:batchId},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                           $('#blkSipEnb').hide();
                       }
                   }
    })
    
}

//tabs initialization
$(function() {
	$( "#tabs" ).tabs({
		create: function(event, ui) {},
		select: function(event, ui) {
		}
	});
});


function getbatchTransactionLog(batchId){
 $.ajax({
                   url : "action_layer.php?action=getBatchTransaction",
                   type: "POST", 
                   data:{batchId:batchId},
                   dataType: "json",
                   success:function (text)
                   {
                       console.log(text);
                       var str = designbatchTransaction(text);                       
                       $('#batchtransaction').html('');
                       $('#batchtransaction').html(str);
                       $("#batchTrstable tbody tr:visible:even").addClass("even"); 
                       $("#batchTrstable tbody tr:visible:odd").addClass("odd");
                   }
})
}

function designbatchTransaction(text){
var str = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl boxsize" id="batchTrstable">\
                <thead>\
                    <tr>\
                        <th width="15%">Date</th>\
                        <th width="10%">Type</th>\
                        <th width="10%" class="alR">Talktime</th>\
                        <th width="25%">Description</th>\
                        <th width="15%" class="alR">Debit</th>\
                        <th width="15%" class="alR">Credit</th>\
                        <th width="15%" class="alR">Closing Balance</th>\
                    </tr>\
                </thead>\
          		<tbody>';    
  
 $.each( text.detail, function(key, item ) {
  str += '<tr class="hvrParent">\
                    <td>'+item.date+'</td>\
                    <td>'+item.paymentType+'</td>\
                    <td class="alR">'+item.talktime+'</td>\
                    <td>'+item.description+'</td>\
                    <td class="alR">\
						<div class="debit fr">'+ item.debitActualCurrency+'</div>\
						<div class="fr hvrChild mrR">('+ item.debit +' '+item.currencyName +')</div>\
					</td>\
                    <td class="alR">\
						<div class="debit fr">'+item.creditActualCurrency+'</div>\
						<div class="fr hvrChild mrR">('+ item.credit +' '+item.currencyName +') </div>\
					</td>\
                    <td class="alRcloseBalance">'+parseFloat(item.closingBalance).toFixed(2)+'</td>\
                </tr>';
   
    })
    
    str+= '</tbody></table>';
     
     return str;
    


}


function addBatchAdditionTransaction()
{
    $('#batchAdditionalTrans').attr('disabled' , 'disabled');
    
 // status variable use for status of transaction add / reduce
 if($("input[type='radio'].addReduceRadio").is(':checked')) {
        var status = $("input[type='radio'].addReduceRadio:checked").val();
 }else
 {
      show_message("please select option either Received Money or Giving Money","error");
      $('#batchAdditionalTrans').removeAttr('disabled');
      return false;
 }
 
 // var transType use for transaction type (cash,bank,voip91,other).
 var transType = $('#transType').val();
 var description = $('#description').val();
 var amount = $('#transAmount').val();
 var batchId = $('#batchId').val();
 var currency = $('#currency').val();
 var transTypeOther = $('#transTypeOther').val();
 var reg=/^[a-zA-Z0-9\@\_\-\s]+$/;
 var reg2 = /^[0-9]+(\.[0-9]{1,4})?$/;
 
 //check transaction type validation 
 if(!reg.test(transType))
 {
      show_message("please enter valid transaction type! ","error");
      $('#batchAdditionalTrans').removeAttr('disabled');
      return false;
 }
 
 if(!reg.test(description))
 {
      show_message("please enter valid description !","error");
      $('#batchAdditionalTrans').removeAttr('disabled');
      return false;
 }
        
 if(!reg2.test(amount))
 {
      show_message("please enter valid amount! ","error");
      $('#batchAdditionalTrans').removeAttr('disabled');
      return false;
 }
 
 if(amount.length > 7) 		
 {
      show_message("please enter amount no more then 7 digits ! ","error");
      $('#batchAdditionalTrans').removeAttr('disabled');
      return false;
 }
 
 if(transTypeOther.length > 20)
 {
      show_message("please enter valid transaction type! ","error");
      $('#batchAdditionalTrans').removeAttr('disabled');
      return false;
 }
        $.ajax({
                url : "action_layer.php?action=addReduceBatchTransaction",
                type: "POST", 
                data:{status:status,transType:transType,description:description,amount:amount,batchId:batchId,transTypeOther:transTypeOther,currency:currency},
                dataType: "json",
                success:function (text)
                {
                    show_message(text.msg,text.status);
                    if(text.status == "success")
                    {

                            var str = designbatchTransaction(text.str);                       
                            $('#batchtransaction').html('');
                            $('#batchtransaction').html(str);
                            $("#batchTrstable tbody tr:visible:even").addClass("even"); 
                            $("#batchTrstable tbody tr:visible:odd").addClass("odd");
                            $('#transType').val('');
                            $('#description').val('');
                            $('#transAmount').val('');
                            $("#transotherType").hide();
                    }
                        $('#batchAdditionalTrans').removeAttr('disabled');
                }
            })
                
             
}


$(document).ready(function() { 
  
  var options = { 
                     
                        url:"action_layer.php?action=editBatchDetail", 
                        type:'POST',        
			dataType: 'json',
			beforeSubmit:  showBatchRequest,  // pre-submit callback 
			success:     
                                function(text)
                                {                                    
                                 show_message(text.msg,text.status);
                                 if(text.status == "success"){
                                    var str = createBulkDesign(text.batchDetail);
                                    $("#bulkclientList").html('');
                                    $("#bulkclientList").html(str);   
                                }
                           }
		};
		$('#editBatchDetailform').ajaxForm(options); 
	}); 
        
 $().ready(function() {
     
      $.validator.addMethod("nameRegex", function(value, element) {
                return this.optional(element) || /^[A-Za-z][a-z0-9]+$/i.test(value);
            }, "field must contain only letters and numbers.");
            
        // validate the comment form when it is submitted 
        $("#editBatchDetailform").validate({
                rules: {
                        batchName :{
                            required: true,
                            maxlength: 30,
                            nameRegex:true
                        },
                        batchExpiry :{
                            required: true,
                            maxlength: 30
                        }
                        
                       }
        });

 });
 function showBatchRequest(formData, jqForm, options){
     
            $("#loading").show();
            if($("#editBatchDetailform").valid())
                    return true; 
            else
                    return false;
}
</script>
<script type="text/javascript">
$(document).ready(function()
{
			$('.back').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "-1000px"}, "slow");
						$('.slideLeft').fadeIn(2000);
				}
			});
	});
        

var strt = <?php echo $pageNo; ?>;
    var totalCount = <?php if(isset($batchData['totalCount']) && (is_numeric($batchData['totalCount'])) && $batchData['totalCount'] != 0) echo $batchData['totalCount']; else echo 1; ?>;

//console.log(totalCount);
    if(strt == undefined || strt == null || strt == '' )
        strt = 1;

    if(totalCount == undefined || totalCount == null || totalCount == '' )
        totalCount = 1;
batchPagination(totalCount,<?php echo $pageNo; ?>,'#pagination',<?php echo $batchId; ?>,1);

</script>

<script>
//maintain tab content height
var _N = $('.ui-tabs-nav').outerHeight(true)

_H = $('#rightsec').height();
$('.tabs').css({height: _H - _N -60, 'overflow':'auto'});

//initialise tiptip for help icon
$(".helpW, .help, .loginAs").tipTip();

//passwords show hide function
function hideShow()
{
  $('#showAllP').click(function(){
  $('.hideThis, #showAllP').hide()
  $('.showThis, #hideAllP').show()
  });
  
$('#hideAllP').click(function(){
  $('.hideThis, #showAllP').show()
  $('.showThis, #hideAllP').hide()
  });
$('.showThis, .hideThis').click(function(){
  $(this).hide()
  $(this).siblings().show()
  })

}

hideShow();

/**
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
 * @since 17/05/2014
 * @param {type} batchId
 * @returns {undefined}
 */
function deleteBatchStatus(batchId)
{
     $.ajax({
                url : "/controller/batchController.php?action=getBatchDeleteFlag",
                type: "POST", 
                data:{batchId:batchId},
                dataType: "json",
                success:function (text)
                {
                    
                    var blockType;
                    if(text.deleteFlag > 0)
                    {
                        blockType = 1;
                        $('#deleteBatch').val('Retrive Batch');
                       	$('#deleteBatch').removeClass('btn-danger').addClass('btn-primary');
                        //code to make it retrive button
                    }
                    else
                    {
                        blockType = 0;
                        $('#deleteBatch').val('Delete Batch');		
                       	$('#deleteBatch').removeClass('btn-primary').addClass('btn-danger');
                       //code to make it delete button
                       
                    }
                    $("#deleteBatch").attr("onclick","setBatchDeleteFlag("+batchId+",'"+blockType+"')");
                    console.log(text);
                   // show_message(text.msg,text.status);
                    //if(text.status == "success")
                    {

//                            var str = designbatchTransaction(text.str);                       
//                            $('#batchtransaction').html('');
//                            $('#batchtransaction').html(str);
//                            $("#batchTrstable tbody tr:visible:even").addClass("even"); 
//                            $("#batchTrstable tbody tr:visible:odd").addClass("odd");
//                            $('#transType').val('');
//                            $('#description').val('');
//                            $('#transAmount').val('');
//                            $("#transotherType").hide();
                    }
                        //$('#batchAdditionalTrans').removeAttr('disabled');
                }
            })
    
}


</script>
