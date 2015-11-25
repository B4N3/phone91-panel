<?php

$id_active_call = $_GET['id_active_call'];

//$id_active_call=$argv[1];

//echo "$id_active_call";
if(preg_match('[^0-9\.]',$id_active_call) || empty($id_active_call))
{
    echo $_GET["callback"]."(Error invalid call id please provide a valid id)";
    exit();
}
$cmd1 = "/usr/sbin/asterisk -rx 'channel request hangup  ".$id_active_call." ' 2>&1 ";

$output = phone91exec($cmd1);
$a =  " output =\"".htmlentities($output)."\"";
echo $_GET["callback"]."(".$a.")"; 

?>