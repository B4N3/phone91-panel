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

class adminController {

 #created by sudhir pandey <sudhir@hostnsoft.com>
 #creation date 11/10/2013
 #function use to get all client detail    
 function getAllClientDetail($request, $session){
    include_once(CLASS_DIR . "reseller_class.php");
    $res_obj=new reseller_class();
    $allClient=$res_obj->manageClients($request,$session,"allClient");
    print_r($allClient);
    //var_dump($allClient);
     
 } 
 
 function searchCallFailedError($request,$session)
 {
      include_once(CLASS_DIR . "callLog_class.php");
      $logObj=new log_class();
      $result = $logObj->callFailedErrorLog($request);
      echo $result;
 }

 /**
  * @author Ankit patidar <ankitpatidar@hostnsoft.com>
  * @since 8/4/2014
  * @param type $request
  * @param type $session
  */
 function getEditFundLog($request,$session)
 {
     include_once(CLASS_DIR."adminUpdationLog_class.php");
     $log = new adminUpdationLog_class();
     
     if(isset($request['pageNo']))
         $pageNo = $request['pageNo'];
     else
         $pageNo = 1;
     
     echo $logDetail = $log->getEditFundLog($pageNo);
     unset($log);
 }
 
 function getCallLimitLog($request,$session)
 {
     include_once(CLASS_DIR."adminUpdationLog_class.php");
     $log = new adminUpdationLog_class();
     
     if(isset($request['pageNo']))
         $pageNo = $request['pageNo'];
     else
         $pageNo = 1;
     
     echo $logDetail = $log->getAdminLogDetail(6,$pageNo);
     unset($log);
 }
 
 
 function getBandWidthLimitLog($request,$session)
 {
     include_once(CLASS_DIR."adminUpdationLog_class.php");
     $log = new adminUpdationLog_class();
     
     if(isset($request['pageNo']))
         $pageNo = $request['pageNo'];
     else
         $pageNo = 1;
     
     echo $logDetail = $log->getAdminLogDetail(7,$pageNo);
     unset($log);
 }
 
 function getChangeTeriffLog($request,$session)
 {
     include_once(CLASS_DIR."adminUpdationLog_class.php");
     $log = new adminUpdationLog_class();
     
     if(isset($request['pageNo']))
         $pageNo = $request['pageNo'];
     else
         $pageNo = 1;
     
     echo $logDetail = $log->getAdminLogDetail(2,$pageNo);
     unset($log);
 }
 
 function getChangeAccManagerLog($request,$session)
 {
     include_once(CLASS_DIR."adminUpdationLog_class.php");
     $log = new adminUpdationLog_class();
     
     if(isset($request['pageNo']))
         $pageNo = $request['pageNo'];
     else
         $pageNo = 1;
     
     echo $logDetail = $log->getAdminLogDetail(4,$pageNo);
     unset($log);
 }
 
 
 function getChangeUserStatusLog($request,$session)
 {
     include_once(CLASS_DIR."adminUpdationLog_class.php");
     $log = new adminUpdationLog_class();
     
     if(isset($request['pageNo']))
         $pageNo = $request['pageNo'];
     else
         $pageNo = 1;
     
     if(isset($request['actionType']) && $request['actionType'] == 5 )
         $action = 5;
     else 
         $action = 3;
     
     echo $logDetail = $log->getAdminLogDetail($action,$pageNo);
     unset($log);
 }
 
 function getDeleteUserLog($request,$session)
 {
     include_once(CLASS_DIR."adminUpdationLog_class.php");
     $log = new adminUpdationLog_class();
     
     if(isset($request['pageNo']))
         $pageNo = $request['pageNo'];
     else
         $pageNo = 1;
     
     echo $logDetail = $log->getAdminLogDetail(1,$pageNo);
     unset($log);
 }
 
    function getAccessNumberLog($request,$session)
    {
        include_once(CLASS_DIR."phonebook_class.php");
        $phObj = new phonebook_class();

        if(isset($request['pageNo']))
            $pageNo = $request['pageNo'];
        else
            $pageNo = 1;

        echo $logDetail = $phObj->getAccessNumberLogDetail($request , $pageNo);
        
        unset($phObj);
    }
    
    
    function updateBatchUser($request,$session)
    {
        //print_r($request);
        
        include_once(CLASS_DIR."batchUser_class.php");
        $batchCls  = new batchUser_class();
        
        $param['batchName'] = $request['batchName'];
        $param['batchExpiry'] = $request['batchExpiry'];
        $param['batchId'] = $request['batchId'];
      
                
        $result = $batchCls->editBatchDetail($param,$_SESSION['userid'],  1 );
        
        include_once(CLASS_DIR."reseller_class.php");
        $resObj  = new reseller_class();
        
        
        if($request['listenTime'] == "on")
        {
            $currStatus = 0;
        }
        else
        {
            $currStatus = 1;
        }
       
       // echo ' sdfsdf '.$currStatus;
        
        $listenTimeRes =  $resObj->UpdateListenRemainStatusForBatch($_SESSION['userid'],$request['batchId'],$currStatus );
        $listenTimeRes = json_decode($listenTimeRes , true);

        //print_r($listenTimeRes);
        //print_r($result);
       
        
        $result = json_decode($result ,  true );
         
        
        
        if($result["status"] == "error")
        {
              echo json_encode(array("status" => $result["status"] , "msg" => $result["msg"]  ) );
              return;
        }
        
        if($listenTimeRes["status"] == "error")
        {
            echo json_encode(array("status" => $result["status"] , "msg" => $result["msg"]  ) );
            return;
        }
        
      
        echo json_encode(array("status" =>"success" , "msg" => "Updated Successfully!"  ) );
           
    }
    
    
    
    
    
    
 
}

try{
    $adminCtrlObj = new adminController();
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
