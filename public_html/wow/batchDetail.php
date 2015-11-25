<?php 
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 04-sep-2013
 * @package Phone91
 * @details reseller manage clients - batchname  page 
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


#include reseller_class.php file 
include_once CLASS_DIR.'reseller_class.php';
#create object of reseller_class
$resellerObj = new reseller_class();
#get batchid from request 
$batchId = $_REQUEST['batchId'];

$batchUserData =  $resellerObj->getBatchDetail($batchId,$pageNo,1);
$batchData = json_decode($batchUserData, true);    

if(!isset($batchData['batchName'])){
    echo "you have no permission for show Batch detail..";
    die();
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


<div id="tabs" class="traffiBttn mrT2">
  <ul>
        <li class="none"><a href="#tabs-1" title="" ><span>Batch Detail</span></a></li>
        <li class="none"><a href="#tabs-2" title="" ><span>Edit Batch</span></a></li>
  </ul>
    <div id="tabs-1" class="pd">
    
<a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<!--Batch  Main Wrapper-->
<div id="batchWrap">
	<!--Inner Wrapper-->
   	<div class="inner">
          <div class="clear srchrowSec">
              <input type="text" placeholder="Search" id="searchUserName" onkeyup=" getBatchDetail(this)"/>
            <input type="hidden" id="batchId" value="<?php echo $batchId; ?>"/>
            <?php if($batchData['sipStatus'] != 1){?>
            <a style="color:rgb(14, 14, 70);font-size:14px" id="blkSipEnb" onclick="enableSip(<?php echo $batchId; ?>);">Click here to Enable Sip </a>
            <?php }?>
            <div class="sett pr">
      <!--             <div class="cp" onclick="uiDrop(this,'#showDwSett', 'true')"> <i class="ic-24 setting"></i> <i class="ic-16 dropsign"></i> </div>
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
                  </select>-->
            </div>
          </div>
          <h3 class="h3wico"><span class="fl"><?php echo $batchData['batchName'];?></span><!-- <i class="ic-24 edit cp"></i> --></h3>
          <p class="batchInfo mrB2  mrT"> 
          		<span>Created on <?php echo $batchData['createDate']; ?></span> 
                <span>Expiry <?php echo date('Y-m-d',strtotime($batchData['expiryDate'])); ?></span>
         </p>
         
         <a target="_blank" href="/controller/adminManageClientCnt.php?batchId=<?php echo $_REQUEST['batchId']; ?>&type=csv&action=exportBatchUser" ><input type="button" value="Export Csv"/></a>
         <a target="_blank" href="/controller/adminManageClientCnt.php?batchId=<?php echo $_REQUEST['batchId']; ?>&type=xlsx&action=exportBatchUser" ><input type="button" value="Export xls"/></a>
         
         
         <div class="flip-scroll">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" id="batchtbl" class="cmntbl boxsize">
                <thead>
                      <tr>
                            <th>Login as</th>
                            <th>User</th>
                            <th>Password</th>
                            <th>Balance</th>
                            <th>Status(Used/Unused)</th>
                            <th>Enable/Disable</th>
                      </tr>
                </thead>
                <tbody>
					  <?php foreach($batchData['userDetail'] as $userDetail) {?>
                      <tr class="">
                        <td><span title="Login As" class="ic-24 login loginAs" onclick="redirectLoginUrl('<?php echo $userDetail['userId']; ?>');" style="width: 58px;color: turquoise;" >Login as</span></td>
                        <td><?php echo $userDetail['userName']; ?></td>
                        <td><?php echo $userDetail['password']; ?></td>
                        <td><?php echo round($userDetail['balance'],3); ?> </td>
                        <?php if($userDetail['status']==1){
                                        $checked='checked=checked';
                                    }  else $checked='';?>
                        <td align="center">
                        	<input type="checkbox" <?php echo $checked;?> userid="<?php echo $userDetail['userId']; ?>" name="usedStatus" class ="usedStatus" onChange="changeStatus(this);" />
                         </td>
                         <td align="center">
                        	<span class="funder">
                                    <?php   if($userDetail["blockUnblockStatus"] != 1){
                                                               $statusClass ="redDisabl";
                                                               $Bstatus = "block";
                                                            }else{
                                                                $statusClass ="";
                                                                $Bstatus = "unBlock";
                                                            }
                                                            ?>
                                    <label onclick="changeUserStatus(this,<?php echo $userDetail['userId'];?> );" for="chnage" class="ic-32 grnEnabl cp <?php echo $statusClass; ?> ">
                                    </label>
                                   <input type="checkbox" id="chnage<?php echo $userDetail['userId'];?>" style="display:none" checked="checked"  value ="<?php echo $Bstatus; ?>" />
                                </span>
                         </td>
                      </tr>
                      <?php } ?>
                </tbody>
          </table>
             <div id="batchPagination"></div>
          <div class="mrT2">
              
<!--           		 <h3>pagination come here</h3>-->
          </div>
      </div>
  <!--Inner Wrapper-->
</div>
<!--//Batch  Main Wrapper-->
</div>
    </div>
    <div id="tabs-2" class="mrB1 pr">
        <div style="padding: 10px;" >
            <form method="post" action=""  name="batchEdit" id="batchEdit">
                <div style="padding: 5px;">
                    <input type="text" name="batchName" id="batchName" value="<?php echo $batchData['batchName'];?>" >
                    <input type="hidden" name="batchId" id="batchId" value="<?php echo $batchId; ?>" />
                </div>

                <div style="padding: 5px;">
                    <input type="text" name="batchExpiry" id="batchExpiry" value="<?php echo date("Y-m-d",strtotime($batchData['expiryDate']));?>"/>
                </div>

                <div class="">
                    
                    <?php 
                    $checked = "";  
                    if($batchData['listenTime'])
                    {
                        $checked ="checked='checked'";
                    }
                    
                    ?>
                    
                    <input type="checkBox" name="listenTime" id="listenTime" <?php echo $checked; ?>  /> Listen Time
                </div>
                <BR>
                <div>
                    <input type="button" name="editBatchBtn" value="SAVE" onclick="submitform()" /> 
                </div>
          </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    
    function submitform()
    {
        $.ajax({
                   url : "../controller/adminController.php?action=updateBatchUser",
                   type: "POST", 
                   data:$("#batchEdit").serialize(),
                   dataType: "json",
                   success:function (text)
                   {
                       console.log(text);
                       show_message(text.msg,text.status);
                   }
    })
    }
    
    
    
 $(function() {
     
     
        $( "#batchExpiry" ).datepicker({
     changeMonth: true,
     changeYear: true
      
});
   
        $.datepicker.setDefaults({changeMonth: true,
            changeYear: true});
     
     
     
	$( "#tabs" ).tabs({
		create: function(event, ui) {},
		select: function(event, ui) {}
	});
        
// currencyList is global variable initialize in panel.js    
//$('.currencyList').append(currencyList);         
});
    
    
 var globalBatchSearch = null;    
    
$( document ).ready(function() {
$("#batchtbl tbody tr:visible:even").addClass("even"); 
$("#batchtbl tbody tr:visible:odd").addClass("odd");
$('.loginAs').css('cursor','pointer');


if(globalBatchSearch != null)
   clearTimeout(globalBatchSearch);

globalBatchSearch  = setTimeout(function(){
   
   $('#searchUserName').keyup(function() {
           getBatchDetail();
});
  
   
},800);




});

function getBatchDetail(ths)
{
     var batchId = $('#batchId').val();
 var searchData = $(ths).val();
 
 
 console.log(searchData);
 $.ajax({
                   url : "/action_layer.php?action=searchBulkClient",
                   type: "POST", 
                   data:{batchId:batchId,searchData:searchData},
                   dataType: "json",
                   success:function (text)
                   {
                       var str ='',loginAs='';
                       var url = window.location.hash;
                       var checked='';
                      $.each( text.detail, function(key, item ) {
                          
        //console.log(item);
                            if(item.userName != undefined)
                            {  
                                    if(item.deleteFlag > 0)
                                       loginAs='<span title="Login As" class="login loginAs" onclick=" javascript:void(0);" >Deleted</span>';
                                   else if(item.blockUnblockStatus > 1)
                                       loginAs = '<span title="Login As" class="login loginAs" onclick=" javascript:void(0);" >Blocked</span>    ';
                                   else
                                       loginAs = ' <span style="width: 58px; color: rgb(64, 224, 208); cursor: pointer;" title="Login As" class="ic-24 login loginAs" onclick="redirectLoginUrl('+item.userId+')" >Login as</span>';  


                                     str += '<tr class="">\
                                               <td>'+loginAs+'</i></td>\
                                               <td>'+item.userName+'</td>\
                                               <td>'+item.password+'</td>\
                                               <td>'+item.balance+'</td>';
                                       if(item.status == 1)
                                            checked='checked=checked';
                                        
                                     str += '<td align="center"><input type="checkbox" '+checked+' userid="'+item.userId+'" name="usedStatus" class ="usedStatus" onChange="changeStatus(this);" /></td>';
    
                                   console.log(item);
    
                                   if(item.blockUnblockStatus != 1)
                                   {
                                       var statusClass = "redDisabl";
                                       var bStatus = "block";
                                       
                                   }
                                   else
                                   {
                                       var statusClass = "";
                                       var bStatus = "unBlock";
                                   }
                                    // console.log(str);
                                  
                                  str +='<td align="center"><span class="funder"><label onclick="changeUserStatus(this,'+item.userId+');" for="chnage" class="ic-32 grnEnabl cp '+statusClass+' "></label>\
                                   <input type="checkbox" id="chnage'+item.userId+'" style="display:none" checked="checked"  value ="'+bStatus+'" />\
                                </span></td></tr>';

                            }
                      
                      });
                      
                      $( "#batchtbl tbody" ).html('');
                      $( "#batchtbl tbody" ).html(str);
                      $("#batchtbl tbody tr:visible:even").addClass("even"); 
                      $("#batchtbl tbody tr:visible:odd").addClass("odd");
                        if(text.pages < 2)
                        $('#batchPagination').hide();
                      else
                        $('#batchPagination').show();
                      clientPagination(text.pages,1,'#batchPagination',batchId,1);
                    
                      
                      }
                      
                      
                   
    });
}



function changeStatus(ths){
    var userId = $(ths).attr('userid');
    var status = 0;
     if ($(ths).is(':checked')) {
        status = 1;                  
        } else {
        status = 0;
     } 
    $.ajax({
                   url : "/action_layer.php?action=changeBulkClientStatus",
                   type: "POST", 
                   data:{userId:userId,status:status},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                   }
    })
}

function enableSip(batchId){
    $.ajax({
                   url : "/action_layer.php?action=batchSipEnabel",
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

    if(strt == undefined || strt == null || strt == '' )
        strt = 1;

    if(totalCount == undefined || totalCount == null || totalCount == '' )
        totalCount = 1;
clientPagination(totalCount,<?php echo $pageNo; ?>,'#batchPagination',<?php echo $batchId; ?>,1);
 
<?php 

if(isset($_REQUEST['token']) && !empty($_REQUEST['token']))
{ ?>
    
    //console.log("EnterToken");
    
   // $('#searchUserName').val('<?php echo base64_decode($_REQUEST['token']); ?>').trigger('keyup');
    
    $(document).ready(function()
    { 
        $('#searchUserName').val('<?php echo base64_decode($_REQUEST['token']); ?>');
        getBatchDetail($('#searchUserName'));
    });
    
<?php }

?>
</script>
