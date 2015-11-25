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

        $userId = (isset($_REQUEST['currencyId']) and $_REQUEST['currencyId'] != '')? base64_decode($_REQUEST['currencyId']) : "";
        $tariffId = (isset($_REQUEST['fbCurrency']) and $_REQUEST['fbCurrency'] != '')? base64_decode($_REQUEST['fbCurrency']) : "";
        $userDomain = (isset($_REQUEST['userDomain']) and $_REQUEST['userDomain'] != '')? base64_decode($_REQUEST['userDomain']) : "";
        
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
            
            $response = $funobj->login_user($userId, $pwd, 0 ,$host);
        }
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