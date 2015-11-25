<?php

$nowdate = date('Y-m-d')  . "T" . date('H:i:s');
$expirdate = date('Y-m-d') . "T"  . date('H:i:s')."+01:07";

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient('https://staging.cashu.com/merchants/cashUPayments.wsdl',array(
    "trace"      => 1));

$header = array(
		'msgType' => 'CUD',
		'msgDate' => $nowdate
		
) ;


$CUD = array(
		'merchantID' => 'phonee' ,
		'orderID' => '132321123',
		'amount' => '7',
		'currency' => 'usd' ,
		'itemName' => 'ITEM_DESC1' ,
		'expiryDate' => $expirdate ,
		'clientName' => 'shubhendra' ,
		'clientEmail' => 'shubhendra@hostnsoft.com' ,
		'clientMobile' => '919977389948' ,
		'ticketNumber' => '12456345'
) ;

$messageBody = array(
		'header' => $header ,
		'CUD' => $CUD ,
		'signature' => 'voip'
);


$params = array( 'message'=> $messageBody );


$result = $client->__SoapCall('PAYMENT_METHOD', array($params));

echo print_r($client->__last_request) . "\n";
echo print_r($client->__last_response) . "\n\n";
echo print_r($result) . "\n" . "<br>";

echo $nowdate . "<br>";
echo $expirdate;
?>