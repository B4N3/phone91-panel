<?php
/**
 * @author Rahul Chordiya <rahul@hostnsoft.com>
 * @since 07 Aug 2013
 * @details File use to //Define All Necessary Constant which will be used accross the panel  
 */


//Auto check for test environment if domain is beta.voip91.com
if($_SERVER["HTTP_HOST"]=="beta.voip91.com")
{
    $isTestEnvironment=1;
//    define("ENVIRONMENT","test");
}
else{//Set $isTestEnvironment=0; means live environment
    $isTestEnvironment=0;
}

//ROOT_DIR is the physical path on server where all files are stored
define("ROOT_DIR",$_SERVER['DOCUMENT_ROOT']."/");

//CLASS_DIR is location of all classes which are being used by system
define("CLASS_DIR",$_SERVER['DOCUMENT_ROOT']."/classes/");

//set date format
define('DATEFORMAT', 'd-m-Y H:i:s');
// is defined two times because may be used by somecode somewhere
// CALLSERVERURL is added on 07 aug
//define("CALLSERVERURL","https://voip91.com/");


if($isTestEnvironment)
{
    #change jsurl voip91 to beta.voip91 (30-07-2013)
    //
    define("URL","http://beta.voip91.com/");
    define("callServerUrl","http://beta.voip91.com/");
    define("CALLSERVERURL","http://beta.voip91.com/");
    define("JSURL","http://beta.voip91.com/js/");
    define("CSSURL","https://voip91.com/css/");
    define("IMGURL","https://voip91.com/images/");
  
}
else
{
    //
    define("URL","http://voip91.com");
    define("callServerUrl","https://voip91.com/");
    define("CALLSERVERURL","https://voip91.com/");
    define("JSURL","https://voip91.com/js/");
    define("CSSURL","https://voip91.com/css/");
    define("IMGURL","https://voip91.com/images/");
    
}



?>