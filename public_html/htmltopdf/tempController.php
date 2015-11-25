<?php
include_once 'session.php';
class acmController {

    function getFirstTemplate($request){
//      print_r($request);
      include_once("templateClass.php");
      $tempObj = new templateClass();
      $template = $tempObj->getFirstTemplate($request);
      echo json_encode(array("template"=>htmlentities($template)));
      unset($tempObj);
    }
    
    function saveTemplateInPdf($request){
        
      include_once("templateClass.php");
      $tempObj = new templateClass();
      $template = $tempObj->saveTemplateInPdf($request);
      echo json_encode(array("fileName"=>$template));
      unset($tempObj);
        
    }
    
    function saveTemplate($request,$session){
        
      include_once("templateClass.php");
      $tempObj = new templateClass();
      $template = $tempObj->saveTemplate($request,$session['userId']);
      $authKey = $tempObj->getUserAuthKey($session['userId']);
      echo json_encode(array("templateId"=>$template,"authKey"=>$authKey));
      unset($tempObj);
      
    }
    
    function uploadTemplate($request,$session,$file){
        
      include_once("templateClass.php");
      $tempObj = new templateClass();
      echo $template = $tempObj->uploadTemplate($file);
      unset($tempObj);
        
        
    }
    
    function login($request){
      include_once("templateClass.php");
      $tempObj = new templateClass();
      echo $template = $tempObj->login($request);
      unset($tempObj);
    }
    
    function generateAuthkey($request,$session){
      include_once("templateClass.php");
      $tempObj = new templateClass();
      echo $template = $tempObj->generateAuthkey($request,$session['userId']);
      unset($tempObj);
    }
    
    function getAllTemplate($request,$session){
      include_once("templateClass.php");
      $tempObj = new templateClass();
      echo $template = $tempObj->getallTemplate($session['userId']);
      unset($tempObj);
    }
    
    function gethtmlFromTemplate($request,$session){
      include_once("templateClass.php");
      $tempObj = new templateClass();
      echo $template = $tempObj->gethtmlFromTemplate($request,$session['userId']);
      unset($tempObj);
    }
 
}

try{
    $acmCtrlObj = new acmController();
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "" && method_exists($acmCtrlObj,$_REQUEST['action'] )){
        if(isset($_FILES)){
            $acmCtrlObj->$_REQUEST['action']($_REQUEST,$_SESSION,$_FILES);
        }else{
            $acmCtrlObj->$_REQUEST['action']($_REQUEST,$_SESSION);
        }
    }else
    {
	echo 'You dont have permission to access!';
	die();
    }
}
 catch (Exception $e)
 {
     mail("sudhir@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
?>
