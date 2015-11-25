#!/usr/bin/php -q
<?php
/**
 * 
 * Msg91 mobile varification
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * 
 */

date_default_timezone_set('Asia/Calcutta');
include_once('/var/www/html/db_inc.php');
include_once('/var/www/html/fun.php');
//include_once ("phpagi-2.20/phpagi.php");

$uniqueId = $argv[1];

if ($uniqueId == '')
{
	die();
}

$con = msg91_connect();
$sql = "insert into msg91_complete (unique_id,mobile_no,status,code,retry) select unique_id,mobile_no,status,code,retry from msg91_status where unique_id = '$uniqueId'";
$result = mysql_query($sql,$con) or die(mysql_error());
mysql_close($con);

$con = msg91_connect();
$sql1 = "delete from msg91_status where unique_id = '$uniqueId'";
$result = mysql_query($sql1,$con) or die(mysql_error());
mysql_close($con);

?>
