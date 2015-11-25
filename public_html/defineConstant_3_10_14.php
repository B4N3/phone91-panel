<?php
/**
 * @author Ankit patidar <ankitpatidar@hostnsoft.com> on 23/11/2013
 * @since 23/11/2013
 * @copyright (c) 2013, Phone91
 * @version 1
 * Description it contains all regular expression constants 
 */

//for number
defined('NOTNUM_REGX') or define('NOTNUM_REGX','/[^0-9]+/');

defined('NOTPHNNUM_REGX') or define('NOTPHNNUM_REGX','/[^0-9]+/');
//defined('PHNNUM_REGX') or define('PHNNUM_REGX','/^[0-9]{7,18}$+/');

//if(!defined('NOTPHNNUMSPACE_REGX'))
//    define('NOTPHNNUMSPACE_REGX','/[^a-zA-Z0-9\@\s\.\_\-]+/');


defined('NOTALPHANUM_REGX') or define('NOTALPHANUM_REGX','/[^0-9a-zA-Z\.]+/');

//for mobiole number
defined('NOTMOBNUM_REGX') or define('NOTMOBNUM_REGX','/[^0-9]+/');

//for mobiole number
defined('DECIMAL_REGX') or define('DECIMAL_REGX','/^[0-9]+(\.[0-9]{1,3})?$/');

//for username
defined('NOTUSERNAME_REGX') or define('NOTUSERNAME_REGX','/[^a-zA-Z0-9\@\_\.]+/');

defined('NOTUSERNAME_EMAIL_REGX') or define('NOTUSERNAME_EMAIL_REGX','/[^a-zA-Z0-9\_]+/');

defined('EMAIL_REGX') or define('EMAIL_REGX','/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix');

//for username
defined('NOTUSERNAME_NORMAL_REGX') or define('NOTUSERNAME_NORMAL_REGX','/[^a-zA-Z0-9\_]+/');


//for password
defined('NOTPASSWORD_REGX') or define('NOTPASSWORD_REGX','/[^a-zA-Z0-9\@\$\}\{\.\_\-\(\)\]\[\:]+/');

//for email
defined('EMAIL_REGX') or define('EMAIL_REGX','/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/i');

defined('URL_REGX') or define('URL_REGX',"/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z0-9]{2,3}(\/\S*)?/");
//    define('URL_REGX',"/(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/");

//for alphabtic
defined('NOTALPHABATE_REGX') or define('NOTALPHABATE_REGX','/[^A-Za-z]/');

defined('NOTALPHABATESPACE_REGX') or define('NOTALPHABATESPACE_REGX','/[^A-Za-z\s]/');

defined('NOTALPHANUMSPACE_REGX') or define('NOTALPHANUMSPACE_REGX','/[^A-Za-z0-9\s]/');

defined('NOTALPHABATECOMMA_REGX') or define('NOTALPHABATECOMMA_REGX','/[^A-Za-z\,\s]/');

defined('NOTALPHANUMCOMMA_REGX') or define('NOTALPHANUMCOMMA_REGX','/[^A-Za-z0-9\,\s]/');

//for not plan name
defined('NOTPLANNAME_REGX') or define('NOTPLANNAME_REGX','/[^a-zA-Z0-9\@\_\-\s]+/');

//for not country name
defined('NOTCOUNTRY_REGX') or define('NOTCOUNTRY_REGX','/[^a-zA-Z\-\_\s]+/');

defined('NOTTEXT_REGX') or define('NOTTEXT_REGX','/[^a-zA-Z0-9\-\_\s\/\-\_\@\.\:\,\!\%\$\&\(\)\+]+/');

defined('IP_ADDRESS') or define('IP_ADDRESS','/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/');

defined('PHNNUM_REGX') or define('PHNNUM_REGX','/^[0-9]{8,18}+$/');




defined('LOCALHOST') or define("LOCALHOST",'192.168.1.191'); 









?>