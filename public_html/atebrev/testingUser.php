<?php
require_once('login/fb/facebook.php');

  $config = array(
    'appId' => '209650962548024',
    'secret' => 'eeb2be29b2ff76afa54ecfa5fed1a33e',
      'cookies'=>true
//    'baseUrl' => 'http://voip91.com/sameer.php',
  );

  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
  print_R($_SESSION);
  echo $facebook->getLoginUrl();//array("redirect_uri"=>"http://voip91.com/testingUser.php")
  echo "<br/>";
  print_r($user_id);
?>
