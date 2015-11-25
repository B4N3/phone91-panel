<?php
#includes config.php file
include dirname(dirname(__FILE__)).'/config.php';
#class containing function
class setting_class extends fun  
{  
   #function connecting from action_layer.php 
   
    function update_newdetails($parm,$userid) {

      
       #check name is valid or not 
       if(!preg_match('/^[a-zA-Z_@-\s]+$/',$parm['name']))       {
          return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid Name'));
       }
       
       #check for country name 
       if(!preg_match('/^[a-zA-Z]+$/',$parm['country'])){
          return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid Country'));
       }
       
       #check for city is valid or not 
       if(!preg_match('/^[a-zA-Z]+$/',$parm['city'])){
          return json_encode(array('msgtype'=>'error','msg'=>'PLease Enter Valid City'));
       }
       
       #check for zip code 
       if(!preg_match('/^[0-9]+$/',$parm['zip'])){
          return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid Zipcode '));
       }
       
       if(!$this->check($parm['dob'])){
           return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid date '));
       }
       
       if($parm['gender'] != 0 && $parm['gender'] != 1){
           return json_encode(array('msgtype'=>'error','msg'=>'Please select gender'));
       }
       
       $name = $this->db->real_escape_string($parm['name']);
       $ocupation = $this->db->real_escape_string($parm['ocupation']);
       $address = $this->db->real_escape_string($parm['address']);
       $city = $this->db->real_escape_string($parm['city']);
       $zip = $this->db->real_escape_string($parm['zip']);
       $country = $this->db->real_escape_string($parm['country']);
       
               
       #table name in that the action to be taken      
       $table = '91_personalInfo';
       #$data contains the array data 
       $data= array("name"=>$name,"sex"=>$parm['gender'],"ocupation"=>$ocupation,"dob"=>$parm['dob'],"address"=>$address,"city"=>$city,"zipCode"=>$zip,"country"=>$country); 
       #update the data with perticular userid
       $this->db->update($table,$data)->where("userId = '$userid' "  );
       $this->db->getQuery();
       #execute the query
       $result = $this->db->execute();   
       return json_encode(array('msgtype'=>'success','msg'=>'Updated Successfully'));
    }
    
#function to check the date    
#created by balachand
    
function check($date){
        
        #regular expression for date
        if (preg_match ("/^(\d{4})-(\d{2})-(\d{2})$/",$date))                {
          
        #check weather the date is valid of not
            if(strtotime($date)) {
           
                   return true;//"date is valid";
                }
                else
                    return false;//"Invalid Date and Time formate."; 
         }
         else
              return false;//"Invalid Date and Time.";
    }
       
    function updateNewsDb($colName,$value,$userId)
    {
        /* @author : sameer 
         * @created : 3-09-13
         * @desc : function is used to update the value on the 91_news table 
         * 
         */
       if($colName != "" && $value != "" && ($value == 0 || $value ==1) && $userId != "")
       {
            $query = "INSERT INTO 91_news (userId,".$colName.",date) values ('".$userId."','".$value."',now())  ON DUPLICATE KEY UPDATE  ".$colName." = ".$value." ";          
            $res = $this->db->query($query) ;

       }

        if($res)
            return true;
        else
            return false;
    }
    function getUpdateNewsDb($userId)
    {
        /* @author : sameer 
         * @created : 3-09-13
         * @desc : function get the details of the user from 91_news table 
         * 
         */
       if($userId != "")
       {
            $table = '91_news';
            $this->db->select('*')->from($table)->where(" userid='" . $userId . "' ");    
            $this->db->getQuery();
            $result = $this->db->execute();
            if($result)
            {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                return json_encode($row);
            }
            else
                return false;
       }
       
        
    }
}
    ?>

