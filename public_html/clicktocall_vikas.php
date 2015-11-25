<?php
include("config.php");
//die("Bye1");
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


require_once(CLASS_DIR."call_class.php");
//die("Bye");

error_reporting(-1);
$callObj = new call_class();

//$fdigit = -1;
$_SESSION['status'] = "Connecting...";
$dest = $_REQUEST['q'];
$source = $_REQUEST['d'];

if(preg_match('/[^0-9]+/', $source) || $source == "" || strlen($source) > 18 || strlen($source) < 7 )
        die("Invalid source Number");
if(preg_match('/[^0-9]+/', $dest) || $dest == "" || strlen($dest) < 7 || strlen($dest) >18)
        die("Invalid destination Number");

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
$result =  $funObj->getUserInformation($_SESSION['userid'],1);


//$result = ($funObj->db->execute());
die("Bye");

//call tracker
if(empty($result))
{
    $trackMsg = 'problem while get user login detail';
    $trackDtl['condition'] = $condition;
    $trackId = $funObj->callTracker($trackId,$startTimTracker,'',$trackMsg,$trackDtl,_FILE__,__LINE__);
}


     
 $login = $result['userName'];
 $pwd = $result['password'];
 

//$result = mysqli_query("select login,password from clientsshared where login='$_SESSION[username]'");
//while ($get_userinfo = mysql_fetch_array($result)) {
//	$login = $get_userinfo[0];
//	$pwd = $get_userinfo[1];
//}
$nine["login"] = $login;
$nine["password"] = $pwd;

$trackMsg = 'user login detail';
$trackDtl['credential'] = $nine;
$trackId = $funObj->callTracker($trackId,$startTimTracker,'',$trackMsg,$trackDtl,__FILE__,__LINE__);

//echo "1";
$msgid = $callObj->Call($nine);
//var_dump($msgid);
//unset objects
unset($funObj);
unset($callObj);

//die("Bye");

echo $msgid;
//mysql_close($con);
?>
