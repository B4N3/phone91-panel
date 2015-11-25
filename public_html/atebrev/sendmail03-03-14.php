<?php
//include requiured files
//include_once 'config.php';
  

  
  class MailAndErrorHandler
  {
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
       function sendmail_mandrill($mailTo, $subject, $message, $from_mail='',$fromName='',$toBcc='',$attachFile=array(),$path='')
        {

              if($from_mail == '')
                $from_mail = "Noreply@".(($_SERVER['HTTP_HOST']!='')?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME']).".com";
            //apply error handling
            try
            {
                   
                    //call function to mail
                    $ret= $this->sendViaMandrill($mailTo, $subject, $message, $from_mail,$fromName,$toBcc,$attachFile,$path);
                    trigger_error('mail response'.  json_encode($ret));
                    //check mail status
                    if( $ret[0]->status =="rejected" || $ret[0]->status=="invalid" || $ret[0]->status=="error")
                    {
                       //maintain error log
                       trigger_error(print_r((array)$ret[0],1).' msg templete'.$message);
                       $resp= $this->sendViaMandrill(array("AnkitPatidar@hostnsoft.com"), $subject,print_r((array)$ret[0],1).'\nmsg templete'.$message, $from_mail,$fromName);
                       
                       //che response status
                       if( $ret[0]->status =="rejected" || $ret[0]->status=="invalid" || $ret[0]->status=="error")
                        {
                           //log error
                           trigger_error(print_r((array)$resp[0],1).'msg templete'.$message);
                        }
                       return false;
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
     function sendViaMandrill($mailTo, $subject, $message, $from_mail='',$fromName="Phone91",$toBcc='',$attachFile=array(),$path='') 
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
