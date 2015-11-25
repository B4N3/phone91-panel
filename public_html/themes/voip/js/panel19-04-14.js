/* ============================================================
 * js for Phone91 user panel
 * v1.1 by Sarfaraz Ansari & Rahul Chourdiya
 * sarfaraz@hostnsoft.com 
 * rahul@hostnsoft.com
 * Date : 20 July 2013
 * Last Update 20 Oct 2013
 * 
 * ============================================================
 * this js contains multiple functions some of them are-:
 1 autoAdjust- for adjust height and width of some element on window resize
 2 uiDrop- for use of dropdown menu
 3 loadpage- is used for load page in destination div
 4 _load- this function initialize loadpage on hashchange
 5 show_message this function work on show notifications
 6 makeFakeSpan- this function is used for make fake span in left menu section for make quick search easy
 7 searchClient- this function is used for search clients
 8 actMenu- this function is used for expand or collapse the navigation items
 9 Progress bar in li's
 ******************************functions for smaller screens******************************
 10  showCmenu- this function is work on smaller screens for animate client search wrapper
 11  showMmenu- this function is work on show navigation pane in smaller screen
 * ============================================================ */

/*! 
Tocca.js v0.0.7 || Gianluca Guarini 
for mobile touch event
*/
!function(a,b){"use strict";if("function"!=typeof a.createEvent)return!1;var c,d,e,f,g,h="undefined"!=typeof jQuery,i=!!("ontouchstart"in window)&&navigator.userAgent.indexOf("PhantomJS")<0,j=function(a,b,c){for(var d=b.split(" "),e=d.length;e--;)a.addEventListener(d[e],c,!1)},k=function(a){return a.targetTouches?a.targetTouches[0]:a},l=function(b,e,f,g){var i=a.createEvent("Event");if(g=g||{},g.x=c,g.y=d,g.distance=g.distance,h)jQuery(b).trigger(e,g);else{i.originalEvent=f;for(var j in g)i[j]=g[j];i.initEvent(e,!0,!0),b.dispatchEvent(i)}},m=!1,n=b.SWIPE_TRESHOLD||80,o=b.TAP_TRESHOLD||200,p=b.TAP_PRECISION/2||30,q=0;i=b.JUST_ON_TOUCH_DEVICES?!0:i,j(a,i?"touchstart":"mousedown",function(a){var b=k(a);e=c=b.pageX,f=d=b.pageY,m=!0,q++,clearTimeout(g),g=setTimeout(function(){e>=c-p&&c+p>=e&&f>=d-p&&d+p>=f&&!m&&l(a.target,2===q?"dbltap":"tap",a),q=0},o)}),j(a,i?"touchend touchcancel":"mouseup",function(a){var b=[],g=f-d,h=e-c;if(m=!1,-n>=h&&b.push("swiperight"),h>=n&&b.push("swipeleft"),-n>=g&&b.push("swipedown"),g>=n&&b.push("swipeup"),b.length)for(var i=0;i<b.length;i++){var j=b[i];l(a.target,j,a,{distance:{x:Math.abs(h),y:Math.abs(g)}})}}),j(a,i?"touchmove":"mousemove",function(a){var b=k(a);c=b.pageX,d=b.pageY})}(document,window);

var globalTimeout = null;

/*settings*/
function showAddAccbox(){
	$('#addAccbox').toggle();
}

function notActionBtn(e){
	if($(e.target).parents(".action").length == 1 || $(e.target).hasClass('action'))
		return false;
	else
		return true;	
}
/*===================== This function is used for dropdown menus========*/
function uiDrop(ths,target, auto){	
	if( $(target).is(':visible')){
		$(ths).removeClass('active');
		$(target).slideUp('fast');
	}
	else
	{
		$(ths).addClass('active');
		$(target).slideDown('fast');
	}
	
	$(target).mouseup(function(e){
		e.preventDefault();
		return false;
	});
	
	var platform = navigator.platform; 

 	if( platform == 'iPad'){
		$(document).unbind('touchstart');
		
		document.addEventListener('touchend', function(e) {
			if(auto == 'true'){
				$(target).slideUp('fast');
				e.preventDefault();
				return false;
			}
		}, false);
	
	}
	
	var userAgent = navigator.userAgent.toLowerCase();
    var isIphone = (userAgent.indexOf('iphone') != -1) ? true : false;
 
    if (isIphone) {
		$('#body').unbind('mouseup');	
		$('#body').mouseup(function(e){
			if(auto == 'true')
				$(target).slideUp('fast');
		});
	}
	else{
		$(document).unbind('mouseup');	
		$(document).mouseup(function(e){
			if(auto == 'true')
				$(target).slideUp('fast');
				
		});
	}
};
/*===================== This function is used for page load in destination id========*/
function ajaxload(options){
		var defaults = {
		url:'',	//url
		loadIn:null,	//load html in objects
		prepTo:null,	//prepend to objects
		appTo:null,	//append to objects
		before:null,	//before to objects
		after:null,	//after an objects
		del:null,	//remove objects
		data:'',	//form data
		dtype:'html',	//xml, json, script, or html
		ptype:'GET',	//GET or POST
		beforeSend:function(){},//callback function
		success:function(text){}//callback function
	};		
	var opts = $.extend(defaults, options);	
	var forwardToCallback;	
		xRequest = $.ajax({
			type: opts.ptype,
			url:opts.url,			
			cache:true,
			data:opts.data,
			dataType:opts.dtype,		
			timeout: 15000,
			error:function(jqXHR, textStatus, errorThrown){
				if(textStatus == 'timeout'){
					jqXHR.abort();notification('error','Request Timeout, Try Again!')
				};
				$('#progress').width(0);
			},
			beforeSend:function(){opts.beforeSend.call(this)},
			success: function(data){
				ga('send', 'pageview',window.location.href);
				
				var Data;
				if(opts.dtype=='html') Data = data;							
				if(opts.dtype=='json') Data = data.data;
				
				if(opts.loadIn != null)
					$(opts.loadIn).html(Data);
				if(opts.prepTo != null)
					$(opts.prepTo).prepend(Data);
				if(opts.appTo != null)
					$(opts.appTo).append(Data);
				if(opts.before != null)
					$(opts.before).append(Data);
				if(opts.after != null)
					$(opts.after).append(Data);					
				if(opts.del != null)
					$(opts.del).remove();					
				if(data.msgtype != undefined && data.msg != undefined)
					notification(data.msgtype,data.msg);
															
				forwardToCallback = data;
			},
			statusCode:{				 
			},
			complete: function(jqXHR, textStatus){								
				if(textStatus == 'timeout'){return false;}
				else{opts.success.call(this,forwardToCallback);}				
			}		
		});
		return xRequest;
 	}
var pageLoad=0;
if(window.location.hash && pageLoad==0){_load();pageLoad=1;}

$(window).bind('hashchange', function(){_load()});

/**********************Description of this Function*****************/
/*
this function is written for load a page by ajax 
and it load page in two different section 
first one is get by #
the second one is get by |
and the credit goes to one and only sarfaraz@hostnsoft.com

*/
var currentPage;
function _load(type){
	$('#rightsec').html('');
	/*****************************************
	define some variable let me introduce it
	1. hash = read window.location
	2. url1 = for load page in section one just after #
	3. url2 = load page in section two getting it after | (pipe)
	
	4. url1Pre (left url) = getting the cut name in section one url
	5. url2Pre (left url) = getting the cut name in section two url
	****************************************/
	var hash, url1,url2, url1Pre, url2Pre;
	hash = window.location.hash.substring(2);
	url1 = hash;
	
	if(hash.indexOf('%7C') != -1){
		hash = hash.replace("%7C","|");
	}
	if(hash.indexOf('|') != -1){
		url1 = hash.split('|')[0];
		url1Pre = url1.split('.')[0];
		url2 = hash.split('|')[1];
		url2Pre = url2.split('.')[0];
	}
	
	//if url consist on single url
    if(currentPage == url1 && url2 != undefined && url2.length > 2){		
        ajaxload({url:url2, loadIn:'#rightsec',success:function(){if(hash.indexOf("setting")==0)setActive(url2);}})
    }	
	//Else if url consist of Two pages. Load first in Container and second page in rightsec
	else
	{		
		currentPage = url1;
		if(url1.length > 2)
		{
			ajaxload({
				url:url1,
				loadIn:'#container',
				success:function(){
					setActive(currentPage);
					if(hash.indexOf('|') != -1)
					{
						ajaxload({
							url:url2,
							loadIn:'#rightsec',
							success:function(){if(hash.indexOf("setting")==0)setActive(url2);}
						})
					}
				}
			})
		}
	}
}

function show_message(message,type)
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
	
	toastr[type](message)
}


$( document ).ajaxStart(function() {
        $("#progress" ).show().animate({
                width: "80%",
            },{
                duration: 100,
            });
})

$( document ).ajaxStop(function() {
    //$('#progress').css('width','101%')
    $( "#progress" ).animate({
                width: "100%",				
            }, {
                duration: 100,				
                complete: function() {
                    $( "#progress" ).fadeOut('slow',function(){$(this).css('width',0)});
                }
            });

});

function generatePassword(len){
    var pwd = [], cc = String.fromCharCode, R = Math.random, rnd, i;
    pwd.push(cc(48+(0|R()*10))); // push a number
    pwd.push(cc(65+(0|R()*26))); // push an upper case letter

    for(i=2; i<len; i++){
       rnd = 0|R()*62; // generate upper OR lower OR number
       pwd.push(cc(48+rnd+(rnd>9?7:0)+(rnd>35?6:0)));
    }

    // shuffle letters in password
    return pwd.sort(function(){ return R() - .5; }).join('');
}

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

function slideAndBack(showObj,hideObj){
	/*for responsive design*/
	if($(window).width() <= 600){	
		$('.slideAndBack').on('tap',function(e){
			if(notActionBtn(e)){
				setTimeout(function(){
					$(showObj).hide();
					$(hideObj).show();
				},600);
			}
		});
		
		$('.back').on('tap',function(e){		
			setTimeout(function(){
				$(showObj).show();
				$(hideObj).hide();
			},600);					
		});
	}
}

function dynamicPageName(page){
	$('#dynamicPageName').html(page);
}
$(document).ready(function(){
	$(document).bind("ajaxStart", function(){
		$('#logo').attr({
			src:'images/loading.gif',
			height:32
		}).css('marginTop','7px');
		//$('#header').css({'background-image':'url(images/loading2.gif)','background-repeat':'no-repeat','background-position':'130px 14px'})
	}).bind("ajaxStop", function(){		
		//$('#header').css({'background-image':'none'})
		$('#logo').attr({
			src:'images/phone91-logo.jpg',
			height:46
		}).css('marginTop','0');
	});	
});

function getReq(options){		
	var defaults = {name : "", str : window.location.href};
	var opts = $.extend(defaults, options);		
	name = opts.name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\(?|#)&]"+opts.name+"=([^&#]*)";			
	var regex = new RegExp( regexS );
	var result = regex.exec( opts.str );		
	if( result == null ) return ''; else return result[1];
};

function setActive(hilit){//console.log(hilit);
	
	function rc(v){ $(v).removeClass('active');}
	function ac(v){ $(v).addClass('active');}
	
	rc('.userMenuLi li');	
	rc('.resellerMenuLi li');
	rc('#topRightAct li');
	$('#manageLbl').html('Manage');
	$('#logLbl').html('Log');
	
	if(hilit == 'contact.php'){
		 ac('.contactLi');
	}		
	
	if(hilit == 'call-log.php'){
		ac('.call-logLi');
	}	
			
	if(hilit == 'reseller-manage-clients.php'){
		$('#manageLbl').html('Manage Clients'); ac('.resellerManage');
	}
	
	if(hilit == 'reseller-manage-plan.php'){
		$('#manageLbl').html('Manage Plans'); ac('.resellerManage');		
	}
	
	if(hilit == 'reseller-manage-pins.php'){
		$('#manageLbl').html('Manage Pins'); ac('.resellerManage');
	}
	if(hilit == 'manage-websites.php'){
		$('#manageLbl').html('Manage Websites'); ac('.resellerManage');
	}
	
	if(hilit == 'reseller-transactional-log.php'){
		$('#logLbl').html('Transaction Log'); ac('.resellerLog');
	}
	
	if(hilit == 'reseller-call-log.php'){
		$('#logLbl').html('Call Log'); ac('.resellerLog');
	}
	
	if(hilit == 'reseller-call-log.php'){
		$('#logLbl').html('Call Log'); ac('.resellerLog');
	}
	
	if(hilit == 'panel-pricing.php' || hilit == 'buymore.php' || hilit == 'transactions.php'){
		ac('.balance');
		$('#setmenu a').removeClass('active');
		$('.'+hilit.split(".")[0]).addClass('active');
	}
	
	if(hilit == 'email.php' || hilit == 'phone.php' || hilit == 'register-ids.php' || hilit == 'personal.php' || hilit == 'change-password.php' || hilit == 'news-updates.php' || hilit == 'reseller-setting.php' || hilit == 'clicktocall_Setting.php'){
		ac('.setting');
		$('#setmenu a').removeClass('active');
		$('.'+hilit.split(".")[0]).addClass('active');
	}
}

//function for pagination
function pagination(count,strt,divs,clientId)
{
    if(strt == undefined || strt == 0 || strt== "")
        strt=1;
    
    if(count == undefined || count == 0 || count== "")
        count = 1;
        //code for pagination
	if(count > 2 ){
		$(divs).paginate({
			count       : count,
			start       : strt,
			display     : 10,
			border : false,
			text_color: '#000',
			background_color: '#ddd',
			text_hover_color: '#fff',
			background_hover_color: '#333',
			images                  : false,
			mouse                   : 'press',
			page_choice_display     : true,
			show_first              : true,
			show_last               : true,
			rotate					: false,
			item_count_display      : true,						
			item_count_total : count,
			onChange                : function(page){
                            if(clientId == undefined || clientId == null )
				window.location.href= window.location.href.split('?')[0]+'?&pageNo='+page;
                            else
                               window.location.href= window.location.href.split('?')[0]+'?&pageNo='+page+'&clientId='+clientId;
                        }
                                    
		});
	}
}


/**
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com> 
 * @since 20/01/2014
 * @uses function for load more on scroll
 * @param int pageNo current page number
 * @param int pages total number of pages
 * @param string action url to get data
 * @param string functionForDesign fuction name in whick html data will append
 * @param string scrollTarget element in whick scroll work
 */
function loadMoreDetail(pageNo,pages,action,functionForDesign,scrollTarget,callback)
{
        //$(target).load("autoload_process.php", {'page':pageNo}, function() {pageNo++;}); //load first group
  
        $('#'+scrollTarget).scroll(function() { //detect page scroll
        
        
        if((document.getElementById(scrollTarget).scrollHeight < ($('#'+scrollTarget).height()+$('#'+scrollTarget).scrollTop()+100)))  //user scrolled to bottom of the page?
         {
           
             //if gobalTimeout set then clear it
             if(globalTimeout != null) 
         	clearTimeout(globalTimeout);
		
              //use setTimeout to resist multiple requests  
              globalTimeout=setTimeout(function(){
              if(pageNo <= pages) //there's more data to load
            	{
            		
            		
	                loading = true; //prevent further ajax loading
	                //$('.animation_image').show(); //show loading image
	                
	                //load data from the server using a HTTP POST request
	                $.ajax({url:action,
	                	type:'POST',
	                	dataType:'json',
	                	data:'pageNo='+pageNo, 
	                	success:function(data){
		                    var str = window[functionForDesign](data);
		                     //var str = createDesign(data);								
		                    $('#'+scrollTarget).append(str); //append received data into the element
		                    slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
		                    pageNo++; //loaded group increment

		                    //loadMoreDetail(pageNo,pages,action,functionForDesign,scrollTarget);
		                    loading = false; 
							if (callback && typeof(callback) === "function") {  
								callback(str);  
							}
	                	}
	                 });
	                 
                
            	}  
                            
               },600); //end of setTimeout function
         }   
    });
} //end of loadMoreDetail function

/**
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
 * @since 20/02/2014
 * @uses to create acm list design
 */
function createAcmList(data)
{
    var str = '';
    
    //validate msg
    if(typeof data != 'object')
        return str;
    
    if(typeof data.detail != 'object')
        return str;
    
    console.log(data);
    
    $.each( data.detail, function(key, value ) {
        str += '<li onclick="window.location.href=\'#!manage-account-manager.php|update-account-manager.php?acmId='+value.acmId+'\'">\
                                    <div class="linkCont">\
                                            <span class="ic-16 link"></span>\
                                        <div class="showLinksCont dn">\
                                                <span class="blackThmCrl">'+value.fullName+'</span>\
                                            <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>\
                                        </div>\
                                    </div>\
                                    <div class="usrDescr">\
                                            <div class="fixed">\
                                                <p class="uname ellp">\
                                                            '+value.fullName+'<h3 class="yelloThmCrl ellp">'+value.userName+'</h3>\
                                                    <span class="funder">\
                                                        <label onclick="deleteAcm(this,'+value.acmId+');" for="chnage" class="ic-32 grnEnabl cp ">\
                                                        </label>\
                                                    <input type="checkbox" id="chnage" style="display:none" checked="checked"  value ="" />\
                                                    </span>\
                                                    <p class="textSip">Delete</p>\
                                            </div>\
                                </div>\
                            </li>';
        
    });
    
    return str;
}