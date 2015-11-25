<?php
include("includes/config.php");
/*if(isset($_REQUEST['username']) && !isset($_REQUEST['mobileNumber0']) && !isset($_REQUEST['mobileNumber1']))
{
	$username=$_REQUEST['username'];
	$result=mysql_query("select login from clientsshared where login='$username'");
	$res=mysql_num_rows($result);			
	if($res!=0) 
	{
		echo 'already exist';		// User Id Exist
		exit;	
	}
	else
	{
		echo 'available';
	}
}*/
if(isset($_REQUEST['username']))
{
	function generatePassword ($length = 8)
	{
	
	  // start with a blank password
	  $password = "";
	
	  // define possible characters
	  $possible = "0123456789"; 
		
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
	//$location=$_REQUEST['location'];
	
	//$email=$_REQUEST['email'];
	//$cur=$_REQUEST['currency'];
	//$tariff_id=$_REQUEST['tariff'];
	//$id_reseller=$_REQUEST['reseller'];
	//$nine[user]=$_REQUEST['smsuser'];
	//$nine[password] = $_REQUEST['smspwd']; // sms text
	//$nine[sender] = $_REQUEST['domain']; // sms text
			
	
//to check if userid existes or not
	$result=mysql_query("select login from clientsshared where login='$username'");
	$res=mysql_num_rows($result);
			
	if($res!=0) 
	{
		echo 10;		// User Id Exist
		exit;	
	}
		else 
	{
		
		$pwd=generatePassword();
	$query="insert into clientsshared(login,password,type,id_tariff,account_state,tech_prefix,id_reseller,type2,type3,id_intrastate_tariff,id_currency,codecs,primary_codec) values('$username','$pwd',3277331,'292',0.0000,'SD:;ST:;DP:;TP:;CP:;SC:!$username','209',1,0,-1,'1',12,4)";
		$result=mysql_query($query)	or die(mysql_error());
		if($result)
		{
			
			// $query="insert into dialingplan (id_dialplan, telephone_number, priority, route_type, tech_prefix, dial_as, id_route, call_type, type, from_day, to_day, from_hour, to_hour, balance_share, fields, call_limit) values(NULL, '$username', 0, 0, 'DN:$username->$contact', '', 7, 1207959572, 0, 0, 6, 0, 2400, 100, '-1', 0)";
			
			/*dialingplan;
			200, '9100000011', 0, 0, 'DN:9100000011->919977871114', '', 7, 1207959572, 0, 0, 6, 0, 2400, 100, '-1', 0;*/
			
			//$result=mysql_query($query)	or die(mysql_error());
			//if($result)
			{
				echo 'Success '.$pwd;
				exit;
			}
		}
		else
		{
			echo 0;
			exit();
		}
	}
}
?>
