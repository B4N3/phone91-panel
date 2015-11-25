<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once 'FBconfig.php';
$funobj->clearBrowserCache();
error_reporting(0);
if (isset($me)) {
    
	$email=$me["email"];
	
        
        if(!$funobj->checkEmail($email))
        {

            //NULL array(13) { ["id"]=> string(15) "100001856470484" ["name"]=> string(14) "Rahul Chordiya" 
            //["first_name"]=> string(5) "Rahul" ["last_name"]=> string(8) "Chordiya" 
            //["link"]=> string(41) "http://www.facebook.com/rahul.chordiya.12" 
            //["username"]=> string(17) "rahul.chordiya.12" 
            //["birthday"]=> string(10) "11/06/1988" 
            //["gender"]=> string(4) "male" 
            //["email"]=> string(19) "rahul@hostnsoft.com" 
            //["timezone"]=> float(5.5) ["locale"]=> string(5) "en_US" 
            //["verified"]=> bool(true) ["updated_time"]=> string(24) "2012-11-03T07:48:00+0000" }
            
            $_SESSION["signup_email"]=$email;
            $_SESSION["signup_first_name"]=$me["first_name"];
            $_SESSION["signup_last_name"]=$me["last_name"];
            $_SESSION['signup_picture']='https://graph.facebook.com/'.$uid.'/picture';
            
            
            header('Location: /signup.php');
        }

	//header('Location: /userhome.php');
	exit(0);
}
else
{
	$extra_params = array('scope' => 'email,sms,publish_stream,status_update,user_birthday,user_location,user_work_history,user_online_presence,friends_online_presence,read_friendlists,xmpp_login',
           "nonsence" => "nonsence");
        $loginUrl = $facebook->getLoginUrl($extra_params);
       
       
	//$loginUrl = $facebook->getLoginUrl();
	header('Location: '.$loginUrl);
}
?>