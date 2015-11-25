<?php

/**
*@author Ankit Patidar <ankitpatidar@hostnsoft.com>
*@since 26/8/2014
*/
defined('HOST_NAME') or define("HOST_NAME",$_SERVER['HTTP_HOST']);    
defined('TESTING_SERVER_NAME') or define("TESTING_SERVER_NAME",'testing.phone91.com'); 

defined('TESTING_SERVER_NAME1') or define("TESTING_SERVER_NAME1",'testing1.phone91.com'); 


defined('ENCRYPT_KEY') or define("ENCRYPT_KEY",'356b02Z06Mx0c8L1Py01f097F5045LN6f4a5t612813p690V8t96LoS916dtP91C'); 

 if(HOST_NAME == TESTING_SERVER_NAME || HOST_NAME == TESTING_SERVER_NAME1 )
 {
     defined('CONN_HOSTNAME') or define("CONN_HOSTNAME",'localhost'); 
    defined('CONN_USER') or define("CONN_USER",'voip91_switch'); 
    defined('CONN_PASSWORD') or define("CONN_PASSWORD",'yHqbaw4zRWrUWtp8'); 
    defined('CONN_DBNAME') or define("CONN_DBNAME",'voip91_switch'); 
 }
 else
 {
    defined('CONN_HOSTNAME') or define("CONN_HOSTNAME",'localhost'); 
    defined('CONN_USER') or define("CONN_USER",'phone91'); 
    defined('CONN_PASSWORD') or define("CONN_PASSWORD",'yHqbaw4zRWrUWtp8'); 
    defined('CONN_DBNAME') or define("CONN_DBNAME",'voip'); 

 }


defined('MANDRILLKEY') or define("MANDRILLKEY","UyYmryeHJCDreWdOvy7RSQ");
//set Mode
if($_SERVER["HTTP_HOST"]=="localhost" || $_SERVER["HTTP_HOST"]=="127.0.0.1" || $_SERVER["HTTP_HOST"] =='testing.phone91.com')
{
    $testMode=true;
    $redirectUrl = $_SERVER["HTTP_HOST"].'/googleInsertContact.php';
    $mongoDb = 'phone91testing';
//    define("ENVIRONMENT","test");
}
else{//Set $isTestEnvironment=0; means live environment
    $testMode=false;
    $mongoDb = 'phone91';
     $redirectUrl = 'voice.phone91.com/googleInsertContact.php';
}

defined('MONGO_DBNAME') or define("MONGO_DBNAME",$mongoDb);
//set configuration for google contact import

$gClientId  = $testMode ? '389356668086-e3g4q6vho7613hoj60hef2shkh7cru1s.apps.googleusercontent.com' : '389356668086.apps.googleusercontent.com';
$gClientSceret = $testMode ? 'VT6k3uLUEgmDXdq58DiM0mlq' : 'KcE9c4ZcxsbnlR_gPXJNktIr';
$gRedirectUrl = $testMode ? 'http://'.$redirectUrl : $protocol.$redirectUrl;

defined('GCLIENTID') or define("GCLIENTID",$gClientId); 
defined('GCLIENTSECRET') or define("GCLIENTSECRET",$gClientSceret); 
defined('GREDIRECTURL') or define("GREDIRECTURL",$gRedirectUrl); 



?>