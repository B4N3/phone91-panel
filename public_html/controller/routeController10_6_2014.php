<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR . "routeClass.php");
$routeObj = new routeClass();

#VALIDATE THAT USER LOGED IN OR NOT 
if (!$funobj->login_validate()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

#ONLY RESELLER CAN ACCESS THESE FUNCTIONS 
if (!$funobj->check_admin()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

class routeController{
    
    function getRouteDetails()
    {
        $routeObj = new routeClass();
        return $routeObj->getRoute();
    }
}

try{
    $routeCntObj = new routeController();
    if (isset($_REQUEST['call']) && $_REQUEST['call'] != "")
        echo $routeCntObj->$_REQUEST['call']($_REQUEST, $_SESSION);
}catch (Exception $e){
    mail("sameer@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
}
?>