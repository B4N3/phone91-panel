<?php
//Include Common Configuration File First
include_once ('config.php');
include_once CLASS_DIR . 'call_class.php';
$callObj = new call_class();

if (!$funobj->login_validate() ) {
    $funobj->redirect("index.php");
}

$start = 0;
$limit = 10;
$userId = $_SESSION["id"];
//$response = $callObj->getMyCall($userId, $start, $limit);
//var_dump($response);
//$callData = json_decode($response, true);
?>
<style>.commLeftSec, .commRightSec{top:40px;}</style>

<div class="commHeader">
	<div class="showAtBack"><a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a></div>
</div>

<div id="emptyCallLog"><!--design appears here if call log is empty--></div>

<div id="hdTxt" class="h3">Recent Calls</div>
<div id="leftsec" class="commLeftSec slideLeft">
    <!--inner-->
	<div class="innerSection">
    	<!--search-->
        <div class="clear showAtFront" id="srchrow">
            <input type="text" placeholder="Search" class="linp" value="" onkeyup="searchKeyword($(this).val());" />
        </div>
		<ul id="callLogList" class="ln" style="margin-top:50px">
		</ul>
	</div>	
</div>
 <!--//Left Coll Log Section-->

<!--Right Coll Log Section-->
<div id="rightsec" class="commRightSec usercallWidth slideRight bgW pd15">	
	<div class="clear mrB1" id="detailsLog">
	</div>
	<table id="CallLogTbl" class="cmntbl mrB2" width="100%" cellspacing="0" cellpadding="0" border="0">
    	<thead>
        	<tr class="semi">
            	<th>Time</th>
                <th>Date</th>
                <th>Duration</th>
                <th>Call via</th>
                <th>Call cost</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<!--//Right Coll Log Section-->

<script type="text/javascript">
var storage = window['localStorage'];
dynamicPageName('Call Log');

if(storage.getItem('currentId')){
	getLogDetails(storage.getItem('currentId'),$('#logLi'+storage.getItem('currentId')),'')
}

slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');

$(document).ready(function(){
   $.ajax({
        //function fetches the call logs list of the user 
        // return type is json 
        url:"controller/userCallLog.php?call=userCallLogs",
        type:"POST",
        dataType:"JSON",
        success:function(msg)
        {
            
           
            var str = "";
            var name = "Unknown";
            var callType = "";
            
            if(msg != null)
            {
            
            $.each(msg,function(key,value){
//                if(value.call_type == "C2C")
//                    callType = "<span class='calling'>Two way calling</span>";//<a class='btn btn-medium btn-primary callButtn' title='Call'><span class='ic-16 callW'></span>Call</a>";
//                else
//                    callType = value.call_type;
                 console.log(value.call_start);
                str += '<li class="clear logLi slideAndBack" id="logLi'+value.called_number+'" onclick="getLogDetails('+value.called_number+',$(this),event)">\
							<p class="cntName ellp">'+(value.contactName != null?value.contactName:name)+'</p>\
							<p class="cntmNo"><i class="ic-16 call"></i>'+value.called_number+' | '+value.call_start+'</p>\
						</li>';
            })            
        	
			$('#callLogList').html(str);
			
			if(storage.getItem('currentId')){
				getLogDetails(storage.getItem('currentId'),$('#logLi'+storage.getItem('currentId')),'')
			}
			
			slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
			$('#hdTxt, #leftSec, #rightSec').show();
        }
        else
        {
            str += '<div id="emptyCallLogNote"><h3 class="h2 mrB2">|| Call log <span class="red">Empty</span> ||</h3></div><div class="sketchCir"><div class="sketch"></div></div><div class="ecDesc">But you know what?<br> Today is a good day to <a href="#!contact.php" class="green tdu">talk!</a></div></div>'
            $('#hdTxt, #leftsec, #rightsec').hide();
			$('#emptyCallLog').html(str).show();
        }
						
        }
    })
   }) 
    
	function searchKeyword(keyword)
    {
        $.ajax({
        //function fetches the call logs list of the user 
        // return type is json 
        url:"controller/userCallLog.php",
        type:"POST",
        data:{call:'searchCallLogs',keyword:keyword},
        dataType:"JSON",
        success:function(msg)
        {
			var str = '';
            var name = "Unknown";
            var callType = "";
           
            if(msg != null && msg.type != "error")
            {
				$.each(msg,function(key,value){
					
	//                if(value.call_type == "C2C")
	//                    callType = "Two way calling";
	//                else
	//                    callType = value.call_type;
				   
				   str += '<li class="clear logLi slideAndBack" id="logLi'+value.called_number+'" onclick="getLogDetails('+value.called_number+',$(this),event)">\
							<p class="cntName ellp">'+(value.contactName != null?value.contactName:name)+'</p>\
							<p class="cntmNo"><i class="ic-16 call"></i>'+value.called_number+' | '+value.call_start+'</p>\
						</li>';
				})
            }
            else
			{
                //show_message(msg.msg,msg.type);
			}
        		$('#callLogList').html(str);
				slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
        }
        
    })
    
    }
    
	function getLogDetails(number,ths,e)
    {
		$('.logLi').removeClass('selected');
        $(ths).addClass('selected');
		
		if(!e.target){
			if($(ths).length > 0){
				var top = $(ths).position().top;
				$('#callLogList').scrollTop(top-100);		
			}
		}
		
		storage.setItem('currentId',number)
        $.ajax({
        //function fetches the call logs list of the user 
        // return type is json 
        url:"controller/userCallLog.php",
        type:"POST",
        data:{call:'getCallLogsDetails',number:number},
        dataType:"JSON",
        success:function(msg)
        {
           
            var balDeducted =0.0;
            var callduration =0.0;
            var name = "Unknown";
            var str = '';
            var i = 0;
            var head ="";
            var clas = 'unanswered';
            var callType = "";
            if(msg != null)
            {
            $.each(msg,function(key,value){
                i++;
                if(value.call_type == "C2C")
                    callType = "Two way calling";
                else
                    callType = value.call_type;
                    value.duration;
                    
               console.log("Duration "+value.duration);
               var newDuration = (Math.ceil(value.duration/60) * 60);
               balDeducted = (balDeducted+ parseFloat(value.deductBalance)); 
               
               callduration = (callduration + parseFloat(newDuration)); 
               
               if(value.status == "ANSWERED")
                   clas = 'answered';
        str += ' <tr class='+clas+'>\
                        <td>'+value.time+'</td>\
			<td>'+value.date+'</td>\
                        <td>'+value.callduration+'</td>\
                        <td class="cProvider">'+callType+'</td>\
                        <td>'+value.deductBalance+' <?php echo $_SESSION['currencyName']; ?></td>\
                    </tr> \ ';
            })
            callduration = secondsToTime(callduration);
            head = '<div class="col-5">\
                        <h3>'+i+' Calls</h3>\
                    </div>\
					<div class="col-5">\
                        <h3 class="ellp name alR">'+(msg[0].contactName== null ? msg[0].called_number : name)+'</h3>\
                    </div>';
        }
        
        $('#time_'+number).html(callduration+' hour');      
        $('#amount_'+number).html(" | "+balDeducted);      
        $('#totalCall_'+number).html(i+' <span>Calls</span>');      
        $('#detailsLog').html(head);
        $('#CallLogTbl tbody').html(str);

        }
        
    })
    }
    
    
//function secondsToTime(secs)
//{
//    var hours = Math.floor(secs / (60 * 60));
//    if(hours < 10)
//        hours = '0'+hours;
//        var minutes = Math.floor(secs / 60) ;
//    if(minutes < 10)
//        minutes = '0'+minutes;
//    
//        var seconds =Math.floor( secs % 60);
//    if(seconds < 10)
//        seconds = '0'+seconds;
//    
//    
//    return hours+':'+minutes+':'+seconds;
//}    
    
    function secondsToTime(d) {
d = Number(d);
var h = Math.floor(d / 3600);
var m = Math.floor(d % 3600 / 60);
var s = Math.floor(d % 3600 % 60);
return ((h > 0 ? h + ":" : "00:") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") + (s < 10 ? "0" : "") + s); 
}

</script>