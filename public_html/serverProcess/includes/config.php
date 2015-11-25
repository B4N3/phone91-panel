<?
// Copyright (c) 2006 ivis All rights reserved
// support@ivis.ro
// For information on how the script works read the inclosed readme.txt file

// MySQL connection information 
// $dbserver="67.205.89.202"; //MySQL root
// $dbuser="ankita"; //MySQL user
// $dbpass="%tgbhu*76"; //MySQL password
// $dbname="voipswitch"; //MySQL database
// 


/*$dbserver="localhost"; //MySQL root
$dbuser="root"; //MySQL user
$dbpass=""; //MySQL password
$dbname="voipswitch"; //MySQL database
*/


$con = mysql_connect("216.245.201.194","voipswitchuser",'+4H8ZXcSyWn7CuX*') or die(" Couldnot connect to the server ");
		mysql_select_db("voipswitch",$con) or die(" Database Not Found ");
		
// 		
// $con = mysql_connect($dbserver,$dbuser,$dbpass);
// mysql_select_db($dbname,$con) or die(" can not connect to database ".mysql_error());

?>