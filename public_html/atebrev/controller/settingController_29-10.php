<?php
/* @author : sameer 
 * @created : 03/09/2013
 * @desc : settings controller consist of all the calling functions for user setting 
 */
include dirname(dirname(__FILE__)). '/config.php';
include_once(CLASS_DIR."setting_class.php");
$settingObj = new setting_class();
error_reporting(-1);
if(!$funobj->login_validate()){
        $funobj->redirect(ROOT_DIR."index.php");
}

class settingController
{
    function updateNewsSetting($request,$session)
    {
        /* @desc : function call the updateNewsDb to update the value in the database 91_news ethier 0 or 1 
         *         this is the caller function of the updateNewsDb() from setting_class 
         * 
         */
        $settingObj = new setting_class();
        if(isset($request['type']) && $request['type'] != "")
        {
            #switch case is used for multiple coloumn name if incase any feature increases then increase entry here 
            switch ($request['type'])
            {
                case "facebook":
                {
                    $colName = "facebook";
                    break;
                }
                case "google":
                {
                    $colName = "google";
                    break;
                }
                case "news":
                {
                    $colName = "news";
                    break;
                }
                case "sms":
                {
                    $colName = "sms";
                    break;
                }
                case "email":
                {
                    $colName = "email";
                    break;
                }
                default :
                    exit();
            }
            if($request['value'] == 0 || $request['value'] == 1)
                $value = $request['value'];
            else
            {
                echo json_encode (array("msg" => "Error undefined data please try again","status"=> "error"));
                exit();
            }
                
        }
     if($settingObj->updateNewsDb($colName,$value,$session['id']))
              echo json_encode (array("msg" => "Updated successfuly","status"=> "success"));
     else
              echo json_encode (array("msg" => "Error updating request please try again","status"=> "error"));
     
    }
    function getNewsPageDetails($request,$session)
    {
       $settingObj = new setting_class();
       echo $settingObj->getUpdateNewsDb($session['id']); 
    }
    function getProfileDetails($request,$session)
    {
       $settingObj = new setting_class();
       echo $settingObj->getProfileDetails($session['id']); 
    }
}

$settingCtrlObj = new settingController();

if(isset($_REQUEST['call']) && $_REQUEST['call'] != "")
{
    $functionName = "".trim($_REQUEST['call']);
    $settingCtrlObj->$functionName($_REQUEST,$_SESSION);
}

//}
?>
