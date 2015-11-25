<?php

//Send SMSto 91
function SendSMS($tempparam) {
$connect_url = "http://msg91.com/international/sendhttp.php"; // Do not change

$param["user"] = $tempparam[user]; // beep7 profile ID
$param["password"] = $tempparam[password]; // beep7 password
$senderid=$_SERVER['HTTP_HOST'];
$param["sender"] = $tempparam[sender];
$param["mobiles"]=$tempparam[mobiles];
$param["message"]=$tempparam[message];
foreach($param as $key=>$val){ 
$request.= $key."=".urlencode($val);
$request.= "&";
}
$request = substr($request, 0, strlen($request)-1);
$url2 = $connect_url."?".$request;
//mail("indoreankita@gmail.com","URL",$url2);
$ch = curl_init($url2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);
return $curl_scraped_page;
}

?>