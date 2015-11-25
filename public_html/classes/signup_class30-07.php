<?php
include dirname(dirname(__FILE__)).'/config.php';
class signup_class extends fun //validation_class.php
{
    function check_user_avail($username = NULL) 
    {
        if(isset($_REQUEST['username']))
            $username = $_REQUEST['username'];
        
        $table = '91_userLogin';
        $this->db->select('userName')->from($table)->where("userName = '" . $username . "' ");
//        echo $this->db->getQuery();
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
    
    function generatePassword() {
		$length = 4;
		$password = "";
		$possible = "0123456789";
		$i = 0;
		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
			if (!strstr($password, $char)) {
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 26-07-2013
    #function use for 
    function sign_up($parm){
        print_r($parm);
        
        /*
         *  [action] => signup
    [username] => sudhirp29
    [location] => 91
    [code] => 91
    [mobileNumber] => 9630738974
    [email] => sudhir@hostnsoft.com
    [password] => sudhir29
    [repassword] => sudhir29
    [currency] => 2
    [client_type] => 3
         * 
         */
      
     #check for all value inserted or not in signup form    
      if (strlen($parm['username']) < 1 || strlen($parm['password']) < 1 || strlen($parm['code']) < 1 || strlen($parm['mobileNumber']) < 1 || strlen($parm['email']) < 1) 
        {
          return json_encode(array("status"=>"error","msg"=>"Incomplete From!"));  
        }
      #tariff id from plan table 
      $tariff_id = 8;
      #user balance from plan table  
      $balance = 0.5000;
      #puls 
      $puls = 60;
      #currency id 
      $currency_id = $parm['currency'];
      #reseller id 
      $reseller_id = 1;
      #call limit 
      $call_limit = 2;
        
      //remove zero from starting of number if exist
      if (substr($parm['mobileNumber'], 0, 1) == 0)
        $phone = substr($parm['mobileNumber'], 1, strlen($parm['mobileNumber']) - 1);
      
      //get contact with country code
      $contact = $parm['code'] . $phone;
      
      //check if username already exists
      $check = $this->check_user_avail($parm['username']);
      if($check == 0){
           return json_encode(array("status"=>"error","msg"=>"Sorry username already in use!"));
      }
      
      if($parm['password'] != $parm['repassword']){
          return json_encode(array("status"=>"error","msg"=>"Sorry password not matched!"));
      }
      
      //to check if phoneno existes or not
      $table = '91_contact';
      $this->db->select('*')->from($table)->where("contact_no = '" . $parm['mobileNumber'] . "' and country_code = '".$parm['code']."' and confirm = 1");
      $this->db->getQuery();
      $result = $this->db->execute();
      if ($result->num_rows > 0){
          return json_encode(array("status"=>"error","msg"=>"Phone number already in use by another user!"));
      }
      
      //to check  email address already exists or not 
      $table = '91_verifiedEmails';
      $this->db->select('*')->from($table)->where("email = '" . $parm['email'] . "'");
      $this->db->getQuery();
      $result = $this->db->execute();
      if ($result->num_rows > 0){
          return json_encode(array("status"=>"error","msg"=>"This email address already registered!"));
      }
      
      #insert userdetail into database 
      $personalTable = '91_personalInfo';
      $data=array("name"=>$parm['username'],"contact_no"=>$parm['mobileNumber'],"emailId"=>$parm['email'],"countryCode"=>(int)$parm['code']); 

      #insert query (insert data into 91_personalInfo table )
      $this->db->insert($personalTable, $data);	
      $qur = $this->db->getQuery();
      $result = $this->db->execute();
      var_dump($result);
      #check data inserted or not 
      if(!$result){
          mail("sudhir@hostnsoft.com", "Phone91 signup_class personal info table query fail", "query " . $qur);
         return json_encode(array("status"=>"error","msg"=>"signup process fail!"));
          
      }
      
      #user id 
      $userid = $this->db->insert_id;
      
      #insert login detail into login table database 
      $loginTable = '91_userLogin';
      $data=array("userId"=>(int)$userid,"userName"=>$parm['username'],"password"=>$parm['password'],"isBlocked"=>1,"type"=>(int)$parm['client_type']); 

      #insert query (insert data into 91_userLogin table )
      $this->db->insert($loginTable, $data);	
      $qur = $this->db->getQuery();
      $result = $this->db->execute();
      var_dump($result);
      #check data inserted or not 
      if(!$result){
          mail("sudhir@hostnsoft.com", "Phone91 signup_class userlogin  table query fail", "query " . $qur);
         return json_encode(array("status"=>"error","msg"=>"signup process fail!"));
          
      }
      
      
      #insert login detail into login table database 
      $loginTable = '91_userBalance';
      $data=array("userId"=>(int)$userid,"tariffId"=>(int)$tariff_id,"balance"=>$balance,"pulse"=>$puls,"currencyId"=>(int)$currency_id,"callLimit"=>(int)$call_limit,"resellerId"=>(int)$reseller_id); 
      
      #insert query (insert data into 91_userLogin table )
      $this->db->insert($loginTable, $data);	
      $tempsql = $this->db->getQuery();
      $result = $this->db->execute();
      var_dump($result);
      if (!$result){
        mail("sudhir@hostnsoft.com", "Phone91 signup_class 91_userBalance query fail", "query " . $tempsql);
        return json_encode(array("status"=>"error","msg"=>"signup process fail!"));  
      }
      
      
      #contact no and email id store in temp contact and temp email teable for contactno and email varifiction 
      # Store Contact No :
      $confirm_code = $this->generatePassword();
      
      #value for store in database 
      $table = "91_tempcontact";
      $data=array("userid"=>(int)$userid,"contact_no"=>$parm['mobileNumber'],"email"=>$parm['email'],"country_code"=>(int)$parm['code'],"confirm_code"=>$confirm_code,"confirm"=>0,"creatDate"=>date('Y-m-d H:i:s')); 


      #insert query (insert data into 91_tempcontact table )
      $this->db->insert($table, $data);	
      $this->db->getQuery();
      $result = $this->db->execute();
      var_dump($result);
      
      # Assign Variables for sending sms to user
        $d["text"] = "you are successfully registered your username is: " . $parm['username'] . " and confirmation-code is: " . $confirm_code . " Please recharge to start using this account."; // sms text
        $d["to"] = $parm['code'] . $parm['mobileNumber'];
        //for 91 user
        $nine[mobiles] = $parm['mobileNumber'];
        $nine[message] = "you are successfully registered your username is " . $parm['username'] . " and confirmation-code is " . $confirm_code . " Please recharge to start using this account."; // sms text
           
        
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
        #check sms send or not 
        if(!$error){
          return json_encode(array('msgtype' => 'error', 'msg' => 'sms not send!'));
        }
        
        
        
        
        
        # Store Email in tampEmpail for email id varification
        #value for store in database 
        $emailconfirm_code = $this->generatePassword();
        $tempEmail = "91_tempEmails";       
        $data=array("userid"=>(int)$userid,"email"=>$parm['email'],"confirm_code"=>$emailconfirm_code,"date"=>date('Y-m-d H:i:s')); 
 
        #insert query (insert data into 91_tempEmails table )
        $this->db->insert($tempEmail, $data);	
        $this->db->getQuery();
        $savedata = $this->db->execute();
        var_dump($savedata);

        if ($savedata) 
            {	 
                #send confirm code by email id for email varification 
                $sentmail = $funobj->send_verification_mail($parm['email'],$emailconfirm_code );
                if($sentmail)
                {
                    return  json_encode(array('msgtype' => 'success', 'msg' => 'Your Confirmation link Has Been Sent To Your Email Address and mobile no.'));
                }else
                    return json_encode(array('msgtype' => 'error', 'msg' => 'mail not send.'));
            }
        
    
      
  /*   not in use 
     
         $addClient = 0;
            if ($repara['update_detail'] == 1)
                    $addClient = 1;
           
//            //get user details
//            $username = $this->sql_safe_injection($repara['username']);
//            $pwd = $this->sql_safe_injection($repara['password']);
//            $codeL = $this->sql_safe_injection($repara['code']);
//            $code = substr($codeL, 1, strlen($codeL) - 1);
//            $phone = $this->sql_safe_injection($repara['mobileNumber']);
//            $email = $this->sql_safe_injection($repara['email']);
//            $cur = $this->sql_safe_injection($repara['currency']);
//            $client_type = $this->sql_safe_injection($repara['client_type']);
            //validation for fields
            if (strlen($username) < 1 || strlen($pwd) < 1 || strlen($code) < 1 || strlen($phone) < 1 || strlen($email) < 1) 
            {
                echo 'Incomplete From';
                exit();
            }
            
             //to change tariff_id acc to currency
            if ($cur == 1) 
            {
                $tariff_id = 8;
                $id_reseller = 22;
		$user_balance = 0.5000;
            } 
            else if ($cur == 2) 
                {
                    $tariff_id = 7;
                    $id_reseller = 20;
			$user_balance = 25.0000;
                } 
                else if ($cur == 3) 
                    {
                        $tariff_id = 9;
                        $id_reseller = 11;
			$user_balance = 2.0000;
                    }
                 
                 //remove zero from starting of number if exist
		if (substr($phone, 0, 1) == 0)
			$phone = substr($phone, 1, strlen($phone) - 1);
		//get contact with country code
		$contact = $code . $phone;
                //create connection
		$con = $this->connect();
		$result = mysql_query("select login from clientsshared where login='$username'");
		$res = mysql_num_rows($result);
                //check if username already exists
		if ($res != 0) 
                {
                    echo "This User ID already exists";
                    exit();
		} 
                else 
                {
                    //to check if phoneno existes or not
                    $result = mysql_query("select userid from contact where contact_no='$phone' and cntry_code='$code' and confirm='1'");
                    $res = mysql_num_rows($result);
                     
                    //if phone number exists
                    if ($res != 0 && $addClient != 1) 
                    {
                            echo "Phone number already in use by another user";
                            exit();
                    } 
                    else //if new phone number
                    {  
                        //code to check email exists or not,for temp_contact_table
                        $emailResultTemp = mysql_query("SELECT userid FROM temp_contact_email WHERE email='$email'") or die(mysql_error()); 
                        $countEmail = mysql_num_rows($emailResultTemp);
                        //for contact table
                        $emailResultCont = mysql_query("SELECT userid FROM contact WHERE email='$email'") or die(mysql_error());
                        $emailCountCont = mysql_num_rows($emailResultCont);
                        //check if count not zero,means email address already exists
                        if($countEmail != 0 or $emailCountCont != 0 and $addClient !=1)
                        {
                            echo "This email address already registered";
                            exit();
                        }
                        else//if new email
                        {
                            if ($addClient == 1) 
                            {
                                $pwd = $repara['pwd'];
                                $user_balance = $repara['balance'];
                                $result = mysql_query("select id_tariff,id_currency from clientsshared where id_client='$_SESSION[id]'");
                                $resellerDetail = mysql_fetch_array($result);
                                $tariff_id = $resellerDetail['id_tariff'];
                                $cur = $resellerDetail['id_currency'];
                                $id_reseller = $_SESSION['id'];
//                                 include_once("classes/profile_class.php");
//                                  $res_bal=$pro_obj->getUserBalance($id_reseller);//from new ms_user_balance table
//                                  if($res_bal<$user_balance)
//                                  $user_balance=0;
//                                  if(!$pro_obj->check_empty($res_bal,''))
//                                  {
//                                  echo "Unable To Get Your Balance";
//                                  exit();
//                                    }
//                                    $newUserBal=$res_bal-$user_balance;
//                                    $pro_obj->updateUserBalance($id_reseller,$newUserBal); 
                          } 
                            else 
                            {
                //                $user_balance = 0.5000;
				$id_reseller = 30159;
                            }
                          
                            $query = "insert into clientsshared(login,password,type,id_tariff,account_state,tech_prefix,id_reseller,type2,type3,id_intrastate_tariff,id_currency,codecs,primary_codec,client_type) values('$username','$pwd',45220371,'$tariff_id','$user_balance',CONCAT('SD:;ST:;DP:;TP:;CP:!',$code,$phone,';SC:'),'$id_reseller',1,0,-1,'$cur',12,4,'$client_type')";
                            $result = mysql_query($query) or die(mysql_error());
                            if (!$result)
                                mail("rahul@hostnsoft.com", "Phone91 function_layer clientsshared query fail", "query " . $query . " Error " . $error);
                            if ($result) 
                            {
                                $userid = mysql_insert_id();
                                $pwd = $this->generatePassword();
                                $tempsql = "insert into tempcontact(userid,contact_no,email,cntry_code,confirm_code,confirm) values('$userid','$phone','$email','$code','$pwd','0')";
                                $tempentry = mysql_query($tempsql) or $error = (mysql_error());
                                if (!$tempentry)
                                      mail("rahul@hostnsoft.com", "Phone91 function_layer tempcontact query fail", "query " . $tempsql . " Error " . $error);
                                $regi_sql = "insert into register_info(userid) values('$userid')";
                                $regi_entry = mysql_query($regi_sql) or $error = (mysql_error());
                                if (!$regi_entry)
                                        mail("rahul@hostnsoft.com", "Phone91 function_layer register query fail", "query " . $regi_sql . " Error " . $error);
                                $emailCodeGen = $this->generatePassword();
                                $emailCode=  base64_encode($emailCodeGen);
                                $temp_email_sql = "insert into temp_contact_email(userid,email,confirm_code,confirm) values('$userid','$email','$emailCodeGen','0')";
                                $temp_email_result = mysql_query($temp_email_sql) or $temp_email_error = (mysql_error());
                                if(!$temp_email_result)
                                        mail("rahul@hostnsoft.com", "Phone91 function_layer tempcontact query fail", "query " . $tempsql . " Error " . $temp_email_error);
                                if ($addClient != 1) 
                                {
                                    //Assign Variables for sending sms to user
                                    $d['sender'] = "Phone91";
                                    $d['message'] = "you are successfully registered your username is: " . $username . " and confirmation-code is: " . $pwd . " Please recharge to start using this account."; // sms text
                                    $d['mobiles'] = $contact;
                                    //for 91 user
                                     $nine['sender'] = "Phonee";
                                    $nine['mobiles'] = $phone;
                                    $nine['message'] = "you are successfully registered your username is " . $username . " and confirmation-code is " . $pwd . " Please recharge to start using this account."; // sms text
                                    //Call function
                                    if ($code == "91")
                                            $this->SendSMS91($nine);
                                    else
                                            $this->SendSMSUSD($d);   

                                    $this->mobile_verification_api($contact, $pwd);
                                }
                                   $mailData=<<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="background:#ddd">
<body style="background:#ddd; width:100%;">
<div style="width:625px; margin:0 auto;  background:#fff;color:#000">
<!---------------header-------------------->
<div class="wrap"><div style="height:8px;background-color:#00B0F0;"></div></div>
<div id="header" align="center">
    <div class="wrap bgw">
    <div id="head1" class="grayclr p20 f14"><h1 class="mar0">Thank you <span style="color:#00B0F0;">$username</span> for Signing Up at</h1></div>
    <div id="head2" class="black "><h2 style="font-size:120px; margin:0;padding:0;">Phone<span style="color:#00B0F0;">91</span></h2></div>
    <div id="hcont" style="padding:20px;">
                    <span style="font-size:16px; color:#555;">We are an International voice calling solutionsprovider. You are now connected with the Company thatsends quality Voice <strong style="color:#000;">on 150+ telecom operators.</strong></span>
     </div>
</div>
</div>
<!----------------main container-------------------->
<div id="main">
    <div class="wrap">
    <div style="border-top:1px solid #00B0F0; border-bottom:1px solid #00B0F0; color:#fff; text-align:left;">
                    <div id="mlink" style="background-color:#00B0F0; padding:20px; margin:1px 0;">
            <h2 style="margin:0;padding:0; font-size:24px;">Thanks a lot for choosing us! Please confirm your Email ID by clicking the link given below.</h2>
            <div id="link"><a href="https://voip91.com/verify_email.php?email=$email&confirmatioCode=$emailCodeGen" style="color:#000; font-size:16px;">Confirm</a></div>
                            <div style="margin:0;padding:0; margin-top:20px;">Or use this confirmation code   <span style="color:#000;">$emailCodeGen</span>    at the site from you have signup.</div>
        </div>
    </div>
    </div>
</div>
<!----------------queries container-------------------->
<div id="queries">
            <div class="wrap">
            <div id="quriBox" style="padding:20px;">
                            <div id="payh1" class="marb10"><h2 class="mar0">For Support :</h2></div>
                    <div id="qcont">
                    <span style="margin:0;padding:0; font-size:18px; color:#777;">For any queries, please contact on below details and one of our friendly staff will reply you very soon.</span>
                </div>
                <div id="qsupport">
                    <div class="emal f14"><span class="grayclr ebox">Gtalk IM</span> <span class="ecbox">: support@phone91.com</span></div>
                    <div class="emal f14"><span class="grayclr ebox">Email</span> : <a href="#" class="black"><span class="ecbox" style="text-decoration:underline;">support@phone91.com</span></a></div>
                </div>
        </div>
    </div>
</div>
<!----------------payment container-------------------->
<div id="payment">
            <div class="wrap">
    <div id="payCon" style="padding:20px;">
                    <div id="payh1" class="marb10"><h2 class="mar0">For Payment :</h2></div>
            <div style="margin:0;padding:0; font-size:18px; color:#777;">Online payment</div>
            <div class="grayclr lh f14 marb10">Login to your account, Click on "Pay Online", fill your billing details, choose the payment type and a recharge amount, and Click suitable online payment option from Paypal, debit card (ATM), Credit card orMooneybookers(Skrill). After successful payment, your account will be recharged automatically.</div>
            <div><strong>*We suggest that you should use Google Chrome for browsing our website and making payment.</strong></div>
    </div>
    </div>
</div>
<!----------------team container-------------------->
<div id="team">
    <div class="wrap">
    <div id="teambox" style="padding:20px;">
            <div id="thead"><span class="bold f12 ">Regards,</span><br><strong><span class="bold">Phone<span class="bclr">91 </span>Team</span></strong></div>
        <div id="icon" style="padding:15px 0px;"><span class="f14 bold">For updates of our services,follow us on:</span>
                    <br />
                    <a href="https://www.facebook.com/phone91" class=" bold f14" style="color:#3B5998; margin-right:20px; font-weight:bold; font-size:18px; text-decoration:none;">Facebook</a>
                    <a href="https://twitter.com/phone91" class=" bold f14" style="color:#37B9E3; text-decoration:none; font-weight:bold; font-size:18px;">twitter</a></div>
    </div>
</div>
</div>
<!----------------Footer container-------------------->
<div id="fbox">
    <div class="wrap">
  <div style="padding-top:5px; background-color:#FFF;">
    <div id="footer" style="padding:20px; background:#f5f5f5;">
                <div class="privacy marb10">
                    <h2>Privacy Statement</h2>
<span class="f14 grayclr lh">We are happy to have you on our list, and since we want to keep you all to ourselves, we never share your Email address with anyone.</span>
                </div>
                <div class="privacy marb10">
                    <h4 class="mar0 marb10 f14">Manage Your Subscription</h4>
<span class="f14 grayclr lh">You are subscribed to <a href="https://voip91.com/">phone91.com</a> with the email address: $email</span>
                </div>
                <div class="privacy">
                    <h4 class="mar0 marb10 f14">Unsubscribe or change your subscription</h4>
                </div>
                <div id="copy"  style="margin-top:20px;">
                    <span class="f12">Copyright © 2013 <a href="https://voip91.com/" class="f14">phone91.com </a>, All rights reserved.</span>
                </div>
      </div>
    </div>
</div>
</div>
<div class="wrap"><div style="height:8px;background-color:#00B0F0;"></div></div>
</div>
</body>
</html>
EOF;
                             require('Mandrill.php');
                             Mandrill::setApiKey('zjlmyNcktAB5pnXO5TPdxg');
//				  $request_json = '
//                {"type":"messages",
//                "call":"send",
//                "message":{"html":"'.$mailData.'" , 
//                "text": "example text", 
//                "subject": "Verify Your Email", 
//                "from_email": "admin@phone91.com", 
//                "from_name": "Phone91", 
//                "to":[{"email": "'.$email.'", "name": "'.$username.'"}],
//                "track_opens":"true",
//                "track_clicks":"true",
//                "auto_text":"true",
//                "url_strip_qs":"true"
//              
//               }}';
                               $request_json["type"]="messages";
                              $request_json["call"]="send";
                              $req["html"]=$mailData;
                               $req["subject"]="Welcome to Phone91";
                               $req["from_email"]= "support@phone91.com";
                               $req["from_name"]=  "Phone91";
                               $resTo["email"]= $email;			   
                               $resTo["name"]= $username;
                               $req["to"][]=$resTo;
                               $req["track_opens"]=  "true";
                               $req["track_clicks"]=  "true";				  
                               $req["auto_text"]=  "true";				  
                               $req["url_strip_qs"]=  "true";	
                               $request_json["message"]=$req;
                               $final= json_encode($request_json);
                                    $ret = Mandrill::call((array) json_decode($final));
                                    //if(!$ret->status=="sent")
                                    //	mail("rahul@hostnsoft.com", "Phone91 function_layer maindrill  fail", "query " . print_r($ret,1) . " Error " );
                                    /* $from_email = "info@callplz.com";
                                      $headers .= "X-Priority: 1\n";
                                      $headers .= "X-MSMail-Priority: High\n";
                                      $headers .= "Return-Path: <$from_email>\n";
                                      $headers .= "Reply-To: <".$from_email.">\n";
                                      $headers .= "From:  <" . $from_email . ">\n";
                                      $headers .= "X-Sender: <$from_email>\n";
                                      $headers .= "X-Mailer: PHP/" . phpversion();
                                      $to="rahulchordiya@gmail.com";
                                      mail($to,$subject,$query.$nine['mobiles'].$nine['message'].$userid.$tempentry.$result,$headers); 
                                    echo "Successfully Register Password send to mobile.";
//					header("location:firsttimeuser.php?username='".$_REQUEST['username']."'");
                            }
                         }
                    }
		}
		mysql_close($con);*/
    }
    
    
}//end of class
$signup_obj	=	new signup_class();//class object
?>