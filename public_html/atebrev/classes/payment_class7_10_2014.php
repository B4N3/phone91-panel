<?php

/**
 * @author  Rahul <rahul@hostnsoft.com>
 * @modified by sudhir <sudhir@hostnsoft.com>
 * @since 24-07-2013
 * @package Phone91
 * @details class use for all settings (phone,email setting). 
 */

include dirname(dirname(__FILE__)).'/config.php';
include_once dirname(dirname(__FILE__)).'/paymentConfig.php';


class payment_class extends fun{
        
   function saveOrderDetail($param,$userId){
       
       //get tracker time
        $startTimTracker = date(DATEFORMAT); 
        
        //call tracker
        $trackId = $this->paymentTracker(null, $startTimTracker,'','user order for recharge',$param);

        //get tracker time
        $startTimTracker = date(DATEFORMAT); 

        
        
        if(preg_match(NOTNUM_REGX, $userId))
        {
           return json_encode(array('status' => 'error', 'msg' => 'User id not valid!'));
        }
       #get order id 
       $orderId = $this->randomOrderId(15);
       $userDetail = $this->getUserBalanceInfo($userId);
       $oldBalance = $userDetail['balance'];
       
       if(!is_numeric($param['talktime']))
        {
           return json_encode(array('status' => 'error', 'msg' => 'Talktime not valid!'));
        }
        
        $recharge = trim($param['talktime']);
        $talktime = trim($param['talktime']);
        $ip = $this->getUserIP();
        $table = '91_confirmOrder';
        $data = array("orderId"=>$orderId,
                     "clientId"=>$userId,
                     "recharge"=>$recharge,
                     "talktime"=>$talktime,
                     "balance"=>$oldBalance,
                     "rechargeTime"=>date('Y-m-d H:i:s'),
                     "status"=>'undone',
                     "ip"=>$ip,
                     "paymentBy"=>3);
       
        $response = $this->insertData($data, $table);
       
        //call tracker
        $trackId = $this->paymentTracker($trackId, $startTimTracker,'','user stripe order details',$data);
        
        if (!$response)
        {
            return json_encode(array('status' => 'error', 'msg' => 'Order id not save!'));
        }
        else 
        {
            
             return json_encode(array('status' => 'success', 'msg' => 'Save order Id',"orderId"=>base64_encode(base64_encode($orderId)),
                 'id' =>base64_encode(base64_encode($trackId))));
        }
       
   }
   
   /**
    * @author Sudhir Pandey <sudhir@hostnsoft.com>
    * @param type $param
    * @param type $userId
    * @return type
    */
   function paymentResponse($param,$userId){
       
       include_once(CLASS_DIR."transaction_class.php");
       
       //get tracker time
        $startTimTracker = date(DATEFORMAT); 
        
        //get order id and track id
        $orderId = base64_decode(base64_decode( $param['orderId']));
        $trackId = base64_decode(base64_decode( $param['id']));
        //call tracker
        $trackId = $this->paymentTracker($trackId, $startTimTracker,'','stripe:user came for automatic recharge',$param);
        //get tracker time
        $startTimTracker = date(DATEFORMAT); 
       
       $token  = $param['token']['id'];
       
     
       if($orderId == '' || $orderId == NULL){
          return json_encode(array('status' => 'error', 'msg' => 'Order id not valid!'));
       }
       
       
       #get payment detail by orderId
       $rsOrder = $this->selectData('*', '91_confirmOrder',"orderId='$orderId'");
       if(!$rsOrder)
        {
          return json_encode(array('status' => 'error', 'msg' => 'problem while get order detail')); 
        } 
       
        if ($rsOrder->num_rows > 0) {
         $row = $rsOrder->fetch_array(MYSQLI_ASSOC);	  
        
        #get order details from array
        $userId = $row['clientId'];
        $recharge = $row['recharge'];
        $amount =(int)$row['recharge'] * 100;
        $talktime = $row['talktime'];
        $dbIp = $row['ip'];

        //call tracker
        $trackId = $this->paymentTracker($trackId, $startTimTracker,'','stripe:user came for automatic recharge order details',$row);
        //get tracker time
        $startTimTracker = date(DATEFORMAT); 
        
        $description = $userId." : ".$orderId;
        $currencyId  = $this->getUserBalanceInfo($userId);
	$currency = $this->getCurrencyViaApc($currencyId['currencyId']);
       
        
        try
            {
            $charge = Stripe_Charge::create(array(
                     "amount" => $amount, // amount in cents, again
                     "currency" => $currency,
                     "card" => $token,
                     "description" => $description
            ));
                
            
            if($charge->paid == true)
            {
                $ip = $this->getUserIP();
                if($ip != $dbIp)
                  return json_encode(array('status' => 'error', 'msg' => 'problem in save detail of payment due to ip not valid!')); 
                
                $res = $this->updateUserStatus($userId,$talktime,$recharge,$orderId);   
		$data = json_decode($res, true);
                if($data['status'] == 'success')
                {
		      $updateBalance = $data['updateBalance']." ".$currency;
                       $trackId = $this->paymentTracker($trackId, $startTimTracker,'','stripe:recharged succefully',array('balance' => $updateBalance));
                      return json_encode(array('status' => 'success', 'msg' => 'Successfully charged '.$recharge.' '.$currency.'!',"updateBalance"=>$updateBalance)); 
               
                }
                else
                {
                   $trackId = $this->paymentTracker($trackId, $startTimTracker,'','stripe:problem in save detail of payment',$data); 
                   return json_encode(array('status' => 'error', 'msg' => 'problem in save detail of payment!')); 
            
                }
            }
            else
            {
                 $trackId = $this->paymentTracker($trackId, $startTimTracker,'','stripe:Payment not Successfully charged,payment status:'.$charge->paid,array()); 
                    return json_encode(array('status' => 'error', 'msg' => 'Payment not Successfully charged!')); 
            }
           
            }
            catch(Exception $e)
            {
          
                return json_encode(array('status' => 'error', 'msg' => 'Payment not Successfully charged !')); 
            }

        }
   }
   
   
   function updateUserStatus($idClient,$dbTalktime,$dbRechare,$orderId){
       
       
     include_once(CLASS_DIR."transaction_class.php");
     $tranxObj = new transaction_class();
    
     #get user detail
     $result = $this->selectData('balance,currencyId,resellerId', '91_userBalance',"userId=$idClient");
        
        #if result not found then set tracker
        if(!$result)
        {
            return json_encode(array("status"=>'error',"msg"=>'problem while get user balance'));
        }
         
        $getUserInfo=mysqli_fetch_array($result);
       
        #get details from array balance,currency id,reseller id
        $balance=$getUserInfo['balance'];
        $cid=$getUserInfo['currencyId'];
        $resellerId=$getUserInfo['resellerId'];
     
     
     
     
    //get closing balance
    $closingBalance = $tranxObj->getClosingBalance($idClient);

    $updatedFlag =  $tranxObj->updateUserBalance($idClient,$dbTalktime,'+');//update balance

    if($updatedFlag == 0)
    {
        if($this->sendErrorMail('sudhir@hostnsoft.com', 'Balance not updated while strip recharge  , Avialable details:userId:'.$idClient.' talktime:'.$dbTalktime))
        {
                //trigger_error('problem in mail:\nmailto');
        }

       return json_encode(array("status"=>'error',"msg"=>'Balance not updated!'));
    }
        //set fromUser and toUser
        $tranxObj->fromUser = $resellerId;

        $tranxObj->toUser = $idClient;

        $updatedClosingBal = (float)$closingBalance-(float)$dbRechare;
    //maintain transaction log
    $tranxObj->addTransactional_sub(0,$balance,'stripe',0,$dbRechare,$updatedClosingBal,'orderId:'.$orderId,$cid);


    $totalBal = $balance+$dbTalktime;
    $tranxObj->addTransactional_sub($dbTalktime,$totalBal,'voip',$dbRechare,0,$closingBalance,'orderId:'.$orderId,$cid);
    //$tranxObj->addTransactional($resellerId,$idClient,$dbRechare,$dbTalktime,'paypal',$txnId,'prepaid');//maintain transaction log    




    /**
        * @code to update payment status in confirmOrder table to one
        */
    //prepare update array
    $updateData = array('status' => 'done',
                        'rechargeTime' => date('Y-m-d H:i:s'));

    //code to update confirmorder table
    $this->updateData($updateData,'91_confirmOrder',"orderId ='".$orderId."'");


   return json_encode(array("status"=>'success',"msg"=>'balance updated',"updateBalance"=>$totalBal));

     
 }
    



}
?>
