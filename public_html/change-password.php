<?php
//Include Common Configuration File First
include_once('config.php');
if (!$funobj->login_validate() ) {
    $funobj->redirect("index.php");
}
?>

<!--Change Password  Wrapper-->
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>

<div id="passBox">
	<div class="setContainer settRightSec">
		<!--<h2 class="headSetting">Personal</h2>-->
	   <div class="whiteWrapp">
				<p class="mrB">Current Password</p>
				<form id="changepasswordform" name="changePasswdForm" method="POST"  action="">
						<input type="password" name="curr_pwd" id="curr_pwd"  />
						<p class="mrT2 mrB">New Password</p>
						<input type="password" name="new_pwd" id="new_pwd"  />
						<p class="mrT2 mrB">Confirm New Password</p>
						<input type="password" name="confirm_pwd" id="confirm_pwd"  class="db"/>
						<input class="mrT2 btn btn-medium btn-primary clear" type="submit" name="save" id="save" value="Save"  title="Save" />
				</form>
	  </div>
	</div>
</div>
<!--//Change Password  Wrapper-->
<script type="text/javascript">
dynamicPageName('Change Password')
slideAndBack('.slideLeft','.slideRight');    
 $(document).ready(function() {	  
		var options = { 
                        url:"action_layer.php?action=change_pwd",
                        type : "POST",
                        dataType:  'json',
                        beforeSubmit:  changeShowRequest,  // pre-submit callback 
                        success:function(msg)
                        {
                                
                                show_message(msg.msg, msg.msgtype);
                                $("#changepasswordform")[0].reset();
                        }
		};
        
    
		$('#changepasswordform').ajaxForm(options); 
                
	}); 


function changeShowRequest(){
            // validate the comment form when it is submitted	
            $("#changepasswordform").validate({
                    rules: {
                            curr_pwd :{
				required: true,
                                minlength: 4,
                                maxlength: 25
                                
                                     },
                            new_pwd :{
				required: true,	
                                minlength: 8,
                                maxlength: 25
                                     },
                            confirm_pwd :{
				required: true,
                                minlength: 8,
                                maxlength: 25,
                                equalTo: "#new_pwd"
                                    }   
                                                         
                        
                    }
            })
          
        if($("#changepasswordform").valid())
                return true; 
        else
                return false;
}
</script>

 