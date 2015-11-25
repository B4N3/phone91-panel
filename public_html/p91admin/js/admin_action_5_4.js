function delete_dummy_route(id)
{
	if(confirm("Are You Sure Want To Delete This Route")==true)
	{
		$.ajax({
		   type: "POST",
		   url: "../action_layer.php?action=105",
		   data: "id="+id,
		   success: function(msg){
		   	if(msg=='Route Deleted Successfully')
			   {
		        show_message(msg,"success");
		        $('#tr_'+id).hide();
		       }
		      else
		      {
		   	    show_message(msg,"error");
		     }
		   }
		 });
	}
}
function route_status(rid)
	{
	var status='';//$('#status'+cid).attr('checked');
	if($('#status'+rid).attr('checked'))
		status=1;
	else
		status=0;
		
			$.ajax({
		   type: "POST",
		   url: "../action_layer.php?action=104",
		   data: "rid="+rid+"&rstatus="+status,
		   success: function(msg){
			   if(msg=='Route Status Updated Successfully')
			   {				   
				if(status==1)
				$('#statusLabel'+rid).removeClass('red').addClass('green').text('Enabled');
				else
				$('#statusLabel'+rid).removeClass('green').addClass('red').text('Disabled');
				// load_next('<?php echo $page ;?>');
			   	show_message(msg,"success");
			   }
			   else
			   show_message(msg,"error");  
		   }
		 });
	}
function get_cosecutive_nos(id,date,type)
{
	$('#loading').show().html("<img class='loading' src='images/loading.gif' height='32' width='32' />");
    $.ajax({
 	type: "GET",
 	url: "../action_layer.php?action=102&msg_type="+type+"&request_id="+id+"&request_date="+date,
 	success: function(msg){
			$('#loading').hide();
					
			$("#new_div"+id).html(msg);
			}
								
		});

}
function loadpage(url,destination){
	wh=$(window).height()-115;
	$('#ajax_content').css('min-height',wh);
	$('#loading').show().html("<img class='loading' src='images/loading.gif' height='32' width='32' />");
	//$.ajaxSetup({ cache: false });
	$.ajax({type: "GET",url:url,cache:false,success: function(msg){
			$('#loading').hide()			
			if(destination == '#notification')							
			{
					show_message(msg,'');
			}
			else
				$(destination).html(msg);						
			h=$('#ajax_content').height();
			if(h < wh){
				h=wh;
				$('#ajax_content').height(h);
			}
			$('.toggleContent').height(h);								
			$('.tip').tipTip();						
		}});
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
			$.ajax({type: "GET",url:"../action_layer.php?action=54",data: {msg: a}, success: function(msg){ 
				show_message(msg,"success");
				$("#loading").hide(); 
				$("#ajax_content").html(msg);
				$("#show_availability").html(msg);
				
				}});
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
		$("#notification div").append('<div style="position:absolute; top:10px; right:10px;">Click to Quick Hide</div>');
		$("#notification").show();	         			  
		$(function() {
			t = setTimeout(function() {
			$("#notification").fadeOut();
		  	}, 4000);
		 });
		 $("#notification").click(function () {
		   // Slide it up right away
		   $("#notification").fadeOut();
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

/*
 * TipTip
 * Copyright 2010 Drew Wilson
 * www.drewwilson.com 
 */
(function($){$.fn.tipTip=function(options){var defaults={activation:"hover",keepAlive:false,maxWidth:"200px",edgeOffset:3,defaultPosition:"bottom",delay:0,fadeIn:200,fadeOut:200,attribute:"title",content:false,enter:function(){},exit:function(){}};var opts=$.extend(defaults,options);if($("#tiptip_holder").length<=0){var tiptip_holder=$('<div id="tiptip_holder" style="max-width:'+opts.maxWidth+';"></div>');var tiptip_content=$('<div id="tiptip_content"></div>');var tiptip_arrow=$('<div id="tiptip_arrow"></div>');$("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow.html('<div id="tiptip_arrow_inner"></div>')))}else{var tiptip_holder=$("#tiptip_holder");var tiptip_content=$("#tiptip_content");var tiptip_arrow=$("#tiptip_arrow")}return this.each(function(){var org_elem=$(this);if(opts.content){var org_title=opts.content}else{var org_title=org_elem.attr(opts.attribute)}if(org_title!=""){if(!opts.content){org_elem.removeAttr(opts.attribute)}var timeout=false;if(opts.activation=="hover"){org_elem.hover(function(){active_tiptip()},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}else if(opts.activation=="focus"){org_elem.focus(function(){active_tiptip()}).blur(function(){deactive_tiptip()})}else if(opts.activation=="click"){org_elem.click(function(){active_tiptip();return false}).hover(function(){},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}function active_tiptip(){opts.enter.call(this);tiptip_content.html(org_title);tiptip_holder.hide().removeAttr("class").css("margin","0");tiptip_arrow.removeAttr("style");var top=parseInt(org_elem.offset()['top']);var left=parseInt(org_elem.offset()['left']);var org_width=parseInt(org_elem.outerWidth());var org_height=parseInt(org_elem.outerHeight());var tip_w=tiptip_holder.outerWidth();var tip_h=tiptip_holder.outerHeight();var w_compare=Math.round((org_width-tip_w)/2);var h_compare=Math.round((org_height-tip_h)/2);var marg_left=Math.round(left+w_compare);var marg_top=Math.round(top+org_height+opts.edgeOffset);var t_class="";var arrow_top="";var arrow_left=Math.round(tip_w-12)/2;if(opts.defaultPosition=="bottom"){t_class="_bottom"}else if(opts.defaultPosition=="top"){t_class="_top"}else if(opts.defaultPosition=="left"){t_class="_left"}else if(opts.defaultPosition=="right"){t_class="_right"}var right_compare=(w_compare+left)<parseInt($(window).scrollLeft());var left_compare=(tip_w+left)>parseInt($(window).width());if((right_compare&&w_compare<0)||(t_class=="_right"&&!left_compare)||(t_class=="_left"&&left<(tip_w+opts.edgeOffset+5))){t_class="_right";arrow_top=Math.round(tip_h-13)/2;arrow_left=-12;marg_left=Math.round(left+org_width+opts.edgeOffset);marg_top=Math.round(top+h_compare)}else if((left_compare&&w_compare<0)||(t_class=="_left"&&!right_compare)){t_class="_left";arrow_top=Math.round(tip_h-13)/2;arrow_left=Math.round(tip_w);marg_left=Math.round(left-(tip_w+opts.edgeOffset+5));marg_top=Math.round(top+h_compare)}var top_compare=(top+org_height+opts.edgeOffset+tip_h+8)>parseInt($(window).height()+$(window).scrollTop());var bottom_compare=((top+org_height)-(opts.edgeOffset+tip_h+8))<0;if(top_compare||(t_class=="_bottom"&&top_compare)||(t_class=="_top"&&!bottom_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_top"}else{t_class=t_class+"_top"}arrow_top=tip_h;marg_top=Math.round(top-(tip_h+5+opts.edgeOffset))}else if(bottom_compare|(t_class=="_top"&&bottom_compare)||(t_class=="_bottom"&&!top_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_bottom"}else{t_class=t_class+"_bottom"}arrow_top=-12;marg_top=Math.round(top+org_height+opts.edgeOffset)}if(t_class=="_right_top"||t_class=="_left_top"){marg_top=marg_top+5}else if(t_class=="_right_bottom"||t_class=="_left_bottom"){marg_top=marg_top-5}if(t_class=="_left_top"||t_class=="_left_bottom"){marg_left=marg_left+5}tiptip_arrow.css({"margin-left":arrow_left+"px","margin-top":arrow_top+"px"});tiptip_holder.css({"margin-left":marg_left+"px","margin-top":marg_top+"px"}).attr("class","tip"+t_class);if(timeout){clearTimeout(timeout)}timeout=setTimeout(function(){tiptip_holder.stop(true,true).fadeIn(opts.fadeIn)},opts.delay)}function deactive_tiptip(){opts.exit.call(this);if(timeout){clearTimeout(timeout)}tiptip_holder.fadeOut(opts.fadeOut)}}})}})(jQuery);


//  Manage Admin JS   By Rahul   6 Jan

function export_client()
   {
	id=new Array()
		a=0;
		$("input.check_client:checked").each(function(){
		  id[a]=$(this).val();
				a++;
		});
		if(id.length<1)
				{
					show_message("please select atleast one client to export","warning");
				    return false;
				}
	
		      window.location="../action_layer.php?action=63&check_id="+id;
	   		 
}
function add_client()
{
	$('#loading').show();
	$("#ajax_content").load("manage_admin.php?add=yes",function() {$('#loading').hide();});
}
var show=1;	
		
function add_client_submit()
	{
			var fname=$("#fname").val();
			var lname=$("#lname").val();
			var user_name=$("#adduser_name").val();
			var mob_no=$("#mob_no").val();
			var expiry=$("#expiry").val();
			var balance=$("#balance").val();
			var utype=$("#utype").val();
			var email=$("#email").val();
			var tariff_plan=$("#tariff_plan").val();
			
			var update_detail=1;
			
			if(fname=='' || $.trim(fname).length<1)
			{
				show_message("Please Enter First Name","error");
				$("#fname").focus();
			    return false;}
			
			if(lname==''|| $.trim(lname).length<1)
			{show_message("Please Enter Last Name","error");
			$("#lname").focus();return false;}
			
			if(user_name=='' || $.trim(user_name).length<5)
			{show_message("Please Enter User Name(Minimum length 5 Character) ","error");
			$("#adduser_name").focus();return false;}
			
			if(mob_no==''|| $.trim(mob_no).length<9 || isNaN(mob_no))
			{
				if(!isNaN(mob_no))
				{
					show_message("Please Enter Proper Mobile Number","error");
				}
				else
				{
					show_message("Please Enter Valid Mobile Number","error");
					$("#mob_no").val('');
				}
			$("#mob_no").focus();return false;
			}
			
			if(expiry=='' || $.trim(expiry).length<1 )
			{show_message("Please Select Expiry Date","error");
			$("#expiry").focus();return false;}
			if(utype=='')
			{show_message("Please Select User Type","error");
			$("#utype").focus();return false;}
			if(balance=='' || $.trim(balance).length<1)
			{show_message("Please Enter Balance","error");
			$("#balance").focus();return false;}
			if(email=='' || $.trim(email).length<1)
			{show_message("Please Enter Email","error");
			$("#email").focus();return false;}
			else
			{
				$('#loading').show();
				$.ajax({
		   		type: "POST",
		   		url: "../action_layer.php?action=15",
		   		data: "update_detail="+update_detail+"&fname="+fname+"&lname="+lname+"&user_name="+user_name+"&mob_no="+mob_no+"&expiry="+expiry+"&balance="+balance+"&utype="+utype+"&user_email="+email+"&tariff_plan="+tariff_plan,
		   		success: function(msg){
					//alert(msg);
					if(msg=='User Sucessfully Registered')
					{
					 $("#ajax_content").load("manage_admin.php");
				     show_message(msg,"success");
					  }
				     else {
				     show_message(msg,"error");
					   }
					  $('#loading').hide(); 
								
					}
		 		});
			}
	}
			
function edit_route(id)
{
	var route=$("#gateway"+id).val();
	$.ajax({
		type: "POST",
		url: "../action_layer.php?action=71",
		data: "id="+id+"&route="+route,
		success: function(msg){
		show_message("Route Updated Successfully","success");
		}
	});
}
function edit_dnd(id)
{
	var dnd=$("#dnd"+id).val();
	$.ajax({
		type: "POST",
		url: "../action_layer.php?action=72",
		data: "id="+id+"&dnd="+dnd,
		success: function(msg){
		show_message("DND Updated","success");
		}
	});
}
function changeClass(id,cls)
{
document.getElementById(id).setAttribute("class", cls);
}
function edit_sentper(id,val)
{
	var per=$("#sentper_"+id).val();
	$.ajax({
		type: "POST",
		url: "../action_layer.php?action=76",
		data: "id="+id+"&per="+per,
		success: function(msg){
		show_message("Sent Percentage Updated","success");
		}
	});
}
function edit_dialplan(id,value)
{
	var newdial=$("#dial_"+id).val();
	$.ajax({
		type: "POST",
		url: "../action_layer.php?action=88",
		data: "id="+id+"&newdial="+newdial+"&olddial="+value,
		success: function(msg){
		 show_message("dial plan updated","success");
		}
	});
}
function edit_ratio(id,val)
{
	var ratio=$("#ratio_"+id).val();
	$.ajax({
		type: "POST",
		url: "../action_layer.php?action=85",
		data: "id="+id+"&ratio="+ratio,
		success: function(msg){
		show_message("Ratio Updated","success");
		}
	});
}
function changeDemoUser(id)
{
	var status='';//$('#status'+cid).attr('checked');
	if($('#chkDemo'+id).attr('checked'))
	{
		status=1;
		var conf="Do You Really Want To Make Demo User To This Client";
	}
	else
	{
		status=2;
		var conf="Do You Really Want To Delete This Client As Demo";
	}
	
	if(confirm(conf)==true)
	{
		$.ajax({
		   type: "POST",
		   url: "../action_layer.php?action=108",
		   data: "id="+id+"&status="+status,
		   success: function(msg){
			   if(msg=='Demo User Status Updated Successfully')
			   {				   
				show_message(msg,"success");
			   }
			   else
			   show_message(msg,"error");  
		   }
		 });
	}
}
function client_status(cid)
	{
	var status='';//$('#status'+cid).attr('checked');
	if($('#status'+cid).attr('checked'))
		status=1;
	else
		status=2;
		
	if(status==1)
	$('#statusLabel'+cid).removeClass('red').addClass('green').text('Enabled');
	else
	$('#statusLabel'+cid).removeClass('green').addClass('red').text('Disabled');
	
			$.ajax({
		   type: "POST",
		   url: "../action_layer.php?action=74",
		   data: "cid="+cid+"&cstatus="+status,
		   success: function(msg){
			   if(msg='Client Status Updated Successfully')
			   {				   
				//if(status==1)
				//$('#statusLabel'+cid).removeClass('red').addClass('green').text('Enabled');
				//else
				//$('#statusLabel'+cid).removeClass('green').addClass('red').text('Disabled');
				// load_next('<?php echo $page ;?>');
			   	show_message(msg,"success");
			   }
			   else
			   show_message(msg,"error");  
		   }
		 });
	}
function change_password(form_id)
{
	$('#loading').show();
	var new_pass=$("#new_pass_"+form_id).val();
	if(new_pass=='' || $.trim(new_pass).length<5 )
	{
		show_message("Please Enter Password(Minimum length 5 Character) ","error");
		$("#new_pass_"+form_id).focus();
		$('#loading').hide();
		return false;
	}
	else
	{
	$.ajax({
		   type: "POST",
		   url: "../action_layer.php?action=41",
		   data: "id="+form_id+"&update_detail=4&new_pass="+new_pass,
		   success: function(msg){
			   if(msg=='Password Reset Successfully')
			   {
				   //load_next('<?php //echo $page ;?>');
					$("#ajaxcontent"+form_id).hide();
					$("#ajaxcontent"+form_id).html('');		
					show_message(msg,"success");
			   }
				else
				show_message(msg,"error");
				$('#loading').hide();
		   }
		 });
	}
	return false;
}
function edit_client(id,val,ths)
	{if($(ths).hasClass('active'))
		{
			$('.ajxcontent').hide();	
			$(ths).removeClass('active');			
		}
		else
		{
			$('.toggle_tab').removeClass('active')
			$('.ajxcontent').hide();
			$(ths).addClass('active')	;		
			$("#ajaxcontent"+id).show();
			$('#loading').show();
			$("#ajaxcontent"+id).load(val,function() {$('#loading').hide();});			
		}	
	}

	

function edit_client_submit(id)
{
			var fname=$("#fname").val();
			var lname=$("#lname").val();
			var user_name=$("#user_name2").val();
			var mob_no=$("#mob_no").val();	
			var sender=$("#sender").val();
			var utype2=$("#utype2").val();
			if(fname=='' || $.trim(fname).length<1)
			{
				show_message("Please Enter First Name","error");
				$("#fname").focus();
			return false;}
			
			if(lname==''|| $.trim(lname).length<1)
			{show_message("Please Enter Last Name","error");
			$("#lname").focus();return false;}
			
			if(user_name=='' || $.trim(user_name).length<5)
			{show_message("Please Enter User Name(Minimum length 5 Character) ","error");
			$("#adduser_name").focus();return false;}
			
		
			if(mob_no==''|| $.trim(mob_no).length<9 || isNaN(mob_no))
			{
				if(!isNaN(mob_no))
				{
					show_message("Please Enter Proper Mobile Number","error");
				}
				else
				{
					show_message("Please Enter Valid Mobile Number","error");
					$("#mob_no").val('');
				}
			$("#mob_no").focus();return false;
			}
			
			else
			{
			$('#loading').show(); 
			$.ajax({
		   type: "POST",
		   url: "../action_layer.php?action=41",
		   data: "id="+id+"&update_detail=1&fname="+fname+"&lname="+lname+"&user_name="+user_name+"&mob_no="+mob_no+"&sender="+sender+"&utype2="+utype2,
		   success: function(msg){
			 
			   if(msg=='Update Successful')
			   {
				    //load_next('manage_admin.php');
				    $("#ajaxcontent"+id).hide();
				    show_message(msg,"success");
			   }
				else {
				     show_message(msg,"error");
				    }
					$('#loading').hide();
				 }
		 });
	}
}
function delete_client(id)
{
	if(confirm("Are You Sure Want To Delete This Client")==true)
	{
		$.ajax({
		   type: "POST",
		   url: "../action_layer.php?action=75",
		   data: "id="+id,
		   success: function(msg){
		   show_message("Client Deleted Successfully","success");
		   }
		 });
	}
}
function load_details(id,page,ths)
{
	if($(ths).hasClass('active'))
		{
			$('.ajxcontent').hide();	
			$(ths).removeClass('active');			
		}
		else
		{
			$('.ajxcontent').hide();
			$('.actions_menu a').removeClass('active');
			$(ths).addClass('active')	;		
			$("#ajaxcontent"+id).show();
			$('#loading').show();
			$("#ajaxcontent"+id).load(page,function() {$('#loading').hide();});			
		}	
	}
function load_next(page)
	{$("#loading").show();
	//alert(page);
		$("#ajax_content").load(page,function() {$('#loading').hide();});

}



	
function sender_option(id)
	{
		var status=$("#sender"+id).val();
		$.ajax({
		   type: "POST",
		   url: "../action_layer.php?action=80",
		   data: "id="+id+"&status="+status,
		   success: function(msg){
			   if(msg='Random Sender Id Updated Successfully')
			   {
				load_next('manage_admin.php');
			   show_message(msg,"success");
			   }
			   else
			   show_message(msg,"error");  
		   }
		 });
	}
function edit_funds_submit(form_id)
{
   var type;
   $('#button_text').attr('disabled',true);
   if(document.getElementById("type_"+form_id).checked==true)
   {
     type=1;
   }
   else
   {
    type=2;
   }
   var expiry=$("#expiry_"+form_id).val();
   var sms=$("#sms_"+form_id).val();
   var amount=$("#amount_"+form_id).val();
   var plan=$("#plan_"+form_id).val();
   var description=$("#description_"+form_id).val();
   var transaction_type=$("#trans_sms_"+form_id).val();
      $.ajax({
              type: "POST",
              url: "../action_layer.php?action=41",
              data: "id="+form_id+"&update_detail=3&expiry="+expiry+"&sms="+sms+"&amount="+amount+"&type="+type+"&description="+description+"&plan="+plan+"&transaction_type="+transaction_type,
             success: function(msg){
              if(msg=='Update Successful')
				{
				// load_next('manage_admin.php');
				$("#ajaxcontent"+form_id).hide();
                 show_message(msg,"success");
				}
			else
			{
			   show_message(msg,"error");
			   $('#button_text').attr('disabled',false);
                 }
                }
                 
       });
                        
}
function edit_voice_bal(id)
{
	var type;
	 $('#button_voice').attr('disabled',true);
                       if(document.getElementById("voice_"+id).checked==true)
                       {
                               type=1;
                       }
                       else
                       {
                               type=2;
                       }
	var vsms=$("#vsms_"+id).val();
	var vbal=$("#vbal_"+id).val();
	var description=$("#description").val();
	var transaction_type=$("#trans_voice_"+id).val();
	if(vsms=="")
	{
	show_message("Please enter voice SMS","error");
		$('#button_voice').attr('disabled',false);
	}
	else
	$.ajax({
             type: "POST",
             url: "../action_layer.php?action=41",
data: "id="+id+"&update_detail=5&vsms="+vsms+"&vbal="+vbal+"&type="+type+"&transaction_type="+transaction_type+"&description="+description,
	         success: function(msg){
			 if(msg=='Update Successful')
			  {
				//load_next('manage_admin.php');
				$("#ajaxcontent"+id).hide();
                show_message(msg,"success");
			  }
				else{
			show_message(msg,"error");
			$('#button_voice').attr('disabled',false);
           }
              }
        });
}
function hideMenu(ths){
	if($(ths).attr('alt')=='hidemenu')
	{
		$('#sidebar').hide();
		$('#expandCollapse').hide();
		$(ths).attr('alt','showmenu');
		$(ths).attr('title','Click to collapse');		
		$('#page').css('margin-left','0px');
	}else{
		$('#sidebar').show();
		$('#expandCollapse').show();
		$(ths).attr('alt','hidemenu');
		$(ths).attr('title','Click to Expand');
		$('#page').css('margin-left','220px');
		}
}
function expandCollapse(){
	if($('#expandCollapse').text()=='Expand'){
		//$('#user_details_menu').append('');
		$('#sidebar .section_content').slideDown();
		$('#expandCollapse').text('Collapse');
	}
	else{
		$('#sidebar .section_content').slideUp();
		$('#expandCollapse').text('Expand');
		}
}
function autoHeight(removeExtra,objName){	
	var winHeight=$(window).height();
	var objHeight=winHeight-removeExtra;
	$(objName).css('height',objHeight);
}

//// behaviour.js

try {
  document.execCommand("BackgroundImageCache", false, true);
} catch(err) {}

function addDOMLoadEvent(f){if(!window.__ADLE){var n=function(){if(arguments.callee.d)return;arguments.callee.d=true;if(window.__ADLET){clearInterval(window.__ADLET);window.__ADLET=null}for(var i=0;i<window.__ADLE.length;i++){window.__ADLE[i]()}window.__ADLE=null};if(document.addEventListener)document.addEventListener("DOMContentLoaded",n,false);/*@cc_on @*//*@if (@_win32)document.write("<scr"+"ipt id=__ie_onload defer src=//0><\/scr"+"ipt>");var s=document.getElementById("__ie_onload");s.onreadystatechange=function(){if(this.readyState=="complete")n()};/*@end @*/if(/WebKit/i.test(navigator.userAgent)){window.__ADLET=setInterval(function(){if(/loaded|complete/.test(document.readyState)){n()}},10)}window.onload=n;window.__ADLE=[]}window.__ADLE.push(f)}


function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}


function fixlayout_ff () {
	var title_wrapper_right =  getElementsByClass('title_wrapper_right',null,null);
	if(!title_wrapper_right.length) {return false}
	for(i=0; i<title_wrapper_right.length; i++) {
		var this_element = title_wrapper_right[i];
		var this_element_parent = title_wrapper_right[i].parentNode;
		this_element.style.display = 'none';
		title_wrapper_right[i].parentNode.removeChild(title_wrapper_right[i]);
		this_element_parent.appendChild(this_element);
		
		this_element.style.display = 'block';
		
	}
}


window.onresize = function () {
	fixlayout_ff ();
}
//wall function javascript
function tabClk(tabClass,ths){	
	$('.tab').removeClass('active');
	$(ths).addClass('active');	
	$('.wall').hide();
	$('.'+tabClass).show();
	$('.toggleContent').height($('#ajax_content').height());
}
	
function showMore(ths){
	$('div.more').hide();	
	if($(ths).hasClass('active')){
		$(ths).removeClass('active')
		$('div.more').hide();
		}
	else{
		$(ths).addClass('active');
		$('div.more').show();
	}		
}