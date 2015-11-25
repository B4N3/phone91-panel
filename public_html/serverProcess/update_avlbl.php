<?php include("includes/config.php");
if(isset($_REQUEST['userid']) && isset($_REQUEST['password'])  && isset($_REQUEST['number'])  && isset($_REQUEST['v_number']) )
{
//	http://phone91.com/serverProcess/update_avlbl.php?userid=avlbl_screen&password=89706412&number=919981534313&v_number=91026501982

		$login=$_REQUEST['userid'];
		$pwd=$_REQUEST['password'];
		$contact='00000'.$_REQUEST['number'];
		$vnumber=$_REQUEST['v_number'];
		
		$result=mysql_query("select login,password from clientsshared where login='$login' and password='$pwd'");
		if(mysql_num_rows($result)>0)
		{				// 'DN:00000->;CP:!52525252;'
	
		$query="insert into dialingplan (id_dialplan, telephone_number, priority, route_type, tech_prefix, dial_as, id_route, call_type, type, from_day, to_day, from_hour, to_hour, balance_share, fields, call_limit) values(NULL, '$contact', 0, 0, 'DN:00000->;CP:!$vnumber', '', 7, 1207959572, 0, 0, 6, 0, 2400, 100, '-1', 0)";
			/*dialingplan;
			200, '9100000011', 0, 0, 'DN:9100000011->919977871114', '', 7, 1207959572, 0, 0, 6, 0, 2400, 100, '-1', 0;*/
			
			$result=mysql_query($query)	or die(mysql_error());
			if($result)
			{
				echo 'Success ';
				exit;
			}
		}
		else
		{
			echo 'username and/or password not match';
		}
}
?>
