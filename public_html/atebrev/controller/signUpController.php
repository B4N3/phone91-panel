<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */

include_once("../config.php");
include_once(CLASS_DIR."signup_class.php");
class signUpController
{
    /**
     * @author sameer rathod
     * @param array $request
     * @param type $session
     * @return type
     */
    function signUpUser($request,$session)
    {
        $signUpObj = new signup_class();
        $request['firstName'] = "TESTING USER";
        return $signUpObj->signUp($request);
    }
    
    /**
     * @author sudhir pandey
     * @param type $request
     * @param type $session
     */
    function verifyContactNumber($request,$session)
    {
        include_once(CLASS_DIR."contact_class.php");
        $cont_obj = new contact_class();
        $userid=$session["id"];	
        if($cont_obj->checkNumberExist($request['countryCode'],$request['mobileNumber'],$userid) == 1 ) {
             $msg = json_encode(array('status' => 'error', 'msg' => 'Sorry This Number Is Already In  Use !'));
        }else{
            
            $defaultno = $cont_obj->getUserDefaultNumber($userid,1);
            
          
            if($defaultno)
            {
                include_once(CLASS_DIR."sendSmsClass.php");
                $sendSms = new sendSmsClass();

                $paramSms['to'] = $defaultno;
                $paramSms['text'] = "One more number is verified by your account. If it is not you please contact support." ;

                $sendSms->sendMessagesGlobal($paramSms);        
            }
            
            
            
            $signUpObj = new signup_class();
            
            $msg = $signUpObj->mobileVerificationBeforeLogin($request,$session['id']);
        }
        if(isset($_GET["callback"])){
        echo $_GET["callback"]."(".$msg.")"; 
        }else
        echo $msg;  
        
    }
    
    /**
     * @author sameer rathod 
     * @param type $request
     * @param type $session
     */
    function verifyNumber($request, $session)
    {
        include_once(CLASS_DIR."contact_class.php");
        $cont_obj = new contact_class();
        $userid=$session["id"];	
        $msg=$cont_obj->verifyNumber($request,$userid,1); 
        $msgData = json_decode($msg,TRUE);
        if(isset($request['domain'])){
            if($msgData['msgtype'] == "success")
            {
                if($request['signupFrom'])
                {
                    $userDetails = $cont_obj->getUserInformation($userid , 1);
                    $response =  $cont_obj->getVerifiedNumber($userid, $request['key'] );

                    $resData = json_decode($response,TRUE);

                    if(isset($resData['userData'] ))
                    {
                       $userData = json_decode($resData['userData'],TRUE);
                       $tempparam['sender'] = "Phonee";
                       $tempparam['mobiles'] = $userData['countryCode'].$userData['verifiedNumber']; // mobile number without 91
                       $tempparam['message'] = "Hey There, Thanks for Signing up to phone91. your username and password are- userName : ".$userDetails['userName']." and password :".$userDetails['password'] ; // sms text for usd

                       $cont_obj->SendSMS91($tempparam);
                    }

                }

                header("location:".$request['domain']."/signup-step.php?success=".$msgData['msg']);

            }
            else
               header("location:".$request['domain']."/signup-step.php?error=".$msgData['msgtype']."&msg=".$msgData['msg']);

        }else
          echo $msg;  
    
   }
   
   /**
    * 
    * @param type $request
    * @param type $session
    * @return type
    */
   function updateCurrency($request, $session)
   {
       
       $signUpObj = new signup_class();
       return $signUpObj->updateUserCurrencySetPlan($request['currencyId'],$session['resellerId'],$session['id'],1);
   }
    
   
   /**
    *@author sudhir pandey <sudhir@hostnsoft.com>
    *@since 27-12-2013
    *@description function use to Resend call and sms for verification 
    */
   function resendVerifyCode($request, $session){
       include_once(CLASS_DIR."contact_class.php");
       $cont_obj = new contact_class();
       $userid=$_SESSION["id"];	

       if($request['smscall'] == 'sms'){
            $msg=$cont_obj->resendConfirm_code($_REQUEST,$userid);
       }else if($request['smscall'] = 'call'){
            $msg=$cont_obj->callmeConfirm_code($_REQUEST,$userid);
       }

       if(isset($_GET["callback"])){
        echo $_GET["callback"]."(".$msg.")"; 
        }else
        echo $msg;  
       
   }
   
   /**
    * 
    * @param type $request
    * @param type $session
    */
   function backAddNumber($request, $session){
       include_once(CLASS_DIR."contact_class.php");
       $cont_obj = new contact_class();
       $userid=$_SESSION["id"];	
       $msg = $cont_obj->deleteOldTempNumber($userid);
       if(isset($_GET["callback"])){
        echo $_GET["callback"]."(".$msg.")"; 
        }else
        echo $msg;  
       
   }
   
   /**
    * 
    * @author Nidhi <nidhi@wlakover.in>
    * @param srring  $request['domain'] 
    * @param string $name $request['fbCurrency']
    * @detail This function is called from google or facebook login of phone91.
    * @creationDate 31/12/2013
    * 
    */
   function facebookGoogleSignup($request)
   {
        include_once(CLASS_DIR."signup_class.php");
        $signup_obj = new signup_class();

        #- including function layer.php only to call randomNumber this function.
        include_once  "../function_layer.php";
        $funobj = new fun();
        
        $userId = (isset($_REQUEST['currencyId']) and $_REQUEST['currencyId'] != '')? base64_decode($_REQUEST['currencyId']) : "";
        $tariffId = (isset($_REQUEST['fbCurrency']) and $_REQUEST['fbCurrency'] != '')?$_REQUEST['fbCurrency'] : "";
        $userDomain = (isset($_REQUEST['userDomain']) and $_REQUEST['userDomain'] != '')? $_REQUEST['userDomain'] : $_SERVER['HTTP_HOST'];

        if(empty($userId) || empty($tariffId))
        {
            unset($_SESSION);
            header('Location: http://'.$userDomain.'/signup.php?msg=101'); 
            
            exit();
        }
        
        $_SESSION['currentHost'] = $userDomain;
        $_SESSION['domain'] = $userDomain;
        
        
        $userId = base64_decode($userId);
        
        $resellerId = $funobj->getResellerId($userId);

        $userCurrencyId = $funobj->getOutputCurrency($tariffId);
        
        $signup_obj->updateUserCurrencySetPlan($userCurrencyId,$resellerId,$userId ,1);
        
        $response = $funobj->getUserInformation($userId , 1);
       
        if($response)
        {
            $host = $userDomain;
            $userId = $response['userName'];
            $pwd = $response['password'];
            $_SESSION['id'] = $response['userId'];
            $response = $funobj->login_user($userId, $pwd, 0 ,$host);
        }
        else
        {
            header('Location: http://'.$userDomain.'/signup.php?msg=101'); 
            exit();
        }
   }
   
   /**
    * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
    * @since 10/03/2014
    * @param type $request
    * @param type $session 
    */
   function loginAs($request,$session)
   {
       $type = (isset($request['type']) && $request['type'] != '')?$request['type']:'';
       
       include_once(CLASS_DIR."signup_class.php");
        $signup_obj = new signup_class();

       if($type == '')
           echo $signup_obj->loginAsInReseller($request,$session);
       else
           echo $signup_obj->loginAsInAdmin($request,$session);
   } //end of loginAs
   
   function signupWlabel($request, $session){
     include_once(CLASS_DIR."signup_class.php");
     $signup_obj = new signup_class();
     
     #default currency 
     $request['currency'] = 8;

     echo $msg=$signup_obj->signUp($request);
     exit();
   }
   
   /*
    * This function runs in case of forgot password.
    * This function is to resend verification code. 
    * @author nidhi <nidhi@walkover.in>
    */
     function resendVerificationCode($request ,$session)
    {
        include_once(ROOT_DIR."function_layer.php");
        $funobj = new fun();
        
        $smsCall = trim($request['smsCall']);
        $userId = trim($request['userId']); 
        
        if(preg_match( NOTNUM_REGX ,$userId) || strlen($userId) < 1 )///[^a-zA-Z0-9\_\@\.]+/
        {
           $errorMessage =  json_encode(array("status" => "error" , "msg" =>"Invalid UserId. Please provide valid user id" ));
            header('Location: http://phone91.com/forget-password.php?error='.base64_encode($errorMessage));
        }
        
        if(preg_match( NOTNUM_REGX ,$smsCall) || strlen($smsCall) < 1 )///[^a-zA-Z0-9\_\@\.]+/
        {
           $errorMessage =  json_encode(array("status" => "error"  , "msg" =>"Please enter Proper value for sms or call. 1 - sms , 2 - call , 3 - email " ));
           header('Location: http://phone91.com/forget-password.php?error='.base64_encode($errorMessage));
        }
        
        

        $param['smsCall'] = $smsCall;
        $param['userId'] = $userId;
        
        if(isset($request['type']) && $request['type'] == '1')
        {
             $param['type'] = 'signUp';
        }
        
        $response = $funobj->resendVerfication($param);
        $response = json_decode($response , true );
        
        //stdClass Object ( [type] => 1 [id] => 38614 [contact] => Array ( [0] => stdClass Object ( [number] => 91-8989832694 [resellerId] => 2 [userId] => 38614 [resellerName] => voipreseller [userName] => sneha1asdf ) ) [status] => success [msg] => verification code successfully send. [verifyOption] => 0 )
        
       if($response['status'] == 'success')
       {
           
            $responseArr['type'] = "1";
            $responseArr['id'] = $userId;

            $userName = $funobj->getUserId($userId , 1 ); //getUserId .. 
            
            if($request['smsCall'] == "3")
            {
                $number = $request['verifiedNumber'];
            }else 
            {
                $number = $request['countryCode']."-".$request['verifiedNumber'];
            }
             
            $responseArr['contact'][] =  array( 'number' => $number  , 'userId' => $userId , "userName" => $userName );
            $responseArr['status'] = 'success';
            $responseArr['msg'] = 'verification code successfully send.';
            $responseArr['verifyOption'] = "0";
           
            $errorMessage =  json_encode($responseArr);
            header('Location: http://phone91.com/forget-password.php?error='.base64_encode($errorMessage));
       }
       else
       {
           $errorMessage =  json_encode(array("status" => "error"  , "msg" =>$response['message'] ));
           header('Location: http://phone91.com/forget-password.php?error='.base64_encode($errorMessage));
       }   
    }
    public function fbGlSignUp($request,$session)
    {
        $domain = $request['domain'];
        $id = base64_decode($request['eid']);
        $signupObj = new signup_class();
        $result = $signupObj->getSignUpDetailsFromCache($id,$domain);
        
        if(!$result)
        {
            
            header('Location: http://'.$domain.'/signup-step_sameer.php?error='.$signupObj->msg.'&fbgl=1&eid='.$id); 
           exit();
        }
        
        $param = $signupObj->data;
        $param['isGoogleFb'] = 1;
//        print_R($param);
//        die();
//        $param['signFrom'] = 1;
        $response = $signupObj->signUp($param);
        
        $resultArr = json_decode($response, true);

     var_dump($resultArr);
     die();
        
        if($resultArr['status'] == 'success')
        {
//            if($resultArr['getCurrency'])
//            { 
                $response = $funobj->login_user( $param['username'], $param['password'], 0 ,$domain);
//            }
//            else 
//            {
//                $userDetail = base64_encode(base64_encode(json_encode( array( base64_encode(base64_encode($signupObj->newUserId))  , base64_encode(1) ))));
//
//                if($domain == 'phone91.com')
//                   header('Location: http://phone91.com/signup-step.php?msg=101&error='.$userDetail); 
//                else 
//                {
//                     
//                     $response = $funobj->login_user($email, $param['password'] , 0 ,$param['domain']);
//                     exit();
//                }
//            }
        }
        else 
        {
            $userDetail = json_encode(array('status' => 'error' , 'msg' => $resultArr['msg']) );

            if($domain == 'phone91.com')
                header('Location: http://phone91.com/signup.php?error='.$userDetail); 
            else 
            {
                
               //$response = $funobj->login_user($signup_obj->newUserId, $param['password'] , 0 ,$param['domain']);
                header('Location: /userhome.php');      
                
                exit();
            }

        }
    }
   
    
}


try{
    $signUpClsObj = new signUpController();
    if (isset($_REQUEST['call']) && $_REQUEST['call'] != "" && method_exists($signUpClsObj,$_REQUEST['call'] ))
        echo $signUpClsObj->$_REQUEST['call']($_REQUEST, $_SESSION);
    else
    {
	echo 'You dont have permission to access!';
	die();
    }
}
 catch (Exception $e)
 {
     mail("sameer@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }

?>