

<script src="/js/exporting.js"></script>
<!--<script src="js/json-to-table.js"></script>
<script src="/js/Chart.min.js"></script>-->
<div class="commHeader">
	<div class="showAtFront">
        <div class="clear" id="srchrow">
			<!--<input type="text" name="searchUser" onkeyup="advanceSearchUser($(this).val())" id="searchUser" placeholder="search User">-->
            <!--<label>Showing 100 results by latest whose cost is more than 500</label>-->
            <div class="sett pr">

            </div>
        </div>
	</div>
	
 
<div class="logWrap">
	<div class="logLeft">
		
		<div class="logGrid">
		
			<div class="cols2 col">
				<div class="colInner">
					<div id="getRouteCreditDetailGraphTitle" class="gridTitle"></div>
					<div id="getRouteCreditDetailGraphContainer"></div>
				</div>
			</div>
				
		
							
			
		
			
                    
                       
		</div>
	</div>
	
</div>
</div>
<script type="text/javascript">


//var detailsResponse;
function getRouteCreditDetails()
{
	$.ajax({
		url: "/controller/userCallLog.php",
		data: {"call": "getRouteCreditDetail"},
		type: "post",
		dataType: "json",
		success: function(response)
		{
			if(response == null || response.length == 0)
				return false;
                                       
					callBackDrawGraph(response, 'getRouteCreditDetail');
                        
                       
			
		}
	})
}

    getRouteCreditDetails();
  



    function callBackDrawGraph(data, call) {
        var categories = [];
        var dataArray = [];
        var mix = [];
        var i = 0;
        var chartType = 'pie';        
       

        $.each(data,function(key,element){
                         
			categories[i] = key;
			dataArray[i] = element;
			mix[i] = new Array(element.routeName+'('+element.currency+')',parseFloat(element.routeClosingAmt)); //get value and percentage
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
		
		
    }


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

    function createHighchart(id,chartType,Title,dataArray,datas)
    { 
        
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
           	 	width:600,
           	 	height:600
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
                        	//console.log(this.point.options.y < 0);
                        	return (this.point.options.y < 0)?this.point.name+' : '+this.point.options.y:this.point.name+' : '+this.y
							//return this.point.name+' : '+this.y;
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
			   
			   	  if (this.y ==null) {
                    return this.name + ': ' + this.options.y;
                  } 
                  else
			   		return this.name +' : '+this.y;			   
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
				'#2e899b',
				'#781f1f',
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
				'#d2d2d2'				
            ],
            series:dataArray
        });
        
    }
</script>