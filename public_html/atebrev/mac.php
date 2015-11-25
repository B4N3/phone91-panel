<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//echo "123";
include 'config.php';

//$funObj = new fun();
//
//$userIp = $funObj->getUserIP();
//
//$mac = shell_exec('arp -a ' . escapeshellarg($ip));
//
//// can be that the IP doesn't exist or the host isn't up (spoofed?)
//// check if we found an address
//if(empty($mac)) {
//die("No mac address for $ip not found");
//}
//
//// having it
//echo "mac address for $ip: $mac";


$ipAddress=$_SERVER['REMOTE_ADDR'];



#here U can run external command

$arp="arp -a $ipAddress";

var_dump($arp);
$lines=explode("n", $arp);

#looking up the arp U need
foreach($lines as $line)
{
    $cols=preg_split('/s+/', trim($line));
    if ($cols[0]==$ipAddress)
    $macAddr=$cols[1];
    
var_dump($macAddr);
}