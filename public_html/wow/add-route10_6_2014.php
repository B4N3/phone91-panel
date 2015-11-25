<?php


?>
<a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<!--Reseller Add Pins-->
<form id="batchpin" name="batchpin" action="">
	<div id="addClntForm" class="formElemt">                    
		<div class="fields">
			<label>Name</label>
			<input type="text" name="routeName"/>
		</div>
		
		<div class="fields">
			<label>Quality (1-10)</label> 
                        <select id="quality" name="routeQuality">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select>
		</div>
		
		 <div class="fields">   
                        <label>User Name</label>
			   <input type="text" id="routeUserName" name="routeUserName" /> 
		</div>
		 <div class="fields">   
                        <label>Password </label>
			   <input type="password" id="routePassword" name="routePassword" /> 
		</div>
		
		<div class="fields">
			   <label> IPs (one or more IPs)</label>
			<div class="addIpsWrp">
			   <div class="addsipInput">
					 <input type="text" placeholder="192.168.1.1" name="routeIps">
					 <!--<span title="Delete" class="ic-24 delete cp" onclick="delIpRow(this)"></span>--> 
			   </div>			   
			   <!--<p onclick="newIpRow(this)" class="arBorder secondry fl cp sucsses cp" title="Add">-->
						<!--<span class="ic-16 add "></span>-->
              	   </p>
			</div>				   
		</div>
		
		<div class="fields">
			<label>Call Limit</label>
			<input type="text" name="routeCallLimit" />
		</div>
	   
		<div class="fields">
			<label>Prefix</label>
			<input type="text" name="routePrefix" />
		</div>		 
	   
		<button value="Add Route" class="btn btn-medium btn-primary clear alC" type="submit" title="Add Batch">
			<div class="tryc tr3">
				<span class="ic-16 add"></span>
				<span>Add route</span>
			</div>
		</button>
        
		</div>
</form>    
<!--//Reseller Add Pins-->            
<script type="text/javascript">
function newIpRow(ts){
	var clone = $(ts).prev().clone();	
	$(ts).before(clone);	
}
function delIpRow(ts){
	if($('.addsipInput').length > 1)	
		$(ts).parent().remove();
	else
		$(ts).prev().val('');	
}
// function call before submit ajex for validation
function showRequest(formData, jqForm, options){ 
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
                                maxlength: 20
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

</script>