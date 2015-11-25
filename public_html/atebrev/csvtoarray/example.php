<?php 
	include_once('csv.inc.php');
	$csv = new csv_uploder('user.csv', 2000 , ',');
	echo "<pre>";
		print_r($csv->getCsv());
	echo "</pre>";
?>
