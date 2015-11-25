<?php 
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");

$host='';
//if(isset($_SESSION['currentHost']) && $_SESSION['currentHost'] != NULL){
//  $host = $_SESSION['currentHost']; 
//}



$host = $_SERVER['HTTP_HOST'];

if(preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$host))
    $http = 'http://';
else
    $http='https://';

if(isset($_SESSION['acmId']))
{
    $redirectUrl = $http.$host.'/admin/login.php';
}
else
{
    $redirectUrl = $http.$host.'/index.php';
}


$session_id=session_id();        

session_destroy();
session_write_close();
session_unset();
session_commit();
unset($_SESSION);



if($host == 'phone91.com')
{
      header("location:"."http://".$host."/admin/logout.php");
      exit();
}

if($host != '')
{
  header("location:".$redirectUrl);
}
//}else
//header("Location: index.php");
?>