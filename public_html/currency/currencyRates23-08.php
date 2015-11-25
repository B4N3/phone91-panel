<?php
include_once("model.php");
#- author- sudhir pandey (sudhir@hostnsoft.com)
#- date- 01/07/2013
#- save currency rates of current date (USD,AUD,Euro). 


#function for get data of given url 
function get_data($url) {
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 02/07/2013
#function use for find currency amount of given currency code and amount 
function currencyAmount($to){
    if($to == "USD"){
        return 1;
    }
    $from="USD";
   #models object
   $conn = new models;
    
    #collection name 
    $currencyRate = "currencyRate";
    $start = date('Y-m-d')." 00:00:00";
    $end = date('Y-m-d',strtotime("+1 day"))." 00:00:00";        
    
    if($to == "EUR") $to = "EURO";
    if($from == "EUR") $from = "EURO";
   
    
    #column name of currency table 
    $curr_name=$from."_to_".$to;
    
    #condition where date is currend date 
    $condition = array("date"=>array('$gte'=>new MongoDate(strtotime($start)),'$lt'=>new MongoDate(strtotime($end))));
    $result =$conn->mongo_find($currencyRate,$condition);
    foreach($result as $res){
        
        # per currency amount (1 INR = ? USD)
        $per_currency = $res['currency'][$curr_name];
    }
    
    return $per_currency;
    
} #end of function 


#function use for convert other currency to INR where amount is 1 .
# like 1 USD to  ? INR
function convertCurr($from,$to){
    #handle division by zero exception by try catch 
//    try {
//    if(!$currency){   
//        throw new Exception('Division by zero.');
//    }else
    {
      #usd to inr (1 USD = 59 INR)
echo $from."currrrrrrrrrr".$to;        
        echo $fromCurrency=currencyAmount($from);
        echo "@@";
        echo $toCurrency=currencyAmount($to);
      $data = ( $toCurrency / $fromCurrency);
    }
// }catch(Exception $ex){
//     echo $ex;
// }
 return $data;
}

#currecy record save into data base of current date 
/*
 * INR - USD
 * INR - AD
 * INR - EURO
 * USD - INR
 * AD  - INR
 * EURO- INR
 */

#get data of inr and use where amount is 1 
$urlUSD = "http://www.exchangerate-api.com/USD/INR/1?k=mhXK9-WknIS-he8My";
$USDamt = get_data($urlUSD);

$urlAED = "http://www.exchangerate-api.com/USD/AED/1?k=mhXK9-WknIS-he8My";
$AEDamt = get_data($urlAED);

$urlEuro = "http://www.exchangerate-api.com/USD/EUR/1?k=mhXK9-WknIS-he8My";
$Euroamt = get_data($urlEuro);




//echo "1 USD = ".$usd_inr." IND </br>";
//echo "1 AUD = ".$aed_inr." IND </br>";
//echo "1 EURO = ".$euro_inr." IND </br>";

#collection name 
$currencyRate = "currencyRate";

#models object
$conn = new models;

#check currency amount is already inserted in database or not
$start = date('Y-m-d')." 00:00:00";
$end = date('Y-m-d',strtotime("+1 day"))." 00:00:00";        

#condition where date is currend date 
$condition = array("date"=>array('$gte'=>new MongoDate(strtotime($start)),'$lt'=>new MongoDate(strtotime($end))));
$result =$conn->mongo_count($currencyRate,$condition);
if ($result <= 0){

$currency = array("USD_to_INR"=>(float)$USDamt,"USD_to_AED"=>(float)$AEDamt,"USD_to_EURO"=>(float)$Euroamt);//,"USD_to_INR"=>$usd_inr,"AED_to_INR"=>$aed_inr,"EURO_to_INR"=>$euro_inr
$data=array("date"=>new MongoDate(time()),"currency"=>$currency);
#insert current date currency record in database 
$conn->mongo_insert($currencyRate,$data);




}



//$urlAd = "www.voip91.com/currency/index.php?fr=INR&sdas=USD&amount=1?k=mhXK9-WknIS-he8My";



?>