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
//$transaction = $trans_obj->getPersonalTransaction($userId,3,NULL,$pageNo ); // 1 for get reseller transaction log 
//$transData = json_decode($transaction,TRUE);


//print_r($transData);

?>
<!--Reseller Trabsactiona Log Main Wrapper-->
<div id="resTrLogWrap" class="commLeftList">
	<!--Inner Wrapper-->
    <div class="clear inner">
        <div id="filter">
            <input type="radio" name="filterBy" checked value="all"> All Transaction
            <input type="radio" name="filterBy" value="signUp"> SingUp
            <input type="radio" name="filterBy" value="Bulk Add"> Batch User
            <input type="radio" name="filterBy" value="Calling Card"> Calling Card
            <input type="radio" name="filterBy" value="Share Talktime"> Share Talktime
            <input type="radio" name="filterBy" value="Earn Credit"> Earn Credit
            <input type="radio" name="filterBy" value="RefererBalance"> Referer Balance
            <input type="radio" name="filterBy" value="Pin"> Recharge by pin
            <input type="radio" name="filterBy" value="stripe"> stripe
            <input type="radio" name="filterBy" value="cashu"> cashU
            <input type="radio" name="filterBy" value="paypal"> paypal
            
            <input class="mrT2 btn btn-medium btn-primary" value="Go" type="submit" title="Go" onclick="getAllTransactions(1)">
        </div>
      
       
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
                            //foreach( $transData['detail'] as $detail ) {
                                
                                
                                
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
                            //} ?>
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
           <div id="pagination" style="padding-left: 39px;position: relative;top: -47px ;" class="mrT1"></div>
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


function getAllTransactions(pageNo)
{
    
    
    var filterBy = $("input[name=filterBy]:checked").val();
    
    
        $.ajax({
            url:"http://voice.phone91.com/controller/adminController.php?action=getPersonalTransaction",
            type:"POST",
            dataType:"JSON",
            data:"userId="+<?php echo $_SESSION['userid']; ?>+"&pageNo="+pageNo+"&filterBy="+filterBy,
            success:function(msg)
            { 
               
               if( msg.status == "success" )
               {
                   
                  // console.log(msg.data.totalCount);
                    transactionDetailDesign(msg.data);
                    $("#resTrnLogTbl tbody tr:visible:even").addClass("even"); 
                    $("#resTrnLogTbl tbody tr:visible:odd").addClass("odd");
//                    console.log(eval(msg.data.totalCount));
//                    pagination(msg.data.totalCount)) echo $transData['totalCount']; else echo 1; ?>,pageNo,'#pagination');
                    allRoutePagination(msg.data.totalCount,pageNo,'#pagination');
               }
               else
               {
                   
               }
               
            }
        }); 
}


getAllTransactions(1);



function allRoutePagination(count,strt,divs)
{
//     if(type == 1){
//         type ='batchId';
//     }else
//         type ='clientId';
//    



    if(strt == undefined || strt == 0 || strt== "")
        strt=1;
    
    if(count == undefined || count == 0 || count== "")
        count = 1;
    
    
        //code for pagination
	if(count > 1 ){
            
            
		$(divs).paginate({
			count       : count,
			start       : strt,
			display     : 10,
			border : false,
			text_color: '#000',
			background_color: '#ddd',
			text_hover_color: '#fff',
			background_hover_color: '#333',
			images                  : false,
			mouse                   : 'press',
			page_choice_display     : true,
			show_first              : true,
			show_last               : true,
			rotate					: false,
			item_count_display      : true,						
			item_count_total : count,
			onChange                : function(page){
                            
                         getAllTransactions(page);

//                            if(routeId == undefined || routeId == null )
//				window.location.href= window.location.href.split('?')[0]+'?pageNo='+page;
//                            else
//                               window.location.href= window.location.href.split('?')[0]+'?pageNo='+page+'&routeId='+routeId+'&tb=0';
                        }
                                    
		});
	}
        
}




//pagination(<?php if(isset($transData['totalCount'])) echo $transData['totalCount']; else echo 1; ?>,<?php echo $pageNo; ?>,'#pagination');

</script>

<style>
    #filter{margin-bottom:10px}
    #filter input{margin-left:10px;}
</style>