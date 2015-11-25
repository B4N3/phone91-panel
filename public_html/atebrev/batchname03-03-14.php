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
    echo "you have no permission for show pin detail..";
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
<a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<!--Batch  Main Wrapper-->
<div id="batchWrap">
	<!--Inner Wrapper-->
   	<div class="inner">
          <div class="clear srchrowSec">
            <input type="text" placeholder="Search" id="searchUserName"/>
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
         <div class="flip-scroll">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" id="batchtbl" class="cmntbl boxsize">
                <thead>
                      <tr>
                            <th>Login as</th>
                            <th>User</th>
                            <th>Password</th>
                            <th>Balance</th>
                            <th>Status(Used/Unused)</th>
                      </tr>
                </thead>
                <tbody>
					  <?php foreach($batchData['userDetail'] as $userDetail) {?>
                      <tr class="">
                        <td><i class="ic-24 login"></i></td>
                        <td><?php echo $userDetail['userName']; ?></td>
                        <td><?php echo $userDetail['password']; ?></td>
                        <td><?php echo round($userDetail['balance'],3); ?> <?php echo $userDetail['currecyName'];?></td>
                        <?php if($userDetail['status']==1){
                                        $checked='checked=checked';
                                    }  else $checked='';?>
                        <td align="center">
                        	<input type="checkbox" <?php echo $checked;?> userid="<?php echo $userDetail['userId']; ?>" name="usedStatus" class ="usedStatus" onChange="changeStatus(this);" />
                         </td>
                      </tr>
                      <?php } ?>
                </tbody>
          </table>
          <div class="mrT2">
              <div id="pagination"></div>
<!--           		 <h3>pagination come here</h3>-->
          </div>
      </div>
  <!--Inner Wrapper-->
</div>
<!--//Batch  Main Wrapper-->
<script>
$( document ).ready(function() {
$("#batchtbl tbody tr:visible:even").addClass("even"); 
$("#batchtbl tbody tr:visible:odd").addClass("odd");
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
                       var str ='';
                       
                      $.each( text, function(key, item ) {
                          str += '<tr class="">\
                                    <td><i class="ic-24 login"></i></td>\
                                    <td>'+item.userName+'</td>\
                                    <td>'+item.password+'</td>\
                                    <td>'+item.balance+'USD</td>';
                            if(item.status == 1){
                             var checked='checked=checked';
                            }  else var checked='';
                          str += '<td align="center"><input type="checkbox" '+checked+' userid="'+item.userId+'" name="usedStatus" class ="usedStatus" onChange="changeStatus(this);" /></td>\
                        </tr>';
                      })
                      
                      $( "#batchtbl tbody" ).html('');
                      $( "#batchtbl tbody" ).html(str);
                      $("#batchtbl tbody tr:visible:even").addClass("even"); 
                      $("#batchtbl tbody tr:visible:odd").addClass("odd");
                   }
    })
});
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
        
        
//code for pagination
        $(function() {
                    $('#pagination').paginate({
                        count       : <?php if(isset($batchData['totalCount']) && (is_numeric($batchData['totalCount'])) && $batchData['totalCount'] != 0) echo $batchData['totalCount']; else echo 1; ?>,
                        start       : <?php echo $pageNo; ?>,
                        display     : 10,
                        border : true,
                        text_color: '#000',
                        background_color: '#ddd',
                        text_hover_color: '#fff',
                        background_hover_color: '#333',
                        images                  : false,
                        mouse                   : 'press',
                        page_choice_display     : true,
                        show_first              :true,
                        show_last               :true,
                        onChange                : function(page){
                         console.log(page);                           
                         window.location.href= window.location.href.split('?')[0]+'?&pageNo='+page+'&batchId=<?php echo $batchId; ?>';
        

                                                  }
                        });
            });        
</script>
