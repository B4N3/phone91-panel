<?php
/*
 * Copyright 2012 Google Inc.
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
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_PlusService.php';

error_reporting(-1);


    
$oauth2_client_id='30203330065.apps.googleusercontent.com';
    $oauth2_client_secret='U_Y0gOJyykEZRKVlQ1lAu03Z';
    $oauth2_redirect_uri='https://voip91.com/login/login-google.php';
    
    $OAUTH2_CLIENT_ID = $oauth2_client_id;
$OAUTH2_CLIENT_SECRET = $oauth2_client_secret;

$client = new Google_Client();
$client->setApplicationName('Google Contacts For Phone91');
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
//$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
//  FILTER_SANITIZE_URL);
$client->setRedirectUri($oauth2_redirect_uri);

    
$client->setScopes("http://www.google.com/m8/feeds/");
// Documentation: http://code.google.com/apis/gdata/docs/2.0/basics.html
// Visit https://code.google.com/apis/console?api=contacts to generate your
// oauth2_client_id, oauth2_client_secret, and register your oauth2_redirect_uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_redirect_uri');
// $client->setDeveloperKey('insert_your_developer_key');

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
}

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  $client->revokeToken();
}

$plus = new Google_PlusService($client);
if ($client->getAccessToken()) {
  $me = $plus->people->get('me');
  print "Your Profile: <pre>" . print_r($me, true) . "</pre>";

  $params = array('maxResults' => 100);
  $activities = $plus->activities->listActivities('me', 'public', $params);
  print "Your Activities: <pre>" . print_r($activities, true) . "</pre>";
  
  $params = array(
    'orderBy' => 'best',
    'maxResults' => '20',
  );
  $results = $plus->activities->search('Google+ API', $params);
  foreach($results['items'] as $result) {
    print "Search Result: <pre>{$result['object']['content']}</pre>\n";
  }

  // The access token may have been updated lazily.
  $_SESSION['token'] = $client->getAccessToken();
}


if ($client->getAccessToken()) {
  $req = new Google_HttpRequest("https://www.google.com/m8/feeds/contacts/default/full");
  $val = $client->getIo()->authenticatedRequest($req);

  // The contacts api only returns XML responses.
  $response = json_encode(simplexml_load_string($val->getResponseBody()));
  print "<pre>" . print_r(json_decode($response, true), true) . "</pre>";

  // The access token may have been updated lazily.
  $_SESSION['token'] = $client->getAccessToken();
} else {
  $auth = $client->createAuthUrl();
}



if (isset($auth)) {
    print "<a class=login href='$auth'>Connect Me!</a>";
  } else {
    print "<a class=logout href='?logout'>Logout</a>";
}

