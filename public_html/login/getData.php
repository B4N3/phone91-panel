<?php session_start();
if(isset($_REQUEST['yahoo']))
{
	$email = $_GET['openid_ax_value_email'];
	//echo $uid.'Google'.$username.$email;

	if($email);

	$_SESSION['user_email']=$email;

	//$_SESSION['image']='https://graph.facebook.com/'.$uid.'/picture';

	header('Location: ../accounting/index.php#'.$email);
}
	header('Location: ../index.php#'.$email);


?>

