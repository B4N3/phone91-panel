<?php
//Include Common Configuration File First
include_once('config.php');
if (!$funobj->login_validate() || !$funobj->check_reseller()) {
    $funobj->redirect("index.php");
}
?>
<div class="commHeader">
	<div class="showAtFront">		
			<a class="btn btn-medium btn-primary clear alC iconBtn fl mrR1 slideAndBack" href="#!reseller-manage-plan.php|reseller-add-plan.php" 
					title="Add New Plan">
				<span class="ic-24 addW"></span> 
				<span class="iconBtnLbl">Add New Plan</span> 
			</a>
			<div class="clear" id="srchrow">
			<input type="text" placeholder="search Plan" id="searchPlanUser" name="searchUser" onkeyup="searchManagePlan($(this))"/>
	<!--            <label>
				<p class="fl">Showing <span>1000</span> results by <span>latest</span> whose balance is less than</p>
				<p class="fl showInfo"> 
					<span class="ic-8 close"></span>
					<span class="fl">1000</span>
					<span class="ic-8 arrow"></span>
				<p class="rowClose fl cp" title="Close"><span class="ic-16 close"></span></p>
				</p>
			</label>-->
		</div>
	</div>
	<div class="showAtBack"><a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a></div>
</div>	
        
		
<div id="leftsec" class="slideLeft commLeftSec">
	<div class="innerSection">
		<ul class="ln mngPlnList commLeftList" id="planList">
		</ul>
        
	</div>
</div>


<div id="rightsec" class="slideRight commRightSec"> </div>
    
<script>
/* set active
planId: "458"
window['localStorage'] stores above values. this helps to get current state.
*/
var storage = window['localStorage'];

dynamicPageName('Manage Plans')

function loadPage(id, currency, e)
{
	$('.planLabel').removeClass('active');
	$("#planLi_"+id).addClass('active');		
	storage.setItem('planId',id);
	if(!$(e.target).hasClass('delIc'))
		window.location.hash='!reseller-manage-plan.php|reseller-manage-plan-setting.php?tariffId='+id+'';//&curr='+currency+'';
	if(!e.target){
		if($("#planLi_"+id).length > 0){
			var top = $("#planLi_"+id).position().top;
			$('.mngPlnList').scrollTop(top-100);		
		}
	}
}
    function getManagePlanDetails(search,currentId)
    {
        /* @author :SAMEER RATHOD
         * @desc : get the plan details form database and render it in the concern div 
         **/
        console.log(typeof search);
        $.ajax({
            /* the search condition is for, when user searches a plan or when a after fetching tariff or editing the tariff when we need to realod the manageplan div*/
            url:"controller/managePlanController.php?call=managePlan"+((typeof search == 'undefined' ||search == null )?"":search),
            dataType:"JSON",
            success: function(msg)
            {

                var str = createPlanDesign(msg);
                
                var pages;
                //validate pages
                if(msg.pages == undefined || msg.pages == '' || msg.pages == null)
                    pages = 1;
                 //call function for load more for plans
                loadMoreDetail(2,pages,'controller/managePlanController.php?call=managePlan','createPlanDesign','planList');

                $('#leftsec ul').html(str);
		slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');  		
                if(msg.status == "error" || msg.count == 0)
                {
                    show_message(msg.msg,msg.status);
                    return false;
                }                                
                      
				
				if(storage.getItem('planId')){
					loadPage(storage.getItem('planId'),'','')
				}
            }// end of succes braces 
        })// end of ajax 
    }    
	
    function deletePlan(itemId,random)
    {
        var conf = confirm("If you delete this plan,it may affect other user calling.");
        if(conf == true)
        {
            $.ajax({
                url:"controller/managePlanController.php?call=deletePlan",
                data:{tariffId:itemId},
                dataType:"JSON",
                success: function(response)
                {
                    $('#liId_'+itemId).hide();
                    show_message(response.msg,response.status);
                    $('#rightsec').html('');
                    if(response.status == "success")
                        window.location.hash="#!reseller-manage-plan.php";

                }
            })
        }
    }
    function toggleDelete(icId)
    {
    //                    $('#delIc_'+icId).toggle();
    }
    var _globalTimeOut = null;
    function searchManagePlan(ths)
    {
        var keyword = ths.val();
        
        if(/[^a-zA-Z0-9\s]+/.test(keyword))
        {
            show_message("Invalid Plan Name please Provide a valid data only alpha numeric and space is allowed","error");
            return false;
        }
            
        if(_globalTimeOut != null)
            clearTimeout(_globalTimeOut);
        
        _globalTimeOut = setTimeout(function(){
            
            getManagePlanDetails("&planName="+keyword+"&mnpln=search");
        },600);
    }
</script>
<script type="text/javascript">
    $(document).ready(function()
    {           
        $('.actwrap').hide();        
        getManagePlanDetails();
    });//end of document ready


/**
* added by Ankit Patidar <ankitpatidar@hostnsoft.com>
* @since 22/01/2014
* @uses function for create plan list design
*/
function createPlanDesign(msg)
{
    var str = '';
    
    //validate msg
    if(typeof msg != 'object')
        return str;
    
     if(msg.allvalue == "" || msg.allvalue == null || typeof msg.allvalue ==='undefined')
                {
                    str += "<li>No plan available please add a Plan</li>";
                    window.location.href='#!reseller-manage-plan.php|reseller-add-plan.php'
                }
                else
                {
                    if(msg.allvalue != null && typeof msg.allvalue !=='undefined')
                    {                                       
                        $.each(msg.allvalue, function(i, item){

                            str += "<li class='planLabel slideAndBack' id='planLi_"+item.id+"' onmouseover=\"toggleDelete('"+item.id+"')\" onclick=\"loadPage('"+item.id+"','"+item.currency+"',event);\">\
                            <h3 class='ellp'>"+item.value+"</h3>\
                            <p class='dt'>"+item.date+"</p>\
                            <div class='mnpInfo clear'>\
                                    <p>"+item.currency+"</p>\
                                <p>"+item.billInSec+"</p>\
                            </div>\
                            <div class='actwrap' title='Delete'>\
                                    <i class='ic-24 delR delIc' id='delIc_"+item.id+"' onclick=\"deletePlan('"+item.id+"',<?php echo rand(10, 99); ?>);\"></i>\
                            </div>\
                            </li>"

                        });
                    }
                }

    return str;

}

//get pages
//var pageNo = $('#planPageNo').val();

//if(pageNo == undefined || pageNo == '' || pageNo == null)
   //var pageNo = 2;
 
 //get total client pages
// var pages =  $('#planPages').val();
// if(pages == undefined || pages == '' || pages == null)
//    pages = 1;



//console.log($('#planPages').val());
 //call function for load more for manage clients
//loadMoreDetail(pageNo,3,'controller/managePlanController.php?call=managePlan','createPlanDesign','planList');

</script>

