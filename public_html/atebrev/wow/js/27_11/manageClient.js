$(document).ready(function()
{
    
    $("#clientTrstable tbody tr:visible:even").addClass("even"); 
$("#clientTrstable tbody tr:visible:odd").addClass("odd");

$("#transType").on('change',function(event){
   if(this.value=='Other')
       $("#transotherType").show();
   else
       $("#transotherType").hide();
 })
        
     

     $( "#tabst" ).tabs({
		create: function(event, ui) {console.log($('.ui-tabs-selected a',this).text())},
		select: function(event, ui) {
		console.log(ui.tab.innerText)}
	});
        
   
    
	$('.slideLeft ul li, .reserrlerBtn').click(function() {
				if ( $(window).width() <1024) {
					$('.slideRight').animate({"right": "20px"}, "slow");
					$('.slideLeft').fadeOut('fast');
				}
		});
                
                
  
       

        
             
                
	});


function toggleState(ths,type)
	{
		ths.toggleClass('redDisabl');
		if($('#chnage'+type).val() == "uncheck")
			{
					$('#chnage'+type).val("check");
			}
			else
				{
					$('#chnage'+type).val("uncheck");
				}
		}

	/*function toggleState(ths,type)
	{
		ths.toggleClass('redDisabl');
		if($('#changefunder'+type).val() == "uncheck")
			{
					$('#changefunder'+type).val("check");
			}
			else
				{
					$('#changefunder'+type).val("uncheck");
				}
		}*/
                
                
$(function() {
var xhr;
$('#searchUser').keyup(function() {
//  alert('Handler for .keyup() called.');
   searchUrl='manage-client.php?q='+$(this).val();
   console.log(xhr);
   if( Object.prototype.toString.call( xhr ) === '[object Object]' ) {
       xhr.abort()
   }
                xhr = $.ajax({
                type: "GET",
                url: searchUrl,
                success: function(msg){
                   $("#leftsec").html(msg);
                }
});
console.log(xhr);
//kill the request
});
});
                
		
	  jQuery(document).ready(function ($) {
		"use strict";
		$('#leftsec, .scrolll ').perfectScrollbar();
	  });
//	  current();

//**********transaction script

 
	

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

function addAdditionTransaction(){
   
 // status variable use for status of transaction add / reduce
 var status = $('#changefunderTrans').val();
 // var transType use for transaction type (cash,bank,voip91,other).
 var transType = $('#transType').val();
 var description = $('#description').val();
 var amount = $('#transAmount').val();
 var toUser = $('#toUser').val();
 var transTypeOther = $('#transTypeOther').val();
 var reg=/^[a-zA-Z0-9\@\_\-\s]+$/;
 var reg2 = /^[0-9]+$/;
 
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
                   data:{status:status,transType:transType,description:description,amount:amount,toUser:toUser,transTypeOther:transTypeOther},
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
                   }
        })
             }else 
             show_message("please enter valid other type ,no more then 20 characters.! ","error");
            }else
            show_message("please enter amount no more then 7 digits ! ","error");
        }else
        show_message("please enter valid amount! ","error");
    }else
       show_message("please enter valid description !","error");
    }else
       show_message("please enter valid transaction type! ","error");
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
  
 $.each( text, function(key, item ) {
  str += '<tr class="">\
                    <td>'+item.date+'</td>\
                    <td>'+item.name+'</td>\
                    <td>'+item.paymentType+'</td>\
                    <td class="alR">'+item.amount+'</td>\
                    <td class="alR">'+item.currentBalance+'</td>\
                    <td>'+item.description+'</td>\
                    <td class="alR"><span class="debit">'+item.debit+'</span></td>\
                    <td class="alR">'+item.credit+'</td>\
                    <td class="alRcloseBalance">'+item.closingBalance+'</td>\
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
			beforeSubmit:  showEditFundRequest,  //pre-submit callback 
			success:     
                                function(text)
                                {
                                show_message(text.msg,text.status);
                                if(text.status == "success"){
                                   $(':input','#editFundform')
                                    .not(':button, :submit, :reset, :hidden')
                                    .val('')
                                    .removeAttr('checked')
                                    .removeAttr('selected'); 
                                   
                                }
                                 $('#save').removeAttr('disabled');
                                }
		};
		$('#editFundform').ajaxForm(options); 
	}); 
        
function showEditFundRequest(formData, jqForm, options){
  $.validator.setDefaults({
  submitHandler: function() {
      $('#save').attr('disabled','disabled');
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
                                }
                                }
		};
                
                
                
		$('#editClientInfo').ajaxForm(options); 
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
                            required: true,
                            
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
$(document).ready(function() { 
    
  	var options = { 
                
                        url:"/controller/adminManageClientCnt.php?action=editGeneralSetting", 
                        type:'POST',        
			dataType: 'json',
			beforeSubmit:  showEditGeneralRequest,  // pre-submit callback 
			success:     
                                function(text)
                                {
                                show_message(text.msg,text.status);
                                }
		};
		$('#editgeneralSetting').ajaxForm(options); 
	}); 
        
function showEditGeneralRequest(formData, jqForm, options){
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#editgeneralSetting").validate({
                rules: {
                        newPass :{
                            required: true,
                            maxlength: 15,
                        }
                                           
                       }
        })
        
    })
            $("#loading").show();
            if($("#editgeneralSetting").valid())
                    return true; 
            else
                    return false;
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
