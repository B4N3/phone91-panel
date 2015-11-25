<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR . "dialPlanClass.php");
$dialPlanObj = new dialPlanClass();

#VALIDATE THAT USER LOGED IN OR NOT 
if (!$funobj->login_validate()) {
    $funobj->redirect(PROTOCOL.HOST_NAME . "/index.php");
}

#ONLY RESELLER CAN ACCESS THESE FUNCTIONS 
if (!$funobj->check_admin()) {
    $funobj->redirect(PROTOCOL.HOST_NAME . "/index.php");
}

class dialPlanController{
    
    function addPlan($request,$session)
    {
        $dialPlanObj = new dialPlanClass();
        return $dialPlanObj->addDialPlan($request['planName'],$session['id']);
    }
    function getPlan($request,$session)
    {
        $dialPlanObj = new dialPlanClass();
        
        if(isset($request['pageNo']))
        $pageNo = $request['pageNo'];
        else
        $pageNo = 1;
        
        if(isset($request['search']) && $request['search'] == 1 && $request['planName'] != "")        
            return $dialPlanObj->getPlanList(2,$pageNo,$session['id'],$request['planName']);
        else
            return $dialPlanObj->getPlanList(1,$pageNo,$session['id']);
    }
    function addCountry($request,$session)
    {
        $dialPlanObj = new dialPlanClass();
        return $dialPlanObj->addDialPlanCountry($request['country'],$session['id'],$request['planId']);
    }
    function getPrefix($request,$session)
    {
        $dialPlanObj = new dialPlanClass();
        return $dialPlanObj->getPrefixDetails($request['planId'],$session['id'],$request['pageNumber']);
    }
    function addPrefix($request,$session)
    {
        $dialPlanObj = new dialPlanClass();
        $dialPlanId = trim($_REQUEST['dialPlanId']);
        $countryId = trim($_REQUEST['countryId']);
        $prefix = trim($_REQUEST['prefix']);
        $routeId = trim($_REQUEST['routeId']);
        return $dialPlanObj->addPrefixDetails($dialPlanId,$countryId,$prefix,$routeId,$session['id']);
    }
    function editPrefix($request,$session)
    {
        $dialPlanObj = new dialPlanClass();
        $dialPlanId = trim($_REQUEST['dialPlanId']);
        $countryId = trim($_REQUEST['countryId']);
        $prefix = trim($_REQUEST['prefix']);
        $routeId = trim($_REQUEST['routeId']);
        $slno = trim($_REQUEST['id']);
        return $dialPlanObj->addPrefixDetails($dialPlanId,$countryId,$prefix,$routeId,$session['id'],$slno,1);
    }
    
    function deletePrefix($request,$session)
    {
        $dialPlanObj = new dialPlanClass();
        $dialPlanId = trim($_REQUEST['dialPlanId']);
        $serialNum = trim($_REQUEST['prefixId']);
        return $dialPlanObj->deletePrefixDetails($dialPlanId,$serialNum,$session['id']);
    }
    
    function searchPrefix($request,$session)
    {
        $dialPlanObj = new dialPlanClass();
        return $dialPlanObj->getPrefixDetails($request['planId'],$session['id'],$request['pageNumber'],1,$request['keyword']);
    }
    function deletePlan($request,$session)
    {
        $dialPlanObj = new dialPlanClass();
        $planId = trim($request['dialPlanId']);
        $userId = trim($session['id']);
        return $dialPlanObj->deleteDialPlan($planId, $userId);
    }
    
    function exportDialPlan($request,$session)
    {
        include_once 'function_layer.php';
        $funObj = new fun();

        $dialPlanObj = new dialPlanClass();
        $planDetail = $dialPlanObj->getPrefixDetails($request['planId'],$session['id'],'','','',1);
        $planDetail = json_decode($planDetail , true);

        $dialPlanPrefix = array();
        foreach($planDetail as $key=>$value)
        {
            if(isset($value['prefix']))
            {
              foreach($value['prefix'] as $prefix)
              {
                
                 $prefix['routeName'] =  $funObj->getRouteName($prefix['routeId']) ;
                 $prefix['country'] = $value['country'];
                 $dialPlanPrefix[] = $prefix;
              }
            }
        }
        
        array_multisort($dialPlanPrefix, 'country',SORT_ASC);
         
       
        $fieldType = array('A' => 'userPrefix' , 
                          'B' => 'routeName' , 
                          'C' => 'country' );
             
        $fileName = $funObj->exportRecords($dialPlanPrefix ,$request['type'] ,'Dial Plan' ,$fieldType);

        if($fileName != FALSE)
          $funObj->downloadExportedFile($fileName);
        else
          return json_encode(array("msg"=>$funObj->msg,"status"=>$funObj->status));
    }   
    
}

try{
    $dialPlanObj = new dialPlanController();
    if (isset($_REQUEST['call']) && $_REQUEST['call'] != "" && method_exists($dialPlanObj,$_REQUEST['call'] ))
        echo $dialPlanObj->$_REQUEST['call']($_REQUEST, $_SESSION);
    else
    {
    echo 'You dont have permission to access!';
    die();
    }    
}
 catch (Exception $e)
 {
     mail("sameer@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }



 
?>