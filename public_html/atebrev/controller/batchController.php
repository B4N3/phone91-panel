<?php
/* @author : sameer 
 * @created : 03/09/2013
 * @desc : settings controller consist of all the calling functions for user setting 
 */
include dirname(dirname(__FILE__)). '/config.php';
include_once(CLASS_DIR."batchUser_class.php");

error_reporting(-1);
if(!$funobj->login_validate()){
        $funobj->redirect(PROTOCOL.HOST_NAME . "/index.php");
}

class batchController
{
    function getBatchDeleteFlag($request,$session)
    {
        $batchObj = new batchUser_class();
        $res = $batchObj->getBatchInternalDetail($request['batchId'],$session['id']); 
        echo json_encode($res);
    }

  function showBatchChainDetail($request,$session){
        $batchObj = new batchUser_class();
        echo $res = $batchObj->showBatchChainDetail($request,$session); 
        
    }
    




 
   
   
   

}

$batchCtrlObj = new batchController();

if(isset($_REQUEST['action']) && $_REQUEST['action'] != "" && method_exists($batchCtrlObj,$_REQUEST['action'] ))
{
    $functionName = "".trim($_REQUEST['action']);
    $batchCtrlObj->$functionName($_REQUEST,$_SESSION);
}
else
{
    echo 'You dont have permission to access!';
    die();
}    

?>