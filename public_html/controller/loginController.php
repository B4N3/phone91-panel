<?php
/* @AUTHOR :SAMEER RATHOD
 
 */
include dirname(dirname(__FILE__)) . '/config.php';


error_reporting(-1);
class loginController {



    
    public function redirectToBuymorePage($request,$session)
    {
    	$funObj = new fun();
    	//$request['domainName']  ='192.168.1.191';
        $funObj->login_user($request['userName'],$request['password'],0,NULL,0,array(),$request['domainName']);
        exit();
    }
    
}

try{
    
    $loginObj = new loginController();
    
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "" && method_exists($loginObj,$_REQUEST['action'] ))
        echo $loginObj->$_REQUEST['action']($_REQUEST, $_SESSION);
    else
    {
    echo 'You dont have permission to access!';
    die();
    }    
}catch (Exception $e){
    mail("Ankitpatidar@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
}