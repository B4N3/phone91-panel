<?php

/**
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
 * @since  11/04/2014
 * @package Phone91 / controller
 */

include dirname(dirname(__FILE__)) . '/config.php';
//if (!$funobj->login_validate()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}
//if (!$funobj->check_reseller()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}

class acmController {


 
 function getAcmList($request,$session)
 {
      include_once(CLASS_DIR . "account_manager_class.php");
      $acmObj=new Account_manager_class();
      $result = $acmObj->allManagerList($request,$session);
      echo $result;
      unset($acmObj);
 }

  function loadMoreAcmByPage($request,$session)
   {
       include_once(CLASS_DIR . "account_manager_class.php");
        $acmObj = new Account_manager_class();
      $result = $acmObj->allManagerList($request,$session);
      echo $result;
      unset($acmObj);
   }
   
   function addAcm($request,$session){
       include_once(CLASS_DIR . "account_manager_class.php");
        $acmObj = new Account_manager_class();
        echo $msg=$acmObj->addAccountManager($request,$session);
        unset($acmObj);
    }
    
    function checkAcmExists($request,$session){
       include_once(CLASS_DIR . "account_manager_class.php");
        $acmObj = new Account_manager_class();
        echo $msg=$acmObj->checkAcmExists($request,$session);
        unset($acmObj);
    }
    
     function deleteAcm($request,$session)
    {
        include_once(CLASS_DIR . "account_manager_class.php");
        $acmObj = new Account_manager_class();
        echo $msg=$acmObj->deleteAcm($request,$session);
    }
    function accountManagerCodeVerfy($request,$session){
        include_once(CLASS_DIR . "account_manager_class.php");
        $acmObj = new Account_manager_class();
        echo $msg=$acmObj->accountManagerCodeVerfy($request,$session['accountManagerId']);
    }
 
}
try{
    $acmCtrlObj = new acmController();
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "")
       $acmCtrlObj->$_REQUEST['action']($_REQUEST, $_SESSION);
}
 catch (Exception $e)
 {
     mail("sudhir@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
 
 //http://192.168.1.174/controller/adminController.php?action=getAllClientDetail
?>
