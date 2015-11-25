<?php

/**
 * 
 * Two way calling status Initializing calls
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * 
 */

error_reporting(0);
date_default_timezone_set('Asia/Calcutta');
include_once('/var/lib/asterisk/agi-bin/db_inc.php');
include_once('/var/lib/asterisk/agi-bin/fun.php');

$src_no = isset($_REQUEST['src_no']) ? trim($_REQUEST['src_no']) : "";
$des_no = isset($_REQUEST['des_no']) ? trim($_REQUEST['des_no']) : "";
$username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : "";
$password = isset($_REQUEST['password']) ? trim($_REQUEST['password']) : "";
$vcode = isset($_REQUEST['vcode']) ? trim($_REQUEST['vcode']) : "";
$record = isset($_REQUEST['record']) ? trim($_REQUEST['record']) : "";

//if ($username != 'vikas123' && $username != 'rahulchordiya')
//{
//	die(json_encode(array("status" => "error", "msg" => "Temporary Down")));
//}


if($src_no == '' || !(is_numeric($src_no)) || !(strlen($src_no)>9) || !(strlen($src_no)<17)) 
	die(json_encode(array("status" => "error", "msg" => "Source Number Invalid")));
	
if($des_no == '' || !(is_numeric($des_no)) || !(strlen($des_no)>9) || !(strlen($des_no)<17)) 
	die(json_encode(array("status" => "error", "msg" => "Destination Number Invalid")));

if($username == '') 
	die(json_encode(array("status" => "error", "msg" => "Username Number Invalid")));

if($password == '') 
	die(json_encode(array("status" => "error", "msg" => "Password Number Invalid")));

if($vcode == '') 
	die(json_encode(array("status" => "error", "msg" => "Invalid verification code")));


if ($src_no == $des_no)
	die(json_encode(array("status" => "error", "msg" => "Both Number Are Same You Can Not Call On Same Mobile Number")));


if (!(is_numeric($vcode)) || !(strlen($vcode) <= 4))
	die(json_encode(array("status" => "warning", "msg" => "Verification code must be less than equal to 4 digit")));


$id_client = getclientidbyusername($username);

if ($id_client == '')
{
	die(json_encode(array("status" => "error", "msg" => "username does not exist")));
}

$client_status = get_clientstatus($id_client);

if ($client_status != 1)
{
	die(json_encode(array("status" => "error", "msg" => "user does not allowed to call")));
}

$get_userinfo = getUserDetails($id_client);
$id_balance = $get_userinfo['account_state'];
$id_tariff = $get_userinfo['id_tariff'];
$id_password = $get_userinfo['password'];
$id_login = $get_userinfo['login'];
$id_reseller = get_idReseller($id_client);
$tariff_desc = get_tariffdesc($id_tariff);

$callrate_src = getCallRate($src_no,$id_tariff);
$callrate_des = getCallRate($des_no,$id_tariff);
$callrate = $callrate_src + $callrate_des;

if ($callrate_src =='' || $callrate_src <= 0)
	die(json_encode(array("status" => "error", "msg" => "Source Number Tariff Not Found")));

if ($callrate_des =='' || $callrate_des <= 0)
	die(json_encode(array("status" => "error", "msg" => "Destination Number Tariff Not Found")));
	
if ($id_balance == '' || $id_balance <= 0 || $id_balance <= $callrate)
	die(json_encode(array("status" => "error", "msg" => "Insufficient Balance To Make Call")));


if($id_password == $password)
{
	$sql_count = "select count(*) from currentcalls where id_client = '$id_client'";
	$con_count = voip_connect();
	$resCheck_count = mysql_query($sql_count, $con_count) or die(mysql_error());
	mysql_close($con_count);
	$id_client_res = mysql_fetch_array($resCheck_count);
	$id_client_count = $id_client_res[0];
	
	if ($id_client_count >= 1){
		die(json_encode(array("status" => "warning", "msg" => "You Are Using Max allowed concurrent connect call limit")));
	}
}
else
{
	die(json_encode(array("status" => "error", "msg" => "Wrong Username/Password")));
}

$call_limit = $id_balance / $callrate;
$maxduration = $call_limit * 60;

if($maxduration >= 1)
{
        $maxdur = (int)$maxduration;
}
else
{
        $maxdur = 1;
}

$con = voip_connect();
$sql5 = "select * from current_calling_route";
$routeInfo = mysql_query($sql5,$con) or die(mysql_error());
mysql_close($con);

$getRouteInfo = mysql_fetch_array($routeInfo);
$route = $getRouteInfo['routename'];

if ($route == ''){
        $route = "ivoice2";
}

if ($record == 1) $record = 1;
else $record = 0;

//$unique_id = $id_client.random().date('dm');
$unique_id1 = createUniqueId($id_client);
$unique_id2 = createUniqueId($id_client);

$param1['uniqueId'] = $unique_id1;
$param1['id_client'] = $id_client;
$param1['callerId'] = $des_no;
$param1['dialed_number'] = $src_no;
$param1['id_reseller'] = $id_reseller;
$param1['tariffdesc'] = $tariff_desc;
$param1['status'] = 'DIALING';
$param1['call_type'] = 'ecallback1';

$param2['uniqueId'] = $unique_id2;
$param2['id_client'] = $id_client;
$param2['callerId'] = $src_no;
$param2['dialed_number'] = $des_no;
$param2['id_reseller'] = $id_reseller;
$param2['tariffdesc'] = $tariff_desc;
$param2['status'] = 'WAITING';
$param2['call_type'] = 'ecallback2';


insertIntoCurrentCalls($param1);
insertIntoCurrentCalls($param2);

$callFileParam['unique_id1'] = $unique_id1;
$callFileParam['unique_id2'] = $unique_id2;
$callFileParam['maxdur'] = $maxdur;
$callFileParam['src_no'] = $src_no;
$callFileParam['des_no'] = $des_no;
$callFileParam['route'] = $route;
//$callFileParam['route'] = 'tatanew';
$callFileParam['vcode'] = $vcode;
$callFileParam['record'] = $record;

c2cverifycallfile($callFileParam);

echo json_encode(array("status" => "success", "msgid1" => $unique_id1, "msgid2" => $unique_id2));

?>
