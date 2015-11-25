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
}
?>
