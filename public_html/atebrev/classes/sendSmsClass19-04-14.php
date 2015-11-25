<?php

/**
 * @Author nidhi <nidhi@wlakover.in>
 * @createdDate 12-07-13
 * 
 */
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."/db_class.php");

class sendSmsClass  extends fun
{
     /*
     * @author nidhi<nidhi@walkover.in>
     * This function is used to send messages. 
     * steps.
     * 1. check number
     * 2. if starts from 91 - send via msg91
     * 3. if starts from 1 send via us to us api
     * 4. if starts from any other number - send via international api of clickatell
     * 
      * required parameters -
      * $param['to'], $param['text'],$param['senderId']
      * 
     */
    function sendMessagesGlobal($param)
    {  
      /////  print_r($param);
        
        if( $param['to'] == $param['senderId'] )
        {
             logmonitor("SMS-PHONE91", json_encode($param)."  0 <b> response is</b> NO RESPONSE because same numbers.");
            return 0; 
        }
       
        if($param['to'] == '12028038240' )
        {
            logmonitor("SMS-PHONE91", json_encode($param)."  Z <b> response is</b> NO RESPONSE because same numbers.");
            return 0; 
        }
        
        if (substr($param['to'],0,2) == "91")
        {
            $param['sender'] = "Phonee";
            $param['mobiles'] = $param['to'];
            $param['message'] =  $param['text'];
            
            $response = $this->SendSMS91($param);
            
            $sendVia = 1;
        }
        else if (substr($param['to'],0,1) == "1")
        {
            switch($param['senderId'])
            {
                case '12028038240':
                        $param['password'] = "AWIKSeLIcPFHBS";
                        $param['apiId'] = "3468158";
                        $param['userName'] = "phone91";
                        
                        $sendVia = 4;
                   $response = $this->SendSmsToUs($param); 
                        
                    break;
                
                default:
                    $param['password'] = "AWIKSeLIcPFHBS";
                    $param['apiId'] = "3468158";
                    $param['userName'] = "phone91";
                    $param['senderId'] = '12028038240';
                    $sendVia = 2;
                   $response =  $this->SendSmsToUs($param); 
            }
            
            
        }
        else
        {
           $response =  $this->SendSmsInternational($param);
             $sendVia = 3;
        }
      
       logmonitor("SMS-PHONE91", json_encode($param).' // '.$sendVia." <b> response is</b> ".json_encode($response));
        
    }
    
    /**
     * @author nidhi <nidhi@walkover.in>
     * @uses function use to send sms to us 
     * @param array $tempparam
     * @return array
     */
    function SendSmsToUs($param) 
    {
        //prepare details for curl
        $param["to"] = $param['to'];
        $param["text"] = urlencode($param['text']);
        
        $connect_url = "https://api.clickatell.com/http/sendmsg?user=phone91&password=".$param['password']."&api_id=".$param['apiId']."&to=".$param["to"]."&text=".$param["text"]."&concat=3&from=".$param["senderId"]."&mo=1"; 
        
        trigger_error($connect_url);
        $ch = curl_init($connect_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        trigger_error($curl_scraped_page);
        return $curl_scraped_page;
    }
    
    /**
     * @author nidhi <nidhi@walkover.in>
     * @uses function use to send sms to us 
     * @param array $tempparam
     * @return array
     */
    function SendSmsInternational($param) 
    {
        $param["to"] = $param['to'];
        $param["text"] = urlencode($param['text']);
        
        $param['password'] = "@%@847Hg%U";
        $param['apiId'] = "3451976";
        
        $connect_url = "https://api.clickatell.com/http/sendmsg?user=phone91&password=".$param['password']."&api_id=".$param['apiId']."&to=".$param["to"]."&text=".$param["text"]; // 
        
        trigger_error($connect_url);
        $ch = curl_init($connect_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        trigger_error($curl_scraped_page);
        return $curl_scraped_page;
    }
    
    
}




?>