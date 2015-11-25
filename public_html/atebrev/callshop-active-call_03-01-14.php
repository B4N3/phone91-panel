<?php
//Include Common Configuration File First
include_once('config.php');
if (!$funobj->login_validate() || !$funobj->check_reseller()) {
    $funobj->redirect("index.php");
}
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
        <div id="add-shop-dialog" class="dn" title="Add">
            <div id="add-shop-inner">
            	<div class="col-5">
              		<div class="addform"> 
                        <form name="addSystemForm" id="addSystemForm" action="" method="post">
                    		<p>System Name</p>
                                <input type="text" value="" name="systemName" class="mrB1"/>
<!--                            <p class="nameSys">System Name</p>-->
                            <div class="rdrow"><input type="radio" id="sip" name="sysID" value="0" checked="checked" onclick="sipSetting();$('#submitButtonCS').show();$('#submitButtonCS').val('Generate');"/><label for="sip">Sip</label></div>
                            <div class="rdrow"><input type="radio" id="messenger" name="sysID" value="1" onclick="messengerSetting();$('#submitButtonCS').show();$('#submitButtonCS').val('Save Changes');"/><label for="messenger">MessengerId</label></div>
<!--                            <div class="rdrow"><input type="radio" id="vtalk" name="sysID" /><label for="vtalk">Vtalk</label></div>-->
<!--                            <div id="genDiv" class="dn">
                                <input type="button" class="mrT2 btn btn-medium btn-primary alC" title="Generate" value="Generate"/>
                            </div>-->
<!--                            <div id="sipDiv" class="dn">
                            <p class="nameSys">Sip User Name</p>
                            <input id="sipUserName" type="text" name="userName" value="" />
                            <p class="nameSys">Sip Password</p>
                            <input id="sipPassword" name="password" type="text" value="" class="required" />
                            </div>-->
                            <div id="messengerDiv" style="display: none">
                            <p class="nameSys">Messenger ID</p>
                            <input id="messengerId" name="messengerId" type="text" value="" readonly="readonly"/>
                            <input id="messengerType" name="messengerType" type="hidden" value="" />
                            </div>
                            <input  type="hidden" name="callShopId" value="<?php echo $_REQUEST['shopId']; ?>" />                            
                            <input type="submit" id="submitButtonCS" class="mrT2 btn btn-medium btn-primary alC" title="Save Changes" value="Generate"/>
                        </form>
                    </div>
                </div><!--/end first col child-->
                
                <div id="addShopIdList" class="col-5">
                    <div class="wrp">
                        <div class="pdL2">
                            <p>Select ID for System1</p>
                            <div class="">
                                <input type="text" value="" id="searchMessengerId" />
                            </div>
                        </div>
                        
                        <ul class="ln srchrslt" id="srchrslt" >
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
        <!--callshop dialog start this popup will show on click of add or edit callshop-->
        <div id="edit-shop-dialog" class="dn cmnnEle" title="Add">
            <div id="edit-shop-inner">
            	<div class="col-5">
              		<div class="editform addform"> 
                        <form name="editSystemForm" id="editSystemForm" action="" method="post">
                    		<p>System Name</p>
                                <input type="text" value="" name="systemName" id="systemNameEdit" class="mrB1"/>
<!--                            <p class="nameSys">System Name</p>-->
                            <div class="rdrow"><input type="radio" id="sipEdit" name="sysID" value="0" checked="checked" onclick="sipSetting();"/><label for="sip">Sip</label></div>
                            <div class="rdrow"><input type="radio" id="messengerEdit" name="sysID" value="1" onclick="messengerSetting();"/><label for="messenger">MessengerId</label></div>
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
                                <p class="nameSys">Messenger ID</p>
                                    <input id="messengerIdEdit" name="messengerId" type="text" value="" readonly="readonly"/>
                                    <input id="messengerTypeEdit" name="messengerType" type="hidden" value="" />
                            </div>
                            <input  type="hidden" name="systemId" value="" id="systemIdEdit" />                            
                            <input  type="hidden" name="callShopId" value="<?php echo $_REQUEST['shopId']; ?>" />                            
                            <input type="submit" id="submitButtonCSEdit" class="mrT2 btn btn-medium btn-primary alC" title="Generate" value="Generate"/>
                        </form>
                    </div>
                </div><!--/end first col child-->
                
                <div id="addShopIdList2" class="col-5">
                    <div class="wrp">
                        <div class="pdL2">
                            <p>Select ID for System1</p>
                            <div class="">
                                <input type="text" value="" id="searchMessengerId2" />
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
                <h1 class="mrT2" id="summaryTotalCall">3 calls <span id="summaryTotalCallSpan">25.3USD</span></h1>
                
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
<script>
$("input:radio[name=sysID]").click(function() {
    var value = $(this).val();
	if(value == 1)
		$('')
});
function sipSetting()
{
	toggleDiv('sipDivEdit','messengerDivEdit');
    $('#messengerDiv').hide();
    $('#messengerId').removeClass('required');
    $('#messengerIdEdit').removeClass('required');
	$('#submitButtonCS').val('Generate');
	$('#addShopIdList').hide();
	$('#addShopIdList2').hide();
        $('#submitButtonCSEdit').val('Save');
}
function messengerSetting()
{
    toggleDiv('messengerDiv','sipDiv');
    toggleDiv('messengerDivEdit','sipDivEdit');
    $('#messengerId').addClass('required');
    $('#sipPassword').removeClass('required');
    $('#messengerIdEdit').addClass('required');
	$('#submitButtonCS').val('Save Changes');
	$('#addShopIdList').show();
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
                        <p></p>\
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
//            console.log(response);
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
//            console.log(response);
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
                $('#submitButtonCSEdit').val('Generate');
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
            $( "#edit-shop-dialog" ).dialog({ modal: true, resizable:false, width:800, height:445});
            
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
//        console.log("123");
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
                    console.log(currency);
                    str += '<li>\
                    	<h3>'+value.dialedNumber+'</h3>\
                        <p>'+value.date+'</p>\
                        <div class="clear lir">\
                        	<p>'+duration+' min</p>\
                            <p><span class="ratebig">'+value.cost+'</span>'+currency+'</p>\
                        </div>\
                    </li>';
                    totalCost = (totalCost+value.cost);           
                     
                })
                $('#summaryShopName').html(systemName);
                $('#summaryTotalCall').html(i+" calls");
                
                $('#summaryTotalCallSpan').html(totalCost+" "+currency);
                $('#summaryUl').html(str);
//                $( "#summary-dialog" ).dialog({ modal: true, resizable:false, width:600, height:500});

dialogClick();
            }
            if(type == 2)
            {
                str = '<li id="addshop">\
                    <h3 class="cp" >+ Add New</h3>\
                </li>';
                $.each(response, function(key,value){
                     var duration = "";
                     
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
                     (duration == undefined || duration == null ? duration ="00" : duration); 
                     duration = (duration > 59 ? ((duration/60).toFixed(2).replace(".",":")) : ("00:"+duration)); 
                     
                     (value.callRate == undefined || value.callRate == null ? value.callRate ="" : value.callRate); 
                     (value.currency == undefined || value.currency == null ? value.currency ="" : value.currency); 
                     (value.lastNumber == undefined || value.lastNumber == null ? value.lastNumber ="&nbsp;" : value.lastNumber); 
                     (value.lastdate == undefined || value.lastdate== null ? value.lastdate ="&nbsp;" : value.lastdate); 
                     (value.totalCall == undefined || value.totalCall== null ? value.totalCall ="0" : value.totalCall); 
                     (value.cost == undefined || value.cost== null ? value.cost ="" : value.cost); 
                     
                     var callRate = "";
                     if(value.callRate != "" && value.currency != "")
                        callRate = ' | '+value.callRate+'/'+value.currency;
                    
                    str += '<li id="call_'+key+'" onclick="">\
                              <div class="fixHight"> <h3 class="clear">\
                                <span class="ellp">'+value.systemName+'</span>\
                                <i class="ic-24 edit cp" title="Edit" onclick="editSystemDetails(\''+key+'\',\''+callShopId+'\')"></i>\
                            </h3>\
                            <p class="tm">'+duration+' min'+callRate+'</p>\
                            <h3 class="mrT2 ">'+value.lastNumber+'</h3>\
                            <p class="dt ">'+value.lastdate+'</p></div>\
                            <div class="line"></div>\
                            <div class="clear">\
                                <a href="javascript:void(0)" onclick="resetSummary('+callShopId+','+key+')" class="btn btn-mini btn-inverse alC" title="Reset">\
                                    <div class="clear tryc">\
                                        <span class="ic-16 refresh"></span>\
                                        <span>Reset</span>\
                                    </div>\
                                </a>\
                                <span class="prov f">'+value.totalCall+' Calls</span>\
                                <span class="sepCall">|</span>\
                                <span class="prov">Total <span style="font-size:20px;">'+(value.cost!= undefined && value.cost !="" ?value.cost.toFixed(2):"0.00")+'</span>'+value.currency+'</span>\
                                <span class="prov fr summary " onclick="getCallShopSummary('+callShopId+',1,\''+blank+'\',\''+key+'\',\''+value.systemName+'\')">Summary</span>\
                            </div>\
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
			$( "#add-shop-dialog" ).dialog({ modal: true, resizable:false, width:600, height:445,close:function(event,ui){
                                console.log("hello");
//                                $(this).dialog('destroy');
//                                $(this).remove();
                        }});
		})
		
//		 $('.summary').unbind('click').click(function(){
////			$( "#summary-dialog" ).dialog({ modal: true, resizable:false, width:600, height:500,close:function(event,ui){
////                                console.log("hello again");
//////                                $(this).dialog('destroy');
//////                                $(this).remove();
////                        }});
//		})
        }
    })
}

function dialogClick()
{
    $( "#summary-dialog" ).dialog({ modal: true, resizable:false, width:600, height:500,close:function(event,ui){
                                console.log("hello again");
//                                $(this).dialog('destroy');
//                                $(this).remove();
                        }});
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
            console.log(response);
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