<?php

//include_once (dirname(dirname(__FILE__)) ."classes/transaction_class.php");


//$funObj = new fun();
$token  = $_POST['id'];
var_dump($token);
die('hit');
$orderId = $_POST['orderId'];
 trigger_error('sudhir11');
#get payment detail by orderId
$rsOrder = $funObj->selectData('*', '91_confirmOrder',"orderId='$orderId'");
if(!$rsOrder)
    {
       die('problem while get order detail'); 
       trigger_error('sudhir12');
    } //end of result validate
//get detail array
    $row = mysqli_fetch_assoc($rsOrder);

    //get order details from array
    $userId = $row['clientId'];
    $recharge = $row['recharge'];
    $amount =(int)$row['recharge'] * 100;
    //$dbRechare = $row['recharge'];
    $dbIp = $row['ip'];

    $description = $userId." : ".$orderId;
    trigger_error('sudhir13');
try
{
    trigger_error('sudhir14');
      $charge = Stripe_Charge::create(array(
         "amount" => $amount, // amount in cents, again
         "currency" => "usd",
         "card" => $token,
         "description" => $description
      ));
      trigger_error('sudhir15');
      $dbTalktime = 12;
      if($charge->paid == true){
          trigger_error('sudhir16');
       $res = updateUserStatus($userId,$dbTalktime,$recharge,$orderId,'147');   
       trigger_error('sudhir17');
       if($res == 1){
           trigger_error('sudhir18');
       echo '<h1>Successfully charged $'.$recharge.'!</h1>';
       }else
        echo '<h1>Payment not Successfully charged.</h1>'; 
      }else
      echo '<h1>Payment not Successfully charged!</h1>'; 
}
catch(Exception $e)
{
//$error = $e->getMessage();
     echo '<h1>Payment not Successfully charged !</h1>'; 
  //var_dump($e);
  
}

 echo '<a class="btn btn-medium btn-primary" href="http://testing.phone91.com/userhome.php#!setting.php|buymore.php" >Back</a>';

 
 
 
 
 function updateUserStatus($idClient,$dbTalktime,$dbRechare,$orderId,$currencyId){
     
     $funObj = new fun();
     $tranxObj = new transaction_class();
     
     #get user detail
     $result = $funObj->selectData('balance,currencyId,resellerId', '91_userBalance',"userId=$idClient");
        
        //if result not found then set tracker
        if(!$result)
        {
            //set tracker msg
            $trackMsg = "problem while get user balance";
            
            trigger_error($trackMsg.'track Detail:');
            
            die($trackMsg);//stop the srcipt execution
             
        }//end of result validation
         
        //get detail array
        $getUserInfo=mysqli_fetch_array($result);
       
        //get details from array balance,currency id,reseller id
        $balance=$getUserInfo['balance'];
        $cid=$getUserInfo['currencyId'];
        $resellerId=$getUserInfo['resellerId'];
     
     
     
     
    //get closing balance
    $closingBalance = $tranxObj->getClosingBalance($idClient);

    $updatedFlag =  $tranxObj->updateUserBalance($idClient,$dbTalktime,'+');//update balance

    if($updatedFlag == 0)
    {
        trigger_error('Balance not updated while paypal recharge Avialable details:userId:'.$idClient.' talktime:'.$dbTalktime);
        //mail response
        if($funObj->sendErrorMail('sudhir@hostnsoft.com', 'Balance not updated while strip recharge  , Avialable details:userId:'.$idClient.' talktime:'.$dbTalktime))
        {
                trigger_error('problem in mail:\nmailto');
        }

        die('Balance not updated'); //stop execution
    }
        //set fromUser and toUser
        $tranxObj->fromUser = $resellerId;

        $tranxObj->toUser = $idClient;

        $updatedClosingBal = (float)$closingBalance-(float)$dbRechare;
    //maintain transaction log
    $tranxObj->addTransactional_sub(0,$balance,'stripe',0,$dbRechare,$updatedClosingBal,'orderId:'.$orderId,$currencyId);


    $totalBal = $balance+$dbTalktime;
    $tranxObj->addTransactional_sub($dbTalktime,$totalBal,'voip',$dbRechare,0,$closingBalance,'orderId:'.$orderId,$currencyId);
    //$tranxObj->addTransactional($resellerId,$idClient,$dbRechare,$dbTalktime,'paypal',$txnId,'prepaid');//maintain transaction log    




    /**
        * @code to update payment status in confirmOrder table to one
        */
    //prepare update array
    $updateData = array('status' => 'done',
                        'rechargeTime' => date('Y-m-d H:i:s'));

    //code to update confirmorder table
    $funObj->updateData($updateData,'91_confirmOrder',"orderId ='".$orderId."'");


    return 1;

     
 }
 
?>