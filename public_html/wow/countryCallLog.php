<html>
  <head>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
    <script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
    <script src="/js/highcharts.js"></script>
     <script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  

  <script>

 getCountryDetailForPie();
var country = [];
 google.load('visualization', '1', {'packages': ['geochart']});
 google.setOnLoadCallback(getCountryDetailsForMap);


 function drawMap(country) {
      console.log('entered!');
      var data = google.visualization.arrayToDataTable(country);

      var options = {
        width: 600,
        height: 600,
         dataMode:'regions',
        colorAxis: {
                    colors: ['33FF99','00FF80','00CC66','00994C','006633']},
        showLegend:false
        };

      // var options = {};
      // options['dataMode'] = 'regions';
      var map = new google.visualization.GeoChart(document.getElementById('getCountryDetailsGraphContainer'));
      console.log(data);
      console.log(options);
      map.draw(data, options);
  };


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

 

function showChartByDate(){

    
    getCountryDetailsForMap();
    getCountryDetailForPie();
}

 


function getCountryDetailsForMap()
{

    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();

    if(fromDate == undefined || fromDate == null || fromDate == '')
      fromDate= '';
  
  if(toDate == undefined || toDate == null || toDate == "")
      toDate= '';
    

  $.ajax({
    url: "/controller/userCallLog.php",
    data: {"call": "getCountryGraph",fromDate:fromDate,toDate:toDate},
    type: "post",
    dataType: "json",
    success: function(response)
    {
        console.log(response);
      if(response == null)
          return false;
        country = [];
        country.push(['Country', 'Answered','Failed']);
        //console.log(country);
        $.each(response.detail,function(key,value){

            country.push([value.country,parseFloat(value.Answered),parseFloat(value.Failed)]);
        });

        console.log(country);
        //sleep(800);
         drawMap(country);

    
    }
  })
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}


//getCountryDetailsForMap('2014/05/30','2014/06/30');
function getCountryDetailForPie()
{
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();

    if(fromDate == undefined || fromDate == null || fromDate == '')
      fromDate= '';
  
    if(toDate == undefined || toDate == null || toDate == "")
      toDate= '';


  $.ajax({
    url: "/controller/userCallLog.php",
    data: {"call": "getCountryPieDetail",fromDate:fromDate,toDate:toDate},
    type: "post",
    dataType: "json",
    success: function(response)
    {
      if(response == null)
        return false;
                    
      callBackDrawGraph(response,'getCountryPieDetails');
    }
  })
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
      mix[i] = new Array(element[0],parseFloat(element[1])); //get value and percentage
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
        
        console.log(series);   
    createHighchart('#'+call+'GraphContainer',chartType,call,series,categories);    
    
          
    }   
    



$(window).on('load',function(){
 $( "#fromDate,#toDate" ).datepicker({
     changeMonth: true,
     changeYear: true
      });
})

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
          $(function () {
              $(id).highcharts({
                  chart: {
                      plotBackgroundColor: null,
                      plotBorderWidth: null,
                      plotShadow: false,
                       type: chartType,        
                  },
                  title: {
                      text: 'country graph'
                  },
                  tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                  },
                  plotOptions: {
                      pie: {
                          allowPointSelect: true,
                          cursor: 'pointer',
                          dataLabels: {
                              enabled: true,
                              format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                              style: {
                                  color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                              }
                          }
                      }
                  },
                  series: dataArray,
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
              });
          });

    
        
    }


  </script>
  </head>
  <body>
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
  
</div>
<div class="dateSearch"> From:<input type="text" id="fromDate" name="fromDate" value="" class=""> To:<input type="text" id="toDate" name="toDate" value="" class=""/> <button id="showChart" class="" onclick="showChartByDate()">Go</button></div>
 
<div class="logWrap">
  <div class="logLeft">
    
    <div class="logGrid">
    
      <div class="cols2 col" style="width: 600px;height: 600px;">
        <div class="colInner">
         <div id="getCountryDetailsGraphTitle" class="gridTitle"></div>
          <div style="width: 592px;height: 532px;"  id="getCountryDetailsGraphContainer"></div>
        </div>
      </div>
      <div class="cols2 col" style="width: 600px;height: 600px;position: absolute;left: 613;top: 55px;">
        <div class="colInner">      
          
             <div id="getCountryPieDetailsGraphTitle" class="gridTitle"></div>
          <div id="getCountryPieDetailsGraphContainer"></div>
        
        </div>
      </div>    
    
      
    </div>
  </div>
  
</div>
<div id='pienew' style='width:500px;height:500px'></div>
  </body>
</html>