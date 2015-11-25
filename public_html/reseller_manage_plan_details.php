<?php
include_once("classes/plan_class.php");
$planObj = new plan_class();
switch ($_REQUEST['call'])
{
    case "managePlan":
    {
        $result = $planObj->managePlan();
        echo $result;
        break;
    }
    case "manageTariff":
    {
        $userId = $planObj->getUserId($_REQUEST);
        if(!($userId && $_SESSION['id'] == $userId))
        {
            $msgArr['allvaluess'] = "Invalid Plan";
            echo json_encode($msgArr);
            exit();
        }
        $result = $planObj->manageTariff();
        echo $result;
        break;
    }
    case "selectPlan":
    {
        $result = $planObj->selectPlan();
        echo $result;
        break;
    }
    case "addplan":
    {
        error_reporting(-1);
        if(isset($_REQUEST['planName']) && (preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $_REQUEST['planName']) || strlen(trim($_REQUEST['planName'])) < 1 || strlen(trim($_REQUEST['planName'])) > 55))
        {
           $responseArr["msg"] = "please select a valid plan name must not containg any spacial character other than '@','_','-'";
           $responseArr["status"] = "error";
           echo json_encode($responseArr);
           exit();
        }
        
        if(isset($_REQUEST['import']) && $_REQUEST['import'] == 3)
        {
            foreach($_REQUEST['countryCode'] as $key => $value)
            {
                $isInValid =0;
                $rate = trim($_REQUEST['rate'][$key]);
                $countryName = trim($_REQUEST['countryName'][$key]);
                $operator = trim($_REQUEST['operator'][$key]);
                
                if($rate == "" || !is_numeric($rate) || $rate <= 0 || strlen($rate) > 9)
                {
                    $isInValid = 1;
                }
                if($countryName == "" || preg_match('/[^a-zA-Z\-\_\s]+/',$countryName) || strlen($countryName) > 55)
                {
                    $isInValid = 1;
                }
                if($operator != "" && preg_match('/[^a-zA-Z\-\_\s]+/',$operator))
                {
                    $isInValid = 1;
                }       
                if($value == "" || preg_match('/[^0-9]+/',$value) || strlen($value) > 7)
                {
                    $isInValid = 1;
                }
                        
                        
             if($isInValid)
             {
                 unset($_REQUEST['countryCode'][$key]);
                 unset($_REQUEST['countryName'][$key]);
                 unset($_REQUEST['rate'][$key]);
             }
            
        }
            if(count($_REQUEST['countryCode']) < 1) 
            {
                $responseArr["msg"] = "Invalid Tariff value Please enter atleast one correct entry";
                $responseArr["status"] = "error";
                echo json_encode($responseArr);
                exit();
            }
        }
        elseif(isset($_REQUEST['import']) && $_REQUEST['import'] == 1)
        {
            
            if(isset($_FILES) && (substr($_FILES['file']['name'],-3) != 'xls' || $_FILES['file']['name'] == ""))
            {
            $responseArr["msg"] = "Invalid file please select a proper excel file";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
            }
            
            
            
            if(isset($_REQUEST['importWith']) && $_REQUEST['importWith'] == 'on')
            {
                if(isset($_REQUEST['importValue']) && (!is_numeric($_REQUEST['importValue']) || $_REQUEST['importValue'] < 1 || $_REQUEST['importValue'] > 100))
                {
                $responseArr["msg"] = "Invalid rate % Please select a value between 1-100";
                $responseArr["status"] = "error";
                echo json_encode($responseArr);
                exit();
                }
            }
         
        }
        else
        {
            if(isset($_REQUEST['plantype']) && trim($_REQUEST['plantype']) == "Select")
            {
                $responseArr["msg"] = "Please Select a plan";
                $responseArr["status"] = "error";
                echo json_encode($responseArr);
                exit();
            }
            if(isset($_REQUEST['planWith']) && $_REQUEST['planWith'] == 'on')
            {
                if(isset($_REQUEST['planValue']) && (!is_numeric($_REQUEST['planValue']) || $_REQUEST['planValue'] < 1 || $_REQUEST['planValue'] > 100))
                {
                $responseArr["msg"] = "Invalid rate % Please select a value between 1-100";
                $responseArr["status"] = "error";
                echo json_encode($responseArr);
                exit();
                }
            }
        }
        
        
        if(isset($_REQUEST['billingSec']) && (preg_match('/[^0-9]+/',$_REQUEST['billingSec']) || $_REQUEST['billingSec'] < 1 || $_REQUEST['billingSec'] > 600))
            {
                $responseArr["msg"] = "Invalid billing seconds Please select a value between 1-600";
                $responseArr["status"] = "error";
                echo json_encode($responseArr);
                exit();
            }
        $result = $planObj->addPlan($_REQUEST,$_SESSION);
        echo $result;
        break;
    }
    case "editTariff":
    {
        error_reporting(-1);
        if(!isset($_REQUEST['prefix']) || preg_match('/[^0-9]+/',trim($_REQUEST['prefix'])) || strlen(trim($_REQUEST['prefix'])) > 15)
        {
            $responseArr["msg"] = "Invalid prefix please insert proper interger value";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        if(!isset($_REQUEST['country']) || preg_match('/[^a-zA-z\s\-\_]+/',trim($_REQUEST['country'])) || strlen(trim($_REQUEST['country'])) > 55)
        {
            $responseArr["msg"] = "Invalid Country name please insert proper Alphabetic value";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        if(!isset($_REQUEST['rate']) || !is_numeric($_REQUEST['rate']) || strlen(trim($_REQUEST['rate'])) > 9 || $_REQUEST['rate'] < 0)
        {
            $responseArr["msg"] = "Invalid Rate please insert proper rates ";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        if(isset($_REQUEST['operator']) || preg_match('/[^a-zA-z\s\-\_]+/',trim($_REQUEST['operator'])) || strlen(trim($_REQUEST['operator'])) > 18 )
        {
            $responseArr["msg"] = "Invalid Operator Name please provide proper operator name";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        if(!isset($_REQUEST['pid']) || !is_numeric($_REQUEST['pid']) )
        {
            $responseArr["msg"] = "Invalid plan please select a plan to edit the tarrif";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        $result = $planObj->editTariff($_REQUEST,$_SESSION);
        echo $result;
        break;
    }
    case "deleteTariff":
    {
        error_reporting(-1);
        $result = $planObj->deletePlanTariffs($_REQUEST,$_SESSION);
        echo $result;
        break;
    }
    case "deletePlan":
    {
        error_reporting(-1);
        $result = $planObj->deletePlan($_REQUEST,$_SESSION);
        echo $result;
        break;
    }
}
?>
