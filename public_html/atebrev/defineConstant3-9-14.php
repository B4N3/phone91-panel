<?php
/**
 * @author Ankit patidar <ankitpatidar@hostnsoft.com> on 23/11/2013
 * @since 23/11/2013
 * @copyright (c) 2013, Phone91
 * @version 1
 * Description it contains all regular expression constants 
 */

//for number
defined('NOTNUM_REGX') or define('NOTNUM_REGX','/[^0-9]+/');

defined('NOTPHNNUM_REGX') or define('NOTPHNNUM_REGX','/[^0-9]+/');

//if(!defined('NOTPHNNUMSPACE_REGX'))
//    define('NOTPHNNUMSPACE_REGX','/[^a-zA-Z0-9\@\s\.\_\-]+/');


defined('NOTALPHANUM_REGX') or define('NOTALPHANUM_REGX','/[^0-9a-zA-Z]+/');

//for mobiole number
defined('NOTMOBNUM_REGX') or define('NOTMOBNUM_REGX','/[^0-9]+/');

//for mobiole number
defined('DECIMAL_REGX') or define('DECIMAL_REGX','/^[0-9]+(\.[0-9]{1,3})?$/');

//for username
defined('NOTUSERNAME_REGX') or define('NOTUSERNAME_REGX','/[^a-zA-Z0-9\@\_\.]+/');

defined('NOTUSERNAME_EMAIL_REGX') or define('NOTUSERNAME_EMAIL_REGX','/[^a-zA-Z0-9\_]+/');

defined('EMAIL_REGX') or define('EMAIL_REGX','/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix');

//for username
defined('NOTUSERNAME_NORMAL_REGX') or define('NOTUSERNAME_NORMAL_REGX','/[^a-zA-Z0-9\_]+/');


//for password
defined('NOTPASSWORD_REGX') or define('NOTPASSWORD_REGX','/[^a-zA-Z0-9\@\$\}\{\.\_\-\(\)\]\[\:]+/');

//for email
defined('EMAIL_REGX') or define('EMAIL_REGX','/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/i');

defined('URL_REGX') or define('URL_REGX',"/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z0-9]{2,3}(\/\S*)?/");
//    define('URL_REGX',"/(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/");

//for alphabtic
defined('NOTALPHABATE_REGX') or define('NOTALPHABATE_REGX','/[^A-Za-z]/');

defined('NOTALPHABATESPACE_REGX') or define('NOTALPHABATESPACE_REGX','/[^A-Za-z\s]/');

defined('NOTALPHANUMSPACE_REGX') or define('NOTALPHANUMSPACE_REGX','/[^A-Za-z0-9\s]/');

defined('NOTALPHABATECOMMA_REGX') or define('NOTALPHABATECOMMA_REGX','/[^A-Za-z\,\s]/');

defined('NOTALPHANUMCOMMA_REGX') or define('NOTALPHANUMCOMMA_REGX','/[^A-Za-z0-9\,\s]/');

//for not plan name
defined('NOTPLANNAME_REGX') or define('NOTPLANNAME_REGX','/[^a-zA-Z0-9\@\_\-\s]+/');

//for not country name
defined('NOTCOUNTRY_REGX') or define('NOTCOUNTRY_REGX','/[^a-zA-Z\-\_\s]+/');

defined('NOTTEXT_REGX') or define('NOTTEXT_REGX','/[^a-zA-Z0-9\-\_\s\/\-\_\@\.\:\,\!\%\$\&\(\)\+]+/');

defined('IP_ADDRESS') or define('IP_ADDRESS','/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/');

defined('PHNNUM_REGX') or define('PHNNUM_REGX','/^[0-9]{8,18}+/');

defined('MANDRILLKEY') or define("MANDRILLKEY","UyYmryeHJCDreWdOvy7RSQ");

defined('HOST_NAME') or define("HOST_NAME",$_SERVER['HTTP_HOST']);    

defined('LOCALHOST') or define("LOCALHOST",'192.168.1.191'); 

defined('TESTING_SERVER_NAME') or define("TESTING_SERVER_NAME",'testing.phone91.com'); 

defined('TESTING_SERVER_NAME1') or define("TESTING_SERVER_NAME1",'testing1.phone91.com'); 



//get and set protocol
 if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') 
        {
            $protocol = 'https://';
        }
        else 
        {
            $protocol = 'http://';
        }
        
defined('PROTOCOL') or define("PROTOCOL",$protocol); 

//set Mode
if($_SERVER["HTTP_HOST"]=="localhost" || $_SERVER["HTTP_HOST"]=="127.0.0.1" || $_SERVER["HTTP_HOST"] =='testing.phone91.com')
{
    $testMode=true;
    $redirectUrl = $_SERVER["HTTP_HOST"].'/googleInsertContact.php';
//    define("ENVIRONMENT","test");
}
else{//Set $isTestEnvironment=0; means live environment
    $testMode=false;
     $redirectUrl = 'voice.phone91.com/googleInsertContact.php';
}

//set configuration for google contact import

$gClientId  = $testMode ? '389356668086-e3g4q6vho7613hoj60hef2shkh7cru1s.apps.googleusercontent.com' : '389356668086.apps.googleusercontent.com';
$gClientSceret = $testMode ? 'VT6k3uLUEgmDXdq58DiM0mlq' : 'KcE9c4ZcxsbnlR_gPXJNktIr';
$gRedirectUrl = $testMode ? 'http://'.$redirectUrl : $protocol.$redirectUrl;

defined('GCLIENTID') or define("GCLIENTID",$gClientId); 
defined('GCLIENTSECRET') or define("GCLIENTSECRET",$gClientSceret); 
defined('GREDIRECTURL') or define("GREDIRECTURL",$gRedirectUrl); 

defined('ENCRYPT_KEY') or define("ENCRYPT_KEY",'356b02Z06Mx0c8L1Py01f097F5045LN6f4a5t612813p690V8t96LoS916dtP91C'); 
?>