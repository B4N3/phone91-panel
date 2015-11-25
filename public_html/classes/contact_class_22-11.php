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
		while($row= $result->fetch_array(MYSQL_ASSOC)) {
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
		while($row= $result->fetch_array(MYSQL_ASSOC)) {
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
		$get_details = $checkexist->fetch_array(MYSQL_ASSOC);
		$userid = $get_details['userid'];
		if ($userid != $session['id_cl']) {
			$result = $this->db->query("select login from clientsshared where id_client='$userid'");
			$get_details = $result->fetch_array(MYSQL_ASSOC);
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
	    if ($result->num_rows > 0) {
		while($row= $result->fetch_array(MYSQL_ASSOC)) {
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
		while($row= $result->fetch_array(MYSQL_ASSOC)) {
		     $contact = $row;
		}
	    }
	    else
		$contact = 0;
	    return $contact ;
	}
        
        #created by sudhir pandey (sudhir@hostnsoft.com)
        #creation date 23/07/2013
        #function use for verify mobile number of login user
        function verifyEmailid($parm,$userid){

            $key = $parm['key'];
            //validation for confirmation
            if (strlen($parm['key']) > 0 || !preg_match('/[^0-9]+/', $parm['key'])) 
            {
            #check for verification code is match or not 
            $temptable = '91_tempEmails';
	    $this->db->select('*')->from($temptable)->where("userid = '" . $userid . "' and confirm_code = '".$key."'");
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
              while($row= $result->fetch_array(MYSQL_ASSOC)) {
		   $email = $row['email'];
                   
		}
               
            #check for duplicte entry of email id in verify email table     
            $table = '91_verifiedEmails';
	    $this->db->select('*')->from($table)->where("userid = '" . $userid . "' and confirm_code = '".$key."'");
            $this->db->getQuery();
	    $result = $this->db->execute();
            
            if ($result->num_rows == 0){
                
                #data for insert verify email id into varifytable 
                $data=array("userid"=>(int)$userid,"email"=>$email,"confirm_code"=>$key); 

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
function checkNumberExist($country_code,$phone,$userId){
    
    $resellerId = $this->getResellerId($userId);
    $status = 0;
    
    #table name 
    $table = '91_verifiedNumbers'; 
    $this->db->select('*')->from($table)->where("countryCode = '" . $country_code . "' and verifiedNumber='".$phone."'");
    $result = $this->db->execute();

    //if number is not assign to any other user
    if ($result->num_rows > 0) {

         while($row = $result->fetch_array(MYSQL_ASSOC)) {
		     $anotherUserId = $row['userId'];
                     $anotherResellerId = $this->getResellerId($anotherUserId);
                     if($anotherResellerId == $resellerId){
                         $status = 1;
                         break;
                     }
		}

    }
    return $status;
    
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25/07/2013
#function use for add new email id of login user 

function addnew_emailid($new_emailid, $userid) {

    $funobj = new fun();
    
    #generate confirmation code
    $confirm_code = $funobj->generatePassword();

    #check email id is valid or not 
    if (!$funobj->isValidEmail($new_emailid)) {
         return json_encode(array('msgtype' => 'error', 'msg' => 'This Is Not Valid Email.'));
    }
    
        #table name 
        $verifyEmail = '91_verifiedEmails';
        $this->db->select('*')->from($verifyEmail)->where("email = '" . $new_emailid . "'");
        $result = $this->db->execute();

        //if email id is not assign to any other user
        if ($result->num_rows > 0) {
             return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Email Id Is Already In Use .'));
        }
        
            $tempEmail = '91_tempEmails';
            $this->db->select('*')->from($tempEmail)->where("email = '" . $new_emailid . "'");
            $result = $this->db->execute();

            #check for email id is already used or not
            if ($result->num_rows == 0) {
                #value for store in database 
                $data = array("userid" => (int) $userid, "email" => $new_emailid, "confirm_code" => $confirm_code, "date" => date('Y-m-d H:i:s'));


                #insert query (insert data into 91_tempEmails table )
                $this->db->insert($tempEmail, $data);
                $this->db->getQuery();
                $savedata = $this->db->execute();


                if ($savedata) {
                    $sentmail = $funobj->send_verification_mail($new_emailid, $confirm_code);
                    $unverifiedEmail = $this->getUnConfirmEmail($userid);
                    if ($sentmail) {
                        return json_encode(array('msgtype' => 'success', 'msg' => 'Confirmation Link Has Been Sent To Your Email Address.',"unverifiedEmail"=>$unverifiedEmail));
                    }
                    else
                        return json_encode(array('msgtype' => 'error', 'msg' => 'Not Possible To Send Mail.'));
                }
            }
            else
                return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Email Id Is Already In Use !'));
        
           
   
       
}


#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 23/07/2013
#function use for verify mobile number of login user
function verifyNumber($parm,$userid){
    
    $key = $parm['key'];
  //validation for confirmation
	if (strlen($parm['key']) > 0 || !preg_match('/[^0-9]+/', $parm['key'])) 
        {
            //This query check for contact information in contact and temp - contact
            $temptable = '91_tempNumbers';
	    $this->db->select('*')->from($temptable)->where("userId = '" . $userid . "' and confirmCode = '".$key."'");
            $this->db->getQuery();
	    $result = $this->db->execute();
	    // processing the query result  
           // var_dump($result);
	    if ($result->num_rows <= 0){
                
                  return json_encode(array('msgtype' => 'error', 'msg' => 'Verification Code Mismatch !'));             
//               
            } 
            else
            {
              while($row= $result->fetch_array(MYSQL_ASSOC)) {
//		    $confirm = $row['confirm'];
                    $countryCode = $row['countryCode'];
                    $tempNumber = $row['tempNumber'];
                     
		}
               
            if ($this->checkNumberExist($countryCode,$tempNumber,$userid) == 1 ) {
             return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry This Number Is Already In Use !'));
                }
            $table = '91_verifiedNumbers'; 
            #check for first varify no or not if first varify no. then set as a default no .    
            $this->db->select('*')->from($table)->where("userId = '" . $userid . "'"); //userId = '" . $userid . "' and 
            $this->db->getQuery();
	    $result = $this->db->execute();
             if ($result->num_rows > 0){
                 $isdefault = 0;
             }else $isdefault = 1;
                
            $data=array("userId"=>(int)$userid,"countryCode"=>(int)$countryCode,"verifiedNumber"=>$tempNumber,"isDefault"=>$isdefault,"verifiedDate"=>date('Y-m-d H:i:s')); 
            
            #insert query (insert data into 91_tempcontact table )
            $this->db->insert($table, $data);	
            $this->db->getQuery();
            $result = $this->db->execute();
//            var_dump($result);
            
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
             return json_encode(array('msgtype' => 'success', 'msg' => 'Number Successfuly Verified !',"confirmNo"=>$confirmNo));
            }else
            {
             return json_encode(array('msgtype' => 'error', 'msg' => 'error in verified number !'));
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
    var_dump($result);
    
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

    $row = $cntResult->fetch_array(MYSQL_ASSOC);
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
    echo $parm['emailId'];
    $table = '91_verifiedEmails';
    $data=array("default_email"=>0); 

    $this->db->update($table, $data)->where("userid = '" . $userid . "' and default_email =1");
    $this->db->getQuery();
    $result = $this->db->execute();
    var_dump($result);
    
    $data=array("default_email"=>1); 

    $this->db->update($table, $data)->where("verifiedEmail_id = '" . (int)$parm['emailId']."'");
    $this->db->getQuery();
    $result = $this->db->execute();
    var_dump($result);
    
    return json_encode(array('msgtype' => 'success', 'msg' => 'Email id set as a Default email !'));
    
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25-07-2013
#function use for resend confirm code to uesr 
function resendConfirm_code($parm,$userid){
    
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

          return json_encode(array('status' => 'error', 'msg' => 'you have riched max resend limit for today pelase try again tomorrow or contact support  !'));             
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
       if ($_POST['country_code'] == $contact['countryCode'] && $_POST['resend_phone'] == $contact['tempNumber'] && strlen($contact['confirmCode']) > 0) 
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
		if ($contact['countryCode'] == "91")
                {
			$funobj->SendSMS91($nine);
                }
		else
			$funobj->SendSMSUSD($d);
		return json_encode(array('status' => 'success', 'msg' => 'Confirmation Code Send'));    
                
		
	}
    
    
    
}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 25-07-2013
#function use for resend confirm code to uesr by call
function callmeConfirm_code($parm,$userid){
   
    $contact = $this->getUnconfirmMobile($userid);
    
    #table name 
    $table = '91_resendCode';
    #type 1 for phone and 2 for email
    $data=array("type"=>1,"userid"=>(int)$userid,"date"=>date('Y-m-d'),"resend_by"=>2); 

    #insert query (insert data into 91_tempcontact table )
    $this->db->insert($table, $data);	
    $this->db->getQuery();
    $result = $this->db->execute();
    //var_dump($result);
    
    #find total time of resend confirm code and it is not more then 5 times
    $this->db->select('*')->from($table)->where("userid = '" . $userid . "' and type = 1 and resend_by = 2 and date = '".date('Y-m-d')."'");
    $this->db->getQuery();
    $result = $this->db->execute();
    // processing the query result  
   // var_dump($result);
    if ($result->num_rows > 5){

          return json_encode(array('msgtype' => 'error', 'msg' => 'you have riched max resend limit for today pelase try again tommorow or contact support  !'));             
//               
       } 
  
        //code to resend voice


	if ($_POST['country_code'] == $contact['countryCode'] && $_POST['resend_voice'] == $contact['tempNumber'] && strlen($contact['confirmCode']) > 0) 
        {
		$mobile_no = $contact['countryCode'] . $contact['tempNumber'];
		$vcode = $contact['confirmCode'];
                $funobj = new fun();
		$funobj->mobile_verification_api($mobile_no, $vcode);
		return json_encode(array('msgtype' => 'success', 'msg' => 'Success please wait for the call and enter you confirmation code'));    
		
	}
    //}//end of if for count
    
    
}
#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 02/08/2013
#function used to delete the  verified email address
function deleteEmailId($emailid,$userId)
  {
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
function getUserDefaultNumber($userId)
{
    #name of the table on which action to be taken 
    $table='91_verifiedNumbers';
    #find total time of resend confirm code and it is not more then 5 times
    $this->db->select('*')->from($table)->where("userId = '" . $userId . "' and isDefault = 1 ");
    $result = $this->db->execute();
    if($result->num_rows > 0)
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return $row['countryCode'].$row['verifiedNumber'];
    }
    else
        return 0;
}

#created by Balachandra Hegde<balachandra@hostnsoft.com>
#modified by sudhir pandey 19/08/2013
#date 02/08/2013
#function used to delete the verified phone number   
function deletephone($contactid,$userId)
   {
    #name of the table on which action to be taken 
    $table='91_verifiedNumbers';
    #find total time of resend confirm code and it is not more then 5 times
    $this->db->select('*')->from($table)->where("userId = '" . $userId . "' and varifiedNumber_id  = '".$contactid."' and isDefault = 1 ");
    $result = $this->db->execute();
    if ($result->num_rows > 0){

          return json_encode(array('msgtype' => 'error', 'msg' => 'you can not delete default contact number !'));             
    } 
                    
    #delete the data from table 91_verifiednumbers hwere userid and conatid matches
    $this->db->delete($table)->where("userId = '" .$userId."' and  varifiedNumber_id  = '".$contactid."'");

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
        return json_encode(array('msgtype'=>'error','msg'=>'Not Possible No Delete'));
        
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
   
#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 20/08/2013
#function used to update the news and social networks
   function news_update($key,$value,$userid)
   {
       $table='91_profilesettings';
       $sql=$this->db->update($table)->set("key='$value'")->where("userId='".$userid."'");
       $this->db->getQuery($sql);
       $this->db->execute($sql);
        return 1;
       
      
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

#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 20/08/2013
#function used to update the news and social networks
function news_updates($request,$userId)
{
  #extract the value sent from the post method  
  extract($request);
  #check the key and its value
  if (($key == 'fb_updates' || $key == 'google_updates' || $key == 'acc_news' || $key == 'acc_sms' || $key == 'acc_emails') && ($value == 1 || $value == 0)) {

     #$table is the name of the table in database where the update action takes place
     $table = '91_profilesettings';

     #put the data in array i.e $data   
     $data = array($key => $value);
     //print_r($data);
     #for the update query the condition is stored in $condition    
     $condition = " userid=" . $userId . " ";
     $this->db->update($table, $data)->where($condition);
     echo $this->db->getQuery();

     #execute the query
     if ($result = $this->db->execute()) {
         #if query give the proper value       
         if ($result) {
             #display the success message
             //$response["msg"] = "Update Successfully";
             //$response["msgtype"] = "success";
             return json_encode(array('msg'=>'error','msgtype'=>'success'));
             }

          } 

       }  else {
         #any condition false then display error message  
         $response["msg"] = "Update";
         $response["msgtype"] = "error";
     }
     #return the result in json form
     return json_encode($response);
 }
   
   
}
?>
