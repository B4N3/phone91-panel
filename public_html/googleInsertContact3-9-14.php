<?php

/* @author : sameer 
 * @created : 30-08-13
 * @desc : redirect url of google sync insert the contacts into mongodb database  
 * 
 */ 
include_once ('config.php');
include_once  (CLASS_DIR.'phonebook_class.php');


//your client secret
$client_id='389356668086.apps.googleusercontent.com';//your client id
$redirect_uri='http://voice.phone91.com/googleInsertContact.php';//you redirect url
$client_secret='KcE9c4ZcxsbnlR_gPXJNktIr';
$max_results = 5000;//set required maximum results
 
 //set access mode
 if(isset($_REQUEST['accessMode']))
     $accessmode = 'offline';//if user offline
 else
     $accessmode = 'online';//if user online
//Initial grant for access approved by user returns 'code' URL param
if(isset($_REQUEST['code']))
{
    //call function to get access token
    $accesstoken = get_oauth2_token($_REQUEST['code'],$accessmode);
    //score url for get contacts,default for session user
    $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&oauth_token='.$accesstoken.'&alt=json';
    //get xml response by calling curl function
    $response =  curl_file_get_contents($url);
    $responseArr = json_decode($response,TRUE);

    $parm['contact'] = array();
     // if(is_object($response))
     //  $responseArr = get_object_vars($response);
   $i = 0;
     foreach($responseArr['feed']['entry'] as $contact)
     {
         $name = "";
         $number = "";
         $emailId = "";
         $contact = $contact;
          
         //condition for name
          if(isset($contact['title']))
          {
              
              $title = $contact['title'];
              $name = $title['$t'];
          }
          
          //condition to get email
          if(isset($contact['gd$email']))
          {
           
            $email = $contact['gd$email'][0];
            $emailId = $email['address'];
          
         } 
         //condition for mobile number
         if(isset($contact['gd$phoneNumber']))
         {
           
            $numberArr = $contact['gd$phoneNumber'][0];
            $number = $numberArr['$t'];
          
         }
         if(isset($numberArr['$t']) && $numberArr['$t'] != "")
         {
            #all name array   
            $parm['name'][$i] = $name;
            #all email array
            $parm['email'][$i] = $emailId;
            #all contact

            if(!in_array($number, $parm['contact']))
              $parm['contact'][$i] = $number;
            else
              $parm['contact'][$i] = '';
            
         }
         $i++;
     }
    
//     print_r($parm);
    $phnbClsObj = new phonebook_class();
     
    $httpHost = base64_decode(urldecode($_REQUEST['state']));
    $httpHostArr = explode("_||_",$httpHost);
    $result  = $phnbClsObj->addContact($parm, $httpHostArr[1]);
    $res = json_decode($result);
    
    $data = "msg=".$res->{'msg'}."&status=".$res->{'status'};
    $data=  urlencode($data);
    $url = "https://".$httpHostArr[0]."/userhome.php#!contact.php"."?".$data;
     
// $url=  urlencode($url);
// echo $url;    
//    header("location: http://voip91.com");
    header("location: ".$url);
    exit();
}

//function to get content by curl
function curl_file_get_contents($url)
{
    $curl = curl_init();
    $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

    curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
    curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.	

    curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
    curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//To stop cURL from verifying the peer's certificate.

    $contents = curl_exec($curl);
    curl_close($curl);
    return $contents;
}

//returns session token for calls to API using oauth 2.0
//set global refreshToken var if refresh token is returned in response
function get_oauth2_token($grantCode,$grantType) 
{
    global $client_id;
    global $client_secret;
    global $redirect_uri;
    
    //set auth url
    $oauth2token_url = "https://accounts.google.com/o/oauth2/token";
    
    //set post fields
    $clienttoken_post = array("client_id" => $client_id,
                              "client_secret" => $client_secret);
 
    //if user online set post fields
    if($grantType === "online")
    {
        $clienttoken_post["code"] = $grantCode;
        $clienttoken_post["redirect_uri"] = $redirect_uri;
        $clienttoken_post["grant_type"] = "authorization_code";
    }
    else if($grantType === "offline")//if user offline set post fields
    {
        $clienttoken_post["refresh_token"] = $grantCode;
        $clienttoken_post["grant_type"] = "refresh_token";
    }
     
    //curl code to get authorized and get access token and refresh token
    $curl = curl_init($oauth2token_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $clienttoken_post);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $json_response = curl_exec($curl);
    curl_close($curl);
    
    //get auth object
    $authObj = json_decode($json_response);
    
    //if offline access requested and granted, get refresh token
    if (isset($authObj->refresh_token))
    {
        global $refreshToken;
        $refreshToken = $authObj->refresh_token;//set refresh token
    }
 
    //get access token and return
    $accessToken = $authObj->access_token;
    return $accessToken;
}//end of function get_oauth2_token()
?>