<?php
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 08-aug-2013
 * @package Phone91
 * @details reseller add pin page for create new batch of pin 
 *///Include Common Configuration File First
include_once('config.php');
include_once CLASS_DIR.'plan_class.php';
$funObj = new fun();

if (!$funobj->login_validate() || !$funobj->check_reseller()) {
    $funobj->redirect("index.php");
}

$planDetail=$funObj->getTariffIdandName($_SESSION['userid']);
$planObj = new plan_class();
#get default plan 
$defaultPlan = $planObj->getUserDefaultPlan($_SESSION['userid']);


 foreach($planDetail as $key=>$value){ 
      $tariffOptions.= '<option value="'.$key.'">'.$value.'</option>';
 }
$tariffOptions .= '<option value="'.$defaultPlan.'">Default Plan</option>';

?>
<!--Reseller Add Pins-->
<form id="batchpin" name="batchpin" action="">
	<div id="addClntForm">
    				<div class="reSellerhead">Add PINs</div>
                    
<!--                    <div class="fields">
                    	<label>Pin Format eg(abc123)</label>
                        <input type="text" name="pinFormate"/>
                    </div>-->
                    
                    <div class="fields">
                    	<label>Batch Name</label>
                        <input type="text" name="bname"/>
                    </div>
                    
                    <div class="fields">
                    	<label>No. of PINs</label> 
                        <input type="text" name="totalPins"/>
                    </div>
                    
                     <div class="fields">   
                    		<label>Tariff Plan </label>
                            <select name="tariff_Plan">
                                <?php echo $tariffOptions;?>
                            </select>
                    </div>
                    
                    <div class="fields">
                           <label> Batch Expiry</label>
                           <input type="text" id="Expirydate" name="expiry_date" />
                    </div>
                    
                    <div class="fields">
                    	<label>Amount per PIN </label>
                        <input type="text" name="amountPerPin" />
                    </div>
                    
                     <div class="fields">
                  			<label>Payment Type</label>
                            <div id="paymentType" class="clear btnlbl">
                                <input type="radio" id="prepaid" name="pType" onchange="showNext('partialWrap',false);"/><label for="prepaid">Prepaid</label>
                                <input type="radio" id="postpaid" name="pType" onchange="showNext('partialWrap',false);" checked="checked" /><label for="postpaid">Postpaid</label>
                                <input type="radio" id="partial" name="pType" onchange="showNext('partialWrap',true);"  /><label for="partial">Partial</label>
                            </div>
                    </div>
                    
                     <div id="partialWrap" class="dn">
                            <div class="fields">  
                                    <label>Partial Amount</label>
                                    <input type="text" name="partialAmount" />
                            </div>
                            
                            <div class="fields">  
                                    <label>Currency</label>
                                    <select name="currency">
                                        <option>Choose</option>
                                        <option>USD</option>
                                        <option>INR</option>
                                        <option>GBP</option>
                                    </select>
                            </div>
                       </div>
                    <div class="fields">
                        <input type="checkbox" name="listenRemainingTime" />
              	        Listen the remaining time during the call.
       
                    </div>
                   <button value="Add Batch" class="btn btn-medium btn-primary clear alC" type="submit" title="Add Batch">
                         <div class="tryc tr3">
                                <span class="ic-16 add"></span>
                                <span>Add Batch</span>
                             </div>
            	</button>
         </div>
</form>    
<!--//Reseller Add Pins-->            
<script type="text/javascript">
$(function() {
	$( "#paymentType, #BatchType" ).buttonset();
       
        $("#Expirydate").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate:0,
            dateFormat:"yy-mm-dd"
    });
});
function showNext(id,status){
    if(status)
	$( "#"+id ).show();
    else
        $( "#"+id ).hide();
}
//submit jquery ajex form for creat pin batch
$(document).ready(function() { 
      var options = { 
                  url : "action_layer.php?action=createPinBatch",
                  type: "POST",dataType: "json",
                  beforeSubmit:  showRequest,  // pre-submit callback 
                  success:       showResponse  // post-submit callback 
                  
          }; 
          $('#batchpin').ajaxForm(options); 
  }); 
  // function call before submit ajex for validation
function showRequest(formData, jqForm, options) { 
        $.validator.setDefaults({
            submitHandler: function() { console.log("submitted!"); }
         });
         
        $().ready(function() {
            // validate the comment form when it is submitted	
            $("#batchpin").validate({
                    rules: {
                            bname :{
				required: true,
				minlength: 3,
                                maxlength: 25
                                     },
                            totalPins :{
				required: true,
                                number:true,
                                maxlength: 3
				       },   
                            expiry_date :{
				required: true
				     },
                            amountPerPin :{
				required: true,
				number:true,
                                maxlength:4
                                     },
                            partialAmount :{
				required: false,
				number:true
                                     }      
                                     
                        
                    }
            })
          })
        if($("#batchpin").valid())
                return true; 
        else
                return false;
  }
function showResponse(response, statusText, xhr, $form)  {
    show_message(response.msg,response.msgtype);
    if(response.msgtype == "success"){
        var str = batchlistDesign(response);
       
        
        $('#leftsec ul').html('');
        $('#leftsec ul').html(str);    
         $("#batchpin")[0].reset();



    }
 		
 }   
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

