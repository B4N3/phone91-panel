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
    
    


              
    function searchCallFailed(pageNo)
    {
        $.ajax({url:'/controller/adminController.php?action=searchCallFailedError',
            type:'POST',
            dataType:'json',
            data:{sDate:$.trim($('#fromDate').val()),
                  eDate:$.trim($('#toDate').val()),
		              q:$.trim($('#searchCallFailed').val()),
                  pageNo:pageNo},
            success:function(data){
                console.log(data);
                if(data.status == 1)
                {
                    var str = '';
                    $.each(data.callFailedData,function(key,value)
                    {
                        //console.log(value);
                        
                        if(value.clientType == '4')
                        {
                            var type = '2';
                        }
                        else 
                        {
                             var type = '1';
                        }
                        
                        str +='<tr class=""><td>'+data.chainIds.chainIds[value.chainId]+'</td><td>'+value.telNum+'</td>\
                                    <td class="">'+value.date+'</td>\
                                    <td class="alC blueThmCrl">'+value.reason+'</td></tr>';
                                   
                        $('#CallFailedErrorLog tbody').html(str);
                    });

                    callFailedpagination(data.pages,pageNo,'#pagination'); 

                
                     
                }
                else
                    $('#CallFailedErrorLog tbody').html('<tr><td>'+data.msg+'</td></tr>');
            
           }
           });
    }

searchCallFailed(1);

   function callFailedpagination(count,strt,divs)
{

    if(strt == undefined || strt == 0 || strt== "")
        strt=1;
    
    if(count == undefined || count == 0 || count== "")
        count = 1;
    
    
        //code for pagination
  if(count > 1 ){
            
              $('#pagination').show();
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
      rotate          : false,
      item_count_display      : true,           
      item_count_total : count,
      onChange                : function(page){
                            
                        
                          searchCallFailed(page);

                        }
                                    
    });
  }
  else
    $('#pagination').hide();
        
} 




</script>
<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
    <div class="dateSearch"> From:<input type="text" id="fromDate" name="dob" value="" class="">
	To:<input type="text" id="toDate" name="dob" value="" class="">
	<input type="text" id="searchCallFailed" name="search" value="" class="" placeholder='search' > 
	<button  class="" onclick="searchCallFailed(1)">Go</button>
    </div>
	
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



</script>    