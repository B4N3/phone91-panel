<?php
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."routeClass.php");


if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("index.php");
}


#touser id
$routeId = $_REQUEST['routeId'];

if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;

//get route current balance
$robj = new routeClass();
$currBalance = $robj->getCurrentRouteBalance($routeId);

$routeCurr = '';
//get route detail
$routeDetailJson = $robj->getRouteDetail($_REQUEST,$_SESSION['userid']);

if($routeDetailJson)
{
    $routeArr = json_decode($routeDetailJson,TRUE);

    $routeCurr = $routeArr['currency'];
}



?>

<div id="tabs" class="mngClntFnction">
    <ul>
            <li onclick ="getRouteTransactionLog(<?php echo $routeId;?>,<?php echo $pageNo;?>)"><a href="#tabs-1" title="Transactional"><span class="ic-40 tranLog"></span></a></li>
            <li><a href="#tabs-2" title="Edit Fund"><span class="ic-40 editfund"></span></a></li>
			<!--<li><a href="#tabs-3" title="Add SIP"><span class="ic-40 addsip"></span></a></li>-->
	    <li onclick="getRouteInfo(<?php echo $routeId; ?>)"><a href="#tabs-3" title="Settings"><span class="ic-40 setting"></span></a></li>
<!--            <li ><a href="#tabs-4" title="Edit Route Detail"><span class="ic-40 setting"></span></a></li>-->
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
        <div id="pagination" class="mrT1"></div>
        <!-- Bootm Actions Wrapper-->
        <form action='javascript:;' id="addReduceTransaction">
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
		          <input type="hidden" id="routeId" value="<?php echo $routeId; ?>" name="routeId">
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
                      <input type="checkbox" name='status' value="add" checked="checked" style="display:none" id="changefunderTrans">
                  </span>
                  
                  <input type="text" placeholder="Amount" id="transAmount" name="amount" class="mrL fl">
                  <select name="currency" id="currency" class="currency fl currencyList">
                         
                  </select>                    
              </div>
          </div>
          <div class="actionDiv">
          	<p class="mrB">&nbsp;</p>
          	<input type="submit" class="mrL btn btn-medium btn-primary" name="Done" id="saveadditionTrs" value="Done" title="Done">
		      </div>
            
        </div>
      </form>
    </div>
    <!--//1st Tabs Content-->
    
    <!--2nd Tabs Content-->
    <div id="tabs-2" class="pd15">
    	<form class="formElemt" id="editFundRoute" action="javascript:;">
            <div class="fields">
                            <p>Current  Balance</p>
                            <h3 class="routeBalance"><span class="<?php echo $routeId;?>changeRouteBal"><?php echo round($currBalance,3); ?> </span> <?php echo "  ".$routeCurr;  ?> </h3>
                        </div>
            <div class="fields addReduce">   
                     <label>Add/Reduce Fund</label>
                    <span>
                         <label onclick="toggleState($(this),'EditFund');" for="changefunder" class="ic-60 enable cp"></label>
                         <input type="checkbox" id="changefunderEditFund" name="changefunderEditFund" style="display:none" checked="checked" value="add" />
                      
                    </span>
                     <input type="hidden" name="toRouteEditFund" value="<?php echo $routeId; ?>" id="toRouteEditFund"/>
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
   
  
    
    <!--4rth Tabs Content--> 
    <div id="tabs-3" class="pd15">
     	<div class="leftSetform">
                <form class="formElemt settingform" id="editRouteInfo">
                    <div class="add clear oh mrB1">
                            <span class="ic-12 lgrayArr db fl"></span>
                            <label class="db fl fwd">Panel Setting</label>
                            <input type="hidden" id="routeId" name="routeId" value="<?php echo $routeId;?>" />
                  </div>
                    <div class="fields">
                        <label>Route Name</label>
                       <input type="text" name="routeName" id="routeName" value="" />
                     <input type ="hidden" name="oldRouteName" id="oldRouteName" value=""/>     
                  </div>
                    <div class="fields">
                        <label>Edit Prefix</label>
                       <input type="text" name="routePrefix" id="routePrefix" value="" />
                     <input type ="hidden" name="oldPrefix" id="oldPrefix" value=""/>     
                  </div>
                    <div class="fields">
                        <label>Ip</label>
                       <input type="text" name="routeIps" id="routeIps" value="" />
                     <input type ="hidden" name="oldRouteIp" id="oldRouteIp" value=""/>     
                  </div>

                    <div class="fields">
                        <label>Tariff/Plan</label>
                        <select name="tariff" id="tariff" class="selPlan" style="">
                       
                        </select>
                    <input type ="hidden" name="hideTariff" id="hideTariff" value="<?php //echo $userInfo['tariffId'];?>" oldcurrency ="<?php //echo $oldCurrency; ?>"/>                
                
                  </div>
                    <input type="submit" class="mrT btn btn-medium btn-primary" name="Done"  value="Done" title="Done"> 
                    </form>
           	
            <div class="rightSetForm" style="float: left;left: 375px;position: absolute;top: 42px;width: 300px;">
                <form class="formElemt settingform" id="editDivertedRoute">


                    <br><br>
                   <div class="fields">
                            <span class="ic-12 lgrayArr db fl"></span>
                            <label class="db fl fwd mrB">Diverted Route</label>
                            <select name="divertedRoute" id="divertedRoute" style="">
                           
                            </select>
                            <input type="hidden" name="routeId" value="<?php echo $routeId;?>">      
                            <input type="hidden" name="oldDivertedRoute" id="oldDivertedRoute" value="" />
                  </div>
                   <input type="submit" class="mrT btn btn-medium btn-primary" name="Done" value="Done" title="Done">
                  </form> 

              </div> 
            
        <!-- assign rout and dial plan -->

        
    </div>
    <!--//4rth Tabs Content--> 
    
    <!--5th Tabs Content--> 
   <!--  <div id="tabs-4">
    	 <div class="tablflip-scroll dn" id="transactionTable">
      		   <table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize" id="systemInfo">
                   
           		</table>
            
      			 
        </div>
    </div> -->
    <!--//5th Tabs Content--> 

  </div>
<!--    <div id="tabs-4" class="pd15">

      <form class="formElemt settingform" action='javascript:;' id="routeEmailContact">
        <div class="fields">
         <label> Add Emails </label>
      <div class="addIpsWrp">
         <div class="addsipInput multiEmails">
           <input type="text" placeholder="example@xyz.com" name="routeEmails[]" >
           <span title="Delete" class="ic-24 delete cp" onclick="delRow(this)"></span> 
         </div>        
         <p onclick="newRow(this)" class="arBorder secondry fl cp sucsses cp " title="Add">
            <span class="ic-16 add "></span>
                   </p>
      </div>           
      </div>
      <div class="fields">
         <label> Add Contacts </label>
      <div class="addIpsWrp">
         <div class="addsipInput multiContacts">
           <input type="text" placeholder="Contact Number" name="routeContacts[]" >
           <span title="Delete" class="ic-24 delete cp" onclick="delRow(this)"></span> 
         </div>        
         <p onclick="newRow(this)" class="arBorder secondry fl cp sucsses cp" title="Add">
            <span class="ic-16 add "></span>
                   </p>
      </div>           
      </div>

         <input type="submit" class="mrT btn btn-medium btn-primary" name="Done" id="saveRouteEmailContact" value="Done" title="Done">
      </form>

      <div class='' style="width: 50%;height: 500px;position: absolute;top: 84px;left: 373px;">
        <form class="formElemt settingform" action='javascript:;' id="editRouteDetail">
	    
	     <div class="fields">
			   <label> Route Account Manager Email </label>
			<div class="addIpsWrp">
			   <div class="addsipInput">
				<input type="text" placeholder="Email" name="racmEmail" >
					
			   </div>			   

			</div>				   
	    </div>
	     <div class="fields">
			   <label> Route Account Manager MSN ID: </label>
			<div class="addIpsWrp">
			   <div class="addsipInput">
				<input type="text" placeholder="enter Msn Id" name="racmMsnId" >
					
			   </div>			   

			</div>				   
	    </div>
	     <div class="fields">
			   <label> Route Account Manager Contact No </label>
			<div class="addIpsWrp">
			   <div class="addsipInput">
				<input type="text" placeholder="Number" name="racmContact" >
					
			   </div>			   

			</div>				   
	    </div>
	    <div class="fields">
			   <label> Route Support Email: </label>
			<div class="addIpsWrp">
			   <div class="addsipInput">
				<input type="text" placeholder="example@xyz.com" name="rSupportEmail" >
					
			   </div>			   

			</div>				   
	    </div>
	     <div class="fields">
			   <label> Route Support MSN ID: </label>
			<div class="addIpsWrp">
			   <div class="addsipInput">
				<input type="text" placeholder="enter Msn Id" name="rSupportMsnId" >
					
			   </div>			   

			</div>				   
	    </div>
	     <div class="fields">
			   <label> Route Support Contact No </label>
			<div class="addIpsWrp">
			   <div class="addsipInput">
				<input type="text" placeholder="Number" name="rSupportContact" >
					
			   </div>			   

			</div>				   
	    </div>
	    <input type="submit" class="mrT btn btn-medium btn-primary" name="Done" id="saveRouteSupportDtl" value="Done" title="Done">
	    
	    
	    
      </form>
    </div>
  </div>-->
</div>
<script type="text/javascript">
    
$('.currencyList').html(currencyList);

 selectPlan();
var tb = <?php echo (isset($_REQUEST['tb']))? $_REQUEST['tb'] : 0;?>;

switch(tb)
{
     case 0:
	getRouteTransactionLog('<?php echo $routeId?>',<?php echo $pageNo;?>);
	break;
    case 2:
	getRouteInfo('<?php echo $routeId?>');
	break;
   
}


 $(function() {
     
 // currencyList is global variable initialize in panel.js    
 //$('.currencyList').append(currencyList); 
 //$('#fundCurrency option[value="'+<?php //echo $userInfo['currencyId'];?>+'"]').prop('selected',true);  
 //$('#partialCurrency option[value="'+<?php //echo $userInfo['currencyId'];?>+'"]').prop('selected',true);
 
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
//***********edit fund*****        
$(document).ready(function() { 
    
 
    var options = { 
                     
                        url:"/controller/routeController.php?action=addReduceTransaction", 
                        type:'POST',        
      dataType: 'json',
      beforeSubmit:  showAddReduceFundRequest,  // pre-submit callback 
      success:     
              function(text)
              {
                console.log(text);
              show_message(text.msg,text.status);
              if(text.status == "success"){
                var str = designTransactionLog(text.str);
                 $('#addReduceTransaction')[0].reset();
                 $('#transactionTable').html('');
                $('#transactionTable').html(str);
                $("#clientTrstable tbody tr:visible:even").addClass("even"); 
                $("#clientTrstable tbody tr:visible:odd").addClass("odd");
                 $("#transotherType").hide();
                 // var routeId = $('#toRouteEditFund').val();
                 
                 // $('.'+routeId+'changeBal').html(parseFloat(text.balance).toFixed(2));
                 
              }
              $('#saveadditionTrs').removeAttr('disabled');
              
              }
    };
    $('#addReduceTransaction').ajaxForm(options); 
  }); 

function showAddReduceFundRequest(formData, jqForm, options){

  $.validator.setDefaults({
  submitHandler: function() {
      
  }
  });
   
            //$("#loading").show();
            if($("#addReduceTransaction").valid())
            {
                $('#saveadditionTrs').attr('disabled','disabled');
                return true;
            }
               
            else
            {
               $('#saveadditionTrs').removeAttr('disabled');
                return false;
            }
                    
}

$().ready(function() {
        // validate the comment form when it is submitted 
        $("#addReduceTransaction").validate({
                rules: {
                        transType :{
                            required: true
                        },
                        description :{
                            required: true,
                            maxlength: 200
                             },
                        amount:{
                          required:true,
                          number:true,
                          maxlength:5,
                          minlength:1
                        },
                        currency:{
                          required:true

                        }
                        }
        })

         $('#transType,#description,#transAmount').blur(function(){
    console.log($(this).attr('id'));
        $("#"+$(this).attr('id')).valid();
    }); 
        
    })   


function addAdditionTransaction()
{
   
   $('#saveadditionTrs').attr( 'disabled' , 'disabled' );

 // status variable use for status of transaction add / reduce
 var status = $('#changefunderTrans').val();
 // var transType use for transaction type (cash,bank,voip91,other).
 var transType = $('#transType').val();
 var description = $('#description').val();
 var amount = $('#transAmount').val();
 //var toUser = $('#toUser').val();
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
                   data:{status:status,currency:currency,transType:transType,description:description,amount:amount,transTypeOther:transTypeOther},
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

 console.log(text);
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
  
  
  if(text.detail == null || text.detail == undefined)
      return str;
  
 if(text.totalCount != 0) 
 $.each( text.detail, function(key, item ) {
  str += '<tr class="hvrParent">\
                    <td>'+item.date+'</td>\
                    <td>'+item.userName+'</td>\
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
 
    $("#editFundRoute").validate({
		onfocusout: function(element) { $(element).valid(); },
		rules: {
			fundAmount :{
			    required: true,
			    minlength: 1,
			    maxlength:5,
			    number:true
				 },

			balance :{
			    required: true,
			    minlength:1,
			    maxlength:5,
			    number:true
				 },
			partialAmt :{
			    required: false,
			    number:true,
			    minlength:1,
			    maxlength:4
				 } ,
			fundDescription:{
			    required:false,
			    maxlength:200
			}


		}
            });



	var options = { 
                     
                        url:"/controller/routeController.php?action=editFundRoute", 
                        type:'POST',        
			dataType: 'json',
			beforeSubmit:  showEditFundRequest,  // pre-submit callback 
			success:     
                                function(text)
                                {
                                show_message(text.msg,text.status);
                                if(text.status == "success"){
                                   $('#editFundRoute')[0].reset();
                                   var routeId = $('#toRouteEditFund').val();
                                   
                                   $('.'+routeId+'changeRouteBal').html(parseFloat(text.balance).toFixed(2));
                                   
                                }
                                 $('#save').removeAttr('disabled');
                                }
		};
		$('#editFundRoute').ajaxForm(options); 
	}); 
   
   




 
   
function showEditFundRequest(formData, jqForm, options){

  $.validator.setDefaults({
  submitHandler: function() {
      
  }
  });
   
            $("#loading").show();
            if($("#editFundRoute").valid())
            {
              $('#save').attr('disabled','disabled');
              return true;
            }
                     
            else
            {
                   $('#save').removeAttr('disabled');
                    return false;
            }
              
}


//*************transaction detail of reseller

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 25/10/2013
//function use for design transaction log 
function getRouteTransactionLog(routeId,pageNo){
 $.ajax({
                   url : "/controller/routeController.php?action=getRouteTransaction",
                   type: "POST", 
                   data:{routeId:routeId},
                   dataType: "json",
                   success:function (text)
                   {
                       var str = designTransactionLog(text); 
		       
                       $('#transactionTable').html('');
                       $('#transactionTable').html(str);
		       //console.log(text);
		       //routePagination(text.totalCount,pageNo,'#pagination',routeId);
                       $("#clientTrstable tbody tr:visible:even").addClass("even"); 
                       $("#clientTrstable tbody tr:visible:odd").addClass("odd");
                   }
})
}



//*************setting*****

$(document).ready(function() {
    
   
		var options = { 
                     
                        url:"/controller/routeController.php?action=editRouteInfo", 
			dataType: 'json',
			type:'POST', 
			beforeSubmit:  showEditInfoRequest,  // pre-submit callback 
			success:     
                                function(text)
                                {
                                    show_message(text.msg,text.status);                                 
                                }
		};
                
                
                
		$('#editRouteInfo').ajaxForm(options); 
                
           
            
                
	}); 
        
        
  $(document).ready(function() {
     

             jQuery.validator.addMethod('selectcheck', function (value) {
                return (value != 'Select');
            }, "Please Select proper value!"); 


             jQuery.validator.addMethod('checkPrefix', function (value) {
                var prefixRegx = /[^0-9\*\#\+]/;

                if(prefixRegx.test(value))
                  return false;
                else
                  return true;


            }, "Please enter a valid value!"); 
            // validate the comment form when it is submitted	
            $("#editRouteInfo").validate({
              onfocusout: function(element) { $(element).valid(); },
                    rules: {
                            routeName :{
                        				required: true,
                        				minlength: 3,
                                maxlength:40
                                     },

                            routeIps :{
                          				required: true//,
                          				//IP4Checker: true
                                     },
                            routePrefix :{
                          				required: false,
                          				checkPrefix:true,
                                minlength:1,
                                maxlength:8
                                     } ,

                                 tariff:{
                                      required:true,
                                      selectcheck:true
                                     }     
                        }
                                  
            });
    
    
          })       
        
 function showEditInfoRequest(formData, jqForm, options)
 {
  
    $.validator.setDefaults({
    submitHandler: function() { console.log("submitted!");

      }
    });
     
    $("#loading").show();

    if($("#editRouteInfo").valid())
            return true; 
    else
            return false;

} 

//************general setting ***
//$(document).ready(function() { 
    
  	var options = { 
                
                        url:"/controller/routeController.php?action=editDivertedRoute", 
                        type:'POST',        
			dataType: 'json',
			success:     
                                function(text)
                                {
                                show_message(text.msg,text.status);

                                }
		};
		$('#editDivertedRoute').ajaxForm(options); 
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
  
  
  function getRouteInfo(routeId){
     
    $.ajax({
                    url : "/controller/routeController.php?action=getRouteDetail", 
                    type: "POST", 
                    dataType: "json",
                    data:{routeId:routeId},
                    success:function (text)
                    {
                        console.log(text);
                        
                        if(text.routeName != '' && text.routeName != undefined)
                        {
                            $('#routeName').val(text.routeName);
                            $('#oldRouteName').val(text.routeName);
                            $('#routeIps').val(text.routeIp);
                            $('#routePrefix').val(text.prefix);
                            console.log(text.tariffId);
                            $('#tariff').val(text.tariffId);
                            
                          
                            var option='';
//                             if(text.routeList.length > 0)
//                            {
                //                console.log(resMsg);
                                option += "<option value='Select'>Select</option>";
                                $.each(text.routeList,function(i,item)
                                {
                                        
                                        option += "<option value='"+i+"'>"+item+"</option>";
                                });
                                
                                $('#divertedRoute').html(option);
                               
                               
                               if(text.divertedRoute != 0)
                               {
                                    $('#divertedRoute').val(text.divertedRoute);
                                    $('#oldDivertedRoute').val(text.divertedRoute);
                               }
//                                console.log($('#divertedRoute option[value="'+routeId+'"]').remove());
                                
                                
                                
                            //}

                            
                            
                        }
//                        var str = '';
//                        var selected = '';
//                         $.each(text, function(key, item ) {
//                             var selecMgr = '';
//                             if(managerId == key){
//                               selected = key  
//                               var selecMgr = 'selected = "selected"';   
//                             }
//                             str += '<option value='+key+' '+selecMgr+'>'+item+'</option>';
//                         })
                        //$('#accountManager').html(str);
                       // $('#accountManager').after('<input type ="hidden" name="oldmanager" id="oldmanager" value="'+selected+'" /> ');
//                        var str = ipDetailDesign(text);  
//                        $('#systemInfo').html('');
//                        $('#systemInfo').html(str);
                        
                    }
    });
    
//     $.ajax({
//                    url : "/controller/adminManageClientCnt.php?action=getRouteAndDialplanList", 
//                    type: "POST", 
//                    data:{clientId:clientId},
//                    dataType: "json",
//                    success:function (text)
//                    {
//                        if(text.status == "success"){
//                        var str = '';
//                         $.each(text.routeData, function(key, item ) {
//                              var selectroute = '';
//                             if(text.routeId == key){
//                               selectroute = 'selected = "selected"';   
//                             }
//                            str += '<option value='+key+' '+selectroute+'>'+item+'</option>';
//                         })
//                        $('#routeList').html(str);
//                        
//                        var dialstr = '';
//                         $.each(text.dialPlanData, function(key, item ) {
//                              var selectdialp = '';
//                             if(text.routeId == key){
//                               selectdialp = 'selected = "selected"';   
//                             }
//                            dialstr += '<option value='+key+' '+selectdialp+'>'+item+'</option>';
//                         })
//                        $('#dialPlanList').html(dialstr);
//                        
//                        if(text.isDialPlan == 1){
//                            $("#dialplanradio").prop("checked", true);
//                            $('.selDialorRoute').hide();
//                            $('#dialPlanListDiv').show();
//                        }else
//                        {
//                            $("#routeradio").prop("checked", true);
//                            $('.selDialorRoute').hide();
//                            $('#routeListDiv').show();
//                        }
//                    }
//                        
//                        
//                        
//                        
//                    }
//                    
//     });
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
      
//       $.ajax({
//                   url : "/controller/adminManageClientCnt.php?action=changeUserToReseller", 
//                   type: "POST", 
//                   data:{userId:userId},
//                   dataType: "json",
//                   success:function (text)
//                   {
//                       show_message(text.msg,text.status);
//                                             
//                   }
//       });
      
      
  }
  
  function changeSipSetting(userId)
  {
//      if($('#changeSip').is(':checked'))
//          var actionType = "enable";
//      else
//          var actionType = "disable";
//      
//      
//      if(/[^0-9]/.test(userId) || userId == "" || userId == null)
//      {
//          show_message("Invalid User please select a valid user","error");
//          return false;
//      }
//      
//      $.ajax({
//            url : "/controller/adminManageClientCnt.php", 
//            type: "POST", 
//            data:{forUser:userId,action:"changeSipSetting",actionType:actionType},
//            dataType: "json",
//            success:function (text)
//            {
//                show_message(text.msg,text.status);
//
//            }
//      })
  }
  
  function listenRemainMinStatus(userId,currentStatus){
//    $.ajax({
//                   url : "/controller/adminManageClientCnt.php?action=listenRemainMinStatus", 
//                   type: "POST", 
//                   data:{userId:userId,
//                   currStatus:currentStatus},
//                   dataType: "json",
//                   success:function (text)
//                   {
//                       show_message(text.msg,text.status);
//                       if(text.status == "success"){
//                           //$('#listenRemainingMin').hide();
//                       }
//                                     
//                   }
//       });
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


//var strt = <?php //echo $pageNo; ?>;
//    var totalCount = <?php //if(isset($transData['totalCount']) && is_numeric($transData['totalCount']) && $transData['totalCount'] != 0) echo $transData['totalCount'];else echo 1; ?>;
//
//    if(strt == undefined || strt == null || strt == '' )
//        strt = 1;
//
//    if(totalCount == undefined || totalCount == null || totalCount == '' )
//        totalCount = 1;
//clientPagination(totalCount,<?php //echo $pageNo; ?>,'#pagination',<?php //echo $clientId; ?>,0);


	$( "#tabs" ).tabs({
		active: tb,
		create: function(event, ui) {console.log($('.ui-tabs-selected a',this).text());
                console.log(tb+'hello');},
		select: function(event, ui) {
		console.log(ui.tab.innerText)}
	});


$(document).ready(function() {
     

            // validate the comment form when it is submitted 
            $("#editRouteDetail").validate({
              onfocusout: function(element) { $(element).valid(); },
                    rules: {
                            racmEmail:{
                                        email:true,
                                        minlength:8,
                                        maxlength:50
                            }  ,
                            racmMsnId:{
                                        required:false,
                                        email:true,
                                        minlength:8,
                                        maxlength:50        
                            },
                            racmContact:{
                                          required: false,
                                          number:true,
                                          minlength:8,
                                          maxlength:20//,
                            },
                            rSupportEmail:{
                                            required:false,
                                            email:true,
                                            minlength:8,
                                            maxlength:50            
                            },
                            rSupportMsnId:{
                                            required:false,
                                            email:true,
                                            minlength:8,
                                            maxlength:50              
                            },
                            rSupportContact:{
                                              required: false,
                                          number:true,
                                          minlength:8,
                                          maxlength:20//,
                            }   
                           
                    }
            });
    
         


    
          })   



function newRow(ts){

 if($(ts).prev().hasClass('multiContacts'))
    {

      if($('.multiContacts').length < 4)  
       {
           var clone = $(ts).prev().clone(); 
            $(ts).before(clone);  
       }
    }

     if($(ts).prev().hasClass('multiEmails'))
    {
      if($('.multiEmails').length < 4)  
       {
           var clone = $(ts).prev().clone(); 
            $(ts).before(clone);  
        }
    }
}

 
 
function delRow(ts)
{

    if($(ts).parent().hasClass('multiContacts'))
    {

      if($('.multiContacts').length > 1)  
        $(ts).parent().remove();
      else
        $(ts).prev().val(''); 
    }

    if($(ts).parent().hasClass('multiEmails'))
    {
      if($('.multiEmails').length > 1)  
        $(ts).parent().remove();
      else
        $(ts).prev().val(''); 
}

}              

$(document).ready(function() { 
   

 

          var options = { 
                  url:'/controller/routeController.php?action=addEditRouteSupportDtl',
                  dataType:  'json',
                  //target:        '#response',   // target element(s) to be updated with server response 
                  beforeSubmit:  showRouteDtlRequest,  // pre-submit callback 
                  success:       function(data){
                    console.log(data);
                    $('#saveRouteSupportDtl').removeAttr('disabled');
                  }  // post-submit callback 
          }; 
          $('#editRouteDetail').ajaxForm(options); 
  }); 




 function showRouteDtlRequest(formData, jqForm, options)
 {
  
    $.validator.setDefaults({
    submitHandler: function() { console.log("submitted!");

      }
    });
     
    //$("#loading").show();

    if($("#editRouteDetail").valid())
    {
            $('#saveRouteSupportDtl').attr('disabled','disabled');
            return true; 
    }
      
    else
    { 
               $('#saveRouteSupportDtl').removeAttr('disabled');
              return false;
    }
      


}



$(document).ready(function() { 
   
// validate the comment form when it is submitted 
            $("#routeEmailContact").validate({
              onfocusout: function(element) { $(element).valid(); }
            });


           $('[name*="routeEmails"]').each(function () {
                  $(this).rules('add', {
                       email:true,
                        required: false,
                        minlength: 8,
                        maxlength:50
                     
                  });
              });

                $('[name*="routeContacts"]').each(function () {
                  $(this).rules('add', {
                       required: false,
                        number:true,
                        minlength:8,
                        maxlength:20//,
                     
                  });
              });       


            var options = { 
                  url:'/controller/routeController.php?action=addEditRouteEmailContact',
                  dataType:  'json',
                  //target:        '#response',   // target element(s) to be updated with server response 
                  beforeSubmit:  showRouteEmailContactReq,  // pre-submit callback 
                  success:       function(data){
                    console.log(data);
                    $('#saveRouteEmailContact').removeAttr('disabled');
                  }  // post-submit callback 
            }; 
          $('#routeEmailContact').ajaxForm(options); 
  }); 

function showRouteEmailContactReq(formData, jqForm, options)
{

     $.validator.setDefaults({
          submitHandler: function() { console.log("submitted!");

            }
          });
           
          //$("#loading").show();

        if($("#routeEmailContact").valid())
        {
                $('#saveRouteEmailContact').attr('disabled','disabled');
                return true; 
        }
          
        else
        { 
                   $('#saveRouteEmailContact').removeAttr('disabled');
                  return false;
        }
}

</script>