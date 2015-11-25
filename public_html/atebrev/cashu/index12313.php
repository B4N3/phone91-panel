<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>

<?php

$amount = 7; 
$token = md5('phonee:'.$amount.':usd:voip'); 

$testmode = 1 ; 

$client = new SoapClient("http://sandbox.cashu.com/secure/payment.wsdl", array('trace' => true));
$request = $client->DoPaymentRequest('phonee',$token,'Testing the SOAP','usd',$amount,'en','29','Testing the SOAP','txt2','txt3','txt4','txt5',$testmode,'');
echo $request ; 

mail("shubh124421@gmail.com","The Data From Url",print_r($_REQUEST,1));

?>

</body>
</html>