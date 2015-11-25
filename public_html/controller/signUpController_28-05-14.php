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
        
        trigger_error("numberExist".  json_encode($request));
        
        if($cont_obj->checkNumberExist($request['countryCode'],$request['mobileNumber'],$userid) == 1 ) {
             $msg = json_encode(array('status' => 'error', 'msg' => 'Sorry This Number Is Already In  Use !'));
        }else{
            trigger_error("verification");
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
    
}
try{
    $signUpClsObj = new signUpController();
    if (isset($_REQUEST['call']) && $_REQUEST['call'] != "")
        echo $signUpClsObj->$_REQUEST['call']($_REQUEST, $_SESSION);
}
 catch (Exception $e)
 {
     mail("sameer@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }

?>