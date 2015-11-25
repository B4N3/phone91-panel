<?php
include("includes/config.php");


$rid = $_REQUEST['userid'];

if(!empty($_REQUEST['bgcolor'])){
$bgcolor = $_REQUEST['bgcolor'];
}else{
	$bgcolor = '666666';
}

// ****************************************************************************************************************//
$query="select account_state from clientsshared where login='".$rid."'";

$result=mysql_query($query)	or die(mysql_error());
if(mysql_num_rows($result))
$row=mysql_fetch_array($result);
else
$row[0]='error';

$strContents=$row[0];
     

echo $strContents;

?>