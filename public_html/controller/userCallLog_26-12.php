<?php

/**
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  10 DEC 2013
 * @package Phone91 / controller
 */

include dirname(dirname(__FILE__)) . '/config.php';
if (!$funobj->login_validate()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}
if (!$funobj->check_reseller()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

class userCallLog {

    function recentCall($request, $session){
       
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();

        $userid = $session['id'];
        $res  = $logClsObj->getRecentCalls($userid);
        while($row = $res->fetch_array(MYSQL_ASSOC))
        {
            if(!in_array($row['called_number'], $checkArr))
            {
            $resArr[substr($row['uniqueId'], 0,-6)]["record"] = "";
            if( $row['uniqueId'] == substr($row['uniqueId'], 0,-3)."001" )
            {
                $resArr[substr($row['uniqueId'], 0,-6)]["record"] = $row;

            }

            $resArr[substr($row['uniqueId'], 0,-6)]["balance"] += $row['deductBalance'];

            $checkArr[] = $row['called_number'];
            }
        }
        echo json_encode($resArr);
    }
   
   
   
    function userCallLogs($request, $session)
    {        
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();

        $userid = $session['id'];
        $res  = $logClsObj->getCallLogs($userid);
        include_once (dirname(dirname(__FILE__)).'/classes/phonebook_class.php');
        $phnbClsObj = new phonebook_class();
        $allcontact = $phnbClsObj->getAllContact($userid);
        foreach ($allcontact as $cntValue)
        {
        foreach($cntValue as $value)
        {
            $contactNoArr[$value['contactNo']] = $value['name'];
        }
        }
        while($row = $res->fetch_array(MYSQL_ASSOC))
        {
        $row['contactName'] = $contactNoArr[$row['called_number']];
        $resArr[] = $row;
        }

        echo json_encode($resArr);
    }

    function searchCallLogs($request, $session)
    {
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();
        
        $userid = $session['id'];
        $searchKeyword = $request['keyword'];
        
        if(preg_match('/[^a-zA-Z0-9]+/', $searchKeyword))
        {
            $arr['msg'] = "please enter a proper keyword";
            $arr['type'] = "error";
            die(json_encode($arr)); 
        }
        
        $res  = $logClsObj->searchCallLogs($searchKeyword,$userid);
        
        include_once (dirname(dirname(__FILE__)).'/classes/phonebook_class.php');
        $phnbClsObj = new phonebook_class();
        $allcontact = $phnbClsObj->getAllContact($userid);
        
        foreach ($allcontact as $cntValue)
        {
            foreach($cntValue as $value)
            {
                $contactNoArr[$value['contactNo']] = $value['name'];
            }
        }
        while($row = $res->fetch_array(MYSQL_ASSOC))
        {
            $row['contactName'] = $contactNoArr[$row['called_number']];
            $resArr[] = $row;
        }
        echo json_encode($resArr);
    }  

    function getCallLogsDetails($request, $session)
    {
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();
        
        $userid = $session['id'];
        $number = trim($request['number']);
        
        if(preg_match('/[^0-9]+/', $number))
        {
        $arr['msg'] = "please select a name";
        $arr['type'] = "error";
        die(json_encode($arr)); 
        }

        $res  = $logClsObj->getCallLogsDetails($number,$userid);

        include_once (dirname(dirname(__FILE__)).'/classes/phonebook_class.php');
        
        $phnbClsObj = new phonebook_class();
        $allcontact = $phnbClsObj->getAllContact($userid);
        
        foreach ($allcontact as $cntValue)
        {
            foreach($cntValue as $value)
            {
                $contactNoArr[$value['contactNo']] = $value['name'];
            }
        }
        while($row = $res->fetch_array(MYSQL_ASSOC))
        {
            $row['contactName'] = $contactNoArr[$row['called_number']];
            $row['callduration'] = gmdate("i:s",$row['duration']);
            $row['currencyName'] = $logClsObj->getCurrencyViaApc($row['currencyId'],1);
            $resArr[] = $row;
        }
        echo json_encode($resArr);
    }  
    
    
    function showStatus($request, $session)
    {
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();
        
        if(isset($request['userId']) && $request['userId'] != "")
            $userId = $request['userId'];
        else
            $userId = $session['id'];

        $resellerId = $funobj->getResellerId($userId);
        
        if($resellerId == $session['id'] || $resellerId == 1)
        $res = $logClsObj->getCallLogSummary("status",$userId);
        else
        echo(json_encode (array("msg"=>"Invalid User Please try again with valid user","status"=>"error")));
        
    }
    
    function showCallVia($request, $session)
    {
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();
        
        if(isset($request['userId']) && $request['userId'] != "")
            $userId = $request['userId'];
        else
            $userId = $session['id'];
        
        $resellerId = $funobj->getResellerId($userId);
        if($resellerId == $session['id'] || $resellerId == 1)
        {
            $res = $logClsObj->getCallLogSummary("callVia",$userId);
            echo $res;
        }
        else
            echo(json_encode (array("msg"=>"Invalid User Please try again with valid user","status"=>"error")));
    }  
    
    
    function getDefaultNumber($request, $session)
    {
        include_once (CLASS_DIR.'contact_class.php');
        $contactObj = new contact_class();

        echo $contactNumber = $contactObj->getUserDefaultNumber($session['id']);
    }
    
    
    function getStatusDetails($request, $session)
    {
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();
    
        $chainId = $this->validateUserGetChainId($request['userId'],$request['type'],$session);
        if(!$chainId)
        {
            echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
            exit();
        }
        $date  = date('Y-m-d',strtotime("-30 days"));
        $type = "status";

        return $logClsObj->getStatusAndTypeDetails($chainId,$date,$type);
    }  

    
    function getCallViaDetails($request, $session)
    {
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();

        $chainId = $this->validateUserGetChainId($request['userId'],$request['type'],$session);
        if(!$chainId)
        {
            echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
            exit();
        } 
        $date  = date('Y-m-d',strtotime("-30 days"));
        $type = "callType";
        return $logClsObj->getStatusAndTypeDetails($chainId,$date,$type);

    }  

    function getStatistics($request, $session)
    {
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();

        $chainId = $this->validateUserGetChainId($request['userId'],$request['type'],$session);
        if(!$chainId)
        {
            echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
            exit();
        }
        $date  = date('Y-m-d',strtotime("-30 days"));

        $myTotalTime = $logClsObj->getResellerTotalStatistics($chainId,$date,1);
        
        if($myTotalTime)
            $response['myTotalTime'] = $myTotalTime; 
        
        $customerTotalTime = $logClsObj->getResellerTotalStatistics($chainId,$date,2);
        
        if($customerTotalTime)
            $response['customerTotalTime'] = $customerTotalTime;
        
        $totalProfit = $logClsObj->getResellerTotalStatistics($chainId,$date,3);
        
        if($totalProfit)
            $response['totalProfit'] = $totalProfit;
       
        echo json_encode($response);
    }  

    function getResellerDurationDetails($request, $session)
    {
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();

        $chainId = $this->validateUserGetChainId($request['userId'],$request['type'],$session);
        if(!$chainId)
        {
            echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
            exit();
        } 
        $date  = date('Y-m-d',strtotime("-30 days"));

        return $logClsObj->getResProfitDurationGraphDetails($chainId,$date,"duration");
    }  
    
    
    function getResellerProfitDetails($request, $session)
    {
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();

        $chainId = $this->validateUserGetChainId($request['userId'],$request['type'],$session);
        if(!$chainId)
        {
            echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
            exit();
        } 
        $date  = date('Y-m-d',strtotime("-30 days"));

        return $logClsObj->getResProfitDurationGraphDetails($chainId,$date,"profit");

    } 
    
    
    function getCreditGraphDetails($request, $session)
    {
        
        include_once (CLASS_DIR.'callLog_class.php');
        $logClsObj = new log_class();

        $chainId = $this->validateUserGetChainId($request['userId'],$request['type'],$session);
        if(!$chainId)
        {
            echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
            exit();
        } 
        //     $date  = date('Y-m-d',strtotime("-30 days"));

        return $logClsObj->getCreditGraph($chainId);

    }  
    
    
    function userCallLogsForChart($request, $session)
    {
        /**
        * @case added by Ankitpatidar <ankitpatidar@hostnsoft.com> on 31/10/2013
        * @desc:code to get details for draw graph
        */

        //get user id from session  
        $userId = $request['userId'];

        //get call log details for this user
        //if($session['isAdmin'] == 1) //apply validation for admin
        $res  = $this->getUserCallLogDetails($userId);

        echo $res;
    }  
    
    
    function userCallLogsForTimeLine($request, $session)
    {

        /**
        * @case added by Ankitpatidar <ankitpatidar@hostnsoft.com> on 31/10/2013
        * @desc:code to get details for draw graph
        */

        //get user id from session  
        $userId = $request['userId'];

        //get user chain id
        $chainId = $this->validateUserGetChainId($userId,$request['type'],$session);

        if(!$chainId)
        {
        //trigger  error
            trigger_error('chain id not found for user:'.$userId);     
            echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
            exit();
        }

        //create function class object
        $funObj = new fun();

        //get table name
        $durationTBL = '91_durationCharged';

        $result = $funObj->selectData('SUM(durationCharged) as sum,DATE(date) as date', $durationTBL," chainId='$chainId' and DATE(date) BETWEEN DATE_SUB(NOW(),INTERVAL 90 DAY) and DATE(NOW()) group by DATE(date)");

        if(!$result)
        trigger_error('problem while gettig result for user:'.$userId);

        //code to get call counts
        $callTbl = '91_calls';

        //get answered call count date wise
        $resultCall = $funObj->selectData('COUNT(id_chain) as count,DATE(call_start) as date', $callTbl," id_chain='$chainId' and status = 'ANSWERED' and DATE(call_start) BETWEEN DATE_SUB(NOW(),INTERVAL 90 DAY) and DATE(NOW()) group by DATE(call_start)");

        while($row = $result->fetch_array(MYSQL_ASSOC))
        {
        $ress['time'][] = $row; 
        }

        if(!$resultCall)
        trigger_error('problem while gettig result for user:'.$userId);



        while( $callRow = $resultCall->fetch_array(MYSQL_ASSOC))
        {
        $ress['count'][] = $callRow;
        }
        /**
        * SELECT COUNT( id_chain ) 
        FROM  `91_calls` 
        WHERE id_chain =  '111111lm'
        AND STATUS =  'ANSWERED'
        GROUP BY DATE( call_start ) 
        LIMIT 0 , 30
        */

        print_r($ress);

        die('passion');
        //get call log details for this user
        //if($session['isAdmin'] == 1) //apply validation for admin
        //$res  = $this->getUserCallLogDetails($userid);

        unset($errTalk); //unset error handler

        unset($funObj); //unset funObj   
        echo $res;

    }
    
    
    /**
    * @author Ankit Patidar <ankitpatidar@hostnsoft.com> on 31/10/2013
    * @Description function to get call log details for timeLine ,it returns date wise call counter,profit,total mins,total customer mins and ACD(average call duration)
    * @param  int $userId userid 
    * @return Array array('key', value)
    */
    function getUserCallLogDetails($userId)
    {
        //create fun class object
        $funObj = new fun();

        $result = $funObj->selectData('COUNT(*) as total, DATE(call_start) as callStart','91_calls','id_client='.(int)$userId.' AND DATE(call_start) BETWEEN DATE_SUB(NOW(),INTERVAL 90 DAY) AND DATE( NOW( ) ) GROUP BY DATE(call_start)');


        while( $row = $result->fetch_array(MYSQL_ASSOC) )
        {
            //$index = date('d',$row['DATE(call_start)']);

            $date = new DateTime($row['callStart']);
            $index = (int) $date->format('d');

            //create array date wise
            $res[$index] = $row['total'];

            unset($row);
        }

        unset($funObj);

        return json_encode($res);
    } //end of funtion 


    /**
    * @author Ankit Patidar <ankitpatidar@hostnsoft.com> on 31/10/2013
    * @Description function to get call log details ,it returns date wise call counter
    * @param  int $userId userid 
    * @return Array array('data', count)
    */
    function getUserCallLogForTimeLine($userId)
    {
        //create fun class object
        $funObj = new fun();

        //get date
        $Olddate = date('Y-m-d');

        $date = strtotime($Olddate.' -30');

        $result = $funObj->selectData('COUNT(*) as total, DATE(call_start)','91_calls','id_client="'.(int)$userId.'" AND DATE(call_start) BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND DATE( NOW( ) ) GROUP BY DATE(call_start)');

        while( $row = $result->fetch_array(MYSQL_ASSOC) )
        {
            //create array date wise
            $res[date('d',$row['DATE(call_start)'])] = $row['total'];
            $rows[] = $row;
            unset($row);
        }

        return json_encode($res);
    } //end of funtion getUserCallLogForTimeLine

    
    function validateUserGetChainId($userId,$type,$session)
    {
        $logClsObj = new log_class();
        if(isset($userId) && $userId != "")
        {
            if($type == 1)
            {
                if(preg_match('/[^0-9]+/', $userId))
                        return false;
            $chainId = $logClsObj->getUserChainId($userId);
            }
            elseif($type == 2)
            {
                if(preg_match('/[^0-9a-zA-Z]+/', $userId))
                        return false;
                $chainId = $logClsObj->getUserChainIdViaName($userId);
            }   
        }
        else
        $chainId = $session['chainId'];

        return $chainId;
    }

    function getAllChart($request, $session){
        
        $chartData = array();
        $graph = explode(',', $request['graph']);
        for($i=0;$i<count($graph);$i++){
            $chartData[$graph[$i]] = $this->$graph[$i]($request, $session);
        }
        echo json_encode($chartData);

    }

}

try{
    $callLogObj = new userCallLog();
    if (isset($_REQUEST['call']) && $_REQUEST['call'] != "")
       $callLogObj->$_REQUEST['call']($_REQUEST, $_SESSION);
}
 catch (Exception $e)
 {
     mail("sudhir@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }
?>
