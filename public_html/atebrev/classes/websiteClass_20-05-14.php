<?php
/**
 * @Author sudhir pandey <sudhir@hostnsoft.com>
 * @createdDate 24-09-2013
 * @modified by sameer rathod
 * class for manage website 
 */

include_once dirname(dirname(__FILE__)).'/config.php';
//echo CLASS_DIR;

//require_once(CLASS_DIR."/db_class.php");
//die("132");

class websiteClass {
   
    /**
     * @uses $userId Description class varible for storing user id 
     * @var type
     */
    protected $userId;
    
    /**
     * @uses $companyName Description class varible for storing name of the company
     * @var type
     */
    protected $companyName;
    
    /**
     * @uses $domainName Description class varible for storing domain name pointed to the website
     * @var type
     */
    protected $domainName;
    
    /**
     * @uses $theme Description class varible for storing name of the theme selected fot the website
     * @var type
     */
    protected $theme;
    
    /**
     * @uses $language Description used for storing default language of the website during add wesite process 
     * @var type
     */
    protected $language;
    
    /**
     * @uses $resellerId Description used for storing the reseller id 
     * @var type
     */
    protected $resellerId;
    
    /**
     * @uses $fileName Description used for storing the file name of the file which is uploaded 
     * @var type
     */
    protected $fileName;
    
    /**
     * @uses $dbFileName Description class variable used for storing file name along with whole path 
     * @var type
     */
    protected $dbFileName;
    
    /**
     * @uses $domainFolder Description class variable for storing name of the folder in which file is stored 
     * @var type
     */
    protected $domainFolder = 0;
    
    /**
     * @uses $paramError Description class variable for storing the erroe message to be returned during validation if in case error occur
     * @var type
     */
    protected $paramError;
    
    /**
     * @uses $validateFlag Description class variable for storing a flag bit of validation by default it is set to false 
     *       and if it is true that means the validation function is called and there is no error
     * @var type
     */
    protected $validateFlag = false;
    
    

    /**
     * @author sameer rathod <sameer@hostnsoft.com>
     * @param type $domain
     * @uses setDomainFolder Description used for replacing and .present in the folder name with _
     */
    public function setDomainFolder($domain) {
        $this->domainFolder = str_replace(".", "_", $domain); 
    }
    
    
  /**
   *@author sudhir pandey <sudhir@hostnsoft.com>
   *@since version 1.0 date 24-09-2013
   *@modified  by SAMEER
   *@since version 1.1 date 28-12-2013
   *@uses Descriptionfunction use to add manage website company name and domain name  
   */
   function addManageWebsite($parm,$userId){
       
       $funobj = new fun();

       /**
        * Initilize the variable here 
        */
        $this->companyName = trim($parm['companyName']);
        $this->domainName = trim($parm['domainName']);
        $this->theme = trim($parm['theme']);
        $this->language = trim($parm['language']);
        $this->resellerId = trim($userId);
       
        
       #check for company name is valid or not 
       if(preg_match("/[^a-zA-Z0-9\.\_\@\-\s]+/", $this->companyName)){
            return json_encode(array("status" => "error", "msg" => "company name is not valid !"));
       }
       
       #check for domain name is valid or not 
       if(preg_match("/[^a-zA-Z0-9\.]+/", $this->domainName) || strlen($this->domainName) < 5){
            return json_encode(array("status" => "error", "msg" => "domain name is not valid !"));
       }
        
       #check for Theme name is valid or not 
       if(preg_match("/[^a-zA-Z0-9\.\_\@\-\s]+/", $this->theme)){
            return json_encode(array("status" => "error", "msg" => "Theme name is not valid !"));
       }
       
       #check for language is valid or not 
       if(preg_match("/[^a-zA-Z]+/", $this->language)){
            return json_encode(array("status" => "error", "msg" => "language is not valid !"));
       }
       
       #count the number of tariff plan in the array
       $tariffIdCnt = count($parm['tariffPlan']);
       
       #count the number of unique tariff plan in the array
       $tariffIdCntAfterUnique = count(array_unique($parm['tariffPlan']));
       
       #count the number of currencyid in the array
       $currencyCnt = count($parm['currency']);
       
       #count the number of unique currencyid  in the array
       $currencyCntAfterUnique = count(array_unique($parm['currency']));
       
       #validate if the currency or tariff id is redundant 
       if($tariffIdCnt != $tariffIdCntAfterUnique || $currencyCnt != $currencyCntAfterUnique)
       {
           return json_encode(array("status" => "error", "msg" => "Only one tariff plan per currency is allowed!")); 
       }
       
       
       #loop through the param for validating the tariff please and currency with balance 
        for($i=0;$i < $tariffIdCnt;$i++)
        {
            #validations 
            if(preg_match("/[^0-9]+/", $parm['tariffPlan'][$i]) || preg_match("/[^0-9]+/", $parm['currency'][$i]) || preg_match("/[^0-9\.]+/", $parm['balance'][$i]) || strlen($parm['balance'][$i]) > 10){
                return json_encode(array("status" => "error", "msg" => "Invalid Input provided please fill valid input!"));
            }
            
            #store the values in a array for further usage 
            $valuesArr[] = array($this->resellerId,$parm['tariffPlan'][$i],$parm['currency'][$i],$parm['balance'][$i]);
        }
        
       
       

       #check for domain name is already inserted in table
       $result = $funobj->selectData("domainName","91_domainDetails","domainName = '".$this->domainName."'");

       if(!$result)
       {
           return json_encode(array("status" => "error", "msg" => "Error in domain check please try again !"));
       }
       elseif($result->num_rows > 0){
           return json_encode(array("status" => "error", "msg" => "domain name already exist !"));
       }
       
       #data for save company name and domain name 
       $data = array("companyName"=>$this->companyName,"domainName"=>$this->domainName,"resellerId"=>$this->resellerId,"language"=>$this->language);
       
       #clean the domain name the folder to store the files for the concern domain is same as the domain name 
       $domainFolder = str_replace(".","_",$this->domainName);
               
       #check if file exist
       if(!file_exists('../manageWebsiteImage/'.$domainFolder))
       {
            #create directory for domain data 
            $dirResult = mkdir('../manageWebsiteImage/'.$domainFolder."/", 0777,true);
            
       }
       else
       {
           #set the dir result =1 as the directory already exisit 
           $dirResult = 1;
       }

       #if directory exist then write the file else error 
        if ($dirResult == 1) {
            
            # if directory created then add main file in same directory
            $fp = fopen('manageWebsiteImage/'.$domainFolder.'/main.json', 'w');
            fwrite($fp, json_encode($data));
            fclose($fp);
            
        } else {
            return json_encode(array("status" => "error", "msg" => "domain folder not created !"));
       }
       
       #insert the data into domain details table 
       $result = $funobj->insertData($data,"91_domainDetails");
//       echo $funobj->querry;
       
       if(!$result || $funobj->db->affected_rows < 1){
           return json_encode(array("status" => "error", "msg" => "Error inserting domian details please try again later!"));
       }
       
       #last insert id which is domain id and insert with reseller default currency
       $lastInsertId = $funobj->db->insert_id;
       
       #append the last insert id which is the promary key of the domain details table to the valueArr ie parameter array which we ahd created before
       foreach($valuesArr as $val)
       {
           #this is used to insert the id to the array 
            array_unshift($val, $lastInsertId);
            
            #prepare a string with comma seprated value and store it in array 
            $resultValArr[] = implode("','",$val);
       }
       
       #prepare the string for values section of the sql query 
       $values = "('";
       $values .= implode("'),('",$resultValArr);
       $values .= "')";
       
       #sql querry for reseller default currency table 
       $queryDefaultCurrency = "INSERT INTO 91_resellerDefaultCurrency (domainId,resellerId,tariffId,currencyId,balance) values ".$values." ";
       
       #insert the query to the db 
       $defaultCurrResult = $funobj->db->query($queryDefaultCurrency);

       if($defaultCurrResult && $funobj->db->affected_rows > 0){
           return json_encode(array("status" => "success", "msg" => "successfully added domain please edit the domain for further details!","id"=>$lastInsertId));
       }
       else
       {   
           #In case of error delete the domain details as it is saved before it has to be deleted 
           $condition = " id = ".$lastInsertId."";
           $delRes = $funobj->deleteData("91_domainDetails", $condition);
           
           if(!$delRes || $funobj->db->affected_rows < 1)
               return json_encode(array("status" => "error", "msg" => "Error in domain addition please contact provider else it wont function properly!"));
           
           return json_encode(array("status" => "error", "msg" => "Error adding domain please try again later!"));
       }
       
       
   }
   
   
   /**
    * @author sameer rathod 
    * @param type $param
    * @param type $resellerId
    * @return type
    */    
   public function updateDomainDetails($param,$resellerId) {
       
       /*
        * initilize the variables 
        */
       $companyName = trim($param['companyName']);
       $domainName = trim($param['domainName']);
       $domainId = trim($param['domainId']);
       
       #validations on the request parameters 
       if(preg_match(NOTNUM_REGX, $resellerId) || $resellerId == "")
       {
           return json_encode(array("msg"=>"Error invalid user please login","status"=>"error"));
       }
       
       if(preg_match(NOTNUM_REGX, $domainId) || $domainId == "")
       {
           return json_encode(array("msg"=>"Error invalid domain please select a valid domain","status"=>"error"));
       }
       
       if(preg_match(NOTALPHABATESPACE_REGX, $companyName) || $companyName == "")
       {
           return json_encode(array("msg"=>"Error invalid company name please try with a valid name ","status"=>"error"));
       }
       
       if(preg_match('/[^0-9a-zA-Z\.\_\-]+/', $domainName) || $domainName == "")
       {
           return json_encode(array("msg"=>"Error invalid domian name please insert a valid domian name","status"=>"error"));
       }
       
       
       $funobj = new fun();
       
       #data to be updated 
       $data = array("companyName"=>$companyName,"domainName"=>$domainName);
       
       $table = "91_domainDetails";
       
       #condition for updation
       $condition = " id=".$domainId." and resellerId=".$resellerId." ";
       
       $selData = $funobj->selectData("domainName",$table,$condition);
       if($selData)
       {
           $row = $selData->fetch_array(MYSQLI_ASSOC);
           $currenctDomainName = $row['domainName'];
       }
       else
       {
           return json_encode(array("msg"=>"Unable to find the details","status"=>"error"));
       }
       
       
       #update data here 
       $updateResult = $funobj->updateData($data, $table,$condition);
       $affectedRows = $funobj->db->affected_rows;
       
       #check if data is updated properly or not 
       if($updateResult)
       {
           if($affectedRows > 0)
           {
               $currenctDomainName = str_replace(".", "_", $currenctDomainName);
               $domainName = str_replace(".", "_", $domainName);
            if(file_exists(ROOT_DIR."/manageWebsiteImage/".$currenctDomainName))
            {
                rename(ROOT_DIR."/manageWebsiteImage/".$currenctDomainName, ROOT_DIR."/manageWebsiteImage/".$domainName);
            }
            return json_encode(array("msg"=>"successfully updated the records","status"=>"success"));
           }
           else
            return json_encode(array("msg"=>"Nothing to update","status"=>"error"));
       }
       else
           
           return json_encode(array("msg"=>"Error updating record pelase try again later","status"=>"error"));
   }
   
   /**
    * @author sameer rathod 
    * @param type $id
    * @return int
    * @uses function getThemeName  Description is user to get he name of the theme according to the id given to it 
    */
   public function getThemeName($id) {
       #validate the id to number 
        if(preg_match(NOTNUM_REGX, $id) || $id == "")
            return 0;
        $funobj = new fun();
        
        #select the data from themeDetails table where id == to the id provided
        $selRes = $funobj->selectData("themeName", "91_themeDetails","id=".$id);
        #render row 
        $row = $selRes->fetch_array(MYSQLI_ASSOC);
        #return only theme name
        return $row['themeName'];
   }
   
   /**
    * @author sameer rathod 
    * @param type $param
    * @param type $resellerId
    * @return type
    * @uses used to update the theme selected by the user
    */
   public function updateTheme($param,$resellerId) {
       
       #initialize the variables 
       $themeId = trim($param['themeId']);
       $domainName = trim($param['domainName']);
       
       #validate the parameters 
       if(preg_match(NOTNUM_REGX, $resellerId) || $resellerId == "")
            return json_encode(array("msg"=>"Error invalid user please login","status"=>"error"));
       if(preg_match('/[^a-zA-Z0-9\.\_\-]/', $domainName) || $domainName == "")
            return json_encode(array("msg"=>"Error invalid domain please select a domain name first","status"=>"error"));
       
       if(preg_match(NOTNUM_REGX, $themeId) || $themeId == "")
            return json_encode(array("msg"=>"Error invalid theme pelease select a valid theme ","status"=>"error"));
       
       $funobj = new fun();
       
       #fetch the name of the theme 
       $themeName = $this->getThemeName($themeId);
       
       if(!$themeName)
            return json_encode(array("msg"=>"Error invalid theme pelease select a valid theme ","status"=>"error"));
       
       #set variables for updating the domain details table 
       $data = array("theme"=>$themeName);
       $table = "91_domainDetails";
       $condition = " resellerId=".$resellerId." and domainName='".$domainName."'";
       
       #update data domain details 
       $updateResult = $funobj->updateData($data, $table,$condition);

        #check if data is updated properly or not
       if($updateResult && $funobj->db->affected_rows > 0)
           return json_encode(array("msg"=>"successfully updated the records","status"=>"success"));
       else
           return json_encode(array("msg"=>"Error updating record pelase try again later","status"=>"error"));
       
       
   }
   
   /**
    * @author sameer rathod <sameer@hostnsoft.com>
    * @param type $parm
    * @param type $userId
    * @return type
    * @uses used to update the default reseller plan  
    */
   public function updateResellerDefaultTariff($parm,$userId) {
       $funobj = new fun();
       
       if(preg_match(NOTNUM_REGX, $userId) || $userId == "")
          return json_encode(array("status" => "error", "msg" => "Invalid User please login again "));      
       
       if(preg_match('/[^a-zA-Z0-9\.\_\-]/', $parm['domainName']) || $parm['domainName'] == "" || strlen($parm['domainName']) < 5 )
          return json_encode(array("status" => "error", "msg" => "Invalid domain name please select a domain "));      
       
       #count the number of tariff plan in the array
       $tariffIdCnt = count($parm['tariffPlan']);
       
       #count the number of unique tariff plan in the array
       $tariffIdCntAfterUnique = count(array_unique($parm['tariffPlan']));
       
       #count the number of currencyid in the array
       $currencyCnt = count($parm['currency']);
       
       #count the number of unique currencyid  in the array
       $currencyCntAfterUnique = count(array_unique($parm['currency']));
       
       #validate if the currency or tariff id is redundant 
       if($tariffIdCnt != $tariffIdCntAfterUnique || $currencyCnt != $currencyCntAfterUnique)
       {
           return json_encode(array("status" => "error", "msg" => "Only one tariff plan per currency is allowed!")); 
       }
       $this->resellerId = $userId;
       
       #get details of reseller accoriding to domain 
       $res = $funobj->getDomainResellerId($parm['domainName'],2);
       
       #set the domain id of the user 
       $domainId = $res['id'];
       
        #loop through the param for validating the tariff please and currency with balance 
        for($i=0;$i < $tariffIdCnt;$i++)
        {
            #validations 
            if(preg_match("/[^0-9]+/", $parm['tariffPlan'][$i]) || preg_match("/[^0-9]+/", $parm['currency'][$i]) || preg_match("/[^0-9\.]+/", $parm['balance'][$i]) || strlen($parm['balance'][$i]) > 10){
                return json_encode(array("status" => "error", "msg" => "Invalid Input provided please fill valid input!"));
            }
            
            #store the values in a array for further usage 
            $valuesArr[] = "".$domainId."','".$this->resellerId."','".$parm['tariffPlan'][$i]."','".$parm['currency'][$i]."','".$parm['balance'][$i]."";
        }
        
        
        #prepare the string for values section of the sql query 
       $values = "('";
       $values .= implode("'),('",$valuesArr);
       $values .= "')";
        
        #sql querry for reseller default currency table 
       $queryDefaultCurrency = "INSERT INTO 91_resellerDefaultCurrency (domainId,resellerId,tariffId,currencyId,balance) values ".$values." ";
       $queryDefaultCurrency .= "on duplicate key update balance=VALUES(balance),tariffId=VALUES(tariffId)";


       #insert the query to the db 
       $defaultCurrResult = $funobj->db->query($queryDefaultCurrency);
       if(!$defaultCurrResult)
           return json_encode (array("msg"=>"Error updating record please try again","status"=>"error"));
       else
           return json_encode (array("msg"=>"Successfully updated records","status"=>"success"));
   }
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version 1.0 date 24-09-2013
    * @modified  by SAMEER
    * @param type $resellerId
    * @return type
    * @use function use to get all company name and domain name 
    */
   function getManageWebsite($resellerId){
       
       $funobj = new fun();
       #collection name 
       $collectionName = '91_manageWebsite';
        
       #Initilize array
       $allWebsite = array();
       
       $condition = array("resellerId"=> $resellerId);
       
       #select data from domain details table 
       $result = $funobj->selectData("*","91_domainDetails","resellerId =". $resellerId." limit 20");
       
       if(!$result)
           return json_encode(array("msg"=> "Invalid domain please contact provider","status"=>"error"));
       
       while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
           $allWebsite[] = $row;
        }
        return json_encode($allWebsite);
       
   }
   
   
   
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version1.0 28-09-2013
    * @since version2.1 28-12-2013 by sameer 
    * @param type $param
    * @param type $userId
    * @return type
    */
   function addData($param,$userId,$type,$fileName = NULL){
        
    $funobj = new fun();
    $this->domainName = $param['domainId'];
    
    
    #validate type 
    if(preg_match(NOTALPHABATE_REGX, $type) || $type == "")
            return json_encode(array("status" => "error", "msg" => "Invalid type please contact provoder!"));
    
    #validate user id 
    if(preg_match(NOTNUM_REGX, $userId) || $userId == "")
            return json_encode(array("status" => "error", "msg" => "Invalid user please login!"));
    
   #get the reseller id of the domain
    $resellerId = $funobj->getDomainResellerId($this->domainName);
    
    if($resellerId != $userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for update Manage Website !"));
    }
    
    #clean the doamain name and set it in class variable 
    $this->setDomainFolder($this->domainName);
    
    if(!$this->domainFolder)
        return json_encode(array("status" => "error", "msg" => "Error can't find respective domain location!"));
    
    #set the file name path 
    $this->dbFileName = '../manageWebsiteImage/'.$this->domainFolder.'/'.$type.'.json';
   
    #if file is uploaded by the user
   if(isset($_FILES['file']) && ($_FILES['file']['name'] != "" || !is_null($_FILES['file']['name'])))
   {
       #validate file name which is to be uploaded
       if(preg_match('/[^a-zA-Z0-9\s\.\_\-\@]+/', $fileName) || $fileName == "")
       {
            return json_encode(array("status" => "error", "msg" => "Invalid file name!"));
       }
          
//       var_dump($_FILES['file']['size']);
       if($_FILES['file']['size'] > 200000)
            return json_encode(array("msg"=>"Error file size  more then 200k is not allowed","status"=>"error"));
       
       #upload file to sever 
        $status =  $this->uploadLogoImg($param,$_FILES['file'],$fileName);
        
        if(!$status){
            return json_encode(array("status" => "error", "msg" => "Error uploading file please try again later!"));
        }
   }
   else
   {
       #if file already exist 
       if(file_exists($this->dbFileName))
        {
           #get the data from the file 
            $string = $this->getFileData($this->domainFolder.'/'.$type.'.json');
            $existingData = json_decode($string,true);
            
            $this->fileName = $existingData['logoImage'];
        }
//        else
//            return json_encode(array("status" => "error", "msg" => "Record not found !"));
   }
    
   
   switch($type)
    {
        case "generalData":
        {
            #validate the parameters 
            if(!$this->validateGeneralData($param))
                return $this->paramError;
            
            #render the param into a fromat tobe saved on the file
            $updateData = $this->addGeneralData($param);
                    break;   
        }   
         
        case "home":
        { 
            #validate the parameters 
            if(!$this->validateHomeData($param))
            {
                return $this->paramError;
            }
            #render the param into a fromat tobe saved on the file
            $updateData = $this->addHomeData($param);
                    break;
                }
        case "about":
        {
            #validate the parameters 
            if(!$this->validateAboutData($param))
                return $this->paramError;
            
            #render the param into a fromat tobe saved on the file
            $updateData = $this->addAboutData($param);
            
            break;
        }
        case "contact":
        {
            #validate the parameters 
            if(!$this->validateContactData($param))
                return $this->paramError;
            
            #render the param into a fromat tobe saved on the file
            $updateData = $this->addContacPageData($param);
            break;
        }
        case "pricing":
        {
            #validate the parameters 
            if(!$this->validatePricingData($param))
                return $this->paramError;
            
            #render the param into a fromat tobe saved on the file
            $updateData = $this->addPricingData($param);
            
            if(!$updateData)
                return $this->paramError;
            
            break;
        }
    }
    
    #save general page Data ( json formate ) into generalData.js file  
    $fp = fopen($this->dbFileName, 'w');
    fwrite($fp, json_encode($updateData));
    fclose($fp);
    
    return json_encode(array("status" => "success", "msg" => "Successfully Update General Page Data !"));
     
   }
   
   /**
    * @author sameer rathod <sameer@hostnsoft.com>
    * @param type $param
    * @return int
    */
   public function generalValidations($param) {
       
       /**
        * genral validation for each parameter coming from the request 
        */
       
       if(preg_match(NOTALPHANUMSPACE_REGX, $param['title']) || strlen($param['title']) > 80 )
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid home tile special character not allowed","status"=>"error"));
            return 0;
        }
        
        if(preg_match(NOTALPHANUMCOMMA_REGX, $param['mKeyword']) || strlen($param['mKeyword']) > 300)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid meta keywords","status"=>"error"));
            return 0;
        }
        if(preg_match(NOTALPHANUMCOMMA_REGX, $param['mDescription']) || strlen($param['mDescription']) > 250 )
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid meta description","status"=>"error"));
            return 0;
        }
        if(preg_match(NOTTEXT_REGX, $param['welcomeContent']) || strlen($param['welcomeContent']) > 350)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid Welcome Content","status"=>"error"));
            return 0;
        }
        
        
        if(preg_match(NOTTEXT_REGX, $param['heading']) || strlen($param['heading']) > 250)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid welcome heading value","status"=>"error"));
            return 0;
        }
        
        if(preg_match(NOTTEXT_REGX, $param['subHeading']) || strlen($param['subHeading']) > 250)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid welcome subheading value","status"=>"error"));
            return 0;
        }
        
        if(preg_match(NOTTEXT_REGX, $param['text']) || strlen($param['text']) > 350)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid welcome text value","status"=>"error"));
            return 0;
        }
        
        
        if(($param['link'] != "" && !preg_match(URL_REGX, $param['link'])) || strlen($param['link']) > 80)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid welcome link","status"=>"error"));
            return 0;
        }
        
        $this->validateFlag = true;
        return 1;
   }
   
   /**
    * @author sameer rathod <sameer@hostnsoft.com>
    * @param type $param
    * @return type
    */
    public function validateHomeData($param) {
        return $this->generalValidations($param);
    }
    
    /**
     * @author sameer rathod <sameer@hostnsoft.com>
     * @param type $param
     * @return int
     */
    public function validateGeneralData($param) {
        
        if(($param['facebook'] != "" && !preg_match(URL_REGX, $param['facebook'])) || strlen($param['facebook']) > 80)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid facebook url","status"=>"error"));
            return 0;
        }
        if(($param['twitter'] != "" && !preg_match(URL_REGX, $param['twitter']))|| strlen($param['twitter']) > 80)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid twitter url","status"=>"error"));
            return 0;
        }
        if(($param['linkedin'] != "" && !preg_match(URL_REGX, $param['linkedin'])) || strlen($param['linkedin']) > 80)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid linkedin url","status"=>"error"));
            return 0;
        }
        if(($param['gplus'] != "" && !preg_match(URL_REGX, $param['gplus'])) || strlen($param['gplus']) > 80) 
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid gplus url","status"=>"error"));
            return 0;
        }
        
        if((preg_match(NOTTEXT_REGX, $param['address'])) || strlen($param['address']) > 300)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid address value or length should not be more then 300 character","status"=>"error"));
            return 0;
        }
        
        if(preg_match(NOTPHNNUM_REGX, $param['phoneNo']))
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid phone Number value","status"=>"error"));
            return 0;
        }
        
        if(!preg_match(EMAIL_REGX, $param['emailId']) || strlen($param['emailId']) > 40)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid emailId value","status"=>"error"));
            return 0;
        }
        
        $this->validateFlag = true;
        return 1;
    }
   
    /**
     * @author sameer rathod <sameer@hostnsoft.com>
     * @param type $param
     * @return int
     */
    public function validateAboutData($param) {
        
        
        if(preg_match(NOTTEXT_REGX, $param['whoUR']) || strlen($param['whoUR']) > 300)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid who you are value max 300 character allowed","status"=>"error"));
            return 0;
        }
        if(preg_match(NOTTEXT_REGX, $param['vision']) || strlen($param['whoUR']) > 300)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid vision value max 300 character allowed","status"=>"error"));
            return 0;
        }
        if(preg_match(NOTTEXT_REGX, $param['mission']) || strlen($param['whoUR']) > 300)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid mission value max 300 character allowed","status"=>"error"));
            return 0;
        }
                
        return $this->generalValidations($param);
    }
    
    /**
     * @author sameer rathod <sameer@hostnsoft.com>
     * @param type $param
     * @return int
     */
    public function validateContactData($param) {
        
        
        if(preg_match(NOTUSERNAME_REGX, $param['contactFormEmail']) || strlen($param['contactFormEmail']) > 40)        
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid contact email","status"=>"error"));
            return 0;
        }
        if(preg_match(NOTTEXT_REGX, $param['gMapEmbededCode']) || strlen($param['gMapEmbededCode']) > 250)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid google code","status"=>"error"));
            return 0;
        }
        if(($param['cntBnrStatus'] != 0 &&  $param['cntBnrStatus'] != 1) || ($param['contactFormStatus'] != 0 &&  $param['contactFormStatus'] != 1 ) || ($param['mapLocationStatus'] != 0 &&  $param['mapLocationStatus'] != 1 ))
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid flag","status"=>"error"));
            return 0;
        }
        
        return $this->generalValidations($param);
    }
    
    /**
     * @author sameer rathod <sameer@hostnsoft.com>
     * @param type $param
     * @return type
     */
    public function validatePricingData($param) {
        return $this->generalValidations($param);
    }
   
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version1.1 02-10-2013
    * @since version2.1 28-12-2013 by SAMEER 
    * @param type $parm
    * @param type $file
    * @return int  * 
    */
   function uploadLogoImg($parm,$file,$type){
       
       #validate if file name or domain name is blank then return false
        if ($file["name"] == "" || $this->domainName == "") {
            return 0;
        }
        
        #validate the type parameter
        if(preg_match("/[^a-zA-Z]+/",$type))
                return 0;
        
        #fetch the extension from the file name
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        
        #array of allowed extension
        $extArray = array("jpg",'jpeg','gif','png');
        
        #check if the file extension is allowed or not 
        if(in_array($extension,$extArray) && $file['error'] < 1) 
        {
            $domainFolder = str_replace(".", "_", $this->domainName);
            
            #move uploaded file to image folder   
            if(move_uploaded_file($file["tmp_name"], ROOT_DIR."/manageWebsiteImage/" . $domainFolder . "/".$type.".".$extension ))
            {
                $this->fileName = $type.".".$extension;
                return 1;
            }
            else
                return 0;
        }
        else
            return 0;
    }
   

   

   
   /**
    * @author : sameer rathod
    * @param type $domainId
    * @param type $type
    * @return type
    */
   private function fetchDataFromFile($domainId,$type){
       $funobj = new fun();
       #check permission for show managewebsite general data or not  
       $resellerId = $funobj->getDomainResellerId($domainId);
       if($resellerId != $this->userId){
         return json_encode(array("status" => "error", "msg" => "You have no permission for show Manage Website Data !"));
       }
       
       if(preg_match('/[^a-zA-Z]+/', $type)){
           return json_encode(array("status" => "error", "msg" => "Error Invalid file type please contact provider!"));
       }
           
       #clean the domain name for folder
       $this->setDomainFolder($domainId);
       if(!$this->domainFolder)
        return json_encode(array("status" => "error", "msg" => "Error can't find respective domain location!"));
       
       #get general page json data from generalData.js File        
       $string = $this->getFileData($this->domainFolder.'/'.$type.'.json');
       $result = json_decode($string,true);
       
       return $result;
   }
   
   
   
   
   /**
    * @author sameer rathod
    * @version 1.1 
    * @param type $param
    * @param type $userId
    * @return array
    * @uses function use to add Home page Data in manage website 
    */
   function addGeneralData($param){
       
    #update data array
    $updateData = array("logoImage" => $this->fileName,
                "socialLinks" => array("facebook" => $param['facebook'],
                    "twitter" => $param['twitter'],
                    "linkedin" => $param['linkedin'],
                    "gplus" => $param['gplus']),
                "contact" => array("address" => $param['address'],
                    "phoneNo" => $param['phoneNo'],
                    "email" => $param['emailId'])
            );
    
    return $updateData;
       
   }
   /**
    * @author sameer rathod 
    * @version 1.1
    * @param type $param
    * @param type $userId
    * @return array
    * @uses function use to add Home page Data in manage website 
    */
   function addHomeData($param){
       
    $updateData = array("welcomeImage" => $this->fileName,
                        "mKeyword" => $param['mKeyword'],
                        "mDescription" => $param['mDescription'],
                        "welcomeContent" => $param['welcomeContent'],
                        "title" => $param['title'],
                        "heading" => $param['heading'],
                        "subHeading" => $param['subHeading'],
                        "text" => $param['text'],
                        "link" => $param['link']);    
    return $updateData;
       
   }
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version 1.1  02/10/2013
    * @version 1.2 by Sameer Rathod 
    * @param type $param
    * @param type $userId
    * @return array
    * @uses function use to add Home page Data in manage website 
    */
   function addAboutData($param){
       
    #banner status 1 if check checkbox otherwise 0
    if(isset($param['bannerStatus'])){
        $bannerStatus = 1;
    }else
        $bannerStatus = 0;
    
    
    
    $updateData = array('domainId' => $param['domainId'],
                        "welcomeImage" => $this->fileName,
                        "title"=>$param['title'],
                        "mKeyword"=>$param['mKeyword'],
                        "mDescription"=>$param['mDescription'],
                        "bannerStatus"=>$bannerStatus,
                        "bannerDetail"=>array("heading"=>$param['heading'],
                                              "subHeading"=>$param['subHeading'],
                                              "text"=>$param['text'],
                                              "link"=>$param['link']),
                        "whoUR"=>$param['whoUR'],
                        "vision"=>$param['vision'],
                        "mission"=>$param['mission']);
    
    return $updateData;
       
   }
   
   
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version 1.1  03/10/2013
    * @version 1.2 by Sameer Rathod 
    * @param type $param
    * @param type $userId
    * @return int
    * @uses function use to add contact page Data   
    */ 
   function addContacPageData($param){
       
    if(isset($param['cntBnrStatus'])) $cntBnrStatus = 1; else $cntBnrStatus = 0;
    if(isset($param['contactFormStatus'])) $contactFormStatus = 1; else $contactFormStatus = 0;
    if(isset($param['mapLocationStatus'])) $mapLocationStatus = 1; else $mapLocationStatus = 0;
    
       
    #update data array
    $updateData = array('domainId' => $param['domainId'],
            "title" => $param['title'],
            "mKeyword" => $param['mKeyword'],
            "mDescription" => $param['mDescription'],
            "cntbannerStatus" => $cntBnrStatus,
            "cntbannerDetail" => array("heading" => $param['heading'],
                "subHeading" => $param['subHeading'],
                "text" => $param['text'],
                "link" => $param['link']),
            "contactFormStatus" => $contactFormStatus,
            "contactFormEmail" => $param['contactFormEmail'],
            "mapLocationStatus" => $mapLocationStatus,
            "gMapEmbededCode" => $param['gMapEmbededCode']);

        return $updateData;

   }
      
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version 1.1  03/10/2013
    * @version 1.2 by Sameer Rathod 
    * @param type $param
    * @return int
    * @uses function use to add pricing data  
    */
   function addPricingData($param){
       

    
    # array for total no of bank detail 
    $bankName = $param['bankName'];
    $ifsc = $param['ifsc'];
    $accountNo = $param['accountNo'];
    $accountName = $param['accountName'];
    
    
    #update bank detail 
    for ($i = 0; $i < count($bankName); $i++) {
        
        #validate bank details request parameters 
        if(preg_match(NOTALPHABATESPACE_REGX, $bankName[$i]) || $bankName[$i] == "" || strlen($bankName[$i]) > 30)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid bank name","status"=>"error"));
            return 0;
        }
        
        
        if(preg_match(NOTALPHANUM_REGX, $ifsc[$i]) || $ifsc[$i] == "" || strlen($ifsc[$i]) > 10)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid ifsc code","status"=>"error"));
            return 0;
        }
        
        if(preg_match(NOTNUM_REGX, $accountNo[$i]) || $accountNo[$i] == "" || strlen($accountNo[$i]) > 30)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid account number ","status"=>"error"));
            return 0;
        }
        
        if(preg_match(NOTALPHABATESPACE_REGX, $accountName[$i]) || $accountName[$i] == "" || strlen($accountName[$i]) > 30)
        {
            $this->paramError = json_encode(array("msg"=> "Error invalid account name","status"=>"error"));
            return 0;
        }
        #update Bank detail 
        $data = array("slNo" => $i, "BankName" => $bankName[$i], "ifsc" => $ifsc[$i], "accountNo" => $accountNo[$i],"accountName"=>$accountName[$i]); 

        $detail[] = $data; 
     }
     
    #update data array
    $updateData = array('domainId' => $param['domainId'],
        "welcomeImage" => $this->fileName,
        "title"=>$param['title'],
        "heading"=>$param['heading'],
        "subHeading"=>$param['subHeading'],
        "text"=>$param['text'],
        "link"=>$param['link'],
        "mKeyword"=>$param['mKeyword'],
        "mDescription"=>$param['mDescription'],
        "tariffPlan"=>$param['tariffPlan'],
        "bankDetail"=>$detail);
    
    return $updateData;
       
   }
   
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version 1.1  02/10/2013
    * @version 1.2 by Sameer Rathod 
    * @param type $domainId
    * @param type $userId
    * @return type
    * @uses function use to get general Data of any website
    */
   function getGeneralData($domainId,$userId){
       $this->userId = $userId;
       $result = $this->fetchDataFromFile($domainId,'generalData');            
           $data['logoimage'] = $result['logoImage'];
           $data['socialLinks'] = $result['socialLinks']; 
           $data['contact'] = $result['contact'];
           
       #get home page json data from home.js file     

       $result = $this->fetchDataFromFile($domainId,'home'); 
         
           $data['title'] = $result['title'];
           $data['heading'] = $result['heading'];
           $data['subHeading'] = $result['subHeading'];
           $data['text'] = $result['text'];
           $data['link'] = $result['link'];
           
           $data['mKeyword'] = $result['mKeyword'];
           $data['mDescription'] = $result['mDescription'];
           $data['welcomeImage'] = $result['welcomeImage'];
           $data['welcomeContent'] = $result['welcomeContent'];
           
       
        return json_encode($data);
       
   }
   
    /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version 1.1  02/10/2013
    * @version 1.2 by Sameer Rathod 
    * @param type $domainId
    * @param type $userId
    * @return array
    * @uses function use to get About page Data of any website  
    */
   function getAboutData($domainId,$userId){
        $this->userId = $userId;
        $res = $this->fetchDataFromFile($domainId, 'about');
        return json_encode($res);
    }
   
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version 1.1  03/10/2013
    * @version 1.2 by Sameer Rathod 
    * @param type $domainId
    * @param type $userId
    * @return type
    */
   function getContactPageData($domainId,$userId){
        $this->userId = $userId;
        $res = $this->fetchDataFromFile($domainId, 'contact');
        return json_encode($res);
   }

   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version 1.1  04/10/2013
    * @version 1.2 by Sameer Rathod 
    * @param type $param
    * @return int
    * @uses function use to get princing page data 
    */
   function getPricingPageData($domainId,$userId){
       $this->userId = $userId;
      $res = $this->fetchDataFromFile($domainId,'pricing');
        return json_encode($res);
       
   }
   
   /**
    * @author sudhir pandey <sudhir@hostnsoft.com>
    * @since version 1.1  04/10/2013
    * @version 1.2 by Sameer Rathod 
    * @param type $param
    * @return int
    * @uses function use to delete website 
    */
   function deleteWebsite($param,$userId){
       
       $funobj = new fun();
       $domainName = trim($param['domainId']);
       if(preg_match('/[^a-zA-Z0-9\@\.\-\_]+/', trim($param['domainId'])))
               return json_encode(array("msg"=>"Error Invalid domain name please select a valid domain","status"=>"error"));
       #delete data from domain details table 
       $result = $funobj->deleteData("91_domainDetails","domainName='". $domainName."' and resellerId = '".$userId."'");
       

       if(!$result || $funobj->db->affected_rows < 1)
           return json_encode (array("msg"=>"Error deleting domin details please try again later","status"=>"error"));
       
       #clean and set the domain folder
       $this->setDomainFolder($param['domainId']);

       
       #directory name 
       $dir =  $this->domainFolder;
       
       #remove directory of Domain name 
       $this->removeDir(ROOT_DIR."/manageWebsiteImage/".$dir);
       
       
       if($result){
           return json_encode(array("status" => "success", "msg" => "Successfully Deleted Website Detail!"));
       }  else {
           return json_encode(array("status" => "error", "msg" => "Website not deleted .. "));
       }
       
       
       
   }
   
   /**
    * @author sameer rathod 
    * @param type $param
    * @param type $userId
    * @return type
    * @uses to delete the reseller default tariff
    */
   public function deleteResellerDefaultTariff($param,$userId) {
       
       #validate parameters 
       if(preg_match(NOTNUM_REGX, $param['id']) || $param['id'] == "")
       {
           return json_encode(array("msg"=>"Invalid Record please try again or contact provider","status"=>"error"));
       }
       
       if(preg_match(NOTNUM_REGX, $userId) || $userId == "")
       {
           return json_encode(array("msg"=>"Invalid user please try again or login again","status"=>"error"));
       }
       
       $funobj = new fun();
       
       $table = "91_resellerDefaultCurrency";
       
       $selRest = $funobj->selectData("domainId",$table," resellerId = ".$userId);
       
       if($selRest->num_rows <= 1)
           return json_encode(array("msg"=>"You cannot delete all the entries you have to keep atleast one","status"=>"error"));
       
       #conditions for deleting the data 
       $condition = " resellerId = ".$userId." and sno=".$param['id']."";
       
       
       #delete function called 
       $delRes = $funobj->deleteData($table,$condition);
       
       #check if data is delete properly or not 
       if($delRes)
       {
           return json_encode (array("msg"=>"Successfully deleted the record","status"=>"success"));
       }
       else {
           return json_encode (array("msg"=>"Error deleting record please try again","status"=>"error"));
       }
   }
   
   /**
    * @author sudhir pandey
    * @param type $fileName
    * @return type
    */
   function getFileData($fileName) {
       
    $url = "http://".$_SERVER['HTTP_HOST'].'/manageWebsiteImage/'.$fileName;
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}


// When the directory is not empty:
 /**
  * @author sudhir pandey
  * @param type $dir
  */       
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
