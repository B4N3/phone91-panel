<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  11 oct 2013
 * @package Phone91 / controller
 *@uses it also include accessNumber related events
 */

include dirname(dirname(__FILE__)) . '/config.php';

class CallCtrl {

 
 
 function cutCurrentCall($request,$session)
 {
      include_once(CLASS_DIR . "call_class.php");
      $callObj=new call_class();
      
      
      $result = $callObj->cutCurrentCall($request['uniqueId'],$session['userId'],$session['client_type']);
      
      $json = json_encode(array('status' => $callObj->status,'msg' => $callObj->msg ));
      unset($callObj);
      return $json;
      
 }

 }


try{
    $callContObj = new CallCtrl();
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "" && method_exists($callContObj,$_REQUEST['action'] ))
       echo $callContObj->$_REQUEST['action']($_REQUEST, $_SESSION);
    else
    {
	echo 'You dont have permission to access!';
	
    }
    exit();
}
 catch (Exception $e)
 {
     mail("ankitpatidar@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
