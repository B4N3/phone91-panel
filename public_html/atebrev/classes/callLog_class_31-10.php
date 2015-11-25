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
    function getCreditGraph($chainId)
    {
        if($chainId == "" ||preg_match('/[^a-zA-Z0-9]+/',$chainId))
                return json_encode(array("msg"=> "Invalid chain Id","status"=> "error"));
        $query = "select * from 91_closingAmount where `closingAmount` < 0 AND userId in (SELECT userId  FROM `91_userbalance` WHERE chainId like '".$chainId."____')";
        $result = $this->db->query($query);
        if($result)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
//                $resultant['data'][] = $row;
                $resultant["data"][$row["userId"]] =$row["closingAmount"];
            }
            $resultant['count'] = count($resultant['data']);
            
        }
        else
        {
            $resultant = array("msg"=> "Error fetching details for credit graph","status"=>"error");
        }
        return json_encode($resultant);
    }
    function getStatusAndTypeDetails($chainId,$date,$type)
    {
        if($chainId == "" ||preg_match('/[^a-zA-Z0-9]+/',$chainId))
                return json_encode(array("msg"=> "Invalid chain Id","status"=> "error"));
        if($date == "" ||preg_match('/[^0-9\-]+/',$chainId))
                return json_encode(array("msg"=> "Invalid date","status"=> "error"));
        if($type == "" ||preg_match('/[^A-Za-z]+/',$type))
                return json_encode(array("msg"=> "Invalid Type","status"=> "error"));
        
        if($type == "status")
        {
            $table = " 91_callStatusDaily ";
            $columns = " sum(total) as totalNumber ,sum(totalDuration) as total ,status as type";
            $condition = "chainId like '".$chainId."%' and date(date) between '".$date."' and date(now()) group by status";
        }
        elseif($type == "callType" )
        {
            $table = " 91_callType";
            $columns = " sum(total) as total,callType as type";
            $condition = "chainId like '".$chainId."%' and date(date) between '".$date."' and date(now()) group by callType";
        }
        $selResult = $this->selectData($columns, $table,$condition);
        if($selResult)
        {
            $sumOfCall = 0;
            while($row = $selResult->fetch_array(MYSQLI_ASSOC))
            {
                //$response[$row['type']] = $row;
                
                $response[$row['type']] = $row['total'];
                
                $sumOfCall += $row['total'];
                
            }
            $finalResponse['via']=$response;
            //$response['sum']['totalSumDuration'] = $sumOfCall;
            $finalResponse['totalSumDuration']=$sumOfCall;
//            $response['sum']['avgCallDuration'] = ($response['ANSWERED']['totalDuration']/$response['ANSWERED']['total']);
        }
        else
        {
            $response = array("msg"=>"Error Fetching Details","status"=>"error");
        }
        
        return json_encode($finalResponse);
    }
    
    function getResellerTotalStatistics($chainId,$date,$type)
    {
        /* callduration and profit
         * $type : 1 is for My total time 
         * $type : 2 is for total customer time
         * $type : 3 is for Total Profit
         */
        if($chainId == "" ||preg_match('/[^a-zA-Z0-9]+/',$chainId))
                return json_encode(array("msg"=> "Invalid chain Id","status"=> "error"));
        if(date == "" ||preg_match('/[^0-9\-]+/',$chainId))
                return json_encode(array("msg"=> "Invalid date","status"=> "error"));
        
        $table = " 91_durationCharged";
        switch($type)
        {
            
            case 1:
            {
                $columns = " durationCharged ";
                $condition = "chainId = '".$chainId."' and date(date) between '".$date."' and date(now()) ";
                break;
            }
            case 2:
            {
                $columns = " sum(durationCharged) as durationCharged";
                $condition = "resellerId = '".$chainId."' and date(date) between '".$date."' and date(now()) ";
                break;
            }
            case 3:
            {
                $table = " 91_chainProfit";
                $columns = " sum(profit) as profit";
                $condition = "parentId = ".$chainId." and date(date) between '".$date."' and date(now()) ";
                break;
            }
        }
        
        $selResult = $this->selectData($columns, $table,$condition);
        if($selResult)
        {
            $row = $selResult->fetch_array(MYSQLI_ASSOC);
            return $row;
        }
        else
            return false;
//            return json_encode(array("msg"=>"Error fetching details","status"=>"error"));
        
        
    }
    function getResProfitDurationGraphDetails($chainId,$date,$type)
    {
        if($chainId == "" ||preg_match('/[^a-zA-Z0-9]+/',$chainId))
                return json_encode(array("msg"=> "Invalid chain Id","status"=> "error"));
        if(date == "" ||preg_match('/[^0-9\-]+/',$chainId))
                return json_encode(array("msg"=> "Invalid date","status"=> "error"));
        
        $chainId = $this->db->real_escape_string($chainId);
        $date = $this->db->real_escape_string($date);
        if($type == "duration")
        {
            $query = "select sum(durationCharged) as clmValue,chainId,resellerId from (SELECT * FROM `91_durationCharged` where resellerId = '".$chainId."' and date(date) between '".$date."' and date(now()) order by durationCharged DESC ) as durationTable group by chainId  limit 100";
        }
        elseif($type == "profit")
        {
            $query = "select sum(profit) as clmValue ,childId as chainId from (SELECT * FROM `91_chainProfit` where parentId = ".$chainId." and date(date) between '".$date."' and date(now()) order by profit DESC ) as t group by childId limit 100";
        }
        
        $result = $this->db->query($query);
        if($result)
        {
            $totalCallDuration = 0;
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $response[$row['chainId']] = $row['clmValue'];
                $chainIdArr[] = $row['chainId'];
                $totalCallDuration += $row['clmValue'];
            }
            
            $userNameArr = $this->getUserNameViaChainIdArray($chainIdArr);
            if(!$userNameArr)
                json_encode(array("msg"=>"Error fetching User Details please try again later","status"=>"error"));
            
            foreach($response as $key => $value)
            {
//                $finalArr[$userNameArr[$key]]['clmValue'] = $value;
//                $finalArr[$userNameArr[$key]]['percentage'] = (($value/$totalCallDuration)*100);
                
                $finalArr[$userNameArr[$key]] = number_format((($value/$totalCallDuration)*100),2);
                
                
                
            }
            return json_encode($finalArr);
        }
        else
        {
            return json_encode(array("msg"=> "Error Fetching Details please try agian later","status"=>"error"));
        }
    }
    function getUserNameViaChainIdArray($chainIdArr)
    {
        if($chainIdArr == "" || !is_array($chainIdArr))
            return json_encode(array("msg" => "invalid Input Provided","status"=>"error"));
        $chainIdArr = array_unique($chainIdArr);
        $chainIdArr = array_values($chainIdArr);
        if(count($chainIdArr) <1)
            return json_encode(array("msg" => "invalid Input Provided","status"=>"error"));
        $chainIdString  = implode("','",$chainIdArr);
        $table = "91_manageClient";
        $column = "userName,chainId";
        $condition = "chainId IN ('".$chainIdString."')";
        $result  = $this->selectData($column, $table,$condition);
        if($result)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $response[$row['chainId']] = $row['userName'];
            }
            return $response;
        }
        else
            return false;
//            return json_encode(array("msg"=>"Error fetching Details please try again later","status"=>"error"));
    }
    
}
?>
