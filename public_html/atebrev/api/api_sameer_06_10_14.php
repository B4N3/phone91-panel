<?php

require_once("Rest.inc_sameer.php");
require_once dirname(dirname(dirname(__FILE__))) . '/foundationCall.php';
require_once dirname(dirname(__FILE__)) . '/defineConstant.php';
require_once dirname(dirname(__FILE__)) . '/definePath.php';
//require_once dirname(dirname(__FILE__)).'/logmonitor.php';
//var_dump($_REQUEST);
// ini_set('display_errors', 1);
//error_reporting(-1);
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
    protected $isValidate = 0; //variable for check validate

    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB = "";

    private $db = NULL;

    public function __construct() {
        //include funtion layer
        include_once $_SERVER["DOCUMENT_ROOT"] . "/apiConfig.php";
        $this->funobj = new fun();
        parent::__construct();
        $this->dbConnect();
    }

    public function __destruct() {
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

        $func = strtolower(trim(end(explode("/", $_REQUEST['request']))));

        if (method_exists($this, $func))
            $this->$func();
        else {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "101", "message" => 'Please Go Through API Documentations');
            $this->response($this->json($error), 200);
        }
    }


    private function checkRegx($particular, $expression, $message = "Invalid Input", $code = '122', $maxLen = NUll) {
        //echo '   '.$Particular.'   ==  '.$expression.'  +++  '.preg_match( $expression ,$particular);
        //// echo  '   ++   '.preg_match( $expression ,$particular).'   '.$expression;
        //check for max length
        $lenCheck = !is_null($maxLen) ? (strlen($particular) > $maxLen ) : false;

        if (preg_match($expression, $particular) || strlen($particular) < 1 || $lenCheck) {///[^a-zA-Z0-9\_\@\.]+/


            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => $code, "message" => $message);
            $this->response($this->json($error), 200);
        }
    }

    private function login($return = false, $type = NULL) {

        $userIdType = "";
        $passwordType = "";
//            error_reporting(-1);
        if ($this->get_request_method() != "POST" && $this->get_request_method() != "GET") {
            $this->response('', 200);
        }

        $user = trim($this->_request['user']);
        $loginType = $this->_request['loginType'];
        $resellerId = $this->_request['resellerId'];
        $countryCodeArr = $this->_request['countryCodeArr'];
        $password = trim($this->_request['password']);
        
        
        $checkLoginFlag = 0;
        if (is_numeric($password) && strlen($password) == 4) {
            $type = "pin";
        }




//                if(isset($loginType) && ($loginType == "" || preg_match(NOTNUM_REGX,$loginType )|| $loginType != 1))
        if (isset($loginType) && ($loginType == "" || $loginType != 1)) {
            if (empty($loginType)) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1001", "message" => "Invalid Login type please read the api documentation");
                $this->response($this->json($error), 200);
            }
        }

        $userNameLen = strlen($user);

        #numeric username logic strarts here 
        #if user name is number 
        if (ctype_digit($user)) {

            if (empty($resellerId) || preg_match(NOTNUM_REGX, $resellerId) || strlen($resellerId) > 40) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1000", "message" => "Invalid Reseller please try again");
                $this->response($this->json($error), 200);
            }

            if (preg_match(NOTNUM_REGX, $user) || $userNameLen < 7 || $userNameLen > 18) {///[^a-zA-Z0-9\_\@\.]+/
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1002", "message" => "Invalid Number please use a valid Number");
                $this->response($this->json($error), 200);
            }

            include_once(CLASS_DIR . "contact_class.php");

            $cntClsObj = new contact_class();
            
//                        $result = $cntClsObj->checkVerifiedNumberExist($user,$resellerId);
            //case of number and verified number
            //validate the sting first with all type of validations 
            //check the number in verified table if get the number then 
            //fetch the userId and set the type for the check_login to 2
            //else check the username for batch 
            //set cloumn to be fetched
            $coloumn = 'userId,resellerId,countryCode,verifiedDate,verifiedNumber';

            //get verified number with single number form verifed number
            $result = $cntClsObj->getVerifiedNumber($user, 2, $coloumn, $resellerId);

            if (!$result) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $cntClsObj->code, "message" => $cntClsObj->msg));
                $this->response($this->json($error), 200);
            } else if ($result && is_array($cntClsObj->data) && empty($cntClsObj->data)) {
                //case when query output is true means no fatal but there is not data found
                #VALIDATE COUNTRY CODE ARRAY PENDING 
                //prepare a combinatiaon of country code array
                foreach ($countryCodeArr as $val) {
                    $numberArr[] = $val . $user;
                }

                # validation of $numberArr is inside the below function
                #get verified nuber when array is supplied with in operator
                $result = $cntClsObj->getVerifiedNumber($numberArr, 1, $coloumn, $resellerId);

                if (!$result) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "1003", "message" => "Error unable to get details"));
                    $this->response($this->json($error), 200);
                } else if (count($cntClsObj->data) < 1) {
                    //check number only in verified number field
                    $result = $cntClsObj->selectData($coloumn, "91_verifiedNumbers", "verifiedNumber='" . $user . "' and resellerId=" . $resellerId);

                    if (!$result || $result->num_rows < 1) {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => "1004", "message" => "Error unable to get details"));
                        $this->response($this->json($error), 200);
                    }


                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        $dataVerNum[] = $row;
                    }
                    
                    $cntClsObj->setData($dataVerNum);
                }
            }
            //count the reuslt
            $countRes = count($cntClsObj->data);



            if ($result && $countRes > 0) {
                // result is coorect then set the user id as a user and set the check login falg to one 
                // to check the user via user id
                if (1 == $countRes) {
                    $user = $cntClsObj->data[0]['userId'];
                } else {
                    foreach ($cntClsObj->data as $value) {
                        $key = $value['countryCode'] . $value['verifiedNumber'];
                        $user[$key] = $value['userId'];
                    }
                }

//                            $user  = $cntClsObj->data['userId'];
                $checkLoginFlag = 1;
            } else {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1005", "message" => "Error no data found");
                $this->response($this->json($error), 200);
            }

            //set userId type == 2 for checking the user by user Id not by user name 
            $userIdType = 2;

            /*             * * user name as number case end here***** */
        } elseif (strpos($user, "@") !== false) {
            include_once(CLASS_DIR . "contact_class.php");
            $cntClsObj = new contact_class();
            $result = $cntClsObj->checkVerifiedEmailExist($user, $resellerId);

            if ($result) {
                $user = $cntClsObj->data['userId'];

                $checkLoginFlag = 1;
            } else {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => $cntClsObj->code, "message" => $cntClsObj->msg);
                $this->response($this->json($error), 200);
            }
            //case of email
            //check the email in verified table first if exist
            //then get the userId and set the checklogin flag to 2 else
            //return error invalid email id
            #set userId type == 2 for checking the user by user Id not by user name 
            $userIdType = 2;
        } else {
            if (preg_match(NOTUSERNAME_REGX, $user) || strlen($user) < 5) {///[^a-zA-Z0-9\_\@\.]+/
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1006", "message" => "Invalid user name please use a valid user name");
                $this->response($this->json($error), 200);
            }

            #set userId type == 1 for checking the user by user name not by user ID
            $userIdType = 1;
        }

//                }



        if (is_null($type)) {
            
//var_dump($password);
            if (preg_match(NOTPASSWORD_REGX, $password) || strlen($password) < 5) {///[^a-zA-Z0-9\.\@\$\-\_]+/
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1018", "message" => "Invalid password please try again with a valid password");
                $this->response($this->json($error), 200);
            }

            //for password
            $passwordType = 1;
        } elseif ($type == "pin") {
//            $pin = $password;
            if (preg_match(NOTNUM_REGX, $password) || empty($password)) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1008", "message" => "Invalid pin please provide a valid pin");
                $this->response($this->json($error), 200);
            }
            //pin as a password
            $passwordType = 2;
//            $password = $pin; //$pin variable can be removed
        } elseif ($type == "auth") {
            $password = "29a77b7c1a19da3527153bef89fb0d98";

            $auth = trim($this->_request['auth']);
            if (!preg_match('/^[a-f0-9]{32}$/', $auth) || empty($auth)) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1009", "message" => "Invalid authentication key please provide a valid authentication key");
                $this->response($this->json($error), 200);
            }

            if ($auth != $password) {///[^a-zA-Z0-9\_\@\.]+/
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1010", "message" => "Invalid authentication key please contact provider");
                $this->response($this->json($error), 200);
            }
            //auth key
            $passwordType = 3;
        } else {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "1011", "message" => "Invalid authentication type please contact provider");
            $this->response($this->json($error), 200);
        }

        if (!empty($user) && !empty($password)) {


            $loginAttampt = $this->funobj->checkLoginFailed($user);
            if (($loginAttampt > 10)) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1012", "message" => "Maximum Number of request exceed");

//				$error = array(RESPONSE => "0", MESSAGE => "Maximum Number of request exceed");
                $this->response($this->json($error), 200);
            }

//			include_once $_SERVER["DOCUMENT_ROOT"] . "/function_layer.php";

            if (is_array($user)) {
                foreach ($user as $val) {
                    $result = $this->funobj->checkLoginNew($val, $password, $userIdType, $passwordType);
                    if ($result)
                        break;
                }
            }else {
                $result = $this->funobj->checkLoginNew($user, $password, $userIdType, $passwordType);
            }


            if (!$result) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "1013", "message" => "Unable to fetch user details please try again later");
//                            $error = array(RESPONSE => "0", MESSAGE => "Unable to fetch user details please try again later");
                $this->response($this->json($error), 200);
            }

            $res = $result->num_rows;
            if ($res == '0') {
                $this->funobj->loginFailed($user);

                $error = array(RESPONSE => "0", MESSAGE => array("code" => "1014", "message" => "Invalid Username or Password"));
                $this->response($this->json($error), 200);
            } else {
                $getUserInfo = $result->fetch_array(MYSQLI_ASSOC);

                
                if ($userIdType == 2 && ctype_digit($user) && isset($this->_request['accessToken'])) {
                    
                    if(empty($this->_request['accessType']) || ($this->_request['accessType'] != 0 && $this->_request['accessType'] !=1)){
                        $error = array(RESPONSE => "0", MESSAGE => array("code" =>"1019", "message" => "Error for attaching email to this account you have to pass a valid access type"));
                        $this->response($this->json($error), 200);
                    }
                    
                    include_once(CLASS_DIR . "signup_class.php");
                    $signupObj = new signup_class();
                    $accessToken = $this->_request['accessToken'];
                    $accessType = $this->_request['accessType'];
                    $resp = $signupObj->checkFbGlAuthKey($accessToken, $accessType);
                    if (!$resp || empty($signupObj->data)) {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => $signupObj->code, "message" => $signupObj->msg));
                        $this->response($this->json($error), 200);
                    }
                    
                    $resp = json_decode($signupObj->data, true);
                    unset($signupObj);
                    
                   
                    if ($resp['verified'] == true || $resp['verified_email'] != "") {
                        //add verified email id to the database
                        include_once(CLASS_DIR . "contact_class.php");
                        $cntClsObj = new contact_class();
                        
//                        print_r($getUserInfo);
                        $resultVerifiedId = $cntClsObj->addVerifiedEmailId($resp['email'], $getUserInfo['userId'], $getUserInfo['resellerId']);


                        if (!$resultVerifiedId) {
                            $error = array(RESPONSE => "0", MESSAGE => array("code" => $cntClsObj->code, "message" => $cntClsObj->msg));
                            $this->response($this->json($error), 200);
                        }
                        unset($cntClsObj);
                    } else {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => "1015", "message" => "Error invalid email id cant authenticate"));
                        $this->response($this->json($error), 200);
                    }
                }
//                            $getUserInfo = mysqli_fetch_array($result);

                if ($getUserInfo["isBlocked"] != 1) {
                    $this->funobj->loginFailed($user);
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "1016", "message" => "User Account Blocked"));
                    $this->response($this->json($error), 200);
                }

                if ($loginType) {
                    if ($getUserInfo["beforeLoginFlag"] != 2) {
//                                    $this->funobj->loginFailed($user); 
                        /*                         * *  please do not change error code 1007  *** */
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => "1007", "message" => "User Account Notverified"), CONTENT => array("beforeLoginFlag" => $getUserInfo["beforeLoginFlag"]));
                        if ($return) {
                            $error = array(RESPONSE => "0", MESSAGE => array("code" => "1007", "message" => "User Account Notverified"), CONTENT => array("beforeLoginFlag" => $getUserInfo["beforeLoginFlag"], "userId" => $getUserInfo["userId"], "resellerId" => $getUserInfo["resellerId"]));
                            return $error;
                        } else
                            $this->response($this->json($error), 200);
                    }
                }
                $respnse[RESPONSE] = "1";
                $respnse[MESSAGE] = array("code" => "100", "message" => "Valid User");
//                $tempData[] = array('type'=>$getUserInfo["type"],'userName'=>$getUserInfo["userName"],'balance'=>$getUserInfo["balance"],'tariffId'=>$getUserInfo["tariffId"],'name'=>$getUserInfo["name"]);
                unset($getUserInfo["password"]);
                $respnse[CONTENT][] = $getUserInfo;
//                          $respnse[STATUS] = "success";
//                          $respnse["msg"] = "Valid User";
                if ($return) {

                    $respnse["id"] = $getUserInfo["userId"];
                    $respnse[CONTENT]['id'] = $respnse["id"];
                    $respnse[CONTENT]['resellerId'] = $getUserInfo["resellerId"];
                    $respnse[CONTENT]['type'] = $getUserInfo["type"];
                    $respnse[CONTENT]['sipFlag'] = $getUserInfo["sipFlag"];
                    $respnse[CONTENT]['userName'] = $getUserInfo["userName"];
                    $respnse[CONTENT]['tariffId'] = $getUserInfo["tariffId"];
                    return $respnse;
                } else
                    $this->response($this->json($respnse), 200);
            }
        }
        else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "1017", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    private function fbGlLogin() {
        #$accessType is 1 for facebook and 2 for google
        $accessToken = $this->_request['accessToken'];
        $accessType = $this->_request['accessType'];


        $domainResult = $this->funobj->getDomainResellerId($this->_request['resellerId'], 3);

        if (!$domainResult) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "2000", "message" => "Unable to find domain of this reseller!!"));
            $this->response($this->json($error), 200);
        }


        include_once(CLASS_DIR . "signup_class.php");
        $signupObj = new signup_class();


//            $response = $this->funobj->callApi( $accessToken , $url );
        #$accessType is 1 for facebook and 2 for google
        $response = $signupObj->checkFbGlAuthKey($accessToken, $accessType);

        if (!$response) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => $signupObj->code, "message" => $signupObj->msg));
            $this->response($this->json($error), 200);
        }

        $response = json_decode($signupObj->data, true);

//            var_dump($response);
        if ($response['verified'] == 'true' || $response['verified_email'] != "") {


            if ($signupObj->check_email_avail($response['email'], $domainResult['domainName'])) {
                if ($accessType == 2) {
                    $param['firstName'] = $response['given_name'];
                    $param['lastName'] = $response['family_name'];
                } else {
                    $param['firstName'] = $response['first_name'];
                    $param['lastName'] = $response['last_name'];
                }
                $param['email'] = $response['email'];
                $param['username'] = "rename_" . $this->funobj->randomNumber(8);
                $param['password'] = $this->funobj->randomNumber(8);
//                    $param['resellerId'] = $this->_request['resellerId'];
                $param['domain'] = $domainResult['domainName'];
                $param['tempId'] = $this->funobj->generatePassword(8);

                $param['currency'] = '84';
                $param['signupFrom'] = '1';
//                    $param['isGoogleFb'] = '1';
//                    $response = $signupObj->signUp($param);
//                    $response = json_decode($response , true);
                $response = $this->funobj->cacheSignUpDetails($param);
                // print_r($response);

                if ($response) {
                    $error = array(RESPONSE => "1", MESSAGE => array("code" => "2001", "message" => "Successfully saved details for signup"), CONTENT => array(array('tempId' => base64_encode($param['tempId']))));
                } else {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => $this->funobj->code, "message" => $this->funobj->msg));
                }
            } else {

                $userDetails = $this->funobj->getUserFromEmail($response['email'], $this->_request['resellerId'], '1');

                //print_r($userDetails);

                if ($userDetails) {
                    $emailRecord = array();

                    foreach ($userDetails as $row) {
                        //print_r($row);

                        $userInfo = $this->funobj->getUserInformation($row['userid'], 1);



                        $userId = $userInfo['userName'];
                        $password = $userInfo['password'];

                        $emailRecord[] = array('userName' => $userId, "password" => base64_encode($password), "type" => $userInfo['type']);
                    }

                    $error = array(RESPONSE => "1", MESSAGE => array("code" => "2002", "message" => "This user is already registered with us!!"), CONTENT => $emailRecord);
                } else {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "2003", "message" => "Unable to  fetch user details pleas Try Again later!!"));
                }
            }
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "2004", "message" => "Invalid token please try again later!!"));
        }



        $this->response($this->json($error), 200);
    }

    public function signUpApi() {


        $this->_request['password'] = base64_decode($this->_request['password']);
        $this->checkRegx($this->_request['resellerId'], NOTNUM_REGX, "Invalid ResellerId", '4000');
        $this->checkRegx($this->_request['firstName'], NOTPLANNAME_REGX, "Invalid First Name", '4001');
        $this->checkRegx($this->_request['lastName'], NOTPLANNAME_REGX, "Invalid Last Name", '4002');
        $this->checkRegx($this->_request['userName'], NOTUSERNAME_REGX, "Invalid User Name", '4003');
        $this->checkRegx($this->_request['password'], NOTPASSWORD_REGX, "Invalid Password", '4004');
        $this->checkRegx($this->_request['currency'], NOTNUM_REGX, "Invalid currency", '4005');
        $this->checkRegx($this->_request['countryCode'], NOTNUM_REGX, "Invalid COuntry Code", '4006');
        $this->checkRegx($this->_request['contactNumber'], NOTMOBNUM_REGX, "Invalid Contact Number ", '4007');




        $domainResult = $this->funobj->getDomainResellerId($this->_request['resellerId'], 3);

        if (!$domainResult['domainName']) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "4008", "message" => "Invalid Domain Name"));
            $this->response($this->json($error), 200);
        }

        if (!preg_match(EMAIL_REGX, $this->_request['email'])) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "4009", "message" => "Invalid email Id"));
            $this->response($this->json($error), 200);
        }

        include_once(CLASS_DIR . "contact_class.php");
        $contObj = new contact_class();


        if ($contObj->checkNumberExist($this->_request['countryCode'], $this->_request['contactNumber'], '', $this->_request['resellerId']) == 1) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "4010", "message" => "Sorry this number is already in use"));
            $this->response($this->json($error), 200);
        }

        $param['firstName'] = $this->_request['firstName'];
        ;
        $param['lastName'] = $this->_request['lastName'];
        $param['email'] = $this->_request['email'];
        $param['username'] = $this->_request['userName'];
        $param['password'] = $this->_request['password'];
        $param['currency'] = $this->_request['currency'];
        $param['domain'] = $domainResult['domainName'];
        $param['signupFrom'] = '4';

        include_once(CLASS_DIR . "signup_class.php");
        $signupObj = new signup_class();

        $response = $signupObj->signUp($param);

        $resultArr = json_decode($response, true);

        if ($resultArr['status'] == 'success') {
            $requestParm['countryCode'] = $this->_request['countryCode'];
            $requestParm['mobileNumber'] = $this->_request['contactNumber'];
            $requestParm['carrierType'] = 'SMS';

            $res = $this->funobj->getUserId($param['username']);
            $msgResponse = $signupObj->mobileVerificationBeforeLogin($requestParm, $res, $domainResult['domainName']);

            $msgResponse = json_decode($msgResponse, true);

            if ($msgResponse['status'] == 'success') {
                $error = array(RESPONSE => "1", MESSAGE => array("code" => "4011", "message" => $msgResponse['msg']), CONTENT => array(array('userId' => $res)));
                $this->response($this->json($error), 200);
            } else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "4012", "message" => $msgResponse['msg']));
                $this->response($this->json($error), 200);
            }

            //var_dump($msg);
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "4013", "message" => $resultArr['msg']));
            $this->response($this->json($error), 200);
        }
    }

    private function verifiedSignUpApi() {

        $eid = base64_decode($this->_request['tempId']);
        $currencyId = $this->_request['currency'];

        $signFrom = $this->_request['signupFrom'];
        $resellerId = $this->_request['resellerId'];

        $this->checkRegx($eid, NOTALPHANUM_REGX, "Error Invalid Id please try with valid details", '5000', 80);
        $this->checkRegx($currencyId, NOTNUM_REGX, "Error Invalid currency Id please try again", '5001', 10);
        $this->checkRegx($resellerId, NOTNUM_REGX, "Error Invalid reseller Id please try with valid details", '5002', 10);

        if (isset($this->_request['signFrom'])) {
            $this->checkRegx($signFrom, NOTNUM_REGX, "Error Invalid parameter please read api documentation", '5003', 10);
            if ($signFrom != 1) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "5004", "message" => "Error Invalid sign up details please provide valid data"));
                $this->response($this->json($error), 200);
            }
        } else {
            $signFrom = 0;
        }

        //get domain name form reseller id 
        $domainResult = $this->funobj->getDomainResellerId($this->_request['resellerId'], 3);

        if (!$domainResult) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "5005", "message" => "Unable to find domain of this reseller!!"));
            $this->response($this->json($error), 200);
        }

        /*         * *check if eid exixt in verified table** */
        include_once(CLASS_DIR . "contact_class.php");
        $cntClsObj = new contact_class();
        $verifiedNumberRes = $cntClsObj->getVerifiedNumber($eid, 1, "userId", $resellerId);
        if (!$verifiedNumberRes || count($cntClsObj->data) < 1) {
            //incase if you want to track actual reason of function fault then 
            //echo $cntClsObj->msg;
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "5006", "message" => "Error No verified number found you have to verify a number before signup"));
            $this->response($this->json($error), 200);
        }
        unset($cntClsObj);

        /*         * ******** end of verified number eid verification ************* */




        $domain = $domainResult['domainName'];
//            var_dump($domain);
//            die();
        //get details from signup cache
        $result = $this->funobj->getSignUpDetailsFromCache($eid, $domain);
//            var_dump($result);
        if (!$result) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "5007", "message" => "Error fetching details for signup please try again"));
            $this->response($this->json($error), 200);
        }
        $param = $this->funobj->data;
//            var_dump($param);
        //for case of fbgl 
        if ($signFrom) {
            $param['signupFrom'] = 1;
        }
        $param['isApi'] = 1;
        $param['currency'] = $currencyId;
        //call signup
        include_once(CLASS_DIR . "signup_class.php");
        $signupObj = new signup_class();
        $response = $signupObj->signUp($param);
        $response = json_decode($response, true);

//            var_dump($response);
//            die();
        if ($response['status'] == "success") {
            $newUserId = $signupObj->newUserId;

            $resultUpdId = $signupObj->updateVerifiedUserId($eid, $newUserId);
            if ($resultUpdId) {

                //update before login flag to 2 and check if it is set to one only then set to 2 other with donot set it 
                $beforeLoginRes = $signupObj->updateBeforeLoginFlag($newUserId, 2);
                if (!$beforeLoginRes) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => $signupObj->code, "message" => $signupObj->msg));
                    unset($signupObj);
                    $this->response($this->json($error), 200);
                }

                $content = array(array('userName' => $param['username'], 'pwd' => $param['password'], 'tempId' => base64_encode($param['tempId'])));
                //update verified table with new user Id
                $error = array(RESPONSE => "1", MESSAGE => array("code" => "5008", "message" => "Successfully signed up now login with the credentials"), CONTENT => $content);
            } else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $this->funobj->code, "message" => $this->funobj->msg));
            }
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "5009", "message" => $response['msg']));
        }
        unset($signupObj);
        $this->response($this->json($error), 200);
    }

    /*
     * @author nidhi<nidhi@walkover.in>
     */

    public function gcmApi() {
        #- getting user name from request
        $userName = $this->_request['user'];

        #- if invalid user name
        if (preg_match(NOTUSERNAME_REGX, $userName) || strlen($userName) < 5) {///[^a-zA-Z0-9\_\@\.]+/
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "102", "message" => "Invalid user name please use a valid user name");
            $this->response($this->json($error), 200);
        }

        #- getting password from request
        $password = $this->_request['password'];

        #- if invalid password.
        if (preg_match(NOTPASSWORD_REGX, $password) || strlen($password) < 5) {///[^a-zA-Z0-9\.\@\$\-\_]+/
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "103", "message" => "Invalid password please try again with a valid password");
            $this->response($this->json($error), 200);
        }

        #- getting gcmid from request.
        $gcmId = $this->_request['gcmId'];

        if (empty($gcmId) || strlen($gcmId) > 512) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "127", "message" => "Invalid Gcm Id");
            $this->response($this->json($error), 200);
        }

        $result = $this->funobj->checkLogin($userName, $password);

        if (!$result) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "105", "message" => "Unable to fetch user details please try again later");
            $this->response($this->json($error), 200);
        }
        $res = $result->num_rows;

        if ($res == '0') {
            $this->funobj->loginFailed($userName);
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "106", "message" => "Invalid Username or Password"));
            $this->response($this->json($error), 200);
        } else {
            $params['gcmId'] = $gcmId;
            $response = $this->funobj->gcmApi($params);

            $userId = $this->funobj->getUserId($userName);

            if (!$userId) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "102", "message" => "Invalid user name please use a valid user name");
                $this->response($this->json($error), 200);
            }


            $sqlMan = "INSERT INTO 91_gcmUsers (userId,gcmId) values ('" . $userId . "','" . $gcmId . "') ON DUPLICATE KEY UPDATE gcmId='" . $gcmId . "'";

            $response = $this->funobj->db->query($sqlMan);



            if ($response) {
                $error = array(RESPONSE => "1", MESSAGE => array("message" => "Registration Id Added Successfully"));
                $this->response($this->json($error), 200);
            } else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "128", "message" => "An error occoured please try again later"));
                $this->response($this->json($error), 200);
            }
        }
    }

    /**
     * 
     */
    private function forgotPasswordApiNew() {
//            ini_set("display_errors",1);
//            error_reporting(-1);
        //validation pending put validation on each param

        $particular = $this->_request['number'];
        $countryCode = $this->_request['countryCode'];
        $countryCodeArr = $this->_request['countryCodeArr'];
        $carrierType = $this->_request['carrierType'];
        $resellerId = $this->_request['resellerId'];

//            if(!preg_match(NOTPHNNUM_REGX,$particular) || empty($particular))
//            {
//                $error = array(RESPONSE => "0", MESSAGE => array( "code" => "403" , "message" => "Error invalid number pleaser try with valid number" ));
//                $this->response($this->json($error), 200);
//            }



        if ((preg_match(NOTNUM_REGX, $resellerId) || empty($resellerId) || strlen($resellerId) > 30)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "6000", "message" => "Error invalid reseller"));
            $this->response($this->json($error), 200);
        }


        if ((preg_match(NOTNUM_REGX, $carrierType) || $carrierType == "" || strlen($carrierType) > 10)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "6017", "message" => "Error invalid carrier type pelase provide a valid data"));
            $this->response($this->json($error), 200);
        }

        switch ($carrierType) {
            case "0":
                $cType = "SMS";
                break;
            case "1":
                $cType = "CALL";
                break;
            default :
                $cType = "SMS";
        }

        include_once(CLASS_DIR . "contact_class.php");
        $cntClsObj = new contact_class();


        /**
         * check how many number of times the 
         * the user tried to hit the api 
         */
        $ip = $this->funobj->getUserIp();
        $resHitOCounter = $cntClsObj->getResendCounter($ip);
        if (!$resHitOCounter) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "6018", "message" => "Error fetching ip details"));
            $this->response($this->json($error), 200);
        }
        //PENDIGN FROM HERE 
        $counters = $cntClsObj->data;


        if ($counters['dayCounter'] > 10 && $ip != "111.118.250.236") {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "6019", "message" => "Error you have exceed the max trial limit your Ip have been blocked for today"));
            $this->response($this->json($error), 200);
        }

        if ($counters['hourCounter'] > 3 && $ip != "111.118.250.236") {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "6020", "message" => "Error you have exceed the max trial limit please try after one hour"));
            $this->response($this->json($error), 200);
        }





        // prepare combination of country code and number
        if (ctype_digit($particular)) {

//                if(isset($countryCode) && (preg_match(NOTNUM_REGX,$countryCode) || empty($countryCode) || strlen($countryCode) > 10))
//                {
//                    $error = array(RESPONSE => "0", MESSAGE => array( "code" => "403" , "message" => "Error invalid countrty code " ));
//                    $this->response($this->json($error), 200);
//                }
            //set cloumn to be fetched
            $coloumn = 'userId,resellerId,countryCode,verifiedDate,verifiedNumber';


            //get verified number with single number form verifed number
            $result = $cntClsObj->getVerifiedNumber($particular, 2, $coloumn, $resellerId);
            
            $count = count($cntClsObj->data);
            if (!$result) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6001", "message" => "Error unable to get details"));
                $this->response($this->json($error), 200);
            } else if ($result && is_array($cntClsObj->data) && empty($cntClsObj->data)) {
                //case when query output is true means no fatal but there is not data found
                //prepare a combinatiaon of country code array
                foreach ($countryCodeArr as $val) {
                    $numberArr[] = $val . $particular;
                }

                # validation of $numberArr is inside the below function
                #get verified nuber when array is supplied with in operator
                $result = $cntClsObj->getVerifiedNumber($numberArr, 1, $coloumn, $resellerId);


                //count the reuslt
                $count = count($cntClsObj->data);
                if (!$result || $count < 1) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "6002", "message" => $cntClsObj->msg != '' ? $cntClsObj->msg : "Error unable to get details"));
                    $this->response($this->json($error), 200);
                }
            }


            if (1 == $count) {
                //send message on this number 
                $details = $cntClsObj->data[0];
                $numberToSendMsg = $details['countryCode'] . $details['verifiedNumber'];

                //get all the verified number for this user 
                $resultUsrAllNum = $cntClsObj->getVerifiedNumber($details['userId'], 1, $coloumn, $resellerId);

                //if there is any porblem in fetching the details then hit this api
                if (!$resultUsrAllNum) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "6016", "message" => "Error unable to fetch details for this user"));
                    $this->response($this->json($error), 200);
                }

                //variable consist of all the numbers of the user
                $allNumDetails = $cntClsObj->data;


                //update the confirmation code before sending the verification code
                $updateConfirmCodeResult = $cntClsObj->updateConformationCode($details['userId'], $numberToSendMsg, 1);
                
                
                
                if (!$updateConfirmCodeResult) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "6003", "message" => "Error unable to initilize the confirmation process"));
                    $this->response($this->json($error), 200);
                }
                /*                 * *******insert the attempt*********** */
                $resSendCnt = $cntClsObj->setResendCounter($details['userId'], 1, 1);
                if (!$resSendCnt) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "6021", "message" => "Error genrating request for sign up verification please try again later"));
                    $this->response($this->json($error), 200);
                }

                /*                 * ****************** */
                //send sms or call according to the user 
                $cntClsObj->sendSmsCall($details['verifiedNumber'], $details['countryCode'], $cntClsObj->data, $cType);

                $error = array(RESPONSE => "1", MESSAGE => array("code" => "6004", "message" => "Successfuly processed the request we have sent you a code on you number"), CONTENT => $allNumDetails);
                $this->response($this->json($error), 200);
            } else {
                //send the array of the number back to user
                $error = array(RESPONSE => "1", MESSAGE => array("code" => "6005", "message" => "Please select a number to send message"), CONTENT => $cntClsObj->data);
                $this->response($this->json($error), 200);
            }
        } elseif (filter_var($particular, FILTER_VALIDATE_EMAIL)) {
            if (!preg_match(EMAIL_REGX, $particular) || empty($particular)) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6006", "message" => "Error unable to initilize the confirmation process"));
                $this->response($this->json($error), 200);
            }
            //email logic 

            $emailResult = $cntClsObj->checkVerifiedEmailExist($particular, $resellerId);

            if (!$emailResult) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $cntClsObj->code, "message" => $cntClsObj->msg));
                $this->response($this->json($error), 200);
            }

            $userId = $cntClsObj->data['userId'];

            $numberResult = $cntClsObj->getVerifiedNumber($userId, 1, 'countryCode,verifiedNumber,userId', $resellerId);

            if (!$numberResult || empty($numberResult)) {

                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6007", "message" => "Error cannot find number for this user"));
                $this->response($this->json($error), 200);
            }

            $details = $cntClsObj->data[0];

            $numberToSendMsg = $details['countryCode'] . $details['verifiedNumber'];

            $updateConfirmCodeResult = $cntClsObj->updateConformationCode($details['userId'], $numberToSendMsg, 1);


            if (!$updateConfirmCodeResult) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6008", "message" => "Error unable to initilize the confirmation process"));
                $this->response($this->json($error), 200);
            }


            /*             * *******insert the attempt*********** */
            $resSendCnt = $cntClsObj->setResendCounter($details['userId'], 1, 1);
            if (!$resSendCnt) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6022", "message" => "Error genrating request for sign up verification please try again later"));
                $this->response($this->json($error), 200);
            }

            /*             * ****************** */

            $cntClsObj->sendSmsCall($details['verifiedNumber'], $details['countryCode'], $cntClsObj->data, $cType);
//                $cntClsObj->sendSmsCall($details['verifiedNumber'],$details['countryCode'],$cntClsObj->data,'SMS' );
            $cntClsObj->send_verification_mail($particular, $cntClsObj->data, '', '', 1);

            //send email to the user with confirmation code 
            //update existing code then send the message
            //send rest mail id to the user
            $emailResult = $cntClsObj->getConfirmEmail($userId);

            if (empty($emailResult)) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6009", "message" => "Error unable to fetch other email of this user"));
                $this->response($this->json($error), 200);
            }

            $error = array(RESPONSE => "1", MESSAGE => array("code" => "6004", "message" => "Confirmation code sent to your email and default mobile number"), CONTENT => $emailResult);
            $this->response($this->json($error), 200);
        } else {
            if (preg_match(NOTUSERNAME_REGX, $particular) || empty($particular)) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6011", "message" => "Error Invalid user name please provide a valid user name"));
                $this->response($this->json($error), 200);
            }
            $userId = $this->funobj->getUserId($particular);
            if (!$userId) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6012", "message" => "Error Invalid user please try again"));
                $this->response($this->json($error), 200);
            }

            $numberResult = $cntClsObj->getVerifiedNumber($userId, 1, 'userId,countryCode,verifiedNumber,resellerId', $resellerId);

            $details = $cntClsObj->data;
            $cnt = count($details);

            if (!$numberResult || $cnt < 1) {

                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6013", "message" => "Error cannot find number for this user"));
                $this->response($this->json($error), 200);
            }
            $code = "6005";
            if ($cnt == 1) {


                //send sms to this number
                $row = $details[0];
                $numberToSendMsg = $row['countryCode'] . $row['verifiedNumber'];
                $userId = $row['userId'];
                $updateConfirmCodeResult = $cntClsObj->updateConformationCode($userId, $numberToSendMsg, 1);

                if (!$updateConfirmCodeResult) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "6014", "message" => "Error unable to initilize the confirmation process"));
                    $this->response($this->json($error), 200);
                }

                /*                 * *******insert the attempt*********** */
                $resSendCnt = $cntClsObj->setResendCounter($userId, 1, 1);
                if (!$resSendCnt) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "6023", "message" => "Error genrating request for sign up verification please try again later"));
                    $this->response($this->json($error), 200);
                }

                /*                 * ****************** */


                $cntClsObj->sendSmsCall($row['verifiedNumber'], $row['countryCode'], $cntClsObj->data, $cType);
                $code = "6004";
            }

            $error = array(RESPONSE => "1", MESSAGE => array("code" => $code, "message" => "Please select a number to get confirmation code"), CONTENT => $details);
            $this->response($this->json($error), 200);
        }
    }

    /*
     * @author nidhi<nidhi@walkover.in>
     */

    public function forgotPasswordApi() {
        error_reporting(-1);
        #- getting parameters from request
        #- particular is for username or mobile number or Email Id
        $particular = trim($this->_request['verifyBy']);
        $smsCall = trim($this->_request['smsCall']);
        $countryCode = trim($this->_request['countryCode']);
        $userId = trim($this->_request['userId']);
        $flag = trim($this->_request['flag']);
        $domain = trim($this->_request['domain']);


        $type = $this->_request['type'];

        switch ($type) {
            case '1':

                if (is_numeric($this->_request['verifyBy'])) {
                    $error[RESPONSE] = "0";
                    $error[MESSAGE] = array("code" => "102", "message" => "Please Enter valid username !!!");
                    $this->response($this->json($error), 200);
                }

                $this->checkRegx($this->_request['verifyBy'], NOTUSERNAME_REGX, $message = "Please enter valid user name!", '102');

                break;

            case '2':
                $this->checkRegx($this->_request['verifyBy'], NOTMOBNUM_REGX, $message = "Please enter valid mobile Number", '122');
                break;

            case '3':

                if (!preg_match(EMAIL_REGX, $particular) || strlen($particular) < 5 || strlen($particular) > 30) {
                    $error[RESPONSE] = "0";
                    $error[MESSAGE] = array("code" => "144", "message" => "please Enter valid email Id !!!");
                    $this->response($this->json($error), 200);
                }

                $smsCall = 3;
                break;

            default:
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "145", "message" => "Please enter valid type!!!");
                $this->response($this->json($error), 200);
        }




        if (empty($particular)) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "144", "message" => "Please Enter mobile Number Or email Id or username.");
            $this->response($this->json($error), 200);
        }


        if (empty($smsCall)) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "140", "message" => "Please enter Proper value for sms or call. 1 - sms , 2 - call , 3 - email");
            $this->response($this->json($error), 200);
        }

        switch ($smsCall) {
            case '1':
                $smsCall = 'SMS';
                break;
            case '2':
                $smsCall = 'CALL';
                break;
            case '3':
                $smsCall = 'MAIL';
                break;
            default:
                $smsCall = 'SMS';
        }

        $response = $this->funobj->forgotPassword($particular, $smsCall, $countryCode, $userId, $flag, $domain);

        $responseParam = json_decode($response, true);


        if ($responseParam['status'] == 'success') {
            $respnse[RESPONSE] = "1";
            $respnse[MESSAGE] = array("code" => "", "message" => $responseParam['msg']);

            $respnse["type"] = $responseParam['type'];

            $respnse[CONTENT] = $responseParam["contact"];
        } else {
            $respnse[RESPONSE] = "0";
            $respnse[MESSAGE] = array("code" => "140", "message" => $responseParam['msg']);
        }
        $this->response($this->json($respnse), 200);
    }

    private function currencyList() {

//            #this line is for security in pricingController
        $_REQUEST['action'] = "";
        include_once(ROOT_DIR . "controller/pricingController.php");


        $result = $pricingController->getDefaultTariffList(null, null);
        $result = json_decode($result, 1);

        if ($result['status'] == "error")
            $error = array(RESPONSE => "1", MESSAGE => array("code" => "6100", "message" => $result['status']));
        else {
//            var_dump($result);
            foreach ($result as $key => $value) {
                $content[] = array('tariffId' => $value['tariffId'], 'currency' => $value['currency']);
            }

            if (empty($content)) {
                $response = 0;
            } else {
                $response = 1;
            }


            $error = array(RESPONSE => $response, MESSAGE => array("code" => "6101", "message" => "Currency Successfully Sent."),
                CONTENT => $content);
        }
        $this->response($this->json($error), 200);
    }

    /**
     * @uses function to send verification code
     * @author Ankit patidar <ankitpatidar@hostnsoft.com> 
     */
    function sendVerificationCode() {
        $user = $this->_request['user'];

        if (preg_match(NOTUSERNAME_REGX, $user) || strlen($user) < 5 || strlen($user) > 30) {///[^a-zA-Z0-9\_\@\.]+/
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "6200", "message" => "Invalid user name please use a valid user name");
            $this->response($this->json($error), 200);
        }

        //get user id
        $userId = $this->funobj->getUserId($user);
        /**
         * code to get verified number,if not found then get temp number 
         */
        $verifyNum = 1; //variable use to select verified or temp number
        //get confirm mobile number
        $confirmRes = $this->funobj->getConfirmNumber($userId);

        if (is_array($confirmRes)) {
            //get country code and mobile number
            $cCode = $confirmRes['countryCode'];
            $number = $confirmRes['verifiedNumber'];
        }
        //get number
        if (!$confirmRes) {
            //search temp number
            $unConfirmRes = $this->funobj->getUnConfirmNumber($userId);

            if (is_array($unConfirmRes)) {
                $verifyNum = 0;
                //get country code and mobile number
                $cCode = $confirmRes['countryCode'];
                $number = $confirmRes['tempNumber'];
            }

            if (!$unConfirmRes) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "6201", "message" => "User name not registered!!!"));
                $this->response($this->json($error), 200);
            }
        }

        //include signup class and create object
        include_once(CLASS_DIR . 'signup_class.php');
        $signUp = new signup_class();
        //set para
//            echo $signUp->mobileNumber = $number;
//            echo $signUp->countryCode = $cCode;
//            echo $signUp->userId = $userId;
        $signUp->mobileNumber = $number;
        $signUp->countryCode = $cCode;
        $signUp->userId = $userId;

        //validate parameters
        $validateResJson = $signUp->validateContactParam();

        if ($validateResJson != 1) {
            $validRes = json_decode($validateResJson, TRUE);

            if ($validRes['status'] == 'error') {
                $response[RESPONSE] = '0';
                $response[MESSAGE] = array('code' => '6202', 'message' => $validRes['msg']);
            }
        } else if ($validateResJson) {
            //get confirmation code
            $confirmCode = $this->funobj->generatePassword();



            $data = array("confirmCode" => $confirmCode);

            if ($verifyNum == 1) {
                $table = '91_verifiedNumbers';
                $condition = "userId='" . $userId . "' and verifiedNumber='" . $number . "'";
            } else {
                $table = '91_tempNumbers';
                $condition = "userId='" . $userId . "' and tempNumber='" . $number . "'";
            }

            $updRes = $this->funobj->updateData($data, $table, $condition);

            //if confirm code updated
            if ($updRes) {
                $this->funobj->sendSmsCall($number, $cCode, $confirmCode, 'SMS');
                $response[RESPONSE] = '1';
                $response[MESSAGE] = array('Verification number send to your number!!!');
                $response[CONTENT] = array('number' => $number, 'countryCode' => $cCode);
            } else {
                $response[RESPONSE] = '0';
                $response[MESSAGE] = array('code' => '6203', 'message' => 'Problem while send code!!!');
            }
        }

        $this->response($this->json($response), 200); //response
    }

    /*
     * @author nidhi<nidhi@walkover.in>
     */

    private function resendVerificationCode() {
        $smsCall = trim($this->_request['smsCall']);
        $userId = trim($this->_request['userId']);

        $this->checkRegx($userId, NOTNUM_REGX, $message = "Invalid UserId. Please provide valid user id", '6300');
        $this->checkRegx($smsCall, NOTNUM_REGX, $message = "Please enter Proper value for sms or call. 1 - sms , 2 - call , 3 - email ", '6301');

        $param['smsCall'] = $smsCall;
        $param['userId'] = $userId;

        if (isset($this->_request['type']) && $this->_request['type'] == '1') {
            $param['type'] = 'signUp';
        }

        $response = $this->funobj->resendVerfication($param);
        $response = json_decode($response, true);

        if ($response['status'] == 'success') {
            $error[RESPONSE] = "1";
            $error[MESSAGE] = array("code" => "6303", "message" => $response['message']);
            $this->response($this->json($error), 200);
        } else {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => '6304', "message" => $response['message']);
            $this->response($this->json($error), 200);
        }
    }

    /*
     * @aurhor nidhi<nidhi@walkiver.in>
     * @updated by sameer rathod
     * This fuction is to check verification code 
     */

    private function checkVerificationCode() {

        //get user name,number and confirmCode
        $countryCode = $this->_request['countryCode'];
        $number = $this->_request['number'];
        $confirmCode = $this->_request['confirmCode'];
//        $userId = $this->_request['userId'];
        $smsCall = $this->_request['carrierType'];
        $resellerId = $this->_request['resellerId'];
//        $eid = $this->_request['tempId'];
        //$this->checkRegx( $this->_request['resellerId'] , NOTNUM_REGX , $message = "Invalid ResellerId" , '141' );
        $this->checkRegx($countryCode, NOTNUM_REGX, $message = "Error invalid country code please try again", '6400', 20);
        $this->checkRegx($confirmCode, NOTNUM_REGX, $message = "Invalid Confirmation Code", '6401', 5);
        $this->checkRegx($smsCall, NOTNUM_REGX, $message = "Please enter Proper value for sms or call. 1 - sms , 2 - call , 3 - email", '6402', 2);
        $this->checkRegx($resellerId, NOTNUM_REGX, $message = "Invalid reseller Id please provide a valid reseller Id", '6403');

//        if(isset($this->_request['tempId']))
//        {
//            $this->checkRegx( $eid , NOTNUM_REGX , $message = "Error Invalid request Id please provide valid data" , '342' );
//        }
        //set perticular variable 
        $particular = $countryCode . $number;

        switch ($smsCall) {
            case '1':
                $smsCall = 'SMS';
                break;
            case '2':
                $smsCall = 'CALL';
                break;
//            case '3':
//                  $smsCall = 'EMAIL';
//                 break;
            default:
                $smsCall = 'SMS';
        }
        //case of email has been commented by sameer as it is not need now 
        //if in future it is needed then we have to uncomment this code and add
        //a login for email below under istemp variable check 
        //validate user name
//        if($smsCall == 'EMAIL')
//        {
//            if(!preg_match(EMAIL_REGX,$particular) || strlen($particular) < 5 || strlen($particular) > 30)///[^a-zA-Z0-9\_\@\.]+/
//            {
//                $error[RESPONSE] = "0";
//                $error[MESSAGE] = array("code"=>"122","message"=>"Invalid email Id please use a valid email Id !!!");
//                $this->response($this->json($error), 200);
//            }
//        }
//        else 
//        {
        if (preg_match(NOTNUM_REGX, $particular) || strlen($particular) < 5 || strlen($particular) > 30) {///[^a-zA-Z0-9\_\@\.]+/
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "6404", "message" => "Invalid mobile number or user name !!!");
            $this->response($this->json($error), 200);
        }
//        }
        //validate code
        if (preg_match(NOTNUM_REGX, $confirmCode) || strlen($confirmCode) < 4 || strlen($confirmCode) > 8) {///[^a-zA-Z0-9\_\@\.]+/
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "6405", "message" => "Invalid confirmcode please use a valid code!!!");
            $this->response($this->json($error), 200);
        }

        include_once(CLASS_DIR . "contact_class.php");
        $cntClsObj = new contact_class();

        $resultVerNum = $cntClsObj->getVerifiedNumber($particular, 2, "userId", $resellerId);

        if (!$resultVerNum) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => $cntClsObj->code, "message" => $cntClsObj->msg);
            $this->response($this->json($error), 200);
        }




        //call verfy function
        $verify = $this->funobj->verifyCode($confirmCode, $particular, $smsCall, NULL, $resellerId);

//           var_dump($verify);    
        if (!$verify) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array('code' => '6407', 'message' => 'Verification code did not match!!!');
            $this->response($this->json($error), 200);
        }

        /* this condition is here because of forgot password case
         * for signup case the number souldn't be already verified 
         * 
         * and the verify code funciton is usch that usign that 
         * function we have to write logic like this
         * 
         */

        if ($resultVerNum && !empty($cntClsObj->data) && $this->funobj->isTemp == true) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "6406", "message" => "Error this number is already verified");
            $this->response($this->json($error), 200);
        }
//         var_dump($this->funobj->isTemp);
//        die("34567");
//        if($verify)
//        { 
//            $resultUpdId = $signupObj->updateVerifiedUserId($eid,$newUserId);
        if ($this->funobj->isTemp == true) {
            $isDefault = 0;
            //check for default number
            $result = $cntClsObj->getVerifiedNumber($verify, 1, "userId", $resellerId);
            if (!$result) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array('code' => '6408', 'message' => 'Verification code did not match!!!');
                $this->response($this->json($error), 200);
            } else if (empty($cntClsObj->data)) {
                $isDefault = 1;
            }


            //enter the number in verified
            $data = array("userId" => $verify,
                "countryCode" => $countryCode,
                "verifiedNumber" => $number,
                "isDefault" => $isDefault,
                "verifiedDate" => date('Y-m-d H:i:s'),
                "domainResellerId" => $resellerId,
                "confirmCode" => $confirmCode,
                "resellerId" => $resellerId);

            #insert query (insert data into 91_tempcontact table )
            $resultInsert = $cntClsObj->insertData($data, "91_verifiedNumbers");
            if (!$resultInsert) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array('code' => '6409', 'message' => 'Error inserting code please try again later');
                $this->response($this->json($error), 200);
            }

            //delete the number form temp 
            $resultDel = $cntClsObj->deleteData("91_tempNumbers", "userId = '" . $verify . "' and concat(countryCode,tempNumber) = '" . $particular . "' and confirmCode = '" . $confirmCode . "'");
            if (!$resultDel) {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array('code' => '6410', 'message' => 'Error removing number form temp');
                $this->response($this->json($error), 200);
            }
        }

        $response[RESPONSE] = "1";
        $response[MESSAGE] = array("code" => "6411", "message" => 'This number is verified now');
        ;
        $this->response($this->json($response), 200);
//        }
//        else
//        {
//            
//        }

//        $this->response($this->json($response), 200);
    }

    private function verifyContactNumber() {
        include_once(CLASS_DIR . "contact_class.php");
        $conObj = new contact_class();

        $this->checkRegx($this->_request['userId'], NOTNUM_REGX, $message = "Invalid userId", $code = '6500');
        $this->checkRegx($this->_request['confirmCode'], NOTNUM_REGX, $message = "Invalid Confirm Code", $code = '6501');

        $userid = $this->_request['userId'];
        $key = $this->_request['confirmCode'];

        $param['key'] = $key;

        $msg = $conObj->verifyNumber($param, $userid, 1);
        $msgData = json_decode($msg, TRUE);

        if ($msgData['msgtype'] == 'success') {
            $error[RESPONSE] = "1";
            $error[MESSAGE] = array("code" => '6502', "message" => $msgData['msg']);
            $this->response($this->json($error), 200);
        } else {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => '6503', "message" => $msgData['msg']);
            $this->response($this->json($error), 200);
        }
    }

    /*
     * @author nidhi<nidhi@walkover.in>
     * This function is to reset user's password.
     * 
     */

    private function resetPassword() {
        $particular = $this->_request['verifyBy'];
        $confirmCode = $this->_request['confirmCode'];
        $resellerId = $this->_request['resellerId'];
        $smsCall = $this->_request['smsCall'];
        $newPassword = $this->_request['newPassword'];
        $key = $this->_request['key'];

        $this->checkRegx($this->_request['confirmCode'], NOTNUM_REGX, "Invalid Confirmation Code", '6600');
        $this->checkRegx($this->_request['smsCall'], NOTNUM_REGX, "Please enter Proper value for sms or call. 1 - sms , 2 - call , 3 - email", '6601');

        $this->checkRegx($this->_request['newPassword'], NOTPASSWORD_REGX, "Please Enter valid password.", '6602');

        if (empty($key)) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "6603", "message" => "Please enter valid key!!!");
            $this->response($this->json($error), 200);
        }


        switch ($smsCall) {
            case '1':
                $smsCall = 'SMS';
                break;
            case '2':
                $smsCall = 'CALL';
                break;
            case '3':
                $smsCall = 'EMAIL';
                break;
            default:
                $smsCall = 'SMS';
        }

        if ($smsCall == 'EMAIL') {
            if (!preg_match(EMAIL_REGX, $particular) || strlen($particular) < 5 || strlen($particular) > 30) {///[^a-zA-Z0-9\_\@\.]+/
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "6604", "message" => "Invalid email Id please use a valid email Id !!!");
                $this->response($this->json($error), 200);
            }
        } else {
            if (preg_match(NOTNUM_REGX, $particular) || strlen($particular) < 5 || strlen($particular) > 30) {///[^a-zA-Z0-9\_\@\.]+/
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "6605", "message" => "Invalid mobile number please use a valid number!!!");
                $this->response($this->json($error), 200);
            }
        }

        $userId = $this->funobj->verifyCode($confirmCode, $particular, $smsCall, NULL, $resellerId);

        if ($userId == base64_decode($key)) {
            $response = $this->funobj->changePassword("", $newPassword, $userId, 1);
            $response = json_decode($response, true);

            if ($response['msgtype'] == 'success') {
                $error[RESPONSE] = "1";
                $error[MESSAGE] = array("code" => "6606", "message" => 'Password changed successfully');
            } else {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "6607", "message" => $response['msg']);
            }
        } else {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "6608", "message" => "Error updating password Please try again.");
        }

        $this->response($this->json($error), 200);
    }

    /**
     * @uses API to verify code for user
     * 
     */
    private function verifyByCode() {
        //get user name,number and confirmCode
        $user = $this->_request['user'];
        $number = $this->_request['number'];
        $confirmCode = $this->_request['confirmCode'];

        //validate user name
        if (preg_match(NOTUSERNAME_REGX, $user) || strlen($user) < 5 || strlen($user) > 30) {///[^a-zA-Z0-9\_\@\.]+/
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "6700", "message" => "Invalid user name please use a valid user name!!!");
            $this->response($this->json($error), 200);
        }

        //validate user name
        if (preg_match(NOTNUM_REGX, $number) || strlen($number) < 5 || strlen($number) > 30) {///[^a-zA-Z0-9\_\@\.]+/
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "6701", "message" => "Invalid mobile number please use a valid number!!!");
            $this->response($this->json($error), 200);
        }

        //validate code
        if (preg_match(NOTNUM_REGX, $confirmCode) || strlen($confirmCode) < 4 || strlen($confirmCode) > 8) {///[^a-zA-Z0-9\_\@\.]+/
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "6702", "message" => "Invalid confirmcode please use a valid code!!!");
            $this->response($this->json($error), 200);
        }
        //get user id
        $userId = $this->funobj->getUserId($user);
        //call verfy function
        $verify = $this->funobj->verifyCode($confirmCode, $number);

        if ($verify) {
            ////get random string
            $randomNum = (strtotime(gmdate("d/m/Y H:i:s")));

            $resultRand = $this->funobj->selectData("userId", "91_randomApiStr", "userId='" . $userId . "'");

            if ($resultRand->num_rows > 0) {
                //update info for this user id
                $data = array("randomStr" => md5($randomNum),
                    "date" => date('Y-m-d H:i:s'));
                $condition = "userId='" . $userId . "'";

                $res = $this->funobj->updateData($data, "91_randomApiStr", $condition);
            } else {
                //insert details into 91_randomApiStr
                //data to insert
                $data = array('userId' => $userId,
                    'randomStr' => $randomNum,
                    'date' => date('Y-m-d H:i:s'));
                $res = $this->funobj->insertData($data, '91_randomApiStr');
            }

            if (!$res) {
                $response[RESPONSE] = "0";
                $response[MESSAGE] = array('code' => '6703', 'message' => 'Problem while verification try again!!!');
            } else {
                $response[RESPONSE] = "1";
                $response[MESSAGE] = "user verified!!!";
                $response[CONTENT] = array('scretCode' => $randomNum);
            }
        } else {
            $response[RESPONSE] = "0";
            $response[MESSAGE] = array('code' => '6704', 'message' => 'Verification code did not match!!!');
        }

        $this->response($this->json($response), 200);
    }

    private function balance() {
        $response = $this->login(true);
        if ($response[RESPONSE] == "1") {
            //$funobj = new fun();
            $result = $this->funobj->selectData('balance,currencyId', '91_userBalance', "userId = '" . $response["id"] . "'  ");
//                        $funobj->db->select('balance,currencyId')->from('91_userBalance')->where("userId = '" . $response["id"] . "'  ");
//			$result = $funobj->db->execute();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
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
                $returnResult[MESSAGE] = array("code" => "6800", "message" => "Success");
                $returnResult[CONTENT][] = $balanceRes;
                $this->response($this->json($returnResult), 200);
            }
        }
        $error = array(RESPONSE => "0", MESSAGE => array("code" => "6801", "message" => "Incomplete Details"));
        $this->response($this->json($error), 200);
    }

    private function getDirectBalance() {
        $response = $this->login(true, "auth");
        if ($response[RESPONSE] == "1") {
            //$funobj = new fun();
            $result = $this->funobj->selectData('balance,currencyId', '91_userBalance', "userId = '" . $response["id"] . "'  ");

            $balanceStr = '';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $balanceStr.= round($row[BALANCE], 1);
                    $currencyId = $row["currencyId"];
                    $balanceRes["currencyCode"] = $currencyId;
                    $balCurr = $this->funobj->getCurrencyViaApc($currencyId);

                    //validation for currency
                    if (isset($balCurr) and $balCurr != '') {
                        $balanceStr.= ' ' . $this->funobj->getCurrencyViaApc($currencyId);
                    } else {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => "6900", "message" => 'Problem while getting currency!!!'));
                        $this->response($this->json($error), 200);
//                                        echo 'Problem while getting currency!!!';
                        exit();
                    }
                }

                echo $balanceStr;
                exit();
            }
        }

        $error = array(RESPONSE => "0", MESSAGE => array("code" => "6901", "message" => '404 balance not found!!!'));
        $this->response($this->json($error), 200);
//		echo $error = '404 balance not found!!!';           
        exit();
    }

    private function twowaycalling() {

        $source = $this->_request['source'];
        $dest = $this->_request['dest'];
        if (strlen($source) < 8 || strlen($source) > 19 || preg_match(NOTNUM_REGX, $source)) {//'/[^0-9]+/'
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7000", "message" => "Invalid Source Number"));
            $this->response($this->json($error), 200);
        }
        if (strlen($dest) < 8 || strlen($dest) > 19 || preg_match(NOTNUM_REGX, $dest)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7001", "message" => "Invalid Destination Number"));
            $this->response($this->json($error), 200);
        }

        $response = $this->login(true);
        if ($response[RESPONSE] == "1") {
            $nine["login"] = $this->_request['user'];
            $nine["password"] = $this->_request['password'];
            if ($nine["login"] == "" || $nine["password"] == "") {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7002", "message" => "Invalid Credentials Please enter a proper userId and password"));
                $this->response($this->json($error), 200);
            }

            include_once $_SERVER["DOCUMENT_ROOT"] . '/definePath.php';
            include(CLASS_DIR . "call_class.php");
            $call_obj = new call_class();
            //$funobj = new fun();

            $nine["dest"] = $dest;
            $nine["source"] = $source;

            $msgid = $call_obj->Call($nine);

            $msgid = str_replace('"', '', substr(substr($msgid, 0, -1), 1));

            $msgIdArr = explode(",", $msgid);


            foreach ($msgIdArr as $val) {
                $subArr = explode(":", $val);
                $newArrMsgId[$subArr[0]] = $subArr[1];
            }

            $returnResult[RESPONSE] = "1";
            $returnResult[MESSAGE] = array("code" => "7003", "message" => "success");
            $returnResult[CONTENT][] = $newArrMsgId;
            $this->response($this->json($returnResult), 200);
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7004", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    /**
     * @uses function to list the clients 
     */
    private function listclients() {

        $response = $this->login(true);

        if ($response[RESPONSE] == "1") {

            $columns = 'userName,type,balance,currencyId,isBlocked';
            $table = '91_manageClient';
            $condition = "resellerId = '" . $response["id"] . "' and (type = 2 or type = 3)";
            $result = $this->funobj->selectData($columns, $table, $condition);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $returnResult[CLIENTUSERNAME] = $row["userName"];
                    $returnResult[BALANCE] = $row["balance"];
                    $status = $row["isBlocked"];

                    $type = $row["type"];

                    if ($type == 1) {
                        $type = "admin";
                    } else if ($type == 2) {
                        $type = "reseller";
                    } else if ($type == 3) {
                        $type = "user";
                    }

                    $returnResult[TYPE] = $type;

                    $returnResult[CURRENCY] = $this->funobj->getCurrencyViaApc($row["currencyId"]);

                    if ($status != 1) {
                        $status = "Disabled";
                    } else {
                        $status = "Active";
                    }

                    $returnResult[STATUS] = $status;

                    $finalResponse[] = $returnResult;
                }

                if (empty($finalResponse)) {
                    $response = 0;
                } else
                    $response = 1;

                $finalResponseArr[RESPONSE] = $response;
                $finalResponseArr[MESSAGE] = array("code" => "7100", "message" => "Success");
                $finalResponseArr[CONTENT] = $finalResponse;
                $this->response($this->json($finalResponseArr), 200);
            }
            else {
                $response = array(RESPONSE => "0", MESSAGE => array("code" => "7001", "message" => "Client detail not found!"));
                $this->response($this->json($response), 200);
            }
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7002", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    private function changepassword() {
        $response = $this->login(true);
        $pinFlag = 0;
        //check login response
        if ($response[RESPONSE] == "1") {
            $new_pwd = $this->_request['newPassword'];

            if (empty($new_pwd) || strlen($new_pwd) < 4 || strlen($new_pwd) > 20) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7200", "message" => "Incomplete Details Password Must not be empty and should be more then 3 character"));
                $this->response($this->json($error), 200);
                exit();
            }
            
            if(strlen($new_pwd) == 4){
              if (preg_match(NOTNUM_REGX, $new_pwd)) {//'/[^a-zA-Z0-9\@\$\.\_\-]/'
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "7201", "message" => "Invalid pin must be numeric and of 4 character"));
                    $this->response($this->json($error), 200);
                    exit();
                }
                $pinFlag = 1;
                
            }else{
                
            if (preg_match(NOTPASSWORD_REGX, $new_pwd)) {//'/[^a-zA-Z0-9\@\$\.\_\-]/'
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7201", "message" => "Invalid password must not contain any character other then (a-z,A-Z,0-9,@,$,.,_,-)"));
                $this->response($this->json($error), 200);
                exit();
            }
            }



            $new_pwd = $this->funobj->db->real_escape_string($new_pwd);
            if($pinFlag){
                $data = array('userPin' => $new_pwd);
                $result = $this->funobj->updateData('91_userLogin', $data, "userId = '" . $response["id"] . "' ");

            }else{
            $data = array('password' => $new_pwd);
            $result = $this->funobj->updateWithEncryption('91_userLogin', $data, "userId = '" . $response["id"] . "' ");
            }
            
            if ($result) {
                if ($response[CONTENT]['sipFlag'] && !$pinFlag) {
                    $dataSip = array("passwd" => $new_pwd);

                    $resultSip = $this->funobj->updateData($dataSip, "91_verifiedSipId", "userId = '" . $response["id"] . "' ");

                    if ($resultSip) {
//                                    ob_start();
//                                    $res = sip_delete($response[CONTENT]['userName']);
//                                    $res2 = sip_add($response[CONTENT]['userName'],$new_pwd);
//                                    ob_end_clean();
                        $this->funobj->enableSip($response[CONTENT]['userName'], 1);
                    }
                }

                $returnResult = array(RESPONSE => "1", MESSAGE => array("code" => "7202", "message" => "Update Successfully"));
                $this->response($this->json($returnResult), 200);
            } else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7203", "message" => "Unable to update at this time"));
                $this->response($this->json($error), 200);
            }
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7204", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    /**
     * @uses function to change client password 
     */
    private function changeclientpassword() {
        $response = $this->login(true);
        if ($response[RESPONSE] == "1") {
            $login = $this->_request['clientusername'];
            if (empty($login) || strlen($login) < 5) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7301", "message" => "Incomplete Details Username Must be more than 5 Character"));
                $this->response($this->json($error), 200);
                exit();
            }

            $new_pwd = $this->_request['newPassword'];
            if (empty($new_pwd) || strlen($new_pwd) < 5 || preg_match('/[^a-zA-Z0-9\.\@\$\-\_\?]/', $new_pwd)) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7302", "message" => "Incomplete Details Password Must be more than 5 Character"));
                $this->response($this->json($error), 200);
                exit();
            }

            //$funobj = new fun();
            $new_pwd = $this->funobj->db->real_escape_string($new_pwd);
            $login = $this->funobj->db->real_escape_string($login);

            $result = $this->funobj->selectData('userId', '91_manageClient', "userName = '" . $login . "'");

            if ($result) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $data = array('password' => $new_pwd);
                //$resUpd = $this->funobj->updateData($data, '91_userLogin','userId = '.$row['userId']);
                $resUpd = $this->funobj->updateWithEncryption('91_userLogin', $data, 'userId = ' . $row['userId']);

                if ($resUpd && $this->funobj->db->affected_rows == 1) {
                    $returnResult = array(RESPONSE => "1", MESSAGE => array("code" => "7303", "message" => "Update Successfully"));
                    $this->response($this->json($returnResult), 200);
                } else {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "7304", "message" => "Unable to update at this time"));
                    $this->response($this->json($error), 200);
                }
            }

//                        echo $sql ="update 91_userLogin set password='".$new_pwd."' where userId in (select userId from 91_userBalance where resellerId='".$response["id"]."') and 91_userLogin.userName='" . $login . "'";
//                        $funobj->db->query($sql);
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7305", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    /**
     * @uses function to change client password 
     */
    private function getTwoWayCallRate() {
        $response = $this->login(true);
        if ($response[RESPONSE] == "1") {
//                	$login = $this->_request['clientusername'];
//                        $this->checkRegx( $this->_request['source'] , NOTPHNNUM_REGX , $message = "Invalid Source Number please enter a valid number" , $code = '601' );
//                        $this->checkRegx( $this->_request['destination'] , NOTPHNNUM_REGX , $message = "Invalid destination Number please enter a valid number" , $code = '602' );

            if (preg_match(NOTNUM_REGX, $this->_request['source']) || strlen($this->_request['source']) < 7 || strlen($this->_request['source']) > 18) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7400", "message" => "Invalid source number"));
                $this->response($this->json($error), 200);
            }
            if (preg_match(NOTNUM_REGX, $this->_request['destination']) || strlen($this->_request['destination']) < 5 || strlen($this->_request['destination']) > 18) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7401", "message" => "Invalid destination number"));
                $this->response($this->json($error), 200);
            }

            include_once CLASS_DIR . 'call_class.php';
            $callClsObj = new call_class();

            $parm['source'] = $this->_request['source'];
            $parm['destination'] = $this->_request['destination'];
            $id_tariff = $response[CONTENT]['tariffId'];
            $finalResponse = $callClsObj->seeCallRate($parm, $id_tariff);

            $finalResponseArr[RESPONSE] = "1";
            $finalResponseArr[MESSAGE] = array("code" => "7402", "message" => "success");
            $finalResponseArr[CONTENT] = json_decode($finalResponse);
            $this->response($this->json($finalResponseArr), 200);
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7403", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    /**
     * @uses function to change client password 
     */
    private function getTwoWayCallResponse() {
        $response = $this->login(true);

        if ($response[RESPONSE] == "1") {
//                	$login = $this->_request['clientusername'];
//                        $this->checkRegx(  , NOTPHNNUM_REGX , $message = "Invalid Source Number please enter a valid number" , $code = '601' );
//                        $this->checkRegx( $this->_request['destination'] , NOTPHNNUM_REGX , $message = "Invalid destination Number please enter a valid number" , $code = '602' );

            include_once CLASS_DIR . 'call_class.php';
            $callClsObj = new call_class();

            $userId = $response['id'];
            $parm['uniqueId'] = $this->_request['uniqueId'];
            $finalResponse = $callClsObj->callResponse($parm, $userId);
            $finalResponse = json_decode($finalResponse, TRUE);
//                        var_dump($finalResponse);
            if ($finalResponse['status'] == "success") {
                $finalResponseArr[RESPONSE] = "1";
                $finalResponseArr[MESSAGE] = array("code" => "7500", "message" => "success");
                $finalResponseArr[CONTENT] = $finalResponse['msg'];
                $this->response($this->json($finalResponseArr), 200);
            } else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7501", "message" => $finalResponse['msg']));
                $this->response($this->json($error), 200);
            }
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7502", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    /* This api is wrong and wont function correctly */

    private function updateclientbalance() {

        $response = $this->login(true);
        if ($response[RESPONSE] == "1") {
            $login = $this->_request['clientusername'];

            include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/reseller_class.php";
            $resClsObj = new reseller_class();

            $clientUserId = $resClsObj->getUserId($login);
            $param['toUserEditFund'] = $clientUserId;
            $param['fundAmount'] = trim($this->_request['receivingAmount']);
            $param['balance'] = trim($this->_request['balance']);
            $param['partialAmt'] = trim($this->_request['partialAmt']);

            $action = trim($this->_request['action']);
            if ($action == "1") {
                $param['changefunderEditFund'] = "add";
            } elseif ($action == "0") {
                $param['changefunderEditFund'] = "reduce";
            }


            $param['otherPaymentType'] = trim($this->_request['otherPaymentType']);

            $paymentType = trim($this->_request['paymentType']);

            if ($paymentType == "0") {
                $param['pType'] = "prepaid";
            } elseif ($paymentType == "1") {
                $param['pType'] = "partial";
            } elseif ($paymentType == "2")
                $param['pType'] = "postpaid";


            $paymentMode = trim($this->_request['paymentMode']);

            if ($paymentMode == "0") {
                $param['fundPaymentType'] = "Cash";
            } elseif ($paymentMode == "1") {
                $param['fundPaymentType'] = "Memo";
            } elseif ($paymentMode == "2") {
                $param['fundPaymentType'] = "Bank";
            } elseif ($paymentMode == "3")
                $param['fundPaymentType'] = "Other";
            else
                $param['fundPaymentType'] = "Cash";

            $param['fundDescription'] = trim($this->_request['description']);
            $param['fundCurrency'] = trim($this->_request['amountCurrency']);
            $param['partialCurrency'] = trim($this->_request['partialAmtCurrency']);

            $fundResponse = $resClsObj->editFund($param, $response[CONTENT]['id']);
            $fundResponse = json_decode($fundResponse);


            if ($fundResponse->status == "success") {
                $error[RESPONSE] = "1";
            } else {
                $error[RESPONSE] = "0";
            }
            $error[MESSAGE]['code'] = '101';
            $error[MESSAGE]['message'] = $fundResponse->msg;
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
     * @uses function to add and remove sip 
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     */
    private function addRemoveSip() {
        //validate login details
        $response = $this->login(true);

        //check login response
        if ($response[RESPONSE] == "1") {
//                    var_dump("here");
            $action = (isset($this->_request['action']) && $this->_request['action'] != '') ? $this->_request['action'] : '';
            //get userName
            $userName = $this->_request['user'];

            //get user id
            $userId = $this->funobj->getUserId($userName);

            if (isset($userId))
                $result = $this->funobj->enableSip($userId, $action);

            //validate result
            if (isset($result)) {
                $resArr = json_decode($result, TRUE); //get array
                //set success and error msg for response
                if ($resArr['status'] == 'success') {
                    $response[RESPONSE] = '1';
                    $response[MESSAGE] = array("code" => "7600", "message" => $resArr['msg']);
                } else {
                    $response[RESPONSE] = '0';
                    $response[MESSAGE] = array('code' => '7601', 'message' => $resArr['msg']);
                }
                $this->response($this->json($response), 200);
            } else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7602", "message" => "Problem while sip operation!!!"));
                $this->response($this->json($error), 200);
            }
        } else { //if login response not valid then give error response
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7603", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    private function uploadLog() {
        $response = $this->login(true);

        //check login response
        if ($response[RESPONSE] == "1") {
//             $fileName = $this->_request['fileName'];   
            $file = $_FILES['file'];
            $extension = end(explode(".", $file['name']));
            if ($extension != "txt" || preg_match('/[^a-zA-Z0-9\.]/', $file['name']) || empty($file['name'])) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "7700", "message" => "Invalid file please provide a valid text file"));
                $this->response($this->json($error), 200);
            } else {
                $prefix = date("dmY h:i:s");
                $fileName = $this->_request['user'] . $prefix . $file['name'];
                if (file_exists("../../logFiles/" . $fileName)) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "7701", "message" => "Error file already exist with this name please try with different file name"));
                    $this->response($this->json($error), 200);
                }

                if (move_uploaded_file($file['tmp_name'], "../../logFiles/" . $fileName)) {
                    $error = array(RESPONSE => "1", MESSAGE => array("code" => "7702", "message" => "File Successfully Uploaded"));
                    $this->response($this->json($error), 200);
                } else {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "7703", "message" => "Error uploading the file please try again "));
                    $this->response($this->json($error), 200);
                }
            }
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7704", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    private function getPlanList() {
        $response = $this->login(true);
        if ($response[RESPONSE] == "1") {
            include_once(CLASS_DIR . "plan_class.php");
            $planOnj = new plan_class();
            $userId = $response['id'];
            $result = $planOnj->getPlanName("planName,tariffId", $userId, 2, null, 0);
            $result = json_decode($result, true);
            if ($result['status'] == "error") {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $result['code'], "message" => $result['msg']));
                $this->response($this->json($error), 200);
            } else {
                $finalResponseArr[RESPONSE] = "1";
                $finalResponseArr[MESSAGE] = array("code" => 550, "message" => 'success');
                $finalResponseArr[CONTENT] = $result;
                $this->response($this->json($finalResponseArr), 200);
            }
        }
    }

    private function createPinPassword() {
        //take number and confirm code check in verified table 
        //check the confirm code if true then update the ping of the user
//             $countryCode = $this->_request['countryCode'];
        $number = $this->_request['number'];
        $confirmCode = $this->_request['confirmCode'];
        $resellerId = $this->_request['resellerId'];
        $pin = $this->_request['pin'];

//             if(!preg_match(NOTPHNNUM_REGX,$number) || preg_match(NOTNUM_REGX,$countryCode) || empty($number)){
        if (!preg_match(PHNNUM_REGX, $number) || empty($number)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7900", "message" => "Error invalid contact number please provide valid data"));
            $this->response($this->json($error), 200);
        }

        if (preg_match(NOTNUM_REGX, $confirmCode) || empty($confirmCode) || strlen($confirmCode) > 6 || strlen($confirmCode) < 3) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7901", "message" => "Error invalid confrirm Code please provide valid data"));
            $this->response($this->json($error), 200);
        }
        if (preg_match(NOTNUM_REGX, $resellerId) || empty($resellerId) || strlen($resellerId) > 10) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7902", "message" => "Error invalid reseller please try again with valid reseller Id"));
            $this->response($this->json($error), 200);
        }
        if (preg_match(NOTNUM_REGX, $pin) || empty($pin) || strlen($pin) != 4) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7903", "message" => "Error invalid pin only numeric 4 digit pin is allowed"));
            $this->response($this->json($error), 200);
        }

        include_once(CLASS_DIR . 'contact_class.php');
        $cntClsObj = new contact_class();
        $particular = $number;
//             $result = $cntClsObj->checkVerifiedNumberExist($particular,$resellerId,0);
        $result = $cntClsObj->getVerifiedNumber($particular, 2, 'userId,confirmCode', $resellerId);
//             var_dump($cntClsObj->data);
        if (!$result || count($cntClsObj->data) < 1) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => $cntClsObj->code, "message" => $cntClsObj->msg));
            $this->response($this->json($error), 200);
        }

        $conCode = $cntClsObj->data[0]['confirmCode'];

        if ($confirmCode != $conCode) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "7904", "message" => "Error confirm code mismatch"));
            $this->response($this->json($error), 200);
        }

        $userId = $cntClsObj->data[0]['userId'];

        //update pin in login table 
        $updatePinRes = $cntClsObj->updateUserLoginPin($pin, $userId);

        if (!$updatePinRes) {

            $error = array(RESPONSE => "0", MESSAGE => array("code" => $cntClsObj->code, "message" => $cntClsObj->msg));
            $this->response($this->json($error), 200);
        }
        $error = array(RESPONSE => "1", MESSAGE => array("code" => "7905", "message" => "successfuly updated user pin"));
        $this->response($this->json($error), 200);
    }

    private function signUpWithNumber() {

        //add currency parameter here
        //take it form user
        ini_set('display_errors', 1);
        error_reporting(-1);
        include_once(CLASS_DIR . "contact_class.php");
        $cntClsObj = new contact_class();

        $countryCode = $this->_request['countryCode'];
        $number = $this->_request['number'];
        $carrierType = $this->_request['carrierType'];
//             $sendSms = $this->_request['sendSms'];

        if (isset($this->_request['tempId'])) {
            $eid = base64_decode($this->_request['tempId']);

            if (preg_match(NOTALPHANUM_REGX, $eid) || empty($eid)) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "8000", "message" => "Error invalid Id"));
                $this->response($this->json($error), 200);
            }
        }


        if (preg_match(NOTNUM_REGX, $countryCode) || empty($countryCode)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "8001", "message" => "Error invalid country code please try again"));
            $this->response($this->json($error), 200);
        }

        if (preg_match(NOTNUM_REGX, $carrierType) || $countryCode == "" || strlen($carrierType) > 10) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "8001", "message" => "Error invalid carrierType please provide valid data"));
            $this->response($this->json($error), 200);
        }

        switch ($carrierType) {
            case "0":
                $cType = 'SMS';
                break;
            case "1":
                $cType = 'CALL';
                break;
            default :
                $cType = 'SMS';
                break;
        }


        $domainName = $_SERVER['HTTP_HOST'];
        if (isset($this->_request['resellerId']))
            $resellerId = $this->_request['resellerId'];
        else {
            
            $resellerId = $cntClsObj->getDomainResellerIdViaApc($domainName);
        }

        $contactNumber = $countryCode . $number;
//             $result = $cntClsObj->checkVerifiedNumberExist($contactNumber,$resellerId,$sendSms,$countryCode);
        $result = $cntClsObj->getVerifiedNumber($contactNumber, 2, "userId,resellerId,countryCode,verifiedDate,verifiedNumber", $resellerId);
        if (!$result) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => $cntClsObj->code, "message" => $cntClsObj->msg));
            $this->response($this->json($error), 200);
        }
        if ($result && count($cntClsObj->data) > 0) {
            //return number is verified
            //or the response accordingly
            $finalResponseArr[RESPONSE] = "1";
            $finalResponseArr[MESSAGE] = array("code" => "8002", "message" => 'Number is already verified');
            $finalResponseArr[CONTENT] = $cntClsObj->data;
            $this->response($this->json($finalResponseArr), 200);
        } else {

            /**
             * check how many number of times the 
             * the user tried to hit the api 
             */
            $ip = $cntClsObj->getUserIp();
            
            $resHitOCounter = $cntClsObj->getResendCounter($ip);
            
            if (!$resHitOCounter) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $cntClsObj->code, "message" => $cntClsObj->msg));
                $this->response($this->json($error), 200);
            }
            //PENDIGN FROM HERE 
            $counters = $cntClsObj->data;
//                 var_dump($counters);

            if ($counters['dayCounter'] > 10) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "8010", "message" => "Error you have exceed the max trial limit your Ip have been blocked for today"));
                $this->response($this->json($error), 200);
            }

            if ($counters['hourCounter'] > 3) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "8009", "message" => "Error you have exceed the max trial limit please try after one hour"));
                $this->response($this->json($error), 200);
            }

//               if($cntClsObj->code == "404")
//               {

            $domainResult = $cntClsObj->getDomainResellerId($resellerId, 3);
            
//                    var_dump($domainResult);
//                    die();
            if (!$domainResult) {

                $error = array(RESPONSE => "0", MESSAGE => array("code" => "8003", "message" => "Error fetching domain details"));
                $this->response($this->json($error), 200);
            }

            if (is_null($eid)) {
                $eid = $this->funobj->generatePassword(8);
                
                if (empty($eid)) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "8005", "message" => "Internal server error"));
                    $this->response($this->json($error), 200);
                }
                
                
                
                $param['firstName'] = "NameRequired";
                $param['lastName'] = "familyNamerequired";
                $param['email'] = $number . "@update.com";
                $param['username'] = "rename_" . $cntClsObj->randomNumber(8);
                $param['password'] = $cntClsObj->randomNumber(8);
                //                    $param['resellerId'] = $this->_request['resellerId'];
                $param['domain'] = $domainResult['domainName'];
                $param['tempId'] = $eid;

                $param['currency'] = '84';
                $param['signupFrom'] = '1';
//print_R($param);
                $response = $cntClsObj->cacheSignUpDetails($param);
//                        var_dump($response);
//                        echo $cntClsObj->msg;
                if (!$response) {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => "8004", "message" => "Error genrating request for sign up please try again later"));
                    $this->response($this->json($error), 200);
                }
            }
            else{
                /*check if eid exist in the table or not */
                $checkEidResp = $cntClsObj->getSignUpDetailsFromCache($eid,$domainName);
            }

            if ($eid == "") {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "8005", "message" => "Internal server error"));
                $this->response($this->json($error), 200);
            }
            /*             * ***************** */
//                    $confirmCode = $cntClsObj->generatePassword();
//                    
////                    $number = substr($particular, strlen($countryCode));
//                    $tempData = array(
//                        "userId"=>$eid,
//                        "domainResellerId"=>$resellerId,
//                        "countryCode"=>$countryCode,
//                        "tempNumber"=>$number,
//                        "confirmCode"=>$confirmCode,
//                        "resellerId"=>$resellerId,
//                    );
//                    $cntClsObj->db->insert("91_tempNumbers",$tempData)->onDuplicate(" confirmCode=".$confirmCode);
//                    $inserRes = $cntClsObj->db->execute();
            /*             * *************** */

            /*             * *******insert the attempt*********** */
            $resSendCnt = $this->funobj->setResendCounter($eid, 1, 1);
//                    var_dump($resSendCnt);
//                    die("dsafa");
            if (!$resSendCnt) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "8011", "message" => "Error genrating request for sign up verification please try again later"));
                $this->response($this->json($error), 200);
            }

            /*             * ****************** */
            include_once CLASS_DIR . 'signup_class.php';
            $signupObj = new signup_class();

            $req['mobileNumber'] = $number;
            $req['countryCode'] = $countryCode;
            $req['carrierType'] = "SMS";
            $signupObj->resellerId = $resellerId;


            $inserRes = $signupObj->mobileVerificationBeforeLogin($req, $eid);
            $inserRes = json_decode($inserRes, true);

            unset($signupObj);
            if ($inserRes['status'] == "error") {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "8006", "message" => "Error genrating request for sign up verification please try again later"));
                $this->response($this->json($error), 200);
            }

//                    //set the eid for giving it in response
//                    $this->setData(array("eid"=>$tempId));
//                    $cntClsObj->sendSmsCall($number,$countryCode,$confirmCode,'SMS' );
            //do entry in temp table   
            //send sms on this number
//               }
            $error = array(RESPONSE => "1", MESSAGE => array("code" => "8007", "message" => "successfuly saved the request for registration"));
            $error[CONTENT][] = array("tempId" => base64_encode($eid));
            $this->response($this->json($error), 200);
        }
    }

//    public function listOfAllContacts() {
//        include_once(CLASS_DIR . "phonebook_class.php");
//        $phoneObj = new phonebook_class();
//
//
//        $response = $this->login(true);
//        if ($response[RESPONSE] == "1") {
//            $userId = $response["id"];
//
//            $this->checkRegx($userId, NOTNUM_REGX, "Invalid User Id!", '143');
//
//            $response = $phoneObj->getAllContact($userId);
//            $allContactArr = $response['allcontact'];    //allcontact
//            // print_r($allContactArr);
//
//            $contactArray = array();
//
//            foreach ($allContactArr as $contactDetail) {
//
//                $contactArray[] = array("contactNumber" => (isset($contactDetail['contactNo']) ? $contactDetail['contactNo'] : ""),
//                    "contactName" => (isset($contactDetail['name']) ? $contactDetail['name'] : ""),
//                    "accessNumber" => (isset($contactDetail['accessNo']) ? $contactDetail['accessNo'] : ""),
//                    "hash" => (isset($contactDetail['hash']) ? $contactDetail['hash'] : ""),
//                    "code" => (isset($contactDetail['code']) ? $contactDetail['code'] : ""),
//                    "code" => (isset($contactDetail['email']) ? $contactDetail['email'] : ""),
//                    "contactId" => (isset($contactDetail['contact_id']) ? (string) $contactDetail['contact_id'] : ""));
//            }
//
//            if (!empty($contactArray)) {
//                $respnse[RESPONSE] = "1";
//                $respnse[MESSAGE] = array("code" => "", "message" => count($contactArray) . "contacts found successfully!!");
//                $respnse[CONTENT] = $contactArray;
//            } else {
//                $respnse[RESPONSE] = "0";
//                $respnse[MESSAGE] = array("code" => "143", "message" => "Contacts not found!!");
//            }
//
//            $this->response($this->json($respnse), 200);
//        }
//        $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Incomplete Details"));
//        $this->response($this->json($error), 200);
//    }

    function listOfAllContacts() {
        include_once(CLASS_DIR . "phonebook_class.php");
        $phoneObj = new phonebook_class();


        $response = $this->login(true);

        if ($response[RESPONSE] == "1") {
            $userId = $response["id"];

            $contactTime = $this->_request['contactTime'];
            ;

            $contactTime = date('Y-m-d H:i:s', $contactTime);
            ;



            $time = $phoneObj->getLastUpdateTime($userId);


            /// echo $contactTime;  echo '<br>'; echo $time;

            if ($time && strtotime($contactTime) < strtotime($time) || $time == 0 || empty($contactTime)) {
                $this->checkRegx($userId, NOTNUM_REGX, "Invalid User Id!", '143');

                $status = $this->_request['contactsWithAccess'];
                if($status == 1){
                 $response =  $phoneObj->getAllContact($userId,0,1);
                }else
                  $response =  $phoneObj->getAllContact($userId);   

                $allContactArr = $response['allcontact'];    //allcontact
                // print_r($allContactArr);

                $contactArray = array();
                foreach ($allContactArr as $contactDetail) {

                    $contactArray[] = array("contactNumber" => (isset($contactDetail['contactNo']) ? $contactDetail['contactNo'] : ""),
                        "contactName" => (isset($contactDetail['name']) ? $contactDetail['name'] : ""),
                        "accessNumber" => (isset($contactDetail['accessNo']) ? $contactDetail['accessNo'] : ""),
                        "hash" => (isset($contactDetail['hash']) ? $contactDetail['hash'] : ""),
                        "code" => (isset($contactDetail['code']) ? $contactDetail['code'] : ""),
                        "email" => (isset($contactDetail['email']) ? $contactDetail['email'] : ""),
                        "contactId" => (isset($contactDetail['contact_id']) ? (string) $contactDetail['contact_id'] : ""));
                }

                if ($time == 0) {
                    $phoneObj->addLastUpdateTime($userId);
                }


                if (!empty($contactArray)) {
                    $respnse[RESPONSE] = "1";
                    $respnse[MESSAGE] = array("code" => "144", "message" => count($contactArray) . " contacts found successfully!!");
                    $respnse[CONTENT] = $contactArray;
                } else {
                    $respnse[RESPONSE] = "0";
                    $respnse[MESSAGE] = array("code" => "143", "message" => "Contacts not found!!");
                }
            } else {
                $respnse[RESPONSE] = "0";
                $respnse[MESSAGE] = array("code" => "143", "message" => "There is no updated data. ");
            }
            $this->response($this->json($respnse), 200);
        }
        $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Incomplete Details"));
        $this->response($this->json($error), 200);
    }

   public function addContact() {
        include_once(CLASS_DIR . "phonebook_class.php");
        $phoneObj = new phonebook_class();



        $response = $this->login(true);
        if ($response[RESPONSE] == "1") {
            $userId = $response["id"];


            $parm['name'][] = $this->_request['name'];
            $parm['email'][] = $this->_request['email'];
            $parm['conAccessNumber'][] = $this->_request['conAccessNumber'];
            $parm['extensionNumber'][] = $this->_request['extensionNumber'];


            $contact = $this->_request['contact'];
            $numberWithCode = $this->funobj->getCountryCodeAndNumber($contact);
            $parm['code'][] = $numberWithCode['code'];
            $parm['contact'][] = $numberWithCode['number'];

            $this->checkRegx($userId, NOTNUM_REGX, "Please enter valid User Id!", '152');

            $response = $phoneObj->addContact($parm, $userId, '1');

            $response = json_decode($response, true);

            if ($response['status'] == "success") {
                $error[RESPONSE] = "1";
                $error[MESSAGE] = array("code" => "", "message" => $response['msg']);
            } else {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "102", "message" => $response['msg']);
            }

            $this->response($this->json($error), 200);
        }
        $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Incomplete Details"));
        $this->response($this->json($error), 200);
    }
    
    
    # @author nidhi<nidhi@walkover.in>
    # This function is here to edit contact number.

     public function editContactNumber() {

        include_once(CLASS_DIR . "phonebook_class.php");
        $phoneObj = new phonebook_class();

        $response = $this->login(true);
        if ($response[RESPONSE] == "1") {
            $userId = $response["id"];


            $parm['contactId'] = $this->_request['contactId'];
            $parm['name'] = $this->_request['name'];
            $parm['email'] = $this->_request['email'];
            $parm['code'] = $this->_request['code'];
            $parm['contactNo'] = $this->_request['contact'];
            $parm['conAccessNumber'] = $this->_request['accessNumber'];
            $parm['extensionNumber'] = $this->_request['extensionNumber'];

            
            $numberWithCode = $this->funobj->getCountryCodeAndNumber($parm['contactNo']);
            
            $parm['code'] = $numberWithCode['code'];
            $parm['contact'] = $numberWithCode['number'];
            
            
            
            $this->checkRegx($userId, NOTNUM_REGX, "Please enter valid User Id!", '152');

            $response = $phoneObj->updateContact($parm, $userId, '1');
            $response = json_decode($response, true);

            if ($response['status'] == "success") {
                $error[RESPONSE] = "1";
                $error[MESSAGE] = array("code" => "", "message" => $response['msg']);
            } else {
                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "102", "message" => $response['msg']);
            }

            $this->response($this->json($error), 200);
        }

        $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Incomplete Details"));
        $this->response($this->json($error), 200);
    }
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @desc function use to create pin user for server side api (gtalk *Pin# recharge).
     */
    private function pinUser() {
        if (isset($this->_request['type']) && isset($this->_request['emailId']) && isset($this->_request['pin']) && isset($this->_request['pin'])) {

            #account type : 1 for gtalk and 2 for skype 
            $accountType = $this->_request['type'];

            #emailid : for skype or gtalk
            $emailId = $this->_request['emailId'];

            #pin
            $pin = $this->_request['pin'];

            #check given authentication key is valid or not
            if ($this->_request['authKey'] == "213265498754665458") {
                include_once(CLASS_DIR . "callingCard_class.php");
                $cardobj = new callingCard_class();
                echo $msg = $cardobj->createPinUser($accountType, $emailId, $pin);
            } else
                echo $msg = "You have no permission for use this API.";
        } else
            echo "Please provide valid accountType, emailid, pin and AuthKey";
    }

    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 27/02/2014
     * @uses API to call for selected department  
     */
    private function clickToCallDeptOld() {

        if (isset($this->_request['voiceJsonp']))
            $callBack = 1;
        else
            $callBack = 0;
        //validate login details
        //$response = $this->login(true);
        //check login response
        if (isset($this->_request['deptId']) && isset($this->_request['customerNum']) and isset($this->_request['token'])) {
            $deptId = (int) $this->_request['deptId'];

            $userId = (int) $this->_request['token'];

            if (empty($deptId) || !is_numeric($deptId) || empty($userId) || !is_numeric($userId)) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "118", "message" => "Incomplete Details please enter a valid department"));

                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                } else
                    $this->response($json, 200);
                exit();
            }

            $cusNum = $this->_request['customerNum'];
            //apply validation on customer number
            $validNum = str_replace('+', '', $cusNum);
            $validNum = (int) str_replace(' ', '', $validNum);


            //include signup class and create object
            include_once(CLASS_DIR . 'clickToCall_plugin_class.php');
            $ctcObj = new clickToCall_plugin_class();

            $numberResult = json_decode($ctcObj->getRandomNumberOfDept($deptId), TRUE);
            $number = $numberResult['number'];

            $validDeptNum = str_replace('+', '', $number);
            $deptNum = (int) str_replace(' ', '', $validDeptNum);



            if ((strlen($validNum) < 8) || strlen($validNum) > 18 || strlen($deptNum) < 8 || strlen($deptNum) > 18) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "122", "message" => "Invalid mobile number please use a valid number"));
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                } else
                    $this->response($json, 200);
                exit();
            }


            $infoArray = $ctcObj->getUserInformation($userId, 1);


            // $infoArray = $userInfo->fetch_array(MYSQLI_ASSOC);

            if (empty($infoArray) || !is_array($infoArray)) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "134", "message" => "Problem While getting details"));
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                } else
                    $this->response($json, 200);
                exit();
            }

            $nine["login"] = $infoArray['userName'];
            $nine["password"] = $infoArray['password'];
            if ($nine["login"] == "" || $nine["password"] == "") {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "112", "message" => "Invalid Credentials Please enter a proper userId and password"));
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                } else
                    $this->response($json, 200);
                exit();
            }
            //include_once $_SERVER["DOCUMENT_ROOT"] .'/definePath.php';
            include(CLASS_DIR . "call_class.php");
            $call_obj = new call_class();
            //$funobj = new fun();

            $nine["dest"] = $validNum;
            $nine["source"] = $deptNum;

            $msgid = $call_obj->Call($nine);

            $msgid = str_replace('"', '', substr(substr($msgid, 0, -1), 1));

            $msgIdArr = explode(",", $msgid);


            foreach ($msgIdArr as $val) {
                $subArr = explode(":", $val);
                $newArrMsgId[$subArr[0]] = $subArr[1];
            }

            $returnResult[RESPONSE] = "1";
            $returnResult[MESSAGE] = "success";
            $returnResult[CONTENT][] = $newArrMsgId;

            $json = $this->json($returnResult);
            if ($callBack) {
                echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                exit();
            } else
                $this->response($json, 200);

            unset($ctcObj);
        }
        else { //if login response not valid then give error response
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "118", "message" => "Incomplete Details"));
            $json = $this->json($error);
            if ($callBack) {
                echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                exit();
            } else
                $this->response($json, 200);
        }
    }

    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 27/02/2014
     * @uses API to call for selected department  
     */
    private function clickToCallDept() {
        #callback 1 = data return in jsonp otherwise 0 for json data 
        if (isset($this->_request['voiceJsonp']))
            $callBack = 1;
        else
            $callBack = 0;

        $cusNum = $this->_request['customerNum'];
        //apply validation on customer number
        $validNum = str_replace('+', '', $cusNum);
        $validNum = (int) str_replace(' ', '', $validNum);
        //validate login details

        if ((strlen($validNum) < 8) || strlen($validNum) > 18) {
            $error = array(RESPONSE => "0", "code" => "122", "message" => "Invalid mobile number please use a valid number");
            $json = $this->json($error);
            if ($callBack) {
                echo $this->_request['voiceJsonp'] . '(' . $json . ')';
            } else
                $this->response($json, 200);
            exit();
        }


        //check login response
        if (isset($this->_request['deptId']) && isset($this->_request['customerNum']) and isset($this->_request['token'])) {
            $deptId = (int) $this->_request['deptId'];

            $userId = (int) $this->_request['token'];

            if (empty($deptId) || !is_numeric($deptId) || empty($userId) || !is_numeric($userId)) {
                $error = array(RESPONSE => "0", "code" => "118", "message" => "Incomplete Details please enter a valid department");

                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                } else
                    $this->response($json, 200);
                exit();
            }

            //include signup class and create object
            include_once(CLASS_DIR . 'clickToCall_plugin_class.php');
            $ctcObj = new clickToCall_plugin_class();

            //get numbers for dept
            $numberResult = json_decode($ctcObj->getNumbersOfDept($deptId), TRUE);

            if ($numberResult['status'] != 1) {
                $error = array(RESPONSE => "0", "code" => "134", MESSAGE => $numberResult['msg']);
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                } else
                    $this->response($json, 200);
                exit();
            }

            $numbers = $numberResult['numbers'];

            //get user info
            $infoArray = $ctcObj->getUserInformation($userId, 1);
            //validate result
            if (empty($infoArray) || !is_array($infoArray)) {
                $error = array(RESPONSE => "0", "code" => "134", "message" => "Problem While getting details");
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                } else
                    $this->response($json, 200);
                exit();
            }

            //set parameters
            $nine["login"] = $infoArray['userName'];
            $nine["password"] = $infoArray['password'];
            //validate parameters
            if ($nine["login"] == "" || $nine["password"] == "") {
                $error = array(RESPONSE => "0", "code" => "112", "message" => "Invalid Credentials Please enter a proper userId and password");
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                } else
                    $this->response($json, 200);
                exit();
            }

            //include call class
            include(CLASS_DIR . "call_class.php");
            $call_obj = new call_class();

            //set flag
            $flag = FALSE;
            $i = 0;
            //loop to call random numbers
            while (count($numbers)) {
                $i++;
                if ($i > 20)
                    break;
                //get random number from array
                $numIndex = array_rand($numbers, 1);
                $number = $numbers[$numIndex];

                unset($numbers[$numIndex]); //unset called number
                //$numberResult = json_decode($ctcObj->getRandomNumberOfDept($deptId),TRUE);
                // $number = $numberResult['number'];

                $validDeptNum = str_replace('+', '', $number);
                $deptNum = (int) str_replace(' ', '', $validDeptNum);

                if (strlen($deptNum) < 8 || strlen($deptNum) > 18) {
                    $error = array(RESPONSE => "0", "code" => "122", "message" => "Invalid mobile number please use a valid number");
                    $json = $this->json($error);
                    if ($callBack) {
                        echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                    } else
                        $this->response($json, 200);
                    exit();
                }

                $nine["dest"] = $validNum;
                $nine["source"] = $deptNum;

                $callResult = $call_obj->Call($nine);
                $callResultArray = json_decode($callResult, TRUE);

                if ($callResultArray['status'] != 'success') {
                    $error = array(RESPONSE => "0", "code" => "135", "message" => "Problem while calling!!!");
                    $json = $this->json($error);
                    if ($callBack) {
                        echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                    } else
                        $this->response($json, 200);
                    exit();
                }

                $msgId1 = $callResultArray['msgid1'];

                //set callResponse
                $callResp = 'DIALING';

                $j = 0;
                while ($callResp == 'DIALING') {
                    $j++;
                    if ($j > 20)
                        break;

                    sleep(3);
                    $param['uniqueId'] = $msgId1;
                    $callResJson = $call_obj->callResponse($param);
                    $callResArr = json_decode($callResJson, TRUE);

                    if ($callResArr['status'] != 'success') {
                        $error = array(RESPONSE => "0", "code" => "136", "message" => "Problem while get call response!!!");
                        $json = $this->json($error);
                        if ($callBack) {
                            echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                        } else
                            $this->response($json, 200);
                        exit();
                    }

                    $callResp = $callResArr['msg'];
                }

                //check for call status
                if ($callResp == 'ANSWER') {
                    $flag = TRUE;
                    $error = array(RESPONSE => "1", 'callStatus' => 'ANSWER', MESSAGE => 'success');
                    $json = $this->json($error);
                    if ($callBack) {
                        echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                    } else
                        $this->response($json, 200);
                    exit();
                }
            } //end of while

            unset($ctcObj);

            //check flag and return error response 
            if (!$flag) {
                $error = array(RESPONSE => "0", "code" => "136", "message" => "Not Connected to any number!!!");
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                } else
                    $this->response($json, 200);
                exit();
            }
        }
        else { //if login response not valid then give error response
            $error = array(RESPONSE => "0", "code" => "118", "message" => "Incomplete Details");
            $json = $this->json($error);
            if ($callBack) {
                echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                exit();
            } else
                $this->response($json, 200);
        }
    }

//click to call function

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @desc calling card api use in access number (create and recharge user by card) 
     */
    function callingCard() {



        $cardNumber = $this->_request['cardNumber'];
        $senderId = $this->_request['senderId'];
        $accessNumber = $this->_request['accessNumber'];

        #remove + sign and space from sender id 
        $senderId = str_replace('+', '', $senderId);
        $senderId = (int) str_replace(' ', '', $senderId);

        include_once(CLASS_DIR . "callingCard_class.php");
        $cardobj = new callingCard_class();

        #check sender id length 
        if ((strlen($senderId) < 8) || strlen($senderId) > 18) {
            $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, 'not found', '601 : Invalid Sender id please use a valid Sender id');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "601", "message" => "Invalid Sender id please use a valid Sender id"));
            if ($this->_request['bySms']) {
                return $error;
            } else
                $this->response($this->json($error), 200);
        }

        #check validity for access number 
        if ((strlen($accessNumber) < 8) || strlen($accessNumber) > 18) {
            $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, 'not found', '602 : Invalid Sender id please use a valid Sender id');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "602", "message" => "Invalid Access Number please use a valid Access Number"));
            if ($this->_request['bySms']) {
                return $error;
            } else
                $this->response($this->json($error), 200);
        }

        #get reseller id from longCode(access number) table 91_longCodeNumber number 
        if ($this->_request['bySms']) {
            $resellerDetail = $this->funobj->longCodeResellerId($accessNumber, 1);
        } else
            $resellerDetail = $this->funobj->longCodeResellerId($accessNumber);

        $resellerData = json_decode($resellerDetail, TRUE);  //$pinGenerator,$pinTariff,$pinCurrency,$pinBalance   

        if ($resellerData['status'] == "error") {
            $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, 'not found', '602 : Invalid Access Number please use a valid Access Number');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "602", "message" => "Invalid Access Number please use a valid Access Number"));
            if ($this->_request['bySms']) {
                return $error;
            } else
                $this->response($this->json($error), 200);
        }

        $resellerId = $resellerData['resellerId'];



        #get Calling card detail 
        $pinDetail = $cardobj->getPinDetail($cardNumber, $resellerId);
        $pinDataDetail = json_decode($pinDetail, TRUE);  //$pinGenerator,$pinTariff,$pinCurrency,$pinBalance   

        if ($pinDataDetail['status'] == "error") {
            $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, 'not found', $pinDataDetail['code'] . ":" . $pinDataDetail['msg']);
            $error = array(RESPONSE => "0", MESSAGE => array("code" => $pinDataDetail['code'], "message" => $pinDataDetail['msg']));
            if ($this->_request['bySms']) {
                return $error;
            } else
                $this->response($this->json($error), 200);
        }

        $batchName = $pinDataDetail['batchName'];
        //print_r($pinDataDetail);

        if ($resellerData['prefix'] == '' || $resellerData['prefix'] == 0 || $resellerData['prefix'] == NULL) {
            $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, 'not found', '602 : Invalid Access Number Prefix please use a valid Access Number');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "602", "message" => "Invalid Access Number please use a valid Access Number"));
            if ($this->_request['bySms']) {
                return $error;
            } else
                $this->response($this->json($error), 200);
        }


        #check reseller prefix match given sender id or not 
        $senderId = $this->funobj->senderIdPrefixMatch($resellerData['prefix'], $senderId);


        #search sender id into verified table for check number are verified or not 
        $result = $this->funobj->selectData("userId", " 91_verifiedNumbers", "domainResellerId='" . $resellerId . "' and (CONCAT(countryCode,verifiedNumber) = '" . $senderId . "' OR verifiedNumber='" . $senderId . "')");

        if ($result->num_rows > 0) {

            $row = $result->fetch_array(MYSQL_ASSOC);
            $userid = $row['userId'];
            $userTariff = $cardobj->getUserTariff($userid);
            //echo $userid."  ".$userTariff."  ".$pinDataDetail['pinTariff']."  ".$pinDataDetail['batchId']."  ".$cardNumber."  ".$pinDataDetail['pinBalance'];
            $rechargeResult = $cardobj->rechargeByPin($userid, $userTariff, $pinDataDetail['pinTariff'], $pinDataDetail['batchId'], $cardNumber, $pinDataDetail['pinBalance']);
            $rechargeStatus = json_decode($rechargeResult, TRUE);  //$pinGenerator,$pinTariff,$pinCurrency,$pinBalance   

            if ($rechargeStatus['status'] == "error") {
                $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, $pinDataDetail['batchName'], $rechargeStatus['code'] . ":" . $rechargeStatus['msg']);
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $rechargeStatus['code'], "message" => $rechargeStatus['msg']));
                if ($this->_request['bySms']) {
                    return $error;
                } else
                    $this->response($this->json($error), 200);
            }else {
                $cardobj->callingCardSendSms($senderId, $userid, $pinDataDetail['pinCurrency'], $pinDataDetail['pinBalance'], $rechargeStatus['currentBal']);
                $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, $pinDataDetail['batchName'], $rechargeStatus['msg']);
                $error = array(RESPONSE => "1", MESSAGE => array("message" => $rechargeStatus['msg']));
                if ($this->_request['bySms']) {
                    return $error;
                } else
                    $this->response($this->json($error), 200);
            }
        }else {

            //echo $pinDataDetail['pinGenerator']."   ".$pinDataDetail['pinTariff']."   ".$pinDataDetail['pinCurrency']."   ".$pinDataDetail['pinBalance'];
            $userData = $cardobj->createUser($pinDataDetail['pinGenerator'], $pinDataDetail['pinTariff'], $pinDataDetail['pinCurrency'], $pinDataDetail['pinBalance'], $senderId);
            $userDetail = json_decode($userData, TRUE);

            if ($userDetail['status'] == "error") {
                $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, $pinDataDetail['batchName'], $userDetail['code'] . ":" . $userDetail['msg']);
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $userDetail['code'], "message" => $userDetail['msg']));
                if ($this->_request['bySms']) {
                    return $error;
                } else
                    $this->response($this->json($error), 200);
            }
            $userId = $userDetail['userId'];

            $number = $this->funobj->getCountryCodeAndNumber($senderId);
            if ($number['code'] == '' || $number['code'] == NULL) {
                $number['code'] = $resellerData['prefix'];
                $number['number'] = $senderId;
            }

            $result = $cardobj->updateTransLogAndPinStatus($resellerId, $userId, $pinDataDetail['pinBalance'], $cardNumber, $batchName);

            $res = $cardobj->addVerifedNumber($userId, $number['code'], $number['number'], $resellerId);

            if (!$res) {
                $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, $pinDataDetail['batchName'], '617: new user Created but contact number not save!');
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "617", "message" => "new user Created but contact number not save!"));
                if ($this->_request['bySms']) {
                    return $error;
                } else
                    $this->response($this->json($error), 200);
            }
            if (!$result) {
                $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, $pinDataDetail['batchName'], '618 : new user Created but Card detail not update!');
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "618", "message" => "new user Created but Card detail not update!"));
                if ($this->_request['bySms']) {
                    return $error;
                } else
                    $this->response($this->json($error), 200);
            }

            $cardobj->callingCardSendSms($senderId, $userId, $pinDataDetail['pinCurrency'], $pinDataDetail['pinBalance']);
            $cardobj->callingCardLog($senderId, $accessNumber, $cardNumber, $pinDataDetail['batchName'], 'New User Created successfully userid :' . $userId);
            $error = array(RESPONSE => "1", MESSAGE => array("message" => "New User Created successfully"));
            if ($this->_request['bySms']) {
                return $error;
            } else
                $this->response($this->json($error), 200);
        }
    }

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @desc function use to direct API for add user 4 digit pin number in access number 
     *   
     */
    function addUserPin() {
        $pinNumber = $this->_request['pinNumber'];
        $senderId = $this->_request['senderId'];
        $accessNumber = $this->_request['accessNumber'];
        #remove + sign and space from sender id 
        $senderId = str_replace('+', '', $senderId);
        $senderId = (int) str_replace(' ', '', $senderId);

        include_once(CLASS_DIR . "callingCard_class.php");
        $cardobj = new callingCard_class();

        if (!preg_match("/^[0-9]{4}$/", $pinNumber)) {
            $cardobj->callingCardLog($senderId, $accessNumber, $pinNumber, 'not found', ' 701 :Invalid Pin Number please use a valid Pin Number');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "701", "message" => "Invalid Pin Number please use a valid Pin Number"));
            $this->response($this->json($error), 200);
        }


        #check sender id length 
        if (!preg_match("/^[0-9]{8,18}$/", $senderId)) {
            $cardobj->callingCardLog($senderId, $accessNumber, $pinNumber, 'not found', '702 :Invalid Sender id please use a valid Sender id');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "702", "message" => "Invalid Sender id please use a valid Sender id"));
            $this->response($this->json($error), 200);
        }

        #check validity for access number 
        if ((strlen($accessNumber) < 8) || strlen($accessNumber) > 18) {
            $cardobj->callingCardLog($senderId, $accessNumber, $pinNumber, 'not found', '703 :Invalid Access Number please use a valid Access Number');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "703", "message" => "Invalid Access Number please use a valid Access Number"));
            $this->response($this->json($error), 200);
        }

        #get reseller id from longCode(access number) table 91_longCodeNumber number 
        $resellerDetail = $this->funobj->longCodeResellerId($accessNumber);
        $resellerData = json_decode($resellerDetail, TRUE);  //$pinGenerator,$pinTariff,$pinCurrency,$pinBalance   

        if ($resellerData['status'] == "error") {
            $cardobj->callingCardLog($senderId, $accessNumber, $pinNumber, 'not found', '704 :Invalid Access Number please use a valid Access Number');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "704", "message" => "Invalid Access Number please use a valid Access Number"));
            $this->response($this->json($error), 200);
        }

        $resellerId = $resellerData['resellerId'];

        if ($resellerData['prefix'] == '' || $resellerData['prefix'] == 0 || $resellerData['prefix'] == NULL) {
            $cardobj->callingCardLog($senderId, $accessNumber, $pinNumber, 'not found', '704 : Invalid Access Number Prefix please use a valid Access Number');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "704", "message" => "Invalid Access Number please use a valid Access Number"));
            $this->response($this->json($error), 200);
        }

        #check reseller prefix match given sender id or not 
        $senderId = $this->funobj->senderIdPrefixMatch($resellerData['prefix'], $senderId);



        #search sender id into verified table for check number are verified or not 
        $result = $this->funobj->selectData("userId", " 91_verifiedNumbers", "domainResellerId='" . $resellerId . "' and (CONCAT(countryCode,verifiedNumber) = '" . $senderId . "' OR verifiedNumber='" . $senderId . "')");

        if ($result->num_rows > 0) {

            $row = $result->fetch_array(MYSQL_ASSOC);
            $userid = $row['userId'];
            #update pin status 
            $table = '91_userLogin';
            $data = array("userPin" => $pinNumber);
            $condition = "userId=" . $userid;
            $this->funobj->db->update($table, $data)->where($condition);
            $this->funobj->db->getQuery();
            $result = $this->funobj->db->execute();
            if (!$result) {
                $cardobj->callingCardLog($senderId, $accessNumber, $pinNumber, 'not found', '705 :User Pin not updated userid :' . $userid);
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "705", "message" => "User Pin not updated"));
                $this->response($this->json($error), 200);
            } else {
                $cardobj->callingCardLog($senderId, $accessNumber, $pinNumber, 'not found', 'User Pin updated successfully');
                $error = array(RESPONSE => "1", MESSAGE => array("message" => "Pin updated successfully"));
                $this->response($this->json($error), 200);
            }
        } else {
            $cardobj->callingCardLog($senderId, $accessNumber, $pinNumber, 'not found', '706 :User Pin not updated');
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "706", "message" => "User Pin not updated"));
            $this->response($this->json($error), 200);
        }
    }

    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 28/02/2014
     * @uses to get department list by user
     */
    private function getCTCPlugin() {

        //get html from file
        $html = file_get_contents('clktocall-html.php', true);

        if (isset($this->_request['voiceJsonp']))
            $callBack = 1;
        else
            $callBack = 0;


        $checkAuth = 0;
        if (!isset($this->_request['token']) || $this->_request['token'] == '' || $this->_request['token'] == null) {
            $error = array(RESPONSE => "0", "code" => "131", "message" => "Please enter a valid token!!!");
            $json = $this->json($error);
            if ($callBack) {
                echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                exit();
            } else
                $this->response($json, 200);
        }



        if (isset($this->_request['token'])) {
            $checkAuth = 1;
        }

        //get user id
        $userId = $this->_request['token'];

        if ($checkAuth == 1) {
            //include signup class and create object
            include_once(CLASS_DIR . 'clickToCall_plugin_class.php');
            $ctcObj = new clickToCall_plugin_class();

            $deptDataJson = $ctcObj->getDeptsByUserId($userId);

            $depts = json_decode($deptDataJson, TRUE);

            if ($depts['status'] == 1) {
                $error = array(RESPONSE => "1", CONTENT => array("depts" => $depts['depts'], 'html' => $html), MESSAGE => 'success');
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                    exit();
                } else
                    $this->response($json, 200);


                //$respnse[CONTENT][]['type'] = $getUserInfo["type"];
            }
            else {
                $error = array(RESPONSE => "0", "code" => "133", "message" => "Department not found!!!");
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                    exit();
                } else
                    $this->response($json, 200);
            }
            unset($ctcObj);
        }
        else {
            $error = array(RESPONSE => "0", "code" => "132", "message" => "You are not authorized!!!");
            $json = $this->json($error);
            if ($callBack) {
                echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                exit();
            } else
                $this->response($json, 200);
        }
    }

//end of getDeptListForUser

    function getDeptListForUser() {

        if (isset($this->_request['voiceJsonp']))
            $callBack = 1;
        else
            $callBack = 0;


        $checkAuth = 0;
        if (!isset($this->_request['token']) || $this->_request['token'] == '' || $this->_request['token'] == null) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "131", "message" => "Please enter a valid token!!!"));
            $json = $this->json($error);
            if ($callBack) {
                echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                exit();
            } else
                $this->response($json, 200);
        }



        if (isset($this->_request['token'])) {
            $checkAuth = 1;
        }

        //get user id
        $userId = $this->_request['token'];

        if ($checkAuth == 1) {
            //include signup class and create object
            include_once(CLASS_DIR . 'clickToCall_plugin_class.php');
            $ctcObj = new clickToCall_plugin_class();

            $deptDataJson = $ctcObj->getDeptsByUserId($userId);

            $depts = json_decode($deptDataJson, TRUE);

            if ($depts['status'] == 1) {
                $error = array(RESPONSE => "1", CONTENT => array("depts" => $depts['depts']), MESSAGE => 'success');
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                    exit();
                } else
                    $this->response($json, 200);


                //$respnse[CONTENT][]['type'] = $getUserInfo["type"];
            }
            else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "133", "message" => "Department not found!!!"));
                $json = $this->json($error);
                if ($callBack) {
                    echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                    exit();
                } else
                    $this->response($json, 200);
            }
            unset($ctcObj);
        }
        else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "132", "message" => "You are not authorized!!!"));
            $json = $this->json($error);
            if ($callBack) {
                echo $this->_request['voiceJsonp'] . '(' . $json . ')';
                exit();
            } else
                $this->response($json, 200);
        }
    }

//end of getDeptListForUser

    private function json($data) {
        if (is_array($data)) {
            return json_encode($data);
        }
    }

    /*
     * @author nidhi@walkover.in
     * This function is to receive messages.
     * http://voice.phone91.com/api/sendMesages?api_id=3468158&from=12028038240&to=12028038240&timestamp=2014-03-10 11:04:21&text=&charset=ISO-8859-1&udh=
     */

    public function sendMesages() {
        #- getting parameters from request. sendSmsClass.php       
        include_once(CLASS_DIR . "sendSmsClass.php");
        $sendSms = new sendSmsClass();

        $numberRes = $this->getUserIdSms($this->_request['from'], 1, $this->_request['to'], 1);

        if (empty($numberRes['number'])) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "122", "message" => "Invalid Access number or mobile Number");
            $this->response($this->json($error), 200);
        }

        $fromUser = trim($numberRes['number']);

        $toUser = trim($this->_request['to']);

        if ($fromUser == $toUser) {
            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "122", "message" => "both numbers are same");
            $this->response($this->json($error), 200);
        }


        // echo " from".$fromUser;

        $tempparam['to'] = $fromUser;
        $tempparam['senderId'] = $toUser;

        if (preg_match(NOTMOBNUM_REGX, $fromUser)) {
            $tempparam['text'] = "Your request could not be processed. Please contact support@phone91.com";
            $sendSms->sendMessagesGlobal($tempparam);

            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "122", "message" => "Invalid mobile number please use a valid number");
            $this->response($this->json($error), 200);
        }

        if (preg_match("/^[0-9]{14}$/", trim($this->_request['text']))) {

            $this->_request['cardNumber'] = trim($this->_request['text']);
            $this->_request['senderId'] = $fromUser;
            $this->_request['accessNumber'] = $toUser;
            $this->_request['bySms'] = 1;

            $callingcardResp = $this->callingCard();

            if ($callingcardResp['response'] == 1) {
                echo $response = $this->getUserPin($fromUser, $toUser, $tempparam);
            } else {
                $tempparam['text'] = $callingcardResp['message']['message'];
                $sendSms->sendMessagesGlobal($tempparam);

                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => $callingcardResp['message']['code'], "message" => $callingcardResp['message']['message']);
                $this->response($this->json($error), 200);
            }
        } else {

            $message = strtolower(trim($this->_request['text']));
            $messageArray = array("b", "bb", "bll", "bell", "balance", "belence", "blnc", "bbb", "bal", "bel", "bl", "bln", "blc", "belance", "balence", "balnc", "belnc");

            if ($message == 'forget' || $message == 'forgot' || $message == 'pin' || in_array($message, $messageArray)) {
                ////echo $fromUser." ".$toUser; 
                /// print_r($tempparam);
                echo $response = $this->getUserPin($fromUser, $toUser, $tempparam, $message);
            } else if (preg_match('/^[a-zA-Z]+\s+\d+$/', $message)) {
                $destinationNo = preg_replace("/[^0-9]/", "", $message);
                ;

                $this->_request['callerId'] = $fromUser;
                $this->_request['destinationNo'] = $destinationNo;
                $this->_request['accessNo'] = $toUser;
                $this->_request['status'] = 1;

                echo $this->getAccessNo('1');
            } else if ($message == 'help') {
                $tempparam['text'] = 'Hey there, 
follow the below instructions to try our help.

1. To check balance: SMS ‘bal’ to 12028038240
2. If you forget your PIN: SMS forget to 12028038240.
3. If you need a new direct access number: SMS your friend’s name and contact number with country code to 12028038240, and we will reply you the access number.

Happy sharing!
Team Phone91';

                $sendSms->sendMessagesGlobal($tempparam);

                $error[RESPONSE] = 1;
                $error[MESSAGE] = " Help Successfully sent to " . $fromUser;
                $this->response($this->json($error), 200);
            } else if ($message == 'delete' || $message == 'deactiv' || $message == 'deactivate' || $message == 'deactive') {
                include_once(CLASS_DIR . "contact_class.php");

                $conObj = new contact_class();
                $result = $this->getUserIdSms($fromUser, 1, $toUser);

                if (isset($result['response']) && $result['response'] == 0) {
                    $tempparam['text'] = $result['message'];
                    $sendSms->sendMessagesGlobal($tempparam);

                    $error[RESPONSE] = 0;
                    $error[MESSAGE] = $result['message'];
                    $this->response($this->json($error), 200);
                }

                $fromUser = $result['number'];
                if ($result) {
                    //$fromUser;
                    $response = $conObj->deletephone($fromUser, $result['userId'], 1, $result['resellerId']);

                    //echo $this->funobj->querry;

                    $deleteResult = json_decode($response, true);

                    if ($deleteResult['msgtype'] == 'success') {
                        $response = 1;
                        $errorMessage = $deleteResult['msg'] . " You can always SMS Help to 12028038240.
Happy sharing!
Team Phone91";
                        $error[RESPONSE] = "1";
                        $error[MESSAGE] = $errorMessage;
                        $this->response($this->json($error), 200);
                    } else {
                        $response = 0;
                        $errorMessage = $deleteResult['msg'] . " You can always SMS Help to 12028038240.
Happy sharing!
Team Phone91";
                        ;
                    }
                } else {
                    $response = 0;
                    $errorMessage = "An error occoured please try again later";
                }

                $tempparam['text'] = $errorMessage;
                $sendSms->sendMessagesGlobal($tempparam);

                $error[RESPONSE] = $response;
                $error[MESSAGE] = array("code" => "140", "message" => $errorMessage);
                $this->response($this->json($error), 200);
            } else {
                $tempparam['text'] = "Your request is invalid. you can always SMS Help to 12028038240.
Happy sharing!
Team Phone91";
                $sendSms->sendMessagesGlobal($tempparam);

                $error[RESPONSE] = "0";
                $error[MESSAGE] = array("code" => "140", "message" => "Invalid Request");
                $this->response($this->json($error), 200);
            }
        }
    }

    function getUserPin($fromUser, $toUser, $tempparam, $message = NULL) {
        include_once(CLASS_DIR . "sendSmsClass.php");
        $sendSms = new sendSmsClass();

        $userDetails = $this->getUserIdSms($fromUser, 1, $toUser);

        //print_r($userDetails);

        if (isset($userDetails['response']) && $userDetails['response'] == 0) {
            $tempparam['text'] = $userDetails['message'];
            $sendSms->sendMessagesGlobal($tempparam);

            $error[RESPONSE] = 0;
            $error[MESSAGE] = $userDetails['message'];
            $this->response($this->json($error), 200);
        }

        $fromUser = $userDetails["number"];

        $userInfo['userInfo']['userId'] = $userDetails['userId'];

        if (!empty($userInfo['userInfo']['userId'])) {
            $messageArray = array("b", "bb", "bll", "bell", "balance", "belence", "blnc", "bbb", "bal", "bel", "bl", "bln", "blc", "belance", "balence", "balnc", "belnc");

            if (in_array($message, $messageArray)) {
                $response = $this->funobj->getUserBalanceInfo($userInfo['userInfo']['userId']);
                $currency = $this->funobj->getCurrencyViaApc($response['currencyId'], 1);
                $tempparam['text'] = 'Hey there, your account balance is ' . round($response['balance'], 2) . ' ' . $currency . '. 
For more such shortcuts, you can always SMS Help to 12028038240.
Happy sharing!
Team Phone91';
                $sendSms->sendMessagesGlobal($tempparam);

                $error[RESPONSE] = "1";
                $error[MESSAGE] = "Balance Information successfully sent to " . $fromUser;
                $this->response($this->json($error), 200);
            } else {
                $userInfornation = $this->funobj->getUserInformation($userInfo['userInfo']['userId'], 1);

                if ($userInfornation) {
                    if ($userInfornation['userPin']) {
                        $tempparam['text'] = 'Hey there, your PIN is: ' . $userInfornation['userPin'] . '.
For more such shortcuts, you can always SMS Help to 12028038240.
Happy sharing!
Team Phone91';

                        $sendSms->sendMessagesGlobal($tempparam);

                        $error[RESPONSE] = "1";
                        $error[MESSAGE] = "Pin successfully sent to " . $fromUser;
                        $this->response($this->json($error), 200);
                    } else {
                        $newPwd = $this->funobj->randomdigit(4);
                        $data = array("userPin" => $newPwd);
                        $table = "91_userLogin";
                        $condition = 'userId = "' . $userInfo['userInfo']['userId'] . '"';

                        $result = $this->funobj->updateData($data, $table, $condition);

                        if ($result) {
                            //$tempparam['text'] =  "Your 4 digit pin is ".$newPwd." you can use this pin to make a call";

                            $tempparam['text'] = 'Hey there, your PIN is: ' . $newPwd . '.
For more such shortcuts, you can always SMS Help to 12028038240.

Happy sharing!
Team Phone91';

                            $sendSms->sendMessagesGlobal($tempparam);

                            $error[RESPONSE] = "1";
                            $error[MESSAGE] = "Pin successfully sent to " . $fromUser;
                            $this->response($this->json($error), 200);
                        } else {
                            $tempparam['text'] = "Your request could not be processed! please try after some time.";
                            $sendSms->sendMessagesGlobal($tempparam);

                            $error[RESPONSE] = "0";
                            $error[MESSAGE] = array("code" => "140", "message" => "Your request could not be processed! please try after some time.");
                            $this->response($this->json($error), 200);
                        }
                    }
                } else {
                    $tempparam['text'] = "Your request could not be processed! please try after some time.";
                    $sendSms->sendMessagesGlobal($tempparam);

                    $error[RESPONSE] = "0";
                    $error[MESSAGE] = array("code" => "140", "message" => "This user is not verified.");
                    $this->response($this->json($error), 200);
                }
            }
        } else {
            $tempparam['text'] = "Your request could not be processed! please try after some time. May be this user is not resgistered witdh us";
            $sendSms->sendMessagesGlobal($tempparam);

            $error[RESPONSE] = "0";
            $error[MESSAGE] = array("code" => "140", "message" => "This user is not verified. user Id not found!");
            $this->response($this->json($error), 200);
        }
    }

    /*
     * @author nidhi<nidhi@walkover.in>
     */

    function getAccessNo($show = NULL) {
        include_once(CLASS_DIR . "sendSmsClass.php");
        $sendSms = new sendSmsClass();

        if ($show != '1') {
            $error[RESPONSE] = 0;
            $error[MESSAGE] = "You do not have permission to generate or check access number";
            $this->response($this->json($error), 200);
        }
        //print_r($this->_request);

        $param['senderNo'] = $this->_request['callerId'];
        $param['destinationNo'] = $this->_request['destinationNo'];
        $param['accNo'] = $this->_request['accessNo'];

        if (isset($this->_request['status']) && $this->_request['status'] == 1)
            $status = 1;
        else
            $status = 0;

        $userDetails = $this->getUserIdSms($param['senderNo'], $status, $param['accNo']); //getUserIdSms($userNumber,$status = 1, $toUser)

        $tempparam['to'] = $param['senderNo'];
        $tempparam["password"] = "AWIKSeLIcPFHBS";
        $tempparam["apiId"] = "3468158";

        //print_r($userDetails);
        if (isset($userDetails['response']) && $userDetails['response'] == 0) {
            $tempparam['text'] = $userDetails['message'];
            $sendSms->sendMessagesGlobal($tempparam);

            $error[RESPONSE] = 0;
            $error[MESSAGE] = $userDetails['message'];
            $this->response($this->json($error), 200);
        }

        $param['senderNo'] = $userDetails["number"];
        $param['userId'] = $userDetails['userId'];

        if (!($param['userId'])) {

            $tempparam['text'] = "Your number is not verified. Please verify your account. or contact support@phone91.com ";
            $sendSms->sendMessagesGlobal($tempparam);

            $error[RESPONSE] = 0;
            $error[MESSAGE] = "Your number is not verified. Please verify your account. or contact support@phone91.com ";
            $this->response($this->json($error), 200);
        }

        include_once(CLASS_DIR . "phonebook_class.php");
        $phoneObj = new phonebook_class();


        // echo$param['userId'] ;
        $accResult = $phoneObj->getContactInfo($param['userId'], $param['destinationNo']);

        $contactArray = $accResult['allcontact'];

        #- Checking access number for that contact is exist or not.
        if (isset($contactArray['accessNo']) && !empty($contactArray['accessNo'])) {
            //if($param['accNo'] != $contactArray['accessNo'])
            {
                $hash = "";

                if (isset($contactArray['hash']) && $contactArray['hash'] != '100') {
                    $hash = " #" . $contactArray['hash'];
                }

                //echo $param['destinationNo'];
                $tempparam['text'] = "Hello, Your access Number for " . $param['destinationNo'] . " is " . $contactArray['accessNo'] . $hash;
                $sendSms->sendMessagesGlobal($tempparam);

                $response = 1;
                $message = "Access Number Sent successfully.";
            }
        } else {
            $accessNoArray = $phoneObj->getUserAccessNumbers($param['userId']);

            $accessNoArr = $accessNoArray['accessNumber'];

            $allAccessNoArr = $phoneObj->allAccessNo(1, 2);

            //print_r($allAccessNoArr);
            //print_r($accessNoArr);

            $result = array_diff($allAccessNoArr, $accessNoArr);
            $matchedNumber = $this->getAccessNumberViaPrefix($result, $param['senderNo']);

            //print_r($accessNoArray);

            $hash = $accessNoArray['hash'];
            $allHash = range(10, 99);

            $hashDifference = array_diff($allHash, $hash);

            $hashValue = array_rand($hashDifference);

            if (empty($hashDifference)) {
                $hashValue = "101";
                mail("nidhi@walkover.in,shubhendra@hostnsoft.com", "All Hash  Are assigned", "Hello, please check All hash numbers are finished." . json_encode(debug_backtrace()));
            }

            if (!empty($result)) {
                #- updating acess number to database.
                include_once(CLASS_DIR . "db_class.php");
                $dbobj = new db_class();

                $db = $dbobj->connectMongoDb();

                #- conditions to set in db.
                $collectionName = "phonebook";

                if ($contactArray && !empty($contactArray)) {

                    $setArray = array('$set' => array(
                            'hash' => $hashValue,
                            'accessNo' => '' . $matchedNumber));

                    $accConditionArray = array('userId' => $param['userId'], "contactNo" => $param['destinationNo']);

                    $db->$collectionName->update($accConditionArray, $setArray, array('upsert' => true));
                } else {
                    $data = array("contact_id" => new mongoId(),
                        "name" => $param['destinationNo'],
                        "hash" => $hashValue,
                        "contactNo" => $param['destinationNo'],
                        'userId' => $param['userId'],
                        'accessNo' => '' . $matchedNumber);


                    $db->$collectionName->insert($data);
                }


                #- code ends
                #- sending message to user.
                //$tempparam['text'] = "Hello, Your access Number for ".$param['destinationNo']." is ".$result[0];
                $tempparam['text'] = 'Hey there, your access number to (' . $param['destinationNo'] . ') is: ' . $matchedNumber . ' #' . $hashValue . '
For more such shortcuts, you can always SMS Help to 12028038240.
Happy sharing!
Team Phone91';

                $sendSms->sendMessagesGlobal($tempparam);

                $response = 1;
                $message = "Access Number successfully sent.";
            } else {
                $response = 0;
                $message = "Your request could not be processed. Please check your contact number. All access numbers are already used.";
                $tempparam['text'] = "Your request could not be processed. Please check your contact number. or contact support@phone91.com";

                $sendSms->sendMessagesGlobal($tempparam);
            }
        }

        $error[RESPONSE] = $response;
        $error[MESSAGE] = $message;
        $this->response($this->json($error), 200);
    }

    function getAccessNumberViaPrefix($array, $mystring) {
        $allNumbers = array();

        foreach ($array as $key => $val) {
            $numstring = $this->checkTwoStrings($mystring, $val);
            $allNumbers[$val] = $numstring;
        }

        return array_search(max($allNumbers), $allNumbers);
    }

    function checkTwoStrings($mystring, $val) {
        $str = '';
        for ($i = 0; $i < count($mystring); $i++) {
            $str.=$mystring[$i];
            $response = $this->startsWith($val, $str);

            if (!$response) {
                return $i;
            }
        }
        return count($mystring);
    }

    function startsWith($haystack, $needle) {
        return $needle === '' . substr($haystack, 0, strlen($needle)); // substr's false => empty string
    }

    /*
     * @autho nidhi <nidhi@walkover.in>
     * This function is to get user id from number
     */

    function getUserIdSms($userNumber, $status = 1, $toUser, $getNumber = NULL) {

        #- getting reseller id and prefix from longCodeResellerId function.
        $resellerId = $this->funobj->longCodeResellerId($toUser, $status);
        $resellerId = json_decode($resellerId, true);

        if ($resellerId['prefix'] == '' || $resellerId['prefix'] == 0 || $resellerId['prefix'] == NULL) {
            return array('response' => 0, 'message' => 'Invalid Access Number. Please enter valid access number.');
        }

        $userNumber = $this->funobj->senderIdPrefixMatch($resellerId['prefix'], $userNumber);

        if ($getNumber) {
            return array('number' => $userNumber);
        }


        $result = $this->funobj->checkVerifiedNumber($userNumber, $resellerId['resellerId']);



        $userInfo = json_decode($result, true);

        if ($userInfo['msgStatus'] == "success") {
            return array('userId' => $userInfo['userInfo']['userId'], 'resellerId' => $resellerId['resellerId'], "prefix" => $resellerId['prefix'], "number" => $userNumber);
        } else {
            return array('response' => 0, 'message' => 'This Number Is Not verified!');
        }
    }

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 13-06-14
     * @desc function use to recharge by pin api 
     * @ direct use by api  
     */
    function rechargePin() {



        $pin = $this->_request['pin'];

        if (!preg_match("/^[0-9]+$/", $pin)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "301", "message" => "Please enter valid pin."));
            $this->response($this->json($error), 200);
        }

        $response = $this->login(true);

        include_once(CLASS_DIR . "callingCard_class.php");
        $cardobj = new callingCard_class();

        if ($response[RESPONSE] == "1") {

            $userId = $response["id"];
            $resellerId = $respnse[CONTENT]['resellerId'];

            $userTariff = $cardobj->getUserTariff($userId);


            $pinDetail = $cardobj->getPinDetail($pin, $resellerId);
            $pinDataDetail = json_decode($pinDetail, TRUE);

            if ($pinDataDetail['status'] == "error") {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $pinDataDetail['code'], "message" => $pinDataDetail['msg']));
                $this->response($this->json($error), 200);
            }

            $rechargeStatus = $cardobj->rechargeByPin($userId, $userTariff, $pinDataDetail['pinTariff'], $pinDataDetail['batchId'], $pin, $pinDataDetail['pinBalance']);
            $rechargeDetail = json_decode($rechargeStatus, TRUE);
            if ($rechargeDetail['status'] == 'success') {
                $error = array(RESPONSE => "1", MESSAGE => array("code" => "", "message" => "successfully recharge.", 'currentBal' => $rechargeDetail['currentBal']));
                $this->response($this->json($error), 200);
            } else
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $rechargeDetail['code'], "message" => $rechargeDetail['msg']));
            $this->response($this->json($error), 200);
        }else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }
    }

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 16/06/2014
     * @desc function use to get price detail according to country. 
     * http://voip91.biz/searchRate.php?country=India&currency=7
     */
    private function searchPrice() {

        $country = $this->_request['country'];
        $currency = $this->_request['currency'];

        if (!preg_match('/^[a-zA-Z0-9\_\-\s]+/', $country)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "country not valid."));
            $this->response($this->json($error), 200);
        }


        include_once(CLASS_DIR . "callingCard_class.php");
        $cardobj = new callingCard_class();

        $detail = $cardobj->searchRate($country, $currency);
        if (count($detail) > 1) {
            $error = array(RESPONSE => "1", MESSAGE => array("code" => "", "message" => "Successfully show all price"), CONTENT => array($detail));
            $this->response($this->json($error), 200);
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Price not found."));
            $this->response($this->json($error), 200);
        }
    }

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 23/6/2014
     * @desc function use to set user currency in signUp time 
     */
    private function setUserCurrency() {

        $tariffId = $this->_request['tariffId'];

        if (!preg_match("/^[0-9]+$/", $tariffId)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "301", "message" => "Please select valid currency."));
            $this->response($this->json($error), 200);
        }


        $response = $this->login(true);

        if ($response[RESPONSE] == "1") {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "302", "message" => "Currency already set."));
            $this->response($this->json($error), 200);
        } elseif ($response[RESPONSE] == "0") {
            if ($response[MESSAGE]['code'] == '1007') {

                $userDetail = $response[CONTENT];

                if ($userDetail['beforeLoginFlag'] == 0) {

                    $currencyId = $this->funobj->getOutputCurrency($tariffId);

                    include_once(CLASS_DIR . "signup_class.php");
                    $signupObj = new signup_class();
                    $updateStatus = $signupObj->updateUserCurrencySetPlan($currencyId, $userDetail['resellerId'], $userDetail['userId'], 1);
                    $result = json_decode($updateStatus, TRUE);
                    if ($result['status'] == "success") {
                        $error = array(RESPONSE => "1", MESSAGE => array("code" => "", "message" => "Successfully currency set."));
                        $this->response($this->json($error), 200);
                    } else {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => '306', "message" => $result['msg']));
                        $this->response($this->json($error), 200);
                    }
                } else {
                    $error = array(RESPONSE => "0", MESSAGE => array("code" => '305', "message" => 'Currency already assign to this user.'));
                    $this->response($this->json($error), 200);
                }
            } else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $response[MESSAGE]['code'], "message" => $response[MESSAGE]['message']));
                $this->response($this->json($error), 200);
            }
        }




        //$userId = $response["id"];
        //$resellerId = $respnse[CONTENT]['resellerId'];
    }

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 24-06-2014
     * @desc function use to save temporary number of user in signup or login time (before login page ) 
     * */
    private function saveTempNumber() {

        $countryCode = $this->_request['countryCode'];
        $contactNo = $this->_request['contactNo'];
        $smsCall = $this->_request['smsCallType'];

        if (!preg_match("/^[0-9]{1,5}$/", $countryCode)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "301", "message" => "Please enter valid country code."));
            $this->response($this->json($error), 200);
        }

        if (!preg_match("/^[0-9]{8,18}$/", $contactNo)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "302", "message" => "Please enter valid contact number."));
            $this->response($this->json($error), 200);
        }

        if (!preg_match('/^[0-2]+/', $smsCall)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "301", "message" => "Please enter valid type."));
            $this->response($this->json($error), 200);
        }

        if ($smsCall == 1) {
            $smsCall = 'SMS';
        } elseif ($smsCall == 2) {
            $smsCall = 'CALL';
        } else {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "301", "message" => "Please enter valid type."));
            $this->response($this->json($error), 200);
        }


        $response = $this->login(true);

        if ($response[RESPONSE] == "1") {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "302", "message" => "User already verified!"));
            $this->response($this->json($error), 200);
        } elseif ($response[RESPONSE] == "0") {
            if ($response[MESSAGE]['code'] == '1007') {

                $userDetail = $response[CONTENT];

                if ($userDetail['beforeLoginFlag'] == 1) {

                    #check number already in use 
                    include_once(CLASS_DIR . "contact_class.php");
                    $cont_obj = new contact_class();
                    if ($cont_obj->checkNumberExist($countryCode, $contactNo, $userDetail['userId']) == 1) {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => "303", "message" => "Number alrady in use."));
                        $this->response($this->json($error), 200);
                    }

                    include_once(CLASS_DIR . "signup_class.php");
                    $signUpObj = new signup_class();
                    $request = array();
                    $request['countryCode'] = $countryCode;
                    $request['mobileNumber'] = $contactNo;
                    $request['carrierType'] = $smsCall;
                    $msg = $signUpObj->mobileVerificationBeforeLogin($request, $userDetail['userId']);

                    $result = json_decode($msg, TRUE);

                    if ($result['status'] == "success") {
                        $error = array(RESPONSE => "1", MESSAGE => array("code" => "", "message" => "Verification code send successfully."));
                        $this->response($this->json($error), 200);
                    } else {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => '306', "message" => $result['msg']));
                        $this->response($this->json($error), 200);
                    }
                } else {
                    if ($userDetail['beforeLoginFlag'] == 0) {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => '307', "message" => "There are no any currency assign to user."));
                    } else
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => '307', "message" => "User already verified."));
                    $this->response($this->json($error), 200);
                }
            }else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $response[MESSAGE]['code'], "message" => $response[MESSAGE]['message']));
                $this->response($this->json($error), 200);
            }
        }
    }

    /**
     * @author sudhir pandey <sudhir@hostnsot.com>
     * @since 25/06/2014
     * @desc function use to save verification number and set beforelogin flag 2 
     */
    private function saveVerifyNumber() {

        $code = $this->_request['verificationCode'];

        if (!preg_match("/^[0-9]{1,5}$/", $code)) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "301", "message" => "Please enter valid verification code."));
            $this->response($this->json($error), 200);
        }

        $response = $this->login(true);

        if ($response[RESPONSE] == "1") {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "302", "message" => "User already verified!"));
            $this->response($this->json($error), 200);
        } elseif ($response[RESPONSE] == "0") {
            if ($response[MESSAGE]['code'] == '1007') {

                $userDetail = $response[CONTENT];

                if ($userDetail['beforeLoginFlag'] == 1) {

                    include_once(CLASS_DIR . "contact_class.php");
                    $cont_obj = new contact_class();
                    $request = array();
                    $request['key'] = $code;
                    $msg = $cont_obj->verifyNumber($request, $userDetail['userId'], 1);
                    $msgData = json_decode($msg, TRUE);


                    if ($msgData['msgtype'] == "success") {
                        $error = array(RESPONSE => "1", MESSAGE => array("code" => "", "message" => " Number successfully verified."), CONTENT => array(array('type' => $userDetail['type'])));
                        $this->response($this->json($error), 200);
                    } else {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => '306', "message" => $msgData['msg']));
                        $this->response($this->json($error), 200);
                    }
                } else {
                    if ($userDetail['beforeLoginFlag'] == 0) {
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => '307', "message" => "There are no any currency assign to user."));
                    } else
                        $error = array(RESPONSE => "0", MESSAGE => array("code" => '307', "message" => "User already verified."));
                    $this->response($this->json($error), 200);
                }
            }else {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $response[MESSAGE]['code'], "message" => $response[MESSAGE]['message']));
                $this->response($this->json($error), 200);
            }
        }
    }

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 19-8-2014
     * @desc get all country list from database 
     */
    function getAllCountry($return = false) {
        $resellerId = $this->_request['resellerId'];
        include_once(CLASS_DIR . "phonebook_class.php");
        $phoneObj = new phonebook_class();
        $countyList = $phoneObj->getCountriesWithPrefix($resellerId);
        $countryRes = json_decode($countyList, TRUE);

        if ($countryRes['status'] == 'error') {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => '301', "message" => $countryRes['msg']));
            $this->response($this->json($error), 200);
        }

        if ($return) {
            return $countryRes['countryList'];
        }

        $respnse[RESPONSE] = "1";
        $respnse[MESSAGE] = array("code" => "", "message" => "All country list.");
        $respnse[CONTENT] = array($countryRes['countryDetail']);
        $this->response($this->json($respnse), 200);
    }

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 19-8-2014
     * @desc get all state list as per country selected 
     */
    function getAllState($return = false, $countryCode = '') {
        $resellerId = $this->_request['resellerId'];
        if ($countryCode == '') {
            $prefix = $this->_request['countryCode'];
        } else
            $prefix = $countryCode;

        $response = $this->login(true);
        include_once(CLASS_DIR . "phonebook_class.php");
        $phoneObj = new phonebook_class();

        if ($response[RESPONSE] == "1") {
            $userId = $response["id"];
            //$stateList =  $phoneObj->getStates($resellerId,$prefix); 
            $stateList = $phoneObj->getOneCountryDetail($resellerId, $prefix, $userId);

            $stateRes = json_decode($stateList, TRUE);

            if ($stateRes['status'] == 'error') {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => '301', "message" => $stateRes['msg']));
                $this->response($this->json($error), 200);
            }

            if ($return) {
                return $stateRes['stateDetail']; //data
            }


            $respnse[RESPONSE] = "1";
            $respnse[MESSAGE] = array("code" => "", "message" => "All state list.");
            $respnse[CONTENT] = $stateRes['stateDetail'];
            $this->response($this->json($respnse), 200);
        }

        $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Incomplete Details"));
        $this->response($this->json($error), 200);
    }

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 19-8-2014
     * @desc get all state list as per country selected 
     */
    function getAllAccessNO($return = false, $state = '') {
        $resellerId = $this->_request['resellerId'];
        if ($state == '') {
            $state = $this->_request['state'];
        }

        $response = $this->login(true);
        include_once(CLASS_DIR . "callingCard_class.php");
        $cardobj = new callingCard_class();

        if ($response[RESPONSE] == "1") {

            $userId = $response["id"];

            include_once(CLASS_DIR . "phonebook_class.php");
            $phoneObj = new phonebook_class();

            $accessNoList = $phoneObj->getAccessNumberBystate($resellerId, $state, $userId);
            $accessNoRes = json_decode($accessNoList, TRUE);

            if ($accessNoRes['status'] == 'error') {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => '301', "message" => $stateRes['msg']));
                $this->response($this->json($error), 200);
            }

            if ($return) {
                return $accessNoRes['data']['hashValue'];
            }

            $respnse[RESPONSE] = "1";
            $respnse[MESSAGE] = array("code" => "", "message" => "All available access number list.");
            $respnse[CONTENT] = array($accessNoRes['data']['hashValue']);
            $this->response($this->json($respnse), 200);
        }

        $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Incomplete Details"));
        $this->response($this->json($error), 200);
    }

    function allCountyAccessNo() {

        $countryList = $this->getAllCountry(true);
        $countryWstate = array();
        foreach ($countryList as $key => $val) {
            $state['countryCode'] = $key;
            $state['countryName'] = $val;
            $data = $this->getAllState(true, $key);
            foreach ($data as $sKey => $sVal) {
                $stData['stateName'] = $sVal;
                $accData = $this->getAllAccessNO(true, $sVal);
                foreach ($accData as $aKey => $aVal) {
                    $accNo['accessNo'] = $aKey;
                    $accNo['extensionNO'] = $aVal;
                    $stData['accessNo'][] = $accNo;
                }

                $state['state'][] = $stData;
            }

            $countryWstate[] = $state;
        }

        $respnse[RESPONSE] = "1";
        $respnse[MESSAGE] = array("code" => "", "message" => "All available access number list.");
        $respnse[CONTENT] = $countryWstate;
        $this->response($this->json($respnse), 200);
//      print_r($countryWstate);
//      die();
    }

    /**
     * @author ANkit patidar <ankitpatidar@hostnsoft.com>
     * @param class variable _request that contains username and password
     * @abstract from mobile app
     * @since 13/6/2014
     * @uses api to redirect use to buymorepage
     */
    private function buymorePage() {

        $userName = $this->_request['user'];
        $password = $this->_request['password'];
        $response = $this->login(true);

        if ($response[RESPONSE] == "1") {


            $userId = $this->funobj->getUserId($userName);

            //check user Id
            if (!is_numeric($userId)) {
                $errorRes = json_decode($userId, TRUE);
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "143", "message" => $errorRes['msg']));
                $this->response($this->json($error), 200);
            }



            //get domain resellerId
            $domainResellserId = $this->funobj->getDomainResellerIdFromVerifiedNum($userId, 1);

            //check domainresellerId
            if ($domainResellserId == 0) {

                $error = array(RESPONSE => "0", MESSAGE => array("code" => "141", "message" => 'Invalid reseller Id!!!'));
                $this->response($this->json($error), 200);
            }


            //get domain name
            $domainName = $this->funobj->getResellerDomain($domainResellserId, 1);

            echo $domainName;
            //check domain name
            if (!$domainName) {
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "151", "message" => 'Invalid reseller Domain!!!'));
                $this->response($this->json($error), 200);
            }

            $para['userName'] = $this->_request['user'];
            $para['password'] = $this->_request['password'];
            $para['domainName'] = $domainName;
            $_REQUEST['action'] = "";

            include_once(ROOT_DIR . 'controller/loginController.php');

            $loginObj->redirectToBuymorePage($para, array());
        }

        $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Incomplete Details"));
        $this->response($this->json($error), 200);
    }

    // for internal use only dont share publically
    private function deleteNumber() {
        $auth = "1234667753asdfggqert";
        $number = $this->_request['number'];
        $pin = $this->_request['pin'];
        $type = $this->_request['type'];
        if ($auth != $pin) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "Incomplete Details"));
            $this->response($this->json($error), 200);
        }

        $this->checkRegx($number, NOTNUM_REGX, "Invalid User Id!", '143', 20);

        if ($type == 1)
            $table = "91_verifiedNumbers";
        else
            $table = "91_tempNumbers";

        $result = $this->funobj->deleteData($table, " verifiedNumber = '" . $number . "' ");
        if (!$result) {
            $error = array(RESPONSE => "0", MESSAGE => array("code" => "108", "message" => "unable to delete the number"));
            $this->response($this->json($error), 200);
        }
        $error = array(RESPONSE => "1", MESSAGE => array("code" => "108", "message" => "number deleted successfuly"));
        $this->response($this->json($error), 200);
    }
    
    
    private function seeCallRate(){
        //get call rate of the user 
        //call see call rate  
        
        $response = $this->login(true);

        if ($response[RESPONSE] == "1") {
        
            include_once CLASS_DIR.'call_class.php';
            $callClsObj = new call_class();
            $param['source'] = $this->_request['source'];
            $param['destination'] = $this->_request['destination'];
            $tariffId = $response[CONTENT]['tariffId'];
            
            $callRateResult = $callClsObj->seeCallRate($param,$tariffId);
            if($callRateResult){
                $error = array(RESPONSE => "1", MESSAGE => array("code" => "8100", "message" => "success"),CONTENT => array($callClsObj->data));
                $this->response($this->json($error), 200);
            }else{
                $error = array(RESPONSE => "0", MESSAGE => array("code" => $callClsObj->code, "message" => $callClsObj->msg));
                $this->response($this->json($error), 200);
            }
            
        }
    }
    
    private function checkCallStatus(){
        //get call rate of the user 
        //call see call rate  
        
        $response = $this->login(true);

        if ($response[RESPONSE] == "1") {
        
            include_once CLASS_DIR.'call_class.php';
            $callClsObj = new call_class();
            $param['uniqueId'] = $this->_request['uniqueId'];
            $userId = $response['id'];
            
            $callRateResult = $callClsObj->callResponse($param,$userId);
            $callRateResult = json_decode($callRateResult,true);
            if($callRateResult['status'] == "success"){
                $data[] = array("status"=>$callRateResult['msg'],"timeElapsed"=>$callRateResult['timeYet']);
                $error = array(RESPONSE => "1", MESSAGE => array("code" => "8200", "message" => "success"),CONTENT => $data);
                $this->response($this->json($error), 200);
            }else{
                $error = array(RESPONSE => "0", MESSAGE => array("code" => "8201", "message" => $callRateResult['msg']));
                $this->response($this->json($error), 200);
            }
            
        }
    }
    

}

$api = new API();
$api->processApi();
?>
