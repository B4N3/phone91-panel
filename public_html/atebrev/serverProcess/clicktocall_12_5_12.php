<?php
session_start();
include("includes/config.php");
function Call($paramarg) {
$connect_url = "http://67.205.89.202/VSServices/callback.aspx"; // Do not change

$param["login"] = $paramarg[login]; // beep7 profile ID
$param["password"] = $paramarg[password]; // beep7 password
$param["source"] = $paramarg[source];
$param["dest"]=$paramarg[dest];
$param["type"]="1";
foreach($param as $key=>$val){ 
$request.= $key."=".urlencode($val);
$request.= "&";
}
$request = substr($request, 0, strlen($request)-1);
$url2 = $connect_url."?".$request;

$ch = curl_init($url2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);
return $curl_scraped_page;
}
$fdigit=-1;
$_SESSION['status']="callplz.com";
	$source=$_REQUEST['q'];
	$dest=$_REQUEST['d'];
	$user=$_REQUEST['u'];
	$pwd=$_REQUEST['p'];
//for a new call
	$nine[dest] =$dest;
	$nine[source]=$source;
	
		
$result=mysql_query("select login,password from clientsshared where login='$user' and password='$pwd'");
		while($get_userinfo=mysql_fetch_array($result)){
		$login=$get_userinfo[0];
		$pwd=$get_userinfo[1];
		}
	$nine[login] =$login;
	$nine[password]=$pwd;
	
	//Call function
		$msgid=Call($nine);

	$objDOM = new DOMDocument();
  $objDOM->loadXML($msgid); //make sure path is correct


  $note = $objDOM->getElementsByTagName("response");
  // for each note tag, parse the document and get values for
  // tasks and details tag.

  foreach( $note as $value )
  {
    $tasks = $value->getElementsByTagName("code");
    $task  = $tasks->item(0)->nodeValue;


    $details = $value->getElementsByTagName("message");
    $detail  = $details->item(0)->nodeValue;

   }
echo $detail;
//$_SESSION['guid']=$detail;
?>
