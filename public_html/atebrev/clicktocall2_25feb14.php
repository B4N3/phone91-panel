<?php

/**
 * 
 * Two way calling status Initializing calls
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * 
 */

error_reporting(-1);
date_default_timezone_set('Asia/Calcutta');
//include_once('/var/lib/asterisk/agi-bin/db_inc.php');
//include_once('/var/lib/asterisk/agi-bin/fun.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/function_class.php');
//include_once('/home/voip91/public_html/classes/dbconnect_class.php');

$src_no = isset($_REQUEST['src_no']) ? trim($_REQUEST['src_no']) : "";
$des_no = isset($_REQUEST['des_no']) ? trim($_REQUEST['des_no']) : "";
$username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : "";
$password = isset($_REQUEST['password']) ? trim($_REQUEST['password']) : "";


//if ($username != 'vikas123' && $username != 'rahulchordiya')
//{
//	die(json_encode(array("status" => "error", "msg" => "Temporary Down")));
//}


if($src_no == '' || !(is_numeric($src_no)) || !(strlen($src_no)>9) || !(strlen($src_no)<17)) 
	die(json_encode(array("status" => "error", "msg" => "Source Number Invalid")));
	
if($des_no == '' || !(is_numeric($des_no)) || !(strlen($des_no)>9) || !(strlen($des_no)<17)) 
	die(json_encode(array("status" => "error", "msg" => "Destination Number Invalid")));

if($username == '') 
	die(json_encode(array("status" => "error", "msg" => "Username Invalid")));

if($password == '') 
	die(json_encode(array("status" => "error", "msg" => "Password Invalid")));

if ($src_no == $des_no)
	die(json_encode(array("status" => "error", "msg" => "Both Number Are Same You Can Not Call On Same Mobile Number")));

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

$client_status = $fun_obj->get_clientstatus($id_client);

if ($client_status != 1)
{
	die(json_encode(array("status" => "error", "msg" => "user does not allowed to call")));
}

//$unique_id1 = $fun_obj->createUniqueId($id_client);
//$unique_id2 = $fun_obj->createUniqueId($id_client);

$unique_id = $fun_obj->createUniqueId($id_client);

$unique_id1 = $unique_id.'091001';
$unique_id2 = $unique_id.'091002';

$check_combine_balance = $fun_obj->check_combine_chain_balance($chainId, $src_no, $des_no, $reason);

if ($check_combine_balance != 1){
	$fun_obj->reject_reason($chainId, $src_no, $reason);
	die(json_encode(array("status" => "error", "msg" => "Please contact to your Account manager OR support")));
}

$caller_id = $fun_obj->getCallerId($id_client);

$maxdur = 3600;

$route = $fun_obj->voip_route();

if ($route == ''){
        $route = "tata2";
}

//$route = 'faketopri';

$param1['uniqueId'] = $unique_id1;
$param1['id_client'] = $id_client;
$param1['callerId'] = $des_no;
$param1['dialed_number'] = $src_no;
$param1['id_chain'] = $chainId;
$param1['status'] = 'DIALING';
$param1['call_type'] = 'C2C';

$param2['uniqueId'] = $unique_id2;
$param2['id_client'] = $id_client;
$param2['callerId'] = $src_no;
$param2['dialed_number'] = $des_no;
$param2['id_chain'] = $chainId;
$param2['status'] = 'WAITING';
$param2['call_type'] = 'C2C';

$insert_status1 = $fun_obj->insertIntoCurrentCalls($param1);
$insert_status2 = $fun_obj->insertIntoCurrentCalls($param2);

if ($insert_status1 != 1 || $insert_status2 != 1){
	die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Please try after some time OR contact support")));
}

$callFileParam['unique_id1'] = $unique_id1;
$callFileParam['unique_id2'] = $unique_id2;
$callFileParam['maxdur'] = $maxdur;
$callFileParam['src_no'] = $src_no;
$callFileParam['des_no'] = $des_no;
$callFileParam['route'] = $route;

$call_status = $fun_obj->c2ccallfile($callFileParam);

if ($call_status){
	echo json_encode(array("status" => "success", "msgid1" => $unique_id1, "msgid2" => $unique_id2));
}
else {
	die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Contact support")));
}
?>
