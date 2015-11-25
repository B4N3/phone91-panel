<?php 
if(isset($_GET['openid.ax.value.email']))
{
	$email = $_GET['openid.ax.value.email'];
	header('Location: ../accounting/index.php#'.$email);
	exit();
}
else
{
	
	function getAuthenticate($callback,$server) {
		// Creating new instance
		$openid = new LightOpenID;
		$openid->identity = $server;
		//setting call back url
		$openid->returnUrl = $callback;
		//finding open id end point from google
		$endpoint = $openid->discover('https://me.yahoo.com');
		$fields =
				'?openid.ns=' . urlencode('http://specs.openid.net/auth/2.0') .
				'&openid.return_to=' . urlencode($openid->returnUrl) .
				'&openid.claimed_id=' . urlencode('http://specs.openid.net/auth/2.0/identifier_select') .
				'&openid.identity=' . urlencode('http://specs.openid.net/auth/2.0/identifier_select') .
				'&openid.mode=' . urlencode('checkid_setup') .
				'&openid.ns.ax=' . urlencode('http://openid.net/srv/ax/1.0') .
				'&openid.ax.mode=' . urlencode('fetch_request') .
				'&openid.ax.required=' . urlencode('email,firstname,lastname') .
				'&openid.ax.type.firstname=' . urlencode('http://axschema.org/namePerson/first') .
				'&openid.ax.type.lastname=' . urlencode('http://axschema.org/namePerson/last') .
				'&openid.ax.type.email=' . urlencode('http://axschema.org/contact/email');
		header('Location: ' . $endpoint . $fields);
	}
	require 'google-open/openid.php';
	$server='https://me.yahoo.com';
	$callback='http://giddh.com/login/getData.php';
	// calling login functions
	getAuthenticate($callback,$server);
}
?>
