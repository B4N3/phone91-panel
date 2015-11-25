<?php 
 /**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 08-aug-2013
 * @package Phone91
 * @details reseller manage pin page for show detail of batch pin and edit delete batch  
 */
#include common config file  
include dirname(dirname(__FILE__)) . '/config.php';
#get batch id form request 
$batchid = $_REQUEST['batchid'];
#include pin class
include_once CLASS_DIR.'pin_class.php';
$userid = $_SESSION['userid'];
#object of pin class
$pinobj = new pin_class();


//get page number
if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;


#function call for get batch details (total no of pin detail of selected batch) 
$batchPinjson = $pinobj->getPinDetails($batchid,$userid,$pageNo,0,1);
$batchPinData = json_decode($batchPinjson, true);   

foreach($batchPinData['batch_details'] as $batchDetails){ 
   
?>
<!--Manage Pins-->
<div id="manage-pin-wrap" class="clear">
    <!--Left Manage Pins-->
    <div id="leftwrap">
    	<!--Search Rows-->
        <div class="clear srchrowSec">
                    <h3 class="h3wico clear">
                             <span class="fl"><span class="hideInTablet">Manage PINs : </span> <?php echo $batchDetails['batch_name']?></span> 
          		  </h3>
                <div class="sett pr">
                    <a class="btn btn-medium btn-primary clear alC" target="_blank" href="/action_layer.php?action=exportPinList&batchId=<?php echo $batchid; ?>" title="Export CSV">Export CSV</a>
<!--                    <div class="cp" onclick="uiDrop(this,'#showDwSett', 'true')">
                                <i class="ic-24 setting"></i>
                                <i class="ic-16 dropsign"></i>
                        </div>
                       <ul class="dropmenu boxsize ln" id="showDwSett">
                                <li>Export CSV</li>
                                <li>Export PDF</li>
                                <li>Export XlS</li>
                        </ul>-->
<!--                        <select name="" style="width:60px; float:right;">
                                <option>100</option>
                                <option>150</option>
                                <option>200</option>
                                <option>250</option>
                        </select>-->
                </div>
        </div>
        <!--//Search Rows-->
        <p class="batchInfo mrB2 clear">
                <span>Created on <?php echo $batchDetails['created_date']; ?></span> |
                <span>Expiry <?php echo date('Y-m-d',strtotime($batchDetails['expire_date'])); ?></span>
        </p>
        <div class="flip-scroll ">
    		    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="batchtbl" class="cmntbl boxsize">
            <thead>
                <tr>
                    <th>Pin Code</th>
                    <th>Used Date</th>
                    <th>Used By</th>
                </tr>
            </thead>
            <tbody>
                <?php $noOfUsedPin = 0;
                    foreach($batchPinData['myPins'] as $pinDetails){ 
                    if($pinDetails["status"] == "Used"){
                        $noOfUsedPin = $noOfUsedPin + 1;
                    }
                    ?>
                
                <tr class="">
                    <td><?php echo $pinDetails['pin_code']; ?></td>
                    <td><?php echo $pinDetails['used_date'];?></td>
                    <td><?php echo $pinDetails['userBy'];?></td>
                </tr>
                <?php } ?>

            </tbody>
        </table>
       	</div>
        <div class="mrT2">
            <input type="hidden" id="pageNo" value="<?php echo $pageNo;?>">
            <input type="hidden" id="totalPage" value="<?php echo $batchPinData['totalCount'];?>">
            <input type="hidden" id="batchId" value="<?php echo $batchid;?>">
			<!--<h3>pagination come here</h3>-->
                         <div id="pagination"></div>
        </div>
    </div>
	<!--//Left Manage Pins-->

	<!--Right Manage Pins-->
	<div id="rightwrap">
         <form id="editBatchform" class="formElemt">   
    			<h3 class="h3wico mrB2"><span>Edit Batch</span></h3>
                <div class="fields">
                    <label>Batch Name</label>
                    <input type="text" name="batchName" id="batchname" value="<?php echo htmlentities($batchDetails['batch_name']);?>" />
                    <input type ="hidden" name="oldBatchName" id="oldBatchName" value="<?php echo htmlentities($batchDetails['batch_name']);?>"/>  
                    <input type="hidden" name="batchid" value="<?php echo $batchDetails['id']; ?>">
                </div>
				<?php if($noOfUsedPin == 0) {?>
                <div class="fields">
                    <label>Tariff Plan</label>
                    <select name="tariffPlan" id="tariff">
                        <?php
                         include_once(CLASS_DIR."plan_class.php");
                        $planObj = new plan_class();
                        $result = $planObj->getPlanName("planName,tariffId,outputCurrency",$batchDetails['userId'],2,NULL);
                        $planDetail = json_decode($result,TRUE);   
                                    foreach($planDetail as $key){ 
                                        $selected = '';
                                        if($key['tariffId'] == $batchDetails['tariffId']){
                                                $oldCurrency = $key['tariffId'];
                                                 $selected = 'selected = "selected"';
                                            }
                                       echo '<option value="'.$key['tariffId'].'"  currency ="'.$key['planName'].'" '.$selected.'>'.$key['planName'].'</option>';
                                    } 
                        
  
                        
                        ?>
                    </select>
                    <input type ="hidden" name="oldTariffPlan" id="oldTariffPlan" value="<?php echo $oldCurrency;?>"/>
            </div>
       		<?php }?>
                        
                   
        	<div class="fields">
            	<label>Batch Expiry</label>
           		 <input type="text" id="batchExpiry"  name="batchExpiry" value="<?php echo $batchDetails['expire_date']; ?>" />
        	 <input type ="hidden" name="oldBatchExpiry" id="oldBatchExpiry" value="<?php echo $batchDetails['expire_date']; ?>"/>
                </div>
      	  <?php if($noOfUsedPin == 0) {?>
        	<div class="fields">
            	<label>Amount per PIN</label>
           		 <input type="text" name="amountperpin" id="amount" value="<?php echo $batchDetails['amountPerPin']; ?>" />
                         <input type ="hidden" name="oldamountperpin" id="oldamountperpin" value="<?php echo $batchDetails['amountPerPin']; ?>"/>
                </div>
        <?php } ?>
        </form>
        <!--<p>Batch for</p>
        <div id="BatchType" class="clear btnlbl">
        <input type="radio" id="me" name="bType" /><label for="me">Me</label>
        <input type="radio" id="res" name="bType" checked="checked" /><label for="res">My Reseller</label>
        </div>
        <div id="resWrap" class="dn">
            <p>Reseller Name</p>
            <div class="">
                <input type="text" />
            </div>
        </div>-->
        <a class="btn btn-medium btn-primary clear alC" onclick="editBatchPin(this);" title="Update">Update</a>
      <?php if($noOfUsedPin == 0) {?>
        <a  class="btn btn-medium btn-danger clear alC"  onclick="deleteBatchPin(this);" batchid="<?php echo $batchDetails['id'];?>" title="Delete">Delete </a>      <?php } }?>       
    </div>
	<!--//Right Manage Pins-->
</div>
<!--//Manage Pins-->
<script type="text/javascript">
$(function() {
	$( "#BatchType" ).buttonset();
         $("#batchExpiry").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate:0,
            dateFormat:"yy-mm-dd"
    });
});
$( document ).ready(function() {
$("#batchtbl tbody tr:visible:even").addClass("even"); 
$("#batchtbl tbody tr:visible:odd").addClass("odd");
});
function showNext(){
	//$( "#partialWrap" ).show();
}
function editBatchPin(ths){
    var batchData = $('#editBatchform').serialize();
    
    //check name valide or not 
    var reg1=/^[a-zA-Z_@-]{2,20}$/;
    var batchname = $('#batchname').val();
    if(!reg1.test(batchname)){
       return show_message("Please enter valide batch name","error");
    }
    if($('#batchExpiry').val() == ''){
        return show_message("Please select expiry date ","error");
    }
    
    $.ajax({
	    url : "/action_layer.php?action=editPinBatch",
	    type: "POST",dataType: "json",
	    data: batchData ,
            success:function (text){
                show_message(text.msg,text.msgtype);
                if(text.msgtype == "success"){
                    // function in reseller-manage-pins.php script function 
                    var str = batchlistDesign(text);
                    $('#leftsec ul').html('');
                    $('#leftsec ul').html(str);
//                    $('#oldBatchName').val($('#batchname').val());
//                    $('#oldTariffPlan').val($('#tariff').val());
//                    $('#oldBatchExpiry').val($('#batchExpiry').val());
//                    $('#oldamountperpin').val($('#amount').val());
                }
            }
    });
//    }
}
//created by Balachandra 
//date 26/07/2013
function deleteBatchPin(ths)
{
    //get the value of the batchid from the php code
    var batchid = $(ths).attr('batchid');
    
    //using jquery,ajax post the data to action layer
    $.ajax({
	    url : "/action_layer.php?action=deleteBatchPin",
	    type: "POST", 
            data:{batchid:batchid},
            //returning data in json format
            dataType: "json",
	    
            success:function (text)
            {
              //success or error message returns back here  
              show_message(text.msg,text.msgtype);
              
              //if success then automatically remove th4e batch without relaoding the page
              if(text.msgtype == "success"){
                // function in reseller-manage-pins.php script function 
                var str = batchlistDesign(text);
                $('#leftsec ul').html('');
                $('#leftsec ul').html(str);    
                    
                //in the input of type button,submit etc are disabled from action
                $(':input','#editBatchform')
                    .not(':button, :submit, :reset, :hidden')
                    .val('')
                    .removeAttr('checked')
                    .removeAttr('selected');
                }
            }
    });
}

function exportPinList(batchId){
$.ajax({
	    url : "/action_layer.php?action=exportPinList",
	    type: "POST", 
            data:{batchId:batchId},
            dataType: "json",
	    success:function (text)
            {
              //success or error message returns back here  
              show_message(text.msg,text.msgtype);
            }
        })
}
</script>