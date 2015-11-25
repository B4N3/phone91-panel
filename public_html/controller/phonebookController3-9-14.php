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

class PhonebookController {

 
 
 function getAccessNumberDetails($request,$session)
 {
      include_once(CLASS_DIR . "phonebook_class.php");
      $phoneBObj=new phonebook_class();
      $result = $phoneBObj->getAccessNumberDetails($request);
      echo $result;
      unset($phoneBObj);
 }

 function getMatchedContact($request,$session)
 {
      include_once(CLASS_DIR . "phonebook_class.php");
      $phoneBObj=new phonebook_class();
      $result = $phoneBObj->getMatchedContact($request,$session);
      echo $result;
      unset($phoneBObj);
 }
 
 function addSchedule($request,$session)
 {
     include_once(CLASS_DIR . "clickToCall_plugin_class.php");
      $ctcObj=new clickToCall_plugin_class();
      $result = $ctcObj->addSchedule($request,$session);
      echo $result;
      unset($ctcObj);
 }
 
 function getScheduleList($request,$session)
 {
     include_once(CLASS_DIR . "clickToCall_plugin_class.php");
      $ctcObj=new clickToCall_plugin_class();
      $result = $ctcObj->getScheduleList($request,$session);
      echo $result;
      unset($ctcObj);
 }
 
  function getScheduleDetail($request,$session)
 {
     include_once(CLASS_DIR . "clickToCall_plugin_class.php");
      $ctcObj=new clickToCall_plugin_class();
      $result = $ctcObj->getScheduleDetail($request,$session);
      echo $result;
      unset($ctcObj);
 }
 
 function deleteSchedule($request,$session)
 {
     include_once(CLASS_DIR . "clickToCall_plugin_class.php");
      $ctcObj=new clickToCall_plugin_class();
      $result = $ctcObj->deleteSchedule($request,$session);
      echo $result;
      unset($ctcObj);
 }
}
try{
    $phoneContObj = new PhonebookController();
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "")
       $phoneContObj->$_REQUEST['action']($_REQUEST, $_SESSION);
}
 catch (Exception $e)
 {
     mail("sudhir@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
 
 //http://192.168.1.174/controller/adminController.php?action=getAllClientDetail
?>
