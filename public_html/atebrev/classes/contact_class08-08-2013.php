<?php
include dirname(dirname(__FILE__)).'/config.php';
class contact_class extends fun{
        
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
    #modify date 24-07-2013
    #function use for get verify contact number form 91_contact table 
    function getConfirmMobile($userid)
	{
            #table name
	    $table = '91_verifiedNumbers';
	    #select query 
            $this->db->select('*')->from($table)->where("userId = '" . $userid . "'");
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
    function getUnconfirmMobile($userid)
	{
            #table name 
            $table = '91_tempNumbers';
	    $this->db->select('*')->from($table)->where("userId = '" . $userid . "' ");
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
        #modified by sudhir pandey (sudhir@hostnsoft.com)
        #modified date 25/07/2013
        #function use for get confirm email id    
	function getConfirmEmail($userid)
	{
	    $verifyEmail = '91_verifiedEmails'; 
            $this->db->select('*')->from($verifyEmail)->where("userid = '" . $userid . "'");
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
        #modified date 25/07/2013
        #function use for get un verified email id    
	function getUnConfirmEmail($userid)
	{
	    $tempEmail = '91_tempEmails'; 
            $this->db->select('*')->from($tempEmail)->where("userid = '" . $userid . "'");
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
            if (strlen($parm['key']) > 0) 
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

                return json_encode(array('msgtype' => 'success', 'msg' => 'Email Id Successfully Verified !'));
             
                               
                                      
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
        
        if (!preg_match("/^[0-9]{8,15}$/", $phone)){  
           
       return json_encode(array("msgtype"=>"error","msg"=>"Contact Number Is Not Valid!"));
       }
        
        # generate confirmation code 
        $confirm_code = $this->conf_code();

        #table name 
        $table = '91_verifiedNumbers'; 
        $this->db->select('*')->from($table)->where("verifiedNumber = '" . $country_code . "' and verifiedNumber='".$phone."'");
        $result = $this->db->execute();

        //if number is not assign to any other user
        if ($result->num_rows == 0) {

            #only one unverify data store in database 
            #table name 
            $table = '91_tempNumbers'; 
            $this->db->select('*')->from($table)->where("userId = '" . (int)$userid . "'");
            $result = $this->db->execute();
            if ($result->num_rows > 1) {
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
                if($error!=1){
                    return json_encode(array('msgtype' => 'success', 'msg' => 'Contact added successfully! please enter verification code.'));
                   // $delete = $this->db->query("delete from tempcontact where userid='" . $session['id_cl'] . "'");
                   }else{
                   return json_encode(array('msgtype' => 'error', 'msg' => 'Contact Added But Unable To Send SMS!',"response"=>$sendSmsResponse));
                }
            }
            else{
                echo $sql;
            }

        }
        else { //in case of number assign to another user
                
             return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry this Number is already in  use !'));
            
        }

}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 23/07/2013
#function use for verify mobile number of login user
function verifyNumber($parm,$userid){
    
    $key = $parm['key'];
  //validation for confirmation
	if (strlen($parm['key']) > 0) 
        {
            //This query check for contact information in contact and temp - contact
            $temptable = '91_tempNumbers';
	    $this->db->select('*')->from($temptable)->where("userId = '" . $userid . "' and confirmCode = '".$key."'");
            $this->db->getQuery();
	    $result = $this->db->execute();
	    // processing the query result  
           // var_dump($result);
	    if ($result->num_rows <= 0){
                
                  return json_encode(array('msgtype' => 'error', 'msg' => 'verification code mismarch !'));             
//               
            } 
            else
            {
              while($row= $result->fetch_array(MYSQL_ASSOC)) {
//		    $confirm = $row['confirm'];
                    $countryCode = $row['countryCode'];
                    $tempNumber = $row['tempNumber'];
                     
		}
               
            $table = '91_verifiedNumbers';
	    $this->db->select('*')->from($table)->where("verifiedNumber = '".$tempNumber."'"); //userId = '" . $userid . "' and 
            $this->db->getQuery();
	    $result = $this->db->execute();
            if ($result->num_rows == 0){
                

            $data=array("userId"=>(int)$userid,"countryCode"=>(int)$country_code,"verifiedNumber"=>$tempNumber); 

            #insert query (insert data into 91_tempcontact table )
            $this->db->insert($table, $data);	
            $this->db->getQuery();
            $result = $this->db->execute();
//            var_dump($result);
            
            #delete from temp contact 
            $this->db->delete($temptable)->where("userId = '" . $userid . "' and confirmCode = '".$key."'");
            $this->db->getQuery();
	    $result = $this->db->execute();
           
             return json_encode(array('msgtype' => 'success', 'msg' => 'Number successfuly verified !'));
             
                               
                                      
            }else 
                { //in case of number assign to another user
                 return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry this Number is already in use !'));
                }
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
    var_dump($result);
    
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
    var_dump($result);
    
    #find total time of resend confirm code and it is not more then 5 times
    $this->db->select('*')->from($table)->where("userid = '" . $userid . "' and type = 1 and resend_by = 1 and date = '".date('Y-m-d')."'");
    $this->db->getQuery();
    $result = $this->db->execute();
    // processing the query result  
   // var_dump($result);
    if ($result->num_rows > 5){

          return json_encode(array('msgtype' => 'error', 'msg' => 'you have riched max resend limit for today pelase try again tomorrow or contact support  !'));             
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
	if ($parm['country_code'] == $contact['country_code'] && $parm['resend_phone'] == $contact['contact_no'] && strlen($contact['confirm_code'])>0) 
        {
		$d["message"] = "Enter this confirmation code " . $contact['confirm_code'] . " to confirm your mobile number."; // sms text
		
		$d["sender"]="Phonee";					
                $d["mobiles"]=$contact['country_code'] . $contact['contact_no'];
                //for 91 user
                
		$nine[mobiles] = $contact['country_code'] . $contact['contact_no'];
		$nine[message] = "Enter this confirmation code " . $contact['confirm_code'] . " to confirm your mobile number."; // sms text
		$nine[sender] = "Phonee";
		//Call function
                 $funobj = new fun();
		if ($contact['country_code'] == "91")
                {
			$funobj->SendSMS91($nine);
                }
		else
			$funobj->SendSMSUSD($d);
		//echo $count;
                
		
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
    var_dump($result);
    
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
    
    
  /*  
//get current date
    $currentDate = date("Y-m-d");
    
    //compare current date with database date
    if(strtotime($ddate) != strtotime($currentDate) )
    {
        $updateDateCount = "UPDATE tempcontact set count = 5,date='$currentDate' WHERE userid='" . $_SESSION['id_cl'] . "'";
        mysql_query($updateDateCount);
        $count = 5;
    }
    
    //check count if greater than zero
    if($count > 0)
    {
        //get country code and mobile num
        $conCode = $_POST['country_code'];
        $reCallMob = $_POST['resend_voice'];
        //query to update count
        $queryForUpdateCount = "UPDATE tempcontact set count = count-1 WHERE cntry_code=$conCode AND contact_no = $reCallMob" ;
       
        mysql_query($queryForUpdateCount) or die(mysql_error());
        $count = $count -1;*/
	
        //code to resend voice
	if ($_POST['country_code'] == $contact['country_code'] && $_POST['resend_voice'] == $contact['contact_no'] && strlen($contact['confirm_code']) > 0) 
        {
		$mobile_no = $contact['country_code'] . $contact['contact_no'];
		$vcode = $contact['confirm_code'];
                $funobj = new fun();
		$funobj->mobile_verification_api($mobile_no, $vcode);
		
		echo "Confirmation Code Send";
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


#created by Balachandra Hegde<balachandra@hostnsoft.com>
#date 02/08/2013
#function used to delete the verified phone number   
function deletephone($contactid,$userId)
   {
    #name of the table on which action to be taken 
    $tabl='91_verifiedNumbers';
    
                    
    #delete the data from table 91_verifiednumbers hwere userid and conatid matches
    $this->db->delete($tabl)->where("userId = '" .$userId."' and  varifiedNumber_id  = '".$contactid."'");
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
   
}
?>
