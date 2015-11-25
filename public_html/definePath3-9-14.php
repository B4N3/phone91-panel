<?php
/**
 * @author Rahul Chordiya <rahul@hostnsoft.com>
 * @since 07 Aug 2013
 * @details File use to //Define All Necessary Constant which will be used accross the panel  
 *Modified by ANkit Patidar <ankitpatidar@hostnsoft.com>
 */

//ROOT_DIR is the physical path on server where all files are stored
define("ROOT_DIR",$_SERVER['DOCUMENT_ROOT']."/");

//CLASS_DIR is location of all classes which are being used by system
define("CLASS_DIR",$_SERVER['DOCUMENT_ROOT']."/classes/");

//set date format
define('DATEFORMAT', 'd-m-Y H:i:s');

defined("ADMIN_DIR") or define("ADMIN_DIR","/wow/");

if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') 
        {
            $protocol = 'https://';
        }
        else 
        {
            $protocol = 'http://';
        }




    if(!defined('URL'))    
        define("URL",$protocol.$_SERVER['HTTP_HOST']."");
   
    if(!defined('callServerUrl'))
        define("callServerUrl",$protocol.$_SERVER['HTTP_HOST']."/");
    
    if(!defined('CALLSERVERURL'))
        define("CALLSERVERURL",$protocol.$_SERVER['HTTP_HOST']."/");
    
    if(!defined('JSURL'))
        define("JSURL",$protocol.$_SERVER['HTTP_HOST']."/js/");
    
    if(!defined('CSSURL'))
        define("CSSURL",$protocol.$_SERVER['HTTP_HOST']."/css/");
    
    if(!defined('IMGURL'))
        define("IMGURL",$protocol.$_SERVER['HTTP_HOST']."/images/");
   
    if(!defined('ROOTURL'))
        define("ROOTURL",$protocol.$_SERVER['HTTP_HOST']."");
    
//}



?>