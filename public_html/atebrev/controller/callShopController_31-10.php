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
    function editCallshop($request,$session)
    {
        $callShopObj = new callShop_class();
        return $callShopObj->editCallshop($request,$session['id']);
    }
    function getCallShopCallDetails($request,$session)
    {
        $callShopObj = new callShop_class();
        return $currentCall = $callShopObj->getCallShopActiveCall($session['chainId']);
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
