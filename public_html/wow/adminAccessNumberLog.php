<?php
include dirname(dirname(__FILE__)) . '/config.php';

//get page number
if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;

$sDate = (isset($_REQUEST['sDate']) && $_REQUEST['sDate'] != '')?date('Y-m-d 00:00:00',strtotime($_REQUEST['sDate'])):'';
$eDate = (isset($_REQUEST['eDate']) && $_REQUEST['eDate'] != '')?date('Y-m-d 23:59:59',strtotime($_REQUEST['eDate'])):'';

$qString = (isset($_REQUEST['q']) && $_REQUEST['q']) != ''?trim($_REQUEST['q']):'';

?>
<style>
    .cmntbl tbody tr.zerobal.odd{ height:3px !important;}
</style>

<div id="resTrLogWrap" class="commLeftList">
    
    <div class="dateSearch"> From:<input type="text" id="fromDate" name="dob" value="<?php echo $sDate; ?>" class="">
        To:<input type="text" id="toDate" name="dob" value="<?php echo $eDate; ?>" class="">
        <input type="text" id="searchCallFailed" name="search" value="<?php echo $qString; ?>" class="" placeholder='search' > 
	<button  class="" onclick="getAccessNumberDetail('<?php echo $pageNo; ?>')">Go</button>
    </div>
    
    <div class="clear inner"> 
    
        <div id="resultContainer" class="clear flip-scroll box-"> 
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="cmntbl boxsize" id="accessNumberLogTable">
                    <thead>
                      	<tr>
                            <th width="10%">Access Number</th>
                            <th width="8%">Caller Id</th>
                            <th width="5%">Unique Id</th>
                            <th width="5%">Step</th>
                            <th width="25%">User Information</th>
                            <th width="8%">Date</th>
                            
                      	</tr>
                    </thead>
                    <tbody id="tableBody">
                     
                    </tbody>
            </table>
            
            
        </div>
           <div id="pagination" class="mrT1"></div>
    </div>
    
    
</div>
<script>

getAccessNumberDetail("<?php echo $pageNo; ?>");

function getAccessNumberDetail(pageNo)
{
        $.ajax({
                url:" http://voice.phone91.com/controller/adminController.php?action=getAccessNumberLog",
                type:"POST",
                dataType:"JSON",data:{  sDate:$.trim($('#fromDate').val()),
                                        eDate:$.trim($('#toDate').val()),
                                        q:$.trim($('#searchCallFailed').val()),
                                        pageNo:pageNo},
                success:function(msg)
                { 
                    console.log(msg.data.length);
                    
                    if(msg.data.length > 0)
                    {
                        var response = accessNumberLogDesign(msg.data);
                        
                       // console.log(response);
                        
                        $('#tableBody').html(response);
                        
                        $("#accessNumberLogTable tbody tr:visible:even").addClass("even"); 
$("#accessNumberLogTable tbody tr:visible:odd").addClass("odd");

                        paginationNew( msg.totalCount  ,<?php echo $pageNo; ?>,'#pagination');
                        
                    }
                }
        }); 
}


function accessNumberLogDesign(text)
{
    var str = ''; 
    window.uniqueId = 0;
    
    $.each(text,function(key,value)
    { 
        if(window.uniqueId == 0 )
        {
            window.uniqueId = value.uniqueId;
        }
        
        
        if(window.uniqueId == value.uniqueId )
        {
            var displayLine = "";
        }
        else
        {
           window.uniqueId = value.uniqueId;
           str+="<tr class='zerobal'><td colspan='100%'></td></tr>";
           
        }
        
        
        
        console.log(value.uniqueId);
        
        str+="<tr><td>"+value.accessNumber+"</td><td>"+value.callerId+"</td><td>"+value.uniqueId+"</td><td>"+value.step+"</td><td>"+value.userInfo+"</td><td>"+value.date+"</td></tr>";
        
    });
    
    //console.log("Nidhi" +str);
    
    
    return str;
}


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
    
function paginationNew(count,strt,divs,clientId)
{
    if(strt == undefined || strt == 0 || strt== "")
        strt=1;
    
    if(count == undefined || count == 0 || count== "")
        count = 1;
        //code for pagination
	if(count > 1 ){
		$(divs).paginate({
			count       : count,
			start       : strt,
			display     : 10,
			border : false,
			text_color: '#000',
			background_color: '#ddd',
			text_hover_color: '#fff',
			background_hover_color: '#333',
			images                  : false,
			mouse                   : 'press',
			page_choice_display     : true,
			show_first              : true,
			show_last               : true,
			rotate					: false,
			item_count_display      : true,						
			item_count_total : count,
			onChange                : function(page){
                            
                               window.location.href= window.location.href.split('?')[0]+'?pageNo='+page+'&sDate='+$.trim($('#fromDate').val())+"&eDate="+$.trim($('#toDate').val())+"&q="+$.trim($('#searchCallFailed').val());
                        }
                                    
		});
	}
}

</script>