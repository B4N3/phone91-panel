<?php

/*
 * Created on May 9, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

// Point to the correct directory
chdir("..");
// Include all the required files
require_once('library/googlecart.php');
require_once('library/googleitem.php');
//require_once('library/googleshipping.php');
//require_once('library/googletax.php');
error_reporting(-1);

if (isset($_REQUEST["merchant_id"]) && is_numeric($_REQUEST["merchant_id"]))
        $merchant_id = $_REQUEST["merchant_id"];
else
        $merchant_id = "909858575103851";

if (isset($_REQUEST["merchant_key"]) && is_numeric($_REQUEST["merchant_key"]))
        $merchant_key = $_REQUEST["merchant_key"];
else
        $merchant_key = "hZTtCFv5bQTrpZf1DFGWvg";


function toGBP($from)
{
$url = "http://www.exchangerate-api.com/$from/GBP/1?k=mhXK9-WknIS-he8My"; 	//nedd to change after 1500 request per month
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response= curl_exec($ch);
curl_close($ch);
return $response;
}


$amount=10*toGBP("EUR");

GoogleCheckout($merchant_id, $merchant_key,"Phone91 Item Lavel 1", " Basic First Amount",$amount);

function GoogleCheckout($merchant_id, $merchant_key,$produceDetails,$productDescription,$amount) {
        // Create a new shopping cart object

        $server_type = "checkout";
        $currency = "GBP";
        $cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);

        // Add items to the cart
        $item = new GoogleItem($produceDetails,$productDescription, 1, $amount);

        $cart->AddItem($item);

//        // Add <merchant-private-data>
//        $cart->SetMerchantPrivateData(
//                new MerchantPrivateData(array("animals" => array("type" => "cat,dog"))));
//

//         Display XML data
//         echo "<pre>";
//         echo htmlentities($cart->GetXML());
//         echo "</pre>";
       //  Display a medium size button
        //echo $cart->GetXML();
         
          echo $cart->CheckoutButtonCode("LARGE");
          
       
  // if i reach this point, something was wrong
//  echo "An error had ocurred: <br />HTTP Status: " . $status. ":";
//  echo "<br />Error message:<br />";
//  echo $error;

  
        
}
?>