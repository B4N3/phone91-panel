<?php
 /**
 * Error Talk Class
 *
 * Give The Ability To Manage All Application Error Messages.
 *
 * @package             Utilities and Tools
 * @subpackage          Libraries
 * @category            Libraries
 * @author		Rahul Chordiya - WEB DEVELOPER <rahul@hostnsoft.com>
 * @version             1.0
 * @link		http://vtermination.com/
 */

 /**
  * all configuration  needs to be edit found at initialize function
  */
class errorTalk {

   public static $conf = array();

   public static function initialize()
   {
      //if E_STRICT is not defined, define it
      if(!defined('E_STRICT')) define('E_STRICT', 0);

      /*************************************************************************
       *
       * Send Errors to Specified Email Address ?
       * 
       * @var boolean
       *
       *************************************************************************
      */
      self::$conf['emailActive'] = FALSE;
      /*************************************************************************
       *
       * print error to browser
       * 
       * @var boolean
       *
       *************************************************************************
      */
      self::$conf['showErrorToBrowser'] = FALSE;
      /*************************************************************************
       *
       * Throwing errors in a specific file
       *
       * @var boolean
       *
       *************************************************************************
      */
      self::$conf['logFile'] = TRUE;
      /*************************************************************************
       *
       * File Path Errors
       * 
       * @var string
       *
       *************************************************************************
      */
      self::$conf['logFilePath'] = dirname(dirname(__FILE__))."/error_reporting/errorTalkLogFile".date('d-m-Y').".txt";
      /*************************************************************************
       *
       * E-mail that you want to tell him mistakes
       *
       * Note that this fueature will disable if self::$conf['Email'] is False
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf['Email'] = "rahul@hostnsoft.com";
      
      /*************************************************************************
       *
       * Webmaster email
       *
       * Note that this fueature will disable if self::$conf['Email'] is False
       * 
       * @var string
       *
       *************************************************************************
      */
      self::$conf['webmasterEmail'] = 'rahul@hostnsoft.com';
      /*************************************************************************
       *
       * Email header
       *
       * Note that this fueature will disable if self::$conf['Email'] is False
       * 
       * @var string
       *
       *************************************************************************
      */
      self::$conf['emailHeader'] = 'From : errorTalk ';
      /*************************************************************************
       *
       * Error Handler
       *
       * @var array
       *
       *************************************************************************
      */
      self::$conf['handlerName'] = array("errorTalk","errorHandler");
      /*************************************************************************
       *
       * E_COMPILE_ERROR default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_COMPILE_ERROR] = "Fatal error";
      /*************************************************************************
       *
       * E_COMPILE_WARNING default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_COMPILE_WARNING] = "Warning";
      /*************************************************************************
       *
       * E_CORE_ERROR default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_CORE_ERROR] = "Fatal error";
      /*************************************************************************
       *
       * E_CORE_WARNING default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_CORE_WARNING] = "Warnings";
      /*************************************************************************
       *
       * E_DEPRECATED default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_DEPRECATED] = "Warnings";
      /*************************************************************************
       *
       * E_ERROR default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_ERROR] = "Fatal Error";
      /*************************************************************************
       *
       * E_PARSE default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_PARSE] = "Fatal Error";
      /*************************************************************************
       *
       * E_RECOVERABLE_ERROR default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_RECOVERABLE_ERROR] = "Fatal Error";
      /*************************************************************************
       *
       * E_STRICT default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_STRICT] = "Fatal Error";
      /*************************************************************************
       *
       * E_WARNING default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_WARNING] = "Fatal Error";
      /*************************************************************************
       *
       * E_USER_ERROR default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_USER_ERROR] = "Fatal Error";
      /*************************************************************************
       *
       * E_USER_NOTICE default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_USER_NOTICE] = "Notice";
      /*************************************************************************
       *
       * E_USER_WARNING default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_USER_WARNING] = "Warning";
      /*************************************************************************
       *
       * E_USER_DEPRECATED default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_USER_DEPRECATED] = "Warning";
      /*************************************************************************
       *
       * E_DEPRECATED default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_ALL] = "Fatal Error";
      /*************************************************************************
       *
       * E_NOTICE default message
       *
       * @var string
       *
       *************************************************************************
      */
      self::$conf[E_NOTICE] = "Notice";
      /*************************************************************************
       *
       * Error Level
       *
       * @var int
       *
       *************************************************************************
      */
      self::$conf['errorLevel'] = E_ALL | E_STRICT;
      
      /**
       * E_CUSTOM_ERROR
       * @var string
       */
      self::$conf['CUSTOM_ERROR'] = 'Fatal Error';
   }
   /*
    ****************************************************************************
    * @access            public
    * @param             int
    * @param             string
    * @param             string
    * @param             int
    * @param             array
    * @return            string
    ****************************************************************************
   */
   public static function errorHandler($errLevel,$errMessage,$errFile,$errLine,$errContext)
   {
       self::outputHandler($errLevel, $errMessage, $errFile, $errLine, $errContext);
   }
   /*
    ****************************************************************************
    * @access            public
    * @param             int
    * @param             string
    * @param             string
    * @param             int
    * @param             array
    * @return            string
    ****************************************************************************
   */
   private static function outputHandler($errLevel,$errMessage,$errFile,$errLine,$errContext)
   {
       //get time and zone
       $date = date('d-M-Y H:i:s')." ".date_default_timezone_get();//get Time
       
       //prepare error msg to write
       $alertMsg = '['.$date.']';
       $alertMsg .= " PHP ".self::getErrorLevel($errLevel).":  $errMessage in ";
       $alertMsg .= " $errFile";
       $alertMsg .= " on line $errLine%br%";
      
       $output = str_replace ("%br%", "<br>", $alertMsg); // write <br> tag to break line the lines with browsers

       if(self::$conf['showErrorToBrowser'])
          echo $output;

       $alertMsg = str_replace ("%br%", "\r\n", $alertMsg); // write /r/n to break line with E-mail & Error File

       if(self::$conf['emailActive'])
          self::LogErrorToMail(strip_tags($alertMsg));

       if(self::$conf['logFile'])
          self::LogErrorToFile(strip_tags($alertMsg));
   }
   /*
    ****************************************************************************
    * @access            private
    * @param             string
    * @return            boolean
    ****************************************************************************
   */
   private static function LogErrorToMail($alertMsg)
   {
       @error_log($alertMsg, 1, self::$conf['Email'], self::$conf['emailHeader'].self::$conf['webmasterEmail']);
   }
   /*
    ****************************************************************************
    * @access            private
    * @param             string
    * @return            boolean
    ****************************************************************************
   */
   private static function LogErrorToFile($alertMsg)
   {
       error_log($alertMsg, 3, self::$conf['logFilePath']);
   }
   /*
    ****************************************************************************
    * @access            private
    * @param             int
    * @return            string
    ****************************************************************************
   */
   private static function getErrorLevel($errLevel)
   {
       switch ($errLevel)
       {
           case E_USER_ERROR :
               return self::$conf[E_USER_ERROR];
               break;
           case E_USER_NOTICE :
               return self::$conf[E_USER_NOTICE];
               break;
           case E_USER_WARNING :
               return self::$conf[E_USER_WARNING];
               break;
           case E_USER_DEPRECATED :
               return self::$conf[E_USER_DEPRECATED];
               break;
           case E_COMPILE_ERROR :
               return self::$conf[E_COMPILE_ERROR];
               break;
           case E_COMPILE_WARNING :
               return self::$conf[E_COMPILE_WARNING];
               break;
           case E_CORE_ERROR :
               return self::$conf[E_CORE_ERROR];
               break;
           case E_CORE_WARNING :
               return self::$conf[E_CORE_WARNING];
               break;
           case E_DEPRECATED :
               return self::$conf[E_DEPRECATED];
               break;
           case E_ERROR :
               return self::$conf[E_ERROR];
               break;
           case E_PARSE :
               return self::$conf[E_PARSE];
               break;
           case E_RECOVERABLE_ERROR :
               return self::$conf[E_RECOVERABLE_ERROR];
               break;
           case E_STRICT :
               return self::$conf[E_STRICT];
               break;
           case E_ALL :
               return self::$conf[E_ALL];
               break;
           case E_NOTICE :
               return self::$conf[E_NOTICE];
               break;
           case E_WARNING :
               return self::$conf[E_WARNING];
               break;
           case 'CUSTOM_ERROR':
               return self::$conf['CUSTOM_ERROR'];
       }
   }
   /*
    ****************************************************************************
    *
    * start use errortalk functionality
    *
    * set the error handler , and specify the error level
    *
    * @access            public
    * @return            void
    ****************************************************************************
   */
   public static function errorTalk_Open()
   {
       set_error_handler(errorTalk::$conf['handlerName'],  errorTalk::$conf['errorLevel']);
   }
   /*
    ****************************************************************************
    *
    * stop use errortalk functionality
    *
    * hide all system error
    *
    * @access            public
    * @return            void
    ****************************************************************************
   */
   public static function errorTalk_Close()
   {
       set_error_handler(errorTalk::$conf['handlerName'],  FALSE);
       error_reporting(FALSE);
   }
   
  
   
   

}
?>