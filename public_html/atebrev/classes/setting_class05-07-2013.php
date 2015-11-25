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
       
       $sex=$parm['gender'];
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
       
       
      if($name && preg_match('/^[a-zA-Z]+$/',$name))
      {
          if ($sex== 1 || $sex== 0)
          {
            if ($dob && $this->check($dob))
              {
                if ($country && preg_match('/^[a-zA-Z]+$/',$country))
                {
                    if ($city && preg_match('/^[a-zA-Z]+$/',$city))
                    {
                        if ($zip && preg_match('/^[0-9]+$/',$zip))
                        {
                    
              $table = '91_personalInfo';
              $data= array(
              "userId"=>"$userid", 
              "name"=>"$name",
              "age"=>"",  
              "sex"=>"$sex",
              "ocupation"=>"$ocupation",
              "dob"=>"$dob",
              "address"=>"$address",
              "city"=>"$city",
              "zipCode"=>"$zip",
              "state"=>"",    
              "country"=>"$country"); 
    
    $this->db->update($table,$data)->where("userId = '$userid' "  );
    $this->db->getQuery();
    $result = $this->db->execute();
    //var_dump($result);
             
      return json_encode(array('msgtype'=>'success','msg'=>'updated successfully'));
                        }  else 
                             return json_encode(array('msgtype'=>'error','msg'=>'zipcode feild  error'));
                    }else return json_encode(array('msgtype'=>'error','msg'=>'city feild error'));
                } else return json_encode(array('msgtype'=>'error','msg'=>'country feild error'));
            } else return json_encode(array('msgtype'=>'error','msg'=>'date of birth  field error '));
          } else return json_encode(array('msgtype'=>'error','msg'=>'select gender  error'));
     } else return json_encode(array('msgtype'=>'error','msg'=>'name field error'));
    }
    
    
 /*function is_date($value, $format = 'yyyy/mm/dd'){
     $obj=new new_editdetails($parm,$userid);
     $value=$obj->$dob;
 
if(strlen($value) == 10 && strlen($format) == 10){
 
// find separator. Remove all other characters from $format
$separator_only = str_replace(array('m','d','y'),'', $format);
$separator = $separator_only[0]; // separator is first character
 
if($separator && strlen($separator_only) == 2)
    {
// make regex
$regexp = str_replace('mm', '[0-1][0-9]', $value);
$regexp = str_replace('dd', '[0-3][0-9]', $value);
$regexp = str_replace('yyyy', '[0-9]{4}', $value);
$regexp = str_replace($separator, "\\" . $separator, $value);
 
if($regexp != $value && preg_match('/'.$regexp.'/', $value))
                {
 
// check date
$day = substr($value,strpos($format, 'd'),2);
$month = substr($value,strpos($format, 'm'),2);
$year = substr($value,strpos($format, 'y'),4);
 
if(@checkdate($month, $day, $year))
return true;
}
}
}
return json_encode(array('msgtype'=>'error','msg'=>'dob field error'));;
}*/
    function check($date)
    {
        
    
        if (preg_match ("/^(\d{4})-(\d{2})-(\d{2})$/",$date))
                {
          
        //check weather the date is valid of not
            if(strtotime($date))
                {
           
                   return true;//"date is valid";
                }
                else
                    return false;//"Invalid Date and Time formate."; 
         }
         else
              return false;//"Invalid Date and Time.";
    }
       
    };

    ?>

