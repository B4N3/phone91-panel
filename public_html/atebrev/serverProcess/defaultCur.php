<?php
include("includes/config.php");

$bgcolor="";
$id_tariff = 0;
$DEFtariff = $_REQUEST['default_tariff'];
if(!empty($_REQUEST['bgcolor'])){
$bgcolor = $_REQUEST['bgcolor'];
}else{
	$bgcolor = '666666';
}

// ****************************************************************************************************************//

$query="select c1.name,c1.id from currency_names c1 where c1.id IN(select id_currency from tariffsnames t1 where t1.id_tariff=".$DEFtariff.")";
$result=mysql_query($query)
	or die(mysql_error());
$ct=0;
$strContents="";
while($row = mysql_fetch_array($result)){
	$cname1=$row[0];
		$cid1=$row[1];
		$strContents=$cid1;
	//$strContents.= ' <option value="'.$cid1.'">'.$cname1.'</option>';           
            

}

echo $strContents;

?>