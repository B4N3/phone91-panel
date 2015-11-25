<?php
/**
 * Timezone Setting
 */
date_default_timezone_set('asia/calcutta');

/**
  * Enable Sessions
  */
if(!session_id()) session_start();

/** 
 * Sandbox Mode - TRUE/FALSE
 */
$sandbox = TRUE;
$domain = $sandbox ? $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST'];

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
$api_username = $sandbox ? 'ankkuboss_api1.paypal.com' : 'payment_api1.walkover.in';
$api_password = $sandbox ? '1386154803' : 'W8CYN2N8XCD9DW3Z';
$api_signature = $sandbox ? 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AybgbuSDMKe25IsrKrHXiS6IwPMj' : 'AFcWxV21C7fd0v3bYYYRCpSSRl31AELpWQJU97cyGBWE12cUaQJvmkDm';

/**
 * Third Party User Values
 * These can be setup here or within each caller directly when setting up the PayPal object.
 */
$api_subject = '';	// If making calls on behalf a third party, their PayPal email address or account ID goes here.
$device_id = '';
$device_ip_address = $_SERVER['REMOTE_ADDR'];
?>