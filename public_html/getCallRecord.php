<?php

/*
 * @author Nidhi<nidhi@walkover.in>
 * This file is download file from the link of call record
 * Here we are getting authentication key and file name from request
 * 
 */
include_once('config.php');

$funobj = new fun();

#- getting authentication key from request.
$auth = (isset($_REQUEST['auth']) and !empty($_REQUEST['auth']))?$_REQUEST['auth'] :'';

if(empty($auth))
{
    echo json_encode(array('status' => 'error' , "message" => "Invalid Authentication Key"));
    die();
}

#- getting filename from request.
$fileName = (isset($_REQUEST['file']) and !empty($_REQUEST['file']))?$_REQUEST['file'] :'';

if(empty($fileName))
{
    echo json_encode(array('status' => 'error' , "message" => "Invalid file name"));
      die();
}

 $auth = $funobj->sql_safe_injection($auth);
 $fileName = $funobj->sql_safe_injection($fileName);

#- checking this authenticationkey and file name exist in db 
$result = $funobj->selectData('*','91_checkAuth',"auth='". $auth."'  and fileName='".$fileName."'");

if ($result->num_rows > 0) 
{ 
    while ($res = $result->fetch_array(MYSQLI_ASSOC)) 
    {
        $file="zip/".$res['fileName']; //file location
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }
}
else
{
     echo json_encode(array('status' => 'error' , "message" => "Invalid authentication key or file name"));
       die();
}
 
?>