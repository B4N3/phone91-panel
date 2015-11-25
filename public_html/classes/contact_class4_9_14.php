<?php

/**
 * @author  Rahul <rahul@hostnsoft.com>
 * @modified by sudhir <sudhir@hostnsoft.com>
 * @since 24-07-2013
 * @package Phone91
 * @details class use for all settings (phone,email setting). 
 */

include dirname(dirname(__FILE__)).'/config.php';

class contact_class extends fun{
        
    private $defaultEmail = 'ankitpatidar@hostnsoft.com';
    public $msg="";
    public $status="";
    public $code="";
    public $data=array();
    
    #modified by sudhir pandey (sudhir@hostnsoft.com)
    #modify date 24-07-2013
    #function use for get verify contact number form 91_contact table 
    function getConfirmMobile($userId)
	{
            #table name
	    $table = '91_verifiedNumbers';
	    #select query 
            $this->db->select('*')->from($table)->where("userId = '" . $userId . "'");
            $this->db->getQuery();
	    $result = $this->db->execute();
	    // processing the query result           
	    if ($result->num_rows > 0) {
		while($row= $result->fetch_array(MYSQLI_ASSOC)) {
		     $contact[] = $row;
		}
	    }
	    else
		$contact[] = 0;
	    return $contact ;
	}
    
    #modified by sudhir pandey (sudhir@hostnsoft.com)
    #modified date 23/07/2013
    #function use for get unconfirm mobile number    
    function getUnconfirmMobile($userId)
	{
            #table name 
            $table = '91_tempNumbers';
	    $this->db->select('*')->from($table)->where("userId = '" . $userId . "'");
            $this->db->getQuery();
	    $result = $this->db->execute();
	    // processing the query result  
           // var_dump($result);
	    if ($result->num_rows > 0) {
		while($row= $result->fetch_array(MYSQLI_ASSOC)) {
		     $contact = $row;
                     
		}
	    }
	    else
		$contact = 0;
            return $contact ;
	}
    
    
    //not in use
    function changeContact($request, $session){
	
	$code = $request['code'];
	$code = substr($code, 1, strlen($code) - 1);
	$phone = $request['mobileNumber'];			
	$confirm_code = $this->conf_code();
	$checkexist = $this->db->query("select * from contact where cntry_code='$code' and confirm='1' and contact_no='$phone'");
	if($checkexist->num_rows == 0){
	    $contact_confirmation = isset($_COOKIE[$phone])? ++$_COOKIE[$phone]: 1;
	    if($contact_confirmation < 4){
		$this->db->query("delete from tempcontact where userid='" . $session['id_cl'] . "'");
		$todayDate = date("Y-m-d");
		$this->db->query("insert into tempcontact values('" . $session['id_cl'] . "','$phone','','$code','$confirm_code',0,'$todayDate',5)");
		$flag = '1';
		$d["text"] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
		$d["to"] = $code . $phone;
		//for 91 user
		$nine[mobiles] = $phone;
		$nine[message] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
		//Call function
		$error = 0;
		$funobj = new fun();
		if ($code == "91"){
		    if($funobj->SendSMS91($nine) == 'code: 101'){
			 $error = 1;
		    }
		}else{
			if($funobj->SendSMSUSD($d) == 'error: 101'){
			    $error = 1;
			}
		}
		if($error){
		    $this->db->query("delete from tempcontact where userid='" . $session['id_cl'] . "'");
		    return 4;
		}else{
		    setcookie($phone, $contact_confirmation, time()+60*60*24);
		    return 1;
		}
	    }  else {
		 return 5;
	    }
	}else { //in case of number assign to another user
		$flag = '0';
		$get_details = $checkexist->fetch_array(MYSQLI_ASSOC);
		$userid = $get_details['userid'];
		if ($userid != $session['id_cl']) {
			$result = $this->db->query("select login from clientsshared where id_client='$userid'");
			$get_details = $result->fetch_array(MYSQLI_ASSOC);
			$username = $get_details['login'];
			return 2;
		} else {
			return 3;

		}
	}

        exit();
    }
    
    
    
    function conf_code($length = 4) {//Function use to generate confirmation code same as password
	// start with a blank password
	$conf_code = "";
	// define possible characters
	$possible = "0123456789";
	// set up a counter
	$i = 0;
	// add random characters to $password until $length is reached
	while ($i < $length) {
		// pick a random character from the possible ones
		$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
		// we don't want this character if it's already in the password
		if (!strstr($conf_code, $char)) {
			$conf_code .= $char;
			$i++;
		}
	}
	return $conf_code;
    }
    
    function deleteContact($phone, $user_id){
	if($this->db->query("DELETE FROM `91_tempNumber` WHERE tempNumber=".$phone." AND userId=".$user_id))
		return 1;
    }
    
    
    
        #modified by sudhir pandey (sudhir@hostnsoft.com)
        #modified date 25/07/2013
        #function use for get confirm email id    
	function getConfirmEmail($userId,$fields = NULL)
	{
	    $contact=array();
            if(is_null($fields))
                $fields = "*";
            $verifyEmail = '91_verifiedEmails'; 
            $this->db->select($fields)->from($verifyEmail)->where("userid = '" . $userId . "'");
            $result = $this->db->execute();
	    // processing the query result 
            if(!$result)
            {
                return false;
            }
	    else if ($result->num_rows > 0) {
		while($row= $result->fetch_array(MYSQLI_ASSOC)) {
		     $contact[] = $row;
		}
	    }
	    
	    return $contact ;
	}
        
        #modified by sudhir pandey (sudhir@hostnsoft.com)
        #modified date 25/07/2013
        #function use for get un verified email id    
	function getUnConfirmEmail($userId)
	{
	    $tempEmail = '91_tempEmails'; 
            $this->db->select('*')->from($tempEmail)->where("userid = '" . $userId . "'");
            $result = $this->db->execute();
	    // processing the query result           
	    if ($result->num_rows > 0) {
		while($row= $result->fetch_array(MYSQLI_ASSOC)) {
		     $contact = $row;
		}
	    }
	    else
		$contact = 0;
	    return $contact ;
	}
        
        #created by sudhir pandey (sudhir@hostnsoft.com)
        #creation date 23/07/2013
        #function use for verify email id of login user
        function verifyEmailid($parm,$userid){

            $key = $parm['key'];
            
            if($userid != NULL && preg_match(NOTNUM_REGX, $userid))
		return json_encode(array( "msg" => "Please Enter a valid user!","status" => "error" ));
            
            //validation for confirmation
            if (strlen($parm['key']) > 0 || !preg_match('/[^0-9]+/', $parm['key'])) 
            {
            #check for verification code is match or not 
                
            #- Last modified by nidhi<nidhi@walkover.in>
            #- modification - I aplied condition to check with domain reseller id also.
            $resellerIdServer = $this->getDomainResellerId($_SERVER['HTTP_HOST']);
                
             $resellerId = $this->getResellerId($userid);
            
            $temptable = '91_tempEmails';
	    
            $this->db->select('*')->from($temptable)->where("userid = '" . $userid . "' and confirm_code = '".$key."' and domainResellerId = '".$resellerId."'");
            
            
            
             $this->db->getQuery();
	    
            $result = $this->db->execute();
	    // processing the query result  
            // var_dump($result);
	    if ($result->num_rows <= 0){
                
                  return json_encode(array('msgtype' => 'error', 'msg' => 'Verification Code Mismatch !'));             
            
            } 
            else
            {
              #if verification code is match  then 
              while($row= $result->fetch_array(MYSQLI_ASSOC)) {
		   $email = $row['email'];
                   $newsFlag =$row['newsFlag'];
		}
               
            #check for duplicte entry of email id in verify email table     
            $table = '91_verifiedEmails';
	    $this->db->select('*')->from($table)->where("userid = '" . $userid . "' and confirm_code = '".$key."' and domainResellerId = '".$resellerIdServer."' ");
            $this->db->getQuery();
	    $result = $this->db->execute();
            
            if ($result->num_rows == 0){
                
                #data for insert verify email id into varifytable 
                $data=array("userid"=>(int)$userid,"email"=>$email,"confirm_code"=>$key,"newsFlag"=>$newsFlag, "domainResellerId" => $resellerIdServer); 

                #insert query (insert data into 91_verifiedEmails table )
                $this->db->insert($table, $data);	
                $this->db->getQuery();
                $result = $this->db->execute();
                

                #delete from temp email table 
                $this->db->delete($temptable)->where("userid = '" . $userid . "' and confirm_code = '".$key."'");
                $this->db->getQuery();
                $result = $this->db->execute();

                $confirmEmail = $this->getConfirmEmail($userid);
                return json_encode(array('msgtype' => 'success', 'msg' => 'Email Id Successfully Verified !',"confirmEmail"=>$confirmEmail));
             
                               
                                      
            }else 
                { //in case of number assign to another user
                 return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Email Id Is Already In  Use !'));
                }
            }
            
	}
        return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Varification Code !'));
    
        }
        
        
        
        #created by sudhir pandey (sudhir@hostnsoft.com)
        #creation date 23/07/2013
        #function use for update new contact no. of login user 
        function update_newcontact($country_code,$phone,$userid){

         if($userid != NULL && preg_match(NOTNUM_REGX, $userid))
		return json_encode(array( "msg" => "Please Enter a valid user!","status" => "error" ));
                
            
        #check country name is selected or not 
        if ($country_code == 'selectCountry') {
		return json_encode(array('msgtype' => 'error', 'msg' => 'Please Select Country'));
	}
        
        #check contact no is not null
        if($phone == '' || $phone == NULL){
            return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Contact Number !'));
        }
        
       #remove all 0 form starting of number 
       $phone = ltrim($phone,'0');
        
       if (!preg_match("/^[0-9]{8,15}$/", $phone)){  
           
       return json_encode(array("msgtype"=>"error","msg"=>"Contact Number Is Not Valid!"));
       }
       
       #remove space form country_code
       $country_code = preg_replace('/\s+/', '', $country_code);
       
       # generate confirmation code 
       if (!preg_match("/^[0-9]{1,7}$/", $country_code)){  
           
       return json_encode(array("msgtype"=>"error","msg"=>"Country Code Is Not Valid!"));
       }
       
        $confirm_code = $this->conf_code();

        
       
        //if number is not assign to any other user
        if ($this->checkNumberExist($country_code,$phone,$userid) == 1 ) {
             return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Number Is Already In  Use !'));
        }

            #only one unverify data store in database 
            #table name 
            $table = '91_tempNumbers'; 
            $this->db->select('*')->from($table)->where("userId = '" . (int)$userid . "'");
            $result = $this->db->execute();
            if ($result->num_rows > 0) {
                 return json_encode(array('msgtype' => 'error', 'msg' => 'First  Verify Your Unverified Contact Number !'));
             
            }
            
            #value for store in database 
            $data=array("userId"=>(int)$userid,"tempNumber"=>$phone,"countryCode"=>(int)$country_code,"confirmCode"=>$confirm_code,"date"=>date('Y-m-d H:i:s')); 

            
            #insert query (insert data into 91_tempcontact table )
            $this->db->insert($table, $data);	
            $sql=$this->db->getQuery();
            $result = $this->db->execute();
            //var_dump($result);

            if($result)
            {
                # Assign Variables for sending sms to user
                $d["text"] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
                $d["to"] = $country_code . $phone;
                //for 91 user
                $nine['mobiles'] = $phone;
                $nine['message'] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
                //Call function
                $funobj = new fun();
                if ($country_code == "91"){
                    $sendSmsResponse=$funobj->SendSMS91($nine);
                        if($sendSmsResponse == 'code: 101'){
                             $error = 1;
                        }
                }else{
                    $sendSmsResponse=$funobj->SendSMSUSD($d) ;
                        if($sendSmsResponse == 'error: 101'){
                            $error = 1;
                        }
                }
                $unverifiedContact = $this->getUnconfirmMobile($userid);
                $unverifiedContactArr['tempNumber'] = $unverifiedContact['tempNumber'];
                $unverifiedContactArr['countryCode'] = $unverifiedContact['countryCode'];
                if($error!=1){
		    
                    return json_encode(array('msgtype' => 'success', 'msg' => 'Contact Added Successfully! Please Enter Verification Code.',"unverifiedContact"=>$unverifiedContactArr));
                   // $delete = $this->db->query("delete from tempcontact where userid='" . $session['id_cl'] . "'");
                   }else{
                   return json_encode(array('msgtype' => 'success', 'msg' => 'Contact Added But Unable To Send SMS!',"response"=>$sendSmsResponse,"unverifiedContact"=>$unverifiedContactArr));
                }
            }
            else{
                echo $sql;
            }

        
        
  
       
}

#created by sudhir pandey <sudhir@hostnsoft.com>
#creation date 13-11-2013
#function use to check number already exist or not 
function checkNumberExist($country_code,$phone,$userId, $status = 0)
{
//    $resellerIdServer = $this->getDomainResellerId($_SERVER['HTTP_HOST']);
    $status = 0;    
    #table name 
    
    $resellerId = $this->getResellerId($userId);
     
    $condition = '';
    
    if(isset($_SESSION['ipCheck']) && $_SESSION['ipCheck'] == '1')
    $condition = ' and status= "1"  ';
    
    $userIdCon = '';
    if(!empty($userId))
        $userIdCon = '  and userId="'.$userId.'" ';
    
    $table = '91_verifiedNumbers'; 
    $this->db->select('*')->from($table)->where("countryCode = '" . $country_code . "' and verifiedNumber='".$phone."' and resellerId = '".$resellerId."'  ".$condition.' '.$userIdCon);
    
       $this->db->getQuery();
    
    $result = $this->db->execute();
   
    
    //if number is not assign to any other user
    if ($result->num_rows > 0) 
    {
        return 1;
    }
    return 0;
    
    return $status;    
}

private function validateCotactNumber($contactNumber)
{
    if(preg_match(NOTMOBNUM_REGX,$contactNumber) || strlen($contactNumber) < 7 || strlen($contactNumber) > 18){
        $this->msg = "Invalid contact number please provide a valid number";
        $this->status = "error";
        $this->code = "403";
        
        return false;
    }
    else 
        return true;
}

/**
 * @author sameer rathod
 * @param type $contactNumber
 * @param type $type
 * @return boolean
 */
function checkVerifiedNumberExist($contactNumber,$type,$resellerId)
{
//    $resellerIdServer = $this->getDomainResellerId($_SERVER['HTTP_HOST']);
//    $status = 0;    
    #table name 
    if(!$this->validateCotactNumber($contactNumber)){
        return false;
    }
       
    if(preg_match(NOTNUM_REGX,$resellerId) || empty($resellerId)){
        $this->msg = "Error Invalid user please try again";
        $this->status = "error";
        $this->code = "403";
        
        return false;
    }
    
    
    
//    $resellerId = $this->getResellerId($userId);
     
    $table = '91_verifiedNumbers'; 
    $coloumn = 'userId,domainResellerId,resellerId,countryCode,verifiedDate,verifiedNumber';
    $result = $this->selectData($coloumn,$table,"concat(countryCode,verifiedNumber) = '" . $contactNumber . "' and resellerId=".$resellerId);
//    $this->db->select('*')->from($table)->where("countryCode = '" . $country_code . "' and verifiedNumber='".$phone."' and resellerId = '".$resellerId."'");
//    $result = $this->db->execute();

    //if number is not assign to any other user
    if($result && $result->num_rows > 0) 
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        if($type == 1){
//            unset($row['confirmCode']);
            $this->data['userId'] = $row['userId'];
            $result = $this->getUserId($row['userId'],'1');
            if(!$result)
            {
                $this->msg = "Error unable to fetch user name";
                $this->status = "error";
                $this->code = "403";
                return false;
            }
            $this->data['userName'] = $result;
            return true;
        }
        elseif($type==2){
            
            $resultForAllVerifiedNumber = $this->selectData($coloumn,$table,"userId = '" . $row['userId'] . "' and resellerId=".$resellerId);
            while($rowOfNumbers = $resultForAllVerifiedNumber->fetch_array(MYSQLI_ASSOC))
            {
//                unset($rowOfNumbers['confirmCode']);
                $userIdArray[] = $rowOfNumbers['userId'];
                $data[$rowOfNumbers['userId']] = $rowOfNumbers;
            }
            $result = $this->getUserId($userIdArray,'2');
            foreach($data as $key => $value)
            {
                
                $data[$key]['userName'] = $result[$key];
            }
            $data = array_values($data);
            $this->data = $data;
             return true;

           
        }
        else{
            $this->msg = "Invalid type please provide a valid response type";
            $this->status = "error";
            $this->code = "403";
            //send mail to admin in thi case
            return false;
        }
    }
    $this->msg = "Number not found";
    $this->status = "error";
    $this->code = "404";
    return false;
   
}
/**
 * @author sameer rathod
 * @param type $contactNumber
 * @param type $type
 * @return boolean
 */
function checkVerifiedEmailExist($email,$resellerId)
{
//    $resellerIdServer = $this->getDomainResellerId($_SERVER['HTTP_HOST']);
//    $status = 0;    
    #table name 
    if(!$this->isValidEmail($email) || strlen($email) >40  ){
        $this->msg = "Invalid Email Id please provide a valid email";
        $this->status = "error";
        $this->code = "403";
        return false;
    }
            
    if(preg_match(NOTNUM_REGX,$resellerId) || empty($resellerId)){
        $this->msg = "Error Invalid user please try again";
        $this->status = "error";
        $this->code = "403";
        
        return false;
    }
    
//    $resellerId = $this->getResellerId($userId);
     
    $table = '91_verifiedEmails'; 
    $result = $this->selectData('*',$table,"email = '" . $email . "' and resellerId ='".$resellerId."'");
    
//    $this->db->select('*')->from($table)->where("countryCode = '" . $country_code . "' and verifiedNumber='".$phone."' and resellerId = '".$resellerId."'");
//    $result = $this->db->execute();

    //if number is not assign to any other user
    if($result && $result->num_rows > 0)
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $this->data['userId'] = $row['userid'];
        return true;
    }
    else{
        $this->msg = "Email not found";
        $this->status = "error";
        $this->code = "404";
        return false;
    }
}

public function addVerifiedEmailId($email,$userId,$resellerId,$domain=NULL)
{
//    $funobj = new fun();

    if(!$this->isValidEmail($email) || strlen($email) >40  ){
        $this->msg = "Invalid Email Id please provide a valid email";
        $this->status = "error";
        $this->code = "403";
        return false;
    }
    if(preg_match(NOTNUM_REGX,$userId) || empty($userId)){
        $this->msg = "Error invalid user please try with valid user";
        $this->status = "error";
        $this->code = "403";
            return false;
    }
    
    if(preg_match(NOTNUM_REGX,$resellerId) || empty($resellerId)){
        $this->msg = "Error unable to fetch reseller details";
        $this->status = "error";
        $this->code = "403";
            return false;
    }
    
    $isDefault = "0";
    
    #generate confirmation code
    $confirmCode = $this->generatePassword();
    
    if(is_null($domain))
        $domain = $_SERVER['HTTP_HOST'];
    /* will remove this in future after confirming that we have to remove the domain reseller coloumn also*/
    $domainResellerId = $this->getDomainResellerIdViaApc($domain,1);
    if(!$domainResellerId)
    {
        $this->msg = "Error unable to fetch reseller domain details";
        $this->status = "error";
        $this->code = "403";
        return false;
    }
    $checkEmailResult = $this->checkVerifiedEmailExist($email,$resellerId);
   
    if($checkEmailResult)
    {
        $this->msg = "Error Email Id alredy exist please try again with different email Id";
        $this->status = "error";
        $this->code = "403";
        return false; 
    }
    
    $result = $this->getConfirmEmail($userId);
     
    
    if(!$result)
    {
        $this->msg = "Error unable to fetch the details please try again";
        $this->status = "error";
        $this->code = "403";
        return false;
    }
    
    if(empty($result)){
        $isDefault = "1";
    }
    
    $insertArray = array(
        "userid" => $userId,
        "email" => $email,
        "confirm_code" => $confirmCode,
        "default_email" => $isDefault,
        "date" => "now()",
        "resellerId" => $resellerId,
        "domainResellerId" => $domainResellerId
    );
    $result = $this->insertData($insertArray, "91_verifiedEmails");
    
    if(!$result)
    {
        $this->msg = "Error unable to fetch the details please try again";
        $this->status = "error";
        $this->code = "403";
        return false;
        
    }
    else
        return true;
    
}





#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25/07/2013
#function use for add new email id of login user 
function addnew_emailid($new_emailid, $userid  ) {

    $funobj = new fun();
    
    if($userid != NULL && preg_match(NOTNUM_REGX, $userid))
		return json_encode(array( "msg" => "Please Enter a valid user!","status" => "error" ));
    
    #generate confirmation code
    $confirm_code = $funobj->generatePassword();

    //check for valid length
    if (strlen($new_emailid) > 40) 
    {
         return json_encode(array('msgtype' => 'error', 'msg' => 'The Maximum length for email is 40 character!!!'));
    }
    
    
    #check email id is valid or not 
    if (!$funobj->isValidEmail($new_emailid)) 
    {
         return json_encode(array('msgtype' => 'error', 'msg' => 'This Is Not Valid Email.'));
    }
    
    
    
    if(isset( $_SESSION['resellerId'] ) && !empty($_SESSION['resellerId']))
    {
        $resellerId = $_SESSION['resellerId'];
    }
    else
    {
        $resellerId = $this->getDomainResellerId($_SERVER['HTTP_HOST']);
    }
    
    
    //echo ' json _ encode '.json_encode($_SESSION['resellerId']);
    
    
    #table name 
    $verifyEmail = '91_verifiedEmails';
    $this->db->select('*')->from($verifyEmail)->where("email = '" . $new_emailid . "' and resellerId='".$resellerId."' ");
    $result = $this->db->execute();

    //if email id is not assign to any other user
    if ($result->num_rows > 0) 
    {
         return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Email Id Is Already In Use .'));
    }
        
    $tempEmail = '91_tempEmails';
    $this->db->select('*')->from($tempEmail)->where("email = '" . $new_emailid . "' and resellerId = '".$resellerId."' ");
    $result = $this->db->execute();

    #check for email id is already used or not
    if ($result->num_rows == 0) 
    {
        #- Last Modified By nidhi<nidhi@walkover.in>
        #- modification- finding domain reseller id from database and setting to verified email table.
        $resellerIdServer = $this->getDomainResellerId($_SERVER['HTTP_HOST']);
        #- value for store in database 
        $data = array("userid" => (int) $userid, "email" => $new_emailid, "confirm_code" => $confirm_code, "date" => date('Y-m-d H:i:s') , 'domainResellerId' => $resellerIdServer , "resellerId" => $resellerId  );
        #- modification ends.
        
        #insert query (insert data into 91_tempEmails table )
        $this->db->insert($tempEmail, $data);
        
        $savedata = $this->db->execute();


        if ($savedata) 
        {
            $sentmail = $funobj->send_verification_mail($new_emailid, $confirm_code);
            $unverifiedEmail = $this->getUnConfirmEmail($userid);
            if ($sentmail) 
            {
                return json_encode(array('msgtype' => 'success', 'msg' => 'Confirmation Link Has Been Sent To Your Email Address.',"unverifiedEmail"=>$unverifiedEmail));
            }
            else
                return json_encode(array('msgtype' => 'error', 'msg' => 'Not Possible To Send Mail.'));
        }
        else
        {
             return json_encode(array('msgtype' => 'error', 'msg' => 'Something went wrong please try again'));
        }
    }
    else
        return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Email Id Is Already In Use !'));
}
        
function checkverifiedNoExist($userid , $countryCode, $tempNumber )
{
           
   
    if(preg_match(NOTUSERNAME_REGX,$userId ))
        {
            $_SESSION['error'] = "user name is not valid!";
            return 0;
        }
       
        if(!is_numeric($countryCode))
            return 0;
        
        if(preg_match(NOTNUM_REGX,$tempNumber))
                return 0;
        
        include_once $_SERVER['DOCUMENT_ROOT'] . '/function_layer.php';
        $funObj = new fun();
        
        
         $result = $funObj->selectData('count(*)',"91_verifiedNumbers","userId='".$userid."'  and verifiedNumber= '".$tempNumber."' and countryCode='".$countryCode."' ");
        
         
         
         $verified = 0;
        
        if( $result->num_rows > 0 ) 
        {	
            while($row = $result->fetch_array(MYSQL_ASSOC) ) 
            {
               $verified = $row['count(*)'];
}
        }

        return $verified;
}
#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 23/07/2013
#function use for verify mobile number of login user
function verifyNumber($parm,$userid,$type = NULL){
    
        $key = $parm['key'];
        
        if($userid != NULL && preg_match(NOTNUM_REGX, $userid))
		return json_encode(array( "msg" => "Please Enter a valid user!","status" => "error" ));
        
        #validation for confirmation
	if (strlen($parm['key']) > 0 || !preg_match('/[^0-9]+/', $parm['key'])) 
        {
            
            #This query check for contact information in contact and temp - contact
            $temptable = '91_tempNumbers';
	    
            $this->db->select('*')->from($temptable)->where("userId = '" . $userid . "' and confirmCode = '".$key."'");
            
           //$this->db->select('*')->from($temptable)->where("userId = '" . $userid . "'");
            
             $this->db->getQuery();
	    $result = $this->db->execute();
	    if ($result->num_rows <= 0){
                  return json_encode(array('msgtype' => 'error', 'msg' => 'Verification Code Mismatch !'));             
            } 
            else
            {
              while($row= $result->fetch_array(MYSQLI_ASSOC)) {
                    $countryCode = $row['countryCode'];
                    $tempNumber = $row['tempNumber'];
		}
               
            if ($this->checkNumberExist($countryCode,$tempNumber,$userid) == 1 ) {
             return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Number Is Already In Use !'));
                }
            $table = '91_verifiedNumbers'; 
            #check no any verified number in table if yes then set as a default no .    
            $this->db->select('*')->from($table)->where("userId = '" . $userid . "'"); //userId = '" . $userid . "' and 
            $this->db->getQuery();
	    $result = $this->db->execute();
             if ($result->num_rows > 0){
                 $isdefault = 0;
             }else $isdefault = 1;
             
             if(isset($_SESSION['domain']))
                $domainResellerId = $this->getDomainResellerId($_SESSION['domain']);
                else
                $domainResellerId = $this->getDomainResellerId($_SERVER['HTTP_HOST']); 
            
            $resellerId = $this->getResellerId($userid);
                
            
             if( $this->checkverifiedNoExist($userid , $countryCode, $tempNumber ))
                {
                    $verifiedNoStatus = array("verifiedDate" => date('Y-m-d H:i:s') , "confirmCode" => $key );
                    $this->db->update($table , $verifiedNoStatus)->where( "userId = '" . $userid . "' and verifiedNumber = '".$tempNumber."' and countryCode='".$countryCode."' ");	
                    $this->db->getQuery();
                    $updateresult = $this->db->execute();
                }
                else 
                {
            $data = array( "userId" => (int)$userid, 
                            "countryCode"=>(int)$countryCode,
                            "verifiedNumber"=>$tempNumber,
                            "isDefault"=>$isdefault,
                            "verifiedDate"=>date('Y-m-d H:i:s'),
                            "domainResellerId"=>$domainResellerId,
                            "confirmCode" => $key , "resellerId" => $resellerId ); 
            
            #insert query (insert data into 91_tempcontact table )
            $this->db->insert($table, $data);	
            $query= $this->db->getQuery();
            $result = $this->db->execute();

            if(!$result){
                logmonitor('phone-ankit', 'insertdata:'.json_encode($data).' query:'.$query);
                trigger_error('insertdata:'.json_encode($data).' query:'.$query);
                return json_encode(array('msgtype' => 'error', 'msg' => 'number not verified  please contact your admin !'));
            }
            
                }
            
            /*
                 * 
                 */
                    $verifiedNoStatus = array("status" => 1);
                    $this->db->update($table , $verifiedNoStatus)->where("userId = '" . $userid . "'");	
                    $this->db->getQuery();
                    $updateresult = $this->db->execute();

                /*
                 * 
                 */  
            
            
            
            
            #delete from temp contact 
            $this->db->delete($temptable)->where("userId = '" . $userid . "' and confirmCode = '".$key."'");
            $this->db->getQuery();
	    $deleteResult = $this->db->execute();
            $confirmNo = $this->getConfirmMobile($userid);
            
            if($isdefault == 1){
               
            $personaltable = '91_personalInfo';    
            $personalContactNo = $countryCode.$tempNumber;
            $personalCnt = array("contactNo"=>$personalContactNo); 
            
            #insert query (insert data into 91_tempcontact table )
            $this->db->update($personaltable, $personalCnt)->where("userId = '" . $userid . "'");	
            $this->db->getQuery();
            $updateresult = $this->db->execute();
            }
           
            if($result){
                
                if($type == 1)
                {
//                    if(isset($_SESSION['domain'])){
//                    $updData = array("beforeLoginFlag"=>2);
//                    }
//                    else{
//                    $updData = array("beforeLoginFlag"=>2);  
//                    }
                    
                    
                    $updData = array("beforeLoginFlag"=>2);  
                    
                    $updRes = $this->updateData($updData,"91_userLogin","userId='".$userid."'");
                   
                    if(!$updRes)
                    {
                        logmonitor('phone-ankit','problem in update before login flag while signup,query:'.$this->querry);
                        $this->sendErrorMail($this->defaultEmail,'problem in update before login flag while signup,query:'.$this->querry);
                        //mail("sameer@hostnsoft.com","update query fails",__FILE__."".__FUNCTION__);
                    }
                    $_SESSION['loginFlag'] = 2;
                }
                return json_encode(array('msgtype' => 'success', 'msg' => 'Number Successfuly Verified !',"confirmNo"=>$confirmNo));
            }
                               
                                      
//            }else 
//                { //in case of number assign to another user
//                 return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Number Is Already In Use !'));
//                }
            }
            
	}
        return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Varification Code !'));
    
}
#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 24-07-2013
#function use for make defaul number 
function makeDefaultNumber($parm,$userid){
    #table name 
    $table = '91_verifiedNumbers';
    $data=array("isDefault"=>0); 

    $this->db->update($table, $data)->where("userId = '" . $userid . "' and isDefault =1");
    $this->db->getQuery();
    $result = $this->db->execute();
    
    
    $table = '91_verifiedNumbers';
    $data=array("isDefault"=>1); 

    $this->db->update($table, $data)->where("varifiedNumber_id = '" . (int)$parm['contactId']."'");
    $this->db->getQuery();
    $result = $this->db->execute();
    
    
    
    #get country code and tempnumber from varified number table 
    $this->db->select('*')->from($table)->where("varifiedNumber_id = '" . (int)$parm['contactId']."'");
    $cntResult = $this->db->execute();

    //if number is not assign to any other user
    if ($cntResult->num_rows > 0) {

    $row = $cntResult->fetch_array(MYSQLI_ASSOC);
    $countryCode = $row['countryCode'];
    $tempNumber = $row['verifiedNumber'];
    
    }
    
    $personaltable = '91_personalInfo';    
    $personalContactNo = $countryCode.$tempNumber;
    $personalCnt = array("contactNo"=>$personalContactNo); 

    #insert query (insert data into 91_tempcontact table )
    $this->db->update($personaltable, $personalCnt)->where("userId = '" . $userid . "'");	
    $this->db->getQuery();
    $updateresult = $this->db->execute();
    
    return json_encode(array('msgtype' => 'success', 'msg' => 'Contact Number set as a Default Number !'));
    
}


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25-07-2013
#function use for make defaul email 
function makeDefaultemail($parm,$userid){
    
    $table = '91_verifiedEmails';
    $data=array("default_email"=>0); 

    $this->db->update($table, $data)->where("userid = '" . $userid . "' and default_email =1");
    $this->db->getQuery();
    $result = $this->db->execute();
    
    
    $data=array("default_email"=>1); 

    $this->db->update($table, $data)->where("verifiedEmail_id = '" . (int)$parm['emailId']."'");
    $this->db->getQuery();
    $result = $this->db->execute();
    
    
    return json_encode(array('msgtype' => 'success', 'msg' => 'Email id set as a Default email !'));
    
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25-07-2013
#function use for resend confirm code to uesr 
function resendConfirm_code($parm,$userid){
    
    if($userid != NULL && preg_match(NOTNUM_REGX, $userid))
		return json_encode(array( "msg" => "Please Enter a valid user!","status" => "error" ));
    
    # get unconfirm mobile number    
    $contact = $this->getUnconfirmMobile($userid);
    
    #save record of resend confirm code 
    
    #table name 
    $table = '91_resendCode';
    #type 1 for phone and 2 for email
    $data=array("type"=>1,"userid"=>(int)$userid,"date"=>date('Y-m-d'),"resend_by"=>1); 

    #insert query (insert data into 91_tempcontact table )
    $this->db->insert($table, $data);	
    $this->db->getQuery();
    $result = $this->db->execute();
    //var_dump($result);
    
    #find total time of resend confirm code and it is not more then 5 times
    $this->db->select('*')->from($table)->where("userid = '" . $userid . "' and type = 1 and resend_by = 1 and date = '".date('Y-m-d')."'");
    $this->db->getQuery();
    $result = $this->db->execute();
    // processing the query result  
   // var_dump($result);
    if ($result->num_rows > 5){

          return json_encode(array('status' => 'error', 'msg' => 'You have reached the maximum SMS limit for today. Please try again tomorrow or contact Support!'));             
//               
       } 
    
  /**********   more then 5 resend   
    //get current date
    $currentDate = date("Y-m-d");
    
    //compare current date with database date,update date if not equal
    if(strtotime($ddate) != strtotime($currentDate) )
    {
        $updateDateCount = "UPDATE tempcontact set count = 5,date='$currentDate' WHERE userid='" . $_SESSION['id_cl'] . "'";
        mysql_query($updateDateCount);
        $count = 5;
    }
   
    //check count
    if($count > 0)
    {
        //get country code and mobile num
        $conCode = $_POST['country_code'];
        $resendMob = $_POST['resend_phone'];
        //query to update count
        $queryForUpdateCount = "UPDATE tempcontact set count = count-1 WHERE cntry_code=$conCode AND contact_no = $resendMob" ;
       
        mysql_query($queryForUpdateCount) or die(mysql_error());
        $count = $count -1;
  */  
    
        //code to resend code
       if (strlen($contact['confirmCode']) > 0)//$_POST['country_code'] == $contact['countryCode'] && $_POST['resend_phone'] == $contact['tempNumber'] && 
       {
		$d["message"] = "Enter this confirmation code " . $contact['confirmCode'] . " to confirm your mobile number."; // sms text
		
		$d["sender"]="Phonee";					
                $d["mobiles"]=$contact['countryCode'] . $contact['tempNumber'];
                //for 91 user
                
		$nine[mobiles] = $contact['countryCode'] . $contact['tempNumber'];
		$nine[message] = "Enter this confirmation code " . $contact['confirmCode'] . " to confirm your mobile number."; // sms text
		$nine[sender] = "Phonee";
		//Call function
                 $funobj = new fun();
                 
                include_once(CLASS_DIR."sendSmsClass.php");
                $smsObj = new sendSmsClass();
                
                #- Parameters for message function.
                
                $param['to'] =   $contact['countryCode'] . $contact['tempNumber'];
                $param['text'] = "Enter this confirmation code " . $contact['confirmCode'] . " to confirm your mobile number.";
                
		$smsObj->sendMessagesGlobal($param);
                
		return json_encode(array('status' => 'success', 'msg' => 'Confirmation Code Send'));    
                
		
	}
    
    
    
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25-07-2013
#function use for resend confirm code to uesr by call
function callmeConfirm_code($parm,$userid){
   
   
    #table name 
    $table = '91_resendCode';
    #type 1 for phone and 2 for email
    $data=array("type"=>1,"userid"=>(int)$userid,"date"=>date('Y-m-d'),"resend_by"=>2); 

    #insert query (insert data into 91_tempcontact table )
    $this->db->insert($table, $data);	
    $this->db->getQuery();
    $result = $this->db->execute();
    
    
    #find total time of resend confirm code and it is not more then 5 times
    $this->db->select('*')->from($table)->where("userid = '" . $userid . "' and type = 1 and resend_by = 2 and date = '".date('Y-m-d')."'");
    $this->db->getQuery();
    $result = $this->db->execute();
    // processing the query result  
   
    if ($result->num_rows > 5){
       return json_encode(array('msgtype' => 'error', 'msg' => 'you have riched max resend limit for today pelase try again tommorow or contact support  !'));             
    } 
  
    //code to resend voice

    $contact = $this->getUnconfirmMobile($userid);

    if (strlen($contact['confirmCode']) > 0)  //$_POST['country_code'] == $contact['countryCode'] && $_POST['resend_voice'] == $contact['tempNumber'] && 
    {
            $mobile_no = $contact['countryCode'] . $contact['tempNumber'];
            $vcode = $contact['confirmCode'];
            $this->mobile_verification_api($mobile_no, $vcode);
            return json_encode(array('status' => 'success', 'msg' => 'Success please wait for the call and enter you confirmation code'));    

    }
    
    
    
}

/**
 * created by Balachandra Hegde<balachandra@hostnsoft.com>
*last updated by ankit Patidar <ankitpatidar@hostnsoft.com> on 28/8/2014 change validation validation
*date 02/08/2013
*function used to delete the  verified email address
 * @param int $emailId unique id in verifiedEmail table
 */

function deleteEmailId($emailid,$userId)
  {
    
     if (preg_match(NOTNUM_REGX,$emailid)) 
     {
        return json_encode(array("msgtype" => "error","msg" => "email id is not valid !"));
     }
     
     if (!preg_match("/^[0-9]+$/",$userId))
     {
        return json_encode(array("msgtype" => "error","msg" => "User Id not valid."));
     }    
    
     $emailid = trim($emailid);
     $userId = trim($userId);
    #action to be taken in this table=91_verifiedEmails in database
    $table='91_verifiedEmails';
    
    $this->db->select('*')->from($table)->where("userid = '" . $userId . "' and verifiedEmail_id  = '".$emailid."' and default_email = 1 ");
    $result = $this->db->execute();
    if ($result->num_rows > 0){

          return json_encode(array('msgtype' => 'error', 'msg' => 'you can not delete default email !'));             
    } 
    
                    
    #delete the data from table 91_verifiedEmails if userid and id of mail address matched
    $this->db->delete($table)->where("userid = '" .$userId."' and  verifiedEmail_id = '".$emailid."'");
    $this->db->getQuery();
    #execute the query
    $result = $this->db->execute();
    
    #if query executed successfully
    if($result)
    {
        #display the message deleted successfully
        return json_encode(array('msgtype'=>'success','msg'=>'Deleted Successfully'));

    }

    else
    {
        #diplay the error message.
        return json_encode(array('msgtype'=>'error','msg'=>'Not Possible To Delete'));

     }
    
  }

#created by Sameer Rathod<sameer@hostnsoft.com>
#modified by sudhir pandey 02/09/2013
#date 02/09/2013
#function used to fetched the default verified phone number   
function getUserDefaultNumber($userId , $type = 0)
{
    #name of the table on which action to be taken 
    $table='91_verifiedNumbers';
    #find total time of resend confirm code and it is not more then 5 times
    $this->db->select('*')->from($table)->where("userId = '" . $userId . "' and isDefault = 1 ");
    $result = $this->db->execute();
    if($result->num_rows > 0)
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        
        if($type)
            return $row['countryCode'].$row['verifiedNumber'];
        else
        return array("number" => $row['verifiedNumber'] , "countryCode" => $row['countryCode'] ); //$row['countryCode'].
    }
    else
        return 0;
}

#created by Balachandra Hegde<balachandra@hostnsoft.com>
#modified by sudhir pandey 19/08/2013
#date 02/08/2013
#function used to delete the verified phone number   
function deletephone($contactid,$userId,$status = NULL,$resellerId = NULL)
{
    if (!preg_match("/^[0-9]+$/", $contactid)) {
             return json_encode(array('msgtype'=>'error','msg'=>'Contact id is not valid.'));  
        }
    if (!preg_match("/^[0-9]+$/", $userId)) {
             return json_encode(array('msgtype'=>'error','msg'=>'User id is not valid.'));  
        }
    
    
   // echo $contactid;
    #name of the table on which action to be taken 
    $table='91_verifiedNumbers';
    #find total time of resend confirm code and it is not more then 5 times
    
    if($status == 1)
    {
        $this->db->select('*')->from($table)->where("userId = '" . $userId . "' and domainResellerId  = '".$resellerId."' and  isDefault = 1 and CONCAT(countryCode,verifiedNumber)= '".$contactid."'");
    }
    else 
    {
        $this->db->select('*')->from($table)->where("userId = '" . $userId . "' and varifiedNumber_id  = '".$contactid."' and isDefault = 1 ");
    }
    
    $result = $this->db->execute();
    
    if ($result->num_rows > 0)
    {
        return json_encode(array('msgtype' => 'error', 'msg' => 'you can not delete default contact number !'));             
    } 
                    
    #delete the data from table 91_verifiednumbers hwere userid and conatid matches
    
    if($status == 1)
    {
         $this->db->delete($table)->where("userId = '" . $userId . "' and domainResellerId  = '".$resellerId."' and CONCAT(countryCode,verifiedNumber)= '".$contactid."'");
         
        // echo"userId = '" . $userId . "' and domainResellerId  = '".$resellerId."' and CONCAT(countryCode,verifiedNumber)= '".$contactid."'";
    }
    else 
    {
        $this->db->delete($table)->where("userId = '" .$userId."' and  varifiedNumber_id  = '".$contactid."'"); 
    }
   

    #execute the query
    $result = $this->db->execute();
    
    #if query executed successfully then
    if($result)
    {
        #dispaly success message
        return json_encode(array('msgtype'=>'success','msg'=>'Deleted Successfully'));
    }
    else
    {
        #display error message
        return json_encode(array('msgtype'=>'error','msg'=>'Not Possible To Delete'));  
    }
                        
    
}

   
   
   
#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 02/08/2013
#function used to delete the unverified phone number    
function deleteunverifyphone($tempid,$userId)
   {
    #name of the table on which action to be taken 
    $tabl='91_tempNumbers';
    
                    
    #delete the data from table 91_tempNumbers hwere userid and tempid matches
    $this->db->delete($tabl)->where("userId = '" .$userId."' and   tempNumber  = '".$tempid."'");
    $this->db->getQuery();

    #execute the query
    $result = $this->db->execute();
    
    #if query executed successfully then
    if($result)
    {
        #dispaly success message
        return json_encode(array('msgtype'=>'success','msg'=>'Deleted Successfully'));

    }
    
   
    else{
        
        #display error message
        return json_encode(array('msgtype'=>'error','msg'=>'Not Possible to Delete This Number'));
        
        }
                        
    
   }
#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 02/08/2013
#function used to delete the unverified phone number       
function deleteUnverifyEmail($unverifyemail,$userId)
 {
    #name of the table on which action to be taken 
    $table='91_tempEmails';
    
                    
    #delete the data from table 91_tempNumbers hwere userid and tempid matches
    $this->db->delete($table)->where("userId = '" .$userId."' and   email = '".$unverifyemail."'");
    $this->db->getQuery();

    #execute the query
    $result = $this->db->execute();
    
    #if query executed successfully then
    if($result)
    {
        #dispaly success message
        return json_encode(array('msgtype'=>'success','msg'=>'Deleted Successfully'));

    }
    
   
    else{
        
        #display error message
        return json_encode(array('msgtype'=>'error','msg'=>'Not Possible To Delete'));
        
        }
                        
    
   }
   
   
#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25-07-2013
#function use for resend email confirm code to uesr 
function resendEmailConfirm_code($parm,$userid){
    
    # get unconfirm email id     
    $contact = $this->getUnConfirmEmail($userid);
    
    #save record of resend confirm code 
    
    #table name 
    $table = '91_resendCode';
    #type 1 for phone and 2 for email
    $data=array("type"=>2,"userid"=>(int)$userid,"date"=>date('Y-m-d'),"resend_by"=>3); 

    #insert query (insert data into 91_resendcode table )
    $this->db->insert($table, $data);	
    $this->db->getQuery();
    $result = $this->db->execute();
    //var_dump($result);
    
    #find total time of resend confirm code and it is not more then 5 times
    $this->db->select('*')->from($table)->where("userid = '" . $userid . "' and type = 2 and resend_by = 3 and date = '".date('Y-m-d')."'");
    $this->db->getQuery();
    $result = $this->db->execute();
    // processing the query result  
   // var_dump($result);
    if ($result->num_rows > 50){

          return json_encode(array('msgtype' => 'error', 'msg' => 'you have riched max resend limit for today pelase try again tomorrow or contact support  !'));             
//               
       } 
    
 
       $funobj = new fun();
       
       $sentmail = $funobj->send_verification_mail($contact['email'], $contact['confirm_code']);
                        if ($sentmail) {
                            return json_encode(array('msgtype' => 'success', 'msg' => 'Confirmation Link Has Been Sent To Your Email Address.'));
                        }
                        else
                            return json_encode(array('msgtype' => 'error', 'msg' => 'Not Possible To Send Mail.'));
                    
  
}

/**
 *@author sudhir pandey <sudhir@hostnsoft.com>
 *@since 27-12-2013
 *@description function use to delete all unverify number of user
 */
function deleteOldTempNumber($userId){
   
    $tabl='91_tempNumbers';
    $userId = $this->db->real_escape_string($userId);
    #delete the data from table 91_tempNumbers were userid matches
    $this->db->delete($tabl)->where("userId = '" .$userId."'");
    $this->db->getQuery();

    #execute the query
    $result = $this->db->execute();
    
    #if query executed successfully then
    if($result)
    {
        #dispaly success message
        return json_encode(array('status'=>'success','msg'=>'Deleted Successfully'));
    }
    else{
        #display error message
        return json_encode(array('status'=>'error','msg'=>'Not Possible to Delete This Number'));
        }
    
}

/**
 * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
 * @since 18/03/2014
 * @param int $userId user unique id to get verified email
 * @filesource
 *  
 */
function getOneVerifiedEmail($userId)
{
    if(empty($userId) || !is_numeric($userId))
       return json_encode(array('status' => 0,'msg' => 'Invalid user Id!!!'));
   
    $verifyEmail = '91_verifiedEmails'; 
    $this->db->select('*')->from($verifyEmail)->where("userid = '" . $userId . "' and default_email=1");
    $result = $this->db->execute();
    // processing the query result           
    if ($result->num_rows > 0) 
    {
        while($row= $result->fetch_array(MYSQLI_ASSOC)) 
        {
             $verEmail = $row['email'];
        }
        return json_encode(array('status' => 1,'msg' => 'Default Email Found!!!' ,'email' => $verEmail));
    }
    
    $this->db->select('*')->from($verifyEmail)->where("userid = '" . $userId . "'")->limit(1);
    $result = $this->db->execute();
    // processing the query result           
    if ($result->num_rows > 0) 
    {
        while($row= $result->fetch_array(MYSQLI_ASSOC)) 
        {
             $verEmail = $row['email'];
        }
        return json_encode(array('status' => 1,'msg' => 'Email Found!!!' ,'email' => $verEmail));
    }
    else
        return json_encode(array('status' => 0,'msg' => 'Email not Found!!!'));
}

}
?>
