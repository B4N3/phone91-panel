<?php
/**
 * @Author sudhir pandey <sudhir@hostnsoft.com>
 * @createdDate 24-09-2013
 * class for manage website 
 */

include dirname(dirname(__FILE__)).'/config.php';
include_once(CLASS_DIR."/db_class.php");


class websiteClass {
   
    
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 24-09-2013
   #function use to add manage website company name and domain name  
   function addManageWebsite($parm,$userId){
       
       #db class obj for connect mongodb
       $dbobj = new db_class();
       
       #collection name 
       $collectionName = '91_manageWebsite';
       
       #check for company name is velide or not 
       if(!preg_match("/^[a-zA-Z0-9._@-\s]+$/", $parm['companyName'])){
            return json_encode(array("status" => "error", "msg" => "company name is not velide !"));
       }
       
       #check for domain name is velide or not 
       if(!preg_match("/^[a-zA-Z0-9.]+$/", $parm['domainName'])){
            return json_encode(array("status" => "error", "msg" => "domain name is not velide !"));
       }
        
       #check for Theme name is velide or not 
       if(!preg_match("/^[a-zA-Z0-9._@-\s]+$/", $parm['theme'])){
            return json_encode(array("status" => "error", "msg" => "Theme name is not velide !"));
       }
       
       #check for language is velide or not 
       if(!preg_match("/^[a-zA-Z]+$/", $parm['language'])){
            return json_encode(array("status" => "error", "msg" => "language is not velide !"));
       }
        

       #check for domain name is already inserted in table
       $condition = array('domainName' => $parm['domainName']);
       $result = $dbobj->mongo_count($collectionName, $condition);

       if($result > 0){
           return json_encode(array("status" => "error", "msg" => "domain name already exist !"));
       }
       
       #data for save company name and domain name 
       $data = array("companyName"=>$parm['companyName'],"domainName"=>$parm['domainName'],"resellerId"=>$userId,"theme"=>$parm['theme'],"language"=>$parm['language']);
       
       
       #create directory for domain data 
       $result = mkdir('../manageWebsiteImage/'.$parm['domainName'], 0777);
        if ($result == 1) {
            
            # if directory created then add main file in same directory
            $fp = fopen('../manageWebsiteImage/'.$parm['domainName'].'/main.js', 'w');
            fwrite($fp, json_encode($data));
            fclose($fp);
        
            
        } else {
            return json_encode(array("status" => "error", "msg" => "domain folder not created !"));
       }
       
       $result = $dbobj->mongo_insert($collectionName, $data);
       if($result){
           return json_encode(array("status" => "success", "msg" => "successfully domain name save !"));
       }else
           return json_encode(array("status" => "error", "msg" => "domain name not added !"));
       
       
   }
   
   #created by sudhir pandey (sudhir@hostnsoft.com)
   #creation date 24-09-2013
   #function use to get all company name and domain name 
   function getManageWebsite(){
       
       #db class obj for connect mongodb
       $dbobj = new db_class();
       
       #collection name 
       $collectionName = '91_manageWebsite';
           
       $allWebsite = array();
       
       $result = $dbobj->mongo_find($collectionName);
       foreach ($result as $res) {
           $data['id'] = $res['_id']->{'$id'};
           $data['companyName'] = $res['companyName'];
           $data['domainName'] = $res['domainName'];
           $data['theme'] = $res['theme'];
           $data['language'] = $res['language'];
           $allWebsite[] = $data;
        }
        
        return json_encode($allWebsite);
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 28/09/2013
   #function use to add general data of manage website 
   function addGeneralData($param,$userId){
       
       
    #upload logo image file    
    $status =  $this->uploadLogoImg($param);
   
    if($status == "error3"){
        $status = $param['logoimg'];
    }
    
    if($status == "error1"){
        return json_encode(array("status" => "error", "msg" => "file not uploaded !"));
    }
    #check file already exist 
    if($status == "error2"){
        return json_encode(array("status" => "error", "msg" => "selected file already exist !"));
    }
    
    
    #check permission for update managewebsite or not 
    $resellerId = $this->getResellerId($param['domainId']);
    if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for update Manage Website !"));
    }
    
    #update data array
    $updateData = array("logoImage"=>$status,"socialLinks"=>array("facebook"=>$param['facebook'],"twitter"=>$param['twitter'],"linkedin"=>$param['linkedin'],"gplus"=>$param['gplus']),"contact"=>array("address"=>$param['address'],"phoneNo"=>$param['phoneNo'],"email"=>$param['emailId']));

    #save general page Data ( json formate ) into generalData.js file  
    $fp = fopen('../manageWebsiteImage/'.$param['domainId'].'/generalData.js', 'w');
    fwrite($fp, json_encode($updateData));
    fclose($fp);
    
    return json_encode(array("status" => "success", "msg" => "Successfully Update General Page Data !"));
     
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 02-10-2013
   #upload logo image file and return path name 
   function uploadLogoImg($parm){

    #error3 : if file name is blank    
    if($_FILES["logoFile"]["name"] == ""){
        return "error3";
    }   
       
    #check file upload error   
    if ($_FILES["logoFile"]["error"] > 0)
    {
        return "error1";
    }
    else
    {
    #check file already exist or not   
    if (file_exists("../manageWebsiteImage/".$parm['domainId']."/". $_FILES["logoFile"]["name"]))
      {
        return "error2";
      }
    else
      {
      #move uploaded file to image folder   
      move_uploaded_file($_FILES["logoFile"]["tmp_name"], "../manageWebsiteImage/".$parm['domainId']."/". $_FILES["logoFile"]["name"]);
      return $_FILES["logoFile"]["name"];
      
      }
     }
       
   }
   
   #created by sudhir pandey < sudhir@hostnsoft.com>
   #creation date 02-10-2013
   #function use to get Reseller id by domain id 
   function getResellerId($domainId){
       
       #db class obj for connect mongodb
       $dbobj = new db_class();
       
       #collection name 
       $collectionName = '91_manageWebsite';
           
       $condition = array("domainName"=> $domainId);
       
       $result = $dbobj->mongo_find($collectionName,$condition);
       foreach ($result as $res) {
           $resellerId = $res['resellerId'];
        }
        return $resellerId ; 
        
       
   }
   
   #created by sudhir pandey < sudhir@hostnsoft.com>
   #creation date 02-10-2013
   #function use to get general Data of any website  
   function getGeneralData($domainId,$userId){
       
       
       #check permission for show managewebsite general data or not  
       $resellerId = $this->getResellerId($domainId);
       if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for show Manage Website Data !"));
       }
       
       #get general page json data from generalData.js File        
       $string = $this->getFileData($domainId.'/generalData.js');
       $result = json_decode($string,true);
      
               
           $data['logoimage'] = $result['logoImage'];
           $data['socialLinks'] = $result['socialLinks']; 
           $data['contact'] = $result['contact'];
           
       #get home page json data from home.js file     
       $string = $this->getFileData($domainId.'/home.js');
       $result = json_decode($string,true); 
           
           $data['homeKeyword'] = $result['homeKeyword'];
           $data['homeDescription'] = $result['homeDescription'];
           $data['welcomeImage'] = $result['welcomeImage'];
           $data['welcomeContent'] = $result['welcomeContent'];
           
        
       
        return json_encode($data);
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 02/10/2013
   #function use to add Home page Data in manage website 
   function addHomeData($param,$userId){
       
    $imgName = $_FILES["welcomeImg"]["name"];
       
    #check welcome image name is blank    
    if($_FILES["welcomeImg"]["name"] == ""){
       $imgName = $param['welImage'];
    }else{   
       
    #check file upload error   
    if ($_FILES["welcomeImg"]["error"] > 0)
    {
        return json_encode(array("status" => "error", "msg" => "file not uploaded !"));
    }
    
    #check file already exist or not   
    if (file_exists("../manageWebsiteImage/".$param['domainId']."/". $_FILES["welcomeImg"]["name"]))
    {
        return json_encode(array("status" => "error", "msg" => "selected file already exist !"));
    }
    
      
    #move uploaded file to image folder   
    move_uploaded_file($_FILES["welcomeImg"]["tmp_name"], "../manageWebsiteImage/".$param['domainId']."/". $_FILES["welcomeImg"]["name"]);
    
    }
    
       
    #check permission for update managewebsite or not 
    $resellerId = $this->getResellerId($param['domainId']);
    if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for update manage Website !"));
    }
    
    #update data array
    $updateData = array("homeKeyword"=>$param['homeKeyword'],"homeDescription"=>$param['homeDescription'],"welcomeImage"=>$imgName,"welcomeContent"=>$param['welcomeContent']);
    
    #save home page data into home.js file in json formate 
    $fp = fopen('../manageWebsiteImage/'.$param['domainId'].'/home.js', 'w');
    fwrite($fp, json_encode($updateData));
    fclose($fp);
    
   
    return json_encode(array("status" => "success", "msg" => "Successfully Update General Page Data !"));
   
    
    
     
       
       
       
   }
   
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 02/10/2013
   #function use to add Home page Data in manage website 
   function addAboutData($param,$userId){
       
       
    #check permission for update managewebsite or not 
    $resellerId = $this->getResellerId($param['domainId']);
    if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for update manage Website !"));
    }
    

    #banner status 1 if check checkbox otherwise 0
    if(isset($param['bannerStatus'])){
        $bannerStatus = 1;
    }else
        $bannerStatus = 0;
    
    #update data array
    $updateData = array('domainId' => new mongoId($param['domainId']),"aboutKeyword"=>$param['aboutKeyword'],"aboutDescription"=>$param['aboutDescription'],"bannerStatus"=>$bannerStatus,"bannerDetail"=>array("bannerHeading"=>$param['bannerHeading'],"bannerSubHead"=>$param['bannerSubHead'],"buttonText"=>$param['buttonText'],"buttonLink"=>$param['buttonLink']),"whoUR"=>$param['whoUR'],"vision"=>$param['vision'],"mission"=>$param['mission']);
    
    $fp = fopen('../manageWebsiteImage/'.$param['domainId'].'/about.js', 'w');
    fwrite($fp, json_encode($updateData));
    fclose($fp);
    
    return json_encode(array("status" => "success", "msg" => "Successfully Update About Page Data !"));
           
       
   }
   
   #created by sudhir pandey < sudhir@hostnsoft.com>
   #creation date 03-10-2013
   #function use to get About page Data of any website  
   function getAboutData($domainId,$userId){
       
       
       #check permission for show managewebsite general data or not  
       $resellerId = $this->getResellerId($domainId);
       if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for show Manage Website Data !"));
       }
       
      
       # get about page data form about.js file
       $string = $this->getFileData($domainId.'/about.js');
       $res = json_decode($string,true); 
       
       
           $data['aboutKeyword'] = $res['aboutKeyword'];
           $data['aboutDescription'] = $res['aboutDescription']; 
           $data['bannerDetail'] = $res['bannerDetail'];
           $data['mission'] = $res['mission'];
           $data['vision'] = $res['vision'];
           $data['whoUR'] = $res['whoUR'];
           $data['bannerStatus'] = $res['bannerStatus'];
           
       
        
        
        return json_encode($data);
       
   }
   
   
   #created by sudhir pandey < sudhir@hostnsoft.com>
   #creation date 03-10-2013
   #function use to add contact page Data   
   function addContacPageData($param,$userId){
       
    #check permission for update managewebsite or not 
    $resellerId = $this->getResellerId($param['domainId']);
    if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for update manage Website !"));
    }
    

    if(isset($param['cntBnrStatus'])) $cntBnrStatus = 1; else $cntBnrStatus = 0;
    if(isset($param['contactFormStatus'])) $contactFormStatus = 1; else $contactFormStatus = 0;
    if(isset($param['mapLocationStatus'])) $mapLocationStatus = 1; else $mapLocationStatus = 0;
    
       
    #update data array
    $updateData = array('domainId' => new mongoId($param['domainId']),"contactKeyword"=>$param['contactKeyword'],"contactDescription"=>$param['contactDescription'],"cntbannerStatus"=>$cntBnrStatus,"cntbannerDetail"=>array("contactHeading"=>$param['contactHeading'],"contactSubHeading"=>$param['contactSubHeading'],"contactBtnText"=>$param['contactBtnText'],"contactBtnLink"=>$param['contactBtnLink']),"contactFormStatus"=>$contactFormStatus,"contactFormEmail"=>$param['contactFormEmail'],"mapLocationStatus"=>$mapLocationStatus,"gMapEmbededCode"=>$param['gMapEmbededCode']);
    
    $fp = fopen('../manageWebsiteImage/'.$param['domainId'].'/contact.js', 'w');
    fwrite($fp, json_encode($updateData));
    fclose($fp);
    
   
    return json_encode(array("status" => "success", "msg" => "Successfully Update contact Page Data !"));
    
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 03/10/2013
   #funtion use to get contact page data 
   function getContactPageData($domainId,$userId){
       
      
       
       #check permission for show managewebsite general data or not  
       $resellerId = $this->getResellerId($domainId);
       if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for show Manage Website Data !"));
       }
       
       
       #get contact page data from contact.js file
       $string = $this->getFileData($domainId.'/contact.js');
       $res = json_decode($string,true); 
       
       
      
           $data['contactKeyword'] = $res['contactKeyword'];
           $data['contactDescription'] = $res['contactDescription']; 
           $data['cntbannerStatus'] = $res['cntbannerStatus'];
           $data['cntbannerDetail'] = $res['cntbannerDetail'];
           $data['contactFormStatus'] = $res['contactFormStatus'];
           $data['contactFormEmail'] = $res['contactFormEmail'];
           $data['mapLocationStatus'] = $res['mapLocationStatus'];
           $data['gMapEmbededCode'] = $res['gMapEmbededCode'];
           
       
        
        return json_encode($data);
       
   }
   
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 04/10/2013
   #function use to add pricing data 
   function addPricingData($param,$userId){
       
    #check permission for update managewebsite or not 
    $resellerId = $this->getResellerId($param['domainId']);
    if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for update manage Website !"));
    }
    
    # array for total no of bank detail 
    $bankName = $param['bankName'];
    $ifsc = $param['ifsc'];
    $accountNo = $param['accountNo'];
    $accountName = $param['accountName'];
    
    
    #update bank detail 
    for ($i = 0; $i < count($bankName); $i++) {
              
                #update Bank detail 
                $data = array("slNo" => new mongoId(), "BankName" => $bankName[$i], "ifsc" => $ifsc[$i], "accountNo" => $accountNo[$i],"accountName"=>$accountName[$i]); 

                $detail[] = $data; 
               
     }
     
    #update data array
    $updateData = array('domainId' => new mongoId($param['domainId']),"pricingKeyword"=>$param['pricingKeyword'],"pricingDescription"=>$param['pricingDescription'],"tariffPlan"=>$param['tariffPlan'],"bankDetail"=>$detail);
    
    $fp = fopen('../manageWebsiteImage/'.$param['domainId'].'/pricing.js', 'w');
    fwrite($fp, json_encode($updateData));
    fclose($fp); 
    
   
         return json_encode(array("status" => "success", "msg" => "Successfully Update Pricing Page Data !"));
   
       
        
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 04/10/2013
   #function use to get princing page data 
   function getPricingPageData($domainId,$userId){
       
       #check permission for show managewebsite pricing page data or not  
       $resellerId = $this->getResellerId($domainId);
       if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for show Manage Website Data !"));
       }
       
       
       #get pricing page data from pricing.js file 
       $string = $this->getFileData($domainId.'/pricing.js');
       $res = json_decode($string,true); 
       
       
           $data['pricingKeyword'] = $res['pricingKeyword'];
           $data['pricingDescription'] = $res['pricingDescription']; 
           $data['tariffPlan'] = $res['tariffPlan'];
           $data['bankDetail'] = $res['bankDetail'];
           
       
        return json_encode($data);
       
   }
   
   #created by sudhir pandey <sudhir@hostnsoft.com>
   #creation date 04/10/2013
   #function use to delete website 
   function deleteWebsite($param,$userId){
       
       #check permission for show managewebsite pricing page data or not  
       $resellerId = $this->getResellerId($param['domainId']);
       if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for Delete Website Data !"));
       }
       
       #db class obj for connect mongodb
       $dbobj = new db_class();
        $condition = array("domainName"=> $param['domainId']);
       
       
       
       $collectionName = '91_manageWebsite';
       $status=$dbobj->mongo_delete($collectionName,$condition);
       
       #directory name 
       $dir ='../manageWebsiteImage/'.$param['domainId'];
       
       #remove directory of Domain name 
       $this->removeDir($dir);
       
       $allWebsite = $this->getManageWebsite();
       $allDomain = json_decode($allWebsite,TRUE);
       
       
       if($status){
           return json_encode(array("status" => "success", "msg" => "Successfully Deleted Website Detail!","allDomain"=>$allDomain));
       }  else {
           return json_encode(array("status" => "error", "msg" => "Website not deleted .. "));
       }
       
       
       
   }
   
   function getFileData($fileName) {
    $url = '192.168.1.174/manageWebsiteImage/'.$fileName;
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}


// When the directory is not empty:
 function removeDir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
 }
   
   
}
?>
