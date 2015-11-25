<?
    include('config.php');
    $con=$funobj->connect();
?>
<html>
<head></head>
<script type="text/javascript">
	$('.demolinks a').mouseover(function() {
	  $('.demo').html("<img src='images/"+$(this).attr("alt")+"' />");
	  //alert($(this).attr("href"));
	  event.preventDefault();
	});
</script>
<body>
<?php include_once("analyticstracking.php") ?>
<h3>Active Calls</h3><br />
<?
if ($_SESSION['client_type'] == 1) {
	$sql = "select * from currentcalls order by call_start desc limit 0,10";
	$result = mysql_query($sql) or die("Error In Sql: ".mysql_error());
}
else {
	$sql = "select * from currentcalls where id_client='".$_SESSION['userid']."' and dialed_number NOT LIKE '0000%' order by call_start desc limit 0,10";
	$result = mysql_query($sql) or die("Error In Sql: ".mysql_error());
}
	
?>
<table class="tbl" width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
  	<tr class="tblhd">
	<td><h4>Client ID</h4></td>
        <td><h4>Called Number</h4></td>
        <td><h4>Time</h4></td>
        <td><h4>Region</h4></td>
	<td><h4>Reseller ID</h4></td>
  	</tr>
  <?
  	$i=1;
	$class='';
  	while($rows=mysql_fetch_array($result))
	{
		if($i%2==1)
			$class='odd';
		else
			$class='evven';
  ?>
    <tr class="<?=$class?>">
	<td><?=$rows['id_client']?></td>
        <td><?=$rows['dialed_number']?></td>
        <td><?=$rows['call_start']?></td>
        <td><?=$rows['tariffdesc']?></td>
	<td><?=$rows['id_reseller']?></td>
  	</tr>
  	<?
	$i++;
	}
	?>
  
</tbody>
</table>
<div class="clf"></div>
</body>
</html>
