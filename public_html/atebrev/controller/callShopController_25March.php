<?php

/* @AUTHOR :SAMEER RATHOD
 * @DESC : CALL SHOP CONTROLLER CONSIST OF ALL THE FUNCTION WHICH WILL BE CALLED FROM CALL SHOP FEATURE 
 *         THIS IS INDEPENDENT CONTROLLER FOR CALL SHOP ONLY
 */
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR . "callShop_class.php");


#VALIDATE THAT USER LOGED IN OR NOT 
if (!$funobj->login_validate()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

#ONLY RESELLER CAN ACCESS THSEE FUNCTIONS 
if (!$funobj->check_reseller()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}
class callShopController
{
    function addSystem($request,$session)
    {
        $callShopObj = new callShop_class(); 
        
        $callShopObj->userName = $request['userName'];
        $callShopObj->password = $request['password'];
        
        $validateResult = $callShopObj->validateUserNameNPassword();
        if($validateResult != 1)
            return $validateResult;            
        
        return $callShopObj->insertSystemDetails($request,$session['id']);    
    }
    function getVerifiedEmailId($request,$session)
    {
        include_once(CLASS_DIR . "callShop_class.php");
        $callClsObj = new callShop_class();
        $emailIdArr  = $callClsObj->getVerifiedEmailIdDetails($session['id'],"slNo,email");
        return json_encode($emailIdArr);
    }
    function addCallShopUser($request,$session)
    {
        $callShopObj = new callShop_class(); 
        
        return $callShopObj->addCallShopUser($request,$session['id']);    
        
    }
    function getCallShopUser($request,$session)
    {
        $callShopObj = new callShop_class(); 
        return $callShopObj->getCallShopDetails($session['id']);
    }
    function searchCallShopUser($request,$session)
    {
        $callShopObj = new callShop_class();
        
        return $callShopObj->getCallShopDetails($session['id'],$request['keyword']);
    }
    function editCallshop($request,$session)
    {
        $callShopObj = new callShop_class();
        return $callShopObj->editCallshop($request,$session['id']);
    }
    function getCallShopCallDetails($request,$session)
    {
        $callShopObj = new callShop_class();
        return $currentCall = $callShopObj->getCallShopActiveCall($request['callShopId']);
    }
    function getCallShopSystemDetails($request,$session)
    {
        $callShopObj = new callShop_class();
        return $systemDetails = $callShopObj->getSystemDetails($request['systemId'],$session['id'],$request['callShopId']);
    }
    function editCallShopSystemDetails($request,$session)
    {
        $callShopObj = new callShop_class();
        $systemDetails = $callShopObj->editSystemDetails($request,$request['systemId'],$session['id'],$request['callShopId']);
        
        return $systemDetails;
         
    }
    function getCallShopSummary($request,$session)
    {
        $callShopObj = new callShop_class();
        if(isset($request['systemId']) && $request['systemId'] != "")
            $systemId = $request['systemId'];
        else
            $systemId = Null;
        
        
        return $callshopsummary = $callShopObj->getCallShopSummary($request['callShopId'],$session['id'],$request['type'],$systemId);
    }
    function resetSummary($request,$session)
    {
        $callShopObj = new callShop_class();
        return $callShopObj->resetCallShopSummary($request['systemId'],$request['callShopId'],$session['id']);
    }
    
    function setUserDeleteFlag($request,$session)
    {
        include_once(CLASS_DIR."reseller_class.php");
        $client_obj = new reseller_class();
        $userid=$session["id"];	
        $msg=$client_obj->changeUserStatus($request,$userid,"deleteFlag");
        
        $message = json_decode($msg);
        if($message->status != "error")
        {
            $callShopObj = new callShop_class();        
            echo $systemMsg = $callShopObj->deleteCallShopSystem($request['userId'],$userid);
        }
    }
}

try{
    $callShopCtrlObj = new callShopController();
    if (isset($_REQUEST['call']) && $_REQUEST['call'] != "")
        echo $callShopCtrlObj->$_REQUEST['call']($_REQUEST, $_SESSION);
}
 catch (Exception $e)
 {
     mail("sameer@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
?>
