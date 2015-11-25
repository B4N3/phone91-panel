<?php
include("config.php");

$startTimTracker = date(DATEFORMAT);

$funObj = new fun();

if (!$funObj->login_validate()) 
{
	$funObj->redirect("index.php");
}

$trackMsg = 'user reached at click to call page';

$trackDtl['request'] = $_REQUEST;

$trackDtl['session'] = $_SESSION;

$trackId = $funObj->callTracker(null,$startTimTracker,$_SESSION['userid'],$trackMsg,$trackDtl,__FILE__,__LINE__);

$startTimTracker = date(DATEFORMAT);

include(CLASS_DIR."call_class_ankit.php");
error_reporting(-1);
$callObj = new call_class();

$_SESSION['status'] = "Connecting...";
$dest = $_REQUEST['q'];
$source = $_REQUEST['d'];

//for a new call
$nine["dest"] = $dest;
$nine["source"] = $source;


#table name
$table='91_userLogin';

$condition = "userId = ".$_SESSION['userid']."";

#fetch all detail form login table where userid is session user id 
$result = $funObj->selectData('*',$table,$condition);
//call tracker
if(!$result)
{
    $trackMsg = 'problem while get user login detail';
    $trackDtl['condition'] = $condition;
    
    $funObj->callError($trackMsg.',condition:'.$condition,__FILE__,__LINE__);
    
    $trackId = $funObj->callTracker($trackId,$startTimTracker,'',$trackMsg,$trackDtl,__FILE__,__LINE__);
}


if ($result->num_rows > 0) 
{
        $row = $result->fetch_array(MYSQLI_ASSOC);                         
        
         extract($row);          //userId,userName,password,isBlocked,type      
         $login = $userName;
         $pwd = $password;
 }


$nine["login"] = $login;
$nine["password"] = $pwd;

$trackMsg = 'user login detail';
$trackDtl['credential'] = $nine;
$trackId = $funObj->callTracker($trackId,$startTimTracker,'',$trackMsg,$trackDtl,__FILE__,__LINE__);

//call function for calling
$msgid = $callObj->Call($nine,$trackId);

//unset objects
unset($funObj);
unset($callObj);

echo $msgid;
?>