<?php
include("includes/config.php");

$bgcolor="";
$id_tariff = 0;
$USDtariff = $_REQUEST['USDtariff'];
$INRtariff = $_REQUEST['INRtariff'];
$AEDtariff = $_REQUEST['AEDtariff'];
$cur = $_REQUEST['cur'];
if(!empty($_REQUEST['bgcolor'])){
$bgcolor = $_REQUEST['bgcolor'];
}else{
	$bgcolor = '666666';
}

// ****************************************************************************************************************//
$query="select c1.name,c1.id from currency_names c1 where c1.id IN(select id_currency from tariffsnames t1 where t1.id_tariff IN(";
if($USDtariff!=0)
	$query.=$USDtariff.",";
if($INRtariff!=0)
	$query.=$INRtariff.",";	
if($AEDtariff!=0)
	$query.=$AEDtariff.",";	
$query=substr($query,0,strlen($query)-1);
$query.="))";
//echo $query;
$result=mysql_query($query)
	or die(mysql_error());
$ct=0;
$strContents="";
while($row = mysql_fetch_array($result)){
	$cname1=$row[0];
		$cid1=$row[1];
		if($cid1==$cur)
       	$strContents.= ' <option value="'.$cid1.'" selected>'.$cname1.'</option>';   
		else
		$strContents.= ' <option value="'.$cid1.'">'.$cname1.'</option>';   	        
            

}

echo $strContents;

?>