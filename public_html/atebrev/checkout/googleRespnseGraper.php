<?php   
$data="909858575103851:hZTtCFv5bQTrpZf1DFGWvg";
$authKey=  base64_encode($data);
$serialNumber=759279723591914;
$header_arr = array("Authorization: Basic ".$authKey,
                    "Content-Type: application/xml; charset=UTF-8",
            "Accept: application/xml; charset=UTF-8");

$request='_type=notification-history-request&serial-number='.$serialNumber;
$test_URL="https://checkout.google.com/api/checkout/v2/reports/Merchant/909858575103851";
$ch = curl_init($test_URL);     
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_arr);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$body = curl_exec($ch);

if (curl_errno($ch)) {
  $log.=', error! :'.curl_error($ch);
} else {
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $log.=', status code is: '.$status_code;  //400
}

$from_email = "support@phone91.com";
$headers = "X-Priority: 1\n"; 
$headers .= "X-MSMail-Priority: High\n"; 
$headers .= "Return-Path: <$from_email>\n";
$headers .= "Reply-To: <".$from_email.">\n";
$headers .= "From:  <" . $from_email . ">\n";
$headers .= "X-Sender: <$from_email>\n";
$headers .= "X-Mailer: PHP/" . phpversion();

mail("rahul@hostnsoft.com","return value",$log,$headers);
?>

<?php
//
//$from_email = "support@phone91.com";
//$headers = "X-Priority: 1\n"; 
//$headers .= "X-MSMail-Priority: High\n"; 
//$headers .= "Return-Path: <$from_email>\n";
//$headers .= "Reply-To: <".$from_email.">\n";
//$headers .= "From:  <" . $from_email . ">\n";
//$headers .= "X-Sender: <$from_email>\n";
//$headers .= "X-Mailer: PHP/" . phpversion();
//
//$url="https://checkout.google.com/api/checkout/v2/reports/Merchant/909858575103851";
//$data='<notification-history-request xmlns="http://checkout.google.com/schema/2">
//  <serial-number>759279723591914</serial-number>
//</notification-history-request>';
//$data=urlencode($data);
//$objURL = curl_init($url);
//curl_setopt($objURL, CURLOPT_RETURNTRANSFER, 1); 
//curl_setopt($objURL,CURLOPT_POST,1);
//curl_setopt($objURL, CURLOPT_POSTFIELDS,$data);
//echo $retval = trim(curl_exec($objURL));
//curl_close($objURL);

mail("rahul@hostnsoft.com","return value",$retval,$headers);

?>