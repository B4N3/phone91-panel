<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  11 oct 2013
 * @package Phone91 / controller
 */

include dirname(dirname(__FILE__)) . '/config.php';
//if (!$funobj->login_validate()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}
//if (!$funobj->check_reseller()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}

class faqsController {

 
 
 function searchFAQS($request,$session)
 {
      include_once(CLASS_DIR . "FAQS_class.php");
      $faqsObj=new FAQS_class();
      $result = $faqsObj->faqsDetails($request);
      echo $result;
 }

 
 
}
try{
    $faqsContObj = new faqsController();
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "" && method_exists($faqsContObj,$_REQUEST['action'] ))
       $faqsContObj->$_REQUEST['action']($_REQUEST, $_SESSION);
    else
    {
	echo 'You dont have permission to access!';
	die();
    }    
}
 catch (Exception $e)
 {
     mail("sudhir@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
 
 
 //http://192.168.1.174/controller/adminController.php?action=getAllClientDetail
?>
