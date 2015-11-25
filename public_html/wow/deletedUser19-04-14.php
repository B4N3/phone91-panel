<?php 
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."adminUpdationLog_class.php");

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

$log = new adminUpdationLog_class();
$logDetail = $log->getAdminLogDetail(1);
$logDetail = json_decode($logDetail,TRUE);


?>


<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="deletedLog" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">Updated By</th>
        <th width="10%" class="alC">Time</th>
        <th width="10%" class="alC">Old Status</th>
        <th width="10%" class="alC">Current Status</th>
<!--        <th width="10%" class="alC">Ratio</th>-->
         <th width="10%" class="alC">Client</th>
<!--        <th width="35% " class="alC noBorder">Description</th>-->
      </tr>
    </thead>
    
    <tbody>
      <?php
            foreach ($logDetail as $logData)
	  { ?>
		   	<tr class="">
                        <td><?php echo $logData['actionTakenBy'];?></td>
                        <td class="alC"><?php echo $logData['time'];?></td>
                        <td class="alC"><?php echo $logData['oldStatus'];?></td>
                        <td class="alC blueThmCrl"><?php echo $logData['currentStatus'];?></td>
<!--                         <td class="alC blueThmCrl"></td>-->
                        <td class="alC  blueThmCrl"><?php echo $logData['userName'];?></td>
<!--                        <td class="alC noBorder"><?php echo $logData['description'];?></td>-->
                        </tr>
				
				
          <?php }?>
      <tr class="zerobal">
        <td colspan="100%"></td>
      </tr>
      </tbody>
  </table>
</div>
<!--//Account Manager Edit Funds-->
<script>
    $(document).ready(function(){
       $("#deletedLog tbody tr:visible:even").addClass("even"); 
       $("#deletedLog tbody tr:visible:odd").addClass("odd");
       
    });
</script>    