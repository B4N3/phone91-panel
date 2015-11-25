<?php
include dirname(dirname(__FILE__)) . '/config.php';
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


<script src="/js/exporting.js"></script>
<!--<script src="js/json-to-table.js"></script>
<script src="/js/Chart.min.js"></script>-->
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
<div class="dateSearch"> From:<input type="text" id="fromDate" name="fromDate" value="" class=""> To:<input type="text" id="toDate" name="toDate" value="" class=""/> <button id="showChart" class="" onclick="showChartByDate()">Go</button></div>
 
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
                    
                        <div class="cols2 col">
				<div class="colInner">			
					<div id="getResellerLossDetailsGraphTitle" class="gridTitle"></div>
					<div id="getResellerLossDetailsGraphContainer"></div>
				</div>
			</div>
                    
                       <div class="cols2 col">
				<div class="colInner">			
					<div id="" class="gridTitle"></div>
					<div id="GraphContainerAllCall"></div>
                                        <div id="totalBalance"></div>
				</div>
			</div>
                    
<!--                    <div id=""></div>-->
		
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
                <span class="rlStrong" id="totalProfit">0</span>
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

// $( "#fromDate,#toDate" ).datepicker({
//      changeMonth: true,
//      changeYear: true
//});

//var detailsResponse;
function getDetails(userId, type,fromDate,toDate)
{
	$.ajax({
		url: "/controller/userCallLog.php",
		data: {"call": "getAllChart",userId: userId, type: type,fromDate:fromDate,toDate:toDate,"graph":"getStatusDetails,getCallViaDetails,getResellerDurationDetails,getResellerProfitDetails,getCreditGraphDetails,getResellerLossDetails"},
		type: "post",
		dataType: "json",
		success: function(response)
		{
			if(response == null)
				return false;
                            
                           // $("#totalBalance").html(response.totalBal);
                           console.log(response);
                            console.log(response.totalBal+"NIdhi ---");
                    
			$.each(response, function(key, item ) {
                                
                                if(key == 'getStatusDetails'){
                                    $('#customerTime').html(item.customerTime);
                                    $('#mytotalTime').html(item.myTime);
                                }
                                                            
                    		if(item.data == null || item.data == ''){
					$('#'+key+'GraphContainer').parents('.cols2').hide();
                            }else{
                                        $('#'+key+'GraphContainer').parents('.cols2').show();
					callBackDrawGraph(item, key);
                        
                        }
			})
		}
	})
}

    getDetails(<?php echo $_SESSION['id']; ?>, 1);
  

function showChartByDate(){
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    getDetails(<?php echo $_SESSION['id']; ?>, 1,fromDate,toDate);
    getAllCallDetails();
}

    function callBackDrawGraph(data, call) {
        var categories = [];
        var dataArray = [];
        var mix = [];
        var i = 0;
        var chartType = 'pie';        
        if(data.status == "error" || data.data == null)
            return false;

        $.each(data.data,function(key,element){                    
			categories[i] = key;
			dataArray[i] = element;
			mix[i] = new Array(key,parseFloat(element)); //get value and percentage
			i++; //increament counter                    
		}); //end of each
                
        
        var series= [{//format for pie
                        name: 'value',
                        data: mix,
						dataLabels: {							
							style: {
								fontSize: '12px'								
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
				$('#resellerLog').show();
				break
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
                        case 'getResellerLossDetails':
				title = 'Loss';
				break;	
		}
		
                
		$('#'+call+'GraphTitle').html(title);          
    }

    function createHighchart(id,chartType,Title,dataArray,datas)
    { 
        //highchart function with proper setting to genrate graph           
        function hideLegend(ts){
			var legend = $('.highcharts-legend',ts.container);
			legend.hide();
		};
		function showHideLegend(ts){
			var legend = $('.highcharts-legend',ts.container);			
			if(legend.css("display") == 'inline' || legend.css("display") == 'block')
				legend.hide();
			else
				legend.show();	
		}
		$(id).highcharts({           
            chart: {
                type: chartType,				
				style: {
					fontFamily: 'Arial, Helvetica, sans-serif', // default font
					fontSize: '25px'
				},
				events: {
					redraw: function() {
                    	//hideLegend(this)						
                	},
                	load: function(event) {                    	
						hideLegend(this);						
                	}
           	 	}
            },
			title: {
            	text: ''
        	},          
            plotOptions: {                
                pie:{
               		allowPointSelect: true,
                    cursor: 'pointer',
					size:150,
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',                        
                        formatter: function() {
							return this.point.name+' : '+this.y;
						}
					},
                    showInLegend: true				
				}
            },
            legend: {
               layout: 'vertical',
               align: 'right',
			   floating:true,
			   maxHeight:300,             
               borderWidth: 1,
               backgroundColor: '#FFFFFF',
               shadow: true,
			   labelFormatter: function() {
			   	return this.name +' - '+this.y;			   
			   }
            },
			navigation: {
				buttonOptions: {
					height: 27,
					symbolY: 13,
					theme: {
						// Good old text links
						style: {
							color: '#30A5D8'							
						},
						states: {
							hover: {
								fill: '#f5f5f5'
							},
							select: {								
								fill: '#f5f5f5'
							}
                    	}
					}					
				}
			},
			exporting: {
            	buttons: {					
					/*exportButton: {
						text: 'Download',						
						menuItems: Highcharts.getOptions().exporting.buttons.contextButton.menuItems.splice(2)
					},
					printButton: {
						text: 'Print',
						onclick: function () {
							this.print();
						}
					},*/
					legendButton: {
						text: 'Legends',
						onclick: function () {
							showHideLegend(this);						
						}
					}
            	}
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
    
    
    function getAllCallDetails()
    {
         var fromDate = $('#fromDate').val();
         var toDate = $('#toDate').val();
        
    	$.ajax({
    		url: "/controller/userCallLog.php",
    		data: {"call": "getAllCallDetails" ,fromDate:$('#fromDate').val(),toDate:$('#toDate').val()},
    		type: "post",
    		dataType: "json",
    		success: function(response)
    		{
    		    console.log(response);
    		    if(response == null)
    			    return false;

    		    //$.each(response, function(key, item ) {


    			    if(response.data == null || response.data == '' || response.data == undefined){
    				    return false; //$('#'+key+'GraphContainer').parents('.cols2').hide();
    			}else{
    			    $('.totalCalls').html('Total Calls '+response.totalCalls);
    				   //$('#'+key+'GraphContainer').parents('.cols2').show();
    				    callBackDrawGraphAllCall(response.data, "getAllCallDetails",'pie');

    		    }
    		    //})
    		}
    	})
	
    }
    
    
  getAllCallDetails();  
    
    
    
    function callBackDrawGraphAllCall(data, call,chartType,xText) {
    console.log(data);
    console.log(call);
        var categories = [];
        var dataArray = [];
        var dataSer = [];
        var mix = [];
        var i = 0;
        var dataRwise = [];      
        // if(data.status == "error" || data.data == null)
        //     return false;

        switch(chartType)
        {
            case 'pie':
                    {   $.each(data,function(key,element){                    
                            categories[i] = key;
                            dataArray[i] = element;
                            mix[i] = new Array(key,parseFloat(element)); //get value and percentage
                            i++; //increament counter                    
                        }); //end of each
                            
                
                        var series= [{//format for pie
                                        name: 'value',
                                        data: mix,
                                        dataLabels: {                           
                                            style: {
                                                fontSize: '12px'                                
                                            }
                                        }
                                    }];
            }
            break;
            case 'line':
            {
                categories = data.dayArr;
                series = [{name:'Total Call Duration',
                          data:data.totalCallDuration},
                          {name:'Route call duration',
                           data:data.routeCallDuration },
                           {name:'ACD(average call duration)',
                           data:data.acd },
                           {name:'ASR(average seizure ratio)',
                           data:data.asr }];
            }
            break;
        }
        
        console.log(categories);
        console.log(series);
		createHighchartNew('#GraphContainerAllCall',chartType,call,series,categories,xText);		
		

    }
    function createHighchartNew(id,chartType,Title,dataArray,datas,xText)
    { 
        //highchart function with proper setting to genrate graph           
       
		$(id).highcharts({           
            chart: {
                type: chartType,				
				style: {
					fontFamily: 'Arial, Helvetica, sans-serif', // default font
					fontSize: '25px'
				},
				events: {
					redraw: function() {
                    	//hideLegend(this)						
                	},
                	load: function(event) {                    	
						hideLegend(this);						
                	}
           	 	},
                width:600
			
            },
			title: {
            	text: ''
        	},
            xAxis: {
                 title: {
                    text: xText
                },
                categories: datas,
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Minutes'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },          
            plotOptions: {                
                pie:{
               		allowPointSelect: true,
                    cursor: 'pointer',
					size:150,
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',                        
                        formatter: function() {
							return this.point.name+' : '+this.y;
						}
					},
                    showInLegend: true				
				}
            },
            legend: {
               layout: 'vertical',
               align: 'right',
			   floating:true,
			   maxHeight:300,             
               borderWidth: 1,
               backgroundColor: '#FFFFFF',
               shadow: true,
			   labelFormatter: function() {
			   	return this.name +' - '+this.y;			   
			   }
            },
			navigation: {
				buttonOptions: {
					height: 27,
					symbolY: 13,
					theme: {
						// Good old text links
						style: {
							color: '#30A5D8'							
						},
						states: {
							hover: {
								fill: '#f5f5f5'
							},
							select: {								
								fill: '#f5f5f5'
							}
                    	}
					}					
				}
			},
			exporting: {
            	buttons: {					
//					exportButton: {
//						text: 'Download',						
//						menuItems: Highcharts.getOptions().exporting.buttons.contextButton.menuItems.splice(2)
//					},
//					printButton: {
//						text: 'Print',
//						onclick: function () {
//							this.print();
//						}
//					},
					legendButton: {
						text: 'Legends',
						onclick: function () {
							showHideLegend(this);						
						}
					}
            	}
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
				
							
            ],
            series:dataArray
        });
        
    }
     function hideLegend(ts){
            var legend = $('.highcharts-legend',ts.container);
            legend.hide();
        };
</script>