<!--Reseller Trabsactiona Log Main Wrapper-->
<div id="resTrLogWrap" class="commLeftList">
	<!--Inner Wrapper-->
    <div class="clear inner">
         <!--Mid Transactional Conent-->
  		      <div class="tablflip-scroll" id="transactionTable">
      		   <table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize" id="clientTrstable">
                <thead>
                    <tr>
                        <th >Date</th>
                        <th >A/C Manager</th>
			<th >Route</th>
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
                  <?php  //foreach($transData['detail'] as $trans) { ?>
                         <tr class="hvrParent">
                                <td><?php //echo $trans['date'];?></td>
                                <td><?php //echo $trans['name'];?></td>
                                <td><?php //echo htmlentities($trans['paymentType']);?></td>
                                <td class="alR"><?php //if($trans['amount'] != 0) echo $trans['amount']; else echo "-"; ?></td>
                                <td class="alR"><?php //echo round($trans['currentBalance'],3); ?></td>
                                <td><?php //echo htmlentities($trans['description']); ?></td>
                                <td class="alR">
                                	<div class="debit fr"><?php //echo $trans['debitActualCurrency']; ?></div>
                                    <div class="hvrChild fr mrR">(<?php //echo $trans['debit'] . " " . $trans['currencyName']; ?>)</div>
								</td>
                                <td class="alR">
                                	<div class="debit fr"><?php //echo $trans['creditActualCurrency']; ?></div>
                                    <div class="hvrChild fr mrR">(<?php //echo $trans['credit'] . " " . $trans['currencyName'];?>)</div>
                                </td>
                                <td class="alR closeBalance"><?php //echo round($trans['closingBalance'],2); ?></td>
              		  </tr>
                  <?php //} ?>				
                        <tr class="zerobal">
                		    <td colspan="100%"></td>
               			 </tr>
                           
            </tbody>
        </table>
           </div>
        <div id="pagination" style="padding-left: 39px;position: relative;top: -47px ;" class="mrT1"></div>
       
  <!--//Inner Wrapper-->
  <!--/end call log inner--> 
</div>
<!--//Reseller Trabsactiona Log Main Wrapper-->
<style type="text/css">#resultContainer{overflow:auto;}</style>
<script type="text/javascript">
//dynamicPageName('Transactional Log');
//$( document ).ready(function() {
//	$("#resTrnLogTbl tbody tr:visible:even").addClass("even"); 
//	$("#resTrnLogTbl tbody tr:visible:odd").addClass("odd");
//});

//pagination(<?php //if(isset($transData['totalCount'])) echo $transData['totalCount']; else echo 1; ?>,<?php //echo $pageNo; ?>,'#pagination');


function designTransactionLog(text){

 console.log(text);
 var str = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize" id="clientTrstable">\
                <thead>\
                    <tr>\
                        <th width="10%">Date</th>\
                        <th width="15%">A/C Manager</th>\
			<th>Route Name</th>\
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
  
  
  if(text.detail == null || text.detail == undefined)
      return str;
  
 if(text.totalCount != 0) 
 $.each( text.detail, function(key, item ) {
  str += '<tr class="hvrParent">\
                    <td>'+item.date+'</td>\
                    <td>'+item.userName+'</td>\
		    <td>'+item.routeName+'</td>\
                    <td>'+item.paymentType+'</td>\
                    <td class="alR">'+item.amount+'</td>\
                    <td class="alR">'+parseFloat(item.currentBalance).toFixed(2)+'</td>\
                    <td>'+item.description+'</td>\
                    <td class="alR">\
						<div class="debit fr">'+ item.debitActualCurrency+'</div>\
						<div class="fr hvrChild mrR">('+ item.debit +' '+item.currencyName +')</div>\
					</td>\
                    <td class="alR">\
						<div class="debit fr">'+parseFloat(item.creditActualCurrency).toFixed(2)+'</div>\
						<div class="fr hvrChild mrR">('+ item.credit +' '+item.currencyName +') </div>\
					</td>\
                    <td class="alRcloseBalance">'+parseFloat(item.closingBalance).toFixed(2)+'</td>\
                </tr>';
   
    });

//    str+='<tr class="zerobal">\
//                    <td colspan="100%"></td>\
//                </tr>\
//    <tr><td></td>\
//    <td></td>\
//    <td></td>\
//    <td></td>\
//    <td></td>\
//    <td></td>\
//    <td>'+text.totalDebit+'</td>\
//    <td>'+text.totalCredit+'</td>\
//    <td>'+text.totalClosingAmt+'</td>\
//    </tr>';
//    
    str+= '</tbody></table>';
     
     return str;
    
}

getRouteAllTransactionLog(1);

function getRouteAllTransactionLog(pageNo){
 $.ajax({
                   url : "/controller/routeController.php?action=getAllRouteTransaction",
                   type: "POST", 
                   data:{pageNo:pageNo},
                   dataType: "json",
                   success:function (text)
                   {
                       var str = designTransactionLog(text.detail); 
		       
                       $('#transactionTable').html('');
                       $('#transactionTable').html(str);
		       console.log(text);
		       allRoutePagination(text.detail.totalCount,pageNo,'#pagination');
                       $("#clientTrstable tbody tr:visible:even").addClass("even"); 
                       $("#clientTrstable tbody tr:visible:odd").addClass("odd");
                   }
})
}

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
                            
                         getRouteAllTransactionLog(page);

//                            if(routeId == undefined || routeId == null )
//				window.location.href= window.location.href.split('?')[0]+'?pageNo='+page;
//                            else
//                               window.location.href= window.location.href.split('?')[0]+'?pageNo='+page+'&routeId='+routeId+'&tb=0';
                        }
                                    
		});
	}
        
}

</script>