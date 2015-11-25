<?php

/*
* Author:Balachandra
* date:22-07-2013
* modified by : sameer rahtod
* date:18-10-2013
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
    <h2 class="h2 fwN">My Profile</h2>
    <p class="mrT1 mrB">Your Name</p>
    <form id="userProfile" action="">
    <div class="">
        <input type="text" name="name" id="name"  value="" />
    </div>
    <p class="mrT1 mrB" >Gender</p>
    <div id="cprow" name="gender1" >
        <input id="male"   type="radio" value="1" name="gender" />
        <label for="male">Male</label>
        
        <input id="female" type="radio"  value="0" name="gender" />
        <label for="female">Female</label>
    </div>

    <p class="mrT2 mrB">Date of Birth</p>
    <div class="">
    	<input type="text" id="dob" name="dob" value=""/>
    </div>
    
    <p class="mrT2 mrB">Occupation</p>
    <div>
    	<input type="text"  name="ocupation" id="ocupation" value=""/>
    </div>
    
    <p class="mrT2 mrB">Country</p>
    <div>
    	<input type="text"  id="country" name="country" value=""/>
    </div>
    
     <p class="mrT2 mrB">City</p>
    <div>
    	<input type="text" id="city" name="city" value=""/>
    </div>
    
    <p class="mrT2 mrB">Zipcode</p>
    <div>
    	<input type="text" id="zip" name="zip" value="" />
    </div>
    
    <p class="mrT2 mrB">Address</p>
    <div>
    	<textarea class="rn" cols="30" rows="5" id="address" name="address"></textarea>
    </div>
    <input class="mrT2 btn btn-medium btn-primary"  type="submit" name="save" id="save" value="Save" />
    </form>
</div>   
</div>

<script type="text/javascript">
dynamicPageName('Personal')
slideAndBack('.slideLeft','.slideRight');    
    $(document).ready(function() {    
		var options = {                      
                        url:"action_layer.php?action=edit_details", 
			dataType:  'json',
			type: 'POST',
			beforeSubmit:  showRequest,  // pre-submit callback 
			success:     
                                function(text)
                                {
                                show_message(text.msg,text.msgtype);
                                }                                       
		};
		$('#userProfile').ajaxForm(options);                 
	}); 
	// pre-submit callback 
	function showRequest(formData, jqForm, options) { 
		$("#loading").show();
		if($("#userProfile").valid())
			return true; 
		else
			return false;
	} 	
	$.validator.setDefaults({
	submitHandler: function() { console.log("submitted!"); }
	});
        $().ready(function() {
	// validate the comment form when it is submitted	
	$("#userProfile").validate({
		rules: {
			name :{
				required: true,
				maxlength: 25                              
			},	
                 gender: "required",
                 dob: {
				required: true,
				minlength: 10
			},
			city: {
				required: true,
				minlength: 2,
                                maxlength: 20
			},
                        ocupation:{
                                 required:true,
                                 minlength:2,
                                 maxlength:20
                        },
			zip: {
				required: true,
				minlength: 4,
                                maxlength:15,
                                number:true
			},
			country: {
				required: true,
				minlength: 2,
                                maxlength:20
			}
		},
		messages: {
			name: {
				required: "Please enter your name",
				
			},	
                        gender: "Please select the gender",
			dob: {
				required: "Please enter Date of birth",
				minlength: "Your Dob must consist of at least 10 characters"
			},
			city: {
				required: "Please provide City name",
				minlength: "Your City Name must be at least 2 characters long"
			},
                        ocupation: {
				required: "Please provide Occupation",
				minlength: "Your Occupation must be at least 2 characters long"
			},
			zip: {
				required: "Please provide zipcode",
				minlength: "Your zipcode must be at least 4 characters long"				
			},
			country: {
				required: "Please provide Country Name",
				minlength: "Your Country Name must be at least 2 characters long"				
			}
		}
	});
	
// validate signup form on keyup and submit
	});
$("#dob").datepicker({
            changeMonth: true,
            changeYear: true,
             maxDate:0,
             yearRange: "1950:2003",
            dateFormat:"yy-mm-dd"
    });
    $('#userProfile').validate();
 </script>  
   
   <script type="text/javascript">

        
$.ajax({
    url:"controller/settingController.php",
    type:"post",
    data:{"call":"getProfileDetails"},
    dataType:"json",
    success:function(response){
//        console.log(response);
        $('#name').val(response.name);
        $('#dob').val(response.dob);
        $('#ocupation').val(response.ocupation);
        $('#country').val(response.country);
        $('#city').val(response.city);
        $('#zip').val(response.zip);
        $('#address').html(response.address);
        if(response.gender == 1)
            $('#male').attr("checked",true);
        else
            $('#female').attr("checked",true);
    }
    
    
})
</script>
 