<?php
/**
 * 
 * Calling function class
 * 
 * @author "Vikas Wasiya" <vikas@hostnsoft.com>
 * 
 */


include_once('/home/voip91/public_html/beta/classes/dbconnect_class.php');
//include_once("/var/lib/asterisk/agi-bin/phpagi-2.20/phpagi.php");

class fun_class extends db_class{
	function createUniqueId($id_client){
		//$this->send_gtalk_response('vikas@walkover.in','Hello');
        	return $id_client.rand(1000000,10000000).date('dm');
	}

	function getclientidbyjid($jid){
	        $searchSql = "select * from 91_gtalkContact where gtalk_email like '".$jid."' limit 1";
//		$con = $this->astererisk_connect();
		$con = $this->voip_connect();
	        $result = mysql_query($searchSql,$con);
	        mysql_close($con);
	        if($result && mysql_num_rows($result)>0){
	                $get_userinfo = mysql_fetch_array($result);
	                $id_client = $get_userinfo['id_client'];
	                return $id_client;
	        }
	        else {
        	        mail('vikas@hostnsoft.com',' New voice server gtalk_status.php getIdClientByUniqueId function',' query Error occur '.mysql_error().$searchSql);
       	         return 'unable to fetch userName from jabber id';
        	}
	}



function getclientidbyjid1($jid){
                $searchSql = "select * from 91_verifiedGtalkId  where email like '".$jid."' limit 1";
//              $con = $this->astererisk_connect();
                $con = $this->voip_connect();
                $result = mysql_query($searchSql,$con);
                mysql_close($con);
                if($result && mysql_num_rows($result)>0){
                        $get_userinfo = mysql_fetch_array($result);
                        $id_client = $get_userinfo['userId'];
                        return $id_client;
                }
                else {
                        mail('vikas@hostnsoft.com',' New voice server gtalk_status.php getIdClientByUniqueId function',' query Error occur '.mysql_error().$searchSql);
                 return 'unable to fetch userName from jabber id';
                }
        }




	function check_register($jid){
		$con = $this->voipcall_connect();

		$sqlCheck="select * from gtalk_status where jabber_id='".$jid."'";
        	$resCheck = mysql_query($sqlCheck,$con) or $error=mysql_error();
        	mysql_close($con);

	        $countId = mysql_num_rows($resCheck);

        	if ( !($countId)){
        	        return 0;
        	}
        	
		$get_userinfo = mysql_fetch_array($resCheck);
        	$passwd = $get_userinfo['password'];

	        $client_id = $this->getclientidbyjid($jid);
		
	        $query = "select * from 91_userLogin where userId = '$client_id'";
	
	        $con = $this->voip_connect();
	        $resCheck = mysql_query($query,$con) or die(mysql_error());
	        mysql_close($con);

	        $get_userinfo = mysql_fetch_array($resCheck);
	        $password = $get_userinfo['password'];
	        $password = strtolower($password);

	        if ($passwd == $password){
	                return 1;
	        }
	        else {
	                return 0;
        	}
	}


function check_register1($jid){
                //$con = $this->voipcall_connect();

               //NN
			          $host = "localhost";
                                 $username = "root";
                                 $password = "#9QGCBo@";
                                 $database = "voip91_switch";
                                 $con = mysql_connect($host,$username,$password) or die(" Couldnot connect to the server ");
                                 mysql_select_db($database,$con) or die(" As Database Not Found voipcall ");

                
                $sqlCheck="select * from 91_verifiedGtalkId  where email='".$jid."'";
                $resCheck = mysql_query($sqlCheck,$con) or $error=mysql_error();
                mysql_close($con);

                $countId = mysql_num_rows($resCheck);

                if ( !($countId)){
                //   $fun_obj->jabber_send_response($to,$from,'0');
                        return 0;
                }
		else {
                        return 1;
                }

                $get_userinfo = mysql_fetch_array($resCheck);
               // $passwd = $get_userinfo['password'];
               // $client_id = $this->getclientidbyjid($jid);
                //$query = "select * from 91_userLogin where userId = '$client_id'";
                //$con = $this->voip_connect();
                //$resCheck = mysql_query($query,$con) or die(mysql_error());
               // mysql_close($con);
                //$get_userinfo = mysql_fetch_array($resCheck);
                //$password = $get_userinfo['password'];
                //$password = strtolower($password);
               // if ($passwd == $password){
               //         return 1;
                //}
                //else {
                //        return 0;
               // }
        }




	function jabber_send_response($to,$email,$text){
        	$tmp = explode("/", $to);
        	$tmp1 = $tmp[0];

        	$tmp2 = explode("@", $tmp1);

        	$username = $tmp2[0];
        	$domain = $tmp2[1];

        	if ($username == 'phone'){
        	        $passwd = ")okm(ijn";
      	          	$cmd = "jsend ".$username." ".$domain." '".$passwd."' ".$email." ".$text;
//              $cmd = 'pygsend '.$email.' '.$text;
                	phone91exec($cmd);
        	}
        	else if ($username == 'domain'){
        	        $passwd = "pushpendra@123#";
        	        $cmd = "jsend ".$username." ".$domain." '".$passwd."' ".$email." ".$text;
        	        phone91exec($cmd);
        	}
        	else if ($username == 'agrawalpraniti'){
        	        $passwd = "@praniti123";
        	        $cmd = "jsend ".$username." ".$domain." '".$passwd."' ".$email." ".$text;
        	        phone91exec($cmd);
        	}
        	else if ($username == 'zedshed007'){
                	$passwd = "zedshed@123#";
                	$cmd = "jsend ".$username." ".$domain." '".$passwd."' ".$email." ".$text;
                	phone91exec($cmd);
        	}
	}

	function unregister($jid){
	        $query = "update gtalk_status set password = '' where jabber_id='".$jid."'";
        	$con = $this->voipcall_connect();
        	$result = mysql_query($query,$con) or die(mysql_error());
        	mysql_close($con);

        	if($result){
        	        return 1;
        	}
        	else {
        	        return 0;
        	}
	}

      function unregister1($jid){
            	$con = $this->voip_connect();
               	$query = "SELECT * FROM 91_verifiedGtalkId where email = '$jid'";
                $result = mysql_query($query,$con) or die('Query error10');
                mysql_close($con);
		$resCheck = mysql_num_rows($result);
		if(!$resCheck){
			return 0;
		}
		if(!$result){
			return 0;
		}
                
		$get_userinfo = mysql_fetch_array($result);
                $userId = $get_userinfo['userId'];
                $systemId = $get_userinfo['systemId'];
                $email = $get_userinfo['email'];
		$isCallShopUser = $get_userinfo['isCallShopUser'];
		$code = $get_userinfo['code'];
 
		$con = $this->voip_connect();
                $query= "insert into 91_deleteContact (userId, systemId, email, isCallShopUser, code) values('$userId','$systemId','$email', '$isCallShopUser', '$code')" ;
	       	$result = mysql_query($query,$con) or die('Query error11');
                mysql_close($con);
		if(!$result){
                        return 0;
                }

		$con = $this->voip_connect();
                $query = "delete from 91_verifiedGtalkId where email = '$jid'";
	        mysql_query($query, $con);
		mysql_close($con);
                if(!$result){
                        return 0;
                }
                else {
                        return 1;
                }
        }


	function getUserDetails($idClient){
	        $con = $this->voip_connect();
		$query = "SELECT L.*,B.* FROM  91_userLogin L, 91_userBalance B where L.userId = '$client_id' and B.userId = '$client_id'";
	        $result = mysql_query($query, $con) or die("Can not verify your details. ".mysql_error());
	        mysql_close($con);
	        $res = mysql_num_rows($result);
	        if(!$res){
	                $response = 'Sorry username and/or password are not matche. use \'register username password\'';
	                //send_gtalk_response($from, 'Sorry username and password are not match. use register username password');
	                exit();
	        }
        	else{
	                $get_userinfo = mysql_fetch_array($result);
	        }
        	return $get_userinfo;
	}

	function get_currency($currencyId){
	        $con = $this->voip_connect();
	        $result = mysql_query("select currency from 91_currencyDesc where currencyId = '$currencyId'",$con) or die('Query error12');
	        mysql_close($con);
	        $cur = mysql_fetch_array($result);
	        return $currency = $cur['currency'];
	}

	function search_pb($client_id,$search_name){
	        $con = $this->voipswitch_connect();
	        $result = mysql_query("SELECT * FROM voipswitch.addressbook a where id_client like '".$client_id."' and (telephone_number like '%".$search_name."%' or nickname like '%".$search_name."%')",$con) or die('addres_sbook Query error13');
	        mysql_close($con);
	        return $result;
	}

	function getCallRate($number,$id_tariff){
	        $i = strlen($number);
		//$this->send_gtalk_response("vikas@walkover.in",$number.' '.$id_tariff);
	        $prefix='';
	        for($i;$i>0;$i--){
	                $number = substr($number,0, $i);
	                $prefix.= "'".$number."',";
	        }
	        $prefix = substr($prefix, 0,strlen($prefix)-1);
	        $sql = "select voiceRate,prefix from 91_tariffs where tariffId = '".$id_tariff."' AND prefix in (".$prefix.") order by length(prefix) desc limit 1";
//        send_gtalk_response("vikas@hostnsoft.com",urlencode($sql));
        //mail("vikas@hostnsoft.com","query",$sql);
	        $con = $this->voip_connect();
	        $result = mysql_query($sql,$con);
	        if(!$result){
			return 0;
		}
	        mysql_close($con);
	        if($result)
	        {
	                $res = mysql_num_rows($result);
	                $row = mysql_fetch_array($result);
	                return $voice_rate = $row['voiceRate'];
	        }
	}

	function getPrefix($number,$id_tariff){
		$i = strlen($number);
                $prefix='';
                for($i;$i>0;$i--){
                        $number = substr($number,0, $i);
                        $prefix.= "'".$number."',";
                }
                $prefix = substr($prefix, 0,strlen($prefix)-1);
                $sql = "select voiceRate,prefix from 91_tariffs where tariffId = '".$id_tariff."' AND prefix in (".$prefix.") order by length(prefix) desc limit 1";
                $con = $this->voip_connect();
                $result = mysql_query($sql,$con);
                if(!$result){
                        return 0;
                }
                mysql_close($con);
                if($result)
                {
                        $res = mysql_num_rows($result);
                        $row = mysql_fetch_array($result);
                        return $prefix = $row['prefix'];
                }

	}

	function findText($start_limiter,$end_limiter,$haystack){
	        $start_pos = strpos($haystack,$start_limiter);
	        if ($start_pos === FALSE){
	                $start_pos = 0;
	        }
        	else{
	                $start_pos += 1;
	        }
	
	        $end_pos = strpos($haystack,$end_limiter);

	        if ($end_pos === FALSE){
	                $end_pos = strlen($haystack)-1;
	        }
	        else{
                 	$end_pos -= 1;
	        }

	        return substr($haystack, $start_pos, ($end_pos-$start_pos+1));
	}

	function getCallerId($idClient){
	        $con = $this->voip_connect();
	        $searchSql = "select CONCAT(countryCode,verifiedNumber) as callerId from 91_verifiedNumbers where userId = '$idClient' and isDefault = '1'";
	        $result = mysql_query($searchSql,$con);

	        if($result && mysql_num_rows($result)>0){
	                $get_userinfo = mysql_fetch_array($result);
	        }
        	else {
                        return 0;
                }
	        $callerId = $get_userinfo['callerId'];
	        return $callerId;
	}

	function send_gtalk_response($email,$text){
	        if((strlen(trim($text))>0) && (strlen(trim($email))>0)){
	                $cmd = 'gsend '.$email.' '.$text;
	//              $cmd = 'pygsend '.$email.' '.$text;
	                phone91exec("$cmd");
	        }
	        else {
	
	        }
	}

	function get_clientstatus($client_id){
	        $con = $this->voip_connect();
	        $sql = "SELECT isBlocked FROM 91_userLogin where userId = '$client_id'";
	        $result = mysql_query($sql,$con) or die('Query error14');
	        mysql_close($con);
       	 	$get_userinfo = mysql_fetch_array($result);
        	$client_status = $get_userinfo['isBlocked'];
        	return $client_status;
	}

	function getChainId($client_id){
		$con = $this->voip_connect();
		$sql = "SELECT chainId FROM 91_userBalance where userId = '$client_id'";
		$result = mysql_query($sql,$con) or die('Query error15');
                mysql_close($con);
                $get_userinfo = mysql_fetch_array($result);
                $chainId = $get_userinfo['chainId'];
                return $chainId;
	}

	function getUserId($chainId){
                $con = $this->voip_connect();
                $sql = "SELECT userId FROM 91_userBalance where chainId = '$chainId'";
                $result = mysql_query($sql,$con) or die('Query error16');
                mysql_close($con);
                $get_userinfo = mysql_fetch_array($result);
                $userId = $get_userinfo['userId'];
                return $userId;
        }

	function current_calls($chainId){
		$con = $this->voip_connect();
                $sql = "select count(*) as calls from 91_currentCalls where id_chain like '$chainId%'";
                $result = mysql_query($sql,$con) or die('Query error17');
                mysql_close($con);
		$get_userinfo = mysql_fetch_array($result);
                return $get_userinfo['calls'];
	}

	function reject_reason($chainId, $number, $reason){
		$con = $this->voip_connect();
		$sql = "insert into 91_rejectCalls (chainId, reason, telNum) values ('$chainId', '$reason', '$number')";
		$result = mysql_query($sql,$con) or die('Query error18');
		mysql_close($con);
	}

	function check_user_balance($chainId, $number, &$reason){
        $con = $this->voip_connect();
        $sql = "select tariffId,balance,callLimit from 91_userBalance where chainId = '$chainId'";
        $result = mysql_query($sql,$con) or die('Query error19');
        mysql_close($con);
        $get_userinfo = mysql_fetch_array($result);
        $tariff = $get_userinfo['tariffId'];
        $balance = $get_userinfo['balance'];
        $callLimit = $get_userinfo['callLimit'];

        if ($tariff == 0 || $tariff == ''){
            $reason = "Tariff not found";
            return 0;
        }

        if ($balance <= 0 || $balance == ''){
            $reason = "Insufficient balance";
            return 0;
                }

        $callRate = $this->getCallRate($number, $tariff);

        if ($callRate == '' || $callRate <= 0 || $callRate >= $balance){
            $reason = "Not have balance to call";
            return 0;
        }

        $current_calls = $this->current_calls($chainId);

        if ($current_calls >= $callLimit){
            $reason = "Maximum call reached:";
            return 0;
        }
		return 1;
	}


	function check_chain_balance($chainId, $number, &$reason){
		$con = $this->voip_connect();
		$sql = "select tariffId,balance,callLimit from 91_userBalance where chainId = '$chainId'";
		$result = mysql_query($sql,$con) or die('Query error20');
		mysql_close($con);
		$get_userinfo = mysql_fetch_array($result);
		$tariff = $get_userinfo['tariffId'];
		$balance = $get_userinfo['balance'];
		$callLimit = $get_userinfo['callLimit'];

		if ($tariff == 0 || $tariff == ''){ 
			$reason = "Tariff not found: chainId = $chainId, number = $number, tariff = $tariff";
			return 0;
		}

		if ($balance <= 0 || $balance == ''){ 
			$reason = "Insufficient balance: chainId = $chainId, number = $number, balance = $balance";
			return 0;
                }

		$callRate = $this->getCallRate($number, $tariff);

		if ($callRate == '' || $callRate <= 0 || $callRate >= $balance){
			$reason = "Not have balance to call: chainId = $chainId, number = $number, balance = $balance, callRate = $callRate";
			return 0;
		}

		$current_calls = $this->current_calls($chainId);

		if ($current_calls >= $callLimit){
			$reason = "Maximum call reached: chainId = $chainId, number = $number, current_calls = $current_calls, callLimit = $callLimit";
			return 0;
		}

		if (strlen($chainId) <= 4){
			$reason = "sucess";
			return 1;
		}
		else {
			$newchainId = substr($chainId,0,-4);
			return $this->check_chain_balance($newchainId, $number, $reason);
		}
	}

               
        function check_chain_balance1($chainId, $number, &$reason){
                $con = $this->voip_connect();

                     if ($con=='0')
                       { return 5;
                          die();
                               }
                $sql = "select tariffId,balance,callLimit from 91_userBalance where chainId = '$chainId'";
                $result = mysql_query($sql,$con) or die('Query error21');
                mysql_close($con);
                $get_userinfo = mysql_fetch_array($result);
                $tariff = $get_userinfo['tariffId'];
                $balance = $get_userinfo['balance'];
                $callLimit = $get_userinfo['callLimit'];


              if ($tariff == 0 || $tariff == ''){
                        $reason = "Tariff not found: chainId = $chainId, number = $number, tariff = $tariff";
                        return 2;
                }

                if ($balance <= 0 || $balance == ''){
                        $reason = "Insufficient balance: chainId = $chainId, number = $number, balance = $balance";
                        return 4;
                }

                $callRate = $this->getCallRate($number, $tariff);
                  //echo  "balnce=".$balance;

                if ($callRate == '' || $callRate <= 0 || $callRate >= $balance){
                        $reason = "Not have balance to call: chainId = $chainId, number = $number, balance = $balance, callRate = $callRate";
                        return 3;
                }

                $current_calls = $this->current_calls($chainId);

                if ($current_calls >= $callLimit){
                        $reason = "Maximum call reached: chainId = $chainId, number = $number, current_calls = $current_calls, callLimit = $callLimit";
                        return 5;
                }

                if (strlen($chainId) <= 4){
                        $reason = "sucess";
                        return 1;
                }
                else {
                        $newchainId = substr($chainId,0,-4);
                        return $this->check_chain_balance1($newchainId, $number, $reason);
                }
        }


	function voip_route(){
		$con = $this->voip_connect();
		$sql5 = "select * from 91_currentCallingRoute";
		$routeInfo = mysql_query($sql5,$con) or die(mysql_error());
		mysql_close($con);

		$getRouteInfo = mysql_fetch_array($routeInfo);
		return $route = $getRouteInfo['routename'];
	}

	function update_chain_balance($chainId, $number){
		$tariff = $this->gettariffidbychainid($chainId);

		$callRate = $this->getCallRate($number, $tariff);

		$con1 = $this->voip_connect();
		$sql1 = "update 91_userBalance set balance = balance - $callRate where chainId = '$chainId'";
		$result = mysql_query($sql1,$con1) or die('Query error22');
		mysql_close($con1);

                if (strlen($chainId) <= 4){
                        return 1;
                }
                else {
                        $newchainId = substr($chainId,0,-4);
                        return $this->update_chain_balance($newchainId,$number);
                }
	}

	function get_idtariff($client_id){
	        $con = $this->voip_connect();
	        $sql = "SELECT tariffId FROM 91_userBalance where userId = '$client_id'";
	        $result=mysql_query($sql,$con) or die('Query error23');
	        mysql_close($con);
	        $get_userinfo = mysql_fetch_array($result);
	        $id_tariff = $get_userinfo['tariffId'];
       	 	return $id_tariff;
	}

	function gettariffidbychainid($chainId){
                $con = $this->voip_connect();
                $sql = "SELECT tariffId FROM 91_userBalance where chainId = '$chainId'";
                $result=mysql_query($sql,$con) or die('Query error');
                mysql_close($con);
                $get_userinfo = mysql_fetch_array($result);
                $id_tariff = $get_userinfo['tariffId'];
                return $id_tariff;
        }

	function request($body) {
    		$host = "localhost";
    		$port = 9876;
//    		$req = "chainId=$body";
		$req = "GET /uniqueId=$body HTTP/1.1";
//    		$timeout = '';
		try {
	      		$socket = fsockopen($host, $port);
	      		$result = fwrite($socket, $req);
			if($result){
				return 1;
			}
			else{ 
				return 0;
			}
		}
		catch (Exception $e) {
			return 0;
		}
    	}

	function getpulse($tariffId){
		$con = $this->voip_connect();
		$sql = "select billingInSeconds from 91_plan where tariffId = '$tariffId'";
		$result = mysql_query($sql,$con) or die('Query error1');
                mysql_close($con);
                $get_userinfo = mysql_fetch_array($result);
                $pulse = $get_userinfo['billingInSeconds'];
                return $pulse;

	}

	function refund_chain_balance($uniqueId, $chainId, $telNum, $startTs, $endTs, $nextTs){
		$sql_select = "select userId,tariffId,currencyId from 91_userBalance where chainId = '$chainId'";
		$con_select = $this->voip_connect();
		$result = mysql_query($sql_select,$con_select) or die('Query error2');
		mysql_close($con_select);
		$get_userinfo = mysql_fetch_array($result);

		$tariffId = $get_userinfo['tariffId'];
		$userId = $get_userinfo['userId'];
		$currencyId = $get_userinfo['currencyId'];

//		$tariffId = $this->gettariffidbychainid($chainId);
//		$userId = $this->getUserId($chainId);
		$expected_pulse = $this->getpulse($tariffId);
		$real_pulse = 60;
		$callRate = $this->getCallRate($telNum, $tariffId);

		$real_deduct = ceil(($nextTs - $startTs) / $real_pulse) * $callRate;
		$pulseCount = ceil(($endTs - $startTs) / $expected_pulse);
		$durationCharged = $pulseCount * $expected_pulse;
		$expected_deduct = $pulseCount * (($callRate * $expected_pulse)/ 60);
		if ($durationCharged <= 0 || $expected_deduct <= 0){
			mail('vikas@walkover.in','balance','uniqueId='.$uniqueId.' chainId='.$chainId.' pulseCount='.$pulseCount.' expected_pulse='.$expected_pulse.' callRate='.$callRate.' endTs='.$endTs.' startTs='.$startTs);
		}

		if ($real_deduct <= $expected_deduct){
			$amount = $expected_deduct - $real_deduct;
			if ($amount > 0){
				$con = $this->voip_connect();
		                $sql = "update 91_userBalance set balance = balance - $amount where chainId = '$chainId'";
        		        $result = mysql_query($sql,$con) or die('Query error3');
        		        mysql_close($con);

				$con = $this->voip_connect();
                                $sql = "insert into 91_chainBalanceReport (uniqueId,chainId,userId,deductBalance,tariffId,voiceRate,currencyId,durationCharged) VALUES ('$uniqueId','$chainId','$userId','$expected_deduct',$tariffId,$callRate,$currencyId,$durationCharged)";
                                $result = mysql_query($sql,$con) or die('Query error4');
                                mysql_close($con);
			}
			else{
				$con = $this->voip_connect();
                                $sql = "insert into 91_chainBalanceReport (uniqueId,chainId,userId,deductBalance,tariffId,voiceRate,currencyId,durationCharged) VALUES ('$uniqueId','$chainId','$userId','$expected_deduct',$tariffId,$callRate,$currencyId,$durationCharged)";
                                $result = mysql_query($sql,$con) or die('Query error5');
                                mysql_close($con);
			}
		}
		else {
			$amount = $real_deduct - $expected_deduct;
			$con = $this->voip_connect();
			$sql = "update 91_userBalance set balance = balance + $amount where chainId = '$chainId'";
                   	$result = mysql_query($sql,$con) or die('Query error6');
         	        mysql_close($con);

			$con = $this->voip_connect();
//                     	$sql = "insert into 91_chainBalanceReport (uniqueId,chainId,deductBalance) VALUES ('$uniqueId','$chainId','$expected_deduct')";
			$sql = "insert into 91_chainBalanceReport (uniqueId,chainId,userId,deductBalance,tariffId,voiceRate,currencyId,durationCharged) VALUES ('$uniqueId','$chainId','$userId','$expected_deduct',$tariffId,$callRate,$currencyId,$durationCharged)";
             		$result = mysql_query($sql,$con) or die('Query error7');
                	mysql_close($con);
		}

		if (strlen($chainId) <= 4){
                        return 1;
                }
                else {
                        $newchainId = substr($chainId,0,-4);
                        return $this->refund_chain_balance($uniqueId, $newchainId, $telNum, $startTs, $endTs, $nextTs);
                }
	}

	function getclientidbyusername($username){
	        $con = $this->voip_connect();
	        $result = mysql_query("select userId from 91_userLogin where userName ='$username'",$con);
	        mysql_close($con);
	        if($result && mysql_num_rows($result)>0){//if details exist
	                $get_userinfo = mysql_fetch_array($result);
	                $response = $get_userinfo['userId'];
        	}
        	else {
                	$response = '';
        	}
        	return $response;
	}

	function check_auth($username,$password){
		$con = $this->voip_connect();
		$sql = "select password from 91_userLogin where userName = '$username'";
		$result = mysql_query($sql,$con);
		mysql_close($con);
		$get_userinfo = mysql_fetch_array($result);
                $passwd = $get_userinfo['password'];
		if ($password == $passwd){
			return 1;
		}
		else{
		 	return 0;
		}
	}

	function check_combine_chain_balance($chainId, $src_no, $des_no, &$reason){
		$con = $this->voip_connect();
		$sql = "select tariffId,balance,callLimit from 91_userBalance where chainId = '$chainId'";
		$result = mysql_query($sql,$con) or die('Query error8');
		mysql_close($con);
		$get_userinfo = mysql_fetch_array($result);
		$tariff = $get_userinfo['tariffId'];
		$balance = $get_userinfo['balance'];
		$callLimit = $get_userinfo['callLimit'];


		if ($tariff == 0 || $tariff == ''){
			$reason = "Tariff not found: chainId = $chainId, src_no = $src_no, des_no = $des_no, tariff = $tariff";
			return 0;
		}

		if ($balance <= 0 || $balance == ''){
			$reason = "Insufficient balance: chainId = $chainId, src_no = $src_no, des_no = $des_no, balance = $balance";
			return 0;
		}

		$callRate1 = $this->getCallRate($src_no, $tariff);
		$callRate2 = $this->getCallRate($des_no, $tariff);
		$callRate = $callRate1 + $callRate2;

		if ($callRate1 == '' || $callRate1 <= 0 || $callRate2 == '' || $callRate2 <= 0 || $callRate >= $balance){
			$reason = "Not have balance to call: chainId = $chainId, src_no = $src_no, des_no = $des_no, balance = $balance, totalcallRate = $callRate";
			return 0;
		}

		$current_calls = $this->current_calls($chainId);
		if ($current_calls >= $callLimit){
			$reason = "Maximum call reached: chainId = $chainId, src_no = $src_no, des_no = $des_no, current_calls = $current_calls, callLimit = $callLimit";
			return 0;
		}

		if (strlen($chainId) <= 4){
			$reason = "sucess";
			return 1;
		}
		else {
			$newchainId = substr($chainId,0,-4);
			return $this->check_combine_chain_balance($newchainId, $src_no, $des_no, &$reason);
		}
	}

	function insertIntoCurrentCalls($param){
		$error = '';
		$unique_id = isset($param['uniqueId']) ? trim($param['uniqueId']):"";
		$id_client = isset($param['id_client']) ? trim($param['id_client']):"";
		$callerId = isset($param['callerId']) ? trim($param['callerId']):"";
		$dialed_number = isset($param['dialed_number']) ? trim($param['dialed_number']):"";
		$status = isset($param['status']) ? trim($param['status']):"";
		$call_type = isset($param['call_type']) ? trim($param['call_type']):"";
		$chainId = isset($param['id_chain']) ? trim($param['id_chain']):"";
		$id_active_call = isset($param['id_active_call']) ? trim($param['id_active_call']):"";

		$con = $this->voip_connect();
		$sql = "insert into 91_currentCalls (uniqueId, id_active_call, id_client, id_chain, callerId, dialed_number, call_dial, status, call_type) VALUES ('$unique_id', '$id_active_call', '$id_client', '$chainId', '$callerId', '$dialed_number', now(), '$status', '$call_type')";
		$result = mysql_query($sql,$con) or $error = mysql_error();
		mysql_close($con);
		
		if ($error != ''){
			return 0;
		}
		
		return 1;
	}

	function c2ccallfile($callFileParam){
		$unique_id1 = isset($callFileParam['unique_id1']) ? trim($callFileParam['unique_id1']):"";
		$unique_id2 = isset($callFileParam['unique_id2']) ? trim($callFileParam['unique_id2']):"";
		$maxdur = isset($callFileParam['maxdur']) ? trim($callFileParam['maxdur']):"";
		$src_no = isset($callFileParam['src_no']) ? trim($callFileParam['src_no']):"";
		$des_no = isset($callFileParam['des_no']) ? trim($callFileParam['des_no']):"";
		$route = isset($callFileParam['route']) ? trim($callFileParam['route']):"";

		if ($maxdur == '' || $maxdur < 1){
			return 0;
		}
		$dir = "/tmp";
		$File = $route.$unique_id1.".call";
		$path = $dir."/".$File;

		$Handle = fopen($path, 'w');
		
		if (!$Handle){
			return 0;
		}

		$Data = "Set: PassedInfo=".$unique_id1."-".$unique_id2."-".$maxdur."-".$src_no."-".$des_no."-".$route."\n";
		fwrite($Handle, $Data);
        	$Data = "Channel: SIP/".$route."/".$src_no."\n";
//		$Data = "Channel: SIP/faketopri/".$src_no."\n";
		fwrite($Handle, $Data);
		$Data = "CallerId: ".$des_no."\n";
		fwrite($Handle, $Data);
		$Data = "Context: click2call\n";
		fwrite($Handle, $Data);
		$Data = "Extension: ".$des_no."\n";
		fwrite($Handle, $Data);
		$Data = "Priority: 1\n";
		fwrite($Handle, $Data);
		fclose($Handle);

		rename($path, "/var/spool/asterisk/outgoing/".$File);
		
		return 1;
	}

	function fail_reason($reason){
	        if($reason == 0)
	        $cause = 'FAILED';

	        else if($reason == 1 )
	        $cause = 'HANGUP';

	        else if($reason == 3)
	        $cause = 'RING TIMEOUT';

	        else if($reason == 5)
	       	$cause = 'BUSY';

	        else if($reason == 8)
	        $cause = 'CONGESTION';

	        else
	        $cause = 'UNKNOWN';

	        return $cause;
	}

	function save_db($mobile_no,$vcode,$uid){
        	$con = $this->voip_connect();
        	$error = '';
       		$sql = "insert into 91_callVerificationStatus (unique_id,mobile_no,status,code) VALUES ('$uid','$mobile_no','PENDING','$vcode')";
        	$result = mysql_query($sql,$con) or $error="Error";
        	mysql_close($con);
        	if ($error != ''){
//        	        echo $error;
        	        return $error;
        	        die();
        	}
		return 1;
	}

	function callfile($mobile_no,$vcode,$uid,$route){
	        $dir = "/tmp";
	        $File = $route.$uid.".call";
	        $path = $dir."/".$File;

	        $Handle = fopen($path, 'w');

		if (!$Handle){
                        return 0;
                }

	        $Data = "Set: PassedInfo=".$uid."\n";
	        fwrite($Handle, $Data);
	        $Data = "Channel: SIP/".$route."/".$mobile_no."\n";
	        fwrite($Handle, $Data);
	        $Data = "CallerId: 401\n";
	        fwrite($Handle, $Data);
	        $Data = "Context: vphone91\n";
	        fwrite($Handle, $Data);
	        $Data = "Extension: 111\n";
	        fwrite($Handle, $Data);
	        $Data = "Priority: 1\n";
	        fwrite($Handle, $Data);
	        fclose($Handle);
//      error_reporting(-1);

	        rename($path, "/var/spool/asterisk/outgoing/".$File);

		return 1;
	}
	
	function get_userType($userId){
		$con = $this->voip_connect();
                $sql = "select type from 91_userLogin where userId = '$userId'";
                $result = mysql_query($sql,$con) or die('Query error9');
                mysql_close($con);
                $get_userinfo = mysql_fetch_array($result);
                $type = $get_userinfo['type'];
		return $type;
	}

}
?>
