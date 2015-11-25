<?php

/**
 * 
 * Phone91 mobile varification Response
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * 
 */

error_reporting(0); 
date_default_timezone_set('Asia/Calcutta'); 
//include_once('/var/lib/asterisk/agi-bin/db_inc.php'); 
//include_once('/var/lib/asterisk/agi-bin/fun.php'); 
include_once('/home/voip91/public_html/classes/function_class.php');

$uniqueId1 = isset($_REQUEST['uniqueId1']) ? trim($_REQUEST['uniqueId1']) : "";
$uniqueId2 = isset($_REQUEST['uniqueId2']) ? trim($_REQUEST['uniqueId2']) : "";
$username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : "";
$password = isset($_REQUEST['password']) ? trim($_REQUEST['password']) : "";

if($username == '') 
	die(json_encode(array("status" => "error", "msg" => "Username Number Invalid")));

if($password == '') 
	die(json_encode(array("status" => "error", "msg" => "Password Number Invalid")));

if($uniqueId1 == '') 
	die(json_encode(array("status" => "error", "msg" => "uniqueId1 Number Invalid")));

if($uniqueId2 == '')
        die(json_encode(array("status" => "error", "msg" => "uniqueId2 Number Invalid")));

$fun_obj = new fun_class();
$db_obj = new db_class();

$id_client = $fun_obj->getclientidbyusername($username);
$chainId = $fun_obj->getChainId($id_client);

if ($id_client == '')
{
        die(json_encode(array("status" => "error", "msg" => "username does not exist")));
}

$auth_status = $fun_obj->check_auth($username,$password);

if ($auth_status != 1){
        die(json_encode(array("status" => "error", "msg" => "username/password are not match")));
}

//$client_status = $fun_obj->get_clientstatus($id_client);

//if ($client_status != 1)
//{
//        die(json_encode(array("status" => "error", "msg" => "user does not allowed to call")));
//}


$con = $db_obj->voip_connect();
$sql_find = "select status from 91_currentCalls where uniqueId in ('$uniqueId1', '$uniqueId2')";
$resCheck = mysql_query($sql_find,$con) or die(mysql_error());
$countId = mysql_num_rows($resCheck);
mysql_close($con);
$status="";
if($countId){
	while($get_userinfo = mysql_fetch_array($resCheck))
	{
	$status.= $get_userinfo['status'].",";
	}
	$statArray = split (",", $status);
	echo json_encode(array("status" => "success", "msg1" => $statArray[0], "msg2" => $statArray[1]));
}
else{
	$con = $db_obj->voip_connect();
	$sql_find = "select status from 91_calls where uniqueId in ('$uniqueId1','$uniqueId2')";
	$resCheck = mysql_query($sql_find,$con) or die(mysql_error());
	$countId = mysql_num_rows($resCheck);
	mysql_close($con);

	$status2="";
	if($countId){
//		$get_userinfo = mysql_fetch_array($resCheck);
//		$status = $get_userinfo['reason'];
//		echo json_encode(array("status" => "success", "msg" => $status));
  			while($get_userinfo = mysql_fetch_array($resCheck))
        		{
        			$status2.= $get_userinfo['status'].",";
        		}
        			$statArray = split (",", $status2);
        			echo json_encode(array("status" => "success", "msg1" => $statArray[0], "msg2" => $statArray[1]));
		}
	else {
		echo "status not exist for this ID\n";
	}
}

?>
