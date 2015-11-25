<?php
include dirname(dirname(__FILE__)).'/config.php';
class contact_class extends fun{
        
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
	if($this->db->query("DELETE FROM `tempcontact` WHERE contact_no=".$phone." AND userid=".$user_id))
		return 1;
    }
    function getConfirmMobile($userid)
	{
	    $table = '91_verifiedNumbers';
	    $this->db->select('*')->from($table)->where("userId = '" . $userid . "' ");
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
            $table = '91_tempcontact';
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
        #modified date 23/07/2013
        #function use for get confirm mobile number   
	function getConfirmEmail($userid)
	{
	    $table = '91_verifiedEmails';
	    $this->db->select('*')->from($table)->where("userId = '" . $userid . "' ");
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
        
        #created by sudhir pandey (sudhir@hostnsoft.com)
        #creation date 23/07/2013
        #function use for update new contact no. of login user 
        function update_newcontact($parm,$userid){

        #variabel country_code use for country code     
       	$country_code = $parm['country_code'] ;
        //$code = substr($code, 1, strlen($code) - 1);
	$phone = $parm['contact_no'];
	
        #check country name is selected or not 
        if ($country_code == 'selectCountry') {
		return json_encode(array('msgtype' => 'error', 'msg' => 'Please Select Country'));
	}
        
        # generate confirmation code 
        $confirm_code = $this->conf_code();

        #table name 
        $table = '91_contact'; 
        $this->db->select('*')->from($table)->where("country_code = '" . $country_code . "' and confirm = 1 and contact_no='".$phone."'");
        $result = $this->db->execute();

        //if number is not assign to any other user
        if ($result->num_rows == 0) {

            #only one unvarify data store in database 
            #table name 
            $table = '91_tempcontact'; 
            $this->db->select('*')->from($table)->where("userid = '" . (int)$userid . "'");
            $result = $this->db->execute();
            if ($result->num_rows > 1) {
                 return json_encode(array('msgtype' => 'error', 'msg' => 'firstly varify your unvarify contact numver !'));
             
            }
            
            #value for store in database 
            $data=array("userid"=>(int)$userid,"contact_no"=>$phone,"email"=>"dummy@gmail.com","country_code"=>(int)$country_code,"confirm_code"=>$confirm_code,"confirm"=>0,"creatDate"=>date('Y-m-d H:i:s')); 

            
            #insert query (insert data into 91_tempcontact table )
            $this->db->insert($table, $data);	
            $this->db->getQuery();
            $result = $this->db->execute();
            var_dump($result);


            # Assign Variables for sending sms to user
            $d["text"] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
            $d["to"] = $country_code . $phone;
            //for 91 user
            $nine[mobiles] = $phone;
            $nine[message] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
            //Call function
            $funobj = new fun();
            if ($country_code == "91"){
                    if($funobj->SendSMS91($nine) == 'code: 101'){
                         $error = 1;
                    }
            }else{
                    if($funobj->SendSMSUSD($d) == 'error: 101'){
                        $error = 1;
                    }
            }
            if($error){
                return json_encode(array('msgtype' => 'success', 'msg' => 'send sms successfuly !'));
               // $delete = $this->db->query("delete from tempcontact where userid='" . $session['id_cl'] . "'");
               }else{
               return json_encode(array('msgtype' => 'error', 'msg' => 'sms not send!'));
            }

        }
        else { //in case of number assign to another user
                
//                $get_details = mysql_fetch_array($checkexist);
//                $userid = $get_details['userid'];
//                if ($userid != $_SESSION['id_cl']) {
//                        $result = mysql_query("select login from clientsshared where id_client='$userid'");
//                        $get_details = mysql_fetch_array($result);
//                        $username = $get_details['login'];
//                        $str= "Sorry this Number is already used with Other username.";
//                } else {
//
//                        $str= "Sorry this Number is already confirm by you.";
//
//                }
             return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry this Number is already used !'));
            
        }

}

#created by sudhir pandey (sudhir@hostnsoft.com)
#creation date 23/07/2013
#function use for varify mobile number of login user
function varifyNumber($parm,$userid){
    
    $key = $parm['key'];
  //validation for confirmation
	if (strlen($parm['key']) > 0) 
        {
            //This query check for contact information in contact and temp - contact
            $temptable = '91_tempcontact';
	    $this->db->select('*')->from($temptable)->where("userid = '" . $userid . "' and confirm_code = '".$key."'");
            $this->db->getQuery();
	    $result = $this->db->execute();
	    // processing the query result  
           // var_dump($result);
	    if ($result->num_rows <= 0){
                
                  return json_encode(array('msgtype' => 'error', 'msg' => 'varification code not march !'));             
//               
            } 
            else
            {
              while($row= $result->fetch_array(MYSQL_ASSOC)) {
		    $confirm = $row['confirm'];
                    $country_code = $row['country_code'];
                    $contact_no = $row['contact_no'];
                     
		}
               
            $table = '91_contact';
	    $this->db->select('*')->from($table)->where("userid = '" . $userid . "' and confirm_code = '".$key."'");
            $this->db->getQuery();
	    $result = $this->db->execute();
            if ($result->num_rows == 0){
                

            $data=array("userid"=>(int)$userid,"email"=>"dummy@gmail.com","country_code"=>(int)$country_code,"contact_no"=>$contact_no,"confirm_code"=>$key,"confirm"=>1); 

            #insert query (insert data into 91_tempcontact table )
            $this->db->insert($table, $data);	
            $this->db->getQuery();
            $result = $this->db->execute();
            var_dump($result);
            
            #delete from temp contact 
            $this->db->delete($temptable)->where("userid = '" . $userid . "' and confirm_code = '".$key."'");
            $this->db->getQuery();
	    $result = $this->db->execute();
           
            
             $result = mysql_query("delete from tempcontact where userid='" . $_SESSION['id_cl'] . "'");
                               
                                      
            }else 
                { //in case of number assign to another user
                 return json_encode(array('msgtype' => 'error', 'msg' => 'Sorry this Number is already used !'));
                }
            }
            
	}
        
    
}

}
?>
