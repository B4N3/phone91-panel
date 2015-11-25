<?php
/* @author : sameer 
 * @created : 09-09-2013
 * @desc : this is controller file for all actions related to active calls 
 */

include_once (dirname(dirname(__FILE__)).'/config.php');
include_once (CLASS_DIR.'activeCall_class.php');

if(!$funobj->login_validate()){
        $funobj->redirect(ROOT_DIR."index.php");
}
error_reporting(-1);
$activeClsObj = new activeCall_class();
switch ($_REQUEST['call'])
{
    case "getActiveCalls":
    {
       $result = $activeClsObj->getActiveCalls();
       echo json_encode($result);
       break;
    }
        
       

}
unset($logClsObj);
?>