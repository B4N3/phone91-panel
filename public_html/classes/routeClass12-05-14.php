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
    
}
?>