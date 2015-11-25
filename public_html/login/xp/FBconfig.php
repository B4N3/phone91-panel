<?php 
ob_start();

require 'fb/facebook.php';
//get hostname
$hostName = $_SERVER["HTTP_HOST"];

$apiKey = "234936599964806";
$apiSecret = "35d22a043a34d6ee742128b204e83892";
$_SESSION["apiKey"] = $apiKey;

//check apiKey and apiSecret set or not
//if($apiKey != 0 and $apiSecret != "")// Create our Application instance.
$facebook = new Facebook(array(
  'appId'  => $apiKey,  //your application's api id
  'secret' => $apiSecret, //secret key of fb application
  'cookie' => true
));
// Get User ID
$uid = $facebook->getUser();
// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($uid) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $me = $facebook->api('/me');
    $FBfriend = $facebook->api('/me/friends');
  } catch (FacebookApiException $e) {
    error_log($e);
    $uid = null;
  }
}
?>