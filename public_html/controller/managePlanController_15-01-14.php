<?php
/* @AUTHOR :SAMEER RATHOD
 * @DESC : MANAGE PLAN CONTROLLER CONSIST OF ALL THE FUNCTION WHICH WILL BE CALLED FROM MANGE PLAN FEATURE 
 *         THIS IS INDEPENDENT CONTROLLER FOR PLAN ONLY
 */
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR . "plan_class.php");
$planObj = new plan_class();

#VALIDATE THAT USER LOGED IN OR NOT 
if (!$funobj->login_validate()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

#ONLY RESELLER CAN ACCESS THESE FUNCTIONS 
if (!$funobj->check_reseller() && !$funobj->check_admin()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

class planController {

    function managePlan($request, $session) {
        /*@DESC : FUNCTION FETCHES THE ALL THE PLANS IT WILL CALL GET PLAN FUNCTION 
         */
        
        $planObj = new plan_class();
        $result = $planObj->getPlans($request, $session['id']);
        return $result;
    }
    function manageTariff($request, $session) {
        /*@DESC :GET THE TARIFF DETAILS 
         */
        
        $planObj = new plan_class();
        $userId = $planObj->getPlanUserId($request['pid']);

        if (!($userId && $session['id'] == $userId) && $session['isAdmin'] != 1) {
            #THIS IF ELSE BELLOW IS ONLY FOR CHECKING THE CONDITION THIS WILL BE REMOVED LATER
            if($request['pid'] != "")
                $msgArr['allvaluess'] = "Invalid Plan";
            else
                $msgArr['allvaluess'] = "Invalid Plans";
            
            echo json_encode($msgArr);
            exit();
        }
        if(!isset($request['limit']) || $request['limit'] == "")
            $request['limit'] = 20;
       
        $result = $planObj->getTarrifDetails($request, $session);
        return $result;
    }

    function selectPlan($request, $session) {
        /*@DESC : GET THE PLAN NAME FOR SELECT OPTION FIELD 
         */
        
        $planObj = new plan_class();
        $result = $planObj->getPlanName("planName,tariffId",$session['id'],2,NULL,$session['isAdmin']);
        
        return $result;
    }

    function addplan($request, $session) {
        /*@DESC : FUNCTION VALIDATE ALL THE FIELD AND CALLED WHEN ADD PLAN FORM IS SUBMITTED OR PLAN IS EDITED 
         */
        
        $planObj = new plan_class();
        if($_SESSION['id'] == "")
        {
            $responseArr["msg"] = "Please Login invalid user";
            $responseArr["status"] = "error";
            return json_encode($responseArr);
        }
        $result = $planObj->addTariffPlan($request, $_SESSION['id']);
        return $result;
    }

    function editTariff($request, $session) {
        /*@DESC : THIS ACTION IS CALLED WHEN EDIT TARIFF REQUEST ARRIVES 
         */
        
        $planObj = new plan_class();
        
        
        $result = $planObj->editTariffDetails($request, $session['id'],$session['isAdmin']);
        return $result;
    }

    function deleteTariff($request, $session) {
        /* @DESC : CALL THE FUNCTION WHICH DELETE THE TARIFF FROM THE PLAN
         */
        
        $planObj = new plan_class();

        $result = $planObj->deletePlanTariffs($request, $session['id'],$session['isAdmin']);
        return $result;
    }

    function deletePlan($request, $session) {
        /* @DESC : CALL THE FUNCTION WHICH DELETE THE THE PLAN
         */
        
        $planObj = new plan_class();

        $result = $planObj->deletePlan($request, $session['id'],$session['isAdmin']);
        return $result;
    }

    function getCurrency($request, $session) {
        /* @DESC : CALL THE FUNCTION TO GET THE CURRENCY 
         */
        
        $planObj = new plan_class();
        $currency_name = $request['currency'];
        $result = $planObj->selectData('*', '91_currencyDesc');
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $response['currency'] = $row['currency'];
            $response['currencyId'] = $row['currencyId'];
            $responseArr[] = $response;
        }
        return $responseArr;
    }
    function searchTariffDetails($request, $session)
    {
        /* @DESC : CALL THE FUNCTION TO SEARCH THE TARIFF DETAILS 
         */
        
        $planObj = new plan_class();
        $keyword = $request['keyword'];
        $tariffId = $request['tariffId'];
        $result = $planObj->searchTarriff($keyword,$tariffId);
        return $result;
    }
    function editPlanName($request, $session)
    {
        $planObj = new plan_class();
        $planName = $_REQUEST['planName'];
        $tariffId = $_REQUEST['tariffId'];
        if($session['id'] == "")
                return json_encode (array("msg"=>"Invalid User","status"=>"error"));
       
        return $planObj->editPlanName($tariffId,$planName,$session['id']);
    }
    function addPlanAdmin($request, $session)
    {
        $planObj = new plan_class();
        $planName = $_REQUEST['planName'];
        $outputCurr = $_REQUEST['outputCurr'];
        $billingSec = $_REQUEST['billingSec'];
        $userId = $session['id'];
        return $planObj->addPlanFromAdmin($planName,$outputCurr,$billingSec,$userId);
    }
    function editPlanAdmin($request, $session)
    {
        $planObj = new plan_class();
        $planName = $_REQUEST['planName'];
        $outputCurr = $_REQUEST['outputCurr'];
        $billingSec = $_REQUEST['billingSec'];
        $tariffId = $_REQUEST['tariffId'];
        $userId = $session['id'];
        return $planObj->addPlanFromAdmin($planName,$outputCurr,$billingSec,$userId,"edit",$tariffId);
    }
    

}
try{
    $planCtrlObj = new planController();
    if (isset($_REQUEST['call']) && $_REQUEST['call'] != "")
        echo $planCtrlObj->$_REQUEST['call']($_REQUEST, $_SESSION);
}
 catch (Exception $e)
 {
     mail("sameer@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
?>
