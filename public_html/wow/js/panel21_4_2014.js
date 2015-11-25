
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
/*defining some variables for auto adjust function
* this function is used for adjust auto height or width on some particular elements
* _W = window width
* _H = window height
* _headerH = header height
* _headerLeftSectionW = header left section width
* _lH = left height in document
* _lW = left width in document
* _headLeftSect1 = header left section width in 1x
* _headLeftSect2 = header left section width in 2x
* _lW2 = left width in document when applying two times left section this variable only use for in header section
*/
/*var _W, _H, _head, _lH, _lM, modH, _lW;
function Set(){
	_W = $(window).width(); //retrieve current window width
	_H = $(window).height(); //retrieve current window height
	_head = $('#header').outerHeight(true);//retrieve current header height
	_lH = _H - _head; //retrieve left height
	modH = _lH-150;
	_lW = _W - $('#leftsec').outerWidth(true);
	
	$('#container').css({height:_lH});
	$('#container #leftsec, #container #rightsec').css({height:modH});
	$('#container #rightsec').css({width:_lW-100});
}
$(function() {
	Set();
});
$(window).resize(function() {
	Set();
});*/
/*===================== This function is used for dropdown menus========*/

var globalTimeout = null;
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
		success:function(text){}//callback function
	};		
	var opts = $.extend(defaults, options);	
	var forwardToCallback;	
		xRequest = $.ajax({
			type: opts.ptype,
			url:opts.url,
			beforeSend:function(){},
			cache:true,
			data:opts.data,
			dataType:opts.dtype,		
			timeout: 15000,
			error:function(jqXHR, textStatus, errorThrown){
				if(textStatus == 'timeout'){jqXHR.abort();notification('error','Request Timeout, Try Again!')};
			},
			success: function(data){
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
	/*********************************
		this below lines written for activate the current menu in section one
		and the credit goes to one and only sarfaraz@hostnsoft.com
		************************************/
               /*********************************
                                    this below lines written for activate the current menu in section two
                                    and the credit goes to one and only sarfaraz@hostnsoft.com
                                    ************************************/
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
	
	if(hash.indexOf('|') != -1){
		url1 = hash.split('|')[0];
		url1Pre = url1.split('.')[0];
		url2 = hash.split('|')[1];
                url2Pre = url2.split('.')[0];
				
		$('.topmenu li, .topmenu li a').removeClass('active')
		
		if(url1Pre === 'setting'){
			$('li.'+ url1Pre).addClass('active')
		}
		else{
			
			$('li a.'+ url1Pre).addClass('active')
		}
	}	
	//if url consist on single url
    if(currentPage==url1 && url2!=undefined){
            ajaxload({
						url:url2,
						loadIn:'#rightsec',
						success:function(){
//								$('.cntnav li a').removeClass('active')
//								$('.cntnav li a.'+lUrl2).addClass('active')
						}
                    })

        }
        //Else if url consist of Two pages. Load first in Container and second page in rightsec
        else{

             currentPage=url1;
            ajaxload({
                    url:url1,
                    loadIn:'#container',
                    success:function(){
                            if(hash.indexOf('|') != -1){
                                    $('.cntnav li a').removeClass('active')
                                    $('.cntnav li a.'+url2Pre).addClass('active')
                                    ajaxload({
                                            url:url2,
                                            loadIn:'#rightsec',
                                            success:function(){

                                            }
                                    })
                            }	
                    }
            })
        }
}
function changePage(url1,url2){
    
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
//var loadingInterval;
//function motion(obj,col,width,height){
//	var  bgPosX=0, bgPosY=0,r=1, c=1;
//        if(loadingInterval)
//            clearInterval(loadingInterval);
//	loadingInterval = setInterval(function(){
////		
//		if(c == col){c = 1;bgPosX=0;r=1;bgPosY=0;}
//            {$(obj).css({'background-position':bgPosX+'px '+bgPosY+'px'}); c++; bgPosX=bgPosX-width;}
//							
//	},120);		
//}
//
//
//function p91Loader(action){
    
//    if(action=='start')
//    {
//        $('#motion').show();
//        motion('#motion',10,150,200);
//    }
//    else
//    {
//        $('#motion').hide();
//        clearInterval(loadingInterval);
//    }
//}

$( document ).ajaxStart(function() {
        $( "#progress" ).show().animate({
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

// type 
function pagination(count,strt,divs,type,action)
{
//     if(type == 1){
//         type ='batchId';
//     }else
//         type ='clientId';
//    
    if(strt == undefined || strt == 0 || strt== "")
        strt=1;
    
    if(count == undefined || count == 0 || count== "")
        count = 1;
    
    
        //code for pagination
	if(count > 1 ){
            
            
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
                            
                         switch(type)
                         {
                             case 0:
                                 getEditFundLog(page,type);
                                 break;
                             case 1:
                                 getcallLimitLog(page,type);
                             break;
                             case 2:
                                 getBandWidthLimitLog(page,type);
                             break;
                             case 3:
                                 getChangeTeriffLog(page.type);
                             break;
                             case 4:
                                 getChangeAccMangerLog(page,type);
                             break;
                             case 5:
                                 getChangeUserStatusLog(page,action,type);
                             break;
                             case 6:
                                 getDeleteUserLog(page,type);
                             break;
                             
                         }

//                            if(clientId == undefined || clientId == null )
//				window.location.href= window.location.href.split('?')[0]+'?pageNo='+page;
//                            else
//                               window.location.href= window.location.href.split('?')[0]+'?pageNo='+page+'&'+type+'='+clientId;
                        }
                                    
		});
	}
        
}

// type 
function clientPagination(count,strt,divs,clientId,type)
{
     if(type == 1){
         type ='batchId';
     }else
         type ='clientId';
    
    if(strt == undefined || strt == 0 || strt== "")
        strt=1;
    
    if(count == undefined || count == 0 || count== "")
        count = 1;
    
    
        //code for pagination
	if(count > 1 ){
            
            
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
				window.location.href= window.location.href.split('?')[0]+'?pageNo='+page;
                            else
                               window.location.href= window.location.href.split('?')[0]+'?pageNo='+page+'&'+type+'='+clientId;
                           // if(type ==0)
//                               getTransactionLog(clientId); 
//                            else if(type == 1)
                                
                                
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
    console.log(pageNo);
        //$(target).load("autoload_process.php", {'page':pageNo}, function() {pageNo++;}); //load first group
        $('#'+scrollTarget).scroll(function() { 
            //detect page scroll
            
            console.log(action);
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
//                                    $('#leftsec, .scrolll ').perfectScrollbar();
//		                    slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
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

var currencyList='';
/**
 * @author sudhir pandey <sudhir@hostnsoft.com>
 * @since 10/04/2014
 * @uses get currency list and set all select field option as a class
 * @ add class name into select box for show all currency list. 
 */
function getCurrencyList()
{
    $.ajax({
                   url : "/controller/adminManageClientCnt.php?action=getCurrencyList", 
                   type: "POST", 
                   dataType: "json",
                   success:function (text)
                   {
                        
                        if(typeof text != 'object')
                        return currencyList;
                   
                    $.each(text, function(key, value ) {
                    currencyList += '<option value="'+key+'">'+value+'</option>';
                    })
                                  
                   }
       });
}     

getCurrencyList();