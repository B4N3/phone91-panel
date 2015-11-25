<?php
include dirname(dirname(__FILE__)) . '/config.php';

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}


#include reseller_class.php file 
include_once CLASS_DIR.'account_manager_class.php';
#create object of reseller_class
$acmObj = new Account_manager_class();

//if account manager then redirect to panel
if($acmObj->loginAcmValidate())
{
    $funobj->redirect("/admin/index.php#!manage-client.php|manage-client-setting.php");
}


#call function manageClients and return json data clientJson
$acmJson=$acmObj->allManagerList($_REQUEST,$_SESSION);
$allclientData = json_decode($acmJson, true);  


?>
<?php 
//if(isset($allclientData["isSearchResult"]) && $allclientData["isSearchResult"]=="false") 
{
?>
<div class="quicKseachsec subPageSrch">
    <div class="quickSearch">
<!--         <span class="ic-16 search icon" title="Search"></span> 
         <input type="text" id="searchUser" onkeyup="advanceSearchAdmin($(this).val())" placeholder="Search Account Manager.." />-->
		 <div class="replaceBttn fl">
			<a href="#!manage-account-manager.php|add-account-manager.php" class="arBorder cmniner secondry fl cp primary" title="Add"><span class="ic-16 add " id="addtariffbtn"></span>Add Account Manager</a>
		</div>
    </div>
<!--    <div class="fl">
         <input type="checkbox" name="showAllUser" onclick="showAllUserList()" id="showAllUser" value="">
         <label for="showAllUser" class="grnThmCrl">Show all clients </span>
    </div>-->
<!--    <label class="showLabel">
        <div class="fl ">
            <span class="fl f14">Showing</span>
             <p class="mrL1 mrR1 fl">
                 <span class="fl blackThmCrl">100</span>
             </p>
             <span class="fl">results by</span>
             <p class="mrL1	 mrR1 fl">
                 <span class="fl blackThmCrl">latest</span>
                 <span class="ic-12 dowpdn fl mrL"></span>
             </p>
              whose balance is less than
        </div>
        <p class="fl showInfo"> 
            <span class="fl">1000</span>
            <span class="ic-12 dowpdn fl mrL"></span>
        </p>
       <p title="Add" class="arBorder fl cp sucsses">
              <span class="ic-12 add "></span>
       </p>
  </label>-->
  </div>
<?php } ?>
<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec">
    <ul class="mngClntList" id="mngClntList">
        <?php foreach($allclientData['detail'] as $clientData){ ?>
          <li onclick="window.location.href='#!manage-account-manager.php|update-account-manager.php?acmId=<?php echo $clientData['acmId'];?>'">
                  <div class="linkCont">
                  	 <span class="ic-16 link"></span>	
                     <div class="showLinksCont dn">
                            <span class="blackThmCrl"><?php echo $clientData['fullName'];?></span>
                           <span class="blueThmCrl"></span>
                       </div>
                   </div>
                  <div class="usrDescr">
                           <div class="fixed">
                               <p class="uname ellp">
                                        <?php echo $clientData['fullName'];?>
                                <h3 class="yelloThmCrl ellp"><?php echo $clientData['userName'];?></h3>
                                <span><?php echo $clientData['contact_no'];?></span>
                              
                              
                               </p>
                                <div class="funder">                                
									<span onclick="deleteAcm(this,<?php echo $clientData['acmId']; ?> );" title="Delete" class="ic-24 delete cp"></span>
								</div>                                
                         </div>
              </div>
          </li>
        <?php } ?>
        <!--  <li onclick="window.location.href='#!manage-client.php|manage-client-setting.php?clientId=31995'">
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
          <li  onclick="window.location.href='#!manage-client.php|manage-client-setting.php?clientId=31995'">
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
<!--//Left Section-->    

<!--Right  Section-->   
<?php 
//if(isset($allclientData["isSearchResult"]) && $allclientData["isSearchResult"]=="false") 
{
?>
<div class= "slideRight" id="rightsec">
</div>
<?php } ?>
<!--//Right Section-->

</div>
 
<script type="text/javascript">
 var globalTimeout = null;
$(document).ready(function()
{
	$('.slideLeft ul li, .reserrlerBtn').click(function() {
				if ( $(window).width() <1024) {
					$('.slideRight').animate({"right": "20px"}, "slow");
					$('.slideLeft').fadeOut('fast');
				}
		});
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
                


 
 
              

	
                
                
//$(function() {

//$('#searchUser').keyup(function() {
    function advanceSearchAcm(keyword)
    {
//  alert('Handler for .keyup() called.');
    var value;
   // $("#showAllUser").is(':checked')? value= 1 : value = 0;

   var searchUrl='/action_layer/adminManageClientCnt.php';
   var xhr;
   if( Object.prototype.toString.call( xhr ) === '[object Object]' ) {
       xhr.abort()
   }
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
                }//function end
                
});

},600)
}            
	




function deleteAcm(ths,acmId)
{
    
    if(confirm('Are you sure? you want to delete this account manager!!!'))
    {
            $.ajax({
                    url : "/action_layer.php?action=deleteAcm",
                    type: "POST", 
                    data:{acmId:acmId},
                    dataType: "json",
                    success:function(text)
                    {
                        console.log(text);
                        var status ;
                        if(text.status ==1)
                        {
                                $(ths).parents('li').remove();
                                status = 'success';
                        }

                        else
                            status= 'error';
                        show_message(text.msg,status);

                    }
            });
    }
   
  

}   

//var pageNo = <?php echo $pageNo; ?>;



//if(pageNo == undefined || pageNo == '' || pageNo == null)
//    pageNo = 1;
 
var pages =  <?php if(isset($allclientData['pages'])) echo $allclientData['pages'];else echo 1; ?>;
if(pages == undefined || pages == '' || pages == null)
   pages = 1;
loadMoreDetail(2,pages,'/action_layer.php?action=loadMoreAcmByPage','createAcmList','mngClntList');
</script>

<script>
//	  jQuery(document).ready(function ($) {
//		"use strict";
//		$('#leftsec, .scrolll ').perfectScrollbar();  
//	  });
//	  current();
</script>