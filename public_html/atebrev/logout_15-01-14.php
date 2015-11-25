<?php 
include_once("session.php");
include_once("function_layer.php");
$funObj = new fun();
$host='';
if(isset($_SESSION['currentHost']) && $_SESSION['currentHost'] != NULL){
  $host = $_SESSION['currentHost']; 
}

$session_id=session_id();        


if(isset($_REQUEST['url']) && $_REQUEST['url'] != "")
    $funObj->insertLandingPage($_REQUEST['url'],$_SESSION['id']);

session_destroy();
session_write_close();
session_unset();
session_commit();
unset($_SESSION);



if($host == 'phone91.com')
{
      header("location:"."http://".$host."/logout.php");
      exit();
}

if($host != ''){
  header("location:"."http://".$host."/index.php");
}else
header("Location: index.php");
?>