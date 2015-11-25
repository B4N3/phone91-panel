<?php

//include 'config/functions.php';
include_once('../config.php');
require 'google-open/openid.php';
$openid = new LightOpenID($persons_name="");

include_once('../logmonitor.php');

require CLASS_DIR."signup_class.php";
$signup_obj = new signup_class();

if($_SERVER["HTTP_HOST"]=='voip91.com')//https
    define('CALLBACK_URL', 'https://'.$_SERVER["HTTP_HOST"].'/login/getGoogleData.php');
else
    define('CALLBACK_URL', 'http://'.$_SERVER["HTTP_HOST"].'/login/getGoogleData.php');

$openid->returnUrl = CALLBACK_URL;

if(!isset($_SESSION['domain']))
{
    $_SESSION['domain'] = $_SERVER['HTTP_HOST'];
    $_SESSION['currentHost'] = $_SERVER['HTTP_HOST'];;
}



if($openid->validate())
{
    $returnVariables = $openid->getAttributes();
    $email = $returnVariables["contact/email"];
    $firstName = $returnVariables["namePerson/first"];
    $lastName = $returnVariables["namePerson/last"];
    
  
    
    $_SESSION['currentHost'] = $_SESSION['domain'];
    $_SESSION['otherLogin'] = '1';
    $_SESSION["signup_email"] = $email;
    $_SESSION["signup_first_name"] = $firstName;
    $_SESSION["signup_last_name"] = $lastName;
        
    
    
    
    
    if($signup_obj->check_email_avail($email))
    {      
        $param['firstName'] = $_SESSION["signup_first_name"];
        $param['lastName'] = $_SESSION["signup_last_name"];
        $param['email'] = $_SESSION["signup_email"];
        $param['username'] = $email;
        $param['password'] = $funobj->randomNumber(8);;
        $param['currency'] = '84';
        $param['signupFrom']  = '1'; 
        $param['domain']  = $_SESSION['domain'];
        
          $param['isGoogleFb']  = '1';
        //print_r($param);
        
        $response = $signup_obj->signUp($param);
        
        $resultArr = json_decode($response, true);

     
        
        if($resultArr['status'] == 'success')
        {
            if($resultArr['getCurrency'])
            { 
                $response = $funobj->login_user( $param['username'], $param['password'], 0 ,$_SESSION['domain']);
            }
            else 
            {
                $userDetail = base64_encode(base64_encode(json_encode( array( base64_encode(base64_encode($signup_obj->newUserId))  , base64_encode(1) ))));

                if($_SESSION['domain'] == 'phone91.com')
                   header('Location: http://phone91.com/signup-step.php?msg=101&error='.$userDetail); 
                else 
                {
                     
                     $response = $funobj->login_user($email, $param['password'] , 0 ,$param['domain']);
                     exit();
                }
            }
         }
        else 
        {
            $userDetail = json_encode(array('status' => 'error' , 'msg' => $resultArr['msg']) );

            if($_SESSION['domain'] == 'phone91.com')
                header('Location: http://phone91.com/signup.php?error='.$userDetail); 
            else 
            {
                
               //$response = $funobj->login_user($signup_obj->newUserId, $param['password'] , 0 ,$param['domain']);
                header('Location: /userhome.php');      
                
                exit();
            }

        }
    }
 else     
 {
     $responseparam =  $funobj->getUserFromEmail($email);
     
     logmonitor("phone91-google", json_encode($responseparam));
     
      $response =  $funobj->getUserInformation(intval($responseparam[0]['userid']) , 1);
     

     if($response)
     {
        $host = $_SESSION['domain'];
        
        $userId = $response['userName'];
        $pwd = $response['password'];
        
        if(isset($_SESSION['page']) && !empty($_SESSION['page']))
            $host = $_SESSION['page'];
        
       $response = $funobj->login_user($userId, $pwd, 0 ,$host);
     }
     else 
     {
         if($_SESSION['domain'] == 'phone91.com')
            header('Location: http://phone91.com/signup.php?error='.$userDetail); 
        else 
        {
            header('Location: /userhome.php');    
        }
     }
     
     
 }
    
    exit(0);
}
else
{
    
    header('Location: ../index.php');
}
?>