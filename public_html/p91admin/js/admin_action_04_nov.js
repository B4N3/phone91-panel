function loadpage(url,destination){
	$(destination).html("<img src='images/loading.gif' />");
	$.ajax({type: "GET",url:url,success: function(msg){$(destination).html(msg);}});
	}
	
function togleNext(obj){	
	$(obj).next(".section_content").toggle();
	}
	
$(document).ready(function(){
	$('.sidebar_menu li a').click(function(){	
	$('.sidebar_menu li a').removeClass('active');
	$(this).addClass('active');
	})
	
	$(".togle").click(function(){
			
		})
			
	$(".load_it").colorbox({width:"60%", height:"80%", iframe:true});
	$(".load_route").click(function()
	{
		$("#loading").show();
		$.ajax({type: "GET",url: "/admin/inc/smpp_request.php",success: function(msg){ $("#loading").hide(); $("#ajax_content").html(msg);}});		
	});
	
	$(".load_current_route").click(function()
	{
	$("#loading").show();
	$.ajax({type: "GET",url: "/admin/inc/current_routelist.php",success: function(msg){ $("#loading").hide(); $("#ajax_content").html(msg);}});		
	});
	$(".load_test_panel").click(function()
	{
	$("#loading").show();
	$.ajax({type: "GET",url: "/admin/inc/smpp_send_test_sms.php",success: function(msg){ $("#loading").hide(); $("#ajax_content").html(msg);}});		
	});
	
	$(".restart_test_server").click(function()
	{
		if(confirm("Are you sure to restart test server"))
		{
			$("#loading").show();
			$.ajax({type: "GET",url: "/admin/inc/action.php?action=restart",success: function(msg){ $("#loading").hide(); alert(msg);}});		
		}
	});
	
	$(".restart_main_server").click(function()
	{
		if(confirm("Are you sure to restart Main server Make sure that sms are not sending from this route at this time"))
		{
			$("#loading").show();
			$.ajax({type: "GET",url: "/admin/inc/action.php?action=restart_main",success: function(msg){ $("#loading").hide(); alert(msg);}});		
		}
	});
	
	
$(".user_sms").click(function()
{
	$("#loading").show();
	$.ajax({type: "GET",url: "/admin/inc/user_sms.php",success: function(msg){ $("#loading").hide(); $("#ajax_content").html(msg);}});		
});		
});
function send_test_sms() 
{
	$("#loading").show();
	var u,p,f,t,msg,msk,dlrurl;
	u=$("#username").val();
	p=$("#password").val();
	f=$("#from").val();
	t=$("#to").val();
	msg=$("#text").val();
	msk=$("#dlr-mask").val();
	dlrurl=$("#dlr-url").val();
	 $.ajax({type: "GET",url: "/admin/inc/action.php?action=send_test_sms",data: { u: u, password:p, from:f, to:t, text:msg, dlrmask:msk,dlrurl:dlrurl}, success: function(msg){ $("#loading").hide(); $("#loading").html(msg);}});
	  return false;	
}

function confirm_smpp_account() 
{
	$("#loading").show();
	var u,p,f,t,msg,msk,dlrurl;
	r=$("#route").val();
	ip=$("#smpp_ip").val();
	port=$("#port").val();
	u=$("#username").val();
	p=$("#password").val();
	 $.ajax({type: "GET",url: "/admin/inc/action.php?action=confirm_smpp_account",data: { route: r, smpp_ip:ip, port:port, smpp_username:u, smpp_password:p}, success: function(msg){ $("#loading").hide(); $("#loading").html(msg);}});
	  return false;	
}

function add_test_smpp() 
{
	$("#loading").show();
	var u,p,f,t,msg,msk,dlrurl;
	r=$("#route").val();
	ip=$("#smpp_ip").val();
	port=$("#port").val();
	u=$("#smpp_username").val();
	p=$("#smpp_password").val();
	 $.ajax({type: "POST",url: "/admin/inc/action.php?action=add_test_smpp",data: { route: r, smpp_ip:ip, port:port, smpp_username:u, smpp_password:p}, success: function(msg){ $("#loading").hide(); $("#loading").html(msg);
	 }});
	  return false;	
}
all_user_sms =function()
{
	a= $("#msg").attr("value");
	c = jQuery.trim(a);
	if(c.length<1)
	{
		alert("Please provide proper sms.");
	}
	else
	{
		//alert(a);
		var answer = confirm ("Are you want to send this sms:-> '"+a+"'")
		if (answer)
		{
			$("#loading").show();	
			$.ajax({type: "GET",url:"../action_layer.php?action=54",data: {msg: a}, success: function(msg){ $("#loading").hide(); $("#show_availability").html(msg);}});
		}
		else
		alert ("SMS sending stop.")
	}
}


edit_smpp = function(a) 
{
	$("#loading").show();	
	$.ajax({type: "GET",url: "/admin/inc/adminview.php",data: { id: a }, success: function(msg){ $("#loading").hide(); $("#ajax_content").html(msg);}});		
}	
show_smpp = function(a) 
{
	$("#loading").show();	
	$.ajax({type: "GET",url: "/admin/inc/active_smpp.php",data: { id: a }, success: function(msg){ $("#loading").hide(); $("#ajax_content").html(msg);}});		
}
delete_smpp = function(a) 
{
	if(confirm("are you sure to delete"))
	{
		$("#loading").show();	
		$.ajax({type: "POST",url: "/msg91/admin/inc/action.php?action=delete_smpp",data: { smsc_id: a }, success: function(msg){ $("#loading").hide(); $("#ajax_content").html(msg);}});		
	}
}

load_ajax_data_page = function(page,data) 
{
	$("#loading").show();	
	$.ajax({type: "GET",url: page,data: { utype: data }, success: function(msg){ $("#loading").hide(); $("#ajax_content").html(msg);}});		
}

load_ajax_page = function(page) 
{
	$("#loading").show();	
	$.ajax({type: "GET",url: page, success: function(msg){ $("#loading").hide(); $("#ajax_content").html(msg);}});		
}

function check_availability1(uname)
{
rand=parseInt(Math.random()*99999999);
$.ajax({type: "GET",url:"../action_layer.php?action=14",data: {rand: rand,uname: uname}, success: function(msg){ $("#loading").hide(); $("#show_availability").html(msg);}});
}

function load_menu(type){if(document.getElementById(type).style.display=='inline'){try { document.getElementById(type).style.display='none'; document.getElementById(type+"_span").innerHTML='Show'; } catch (e) {}}else{try { document.getElementById(type).style.display='inline'; document.getElementById(type+"_span").innerHTML='Hide'; } catch (e) {}}}

function show_message(error_message,type)
	{
		var t=0;
		//$("#notification").hide();			
		$("#notification").html('<div class="'+type+'"><h2>'+error_message+'</h2></div>');
		$('#loading').hide();	
			
		$("#notification").slideDown();	         
			  
		$(function() {
			t = setTimeout(function() {
			$("#notification").slideUp();
		  	}, 4000);
		 });
		 $("#notification").click(function () {
		   // Slide it up right away
		   $("#notification").slideUp();
		   // Stop the timeout that was running
			clearTimeout(t);
		});
	}
function Check_Uncheck(source, targets) {
		$source = $(source);
		$targets = $('input[name="'+ targets + '"]');
		if ($source.is(':checked')) {
			$targets.attr('checked',true);
		} else {
			$targets.removeAttr('checked');
		}
		return true;
}

$(".tab").click(function()
{
	alert('a');
	$(".wall").hide();
	$(this).show();
});