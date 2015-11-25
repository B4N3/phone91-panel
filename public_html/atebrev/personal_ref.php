<?php

$name=$_POST['name'];
$dob=$_POST['dob'];
$ocupation=$_POST['ocupation'];
$country=$_POST['country'];
$city=$_POST['city'];
$zip=$_POST['zip'];
$address=$_POST['address'];
$sex=$_POST['gender'];

include_once('config.php');
$userid=$_SESSION['userid'];
$con=mysqli_connect('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8');
  $db=mysqli_select_db("voip91_switch",$con);
   $funobj=new fun();
    //$table 
    $table = '91_personalInfo';
    $funobj->db->select('*')->from($table)->where("userId = '" .$_SESSION['userid'] . "'");
    //echo $funobj->db->getQuery();
    $result = $funobj->db->execute();
    
    while(mysqli_fetch_row($result)>0)
    {
        $sql="UPDATE `91_personalInfo` SET `name`='".$_POST['name']."',`dob`='".$_POST['dob']."',`ocupation`='".$_POST['ocupation']."',`country`='".$_POST['country']."',`city`='".$_POST['city']."',`zipCode`='".$_POST['zip']."',`address`='".$_POST['address']."',`sex`='".$POST['gender']."' WHERE `userId`='".$userid."'" ;           
            echo $sql;
            $res1=mysqli_query($sql);
    }
    ?>
