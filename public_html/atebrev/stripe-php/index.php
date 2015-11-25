<?php 
error_reporting(-1);
try{
require_once('config.php');
}
catch(Exception $e){
	var_dump($e);


}
 ?>

<form action="charge.php" method="post">
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-amount="1500" data-description="Payment Gateway"></script>
</form>