//---------------------------------AJAX LIBRARY FUNCTINS------------------------------------------//
function get_xmlhttp_object() {
    var http;
    try {
      http = new XMLHttpRequest;
        get_http_object = function() {
          return new XMLHttpRequest;
        };
    }
    catch(e) {
      var msxml = [
        'MSXML2.XMLHTTP.3.0',
        'MSXML2.XMLHTTP',
        'Microsoft.XMLHTTP'
      ];
      for (var i=0, len = msxml.length; i < len; ++i) {
        try {
          http = new ActiveXObject(msxml[i]);
          get_http_object = function() {
            return new ActiveXObject(msxml[i]);
          };
          break;
        }
        catch(e) {}
      }
    }
    return http;
  };

function load_page(url,divid)
{
var xmlhttp=get_xmlhttp_object();
if(xmlhttp.readyState==0)
xmlhttp.open("GET",url,true);
else
alert("Script Execution Error # 1");
xmlhttp.onreadystatechange = function() {//Function Start
if((xmlhttp.readyState==1)||(xmlhttp.readyState==2)||(xmlhttp.readyState==3)) //This is when the Request is in Progress. IF # 1
{
document.getElementById("loading").style.visibility='visible';
}//End IF # 1
if(xmlhttp.readyState==4) // This is when request has been completely executed IF # 2
{
document.getElementById(divid).src=url;
try {
document.getElementById(divid).onload=function() {
try { 
url_id=url.split(".");
//alert(url_id[0]);
document.body.id='body_'+url_id[0]; } catch(e) { alert(e); }
height=parseInt(document.getElementById(divid).contentWindow.document.body.scrollHeight);
document.getElementById(divid).height=height + "px"; 
document.getElementById("loading").style.visibility='hidden';
	};
} catch(e) { }
} //End IF # 2
};//End Function
xmlhttp.send(null);
}

//-------------------------------------AJAX LIBRARY FUNCTIONS---------------------------------
/*
document.getElementById(divid).contentWindow.document.body.onload = function {
alert("hi");
} 

document.getElementById(divid).height=height + "px"; 
*/
//Testing For Gmail Kind of Code Over Here