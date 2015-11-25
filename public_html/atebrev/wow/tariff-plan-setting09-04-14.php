<?php
include_once("../classes/plan_class.php");
$planObj = new plan_class();

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}

if(!isset($_REQUEST['tariffId']))
{
    echo "please select a plan";
    die();
}
$planArr = $planObj->getPlanName('planName,outputCurrency,billingInSeconds ', $_SESSION['id'],1,$_REQUEST['tariffId'],1);

?>

<!--Secondary Index page menus-->
<div class="secondaryMenu" id="secondaryMenu">
 <div class="planRow"></div>
 	<h3 class="fl"><?php echo $planArr['planName']; ?></h3>
        <p class="arBorder fl cp sucsses edit" title="Add" onclick="toggleDiv('planrowEdit', 'secondaryMenu')">
         	<span class="ic-16 edit fl"></span>
     </p>
</div>
<!--Plan will visible on Click of Edit-->
<div class="planrowEdit dn" id="planrowEdit">
    <form name="editPlanAdmin" id="editPlanAdmin" method="post">
        <input  type="hidden" name="tariffId" id="currentSelected" value="<?php echo $_REQUEST['tariffId']; ?>"  />
        <input type="hidden" id="idNameEdit" value="editPlanAdmin"/>   
        <input type="text" name="planName" value="<?php echo $planArr['planName']; ?>" class="fl head"/>
 	<p class="arBorder fl cp subpage sucsses close" title="close" onclick="toggleDiv( 'secondaryMenu','planrowEdit')">
         	<span class="ic-16 close fl"></span>
     </p>    
     <span class="fl text">Output Currency</span>
        <select class="currency" id="opCurr" name="outputCurr">
                <option value="147">USD</option>
                <option value="63">INR</option>
                <!--<option value="48">GBP</option>-->
                <option value="1">AED</option>
        </select>
    <span class="fl text">Billing in Second</span>
    <input type="text" name="billingSec" value="<?php echo $planArr['billingInSeconds']; ?>" class="billSec"/>
        <input type="submit" title="Done" class="btn btn-mini btn-primary clear mrL bilbtn" value="Done" /> 
    </form>
</div>
<!--//Plan will visible on Click of Edit-->
<div id="formWrap"> 
    
    <form name="addPlanAdmin" id="addPlanAdmin" method="post" onsubmit="return validateInsertRows();">
    <div id="tabs" class="traffiBttn mrT2">
        <ul>
            <li class="none"><a href="#tabs-1" title="" onclick="addRowAdmin();$('#importInput').val(3);"><span class="ic-16 add"></span></a></li>
            <li class="none"><a href="#tabs-2" title="" onclick="browseSetting();"><span class="ic-16 leftIn"></span></a></li>
            <li><a href="#tabs-3" title="" onclick="selSetting();"><span class="ic-16 right"></span></a></li>
        </ul>
        <input name="import" value="" id="importInput" type="hidden" />
        <input  type="hidden" name="tariffId" id="currentSelected" value="<?php echo $_REQUEST['tariffId']; ?>"  />
        
	<!--1st Tabs Content-->
    <div id="tabs-1" class="pd">
        <div class="tablflip-scroll mrT dn tariff_table_div">
  		  <input name="sizeOfRow" value="0" id="sizeOfRow" type="hidden" />
      	 <table id="tariff_table" width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize grayTabl">
           <thead>
                    <tr>
                        <th width="10%">Country Code</th>
                        <th width="10%">Country</th>
                        <th width="10%">Operator</th>
                        <th width="22%" class="noBorder">Rate(AED)</th>
                        <th width="50%" class="noBorder">&nbsp;</th>
                    </tr>
                </thead>
              <tbody>
            </tbody>
        </table>
	</div>
        
    </div>
    <!--//1st Tabs Content-->
    
    <!--2nd Tabs Content-->
    <div id="tabs-2" class="mrB1 pr">
    		<input type="file" name="file" id="file" class="fileCab fl"/>
            <a href="/rateplan.xls" class="textAb" title="Download sample .xls file"><span class="fl  themeClr">Download sample .csv file</span></a>
            
           <div class="showDetls">
           		<p class="mrT1 fl mrR3">
                    <input type="checkbox" name="importWith" class="fl mrR" onchange="$('#importValue').toggleClass('required number');"/>
                    <span class="fl">With</span>
                </p>
                <span class="funder fl">
                        <label class="ic-60 enable cp"  for="changefunder" onclick="toggleState($(this),'Trans');"></label>
                        <input type="checkbox" checked="checked" style="display:none" id="changefunderTrans" name="rateAction" value="planInc" />
               </span>
                
                <span class="fl   mrR1  mrL1">
                    <input type="text" style="width:50px;" name="importValue"  id="importValue"/>
                    % Rate</span>
                <span class="fl  mrT1 mrR1 mrL1"><label>File Currency</label></span>
                <select class="small fl" name="fileCurrency" id="currency">
                    <option value="147">USD</option>
                    <option value="63">INR</option>
                    <option value="48">GBP</option>
                    <option value="1">AED</option>
               </select>
                <input type="hidden" name="currentPlanCurrency" value="<?php //echo $planArr['currency']; ?>"/>
                
           </div>
    </div>
    <!--//2nd Tabs Content-->
   
    <!--3rd Tabs Content--> 
    <div id="tabs-3"  class="pr clear pd">
    <div class="selectAb">
            <select name="plantype" id="selPlan" class="isInput150 fl select">
                <option>Select</option>
                <option>1</option>
            </select>
            <p class="mrT1 fl mrR3 mrL1 ">
                    <input type="checkbox" name="planWith" onchange="$('#planValue').toggleClass('required number')" class="fl mrR"/>
                    <span class="fl">With</span>
                </p>
        <span class="funder fl">
            <label onclick="toggleState($(this),'Sel');" class="ic-60 enable cp" for="changefunder"></label>
            <input type="checkbox" id="changefunderSel" name="selRateAction"  value="planInc" checked="checked" style="display:none" >
        </span>
                
            <span class="fl  mrL">
                <input type="text" style="width:50px;" name="planValue" id="planValue" />
                % Rate</span>
    </div>
    <!--//3rd Tabs Content--> 
 	</div>
    
    <div class="cmnReplace clear oh mrB2">
        <div class="fl mrT1" id="replaceDiv">
               <input type="checkbox" name="rep" id="repAll" value="all"  class="fl"/>
               <span class="fl">Replace All</span>
        </div>
        <input type="submit" class="mrL1 btn btn-medium btn-primary fl" name="append" id="save" value="Done" title="Done">
    </div>
    
	</div>
    </form>
</div>
 
 <div class="tablflip-scroll mrT clear">
   <table width="100%" cellspacing="0" cellpadding="0" border="0" id="clientTrstable" class="cmntbl  boxsize grayTabl">
            <thead>
                <tr>
                    <th width="10%">Country Code</th>
                    <th width="10%">Country</th>
                    <th width="10%">Operator</th>
                    <th width="22%" class="noBorder">Rate(<?php echo $planArr['outputCurrency']; ?>)</th>
                    <th width="50%" class="noBorder">&nbsp;</th>
                </tr>
            </thead>
            <tbody id="tbody">
      		 </tbody>
    </table>
     <div id="pagination"></div>
</div>

<script src="/js/reseller.js"></script>
<script type="text/javascript">
 $(function() {
	$( "#tabs" ).tabs({
		create: function(event, ui) {},
		select: function(event, ui) {}
	});
});
function toggleState(ths,type)
{
    ths.toggleClass('disable');
    if($('#changefunder'+type).val() == "reduce")
    {
       $('#changefunder'+type).val("add");
    }
    else
    {
        $('#changefunder'+type).val("reduce");
    }
}

function browseSetting()
{
    $('#importInput').val(1);
    $('#file').addClass('required ')
}
function selSetting()
{
    $('#importInput').val(2);
    $('#file').removeClass('required ');

}
function addRowAdmin()
{
    $('.tariff_table_div').show();
    /* @AUTHOR :SAMEER 
     * @DESC : ADD THE EXTRA ROW TO THE TABLE IN MANUAL ENTRY OPTION 
     */
    var i = $('#tariff_table tbody tr').eq($('#tariff_table tbody tr').size()-1).attr('rowIndex');
	if(i==undefined)
		i=1;
	else 
		i++;
	
    var row;
    row = '<tr rowIndex="'+i+'">\
            <td width="10%"><input type="text" value="" class="cntryCode isInput150" id="countryCode'+i+'" name="countryCode[]" class=""/></td>\
            <td width="10%"><input type="text" value="" class="cntryName isInput150" id="countryName'+i+'" name="countryName[]" class=""/></td>\
            <td width="10%"><input type="text" value="" class="operator" id="operator'+i+'" name="operator[]" class=""/></td>\
            <td class="noBorder"  width="22%"><input type="text" value="" class="rate" id="rate'+i+'" name="rate[]" class=""/>\
            <span class="ic-24 delete cp" title="Delete" onclick="removeRow($(this))"></span></td>\
                   <td class="noBorder" width="50%">&nbsp;</td>\
</tr>';
    
    $('#tariff_table tbody').append(row);
    /*UPDATE THE VALUE THIS IS USED TO ITERATE THROUGH ALL THE VALUED DURING INSERTION OF THE TARIFF*/
    $('#sizeOfRow').val((i));
}

function removeRow(ths)
{
    $('#sizeOfRow').val($('#sizeOfRow').val()-1);
    ths.closest('tr').remove();
}
var optionAddPalnAdmin = {
    url:"/controller/managePlanController.php?call=addPlan",
    type:"post",
    dataType:"json",
    clearForm:true,
    beforeSubmit:validateAdminAddPlanForm,
    success: function(response)
    {
        show_message(response.msg,response.status);
        if(response.status == "success")
            getTariffDetails(<?php echo $_REQUEST['tariffId']; ?>,20,'admin');
    }
}
$('#addPlanAdmin').ajaxForm(optionAddPalnAdmin);

getTariffDetails(<?php echo $_REQUEST['tariffId']; ?>,20,'admin');
selectPlan();
function hello(){console.log(2222222)}
var optionsEditPlanAdmin = {
        url:"/controller/managePlanController.php",
        data:{"call":"editPlanAdmin"},
        type:"post",
        dataType:"json",		
        beforeSubmit:function(){return validateAdminEditPlanForm('idNameEdit')},		
        success: function(response)
        {
            show_message(response.msg,response.status);
            getPlanList();
        }
}
    $('#editPlanAdmin').ajaxForm(optionsEditPlanAdmin);
    
$(document).ready(function(){
    $('#opCurr [value=<?php echo $planArr['currencyId']; ?>]').attr("selected","selected");
})
 </script>