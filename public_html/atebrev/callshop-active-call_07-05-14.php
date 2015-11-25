<?php
//Include Common Configuration File First
include_once('config.php');

if (!$funobj->login_validate() || !$funobj->check_reseller()) 
{
    $funobj->redirect("index.php");
}

$param['userId'] = $_REQUEST['shopId'];
$param['fieldName'] = "callRecord";

$result = $funobj->getUserDetailsCallshop($param);

?>

<div id="callshopWrap" class="Wrap">		
        <div id="callshopListWrap" class="clear">
            <ul class="ln cmnli-callshop clear" id="cmnli-callshop">
            	<li id="addshop">
                    <h3>+ Add New</h3>
                </li>
                
<!--            	<li onclick="">
                      <div class="fixHight"> 
                          <h3 class="clear">
                                <span class="ellp">The Rock</span>
                                <i class="ic-24 edit cp" title="Edit"></i>
                        </h3>
                        <p class="tm">43:33min | 0.025USD/min</p>
                        
                        <h3 class="mrT2">1234567890</h3>
                        <p class="dt">5/8/2011 8:31:58 PM</p>
                    </div>
                    <div class="line"></div>
                    
                    <div class="clear">
                            <a href="javascript:void(0)" class="btn btn-mini btn-danger alC" title="Stop">
                                <div class="clear tryc">
                                    <span class="ic-16 stop"></span>
                                    <span>Stop</span>
                                </div>
                            </a>
                       		<span class="prov">Skype</span>
                   </div>
                </li>-->
            <?php 
//            for($i = 1; $i <= 1; $i++)
//			
//			echo'
//                
//				
//				<li onclick="">
//                      <div class="fixHight"> 
//						  <h3 class="clear">
//							<span class="ellp">The Rock</span>
//							<i class="ic-24 edit cp" title="Edit"></i>
//						</h3>
//						<p class="tm">43:33min | 0.025USD/min</p>
//						<h3 class="mrT2">1234567890</h3>
//						<p class="dt">5/8/2011 8:31:58 PM</p>
//					</div>
//                    <div class="line"></div>
//                    <div class="clear">
//                        <a href="javascript:void(0)" class="btn btn-mini btn-inverse alC" title="Reset">
//                            <div class="clear tryc">
//                                <span class="ic-16 refresh"></span>
//                                <span>Reset</span>
//                            </div>
//                        </a>
//                        <span class="prov f">3 Calls</span>
//						<span class="sepCall">|</span>
//						<span class="prov">Total <span style="font-size:20px;">25</span>USD</span>
//						<span class="prov fr"><a href="javascript:void(0)" class="themeLink summary" title="Summary">Summary</a></span>
//                    </div>
//                </li>
//            	';
			?>
            </ul>
        </div>

<!--    	<div id="pagiwrap">
        	pagination come in this div
        </div>-->
    	
        <!--callshop dialog start this popup will show on click of add or edit callshop-->
        <div id="add-shop-dialog" class="dn" title="Add System">
            <div id="add-shop-inner">
            	<div class="col-5">
              		<div class="addform"> 
                        <form name="addSystemForm" id="addSystemForm" action="" method="post">
                    		<p>System Name</p>
                                <input type="text" value="" name="systemName" id="systemName" class="mrB1"/>
                            <div class="rdrow"><input type="radio" id="sip" name="sysID" value="0" checked="checked" onclick="sipSetting();$('#submitButtonCS').show();$('#submitButtonCS').val('Add System');"/><label for="sip">Sip</label></div>
                            <div class="rdrow"><input type="radio" id="messenger" name="sysID" value="1" onclick="messengerSetting();$('#submitButtonCS').show();$('#submitButtonCS').val('Add System');"/><label for="messenger">Messenger Id</label></div>

                            <div id="messengerDiv" style="display: none">                            
                            <input id="messengerId" name="messengerId" type="text" value="" placeholder="Select id  &rarr;" readonly="readonly"/>
                            <input id="messengerType" name="messengerType" type="hidden" value="" />
                            </div>
                            
                            <input type="checkbox" name="callRecordStatus" id="callRecordStatus"> Call Record Status
                            
                            <input  type="hidden" name="callShopId" value="<?php echo $_REQUEST['shopId']; ?>" />                            
                            <input type="submit" id="submitButtonCS" class="mrT1 btn btn-medium btn-primary alC" title="Save Changes" value="Add System"/>
                        </form>
                    </div>
                </div><!--/end first col child-->
                
                <div id="addShopIdList" class="col-5">
                    <div class="wrp">
                        <div class="pdL2">
                            <p>Select ID for <strong id="sysName">...</strong></p>
                            <div class="">
                                <input type="text" value="" id="searchMessengerId" placeholder="Search messenger id" />
                            </div>
                        </div>
                        
                        <ul class="ln srchrslt" id="srchrslt" >

                        </ul>
                        
                    </div>    
                </div><!--/end last col child-->
        	</div><!--/end call shop inner-->
        </div>
        <!--/end call shop div-->
        <!--callshop dialog start this popup will show on click of add or edit callshop-->
        <div id="edit-shop-dialog" class="dn cmnnEle" title="Edit System">
            <div id="edit-shop-inner">
            	<div class="col-5">
              		<div class="editform addform"> 
                        <form name="editSystemForm" id="editSystemForm" action="" method="post">
                    		<p>System Name</p>
                                <input type="text" value="" name="systemName" id="systemNameEdit" class="mrB1"/>
<!--                            <p class="nameSys">System Name</p>-->
                            <div class="rdrow">
								<input type="radio" id="sipEdit" name="sysID" value="0" checked="checked" onclick="sipSettingEdit();"/><label for="sipEdit">Sip</label>
							</div>
                            <div class="rdrow">
								<input type="radio" id="messengerEdit" name="sysID" value="1" onclick="messengerSettingEdit();"/><label for="messengerEdit">Messenger Id</label>
							</div>
<!--                            <div class="rdrow"><input type="radio" id="vtalk" name="sysID" /><label for="vtalk">Vtalk</label></div>-->
<!--                            <div id="genDiv" class="dn">
                                <input type="button" class="mrT2 btn btn-medium btn-primary alC" title="Generate" value="Generate"/>
                            </div>-->
                            <div id="sipDivEdit" >
                                <p class="nameSys">Sip User Name</p>
                                <input id="sipUserNameEdit" type="text" name="userName" value="" readonly="readonly" />
                                <p class="nameSys">Sip Password</p>
                            	<input id="sipPasswordEdit" name="password" type="text" value="" />
                            </div>
                            <div id="messengerDivEdit" style="display: none;">                               
                                    <input id="messengerIdEdit" name="messengerId" type="text" value="" placeholder="Select id  &rarr;" readonly="readonly"/>
                                    <input id="messengerTypeEdit" name="messengerType" type="hidden" value="" />
                            </div>
                            <input  type="hidden" name="systemId" value="" id="systemIdEdit" />                            
                            <input  type="hidden" name="callShopId" value="<?php echo $_REQUEST['shopId']; ?>" />                            
                            <input type="submit" id="submitButtonCSEdit" class="mrT1 btn btn-medium btn-primary alC" title="Generate" value="Save changes"/>
                        </form>
                    </div>
                </div><!--/end first col child-->
                
                <div id="addShopIdList2" class="col-5">
                    <div class="wrp">
                        <div class="pdL2">
                            <p>Select ID for <strong id="editSysNameLbl">...</strong></p>
                            <div class="">
                                <input type="text" value="" id="searchMessengerId2" placeholder="Search messenger id" />
                            </div>
                        </div>
                        
                        <ul class="ln srchrslt" id="srchrslt2" >
<!--                            <li>
                            	<p>Skype</p>
                                <h3>The Rock</h3>
                            </li>
                            -->
                        </ul>
                        
                    </div>    
                </div><!--/end last col child-->
        	</div><!--/end call shop inner-->
        </div>
        <!--/end call shop div-->
        
        <!--summary show popup start from here-->
        <div id="summary-dialog" class="dn" title="Summary">
        	<div id="summary-inner">
                    <h3 id="summaryShopName">Sudhir Pandey</h3>
                <div id="summaryCallCost"></div>
                
                <ul class="ln cmnli-summary" id="summaryUl">
                    <li>
                    	<h3>989235468</h3>
                        <p>1/5/2011 6:32:50 A</p>
                        <div class="clear lir">
                        	<p>43:33min</p>
                            <p><span class="ratebig">10.25</span>USD</p>
                        </div>
                    </li>
                    <li>
                    	<h3>989235468</h3>
                        <p>1/5/2011 6:32:50 A</p>
                        <div class="clear lir">
                        	<p>43:33min</p>
                            <p><span class="ratebig">10.25</span>USD</p>
                        </div>
                    </li>
                    <li>
                    	<h3>989235468</h3>
                        <p>1/5/2011 6:32:50 A</p>
                        <div class="clear lir">
                        	<p>43:33min</p>
                            <p><span class="ratebig">10.25</span>USD</p>
                        </div>
                    </li>
                </ul>
                
        	</div>
        </div><!--summary show popup end -->
</div><!--/end call log wrap-->
<script type="text/javascript" src="js/base64.js"></script>
<script>
$('#systemName').keyup(function(){
	var sysName = $(this).val();
	if(sysName == '') sysName ='...';
	$('#sysName').html(sysName);
})
$('#systemNameEdit').keyup(function(){
	var sysName = $(this).val();
	if(sysName == '') sysName ='...';
	$('#editSysNameLbl').html(sysName);
})
$("input:radio[name=sysID]").click(function() {
    var value = $(this).val();
	if(value == 1)
		$('')
});

function sipSetting()
{
	$('#messengerDiv').hide();
    $('#messengerId').removeClass('required');    
	$('#submitButtonCS').val('Generate');
	$('#addShopIdList').hide();
}

function messengerSetting()
{
    toggleDiv('messengerDiv','sipDiv');    
    $('#messengerId').addClass('required');
    $('#sipPassword').removeClass('required');
    $('#messengerIdEdit').addClass('required');
	$('#submitButtonCS').val('Save Changes');
	$('#addShopIdList').show();	
}

function sipSettingEdit()
{
	toggleDiv('sipDivEdit','messengerDivEdit');    
    $('#messengerIdEdit').removeClass('required');		
	$('#addShopIdList2').hide();
    $('#submitButtonCSEdit').val('Save Changes');
}

function messengerSettingEdit()
{
  	toggleDiv('messengerDivEdit','sipDivEdit');    
    $('#messengerIdEdit').addClass('required');
	$('#submitButtonCS').val('Save Changes');	
	$('#addShopIdList2').show();
	$('#submitButtonCSEdit').val('Save Changes');
        
}


$.ajax({
    url:"controller/callShopController.php",
    type:"post",
    data:{"call":"getVerifiedEmailId"},
    dataType:"JSON",
    success: function(response){
        
       
        var str = "";
        
        $.each(response,function(key,value){
            str += '<li onclick="setMessengerId(\''+value.email+'\','+value.type+')">\
                        <h3>'+value.email+'</h3>\
                    </li>';
        })
        $('#srchrslt').html(str);
        $('#srchrslt2').html(str);
        
        $('#searchMessengerId').quicksearch('#srchrslt li');
        $('#searchMessengerId2').quicksearch('#srchrslt2 li');
    }
})


    var addSystemOptions = {
        url:"controller/callShopController.php",
        type:"post",
        data:{"call":"addSystem"},
        beforeSubmit:validateAddSystemForm,
        dataType:"JSON",
        success: function(response){

            show_message(response.msg,response.status);
            $( "#add-shop-dialog" ).dialog('destroy');
            <?php if(isset($_REQUEST['shopId']))
            { ?>
            getcalls('<?php echo $_REQUEST['shopId']; ?>');
            <?php } ?>
        }
    }
    $('#addSystemForm').ajaxForm(addSystemOptions);
    
    
    var editSystemOptions = {
        url:"controller/callShopController.php",
        type:"post",
        data:{"call":"editCallShopSystemDetails"},
        beforeSubmit:validateEditSystemForm,
        dataType:"JSON",
        success: function(response){

            show_message(response.msg,response.status);
            $( "#add-shop-dialog" ).dialog('destroy');
        }
    }
    $('#editSystemForm').ajaxForm(editSystemOptions);

function setMessengerId(id,type)
{
    $('#messengerId').val(id);
    $('#messengerType').val(type);
    $('#messengerIdEdit').val(id);
    $('#messengerTypeEdit').val(type);
}


function editSystemDetails(systemId,callShopId)
{
    $.ajax({
        url:"/controller/callShopController.php",
        data:{"call":"getCallShopSystemDetails","callShopId":callShopId,"systemId":systemId},
        type:"post",
        dataType:"json",
        success: function(response){
            $('#editSystemForm input').not(':button,:hidden,:submit').val("");
            
            if(response == null)
            {
                show_message("No system found","error");
                return false;
            }
            $('#systemIdEdit').val(systemId);
            $('#systemNameEdit').val(response.systemName);
            if(response.type == 0)
            {
                $('#sipEdit').attr("checked",true);
                $('#sipDivEdit').show();
                $('#messengerDivEdit').hide();
                $('#sipUserNameEdit').val(response.userName);
                $('#sipPasswordEdit').val(response.password);
                $('#submitButtonCSEdit').val('Save Changes');
            }
            else if(response.type == 1)
            {
                
                $('#messengerTypeEdit').val(response.messengerType);
                $('#messengerEdit').attr("checked",true);
                $('#messengerDivEdit').show();
                $('#sipDivEdit').hide();
                $('#messengerIdEdit').val(response.messengerId);
                $('#submitButtonCSEdit').val('Save Changes');
            }
			$('#editSysNameLbl').html(response.systemName);
            $( "#edit-shop-dialog" ).dialog({ modal: true, resizable:false, width:600, height:445});
            
        }
    })
}
function getcalls(shopId)
{
   callShopId = shopId;
   systemArr = [];
 $.ajax({
     url:"controller/callShopController.php",
     type : "post",
     data:{"call":"getCallShopCallDetails","callShopId":callShopId},
     dataType:"JSON",
     success: function(response){
         
        if(response != null)
        {
            
        
        var str = '<li id="addshop">\
                    <h3 class="cp" >+ Add New</h3>\
                </li>';
            
            
        var i = 0;
         $.each(response,function(key,value){
             
             systemArr[i] = value.systemId;
            i++;
             var callduration = value.duration;
             callduration = (callduration > 59 ? ((callduration/60).toFixed(2).replace(".",":")) : ("00:"+callduration));
            str += '<li onclick="">\
                      <div class="fixHight"> <h3 class="clear">\
                        <span class="ellp">Unknown</span>\
                        <!-- <i class="ic-24 edit cp" title="Edit"></i> -->\
                    </h3>\
                    <p class="tm">'+callduration+' min | 0.025USD/min</p>\
                    <h3 class="mrT2">'+value.dialed_number+'</h3>\
                    <p class="dt">'+value.call_dial+'</p></div>\
                    <div class="line"></div>\
                    <div class="clear">\
                        <a href="javascript:void(0)" class="btn btn-mini btn-danger alC" title="Stop">\
                            <div class="clear tryc">\
                                <span class="ic-16 stop"></span>\
                                <span>Stop</span>\
                                        </div>\
                        </a>\
                        <span class="prov">'+value.call_type+'</span>\
                    </div>\
                </li>';                         
         })
         $('#cmnli-callshop').html(str);

         
        
        }else
        {
            show_message('No active systems in call shop',"error");
            
        }
        getCallShopSummary(callShopId,2,systemArr);

//         $('#addshop').unbind('click').click(function(){
//             
//			$( "#add-shop-dialog input" ).not(':button,:hidden,:submit').val("");
//			$( "#add-shop-dialog" ).dialog({ modal: true, resizable:false, width:600, height:445,close:function(event,ui){
//                                $(this).dialog('destroy');
//                                $(this).remove();
//                        }});
//		})
//		
//		 $('.summary').unbind('click').click(function(){
//			$( "#summary-dialog" ).dialog({ modal: true, resizable:false, width:600, height:500,close:function(event,ui){
//                                $(this).dialog('destroy');
//                                $(this).remove();
//                        }});
//		})
     }
 })   
}
<?php if(isset($_REQUEST['shopId']))
{ ?>
getcalls('<?php echo $_REQUEST['shopId']; ?>');
<?php } ?>
   
   
function getCallShopSummary(callShopId,type,systemIdArr,systemId,systemName)
{
    $.ajax({
        url:"/controller/callShopController.php",
        type:"post",
        dataType:"json",
        data:{"call":"getCallShopSummary","callShopId":callShopId,"type":type,"systemId":systemId},
        success: function(response)
        {
             $('#summaryUl').html("");
             $('#summaryShopName').html("No Calls Yet");
             $('#summaryTotalCall').html("0 calls");
            if(response != null)
            {
                
            var blank = [];
            blank[0] = "";
            var str = '';
        
            if(type == 1)
            {
				var i = 0;
                var totalCost = 0;
                var currency = "";
                $.each(response, function(key,value){
                    i++;
                    
                    var duration = value.duration;
                    duration = (duration > 59 ? ((duration/60).toFixed(2).replace(".",":")) : ("00:"+duration)); 
                    currency = value.currency;
                    
                    str += '<li><div class="summaryWrp"\
                    	<h3>'+value.dialedNumber+'</h3>\
                        <p>'+value.date+'</p>\
                        <div class="clear lir">\
                        	<p>'+duration+' min</p>\
                            <p><span class="ratebig">'+value.cost+'</span> '+currency+'</p>\
                        </div>\
                    </div></li>';
                    totalCost += parseFloat(value.cost);                     
                })
				var call = (i.length == 1)? ' Call' : ' Calls';
                var callcost = '<span>'+i+'</span>'+call+' <span>'+totalCost+'</span> '+currency;
								
				$('#summaryShopName').html(systemName);
                $('#summaryCallCost').html(callcost);                                
                $('#summaryUl').html(str);

dialogClick();
            }
            if(type == 2)
            {
                str = '<li id="addshop">\
                    <h3 class="cp" >+ Add New</h3>\
                </li>';
    
                var callRecordStatus = '<?php echo $result; ?>';
                
                console.log(callRecordStatus);
                
                var history = "";
                
               
                $.each(response, function(key,value){
                     var duration = "";
                     
                     //console.log(' Record '+response+' Value :: '+value );
                     //console.log(value);
                     
                    if($.inArray(key, systemIdArr) < 0)
                    {
                        
                        if(value.type == 0)
                        {
                            var name = value.userName;
                            var type = "sip";
                        }
                        else
                        {
                            var name = value.messengerId;
                            var type = "messenger";
                        }
                     duration = value.duration;
                     (duration == undefined || duration == null ? duration = "00" : duration); 
                     duration = (duration > 59 ? ((duration/60).toFixed(2).replace(".",":")) : ("00:"+duration)); 
                     
                     (value.callRate == undefined || value.callRate == null ? value.callRate ="" : value.callRate); 
                     (value.currency == undefined || value.currency == null ? value.currency ="" : value.currency); 
                     (value.lastNumber == undefined || value.lastNumber == null ? value.lastNumber ="&nbsp;" : value.lastNumber); 
                     (value.lastdate == undefined || value.lastdate== null ? value.lastdate ="&nbsp;" : value.lastdate); 
                     (value.totalCall == undefined || value.totalCall== null ? value.totalCall ="0" : value.totalCall); 
                     (value.cost == undefined || value.cost== null ? value.cost ="" : value.cost); 
                     
                     var callRate = "";
                     if(value.callRate != "" && value.currency != "")
                        callRate = '<span>'+value.callRate+'</span><span> '+value.currency+'</span>';
                    
                    if(callRecordStatus == 1)
                    {
                        var userShopId = "<?php echo base64_encode($param['userId']) ?>";;
                        var userSystemId =  $.base64('encode', key);
                        
                        history = '<h5><a href="#!callshop.php|callRecord.php?shopId='+userShopId+'&systemId='+userSystemId+'">Record History<a/></h5>';
                    }
    
                    
                    
                    str += '<li id="call_'+key+'" onclick="">\
                              <div class="fixHight"> <h3 class="clear">\
							  <i class="ic-24 edit cp" title="Edit" onclick="editSystemDetails(\''+key+'\',\''+callShopId+'\')"></i>\
                              <div class="ellp csSysName" title="'+value.systemName+'">'+value.systemName+'</div>\
                            </h3>\
                            <div class="tm"><span class="csMrR">'+duration+' min</span>'+callRate+'</div>\
                            <div class="mrT2 ">'+value.lastNumber+'</div>\
                            <p class="dt ">'+value.lastdate+'</p></div><div></div>\
                            <div class="line"></div>\
                            <div class="clear">\
                                <a href="javascript:void(0)" onclick="resetSummary('+callShopId+','+key+')" class="btn btn-mini btn-inverse csMrR" title="Reset">\
                                    <div class="clear">\
                                        <span class="ic-16 refresh"></span>\
                                    </div>\
                                </a>\
                                <div class="csFtrSec csMrR f"><span class="csTtlNum">'+value.totalCall+'</span> Calls</div>\
                                <div class="csFtrSec"><span class="csTtlNum">'+(value.cost!= undefined && value.cost !="" ?value.cost.toFixed(2):"0.00")+'</span> '+value.currency+'</div>\
                                <div class="csFtrSec last fr summary themeLink" onclick="getCallShopSummary('+callShopId+',1,\''+blank+'\',\''+key+'\',\''+value.systemName+'\')">Summary</div>\
                            </div><div style="float:right;">'+history+'</div>\
                        </li>';  

            
           
                    }
                })

                 $('#cmnli-callshop').html(str);
            }
        }
        else
        {
            show_message("No call yet","error");
                
        }
		
	$('#addshop').unbind('click').click(function(){
		$( "#add-shop-dialog input" ).not(':button,:hidden,:submit').val("");
		$( "#add-shop-dialog" ).dialog({ modal: true, resizable:false, width:600, height:445,close:function(event,ui){}});
	})
		

        }
    })
}

function dialogClick()
{
    $( "#summary-dialog" ).dialog({ modal: true, resizable:false, width:600, height:500,close:function(event,ui){}});
}

function resetSummary(callShopId,systemId)
{
    $.ajax({
        url:"/controller/callShopController.php",
        type:"post",
        dataType:"json",
        data:{"call":"resetSummary","callShopId":callShopId,"systemId":systemId},
        success:function(response)
        {
            show_response(response.msg,response.status);
        }
    })
}


function textOnlyValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
     if(/[^a-zA-Z ]+/.test(value))
         return false;
     else
         return true;
}
function userNameValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
    if($('#sip').is(':checked'))
    {
     if(/[^a-zA-Z0-9 ]+/.test(value) || value == "")
         return false;
     else
         return true;
    }
}
$.validator.addMethod("textOnly", textOnlyValidation, "Please enter only alpha characters( a-z ).");
$.validator.addMethod("userName", userNameValidation, "Please enter only alpha characters( a-z, 0-9).");
function validateAddSystemForm()
{
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : function the all plan form before submitting for javascript validation
     **/
    $('#addSystemForm').validate({
        rules: {
            systemName :{
                textOnly:true,
                required: true,
                minlength: 6,
                maxlength: 18

                        }
          }
        })
    if($("#addSystemForm").valid())
            return true; 
        else
            return false;
        
}
function validateEditSystemForm()
{
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : function the all plan form before submitting for javascript validation
     **/
    $('#editSystemForm').validate({
        rules: {
            systemName :{
                textOnly:true,
                required: true,
                minlength: 6,
                maxlength: 18

                        }
          }
        })
    if($("#editSystemForm").valid())
            return true; 
        else
            return false;
        
}





<?php if(isset($_REQUEST['shopId'])){ ?>
    
$('#callShopLi_<?php echo $_REQUEST['shopId']; ?>').addClass('selected');
<?php  }?>



</script>