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

$uniqueId = $argv[1];
$failReason = $argv[2];

if ($uniqueId == '')
{
	die();
}

if($failReason == 0)
$cause = 'FAILED';

else if($failReason == 1 )
$cause = 'HANGUP';

else if($failReason == 3)
$cause = 'RING TIMEOUT';

else if($failReason == 5)
$cause = 'BUSY';

else if($failReason == 8){
        $cause = 'CONGESTION';
        $retry_time = 1;
}

else
$cause = 'UNKNOWN';


$con = msg91_connect();
$sql = "update msg91_status set status = '$cause' where unique_id = '$uniqueId'";
$result = mysql_query($sql,$con) or die(mysql_error());
mysql_close($con);

?>
