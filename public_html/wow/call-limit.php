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
//$logDetail = $log->getAdminLogDetail(6);
//$logDetail = json_decode($logDetail,TRUE);

?>
<script src="../js/base64.js"></script>
<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="callLimitTable" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">Updated By</th>
        <th width="10%" class="alC">Time</th>
        <th width="10%" class="alC">Old Call Limit</th>
        <th width="10%" class="alC">Current Call Limit</th>
<!--        <th width="10%" class="alC">Ratio</th>-->
         <th width="10%" class="alC">Client</th>
        <th width="35% " class="alC noBorder">Description</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
     // foreach($logDetail as $logData){
         
       ?>
		    
<!--                <tr class="">
                        <td><?php // echo $logData['actionTakenBy'];?></td>
                        <td class="alC"><?php //echo $logData['time'];?></td>
                        <td class="alC"><?php //echo $logData['oldStatus'];?></td>
                        <td class="alC blueThmCrl"><?php //echo $logData['currentStatus'];?></td>
                         <td class="alC blueThmCrl"></td>
                        <td class="alC  blueThmCrl"><?php //echo $logData['userName'];?></td>
                        <td class="alC noBorder"><?php //echo $logData['description'];?></td>
                 </tr>-->
				
      <?php //} ?>
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
       $("#callLimitTable tbody tr:visible:even").addClass("even"); 
       $("#callLimitTable tbody tr:visible:odd").addClass("odd");
       
    });
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 8/04/2014
     * @param {int} type 1 for call limit
     * @returns void
     */
    function getcallLimitLog(pageNo,type)
    {
        $.ajax({
            url:'/controller/adminController.php?action=getCallLimitLog',
            type:'POST',
            dataType:'json',
            data:{'pageNo':pageNo},
            success:function(data){
                
                var str = '';
                $.each(data,function(index,value){
                   
                   console.log(value);
                   
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
              $('#callLimitTable tbody').html(str); 
              $("#callLimitTable tbody tr:visible:even").addClass("even"); 
              $("#callLimitTable tbody tr:visible:odd").addClass("odd");
            
            
                pagination(data.pages,pageNo,'#pagination',type);
            }
            
        });
        
        
    }
    
    getcallLimitLog(<?php echo $pageNo; ?>,1);
    
    
</script>    