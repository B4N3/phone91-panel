function loadpage(url,destination){
	wh=$(window).height()-115;
	$('#ajax_content').css('min-height',wh);
	$('#loading').show().html("<img class='loading' src='images/loading.gif' height='32' width='32' />");
	//$.ajaxSetup({ cache: false });
	$.ajax({type: "GET",url:url,cache:false,success: function(msg){
			$('#loading').hide()
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