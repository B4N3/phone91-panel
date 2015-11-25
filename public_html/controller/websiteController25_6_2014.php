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

       if($session['captcha'] != $request['identificationNumber'])
            return false;
       
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       return $websiteObj->addManageWebsite($request,$userId);
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 28/09/2013
   #function use to add general detail 
   function addGeneralData($request, $session){
         
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       
//       /* not user name regex is used for file name*/
//       $param["logoImage"] = array(NOTUSERNAME_REGX,$_FILES['file']['name'],1);
//       $param["logoImage"] = array(NOTUSERNAME_REGX,$_FILES['file']['name'],1);
       
//       echo $websiteObj->addGeneralData($request,$userId);
       return $websiteObj->addData($request,$userId,"generalData","logo");
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 02/10/2013
   #function use to add Home page detail 
   function addHomeData($request, $session){
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
//       echo $websiteObj->addHomeData($request,$userId);
       return $websiteObj->addData($request,$userId,"home","welcomeImage");
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 03/10/2013
   #function use to add about page detail 
   function addAboutData($request, $session){
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
//       echo $websiteObj->addAboutData($request,$userId);
       return $websiteObj->addData($request,$userId,"about","aboutImg");
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 03/10/2013
   #function use to add contact page data 
   function addContacPageData($request, $session){
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
//       echo $websiteObj->addContacPageData($request,$userId);
       return $websiteObj->addData($request,$userId,"contact");
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 04/10/2013
   #function use to add pricing page data 
   function addPricingData($request, $session){
       
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       return $websiteObj->addData($request,$userId,"pricing","pricingImg");
       
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 04/10/2013
   #function use to delete website 
   function deleteWebsite($request, $session){
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       return $websiteObj->deleteWebsite($request,$userId);
   }
   
   /**
    * @author sameer rathod <sameer@hostnsoft.com>
    * @param type $request
    * @param type $session
    */
   public function deleteResellerTariff($request, $session) {
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       return $websiteObj->deleteResellerDefaultTariff($request,$userId);
   }
   
   /**
    * @author sameer rathod <sameer@hostnsoft.com>
    * @param type $request
    * @param type $session
    */
   public function updateResellerTariff($request, $session) {
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $userId = $session['id'];
       return $websiteObj->updateResellerDefaultTariff($request,$userId);
   }
   
   /**
    * @author sameer rathod <sameer@hostnsoft.com>
    * @param type $request
    * @param type $session
    */
   public function updateDomainDetails($request, $session) {
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $resellerId = $session['id'];
       return $websiteObj->updateDomainDetails($request,$resellerId);
   }
   
   /**
    * @author sameer rathod <sameer@hostnsoft.com>
    * @param type $request
    * @param type $session
    */
   public function updateThemeDetails($request, $session) {
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $resellerId = $session['id'];
       return $websiteObj->updateTheme($request,$resellerId);
   }
   
   /**
    * @author sameer rathod <sameer@hostnsoft.com>
    * @param type $request
    * @param type $session
    */
   public function getDomainList($request, $session) {
       include_once(CLASS_DIR . "websiteClass.php");
       $websiteObj = new websiteClass();
       $resellerId = $session['id'];
       $res = $websiteObj->getManageWebsite($resellerId);
       
       return $res;
   }
   
   

}
try{
    $websiteCtrlObj = new websiteController();    
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "")
       echo $websiteCtrlObj->$_REQUEST['action']($_REQUEST, $_SESSION);
}
 catch (Exception $e)
 {
     mail("sudhir@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
?>
