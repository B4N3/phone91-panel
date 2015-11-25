<?php
try
{
	require_once('lib/Stripe.php');	
}
catch(Exception $e)
{
	var_dump($e);
}

$stripe = array(
  "secret_key"      => "sk_test_w8ronCQhbBQF6tXgsNgRZKFW",
  "publishable_key" => "pk_test_BrzuOPqgn59rw4lXF5r8L4LW"
);

Stripe::setApiKey($stripe['secret_key']);



?>