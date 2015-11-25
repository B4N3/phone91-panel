<?php

include dirname(dirname(__FILE__)).'/config.php';//includes config.phph file
//class containing function

class setting_class extends fun  
{
   public  function update_newdetails($parm,$userid)//function connecting from action_layer.php
    {
            
       
       $con=mysqli_connect('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8');//connecting to server
       $db=mysqli_select_db("voip91_switch",$con);//connecting database
       
       $userid=$_SESSION['userid'];//getting the userid by SESSION
       
       $name = $parm['name']; //$name input name by post method
       
       
       $dob = $parm['dob'];//$dob input dob by post method
       
       $ocupation = $parm['ocupation'];//input ocupation storing in variable
       //input country storing in $country
       $country = $parm['country']; 
       //input city storing in $city
       $city = $parm['city'];
       //input zip storing in $zip
       $zip = $parm['zip'];
       //input adrress storing in $address
       $address=$parm['address'];
               //update all data by entered value.
      /* if($name!=''){
               $res="UPDATE `91_personalInfo` 
                   SET `name`='".$name."',
                       `sex`='".$sex."',
                           `ocupation`='".$ocupation."',
                               `dob`='".$dob."',
                                   `address`='".$address."',
                                       `city`='".$city."',
                                           `zipCode`='".$zip."',
                                               `country`='".$country."'
                                                   WHERE `userId`='".$userid."'";
             
               //echo $res;
               //$result=mysqli_query($res);
               //var_dump($result); */
    $table = '91_personalInfo';
    $data= array("name"=>"$name",
                 "sex"=>"$sex",
                 "ocupation"=>"$ocupation",
                 "dob"=>"$dob",
                "address"=>"$address",
                "city"=>"$city",
                "zipCode"=>"$zip",
                "country"=>"$country"); 

    $this->db->update($table,$data)->where("userId = '$userid' "  );
    $this->db->getQuery();
    $result = $this->db->execute();
    var_dump($result);
               echo "updated successfully";
      
           
       //echo "NO";}
       
       }
    }

    ?>