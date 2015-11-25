<?php

//include 'config/functions.php';



session_start();



if (!empty($_GET['openid_ext1_value_firstname']) && !empty($_GET['openid_ext1_value_lastname']) && !empty($_GET['openid_ext1_value_email'])) {    

    $username = $_GET['openid_ext1_value_firstname'] . $_GET['openid_ext1_value_lastname'];

    $email = $_GET['openid_ext1_value_email'];



	//echo $uid.'Google'.$username.$email;

	if($email);

	$_SESSION['user_email']=$email;

	//$_SESSION['image']='https://graph.facebook.com/'.$uid.'/picture';

	header('Location: ../accounting/index.php#'.$email);

}

?>

