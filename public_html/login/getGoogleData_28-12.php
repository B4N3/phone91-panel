<?php

//include 'config/functions.php';
include_once('../config.php');
require 'google-open/openid.php';
$openid = new LightOpenID;

if($_SERVER["HTTP_HOST"]=='voip91.com')//https
define('CALLBACK_URL', 'https://'.$_SERVER["HTTP_HOST"].'/login/getGoogleData.php');
else
define('CALLBACK_URL', 'http://'.$_SERVER["HTTP_HOST"].'/login/getGoogleData.php');

$openid->returnUrl = CALLBACK_URL;
if($openid->validate())
{
    $returnVariables = $openid->getAttributes();
    $email=$returnVariables["contact/email"];
    $firstName=$returnVariables["namePerson/first"];
    $lastName=$returnVariables["namePerson/last"];
    if(!$funobj->checkEmail($email))
    {   
        $_SESSION["signup_email"]=$email;
        $_SESSION["signup_first_name"]=$firstName;
        $_SESSION["signup_last_name"]=$lastName;
//            $_SESSION['signup_picture']='https://graph.facebook.com/'.$uid.'/picture';
        header('Location: /signup.php');
    }
    exit(0);
}
else{
            header('Location: ../index.php');
}
?>

