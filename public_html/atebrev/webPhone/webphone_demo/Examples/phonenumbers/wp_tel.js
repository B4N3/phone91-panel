/*
  Scan a page for phone number looking substrings and convert them into clickable links
*/


var wp_tel = function ( )
{
// private members and methods

function LocatePhonenumbers ()
{
	if(typeof String.prototype.trim !== 'function')
	{
		String.prototype.trim = function()
		{
			return this.replace(/^\s+|\s+$/g, ''); 
		}
	}

	var dom = document.getElementsByTagName("*");
	var elementCount = dom.length;

	//document.getElementById('loging').innerHTML += '<br> elementCount = ' + elementCount;
	
	for (var i = elementCount; i >= 0; i--)
	{
		if (typeof(dom[i]) === 'undefined' || dom[i] === null) { continue; }
		if ( (dom[i].nodeName).toUpperCase() === 'BODY' ) { break; }
		
		if ( (dom[i].nodeName).toUpperCase() === 'BR' && (dom[i].nodeName).toUpperCase() === 'IMG' (dom[i].nodeName).toUpperCase() === 'A' )
		{
			continue;
		}

		var content = dom[i].innerHTML;
		var done = new Array(); // already replaced numbers
		
		var j = 0;
		while (j < content.length)
		{
			j++;
			if (IsNumber(content.charAt(j)))
			{
				var replaceval = '';
				var isPhoneNumber = 0; // check if it's phone number based on subsucvent digits found
				
				if (j > 1 && ( content.charAt(j - 2) === '+' || content.charAt(j - 2) === '(') )
				{
					replaceval = replaceval + content.charAt(j - 2);
				}
				
				if (j > 0 && ( content.charAt(j - 1) === '+' || content.charAt(j - 1) === '(') )
				{
					replaceval = replaceval + content.charAt(j - 1);
				}

				var limit = j + 24;
				if (limit > content.length) { limit = content.length; }

				for (var k = j; k < limit; k++)
				{
					if ( IsChar(content.charAt(k)) ) { break; }
					replaceval = replaceval + content.charAt(k);
					isPhoneNumber++;
				}
				
				if (isPhoneNumber > 7) // if phone number found
				{
					replaceval = replaceval.trim();
//					alert('replaceval_1 = ' + replaceval);

					var wasAlreadyReplaced = false;
					if (done !== null)
					{
						for (var kk = 0; kk < done.length; kk++)
						{
							if (done[kk] === replaceval)
							{
								wasAlreadyReplaced = true;
								break;
							}
						}
					}
					
					
				// if not already replaced
					if (!wasAlreadyReplaced)
					{
						done[done.length] = replaceval;
						
						var linked = '<a href="#" title="Click to call" onclick="wp_tel.GoToWebphone(\'' + replaceval + '\');">' + replaceval + '</a>';
						
						content = ReplaceAll(content, replaceval, linked);
						
						j = j + linked.length - 1;
					}
				}
				else if (replaceval.length > 1)
				{
					j = j + replaceval.length - 1;
				}
			}
		}
		dom[i].innerHTML = content;
	}
}

function ReplaceAll(str, what, value)
{
	return str.replace(new RegExp(EscapeRegExp(what), 'g'), value);
}

function EscapeRegExp(str)
{
	return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
}

function IsChar(value) // !!! IMPORTANT used only for finding phone numbers
{
	if (typeof(value) === 'undefined' || value == null) { return false; }
	value = value.replace(/\s+/g, '');
	
	if (value == null || value.length < 1 || value === '-' || value === ')' || value === '(') { return false; }
	
	return isNaN(value);
}

function IsNumber (value)
{
	//return (typeof value === 'number' && isFinite(value));
	if (typeof(value) === 'undefined' || value == null) { return false; }
	value = value.replace(/\s+/g, '');
	
	if (value == null || value.length < 1) { return false; }
	
	return !isNaN(value);
}

// public interface
return {
	serveraddress: '',
	username: '',
	password: '',
	md5: '',
	skin: '',
	
	GoToWebphone: function (number)
	{
		// find out script src path
		var scripts = document.getElementsByTagName("script");
		var source = '';
		for (var i = 0; i < scripts.length; i++)
		{
			source = scripts[i].src;
			if (typeof(source) !== 'undefined' && source !== null && (source.toLowerCase()).indexOf('wp_tel.js') > 0 )
			{
				source = source.substring(0, source.indexOf('wp_tel.js'));
				break;
			}else
			{
				source = '';
			}
		}
		

		var url = source + wp_tel.skin + '/webphone.htm?serveraddress=' + wp_tel.serveraddress + '&username=' + wp_tel.username + '&password=' + wp_tel.password + '&md5=' + wp_tel.md5 + '&haveloginpage=false&callto=' + number + '&autocall=true';

		window.open(url ,"_blank", "scrollbars=yes, resizable=yes, top=100, left=200, width=400, height=500");

		return false; // will prevent browser from following the link
	},
	
	atpageload: function ()	//	function called on page load
	{
		LocatePhonenumbers();
	}
}
}( );

window.onload = wp_tel.atpageload;