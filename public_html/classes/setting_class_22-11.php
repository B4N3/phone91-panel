<?php
#includes config.php file
include dirname(dirname(__FILE__)).'/config.php';
#class containing function
class setting_class extends fun  
{  
   #function connecting from action_layer.php 
   
    function update_newdetails($parm,$userid) {

      
       #check name is valid or not 
       if(preg_match('/[^a-zA-Z_@-\s]+/',$parm['name']) || strlen($parm['name'])  > 32)       {
          return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid Name'));
       }
       
       #check for country name 
       if(preg_match('/[^a-zA-Z\s]+/',$parm['country']) || strlen($parm['country'])  > 32){
          return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid Country'));
       }
       
       #validate ocupation
       if(preg_match('/[^a-zA-Z\s]+/',$parm['ocupation']) || strlen($parm['ocupation'])  > 32){
          return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid Ocupation'));
       }
       
       #check for city is valid or not 
       if(preg_match('/[^a-zA-Z\s]+/',$parm['city']) || strlen($parm['city'])  > 32){
          return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid City'));
       }
       
       #check for zip code 
       if(preg_match('/[^0-9]+/',$parm['zip']) || strlen($parm['zip'])  > 11 || strlen($parm['zip'])  < 6){
          return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid Zipcode '));
       }
       
       #validate address 
       if(preg_match('/[^0-9a-zA-Z\-\/\@\s]+/',$parm['address']) || strlen($parm['zip'])  > 64 ){
          return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid Address '));
       }
       
       #check dob
       if(!$this->check($parm['dob'])){
           return json_encode(array('msgtype'=>'error','msg'=>'Please Enter Valid date '));
       }
       
       #validate gender
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
    
    
    #created by sameer rathod 
    #creation date 22-10-2013
    #function use to add register ids
    function addRegisterId($param,$userId){

       
       
       #check for company name is velide or not 
       if(preg_match("/[^a-zA-Z0-9\.\_\@\-\$]+/", $param['emailId'])){
            return json_encode(array("status" => "error", "msg" => "emailId is not valid"));
       }
       
       if($param['type'] == 'select' || $param['type'] =='' || $param['type'] == null){
           return json_encode(array("status" => "error", "msg" => "please select valid type"));
       }
      
       $emailId = $param['emailId'];
       $type = $param['type'];
       
       if($type == 1)
       {
           $tableName = '91_verifiedGtalkId';
           $tempTableName = '91_tempGtalkId';
       }
       elseif($type == 2)
       {
           $tableName = '91_verifiedSkypeId';
           $tempTableName = '91_tempSkypeId';
       }
//       echo $tableName;
        #check user Name already exist 
        $result = $this->selectData('*', $tableName, "email ='".$emailId."'");
        #check the resulting value exists or not 
        if($result->num_rows > 0)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC); 
            if($row['userId'] == $userId)
                return json_encode (array("msg"=>"Email id already exist","status"=>"error"));
            
            $tempSelResult = $this->selectData('*', $tempTableName, "email ='".$emailId."' and userId = '".$userId."'");
            if($tempSelResult->num_rows > 0)
                return json_encode (array("msg"=>"Email id already exist","status"=>"error"));
            
            $code = $this->generatePassword();
            $data = array("email"=>$emailId,"code"=>$code,"userId"=>$userId);
            $resInsert = $this->insertData($data, $tempTableName);
        }
        else {
            $data = array("email"=>$emailId,"userId"=>$userId);
            $resInsert = $this->insertData($data, $tableName);
            
        }
//        echo $this->db->error;
        if($resInsert){
             return json_encode(array("status" => "success", "msg" => "successfully added ids."));
        }
        else
            return json_encode(array("status" => "error", "msg" => "Error Inserting email Id"));
    }
    
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 07/10/2013
    #function use to get all white label ids
    function allWhiteLabelIds($userId){

        $collectionName = '91_whiteLabelids';
        
        #get all white label ids 
        $this->db->select('*')->from($collectionName)->where("resellerId ='".$userId."'");
        $this->db->getQuery();
            
        #execute query
        $result=$this->db->execute();

        #check the resulting value exists or not 
        if($result->num_rows > 0)
          {
            while ($row = $result->fetch_array(MYSQL_ASSOC) ) {
                
                $data['userName'] = $row['userName'];
                $data['password'] = $row['password'];
                $data['type'] = $row['type'];
                
                $whiteLabelData[] = $data;
                        
            
          }
        
        }else
            $whiteLabelData = array();

        return json_encode($whiteLabelData);
    
    }

    #created by sameer rathod 
    #creation date 22-10-2013
    #function use to get register ids details
    function getRegisterIdDetail($userId,$type,$id = Null){

        $data =array();
        $condition = "userId = '".$userId."'";
        if(!is_null($id))
                $condition .= " and emailId = '".$id."'"; 
        switch ($type)
        {
            case 1:
            {
                $tableName = '`91_verifiedGtalkId`';
                $idType = 'gtalk';
                $isConfirm = 1;
                break;
            }
            case 2:
            {
                $tableName = '`91_verifiedSkypeId`';
                $idType = 'skype';
                $isConfirm = 1;
                break;
            }
            case 3:
            {
                $tableName = '`91_tempGtalkId`';
                $idType = 'gtalk';
                $isConfirm = 0;
                break;
            }
            case 4:
            {
                $tableName = '`91_tempSkypeId`';
                $idType = 'skype';
                $isConfirm = 0;
                break;
            }
        }

        #get all white label ids 
        $this->db->select('*')->from($tableName)->where($condition);
//        echo $this->db->getQuery();
            
        #execute query
        $result=$this->db->execute();

        #check the resulting value exists or not 
        if($result && $result->num_rows > 0)
        {
            while($row = $result->fetch_array(MYSQLI_BOTH))
            {
                $row['type'] = $idType;
                $row['confirm'] = $isConfirm;
                $data[] = $row;
            }
        }
        return $data;
    }
    
    #created by sameer rathod 
    #creation date 22-10-2013
    #function use to delete register ids
    function deleteRegisterId($id,$type,$user,$temp){
        if($id == "" || $type =="" || $user == "")
            return json_encode(array("msg"=>"Invalid Input please rovide valid details","status" => "error"));
        $id = trim($id);
        if(preg_match('/[^a-zA-Z]+/', trim($type)))
            return json_encode(array("msg"=>"Invalid type","status" => "error"));
        if($temp != 1 && $temp != 0)
            return json_encode(array("msg"=>"Invalid data please contact support","status" => "error"));
        
        $condition = "userId = '".$user."' and email = '".$id."'";
                
        if($temp == 0){
            $tableType = 'verified';
        }
        elseif($temp == 1) {
            $tableType = 'temp';
        }
        
        if(trim($type) == 'gtalk'){
            $tableName = '91_'.$tableType.'GtalkId';
        }
        elseif(trim($type) == 'skype') {
            $tableName = '91_'.$tableType.'SkypeId';
        }
        else {
            return json_encode(array("msg"=>"Invalid type","status" => "error"));
        }
        
        #get all white label ids 
        $this->db->delete($tableName)->where($condition);
        $this->db->getQuery();
        
        #execute query
        $result=$this->db->execute();

        #check the resulting value exists or not 
        if($result && $this->db->affected_rows > 0)
        {
            return json_encode(array("msg"=>"Successfuly deleted Id","status" => "success"));
        }
        else
            return json_encode(array("msg"=>"Cannot delete entry please try again later","status" => "error"));
    }
    
function getProfileDetails($userid)
{
    #create the object of function fun().
    
    #$table name of the table in database
    $table = '91_personalInfo';
    #select all data from the table  where userid is session userid
    $result = $this->selectData("*", $table,"userId = '" .$userid . "'");
//    $this->db->select('*')->from($table)->where("userId = '" .$_SESSION['userid'] . "'");
    #execute the query
//    $result = $this->db->execute();
    #fetch all the rows from the table 
    $row=$result->fetch_array();
        
    #store all array elements in the particular variable name corresponds to them. and accessing the perticular row from the database.  
    $name=$row[1];
    $dob=$row[5];
    $country=$row[10];
    $city=$row[7];
    $ocupation=$row[4];
    $zip=$row[8];
    $address=$row[6];
    $sex=$row[3];
          
           #return array at the end of loop
    return json_encode(array("name"=>$name,"dob"=>$dob,"ocupation"=>$ocupation,"country"=>$country,"city"=>$city,"zip"=>$zip,"address"=>$address,"gender"=>$sex));
       
     #check for the user gender,so check the user existance   
} 

    
}
    ?>

