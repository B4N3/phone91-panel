/**
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com> on 29/10/2013
 * @description It contains js code for admin panel
 */

/**
 * //change the heading for each page
 */
$('.headingName').click(function(){
    
    
    
    $('.headingTitle').html($(this).attr('title'));
    
    
});

/**
 * @added by Ankit Patidar <ankitpatidar@hostnsoft.com> on 30/10/2013
 * @param {string} call
 * @param {int} userId
 * @param {string} type
 * @returns {void}
 */
 function getDetails(call, userId, type)
    {
        
        //make request to get details
        $.ajax({
            url: "/controller/userCallLog.php",
            data: {call: call, 
                   userId: userId,
                   type: type},
            type: "post",
            dataType: "json",
            success: function(response)
            {    
                console.log(response)
                
                //console.log(response.via)
                
                //intialise arrays
                var categories = [];
                var dataArray = [];
                var mix = new Array();
                //counter
                var i = 0;
                
                //create array for categories and dataArray
                $.each(response,function(key,element){
                    
                    categories[i] = key;
                    
                    dataArray[i] = parseInt(element);
                    
                    mix[i] = new Array(key,parseInt(element));
                    i++;
                    
                });
                
                console.log(mix);
                
                var seriesP ;
                
                seriesP= [{
                   name: 'call status',
                   data:mix

               }];
           
               var seriesB;
             
             
               var seriesB= [{
                   name: 'call Status',
                   data: dataArray

               }];
           
           
           console.log(seriesB);
          //call function to draw graph in bar
                createHighchart('#getStatusDetailsBarGraphContainer','column','call status in bar chart',seriesB,categories);
        
          
           // console.log(seriesP);
               //function to draw graph with pie
               createHighchart('#getStatusDetailsPieGraphContainer','pie','call status in pie chart',seriesP,categories);
                
                //call function to create graph
               // callBackDrawGraph(response, call);
                //console.log(call)
                //console.log(JSON.stringify(response))

            }
        })
    }//end of function 
    
    
    /**
     * @added by Ankit Patidar <ankitpatidar@hostnsoft.com> on 30/10/2013
     * @param {object} data
     * @param {string} call
     * @returns {void}
     */
    function callBackDrawGraph(data, call) 
    {
        
        
        
        
//        var head = ["name", "value"];
//        var $table;
//        var caption;
        
        //conditions for particular graph
//        if (call === "getCallViaDetails") {
//             $table = json2table(data.via, call + "Tbl", head);
//             caption="<caption>Call Via</caption>";
//        }
//        if (call === "getStatusDetails") {
//             $table = json2table(data.via, call + "Tbl", head);
//             //caption="<caption>Call Status</caption>";
//        }
//        
//        if (call === "getResellerDurationDetails") {  //Reseller Grraph 
//             $table = json2table(data, call + 'Tbl', head);
//             caption="<caption>Reseller Duration</caption>";
//        }
//        if (call === "getResellerProfitDetails") {  //Reseller Profit Grraph 
//             $table = json2table(data, call + 'Tbl', head);
//             caption="<caption>Profit Details</caption>";
//        }
//        if (call === "getCreditGraphDetails") {
//             $table = json2table(data.data, call + 'Tbl', head);
//             caption="<caption>Credit</caption>";
//        }


//        console.log($table);
//        
//        console.log('table');
//        //$("#CallViaChart").append($table);
//        //var newdiv = $('<div>', {'id': call + 'GraphContainer',class:"graphDiv"});
//        //$("#CallViaChart").append(newdiv);
//        
//        //$('#'+call + 'Tbl').prepend(caption)
//        
//        $("#" + call + " Tbl").attr('data-graph-container', '#' + call +'PieGraphContainer');
//        
//        $("#" + call +" Tbl").attr('data-graph-container', '#' + call +'BarGraphContainer');
//        
//        //condition for pie
//        //if(call == 'getStatusDetailsPie')
//            $("#"+call+ " Tbl").attr('data-graph-type', 'pie');
//        //else //for bar chart
//            $("#" + call + "Tbl").attr('data-graph-type', 'chart');
        
//         if (call === "getCreditGraphDetails") {
//             console.log(data)
//             $("#" + call + "Tbl").attr('data-graph-type', 'column');
// //            $("#" + call + "Tbl").attr('data-graph-width', '1000');
//         }
//        $("#" + call + "Tbl").attr('data-graph-datalabels-enabled', '1');
//        $("#" + call + "Tbl").attr('data-graph-height', '300');
//        $("#" + call + "Tbl").highchartTable();
        //$("#CallViaChart table").hide();
    }
    
    
    
    
    function createHighchart(id,chartType,Title,dataArray,datas)
    {

        
        
        
    
    //jquery function to genrate graph    
 $(function () {
     
   
		   
        //highchart function with proper setting to genrate graph           
        $(id).highcharts({
            chart: {
                type: chartType
              //  height:height,
               // width:width
            },
            title: {
                text: Title
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
                pie:{
               allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        
                         formatter: function() {
                            //console.log(this);
//                             if(cType == "amount")
//                                return '<b>'+ this.point.name +'</b>: '+this.y+' Rs';
//                            else
                                return '<b>'+this.point.name+'<b>:'+this.y;
                            }
                        },
                     showInLegend: true
                }
            },
            legend: {
                //layout: 'vertical',
                //align: 'right',
                //verticalAlign: 'top',
                //x: 16,
                //y: 100,
               // floating: true,
               // borderWidth: 1,
                //backgroundColor: '#FFFFFF',
                //shadow: true
            },
            credits: {
                enabled: false
            },
            series:dataArray
        });
    });
    
        
    }