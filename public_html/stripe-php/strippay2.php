<?php 
//error_reporting(-1);
try{
include dirname(dirname(__FILE__)) . '/config.php';
require_once('config.php');
}
catch(Exception $e){
	var_dump($e);
}

$dataAmount = $_REQUEST['amount'];




 ?>
<!--
<form action="charge.php" method="post">
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key=""
          data-amount="<?php// echo $dataAmount; ?>" data-description="Payment Gateway"></script>
  
          
          
</form>-->




<script src="https://checkout.stripe.com/checkout.js"></script>

<button id="customButton">Purchase</button>

<script>
  var handler = StripeCheckout.configure({
    key: '<?php echo $stripe['publishable_key']; ?>',
    image: '/square-image.png',
    token: function(token, args) {
      // Use the token to create the charge with a server-side script.
      // You can access the token ID with `token.id`
    }
  });

  document.getElementById('customButton').addEventListener('click', function(e) {
    // Open Checkout with further options
    handler.open({
      name: 'Demo Site',
      description: 'Payment Gateway',
      amount: <?php echo $dataAmount; ?>
    });
    e.preventDefault();
  });
</script>

