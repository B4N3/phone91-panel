<?php
include_once("classes/db_class.php");
class validation_class extends db_class
{
	function check_reseller()
	{
		if(!isset($_SESSION['client_type']))
			return false;
		else if($_SESSION['client_type']!=2 && $_SESSION['client_type']!=1)
			return false;
		if($_SESSION['client_type'] == 2 || $_SESSION['client_type'] ==3)
			return true;
		/*if($_SESSION['id']!=207)
			return false;
		else
			return true;*/
	}

	function check_user()
	{
		if(!isset($_SESSION['id']))
			return false;
		else
			return true;
			
		/*if($_SESSION['uty']!=3)
			return false;
		else
			return true;*/
	}
	function check_admin()
	{
		//if((!isset($_SESSION['id']))|| !is_admin())//edit by sapna
		if(!isset($_SESSION['id'])||$_SESSION['client_type']!=1)
			return false;
		else
			return true;
	}
	function expire()
	{
		$_SESSION['msg']="You Need To Login To Access Your Account";
		//header("Location: index.php");
		?>
	<script>
		self.parent.location.href = 'index.php';
	</script>
	<?php
		exit();
	}
	
	function check_empty($field,$msg)
	{
		if(trim($field)=="")
		{
			if($msg!="")
			{
				$_SESSION['msg_type']=3;
				 $_SESSION['msg'].="Please enter ".$msg."<br>"; 
			}
			return false;
		}
		return true;
	}
	
	function check_parent_reseller($res_id)
	{
	$dbh=$this->connect_db();
	$sql="select * from clientsshared where id_client='".$res_id."' and id_reseller='".$_SESSION['id']."'";
	$result=mysql_query($sql,$dbh);
	mysql_close($dbh);
	if((!$result)||(mysql_num_rows($result)<=0))
		die("You Are Not Authorized To View This Page");
	else 
		return 1;
	}

}//end of class

$val_obj	=	new validation_class();//class object
?>