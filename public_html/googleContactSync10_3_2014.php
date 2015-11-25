<?php
/* @author : sameer 
 * @created : 30-08-13
 * @desc : code for google contact sync 
 */

//$client_id='389356668086.apps.googleusercontent.com';//your client id
//
////$redirect_uri='http://localhost/oauth/refreshtoken.php';//you redirect url
//$redirect_uri='https://voip91.com/googleInsertContact.php';//you redirect url
$client_id='389356668086.apps.googleusercontent.com';//your client id
$redirect_uri='http://voip91.com/googleInsertContact.php';//you redirect url
$approval_prompt = 'force';//parameter to get refresh token
$scope = "https://www.google.com/m8/feeds/"; //google scope to access
$state = base64_encode(trim($_SERVER['HTTP_HOST']).'_||_'.$_SESSION['id']); //optional - could be whatever value you want

$access_type = "offline"; //optional - allows for retrieval of refresh_token for offline access

$loginUrl = sprintf("https://accounts.google.com/o/oauth2/auth?scope=%s&state=%s&redirect_uri=%s&response_type=code&client_id=%s&access_type=%s&approval_prompt=%s", $scope, $state, $redirect_uri, $client_id, $access_type,$approval_prompt);
 


?>
<!--show login url-->
<!--<a href="<?php // echo $loginUrl ?>">Login with Google account using OAuth 2.0</a>-->
<!--?>-->
