<?php

//session_start();

include('config.php');

//include('dbconfig.php');
//create connection to db
$db = $funobj->connecti();
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

		   $currencyCodeType=$_REQUEST['currency_name'];

		   //$paymentType=$_REQUEST['paymentType'];

		  // $_SESSION['talktime']=$_REQUEST['talktime'];

		   //echo $currencyCodeType;
		   
		   $charge=$_REQUEST['recharge'];

		   if($currencyCodeType != "USD") 
                   { 
                       $result = file_get_contents('http://www.google.com/ig/calculator?hl=en&q='.$charge.$currencyCodeType.'=?USD');

			$s = (explode(":",$result));
			$err= $s[4];$err1=$s[3];
			$s = explode(" ",$s[2]);
                        $recharge =substr($s[1],1);

			if($err !="true}" )
			{ 
                            //mail('shubh124421@gmail.com','error',$err1);
                            mail('ankkubosstest@gmail.com','error',$err);
                        }

		   }
                   else
		   { 
                       $recharge=$_REQUEST['recharge']; 
                   } 	
                   $recharge=ceil($recharge);
                    //$personName = $_SESSION['username'];
                    $AMT = $recharge;

                    //$id_client = $_SESSION['userid'];

                    //$username = $_SESSION['username'];
                    $username= 'ankkuboss';
                    $id_client = 30679;
       
                    $result=mysqli_query($db,"select account_state,id_client,id_currency from clientsshared where id_client='".$id_client."'") or die(mysqli_error());

                    $get_userinfo=mysqli_fetch_array($result);

                    $balance=$get_userinfo['account_state'];				
                    //get ip
                    $ip = $funobj->getUserIp();
                    //get order id
                    $orderId = $funobj->randomNumber(15);
                    $talktime = $_REQUEST['talktime'];
                    //insert values into confirmOrder table
                   $sql = "insert into confirmOrder(id,order_id,client_id,recharge,talktime,balance,recharge_time,status,ip) values(null,'$orderId','$id_client','$charge','$talktime','$balance',now(),'undone','$ip')";

                    mysqli_query($db,$sql) or die(mysqli_error());
                    $custom = mysqli_insert_id();


                    $bmw = rand(1111111,9999999);
                    //print_r($_REQUEST);

//$sql = "UPDATE shubhendra1 SET tamp=".$bmw." WHERE login = ".$username."";
//mysqli_query ($con,$sql) or die(mysqli_error())
?>

<body onLoad="submitForm();">
<form action="https://www.cashu.com/cgi-bin/pcashu.cgi" name="form1" method="post">
  <input type="hidden" name="merchant_id" value="phonee">
  <input type="hidden" name="token" value="<?php echo md5('phonee:'.$AMT.':usd:voip');?>">
  <input type="hidden" name="display_text" value="Phone91 recharge">
  <input type="hidden" name="currency" value="usd">
  <input type="hidden" name="amount" value="<?php echo $AMT;?>">
  <input type="hidden" name="language" value="en">
  <input type="hidden" name="session_id" value="">
  <input type="hidden" name="item_name" value="Phone91 recharge">
  <input type="hidden" name="test_mode" value="1">
  <input type="hidden" name="uname" value="<?php echo base64_encode($username);?>">
  <input type="hidden" name="txt1" value="<?php echo base64_encode($orderId);?>">
  <input type="hidden" name="txt2" value="<?php echo base64_encode($currencyCodeType);?>">
  <input type="hidden" name="txt3" value="<?php echo base64_encode($talktime);?>">
  <input type="hidden" name="txt4" value="<?php echo base64_encode($charge);?>">
  <input type="hidden" name="txt5" value="<?php echo base64_encode($ip);?>">
  <input type="hidden" name="txt6" value="<?php echo base64_encode($balance);?>">
</form>
<script>
  
document.forms[0].submit();

</script> 
<script language="javascript">

function submitForm(){

if(document.form1.charge.value!='' && document.form1.charge.value!=0 && document.form1.charge.value>0){

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
