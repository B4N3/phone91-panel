<?php
include("config.php");
$con = $funobj->connect();
$end="call Ended";

if (!$funobj->login_validate()) {
	$funobj->redirect("index.php");
}


include(CLASS_DIR."call_class.php");
error_reporting(-1);
$call_obj = new call_class();

$uniqueId = isset($_REQUEST['uniqueId']) ? trim($_REQUEST['uniqueId']) : "";

if($uniqueId == '') 
{
	echo json_encode(array("status" => "error", "msg" => "uniqueId Number Invalid"));
	exit();
}

$nine["uniqueId"] = $uniqueId;

$userId = $_SESSION['id'];

if(!(is_null($call_obj->callResponse($nine ,$userId))))
{

echo $call_obj->callResponse($nine ,$userId );

}

?>
