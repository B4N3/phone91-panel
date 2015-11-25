<?php include_once("SimpleOpenID.class.php");
$openid = new SimpleOpenID;
if(isset($_REQUEST['response']))
{
	$identity = $_GET['openid_identity'];
	$openid->SetIdentity($identity);
	$ok = $openid->ValidateWithServer();
	
	if ($ok) {
		echo $_GET['openid_sreg_email'];
		die();
	  /**
	   * Tasks:
	   * If user doesn't exist (tested by LoadByOpenID) - create an account
	   * If they're new - send them to an activate page with appropriate captcha logic
	   * If they existed already, redirect to their home page
	   */
	
	  // tries to load a user profile using their openid identity,
	  // standard stuff, that would normally be by username.
	  if (!$User->LoadUserByOpenID($identity)) {
		// create a new user
		$User->CreateUserFromOpenID($identity, $_GET['openid_sreg_email']);
	
		// ask the user, as a once off, to prove they're human.
		Utility::Redirect('/activate');
	  } else {
		// redirect the user to their home page
		Utility::Redirect('/' . $User->username);
	  }
	} else if ($openid->IsError() == true) {
	  // There was a problem logging in.  This is captured in $error (do a var_dump for details)
	  $error = $openid->GetError();
	
	  $msg = "OpenID auth problem\nCode: {$error['code']}\nDescription: {$error['description']}\nOpenID: {$identity}\n";
	
	  // error message handling is done further along in the code, but ensure the user
	  // can pass on as much information as possible to replicate the bug.
	} else { 
	  // General error, not due to comms
	  $Error = 'Authorisation failed, please check the credentials entered and double check the use of caplocks.';
	}
	exit();
}
else
{
	// $_REQUEST['openid'] is the input field the user submitted
	//$openid->SetIdentity();
	
	// ApprovedURL is the url we want to call back tohttp://giddh..com/
	//$openid->SetApprovedURL('http://giddh.com/login/login-openid.php?response');
	//$openid->SetTrustRoot('http://giddh.com');
	
	// I'm also requesting their email address for the creation of their new profile
	//$openid->SetOptionalFields('email');
	
	// User::GetOpenIDServer checks the database table 'user_openids' for the 
	// user's openid and the associated identity, which saves having to run
	// a separate HTTP request if it's not available (see else case).
	//if (list($server, $identity) = User::GetOpenIDServer($_REQUEST['openid'])) {
	  $openid->SetOpenIDServer('http://giddh.myopenid.com/');
	 // $openid->SetIdentity();
	//} //else {
	  // 
	  if ($server = $openid->GetOpenIDServer()) {
		// just used to optimise the process
		$identity = $openid->GetIdentity();
	
		// we're now creating a relationship between the user's OpenID and their
		// *real* identity which can be used in subsequent logins to save time.
		//User::SaveOpenIDServer($_REQUEST['openid'], $server, $identity);
	  } else {
		// This shouldn't happen - but will if there's something fundamentally wrong 
		// with our request.  Examples in Debugging section of this tutorial.
		user_error('Something has gone wrong with OpenID identity request process');
	  }
	//}
	
	// send the user to their OpenID provider for authentication
	$openid->Redirect();
}
?>