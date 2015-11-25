<?php
	
	$buisness = 'arpit__1245162853_biz@yahoo.com';
	$return = 'http://chapter7.in/testSite/testsite3/index.php';
	$cancel_return = 'http://chapter7.in/testSite/testsite3/index.php';

?>
<body onLoad="submitForm();">
<FORM ACTION="http://chapter7.in/testSite/testsite3/serverProcess/paypal.php" METHOD="POST" name="form1">
<INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
<INPUT TYPE="hidden" NAME="item_name" VALUE="Recharge Phone91.com">
<INPUT TYPE="hidden" NAME="amount" VALUE="<?php echo $AMT ; ?>">
<input type="hidden" name="business" value="<?php echo $buisness ; ?>">
<input type="hidden" name="return" value="<?php echo $return ; ?>">
<input type="hidden" name="cancel_return" value="<?php echo $cancel_return ; ?>">
</FORM>

<?php
//}			
?>

<script language="javascript">
function submitForm(){
if(document.form1.custom.value!='' && document.form1.amount.value>0){
	document.form1.submit();
}else{
	doucment.location='login.php';
}
}
</script>
