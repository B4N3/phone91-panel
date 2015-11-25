<?php
include("config.php");
//$con = $funobj->connecti();
if (!$funobj->login_validate()) {
	$funobj->redirect("index.php");
}

include(CLASS_DIR."call_class.php");
error_reporting(-1);
$call_obj = new call_class();

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
#fetch all detail form login table where userid is session user id 
//var_dump($funobj);
$funobj->db->select('*')->from($table)->where("userId = '".$_SESSION['userid']."'");
//echo $funobj->db->getQuery();

$result = ($funobj->db->execute());
if ($result->num_rows > 0) {
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


$msgid = $call_obj->Call($nine);
echo $msgid;
//mysql_close($con);
?>