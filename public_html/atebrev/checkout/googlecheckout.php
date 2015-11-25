<?php

/*
 * Created on May 9, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

// Point to the correct directory
//chdir("..");
// Include all the required files
include_once('../config.php');
require_once('library/googlecart.php');
require_once('library/googleitem.php');
//require_once('library/googleshipping.php');
//require_once('library/googletax.php');
error_reporting(-1);


$id_client = $_SESSION['userid'];
include('../dbconfig.php');
$db=dbConnect();

$result=mysql_query("select account_state,id_client,id_currency from clientsshared where id_client='".$id_client."'");
$get_userinfo=mysql_fetch_array($result);
$balance=$get_userinfo['account_state'];				
$sql = "insert into payments(id_client,client_type,money,data,type,description,actual_value,invoice_id) values('$id_client',32,0,now(),1,'Recharge by Google','$balance',0)";
mysql_query($sql) or die(mysql_error());
$customInsID = mysql_insert_id();



$uid=base64_decode(base64_decode(session_id()));
if (isset($_REQUEST["merchant_id"]) && is_numeric($_REQUEST["merchant_id"]))
        $merchant_id = $_REQUEST["merchant_id"];
else
        $merchant_id = "909858575103851";

if (isset($_REQUEST["merchant_key"]) && is_numeric($_REQUEST["merchant_key"]))
        $merchant_key = $_REQUEST["merchant_key"];
else
        $merchant_key = "hZTtCFv5bQTrpZf1DFGWvg";


//Payment

function toGBP($from)
{
$url = "http://www.exchangerate-api.com/$from/GBP/1?k=mhXK9-WknIS-he8My"; 	//nedd to change after 1500 request per month
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response= curl_exec($ch);
curl_close($ch);
return $response;
}


//$amount=10*toGBP("EUR");
if(isset($_REQUEST["Payment"]) && is_numeric($_REQUEST["Payment"]) && $_REQUEST["Payment"]>0)
        $amount=$_REQUEST["Payment"];
else
        $amount=10;

$amount=0.5;
if(isset($_REQUEST["currency_name"]) && strlen($_REQUEST["currency_name"])==3)
{
        //$amount=$amount*toGBP($_REQUEST["currency_name"]);
}
        
        //$amount=$amount*toGBP("USD");
if($amount>0)
        GoogleCheckout($merchant_id, $merchant_key,"Phone91 Item Lavel 1", " Basic First Amount",$amount,$uid,$customInsID);
else {
        echo "Invalid Amount";
}


function GoogleCheckout($merchant_id, $merchant_key,$produceDetails,$productDescription,$amount,$uid,$customInsID) {
        // Create a new shopping cart object

        $server_type = "checkout";
        $currency = "GBP";
        $cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);

        // Add items to the cart
        $item = new GoogleItem($produceDetails,$productDescription, 1, $amount);

        $cart->AddItem($item);

//        // Add <merchant-private-data>
        $cart->SetMerchantPrivateData(
                new MerchantPrivateData(array("uid" => array("sid" => "$uid"),"insID"=>array("id"=>$customInsID))));
//

//         Display XML data
//         echo "<pre>";
//         echo htmlentities($cart->GetXML());
//         echo "</pre>";
       //  Display a medium size button
        //echo $cart->GetXML();
         
          echo $cart->CheckoutButtonCode("SMALL");
}
?>
<script>
document.forms[0].submit();
</script>