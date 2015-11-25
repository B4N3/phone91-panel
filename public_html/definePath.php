<?php
/**
 * @author Rahul Chordiya <rahul@hostnsoft.com>
 * @since 07 Aug 2013
 * @details File use to //Define All Necessary Constant which will be used accross the panel  
 */


//Auto check for test environment if domain is beta.voip91.com
if($_SERVER["HTTP_HOST"]=="localhost" || $_SERVER["HTTP_HOST"]=="127.0.0.1")
{
    $isTestEnvironment=1;
//    define("ENVIRONMENT","test");
}
else{//Set $isTestEnvironment=0; means live environment
    $isTestEnvironment=0;
}

defined('TESTENVIRONMENT') or define('TESTENVIRONMENT', $isTestEnvironment);
//ROOT_DIR is the physical path on server where all files are stored
defined("ROOT_DIR") or define("ROOT_DIR",$_SERVER['DOCUMENT_ROOT']."/");

//CLASS_DIR is location of all classes which are being used by system
defined("CLASS_DIR") or define("CLASS_DIR",$_SERVER['DOCUMENT_ROOT']."/classes/");

defined("ADMIN_DIR") or define("ADMIN_DIR","/wow/");//$_SERVER['DOCUMENT_ROOT'].

//set date format
defined("DATEFORMAT") or define('DATEFORMAT', 'd-m-Y H:i:s');
// is defined two times because may be used by somecode somewhere
// CALLSERVERURL is added on 07 aug
//define("CALLSERVERURL","https://voip91.com/");


if($isTestEnvironment)
{
    #change jsurl voip91 to beta.voip91 (30-07-2013)
    //
    defined("URL") or define("URL","http://localhost/");
    defined("callServerUrl") or define("callServerUrl","http://localhost/");
    defined("CALLSERVERURL") or define("CALLSERVERURL","http://localhost/");
    defined("JSURL") or define("JSURL","http://localhost/js/");
    defined("CSSURL") or define("CSSURL","http://localhost/css/");
    defined("IMGURL") or define("IMGURL","http://localhost/images/");
	defined("CALLSERVERURL1") or define("CALLSERVERURL1","http://localhost/");
  
}
else
{
    //
    defined("URL") or define("URL","http://".$_SERVER['HTTP_HOST']."");
    defined("callServerUrl") or define("callServerUrl","https://".$_SERVER['HTTP_HOST']."/");
	if( isset($_SERVER['HTTPS'] ) ) {
        defined("CALLSERVERURL") or define("CALLSERVERURL","http://".$_SERVER['HTTP_HOST']."/");
    } else {
        defined("CALLSERVERURL") or define("CALLSERVERURL","https://".$_SERVER['HTTP_HOST']."/");
    }
//    defined("CALLSERVERURL") or define("CALLSERVERURL","https://".$_SERVER['HTTP_HOST']."/");
    defined("JSURL") or define("JSURL","https://".$_SERVER['HTTP_HOST']."/js/");
    defined("CSSURL") or define("CSSURL","https://".$_SERVER['HTTP_HOST']."/css/");
    defined("IMGURL") or define("IMGURL","https://".$_SERVER['HTTP_HOST']."/images/");
    defined("ROOTURL") or define("ROOTURL","https://".$_SERVER['HTTP_HOST']."");
	defined("CALLSERVERURL1") or define("CALLSERVERURL1","http://".$_SERVER['HTTP_HOST']."/");
    
}



?>
