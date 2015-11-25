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
//$logDetail = $log->getEditFundLog();
//$logDetail = json_decode($logDetail,TRUE);


?>

<script src="../js/base64.js"></script>
<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="editfundLog" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">Updated By</th>
        <th width="10%" class="alC">Time</th>
        <th width="10%" class="alC">Fund (Talktime)</th>
        <th width="10%" class="alC">Credit Fund (Talktime)</th>
        <th width="10%" class="alC">Client Name</th>
        <th width="45% noBorder">Description</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
            //foreach ($logDetail as $logData)
	  { ?>
<!--		   	<tr class="">
					<td><?php //echo htmlentities($logData['fromUserName']); ?></td>
					<td class="alC"><?php //echo $logData['date']; ?></td>
					<td class="alC"><?php //echo $logData['currentBalance']; ?></td>
					 <td class="alC blueThmCrl"><?php //echo $logData['amount']; ?></td>
					<td class="alC  blueThmCrl"><?php //echo htmlentities($logData['toUserName']); ?></td>
                                        <td class="noBorder"><?php //echo htmlentities($logData['description']); ?></td>
                        </tr>-->
				
          <?php }?>
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
       $("#editfundLog tbody tr:visible:even").addClass("even"); 
       $("#editfundLog tbody tr:visible:odd").addClass("odd");
       
    });
    
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 8/04/2014
     * @param {int} type 0 for edit fund
     * @returns void
     */
    function getEditFundLog(pageNo,type)
    {
        $.ajax({
            url:'/controller/adminController.php?action=getEditFundLog',
            type:'POST',
            dataType:'json',
            data:{'pageNo':pageNo},
            success:function(data)
            {
                
                //  console.log($.parseJSON(data.currencyId));
                


                var str = '';
                $.each(data,function(index,value){
                   
                   //console.log(value);
                 
                   var userId = value.toUser;
                   
                   
                   if(value.fromUserName != undefined) 
                   {
                        str+="<tr class=''>\
                                            <td>"+value.fromUserName+"</td>\
                                            <td class='alC'>"+value.date+"</td>\
                                            <td class='alC'>"+value.currentBalance+"</td>\
                                             <td class='alC blueThmCrl'>"+value.amount+"  " +data.currencyId[userId] +"</td>\
                                            <td class='alC  blueThmCrl'><a href='http://voice.phone91.com/wow/index.php#!manage-client.php?q="+$.base64.encode(value.toUserName)+"&qs="+$.base64.encode(value.toUser)+"|transactional.php?clientId="+value.toUser+"&tb=0' >"+value.toUserName+"</a></td>\
                                            <td class='noBorder'>"+value.description+"</td>\
                            </tr>";
                                                
                    }
                    
                });
              $('#editfundLog tbody').html(str); 
              $("#editfundLog tbody tr:visible:even").addClass("even"); 
              $("#editfundLog tbody tr:visible:odd").addClass("odd");
            
            
                pagination(data.pages,pageNo,'#pagination',type);
            }
            
        });
        
        
    }
    
    getEditFundLog(<?php echo $pageNo; ?>,0);
</script>    