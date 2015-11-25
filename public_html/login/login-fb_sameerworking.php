<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once 'FBconfig.php';

$funobj->clearBrowserCache();
error_reporting(0);

require CLASS_DIR."signup_class.php";
$signup_obj = new signup_class();

$_SESSION['action'] = $_REQUEST['action'];






if(isset($_REQUEST['userDomain']) && $_REQUEST['userDomain'] == 'phone91.com')
{   
    $_SESSION['domain'] = 'phone91.com'; 
    $_SESSION['currentHost'] = 'phone91.com';;
    
    if(isset($_REQUEST['page']))
        $_SESSION['page'] = $_REQUEST['page'];
    else
        $_SESSION['page']  = '';
}
else 
{
     $_SESSION['domain'] = $_SERVER['HTTP_HOST'];
     $_SESSION['currentHost'] = $_SERVER['HTTP_HOST'];;
}



if (isset($me)) 
{
	$email = $me["email"];
	
        //logmonitor('phone91-facebook', 'log'.json_encode($me) );
        
        $_SESSION['otherLogin'] = '1';
        $_SESSION['currentHost'] = $_SESSION['domain'];
        $_SESSION["signup_email"]= $email;
        $_SESSION["signup_first_name"]=$me["first_name"];
        $_SESSION["signup_last_name"]=$me["last_name"];
        $_SESSION['signup_picture']='https://graph.facebook.com/'.$uid.'/picture';
        
       //logmonitor(' phone91-facebook ', $signup_obj->check_email_avail($email ));
        
        if($signup_obj->check_email_avail($email ))
        {
            
            $param['firstName'] = $_SESSION["signup_first_name"];
            $param['lastName'] = $_SESSION["signup_last_name"];
            $param['email'] = $_SESSION["signup_email"];
            $param['username'] = $email;
            $param['password'] = $funobj->randomNumber(8);;
            $param['currency'] = '84';
            $param['signupFrom']  = '1'; 
            $param['domain']  = $_SESSION['domain'];
            
          //  logmonitor(' phone91-facebook ', json_encode($param));
            
            $response = $signup_obj->signUp($param);

            $resultArr = json_decode($response, true);

            
           // logmonitor(' SIGNup 1 ', json_encode($resultArr));
             
             
            if($resultArr['status'] == 'success')
            {
                if($resultArr['getCurrency'])
                { 
                    $response = $funobj->login_user( $param['username'], $param['password'], 0 ,$_SESSION['domain']);
                }
                else 
                {
                    $userDetail = base64_encode(base64_encode(json_encode( array( base64_encode(base64_encode($signup_obj->newUserId)) , base64_encode(1) ) )));

                    if($_SESSION['domain'] == 'phone91.com')
                       header('Location: http://phone91.com/signup-step.php?msg=101&error='.$userDetail); 
                    else 
                    {
                         $response = $funobj->login_user($email, $param['password'] , 0 ,$param['domain']);
                         exit();
                    }
                }
            }
            else 
            {
                $userDetail = json_encode(array('status' => 'error' , 'msg' => $resultArr['status']) );

                if($_SESSION['domain'] == 'phone91.com')
                    header('Location: http://phone91.com/signup.php?error='.$userDetail); 
                else 
                {
                    header('Location: /userhome.php');  
                }
            } 
        }
        else 
        {
            $responseparam =  $funobj->getUserFromEmail($email);
            
            
            //print_r($response);
            
            
          //  logmonitor(' phone91-facebook ', ' Response '.print_r( $responseparam , 1)." R ".$responseparam[0]['userid'] );
            
            $response =  $funobj->getUserInformation( intval($responseparam[0]['userid']) , 1);
       //     
             //logmonitor(' phone91-facebook ', ' Response '.json_encode( $response) );
            
            if($response)
            {
               $host = $_SESSION['domain'];

               $userId = $response['userName'];
               $pwd = $response['password'];

               if(isset($_SESSION['page']) && !empty($_SESSION['page']))
                   $host = $_SESSION['page'];
               
             //  logmonitor(' phone91-facebook ', ' Parameter  '.$response );
               
               $response = $funobj->login_user($userId, $pwd, 0 ,$host);
               
              // logmonitor(' phone91-facebook ', ' Parameter1  '.$response );
            }
            else 
            {
                if($_SESSION['domain'] == 'phone91.com')
                   header('Location: http://phone91.com/signup.php?error='.$userDetail); 
               else 
               {
                   header('Location: /userhome.php');
               }
            }

            
        }	
	exit(0);
}
else
{
	$extra_params = array('scope' => 'email,sms,publish_stream,status_update,user_birthday,user_location,user_work_history,user_online_presence,friends_online_presence,read_friendlists,xmpp_login',
           "nonsence" => "nonsence");
        $loginUrl = $facebook->getLoginUrl($extra_params);
       
   //echo $loginUrl = $facebook->getLoginUrl();die();
	header('Location: '.$loginUrl);
}
?>