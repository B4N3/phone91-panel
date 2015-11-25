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

if(isset($_SESSION['page']))
{
    $page = $_SESSION['page'];
}
 else {
     $page = '';
}
if($openid->validate())
{
    
    $returnVariables = $openid->getAttributes();
    $email = $returnVariables["contact/email"];
    $firstName = $returnVariables["namePerson/first"];
    $lastName = $returnVariables["namePerson/last"];
    
    
    $_SESSION['currentHost'] = 'phone91.com';
    $_SESSION['otherLogin'] = '1';
    $_SESSION["signup_email"] = $email;
    $_SESSION["signup_first_name"] = $firstName;
    $_SESSION["signup_last_name"] = $lastName;
        
    if(!$funobj->checkEmail($email , $page))
    {   
   
        $userDetail = base64_encode(base64_encode(json_encode( array( base64_encode(base64_encode($_SESSION["signup_email"])) ,base64_encode(base64_encode($_SESSION["signup_first_name"])), base64_encode(base64_encode($_SESSION["signup_last_name"]) )))));
        

        if($_SESSION['domain'] == 'phone91.com')
           header('Location: http://phone91.com/signup-step.php?msg=101&error='.$userDetail); 
        else 
        {
            header('Location: /signup.php');    
        }
    }
    
    exit(0);
}
else
{
            header('Location: ../index.php');
}
?>

