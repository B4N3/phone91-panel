<?php
/*
 * @author rahul <rahul@hostnsoft.com>
 * @package Phone91
 * class use for containt all general function  
 * 
 */


class fun extends commonFunction 
{
   
    var  $lastInsertId;
    
    var  $param;
    var  $funResponse;
    var  $funMessage;
    var  $status = "error";
    var  $msg;
    var  $code;
    var  $data;
    var  $email;
    var  $userId;
    var  $fbgl = false;
    var  $isTemp = false;
    
    
    #function use for clear browser cache
    function clearBrowserCache() 
    {
        header("Pragma: no-cache");
        header("Cache: no-cache");
        header("Cache-Control: no-cache, must-revalidate");
    }

    #function use for connect databse 
    function db_connect() 
    {
        //$con = mysql_connect("216.245.201.194","voipswitchuser",'+4H8ZXcSyWn7CuX*') or die(" Couldnot connect to the server ");
        if(HOST_NAME == TESTING_SERVER_NAME)
        {
             $con = mysql_connect("localhost", "voip91_switch", 'yHqbaw4zRWrUWtp8') or die("Couldnot connect to the server" . mysql_error());
             mysql_select_db("voip91_switch", $con) or die(" Database Not Found ");
        }  
        else
        {  
            $con = mysql_connect("localhost", "phone91", 'yHqbaw4zRWrUWtp8') or die("Couldnot connect to the server" . mysql_error());
            mysql_select_db("voip", $con) or die(" Database Not Found ");
        }
        return $con;
    }

    function connecti() 
    {
        $con = mysqli_connect('localhost', 'phone91', 'yHqbaw4zRWrUWtp8', 'voip') or die("Couldnot connect to the server" . mysqli_connect_error());
        /* check connection */

        if (!$con)
            die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());

        // echo 'Success... ' . mysqli_get_host_info($con) . "\n";
        return $con;
    }

    /**
     * @author Sameer Rathod
     * @example :: This is the description of the function 
     *             function is used ti valdiate the before 
     *             login process ie check is user has confirm
     *             the contact number or not and also check
     *             for the currency
     * @filesource
     * @return int
     */
    public function validateBeforeLogin() {
    
        if(isset($_SESSION['loginFlag']) && !empty($_SESSION['loginFlag']))
        {
            #if user has currency and verify number then return 1 (go to userhome page)
            if($_SESSION['loginFlag'] == 2)
                return 1;
            else
            $this->redirectVieLoginFlag($_SESSION['loginFlag']);
           
        }
        else 
        {
            $result = $this->selectData("beforeLoginFlag", "91_userLogin"," userId =".$_SESSION['id']."");
            if($result && $result->num_rows > 0)
            {
                
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $_SESSION['loginFlag'] = $row['beforeLoginFlag'];
                if($_SESSION['loginFlag'] == 2)
                return 1;
                else
                $this->redirectVieLoginFlag($_SESSION['loginFlag']);
            }
            else
                return 0;
         }
         
        
    }
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @date 17/01/2014
     * @desc function use to redirect in login time according to login flage :  
     *       0 : redirect for get currency
     *       1 : redirect for get contact number 
     *       2 : redirect userhome page   
     */
    function redirectVieLoginFlag($loginFlag){
        
        
        if($loginFlag == 0)
        {
            #in case of phone91
            if(isset($_SESSION['domain']) && $_SESSION['domain'] == "phone91.com"){
            $userDetail = base64_encode(base64_encode(json_encode( array( base64_encode(base64_encode($_SESSION['id'])) ))));
            header('Location: http://'.$_SESSION['domain'].'/signup-step.php?msg=101&error='.$userDetail); 
            }
            else
            {
                header("location: /beforeLogin.php");
                
            }
            exit();
                
        }else if($loginFlag == 1)    
        {
            
            $hisNumber = '';
            if(isset(  $_SESSION['verifiedNo']) && strlen(  $_SESSION['verifiedNo']) > 0 )
            {
                $hisNumber = '?verifiedNumber='. $_SESSION['verifiedNo'];
            }
            
            if(isset($_SESSION['domain']) && $_SESSION['domain'] == "phone91.com"){
                header("location:"."http://".$_SESSION['domain']."/signup-step.php".$hisNumber);
            }
            else{
                header("location: /beforeLogin.php".$hisNumber);
            }
            exit();
        }
        
    }
    
    public function validateAdmin() {
        if($this->check_admin())
        {
            header("location:"."http://".HOST_NAME.ADMIN_DIR."index.php");
            exit();
        }
        else
            return 0;
    }
    #function use to check user login or not 
    function login_validate() 
    {
       
        
        if (isset($_SESSION['id']) && strlen($_SESSION['id']) > 0)
        {
//            $this->validateAdmin();
            $loginValidate = 1;
            $loginValidate = $this->validateBeforeLogin();
            return $loginValidate;
        }
        else 
        {
            $_SESSION['login_error'] = "Please proceed with username and password.";
            return 0;
        }
    }
    
    function checkIpAttack($type='0') 
    {
        /* function return total number of last login from user current Ip */
       
        $iparr = array("111.118.250.235","111.118.250.236","111.118.250.237","111.118.250.238");
        if(in_array($this->getUserIP(),$iparr))
            return 0;
        
        
        if($type == '1')
            $condition = " AND type = 1";
        else
            $condition = "";  
        
        $result =  $this->selectData( 'count(*)', "91_userHistory", "ip like '%" . $this->getUserIP() . "' and date > DATE_SUB(now(), INTERVAL 360 MINUTE) ".$condition );
        
        if( $result->num_rows > 0 ) 
        {	
            while($row = $result->fetch_array(MYSQL_ASSOC) ) 
            {
               $noIp = $row['count(*)'];
            }
        }
        else 
        {
            $noIp = 0;
        }
        return $noIp;
    }

    
    function addUserHistory($type = '0')
    {
        $table = "91_userHistory";
        
        
        if($type == '1')
            $data = array( "ip" => $this->getUserIP() , "type" => '1');
        else
            $data = array( "ip" => $this->getUserIP() );
         
        $response = $this->insertData($data, $table);
        
        if (!$response)
        {
            return 0;
        }
        else 
        {
             return 1;
        }
    }
    
    
    #function use to check user login or not 
    function checkSession() 
    {   
        if (isset($_SESSION['id']) && strlen($_SESSION['id']) > 0)
        {
            return 1;
        }
        else 
        {
            $_SESSION['login_error'] = "Please proceed with username and password.";
            return 0;
        }
    }
    
    
    
    
    
    /**
     * @uses for get currency from apc
     * @param string $currency
     * @param int $type 1 for name from id, 2 for id from name
     * @return int | string
     */
    public function getCurrencyViaApc($currency,$type =1) 
    {
        
        $apcArray = "";
        $apcArray = apc_fetch("currency");
        if(!is_array($apcArray) || $apcArray == "")
        {
            $result = $this->selectData('currencyId,currency', '91_currencyDesc');
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
            {
                $data[$row['currencyId']] = $row['currency'];
            }
            $apcStore = apc_store("currency", $data);
            $apcArray = $data;
        }
        
        if($type == 2)
        {
            $apcArray =array_flip($apcArray);
        }
        
        if(isset($apcArray[$currency]))
            return $apcArray[$currency];
        else
            return 0;
    }
    
     
    /**
     * @uses for get currency
     * @param string $currency_name
     * @return int
     */
    public function getCurrency($currency_name) 
    {
        
        $condition = "currency = '" . trim($currency_name) . "'";
        $result = $this->selectData('currencyId', '91_currencyDesc',$condition );
            
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            return $row['currencyId'];
        }
    }

    #function for get currency name
    /**
     * @method to get currecy name
     * @param int $currency_id
     * @return string
     */
    public function getCurrencyName($currency_id) 
    {
        $result = $this->selectData('currency', '91_currencyDesc', "currencyId = '" . trim($currency_id) . "'");
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
        {
            return $row['currency'];
        }
    }
    
    
    
     
    
    /**
     * @uses to get currency detail
     * @param int $id_tariff
     * @return string
     */
    function get_currency($id_tariff) 
    {
        if ($id_tariff == 8) 
        {
            $cid = 1;
        } 
        else if ($id_tariff == 7) 
        {
            $cid = 2;
        } 
        else if ($id_tariff == 9) 
        {
            $cid = 3;
        }

        //get currency name
        $result = $this->selectData('name','currency_names',"id='$cid'");
        
        $cur = $result->fetch_array(MYSQLI_ASSOC);
        
        return $currency = $cur['name'];
    }
    
    /**
     * 
     * @param type $resellerId
     * @param type $currencyId
     * @return int
     */
    function getResellerDefaultCurrency($resellerId , $currencyId,$type = NULL,$domainId = NULL)
    {
        
//        var_dump($resellerId);
        /**
         * @author sameer rathod 
         * @desc get the defalut currency of the reseller
         */
        if(is_null($resellerId))
            return 0;
        if( is_null($type) && (is_null($currencyId) || !is_numeric($currencyId)))
            return 0;
        if(!is_null($domainId) && preg_match(NOTNUM_REGX, $domainId))
            return 0;
        if( $type==2 && is_null($domainId))
            return 0;
        
        $table = "91_resellerDefaultCurrency";
        if(is_null($type))
        {
            $coloumn = "tariffId,balance";
            $condition = "resellerId = ".$resellerId." and currencyId =".$currencyId;
        }
        elseif($type == 1){
            $coloumn = "tariffId,currencyId";
            $condition = "resellerId = ".$resellerId." ";
        }
        elseif($type == 2)
        {
            $coloumn = "*";
            $condition = "resellerId = ".$resellerId." and domainId=".$domainId."";
        }
        
        $result = $this->selectData($coloumn, $table,$condition);
        return $result;
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @uses function to convert amount to particular currency 
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return float
     */
    function currencyConvert($from, $to, $amount) 
    {
        $protocol = $this->getProtocol();
        
        if(CALLSERVERURL == 'http://localhost/')
            $tempUrl = 'voice.phone91.com/';
        else
            $tempUrl = CALLSERVERURL;

        $url = $tempUrl."currency/index.php?from=$from&to=$to&amount=$amount";

        //nedd to change after 1500 request per month
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
	
        return $response;   
    }
 

//end of currency convert function
    
    /**
     * @uses function for protect sql injection
     * @param string $s
     * @return string
     */
    function sql_safe_injection($s) 
    {
        return $this->db->real_escape_string($s);
    } //end sql_safe_injection() function


    
    /**
     * @uses function not in use 
     * @param int $id
     * @param int $type
     * @return string
     */
    function delete_client($id, $type) {
        $con = $this->connect();
        if ($id == '') 
        {
            echo "Invalid Id";
            $_SESSION['msg'] = "Invalid Id";
            exit();
        }
        $to = 'rahul@hostnsoft.com';
        $subject = 'Error while deleting client';
        $message = "Error in phone880 = ";
        if ($type != 2) 
        {
            $qry1 = "delete from clientsshared where id_client='" . $id . "' ";
            $result1 = mysql_query($qry1, $con) or mail($to, $subject, $message . mysql_error());
            $qry2 = "delete from calls where id_client='" . $row['id_client'] . "' ";
            $result2 = mysql_query($qry2, $con) or mail($to, $subject, $message . mysql_error());
            $qry3 = "delete from contact where userid='" . $id . "' ";
            $result3 = mysql_query($qry3, $con) or mail($to, $subject, $message . mysql_error());
            $qry4 = "delete from tempcontact where userid='" . $id . "' ";
            $result4 = mysql_query($qry4, $con) or mail($to, $subject, $message . mysql_error());
            $qry5 = "delete from payments where id_client='" . $id . "' ";
            $result5 = mysql_query($qry5, $con) or mail($to, $subject, $message . mysql_error());
            if ($result1 and $result2 and $result3 and $result4 and $result5) 
            {
                $_SESSION['msg'] = 'Client Deleted Successfully';
                $response = 'Client Deleted Successfully';
            }
        } 
        else 
        {
            $sql = "select * from tariffreseller where id_reseller='" . $id . "' ";
            $res = mysql_query($sql);
            if (mysql_num_rows($res)) 
            {
                while ($row = mysql_fetch_array($res)) 
                {
                    $qry1 = "delete from tariffsnames where id_tariff='" . $row['id_tariff'] . "' ";
                    $result1 = mysql_query($qry1, $con) or mail($to, $subject, $message . mysql_error());
                    $qry8 = "delete from tariffs where id_tariff='" . $row['id_tariff'] . "' ";
                    $result8 = mysql_query($qry8, $con) or mail($to, $subject, $message . mysql_error());
                }
            }
            $qry2 = "delete from tariffreseller where id_reseller='" . $id . "' ";
            $result2 = mysql_query($qry2, $con) or mail($to, $subject, $message . mysql_error());
            $sqlc = "select * from clientsshared where id_reseller='" . $id . "' ";
            $resc = mysql_query($sqlc);
            if (mysql_num_rows($resc)) 
            {
                while ($row = mysql_fetch_array($resc)) 
                {
                    $qry3 = "delete from contact where userid='" . $row['id_client'] . "' or userid='" . $id . "'";
                    $result3 = mysql_query($qry3, $con) or mail($to, $subject, $message . mysql_error());
                    $qry4 = "delete from tempcontact where userid='" . $row['id_client'] . "' or userid='" . $id . "'";
                    $result4 = mysql_query($qry4, $con) or mail($to, $subject, $message . mysql_error());
                    $qry5 = "delete from payments where id_client='" . $row['id_client'] . "' or id_client='" . $id . "'";
                    $result5 = mysql_query($qry5, $con) or mail($to, $subject, $message . mysql_error());
                    $qry6 = "delete from calls where id_client='" . $row['id_client'] . "' or id_client='" . $id . "'";
                    $result6 = mysql_query($qry6, $con) or mail($to, $subject, $message . mysql_error());
                }
            }
            $qry7 = "delete from clientsshared where id_reseller='" . $id . "' or id_client='" . $id . "'";
            $result7 = mysql_query($qry7, $con) or mail($to, $subject, $message . mysql_error());
            if ($result2 and $result7) 
            {
                $_SESSION['msg'] = 'Client Deleted Successfully';
                $response = 'Client Deleted Successfully';
            }
        }
        mysql_close($con);
        return $response;
    }

//end delete_client() function
 
    
    
    /**
     * @uses function use for set Session value in login time 
     * @param int $userid
     * @return void 
     */
    function initiateSession($userid) 
    {

        #get user detail and set session value 
        $table = '91_userLogin';
        $result = $this->selectData('callingStatus,userPin,sipFlag,beforeLoginFlag,type,deleteFlag,isBlocked,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,userName,userId',$table,"userId = '" . $userid . "' ");
        
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            extract($row);          //userId,userName,password,isBlocked,type      
            
//            session_unset();
//            unset($_SESSION);
//            session_destroy();
//            
//             define('ADMIN_SESSION' , $type );
//            include_once 'session.php';
            
           
            
            //new memcacheSessionHandler();
            
            //session_start();
            
          // ini_set('session.cookie_lifetime', 60 * 60 * 2   );
            //ini_set('session.gc_maxlifetime',60 * 60 * 2 );

            session_start();
            
            # set session username
            $_SESSION['username'] = $userName;
            # set session userid
            $_SESSION['id'] = $userId;
            $_SESSION['userid'] = $userId;
            $_SESSION['userId'] = $userId;
            $_SESSION['contact_no'] = '';
    
            $_SESSION['client_type'] = $type;
            
            $_SESSION['passwd'] = $password;

            /*
             * 
             */
            
//            if($type == '3' )
//            {
//                ini_set('session.cookie_lifetime', 60 * 60 * 24 );
//                ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
//            }
//            else if( $type == '2' || $type == '1' )
//            {
//                ini_set('session.cookie_lifetime', 60 * 60 * 1   );
//                ini_set('session.gc_maxlifetime',60 * 60 * 1 );
//            }
            
       
            
            #set personal detail  
            $table = '91_personalInfo';
            $result =  $this->selectData('*',$table,"userId = '" . $userid . "' ");
            
            // processing the query result
            if ($result->num_rows > 0) 
            {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                extract($row);
                $_SESSION['name'] = $name;
                $_SESSION['sex'] = $sex = 1 ? "Male" : "Female";

                //1array(15) { ["slNo"]=> string(1) "2" ["userId"]=> string(5) "30890" ["name"]=> string(5) "Lovey" ["telNo"]=> string(0) "" 
                //["age"]=> string(2) "18" [""]=> string(1) "0" ["dob"]=> string(10) "0000-00-00" ["address"]=> string(7) "Indoree"
                // ["city"]=> string(6) "Indore" ["zipCode"]=> string(6) "454545" ["state"]=> string(2) "MP" ["country"]=> string(5) "India" 
                // ["countryCode"]=> string(2) "91" ["pinCode"]=> string(6) "454545" ["emailId"]=> string(19) "rahul@hostnsoft.com" }
            }

            #set tariff id in session 
            $table = '91_userBalance';
            $result =  $this->selectData('*',$table,"userId = '" . $userid . "' ");
            
            // processing the query result
            if ($result->num_rows > 0) 
            {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                extract($row);
                $_SESSION['id_tariff'] = $tariffId;
                $_SESSION['currencyId'] = $currencyId;
                $_SESSION['chainId'] = $chainId;
                $_SESSION['currencyName'] = $this->getCurrencyViaApc($currencyId,1);
                $_SESSION['resellerId'] = $resellerId;
                $_SESSION['protocol'] = $this->getProtocol();
                
            }

        }
    }

    
    /**
     * @uses to get user email detail by email id 
     * @param string $email
     * @return boolean | array
     */
    function getUserFromEmail($email , $resellerId = NULL, $type= '1' ) 
    {
        
        if($resellerId)
        {
            $resellerIdServer = $resellerId;
        }
        else
         $resellerIdServer = $this->getDomainResellerId(HOST_NAME);
        
        $table = '91_verifiedEmails';
        
        
        $result =  $this->selectData('userid',$table,"email = '" . $email . "' and domainResellerId = '" . $resellerIdServer . "' ");
        
        // processing the query result
        if ($result->num_rows > 0) 
        {
            
            if($type == '1')
            {
                $emailDetails = array();
                
                    while ( $row = $result->fetch_array(MYSQLI_ASSOC) ) 
                    {
                        $emailDetails[] = $row;
                    }
                   
                    
                    return $emailDetails; 
                    
            }   
            
            foreach ($result->fetch_array(MYSQLI_ASSOC) as $row) 
            {
                return $row;
            }
        }
        else
            return false;
    }

    
    /**
     * @uses to check user has verified email or not
     * @param type $email
     * @return boolean
     */
    function checkEmail($email , $redirectUrl=NULL) 
    {
        $userid = $this->getUserFromEmail($email);
       
        if ($userid) 
        {
            $this->initiateSession($userid);
            
            header("location: /userhome.php");
            exit();
        } 
        else 
        {
            return false;
        }
    }

    
    /**
     * @author rahul <rahul@hostnsoft.com>
     * @uses to get data of login user 
     * @param string $userName
     * @param string $pwd
     * @return type
     * @usedIn : api for checking user and checking usercredential
     * last updated by sudhir pandey <sudhir@hostnsoft.com> 
     * last updated by sameer rathod <sameer@hostnsoft.com> 
     */
    function checkLogin($userName, $pwd,$type=null) 
    {
	
        if(preg_match('/[^a-zA-Z0-9\.\_\@]+/', $userName) ||$userName== "")
        {
            return 0;
        }
        
        if(is_null($type))
        {
            
            if(preg_match(NOTPASSWORD_REGX, $pwd) || empty($pwd))
            {
                return 0;
            }
            
           $condition = "userName='" . $userName . "' and password=AES_ENCRYPT('".$pwd."','".ENCRYPT_KEY."')";
         
        }
        else if(1 == $type)
        {
            $condition = "userName='" . $userName . "' ";
        }
        else if (2 == $type)
        {
           
            
            if(preg_match(NOTPASSWORD_REGX, $pwd) || empty($pwd))
            {
              
                return 0;
            }
           $condition = "userId='" . $userName . "' and password=AES_ENCRYPT('".$pwd."','".ENCRYPT_KEY."')";
           
        }
        else{
            return 0;
        }
       
	
//        $userName = $this->db->real_escape_string($userName);
//        $pwd = $this->db->real_escape_string($pwd);
        # get all detail of login user like (isBlock status ,user deleted or not etc)
        $result = $this->selectData('userId,userName,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,isBlocked,deleteFlag,type,resellerId,sipFlag,tariffId,beforeLoginFlag','91_manageClient',$condition);
       
        return $result;

    }
    
    function checkLoginNew($user, $pwd,$userIdType,$passwordType ) 
    {
	
        if(preg_match('/[^a-zA-Z0-9\.\_\@]+/', $user) ||$user== "")
        {
            return 0;
        }
        
        if($userIdType == 1){
            $condition = "userName='" . $user. "'";
        }
        else if($userIdType == 2){
            $condition = "userId='" . $user. "'";
        }
        else{
           return 0; 
        }
        
        switch($passwordType){
            case 1:
                //case of password
                if(preg_match(NOTPASSWORD_REGX, $pwd) || empty($pwd))
                {
                    return 0;
                }
                
                $condition .= " and password=AES_ENCRYPT('".$pwd."','".ENCRYPT_KEY."')";
                
            break;
            case 2:
                //case of pin 
                if(preg_match(NOTNUM_REGX, $pwd) || empty($pwd))
                {
                    return 0;
                }
                $condition .= " and userPin='".$pwd."'";
                
            break;
            case 3:
                //incase of auth key leave the passwor blank
                //fetch the user on the basis of userId or userName
                //and check the passorwd out side the function
                
            break;
            
            
        }
      
        
//        $userName = $this->db->real_escape_string($userName);
//        $pwd = $this->db->real_escape_string($pwd);
        # get all detail of login user like (isBlock status ,user deleted or not etc)
        $result = $this->selectData('userId,name,userName,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,isBlocked,deleteFlag,type,resellerId,sipFlag,tariffId,beforeLoginFlag,balance','91_manageClient',$condition);
       
        return $result;

    }

    
    function checkValidation($userName , $password)
    {
        if(preg_match(NOTPASSWORD_REGX,$password ))
        {
            $_SESSION['error'] = "please enter a valid password must not containg any spacial character other than '@_-!$()[]{}?' ";
             return 0;
        }
        
        if(preg_match(NOTUSERNAME_REGX,$userName ))
        {
            $_SESSION['error'] = "user name are not valid!";
            return 0;
        }
        
        return 1;
    }

  
    /**
     * @uses to login user 	 
     * @param int $userid
     * @param string $pwd
     * @param type $remember_me
     * @param string $host
     * @param int $signup
     * @abstract callfrom index/loginController
     * modified by Ankit Patidar <ankitpatidar@hostnsoft.com> on 12/6/2014 $domainName parameter added
     * @return boolean
     */
  function login_user($userId, $pwd, $remember_me,$host = NULL,$signUp = 0,$tempVar= array(),$domainName = '',$fileName=NULL,$macAddress = NULL) 
  {
        
        session_unset();
        session_destroy();
        session_start();
        
       if(is_null($fileName))
           $fileName = "/sign-in.php";
       
        if($this->checkIpAttack('1') > 7 && $signUp != '1')
        {
            $_SESSION['error'] = "Your  IP has exceeded the trial limit. Please try after 24 hours or contact support. We would be glad to assist you.";
            header("location: http://".$host."?error=".$_SESSION['error'].$this->param);
            exit();
        }
        
        
        
        $userId = strtolower($userId);
        
        $userIp = $this->getUserIP();

         $response =  $this->checkBlockUser($userIp);

        if($response)
        {
           $this->addUserHistory('1');
           $_SESSION['error'] = "Invalid User Please try again later";
           header("location: http://".$host."?error=".$_SESSION['error'].$this->param);
           exit();
        }
        
        $response = $this->checkValidation($userId,$pwd);
      
        if(!$response)
        {
            $this->addUserHistory('1');
            header("location: http://".$host."?error=".$_SESSION['error'].$this->param);
        }
        
        $userId = $this->sql_safe_injection($userId);
        $pwd = $this->sql_safe_injection($pwd);

        $_SESSION['currentHost'] = $host;
        $uid = '';
        
        #check login failed time 
        
        $loginAttampt = $this->checkLoginFailed($userId);
        
        if (($loginAttampt > 10)) 
        {
               $this->addUserHistory('1');
               $_SESSION['error'] = "Maximum Number of request exceed.";
               header("location: http://".$host."?error=".$_SESSION['error'].$this->param);
               exit();
        }

        if(!empty($tempVar))
            $_SESSION['tempVar'] = $tempVar;
        //call function to check login
        
       
        $result = $this->checkLogin($userId, $pwd);
      
        //trigger_error('user details:'.$userId.' pass:'.$pwd.' num:'.$result->num_rows);
        
        if(!$result || $result->num_rows < 1)
        {
            $this->addUserHistory('1');
            $_SESSION['error'] = "Invalid userName or password";
            ($host == 'phone91.com')? $sendurl = $fileName."?error=".$_SESSION['error'].$this->param : $sendurl = "?error=".$_SESSION['error'].$this->param; 
//            $sendurl = $fileName."?error=".$_SESSION['error'].$this->param ;
            header("location: http://".$host.$sendurl); 
            exit();
        }
        
        if ($result && $result->num_rows > 0 ) 
        {
            $getUserInfo = $result->fetch_array(MYSQLI_ASSOC);
            
            if ($getUserInfo["isBlocked"] != 1 && !isset($_SESSION['tempVar'])) 
            {
                $this->addUserHistory('1');
                $_SESSION['error'] = "Account Blocked.";
                if(($host == 'phone91.com'))
                header("location: http://".$host.$fileName."?error=".$_SESSION['error'].$this->param);
                else
                header("location: http://".$host."?error=".$_SESSION['error'].$this->param); 
                exit();
            }
            
            if ($getUserInfo["deleteFlag"] > 0  && !isset($_SESSION['tempVar'])) 
            {
                $this->addUserHistory('1');
                $_SESSION['error'] = "Account Deleted.";
                if(($host == 'phone91.com'))
                header("location: http://".$host.$fileName."?error=".$_SESSION['error'].$this->param);
                else
                header("location: http://".$host."?error=".$_SESSION['error'].$this->param); 
                exit();
            }
            
            #check batch user expiry date 
            if($getUserInfo['type'] == 4 && !isset($_SESSION['tempVar'])){
                $batchExpiryDate = $this->getUserBatchExpiryDate($getUserInfo['userId']);
                if(strtotime(date('Y-m-d',strtotime($batchExpiryDate))) < strtotime(date('Y-m-d')))
                {
                    $this->addUserHistory('1');
                    $_SESSION['error'] = "your validity for this service is expired.";
                    header("location: http://".$host."?error=".$_SESSION['error'].$this->param); 
                    exit();
                }
            }
           
            if($this->fbgl == true){
                //attach email here 
                include_once(CLASS_DIR."contact_class.php");
                $cntClsObj = new contact_class();
                $resultVerifiedId = $cntClsObj->addVerifiedEmailId($this->email,$getUserInfo['userId'],$getUserInfo['resellerId'],$host);
                
                
                if(!$resultVerifiedId && $cntClsObj->code != '4034')
                {
                    $this->addUserHistory('1');
//                    $_SESSION['error'] = "Error unable to attach email id with this user";
                    $_SESSION['error'] = $cntClsObj->msg;
                    ($host == 'phone91.com')? $sendurl = $fileName."?error=".$_SESSION['error'] : $sendurl = "?error=".$_SESSION['error']; 
                    header("location: http://".$host.$sendurl.$this->param); 
                    exit();
                }
                unset($cntClsObj);
            }
            
            
            #function call for assign value of session 
            $this->initiateSession($getUserInfo["userId"]);
            
            
            //call function to save user details like ip browser, last login time
            $userResp = $this->saveUserSystemDetails($getUserInfo["userId"]);
           
            
           
            if($host == 'phone91.com/basic.php' || $host == 'phone91.com/reseller-api.php' || $host == 'phone91.com/two-way-calling-api.php' )
            {
                $_SESSION['domain'] = 'phone91.com'; $_SESSION['currentHost'] = 'phone91.com';;
                header("location: http://".$host."?msg=". base64_encode(base64_encode(json_encode($_SESSION))).$this->param);
                exit();
            }
            
           
            
            $_SESSION['domain'] = $host;
//            $_SESSION['domain'] = dirname($host);
             
            if($signUp == 1)
            {
                ##- calling function to add mac Address and ip 
                $this->updateAndSetMacAdd( $userId  );
                return TRUE; 
                exit();
             
            }
            
            $redirectUrl ='';
            $redirectUrl = $this->getLandingPage($getUserInfo["userId"]);
//            print("dfasdfas");
//            die();
           $protocol = $this->getProtocol();
            
            if ($_SESSION['client_type'] == 1) 
            {
                $_SESSION['isAdmin'] = 1;                
                $redirectUrl = HOST_NAME.ADMIN_DIR."index.php#!manage-client.php|manage-client-setting.php";
                header("location:".$protocol.$redirectUrl);
            }
            else 
            {
               //$domainResellerId =  $this->getDomainResellerId($_SESSION['domain'] );
                
                if($_SESSION['client_type'] != '2')
                {
                   // var_dump($redirectUrl);
                   // var_dump($domainName);
                    //var_dump($protocol);
                    //die();
                    $this->redirectForLogin( $redirectUrl , $domainName , $protocol  );
                }
                else
                {

                    ##-- code to check Ip. 
                    ##- Here we are checking that user came before same ip or not. 
                    $lastLogin = $this->getLastLoginDetail( $userId , $macAddress); 
                    //var_dump($lastLogin); die();

                    $this->updateAndSetMacAdd( $userId  );
                    $_SESSION['ipCheck'] = '1';

                     if($lastLogin['status'] )
                    {
                        if(!$lastLogin['noIp'])
                        {
                            $userIdbyUserName = $this->getUserId($userId);
                            $defaultno = $this->getDefaultContact($userId);


                           // $_SESSION['verifiedNo'] = $defaultno;
                            $response = $this->updateBeforeLoginFlag($userIdbyUserName , 1);

                            if(!$response)
                            {
                                $this->addUserHistory('1');
                                $_SESSION['error'] = "Something went wrong please try again later";
                                header("location: http://".$host."?error=".$_SESSION['error']);
                                exit();
                            }

                            $resposne = $this->updateVerifiedNoStatus($userIdbyUserName , 0);

                            if(!$response)
                            {
                                $this->addUserHistory('1');
                                $_SESSION['error'] = "Something went wrong please try again later";
                                header("location: http://".$host."?error=".$_SESSION['error']);
                                exit();
                            }



                            $redirectUrl = HOST_NAME."/userhome.php#!contact.php";
                            header("location:".$protocol.$redirectUrl);

                            exit();

                        }
                    }
                    else
                    {
                        $this->addUserHistory('1');
                        $_SESSION['error'] = "Something went wrong please try again later";
                        header("location: http://".$host."?error=".$_SESSION['error']);
                        exit();
                    }
                

                    $this->redirectForLogin($redirectUrl , $domainName ,$protocol  );

                }
                
                
                
                
                ##- code for checking ip ends here.
                
                
                
             
            }
        }
        else
        {
            $this->addUserHistory('1');
            $this->loginFailed($userId);
            $_SESSION['error'] = "Sorry Username and Password are not matched. Please Try with proper details.";
            header("location: http://".$host."?error=".$_SESSION['error'].$this->param);
	    exit();
        } 
       
    }

    
    function redirectForLogin($redirectUrl , $domainName ,$protocol  )
    {

        if(!$redirectUrl)
            $redirectUrl = HOST_NAME."/userhome.php#!contact.php";
        else
            $redirectUrl = HOST_NAME.'/userhome.php#'.$redirectUrl;

        try{

            if($domainName != '')
            {
                header("location:".$protocol.$domainName.'/userhome.php#!setting.php|buymore.php');
                exit();
            }


             header("location:".$protocol.$redirectUrl);
             exit();
        }
        catch(Exception $e)
        {
          trigger_error('exception:problem in redirection');
        }
 
    }
    
    
   function getDefaultContact($userId)
    {
        if(preg_match(NOTUSERNAME_REGX,$userId ))
        {
            $_SESSION['error'] = "user name is not valid!";
            return 0;
        }
        
        $defaultno = 0;
        
        include_once(CLASS_DIR."contact_class.php");
        $conObj = new contact_class();

        $userId = $this->getUserId($userId);

        $defaultno = $conObj->getUserDefaultNumber($userId);
        
        if(is_array($defaultno) && !empty($defaultno))
        {
            $_SESSION['verifiedNo'] = $defaultno['number'];
            $_SESSION['countryCode'] = $defaultno['countryCode'];
        }   
        else
        {
           $allContactsArr = $conObj->getConfirmMobile($userId);

           if(count($allContactsArr) > 0 )
           {
               $_SESSION['verifiedNo'] = $allContactsArr[0]['verifiedNumber'];
               $_SESSION['countryCode'] = $allContactsArr[0]['countryCode'];
           }

        }
        
        return $defaultno;
        
    }
    
    
    function getLastLoginDetail($userId , $macAddress)
    { 
        if(preg_match(NOTUSERNAME_REGX,$userId ))
        {
            $_SESSION['error'] = "user name is not valid!";
            return array("status" => 0 );
        }
        
        if(preg_match(NOTUSERNAME_REGX,$macAddress ))
        {
            $_SESSION['error'] = "user name is not valid!";
            return array("status" => 0 );
        }
        $macAdd = '';
        
        if(!empty($macAddress))
        $macAdd = " or macAddress = '".$macAddress."' ";
        
        $result =  $this->selectData( 'count(*)', "91_loginFailed", "username = '".$userId ."' and status = 1 and ip ='" . $this->getUserIP()."' ".$macAdd );
        
       
        
        $noIp = 0;
        
        if( $result->num_rows > 0 ) 
        {	
            while($row = $result->fetch_array(MYSQL_ASSOC) ) 
            {
               $noIp = $row['count(*)'];
            }
        }
        
        
      
        return array("status" => 1 , "noIp" => $noIp);
        
    }
    
    function updateBeforeLoginFlag( $userId , $loginFlag = 0 )
    {
        if(preg_match(NOTNUM_REGX,$userId ))
        {
            $_SESSION['error'] = "user name is not valid!";
            
            $this->msg = "Error Invalid user Id";
            $this->code = "318";
            $this->status = "error";
            
            return 0;
        }

        $loginFlag = (string) $loginFlag;
        if(!ctype_digit($loginFlag))
        {
            $this->msg = "Error Invalid login flag";
            $this->code = "319";
            $this->status = "error";
            return 0;
        }
            
        
         
        $result =  $this->selectData( 'beforeLoginFlag', "91_userLogin", "userId = '".$userId ."' ");
        if(!$result)
        {
            $this->msg = "Error fetching user details";
            $this->code = "320";
            $this->status = "error";
            return 0; 
        }
            
        
        if( $result->num_rows > 0 ) 
        {	
            while($row = $result->fetch_array(MYSQL_ASSOC) ) 
            {
               $hisFlag = $row['beforeLoginFlag'];
            }
        }
        
        /***This is the most idiotic condition the one who had implemented this 
         * Please understand that if this funcition is used directly by another developer 
         * then a serious issue can be arrised and the bug can be counted in his account
         * and you will be responsible for truoble
         * ***/
//        if($hisFlag == 0)
//            $loginFlag =0;
        
        
        $updData = array("beforeLoginFlag"=>$loginFlag);

        $updRes = $this->updateData($updData,"91_userLogin","userId='".$userId."'");
 
        
        if(!$updRes){
            $this->msg = "Error updating login flag";
            $this->code = "321";
            $this->status = "error";
            return 0;
        }
        else 
            return 1;
    }
    
    function updateVerifiedNoStatus($userId, $status = 0)
    {
       if(preg_match(NOTUSERNAME_REGX,$userId ))
        {
            $_SESSION['error'] = "user name is not valid!";
            return 0;
        }
        
        if(!is_numeric($status))
            return 0;
        
         $updData = array("status"=>$status);
         
         $updRes = $this->updateData($updData,"91_verifiedNumbers","userId='".$userId."'");
        // echo $this->querry;
         ///die();
         if(!$updRes)
             return 0;
         else 
             return 1; 
    }
    
    function updateAndSetMacAdd( $userIdbyUserName  )
    {
        if(preg_match(NOTUSERNAME_REGX,$userIdbyUserName ))
        {
            $_SESSION['error'] = "user name is not valid!";
            return 0;
        }

        $userIp = $this->getUserIP();
        
        $randomStr = md5($this->randomNumber(24)) ;
        
        setcookie( "%$$#@!%$#%", $randomStr, strtotime( '+30 days' ) );
        
        $_SESSION['cookieValue'] = $randomStr;
        
        $data = array( "username" => $userIdbyUserName  , "ip" => $userIp , "status" => 1 , "macAddress"  => $randomStr );

        $response = $this->insertData($data, "91_loginFailed");
        
        //var_dump();
        
        //die();
        
       // echo $this->querry;
       // die();
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 7/05/2014
     */
    function loginUserForLoginAs($userName,$host = NULL,$tempVar= array())
    {
        
        session_unset();
        session_destroy();
        session_start();
         
         trigger_error('enter in loginAs1:'.$userName); 
        if(empty($userName) || preg_match(NOTUSERNAME_REGX,$userName))
        {
            return json_encode(array('status' => 'error','msg' => 'Invalid User name!!!'));
        }
        
        $userName = strtolower($userName);

        $_SESSION['currentHost'] = $host;
       
        if(!empty($tempVar))
            $_SESSION['tempVar'] = $tempVar;
        

        
        $getUserInfo = array();
        $getUserInfo = $this->getUserInformation($userName);
        
       
        if (!empty($getUserInfo)) 
        {
            
            if ($getUserInfo["deleteFlag"] > 0  && !isset($_SESSION['tempVar'])) 
            {
                return json_encode(array('status' => 'error' ,"msg" => 'Accont deleted!!!'));
            }
            
            
           
            #check batch user expiry date 
            if($getUserInfo['type'] == 4 && !isset($_SESSION['tempVar']))
            {
                $batchExpiryDate = $this->getUserBatchExpiryDate($getUserInfo['userId']);
                if(strtotime(date('Y-m-d',strtotime($batchExpiryDate))) < strtotime(date('Y-m-d')))
                {
                    $error = "your validity for this service is expired!!!";
                    return json_encode(array('status' => 'error' ,"msg" => $error));
                }
            }
            
            
            #function call for assign value of session 
            $this->initiateSession($getUserInfo["userId"]);
            
            
            $_SESSION['domain'] = $host;
            
            $redirectUrl ='';
            $redirectUrl = $this->getLandingPage($getUserInfo["userId"]);
//            print("dfasdfas");
//            die();
           $protocol = $this->getProtocol();
             
            if ($_SESSION['client_type'] == 1) 
            {
                $_SESSION['isAdmin'] = 1;                
                $redirectUrl = HOST_NAME.ADMIN_DIR."index.php#!manage-client.php|manage-client-setting.php";
                header("location:".$protocol.$redirectUrl);
            }
            else 
            {
               
                if(!$redirectUrl)
                    $redirectUrl = HOST_NAME."/userhome.php#!contact.php";
                else
                    $redirectUrl = HOST_NAME.'/userhome.php#'.$redirectUrl;
               
                try{
                    
                     header("location:".$protocol.$redirectUrl);
                     exit();
                }
                catch(Exception $e)
                {
                  trigger_error('exception:problem in redirection');
                }
             
            }
        }
        else
        {
           return json_encode(array('status' => 'error','msg' => 'User info not found,Technical error occured!!!'));
        } 
       
    }
    
    
    
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @date 24-02-2014
     * @param type $userId 
     */
    function getUserBatchExpiryDate($userId){
        
      $table = '91_userBalance';
      
      #get chain id for user 
      $result = $this->selectData('userBatchId',$table,"userId = '" .$userId. "'");
      
      if(!$result)
          trigger_error('problem while get user batch id');
      
      if ($result->num_rows > 0) {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $batchId = $row['userBatchId'];
      }
      
      #get expiry date of batch 
      $batchTable = '91_bulkUser';
      
      #get chain id for user 
      $result = $this->selectData('expiryDate',$batchTable,"batchId = '" .$batchId. "'");
      
      if(!$result)
          trigger_error('problem while get user batch expiry date ');
      
      if ($result->num_rows > 0) {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $expirydate = $row['expiryDate'];
      }
      
      return $expirydate;
      
      
      
    }
    
    /**
     * @uses function to change user login
     * @param int $childUserId
     * @param  array $session
     * @return boolean
     */
    function changeUserLogin($childUserId,$session)
    {
        if($session['id'] == "" || preg_match('/[^0-9]+/', $session['id']))
                return false;
        if($childUserId == "" || preg_match('/[^0-9]+/', $childUserId))
                return false;
        
        $parentUserId = $session['id'];
        
        if($session['userType'] == 1)
            $url = "..".ADMIN_DIR."index.php";
        else
            $url = "../user/userhome.php";
        
        session_unset();
        session_destroy();
        session_start();
        
        //call function to initiate session
        $this->initiateSession($childUserId);
        $_SESSION['parentChidFlag'] = 1;
        $_SESSION['parentUserId'] = $parentUserId;
        header('Location:'.$url);
        
    }
    
    
    
    /**
     * @uses function use for logout user and session destroy ..
     * @return void 
     */
    function logout() {
        session_start();
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['msg'] = "Successfully logged out. Thankyou for using our service";
        header('Location: index.php');
    }

     
    /**
     * @uses function to check use is admin or not
     * @return boolean
     */
    function is_admin($userId = Null) 
    {  
        
        if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) 
        {
            return true;
        }
        
        if(!is_null($userId))
            $user = $userId;
        else
            $user = $_SESSION['id'];
        
                
        
        //get admin id
        $result = $this->selectData('adminId','91_adminUser',"adminId='" . $user . "'");
        

          
        if (!$result)
           die("Unable To Fetch User Data");

        if ($result->num_rows > 0) 
        {
           $_SESSION['isAdmin'] = 1;
           return true;
        }
        else
            return false;
    }

  
    /**
     * @uses function use for check user is admin or not by use of is_admin function 
     * @return boolean
     */
    function check_admin($userId = NULL) 
    {
        $admin = $this->is_admin($userId);
        
        if ((!isset($_SESSION['id'])) || !$admin) 
        {
            return false;
        }
        else
            return true;
    }
    
   
    /**
     * @uses function use to check login user is reseller or not 
     * @return boolean
     */
    function check_reseller() 
    {
        if (!isset($_SESSION['id']) || $this->check_admin())
            return false;
    
        if ($_SESSION['client_type'] != 2 && $_SESSION['client_type'] != 1 )
            return false;
        else
            return true;
    }

     
    /**
     * @uses function use to check login user id user or not
     * @return boolean
     */
    function check_user() 
    {
        if (!isset($_SESSION['id'])|| $this->check_admin())
            return false;
        if ($_SESSION['client_type'] != 3)
            return false;
        else
            return true;
    }

    
    /**
     * @uses function use to redirect url
     * @param string $url
     * @param boolean $permanent
     * @param int $statusCode
     * @return void
     */
    function redirect($url, $permanent = false, $statusCode = 303) 
    {
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === "XMLHttpRequest") 
        {
            echo "<script>window.top.location.href='$url'</script>";
        }else 
        {
            if (!headers_sent()) 
            {
                header('location: ' . $url, $permanent, $statusCode);
            } 
            else 
            {
                echo "<script>location.href='$url'</script>";
            }
            exit(0);
        }
    }

   
    /**
     * @uses function for generate password it's a random number 
     * @param int $length
     * @return int
     * function use in api pin user 
     */
    function generatePassword($length=4) 
    {
        #password length
        $password = "";
        $possible = "123456789";
        $i = 0;
        while ($i < $length) 
        {
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            $password .= $char;
                $i++;
            
        }
        
        return $password;
    }

    
    
    /**
     * @uses function use to send sms to usd  
     * @param array $tempparam
     * @return type
     */
    function SendSMSUSD($tempparam) 
    {
        //prepare details for curl
        $connect_url = "http://world.msg91.com/sendhttp.php"; // 
        $param["user"] = "phone91"; // 
        $param["password"] = "Phone91Int"; // beep7 password
        
        //validate sender
        if (isset($tempparam['sender']) && strlen($tempparam['sender']) > 0)
            $param["sender"] = $tempparam['sender']; //
        else
            $param["sender"] = "919893385095";
        
        
        $param["mobiles"] = $tempparam['mobiles'];
        $param["message"] = $tempparam['message'];
       
        $request = '';
        
        //prepare query string
        foreach ($param as $key => $val) 
        {
            $request.= $key . "=" . urlencode($val);
            $request.= "&";
        }
        
        
        $request = substr($request, 0, strlen($request) - 1);
        $url2 = $connect_url . "?" . $request;
        $ch = curl_init($url2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        return $curl_scraped_page;
    }
    
     
    /**
     * @uses function use to send sms to usd 
     * @param array $tempparam
     * @return array
     */
    function SendSMSUSDnew($tempparam) 
    {
       // print_r($tempparam);
        
        //prepare details for curl
        $param["to"] = $tempparam['to'];
        $param["text"] = urlencode($tempparam['text']);
        
        $password = "@%@847Hg%U";
        $extraParam = "";
        if(isset($tempparam["password"]) && !empty($tempparam["password"]))
        {
             $password = $tempparam["password"];
             $extraParam = " &from=12028038240&mo=1";
        }
        
        $apiId = "3451976";
        if(isset($tempparam["apiId"]) && !empty($tempparam["apiId"]))
        {
              $apiId = $tempparam["apiId"];
        }
        
        $connect_url = "https://api.clickatell.com/http/sendmsg?user=phone91&password=".$password."&api_id=".$apiId."&to=".$param["to"]."&text=".$param["text"]."&concat=3".$extraParam; // 
        
        trigger_error($connect_url);
        $ch = curl_init($connect_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        trigger_error($curl_scraped_page);
        return $curl_scraped_page;
    }
    

    
    /**
     * @uses function SEND SMS FOR USD
     * @param array $param
     * @return array
     */
    function SendSMSUSDold($param) 
    {
        //prepare details for curl
        $connect_url = "https://203.142.18.146:8080/server/sendsms/"; // 
        $param["login"] = "callplz1"; // 	
        $param["password"] = "qazwsxedc"; // 
        $param["clientid"] = "7PBq7PB8";
        $param["sender"] = "Phone91";
        $param["message_type"] = "TEXT";
        $param["receiver"] = $param[to];
        $param["message"] = $param[text];
        
        //prepare query string
        foreach ($param as $key => $val) 
        {
            $request.= $key . "=" . urlencode($val);
            $request.= "&";
        }
        
        
        $request = substr($request, 0, strlen($request) - 1);
        $url2 = $connect_url . "?" . $request;
        $ch = curl_init($url2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        return $curl_scraped_page;
    }

    /**
     * @uses function to send sms,Send SMSto 91
     * @param array $tempparam
     * @return array
     */
    function SendSMS91($tempparam) 
    {
        
        //prepare details for curl
        $connect_url = "https://vtermination.com/sendhttp.php"; // Do not change
        //set parameters to send
        $param["user"] = "phone91"; // 
        $param["password"] = "Phone91-Passw0rd"; // 
        
        //validate sender,and set default value
        if ($tempparam['sender'] != "")
            $param["sender"] = $tempparam['sender'];
        else
            $param["sender"] = "Phonee";
        
        $param["mobiles"] = $tempparam['mobiles'];
        $param["message"] = $tempparam['message'];
        $param["route"] = 4;
        $request = '';
        //set request parameter
        foreach ($param as $key => $val) 
        {
            $request.= $key . "=" . urlencode($val);
            $request.= "&";
        }
        
        //remove last '&' character
        $requestComplete = substr($request, 0, strlen($request) - 1);
        //prepare url for initialize
         $preUrl = $connect_url . "?" . $requestComplete;

        $ch = curl_init($preUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
         
        $curl_scraped_page = curl_exec($ch);
        
        //check ,if response false then show curl error
        if ($curl_scraped_page === FALSE) {

            die(curl_error($ch));
        }

        curl_close($ch);
        return $curl_scraped_page;
    }
// not in use
// 
//    function check_user_avail($username = NULL) {
//        if (isset($_REQUEST['username']))
//            $username = $this->sql_safe_injection($_REQUEST['username']);
//        //echo "select login from clientsshared where login='$username'";
//        $result = mysql_query("select login from clientsshared where login='$username'");
//        $res = mysql_num_rows($result);
//        if ($res != 0) {
//            return 0; //echo "Sorry username already in use";
//            exit();
//        } else {
//            return 1;
//            exit();
//        }
//    }
//
//    function check_email_avail($email) {
//        if (isset($email))
//            $email = $this->sql_safe_injection($email);
//        //echo "select login from clientsshared where login='$username'";
//        $sql = "SELECT email FROM contact WHERE email='" . $email . "' AND confirm=1";
//        $result = mysql_query($sql);
//        $res = mysql_num_rows($result);
//        if ($res != 0) {
//            return 0; //echo "Sorry username already in use";
//            exit();
//        } else {
//            return 1;
//            exit();
//        }
//    }

    
    /**
     * @uses function use to check rate according to user plan  
     * @param int $code
     * @return void 
     */
    function check_rate($code) 
    {
        $prefix = $this->sql_safe_injection($code);
        //echo "select login from clientsshared where login='$username'";
       // $dbh = $this->connect();
        
        $result = $this->selectData('description,voice_rate,prefix','tariffs',"prefix like '" . $prefix . "%'  and id_tariff='8'");
        
        //$search_qry = "select description,voice_rate,prefix from tariffs where prefix like '" . $prefix . "%'  and id_tariff='8'";
        //	$search_qry="select login from clientsshared where login like '".$q."%'";
        //$exe_qry = mysql_query($search_qry) or die(mysql_error());
        if ($result->num_rows > 0) 
        {
            while ($res = $result->fetch_array(MYSQLI_ASSOC)) 
            {
                $rate = $res['voice_rate'];
                $country = $res['description'] . ' ' . $res['prefix'];
                echo '<tr>                        
            <td>' . $country . '</td>
			<td>' . $rate . ' USD</td>            
            </tr>
            ';
                //return "";			
            }
        } 
        else 
        {
            echo "0";
        }
        //mysql_close($dbh);
    }

    
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com> 
     * @since 4/3/2013
     * @uses function to verify confirmation code,return 1 if code exist in any table and return 0 if not exist in both table contact and tempcontact
     * @param int $code
     * @param bigInt $number
     * @return int
     * @last update sameer rathod
     */
    function verifyCode($code,$number,$type=NULL, $domainName = NULL,$resellerId=NULL ) 
    {
       
        if(!ctype_digit($code) || !ctype_digit($number)&& $type !='EMAIL' )
            return json_encode(array("msg"=>"Invalid confirm code  ","status"=>"error"));
        elseif($type =='EMAIL' && !preg_match(EMAIL_REGX, $number))
                return json_encode(array("msg"=>"Invalid Input please try again","status"=>"error"));
       
        if($type == 'EMAIL'){
             if(!preg_match(EMAIL_REGX,$number))
                {
                    return json_encode(array("msg"=>"Invalid Input please provide proper email id.","status"=>"error"));
                }
        }else{
            if(!preg_match(PHNNUM_REGX,$number))
                {
                    return json_encode(array("msg"=>"Invalid Input please provide proper contact number.","status"=>"error"));
                }
        }
        
        if(is_null($resellerId))
        {
            //if reseller Id is not passed then get the id from current domain 
            if(is_null($domainName))
                $resellerId = $this->getDomainResellerIdViaApc(HOST_NAME);
            else{
                $resellerId = $this->getDomainResellerIdViaApc($domainName);
            }
            
            if(!$resellerId)
                return json_encode(array("msg"=>"Error Invalid domain","status"=>"error","code"=>"4012"));
            
        }
        else
        {
             if( !ctype_digit($resellerId) || empty($resellerId) )
                return json_encode(array("msg"=>"Error invalid reseller Id ","status"=>"error","code"=>"4013"));
        }
        
        
        
     
        
    //$resellerIdServer = 2;
     //   echo '12345';
        
        $count = 0;
        //query to check code exist in contact table
        
        /* changed by sameer do not delete this is for refrence for what changed done 
        if($type == 'EMAIL')
          $result = $this->selectData("userid","91_verifiedEmails","email='".$number."' and confirm_code='".$code."' and domainResellerId= ".$resellerIdServer);
        else
           $result = $this->selectData("userId"," 91_verifiedNumbers","CONCAT(countryCode,verifiedNumber) = '".$number."' and confirmCode='".$code."' and domainResellerId=".$resellerIdServer);
        */
	if($type == 'EMAIL'){
            $column = "userid";
            $table = "91_verifiedEmails";
            $condition = "email='".$number."' and confirm_code='".$code."' ";
          
        }else{
            $column = "userId";
            $table = "91_verifiedNumbers";
            $condition = "CONCAT(countryCode,verifiedNumber) = '".$number."' and confirmCode='".$code."' ";
           
        }
        
        $condition .= "  and resellerId= ".$resellerId;
            
        $result = $this->selectData($column,$table,$condition);
     
// $result = $this->selectData("userId",$tableName,"CONCAT(countryCode,verifiedNumber) = '".$number."' and confirmCode=".$code);
       // echo ' Query '.$this->querry;
        if($result)
        {
            $count = $result->num_rows;
            $resultArr = $result->fetch_array(MYSQLI_ASSOC);
            
            if($type == 'EMAIL')
                $userId = $resultArr["userid"];
            else
                $userId = $resultArr["userId"];
        }
        //check if row exist or not
        if ($count != 0) 
        {
            $flag = $userId;
        } 
        else 
        { //check in tempcontact table code exist or not
            
           // $resultTemp = $this->selectData("userId","91_tempNumbers","CONCAT(countryCode,tempNumber) = '".$number."' and confirmCode=".$code);
            
            if($type == 'EMAIL')
                $table = "91_tempEmails";
            else{
                $table = "91_tempNumbers";
                $condition = "CONCAT(countryCode,tempNumber) = '".$number."' and confirmCode='".$code."' ";
            }
            
            
            $resultTemp = $this->selectData($column,$table,$condition);
            if(!$resultTemp)
                return json_decode (array("msg"=>"Error getting details ","status"=>"error"));
            
            $countTemp = $resultTemp->num_rows;
            
            $resultArrTemp = $resultTemp->fetch_array(MYSQLI_ASSOC);
            
             if($type == 'EMAIL')
                $userId = $resultArrTemp["userid"];
            else {
              $userId = $resultArrTemp["userId"];    
            }
             
            
            //set value of flag according to countTemp value
            if ($countTemp != 0) 
            {
                $flag = $userId;
                $this->isTemp = true;
            }
            else
                $flag = 0;
        }

        return $flag;
    }

//end of verifyCode function

    #function to send confirmation code via sms or call

    /**
     * @last updated by Ankit Patidar <ankitpatidar@hostnsoft.com> on 4/3/2013
     * @param string $userName it may be username or mobile number
     * @param string $smsCall it is clicked button text SMS or CALL
     * @return type
     */
    function forget_password($userName, $smsCall) 
    {
        //create connection to database
        $con = $this->db_connect();
        $uid = $userName;
        
        include_once(CLASS_DIR."sendSmsClass.php");
        $smsObj = new sendSmsClass();
        
        
        if(preg_match(NOTUSERNAME_REGX,$userName) || $userName == "")
        {
            return json_encode(array("msg"=>"Invalid Input please provide proper User Name","status"=>"error"));
        }

        if (strlen($userName) > 3) 
        {

           $userName =  $this->db->real_escape_string($userName);
           $resultSel = $this->selectData('userId,userName', '91_userLogin','userName="'.$userName.'"');
           
            $res = $resultSel->num_rows;
            if ($res == 0) 
            {
                $show_msg = "Sorry user with this username or id not found.";
                $status = "error";
            } 
            else 
            {
                $getUserInfo = $resultSel->fetch_array(MYSQLI_ASSOC);
                
                //print_r($getUserInfo);
                
                $uid = $getUserInfo['userId'];

                $confirmCode = $this->generatePassword("4");
                if(strlen($confirmCode) < 4)
                {
                    mail("sameer@hostnsoft.com","password error","".$confirmCode);
                    $confirmCode = $this->generatePassword("4");
                   
                }
                //update code in tables
                $data = array("confirmCode"=>"$confirmCode");
                
                $condition = "userId='" . $uid . "'";
                $updRes = $this->updateData($data, "91_verifiedNumbers" , $condition);
                $updRes2 = $this->updateData($data, "91_tempNumbers" , $condition);
                $resultVerSel = $this->selectData('verifiedNumber,countryCode,confirmCode', '91_verifiedNumbers',"userId='" . $uid . "'");

                //echo $this->querry;
                
                
                if ($resultVerSel->num_rows > 0) 
                {
                    $getUserInfo = $resultVerSel->fetch_array(MYSQLI_ASSOC);
                    $contact_no = $getUserInfo['verifiedNumber'];
                    $confirmCode = $getUserInfo['confirmCode'];
//                    var_dump($confirmCode);
//                    if (strlen($contact_no) < 8) {
//                        $temp_flag = 1;
//                    }
                    $code = $getUserInfo['countryCode'];
                    $contact = $code . $contact_no;
//                    var_dump($contact); 
                    //Assign Variables for sending sms to user
                    if ($smsCall == "SMS") {
                        $msg = "Enter this confirmation code " . $confirmCode . " to reset your password."; // sms text for usd					                                        
                        $d['sender'] = "Phone91";
                        $d['message'] = $msg;
                        $d['mobiles'] = $contact;
                        //Assign Variables for sending sms to 91 user
                       // $nine['sender'] = "Phonee";
                        $nine['to'] = $contact; // mobile number without 91
                        $nine['text'] = $msg;
                        //Call function
                        $smsObj->sendMessagesGlobal($nine);
                    }
                    else if ($smsCall == "CALL") {
                        $this->mobile_verification_api($contact, $confirmCode);
                    }
                    $show_msg = "confirmation code has been sent to your mobile";
                    $status = "success";
                    $finalResponse['number'] = $contact;
                } else if ($resultVerSel->num_rows == 0) {//if username or number not found in contact table then search in tempcontact table 
                    $resultTempSel = $this->selectData('tempNumber,countryCode', '91_tempNumbers',"userId=''" . $uid."'");
//                    $result = mysql_query("select tempNumber,countryCode from 91_tempNumbers where userId=" . $uid) or die("Error");
                    //if row found
                    if ($resultTempSel->num_rows > 0) {
                        $getUserInfo = $resultTempSel->fetch_array(MYSQLI_ASSOC);
                        $contact_no = $getUserInfo['tempNumber'];
                        $code = $getUserInfo['countryCode'];
                        $contact = $code . $contact_no;
                        //if SMS button clicked
                        if ($smsCall == "SMS") {
                            //Assign Variables for sending sms to user
                            $d['sender'] = "Phone91";
                            $d['message'] = "Enter this confirmation code " . $confirmCode . " to reset your password."; // sms text for usd
                            $d['mobiles'] = $contact;
                            //Assign Variables for sending sms to 91 user
                            //$nine['sender'] = "Phonee";
                            $nine['to'] = $contact; // mobile number without 91
                            $nine['text'] = "Enter this confirmation code " . $confirmCode . " to reset your password."; // sms text for usd
                            //Call function
                             $smsObj->sendMessagesGlobal($nine);
                        }
                        else if ($smsCall == "CALL") {//if CALL button clicked
                            $this->mobile_verification_api($contact, $confirmCode);
                        }
                        $show_msg = "confirmation code has been sent to your mobile";
                        $status = "success";
                        $finalResponse['number'] = $contact;
                    }//end of if for rows of tempcontact table
                    else {
                        $show_msg = "This User ID does not exists";
                        $status = "error";
                    }
                } //end of else if for search username or number in tempcontact
            } //end of else (if username or uid found in clientsshared table)
        } //end of if

        $finalResponse["msg"] = $show_msg;
        $finalResponse["status"] = $status;
        return json_encode($finalResponse);
    }

    public function sendSmsCall($contactNo,$countryCode,$confirmCode,$smsCallFlag , $userName = NULL)
    {
        include_once(CLASS_DIR."sendSmsClass.php");
        $smsObj = new sendSmsClass();
        $contact = $countryCode . $contactNo;
        //Assign Variables for sending sms to user Username is ".$userName." and
        if ($smsCallFlag == "SMS") {
            $msg = "Hey there, Your verification code is: ".$confirmCode.". Reset your password right away. Happy sharing! Team Phone91 "; // sms text for usd					                                        
            $d['sender'] = "Phone91";
            $d['message'] = $msg;
            $d['mobiles'] = $contact;
            //Assign Variables for sending sms to 91 user
            $nine['sender'] = "Phonee";
            $nine['mobiles'] = $contactNo; // mobile number without 91
            $nine['message'] = $msg;
            //Call function
            $nine['to'] = $contact; // mobile number without 91
            $nine['text'] = $msg;
            //Call function
            $smsObj->sendMessagesGlobal($nine);
            }
        else if ($smsCallFlag == "CALL") {
            $this->mobile_verification_api($contact, $confirmCode);
        }
        else if($smsCallFlag == "MAIL") #- condition Applied By Nidhi<nidhi@walkover.in>
        {
            $this->send_verification_mail($contactNo, $confirmCode,'','' , 1 ) ;
        }
        // handle the response of the sms and call also

    }
    
    public function forgetPassword($userName,$smsCall)
    {
        if(preg_match('/[^a-zA-Z0-9]+/',$userName) || $userName == "")
        {
            return json_encode(array("msg"=>"Invalid Input please provide proper User Name","status"=>"error"));
        }
        
        $serverName = $this->db->real_escape_string(HOST_NAME);
        $serverResellerIdResult = $this->selectData('resellerId', '91_domainDetails',"domain like '".$serverName."'");
        if(!$serverResellerIdResult || $serverResellerIdResult->num_rows <= 0)
        {
            $showMsg = "Internal server error please contact support";
            $status = "error";
            return json_encode(array("msg"=>$showMsg,"status"=>$status));
        }
        else 
        {
            $fetchServerResult = $serverResellerIdResult->fetch_array(MYSQLI_ASSOC);
            $resellerIdServer = $fetchServerResult['resellerId'];
        }
        
        
        if(is_numeric($userName))
        {
            $resultVerSel = $this->selectData('verifiedNumber,countryCode,confirmCode', '91_verifiedNumbers',"varifiedNumber='" . $userName . "' and domainResellerId = '".$resellerIdServer."'");
            $verifiedCount = $resultVerSel->num_rows;
            if(!$resultVerSel || $verifiedCount < 1)
            {
                $userName = substr_replace($userName, "X", 4, 3);
                $showMsg = "Sorry, $userName is not registered with us. You can register by <a href='signup.php' style='color: #030; font-size:24px;' >SignUP</a> !.";
                
                $status = "error";
            }    
        }
        else
        {
           $userId = $this->getUserId($userName);
           
           if(!$userId)
           {
               $showMsg = "Sorry, username is not registered with us. You can register by <a href='signup.php' style='color: #030; font-size:24px;' >SignUP</a> !.";
               $status = "error";
           }
           else
           {
//               else
//               {
//                    $fetchServerResult = $serverResellerIdResult->fetch_array(MYSQLI_ASSOC);
//                    $resellerIdServer = $fetchServerResult['resellerId'];
                   
                $resultVerSel = $this->selectData('verifiedNumber,countryCode,confirmCode', '91_verifiedNumbers',"userId='" . $userId . "' and domainResellerId = '".$resellerIdServer."'");
                
                $verifiedCount = $resultVerSel->num_rows;
           }
        }
        
        if($resultVerSel && $verifiedCount > 0)
        {
            if($verifiedCount == 1)
            {
                $numberArr = $resultVerSel->fetch_array(MYSQLI_ASSOC);
                
                $confirmCode = $this->generatePassword();
                if(strlen($confirmCode) < 4)
                {
                    echo $confirmCode;
                }    
                
                //update code in tables
                
                $data = array("confirmCode"=>$confirmCode);
                $condition = "userId='" . $userId . "' and domainResellerId = '".$resellerIdServer."' and verifiedNumber='".$numberArr['verifiedNumber']."'";
                
                $updRes = $this->updateData($data, "91_verifiedNumbers" , $condition);
                
//              $updRes2 = $this->updateData($data, "91_tempNumbers" , $condition);
                if($updRes)
                {
                    //send sms or call 
                    $this->sendSmsCall($numberArr['verifiedNumber'],$numberArr['countryCode'],$confirmCode,$smsCall );
                    $responseArr['type'] = "1";
                    $responseArr['id'] = $userId;
                    $responseArr['contact'] = array($numberArr['countryCode'].$numberArr['verifiedNumber']);
                    
                    return json_encode($responseArr);
                }
                else
                {
                    $showMsg = "Error process request please contact provider";
                    $status = "error"; 
                }
            }
            else
            {
                while($numberArr = $resultVerSel->fetch_array(MYSQLI_ASSOC))
                {
                    $verifiedNumberResArr[] = $numberArr['countryCode'].$numberArr['verifiedNumber'];
                }
                $responseArr['type'] = "2";
                $responseArr['id'] = $userId;
                $responseArr['contact'] = $verifiedNumberResArr;
                return json_encode($responseArr);
            }
        }               
            return json_encode(array("msg"=>$showMsg,"status"=>$status,"type"=>"0"));
    }
    
    
    /*
     * This function is created to call   Api. This function calls api and returns response in json.
     */
    public function callApi( $accessToken , $url )
    {
        if(empty($accessToken) || empty($url))
        {
           return false; 
        }
        
         $curlUrl = $url.$accessToken;

         $curl = curl_init($curlUrl);
         $curlheader = array();
         curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
         $curlheader[0] = "Authorization: Bearer " . $accessToken;
         curl_setopt($curl, CURLOPT_HTTPHEADER, $curlheader);

         $json_response = curl_exec($curl);
         if(curl_error($curl))
             return false;

         return $json_response;
    }
    
    /**
     * @abstract called from action layer in case of voip91.biz forget password
     * @param type $userName
     * @param type $smsCall
     * @param type $countryCode
     * @param type $userId
     * @return type
     */
    public function forgotPassword($userName,$smsCall ,$countryCode = NULL , $userId = NULL,$flag=NULL,$domain=NULL)
    {
	if($userId != NULL && preg_match(NOTNUM_REGX, $userId))
		return json_encode(array( "msg" => "Please Enter a valid user!","status" => "error" ));
         //  logmonitor('nidhi-phone91', 'Log :: '.$userName );
        
//        $response =  $this->addUserHistory();
        
//        if(!$response)
//        {
//             return json_encode(array( "msg" => "Error while updating history","status" => "error" ));
//        }
//echo "sdfasdf";
        if($this->checkIpAttack() > 7)
        {
            // return json_encode(array( "msg" => "Your  IP has exceeded the trial limit. Please try after 24 hours or contact support. We would be glad to assist you.","status" => "error" ));
        }
      
        if(preg_match('/[^a-zA-Z0-9\@\.\_]+/',$userName) || $userName == "")
        {
            return json_encode(array( "msg" => "This UserName ".$userName." Is Not Registered With Us","status" => "error" , "type" => 0 ));
        }
        
        if($smsCall == 'MAIL')
            $userIdField = 'userid';        
        else
            $userIdField = 'userId';
        
        if(is_null($domain))
            $serverName = $this->db->real_escape_string(HOST_NAME);
        else
            $serverName = $this->db->real_escape_string($domain);   
        
        $resellerIdServer = $this->getDomainResellerId($serverName);
        
//        $serverResellerIdResult = $this->selectData('*', '91_domainDetails',"domainName like '".$serverName."'");
//        var_dump($serverResellerIdResult);
        if(!$resellerIdServer)
        {
            $showMsg = "Internal server error please contact support";
            $status = "error";
            return json_encode(array("msg"=>$showMsg,"status"=>$status , "type" => 0));
        }
        $resultVerSel = 0;
        if(is_numeric($userName) || ctype_digit($userName))
        {
           
            if(empty($countryCode) || !ctype_digit($countryCode))
            {
                $showMsg = "Please Enter Valid Country Code";
                $status = "error";
                return json_encode(array( "msg" => $showMsg, "status" => $status , "type" => 0));
            }
            else 
            {
                $userName = $countryCode.$userName;
            }
            
            if($flag)
                $column = 'resellerId';
            else
                $column = 'domainResellerId';
            
            if(isset($userId) && !empty($userId))
                $condition =  "userId = '".$userId."' and $column = '".$resellerIdServer."' ";
            else
                $condition =  "$column = '".$resellerIdServer."'";
            
            $resultVerSel = $this->selectData('userId,verifiedNumber,countryCode,confirmCode,resellerId', '91_verifiedNumbers',"CONCAT(countryCode,verifiedNumber)='".$userName."' and ".$condition);
            
            
           //echo $this->querry;
            
            $verifiedCount = $resultVerSel->num_rows;
                
            if(!$resultVerSel || $verifiedCount < 1)
            {
                $userName = substr_replace($userName, "X", 4, 3);
                $showMsg = "Sorry, $userName is not registered with us.   ";
                $status = "error";
            }
        }
        else if($smsCall == 'MAIL')
        {
            if(!filter_var($userName, FILTER_VALIDATE_EMAIL)) 
            {
                $showMsg = "Please Enter Valid Email Id";
                $status = "error";
                return json_encode(array( "msg" => $showMsg, "status" => $status , "type" => 0));
            }
            
             if(isset($userId) && !empty($userId))
                $condition =  " AND userid = '".$userId."' ";
            else
                $condition =  "";
            
            
            #- Last modified by nidhi<nidhi@walkover.in>
            #- modification- aplied one more condition in where clause. ie. domain reseller id
            $resultVerSel = $this->selectData('verifiedEmail_id,userid,email,confirm_code,default_email,resellerId', '91_verifiedEmails',"email='".$userName."' " .$condition);
            $verifiedCount = $resultVerSel->num_rows;
            #- modification ends.
            
            if(!$resultVerSel || $verifiedCount < 1)
            {
               $userName = substr_replace($userName, "X", 4, 3);
               $showMsg = "Sorry, $userName is not registered with us.  ";
               $status = "error";
            } 
        }
        else
        {
           $userId = $this->getUserId($userName);
           
           if(!$userId)
           {
               $showMsg = "Sorry, username ".$userName." is not registered with us. ";
               $status = "error";
           }
           else
           {
               
               $resellerIdOfUser =  $this->getResellerId($userId);
               
               if(!$resellerIdOfUser)
                $resellerId = $fetchServerResult['resellerId'];
               else
                 $resellerId = $resellerIdOfUser;
               
               ///$resellerId = 2;

               $resultVerSel = $this->selectData('verifiedNumber,countryCode,confirmCode , resellerId ,'.$userIdField, '91_verifiedNumbers',"".$userIdField."='" . $userId . "' and isDefault=1");
               

               $verifiedCount = $resultVerSel->num_rows;
           }
        }
        
        ////logmonitor('nidhi-phone91', 'Log :: '.$this->querry );
        
        if($resultVerSel && $verifiedCount > 0)
        {
           
            if($verifiedCount == 1)
            {
                $numberArr = $resultVerSel->fetch_array(MYSQLI_ASSOC);
                $userId = $numberArr[$userIdField];
                $confirmCode = $this->generatePassword();
                
                if($smsCall == 'MAIL')
                {
                    $condition = "userId='" . $userId . "'  and email='".$userName."'";
                    $data = array("confirm_code"=>$confirmCode);
                      
                    $varifiedId = $userName;
                    $updRes = $this->updateData($data, "91_verifiedEmails" , $condition);
               
                    if($updRes && $this->db->affected_rows > 0)
                    {
                        $this->sendSmsCall( $varifiedId, $numberArr['countryCode'], $confirmCode,$smsCall );

                        $responseArr['type'] = "1";
                        $responseArr['id'] = $userId;
                        $emailId = $userName; 
                        
                       // print_r($numberArr);
                        
                        $resellerName = $this->getUserId($numberArr['resellerId'] , 1);
                        
                        $userName = $this->getUserId($numberArr['userid'] ,1 );
                        
                        
                        $responseArr['contact'][] =  array( 'number' => $emailId , 'resellerId' => $numberArr['resellerId'] , 'userId' =>$numberArr['userid'] , 'resellerName' => $resellerName , "userName" => $userName );
                        
                        $responseArr['status'] = 'success';
                        $responseArr['msg'] = 'verification code successfully send.';

                        return json_encode($responseArr);
                    }
                    else 
                    {
                        $showMsg = "Error process request please contact provider";
                        $status = "error"; 
                    }
                    
                }
                else
                {
                    if(isset($resellerId) && !empty($resellerId))
                        $condition =  "resellerId = '".$resellerId."'";
                    else
                        $condition =  "$column = '".$resellerIdServer."'";
                    
                    $data = array("confirmCode"=>$confirmCode);
                    $condition = "userId='" . $userId . "' and $condition  and verifiedNumber='".$numberArr['verifiedNumber']."'";
                    
                    $varifiedId = $numberArr['verifiedNumber'];
                    $updRes = $this->updateData($data, "91_verifiedNumbers" , $condition);
                    
                    if($updRes && $this->db->affected_rows > 0)
                    {
                        $this->sendSmsCall( $varifiedId, $numberArr['countryCode'], $confirmCode,$smsCall );

                        $responseArr['type'] = "1";
                        $responseArr['id'] = $userId;
                        $resellerName = $this->getUserId($numberArr['resellerId'] , 1 );
                        
                        $userName = $this->getUserId($numberArr['userId'] , 1 ); //getUserId .. 
                        
                        
                        $responseArr['contact'][] =  array( 'number' => $numberArr['countryCode']."-".$numberArr['verifiedNumber'] , 'resellerId' => $numberArr['resellerId'] , 'userId' =>$numberArr['userId'] , 'resellerName' => $resellerName , "userName" => $userName );
                        $responseArr['status'] = 'success';
                        $responseArr['msg'] = 'verification code successfully send.';
                        return json_encode($responseArr);
                    }
                    else 
                    {
                        $showMsg = "Error process request please contact provider ";
                        $status = "error"; 
                    }
                    
                }

            }
            else
            {
                
                 
                while($numberArr = $resultVerSel->fetch_array(MYSQLI_ASSOC))
                {
                        $resellerName = $this->getUserId($numberArr['resellerId'] , 1);

                         $userName = $this->getUserId($numberArr[$userIdField] , 1); 
                         
                        if($smsCall == 'MAIL')
                        {
                            $fieldValue = $numberArr['email'];
                            
                }
                        else
                        {
                            $fieldValue = $numberArr['countryCode']."-".$numberArr['verifiedNumber'];
                        }
                         
                         
                        //echo $this->querry.'  ..  <br/>';
                        $verifiedNumberResArr[] = array( 'number' => $fieldValue , 'resellerId' => $numberArr['resellerId'] , 'userId' =>$numberArr[$userIdField] , 'resellerName' => $resellerName , "userName" =>  $userName );
                    }

                $responseArr['type'] = "2";
                   // $responseArr['id'] = $numberArr['userId'];
                $responseArr['contact'] = $verifiedNumberResArr;
                $responseArr['status'] = 'success';
                    $responseArr['msg'] = 'This Number exist in many resellers. Please choose one reseller. ';
                return json_encode($responseArr);
            }
            
        }
        else
        {
            $showMsg = "No Records Found. May be this user not registered with us.";
            $status = "error";
        }
                    return json_encode(array("msg"=>$showMsg,"status"=>$status,"type"=>"0"));
    }
    
    
    /*
     * @author nidhi<nidhi@walkover.in>
     * 
     * Steps::
     * 1. Insert Details to 91_resendCode table
     * 2. Select Recods of today from 91_resendCode table
     * 3. If records are less then five then send verification code to That number or Email .
     * 
     */
    function resendVerfication($param)
    {
        if(!is_numeric( $param['smsCall'] ))
            return json_encode(array('status' => 'error' , 'message' => "Please enter Proper value for sms or call. 1 - sms , 2 - call , 3 - email" ) );

        if(!is_numeric( $param['userId'] ))
            return json_encode(array('status' => 'error' , 'message' => "Invalid UserId" ) );
        
        $table = "91_resendCode";
        $result = $this->selectData('userid', $table, " date between  '".date('Y-m-d 00:00:00')."' and  '".date('Y-m-d 23:59:59')."'  and userid =" . $param['userId'] );
         
        if( $result->num_rows > 5 )
        {
             return json_encode(array('status' => 'error' , 'message' => "Your  IP has exceeded the trial limit. Please try after 24 hours or contact support. We would be glad to assist you." ) );
        }
        
        if($param['smsCall'] == '3')
            $type = 2; 
        else
            $type = 1; 
       
        $data = array( "userid" => $param['userId']  , "resend_by" => $param['smsCall'] , "type" => $type , "user_ip"  => $this->getUserIP() );

        $response = $this->insertData($data, $table);
        
        if ($response)
        {
           if($param['smsCall'] == '3')
           {
                $resultVerSel = $this->selectData('confirm_code,email', '91_verifiedEmails',"userId='" . $param['userId'] . "'");
                
                if( $resultVerSel->num_rows > 0 )
                {
                    $getUserInfo = $resultVerSel->fetch_array(MYSQLI_ASSOC);
                    
                    $this->sendSmsCall( $getUserInfo['email'], '', $getUserInfo['confirm_code'],'MAIL' );
                    return json_encode(array('status' => 'success' , 'message' => "verification code sent successfully" ) );
                }
                else 
                {
                    return json_encode(array('status' => 'error' , 'message' => "Unable to fetch user details. Please try after some time" ) );
                }
                
           }
           else 
           {
               if(isset($param['type'] ) && $param['type'] == 'signUp' )
               {
                   $fieldName = 'tempNumber';
                   $tableName = '91_tempNumbers';
               }
               else
               {
                   $fieldName = 'verifiedNumber';
                   $tableName = '91_verifiedNumbers';
               }
                $resultVerSel = $this->selectData('confirmCode,'.$fieldName.',countryCode', $tableName ,"userId='" . $param['userId'] . "'");
              // echo $this->querry;
                if( $resultVerSel->num_rows > 0 )
                {
                    $getUserInfo = $resultVerSel->fetch_array(MYSQLI_ASSOC);
                    //var_dump($smsCall);
                    
                    if($param['smsCall']  == '1')
                        $smsCall = 'SMS';
                    else
                        $smsCall = 'CALL';
                    
                    $this->sendSmsCall( $getUserInfo[$fieldName],$getUserInfo['countryCode'] , $getUserInfo['confirmCode'] ,$smsCall );
                    return json_encode(array('status' => 'success' , 'message' => "verification code sent successfully" ) );
                }
                else 
                {
                     return json_encode(array('status' => 'error' , 'message' => "Unable to fetch user details. Please try after some time" ) );
                }
           }    
        }
        else 
        {
            return json_encode(array('status' => 'error' , 'message' => "An error Occoured  Please try after some time." ) );
        }
    }
    
    
    
    /**
     * last updated by Balachandra<balachandra@hostnsoft.com> on 29/07/2013
     * last modified by Ankit patidar <ankitpatidar@hostnsoft.com> on 21/8/2014
     * @abstract called from action layer while forget password
     * @uses function to change password
     * @param string $currPwd
     * @param string $newPwd
     * @param int $userId
     * @param int $type
     * @return json 
     */
    function changePassword($currPwd, $newPwd,$userId,$type = 0) 
    {

        if(preg_match('/[^a-zA-Z0-9\@\$\}\{\.]+/', $newPwd) || $newPwd == "")
        {
            return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Valid New Password'));
        }
        
        if(preg_match('/[^0-9]+/', $userId) || $userId == "")
        {
            return json_encode(array('msgtype' => 'error', 'msg' => 'Ivalid User Please Login'));
        }

        #$table name of the table in database
        $table = '91_userLogin';
        
        #access password by database of the current user
            $result = $this->selectData('userName,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,sipFlag',$table,"userId = '" . $userId . "'");
            
            if(!$result)
                return json_encode (array("msg"=>"Error can not find details of user","status"=>"error"));
            
            #fetching the array element and putting in a varible $pwd
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
           
            $userName = $row['userName'];
            $password = $row['password'];
            $sipFlag = $row['sipFlag'];
            
        
        if($type != 1)
        {
            if(preg_match('/[^a-zA-Z0-9\@\$\}\{\.]+/', $currPwd) || $currPwd == "")
            {
                return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Vlaid Current Password'));
            }
            
            #store the particular column data
            if ($password != $currPwd) 
            {
                #echo "Please enter correct password";
                return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Correct Password'));
            }
            
        }
        else
        {
            
        }        
        #check curr_pwd is equal to database user password        
        $newPwd = $this->db->real_escape_string($newPwd);

        #data to pass in update command that is new password
        $data = array("password" => $newPwd);
        
        //$result1 = $this->updateData($data,$table,"userId = '" . $userId . "' ");
      $result1 = $this->updateWithEncryption($table,$data,"userId = '" . $userId . "' ");
        
//     echo $this->querry;
//     die('jfkdjfkj');
        #if query executed then
        if ($result1) {
            
            
            if($sipFlag)
            {
                $dataSip = array("passwd" => $newPwd);
                
                $resultSip = $this->updateWithEncryption("91_verifiedSipId",$dataSip,"userId = '" . $userId . "' ");
               
                if($resultSip)
                {
//                    ob_start();
//                    $res = sip_delete($userName);
//                    $res2 = sip_add($userName,$new_pwd);
//                    ob_end_clean();
                    $msg = $this->enableSip($userId,1);
                }
            }
            
//            $msg = $this->enableSip($userId,1);
            $resultData = json_decode($msg, TRUE);
            
            if($resultData['status'] != "success")
            {
                trigger_error('sign Up user sip not enable');
            }
            #echo "password changed successfully"
            return json_encode(array('msgtype' => 'success', 'msg' => 'Password Changed Successfully'));
        }
        #if query is not successfull    
        else {
            #weak password please chose another one.
            return json_encode(array('msgtype' => 'error', 'msg' => 'Error Updating password'));                
        }
        
//        mysqli_close($con);
    }


    #check email id is velid or not 
    function isValidEmail($email) {
        return preg_match(EMAIL_REGX, $email);
    }

    
    
    #function use to send code for verify email id 
    #function use Mandrill
    function send_verification_mail($newEmailid, $pwd,$balance = NULL,$currencyId = NULL , $type = NULL ) 
    {
        
        
        if($type == 1)
        {
            $mailData = <<<EOF
            <html xmlns="http://www.w3.org/1999/xhtml" style="background:#fff">
            <body style="background:#fff; padding:0; margin:0; font:12px Verdana, Geneva, sans-serif; font-size:14px; color:#999; line-height:22px">
            <!--Main wrapper-->
            <div style="width:625px; margin:0 auto;  background:#fff;">
            <!--Header-->
            <div style="height:5px;background-color:#FFCD53;"><span style="height:5px;background-color:#296FA2; width:100px; display:block"></span></div>
            <!--Mid content-->
            <div>
            <h1 style="color:#296FA2; font-weight:normal; text-align:left">Hey,</h1>
            <div style="padding:20px 0;">We are sorry you are having trouble with your password. Your Verification code is: $pwd.
            Enter the given code to reset your password. </div>
            </div>
                    
            <div style=" font-size:15px;">
            Happy sharing!<br/>
                Team Phone91
            </div>
            </div>
            <!--//Main wrapper-->
            </body>
            </html>
EOF;
        }
        else 
        {
        $currName = $this->getCurrencyViaApc($currencyId,1);
        $password = base64_encode($pwd);
        
        $server = explode('.', HOST_NAME);
        $hostName = $server['0'];
         $protocol = $this->getProtocol();
        $supportmail = '';
        $domainName = HOST_NAME ;
        if($hostName == 'phone91'){
            $supportmail = "For anything else, we are just a mail away at:  <a href='#' style='color:#296FA2;  text-decoration:none'>support@phone91.com</a>.";
            $domainName = "voice.phone91.com";
        }
        ////code to create mail content   
        $mailData = <<<EOF
            <html xmlns="http://www.w3.org/1999/xhtml" style="background:#fff">
            <body style="background:#fff; padding:0; margin:0; font:12px Verdana, Geneva, sans-serif; font-size:14px; color:#999; line-height:22px">
            <!--Main wrapper-->
            <div style="width:625px; margin:0 auto;  background:#fff;">
                    <!--Header-->
                    <div style="height:5px;background-color:#FFCD53;"><span style="height:5px;background-color:#296FA2; width:100px; display:block"></span></div>
                <!--Mid content-->
                    <div>
                        <h1 style="color:#296FA2; font-weight:normal; text-align:left">Howdy!</h1>
                        <div style="padding:15px 15px 15px 0;">
                                     Thank you for joining the $hostName family. Your account has been recharged with an initial amount of Rs. $balance so that you can start calling right away. 
                         </div>
                    </div>
                <div align="center" style="background-color: #FBFBFB; border: 1px solid #F8F8F8;  color: #666666; font-size: 15px; line-height: 29px; margin: 20px 0; padding: 10px;">
                                To get started, please click the below link to validate your E-mail address:<br/>	
                            <a href="https://$domainName/verify_email.php?email=$newEmailid&confirmationCode=$password" style=" color:#296FA2; text-decoration:none">Confirm</a><br/>	
                             <div style="font-size:20px; padding:10px 0">OR</div>	
                            You can alternatively verify it by entering the confirmation code: $pwd in your account settings option. 	
                </div>
                    <div>
                    Stay connected with us on <a href="#" style="color:#296FA2; text-decoration:none">FB</a>,  <a href="#" style="color:#296FA2;  text-decoration:none">Twitter</a> and on our  <a href="#" style="color:#296FA2;  text-decoration:none">blog</a> to always be notified about the interesting offers we introduce from time to time.
                    $supportmail
                    
                </div>
                    <div style="margin:20px 0"><span style="color:#666">Thanks once again</span>, we hope to help you always to stay closer to the ones you love!</div>
                    <div style=" font-size:15px">
                    Stay closer <br/>
                            Team $hostName
                </div>
            </div>
            <!--//Main wrapper-->
            </body>
            </html>
EOF;
}
        //set api key and parameters
        require('Mandrill.php');
        Mandrill::setApiKey(MANDRILLKEY);
        $request_json["type"] = "messages";
        $request_json["call"] = "send";
        $req["html"] = $mailData;
        $req["subject"] = "verify user email";
        $req["from_email"] = "support@phone91.com";
        $req["from_name"] = "Phone91";
        $resTo["email"] = $newEmailid;
        $req["to"][] = $resTo;
        $req["track_opens"] = "true";
        $req["track_clicks"] = "true";
        $req["auto_text"] = "true";
        $req["url_strip_qs"] = "true";
        $request_json["message"] = $req;
        $final = json_encode($request_json);
        $ret = Mandrill::call((array) json_decode($final));

        $arr = get_object_vars($ret[0]);
        if ($arr['status'] == 'sent')
            return true;
        else
            return false;
    }

    
    
    function get_country_frm_num($number) {
        for ($z = 5; $z > 0; $z--) {
            $flag = 0;
            $countrycode = substr($number, 0, $z);
            $dbh = $this->connect();
            $search_qry = "select prefix,description,voice_rate from tariffs where  prefix like '" . $countrycode . "' and id_tariff='8' limit 1 ";
            //	$search_qry="select login from clientsshared where login like '".$q."%'";
            $exe_qry = mysql_query($search_qry) or die(mysql_error());
            if (mysql_num_rows($exe_qry) > 0) {
                $ct = 0;
                while ($res = mysql_fetch_assoc($exe_qry)) {
                    return $res['voice_rate'];
                    $flag = 1;
                    break;
                }
            }
            mysql_close($dbh);
        }
        if ($flag == 0)
            return "Country Not Matched";
    }

    function rechargeEmailTemplate($balance)
    {
        
        $server = explode('.', HOST_NAME);
        $hostName = $server['0'];
        
         $supportmail = '';
        if($hostName == 'phone91')
            $supportmail = "You must report such incidents to our customer care team at support@phone91.com.";
        
        $mailData = <<<EOF
        <html xmlns="http://www.w3.org/1999/xhtml" style="background:#fff">
        <body style="background:#fff; padding:0; margin:0; font:12px Verdana, Geneva, sans-serif; font-size:14px; color:#999; line-height:22px">
        <!--Main wrapper-->
        <div style="width:625px; margin:0 auto;  background:#fff;">
         <!--Header-->
         <div style="height:5px;background-color:#FFCD53;"><span style="height:5px;background-color:#296FA2; width:100px; display:block"></span></div>
        <!--Mid content-->
         <div>
             <h1 style="color:#296FA2; font-weight:normal; text-align:left">Yay</h1>
             <div style="padding:15px 15px 15px 0;">
                          Your $hostName account has been successfully recharged with an amount of Rs. $balance. You'll find a detailed description of it in the 'Transactions' option of your account. <a href="#" style="color:#296FA2; text-decoration:none">Share it on Facebook</a>, and we will immediately add 10% of your recharge amount in your account. </div>
         </div>

        <div>
         Find out what's new on Phone91 at <a href="#"  style="color:#296FA2;  text-decoration:none">Facebook</a>, <a href="#"  style="color:#296FA2;  text-decoration:none">Twitter</a> and our blog - <a href="#"  style="color:#296FA2;  text-decoration:none">Phone 91</a>. You never know when you find something interesting. May be a running offer. It is important to stay charged, always! </div>

         <div>Had any Problem with your order? Contact our <a href="#"  style="color:#296FA2;  text-decoration:none">Customer Care</a>. </div>

         <div  style="background-color: #FBFBFB; border: 1px solid #F8F8F8;  color: #666666; 	margin: 20px 0; padding: 10px;">
                 <span><strong>You need to know:</strong></span>
                      <ul style="margin: 0px; padding: 10px 0px 0px 17px;">
                             <li style=" margin: 0;  padding: 0 0 15px;"> At $hostName, your personal information is kept safe. Our online recharge is processed through the strong security channels we have set.</li>
                             <li  style=" margin: 0;  padding: 0 0 15px;">Please be beware of communication by anyone on behalf of $hostName seeking information like your bank account number, credit/debit card details, passwords, PIN numbers or any personal information because we NEVER take such information over Phone calls.</li>
                             <li  style=" margin: 0;  padding: 0 0 15px;">$supportmail , if you face any.</li>
                     </ul>
             </div>

         <div style=" font-size:15px">
         Stay charged! <br/>
                 Team $hostName
        </div>
        </div>
        <!--//Main wrapper-->
        </body>
        </html>
EOF;

    }
    
    
    
    /**
     * @author John Doe <john.doe@example.com>
     * @uses function to get user feedback
     * @param type $sub
     * @param type $dis
     * @return string
     */
    function user_feedback($sub, $dis) 
    {
        $email = 'rahul@hostnsoft.com';
        $subject = $sub . " Feedback Phone91.com ";
        $message = "A <b>user " . $_SESSION['username'] . "</b> Send Feedback to admin of chapter91.com<br /><br /> Detail feedback of user is as follows:<br /><br />" . $dis;
        
        
        if (strlen($_SESSION['contact_no']) > 8) 
        {//if user mobile number is confirm with chapter91
            $message .="<br /> and user mobile number is " . $_SESSION['contact_no'];
        }
        
        
        $header = 'MIME-Version: 1.0' . "\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
        
        
        if (strlen($_SESSION['email']) > 8) 
        { //if user have his or her email then mail send by user email
            $header .= 'From: ' . $_SESSION['email'] . "\n";
        } 
        else 
        {
            $header .= 'To: Feedback@Phone91.com <autoreply@phone91.com>' . "\n";
        }
        mail($email, $subject, $message, $header);
        return "Your Feedback is submited Successfully thankyou for your precious time.";
    }

    /**
     * 
     * @param type $mobile_no
     * @param type $vcode
     */
    function mobile_verification_api($mobile_no, $vcode) {
        $connect_url = CALLSERVERURL.'phone91_verification/mobile_verify_api.php'; // Do not change
        $param["mobile_no"] = $mobile_no; //
        $param["vcode"] = $vcode; //
        $request = "";
        
        foreach ($param as $key => $val) 
        {
            $request.= $key . "=" . urlencode($val);
            $request.= "&";
        }
        
        $request = substr($request, 0, -1);
        $url2 = $connect_url . "?" . $request;
        $ch = curl_init($url2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 ); 
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );

        $json = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * 
     * @param int $uid
     */
    function mobile_verification_response($uid) 
    {
        $connect_url = CALLSERVERURL.'phone91_verification/mobile_verify_response.php'; // Do not change
        $param["uid"] = $uid; 
        
        foreach ($param as $key => $val) 
        {
            $request.= $key . "=" . urlencode($val);
            $request.= "&";
        }
        $request = substr($request, 0, -1);
        $url2 = $connect_url . "?" . $request;
        $ch = curl_init($url2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        curl_close($ch);
    }


   

    /**
     * 
     * @param string $userName
     * @return int
     */
    function checkLoginFailed($userName) 
    {
        $table = '91_loginFailed';
        $result = $this->selectData('username',$table,"username='" . $userName . "' and date > DATE_SUB(now(), INTERVAL 4 MINUTE) ");
        
        // processing the query result
        if ($result->num_rows > 0) 
        {
            return $result->num_rows;
        }
        else
            return 0;
    }

    /**
     * 
     * @param string $userName
     */
    function loginFailed($userName) 
    {
        $data = array("username" => $userName, "ip" => $this->getUserIP());
        $table = '91_loginFailed';
        $insertresult =  $this->insertData($data,$table);
    }

    /**
     * @uses function to get random number 
     * @param int $length
     * @return string
     */
    function randomNumber($length,$type = null) 
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';        
       $size = strlen( $chars );
       $str = '';

       for( $i = 0; $i < $length; $i++ ) 
        {
                // getting random charectors from string $chars.
                $str.= $chars[ rand( 0, $size - 1 ) ];   
         }

        // dechex - Decimal to hexadecimal conversion.
         if(is_null($type))
        $str.= dechex(time());
        return $str;
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 5/3/2014
     * @uses function to get random orderId
     * @param int $length
     * @filesource
     * @return string
     */
    function randomOrderId($length) 
    {
       $new = false;
       //this loop continue till the new string not found
        while($new == false)
        {

            $randNo = $this->randomNumber($length);
            //$userName = $batchId.$randNo;		
            $table = '91_confirmOrder';
            $result = $this->selectData('orderId',$table,"orderId = '" . $randNo . "' ");

            // processing the query result
            if ($result->num_rows > 0) 
            {
                $new=false;		    
            }
            else
                $new=true;
        }
        return $randNo;	
    }
    

    
    /**
     * @uses function to update balance
     * @param type $con
     * @param type $clientId
     * @param type $talktime
     * @param string $type
     * @return string
     */
    function updateBalance($con, $clientId, $talktime, $type) {
        $error = "success";
        if ($type = 'Add')
            $sqlC = "UPDATE 91_userBalance SET balance=balance+$talktime WHERE userId='" . $clientId . "'";
        else
            $sqlC = "UPDATE clientsshared SET balance=balance-$talktime WHERE userId='" . $clientId . "'";
        mysqli_query($con, $sqlC) or $error = 'error::' . mysqli_error();
        return $error;
    }

//end of function updatebalance

    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoftcom> on 15/7/2013
     * modified by sameer rathod 18-10-2013
     * @param int $teriffId 
     * @uses functoin to get recharge and talktime to show 
     */
    function getRechargeTalktime($terrifId) 
    {
        $result = $this->selectData('recharge,talktime', 'rechargeByTerriff','terriffId='.$terrifId);
        return $result;
    }

//end of function getRechargeTalktime

    
    /**
     * @uses function to get checksum
     * @param int $MerchantId
     * @param int $Amount
     * @param string $OrderId
     * @param string $URL
     * @param string $WorkingKey
     * @return string
     */
    function getchecksum($MerchantId, $Amount, $OrderId, $URL, $WorkingKey) 
    {
        $str = "$MerchantId|$OrderId|$Amount|$URL|$WorkingKey";
        $adler = 1;
        $adler = $this->adler32($adler, $str);
        return $adler;
    }

    
    /**
     * @uses function to verify checksum
     * @param int $MerchantId
     * @param string $OrderId
     * @param int $Amount
     * @param string $AuthDesc
     * @param string $CheckSum
     * @param string $WorkingKey
     * @return boolean
     */
    function verifychecksum($MerchantId, $OrderId, $Amount, $AuthDesc, $CheckSum, $WorkingKey) 
    {
        $str = "$MerchantId|$OrderId|$Amount|$AuthDesc|$WorkingKey";
        $adler = 1;
        $adler = $this->adler32($adler, $str);

        if ($adler == $CheckSum)
            return "true";
        else
            return "false";
    }

    /**
     * 
     * @param type $adler
     * @param type $str
     * @return type
     */
    function adler32($adler, $str) 
    {
        $BASE = 65521;

        $s1 = $adler & 0xffff;
        $s2 = ($adler >> 16) & 0xffff;
        for ($i = 0; $i < strlen($str); $i++) 
        {
            $s1 = ($s1 + ord($str[$i])) % $BASE;
            $s2 = ($s2 + $s1) % $BASE;
            //echo "s1 : $s1 <BR> s2 : $s2 <BR>";
        }
        return $this->leftshift($s2, 16) + $s1;
    }

    /**
     * 
     * @param type $str
     * @param type $num
     * @return type
     */
    function leftshift($str, $num) 
    {

        $str = decbin($str);

        for ($i = 0; $i < (64 - strlen($str)); $i++)
            $str = "0" . $str;

        for ($i = 0; $i < $num; $i++) {
            $str = $str . "0";
            $str = substr($str, 1);
            //echo "str : $str <BR>";
        }
        return $this->cdec($str);
    }

    /**
     * 
     * @param type $num
     * @return type
     */
    function cdec($num) 
    {
        $dec = '';
        for ($n = 0; $n < strlen($num); $n++) 
        {
            $temp = $num[$n];
            $dec = $dec + $temp * pow(2, strlen($num) - $n - 1);
        }

        return $dec;
    }

     /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 26/03/2014
     * @param int $type
     * @uses function to get country code and country name
     * @return Array
      * last update by ankit patidar on 24/9/2014 code for type 2 added
     */
    function countryCodes($type=NULL)
    {
        include_once('csvtoarray/csv.inc.php');
        $csv = new csv_uploder('csvtoarray/iso.csv', 2000 , ',');
        $array=$csv->getCsv();

        foreach($array as $value)
        {      
            if($type == 1)
            {
                $flagId = explode('/', $value["ISO"]);
                $country[$value["Country"]] = $flagId[0];
            }
	    else if($type == 2)
	    {
		$flagId = explode('/', $value["ISO"]);
                $country[trim($value["CountryCode"])] = $flagId[0];

	    }
            else
               $country[trim($value["CountryCode"])] = $value["Country"];
                
        }
       
      asort($country);
       return $country;
        
        
    } //end of function countryCodes
    
    
#find country name 

    
    /**
     * last modified by Ankit Patidar <ankitpatidar@hostnsoft.com> on 24 /9 /2014 type 2 code added
     * @param type $type
     * @return type
     */
    function countryArray($type=NULL) 
    {
//        $url = CALLSERVERURL."isoData.php";
        $url = "http://voice.phone91.com/isoData.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $string1 = json_decode($data, true);
        
        $count = count($string1);
        
        for ($i = 0; $i < $count; $i++) 
        {
            if($type == 1)
            {
                $flagId = explode('/', $string1[$i]['ISO']);
                $country[$string1[$i]['Country']] = $flagId[0];
            }
	    else if($type == 2)
	    {
		$flagId = explode('/', $string1[$i]["ISO"]);
                $country[trim($string1[$i]["CountryCode"])] = $flagId[0];

	    }
            else
                $country[$string1[$i]['CountryCode']] = $string1[$i]['Country'];
        }
        asort($country);
        return $country;
    }
    
   
    function countryArrayNew()
    {
        $country = array();
        $url = CALLSERVERURL."isoData.php";   
        $ch = curl_init();    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

    $string1 = json_decode($data, true);
    for($i=0;$i<count($string1);$i++){
        $country[$string1[$i]['CountryCode']]=$string1[$i]['Country'];

    } 
    return $country;
    }
    
    # get all detail iso data , country code and country name 
    function countryAllDetail() 
    {

        include_once('csvtoarray/csv.inc.php');
        $csv = new csv_uploder('csvtoarray/iso.csv', 2000 , ',');
        $array=$csv->getCsv();

        foreach($array as $value)
        {
          //var_dump($value);
          $data["Country"]=$value["Country"];
          $data["CountryCode"]=str_replace(" ","",trim($value["CountryCode"]));
          $data["ISO"]=$value["ISO"];
          $response[]=$data;
        }

        return $response;

    }
    
    function currencyArray() 
    {
        //Only For net core 
        if ($row = apc_fetch('currencyArray')) 
        {
            return $row;
        } 
        else 
        {

            $result = $this->selectData('currencyId,currency','91_currencyDesc');

            if ($result->num_rows > 0) 
            {

                while ($rowData = $result->fetch_array(MYSQLI_ASSOC)) 
                {	
                    $currencyArr[] = $rowData;
                }
            }
            
            if (!$result)
                return ("Unable To Fetch User Data");

            apc_store('currencyArray', $currencyArr);
            return $currencyArr;
        }
    }

    #function created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 
    #

    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 09/08/2013
     * @uses function use for check user has confirm mobile no or not if yes then go to userhome page otherwise confirm mobile no page 
     * @param type $userId
     * @return bigInt
     */
    function getConfirmNumber($userId) 
    {

        //Code To redirect user to phone setting page if user do not have any confirmed mobile number
        include_once(CLASS_DIR . 'contact_class.php');

        #get all contact detail 
        $contactObj = new contact_class();

        #find verified contact number
        $vContactArr = $contactObj->getConfirmMobile($userId);
        
        unset($contactObj);
        
        return $vContactArr[0];
    }
    
     /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 09/08/2013
     * @uses function use for check user has confirm mobile no or not if yes then go to userhome page otherwise confirm mobile no page 
     * @param type $userId
     * @return bigInt
     */
    function getUnConfirmNumber($userId) 
    {

        //Code To redirect user to phone setting page if user do not have any confirmed mobile number
        include_once(CLASS_DIR . 'contact_class.php');

        #get all contact detail 
        $contactObj = new contact_class();

        #find verified contact number
        $vContactArr = $contactObj->getUnconfirmMobile($userId);
        
        unset($contactObj);
        
        return $vContactArr[0];
    }
    
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 08/08/2013
     * @uses function use for get last chainId
     * @param type $reseller_id
     * @return string
     */
     function getlastChainId($reseller_id)
     {
        #insert login detail into login table database 
        $loginTable = '91_userBalance';

        #get chain id for user 
        $this->db->select('*')->from($loginTable)->where("resellerId = '" .$reseller_id. "'")->orderBy('userId DESC')->limit(1);
        $query = $this->db->getQuery();
        $result = $this->db->execute();
        
        //log errors
        if(!$result)
            trigger_error ('Problem while get user balance,Query:'.$query);
                    
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $chainId = $row['chainId'];
        }
        else
        {
            $resellerChainId = $this->getUserChainId($reseller_id);
            
            if(!$resellerChainId || $resellerChainId == "" || is_null($resellerChainId))
                return 0;           
            
            $chainId = $resellerChainId."1111";
        }

        return $chainId;
     }   
     
     
     /**
      * @author Rahul sir <rahul@hostnsoft.com>
      * @since 08-08-2013
      * @param type $a
      * @return string
      */
     function generateId($a)
     {
    
        $firstTwo=substr($a,0,2);
        $firstThree=substr($a,0,3);
       //echo " ";
         $first=substr($a,0,1);
       //echo " ";
         $second=substr($a,1,1);
       //echo " ";
         $third=substr($a,2,1);
       //echo " ";
         $last=substr($a,3,1);
       //echo " ";


      if($last=="9")
      {
           $last="a";
           $wxyz=$first.$second.$third.$last;
           return $wxyz;
      }

      if($last=="z")
       {
             if($third=="9")
             {
                 $third="a";
                 $last="1";
                 $wxyz=$first.$second.$third.$last;
                 return $wxyz;
             }
             if($third=="z")
             {
                 if($second=="9")
                 {
                     $second="a";
                     $last="1";
                     $third="1";
                     $wxyz=$first.$second.$third.$last;
                     return $wxyz;
                 }
                 if($second=="z")
                 {
                    if($first=="9")
                     {
                         $first="a";
                         $last="1";
                         $third="1";
                         $second="1";
                         $wxyz=$first.$second.$third.$last;
                         return $wxyz;
                     } 

                     ++$first;
                     $second="1";
                     $third="1";
                     $last="1";
                     $wxyz=$first.$second.$third.$last;
                     return $wxyz;
                 }

                 ++$second;
                 $third="1";
                 $last="1";
                 $wxyz=$first.$second.$third.$last;
                 return $wxyz;
             }
             ++$third;
             $last="1";
             $wxyz=$first.$second.$third.$last;
             return $wxyz;
        }
   
    ++$last;
      $wxyz=$first.$second.$third.$last;
     return $wxyz;
}
     
    
    #created by 
    #creation date 09/08/2013
    #
/**
 * @uses function use for create new chain id by use of generateId function 
 * @author sudhir pandey <sudhir@hostnsoft.com>
 * @param string $lastChainId
 * @return string
 */
    function newChainId($lastChainId)
    {
         
         #last chain id first part 
         $firstpart = substr($lastChainId,0,-4);
         #last chain id second part (currentuser chain id).
         $secondpart = substr($lastChainId,-4);
         
         #increment last chain id by generateId function 
         $incId = $this->generateId($secondpart);
         
         if($incId =='' || $incId == $secondpart)
         {
            $this->sendErrorMail("rahul@hostnsoft.com", "Chain Id creation problem (either chain id is blank or same as last chain id).");
         }
         
         #new chain id
         $newChainId = $firstpart.$incId;
         return $newChainId;
         
    }
    
    /**
     * 
     * @param string $email
     * @param type $mailData
     */
    function sendErrorMail($email, $mailData,$from = "support@phone91.com",$subject = "Phone91 Error Report") 
    {
       //set parameters to send mail
        $to = $email;
        $message = $mailData;
        
        //include mail file
        include_once 'sendmail.php';
        
        $mail = new MailAndErrorHandler();
        
        //call function to send mail
        if(!$mail->sendmail_mandrill(array($to), $subject, $message, $from))
                trigger_error('problem while send mail ,backtrace:'. json_encode(debug_backtrace()));
        
        //free object space
        unset($mail);
    }
    
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 27/08/2013
     * @uses function use for get reseller chain id 
     * @param int $userId
     * @return boolean
     */
    function getResellerChainId($userId)
    {
         
      $loginTable = '91_userBalance';
      
      #get chain id for user 
      $result = $this->selectData('*',$loginTable,"userId = '" .$userId. "'");
      
      if($result)
      {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $chainId = $row['chainId'];

        #reseller chain id first part 
        $resellerChainId = substr($chainId,0,-4);

        return $resellerChainId;
      }
      else
          return false;
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 
    #
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param int $userId
     * @since 27/08/2013
     * @uses function use for get user chain id  
     * @return boolean
     */
    function getUserChainId($userId)
    {
         
      $loginTable = '91_userBalance';
      
      if(preg_match(NOTNUM_REGX,$userId) || $userId == "" || is_null($userId))
              return 0;
      
      #get chain id for user 
      $result = $this->selectData('chainId',$loginTable,"userId = '" .$userId. "'");
      
      if($result)
      {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $chainId = $row['chainId'];
        return $chainId;
      }
      else
          return false;
         
    }
    
    /**
     * @author  sameer rathod <sameer@hostnsoft.com>
     * @since 25-10-2013
     * @uses function use for getting user chain id  by user name from manageClient
     * @param string $userName
     * @return boolean
     */
    function getUserChainIdViaName($userName)
    {
      
      if(preg_match('/[^0-9a-zA-Z]+/', $userName))
              return json_encode (array("msg"=>"Error Invalid user name","status"=>"error"));
    
      $table = '91_manageClient';
      $columns = "chainId";
      $result = $this->selectData($columns, $table,"userName= '".$userName."'");
      
      if($result)
      {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $chainId = $row['chainId'];
        return $chainId;
      }
      else
          return false;
         
    }
    
  
   
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 27/08/2013
     * @uses function use for get reseller id  
     * @param type $userid
     * @return boolean
     */
    function getResellerId($userId)
    {
       if(empty($userId) || !is_numeric($userId))
            return false; 
        
      $loginTable = '91_userBalance';
      
      #get reseller id for user 
      $result = $this->selectData('*',$loginTable,"userId = '" .$userId. "'");
      
      if($result)
      {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $resellerId = $row['resellerId'];
        return $resellerId;
      }
      else
          return false;
         
    }
    
    /**
     * @author 
     * @param string $url
     * @param int $userId
     * @return boolean
     */
    function insertLandingPage($url,$userId)
    {
        $table = "91_userLandingPage";
        $url = base64_encode($url);
        
        
        if(preg_match(NOTNUM_REGX, $userId) || empty($userId))
                return false;
        $userId = $this->db->real_escape_string($userId);
        $res = $this->db->query("INSERT INTO ".$table." (userId,url) values ('".$userId."','".$url."') ON DUPLICATE KEY UPDATE url = '".$url."'");
        
        
        if($res)
            return true;
        else
        {
            trigger_error("Problem while insert data in '.$table.' query: INSERT INTO ".$table." (userId,url) values ('".$userId."','".$url."') ON DUPLICATE KEY UPDATE url = '".$url."'");
            return false;
        }
    }
    
    
    /**
     * 
     * @param int $userId
     * @return boolean
     */
    function getLandingPage($userId)
    {
        $table = "91_userLandingPage";
        $result = $this->selectData("url", $table, "userId=".$userId);
        
        if($result)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            return base64_decode($row['url']);
        }
        else
        {
            return false;
        }
    }
    
    /**
     * 
     * @param int $tariffId
     * @return int
     */
    public function getOutputCurrency($tariffId) 
    {
        
        #check total no of client is valid or not 
        if (!preg_match("/^[0-9]+$/", $tariffId)) {
            return 0;
        }
        
        #GET OUTPUT CURRENCY OF PLAN FOR CURRENCY CONVERSION
        if(is_null($tariffId))
            return 0;
        
        $condition = "tariffId =" . trim($tariffId);
        $curRes = $this->selectData("outputCurrency", '91_plan', $condition);
        
        if ($curRes) 
        {
            $curResRow = $curRes->fetch_array(MYSQLI_ASSOC);
            return $curResRow['outputCurrency'];
        }
        else
            return 0;
    }

    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 23/10/2013
     * @uses function use to make log of account manager (save all log into 91_adminlog)
     * @param int $userId , change status of which particular user   
     * @param int $actionType , use to action Type :
     *      1. Active and Deactive user status   
     *      2. change tariff plan of user 
     *      3. enabel disabel user 
     *      4. change account manager
     *      5. change user to reseller 
     *      6. change call limit 
     * @param string $oldStatus use to get old status of action 
     * @param string $currentStatus use to get current status of action after changes 
     * @param int $actionTakenBy , user id who take action 
     * @param string $description , description of action 
     */ 
    function accountManagerLog($userId,$actionType,$oldStatus,$currentStatus,$actionTakenBy,$description){
    
        #table name for store log file
        $logTable = '91_adminLog';
        
        $oldStatus = $this->db->real_escape_string($oldStatus);
        $currentStatus = $this->db->real_escape_string($currentStatus);
        $description = $this->db->real_escape_string($description);
        
        $data = array("userId" => $userId,"actionType"=>$actionType,"oldStatus"=>$oldStatus,"currentStatus"=>$currentStatus,"date"=>date('Y-m-d H:i:s'),"actionTakenBy"=>$actionTakenBy,"description"=>$description);
        if(isset($_SESSION['acmId']) || $_SESSION['acmId'] != ''){
            $data['changedBy'] = 1;
            $data['accManagerId'] = $_SESSION['acmId'];
        }
        
        #insert query (insert data into 91_personalInfo table )
        $result = $this->insertData($data,$logTable);
        
        #check data inserted or not 
        if (!$result) {
             return json_encode(array("status" => "error", "msg" => "add log detail ! "));
        }
        
        
    }
    
    /**
     * @author sudhir pandey  <sudhir@hostnsoft.com>
     * @since 26/10/2013
     * @uses function to get all Admin detail 
     * 
     */
    function getAllAdmin()
    {
        
      $admin = array();
        
      $loginTable = '91_userLogin';
      
      #get all admin id from  userlogin table  
      $result = $this->selectData('*',$loginTable,"type = 1");
      
      if($result->num_rows > 0)
      {
           while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $admin[$row['userId']] = $row['userName'];
           }
      }
        
      return $admin;
    }
    
    /**
     * @author sudhir pandey  <sudhir@hostnsoft.com>
     * @since 29/10/2013
     * @uses function get Admin id and name
     * 
     */
    function getadminId($userId)
    {
        
      $managerTable = '91_accountManager';
      
      $userId = $this->db->real_escape_string($userId);
      
      #get all admin id from  userlogin table  
      $result = $this->selectData('managerId',$managerTable,"userId = ".$userId." ");
      
      if($result->num_rows > 0)
      {
          $res = $result->fetch_array(MYSQLI_ASSOC);
          $managerId = $res['managerId'];
      }else
          $managerId = 0;
      
      return $managerId;
      
             
    }
    
      /**
     * @author Nidhi <nidhi@walkover.in>
     * @since 18/02/2014
     * @uses function to get account manager admin id 
     * @filesource
     */
    function getAcmName($acmId)
    {
        //validate acm id
        if(!is_numeric($acmId))
            return 0;
        
        $table = '91_accountManagerDetails';
        
        $result = $this->selectData('userName',$table,'acmId='.$acmId);
        
        if(!$result)
        {
            trigger_error('problem while getting admin from table!!');
            return 0;
        }
        
        $res = $result->fetch_array(MYSQLI_ASSOC);
        
        return $res;
    }
    
    
   
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param user id to get ip detail 
     * @return array 
     */
    function getUserSystemDetail($userId)
    {
      if(preg_match('/[^0-9]+/', $userId) || $userId == "")
        {
            $detail = array(); 
            return json_encode($detail);
        }
        
      # table name to get ip and browser name   
      $ipTable = '91_ipDetails';
      
      $userId = $this->db->real_escape_string($userId);
      
      #get ip and browser detail of user   
      $result = $this->selectData('*',$ipTable,"userId = ".$userId." ");
      
      if($result->num_rows > 0)
      {
          while ($res = $result->fetch_array(MYSQLI_ASSOC))
          {
              $detail[] = $res;
          }
      }
      else
          $detail = array();
        
      return json_encode($detail);
    }
    
     /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com> 
     * @since 28/10/2013 
     * @param int $userId
     * @uses function to save user system details like ip ,time ,browser
     * @return array
     */
    function saveUserSystemDetails($userId)
    {
       //get user ip address 
       $userIP  = $this->getUserIP();
       
       //get user browser
       $BrowserArr = $this->getBrowser();
       $userBrowser = $BrowserArr['name'];
       
       //get date time for login
       $time = date('Y-m-d H:i:s');
       
       //prepare data to send
       $data =array('userId' => $userId,
                    'IpAddress' => $userIP,
                    'date' => $time,
                    'browser' => $userBrowser);
       
       //set table
       $table = '91_ipDetails';
       
       //apply insert query
       $result = $this->insertData($data,$table);
       
       if(!$result)//check result
       {
           //prepare response for error 
           $response['msg'] = 'Problem while Insert data!!!';    
           $response['type'] = 0;
       }
       else
       {
           //prepare response for success
           $response['msg'] = 'success';        
           $response['type'] = 1;
       }
       
       return $response;
       
    }//end of function saveUserSystemDetails   
    
    /**
     * added by Ankit patidar <ankitpatidar@hostnsoft.com> on 28/10/2013 
     * @return array with browser info
     */
    function getBrowser() 
    { 
        //get user agent 
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        
        //set default values for variables
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
        } 
        elseif(preg_match('/Firefox/i',$u_agent)) 
        { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
        } 
        elseif(preg_match('/Chrome/i',$u_agent)) 
        { 
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
        } 
        elseif(preg_match('/Safari/i',$u_agent)) 
        { 
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
        } 
        elseif(preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
        } 
        elseif(preg_match('/Netscape/i',$u_agent)) 
        { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
        } 

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    } //end of funtion getBrowser

    
     
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 11-09-2013
     * @uses function use for get username by userid 
     * @param type $userId
     * @return type
     */
    function getuserName($userId)
    {
        
         if(preg_match(NOTNUM_REGX, $userId) || $userId == "")
                return FALSE;
         
        #condition for find username and pin detail 
        $condition = "userId = '" . $userId . "' ";

        #find user name of given id (we can not use session name because userid will change).
        $info = "91_personalInfo";
        $userInfo = $this->selectData('*',$info,$condition);
        $userName='';
        
        if ($userInfo->num_rows > 0) 
        {
            $user = $userInfo->fetch_array(MYSQLI_ASSOC);
            $userName = $user['name'];             
        } 
        return $userName;

    }
    
    #created by 
    #creation date 
    
    /**
     * @author sameer rathod <sameer@hostnsoft.com>
     * @uses function use for get userid by userName
     * @since 26-11-2013
     * @param string $userName 
     */
    function getUserId($userName , $type = '0')
    {
        if($type == '2')
        {
            if(!is_array($userName))
                return json_encode(array("msg"=>"Invalid Input given please enter valid data","status"=>"error"));
            $userName = array_unique($userName);
            $userName = array_values($userName);
            foreach($userName as $val)
            {
                if(preg_match('/[^a-zA-Z0-9\@\.\_]+/', $userName) || empty($userName))
                {
                    return json_encode(array("msg"=>"Invalid user name please enter a valid name","status"=>"error"));
                }
            }
            
            $userName = implode("','", $userName);
            $condition = "userId IN ('" . $userName . "')";
              
        }
        else
        {
            if(preg_match('/[^a-zA-Z0-9\@\.\_]+/', $userName) || empty($userName))
            {
                return json_encode(array("msg"=>"Invalid user name please enter a valid name","status"=>"error"));
            }
            $userName = $this->db->real_escape_string($userName);
            
            if($type == '1')
            {
                  $condition = "userId = '" . $userName . "' ";
                  $findVar = "userName";
            }
            else
            {
            $condition = "userName = '" . $userName . "' ";
                $findVar = "userId";
            }

        }  
        
        
        
        #find userId of given name 
        $manageClient = "91_manageClient";
        $userinfo = $this->selectData('userId,UserName', $manageClient,$condition);
        
        if ($userinfo && $userinfo->num_rows > 0) 
        {
            if($type == '2')
            {
                while($row = $userinfo->fetch_array(MYSQLI_ASSOC))
                {
                    $data[$row['userId']] = $row['userName'];
                }
            }
            else
            {
                $user = $userinfo->fetch_array(MYSQLI_ASSOC);

                $data = $user[$findVar];             
            }
            return $data;
        } 
        return 0;

    }
    
     /**
       * @author Ankit Patidar <ankitpatidar@hostnsoft.com> on 21/10/2013
       * @param stirng $mongoId
       * @param string $startTime
       * @param string $userId
       * @param string $description
       * @param string $functionName
       * @return string return id
       */
      function paymentTracker($mongoId,$startTimeT,$userId = '',$msg = '',$desc=array(),$functionName='')
      {
        //convert start time to mongo date
        $startTime = new MongoDate(strtotime($startTimeT)); 
        //get end time
        $endTime = new MongoDate(strtotime(date('d-m-Y H:i:s')));
        
        //get total time
        $totalTime = strtotime(date('d-m-Y H:i:s'))-strtotime($startTimeT);
        
        //check for difference
        if($totalTime > 0)
            $totalDiffernce = new MongoDate($totalTime);
        else//assign zero to difference
            $totalDiffernce = 0;
        
        //include required file
        include_once ('classes/db_class.php');
        //create mongo connection
        $dbObj = new db_class();
        
        //get collection name
        $collectionName = 'paymentTracker';
        
        //check for mongoId
        if(is_null($mongoId)) 
        {
            //apply exception handling 
            try 
            {
                //get mongo id
                $mongoId = new MongoId();
                
                //prepare info array
                $info = array("startTime" => $startTime, 
                              "endTime" => $endTime,
                              "Time" => $totalDiffernce, 
                              "msg" => $msg,
                              "description" => $desc);
                //prepare array to insert
                $data = array("_id" => $mongoId,
                              "Function" => $functionName,
                              "info" => array($info), 
                              "userid" => $userId);
                
               
                $resultMongo = $dbObj->mongo_insert($collectionName,$data); //insert request 
                
                //log error
                if(!$resultMongo)
                    trigger_error('problem while insert in payment tracker,data:'.json_encode($data));
                //free variable space
                unset($dbObj);
                
               
            } 
            catch (exception $e) 
            {
                //if exception occur then maintain log
                $returnArr = array("error" => $e->getMessage());
                trigger_error('Exception occur in payment tracker'.$returnArr);
               
            }
            return (string)$mongoId;
       }
        else //if mongo id not null
        {
           
            //apply exception handling
            try 
            {
                //where condition
                $where = array("_id" => new MongoId($mongoId));
                //prepare info array
                $info = array("startTime" => $startTime, 
                              "endTime" => $endTime,
                              "Time" => $totalDiffernce, 
                              "msg" => $msg,
                              "description" => $desc);
                
               //push to old info array
                $insertThis = array('$push' => array("info" => $info));
               
               //update document
               $updtReqId = $dbObj->mongo_update($collectionName,$where, $insertThis);
                
               //free variable space
               
                unset($dbObj);
               
              
            }//end of try 
            catch (exception $e) 
            {
                //maintain error log if exception occur
                $returnArr1 = array("error" => $e->getMessage());

            }
            return (string)$mongoId;
          }//end of else
          
          
      }//end of function paymentTracker 

       /**
       * @author Ankit Patidar <ankitpatidar@hostnsoft.com> on 21/10/2013
       * @param stirng $mongoId
       * @param string $startTime
       * @param string $userId
       * @param string $description
       * @param string $functionName
       * @return string return id
       */
      function signUpTracker($mongoId,$startTimeT,$userId = '',$msg = '',$desc=array(),$functionName='')
      {
        //convert start time to mongo date
        $startTime = new MongoDate(strtotime($startTimeT)); 
        //get end time
        $endTime = new MongoDate(strtotime(date('d-m-Y H:i:s')));
        
        //get total time
        $totalTime = strtotime(date('d-m-Y H:i:s'))-strtotime($startTimeT);
        
        //check for difference
        if($totalTime > 0)
            $totalDiffernce = new MongoDate($totalTime);
        else//assign zero to difference
            $totalDiffernce = 0;
        
        //include required file
        include_once ('classes/db_class.php');
        //create mongo connection
        $dbObj = new db_class();
        
        //get collection name
        $collectionName = 'signUpTracker';
        
        //check for mongoId
        if(is_null($mongoId)) 
        {
            //apply exception handling 
            try 
            {
                //get mongo id
                $mongoId = new MongoId();
                
                //prepare info array
                $info = array("startTime" => $startTime, 
                              "endTime" => $endTime,
                              "Time" => $totalDiffernce, 
                              "msg" => $msg,
                              "description" => $desc);
                //prepare array to insert
                $data = array("_id" => $mongoId,
                              "Function" => $functionName,
                              "info" => array($info), 
                              "userid" => $userId);
                
               
                $resultMongo = $dbObj->mongo_insert($collectionName,$data); //insert request 
                
                //log error
                if(!$resultMongo)
                    trigger_error('problem while insert in signUp tracker,data:'.json_encode($data));
                //free variable space
                unset($dbObj);
                
               
            } 
            catch (exception $e) 
            {
                //if exception occur then maintain log
                $returnArr = array("error" => $e->getMessage());
                trigger_error('Exception occur in sign Up tracker'.$returnArr);
               
            }
            return (string)$mongoId;
       }
        else //if mongo id not null
        {
           
            //apply exception handling
            try 
            {
                //where condition
                $where = array("_id" => new MongoId($mongoId));
                //prepare info array
                $info = array("startTime" => $startTime, 
                              "endTime" => $endTime,
                              "Time" => $totalDiffernce, 
                              "msg" => $msg,
                              "description" => $desc);
                
               //push to old info array
                $insertThis = array('$push' => array("info" => $info));
               
               //update document
               $updtReqId = $dbObj->mongo_update($collectionName,$where, $insertThis);
                
               //free variable space
               
                unset($dbObj);
               
              
            }//end of try 
            catch (exception $e) 
            {
                //maintain error log if exception occur
                $returnArr1 = array("error" => $e->getMessage());

            }
            return (string)$mongoId;
          }//end of else
          
          
      }//end of function paymentTracker 
      
      
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com> on 23/10/2013
     * @param string $number
     * @return float number 2 decimal number
     */
     function getNumberWithTwoDecimal($number)
     {
        //explode by dot 
        $arr = explode('.',$number);

        //check for decimal value
        if(isset($arr[1]))
                $dec = substr(end($arr),0,2);
        else//else set default value 
                $dec = 0;

        $num = $arr[0].'.'.$dec;
        
        return $num;
     }//end of function getNumberWithTwoDecimal
    
     /**
        * @author  Rahul <rahul@hostnsoft.com>
        * @package signup Class 
        * @since 07 aug 13  V 1.0
        * @depends md5 max length 32
        * @used in create User Batch
        * @uses function to give random username which is not exist in Database.
        * @
        */
     function createUsername($batchId,$length=8)
     {		
		
		$new = false;
		while($new == false)
		{
                    
                    $randNo = $this->batchNameNumber($length);
                    $userName = $batchId.$randNo;		
                    $table = '91_userLogin';
                    $result = $this->selectData('userName',$table,"userName = '" . $userName . "' ");
                    
                    // processing the query result
                    if ($result->num_rows > 0) 
                    {
                        $new=false;		    
                    }
                    else
                        $new=true;
		}
		return $userName;	
	}
        
  function batchNameNumber($length) 
    {
      //this function is used in api for fbgl sign up and same for web
        #password length
        $password = "";
        $possible = "0123456789";
        $i = 0;
        while ($i < $length) 
        {
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            if (!strstr($password, $char)) 
            {
                $password .= $char;
                $i++;
            }
        }
        
        return $password;
    }
    
      
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 11-11-2013
     * @param int $userId
     * @return array
     */    
    function getTariffIdandName($userId)
    {
        
        #condition for find tariff id and name 
        $condition = "userId = '" . $userId . "' ";
        
        $tariffData = array();
        $tableNsme = "91_plan";
        $result = $this->selectData('*',$tableNsme,$condition);
        
        if ($result->num_rows > 0) 
        {
            while ($res = $result->fetch_array(MYSQLI_ASSOC))
            {
                 $tariffData[$res['tariffId']] = $res['planName'];
            }        
        } 
        
        return $tariffData;
     
    }
    
  
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 13/11/2013
     * @uses function use to check verify contact no or email id if user check phone and email in reseller setting (setting -> phone)
     * @param type $userId
     * @return type Description
     */
    function checkVerifyNoEmail($userId)
    {
        
        #check status of email or phone no verify from 91_resellerSetting
        $table = "91_resellerSetting";
        
        #condition for find tariff id and name 
        $condition = "userId = '" . $userId . "' ";
        
        $result = $this->selectData('*',$table,$condition);
        
        if ($result->num_rows > 0) 
        {
            $res = $result->fetch_array(MYSQLI_ASSOC);
            $email = $res['email'];
            $phone = $res['mobile'];
        }
        else
        {
            $email = 0;
            $phone = 1;
        }
        
        #check if phone is checked then check contact no is verify or not 
        if($phone == 1)
        {
            if ($this->getConfirmNumber($userId) == 0)
            {
                
                 if($_SESSION['client_type'] != 4)
                    echo "<script>window.location.href='#!setting.php|phone.php'</script>";
            }                
        }
        
        
        if($email == 1)
        {
             include_once(CLASS_DIR . 'contact_class.php');
             $contactObj = new contact_class();
             $emailArr = $contactObj->getConfirmEmail($userId);
            
            if (empty($emailArr))
            {
                 if($_SESSION['client_type'] != 4)
                    echo "<script>window.location.href='#!setting.php|email.php'</script>";
            }   
        }
        
     
    }
    
    function getResellerSetting($userid){
        $table = '91_resellerSetting';
        $this->db->select('mobile,email')->from($table)->where("userId=".$userid);
        $this->db->getQuery();
        $result = $this->db->execute();    
        //echo $result->num_rows;
       
        if ($result->num_rows > 0) {
            $row=$result->fetch_array(MYSQL_ASSOC) ;
            $setting = $row;
        }
        else{
            $setting['mobile']=0;
            $setting['email']=0;
        }
            return $setting;
    }
    
    
   /**
    * @author Ankit Patidar <ankitpatidar@hostnsoft.com> 
    * @since 19/11/2013
    * @param string $errorMsg
    * @param string $errFile 
    * @param int $errLine
    * @return  
    */ 
   public static function CallError($errorMsg,$errFile,$errLine)
   {
       //set error file
       $fileName = 'error_reporting/callError_'.gmdate('d_M_Y').'.txt';
       
       //get time and zone
       $date = date('d-M-Y H:i:s');//get Time
       
       $alertMsg = '['.$date.']';
       $alertMsg .= "PHP User generated error :". $errorMsg." in ";
       $alertMsg .= " $errFile";
       $alertMsg .= " on line $errLine %br%";
      
       $alertMsg = str_replace ("%br%", "\r\n", $alertMsg); // write <br> tag to break line the lines with browsers
       
       
       
        //if file not exists then create with permission
        if (!file_exists($fileName))
        {
             $fileHandle = fopen($fileName, "w+");
              fclose($fileHandle);
        }

        //call  function to append error to  erro log file
        file_put_contents($fileName,$alertMsg,FILE_APPEND);
   }
  
   //modified by:Balachandra<balachandra@hostnsoft.com>
   /**
    * @abrstract this function called from action layer while chage password feature
    * last modified by Ankit Patidar <ankitpatidar@hostnsoft.com> on 21/8/2014
    */
    //date: 29/072013
    function change_pwd($curr_pwd, $new_pwd) {
       

        #getting the session userid
        $userid = $_SESSION['userid'];

       if(!preg_match('/^[a-zA-Z0-9\@\_\-\!\$\(\)\?\[\]\{\}\s]+/', $new_pwd))
        {
           return json_encode(array("msgtype" => "error", 
                                    "msg" => "please enter a valid password must not containg any spacial character other than '@_-!$()[]{}?' "));
        }

        if(strlen($new_pwd) > 25)
        {
           return json_encode(array("status" => "error", 
                                    "msg" => "Please enter password less then 25 character"));
        }
        
        #$table name of the table in database
        $table = '91_userLogin';
        
        
        #access password by database of the current user
        $qury = $this->db->select('userName,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,sipFlag')->from($table)->where("userId = '" . $userid . "'");
        $this->db->getQuery();

        #execute the query
        $result = $this->db->execute($qury);

        if(!$result)
            return json_encode (array("msg"=>"Error in fetching user details","status"=>"error"));
        
        #fetching the array element and putting in a varible $pwd
        $row = $result->fetch_array(MYSQLI_ASSOC);

        #store the particular column data
        $pwd1 = $row['password'];
        $sipFlag = $row['sipFlag'];
        $userName = $row['userName'];

        #check curr_pwd is equal to database user password
        if ($pwd1 != $curr_pwd) {
            #echo "Please enter correct password";
            return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Correct Password'));
        } else {
            $new_pwd = $this->db->real_escape_string($new_pwd);
            
            #data to pass in update command that is new password
            $data = array("password" => $new_pwd);

	    $result1 = $this->updateWithEncryption($table, $data, "userId = '" . $userid . "' ");
            #update the table by new password corresponding to the userid
//            $query = $this->db->update($table, $data)->where("userId = '" . $userid . "' ");
//
//            #get the query sentence
//            $this->db->getQuery($query);
//
//            #execute the query
//            $result1 = $this->db->execute($query);

            #if query executed then
            if ($result1) {
                

                #enable user sip id
                $sipMsg = $this->enableSip($userid,1);
                $resultData = json_decode($sipMsg, TRUE);
                if($resultData['status'] != "success"){
                    trigger_error('user sip not enable in add new client ');
                }
                
                #echo "password changed successfully"
                return json_encode(array('msgtype' => 'success', 'msg' => 'Password Changed Successfully'));
            }
            #if query is not successfull    
            else {
                #weak password please chose another one.
                return json_encode(array('msgtype' => 'error', 'msg' => 'Password Is Too Weak'));
                ;
            }
        }
        
    }
   
    /**
       * @author Ankit Patidar <ankitpatidar@hostnsoft.com> 
       * @uses 19/11/2013
       * @param stirng $mongoId
       * @param string $startTime
       * @param string $userId
       * @param string $description
       * @param string $file
       * @param string $line
       * @return string return id
       */
      function callTracker($mongoId,$startTimeT,$userId = '',$msg = '',$desc=array(),$file,$line)
      {
        //convert start time to mongo date
        $startTime = new MongoDate(strtotime($startTimeT)); 
        //get end time
        $endTime = new MongoDate(strtotime(date('d-m-Y H:i:s')));
        
        //get total time
        $totalTime = strtotime(date('d-m-Y H:i:s'))-strtotime($startTimeT);
        
        //check for difference
        if($totalTime > 0)
            $totalDiffernce = new MongoDate($totalTime);
        else//assign zero to difference
            $totalDiffernce = 0;
        
        //set file and line
        $desc['file'] = $file;
        $desc['line'] = $line;
        //include required file
        include_once ('classes/db_class.php');
        //create mongo connection
        $dbObj = new db_class();
        
        //get collection name
        $collectionName = 'callTracker';
        
        //check for mongoId
        if(is_null($mongoId)) 
        {
            //apply exception handling 
            try 
            {
                //get mongo id
                $mongoId = new MongoId();
                
                //prepare info array
                $info = array("startTime" => $startTime, 
                              "endTime" => $endTime,
                              "Time" => $totalDiffernce, 
                              "msg" => $msg,
                              "description" => $desc);
                //prepare array to insert
                $data = array("_id" => $mongoId,
                              "info" => array($info), 
                              "userid" => $userId);
                
               
                $resultMongo = $dbObj->mongo_insert($collectionName,$data); //insert request 
                
                //log error
                if(!$resultMongo)
                    trigger_error('problem while insert in signUp tracker,data:'.json_encode($data));
                //free variable space
                unset($dbObj);
                
               
            } 
            catch (exception $e) 
            {
                //if exception occur then maintain log
                $returnArr = array("error" => $e->getMessage());
                trigger_error('Exception occur in sign Up tracker'.$returnArr);
               
            }
            return (string)$mongoId;
       }
        else //if mongo id not null
        {
           
            //apply exception handling
            try 
            {
                //where condition
                $where = array("_id" => new MongoId($mongoId));
                //prepare info array
                $info = array("startTime" => $startTime, 
                              "endTime" => $endTime,
                              "Time" => $totalDiffernce, 
                              "msg" => $msg,
                              "description" => $desc);
                
               //push to old info array
                $insertThis = array('$push' => array("info" => $info));
               
               //update document
               $updtReqId = $dbObj->mongo_update($collectionName,$where, $insertThis);
                
               //free variable space
               
                unset($dbObj);
               
              
            }//end of try 
            catch (exception $e) 
            {
                //maintain error log if exception occur
                $returnArr1 = array("error" => $e->getMessage());

            }
            return (string)$mongoId;
          }//end of else
          
          
      }//end of function paymentTracker 
      
   function getDomainResellerId($domianName,$type = 1)
    {
       /* @author sameer rathod 
         * @desc get the reseller id according to domain
         * modified by Sudhir Pandey <sudhir@hostnsoft.com>
         */
       
       if( $type == 3 )
        {
           //for fetching details with reseller Id 
            if(preg_match(NOTNUM_REGX, $domianName) || $domianName == "")
                return 0;
            
            $condition = " resellerId ='".$domianName."' ";
            
        }
        else
        {
            if(preg_match('/[^a-zA-Z0-9\@\_\.\-]+/', $domianName) || $domianName == "")
                return 0;
            
            $condition = " domainName = '".$domianName."' ";
        }
        
        $result = $this->selectData("*", "91_domainDetails",$condition);
       
        if($result && $result->num_rows > 0)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if($type == 1)
                $result = $row['resellerId'];
            elseif($type == 2 || $type == 3)
                $result = $row;
            else
                $result = 0;
        }
        else 
        {
            $result = 0;
        }
              
        return $result;
        
    }
   function getDomainResellerIdViaApc($domainName,$type = 1)
    {
        /* @author sameer rathod 
         * @desc get the reseller id according to domain
         * modified by Sudhir Pandey <sudhir@hostnsoft.com>
         */
       
       $domainName = trim($domainName);
        
        if(preg_match('/[^a-zA-Z0-9\@\_\.\-]+/', $domainName) || empty($domainName))
                return 0;
        
        $apcArry = array();
        $apcArray = apc_fetch("domainDetails");
        if(!is_array($apcArry) || empty($apcArry))
        {
            $result = $this->selectData("*", "91_domainDetails");
            if($result && $result->num_rows > 0)
            {
                while($row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    $data[$row['domainName']] = $row;
                }
                $apcStore = apc_store("domainDetails", $data);
                $apcArray = $data;
                
            }
        }
        
        if(!array_key_exists($domainName, $apcArray))
            return 0;
        else      
        {
            /* there is a exception which has to be  handled in future 
             * that if one reseller has many domain then which domain has
             * to be fetched 
             */
//            $row = $result->fetch_array(MYSQLI_ASSOC);
            if($type == 1)
                $result = $apcArray[$domainName]['resellerId'];
            elseif($type == 2)
                $result = $apcArray[$domainName];
            else
                $result = 0;
        }
        
              
        return $result;
    }

    function enableSip($userId,$action)
    {
//        if(!$this->check_admin())
//           return json_encode (array("msg"=>"This feature is only for admin","status"=>"error"));
        
        if(preg_match("/[^0-9]+/",$userId) || $userId == "")
            return json_encode (array("msg"=>"Invalid user please login","status"=>"error"));
        
        if($action == 1)
        {
            $sipFlag = 1;
        }
        elseif($action == 0)
        {
            $sipFlag = 0;
        }
        else
            return json_encode (array("msg"=>"Invalid sip flag please enter proper value","status"=>"error"));

        
        $result = $this->selectData('userName,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,chainId', "91_manageClient","userId=".$userId);
        
        if($result)
        {
            
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $userName  = $row['userName']; 
            $password  = $row['password']; 
            $sipChainId  = $row['chainId']; 
            $data = array("sipFlag"=>$sipFlag);
            $table = "91_userLogin";
            $condition = "userId=". $this->db->real_escape_string($userId);
            $res = $this->updateData($data, $table,$condition);
            $sipTable  = "91_verifiedSipId";
            if($res)
            {
                if($action == 1)
                {
                     
                   
//                    $insertVerifySipArr = array("userId"=>$userId,"userName"=>$userName,"passwd"=>$password,"chainId"=>$sipChainId,"isCallShopUser"=>0);

                    $conditionNew = " on duplicate key update userName = '".$userName."',passwd =AES_ENCRYPT('". $password."','".ENCRYPT_KEY."')";
                    
                
                    
                    $sql = "INSERT INTO ".$sipTable." (userId,userName,passwd,chainId,isCallShopUser) values('".$userId."','".$userName."',AES_ENCRYPT('". $password."','".ENCRYPT_KEY."'),'".$sipChainId."',0) ".$conditionNew;
                    $resSip = $this->db->query($sql);
                    
                    
                    if(!$resSip)
                    {
                        $updateData = array("sipFlag"=>0);
                        $updRes = $this->updateData($updateData, $table,$condition);
                        if(!$updRes || $this->db->affected_rows < 1)
                            return json_encode(array("msg"=>"Error updating sip will not function properly please contact provider","status"=>"error"));
                        
                        return json_encode(array("msg"=>"Error updating sip please try again","status"=>"error"));
                    }
                    
                    
                    if(HOST_NAME != LOCALHOST && HOST_NAME != TESTING_SERVER_NAME && HOST_NAME != TESTING_SERVER_NAME1) 
                    {
//                        ob_start();
//                        sip_delete($userName);
//                        sip_add($userName,$password);
                        $sqlBuddies = "INSERT INTO sip_buddies (name,sippasswd) values ('".$userName."',AES_ENCRYPT('". $password."','".ENCRYPT_KEY."')) on duplicate key update sippasswd=AES_ENCRYPT('". $password."','".ENCRYPT_KEY."')";
                        $resSipbuddies = $this->db->query($sqlBuddies);
                        if(!$resSipbuddies)
                            return json_encode(array("msg"=>"Error updating sip please try again 101","status"=>"error"));
//                        ob_end_clean();
                    }                        
                    
                    
                }
                elseif($action == 0)
                {
                    
                    $deleteRes = $this->deleteData($sipTable,$condition);
                    if($deleteRes)
                    {
                        
                         
                        if(HOST_NAME != LOCALHOST && HOST_NAME != TESTING_SERVER_NAME && HOST_NAME != TESTING_SERVER_NAME1)
                        {
//                            ob_start();   
////                            sip_delete($userName); 
//                            ob_end_clean(); 
                            
                            $deleteSipBuddies = $this->deleteData("sip_buddies","name='".$userName."'");
                            if(!$deleteSipBuddies)
                                return json_encode(array("msg"=>"Error deleting sip please try again 201","status"=>"error"));
                            
                        } 
                        
                         
                       
                    }
                    else
                        return json_encode(array("msg"=>"Error deleting sip please try again","status"=>"error"));   
                
                }
                
                return json_encode (array("msg"=>"Sip updated successfuly","status"=>"success"));
            }
        }
        else
           return json_encode (array("msg"=>"No data found for this user please contact provider","status"=>"error"));
    }
    
    /**
     *@author Ankit Patidar <ankitpatidar@hostnsoft.com>
     *@since 14/01/2014
     * @param int $userId 
     */
    function getLoginFlag($userId)
    {
        if(preg_match(NOTNUM_REGX, $userId))
        {
            $this->funResponse = 0;
            $this->funMessage = "Invalid User Id. It should be numeric.";
        }
        
        //apply query for login flag
        $result = $this->selectData("beforeLoginFlag", "91_userLogin"," userId =".$userId."");
            
        //validate result
        if($result && $result->num_rows > 0)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $loginFlag = $row['beforeLoginFlag'];
            $this->funResponse = 1;
             $this->funMessage = "Before Login flag sent successfully";

        }
        else 
        {
            $this->funResponse = 0;
            $this->funMessage = "Unable to find record of this user.";
            $loginFlag = 0;
        }
        
        return $loginFlag;
    }

    
    /**
     * updated by Ankit Patidar <ankitpatidar@hosntsoft.com> on 08/05/2014
     * @param int/string $userName it may username or userId
     * @param int $reqFlag
     * @return array
     */
    function getUserInformation($userName , $reqFlag = NULL)
    {
        //validate parameters
        if($reqFlag == 1 && (strlen($userName) == 0 ||  preg_match(NOTNUM_REGX, $userName)))
        {
            $this->msg = "Error user name invalid";
                return array();
        }
        else if(strlen($userName) == 0 || preg_match(NOTUSERNAME_REGX, $userName))
        {
            $this->msg = "Error userName invalid";
            return array();
        }
        
       
        $userName = $this->sql_safe_injection($userName);
        
        if($reqFlag == 1)
            $condition = " userId='".$userName."'";
        else
            $condition = " userName='".$userName."'";
       //apply query for login flag
        
        $result = $this->selectData('callingStatus,userPin,sipFlag,beforeLoginFlag,type,deleteFlag,isBlocked,AES_DECRYPT(password,"'.ENCRYPT_KEY.'") as password,userName,userId', "91_userLogin",$condition);
         trigger_error('enter in loginAs4:'.$userName); 
       
       //validate result
       if($result && $result->num_rows > 0)
       {
           $userDetail = $result->fetch_array(MYSQLI_ASSOC);
       }
       else
       {
           $this->msg = "Error fetching details";
           trigger_error('User Information not found:user:'.$userName);
           $userDetail = array();
       }
       return $userDetail;
    }
    
    /**
    *@abstract called from buymore.php
    */
    function getUserBalanceInfo($userId)
    {
        if(preg_match(NOTNUM_REGX, $userId))
        {
           return array();
        }
       $result = $this->selectData("`userId`, `chainId`, `tariffId`, `balance`, `currencyId`, `callLimit`, `resellerId`, `userBatchId`, `status`, `bandwidthLimit`, `getMinuteVoice`, `routeId`, `isDialPlan`, `callRecord`, `dialPlanId`", "91_userBalance","userId='".$userId."'");

       //validate result
       if($result && $result->num_rows > 0)
       {
           $userDetail = $result->fetch_array(MYSQLI_ASSOC);
       }
       else 
           $userDetail = array();

       return $userDetail;
    }
    
    /**
     * @author nidhi<nidhi@walkover.in>
     * last modified by Ankit Patidar <ankitpatidar@hostnsoft.com> on 28/8/2014 apply validation
     * @only one parameter will be passed here. ie callshop Id.
     * @updated by sameer rathod 06-06-14 inject a type for result 
     *          rest changes left for nidhi as per discussion with sudhir  
     * @used from :: edit-callshop.php direct in non mvc pattern
     */
    function getUserDetailsCallshop($param)
    {
        if(!isset($param['userId']) || preg_match(NOTNUM_REGX,$param['userId']) )
	    return false;
        
         if(!isset($param['fieldName']) || preg_match(NOTALPHABATE_REGX,$param['fieldName']))
	    return false;
         
         
        #condition for find username and pin detail 
        $condition = "userId = '" . $param['userId'] . "' ";

        
        #find user name of given id (we can not use session name because userid will change).
        $info = "91_userBalance";
        $userInfo = $this->selectData('*',$info,$condition);
//echo $this->querry;
        $userRecord = "";
        
        if ($userInfo->num_rows > 0) 
        {
            $user = $userInfo->fetch_array(MYSQLI_ASSOC);
           
            if($param['type'] == 1)
            {
                $userRecord[$param['fieldName']] = $user[$param['fieldName']];             
                $userRecord['tariffId'] = $user['tariffId'];             
            }else
                $userRecord = $user[$param['fieldName']];             
        }
        
        return $userRecord;
        
    }
    
    
    /*
     * @author nidhi<nidhi@walkover.in>
     * @parameter - only one parameters is passed here i.e Gcm Id
     * @description - This function is used to send gcm message.
     *  
     */
    function gcmApi($param)
    {
        #- registration id for current number going in gcm api
        if(!is_array($param['gcmId']))
            $id[0] = $param['gcmId'];
         else 
            $id = $param['gcmId'];
            
        #- Replace with real BROWSER API key from Google APIs
        $apiKey = "AIzaSyBe-X44jBuS2GL-N7edJxbc8PhVaQtwCGw";

        #- Replace with real client registration IDs 
        $registrationIDs = $id;

        #- Message to be sent
        //$message = $content;

        #- Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array( 'registration_ids'  => $registrationIDs,
                         'data' => array( "message" => 'hello' , "messgaeType" => 'hello' ));

        $headers = array( 'Authorization: key=' . $apiKey,
                           'Content-Type: application/json');

        #-  Open connection
        $ch = curl_init();
        
        #-  Set the url, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 ); 
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        #- Execute post
        $result = curl_exec( $ch );
        
        if( curl_errno( $ch ) )
            curl_error($ch);
        
        $result = json_decode($result);
    
        #- Close connection
        curl_close( $ch );
        //print_r($result);
        #- if gcm response is success
        if(is_object($result))
        {
            if($result->success!=0)
               $msgStatus='success';
            
            
                #- if gcm response id failure
                if($result->failure!=0)
                    $msgStatus='failure';

                    $results = $result->results;
                    if (is_object($results[0])) 
                        $results = get_object_vars($results[0]);
                    $messageId1=explode(':',$results['message_id']);

                    #- message id from gcm
                    $messageId=$messageId1[1];;
          }
            #- array for response contains messageStatus and messageId
            $gcmResponse=array("msgStatus" => $msgStatus,"messageId" => $messageId);	

        #- returning response
        return $gcmResponse;
    }#- end of function for GCM API
    
    
    function generateZipFile($param)
    {
        # load zipstream class
        $zip = new ZipArchive;
        
        $files = array();
        
        $param['shopId'];
        $param['systemId'];
        
        $dir = "";
        
        if(empty($param['shopId']))
             return json_encode(array("msgStatus" => "error" , "message" => "shopId is not defined" ));
        
        switch($param['type'])
        {
            case '0':
                 $dir = $param['shopId'].'/';
                 $zipName = $param['shopId'].time().'.zip';
                break;
            
            case '1':
                 $dir = $param['shopId'].'/'. $param['systemId'].'/';
                 $zipName = $param['shopId']."_".$param['systemId'].time().'.zip';
                break;
            
            case '2':
                 $dir = $param['shopId'].'/'. $param['systemId'].'/'.$param['date'].'/';
                 $zipName = $param['shopId']."_".$param['systemId']."_".$param['date'].time().'.zip';
                break;
            
        }
        //echo $zipName;
        unlink($zipName);
        //echo $dir;
       echo  $dir = '/RECORD/'.$dir;
        
            if (file_exists('/RECORD/'.$dir)) 
            {
            //echo "The file  exists";
            } 
            else 
            {
            echo "The file  does not exist"; die();
            }
       
       
         if(empty($dir))
            return json_encode(array("msgStatus" => "error" , "message" => "Please define correct type" ));
         
      
        
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        while($it->valid()) 
        {
            if (!$it->isDot()) 
            {

                 $files[] = $it->getSubPathName();
            }

            $it->next();
        }
        
        $archive_file_name = 'zip/'.$zipName;
        
        
        $attachedFiles = array();
        if ($zip->open('zip/'.$zipName,  ZipArchive::CREATE)) 
        {
            
            if(!empty($files))
                foreach($files as $file)
                {
                    $attachedFiles[] = $file;
                   if( !$zip->addFile($dir.$file, $file))
                   {
                       return json_encode(array("msgStatus" => "error" , "message" => "Error in getting file" ));
                   }
                }
            
            $zip->close();
            
            //then send the headers to foce download the zip file 
            header("Content-type: application/zip"); 
            header("Content-Disposition: attachment; filename=$archive_file_name"); 
            header("Pragma: no-cache"); 
            header("Expires: 0"); 
           
            $userIdResult = $this->selectData("email","91_verifiedEmails","userid =".$_SESSION['id']);
                         
            $verifiedEmail = 0;
           
            if($userIdResult->num_rows > 0)
            {
                $row = $userIdResult->fetch_array(MYSQL_ASSOC);
                $verifiedEmail = $row['email'];
            }
            
            if($verifiedEmail)
            {
                $Message = "Zip file sent successfully on your verified emailId ";

                $auth = $this->randomNumber(14);
                $data['auth'] = $auth;
                $data['fileName'] = $zipName;
                $response = $this->insertData($data,"91_checkAuth");
                
                if($response)
                {
                    $subject = "Phone91 Call Record";
                    $mailData = 'Hello, Below is the link to download zip file. <a href="'.HOST_NAME.'/getCallRecord.php?auth='.$auth.'&file='.$zipName.'"  style="color:#296FA2;  text-decoration:none">Click Here</a>';
                    $from = "support@phone91.com";
                    $email = $verifiedEmail;
                    $response = $this->sendErrorMail($email, $mailData,$from,$subject) ;
                }
                else
                {
                    logmonitor("phone91-mail" , "error while inserting records in download call record");
                }
                
                #- call function to send mail
              
            }
            else 
            {
                 $Message = "Please verify your email Id to get Zip File.";
            }
            
            
            
            return json_encode(array("msgStatus" => "success" , "message" => $Message  ));
        } 
        else 
        {
             return json_encode(array("msgStatus" => "error" , "message" => "Unable to create zip file" ));
        }
        
        
          
    }
    
      ## checking country Code exist.
    function getCountryCodeAndNumber($number)
    {
       
        $codeExist = 0;
        $code = '';
        
        $numlength = strlen($number);
        
        if($numlength <= 10)
        {
          return  array( 'exist' => 0, 'code' => $code );
        }
        ## all country Code Array..
//        $allNumbersArray = array(355,213,244,264,268,54,374,297,61,43,994,242,973,880,375,32,501,229,591,387,267,55,359,226,855,237,1,238,56,86,57,506,385,357,420,45,809,593,20,503,372,679,358,33,241,49,233,30,502,245,509,504,852,36,354,91,62,353,
//972,39,876,81,962,7,254,965,996,856 ,371,961,370,352,389,60,223,356,230,52,373,212,258,95,264,977,31,599,64,505,227,234,47,968,92,507,675,595,51,63,48,351,974,40,7,250,966,221,381,65,421,386,27,34,94,46,41,886,992,255,66,228,868,216,971);
        
        $allNumbersArray = array(93,355,213,1684,376,244,1264,672,1268,54,374,297,61,43,994,1242,973,880,1246,375,32,501,229,1441,975,591,387,267,55,246,1284,673,359,226,95,257,855,237,1,238,1345,236,235,56,86,61,61,57,269,682,506,385,53,357,420,243,45,253,1767,1809,593,20,503,240,291,372,251,500,298,679,358,33,689,241,220,970,995,49,233,350,30,299,1473,1671,502,224,245,592,509,39,504,852,36,354,91,62,98,964,353,44,972,39,225,1876,81,962,7,254,686,381,965,996,856,371,961,266,231,218,423,370,352,853,389,261,265,60,960,223,356,692,222,230,262,52,691,373,377,976,382,1664,212,258,264,674,977,31,599,687,64,505,227,234,683,672,850,1670,47,968,92,680,507,675,595,51,63,870,48,351,974,242,40,7,250,590,290,1869,1758,1599,508,1784,685,378,239,966,221,381,248,232,65,421,386,677,252,27,82,34,94,249,597,268,46,41,963,886,992,255,66,670,228,690,676,1868,216,90,993,1649,688,256,380,971,44,1,598,1340,998,678,58,84,681,970,212,967,260,263,1868,95);
        $numone = substr( $number , 0 ,1);
        $numTwo = substr( $number , 0 ,2);
        $numThree = substr( $number , 0 ,3);
        $numFour = substr( $number , 0 , 4);
        
        $state = 'false';
        
        ## checking for each digit of country code.
        if(in_array($numone, $allNumbersArray))
        {
             $code = $numone;
             $codeExist++;
             $state = 1;
        }
        else if(in_array($numTwo, $allNumbersArray))
        {
            $code = $numTwo;
            $codeExist++;
            $state = 2;
        }
        else if(in_array($numThree, $allNumbersArray))
        {
            $code = $numThree;
            $codeExist++;
            $state = 3;
        }
        else if(in_array($numFour, $allNumbersArray))
        {
            $code = $numFour;
            $codeExist++;
              $state = 4;
        }
        
        if($codeExist > 0)
		{
            $result = 1;
			$num=substr($number,strlen($code),$numlength-strlen($code));
		}
        else 
		{
           $result = 0;
           $num='';
		}
        $result = array( 'exist' => $result , 'code' => $code ,'state' => $state,'number' => $num);
        
        return $result;
    } //end of function getCountryCodeAndNumber
	
	
    function longCodeResellerId($accessNumber , $type = 2)
    {
    
        if(preg_match( NOTMOBNUM_REGX, $accessNumber ))
        {
            return json_encode(array("msgStatus" => "error" , "message" => "Invalid access NUmber" ));
        }
        
        $prefix = '';
        $accNoResult = $this->selectData("resellerId,prefix"," 91_longCodeNumber","longCodeNo =".$accessNumber." and type=".$type);
        
        //echo $this->querry;
        
        if($accNoResult->num_rows > 0)
        {
                $row = $accNoResult->fetch_array(MYSQL_ASSOC);
                $resellerId = $row['resellerId'];
                $prefix = $row['prefix'];
        }else       
        $resellerId = 2;
        
        return  json_encode(array("msgStatus" => "success" , "resellerId" => $resellerId ,"prefix"=>$prefix));
    }
    
    function checkVerifiedNumber($senderId,$resellerId)
    {

        if(preg_match( NOTMOBNUM_REGX, $senderId ))
        {
            return json_encode(array("msgStatus" => "error" , "message" => "Invalid Sender Id" ));
        }
        
       
        if(preg_match( NOTNUM_REGX, $resellerId ))
        {
            return json_encode(array("msgStatus" => "error" , "message" => "Invalid reseller id" ));
        }
        
        #search sender id into verified table for check number are verified or not 
        $result = $this->selectData("userId"," 91_verifiedNumbers","CONCAT(countryCode,verifiedNumber) = '".$senderId."' and domainResellerId=".$resellerId);
        
        $row = 0;
        $response = "error";
        
        if($result->num_rows > 0)
        {
            $row = $result->fetch_array(MYSQL_ASSOC);
            $response = "success";
        }
        
        return  json_encode(array("msgStatus" => $response , "userInfo" => $row ));
    } 
    
    function  randomdigit($digits)  
    {   
        static  $startseed  =  0;   
        
        if(!$startseed)  
        { 
            $startseed  =  (double)microtime()*getrandmax();   
            srand($startseed); 
        } 
        
        $range  =  8; 
        $start  =  1; 
        $i  =  1; 
        
        while($i < $digits)  
        { 
            $range  =  $range  .  9; 
            $start  =  $start  .  0; 
            $i++; 
        } 
        return  (rand()%$range+$start);   
    } 
    
    function getProtocol()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') 
        {
            $protocol = 'https://';
        }
        else 
        {
            $protocol = 'http://';
        }
            
        return $protocol;
    }
    
    
    /**
     *@author Ankit patidar <ankitpatidar@hostnsoft.com> 
     * @since 11/03/2014
     * @param string $userVar
     * @param int $from 
     * @param int $to 
     */
    function getUserOneDetail($userVar,$from,$to)
    {
        if($from =='userName' && preg_match('/[^a-zA-Z0-9\@\.\_]+/', $userVar))
        {
            return json_encode(array("msg"=>"Invalid user name please enter a valid name","status"=>0));
        } 
        elseif($from =='userId' && (preg_match(NOTNUM_REGX,$userVar) || $userVar == "" || is_null($userVar)))
              return json_encode(array("msg"=>"Invalid user name please enter a valid name","status"=>0));
      else if($from =='chainId' && ($userVar == "" || is_null($userVar)))
              return json_encode(array("msg"=>"Invalid invalid chainId","status"=>0));
        
        $userVar = $this->db->real_escape_string($userVar);
        $condition = $from."= '" . $userVar . "' ";

        #find userId of given name 
        $manageClient = "91_manageClient";
        $userinfo = $this->selectData($to, $manageClient,$condition);
        
        if ($userinfo->num_rows > 0) 
        {
            $user = $userinfo->fetch_array(MYSQLI_ASSOC);
        
            return json_encode(array("msg"=>"Record found successfully","status"=>1,$to=>$user[$to]));      
        } 
        return json_encode(array("msg"=>"Record not found!!!","status"=>0));
        
        
    }
    
     /*
     * @author nidhi<nidhi@walkover.in>
     * This function is to check user is blocked or not.
     * there is onely one parameter particular.
     * particular may be ipAddress,emailId or mobileNumber
     * 
     */
    function  checkBlockUser($particular)
    {
        $iparr = array("111.118.250.235","111.118.250.236","111.118.250.237","111.118.250.238");
        if(in_array($particular,$iparr))
            return 0;
            
        $result = $this->selectData("particular"," 91_blockIp","particular = '".$particular."' ");
         
         if ((!$result) || ($result->num_rows <= 0))
            return 0;
         else
            return 1;
    }
    
    function unblocUserIp($param)
    {
        if(!is_numeric($param['BlockId']))
        {
            $responseArr["msg"] = "Invalid User Id";
            $responseArr["status"] = "0";
        }
        
        $deleteRes = $this->deleteData("91_blockIp","sNo=".$param['BlockId']);
        
        if($deleteRes)
        {
            $responseArr["msg"] = "User is unblocked successfully";
            $responseArr["status"] = "1";
        }
        else    
        {
            $responseArr["msg"] = "Please try again later";
            $responseArr["status"] = "0";
               
        }
        
        return json_encode($responseArr);
    }
    
    function validateBlockUser($param)
    {
       if(is_array($param['userIpAddress']) && is_array($param['reason']))
       {
           foreach($param['userIpAddress'] as $key=>$value)
           {
              // EMAIL_REGX ||  NOTMOBNUM_REGX || NOTTEXT_REGX || NOT_IP_ADDRESS || IP_ADDRESS
               
                $type = 0;
                
                $value = trim($value);
                
                $isInavlid = 0;
                if(preg_match(IP_ADDRESS ,$value ))
                {
                    $type =  "1";
                   
                }
                else if(preg_match(EMAIL_REGX ,$value ))
                {
                    $type =  "2";
                }
                else if(!preg_match(NOTMOBNUM_REGX ,$value ))
                {
                    $type =  "3";
                }
                else 
                {
                     $isInavlid = 1;
                }
                
                if(preg_match( NOTTEXT_REGX , $param['reason'][$key] ))
                {
                     $isInavlid = 1;
                }
                
                $param['type'][$key] = $type;
                
                if($isInavlid)
                {
                   unset($param['userIpAddress'][$key]);
                   unset($param['reason'][$key]);
                   unset( $param['type'][$key]);
                }
                
           }
           
           $param['sizeOfRow'] = count($param['userIpAddress']);
           
           if($param['sizeOfRow'] < 1) 
           {
                $responseArr["msg"] = "Invalid Value to block Please enter atleast one correct value";
                $responseArr["status"] = "0";
                return json_encode($responseArr);
           }
           
           
       }
       else 
       {
           $responseArr["msg"] = "Invalid Request";
           $responseArr["status"] = "0";
           return json_encode($responseArr);
       }
       
       return $param;
       
    }
    
    function addBlockUserInfo($param)
    {   
        $result = $this->validateBlockUser($param);
        
        if(!is_array($result))
            return $result;
        
        $sqlMan = "INSERT INTO  91_blockIp (blockedBy,type,particular,reason) values ";
        
      //  print_r($_SESSION);
        
        foreach($result['userIpAddress'] as $key => $value)
        {
            #Loop through each input field to get the value
            if ($value) 
            {
                $sqlMan .= "('".$_SESSION['acmId']."','".trim($result['type'][$key]) ."','" .trim($value). "','". trim($result['reason'][$key]) ."'),";
            }
        }
        
        $sqlMan = substr($sqlMan, 0, -1);
        
        $sqlExtend = " on DUPLICATE KEY UPDATE particular=VALUES(particular),reason=VALUES(reason)";
        
        $sqlMan = $sqlMan . $sqlExtend;
                
        if ($this->db->query($sqlMan))
        {
            include_once("classes/account_manager_class.php");
            $acmObj = new Account_manager_class();
            $result = $acmObj->allBlockUserList("",$_SESSION);
            
            $responseArr["content"] = $result;
            $responseArr["msg"] = "Successfully added to black List";
            $responseArr["status"] = "1";
        }
        else 
        {
            $responseArr["msg"] = "Invalid Request";
            $responseArr["status"] = "0";
        }
        
        return json_encode($responseArr);
    }
    
    
    /*
     * @author nidhi<nidhi@walkover.in>
     * This function is to get verified number from user id and confirmation code.
     */
    function getVerifiedNumber($userId , $confirmCode )
    {
        $userId = $this->db->real_escape_string($userId);
        
        $confirmCode = $this->db->real_escape_string($confirmCode);
        
        
         $condition = "userId='".$userId."' and  confirmCode='".$confirmCode."'";

        #find userId of given name 
        $tableName = "91_verifiedNumbers";
        
        $userinfo = $this->selectData("verifiedNumber,countryCode", $tableName,$condition);
        
      
        
        if($userinfo->num_rows > 0) 
        {
            $user = $userinfo->fetch_array(MYSQLI_ASSOC);
            return json_encode(array("msg"=>"Record found successfully","status"=>1,"userData" => json_encode($user)));      
        }
        
        return json_encode(array("msg"=>"Record not found!!!","status"=>0));
    }
    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 19-03-2014
     * @desc function use to check sender id prefix match longcode ( accessnumber ) prefix 
     */
    function senderIdPrefixMatch($prefix,$senderId){
 
        $prefixMatch = '/^'.$prefix.'[0-9]{7,16}$/';
        if(!preg_match($prefixMatch, $senderId)){
           $senderId = $prefix.$senderId;
        }
        
        return $senderId;
    }
    
    /*
     * @author nidhi<nidhi@walkover.in>
     * @param No parameters required.
     * @desc returns json of country names.
     * This function is used in case of forgot password.
     */
    
    function getCountryList()
    {
        include_once('csvtoarray/csv.inc.php');
        $csv = new csv_uploder('csvtoarray/iso.csv', 2000 , ',');
        $array = $csv->getCsv();

        foreach($array as $value)
        {
            $data["Country"] = $value["Country"];
            $data["CountryCode"] = trim($value["CountryCode"]);
            $data["ISO"] = $value["ISO"];
            $response[] = $data;
        }
        
      return  $response = json_encode($response);
    }
    
    /*
     * @author nidhi<nidhi@walkover.in>
     * 
     */
    function addTotempNumbers($param)
    {  
        
        //print_r($param);
        
        //$userId = $this->getUserId($param['username']);
       
        $confirmCode = $this->generatePassword();
        
        #value for store in database 
        $tempNumTable = "91_tempNumbers";
        
        $data = array(  "userId" => (int)$param['userId'],
                        "countryCode" => (int)$param['countryCode'],
                        "tempNumber" => $param['contactNumber'],
                        "domainResellerId" => $param['resellerId'],
                        "confirmCode" => $confirmCode ,
                        "date" => date( 'Y-m-d H:i:s' )); 
        
        #insert query (insert data into 91_tempcontact table )
        $tempNumResult = $this->insertData($data, $tempNumTable);
        
       // echo $this->querry;
        
        $this->sendSmsCall( $param['contactNumber'] , $param['countryCode'] , $confirmCode,$param['smsCall'] );
        
        if(!$tempNumResult)
        {
            $response = array("status" => "error" , "msg" => "Error in updating Contact Number");
        }
        else 
        {
            $response = array("status" => "error" , "msg" => "Contact Updated successfully");
        }
        
        return json_encode($response);
        
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 4/4/2014
     * @param int $userId
     * @return 0 on fail and time offset of reseller on success
     */
    function getResellerTimeZone($userId)
    {
        if(empty($userId) || !is_numeric($userId))
            return '+0:00';
        
        #find userId of given name 
        $tableName = "91_personalInfo";
        
        $userInfo = $this->selectData("offset", $tableName,'userId='.$userId);
        
        if(!$userInfo || $userInfo->num_rows == 0)
        {
            trigger_error('problem while getting timezone,userId='.$userId);
            return '+0:00';
        }
        
        $row = $userInfo->fetch_array(MYSQLI_ASSOC);

        $offset = $row['offset'];
        
        return $offset;
    }
    
    /*
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 09-04-14
     * @desc get currency list from database and show in select box 
     */
    function getCurrencyList(){
          
      $currencyList = array();
        
      $table = '91_currencyDesc';
      
      #get all admin id from  userlogin table  
      $result = $this->selectData('*',$table,"status = 1");
      
      if($result->num_rows > 0)
      {
           while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $currencyList[$row['currencyId']] = $row['currency'];
           }
      }
        
      return json_encode($currencyList); 
        
    }
    
    public function exportRecords($tariffArray,$type,$title="CSV", $fieldType = array())
    {
        if(empty($tariffArray) || !is_array($tariffArray) || $tariffArray == "")
        {
            $this->msg = "Error Unable to fetch details please try again later or contact provider";
            $this->status = "error";
            return false;
        }
        
        if(empty($fieldType))
        {
            $this->msg =  "Plese Enter Field type";
            $this->status = "error";
            return false;
        }
        
        if($type == "csv")
        {
            date_default_timezone_set("Asia/Kolkata");
            $timeStamp = date('d_m_y_H_i_s');
            
            $fileName = dirname(__FILE__)."/exportFiles/".$timeStamp.".csv";
            
            if(file_exists($fileName))
                return $fileName;
            
            ini_set('display_errors',1);
            error_reporting(E_ALL);
            
            $fp = fopen($fileName, "w");
           
            fputcsv($fp, $fieldType);
            foreach($tariffArray as $innerArray)
            {
                $newTarrif = array();
                foreach($fieldType as $key=>$field)
                {
                    $newTarrif[$field] =  $innerArray[$field]; 
                }
                
                fputcsv($fp, $newTarrif);
                
                unset($newTarrif);
            }
            
            fclose($fp);
            return $fileName;
            
        }
        elseif($type == "xlsx")
        {
            ini_set('max_execution_time', 3000);

            require_once CLASS_DIR.'PHPExcel.php';
            require_once CLASS_DIR.'PHPExcel/Writer/Excel2007.php';

             #- GET THE FILE NAME AND EXTENSION
            ini_set('memory_limit', '512M');

            $objPHPExcel = new PHPExcel();
            // Set properties

            $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Tariff Rate");
            $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Tariff Rate");
            // Add some data

            $objPHPExcel->setActiveSheetIndex(0);
            
            $i = 1;
            
            foreach($fieldType as $key => $field)
                   $objPHPExcel->getActiveSheet()->SetCellValue($key.$i, $field);
             $i++;
            foreach($tariffArray as $innerArray)
            {
                foreach($fieldType as $key => $field)
                {
                    $objPHPExcel->getActiveSheet()->SetCellValue($key.$i, $innerArray[$fieldType[$key]]);
                }
                $i++;
            }

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle($title);
            // Save Excel 2007 file
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $timeStamp =date('d_m_y_H_i_s');
            
            $fileName = dirname(__FILE__)."/exportFiles/".$timeStamp.".xlsx";
           
            try {
                
               
                 $res = $objWriter->save($fileName);
                
                 
            } 
            catch (Exception $ex) 
            {
                echo "PARSER ERROR: ".$e->getMessage()."<br />\n";
            }
           
            
            return $fileName;
        }
        else 
        {
            $this->msg = "Error Invalid format can not export the file";
            $this->status = "error";
            return false;
        }
    }
    
    public function downloadExportedFile($filename)
    {
        // required for IE, otherwise Content-disposition is ignored
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        // addition by Jorg Weske
        $file_extension = strtolower(end(explode(".",$filename)));
         

        if ($filename == "") 
        {
            echo "<html><title> Download </title><body>ERROR: download file NOT SPECIFIED. </body></html>";
            exit;
        } elseif (!file_exists($filename)) {
            echo "<html><title> Download </title><body>ERROR: File not found. </body></html>";
            exit;
        }
        
        switch ($file_extension) {
            case "pdf": $ctype = "application/pdf";
                break;
            case "exe": $ctype = "application/octet-stream";
                break;
            case "zip": $ctype = "application/zip";
                break;
            case "doc": $ctype = "application/msword";
                break;
            case "xls": $ctype = "application/vnd.ms-excel";
                break;
            case "ppt": $ctype = "application/vnd.ms-powerpoint";
                break;
            case "gif": $ctype = "image/gif";
                break;
            case "png": $ctype = "image/png";
                break;
            case "jpeg":
            case "jpg": $ctype = "image/jpg";
                break;
            default: $ctype = "application/force-download";
        }
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers 
        header("Content-Type: $ctype");
        // change, added quotes to allow spaces in filenames, by Rajkumar Singh
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($filename));
        readfile("$filename");
        exit();
    }  
    
    public function getRouteName($routeId) 
    {
        
        $condition = "routeId = '" . trim($routeId) . "'";
        $result = $this->selectData('route', '91_route',$condition );

        if($result->num_rows > 0)
        {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
            {
                return $row['route'];
            }
        }
    }
    
    /**
     * @author sameer rathod
     * @param type $param
     * @return boolean
     */
    public function cacheSignUpDetails($param){
        
      
        if(preg_match(NOTALPHANUM_REGX, $param['firstName']) || strlen($param['firstName']) > 30){
            $this->msg = "Error invalid first name must contain alphabets or number and not more then 30 character";
            $this->code = "3001";
            return false;
        }
        if(preg_match(NOTALPHANUM_REGX, $param['lastName']) || strlen($param['lastName']) > 30){
            $this->msg = "Error invalid last name must contain alphabets or number and not more then 30 character";
            $this->code = "3002";
            return false;
        }
        if(!preg_match(EMAIL_REGX, $param['email']) || strlen($param['email']) < 4 ||strlen($param['email']) > 40){
            $this->msg = "Error invalid email Id must not be more then 40 character";
            $this->code = "3003";
            return false;
        }
        if(preg_match(NOTUSERNAME_REGX, $param['username']) || strlen($param['username']) < 4  || strlen($param['username']) > 40){
            $this->msg = "Error invalid userName must contain alphabets or number and not more then 40 character";
            $this->code = "3004";
            return false;
        }
        if(preg_match(NOTPASSWORD_REGX, $param['password']) || strlen($param['password']) < 7 || strlen($param['password']) > 30){
            $this->msg = "Error invalid password";
            $this->code = "3005";
            return false;
        }
        if(preg_match(NOTNUM_REGX, $param['currency']) || strlen($param['currency']) < 1 || strlen($param['currency']) > 8){
            $this->msg = "Error invalid currency";
            $this->code = "3006";
            return false;
        }
        
        
        if(preg_match(NOTNUM_REGX, $param['signupFrom']) || $param['signupFrom'] < 1 || $param['signupFrom'] > 9){
            $this->msg = "Error invalid signUp from value";
            $this->code = "3007";
            return false;
        }
        if(preg_match('/[^a-zA-Z0-9\-\.\:\/]/', $param['domain']) || strlen($param['domain']) < 4 ||strlen($param['domain']) > 66){
            $this->msg = "Error invalid domain";
            $this->code = "3008";
            return false;
        }
        $table = "91_signUpCache";
        $sql = "INSERT INTO ".$table." (firstName,lastName,email,username,password,currency,signupFrom,domain,tempId) values ('".$param['firstName']."','".$param['lastName']."','".$param['email']."','".$param['username']."','".$param['password']."','".$param['currency']."','".$param['signupFrom']."','".$param['domain']."','".$param['tempId']."') ON DUPLICATE KEY UPDATE firstName = '".$param['firstName']."',lastName = '".$param['lastName']."',username = '".$param['username']."',password = '".$param['password']."',domain='".$param['domain']."',tempId='".$param['tempId']."',signupFrom='".$param['signupFrom']."'";
       
        $res = $this->db->query($sql);
        if($res)
            return true;
        else{
            $this->msg = "Error Unable to cache the request please try again";
            $this->code = "3009";
            return false;
        }
    }
    
    /**
     * @author sameer rathod <sameer@hostnsoft.com>
     * @param type $Id
     * @param type $domain
     * @return boolean
     */
    function getSignUpDetailsFromCache($Id,$domain){
        
        //initilize variable
        $this->data = false;
        if(preg_match(NOTALPHANUM_REGX, $Id) || empty($Id) || strlen($Id)  > 40){
            $this->msg = "Error Invalid Id please provide a valid Id";
            $this->code = "3003";
            return false;
        }
        if(preg_match('/[^a-zA-Z0-9\-\.\:\/]+/', $domain) || strlen($domain) < 4 ||strlen($domain) > 66){
            $this->msg = "Error invalid domain";
            $this->code = "3008";
            return false;
        }
        $table = "91_signUpCache";
//         $condition = "tempId = '".$Id."' and domain = '".$domain."'";
        
        //change temperaroly as per situation
         $condition = "tempId = '".$Id."' ";
       
        $result = $this->selectData("*", $table, $condition);
        if(!$result)
        {
            $this->msg = "Error unable to get the details for signup please try again";
            $this->code = "3010";
            return false;
        }
        else{
            $row = $result->fetch_array(MYSQL_ASSOC);
            $this->data = $row;
            return true;
        }
    }
    
    /**
     * @author sameer rathod 
     * @param type $userId
     * @param type $number
     * @param type $type 1 for verified table else for temp table
     * @return boolean
     */
    public function updateConformationCode($userId,$number,$type)
    {
        if(preg_match(NOTNUM_REGX, $userId) || empty($userId))
        {
           $this->msg = "Error Invalid user please try again";
           $this->code = "4010";
           return false; 
        }
        
        if(!preg_match(PHNNUM_REGX, $number) || empty($number))
        {
           $this->msg = "Error Invalid number please try again";
           $this->code = "4010";
           return false; 
        }
        
        $confirmCode = $this->generatePassword();

        
        $data = array("confirmCode" => $confirmCode);

        if($type == 1)
        {
            $table = '91_verifiedNumbers';
            $condition = "userId='" . $userId . "' and CONCAT(countryCode,verifiedNumber) ='".$number."'";
        }
        else
        {
            $table = '91_tempNumbers';
            $condition = "userId='" . $userId . "' and CONCAT(countryCode,tempNumber) ='".$number."'";
        }

        $updRes = $this->updateData($data, $table , $condition);
        
        if($updRes)
        {
            $this->msg = "Successfuly updated the confirm code";
            $this->code = "202";
            $this->setData($confirmCode);
            
            return true;
        }
        else
        {
            $this->msg = "Error Unable to update the details";
            $this->code = "4011";
            return false;
        }
    }
    
    /**
  
     * @param type $data   * @author sameer rathod
     * @desc use this function to set global output
     */
    public function setData($data)
    {
        unset($this->data);
        $this->data = $data; 
    }
    
    public function updateUserLoginPin($pin,$userId){
        if(preg_match(NOTNUM_REGX, $pin) || empty($pin))
        {
            $this->msg = "Error invalid pin";
            $this->code = "403";
            $this->status = "error";
            return false;
        }
        if(preg_match(NOTNUM_REGX, $userId) || empty($userId))
        {
            $this->msg = "Error invalid user please try agian with a valid user";
            $this->code = "403";
            $this->status = "error";
            return false;
        }
        $data = array("userPin"=>$pin);
        $table = "91_userLogin";
        $condition = "userId = '".$userId."'";
        $result = $this->updateData($data, $table, $condition);
        if(!$result)
        {
            $this->msg = "Error unable to update data please try again";
            $this->code = "405";
            $this->status = "error";
            return false;
        }else
            return true;
    }
    
    
    
    /**
     * @author sameer rathod
     * @param type $type
     * @param type $userId
     * @param type $resendBy
     * @return boolean
     */
    public function setResendCounter($userId,$type,$resendBy){
//        echo "dfasdfasd";
        if(preg_match(NOTNUM_REGX, $type) || empty($type)){
            return false;
        }
        
        if(preg_match(NOTNUM_REGX, $userId) || empty($userId)){
            return false;
        }
        
        if(preg_match(NOTNUM_REGX, $resendBy) || empty($resendBy)){
            return false;
        }
        
        #table name 
        $table = '91_resendCode_new';
        
        #type 1 for phone and 2 for email
        $data = array("type"=>$type,"userId"=>$userId,"resendBy"=>$resendBy,"ip"=>"".$this->getUserIP()); 
//print_r($data);
        #insert query (insert data into 91_tempcontact table )
        $result = $this->insertData($data, $table);
//        echo $this->querry;
        if($result)
        {
            return true;
        }
        return false;

    }
    
    /**
     * @author sameer rathod
     * @param type $userId
     * @param type $type
     * @param type $resendBy
     * @return boolean
     */
    public function getResendCounter($ip,$userId=NULL,$resendBy=NUll,$type=NUll){
        
        $condition = "";
        if(!is_null($type) && (preg_match(NOTNUM_REGX, $type) || empty($type))){
            $this->status ="error";
            $this->code ="322";
            $this->msg ="Error invalid type please provide valid data";
            return false;
        }
        
        
        if(!is_null($resendBy) && (preg_match(NOTNUM_REGX, $resendBy) || empty($resendBy))){
            $this->status ="error";
            $this->code ="321";
            $this->msg ="Error invalid resendby value please provide valid data";
            return false;
        }
        
        if(!is_null($userId) && (preg_match(NOTNUM_REGX, $userId) || empty($userId))){
            $this->status ="error";
            $this->code ="323";
            $this->msg ="Error invalid user id please provide valid data";
            return false;
        }
        
        if(preg_match('/[^0-9\.]/', $ip) || empty($ip)){
            $this->status ="error";
            $this->code ="324";
            $this->msg ="Error invalid ip please provide valid data";
            return false;
        }
        
        if(!is_null($type))
        {
            $condition .= "type = ".$type." and ";
        }
        
        if(!is_null($resendBy))
        {
            $condition .= " resendBy = ".$resendBy." and ";
        }
        
        if(!is_null($userId))
        {
            $condition .= " userId = ".$userId." and ";
        }
        
        $condition .= " ip = '".$ip."' ";
        
        $table = "91_resendCode_new";
        
        /** get the counter of one hour**/
        $conditionHours = $condition." and date >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        
        
        $hourRes = $this->selectData("count(*) as counter", $table, $conditionHours);
        
        if(!$hourRes)
        {
            $this->status ="error";
            $this->code ="325";
            $this->msg ="Error fetching details please try again";
            return false;
        }
        $hourRow = $hourRes->fetch_array(MYSQLI_ASSOC);
        $hourCounter = $hourRow['counter'];
        
        $conditionDay = $condition." and DATE(date) = CURDATE()";
        /**** get the counter of one day ****/
        $dayRes = $this->selectData("count(*) as cnt", $table, $conditionDay);
        if(!$dayRes)
        {
            $this->status ="error";
            $this->code ="326";
            $this->msg ="Error fetching details";
            return false;
        }
        $dayRow = $dayRes->fetch_array(MYSQLI_ASSOC);
        $dayCounter = $dayRow['cnt'];
        
        $data['hourCounter']=$hourCounter;
        $data['dayCounter']=$dayCounter;
//         print_R($data);
        $this->setData($data);

        return true;
    }
    
    
    function getContactDetails($contactNumber , $resellerId = NULL )
    {
         
        if(is_null($resellerId))
        {

            $resellerId = $_SESSION['resellerId'];
        }
        else if(!is_numeric( $resellerId ))
        {
            return 0;
        }
        
   
        $condition = " CONCAT(countryCode,verifiedNumber) = ".$contactNumber." AND resellerId = ".$resellerId;
        $userInfo = $this->selectData('*',"91_verifiedNumbers",$condition);

        $userId = 0;
        
        if ($userInfo->num_rows > 0) 
        {
            $contactDetails = $userInfo->fetch_array(MYSQLI_ASSOC);  
            //print_r($contactDetails); 
            $userId = $contactDetails['userId'];
        } 
        
        return $userId; 
    }
    
    
    ##- parameters user userInfo 
    ##- This function is to get users currency and currency rate.
    function getUserCurrencyAndRate($userInfo , $currency )
    {
        $userId= 0;
        
        
        
        if(preg_match(NOTALPHANUM_REGX, $currency ))
        {
           return json_encode(array("status" => "error", "msg" => "Please enter valid currency. "));
        }
        
        if(!preg_match( NOTMOBNUM_REGX, $userInfo )) 
        {    
           $userId =  $this->getContactDetails($userInfo );
        }
        else if(preg_match( EMAIL_REGX , $userInfo )) //
        {  
           $userArr =  $this->getUserFromEmail($userInfo );
         
           if(is_array($userArr))
               $userId = $userArr[0]['userid'];
        }
        else if(!preg_match( NOTUSERNAME_REGX , $userInfo )) //
        {    
           $userId =  $this->getUserId($userInfo );
        }
        
        if($userId == $_SESSION['id'])
        {
           return json_encode(array("status" => "error" , "message" => "You can not transfer fund to yourself." )); 
        }
        
        if($userId )
        {
           // echo $userId;
            $balanceInfo =  $this->getUserBalanceInfo($userId); //currencyId 
            $currencyId = $this->getOutputCurrency($balanceInfo['tariffId']);   
            $usersCurrency = $this->getCurrencyViaApc($currencyId ,1);
            
            
            if(empty($currency) || empty($usersCurrency) )
            {
                return json_encode(array("status" => "error" , "message" => "Please enter valid currency." ));
            }
            
            $rate = $this->currencyConvert($currency, $usersCurrency , 1 );
            
            $validTarrifId = array(7,8,9,84);

            if(!in_array($balanceInfo['tariffId'] ,$validTarrifId ))
            {
                  return json_encode(array("status" => "error" , "message" => "This user do not have valid tarrifs." ));
            }
           // print_r($balanceInfo);
            if($balanceInfo['resellerId'] != 2)
            {
                 return json_encode(array("status" => "error" , "message" => "you can not transfer fund to this user." ));
            }
            
            $result =  $this->getUserLoginDetails($userId , 'type');
            
            if ( $result->num_rows > 0 ) 
            {
                $receiverInfo = $result->fetch_array(MYSQLI_ASSOC);
                
                if($receiverInfo['type'] != 3)
                    return json_encode(array("status" => "error" , "message" => "Only users are allowed to transfer fund via this feature." ));
            }
            
            return json_encode(array("status" => "success" , "message" => ""  , "content" => array("currency" => $usersCurrency , "rate" => $rate , "userId" => $userId)));

        }
        else 
        {
             return json_encode(array("status" => "error" , "message" => "No user found for this Information." ));
        }
    }
    
    
    #- This function is to check valid string in array.
    function checkValidationArr($giverArr , $receiverArr)
    {
        if(preg_match(NOTNUM_REGX, $giverArr['id'] ))
        {
           return json_encode(array("status" => "error", "msg" => "Invalid User. "));
        }
        
        if(preg_match(NOTNUM_REGX, $receiverArr['id'] ))
        {
           return json_encode(array("status" => "error", "msg" => "Invalid Receiver. "));
        }
        
        if(preg_match(NOTNUM_REGX, $giverArr['clientType'] ))
        {
           return json_encode(array("status" => "error", "msg" => "please enter valid client type. "));
        }
       
        if(preg_match(NOTNUM_REGX, $giverArr['tarrifId'] ))
        {
           return json_encode(array("status" => "error", "msg" => "please enter valid tarrif id. "));
        }
        
        if(preg_match(NOTNUM_REGX, $giverArr['resellerId'] ))
        {
           return json_encode(array("status" => "error", "msg" => "please select a valid reseller id. "));
        }
        
        if(preg_match(NOTNUM_REGX, $giverArr['currencyId'] ))
        {
           return json_encode(array("status" => "error", "msg" => "please select a valid currency id. "));
        }

        //echo $giverArr['amount'];
        if(preg_match(NOTALPHANUM_REGX, $giverArr['amount'] ))
        {
           return json_encode(array("status" => "error", "msg" => "please select a valid amount. "));
        }
        
        if(preg_match(NOTUSERNAME_REGX, $giverArr['userName'] ))
        {
           return json_encode(array("status" => "error", "msg" => "please select a valid username. "));
        }
        
        if(preg_match(NOTPASSWORD_REGX, $giverArr['password'] ))
        {
           return json_encode(array("status" => "error", "msg" => "please select a valid password. "));
        }
        
        
        return 1;
    }
    
    #- This function is to get details from user login function.
    function getUserLoginDetails($userId , $fieldKey = 0)
    {
        if(preg_match(NOTNUM_REGX, $userId))
        {
           return 0;
        }
        
        if(empty($fieldKey))
        {
            $fieldKey = '*';
        }
        
        $condition= 'userId='.$userId;
        
        $userInfo = $this->selectData( $fieldKey ,'91_userLogin',$condition); //
        
        return $userInfo;
    }
    
    function transferFund( $giverArr , $receiverArr )
    {
        #- validations to check all  input parameters.
        $response = $this->checkValidationArr(  $giverArr , $receiverArr );
        
        if($response != 1)
        {
            $response = get_object_vars(json_decode($response)) ;
            return json_encode(array("status" => $response['status'], "msg" => $response['msg'] ));
        }
        
        #- User can not transfer fund him self.    
        if($giverArr['id'] == $receiverArr['id'] )
        {
            return json_encode(array("status" => "error", "msg" => "You can not transfer fund to yourself. "));
        }

        #- function to check login.
        $result = $this->checkLogin($giverArr['id'] ,  $giverArr['password'] , 2);
   
     
        if ( !$result || $result->num_rows < 1  ) 
        {
            
            return json_encode(array("status" => "error", "msg" => "Please Enter Valid Password "));
        }
        
        #- getting details of receiver.
        $result = $this->getUserLoginDetails($receiverArr['id']);

        if ($result || $result->num_rows > 0 ) 
        {
            $receiverInfo = $result->fetch_array(MYSQLI_ASSOC);
            
            if(empty($receiverInfo))
            {
                return json_encode(array("status" => "error", "msg" => "Receiver details not found. Please Try Again. "));
            }
            
        }
        else
        {
             return json_encode(array("status" => "error", "msg" => "Please Enter Valid user details"));
        }
       
        
        $receriverBalInfo = $this->getUserBalanceInfo($receiverArr['id']);

        if(empty($receriverBalInfo))
        {
            return json_encode(array("status" => "error", "msg" => "Inavalid Input please try again. "));
        }
        
        #- only users are allowed to transfer fund.
        if($giverArr['clientType'] != 3 || $receiverInfo['type'] != 3 )
        {
            return json_encode(array("status" => "error", "msg" => "Only users can transfer fund via this feature. !")); 
        }

        #- This feature is allowed for users with reseller id 2 only.
       if($giverArr['resellerId'] != 2 || $receriverBalInfo['resellerId'] != 2)
       {
         
          return json_encode(array("status" => "error", "msg" => "Sorry you are not allowed to do this. !"));  
       }
       
       #- User can transfer fund with only 7 , 8 , 9, 84 tarrif ids.
       $validTarrifId = array(7,8,9,84);
       if(!in_array($giverArr['tarrifId'], $validTarrifId) || !in_array($receriverBalInfo['tariffId'], $validTarrifId) )
       {
           return json_encode(array("status" => "error", "msg" => "Sorry you are not allowed to do this. !"));  
       }
       
        #- Getting rates 
        $receiverCurrency = $this->getOutputCurrency($receriverBalInfo['tariffId']);

        $receiverCurrency = $this->getCurrencyViaApc($receiverCurrency,1);

        $giverCurrency = $this->getOutputCurrency($giverArr['tarrifId']);
        $giverCurrency = $this->getCurrencyViaApc($giverCurrency,1); 

       
        if(empty($giverCurrency) || empty($receiverCurrency) )
        {
            return json_encode(array("status" => "error", "msg" => "Inavalid Input please try again. "));
        }
        
        $receiverAmount = $this->currencyConvert($giverCurrency , $receiverCurrency , $giverArr['amount'] );
       
        if(empty($receiverAmount))
        {
             return json_encode(array("status" => "error", "msg" => "Inavalid Input please try again. "));
        }
        
        $receiverAmount = number_format($receiverAmount , 2);
       
       $giverBalInfo = $this->getUserBalanceInfo($giverArr['id']);
       
       if(empty($giverBalInfo))
       {
           return json_encode(array("status" => "error", "msg" => "Inavalid Input please try again. "));
       }
       
       #- user can transfer fund if he has sufficien balance.
       if($giverBalInfo['balance'] >= $giverArr['amount'])
       {
           
            $giverCurrntbal = $giverBalInfo['balance'] - $giverArr['amount'] ;
            $receiverCurrntbal = $receriverBalInfo['balance'] + $receiverAmount;
           
            include_once(CLASS_DIR."transaction_class.php");
            $transObj = new transaction_class();
           
            $response =  $transObj->updateUserBalance( $giverArr['id'], $giverArr['amount'] ,  '-');
            
            if(!$response)
                trigger_error ("error while updating balance in fund transfer.");
            
            $response =  $transObj->updateUserBalance( $receiverArr['id'] , $receiverAmount,  '+'); 
           
            if(!$response)
                trigger_error ("error while updating balance in fund transfer.");
            
             $transObj->fromUser = $receiverArr['id'];
             $transObj->toUser = $giverArr['id']; 
            
          //print_r();
          
            $paymentType = $receiverInfo['userName'];  
            $description = "Fund transfer to ".$paymentType;
            
            $response = $transObj->addTransactional_sub($giverArr['amount'] ,$giverCurrntbal,$paymentType,0,0,0,$description );
            
            if(!$response)
                trigger_error ("error while updating Transaction Log in fund transfer.");
            
            $transObj->fromUser = $giverArr['id'];
            $transObj->toUser = $receiverArr['id'];
            
            $paymentType = $giverArr['userName'];
            $description = "Fund transfer by ".$paymentType;
            
            $response = $transObj->addTransactional_sub( $receiverAmount ,$receiverCurrntbal,$paymentType,0,0,0,$description );
            
            if(!$response)
                trigger_error ("error while updating Transaction Log in fund transfer.");
            
            
            return json_encode(array("status" => "success", "msg" => "Fund Successfully Transferred!! "));
             
       }
       else
       {
            return json_encode(array("status" => "error", "msg" => "You have insufficient balance. "));
       }     
    }
    
}

$funobj = new fun(); //class object
?>
