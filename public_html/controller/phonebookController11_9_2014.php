<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  11 oct 2013
 * @package Phone91 / controller
 *@uses it also include accessNumber related events
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
 
 function editScheduleDetail($request,$session)
 {
     include_once(CLASS_DIR . "clickToCall_plugin_class.php");
      $ctcObj=new clickToCall_plugin_class();
      $result = $ctcObj->editScheduleDetail($request,$session);
      echo $result;
      unset($ctcObj);
 }

 function editSchedule($request,$session)
 {
    include_once(CLASS_DIR . "clickToCall_plugin_class.php");
    $ctcObj=new clickToCall_plugin_class();
    $result = $ctcObj->editSchedule($request,$session);
    echo $result;
    unset($ctcObj);  
 }
 
 function changeScheduleName($request,$session)
 {
     include_once(CLASS_DIR . "clickToCall_plugin_class.php");
      $ctcObj=new clickToCall_plugin_class();
      $result = $ctcObj->changeScheduleName($request,$session);
      echo $result;
      unset($ctcObj);
 }
 
 function deleteScheduleRows($request,$session)
 {
     include_once(CLASS_DIR . "clickToCall_plugin_class.php");
      $ctcObj=new clickToCall_plugin_class();
      $result = $ctcObj->deleteScheduleRows($request,$session);
      echo $result;
      unset($ctcObj);
 }

 function getCountriesWithPrefix($request,$session)
 {
     include_once(CLASS_DIR . "phonebook_class.php");
      $phoneBObj=new phonebook_class();
      $result = $phoneBObj->getCountriesWithPrefix($session['resellerId']);
      echo $result;
      unset($phoneBObj);

 }


function getStatesByPrefix($request,$session)
{
      include_once(CLASS_DIR . "phonebook_class.php");
      $phoneBObj=new phonebook_class();
      $result = $phoneBObj->getStatesByPrefix($session['resellerId'],$request['prefix']);
      echo $result;
      unset($phoneBObj);

}

function getStates($request,$session)
{
    include_once(CLASS_DIR . "phonebook_class.php");
      $phoneBObj=new phonebook_class();
      $result = $phoneBObj->getStates($session['resellerId'],$request['prefix']);
      echo $result;
      unset($phoneBObj);

    
}

function getAccessNumberBystate($request,$session)
{
    include_once(CLASS_DIR . "phonebook_class.php");
    $phoneBObj=new phonebook_class();
    $result = $phoneBObj->getAccessNumberBystate($session['resellerId'],$request['state']);
    echo $result;
    unset($phoneBObj);
    
}

function assignSchedules($request,$session)
{
      include_once(CLASS_DIR . "clickToCall_plugin_class.php");
      $ctcObj=new clickToCall_plugin_class();
      $result = $ctcObj->assignSchedules($request,$session);
      echo json_encode(array('status' => $ctcObj->status,'msg' => $ctcObj->msg,'data'=> $ctcObj->data));
      unset($ctcObj);
}

function unAssignSchedule($request,$session)
{
      include_once(CLASS_DIR . "clickToCall_plugin_class.php");
      $ctcObj=new clickToCall_plugin_class();
      $result = $ctcObj->unAssignSchedules($request,$session);
      echo $result;
      unset($ctcObj);

}

function getOneCountryDetail($request,$session)
{
    include_once(CLASS_DIR . "phonebook_class.php");
    $phoneBObj=new phonebook_class();
    $result = $phoneBObj->getOneCountryDetail($session['resellerId'],$request['prefix']);
    echo $result;
    unset($phoneBObj); 
}

function getAllCountry($request,$session)
{
    include_once(CLASS_DIR . "phonebook_class.php");
    $phoneBObj=new phonebook_class();
    $result = $phoneBObj->countryAllDetail();
    echo json_encode($result);
    unset($phoneBObj);
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
