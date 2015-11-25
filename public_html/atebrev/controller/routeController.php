<?php

/** 
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
 * @since May 2014
 * @uses to fire events for route event
 * 
 */

include_once dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR ."routeClass.php");

$routeObj = new routeClass();

//#VALIDATE THAT USER LOGED IN OR NOT 
//if (!$funobj->login_validate()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}
//
//#ONLY RESELLER CAN ACCESS THESE FUNCTIONS 
//if (!$funobj->check_admin()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}

class routeController{
    
    function __construct() 
    {
	
	$this->routeObj = new routeClass();
    }
    
    function __destruct()
    {
	unset($this->routeObj);
    }
    
    function getRoute($request,$session)
    {
        
        echo $this->routeObj->getRoute($request,$session['userid']);
    }
    
    function addRoute($request,$session)
    {
        
        echo $this->routeObj->addRoute($request);
       
        
    }
    
    function checkRouteExists($request,$session)
    {
        
        echo $this->routeObj->checkRouteExists($request);
       
    }
    
      function getRouteDetail($request,$session)
    {
        
        echo $this->routeObj->getRouteDetail($request,$session['userid']);
       
    }
    
    function editRouteInfo($request,$session)
    {
        
        echo $this->routeObj->editRouteInfo($request);
        
    }
    
    function editDivertedRoute($request,$session)
    {
       
        echo $this->routeObj->editDivertedRoute($request,$session);
       
    }
    
    function editFundRoute($request,$session)
    {
        
        echo $this->routeObj->editFundRoute($request,$session);
        
    }
    
    function getRouteTransaction($request,$session)
    {
	
	
	if(!empty($request['pageNo']) && is_numeric($request['pageNo']))
	    $pageNo = $request['pageNo'];
	else
	    $pageNo= 1;
	
        echo $this->routeObj->getRouteTransaction($request['routeId'],$session['userid'],$pageNo);
        
    }
    
    function addReduceTransaction($request,$session)
    {
	
	 echo $this->routeObj->addReduceTransactionRoute($request,$session);
    }

     function addEditRouteSupportDtl($request,$session)
    {
    
        echo $this->routeObj->addEditRouteSupportDtl($request,$session);
    }

    function addEditRouteEmailContact($request,$session)
    {
        echo $this->routeObj->addEditRouteEmailContact($request,$session);
    }
    
    function getRouteList($request,$session)
    {
        echo json_encode($this->routeObj->getRouteList());
    }
    
    function getAllRouteTransaction($request,$session)
    {
	if(!empty($request['pageNo']) && is_numeric($request['pageNo']))
	    $pageNo = $request['pageNo'];
	else
	    $pageNo= 1;
	echo $this->routeObj->getAllRouteTransaction($session['userid'],$pageNo);
    }
}


try{
    
    $routeCntObj = new routeController();
    
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "" && method_exists($routeCntObj,$_REQUEST['action'] ))
        echo $routeCntObj->$_REQUEST['action']($_REQUEST, $_SESSION);
    else
    {
    echo 'You dont have permission to access!';
    exit();
    
    }
    
}catch (Exception $e){
    mail("Ankitpatidar@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
}
?>