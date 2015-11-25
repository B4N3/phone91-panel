<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @package Phone91
 * @details file use for add new client 
 */

//Include Common Configuration File First
include_once('config.php');
include_once CLASS_DIR.'reseller_class.php';
$resellerObj = new reseller_class();
#find country name 
function countryArray(){
$url = "https://voice.phone91.com/isoData.php";   
     $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    
$string1 = json_decode($data, true);
for($i=0;$i<count($string1);$i++){
    $country[$string1[$i]['CountryCode']]=$string1[$i]['Country'];
    
} 
return $country;
}
$country = $resellerObj->countryCodes();
$currencyArray=$resellerObj->currencyArray();
//var_dump($currencyArray);
?>


<!--Add New Client Tabs Content-->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Add New Client</a></li>
        <li><a href="#tabs-2">Generate Bulk Clients</a></li>
    </ul>

    <!--1 st Tab Content-->
    <div id="tabs-1" class="paddingInner">
        <form id="addNewClient" action="">
         	   <div id="addClntForm" >
				
                    <div class="fields">
                      <label>Name<sup>*</sup></label>
	                    <input type="text" id="name" name="name"/>
                    </div>
                   
                 	<div class="fields">
                		<label>Username<sup>*</sup></label>
                   		<input type="text" name="username" id="username"/>
                	</div>
                
                    <div class="fields">
                        <label>Choose Country<sup>*</sup></label>
                        <select name="country" id="countryCodeNo">
                            <option value="">Select Country</option>
                            <?php foreach ($country as $key=>$countryname) {
                                echo "<option value='$key'>$countryname</option>";
                            } ?>
                        </select>
                    </div>
                    
					<div class="fields">
                		<label>Contact<sup>*</sup></label>
                        <p id="mobwrap">
                            <input type="text" name="contactNo_code" id="contactNo_code"  onblur="selectcountryOpt($(this).val())"/>
                            <input type="text" name="contactNumber" id="contactNumber"/>
                        </p>
                	</div>
                    
                  <div class="fields">  
                  		<label>E-mail<sup>*</sup></label>
                        <input type="text" name="email" id="email"/>
                  </div>
                  
  				  <div class="fields">  
                       <label>Password<sup>*</sup></label>
                       <input type="password" id="password" name="password">
                  </div>

              	  <div class="fields">
                       <label>
                       		<div class="fr">
                                <span class="fl">Tariff<sup>*</sup></span>
                                <i title="Select the pricing plan you want to assign. But you’ll need to create a plan first. To create a plan, go to, manage plan, and select add plan." class="ic-16 help fr"></i>
                            </div>
                       </label>
                       <select name="tariff"  class="selPlan">
                            <?php // echo $tariffOptions;?>
                       </select>
                  </div>
                   
                   
                  <div class="dn fields" id="clientotherType">
                        <label>Enter Type<sup>*</sup></label>
                        <input type="text" name="clientotherType"  />
                  </div>
                  
  
                 <div class="fields">          
                       <label>
                       		<div class="fr">
                                <span class="fl">User Type<sup>*</sup></span>
                                <i title="Select whether the new client will be a user or reseller." class="ic-16 help fr"></i>
                            </div>
                       </label>
                       <select name="userType" id="userType">
                            <option value="3">User</option>
                            <option value="2">Reseller</option>
                        </select>
                   </div>      
                       
                <div class="fields">
					<label>&nbsp;</label>
					<button type="submit" class="btn btn-medium btn-blue clear alC addSpaceBtn"  value="ADD" title="Add">
						 <div class="tryc tr3">
							<span class="ic-16 add"></span>
							<span>Add</span>
						 </div>
					</button>
				</div>
                
            </div>
        </form>
    </div>
    <!--//1 st Tab Content-->
    
    <!--2nd Tab Content-->
    <div id="tabs-2"  class="paddingInner"> 
        <form id="addBulkClient" method="POST" action="action_layer.php?action=addNewBatch">
        <div id="addClntForm" class="batch">
        
			<div class="fields">
                <label>
                    <div class="fr">
                        <span class="fl">Batch Name<sup>*</sup></span>
                        <i title="Name of the batch. " class="ic-16 help fr"></i>
                    </div>
                </label>
                <input type="text" name="batchName" id="batchName" />
            </div>
            
            <div class="fields">
                <label>Number of Clients<sup>*</sup></label>
                <input type="text" name="totalClients"  id="totalClients"/>
            </div>
            
            <div class="fields">
                <label>
                    <div class="fr">
                        <span class="fl">Tariff<sup>*</sup></span>
                        <i title="Select the pricing plan you want to assign. But you’ll need to create a plan first. To create a plan, go to, manage plan, and select add plan." class="ic-16 help fr"></i>
                    </div>
                </label>
                <select name="tariff" class="selPlan" onchange="chaneTariff(this)">
                    <?php // echo $tariffOptions;?>
                </select>
            </div>
            
             <div class="fields">
                <label>Batch Expiry<sup>*</sup></label>
                <input type="text" name="batchExpiry" id="expiryDate"/>
            </div>
            
            <div id="recAmt" class="sporow fields">
                <label>
                    <div class="fr">
                        <span class="fl">Recharge Amount<sup>*</sup></span>
                        <i title="Amount of the talktime you are giving or receiving" class="ic-16 help fr"></i>
                    </div>
                </label>
                <input type="text" id="rechargeAmt" name="rechargeAmt" placeholder="Amount"/>
                <select id="fundCurrency" name="fundCurrency" class="small currencyList">
                    
                </select>
            </div>  
            
            <div id="talktime" class="fields pr fl">
                <label>
                    <div class="fr">
                        <p class="fl">Talktime<sup>*</sup></p>
                        <i title="Per user calling balance." class="ic-16 help fr"></i>
                    </div>
                </label>
                <input type="text" name="balance" id="balanceInput" />
                <span class="fl mrT" id="currencyName" style="top:1px; right:10px; left:auto"></span>
            </div>
            
            <div class="fields">
                <label>Payment Type<sup>*</sup></label>
                <select id="payType" name="payType" onchange="showNext(this)">
                    <option value="select">Select</option>
                    <option id="advance" value="prepaid" >Prepaid</option>
                    <option id="partial" value="partial" >Partial</option>
                    <option id="credit"  value="postpaid" > Postpaid</option>
                </select>
           </div> 
           
           <div class="fields" id="cashMemoBank">
                <label>Paid via<sup>*</sup></label>
                <select name="payTypeBulk" id="payTypeBulk">
                    <option value="Cash">Cash</option>
                    <option value="Memo">Memo</option>
                    <option value="Bank">Bank</option>
                    <option value="Other">Other</option>
                </select>
          	</div>
            
            <div class="dn fields" id="otherType">
                <label>Enter Type<sup>*</sup></label>
                <input type="text" name="otherType"  />
            </div>
           
            <div id="partialWrap" class="fields dn">
                  <div class="sporow fields">
                      <label>Partial Amount<sup>*</sup></label>
                      <input type="text" id="partialAmt" name="partialAmt" placeholder="Amount"/>
                      <select name="partialCurrency" id="partialCurrency" class="currencyList">
                            
                      </select>
                  </div>                                           
            </div>
            
         	<!--<input type="button" id="">-->
            <div class="fields">
            	<label>&nbsp;</label>
                <button class="btn btn-blue clear alC addSpaceBtn" href="javascript:void(0)" title="Generate" id="btnAddclietn">
                    <div class="tryc tr3">
                        <span class="ic-16 add"></span>
                        <span>Generate</span>
                    </div>
                </button>
            </div>
        
        </div>
      </form>
    </div>
    <!--//2nd Tab Content-->
    
</div>
<!--//Add New Client Tabs Content-->
<script type="text/javascript">
 // currencyList is global variable initialize in panel.js    
 $('.currencyList').append(currencyList); 
 
 function selectcountryOpt(valu)
    {
        $('#countryCodeNo option[value="'+valu+'"]').prop('selected',true);
    }
  
function chaneTariff(ths){
  var tariff = $(ths).val();
  $.ajax({
                   url : "action_layer.php?action=getCurrencyName", 
                   type: "POST", 
                   data:{tariff:tariff},
                   dataType: "json",
                   success:function (text)
                   {
                       console.log(text.currencyName);
                       $('#currencyName').html(text.currencyName);
                                             
                   }
       });
  

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

$(function() {
	$( "#tabs" ).tabs({
		create: function(event, ui) {console.log($('.ui-tabs-selected a',this).text())},
		select: function(event, ui) {
		console.log(ui.tab.innerText)}
	});
});

$("#countryCodeNo").on('change',function(event){
   $("#contactNo_code").val($(this).val().replace(/ /g,''));
 }) 

$("#payTypeBulk").on('change',function(event){
   if(this.value=='Other')
       $("#otherType").show();
   else
       $("#otherType").hide();
 })

 $(document).ready(function(){
  selectPlan();   
     
     var options1={
                 url: "action_layer.php?action=addNewClient",
                 type: "post",
                 dataType:  'json',
	         //target:        '#response',   // target element(s) to be updated with server response 
		 beforeSubmit:  showRequest1,  // pre-submit callback 
		  success:       showResponse1 };
                $('#addNewClient').ajaxForm(options1);
                
 });

 function showRequest1(formData, jqForm, options) { 
   $.validator.setDefaults({
  submitHandler: function() {
      $('#btnAddclietn').attr('disabled','disabled');
  }
  });  
 
 
            jQuery.validator.addMethod('selectcheck', function (value) {
                return (!(value == '0' || value == 'select' || value =='Select' || value == ''));
            }, "Please Select proper value!"); 
            
		//var queryString = $.param(formData); 
		//alert('About to submit: \n\n' + queryString); 	    
		$("#loading").show();
		if($("#addNewClient").valid())
			return true; 
		else
			return false;
	} 
 function showResponse1(responseText, statusText, xhr, $form)  { 
		show_message(responseText.msg,responseText.status);
                if(responseText.status == "success"){
                    var str = clientListDesign(responseText.resellerClient); 
                    $('#clientList').prepend(str);
                       
                    $(':input','#addNewClient')
                                    .not(':button, :submit, :reset, :hidden')
                                    .val('')
                                    .removeAttr('checked')
                                    .removeAttr('selected')
                                    .removeClass('valid error'); 
                   }
//		consol.log(responseText.resellerClient);
                $('#btnAddclietn').removeAttr('disabled');
		$("#loading").hide();
	}
        
 function clientListDesign(value){
      var str ="";
            var url = window.location.hash; 
            var deleteClass = ''; 
            var hideIcons = '';
            var contactNo = '';
            if(value.contact_no != ''){
              contactNo = '<i class="ic-16 correct"></i><label>'+value.contact_no+'</label>';
            }
            var loginAs='';
            if(value.deleteFlag == 0){
            	hideIcons = '';
            	deleteClass = '';	
                var hrefLocation ='/controller/signUpController.php?call=loginAs&userId='+value.id+'&url='+url.substring(1);
                loginAs='<span title="Login As" class="ic-24 login loginAs clientLi'+value.id+'" onclick="redirectLoginUrl('+value.id+')"></span>';
            }
            else
            {
            	hideIcons = 'dn';
            	deleteClass = 'deleted';
            	loginAs='<span title="Login As" class="ic-24 login loginAs '+hideIcons+'" onclick="javascript:void(0);"></span>';

            }
                
            
            if(value.blockUnblockStatus != 1)
            {
                var statusClass ="disabledR";
                var Bstatus = "block";
            }
            else
            {
                var statusClass ="";
                var Bstatus = "unBlock";
            }
            
            var usertype = '';
            if(value.client_type != ''){ 
            usertype = value.client_type
            }
            

            if(usertype == 'user')
            	userClass = 'green';
            else
            	userClass = 'gold';
            var balance = parseFloat(value.balance);
            
                    str += '<li class="group slideAndBack '+deleteClass+'" id="clientLi'+value.id+'" data-id="'+value.id+'" onclick="window.location.hash=\'!reseller-manage-clients.php|reseller-client-setting.php?clientId='+value.id+'\'">\
                                    <div class="uiwrp cp">\
                                                <label>'+value.uname+' <b class="'+userClass+'">'+usertype+'</b></label>\
                                        </div>\
                                        '+loginAs+'<h3 class="ellp font22 nameClient">'+value.name+'</h3>\
                                        <div class="uiwrp cp">\
                                            '+contactNo+'\
                                        </div>\
                                        <div class="tInfo"> \
											<div class="bal"> \
												<span class="'+value.id+"changeBal"+'">'+balance.toFixed(2)+'</span>\
												'+value.id_currency_name+'\
											</div>\
											Tariff <b>'+value.planName+'</b> \
                                        </div> \
                                        <div class="actwrp action">\
                                                <div class="switch clientLi'+value.id+' '+hideIcons+'">\
                    <label onclick="changeUserStatus(this,'+value.id+');" class="ic-sw enabledR '+statusClass+'"></label>\
                    <input type="checkbox" id="changeStatus'+value.id+'" style="display:none" checked="checked"  value ="'+Bstatus+'"/>\
                        </div></div>\
                              </li>';
        
            
        

            return str;
 }        
 
 function reloadClientSetting(userid){
     window.location.href= '#!reseller-manage-clients.php|reseller-client-setting.php?clientId='+userid+'';
 }
 $().ready(function() {
	// validate the comment form when it is submitted	
         $.validator.addMethod("nameRegex", function(value, element) {
                return this.optional(element) || /^[A-Za-z][a-z0-9]+$/i.test(value);
            }, "field must contain only letters and numbers.");


	$("#addNewClient").validate({
		rules: {
                    name:{required : true,
                        minlength:5,
                        maxlength:25,
                        nameRegex:true
                    },
                    country:{
                        selectcheck: true
                    },
                    tariff:{
                        selectcheck: true
                    },
                    username:{required : true,
                        minlength:5,
                        maxlength:25,
                        nameRegex:true
                    },
                    contactNo_code:{
                        required:true,
                        number:true
                    },
                    contactNumber:{
                        required:true,
                        number:true
                    },
                    email:{
                        required:true,
                        email:true
                    },
                    password:{
                        required:true
                    },
                  messages:{
                      name: {required: "Please enter the client's name.",
                             minlegth:"Name should be of at least 5 characters.",
                             maxlength:'Maximum chararacter limit exceeded.'
                             
                      },
                      username:{required: "Please enter the client's username.",
                             minlegth:"Name should be of at least 5 characters.",
                             maxlength:'Maximum chararacter limit exceeded.'
                             
                      },
                      contactNo_code:{
                        required:"Please enter the contact number code.",
                        number:"Please enter numbers only."
                      },
                      contactNumber:{
                          required:"Please enter the contact number.",
                          number:"Please enter numbers only."
                      },       
                      email:{
                          required: "Please enter the Email address.",
                          email:"Enter a valid Email ID."
                      },
                      password:{
                        required:"Please enter the password."
                        
                      }
                          
                      }       
                  }
              }); 

                  $('#username,#name,#contactNo_code,#contactNumber,#email,#password').blur(function(){
    console.log($(this).attr('id'));
        $("#"+$(this).attr('id')).valid();
    }); 

    $('#countryCodeNo').change(function(){
    console.log($(this).attr('id'));
        $("#"+$(this).attr('id')).valid();
    }); 


 });    
//expiryDate Date Picker Jacascript Code

var now = new Date();
var future = now.setMonth(now.getMonth() + 1, 1);

console.log(new Date(future));
console.log('Nidhi');

$("#expiryDate").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: "-0Y", 
            maxDate: "+12Y",
            dateFormat:"yy-mm-dd"
    });
    
</script>
<script type="text/javascript">
    $(document).ready(function() { 
		var options = { 
			dataType:  'json',
			//target:        '#response',   // target element(s) to be updated with server response 
			beforeSubmit:  showRequest,  // pre-submit callback 
			success:       showResponse  // post-submit callback 
		}; 
		$('#addBulkClient').ajaxForm(options); 
	});
	
    // pre-submit callback 
	function showRequest(formData, jqForm, options) { 
		   
		$("#loading").show();
                
                jQuery.validator.addMethod('selectcheck', function (value) {
                return (!(value == '0' || value == 'select' || value =='Select' || value == ''));
            }, "Please Select proper value!"); 
		if($("#addBulkClient").valid())
			return true; 
		else
			return false;
	} 

	// post-submit callback 
	function showResponse(responseText, statusText, xhr, $form)  { 
		
                show_message(responseText.msg,responseText.status);
                if(responseText.status == "success"){
                     $("#addBulkClient")[0].reset();
                var str = createBulkDesign(responseText);
                $("#bulkclientList").html('');
                $("#bulkclientList").html(str);  
                     
                     
                }
		$("#loading").hide();
		//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.'); 
	} 
         $().ready(function() {
          $.validator.addMethod("nameRegex", function(value, element) {
                return this.optional(element) || /^[A-Za-z][a-z0-9]+$/i.test(value);
            }, "field must contain only letters and numbers.");
    
         
	// validate the comment form when it is submitted	
	$("#addBulkClient").validate({
		rules: {
		  batchName :{
                      required : true,
                      minlength:5,
                      maxlength:30,
                      nameRegex:true
                    },			
		totalClients: {
                        required: true,
                        minlength: 1,
                        max:1000,
                        number: true
			},
                tariff:{
                        selectcheck: true
                    },
                payType:{
                        selectcheck: true
                    },
		batchExpiry: {
			required: true,
			minlength: 10
			},
		balance: {
			required: true,
			minlength: 1,
			maxlength: 5,
                        number :true
			},
                rechargeAmt:{
                        required: true,
			minlength: 1,
			maxlength: 5,
                        number :true
                }
		},
		messages: {
			batchName: {required: "Please enter the batch name.",
                        minlegth:"The batch name should be of at least 5 characters.",
                        maxlength:'Maximum chararacter limit exceeded.'
                             
                      },	
			totalClients:{
			required:"Please enter the total number of clients.",
			minlength:"There should be minimum 2 clients in a batch.",
			maxlength:"The number of clients should not be more than 1000.",
                        number: "Please enter numbers only."
                  },
                tariff:"Please select the tariff plan.",
		batchExpiry: {
                        required: "Please enter the batch's expiry date.",
                        minlength: "The expiry date format must be YYYY-MM-DD."
		},
		balance: {
                        required: "Please enter the talktime.",
                        minlength: "Talktime should be more than zero.",
                        number:"Please enter numbers only.",
                        maxlength:"The talktime should not exceed 5 characters."
		},
                rechargeAmt: {
                        required: "Please enter the amount you have received.",
                        minlength: "Recharge amount should be more than zero.",
                        number:"Please enter numbers only.",
                        maxlength:"The recharge amount should not exceed 5 characters."
		}
		}
	});

  $('#batchName,#totalClients,#expiryDate,#rechargeAmt,#balanceInput').blur(function(){
    console.log($(this).attr('id'));
        $("#"+$(this).attr('id')).valid();
    });
	// validate signup form on keyup and submit
	});
</script>
<script type="text/javascript">
$(document).ready(function()
{
			$('.back').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "-1000px"}, "slow");
						$('.slideLeft').fadeIn(2000);
				}
			});
	});

	
	//initialise tiptip for help icon
	$(".helpW, .help").tipTip();
</script>
