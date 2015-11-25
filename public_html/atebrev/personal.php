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
    <p class="mrT2 mrB">Select Your TimeZone</p>
    <div>
        <select id="panel_time" name="panel_time">
	<option value="+05:30">Select</option>
        <option value="-11:00">(GMT-11:00) Midway Island, Samoa</option>
	<option value="-10:00">(GMT-10:00) Hawaii-Aleutian</option>
	<option value="-10:00">(GMT-10:00) Hawaii</option>
	<option value="-09:30">(GMT-09:30) Marquesas Islands</option>
	<option value="-09:00">(GMT-09:00) Gambier Islands</option>
	<option value="-09:00">(GMT-09:00) Alaska</option>
	<option value="-08:00">(GMT-08:00) Tijuana, Baja California</option>
	<option value="-08:00">(GMT-08:00) Pitcairn Islands</option>
	<option value="-08:00">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
	<option value="-07:00">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
	<option value="-07:00">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
	<option value="-07:00">(GMT-07:00) Arizona</option>
	<option value="-06:00">(GMT-06:00) Saskatchewan, Central America</option>
	<option value="-06:00">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
	<option value="-06:00">(GMT-06:00) Easter Island</option>
	<option value="-06:00">(GMT-06:00) Central Time (US &amp; Canada)</option>
	<option value="-05:00">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
	<option value="-05:00">(GMT-05:00) Cuba</option>
	<option value="-05:00">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
	<option value="-04:30">(GMT-04:30) Caracas</option>
	<option value="-04:00">(GMT-04:00) Santiago</option>
	<option value="-04:00">(GMT-04:00) La Paz</option>
	<option value="-04:00">(GMT-04:00) Faukland Islands</option>
	<option value="-04:00">(GMT-04:00) Brazil</option>
	<option value="-04:00">(GMT-04:00) Atlantic Time (Goose Bay)</option>
	<option value="-04:00">(GMT-04:00) Atlantic Time (Canada)</option>
	<option value="-03:30">(GMT-03:30) Newfoundland</option>
	<option value="-03:00">(GMT-03:00) UTC-3</option>
	<option value="-03:00">(GMT-03:00) Montevideo</option>
	<option value="-03:00">(GMT-03:00) Miquelon, St. Pierre</option>
	<option value="-03:00">(GMT-03:00) Greenland</option>
	<option value="-03:00">(GMT-03:00) Buenos Aires</option>
	<option value="-03:00">(GMT-03:00) Brasilia</option>
	<option value="-02:00">(GMT-02:00) Mid-Atlantic</option>
	<option value="-01:00">(GMT-01:00) Cape Verde Is.</option>
	<option value="-01:00">(GMT-01:00) Azores</option>
	<option value="+0:00">(GMT) Greenwich Mean Time : Belfast</option>
	<option value="+0:00">(GMT) Greenwich Mean Time : Dublin</option>
	<option value="+0:00">(GMT) Greenwich Mean Time : Lisbon</option>
	<option value="+0:00">(GMT) Greenwich Mean Time : London</option>
	<option value="+0:00">(GMT) Monrovia, Reykjavik</option>
	<option value="+01:00">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
	<option value="+01:00">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
	<option value="+01:00">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
	<option value="+01:00">(GMT+01:00) West Central Africa</option>
	<option value="+01:00">(GMT+01:00) Windhoek</option>
	<option value="+02:00">(GMT+02:00) Beirut</option>
	<option value="+02:00">(GMT+02:00) Cairo</option>
	<option value="+02:00">(GMT+02:00) Gaza</option>
	<option value="+02:00">(GMT+02:00) Harare, Pretoria</option>
	<option value="+02:00">(GMT+02:00) Jerusalem</option>
	<option value="+02:00">(GMT+02:00) Minsk</option>
	<option value="+02:00">(GMT+02:00) Syria</option>
	<option value="+03:00">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
	<option value="+03:00">(GMT+03:00) Nairobi</option>
	<option value="+03:30">(GMT+03:30) Tehran</option>
	<option value="+04:00">(GMT+04:00) Abu Dhabi, Muscat</option>
	<option value="+04:00">(GMT+04:00) Yerevan</option>
	<option value="+04:30">(GMT+04:30) Kabul</option>
	<option value="+05:00">(GMT+05:00) Ekaterinburg</option>
	<option value="+05:00">(GMT+05:00) Tashkent</option>
	<option value="+05:30">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
	<option value="+05:45">(GMT+05:45) Kathmandu</option>
	<option value="+06:00">(GMT+06:00) Astana, Dhaka</option>
	<option value="+06:00">(GMT+06:00) Novosibirsk</option>
	<option value="+06:30">(GMT+06:30) Yangon (Rangoon)</option>
	<option value="+07:00">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
	<option value="+07:00">(GMT+07:00) Krasnoyarsk</option>
	<option value="+08:00">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
	<option value="+08:00">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
	<option value="+08:00">(GMT+08:00) Perth</option>
	<option value="+08:45">(GMT+08:45) Eucla</option>
	<option value="+09:00">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
	<option value="+09:00">(GMT+09:00) Seoul</option>
	<option value="+09:00">(GMT+09:00) Yakutsk</option>
	<option value="+09:30">(GMT+09:30) Adelaide</option>
	<option value="+09:30">(GMT+09:30) Darwin</option>
	<option value="+10:00">(GMT+10:00) Brisbane</option>
	<option value="+10:00">(GMT+10:00) Hobart</option>
	<option value="+10:00">(GMT+10:00) Vladivostok</option>
	<option value="+10:30">(GMT+10:30) Lord Howe Island</option>
	<option value="+11:00">(GMT+11:00) Solomon Is., New Caledonia</option>
	<option value="+11:00">(GMT+11:00) Magadan</option>
	<option value="+11:30">(GMT+11:30) Norfolk Island</option>
	<option value="+12:00">(GMT+12:00) Anadyr, Kamchatka</option>
	<option value="+12:00">(GMT+12:00) Auckland, Wellington</option>
	<option value="+12:00">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
	<option value="+12:45">(GMT+12:45) Chatham Islands</option>
	<option value="+13:00">(GMT+13:00) Nuku'alofa</option>
	<option value="+14:00">(GMT+14:00) Kiritimati</option>
	</select>
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
        $('#panel_time').val(response.timeZone);
        if(response.gender == 1)
            $('#male').attr("checked",true);
        else
            $('#female').attr("checked",true);
    }
    
    
})
</script>
 