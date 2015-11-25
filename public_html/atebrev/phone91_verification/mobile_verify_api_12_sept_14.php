<?php
/**
 * 
 * Phone91 mobile varification
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * 
 */

//error_reporting(E_ALL);
date_default_timezone_set('Asia/Calcutta');
//include_once('/home/voip91/public_html/classes/function_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/classes/function_class.php');
//include_once('/var/lib/asterisk/agi-bin/db_inc.php');
//include_once('/var/lib/asterisk/agi-bin/fun.php');
//include_once('/var/lib/asterisk/agi-bin/phpagi-2.20/phpagi.php');

function callfile_local($mobile_no,$vcode,$uid,$route){
            $dir = "/tmp";
            $File = $route.$uid.".call";
            $path = $dir."/".$File;

            $Handle = fopen($path, 'w');

        if (!$Handle){
                        return 0;
                }

            $Data = "Set: PassedInfo=".$uid."\n";
            fwrite($Handle, $Data);
            $Data = "Channel: SIP/".$route."/".$mobile_no."\n";
            fwrite($Handle, $Data);
            $Data = "CallerId: 401\n";
            fwrite($Handle, $Data);
            $Data = "Context: vphone91\n";
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

$id_client = '2';
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

$route = $routeName;

$call_status = callfile_local($mobile_no,$vcode,$uid,$route);

if ($call_status){
	echo json_encode(array("status" => "success", "msgid1" => $uid));
}
else{
        die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Contact support")));
}

?>
