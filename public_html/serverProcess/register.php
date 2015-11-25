<?php
include("includes/config.php");
include("includes/functions.php");
include('smsconfig.php');

if(isset($_REQUEST))
{
				function generatePassword ($length = 8)
				{
				
				  // start with a blank password
				  $password = "";
				
				  // define possible characters
				  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
					
				  // set up a counter
				  $i = 0; 
					
				  // add random characters to $password until $length is reached
				  while ($i < $length) { 
				
					// pick a random character from the possible ones
					$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
						
					// we don't want this character if it's already in the password
					if (!strstr($password, $char)) { 
					  $password .= $char;
					  $i++;
					}
				
				  }
				
				  // done!
				  return $password;
				
				}

				$username=$_REQUEST['username'];
				$location=$_REQUEST['location'];
				$code=$_REQUEST['mobileNumber0'];
				//    $code = substr($code,1,strlen($code)-1);
				$phone=$_REQUEST['mobileNumber1'];
				$email=$_REQUEST['email'];
				$cur=$_REQUEST['currency'];
				$tariff_id=$_REQUEST['tariff'];
				$id_reseller=$_REQUEST['reseller'];
				$nine[user]=$_REQUEST['smsuser'];
				$nine[password] = $_REQUEST['smspwd']; // sms text
			$nine[sender] = $_REQUEST['domain']; // sms text
				
				if(substr($phone,0,1)==0)
					$phone = substr($phone,1,strlen($phone)-1);
				$pwd=generatePassword();
				$contact=$code.$phone;

	
//to check if userid existes or not
					$result=mysql_query("select login from clientsshared where login='$username'");
							$res=mysql_num_rows($result);
								
							if($res!=0) {

										echo 10;		// User Id Exist
										exit;

        					}
								else 
							{
		
							//to check if phoneno existes or not
						$result=mysql_query("select userid from contact where contact_no='$phone' and cntry_code='$code'");
								$res=mysql_num_rows($result);
									
								if($res!=0) {
											echo 20;      // Phone No Exist
											exit;
						        }else {
		
							$query="insert into clientsshared(login,password,type,id_tariff,account_state,tech_prefix,id_reseller,type2,type3,id_intrastate_tariff,id_currency,codecs,primary_codec) values('$username','$pwd',3277331,'$tariff_id',0.0000,'SD:;ST:;DP:;TP:;CP:;SC:','$id_reseller',1,0,-1,'$cur',12,4)";
							$result=mysql_query($query)
								or die(mysql_error());
							
							
								$result=mysql_query("select max(id_client) from clientsshared");
									
									$get_userinfo=mysql_fetch_array($result);
									$userid=$get_userinfo[0];
									
							$query="insert into contact() values('$userid','$phone','$email','$code')";
							$result=mysql_query($query)
								or die(mysql_error());

	
//Assign Variables for sending sms to user
							//$d["text"]= "you are successfully registered your username is: ".$username." and password id: ".$pwd. " Please recharge to start using this account."; // sms text
							//$d["to"]=$contact;

//for 91 user
							$nine[mobiles]=$contact;
							$nine[message] = "you are successfully registered your username is ".$username." and password id ".$pwd. " Please recharge to start using this account."; // sms text

//Call function
							//if($code=="91")
								SendSMS($nine);
							//else
							//	SendSMSUSD($d);
								echo 50;
//$url= "firsttimeuser.php?username=".$_REQUEST['username'];
//header('Location:index.php');
							    // Registration Successfully
							exit;
}
}
}
?>
