<?php
/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
include dirname(dirname(__FILE__)) . '/config.php';
class routeClass extends fun
{
    var $msg = "";
    var $validateFlag = false;
    function validateRouteParam($request){
        if(preg_match('/[^a-zA-Z0-9]+/',$request['routeName']))
        {
            $this->msg = "Error Invalid Route Name Please Enter a Valid Route Name. Only Aplphabet and Numbers are Allowed";
            return 0;
        }
        if(preg_match('/[^0-9]+/',$request['routeQuality']))
        {
            $this->msg = "Error Invalid Route Quality Value";
            return 0;
        }
        if(preg_match(NOTUSERNAME_REGX,$request['routeUserName']))
        {
            $this->msg = "Error Invalid Route User Name Please Enter a Valid Data";
            return 0;
        }
        if(preg_match(NOTPASSWORD_REGX,$request['routePassword']))
        {
            $this->msg = "Error Invalid Route Password Please Enter only alphaNumber and (@,$,},{,.,_,-,(,),],[,:)";
            return 0;
        }
        if(preg_match('/[^0-9\.]+/',$request['routeIps']) || strlen($request['routeIps']) < 7 || strlen($request['routeIps']) >15 )
        {
            $this->msg = "Error Invalid Route Ip please enter a valid ip address";
            return 0;
        }
        if(preg_match('/[^0-9]+/',$request['routeCallLimit']) || $request['routeCallLimit'] < 1 || $request['routeCallLimit'] > 10)
        {
            $this->msg = "Error Invalid Route Call Limit please enter a valid number between 1 and 10";
            return 0;
        }
        if(preg_match('/[^0-9\*\#\+]+/',$request['routePrefix']))
        {
            $this->msg = "Error Invalid Route prefix please enter a valid prefix you can use numeric character and (*,#,+) only";
            return 0;
        }
        $this->validateFlag = true;
        return 1;
    }
    function addRoute(){
        
        $validateResult = $this->validateRouteParam($request);        
        if(!$validateResult || $this->validateFlag == false)
            return json_encode(array("msg"=>$this->msg,"status"=>"error"));
        
        
    }
    
    function getRoute()
    {
        $selRes = $this->selectData("*", "91_route");
        if($selRes)
        {
            while($row = $selRes->fetch_array(MYSQLI_ASSOC))
            {
                $data[] = $row;
            }
            
            return json_encode($data);
        }
        else {
            return false;
        }
    }
    
  
    function getRouteAndDialPlanList($param,$userId,$userType){
        
        $routeData = array();
        $dialPlanData = array();
        if($userType !=1){
             return json_encode(array("msg"=>"you have no permission to get Route and dial plan list","status"=>"error"));
        }
        if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
        
        if(preg_match('/[^0-9]+/', $param['clientId']) || $param['clientId'] == "")
             return json_encode(array("msg"=>"Error Invalid client selected please try again","status"=>"error"));
        
        $selRes = $this->selectData("*", "91_route");
        if($selRes)
        {
            while($row = $selRes->fetch_array(MYSQLI_ASSOC))
            {
                $routeData[$row['routeId']] = $row['route'];
            }
        }
        
        $DialRes = $this->selectData("*", "91_dialPlanDetail","userId=".$userId);
        if($DialRes)
        {
            while($dialrow = $DialRes->fetch_array(MYSQLI_ASSOC))
            {
                $dialPlanData[$dialrow['id']] = $dialrow['planName'];
            }
        }
        
        $userInfo = $this->getUserBalanceInfo($param['clientId']);
        if($userInfo['routeId'] == '' || $userInfo['routeId'] == NULL){
            $userInfo['routeId'] = 0;
        }
        if($userInfo['isDialPlan'] == '' || $userInfo['isDialPlan'] == NULL){
            $userInfo['isDialPlan'] = 0;
        }
        $isDialPlan = $userInfo['isDialPlan'];
        $routeId = $userInfo['routeId'];
       return json_encode(array("status"=>"success","routeData"=>$routeData,"dialPlanData"=>$dialPlanData,"isDialPlan"=>$isDialPlan,"routeId"=>$routeId));
             
    }
    
    function setUserDialPlanOrRoute($param,$userId,$userType){
        
       if($userType !=1){
             return json_encode(array("msg"=>"you have no permission to get Route and dial plan list","status"=>"error"));
       }
        
       if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
       
       if(preg_match('/[^0-9]+/', $param['clientId']) || $param['clientId'] == "")
             return json_encode(array("msg"=>"you have no permission to update route or dial plan id ","status"=>"error"));
       
      #check permission for update route and dial plan 
      $resellerId = $this->getResellerId($param['clientId']);  
      
     
      if($resellerId != $userId)
      {
          return json_encode(array("status" => "error", "msg" => "you have no permission to change route or dial plan."));
      }
      
       
       #get user old status
       $userInfo = $this->getUserBalanceInfo($param['clientId']);
        if($userInfo['routeId'] == '' || $userInfo['routeId'] == NULL){
            $userInfo['routeId'] = 0;
        }
        if($userInfo['isDialPlan'] == '' || $userInfo['isDialPlan'] == NULL){
            $userInfo['isDialPlan'] = 0;
        }
       
        $oldStatus = $userInfo['routeId'].",".$userInfo['isDialPlan']; 
       
        #include reseller class to get all user list of chain  
        include_once(CLASS_DIR."reseller_class.php");
        
        $res_obj = new reseller_class(); 
        
        $condition = $res_obj->getResellerAllUser($param['clientId']);
        
       if($param['routeDialplan'] == "route"){
           
           $result = $this->setUserRoute($condition,$param['routeList'],0);  
           $newStatus = $param['routeList'].",0";
           $this->accountManagerLog($param['clientId'],13,$oldStatus,$newStatus,$userId,"update route and dial plan");
       
           
       }elseif ($param['routeDialplan'] == "dialPlan") {
           
           $result = $this->setUserRoute($condition,$param['dialPlanList'],1);
           $newStatus = $param['dialPlanList'].",1";
           $this->accountManagerLog($param['clientId'],13,$oldStatus,$newStatus,$userId,"update route and dial plan");
      
           }
       
       
       
       
       if($result == 0){
           return json_encode(array("msg"=>"request not updated..","status"=>"error")); 
       }
       
       return json_encode(array("msg"=>"request successfully updated..","status"=>"success")); 
       
    }
    
    
  function setUserRoute($condition,$routeId,$isDialPlan){
         
       if(preg_match('/[^0-9]+/', $routeId) || $routeId == "")
             return 0;
       
       $data = array("isDialPlan"=>$isDialPlan,"routeId"=>$routeId);
       $table = "91_userBalance";
       $res = $this->updateData($data, $table,$condition);
       
       if(!$res)
           return 0;
       
       return 1;
      
  }
    
    
}
?>