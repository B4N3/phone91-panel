errormsg = "";
$(document).ready(function(){
    usernameMsg = emailMsg = passwordMsg = 0;
	
    //function to request to check username already exists or not
    check_user_exist =function()						
    {
	    var u= $("#username").val();
            var reg=/^[a-zA-Z0-9\@\_\-\s]*[A-Za-z][a-zA-Z0-9\@\_\-\s]*$/;
	    u = jQuery.trim(u);
	    if(u.length >=5) 		
	    {
                
                //apply validation for character length
                if(u.length >= 25)
                {
                    $("#username").next().addClass("error_red").html("Username Must Less than 25 character");	
                    $("#username").removeClass("error_green");
                    $("#username").addClass("error_red");
                    return false;
                }
                
                if(!reg.test(u)){
                    $("#username").val();
                    $("#username").next().removeClass("error_green").addClass("error_red").html("Username not valid");	
                    $("#username").removeClass("error_green");
                    $("#username").addClass("error_red");
                   usernameMsg = 0;
                   	
                    return false;
                }
		
		$("#username").css({'background':'url(images/loading.gif) no-repeat','background-position':'right center'})
		$.ajax({type: "GET",url: "action_layer.php?action=check_avail",data: { username: u},
		success: function(msg)
		{ 
			$("#username").css({'background':'#fff'})
			if(msg==0) 
			{
				$("#username").val();
//				$("#username").focus();
				$("#username").next().removeClass("error_green").addClass("error_red").html("Already In use");	
				$("#username").removeClass("error_green");
				$("#username").addClass("error_red");
			}
			if(msg==1)
			{
				$("#username").next().addClass("error_green").html("You can choose this username. Available");	
				$("#username").removeClass("error_red");
				$("#username").addClass("error_green");
			}
			usernameMsg = msg;
		}});	
	    }
	    else
	    {
		$("#username").next().addClass("error_red").html("Username Must Contain 5 character");						
                $("#username").addClass("error_red");
		//$("#username").focus();
	    }

    }

    //check if email already exist
    check_email_exist =function()
     {   
	var u= $("#email").val();
	u = jQuery.trim(u);
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        
        if(u != ''){
	if(emailReg.test(u)) 		
	{
	    
	    $("#email").css({'background':'url(images/loading.gif) no-repeat','background-position':'right center'})
	    $.ajax({type: "GET",url: "action_layer.php?action=check_email_avail",data: { email: u},
	    success: function(msg)
	    { 
		    $("#email").css({'background':'#fff'})
		    if(msg==0) 
		    {
			    $("#email").val();
//			    $("#email").focus();
			    $("#email").next().removeClass("error_green").addClass("error_red").html("Already In use");	
			    $("#email").removeClass("error_green");
			    $("#email").addClass("error_red");
		    }
		    if(msg==1)
		    {
			    $("#email").next().addClass("error_green").html("You can register with this email. Available");	
			    $("#email").removeClass("error_red");
			    $("#email").addClass("error_green");
		    }
                    
		    emailMsg = msg;
	    }});	
	}
	else
	{
		emailMsg = 0;
                $("#email").next().removeClass("error_green").addClass("error_red").html("Please enter a proper email id");
                $("#email").removeClass("error_green");
                $("#email").addClass("error_red");
		//$("#email").focus();
	}
        }else
	{
            emailMsg = 0;
		$("#email").next().removeClass("error_green").addClass("error_red").html("Please enter email id");
                $("#email").removeClass("error_green");
                $("#email").addClass("error_red");
		//$("#email").focus();
	}
    }
    checkEmailExist =function()
     {   
	var u= $("#email").val();
	u = jQuery.trim(u);
	
        var returnvalue = 0;
        if(u != ''){
	
	    
//	    $("#email").css({'background':'url(images/loading.gif) no-repeat','background-position':'right center'})
	    $.ajax({type: "POST",url: "action_layer.php?action=check_email_avail",data: { email: u},async:false,
	    success: function(msg)
	    { 
                $("#email").next('label.msg').remove();
//		    $("#email").css({'background':'#fff'})
		    
		    if(msg==1)
		    {
                      returnvalue = 1; 
		    }
                    
		    
	    }});
           return returnvalue;
	
        }else
            return false;
       
    }
    
    //check password 
    check_password_strength = function()
    {
	var u= $.trim($("#password").val());
	if(u == '' || u.length<7)
	{
	    $("#password").next().removeClass("error_green").addClass("error_red").html("Password Must be 7 Character Long");	
            $("#password").removeClass("error_green");
            $("#password").addClass("error_red");
//	    $("#password").focus();
	}
	else
	{
	    $("#password").next().addClass("error_green").html("You can use this password");	
            $("#password").removeClass("error_red");
	    $("#password").addClass("error_green");

	    passwordMsg = 1;
	}
    }
    
    //check form validation
    register =function()
    {
        
       check_password_strength();
       check_user_exist();
       check_email_exist();
	if(parseInt(emailMsg) && parseInt(passwordMsg) && parseInt(usernameMsg)){
	    return true;
	}else
	    return false;
        
        
    };
    
    registerNew = function (){
            
        if(parseInt(emailMsg) && parseInt(usernameMsg)){
	    return true;
	}else
	    return false;
    }
				






 

});




function getCode()
{
	$("#code").val('+'+$("#location").val());
}

function validateEmailv2(email)
{
// a very simple email validation checking. 
// you can add more complex email checking if it helps 
    if(email.length <= 0)
	{
	  return true;
	}
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null) 
      {
	    var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
	    if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
return false;
}


function validate()
{
	var u = $("#username").val();
	u = jQuery.trim(u);
	if(u=="" || u.length <5)
	{
//		$("#username").focus();
		$("#username").addClass("error_red");
		$("#username").next().addClass("error_red").html("Please enter username");		
		return false;
	}
	else
	{
		$("#username").next().html("");	
		$("#username").removeClass("error_red");
		$("#username").addClass("error_green");
	}
	if($("#location").val()==="nocountry")
	{
//		$("#location").focus();
		$("#location").addClass("error_red");
		$("#location").next().addClass("error_red").html("Please select Country");	
		return false;
	}
	else
	{
		$("#location").next().html("");	
		$("#location").removeClass("error_red");
		$("#location").addClass("error_green");
	}
	var number=$("#mobileNumber").val();
	if(number==""||isNaN(number) || number.length<7)
	{
//		$("#mobileNumber").focus();
		$("#mobileNumber").addClass("error_red");
		$("#moberror").addClass("error_red").html("Please enter Valid Mobile Number");		
		return false;
	}
	else
	{
		$("#mobileNumber").next().html("");	
		$("#mobileNumber").removeClass("error_red");
		$("#mobileNumber").addClass("error_green");
		$("#moberror").html("");
	}
	var email=$("#email").val();;
	if(email==""||validateEmailv2(email)==false)
	{
//		$("#email").focus();	
		$("#email").addClass("error_red");
		$("#email").next().addClass("error_red").html("Please enter Valid email address");
		return false;
	}
	else
	{
		$("#email").next().html("");	
		$("#email").removeClass("error_red");
		$("#email").addClass("error_green");
	}
	var password=$("#password").val();
                if(password=="" || password.length<7)
	{
//		$("#password").focus();	
		$("#password").addClass("error_red");
		$("#password").next().addClass("error_red").html("Please enter valid password Must be 7 Character Long");
		return false;
	}
	else
	{
		$("#password").next().html("");	
		$("#password").removeClass("error_red");
		$("#password").addClass("error_green");
	}
	var repassword=$("#repassword").val();
                if(repassword=="")
	{
//		$("#repassword").focus();	
		$("#repassword").addClass("error_red");
		$("#repassword").next().addClass("error_red").html("Please enter valid confirmation password");
		return false;
	}
	else
	{
		$("#repassword").next().html("");	
		$("#repassword").removeClass("error_red");
		$("#repassword").addClass("error_green");
	}
                if(repassword != password)
                  {
//		$("#repassword").focus();	
                $("#repassword").val('');
		$("#repassword").addClass("error_red");
		$("#repassword").next().addClass("error_red").html("Password not matched.");
		return false;
	}
	else
	{
		$("#repassword").next().html("");	
		$("#repassword").removeClass("error_red");
		$("#repassword").addClass("error_green");
	}      
	
	return true;

}
 
function show_message(message,type)
{
    if ($(window).width() <= 600)
    {
        toastr.options = {
            "positionClass": "toast-top-full-width"
        }
    }
    else
    {
        toastr.options = {
            "positionClass": "toast-bottom-right"
        }
    }
    toastr[type](message) 
        
        
}	
	

// wait for the DOM to be loaded 

 //function to show response of form submit	
 function showResponse(responseText, statusText, xhr, $form) 
 { 
    
     /*     
     if(responseText.msg=='This User ID already exists')
     {
	     $("#username").removeClass("error_green");
	     $("#username").val();
	     $("#username").focus();
	     $("#username").addClass("error_red");
	     $("#username").next().addClass("error_green").html("You can choose this username. Available");
             return false;
    }
     else//if username new
     {
	     $("#username").addClass("error_green");
     }

     if(responseText.msg=='Phone number already in use by another user')
     {
	     $("#mobileNumber").removeClass("error_green");
	     $("#mobileNumber").val();
	     $("#mobileNumber").focus();
	     $("#mobileNumber").addClass("error_red");
	     $("#moberror").addClass("error_red").html("Mobile Number already in use");	
             return false;
     }
     else if(responseText.msg=='This email address already registered')
	 {
	     $("#email").removeClass("error_green");
	     $("#email").val();
	     $("#email").focus();
	     $("#email").addClass("error_red");
	     $("#emailerror").addClass("error_red").html("Email Address Already Registered");
             return false;
	 }
*/

console.log(responseText);
if(responseText.status == "success"){
	     var user=$("#username").val();
	     var password=$("#password").val();
	     var u =$("#username").val();
	    console.log("userhosjo"); 
       window.location = "userhome.php";
   
//     window.location = "index.php?submit=submit&uname="+user+"&pwd="+password+"";
}else
    show_message(responseText.msg,responseText.status);

     
} 

$(document).ready(function(){		
	$(document)
	.bind("ajaxStart", function(){
		$('#logo').attr({
			src:'images/loading.gif',
			height:32
		}).css('marginTop','7px');
		//$('#header').css({'background-image':'url(images/loading2.gif)','background-repeat':'no-repeat','background-position':'130px 14px'})
	})
	.bind("ajaxStop", function(){		
		//$('#header').css({'background-image':'none'})
		$('#logo').attr({
			src:'images/phone91-logo.jpg',
			height:46
		}).css('marginTop','0');
	});		
});
changeQs = function(str)
{
        var url = window.location.hash;
        for(var key in str)
        {
            if( url.indexOf("&"+key) == -1 && url.indexOf("?"+key) == -1 && url.indexOf("#"+key) == -1 )
            { 
                url = url+'&'+key+'='+str[key];	
            }
            else
            { 
                var regexS = "[?&#]"+key+"=([^&#]*)";
                var regex = new RegExp( regexS );		
                result = regex.exec(url);                       
                url = url.replace(result[0],'&'+key+'='+str[key]);                       
            }
        }		
        if( url == null ) 
                return ""; 
        else
                window.location.hash = url;
        if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){ //test for MSIE x.x;
        var ieversion=new Number(RegExp.$1) // capture x.x portion and store as a number
      if(ieversion<9)
        hashChanges();
      }
}