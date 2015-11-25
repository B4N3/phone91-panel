<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  25 sep 2013
 * @package Phone91 / controller
 */

include dirname(dirname(__FILE__)) . '/config.php';
if (!$funobj->login_validate()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}
if (!$funobj->check_reseller()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

class websiteController {

 
  #created by sudhir pandey <sudhir@hostnsoft.com>
  #creation date 25-09-2013
  #function use to add webite detail like company name ,domain name and theme    
  function addWebsite($request, $session){
       
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       echo $websiteObj->addManageWebsite($request,$userId);
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 28/09/2013
   #function use to add general detail 
   function addGeneralData($request, $session){
         
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       echo $websiteObj->addGeneralData($request,$userId);
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 02/10/2013
   #function use to add Home page detail 
   function addHomeData($request, $session){
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       echo $websiteObj->addHomeData($request,$userId);
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 03/10/2013
   #function use to add about page detail 
   function addAboutData($request, $session){
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       echo $websiteObj->addAboutData($request,$userId);
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 03/10/2013
   #function use to add contact page data 
   function addContacPageData($request, $session){
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       echo $websiteObj->addContacPageData($request,$userId);
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 04/10/2013
   #function use to add pricing page data 
   function addPricingData($request, $session){
       
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       echo $websiteObj->addPricingData($request,$userId);
       
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 04/10/2013
   #function use to delete website 
   function deleteWebsite($request, $session){
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       echo $websiteObj->deleteWebsite($request,$userId);
   }
   

}
try{
    $websiteCtrlObj = new websiteController();
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "")
       $websiteCtrlObj->$_REQUEST['action']($_REQUEST, $_SESSION);
}
 catch (Exception $e)
 {
     mail("sudhir@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
?>
