<?php
require_once("Rest.inc.php");
/**
 * @author Rahul Chordiy <rahul@hostnsoft.com>
 * @description File used for Mobile API
 * 
 * 
 */
class API extends REST {

	public $data = "";

	const DB_SERVER = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB = "";

	private $db = NULL;

	public function __construct() {
		parent::__construct();
		$this->dbConnect();
	}

	private function dbConnect() {
		include_once $_SERVER["DOCUMENT_ROOT"] . "/function_layer.php";
		$funobj = new fun();
		return $funobj->connecti();
//			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
//			if($this->db)
//				mysql_select_db(self::DB,$this->db);
	}

	public function processApi() {
		$func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
		//var_dump(method_exists($this,$func));
		if (method_exists($this, $func))
			$this->$func();
		else {
			$this->response('Please Go Through API Documentations', 200);
		}
	}

	private function login($return = false) {
		if ($this->get_request_method() != "POST" && $this->get_request_method() != "GET") {
			$this->response('', 200);
		}
		$user = $this->_request['user'];
		$password = $this->_request['password'];		
		
		if (!empty($user) and !empty($password)) {
			
			$funobj = new fun();
			 $loginAttampt = $funobj->checkLoginFailed($user);
			if (($loginAttampt > 10)) {
				$error = array('status' => "Failed", "msg" => "Maximum Number of request exceed");
				$this->response($this->json($error), 200);
			}
			
//			include_once $_SERVER["DOCUMENT_ROOT"] . "/function_layer.php";
			$result = $funobj->checkLogin($user, $password);
			$res = mysqli_num_rows($result);
			if ($res == '0') {
				$funobj->loginFailed($user); 
				$error = array('status' => "Failed", "msg" => "Invalid Username or Password");
				$this->response($this->json($error), 200);
			} else {
				$get_userinfo = mysqli_fetch_array($result);
				if ($get_userinfo["isBlocked"] != 1) {
					$funobj->loginFailed($user); 
					$error = array('status' => "Failed", "msg" => "User Account Blocked");
					$this->response($this->json($error), 200);
				}
				$respnse["status"] = "success";
				$respnse["msg"] = "Valid User";
				if ($return) {

					$respnse["id"] = $get_userinfo["userId"];
					return $respnse;
				}
				else
					$this->response($this->json($respnse), 200);
			}
			
		}
		$error = array('status' => "Failed", "msg" => "Incomplete Details");
		$this->response($this->json($error), 200);
	}

	private function balance() {
		$response = $this->login(true);
		if ($response['status'] == "success") {
			$funobj = new fun();
			$funobj->db->select('balance,currencyId')->from('91_userBalance')->where("userId = '" . $response["id"] . "'  ");
			$result = $funobj->db->execute();
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_array(MYSQL_ASSOC)) {
					$returnResult["balance"] = $row["balance"];
					$id_currency = $row["currencyId"];
                                        
                                        
					if ($id_currency == 1) {
						$cid = "USD";
					} else if ($id_currency == 2) {
						$cid = "INR";
					} else if ($id_currency == 3) {
						$cid = "AED";
					}
                                        
                                        
					$returnResult["currency"] = $cid;
				}
				$this->response($this->json($returnResult), 200);
			}
		}
		$error = array('status' => "Failed", "msg" => "Incomplete Details");
		$this->response($this->json($error), 200);
	}
	private function twowaycalliing() {
            
		$response = $this->login(true);
		if ($response['status'] == "success") {
			$nine["login"] = $this->_request['user'];
			$nine["password"] = $this->_request['password'];	
			include_once $_SERVER["DOCUMENT_ROOT"] .'/definePath.php';
			include(CLASS_DIR."call_class.php");			
			$call_obj = new call_class();	
			$funobj = new fun();
			$source = $this->_request['source'];;
			$dest = $this->_request['dest'];
			if(strlen($source)<8  || strlen($source)>19)
			{
				$error = array('status' => "Failed", "msg" => "Invalid Source Number");
				$this->response($this->json($error), 200);
			}
			if(strlen($dest)<8  || strlen($dest)>19)
			{
				$error = array('status' => "Failed", "msg" => "Invalid Destination Number");
				$this->response($this->json($error), 200);
			}
			$nine["dest"] = $dest;
			$nine["source"] = $source;

			$msgid = $call_obj->Call($nine);


			$returnResult["status"] = "success";
			$returnResult["msg"] = $msgid;
			$this->response($this->json($returnResult), 200);
		}
		$error = array('status' => "Failed", "msg" => "Incomplete Details");
		$this->response($this->json($error), 200);
	}

	private function listclients() {
		$response = $this->login(true);
		if ($response['status'] == "success") {
			$funobj = new fun();
			$funobj->db->select('userName,type,balance,currencyId,isBlocked')->from('91_manageClient')->where("resellerId = '" . $response["id"] . "'  ");
			$result = $funobj->db->execute();
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_array(MYSQL_ASSOC)) {
					$returnResult["username"] = $row["userName"];
					$returnResult["balance"] = $row["balance"];


					$status = $row["isBlocked"];

					$id_currency = $row["currencyId"];

					$type = $row["type"];


					if ($type == 1) {
						$type = "admin";
					} else if ($type == 2) {
						$type = "reseller";
					} else if ($type == 3) {
						$type = "user";
					}

					$returnResult["type"] = $type;

					if ($id_currency == 1) {
						$cid = "USD";
					} else if ($id_currency == 2) {
						$cid = "INR";
					} else if ($id_currency == 3) {
						$cid = "AED";
					}
					$returnResult["currency"] = $cid;


					if ($status != 1) {
						$status = "Disabled";
					} else {
						$status = "Active";
					}

					$returnResult["status"] = $status;

					$finalResponse[] = $returnResult;
				}
				$this->response($this->json($finalResponse), 200);
			}
		}
		$error = array('status' => "Failed", "msg" => "Incomplete Details");
		$this->response($this->json($error), 200);
	}

	private function changepassword() {
		$response = $this->login(true);
		if ($response['status'] == "success") {
			$new_pwd = $this->_request['newpassword'];
			if (empty($new_pwd) || strlen($new_pwd) < 5) {
				$error = array('status' => "Failed", "msg" => "Incomplete Details Password Must be more than 5 Character");
				$this->response($this->json($error), 200);
				exit();
			}
			$funobj = new fun();
			$data = array('password' => $new_pwd);
			$funobj->db->update('91_userLogin', $data)->where("userId = '" . $response["id"] . "'  ");
			//var_dump($funobj->db->getQuery());
			$result = $funobj->db->execute();
//				var_dump($result);
			//echo $result->affected_rows;
			if ($result) {
				$returnResult = array('status' => "success", "msg" => "Update Successfully");
				$this->response($this->json($returnResult), 200);
			} else {
				$error = array('status' => "Failed", "msg" => "Unable to update at this time");
				$this->response($this->json($error), 200);
			}
		}
		$error = array('status' => "Failed", "msg" => "Incomplete Details");
		$this->response($this->json($error), 200);
	}

	private function changeclientpassword() {
		$response = $this->login(true);
		if ($response['status'] == "success") {
			$login = $this->_request['clientusername'];
			if (empty($login) || strlen($login) < 5) {
				$error = array('status' => "Failed", "msg" => "Incomplete Details Username Must be more than 5 Character");
				$this->response($this->json($error), 200);
				exit();
			}

			$new_pwd = $this->_request['newpassword'];
			if (empty($new_pwd) || strlen($new_pwd) < 5) {
				$error = array('status' => "Failed", "msg" => "Incomplete Details Password Must be more than 5 Character");
				$this->response($this->json($error), 200);
				exit();
			}
                        
			$funobj = new fun();
                        
                        $sql ="update 91_userLogin set password='".$new_pwd."' where userId in (select userId from 91_userBalance where resellerId='".$response["id"]."') and 91_userLogin.userName='" . $login . "'";
                        $funobj->db->query($sql);

			if ($funobj->db->affected_rows == 1) {
				$returnResult = array('status' => "success", "msg" => "Update Successfully");
				$this->response($this->json($returnResult), 200);
			} else {
				$error = array('status' => "Failed", "msg" => "Unable to update at this time");
				$this->response($this->json($error), 200);
			}
		}
		$error = array('status' => "Failed", "msg" => "Incomplete Details");
		$this->response($this->json($error), 200);
	}

	private function updateclientbalance() {
		$response = $this->login(true);
		if ($response['status'] == "success") {
			$login = $this->_request['clientusername'];
			if (empty($login) || strlen($login) < 5) {
				$error = array('status' => "Failed", "msg" => "Incomplete Details Username Must be more than 5 Character");
				$this->response($this->json($error), 200);
				exit();
			}

			$amount = $this->_request['amount'];
			if (empty($amount) || !is_numeric($amount) || $amount < 0) {
				$error = array('status' => "Failed", "msg" => "Incomplete Details Amount Must be more than 0");
				$this->response($this->json($error), 200);
				exit();
			}
			$type = $this->_request['type'];
			if (empty($type) || strlen($type) < 3) {
				$error = array('status' => "Failed", "msg" => "Incomplete Details Type Must be provide");
				$this->response($this->json($error), 200);
				exit();
			}
			if (($type != "add" && $type != "reduce")) {
				$error = array('status' => "Failed", "msg" => "Incomplete Details Transfer-type must be add or reduce");
				$this->response($this->json($error), 200);
				exit();
			}

			$funobj = new fun();

			$funobj->db->select('id_client,account_state')->from('clientsshared')->where(" id_reseller = '" . $response["id"] . "'  and login= '" . $login . "' ");
			$result = $funobj->db->execute();
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_array(MYSQL_ASSOC)) {
					$current_balance = $row["account_state"];
					$trans_tuserid = $row["id_client"];
				}
			}
			else if ($result->num_rows == 0) {
				$error = array('status' => "Failed", "msg" => "Unable to fetch client details.");
				$this->response($this->json($error), 200);
				exit();
			}
			
			if($type=="add")
				$newBalance = $current_balance + $amount;
			else if($type=="reduce")
				$newBalance = $current_balance - $amount;
			
			if($newBalance <0)
			{
				$error = array('status' => "Failed", "msg" => "User Do not have this much amount to reduce.");
				$this->response($this->json($error), 200);
				exit();
			}
			$data = array('account_state' => $newBalance);
			$funobj->db->update('clientsshared', $data)->where("id_reseller = '" . $response["id"] . "'  and login= '" . $login . "' ");
			$updateQry=$funobj->db->getQuery();
			$result = $funobj->db->execute();				
			
			if ($funobj->db->affected_rows == 1) {
				$data=array("trans_fuserid"=>$response["id"] , "trans_tuserid"=>$trans_tuserid, "trans_amt"=>$amount, "trans_crnt_amt"=>$current_balance, "trans_date"=>date("Y-m-d H:i:s")," trans_type"=>$type);
				$table = 'reseller_transaction';
				$funobj->db->insert($table, $data);
				$insertQry=($funobj->db->getQuery());
				$logresult = $funobj->db->execute();
				$batchId="";
				if($logresult  && $funobj->db->affected_rows == 1)
				{
					$returnResult = array('status' => "success", "msg" => "Update Successfully");
					$this->response($this->json($returnResult), 200);
				}
				else{
					mail("rahul@hostnsoft.com","Phone91 transaction Log","Error While inserting trans log in db");
					$returnResult = array('status' => "success", "msg" => "Update Successfully but error occur duing inserting log. Update: ".$updateQry." Insert qry ".$insertQry);
					$this->response($this->json($returnResult), 200);
				}
			
			
				
			} else {
				$error = array('status' => "Failed", "msg" => "Unable to update at this time");
				$this->response($this->json($error), 200);
			}
		}
		$error = array('status' => "Failed", "msg" => "Incomplete Details");
		$this->response($this->json($error), 200);
	}

	private function json($data) {
		if (is_array($data)) {
			return json_encode($data);
		}
	}

}

$api = new API;
$api->processApi();
?>