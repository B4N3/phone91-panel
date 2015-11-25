<?php

class db_class{
	function voiptest_connect(){
     	$con = mysql_connect("localhost","voip91_switch",'yHqbaw4zRWrUWtp8') or die(" Couldnot connect to the server ");
	    mysql_select_db("voip91_switch",$con) or die(" Database Not Found voip91_switch ");
	    return $con;
	}
	function voip_connect(){
            $con = mysql_connect("localhost","phone91",'yHqbaw4zRWrUWtp8') or die(" Couldnot connect to the server ");
            mysql_select_db("voip",$con) or die(" Database Not Found voip ");
            return $con;
    }
	function voipcall_connect(){
		$host = "localhost";
    		$username = "walkover";
    		$password = "8m3AfI)K}({=:8q";
    		$database = "voipcall";
    		$con = mysql_connect($host,$username,$password) or die(" Couldnot connect to the server ");
    		mysql_select_db($database, $con) or die(" As Database Not Found voipcall ");
    		return $con;
	}
	function astererisk_connect(){
	        $con = mysql_connect("localhost","asteriskDbUser",'Vn8b857WqHWS2RYL') or die(" Couldnot connect to the server ");
	        mysql_select_db("asterisk",$con) or die(" As Database Not Found asterisk ");
	        return $con;
	}
	function voipswitch_connect(){
		        $con = mysql_connect("localhost","voipswitchuser",'+4H8ZXcSyWn7CuX*') or die(" Couldnot connect to the server ");
	        mysql_select_db("voipswitch",$con) or die(" Database Not Found voipswitch ");
	        return $con;
        }
	function voiptopri_connect(){
                $con = mysql_connect("111.118.250.238","walkover",'8m3AfI)K}({=:8q') or die(" Couldnot connect to the server ");
            mysql_select_db("Gtalk",$con) or die(" Database Not Found voipswitch ");
            return $con;
        }
/*	function mongo_phone91_connect(){
        $m = new Mongo('mongodb://127.0.0.1:27017/phone91');
        $db = $m->phone91;
        return $db;
    } */ 
	function mongo_phone91_connect(){
        $m = new Mongo('mongodb://phonebook:bY6c1e9y$#@127.0.0.1:27017/phone91');
        $db = $m->phone91;
        return $db;
    }
}
?>
