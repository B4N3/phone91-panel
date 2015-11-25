<?php
include dirname(dirname(__FILE__)) . '/config.php';

if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}


#include reseller_class.php file 
include_once CLASS_DIR.'reseller_class.php';
#create object of reseller_class
$resellerObj = new reseller_class();
#call function manageClients and return json data clientJson
$clientJson=$resellerObj->manageClients($_REQUEST,$_SESSION,"allClient");
$allclientData = json_decode($clientJson, true);  

?>
<?php 
if($allclientData["isSearchResult"]=="false") 
{
?>
<div class="quicKseachsec">
    <div class="quickSearch">
         <span class="ic-16 search icon" title="Search"></span> 
         <input type="text" id="searchUser" placeholder="Search Client.." />
    </div>
    <label class="showLabel">
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
  </label>
  </div>
<?php } ?>
<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec">
    <ul class="mngClntList">
        <?php foreach($allclientData['client'] as $clientData){ ?>
          <li onclick="window.location.href='#!manage-client.php|transactional.php?clientId=<?php echo $clientData['id'];?>'">
                  <div class="linkCont">
                  	 <span class="ic-16 link"></span>	
                     <div class="showLinksCont dn">
                            <span class="blackThmCrl"><?php echo $clientData['name'];?></span>
                           <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>
                       </div>
                   </div>
                  <div class="usrDescr">
                            <p class="uname">
                                    <?php echo $clientData['name'];?>
                            <h3 class="yelloThmCrl"><?php echo $clientData['uname'];?></h3>
                            <span><?php echo $clientData['contact_no'];?></span>
                           <p class="acMan"> <span>A/c M:</span> <?php echo $clientData['managerName'];?></p>
                           </p>
                            <span class="funder">
                                <?php  if($clientData["blockUnblockStatus"] != 1){
                                                           $statusClass ="redDisabl";
                                                           $Bstatus = "block";
                                                        }else{
                                                            $statusClass ="";
                                                            $Bstatus = "unBlock";
                                                        }
                                                        ?>
                                <label onclick="changeUserStatus(this,<?php echo $clientData['id'];?> );" for="chnage" class="ic-32 grnEnabl cp <?php echo $statusClass; ?> "></label>
                               <input type="checkbox" id="chnage<?php echo $clientData["id"];?>" style="display:none" checked="checked"  value ="<?php echo $Bstatus; ?>" />
                            </span>
							<p class="textSip">SIP</p>
                   </div>
          </li>
        <?php } ?>
          <li onclick="window.location.href='#!manage-client.php|manage-client-setting.php?clientId=31995'">
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
                            <!--IF Varified then grnThmCrl will come-->
                            <span class="grnThmCrl">+19893073345</span>
                           <p class="acMan"> <span>A/c M:</span> Shubhendra Agrawal</p>
                           </p>
                       	 <span class="funder">
                                <label onclick="toggleState($(this),'Trans');" for="chnage" class="ic-32 grnEnabl cp"></label>
                                <input type="checkbox" id="" style="display:none" checked="checked" value="check" />
                            </span>
                          <p class="textSip">SIP</p>
                   </div>
          </li>
      </ul>
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
if($allclientData["isSearchResult"]=="false") 
{
?>
<div class= "slideRight" id="rightsec">
</div>
<?php } ?>
<!--//Right Section-->

</div>
<script type="text/javascript">
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
                
                
$(function() {
var xhr;
$('#searchUser').keyup(function() {
//  alert('Handler for .keyup() called.');
   searchUrl='manage-client.php?q='+$(this).val();
   console.log(xhr);
   if( Object.prototype.toString.call( xhr ) === '[object Object]' ) {
       xhr.abort()
   }
                xhr = $.ajax({
                type: "GET",
                url: searchUrl,
                success: function(msg){
                   $("#leftsec").html(msg);
                }
});
console.log(xhr);
//kill the request
});
});
                
		
</script>

<script>
	  jQuery(document).ready(function ($) {
		"use strict";
		$('#leftsec, .scrolll ').perfectScrollbar();
	  });
	  current();
</script>