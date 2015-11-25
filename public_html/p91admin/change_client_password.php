<script>
   function change_password1(form_id)
   {$("#loading").show();
       var new_pass=$("#new_pass_"+form_id).val();
       if(new_pass==""||$.trim(new_pass).length<5)
       {show_message("Please Enter Password(Minimum length 5 Character) ","error");
           $("#new_pass_"+form_id).focus();
           $("#loading").hide();return false}
       else {
           $.ajax(
                    {
                        type:"POST",
                        url:"admin_action_layer.php?action=1",
                        data:"id="+form_id+"&new_pass="+new_pass,
                        
                        success:function(msg)                        
                        {
                            if(msg=="done"){                        
                             
                          $("#ajaxcontent"+form_id).hide();
                          $("#ajaxcontent"+form_id).html("");
                          show_message("Password changed successfully","success");
                            }
                            else {show_message("Error Message","error");}
                            $("#loading").hide();
                        }});
                    return false
                    }}
</script>
<?php

$id=$_REQUEST['form_id'];
?>
<h3 class="whitehd">Reset Password</h3>
<div class="outer">	
	<div class="thefield">
	<input name="new_pass_<?php echo $id; ?>" id="new_pass_<?php echo $id; ?>" type="text">
	</div>
    <div class="thefield">
    <input name="submit" type="button" value="Reset" class="medium green awesome" onclick="change_password1(<?php echo $id; ?>);"></div>
</div>



