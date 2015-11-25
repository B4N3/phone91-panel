<?php
echo $url = "https://voip91.com/isoData.php";

$homepage = file_get_contents('https://voip91.com/isoData.php');
var_dump($homepage);
//echo file_get_contents($url);

//echo get_data($url);




function get_data($url) {
    $ch = curl_init();
    //$timeout = 400;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT);   
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>

