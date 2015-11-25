<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('ses.php');

//ses-smtp-user.20130731-114438
class awsSesMail{
    function mailAwsSes($to, $subject, $message, $from){
          /* @author Rahul
             * 31July2013
             * @param string $to  
             * @param string $subject  
             * @param string $message
             * @param string $from
             * @return array combination of response  //Array ( [MessageId] => 00000140336b9ee0-527103ed-ab21-46bd-a20f-38afc0415b5c-000000 [RequestId] => af6da0c3-f9aa-11e2-91f7-abec0bf7c19b )
             */
            //Include Mandrill file here      
            $ses = new SimpleEmailService('AKIAI7YYMQL3R2IKX4JQ', 'zLBY/L31vzfjnwk5TJ1y7tDgCQs08aC03pDVd5UB');
            $m = new SimpleEmailServiceMessage();
            print_r($ses->verifyEmailAddress($from));
            print_r($ses->listVerifiedEmailAddresses());
            $m->addTo($to);
            $m->setFrom($from);
            $m->setSubject($subject);
            $m->setMessageFromString($message,$message);
    //        $m->setMessageFromString($text, $html);

            return ($ses->sendEmail($m));         
    }

}
?>
