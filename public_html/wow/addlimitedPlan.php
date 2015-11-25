<?php
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 08-aug-2013
 * @package Phone91
 * @details reseller add limited plan page for create new batch of pin 
 *///Include Common Configuration File First
include dirname(dirname(__FILE__)) . '/config.php';

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}

?>
<a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<!--Reseller Add Pins-->
<form id="specialPlan" name="specialPlan" action="">
	<div id="addplanForm" class="formElemt">
    				<div class="reSellerhead">Add Limited Plan : </div>
                    
                    <div class="fields">
                    	<label>Plan Name</label>
                        <input type="text" name="planName"/>
                    </div>
                    
                    <div class="fields">
                    	<label>Tariff Rate</label> 
                        <input type="text" name="tariffRate"/> <span>USD</span>
                    </div>
                    
                     <div class="fields">   
                    	<label>Minutes</label>
                        <input type="text" name="minutes"/><span>min</span>
                    </div>
                    
                    <div class="fields">
                           <label>Day Limit</label>
                           <input type="text" name="dayLimit"/>
                    </div>
                    
                    <div class="fields">
                    	<label>Hours Limit</label>
                        <input type="text" name="hourLimit" />
                    </div>
                    
                    <div class="fields">
                    	<label>Call Limit</label>
                        <input type="text" name="callLimit" />
                    </div>
                     
                    <button value="Add Plan" class="btn btn-medium btn-primary clear alC" type="submit" title="Add Plan">
                         <div class="tryc tr3">
                                <span class="ic-16 add"></span>
                                <span>Add Plan</span>
                             </div>
            	</button>
         </div>
</form>    
<!--//Reseller Add Pins-->            
<script type="text/javascript">


//submit jquery ajex form for creat pin batch
$(document).ready(function() { 
      var options = { 
                  url : "/controller/adminManageClientCnt.php?action=AddLimitedPlan",
                  type: "POST",dataType: "json",
                  beforeSubmit:  showRequest,  // pre-submit callback 
                  success:       showResponse  // post-submit callback 
                  
          }; 
          $('#specialPlan').ajaxForm(options); 
  }); 
  // function call before submit ajex for validation
function showRequest(formData, jqForm, options) { 
        $().ready(function() {
            // validate the comment form when it is submitted	
            $("#specialPlan").validate({
                    rules: {
                            planName :{
				required: true,
				minlength: 3,
                                maxlength: 20
                                     },
                            tariffRate :{
				required: true,
                                number:true,
                                maxlength: 3
				       },   
                            minutes :{
				required: true,
                                number:true,
                                maxlength: 5
				     },
                            dayLimit :{
				required: true,
				number:true,
                                maxlength:3
                                     },
                            hourLimit :{
				required: true,
				number:true,
                                maxlength:3
                                     },   
                            callLimit :{
				required: true,
				number:true,
                                maxlength:2
                                     }       
                                     
                        
                    }
            })
          })
        if($("#specialPlan").valid())
                return true; 
        else
                return false;
  }
function showResponse(response, statusText, xhr, $form)  {
    show_message(response.msg,response.status);
       
    if(response.status == "success"){
       
        var str = planlistDesign(response.limitedPlan);
        $('#leftsec ul').html('');
        $('#leftsec ul').html(str);    
        $(':input','#specialPlan')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected')
            .removeClass('valid error');


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

