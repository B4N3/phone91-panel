<?php
include("includes/config.php");
include("includes/functions.php");
$user = $_SESSION['unm'];

$usr = $_POST['usr'];

$sql = " select a.*,b.name from clientsshared a left join currency_names b on a.id_currency=b.id where id_client = '".$usr."'";
$rs = mysql_query($sql);
if(mysql_num_rows($rs)>0){
	$amt = mysql_result($rs,0,'account_state');
	$cur = mysql_result($rs,0,'name');	
}
if(!($amt>0)){
	$amt = 0;
}


	$result=mysql_query("select account_state,id_currency from clientsshared where id_client='$usr'");
		
		$get_userinfo=mysql_fetch_array($result);
		$balance=$get_userinfo[account_state];
		$cid = $get_userinfo[id_currency];
		
	$result=mysql_query("select name from currency_names where id='$cid'");
		
		$get_userinfo=mysql_fetch_array($result);
		$currency=$get_userinfo[name];
		
if(isset($_POST))
{
 	$pin=$_POST['pin'];
	
	$code=$_POST['code'];
	
	
//echo "select AED,USD,INR from pin where pin2='$pin' AND pin1='$code' AND flag=0";
$result=mysql_query("select AED,USD,INR from pin where pin2='$pin' AND pin1='$code' AND flag=0");
		$res=mysql_num_rows($result);
		$get_userinfo=mysql_fetch_array($result);
		$AED=$get_userinfo[AED];
		$USD = $get_userinfo[USD];
		$INR = $get_userinfo[INR];
		
		if($res!=1) {
		
		echo false;
        }
		else{
       if($cid==1)
		$talktime=$USD;
       else if($cid==2)
		$talktime=$INR;
	else if($cid==3)
		$talktime=$AED;

//to add payment to database
$result=mysql_query("select account_state,id_client,id_reseller from clientsshared where id_client='$usr'");
		
				$get_userinfo=mysql_fetch_array($result);
				$balance=$get_userinfo[account_state];
				$id_client=$get_userinfo[id_client];
				$id_reseller=$get_userinfo[id_reseller];
		
			$result=mysql_query("select contact_no,cntry_code from contact where userid='$id_client'");
		
				$get_userinfo=mysql_fetch_array($result);
				$contact_no=$get_userinfo[contact_no];
		 //to get code
		 		$cntry_code=$get_userinfo[cntry_code];
				
			$contact=$cntry_code.$contact_no;				
				$total_amount=$balance+$talktime;
		
			$query="update clientsshared set account_state='$total_amount' where id_client='$usr'";
/*	
************************************************************************************************************************************************************************************* */

			$result=mysql_query($query)
			or die(mysql_error());

/* *************************************************************************************************************************************************************************************

*/

	//to maintain reseller recharge commission

//$currency = $_POST['curr'];	
if($currency=="AED")
$recharge_amt=($talktime/3.65)*0.01;
else if($currency=="USD")
$recharge_amt=($talktime/50)*0.01;
else
  $recharge_amt=$talktime*0.01;
$result=mysql_query("select recharge from reseller_user where rid=".$id_reseller);
		
		$res=mysql_num_rows($result);
		$get_userinfo=mysql_fetch_array($result);
		if($res==0) {
		//insert
			$query="insert into reseller_user values('$id_reseller','$recharge_amt')";
			
		}
		else
		{
		$old_recharge=$get_userinfo[recharge];
		$recharge_amt=$old_recharge+$recharge_amt;
		//update
			$query="update reseller_user set recharge='$recharge_amt' where rid='$id_reseller'";
			
		}
/*	
************************************************************************************************************************************************************************************* */

	$result=mysql_query($query)
			or die(mysql_error());
	
/*
*************************************************************************************************************************************************************************************
*/	

			//to maintain history
	
	$query="insert into payments(id_client,client_type,money,data,type,description,invoice_id) values('$id_client',32,'$talktime',now(),1,'Recharge key',0)";
/*	
************************************************************************************************************************************************************************************* */
	$result=mysql_query($query)
	or die(mysql_error());
/*	
*************************************************************************************************************************************************************************************
*/
$query="update pin set flag=1 where pin2='$pin' AND pin1='$code'";
/*	
************************************************************************************************************************************************************************************* */
	$result=mysql_query($query)
	or die(mysql_error());
/*	
*************************************************************************************************************************************************************************************
*/

echo true;
        }

}
?>
