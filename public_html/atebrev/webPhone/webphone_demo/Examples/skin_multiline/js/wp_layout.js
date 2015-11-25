var wp_layout = function ( )
{
// private members and methods

//default layout values
var def_background_color = '#94c23f';
var def_background_border_color = ''; //wp_layout.HoverCalc(def_background_color, -10);
var def_general_text_color = '#444444';
var def_button_color = '#01afd4';
var def_button_border_color = ''; //wp_layout.HoverCalc(def_button_color, -10);
var def_button_text_color = "#ffffff";
var def_webphone_width = 256;
var def_webphone_height = 450;
var def_call_button_color = '#00ff00';
var def_hangup_button_color = '#ff0000';
var def_status_text_color = '#26b14a';
var def_brandname = 'Mizutech';
var def_company_webpage = 'http://www.mizu-voip.com';

function SetBgColor()
{
	try{
		def_background_border_color = wp_layout.HoverCalc(def_background_color, -10);
		def_button_border_color = wp_layout.HoverCalc(def_button_color, -10);
	
	try{
		if (wp_api.general_text_color != null && wp_api.general_text_color.length > 2)
		{
			wp_layout.curr_general_text_color = wp_api.general_text_color;
		}else
		{
			wp_layout.curr_general_text_color = def_general_text_color;
		}
	}catch (e) {wp_layout.curr_general_text_color = def_general_text_color;}


	try{
		if (wp_api.background_color != null && wp_api.background_color.length > 2)
		{
			wp_layout.curr_background_color = wp_api.background_color;
		}else
		{
			wp_layout.curr_background_color = def_background_color;
		}
	}catch (e) {wp_layout.curr_background_color = def_background_color;}
	
	wp_layout.curr_background_border_color = wp_layout.HoverCalc(wp_layout.curr_background_color, -10);
	
	
	try{
		if (wp_api.button_color != null && wp_api.button_color.length > 2)
		{
			wp_layout.curr_button_color = wp_api.button_color;
		}else
		{
			wp_layout.curr_button_color = def_button_color;
		}
	}catch (e) {wp_layout.curr_button_color = def_button_color;}
	
	wp_layout.curr_button_border_color = wp_layout.HoverCalc(wp_layout.curr_button_color, -10);
	
	
	try{
		if (wp_api.button_text_color != null && wp_api.button_text_color.length > 2)
		{
			wp_layout.curr_button_text_color = wp_api.button_text_color;
		}else
		{
			wp_layout.curr_button_text_color = def_button_text_color;
		}
	}catch (e) {wp_layout.curr_button_text_color = def_button_text_color;}
	
	try{
		if (wp_api.webphone_width != null && wp_api.webphone_width > 0 && !isNaN(wp_api.webphone_width))
		{
			wp_layout.curr_webphone_width = wp_api.webphone_width;
		}else
		{
			wp_layout.curr_webphone_width = def_webphone_width;
		}
	}catch (e) {wp_layout.curr_webphone_width = def_webphone_width;}
	
	try{
		if (wp_api.webphone_height != null && wp_api.webphone_height > 0 && !isNaN(wp_api.webphone_height))
		{
			wp_layout.curr_webphone_height = wp_api.webphone_height;
		}else
		{
			wp_layout.curr_webphone_height = def_webphone_height;
		}
	}catch (e) {wp_layout.curr_webphone_height = def_webphone_height;}
	
	try{
		if (wp_api.call_button_color != null && wp_api.call_button_color.length > 2)
		{
			wp_layout.curr_call_button_color = wp_api.call_button_color;
		}else
		{
			wp_layout.curr_call_button_color = def_call_button_color;
		}
	}catch (e) {wp_layout.curr_call_button_color = def_call_button_color;}
	
	try{
		if (hangup_button_color != null && hangup_button_color.length > 2)
		{
			wp_layout.curr_hangup_button_color = hangup_button_color;
		}else
		{
			wp_layout.curr_hangup_button_color = def_hangup_button_color;
		}
	}catch (e) {wp_layout.curr_hangup_button_color = def_hangup_button_color;}

	try{
		if (wp_api.status_text_color == null && wp_api.status_text_color.length < 2)
		{
			wp_api.status_text_color = def_status_text_color;
		}
	}catch (e) {wp_api.status_text_color = def_status_text_color;}
	
	try{
		if (wp_api.brandname != null && wp_api.brandname.length > 1)
		{
			wp_layout.curr_brandname = wp_api.brandname;
		}else
		{
			wp_layout.curr_brandname = def_brandname;
		}
	}catch (e) {wp_layout.curr_brandname = def_brandname;}
	
	try{
		if (wp_api.company_webpage != null && wp_api.company_webpage.length > 2)
		{
			wp_layout.curr_company_webpage = wp_api.company_webpage;
		}else
		{
			wp_layout.curr_company_webpage = def_company_webpage;
		}
	}catch (e) {wp_layout.curr_company_webpage = def_company_webpage;}
	
	
	var loginTextBoxBorderColor = wp_layout.HoverCalc(wp_layout.curr_background_color, -20);
	loginTextBoxBorderColor = wp_layout.HoverCalc(loginTextBoxBorderColor, -20);
	loginTextBoxBorderColor = wp_layout.HoverCalc(loginTextBoxBorderColor, -20);
	
	var widthHeightPlus = 0;
	if ($.browser.msie) {widthHeightPlus = 4;}
	
	var widthTmp = wp_layout.curr_webphone_width + widthHeightPlus;
	var heightTmp = wp_layout.curr_webphone_height + widthHeightPlus
	
	document.getElementById("webphone_bg_container").style.width = widthTmp + 'px';
	document.getElementById("webphone_bg_container").style.height = heightTmp + 'px';
	
	var container_register_width = Math.floor(wp_layout.curr_webphone_width * 0.7);
	document.getElementById("container_register").style.width = container_register_width + 'px';
	document.getElementById("register_form").style.width = container_register_width + 'px';
	
	var container_dial_width = Math.floor(wp_layout.curr_webphone_width * 0.9);
	document.getElementById("container_dial").style.width = container_dial_width + 'px';
	
	
	document.getElementById("username_input").style.borderColor = loginTextBoxBorderColor;
	document.getElementById("password_input").style.borderColor = loginTextBoxBorderColor;
    
    if (document.getElementById('license_key_input') != null)       document.getElementById("license_key_input").style.borderColor = loginTextBoxBorderColor;
    if (document.getElementById('server_address_input') != null)    document.getElementById("server_address_input").style.borderColor = loginTextBoxBorderColor;
	
	document.body.style.color = wp_layout.curr_general_text_color;
	
	if (document.getElementById("btn_callhangup") != null)
	{
		document.getElementById("PhoneNumberDiv").style.backgroundColor = '#ffffff';
		document.getElementById("PhoneNumberDiv").style.borderColor = wp_layout.curr_background_border_color;
		
		document.getElementById("PhoneNumber").style.color = wp_layout.curr_general_text_color;
		document.getElementById("PhoneNumber").style.backgroundColor = '#ffffff';
	}else
	{
		document.getElementById("PhoneNumberDiv").style.backgroundColor = wp_layout.HoverCalc(wp_layout.curr_background_color, 30);
		document.getElementById("PhoneNumberDiv").style.borderColor = wp_layout.curr_background_border_color;
		
		document.getElementById("PhoneNumber").style.color = wp_layout.curr_general_text_color;
		document.getElementById("PhoneNumber").style.backgroundColor = wp_layout.HoverCalc(wp_layout.curr_background_color, 30);
	}
		
	document.getElementById("webphone_bg_container").style.backgroundColor = wp_layout.curr_background_color;
	document.getElementById("webphone_bg_container").style.borderColor = wp_layout.curr_background_border_color;
	if (document.getElementById('logo_a') != null)		document.getElementById("logo_a").style.color = wp_layout.curr_general_text_color;
	if (document.getElementById('logo2_a') != null)		document.getElementById("logo2_a").style.color = wp_layout.curr_general_text_color;
	document.getElementById("btn_connect").style.color = wp_layout.curr_button_text_color;
	document.getElementById("btn_connect").style.backgroundColor = wp_layout.curr_button_color;
	document.getElementById("btn_connect").style.borderColor = wp_layout.curr_button_border_color;
	
	var logoDivWidth = container_dial_width - 62;
	if (document.getElementById('logo') != null)		document.getElementById("logo").style.width = logoDivWidth + 'px';
	
	if (wp_layout.curr_company_webpage.indexOf("http://") < 0)
	{
		wp_layout.curr_company_webpage = "http://" + wp_layout.curr_company_webpage;
	}
	if (document.getElementById('logo_a') != null)		document.getElementById("logo_a").href = wp_layout.curr_company_webpage;
	if (document.getElementById('logo_a') != null)		document.getElementById("logo_a").innerHTML = wp_layout.curr_brandname;
	if (document.getElementById('logo_a') != null)		$("a#logo_a").attr("title",wp_layout.curr_brandname + ' Home Page');
	
	if (document.getElementById('logo2_a') != null)		document.getElementById("logo2_a").href = wp_layout.curr_company_webpage;
	if (document.getElementById('logo2_a') != null)		document.getElementById("logo2_a").innerHTML = wp_layout.curr_brandname;
	if (document.getElementById('logo2_a') != null)		$("a#logo2_a").attr("title",wp_layout.curr_brandname + ' Home Page');
	
	
	// numpad
	var borderDial = 0;
	var borderCallHangup = 0;
	var borderCallfunction = 0;
    var btnLineWidthDiff = 0
	if ($.browser.msie)
	{
		var browserVersion = parseInt($.browser.version, 10);
		if (browserVersion > 6)
		{						// IE 7,8,9,...
			borderDial = 24;		if (browserVersion > 9) { borderDial = 30; }
			borderCallHangup = 24;
			borderCallfunction = 10;
            btnLineWidthDiff = 22;		if (browserVersion > 9) { btnLineWidthDiff = 32; }
			
			// add <br> between img and span in all IE versions but 6
			if (document.getElementById("btn_chat") != null && document.getElementById("btn_transfer") != null && document.getElementById("btn_hold") != null && document.getElementById("btn_conference") != null)
			{
				$("#btn_chat img").after("<br />");
				$("#btn_transfer img").after("<br />");
				$("#btn_hold img").after("<br />");
				$("#btn_conference img").after("<br />");
			}
			if (document.getElementById('lines') != null)
			{
				document.getElementById('lines').style.marginLeft = '2px';
			}
		}else
		{						// IE 6
			borderDial = 24;
			borderCallHangup = 23;
			borderCallfunction = 15;
            btnLineWidthDiff = 26;
			
			if (document.getElementById("btn_chat") != null && document.getElementById("btn_transfer") != null && document.getElementById("btn_hold") != null && document.getElementById("btn_conference") != null)
			{
				document.getElementById("btn_chat").firstChild.style.display = 'none';
				document.getElementById("btn_chat").style.lineHeight = '31px';
				
				document.getElementById("btn_transfer").firstChild.style.display = 'none';
				document.getElementById("btn_transfer").style.lineHeight = '31px';
				
				document.getElementById("btn_hold").firstChild.style.display = 'none';
				document.getElementById("btn_hold").style.lineHeight = '31px';
				
				document.getElementById("btn_conference").firstChild.style.display = 'none';
				document.getElementById("btn_conference").style.lineHeight = '31px';
			}
			if (document.getElementById('lines') != null)
			{
				document.getElementById('lines').style.marginLeft = '5px';
			}
		}
		document.getElementById("numpad").style.paddingLeft = '2px';
		if (document.getElementById("callfunctions") != null)
		{
			document.getElementById("callfunctions").style.width = (Math.floor((container_dial_width - borderCallfunction + 4) / 4) * 4) + 'px';
		}
	}else
	{							// Other browsers
		borderDial = 31;
		borderCallHangup = 25;
		borderCallfunction = 12;
        btnLineWidthDiff = 35;
		
		if (document.getElementById("callfunctions") != null)
		{
			document.getElementById("callfunctions").style.marginLeft = '-2px';
		}
		
		// add <br> between img and span in all other browsers
		if (document.getElementById("btn_chat") != null && document.getElementById("btn_transfer") != null && document.getElementById("btn_hold") != null && document.getElementById("btn_conference") != null)
		{
			$("#btn_chat img").after("<br />");
			$("#btn_transfer img").after("<br />");
			$("#btn_hold img").after("<br />");
			$("#btn_conference img").after("<br />");
		}
		if (document.getElementById('lines') != null)
		{
			document.getElementById('lines').style.marginLeft = '3px';
		}
	}
	var buton_width = Math.floor((container_dial_width - borderDial) / 3)
	var buton_call_width = Math.floor((container_dial_width - borderCallHangup) / 2)
	var buton_callfunctions_width = Math.floor((container_dial_width - borderCallfunction) / 4)
	
	for (var i = 0; i < 12; i++)
	{
		var currId = "btn_"+i;
		var button = document.getElementById(currId);
		
		button.style.backgroundColor = wp_layout.curr_button_color;
		button.style.borderColor = wp_layout.curr_button_border_color;
		button.style.color = wp_layout.curr_button_text_color;
		
		button.style.width = buton_width + 'px';
	}
	
	if (document.getElementById("callbuttons") != null)
	{
		document.getElementById("btn_call").style.backgroundColor = wp_layout.curr_call_button_color;
		document.getElementById("btn_call").style.borderColor = wp_layout.HoverCalc(wp_layout.curr_call_button_color, -10);
		document.getElementById("btn_call").style.color = wp_layout.curr_button_text_color;
		document.getElementById("btn_call").style.width = buton_call_width + 'px';
	
		document.getElementById("btn_hangup").style.backgroundColor = wp_layout.curr_hangup_button_color;
		document.getElementById("btn_hangup").style.borderColor = wp_layout.HoverCalc(wp_layout.curr_hangup_button_color, -10);
		document.getElementById("btn_hangup").style.color = wp_layout.curr_button_text_color;
		document.getElementById("btn_hangup").style.width = buton_call_width + 'px';
	}
	
	if (document.getElementById("acceptreject") != null)
	{
		document.getElementById("btn_accept").style.backgroundColor = wp_layout.curr_call_button_color;
		document.getElementById("btn_accept").style.borderColor = wp_layout.HoverCalc(wp_layout.curr_call_button_color, -10);
		document.getElementById("btn_accept").style.color = wp_layout.curr_button_text_color;
		document.getElementById("btn_accept").style.width = buton_call_width + 'px';
		
		document.getElementById("btn_reject").style.backgroundColor = wp_layout.curr_hangup_button_color;
		document.getElementById("btn_reject").style.borderColor = wp_layout.HoverCalc(wp_layout.curr_hangup_button_color, -10);
		document.getElementById("btn_reject").style.color = wp_layout.curr_button_text_color;
		document.getElementById("btn_reject").style.width = buton_call_width + 'px';
	}
	
	if (document.getElementById("btn_callhangup") != null)
	{
		if ($.browser.msie)
		{
			var browserVersion = parseInt($.browser.version, 10);
			if (browserVersion > 6)
			{						// IE 7,8,9,...
				document.getElementById("btn_callhangup").style.width = Math.floor((container_dial_width - 36) / 3) + 'px';
			}else
			{						// IE 6
				document.getElementById("btn_callhangup").style.width = Math.floor((container_dial_width - 45) / 3) + 'px';
			}
		}else
		{							// Other browsers
			document.getElementById("btn_callhangup").style.width = Math.floor((container_dial_width - 56) / 3) + 'px';
		}
		
		document.getElementById("PhoneNumberDiv").style.width = Math.floor((container_dial_width * 2) / 3) + 'px'
		document.getElementById("btn_callhangup").style.backgroundColor = wp_layout.curr_call_button_color;
		document.getElementById("btn_callhangup").style.borderColor = wp_layout.HoverCalc(wp_layout.curr_call_button_color, -10);
		document.getElementById("btn_callhangup").style.color = wp_layout.curr_button_text_color;
		document.getElementById("btn_callhangup").innerHTML = 'Call';
	}
	
	if (document.getElementById("callfunctions") != null)
	{
		document.getElementById("callfunctions").style.backgroundColor = wp_layout.curr_button_color;
		document.getElementById("callfunctions").style.borderColor = wp_layout.curr_button_border_color;
		document.getElementById("callfunctions").style.color = wp_layout.curr_button_text_color;
	}
	
	if (document.getElementById("btn_chat") != null && document.getElementById("btn_transfer") != null && document.getElementById("btn_hold") != null && document.getElementById("btn_conference") != null)
	{
		document.getElementById("btn_chat").style.backgroundColor = wp_layout.curr_button_color;
		document.getElementById("btn_chat").style.borderColor = wp_layout.curr_button_border_color;
		document.getElementById("btn_chat").style.width = buton_callfunctions_width + 'px';

		if ($.browser.msie && parseInt($.browser.version, 10) === 10) // to allign callfunction in one line in IE 10
		{
			document.getElementById("btn_chat").style.borderLeftWidth = 0;
			document.getElementById("btn_chat").style.borderLeftStyle = 'none';
		}
		
		document.getElementById("btn_transfer").style.backgroundColor = wp_layout.curr_button_color;
		document.getElementById("btn_transfer").style.borderColor = wp_layout.curr_button_border_color;
		document.getElementById("btn_transfer").style.width = buton_callfunctions_width + 'px';
		
		document.getElementById("btn_hold").style.backgroundColor = wp_layout.curr_button_color;
		document.getElementById("btn_hold").style.borderColor = wp_layout.curr_button_border_color;
		document.getElementById("btn_hold").style.width = buton_callfunctions_width + 'px';
		
		document.getElementById("btn_conference").style.backgroundColor = wp_layout.curr_button_color;
		document.getElementById("btn_conference").style.borderColor = wp_layout.curr_button_border_color;
		document.getElementById("btn_conference").style.width = buton_callfunctions_width + 'px';
		
		var spanWidth = buton_callfunctions_width - 2;
		$("#btn_chat span").css('width', spanWidth + 'px');
		$("#btn_transfer span").css('width', spanWidth + 'px');
		$("#btn_hold span").css('width', spanWidth + 'px');
		$("#btn_conference span").css('width', spanWidth + 'px');
	}
	
    // manage Multiline skin
	var divLines;
	if (wp_api.isMultiLineSkin && wp_api.nrOfLines > 1)
	{
		divLines = document.getElementById('lines');
		
		for (var i = 0; i < wp_api.nrOfLines; i++)
		{
			var line = i + 1;
			//divLines.innerHTML += "<div id=\"line_" + line + "\" style=\"float:left; margin-top:6px;\"><div id=\"btn_line_" + line + "\" onclick=\"WJSAPI_ChangeLine("+line+")\">Line " + line + "</div><span id=\"status_line_" + line + "\"style=\"padding:0px; float:left; display:inline-block; margin-top:5px; margin-left:5px; font-size:11px; color:#26b14a; font-weight:bold; white-space:nowrap; overflow:hidden;\"></span></div>";
            divLines.innerHTML += "<div id=\"btn_line_" + line + "\" onclick=\"wp_common.wp_ChangeLine("+line+")\"><span id=\"btn_line_" + line + "_span\" style=\"display:inline-block; overflow:hidden;\">Line " + line + "</span></div>";
		}
		
		for (var i = 0; i < wp_api.nrOfLines; i++)
		{
			var line2 = i + 1;
			var idBtnLines = 'btn_line_' + line2;
			
			var currBtn = document.getElementById(idBtnLines);

			currBtn.style.backgroundColor = wp_layout.curr_button_color;
			currBtn.style.borderColor = wp_layout.curr_button_border_color;
			currBtn.style.color = wp_layout.curr_button_text_color;
			
            var btnWith = Math.floor((container_dial_width - btnLineWidthDiff) / 4);
            
			currBtn.style.width = btnWith + 'px';
            
            var btnSpanWidth = btnWith - 5;            
            document.getElementById('btn_line_' + line2 + '_span').style.width = btnSpanWidth + 'px';
			
			// hover
			$("div#"+idBtnLines).mouseenter(function()
			{
                var theLine = ($(this).attr('id')).charAt(($(this).attr('id')).length - 1);
                
				if ($.browser.msie)
				{
                    if (theLine == wp_common.currLine)
                    {
                        $(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_call_button_color, 15));
                    }else
                    {
                        $(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
                    }
				}else
				{
                    if (theLine == wp_common.currLine)
                    {                        
                        $(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_call_button_color, 15));
                    }else
                    {
                        $(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
                    }
				}
			}).mouseleave(function()
			{
                var theLine = ($(this).attr('id')).charAt(($(this).attr('id')).length - 1);
                
				if ($.browser.msie)
				{
                    if (theLine == wp_common.currLine)
                    {
                        $(this).children().css("background-color",wp_layout.curr_call_button_color);
                    }else
                    {
                        $(this).children().css("background-color",wp_layout.curr_button_color);
                    }
				}else
				{
                    if (theLine == wp_common.currLine)
                    {
                        $(this).css("background-color",wp_layout.curr_call_button_color);
                    }else
                    {
                        $(this).css("background-color",wp_layout.curr_button_color);
                    }
				}
			});
		}
		disableSelection(document.getElementById('lines'));
	}
    
    // manage Multiline skin button's style
    if (wp_api.isMultiLineSkin && wp_api.nrOfLines > 1)
    {
        for (var i = 1; i <= wp_api.nrOfLines; i++)
        {
            if (i == wp_common.currLine)
            {
                $("div#btn_line_"+i).css("background-color",wp_layout.curr_call_button_color);
                if (!$.browser.msie)    $("div#btn_line_"+i).css("border-color",wp_layout.HoverCalc(wp_layout.curr_call_button_color, -10));
                $("div#btn_line_"+i).css("font-weight","bold");
            }else
            {
                $("div#btn_line_"+i).css("background-color",wp_layout.curr_button_color);
                if (!$.browser.msie)    $("div#btn_line_"+i).css("border-color",wp_layout.curr_button_border_color);
                $("div#btn_line_"+i).css("font-weight","normal");
            }
        }
    }
	
	}catch (e) {  }
	
	if (document.getElementById('logo') != null)			disableSelection(document.getElementById('logo'));
	if (document.getElementById('logo2') != null)			disableSelection(document.getElementById('logo2'));
	if (document.getElementById('btn_connect') != null)		disableSelection(document.getElementById('btn_connect'));
	if (document.getElementById('header') != null)			disableSelection(document.getElementById('header'));
	if (document.getElementById('info') != null)			disableSelection(document.getElementById('info'));
	if (document.getElementById('numpad') != null)			disableSelection(document.getElementById('numpad'));
	if (document.getElementById('callbuttons') != null)		disableSelection(document.getElementById('callbuttons'));
	if (document.getElementById('acceptreject') != null)	disableSelection(document.getElementById('acceptreject'));
	if (document.getElementById('callfunctions') != null)	disableSelection(document.getElementById('callfunctions'));
	
    curvyCorners.init();
}

// disable text selection
function disableSelection(target)
{
	if (typeof target.onselectstart!="undefined") //For IE 
	    target.onselectstart=function(){return false}
		
	else if (typeof target.style.MozUserSelect!="undefined") //For Firefox
    	target.style.MozUserSelect="none"
	else //All other route (For Opera)
    	target.onmousedown=function(){return false}
	
	target.style.cursor = "default"
}

// public interface
return {

	//current layout values
	curr_background_color: '',
	curr_background_border_color: '',
	curr_general_text_color: '',
	curr_button_color: '',
	curr_button_border_color: '',
	curr_button_text_color: '',
	curr_webphone_width: 0,
	curr_webphone_height: 0,
	curr_call_button_color: '',
	curr_hangup_button_color: '',
	curr_brandname: '',
	curr_company_webpage: '',

	ApplyCustomSkin: function () // called on page loading from Common.js
	{
		SetBgColor();
	},
	
	HoverCalc: function (color, modifyValue) // vilagosabb szint csinal
	{
		try{
	//	var modifyValue = 15; // the value that every color (RGB) is modified when hover
		var origColor = color;
		var pos = color.indexOf('#');
		if (pos >= 0)
		{
			color = color.substring(pos+1);
		}
		
		if (color.length == 6)
		{
			var red = parseInt(color.substring(0,2), 16);
			var green = parseInt(color.substring(2,4), 16);
			var blue = parseInt(color.substring(4,6), 16);
			
			if ((red  + modifyValue) > 255)		{red = red - modifyValue;}	else if ((red  + modifyValue) < 0)		{red = red - modifyValue;}	else {red = red + modifyValue;}
			if ((green  + modifyValue) > 255)	{green = green - modifyValue;}	else if ((green  + modifyValue) < 0)	{green = green - modifyValue;}	else {green = green + modifyValue;}
			if ((blue  + modifyValue) > 255) 	{blue = blue - modifyValue;}	else if ((blue  + modifyValue) < 0)		{blue = blue - modifyValue;}	else {blue = blue + modifyValue;}
			
			red = red.toString(16);
			green = green.toString(16);
			blue = blue.toString(16);
			
			if (red.length < 2)		{red = '0' + red;}
			if (green.length < 2)	{green = '0' + green;}
			if (blue.length < 2)	{blue = '0' + blue;}
			
			color = '#' + red + green + blue;
		}else if (color.length == 3)
		{
			var red = parseInt(color.substring(0,1), 16);
			var green = parseInt(color.substring(1,2), 16);
			var blue = parseInt(color.substring(2,3), 16);
			
			if ((red  + modifyValue) > 255)		{red = red - modifyValue;}	else if ((red  + modifyValue) < 0)		{red = red - modifyValue;}	else {red = red + modifyValue;}
			if ((green  + modifyValue) > 255)	{green = green - modifyValue;}	else if ((green  + modifyValue) < 0)	{green = green - modifyValue;}	else {green = green + modifyValue;}
			if ((blue  + modifyValue) > 255) 	{blue = blue - modifyValue;}	else if ((blue  + modifyValue) < 0)		{blue = blue - modifyValue;}	else {blue = blue + modifyValue;}
			
			red = red.toString(16);
			green = green.toString(16);
			blue = blue.toString(16);
			
			if (red.length < 2)		{red = '0' + red;}
			if (green.length < 2)	{green = '0' + green;}
			if (blue.length < 2)	{blue = '0' + blue;}
			
			color = '#' + red + green + blue;
		}else
		{
			return origColor;
		}
		return color;
		}catch (e){}
		return '';
	}
}
}( ); // namespace END


// button mouseover and mouseout event management

$("div#btn_connect").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_0").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_1").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_2").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_3").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_4").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_5").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_6").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_7").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_8").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_9").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_10").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_11").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_button_color);
	}
});

$("div#btn_call").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_call_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_call_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_call_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_call_button_color);
	}
});

$("div#btn_hangup").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_hangup_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_hangup_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_hangup_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_hangup_button_color);
	}
});

$("div#btn_accept").mouseenter(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_call_button_color, 15));
	}else
	{
		$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_call_button_color, 15));
	}
}).mouseleave(function()
{
	if ($.browser.msie)
	{
		$(this).children().css("background-color",wp_layout.curr_call_button_color);
	}else
	{
		$(this).css("background-color",wp_layout.curr_call_button_color);
	}
});

if (document.getElementById("btn_callhangup") != null)
{
	$("div#btn_callhangup").mouseenter(function()
	{
		if ($.browser.msie)
		{
			if (wp_common.callhangup_isInCall)
			{
				$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_hangup_button_color, 15));
			}else
			{
				$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_call_button_color, 15));
			}
		}else
		{
			if (wp_common.callhangup_isInCall)
			{
				$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_hangup_button_color, 15));
			}else
			{
				$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_call_button_color, 15));
			}
		}
	}).mouseleave(function()
	{
		if ($.browser.msie)
		{
			if (wp_common.callhangup_isInCall)
			{
				$(this).children().css("background-color",wp_layout.curr_hangup_button_color);
			}else
			{
				$(this).children().css("background-color",wp_layout.curr_call_button_color);
			}
		}else
		{
			if (wp_common.callhangup_isInCall)
			{
				$(this).css("background-color",wp_layout.curr_hangup_button_color);
			}else
			{
				$(this).css("background-color",wp_layout.curr_call_button_color);
			}
		}
	});
}

if (document.getElementById("btn_chat") != null && document.getElementById("btn_transfer") != null && document.getElementById("btn_hold") != null && document.getElementById("btn_conference") != null)
{
	$("div#btn_chat").mouseenter(function()
	{
		if ($.browser.msie)
		{
			$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
		}else
		{
			$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
		}
	}).mouseleave(function()
	{
		if ($.browser.msie)
		{
			$(this).children().css("background-color",wp_layout.curr_button_color);
		}else
		{
			$(this).css("background-color",wp_layout.curr_button_color);
		}
	});
	
	$("div#btn_transfer").mouseenter(function()
	{
		if ($.browser.msie)
		{
			$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
		}else
		{
			$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
		}
	}).mouseleave(function()
	{
		if ($.browser.msie)
		{
			$(this).children().css("background-color",wp_layout.curr_button_color);
		}else
		{
			$(this).css("background-color",wp_layout.curr_button_color);
		}
	});
	
	$("div#btn_hold").mouseenter(function()
	{
		if ($.browser.msie)
		{
			$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
		}else
		{
			$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
		}
	}).mouseleave(function()
	{
		if ($.browser.msie)
		{
			$(this).children().css("background-color",wp_layout.curr_button_color);
		}else
		{
			$(this).css("background-color",wp_layout.curr_button_color);
		}
	});
	
	$("div#btn_conference").mouseenter(function()
	{
		if ($.browser.msie)
		{
			$(this).children().css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
		}else
		{
			$(this).css("background-color",wp_layout.HoverCalc(wp_layout.curr_button_color, 15));
		}
	}).mouseleave(function()
	{
		if ($.browser.msie)
		{
			$(this).children().css("background-color",wp_layout.curr_button_color);
		}else
		{
			$(this).css("background-color",wp_layout.curr_button_color);
		}
	});
}
