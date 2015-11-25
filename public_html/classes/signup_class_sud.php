<?php

/**
 * @author  Rahul <rahul@hostnsoft.com>
 * @modified by sudhir <sudhir@hostnsoft.com>
 * @since 08 sep 2013
 * @package Phone91
 * @details class use for signup
 */

include dirname(dirname(__FILE__)).'/config.php';



class signup_class extends fun //validation_class.php
{
    
    protected $validateFlag = FALSE;
    public $newUserId;
    public $userName;
    public $userId;
    public $resellerId;
    protected $domainResellerId;
    public $firstName;
    public $password;
    public $email;
    public $mobileNumber;
    protected $currencyId;
    public $tariffId;
    public $countryCode;
    protected $confirmCode;
    protected $currencyFlag = 0;
    protected $singleCurrencyFlag = 0;
    
    
    
    
   
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 09/08/2013
    #function use for create new chain id by use of generateId function 
    function newChainId($lastChainId){
         
         #last chain id first part 
         $firstpart = substr($lastChainId,0,-4);
         #last chain id second part (currentuser chain id).
         $secondpart = substr($lastChainId,-4);
         
         #increment last chain id by generateId function 
         $incId = $this->generateId($secondpart);
         if($incId =='' || $incId == $secondpart){
           $this->sendErrorMail("rahul@hostnsoft.com", "Chain Id creation problem (either chain id is blank or same as last chain id).");
         }
         
         
         #new chain id
         $newChainId = $firstpart.$incId;
         return $newChainId;
         
    }
    
    function createUser($parm,$tariff_id,$balance,$currency_id)
    {
      /**
       * @AUTHOR : SUDHIR PANDEY 
       *
       * @MODIFIED BY : SAMEER RATHOD 
       *
       * @DESC : FUNCTION INSERT THE USER INTO THE DATABSE IT DOES ENTRY IN 91_USERLOGIN
       *         91_USERBALANCE,91_PERSONALINFO
       * @PARAMETER : 
       *            $PARAM: ARRAY()
       *                    $PARM['USERNAME'] : DESIRED NAME OF THE USER 
       *                    $PARM['UNAME'] : IN CASE IF THIS IS SET THEN THIS WILL BE THE USERNAME 
       *                                     AND IS DIFFERENT FROM THE NAME OF THE USER 
       *                    $PARM['PASSWORD'] : DESIRED PASSWORD GIVEN BY THE USER
       *                    $PARM['CLIENT_TYPE'] : CLIENT TYPE STATES THAT WHETHER IT'S A RESELLER OR A USER OR A CALLSHOP
       *                    $PARM['CLIENT_LIMIT'] : DEFAULT CALL LIMIT OF THE USER
       *                    $PARM['RESELLER_ID'] : RESELLER UNDER WHICH TEH USER IS BEING CREATED 
       *  
       * @RETURN : RETURN ERROR MSG AND STATUS IN CASE OF ERROR 
       *           RETURN 1 INCASE OF SUCCESS   
       */
      
       #FETCH THE DETAILS OF THE RESELLER IF THE RESELLER IS BLOCKED OR DELETED
       #THEN THE NEW SIGNUPED USER WILL HAVE TEH SAME SETTINGS 
       
        $lastchainId = 0; //initilize last chain id ;
      
        $table = '91_userLogin';
        $condition = "userId = '".$parm['reseller_id']."'";
        $this->db->select('isBlocked,deleteFlag')->from($table)->where($condition);        
        $loginresult = $this->db->execute();
        if ($loginresult->num_rows > 0) {
            $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
            $blockUnblockStatus = $logindata['isBlocked'];
            $deleteFlag = $logindata['deleteFlag'];
        }
        else
        {
            return json_encode(array("status"=>"error","msg"=>"Error Unable to fetch the reseller details Please Try again"));
        }
        
       
        #get reseller rout id and dial plan id 
        $userRoutDetail = $this->getUserBalanceInfo($parm['reseller_id']);
        
        #set route id if not get
        if($userRoutDetail['routeId'] == '' || $userRoutDetail['routeId'] == NULL){
            $userRoutDetail['routeId'] = 1;
        }
        #set dialplan if not get
        if($userRoutDetail['isDialPlan'] == '' || $userRoutDetail['isDialPlan'] == NULL){
            $userRoutDetail['isDialPlan'] = 0;
        }
        
        
      #get last chain id from user balance table  
      $lastchainId = $this->getlastChainId($parm['reseller_id']);
      
      if(!$lastchainId || $lastchainId == "" )
           return json_encode (array("msg"=>"Internal sever error cant create user please try again 505","status"=>"error"));
          
      
      #new chain id (incremented id of lastchain id )
      $chainId = $this->newChainId($lastchainId);
     
      //get reseller timezone
      $timeZone = $this->getResellerTimeZone($parm['reseller_id']);
      
      
      #insert userdetail into database       
      $data=array("name"=>$parm['firstName'],"date"=>date('Y-m-d H:i:s'),'offset' => $timeZone); 
      $personalTable = '91_personalInfo';
      
      
      #insert query (insert data into 91_personalInfo table )
      $personalResult = $this->insertData($data, $personalTable);

      
      #check data inserted or not 
      if(!$personalResult){
          trigger_error('signUp data create user:'.json_encode($data).' query:'.$this->querry);
          //logmonitor('phone-ankit', 'signUp data create user:'.json_encode($data).' query:'.$this->querry);
//        $this->sendErrorMail("sameer@hostnsoft.com", "Phone91 signup_class personal info table query fail : $qur ");
        return json_encode(array("status"=>"error","msg"=>"signup process fail  !"));
          
      }
      
      #user id
      if(isset($parm['uName']) && $parm['uName'] != "")
        $userName = strtolower($parm['uName']);
      else
        $userName = strtolower($parm['username']);
      
      
      if(!isset($parm['client_type']) || $parm['client_type'] == NULL){
          $parm['client_type'] = 3; // user           
      }
      
      $userid = $this->db->insert_id;
      $this->newUserId = $this->db->insert_id;
      #insert login detail into login table database 
      $loginTable = '91_userLogin';
      $data=array("userId"=>$userid,
	          "userName"=>$userName,
	          "password"=>$parm['password'],
	          "isBlocked"=>$blockUnblockStatus,
	          "deleteFlag"=>$deleteFlag,
	          "type"=>(int)$parm['client_type']); 

//       $resellerSetting = $this->getResellerSetting($parm['reseller_id']);  
       
      
      $beforeLoginFlag = 0;
      
//      ($resellerSetting['mobile'] == 1? $beforeLoginFlag = 0 : $beforeLoginFlag = 1);
      (($this->currencyFlag == 1 || $this->singleCurrencyFlag == 1) ? $beforeLoginFlag = 1 : $beforeLoginFlag = 0);

     /*need to discuss this condition*/
       if($parm['domain'] == "phone91.com" && $parm['signupFrom'] != '1'){
          $beforeLoginFlag = 1;
         
      }
      
      /*
       * This condition is applied in case of api 
       */
      // this is commented by sameer as the team does not know who implemented this 
      // and from where this has been used hence it is consider as unused logic
      // 20:09:14 06:36
//      if($parm['apiUser'] == "1")
//      {
//            $beforeLoginFlag = 1;
//      }
      
      
      /**
       * this parameter is set form verified signup api 
       * the before login falg is set to 1
       */
      if($parm['isApi'] == "1")
      {
            $beforeLoginFlag = 1;
      }
      
      
      #check call shop user and set beforelogin flage 2 
      if($parm['client_type'] == 7){
          $beforeLoginFlag = 2;
      }
      
      
      
      
//        #condition for voip91 only  
//        if($_SERVER['HTTP_HOST'] == "voip91.com")  
//            $beforeLoginFlag = 2;
      
//      $beforeLoginFlag = 2; // for old signup funtion
          $data['beforeLoginFlag'] = $beforeLoginFlag;
       
        
          
          
      #insert query (insert data into 91_userLogin table )
      $loginResult = $this->insertWithEncryption( $loginTable,$data);



      #check data inserted or not 
      if(!$loginResult){
        //  logmonitor('phone-ankit', 'signUp data login table:'.json_encode($data).' query:'.$this->querry);
          $this->deleteData($personalTable, "userId = ".$userid);
          
          
//         $this->sendErrorMail("rahul@hostnsoft.com", "Phone91 signup_class userlogin  table query fail : $qur ");
         return json_encode(array("status"=>"error","msg"=>"signup process fail !"));
          
      }
      
      
     
      
      if(strlen($chainId) < 1 || $chainId == NULL)
          return json_encode (array("msg"=>"Internal sever error cant create user please try again","status"=>"error"));
      
       $callRecord =  (isset($parm['callRecord']) and $parm['callRecord'] == 1 )? $parm['callRecord']:"0";
       
      #insert login detail into login table database 
      $balanceTable = '91_userBalance';
     
      $data = array("userId"=>(int)$userid, 
                    "chainId"=>$chainId,
                    "tariffId"=>(int)$tariff_id,
                    "balance"=>$balance,
                    "currencyId"=>(int)$currency_id,
                    "callLimit"=>(int)$parm['call_limit'],
                    "resellerId"=>(int)$parm['reseller_id'],
                    "callRecord" => $callRecord,
                    "routeId"=>$userRoutDetail['routeId'],
                    "isDialPlan"=>$userRoutDetail['isDialPlan'],
                    "getMinuteVoice"=>1); 
      
               
      #insert query (insert data into 91_userBalance table )
      $balanceResult = $this->insertData($data, $balanceTable);
       
      
      if (!$balanceResult){
          
          //logmonitor('phone-ankit', 'signUp data balance tacle:'.json_encode($data).' query:'.$this->querry);
          $this->deleteData($personalTable, "userId = ".$userid);
          $this->deleteData($loginTable, "userId = ".$userid);
         
//         $this->sendErrorMail("rahul@hostnsoft.com", "Phone91 signup_class 91_userBalance query fail : $tempsql ");
          return json_encode(array("status"=>"error","msg"=>"signup process fail !"));  
      }
      
      
        $msg = $this->enableSip($userid,1);
        $resultData = json_decode($msg, TRUE);
        if($resultData['status'] != "success")
        {
            trigger_error('sign Up user sip not enable');
        }
        
        return 1;
    }
     
    
    
    public function validateSignUp($userNameChk = NULL)
    {
         //echo $this->userName.'    //   '. strlen($this->firstName).' | ' .strlen($this->tariffId) .' | '. strlen($this->userName).' | '.strlen($this->password).' | '. ' | '.strlen($this->email);
         
        // strlen($parm['firstName']) < 1  ||
        if (strlen($this->firstName) < 1 || strlen($this->tariffId) < 1 || strlen($this->userName) < 1  || strlen($this->password) < 1 ||  strlen($this->email) < 1) 
        {
            return json_encode(array("status"=>"error",
                                     "msg"=>"Please provide all information"));  
        }
        
        
        if($userNameChk)
        {
            if(preg_match(NOTUSERNAME_REGX, $this->userName))
            {
               return json_encode(array("status" => "error","msg" => "user name are not valid!"));
            }
        }
        else
        {
            if(preg_match(NOTUSERNAME_EMAIL_REGX, $this->userName))
            {
               return json_encode(array("status" => "error","msg" => "user name are not valid!"));
            }
        }
        
        if(isset($this->firstName) && preg_match(NOTPLANNAME_REGX, $this->firstName))
        {
           return json_encode(array("status" => "error","msg" => "Please enter valid Name!"));
        }
        
         
        if (!preg_match(EMAIL_REGX, $this->email)) 
        {
         
            return json_encode(array("status" => "error", "msg" => "email id is not valid !"));
        }
        
        if(preg_match(NOTPASSWORD_REGX, $this->password))
        {
           return json_encode(array("status" => "error", "msg" => "please enter a valid password must not containg any spacial character other than '@_-!$()[]{}?' "));
        }
        
        if(preg_match(NOTNUM_REGX, $this->tariffId))
        {
           return json_encode(array("status" => "error", "msg" => "please select a valid currency' "));
        }
        return 1;
        
    }
    
    /**
     * @author sameer rathod 
     * @param array $param : firstName,lastName,username,email,password,currency,domain
     * @return type 
     * @last modified by : sudhir pandey <sudhir@hostnsoft.com>
     */
    public function signUp($param, $currencyFlag = 0)
    {
        
        $userIp = $this->getUserIP();
        $response =  $this->checkBlockUser($userIp);

        if($response)
        {
           return json_encode(array("status"=>"error","msg"=>"This User is invalid"));
        } 
        
        if(preg_match('/^[0-9]+$/', $param['username'])){
           return json_encode(array("status" => "error", "msg" => "UserName is not valid, please enter atleast one character.")); 
        }
        
        if(!preg_match('/^[a-zA-Z0-9\@\_\.]+$/', $param['username'])){
           return json_encode(array("status" => "error", "msg" => "UserName is not valid, please enter valid userName.")); 
        }
        
        #- globla variable username ,password and first name 
        $this->userName = $param['username'];
        $this->password = $param['password'];
        $this->firstName = $param['firstName'];
        
        //validation for lastname
        if(isset($param['lastName']) && $param['lastName'] != '' )
            $this->firstName.=" ".$param['lastName'];
        
        $this->email = $param['email'];
       
        
        #user select tariff id not currency, its see currency but values are tariff id 
        $this->tariffId = $param['currency'];
        
	$validateRes = 0;
//	if(isset($param['signupFrom']))
	    $validateRes = $this->validateSignUp($param['signupFrom']);
        
       
        if($validateRes != 1)
            return $validateRes;
        
        if(isset( $param['isGoogleFb']) &&  $param['isGoogleFb'] == '1')
        {
             $check = $this->check_user_avail($this->userName , $param['isGoogleFb']);
        }
        else
        $check = $this->check_user_avail($this->userName);
        
        if($check == 0)
        {
             return json_encode(array("status"=>"error","msg"=>"This User name already exists!"));
        }
        
        #get output currency according to tariff id 
        $currencyId = $this->getOutputCurrency($this->tariffId);
        
        
        if($currencyId == 0){
            return json_encode(array("status"=>"error","msg"=>" Dont have any currency for this domain please contact provider!"));
        }
        
        #- condition applied by nidhi nidhi@walkover.in
        #- to check email id of user with domain reseller id.
        $resellerIdServer = $this->getDomainResellerId($_SERVER['HTTP_HOST']);
        //to check  email address already exists or not 
        $table = '91_verifiedEmails';
        $condition = "email = '" . $this->email . "'";  //codition removed for domain reseller id because I may cuse error,
        $result = $this->selectData("*", $table, $condition);

        if ($result->num_rows > 0)
        {
            //return json_encode(array("status"=>"error","msg"=>"This email address already registered!"));
        }

        if(isset($param['domain']))
            $domain = $param['domain'];
        else
            $domain = $_SERVER['HTTP_HOST'];
      
        $domainResellerDetails = $this->getDomainResellerId($domain,2);  
        
        $resellerId = $param['reseller_id'] = $domainResellerDetails['resellerId'];
        $domainId = $domainResellerDetails['id'];
      
        if (!$resellerId || $resellerId == "" || $param['reseller_id'] == "" || !$param['reseller_id'])
        {
            return json_encode(array("status"=>"error","msg"=>"Dont have any reseller information for this domain please contact provider"));
        }
      
      
        #set call limit
        $param['call_limit'] = 2;

        #initilize the balance variable 
        $balance = 0;
        
        #get all the default currency of the the reseller  
        $balanceData = $this->getResellerDefaultCurrency($resellerId , $currencyId,2,$domainId);
        #set the num rows into a table 
        $balanceDataNumRows = $balanceData->num_rows;
        
         if(!$balanceData)
            trigger_error('response not found from getResellerDefaultCurrency function');

         
        if($balanceData && $balanceDataNumRows > 0)
        {
           #if the num rows id only one then set the single flag this will be the 
           #actual currency and then user dont need to provide the currency later 
          if($balanceDataNumRows == 1)
          {
              #this flag will be check at the time of setting before login falg for currency
              $this->singleCurrencyFlag = 1;
              $row = $balanceData->fetch_array(MYSQLI_ASSOC);
              $balance = $row['balance'];
              $this->tariffId = $row['tariffId'];
          }
          else
          {
              #if there are more than one currency of the reseller then two cases occurs 
              #one if the currency if provided already by the user then currencyflag will 
              #be one and this becomes the actual currency other wise it is a dummy currency 
              #and the tariffid will be dummy and balance will be 0 for this 
              while($row = $balanceData->fetch_array(MYSQLI_ASSOC))
              {   
                  if($row['currencyId'] == $currencyId)                
                  {
                      $balance = $row['balance'];
                      $this->tariffId = $row['tariffId'];
                  }
              }
          }
        }
        else
            return json_encode(array("status"=>"error","msg"=>"Invalid currency type please try again"));


       $createUserResult = $this->createUser($param, $this->tariffId,$balance, $currencyId);

     
        if($createUserResult != 1)
            return $createUserResult;
        
        $userId = $this->newUserId;
        
        /**
         * 
         * This condition is applied by nidhi <nidhi@walkover.in>
         * To redirect user in case of google or facebook
         * we will take currency of this user later.
         *  
         */
      
        
        if($this->singleCurrencyFlag == 1 || (isset($param['domain']) &&  $param['domain'] == "phone91.com" && $param['signupFrom'] != '1' ||  $param['isApi'] == 1 ))
            $msg = $this->signupTransaction($resellerId,$userId,$balance);
        
       
        #if ever we set a currency form any domain then change this condition according to it because 
        #this will only work for phone91 in case of multiple currency 
//        if($this->singleCurrencyFlag == 1 || (isset($param['domain']) &&  $param['domain'] == "phone91.com"))
//        {
//            #add taransaction detail into taransation log table 
//            $msg = $this->signupTransaction($resellerId,$userId,$balance);
//        }
        
        
        $this->setResellerSetting($userId);
       
      
        
       
       
        
        #check all update reveive by email or not
        $updateReceive = 0;
        if($param['updateReceive'] == 1){
            $updateReceive = 1;
        }
        
        
        $tempEmail = "91_tempEmails";
        
        //signupfrom is 1 in case of facebook and google 
        //for making the entry of email in verified table
        
        if(isset($param['signupFrom']) && $param['signupFrom'] == '1')
            $tempEmail = "91_verifiedEmails";
        
        $emailConfirmCode = $this->generatePassword();
           
        $resellerId = $this->getResellerId($userId);
        
        $data=array("userid"=>(int)$userId,
                  "email"=>$param['email'],
                  "confirm_code"=>$emailConfirmCode,
                  "date"=>date('Y-m-d H:i:s'),
                  "newsFlag"=>$updateReceive , "resellerId" => $resellerId); 
        
        #insert query (insert data into 91_tempEmails table )
        $tempEmailResult = $this->insertData($data, $tempEmail);
        
        if ($tempEmailResult) 
        {
            if($param['isApi'] ==1 )
            {
               return json_encode(array('status' => 'success','msg' => 'Signup successfully complited ')); 
            }
	    
            
	     if(isset($param['domain']))
	    {
		$this->login_user($this->userName,$this->password,0,$param['domain']);
	    }
	    else        
		$this->login_user($this->userName,$this->password,0,NULL); 

            if(isset($param['signupFrom']) && $param['signupFrom'] == '1')
            {
                mail($this->userName,"Phone91" , "Hey There, Thanks for signup! on phone91. Your username and password are - username: ".$this->userName." and password: ".$this->password);
                return json_encode(array("status"=>"success","msg"=>"You are  successfully registered with us", 'getCurrency' => $this->singleCurrencyFlag ));
            }
              
              #send confirm code by email id for email varification 
              $sentmail = $this->send_verification_mail($param['email'],$emailConfirmCode );
              if($sentmail)
              {
                  return  json_encode(array('status' => 'success', 'msg' => 'Your Confirmation link Has Been Sent To Your Email Address.'));
              }
              else
              {
                  return json_encode(array('status' => 'success','msg' => 'Signup successfully complited but your confirmation link has not send.'));
              }

        }
        else
        {
              return json_encode(array('status' => 'success','msg' => 'Signup successfully complited but your confirmation link has not send.'));
              trigger_error('problem while insert in temp emails,data:'.json_encode($data));
        }

        /** 
         * email validation part left send a confirmation email along with login user with flag 1 
         * reditrect 
         * 
         * 
         * 
         */
        
    }
    
    public function validateContactParam() {        
        if(preg_match(NOTMOBNUM_REGX, $this->mobileNumber) || empty($this->mobileNumber))
                return json_encode (array("msg"=>"Invalid Mobile Number please enter correct number","status"=>"error"));
        
        if(preg_match(NOTNUM_REGX, $this->countryCode) || empty($this->countryCode))
                return json_encode (array("msg"=>"Invalid code please select a proper country","status"=>"error"));
        
        if(preg_match(NOTNUM_REGX, $this->userId) || empty($this->userId))
                return json_encode (array("msg"=>"Invalid user please login","status"=>"error"));
        
        if(isset($this->resellerId) && (preg_match(NOTNUM_REGX, $this->resellerId) || empty($this->resellerId)))
                return json_encode (array("msg"=>"Invalid reseller Id please provide a valid reseller Id","status"=>"error"));
        
        $this->validateFlag = TRUE;
        return 1;
    }
    
    /**
     * @author sameer rathod 
     * @param type $request
     * @param type $userId
     * @param type $type
     * @return int
     */
    public function saveMobileNumber($request,$userId,$type)
    {
        $this->mobileNumber = $request['mobileNumber'];
        $this->countryCode = $request['countryCode'];
        $this->userId = $userId;
        
        $validateResult = $this->validateContactParam();
        
        if($validateResult != 1)
            return $validateResult;
        
        /******This is to chekc whether the validate function is called or not**********/
        if(!$this->validateFlag)
            return json_ecode(array("msg"=>"Error : 500 Internal server error invalid input provided","status"=>"error"));
        
        if($type == "temp")
        {
            $table = "91_tempNumbers";
            $col = "tempNumber";
        }
        elseif($type == "verify")
        {
            $table = "91_verifiedNumbers";
            $col = "verifiedNumber";
        }
        
        if(isset($_SESSION['domain']))
        $this->domainResellerId = $this->getDomainResellerId($_SESSION['domain']);
        else
        $this->domainResellerId = $this->getDomainResellerId($_SERVER['HTTP_HOST']); 
        
       // echo ' DOMAIN    '.$_SESSION['domain'].'  '.$this->domainResellerId ;
        
        //die('s');
        
        if(!isset($this->resellerId) || empty($this->resellerId))
            $this->resellerId = $this->getResellerId($userId);
//        else
//            $resellerId
        
       
        $this->confirmCode = $this->generatePassword(4);
        
        $data = array( "userId" => $this->userId ,
                        "domainResellerId"=>  $this->domainResellerId,
                        "countryCode" => $this->countryCode ,
                        $col => $this->mobileNumber,
                        "confirmCode" => $this->confirmCode, 
                        "resellerId" => $this->resellerId   );
        
        
//        $result = $this->insertData($data,$table);
        $this->db->insert($table,$data)->onDuplicate(" confirmCode=".$this->confirmCode);
        $result = $this->db->execute();
        
        if($result)
            return 1;
        else
            return json_encode(array("msg"=>"Error saving contact number please try again later","status"=>"error"));
    }
    
    
    
    public function getResellerSetting($userId) {
        if(preg_match('/[^0-9]+/', $userId) || $userId == "")
            return FALSE;
        $result = $this->selectData("mobile", "91_resellerSetting","userId=".$userId);
        if($result)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            return $row;
        }
        else
            return FALSE;
    }   
    
    /**
     * @author Sameer Rathod
     * @modified by Sudhir Pandey <sudhir@hostnsoft.com>
     * @param type $carrierType = sms or call
     */
    protected function sendSmsAndCall($carrierType) {
        
          if(!$this->validateFlag)
            return json_ecode(array("msg"=>"Error : 500 Internal server error invalid input provided","status"=>"error"));
        
          if($this->countryCode == "" || $this->mobileNumber == "")
              return json_encode (array("msg" => "Invalid Input please try again later","status"=>"error"));
          
          $mobileNo = $this->countryCode . $this->mobileNumber;
          
          $userDetail = $this->getUserInformation($this->userId,1);
           
          $this->userName = $userDetail['userName'];
          
          
          if($carrierType  == "SMS")
          {
              # Assign Variables for sending sms to user
            $d["text"] = "you are successfully registered your username is: " . $this->userName . " and confirmation-code is: " . $this->confirmCode . " Please recharge to start using this account."; // sms text
            $d["to"] = $mobileNo;

            //for 91 user
            $nine['mobiles'] = $this->mobileNumber;
            $nine['message'] = "you are successfully registered your username is " . $this->userName . " and confirmation-code is " . $this->confirmCode . " Please recharge to start using this account."; // sms text
            
            include_once(CLASS_DIR."sendSmsClass.php");
            $sendSms = new sendSmsClass();
            
            $paramSms['to'] = $mobileNo;
            $paramSms['text'] = $nine['message'] ;
            
            $sendSms->sendMessagesGlobal($paramSms);
            
//            if ($this->countryCode == "91")
//            {
//                  $sendSmsResponse= json_decode($this->SendSMS91($nine));
//            }
//            else
//            {
//                  $sendSmsResponse=  json_decode($this->SendSMSUSDnew($d));
//            }

            $sendSmsResponse->contactNumber = $mobileNo;
            $sendSmsResponse->userName = $this->userName;
            $sendSmsResponse->userId = $this->userId;

            include_once(CLASS_DIR."db_class.php");
            $dbClsObj = new db_class();
            $dbClsObj->mongo_insert("91_smsApiResponse", $sendSmsResponse);
            
            if($sendSmsResponse->status == "error")
                $response = array("msg"=>"Error message not sent please contact provider","status"=>"error");
            else
                $response = array("msg"=>"Successfuly Registered verifycation code will be recieved shortly on your mobile","status"=>"success","data"=>$sendSmsResponse->contactNumber);
                
            
            return json_encode($response);
          }
          elseif($carrierType == "CALL")
          {
            #send code by call
            $this->mobile_verification_api($mobileNo, $this->confirmCode);
            $response = array("msg"=>"Successfuly Registered verifycation code will be recieved shortly on your mobile","status"=>"success","data"=>$mobileNo);
            return json_encode($response);
          }
          else
          {
              return json_encode(array("msg"=>"Invalid carrier type please contact provider","status"=>"error"));
          }
    }
    
    /**
     * @author Sameer Rathod <sameer@hostnsoft.com>
     * @param type $request
     * @param type $userId
     * @return type json
     */
    public function mobileVerificationBeforeLogin($request,$userId) {
        
        $saveResult = $this->saveMobileNumber($request, $userId, "temp");  
        
        if($saveResult != 1)
            return $saveResult;
        
        #carrier type = sms or call
        return $this->sendSmsAndCall($request['carrierType']);
        
    }
    
    /**
     * @author Sameer Rathod <sameer@hostnsoft.com>
     * @param type $currencyId
     * @param type $resellerId
     * @param type $userId
     * @return type json
     */
    public function updateUserCurrencySetPlan($currencyId,$resellerId,$userId ,$reqFlag = NULL) {
        $currencyId = trim($currencyId);
        if(preg_match('/[^0-9]+/', $currencyId) || $currencyId == "")
                return json_encode (array("Invalid Currency please select a proper currency","status"=> "error"));
        
        if( $resellerId == "")
                return json_encode (array("msg"=>"Insuficient information of the user pelase contact provider","status"=> "error"));
        
        if($userId == "")
                return json_encode (array("Invalid user please login again","status"=> "error"));
        
        
        
        
    /***********GET THE DEFAULT TARIFF DETAILS OF THE RESELLER************************/
      $resultDefaultCurrency = $this->getResellerDefaultCurrency($resellerId , $currencyId);
      
     
      //log error
      if(!$resultDefaultCurrency)
          trigger_error('response not found from getResellerDefaultCurrency function');
      
      if($resultDefaultCurrency && $resultDefaultCurrency->num_rows > 0)
      {
        $row = $resultDefaultCurrency->fetch_array(MYSQLI_ASSOC);
        $tariffId = $row['tariffId'];
        $balance = $row['balance'];
      }
      else
          return json_encode(array("status"=>"error","msg"=>"Invalid currency type please try again"));
  /********************END HERE*****************************************************/   
      
      $opCurr = $this->getOutputCurrency($tariffId);
      
      if($opCurr > 0)
          $_SESSION['currencyId'] = $opCurr;
      
      $data = array("tariffId"=>$tariffId,"balance"=>$balance , "currencyId" => $currencyId);
      $table = "91_userBalance";
      $condition = "userId = ".$userId."";
      $updateRes = $this->updateData($data, $table,$condition);
     
      
      if($updateRes)
      {
         $resellerSetting = $this->getResellerSetting($resellerId); 
         if($resellerSetting['mobile'] == 1)
             $loginFlag = 1;
         else
             $loginFlag = 2;
         
        $updData = array("beforeLoginFlag"=>$loginFlag);
        
        if($reqFlag == 1)
        {
//            $updData = array("beforeLoginFlag"=>1);
       
            $this->signupTransaction($resellerId,$userId,$balance);
        }
//        else
//        {
////            $updData = array("beforeLoginFlag"=>1);
//  
//        }
        
        $updRes = $this->updateData($updData,"91_userLogin","userId='".$userId."'");
         
     
        if(!$updRes)
            mail("sameer@hostnsoft.com","update query fails",__FILE__."".__FUNCTION__);
        
          $_SESSION['loginFlag'] = $loginFlag;
          return json_encode(array("msg"=>"Successfully updated the currency","status"=>"success"));
      }
      else
          return json_encode(array("msg"=>"Error updating information please try again later","status"=>"error"));
    }
    
    
    
    
    #created by sudhir pandey 
    #function use to save signup transaction detail into transaction table 
    function signupTransaction($resellerId,$userId,$balance){
       
        #add taransaction detail into taransation log table 
        include_once(CLASS_DIR."transaction_class.php");
        
        $transaction_obj = new transaction_class();
        
        /* CALL ADD TRANSACTIONAL FUNCTION FOR ADD TRANSACTION  : 
         * 
         * $resellerid : FromUser
         * $userid : toUser
         * $balance : amount for credit or debit
         * $balance : talktime amount 
         * $paymentType : cash,memo,bank
         * $description : description of transaction 
         * type : prepaid ,postpaid , partial
         * 
         */
        $transaction_obj->fromUser = $resellerId;
        $transaction_obj->toUser = $userId;
      
        //var_dump($transactionObj);      
        $msg = $transaction_obj->addTransactional_sub($balance,$balance,"signUp",0,0,0,"Sign Up Transaction",0);
        
//        $msg = $transaction_obj->addTransactional($reseller_id, $userid, $balance,$balance, "cash", "sign Up", "prepaid"); //$fromUser,$toUser,$amount,$paymentType,$description,$type
       
//        #get current balance form 91_userBalance table
//        $currBalance = $transaction_obj->getcurrentbalance($userid);
//        $currentBalance = ((int)$currBalance + (int)$balance);
//        
//        #update current balance of user in userbalance table 
//        $transaction_obj->updateUserBalance($userid,$currentBalance);
        
        
    }
    
    
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 18-12-2013
    #function use to set reseller setting : set email and mobile status for check verification 
    function setResellerSetting($userid){
        
        $table = '91_resellersetting';
        $dataArr = array(
                                 'userId' => $userid,
                                 'email' => 0,
                                 'mobile' => 1
                        );
                
        $result = $this->insertData($dataArr,$table);
        if(!$result)
           trigger_error('problem while insert reseller setting: '.json_encode($data));
    }
    
    
    #created by sudhir pandey 
    #function use to check user name are already exist in table or not
    function check_user_avail($username = NULL , $isGoogleFb = NULL) 
    {
        if(isset($_REQUEST['username']))
            $username = $_REQUEST['username'];
        
        
        if(isset($isGoogleFb) && $isGoogleFb == '1')
        {
            if(!preg_match(EMAIL_REGX, $username))
            {
               return json_encode(array("status" => "error", "msg" => "userName are not valid.")); 
            }
        }
        else
        {
//            if(!preg_match('/^[a-zA-Z0-9\@\_\-\!\$\(\)\?\[\]\{\}\s]+$/', $username)){
            if(preg_match(NOTUSERNAME_REGX, $username) || empty($username)){
                   return json_encode(array("status" => "error", "msg" => "userName are not valid.")); 
            }
        }
        
        $table = '91_userLogin';
        $this->db->select('userName')->from($table)->where("userName = '" . $username . "' ");
//       echo $this->db->getQuery();
        $result = $this->db->execute();
//        	    var_dump($result);
        // processing the query result
        if ($result->num_rows > 0) {
            return 0; //echo "Sorry username already in use";
            exit();
        }
        else
        {
            return 1;
            exit();
        }
    }
    
    #created by sudhir pandey 
    #function use to check email aleady exit or not in verification table 
    function check_email_avail($email ,$domainName = NULL ,$type=NUll) 
    {
        
        if(!preg_match(EMAIL_REGX,$email) || empty($email)){
            return 0;
        }
        
        $email = $this->db->real_escape_string($email);
        
        
        if($domainName)
        {
             $domainName = $this->db->real_escape_string($domainName);
             $resellerIdServer = $this->getDomainResellerId($domainName);
        }
        else
            $resellerIdServer = $this->getDomainResellerId($_SERVER['HTTP_HOST']);
        
        
        $condition = "email = '" . $email . "'";
        
        if(is_null($type))
            $condition .= " and domainResellerId='".$resellerIdServer."'";
        
        $table = '91_verifiedEmails';
        $this->db->select('email')->from($table)->where($condition); // condition is removed because it may cause error.
        
        /////echo $this->querry;
        
        $result = $this->db->execute();
        // processing the query result
        if ($result->num_rows > 0) 
        {
            
            
            return 0; //echo "Sorry username already in use";
        }
        else
        {
            return 1;
        }
    }
    
   
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 03/09/2013
    #function use to create bulk client     
    function addNewClientBatch($request,$resellerId){

        #check username is blank or not
        if ($request['batchName'] == '' || $request['batchName'] == NULL) {
            return json_encode(array("status" => "error", "msg" => "Please insert Batch name ."));
        }
        
        #check valid batch name 
        if(!preg_match('/^[a-zA-Z_@-\s]+$/',$request['batchName']))
        {
          return json_encode(array('status'=>'error','msg'=>'Please Enter Valid batch name'));
        }
        else if(strlen($request['batchName']) < 5 || $request['batchName'] > 30)
        {
          return json_encode(array('status'=>'error','msg'=>'Please Enter Valid length for batch name Min:5,Max:30!!!'));
        }

        #check total no of client is valid or not   
        if (!is_numeric($request['totalClients']) && strlen($request['totalClients'])>4 ) {
            return json_encode(array("status" => "error", "msg" => "Total Number of client are not valid!"));
        }

        #check total no of client is valid or not 
        if (!preg_match("/^[0-9]{1,4}$/", $request['totalClients'])) {
            return json_encode(array("status" => "error", "msg" => "Total Number of client are not valid!"));
        }

        #check tariff paln is selected or not 
        if ($request['tariff'] == "") {
            return json_encode(array("status" => "error", "msg" => "Please Select Tariff Plan ! "));
        }

        #chech payment type is selected or not 
        if ($request['payTypeBulk'] == "select") {
            return json_encode(array("status" => "error", "msg" => "Please Select Payment Type ! "));
        }
        #chech payment type is selected or not 
        if (strtotime($request['batchExpiry']) < strtotime(date("Y-m-d H:i:s"))) {
            return json_encode(array("status" => "error", "msg" => "Expiry Date Must be correct "));
        }

        #check total no of pins is numeric or not 
        if (!preg_match("/^[0-9]{1,4}$/", $request['balance'])) {
            return json_encode(array("status" => "error", "msg" => "4 digits Numeric value required in balance field ! "));
        }
        
        
        if($request['totalClients']< 1 || $request['totalClients']>999)
            return json_encode(array("status" => "error", "msg" => "Invalid number of total Client ! "));

        
        $bulkUserTable = '91_bulkUser';
        
        $this->db->select('*')->from($bulkUserTable)->where("batchName = '" . $request['batchName'] . "'");
        $this->db->getQuery();
        $result = $this->db->execute();
        if ($result->num_rows > 0){
            return json_encode(array("status"=>"error","msg"=>"Batch name already registered!"));
        }
        

        $data = array("batchName" => $request['batchName'],"numberOfClients"=>(int)$request["totalClients"],"expiryDate"=>$request["batchExpiry"],"userId"=>$resellerId,"batchBalance" =>$request['balance'],"listenTime" => 1);

        #insert query (insert data into 91_bulkUser table )
        $this->db->insert($bulkUserTable, $data);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        //var_dump($result);
        #check data inserted or not 
        if (!$result) {
            $this->sendErrorMail("rahul@hostnsoft.com", "$bulkUserTable insert query fail : $qur ");
            return json_encode(array("status" => "", "msg" => "add Batch process fail! please try again"));
        }
        $batchId = $this->db->insert_id;

                
        
        #get reseller block unblock status from login table 
        $table = '91_userLogin';
        $condition = "userId = '".$resellerId."'";
        $this->db->select('*')->from($table)->where($condition);
        $this->db->getQuery();
        $loginresult = $this->db->execute();
        if ($loginresult->num_rows > 0) {
        $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
        $blockUnblockStatus = $logindata['isBlocked'];
        $deleteFlag = $logindata['deleteFlag'];
            }
            
        #get reseller rout id and dial plan id 
        $userRoutDetail = $this->getUserBalanceInfo($resellerId);
        
        #set route id if not get
        if($userRoutDetail['routeId'] == '' || $userRoutDetail['routeId'] == NULL){
            $userRoutDetail['routeId'] = 1;
        }
        #set dialplan if not get
        if($userRoutDetail['isDialPlan'] == '' || $userRoutDetail['isDialPlan'] == NULL){
            $userRoutDetail['isDialPlan'] = 0;
        }    
                 
        include_once(CLASS_DIR."transaction_class.php");
        $transaction_obj = new transaction_class();    
            
        for($request['totalClients'];$request['totalClients']>0;$request['totalClients']--){
            $userName= $this->createUsername($batchId);
            $password= $this->createUsername($batchId);  
            
                $lastchainId = 0 ;
                
                #get last chain id from user balance table  
                $lastchainId = $this->getlastChainId($resellerId);
            
                if(!$lastchainId || $lastchainId == "" )
                    return json_encode (array("msg"=>"Internal sever error cant create user please try again 505","status"=>"error"));

                #new chain id (incremented id of lastchain id )
                $chainId = $this->newChainId($lastchainId);
            
                #insert userdetail into database 
                $personalTable = '91_personalInfo';
                $data = array("name" => $userName,"date"=>date('Y-m-d H:i:s'),"menuIndex"=>'1,3,4,5,6,12');

                #insert query (insert data into 91_personalInfo table )
                $this->db->insert($personalTable, $data);
                $qur = $this->db->getQuery();
                $result = $this->db->execute();
                //var_dump($result);
                #check data inserted or not 
                if (!$result) {
                    trigger_error('add user process fail!:'.$qur);
                    $this->sendErrorMail("Ankitpatidar@hostnsoft.com", "insert query fail : $qur ");
                    return json_encode(array("status" => "", "msg" => "add user process fail!"));
                }

                #user id 
                $userid = $this->db->insert_id;

                
        

                #insert login detail into login table database 
                $loginTable = '91_userLogin';
                $data = array("userId" => (int) $userid, "userName" => $userName, "password" => $password, "isBlocked" => $blockUnblockStatus,"deleteFlag"=>$deleteFlag, "type" => 4,"beforeLoginFlag"=>2);

                #insert query (insert data into 91_userLogin table )
                $this->insertWithEncryption($loginTable,$data);
                
                #check data inserted or not 
                if (!$result) {
                  trigger_error('add user process fail!:'.$qur);
                    $this->deleteData($personalTable, "userId = ".$userid);
                    return json_encode(array("status" => "error", "msg" => "add user process fail !"));
                }



                 #user balance from plan table  
                $balance = $request['balance'];
                #currency id 
                $currency_id = $this->getOutputCurrency($request['tariff']);
                #call limit 
                $call_limit = 2;
                
                #payment type (cash,memo,bank).
                if(isset($parm['payType']) && $parm['payType'] == "Other"){
                $paymentType = $this->db->real_escape_string($request['otherType']);     
                }else
                  $paymentType = $request['payTypeBulk'];
                
                #description
                $description = '';

               

                
                
                #insert login detail into 91_userBalance table database 
                $loginTable = '91_userBalance';
                $data = array("userId" => (int) $userid,"chainId"=>$chainId , "tariffId" => (int) $request['tariff'], "balance" => $balance, "currencyId" => (int) $currency_id, "callLimit" => (int) $call_limit, "resellerId" => (int) $resellerId, "userBatchId"=>(int)$batchId ,"routeId"=>$userRoutDetail['routeId'],"isDialPlan"=>$userRoutDetail['isDialPlan'],"getMinuteVoice" => 1);
                #insert query (insert data into 91_userLogin table )

                trigger_error('user balance detail!:'.json_encode($data));
                $this->db->insert($loginTable, $data);
                $tempsql = $this->db->getQuery();
                $result = $this->db->execute();
                //var_dump($result);
                if (!$result) {
                  trigger_error('add user process fail!:'.$tempsql);
                    $this->deleteData($personalTable, "userId = ".$userid);
                    $this->deleteData($loginTable, "userId = ".$userid);
                    return json_encode(array("status" => "error", "msg" => "add user process fail!"));
                }
                 
          
        
                /* CALL ADD TRANSACTIONAL FUNCTION FOR ADD TRANSACTION  : 
                * 
                * $resellerid : FromUser
                * $userid : toUser
                * $balance : amount for credit or debit
                * $balance : talktime amount 
                * $paymentType : cash,memo,bank
                * $description : description of transaction 
                * type : prepaid ,postpaid , partial
                * 
                */
                $transaction_obj->fromUser = $resellerId;
                $transaction_obj->toUser = $userid;

                $msg = $transaction_obj->addTransactional_sub($balance,$balance,"Bulk Add",0,0,0,"Bulk User Add",0);
                
                
                #enable user sip id
                $sipMsg = $this->enableSip($userid,1);
                $resultData = json_decode($sipMsg, TRUE);
                if($resultData['status'] != "success"){
                    trigger_error('user sip not enable in batch client ');
                }

                
               
        }
        
        include_once(CLASS_DIR . "reseller_class.php");
        $res_obj=new reseller_class();
        $bulkuser = $res_obj->bulkUserBatch($_SESSION['userid']);
        $bulkData = json_decode($bulkuser, true); 
        if($result){
            return json_encode(array("status" => "success", "msg" => "successfully add bulk user","batchDetail"=>$bulkData));
        }
        
    }
    
    
    /**
    * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
    * @since 10/03/2014
    * @param type $request
    * @param type $session 
    */
   function loginAsInReseller($request,$session)
   {
       //get user id and validate that
       if(!isset($request['userId']) || !is_numeric($request['userId']))
       {
           return json_encode(array('status' => 0,'msg' => 'Not a valid user!!!'));
           
       }
       
       //check for reseller
        if(!isset($session['tempVar']['type']))
        {
            $tempVar = array('mainUser' => $session['userid'],
                             'type' => 2);
        }
        else //for user
            $tempVar = array();
            
       
//       if(!isset($session['mainUser']))
//            $tempVar = $session['userid'];
//        else
//            $tempVar = false;
        
       
       $userId = $request['userId'];
        #- including function layer.php 
       // include_once  "../function_layer.php";
       // $funObj = new fun();
        
        $userInfo = $this->getUserInformation($userId,1);
       
        //check for correct reseller
        if($session['client_type'] == '2')
        {
            
            if(isset($request['url']) && $request['url'] != "")
                $this->insertLandingPage($request['url'],$session['id']);
            //get user reseller id
            if($tempVar)
                $resId = $this->getResellerId($userId);
            else
                $resId = $this->getResellerId($session['id']);
                
            if(!$resId)
            {
                return json_encode(array('status' => 0,'msg' => 'user info not found!!!'));
                
            }
            elseif($tempVar && $resId != $session['userid'])
            {
                return json_encode(array('status' => 0,'msg' => 'You have not permission for login as this user!!!'));
                

            }
            elseif(!$tempVar && $resId != $userId)
            {
                return json_encode(array('status' => 0,'msg' => 'You have not permission for login as this user!!!'));
                
            }
            
        }
        else if($session['client_type'] == '3')
        {
            //get user reseller id
            $resId = $this->getResellerId($session['userid']);
            
             if(!$resId)
            {
                 
                return json_encode(array('status' => 0,'msg' => 'Problem while getting info!!!'));
                
            }
            elseif($resId != $userId)
            {
                return json_encode(array('status' => 0,'msg' => 'You have not permission for login as this user!!!'));
                

            }
            
            
        }
        
        if(empty($userInfo))
        {
            return json_encode(array('status' => 0,'msg' => 'user info not found!!!'));
            
        }
         
        
        $userName = $userInfo['userName'];
        $pass = $userInfo['password'];
        
        $host = $session['currentHost'];
        
        $response = $this->login_user($userName, $pass, 0 ,$host,0,$tempVar);
        
   }
   
   /**
    * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
    * @since 22/03/2014
    * @param Array $request contains user id and type(optional)
    * @param array $session
    * @return type
    */
   function loginAsInAdmin($request,$session)
   {
      
      //get user id and validate that
       if(!isset($request['userId']) || !is_numeric($request['userId']))
       {
           return json_encode(array('status' => 0,'msg' => 'Not a valid user!!!'));
           
       }
       
       //get user details by user id
        $userId = $request['userId'];
        $userInfo = $this->getUserInformation($userId,1);

        if(empty($userInfo))
            return json_encode(array('status' => 0,'msg' => 'user information not found!!!'));
        
        $userName = $userInfo['userName'];
        $pass = $userInfo['password'];

	
        $host = (isset($session['currentHost'])?$session['currentHost']:'');

       $tempVar = array();
       //check user type
       if(isset($session['client_type']) && $session['client_type']  == 1)
       {
           //check for admin
            if(!isset($session['acmId']))
            {
                $tempVar = array('mainUser' => $session['userid'],
                                 'type' => 1);
            }
            else //for account manager
            {
                $tempVar = array('mainUser' => $session['userid'],
                                 'acmId' => $session['acmId'],
                                 'type' => 1
		    );
            }
            
            //call login function
            $response = $this->loginUserForLoginAs($userName,$host,$tempVar);
       
       }
       else //if other then admin,in case of return back to admin or account manager
       {
          
           //check for return back admin
           if(!isset($session['tempVar']['acmId']) && $session['tempVar']['type'] == 1 )
           {
	       
                //call login function
                $response = $this->loginUserForLoginAs($userName,$host,$tempVar);
       
           }
           elseif(isset($session['tempVar']['acmId']) && is_numeric($session['tempVar']['acmId']) )
           {
              
               $acmId = $session['tempVar']['acmId'];
               //get acm info
               include_once CLASS_DIR.'account_manager_class.php';
               $acmObj = new Account_manager_class();
               
               $acmArr = $acmObj->getAcmInfo($acmId);
               
               
               if(empty($acmArr))
               {
                   return json_encode(array('status' => 0,'msg' => 'user info not found!!!'));
               }    
               
//               $acmCredential = array();
//               //get account manager username and password
//               $acmCredential['uname'] = $acmArr['userName'];
//               $acmCredential['pwd'] = $acmArr['password'];
//               
	       $acmId = $acmArr['acmId'];
	       $acmCodeArr['code'] = $acmArr['acmCode'];
               //call account manager fucntion
               $resultJson =  $acmObj->accountManagerCodeVerfy($acmCodeArr,$acmId);
	       $resultArr = json_decode($resultJson,TRUE);
	       
	       if($resultArr['status'] == 'success')
		   $this->redirect(ADMIN_DIR.'index.php#!manage-client.php|manage-client-setting.php');
	       else
		   echo $resultJson;
	       
	       
	     }    
           
       }
        
   }
   
   /**
    * @author sameer rathod
    * @param type $eid
    * @param type $userId
    * @return boolean
    */
   public function updateVerifiedUserId($eid,$userId)
   {
       if(preg_match(NOTALPHANUM_REGX,$eid) || empty($eid))
       {
           $this->msg = "Error Invalid Id please provide valid data";
           $this->code = "314";
           $this->status = "error";
               return false;
       }
       if(preg_match(NOTNUM_REGX,$userId) || empty($userId))
       {
           $this->msg = "Error Invalid User Id please provide valid data";
           $this->code = "315";
           $this->status = "error";
           return false;
       }
       $table = "91_verifiedNumbers";
//       $column = "userId";
       $data = array("userId"=>$userId);
       $result = $this->updateData($data,$table,"userId='".$eid."'");
       if($result)
       {
           $this->msg = "Successfuly updated userId";
           $this->status = "success";
           $this->code = "316";
           return true;
       }
       else{
           $this->msg = "Error updating data please try again later";
           $this->code = "317";
           $this->status = "error";
           return false;
       }
       
   }
   
   /**
    * @author sameer rathod
    * @param type $authToken
    * @param type $accessType
    * @return boolean
    */
   public function checkFbGlAuthKey($authToken,$accessType){
      $url = '';

       if(empty($authToken))
       {
           $this->msg = "Error Invalid authentication token";
           $this->code = "310";
           $this->status = "error";
           return false;
       }
       
       if($accessType != 1 && $accessType != 2)
       {
           $this->msg = "Error Invalid access type";
           $this->code = "311";
           $this->status = "error";
           return false;
       }
        switch($accessType)
        {
            case '1' :
                $url =  "https://graph.facebook.com/me?access_token=";
                break;

            case '2' :
               $url =  "https://www.googleapis.com/oauth2/v1/userinfo?access_token=";
                break;

            default:

               $this->msg = "Error Invalid access type";
               $this->code = "312";
               $this->status = "error";
               return false;   

        }

        $response = $this->callApi( $authToken , $url );
        
        if(!$response || strtolower($response) == "not found")
        {
           $this->msg = "Error in api";
           $this->code = "313";
           $this->status = "error";
           return false; 
        }
        
        $this->setData($response);
        return true;
   }
   
   
}//end of class
$signup_obj = new signup_class();//class object
?>