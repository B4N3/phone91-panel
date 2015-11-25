<?php

include_once '../classes/routeClass.php';
    $rObj = new routeClass();
if(!isset($_REQUEST['routeId'])){
    
    
    
    $response = $rObj->getRoute(array(),$_SESSION['userid']);
    
    $arr = json_decode($response,TRUE);
    $_REQUEST['routeId'] = $arr[0]['id'];
    
}

$routeJson = $rObj->getRouteDetail($_REQUEST,$_SESSION['userid']);

$routeArray = json_decode($routeJson,TRUE);
$routeName = $routeArray['routeName'];

?>

<!--Manage Client Settings-->

<!--<script src="../js/jquery.highchartTable-min.js"></script>-->
<script src="../js/json-to-table.js"></script>
<script src="js/script.js"></script>
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
<div class="dateSearch" style="margin-bottom: 11px;"> From:<input type="text" id="fromDate" name="dob" value="" class="">
    To:<input type="text" id="toDate" name="dob" value="" class="">
    <button  class="" onclick="getRouteCharts(<?php echo $_REQUEST['routeId'];?>)">Go</button>
    </div>
<div class="secondaryMenu">
  		<ul class="clear oh mrB2" id="manageRouteSet">
            <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-route.php|route-transactional.php?routeId=<?php echo $_REQUEST['routeId'];?>&tb=0'" >
                    		<span class="ic-tranLog"></span>
                            <p>Transactional</p>
                    </a>
            </li>
            <a href="transactional.php"></a>
            <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-route.php|route-transactional.php?routeId=<?php echo $_REQUEST['routeId'];?>&tb=1'" >
                        <span class="ic-editfund "></span>
                        <p> Edit Fund</p>
                    </a>
            </li>
<!--            <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-route.php|routeTransactional.php?clientId=<?php echo $_REQUEST['routeId'];?>&tb=2'">
                         <span class="ic-addsip "></span>
                        <p> Add SIP</p>
                    </a>
             </li>-->
			<li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-route.php|route-transactional.php?routeId=<?php echo $_REQUEST['routeId'];?>&tb=2'" >
                         <span class="ic-setting"></span>
                        <p> Manage Route</p>
                    </a>
             </li>
<!--             <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-route.php|route-transactional.php?routeId=<?php echo $_REQUEST['routeId'];?>&tb=3'" >
                        <span class="ic-latestinfo"></span>
                        <p> Route info</p>
                    </a>
             </li>-->
    </ul>
  		<div class="callnstatus clear">
<!--        			<div class="box-widgets fwid">
                   		<p class="head">
                        		<span class="fl">Call Log</span>
                                <span class="fr f12 totalCalls">Total Calls 2500</span>
                        </p>
                       <div class="content">
                      	  <div id="getRouteStatusDetailsBarGraphContainer"></div>
                        </div>
                    </div>-->
                    
                    <div class="box-widgets fwid2" style="width: 1000px;">
                    		<p class="head">
                        		<span class="fl">Status</span>
                                <span class="fr f12 totalCalls">Total Calls 0</span>
                        </p>
                         <div class="content">
                        	<div id="getRouteStatusDetailsGraphContainer"></div>
                         </div>
                    </div>
        </div>
         <div id="changeSearchType"><span>Select Criteria: </span> 
                        <input type="radio" name='searchType' class='searchcri' checked="checked" value="1">Date
                        <input type="radio" name='searchType' class='searchcri' value="2">Monthly
                    </div>
    	           <div class="box-widgets timeline" style="width: 1000px;">
                        <p class="head">
                        <span class="fl">Timeline</span>
                        <span class="fr f12 totalCalls">Total Calls 0</span>
                        </p>
                    <div class="content">
                    <div id="getRouteTimeLineDetailsGraphContainer"></div>
                   
                    </div>
                 </div>
                  <div class="box-widgets country" style="width:1400px;">
                        <p class="head">
                        <span class="fl">Country</span>
                        <span class="fr f12 totalCalls">Total Calls 0</span>
                        </p>
                    <div class="content">
                    <div id="getRoutecountryDetailsGraphContainer">
                        <iframe id="countryifrm" src="<?php echo PROTOCOL.HOST_NAME.ADMIN_DIR;?>routeCountryLog.php?fromDate=&toDate=&route=<?php echo $routeName;?>" style="width:1400px;height:600px;position: relative;top: 1px;left: 51px;border-width: 0px;"></iframe></div>
                   
                    </div>
                 </div>
                  
        </div>	

<!--//Manage Client Settings-->
<script type="text/javascript">


$.datepicker.setDefaults({changeMonth: true,
            changeYear: true});
  $('#fromDate').datepicker({onSelect: function() {
            var date = $(this).datepicker('getDate');
            if (date) {
                  date.setDate(date.getDate() + 1);
            }
            $('#toDate').datepicker('option', 'minDate', date);
      }});
      $('#toDate').datepicker({onSelect: function() {
            var date = $(this).datepicker('getDate');
            if (date) {
                  date.setDate(date.getDate() - 1);
            }
            $('#fromDate').datepicker('option', 'maxDate', date);
      }});    
    
    
    var routeId = <?php echo $_REQUEST['routeId'];?>;
    getRouteDetail(routeId,'getRouteStatusDetails','status');
    

    function getRouteCharts(routeId)
    {
        getRouteDetail(routeId,'getRouteStatusDetails','status');

        var searchType =  $("input:radio[name=searchType]:checked").val();
        if(searchType == undefined || searchType == null)
            searchType = 1;
        getRouteTimeLineDetail(routeId,'getRouteTimeLineDetails',searchType);


        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

       
        $('#countryifrm').attr('src','<?php echo PROTOCOL.HOST_NAME.ADMIN_DIR;?>routeCountryLog.php?fromDate='+fromDate+'&toDate='+toDate+'&route=<?php echo $routeName;?>');
        $( '#countryifrm' ).attr( 'src', function ( i, val ) { return val; });




    }

    function getRouteDetail(routeId,action,type,fromDate,toDate)
    {
    	$.ajax({
    		url: "/controller/userCallLog.php",
    		data: {"call": action,routeId: routeId, type: type,fromDate:$('#fromDate').val(),toDate:$('#toDate').val()},
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
    				    callBackDrawGraph(response.data, action,'pie');

    		    }
    		    //})
    		}
    	})
	
    }
    
    getRouteTimeLineDetail(routeId,'getRouteTimeLineDetails',1);


    /**
    *@author Ankit Patidar <ankitpatidar@hostnsoft.com>
    *@param int searchType 1 for date wise search and 2 for month wise search
    */
    function getRouteTimeLineDetail(routeId,action,searchType)
    {
        $.ajax({
            url: "/controller/userCallLog.php",
            data: {"call": action,
                    routeId: routeId,
                    fromDate:$('#fromDate').val(),
                    toDate:$('#toDate').val(),
                    searchType:searchType},
            type: "post",
            dataType: "json",
            success: function(response)
            {
                console.log(response);
                if(response.status == 'error')
                    return false;

                var xText = '';
                switch(searchType)
                {
                    case '1':
                        xText = 'Dates';
                    break;
                    case '2':
                        xText = 'Months';
                    break;
                    default:
                    break;
                }
                callBackDrawGraph(response.detail, action,'line',xText);

                // //$.each(response, function(key, item ) {


                //     if(response.data == null || response.data == '' || response.data == undefined){
                //         return false; //$('#'+key+'GraphContainer').parents('.cols2').hide();
                // }else{
                //     $('.totalCalls').html('Total Calls '+response.totalCalls);
                //        //$('#'+key+'GraphContainer').parents('.cols2').show();
                //         callBackDrawGraph(response.data, action);

                // }
                //})
            }
        })
    }

//call function to draw graph
function callBackDrawGraph(data, call,chartType,xText) {
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
		createHighchartNew('#'+call+'GraphContainer',chartType,call,series,categories,xText);		
		

    }



//getRouteStatusForPie('getRouteStatusDetails','<?php //echo $_REQUEST['routeId'];?>',1);

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

</script>