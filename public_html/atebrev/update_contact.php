<?php
echo "update_contact";
include('config.php');
//validate login
if(!$funobj->login_validate()){
	header("Location: index.php");
}
$con = $funobj->connect();
$tempresult = mysql_query("select * from tempcontact where userid='" . $_SESSION['id_cl'] . "'") or die(mysql_error());
if (mysql_num_rows($tempresult) > 0) { //if their is entry into temp table
	$flag = '1';
	$get_details = mysql_fetch_array($tempresult);
	$code = $get_details['cntry_code'];
	$phone = $get_details['contact_no'];
	$email = $get_details['email'];
	$confirm_code = $get_details['confirm_code'];
        $count = $get_details['count'];
        $ddate = $get_details['date'];
} else {
	$tempresult = mysql_query("select * from contact where userid='" . $_SESSION['id_cl'] . "'") or die(mysql_error());
	if (mysql_num_rows($tempresult) > 0) { //if their is entry into temp table
		$flag = '0';
		$get_details = mysql_fetch_array($tempresult);
		$code = $get_details['cntry_code'];
		$phone = $get_details['contact_no'];
		$email = $get_details['email'];
		$confirm = $get_details['confirm'];
		$confirm_code = $get_details['confirm_code'];
		if ($confirm == '0' && strlen($confirm_code) > 5) {
			$flag = '1';
		}
	}
}

if (isset($_POST['country_code']) && isset($_POST['resend_phone']) ) 
{
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
    
        //code to resend code
	if ($_POST['country_code'] == $code && $_POST['resend_phone'] == $phone && strlen($confirm_code)  >0) 
        {
		$d["message"] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
		
		$d["sender"]="Phonee";					
                $d["mobiles"]=$code . $phone;
//for 91 user
                
		$nine[mobiles] = $code . $phone;
		$nine[message] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
		$nine[sender] = "Phonee";
		//Call function
		if ($code == "91")
                {
			$funobj->SendSMS91($nine);
                        mail("sameer@hostnsoft.com","phone880",print_r($nine,1));
                }
		else
			$funobj->SendSMSUSD($d);
		echo $count;
		exit;
	}
     }//end of if for count
     else
	echo 0;
     exit;
}//end of condition for resend text

//code to resend voice for confirmation code
if (isset($_POST['country_code']) && isset($_POST['resend_voice'])) 
{
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
        $count = $count -1;
	
        //code to resend voice
	if ($_POST['country_code'] == $code && $_POST['resend_voice'] == $phone && strlen($confirm_code) > 0) 
        {
		$mobile_no = $code . $phone;
		$vcode = $confirm_code;
		$funobj->mobile_verification_api($mobile_no, $vcode);
		echo $count;
		exit;
	}
    }//end of if for count
    else
	echo 0;
    exit;
}//end of resend code voice condition
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
//If user want to delete the unconfirm mobile
if (isset($_POST['confirmation_code']) && isset($_POST['delete'])) {
	if (strlen($_POST['confirmation_code']) == 0) {
		$result = mysql_query("delete from tempcontact where userid='" . $_SESSION['id_cl'] . "'");
		$flag = '0'; //Number is confirmed
		if ($result)
			echo 'Number is deleted Now';
                header("Location: userhome.php");
		exit();
	}
}
//If user want to confirm mobile
if (isset($_POST['confirmation_code'])) 
{
        //validation for confirmation
	if (strlen($_POST['confirmation_code']) > 0) 
        {
            //This query check for contact information in contact and temp - contact
            $tempresult = mysql_query("select * from tempcontact where userid='" . $_SESSION['id_cl'] . "' and confirm_code='" . $_POST['confirmation_code'] . "'") or die(mysql_error());
            if (mysql_num_rows($tempresult) > 0) 
            { //if their is entry into tempcontact table
                    $get_userinfo = mysql_fetch_array($tempresult);
                    $confirm_flag = 1;
            } 
            else //if entry not exist in tempcontact
            {
                //check if entry exist in contact table for this confirmation code
                $result = mysql_query("select cntry_code,contact_no,confirm,confirm_code,email from contact where userid='" . $_SESSION['id_cl'] . "' and confirm_code ='" . $_POST['confirmation_code'] . "'") or die(mysql_error());
                if (mysql_num_rows($result) > 0) 
                { //If information exists
                     $get_userinfo = mysql_fetch_array($result);
                     $confirm_flag = 1;
                }
                else
                        $confirm_flag = 0;
            }
            if ($confirm_flag == 1) 
            {
                //get required values
                $confirm = $get_userinfo['confirm'];
                $tempcode = $get_userinfo['cntry_code'];
                $tempphone = $get_userinfo['contact_no'];
                $confirm_code = $get_userinfo['confirm_code'];
                //Query check whether number is already assign to another user	
                $checkexist = mysql_query("select * from contact where cntry_code='$tempcode' and confirm='1' and contact_no='$tempphone'");
                if (mysql_num_rows($checkexist) == 0) 
                { //if number is not assign to any other user
                    //This will update the contact information of user when 
                    $updateresult = mysql_query("UPDATE contact SET confirm='1',cntry_code='$tempcode',contact_no='$tempphone' WHERE userid='" . $_SESSION['id_cl'] . "'") or die("Error while update" . mysql_error());
                    if (mysql_affected_rows() == 0) 
                    {//if contact information is not found 
                        //This will insert new details and make the number confirm
                        $query = mysql_query("insert into contact values('" . $_SESSION['id_cl'] . "','$tempphone','','$tempcode','$confirm_code','1')");
                    }
                        if ($updateresult or $query) 
                        {
                                $result = mysql_query("delete from tempcontact where userid='" . $_SESSION['id_cl'] . "'");
                                $updateqry = mysql_query("UPDATE clientsshared set tech_prefix =CONCAT('SD:;ST:;DP:;TP:;CP:!',$code,$phone,';SC:') where id_client='" . $_SESSION['id_cl'] . "'") or die(mysql_error());
                                $code = $tempcode;
                                $phone = $tempphone;
                                //$email = $tempemail;
                                                                                header("Location: userhome.php");
                        }
                        if ($result && $updateqry)
                                $flag = '0'; //Number is confirmed
                }
                else 
                { //in case of number assign to another user
                        $flag = '1';
                        $get_details = mysql_fetch_array($checkexist);
                        $userid = $get_details['userid'];
                        if ($userid != $_SESSION['id_cl']) 
                        {
                                $result = mysql_query("select login from clientsshared where id_client='$userid'");
                                $get_details = mysql_fetch_array($result);
                                $username = $get_details['login'];
                                
                                echo 2;
                        } 
                        else 
                        {
                                
                                echo 3;
                        }
                }
            }
            //end of if code is matched
            else 
            { //if code is not match
                    $flag = '1';
                    
		    echo 4;

            }
	}
        exit();
}
if ( isset($_POST['location']) && isset($_POST['code']) && isset($_POST['mobileNumber'])) {  //this this when user come to this page and their is no element in post
	
	$location = $_POST['location'];
	$code = $_POST['code'];
	$code = substr($code, 1, strlen($code) - 1);
	$phone = $_POST['mobileNumber'];
	if (strlen(trim($location)) < 1 || $location == 'nocountry') {
		$arr = array('id' => '1', 'error' => 'Please Select Country');
		echo json_encode($arr);
		exit();
	}
	if (strlen(trim($code)) < 1 || $code == '') {
		$arr = array('id' => '2', 'error' => 'Please Provide Country Code');
		echo json_encode($arr);
		exit();
	}
//	if (strlen(trim($email)) < 1 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//		$arr = array('id' => '3', 'error' => 'Please Provide proper email Address');
//		echo json_encode($arr);
//		exit();
////			$valid=0;
//	}
	if (strlen($code) > 0 && strlen($phone) > 0) { //If posted information is new			
		$confirm_code = conf_code();
		$checkexist = mysql_query("select * from contact where cntry_code='$code' and confirm='1' and contact_no='$phone'");
		if (mysql_num_rows($checkexist) == 0) { //if number is not assign to any other user
			$delete = mysql_query("delete from tempcontact where userid='" . $_SESSION['id_cl'] . "'") or die(mysql_error()); //it will delete old data from tempcontact
                        $todayDate = date("Y-m-d");
			$query = "insert into tempcontact values('" . $_SESSION['id_cl'] . "','$phone','','$code','$confirm_code',0,'$todayDate',5)";
                        
			$result = mysql_query($query) or die(mysql_error());
			$flag = '1';
			//Assign Variables for sending sms to user
			$d["text"] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
			$d["to"] = $code . $phone;
			//for 91 user
			$nine[mobiles] = $phone;
			$nine[message] = "Enter this confirmation code " . $confirm_code . " to confirm your mobile number."; // sms text
			//Call function
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
			    $delete = mysql_query("delete from tempcontact where userid='" . $_SESSION['id_cl'] . "'") or die(mysql_error()); //it will delete old data from tempcontact
			}else{
			    echo 1;
			}
                                                
		}
		else { //in case of number assign to another user
			$flag = '0';
			$get_details = mysql_fetch_array($checkexist);
			$userid = $get_details['userid'];
			if ($userid != $_SESSION['id_cl']) {
				$result = mysql_query("select login from clientsshared where id_client='$userid'");
				$get_details = mysql_fetch_array($result);
				$username = $get_details['login'];
				$str= "Sorry this Number is already used with Other username.";
			} else {/*
			  ?>
			  <script>
			  alert('This Number is already confirmed by you.');
			  </script>
			  <? */
				$str= "Sorry this Number is already confirm by you.";
                                
			}
		}
	} else {
		$str= 'Incomplete form';
		//$flag='0';
	}
        exit();
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
	<head>
		<!--<meta https-equiv="Content-Type" content="text/html; charset=utf-8" />-->
		<title>Phone91</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<link rel="stylesheet" type="text/css" href="css/phone91v4.css" />
		<script language="javascript" src="js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="js/sign_up.js"></script>
	</head>
	<body>
		<div id='notification'>
		</div>
		<div id="loading" class="rounded5" style="display:none" ><img src="images/loading.gif" /></div>
		<div id="main">		
		<div id="middle">
			<div class="centerinner">
                            <?php
                            if(strlen($str)>3)
                                echo "<span class='style23'>".$str."</span>";
                            ?>
				
				<?php
				$dbh = $funobj->connect();
				$result = mysql_query("select cntry_code,contact_no,confirm,email from contact where userid='" . $_SESSION['id_cl'] . "'", $dbh);
				mysql_close($dbh);
				if (mysql_num_rows($result) > 0) {//if details exist
					while ($get_userinfo = mysql_fetch_array($result)) {
						if ($get_userinfo['confirm'] == '1') {//and user confirm his/her mobile number
							$_SESSION['contact_no'] = $get_userinfo['contact_no'];
							$_SESSION['email'] = $get_userinfo['email'];
							echo "Your Verified Number: <h3>".$get_userinfo['cntry_code'] . $get_userinfo['contact_no']."</h3>";
						} 
					}
				}
//flag 1 use to show that their is a number which is not confirmed with phone91
				if ($flag == '1') {
					echo "<span class='style23'>Please Verify Your Number</span><br />";
					?>
					<!-- Confirmation div in case of number is not in contact  -->
					<div class="form1">
						<form name="confirm" method="post" id="confirm_form1" action="update_contact.php">
							<h3>Enter Confirmation-Code For
								<?= $code . '' . $phone ?></h3>
							<div >
								&nbsp;<input type="hidden" id="resend_code" value="<?= $code ?>"/>
								<input type="hidden" id="resend_phone" value="<?php echo $phone; ?>"/>
								<input type="hidden" id="resend_phone1" value="<?php echo $phone; ?>"/>
								<div align="left"><input type="text" name="confirmation_code" /></div>
								<div align="left"><input type="submit" value="Confirm"  /></div>
							</div>
							<div>
								<span class="style23">Code Not Received?&nbsp;&nbsp;
                                     <a href="javascript:;" id="call_me_code" class="button blue" style="font-size:20px;" >Resend Using Call</a>                                   
									OR&nbsp;&nbsp
                                    
										<a href="javascript:;" id="resend_me_code1" class="button blue" style="font-size:18px;">Resend Using Text</a>
									 
                                     </span><span id="mssg"></span>
							</div>
							<div>
								<lable>Not your number </lable>
								<input name="delete" id="delete" onClick="return confirmdelete()" type="submit" value="delete" />
							</div>
						</form>
					</div>
				<?php } 
				else {?>
				<br />
				<div id="msg">
					<?php
					if (isset($_GET['msg'])) {
						if ($_GET['msg'] == 'Not-Confirm')
							echo "<span  class='style23'>Your number is not Confirm. Please Confirm Now</span>";
					}
					/* $tempresult=mysql_query("select * from contact where userid='".$_SESSION['id_cl']."' and confirm=1") or die(mysql_error());	
					  if(mysql_num_rows($tempresult)>0 ) //if their is entry into temp table
					  {
					  $get_details=mysql_fetch_array($tempresult);
					  $confirm=$get_details['confirm'];
					  $current_code=$get_details['cntry_code'];
					  $current_phone=$get_details['contact_no'];
					  $current_email=$get_details['email'];
					  } */
					?>
				</div>
				<!--Add or update information   -->
				<div class="form1">
					<form name="update_contact" id="new_contact" method="post" action="update_contact.php" >
						</span>
						<label>Choose your country</label>
						<select tabindex="1" onChange="getCode();" name="location" id="location" class="uField valid">
												<option value="93">Afghanistan</option><option value="355">Albania</option><option value="213">Algeria</option><option value="376">Andorra</option><option value="244">Angola</option><option value="1">Antigua and Barbuda</option><option value="54">Argentina</option><option value="374">Armenia</option><option value="61">Australia</option><option value="43">Austria</option><option value="994">Azerbaijan</option><option value="973">Bahrain</option><option value="880">Bangladesh</option><option value="1">Barbados</option><option value="32">Belgium</option><option value="591">Bolivia</option><option value="387">Bosnia and Herzegovina</option><option value="55">Brazil</option><option value="44">British Indian Ocean Territory</option><option value="359">Bulgaria</option><option value="1">Canada</option><option value="235">Chad</option><option value="56">Chile</option><option value="86">China</option><option value="57">Colombia</option><option value="682">Cook Islands</option><option value="506">Costa Rica</option><option value="385">Croatia</option><option value="53">Cuba</option><option value="357">Cyprus</option><option value="420">Czech Republic</option><option value="45">Denmark</option><option value="1">Dominican Republic</option><option value="593">Ecuador</option><option value="20">Egypt</option><option value="503">El Salvador</option><option value="372">Estonia</option><option value="358">Finland</option><option value="33">France</option><option value="49">Germany</option><option value="233">Ghana</option><option value="350">Gibraltar</option><option value="30">Greece</option><option value="590">Guadeloupe</option><option value="502">Guatemala</option><option value="44">Guernsey</option><option value="509">Haiti</option><option value="504">Honduras</option><option value="36">Hungary</option><option value="354">Iceland</option><option value="91">India</option><option value="98">Iran</option><option value="964">Iraq</option><option value="353">Ireland</option><option value="972">Israel</option><option value="39">Italy</option><option value="225">Ivory Coast</option><option value="1">Jamaica</option><option value="81">Japan</option><option value="44">Jersey</option><option value="962">Jordan</option><option value="254">Kenya</option><option value="686">Kiribati</option><option value="965">Kuwait</option><option value="856">Laos</option><option value="961">Lebanon</option><option value="231">Liberia</option><option value="352">Luxembourg</option><option value="261">Madagascar</option><option value="60">Malaysia</option><option value="356">Malta</option><option value="596">Martinique</option><option value="230">Mauritius</option><option value="52">Mexico</option><option value="212">Morocco</option><option value="977">Nepal</option><option value="31">Netherlands</option><option value="64">New Zealand</option><option value="505">Nicaragua</option><option value="234">Nigeria</option><option value="47">Norway</option><option value="968">Oman</option><option value="92">Pakistan</option><option value="507">Panama</option><option value="595">Paraguay</option><option value="51">Peru</option><option value="63">Philippines</option><option value="48">Poland</option><option value="351">Portugal</option><option value="974">Qatar</option><option value="262">Reunion</option><option value="40">Romania</option><option value="7">Russia</option><option value="250">Rwanda</option><option value="966">Saudi Arabia</option><option value="221">Senegal</option><option value="381">Serbia</option><option value="65">Singapore</option><option value="421">Slovakia</option><option value="386">Slovenia</option><option value="252">Somalia</option><option value="27">South Africa</option><option value="34">Spain</option><option value="94">Sri Lanka</option><option value="46">Sweden</option><option value="41">Switzerland</option><option value="963">Syria</option><option value="886">Taiwan</option><option value="66">Thailand</option><option value="1">Trinidad and Tobago</option><option value="216">Tunisia</option><option value="90">Turkey</option><option value="256">Uganda</option><option value="380">Ukraine</option><option value="971">United Arab Emirates</option><option value="44">United Kingdom</option><option value="1">United States</option><option value="1">United States Minor Outlying Islands</option><option value="598">Uruguay</option><option value="58">Venezuela</option><option value="84">Vietnam</option><option value="967">Yemen</option><option value="263">Zimbabwe</option>
						</select>						<div></div>
						<label>Phone Number</label><br />
						<table><tr><td><input name='code' value="code" type="text" id="code" /></td>
                                                <td style="padding:0 0 0 5px;"><input type="text" name='mobileNumber' id='mobileNumber' onFocus="if (this.value == 'Phone number') { this.value = ''; }" value="Phone number" />
								</td></tr>
							<tr><td colspan="2"><div id="moberror"></div></td></tr></table>
						<div></div>
						<div>
							<input type="submit" name="register" id="register" value="Update"/>
						</div>
					</form>
                <table width="80%" border="0" style="margin-top:40px; color:#777;">
					<tr>
						<td class="sitecontent"><div>Phone91 is working on all networks in UAE..Guranteed.</div></td>
					</tr>
				</table>
				</div>
				<!--Information footer   -->
				
				<?php }?>
			</div></div>
			</div>
		<? mysql_close($con); ?>
		
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script language="javascript" type="text/javascript">
			$(document).ready(function(){
                            
                                //function to resend code
				resend_code=	function(){
					$('#resend_me_code').html('Please Wait...');
					var code = $("#resend_code").val();
					var phone = $("#resend_phone1").val()
				
					$.ajax({
						type: "POST",
						url: "update_contact.php",
						data: { country_code: code,resend_phone:phone },
						success: function(msg)
						{     
                                                    
                                                    $('#resend_me_code').unbind('click');
                                                        //alert(msg);
                                                    if(msg > 0)  
                                                        {
                                                            $('#resend_me_code').html('Successfully send');
                                                            $('#mssg').html('you have remainig '+msg+' attempts to resend');
                                                        }
                                                    else
                                                        $('#mssg').html('(You already resend the code)'); 
						}
					});
				};
                                
                               //code to resend voice code
				callme_code=    function(){
					$('#call_me_code').html('Please Wait...');
					var code = $("#resend_code").val();
					var phone = $("#resend_phone1").val();
					console.log(code+phone);
					$.ajax({
						type: "POST",
						url: "update_contact.php",
						data: { country_code: code,resend_voice:phone },
						success: function(msg)
						{     
                                                   
                                                    $('#call_me_code').unbind('click');
                                                    //check if msg greater than zero,then so remaining attemtps
                                                    if(msg > 0) 
                                                        {
                                                             $('#call_me_code').html('Successfully send');
                                                            $('#mssg').html('you have remainig '+msg+' attempts to recall');
                                                        }
                                                    else
                                                        $('#mssg').html('(Code was resend to your number)');
						}
					});
				};
				
                                //call function on click
				$("#resend_me_code1").click(resend_code);
				$("#call_me_code").click(callme_code);
				$('#new_contact').ajaxForm({ beforeSubmit: validate,success: jsonResponse });
 
				$('#confirm_form1').ajaxForm({success: showResponse});
			});
                        
			function jsonResponse(responseText, statusText, xhr, $form)
			{
				
                                  $("#midright").html("<img src='images/loading.gif' />").load("inc/my_setting.php?active=contact");
			}
			function showResponse(responseText, statusText, xhr, $form)
			{
				//alert(responseText);
                                                                $("#midright").html("<img src='images/loading.gif' />").load("inc/my_setting.php?active=contact");				
			}
			function validate()
			{
				//alert('test');
			}
			function confirmdelete()
			{
				//if(document.getElementById("delete").value=="nocountry")
				return confirm("Are you sure to delete");
			}
		</script>
	</body>
</html>
