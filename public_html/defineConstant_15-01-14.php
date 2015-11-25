<?php
/**
 * @author Ankit patidar <ankitpatidar@hostnsoft.com> on 23/11/2013
 * @since 23/11/2013
 * @copyright (c) 2013, Phone91
 * @version 1
 * Description it contains all regular expression constants 
 */

//for number
if(!defined('NOTNUM_REGX'))
    define('NOTNUM_REGX','/[^0-9]+/');
//for mobiole number
if(!defined('NOTMOBNUM_REGX'))
    define('NOTMOBNUM_REGX','/[^0-9]{7,18}+/');

//for username
if(!defined('NOTUSERNAME_REGX'))
    define('NOTUSERNAME_REGX','/[^a-zA-Z0-9\@\_\.]+/');

//for password
if(!defined('NOTPASSWORD_REGX'))
    define('NOTPASSWORD_REGX','/[^a-zA-Z0-9\@\$\}\{\.\_\-\!\(\)\]\[\:]+/');

//for email
if(!defined('EMAIL_REGX'))
    define('EMAIL_REGX','/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/i');

//for alphabtic
if(!defined('ALPHABATE_REGX'))
    define('ALPHABATE_REGX','/[A-Za-z\s]/');

//for not plan name
if(!defined('NOTPLANNAME_REGX'))
    define('NOTPLANNAME_REGX','/[^a-zA-Z0-9\@\_\-\s]+/');

//for not country name
if(!defined('NOTCOUNTRY_REGX'))
    define('NOTCOUNTRY_REGX','/[^a-zA-Z\-\_\s]+/');

define("MANDRILLKEY","UyYmryeHJCDreWdOvy7RSQ");
?>