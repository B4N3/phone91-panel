<?php
/**
 * @author Ankit Patidar <AnkitPatidar@hostnsoft.com> 
 * @description common file to request to any payment gateway
 */

//include required files
include('config_ankit.php');
include_once (dirname(__FILE__)."/paymentConfig_ankit.php");


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
                  'orderId' => $orderId);

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
        $action = 'paypal_ankit.php';
        $marchantId='phonee';
        
        //set where claues
        $whereClause = "paymentGateway='paypal'";
        
        //set paymentBy
        $paymentByCO=0;
        break;
    case 'cashu':
        //set parameters fo cashu
        //$action = 'https://www.cashu.com/cgi-bin/pcashu.cgi';
        $action = 'https://sandbox.cashu.com/cgi-bin/pcashu.cgi';
        //$marchantId='phonee';
        $marchantId='ankkuboss';
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
}//end of switch

//insert order details  into confirmOrder table
$data = array("orderId" => $orderId,
              "clientId" => $idClient,
              'recharge' => $charge ,
              'talktime' => $talktime ,
              'balance' => $balance,
              'rechargeTime' => 'now()',
              'status' => 'undone' ,
              'ip' => $ip,
              'paymentBy' => $paymentByCO);

//get table name
$table = '91_confirmOrder';

//apply insert query
$result = $funObj->insertData($data,$table);


//tracker for insert details
$trackId = $funObj->paymentTracker($trackId, $startTimTracker, $idClient,"confirm order insert details",$data);

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

$funObj = null;
unset($funObj);


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
 
 
 if($action != '')//condition for paypal and cashu
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
        <input type="hidden" name="token" value="<?php echo md5("$marchantId:$AMT:$curForToken:beyond");?>">
<!--          <input type="hidden" name="test_mode" value="1">-->
        <input type="hidden" name="txt1" value="<?php echo base64_encode(json_encode($reqarr)); ?>">
        <input type="hidden" name="txt2" value="<?php echo base64_encode($orderId) ; ?>">
        <input type="hidden" name="txt3" value="<?php echo base64_encode($talktime) ; ?>">
        <input type="hidden" name="txt4" value="<?php echo base64_encode($charge) ; ?>">
        <input type="hidden" name="txt5" value="<?php echo base64_encode($ip) ; ?>">
        <input type="hidden" name="session_id" value="<?php echo $orderId ; ?>">
    </form>

<?php }
    else//for google checkout
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
}//end of if condition for action
include_once("analyticstracking.php"); 
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

