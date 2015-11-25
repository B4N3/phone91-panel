<div>
    <form id="searchForm" name="searchForm" method="post">
        <input type="radio" name="type" value="1" onclick="toogleOption('keyword')"/><label>User Name</label>
        <input type="text" id="keyword" class="elem" name="keyword" value="" style="display: none;"/>
        <input type="radio" name="type" value="2" onclick="toogleOption('keyword')"/><label>Number</label>
        <input type="radio" name="type" value="3" onclick="toogleOption('selRoute')" /><label>Route</label>
        <select name="selRoute" class="elem" id="selRoute" style="display:none;">
        </select>
        <input type="radio" name="type" value="4" onclick="toogleOption('selStatus')"/><label>Status</label>
        <select name="selStatus" class="elem" id="selStatus" style="display:none;">
            <option>Select Status</option>
            <option value="FAILED">Failed</option>
            <option value="CONGESTION">Congestion</option>
            <option value="CANCEL">Cancel</option>
            <option value="CONGESTION">Congestion</option>
            <option value="WAITING">Waiting</option>
            <option value="NOANSWER">No Answer</option>
            <option value="RINGTIMEOUT">Ring TimeOut</option>
            <option value="CHANUNAVAIL">Chanel Un Avail</option>
            <option value="ANSWER">Answer</option>
            <option value="BUSY">Busy</option>
            <option value="HANGUP">HangUp</option>
            <option value="ANSWERED">Answered</option>
        </select>

        <div class="dateSearch"> From:<input type="text" id="fromDate" name="fromDate" value="" class=""> To:<input type="text" id="toDate" name="toDate" value="" class=""/></div>

        <input type="button" value="Go" onclick="submitForm()"/> 
        
        <a class="btn btn-medium btn-primary clear alC" target="_blank" onclick="exprotCallLog(this,'CSV')" title="Export CSV">Export CSV</a>
        <a class="btn btn-medium btn-primary clear alC" target="_blank" onclick="exprotCallLog(this,'XLS')" title="Export xlsx">Export xlsx</a>
</div>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize" id="editfundLog">
    <thead>
      <tr>
        <th width="15%">User Name</th>
        <th width="10%" class="alC">Source Number</th>
        <th width="10%" class="alC">Destination Number</th>
        <th width="10%" class="alC">Call Start Time</th>
        <th width="10%" class="alC">Call End Time</th>
        <th width="10% noBorder" >route</th>
        <th width="10% noBorder">Status</th>
        <th width="10% noBorder">HangUp Reason</th>
        <th width="10% noBorder">Call Type</th>
        <th width="10% noBorder">Balance Deducted</th>
		<th width="10% noBorder">Access Number</th>
      </tr>
    </thead>
    
    <tbody id="tbody">
        
    </tbody>
</table>
<div id="pagination"></div>


<script type="text/javascript">
    $( "#fromDate,#toDate" ).datepicker({
     changeMonth: true,
     changeYear: true
      });

      $.datepicker.setDefaults({changeMonth: true,
            changeYear: true});
      $('#fromDate').datepicker({onSelect: function() {
            var date = $(this).datepicker('getDate');
            if (date) {
                  date.setDate(date.getDate() );
            }
            $('#toDate').datepicker('option', 'minDate', date);
      }});


      $('#toDate').datepicker({onSelect: function() {
            var date = $(this).datepicker('getDate');
            if (date) {
                  date.setDate(date.getDate() );
            }
            $('#fromDate').datepicker('option', 'maxDate', date);
      }});

 



    function toogleOption(id)
    { 
        console.log(id);
        $('.elem').hide();
        $('#'+id).show();
    }
    function renderResponseCallDetails(response)
    {
         if(response == null || typeof response === undefined || response == "" || response == false)
                   return false;
               
               if(response.status == "error")
               {
                   show_message(response.msg,response.status);
                   return false;
               }
        var str = "";
               var i = 1;
               $.each(response,function(key,value){
                   if((i%2) == 0 )
                       var clss = 'even';
                   else
                       var clss = 'odd';
                   
                   if(value['balance_deduct'] == null || value['balance_deduct'] == "")
                   {
                       value['balance_deduct'] = '0';
                   }
                   
                   if(value['userName'] != undefined && value['userName'] != null)
                     str += "<tr class='"+clss+"'>\
                            <td>"+value['userName']+"</td>\
                            <td class='alC'>"+value['caller_id']+"</td>\
                            <td class='alC'>"+value['called_number']+"</td>\
                            <td class='alC blueThmCrl'>"+value['call_start']+"</td>\
                            <td class='alC blueThmCrl'>"+value['call_end']+"</td>\
                            <td class='alC '>"+value['route']+"</td>\
                            <td class='alC '>"+value['status']+"</td>\
                            <td class='alC '>"+value['hangup_reason']+"</td>\
                            <td class='alC '>"+value['call_type']+"</td>\
                            <td class='alC '>"+value['balance_deduct']+" " +value['currency']+"</td>\
							<td class='alC '>"+value['didNumber']+"</td>\
                            </tr>";
               })
              return str;
    }

    function submitForm(pageNo)
    {
       var data = $('#searchForm').serialize();
       data = "call=getCallDetailsAdmin&"+data;
       $.ajax({
           url:"/controller/userCallLog.php",
           type:"post",
           dataType:"JSON",
           data:data+'&pageNo='+pageNo,
           success:function(response)
           {
              
               if(response == null || typeof response === undefined || response == "" || response == false)
                   return false;
               var str = renderResponseCallDetails(response);
               $('#tbody').html(str); 
               callDetailsPagination(response.pages,pageNo,'#pagination');
           }
       })
       
    }
    
    function exprotCallLog(ths,type){
    var data = $('#searchForm').serialize();
    var exportdata = type;
    
       data = "call=getCallDetailsAdmin&"+data;
    var href= "/controller/userCallLog.php?"+data+'&exportdata='+exportdata;
    
    $(ths).attr("href",href);
    $(ths).click();
    
//    $.ajax({
//           url:"/controller/userCallLog.php",
//           type:"post",
//           dataType:"JSON",
//           data:data+'&exportdata='+exportdata,
//           success:function(response)
//           {
//                             
//           }
//       })
    }
    
    
    function getRouteList()
    {
        
        $.ajax({
           url:"/controller/routeController.php",
           type:"post",
           dataType:"JSON",
           data:{"action":"getRouteList"},
           success:function(response)
           {
              
               if(response == null || typeof response === undefined || response == "" || response == false)
                   return false;
               var str = "<option>Select Route</option>";
               $.each(response,function(key,value){
                   str += "<option value='"+value+"'>"+value+"</option>";
               })
               $('#selRoute').html(str);
               
           }
        });
    }
    
   function getAllDetails()
   {
       $.ajax({
           url:"/controller/userCallLog.php",
           type:"post",
           dataType:"JSON",
           data:{"call":"getCallDetailsAdmin"},
           success:function(response)
           {
               console.log(response);
               
                if(response == null || typeof response === undefined || response == "" || response == false)
                   return false;
               var str = renderResponseCallDetails(response);
               $('#tbody').html(str); 
           }
       })
   }
   $(document).ready(function(){
       getRouteList();
       getAllDetails();
   })


    function callDetailsPagination(count,strt,divs)
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
                            
                        
                          submitForm(page);

                        }
                                    
    });
  }
  else
    $('#pagination').hide();
        
} 
   
</script>
