<?php
//session_start();
include('config.php');
include('dbcon.php');
//$db = dbConnect();
//$token = $_REQUEST['token'];
//if(! isset($token)) {
		/* The servername and serverport tells PayPal where the buyer
		   should be directed back to after authorizing payment.
		   In this case, its the local webserver that is running this script
		   Using the servername and serverport, the return URL is the first
		   portion of the URL that buyers will return to after authorizing payment
		   */
	$serverName = $_SERVER['SERVER_NAME'];
	$serverPort = $_SERVER['SERVER_PORT'];

	$url=dirname('http//'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);

        
         
        //get currencycode
	$currencyCodeType=$_REQUEST['currency_name'];
        
	$paymentType=$_REQUEST['paymentType'];
        
	$_SESSION['talktime']=$_REQUEST['talktime'];
	//echo $currencyCodeType;
//	if($currencyCodeType=="AED")
//	{  	
//	     $recharge=$_REQUEST['recharge']/3.5;
//	     $recharge=ceil($recharge);
//	//			$recharge=$recharge;
//	}
//	else if($currencyCodeType=="INR")
//	{
//	     $recharge=$_REQUEST['recharge']/42;
//	     $recharge=ceil($recharge);
//	//			$recharge=$recharge+1;
//	}
//	else
//	{
//	     $recharge=$_REQUEST['recharge'];
//	} 
        $charge=$_REQUEST['recharge'];
        if($currencyCodeType !== "USD") 
        {
            $result = file_get_contents('http://www.google.com/ig/calculator?hl=en&q='.$charge.$currencyCodeType.'=?USD');
            $s = (explode(":",$result));
            $err= $s[4];
            $err1=$s[3];
            $s = explode(" ",$s[2]);
            $recharge =substr($s[1],1);

             if($err !="true}" )
             { mail('AnkitPatidar@hostnsoft.com','error',$err1);}

        }
        else
        { 
             $recharge=$_REQUEST['recharge']; 

        } 	
	$currencyCodeType="USD";
	$personName = $_SESSION['username'];
	$AMT = $recharge;
        $talktime = $_REQUEST['talktime'];
      
//	$id_client = $_SESSION['userid'];
        $id_client = 30679;
       
	$username = $_SESSION['username'];	
	$result=mysql_query("select account_state,id_client,id_currency from clientsshared where id_client='".$id_client."'");
	$get_userinfo=mysql_fetch_array($result);
	$balance=$get_userinfo['account_state'];
        //get ip
        $ip = $funobj->getUserIp();
        //get order id
        $orderId = $funobj->randomNumber(15);
        
        //insert values into confirmOrder table
	$sql = "insert into confirmOrder(id,order_id,client_id,recharge,talktime,balance,recharge_time,status,ip) values(null,'$orderId','$id_client','$charge','$talktime','$balance',now(),'undone','$ip')";
        
	mysql_query($sql) or die(mysql_error());
	$custom = mysql_insert_id();
      
         
?>
<body onLoad="submitForm();">
<form ACTION="paypal.php" METHOD="POST" name="form1">
<INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
<INPUT TYPE="hidden" NAME="item_name" VALUE="Recharge Phone91.com">
<input type="hidden" name="currency" value="usd">
<INPUT TYPE="hidden" NAME="amount" VALUE="<?php echo base64_encode($AMT) ; ?>">
<input type="hidden" name="custom" value="<?php echo base64_encode($custom) ;?>">
<input type="hidden" name="orderId" value="<?php echo base64_encode($orderId) ;?>">
<input type="hidden" name="talktime" value="<?php echo base64_encode($talktime) ;?>">
<input type="hidden" name="ip" value="<?php echo base64_encode($ip) ;?>">
</form>
<script language="javascript">
function submitForm(){
if(document.form1.custom.value!='' && document.form1.custom.value!=0 ){
	document.form1.submit();
}else{
	document.location='login.php';
}
}
</script>
<?php
mysql_close($con);
?>
<?php include_once("analyticstracking.php") ?>