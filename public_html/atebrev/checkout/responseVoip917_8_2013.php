<?php

/**
 * Copyright (C) 2007 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

 /* This is the response handler code that will be invoked every time
  * a notification or request is sent by the Google Server
  *
  * To allow this code to receive responses, the url for this file
  * must be set on the seller page under Settings->Integration as the
  * "API Callback URL'
  * Order processing commands can be sent automatically by placing these
  * commands appropriately
  *
  * To use this code for merchant-calculated feedback, this url must be
  * set also as the merchant-calculations-url when the cart is posted
  * Depending on your calculations for shipping, taxes, coupons and gift
  * certificates update parts of the code as required
  *
  */

  //chdir("..");
//include required files
include_once('../config.php');
include('../dbcon.php');
require_once('library/googleresponse.php');
require_once('library/googlemerchantcalculations.php');
require_once('library/googleresult.php');
require_once('library/googlerequest.php');

define('RESPONSE_HANDLER_ERROR_LOG_FILE', 'googleerror.log');
define('RESPONSE_HANDLER_LOG_FILE', 'googlemessage.log');

//$merchant_id = "909858575103851";  // Your Merchant ID
//$merchant_key = "hZTtCFv5bQTrpZf1DFGWvg";  // Your Merchant Key

$merchant_id = "267751181629662";  // Your Merchant ID
$merchant_key = "mdOWhTqrgPZ_3neyU1YU8g";  // Your Merchant Key

//$server_type = "checkout";  // change this to go live
$server_type = "sandbox"; 
$currency = 'USD';  // set to GBP if in the UK
$certificate_path = ""; // set your SSL CA cert path

$Gresponse = new GoogleResponse($merchant_id, $merchant_key);

$Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);
  //$GRequest->SetCertificatePath($certificate_path);
 
  //Setup the log file
  $Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE, RESPONSE_HANDLER_LOG_FILE, L_ALL);

// Retrieve the XML sent in the HTTP POST request to the ResponseHandler
$xml_response = isset($HTTP_RAW_POST_DATA)?
                  $HTTP_RAW_POST_DATA:file_get_contents("php://input");

if (get_magic_quotes_gpc()) 
{
    $xml_response = stripslashes($xml_response);
}
   
  list($root, $data) = $Gresponse->GetParsedXML($xml_response);
  $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);

  //mail("rahul@hostnsoft.com"," Checkout ", " Response  ".print_r($root,1)." Data ".print_r($data,1)." Session ".print_r($_SESSION,1)." Request ".print_r($_REQUEST,1));
  //mail("ankkubosstest@gmail.com"," Checkout ", " Response  ".print_r($root,1)." Data ".print_r($data,1)." Session ".print_r($_SESSION,1)." Request ".print_r($_REQUEST,1));
  
  //error handling
  try
  {
      //include file
    include('../sendmail.php');
    $mailerr = new MailAndErrorHandler();//create object
    $mailTo = array('ankkubosstest@gmail.com');
    $subject = "Checkout";
    $message = print_r($root,1)." Data ".print_r($data,1)." Session ".print_r($_SESSION,1)." Request ".print_r($_REQUEST,1);
    if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message))
    {
        $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
    }
  }
  catch(Exception $e)
  {
      $mailerr->errorHandler('problem in mail:'.print_r((array)$e).'\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
  }
  
  
  /*$status = $Gresponse->HttpAuthentication();
  if(! $status) {
    die('authentication failed');
  }*/

  /* Commands to send the various order processing APIs
   * Send charge order : $Grequest->SendChargeOrder($data[$root]
   *    ['google-order-number']['VALUE'], <amount>);
   * Send process order : $Grequest->SendProcessOrder($data[$root]
   *    ['google-order-number']['VALUE']);
   * Send deliver order: $Grequest->SendDeliverOrder($data[$root]
   *    ['google-order-number']['VALUE'], <carrier>, <tracking-number>,
   *    <send_mail>);
   * Send archive order: $Grequest->SendArchiveOrder($data[$root]
   *    ['google-order-number']['VALUE']);
   *
   */
  
   /* In case the XML API contains multiple open tags
     with the same value, then invoke this function and
     perform a foreach on the resultant array.
     This takes care of cases when there is only one unique tag
     or multiple tags.
     Examples of this are "anonymous-address", "merchant-code-string"
     from the merchant-calculations-callback API
  */
  function get_arr_result($child_node) 
  {
    $result = array();
    if(isset($child_node)) 
    {
      if(is_associative_array($child_node)) 
      {
        $result[] = $child_node;
      }
      else 
      {
        foreach($child_node as $curr_node)
        {
          $result[] = $curr_node;
        }
      }
    }//end of if for child node
    return $result;
  }//end of function get_arr_result()
    
  //if xml root match any case
  switch ($root) 
  {
      case "authorization-amount-notification": {
             
          //check for protection
         // if($data[$root]["order-summary"]["risk-information"]["eligible-for-protection"]["VALUE"])
          //{
              //get order value
              $orderTotal = floatval($data[$root]["authorization-amount"]["VALUE"]);
              //order amount validation
              if(!isset($orderTotal))
                  die("NOT valid amount");
              //get payment id
             $orderId = base64_decode($data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["insID"]["VALUE"]);
             $ip = base64_decode($data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["uid"]["VALUE"]);
             
             $convertCurrency = base64_decode($data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["convertCurrency"]["VALUE"]);
             $userCurrency = base64_decode($data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["userCurrency"]["VALUE"]);
             $talktime = base64_decode($data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["talktime"]["VALUE"]);  
            //code to update our databases
            if(!isset($orderId))
            {
               
                //error handling
             
                $mailTo = array('ankkubosstest@gmail.com');
                $subject = "google checkout";
                $message = 'problem with orderId id in response.php';
                if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message))
                {
                    $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
                }

                die("problem in order processing");
            }
             //query with payment id
             $sql = " SELECT * FROM confirmOrder WHERE order_id ='.$orderId.'";

             $db = $funobj->connecti();
             $idResult = mysqli_query($db,$sql) or $error=mysqli_error();

             //if error occur 
             if($error!="")
             {
                   //mail("ankkubosstest@gmail.com","select payment phone91",$sql."::".$error);
                  $mailTo = array('ankkubosstest@gmail.com');
                  $subject = "select payment phone91";
                  $message = $sql."::".$error;
                  if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message))
                  {
                      $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
                  }
                
                 die("problem in order processing");
             }
             //get id of client
              $row = mysqli_fetch_row($idResult);
              
              $id_client = $row['client_id'];
              $dbTalktime = $row['talktime'];
              $dbRechare = $row['recharge'];
              $dbIp = $row['ip'];
              
              //get details from 91_userbalance
             $sql="SELECT balance,currencyId FROM 91_userBalance WHERE userId='".$id_client."'";
             $result=mysqli_query($db,$sql) or $error=mysqli_error();
             if($error!="")
             {
                 //mail("ankkubosstest@gmail.com","select client detail phone91",$sql."::".$error);
                //errro handling
                
                $mailTo = array('ankkubosstest@gmail.com');
                $subject = "select client detail phone91";
                $message = $sql."::".$error;
                if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message))
                {
                    $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
                }
                 
             }
                 $get_userinfo=  mysqli_fetch_row($result);
             //get balance
             $balance=$get_userinfo['balance'];
             $cid=$get_userinfo['currencyId'];
             
             if($dbIp != $ip )
             {
                 //mail('shubh124421@gmail.com','have some problem','problem and problem');
                 //mail('ankkubosstest@gmail.com','have some problem in google checkout in voip91beta','ip not match');
                 //errro handling
                
                $mailTo = array('ankkubosstest@gmail.com');
                $subject = "have some problem in google checkout in voip91beta";
                $message = 'ip not match';
                if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message))
                {
                    $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
                }
                 
                die('have some problem in google checkout in voip91beta,ip not match');

             }
              

            $tariifid = intval($get_userinfo["tarrifId"]);

            if(isset($convertCurrency) and $convertCurrency != 'none')//convert amount to user currency
            {
                $actUserCharge = $funobj->currencyConvert($convertCurrency,$userCurrency,$orderTotal);
            }
              //set talktime according to tariffid and order amount
//             if($tariifid == 8)
//              {
//                  //convert GBP to USD
//                  $amount = floor(1.5151*$orderTotal);
//                  if($amount > 0 && $amount < 20)
//                      $talktime = (int) ($amount*0.9);
//
//                  if($amount >= 20 && $amount < 50)
//                      $talktime = (int) $amount;
//
//                  if($amount >= 50)
//                      $talktime = (int) ($amount*1.1);
//
//              }
//              else if($tariifid == 7)
//              {
//                  //convert GBP to INR
//                  $amount = ceil(82.7878*$orderTotal);
//
//                  if($amount > 0 && $amount < 500)
//                      $talktime = (int) ($amount*0.88);
//
//                  if($amount >= 500 && $amount < 1000)
//                      $talktime = (int)$amount;
//
//                  if($amount >= 1000)
//                      $talktime = (int) ($amount*1.1);
//              }
//              elseif($tariifid == 9)
//              {
//                  //convert GBP to AED
//                  $amount = floor(5.55*$orderTotal);
//
//                  if($amount > 0 && $amount < 100)
//                      $talktime = (int) ($amount*0.96);
//
//                  if($amount >= 100 && $amount < 200)
//                      $talktime = (int)$amount;
//
//                  if($amount >= 200)
//                      $talktime = (int) ($amount*1.1);
//
//              }

              $txn_id = $data[$root]["google-order-number"]["VALUE"];
              if($talktime == $dbTalktime and $dbIp == $ip and $dbRechare == $actUserCharge)//validate with ip and talktime and recharge amount
              {
                    //insert details in transactoin tables
                    $sqlP = "insert into transctions(userId,date,amount,description,transactionId,orderId,balance,paymentType) values('$id_client',now(),$actUserCharge,'Automatic recharge by google checkout','$txn_id','$orderId',$totalBal,'google checkout')" ;
                    mysqli_query($db,$sqlP) or $error=mysqli_error();
                    //update balance
                    $status= $funobj->updatebalance($db,$id_client,$talktime,'Add');
                    if($status != 'success')
                    {
                        
                        //errro handling
                        $mailTo = array('ankkubosstest@gmail.com');
                        $subject = "error occur while balance update";
                        $message = $status;
                        if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message))
                        {
                            $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
                        }
                        
                    }
                    else
                    {
                        //update order to done
                        $updateQ = "UPDATE confirmOrder SET status='done' WHERE order_id ='".$orderId."'";
                        mysqli_query($db,$updateQ) or die('problem in order confirmation:'. mysqli_error().'::'.$updateQ);
                    }
              }  
        else 
            $error = 'problem in talktime calculation or ip authentication!!!';
        if($error!="")
        {
             //mail("ankkubosstest@gmail.com","update payment detail phone91",$sql."::".$error);
            //errro handling
            $mailTo = array('ankkubosstest@gmail.com');
            $subject = "update payment detail phone91";
            $message = $sql."::".$error;
            if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message))
            {
                $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
            }
                
        }

             //get currency
              $currency = $funobj->get_currency($tariifid);
              //get buyer email
              $buyerEmail = $data[$root]["order-summary"]["risk-information"]['billing-address']['email']['VALUE'];
              //get user name
              $uname = base64_decode($data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["uname"]["VALUE"]);  
              //set msg to mail
              $mail_msg="One phone91 user with id ".$uname." and Googlecheckout emailid :".$buyerEmail." did google checkout payment of ".$actUserCharge." ".$currency." and got recharged by ".$talktime." ".$userCurrency;

              
              //mail("shubh124421@gmail.com","Phone91 Googlecheckout",$mail_msg);
              //error handling
              
              $mailTo = array('ankkubosstest@gmail.com');
              $subject = "Phone91 GoogleCheckout";
              $message = $mail_msg;
              if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message))
              {
                   $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
              }
                  
             
            //}
        $serialNumber = $data[$root]["serial-number"];
        $Gresponse->SendAck($serialNumber);
//          $Gresponse->SendOKStatus();
         
     break;
     }
    case "request-received": {
      break;
    }
    case "error": {
        //mail("ankkubosstest@gmail.com","error","error tag");

        $mailTo = array('ankkubosstest@gmail.com');
        $subject = "Phone91 GoogleCheckout error";
        $message = 'error tag';
        if(!$mailerr->sendmail_mandrill($mailTo, $subject, $message))
        {
            $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$subject.'\nmsg:'.$message);
        }
                  
      break;
    }
    case "diagnosis": {
      break;
    }
    case "checkout-redirect": {
        //get redirect url
        $redirectUrl = $data[$root]['checkout-redirect']['redirect-url']['VALUE'];
        $sub = "google checkout redirect url";
        $msg = "redirect url".$redirectUrl;
        //mail("ankkubosstest@gmail.com",$sub,$msg);
        $mailTo = array("ankkubosstest@gmail.com");
       
        if(!$mailerr->sendmail_mandrill($mailTo, $sub, $msg))
        {
            $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
            die('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
        }
       // mail("shubh124421@gmail.com",$sub,$msg);
      break;
    }
    case "merchant-calculation-callback": {

      break;
    }
    case "new-order-notification": {
   
        $Gresponse->SendAck($data["new-order-notification"]["serial-number"]);
        
      break;
    }
    case "order-state-change-notification": {
     // $Gresponse->SendAck();
      $new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
      $new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];

      switch($new_financial_state) {
        case 'REVIEWING': {
          break;
        }
        case 'CHARGEABLE': {
            $Gresponse->SendAck($data[$root]["serial-number"]);
          //$Grequest->SendProcessOrder($data[$root]['google-order-number']['VALUE']);
          //$Grequest->SendChargeOrder($data[$root]['google-order-number']['VALUE'],'');
          break;
        }
        case 'CHARGING': {
            $Gresponse->SendAck($data[$root]["serial-number"]);
          break;
        }
        case 'CHARGED': {
            $Gresponse->SendAck($data[$root]["serial-number"]);
          break;
        }
        case 'PAYMENT_DECLINED': {
            // The order was canceled before being completed.

            header("location:index.php");

            echo "<html><head><title>Payment declined</title></head><body><h3>The payment was decliend for previous order.</h3>";

            echo "</body></html>";
          break;
        }
        case 'CANCELLED': {
          break;
        }
        case 'CANCELLED_BY_GOOGLE': {
             //set subject and msg to mail
             $msg = "order cancelled by google";
             $sub= "google checkout error";
            // mail("ankkubosstest@gmail.com",$sub,$msg);
             //mail("shubh124421@gmail.com",$sub,$msg);
             $mailTo = array('ankkbosstest@gmail.com');
            if(!$mailerr->sendmail_mandrill($mailTo, $sub, $msg))
            {
                $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
                die('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
            }
             //redirect to index
             header("location:index.php");

             echo "<html><head><title>Payment declined</title></head><body><h3>The Order Cancelled By Google.</h3>";
          //$Grequest->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],
          //    "Sorry, your order is cancelled by Google", true);
          break;
        }
        default:
          break;
      }

      switch($new_fulfillment_order) {
        case 'NEW': {
            $Gresponse->SendAck($data[$root]["serial-number"]);
          break;
        }
        case 'PROCESSING': {
            $Gresponse->SendAck($data[$root]["serial-number"]);
          break;
        }
        case 'DELIVERED': {
          break;
        }
        case 'WILL_NOT_DELIVER': {
            $msg = "order not deliverd";
            $sub= "google checkout error";
           // mail("ankkubosstest@gmail.com",$sub,$msg);
            //mail("shubh124421@gmail.com",$sub,$msg);
            $mailTo = array('ankkbosstest@gmail.com');
            if(!$mailerr->sendmail_mandrill($mailTo, $sub, $msg))
            {
                $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
                die('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
            }
          break;
        }
        default:
          break;
      }
      break;
    }
    case "charge-amount-notification": {
        $googleOrderNumber = $data[$root]['google-order-number']['VALUE'];
        $latestChargeAmount = $data[$root]['latest-charge-amount']['VALUE'];
        $totalAmount = $data[$root]['total-charge-amount']['VALUE'];
        $sub = "google checkout:charge amount notification";
        $msg = "Google order number:".$googleOrderNumber."\n latest charge amount:".$latestChargeAmount."\n total charge amount:".$totalAmount;
        
        $response = $Grequest->SendChargeOrder($googleOrderNumber,$latestChargeAmount);
        //mail("ankkubosstest@gmail.com",$sub,$msg.print_r($response,1));
        $mailTo = array('ankkbosstest@gmail.com');
        if(!$mailerr->sendmail_mandrill($mailTo, $sub, $msg.print_r($response,1)))
        {
            $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
            die('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
        }
        //mail("shubh124421@gmail.com",$sub,$msg);
//$Grequest->SendDeliverOrder($data[$root]['google-order-number']['VALUE'],
      //    <carrier>, <tracking-number>, <send-email>);
      //$Grequest->SendArchiveOrder($data[$root]['google-order-number']['VALUE'] );
      $Gresponse->SendAck();
      break;
    }
    case "chargeback-amount-notification": {
      $Gresponse->SendAck();
      break;
    }
    case "refund-amount-notification": {
      $Gresponse->SendAck();
      break;
    }
    case "risk-information-notification": {
        //get user detail and risk information
        $eligibleForProtection = $data[$root]["risk-information"]["eligible-for-protection"]["VALUE"];
        $userEmail = $data[$root]["risk-information"]['billing-address']["email"]["VALUE"];
        $contactName = $data[$root]["risk-information"]['billing-address']["contact-name"]["VALUE"];
        $companyName = $data[$root]["risk-information"]['billing-address']["company-name"]["VALUE"];
        $phone = $data[$root]["risk-information"]['billing-address']["phone"]["VALUE"];
        $ip = $data[$root]["risk-information"]["ip-address"]["VALUE"];
        $googleOrderNumber = $data[$root]["google-order-number"]["VALUE"];
        $sub = "Google checkout risk information";
        $msg = "Eligible for protection:".$eligibleForProtection."\n user mail:".$userEmail."\n contact name:".$contactName."\n company name:".$companyName."\n contact number".$phone."\n ip:".$ip."\n Google order number:".$googleOrderNumber;
        //mail("ankkubosstest@gmail.com",$sub,$msg);
        $mailTo = array('ankkbosstest@gmail.com');
        if(!$mailerr->sendmail_mandrill($mailTo, $sub, $msg))
        {
            $mailerr->errorHandler('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
            die('problem in mail:\nmailto'.print_r($mailTo,1).'\nsub:'.$sub.'\nmsg:'.$msg);
        }
        $Gresponse->SendAck();
      break;
    }
    default:
      $Gresponse->SendBadRequestStatus("Invalid or not supported Message");
      break;
  }
 

  /* Returns true if a given variable represents an associative array */
  function is_associative_array( $var ) 
  {
        return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
  }
?>
