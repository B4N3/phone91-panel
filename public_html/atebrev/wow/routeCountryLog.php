
<?php 



if(empty($_REQUEST['fromDate']))
  $fromDate = '2014/1/7';
else
  $fromDate = $_REQUEST['fromDate'];
if(empty($_REQUEST['toDate']))
  $toDate = '2014/3/7';
else
  $toDate = $_REQUEST['toDate'];

if(empty($_REQUEST['route']))
  $route = '';
else
  $route = $_REQUEST['route'];
?>
<html style="overflow:hidden;">
<head>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
    <script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
    <script src="/js/highcharts.js"></script>
     <script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  

  <script>

  var fromDate = '<?php echo $_REQUEST['fromDate']; ?>';
  var toDate = '<?php echo $_REQUEST['toDate']; ?>';
  var route = '<?php echo $route;?>';
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
        showLegend:true
        };

      
      var map = new google.visualization.GeoChart(document.getElementById('getCountryDetailsGraphContainer'));
      console.log(data);
      console.log(options);
      map.draw(data, options);
  };


function getCountryDetailsForMap()
{

   

    if(fromDate == undefined || fromDate == null || fromDate == '')
      fromDate= '';
  
  if(toDate == undefined || toDate == null || toDate == "")
      toDate= '';
    

  $.ajax({
    url: "/controller/userCallLog.php",
    data: {"call": "getCountryGraph",fromDate:fromDate,toDate:toDate,route:route},
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
	if(response.detail == undefined || response.detail == null)
	    return false;
	    
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
    // var fromDate = $('#fromDate').val();
    // var toDate = $('#toDate').val();

    if(fromDate == undefined || fromDate == null || fromDate == '')
      fromDate= '';
  
    if(toDate == undefined || toDate == null || toDate == "")
      toDate= '';


  $.ajax({
    url: "/controller/userCallLog.php",
    data: {"call": "getCountryPieDetail",fromDate:fromDate,toDate:toDate,route:route},
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
    



// $(window).on('load',function(){
//  $( "#fromDate,#toDate" ).datepicker({
//      changeMonth: true,
//      changeYear: true
//       });
// })

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
                       width:500,
                       height:400       
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
<div class="logWrap">
  <div class="logLeft">
    
    <div class="logGrid">
    
      <div class="cols2 col" style="width: 500px;height: 500px;position:relative;top:-77px">
        <div class="colInner">
         <div id="getCountryDetailsGraphTitle" class="gridTitle"></div>
          <div style="width: 592px;height: 532px;"  id="getCountryDetailsGraphContainer"></div>
        </div>
      </div>
      <div class="cols2 col" style="width: 500px;height: 400px;position: relative;left: 613;top: -500px;">
        <div class="colInner">      
          
             <div id="getCountryPieDetailsGraphTitle" class="gridTitle"></div>
          <div id="getCountryPieDetailsGraphContainer"></div>
        
        </div>
      </div>    
    
      
    </div>
  </div>
  
</div>
</body>
</html>

