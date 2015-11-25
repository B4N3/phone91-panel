<?php 
/*
 * @author sudhir pandey <sudhir@hostnsoft.com>
 * @package Phone91 / api
 * @description calculate route profit for last date and set this file as a cron 
 */

date_default_timezone_set("Asia/Kolkata");

//Include path page so that we can define all constant for our system
include_once '/home/voicepho/public_html/definePath.php';

//Include regex constant page so that we can define all constant for our system
include_once '/home/voicepho/public_html/defineConstant.php';

//include all necessary function
include_once "/home/voicepho/public_html/common_function.php";
include_once "/home/voicepho/public_html/function_layer.php";


$lastdate = date("Y-m-d",strtotime("-1 day")); //
$startTime = $lastdate." 00:00:00";
$endTime = $lastdate." 23:59:59";

// get user deduct balance 
$data = getUserDeductBal($startTime, $endTime);

if($data['count'] > 0){
   
 //get route deduct balance
 $routeData = routeDeductBal($data,$startTime,$endTime);

 $result = calculateRouteProfit($data,$routeData,$startTime);

 if($result){
     mail('sudhirp29@gmail.com','Route Profit','successfully profit updated');
    echo "successfully profit updated";
 }else{
     mail('sudhirp29@gmail.com','Route Profit','Profit Not Update.');
     echo "Profit Not Update.";
    }
}else{
    mail('sudhirp29@gmail.com','Route Profit','No record found in chain balance report. ');
    echo "No record found ...";
}

function getUserDeductBal($startTime,$endTime){
    
    $funobj = new fun(); 
    $table = '91_chainBalanceReport';
    $selectData = 'chainId,userId,routeId,sum(deductBalance) as deductBal,currencyId,durationCharged,currencyDesc';
    $condition = "chainId LIKE '1111____' and date BETWEEN '".$startTime."' AND '".$endTime."' GROUP BY userId,routeId"; 
    
    $routeId = array();$userId = array();$deductBal = array();$currencyId = array();
    $durationCharged = array(); $currencyDesc = array();
    $funobj->db->select($selectData)->from($table)->where($condition);
    $funobj->db->getQuery();  
        
    $result = $funobj->db->execute();
    $count = $result->num_rows;
    if($result->num_rows > 0) 
        {	
            while($row = $result->fetch_array(MYSQL_ASSOC)) 
            {
                $routeId[] = $row['routeId'];
                $userId[] = $row['userId'];
                $deductBal[] = $row['deductBal'];
                $currencyId[] = $row['currencyId'];
                $durationCharged[] = $row['durationCharged'];
                $currencyDesc[] = $row['currencyDesc'];
                     
            }
        }
    
    return array("count"=>$count,"routeId"=>$routeId,"userId"=>$userId,"deductBal"=>$deductBal,"currencyId"=>$currencyId,"durationCharged"=>$durationCharged,"currencyDesc"=>$currencyDesc);   
        
}


function routeDeductBal($data,$startTime,$endTime){
    
    $funobj = new fun(); 
    $table = '91_routeBalanceReport';
    $routeDeductBal = array(); $routeCurrency = array();
    for($noOfCalls = 0;$noOfCalls < $data['count'];$noOfCalls++){
        
        $selectData = 'sum(deductBalance) as routdeductBal,currencyDesc';
        $condition = "routeId = '".$data['routeId'][$noOfCalls]."' and uniqueId IN (SELECT uniqueId FROM 91_chainBalanceReport WHERE userId ='".$data['userId'][$noOfCalls]."' and routeId = '".$data['routeId'][$noOfCalls]."' and date BETWEEN '".$startTime."' AND '".$endTime."')"; 
        $funobj->db->select($selectData)->from($table)->where($condition);
         
        $funobj->db->getQuery(); 

        $result = $funobj->db->execute();
        $count = $result->num_rows;
        if($result->num_rows > 0) 
            {	
                $row = $result->fetch_array(MYSQL_ASSOC);
                $routeDeductBal[$noOfCalls] = $row['routdeductBal'];
                $routeCurrency[$noOfCalls] = $row['currencyDesc'];
           }else{
               $routeDeductBal[$noOfCalls] = 0;
               $routeCurrency[$noOfCalls] = 'USD';
           }
    }
    
    
    return array("routeDeductBal"=>$routeDeductBal,"routeCurrency"=>$routeCurrency);
    
}


function calculateRouteProfit($data,$routeData,$startTime){

    
    $profitQuery = "insert into 91_routeProfit (routeId, userId, profit, currencyDesc, date) values ";

    for($totalCount = 0;$totalCount < $data['count'];$totalCount++){

        $funobj = new fun(); 

        $fromCurr = $data['currencyDesc'][$totalCount];
        $toCurr = $routeData['routeCurrency'][$totalCount];
        $amount = $data['deductBal'][$totalCount];
        $routeAmount = $routeData['routeDeductBal'][$totalCount];
        
       if($fromCurr != $toCurr){
            $convertUserbal = $funobj->currencyConvert($fromCurr,$toCurr,$amount); 
        }else
            $convertUserbal = $amount; 

        $profit = $convertUserbal - $routeAmount;
        $routeId = $data['routeId'][$totalCount];
        $userId = $data['userId'][$totalCount];
        
        $profitQuery .= "('".$routeId."','".$userId."','".$profit."','".$toCurr."','".$startTime."'),";

    }

    $profitQuery =  substr($profitQuery, 0, -1); 
   
   $res = $funobj->db->query($profitQuery);
    if($res)
        return true;
    else
        return false;
    

}

    
    
?>