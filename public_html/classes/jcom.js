/**
 * @param {type} Check for number confirmation, matches code type in by user with the code stored and send to user
 * Validate form
 * Send details to update_contact.php and get confirmation 
 * @returns {undefined}
 */
function phone_confirmation(){
    confirmationCode = $("#confirmation_code").val();
    if(confirmationCode.length <1){
	checkEmpty(confirmationCode,'#confirmation_code', 'code');
   }else{
	$.ajax({
		    url: "update_contact.php",
		    type: "POST",
		    data:"confirmation_code="+confirmationCode
		})
		.done(function(msg){
		    if($.trim(msg) == 'Successfully Confirmed'){
//			showMessage("Successfully verified", "#confirmation_code", result)
			$("#confirmation_code").addClass("error_green");
			$("#confirmation_code").next().addClass("error_green").html("Successfully verified");
			show_message("Successfully verified","success");
		    }else if(msg == 2){
			$("#confirmation_code").addClass("error_red");
			$("#confirmation_code").next().addClass("error_red").html("Number already in use");
			show_message("Number already in use","warning");
		    }else if(msg == 3){
			$("#confirmation_code").addClass("error_red");
			$("#confirmation_code").next().addClass("error_red").html("Number already confirmed by you");
			show_message("Number already confirmed by you","warning");
		    }else if(msg == 4){
			$("#confirmation_code").addClass("error_red");
			$("#confirmation_code").next().addClass("error_red").html("Code doenot match");
			show_message("Code doenot match","warning");
		    }
		})
   }
}

/**
 * 
 * @param {type} Check for email confirmation, matches code type in by user with the code stored and send to user
 * Validate form
 * Send details to verify_email.php and get confirmation 
 * @returns {undefined}
 */
function email_confirmation(l){
    confirmationCode = $("#confirmationCode").val();
    email = $("#idemail").val();
    if(confirmationCode.length <1){
	checkEmpty(confirmationCode,'#confirmationCode', 'code');
   }else{
	$.ajax({
		    url: "verify_email.php",
		    type: "POST",
		    data:"confirmatioCode="+confirmationCode+"&email="+email
		    
		})
		.done(function(msg){
		    $("#confirmationCode").addClass("error_red");
		    $("#confirmationCode").next().addClass("error_red").html(msg);
		    show_message(msg,"success");
		})
   }
    
}

/**
 * Updates a user contact number based on which a new number is added 
 * Sends gathered detail to action_layer.php
 * @returns {undefined}
 */
function updateContact(){
    var code = $("#code").val();
    var mobile_no = $("#mobileNumber").val();
    $("input").removeClass("error_red");
    $('input').next().html("");
    if((code.length <1) || (mobile_no.length <1)){
	checkEmpty(code,'#code', 'code');
	checkEmpty(mobile_no,'#mobileNumber', 'number');
    }else if(!$.isNumeric(mobile_no)){
	$("#mobileNumber").addClass("error_red");
	$("#mobileNumber").focus();	
	$("#mobileNumber").next().addClass("error_red").html("Only Digits");
	show_message("Only Digits","warning");
    }else{
	$.ajax({
	    url : "action_layer.php?action=update_contactno",
	    type: "POST",
	    data: "code="+code+"&mobileNumber="+mobile_no+"&location="+mobile_no.replace('+','')+"&register=Update",
	    
	})
	.done(function(msg){
	    if(msg == 0){
		    $("#mobileNumber").addClass("error_red");	
		    $("#mobileNumber").next().addClass("error_red").html("Check number");
		    show_message("Check number","warning");
	    }else if(msg == 1){
		 $("#mobileNumber").next().addClass("error_red").html("Number Change Successfully and verification code send to your emai");
		 show_message("Number Change Successfully and verification code send to your email","success");
	    }else if(msg == 2){
		 $("#mobileNumber").next().addClass("error_red").html("Number used by other user");
		 show_message("Number used by other user","success");
	    }else if(msg == 3){
		 $("#mobileNumber").next().addClass("error_red").html("Number already confirmed by you");
		 show_message("Number already confirmed by you","warning");
	    }else if(msg == 4){
		 $("#mobileNumber").next().addClass("error_red").html("Cannot send confirmation code");
		 show_message("Cannot send confirmation code","warning");
	    }
	    
	})
    }
    
}

/**
 * Changes User password also validate form
 * Send details to action_layer.php for updation
 * @returns {undefined}
 */

function change_pass(){
    
	var c= $.trim($("#curr_pwd").val());
	var n1= $.trim($("#new_pwd").val());
	var n2= $.trim($("#confirm_pwd").val());
	c = jQuery.trim(c);
	$("input").removeClass("error_red");
	$("input").next().html("");
	if(c.length <1 || n1.length <1 || n2.length <1)	{
	    checkEmpty(n2,'#confirm_pwd', 'password');
	    checkEmpty(n1,'#new_pwd', 'password');
	    checkEmpty(c,'#curr_pwd', 'password');
	}else if(n1 != n2){
	    $("#new_pwd").addClass("error_red");
	    $("#confirm_pwd").addClass("error_red");
	    $("#new_pwd").next().html("New Password are not matched");
	    show_message("New Password does not matched.","warning");
	}
	else{
		$("#loading_img").show();
		$.ajax({type: "POST",url: "action_layer.php?action=change_pwd",data: "curr_pwd="+c+"&new_pwd="+n1+"&confirm_pwd="+n2,success: function(msg)
		{
			 $("#loading_img").hide();
			if(msg==0){
				$("#curr_pwd").attr('value','');
				$("#curr_pwd").addClass("error_red");
				$("#curr_pwd").focus();
				showMessage("Please enter correct password", "#curr_pwd", "warning")
			}
			if(msg==1)
			{
				$("#new_pwd").val('');
				$("#curr_pwd").val('');
				$("#confirm_pwd").val('');
				showMessage("Password Change Successfully", "#curr_pwd", "success");
			}
			if(msg==2)
			{
				$("#new_pwd").addClass("error_red");
				$("#confirm_pwd").addClass("error_red");
				$("#new_pwd").attr('value','');
				$("#confirm_pwd").attr('value','');
				showMessage("weak password please chose another one.", "#curr_pwd", "warning");
			}
		}
		});
	}
}

/**
 * Changes User email also validate form
 * Send details to action_layer.php for updation
 * @returns {undefined}
 */
function change_email(){

	var n1= $.trim($("#new_emailid").val());
	var n2= $.trim($("#confirm_emailid").val());
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	$("input").removeClass("error_red");
        $("input").next().html("");
	if( n1.length <1 || n2.length <1){
	   checkEmpty(n1,'#new_emailid','email');
	   checkEmpty(n2,'#confirm_emailid','email');
	}else if(!emailReg.test(n1)){
	    $("#new_emailid").addClass("error_red");
	    $("#new_emailid").focus();	
	    $("#new_emailid").next().addClass("error_red").html("Enter correct email");
	    show_message("Enter correct email","warning");
	}else if(!emailReg.test(n2)){
	    $("#confirm_emailid").addClass("error_red");
	    $("#confirm_emailid").focus();	
	    $("#confirm_emailid").next().addClass("error_red").html("Enter correct email");
	    show_message("Enter correct email","warning");
	}else if(n1 != n2){
	    $("#new_emailid").addClass("error_red");
	    $("#confirm_emailid").addClass("error_red");
	    $("#new_emailid").focus();	
	    $("#new_emailid").next().addClass("error_red").html("Email doesnot match");
	    show_message("Email doesnot match","warning");
	}else{
		$("#loading_img").show();
		$.ajax({type: "GET",url: "action_layer.php?action=change_emailid",data: { new_emailid: n1,confirm_emailid: n2},success: function(msg)
		{
			$("#loading_img").hide();
			if($.trim(msg)==1){	
			    $("#new_emailid").next().addClass("error_green").html("Emailid Change Successfully and verification code send to your emai");
			    window.location.hash = 'settings.php?lnk=settings&tab=verifyEmail';
			}else if(msg==2){
				$("#new_emailid").addClass("error_red");
				$("#confirm_emailid").addClass("error_red");
				$("#new_emailid").val("");
				$("#confirm_emailid").val("");
				show_message("Emailid is not proper please choose another one.","warning");
			}else if(msg==4){
			    $("#new_emailid").next().addClass("error_red").html("This email already registered.");
			    show_message("This email already registered.","warning");
                         }
		}
		});
	}
}

/**
 * Delete temp email of user also validate form
 * Send details to action_layer.php for updation
 * @returns {undefined}
 */
function delete_email()
{
	$("#loading_img").show();
        $.ajax({type: "GET",url: "action_layer.php?action=delete_emailid",data: { },success: function(msg){
	if(msg==1){
		show_message("Emailid is deleted.","success");
	}
	else{
		show_message("Emailid is not deleted.","warning");
	}
	}
	});
}


/**
 * Delete temp contact number of user also validate form
 * Send details to action_layer.php for updation
 * @returns {undefined}
 */
function delete_phoneno(phone_no)
{
	$("#loading_img").show();
        $.ajax({type: "GET",url: "action_layer.php?action=delete_phoneno",data: "phone_no="+phone_no,success: function(msg){
	if(msg==1){
	    show_message("Phone number is deleted.","warning");
	}else{
	    show_message("Phone number is not deleted.","warning");
	}
	}
	});
}
/**
 * Change payment detail of batches
 * @param {type} 
 * @returns {undefined}
 */

 function changeStatus(e){
   $.ajax({
       url: "action_layer.php?action=batch_status",
       type : "POST",
       data : "value="+e.id,
   }).done(function(msg){
       if(msg == 1){
	   show_message("Successfully updated","success");
	   if(e.value == 'Stop'){
	    e.value = 'Resume';
	    e.id = e.id.replace('status','resume');
	  }else if(e.value == 'Resume'){
	       e.value = 'Stop';
	       e.id.replace('status','resume');
	  }
       }
   })
 }
 
/**
 * Function to edit funds from reseller to user also validate form
 * Take in details and send to ajax_layer.php for insertion 
 * @param {type} form_id is the client_id to which fund is being transfered
 * @returns {undefined} 
 */
function edit_funds_transfer(form_id){
    var amount_transfer = $.trim($("#amt_"+form_id).val());
    var amount = $("#amount").val();
    var type = balance = 0;
    if(document.getElementById("type_"+form_id).checked==true){
	type = 'add';
	balance = amount_transfer + amount;
    }else{
	type = 'reduce';
	balance = amount - amount_transfer;
    }
    if(!amount_transfer){
	checkEmpty(amount_transfer,"#amt_"+form_id, 'Amount');
    }else if(!$.isNumeric(amount_transfer)){
	$("#amt_"+form_id).addClass("error_red");
	$("#amt_"+form_id).focus();	
	$("#amt_"+form_id).next().addClass("error_red").html("Only Digits");
	show_message("Only Digits","warning");
    }else{
	$.ajax({
	    url : "action_layer.php?action=edit_funds",
	    type : "POST",
	    data : "type="+type+"&amount_transfer="+amount_transfer+"&balance="+balance+"&to_id="+form_id
	})
	.done(function(msg){
	    if(msg == 1){
		$("#button_text").addClass("error_green");
		$("#button_text").focus();	
		$("#button_text").next().addClass("error_green").html("Fund Added");
		show_message("Fund Added","success");
	    }
	})
    }
}

/**
 * Validates addPin form, send data to action_layer.php 
 * @returns {Boolean}
 */
function validatePinForm(){
    
    var bname = $.trim($("#bname").val());
    var totalPins = $.trim($("#totalPins").val());
    var amount = $.trim($("#amount").val());
    $("input[type!='radio']").removeClass("error_red");
    $("input[type!='radio']").next().html("");
    if(bname.length <1 || totalPins.length <1 || amount.length <1)	{
	    checkEmpty(amount,"#amount", 'Amount');
	    checkEmpty(totalPins,"#totalPins", 'total pins');
	    checkEmpty(bname,"#bname", 'name');
	    return false;
    }else if(!$.isNumeric(totalPins) || !$.isNumeric(amount)){
	 if(!$.isNumeric(amount)){
		    $("#amount").addClass("error_red");
		    $("#amount").focus();	
		    $("#amount").next().addClass("error_red").html("Only numeric");
		    show_message("Check details","warning");
		    
	    }
	    if(!$.isNumeric(totalPins)){
		    $("#totalPins").addClass("error_red");
		    $("#totalPins").focus();	
		    $("#totalPins").next().addClass("error_red").html("Only numeric");
		    show_message("Check details","warning");
		   
	    }
     return false;
     
    }else if((document.getElementById('underMyResellerInpt2').checked) && ($("#underReseller").val().length <1)){
	$("#guid").addClass("error_red");
	$("#guid").focus();	
	$("#guid").next().addClass("error_red").html("Enter reseller name");
	show_message("Check details","warning"); 
	 return false;
    }else{
	var options = { 
		dataType:  'json',
		//target:        '#response',   // target element(s) to be updated with server response 
		beforeSubmit:  showRequest,  // pre-submit callback 
		success:       showResponse  // post-submit callback 
	}; 
	$('#userProfile').ajaxForm(options); 
	return true;
	
    }
	 
}

/**
 * Validation for feedbackForm
 * @param {type} form_id
 * @returns {undefined}
 */
function feedback_submit(captcha){
    user_email = $.trim($("#user_email").val());
    user_number = $.trim($("#user_number").val());
    msg = $.trim($("#msg").val());
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    $("#feedBackForm input").removeClass("error_red");
    $("#feedBackForm input").next().html("");
    $("#feedBackForm textarea").removeClass("error_red");
    $("#feedBackForm textarea").next().html("");
    if(user_email.length <1 || user_number.length <1 || msg.length <1)	{
	     checkEmpty(msg,"#msg", 'your feedback'); 
	     checkEmpty(user_number,"#user_number", 'phone number');
	     checkEmpty(user_email,"#user_email", 'email');
     }else if(!emailReg.test(user_email)){
	$("#user_email").addClass("error_red");
	$("#user_email").focus();	
	$("#user_email").next().addClass("error_red").html("Enter proper email id");
	show_message("Check details","warning");  
     }else{
	 $.ajax({
		url:'../action_layer.php?action=feedbak'+'&mailid='+user_email+'&msg='+msg+'&number='+user_number+'&magic='+captcha,
		success: function(msg){
			if(msg == 'Success')
				{
				     $("#sbmitFeedback").next().addClass("error_green").html("Feedback submitted"); 
			show_message('Submitted Successfully', 'success');
				}
		else
			show_message('Error', 'error');
		}
	   });
    }
}
/**
 * 
 * @param {type} value is the value of an input
 * @param {type} id is the id of the input
 * @param {type} item is the data which contains the input
 * @returns {undefined}
 */
function checkEmpty(value,id,item){
     if( value.length <1){
	$(id).addClass("error_red");
	$(id).focus();	
	$(id).next().addClass("error_red").html("Enter "+item);
	show_message("Enter "+item,"warning");
    }
}

/**
 * 
 * @param {type} value is the value to be displayed
 * @param {type} id is the id where data is to be diaplayed
 * @param {type} result the status success or warning
 * @returns {undefined}
 */
function showMessage(value, id, result){
    add_class = "error_red";
    if(result == "success")
	add_class = "error_green";
    $(id).addClass(add_class);
    $(id).next().addClass(add_class).html(value);
    show_message(value,result);
}


function edit_funds_submit(form_id)
{
   var type;
   $('#button_text').attr('disabled',true);
   if(document.getElementById("type_"+form_id).checked==true)
   {
     type=1;
   }
   else
   {
    type=2;
   }
   var expiry=$("#expiry_"+form_id).val();
   var sms=$("#sms_"+form_id).val();
   var amount=$("#amt_"+form_id).val();
   var plan=$("#plan_"+form_id).val();
   var description=$("#description_"+form_id).val();
   var transaction_type=$("#trans_sms_"+form_id).val();
      $.ajax({
              type: "POST",
              url: "../action_layer.php?action=update_profile",
              data: "id="+form_id+"&update_detail=3&expiry="+expiry+"&sms="+sms+"&amount="+amount+"&type="+type+"&description="+description+"&plan="+plan+"&transaction_type="+transaction_type,
             success: function(msg){
              if(msg=='Update Successful')
				{
				// load_next('manage_admin.php');
				$("#ajaxcontent"+form_id).hide();
                 show_message(msg,"success");
				}
			else
			{
			   show_message(msg,"error");
			   $('#button_text').attr('disabled',false);
                 }
                }
                 
       });
                        
}

function sendVcardClk(Li){
	smsOpen();
	name=$('#li_'+Li+' .name').html();
	$('#ssMessage').append(name);
	}

function addToSendSMSList(Li){		
	smsOpen();
	$('#li_'+Li).css('opacity','.2');
	$('#li_'+Li+' .sendsmsbtn').remove();
	if($('#ssNumbers').val()!='')$('#ssNumbers').append(',')
	num=$('#li_'+Li+' .num').html();
	$('#ssNumbers').append(num);
	}
function smsOpen(){	
	$("#sendsms").animate({'left': '0px'},300);
	}
function smsClose(){	
	$("#sendsms").animate({'left': '-200px'},300);
	$('#ssNumbers').html('');
	if($('#searchNames').val()!=''){
	$('#searchNames').trigger('keyup');	
	}
	else{
	$('#phResult .phrLi').remove();
	$('.scrollBox').tinyscrollbar();
	}
	}
$(document).bind('keydown keypress keyup',function(e){
	var char = e.which;
	if(char=='27'){closeSearch();}
})
$(window).resize(function(){
	autoHeight(30,'#middle.leftAlign');
	autoHeight(130,'.leftAlign .viewport');
	$('.scrollBox').tinyscrollbar();
})
function closeSearch(){
	$('#middle').removeClass('leftAlign').css('min-height','500px');
	$('.resultContent').hide();
	$('#searchNames').val('').focus();
	}
function addNewContact(ths){	
	if($('.addNew').length <= 1){
		$('#middle').addClass('leftAlign');			
		$('#phResult').prepend($('#addNewTemplate').html());
		$('#phResult .number input').focus();
		autoHeight(130,'.leftAlign .viewport');
		$('.scrollBox').tinyscrollbar();
	}else{
		$('#phResult .number input').focus();
	}
}
function addNewCancel(){	
	$('#phResult .addNew').remove();
	$('.scrollBox').tinyscrollbar();
}
function searchNow(){
	autoHeight(30,'#middle');
	$('#middle').addClass('leftAlign');
	$('html,body').animate({scrollTop: $("#phbookContent").offset().top-20},'slow');
	var q=$("#searchNames").val();
	if(q.length>=2)
	{
		$.ajax({
			type:"POST",
			url:"searchPhB.php?action=srchPhBInd",
			dataType: 'json',
			data:{q:q},
			success:function(data){
				//$('.phResult').html();
				$('.resultContent').show();
				$('#phResult').html("");
				//alert(data);
					//alert(data.res);
					//alert(data.error);
				if(data.error=="")
				{
					
					for(var i=0;i<data.res.length;i++)
					{
						var dataStr='<li class="phrLi" id="li_'+data.res[i].id_address_book+'"><table class="listcontent"><tbody><tr><td id="liNumber" class="number"><div class="num">'+data.res[i].telephone_number+'</div><div class="navOuter"><div id="nav1" class="listnav"><ul class="nUl"><li class="nLi"><span class="ui-icon ui-icon-contact"></span><span class="label" onclick="sendVcardClk('+data.res[i].id_address_book+')">Send as Vcard</span></li><li class="nLi"><span class="ui-icon ui-icon-trash"></span><span class="label">Delete</span></li><li class="nLi"><span class="ui-icon ui-icon-gear"></span><span class="label">Edit</span></li></ul></div></div></td><td class="five"></td><td class="numContent"><div class="left"><div id="liName" class="name">'+data.res[i].nickname+'</div><span class="url"><a target="_blank" href="http://phone91.com/">http://phone91.com/</a></span><div class="key">VoIP, PC to Phone, Low Price</div></div><div class="right"><div class="verified tip" title="Verified"></div></div><div class="sendsmsbtn"><button onclick="addToSendSMSList('+data.res[i].id_address_book+')" class="button green">SEND SMS</button></div><div class="clf"></div></td></tr></tbody></table></li>';
						$('#phResult').append(dataStr);
					}					
					autoHeight(130,'#phbookContent .scrollBox .viewport');	
					$('.scrollBox').tinyscrollbar();
				}
				$('.tip').tipTip();
			}
		});
	}	
	}

function autoHeight(removeExtra,objName){	
	var winHeight=$(window).height();
	var objHeight=winHeight-removeExtra;
	$(objName).css('min-height',objHeight);
	
}




function resend_ecode()
{
        $("#loading_img").show();
        $.ajax({type: "GET",url: "action_layer.php?action=resend_ecode",data: { },success: function(msg){
        	if(msg==1){
        	     show_message("Verification code has been sent to your email.","success");
        	}
        	else{
        	        show_message("Please try again.","warning");
        	}
        }
        });
}
function checkRate()
{
        $("#loading_img").show();
	source=$("#source").val();
	dest=$("#dest").val();
        $.ajax({type: "GET",url: "checkRate.php?source="+source+"&dest="+dest,dataType: 'json',success: function(msg){        	
			$(".rateResult").html((msg.rate=='No Tariff found')?msg.rate:msg.rate+' '+currency+'/min')
        }
        });
}



function validate()
{
 
//var domain=window.location;
var interval;
var result=null;
var s=$("#source").val();
var d=$("#dest").val();
if(d.length<8 ||isNaN(d) || d.length>18)
{
 	if(isNaN(d))
	{
		$("#dest").addClass("error_red").attr('value','');
		$("#response").show().addClass("error_red").html('Please Provide proper Source number');
	}
	else
	{
		$("#dest").addClass("error_red");
 		$("#response").show().addClass("error_red").html("please enter number (minimum length 11 , maximum length 18)");
	}
	$("#dest").focus();return false;
}
else
{
		$("#response").html('');
		$("#dest").removeClass("error_red");	
		$("#dest").addClass("error_green");	
}
if(s.length<11 || isNaN(s) || d.length>18)
{
	if(isNaN(s))
	{
		$("#source").addClass("error_red").attr('value','');
		$("#response").show().addClass("error_red").html('Please Provide proper Destination number');
	}
	else
	{
		$("#source").addClass("error_red");
 		$("#response").show().addClass("error_red").html("Please enter number ( Minimum length 11, Maximum length 18)");
	}
	$("#source").focus();
	return false;
}
else
{
	$("#source").addClass("error_green");	
}
var url1="clicktocall.php";

url1=url1+"?q="+d+"&d="+s;

$.ajax({type:"GET",url:url1,success:function(msg){
	//$("#guid").attr('value',msg);
	js.notification('error',msg)
	$("#response").show().html(msg);
	$("#connectcall").css("visibility","hidden")
}});
return false;
alert(url1);
}

$(".ac_results ul").append("<li><div>Country</div></li>");

function loginClose(){	
	$(".login").animate({"top": "-89px"}, "fast",function(){
		$(".login").css({"z-index": "999","-moz-box-shadow":"none","-webkit-box-shadow":"none","box-shadow":"none"});			
		$('.loginBtn').show();
	});	
}


function settalktime(tt,r)
{

            document.myform.talktime.value=tt;
            document.myform.recharge.value=r;
       
    

}//end of settalktime function

function GetPaypal() 
{
    document.myform.action = "ReviewOrder.php";
    document.myform.submit();
}
function GetAll()
{
       
        if(document.getElementById('paypal').checked)
        {
            document.myform.action="ReviewOrder.php";
        }
        else if(document.getElementById('cashu'))
        {
            document.myform.action="CashuOrder.php";
          }
        else if(document.getElementById('moneybookers').checked)
                document.myform.action="moneybookers.php";
        else if(document.getElementById('google').checked)
                document.myform.action="checkout/googlecheckout.php";
        else if(document.getElementById('onecard').checked)
                document.myform.action="confirmation.php";

        document.myform.submit();

}               

function load_details(obj,page,ths){
	var objPage = $(obj).attr('page');
	if($(ths).hasClass('active'))
	{
		$(ths).removeClass('active');
		$(obj).hide();			
	}
	else
	{
		$('.ld').removeClass('active');
		$(ths).addClass('active');
		if(objPage != page)
			$(obj).load(page,function()
			{
				$(obj).show().attr('page',page);
			})
		else
			$(obj).show();	
	}	
}	

 $(document).ready(function() {	 
	 $("#fbottom .homelink").hide();
	
	//feedback submit ajax
		submit_now =function()
			{
				var subj = $("#subject").val();
				var desc= $("#Discription").val();
				if(subj.length >5 && desc.length >20) 
				{ 
				$.ajax({
				 type: "POST",
				 url: "action_layer.php?action=feedback",
				 data: { subject: subj,discription :desc },
						success: function(msg)
						{	
							$("#subject").val("");
							$("#Discription").val("");	 		 
							alert(msg);
						}
					});
				}
				else
				{
					alert('Please Provide Proper Information');
				}
			}
	$("#signin_submit").click(submit_now);

$('.scrollBox').tinyscrollbar();
//$(".register").colorbox();//initilisation of colorbox

$('.loginBtn').mouseenter(function(){
	$(this).hide();
	$(".login").animate({"top": "0px"}, "fast");
	$(".login").css({"z-index": "99999","-moz-box-shadow":"0 1px 5px #ccc","-webkit-box-shadow":"0 1px 5px #ccc","box-shadow":"0 1px 5px #ccc"});
	})
	
/*	:0 1px 1px #ccc;  :0 1px 1px #ccc;  : ;*/
$("#demourl").click(function(){					
		$("#howitworks").show();
		$('html,body,window').animate({scrollTop: $("#howitworks").offset().top},'slow');		
	})
	
	$('.tip').tipTip();	
 });/*End doc Ready*/
 
/*Plugins*/


 /*TinyScrollBar*/
(function($){$.tiny=$.tiny||{};$.tiny.scrollbar={options:{axis:'y',wheel:40,scroll:true,size:'auto',sizethumb:'auto'}};$.fn.tinyscrollbar=function(options){var options=$.extend({},$.tiny.scrollbar.options,options);/*$(this).mouseenter(function(){$('.scrollBox .scrollbar').show();});$(this).mouseleave(function(){$('.scrollBox .scrollbar').hide();});*/this.each(function(){$(this).data('tsb',new Scrollbar($(this),options));});return this;};$.fn.tinyscrollbar_update=function(sScroll){return $(this).data('tsb').update(sScroll);};function Scrollbar(root,options){var oSelf=this;var oWrapper=root;var oViewport={obj:$('.viewport',root)};var oContent={obj:$('.overview',root)};var oScrollbar={obj:$('.scrollbar',root)};var oTrack={obj:$('.track',oScrollbar.obj)};var oThumb={obj:$('.thumb',oScrollbar.obj)};var sAxis=options.axis=='x',sDirection=sAxis?'left':'top',sSize=sAxis?'Width':'Height';var iScroll,iPosition={start:0,now:0},iMouse={};function initialize(){oSelf.update();setEvents();return oSelf;}
this.update=function(sScroll){oViewport[options.axis]=oViewport.obj[0]['offset'+sSize];oContent[options.axis]=oContent.obj[0]['scroll'+sSize];oContent.ratio=oViewport[options.axis]/oContent[options.axis];oScrollbar.obj.toggleClass('disable',oContent.ratio>=1);oTrack[options.axis]=options.size=='auto'?oViewport[options.axis]:options.size;oThumb[options.axis]=Math.min(oTrack[options.axis],Math.max(0,(options.sizethumb=='auto'?(oTrack[options.axis]*oContent.ratio):options.sizethumb)));oScrollbar.ratio=options.sizethumb=='auto'?(oContent[options.axis]/oTrack[options.axis]):(oContent[options.axis]-oViewport[options.axis])/(oTrack[options.axis]-oThumb[options.axis]);iScroll=(sScroll=='relative'&&oContent.ratio<=1)?Math.min((oContent[options.axis]-oViewport[options.axis]),Math.max(0,iScroll)):0;iScroll=(sScroll=='bottom'&&oContent.ratio<=1)?(oContent[options.axis]-oViewport[options.axis]):isNaN(parseInt(sScroll))?iScroll:parseInt(sScroll);setSize();};function setSize(){oThumb.obj.css(sDirection,iScroll/oScrollbar.ratio);oContent.obj.css(sDirection,-iScroll);iMouse['start']=oThumb.obj.offset()[sDirection];var sCssSize=sSize.toLowerCase();oScrollbar.obj.css(sCssSize,oTrack[options.axis]);oTrack.obj.css(sCssSize,oTrack[options.axis]);oThumb.obj.css(sCssSize,oThumb[options.axis]);};function setEvents(){oThumb.obj.bind('mousedown',start);oThumb.obj[0].ontouchstart=function(oEvent){oEvent.preventDefault();oThumb.obj.unbind('mousedown');start(oEvent.touches[0]);return false;};oTrack.obj.bind('mouseup',drag);if(options.scroll&&this.addEventListener){oWrapper[0].addEventListener('DOMMouseScroll',wheel,false);oWrapper[0].addEventListener('mousewheel',wheel,false);}
else if(options.scroll){oWrapper[0].onmousewheel=wheel;}};function start(oEvent){iMouse.start=sAxis?oEvent.pageX:oEvent.pageY;var oThumbDir=parseInt(oThumb.obj.css(sDirection));iPosition.start=oThumbDir=='auto'?0:oThumbDir;$(document).bind('mousemove',drag);document.ontouchmove=function(oEvent){$(document).unbind('mousemove');drag(oEvent.touches[0]);};$(document).bind('mouseup',end);oThumb.obj.bind('mouseup',end);oThumb.obj[0].ontouchend=document.ontouchend=function(oEvent){$(document).unbind('mouseup');oThumb.obj.unbind('mouseup');end(oEvent.touches[0]);};return false;};function wheel(oEvent){if(!(oContent.ratio>=1)){oEvent=$.event.fix(oEvent||window.event);var iDelta=oEvent.wheelDelta?oEvent.wheelDelta/120:-oEvent.detail/3;iScroll-=iDelta*options.wheel;iScroll=Math.min((oContent[options.axis]-oViewport[options.axis]),Math.max(0,iScroll));oThumb.obj.css(sDirection,iScroll/oScrollbar.ratio);oContent.obj.css(sDirection,-iScroll);oEvent.preventDefault();};};function end(oEvent){$(document).unbind('mousemove',drag);$(document).unbind('mouseup',end);oThumb.obj.unbind('mouseup',end);document.ontouchmove=oThumb.obj[0].ontouchend=document.ontouchend=null;return false;};function drag(oEvent){if(!(oContent.ratio>=1)){iPosition.now=Math.min((oTrack[options.axis]-oThumb[options.axis]),Math.max(0,(iPosition.start+((sAxis?oEvent.pageX:oEvent.pageY)-iMouse.start))));iScroll=iPosition.now*oScrollbar.ratio;oContent.obj.css(sDirection,-iScroll);oThumb.obj.css(sDirection,iPosition.now);;}
return false;};return initialize();};})(jQuery);

 /*
 * TipTip
 * Copyright 2010 Drew Wilson
 * www.drewwilson.com
 * code.drewwilson.com/entry/tiptip-jquery-plugin */
(function($){$.fn.tipTip=function(options){var defaults={activation:"hover",keepAlive:false,maxWidth:"200px",edgeOffset:3,defaultPosition:"bottom",delay:0,fadeIn:200,fadeOut:200,attribute:"title",content:false,enter:function(){},exit:function(){}};var opts=$.extend(defaults,options);if($("#tiptip_holder").length<=0){var tiptip_holder=$('<div id="tiptip_holder" style="max-width:'+opts.maxWidth+';"></div>');var tiptip_content=$('<div id="tiptip_content"></div>');var tiptip_arrow=$('<div id="tiptip_arrow"></div>');$("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow.html('<div id="tiptip_arrow_inner"></div>')))}else{var tiptip_holder=$("#tiptip_holder");var tiptip_content=$("#tiptip_content");var tiptip_arrow=$("#tiptip_arrow")}return this.each(function(){var org_elem=$(this);if(opts.content){var org_title=opts.content}else{var org_title=org_elem.attr(opts.attribute)}if(org_title!=""){if(!opts.content){org_elem.removeAttr(opts.attribute)}var timeout=false;if(opts.activation=="hover"){org_elem.hover(function(){active_tiptip()},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}else if(opts.activation=="focus"){org_elem.focus(function(){active_tiptip()}).blur(function(){deactive_tiptip()})}else if(opts.activation=="click"){org_elem.click(function(){active_tiptip();return false}).hover(function(){},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}function active_tiptip(){opts.enter.call(this);tiptip_content.html(org_title);tiptip_holder.hide().removeAttr("class").css("margin","0");tiptip_arrow.removeAttr("style");var top=parseInt(org_elem.offset()['top']);var left=parseInt(org_elem.offset()['left']);var org_width=parseInt(org_elem.outerWidth());var org_height=parseInt(org_elem.outerHeight());var tip_w=tiptip_holder.outerWidth();var tip_h=tiptip_holder.outerHeight();var w_compare=Math.round((org_width-tip_w)/2);var h_compare=Math.round((org_height-tip_h)/2);var marg_left=Math.round(left+w_compare);var marg_top=Math.round(top+org_height+opts.edgeOffset);var t_class="";var arrow_top="";var arrow_left=Math.round(tip_w-12)/2;if(opts.defaultPosition=="bottom"){t_class="_bottom"}else if(opts.defaultPosition=="top"){t_class="_top"}else if(opts.defaultPosition=="left"){t_class="_left"}else if(opts.defaultPosition=="right"){t_class="_right"}var right_compare=(w_compare+left)<parseInt($(window).scrollLeft());var left_compare=(tip_w+left)>parseInt($(window).width());if((right_compare&&w_compare<0)||(t_class=="_right"&&!left_compare)||(t_class=="_left"&&left<(tip_w+opts.edgeOffset+5))){t_class="_right";arrow_top=Math.round(tip_h-13)/2;arrow_left=-12;marg_left=Math.round(left+org_width+opts.edgeOffset);marg_top=Math.round(top+h_compare)}else if((left_compare&&w_compare<0)||(t_class=="_left"&&!right_compare)){t_class="_left";arrow_top=Math.round(tip_h-13)/2;arrow_left=Math.round(tip_w);marg_left=Math.round(left-(tip_w+opts.edgeOffset+5));marg_top=Math.round(top+h_compare)}var top_compare=(top+org_height+opts.edgeOffset+tip_h+8)>parseInt($(window).height()+$(window).scrollTop());var bottom_compare=((top+org_height)-(opts.edgeOffset+tip_h+8))<0;if(top_compare||(t_class=="_bottom"&&top_compare)||(t_class=="_top"&&!bottom_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_top"}else{t_class=t_class+"_top"}arrow_top=tip_h;marg_top=Math.round(top-(tip_h+5+opts.edgeOffset))}else if(bottom_compare|(t_class=="_top"&&bottom_compare)||(t_class=="_bottom"&&!top_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_bottom"}else{t_class=t_class+"_bottom"}arrow_top=-12;marg_top=Math.round(top+org_height+opts.edgeOffset)}if(t_class=="_right_top"||t_class=="_left_top"){marg_top=marg_top+5}else if(t_class=="_right_bottom"||t_class=="_left_bottom"){marg_top=marg_top-5}if(t_class=="_left_top"||t_class=="_left_bottom"){marg_left=marg_left+5}tiptip_arrow.css({"margin-left":arrow_left+"px","margin-top":arrow_top+"px"});tiptip_holder.css({"margin-left":marg_left+"px","margin-top":marg_top+"px"}).attr("class","tip"+t_class);if(timeout){clearTimeout(timeout)}timeout=setTimeout(function(){tiptip_holder.stop(true,true).fadeIn(opts.fadeIn)},opts.delay)}function deactive_tiptip(){opts.exit.call(this);if(timeout){clearTimeout(timeout)}tiptip_holder.fadeOut(opts.fadeOut)}}})}})(jQuery);
