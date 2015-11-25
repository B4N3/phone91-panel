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

$country = $funobj->countryAllDetail();

?>
<?php 
//if($allclientData["isSearchResult"]=="false") 
//{
?>
<div class="quicKseachsec subPageSrch" style="overflow: visible;">
    
    
    <div class="quickSearch">
         <span class="ic-16 search icon" title="Search"></span> 
         <input type="text" id="searchUser" onkeyup="advanceSearchAdmin($(this).val())" placeholder="Search Client.." class="allTypeUser"/>
	 <span class="ic-32 more" title="Search"></span>
         <input type="text" name="searchBulkUser" onkeyup="advanceSearchBulkUser($(this).val())" id="searchBulkUser" placeholder="search Bulk User" class="allTypeUser dn">
		 <div class="replaceBttn fl">
			<a href="#!manage-client.php|addnew-client.php" class="arBorder cmniner secondry fl cp primary" title="Add"><span class="ic-16 add " id="addtariffbtn"></span></a>
		</div>
	 <div>
	     <form action="javascript:;" style="width: 288px;" id="pushForm" onsubmit="advanceSearchAdmin($('#searchUser').val()); $(this).hide('fast')">
		<span><input name="lessThan" type="checkbox">balance < <input name="amtToCheck" type="number" style="width: 50px;" >USD  </span>
		<span><input name="signupBal" type="checkbox">users who have not used signup balance</span>
		<span><input name="reNotUsingCall" type="checkbox">Users Who have recharged but not using calling</span>
		<span><input name="notUseSince" type="checkbox">Users who are not using since <input name="dayToCheck" type="number" style="width: 50px;" > days.</span>
		<span><input name="windowsU" id="win" type="checkbox">Windows</span>
		<span><input name="isoU" id="ios" type="checkbox">IOS</span>
		<span><input name="androidU" id="andro" type="checkbox">Android</span>
		<span><input name="selectCountry" type="checkbox">Select country
			<div class="selectBoxWrapper">
                            <select class="srcSelect">
                                          <?php 
                                               foreach($country as $key =>$countryNames)
                                               {
                                                    $ccode = explode('/', $countryNames['ISO']);
                                                    echo "<option countryFlag=" . $ccode[0] . " value=" . $countryNames['CountryCode'] . "  ".(($countryNames['CountryCode'] == "91")?"selected='selected'":"")."   >" . $countryNames['Country'] . "</option>";
                                               }
                                           ?> 
                            </select>
			    <div class="codeInput">
				<input name="code" type="text" id="code" class="codeSource" value="91" onkeyup="updateFlag(this)"/>
				   
    </div>
                                     </div></span>
		
		<input class="mrT1 isInput100 btn btn-medium btn-primary" type="submit" value="filter">
	     </form>
	     <input id="sendBtn" style="display:none;" onclick="openDialog()" class="mrL btn btn-medium btn-primary fr mrT" type="button" value="Send">
	 </div>
	 <span class="mrT1 fl"><input name="selectAll" id="select-all" type="checkbox">select All</span>
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
         <input type="checkbox" name="showAllBatch" onclick="showAllBatchList()" id="showAllBatch"   />
         <label for="showAllBatch" class="grnThmCrl">Show all Batch</label>
    </div> 
</div>
<?php //} ?>
<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec" style="top: 73px;">
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

<div id="dialog" title="Send" style="display: none;">
    <textarea id="sendText"></textarea>
    <input type="button" onclick="sendPushNotificationOrSMS()" class="mrL btn btn-medium btn-primary" value="send">
</div>
<!--//Right Section-->

</div>
<link rel="stylesheet" type="text/css" href="/css/flags.css">
<script type="text/javascript" src="/js/jquery.selectric.min.js"></script>
<script type="text/javascript"> 
var resId = <?php echo $resId; ?>;
    <?php 
    
if(isset($_REQUEST['q']) && !empty($_REQUEST['q']) && !empty($_REQUEST['qs']) )
{?>
        var searchType = '<?php echo (isset($_REQUEST['type']) && !empty($_REQUEST['type']) )? $_REQUEST['type'] : 0; ?>';
        var searchUserName = '<?php echo base64_decode($_REQUEST['q']); ?>';
        
       console.log(searchType);
       

        if(searchType == '1')
        {
            $('#showAllBatch').attr("checked" , "checked")
            changeClient('batch');
            console.log(searchUserName);
            advanceSearchBulkUser(searchUserName);
           $('#batch').attr('checked','checked');
            
            console.log('Nidhi batch');
           
        }
        else
        {
            changeClient('single');
            advanceSearchAdmin(searchUserName);
             $('#single').attr('checked','checked');
        }


    //    var  searchUserId = '<?php echo base64_decode($_REQUEST['qs']); ?>';

//        var objectDetail = {};
//
//        objectDetail['q'] = searchUserName;
//        objectDetail['qs'] = searchUserId;
//       
//        window.userInfoObj = objectDetail;
       // loadUserDetails(searchUserId , "event",'1');
        

<?php }else { ?>
    
    advanceSearchAdmin('');
   // advanceSearchBulkUser('');

<?php } ?> 
        
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

console.log(ths);
console.log("testing");

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
                            str +='<span title="Login As" class="ic-24 login loginAs" onclick="redirectLoginUrl('+key+');" style="width: 58px;color: turquoise;"> >>'+ value +'</span>';
                           }                       
                        })
                    $(ths).prev().html(str);   
                        
                       
                       //show_message(text.msg,text.status);

                   }
})

}


function redirectLoginUrl(key){

//var hrefLocation ='/controller/signUpController.php?call=loginAs&type=1&userId='+key+'&url='+url.substring(1);
//                        
// window.location.href=\''+hrefLocation+'\'
 $.ajax({
                   url : "/controller/signUpController.php?call=loginAs",
                   type: "POST", 
                   data:{type:1,userId:key},
                   dataType: "json",
                   success:function (text)
                   {
                       console.log(text);
                       if(text.status == "success"){
                       window.open('https://voice.utteru.com/loginAs.php?token='+text.token);
                       
                     }
                      
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
                          str +='<span title="Login As" class="ic-24 login loginAs" onclick="redirectLoginUrl('+key+');" style="width: 58px;color: turquoise;"> >>'+ value +'</span>';
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
        console.log("nidhi");
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
                data:'action=getFilteredClients&q='+keyword+'&value='+value+'&'+$('#pushForm').serialize(),
                url: searchUrl,
                success: function(msg){
		    console.log(msg);
		    
		    if(msg.status =='success')
		    {
                    var str = clentDetailDesign(msg.data);
                     $("#mngClntList").html('');
                     $("#mngClntList").html(str);
                     
			 loadMoreUsersDetail(2,msg.data.pages,searchUrl+'?action=getFilteredClients&q='+keyword+'&value='+value+'&'+$('#pushForm').serialize(),'clentDetailDesign','mngClntList');
		    }
		    else if(msg.status == 'error')
		    {
			 show_message(msg.msg,msg.status);
		    }
                }//function end
                
});

},600);
}            

var appStatus = 0;

function clentDetailDesign(msg){

    
    
    if($('#win').is(':checked') || $('#ios').is(':checked') || $('#andro').is(':checked') )
	appStatus = 1;
    
     var str = "";
                    if(msg.hasOwnProperty('client') && (msg.client != undefined || msg.client != ""))
                    {
                         var url = window.location.hash; 
                        $.each(msg.client,function(key,value)
                        {
                            var statusClass ="";
                          //  console.log("bstatus");
                            //console.log("bstatus"+value.blockUnblockStatus);
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
			    var contactStr = '';
			    if(value.contact_no != '' && appStatus == 0)
				contactStr = '<input style="position: relative;left: -80px;top: 30px" onchange="showHide()" type="checkbox" class="check" contactToken ="'+value.contact_no+'" appStatus ="'+appStatus+'">';
			    else if(appStatus  == 1)
				contactStr = '<input style="position: relative;left: -80px;top: 30px" onchange="showHide()" type="checkbox" class="check" contactToken ="'+value.uname+'" appStatus ="'+appStatus+'">';
			    
                             var hrefLocation ='/controller/signUpController.php?call=loginAs&type=1&userId='+value.id+'&url='+url.substring(1);
                             loginAs=contactStr+'<span title="Login As" class="ic-24 login loginAs" onclick="redirectLoginUrl('+value.id+');" style="width: 58px;color: turquoise;">Login as</span>';
                             var deleteFlage='<span title="Delete" class="ic-24 actdelC cp " onclick="setdeleteFlag('+value.id+',this);" ></span>';
                            //loginAs += '<span title="Login As" class="ic-24 login loginAs" onclick="var url = window.location.hash;window.location.href='/controller/signUpController.php?call=loginAs&userId=&url='+url.substring(1)" ></span>'; 
                        }
                        //else
                            //loginAs='<span title="Login As" class="ic-24 login loginAs" onclick="javascript:void(0);"></span>';
                        
                       // var objectDetail = {};
                        var balance = parseFloat(value.balance);
                                    str += '<li onclick="loadUserDetails('+value.id+',event,0);">\
                                    <div class="linkCont">\
                                            <div></div><span class="ic-16 link '+value.id+'" onclick="showChainDetail('+value.id+',this)"></span>\
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


function loadUserDetails(clientId,e,displyDetail )
{

  if(!$(e.target).hasClass('loginAs'))
  {
      if($(e.target).hasClass('check'))
      {
	 return false;
      }
      
      if( displyDetail == '1')
      {
          console.log(window.userInfoObj );
          console.log("trace");
          window.location.hash='!manage-client.php?q='+window.userInfoObj['q']+'&qs='+window.userInfoObj['qs']+'|transactional.php?clientId='+clientId+'&tb=0;';
      }else
      {
          window.location.hash='!manage-client.php|transactional.php?clientId='+clientId+'&tb=0;';
      }
      
      
    
  }

}


function showAllUserList(){
    
    var keyword = $('#searchUser').val();
    var value;
    $("#showAllUser").is(':checked')? value= 1 : value = 0;
    var searchUrl='/controller/adminManageClientCnt.php';
    xhr = $.ajax({
                type: "POST",
                dataType:"JSON",
                data:{'action':'getFilteredClients','q':keyword,'value':value},
                url: searchUrl,
                success: function(msg){
		  
                      var str = clentDetailDesign(msg.data);
                     $("#mngClntList").html('');
                     $("#mngClntList").html(str);
                     loadMoreDetail(2,msg.data.pages,'/controller/adminManageClientCnt.php?action=getFilteredClients&q='+keyword+'&value='+value,'clentDetailDesign','mngClntList');
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
                    <div class="uiwrp cp">';
                        if(value.acmName != 0)
                          {
                            str +='<p class="tInfo">\
                                A/c Manager : \
                                <b> '+value.acmName+'</b>\
                            </p>';
                          }
                          
                       str +='<i class="ic-16 notif notifyBatch"></i>\
                      <h3 class="ellp font22 batchName">'+value.batchName+'<label>('+value.numberOfClients+')</label></h3>\
                      </div>\
                      <div class="uiwrp cp">\
                      <i class="ic-16 "></i>\
                          <label>'+value.expiryDate+'</label>\
                          <label>Balance : '+parseFloat(value.batchBalance).toFixed(2)+' '+value.currencyName+'/user</label>\
                          </div>';
                          
                          
                         str+='<p class="tInfo">\
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




$('.srcSelect').selectric({
//    responsive:true,
        flagType:true,
	arrowButtonMarkup: '<span class="pickDown "></span>',
        onChange: function(element){
            var value  = $(element).val();
            console.log(value);
            $(element).parents('.selectBoxWrapper').find('.codeSource').val(value);
        },
        optionsItemBuilder: function(itemData, element, index){
            return element.val().length ? '<span class="flag-24 '+element.attr("countryFlag")+'" ></span>' + itemData.text : itemData.text;
        }
});


function SetValue(ths,idpost)
{
    
    var countryName = $(ths).attr('countryName');
    var countryCode = $(ths).attr('countryCode');
    var countryFlags = $(ths).attr('countryFlags');

    $('.setCountry').html('');
    
    $('#setFlag'+idpost).removeClass($('#setFlag'+idpost).attr('flagId')); //flagId

    $('#setFlag'+idpost).addClass(countryFlags);
    $('#setFlag'+idpost).attr('flagId' , countryFlags);
    $("#code"+idpost).val(countryCode.replace(/ /g, ''));
    $('#flaglist'+idpost).hide();
}

var _globalTimeOut = null;

function updateFlag(ths){    
		if(_globalTimeOut != null)
			clearTimeout(_globalTimeOut);        
		 _globalTimeOut = setTimeout(function(){
			var val = $(ths).val();
			if(val === 'undefined' || val == "" )
				return false;
			
			var srcSelect = $(ths).parents('.selectBoxWrapper');    
			srcSelect.find('.srcSelect option[value='+val+']').prop('selected',true);
			srcSelect.find('.srcSelect').selectric('refresh');
		},300);
	}
	


$("#select-all").on("click", function() {
  var all = $(this);
  
  if($(this).is(':checked'))
  {
      $('#sendBtn').show();
  }
  else
      $('#sendBtn').hide();
  
  
  $('.check').each(function() {
       $(this).prop("checked", all.prop("checked"));
  });
});

function showHide()
{

    if($('.check').is(':checked'))
    {
	$('#sendBtn').show();
	console.log('fjhdkhfjdfhk');
    }
    else
    {
	$('#sendBtn').hide();
    }

}

function sendPushNotificationOrSMS()
{
    console.log('ankit');
    console.log(appStatus);
     var checkedValues = $('.check:checked').map(function() {
           
	console.log('ankit');
	console.log(this);
	   if(appStatus == 0)
	   {
	       
		var num = $(this).attr('contacttoken');   
		return num;  

	   }
	   else if(appStatus == 1)
	   {
	       return $(this).attr('contacttoken');
	   }
	   
	   
	   
    
}).get(); 

console.log(checkedValues);

$.ajax({
    url:'/controller/adminManageClientCnt.php',
    type:'POST',
    dataType:'json',
    data:{action:'sendPushOrSMS',
	sendType:appStatus,
	numbers:checkedValues,
	    textCon:$('#sendText').val()},
    success:function(data){
	
	if(data.status != undefined && data.status == 'success')
	{
	    var msg = data.msg;
	    if(data.data != undefined && data.data != null)
	    {
		msg+=+' '+data.data+' message sent.';
	    }
	    show_message(msg,data.status);
	    $('#dialog').dialog('close');
	}
	
    }
});

    
}

function openDialog(){
 $(function() {
    $( "#dialog" ).dialog();
  });   
}

$(function() {
   $( ".quickSearch .more" ).click(function(){
       $('#pushForm').toggle('fast');
   });
});

 function loadMoreUsersDetail(pageNo,pages,action,functionForDesign,scrollTarget,callback)
{
    console.log(pageNo);
        //$(target).load("autoload_process.php", {'page':pageNo}, function() {pageNo++;}); //load first group
        $('#'+scrollTarget).scroll(function() { 
            //detect page scroll
 
          console.log(pageNo);
	  console.log(pages);
        if((document.getElementById(scrollTarget).scrollHeight < ($('#'+scrollTarget).height()+$('#'+scrollTarget).scrollTop()+100)))  //user scrolled to bottom of the page?
         {
           //if gobalTimeout set then clear it
             if(globalTimeout != null) 
         	clearTimeout(globalTimeout);
		
              //use setTimeout to resist multiple requests  
              globalTimeout=setTimeout(function(){
              if(pageNo <= pages) //there's more data to load
            	{
            		
            		
	                loading = true; //prevent further ajax loading
	                //$('.animation_image').show(); //show loading image
	                
	                //load data from the server using a HTTP POST request
	                $.ajax({url:action,
	                	type:'POST',
	                	dataType:'json',
	                	data:'pageNo='+pageNo, 
	                	success:function(data){
		                    var str = window[functionForDesign](data.data);
				    
		                     //var str = createDesign(data);								
		                    $('#'+scrollTarget).append(str); //append received data into the element
//                                    $('#leftsec, .scrolll ').perfectScrollbar();
//		                    slideAndBack('.slideLeft,.showAtFront','.slideRight,.showAtBack');
		                    pageNo++; //loaded group increment

		                    //loadMoreDetail(pageNo,pages,action,functionForDesign,scrollTarget);
		                    loading = false; 
							if (callback && typeof(callback) === "function") {  
								callback(str);  
							}
	                	}
	                 });
	                 
                
            	}  
                            
               },600); //end of setTimeout function
         }   
    });
}
 
 
 
</script>

<style>
    #pushForm{clear:both; background: #fafafa; border: 1px solid #eee; position:relative; z-index:999; padding:20px; display: none}
    #pushForm span{display:block; margin-bottom:5px;}
    .selectBoxWrapper{margin-top:10px}
    .selectricWrapper{width:38px; z-index: 1; margin:0;}
    .selectric{margin:0; padding:0; width:inherit}
    .selectric .label{margin-right:0}
    .pickDown{margin-top:-13px;}
    .codeInput {height:31px; left:0; width:80px; margin-left:0}
    .quickSearch .codeInput input[type="text"]{padding:0 7px; width:42px; font-size:12px;}
    .quickSearch .more{position:absolute; right:40px; cursor:pointer; background-position: -129px -321px}
</style>