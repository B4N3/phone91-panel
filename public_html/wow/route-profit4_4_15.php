

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

<div class="dateSearch"> From:<input type="text" id="fromDate" name="fromDate" value="" class=""> To:<input type="text" id="toDate" name="toDate" value="" class=""/> <button id="showChart" class="" onclick="getRouteProfitDetails()">Go</button></div>

<div >
</br>
</div>

<div class="logRight">
  	<div id="resellerLog">
    	<div class="gridTitle"><h1>Statistics</h1></div>

			<div class="rlFields">
                <label><h2>Total Profit </h2></label>
                <span class="rlStrong" id="totalProfit">0</span>
            </div>

		</div>
    </div>
</div>

<div >
</br>
</div>

    <div class="logWrap">
        <div class="logLeft">

            <div class="logGrid" style="width: 100%; overflow: hidden;">

                <div class="cols2 col" style="width: 600px; float: left;">
                    <div class="colInner">
						<h3>  Profit </h3>
                        <div id="getRouteProfitDetailGraphTitle" class="gridTitle"></div>
                        <div id="getRouteProfitDetailGraphContainer"></div>
                    </div>
                </div>


                <div class="cols2 col" style="margin-left: 620px;">
                    <div class="colInner">
						<h3>  Loss </h3>
                        <div id="getRouteLossDetailGraphTitle" class="gridTitle"></div>
                        <div id="getRouteLossDetailGraphContainer"></div>
                    </div>
                </div>







            </div>
        </div>

    </div>
</div>
<script type="text/javascript">

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



//var detailsResponse;
    function getRouteProfitDetails()
    {

		var fromDate = $('#fromDate').val();
		var toDate = $('#toDate').val();
        $.ajax({
            url: "/controller/userCallLog.php",
            data: {"call": "getRouteProfitDetail", "fromDate":fromDate, "toDate":toDate},
            type: "post",
            dataType: "json",
            success: function(response)
            {
                if (response == null || response.length == 0)
                    return false;
                
                
                var profitDetail = new Array();
                var lossDetail = new Array();
				var profit = 0;
                $.each(response, function(key, element) {
                    if (element.routeClosingAmt >= 0) {
                        var arrayProfit = new Array();
                        arrayProfit['routeClosingAmt'] = element.routeClosingAmt;
                        arrayProfit['routeName'] = element.routeName;
                        arrayProfit['currency'] = element.currency;
                        profitDetail.push(arrayProfit);
						profit = parseFloat(profit) + parseFloat(element.routeClosingAmt);

                    } else {
                        var arrayLoss = new Array();
                        arrayLoss['routeClosingAmt'] = element.routeClosingAmt * -1;
                        arrayLoss['routeName'] = element.routeName;
                        arrayLoss['currency'] = element.currency;
                        lossDetail.push(arrayLoss);
                        console.log(parseFloat(profit));
						profit = (parseFloat(profit) - parseFloat(element.routeClosingAmt));
                                                console.log("loss"+profit);
                                                
                    }
                })
                callBackDrawGraph(profitDetail, 'getRouteProfitDetail');
                callBackDrawGraph(lossDetail, 'getRouteLossDetail');

				$('#totalProfit').html(profit);
            }
        })
    }

    getRouteProfitDetails();




    function callBackDrawGraph(data, call) {
        var categories = [];
        var dataArray = [];
        var mix = [];
        var i = 0;
        var chartType = 'pie';


        $.each(data, function(key, element) {

            categories[i] = key;
            dataArray[i] = element;
            mix[i] = new Array(element.routeName + '(' + element.currency + ')', parseFloat(element.routeClosingAmt)); //get value and percentage
            i++; //increament counter                    
        }); //end of each


        var series = [{//format for pie
                name: 'value',
                data: mix,
                dataLabels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            }];



        createHighchart('#' + call + 'GraphContainer', chartType, call, series, categories);


    }


    //highchart function with proper setting to genrate graph           
    function hideLegend(ts) {
        var legend = $('.highcharts-legend', ts.container);
        legend.hide();
    }
    ;
    function showHideLegend(ts) {
        var legend = $('.highcharts-legend', ts.container);
        if (legend.css("display") == 'inline' || legend.css("display") == 'block')
            legend.hide();
        else
            legend.show();
    }

    function createHighchart(id, chartType, Title, dataArray, datas)
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
                width: 600,
                height: 400
            },
            title: {
                text: ''
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    size: 150,
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            //console.log(this.point.options.y < 0);
                            return (this.point.options.y < 0) ? this.point.name + ' : ' + this.point.options.y : this.point.name + ' : ' + this.y
                            //return this.point.name+' : '+this.y;
                        }
                    },
                    showInLegend: true
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                floating: true,
                maxHeight: 300,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true,
                labelFormatter: function() {

                    if (this.y == null) {
                        return this.name + ': ' + this.options.y;
                    }
                    else
                        return this.name + ' : ' + this.y;
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
                        onclick: function() {
                            showHideLegend(this);
                        }
                    }
                }
            },
            credits: {
                enabled: false
            },
            colors: [
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
            series: dataArray
        });

    }
</script>
