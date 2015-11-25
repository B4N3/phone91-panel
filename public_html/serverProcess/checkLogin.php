<?php
include("includes/config.php");
include("includes/functions.php");
		if(isset($_REQUEST['uname']) && isset($_REQUEST['pass'])){
			
				$uname = validData($_REQUEST['uname']);
				$pass  = validData($_REQUEST['pass']);
		$rid  = validData($_REQUEST['rid']);
					$sql = " select * from clientsshared where login = '$uname' and password='$pass' and id_reseller='$rid'";			
			$rs = mysql_query($sql) or die(mysql_error());	
			if(mysql_num_rows($rs)>0){	
				echo mysql_result($rs,0,'id_client');
			}
			else{
					echo false;
				}			
		}

?>