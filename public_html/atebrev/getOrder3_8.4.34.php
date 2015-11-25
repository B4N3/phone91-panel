<?php
include('config.php');
/**
 * @author Ankit Patidar <AnkitPatidar@hostnsoft.com> 
 * @description common file to request to any payment gateway
 */

//get currencycode
$currencyCodeType=$_REQUEST['currency_name'];

$paymentType=$_REQUEST['paymentType'];

//$_SESSION['talktime']=$_REQUEST['talktime'];

$charge=$_REQUEST['recharge'];
$talktime = $_REQUEST['talktime'];
$id_client = $_SESSION['userid'];

//create connection to db
$db = $funobj->connecti();

$selSql = "SELECT * FROM 91_userBalance WHERE userId='$id_client'";
$username = $_SESSION['username'];
$result=mysqli_query($db,$selSql);
$get_userinfo=  mysqli_fetch_assoc($result);
$balance=$get_userinfo['balance'];
//get ip
$ip = $funobj->getUserIp();
//get order id
$orderId = $funobj->randomNumber(15);

//insert values into confirmOrder table
$sql = "insert into confirmOrder(id,order_id,client_id,recharge,talktime,balance,recharge_time,status,ip) values(null,'$orderId','$id_client','$charge','$talktime','$balance',now(),'undone','$ip')";

mysqli_query($db,$sql) or die(mysqli_error());
$paymentBy = $_REQUEST['paymentBy'];

//set action and parameters to post for all payment gateway
switch ($paymentBy)
{
    case 'paypal':
        //set parameters for paypal
        $action = 'paypal.php';
        $marchantId='phonee';
       
        //query for get currency
        $queryForCurrency = "SELECT * FROM PGCurrency WHERE paymentGateway='paypal'";
        break;
    case 'cashu':
        //set parameters fo cashu
        $action = 'https://www.cashu.com/cgi-bin/pcashu.cgi';
      //$marchantId='phonee';
       $marchantId='ankkuboss';
        $lang = 'en';
         //query for get currency
        $queryForCurrency = "SELECT * FROM PGCurrency WHERE paymentGateway='cashu'";
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
                $merchant_key = $_REQUEST["merchant_key"];
        else
        {
                //$merchant_key = "hZTtCFv5bQTrpZf1DFGWvg";
                $merchant_key= 'mdOWhTqrgPZ_3neyU1YU8g';   
        }
        //query for get currency
        $queryForCurrency = "SELECT * FROM PGCurrency WHERE paymentGateway='googleCheckout'";
        $action='';
        break;
}//end of switch


//get currency in array and set base currency
$curRes = mysqli_query($db, $queryForCurrency);
while($arrCurr = mysqli_fetch_row($curRes))
{
    $currArr[] = $arrCurr[2];
    if($arrCurr[3]==1)//set default currency
        $base = $arrCurr[2];
}

mysqli_close($db);
$convertCurrency='none';
 //check currency in supported currency
if(in_array($currencyCodeType,$currArr))
{
   $AMT = (int)$charge;//if found then do not apply currency conversion
   $currency = $currencyCodeType;
}
else
{
  
    //if not found then convert to base currency
    $AMT =  (int)$funobj->currencyConvert($currencyCodeType,$base,$charge);
    $convertCurrency= $base;
    $currency = $base;
    
}




//function used in googlecheckout 
function GoogleCheckout($merchant_id, $merchant_key,$produceDetails,$productDescription,$amount,$uid,$currency,$ip,$convertCurrency,$userCurrency,$talktime,$uname) 
{
        // Create a new shopping cart object
        //$server_type = "checkout";
        $server_type = "sandbox";
        //$currency = "GBP";
        $cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);
        $digital_url="https://voip91.com/index.php";
        // Add items to the cart
        $item = new GoogleItem($produceDetails,$productDescription, 1, $amount);
        //set digital content to item
        $item->SetURLDigitalContent($digital_url,"",$productDescription) ;
        $cart->AddItem($item);
        //$cart->AddItem($item2);
        // Add <merchant-private-data>
        $cart->SetMerchantPrivateData(
                new MerchantPrivateData(array("uip" => $ip,"oId"=>$uid,'convertCurrency' => $convertCurrency,'userCurrency' => $userCurrency,'talktime'=> $talktime,'uname'=>$uname)));
           $gresult = new GoogleResult();
           $gresult->SetShippingDetails("",0,"false");

         //Display XML data
//        echo "<pre>";
//        echo htmlentities($cart->GetXML());
//        echo "</pre>";
//       // Display a medium size button
       
        echo $cart->CheckoutButtonCode("SMALL");

}
 
 
 if($action != '')//condition for paypal and cashu
 {   
     //echo 'currency'.$currency ;
//     echo '<br>uname'.$username;
//     echo '<br>orderId'.$orderId;
//     echo '<br>talktime'.$talktime;
//     echo '<br>charge'.$charge;
//     echo '<br>ip'.$ip;
//     echo '<br>balance'.$balance;
//     echo '<br>convertCurrency'.$convertCurrency;
//     echo '<br>currencyCodeType'.$currencyCodeType.'<br>';
      $curForToken = strtolower($currency);
  //echo "$marchantId:$AMT:$curForToken:beyond";
  
    
 
?>
<body onLoad="submitForm();">
    <form ACTION="<?php echo $action; ?>" METHOD="POST" NAME="form1">
        <input TYPE="hidden" NAME="currency" VALUE="<?php echo strtoupper($currency); ?>">
        <INPUT TYPE="hidden" NAME="amount" VALUE="<?php echo $AMT; ?>">


<?php if($paymentBy == 'paypal')
{ 
    $repara['orderId']=  base64_encode($orderId);
    
    $repara['charge']=  base64_encode($charge);
    $repara['uname']=  base64_encode($username);
    $repara['convertCurrency']=  base64_encode($convertCurrency);
    $repara['ip']=  base64_encode($ip);
    $repara['userCurrency']=  base64_encode($currencyCodeType);
    
    ?>
        
        <INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
        <INPUT TYPE="hidden" NAME="item_name" VALUE="Recharge Phone91.com">

        <input type="hidden" name="custom" value="<?php echo base64_encode(json_encode($repara));?>">
        
             
<?php }
else//for cashu
{
  ?>
         <input type="hidden" name="merchant_id" value="<?php echo $marchantId;?>">
        
         <input type="hidden" name="language" value="en">
         <input type="hidden" name="display_text" value="Phone91 recharge">
<!--         <input type="hidden" name="token" value="<?php //echo md5("$marchantId:$AMT:$curForToken:voip");?>">-->
           <input type="hidden" name="token" value="<?php echo md5("$marchantId:$AMT:$curForToken:beyond");?>">
          <input type="hidden" name="test_mode" value="1">
        <input type="hidden" name="txt1" value="<?php echo 'demouser'; ?>">
        <input type="hidden" name="txt2" value="<?php echo base64_encode($orderId) ; ?>">
        <input type="hidden" name="txt3" value="<?php echo base64_encode($talktime) ; ?>">
        <input type="hidden" name="txt4" value="<?php echo base64_encode($charge) ; ?>">
        <input type="hidden" name="txt5" value="<?php echo base64_encode($ip) ; ?>">
        <input type="hidden" name="session_id" value="<?php echo $orderId ; ?>">
          <input type="hidden" name="txt6" value="<?php echo base64_encode($balance) ; ?>">
          <input type="hidden" name="txt7" value="<?php echo base64_encode($convertCurrency) ; ?>">
           <input type="hidden" name="txt8" value="<?php echo base64_encode($currencyCodeType) ; ?>">
          

        <?
}
?>
    </form>
<script language="javascript">
function submitForm(){
if(document.form1.amount.value!='' && document.form1.amount.value!=0 ){
	document.form1.submit();
}
else
{
    document.location='login.php';
}
}
</script>
<?php }
else//for google checkout
{
    
            if($AMT>0)
            {
                
                GoogleCheckout($marchantId, $merchant_key,"Phone91 Item Level 1", " Basic First Amount",$AMT,  base64_encode($orderId),$currency, base64_encode($ip),  base64_encode($convertCurrency),  base64_encode($currencyCodeType),  base64_encode($talktime), base64_encode($username));
            }
            else
                die('Invalid amount!!!');
}

include_once("analyticstracking.php"); 
?>

