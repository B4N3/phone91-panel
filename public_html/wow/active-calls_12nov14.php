<?php 
include dirname(dirname(__FILE__)) . '/config.php';
if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}
?>
<script src="js/socketio.js"></script>
<!--ac container-->
<div class="pd4" id="activeCallWrap">

<!--active call boxes-->
	
   
      
</div><!--//ac container-->
<script type="text/javascript" src="/public/adminActiveCall.js"></script>
