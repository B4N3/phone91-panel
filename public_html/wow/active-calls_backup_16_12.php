<?php 
include dirname(dirname(__FILE__)) . '/config.php';
if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}
?>
<html>
<body>
<script>
function secondsToHms(d) {
d = Number(d);
var h = Math.floor(d / 3600);
var m = Math.floor(d % 3600 / 60);
var s = Math.floor(d % 3600 % 60);
return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") + (s < 10 ? "0" : "") + s); 
}
function cutThisCall(uniqueId)
{
    $.ajax({

        url:"//"+window.location.hostname+"/controller/callController.php?action=cutCurrentCall&",
        type:"post",
        dataType:"jsonp",
        jsonpCallback:"callBack",
        data:{"uniqueId":uniqueId.toString()},
        
    })
}
function onLoadingNotif() {

    try {
        socketUri = "wss://voice.utteru.com/activeCall/123abc";
        wsocket =new WebSocket(socketUri);
        write('Connecting... (readyState ' + wsocket.readyState + ')');
        wsocket.onopen = function(msg) {
            //$('body').append("Connection successfully etablished<br>");
            write('Connection successfully opened (readyState ' + this.readyState + ')');
            $('#internetCont').hide();
        };
        wsocket.onmessage = function(msg) {


          // $('body').append(msg.data+"<br>");
            data = msg.data;

            var jsonData= $.parseJSON(data);
            var str='<div class="acBox">No Active Call</div>';

            if(jsonData.length!=0){
                str='';                
            }
           $.each(jsonData, function(i, obj) {
               
              // console.log(obj);
               
              //use obj.id and obj.name here, for example:
              //alert(obj.id_client);

              var callduration = obj.duration;
              //  console.log("reseller"+obj.resName);
              //  console.log("call duration"+(callduration/60).toFixed(2).split("."));
                callduration = secondsToHms(callduration);

				var bn = '';

				if (obj.batchName == null || obj.batchName == undefined){
					bn = '';
				}
				else {
					bn = '(' + obj.batchName+ ')';
				}

                str +='<div class="acBox">\
                        <div class="in">\
                            <h3><a href="http://voice.phone91.com/wow/index.php#!manage-client.php|transactional.php?clientId='+obj.id_client+'&tb=0;" >'+obj.userName+'</a></h3><h3>'+bn+'</h3>\
                            <p><span class="redThmClr">'+callduration+' </span> '+obj.call_type+'</p>\
                        <p><span >'+obj.route+' </span></p>\
                        </div>\
                        <div class="in clear">\
                            <div class="fl">\
                                <h3>'+obj.dialed_number+'</h3>\
                                <p>'+new Date(obj.call_start).toUTCString()+'</p>\
                            </div>\
                            <div class="fl">\
                                <p>'+obj.status+'</p>\
                            </div>\
                            <button type="button" onclick="cutThisCall(\''+obj.uniqueId+'\')" class="btn"><span></span>Stop</button>\
                        </div>\
                    </div>';

            });

                   $('#activeCallWrap').html(str);
                   

        };
        wsocket.onclose = function(msg) {
            if (this.readyState == 2)
                write('Closing... The connection is going throught the closing handshake (readyState ' + this.readyState + ')');
            else if (this.readyState == 3) {
                write('Connection closed... The connection has been closed or could not be opened (readyState ' + this.readyState + ')');
                $('#internetCont').show();
                
            }
            else
                write('Connection closed... (unhandled readyState ' + this.readyState + ')');
        };
        wsocket.onerror = function(event) {


            //terminal.innerHTML = '<li style="color: red;">'+event.data+'</li>'+terminal.innerHTML;
        };
    }
    catch (exception) {
        write(exception);
    }
};
onLoadingNotif();
function write(){}
</script>

<!--ac container-->
<div class="pd4" id="activeCallWrap">

<!--active call boxes-->

</div><!--//ac container-->

</body>
</html>

	
