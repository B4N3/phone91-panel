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
             <input type="text" id="search" placeholder="Dialplan" onkeyup="searchPlan()" />
            <div class="replaceBttn fl">
                <p title="Add" class="arBorder cmniner secondry fl cp primary">
               		 <span class="ic-16 add "></span>
               </p>
           </div>
    </div>
    <label class="searchAdd dn cmnClssBtn">
        <input type="text" id="planName" name="planName" placeholder="" class="fl" />             
        <input type="button" value="Add" class="btn btn-medium btn-primary clear" title="Add" onclick="addPlan()" >
    </label>
</div>
<!--//Quick Serach-->

<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec">
    <ul class="mngClntList dialplan" id="dialPlanUl" style="overflow: scroll;">
				<li onclick="" class="active">
					<div class="tariff">
							<h3 class="blackThmCrl">No Data Found</h3>
							
                                        </div>
                                </li>
              
             

      </ul>
</div>
<!--//Left Section-->    

<!--Right  Section-->   
<div class= "slideRight" id="rightsec">
</div>
<!--//Right Section-->


<script type="text/javascript">
$(document).ready(function()
{
			$('.slideLeft ul li, .reserrlerBtn').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "20px"}, "slow");
						$('.slideLeft').fadeOut('fast');
					}
			});
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

//jQuery(document).ready(function ($) {
//        "use strict";
//        $('#leftsec, .scrolll ').perfectScrollbar();
//});
//current();
toggleAddClose ();

function addPlan()
{
    var planName = $('#planName').val();
    console.log(planName);
    var regExr = /[^a-zA-Z0-9]+/;
    if(regExr.test(planName) || planName.length <3 || planName.length > 40 )
    {
        show_message("Error invalid plan name must be alpha numeric and not more then 40 characters.","error");
        return false 
    }
    $.ajax({
        url:"/controller/dialPlanController.php",
        type:"POST",
        dataType:"JSON",
        data:{"call":"addPlan","planName":planName},
        success:function(response){
            console.log(response);
            show_message(response.msg,response.status);
            if(response.status == "success")
            {
                var str = '<li onclick="window.location.href=\'#!dialplan.php|dialplan-setting.php?planId='+response.lastId+'\'" class="active">\
                                            <div class="tariff">\
                                                            <h3 class="blackThmCrl">'+planName+'</h3>\
                                                            <p>Country Wise</p>\
                                                            <p class="clear mrT1">\
                                                                <div class="funder"><span class="ic-24 delete cp" title="Delete" onclick="deletePlan(\''+response.lastId+'\',$(this))"></span></div>\
                                                                    No. of Prefix: <span class="font15 blackThmCrl">251</span>\
                                                            </p>\
                                              </div>\
                      </li>';
    
                      $('#dialPlanUl noData').remove();
                      $('#dialPlanUl').append(str);
            }
        }
    })
}

var _globalFirstPlanID = null;
function renderPlan(response)
{
    var str = "";
    _globalFirstPlanID = response.data[0].id;
    
    $.each(response.data,function(key,value){
                str += '<li onclick="liClick(event,\''+value.id+'\')" class="active">\
					<div class="tariff">\
							<h3 class="blackThmCrl">'+value.planName+'</h3>\
							<p>Country Wise</p>\
							<p class="clear mrT1">\
                                                            <div class="funder"><span class="ic-24 delete cp" title="Delete" onclick="deletePlan(\''+value.idd+'\',$(this))"></span></div>\
								No. of Prefix: <span class="font15 blackThmCrl">251</span>\
							</p>\
					  </div>\
        	  </li>';
                })
                return str;
           
}
function liClick(e,planId){
//console.log((e.target).attr('class'));
if($(e.target).hasClass('delete'))
{}
else
window.location.href='#!dialplan.php|dialplan-setting.php?planId='+planId+'';
}


function getPlan(search)
{
    var planName = $('#search').val();
//    if(/[^0-9a-zA-Z]+/.test(planName))
//    {
//        show_message("Error Invalid Plan Name only alpha numeric vlaues are allowed","error");
//        return false;
//    }
    
    if(search == 1)
        var data = 'call=getPlan&planName='+planName+'&search=1';
    else
        var data = 'call=getPlan';
    
    
    $.ajax({
        url:"/controller/dialPlanController.php",
        type:"POST",
        dataType:"JSON",
        data:data,
        success:function(response){
//            console.log(response);
            var str = "";
            if(response.status == "error")
            {
                str += '<li class="active">\
					<div class="tariff">\
							<h3 class="blackThmCrl noData">No Data Found</h3>\
					  </div>\
        	  </li>';
            show_message(response.msg,response.status);
            
            }else{
            
            
            str = renderPlan(response);
            console.log("dfasdfa"+_globalFirstPlanID);
            loadMoreDetail(2,response.count,"/controller/dialPlanController.php?call=getPlan",'renderPlan','dialPlanUl');
            $('#dialPlanUl').html(str);
            if(window.location.hash != "!dialplan.php|dialplan-setting.php?planId="+_globalFirstPlanID  && search != 1){
                $('#dialPlanUl li:first').trigger('click');
            }
        }
         }
    })
}
getPlan();

var _globalTimeOut = null;
function searchPlan()
{
    if(_globalTimeOut != null)
        clearTimeout (_globalTimeOut);
    _globalTimeOut = setTimeout(function(){
        getPlan(1);
    },600);
    
}

function deletePlan(planId,ths)
{
    var confirmFlag = confirm("Are you sure you want to delete this Dial Plan");
    if(confirmFlag == true)
    {
        $.ajax({
            url:"/controller/dialPlanController.php",
            type:"POST",
            dataType:"JSON",
            data:{"call":"deletePlan","dialPlanId":planId},
            success:function(response)
            {
                show_message(response.msg,response.status);
                if(response.status == "success")
                ths.parents('li').hide();
            }
        })
    }
}



</script>