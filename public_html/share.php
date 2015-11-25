<?php
include_once ("classes/transaction_class.php");
if(!$funobj->login_validate())
{
    $funobj->redirect("/index.php");
}
    
$userId = $_SESSION['id'];
require_once('login/fb/facebook.php');

  $config = array(
    'appId' => '234936599964806',
    'secret' => '35d22a043a34d6ee742128b204e83892'
  );

$facebook = new Facebook($config);
$user_id = $facebook->getUser();

function updateUserBalanceFacebook($postId,$userId)
{
   $funobj = new fun();
   #select the data from the confirm order table having post and flag set as 0 and the status of the transaction should be completed
   $result = $funobj->selectData("*","91_confirmOrder","clientId=".$userId." and postFlag=0 and status='done' order by rechargeTime desc limit 1");
   
   if($result)
   {
     $row = $result->fetch_array(MYSQLI_ASSOC);
     if($result->num_rows > 0 && $row['postId'] == 0 && $row['postFlag'] != 1)
     {
        $talktime = $row['talktime'];
        $Id = $row['id'];
        $newTalktime = $talktime*0.1;

        $dataId = array("postId" => $postId,"postFlag"=>1);
        $res = $funobj->updateData($dataId,"91_confirmOrder","id = ".$Id."");
        if($res)
        {
            $userId = $funobj->db->real_escape_string($userId);
            
            //create transaction class object
            $tranxObj = new transaction_class();
            
            $sql = "UPDATE 91_userBalance set balance = (balance+".$newTalktime.") where userId = ".$userId."";
            $resUpd = $funobj->db->query($sql);
            
            if($resUpd)
            {
                //get current balance
                $resId = $funobj->getResellerId($userId);
                $balance = $tranxObj->getcurrentbalance($userId);
                
                $tranxObj->fromUser = $resId;
                $tranxObj->toUser = $userId;
                $msg = $tranxObj->addTransactional_sub($newTalktime,$balance,"Fb share",0,0,0,"Fb Share Transaction",0);
                unset($tranxObj); //free object space
                echo "Congratulation you got extra 10% on your last recharge";
            }
            else
            {
                $tranxObj->sendErrorMail('Ankitpatidar@hostnsoft.com','Problem in balance update while fb share!!!,userId:'.$userId.' talktime:'.$newTalktime.' SQL:'.$sql);
                echo 'Problem while recharge';
            }
            //$sql = "UPDATE 91_userBalance set balance = (balance+".$newTalktime.") where userId = ".$userId."";
            //$resUpd = $funobj->db->query($sql);
//            if($resUpd)
//                echo "Congratulation you got extra 10% on your last recharge";
//            else
//                echo "Sorry unable to process";
        }
        else
            echo "Sorry unable to process";
            //increment the balance with 10 percent 
            //and update the post id and flag 
        }
    }
    else
    {
            echo "Sorry unable to process";
//        echo $funobj->errorHandler;
    }
    
}
?>
<html>
  <head></head>
  <body>

  <?php
    if($user_id) {

      // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {
        $ret_obj = $facebook->api('/me/feed', 'POST',
                    array(
                        'link' => 'www.phone91.com',
                        'message' => 'voip calling this user got 10 percent of extra talk time!'
                    ));
        $postId = $ret_obj['id'];
//        echo '<pre>Post ID: ' . $ret_obj['id'] . '</pre>';
        

        #destroy the facebook session 
//        $facebook->destroySession();
        
        #initiate user session again to save the data 

        if($userId == "" || $userId == NULL)
        {
            header("Location:http;//".$_SERVER['HTTP_HOST']."/index.php");
            exit();
        }
        #function to update the status and balance of the user 
        updateUserBalanceFacebook($postId,$userId);

      } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
        $login_url = $facebook->getLoginUrl( array(
                       'scope' => 'email,offline_access,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown',
//            'redirect_uri' => "http://voip91.com/"
                       )); 
        echo 'Please <a href="' . $login_url . '">login.</a>';
        error_log($e->getType());
        error_log($e->getMessage());
      }   
    }
    else {

      // No user, so print a link for the user to login
      // To post to a user's wall, we need publish_stream permission
      // We'll use the current URL as the redirect_uri, so we don't
      // need to specify it here.
      $login_url = $facebook->getLoginUrl( array( 'scope'=> 'email,offline_access,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown',) );
      echo 'Please <a href="' . $login_url . '">login.</a>';

    }

  ?>      

  </body> 
</html> 