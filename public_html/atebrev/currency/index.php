<?php

include_once("model.php");
date_default_timezone_get(); 
 ini_get('date.timezone');
 ini_set('date.timezone','Asia/Kolkata');
 ini_get('date.timezone');
 
#- author- sudhir pandey (sudhir@hostnsoft.com)
#- date- 02/07/2013
#- file use for get amount of given currency from database 
#- call currencyAmount function with requested value (from ,to and amount).
#- $_request['from'] : currency code for given amount
#- $_request['to'] : currency code for resulted amount 
//$url = "www.voip91.com/currency/index.php?from=INR&to=USD&amount=1"
#check from value that it is set or not
if (isset($_REQUEST['from']) && $_REQUEST['from']) {
    $from = $_REQUEST['from'];
    #check to value that it is set or not
    if (isset($_REQUEST['to']) && $_REQUEST['to']) {
        $to = $_REQUEST['to'];
    } else {
        $from = "INR";
        $to = "USD";
    }
} else {
    $from = "INR";
    $to = "USD";
}
#get amount value form request 
$amount = (isset($_REQUEST['amount']) && $_REQUEST['amount']) ? $_REQUEST['amount'] : 0;

//echo currencyAmount($from,$to,$amount);

echo convertCurr($from, $to) * $amount;


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 02/07/2013
#function use for find currency amount of given currency code and amount 
function currencyAmount($to) {
    if ($to == "USD") {
        return 1;
    }
    $from = "USD";
    #models object
    $conn = new models;

    #collection name 
    $currencyRate = "currencyRate";
    $start = date('Y-m-d') . " 00:00:00";
    $end = date('Y-m-d', strtotime("+1 day")) . " 00:00:00";

    if ($to == "EUR")
        $to = "EURO";
    if ($from == "EUR")
        $from = "EURO";


    #column name of currency table 
    $curr_name = $from . "_to_" . $to;

    #condition where date is currend date 
    $condition = array("date" => array('$gte' => new MongoDate(strtotime($start)), '$lt' => new MongoDate(strtotime($end))));
    $result = $conn->mongo_find($currencyRate, $condition);
    foreach ($result as $res) {

        # per currency amount (1 INR = ? USD)
        $per_currency = $res['currency'][$curr_name];
    }

    return $per_currency;
}

#end of function 
#function use for convert other currency to INR where amount is 1 .
# like 1 USD to  ? INR

function convertCurr($from, $to) {

    $fromCurrency = currencyAmount($from);
    //"@@";
    $toCurrency = currencyAmount($to);
    $data = ( $toCurrency / $fromCurrency);

    return $data;
}

?>
