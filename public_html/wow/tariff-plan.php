<?php 
include dirname(dirname(__FILE__)) . '/config.php';
if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}

?>

<!--Quick Serach-->
<div class="quicKseachsec  subPageSrch">
    <div class="quickSearch">
             <span class="ic-16 search icon" title="Search"></span> 
             <input type="text" id="search" placeholder="Search Tariff/Plan" onkeyup="searchPlanList($(this).val())"/>
              <div class="replaceBttn fl">
                      <p class="arBorder cmniner secondry fl cp primary " title="Add">
                            <span class="ic-16 add " id="addtariffbtn"></span>
                   </p>
           </div>
    </div>
    <div class="searchAdd dn cmnClssBtn" id="addPlanName">
    <form name="addPlanForm" id="addPlanForm" method="post">
        <input type="text" id="planNameAdmin" placeholder="Plan Name" name="planName"  class="fl isInput200"/>   
        <input type="hidden" id="idName" value="addPlanForm"/>   
        <select class="fl currn mrR1 currencyList" id="addPlanCurrency" name="outputCurr">
          	 <!--  do not remove currencylist class -->
          </select>    
          <input type="text" id="addPlanBillSec" name="billingSec" placeholder="Billing in Second"  class="fl isInput150 mrR1"/>        
          <input type="submit" name="addPlanFormSubmit" title="Add" class="btn btn-medium btn-primary clear" value="Add" />                  
        </form>
    </div>
    <div class="checkBoxWrp fl">
        <input type="checkbox" id="showAll" value="1" onclick="searchPlanList('')"/><label for="showAll">Show All</label></span>
    </div>
    
<!--//Quick Serach-->
</div>
<!--//Quick Serach-->


<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec" >
	<ul class="mngClntList" id="planList">
                
	</ul>
</div>	
<!--//Left Section-->    

<!--Right  Section-->   
<div class= "slideRight" id="rightsec">
</div>
<!--//Right Section-->

<script src="/js/reseller.js"></script>
<script type="text/javascript">
    var _globalTimeOut = null;
    
$(document).ready(function()
{
			$('.slideLeft ul li, .reserrlerBtn').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "20px"}, "slow");
						$('.slideLeft').fadeOut('fast');
					}
			});

// currencyList is global variable initialize in panel.js    
$('.currencyList').html(currencyList); 
});

	function toggleState(ths,type)
	{
		ths.toggleClass('redDisabl');
		if($('#changefunder'+type).val() == "uncheck")
			{
                            $('#changefunder'+type).val("check");
			}
			else
                        {
                            $('#changefunder'+type).val("uncheck");
                        }
        }
		
</script>

<script type="text/javascript">
    var pages = 1;  
var noDataFlag = 0;
//	  jQuery(document).ready(function ($) {
//		"use strict";
//		$('#leftsec, .scrolll ').perfectScrollbar({
//                    wheelPropagation: true
//                });
//	  });
 
function renderPlanList(response)
{
     
            var str="";
            if(response.allvalue == "" || response.allvalue == null || typeof response.allvalue ==='undefined')
            {
                str += "<li>No plan available please add a Plan</li>";
                noDataFlag = 1;
            }
            else
            {
                if(response.allvalue != null && typeof response.allvalue !=='undefined')
                {                                       
                    $.each(response.allvalue, function(i, item){
                       
                       str +=  '<li onclick="window.location.href=\'#!tariff-plan.php|tariff-plan-setting.php?tariffId='+item.id+'\'">\
                        <div class="tariff">\
                                        <h3 class="blackThmCrl">'+item.value+'</h3>\
                                       <!--<p>Total Plans: <span class="font15">60</span></p>-->\
                                       <div class="funder">\
									   		<span onclick="deletePlan(\''+item.id+'\')" title="Delete" class="ic-24 delete cp"></span>\
										</div>\
                                        <p class="clear mrT1">\
                                                <span class="fl blackThmCrl font15">'+item.currency+'</span>\
                                                <span class="fr">Billing: <span class="font15">'+item.billInSec+'</span></span>\
                                        </p>\
                            </div>\
        	  </li>';
                    })
                }
                           
            }
            return str;
}

function getPlanList(search,currentId)
{
    var showAll = 0;
   $("#showAll").is(':checked') ? showAll = 1 : ""; 
    $.ajax({
        url:"/controller/managePlanController.php?"+((typeof search == 'undefined' ||search == null )?"":search),
        type:"post",
        data:{"call":"managePlan","showall":showAll},
        dataType:"json",
        success: function(response)
        {
           var str = ""; 
            if(response.status == "error")
            {
              show_message(response.msg,response.status);
              return false;
                
            }
            
            
           pages = response.pages; 
           str = renderPlanList(response);
           
            loadMoreDetail(2,pages,"/controller/managePlanController.php?call=managePlan&showall="+showAll,'renderPlanList','planList');
           
            $('#planList').html(str);
            
            if((currentId == null || currentId == "undefined" || currentId == "") && noDataFlag != 1)
            {   
                currentId = response.allvalue[0].id;
//                    $('.planLabel').removeClass("selected");                   
//                    $('#liId_'+currentId).addClass("selected");                   
                window.location.hash='!tariff-plan.php|tariff-plan-setting.php?tariffId='+currentId+'';
            }
        }
    })
}




function searchPlanList(keyword)
{
//    var keyword = ths.val();

    if(/[^a-zA-Z0-9\s]+/.test(keyword))
    {
        show_message("Invalid Plan Name Please Enter Valid Data only alplanumeric value and space is allowed","error");
        return false;
    }
if(_globalTimeOut != null)
clearTimeout(_globalTimeOut);
_globalTimeOut=setTimeout(function(){ 

    if(keyword == "")
        getPlanList();
    else
        getPlanList("planName="+keyword+"&mnpln=search");
},600);
}
getPlanList();
//current();

var options = {
    url:"/controller/managePlanController.php",
    data:{"call":"addPlanAdmin"},
    type:"POST",
    resetForm:true,
    beforeSubmit:function(){
        console.log(validateAdminEditPlanForm('idName'));
        return validateAdminEditPlanForm('idName');},
    dataType:"JSON",
    success: function(response){
        console.log(response);
        show_message(response.msg,response.status);
        if(response.status == "success")
        {
            var str =  '<li onclick="window.location.href=\'#!tariff-plan.php|tariff-plan-setting.php?tariffId='+response.lastInsertedData.lastId+'\'">\
                            <div class="tariff">\
                                            <h3 class="blackThmCrl">'+response.lastInsertedData.planName+'</h3>\
                                           <!--<p>Total Plans: <span class="font15">60</span></p>-->\
                                           <span onclick="deletePlan(\''+response.lastInsertedData.lastId+'\')" title="Delete" class="ic-24 delete cp"></span>\
                                            <p class="clear mrT1">\
                                                    <span class="fl blackThmCrl font15">'+response.lastInsertedData.currency+'</span>\
                                                    <span class="fr">Billing: <span class="font15">'+response.lastInsertedData.billingSec+'</span></span>\
                                            </p>\
                                </div>\
                      </li>';
            $('#planList').prepend(str);   
            window.location.hash='!tariff-plan.php|tariff-plan-setting.php?tariffId='+response.lastInsertedData.lastId+'';
          
        }
    }
}
$('#addPlanForm').ajaxForm(options);
toggleAddClose ();


function deletePlan(itemId,random)
{
    var conf = confirm("If you delete this plan,it may affect other user calling.");
    if(conf == true)
    {
        $.ajax({
            url:"/controller/managePlanController.php?call=deletePlan",
            data:{tariffId:itemId},
            dataType:"JSON",
            success: function(response)
            {
                $('#liId_'+itemId).hide();
                show_message(response.msg,response.status);
                $('#rightsec').html('');
                if(response.status == "success")
                    window.location.hash="#!tariff-plan.php";

            }
        })
    }
}

</script>