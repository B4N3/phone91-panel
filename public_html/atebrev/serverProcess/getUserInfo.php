<?php
include("includes/config.php");
include("includes/functions.php");
$sql = " select a.*,b.name from clientsshared a left join currency_names b on a.id_currency=b.id where id_client = ".$_REQUEST['user'];
$rs = mysql_query($sql);
if(mysql_num_rows($rs)>0){
	$amt = mysql_result($rs,0,'account_state');
	$cur = mysql_result($rs,0,'name');	
	$username=mysql_result($rs,0,'login');
}
if(!($amt>0)){
	$amt = 0;
}
echo $cur.",".$amt.",".$username;

?>