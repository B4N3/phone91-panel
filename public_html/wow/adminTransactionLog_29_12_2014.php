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
$transaction = $trans_obj->getPersonalTransaction($userId,3,NULL,$pageNo ); // 1 for get reseller transaction log 
$transData = json_decode($transaction,TRUE);


//print_r($transData);

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
                    <tbody id="transactionTable">
<!--                      <?php $totalCredit = 0;$totalDebit = 0;
                            foreach( $transData['detail'] as $detail ) {
                                
                                
                                
                                ?> $totalCredit = 0;$totalDebit = 0;
                            foreach( $transData['detail'] as $detail ) {
                              
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
                      </tr>-->
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


function transactionDetailDesign(transDetail)
{
    var str = "";
    
    var clientType = "<?php echo $_SESSION['client_type'];  ?>";
    
    var totalDebit = 0;
    var totalCredit = 0;
    
    var fundTransferObj = {};
    
    $.each(transDetail.detail,function(key,value)
    { 
        
        if(clientType == "2" && value.batchName != "" )
        {
            value.description = value.description+" ";
        }
        
        var closingBalance = value.closingBalance;
        
       
        
        if( value.paymentType == "Fund Transfer")
        {
          
          if(fundTransferObj[value.transactionId] == '0')
          {
              return; 
          }
          
            if( typeof( fundTransferObj[value.transactionId] )  == "undefined")
            {
                fundTransferObj[value.transactionId]  = new Array();
            }
            
            fundTransferObj[value.transactionId].push(value);
            
            
           // console.log(fundTransferObj[value.transactionId].length);
            
            if(fundTransferObj[value.transactionId].length == '2' )
            { 
                var userNameNew = fundTransferObj[value.transactionId]['0']['userName']+"  (fund Transferred: "+fundTransferObj[value.transactionId]['0']['amount']+" "+fundTransferObj[value.transactionId]['0']['currencyName']+" ) ";   
                value.description = "  fund transfer to "+fundTransferObj[value.transactionId]['1']['userName']; 
                value.userName = userNameNew;
            }
            else
            {
                return; 
            }
        }
        
        
        
         str+='<tr class="hvrParent">\n\
                                <td class="alL"><b class="hidden-desktop">Date</b>'+value.date+'</td>\n\
                                <td>\n\
                                      <b class="hidden-desktop">Payment Type</b>\n\
                                      <h3 class="ellp">'+value.userName+'</h3>\n\
                                      <p>'+value.paymentType+'</p>\n\
                                </td>\n\
                                <td class="alR"><b class="hidden-desktop">Amount</b>'+value.amount+" "+value.currencyName+'</td>\n\
                                <td class="alR"><b class="hidden-desktop">Talktime</b>'+value.currentBalance+" "+value.currencyName+'</td>\n\
                                <td><b class="hidden-desktop">Description</b>'+value.description+'</td>\n\
                                <td class="alR">\n\
                                    <b class="hidden-desktop">Debit</b>\n\
                                    <div class="debit fr">'+value.debitActualCurrency+'</div>\n\
                                    <div class="debit fr hvrChild mrR">('+value.debit+' '+value.currencyName+')</div>\n\
                                </td>\n\
                                <td class="alR">\n\
                                	<b class="hidden-desktop">Credit</b>\n\
                                    <div class="credit fr">'+value.creditActualCurrency+'</div>\n\
                                    <div class="credit fr hvrChild mrR">('+value.credit+' '+value.currencyName+')</div>\n\
                                </td>\n\
                                <td class="alR closeBalance"><b class="hidden-desktop">Closing balance</b>';
                                    
                   // s..substring(0, s.length - 1)                 
                                    
                                    value.closingBalance = value.closingBalance;
                                    
                                    
                                    if(value.closingBalance >  0 )
                                    {
                                         value.closingBalance =  value.closingBalance+" dr ";
                                    }
                                    else if(value.closingBalance < 0 )
                                    {
                                        // myString.replace(avoid,'');
                                        
                                        value.closingBalance = value.closingBalance.replace( '-', '')+' cr'; 
                                      //   value.closingBalance = value.closingBalance.substring( 0, closingBalance.length - 1 )+' cr';
                                    }
                                   
                                  totalDebit = parseInt(totalDebit)+  parseInt(value.debitActualCurrency) ;
                                  totalCredit = parseInt(totalCredit)+ parseInt(value.creditActualCurrency);
                                   
                                    //parseInt()
//                                    $totalCredit = $totalCredit + $detail['creditActualCurrency'];
//                                    $totalDebit = $totalDebit + $detail['debitActualCurrency'];
                                 str+=value.closingBalance+'</td></tr><tr class="zerobal hidden-desktop">\n\
                       		<td colspan="100%"></td>\n\
                      </tr>';  
                                
                                
                         
                                                                
     
    });
    
    console.log(fundTransferObj);
    
    
    str+='<tr class="">\n\
                            <td>&nbsp;</td>\n\
                            <td></td>\n\
                            <td></td>\n\
                            <td></td>\n\
                            <td></td>\n\
                            <td class="alR"><span class="debit">'+totalDebit+'</span></td>\n\
                            <td class="alR">'+totalCredit+'</td>\n\
                            <td class="alR closeBalance"></td>\n\
                      </tr>';
    
    //console.log(str);
    
    $("#transactionTable").html(str);
    
    
}


function getAllTransactions()
{
    
        $.ajax({
            url:"http://voice.phone91.com/controller/adminController.php?action=getPersonalTransaction",
            type:"POST",
            dataType:"JSON",data:"userId="+<?php echo $_SESSION['userid']; ?>+"&pageNo="+<?php echo $pageNo; ?>+"",
            success:function(msg)
            { 
               if( msg.status == "success" )
               {
                   
                   /////console.log();
                    transactionDetailDesign($.parseJSON(msg.data));
               }
               else
               {
                   
               }
               
            }
        }); 
}


getAllTransactions();







pagination(<?php if(isset($transData['totalCount'])) echo $transData['totalCount']; else echo 1; ?>,<?php echo $pageNo; ?>,'#pagination');

</script>