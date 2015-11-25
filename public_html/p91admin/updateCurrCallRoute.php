<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/newapi/function_layer.php");
if(isset($_REQUEST['submit']))
{
    $route = trim($_REQUEST['route']);
    $con = $funobj->connect();
    $query = "UPDATE current_calling_route SET routename = '".mysql_real_escape_string($route)."'";
    $result = mysql_query($query,$con) or $error = mysql_error().$query;
    mysql_close($con);
    if($result)
    {
        echo "Route updated successfully";
    }
    else
        echo "Error updating Route::".$error;
}
    $con = $funobj->connect();
    $selQuery = "SELECT routename FROM `current_calling_route`";
    $selResult = mysql_query($selQuery,$con) or die(mysql_error().$selQuery);
    mysql_close($con);
    $row = mysql_fetch_array($selResult);
?>
<html>
    <head></head>
    <body>
        <label>Current Route is :: <?php echo $row[0]; ?> </label>
        <br/>
        <br/>
        <form name="routeForm" action="" method="post">
            <select name="route" id="route">
                <option value="relnew" >relnew</option>
                <option value="tatanew">tatanew</option>
                <option value="tata2">tata2</option>
                <option value="route1">route1</option>
            </select>
            <input type="submit" name="submit" value="Save"> 
        </form>
<!--        <script type="text/javascript">
            $('#route option[value=".$gateway."]').prop('selected', true); 
        </script>-->
    </body>
</html>