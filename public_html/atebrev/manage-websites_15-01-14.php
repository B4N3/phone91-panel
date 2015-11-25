<?php
include_once('config.php');
include_once(CLASS_DIR."websiteClass.php");
$webObj = new websiteClass();

$allWebsite = $webObj->getManageWebsite();
$allDomain = json_decode($allWebsite,TRUE);
?>
<div class="commHeader">
	<div class="showAtFront">
        <div class="clear" id="srchrow">
        	   <a class="btn btn-medium btn-primary clear alC iconBtn fl" title= "Add New Website" 
               href="#!manage-websites.php|add-website.php">
                        <span class="ic-24 addW"></span>
                        <span class="iconBtnLbl">Add New Website</span>
                </a>
               <label>
                        <p class="fl">Showing <span>1000</span> results by <span>latest</span> whose balance is less than</p>
                        <p class="fl showInfo"> 
                                <span class="ic-8 close"></span>
                                <span class="fl">1000</span>
                                <span class="ic-8 arrow"></span>
                                <p class="rowClose fl cp" title="Close"><span class="ic-16 close"></span></p>
                        </p>
               </label>
        </div>
	</div>
	<div class="showAtBack"><a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a></div>	
	
        	
        	<div id="leftsec" class="slideLeft commLeftSec">
            	<div class="innerSection">
					<ul class="ln cmnli-webli commLeftList">
											
					 <?php foreach($allDomain as $allweb){ ?>   
						<li onclick="window.location.href='#!manage-websites.php|add-website.php?id=<?php echo $allweb['domainName'];?>'">
							<div class="jh clear">
									<p><?php echo $allweb['companyName']; ?></p>
									<p><?php echo $allweb['language']; ?></p>
							</div>
							<h3 class="ellp"><?php echo $allweb['domainName']; ?></h3>
							<p class=""><?php echo $allweb['theme']; ?></p>
							<div class="actwrap" onclick="DeleteWebsite(this);" websiteId="<?php echo $allweb['domainName'];?>">
								<i class="ic-24 delR"></i>
							</div>
						</li>
					 <?php } ?>   
					</ul>
				</div>
            </div>
            
            <div id="rightsec" class="box- box-addWebsite slideRight webSite commRightSec">
            </div>
            

<script type="text/javascript">
        
function DeleteWebsite(ths){
    
    var domainId = $(ths).attr('websiteId');
    
    $.ajax({
                   url : "controller/websiteController.php?action=deleteWebsite",
                   type: "POST", 
                   data:{domainId:domainId},
                   dataType: "json",
                   success:function (text)
                   {
                       
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                         var str = '';                      
                           $.each( text.allDomain, function(key, item ) {
                             str +='<li onclick="window.location.href = #!manage-websites.php|add-website.php?id='+item.domainName+'">\
                             <div class="jh clear">\
                             <p>'+item.companyName+'</p>\
                             <p>'+item.language+'</p>\
                             </div>\
                             <h3 class="ellp">'+item.domainName+'</h3>\
                             <p class="">'+item.theme+'</p>\
                             <div class="actwrap" onclick="DeleteWebsite(this);" websiteId="'+item.domainName+'">\
                             <i class="ic-24 delR"></i>\
                             </div>\
                             </li>';
                             
                           })
                           
                           $('#leftsec ul').html('');
                           $('#leftsec ul').html(str);    
                           
                       }

                   }
})
    
}        
</script>

