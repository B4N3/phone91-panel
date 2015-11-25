<?php
/**
 * 
 * Phone91 mobile varification
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * modified by Mayank Gour on 15_sept_2014
 * Remodified by "Mayank Gour"<mayank@hostnsoft.com> on 21_nov_2014
 */

//error_reporting(E_ALL);
date_default_timezone_set('Asia/Calcutta');
//include_once('/home/voip91/public_html/classes/function_class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/function_class.php');
//include_once('/var/lib/asterisk/agi-bin/db_inc.php');
//include_once('/var/lib/asterisk/agi-bin/fun.php');
//include_once('/var/lib/asterisk/agi-bin/phpagi-2.20/phpagi.php');

function callfile_local($mobile_no,$vcode,$uid,$route,$callerId,$v_number,$PATHTORECORD){
            $dir = "/tmp";
            $File = $route.$uid.".call";
            $path = $dir."/".$File;

            $Handle = fopen($path, 'w');

	    if (!$Handle){
            	return 0;
            }

            $Data = "Set: PassedInfo=".$uid."-".$route."-".$v_number."-|".$PATHTORECORD."|"."\n";
            fwrite($Handle, $Data);
            $Data = "Channel: SIP/".$route."/".$mobile_no."\n";
            fwrite($Handle, $Data);
            $Data = "CallerId: ".$callerId."\n";
            fwrite($Handle, $Data);
            $Data = "Context: vphone91_test\n";
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

if (!(is_numeric($vcode)) || strlen($vcode) > 10 || strlen($vcode) < 4 ) {
	die(json_encode(array("status" => "error", "msg" => "Verification Number is Invalid")));
}

$fun_obj = new fun_class();
$db_obj = new db_class();

$username = 'verifyuser';
$id_client = $fun_obj->getclientidbyusername($username);
$chainId = $fun_obj->getChainId($id_client);
$uid = $fun_obj->createUniqueId($id_client);
$uid .= "091005";

$con2 = $db_obj->voip_connect();
$sql2 = "select resellerId, isDialPlan, routeId, tariffId, balance, currencyId from 91_userBalance where userId = '$id_client'";
$result2 = mysql_query($sql2, $con2);
mysql_close($con2);
$get_userinfo = mysql_fetch_array($result2);
$resellerId = $get_userinfo['resellerId'];
$isDialPlan = $get_userinfo['isDialPlan'];
$routeId = $get_userinfo['routeId'];
$user_TariffId = $get_userinfo['tariffId'];
$user_balance = $get_userinfo['balance'];
$user_currencyId = $get_userinfo['currencyId'];
$user_currency = $fun_obj->get_currency($user_currencyId);
$src_no = $mobile_no;

if( $user_balance < 5 ) {
	mail("mayank@hostnsoft.com,vikas@walkover.in,alok@phone91.com,rahulverma@phone91.com","VERIFYUSER: Low Balance","VerifyUser has low balance in its account. Current Balance is: $user_balance $user_currency","From: Utteru <no-reply@www.utteru.com>");
}

if ($isDialPlan == 0){
}
else if ($isDialPlan == 1){
//    $sqlRouteId = "SELECT routeId FROM 91_dialPlanRoute WHERE dialPlanId = '$routeId' and '$src_no' LIKE CONCAT(userPrefix,'%%') ORDER BY userPrefix DESC LIMIT 1";
	$number = $src_no;	
	$i = strlen($number);
	$prefix='';
	for($i;$i>0;$i--){
        	$number = substr($number,0, $i);
		$prefix.= "'".$number."',";
        }
	$prefix = substr($prefix, 0, strlen($prefix)-1);
    $sqlRouteId = "SELECT routeId FROM 91_dialPlanRoute WHERE dialPlanId = '$routeId' and userPrefix in (".$prefix.") order by length(userPrefix) DESC LIMIT 1";
    $con2 = $db_obj->voip_connect();
    $result = mysql_query($sqlRouteId, $con2);
    mysql_close($con2);
    $get_userinfo = mysql_fetch_array($result);
    $routeId = $get_userinfo['routeId'];
}
else {
    $reason = "Invalid DialPlanId = ".$isDialPlan;
    $fun_obj->reject_reason($chainId, $src_no, $reason, 'VERIFICATION');
    die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

if($routeId == 0){
    $reason = "Invalid routeId = ".$routeId;
    $fun_obj->reject_reason($chainId, $src_no, $reason, 'VERIFICATION');
    die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

$sqlDivertedRoute = "select divertedRouteId from 91_divertedRoute where routeId = '$routeId'";
$con = $db_obj->voip_connect();
$result = mysql_query($sqlDivertedRoute, $con);
mysql_close($con);
$get_userinfo = mysql_fetch_array($result);
$divertedRouteId = $get_userinfo['divertedRouteId'];
if($divertedRouteId == 0){
    $reason = "Invalid divertedRouteId = ".$divertedRouteId;
    $fun_obj->reject_reason($chainId, $src_no, $reason, 'VERIFICATION');
    die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

$routeDetails = "select route, optPrefix, routeCredits, tariffId from 91_route where routeId = '$divertedRouteId'";
$con = $db_obj->voip_connect();
$result = mysql_query($routeDetails, $con);
mysql_close($con);
$get_userinfo = mysql_fetch_array($result);
$routeName = $get_userinfo['route'];
$optPrefix = $get_userinfo['optPrefix'];
$routeCredits = $get_userinfo['routeCredits'];
$route_TariffId = $get_userinfo['tariffId'];
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
$v_number = $numWithoutPrefix; //the number to be verified
$mobile_no = $newExten;


$con = $db_obj->voip_connect();
$sql = "insert into 91_callVerificationStatus (unique_id,mobile_no,status,code) VALUES ('$uid','$mobile_no','PENDING','$vcode')";
$result = mysql_query($sql,$con) or $error = mysql_error();
mysql_close($con);

if ($error != ""){
	die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Contact support")));
}

$clientStatus = $fun_obj->get_clientCallStatus($id_client);
if ( $clientStatus['isBlocked'] != 1 ) {
	$reason = "user is blocked";
	$fun_obj->reject_reason($chainId, $src_no, $reason, 'VERIFICATION');
	die(json_encode(array("status" => "error", "msg" => "Contact support")));
}
//if ( $clientStatus['beforeLoginFlag'] != 2 ) {
//	$reason = "user is not verified";
//}
//check user balance
$status = $fun_obj->check_user_balance($chainId, $v_number, $reason);
if (!$status ) {
	mail("mayank@hostnsoft.com,vikas@walkover.in,alok@phone91.com,rahulverma@phone91.com","VERIFYUSER","$reason","From: Utteru <no-reply@www.utteru.com>");
	$fun_obj->reject_reason($chainId, $src_no, $reason, 'VERIFICATION');	
	die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

$status = $fun_obj->check_chain_balance($chainId, $v_number, $reason);
if ( !$status) {
	mail("mayank@hostnsoft.com,vikas@walkover.in,alok@phone91.com,rahulverma@phone91.com","VERIFYUSER","$reason","From: Utteru <no-reply@www.utteru.com>");
	$fun_obj->reject_reason($chainId, $src_no, $reason, 'VERIFICATION');
	die(json_encode(array("status" => "error", "msg" => "Contact support")));
}

$routeCallRate = $fun_obj->getCallRate($v_number,$route_TariffId);
if ( $routeCredits <= 0 || $routeCredits <= $routeCallRate || $routeCallRate <= 0 ) {
	$reason = "Route has not sufficient balance to Call. route = " . $routeName . ", routeCredits = " . $routeCredits . ", callRate = " . $routeCallRate . ", dialed number = " . $v_number . ", tariffId = " . $route_TariffId;
	$fun_obj->reject_reason($chainId, $src_no, $reason, 'VERIFICATION');
	mail("mayank@hostnsoft.com,vikas@walkover.in,alok@phone91.com,rahulverma@phone91.com","Route: $routeName","$reason","From: Utteru <no-reply@www.utteru.com>");
}

$userType = $fun_obj->get_userType($id_client);
$callerId = $fun_obj->getCallerId($id_client);
$callPrefix = $fun_obj->getPrefix($v_number,$user_TariffId);
if ( $callerId == NULL ) {
	$callerId = "401";
}
$con2 = $db_obj->voip_connect();
$sql2 = "insert into 91_currentCalls (uniqueId, id_client, id_chain, callerId, dialed_number, call_dial, status, call_type, route, userType, callPrefix ) VALUES ('$uid', '$id_client', '$chainId', '$callerId', '$v_number', now(), 'DIALING', 'VERIFICATION', '$routeName', '$userType', '$callPrefix')";
$result = mysql_query($sql2,$con2) or $error = mysql_error();
mysql_close($con2);

//RECORDPATH calculation
$dateTime = date("Y-m-d H:i:s");
$date = date("Y-m-d");
$dateTimeTS = strtotime($dateTime);

$isRecord = $fun_obj->isRecord($id_client);
if($isRecord == 1){
    $filename = $v_number."_".$dateTimeTS;
    $path = $id_client."/".$date."/";
    $filepath = $path.$filename;
    $PATHTORECORD=$filepath;
    $sql = "insert into 91_record (uniqueId, telNum, callShopId, systemId, fileName, filePath) values ('$uid', '$v_number', '$id_client', '$systemId', '$filename', '$filepath')";
    $con = $db_obj->voip_connect();
    $result = mysql_query($sql,$con) or $error = mysql_error();
    mysql_close($con);
}


$call_status = callfile_local($mobile_no,$vcode,$uid,$routeName,$callerId,$v_number,$PATHTORECORD);

if ($call_status){
	echo json_encode(array("status" => "success", "msgid1" => $uid));
}
else{
        die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Contact support")));
}

?>
