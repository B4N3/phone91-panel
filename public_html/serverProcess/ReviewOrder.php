<?php
session_start();
include("includes/config.php");
		   $currencyCodeType=$_REQUEST['currency_name'];
		   			
		   if($currencyCodeType=="AED")
		   {		   	
			$recharge=$_REQUEST['recharge']/3.5;
			$recharge=ceil($recharge);
		   }
		   else if($currencyCodeType=="INR")
		   {
		   	
			$recharge=$_REQUEST['recharge']/42;
			$recharge=ceil($recharge);
		   }
		   else
		   {
   		   	
			$recharge=$_REQUEST['recharge'];
		   } 	
	//echo	$AMT = $recharge;
		$id_client = $_REQUEST['custom'];
		 $result=mysql_query("select account_state,id_client,id_currency from clientsshared where id_client='".$id_client."'");
		$get_userinfo=mysql_fetch_array($result);
		$balance=$get_userinfo[account_state];				
?>

<?php
$sql = "insert into payments(id_client,client_type,money,data,type,description,actual_value,invoice_id) values('$id_client',32,0,now(),1,'Automatic recharge by Paypal',$balance,0)";
mysql_query($sql);
$custom = mysql_insert_id();


      $business = $_REQUEST['business'];   // 		phone@phone91.com
      $return	= $_REQUEST['return'];
      $cancel_return = $_REQUEST['cancel_return'];
	  $AMT = $recharge;	
	  $loc = $_REQUEST['location'];
?>


<body onLoad="submitForm();">
<FORM ACTION="paypal.php" METHOD="POST" name="form1">
<INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
<INPUT TYPE="hidden" NAME="item_name" VALUE="Recharge Phone91.com">
<INPUT TYPE="hidden" NAME="amount" VALUE="<?php echo $AMT ; ?>">
<input type="hidden" name="custom" value="<?php echo $custom ;?>">
<input type="hidden" name="business" value="<?php echo $business ; ?>">
<input type="hidden" name="return" value="<?php echo $return ; ?>">
<input type="hidden" name="cancel_return" value="<?php echo $cancel_return ; ?>">
</FORM>
<?php
//}			
?>

<script language="javascript">
function submitForm(){
if(document.form1.custom.value!='' && document.form1.custom.value!=0 && document.form1.amount.value>0){
	document.form1.submit();
}else{
	document.location="<?php echo $loc ;?>";
}
}
</script>
