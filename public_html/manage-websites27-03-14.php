<?php
include_once('config.php');
include_once(CLASS_DIR."websiteClass.php");
$webObj = new websiteClass();

$allWebsite = $webObj->getManageWebsite($_SESSION['id']);
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
        </div>
	</div>
	<div class="showAtBack"><a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a></div>	
	
        	
        	<div id="leftsec" class="slideLeft commLeftSec">
            	<div class="innerSection">
					<ul class="ln cmnli-webli commLeftList" id="webList">
					
					</ul>
				</div>
            </div>
            
            <div id="rightsec" class="box- box-addWebsite slideRight webSite commRightSec">
            </div>
        <div class="clear"></div>
        <div id="dialog-confirm" class="dn" title="Are you sure you want to delete this website?">
            <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
        </div>          

<script type="text/javascript">
/* set active
manageWeb: "2"
window['localStorage'] stores above values. this helps to get current state.
*/
var storage = window['localStorage'];



function selectedWebLi(domainName,e,id){
	//	var id = domainName.replace(/\./g,'');
	$('#webList li').removeClass('active');    
	$('#web_'+id).addClass('active');
	
	if(!$(e.target)){
		if($('#web_'+id).length > 0){
				var top = $('#web_'+id).position().top;
				$('#webList').scrollTop(top-100);		
		}		
	}
	
	if(!$(e.target).hasClass('delIc'))console.log(domainName);
	{
		storage.setItem('manageWeb',domainName);
		storage.setItem('manageWebId',id);
		window.location.hash = '!manage-websites.php|add-website.php?id='+domainName;
	}
	
}

function getDomainList()
{
    $.ajax({
        url:"controller/websiteController.php",
        type:"POST",
        dataType:"JSON",
        data:{"action":"getDomainList"},
        success:function(response){
            if(response == null)
                return false;
            var str = "";
            
        $.each(response,function(key,item){
            str +='<li id="web_'+item.id+'" onclick="selectedWebLi(\''+item.domainName+'\',event,\''+item.id+'\')">\
				 <div class="jh clear">\
				 <p>'+item.companyName+'</p>\
				 <p>'+item.language+'</p>\
				 </div>\
				 <h3 class="ellp">'+item.domainName+'</h3>\
				 <p class="">'+item.theme+'</p>\
				 <div class="actwrap delBtn" onclick="DeleteWebsite(this);" websiteId="'+item.domainName+'">\
				 <i class="ic-24 delR"></i>\
				 </div>\
				 </li>';
            })
            $('#webList').html(str);
            
            if(storage.getItem('manageWeb')){
	selectedWebLi(storage.getItem('manageWeb'),'',storage.getItem('manageWebId'));
	
        }
        }
    })
}

$(document).ready(function(){
    getDomainList();
})


function DeleteWebsite(ths){
    
    var domainId = $(ths).attr('websiteId');
    
    
    
    $( "#dialog-confirm" ).dialog({
      resizable: false,
      height:140,
      width:400,
      modal: true,
      buttons: {
        "Delete Website": function() {
          $( this ).dialog( "close" );
       
    
    $.ajax({
                   url : "controller/websiteController.php?action=deleteWebsite",
                   type: "POST", 
                   data:{domainId:domainId},
                   dataType: "json",
                   success:function (text)
                   {
                       
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                          $(ths).parent('li').hide(); 
                          $(ths).parents('ul').find('li:first').addClass('active').trigger('click');
                           
                       }

                   }
                });
    
     },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
    
    
}


</script>

