<?php
//Include Common Configuration File First
include_once('config.php');
if (!$funobj->login_validate() || !$funobj->check_reseller()) {
    $funobj->redirect("index.php");
}
?>
<!--Reseller Add Plan-->
<a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<div id="addPlanWrap">
    <form name="addPlanForm" id="addPlanForm" method="post"  enctype="multipart/form-data" onsubmit="console.log(validateInsertRows());return validateInsertRows(); ">
        <div class="reSellerhead">Add Plan</div>
        <div class="fields">
            <label>Plan Name</label>
            <input type="text" name="planName" value="" />
        </div>
        <div class="fields">
            <label>Import or select tariff</label>
            <!--Box-->
            <div class="box-imp">
                <div id="upType" class="clear btnlbl">
                    <input type="radio" id="import" value="1" name="import" class="wrapRadio" onclick="toggleDiv('imp-wrap','sel-wrap,#tariff_table');$('#file').addClass('required')" />
                    <label for="import">Import</label>
                    <input type="radio" id="select" value="2" name="import" class="wrapRadio" onclick="toggleDiv('sel-wrap','imp-wrap,#tariff_table')" checked="checked" />
                    <label for="select">Select</label>
                    <input type="radio" id="manual" value="3" name="import" class="wrapRadio" onclick="toggleDiv('tariff_table','imp-wrap,#sel-wrap')"  />
                    <label for="manual">Manual Insert</label>
                </div>

                <!--First Tab content-->
                <div id="imp-wrap" class="dn">
                    <div class="fields">
                        <label>Choose csv or Excel file</label>
                        <div class="fileWrap">
                            <input type="file" name="file" id="file"/>
                        </div>
                    </div>
                    <a class="themeLink f12" href="rateplan.xls">Download Sample Excel File</a>

                    <div class="fields pdT">
                        <span class="fl mrR">
                            <input type="checkbox" name="importWith" class="incCheck" onchange="$('#importValue').toggleClass('required number')" />
                            &nbsp;&nbsp;With&nbsp;&nbsp;
                            <input type="text" style="width:50px;" name="importValue" id="importValue" />
                            &nbsp;&nbsp;%&nbsp;&nbsp;
                        </span> 
                        <span class="funder" style="display:inline-block; float:left;">
                            <label onclick="toggleState($(this),'Imp');" for="changefunder" class="ic-sw fadder cp"></label>
                            <input type="checkbox" id="changefunderImp" style="display:none" checked="checked" name="rateAction" value="planInc" />
                        </span>
                        <span id="incImp"class="fl mrL lh24">Increase Rate</span>
                    </div>

                    <div class="fields pdT">
                        <label>File Currency</label>
                        <select name="fileCurrency" style="width:190px;">
                            <option value="147">USD</option>
                            <option value="63">INR</option>
<!--                            <option value="48">GBP</option>-->
                            <option value="1">AED</option>
                        </select>
                    </div>
                </div>
                <!--//First Tab content-->


                <!--Second Tab content-->
                <div class="dn" id="tariff_table">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="cmntbl" id="trfTbl">
                        <tr>
                            <th width="25%">Country Code</th>
                            <th width="25%">Country Name</th>
                            <th width="25%">Operator</th>
                            <th width="25%">Rate</th>
                           <!--<td width="60px">&nbsp;</td>-->
                        </tr>
                        <tr>
                            <td><input type="text" value="" class="cntryCode" id="countryCode0" name="countryCode[]" class="" /></td>
                            <td><input type="text" value="" class="cntryName" id="countryName0" name="countryName[]" class=""/></td>
                            <td><input type="text" value="" class="operator" id="operator0" name="operator[]" class=""/></td>
                            <td><input type="text" value="" class="rate" id="rate0" name="rate[]" class=""/></td>
                        </tr>
                        <!--<tr>
                            <td colspan="3">
                            </td>
                        </tr>-->
                    </table>
                    <div class="commnPlus">
                        <a class="btn btn-mini btn-primary clear alC" href="javascript:void(0);" onclick="addRow();" title="Add">
                            <div class="clear tryc tr1 addSpace">
                                <span class="ic-16 add"></span>
                            </div>
                        </a>
                    </div>
                    <input type="hidden" name="sizeOfRow" id="sizeOfRow" value="1" />
                </div>
                <!--//Second Tab content-->


                <!--Third Tab content-->
                <div id="sel-wrap" class="sel-wrap">
                    <div class="fields">
                        <label>Select an Old Plan</label>
                        <select name="plantype" style="width:190px;" id="selPlan">
                            <option>Select</option>
                        </select>
                    </div>
                    <div class="clear mrT">
                        <span class="fl mrR">
                            <input type="checkbox" name="planWith"  class="incCheck" onchange="$('#planValue').toggleClass('required number')"/>
                            &nbsp;&nbsp;With&nbsp;&nbsp;
                            <input type="text" style="width:50px;" name="planValue" id="planValue"  />
                            &nbsp;&nbsp;%&nbsp;&nbsp;
                        </span>
                        <span class="funder" style="display:inline-block; float:left;">
                            <label onclick="toggleState($(this),'Sel');" for="changefunder" class="ic-sw fadder cp"></label>
                            <input type="checkbox" id="changefunderSel" style="display:none" checked="checked" name="selRateAction"  value="planInc"/>
                        </span>
                        <span id="incSel"class="fl mrL lh24">Increase Rate</span>
                    </div>
                </div>	
                <!--//Third Tab content--> 

            </div>
            <!--//Box-->
        </div>
        <div class="fields">
            <label>Output Currency</label>
            <select name="currency" style="width:190px;">
                <option value="147">USD</option>
                <option value="63">INR</option>
<!--                <option value="48">GBP</option>-->
                <option value="1">AED</option>
            </select>
        </div>
        <div class="fields">
            <label>Billing in Seconds</label>
            <input type="text" name="billingSec" value="" />
        </div>
        <button value="Add Plan" title="Add Plan" class="btn btn-medium btn-primary clear alC"  onclick="p91Loader('start')">
            <div class="tryc tr3">
                <span class="ic-16 add"></span>
                <span>Add Plan</span>
            </div>
        </button>
    </form>
</div>
<!--//Reseller Add Plan-->
<script type="text/javascript">
$(function() {
    $( "#upType" ).buttonset();
});
$(document).ready(function(){
    /* @author :sameer 
        * @desc :  resquest of fetching the list of plan to display in the select box
        */
    selectPlan();
    $('#addPlanForm').ajaxForm(optionsAddPlan);
    $('.back').click(function() {
                    if ( $(window).width() <1024) {
                        $('.slideRight').animate({"right": "-1000px"}, "slow");
                        $('.slideLeft').fadeIn(2000);
            }
    });

})          
</script>