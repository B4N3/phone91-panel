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
        <input type="button" value="Go" onclick="submitForm()"/> 
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
      </tr>
    </thead>
    
    <tbody id="tbody">
        
    </tbody>
</table>


<script type="text/javascript">
    
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
                            <td class='alC '>"+value['balance_deduct']+"</td>\
                            </tr>";
               })
              return str;
    }
    function submitForm()
    {
       var data = $('#searchForm').serialize();
       data = "call=getCallDetailsAdmin&"+data;
       $.ajax({
           url:"/controller/userCallLog.php",
           type:"post",
           dataType:"JSON",
           data:data,
           success:function(response)
           {
              
               if(response == null || typeof response === undefined || response == "" || response == false)
                   return false;
               var str = renderResponseCallDetails(response);
               $('#tbody').html(str); 
           }
       })
       
    }
    
    
    function getRouteList()
    {
        
        $.ajax({
           url:"/controller/routeController.php",
           type:"post",
           dataType:"JSON",
           data:{"call":"getRouteDetails"},
           success:function(response)
           {
              
               if(response == null || typeof response === undefined || response == "" || response == false)
                   return false;
               var str = "<option>Select Route</option>";
               $.each(response,function(key,value){
                   str += "<option value='"+value.route+"'>"+value.route+"</option>";
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
   
</script>
