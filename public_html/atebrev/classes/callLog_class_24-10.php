<?php

/* @author: sameer 
 * @created: 16-08-2013
 * @desc : the class consist of all the functions required for call log and recent calls 
 */
include dirname(dirname(__FILE__)) . '/config.php';
class log_class extends fun{
    function getRecentCalls($userid)
    {
        $columns = " c.uniqueId,c.caller_id,c.called_number,balance.deductBalance ";
        $table = " 91_chainBalanceReport balance , 91_calls c ";
        $condition = " c.call_Type='C2C' and c.id_client = ".$userid." and c.uniqueId = balance.uniqueId order by c.call_start desc limit 20 ";
        $this->db->select($columns)->from($table)->where($condition);
       
        $result = $this->db->execute();
        echo $this->db->error;
        if($result)
            return $result;
        else 
            return 0;
        
    }
    
    function getCallLogs($userId)
    {
        $columns = " `called_number`,call_type,id_client,call_start ";
        $table = " `91_calls` ";
        $condition = " id_client=".$userId." and status='ANSWERED' group by called_number  order by call_start desc limit 20 ";
        $this->db->select($columns)->from($table)->where($condition);        
//       echo $this->db->getQuery();
        $result = $this->db->execute();
        if($result)
            return $result;
        else 
            return 0;
    }
    function getCallLogsDetails($number,$userId)
    {
        if(is_null($number))
        {
            $response['msg'] = "Invalid Input please try again";
            $response['type'] = "error";
            die(json_encode($response));
        }
        $columns = " c.`called_number`,c.call_type,c.id_client,c.status,c.call_start,c.duration,balance.deductBalance,balance.currencyId ";
        $table = " 91_chainBalanceReport balance , 91_calls c ";
        $condition = " c.id_client=".$userId." and c.called_number=".$number." and balance.uniqueId=c.uniqueId  and balance.userId = c. id_client order by call_start desc limit 20";
        $this->db->select($columns)->from($table)->where($condition);
//        echo $this->db->getQuery();
        $result = $this->db->execute();
        if($result)
            return $result;
        else 
            return 0;
    }
    function searchCallLogs($keyWord,$userId)
    {
        if(is_null($keyWord))
        {
            $response['msg'] = "Invalid Input please try again";
            $response['type'] = "error";
            die(json_encode($response));
        }
        $columns = " `called_number`,call_type,id_client,call_start ";
        $table = " `91_calls` ";
        $condition = " id_client=".$userId." and called_number LIKE '".$keyWord."%' group by called_number  order by call_start desc limit 20 ";
        $this->db->select($columns)->from($table)->where($condition);
        $result = $this->db->execute();
        if($result)
            return $result;
        else 
            return 0;
    }
    function getCallLogSummary($type,$userId)
    {
        if(is_null($userId))
            exit ();
     
        switch($type)
        {
            case "status":
            {
                $table = "91_status";
                break;
            }
            case "callVia":
            {
                $table = "91_callvia";
                break;
            }
        }
            
        $startDate = date("Y-m-d 00:00:00");
        $endDate = date("Y-m-d 23:59:59");
        $condition = "1" ;//" userId = ".$userId." date between '".$startDate."' and '".$endDate."' ";
        $this->db->select("*")->from($table)->where($condition);
//        echo $this->db->getQuery();
        $result = $this->db->execute();
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $resultant['data'][] =  $row; 
        }
        $resultant['count'] = count($resultant['data']);
        return json_encode($resultant);
    }
    function getCreditGraph($userId)
    {
        $query = "select * from 91_closingAmount where `closingAmount` < 0 AND userId in (SELECT userId  FROM `91_userbalance` WHERE chainId like '".$userId."____')";
        $result = $this->db->query($query);
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $resultant['data'][] = $row;
            
        }
        $resultant['count'] = count($resultant['data']);
        return json_encode($resultant);
    }
    function getStatusDetails($chainId,$date)
    {
        if($chainId == "" ||preg_match('/[^a-zA-Z0-9]+/',$chainId))
                return json_encode(array("msg"=> "Invalid chain Id","status"=> "error"));
        if(date == "" ||preg_match('/[^0-9\-]+/',$chainId))
                return json_encode(array("msg"=> "Invalid date","status"=> "error"));
        
        $table = " 91_callStatusDaily ";
        $columns = " sum(total) as total ,sum(totalDuration) as totalDuration ,status ";
        $condition = "chainId like '".$chainId."%' and date(date) = '".$date."' group by status";
        $selResult = $this->selectData($columns, $table,$condition);
        if($selResult)
        {
            while($row = $selResult->fetch_array(MYSQLI_ASSOC))
            {
                $response[$row['status']] = $row;
                $sumOfDuration[] = $row['totalDuration'];
            }
            $total = array_sum($sumOfDuration);
            $response['sum']['totalSumDuration'] = $total;
            $response['sum']['avgCallDuration'] = ($response['ANSWERED']['totalDuration']/$response['ANSWERED']['total']);
        }
        
        return json_encode($response);
    }
    function getResellerTotalStatistics($chainId,$date,$type)
    {
        /* callduration and profit*/
        if($chainId == "" ||preg_match('/[^a-zA-Z0-9]+/',$chainId))
                return json_encode(array("msg"=> "Invalid chain Id","status"=> "error"));
        if(date == "" ||preg_match('/[^0-9\-]+/',$chainId))
                return json_encode(array("msg"=> "Invalid date","status"=> "error"));
        
        $table = " 91_durationCharged";
        switch($type)
        {
            case 1:
            {
                $columns = " sum(durationCharged) as durationCharged";
                $condition = "resellerId = '".$chainId."' and date(date) = '".$date."'";
                break;
            }
            case 2:
            {
                $columns = " durationCharged ";
                $condition = "chainId = '".$chainId."' and date(date) = '".$date."'";
                break;
            }
            case 3:
            {
                $table = " 91_chainProfit";
                $columns = " sum(profit) as profit";
                $condition = "parentId = ".$chainId." and date(date) = '".$date."'";
                break;
            }
        }
        
        $selResult = $this->selectData($columns, $table,$condition);
        if($selResult)
        {
            $row = $selResult->fetch_array(MYSQLI_ASSOC);
        }
        
        return json_encode($row);
    }
    
}
?>
