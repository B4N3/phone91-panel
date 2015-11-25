<?php

include_once('csvtoarray/csv.inc.php');
$csv = new csv_uploder('csvtoarray/iso.csv', 2000 , ',');
$array=$csv->getCsv();
foreach($array as $value)
{
	//var_dump($value);
	$data["Country"]=$value["Country"];
	$data["CountryCode"]=trim($value["CountryCode"]);
	$data["ISO"]=$value["ISO"];
	$response[]=$data;
}
echo  json_encode($response);
?>
