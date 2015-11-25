<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  11 oct 2013
 * @package Phone91 / controller
 */

include dirname(dirname(__FILE__)) . '/config.php';
if (!$funobj->login_validate()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}
//if (!$funobj->check_reseller()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}

class adminManageClientCnt {

 #created by sudhir pandey <sudhir@hostnsoft.com>
 #creation date 11/10/2013
 #function use to get all client detail    
 function getAllClientDetail($_REQUEST, $_SESSION){
    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj=new reseller_class();
    $allClient=$res_obj->manageClients($_REQUEST,$_SESSION,"allClient");
    print_r($allClient);
   
     
 } 
 
 
 /**
  * @author sudhir pandey <sudhir@hostnsoft.com>
  * @since 26-10-2013
  * @description function use to update user general setting in admin panel (like account manager and password).
  */
 function editGeneralSetting($_REQUEST, $_SESSION){
    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj = new reseller_class();
    echo $msg = $res_obj->editGeneralSetting($_REQUEST,$_SESSION['userid']);
}
 
/**
  * @author sudhir pandey <sudhir@hostnsoft.com>
  * @since 26-10-2013
  * @description function use to update user general setting in admin panel (like account manager and password).
  */
 function getUserSysDetail($_REQUEST, $_SESSION){
    $funObj = new fun();
    echo $msg = $funObj->getUserSystemDetail($_REQUEST['userId']);
}

 
 
}
try{
    $adminCtrlObj = new adminManageClientCnt();
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "")
       $adminCtrlObj->$_REQUEST['action']($_REQUEST, $_SESSION);
}
 catch (Exception $e)
 {
     mail("sudhir@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
 
 //http://192.168.1.174/controller/adminController.php?action=getAllClientDetail
?>
