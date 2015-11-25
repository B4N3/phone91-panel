<?php
/** 
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
    
  
    function getRouteAndDialPlanList($userId,$userType){
        
        $routeData = array();
        $dialPlanData = array();
        if($userType !=1){
             return json_encode(array("msg"=>"you have no permission to get Route and dial plan list","status"=>"error"));
        }
        if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
        
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
        
        
       return json_encode(array("status"=>"success","routeData"=>$routeData,"dialPlanData"=>$dialPlanData));
             
    }
    
    function setUserDialPlanOrRoute($param,$userId,$userType){
        
       if($userType !=1){
             return json_encode(array("msg"=>"you have no permission to get Route and dial plan list","status"=>"error"));
       }
        
       if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
       
       if(preg_match('/[^0-9]+/', $param['clientId']) || $param['clientId'] == "")
             return json_encode(array("msg"=>"you have no permission to update route or dial plan id ","status"=>"error"));
        
       if($param['routeDialplan'] == "route"){
           $result = $this->setUserRoute($param['clientId'],$param['routeList'],0);           
       }elseif ($param['routeDialplan'] == "dialPlan") {
           $result = $this->setUserRoute($param['clientId'],$param['dialPlanList'],1);
       }
       
       if($result == 0){
           return json_encode(array("msg"=>"request not updated..","status"=>"error")); 
       }
       
       return json_encode(array("msg"=>"request successfully updated..","status"=>"success")); 
       
    }
    
    
  function setUserRoute($userId,$routeId,$isDialPlan){
      
       if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return 0;
       
       if(preg_match('/[^0-9]+/', $routeId) || $routeId == "")
             return 0;
       
       $data = array("isDialPlan"=>$isDialPlan,"routeId"=>$routeId);
       $table = "91_userBalance";
       $condition = "userId =".$userId ;
       $res = $this->updateData($data, $table,$condition);
       
       if(!$res)
           return 0;
       
       return 1;
      
  }
    
    
}
?>