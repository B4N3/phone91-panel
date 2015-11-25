<?php
include("includes/config.php");
include("includes/functions.php");
$uname = $_REQUEST['unm'];
$sql = " select * from clientsshared where login = '$uname' ";
$rs = mysql_query($sql)
	or die(mysql_error());

if(mysql_num_rows($rs)>0){	
echo " <font color='black'><b>User Id already Exists ! </b></font>";
}else{
echo " <font color='black'><b>User Id is Available...</b></font>";
}

?>