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

if(!defined('NOTPHNNUM_REGX'))
    define('NOTPHNNUM_REGX','/[^0-9]{7,18}+/');

//if(!defined('NOTPHNNUMSPACE_REGX'))
//    define('NOTPHNNUMSPACE_REGX','/[^a-zA-Z0-9\@\s\.\_\-]+/');


if(!defined('NOTALPHANUM_REGX'))
    define('NOTALPHANUM_REGX','/[^0-9a-zA-Z]+/');
//for mobiole number
if(!defined('NOTMOBNUM_REGX'))
    define('NOTMOBNUM_REGX','/[^0-9]{7,18}+/');

//for username
if(!defined('NOTUSERNAME_REGX'))
    define('NOTUSERNAME_REGX','/[^a-zA-Z0-9\@\_\.]+/');

if(!defined('NOTUSERNAME_EMAIL_REGX'))
    define('NOTUSERNAME_EMAIL_REGX','/[^a-zA-Z0-9\_]+/');

if(!defined('EMAIL_REGX'))
    define('EMAIL_REGX','/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix');

//for username
if(!defined('NOTUSERNAME_NORMAL_REGX'))
    define('NOTUSERNAME_NORMAL_REGX','/[^a-zA-Z0-9\_]+/');


//for password
if(!defined('NOTPASSWORD_REGX'))
    define('NOTPASSWORD_REGX','/[^a-zA-Z0-9\@\$\}\{\.\_\-\(\)\]\[\:]+/');

//for email
if(!defined('EMAIL_REGX'))
    define('EMAIL_REGX','/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/i');

if(!defined('URL_REGX'))
    define('URL_REGX',"/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/");
//    define('URL_REGX',"/(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/");

//for alphabtic
if(!defined('NOTALPHABATE_REGX'))
    define('NOTALPHABATE_REGX','/[^A-Za-z]/');

if(!defined('NOTALPHABATESPACE_REGX'))
    define('NOTALPHABATESPACE_REGX','/[^A-Za-z\s]/');

if(!defined('NOTALPHANUMSPACE_REGX'))
    define('NOTALPHANUMSPACE_REGX','/[^A-Za-z0-9\s]/');

if(!defined('NOTALPHABATECOMMA_REGX'))
    define('NOTALPHABATECOMMA_REGX','/[^A-Za-z\,\s]/');

if(!defined('NOTALPHANUMCOMMA_REGX'))
    define('NOTALPHANUMCOMMA_REGX','/[^A-Za-z0-9\,\s]/');

//for not plan name
if(!defined('NOTPLANNAME_REGX'))
    define('NOTPLANNAME_REGX','/[^a-zA-Z0-9\@\_\-\s]+/');

//for not country name
if(!defined('NOTCOUNTRY_REGX'))
    define('NOTCOUNTRY_REGX','/[^a-zA-Z\-\_\s]+/');

if(!defined('NOTTEXT_REGX'))
    define('NOTTEXT_REGX','/[^a-zA-Z0-9\-\_\s\/\-\_\@\.\:\,\!\%\$\&\(\)\+]+/');

if(!defined('IP_ADDRESS'))
    define('IP_ADDRESS','/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/');

if(!defined('PHNNUM_REGX'))
    define('PHNNUM_REGX','/^[0-9]{8,18}+/');

define("MANDRILLKEY","UyYmryeHJCDreWdOvy7RSQ");
?>