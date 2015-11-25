<?php


//include required files
include_once ("classes/transaction_class_ankit.php");
require_once('includes/configExpressCheckout.php');
require_once('includes/paypal.classExpressCheckout.php');
include_once (dirname(__FILE__)."/paymentConfig_ankit.php");

$PayPalConfig = array(
					'Sandbox' => $sandbox,
					'APIUsername' => $api_username,
					'APIPassword' => $api_password,
					'APISignature' => $api_signature
					);
//create fun class object
$funObj = new fun();

//get tracker time
$startTimTracker = date(DATEFORMAT); 

$http="http";
if($_SERVER['HTTP_HOST']=="voip91.com")
    $http="https";

//set tracker msg
$trackMsg = 'user came to payment page ECP';
//details for tracker
$trackDtl = $PayPalConfig;

//call tracker
$trackId = $funObj->paymentTracker(null, $startTimTracker,'',$trackMsg,$trackDtl);

//get tracker time
$startTimTracker = date(DATEFORMAT); 

$funObj->sendErrorMail('ankitpatidar@hostnsoft.com','api details'.print_r($PayPalConfig,1));

$PayPal = new PayPal($PayPalConfig);

//apply exception handling
try
{
    //include file for perform mail
    //include_once (dirname(__FILE__).'/sendmail.php');
    $mailErr = new MailAndErrorHandler();//create object
  
}
catch(Exception $e)
{
    trigger_error('problem in mail object creation:'.print_r((array)$e,1));
    
    //free object space ]
    $mailErr = null;
    unset($mailErr);
    
    //stop script execution
    die('problem while object creation');
}

//get or set action
if(isset($_REQUEST) and isset($_REQUEST['action']))
    $action = $_REQUEST['action'];
else
    $action = 'process';
//switch case for different cases
switch ($action)
{
    case 'process':
    {
        $GECDResult = $PayPal -> GetExpressCheckoutDetails($_SESSION['PayPalResult']['TOKEN']);




        $funObj->sendErrorMail('ankitpatidar@hostnsoft.com','order details'.print_r($GECDResult,1));

        trigger_error('express Checkout Response:'.print_r($GECDResult,1));
        $DECPFields = array(
                            'token' => $_SESSION['PayPalResult']['TOKEN'], 								// Required.  A timestamped token, the value of which was returned by a previous SetExpressCheckout call.
                            'payerid' => $GECDResult['PAYERID'], 							// Required.  Unique PayPal customer id of the payer.  Returned by GetExpressCheckoutDetails, or if you used SKIPDETAILS it's returned in the URL back to your RETURNURL.
                            'returnfmfdetails' => '1', 					// Flag to indiciate whether you want the results returned by Fraud Management Filters or not.  1 or 0.
                            'giftmessage' => '', 						// The gift message entered by the buyer on the PayPal Review page.  150 char max.
                            'giftreceiptenable' => '', 					// Pass true if a gift receipt was selected by the buyer on the PayPal Review page. Otherwise pass false.
                            'giftwrapname' => '', 						// The gift wrap name only if the gift option on the PayPal Review page was selected by the buyer.
                            'giftwrapamount' => '', 					// The amount only if the gift option on the PayPal Review page was selected by the buyer.
                            'buyermarketingemail' => '', 				// The buyer email address opted in by the buyer on the PayPal Review page.
                            'surveyquestion' => '', 					// The survey question on the PayPal Review page.  50 char max.
                            'surveychoiceselected' => '',  				// The survey response selected by the buyer on the PayPal Review page.  15 char max.
                            'allowedpaymentmethod' => '', 				// The payment method type. Specify the value InstantPaymentOnly.
                            'buttonsource' => '' 						// ID code for use by third-party apps to identify transactions in PayPal. 
                    );

        $Payments = array();
        $Payment = array(
                    'amt' => $GECDResult['AMT'], 							// Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
                    'currencycode' => $GECDResult['CURRENCYCODE'], 					// A three-character currency code.  Default is USD.
                    'itemamt' => $GECDResult['ITEMAMT'], 						// Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.  
                    'shippingamt' => $GECDResult['SHIPPINGAMT'], 					// Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
                    'insuranceoptionoffered' => '', 		// If true, the insurance drop-down on the PayPal review page displays the string 'Yes' and the insurance amount.  If true, the total shipping insurance for this order must be a positive number.
                    'handlingamt' => $GECDResult['HANDLINGAMT'], 					// Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
                    'taxamt' => $GECDResult['TAXAMT'], 						// Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order. 
                    'desc' => $GECDResult['DESC'], 							// Description of items on the order.  127 char max.
                    'custom' => $GECDResult['CUSTOM'], 						// Free-form field for your own use.  256 char max.
                    'invnum' => '', 						// Your own invoice or tracking number.  127 char max.
                    'notifyurl' => 'https://voip91.com/checkResponse.php?action=ipn',  						// URL for receiving Instant Payment Notifications
                    'shiptoname' => '', 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
                    'shiptostreet' => '', 					// Required if shipping is included.  First street address.  100 char max.
                    'shiptostreet2' => '', 					// Second street address.  100 char max.
                    'shiptocity' => '', 					// Required if shipping is included.  Name of city.  40 char max.
                    'shiptostate' => '', 					// Required if shipping is included.  Name of state or province.  40 char max.
                    'shiptozip' => '', 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
                    'shiptocountry' => '', 					// Required if shipping is included.  Country code of shipping address.  2 char max.
                    'shiptophonenum' => '',  				// Phone number for shipping address.  20 char max.
                    'notetext' => 'This is a test note before ever having left the web site.', 						// Note to the merchant.  255 char max.  
                    'allowedpaymentmethod' => '', 			// The payment method type.  Specify the value InstantPaymentOnly.
                    'paymentaction' => 'Sale', 					// How you want to obtain the payment.  When implementing parallel payments, this field is required and must be set to Order. 
                    'paymentrequestid' => '',  				// A unique identifier of the specific payment request, which is required for parallel payments. 
                    'sellerpaypalaccountid' => $GECDResult['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID']			// A unique identifier for the merchant.  For parallel payments, this field is required and must contain the Payer ID or the email address of the merchant.
                    );

        $PaymentOrderItems = array();
        $Item = array(
                'name' => $GECDResult['ORDERITEMS'][0]['L_NAME'], 							// Item name. 127 char max.
                'desc' => $GECDResult['ORDERITEMS'][0]['L_DESC'], 							// Item description. 127 char max.
                'amt' => $GECDResult['ORDERITEMS'][0]['L_AMT'], 								// Cost of item.
                'number' => $GECDResult['ORDERITEMS'][0]['L_NUMBER'], 							// Item number.  127 char max.
                'qty' => $GECDResult['ORDERITEMS'][0]['L_QTY'], 								// Item qty on order.  Any positive integer.
                'taxamt' => '', 							// Item sales tax
                'itemurl' => '', 							// URL for the item.
                'itemweightvalue' => '', 					// The weight value of the item.
                'itemweightunit' => '', 					// The weight unit of the item.
                'itemheightvalue' => '', 					// The height value of the item.
                'itemheightunit' => '', 					// The height unit of the item.
                'itemwidthvalue' => '', 					// The width value of the item.
                'itemwidthunit' => '', 					// The width unit of the item.
                'itemlengthvalue' => '', 					// The length value of the item.
                'itemlengthunit' => '',  					// The length unit of the item.
                'ebayitemnumber' => '', 					// Auction item number.  
                'ebayitemauctiontxnid' => '', 			// Auction transaction ID number.  
                'ebayitemorderid' => '',  				// Auction order ID number.
                'ebayitemcartid' => ''					// The unique identifier provided by eBay for this order from the buyer. These parameters must be ordered sequentially beginning with 0 (for example L_EBAYITEMCARTID0, L_EBAYITEMCARTID1). Character length: 255 single-byte characters
                );
         array_push($PaymentOrderItems, $Item);

                //$Item = array(
                //			'name' => 'Widget 456', 							// Item name. 127 char max.
                //			'desc' => 'Widget 456', 							// Item description. 127 char max.
                //			'amt' => '40.00', 								// Cost of item.
                //			'number' => '456', 							// Item number.  127 char max.
                //			'qty' => '1', 								// Item qty on order.  Any positive integer.
                //			'taxamt' => '', 							// Item sales tax
                //			'itemurl' => 'http://www.angelleye.com/products/456.php', 							// URL for the item.
                //			'itemweightvalue' => '', 					// The weight value of the item.
                //			'itemweightunit' => '', 					// The weight unit of the item.
                //			'itemheightvalue' => '', 					// The height value of the item.
                //			'itemheightunit' => '', 					// The height unit of the item.
                //			'itemwidthvalue' => '', 					// The width value of the item.
                //			'itemwidthunit' => '', 					// The width unit of the item.
                //			'itemlengthvalue' => '', 					// The length value of the item.
                //			'itemlengthunit' => '',  					// The length unit of the item.
                //			'ebayitemnumber' => '', 					// Auction item number.  
                //			'ebayitemauctiontxnid' => '', 			// Auction transaction ID number.  
                //			'ebayitemorderid' => '',  				// Auction order ID number.
                //			'ebayitemcartid' => ''					// The unique identifier provided by eBay for this order from the buyer. These parameters must be ordered sequentially beginning with 0 (for example L_EBAYITEMCARTID0, L_EBAYITEMCARTID1). Character length: 255 single-byte characters
                //			);
                //array_push($PaymentOrderItems, $Item);

            $Payment['order_items'] = $PaymentOrderItems;
            array_push($Payments, $Payment);				

            $UserSelectedOptions = array(
                                             'shippingcalculationmode' => '', 	// Describes how the options that were presented to the user were determined.  values are:  API - Callback   or   API - Flatrate.
                                             'insuranceoptionselected' => '', 	// The Yes/No option that you chose for insurance.
                                             'shippingoptionisdefault' => '', 	// Is true if the buyer chose the default shipping option.  
                                             'shippingoptionamount' => '', 		// The shipping amount that was chosen by the buyer.
                                             'shippingoptionname' => '', 		// Is true if the buyer chose the default shipping option...??  Maybe this is supposed to show the name..??
                                             );

            $PayPalRequest = array(
                           'DECPFields' => $DECPFields, 
                           'Payments' => $Payments
                           );

            

            $DECReponse = $PayPal ->DoExpressCheckoutPayment($PayPalRequest);

            parse_str($DECReponse['RAWRESPONSE'], $response);
            
             //tracker for user details
       $trackId = $funObj->paymentTracker($trackId, $startTimTracker,'','DEC RESPONSE',$response);
       
       //start time for tracker
       $startTimTracker = date(DATEFORMAT);
       
       //check response ack
       if(!isset($response))
       {
           $mailErr->sendmail_mandrill($errorMails, 'problem in DoExpressCheckoutPayment parameters','params:'.print_r($PayPalRequest,1),BUSINESSMAIL);
           
        //apply tracker
        $trackId = $funObj->paymentTracker($trackId, $startTimTracker,'','problem in DoExpressCheckoutPayment parameters',$PayPalRequest);
           
           die('problem in DoExpressCheckoutPayment parameters');
       }
       elseif( $response['ACK'] != 'Success') //check for success
       {
           $mailErr->sendmail_mandrill($errorMails, 'problem in DoExpressCheckoutPayment response','Response:'.print_r($response,1),BUSINESSMAIL);
           
           //apply tracker
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,'','problem in DoExpressCheckoutPayment response',$response);
           die('problem in DoExpressCheckoutPayment response');
       }
       
       //get array for required fields
       $reqPara = json_decode(base64_decode($GECDResult['CUSTOM']),TRUE);
        
       //get orderId
       $orderId = trim(base64_decode($reqPara['orderId']));
        
       //get charge amount
       $actUserCharge = base64_decode($reqPara['charge']);
        
       //get usercurrency
       $userCurrency= base64_decode($reqPara['userCurrency']); 
        
       //get convert currency
       $convertCurrency = base64_decode($reqPara['convertCurrency']);
        
       //get recharge value
       $recharge = $response['PAYMENTINFO_0_AMT'];
       
       $payerEmail = $GECDResult['EMAIL'];
       
        //check the order id
       if(!isset($orderId))
       {
             //call tracker for user payment details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker, '','Invalid order id in Express Checkout',$GECDResult);
            die('You are using invalide order number!!!');
       }
            
       //apply select query to get order details by orderid
       $rsOrder = $funObj->selectData('*', '91_confirmOrder',"orderId='$orderId'");
        
       //validate result
       if(!$rsOrder)
       {
           //set tracker msg
           $trackMsg = 'problem while get order detail in Express Checkout';
           //details for tracker
           $trackDtl = array('charge' =>$actUserCharge,
                             'usercurrency' => $userCurrency,
                             'convertCurrency' => $convertCurrency,
                             'gross amount' => $recharge,
                             'orderID' => $orderId);

           //call tracker for user payment details
           $trackId = $funObj->paymentTracker($trackId, $startTimTracker, '',$trackMsg,$trackDtl);

           //free obj space
           $funObj = null;
           unset($funObj);
           //stop execution
           die('problem while get order detail'); 
       } //end of result validate
       
        //get detail array
       $row = mysqli_fetch_assoc($rsOrder);
        
       //get order details from array
       $idClient = $row['clientId'];
       $dbTalktime =$row['talktime'];
       $dbRechare = $row['recharge'];
       $dbIp = $row['ip'];
       
        //convert to respective currency
        if(isset($convertCurrency) and $convertCurrency != 'none') 
        { 
            //get converted amount
           $actUserCharge = $funObj->currencyConvert($userCurrency,$convertCurrency,$dbRechare);

           //get amount with 2 decimal points
           $actUserCharge = $funObj->getNumberWithTwoDecimal($actUserCharge);
        } 
        else//assign usr currency to convert currency
            $convertCurrency = $userCurrency;

        
        //get currency id
        $currencyId = $funObj->getCurrency($userCurrency);
        
        //get user detail
        $result = $funObj->selectData('balance,currencyId,resellerId', '91_userBalance',"userId=$idClient");
        
        //if result not found then set tracker
        if(!$result)
        {
            //set tracker msg
            $trackMsg = "problem while get user balance in Express Checkout";
          
            //call tracker for user payment details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
            //free obj space
            $funObj = null;
            unset($funObj);
            
            die($trackMsg);//stop the srcipt execution
             
        }//end of result validation
         
        //get detail array
        $getUserInfo=mysqli_fetch_array($result);
       
        //get details from array balance,currency id,reseller id
        $balance=$getUserInfo['balance'];
        $cid=$getUserInfo['currencyId'];
        $resellerId=$getUserInfo['resellerId'];
        
        //get user ip
        $ip = base64_decode($reqPara['ip']);//get ip
        
        //create transaction class obj
        $tranxObj = new transaction_class();
        
        $txnId = $response['PAYMENTINFO_0_TRANSACTIONID'];
        /**
         * @code to block paypal id and substract user balance
         */
        if($paymentStatus == 'Reversed') //Reversed
	{
            
            $subBal = $balance-$dbTalktime;
            
            //set tracker msg
            $trackMsg = 'user reached in reversed condition in Express Checkout';
            
            //call tracker
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
            $tranxObj->addTransactional_sub($resellerId,$idClient,$dbTalktime,$subBal,'paypal:Reversed',0,0,0,$txnId,$currencyId);//maintain transaction log    
            $tranxObj->updateUserBalance($idClient,$subBal);//update balance
              
            /**
             * @code to update payment status in confirmOrder table to one
             */
            //prepare update array
            $updateDataR = array('paymentId' => $payerEmail,
                                'status' => 'Reversed',
                                'rechargeTime' => date(DATEFORMAT));
            
            //code to update confirmorder table
            $funObj->updateData($updateDataR,'91_confirmOrder',"orderId ='".$orderId."'");
            
            
            //set tracker msg
            $trackMsg = 'user balance Reversed by Express Checkout!!!';
            
            //tracker for user details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
            //start time for tracker
            $startTimTracker = date(DATEFORMAT);

            
            
            //insert order details  into confirmOrder table
            $data = array("paymentId" => $payerEmail,
                          'status' => 0,
                          'paymentBy' => 0);

            //get table name
            $tablePIS = '91_paymentIdStatus';

            //apply insert query
            $resultPIS = $funObj->insertData($data,$tablePIS);
            
            //set tracker msg
            $trackMsg = "payment reversed in paypal and paypal id blocked in Express Checkout";
           
            //call tracker for user payment details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
            $mailErr->sendmail_mandrill($successEmails, 'Phone91 payment reversed in paypal and paypal id blocked','paypalId:'.$payerEmail,BUSINESSMAIL);
            
            die('Phone91 payment reversed in paypal and paypal id blocked');
            exit();

	} //end of reversed if
        
        
        //calculate total balance
        $totalBal = $balance+$dbTalktime;
        
         //set tracker msg
        $trackMsg = 'user balance about to update in Express Checkout';
        //details for tracker
        $trackDtl = array('charge' =>$actUserCharge.'||'.gettype($actUserCharge),
                          'usercurrency' => $userCurrency,
                          'convertCurrency' => $convertCurrency,
                          'gross amount' => $recharge.'||'.gettype($actUserCharge),
                          'balance' => $balance,
                          'total balance after recharge' => $totalBal,
                          'userIp' => $ip,
                          'txnId' => $txnId,
                          'resellerId' => $resellerId,
                          'userId' => $idClient,
                          'dbRechare' => $dbRechare,
                          'dbTalktime' => $dbTalktime,
                          'dbIP' => $dbIp);

        //call tracker for user payment details
        $trackId = $funObj->paymentTracker($trackId, $startTimTracker, $idClient,$trackMsg,$trackDtl);
         
        //start time for tracker
        $startTimTracker = date(DATEFORMAT);
        
        //$txnId = $orderId;
        if($dbIp == $ip and $recharge == $actUserCharge)//validate with ip and talktime and recharge amount
        {
              //set tracker msg
            $trackMsg = 'user reached at before update condition in Express Checkout';
             //call tracker
            
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
           
            //get closing balance
            $closingBalance = $tranxObj->getClosingBalance($idClient);
            
            //maintain transaction log
            $tranxObj->addTransactional_sub($resellerId,$idClient,0,$balance,'paypal',0,$dbRechare,$closingBalance,'txnId:'.$txnId,$currencyId);
            
            $tranxObj->addTransactional_sub($resellerId,$idClient,$dbTalktime,$totalBal,'voip',$dbRechare,0,$closingBalance,'txnId:'.$txnId,$currencyId);
            //$tranxObj->addTransactional($resellerId,$idClient,$dbRechare,$dbTalktime,'paypal',$txnId,'prepaid');//maintain transaction log    
            $tranxObj->updateUserBalance($idClient,$totalBal);//update balance
            
            
                
            /**
             * @code to update payment status in confirmOrder table to one
             */
            //prepare update array
            $updateData = array('paymentId' => $payerEmail,
                                'status' => 'done',
                                'rechargeTime' => date('Y-m-d H:i:s'));
            
            //code to update confirmorder table
            $funObj->updateData($updateData,'91_confirmOrder',"orderId ='".$orderId."'");
            
            
            //set tracker msg
            $trackMsg = 'user balance updated in Express Checkout';
             //tracker for user details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
            //start time for tracker
            $startTimTracker = date(DATEFORMAT);

        }  
        else 
            $error = 'problem in talktime calculation or ip authentication in Express Checkout!!!'.'dtt'.$dbTalktime.'dbip:'.$dbIp.'ip:'.$ip.'dbr:'.$dbRechare.'act'.$actUserCharge.'cc'.$convertCurrency;
        
        //free the object space
        $tranxObj = null;
        unset($tranxObj);
        
        if($error!="")
        {
          
             //tracker for user details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,'user balance not updated in Express Checkout',$trackDtl);
            
            //send mail via mandrill
            $subject = "update payment detail phone91 in Express Checkout";
            $message = $sql."::".$error;
            if(!$mailErr->sendmail_mandrill($errorMails, $subject, $message,BUSINESSMAIL))
            {
                $mailErr->errorHandler('problem in mail:\nmailto'.print_r($errorMails,1).'\nsub:'.$subject.'\nmsg:'.$message);
            }
            
            //free obj space
            $funObj = null;
            unset($funObj);

            $mailErr = null;
            unset($mailErr);
            //stop the script execution
            die( "Error while payment updation in paypal in Express Checkout");
        }
        
        //get currency details
        $result = $funObj->selectData('name', 'currency_names',"id='$cid'");
        
         //if result not found then set tracker
        if(!$result)
        {
            
            $trackMsg = "problem while get currency name in Express Checkout";
            
            //details for tracker
            $trackDtl = array('userId' => $idClient,
                              'cid' => $cid);

            //call tracker for user payment details
            $trackId = $funObj->paymentTracker($trackId, $startTimTracker,$idClient,$trackMsg,$trackDtl);
            
             //free obj space
            $funObj = null;
            unset($funObj);
            
            //stop the script execution
            die($trackMsg);
             
        }//end of result validation
        
        //get array
        $getUserInfo=mysqli_fetch_array($result);
  
        //get currency
        $currency=$getUserInfo['name'];
        
        //get user name
        $userName = base64_decode($reqPara['uname']);
        
        //get payment currency
        if(!isset($mcCurrency))
            $mcCurrency = $convertCurrency;
        
        //set mail msg
	$mailMsg="One phone91 user with id ".$userName." and paypal emailid ".$payerEmail." did paypal payment of ".$recharge." ".$mcCurrency." and got recharged by ".$dbTalktime." ".$userCurrency.' tranx id:'.$txnId.' trackerId:'.$trackId;
        
	//send mail via mandrill
        $subject = "Phone91 Paypal payment";
        $message = $mailMsg;
        if(!$mailErr->sendmail_mandrill($successEmails, $subject, $message,BUSINESSMAIL))
        {
            trigger_error('problem in mail:\nmailto'.print_r($successEmails,1).'\nsub:'.$subject.'\nmsg:'.$message);
        }
        

    }
    case 'ipn':
    {
        $funObj->sendErrorMail('ankitpatidar@hostnsoft.com','api IPN'.print_r($_REQUEST,1));
    }
    
}






?>