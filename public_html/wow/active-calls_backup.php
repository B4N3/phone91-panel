<?php 
include dirname(dirname(__FILE__)) . '/config.php';
if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}
?>
<html>
<body>
<script>
function onLoadingNotif() {

    try {
        socketUri = "wss://voice.utteru.com/activeCall/123abc";
        wsocket =new WebSocket(socketUri);
        write('Connecting... (readyState ' + wsocket.readyState + ')');
        wsocket.onopen = function(msg) {
            $('body').append("Connection successfully etablished<br>");
            write('Connection successfully opened (readyState ' + this.readyState + ')');
            $('#internetCont').hide();
        };
        wsocket.onmessage = function(msg) {
          $('body').append(msg.data+"<br>");
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

<script src="js/socketio.js"></script>
<!--ac container-->
<div class="pd4" id="activeCallWrap">

<!--active call boxes-->



</div><!--//ac container-->
<script type="text/javascript" src="/public/adminActiveCall.js"></script>

</body>
</html>

	
