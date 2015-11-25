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
         <input type="text" id="searchRoute" onkeyup="advanceSearchRouteAdmin($(this).val())" placeholder="Search Route.." class=""/>
         
		 <div class="replaceBttn fl">
			<a href="#!manage-route.php|add-route.php" class="arBorder cmniner secondry fl cp primary" title="Add"><span class="ic-16 add " id="addtariffbtn"></span></a>
		</div>
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
     advanceSearchRouteAdmin('');
  
    
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
    function advanceSearchRouteAdmin(keyword)
    {
        
//  alert('Handler for .keyup() called.');
    var value=0;
    //$("#showAllUser").is(':checked')? value= 1 : value = 0;

   var searchUrl='/controller/routeController.php';
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
                data:{'action':'getRoute','q':keyword,'value':value},
                url: searchUrl,
                success: function(msg){
                    var str = routeDesign(msg);
                     $("#mngClntList").html('');
                     $("#mngClntList").html(str);
                     
                     //loadMoreDetail(2,msg.pages,'/action_layer.php?action=loadMoreClientByPage','clentDetailDesign','mngClntList');
                }//function end
                
});

},600);
}            



function routeDesign(msg){
     var str = "";
                    if(msg.length ==0) 
                        return str;
                    
                         
                        $.each(msg,function(key,value)
                        {
                            
                        var usertype = '';
                        //if(value.client_type != ''){ 
                        //usertype = '<span class="sep">|</span>Type :<b>'+value.client_type+'</b>';
                        //}
                        
                        //var loginAs = '';
                       // if(value.deleteFlag == 0)
                        {
                             //var hrefLocation ='/controller/signUpController.php?call=loginAs&type=1&userId='+value.id+'&url='+url.substring(1);
                             //loginAs='<span title="Login As" class="ic-24 login loginAs" onclick="window.location.href=\''+hrefLocation+'\'" style="width: 58px;color: turquoise;">Login as</span>';
                             var deleteFlage='<span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag('+value.id+',this);" ></span>';
                            //loginAs += '<span title="Login As" class="ic-24 login loginAs" onclick="var url = window.location.hash;window.location.href='/controller/signUpController.php?call=loginAs&userId=&url='+url.substring(1)" ></span>'; 
                        }
                        //else
                            //loginAs='<span title="Login As" class="ic-24 login loginAs" onclick="javascript:void(0);"></span>';
                        
                        
                        var balance = parseFloat(value.balance);
                                    str += '<li onclick="loadRouteDetails('+value.id+',event);">\
                                    <div class="linkCont">\
                                            <div></div>\
                                        <div class="showLinksCont dn">\
                                                <span class="blackThmCrl">'+value.routeName+'</span>\
                                            <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>\
                                        </div>\
                                    </div>\
                                    <div class="usrDescr">\
                                            <div class="">\
                                                <p class="uname ellp">\
                                                            '+value.routeName+'</p><h3 class="yelloThmCrl ellp"></h3>\
                                                    <span></span>\
                                                <p class="acMan"></p>\
                                                <p class="tInfo">\
                                                    Tariff\
                                                    <b>'+value.planName+'</b>\
                                                <span class="sep">|</span>\
                                                <span class="'+value.id+"changeRouteBal"+'">'+balance.toFixed(2)+'</span>'+value.currency+'\
                                                </p>\
                                                </p>\
                                            </div>\
                                </div>\
                            </li>';
                        });//each
                    
                    // <span class="funder">\
                                                        //<label for="chnage" class="ic-32 grnEnabl cp">\
                                                        //</label>\
                                                    //<input type="checkbox" id="chnage'+value.id+'" style="display:none" checked="checked"  value ="" />\
                                                   // </span>\
                    
                  
               
                return str;
}


function loadRouteDetails(routeId,e)
{
    //$('#mngClntList li').css('background','#F5F5F5');
    //console.log(e.srcElement.localName);
//    if(e.srcElement != null && e.srcElement != undefined)
//    if(e.srcElement.localName == 'li')
//	$(e.target).css('background','#E9E9E9');
//    else
//    {
//	$(e.target).parents('li').css('background','#E9E9E9');
//    }
	
  //if(!$(e.target).hasClass('loginAs'))
    window.location.hash='!manage-route.php|manage-route-setting.php?routeId='+routeId+'&tb=0';

}


//function showAllUserList(){
//    
//    var keyword = $('#searchUser').val();
//    var value;
//    $("#showAllUser").is(':checked')? value= 1 : value = 0;
//    var searchUrl='/controller/adminManageClientCnt.php';
//    xhr = $.ajax({
//                type: "POST",
//                dataType:"JSON",
//                data:{'action':'getAllClientDetail','q':keyword,'value':value},
//                url: searchUrl,
//                success: function(msg){
//                      var str = clentDetailDesign(msg);
//                     $("#mngClntList").html('');
//                     $("#mngClntList").html(str);
//                     loadMoreDetail(2,msg.pages,'/controller/adminManageClientCnt.php?action=getAllClientDetail&q='+keyword+'&value='+value,'clentDetailDesign','mngClntList');
//                }
//    
//});
//    
//}

//showAllUserList();


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



//advanceSearchBulkUser('');










</script>