<?php 
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."adminUpdationLog_class.php");

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}


$log = new adminUpdationLog_class();
$logDetail = $log->getEditFundLog();
$logDetail = json_decode($logDetail,TRUE);


?>


<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="editfundLog" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">Updated By</th>
        <th width="10%" class="alC">Time</th>
        <th width="10%" class="alC">Fund</th>
        <th width="10%" class="alC">Credit Fund</th>
        <th width="10%" class="alC">Client Name</th>
        <th width="45% noBorder">Description</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
            foreach ($logDetail as $logData)
	  { ?>
		   	<tr class="">
					<td><?php echo $logData['fromUserName']; ?></td>
					<td class="alC"><?php echo $logData['date']; ?></td>
					<td class="alC"><?php echo $logData['currentBalance']; ?></td>
					 <td class="alC blueThmCrl"><?php echo $logData['amount']; ?></td>
					<td class="alC  blueThmCrl"><?php echo $logData['toUserName']; ?></td>
					<td class="noBorder"><?php echo $logData['description']; ?></td>
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
       $("#editfundLog tbody tr:visible:even").addClass("even"); 
       $("#editfundLog tbody tr:visible:odd").addClass("odd");
       
    });
</script>    