<?php

/**
* @author Ankit Patidar
* @since 13/05/2014
* @uses file to save and show paypal payment gateway detail
* 
*/

//Include Common Configuration File First
include_once('config.php');
if (!$funobj->login_validate() ) {
    $funobj->redirect("index.php");
}



#extract data from the stored array  
//extract(profile1($userid));
    
?>
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<div id="profileBox">
<div class="setContainer settRightSec">
    <h2 class="h2 fwN">Payment Gateway Details</h2>
    <p class="mrT1 mrB">Paypal</p>
    <form id="paymentDetail" action="">
    <div class="">
        <input type="text" name="paypal" id="paypal"  value="" placeholder="Merchant Id" />
    </div>
    <p class="mrT1 mrB" >Activation status</p>
    <div id="cprow" name="gender1" >
        <input id="enable"   type="radio" value="1" name="status" />
        <label for="enable">enable</label>
        
        <input id="disable" type="radio"  value="0" name="status" />
        <label for="disable">disable</label>
    </div>
   <input class="mrT2 btn btn-medium btn-primary"  type="submit" name="save" id="save" value="Save" />
    </form>
</div>   
</div>

<script type="text/javascript">
dynamicPageName('payment details')
slideAndBack('.slideLeft','.slideRight');    
    $(document).ready(function() {    
		var options = {                      
                        url:"/controller/settingController.php?call=editPaymentGatewayDetails", 
			dataType:  'json',
			type: 'POST',
			beforeSubmit:  showRequest,  // pre-submit callback 
			success:     
                                function(text)
                                {
                                show_message(text.msg,text.status);
                                }                                       
		};
		$('#paymentDetail').ajaxForm(options);                 
	}); 
	// pre-submit callback 
	function showRequest(formData, jqForm, options) { 
		$("#loading").show();
		if($("#paymentDetail").valid())
			return true; 
		else
			return false;
	} 	
	$.validator.setDefaults({
	submitHandler: function() { console.log("submitted!"); }
	});
        $().ready(function() {
	// validate the comment form when it is submitted	
	$("#paymentDetail").validate({
		rules: {
			paypal :{
				required: true,
				maxlength: 50                              
			},	
                 status: "required"
                
		},
		messages: {
				name: {
					required: "Please enter Merchant id",
					
				},	
	            status: "Please select the activation status"
			}
	});
	
// validate signup form on keyup and submit
	});

    $('#paymentDetail').validate();
 </script>  
   
   <script type="text/javascript">

        
$.ajax({
    url:"controller/settingController.php",
    type:"post",
    data:{"call":"getPaymentGatewayDetails"},
    dataType:"json",
    success:function(response){
//        console.log(response);
        $('#paypal').val(response.merchantId);
        if(response.status == 1)
            $('#enable').attr("checked",true);
        else
            $('#disable').attr("checked",true);
    }
    
    
})
</script>
 