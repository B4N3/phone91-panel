#!/usr/bin/php -q
<?php
/**
 * 
 * Phone91 mobile varification
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * 
 */

date_default_timezone_set('Asia/Calcutta');
include_once('/var/www/html/db_inc.php');
include_once('/var/www/html/fun.php');
include_once ("/var/www/html/phpagi-2.20/phpagi.php");

$agi = new AGI();

$uniqueId = $argv[1];

if ($uniqueId == '')
{
	die();
}

$con = msg91_connect();
$sql = "select * from msg91_status where unique_id = '$uniqueId'";
$result = mysql_query($sql,$con) or die(mysql_error());
mysql_close($con);

$get_userinfo = mysql_fetch_array($result);
$vcode = $get_userinfo['code'];
$status = $get_userinfo['status'];

$agi->set_variable(VCODE,$vcode);

$con = msg91_connect();
$sql = "update msg91_status set status = 'ANSWER' where unique_id = '$uniqueId'";
$result = mysql_query($sql,$con) or die(mysql_error());
mysql_close($con);

?>
