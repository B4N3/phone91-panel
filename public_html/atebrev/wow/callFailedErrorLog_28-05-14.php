<?php 
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."/callLog_class.php");

//if (!$funobj->login_validate() || !$funobj->check_admin()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}

$logObj = new log_class();
$_REQUEST['sDate'] = date('11-03-2014 00:00:00');
$_REQUEST['eDate'] = date('Y-m-d 23:59:59');
$result = $logObj->callFailedErrorLog($_REQUEST);

$resArr = json_decode($result,TRUE);
     

?>

<script type="text/javascript">
    var globalTimeout = null;
//    $( "#fromDate,#toDate" ).datepicker({
//        
//        changeMonth: true,
//      changeYear: true
//    });
    $.datepicker.setDefaults({changeMonth: true,
            changeYear: true});
  $('#fromDate').datepicker({onSelect: function() {
            var date = $(this).datepicker('getDate');
            if (date) {
                  date.setDate(date.getDate());
            }
            $('#toDate').datepicker('option', 'minDate', date);
      }});
      $('#toDate').datepicker({onSelect: function() {
            var date = $(this).datepicker('getDate');
            if (date) {
                  date.setDate(date.getDate());
            }
            $('#fromDate').datepicker('option', 'maxDate', date);
      }});
    
    
    $( "#toDate" ).change(function() {
        searchCallFailed();
});

$('#searchCallFailed').keyup(function(){
 if(globalTimeout != null) 
       clearTimeout(globalTimeout);

 //use setTimeout to resist multiple requests  
              globalTimeout=setTimeout(function(){
                searchCallFailed();  
                  
              },600);
              
    
    
});

              
    function searchCallFailed()
    {
        $.ajax({url:'/controller/adminController.php?action=searchCallFailedError',
            type:'POST',
            dataType:'json',
            data:{sDate:$('#fromDate').val(),
                    eDate:$('#toDate').val(),
                q:$('#searchCallFailed').val()},
            success:function(data){
                console.log(data);
                if(data.status == 1)
                {
                    var str = '';
                    $.each(data.callFailedData,function(key,value)
                    {
                        
                        str +='<tr class=""><td>'+data.chainIds.chainIds[value.chainId]+'</td><td>'+value.telNum+'</td>\
                                    <td class="">'+value.date+'</td>\
                                    <td class="alC blueThmCrl">'+value.reason+'</td></tr>';
                                   
                        $('#CallFailedErrorLog tbody').html(str);
                    });
                
                     
                }
                else
                    $('#CallFailedErrorLog tbody').html('<tr><td>'+data.msg+'</td></tr>');
            
           }
           });
    }
</script>
<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
    <div class="dateSearch"> From:<input type="text" id="fromDate" name="dob" value="" class=""> To:<input type="text" id="toDate" name="dob" value="" class=""><input type="text" id="searchCallFailed" name="search" value="" class="" placeholder='search' > </div>
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="CallFailedErrorLog" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">Client Name</th>
        <th width="15%" class="">Number</th>
        <th width="15%" class="">Time</th>
        <th width="15%" class="">Reason</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
        if(isset($resArr['callFailedData']))
        {
            foreach ($resArr['callFailedData'] as $val)
            {
                $clientNameJson = $logObj->getUserOneDetail($val['chainId'],'chainId','userName');
                $clientName = json_decode($clientNameJson,TRUE);
                if(isset($clientName['userName']))
                    $client = $clientName['userName'];
                else
                    $client = '';
                
                    ?>
		   	<tr class="">
					<td><?php echo $client;  ?></td>
					<td class=""><?php echo $val['telNum']; ?></td>
					<td class=""><?php  echo $val['date']; ?></td>
					 <td class="alC blueThmCrl"><?php echo $val['reason']; ?></td>
					
                        </tr>
				
          <?php  
          
          }
        }
        else
        {?>
             <tr><td><?php echo $resArr['msg']; ?></td></tr>           
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