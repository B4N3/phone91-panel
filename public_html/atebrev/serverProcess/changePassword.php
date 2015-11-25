<?php
include("includes/config.php");
include("includes/functions.php");
		$user = $_REQUEST['user'];
		$oldpass = $_REQUEST['oldpass'];
		$pass = $_REQUEST['pass'];
		$pass = validData($pass);
		if($pass!=""){
			$sql = " select * from clientsshared where password='".$pass."'";
			$rs = mysql_query($sql);
			
			$sql2 = " select * from clientsshared where id_client='".$user."' and password='".$oldpass."'";
			$rs2 = mysql_query($sql2);
			
			if(mysql_num_rows($rs)>0||mysql_num_rows($rs2)==0)
			{
				echo false;
			}else
			{
				$sql = " update clientsshared set password='$pass' where id_client='".$user."' and password='".$oldpass."'";
				mysql_query($sql) or die(mysql_error());
				echo true;
			}
		}
			


?>