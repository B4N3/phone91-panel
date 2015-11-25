<?php 

include_once('config.php');
if(preg_match(NOTNUM_REGX, $_REQUEST['callShopId']))
{
    die("Error invalid callshop Id ");
}
$response = $funobj->getuserName( $_REQUEST['callShopId']);

$param['userId'] = $_REQUEST['callShopId'];
$param['fieldName'] = "callRecord";
$param['type'] = 1;

$result = $funobj->getUserDetailsCallshop($param);

?>
<!--  Edit Call Shop-->
<div class="editCallShop">
  		<div id="tabs">
        <a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
    	<div class="head">Edit Call Shop</div>
        <ul>
                <li><a href="#tabs-1">Edit Call Shop</a></li>
                <li><a href="#tabs-2">Edit Funds</a></li>
        </ul>
  
      <!--Tab1 Callshop Edit dialog-->
      <div id="tabs-1" class="paddingInner">
           <div id="ecallshop" title="Edit Call Shop">
               <form name="editCallshop" id="editCallshop">
                    <div class="fields">
                        <label>Name of CallShop:</label>
                        <input type="text"  name="name" value="<?php echo $response; ?>" id="callshopName"/>
                        <input  type="hidden" value="<?php echo $_REQUEST['callShopId']; ?>" name="userId"/>
                        
                    </div>
                    <div class="fields">
                        <label>Tariff Id:</label>
                        <select class="callshopselectPlan selPlan" name="selPlan" id="selPlan">
                            <option>Tarrif</option>
                        </select>
                    </div>
                   
                    <div class="fields">
                        <input type="checkbox" name="callRecordStatus" id="callRecordStatus" value="1" <?php if(isset($result) && $result['callRecord'] ) { ?>checked="checked" <?php } ?> > Call Record
                    </div>
                   
                   <input title="Update" type="submit" class="btn btn-medium btn-primary" value="Update"/>
                </form>
              </div>
       </div>
       <!--//Tab1 Callshop Edit dialog-->
       
       <!--Tab2 Edit funds-->
      	<div id="tabs-2"><!--edit fund info-->
        <form id="editFundform" class="formElemt">
                
                <div class="innerSpace">
                        <p class="f12">Current available balance</p>
                        <h3 class="mrB2 userBalance">644444</h3>
                        <div id="sporow" class="clear"> 
                                <div class="fields">
                                        <label>Amount currency</label>
                                        <select name="fundCurrency" id="currency">
                                            <option value="174">USD</option>
                                            <option value="63">INR</option>
                                            <option value="1">AED</option>
                                        </select>
                                 </div>  
                                   <div class="fields">   
                                         <label>Add/Reduce Fund</label>
                                        <span class="funder">
                                                <label onclick="toggleState($(this),'EditFund');" for="changefunder" class="ic-32 bigfadder cp"></label>
                                                 <input type="checkbox" id="changefunderEditFund" name="changefunderEditFund" style="display:none" checked="checked" value="add" />
                                        </span>
                                      <input type="hidden" name="toUserEditFund" value="<?php echo $_REQUEST['callShopId']; ?>" id="toUserEditFund"/>
                                      <input type="text" placeholder="Amount" id="fundAmount" name="fundAmount"/>
                              </div>
                        </div>
                      <div class="fields noSpace"> 
                            <label>Balance</label>
                            <input type="text" id="balance" name="balance" class="clientBal"/>
                      </div>
                </div>
                <p class="borderMid"></p>
                <div class="innerSpace">
                            <div class="fields">
                                    <label>Payment Type</p></label>
                                    <p id="paymentType" class="clear btnlbl">
                                            <input type="radio" id="advance" name="pType" value="prepaid" onchange="showNext('partialWrap',false);" checked="checked" />
                                            <label for="advance" title="Advance">Advance</label>
                                            <input type="radio" id="partial" name="pType" value ="partial" onchange="showNext('partialWrap',true);" />
                                            <label for="partial" title="Partial">Partial</label>
                                            <input type="radio" id="credit" name="pType"  value ="postpaid" onchange="showNext('partialWrap',false);" />
                                            <label for="credit" title="Credit">Credit</label>
                                    </p>
                            </div>
                            <div id="partialWrap" class="dn clear">
                                   <div class="fields">
                                            <label>Partial Amount</label>
                                             <input type="text" id="partialAmt" name="partialAmt" />
                                    </div>
                                    
                                    <div  class="fields">
                                           <label>Currency</p></label>
                                            <select name="currency">
                                                <option value="147">USD</option>
                                                <option value="63">INR</option>
                                                <option value="1">AED</option>
                                            </select>
                                    </div>
                            </div>
                            <div class="fields">
                                    <label>Type (Cash, Memo, Bank)</label>
                                    <select name="fundPaymentType" id="fundPaymentType">
                                            <option value="Cash">Cash</option>
                                            <option value="Demo">Memo</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Other">Other</option>
                                    </select>
                            </div>
                            <div class="dn fields" id="otherPaymentType">
                                    <label>Enter Type</label>
                                   <input type="text" name="otherPaymentType"/>
                            </div>
                           <div class="fields">
                                    <label>Description</label>
                                     <textarea class="rn desc" id="fundDescription" name="fundDescription"></textarea>
              		    	</div>  
                            <input class="mrT btn btn-medium btn-primary"  type="submit" name="save" id="save" value="Save Changes" title="Save Changes"/>
              	  </div>
        </form>
    </div>
   	   <!--//Tab2 Edit funds-->
   </div>
</div>
<!--//Edit Call Shop-->
 <script type="text/javascript">
var _W, _H, _head, _lH, _lM, modH, _lW;
function Set(){
	_W = $(window).width(); //retrieve current window width
	_H = $(window).height(); //retrieve current window height
	_head = $('#header').outerHeight(true);//retrieve current header height
	_lH = _H - _head; //retrieve left height
	modH = _lH-120;
	_lW = _W - $('#callshopCnt #leftsec').outerWidth(true);
	//console.log(_lW);
	$('#callshopmain').css({height:_lH});
	$('#callshopCnt #leftsec, #callshopCnt #rightsec, .editCallShop').css({height:modH});
	//$('#callshopCnt #rightsec').css({width:_lW});
	//console.log(_lW);
}
$(function() {
	Set();
});
$(window).resize(function() {
	Set();
});

$(function() {
	$( "#tabs" ).tabs({
		create: function(event, ui) {console.log($('.ui-tabs-selected a',this).text())},
		select: function(event, ui) {
		console.log(ui.tab.innerText)}
	});
});
$(function() {
	$( "#paymentType, #BatchType" ).buttonset();
});
function showNext(id,status){
    if(status)
	$( "#"+id ).show();
    else
        $( "#"+id ).hide();
}
$(document).ready(function()
{
		$('.back').click(function() {
				if ( $(window).width() <1024) {
					$('.slideRight').animate({"right": "-1000px"}, "slow");
					$('.slideLeft').fadeIn(2000);
			}
		});
                selectPlan();
	});
function toggleState(ths,type)
{
    ths.toggleClass('bigfreducer');
    if($('#changefunder'+type).val() == "reduce")
    {
       $('#changefunder'+type).val("add");
    }
    else
    {
        $('#changefunder'+type).val("reduce");
    }
}
var options = { 
                     
    url:"action_layer.php?action=editFund",
    type:"post",
    dataType: 'json',
    beforeSubmit:  showEditFundRequest,  // pre-submit callback 
    success:function(text)
    {
        show_message(text.msg,text.status);
    }
};
$('#editFundform').ajaxForm(options); 
var editCallShopOptions = {      
    url:"controller/callShopController.php", 
    type:"post",
    data:{"call":"editCallshop"},
    dataType: 'json',
    beforeSubmit:  validateEditCallShopFrom,  // pre-submit callback 
    success:function(response)
    {
        show_message(response.msg,response.status);
        if(response.status == "success")
        {
            $('#callShopLi_<?php echo $_REQUEST['callShopId']; ?> h3').html($('#callshopName').val());
            $('#callShopLi_<?php echo $_REQUEST['callShopId']; ?> .detailCall .planName').html($('.callshopselectPlan option[value='+$(".callshopselectPlan").val()+']').text());
        }
    }
};
$('#editCallshop').ajaxForm(editCallShopOptions); 
                
function showEditFundRequest(formData, jqForm, options){
  $.validator.setDefaults({
  submitHandler: function() { console.log("submitted!"); }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#editFundform").validate({
                rules: {
                        fundAmount :{
                            required: true,
                            maxlength: 5,
                            number:true
                        },
                        balance :{
                            required: true,
                            maxlength: 5,
                            number:true
                        }
                        
                       }
        })
        
    })
//            $("#loading").show();
            if($("#editFundform").valid())
                    return true; 
            else
                    return false;
}



$(document).ready(function(){
    $('.userBalance').html($('#callShopLi_<?php echo $_REQUEST['callShopId']; ?> .bal').html());
    
    setTimeout(function(){$('.callshopselectPlan option[value="<?php echo $result['tariffId']; ?>"]').attr("selected","selected");}, 1000);
    
})

 </script>
 