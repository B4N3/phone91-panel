// JavaScript Document
function ele(id){ return document.getElementById(id);}
function ctcInit(data){
	document.body.insertAdjacentHTML('beforeend', data.content.html );
	var sel = ele('ctc-dpt');
	var dpts = data.content.depts;
	for (var key in dpts) {        
		var option = document.createElement('option');
		option.value = key;
		(typeof option.innerText === 'undefined')? option.textContent = dpts[key]: option.innerText = dpts[key];
		sel.appendChild(option);		
    }
}

function colourNameToHex(colour)
{
    var colours = {"aliceblue":"#f0f8ff","antiquewhite":"#faebd7","aqua":"#00ffff","aquamarine":"#7fffd4","azure":"#f0ffff",
    "beige":"#f5f5dc","bisque":"#ffe4c4","black":"#000000","blanchedalmond":"#ffebcd","blue":"#0000ff","blueviolet":"#8a2be2","brown":"#a52a2a","burlywood":"#deb887",
    "cadetblue":"#5f9ea0","chartreuse":"#7fff00","chocolate":"#d2691e","coral":"#ff7f50","cornflowerblue":"#6495ed","cornsilk":"#fff8dc","crimson":"#dc143c","cyan":"#00ffff",
    "darkblue":"#00008b","darkcyan":"#008b8b","darkgoldenrod":"#b8860b","darkgray":"#a9a9a9","darkgreen":"#006400","darkkhaki":"#bdb76b","darkmagenta":"#8b008b","darkolivegreen":"#556b2f",
    "darkorange":"#ff8c00","darkorchid":"#9932cc","darkred":"#8b0000","darksalmon":"#e9967a","darkseagreen":"#8fbc8f","darkslateblue":"#483d8b","darkslategray":"#2f4f4f","darkturquoise":"#00ced1",
    "darkviolet":"#9400d3","deeppink":"#ff1493","deepskyblue":"#00bfff","dimgray":"#696969","dodgerblue":"#1e90ff",
    "firebrick":"#b22222","floralwhite":"#fffaf0","forestgreen":"#228b22","fuchsia":"#ff00ff",
    "gainsboro":"#dcdcdc","ghostwhite":"#f8f8ff","gold":"#ffd700","goldenrod":"#daa520","gray":"#808080","green":"#008000","greenyellow":"#adff2f",
    "honeydew":"#f0fff0","hotpink":"#ff69b4",
    "indianred ":"#cd5c5c","indigo ":"#4b0082","ivory":"#fffff0","khaki":"#f0e68c",
    "lavender":"#e6e6fa","lavenderblush":"#fff0f5","lawngreen":"#7cfc00","lemonchiffon":"#fffacd","lightblue":"#add8e6","lightcoral":"#f08080","lightcyan":"#e0ffff","lightgoldenrodyellow":"#fafad2",
    "lightgrey":"#d3d3d3","lightgreen":"#90ee90","lightpink":"#ffb6c1","lightsalmon":"#ffa07a","lightseagreen":"#20b2aa","lightskyblue":"#87cefa","lightslategray":"#778899","lightsteelblue":"#b0c4de",
    "lightyellow":"#ffffe0","lime":"#00ff00","limegreen":"#32cd32","linen":"#faf0e6",
    "magenta":"#ff00ff","maroon":"#800000","mediumaquamarine":"#66cdaa","mediumblue":"#0000cd","mediumorchid":"#ba55d3","mediumpurple":"#9370d8","mediumseagreen":"#3cb371","mediumslateblue":"#7b68ee",
    "mediumspringgreen":"#00fa9a","mediumturquoise":"#48d1cc","mediumvioletred":"#c71585","midnightblue":"#191970","mintcream":"#f5fffa","mistyrose":"#ffe4e1","moccasin":"#ffe4b5",
    "navajowhite":"#ffdead","navy":"#000080",
    "oldlace":"#fdf5e6","olive":"#808000","olivedrab":"#6b8e23","orange":"#ffa500","orangered":"#ff4500","orchid":"#da70d6",
    "palegoldenrod":"#eee8aa","palegreen":"#98fb98","paleturquoise":"#afeeee","palevioletred":"#d87093","papayawhip":"#ffefd5","peachpuff":"#ffdab9","peru":"#cd853f","pink":"#ffc0cb","plum":"#dda0dd","powderblue":"#b0e0e6","purple":"#800080",
    "red":"#ff0000","rosybrown":"#bc8f8f","royalblue":"#4169e1",
    "saddlebrown":"#8b4513","salmon":"#fa8072","sandybrown":"#f4a460","seagreen":"#2e8b57","seashell":"#fff5ee","sienna":"#a0522d","silver":"#c0c0c0","skyblue":"#87ceeb","slateblue":"#6a5acd","slategray":"#708090","snow":"#fffafa","springgreen":"#00ff7f","steelblue":"#4682b4",
    "tan":"#d2b48c","teal":"#008080","thistle":"#d8bfd8","tomato":"#ff6347","turquoise":"#40e0d0",
    "violet":"#ee82ee",
    "wheat":"#f5deb3","white":"#ffffff","whitesmoke":"#f5f5f5",
    "yellow":"#ffff00","yellowgreen":"#9acd32"};

    if (typeof colours[colour.toLowerCase()] != 'undefined')
    	return colours[colour.toLowerCase()];

    return false;
}
function rgbToHex(r, g, b) {
    return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
}
function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)] : null;
}
function contrast(color){
	var rgb;
	if(color.indexOf("#") == -1){
		var hx = colourNameToHex(color);
		rgb = hexToRgb(hx);
	}
	else
	{	
		if (color.length == 4) {
			color = color.substr(1);
			color = color[0] + color[0] + color[1] + color[1] + color[2] + color[2];
		}
		rgb = hexToRgb(color);
	}
	
    var c = 'rgb('+rgb[0]+','+rgb[1]+','+rgb[2]+')';
    var o = Math.round(((parseInt(rgb[0]) * 299) + (parseInt(rgb[1]) * 587) + (parseInt(rgb[2]) * 114)) /1000);
    
    if(o > 125) {
        return 'black';
    }else{ 
        return 'white';
    }    
}

function callResponse(data){
	ele('ctc-submit-btn').style.display = 'block';
	ele('ctc-msg').style.display = 'block';
	ele('ctc-msg').innerHTML = data.message;
	ele('ctc-status').style.display = 'none';
}
function validNumber(number){
	var reg = /^\(?\+?[\d\(\-\s\)]+$/;
  	return reg.test(number);
}
function call(){
	var customerNum = ele('ctc-customerNum').value;
	if(validNumber(customerNum))
	{
		var dpt = ele('ctc-dpt').value;
		var script = document.createElement("script");
		script.src = 'https://voice.phone91.com/api/clickToCallDept?token='+ctcDefault.token+'&deptId='+dpt+'&customerNum='+customerNum+'&voiceJsonp=callResponse';
		document.body.appendChild(script);
		ele('ctc-submit-btn').style.display = 'none';
		ele('ctc-msg').style.display = 'none';
		ele('ctc-status').style.display = 'block';
	}
	else
	{
		ele('ctc-msg').style.display = 'block';
		ele('ctc-msg').innerHTML = 'Invalid mobile number please use a valid number';
	}
}

function ctcOpen(){
	var wid = ele('ctc-body');	
	(wid.style.display == 'block')? wid.style.display = 'none' : wid.style.display = 'block';
}

function getScript(callback){	
	var css = document.createElement("link");
	css.setAttribute("rel", "stylesheet")
  	css.setAttribute("type", "text/css")
  	css.setAttribute("href", 'https://voice.phone91.com/api/ctc.css')
    if (typeof css != "undefined")
		document.getElementsByTagName("head")[0].appendChild(css)
	
	var script = document.createElement("script");
    script.src = 'https://voice.phone91.com/api/getCTCPlugin?token='+ctcDefault.token+'&voiceJsonp=ctcInit';
    document.body.appendChild(script);
	script.onload = callback;	
}

(function(){
	getScript(function(){
		var ctcwrp = ele('ctc-wrp');
		var header = ele('ctc-header');				
		var ctcColor = ctcDefault.color;
		
		header.style.backgroundColor = ctcColor;
		ctcwrp.style.left = ctcDefault.left;
		ctcwrp.style.right = ctcDefault.right;
		
		if(ctcDefault.bottom == undefined && ctcDefault.top == undefined)
			ctcDefault.bottom = 0;
		ctcwrp.style.bottom = ctcDefault.bottom;
		
		ctcwrp.style.top = ctcDefault.top;
		
		
		
		if(ctcColor != undefined)
			header.style.color = contrast(ctcColor);
	});
})();