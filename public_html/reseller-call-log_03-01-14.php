<?php
include_once('config.php');
include_once(CLASS_DIR . "callLog_class.php");

#object of transaction class 
//$trans_obj = new transaction_class();
//$userId = $_SESSION['userid'];
//
//$logClsObj = new log_class();
//$type = "status";
////$type = "callvia";
//echo $result  = $logClsObj->getCallLogSummary($type,$userId);
//echo $res  = $logClsObj->getCreditGraph($userId);
#call function get Reseller Transactionlog Detial for get all detion of transation 
//$transaction = $trans_obj->getResellerTransaction($userId);
//$transData = json_decode($transaction,TRUE);
?>


<!--<script src="/js/jquery.highchartTable-min.js"></script>
<script src="js/json-to-table.js"></script>-->
<!--<script src="/js/Chart.min.js"></script>-->
<div class="commHeader">
	<div class="showAtFront">
        <div class="clear" id="srchrow">
			<!--<input type="text" name="searchUser" onkeyup="advanceSearchUser($(this).val())" id="searchUser" placeholder="search User">-->
            <!--<label>Showing 100 results by latest whose cost is more than 500</label>-->
            <div class="sett pr">
<!--                <div class="cp" onclick="uiDrop(this, '#showTrSett', false)">
                    <i class="ic-16 dropsign"></i>
                    <i class="ic-24 setting"></i>
                </div>-->
<!--                <ul class="dropmenu boxsize ln" id="showTrSett">
                    <li>Export CSV</li>
                    <li>Export PDF</li>
                    <li>Export XlS</li>
                </ul>-->
            </div>
        </div>
	</div>
	<div class="showAtBack"><a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a></div>	
</div>
<div class="logWrap">
	<div class="logLeft">
		<!--<table class="logGrid">
		<tr>		
			<td class="cols2 col">
				<div id="getStatusDetailsGraphTitle" class="gridTitle"></div>
				<div id="getStatusDetailsGraphContainer"></div>
			</td>
			<td class="cols2 col">
				<div id="getCallViaDetailsGraphTitle" class="gridTitle"></div>
				<div id="getCallViaDetailsGraphContainer"></div>
			</td>
		</tr>
		<tr>		
			<td class="cols2 col">
				<div id="getResellerDurationDetailsGraphTitle" class="gridTitle"></div>
				<div id="getResellerDurationDetailsGraphContainer"></div>
			</td>
			<td class="cols2 col">
				<div id="getResellerProfitDetailsGraphTitle" class="gridTitle"></div>
				<div id="getResellerProfitDetailsGraphContainer"></div>
			</td>
		</tr>
		<tr>		
			<td class="cols2 col">
				<div id="getCreditGraphDetailsGraphTitle" class="gridTitle"></div>
				<div id="getCreditGraphDetailsGraphContainer"></div>
			</td>
			<td class="cols2 col">
				
			</td>
		</tr>
		<tr>		
			<td colspan="2" class="col">Map</td>
		</tr>
		</table>-->
		<div class="logGrid">
		
			<div class="cols2 col">
				<div class="colInner">
					<div id="getStatusDetailsGraphTitle" class="gridTitle"></div>
					<div id="getStatusDetailsGraphContainer"></div>
				</div>
			</div>
			<div class="cols2 col">
				<div class="colInner">			
					<div id="getCallViaDetailsGraphTitle" class="gridTitle"></div>
					<div id="getCallViaDetailsGraphContainer"></div>
				</div>
			</div>		
		
			<div class="cols2 col">
				<div class="colInner">			
					<div id="getResellerDurationDetailsGraphTitle" class="gridTitle"></div>
					<div id="getResellerDurationDetailsGraphContainer"></div>
				</div>
			</div>				
			<div class="cols2 col">
				<div class="colInner">			
					<div id="getResellerProfitDetailsGraphTitle" class="gridTitle"></div>
					<div id="getResellerProfitDetailsGraphContainer"></div>
				</div>
			</div>
		
			<div class="cols2 col">
				<div class="colInner">				
					<div id="getCreditGraphDetailsGraphTitle" class="gridTitle"></div>
					<div id="getCreditGraphDetailsGraphContainer"></div>
				</div>
			</div>
		
		</div>
	</div>
	<div class="logRight">
		<div id="resellerLog">
      		<div class="gridTitle">Statistics</div>
            
<!--            <div class="rlFields">
            	<label class="rlLbl">Choose Country</label>
				<span class="rlStrong">                
				<select name="" class="isInput35Fix"> 
                	<option>India</option>
                    <option>USA</option>
                </select>
				</span>
            </div>-->
            
<!--            <div class="rlFields">	
            	<label>Average call duration</label>
                <span class="rlStrong">20:00 min </span>
            </div>-->
            
             <div class="rlFields">	
            	<label>Answered call</label>
                <span class="rlStrong" id="answeredCallPer">0</span>
            </div>
            
            <div class="rlFields">	
            	<label>My total time</label>
                <span class="rlStrong" id="mytotalTime">00:00:00 Hrs</span>
            </div>
            
            <div class="rlFields">	
            	<label>Total Customer time</label>
                <span class="rlStrong" id="customerTime">00:00:00 Hrs</span>
            </div>
            
            <div class="rlFields">	
            	<label>Total Profit</label>
                <span class="rlStrong" id="totalProfit">0 USD</span>
            </div>
            
      </div>
	</div>
</div>
		
      
<script type="text/javascript">
$(document).ready(function(){	
	/*$( "#getStatusDetailsGraphContainer" ).append( "<h1>test</h1>" );
	$("#resTrnLogTbl tbody tr:visible:even").addClass("even");
	$("#resTrnLogTbl tbody tr:visible:odd").addClass("odd");*/
})

//var detailsResponse;
function getDetails(userId, type)
{
	$.ajax({
		url: "controller/userCallLog.php",
		data: {"call": "getAllChart",userId: userId, type: type,"graph":"getStatusDetails,getCallViaDetails,getResellerDurationDetails,getResellerProfitDetails,getCreditGraphDetails"},
		type: "post",
		dataType: "json",
		success: function(response)
		{
//                    console.log(response);
                    if(response == null)
                        return false;
                    
			$.each(response, function(key, item ) {
				callBackDrawGraph(item, key);
			})
		}
	})
}

    getDetails(<?php echo $_SESSION['id']; ?>, 1);
    
    function callBackDrawGraph(data, call) {
        var categories = [];
        var dataArray = [];
        var mix = [];
        var i = 0;
        var chartType = 'pie';
        console.log(data);
        console.log(call);
        if(data.status == "error" || data.data == null)
            return false;

        $.each(data.data,function(key,element){
                    
                    categories[i] = key;
                    dataArray[i] = element;
                    mix[i] = new Array(key,parseInt(element)); //get value and percentage
                    i++; //increament counter
                    
                }); //end of each
                
        
        var series= [{ //format for pie
                        name: 'value',
                        data: mix,
						dataLabels: {							
							style: {
								fontSize: '12px',								
							}
						}
                    }];
        createHighchart('#'+call+'GraphContainer',chartType,call,series,categories);
		
		var title
		switch(call)
		{
		case 'getStatusDetails':
		  title = 'Status';
                  $('#answeredCallPer').html(data.answeredCallPercent + "%");
                  $('#customerTime').html(data.customerTime);
                  $('#mytotalTime').html(data.myTime);
                  break;
		case 'getCallViaDetails':
		  title = 'Call via';
		  break;
		case 'getResellerDurationDetails':
		  title = 'Duration';
		  break;
		case 'getResellerProfitDetails':
		  title = 'Profit';
                  $('#totalProfit').html(data.totalProfit);
		  break;
		case 'getCreditGraphDetails':
		  title = 'Credit Graph';
		  break;		  		  		  
		}		
		$('#'+call+'GraphTitle').html(title);
          
    }

    function createHighchart(id,chartType,Title,dataArray,datas)
    { 
        //highchart function with proper setting to genrate graph           
        $(id).highcharts({
           
            chart: {
                type: chartType,				
				style: {
					fontFamily: 'Arial, Helvetica, sans-serif', // default font
					fontSize: '25px'
				}
            },
            title: {
                text: ''
            },
            
            xAxis: {
                categories: datas,
                title: {
                    text: null
                },
               labels:
                       {
                        rotation:-70,
                        align:'right'
                       }
            },
            yAxis: {
                min: 0,
                title: {
                   
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                    
                }
            },
            tooltip: {
               // pointFormat: '{point.percentage}',
                //percentageDecimals:1,
                //valueDecimals:0
            
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        
                    }
                },
                column:{
                  pointWidth:5 //set column width  
                },
                pie:{
               		allowPointSelect: true,
                    cursor: 'pointer',
					size:150,
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',                        
                         formatter: function() {
                            //console.log(this);
//                             if(cType == "amount")
//                                return '<b>'+ this.point.name +'</b>: '+this.y+' Rs';
//                            else
                                return this.point.name+' : '+this.y;
                            }
                        },
                     showInLegend: true
                }
            },
            legend: {
               layout: 'vertical',
               align: 'right',
               verticalAlign: 'top',
			   margin:50,
               x: 0,
               y: 0,
               floating: true,
               borderWidth: 1,
               backgroundColor: '#FFFFFF',
               shadow: true
            },
            credits: {
                enabled: false
            },
             colors:[
                '#da4453',
                '#f6bb42',
                '#37bc9b', 
                '#4a89dc', 
                '#d770ad', 
                '#aab2bd', 
                '#e9573f',
				'#8cc152',
                '#3bafda',
				'#967adc',
				'#656d78',
				'#434a54',
				'#9f5f3a',
				'#2e899b'
				
				/*'#781f1f',
				'#a51f1f',
				'#cb0000',
				'#d24b4b',				
				'#ec6196',
				'#a54ba5',
				'#784ba5',
				'#4b4ba5',
				'#1f78d2',
				'#1fa5d2',
				'#66cccc',
				'#87d24b',
				'#78a51f',
				'#4b781f',
				'#78781f',
				'#a5a51f',				
				'#d2d24b',
				'#ffff1f',
				'#ffd24b',
				'#ffa51f',
				'#ff781f',
				'#d2784b',				
				'#a5784b',
				'#784b1f',
				'#333333',
				'#a5a5a5',
				'#d2d2d2'*/				
            ],
            series:dataArray
        });
        
    }
		


</script>