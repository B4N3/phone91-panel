<?php
#includes config.phph file
include dirname(dirname(__FILE__)).'/config.php';
#class containing function
class setting_class extends fun  
{  
   #function connecting from action_layer.php 
   public  function update_newdetails($parm,$userid)
    {
            
       #connecting to server
       $con=mysqli_connect('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8');
       $db=mysqli_select_db("voip91_switch",$con);
       
       #getting the userid by SESSION
       $userid=$_SESSION['userid'];
       #$name input name by post method
       $name = $parm['name'];
       #$sex input sex value by post method
       $sex=$parm['gender'];
       #$dob input dob by post method
       $dob = $parm['dob'];
       #input ocupation storing in variable
       $ocupation = $parm['ocupation'];
       #input country storing in $country
       $country = $parm['country']; 
       #input city storing in $city
       $city = $parm['city'];
       #input zip storing in $zip
       $zip = $parm['zip'];
       #input adrress storing in $address
       $address=$parm['address']; 
               
      #check all entered data is valid or not 
      if($name && preg_match('/^[a-zA-Z]+$/',$name))
      {
          #check for gender field
          if ($sex== 1 || $sex== 0)
          {
            #check for date of birth field  
            if ($dob && $this->check($dob))
              {
                #check for country field
                if ($country && preg_match('/^[a-zA-Z]+$/',$country))
                {
                    #check for city field
                    if ($city && preg_match('/^[a-zA-Z]+$/',$city))
                    {
                        #check for zip field
                        if ($zip && preg_match('/^[0-9]+$/',$zip))
                        {
                        #table name in that the action to be taken      
                        $table = '91_personalInfo';
                        #$data contains the array data 
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
                            #update the data with perticular userid
                            $this->db->update($table,$data)->where("userId = '$userid' "  );
                            $this->db->getQuery();
                            #execute the query
                            $result = $this->db->execute();
                            #display success message
                            return json_encode(array('msgtype'=>'success','msg'=>'Updated successfully'));
                        }else return json_encode(array('msgtype'=>'error','msg'=>'zipcode feild  error'));
                    }else return json_encode(array('msgtype'=>'error','msg'=>'city feild error'));
                } else return json_encode(array('msgtype'=>'error','msg'=>'country feild error'));
            } else return json_encode(array('msgtype'=>'error','msg'=>'date of birth  field error '));
          } else return json_encode(array('msgtype'=>'error','msg'=>'select gender  error'));
     } else return json_encode(array('msgtype'=>'error','msg'=>'name field error'));
    }
    
#function to check the date    
function check($date)
    {
        
        #regular expression for date
        if (preg_match ("/^(\d{4})-(\d{2})-(\d{2})$/",$date))
                {
          
        #check weather the date is valid of not
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

