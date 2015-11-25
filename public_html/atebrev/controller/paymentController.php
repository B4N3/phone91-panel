<?php

/**
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
 * @since  11/04/2014
 * @package Phone91 / controller
 */

include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."payment_class.php");

class paymentController {

function saveOrderDetail($request,$session){
    
     $payClassObj = new payment_class();
     echo $payClassObj->saveOrderDetail($request,$session['userid']);
     
}
 
function paymentResponse($request,$session){
     $payClassObj = new payment_class();
     echo $payClassObj->paymentResponse($request,$session['userid']);
}
 
}


try{
    $payCtrlObj = new paymentController();
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "" && method_exists($payCtrlObj,$_REQUEST['action'] ))
       $payCtrlObj->$_REQUEST['action']($_REQUEST, $_SESSION);
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
