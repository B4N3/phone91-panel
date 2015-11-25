<?php
/**
 * @lase updated by Ankit Patidar <ankitpatidar@hostnsoft.com> on 29/10/2013
 * @Description file contain code to show bandWidth limit in account manager log
 */

//include required files
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."adminUpdationLog_class.php");

//check login validate
if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

//create object to get log details
$log = new adminUpdationLog_class();
$logDetail = $log->getAdminLogDetail(7);

//unset object
unset($log);

//get array
$logDetail = json_decode($logDetail,TRUE);

?>

<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="bandWidthTable" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">Updated By</th>
        <th width="10%" class="alC">Time</th>
        <th width="10%" class="alC">Old Bandwidth</th>
        <th width="15%" class="alC">Current Bandwidth</th>
<!--        <th width="10%" class="alC">Ratio</th>-->
        <th width="10%" class="alC">Client</th>
<!--        <th width="40% noBorder">Description</th>-->
      </tr>
    </thead>
    
    <tbody>
     <?php
      foreach($logDetail as $logData){
         
       ?>
		   
				<tr class=''>
					<td><?php echo $logData['actionTakenBy'];?></td>
					<td class="alC"><?php echo $logData['time'];?></td>
					<td class="alC"><?php echo $logData['oldStatus'];?></td>
					<td class="alC blueThmCrl"><?php echo $logData['currentStatus'];?></td>
					<td class="alC blueThmCrl"><?php echo $logData['userName'];?></td>
<!--					<td class="alC blueThmCrl"><?php echo $logData['description'];?></td>-->
<!--					<td class="noBorder">&nbsp;</td>-->
				 </tr>
				
      <?php } ?>
      <tr class="zerobal">
        <td colspan="100%"></td>
      </tr>
    </tbody>
  </table>
</div>
<!--//Account Manager Edit Funds-->

<script type ="text/javascript">
 $(document).ready(function(){
     
     //code to give different color to even odd rows
       $("#bandWidthTable tbody tr:visible:even").addClass("even"); 
       $("#bandWidthTable tbody tr:visible:odd").addClass("odd");
       
    });
</script>