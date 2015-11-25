<?php

/* @author: sameer 
 * @created: 16-08-2013
 * @desc : the class consist of all the functions required for call log and recent calls 
 */
include dirname(dirname(__FILE__)) . '/config.php';
class log_class extends fun{
    
    var $msg;
    var $status;
    
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
        $userId = $this->db->real_escape_string($userId);
        $condition = " id_client=".$userId." and status='ANSWERED' order by call_start desc ";
        $sql = "SELECT * FROM (SELECT ".$columns." FROM ".$table." WHERE ".$condition.") as nestedTable group by called_number limit 20";
        $result = $this->db->query($sql);
//       echo $this->db->getQuery();
//        $result = $this->db->execute();
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
        $query = "select * from 91_closingAmount where `closingAmount` > 0 AND userId in (SELECT userId  FROM `91_userBalance` WHERE chainId like '".$chainId."____')";
        $result = $this->db->query($query);
        if($result)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
//                $resultant['data'][] = $row;
                
                $namedata = $this->getUserInformation($row['userId'],1);
                $resultant["data"][$namedata['userName']] =ABS($row["closingAmount"]);
            }
            $resultant['count'] = count($resultant['data']);
            
        }
        else
        {
            $resultant = array("msg"=> "Error fetching details for credit graph","status"=>"error");
        }
        return $resultant;
    }
    function getStatusAndTypeDetails($chainId,$date,$type,$endDate = NULL)
    {
        
        if($endDate == NULL){
            $endDate = date('Y-m-d');
        }
        
        if($chainId == "" ||preg_match('/[^a-zA-Z0-9]+/',$chainId))
                return json_encode(array("msg"=> "Invalid chain Id","status"=> "error"));
        if($date == "" ||preg_match('/[^0-9\-]+/',$date))
                return json_encode(array("msg"=> "Invalid date","status"=> "error"));
        if($type == "" ||preg_match('/[^A-Za-z]+/',$type))
                return json_encode(array("msg"=> "Invalid Type","status"=> "error"));
        
        if($type == "status")
        {
            $table = " 91_callStatusDaily ";
            $columns = " sum(total) as total ,sum(totalDuration) as totalNumber ,status as type";
            $condition = "chainId like '".$chainId."%' and date(date) between '".$date."' and '".$endDate."' group by status";
        }
        elseif($type == "callType" )
        {
            $table = " 91_callType";
            $columns = " sum(total) as total,callType as type";
            $condition = "chainId like '".$chainId."%' and date(date) between '".$date."' and '".$endDate."' group by callType";
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
                if($type == "status")
                  $sumofDuration += $row['totalNumber'];
                
            }
            $finalResponse['data']=$response;
            //$response['sum']['totalSumDuration'] = $sumOfCall;
            $finalResponse['totalSumDuration']=$sumOfCall;
//          $response['sum']['avgCallDuration'] = ($response['ANSWERED']['totalDuration']/$response['ANSWERED']['total']);
            if($type == "status"){
            $finalResponse['answeredCallPercent'] = number_format((($response['ANSWERED'] * 100) / $sumOfCall),2);
            $customerTime = $this->getResellerTotalStatistics($chainId,$date,2,$endDate);
            $myTime = $this->getResellerTotalStatistics($chainId,$date,1,$endDate);
            $finalResponse['customerTime'] = $this->convertSecondtoTime($customerTime['durationCharged']);//$this->convertSecondtoTime($sumofDuration);
            $finalResponse['myTime'] = $this->convertSecondtoTime($myTime['durationCharged']);//$this->getMyTotalTime($chainId,$date);
            }
        }
        else
        {
            $response = array("msg"=>"Error Fetching Details","status"=>"error");
        }
        
        return $finalResponse;
    }
    
    /*
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 19-12-2013
     * @description function use to get my total time duration 
     */
    function getMyTotalTime($chainId,$date){
        
        $table = " 91_callStatusDaily ";
        $columns = "sum(totalDuration) as totalNumber";
        $condition = "chainId like '".$chainId."' and date(date) between '".$date."' and date(now()) group by status";
        $selResult = $this->selectData($columns, $table,$condition);
        $sumofDuration = 0;
        if($selResult)
        {
           while($row = $selResult->fetch_array(MYSQLI_ASSOC))
            {
               $sumofDuration += $row['totalNumber'];
            }
            
           $mytime = $this->convertSecondtoTime($sumofDuration);
        }else
           $mytime = "00:00:00 Hrs";
        
        return $mytime;
    }
    
    function convertSecondtoTime($init){
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;

        return "$hours:$minutes:$seconds Hrs";
      }
      
    function getResellerTotalStatistics($chainId,$date,$type,$endDate = NULL)
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
        
        if($endDate == NULL){
            $endDate = date('Y-m-d');
        }
        
        $table = " 91_durationCharged";
        switch($type)
        {
            
            case 1:
            {
                $columns = " sum(durationCharged) as durationCharged ";
                $condition = "chainId = '".$chainId."' and date(date) between '".$date."' and '".$endDate."' ";
                break;
            }
            case 2:
            {
                $columns = " sum(durationCharged) as durationCharged";
                $condition = "resellerId = '".$chainId."' and date(date) between '".$date."' and '".$endDate."' ";
                break;
            }
            case 3:
            {
                $table = " 91_chainProfit";
                $columns = " sum(profit) as profit";
                $condition = "parentId = ".$chainId." and date(date) between '".$date."' and '".$endDate."' ";
                break;
            }
        }
        
        $selResult = $this->selectData($columns, $table,$condition);
        
        trigger_error("hoho".$this->querry);
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
        if($date == "" ||preg_match('/[^0-9\-]+/',$date))
                return json_encode(array("msg"=> "Invalid date","status"=> "error"));
        
        $chainId = $this->db->real_escape_string($chainId);
        $date = $this->db->real_escape_string($date);
        if($type == "duration")
        {
            $query = "select sum(durationCharged) as clmValue,chainId,resellerId from (SELECT * FROM `91_durationCharged` where resellerId = '".$chainId."' and date(date) between '".$date."' and date(now()) order by durationCharged DESC ) as durationTable group by chainId  limit 100";
        }
        elseif($type == "profit" || $type == "loss")
        {
          $query = "select sum(profit) as clmValue ,childId as chainId,currencyDesc as currency from (SELECT * FROM `91_chainProfit` where parentId = '".$chainId."' and date(date) between '".$date."' and date(now()) order by profit DESC ) as t group by childId limit 100";
         
            }
     
        
        $result = $this->db->query($query);
       
        
        if($result)
        {
            
            $totalCallDuration = 0;$currency = '';
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                
                $response[$row['chainId']] = $row['clmValue'];
                $chainIdArr[] = $row['chainId'];
                $totalCallDuration += $row['clmValue'];
                if($type == "profit" || $type == "loss") 
                    $currency = $row['currency'];
            }
       
            
            $userNameArr = $this->getUserNameViaChainIdArray($chainIdArr);
            if(!$userNameArr)
                json_encode(array("msg"=>"Error fetching User Details please try again later","status"=>"error"));
            
            foreach($response as $key => $value)
            {
//                $finalArr[$userNameArr[$key]]['clmValue'] = $value;
//                $finalArr[$userNameArr[$key]]['percentage'] = (($value/$totalCallDuration)*100);
                 
                if($type == "duration")
                    {
                        $hours = floor($value / 3600).".".floor(($value / 60) % 60);
                        $finalArr[$userNameArr[$key]] = (float)$hours;
                    }
                    elseif($type == "profit")
                    {
                        if($value > 0){
                        $finalArr[$userNameArr[$key]] = $value;//number_format((($value/$totalCallDuration)*100),2);
                        }
                    }elseif($type == "loss"){
                        if($value < 0){
                        $finalArr[$userNameArr[$key]] = -$value;//number_format((($value/$totalCallDuration)*100),2);
                        }
                    }
                               
            }
            $finalResponse['data']=$finalArr;
            $finalResponse['totalProfit'] = $totalCallDuration . " " .$currency;
            
            return $finalResponse;
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
    
     /**
     * @author Ankit patidar <ankitpatidar@hostnsoft.com>
     * @since 10/03/2014
     * @filesource
     * @uses to get details from  call failed error log
     * @param array $request: contains start date ,end date and search string
     * 
     */
    function callFailedErrorLog($request)
    {
        //set date in required format
        $sDate = (isset($request['sDate']) && $request['sDate'] != '')?date('Y-m-d 00:00:00',strtotime($request['sDate'])):date('Y-m-d 00:00:00');
        $eDate = (isset($request['eDate']) && $request['eDate'] != '')?date('Y-m-d 23:59:59',strtotime($request['eDate'])):date('Y-m-d 23:59:59');
       
        $qString = (isset($request['q']) && $request['q']) != ''?$this->db->real_escape_string($request['q']):'';
        
        
        if(!isset($_SESSION['userid']))
        {
           return json_encode(array('status' => 0,'msg' => 'Your session has destroyed ,Please Login again')); 
        }
        
        $inClause = '';
        if($qString !='')
        {
            //code to get user chain id by name
            if(strlen($qString) > 3)
            {
                $resultCi = $this->selectData('chainId','91_manageClient',"userName LIKE '$qString%'");
               
                
                if($resultCi->num_rows > 0)
                {
                    $cIdArr = array();
                    while($cRow = $resultCi->fetch_array(MYSQLI_ASSOC))
                    {
                        $cIdArr[] = '"'.$cRow['chainId'].'"';
                        unset($cRow);
                    }
                    
                    if(!empty($cIdArr))
                        $inClause = ' chainId IN('.implode(",", $cIdArr).') or';
                }
                
                 
            }
            
            $likeQ = "reason LIKE '%$qString%' or $inClause telNum LIKE '%$qString%' and";
        }
        else
            $likeQ = '';
        
        $table = '91_rejectCalls';
        
        $this->db->select('*')->from($table)->where($likeQ.' date BETWEEN "'.$sDate.'" AND "'.$eDate.'"')->limit(50);
        
        
        $result = $this->db->execute();
        if($qString != '')
        {
//            var_dump($this->db);
//            var_dump($result);
//            echo 'query:'.$this->db->getQuery(); 
//            echo '<br>';
//            echo $likeQ.' date BETWEEN "'.$sDate.'" AND "'.$eDate.'"';
            
        }
//validate result
        if(!$result)
        {
            trigger_error('Problem while getting call failed error log details,query:'.$this->querry);
            return json_encode(array('status' => 0,'msg' => 'Problem while getting call error log details!!!'));
        }
        if($result->num_rows == 0)
        {
            return json_encode(array('status' => 0,'msg' => 'no Record found!!!'));
        }
        
        $data = array();
        $chainIds= array();
        While($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $data[] = $row;
            $clientNameJson = $this->getUserOneDetail($row['chainId'],'chainId','userName');
                $clientName = json_decode($clientNameJson,TRUE);
            $chainIds['chainIds'][$row['chainId']]= $clientName['userName'];     
            unset($row);
        }
        
        return json_encode(array('status' => 1,'msg' => 'Record Found@!!!','callFailedData' => $data,'chainIds' =>$chainIds));
        
    } //end of function callFailedErrorLog()
    
    function getCallDeatilsAdmin($userId,$keyword,$type,$route,$status)
    {
        if($userId  == "" || preg_match(NOTNUM_REGX, $userId))
        {
            $this->msg = "Invalid user please try again";
            $this->status = "error";
            return (array("msg"=>$this->msg,"status"=>$this->status));
        }
            
        
        if(!$this->check_admin($userId))
        {
            $this->msg = "Invalid user please try again";
            $this->status = "error";
            return (array("msg"=>$this->msg,"status"=>$this->status));
        }
            
        
        
        if(preg_match(NOTNUM_REGX, $type))
        {
            $this->msg = "Invalid type please contact provider";
            $this->status = "error";
            return (array("msg"=>$this->msg,"status"=>$this->status));
        }
        
        $condition = "";
        
        switch($type)
        {
            case '1':{
               
            if(preg_match(NOTALPHANUM_REGX, $keyword) || $keyword == "" || strlen($keyword) <3 || strlen($keyword) > 18 )
            {
                $this->msg = "Invalid username please provide a valid user name";
                $this->status = "error";
                return (array("msg"=>$this->msg,"status"=>$this->status));
            }
            
            $user = $this->getUserId($keyword);
            if(!$user)
            {
                $this->msg = "Invalid user no user exist with this name";
                $this->status = "error";
                return (array("msg"=>$this->msg,"status"=>$this->status));
            }
            
            $condition = " id_client=".$user." ";
            
                break;
                
            }
            case '2':{
            if(preg_match(NOTNUM_REGX, $keyword) || $keyword == "" || strlen($keyword) < 7 || strlen($keyword) > 18)
            {
                $this->msg = "Invalid keyword please provide a valid search parameter";
                $this->status = "error";
                return (array("msg"=>$this->msg,"status"=>$this->status));
            }
                $condition = " caller_id='".$keyword."' or called_number='".$keyword."' ";
                break;
                
            }
            case '3':{
            if(preg_match(NOTALPHANUM_REGX, $route) || $route == "" )
            {
                $this->msg = "Invalid route please contact provider";
                $this->status = "error";
                return (array("msg"=>$this->msg,"status"=>$this->status));
            }
            
            $condition = " route='".$route."' ";
            
                break;
                
            }
            case '4':{
                if(preg_match(NOTALPHABATE_REGX, $status) || $status == "" )
                {
                    $this->msg = "Invalid status please contact provider";
                    $this->status = "error";
                    return (array("msg"=>$this->msg,"status"=>$this->status));
                }
                $condition = " status='".$status."' ";
                break;
                
            }
            default :{
                    $condition = " 1 ";
                }
        }
        $columns = "id_client,id_chain,caller_id,called_number,call_start,call_end,status,hangup_reason,call_type,balance_deduct,route";
        $table = "91_calls";
        $condition .= " order by id_call DESC limit 10";
        $result = $this->selectData($columns, $table,$condition);
//        echo $this->querry;
        if(!$result || $result->num_rows < 1)
        {
            $this->msg = "Error no record found";
            $this->status = "error";
            return (array("msg"=>$this->msg,"status"=>$this->status));
        
        }
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $data[] = $row;
            $userIdArr[] = $row['id_client'];
        }

        $userIdArr = array_unique($userIdArr);
        $userIdStr = implode(",",$userIdArr);
        unset($userIdArr);
        $resultUser = $this->selectData("userName,userId","91_manageClient"," userId IN (".$userIdStr.")");
        if(!$resultUser)
        {
            $this->msg = "Error fetching user details please try again later";
            $this->status = "error";
            return (array("msg" => $this->msg, "status" => $this->status));
        }
        while($row = $resultUser->fetch_array(MYSQLI_ASSOC))
        {
            $userIdArr[$row['userId']] = $row['userName'];
        }
        foreach($data as $key =>$value)
        {
            
            $data[$key]['userName'] = $userIdArr[$value['id_client']];
        }
        
        
        
        return $data;
    }
    
}
?>
