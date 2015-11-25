<?php
/**
 * @author Ankit Patidar <AnkitPatidar@hostnsoft.com> on 25/10/2013
 * @file contains the details(constants) for payment gateway configuration
 * 
 */

//set default emails
if(!defined('DATEFORMAT'))
    define("DATEFORMAT","d-m-Y H:i:s");//set default date format

//get domain reseler id
$domainResult = $funobj->getDomainResellerId(HOST_NAME,2);
$commObj = new commonFunction();

//get paypal merchant id
$paypalArr = $commObj->getPGDetails($domainResult['resellerId']);
if(!empty($paypalArr))
{
    $papMerchantId = $paypalArr['merchantId'];
}
else
    $papMerchantId = 'utteru';//'phonee';

//set company email id
if(empty($domainResult['compEmail']))
{
    $compEmail = 'payment@phone91.com'; //set default emal
}
else
    $compEmail = $domainResult['compEmail']; //set company email
 
$paypalMode = FALSE;
$cashuMode = FALSE;
$sandbox = FALSE;
//set paypal details
if(HOST_NAME == TESTING_SERVER_NAME || HOST_NAME == LOCALHOST || HOST_NAME == TESTING_SERVER_NAME1 )
{
    $paypalMode = TRUE;
    $cashuMode = TRUE;
    $sandbox = TRUE;
    
}



$paypalUrl = $paypalMode ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
//$paypalUrl = $paypalMode ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$paypalMerchantId = $paypalMode ? 'ankkuboss' : $papMerchantId;
$businessMail = $paypalMode ? 'ankkuboss2@gmail.com' : $compEmail;


//payment@walkover.in
if(!defined('BUSINESSMAIL'))
    define("BUSINESSMAIL",$businessMail);//set business mail

if(!defined('DEFEMAIL1'))
    define("DEFEMAIL1","Ankitpatidar@hostnsoft.com");//set default email

if(!defined('DEFEMAIL2'))
    define("DEFEMAIL2","shubhendra@hostnsoft.com");//set default email

if(!defined('DEFEMAIL3'))
    define("DEFEMAIL3","shubh124421@gmail.com");//set default email

if(!defined('DEFEMAIL4'))
    define("DEFEMAIL4","alok@phone91.com");//set default email

if(!defined('DEFEMAIL5'))
    define("DEFEMAIL5","rahulverma@phone91.com");//set default email

$successEmails = array(DEFEMAIL1);//$defEmail2,,DEFEMAIL2

if(!$paypalMode)
{
    //$cashuSuccessEmails[] = DEFEMAIL2;
    $successEmails[]= BUSINESSMAIL;
    //$successEmails[]= DEFEMAIL2;
    $successEmails[]= DEFEMAIL4;
    $successEmails[]= DEFEMAIL5;
}


//if(!defined('PAYPALURL'))
    //define("PAYPALURL","https://www.paypal.com/cgi-bin/webscr");//set default URL for paypal,https://www.sandbox.paypal.com/cgi-bin/webscr
//https://www.paypal.com/cgi-bin/webscr

if(!defined('PAYPALURL'))
    define("PAYPALURL",$paypalUrl);

//set paypal merchant id
if(!defined('PAYPALMERCHANTID'))
    define("PAYPALMERCHANTID",$paypalMerchantId); 


//set cashu details


$cashuUrl = $cashuMode ? 'https://sandbox.cashu.com/cgi-bin/pcashu.cgi' : 'https://www.cashu.com/cgi-bin/pcashu.cgi';
$cashuMerchantId = $cashuMode ? 'ankkuboss' : 'phonee';
$cashuKeywork = $cashuMode ? 'beyond' : 'voip';


if(!defined('CASHUURL'))
    define("CASHUURL",$cashuUrl);//set default URl for cashu,https://sandbox.cashu.com/cgi-bin/pcashu.cgi
//https://www.cashu.com/cgi-bin/pcashu.cgi

//if(!defined('CASHUURL'))
//define("CASHUURL","https://www.cashu.com/cgi-bin/pcashu.cgi");

//set cashu merchant id
//define("CASHUMERCHANTID","phonee"); //ankkuboss,phonee

if(!defined('CASHUMERCHANTID'))
    define("CASHUMERCHANTID",$cashuMerchantId); //ankkuboss,phonee

//set cashu keywaord
//define("CASHUKEYWORD","voip"); //beyond,voip

if(!defined('CASHUKEYWORD'))
    define("CASHUKEYWORD",$cashuKeywork); //beyond,voip


//set default mail on sucesses


//set default mail on sucesses
$cashuSuccessEmails = array(DEFEMAIL1);//$defEmail2,,DEFEMAIL2

if(!$cashuMode)
{

   // $cashuSuccessEmails[] = DEFEMAIL2;
    $cashuSuccessEmails[]= DEFEMAIL4;
    $cashuSuccessEmails[]= DEFEMAIL5;
    //$successEmails[]= DEFEMAIL2;
}
//set error mail
$errorMails = array(DEFEMAIL1);

//set error mail
$cashuErrorMails = array(DEFEMAIL1);

/**
 * Timezone Setting
 */
date_default_timezone_set('asia/calcutta');

/**
  * Enable Sessions
  */
//if(!session_id()) session_start();

/** 
 * Sandbox Mode - TRUE/FALSE
 */

$domain = $sandbox ? HOST_NAME : HOST_NAME;


//if(!$sandbox)
//{
//    //$cashuSuccessEmails[] = DEFEMAIL2;
//    $successEmails[]= DEFEMAIL2;
//    $successEmails[]= DEFEMAIL4;
//    $successEmails[]= DEFEMAIL5;
//}

/**
 * Enable error reporting if running in sandbox mode.
 */
if($sandbox)
{
	error_reporting(E_ALL);
	ini_set('display_errors', '1');	
}

/**
 * API Credentials
 */
$api_version = '85.0';
//$application_id = $sandbox ? 'APP-80W284485P519543T' : '';	// Only required for Adaptive Payments.  You get your Live ID when your application is approved by PayPal.
$developer_account_email = 'DEVELOPER_EMAIL_ADDRESS';		// This is what you use to sign in to http://developer.paypal.com.  Only required for Adaptive Payments.
$api_username = $sandbox ? 'ankkuboss_api1.paypal.com' : 'payment_api1.phone91.com';
$api_password = $sandbox ? '1386154803' : '6A8FGGFV646BZD5M';
$api_signature = $sandbox ? 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AybgbuSDMKe25IsrKrHXiS6IwPMj' : 'A3AMCS2mQMoJp2JARK5.at8YCs0LAgc0JxFaJ1WuUzp5SQWZ1BPM3gIW';

//$returnUrl = $sandbox ? 'http://testserver.phone91.com/checkoutResponse.php' : 'https://voip91.com/checkoutResponse.php';
$returnUrl = $sandbox ? PROTOCOL.HOST_NAME.'/checkoutResponse.php' : PROTOCOL.HOST_NAME.'/checkoutResponse.php';
$cancelUrl = $sandbox ? PROTOCOL.HOST_NAME : PROTOCOL.HOST_NAME;
$bussiMail = $sandbox ? 'ankkuboss@paypal.com' : 'payment@phone91.com';
$notifyUrl = $sandbox ? PROTOCOL.HOST_NAME.'/checkoutResponse.php' : PROTOCOL.HOST_NAME.'/checkoutResponse.php';
//$notifyUrl = $sandbox ? 'http://testserver.phone91.com/checkoutResponse.php' : 'https://voip91.com/checkoutResponse.php';
//set apiUserName
if(!defined('API_USERNAME'))
    define ('API_USERNAME', $api_username);

if(!defined('BUSI_MAIL'))
    define ('BUSI_MAIL', $bussiMail);
//set api password
if(!defined('API_PASSWORD'))
    define ('API_PASSWORD', $api_password);

//set api signature
if(!defined('API_SIGNATURE'))
    define ('API_SIGNATURE', $api_signature);

//set payment mode
if(!defined('PAYMENT_MODE_EC'))
    define ('PAYMENT_MODE', $sandbox);

//set return Url
if(!defined('RETURNURL_EC'))
    define ('RETURNURL_EC', $returnUrl);


//set cancel url
if(!defined('CANCELURL_EC'))
    define ('CANCELURL_EC', $cancelUrl);

//set cancel url
if(!defined('NOTIFY_URL_EC'))
    define ('NOTIFY_URL_EC', $notifyUrl);
/**
 * Third Party User Values
 * These can be setup here or within each caller directly when setting up the PayPal object.
 */
$api_subject = '';	// If making calls on behalf a third party, their PayPal email address or account ID goes here.
$device_id = '';
$device_ip_address = $_SERVER['REMOTE_ADDR'];




?>
