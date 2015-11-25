<?php 
//error_reporting(-1);
try{
include dirname(dirname(__FILE__)) . '/config.php';
require_once('config.php');
}
catch(Exception $e){
	var_dump($e);
}
$_REQUEST['talktime'] =150;
$dataAmount = ($_REQUEST['talktime'] * 100);
$orderId = $_REQUEST['orderId'];
 ?>

<form action="/stripe-php/charge.php" method="post">
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-amount="<?php echo $dataAmount; ?>" data-description="Payment Gateway"></script>
        <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">  
</form>