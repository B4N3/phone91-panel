<?php

include_once('config.php');

include_once("classes/callShop_class.php");

$obj = new callShop_class();

$param['shopId'] = base64_decode($_REQUEST['shopId']);
$param['systemId'] = base64_decode($_REQUEST['systemId']);

$table = $obj->getCallShopRecord($param);
?>

<!--  Edit Call Shop-->
<div class="editCallShop">
    
    <div><input type="button" name="downloadAll" id="downloadAll" value="Download All" onClick="downloadFilesAdazip('1')" /></div>
    
    <input type="text" name="fromDate" id="fromDate" />

    <input type="button" name="searchDate" id="searchDate" value="Search" onClick="searchByDate()" />
    
    <input type="button" name="downloadFiles" id="downloadFiles" value="download" onClick="downloadFilesAdazip('2')" />

    <input type="hidden" name="selectedDate" id="selectedDate" value="<?php echo date('Y-m-d'); ?>" />
    
    <table class="cmntbl boxsize cf" width="100%" id="callRecordTable">
            <?php echo $table; ?>
    </table>

</div>

<!--//Edit Call Shop-->
<script type="text/javascript">
    
    
    $(function() {
         $("#fromDate").datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate:0,
            dateFormat:"yy-mm-dd"
    });
});


function searchByDate()
{
    var fromDate = $("#fromDate").val();
     
    $('#selectedDate').val(fromDate);
    
    var shopId = "<?php echo $param['shopId'];  ?>";
    var systemId = "<?php echo $param['systemId'];  ?>";
    
    $.ajax({
            url:"/controller/callShopController.php",
            data:{"call":"getCallShopRecord","fromDate":fromDate , "shopId":shopId ,"systemId" :systemId },
            type:"post",
            dataType:"text",
            success:function (data)
            {
                if(data)
                {
                    $('#callRecordTable').html(data);
                }
                
            }
    });
}


function downloadFilesAdazip(ths)
{
    
    
   var fromDate =  $('#selectedDate').val();
   
   var shopId = "<?php echo $param['shopId'];  ?>";
   var systemId = "<?php echo $param['systemId'];  ?>";
     
      $.ajax({
            url:"action_layer.php?action=generateZipFile",
            data:{"date":fromDate,"shopId":shopId,"systemId":systemId ,'type':ths},
            type:"post",
            dataType:"text",
            success:function (data)
            {
                data = jQuery.parseJSON(data);
                show_message(data.message,data.msgStatus);
            }
    });
   
   
}


</script>
 