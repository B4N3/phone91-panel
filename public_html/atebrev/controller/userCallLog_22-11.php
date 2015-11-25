<?php
/* @author : sameer 
 * @created : 17-08-2013
 * @desc : this is controller file for all actions related to call log  
 */

include_once (dirname(dirname(__FILE__)).'/config.php');
include_once (CLASS_DIR.'callLog_class.php');


$logClsObj = new log_class();

if(!$funobj->login_validate()){
        $funobj->redirect(ROOT_DIR."index.php");
}


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
switch ($_REQUEST['call'])
{
   #fetch the recent call from db
   case "recentCall" :
   {  
       $userid = $_SESSION['id'];
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
       break;
   }
   case "userCallLogs" :
   {   
       $userid = $_SESSION['id'];
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
       break;
   }
   case "searchCallLogs" :
   {   
       $userid = $_SESSION['id'];
       $searchKeyword = $_REQUEST['keyword'];
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
       break;
   }
   case "getCallLogsDetails" :
   {   
       $userid = $_SESSION['id'];
       $number = trim($_REQUEST['number']);
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
       break;
   }
   case "showStatus":
   {
       if(isset($_REQUEST['userId']) && $_REQUEST['userId'] != "")
        $userId = $_REQUEST['userId'];
       else
        $userId = $_SESSION['id'];
       
      $resellerId = $funobj->getResellerId($userId);
      if($resellerId == $_SESSION['id'] || $resellerId == 1)
        $res = $logClsObj->getCallLogSummary("status",$userId);
      else
          echo(json_encode (array("msg"=>"Invalid User Please try again with valid user","status"=>"error")));
          break;
   }
   case "showCallVia":
   {
       if(isset($_REQUEST['userId']) && $_REQUEST['userId'] != "")
        $userId = $_REQUEST['userId'];
       else
        $userId = $_SESSION['id'];
      $resellerId = $funobj->getResellerId($userId);
      if($resellerId == $_SESSION['id'] || $resellerId == 1)
      {
        $res = $logClsObj->getCallLogSummary("callVia",$userId);
        echo $res;
      }
      else
        echo(json_encode (array("msg"=>"Invalid User Please try again with valid user","status"=>"error")));
       break;
   }
   case "getDefaultNumber":
   {
     include_once (CLASS_DIR.'contact_class.php');
     $contactObj = new contact_class();
     
     echo $contactNumber = $contactObj->getUserDefaultNumber($_SESSION['id']);
     break;
   }
   case "getStatusDetails":
   {
     $chainId = validateUserGetChainId($_REQUEST['userId'],$_REQUEST['type'],$_SESSION);
     if(!$chainId)
     {
         echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
         exit();
     }
     $date  = date('Y-m-d',strtotime("-30 days"));
     $type = "status";
     echo $logClsObj->getStatusAndTypeDetails($chainId,$date,$type);
     break;
   }
   case "getCallViaDetails":
   {
     $chainId = validateUserGetChainId($_REQUEST['userId'],$_REQUEST['type'],$_SESSION);
     if(!$chainId)
     {
         echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
         exit();
     } 
     $date  = date('Y-m-d',strtotime("-30 days"));
     $type = "callType";
     echo $logClsObj->getStatusAndTypeDetails($chainId,$date,$type);
     break;
   }
   case "getStatistics":
   {
     $chainId = validateUserGetChainId($_REQUEST['userId'],$_REQUEST['type'],$_SESSION);
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
     break;
   }
   case "getResellerDurationDetails":
   {
     $chainId = validateUserGetChainId($_REQUEST['userId'],$_REQUEST['type'],$_SESSION);
     if(!$chainId)
     {
         echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
         exit();
     } 
     $date  = date('Y-m-d',strtotime("-30 days"));
     
     echo $logClsObj->getResProfitDurationGraphDetails($chainId,$date,"duration");
     
     break;
   }
   case "getResellerProfitDetails":
   {
       
        
        $chainId = validateUserGetChainId($_REQUEST['userId'],$_REQUEST['type'],$_SESSION);
        if(!$chainId)
        {
            echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
            exit();
        } 
        $date  = date('Y-m-d',strtotime("-30 days"));

        echo $logClsObj->getResProfitDurationGraphDetails($chainId,$date,"profit");

        break;
   }
   case "getCreditGraphDetails":
   {
     $chainId = validateUserGetChainId($_REQUEST['userId'],$_REQUEST['type'],$_SESSION);
     if(!$chainId)
     {
         echo json_encode(array("msg"=> "Invalid User Please try with a valid user","status"=>"error"));
         exit();
     } 
//     $date  = date('Y-m-d',strtotime("-30 days"));
     
     echo $logClsObj->getCreditGraph($chainId);
     
     break;
   }
     case "userCallLogsForChart" :
   {   
         /**
          * @case added by Ankitpatidar <ankitpatidar@hostnsoft.com> on 31/10/2013
          * @desc:code to get details for draw graph
          */
         
       //get user id from session  
       $userid = $_SESSION['id'];
       
       //get call log details for this user
       if($_SESSION['isAdmin'] == 1) //apply validation for admin
       $res  = getUserCallLogDetails();
       
       echo $res;
     
       break;
   }
   
   
  
   
   
   
}


 /**
    * @author Ankit Patidar <ankitpatidar@hostnsoft.com> on 31/10/2013
    * @Description function to get call log details ,it returns date wise call counter
    * @return Array array('data', count)
    */
   function getUserCallLogDetails()
   {
       //create fun class object
       $funObj = new fun();
       
       //get date
       $Olddate = date('Y-m-d');

       $date = strtotime($Olddate.' -30');
       
       $result = $funObj->selectData('COUNT(*) as total, DATE(call_start)','91_calls','DATE(call_start) BETWEEN '.date('Y-m-d',$date).' AND DATE( NOW( ) ) GROUP BY DATE(call_start)');
       
       while( $row = $result->fetch_array(MYSQL_ASSOC) )
       {
           //create array date wise
           $res[$row['DATE(call_start)']] = $row['total'];
           
           unset($row);
       }
     
      return json_encode($res);
   } //end of funtion 

?>
