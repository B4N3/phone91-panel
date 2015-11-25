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
    function signUpUser($request,$session)
    {
        $signUpObj = new signup_class();
        $request['firstName'] = "TESTING USER";
        return $signUpObj->signUp($request);
    }
    
    function verifyContactNumber($request,$session)
    {
        include_once(CLASS_DIR."contact_class.php");
        $cont_obj = new contact_class();
        $userid=$session["id"];	
        if($cont_obj->checkNumberExist($request['countryCode'],$request['mobileNumber'],$userid) == 1 ) {
             $msg = json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Number Is Already In  Use !'));
        }else{
            $signUpObj = new signup_class();
            $msg = $signUpObj->mobileVerificationBeforeLogin($request,$session['id']);
        }
        if(isset($_GET["callback"])){
        echo $_GET["callback"]."(".$msg.")"; 
        }else
        echo $msg;  
        
    }
    
    function verifyNumber($request, $session){
       
    include_once(CLASS_DIR."contact_class.php");
    $cont_obj = new contact_class();
    $userid=$session["id"];	
    $msg=$cont_obj->verifyNumber($request,$userid,1); 
    $msgData = json_decode($msg,TRUE);
    if(isset($request['domain'])){
        if($msgData['msgtype'] == "success"){
           header("location:".$request['domain']."/signup-step.php?success=".$msgData['msg']);
           
        }else
           header("location:".$request['domain']."/signup-step.php?error=".$msgData['msgtype']."&msg=".$msgData['msg']);
        
    }else
      echo $msg;  
    
   }
   
   function updateCurrency($request, $session)
   {
       
       $signUpObj = new signup_class();
       return $signUpObj->updateUserCurrencySetPlan($request['currencyId'],$session['resellerId'],$session['id']);
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
        
        $currencyId = (isset($_REQUEST['currencyId']) and $_REQUEST['currencyId'] != '')? base64_decode($_REQUEST['currencyId']) : "";
        $currencyName = (isset($_REQUEST['currencyName']) and $_REQUEST['currencyName'] != '')? base64_decode($_REQUEST['currencyName']) : "";
        $currencylName = (isset($_REQUEST['currencylName']) and $_REQUEST['currencylName'] != '')? base64_decode($_REQUEST['currencylName']) : "";
        
        //$currencyId = explode('xxxx|', $currencyId);
        $emailId = base64_decode($currencyId);
        
        //$currencyName = explode('xxxx|', $currencyName);
        $firstName = base64_decode($currencyName);
        
        //$currencylName = explode('xxxx|', $currencylName);
        $lastName = base64_decode($currencylName);
        
        
        #- getting parameters from request and session
        $param['password'] = $funobj->randomNumber(8); 
        $param['firstName'] = $firstName;
        $param['lastName'] = $lastName;
        $param['email'] = $emailId;
        $param['domain'] = $request['domain'];
        $param['currency'] = $request['fbCurrency'];
        $param['username'] = $emailId;;
        
      //  print_r($param); die();
        
        $response = $signup_obj->signUp($param);
        
        #- decoding signup response
        $msg = json_decode($response);

        if($msg->status == "success")
        {
            $userId = $_SESSION["id"];
            include_once  CLASS_DIR.'contact_class.php';
            
            #- if response is success then  remove his entry of email id from temp table and entering in to 91_verifiedemail table 
            #- because if user is coming from facebook or google. he is verified.
            
            $conObj = new contact_class();
            $conParm = $conObj->getUnConfirmEmail($userId);
            
            $KeyArr = array("key" => $conParm['confirm_code']);
            
            $conObj->verifyEmailid( $KeyArr , $userId);
            
            header("Location: http://".$_SERVER['HTTP_HOST']."/userhome.php#!contact.php");
        }
        else 
        {
            $domain = $request['domain'];
            header("Location: "."http://phone91.com/signup.php?msg=".$msg->msg."&status=".$msg->status);
        }
        exit();
        
   }
   
    
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