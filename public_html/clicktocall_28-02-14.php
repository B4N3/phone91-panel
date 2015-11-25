<?php
include("config.php");

$startTimTracker = date(DATEFORMAT);

$funObj = new fun();

if (!$funObj->login_validate()) {
	$funObj->redirect("index.php");
}

$trackMsg = 'user reached at click to call page';

$trackDtl['request'] = $_REQUEST;

$trackDtl['session'] = $_SESSION;

$trackId = $funObj->callTracker(null,$startTimTracker,$_SESSION['userid'],$trackMsg,$trackDtl,__FILE__,__LINE__);

$startTimTracker = date(DATEFORMAT);

include(CLASS_DIR."call_class.php");
error_reporting(0);
$callObj = new call_class();

//$fdigit = -1;
$_SESSION['status'] = "Connecting...";
$dest = $_REQUEST['q'];
$source = $_REQUEST['d'];

//for a new call
$nine["dest"] = $dest;
$nine["source"] = $source;

//$query = "insert into calllog (userid,no,date) values ('$_SESSION[userid]','$dest',now())";
//$result1 = mysqli_query($query) or $error=(mysqli_error());
//if(!$result1)
//{
//	//Mail
//	//$error
//}
#table name
$table='91_userLogin';

$condition = "userId = ".$_SESSION['userid']."";

#fetch all detail form login table where userid is session user id 
$funObj->db->select('*')->from($table)->where($condition);


$result = ($funObj->db->execute());

//call tracker
if(!$result)
{
    $trackMsg = 'problem while get user login detail';
    $trackDtl['condition'] = $condition;
    $trackId = $funObj->callTracker($trackId,$startTimTracker,'',$trackMsg,$trackDtl,_FILE__,__LINE__);
}


if ($result->num_rows > 0) 
{
        $row = $result->fetch_array(MYSQL_ASSOC);                         
        
         extract($row);          //userId,userName,password,isBlocked,type      
         $login = $userName;
         $pwd = $password;
 }

//$result = mysqli_query("select login,password from clientsshared where login='$_SESSION[username]'");
//while ($get_userinfo = mysql_fetch_array($result)) {
//	$login = $get_userinfo[0];
//	$pwd = $get_userinfo[1];
//}
$nine["login"] = $login;
$nine["password"] = $pwd;

$trackMsg = 'user login detail';
$trackDtl['credential'] = $nine;
$trackId = $funObj->callTracker($trackId,$startTimTracker,'',$trackMsg,$trackDtl,_FILE__,__LINE__);


$msgid = $callObj->Call($nine);

//unset objects
unset($funObj);
unset($callObj);

echo $msgid;
//mysql_close($con);
?>