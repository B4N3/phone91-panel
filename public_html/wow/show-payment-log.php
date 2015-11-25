<?php 
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."/db_class.php");

//if (!$funobj->login_validate() || !$funobj->check_admin()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}


 $dbobj = new db_class();

 $collectionName = 'paymentTracker';

  #check for contact no. is already inserted in table
 $condition = array('userId' => $userid);
 $result = $dbobj->mongo_find($collectionName, array())->sort(array('_id' => -1))->limit(500);
//foreach($result as $val)
//{ 
//    print_R($val['info']);
//}
//die('passion');
 //log errors
 if(!$result)
     trigger_error ('Problem While get details from payment tracker!!!');
?>


<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="editfundLog" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">Start Time</th>
        <th width="15%" class="">End Time</th>
        <th width="15%" class="">Difference of Time</th>
        <th width="15%" class="">Message</th>
        <th width="15%" class="">Client Name</th>
        <th width="25% noBorder">Description</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
            foreach ($result as $val)
            {
                foreach ($val['info'] as $entry)
                {   
                    ?>
		   	<tr class="">
					<td><?php echo date('d-M-Y h:i:s a',$entry['startTime']->sec);  ?></td>
					<td class=""><?php echo date('d-M-Y h:i:s a',$entry['endTime']->sec); ?></td>
					<td class=""><?php  echo $entry['Time']; ?></td>
					 <td class="alC blueThmCrl"><?php echo $entry['msg']; ?></td>
					<td class="alC  blueThmCrl"><?php echo $val['userid']; ?></td>
                                        <td class="noBorder"><?php echo json_encode($entry['description']); ?></td>
                        </tr>
				
          <?php } 
          
          }?>
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