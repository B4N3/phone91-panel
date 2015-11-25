<?php 
error_reporting(0);
ini_set('session.save_handler', 'memcache');
ini_set('session.save_path', 'tcp://localhost:11211?persistent=1');

ini_set("session.gc_maxlifetime", "3600");
if(session_id()=="")
{
// Always executed even if there's already an opened session
session_start();
}
//session_start();
include_once("/home/voip91/public_html/newapi/function_layer.php");
//include_once("function_layer.php");
define("ROOT_DIR",$_SERVER['DOCUMENT_ROOT']."/");

?>