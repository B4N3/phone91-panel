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
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/function_class.php');

function c2ccallfile_local($unique_id1, $unique_id2, $src_no, $des_no, $route) {

    $dir = "/tmp";
    $File = $route . $unique_id1 . ".call";
    $path = $dir . "/" . $File;

    $Handle = fopen($path, 'w');

    if (!$Handle) {
        return 0;
    }

    $Data = "Set: PassedInfo=" . $unique_id1 . "-" . $unique_id2 . "-" . $route . "\n";
    fwrite($Handle, $Data);
    $Data = "Channel: SIP/" . $route . "/" . $src_no . "\n";
    fwrite($Handle, $Data);
    $Data = "CallerId: " . $des_no . "\n";
    fwrite($Handle, $Data);
    $Data = "Context: click2call\n";
    fwrite($Handle, $Data);
    $Data = "Extension: " . $des_no . "\n";
    fwrite($Handle, $Data);
    $Data = "Priority: 1\n";
    fwrite($Handle, $Data);
    fclose($Handle);

    rename($path, "/var/spool/asterisk/outgoing/" . $File);

    return 1;
}

$error = "";

$src_no = isset($_REQUEST['src_no']) ? trim($_REQUEST['src_no']) : "";
$des_no = isset($_REQUEST['des_no']) ? trim($_REQUEST['des_no']) : "";
$username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : "";
$password = isset($_REQUEST['password']) ? trim($_REQUEST['password']) : "";


//if($username != "vikas123"){
//	die(json_encode(array("status" => "error", "msg" => "Temporary Down")));
//}


if ($src_no == '' || !(is_numeric($src_no)) || !(strlen($src_no) > 9) || !(strlen($src_no) < 17)) {
    die(json_encode(array("status" => "error", "msg" => "Source Number Invalid")));
}

if ($des_no == '' || !(is_numeric($des_no)) || !(strlen($des_no) > 9) || !(strlen($des_no) < 17)) {
    die(json_encode(array("status" => "error", "msg" => "Destination Number Invalid")));
}

if ($username == '') {
    die(json_encode(array("status" => "error", "msg" => "Username Invalid")));
}

if ($password == '') {
    die(json_encode(array("status" => "error", "msg" => "Password Invalid")));
}

if ($src_no == $des_no) {
    die(json_encode(array("status" => "error", "msg" => "Both Number Are Same You Can Not Call On Same Mobile Number")));
}

$fun_obj = new fun_class();
$db_obj = new db_class();

$id_client = $fun_obj->getclientidbyusername($username);
$chainId = $fun_obj->getChainId($id_client);

if ($id_client == '') {
    die(json_encode(array("status" => "error", "msg" => "username does not exist")));
}

$auth_status = $fun_obj->check_auth($username, $password);

if ($auth_status != 1) {
    die(json_encode(array("status" => "error", "msg" => "username/password are not match")));
}

$clientInfo = $fun_obj->get_clientCallStatus($id_client);

if ($clientInfo['isBlocked'] != 1) {
//    $agi->set_variable(VALUE,$value);
    die(json_encode(array("status" => "error", "msg" => "user does not allowed to call")));
}

if ($clientInfo['beforeLoginFlag'] != 2) {
    die(json_encode(array("status" => "error", "msg" => "Please verify your account or contact to your account manager")));
}

$unique_id = $fun_obj->createUniqueId($id_client);

$unique_id1 = $unique_id . '091001';
$unique_id2 = $unique_id . '091002';

$check_combine_balance = $fun_obj->check_combine_chain_balance($chainId, $src_no, $des_no, $reason);

if ($check_combine_balance != 1) {
    $fun_obj->reject_reason($chainId, $src_no, $reason);
    die(json_encode(array("status" => "error", "msg" => "Please contact to your Account manager OR support")));
}

$caller_id = $fun_obj->getCallerId($id_client);

$con2 = $db_obj->voip_connect();
$sql2 = "select resellerId, isDialPlan, routeId from 91_userBalance where userId = '$id_client'";
$result2 = mysql_query($sql2, $con2);
mysql_close($con2);
$get_userinfo = mysql_fetch_array($result2);
$resellerId = $get_userinfo['resellerId'];
$isDialPlan = $get_userinfo['isDialPlan'];
$routeId = $get_userinfo['routeId'];

if ($isDialPlan == 0) {
    
} else if ($isDialPlan == 1) {
    $sqlRouteId = "SELECT routeId FROM 91_dialPlanRoute WHERE dialPlanId = '$routeId' and '$src_no' LIKE CONCAT(userPrefix,'%%') ORDER BY userPrefix DESC LIMIT 1";
    $con = $db_obj->voip_connect();
    $result = mysql_query($sqlRouteId, $con);
    $get_userinfo = mysql_fetch_array($result);
    $routeId = $get_userinfo['routeId'];
} else {
    $reason = "Invalid DialPlanId = " . $isDialPlan;
    $fun_obj->reject_reason($chainId, $src_no, $reason);
    die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

if ($routeId == 0) {
    $reason = "Invalid routeId = " . $routeId;
    $fun_obj->reject_reason($chainId, $src_no, $reason);
    die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

$sqlDivertedRoute = "select divertedRouteId from 91_divertedRoute where routeId = '$routeId'";
$con = $db_obj->voip_connect();
$result = mysql_query($sqlDivertedRoute, $con);
$get_userinfo = mysql_fetch_array($result);
$divertedRouteId = $get_userinfo['divertedRouteId'];

if ($divertedRouteId == 0) {
    $reason = "Invalid divertedRouteId = " . $divertedRouteId;
    $fun_obj->reject_reason($chainId, $src_no, $reason);
    die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

$routeDetails = "select route, optPrefix from 91_route where routeId = '$divertedRouteId'";
$con = $db_obj->voip_connect();
$result = mysql_query($routeDetails, $con);
$get_userinfo = mysql_fetch_array($result);
$routeName = $get_userinfo['route'];
$optPrefix = $get_userinfo['optPrefix'];

$i = 0;
$symFlag = 0;
$symPos = 0;
$numStartPos = 0;
$numWithoutPrefix = "";

for ($i = 0; $i < strlen($src_no); $i++) {
    if ($src_no[$i] == '*' || $src_no[$i] == '#') {
        $symFlag = 1;
        $symPos = $i;
    }
}

if ($symFlag == 1) {
    for ($i = $symPos + 1; $i < strlen($src_no); $i++) {
        if ($src_no[$i] == '0') {
            
        } else {
            $numStartPos = $i;
            break;
        }
    }
} else {
    for ($i = 0; $i < strlen($src_no); $i++) {
        if ($src_no[$i] == '0') {
            
        } else {
            $numStartPos = $i;
            break;
        }
    }
}

$j = 0;
$i = 0;

for ($i = $numStartPos; $i < strlen($src_no); $i++) {
    $numWithoutPrefix.= $src_no[$i];
    $j++;
}

$newExten = $optPrefix . $numWithoutPrefix;
$userType = $fun_obj->get_userType($id_client);
$tariffId = $fun_obj->get_idtariff($id_client);
$callPrefix_src = $fun_obj->getPrefix($src_no, $tariffId);
$callPrefix_des = $fun_obj->getPrefix($des_no, $tariffId);

$con = $db_obj->voip_connect();
$sql = "select routeCredits, tariffId from 91_route where route = '$routeName'";
$result = mysql_query($sql, $con);
mysql_close($con);

$get_userinfo = mysql_fetch_array($result);
$routeCredits = $get_userinfo['routeCredits'];
$routeTariffId = $get_userinfo['tariffId'];

$routeCallRate = $fun_obj->getCallRate($newExten, $routeTariffId);


if ($routeCredits <= 0 || $routeCredits <= $routeCallRate) {
    $reason = "Route has not sufficient balance to Call. route = $route, routeCredits = $routeCredits, callRate = $callRate";
    $fun_obj->reject_reason($chainId, $src_no, $reason);
    mail('vikas@walkover.in', 'Route:' . $route, $reason);
    mail('alok@phone91.com', 'Route:' . $route, $reason);
    mail('rahulverma@phone91.com', 'Route:' . $route, $reason);
    mail('shubhendra@hostnsoft.com', 'Route:' . $route, $reason);
    die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Please try after some time OR contact support")));
}



$con = $db_obj->voip_connect();
$sql = "insert into 91_currentCalls (uniqueId, id_client, id_chain, callerId, dialed_number, call_dial, status, call_type, id_reseller, userType, callPrefix, route) VALUES ('$unique_id1', '$id_client', '$chainId', '$des_no', '$src_no', now(), 'DIALING', 'C2C', '$resellerId', '$userType', '$callPrefix_src', '$routeName')";
$result = mysql_query($sql, $con) or $error = mysql_error();
mysql_close($con);


if ($error != "") {
    die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Please try after some time OR contact support")));
}

$con = $db_obj->voip_connect();
$sql = "insert into 91_currentCalls (uniqueId, id_client, id_chain, callerId, dialed_number, call_dial, status, call_type, id_reseller, userType, callPrefix) VALUES ('$unique_id2', '$id_client', '$chainId', '$src_no', '$des_no', now(), 'WAITING', 'C2C', '$resellerId', '$userType', '$callPrefix_des')";
$result = mysql_query($sql, $con) or $error = mysql_error();
mysql_close($con);

if ($error != "") {
    //echo $sql;
    $con = $db_obj->voip_connect();
    $sqldelete = "delete from 91_currentCalls where uniqueId = '$unique_id1'";
    $result = mysql_query($sqldelete, $con);
    mysql_close($con);
    die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Please try after some time OR contact support")));
}

$call_status = c2ccallfile_local($unique_id1, $unique_id2, $newExten, $des_no, $routeName);

if ($call_status) {
    echo json_encode(array("status" => "success", "msgid1" => $unique_id1, "msgid2" => $unique_id2));
} else {
    die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Contact support")));
}
?>
