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

$unChecked = 'disabledR';
if($listenStatus)
    $unChecked = '';

//get checkbox state for user convert user to reseller
$userTypeChecked = 'disabledR';
$changeUserType = 2;
if($userInfo['type'] == 2)
{
  $userTypeChecked = '';
  $changeUserType = 3;
}
  


$userInfo1 = $funobj->getUserInformation($clientId,1);

$hideEditInfo = '';
if(is_array($userInfo) && $userInfo['deleteFlag'] != 0)
{
  $hideEditInfo = 'dn';
  $buttonClass = 'btn-primary';
  $bType = 'unblock';
  $buttonText = 'Retrive Account';
  $deletedStyle = 'pointer-events:none;';

}
else
{
  $buttonClass = 'btn-danger';
  $bType = 'block';
  $buttonText = 'Delete Account';
  $deletedStyle = 'pointer-events:auto;';
}
  
  
?>
<!--Tabs Content-->
<div class="resellerMCHead"><?php echo $userInfo['userName'];?> <!--<span>Currency</span> <span>USD</span>--></div>
<div id="tabs">
    <ul>
            <li onclick ="getTransactionLog(<?php echo $fromUser;?>,<?php echo $clientId;?>)"><a href="#tabs-1"><span class="hideInTablet">Transaction</span> Log</a></li>
            
            <li class="editInfo <?php echo $hideEditInfo;?>"><a href="#tabs-2"><span class="hideInTablet">Edit</span> Fund</a></li>
           
            <li><a href="#tabs-3"><span class="hideInTablet">Edit</span> Info</a></li>
            <li><a href="#tabs-4"><span class="hideInTablet">Change</span> Password</a></li>
    </ul>
    
	<!--1 st Tab Content-->
    <div id="tabs-1" class="tabs">
    	<div id="transactionTable" class="tablflip-scroll pr">
        	<div class="fxhdr"></div>
      		  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="clientTrstable" class="cmntbl alR boxsize">
        	<thead>
                <tr>
                    <th width="13%"><div class="fxhdrtxt">Date</div></th>
                    <th width="9%"><div class="fxhdrtxt">Type</div></th>
                    <th width="10%"><div class="fr"><span class="fl"><div class="fxhdrtxt">Talktime</div></span><i title="The talktime with which  <?php echo $userInfo['userName'];?> will be able to make calls. " class="ic-16 helpW"></i></div></th>
                    <th width="13%"><div class="fr"><span class="fl"><div class="fxhdrtxt">Total talktime</div></span><i title="The total talktime  <?php echo $userInfo['userName'];?>  has received/given till date.. " class="ic-16 helpW"></i></div></th>
                    <th width="23%" class="ellpTh"><div class="fxhdrtxt">Description</div></th>
                    <th width="9%"><div class="fr"><span class="fl"><div class="fxhdrtxt">Debit</div></span><i title="Money due from  <?php echo $userInfo['userName'];?>." class="ic-16 helpW"></i></div></th>
                    <th width="9%"><div class="fr"><span class="fl"><div class="fxhdrtxt">Credit</div></span><i title="Money you have received from <?php echo $userInfo['userName'];?>." class="ic-16 helpW"></i></div></th>
                    <th width="14%"><div class="fr"><span class="fl"><div class="fxhdrtxt">Closing Balance</div></span><i title="Debit (Dr.) closing balance indicates the money you need to take from <?php echo $userInfo['userName'];?>, while Credit (Cr.) closing balance indicates the services due to    <?php echo $userInfo['userName'];?> with the money received." class="ic-16 helpW"></i></div></th>
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
                    <td><div class="fl ellp"><?php echo htmlentities($trans['description']); ?></div> </td>
                    <td class="alR"><div class="debit fr" > <?php echo $trans['debitActualCurrency']; ?></div><div class="debit dn hvrChild fr mrR">(<?php echo $trans['debit'] . " " . $trans['currencyName']; ?>)</div></td>
                    <td class="alR"><div class="credit fr"><?php echo $trans['creditActualCurrency']; ?></div><div class="credit dn hvrChild fr mrR">(<?php echo $trans['credit'] . " " . $trans['currencyName'];?>)</div></td>
                    <td class="alR closeBalance"><div class="fl"><?php $closeBal = round($trans['closingBalance'],2);
                      if($closeBal > 0)
                        echo $closeBal.' Dr';
                      else if($closeBal < 0)
                      { 
                        $closeBalText = substr($closeBal, 1,strlen($closeBal)-1);
                        echo $closeBalText.' Cr';


                      }
                      else 
                        echo 0;


                     ?></div></td>
                </tr>
                <?php
						$totalCredit = $totalCredit + $trans['creditActualCurrency'];
						$totalDebit = $totalDebit + $trans['debitActualCurrency'];
						$totalClosingBalance = $trans['closingBalance'];
                }
                $closeBalance = round($totalClosingBalance,2);
                      if($closeBalance > 0)
                      {
                        $totalClosingBalance =  'You need to take '.$closeBalance;
                        $closeClass = 'green';
                      }
                      else if($closeBalance < 0)
                      { 
                        $closeBalText = substr($closeBalance, 1,strlen($closeBalance)-1);
                       $totalClosingBalance= 'You need to give service of '.$closeBalText;
                       $closeClass = 'debit';

                      }
                      else 
                      {
                        $totalClosingBalance = 0;
                        $closeClass = '';
                      }
                        


                 ?>
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
                    <td class="alR closeBalance <?php echo $closeClass;?>"><?php echo $totalClosingBalance; ?></td>
                </tr>
            </tbody>
        </table>
        </div>
        <div id="pagination" class="mrT1"></div>
       <!-- Bootm Actions Wrapper-->
        <h3 class="mrT3 mrB">Add your money transactions here!</h3>
        <div class="transMoney clear fl">
        
        	<div class="transHd clear">
            	<div class="fl">
                	<input class="mrR fl addReduceRadio" type="radio" name="money" id="received" value="add"/><label for="received" class="cp fl">Received Money</label><i title="Money you have received from <?php echo $userInfo['userName'];?>." class="ic-16 help fl mrL mrR2"></i>
                </div>
            	<div class="fl">
                	<input class="mrR fl addReduceRadio" type="radio" name="money" id="giving" value="reduce"/><label for="giving" class="cp fl">Giving Money</label>
                	<i title="Money you are giving to <?php echo $userInfo['userName'];?>." class="ic-16 help fl mrL"></i>
                </div>
            </div>
            
            <div id="addReduceFund" class="transInner" style="<?php echo $deletedStyle;?>">
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
                
                <div class="actionDiv mDevice">
                    <div class="sporow clear fields">
                        <!--<span class="funder">
                            <label onclick="toggleState($(this),'Trans');" for="changefunder" class="ic-32 bigfadder cp"></label>
                            <input type="checkbox" id="changefunderTrans" style="display:none" checked="checked" value="add" />
                        </span>-->
                        <p class="mrB">&nbsp;</p>
                        <input type="text" name="transAmount" id="transAmount" onblur="checkNumberValidation(this);" placeholder="Amount"/>
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
                    <input type="button" class="btn btn-mini btn-blue" name="additionalTrans" id="additionalTrans" value="ADD TRANSACTION" onclick="addAdditionTransaction();"/>
                    </div>
                </div>
            </div>
            
        </div>
    	<!-- //Bootm Actions Wrapper-->
    </div>
    
    <div id="tabs-2" class="tabs">
        <form id="editFundform" class="formElemt">
                
                <div class="innerSpace clear">
                        <p>Current talktime:</p>
                        <h3 class="mrB2 userBalance"><span class="<?php echo $clientId;?>changeBal"><?php echo round($UserBalance,3); ?> </span> <?php echo "  ".$currency = $funobj->getCurrencyViaApc($userInfo['currencyId'],1); ?></h3>
                        
                        <div class="transMoney clear fl">
                        	<div class="transHd clear">
            					<div class="fl">
                                	<input class="mrR fl" type="radio" name="changefunderEditFund" id="received2" value="add"/><label for="received2" class="cp fl">Received Money</label><i title="Money you have received from <?php echo $userInfo['userName'];?>" class="ic-16 help fl mrL mrR2"></i>
                                </div>
            					<div class="fl">
                                	<input class="mrR fl" type="radio" name="changefunderEditFund" id="giving2" value="reduce"/><label for="giving2" class="cp fl">Giving Money</label>
                                	<i title="Money you are giving to <?php echo $userInfo['userName'];?>" class="ic-16 help fl mrL"></i>
                                </div>
                            </div>
                            
                            <div class="transInner clear">
                                  <div class="actionDiv">  
                                        <div class="fields">
                                            <label>Payment Type<sup>*</sup>
                                            </label>
                                            <select onchange="showNext(this)" id="pType" name="pType">
                                                <option value="">Select</option>
                                                <option id="advance" value="prepaid" >Prepaid</option>
                                                <option id="partial" value="partial" >Partial</option>
                                                <option id="credit"  value="postpaid" > Postpaid</option>
                                                
                                            </select>
                                        </div>
                                  </div> 
                                    
                                  <div class="actionDiv" id="cashMemoBank">
                                      <div class="fields">
                                           <label>Paid via<sup>*</sup></label>
                                           <select name="fundPaymentType" id="fundPaymentType">
                                                    <option value="Cash">Cash</option>
                                                    <option value="Memo">Memo</option>
                                                    <option value="Bank">Bank</option>
                                                    <option value="Other">Other</option>
                                           </select>
                                      </div>
                                  </div>
                                  
                                  <div class="actionDiv">
                                      <div class="dn fields" id="otherPaymentType">
                                           <label>Enter Type</label>
                                           <input type="text" name="otherPaymentType"/>
                                      </div>
                                  </div>
                                    
                                  <div id="partialWrap" class="actionDiv dn">
                                      <div class="sporow fields">
                                          <label>Partial Amount</label>
                                          <input type="text" id="partialAmt" name="partialAmt" placeholder="Amount"/>
                                          <select name="partialCurrency" id="partialCurrency" class="currencyList">
                                                <!--  do not remove currencylist class -->
                                          </select>
                                      </div>                                           
                                  </div>
                              </div>
                          	
                              <div  class="transInner clear">
                                  <div class="actionDiv">
                                       <div class="fields sporow">   
                                            <label><span class="fl mrB">Recharge amount<sup>*</sup></span>
                                            	<i title="Enter the amount according to which you are going to add/reduce balance in <?php echo $userInfo['userName'];?> account. " class="ic-16 help"></i>
                                            </label>
                                            
<!--                                            <span class="funder">
                                                    <label onclick="toggleState($(this),'EditFund');" for="changefunder" class="ic-32 bigfadder cp"></label>
                                                     <input type="checkbox" id="changefunderEditFund" name="changefunderEditFund" style="display:none" checked="checked" value="add" />
                                            </span>-->
                                            
                                            <input type="hidden" name="toUserEditFund" value="<?php echo $clientId; ?>" id="toUserEditFund"/>
                                            <input type="text" placeholder="Amount" id="fundAmount" name="fundAmount"/>
                                            <select name="fundCurrency" id="fundCurrency" class="currencyList">
                                                      <!--  do not remove currencylist class -->                                                   
                                            </select>
                                      </div>
                                  </div>
                                  
                                  <div id="talktime" class="actionDiv"> 
                                      <div class="fields pr"> 
                                            <label class="clear"><p class="fl">Talktime<sup>*</sup></p>
                                            	<i title="Enter the talktime you wish to add/reduce in <?php echo $userInfo['userName'];?> account with according to the recharge amount." class="ic-16 help"></i>
                                            </label>
                                            <input maxlength="8" type="text" id="balance" name="balance" class="clientBal"/>
                                            <span><?php echo $userCurrency; ?></span>
                                      </div>
                                  </div>
                                <!--<select name="fundPaymentType" style="width:120px;">
                                    <option value ="cash">cash</option>
                                    <option value ="bank">bank</option>
                                    <option value ="memo">memo</option>
                                    <option value ="other">other</other>
                                </select>-->
                                <!--<input type="text" id="otherPaymentType" name="otherPaymentType"/>--> 
                                <div class="actionDiv">
                                    <div class="fields">
                                            <label>Description</label>
                                            <textarea id="fundDescription" name="fundDescription"></textarea>
                                    </div>
                                </div>
                            </div>
                        <!--<a class="mrT2 btn btn-medium btn-primary" onclick="UserEditFund();">Save Changes</a>-->
                			<div class="transInner mrB2">
                            	<input class="mrT btn btn-mini btn-blue"  type="submit" name="save" id="save" value="Done"/>
                            </div>
        	
        	</div><!--end of transmoney container-->
        </div><!--end of innerspace div-->
        </form>
    </div>
    <!--//2 nd Tab Content-->
   
    <!--3rd Tab Content-->
    <div id="tabs-3" class="tabs displayEditInfo"><!--display number and email info-->
    		<div id="edInfo" class="fl" style="<?php echo $deletedStyle;?>">
           			<!--<div class="fields mainUsern">
                            <label>Username</label> 
                            <?php // var_dump($userInfo);//array(9) { ["userId"]=> string(5) "31159" ["name"]=> string(12) "sudhirpandey" ["userName"]=> string(12) "sudhirpandey" ["planName"]=> string(7) "Testing" ["balance"]=> string(8) "888.1212" ["isBlocked"]=> string(1) "1" ["type"]=> string(1) "3" ["currencyId"]=> string(1) "2" ["resellerId"]=> string(5) "30618" } ?>
                         <h3><?php //echo $userInfo['userName'];?></h3>
                   </div>-->
                   
            <form id="editClientInfo">
               <!--<div class="fields">
               		<label>Name</label>
                	<input type="text" name="clientName" value="<?php echo $userInfo['name'];?>" />
            </div>-->
            
            <div class="fields">
            		<input type="hidden" name="clientId" value="<?php echo $clientId;?>" />
         		  	 <h3 class="mrB">Contact Details:</h3>
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
           
           <div class="clear">
               <div class="actionDiv">
                    <div class="fields">
                        <label><span class="fl mrB">Call Limit</span> <i class="ic-16 help mrL fl" title="The maximum concurrent calls <?php echo $userInfo['userName'];?> will be able to make at one time."></i></label>
                        <input type="text" name="callLimit" id="callLimit" value="<?php echo $userInfo['callLimit'];?>" />
                        <input type ="hidden" name="oldCallLimit" id="oldCallLimit" value="<?php echo $userInfo['callLimit'];?>"/>            		</div>
               </div>
               
               <div class="actionDiv">
                   <div class="fields">
                        <label>Tariff</label>
                        <select name="currenctTariff" id="currenctTariff" class="selPlan">
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
                </div>
                
                <div class="actionDiv">
                   <div class="fields">
                   		<p class="additionalTransLbl mrT">&nbsp;</p>
                		<input class="btn btn-mini btn-blue" value="Save" type="submit"/>
                   </div>
                </div>
            </div>
            
            <table class="mrT2 mrB2" border="1" bordercolor="#ddd">
            	<tr>
					<?php //if($userInfo['type'] == 3 || $userInfo['type'] == 0){?>
                    <td class="pd"><!-- <input type="checkbox" <?php echo $userTypeChecked; ?>  onclick ="changeUserToReseller(<?php echo $clientId;?>);" > -->Convert to reseller
                    </td>
                    <td class="pd"><label class="ic-sw enabledR cp <?php echo $userTypeChecked; ?>" id="changeToReseller" onclick="changeUserToReseller(<?php echo $clientId;?>,<?php echo $changeUserType; ?>);"></label></td>
                </tr>
                <tr>
                	<?php {?>
                    <td class="pd">
                        <!-- <input type="checkbox" id="listenStatus"  onchange ="listenRemainMinStatus(<?php echo $clientId;?>,<?php echo $listenStatus; ?>);" > -->
                        Listen the remaining time during the call.
						<?php } ?>
                    </td>
                    <td class="pd"><label  id="listenStatus" class="ic-sw enabledR cp <?php echo $unChecked; ?>" onclick="listenRemainMinStatus(<?php echo $clientId;?>,<?php echo $listenStatus; ?>);"></label></td>
                </tr>
                <tr>                

                    <td class="pd"><!-- <input type="checkbox" name="changeSip"  onclick="" id="changeSip" value="" class="mrT03 fl" /> -->Enable mobile or desktop dialers
                    </td>
                    <td class="pd"><label name="changeSip" id="changeSip" class="ic-sw enabledR cp <?php $check= ($userInfo['sipFlag'] == 1? "":"disabledR");  echo $check; ?>" onclick="changeSipSetting(<?php echo $_REQUEST['clientId']; ?>)"></label></td>
				</tr>                    
            </table>
            
            </form>
            
    	</div>
        <!--End edit info div-->
        
        <input type="button" id="deleteButton" onclick="setdeleteFlag(<?php echo $clientId;?>,'<?php echo $bType;?>');" value="<?php echo $buttonText;?>" class="btn btn-medium <?php echo $buttonClass;?> fr">
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
                    </label>
            </div>
         <input class="mrT2 btn btn-mini btn-blue" value="Done" type="submit" />
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
      if($('#changeSip').hasClass('disabledR'))
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
                if(text.status=='success')
                {
                    if($('#changeSip').hasClass('disabledR'))
                    {
                         $('#changeSip').removeClass('disabledR');
                    }
                    else
                    {
                          $('#changeSip').addClass('disabledR');
                    }


                }
                 
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
function showNext(ts){	
	var val = $(ts).val();
    if(val == 'partial')
		$( "#partialWrap" ).show();
    else
        $( "#partialWrap" ).hide();
   
    if(val == "postpaid"){
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
$(document).ready(function() {
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

function checkNumberValidation(ths){
    var reg2 = /^[0-9]+(\.[0-9]{1,4})?$/;
    var amount = $(ths).val();
    if(!reg2.test(amount))
    {
      $(ths).removeClass("valid").addClass("error");
    }else
      $(ths).removeClass("error").addClass("valid");
   
}
function addAdditionTransaction()
{
    $('#additionalTrans').attr('disabled' , 'disabled');
    
 // status variable use for status of transaction add / reduce
 if($("input[type='radio'].addReduceRadio").is(':checked')) {
        var status = $("input[type='radio'].addReduceRadio:checked").val();
        
 }else
 {
      show_message("please select option either Received Money or Giving Money","error");
      $('#additionalTrans').removeAttr('disabled');
      return false;
 }
 
 // var transType use for transaction type (cash,bank,voip91,other).
 var transType = $('#transType').val();
 var description = $('#description').val();
 var amount = $('#transAmount').val();
 var toUser = $('#toUser').val();
 var currency = $('#currency').val();
 var transTypeOther = $('#transTypeOther').val();
 var reg=/^[a-zA-Z0-9\@\_\-\s]+$/;
 var reg2 = /^[0-9]+(\.[0-9]{1,4})?$/;
 
 if(!reg.test(transType))
 {
      show_message("Please enter a valid transaction type.","error");
      $('#additionalTrans').removeAttr('disabled');
      return false;
 }
 
 if(!reg.test(description))
 {
      show_message("Please enter a valid description.","error");
      $('#additionalTrans').removeAttr('disabled');
      return false;
 }
        
 if(!reg2.test(amount))
 {
      show_message("Please enter a valid amount.","error");
      $('#additionalTrans').removeAttr('disabled');
      return false;
 }
 
 if(amount.length > 7) 		
 {
      show_message("The amount should not be more than 7 digits.","error");
      $('#additionalTrans').removeAttr('disabled');
      return false;
 }
 
 if(transTypeOther.length > 20)
 {
      show_message("Please do not enter more than 20 characters.","error");;
      $('#additionalTrans').removeAttr('disabled');
      return false;
 }
 
 
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
                                }
                                 $('#additionalTrans').removeAttr('disabled');
                            }
                        })
                 
           
       
       
   
      
             
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
                    <th width="13%">Date</th>\
                    <th width="9%">Type</th>\
                    <th width="10%"><div class="fr"><span class="fl">Talktime</span><i title="The amount of calling funds added/given in this users/resellers account with the money received/given." class="ic-16 helpW"></i></div></th>\
                    <th width="13%"><div class="fr"><span class="fl">Total talktime</span><i title="Your overall calling balance till date. This does not include the balance you have utilized." class="ic-16 helpW"></i></div></th>\
                    <th width="23%" class="ellpTh">Description</th>\
                    <th width="9%"><div class="fr"><span class="fl">Debit</span><i title="Money you need to take from <?php echo $userInfo['userName'];?>" class="ic-16 helpW"></i></div></th>\
                    <th width="9%"><div class="fr"><span class="fl">Credit</span><i title="Money you have received from <?php echo $userInfo['userName'];?>" class="ic-16 helpW"></i></div></th>\
                    <th width="14%"><div class="fr"><span class="fl">Closing Balance</span><i title="Positive closing balance indicates the money you need to take from <?php echo $userInfo['userName'];?>, while negative closing balance indicates the services due to <?php echo $userInfo['userName'];?> with the given money." class="ic-16 helpW"></i></div></th>\
                 </tr>\
            </thead>\
            <tbody>';    
 var totalCredit=0;var totalDebit=0;var totalClosingBalance=0;

 var closeBal;
 var closeBalText;
 var tempStr;
 $.each( text, function(key, item ) {
     
 if(item.amount != 0) var amount = item.amount; else var amount = '-';

 closeBal = parseFloat(item.closingBalance).toFixed(2);

 if(closeBal > 0)
  closeBalText = closeBal+' Dr';
 else if(closeBal < 0)
 {
  tempStr = new String(closeBal);
  closeBalText = tempStr.substring(1)+' Cr';
 }
 else
  closeBalText = 0;
   
  str += '<tr class="hvrParent">\
                    <td>'+item.date+'</td>\
                    <td>'+item.paymentType+'</td>\
                    <td class="alR">'+amount+'</td>\
                    <td class="alR">'+parseFloat(item.currentBalance).toFixed(2)+'</td>\
                    <td>'+item.description+'</td>\
                    <td class="alR"><div class="debit dn hvrChild fr">('+item.debit+' '+item.currencyName+')</div><div class="debit fr mrR" >'+item.debitActualCurrency+'</div></td>\
                    <td class="alR"><div class="dn hvrChild fr">('+item.credit+' '+item.currencyName+')</div><div class="fr mrR">'+item.creditActualCurrency+'</div></td>\
                    <td class="alRcloseBalance">'+closeBalText+'</td>\
                </tr>';
                        
    totalCredit = Number(totalCredit) + Number(item.credit);
    totalDebit = Number(totalDebit) + Number(item.debit);
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
                                  $('#editFundform')[0].reset();
                                    var clientId = $('#toUserEditFund').val();
                                   
                                   $('.'+clientId+'changeBal').html(parseFloat(text.balance).toFixed(2));
                                }
                                 $('#save').removeAttr('disabled');
                                }
		};
		$('#editFundform').ajaxForm(options); 
	}); 
        
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
                        },
                        pType:'required',
                        partialAmt :{
                            required: true,
                            maxlength: 8,
                            number:true
            }
                        
                       }
        });

 //validate all fields
    $('#pType,#fundAmount').blur(function(){

        $("#"+$(this).attr('id')).valid();
    }); 

        
    })

   
function showEditFundRequest(formData, jqForm, options){
	console.log(0)

 $.validator.setDefaults({
  submitHandler: function() {
      $('#save').attr('disabled','disabled');
  }
  });  
    


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
                            number:true,
                            max:50 
                        },
                        currenctTariff :{
                            required: true
                            
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

                          if($('#listenStatus').hasClass('disabledR'))
                          {
                               $('#listenStatus').removeClass('disabledR');
                          }
                          else
                          {
                                $('#listenStatus').addClass('disabledR');
                          }
                           //$('#listenRemainingMin').hide();
                       }
                                     
                   }
       });
}

function changeUserToReseller(userId,userType){
      
       $.ajax({
                   url : "controller/adminManageClientCnt.php?action=changeUserToReseller", 
                   type: "POST", 
                   data:{userId:userId,
                        userType:userType},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                          if($('#changeToReseller').hasClass('disabledR'))
                          {
                              $('.userType'+userId).removeClass('gold').addClass('green').text('user');
                              $('#changeToReseller').removeClass('disabledR');
                          }
                        
                          else
                          {
                            $('.userType'+userId).removeClass('green').addClass('gold').text('reseller');
                                $('#changeToReseller').addClass('disabledR');
                          }
                            


                           //$('#userToReseller').hide();
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
    $('#newPass').val(Math.random().toString(36).slice(-8));
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
var _N = $('.ui-tabs-nav').outerHeight(true)

_H = $('#rightsec').height();
$('.tabs').css({height: _H - _N -60, 'overflow':'auto'});

//initialise tiptip for help icon
$(".helpW, .help").tipTip();
</script>