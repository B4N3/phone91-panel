<?php
/**
 * @lase updated by Ankit Patidar <ankitpatidar@hostnsoft.com> on 29/10/2013
 * @Description file contain code to show bandWidth limit in account manager log
 */

//include required files
include dirname(dirname(__FILE__)) . '/config.php';
//include_once(CLASS_DIR."adminUpdationLog_class.php");

if(isset($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;

//check login validate
if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

//create object to get log details
//$log = new adminUpdationLog_class();
//$logDetail = $log->getAdminLogDetail(2);
//
////unset object
//unset($log);

//get array
//$logDetail = json_decode($logDetail,TRUE);

?>
<script src="../js/base64.js"></script>

<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="changeTeriff" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">Updated By</th>
        <th width="10%" class="alC">Time</th>
        <th width="10%" class="alC">Old Tariff</th>
        <th width="15%" class="alC">Current Tariff</th>
<!--        <th width="10%" class="alC">Ratio</th>-->
        <th width="10%" class="alC">Client</th>
        <th width="40% noBorder">Description</th>
      </tr>
    </thead>
    
    <tbody>
     <?php
      //foreach($logDetail as $logData){
         
       ?>
		   
<!--            <tr class=''>
                <td><?php //echo $logData['actionTakenBy'];?></td>
                    <td class="alC"><?php //echo $logData['time'];?></td>
                    <td class="alC"><?php //echo $logData['oldStatus'];?></td>
                    <td class="alC blueThmCrl"><?php //echo $logData['currentStatus'];?></td>
                    <td class="alC blueThmCrl"><?php //echo $logData['userName'];?></td>
                    <td class="alC blueThmCrl"><?php //echo $logData['description'];?></td>
					<td class="noBorder">&nbsp;</td>
             </tr>-->
				
      <?php //} 
   
      ?>
      <tr class="zerobal">
        <td colspan="100%"></td>
      </tr>
    </tbody>
  </table>
      <div id="pagination"></div>
</div>
<!--//Account Manager Edit Funds-->

<script type ="text/javascript">
 $(document).ready(function(){
     
     //code to give different color to even odd rows
       $("#changeTeriff tbody tr:visible:even").addClass("even"); 
       $("#changeTeriff tbody tr:visible:odd").addClass("odd");
       
    });
    
         /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 8/04/2014
     * @param {int} type 3 for change teriff
     * @returns void
     */
    function getChangeTeriffLog(pageNo,type)
    {
        $.ajax({
            url:'/controller/adminController.php?action=getChangeTeriffLog',
            type:'GET',
            dataType:'json',
            data:{'pageNo':pageNo},
            success:function(data){
                
                var str = '';
                $.each(data,function(index,value){
                   
                   if(value.actionTakenBy != undefined) 
                    str+="<tr class=''>\
					<td>"+value.actionTakenBy+"</td>\
					<td class='alC'>"+value.time+"</td>\
					<td class='alC'>"+value.oldStatus+"</td>\
					 <td class='alC blueThmCrl'>"+value.currentStatus+"</td>\
					<td class='alC  blueThmCrl'><a href='http://voice.phone91.com/wow/index.php#!manage-client.php?q="+$.base64.encode(value.userName)+"&qs="+$.base64.encode(value.userId)+"|transactional.php?clientId="+value.userId+"&tb=0' >"+value.userName+"</a></td>\
                                        <td class='noBorder'>"+value.description+"</td>\
                        </tr>";
                    
                });
              $('#changeTeriff tbody').html(str); 
              $("#changeTeriff tbody tr:visible:even").addClass("even"); 
              $("#changeTeriff tbody tr:visible:odd").addClass("odd");
            
            
                pagination(data.pages,pageNo,'#pagination',type);
            }
            
        });
        
        
    }
    
    getChangeTeriffLog(<?php echo $pageNo; ?>,3);
</script>