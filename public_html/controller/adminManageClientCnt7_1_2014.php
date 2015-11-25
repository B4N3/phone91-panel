<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  11 oct 2013
 * @package Phone91 / controller
 */

include dirname(dirname(__FILE__)) . '/config.php';

if (!$funobj->login_validate()) {
    $funobj->redirect(PROTOCOL.HOST_NAME . "/index.php");
}
//if (!$funobj->check_reseller()) {
//    $funobj->redirect(ROOT_DIR . "index.php");
//}

class adminManageClientCnt {

 #created by sudhir pandey <sudhir@hostnsoft.com>
 #creation date 11/10/2013
 #function use to get all client detail    
 function getAllClientDetail($request, $session){
    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj=new reseller_class();
    
    if(isset($request['value']) && $request['value'] == 1){
     echo $allClient=$res_obj->manageClients($request,$session,"allClient");
   }else{
     echo $allClient=$res_obj->manageClients($request,$session);   
   }
 } 
 
 
 
 #created by sameer <sameer@hostnsoft.com>
 #creation date 18/11/2013
 #function use to get all client details for reseller 
 function getClientDetailReseller($request, $session){
    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj=new reseller_class();
    echo $allClient=$res_obj->manageClients($request,$session);
 } 
 
 /**
  * @author sudhir pandey <sudhir@hostnsoft.com> 
  * @since 07-01-2014
  * @desc function use to get all bulk client detail  
  */
 function getBulkClientDetail($request, $session){
    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj=new reseller_class();
    if($session['client_type'] = 1){
        if(isset($request['value']) && $request['value'] == 1)
            echo $allClient=$res_obj->bulkUserBatch($session['userid'],$request['q'],1,$request['value']);
        else
            echo $allClient=$res_obj->bulkUserBatch($session['userid'],$request['q']);
    }else        
    echo $allClient=$res_obj->bulkUserBatch($session['userid'],$request['q']);
 }
 
 /**
  * @author sudhir pandey <sudhir@hostnsoft.com>
  * @since 26-10-2013
  * @description function use to update user general setting in admin panel (like account manager and password).
  */
 function editGeneralSetting($request, $session){
    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj = new reseller_class();
    echo $msg = $res_obj->editGeneralSetting($request,$session['userid'],$session['client_type']);
}
 
/**
  * @author sudhir pandey <sudhir@hostnsoft.com>
  * @since 26-10-2013
  * @description function use to update user general setting in admin panel (like account manager and password).
  */
 function getUserSysDetail($request, $session){
    $funObj = new fun();
    echo $msg = $funObj->getUserSystemDetail($request['userId']);
 }

 /**
  * @author sudhir pandey <sudhir@hostnsoft.com>
  * @since 31/10/2013
  * @description function use to change user to resller
  */
 function changeUserToReseller($request,$session){

    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj = new reseller_class();
    
    echo $msg = $res_obj->changeUserTypeStatus($request,$session);
     
 }
 
 /**
  * @author sudhir pandey <sudhir@hostnsoft.com>
  * @since 31/10/2013
  * @description function use to change user to resller
  */
 function changeUserTypeStatus($request,$session){

    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj = new reseller_class();
    echo $msg = $res_obj->changeUserTypeStatus($request,$session);
     
 }
 
 /**
  * @author sudhir pandey <sudhir@hostnsoft.com>
  * @date 12/01/2014
  * @description function use to set status of listen remaining minutes during the call
  */
 function listenRemainMinStatus($request,$session){

    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj = new reseller_class();
    echo $msg = $res_obj->listenRemainMinutes($request['userId'],$session['userid'],$request['currStatus']);
     
 }
 
 #created by sudhir pandey <sudhir@hostnsoft.com>
 #creation date 13/02/2014
 #function use to get all client detail by check box if checked then show all detail otherwise show only current (child) Cient detail    
 function ClientDetailBychecked($request, $session){
    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj=new reseller_class();
   if(isset($request['value']) && $request['value'] == 1){
     echo $allClient=$res_obj->manageClients($request,$session,"allClient");
   }else{
     echo $allClient=$res_obj->manageClients($request,$session);   
   }
 } 
 
 #created by sudhir pandey <sudhir@hostnsoft.com>
 #creation date 21/02/2014
 #function use to send bulk mail to user or reseller
 function sendBulkMail($request, $session){
     
     include_once(CLASS_DIR . "reseller_class.php");
     $res_obj=new reseller_class();
     echo $allClient=$res_obj->sendBulkMail($request,$session);   
     
} 

#created by sudhir pandey <sudhir@hostnsoft.com>
 #creation date 21/02/2014
 #function use to send bulk mail to user or reseller
 function sendBulkSms($request, $session){
     
     include_once(CLASS_DIR . "reseller_class.php");
     $res_obj=new reseller_class();
     echo $allClient=$res_obj->sendBulkSms($request,$session);   
     
} 

function getUserInfoAcm($request,$session){

     include_once(CLASS_DIR . "account_manager_class.php");
     $acmObj=new Account_manager_class();
     echo $allAdmin = $acmObj->getallAcccountManager($session['id'],$session['client_type']);
    
     
}
 
/**
 * @author sudhir pandey <sudhir@hostnsoft.com>
 * @since 09-05-2014
 * @desc function use to get route and dial plan list  
 */
function getRouteAndDialplanList($request,$session){
     include_once(CLASS_DIR . "routeClass.php");
     $routeObj=new routeClass();
     echo $msg = $routeObj->getRouteAndDialPlanList($request,$session['id'],$session['client_type']);
}


/**
 * @author sudhir pandey <sudhir@hostnsoft.com> 
 * @since 09-05-11
 * @desc function use to set user dialplan or rout id  
 */
function setUserDialPlanOrRoute($request,$session){
    include_once(CLASS_DIR . "routeClass.php");
    $routeObj=new routeClass();
    echo $msg = $routeObj->setUserDialPlanOrRoute($request,$session['id'],$session['client_type']);
}

function showChainDetail($request,$session){
    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj = new reseller_class();
    echo $msg = $res_obj->showChainDetail($request,$session);
}
 
function changeBulkClientListenTimeStatus($request,$session)
{
  include_once(CLASS_DIR . "reseller_class.php");
  $res_obj = new reseller_class();
  echo $msg = $res_obj->UpdateListenRemainStatusForBatch($request['userId'],$request['batchId'],$request['status']); 
  unset($res_obj);
}


 public function changeSipSetting($request,$session) {
     $funobj = new fun();
     $userId = $request['forUser'];
     if($request['actionType'] == "enable")
         $action = 1;
     if($request['actionType'] == "disable")
         $action = 0;
         
         
     echo $funobj->enableSip($userId,$action);
 }
 
 function getCurrencyList(){
     $funobj = new fun();
     echo $funobj->getCurrencyList();
 }

    function exportBatchUser($request,$session)
    {
      include_once CLASS_DIR.'reseller_class.php';
      $resellerObj = new reseller_class();

      $batchId = $request['batchId'];
      $type = $request['type'];

      if($type != "csv" && $type != "xlsx")
           return json_encode(array("msg"=>"Error invalid format","status"=>"error"));

      $batchUserData = $resellerObj->getBatchDetail($request['batchId'],'1','0','1');
      $batchData = json_decode($batchUserData, true); 

      if($batchData['status'] == 'error')
      {
           return json_encode(array( "msg" => $batchData['status'], "status" => $batchData['status'] ));
      }

      include_once 'function_layer.php';
      $funObj = new fun();


       $fieldType = array('A' => 'userName' , 
                           'B' => 'password' , 
                           'C' => 'balance' , 
                           'D' => 'status' );


      $fileName = $funObj->exportRecords($batchData['userDetail'] ,$type ,'Batch Users' ,$fieldType);

       if($fileName != FALSE)
           $funObj->downloadExportedFile($fileName);
       else
           return json_encode(array("msg"=>$funObj->msg,"status"=>$funObj->status));
    }
 
    function getFilteredClients($request,$session)
    {
	include_once(CLASS_DIR . "reseller_class.php");
	$resObj=new reseller_class();

	if(isset($request['value']) && $request['value'] == 1){
	$allClient=$resObj->getFilteredClients($request,$session,1);
       }else{
	$allClient=$resObj->getFilteredClients($request,$session);   
}

       $json = json_encode(array('status' => $resObj->status,'msg' => $resObj->msg,'data' => $resObj->data));

       echo $json;
    }
 
    function testFilter($request,$session)
    {
	include_once(CLASS_DIR . "reseller_class_ankit.php");
	$resObj=new reseller_class();

	if(isset($request['value']) && $request['value'] == 1){
	$allClient=$resObj->getFilteredClients($request,$session,1);
       }else{
	$allClient=$resObj->getFilteredClients($request,$session);   
	}

       $json = json_encode(array('status' => $resObj->status,'msg' => $resObj->msg,'data' => $resObj->data));

       echo $json;
    }
    
    
    function sendPushOrSMS($request,$session)
    {
	include_once(CLASS_DIR . "reseller_class.php");
	$resObj=new reseller_class();
	
	$resObj->sendPushOrSMS($request,$session);
	
	$json = json_encode(array('status' => $resObj->status,'msg' => $resObj->msg,'data' => $resObj->data));
	echo $json;
}

}

try{
    $adminCtrlObj = new adminManageClientCnt();
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "" && method_exists($adminCtrlObj,$_REQUEST['action'] ))
       $adminCtrlObj->$_REQUEST['action']($_REQUEST, $_SESSION);
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
