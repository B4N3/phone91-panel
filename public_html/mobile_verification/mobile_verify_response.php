<?php

/**
 * 
 * Phone91 mobile varification Response
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * 
 */

date_default_timezone_set('IST');
include_once('/var/lib/asterisk/agi-bin/db_inc.php');
include_once('/var/lib/asterisk/agi-bin/fun.php');

$unique_id = isset($_REQUEST['uid']) ? trim($_REQUEST['uid']) : "";

if($unique_id == '' || !(is_numeric($unique_id)) )
{
	echo "Wrong Unique Id\n";
	return;
}

$con = msg91_connect();
$sqlCheck = "select * from msg91_complete where unique_id='".$unique_id."'";
$resCheck=mysql_query($sqlCheck,$con);
$countId=mysql_num_rows($resCheck);

if($countId) {
	$get_userinfo=mysql_fetch_array($resCheck);
	$status = $get_userinfo['status'];
	
	echo $status;
	return $status;
}
else {
	echo "Unique Id does not exist\n";
}

mysql_close($con);

?>
