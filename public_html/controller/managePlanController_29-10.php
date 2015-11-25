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

#ONLY RESELLER CAN ACCESS THSEE FUNCTIONS 
if (!$funobj->check_reseller()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

class planController {

    function managePlan($request, $session) {
        /*@DESC : FUNCTION FETCHES THE ALL THE PLANS IT WILL CALL GET PLAN FUNCTION 
         */
        
        $planObj = new plan_class();
        $result = $planObj->getPlans($request, $session);
        return $result;
    }
    function manageTariff($request, $session) {
        /*@DESC :GET THE TARIFF DETAILS 
         */
        
        $planObj = new plan_class();
        $userId = $planObj->getUserId($request);
        if (!($userId && $session['id'] == $userId)) {
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
        $result = $planObj->getPlanName("planName,tariffId",$session['id'],2);
        return $result;
    }

    function addplan($request, $session) {
        /*@DESC : FUNCTION VALIDATE ALL THE FIELD AND CALLED WHEN ADD PLAN FORM IS SUBMITTED OR PLAN IS EDITED 
         */
        
        $planObj = new plan_class();
        
        #VALIDATE THE PLAN NAME SHOULD BE ALPHABETIC ONLY 
        if (isset($request['planName']) && (preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $request['planName']) || strlen(trim($request['planName'])) < 1 || strlen(trim($request['planName'])) > 55)) {
            $responseArr["msg"] = "please select a valid plan name must not containg any spacial character other than '@','_','-'";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }

        if (isset($request['import']) && $request['import'] == 3) {
            /*FOR IMPORT TYPE 3 IE WHEN USER INSERT TARIFF MANUALLY*/
            foreach ($request['countryCode'] as $key => $value) {
                $isInValid = 0;
                #REPLACE THE ASCII CHARACTER 
                $rate = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '',trim($request['rate'][$key]," "));
                $countryName = trim($request['countryName'][$key]);
                $operator = trim($request['operator'][$key]);
                $countryCode = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u',"",trim($request['countryCode'][$key]," "));
                
                #VALIDATE THE TARIFF RATE SHOULD BE NUMERIC ONLY  
                if ($rate == "" || !is_numeric($rate) || $rate <= 0 || strlen($rate) > 9) {
                    $isInValid = 1;
                }
                #VALIDATE THE COUNTRY NAME SHOULD BE ALPHABETIC ONLY
                if ($countryName == "" || preg_match('/[^a-zA-Z\-\_\s]+/', $countryName) || strlen($countryName) > 55) {
                    $isInValid = 1;
                }
                #VALIDATE THE OPERATOR SHOULD BE ALPHABETIC ONLY 
                if ($operator != "" && preg_match('/[^a-zA-Z\-\_\s]+/', $operator)) {
                    $isInValid = 1;
                }
                #VALIDATE COUNTRY CODE SHOULD BE NUMERIC ONLY 
                if ($countryCode == "" || preg_match('/[^0-9]+/', $value) || strlen($value) > 7 || $countryCode < 1) {
                    $isInValid = 1;
                }

                #UNSET THE ROW WHICH IS NOT VALID
                if ($isInValid) {
                    unset($request['countryCode'][$key]);
                    unset($request['countryName'][$key]);
                    unset($request['rate'][$key]);
                    unset($request['operator'][$key]);
                }
            }
            #IF ALL THE ROWS ARE INVALID THEN RETURN ERROR
            if (count($request['countryCode']) < 1) {
                $responseArr["msg"] = "Invalid Tariff value Please enter atleast one correct entry";
                $responseArr["status"] = "error";
                echo json_encode($responseArr);
                exit();
            }
        } elseif (isset($request['import']) && $request['import'] == 1) {
            $extension = substr($_FILES['file']['name'], -3);
            
            #VALIDATE FILE EXTENSION ONLY XLS ARE ALLOWED
            if (isset($_FILES) && ( ($extension != 'xls' && $extension != 'xlsx') || $_FILES['file']['name'] == "")) {
                $responseArr["msg"] = "Invalid file please select a proper excel file";
                $responseArr["status"] = "error";
                echo json_encode($responseArr);
                exit();
            }


            #VALIDATE PERCENTAGE TO INCREASE IN THE TRARIFF RATE 
            if (isset($request['importWith']) && $request['importWith'] == 'on') {
                if (isset($request['importValue']) && (!is_numeric($request['importValue']) || $request['importValue'] < 1 || $request['importValue'] > 100)) {
                    $responseArr["msg"] = "Invalid rate % Please select a value between 1-100";
                    $responseArr["status"] = "error";
                    echo json_encode($responseArr);
                    exit();
                }
            }
        } else {
            #VALIDATE SELECT THIS IS REQUIRED FIELD VALIDATOR
            if (isset($request['plantype']) && trim($request['plantype']) == "Select") {
                $responseArr["msg"] = "Please Select a plan";
                $responseArr["status"] = "error";
                echo json_encode($responseArr);
                exit();
            }
            #VALIDATE PERCENT TO INCREASE OR DECREASE
            if (isset($request['planWith']) && $request['planWith'] == 'on') {
                if (isset($request['planValue']) && (!is_numeric($request['planValue']) || $request['planValue'] < 1 || $request['planValue'] > 100)) {
                    $responseArr["msg"] = "Invalid rate % Please select a value between 1-100";
                    $responseArr["status"] = "error";
                    echo json_encode($responseArr);
                    exit();
                }
            }
        }

        #VALIDATE BILLING SECCOND COMMON FOR ALL FORM 
        if (isset($request['billingSec']) && (preg_match('/[^0-9]+/', $request['billingSec']) || $request['billingSec'] < 1 || $request['billingSec'] > 600)) {
            $responseArr["msg"] = "Invalid billing seconds Please select a value between 1-600";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        $result = $planObj->addPlan($request, $session);
        return $result;
    }

    function editTariff($request, $session) {
        /*@DESC : THIS ACTION IS CALLED WHEN EDIT TARIFF REQUEST ARRIVES 
         */
        
        $planObj = new plan_class();
        
        #VALIDATE PREFIX MUST BE NUMERIC ONLY 
        if (!isset($request['prefix']) || preg_match('/[^0-9]+/', trim($request['prefix'])) || strlen(trim($request['prefix'])) > 15) {
            $responseArr["msg"] = "Invalid prefix please insert proper interger value";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        #VAILDATE COUNTY MUST BE ALPHABETIC AND CAN HAVE SPACE AND - AND _ ONLY
        if (!isset($request['country']) || preg_match('/[^a-zA-z\s\-\_]+/', trim($request['country'])) || strlen(trim($request['country'])) > 55) {
            $responseArr["msg"] = "Invalid Country name please insert proper Alphabetic value";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        #VALIDATE RATE MUST BE NUMERIC OR FLOAT 
        if (!isset($request['rate']) || !is_numeric($request['rate']) || strlen(trim($request['rate'])) > 9 || $request['rate'] < 0) {
            $responseArr["msg"] = "Invalid Rate please insert proper rates ";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        #as per discussion with shubhendra sir 
//        if(isset($request['operator']) || preg_match('/[^a-zA-z\s\-\_]+/',trim($request['operator'])) || strlen(trim($request['operator'])) > 18 )
//        {
//            $responseArr["msg"] = "Invalid Operator Name please provide proper operator name";
//            $responseArr["status"] = "error";
//            echo json_encode($responseArr);
//            exit();
//        }
        #VALIDATE PLAN ID A PLAN SHOUL BE SELECTED FOR WHICH THE TARIFF ROW IS TO EDITED
        if (!isset($request['pid']) || !is_numeric($request['pid'])) {
            $responseArr["msg"] = "Invalid plan please select a plan to edit the tarrif";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        $result = $planObj->editTariff($request, $session);
        return $result;
    }

    function deleteTariff($request, $session) {
        /* @DESC : CALL THE FUNCTION WHICH DELETE THE TARIFF FROM THE PLAN
         */
        
        $planObj = new plan_class();

        $result = $planObj->deletePlanTariffs($request, $session);
        return $result;
    }

    function deletePlan($request, $session) {
        /* @DESC : CALL THE FUNCTION WHICH DELETE THE THE PLAN
         */
        
        $planObj = new plan_class();

        $result = $planObj->deletePlan($request, $session);
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
