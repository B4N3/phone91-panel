<?php
//if(isset($_REQUEST['google']))
session_start();
require 'GoogleOpenID.php';
$googleLogin = GoogleOpenID::getResponse();
  if($googleLogin->success()){
    $user_id = $googleLogin->identity();
	$user_email = $googleLogin->email();
	//echo $googleLogin->assoc_handle();
	echo $_GET['openid_assoc_handle'];
	if($_SESSION['myhandler']==$_GET['openid.assoc_handle'])
	{
		echo 'hello';
	}
	//else
	{
		echo 'bbye';
	}
  }
?>