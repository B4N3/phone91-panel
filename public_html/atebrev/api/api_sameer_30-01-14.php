<?php
require_once("Rest.inc_sameer.php");
require_once dirname(dirname(__FILE__)).'/defineConstant.php';
require_once dirname(dirname(__FILE__)).'/definePath.php';
error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
/**
 * @author Rahul Chordiy <rahul@hostnsoft.com>
 * @description File used for Mobile API
 * 
 * 
 */

/*
 * validation on the request parameter and 
 * update client balance 
 * functionality of each api 
 * contants 
 */
class API extends REST {

	public $data = "";
        protected $isValidate=0; //variable for check validate
	const DB_SERVER = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB = "";

	private $db = NULL;

	public function __construct() {
                //include funtion layer
                include_once $_SERVER["DOCUMENT_ROOT"] . "/function_layer.php";
                $this->funobj = new fun();
		parent::__construct();
		$this->dbConnect();
                
	}
        
        public function __destruct()
        {
            unset($this->funobj);
        }

	private function dbConnect() {
		
		//$funobj = new fun();
               
		return $this->funobj->connecti();
//			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
//			if($this->db)
//				mysql_select_db(self::DB,$this->db);
	}

	public function processApi() {
		$func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
//                var_dump($func);
		//var_dump(method_exists($this,$func));
                
		if (method_exists($this, $func))
			$this->$func();
		else {
                    $error[RESPONSE] = "0";
                    $error[MESSAGE] = array("code"=>"101","message"=>'Please Go Through API Documentations');
			$this->response($this->json($error), 200);
		}
	}

	private function login($return = false,$type = NULL) 
        {
		if ($this->get_request_method() != "POST" && $this->get_request_method() != "GET") 
                {
			$this->response('', 200);
		}
		$user = $this->_request['user'];
		
                
                
                if(preg_match(NOTUSERNAME_REGX,$user) || strlen($user) < 5)///[^a-zA-Z0-9\_\@\.]+/
                {
                    $error[RESPONSE] = "0";
                    $error[MESSAGE] = array("code"=>"102","message"=>"Invalid user name please use a valid user name");
                    $this->response($this->json($error), 200);
                }
                
                if(is_null($type))
                {
                    $password = $this->_request['password'];
                    
                    if(preg_match(NOTPASSWORD_REGX,$password) || strlen($password) < 5)///[^a-zA-Z0-9\.\@\$\-\_]+/
                    {
                        $error[RESPONSE] = "0";
                        $error[MESSAGE] = array("code"=>"103","message"=>"Invalid password please try again with a valid password");
                        $this->response($this->json($error), 200);
                    }
                }
                else
                {
                    $password = "1035d7dbf33728ad29260f1790a89d72";
                    $auth = $this->_request['auth'];
                    
                    if($auth != $password)///[^a-zA-Z0-9\_\@\.]+/
                    {
                        $error[RESPONSE] = "0";
                        $error[MESSAGE] = array("code"=>"103","message"=>"Invalid authentication key please contact provider");
                        $this->response($this->json($error), 200);
                    }                    
                }
                
		if (!empty($user) && !empty($password)) 
                {
			
			
                    $loginAttampt = $this->funobj->checkLoginFailed($user);
                    if (($loginAttampt > 10)) 
                    {
                            $error[RESPONSE] = "0";
                            $error[MESSAGE] = array("code"=>"104","message"=>"Maximum Number of request exceed");
                    
//				$error = array(RESPONSE => "0", MESSAGE => "Maximum Number of request exceed");
				$this->response($this->json($error), 200);
                    }
			
//			include_once $_SERVER["DOCUMENT_ROOT"] . "/function_layer.php";
                    
                    
                    if(is_null($type))                        
                        $result = $this->funobj->checkLogin($user, $password);
                    else
                        $result = $this->checkUserExist($user);
                    
                    if(!$result)
                    {
                        $error[RESPONSE] = "0";
                        $error[MESSAGE] = array("code"=>"105","message"=>"Unable to fetch user details please try again later");
//                            $error = array(RESPONSE => "0", MESSAGE => "Unable to fetch user details please try again later");
                        $this->response($this->json($error), 200);
                    }
                        
                    $res = $result->num_rows;
                    if ($res == '0') 
                    {
				$this->funobj->loginFailed($user); 
				
                                $error = array(RESPONSE => "0", MESSAGE => array("code"=>"106","message"=>"Invalid Username or Password"));
				$this->response($this->json($error), 200);
                    } 
                    else 
                    {
                            $get_userinfo = mysqli_fetch_array($result);
                            if ($get_userinfo["isBlocked"] != 1) 
                            {
                                    $this->funobj->loginFailed($user); 
                                    $error = array(RESPONSE => "0", MESSAGE => array("code"=>"107","message"=>"User Account Blocked"));
                                    $this->response($this->json($error), 200);
                            }
                            $respnse[RESPONSE] = "1";
                            $respnse[MESSAGE] = "Valid User";
                            $respnse[CONTENT][]['type'] = $get_userinfo["type"];
//                          $respnse[STATUS] = "success";
//                          $respnse["msg"] = "Valid User";
                            if ($return) 
                            {
                                    
                                    $respnse["id"] = $get_userinfo["userId"];
                                    $respnse[CONTENT]['id'] = $respnse["id"];
                                    $respnse[CONTENT]['resellerId'] = $get_userinfo["resellerId"];
                                    $respnse[CONTENT]['type'] = $get_userinfo["type"];
                                    return $respnse;
                            }
                            else
				$this->response($this->json($respnse), 200);
			}
			
		}
                else
                {
                    $error = array(RESPONSE => "0", MESSAGE => array("code"=>"108","message"=>"Incomplete Details"));
                    $this->response($this->json($error), 200);
                }
	}

        public function checkUserExist($userName) {
            
            if(preg_match('/[^a-zA-Z0-9\.\_\@]+/', $userName) ||$userName== "")
            {
                return 0;
            }
            
            
            $userName = $this->funobj->db->real_escape_string($userName);
            $result = $this->funobj->selectData('userId,userName,password,isBlocked,deleteFlag,type,resellerId','91_manageClient',"userName='" . $userName . "'");
            if(!$result)
                return 0;
            else
                return $result;
        }
        
        
         /**
         *@uses function to send verification code
         * @author Ankit patidar <ankitpatidar@hostnsoft.com> 
         */
        function sendVerificationCode()
        {
            $user = $this->_request['user'];
		
            if(preg_match(NOTUSERNAME_REGX,$user) || strlen($user) < 5 || strlen($user) > 30)///[^a-zA-Z0-9\_\@\.]+/
            {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code"=>"102","message"=>"Invalid user name please use a valid user name");
                $this->response($this->json($error), 200);
            }
            
            //get user id
            $userId = $this->funobj->getUserId($user);
            /**
             *code to get verified number,if not found then get temp number 
             */
            $verifyNum = 1;//variable use to select verified or temp number
            //get confirm mobile number
            $confirmRes = $this->funobj->getConfirmNumber($userId);
            
            if(is_array($confirmRes))
            {
                //get country code and mobile number
                $cCode = $confirmRes['countryCode'];
                $number= $confirmRes['verifiedNumber'];
            }
            //get number
            if(!$confirmRes)
            {
                //search temp number
                $unConfirmRes = $this->funobj->getUnConfirmNumber($userId);
                
                if(is_array($unConfirmRes))
                {
                    $verifyNum = 0;
                    //get country code and mobile number
                    $cCode = $confirmRes['countryCode'];
                    $number= $confirmRes['tempNumber'];
                }
                
                if(!$unConfirmRes)
                {
                     $error = array(RESPONSE => "0", MESSAGE => array("code"=>"109","message"=>"User name not registered!!!"));
                    $this->response($this->json($error), 200);
                }       
            }
            
            //include signup class and create object
            include_once(CLASS_DIR.'signup_class.php');
            $signUp = new signup_class();
            //set para
            echo $signUp->mobileNumber = $number;
            echo $signUp->countryCode = $cCode;
            echo $signUp->userId = $userId;
           
            //validate parameters
           $validateResJson = $signUp->validateContactParam();
           
           if($validateResJson != 1)
           {
                $validRes = json_decode($validateResJson,TRUE);
           
                if($validRes['status'] == 'error')
                {
                    $response[RESPONSE] = '0';
                    $response[MESSAGE] = array('code' => '122','message'=> $validRes['msg']);
                }
           }
           else if($validateResJson)
           {
               //get confirmation code
                $confirmCode = $this->funobj->generatePassword();
              
                
               
                $data = array("confirmCode" => $confirmCode);
                
                if($verifyNum == 1)
                {
                    $table = '91_verifiedNumbers';
                    $condition = "userId='" . $userId . "' and verifiedNumber='".$number."'";
                }
                else
                {
                    $table = '91_tempNumbers';
                    $condition = "userId='" . $userId . "' and tempNumber='".$number."'";
                }
                
                $updRes = $this->funobj->updateData($data, $table , $condition);
               
                //if confirm code updated
                if($updRes)
                {
                    $this->funobj->sendSmsCall($number,$cCode,$confirmCode,'SMS' );
                    $response[RESPONSE] = '1';
                    $response[MESSAGE] = array('Verification number send to your number!!!');
                    $response[CONTENT] = array('number' => $number,'countryCode' => $cCode);
                }
                else
                {
                     $response[RESPONSE] = '0';
                     $response[MESSAGE] = array('code' => '122','message'=> 'Problem while send code!!!');
                }
           }
           
           $this->response($this->json($response), 200); //response
    }
    
    /**
     * @uses API to verify code for user
     */
    function verifyByCode()
    {
        //get user name,number and confirmCode
        $user = $this->_request['user'];
        $number = $this->_request['number'];
        $confirmCode = $this->_request['confirmCode'];
        
        //validate user name
        if(preg_match(NOTUSERNAME_REGX,$user) || strlen($user) < 5 || strlen($user) > 30)///[^a-zA-Z0-9\_\@\.]+/
        {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code"=>"102","message"=>"Invalid user name please use a valid user name!!!");
            $this->response($this->json($error), 200);
        }
        
         //validate user name
        if(preg_match(NOTNUM_REGX,$number) || strlen($number) < 5 || strlen($number) > 30)///[^a-zA-Z0-9\_\@\.]+/
        {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code"=>"122","message"=>"Invalid mobile number please use a valid number!!!");
            $this->response($this->json($error), 200);
        }

        //validate code
         if(preg_match(NOTNUM_REGX,$confirmCode) || strlen($confirmCode) < 4 || strlen($confirmCode) > 8)///[^a-zA-Z0-9\_\@\.]+/
        {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code"=>"123","message"=>"Invalid confirmcode please use a valid code!!!");
            $this->response($this->json($error), 200);
        }
        //get user id
        $userId = $this->funobj->getUserId($user);
        //call verfy function
        $verify = $this->funobj->verifyCode($confirmCode,$number);
        
        if($verify)
        { 
            ////get random string
            $randomNum= (strtotime(gmdate("d/m/Y H:i:s")));
            
            $resultRand = $this->funobj->selectData("userId","91_randomApiStr","userId='".$userId."'" );
            
            if($resultRand->num_rows > 0)
            {
                //update info for this user id
                $data = array("randomStr" => md5($randomNum),
                              "date" => date('Y-m-d H:i:s')  );
                $condition = "userId='" . $userId . "'";
                
                $res = $this->funobj->updateData($data, "91_randomApiStr" , $condition);
                
            }
            else
            {
                //insert details into 91_randomApiStr
                //data to insert
                $data = array('userId' => $userId,
                            'randomStr' => $randomNum,
                            'date' => date('Y-m-d H:i:s'));
                $res = $this->funobj->insertData($data,'91_randomApiStr');

               
            }
            
            if(!$res)
            {
                $response[RESPONSE] = "0";
                $response[MESSAGE] = array('code' => '124' ,'message' => 'Problem while verification try again!!!');

            }
            else 
            {
                $response[RESPONSE] = "1";
                $response[MESSAGE] = "user verified!!!";
                $response[CONTENT] = array('scretCode' => $randomNum);
            }

            
            
        }
        else
        {
             $response[RESPONSE] = "0";
             $response[MESSAGE] = array('code' => '124' ,'message' => 'Verification code did not match!!!');
        }
        
         $this->response($this->json($response), 200);
    }
        
	private function balance() 
        {
		$response = $this->login(true);
		if ($response[RESPONSE] == "1") 
                {
			//$funobj = new fun();
			$result = $this->funobj->selectData('balance,currencyId', '91_userBalance', "userId = '" . $response["id"] . "'  ");
//                        $funobj->db->select('balance,currencyId')->from('91_userBalance')->where("userId = '" . $response["id"] . "'  ");
//			$result = $funobj->db->execute();
			if ($result->num_rows > 0) 
                        {
                            while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
                            {
                                    $balanceRes[BALANCE] = round($row[BALANCE], 2);
                                    $currencyId = $row["currencyId"];

//					if ($id_currency == 1) {
//						$cid = "USD";
//					} else if ($id_currency == 2) {
//						$cid = "INR";
//					} else if ($id_currency == 3) {
//						$cid = "AED";
//					}
                                        
                                    $balanceRes["currencyCode"] = $currencyId;
                                    $balanceRes[CURRENCY] = $this->funobj->getCurrencyViaApc($currencyId);
                            }
                            $returnResult[RESPONSE] = "1";
                            $returnResult[MESSAGE] = "Success";
                            $returnResult[CONTENT][] = $balanceRes;
                            $this->response($this->json($returnResult), 200);
			}
		}
		$error = array(RESPONSE => "0", MESSAGE => array("code"=>"108","message"=>"Incomplete Details"));
		$this->response($this->json($error), 200);
	}
        
        private function getDirectBalance() 
        {
		$response = $this->login(true,"auth");
		if ($response[RESPONSE] == "1") 
                {
			//$funobj = new fun();
			$result = $this->funobj->selectData('balance,currencyId', '91_userBalance', "userId = '" . $response["id"] . "'  ");
              
                        $balanceStr = '';
			if ($result->num_rows > 0) 
                        {
                            while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
                            {
                                    $balanceStr.= round($row[BALANCE],1);
                                    $currencyId = $row["currencyId"];
                                    $balanceRes["currencyCode"] = $currencyId;
                                    $balCurr = $this->funobj->getCurrencyViaApc($currencyId);
                                    
                                    //validation for currency
                                    if(isset($balCurr) and $balCurr != '')
                                    {
                                        $balanceStr.= ' '.$this->funobj->getCurrencyViaApc($currencyId);
                                    }
                                    else
                                    {
                                        echo 'Problem while getting currency!!!';
                                        exit();
                                    }
                                        
                            }
                            
                           echo $balanceStr;
                           exit();

			}
		}
                
		echo $error = '404 balance not found!!!';           
                exit();
		
	}
        
	private function twowaycalling() {
            
                $source = $this->_request['source'];
                $dest = $this->_request['dest'];
                if(strlen($source)<8  || strlen($source)>19 || preg_match(NOTNUM_REGX, $source))//'/[^0-9]+/'
                {
                        $error = array(RESPONSE => "0", MESSAGE => array("code"=>"110","message"=>"Invalid Source Number"));
                        $this->response($this->json($error), 200);
                }
                if(strlen($dest)<8  || strlen($dest)>19 || preg_match(NOTNUM_REGX, $dest))
                {
                        $error = array(RESPONSE => "0", MESSAGE => array("code"=>"111","message"=>"Invalid Destination Number"));
                        $this->response($this->json($error), 200);
                }
            
		$response = $this->login(true);
		if ($response[RESPONSE] == "1") {
			$nine["login"] = $this->_request['user'];
			$nine["password"] = $this->_request['password'];
                        if($nine["login"] == "" || $nine["password"] == "" )
                        {
                            $error = array(RESPONSE => "0", MESSAGE => array("code"=>"112","message"=>"Invalid Credentials Please enter a proper userId and password"));
                            $this->response($this->json($error), 200);
                        }
                        
			include_once $_SERVER["DOCUMENT_ROOT"] .'/definePath.php';
			include(CLASS_DIR."call_class.php");			
			$call_obj = new call_class();	
			//$funobj = new fun();
			
			$nine["dest"] = $dest;
			$nine["source"] = $source;

			$msgid = $call_obj->Call($nine);
                        
                        $msgid = str_replace('"','',substr(substr($msgid,0,-1),1));
                        
                        $msgIdArr = explode(",",$msgid);


                        foreach($msgIdArr as $val)
                        {
                                $subArr = explode(":",$val);
                                $newArrMsgId[$subArr[0]] = $subArr[1];

                        }
                        
			$returnResult[RESPONSE] = "1";
			$returnResult[MESSAGE] = "success";
			$returnResult[CONTENT][] = $newArrMsgId;
			$this->response($this->json($returnResult), 200);
		}
                else
                {
                    $error = array(RESPONSE => "0", MESSAGE => array("code"=>"108","message"=>"Incomplete Details"));
                    $this->response($this->json($error), 200);
                }
	}

        /**
         *@uses function to list the clients 
         */
	private function listclients() 
        {
            mail('sudhirp29@gmail.com', 'api run', 'run api');
		$response = $this->login(true);
               
		if ($response[RESPONSE] == "1") 
                {
			
                        $columns = 'userName,type,balance,currencyId,isBlocked';
                        $table = '91_manageClient';
                        $condition = "resellerId = '" . $response["id"] . "' and (type = 2 or type = 3)";
                        $result = $this->funobj->selectData($columns, $table,$condition);

                        if ($result->num_rows > 0) 
                        {
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
                                {
					$returnResult[CLIENTUSERNAME] = $row["userName"];
					$returnResult[BALANCE] = $row["balance"];
					$status = $row["isBlocked"];

					$type = $row["type"];

					if ($type == 1) 
                                        {
						$type = "admin";
					} 
                                        else if ($type == 2) 
                                        {
						$type = "reseller";
					} 
                                        else if ($type == 3) 
                                        {
						$type = "user";
					}

					$returnResult[TYPE] = $type;

					$returnResult[CURRENCY] = $this->funobj->getCurrencyViaApc($row["currencyId"]);

					if ($status != 1) 
                                        {
						$status = "Disabled";
					} 
                                        else 
                                        {
						$status = "Active";
					}

					$returnResult[STATUS] = $status;

					$finalResponse[] = $returnResult;
				}
                                $finalResponseArr[RESPONSE] = "1";
                                $finalResponseArr[MESSAGE] = "Success";
                                $finalResponseArr[CONTENT] = $finalResponse;
				$this->response($this->json($finalResponseArr), 200);
			}
                        else
                        {
                            $response = array(RESPONSE => "0", MESSAGE => "No Data to Display");
                            $this->response($this->json($response), 200);
                        }
                        
		}
                else
                {
                    $error = array(RESPONSE => "0", MESSAGE => array("code"=>"108","message"=>"Incomplete Details"));
                    $this->response($this->json($error), 200);
                }
	}

	private function changepassword() 
        {
		$response = $this->login(true);
		
                //check login response
                if ($response[RESPONSE] == "1") 
                {
			$new_pwd = $this->_request['newPassword'];
                        
			if (empty($new_pwd) || strlen($new_pwd) < 5 ) 
                        {
				$error = array(RESPONSE => "0", MESSAGE => array("code"=>"115","message"=>"Incomplete Details Password Must be more than 5 Character"));
				$this->response($this->json($error), 200);
				exit();
			}
                        if(preg_match(NOTPASSWORD_REGX, $new_pwd))//'/[^a-zA-Z0-9\@\$\.\_\-]/'
                        {
                            $error = array(RESPONSE => "0", MESSAGE => array("code"=>"116","message"=>"Invalid password must not contain any character other then (a-z,A-Z,0-9,@,$,.,_,-)"));
                            $this->response($this->json($error), 200);
                            exit();
                        }
                        
                        
			
			$data = array('password' => $new_pwd);
                        $new_pwd = $this->funobj->db->real_escape_string($new_pwd);
			$this->funobj->db->update('91_userLogin', $data)->where("userId = '" . $response["id"] . "'  ");
			//var_dump($funobj->db->getQuery());
			$result = $this->funobj->db->execute();
//				var_dump($result);
			//echo $result->affected_rows;
			if ($result) {
				$returnResult = array(RESPONSE => "1", MESSAGE => "Update Successfully");
				$this->response($this->json($returnResult), 200);
			} else {
				$error = array(RESPONSE => "0", MESSAGE => array("code"=>"117","message"=>"Unable to update at this time"));
				$this->response($this->json($error), 200);
			}
		}
                else
                {
                    $error = array(RESPONSE => "0", MESSAGE => array("code"=>"108","message"=>"Incomplete Details"));
                    $this->response($this->json($error), 200);
                }
                
		
	}

        /**
         *@uses function to change client password 
         */
	private function changeclientpassword() 
        {
		$response = $this->login(true);
		if ($response[RESPONSE] == "1") 
                {
                	$login = $this->_request['clientusername'];
			if (empty($login) || strlen($login) < 5) 
                        {
				$error = array(RESPONSE => "0", MESSAGE => array("code"=>"119","message"=>"Incomplete Details Username Must be more than 5 Character"));
				$this->response($this->json($error), 200);
				exit();
			}

			$new_pwd = $this->_request['newPassword'];
			if (empty($new_pwd) || strlen($new_pwd) < 5 || preg_match('/[^a-zA-Z0-9\.\@\$\-\_\?]/', $new_pwd)) 
                        {
				$error = array(RESPONSE => "0", MESSAGE => array("code"=>"120","message"=>"Incomplete Details Password Must be more than 5 Character"));
				$this->response($this->json($error), 200);
				exit();
			}
                        
			//$funobj = new fun();
                        $new_pwd = $this->funobj->db->real_escape_string($new_pwd);
                        $login = $this->funobj->db->real_escape_string($login);
                        
                        $result = $this->funobj->selectData('userId', '91_manageClient', "userName = '".$login."'");
                        
                        if($result)
                        {
                            $row = $result->fetch_array(MYSQLI_ASSOC);
                            $data = array('password'=>$new_pwd);
                            $resUpd = $this->funobj->updateData($data, '91_userLogin','userId = '.$row['userId']);
                        
                            
                            if($resUpd && $this->funobj->db->affected_rows == 1)
                            {
                              $returnResult = array(RESPONSE => "1", MESSAGE => "Update Successfully");
				$this->response($this->json($returnResult), 200);  
                            }
                            else
                            {
                                $error = array(RESPONSE => "0", MESSAGE => array("code"=>"121","message"=>"Unable to update at this time"));
				$this->response($this->json($error), 200);
                            }
                        }
                        
//                        echo $sql ="update 91_userLogin set password='".$new_pwd."' where userId in (select userId from 91_userBalance where resellerId='".$response["id"]."') and 91_userLogin.userName='" . $login . "'";
                        
//                        $funobj->db->query($sql);

			
		}
                else 
                {
                    $error = array(RESPONSE => "0", MESSAGE => array("code"=>"108","message"=>"Incomplete Details"));
                    $this->response($this->json($error), 200);
                }
		
	}
/*This api is wrong and wont function correctly*/
	private function updateclientbalance() 
        {
            
		$response = $this->login(true);
		if ($response[RESPONSE] == "1") 
                {
                        $login = $this->_request['clientusername'];
                        
                        include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/reseller_class.php";
                        $resClsObj = new reseller_class();
                        
                        $clientUserId = $resClsObj->getUserId($login);
                        $param['toUserEditFund'] = $clientUserId;
                        $param['fundAmount'] = trim($this->_request['receivingAmount']);
                        $param['balance'] = trim($this->_request['balance']);
                        $param['partialAmt'] = trim($this->_request['partialAmt']);
                        
                        $action = trim($this->_request['action']);
                        if($action == "1")
                        {
                           $param['changefunderEditFund'] = "add"; 
                        }
                        elseif($action == "0")
                        {
                           $param['changefunderEditFund'] = "reduce"; 
                        }
                            
                        
                        $param['otherPaymentType'] = trim($this->_request['otherPaymentType']);
                        
                        $paymentType = trim($this->_request['paymentType']);
                        
                        if($paymentType == "0")
                        {
                           $param['pType'] = "prepaid";
                        }
                        elseif($paymentType == "1")
                        {
                           $param['pType'] = "partial";
                        }
                        elseif($paymentType == "2")
                            $param['pType'] = "postpaid";
                        
                        
                        $paymentMode = trim($this->_request['paymentMode']);
                        
                        if($paymentMode == "0"){
                            $param['fundPaymentType'] = "Cash";
                        }elseif($paymentMode == "1"){
                            $param['fundPaymentType'] = "Memo";
                        }elseif($paymentMode == "2"){
                            $param['fundPaymentType'] = "Bank";
                        }elseif($paymentMode == "3")
                            $param['fundPaymentType'] = "Other";
                        else
                            $param['fundPaymentType'] = "Cash";
                        
                        $param['fundDescription']= trim($this->_request['description']);
                        $param['fundCurrency']= trim($this->_request['amountCurrency']);
                        $param['partialCurrency']= trim($this->_request['partialAmtCurrency']);
                      
                        $fundResponse = $resClsObj->editFund($param,$response[CONTENT]['id']);
                        $fundResponse = json_decode($fundResponse);
			
                        
                        if($fundResponse->status == "success")
                        {
                            $error[RESPONSE] = "1";
                        }
                        else
                        {
                            $error[RESPONSE] = "0";
                        }
                        $error[MESSAGE] = $fundResponse->msg;
                        $this->response($this->json($error), 200);
                        exit();        
                        
//                        if (empty($login) || strlen($login) < 5) {
//                            $error = array(RESPONSE => "0", MESSAGE => "Incomplete Details Username Must be more than 5 Character");
//				$this->response($this->json($error), 200);
//				exit();
//			}
//
//			$amount = $this->_request['amount'];
//			if (empty($amount) || !is_numeric($amount) || $amount < 0) {
//				$error = array(RESPONSE => "0", MESSAGE => "Incomplete Details Amount Must be more than 0");
//				$this->response($this->json($error), 200);
//				exit();
//			}
//			$type = $this->_request['type'];
//			if (empty($type) || strlen($type) < 3) {
//				$error = array(RESPONSE => "0", MESSAGE => "Incomplete Details Type Must be provide");
//				$this->response($this->json($error), 200);
//				exit();
//			}
//			if (($type != "add" && $type != "reduce")) {
//				$error = array(RESPONSE => "0", MESSAGE => "Incomplete Details Transfer-type must be add or reduce");
//				$this->response($this->json($error), 200);
//				exit();
//			}
//
//			
//                        $login = $funobj->db->real_escape_string($login);
//			$funobj->db->select('id_client,account_state')->from('clientsshared')->where(" id_reseller = '" . $response["id"] . "'  and login= '" . $login . "' ");
//			$result = $funobj->db->execute();
//			if ($result->num_rows > 0) {
//				while ($row = $result->fetch_array(MYSQL_ASSOC)) {
//					$current_balance = $row["account_state"];
//					$trans_tuserid = $row["id_client"];
//				}
//			}
//			else if ($result->num_rows == 0) {
//				$error = array(RESPONSE => "0", MESSAGE => "Unable to fetch client details.");
//				$this->response($this->json($error), 200);
//				exit();
//			}
//			
//			if($type=="add")
//				$newBalance = $current_balance + $amount;
//			else if($type=="reduce")
//				$newBalance = $current_balance - $amount;
//			
//			if($newBalance <0)
//			{
//				$error = array(RESPONSE => "0", MESSAGE => "User Do not have this much amount to reduce.");
//				$this->response($this->json($error), 200);
//				exit();
//			}
//			$data = array('account_state' => $newBalance);
//			$funobj->db->update('clientsshared', $data)->where("id_reseller = '" . $response["id"] . "'  and login= '" . $login . "' ");
//			$updateQry=$funobj->db->getQuery();
//			$result = $funobj->db->execute();				
//			
//			if ($funobj->db->affected_rows == 1) {
//				$data=array("trans_fuserid"=>$response["id"] , "trans_tuserid"=>$trans_tuserid, "trans_amt"=>$amount, "trans_crnt_amt"=>$current_balance, "trans_date"=>date("Y-m-d H:i:s")," trans_type"=>$type);
//				$table = 'reseller_transaction';
//				$funobj->db->insert($table, $data);
//				$insertQry=($funobj->db->getQuery());
//				$logresult = $funobj->db->execute();
//				$batchId="";
//				if($logresult  && $funobj->db->affected_rows == 1)
//				{
//					$returnResult = array(RESPONSE => "1", MESSAGE => "Update Successfully");
//					$this->response($this->json($returnResult), 200);
//				}
//				else{
//					mail("rahul@hostnsoft.com","Phone91 transaction Log","Error While inserting trans log in db");
//					$returnResult = array(RESPONSE => "1", MESSAGE => "Update Successfully but error occur duing inserting log. Update: ".$updateQry." Insert qry ".$insertQry);
//					$this->response($this->json($returnResult), 200);
//				}
//			
//			
//				
//			} else {
//				$error = array(RESPONSE => "0", MESSAGE => "Unable to update at this time");
//				$this->response($this->json($error), 200);
//			}
		}
		$error = array(RESPONSE => "0", MESSAGE => "Incomplete Details");
		$this->response($this->json($error), 200);
	}

        /**
         *@uses function to add and remove sip 
         *@author Ankit Patidar <ankitpatidar@hostnsoft.com>
         */
        private function addRemoveSip() 
        {
                //validate login details
		$response = $this->login(true);
		
                //check login response
                if ($response[RESPONSE] == "1") 
                {
//                    var_dump("here");
                    $action = (isset($this->_request['action']) && $this->_request['action'] != '')? $this->_request['action']:'';          
                    //get userName
                    $userName = $this->_request['user'];

                    //get user id
                    $userId = $this->funobj->getUserId($userName);
                    
                    if(isset($userId))
                        $result = $this->funobj->enableSip($userId,$action); 
		    
                    //validate result
                    if(isset($result))
                    {
                        $resArr = json_decode($result,TRUE); //get array
                        
                        //set success and error msg for response
                        if($resArr['status'] == 'success')
                        {
                            $response[RESPONSE]='1';
                            $response[MESSAGE] = $resArr['msg'];
                        }
                        else
                        {
                           $response[RESPONSE]='0';
                           $response[MESSAGE] = array('code' => '122','message' => $resArr['msg']);
                        }
                        $this->response($this->json($response), 200);
                    }
                    else
                    {
                        $error = array(RESPONSE => "0", MESSAGE => array("code"=>"121","message"=>"Problem while sip operation!!!"));
                        $this->response($this->json($error), 200);
                    }
                        
                }
                else //if login response not valid then give error response
                {
                    $error = array(RESPONSE => "0", MESSAGE => array("code"=>"118","message"=>"Incomplete Details"));
                    $this->response($this->json($error), 200);
                }
        	
	} 

       
        
        function pinUser()
        {
                if(isset($this->_request['type']) && isset($this->_request['emailId']) && isset($this->_request['pin']) && isset($this->_request['pin']))
                {

                    #account type : 1 for gtalk and 2 for skype 
                    $accountType = $this->_request['type'];

                    #emailid : for skype or gtalk
                    $emailId  = $this->_request['emailId'];

                    #pin
                    $pin = $this->_request['pin'];

                    #check given authentication key is valid or not
                    if($this->_request['authKey'] == "213265498754665458"){
                        echo $msg = $this->createPinUser($accountType,$emailId,$pin);
                    }else
                        echo $msg = "You have no permission for use this API.";
                    }
                    else
                    echo "Please provide valid accountType, emailid, pin and AuthKey";    
        }
        
        function createPinUser($accountType,$emailId,$pin)
        {
    
    //$funobj = new fun();
    
    #check emailid is velide or not 
    if(preg_match("/[^a-zA-Z0-9\.\_\@\-\$]+/", $emailId)){
         return "emailId is not valid";
    }
    
    if($accountType == 1)
      {
          $tableName = '91_verifiedGtalkId';
      }
      elseif($accountType == 2)
      {
          $tableName = '91_verifiedSkypeId';
         
      }else
          return "account type is not valid";
      
    #check user Name already exist 
    $result = $this->funobj->selectData('*', $tableName, "email ='".$emailId."'");

    
        
    #get pin detail 
    $pinDetail = $this->getPinDetail($pin);
    $pinDataDetail = json_decode($pinDetail,TRUE);  //$pinGenerator,$pinTariff,$pinCurrency,$pinBalance   
        
    if($pinDataDetail['status'] == "error"){
        return $pinDataDetail['msg'];
    }
    
//    echo "id".$pinDataDetail['pinGenerator']." ".$pinDataDetail['pinTariff']." ".$pinDataDetail['pinCurrency']." ".$pinDataDetail['pinBalance'];
    
    #check the resulting value exists or not 
    if($result->num_rows > 0)
    {
        $row = $result->fetch_array(MYSQL_ASSOC);
        $userid = $row['userId'];
        $userTariff = $this->getUserTariff($userid);
        return rechargeByPin($userid,$userTariff,$pinDataDetail['pinTariff'],$pinDataDetail['batchId'],$pin,$pinDataDetail['pinBalance']);
        
//        return "Email id already exist";
    }
    
    $userData = $this->createUser($pinDataDetail['pinGenerator'],$pinDataDetail['pinTariff'],$pinDataDetail['pinCurrency'],$pinDataDetail['pinBalance']); 
    $userDetail = json_decode($userData,TRUE);  
    
    if($userDetail['status'] == "error"){
        return $userDetail['msg'];
    }
    
   $userId = $userDetail['userId'];
    
   $data = array("email"=>$emailId,"userId"=>$userId);
   $resInsert = $this->funobj->insertData($data, $tableName);

    
   include_once("../classes/transaction_class.php");
   $transactionObj = new transaction_class();
   
   $transactionObj->fromUser = $pinDataDetail['pinGenerator'];
   $transactionObj->toUser = $userId;
   
   $transactionObj->addTransactional_sub($pinDataDetail['pinBalance'],$pinDataDetail['pinBalance'],"Pin User",0,0,0,"Create and Recharge by Pin");
            
   
    #update pin status 
    $table = '91_pinDetails';
    $data=array("usedDate"=>date('Y-m-d H:i:s'),"status"=>1,"usedBy"=>$userId); 
    $condition = "pincode='".$pin."'";
    $this->funobj->db->update($table, $data)->where($condition);	
    $this->funobj->db->getQuery();
    $result = $this->funobj->db->execute(); 
 
    $msg = "successfully id recharge by pin.. ";
    return $msg;
}

function getPinDetail($pin){
    
    //$funobj = new fun();
    
    # check pin valid or not 
    if(!isset($pin) || strlen($pin)<5)
        {
         return json_encode(array('status'=>'error','msg'=>'Invalide pin!')); 
        }	

    # get pin status (1 for used or 0 for unused).
    $table = '91_pinDetails';

    #selecting the item from table 91_pinDetails
    $this->funobj->db->select('*')->from($table)->where("pincode ='".$pin."'");
    $this->funobj->db->getQuery();

    #execute query
    $result=$this->funobj->db->execute();

    #check the resulting value exists or not 
    if($result->num_rows == 0)
      {
         return json_encode(array('status'=>'error','msg'=>'Invalide pin!')); 
      }

    $row = $result->fetch_array(MYSQL_ASSOC);
    if($row['status'] == 1){
        return json_encode(array('status'=>'error','msg'=>'pin already used by another user!')); 
    }
    
    $batchId = $row['batchId']; 
    
    $pinTable = '91_pin';
    $condition = "batchId = '" . $row['batchId'] ."'"; //userId= '".$userid."' or 
    $this->funobj->db->select('*')->from($pinTable)->where($condition);
    $batchResult = $this->funobj->db->execute();

    // processing the query result
    if ($batchResult->num_rows == 0) {	
        return json_encode(array('status'=>'error','msg'=>'You Have No Permission To Use This Pin!')); 

    }

    $batchDetail = $batchResult->fetch_array(MYSQL_ASSOC);

    if(strtotime(date('Y-m-d',strtotime($batchDetail['expiryDate']))) < strtotime(date('Y-m-d'))){
         return json_encode(array('status'=>'error','msg'=>'Pin are expired !')); 
    }

    #pin tariff id 
    $pinTariff = $batchDetail['tariffId'];
       
    #find pin currency (call function_layer.php function) 
    $pinCurrency = $this->funobj->getOutputCurrency($batchDetail['tariffId']);
    
    #pin Generator id 
    $pinGenerator = $batchDetail['userId'];
    
    #pin balance
    $pinBalance = $batchDetail['amountPerPin'];
    
    return json_encode(array('status'=>'success','pinGenerator'=>$pinGenerator,'pinTariff'=>$pinTariff,'pinCurrency'=>$pinCurrency,'pinBalance'=>$pinBalance,'batchId'=>$batchId)); 
    
}

function createUser($resellerId,$tariff_id,$currency_id,$balance)
    {
        //$funobj = new fun();
        
        $table = '91_userLogin';
        $condition = "userId = '".$resellerId."'";
        $this->funobj->db->select('isBlocked,deleteFlag')->from($table)->where($condition);        
        $loginresult = $this->funobj->db->execute();
        if ($loginresult->num_rows > 0) {
            $logindata = $loginresult->fetch_array(MYSQL_ASSOC);
            $blockUnblockStatus = $logindata['isBlocked'];
            $deleteFlag = $logindata['deleteFlag'];
        }
        else
        {
            return json_encode(array("status"=>"error","msg"=>"Error Unable to fetch the reseller details Please Try again"));
        }
        
      
     $userName = $this->funobj->createUsername($resellerId);
     $password = $this->funobj->createUsername($resellerId);     
        
      #insert userdetail into database       
      $data=array("name"=>$userName); 
      $personalTable = '91_personalInfo';
      #insert query (insert data into 91_personalInfo table )
      $personalResult = $this->funobj->insertData($data, $personalTable);

      #check data inserted or not 
      if(!$personalResult){
//        $this->sendErrorMail("sameer@hostnsoft.com", "Phone91 signup_class personal info table query fail : $qur ");
        return json_encode(array("status"=>"error","msg"=>"pin User not created !"));
          
      }
      
           
      $userid = $this->funobj->db->insert_id;
      
      #insert login detail into login table database 
      $loginTable = '91_userLogin';
      $data=array("userId"=>$userid,"userName"=>$userName,"password"=>$password,"isBlocked"=>$blockUnblockStatus,"deleteFlag"=>$deleteFlag,"type"=>5); 

      #insert query (insert data into 91_userLogin table )
      $loginResult = $this->funobj->insertData($data, $loginTable);

      #check data inserted or not 
      if(!$loginResult){
          $this->funobj->deleteData($personalTable, "userId = ".$userid);
//         $this->sendErrorMail("rahul@hostnsoft.com", "Phone91 signup_class userlogin  table query fail : $qur ");
         return json_encode(array("status"=>"error","msg"=>"pin User not created!"));
          
      }
      
      #get last chain id from user balance table  
      $lastchainId = $this->funobj->getlastChainId($resellerId);
      
      #new chain id (incremented id of lastchain id )
      $chainId = $this->funobj->newChainId($lastchainId);
      
      #insert login detail into login table database 
      $balanceTable = '91_userBalance';
     
      $data=array("userId"=>(int)$userid,"chainId"=>$chainId,"tariffId"=>(int)$tariff_id,"balance"=>$balance,"currencyId"=>(int)$currency_id,"callLimit"=>2,"resellerId"=>(int)$resellerId); 

      #insert query (insert data into 91_userLogin table )
      $balanceResult = $this->funobj->insertData($data, $balanceTable);
      if (!$balanceResult){
          $this->funobj->deleteData($personalTable, "userId = ".$userid);
          $this->funobj->deleteData($loginTable, "userId = ".$userid);
          return json_encode(array("status"=>"error","msg"=>"pin User not created!"));  
      }
      return json_encode(array("status"=>"success","userId"=>$userid));  
    }

   
    
    function rechargeByPin($userid,$userTariff,$pinTariff,$batchId,$pin,$pinAmount)
	{
           
            #get ResellerId of user 
            //$funobj = new fun();
            $resellerId = $this->funobj->getResellerId($userid);
                        
            #find pin generateor id
            $pinTable = '91_pin';
            $condition = "batchId = '" . $batchId . "' and (userId= '".$resellerId."') "; //userId= '".$userid."' or 
            $this->funobj->db->select('*')->from($pinTable)->where($condition);
            $batchResult = $this->funobj->db->execute();

            // processing the query result
            if ($batchResult->num_rows == 0) {	
                return json_encode(array('status'=>'error','msg'=>'You Have No Permission To Use This Pin!')); 
                
            }
            
            
            #find pin and user currency (call function_layer.php function) 
            $pinCurr = $this->funobj->getOutputCurrency($pinTariff);
            $userCurr = $this->funobj->getOutputCurrency($userTariff);
            
            if($pinCurr != $userCurr){
                 return json_encode(array('status'=>'error','msg'=>'you can not use this pin because pin currency not match.')); 
            }
            
            $table = '91_pinDetails';
            
            #update pin status 
            $data=array("usedDate"=>date('Y-m-d H:i:s'),"status"=>1,"usedBy"=>$userid); 
            $condition = "pincode='".$pin."'";
            $this->funobj->db->update($table, $data)->where($condition);	
            $this->funobj->db->getQuery();
            $result = $this->funobj->db->execute();
            
            
            #recharge pin entry in transaction log 
            $amountPerPin = $pinAmount;
            
            
            include_once("../classes/transaction_class.php");
            $transactionObj = new transaction_class();
            
             #update current balance of user in userbalance table 
            $transactionObj->updateUserBalance($userid,$amountPerPin,'+');
            
            
            $getBalance = $transactionObj->getClosingBalance($userid);
            
            //set from user and toUser
            $transactionObj->fromUser = $resellerId;
            $transactionObj->toUser = $userid;
            
            $msg = $transactionObj->addTransactional_sub($amountPerPin,$amountPerPin,"Pin User",$amountPerPin,0,$getBalance,"Recharge by Pin");
            
            
            if($result){
            return json_encode(array('status'=>'success','msg'=>'successfully recharge!')); 
            }else
            {
                return json_encode(array('status'=>'error','msg'=>'error in recharge by pin!')); 
            }
            
            
            
           
		
	} 
        
        
    function getUserTariff($userid){
        
      #get ResellerId of user 
     // $funobj = new fun();  
      $loginTable = '91_userBalance';
      
      #get reseller id for user 
      $this->funobj->db->select('*')->from($loginTable)->where("userId = '" .$userid. "'");
      
      $result = $this->funobj->db->execute();
      if($result)
      {
        $row = $result->fetch_array(MYSQL_ASSOC);
        $userTariff = $row['tariffId'];
        return $userTariff;
      }
      else
          return false;
         
    }

        
        
	private function json($data) {
		if (is_array($data)) {
			return json_encode($data);
		}
	}

}

$api = new API();
$api->processApi();
?>