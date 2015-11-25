<?php
//include("verify.php");
include("includes/config.php");

$oldp = $_REQUEST['p'];
$usr = $_REQUEST['user'];
$sql = " select * from clientsshared where id_client=".$usr;
$rs = mysql_query($sql);
$password = mysql_result($rs,0,'password');

if($oldp==$password){
echo true;
}else{
echo false;
}

?>