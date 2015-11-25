<?php
/**
 * 
 * Phone91 mobile varification
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * modified by Mayank Gour on 15_sept_2014 at 18:15
 */

//error_reporting(E_ALL);
date_default_timezone_set('Asia/Calcutta');
//include_once('/home/voip91/public_html/classes/function_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/function_class.php');
//include_once('/var/lib/asterisk/agi-bin/db_inc.php');
//include_once('/var/lib/asterisk/agi-bin/fun.php');
//include_once('/var/lib/asterisk/agi-bin/phpagi-2.20/phpagi.php');

function callfile_local($mobile_no,$vcode,$uid,$route,$called_number,$callerId){
            $dir = "/tmp";
            $File = $route.$uid.".call";
            $path = $dir."/".$File;

            $Handle = fopen($path, 'w');

        if (!$Handle){
                        return 0;
                }

            $Data = "Set: PassedInfo=".$uid."-".$called_number."\n";
            fwrite($Handle, $Data);
            $Data = "Channel: SIP/".$route."/".$mobile_no."\n";
            fwrite($Handle, $Data);
            $Data = "CallerId: ".$callerId."\n";
            fwrite($Handle, $Data);

//            if(substr($mobile_no,-10)=="9039738328") {
//                $Data = "Context: vphone91_moded\n"; 
//            } else {
                $Data = "Context: vphone91_moded\n";
//            }
//            $Data = "Context: vphone91\n";
            fwrite($Handle, $Data);
            $Data = "Extension: 111\n";
            fwrite($Handle, $Data);
            $Data = "Priority: 1\n";
            fwrite($Handle, $Data);
            fclose($Handle);
//      error_reporting(-1);

            rename($path, "/var/spool/asterisk/outgoing/".$File);

        return 1;
    }

$mobile_no = isset($_REQUEST['mobile_no']) ? trim($_REQUEST['mobile_no']) : "";
$vcode = isset($_REQUEST['vcode']) ? trim($_REQUEST['vcode']) : "";
$error = "";

if ( $mobile_no=='' || $vcode=='' )
{
	die(json_encode(array("status" => "error", "msg" => "Invalid parameters")));
}

if (!(is_numeric($mobile_no)) || strlen($mobile_no) < 6 || strlen($mobile_no) > 20)
{
        die(json_encode(array("status" => "error", "msg" => "Verification Number Invalid")));
}

$fun_obj = new fun_class();
$db_obj = new db_class();

//$id_client = '39063';
$username = 'verifyuser';
$id_client = $fun_obj->getclientidbyusername($username);
$chainId = $fun_obj->getChainId($id_client);

$uid = $fun_obj->createUniqueId($chainId);

$con2 = $db_obj->voip_connect();
$sql2 = "select resellerId, isDialPlan, routeId from 91_userBalance where userId = '$id_client'";
$result2 = mysql_query($sql2, $con2);
mysql_close($con2);
$get_userinfo = mysql_fetch_array($result2);
$resellerId = $get_userinfo['resellerId'];
$isDialPlan = $get_userinfo['isDialPlan'];
$routeId = $get_userinfo['routeId'];

$src_no = $mobile_no;
$called_number= $src_no;
if ($isDialPlan == 0){
}
else if ($isDialPlan == 1){
    $sqlRouteId = "SELECT routeId FROM 91_dialPlanRoute WHERE dialPlanId = '$routeId' and '$src_no' LIKE CONCAT(userPrefix,'%%') ORDER BY userPrefix DESC LIMIT 1";
    $con = $db_obj->voip_connect();
    $result = mysql_query($sqlRouteId, $con);
    $get_userinfo = mysql_fetch_array($result);
    $routeId = $get_userinfo['routeId'];
}
else {
    $reason = "Invalid DialPlanId = ".$isDialPlan;
    $fun_obj->reject_reason($chainId, $src_no, $reason);
    die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

if($routeId == 0){
    $reason = "Invalid routeId = ".$routeId;
    $fun_obj->reject_reason($chainId, $src_no, $reason);
    die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

$sqlDivertedRoute = "select divertedRouteId from 91_divertedRoute where routeId = '$routeId'";
$con = $db_obj->voip_connect();
$result = mysql_query($sqlDivertedRoute, $con);
$get_userinfo = mysql_fetch_array($result);
$divertedRouteId = $get_userinfo['divertedRouteId'];

if($divertedRouteId == 0){
    $reason = "Invalid divertedRouteId = ".$divertedRouteId;
    $fun_obj->reject_reason($chainId, $src_no, $reason);
    die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

$routeDetails = "select route, optPrefix, routeCredits, tariffId from 91_route where routeId = '$divertedRouteId'";
$con = $db_obj->voip_connect();
$result = mysql_query($routeDetails, $con);
$get_userinfo = mysql_fetch_array($result);
$routeName = $get_userinfo['route'];
$optPrefix = $get_userinfo['optPrefix'];
$routeCredits = $get_userinfo['routeCredits'];
$id_tariff = $get_userinfo['tariffId'];

$i = 0;
$symFlag = 0;
$symPos = 0;
$numStartPos = 0;
$numWithoutPrefix = "";

for($i = 0; $i < strlen($src_no); $i++){
    if($src_no[$i] == '*' || $src_no[$i] == '#'){
        $symFlag = 1;
        $symPos = $i;
    }
}

if($symFlag == 1){
    for($i = $symPos + 1; $i < strlen($src_no); $i++){
        if($src_no[$i] == '0'){
        }
        else{
            $numStartPos = $i;
            break;
        }
    }
}
else {
    for($i = 0; $i < strlen($src_no); $i++){
        if($src_no[$i] == '0'){
        }
        else{
            $numStartPos = $i;
            break;
        }
    }
}

$j = 0;
$i = 0;

for($i = $numStartPos; $i < strlen($src_no); $i++){
    $numWithoutPrefix.= $src_no[$i];
    $j++;
}

$newExten = $optPrefix.$numWithoutPrefix;
$mobile_no = $newExten;

$con = $db_obj->voip_connect();
$sql = "insert into 91_callVerificationStatus (unique_id,mobile_no,status,code) VALUES ('$uid','$mobile_no','PENDING','$vcode')";
$result = mysql_query($sql,$con) or $error = mysql_error();
mysql_close($con);

if ($error != ""){
	die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Contact support")));
}

//$route = $fun_obj->voip_route();

//userloginobject is blocked
$clientStatus = $fun_obj->get_clientCallStatus($id_client);
if ( $clientStatus['isBlocked'] != 1 || $clientStatus['beforeLoginFlag'] != 2)
{
	die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

//check user balance
$reason="";
$status = check_user_balance($chainId, $mobile_no, $reason);
if (!$status ) {
	if( strcasecmp($reason,"Insufficient balance") == 0 ) {
		//mail for low balance
	}
	if( strcasecmp($reason,"Tariff not found") == 0 ) {
	}
	if( strcasecmp($reason,"CallRate not found") == 0 ) {
	}
	if( strcasecmp($reason,"Not have balance to call") == 0 ) {
	}
	if( strcasecmp($reason,"Maximum call reached:") == 0 ) {
	}
	die(json_encode(array("status" => "error", "msg" => "Contact support")));
}
$status = check_chain_balance($chainId, $mobile_no, $reason);
if ( !$status) {
	if( strstr($reason,"Tariff not found") ) {
	}
	if( strstr($reason,"Insufficient balance") ) {
	}
	if( strstr($reason,"Not have balance to call") ) {
	}
	die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

$routeCallRate = $fun_obj->getCallRate($mobile_no,$id_tariff);
if ( $routeCredits <= 0 || $routeCredits <= $routeCallRate || $routeCallRate <= 0 ) {
	$reason = "Route has not sufficient balance to Call. route = " . $routeName . ", routeCredits = " . $routeCredits . ", callRate = " . $routeCallRate . ", dialed number = " . $mobile_no . ", tariffId = " . $id_tariff;

//mail ( to,sub-Route: $routeName, $reason);

}

$userType = $fun_obj->get_userType($id_client);
$callerId = $fun_obj->getCallerId($id_client);

$con = $this->voip_connect();
$sql = "insert into 91_currentCalls (uniqueId, id_client, id_chain, callerId, dialed_number, call_dial, status, call_type, route, userType, callPrefix ) VALUES ('$uid', '$id_client', '$chainId', '$callerId', '$called_number', now(), 'Dialing', 'VERIFICATION', '$routeName' )";
$result = mysql_query($sql,$con) or $error = mysql_error();
mysql_close($con);


//$route = $routeName;

$call_status = callfile_local($mobile_no,$vcode,$uid,$routeName,$called_number,$callerId);

if ($call_status){
	echo json_encode(array("status" => "success", "msgid1" => $uid));
}
else{
        die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Contact support")));
}

?>
