<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  23 july 2013
 * @package Phone91
 * 
 */

include_once('config.php');
include_once(CLASS_DIR."transaction_class.php");
include_once(CLASS_DIR."reseller_class.php");
include_once(CLASS_DIR.'contact_class.php');


#from user id 
$fromUser = $_SESSION['userid'];

#touser id
$clientId = $_REQUEST['clientId'];

if($funobj->getResellerId($_REQUEST['clientId']) != $fromUser)
{
    $funobj->redirect('userhome.php#!reseller-manage-clients.php|reseller-addnew-client.php');
}

//get page number
if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;

#object of transaction class 
$transObj = new transaction_class();

#call function getTransactionlogDetial for get all detion of transation 
$transaction = $transObj->getTransactionLogDetail($fromUser, $clientId,$pageNo);
$transData = json_decode($transaction,TRUE);

#get current balance for show in EditFund 
$UserBalance = $transObj->getcurrentbalance($clientId);

#- Code For Getting User Details
$resObj= new reseller_class();
$userInfo=$resObj->loadUserDetails($clientId,'*',$_SESSION['id']); 

$userCurrency = $transObj->getCurrencyName($userInfo['currencyId']);
#- Code For getting Movile Number

$contactObj= new contact_class();
$vContactArr=$contactObj->getConfirmMobile($clientId);


#- If Confirm Mobile Number Is Not Available Then We Find Unconfirm Mobile nUmber.
if($vContactArr[0] == 0)
{
    $unverifiedContact = $contactObj->getUnconfirmMobile($clientId);                 
}  

#finding verified Email Id
$vContactEmailArr = $contactObj->getConfirmEmail($clientId);

#- If Varified EmailId Is Not Available Then We find Unvarified Email Id.
if($vContactEmailArr[0] == 0)
{
    $unverifiedEmail = $contactObj->getUnConfirmEmail($clientId);
}

//get listenRemainvalue status
$listenStatus =  $resObj->getRemainingMinutesStatus($clientId,'userId','getMinuteVoice');

$checked = '';
if($listenStatus)
    $checked = 'checked';


?>
<!--Tabs Content-->
<div class="resellerMCHead"><?php echo $userInfo['userName'];?> <!--<span>Currency</span> <span>USD</span>--></div>
<div id="tabs">
    <ul>
            <li onclick ="getTransactionLog(<?php echo $fromUser;?>,<?php echo $clientId;?>)"><a href="#tabs-1"><span class="hideInTablet">Transaction</span> Log</a></li>
            <li><a href="#tabs-2"><span class="hideInTablet">Edit</span> Fund</a></li>
            <li><a href="#tabs-3"><span class="hideInTablet">Edit</span> Info</a></li>
            <li><a href="#tabs-4"><span class="hideInTablet">Change</span> Password</a></li>
    </ul>
    
	<!--1 st Tab Content-->
    <div id="tabs-1" class="tabs">
    	<div id="transactionTable" class="tablflip-scroll">
      		  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="clientTrstable" class="cmntbl alR boxsize">
        	<thead>
                <tr>
                    <th width="15%">Date</th>
                    <th width="8%">Type</th>
                    <th  class="alR" width="5%">Amount</th>
                    <th class="alR" width="5%">Balance</th>
                    <th width="30%">Description</th>
                    <th class="alR" width="10%">Debit</th>
                    <th  class="alR" width="10%">Credit</th>
                    <th  width="13%">Closing Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalCredit=0;$totalDebit=0;$totalClosingBalance=0;
                foreach($transData['detail'] as $trans) { ?>
                <tr class="hvrParent">
                    <td><?php echo $trans['date']; ?></td>
                    <td><div class="fl"><?php echo htmlentities($trans['paymentType']); ?></div> </td>
                    <td  class="alR"><div class="fl"><?php if($trans['amount'] != 0) echo $trans['amount']; else echo "-"; ?></div></td>
                    <td class="alR"><div class="fl"><?php echo round($trans['currentBalance'],2); ?></div> </td>
                    <td><div class="fl"><?php echo htmlentities($trans['description']); ?></div> </td>
                    <td class="alR"><div class="debit fr" > <?php echo $trans['debitActualCurrency']; ?></div><div class="debit dn hvrChild fr mrR">(<?php echo $trans['debit'] . " " . $trans['currencyName']; ?>)</div></td>
                    <td class="alR"><div class="credit fr"><?php echo $trans['creditActualCurrency']; ?></div><div class="credit dn hvrChild fr mrR">(<?php echo $trans['credit'] . " " . $trans['currencyName'];?>)</div></td>
                    <td class="alR closeBalance"><div class="fl"><?php echo round($trans['closingBalance'],2); ?></div></td>
                </tr>
                <?php
						$totalCredit = $totalCredit + $trans['creditActualCurrency'];
						$totalDebit = $totalDebit + $trans['debitActualCurrency'];
						$totalClosingBalance = $trans['closingBalance'];
                } ?>
                <tr class="zerobal">
                    <td colspan="100%"></td>
                </tr>
                <tr class="">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td class="alR">Total</td>
                    <td class="alR"><span class="debit"><?php echo $totalDebit ;?></span></td>
                    <td class="alR"><?php echo $totalCredit; ?></td>
                    <td class="alR closeBalance"><?php echo $totalClosingBalance; ?></td>
                </tr>
            </tbody>
        </table>
        </div>
        <div id="pagination" class="mrT1"></div>
       <!-- Bootm Actions Wrapper-->
        <div class="clear mrT1">
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
                  <input type="hidden" name="toUser" value="<?php echo $clientId; ?>" id="toUser"/>                
            </div>
			
			<div class="dn actionDiv" id="transotherType">
				<p class="mrB">Enter Type</p>
				<div class="fields">
				<input type="text" name="transTypeOther"  id="transTypeOther" />
				</div>
			</div>
            
            <div class="actionDiv">
            	<p class="mrB">Description</p>
                <div class="fields">
                    <input type="text" name="description" id ="description"/>
                </div>
            </div>
            
            <div class="actionDiv mDevice">
            	<p class="mrB">Add/Reduce</p>
                <div id="sporow" class="clear fields">
                	<span class="funder">
                        <label onclick="toggleState($(this),'Trans');" for="changefunder" class="ic-32 bigfadder cp"></label>
                        <input type="checkbox" id="changefunderTrans" style="display:none" checked="checked" value="add" />
                    </span>
                    <input type="text" name="transAmount" id="transAmount" placeholder="Amount"/>
                    <select name="currency" id="currency" class="currencyList">
                         <!--  do not remove currencylist class -->
                    </select>                    
                </div>				
            </div>
			<div class="actionDiv">
				<p class="mrB additionalTransLbl">&nbsp;</p>
				<div class="fields">
				<input type="button" class="btn btn-medium btn-primary" name="additionalTrans" id="additionalTrans" value=" ADD " onclick="addAdditionTransaction();"/>
				</div>
			</div>
            
        </div>
    	<!-- //Bootm Actions Wrapper-->
    </div>
    
    <div id="tabs-2" class="tabs">
        <form id="editFundform" class="formElemt">
                
                <div class="innerSpace">
                        <p class="f12">Current available balance</p>
                        <h3 class="mrB2 userBalance"><span class="<?php echo $clientId;?>changeBal"><?php echo round($UserBalance,3); ?> </span> <?php echo "  ".$currency = $funobj->getCurrencyViaApc($userInfo['currencyId'],1); ?></h3>
                        <div id="sporow" class="clear"> 
                                <div class="fields">
                                        <label>Amount currency</label>
                                        <select name="fundCurrency" id="fundCurrency" class="currencyList">
                                              <!--  do not remove currencylist class -->                                                   
                                        </select>
                                 </div>  
                                   <div class="fields">   
                                         <label>Add/Reduce Fund</label>
                                        <span class="funder">
                                                <label onclick="toggleState($(this),'EditFund');" for="changefunder" class="ic-32 bigfadder cp"></label>
                                                 <input type="checkbox" id="changefunderEditFund" name="changefunderEditFund" style="display:none" checked="checked" value="add" />
                                        </span>
                                      <input type="hidden" name="toUserEditFund" value="<?php echo $clientId; ?>" id="toUserEditFund"/>
                                      <input type="text" placeholder="Amount" id="fundAmount" name="fundAmount"/>
                              </div>
                        </div>
                        <p class="borderMid"></p>
                      <div class="fields"> 
                            <label>Balance</label>
                            <input type="text" id="balance" name="balance" class="clientBal"/>
                            <span class="fields"><?php echo $userCurrency; ?></span>
                      </div>
                </div>
                <p class="borderMid"></p>
                <div class="innerSpace">
                            <div class="fields">
                                    <label>Payment Type</p></label>
                                    <p id="paymentType" class="clear btnlbl">
                                            <input type="radio" id="advance" name="pType" value="prepaid" onchange="showNext('partialWrap','prepaid',false);" checked="checked" /><label for="advance" title="Advance">Advance</label>
                                            <input type="radio" id="partial" name="pType" value ="partial" onchange="showNext('partialWrap','partial',true);" /><label for="partial" title="Partial">Partial</label>
                                            <input type="radio" id="credit" name="pType"  value ="postpaid" onchange="showNext('partialWrap','postpaid',false);" /><label for="credit" title="Credit">Credit</label>
                                    </p>
                            </div>
                            <div id="partialWrap" class="dn clear">
                                   <div class="fields">
                                            <label>Partial Amount</label>
                                             <input type="text" id="partialAmt" name="partialAmt" />
                                    </div>
                                    
                                    <div  class="fields">
                                           <label>Currency</p></label>
                                            <select name="partialCurrency" id="partialCurrency" class="currencyList">
                                                <!--  do not remove currencylist class -->
                                            </select>
                                    </div>
                            </div>
                            <div id="cashMemoBank">
                            <div class="fields ">
                                    <label>Type (Cash, Memo, Bank)</label>
                                    <select name="fundPaymentType" id="fundPaymentType">
                                            <option value="Cash">Cash</option>
                                            <option value="Memo">Memo</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Other">Other</option>
                                    </select>
                            </div>
                            <div class="dn fields" id="otherPaymentType">
                                    <label>Enter Type</label>
                                   <input type="text" name="otherPaymentType"/>
                            </div>
                            </div>    
                            <!--<select name="fundPaymentType" style="width:120px;">
                                <option value ="cash">cash</option>
                                <option value ="bank">bank</option>
                                <option value ="memo">memo</option>
                                <option value ="other">other</other>
                            </select>-->
                            <!--<input type="text" id="otherPaymentType" name="otherPaymentType"/>--> 
                            <div class="fields">
                                    <label>Description</label>
                                     <textarea class="rn desc" id="fundDescription" name="fundDescription"></textarea>
              		    	</div>  
                            <input class="mrT btn btn-medium btn-primary"  type="submit" name="save" id="save" value="Save Changes" title="Save Changes"/>
                            <!--<a class="mrT2 btn btn-medium btn-primary" onclick="UserEditFund();">Save Changes</a>-->
              	  </div>
        </form>
    </div>
    <!--//2 nd Tab Content-->
   
    <!--3rd Tab Content-->
    <div id="tabs-3" class="tabs displayEditInfo"><!--display number and email info-->
    		<div id="edInfo" class="fl">
           			<div class="fields mainUsern">
                            <label>Username</label> 
                            <?php // var_dump($userInfo);//array(9) { ["userId"]=> string(5) "31159" ["name"]=> string(12) "sudhirpandey" ["userName"]=> string(12) "sudhirpandey" ["planName"]=> string(7) "Testing" ["balance"]=> string(8) "888.1212" ["isBlocked"]=> string(1) "1" ["type"]=> string(1) "3" ["currencyId"]=> string(1) "2" ["resellerId"]=> string(5) "30618" } ?>
                         <h3><?php echo $userInfo['userName'];?></h3>
                   </div>
                   
            <form id="editClientInfo">
               <!--<div class="fields">
               		<label>Name</label>
                	<input type="text" name="clientName" value="<?php echo $userInfo['name'];?>" />
            </div>-->
            
            <div class="fields">
            		<input type="hidden" name="clientId" value="<?php echo $clientId;?>" />
         		  	 <label>Contact</label>
            		<div id="mobwrap">
                            <ul>
									<?php if($vContactArr[0]!=0){      foreach($vContactArr as $vContact){
                                    $isDefault = ($vContact['isDefault']==1) ? "Default" : "Make It Default";;
                                    $makeDefaultAction = ($vContact['isDefault']==1) ? "Default" : "makeDefault(.'".$vContact['verifiedNumber']."')";
                                    ?>
                                    <li class="default">
                                            <p class="idname" id="verifiednumber"></p>
                                            <div class="mailact pr mrT2">
                                                <i class="ic-16 correct"></i>
                                                <label class="fl vari"><?php  echo $vContact['countryCode'] ;?> - <?php  echo $vContact['verifiedNumber'] ;?></label>  
                                               <!-- <span class="ic-24  cp" title="Delete"></span>-->
                                            </div>
                                          
                                    </li>
                                    <?php }  }else{?>
                                    <li class="default">
                                            <p class="idname"> <?php echo  $unverifiedContact['countryCode']." - ".$unverifiedContact['tempNumber']; ?></p>
                                            <div class="mailact pr">
                                            </div>
                                    </li>
                                    <?php }
                                        if($vContactEmailArr[0]!=0){
                                        foreach($vContactEmailArr as $vContact){
                                        $vContactEmail = $vContact['email'];
                                        $isDefault = ($vContact['default_email']==1) ? "Default" : "Make It Default";;
                                    ?>
                                    <li class="">
                                            <p class="idname" id="verifiedemail"></p>
                                            <div class="mailact pr">
                                                <!--<p class="acType">Gtalk</p>-->
                                                <i class="ic-16 correct"></i>
                                                <label class="fl vari"><?php  echo $vContact['email']; ?></label>    
                                                 <!--<span class="ic-24 actdelC cp" title="Delete"></span>-->
                                            </div>
                                         
                                    </li>
                                    <?php }}  else{?>
                                    <li class="">
                                        <p class="idname"><?php echo $unverifiedEmail['email'];?></p>
                                        <div class="mailact pr">
                                        </div>
                                    </li>
                                    <?php }?>
                            </ul>
                            <!--<input type="text" style="width:50px; margin-right:10px;">
                            <input type="text"  style="width:140px;">-->
          		  </div>
           </div>
           
            <div class="fields">
                <label>Call Limit</label>
                <input type="text" name="callLimit" id="callLimit" value="<?php echo $userInfo['callLimit'];?>" />
                 <input type ="hidden" name="oldCallLimit" id="oldCallLimit" value="<?php echo $userInfo['callLimit'];?>"/>                
          	</div>
            
            <div class="fields">
				<label>Tariff</label>
                <select name="currenctTariff" id="currenctTariff" style="" class="selPlan">
               <?php 
                include_once(CLASS_DIR."plan_class.php");
		$planObj = new plan_class();
                $result = $planObj->getPlanName("planName,tariffId,outputCurrency",$_SESSION['userid'],2,NULL,$_SESSION['isAdmin']);
                $planDetail = json_decode($result,TRUE);   
                            foreach($planDetail as $key){ 
                                
                                   if($key['tariffId'] == $userInfo['tariffId']){
                                        $oldCurrency = $key['planName'];
                                        
                                    }
                                     echo '<option value="'.$key['tariffId'].'" >'.$key['planName'].'</option>';
                            } 
               ?>
                    </select>
                <input type ="hidden" name="hideTariff" id="hideTariff" value="<?php echo $userInfo['tariffId'];?>" oldcurrency ="<?php echo $oldCurrency; ?>"/>                
			</div>
			<!--<a class="mrT2 btn btn-medium btn-primary" href="javascript:void(0);">Save Changes</a>-->
            <input class="btn btn-medium btn-primary"  value="Save Changes" type="submit" title="Save Changes"/>
            </form>
            
            
            <?php if($userInfo['type'] == 3 || $userInfo['type'] == 0){?>
            <div id="userToReseller" class="mrT2">
                    <a onclick ="changeUserToReseller(<?php echo $clientId;?>);" class="themeLink">Change user to reseller</a>
            </div>
            <?php } ?>
            <?php {?>
            <div id="listenRemainingMin" class="mrT2">
                <input type="checkbox" id="listenStatus" <?php echo $checked; ?> onchange ="listenRemainMinStatus(<?php echo $clientId;?>,<?php echo $listenStatus; ?>);" >
                    <span  class="themeLink">Listen the remaining time during the call.</span>
            </div>
            <?php } ?>
            
            <div><br>
                    <input type="checkbox" name="changeSip" <?php  ($userInfo['sipFlag'] == 1?$check = "checked = checked":$check ="");echo $check; ?> onclick="changeSipSetting(<?php echo $_REQUEST['clientId']; ?>)" id="changeSip" value="" class="mrT03 fl" /><label for="changeSip" class="mrL">Enable/Disable Sip</label>
            </div>
            
    	</div>
        <!--End edit info div-->
        
<!--        <input type="button" title="Delete Account" value="Delete Account" class="btn btn-medium btn-danger fr">-->
    </div>
    <!--3rd  Tab Content-->
    
    <!--4thTab Content-->
    <div id="tabs-4" class="tabs displayEditInfo"><!-- tab Four content Change Password info-->
    	<div class="fields">
        	<label>Insert new password</label>
        	<form method="POST" id="chagngeClientPasswordForm" action="action_layer.php?action=resetClientPassword">
            <div class="chrow clear">
                    <div class="passDiv"><input type="text" name="newPass" id="newPass" placeholder="********" /></div>
                    <input type="hidden" name="clientId" value="<?php echo $clientId;?>" />
                    <label id="generatePassword">
                            <span class="blk cp"><i class="ic-24 refresh"></i></span>
                            <span>Generate</span>
                    </label>
            </div>
         <input class="mrT2 btn btn-medium btn-primary"  value="Save Changes" type="submit" title="Save Changes" />
        </form>
    </div>
    <!--//4thTab Content-->

 </div>
<!--//Tabs Content-->
<script>
$(document).ready(function(){
	
 // currencyList is global variable initialize in panel.js    
 $('.currencyList').append(currencyList); 
 $('#fundCurrency option[value="'+<?php echo $userInfo['currencyId'];?>+'"]').prop('selected',true);  
 $('#partialCurrency option[value="'+<?php echo $userInfo['currencyId'];?>+'"]').prop('selected',true);
    
    
    $("#currenctTariff option[value='<?php echo $userInfo['tariffId'];?>']").prop('selected', true)
          var options = { 
                  dataType:  'json',
                  //target:        '#response',   // target element(s) to be updated with server response 
                  beforeSubmit:  showRequest,  // pre-submit callback 
                  success:       showResponse  // post-submit callback 
          }; 
          $('#chagngeClientPasswordForm').ajaxForm(options); 
  }); 
  
  function changeSipSetting(userId)
  {
      if($('#changeSip').is(':checked'))
          var actionType = "enable";
      else
          var actionType = "disable";
      
      
      if(/[^0-9]/.test(userId) || userId == "" || userId == null)
      {
          show_message("Invalid User please select a valid user","error");
          return false;
      }
      
      $.ajax({
            url : "/controller/adminManageClientCnt.php", 
            type: "POST", 
            data:{forUser:userId,action:"changeSipSetting",actionType:actionType},
            dataType: "json",
            success:function (text)
            {
                show_message(text.msg,text.status);

            }
      })
  }
  
  
  function showRequest(formData, jqForm, options) { 
		//var queryString = $.param(formData); 
                $.validator.setDefaults({
	submitHandler: function() { }
	});
        $().ready(function() {
            // validate the comment form when it is submitted	
            $("#chagngeClientPasswordForm").validate({
                    rules: {
                            newPass :{
				required: true,
				minlength: 5
			}
                    }
            })
            $('#userProfile').validate();
        })
		$("#loading").show();
		if($("#chagngeClientPasswordForm").valid())
			return true; 
		else
			return false;
	} 
	// post-submit callback 
	function showResponse(response, statusText, xhr, $form)  {

		if(response.msg_type == "success"){
		show_message("Successfully Updated","success");
		}
                else{
                    show_message("An Error Occur while update "+response.msg,"error");
                }
		$("#loading").hide();
		
	} 
$(function() {
	$( "#paymentType, #BatchType" ).buttonset();
});
function showNext(id,event,status){
    if(status)
	$( "#"+id ).show();
    else
        $( "#"+id ).hide();
   
    if(event == "postpaid"){
        $("#cashMemoBank").hide();
    }else{
        $("#cashMemoBank").show();
    }
}

$( document ).ready(function() {
$("#clientTrstable tbody tr:visible:even").addClass("even"); 
$("#clientTrstable tbody tr:visible:odd").addClass("odd");

$("#transType").on('change',function(event){
   if(this.value=='Other')
       $("#transotherType").show();
   else
       $("#transotherType").hide();
 })

});
$(function() {
	$( "#tabs" ).tabs({
		create: function(event, ui) {},
		select: function(event, ui) {
		}
	});
});
//created by sudhir pandey (sudhir@hostnsft.com)
//creation date 06/08/2013
function toggleState(ths,type)
{
    ths.toggleClass('bigfreducer');
    if($('#changefunder'+type).val() == "reduce")
    {
       $('#changefunder'+type).val("add");
    }
    else
    {
        $('#changefunder'+type).val("reduce");
    }
}
function addAdditionTransaction()
{
  $('#additionalTrans').attr('disabled' , 'disabled');
    
 // status variable use for status of transaction add / reduce
 var status = $('#changefunderTrans').val();
 // var transType use for transaction type (cash,bank,voip91,other).
 var transType = $('#transType').val();
 var description = $('#description').val();
 var amount = $('#transAmount').val();
 var toUser = $('#toUser').val();
 var currency = $('#currency').val();
 var transTypeOther = $('#transTypeOther').val();
 var reg=/^[a-zA-Z0-9\@\_\-\s]+$/;
 var reg2 = /^[0-9]+(\.[0-9]{1,4})?$/;
 
 //check transaction type validation 
 if(reg.test(transType))
 {
    
    if(reg.test(description))
    {
        
        if(reg2.test(amount))
        {
            if(amount.length <= 7) 		
	    {
                if(transTypeOther.length <= 20)
                {
                    $.ajax({
                            url : "action_layer.php?action=addReduceTransaction",
                            type: "POST", 
                            data:{status:status,transType:transType,description:description,amount:amount,toUser:toUser,transTypeOther:transTypeOther,currency:currency},
                            dataType: "json",
                            success:function (text)
                            {
                                show_message(text.msg,text.status);
                                if(text.status == "success")
                                {
                                    
                                     var str = designTransactionLog(text.str.detail);                       
                                     $('#transactionTable').html('');
                                     $('#transactionTable').html(str);
                                     $("#clientTrstable tbody tr:visible:even").addClass("even"); 
                                     $("#clientTrstable tbody tr:visible:odd").addClass("odd");
                                     $('#transType').val('');
                                     $('#description').val('');
                                     $('#transAmount').val('');
                                     $("#transotherType").hide();
                                     $('#additionalTrans').removeAttr('disabled');
                                }
                            }
                        })
                 }
                 else {
                    show_message("please enter valid other type ,no more then 20 characters.! ","error");
                    $('#additionalTrans').removeAttr('disabled');
             }
            }
            else{
                show_message("please enter amount no more then 7 digits ! ","error");
                $('#additionalTrans').removeAttr('disabled');
             }
        }
        else{
        show_message("please enter valid amount! ","error");
        $('#additionalTrans').removeAttr('disabled');
             }
    }
    else{
       show_message("please enter valid description !","error");
     $('#additionalTrans').removeAttr('disabled');
             }
    }
    else{
       show_message("please enter valid transaction type! ","error");
     $('#additionalTrans').removeAttr('disabled');
             }
}

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 04/09/2013
//function use for design transaction log 
function getTransactionLog(fromuser,touser){
 $.ajax({
                   url : "action_layer.php?action=getTransactionLog",
                   type: "POST", 
                   data:{fromuser:fromuser,touser:touser},
                   dataType: "json",
                   success:function (text)
                   {
                       var str = designTransactionLog(text.detail);                       
                       $('#transactionTable').html('');
                       $('#transactionTable').html(str);
                       $("#clientTrstable tbody tr:visible:even").addClass("even"); 
                       $("#clientTrstable tbody tr:visible:odd").addClass("odd");
                   }
})
}
//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 04/09/2013
//function use for design transaction log 
function designTransactionLog(text){
 var str = '<table width="100%" border="0" cellspacing="0" cellpadding="0" id="clientTrstable" class="cmntbl alR boxsize">\
            <thead>\
                 <tr>\
                    <th width="15%">Date</th>\
                    <th width="8%">Type</th>\
                    <th   class="alR" width="5%">Amount</th>\
                    <th class="alR" width="5%">Balance</th>\
                    <th width="30%">Description</th>\
                    <th  class="alR" width="10%">Debit</th>\
                    <th class="alR" width="10%">Credit</th>\
                    <th  width="13%">Closing Balance</th>\
                 </tr>\
            </thead>\
            <tbody>';    
 var totalCredit=0;var totalDebit=0;var totalClosingBalance=0;
 $.each( text, function(key, item ) {
     
 if(item.amount != 0) var amount = item.amount; else var amount = '-';
  str += '<tr class="hvrParent">\
                    <td>'+item.date+'</td>\
                    <td>'+item.paymentType+'</td>\
                    <td class="alR">'+amount+'</td>\
                    <td class="alR">'+parseFloat(item.currentBalance).toFixed(2)+'</td>\
                    <td>'+item.description+'</td>\
                    <td class="alR"><div class="debit dn hvrChild fr">('+parseFloat(item.debit).toFixed(2)+' '+item.currencyName+')</div><div class="debit fr mrR" >'+item.debitActualCurrency+'</div></td>\
                    <td class="alR"><div class="dn hvrChild fr">('+parseFloat(item.credit).toFixed(2)+' '+item.currencyName+')</div><div class="fr mrR">'+item.creditActualCurrency+'</div></td>\
                    <td class="alRcloseBalance">'+parseFloat(item.closingBalance).toFixed(2)+'</td>\
                </tr>';
                        
    totalCredit = Number(totalCredit) + Number(item.creditActualCurrency);
    totalDebit = Number(totalDebit) + Number(item.debitActualCurrency);
    totalClosingBalance = item.closingBalance;
    })
    
    str+= '<tr class="zerobal">\
                    <td colspan="100%"></td>\
                </tr>\
                <tr class="">\
                    <td>&nbsp;</td>\
                    <td></td>\
                    <td class="alR">&nbsp;</td>\
                    <td  class="alR">&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td class="alR"><span class="debit">'+totalDebit+'</span></td>\
                    <td class="alR">'+totalCredit+'</td>\
                    <td class="alRcloseBalance">'+totalClosingBalance+'</td>\
                </tr>\
                </tbody>\
            </table>';
     
     
     return str;
    
}
//***edit fund***
$(document).ready(function() { 
    
   $("#fundPaymentType").on('change',function(event){
   if(this.value=='Other')
       $("#otherPaymentType").show();
   else
       $("#otherPaymentType").hide();
 })
		var options = { 
                     
                        url:"action_layer.php?action=editFund", 
                        type:'POST',        
			dataType: 'json',
			beforeSubmit:  showEditFundRequest,  // pre-submit callback 
			success:     
                                function(text)
                                {
                                    
                                $('#save').removeAttr("disabled");
                                show_message(text.msg,text.status);
                                if(text.status == "success"){
                                  $('#fundAmount,#balance,#partialAmt,#fundDescription').val('');
                                    var clientId = $('#toUserEditFund').val();
                                   
                                   $('.'+clientId+'changeBal').html(parseFloat(text.balance).toFixed(2));
                                }
                                 $('#save').removeAttr('disabled');
                                }
		};
		$('#editFundform').ajaxForm(options); 
	}); 
        
function showEditFundRequest(formData, jqForm, options){

    $('#save').attr('disabled','disabled');
  
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#editFundform").validate({
                rules: {
                        fundAmount :{
                            required: true,
                            maxlength: 8,
                            number:true
                        },
                        balance :{
                            required: true,
                            maxlength: 8,
                            number:true
                        }
                        
                       }
        })
        
    })
            $("#loading").show();
            if($("#editFundform").valid())
                    return true; 
            else
                    return false;
}
//*********
$(document).ready(function() { 
		var options = { 
                     
                        url:"action_layer.php?action=editClientInfo", 
			dataType: 'json',
			type:'POST', 
			beforeSubmit:  showEditInfoRequest,  // pre-submit callback 
			success:     
                                function(text)
                                {
                                show_message(text.msg,text.status);
                                if(text.status =="success"){
                                    $('#oldCallLimit').val($('#callLimit').val());
                                    $('#hideTariff').val($('#currenctTariff').val());
                                }
                                }
		};
                
                
                
		$('#editClientInfo').ajaxForm(options); 
	}); 
 function showEditInfoRequest(formData, jqForm, options){
  
  $.validator.setDefaults({
  submitHandler: function() {}
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#editClientInfo").validate({
                rules: {
                        clientName :{
                            required: true,
                            maxlength: 20
                        },
                        callLimit :{
                            required: true,
                            maxlength: 4,
                            number:true
                        },
                        currenctTariff :{
                            required: true
                            
                        }
                        
                       },
                 messages: {
			callLimit: {
				maxlength: "please enter no more then 4 digits"
				
			}	
                 }       
        })
        
    })
            $("#loading").show();
            
            if($('#currenctTariff').val() != $('#hideTariff').val()){
                
                if (!confirm("Are You Sure To Change Tariff Plan form "+ $('#hideTariff').attr('oldcurrency')+" to " +$('#currenctTariff option:selected').text()+"")) {
		return false;
                
                }
            }
              
            
            if($("#editClientInfo").valid())
                    return true; 
            else
                    return false;

} 

function listenRemainMinStatus(userId,currStatus){
    $.ajax({
                   url : "controller/adminManageClientCnt.php?action=listenRemainMinStatus", 
                   type: "POST", 
                   data:{userId:userId,
                        currStatus:currStatus},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                           //$('#listenRemainingMin').hide();
                       }
                                     
                   }
       });
}

function changeUserToReseller(userId){
      
       $.ajax({
                   url : "controller/adminManageClientCnt.php?action=changeUserToReseller", 
                   type: "POST", 
                   data:{userId:userId},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                           $('#userToReseller').hide();
                       }
                                     
                   }
       });
      
      
  }
  

$.extend({
  password: function (length, special) {
    var iteration = 0;
    var password = "";
    var randomNumber;
    if(special == undefined){
        var special = false;
    }
    while(iteration < length){
        randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
        if(!special){
            if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
            if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
            if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
            if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
        }
        iteration++;
        password += String.fromCharCode(randomNumber);
    }
    return password;
  }
});
 $('#generatePassword').click(function(e){
    password = $.password(10,true);
    $('#newPass').val(password);
    e.preventDefault();
});


//pagination(<?php echo $transData['totalCount']; ?>,<?php echo $pageNo; ?>,'#pagination');
//code for pagination

        var strt = <?php echo $pageNo; ?>;
        var totalCount = <?php if(isset($transData['totalCount']) && is_numeric($transData['totalCount']) && $transData['totalCount'] != 0) echo $transData['totalCount'];else echo 1; ?>;
        
        if(strt == undefined || strt == null || strt == '' )
            strt = 1;
        
        if(totalCount == undefined || totalCount == null || totalCount == '' )
            totalCount = 1;
pagination(totalCount,<?php echo $pageNo; ?>,'#pagination',<?php echo $clientId; ?>);
</script>

<script>
//maintain tab content height
var _M = $('.resellerMCHead').outerHeight(true)
var _N = $('.ui-tabs-nav').outerHeight(true)

_H = $('#rightsec').height();
$('.tabs').css({height: _H - (_M+_N) -60, 'overflow':'auto'});
</script>