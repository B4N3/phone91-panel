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
     function getAdminLogDetail($actionType,$pageNo=1)
     {
        $limit = 20;
        $skip = $limit*($pageNo-1);
        # table name to get log details   
        $adminTable = '91_adminLog';

        $actionType = $this->db->real_escape_string($actionType);
        #get log detail of user   
        $this->db->select('SQL_CALC_FOUND_ROWS *')->from($adminTable)->where("actionType =".$actionType." order by date desc")->limit($limit)->offset($skip);
        $result = $this->db->execute();
        
         $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
         
         if(!$resultCount)
             trigger_error('Problem while get totalRows for call limit');
         
         $countRes = mysqli_fetch_assoc($resultCount);
            
        include_once(CLASS_DIR.'account_manager_class.php');
        $acmObj = new Account_manager_class();
        
        if($result->num_rows > 0)
        {
            while ($res = $result->fetch_array(MYSQL_ASSOC))
            {
                
                $data['userId'] = $res['userId'];
                $data['userName'] = $this->getuserName($res['userId']);
                $data['time']=$res['date'];
                if($actionType == 2)
                {
                    $data['oldStatus'] = $this->getTariffName($res['oldStatus']); 
                    $data['currentStatus'] = $this->getTariffName($res['currentStatus']);
                }
                elseif($actionType == 4)
                {
                    $curRes = $acmObj->getAcmName($res['currentStatus']);
                    if($curRes)
                       $data['oldStatus'] = $curRes['userName'];
                    else
                        $data['oldStatus']= '';
                    
                    $resl = $acmObj->getAcmName($res['oldStatus']);
                    if($resl)
                        $data['currentStatus'] =  $resl['userName'];
                    else
                        $data['currentStatus'] = '';
                        
                    
                }
                else
                {
                    $data['oldStatus']=$res['oldStatus'];
                    $data['currentStatus']=$res['currentStatus'];
                }
                #check action taken by (account manager or reseller)
                if($res['changedBy'] == 1)
                {
                    $data['actionTakenBy']=$this->getAccountManagerName($res['accManagerId'])." (A/c Manager)";
                }
                else
                    $data['actionTakenBy']=$this->getuserName($res['actionTakenBy']);

                $data['description']=$res['description'];

                $detail[] = $data;
                
                unset($row);
            }
        }else
            $detail = array();

        $detail['pages']= ceil($countRes['totalRows']/$limit);
        
        unset($acmObj);
        
        return json_encode($detail);

     }
      
     
     
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 11-09-2013
    #function use for get username by userid 
    function getuserName($userId){
        #condition for find username and pin detail 
        $condition = "userId = '" . $userId . "' ";

        #find user name of given id (we can not use session name because userid will change).
        $info = "91_userLogin";
        $this->db->select('*')->from($info)->where($condition);
        $userifo = $this->db->execute();
        if ($userifo->num_rows > 0) {
        $user = $userifo->fetch_array(MYSQL_ASSOC);
           $userName = $user['userName'];             
        } 
       
        
        return $userName;

    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 11-09-2013
    #function use for get account manager name by managerid 
    function getAccountManagerName($acmId){
        #condition for find username and pin detail 
        $condition = "acmId = '" . $acmId . "' ";

        #find user name of given id (we can not use session name because userid will change).
        $info = "91_accountManagerDetails";
        $this->db->select('*')->from($info)->where($condition);
        $userifo = $this->db->execute();
        if ($userifo->num_rows > 0) {
        $user = $userifo->fetch_array(MYSQL_ASSOC);
           $userName = $user['userName'];             
        }
        return $userName;

    }
        
    function getEditFundLog($pageNo = 1)
    {
            $limit = 20;
            $skip = $limit*($pageNo-1);
            #table name   
            $table = '91_transactionLog';

            #get data form transaction log table where form user and touser are given
            $this->db->select('SQL_CALC_FOUND_ROWS *')->from($table)->where("amount !=0 and debit !=0 order by date desc ")->limit($limit)->offset($skip);;
            $this->db->getQuery();
            $result = $this->db->execute();

            $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
            $countRes = mysqli_fetch_assoc($resultCount);
            
            #check data total no of row is greater then 0 or not 
            if ($result->num_rows > 0){
                while ($row= $result->fetch_array(MYSQLI_ASSOC) ) {

                    #from user
                    $data['fromUser'] = $row['fromUser'];

                    if($row['changedBy'] == 1){
                       $data['fromUserName']=$this->getAccountManagerName($row['accManagerId'])." (A/c Manager)";
                    }else
                    $data['fromUserName'] = $this->getuserName($row['fromUser']);
                    $data['toUser'] = $row['toUser'];
                    $data['toUserName'] = $this->getuserName($row['toUser']);
                    $data['date'] = $row['date'];
                    $data['amount'] = $row['amount'];
                    $data['currentBalance'] = $row['currentBalance'];
                    $data['description'] = $row['description'];


                    $transactionData[] = $data;
                    unset($row);
              }

            }else
                $transactionData = array();

             $transactionData['pages']= ceil($countRes['totalRows']/$limit);

            return json_encode($transactionData);

      }
      
      
 function getTariffName($tariffId)
    {
        
        #condition for find tariff id and name 
        $condition = "tariffId = '" . $tariffId . "' ";
        
        $tableNsme = "91_plan";
        $result = $this->selectData('*',$tableNsme,$condition);
        
        $planName = '';

        if ($result->num_rows > 0) 
        {
            $res = $result->fetch_array(MYSQLI_ASSOC);
            $planName = $res['planName'];
                    
        } 
        
        return $planName;
     
    }
        
         
}//end of class
?>