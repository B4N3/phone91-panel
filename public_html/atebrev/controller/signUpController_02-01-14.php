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
        $signUpObj = new signup_class();
        return $signUpObj->mobileVerificationBeforeLogin($request,$session['id']);
    }
    
    function verifyNumber($request, $session){
       
    include_once(CLASS_DIR."contact_class.php");
    $cont_obj = new contact_class();
    $userid=$session["id"];	
    echo $msg=$cont_obj->verifyNumber($request,$userid,1); 
   }
   
   function updateCurrency($request, $session)
   {
       $signUpObj = new signup_class();
       return $signUpObj->updateUserCurrencySetPlan($request['currencyId'],$session['resellerId'],$session['id']);
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