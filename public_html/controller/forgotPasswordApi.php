<?php

chdir('..');

include("config.php");

/**
 * @author Nidhi <nidhi@wlakover.in>
 * @since 23/12/2013
 * @detail : This File Is For forgot Password
 *          Currently Using In case Of phone91
 * 
 */

#- Getting Parameters From Request.
$userId = (isset($_REQUEST['userId']) and $_REQUEST['userId'] != '')? rawurldecode($_REQUEST['userId']) : "";

$smsCall = (isset($_REQUEST['smsCall']) and $_REQUEST['smsCall'] != '')? rawurldecode($_REQUEST['smsCall']) : "";

$countryCode = (isset($_REQUEST['countryCode']) and $_REQUEST['countryCode'] != '')? rawurldecode($_REQUEST['countryCode']) : "";
$verifyOption = (isset($_REQUEST['verifyOption']) and $_REQUEST['verifyOption'] != '')? rawurldecode($_REQUEST['verifyOption']) : "0";

$userId = trim($userId);
$smsCall = trim($smsCall);

if(empty($userId))
{
    $errorMessage = json_encode(array("msg"=>"Invalid Input please provide proper User Name Or Mobile Number","status"=>"error"));
     header('Location: http://phone91.com/forget-password.php?error='.base64_encode($errorMessage));
    die();
}

if(empty($smsCall))
{
    $errorMessage = json_encode(array("msg"=>"Invalid Input please provide Proper value for SMS or CALL","status"=>"error"));
     header('Location: http://phone91.com/forget-password.php?error='.base64_encode($errorMessage));
    die();
}

#- function For Forgot Password.
$response = $funobj->forgotPassword( $userId, $smsCall , $countryCode);

$jsonResponse =  json_decode($response , true);

$jsonResponse['verifyOption'] = $verifyOption;

$response = json_encode($jsonResponse);

#- If Nor Response Found.
if(!$response)
{
   $errorMessage = json_encode(array("msg"=>"SomeThing Went Wrong. Please Try Again","status"=>"error"));
   
   header('Location: http://phone91.com/forget-password.php?error='.base64_encode($errorMessage));
   
}
else 
{
   $errorMessage =  $response; 
   header('Location: http://phone91.com/forget-password.php?error='.base64_encode($errorMessage));
}


?>