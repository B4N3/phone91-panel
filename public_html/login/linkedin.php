<?php
    session_start();

    $config['base_url']             =   'http://www.giddh.com/login/?login&oauth_provider=linkedIn';
    $config['callback_url']         =   'http://www.giddh.com/login/?login&oauth_provider=linkedIn';
    $config['linkedin_access']      =   'yljnfimtf3wi';
    $config['linkedin_secret']      =   'ooRplllWvFFB2PCA';
	
   include_once "linkedinReq.php";
   
   $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
   
   if (!isset($_REQUEST['oauth_verifier']) && ! isset($_SESSION['requestToken'])){
     # First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback    
    //$linkedin->debug = true;

    # Now we retrieve a request token. It will be set as $linkedin->request_token
    $linkedin->getRequestToken();
    $_SESSION['requestToken'] = serialize($linkedin->request_token);
  
    # With a request token in hand, we can generate an authorization URL, which we'll direct the user to
    //echo "Authorization URL: " . $linkedin->generateAuthorizeUrl() . "\n\n";
    header("Location: " . $linkedin->generateAuthorizeUrl());
	
	
    # First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
    //$linkedin->debug = true;
    }
 	
   if (isset($_REQUEST['oauth_verifier'])){
        $_SESSION['oauth_verifier']     = $_REQUEST['oauth_verifier'];

        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->getAccessToken($_REQUEST['oauth_verifier']);

        $_SESSION['oauth_access_token'] = serialize($linkedin->access_token);
        header("Location: " . $config['callback_url']);
        exit;
   }
   else{
        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
   }


    # You now have a $linkedin->access_token and can make calls on behalf of the current member
    $xml_response = $linkedin->getProfile("~:(id,first-name,last-name,headline,picture-url)");

    echo '<pre>';
    echo 'My Profile Info';
    echo $xml_response;
    echo '<br />';
    echo '</pre>';

// 
    // $search_response = $linkedin->search("?company-name=facebook&count=10");
    // //$search_response = $linkedin->search("?title=software&count=10");
// 
    // //echo $search_response;
    // $xml = simplexml_load_string($search_response);
// 
    // echo '<pre>';
    // echo 'Look people who worked in facebook';
    // print_r($xml);
    // echo '</pre>';
?>
