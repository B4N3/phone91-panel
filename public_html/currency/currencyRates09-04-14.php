<?php
include_once("model.php");
#- author- sudhir pandey (sudhir@hostnsoft.com)
#- date- 01/07/2013
#- save currency rates of current date (USD,AUD,Euro). 
mail('sudhir@hostnsoft.com', 'cron run', "successfully cron run");
mail('sameer@hostnsoft.com', 'cron run', "successfully cron run");
mail('sudhirp29@gmail.com', 'cron run', "successfully cron run");

#function for get data of given url 
function get_data($url) {
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}



#currecy record save into data base of current date 
/*
 * USD - INR
 * USD - AED
 * USD - EUR
 */

#get data of inr and use where amount is 1 
$urlUSD = "http://www.exchangerate-api.com/USD/INR/1?k=mhXK9-WknIS-he8My";
$USDamt = get_data($urlUSD);

$urlAED = "http://www.exchangerate-api.com/USD/AED/1?k=mhXK9-WknIS-he8My";
$AEDamt = get_data($urlAED);

$urlEuro = "http://www.exchangerate-api.com/USD/EUR/1?k=mhXK9-WknIS-he8My";
$Euroamt = get_data($urlEuro);

$urlNZD = "http://www.exchangerate-api.com/USD/NZD/1?k=mhXK9-WknIS-he8My";
$NZDamt = get_data($urlNZD);


//echo "1 USD = ".$usd_inr." IND </br>";
//echo "1 AUD = ".$aed_inr." IND </br>";
//echo "1 EURO = ".$euro_inr." IND </br>";

#collection name 
$currencyRate = "currencyRate";

#models object
$conn = new models;

 date_default_timezone_get(); 
 ini_get('date.timezone');
 ini_set('date.timezone','Asia/Kolkata');
 ini_get('date.timezone');

#check currency amount is already inserted in database or not
$start = date('Y-m-d')." 00:00:00";       
$end = date('Y-m-d',strtotime("+1 day"))." 00:00:00";        

#condition where date is currend date 
$condition = array("date"=>array('$gte'=>new MongoDate(strtotime($start)),'$lt'=>new MongoDate(strtotime($end))));

$result =$conn->mongo_count($currencyRate,$condition);
if ($result <= 0){

$currency = array("USD_to_INR"=>(float)$USDamt,"USD_to_AED"=>(float)$AEDamt,"USD_to_EURO"=>(float)$Euroamt,"USD_to_NZD"=>(float)$NZDamt);//,"USD_to_INR"=>$usd_inr,"AED_to_INR"=>$aed_inr,"EURO_to_INR"=>$euro_inr
$data=array("date"=>new MongoDate(time()),"currency"=>$currency);
#insert current date currency record in database 
$conn->mongo_insert($currencyRate,$data);




}else{



#condition where date is currend date 
$condition = array("date"=>array('$gte'=>new MongoDate(strtotime($start)),'$lt'=>new MongoDate(strtotime($end))));

$currency = array("USD_to_INR"=>(float)$USDamt,"USD_to_AED"=>(float)$AEDamt,"USD_to_EURO"=>(float)$Euroamt,"USD_to_NZD"=>(float)$NZDamt);//,"USD_to_INR"=>$usd_inr,"AED_to_INR"=>$aed_inr,"EURO_to_INR"=>$euro_inr
$data=array('$set'=>array("date"=>new MongoDate(time()),"currency"=>$currency));


$result =$conn->mongo_update($currencyRate,$condition,$data);

}

//$urlAd = "www.voip91.com/currency/index.php?fr=INR&sdas=USD&amount=1?k=mhXK9-WknIS-he8My";



?>