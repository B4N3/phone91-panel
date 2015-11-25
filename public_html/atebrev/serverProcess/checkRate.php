<?php
include("includes/config.php");

$cntry=trim($_REQUEST['country']);
$bgcolor="";
$id_tariff = 0;
//$tariff_id = $_REQUEST['tarrif'];
$id_tariff = $_REQUEST['tariff_id'];
//$cur = $_REQUEST['cur'];
if(!empty($_REQUEST['bgcolor'])){
$bgcolor = $_REQUEST['bgcolor'];
}else{
	$bgcolor = '666666';
}
//$query="select * from tariffs where (prefix like '".$cntry."%' or description like '".$cntry."%') and id_tariff='$tariff_id' limit 250";

// ********************* Getting Tariff on the basis of Selected Currency *****************************************//
/*
  $query1 = "select * from tariffsnames where id_tariff in (select id_tariff from resellers1 where id=".$reseller." union select id_tariff from tariffreseller where id_reseller=".$reseller." and resellerlevel=1) and id_currency='".$cur."'";
	$rs = mysql_query($query1);
	if(mysql_num_rows($rs)>0){	
		$id_tariff = mysql_result($rs,0,'id_tariff');						
	}
*/
// ****************************************************************************************************************//

$query="select * from tariffs where (prefix like '".$cntry."%' or description like '".$cntry."%') and id_tariff='$id_tariff' limit 250";
$result=mysql_query($query)
	or die(mysql_error());
$ct=0;
$strContents="";
while($row = mysql_fetch_assoc($result)){
	$code1=$row['prefix'];
	$desc1=$row['description'];
	$rate1=$row['voice_rate'];
	
$strContents.= '    <tr bgcolor="#'.$bgcolor.'">
      			<td>'.$code1.'</td>
			    <td>'.$desc1.'</td>
      			<td>'.$rate1.'</td>
    		</tr>';
}

echo $strContents;

?>