<?php
include_once("classes/plan_class.php");
$planObj = new plan_class();

if (!$funobj->login_validate() || !$funobj->check_reseller()) {
    $funobj->redirect("index.php");
}


if(!isset($_REQUEST['tariffId']))
{
    echo "please select a plan";
    die();
}
$planArr = $planObj->getPlanName('planName,outputCurrency', $_SESSION['id'],1,$_REQUEST['tariffId']);

?>
<div id="manage-plan-setting">
    
    <div id="mnpSettLeft">
        <h3 class="h3wico fl">
            <span class="fl" id="planNameSpan">Manage <?php echo $planArr['planName']; ?></span> 
            <input type="text" style="display:none;" name="planName" id="planName" value="<?php echo $planArr['planName']; ?>" onblur="changePlanName(<?php echo $_REQUEST['tariffId']; ?>,$(this))" />
				<!--<span class="fl">Manage <input type="text" name="planEdit" id="planEdit" value="<?php echo $planArr['planName']; ?>"
                 onblur="editPlanName($(this));"</span> -->            
        </h3>
		<i class="ic-24 edit cp mrL mrT" id="planEdit" onclick="planNameStateChange();"></i>
		<select name="" class="selectretail fr mrL" onchange="getTariffDetails($('#currentSelected').val(),$(this).val())">
			<option>100</option>
			<option>50</option>
			<option>25</option>
		</select>
		
		<input onclick="deleteAll();" type="button" name="deleteSelected" id="deleteSelected" value="Delete Selected" class="btn btn-mini btn-danger fr" title="Delete Selected" style="display:none;"/>
            
		<div class="clear"></div>	
        <p> Currency <?php echo $planArr['currency']; ?></p>        		
        

        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="" class="cmntbl boxsize mrT1">
            <thead>
                <tr>
                    <th width="10%"><input type="checkbox" id="checkAll" onchange="toggleCheckbox(this)" /></th>
                    <th width="18%"><input type="text" value="" placeholder="Country Code" onkeyup="searchTariff($(this))" style="width:100px" /></th>
                    <th width="17%"><input type="text" value="" placeholder="Country Name" onkeyup="searchTariff($(this))"/></th>
                    <th width="17%">Operator</th>
                    <th width="17%">Rate(<?php echo $planArr['currency']; ?>)</th>
                    <th width="17%">Action</th>
                </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
    </div>
    
    <form name="appendForm"   id="appendForm" method="post"  enctype="multipart/form-data" onsubmit="console.log(validateInsertRows());return validateInsertRows();">
        <input  type="hidden" name="tariffId" id="currentSelected" value=""  />
        <div id="mnpSettRight">
            <div class="h3wico"><span>Append</span></div>
            <div class="box-imp">
                <div id="upType" class="clear btnlbl">
                    <input type="radio" id="import" value="1" name="import" onclick="toggleDiv('imp-wrap','sel-wrap,#tariff_table');$('#file').addClass('required');" />
                    <label for="import">Import</label>
                    <input type="radio" id="select" value="2" name="import"  onclick="toggleDiv('sel-wrap','imp-wrap,#tariff_table')" checked="checked" />
                    <label for="select">Select</label>
                    <input type="radio" id="manual" value="3" name="import"  onclick="toggleDiv('tariff_table','imp-wrap,#sel-wrap')"  />
                    <label for="manual">Insert</label>
                </div>
                <div class="dn" id="tariff_table">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="cmntbl" id="trfTbl">
                        <tr>
                            <th width="25%">Country Code</th>
                            <th width="25%">Country Name</th>
                            <th width="25%">Operator</th>
                            <th width="25%">Rate</th>
                        </tr>
                        <tr>
                            <td><input type="text" value="" id="countryCode0" name="countryCode[]" class="editable"  /></td>
                            <td><input type="text" value="" id="countryName0" name="countryName[]" class=""/></td>
                            <td><input type="text" value="" id="operator0" name="operator[]" class=""/></td>
                            <td><input type="text" value="" id="rate0" name="rate[]" class=""/></td>
                        </tr>
                    </table>
                    <div class="commnPlus">
                        <a title="Add"  onclick="addRow();" href="javascript:void(0);" class="btn btn-mini btn-primary clear alC">
                            <div class="clear tryc tr1 addSpace">
                                <span class="ic-16 add"></span>
                            </div>
                        </a>
                    </div>
                    <input type="hidden" name="sizeOfRow" id="sizeOfRow" value="1" />
                </div>
                <div id="sel-wrap" class="sel-wrap">
                    <div class="fields">
                        <label>Select an Old Plan</label>
                        <select name="plantype" style="width:190px;" id="selPlan">
                            <option>Select</option>
                        </select>
                    </div>

                    <div class="clear mrT2">
                        <span class="fl mrR">
                            <input type="checkbox" name="planWith" onchange="$('#planValue').toggleClass('required number')"/>
                            &nbsp;&nbsp;With&nbsp;&nbsp;
                            <input type="text" style="width:50px;" name="planValue" id="planValue" />
                            &nbsp;&nbsp;%&nbsp;&nbsp;
                        </span>
                        <span class="funder" style="display:inline-block; float:left;">
                            <label onclick="toggleState($(this),'Sel');" for="changefunder" class="ic-sw fadder cp"></label>
                            <input type="checkbox" id="changefunderSel" style="display:none" checked="checked" name="selRateAction"  value="planInc"/>
                        </span>
                        <span id="incSel"class="fl mrL lh24">Increase Rate</span>
                    </div>
                </div>
                <div id="imp-wrap" class="dn">
                    <div class="fields bottom">
                        <label>Choose  Excel file</label>
                        <div class="fileWrap">
                            <input type="file" name="file" id="file"/>
                        </div>
                    </div>
                    <a class="themeLink f12" href="rateplan.xls">Download Sample Excel File</a>
                    <div class="clear mrT">
                        <span class="fl mrR">
                            <input type="checkbox" name="importWith" onchange="$('#importValue').toggleClass('required number');" />
                            &nbsp;&nbsp;With&nbsp;&nbsp;
                            <input type="text" style="width:50px;" name="importValue"  id="importValue"/>
                            &nbsp;&nbsp;%&nbsp;&nbsp;
                        </span>
                        <span class="funder" style="display:inline-block; float:left;">
                            <label onclick="toggleState($(this),'Imp');" for="changefunder" class="ic-sw fadder cp"></label>
                            <input type="checkbox" id="changefunderImp" style="display:none" checked="checked" name="rateAction" value="planInc" />
                        </span>
                        <span id="incImp"class="fl mrL lh24">Increase Rate</span>
                    </div>
                    <p class="mrT2 mrB">File Currency</p>
                    <div>
                        <select name="fileCurrency" style="width:190px;">
                            <option value="147">USD</option>
                            <option value="63">INR</option>
<!--                            <option value="48">GBP</option>-->
                            <option value="1">AED</option>
                        </select>
                    </div>
                    <input type="hidden" name="currentPlanCurrency" value="<?php echo $planArr['currency']; ?>"/>
                </div>
                <!--import wrap div end -->
                <div class="fields choice">
                    <input id="repAll" type="checkbox" name="rep" checked="checked" value="all"  />
                    <label for="repAll" style="margin-right:20px;">Replace All</label>
                    <!--<input id="repDup" type="radio" name="rep" value="dup"/>
                    <label for="repDup">Replace Duplicate</label>-->
                </div>
                <div class="fields">
				<!--<label>Output Currency</label>
                    <select name="currency" style="width:190px;">
                        <option value="147">USD</option>
                        <option value="63">INR</option>
                        <option value="48">GBP</option>
                    </select>
                </div>
                <div class="fields">
                    <label>Billing in Seconds</label>
                    <input type="text" name="billingSec" value=""  />
                </div>-->
                <input class="btn btn-medium btn-primary" value="Append" type="submit" name="append" title="Append" onclick="p91Loader('start')" />
            </div>
        </div>
		</div>
    </form>

	<div class="cl"></div>
</div>
<script type="text/javascript">
$(function() {
    $( "#upType" ).buttonset();
});

function onCheck(){
	var n = $('input:checked','#tbody').length;
	(n > 0) ? $('#deleteSelected').show() : $('#deleteSelected').hide();
}
			
function toggleCheckbox(ts){
	$('input:checkbox','#tbody').prop('checked',ts.checked);
	onCheck();
}

$(document).ready(function(){
        getTariffDetails('<?php echo $_REQUEST['tariffId']; ?>');
        selectPlan();
    $('#appendForm').ajaxForm(optionsEditPlan);
    
     $('.back').click(function() {
                        if ( $(window).width() <1024) {
                                $('.slideRight').animate({"right": "-1000px"}, "slow");
                                $('.slideLeft').fadeIn(2000);
                }
    });
});  
function planNameStateChange()
{
    $('#planName').toggle();
    $('#planNameSpan').toggle();
    
}
function changePlanName(tariffId,ths)
{
    var planName = $.trim(ths.val());
    console.log(planName);
    if((/[^a-zA-Z0-9\s@_-]+/.test(planName)) || (/[^0-9]+/.test(tariffId)) || tariffId == null || tariffId == "")
    {
        show_message("Invalid Plan Please Select a Plan First","error");
        return false;
    }
    $.ajax({
        url:"controller/managePlanController.php",
        type:"post",
        dataType:"json",
        data:{"call":"editPlanName","planName":planName,"tariffId":tariffId},
        success: function(response){
            console.log(response);
            show_message(response.msg,response.status);
            if(response.status == "success")
            {
                $('#planNameSpan').val(planName);
                planNameStateChange();
            }
        }
        
    })
}

function deleteTariff(id)
{
    
}

<?php if(isset($_REQUEST['tariffId'])) { ?>
    $('.planLabel').removeClass("selected");
    
    $('#liId_<?php echo $_REQUEST['tariffId']; ?>').addClass("selected");
<?php } ?>
</script>