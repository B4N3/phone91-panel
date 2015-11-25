<?php
include_once ('definePath.php');
include_once(CLASS_DIR."transaction_class.php");

if (!$funobj->login_validate() || !$funobj->check_reseller() ) {
    $funobj->redirect("index.php");
}

//get page number
if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;

echo $pageNo;
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
  		  <div class="clear" id="srchrow">
<!--                 <label class="transition">
                        <p class="fl">Showing <span>100</span> results by <span>latest</span> whose cost is more than </p>
                        <p class="fl showInfo"> 
                                <span class="ic-8 close"></span>
                                <span class="fl">500</span>
                                <span class="ic-8 arrow"></span>
                                <p class="rowClose fl cp" title="Close"><span class="ic-16 close"></span></p>
                        </p>
               </label>-->
<!--              <div class="sett pr">
                    <div class="cp" onclick="uiDrop(this,'#showTrSett', 'true')"> <i class="ic-16 dropsign"></i> <i class="ic-24 setting"></i> </div>
                    <ul class="dropmenu boxsize ln" id="showTrSett">
                          <li>Export CSV</li>
                          <li>Export PDF</li>
                          <li>Export XlS</li>
                    </ul>
              </div>-->
   		 </div>
         <!--Mid Transactional Conent-->
  		 <div id="resultContainer" class="clear flip-scroll box-">
     		 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="resTrnLogTbl" class="cmntbl alR boxsize">
                    <thead>
                      <tr>
                            <th  width="10%">Date</th>
                            <th width="8%">Type</th>
                            <th  class="alR" width="5%">Amount</th>
                            <th class="alR" width="5%">Balance</th>
                            <th width="25%">&nbsp;</th>
                            <th class="alR"  width="8%">Debit</th>
                            <th  class="alR" width="5%">Credit</th>
                            <th width="7%" >Closing Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $totalCredit=0;$totalDebit=0;
                            foreach($transData['detail'] as $detail){ ?>
                              
                              <tr class="hvrParent">
                                        <td class="alL"><?php echo $detail['date'];?></td>
                                        <td>
                                              <h3 class="ellp"><?php echo htmlentities($detail['name']);?></h3>
                                              <p><?php echo htmlentities($detail['paymentType']);?></p>
                                        </td>
                                        <td  class="alR"><?php if($detail['amount']!=0) echo $detail['amount']; else echo "-"; ?></td>
                                        <td class="alR"><?php echo $detail['currentBalance'];?></td>
                                        <td><?php echo htmlentities($detail['description']);?></td>
                                        <td class="alR">
											<div class="debit fr"><?php echo $detail['debitActualCurrency']; ?></div>
											<div class="debit fr hvrChild mrR">(<?php echo $detail['debit'] . " " . $detail['currencyName']; ?>)</div>
										</td>
                                        <td class="alR">
											<div class="fr"><?php echo $detail['creditActualCurrency']; ?></div>
											<div class="fr hvrChild mrR">(<?php echo $detail['credit'] . " " . $detail['currencyName'];?>)</div>
										</td>
                                        <td class="alR"><?php echo $detail['closingBalance'];?></td>
                              </tr>
                     	 <?php 
                                        $totalCredit = $totalCredit + $detail['creditActualCurrency'];
                                        $totalDebit = $totalDebit + $detail['debitActualCurrency'];
                            } ?>
                      <tr class="zerobal">
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
$( document ).ready(function() {
	$("#resTrnLogTbl tbody tr:visible:even").addClass("even"); 
	$("#resTrnLogTbl tbody tr:visible:odd").addClass("odd");
});

pagination(<?php if(isset($transData['totalCount'])) echo $transData['totalCount']; else echo 1; ?>,<?php echo $pageNo; ?>,'#pagination');

</script>