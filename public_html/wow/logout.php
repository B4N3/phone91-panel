<?php 
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");

$host='';
//if(isset($_SESSION['currentHost']) && $_SESSION['currentHost'] != NULL){
//  $host = $_SESSION['currentHost']; 
//}



$host = $_SERVER['HTTP_HOST'];

$http = $funobj->getProtocol();

if(isset($_SESSION['acmId']))
{
    $redirectUrl = $http.$host.ADMIN_DIR.'login.php';
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
      header("location:"."http://".$host.ADMIN_DIR."logout.php");
      exit();
}

if($host != '')
{
  header("location:".$redirectUrl);
}
//}else
//header("Location: index.php");
?>