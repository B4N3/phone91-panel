<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @package Phone91
 * @details file use for add new client 
 */

//Include Common Configuration File First
//include_once('config.php');
//include_once CLASS_DIR.'reseller_class.php';
//$resellerObj = new reseller_class();
#find country name 
function countryArray(){
    $country = array();
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
$country = countryArray();
//$currencyArray=$resellerObj->currencyArray();
//var_dump($currencyArray);
?>
<?php
//                        $funObj = new fun();
//                        $planDetail=$funObj->getTariffIdandName($_SESSION['userid']);
//                        
//			include_once(CLASS_DIR."plan_class.php");
//			$planObj = new plan_class();
//			#get default plan 
//                        $defaultPlan = $planObj->getUserDefaultPlan($_SESSION['id']);
//                        
//                        
//                         foreach($planDetail as $key=>$value){ 
//                              $tariffOptions.= '<option value="'.$key.'">'.$value.'</option>';
//                         }
//                        $tariffOptions .= '<option value="'.$defaultPlan.'">Default Plan</option>';
                            
?>

<!--Add New Client Tabs Content-->
<div id="tabs" class="addNewClientBox">
    <ul>
        <li><a href="#tabs-1">Add New Client</a></li>
    </ul>

    <!--1 st Tab Content-->
    <div id="tabs-1" class="paddingInner">
        <form id="addNewClient" action="">
         	   <div id="addClntForm" >
				
                    <div class="fields">
                      <label>Full Name:</label>
	                    <input type="text" id="name" name="name"/>
                    </div>
                   
                 	<div class="fields">
                		<label>Username</label>
                   		<input type="text" name="username" id="username"/>
                	</div>
                
                    <div class="fields">
                            <label>Choose Country</label>
                            <select name="country" id="countryCodeNo">
                                <option value="select_country">Select Country</option>
                                <?php foreach ($country as $key=>$countryname) {
                                    echo "<option value='$key'>$countryname</option>";
                                } ?>
                            </select>
                    </div>
                    
					<div class="fields">
                		<label>Contact</label>
                        <p id="mobwrap">
                            <input type="text" name="contactNo_code" id="contactNo_code" onblur="selectcountryOpt($(this).val())"/>
                            <input type="text" name="contactNumber" id="contactNumber"/>
                        </p>
                	</div>
                    
                  <div class="fields">  
                  		<label>E-mail</label>
                         <input type="text" name="email" id="email"/>
                  </div>
                  
  				  <div class="fields">  
                        <label>Password</label>
                        <input type="password" id="password" name="password">
                  </div>
    
<!--                    <div class="fields">          
                       <label>Payment Mode(Cash, Memo, Bank)</label>
                       <select name="payType" id="payType">
                            <option value="Cash">Cash</option>
                            <option value="Memo">Memo</option>
                            <option value="Bank">Bank</option>
                            <option value="Other">Other</option>
                        </select>
                   </div>-->
                  
<!--                  <div class="fields"> 
                        <label>Balance</label>  
                        <input type="text" name="clientBalance" ></input> 
                        <input class="btn btn-medium btn-primary clear alC mrT2" type="button" id="" onclick="addNewClient();" value="Add">
                 </div>-->
                <div class="fields">
					<label>&nbsp;</label>
					<button type="submit" class="btn btn-medium btn-primary clear alC  addSpaceBtn"  value="ADD" title="Add">
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
    
    
    
</div>
<!--//Add New Client Tabs Content-->
<script type="text/javascript">

 function selectcountryOpt(valu)
    {
        $('#countryCodeNo option[value="'+valu+'"]').prop('selected',true);
    }
    
$(function() {
	$( "#paymentType, #BatchType" ).buttonset();
});
function showNext(id,status){
    if(status)
	$( "#"+id ).show();
    else
        $( "#"+id ).hide();
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
// $("#payType").on('change',function(event){
//   if(this.value=='Other')
//       $("#clientotherType").show();
//   else
//       $("#clientotherType").hide();
// })
 //created by Balachandra<balachandra@hostnsoft.com>
 //date:06-08-2013
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
                    $('#leftsec ul').html('');
                    $('#leftsec ul').html(str);    
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
        
 function clientListDesign(text){
     var str ='';
     $.each( text, function(key, item ) {
        str +='<li class="group" onclick="reloadClientSetting('+item.userId+');">\
              <i class="ic-16 notif"></i>\
              <label>'+item.name+'</label>\
              </div>\
              <h3 class="ellp font22 nameClient">'+item.userName+'</h3>\
               <div class="uiwrp cp">\
               </div>\
              <p class="tInfo">Tariff <b>'+item.planName+'</b>\
              <span class="sep">|</span>\
              <span>'+parseFloat(item.balance).toFixed(2)+'</span>\
              </p>\
              <div class="actwrp">\
              <div class="switch">';
              if(item.isBlocked != 1){
                var statusClass ="disabledR";
                var Bstatus = "block";
              }else{
                var statusClass ="";
                var Bstatus = "unBlock";
              }
              str +='<label onclick="changeUserStatus(this,'+item.userId+');" class="ic-sw enabledR '+statusClass+'"></label>\
                     <input type="checkbox" id="changeStatus'+item.userId+'" style="display:none" checked="checked"  value ="'+Bstatus+'" />\
                     </div></div>\
                     <span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag('+item.userId+');" ></span>\
                     </li>';

            });
        return str;    
 }        
 
 function reloadClientSetting(userid){
     window.location.href= '#!reseller-manage-clients.php|reseller-client-setting.php?clientId='+userid+'';
 }
 $().ready(function() {
	// validate the comment form when it is submitted	
	$("#addNewClient").validate({
		rules: {
                    name:{required : true,
                        minlength:5,
                        maxlength:25
                    },
                    username:{required : true,
                        minlength:5,
                        maxlength:25
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
                    clientBalance:{
                        required:true,
                        number:true }
                    },
                  messages:{
                      name: {required: "Please enter Name",
                             minlegth:"Please enter atleast 5 chararacter",
                             maxlength:'Maximum chararacter limit Exceeded'
                             
                      },
                      username:{required: "Please enter Name",
                             minlegth:"Please enter atleast 5 chararacter",
                             maxlength:'Maximum chararacter limit Exceeded'
                             
                      },
                      contactNumber:{
                          reuired:"Please enter the contact number",
                          number:"must be numeric"
                      },       
                      email:{
                          required: "Please enter email address",
                          email:"Enter a valid email"
                      },
                      password:{
                        required:"Please enter the password",
                        
                      },
                      clientBalance:{
                          required:"Please enter the amount",
                          number:"Must be numeric"
                      },
                          
                      }       
                  });  
 });    
//expiryDate Date Picker Jacascript Code
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
		//var queryString = $.param(formData); 
		//alert('About to submit: \n\n' + queryString); 	    
		$("#loading").show();
		if($("#addBulkClient").valid())
			return true; 
		else
			return false;
	} 

	// post-submit callback 
	function showResponse(responseText, statusText, xhr, $form)  { 
		show_message(responseText.msg,responseText.status);
		$("#loading").hide();
		//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.'); 
	} 
         $().ready(function() {
	// validate the comment form when it is submitted	
	$("#addBulkClient").validate({
		rules: {
			batchName :"required",			
			totalClients: {
				required: true,
				minlength: 1,
                                maxlength: 3,
                                number: true
			},
            tariff:"required",
			batchExpiry: {
			required: true,
			minlength: 10
			},
			balance: {
			required: true,
			minlength: 1,
			maxlength: 5,
             number :true
			}
		},
		messages: {
			batchName: "Please enter Batch Name",	
			totalClients:{
			required:"Please enter total client",
			minlength:"Must be greater then 1",
			maxlength:"Must be less then 999",
            number: "Must Be Numeric"
        },
	  tariff:"Please select tariff Plan",
		batchExpiry: {
		required: "Please enter Date of expiry",
		minlength: "Your Expiry date must consist of at least 10 characters format YYYY-MM-DD"
		},
		balance: {
		required: "Please provide Balance",
		minlength: "Your Balance must be at least 1 characters long",
		number:"Must be numeric",
		maxlength:"Must be less then 5 characters."
		},
		zip: {
			required: "Please provide zipcode",
			minlength: "Your zipcode must be at least 4 characters long"				
		}
		}
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
</script>
