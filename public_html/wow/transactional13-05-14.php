<?php
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."transaction_class.php");
include_once(CLASS_DIR."reseller_class.php");
include_once(CLASS_DIR.'contact_class.php');

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("index.php");
}

#touser id
$clientId = $_REQUEST['clientId'];

if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;

#object of transaction class 
$trans_obj = new transaction_class();

#call function getTransactionlogDetial for get all detion of transation 
$transaction = $trans_obj->getPersonalTransaction($clientId,0,NULL,$pageNo);
$transData = json_decode($transaction,TRUE);

$UserBalance = $trans_obj->getcurrentbalance($clientId);

$resellerId = $funobj->getResellerId($clientId);
$resObj= new reseller_class();
$userInfo=$resObj->loadUserDetails($clientId,'*',$resellerId);

$contactObj= new contact_class();
$vContactArr=$contactObj->getConfirmMobile($clientId);
if($vContactArr[0] == 0){
    $unverifiedContact = $contactObj->getUnconfirmMobile($clientId);                 
}  
//var_dump($vContactArr);

#find verified contact number
$vContactEmailArr=$contactObj->getConfirmEmail($clientId);
if($vContactEmailArr[0] == 0){
    $unVEmailid = $contactObj->getUnConfirmEmail($clientId);
}



#get admin manager id of user 
$managerId = $funobj->getadminId($clientId);

//get listenRemainvalue status
$listenStatus =  $resObj->getRemainingMinutesStatus($clientId,'userId','getMinuteVoice');

$checked = '';
if($listenStatus)
    $checked = 'checked';

?>

<div id="tabs" class="mngClntFnction">
    <ul>
            <li onclick ="getTransactionLog(<?php echo $clientId;?>)"><a href="#tabs-1" title="Transactional"><span class="ic-40 tranLog"></span></a></li>
            <li><a href="#tabs-2" title="Edit Fund"><span class="ic-40 editfund"></span></a></li>
			<!--<li><a href="#tabs-3" title="Add SIP"><span class="ic-40 addsip"></span></a></li>-->
	    <li onclick="getUserInfoAcm(<?php echo $managerId; ?>)"><a href="#tabs-4" title="Settings"><span class="ic-40 setting"></span></a></li>
            <li onclick="getUserSysDetail(<?php echo $clientId; ?>)"><a href="#tabs-5" title="Latest info"><span class="ic-40 latestinfo"></span></a></li>
   </ul>
   
	<!--1st Tabs Content-->
    <div id="tabs-1">
            <div class="tablflip-scroll" id="transactionTable">
      		   <table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize" id="clientTrstable">
                <thead>
                    <tr>
                        <th >Date</th>
                        <th >A/C Manager</th>
                        <th >Type</th>
                        <th class="alR">Amount</th>
                        <th class="alR">Balance</th>
                        <th >Description</th>
                        <th width="10%" class="alR">Debit</th>
                        <th width="10%" class="alR">Credit</th>
                        <th class="alR">Closing Balance</th>
                    </tr>
                </thead>
          		<tbody>
                  <?php  foreach($transData['detail'] as $trans) { ?>
                         <tr class="hvrParent">
                                <td><?php echo $trans['date'];?></td>
                                <td><?php echo $trans['name'];?></td>
                                <td><?php echo htmlentities($trans['paymentType']);?></td>
                                <td class="alR"><?php if($trans['amount'] != 0) echo $trans['amount']; else echo "-"; ?></td>
                                <td class="alR"><?php echo round($trans['currentBalance'],3); ?></td>
                                <td><?php echo htmlentities($trans['description']); ?></td>
                                <td class="alR">
                                	<div class="debit fr"><?php echo $trans['debitActualCurrency']; ?></div>
                                    <div class="hvrChild fr mrR">(<?php echo $trans['debit'] . " " . $trans['currencyName']; ?>)</div>
								</td>
                                <td class="alR">
                                	<div class="debit fr"><?php echo $trans['creditActualCurrency']; ?></div>
                                    <div class="hvrChild fr mrR">(<?php echo $trans['credit'] . " " . $trans['currencyName'];?>)</div>
                                </td>
                                <td class="alR closeBalance"><?php echo round($trans['closingBalance'],2); ?></td>
              		  </tr>
                  <?php } ?>				
                        <tr class="zerobal">
                		    <td colspan="100%"></td>
               			 </tr>
                           
            </tbody>
        </table>
           </div>
        <div id="pagination" class="mrT1"></div>
        <!-- Bootm Actions Wrapper-->
        <div class="clear mrT2">
        	<div class="actionDiv">
            	<p class="mrB">Type</p>
                 <select name="transType" id="transType">
                    <option value="Cash">Cash</option>
                    <option value="Memo">Memo</option>
                    <option value="Bank">Bank</option>
                    <option value="Other">Other</option>
                </select>
				<!--<input type="text" id="type" name="type" class="isInput150">-->                       
                <input type="hidden" id="toUser" value="<?php echo $clientId; ?>" name="toUser">
            </div>
            <div class="actionDiv">
                <div id="transotherType" class="dn fields">
                    <p class="mrB">Enter Type</p>
                    <input type="text" id="transTypeOther" name="transTypeOther" />
                </div>
            </div>
            <div class="actionDiv">
            	<p class="mrB">Description</p>
                <input type="text" id="description" name="description" class="isInput250"/>
            </div>

            <div class="actionDiv mDevice">
            	<p class="mrB">Add/Reduce</p>
                <div class="clear" id="sporow">
                	<span class="funder fl">
                        <label class="ic-60 enable cp" for="changefunder" onclick="toggleState($(this),'Trans');"></label>
                        <input type="checkbox" value="add" checked="checked" style="display:none" id="changefunderTrans">
                    </span>
                    
                    <input type="text" placeholder="Amount" id="transAmount" name="transAmount" class="mrL fl">
                    <select name="currency" id="currency" class="currency fl currencyList">
                           
                    </select>                    
                </div>
            </div>
            <div class="actionDiv">
            	<p class="mrB">&nbsp;</p>
            	<input type="submit" class="mrL btn btn-medium btn-primary" name="Done" onclick="addAdditionTransaction();" id="saveadditionTrs" value="Done" title="Done">
			</div>
            
        </div>
    </div>
    <!--//1st Tabs Content-->
    
    <!--2nd Tabs Content-->
    <div id="tabs-2" class="pd15">
    	<form class="formElemt" id="editFundform">
            <div class="fields">
                            <p>Current  Balance</p>
                            <h3 class="userBalance"><span class="<?php echo $clientId;?>changeBal"><?php echo round($UserBalance,3); ?> </span> <?php echo "  ".$currency = $funobj->getCurrencyViaApc($userInfo['currencyId'],1); ?> </h3>
                        </div>
            <div class="fields addReduce">   
                     <label>Add/Reduce Fund</label>
                    <span>
                         <label onclick="toggleState($(this),'EditFund');" for="changefunder" class="ic-60 enable cp"></label>
                         <input type="checkbox" id="changefunderEditFund" name="changefunderEditFund" style="display:none" checked="checked" value="add" />
                      
                    </span>
                     <input type="hidden" name="toUserEditFund" value="<?php echo $clientId; ?>" id="toUserEditFund"/>
                  	<div class="fl"><input type="text" name="fundAmount" id="fundAmount" placeholder="Amount"  class="mrL"></div>
                        <select id="fundCurrency" name="fundCurrency" class="small currencyList">
                             
                    </select>
           </div>
			<div class="fields">
                    <label>Balance</label>
                    <div class="fl"><input type="text" id="balance" name="balance" class="clientBal small"/></div>
                    
           </div>
       		<div class="paymMode">
                    <div class="fields minSpac">
                            <label>Payment Type</p></label>
                            <p id="paymentType" class="clear btnlbl">
                                
                                  <input type="radio" id="advance" name="pType" value="prepaid" onchange="showNext('partialWrap','prepaid',false);" checked="checked"  class="ui-helper-hidden-accessible"/><label for="advance" title="Advance"  class="first">Advance</label>
                                    <input type="radio" id="partial" name="pType" value ="partial" onchange="showNext('partialWrap','partial',true);"  class="ui-helper-hidden-accessible"/><label for="partial" title="Partial">Partial</label>
                                    <input type="radio" id="credit" name="pType"  value ="postpaid" onchange="showNext('partialWrap','postpaid',false);"  class="ui-helper-hidden-accessible" /><label for="credit" title="Credit">Credit</label>
                                 
                            </p>
                    </div>
                    <div class="dn clear" id="partialWrap">
                           <div class="fields ifparticl">
                                    <!--<p class="fl particleText">if Partial</p>-->
                                    <div class="fl">
                                        <label>Partial Amount</label>
                                        <input type="text" class="small" id="partialAmt" name="partialAmt"/>
                                        <select name="partialCurrency" id="partialCurrency" class="currencyList">
                                          
                                        </select>
                                     </div>
                            </div>
                          
                    </div>
                    <div id="cashMemoBank">
                    <div class="fields ">
                            <label>Type (Cash, Memo, Bank)</label>
                            <select id="fundPaymentType" name="fundPaymentType">
                                    <option value="Cash">Cash</option>
                                    <option value="Memo">Memo</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Other">Other</option>
                            </select>
                    </div>
                    
                    <div id="otherPaymentType" class="dn fields">
                            <label>Enter Type</label>
                           <input type="text" name="otherPaymentType">
                    </div>
                    </div>    
                    <div class="fields">
                            <label>Description</label>
                             <textarea name="fundDescription" id="fundDescription" class="rn desc"></textarea>
                    </div>  
                    <input type="submit" title="Done" value="Done" id="save" name="Done" class="mrT btn btn-medium btn-primary">
           </div>
        </form>
    </div>
    <!--//2nd Tabs Content-->
   
    <!--3rd Tabs Content--> 
    <!-- <div id="tabs-3"  class="pd15">
    	 <div class="add clear oh">
        	<span class="ic-12 lgrayArr db fl"></span>
            <input type="checkbox" class="fl db"/>
            <label class="db fl fwd">Add API'S</label>
          </div>
    	
         <?php for($i = 1; $i <= 10; $i++) 
		  {
			echo'
			<div class="clear">
					<div class="addsipInput">
							 <input type="text" placeholder="192.198.122.100.48" id="search">
							 <span title="Delete" class="ic-16 delete cp"></span> 
				   </div>
				    <p class="arBorder secondry fl cp sucsses cp" title="Add">
						<span class="ic-16 add "></span>
              	   </p>
			 </div>
			   ';
			}
	   	?>
        <input type="submit" class="mrT1 btn btn-medium btn-primary" name="Done" id="" value="Done" title="Done">
    </div>-->
    <!--//3rd Tabs Content--> 
    
    <!--4rth Tabs Content--> 
    <div id="tabs-4" class="pd15">
     	<div class="leftSetform">
                <form class="formElemt settingform" id="editClientInfo">
                    <div class="add clear oh mrB1">
                            <span class="ic-12 lgrayArr db fl"></span>
                            <label class="db fl fwd">Panel Setting</label>
                            <input type="hidden" name="clientId" value="<?php echo $clientId;?>" />
                  </div>
                    <div class="fields">
                        <label>Call Limit</label>
                       <input type="text" name="callLimit" id="callLimit" value="<?php echo $userInfo['callLimit'];?>" />
                     <input type ="hidden" name="oldCallLimit" id="oldCallLimit" value="<?php echo $userInfo['callLimit'];?>"/>     
                  </div>
                    <div class="fields">
                        <label>Bandwidth Limit</label>
                       <input type="text" name="bandwidthLimit" id="bandwidthLimit" value="<?php echo $userInfo['bandwidthLimit'];?>" />
                     <input type ="hidden" name="oldbandwidthLimit" id="oldbandwidthLimit" value="<?php echo $userInfo['bandwidthLimit'];?>"/>     
               
                  </div>
                     <!--<div class="fields ">
                            <label>Route/Dialplan</label>
                           <input type="text"/>
                      </div>-->
                    <div class="fields">
                        <label>Tariff/Plan</label>
                        <select name="currenctTariff" id="currenctTariff" class="selPlan" style="">
                        <?php
                        
                        include_once(CLASS_DIR."plan_class.php");
                        $planObj = new plan_class();
                        $result = $planObj->getPlanName("planName,tariffId,outputCurrency",$resellerId,2,NULL);
                        var_dump($result);
//                        die();
                        $planDetail = json_decode($result,TRUE);   
                                    foreach($planDetail as $key){ 
                                        $selected = '';
                                        if($key['tariffId'] == $userInfo['tariffId']){
                                                $oldCurrency = $key['planName'];
                                                 $selected = 'selected = "selected"';
                                            }
                                            echo '<option value="'.$key['tariffId'].'"  currency ="'.$key['planName'].'" '.$selected.'>'.$key['planName'].'</option>';
                                    } 
                        
  
                                ?>
                        </select>
                    <input type ="hidden" name="hideTariff" id="hideTariff" value="<?php echo $userInfo['tariffId'];?>" oldcurrency ="<?php echo $oldCurrency; ?>"/>                
                
                  </div>
                    <input type="submit" class="mrT btn btn-medium btn-primary" name="Done"  value="Done" title="Done"> 
                    </form>
           
            <div>
                 <div class="mrT1 mrB1">
                 	<input type="checkbox" name="changeSip" <?php  ($userInfo['sipFlag'] == 1?$check = "checked = checked":$check ="");echo $check; ?> onclick="changeSipSetting(<?php echo $_REQUEST['clientId']; ?>)" id="changeSip" value="" class="mrT03 fl" />
                    <label for="changeSip" class="mrL">Enable/Disable Sip</label>
                 </div>

				<div>
                                    <?php if($userInfo['type'] == 2)
                                        {
                                        $userTypeValue = 'reseller';
                                        $typeFor ='Reseller';
                                        }else if($userInfo['type'] == 3)
                                        {
                                        $userTypeValue = 'user';
                                        $typeFor ='User';
                                    }?>
                                    
                                    
					 <label id="userTypeLbl" onclick="toggleUserType($(this),<?php echo $_REQUEST['clientId']; ?>);" for="changefunder" class="ic-60 user cp <?php echo $userTypeValue; ?>"></label>
					 <span id="userTypeTxt" class="lh32 mrL"><?php echo $typeFor;?></span>
					 <input type="checkbox" id="toggleUserType" name="toggleUserType" style="display:none" checked="checked" value="<?php echo $userTypeValue;?>" />                      
				</div>
                
            </div>
            <div id="listenRemainingMin" class="mrT2">
                <input type="checkbox" <?php echo $checked;?> onchange ="listenRemainMinStatus(<?php echo $clientId;?>,<?php echo $listenStatus;?>);" id="listenRemain">
                <span  class="themeLink">Listen the remaining time during the call.</span>
        	</div>
          </div>
          <div class="rightSetForm">
                <form class="formElemt settingform" id="editgeneralSetting">
                    <div class="add clear oh mrB1">
                            <span class="ic-12 lgrayArr db fl"></span>
                            <label class="db fl fwd">General Setting</label>
                   </div>
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
                                                    <label class="fl vari"><?php  echo $vContact['countryCode'] ;?> - <?php  echo $vContact['verifiedNumber'] ;?></label><br>  
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
                                            $isDefault = ($vContact['default_email']==1) ? "Default" : "Make It Default";
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
                                            <p class="idname"><?php echo $unVEmailid['email'];?></p>
                                            <div class="mailact pr">
                                            </div>
                                        </li>
                                        <?php }?>
                                </ul>
                                <!--<input type="text" style="width:50px; margin-right:10px;">
                                <input type="text"  style="width:140px;">-->
                      </div>
                    <br><br>
                   <div class="fields">
                            <span class="ic-12 lgrayArr db fl"></span>
                            <label class="db fl fwd mrB">Account Manager</label>
                            <select name="accountManager" id="accountManager" style="">
                            <?php /** 
                                  foreach($allAdmin as $key => $value){
                                     $selecMgr = ''; 
                                      if($managerId == $key){
                                          $selecMgr = 'selected = "selected"';
                                      }
                                       echo '<option value="'.$key.'" '.$selecMgr.'>'.$value.'</option>';
                                  }*/
                            ?>
                            </select>
                                 
                            <!--<p title="Add me as Account Manager" class="arBorder secondry fl cp primary cp">
                                <span class="ic-16 add "></span>
                            </p>-->
                            <input type="hidden" name="clientId" value="<?php echo $clientId;?>" />
                  </div>
                   <input type="submit" class="mrT btn btn-medium btn-primary" name="Done" value="Done" title="Done">
                  </form> 
                 <div class="add clear oh mrT4">
                       <p>
                            <span class="ic-12 lgrayArr db fl"></span>
                            <label class="db fl fwd mrB">Change Password</label>
                        </p>
                        <div>
                        <form method="POST" id="chagngeClientPasswordForm" action="/action_layer.php?action=resetClientPassword">
                                <div class="clear chngLabel">
                                   <div class="fl pr">
                                   	<input type="text" name="newPass" id="newPass" placeholder="********" class="fl mrT"/>
                                   </div>
                                    <p title="Reset Password" id="generatePassword" class="arBorder secondry fl cp reset cp reset">
                                        <span class="ic-16 reset"></span>
                                    </p>
                                 </div>
                                <input type="hidden" name="clientId" value="<?php echo $clientId;?>" />
                                <input class="mrT2 btn btn-medium btn-primary"  value="Save Changes" type="submit" title="Save Changes" />
                        </form>
              	</div>
              </div> 
            </div>
        <!-- assign rout and dial plan -->
        <div>
            
           <form class="formElemt " id="setDialPlanAndRoute">
            <div class="add clear oh mrB1">
                    <span class="ic-12 lgrayArr db fl"></span>
                    <label class="db fl fwd">DialPlan and Rout assign</label>
            </div>
            <div class="">
            <input type="radio" name="routeDialplan" value="route" checked="checked" onchange="showDialorRouteDiv(this)">Route
            <input type="radio" name="routeDialplan" value="dialPlan" onchange="showDialorRouteDiv(this)">Dial Plan
            </div>
            <div class="selDialorRoute" id="routeListDiv" style="margin-top: 10px;">
            <select name="routeList" id="routeList" style="">
            </select>
            </div>
            <div class="dn selDialorRoute" id="dialPlanListDiv" style="margin-top: 10px;">   
            <select name="dialPlanList" id="dialPlanList" style="">
            </select>
            </div>
            <input type="hidden" name="clientId" value="<?php echo $clientId;?>" />
           <input class="mrT2 btn btn-medium btn-primary"  value="Done" type="submit" title="Save Changes" />
          </form>  
        </div>    
        
    </div>
    <!--//4rth Tabs Content--> 
    
    <!--5th Tabs Content--> 
    <div id="tabs-5">
    	 <div class="tablflip-scroll" id="transactionTable">
      		   <table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize" id="systemInfo">
                   
           		</table>
            
      			 <!--  <table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize mrT1" id="">
                    <thead>
                        <tr>
                            <th width="20%">Latest Login Time  </th>
                            <th width="20%">Ip's</th>
                            <th width="20%">Browser</th>
                            <th width="60%">&nbsp;</th>
                        </tr>
                    </thead>
          			<tbody>
						  <?php for($i = 1; $i <= 3; $i++) 
                          {
                                echo'
                                  <tr class="even">
                                        <td>13 Feb 2011 09:45:23 AM</td>
                                        <td>192.198.122.100.48</td>
                                        <td>Chrome</td>
                                        <td>&nbsp;</td>
                                  </tr>
                                  <tr class="odd">
                                        <td>13 Feb 2011 09:45:23 AM</td>
                                        <td>192.198.122.100.48</td>
                                        <td>Chrome</td>
                                        <td>&nbsp;</td>
                                  </tr>';
                          } ?>
            	</tbody>
            </table> -->
        </div>
    </div>
    <!--//5th Tabs Content--> 

  </div>
<script type="text/javascript">
var tb = <?php echo (isset($_REQUEST['tb']))? $_REQUEST['tb'] : 0;?>;

 $(function() {
     
 // currencyList is global variable initialize in panel.js    
 $('.currencyList').append(currencyList); 
 $('#fundCurrency option[value="'+<?php echo $userInfo['currencyId'];?>+'"]').prop('selected',true);  
 $('#partialCurrency option[value="'+<?php echo $userInfo['currencyId'];?>+'"]').prop('selected',true);
 
$("#clientTrstable tbody tr:visible:even").addClass("even"); 
$("#clientTrstable tbody tr:visible:odd").addClass("odd");

$("#transType").on('change',function(event){
   if(this.value=='Other')
       $("#transotherType").show();
   else
       $("#transotherType").hide();
 })
        
});

function toggleState(ths,type)
{
    
    ths.toggleClass('disable');
    if($('#changefunder'+type).val() == "reduce")
    {
       $('#changefunder'+type).val("add");
    }
    else
    {
        $('#changefunder'+type).val("reduce");
    }
}

function toggleUserType(ths,userId)
{   
    var inpt = $('#toggleUserType');
	var inTxt = $('#userTypeTxt');
	var cv = inpt.val();	
	ths.toggleClass("user reseller");
	if(cv == 'user' )
	{
		inpt.val('reseller'); 
		inTxt.html('Reseller');
                $('#userTypeLbl').removeClass('user').addClass('reseller');
                var usertype = 2;
        }
	else
	{
		inpt.val('user'); inTxt.html('User');
                $('#userTypeLbl').removeClass('reseller').addClass('user');
                var usertype = 3;
	}
        
        
        $.ajax({
                   url : "/controller/adminManageClientCnt.php?action=changeUserTypeStatus", 
                   type: "POST", 
                   data:{userId:userId,usertype:usertype},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                                             
                   }
       });
      
        
}



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

$(function() {
	$( "#paymentType, #BatchType" ).buttonset();
});

//************* add reduce transaction****

function addAdditionTransaction(){
   
   $('#saveadditionTrs').attr( 'disabled' , 'disabled' );

 // status variable use for status of transaction add / reduce
 var status = $('#changefunderTrans').val();
 // var transType use for transaction type (cash,bank,voip91,other).
 var transType = $('#transType').val();
 var description = $('#description').val();
 var amount = $('#transAmount').val();
 var toUser = $('#toUser').val();
 var transTypeOther = $('#transTypeOther').val();
 var currency = $('#currency').val();
 var reg=/^[a-zA-Z0-9\@\_\-\s]+$/;
 var reg2 = /^[0-9]+(\.[0-9]{1,4})?$/;
 
 //check transaction type validation 
 if(reg.test(transType)){
    if(reg.test(description)){
        if(reg2.test(amount)){
            if(amount.length <= 7) 		
	    {
            if(transTypeOther.length <= 20){
       $.ajax({
                   url : "/action_layer.php?action=addReduceTransaction",
                   type: "POST", 
                   data:{status:status,currency:currency,transType:transType,description:description,amount:amount,toUser:toUser,transTypeOther:transTypeOther},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                            var str = designTransactionLog(text.str);                       
                            $('#transactionTable').html('');
                            $('#transactionTable').html(str);
                            $("#clientTrstable tbody tr:visible:even").addClass("even"); 
                            $("#clientTrstable tbody tr:visible:odd").addClass("odd");
                           $('#transType').val('');
                           $('#description').val('');
                           $('#transAmount').val('');
                           $("#transotherType").hide();
                       }
                        $('#saveadditionTrs').removeAttr('disabled');
                   }
        })
             }else {
             show_message("please enter valid other type ,no more then 20 characters.! ","error");
             $('#saveadditionTrs').removeAttr('disabled');
             }
            }else{
            show_message("please enter amount no more then 7 digits ! ","error");
            $('#saveadditionTrs').removeAttr('disabled');
             }
        }else{
        show_message("please enter valid amount! ","error");
        $('#saveadditionTrs').removeAttr('disabled');
             }
    }else{
       show_message("please enter valid description !","error");
       $('#saveadditionTrs').removeAttr('disabled');
             }
    }else{
       show_message("please enter valid transaction type! ","error");
       $('#saveadditionTrs').removeAttr('disabled');
             }
    
}

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 04/09/2013
//function use for design transaction log 
function designTransactionLog(text){
 var str = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize" id="clientTrstable">\
                <thead>\
                    <tr>\
                        <th width="10%">Date</th>\
                        <th width="15%">A/C Manager</th>\
                        <th width="10%">Type</th>\
                        <th width="5%" class="alR">Amount</th>\
                        <th width="5%" class="alR">Balance</th>\
                        <th width="30%">Description</th>\
                        <th width="5%" class="alR">Debit</th>\
                        <th width="5%" class="alR">Credit</th>\
                        <th width="20%" class="alR">Closing Balance</th>\
                    </tr>\
                </thead>\
          		<tbody>';    
  
 $.each( text.detail, function(key, item ) {
  str += '<tr class="hvrParent">\
                    <td>'+item.date+'</td>\
                    <td>'+item.name+'</td>\
                    <td>'+item.paymentType+'</td>\
                    <td class="alR">'+item.amount+'</td>\
                    <td class="alR">'+parseFloat(item.currentBalance).toFixed(2)+'</td>\
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

//***********edit fund*****        
$(document).ready(function() { 
    
   $("#fundPaymentType").on('change',function(event){
   if(this.value=='Other')
       $("#otherPaymentType").show();
   else
       $("#otherPaymentType").hide();
 })
		var options = { 
                     
                        url:"/action_layer.php?action=editFund", 
                        type:'POST',        
			dataType: 'json',
			beforeSubmit:  showEditFundRequest,  // pre-submit callback 
			success:     
                                function(text)
                                {
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
  $.validator.setDefaults({
  submitHandler: function() {
      
  }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#editFundform").validate({
                rules: {
                        fundAmount :{
                            required: true,
                            maxlength: 5,
                            number:true
                        },
                        balance :{
                            required: true,
                            maxlength: 5,
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


//*************transaction detail of reseller

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 25/10/2013
//function use for design transaction log 
function getTransactionLog(touser){
 $.ajax({
                   url : "/action_layer.php?action=adminGetTransaction",
                   type: "POST", 
                   data:{touser:touser},
                   dataType: "json",
                   success:function (text)
                   {
                       var str = designTransactionLog(text);                       
                       $('#transactionTable').html('');
                       $('#transactionTable').html(str);
                       $("#clientTrstable tbody tr:visible:even").addClass("even"); 
                       $("#clientTrstable tbody tr:visible:odd").addClass("odd");
                   }
})
}

//*************setting*****

$(document).ready(function() {
    
   
		var options = { 
                     
                        url:"/action_layer.php?action=editClientInfo", 
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
                                        $('#oldbandwidthLimit').val($('#bandwidthLimit').val());
                                    }
                                }
		};
                
                
                
		$('#editClientInfo').ajaxForm(options); 
                
           
             var options = { 
                     
                        url:"/controller/adminManageClientCnt.php?action=setUserDialPlanOrRoute", 
			dataType: 'json',
			type:'POST', 
			success:     
                                function(text)
                                {
                                    show_message(text.msg,text.status);
                                    
                                }
		};
                
              $('#setDialPlanAndRoute').ajaxForm(options);    
                
	}); 
 function showEditInfoRequest(formData, jqForm, options){
  
  $.validator.setDefaults({
  submitHandler: function() { console.log("submitted!");
   
              
    
    
    }
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
                            
                        },
                        bandwidthLimit:{
                            required: true,
                            maxlength: 4,
                            number:true
                        }
                        
                       },
                 messages: {
			callLimit: {
				maxlength: "please enter no more then 4 digits"
				
			},
                        bandwidthLimit:{
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

//************general setting ***
//$(document).ready(function() { 
    
  	var options = { 
                
                        url:"/controller/adminManageClientCnt.php?action=editGeneralSetting", 
                        type:'POST',        
			dataType: 'json',
			success:     
                                function(text)
                                {
                                show_message(text.msg,text.status);
                                    if(text.status == "success"){
                                       $('#oldmanager').val($('#accountManager').val());
                                    }
                                }
		};
		$('#editgeneralSetting').ajaxForm(options); 
//	}); 
        

//*********

$(document).ready(function() { 
   
          var options = { 
                  dataType:  'json',
                  //target:        '#response',   // target element(s) to be updated with server response 
                  beforeSubmit:  showRequest,  // pre-submit callback 
                  success:       showResponse  // post-submit callback 
          }; 
          $('#chagngeClientPasswordForm').ajaxForm(options); 
  }); 
  function showRequest(formData, jqForm, options) { 
		
        $().ready(function() {
            // validate the comment form when it is submitted	
            $("#chagngeClientPasswordForm").validate({
                    rules: {
                            newPass :{
				required: true,
				minlength: 5,
                                maxlength: 18
			}
                    }
            })
           
        })
		$("#loading").show();
		if($("#chagngeClientPasswordForm").valid())
			return true; 
		else
			return false;
	} 
	// post-submit callback 
	function showResponse(response, statusText, xhr, $form)  {
//            console.log(responseText)
//            console.log(statusText)
		if(response.msg_type == "success"){
		show_message("Successfully Updated","success");
		}
                else{
                    show_message("An Error Occur while update "+response.msg,"error");
                }
		$("#loading").hide();
		//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.'); 
	}
        
  //***************
  
  function getUserSysDetail(userId){
      $.ajax({
                   url : "/controller/adminManageClientCnt.php?action=getUserSysDetail", 
                   type: "POST", 
                   data:{userId:userId},
                   dataType: "json",
                   success:function (text)
                   {
                       var str = ipDetailDesign(text);  
                       $('#systemInfo').html('');
                       $('#systemInfo').html(str);
//                      
                   }
  });
  }
  
  function getUserInfoAcm(managerId){
    $.ajax({
                    url : "/controller/adminManageClientCnt.php?action=getUserInfoAcm", 
                    type: "POST", 
                    dataType: "json",
                    success:function (text)
                    {
                        var str = '';
                        var selected = '';
                         $.each(text, function(key, item ) {
                             var selecMgr = '';
                             if(managerId == key){
                               selected = key  
                               var selecMgr = 'selected = "selected"';   
                             }
                             str += '<option value='+key+' '+selecMgr+'>'+item+'</option>';
                         })
                        $('#accountManager').html(str);
                        $('#accountManager').after('<input type ="hidden" name="oldmanager" id="oldmanager" value="'+selected+'" /> ');
//                        var str = ipDetailDesign(text);  
//                        $('#systemInfo').html('');
//                        $('#systemInfo').html(str);
                        
                    }
    });
    
     $.ajax({
                    url : "/controller/adminManageClientCnt.php?action=getRouteAndDialplanList", 
                    type: "POST", 
                    dataType: "json",
                    success:function (text)
                    {
                        if(text.status == "success"){
                        var str = '';
                         $.each(text.routeData, function(key, item ) {
                            str += '<option value='+key+'>'+item+'</option>';
                         })
                        $('#routeList').html(str);
                        
                        var dialstr = '';
                         $.each(text.dialPlanData, function(key, item ) {
                            dialstr += '<option value='+key+'>'+item+'</option>';
                         })
                        $('#dialPlanList').html(dialstr);
                        }
                    }
                    
     });
  }
  
  function showDialorRouteDiv(ths){
  if($(ths).val()== "dialPlan"){
      $('.selDialorRoute').hide();
      $('#dialPlanListDiv').show();
  }else
    {
      $('.selDialorRoute').hide();
      $('#routeListDiv').show();
    }
  
  }
  function ipDetailDesign(text){
                                var str ='<thead> \
                                <tr> \
                                <th width="20%">Latest Login Time  </th>\
                                <th width="20%">Ips</th>\
                                <th width="20%">Browser</th>\
                                <th width="60%">&nbsp;</th>\
                                        </tr>\
                                        </thead>\
                                        <tbody>\
                              ';
  $.each( text, function(key, item ) {
  str += '<tr class="even">\
                <td>'+item.date+'</td>\
                <td>'+item.ipAddress+'</td>\
                <td>'+item.browser+'</td>\
                <td>&nbsp;</td>\
          </tr>';
                            
        str+='</tbody>';                    
   
    })
    return str;
      
  }
  
  // function use to change user to reseller 
  function changeUserToReseller(userId){
      
       $.ajax({
                   url : "/controller/adminManageClientCnt.php?action=changeUserToReseller", 
                   type: "POST", 
                   data:{userId:userId},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                                             
                   }
       });
      
      
  }
  
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
  
  function listenRemainMinStatus(userId,currentStatus){
    $.ajax({
                   url : "/controller/adminManageClientCnt.php?action=listenRemainMinStatus", 
                   type: "POST", 
                   data:{userId:userId,
                   currStatus:currentStatus},
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


var strt = <?php echo $pageNo; ?>;
    var totalCount = <?php if(isset($transData['totalCount']) && is_numeric($transData['totalCount']) && $transData['totalCount'] != 0) echo $transData['totalCount'];else echo 1; ?>;

    if(strt == undefined || strt == null || strt == '' )
        strt = 1;

    if(totalCount == undefined || totalCount == null || totalCount == '' )
        totalCount = 1;
clientPagination(totalCount,<?php echo $pageNo; ?>,'#pagination',<?php echo $clientId; ?>,0);


	$( "#tabs" ).tabs({
		active: tb,
		create: function(event, ui) {console.log($('.ui-tabs-selected a',this).text());
                console.log(tb+'hello');},
		select: function(event, ui) {
		console.log(ui.tab.innerText)}
	});


</script>