<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

echo $actual_link
?>
<?php
/*$result = file_get_contents('http://www.google.com/ig/calculator?hl=en&q=100EUR=?USD');

$s = (explode(":",$result));
$e3= $s[4];
$err = $s[3];
$s = explode(" ",$s[2]);
$s1=substr($s[1],1);
echo $s1;echo"</br>";

if($err !="true}" )
{
mail('shubh124421@gmail.com','error',$err);}
*/
?>
</body>
</html>