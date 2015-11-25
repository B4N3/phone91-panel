<?php 
include dirname(dirname(__FILE__)) . '/config.php';
//include_once(CLASS_DIR."adminUpdationLog_class.php");

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

if(isset($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;


//$log = new adminUpdationLog_class();
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 5)
    $action = 5;
else
    $action = 3;
//$logDetail = json_decode($logDetail,TRUE);


?>


<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="changeStatusLog" class="cmntbl  boxsize">
    <thead>
       <tr>
        <th width="15%">Updated By</th>
        <th width="10%" class="alC">Time</th>
        <th width="10%" class="alC">Old Status</th>
        <th width="10%" class="alC">Current Status</th>
<!--        <th width="10%" class="alC">Ratio</th>-->
         <th width="10%" class="alC">Client</th>
        <th width="35% " class="alC noBorder">Description</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
            //foreach ($logDetail as $logData)
	  //{ ?>
<!--		   	<tr class="">
                        <td><?php //echo $logData['actionTakenBy'];?></td>
                        <td class="alC"><?php //echo $logData['time'];?></td>
                        <td class="alC"><?php //echo $logData['oldStatus'];?></td>
                        <td class="alC blueThmCrl"><?php //echo $logData['currentStatus'];?></td>
                         <td class="alC blueThmCrl"></td>
                        <td class="alC  blueThmCrl"><?php //echo $logData['userName'];?></td>
                        <td class="alC noBorder"><?php //echo $logData['description'];?></td>
                        </tr>
				-->
          <?php //}?>
      <tr class="zerobal">
        <td colspan="100%"></td>
      </tr>
      </tbody>
  </table>
    <div id="pagination"></div>
</div>
<!--//Account Manager Edit Funds-->
<script>
    $(document).ready(function(){
       $("#changeStatusLog tbody tr:visible:even").addClass("even"); 
       $("#changeStatusLog tbody tr:visible:odd").addClass("odd");
       
    });
    
                 /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 8/04/2014
     * @param {int} action 5 or 3
     * @param {int} type 5 for change user status
     * @returns void
     */
    function getChangeUserStatusLog(pageNo,action,type)
    {
        $.ajax({
            url:'/controller/adminController.php?action=getChangeUserStatusLog',
            type:'GET',
            dataType:'json',
            data:{'pageNo':pageNo,
                  'actionType':action},
            success:function(data){
                
                var str = '';
                $.each(data,function(index,value){
                   
                   if(value.actionTakenBy != undefined) 
                    str+="<tr class=''>\
					<td>"+value.actionTakenBy+"</td>\
					<td class='alC'>"+value.time+"</td>\
					<td class='alC'>"+value.oldStatus+"</td>\
					 <td class='alC blueThmCrl'>"+value.currentStatus+"</td>\
					<td class='alC  blueThmCrl'>"+value.userName+"</td>\
                                        <td class='noBorder'>"+value.description+"</td>\
                        </tr>";
                    
                });
              $('#changeStatusLog tbody').html(str); 
              $("#changeStatusLog tbody tr:visible:even").addClass("even"); 
              $("#changeStatusLog tbody tr:visible:odd").addClass("odd");
            
            
                pagination(data.pages,pageNo,'#pagination',type,action);
            }
            
        });
        
        
    }

   getChangeUserStatusLog(<?php echo $pageNo; ?>,<?php echo $action; ?>,5); 

</script>    