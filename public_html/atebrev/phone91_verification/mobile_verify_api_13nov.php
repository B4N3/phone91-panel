<?php
/**
 * 
 * Phone91 mobile varification
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * 
 */

error_reporting(E_ALL);
date_default_timezone_set('Asia/Calcutta');
include_once('/home/voip91/public_html/classes/function_class.php');
//include_once('/var/lib/asterisk/agi-bin/db_inc.php');
//include_once('/var/lib/asterisk/agi-bin/fun.php');
//include_once('/var/lib/asterisk/agi-bin/phpagi-2.20/phpagi.php');

$mobile_no = isset($_REQUEST['mobile_no']) ? trim($_REQUEST['mobile_no']) : "";
$vcode = isset($_REQUEST['vcode']) ? trim($_REQUEST['vcode']) : "";

if ( $mobile_no=='' || $vcode=='' )
{
	die(json_encode(array("status" => "error", "msg" => "Invalid parameters")));
}

if (!(is_numeric($mobile_no)) || strlen($mobile_no) < 6)
{
        die(json_encode(array("status" => "error", "msg" => "Verification Number Invalid")));
}

$fun_obj = new fun_class();


$uid = $fun_obj->createUniqueId('1111');

$db_status = $fun_obj->save_db($mobile_no,$vcode,$uid);

if ($db_status != 1){
	die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Contact support")));
}

$maxdur = 3600;

$route = $fun_obj->voip_route();

//$call_status = $fun_obj->callfile($mobile_no,$vcode,$uid,$route);
$call_status = $fun_obj->callfile($mobile_no,$vcode,$uid,"ivoice3");

if ($call_status){
	echo json_encode(array("status" => "success", "msgid1" => $uid));
}
else{
        die(json_encode(array("status" => "error", "msg" => "Not able to proceed this call now. Contact support")));
}

?>
