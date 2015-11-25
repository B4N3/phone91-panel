<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
include dirname(dirname(__FILE__)) . '/config.php';

class pricingController {
    
    /**
    * @author sameer rathod <sameer@hostnsoft.com>
    * @param type $request
    * @param type $session
*/
   public function getDefaultTariffList($request, $session) {
      
//       include_once(CLASS_DIR . "function_layer.php");
       $funObj = new fun();
       
       $domainResult = $funObj->getDomainResellerIdViaApc($_SERVER['HTTP_HOST'],2);
//       print_r($domainResult);
       $resellerId = $domainResult['resellerId'];
       
       
       $result = $funObj->getResellerDefaultCurrency($resellerId , "",2,$domainResult['id']);
       if(!$result)
           return json_encode(array("msg"=>"Error fetching details please try again later","status"=>"error"));
       while($row = $result->fetch_array(MYSQLI_ASSOC))
       {
           $row['currency'] = $funObj->getCurrencyViaApc($row['currencyId'],1); 
           $data[] = $row;
       }
       
        $res = json_encode($data);
        return  $res;
   }
    
    
}
try{
    $pricingController  = new pricingController();   
    
    if (isset($_REQUEST['action']) && $_REQUEST['action'] != "" && method_exists($pricingController,$_REQUEST['action'] ))
    {
       echo $pricingController->$_REQUEST['action']($_REQUEST, $_SESSION);
    }
    else
    {
  echo 'You dont have permission to access!';
  die();
    }    
}
 catch (Exception $e)
 {
     mail("sudhir@hostnsoft.com",__FILE__,print_R($e->getMessage(),1));
 }

