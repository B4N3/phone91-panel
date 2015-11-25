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
<div class="commHeader">
	<div class="clear showAtFront" id="srchrow">
			<p class="showSearch">
				<input type="text" placeholder="Search" value="" onkeyup="searchKeyword($(this).val());" />
				<button class="btn btn-primary" title="Search"><i class="ic-16 searchW"></i></button>
			</p>
			<!--<label>
				<p class="fl">Showing <span>100</span> results by <span>latest</span> whose cost is more than </p>
				<p class="fl showInfo"> 
					<span class="ic-8 close"></span>
					<span class="fl">500</span>
					<span class="ic-8 arrow"></span>
					<p class="rowClose fl cp" title="Close"><span class="ic-16 close"></span></p>
				</p>
		   </label>-->
			</div>
	<div class="showAtBack"><a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a></div>
</div>
<div id="emptyCallLog"></div>
<div id="leftsec" class="commLeftSec slideLeft">
	<div class="innerSection">
		

		<ul id="callLogList" class="ln">
			
		</ul>
	</div>	
</div>
 <!--//Left Coll Log Section-->

<!--Right Coll Log Section-->
<div id="rightsec" class="commRightSec usercallWidth slideRight">	
	<div class="box-dtl clear" id="detailsLog">
	</div>
	<ul class="ln prCallList" id="callLogDetails">
	</ul>
</div>
<!--//Right Coll Log Section-->

<script type="text/javascript">
//dynamicPageName('Call Log');
slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');

$(document).ready(function(){
    
console.log("123");
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
                
                str += '<li class="clear logLi slideAndBack" onclick="getLogDetails('+value.called_number+',$(this))">\
                <div class="col-1-3 slideAndBack">\
                <p class="cntmNo semi">'+value.called_number+'</p>\
                    <p class="cntName ellp">'+(value.contactName != null?value.contactName:name)+'</p>\
                                <p class="cntAddTime">'+value.call_start+' PM</p>\
                                <p class="mrT2">\
                                    <span class="callDuration"><span id="time_'+value.called_number+'"></span> </span> \
                                    <span class="callCost"><span id="amount_'+value.called_number+'"></span> </span>\
                                </p>\
                            </div>\
                            <div class="col-3-4">\
                                <div class="alR">\ '+
//                                    <p class="callInfo">'+callType+'</p>\ 
//                                    <a class="wantToCall mrT btn btn-mini btn-primary clear alC" href="javascript:void(0);">\
//                                        <div class="clear tryc tr1">\
//                                            <span class="ic-16 callW"></span>\ 
//                                            //<span>Call</span>\
//                                        </div>\
//                                    </a>\
                                    '<p class="qntCall"><span id="totalCall_'+value.called_number+'"></span></p>\
                                </div>\
                            </div>\
                        </li>\ ';
            })
            
        	$('#callLogList').html(str);
			slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
			
			$('#srchrow').show();
        }
        else
        {
            str += '<div id="emptyCallLogNote"><div class="mascotWithCap"></div><div class="emptyCallLogMsg">Call log Empty</div><div class="emptyCallLogDes">But you know what...Today is a good day to <a href="#!contact.php" class="themeLink">talk </a> !</div></div>'
            $('#srchrow').hide();
			$('#emptyCallLog').html(str);
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
				   
				   str += '<li class="clear logLi slideAndBack" onclick="getLogDetails('+value.called_number+',$(this))">\
					<div class="col-1-3">\
					<p class="cntmNo semi">'+value.called_number+'</p>\
						<p class="cntName ellp">'+(value.contactName != null?value.contactName:name)+'</p>\
									<p class="cntAddTime">'+value.call_start+' PM</p>\
									<p class="mrT2">\
										<span class="callDuration"><span id="time_'+value.called_number+'"></span> </span> \
										<span class="callCost"><span id="amount_'+value.called_number+'"></span> </span>\
									</p>\
								</div>\
								<div class="col-3-4">\
									<div class="alR">\ '+
	//                                    <p class="callInfo">'+callType+'</p>\ 
	//                                    <a class="wantToCall mrT btn btn-mini btn-primary clear alC" href="javascript:void(0);">\
	//                                        <div class="clear tryc tr1">\
	//                                            <span class="ic-16 callW"></span>\ 
	//                                            //<span>Call</span>\
	//                                        </div>\
	//                                    </a>\
										'<p class="qntCall"><span id="totalCall_'+value.called_number+'"></span></p>\
									</div>\
								</div>\
							</li>\ ';
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
    function getLogDetails(number,ths)
    {
        $('.logLi').removeClass('selected');
        $(ths).addClass('selected');
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
                    
               
               balDeducted = (balDeducted+ parseFloat(value.deductBalance)); 
               
               callduration = (callduration + parseFloat(value.duration)); 
               
               if(value.status == "ANSWERED")
                   clas = 'answered';
        str += ' <li class="clear '+clas+'">\
                <div class="col-1-3">\
                        <p>'+value.call_start+'</p>\
                        <p>\
                            <span class="callDuration">'+value.callduration+' min</span> \ '+
                            //&nbsp;  &nbsp; &nbsp;<span class="callPrice">20 USD</span>\
                        '</p>\
                </div>\
                <div class="col-3-4">\
                    <div class="status alR">\
                        <p class="cInfo error">'+value.status+'</p>\
                        <p class="cProvider">'+callType+'</p>\
                    </div>\
                </div>\
                    </li> \ ';
            })
            balDeducted = balDeducted+" <span class='usd'><?php echo $_SESSION['currencyName']; ?></span>";
            callduration = (callduration > 59 ? ((callduration/60).toFixed(3).replace(".",":")) :(callduration < 10 ? ("00:0"+callduration) : ("00:"+callduration)) );
            
            head = '<div class="col">\
                        <h3 class="ellp name">'+(msg[0].contactName== null ? name: msg[0].called_number)+'</h3>\
                        <p class="contactNo">'+msg[0].called_number +'</p>\
                    </div>\
                    <div class="col">\
                        <h3 class="ellp alR" id="totalBalDeduct">'+balDeducted+'  </h3>\
                        <p class="alR timeDetails">\
                            <span class="callDuration">'+callduration+' </span> min &nbsp;  \
                            <span class="totalCall"><i>'+i+'</i> Calls</span>\
                        </p>\
                    </div>';
        }
//        if(callduration == "")
//            callduration = "00:00";

        
        $('#time_'+number).html(callduration+' min');      
        $('#amount_'+number).html(" | "+balDeducted);      
        $('#totalCall_'+number).html(i+' <span>Calls</span>');      
        $('#detailsLog').html(head);
        $('#callLogDetails').html(str);
        $('#totalBalDeduct').html(balDeducted);

        }
        
    })
    }

</script>