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
//include_once("sql_class.php");
//$objSqlcls = new sql_class();
  require_once('library/googleresponse.php');
  require_once('library/googlemerchantcalculations.php');
  require_once('library/googleresult.php');
  require_once('library/googlerequest.php');

  //define constant for error log and message log
  define('RESPONSE_HANDLER_ERROR_LOG_FILE', 'googleerror.log');
  define('RESPONSE_HANDLER_LOG_FILE', 'googlemessage.log');
  
 //get marchant id and key
  $merchant_id = $_REQUEST["merchantId"];  // Your Merchant ID
  $merchant_key = $_REQUEST["merchantKey"];  // Your Merchant Key
  $currency = $_REQUEST["currency"];//get currency
  //$server_type = "checkout";  // change this to go live
   $server_type = "sandbox";
  if(!isset($currency))
    $currency = 'USD';  // set to GBP if in the UK
  
  $certificate_path = ""; // set your SSL CA cert path

 $Gresponse = new GoogleResponse($merchant_id, $merchant_key);
  
  $Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);
  //$GRequest->SetCertificatePath($certificate_path)
  //Setup the log file
  $Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE, RESPONSE_HANDLER_LOG_FILE);

  // Retrieve the XML sent in the HTTP POST request to the ResponseHandler
  $xml_response = isset($HTTP_RAW_POST_DATA)?
                    $HTTP_RAW_POST_DATA:file_get_contents("php://input");
 
  if (get_magic_quotes_gpc()) {
    $xml_response = stripslashes($xml_response);
  }
   
  list($root, $data) = $Gresponse->GetParsedXML($xml_response);
  $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);

  //include error files for mail and error handling
  include_once dirname(dirname(dirname(__FILE__))).'/sendmail.php';
  //create object to mail and error handling
  $mailerr = new MailAndErrorHandler();
  //mail("rahul@hostnsoft.com"," Checkout ", " Response  ".print_r($root,1)." Data ".print_r($data,1)." Session ".print_r($_SESSION,1)." Request ".print_r($_REQUEST,1));
  $mailTo = array("AnkitPatidar@hostnsoft.com");
  $sub = " Checkout ";
  $msg = " Response  ".print_r($root,1)." Data ".print_r($data,1)." Session ".print_r($_SESSION,1)." Request ".print_r($_REQUEST,1);
  $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
  //mail("ankkubosstest@gmail.com"," Checkout ", " Response  ".print_r($root,1)." Data ".print_r($data,1)." Session ".print_r($_SESSION,1)." Request ".print_r($_REQUEST,1));
  
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
    
  //function to convert currency
  function currencyConvert($from,$to)
    {
        $url = "http://www.exchangerate-api.com/$from/$to/1?k=mhXK9-WknIS-he8My"; 	//nedd to change after 1500 request per month
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response= curl_exec($ch);
        curl_close($ch);
        return $response;
    }
  //if xml root match any case
  switch ($root) 
  {
      case "authorization-amount-notification": {
         
            
          //check for protection
          //if($data[$root]["order-summary"]["risk-information"]["eligible-for-protection"]["VALUE"])
          {
              //get order value
              $orderTotal = floatval($data[$root]["authorization-amount"]["VALUE"]);
              
              //convert to inr
              $amountRupee = $orderTotal*currencyConvert($currency,"INR");
             
              //order amount validation
              if(!isset($amountRupee))
                  die("NOT valid amount");
              //get required fields payment id ,reseller id,company id
             $payId = $data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["insID"]["VALUE"];
             $resellerId = $data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["resellerId"]["VALUE"];
             $compId = $data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["custom"]["VALUE"];
             //get credits
             $credits = $data[$root]["order-summary"]["shopping-cart"]["merchant-private-data"]["credits"]["VALUE"];
             //check fields 
             if(!isset($payId) || !isset($resellerId) || !isset($compId))
             {
                 // code to mail and maintain error log
                $sub = "google checkout in lead";
                $msg = "problem with payement id,reseller id or company id in responseLead.php";
                $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
                $mailerr->errorHandler('error sub:'.$sub.PHP_EOL.'msg:'.$msg);
                //mail("ankkubosstest@gmail.com","google checkout in lead","problem with payement id in response.php");
                die("problem in order processing");
             }
             
             # parameters to update in db.
            $table = 'company_table'; #table Name.
            $parm = 'balance'; # where clause.
            $query = 'cmp_id = '.$resellerId; # condition
            $selQ = "SELECT $parm FROM $table WHERE $query";
            //mail("ankkubosstest@gmail.com","comptable info select q get resseller bal",$selQ.'\ncurr'.$currency);
            # function to select fields.
            //$result =  $objSqlcls->select($table,$parm,$query);

               # getting resellers balance.
//            while($rows = mysql_fetch_array($result))
//            {
//                $balance = $rows['balance']; // balance.
//            }
            
            $googleOrderNumber = $data[$root]["order-summary"]["google-order-number"]["VALUE"];
            # if reseller balance is grater then amount.
            $balance = 719;
            if($balance > $credits)
            {
                # if company id and reseller id and company id should not blank.
                if(!empty($resellerId) && !empty($compId))
                {
                     # update reseller Balance. subtact balance from reseller account.
                     
                     $parmU = "balance = balance - ".$credits;
                     $queryU = "cmp_id = ".$resellerId;
                     $updateQ = "update $table SET $parmU WHERE $queryU";
                     # update in reseller account.
                     // $objSqlcls->update($table,$parmU,$queryU);
                     //mail("ankkubosstest@gmail.com","comptable info select update reseller",$updateQ);
                     #add balance to company.
                     $parmA = "balance = balance + ".$credits;
                     $queryA = "cmp_id = ".$compId;
                     $updateA = "UPDATE $table SET $parmA WHERE $queryA";
                     //mail("ankkubosstest@gmail.com","comptable info select update buyer",$updateA);
                     # update in company account.
                     // $objSqlcls-> update($table,$parmA,$queryA);
                     $buyerEmail = $data[$root]["order-summary"]["risk-information"]['billing-address']['email']['VALUE'];
                     $tableP = "payments";
                     $parmP = "response ='success',transaction_id='".$googleOrderNumber."'";
                     $queryP = "id = ".$payId;
                     $updateB = "UPDATE $tableP SET $parmP WHERE $queryP";
                     
                     // code to mail and maintain error log
                     $sub = "comptable info select update payment";
                     $msg = $updateB.'\ncomptable info select q get resseller bal:'.$selQ.'\ncomptable info select update reseller:'.$updateQ.'\ncomptable info select update buyer:'.$updateA;
                     $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
                     
                     //mail("ankkubosstest@gmail.com","comptable info select update payment",$updateB.'\ncomptable info select q get resseller bal:'.$selQ.'\ncomptable info select update reseller:'.$updateQ.'\ncomptable info select update buyer:'.$updateA);
                     # update to payments table.
                    // $objSqlcls-> update($tableP,$parmP,$queryP);

                }
                else  # if reseller id is empty.
                {
                    // code to mail and maintain error log
                   
                     $sub = "Payment Error";
                     $msg = "Invalid Reseller Id.";
                     $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
                     $mailerr->errorHandler('error sub:'.$sub.PHP_EOL.'msg:'.$msg);
                     //mail("ankkubosstest@gmail.com","Payment Error","Invalid Reseller Id.");
                     die("ERROR:Invalid resseller");
                }
            }
            else 
            {
                // code to mail and maintain error log
                $sub = "payment";
                $msg = "insufficient Balance of reseller.";
                $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
                $mailerr->errorHandler('error sub:'.$sub.PHP_EOL.'msg:'.$msg);
                //mail("ankkubosstest@gmail.com","payment","insufficient Balance of reseller.");
                die("ERROR:Invalid balance or resseler!!");
            }
             
            //set msg to mail
            $mail_msg="One LEAD user  Googlecheckout emailid :".$buyerEmail." did google checkout payment of $amountRupee and get $credits credits with google order number $googleOrderNumber";

            // code to mail and maintain error log
            $sub = "LEAD GoogleCheckout";
            
            $mailerr->sendmail_mandrill($mailTo,$sub,$mail_msg,'');
            //mail("ankkubosstest@gmail.com","LEAD GoogleCheckout",$mail_msg);


        }
        $serialNumber = $data[$root]["serial-number"];
        $Gresponse->SendAck($serialNumber);
//          $Gresponse->SendOKStatus();
         
     break;
     }
    case "request-received": {
      break;
    }
    case "error": {
        $sub = "error";
        $msg = "error tag";
        $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
        $mailerr->errorHandler('error sub:'.$sub.PHP_EOL.'msg:'.$msg);
        //mail("ankkubosstest@gmail.com","error","error tag");
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
        $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
        // mail("ankkubosstest@gmail.com",$sub,$msg);
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
        $Gresponse->SendAck($data[$root]["serial-number"]);
       
      $new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
      $new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];

      switch($new_financial_state) {
        case 'REVIEWING': {
          break;
        }
        case 'CHARGEABLE': {
          //$Grequest->SendProcessOrder($data[$root]['google-order-number']['VALUE']);
          //$Grequest->SendChargeOrder($data[$root]['google-order-number']['VALUE'],'');
          break;
        }
        case 'CHARGING': {
          break;
        }
        case 'CHARGED': {
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
             $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
             //mail("ankkubosstest@gmail.com",$sub,$msg);
             //mail("shubh124421@gmail.com",$sub,$msg);
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
          break;
        }
        case 'PROCESSING': {
          break;
        }
        case 'DELIVERED': {
          break;
        }
        case 'WILL_NOT_DELIVER': {
            $msg = "order not deliverd";
            $sub= "google checkout error";
            $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
            //mail("ankkubosstest@gmail.com",$sub,$msg);
            //mail("shubh124421@gmail.com",$sub,$msg);
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
        $mailerr->sendmail_mandrill($mailTo,$sub,$msg.print_r($response,1),'');
        //mail("ankkubosstest@gmail.com",$sub,$msg.print_r($response,1));
        //mail("shubh124421@gmail.com",$sub,$msg);
//$Grequest->SendDeliverOrder($data[$root]['google-order-number']['VALUE'],
      //    <carrier>, <tracking-number>, <send-email>);
      //$Grequest->SendArchiveOrder($data[$root]['google-order-number']['VALUE'] );
      $Gresponse->SendAck($data[$root]["serial-number"]);
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
        $mailerr->sendmail_mandrill($mailTo,$sub,$msg,'');
        //mail("ankkubosstest@gmail.com",$sub,$msg);
      $Gresponse->SendAck($data[$root]["serial-number"]);
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
