<?php
include("includes/config.php");
include("includes/functions.php");
$tbl = $_REQUEST['tbl'];
$col = $_REQUEST['col'];
$id = $_REQUEST['uid'];
$sql = " select ".$col." from ".$tbl." where id_client= '$id' ";
$rs = mysql_query($sql);

if(mysql_num_rows($rs)>0){	
echo mysql_result($rs,0,$col);
}else{
echo false;
}

?>