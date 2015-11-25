<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
require_once 'src/apiClient.php';
require_once 'src/contrib/apiOauth2Service.php';
$funobj->clearBrowserCache();

$client = new apiClient();
$client->setApplicationName("phone91.com Google Login");
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.profile','https://www.googleapis.com/auth/userinfo.email'));
$oauth2 = new apiOauth2Service($client);
//$apiClient->setAccessToken($_SESSION['oauth_access_token']);
if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
}

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  $client->revokeToken();
}

if ($client->getAccessToken()) {
  $user = $oauth2->userinfo->get();
  //var_dump($user);
  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
  $img = filter_var($user['picture'], FILTER_VALIDATE_URL);
  $personMarkup = "$email<div><img src='$img?sz=50'></div>";
  // The access token may have been updated lazily.
  $_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
}
if(isset($authUrl)) {
	header("Location:".$authUrl." ");
}
elseif(isset($email))
{
    //array(12) { ["id"]=> string(21) "112393963062640048398" 
    //["email"]=> string(19) "rahul@hostnsoft.com" 
    //["verified_email"]=> bool(true) 
    //["name"]=> string(14) "Rahul Chordiya" 
    //["given_name"]=> string(5) "Rahul" 
    //["family_name"]=> string(8) "Chordiya" 
    //["link"]=> string(45) "https://plus.google.com/112393963062640048398" 
    //["picture"]=> string(92) "https://lh6.googleusercontent.com/-IxFU8rN6ErM/AAAAAAAAAAI/AAAAAAAAAEs/iY2VBTGk9GI/photo.jpg" 
    //["gender"]=> string(4) "male" 
    //["birthday"]=> string(10) "0000-06-06" 
    //["locale"]=> string(2) "en" 
    //["hd"]=> string(13) "hostnsoft.com" } 
    
    $_SESSION["signup_email"]=$email;
    $_SESSION["signup_first_name"]=$user["given_name"];
    $_SESSION["signup_last_name"]=$user["family_name"];
    $_SESSION["signup_picture"]=$user["picture"];
    
    
    if(!$funobj->checkEmail($email))
        header('Location: /signup.php');
    
	//header('Location: /userhome.php');
}
?>