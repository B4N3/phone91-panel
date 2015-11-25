//depends on jquery
/*Gloabel variables*/
var _rndr = true;
var _globalTimeout = null;

function js(){	
	this.uiDrop = function (ths,target,auto){
		if( $(target).is(':visible'))
			$(target).slideUp('fast');
		else
			$(target).slideDown('fast');
		
		$(target).mouseup(function(e){
			e.preventDefault();
			return false;
		});		
		$(document).unbind('mouseup');	
		$(document).mouseup(function(e){
			if(!auto)
				$(target).slideUp('fast');			
		});
	};
	
	this.notification = function(type,msg){
		$('#noteInner').stop();
		$('#noteInner').css({'display':'block','display':'inline-block'}).addClass(type).html(msg).delay(2000).fadeOut("slow");
	};
}
var js = new js();

function jd(options,callback){
	var defaults = {
		str:'h1 How it works!', //jade string
		tmpl:null, //template string
		obj:{data:null} //template string			
	}
	if (options && typeof(options) === "function")
	{
		var opts = defaults;
	}
	else
	{
		var opts = $.extend(defaults, options);			
	}		
	
	if(opts.tmpl != null){			
		opts.str = $(opts.tmpl).html();
	}
	
	try
	{
		var fn = jade.compile(opts.str);				
		var html = fn(opts.obj);
		
		if (callback && typeof(callback) === "function") {
			callback(html);
		}
		if (options && typeof(options) === "function") {
			options(html);
		}
		if (callback == undefined && options == undefined) {
			var note ='<p>Note: Use Callback function to show OUTPUT</p>'
						+'Example: jd ({tmpl:,obj:},function(data){ $(\'#content\').html(data) })</br>';
						
			$('body').html(html+note);
		}
	}
	catch(err)
	{
		throw new Error ('template: '+opts.tmpl+', '+err.message);		  
	}
}
if(window.location.pathname == "/userhome2.php" || window.location.pathname == "/userhome.php")
jd({tmpl:'#userhome'},function(data){
	$('#content').html(data);
	if(!window.location.hash)
		window.location.hash = "#action.php?action=twowayCalling&lnk=twc";
});

function updateQueryStringParameter(uri, key, value) {
  var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i");
  separator = uri.indexOf('?') !== -1 ? "&" : "?";
  if (uri.match(re)) {
    return uri.replace(re, '$1' + key + "=" + value + '$2');
  }
  else {
    return uri + separator + key + "=" + value;
  }
}


function changeQs(str)//str = {"key1":"value1"}
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
   
}			

		
function request(options){		
	var defaults = {name : "", str : window.location.href};
	var opts = $.extend(defaults, options);		
	name = opts.name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\(?|#)&]"+opts.name+"=([^&#]*)";			
	var regex = new RegExp( regexS );
	var result = regex.exec( opts.str );		
	if( result == null ) return ''; else return result[1];
}
	
function loadPage(){
	var _hash = window.location.hash.substring(1);
	//var patt = /[\?\&]/g;
	var patt = /action/g;
	var tmpl = patt.test(_hash);
	var tmplName = request({name:'action'});
	var local = request({name:'local'});
	/*$('a').each(function(){
		if (typeof attr !== 'undefined' && attr !== false)
			if($(this).attr('href').substring(1)==_hash)
			{
				$('a').removeClass('active');
				$(this).addClass('active');
			}
	})*/
	var lnk = request({name:'lnk'});
	$('#menu a, #nav a').removeClass('active');
	$('#'+lnk).addClass('active');
	
	if(local)
	{
		jd({tmpl:'#'+tmplName},function(html){	
			$('#right').html(html);	
		});
		tmpl=false;	
		return;
	}
	else if(tmpl)
	{		
		$.ajax({
			url: _hash,
			dataType:"json",
			error:function(jqXHR,textStatus,errorThrown){
				
			},
			beforeSend:function(){
				
			},
			success:function(data){console.log(data);
				jd({tmpl:'#'+tmplName,obj:data},function(html){
					if(_rndr)
						$('#right').html(html);							
					else{
						$('#subDiv').html(html);
						_rndr = true;
					}
				});
			}
		})	
	}
	else
	{
		$.ajax({
			url: _hash,		
			success:function(html){
				$('#right').html(html);
				var tab = request({name:'tab'});
				$('#'+tab).show();
			}
		})
	}	
}

if(window.location.hash){	
	loadPage();
}

$(window).bind('hashchange', function(){ 
	loadPage();
});

$(document).ready(function(){		
	$(document)
	.bind("ajaxStart", function(){
		$('#header').css({'background-image':'url(images/loading2.gif)','background-repeat':'no-repeat','background-position':'130px 14px'})
	})
	.bind("ajaxStop", function(){		
		$('#header').css({'background-image':'none'})
	});		
});

function show_message(error_message,type)
{
	if($(window).width() <= 600)
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
	
	toastr[type](error_message)
}

var _urls = {
		'Home':['Home','#action.php?action=twowayCalling&lnk=twc'],
		'ManagePin':['Manage Pin','#action.php?action=managePin&lnk=mpin'],
		'AddPin':['Add Pin','#action.php?action=addPin&lnk=mpin'],
		'PINs':['PINs',''],
		'TwoWayCalling':['Two Way Calling','#action.php?action=twowayCalling&lnk=twc'],
		'CallHistory':['Call History','#action.php?action=callHistory&lnk=ch'],
		'RechargeHistory':['Recharge History','#action.php?action=rechargeHistory&lnk=rh'],
		'ActiveCalls':['Active Calls','#action.php?action=activeCall&lnk=ac'],
		'ManageClients':['Manage Clients','#action.php?action=manageClients&lnk=mnc'],
		'ManagePlans':['Manage Plans','#action.php?action=managePlan&lnk=mnpln'],
		'ManageTariff':['Manage Tariff','#action.php?action=manageTariff&pid=7'],
		'AddPlans':['Add Plans','#action.php?action=addPlan&lnk=mnpln'],		
		'ManageTariff':['Manage Tariff','']
		}
	
function breadcrumbs(arrayOfLink){
	//a global variable _urls is used in ex._urls={'home':['Home','index.php']} 
	var breadcrumbs='<a href="'+_urls.Home[1]+'" class="fl i16 home16"></a>';
	var href;	
	for(i=1; i < arrayOfLink.length; i++){
		var fetch;
		var arr = arrayOfLink[i].split('#');
		fetch = arr[0];
		if(arr.length > 1)
		{			
			href = '#'+arr[1];
		}
		else
			(_urls[fetch][1] == '')? href = 'javascript:;' : href = _urls[fetch][1];
		console.log(href);
		breadcrumbs +='<span class="fl i16 rt16_1"></span><a class="fl" href="'+href+'">'+_urls[fetch][0]+'</a>';
	}
	$('#breadcrumbs').html(breadcrumbs);
}


//function contactForm()
//{
//    alert('entered!!!');
//    //validate 
//    var name = trim($('#contactName').val());
//    var email= trim($('#contactEmail').val());
//    var number = trim($('#ContactNumber').val());
//    var comment = trim($('#ContactComment').val());
//    
//    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
//    
//    //validate name
//    if(name == '')
//    {
//        show_message('Please enter name', 'warning');
//        return false;
//    }
//    else if(!emailReg.test(email))
//    {
//           show_message('Please enter valid email address', 'warning'); 
//           return false;
//    }
//    else if(isNaN(number) || number.length > 18)
//    {
//        show_message('Please enter valid number', 'warning'); 
//        return false;
//    }else if(comment == '')
//    {
//        show_message('Please enter your comment', 'warning'); 
//        return false;
//    }
//    
//    
//}



/** selectBox 1.0 **/ 
(function( $ ) { 
    $.fn.selectBox = function(options) {
		var opts = $.extend({           
            wrapClass:"default",
            backgroundColor: ""
        }, options );		
		
		return this.each(function(){
			var list='',ln,h,w,bdr,pd,mr,downArrow;
			downArrow = 'iVBORw0KGgoAAAANSUhEUgAAAAkAAAAICAYAAAArzdW1AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjFFRThCRDYxQ0MzQjExRTI5ODVCRTU2MURCMzgyMjk1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjFFRThCRDYyQ0MzQjExRTI5ODVCRTU2MURCMzgyMjk1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MUVFOEJENUZDQzNCMTFFMjk4NUJFNTYxREIzODIyOTUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MUVFOEJENjBDQzNCMTFFMjk4NUJFNTYxREIzODIyOTUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4G9OiUAAAATUlEQVR42mJgIAHkADEbmhgzEE9GFtgCxcgKFwHxQ2RF84H4PxCvgyqcAOWfR1bECcQHoBJXoTTIFDl0d4EUHsanAFnhUnwKcAKAAAMAvacQ2xZPt1gAAAAASUVORK5CYII=';
			//function getStyle(prop){return window.getComputedStyle(this, null).getPropertyValue(prop)}			
			
			h = $(this).height();
			w = $(this).width();
			/*bdr = window.getComputedStyle(this, null).getPropertyValue("border");
			pd = window.getComputedStyle(this, null).getPropertyValue("padding");
			mr = window.getComputedStyle(this, null).getPropertyValue("margin");
			bg = window.getComputedStyle(this, null).getPropertyValue("background-color");*/
			bdr = $(this).css("borderTopWidth")+' '+$(this).css("borderTopStyle")+' '+$(this).css("borderTopColor");
			pd = $(this).css("padding-top")+' '+$(this).css("padding-right")+' '+$(this).css("padding-bottom")+' '+$(this).css("padding-left");
			mr = $(this).css("margin-top")+' '+$(this).css("margin-right")+' '+$(this).css("margin-bottom")+' '+$(this).css("margin-left");
			bg = $(this).css("background-color");			
			selectedTxt = $(this).children(':selected').text();
			
			$(this).css({'opacity':'0','position':'absolute'})
			$(this).wrap('<div class="'+opts.wrapClass+'" style="position:relative;overflow:hidden;"></div>')
			$(this).after('<div class="selectLabel" style="width:'+w+'px; height:'+h+'px; margin:'+mr+'; padding:'+pd+'; border:'+bdr+'; background-image:url(data:image/png;base64,'+downArrow+'); background-position:'+(w-7)+'px center; background-repeat:no-repeat; background-color:'+bg+'">'+selectedTxt+'</div>');
			$(this).focus(function(){
				$(this).next().addClass('focus');
			})
			$(this).blur(function(){
				$(this).next().removeClass('focus');
			})
			$(this).change(function(){
				var txt = $(this).children(':selected').text();
				$(this).next().html(txt);
			})
			
			
    	});
		
    }; 
}( jQuery ));