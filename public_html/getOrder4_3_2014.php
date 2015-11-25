<?php
/**
 * @author Ankit Patidar <AnkitPatidar@hostnsoft.com> 
 * @description common file to request to any payment gateway
 */

//include required files
include('config.php');
include_once (dirname(__FILE__)."/paymentConfig.php");


//get start time for tracker
$startTimTracker = date(DATEFORMAT);

//get currencycode
$currencyCodeType=$_REQUEST['currency_name'];

//get rechare, talktime from request param 
$charge=$_REQUEST['recharge'];
$talktime = $_REQUEST['talktime'];

//get user id from session
$idClient = $_SESSION['userid'];
//get username
$userName = $_SESSION['username'];

//create object for function layer
$funObj = new fun();

//get table name
$UBaltableName = '91_userBalance';

//condition
$whereClauseId = "userId='$idClient'";

//apply query and execute and get result 
$result = $funObj->selectData('*',$UBaltableName,$whereClauseId);

//get balance
$getUserInfo=  mysqli_fetch_assoc($result);
$balance=$getUserInfo['balance'];

//get ip
$ip = $funObj->getUserIp();

//get order id
$orderId = $funObj->randomNumber(15);

//set tracker msg
$trackMsg = 'get user details';
//details for tracker
$trackDtl = array('userIp' =>$ip,
                  'userbalance' => $balance,
                  'recharge' => $charge,
                  'talktime' => $talktime,
                  'orderId' => $orderId,
                  'currencyCodeType' => $currencyCodeType);

//call tracker for user details
$trackId = $funObj->paymentTracker(null, $startTimTracker, $idClient,$trackMsg,$trackDtl);

 
//get start time for trackers
$startTimTracker = date(DATEFORMAT);


//get payment by
$paymentBy = $_REQUEST['paymentBy'];

//set action and parameters to post for all payment gateway
switch ($paymentBy)
{
    case 'paypal':
        //set parameters for paypal
        $action = 'paypal.php';
        $marchantId=PAYPALMERCHANTID;
        
        //set where claues
        $whereClause = "paymentGateway='paypal'";
        
        //set paymentBy
        $paymentByCO=0;
        break;
    case 'cashu':
        //set parameters fo cashu
        //$action = 'https://www.cashu.com/cgi-bin/pcashu.cgi';
        $action = CASHUURL;
        //$marchantId='phonee';
        $marchantId=CASHUMERCHANTID;
        $lang = 'en';
        
        //set where clause
        $whereClause = "paymentGateway='cashu'";
        
        //set paymentBy
        $paymentByCO=1;
        break;
    
    case 'googleCheckout'://incllude files
        require_once('checkout/library/googlecart.php');
        require_once('checkout/library/googleitem.php');
        //require_once('library/googleshipping.php');
        require_once('checkout/library/googleresult.php');
        
        //get or set marchant id
        if (isset($_REQUEST["merchant_id"]) && is_numeric($_REQUEST["merchant_id"]))
                $marchantId = $_REQUEST["merchant_id"];
        else
        {
                //$marchantId = "909858575103851";
                $marchantId='267751181629662';
        }
        
        //get or set marchant key
        if (isset($_REQUEST["merchant_key"]) && is_numeric($_REQUEST["merchant_key"]))
                $merchantKey = $_REQUEST["merchant_key"];
        else
        {
                //$merchantKey = "hZTtCFv5bQTrpZf1DFGWvg";
                $merchantKey= 'mdOWhTqrgPZ_3neyU1YU8g';   
        }
        
        //set the where clause
        $whereClause = "paymentGateway='googleCheckout'";
        $action='';
        
        //set paymentBy
        $paymentByCO=2;
        
        break;
    case 'creditDebit':
    {
        $marchantId='phonee';
        
        //set where claues
        $whereClause = "paymentGateway='paypal'";
        
        //set paymentBy
        $paymentByCO=0;
        /**
         * code for expresscheckout API in paypal
         */
        // Include required library files.
        //require_once('includes/configExpressCheckout.php');
        require_once('includes/paypal.classExpressCheckout.php');

        // Create PayPal object.
        $PayPalConfig = array(
                                                'Sandbox' => $sandbox,
                                                'APIUsername' => API_USERNAME,
                                                'APIPassword' => API_PASSWORD,
                                                'APISignature' => API_SIGNATURE
                                                );

        $PayPal = new PayPal($PayPalConfig);
        

        break;
    }
}//end of switch

//insert order details  into confirmOrder table
$data = array("orderId" => $orderId,
              "clientId" => $idClient,
              'recharge' => $charge ,
              'talktime' => $talktime ,
              'balance' => $balance,
              'rechargeTime' => date('Y-m-d H:i:s'),
              'status' => 'undone' ,
              'ip' => $ip,
              'paymentBy' => $paymentByCO);


//tracker for insert details
$trackId = $funObj->paymentTracker($trackId, $startTimTracker, $idClient,"confirm order insert details",$data);
//get table name
$table = '91_confirmOrder';

//apply insert query
$result = $funObj->insertData($data,$table);




//get table name
$pGtableName = 'PGCurrency';

//apply select query and get payment gateway currency

$currResult = $funObj->selectData('*',$pGtableName,$whereClause);

//get currency in array and set base currency
while($arrCurr = mysqli_fetch_row($currResult))
{
    
    $currArr[] = $arrCurr[2];
    if($arrCurr[3]==1)//set default currency
        $base = $arrCurr[2];
}

$convertCurrency='none';


//check currency in supported currency
if(in_array($currencyCodeType,$currArr))
{
   $AMT = $charge;//if found then do not apply currency conversion
   $currency = $currencyCodeType;
}
else
{
  
    //if not found then convert to base currency
    $AMT =  $funObj->currencyConvert($currencyCodeType,$base,$charge);
    
    $AMT = $funObj->getNumberWithTwoDecimal($AMT);
   
    //$AMT = number_format($AMT,2,'.','');
    $convertCurrency= $base;
    $currency = $base;
    
}



//call tracker for user transaction details
//set tracker msg
$trackMsg = 'user payment details';
//details for tracker
$trackDtl = array('currency for transaction' =>$currency,
                  'convcerted amount' => $AMT,
                  'paymentBy' => $paymentBy);

//call tracker for user details
$trackId = $funObj->paymentTracker($trackId, $startTimTracker, $idClient,$trackMsg,$trackDtl);




/**
 * @Last updated by <ankitpatidar@hostnsoft.com> on 24/10/2013
 * 
 * @Description function used in googlecheckout payment gateway
 * 
 * @param string $merchant_id
 * @param string $merchantKey
 * @param string $produceDetails
 * @param string $productDescription
 * @param float $amount
 * @param int $uid
 * @param string $currency
 * @param string $ip
 * @param string $convertCurrency
 * @param string $userCurrency
 * @param int $talktime
 * @param string $uname
 */
function GoogleCheckout($merchant_id, $merchantKey,$produceDetails,$productDescription,$amount,$uid,$currency,$ip,$convertCurrency,$userCurrency,$talktime,$uname) 
{
        // Create a new shopping cart object
        //$server_type = "checkout";
        $server_type = "sandbox";
        //$currency = "GBP";
        $cart = new GoogleCart($merchant_id, $merchantKey, $server_type, $currency);
        
        $digital_url="https://voip91.com/index.php";
        
        // Add items to the cart
        $item = new GoogleItem($produceDetails,$productDescription, 1, $amount);
        
        //set digital content to item
        $item->SetURLDigitalContent($digital_url,"",$productDescription) ;
        
        //add item
        $cart->AddItem($item);
       
        // Add <merchant-private-data>
        $cart->SetMerchantPrivateData(
                new MerchantPrivateData(array("uip" => $ip,"oId"=>$uid,'convertCurrency' => $convertCurrency,'userCurrency' => $userCurrency,'talktime'=> $talktime,'uname'=>$uname)));
        //set shipping details
        $gresult = new GoogleResult();
        $gresult->SetShippingDetails("",0,"false");

        echo $cart->CheckoutButtonCode("SMALL");

}//end of function GoogleCheckout
 
 
 if(isset($action) and $action != '')//condition for paypal and cashu
 {   
    
      $curForToken = strtolower($currency);
 
?>
<body onLoad="submitForm();">
    <form ACTION="<?php echo $action; ?>" METHOD="POST" NAME="form1">
        <input TYPE="hidden" NAME="currency" VALUE="<?php echo strtoupper($currency); ?>">
        <INPUT TYPE="hidden" NAME="amount" VALUE="<?php echo $AMT; ?>">


<?php if($paymentBy == 'paypal')
{ 
    //get encoded details in array
    $repara['orderId']=  base64_encode($orderId);
    $repara['charge']=  base64_encode($charge);
    $repara['talktime']=  base64_encode($talktime);
    $repara['uname']=  base64_encode($userName);
    $repara['convertCurrency']=  base64_encode($convertCurrency);
    $repara['ip']=  base64_encode($ip);
    $repara['userCurrency']=  base64_encode($currencyCodeType);
    
    ?>
        
        <INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
        <INPUT TYPE="hidden" NAME="item_name" VALUE="Recharge Phone91.com">

        <input type="hidden" name="custom" value="<?php echo base64_encode(json_encode($repara));?>">
        
             
<?php }
else if($paymentBy == 'cashu')//for cashu
{
    //prepare arr to post values
    //$reqarr['uname'] = 'demousser';
    $reqarr['uname'] = $userName;
    $reqarr['balance'] = $balance;
    $reqarr['convertCurrency'] = $convertCurrency;
    $reqarr['userCurrency'] = $currencyCodeType;
  ?>
        <input type="hidden" name="merchant_id" value="<?php echo $marchantId;?>">
        
        <input type="hidden" name="language" value="en">
        <input type="hidden" name="display_text" value="Phone91 recharge">
<!--         <input type="hidden" name="token" value="<?php echo md5("$marchantId:$AMT:$curForToken:voip");?>">-->
        <input type="hidden" name="token" value="<?php echo md5("$marchantId:$AMT:$curForToken:".CASHUKEYWORD);?>">
<!--          <input type="hidden" name="test_mode" value="1">-->
        <input type="hidden" name="txt1" value="<?php echo base64_encode(json_encode($reqarr)); ?>">
        <input type="hidden" name="txt2" value="<?php echo base64_encode($orderId) ; ?>">
        <input type="hidden" name="txt3" value="<?php echo base64_encode($talktime) ; ?>">
        <input type="hidden" name="txt4" value="<?php echo base64_encode($charge) ; ?>">
        <input type="hidden" name="txt5" value="<?php echo base64_encode($ip) ; ?>">
        <input type="hidden" name="session_id" value="<?php echo $orderId ; ?>">
    </form>

<?php }
    else
//for google checkout
    {
            //check amount
            if($AMT>0)
            {
                //call function to make payment with google checkout
                GoogleCheckout($marchantId, $merchantKey,"Phone91 Item Level 1", " Basic First Amount",$AMT,  base64_encode($orderId),$currency, base64_encode($ip),  base64_encode($convertCurrency),  base64_encode($currencyCodeType),  base64_encode($talktime), base64_encode($userName));
            }
            else
                die('Invalid amount!!!');
    }
}
else
{
       //get encoded details in array
    $repara['orderId']=  base64_encode($orderId);
    $repara['charge']=  base64_encode($charge);
    $repara['talktime']=  base64_encode($talktime);
    $repara['uname']=  base64_encode($userName);
    $repara['convertCurrency']=  base64_encode($convertCurrency);
    $repara['ip']=  base64_encode($ip);
    $repara['userCurrency']=  base64_encode($currencyCodeType);
    
    $SECFields = array(
                                                'token' => '', 								// A timestamped token, the value of which was returned by a previous SetExpressCheckout call.
                                                'maxamt' => $AMT, 							// The expected maximum total amount the order will be, including S&H and sales tax.
                                                'returnurl' => RETURNURL_EC, 							// Required.  URL to which the customer will be returned after returning from PayPal.  2048 char max.
                                                'cancelurl' => CANCELURL_EC, 							// Required.  URL to which the customer will be returned if they cancel payment on PayPal's site.
                                                'callback' => '', 							// URL to which the callback request from PayPal is sent.  Must start with https:// for production.
                                                'callbacktimeout' => '', 					// An override for you to request more or less time to be able to process the callback request and response.  Acceptable range for override is 1-6 seconds.  If you specify greater than 6 PayPal will use default value of 3 seconds.
                                                'callbackversion' => '', 					// The version of the Instant Update API you're using.  The default is the current version.							
                                                'reqconfirmshipping' => '', 				// The value 1 indicates that you require that the customer's shipping address is Confirmed with PayPal.  This overrides anything in the account profile.  Possible values are 1 or 0.
                                                'noshipping' => '1', 						// The value 1 indiciates that on the PayPal pages, no shipping address fields should be displayed.  Maybe 1 or 0.
                                                //'FlatRateShippingOptions' => '',
                                                'allownote' => '', 							// The value 1 indiciates that the customer may enter a note to the merchant on the PayPal page during checkout.  The note is returned in the GetExpresscheckoutDetails response and the DoExpressCheckoutPayment response.  Must be 1 or 0.
                                                'addroverride' => '', 						// The value 1 indiciates that the PayPal pages should display the shipping address set by you in the SetExpressCheckout request, not the shipping address on file with PayPal.  This does not allow the customer to edit the address here.  Must be 1 or 0.
                                                'localecode' => '', 						// Locale of pages displayed by PayPal during checkout.  Should be a 2 character country code.  You can retrive the country code by passing the country name into the class' GetCountryCode() function.
                                                'pagestyle' => '', 							// Sets the Custom Payment Page Style for payment pages associated with this button/link.  
                                                'hdrimg' => '', 							// URL for the image displayed as the header during checkout.  Max size of 750x90.  Should be stored on an https:// server or you'll get a warning message in the browser.
                                                'hdrbordercolor' => '', 					// Sets the border color around the header of the payment page.  The border is a 2-pixel permiter around the header space.  Default is black.  
                                                'hdrbackcolor' => '', 						// Sets the background color for the header of the payment page.  Default is white.  
                                                'payflowcolor' => '', 						// Sets the background color for the payment page.  Default is white.
                                                'skipdetails' => '', 						// This is a custom field not included in the PayPal documentation.  It's used to specify whether you want to skip the GetExpressCheckoutDetails part of checkout or not.  See PayPal docs for more info.
                                                'email' => '', 								// Email address of the buyer as entered during checkout.  PayPal uses this value to pre-fill the PayPal sign-in page.  127 char max.
                                                'SolutionType' => 'Sole', 						// Type of checkout flow.  Must be Sole (express checkout for auctions) or Mark (normal express checkout)
                                                'landingpage' => 'Billing', 						// Type of PayPal page to display.  Can be Billing or Login.  If billing it shows a full credit card form.  If Login it just shows the login screen.
                                                'channeltype' => 'Merchant', 						// Type of channel.  Must be Merchant (non-auction seller) or eBayItem (eBay auction)
                                                'giropaysuccessurl' => '', 					// The URL on the merchant site to redirect to after a successful giropay payment.  Only use this field if you are using giropay or bank transfer payment methods in Germany.
                                                'giropaycancelurl' => '', 					// The URL on the merchant site to redirect to after a canceled giropay payment.  Only use this field if you are using giropay or bank transfer methods in Germany.
                                                'banktxnpendingurl' => '',  				// The URL on the merchant site to transfer to after a bank transfter payment.  Use this field only if you are using giropay or bank transfer methods in Germany.
                                                'brandname' => '', 							// A label that overrides the business name in the PayPal account on the PayPal hosted checkout pages.  127 char max.
                                                'customerservicenumber' => '', 				// Merchant Customer Service number displayed on the PayPal Review page. 16 char max.
                                                'giftmessageenable' => '', 					// Enable gift message widget on the PayPal Review page. Allowable values are 0 and 1
                                                'giftreceiptenable' => '', 					// Enable gift receipt widget on the PayPal Review page. Allowable values are 0 and 1
                                                'giftwrapenable' => '', 					// Enable gift wrap widget on the PayPal Review page.  Allowable values are 0 and 1.
                                                'giftwrapname' => '', 						// Label for the gift wrap option such as "Box with ribbon".  25 char max.
                                                'giftwrapamount' => '', 					// Amount charged for gift-wrap service.
                                                'buyeremailoptionenable' => '', 			// Enable buyer email opt-in on the PayPal Review page. Allowable values are 0 and 1
                                                'surveyquestion' => '', 					// Text for the survey question on the PayPal Review page. If the survey question is present, at least 2 survey answer options need to be present.  50 char max.
                                                'surveyenable' => '', 						// Enable survey functionality. Allowable values are 0 and 1
                                                'totaltype' => '', 							// Enables display of "estimated total" instead of "total" in the cart review area.  Values are:  Total, EstimatedTotal
                                                'notetobuyer' => '', 						// Displays a note to buyers in the cart review area below the total amount.  Use the note to tell buyers about items in the cart, such as your return policy or that the total excludes shipping and handling.  127 char max.
                                                'buyerid' => '', 							// The unique identifier provided by eBay for this buyer. The value may or may not be the same as the username. In the case of eBay, it is different. 255 char max.
                                                'buyerusername' => '', 						// The user name of the user at the marketplaces site.
                                                'buyerregistrationdate' => '',  			// Date when the user registered with the marketplace.
                                                'allowpushfunding' => '', 					// Whether the merchant can accept push funding.  0 = Merchant can accept push funding : 1 = Merchant cannot accept push funding.			
                                                'taxidtype' => '', 							// The buyer's tax ID type.  This field is required for Brazil and used for Brazil only.  Values:  BR_CPF for individuals and BR_CNPJ for businesses.
                                                'taxiddetails' => ''						// The buyer's tax ID.  This field is required for Brazil and used for Brazil only.  The tax ID is 11 single-byte characters for individutals and 14 single-byte characters for businesses.
                                        );

        // Basic array of survey choices.  Nothing but the values should go in here.  
        $SurveyChoices = array('Choice 1', 'Choice2', 'Choice3', 'etc');

        $Payments = array();
        $Payment = array(
                                        'amt' => $AMT, 							// Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
                                        'currencycode' => 'USD', 					// A three-character currency code.  Default is USD.
                                        'itemamt' => $AMT, 						// Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.  
                                        'shippingamt' => '', 					// Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
                                        'shippingdiscamt' => '', 				// Shipping discount for this order, specified as a negative number.
                                        'insuranceamt' => '', 					// Total shipping insurance costs for this order.  
                                        'insuranceoptionoffered' => 'false', 		// If true, the insurance drop-down on the PayPal review page displays the string 'Yes' and the insurance amount.  If true, the total shipping insurance for this order must be a positive number.
                                        'handlingamt' => '', 					// Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
                                        'taxamt' => '0', 						// Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order. 
                                        'desc' => 'phone91 item', 							// Description of items on the order.  127 char max.
                                        'custom' => base64_encode(json_encode($repara)), 						// Free-form field for your own use.  256 char max.
                                        'invnum' => '', 						// Your own invoice or tracking number.  127 char max.
                                        'PAYMENTREQUEST_0_NOTIFYURL' => '', 						// URL for receiving Instant Payment Notifications
                                        'shiptoname' => '', 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
                                        'shiptostreet' => '', 					// Required if shipping is included.  First street address.  100 char max.
                                        'shiptostreet2' => '', 					// Second street address.  100 char max.
                                        'shiptocity' => '', 					// Required if shipping is included.  Name of city.  40 char max.
                                        'shiptostate' => '', 					// Required if shipping is included.  Name of state or province.  40 char max.
                                        'shiptozip' => '', 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
                                        'shiptocountrycode' => '', 				// Required if shipping is included.  Country code of shipping address.  2 char max.
                                        'shiptophonenum' => '',  				// Phone number for shipping address.  20 char max.
                                        'notetext' => '', 						// Note to the merchant.  255 char max.  
                                        'allowedpaymentmethod' => '', 			// The payment method type.  Specify the value InstantPaymentOnly.
                                        'paymentaction' => 'Sale', 					// How you want to obtain the payment.  When implementing parallel payments, this field is required and must be set to Order. 
                                        'paymentrequestid' => '',  				// A unique identifier of the specific payment request, which is required for parallel payments. 
                                        'sellerpaypalaccountid' => BUSI_MAIL			// A unique identifier for the merchant.  For parallel payments, this field is required and must contain the Payer ID or the email address of the merchant.
                                        );

        $PaymentOrderItems = array();
        $Item = array(
                                'name' => 'Phone91 Item Level 1', 								// Item name. 127 char max.
                                'desc' => 'Basic First Amount', 								// Item description. 127 char max.
                                'amt' => $AMT, 								// Cost of item.
                                'number' => '1', 							// Item number.  127 char max.
                                'qty' => '1', 								// Item qty on order.  Any positive integer.
                                'taxamt' => '', 							// Item sales tax
                                'itemurl' => '', 							// URL for the item.
                                'itemcategory' => '', 						// One of the following values:  Digital, Physical
                                'itemweightvalue' => '', 					// The weight value of the item.
                                'itemweightunit' => '', 					// The weight unit of the item.
                                'itemheightvalue' => '', 					// The height value of the item.
                                'itemheightunit' => '', 					// The height unit of the item.
                                'itemwidthvalue' => '', 					// The width value of the item.
                                'itemwidthunit' => '', 						// The width unit of the item.
                                'itemlengthvalue' => '', 					// The length value of the item.
                                'itemlengthunit' => '',  					// The length unit of the item.
                                'ebayitemnumber' => '', 					// Auction item number.  
                                'ebayitemauctiontxnid' => '', 				// Auction transaction ID number.  
                                'ebayitemorderid' => '',  					// Auction order ID number.
                                'ebayitemcartid' => ''						// The unique identifier provided by eBay for this order from the buyer. These parameters must be ordered sequentially beginning with 0 (for example L_EBAYITEMCARTID0, L_EBAYITEMCARTID1). Character length: 255 single-byte characters
                                );
        array_push($PaymentOrderItems, $Item);
        $Payment['order_items'] = $PaymentOrderItems;

        array_push($Payments, $Payment);

        $BuyerDetails = array(
                                                        'buyerid' => '', 				// The unique identifier provided by eBay for this buyer.  The value may or may not be the same as the username.  In the case of eBay, it is different.  Char max 255.
                                                        'buyerusername' => '', 			// The username of the marketplace site.
                                                        'buyerregistrationdate' => ''	// The registration of the buyer with the marketplace.
                                                        );

        // For shipping options we create an array of all shipping choices similar to how order items works.
        $ShippingOptions = array();
        $Option = array(
                                        'l_shippingoptionisdefault' => '', 				// Shipping option.  Required if specifying the Callback URL.  true or false.  Must be only 1 default!
                                        'l_shippingoptionname' => '', 					// Shipping option name.  Required if specifying the Callback URL.  50 character max.
                                        'l_shippingoptionlabel' => '', 					// Shipping option label.  Required if specifying the Callback URL.  50 character max.
                                        'l_shippingoptionamount' => '' 					// Shipping option amount.  Required if specifying the Callback URL.  
                                        );
        array_push($ShippingOptions, $Option);

        $BillingAgreements = array();
        $Item = array(
                                  'l_billingtype' => '', 							// Required.  Type of billing agreement.  For recurring payments it must be RecurringPayments.  You can specify up to ten billing agreements.  For reference transactions, this field must be either:  MerchantInitiatedBilling, or MerchantInitiatedBillingSingleSource
                                  'l_billingagreementdescription' => '', 			// Required for recurring payments.  Description of goods or services associated with the billing agreement.  
                                  'l_paymenttype' => '', 							// Specifies the type of PayPal payment you require for the billing agreement.  Any or IntantOnly
                                  'l_billingagreementcustom' => ''					// Custom annotation field for your own use.  256 char max.
                                  );

        array_push($BillingAgreements, $Item);

        $PayPalRequestData = array(
                                                   'SECFields' => $SECFields, 
                                                   'SurveyChoices' => $SurveyChoices, 
                                                   'Payments' => $Payments, 
                                                   'BuyerDetails' => $BuyerDetails, 
                                                   'ShippingOptions' => $ShippingOptions, 
                                                   'BillingAgreements' => $BillingAgreements
                                                   );

        // Pass data into class for processing with PayPal and load the response array into $PayPalResult
        $PayPalResult = $PayPal->SetExpressCheckout($PayPalRequestData);

        trigger_error('paypalRequestData:'.  json_encode($PayPalResult));
        $funObj->sendErrorMail('ankitpatidar@hostnsoft.com',  json_encode($PayPalResult));
    
    //redirect to paypal site
    if($AMT > 0)
    {
        $_SESSION['PayPalResult'] = $PayPalResult;
        header('location:'.$PayPalResult['REDIRECTURL']);
        die();
    }
    else
        die('INVALID AMOUNT!!!');
 
}//end of if condition for action
include_once("analyticstracking.php"); 

$funObj = null;
unset($funObj);
?>
<script language="javascript">
    
/**
 * @description function to submit form
 * @returns void */    
function submitForm()
{ 
    //check for amount 
    if(document.form1.amount.value!='' && document.form1.amount.value!=0 )
    {
	document.form1.submit();
    }
    else//redirect to login page
    {
        document.location='login.php';
    }
}
</script>

