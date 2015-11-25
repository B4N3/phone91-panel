<?php
include dirname(dirname(__FILE__)).'/config.php';
class updateContact_class extends fun{
        
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
//    
//    function deleteContact($phone, $user_id){
//	if($this->db->query("DELETE FROM `tempcontact` WHERE contact_no=".$phone." AND userid=".$user_id))
//		return 1;
//    }
    function deleteTempNumber($phone, $user_id){
        echo "DELETE FROM `91_tempNumbers` WHERE tempNumber=".$phone." AND userId=".$user_id;
	if($this->db->query("DELETE FROM `91_tempNumbers` WHERE tempNumber=".$phone." AND userId=".$user_id))
		return 1;
    }
    
}
?>
