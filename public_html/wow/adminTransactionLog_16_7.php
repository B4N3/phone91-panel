<?php

include dirname(dirname(__FILE__)) . '/config.php';

include_once(CLASS_DIR."transaction_class.php");

//get page number
if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;

#object of transaction class 
$trans_obj = new transaction_class();
$userId = $_SESSION['userid'];

#call function get Reseller Transactionlog Detial for get all detion of transation 
$transaction = $trans_obj->getPersonalTransaction($userId,1,NULL,$pageNo); // 1 for get reseller transaction log 
$transData = json_decode($transaction,TRUE);


?>
<!--Reseller Trabsactiona Log Main Wrapper-->
<div id="resTrLogWrap" class="commLeftList">
	<!--Inner Wrapper-->
    <div class="clear inner">
         <!--Mid Transactional Conent-->
  		 <div id="resultContainer" class="clear flip-scroll box-">
     		 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="resTrnLogTbl" class="cmntbl boxsize">
                    <thead>
                      	<tr>
                            <th width="10%">Date</th>
                            <th width="8%">Type</th>
                            <th width="5%"><div class="alR">Amount</div></th>
                            <th width="5%"><div class="alR">Talktime</div></th>
                            <th width="25%">Description</th>
                            <th width="8%"><div class="alR">Debit</div></th>
                            <th width="5%"><div class="alR">Credit</div></th>
                            <th width="7%" class="alR">Closing Balance</th>
                      	</tr>
                    </thead>
                    <tbody>
                      <?php $totalCredit = 0;$totalDebit = 0;
                            foreach( $transData['detail'] as $detail ) { ?>
                              
                          <tr class="hvrParent">
                                <td class="alL"><b class="hidden-desktop">Date</b><?php echo $detail['date'];?></td>
                                <td>
                                	  <b class="hidden-desktop">Payment Type</b>
                                      <h3 class="ellp"><?php echo htmlentities($detail['userName']);?></h3>
                                      <p><?php echo htmlentities($detail['paymentType']);?></p>
                                </td>
                                <td class="alR"><b class="hidden-desktop">Amount</b><?php if($detail['amount']!=0) echo $detail['amount']; else echo "-"; ?></td>
                                <td class="alR"><b class="hidden-desktop">Talktime</b><?php echo $detail['currentBalance'];?></td>
                                <td><b class="hidden-desktop">Description</b><?php echo htmlentities($detail['description']); if($_SESSION['client_type'] == 2 && $detail['batchName'] != '') echo ' ,Batch Name:'.$detail['batchName']; ?></td>
                                <td class="alR">
                                	<b class="hidden-desktop">Debit</b>
                                    <div class="debit fr"><?php echo $detail['debitActualCurrency']; ?></div>
                                    <div class="debit fr hvrChild mrR">(<?php echo $detail['debit'] . " " . $detail['currencyName']; ?>)</div>
                                </td>
                                <td class="alR">
                                	<b class="hidden-desktop">Credit</b>
                                    <div class="credit fr"><?php echo $detail['creditActualCurrency']; ?></div>
                                    <div class="credit fr hvrChild mrR">(<?php echo $detail['credit'] . " " . $detail['currencyName'];?>)</div>
                                </td>
                                <td class="alR closeBalance"><b class="hidden-desktop">Closing balance</b><?php 
                                $closeBal = round($detail['closingBalance'],2);
                                if($closeBal > 0)
                                  echo $closeBal.' Dr';
                                else if($closeBal < 0)
                                { 
                                  $closeBalText = substr($closeBal, 1,strlen($closeBal)-1);
                                  echo $closeBalText.' Cr';


                                }
                                else 
                                  echo 0;
                                ?></td>
                          </tr>
                     	 <?php 
                                        $totalCredit = $totalCredit + $detail['creditActualCurrency'];
                                        $totalDebit = $totalDebit + $detail['debitActualCurrency'];
                            } ?>
                      <tr class="zerobal hidden-desktop">
                       		<td colspan="100%"></td>
                      </tr>
                      <tr class="">
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="alR"><span class="debit"><?php echo $totalDebit ;?></span></td>
                            <td class="alR"><?php echo $totalCredit; ?></td>
                            <td class="alR closeBalance"></td>
                      </tr>
               </tbody>
     		 </table>
   		 </div>
         <!--//Mid Transactional Conent-->
  </div>
        <div id="pagination" class="mrT1"></div>
  <!--//Inner Wrapper-->
  <!--/end call log inner--> 
</div>
<!--//Reseller Trabsactiona Log Main Wrapper-->
<style type="text/css">#resultContainer{overflow:auto;}</style>

<script type="text/javascript">

dynamicPageName('Transactional Log');

$( document ).ready(function() 
{
	$("#resTrnLogTbl tbody tr:visible:even").addClass("even"); 
	$("#resTrnLogTbl tbody tr:visible:odd").addClass("odd");
});


pagination(<?php if(isset($transData['totalCount'])) echo $transData['totalCount']; else echo 1; ?>,<?php echo $pageNo; ?>,'#pagination');

</script>