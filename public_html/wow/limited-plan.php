<?php
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 25-feb-2015
 * @package Phone91
 * @details reseller limited plan page 
 */
//Include Common Configuration File First
include dirname(dirname(__FILE__)) . '/config.php';

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}

//get page number
if(isset($_REQUEST['pageNo']) && is_numeric($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;


$userId = $_SESSION['userid'];
?>

<!--Quick Serach-->
<div class="quicKseachsec  subPageSrch">
    <div class="quickSearch">
             <span class="ic-16 search icon" title="Search"></span> 
             <input type="text" name="searchBatch" id="searchBatch" onkeyup="SearchlimitedPlan($(this).val())"  placeholder="Search Plan" />
            <div class="replaceBttn fl">
                <a  onclick="PinDetailUrl();" title="Add" class="arBorder cmniner secondry fl cp primary">
               		 <span class="ic-16 add "></span>
               </a>
           </div>
    </div>
    <label class="searchAdd dn cmnClssBtn">
          <input type="text" id="search" placeholder="" class="fl" />             
          <input type="submit" value="Add" class="btn btn-medium btn-primary clear" title="Add" name="">
    </label>
    
</div>

<!--//Quick Serach-->

<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec">
    <ul class="mngClntList dialplan" id="limitedPlanList">
        
    </ul>
</div>
<!--//Left Section-->    

<!--Right  Section-->   
<div class= "slideRight" id="rightsec">
</div>
<!--//Right Section-->


<script type="text/javascript">
 var globalTimeout = null;
/* set active
planId: "458"
window['localStorage'] stores above value. this helps to get current state.
*/
var storage = window['localStorage'];
if(storage.getItem('pinId')){
	showPinDetail('#pinLi'+storage.getItem('pinId'),'');	
}

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 26-07-2013
//function use for show all pin detail by manage-pins.php file 
function showPinDetail(ths,e){
    $(ths).siblings().removeClass('active');
    $(ths).addClass('active');
		
    //get batchid
    var batchid = $(ths).attr('batchid');
    storage.setItem('pinId',batchid);
    //if previous event class is not "action" then batchid send to manage pins.php file for show batch detail   
//	
//	if(!e.target){
//		if($(ths).length > 0){
//			var top = $(ths).position().top;
//			$('.pinlist').scrollTop(top-100);		
//		}
//	}
	
    if(!$(e.target).hasClass('action')){
    $.ajax({
	    url : "manage-pin-details.php",
	    type: "POST",
	    data: {batchid:batchid} ,
	   success: function(text) {          
		  $("#rightsec").html(text);
                  
                  //get page no
                  var page = $('#pageNo').val();
                  
                  var totalPage = $('#totalPage').val();
                  
                  //get batch id
                  var batchId =$('#batchId').val();
       
                  //call function for pagianation
                  pinPagination(page,totalPage,batchId);
                  
		}
	});
    }
 }
 
 function SearchlimitedPlan(keyword){
     var value;
    var xhr;
    if( Object.prototype.toString.call( xhr ) === '[object Object]' ) {
        xhr.abort()
    }
    if(globalTimeout != null) 
    	clearTimeout(globalTimeout);
	globalTimeout=setTimeout(function(){
            
        
	 xhr =	$.ajax({
		   url : "/controller/adminManageClientCnt.php?action=getAllLimitedPlan",
		   type: "POST",
                   dataType:"json",
		   data: {data:keyword,value:value} ,
		   success: function(data) {
                       if(data.status == "success"){
			   var str = planlistDesign(data.limitedPlan);
                            $('#leftsec ul').html('');
			    $('#leftsec ul').html(str);   
                            $('#leftsec ul li').eq(0).click();
                       }
			}
		
	});
        
        },600)
}


SearchlimitedPlan('');
//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 12/08/2013
//function use for create design of batch pin list 
function planlistDesign(data)
{
    var str ='';

   
    var text = data;
    	
	$.each( text, function(key, item ) {
	 
            str += '<li class="" id="limitedLi'+item.planId+'" onclick="window.location.href=\'#!limited-plan.php|limited-planDetail.php?planId='+item.planId+'\'" batchid="'+item.planId+'" >\
	                <h3 class="ellp">'+item.planName+'</h3>\
	                <p class="dt">Rate :  '+item.tariffRate+' USD</p>\
	                <p class="mrT1">Minutes: '+item.minutes+' | Day Limit : '+item.daysLimit+'<span></p>\
                        <p class="mrT1">Hour Limit: '+item.hoursLimit+' | Call Limit : '+item.callsLimit+'<span></p>\
	                </li>';

	     });
	return str;

}

function showAllPin(){
    var keyword = $('#searchBatch').val();
    var value;
    $("#showAllPin").is(':checked')? value= 1 : value = 0;
    $.ajax({
		   url : "/action_layer.php?action=searchBatch",
		   type: "POST",
                   dataType:"json",
		   data: {data:keyword,value:value} ,
		   success: function(data) {
			   var str = batchlistDesign(data); 
			   $('#leftsec ul').html('');
			   $('#leftsec ul').html(str);    
	  
			}
		
	})
    
}



function PinDetailUrl(){
    
 window.location.hash = "";
 window.location.hash = "!limited-plan.php|addlimitedPlan.php";
 }

///var pages =  <?php //if(isset($pinData['pages'])) echo $pinData['pages'];else echo 1; ?>;
//if(pages == undefined || pages == '' || pages == null)
  // pages = 1;

 //call function for load more for manage clients
//loadMoreDetail(1,pages,'/action_layer.php?action=searchBatch&data='+$('#searchBatch').val(),'batchlistDesign','managePinList');


function pinPagination(start,count,batchId)
 {
     if(start == undefined || start == 0 || start== "" || start == null)
        start=1;
    
    if(count == undefined || count == 0 || count== "" || count == null)
        count = 1;
     
     if(count== 1)
         $('#pagination').hide();
     else
         $('#pagination').show();
     
     $(function() {
                    $('#pagination').paginate({
                        count       : count,
                        start       : start,
                        display     : 10,
                        border : true,
                        text_color: '#000',
                        background_color: '#ddd',
                        text_hover_color: '#fff',
                        background_hover_color: '#333',
                        images                  : false,
                        mouse                   : 'press',
                        page_choice_display     : true,
                        show_first              :true,
                        show_last               :true,
                        onChange                : function(page){
                         console.log(page);                           
                         //window.location.href= window.location.href.split('?')[0]+'?&pageNo='+page;
                         
                                    $.ajax({
                                        url : "manage-pin-details.php",
                                        type: "POST",
                                        data: {batchid:batchId,
                                               pageNo:page} ,
                                    success: function(text) {          
                                            $("#rightsec").html(text);

                                            //call function for pagianation
                                            pinPagination(page,count,batchId);

                                            }
                                    });
                              }
                        });
            });                                
                        
 }
</script>