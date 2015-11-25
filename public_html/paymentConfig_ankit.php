<?
/**
 * @author Ankit Patidar <AnkitPatidar@hostnsoft.com> on 25/10/2013
 * @file contains the details(constants) for payment gateway configuration
 * 
 */

//set default emails
define("DATEFORMAT","d-m-Y H:i:s");//set default date format

//payment@walkover.in
define("BUSINESSMAIL","ankkuboss2@gmail.com");//set business mail

define("DEFEMAIL1","Ankitpatidar@hostnsoft.com");//set default email

define("DEFEMAIL2","shubhendra@hostnsoft.com");//set default email

define("DEFEMAIL3","shubh124421@gmail.com");//set default email

//define("PAYPALURL","https://www.paypal.com/cgi-bin/webscr");//set default URL for paypal,https://www.sandbox.paypal.com/cgi-bin/webscr
//https://www.paypal.com/cgi-bin/webscr
define("PAYPALURL","https://www.sandbox.paypal.com/cgi-bin/webscr");


define("CASHUURL","https://sandbox.cashu.com/cgi-bin/pcashu.cgi");//set default URl for cashu,https://sandbox.cashu.com/cgi-bin/pcashu.cgi
//https://www.cashu.com/cgi-bin/pcashu.cgi

//define("CASHUURL","https://www.cashu.com/cgi-bin/pcashu.cgi");

//set cashu merchant id
//define("CASHUMERCHANTID","phonee"); //ankkuboss,phonee

define("CASHUMERCHANTID","ankkuboss"); //ankkuboss,phonee

//set cashu keywaord
//define("CASHUKEYWORD","voip"); //beyond,voip

define("CASHUKEYWORD","beyond"); //beyond,voip

//set paypal merchant id
define("PAYPALMERCHANTID","phonee"); 

//set default mail on sucesses
$successEmails = array(DEFEMAIL1);//$defEmail2

//set default mail on sucesses
$cashuSuccessEmails = array(DEFEMAIL1);//$defEmail2

//set error mail
$errorMails = array(DEFEMAIL1);

//set error mail
$cashuErrorMails = array(DEFEMAIL1);


?>