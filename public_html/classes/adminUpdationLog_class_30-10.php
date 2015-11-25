<?php
/**
 * @Author Rahul <rahul@hostnsoft.com>
 * @createdDate 03-06-13
 * @modified by sudhir <sudhir@hostnsoft.com>
 * @details class use to reseller manage pin (create pin,batch pin generate,recharge by pin etc.)  
 * 
 */
include dirname(dirname(__FILE__)).'/config.php';
class adminUpdationLog_class extends fun
{
     function getAdminLogDetail($actionType){
      
      # table name to get log details   
      $adminTable = '91_adminLog';
      
      $userId = $this->db->real_escape_string($userId);
      $actionType = $this->db->real_escape_string($actionType);
      #get log detail of user   
      $this->db->select('*')->from($adminTable)->where("actionType =".$actionType."");
      $result = $this->db->execute();
      if($result->num_rows > 0)
      {
          while ($res = $result->fetch_array(MYSQL_ASSOC))
          {
              $data['userName'] = $this->getuserName($res['userId']);
              $data['time']=$res['date'];
              $data['oldStatus']=$res['oldStatus'];
              $data['currentStatus']=$res['currentStatus'];
              $data['actionTakenBy']=$this->getuserName($res['actionTakenBy']);
              $data['description']=$res['description'];
              
              $detail[] = $data;
          }
      }else
          $detail = array();
        
      return json_encode($detail);
         
     }
       
        #created by sudhir pandey (sudhir@hostnsoft.com)
        #creation date 11-09-2013
        #function use for get username by userid 
        function getuserName($userId){
            #condition for find username and pin detail 
            $condition = "userId = '" . $userId . "' ";

            #find user name of given id (we can not use session name because userid will change).
            $info = "91_personalInfo";
            $this->db->select('*')->from($info)->where($condition);
            $userifo = $this->db->execute();
            if ($userifo->num_rows > 0) {
            $user = $userifo->fetch_array(MYSQL_ASSOC);
               $userName = $user['name'];             
            } 
            return $userName;
            
        }
         
}//end of class
?>