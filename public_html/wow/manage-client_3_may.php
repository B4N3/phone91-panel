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
        <?php //foreach($allclientData['client'] as $clientData){ ?>
<!--          <li onclick="window.location.href='#!manage-client.php|transactional.php?clientId=<?php //echo $clientData['id'];?>&tb=0'">
                  <div class="linkCont">
                      <div></div>
                  	 <span class="ic-16 link" onclick="showChainDetail(<?php //echo $clientData['id']?>,this)"></span>	
                     <div class="showLinksCont dn">
                            <span class="blackThmCrl"><?php //echo $clientData['name'];?></span>
                           <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>
                       </div>
                   </div>
                  <div class="usrDescr">
                           <div class="">
                               <p class="uname ellp">
                                        <?php //echo $clientData['name'];?></p>
                                <?php //if($clientData['deleteFlag'] == 0){ ?>
						<span title="Login As" class="ic-24 login loginAs" onclick="var url = window.location.hash;window.location.href='/controller/signUpController.php?call=loginAs&type=1&userId=<?php //echo $clientData['id']; ?>&url='+url.substring(1)" style="width: 58px;color: turquoise;" >Login as</span>
                                    <?php //}
                                    //else {?>
                                            	<span title="Login As" class="ic-24 login loginAs" onclick=" javascript:void(0);" ></span>    
                                    <?php //} ?>               
                                <h3 class="yelloThmCrl ellp"><?php //echo $clientData['uname'];?></h3>
                                <span><?php //echo $clientData['contact_no'];?></span>
                               <p class="acMan"><span>A/c M:</span> <?php// echo $clientData['managerName'];?></p>
                               <p class="tInfo">
                                                Tariff 
                                                <b><?php //echo $clientData["planName"];?></b>
                                                <span class="sep">|</span>
                                                <span><?php //echo round($clientData["balance"],2); ?></span>  <?php// echo $clientData["id_currency_name"];?>
                                                <?php //if($clientData["client_type"] != ''){?>
                                                <span class="sep">|</span>
                                                Type : 
                                                <b><?php //echo $clientData["client_type"];?></b>
                                                <?php //}?>
                                </p>
                               </p>
                                <span class="funder">
                                    <?php //  if($clientData["blockUnblockStatus"] != 1){
//                                                               $statusClass ="redDisabl";
//                                                               $Bstatus = "block";
//                                                            }else{
//                                                                $statusClass ="";
//                                                                $Bstatus = "unBlock";
//                                                            }
                                                            ?>
                                    <label onclick="changeUserStatus(this,<?php //echo $clientData['id'];?> );" for="chnage" class="ic-32 grnEnabl cp <?php //echo $statusClass; ?> ">
                                    </label>
                                   <input type="checkbox" id="chnage<?php //echo $clientData["id"];?>" style="display:none" checked="checked"  value ="<?php //echo $Bstatus; ?>" />
                                </span>
                                <p class="textSip">SIP</p>	
                         </div>
              </div>
          </li>-->
        <?php //} ?>
        <!--  <li onclick="window.location.href='#!manage-client.php|transactional.php?clientId=31995'">
                  <div class="linkCont">
                  	 <span class="ic-16 link"></span>	
                     <div class="showLinksCont dn">
                            <span class="blackThmCrl">Manoj Jain </span>
                           <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>
                       </div>
                   </div>
                   <div class="usrDescr">
                            <p class="uname">
                                    Manoj Jain
                            <h3 class="blueThmCrl">manojjain223</h3>
                            <span>+19893073345</span>
                           <p class="acMan"> <span>A/c M:</span> Shubhendra Agrawal</p>
                           </p>
                            <span class="funder">
                                <label onclick="toggleState($(this),'Trans'); " for="chnage" class="ic-32 grnEnabl cp"></label>
                                <input type="checkbox" id="" style="display:none" checked="checked" value="check" />
                            </span>
                             <p class="textSip">SIP</p>
                   </div>
          </li>
          <li  onclick="window.location.href='#!manage-client.php|transactional.php?clientId=31995'">
                  <div class="linkCont">
                  	 <span class="ic-16 link"></span>	
                     <div class="showLinksCont dn">
                            <span class="blackThmCrl">Manoj Jain </span>
                           <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>
                       </div>
                   </div>
                   <div class="usrDescr">
                            <p class="uname">
                                    Manoj Jain
                            <h3 class="grnThmCrl">manojjain223</h3>
                            
                            <span class="grnThmCrl">+19893073345</span>
                           <p class="acMan"> <span>A/c M:</span> Shubhendra Agrawal</p>
                           </p>
                       	 <span class="funder">
                                <label onclick="toggleState($(this),'Trans');" for="chnage" class="ic-32 grnEnabl cp"></label>
                                <input type="checkbox" id="" style="display:none" checked="checked" value="check" />
                            </span>
                          <p class="textSip">SIP</p>
                   </div>
          </li>-->
      </ul>
    </div>
     <div class="allTypeUser dn" id="bulkUser" >
			
			<ul class="ln mngClntList commLeftList" id="bulkclientList">
			<?php //foreach($bulkData['detail'] as $bulkUserDetail){?>
<!--                                <li class="group" id="clientLi<?php //echo $bulkUserDetail["batchId"];?>" data-id="<?php //echo $bulkUserDetail["batchId"];?>" onclick="window.location.href='#!manage-client.php|batchDetail.php?batchId=<?php //echo $bulkUserDetail["batchId"];?>'">
                                        <div class="uiwrp cp">
                                                 <i class="ic-16 notif notifyBatch"></i>
                                                <h3 class="ellp font22 batchName"><?php //echo $bulkUserDetail["batchName"];?> <label>(<?php //echo $bulkUserDetail["numberOfClients"];?>)</label></h3>
                                        </div>
                                        <div class="uiwrp cp">
                                                <i class="ic-16 "></i>
                                                <label><?php //echo date('Y-m-d',strtotime($bulkUserDetail["expiryDate"]));?></label>
                                                <label>Balance : <?php //echo round($bulkUserDetail['batchBalance'],3)." ".$bulkUserDetail['currencyName'];?>/user</label>
                                        </div>
                                        <p class="tInfo">
                                        		Tariff
                                                 <b><?php //echo $bulkUserDetail['tariffName'];?> </b> 
                                                 <span class="sep">|</span>
                                                  <span>  No. of Client :  </span>
                                        </p>
                                  	   <div class="actwrp">
                                            <div class="switch">
						<?php // if($bulkUserDetail['blockStatus'] != 1){
                                                      //  $statusClass ="disabledR";
                                                   //     $bulkBstatus = "block";
                                                    //}else{
                                                      //  $statusClass ="";
                                                        //$bulkBstatus = "unBlock";
                                                   // }
                                               
                                                    ?>
                                    <label onclick="BatchBlockOrUnblock(this,<?php //echo $bulkUserDetail["batchId"];?> );" class="ic-sw enabledR <?php //echo $statusClass; ?>"></label>
                                                        <input type="checkbox" id="changeBatchStatus<?php //echo $bulkUserDetail["batchId"];?>" style="display:none" checked="checked"  value ="<?php //echo $bulkBstatus; ?>" />
                                              
                                            </div>
                                    	</div>
                                     <span title="Delete" class="ic-24 actdelC cp" onclick="setBatchDeleteFlag(<?php //echo $bulkUserDetail["batchId"];?>);" ></span> 
                                    <div class="callCount"></div>
                                    <div class="callCountHover dn"><?php //echo $bulkUserDetail["numberOfClients"];?> Clients</div>
                               	</li>-->
                            <?php //} ?>          
				 
			
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
                          var hrefLocation ='/controller/signUpController.php?call=loginAs&type=1&userId='+key+'&url='+url.substring(1);
                          str +='<span title="Login As" class="ic-24 login loginAs" onclick="window.location.href=\''+hrefLocation+'\'" style="width: 58px;color: turquoise;"> >>'+ value +'</span>';
                                                  
                        })
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
                            if(msg.blockUnblockStatus != 1)
                            {
                                var Bstatus = "block";
                            }
                            else
                            {
                                var statusClass ="";
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
                                    str += '<li onclick="window.location.href=\'#!manage-client.php|transactional.php?clientId='+value.id+'&tb=0\'">\
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
                        <div class="callCountHover dn">'+value.numberOfClients+' Clients</div></li>';
            
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