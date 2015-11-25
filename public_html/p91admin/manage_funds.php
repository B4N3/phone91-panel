<script>
   function change_password1(form_id)
   {$("#loading").show();
       var amount=$("#amount_"+form_id).val();
       var fund_type=$('input[name=fund_type'+form_id+']:radio:checked').val();
       var balance=$("#btn_"+form_id).val();
    var total;
    
        
         if(fund_type=="CR") {total=parseFloat(balance)+parseFloat(amount);  }
           else if(fund_type=="DR") {total=parseFloat(balance)- parseFloat(amount);  }
    
           var amountsave=parseFloat(total);
       if(amount==""||amount==0)
       {show_message("Please Enter currect Amount ","error");
                  $("#amount_"+form_id).focus();
           $("#loading").hide();return false}
       else if(fund_type=="" || fund_type=="NaN" || $.trim(fund_type).length<2)
         {show_message("Please Enter currect Trasaction Type ","error");
          $("#loading").hide();return false}
       else{ 
           $.ajax(
                    {
                        type:"POST",
                        url:"admin_action_layer.php?action=2",
                        data:"id="+form_id+"&amount="+amountsave,
                        
                         
                        success:function(msg)                        
                        {
                            if(msg=="done"){                        
                             
                          $("#ajaxcontent"+form_id).hide();
                          $("#ajaxcontent"+form_id).html("");
                          show_message("Amount changed successfully","success");
                            }
                            else {
                                show_message("Error Message","error");}
                            $("#loading").hide();
                        }});
                    return false
                    }}
</script>
<?php

$id=$_REQUEST['form_id'];

include_once("/home/voip91/public_html/newapi/user_function_class.php");
$rowArray = $user_obj->get_table_detail('clientsshared', 'id_currency,account_state', "id_client='" . $id . "'", 'array');
if ($rowArray != '') {
    $cur_type=trim($rowArray['id_currency']);
     $balance=trim($rowArray['account_state']);
    
                                        switch ($cur_type)
                                            {
                                            case 1:
                                                   $cur_txt="USD"; 
                                            break;
                                            case 2:
                                                     $cur_txt="INR"; 
                                            break;
                                            case 3:
                                                     $cur_txt="AED"; 
                                            break;
                                            default:
                                                    $cur_txt="none"; 
                                            }
                        
} else {    echo "if is not excuting"; }
?>
<h3 class="whitehd">Fund Amount DR/CR</h3>
<div class="outer">	
	<div class="thefield"><input type="hidden" id="btn1" >
            <label>Balance Amount</label><input type="submit" id="btn_<?php echo $id; ?>" value="<? echo $balance; ?>" ><label> <? echo $cur_txt; ?></label><br><br>
             <label>Transaction Type</label>
            <input type="radio" name="fund_type<?php echo $id; ?>" id="fund_type_<?php echo $id; ?>" value="DR"><label>   DR </label>
            <input type="radio" name="fund_type<?php echo $id; ?>" id="fund_type_<?php echo $id; ?>" value="CR"><label>   CR  </label>
            <br> <br>
            <label>Amount</label>
		<input name="amount_<?php echo $id; ?>" id="amount_<?php echo $id; ?>" type="text"><label> <? echo $cur_txt; ?></label>
	</div>
    <div class="thefield">
    <input name="submit" type="button"  value="Save" class="medium green awesome" onclick="change_password1(<?php echo $id; ?>);"></div>
</div>



