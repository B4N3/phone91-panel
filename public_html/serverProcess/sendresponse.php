<?php
session_start();
function Response($param) {
$connect_url = "http://67.205.89.202/VSServices/callback.aspx"; // Do not change

$param["guid"] = $param[guid];
$param["cmd"] = "getStatus"; // beep7 password
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

	$res[guid] =$_REQUEST['d'];
if($res[guid]!=null){	
	//Call function
		$resid=Response($res);

	$objDOM = new DOMDocument();
	$objDOM->loadXML($resid); //make sure path is correct


  $note = $objDOM->getElementsByTagName("response");
  // for each note tag, parse the document and get values for
  // tasks and details tag.




  foreach( $note as $value )
  {
    $tasks = $value->getElementsByTagName("code");
    $task  = $tasks->item(0)->nodeValue;
     if($task!=105&&$task!=3&&$task!=5){
	$details = $value->getElementsByTagName("message");
    $newdetail  = $details->item(0)->nodeValue;
	if($newdetail!="")
		$_SESSION['status']=$newdetail;
	}
	
   }
 }

  echo $_SESSION['status']; 
 ?>
