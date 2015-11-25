<?php
//include requiured files
//include_once 'config.php';
  

  
  class MailAndErrorHandler
  {
      
      
       function __construct()
      {
          //set default mails
          $this->defEmail1 = 'AnkitPatidar@hostnsoft.com';
          $this->defEmail2 = 'sudhir@hostnsoft.com';
      }
     /**
      * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
      * @param array $mailTo
      * @param string $subject
      * @param msg $message
      * @param string $from_mail
      * @param string $fromName
      * @param string $toBcc
      * @param array $attachFile
      * @param sting $path
      * @return boolean
      */
       function sendmail_mandrill($mailTo, $subject, $message, $from_mail='',$fromName='',$toBcc='',$attachFile=array(),$path='',$bulkMail=0)
        {

              if($from_mail == '')
                $from_mail = "Noreply@".(($_SERVER['HTTP_HOST']!='')?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME']).".com";
            //apply error handling
            try
            {
                   
                  //call function to mail
                    $ret= $this->sendViaMandrill($mailTo, $subject, $message,$from_mail,$fromName,$toBcc,$attachFile,$path,$bulkMail);
                    
                    //check mail status
                    if(is_array($ret) and is_object($ret[0])) //check array and object
                    {
                        if( $ret[0]->status =="rejected" || $ret[0]->status=="invalid" || $ret[0]->status=='error')
                        {
                            //if mail not sent then 
                            $mailArr=array($this->defEmail1,$this->defEmail2);
                            $sub = 'error in sending mail';
                            $from ="Noreply@".(($_SERVER['HTTP_HOST']!='')?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME']).".com";
                            $msg =  'Reason for mail failure:'.print_r((array)$ret,1).'mailArr:'.print_r($mailTo,1).'<br/>sub:'.$subject.'<br/>msg:'.$message.'<br/>frommail:'.$from_mail.'<br/>fromname:'.$fromName.'<br/>bcc:'.$toBcc.'<br/>attach file:'.print_r($attachFile,1).'<br/>path:'.$path;
                            $resp= $this->sendViaMandrill($mailArr, $sub, $msg,$from);
                            //check status if not sent then maintain error log
                            if( $resp[0]->status =="rejected" || $resp[0]->status=="invalid" || $resp[0]->status=='error')
                            {
                                trigger_error(print_r((array)$resp,1).'msg'.$msg);
                            }
                            //maintain error log
                            trigger_error(print_r((array)$ret,1).'msg'.$msg);
                           return false;
                        }
                        
                    }
                    elseif(is_object($ret)) //in case of error mandrill send std class error object
                    {
                        //log error
                        trigger_error('problem while mail,error:'.$ret->status.' name:'.$ret->name.' msg:'.$ret->message);
                        mail($this->defEmail1,'problem while mail in lead',print_r((array)$ret,1)); //mail in error case
                        mail($this->defEmail2,'problem while mail in lead',print_r((array)$ret,1)); //mail in error case
                    }
                    return true;
             }
            catch(Exception $e)
            {
                //maintain error log
                 trigger_error(print_r((array)$e,1).'\nmsg templete'.$message);
                  return false;
             }
        }//end of function sendmail_mandrill()
     
     /**
      * @addedBy:Ankit Patidar <ankitpatidar@hostnsoft.com> on 1/8/2013
      * @param Array $mailTo (it includes cc)
      * @param string $subject
      * @param string(html) $msg
      * @param string $from_mail
      * @param string $fromName
      * @param Array $attachFile
      * @return Array
      */   
     function sendViaMandrill($mailTo, $subject, $message, $from_mail='',$fromName="Phone91",$toBcc='',$attachFile=array(),$path='',$bulkMail=0) 
      {        
         //if attach file set
          if(!empty($attachFile))
              {
               // $allfile = explode(",",$attachFile);
                
                foreach($attachFile as $files)
                {
                    $file = $path.$files;
                    $file_size = filesize($file);
                    $handle = fopen($file, "r");
                    $content = fread($handle, $file_size);
                    $content = chunk_split(base64_encode($content));
                    
                    //make attachments
                    $attachment = array('type' =>'text/plain','name' =>$files,'content' => $content);
                    $req["attachments"][]=  $attachment;
                    
                    fclose($handle);
                }
              } 
              else $content='';
              
              
          $content = chunk_split(base64_encode($content));
         
           $message= stripslashes(html_entity_decode(($message)));
$message_complte = <<<EOF
$message
EOF;
          //add bcc if set
        if(isset($toBcc) and $toBcc != "")
            $req["bcc_address"] = $toBcc;
        $include= get_included_files();

        if(!preg_grep('/Mandrill.php/',$include))
        require('Mandrill.php');
        //set key
        Mandrill::setApiKey(MANDRILLKEY);
        //set parameters
        $request_json["type"] = "messages";
        $request_json["call"] = "send";
        $req["html"] = $message_complte;
        $req["subject"] = $subject;
        if(isset($from_mail) and $from_mail != '')
            $req["from_email"] = $from_mail;
        
        $req["from_name"] = $fromName;
        //if attachment then set
//        if(!empty($attachFile))
//        {
//            $attachment = array('type' =>'text/plain','name' =>$files,'content' => $content);
//            $req["attachment"]=  $attachment;
//        }
        
        foreach ($mailTo as $key => $email) 
        {
            $resTo["email"] = $email;
            if($bulkMail == 1)
            $resTo["type"] = "bcc";
            $req["to"][] = $resTo;
        }
        $req["track_opens"] = "true";
        $req["track_clicks"] = "true";
        $req["auto_text"] = "true";
        $req["url_strip_qs"] = "true";
 	//$req["attachments"] = $attachFile;
        $request_json["message"] = $req;
        $final = json_encode($request_json);
        //call function for mail
        $ret = Mandrill::call((array) json_decode($final));
        return $ret;
    }//end of function sendViaMandrill
        
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @param string $errormsg contains error msg
     */
        function errorHandler($errormsg)
        {
          //append required details to error msg
            $msg= '##################################START OF ERROR '.  gmdate('d/M/Y H:i:s').'################################################';
            $errormsg = $msg.$errormsg;
            //get file name day wise
            $fileName = 'errorFounded_'.gmdate('d_M_Y').'.txt';
            
            //if file not exists then create with permission
            if (!file_exists($fileName))
            {
                 $fileHandle = fopen($fileName, "w+");
                  fclose($fileHandle);
            }
            
            //call  function to append error to  erro log file
            file_put_contents($fileName,$errormsg,FILE_APPEND);
        }//end of error handler function
        
  }
  //'\nuser mail:'.$_SESSION['user_email'].'\ncompid:'.$_SESSION['comp_id'].'\nbacktrace:'.print_r(debug_backtrace(),1);
