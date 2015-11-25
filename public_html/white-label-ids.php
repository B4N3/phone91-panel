<?php 
include_once('config.php');
include_once CLASS_DIR.'setting_class.php';

#create object of reseller_class
$settingObj = new setting_class();

$labelData = $settingObj->allWhiteLabelIds($_SESSION['id']);
$whiteLabelData = json_decode($labelData, true);  
if(isset($_REQUEST['userName'])){
    $idsDetail = $settingObj->getidsDetail($_REQUEST['userName'],$_SESSION['id']);
    $idsData = json_decode($idsDetail, true);  
    
}
?>
<!--White Label Id's  Wrapper-->
<a title="Back" class="back btn btn-medium btn-primary hidden-desktop backPhone" href="javascript:void(0);">Back</a>
<div id="wlids">
	<!--Inner Container-->
	<div class="setContainer">
                <a class="btn  btn-primary btn-medium clear alC phoneaddNo email"  onclick="window.location.href='#!setting.php|white-label-ids.php'" title="Id's">
                        <div class="clear tryc">
                                <span class="ic-24 addW"></span>
                                <span>Id's</span>
                        </div>
                  </a>
                <!--Left Phone side-->
                <div class="leftPhone fixed fl mrR1 idsData">
                        <ul class="ln" id="wlIdsList">
                            <?php foreach ($whiteLabelData as $idsdata){?>
                                <li class="default" onclick="window.location.href='#!setting.php|white-label-ids.php?userName=<?php echo $idsdata['userName'];?>'">
                                    <p class="acName"><?php echo $idsdata['userName']; ?></p>
                                    <p class="acProvider"><?php echo $idsdata['type'];?></p>
                                    <a title="Delete" onclick="deleteIds(this);" userName="<?php echo $idsdata['userName'];?>"><span class="ic-24 actdelC cp"> </span></a>
                                </li>
                            <?php } ?>   
                                
                    </ul>
            </div>
             <!--//Left Phone side-->
            
    		<!--Right Phone side-->
     		 <div class="rightPhone fixed fl">
                   <form id ="whiteLabelForm" action="">
                  	 <div id="addAccbox">
           		    	  <p class="mrB">Type</p>
                       	 <select name="type" id="type" >
                                <option value="select">Select Account</option>
                                <option value="Skype" <?php echo ($idsData['type'] == "Skype")? "selected=selected":'';?>>Skype</option>
                                <option value="Gtalk" <?php echo ($idsData['type'] == "Gtalk")? "selected=selected":'';?>>Gtalk</option>
                                <option value="Bingo" <?php echo ($idsData['type'] == "Bingo")? "selected=selected":'';?>>Bingo</option>
                        </select>
                        <p class="mrT2 mrB">Username</p>
                            <input type="text" id="userName" name="userName" value="<?php echo $idsData['userName'];?>"/>
                        <p class="mrT2 mrB">Password</p>
                         <input type="password" id="password" name="password"  value="<?php echo $idsData['password'];?>" />
                		<input class="mrT2 btn btn-medium btn-primary"  value="Add id / Update" type="submit" title="Add id / Update" /></div>
                	</form>
    		    </div>
             <!--//Right Phone side-->
	</div>  
	<!--//Inner Container-->
</div>
<!--//White Label Id's  Wrapper-->
<script>
$(document).ready(function() { 
                
    // ajex request for add white label id's
    var options = { 

            url:"action_layer.php?action=addWhiteLabel", 
            type:'POST',
            dataType: 'json',
            beforeSubmit:  addWhiteLabelRequest,  // pre-submit callback 
            success:     
                    function(text)
                    {
                    show_message(text.msg,text.status);
                    }
    }

    $("#whiteLabelForm").ajaxForm(options); 

});

function addWhiteLabelRequest(formData, jqForm, options){
  
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#whiteLabelForm").validate({
                rules: {
                        userName :{
                            required: true,
                            maxlength: 20
                        },
                        password :{
                            required: true,
                            maxlength: 20
                        }
                        
                       }
        })
        
    })
            
    if($("#whiteLabelForm").valid())
            return true; 
    else
            return false;

} 

function deleteIds(ths){
    var userName = $(ths).attr('userName');
    
    $.ajax({
                   url : "action_layer.php?action=deleteWhiteLabelIds",
                   type: "POST", 
                   data:{userName:userName},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                         var str = '';                      
                           $.each( text.idsData, function(key, item ) {
                               
                            str += ' <li class="default" onclick="window.location.href=#!setting.php|white-label-ids.php?userName='+item.userName+'">\
                                     <p class="acName">'+item.userName+'</p>\
                                     <p class="acProvider">'+item.type+'</p>\
                                     <a title="Delete" onclick="deleteIds(this);" userName="'+item.userName+'"><span class="ic-24 actdelC cp"> </span></a>\
                                </li>';
                               
                           });
                           
                           $('.idsData ul').html('');
                           $('.idsData ul').html(str);    
                        }
                   }
    })
}    
</script><script type="text/javascript">
$(document).ready(function()
{
			$('.back').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "-1000px"}, "slow");
						$('.slideLeft').fadeIn(2000);
				}
			});
	});
</script>