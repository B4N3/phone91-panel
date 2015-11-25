<?php
include dirname(dirname(__FILE__)) . '/config.php';

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}


#include reseller_class.php file 
//include_once CLASS_DIR.'reseller_class.php';
//#create object of reseller_class
//$resellerObj = new reseller_class();
//#call function manageClients and return json data clientJson
//$clientJson=$resellerObj->manageClients($_REQUEST,$_SESSION);
//$allclientData = json_decode($clientJson, true);  
//
$resId = $_SESSION['userid'];
//$bulkuser = $resellerObj->bulkUserBatch($_SESSION['userid']);
//$bulkData = json_decode($bulkuser, true);  

?>
<?php 
//if($allclientData["isSearchResult"]=="false") 
//{
?>
<div class="quicKseachsec subPageSrch">
    
    
    <div class="quickSearch">
         <span class="ic-16 search icon" title="Search"></span> 
         <input type="text" id="searchUser" onkeyup="advanceSearchAdmin($(this).val())" placeholder="Search Client.." class="allTypeUser"/>
         <input type="text" name="searchBulkUser" onkeyup="advanceSearchBulkUser($(this).val())" id="searchBulkUser" placeholder="search Bulk User" class="allTypeUser dn">
		 <div class="replaceBttn fl">
			<a href="#!manage-client.php|addnew-client.php" class="arBorder cmniner secondry fl cp primary" title="Add"><span class="ic-16 add " id="addtariffbtn"></span></a>
		</div>
    </div>
    <div id="userType" class="fl">
        <input type="radio" id="single" name="radio" checked="checked"><label class="radioBtnSetLbl" for="single" onclick="changeClient('single');">Single</label>
        <input type="radio" id="batch" name="radio"><label class="radioBtnSetLbl" for="batch" onclick="changeClient('batch');">Batch</label>				
    </div>
    
    <div id="showAllClientsCheckBox" class="fl allcheckbox">
         <input type="checkbox" name="showAllUser" onclick="showAllUserList()" id="showAllUser" />
         <label for="showAllUser" class="grnThmCrl">Show all clients</label>
    </div>  
    <div id="showAllBatchCheckBox" class="fl dn allcheckbox">
         <input type="checkbox" name="showAllBatch" onclick="showAllBatchList()" id="showAllBatch" />
         <label for="showAllBatch" class="grnThmCrl">Show all Batch</label>
    </div> 
</div>
<?php //} ?>
<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec">
    <div class="allTypeUser" id="singleUser">
    <ul class="mngClntList" id="mngClntList" style="bottom:44px;">
      </ul>
    </div>
     <div class="allTypeUser dn" id="bulkUser" >
			
			<ul class="ln mngClntList commLeftList" id="bulkclientList">
			
			</ul>
		
		   
		</div>	
            
    
    <!-- Define Nature of Client-->
      <div class="naturClint">
      	  <p class="grnThmCrl">
        		<span class="themeBgGrn"></span>
                Premium Clients
          </p>
          <p class="blueThmCrl">
        		<span class="themeBgBlue"></span>
                Normal Clients
          </p>
          <p class="yelloThmCrl">
          		<span class="themeBgYello"></span>
                Idle Clients
          </p>
      </div>
       <!-- //Define Nature of Client-->
</div>
<!--//Left Section-->    

<!--Right  Section-->   
<?php 
//if($allclientData["isSearchResult"]=="false") 
{
?>
<div class= "slideRight" id="rightsec">
</div>
<?php } ?>
<!--//Right Section-->

</div>
<script type="text/javascript">
var resId = <?php echo $resId; ?>;
    
   advanceSearchAdmin('');
    
 var globalTimeout = null;
$(document).ready(function()
{
    
    
	$('.slideLeft ul li, .reserrlerBtn').click(function() {
				if ( $(window).width() <1024) {
					$('.slideRight').animate({"right": "20px"}, "slow");
					$('.slideLeft').fadeOut('fast');
				}
		});
	$("#userType").buttonset();
        
        
});


function toggleState(ths,type)
	{
		
		if($('#chnage'+type).val() == "uncheck")
			{
					$('#chnage'+type).val("check");
			}
			else
				{
					$('#chnage'+type).val("uncheck");
				}
		}
                

/**
 * @author Sudhir Panday <Sudhir@hostnsoft.com> on 19-09-2013
 * @param html ths
 * @param int userId
 * @desc:function use for change user status (block or unblock)
 * @returns void
 */
 
 
function changeUserStatus(ths,userId){
  
  //call toggle function
  $(ths).toggleClass('redDisabl');
  if($('#chnage'+userId).val() == "unBlock")
  {
     $('#chnage'+userId).val("block");
     var status = "block";
     
  }
  else
  {
     $('#chnage'+userId).val("unBlock");
     var status = "unBlock";
 }
 
 
   $.ajax({
                   url : "/action_layer.php?action=changeUserStatus",
                   type: "POST", 
                   data:{userId:userId,status:status},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);

                   }
})
  

}           

function showChainDetail(userId,ths){

$.ajax({
                   url : "/controller/adminManageClientCnt.php?action=showChainDetail",
                   type: "POST", 
                   data:{userId:userId},
                   dataType: "json",
                   success:function (text)
                   {
                       var str = '';
                        var url = window.location.hash; 
                        $.each(text,function(key,value){
                            if(key == 2){
                           str +='<span title="Login As" class="ic-24 login loginAs" style="width: 58px;color: turquoise;"> >>'+ value +'</span>';
                             }else{
                          var hrefLocation ='/controller/signUpController.php?call=loginAs&type=1&userId='+key+'&url='+url.substring(1);
                          str +='<span title="Login As" class="ic-24 login loginAs" onclick="window.location.href=\''+hrefLocation+'\'" style="width: 58px;color: turquoise;"> >>'+ value +'</span>';
                           }                       
                        })
                    $(ths).prev().html(str);   
                        
                       
                       //show_message(text.msg,text.status);

                   }
})

}


function showBatchChainDetail(batchId,ths){

$.ajax({
                   url : "/controller/batchController.php?action=showBatchChainDetail",
                   type: "POST", 
                   data:{batchId:batchId},
                   dataType: "json",
                   success:function (text)
                   {
                       var str = '';
                        var url = window.location.hash; 
                        $.each(text,function(key,value){
                         if(key == 2){
                           str +='<span title="Login As" class="ic-24 login loginAs" style="width: 58px;color: turquoise;"> >>'+ value +'</span>';
                         }else{
                          var hrefLocation ='/controller/signUpController.php?call=loginAs&type=1&userId='+key+'&url='+url.substring(1);
                          str +='<span title="Login As" class="ic-24 login loginAs" onclick="window.location.href=\''+hrefLocation+'\'" style="width: 58px;color: turquoise;"> >>'+ value +'</span>';
                        }                          
                        })
                    console.log(str);
                    $(ths).prev().html(str);   
                        
                       
                       //show_message(text.msg,text.status);

                   }
})

}


	/*function toggleState(ths,type)
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
		}*/
                
                
//$(function() {


//$('#searchUser').keyup(function() {
    function advanceSearchAdmin(keyword)
    {
        
//  alert('Handler for .keyup() called.');
    var value;
    $("#showAllUser").is(':checked')? value= 1 : value = 0;

   var searchUrl='/controller/adminManageClientCnt.php';
   var xhr;
   if( Object.prototype.toString.call( xhr ) === '[object Object]' ) {
       xhr.abort();
   }
   
   console.log(globalTimeout);
   
   if(globalTimeout != null) 
    	clearTimeout(globalTimeout);
   
       globalTimeout=setTimeout(function(){
            
        xhr = $.ajax({
                type: "POST",
                dataType:"JSON",
                data:{'action':'getAllClientDetail','q':keyword,'value':value},
                url: searchUrl,
                success: function(msg){
                    var str = clentDetailDesign(msg);
                     $("#mngClntList").html('');
                     $("#mngClntList").html(str);
                     
                     loadMoreDetail(2,msg.pages,'/action_layer.php?action=loadMoreClientByPage','clentDetailDesign','mngClntList');
                }//function end
                
});

},600);
}            



function clentDetailDesign(msg){
     var str = "";
                    if(msg.hasOwnProperty('client') && (msg.client != undefined || msg.client != ""))
                    {
                         var url = window.location.hash; 
                        $.each(msg.client,function(key,value)
                        {
                            var statusClass ="";
                            console.log("bstatus");
                            console.log("bstatus"+value.blockUnblockStatus);
                            if(value.blockUnblockStatus != 1)
                            {
                                var statusClass ='redDisabl';
                                var Bstatus = "block";
                            }
                            else
                            {
                                
                                var Bstatus = "unBlock";
                            }
                        var usertype = '';
                        if(value.client_type != ''){ 
                        usertype = '<span class="sep">|</span>Type :<b>'+value.client_type+'</b>';
                        }
                        
                        var loginAs = '';
                        if(value.deleteFlag == 0)
                        {
                             var hrefLocation ='/controller/signUpController.php?call=loginAs&type=1&userId='+value.id+'&url='+url.substring(1);
                             loginAs='<span title="Login As" class="ic-24 login loginAs" onclick="window.location.href=\''+hrefLocation+'\'" style="width: 58px;color: turquoise;">Login as</span>';
                             var deleteFlage='<span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag('+value.id+',this);" ></span>';
                            //loginAs += '<span title="Login As" class="ic-24 login loginAs" onclick="var url = window.location.hash;window.location.href='/controller/signUpController.php?call=loginAs&userId=&url='+url.substring(1)" ></span>'; 
                        }
                        //else
                            //loginAs='<span title="Login As" class="ic-24 login loginAs" onclick="javascript:void(0);"></span>';
                        
                        
                        var balance = parseFloat(value.balance);
                                    str += '<li onclick="loadUserDetails('+value.id+',event);">\
                                    <div class="linkCont">\
                                            <div></div><span class="ic-16 link" onclick="showChainDetail('+value.id+',this)"></span>\
                                        <div class="showLinksCont dn">\
                                                <span class="blackThmCrl">'+value.name+'</span>\
                                            <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>\
                                        </div>\
                                    </div>\
                                    <div class="usrDescr">\
                                            <div class="">\
                                                <p class="uname ellp">\
                                                            '+value.name+'</p>'+loginAs+'<h3 class="yelloThmCrl ellp">'+value.uname+'</h3>\
                                                    <span>'+value.contact_no+'</span>\
                                                <p class="acMan">'+value.managerName+'</p>\
                                                <p class="tInfo">\
                                                    Tariff\
                                                    <b>'+value.planName+'</b>\
                                                <span class="sep">|</span>\
                                                <span class="'+value.id+"changeBal"+'">'+balance.toFixed(2)+'</span>'+value.id_currency_name+'\
                                                '+usertype+'\
                                                </p>\
                                                </p>\
                                                    <span class="funder">\
                                                        <label onclick="changeUserStatus(this,'+value.id+');" for="chnage" class="ic-32 grnEnabl cp '+statusClass+' ">\
                                                        </label>\
                                                    <input type="checkbox" id="chnage'+value.id+'" style="display:none" checked="checked"  value ="'+Bstatus+'" />\
                                                    </span>\
                                                    \
                                            </div>\
                                </div>\
                            </li>';
                        });//each
                    
                    
                    
                  
                }//if end
                return str;
}


function loadUserDetails(clientId,e)
{

  if(!$(e.target).hasClass('loginAs'))
    window.location.hash='!manage-client.php|transactional.php?clientId='+clientId+'&tb=0;';

}


function showAllUserList(){
    
    var keyword = $('#searchUser').val();
    var value;
    $("#showAllUser").is(':checked')? value= 1 : value = 0;
    var searchUrl='/controller/adminManageClientCnt.php';
    xhr = $.ajax({
                type: "POST",
                dataType:"JSON",
                data:{'action':'getAllClientDetail','q':keyword,'value':value},
                url: searchUrl,
                success: function(msg){
                      var str = clentDetailDesign(msg);
                     $("#mngClntList").html('');
                     $("#mngClntList").html(str);
                     loadMoreDetail(2,msg.pages,'/controller/adminManageClientCnt.php?action=getAllClientDetail&q='+keyword+'&value='+value,'clentDetailDesign','mngClntList');
                }
    
});
    
}

//showAllUserList();

function showAllBatchList(){
    
    var keyword = $('#searchBulkUser').val();
    var value;
    $("#showAllBatch").is(':checked')? value= 1 : value = 0;
     var searchUrl='/controller/adminManageClientCnt.php';

    xhr = $.ajax({
    type: "POST",
    dataType:"JSON",
    url: searchUrl,
    data:{'action':'getBulkClientDetail','q':keyword,'value':value},
    success: function(data){ 

    	var str = createBulkDesign(data);
        
         $("#bulkclientList").html('');
        $("#bulkclientList").html(str);
			
        }
    });

    
}
function changeClient(clientType){
    if(clientType == 'batch'){
        $('.allTypeUser').hide();
        $('#bulkUser').show();
        $('#searchBulkUser').show();
        $('.allcheckbox').hide();
        $('#showAllBatchCheckBox').show();
        
    }else
    {
        $('.allTypeUser').hide();
        $('#singleUser').show();
        $('#searchUser').show();
        $('.allcheckbox').hide();
        $('#showAllClientsCheckBox').show();
    }		
}

function advanceSearchBulkUser(keyword){
//  alert('Handler for .keyup() called.');
    var xhr;
    var searchUrl='/controller/adminManageClientCnt.php';

    var value;
    $("#showAllBatch").is(':checked')? value= 1 : value = 0;
    if( Object.prototype.toString.call( xhr ) === '[object Object]' ) {
        xhr.abort()
    }

    if(globalTimeout != null) 
    	clearTimeout(globalTimeout);
	globalTimeout=setTimeout(function(){

 xhr = $.ajax({
    type: "POST",
    dataType:"JSON",
    url: searchUrl,
    data:{'action':'getBulkClientDetail','q':keyword,'value':value},
    success: function(data){ 

    	var str = createBulkDesign(data);
         $("#bulkclientList").html('');
        $("#bulkclientList").html(str);
	
        loadMoreDetail(2,data.pages,'/action_layer.php?action=loadMoreBatchClientByPage&resId='+resId+'&q='+$('#searchBulkUser').val(),'createBulkDesign','bulkclientList');
        }
    });




	},600);


}

advanceSearchBulkUser('');


function createBulkDesign(data)
{
        var str = '';
        var data = data.batchDetail;
        //validate data
        if(typeof data != 'object')
            return str;
        
        if(data.detail == undefined || data.detail == ""){
           return str;  
        }
        
        var statusClass='';
        var bulkBstatus='';
       $.each(data.detail,function(key,value)
        {
            if(value.blockStatus != 1){
                statusClass ="disabledR";
                bulkBstatus = "block";
            }else{
                statusClass ="";
                bulkBstatus = "unBlock";
            }
            str +='<li class="group" id="clientLi'+value.batchId+'" onclick="window.location.href=\'#!manage-client.php|batchDetail.php?batchId='+value.batchId+'\'">\
                    <div class="uiwrp cp">\
                      <i class="ic-16 notif notifyBatch"></i>\
                      <h3 class="ellp font22 batchName">'+value.batchName+'<label>('+value.numberOfClients+')</label></h3>\
                      </div>\
                      <div class="uiwrp cp">\
                      <i class="ic-16 "></i>\
                          <label>'+value.expiryDate+'</label>\
                          <label>Balance : '+parseFloat(value.batchBalance).toFixed(2)+' '+value.currencyName+'/user</label>\
                          </div>\
                          <p class="tInfo">\
                              Tariff\
                              <b>'+value.tariffName+'</b>\
                        </p>\
                        <div class="actwrp">\
                        <div class="switch">\
                        <label onclick="BatchBlockOrUnblock(this,'+value.batchId+')" class="ic-sw enabledR '+statusClass+'"></label>\
                        <input type="checkbox" id="changeBatchStatus'+value.batchId+'" style="display:none" checked="checked"  value ="'+bulkBstatus+'" />\
                        </div>\
                        </div>\
                        <span title="Delete" class="ic-24 actdelC cp" onclick="setBatchDeleteFlag('+value.batchId+');" ></span>\
                        <div class="callCount"></div>\
                        <div class="callCountHover dn">'+value.numberOfClients+' Clients</div>\
                        <div><div></div><span class="ic-16 link" onclick="showBatchChainDetail('+value.batchId+',this)"></span></div>\
                        </li>';
            
        });
       

	
	return str;
		
}

//var pages =  <?php //if(isset($allclientData['pages'])) echo $allclientData['pages'];else echo 1; ?>;
//if(pages == undefined || pages == '' || pages == null)
//   pages = 1;
//

//
//
//
//
//var Bulkpages =  <?php //if(isset($bulkData['pages']) && is_numeric($bulkData['pages']) && $bulkData['pages'] != 0) echo $bulkData['pages'];else echo 1; ?>;
//if(Bulkpages == undefined || Bulkpages == '' || Bulkpages == null)
//   Bulkpages = 1;
//

//kill the request






</script>