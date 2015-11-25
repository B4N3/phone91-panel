<?php


?>
<a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<!--Reseller Add Route-->
<h3>Add Route</h3>
<form id="addRoute" name="addRoute" action="javascript:;">
	<div id="addClntForm" class="formElemt">                    
		<div class="fields">
			<label>Name</label>
			<input type="text" name="routeName" onblur="checkRouteExists()" id="routeName"/>
                        <div class="msg"></div>
		</div>
		
<!--		<div class="fields">
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
		</div>-->
		
<!--		 <div class="fields">   
                        <label>User Name</label>
			   <input type="text" id="routeUserName" name="routeUserName" /> 
		</div>
		 <div class="fields">   
                        <label>Password </label>
			   <input type="password" id="routePassword" name="routePassword" /> 
		</div>-->
		
		<div class="fields">
			   <label> IPs </label>
			<div class="addIpsWrp">
			   <div class="addsipInput">
					 <input type="text" placeholder="192.168.1.1" name="routeIps" id="routeIps">
<!--					 <span title="Delete" class="ic-24 delete cp" onclick="delIpRow(this)"></span> -->
			   </div>			   
<!--			   <p onclick="newIpRow(this)" class="arBorder secondry fl cp sucsses cp" title="Add">
						<span class="ic-16 add "></span>
              	   </p>-->
			</div>				   
		</div>
		
		<div class="fields">
			<label>Call Limit</label>
			<input type="text" name="routeCallLimit" id="routeCallLimit" />
		</div>
		<div class="fields">
			<label>Port</label>
			<input type="text" name="routePort" id="routePort" />
		</div>
	   
		<div class="fields">
			<label>Prefix</label>
			<input type="text" name="routePrefix" id="routePrefix" />
		</div>
		<div class="fields">
			<label>Allow Codecs</label>
			<select id="codex" onchange="addCodex(this)">
			    <option value="0">select allowed codex</option>
			    <option value="ulaw">ulaw</option>
			    <option value="alaw">alaw</option>
			    <option value="gsm">gsm</option>
			    <option value="g729">g729</option>
			    <option value="G.722">G.722</option>
			    <option value="G.723.1">G.723.1</option>
			    <option value="G.726">G.726</option>
			    <option value="G.729">G.729</option>
			     <option value="iLBC">iLBC</option>
			    <option value="LPC10">LPC10</option>
			    <option value="Speex">Speex</option>
			</select>
			<div id="selCodex" style="width: 300px;height: 96px;position: relative;top: -31px;left: 239px;border-width: 4px;background-color: #F2F2F2;border-color: #E15F26;"></div>
		</div>


                <div class="fields">
			<label>Tariff</label> 
                        <select id="tariff" class="selPlan" name="tariff">
<!--				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>-->
			</select>
		</div>
<!--                <div class="fields">
			<label>balance</label>
			<input type="text" name="balance" id="balance"/>
                        <div class="msg"></div>
		</div>-->
                
	   
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
    selectPlan();   
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
var globalCodex = [];

function addCodex(ths)
{
	
    var li='';
    
   if($.inArray($(ths).val(),globalCodex) == -1)
   {
       globalCodex[globalCodex.length] = $(ths).val();
	li+='<table cellpadding="0" class="cf hX" style="display:inline-block;margin:2px ">\
						     <tbody>\
							     <tr class="hR">\
								     <td class="hU hM" style="background-color: #ddd; color: #666;float:left;padding: 2px 2px 2px 4px;">\
									     <div class="hN" name="^i" title="codex name" name="codex" role="button" tabindex="0">\
										     '+$(ths).val()+'\
									     </div>\
								     </td>\
								     <td class="hV hM" style="background-color: #ddd; color: #666;float:left;padding: 2px 2px 2px 4px;cursor:pointer">\
									     <span class="hO" name="^i" onclick="removeCodex(this,\''+$(ths).val()+'\')" title="" role="button" tabindex="0">x</span><input type="hidden" name="codexNames[]" value="'+$(ths).val()+'">\
								     </td>\
							     </tr>\
						     </tbody>\
					     </table>';

	$('#selCodex').append(li);
    }

}





function removeCodex(ths,rCodex)
{
    
    
    console.log($(ths).parents('table:first'));
    console.log(rCodex);
    globalCodex.splice( globalCodex.indexOf(rCodex), 1 );
    $(ths).parents('table:first').remove();
    console.log(globalCodex);
    
}
//$.validator.addMethod('IP4Checker', function(value) {
//            var ip = "^(?:(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)\.){3}" +
//                "(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)$";
//                return value.match(ip);
//            }, 'Invalid IP address');
            $().ready(function() {
     
      jQuery.validator.addMethod('selectcheck', function (value) {
                return (value != 'Select');
            }, "Please Select proper value!"); 
     

      jQuery.validator.addMethod('checkPrefix', function (value) {
                var prefixRegx = /[^0-9\*\#\+]/;

                if(prefixRegx.test(value))
                	return false;
                else
                	return true;


            }, "Please enter a valid value!"); 

            // validate the comment form when it is submitted	
            $("#addRoute").validate({
            		onfocusout: function(element) { $(element).valid(); },
                    rules: {
                            routeName :{
					required: true,
					minlength: 3,
                                	maxlength:40
                                     },
			    routeCallLimit:{
					required:true,
					number:true,
					minlength:1,
					maxlength:3
						 
				     },
		            routePort:{
					required:false,
					number:true,
					
			    },

								    tariff:{
										selectcheck:true
								    },
						            routeIps :{
										required: true//,
									//IP4Checker: true
					                 },
                            		routePrefix :{
										required: false,
										checkPrefix:true,
		                                minlength:1,
		                                maxlength:8
			                        },
                        
                    		}
            });
            
            
    

     
    
    
          })

// function call before submit ajex for validation
function showRequest(formData, jqForm, options){ 
//        $.validator.setDefaults({
//            submitHandler: function() { console.log("submitted!"); }
//         });
         
       
        if($("#addRoute").valid())
                return true; 
        else
                return false;
  }
  
  
   $(document).ready(function() { 
		var options = { 
			dataType:  'json',
                        type:'POST',
			url:'/controller/routeController.php?action=addRoute',
			beforeSubmit:  showRequest, // pre-submit callback 
			success:       showResponse  // post-submit callback 
		}; 
		$('#addRoute').ajaxForm(options); 
	});
        
        function showResponse(responseText, statusText, xhr, $form)  { 
		console.log(responseText);
                show_message(responseText.msg,responseText.status);
		
		var str = '';
		
		if(responseText.status == 'success')
		{
		    $('#addRoute')[0].reset();
		    var routeName = $('#routeName').val();
		    var planName = $('#tariff :selected').text();
		     str += '<li onclick="loadRouteDetails('+responseText.lastId+',event);">\
                                    <div class="linkCont">\
                                            <div></div><span class="ic-16 link"></span>\
                                        <div class="showLinksCont dn">\
                                                <span class="blackThmCrl">'+routeName+'</span>\
                                            <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>\
                                        </div>\
                                    </div>\
                                    <div class="usrDescr">\
                                            <div class="">\
                                                <p class="uname ellp">\
                                                            '+routeName+'</p><h3 class="yelloThmCrl ellp"></h3>\
                                                    <span></span>\
                                                <p class="acMan"></p>\
                                                <p class="tInfo">\
                                                    Tariff\
                                                    <b>'+planName+'</b>\
                                                <span class="sep">|</span>\
                                                <span class="'+responseText.lastId+"changeRouteBal"+'">0 </span>'+responseText.currency+'\
                                                </p>\
                                                </p>\
                                            </div>\
                                </div>\
                            </li>';
							
			$('#mngClntList').prepend(str);				
		}
//                if(responseText.status == "success"){
//                     $("#addBulkClient")[0].reset();
//                var str = createBulkDesign(responseText.batchDetail);
//                $("#bulkclientList").html('');
//                $("#bulkclientList").html(str);  
//                     
//                }
//		$("#loading").hide();
		//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.'); 
	} 


var checkRouteExists = function ()						
    {
	    var u= $("#routeName").val();
            var reg=/^[a-zA-Z0-9\@\_\-\s]*[A-Za-z][a-zA-Z0-9\@\_\-\s]*$/;
	    u = jQuery.trim(u);
	    if(u.length >=5) 		
	    {
                
                //apply validation for character length
                if(u.length >= 25)
                {
                    $("#routeName").next().addClass("error_red").html("route Must Less than 25 character");	
                    $("#routeName").removeClass("error_green");
                    $("#routeName").addClass("error_red");
                    return false;
                }
                
                if(!reg.test(u)){
                    $("#routeName").val();
                    $("#routeName").next().removeClass("error_green").addClass("error_red").html("route name not valid");	
                    $("#routeName").removeClass("error_green");
                    $("#routeName").addClass("error_red");
                   usernameMsg = 0;
                   	
                    return false;
                }
		
		$("#routeName").css({'background':'url(images/loading.gif) no-repeat','background-position':'right center'})
		$.ajax({type: "GET",url: "/controller/routeController.php?action=checkRouteExists",data: { routeName: u},
		success: function(msg)
		{ 
			$("#routeName").css({'background':'#fff'})
			if(msg==0) 
			{
				$("#routeName").val();
//				$("#username").focus();
				$("#routeName").next().removeClass("error_green").addClass("error_red").html("Already In use");	
				$("#routeName").removeClass("error_green");
				$("#routeName").addClass("error_red");
			}
			if(msg==1)
			{
				$("#routeName").next().addClass("error_green").html("You can choose this route. Available");	
				$("#routeName").removeClass("error_red");
				$("#routeName").addClass("error_green");
			}
			usernameMsg = msg;
		}});	
	    }
	    else
	    {
		$("#routeName").next().addClass("error_red").html("route name Must Contain 5 character");						
                $("#routeName").addClass("error_red");
		//$("#username").focus();
	    }

    }
</script>