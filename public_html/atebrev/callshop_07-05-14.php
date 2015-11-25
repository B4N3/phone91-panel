<?php
/**
 * @AUTHOR SAMEER RATHOD
 * @PACKAGE PHONE91
 * @DETAILS CALLSHOP PAGE
 */
#INCLUDE COMMON CONFIGURATION FILE FIRST
include_once('config.php');
if (!$funobj->check_reseller()) {
    $funobj->redirect("index.php");
}
?>
<div class="commHeader">
	<div class="showAtFront">
		<a href="javascript:void(0);" class="btn btn-medium btn-primary alC iconBtn fl mrR1" title= "Add New CallShop" id="addnewcallshop">
			<span class="ic-24 addW"></span>
			<span class="iconBtnLbl">Add New CallShop</span>
		</a>			
		<div class="fl" id="srchrow">            
			<input type="text" name="searchUser" id="searchUser" placeholder="search User" onkeyup="searchCallShop($(this).val())">
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
		<ul class="ln mngClntList pinsListing commLeftList" id="shopList">
	
	<!--                    <li class="group" onclick="window.location.href='#!callshop.php|callshop-active-call.php?clientId=<?php echo $clientDetails["id"]; ?>'">																	                                      <h3 class="ellp font15">
					<span class="fl">Name of CallShop</span>
					<a  title="Edit" class="fr" id="editCallShop" 
						href="#!callshop.php|edit-callshop.php?clientId=<?php echo $clientDetails["id"]; ?>'">
						<i title="Edit" class="ic-24 edit cp"></i>
					</a>
				</h3>
				<div class="detailCall">
					<p>Terrif ID</p>
					<p>123444.566</p>
				</div>
				<span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag(<?php echo $clientDetails["id"]; ?>);" ></span> 
			</li>-->
	
		</ul>
	</div>
</div>

<div id="rightsec" class="slideRight commRightSec">
</div>


<form id="add-Cshop" class="dn" title="Add New Call Shop" method="post" action="" >
	<div class="fields">
		<label>Name of CallShop:</label>
                <input type="text" name="userName" id="callShopUserName"/>
	</div>
	<div class="fields">
		<label>Tariff:</label>
		<select class="callshopsele" name="tariffId" id="selPlan">
			<option value="Select">Select</option>
			
		</select>

	</div>
	<div class="fields">
		<label>Balance:</label>
                <input type="text" name="balance" id="callShopBalance"/>
	</div>
	<input type="submit" class="btn btn-medium btn-primary isInput15 nrmLspace" value="Add" />

	<div class="thXmsg dn">Thank You,  New Callshop has been successfully Add...</div>
</form>

<script src="/js/callshop.js" type="text/javascript"></script>

<script type="text/javascript">
/* set active
planId: "458"
window['localStorage'] stores above value. this helps to get current state.
*/
var storage = window['localStorage'];

slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');

var optionCallShopForm = {
    url:"controller/callShopController.php",
    type:"post",
    beforeSubmit:validateCaLLShopForm,
    data:{
        "currencyId":"<?php echo $_SESSION['currencyId']; ?>",
        "call":"addCallShopUser"
    },
    dataType:"JSON",
    success: function(response)
    {
        show_message(response.msg,response.status);
        if(response.status =="success")
        {
            $("#add-Cshop").dialog('destroy');
            var  str = '<li id="callShopLi_'+response.userId+'"+ class="group callShopLi slideAndBack" onclick="callShopLiFunction('+response.userId+',$(this),event)">\
                                  <h3 class="ellp font15">\
                                        <span class="fl">'+$('#callShopUserName').val()+'</span>\
                                            <a  title="Edit" class="fr" id="editCallShop" href="#!callshop.php|edit-callshop.php?callShopId='+response.userId+'">\
                                            <i title="Edit" class="ic-24 edit cp"></i>\
                                            </a>\
                                      </h3>\
                                     <div class="detailCall">\
                                                <p>'+$('#selPlan>option:selected').text()+'</p>\
                                            <p class="bal">'+$('#callShopBalance').val()+'</p>\
                                     </div>\
                                    <span title="Delete" class="ic-24 actdelC cp callShopDelete" onclick="setdeleteFlag('+response.userId+',$(this));" ></span> \
                              </li>';
            
            $('#leftsec ul').append(str);
        }        
    }       
}
   
$('#add-Cshop').ajaxForm(optionCallShopForm);   


function callShopLiFunction(shopId,ths,event)
{
    if($(event.target).hasClass('callShopDelete'))
        return false;
    
	$('.callShopLi').removeClass('active');
    ths.addClass('active');
	
	if(!event.target){
		var top = ths.position().top;
		$('#shopList').scrollTop(top-100);		
	}	

	storage.setItem('shopId',shopId);
	window.location.href='#!callshop.php|callshop-active-call.php?shopId='+shopId;

}

function setdeleteFlag(userId,ths){
    
    var conBox = confirm("Are You Sure You want to delete this call shop");
    if(conBox == true)
    {
        var status = "block";

        $.ajax({
            url : "/controller/callShopController.php",
            type: "POST", 
            data:{
                "call":"setUserDeleteFlag",
                userId:userId,
                status:status
            },
            dataType: "json",
            success:function (text)
            {
                show_message(text.msg,text.status);
                ths.closest('li').hide();
                $('#leftsec ul li:first').trigger('click');
                
            }
        })
    }
  
}

  

function renderCallShopUser(response)
{
    var str = "";
    $.each(response,function(key,value){
                        var balance = value.balance;                        
                        balance = parseFloat(balance).toFixed(2);
                        str += '<li id="callShopLi_'+value.userId+'" class="group callShopLi slideAndBack" onclick="callShopLiFunction('+value.userId+',$(this),event)">\
                                  <h3 class="ellp font15">\
                                        <span class="fl">'+value.name+'</span>\
                                            <a  title="Edit" class="fr" id="editCallShop" href="#!callshop.php|edit-callshop.php?callShopId='+value.userId+'">\
                                            <i title="Edit" class="ic-24 edit cp"></i>\
                                            </a>\
                                      </h3>\
                                     <div class="detailCall">\
                                                <p>'+value.planName+'</p>\
                                            <p class="bal">'+balance+'</p>\
                                     </div>\
                                    <span title="Delete" class="ic-24 actdelC cp callShopDelete" onclick="setdeleteFlag('+value.userId+',$(this));" ></span> \
                              </li>';
    })//END OF EACH LOOP
    
    return str;
                                                        
}

function searchCallShop(keyword)
{
    var xhr;
     if( Object.prototype.toString.call( xhr ) === '[object Object]' ) {
        xhr.abort()
    }
    xhr = $.ajax({
        url:"/controller/callShopController.php",
        type:"POST",
        dataType:"JSON",
        data:{keyword:keyword,call:"searchCallShopUser"},
        success:function(response)
        {
           if(response != null && response!=='undefined' && response!="")
           {
            var str = "";
            str = renderCallShopUser(response);
            $('#leftsec ul').html(str);
			slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
           }//END OF IF
        }
    })
}

</script> 


<script type="text/javascript">
$(document).ready(function(){
		selectPlan();
        $.ajax({
            url:"controller/callShopController.php",
            type:"post",
            dataType:"JSON",
            data:{"call":"getCallShopUser"},
            success: function(response){
                if(response != null && response!=='undefined' && response!="")
                {
                    var str = "";
					str = renderCallShopUser(response);
					$('#leftsec ul').html(str);					
					//$('#leftsec ul li:first').trigger('click');
					slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
					
					if(storage.getItem('shopId')){
						callShopLiFunction(storage.getItem('shopId'),$('#callShopLi_'+storage.getItem('shopId')),'')
					}
                }
            }
        });
});
	
	
$(function() {
	$('#addnewcallshop').click(function(){
		$( "#add-Cshop" ).dialog({ modal: true, resizable:false, width:600, height:400});
	})
});
	
</script>

