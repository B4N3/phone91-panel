<?php
/*
 * @author rahul <rahul@hostnsoft.com>
 * @package Phone91
 * class use for containt all general function  
 * 
 */
include_once 'SuperMySQLi.php';

class fun extends SuperMySQLi {
    var  $errorHandler;
    
    public function __construct() {
//        $this->db = new SuperMySQLi('localhost', 'voipswitchuser', '+4H8ZXcSyWn7CuX*', 'voipswitch');
        $this->db = new SuperMySQLi('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8', 'voip91_switch');
    }

    #function use for clear browser cache
    function clearBrowserCache() {
        header("Pragma: no-cache");
        header("Cache: no-cache");
        header("Cache-Control: no-cache, must-revalidate");
    }

    #function use for connect databse 
    function db_connect() {
        //$con = mysql_connect("216.245.201.194","voipswitchuser",'+4H8ZXcSyWn7CuX*') or die(" Couldnot connect to the server ");
        $con = mysql_connect("localhost", "voip91_switch", 'yHqbaw4zRWrUWtp8') or die("Couldnot connect to the server" . mysql_error());
        mysql_select_db("voip91_switch", $con) or die(" Database Not Found ");
        return $con;
    }

    function connecti() {
        $con = mysqli_connect('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8', 'voip91_switch') or die("Couldnot connect to the server" . mysqli_connect_error());
        /* check connection */

        if (!$con)
            die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());

        // echo 'Success... ' . mysqli_get_host_info($con) . "\n";
        return $con;
    }

    #function use to check user login or not 
    function login_validate() {
        if (isset($_SESSION['id']) && strlen($_SESSION['id']) > 0)
            return 1;
        else {
            $_SESSION['login_error'] = "Please proceed with username and password.";
            return 0;
        }
    }
    #general function for mysql to select data
    public function selectData($columns, $table, $condition = "1") {
        $this->db->select($columns)->from($table)->where($condition);
         $query = $this->db->getQuery();
        $result = $this->db->execute();
        if(!$result)
            $this->errorHandler = $query;
        return $result;
    }
    
    #function for get currency from apc
    public function getCurrencyViaApc($currency,$type) {
        /*@param : $type : 1 for name from id 
         *                 2 for id from name 
         */
        $apcArray = "";
        $apcArray = apc_fetch("currency");
        if(!is_array($apcArray) && $apcArray == "")
        {
            $result = $this->selectData('currencyId,currency', '91_currencyDesc');
            while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                $data[$row['currencyId']] = $row['currency'];
            }
            $apcStore = apc_store("currency", $data);
            $apcArray = $data;
        }
        if($type == 2)
        {
            $apcArray =array_flip($apcArray);
        }
        return $apcArray[$currency]; 
    }
    
    #function for get currency 
    public function getCurrency($currency_name) {
        $result = $this->selectData('currencyId', '91_currencyDesc', "currency = '" . trim($currency_name) . "'");
        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            return $row['currencyId'];
        }
    }

    #function for get currency name 
    public function getCurrencyName($currency_id) {
        $result = $this->selectData('currency', '91_currencyDesc', "currencyId = '" . trim($currency_id) . "'");
        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            return $row['currency'];
        }
    }
    

    public function deleteData($table, $condition = 1) {
        /* @desc : function is used to delete the data from the table
         *          This function will be tarns fered to the function layer
         */
        $this->db->delete($table)->where($condition);
        $delRes = $this->db->execute();
        if($delRes)
            return true;
        else
            return false;
    }
    
    #function use for insert data 
    public function insertData($data, $table) {
        
        $this->db->insert($table, $data);
        $result = $this->db->execute();
        if($result)
            return $result;
        else
            return 0;
    }

    #function use for updat data into database
    public function updateData($data, $table, $condition = 1) {
        $this->db->update($table, $data)->where($condition);
        return $this->db->execute();
    }
    
    #function use for get currency detail 
    function get_currency($id_tariff) {
        if ($id_tariff == 8) {
            $cid = 1;
        } else if ($id_tariff == 7) {
            $cid = 2;
        } else if ($id_tariff == 9) {
            $cid = 3;
        }

        $con = $this->connecti();
        $result = mysqli_query($con, "SELECT name FROM currency_names WHERE id='$cid'") or die('Query error');
        mysqli_close($con);
        $cur = mysqli_fetch_assoc($result);
        return $currency = $cur['name'];
    }

    //functoin to convert amount to particular currency
    function currencyConvert($from, $to, $amount) {
        $url = "https://voip91.com/currency/index.php?from=$from&to=$to&amount=$amount";  //nedd to change after 1500 request per month
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

//end of currency convert function

    function sql_safe_injection($s) {
        $con = $this->connecti();
        //check if get_magic_quotes is set or not
        if (get_magic_quotes_gpc())
        //remove slashes
            $s = stripslashes($s);
        //remove ' from input		
        return mysql_real_escape_string($s);
        mysql_close($con);
    }

//end sql_safe_injection() function
    #not in use 
    function delete_client($id, $type) {
        $con = $this->connect();
        if ($id == '') {
            echo "Invalid Id";
            $_SESSION['msg'] = "Invalid Id";
            exit();
        }
        $to = 'rahul@hostnsoft.com';
        $subject = 'Error while deleting client';
        $message = "Error in phone880 = ";
        if ($type != 2) {
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
            if ($result1 and $result2 and $result3 and $result4 and $result5) {
                $_SESSION['msg'] = 'Client Deleted Successfully';
                $response = 'Client Deleted Successfully';
            }
        } else {
            $sql = "select * from tariffreseller where id_reseller='" . $id . "' ";
            $res = mysql_query($sql);
            if (mysql_num_rows($res)) {
                while ($row = mysql_fetch_array($res)) {
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
            if (mysql_num_rows($resc)) {
                while ($row = mysql_fetch_array($resc)) {
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
            if ($result2 and $result7) {
                $_SESSION['msg'] = 'Client Deleted Successfully';
                $response = 'Client Deleted Successfully';
            }
        }
        mysql_close($con);
        return $response;
    }

//end delete_client() function
 
    
    #function use for set Session value in login time 
    function initiateSession($userid) {

        #get user detail and set session value 
        $table = '91_userLogin';
        $this->db->select('*')->from($table)->where("userId = '" . $userid . "' ");
        $result = $this->db->execute();
        if ($result->num_rows > 0) {
            $row = $result->fetch_array(MYSQL_ASSOC);
            extract($row);          //userId,userName,password,isBlocked,type      

            # set session username
            $_SESSION['username'] = $userName;
            # set session userid
            $_SESSION['id'] = $userId;
            $_SESSION['userid'] = $userId;
            
            $_SESSION['contact_no'] = '';
    //                     $_SESSION['id_tariff'] = $id_tariff;
            $_SESSION['client_type'] = $type;

            #set personal detail  
            $table = '91_personalInfo';
            $this->db->select('*')->from($table)->where("userId = '" . $userid . "' ");
            $result = $this->db->execute();
            // processing the query result
            if ($result->num_rows > 0) {
                $row = $result->fetch_array(MYSQL_ASSOC);
                //var_dump($row);
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
            $this->db->select('*')->from($table)->where("userId = '" . $userid . "' ");
            $result = $this->db->execute();
            // processing the query result
            if ($result->num_rows > 0) {
                $row = $result->fetch_array(MYSQL_ASSOC);
                extract($row);
                $_SESSION['id_tariff'] = $tariffId;
                $_SESSION['currencyId'] = $currencyId;
                $_SESSION['chainId'] = $chainId;
                $_SESSION['currencyName'] = $this->getCurrencyViaApc($currencyId,1);
            }


            //                $_SESSION['uty'] = $client_type;                       
        }
    }

    #get user email detail by email id 
    function getUserFromEmail($email) {
        $table = '91_verifiedEmails';
        $this->db->select('userid')->from($table)->where("email = '" . $email . "' ");
        $result = $this->db->execute();
        // processing the query result
        if ($result->num_rows > 0) {
            foreach ($result->fetch_array(MYSQL_ASSOC) as $row) {
                return $row;
            }
        }
        else
            return false;
    }

    #check user has verified email or not 
    function checkEmail($email) {
        $userid = $this->getUserFromEmail($email);
        if ($userid) {
            $this->initiateSession($userid);
            header("location: /userhome.php");
            exit();
        } else {
            return false;
            //mail("rahul@hostnsoft.com","checkfbLogin"," not exists ".json_encode($email));
            //$this->emailSignUp($email);
            //die();
        }
    }

    

    #created by rahul <rahul@hostnsoft.com>
    #modified by sudhir pandey(sudhir@hostnsoft.com)
    #modified date 17/07/2013
    #function use for get data of login user 

    function checkLogin($userid, $pwd) {

        # object of database connection
        $con = $this->connecti();
        $userid = $con->real_escape_string($userid);
        $pwd = $con->real_escape_string($pwd);
        # get all detail of login user like (isBlock status ,user deleted or not etc)
        $query = "SELECT userId,userName,password,isBlocked,deleteFlag,type FROM  91_userLogin WHERE userName='" . $userid . "' and password='" . $pwd . "'";
        $result = mysqli_query($con, $query);
        mysqli_close($con);
        return $result;

    }

    
  #function use for login user 	
  function login_user($userid, $pwd, $remember_me,$host = NULL,$signup = 0) {
      
        session_start();
        session_unset();
        session_destroy();
        session_start();

        $_SESSION['currentHost'] = $host;
        $uid = '';
        #check login failed time 
        $loginAttampt = $this->checkLoginFailed($userid);
        if (($loginAttampt > 10)) {			
               $_SESSION['error'] = "Maximum Number of request exceed.";    
               if($host != NULL){
                   header("location:".$host."/index.php");
                 }else
               header("location:index.php");
               exit();
       }

        $result = $this->checkLogin($userid, $pwd);
        $res = mysqli_num_rows($result);
        if ($res == '0') {
            $this->loginFailed($userid);
            $_SESSION['error'] = "Sorry Username and Password are not matched. Please Try with proper details.";
            
            if($host != NULL){
              header("location:".$host."/index.php");
            }else
            header("location:index.php");
        } else {
            $get_userinfo = mysqli_fetch_array($result);
            //print_r($get_userinfo);
            if ($get_userinfo["isBlocked"] != 1) {
                $_SESSION['error'] = "Account Blocked.";
                if($host != NULL){
                header("location:".$host."/index.php");
              }else
                header("location:index.php");
                exit();
            }
            
            if ($get_userinfo["deleteFlag"] > 0) {
                $_SESSION['error'] = "Account Deleted.";
                if($host != NULL){
                header("location:".$host."/index.php");
              }else
                header("location:index.php");
                exit();
            }

            #function call for assign value of session 
            $this->initiateSession($get_userinfo["userId"]);
           
            if($signup == 1){
             return TRUE; 
             exit();
             
            }
            
            #get the url which is set by the user at the llast logout             
            $redirectUrl = "";
            $redirectUrl = $this->getLandingPage($get_userinfo["userId"]);
//            var_dump($redirectUrl);
            if ($_SESSION['client_type'] == 1) {
                $_SESSION['isAdmin'] = 1;
                $redirectUrl = "admin/index.php#".$redirectUrl;
                header("location: ".$redirectUrl);
            } else {
                if($redirectUrl == "")
                    $redirectUrl = "userhome.php#!contact.php";
                else
                    $redirectUrl = "userhome.php#".$redirectUrl;
                 
                header("location: ".$redirectUrl);
               
            }
            if ($remember_me) {
                setcookie('usern', $userid, time() + 60 * 60 * 24 * 30);
                setcookie('passn', $pwd, time() + 60 * 60 * 24 * 30);
            } else {
                setcookie('usern', $userid, time() - 100);
                setcookie('passn', $pwd, time() - 100);
            }
        }
        mysql_close($con);
    }

    function changeUserLogin($childUserId,$session)
    {
        if($session['id'] == "" || preg_match('/[^0-9]+/', $session['id']))
                return false;
        if($childUserId == "" || preg_match('/[^0-9]+/', $childUserId))
                return false;
        $parentUserId = $session['id'];
        if($session['userType'] == 1)
            $url = "../admin/index.php";
        else
            $url = "../user/userhome.php";
        session_unset();
        session_destroy();
        session_start();
        $this->initiateSession($childUserId);
        $_SESSION['parentChidFlag'] = 1;
        $_SESSION['parentUserId'] = $parentUserId;
        header('Location:'.$url);
        
    }
    
    
    #function use for logout user and session destroy ..
    function logout() {
        session_start();
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['msg'] = "Successfully logged out. Thankyou for using our service";
        header('Location: index.php');
    }

    #check use is admin or not 
    function is_admin() {   //written by sapna
        if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
            return true;
        }
        $dbh = $this->connect_db();
        $sql = "select admin_id from 91_adminUse where admin_id='" . $_SESSION['id'] . "'";
        $result = mysql_query($sql, $dbh);
        mysql_close($dbh);
        if (!$result)
            die("Unable To Fetch User Data");
        if (mysql_num_rows($result) > 0) {
            $_SESSION['isAdmin'] = 1;
            return true;
        }
        else
            return false;
    }

    #function use for check user is admin or not by use of is_admin function 
    function check_admin() {
        $admin = $this->is_admin();
        if ((!isset($_SESSION['id'])) || !$admin) {
            return false;
        }
        else
            return true;
    }
    
    #function use to check login user is reseller or not 
    function check_reseller() {
        if (!isset($_SESSION['id']))
            return false;
        if ($_SESSION['client_type'] != 2 && $_SESSION['client_type'] != 1 && $_SESSION['client_type'] != 4)
            return false;
        else
            return true;
    }

    #function use to check login user id user or not 
    function check_user() {
        if (!isset($_SESSION['id']))
            return false;
        if ($_SESSION['client_type'] != 3)
            return false;
        else
            return true;
    }

    #function use to redirect url
    function redirect($url, $permanent = false, $statusCode = 303) {
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === "XMLHttpRequest") {
            echo "<script>window.top.location.href='$url'</script>";
        } else {
            if (!headers_sent()) {
                header('location: ' . $url, $permanent, $statusCode);
            } else {
                echo "<script>location.href='$url'</script>";
            }
            exit(0);
        }
    }

  #function for generate password it's a randum number   
  function generatePassword() {
        #password length
        $length = 4;
        $password = "";
        $possible = "0123456789";
        $i = 0;
        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }

    #function use to send sms to usd 
    function SendSMSUSD($tempparam) {
        $connect_url = "http://world.msg91.com/sendhttp.php"; // 
        $param["user"] = "phone91"; // 
        $param["password"] = "Phone91Int"; // beep7 password
        if (isset($tempparam['sender']) && strlen($tempparam['sender']) > 0)
            $param["sender"] = $tempparam['sender']; //
        else
            $param["sender"] = "919893385095";
        //$param["ISFlash"]="0";
        $param["mobiles"] = $tempparam['mobiles'];
        $param["message"] = $tempparam['message'];
       
        $request = '';
        foreach ($param as $key => $val) {
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

    //SEND SMS FOR USD
    function SendSMSUSDold($param) {
        $connect_url = "https://203.142.18.146:8080/server/sendsms/"; // 
        $param["login"] = "callplz1"; // 	
        $param["password"] = "qazwsxedc"; // 
        $param["clientid"] = "7PBq7PB8";
        $param["sender"] = "Phone91";
        $param["message_type"] = "TEXT";
        $param["receiver"] = $param[to];
        $param["message"] = $param[text];
        foreach ($param as $key => $val) {
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

    //function to send sms
    //Send SMSto 91
    function SendSMS91($tempparam) {
        
        $connect_url = "http://india.msg91.com/sendhttp.php"; // Do not change
        //set parameters to send
        $param["user"] = "phone91"; // 
        $param["password"] = "Phone91-Passw0rd"; // 
        if ($tempparam['sender'] != "")
            $param["sender"] = $tempparam['sender'];
        else
            $param["sender"] = "Phonee";
        $param["mobiles"] = $tempparam['mobiles'];
        $param["message"] = $tempparam['message'];
        $request = '';
        //set request parameter
        foreach ($param as $key => $val) {
            $request.= $key . "=" . urlencode($val);
            $request.= "&";
        }

        //remove last '&' character
        $requestComplete = substr($request, 0, strlen($request) - 1);
        //prepare url for initialize
        $preUrl = $connect_url . "?" . $requestComplete;

        $ch = curl_init($preUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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

    #function use to check rate according to user plan 
    function check_rate($code) {
        $prefix = $this->sql_safe_injection($code);
        //echo "select login from clientsshared where login='$username'";
        $dbh = $this->connect();
        $search_qry = "select description,voice_rate,prefix from tariffs where prefix like '" . $prefix . "%'  and id_tariff='8'";
        //	$search_qry="select login from clientsshared where login like '".$q."%'";
        $exe_qry = mysql_query($search_qry) or die(mysql_error());
        if (mysql_num_rows($exe_qry) > 0) {
            while ($res = mysql_fetch_assoc($exe_qry)) {
                $rate = $res['voice_rate'];
                $country = $res['description'] . ' ' . $res['prefix'];
                echo '<tr>                        
            <td>' . $country . '</td>
			<td>' . $rate . ' USD</td>            
            </tr>
            ';
                //return "";			
            }
        } else {
            echo "0";
        }
        mysql_close($dbh);
    }

    #function to verify confirmation code
    #function return 1 if code exist in any table and return 0 if not exist in both table contact and tempcontact
    #author "ankit" <ankitpatidar@hostnsoft.com> on 4/3/2013

    function verifyCode($code) {
        
        if(!is_numeric($code))
            return json_encode(array("msg"=>"Invalid confirm code","status"=>"error"));
        $count = 0;
        //query to check code exist in contact table
        $result = $this->selectData("userId","91_verifiedNumbers","confirmCode=".$code);
        if($result)
        {
            $count = $result->num_rows;
            $resultArr = $result->fetch_array(MYSQLI_ASSOC);
            $userid = $resultArr["userId"];
        }
        //check if row exist or not
        if ($count != 0) {
            $flag = $userid;
        } else { //check in tempcontact table code exist or not
            
            $resultTemp = $this->selectData("userId","91_tempNumbers","confirmCode=".$code);
            if($resultTemp)
            $countTemp = $resultTemp->num_rows;
            $resultArrTemp = $resultTemp->fetch_array(MYSQLI_ASSOC);
            $userid = $resultArrTemp["userId"];
            //set value of flag according to countTemp value
            if ($countTemp != 0) {
                $flag = $userid;
            }
            else
                $flag = 0;
        }
        return $flag;
    }

//end of verifyCode function
    #function to send confirmation code via sms or call

    function forget_password($username, $smsCall) {
        /*
         * @para $username: it may be username or mobile number
         * @para $smsCall: it is clicked button text SMS or CALL
         * @last updated by "ankit" <ankitpatidar@hostnsoft.com> on 4/3/2013
         */
        //create connection to database
        $con = $this->db_connect();
        $uid = $username;
        
        
        //if uid numeric,
        if (is_numeric($uid)) {
            $contact_number = $username;
            $contactQ = "SELECT userId,verifiedNumber,countryCode FROM 91_verifiedNumbers WHERE CONCAT(countryCode,verifiedNumber) LIKE '%" . $contact_number . "%'";
            $result = mysql_query($contactQ) or die("Error");
            if (mysql_num_rows($result) > 0) {
                $confirm = 1;
                $get_userinfo = mysql_fetch_array($result);
            } else {
                $confirm = 0;
                $result = mysql_query("SELECT userId,tempNumber,countryCode FROM 91_tempNumbers WHERE CONCAT(countryCode,tempNumber) LIKE '" . $contact_number . "'") or die("Error ");
                $get_userinfo = mysql_fetch_array($result);
            }
            //get required value
            $uid = $get_userinfo['userId'];
        }//end of if for numeric uid

        if (strlen($username) > 1 || strlen($uid) > 1) {
           $sql = "select userId,userName from 91_userLogin where userName='$username' or userId='$uid'";
           
           $result = mysql_query($sql) or die("Error while processing");
            $res = mysql_num_rows($result);
            if ($res == 0) {
                $show_msg = "Sorry user with this username or id not found.";
                $status = "error";
            } else {
                $get_userinfo = mysql_fetch_array($result);
                $uid = $get_userinfo['userId'];

                $confirm_code = $this->generatePassword();
                //update code in tables
                mysql_query("UPDATE 91_verifiedNumbers SET confirmCode='$confirm_code' WHERE userId='" . $uid . "'");
                mysql_query("UPDATE 91_tempNumbers SET confirmCode='$confirm_code' WHERE userId='" . $uid . "'");

                $result = mysql_query("select verifiedNumber,countryCode from 91_verifiedNumbers where userId='" . $uid . "'") or die("Error " . mysql_error());
                if (mysql_num_rows($result) > 0) {
                    $get_userinfo = mysql_fetch_array($result);
                    $contact_no = $get_userinfo['verifiedNumber'];

                    if (strlen($contact_no) < 8) {
                        $temp_flag = 1;
                    }
                    $code = $get_userinfo['countryCode'];
                    $contact = $code . $contact_no;

                    //Assign Variables for sending sms to user
                    if ($smsCall == "SMS") {
                        $msg = "Enter this confirmation code " . $confirm_code . " to reset your password."; // sms text for usd					                                        
                        $d['sender'] = "Phone91";
                        $d['message'] = $msg;
                        $d['mobiles'] = $contact;
                        //Assign Variables for sending sms to 91 user
                        $nine['sender'] = "Phonee";
                        $nine['mobiles'] = $contact_no; // mobile number without 91
                        $nine['message'] = $msg;
                        //Call function
                        if ($code == "91")
                        {
                            $this->SendSMS91($nine);
                        }
                        else
                            $this->SendSMSUSD($d);
                    }
                    else if ($smsCall == "CALL") {
                        $this->mobile_verification_api($contact, $confirm_code);
                    }
                    $show_msg = "confirmation code has been sent to your mobile";
                    $status = "success";
                } else if (mysql_num_rows($result) == 0 || $temp_flag == 1) {//if username or number not found in contact table then search in tempcontact table 
                    $result = mysql_query("select tempNumber,countryCode from 91_tempNumbers where userId=" . $uid) or die("Error");
                    //if row found
                    if (mysql_num_rows($result) > 0) {
                        $get_userinfo = mysql_fetch_array($result);
                        $contact_no = $get_userinfo['tempNumber'];
                        $code = $get_userinfo['countryCode'];
                        $contact = $code . $contact_no;
                        //if SMS button clicked
                        if ($smsCall == "SMS") {
                            //Assign Variables for sending sms to user
                            $d['sender'] = "Phone91";
                            $d['message'] = "Enter this confirmation code " . $confirm_code . " to reset your password."; // sms text for usd
                            $d['mobiles'] = $contact;
                            //Assign Variables for sending sms to 91 user
                            $nine['sender'] = "Phonee";
                            $nine['mobiles'] = $contact_no; // mobile number without 91
                            $nine['message'] = "Enter this confirmation code " . $confirm_code . " to reset your password."; // sms text for usd
                            //Call function
                            if ($code == "91")
                                $this->SendSMS91($nine);
                            else
                                $this->SendSMSUSD($d);
                        }
                        else if ($smsCall == "CALL") {//if CALL button clicked
                            $this->mobile_verification_api($contact, $confirm_code);
                        }
                        $show_msg = "confirmation code has been sent to your mobile";
                        $status = "success";
                    }//end of if for rows of tempcontact table
                    else {
                        $show_msg = "This User ID does not exists";
                        $status = "error";
                    }
                }//end of else if for search username or number in tempcontact
            }//end of else (if username or uid found in clientsshared table)
        }//end of if
        mysql_close($con); //close db connection
        return json_encode(array("msg"=>$show_msg,"status"=>$status));
    }


    //modified by:Balachandra<balachandra@hostnsoft.com>
    //date: 29/072013
    function change_pwd($curr_pwd, $new_pwd) {
        #mysqli connection  
        $con = $this->connecti();

        #getting the session userid
        $userid = $_SESSION['userid'];

        #get the value of new password by post method 
//        $new_pwd = $_REQUEST['new_pwd'];

        #get the value of current password by post method 
//        $curr_pwd = $_REQUEST['curr_pwd'];

        #$table name of the table in database
        $table = '91_userLogin';
        
        
        #access password by database of the current user
        $qury = $this->db->select('password')->from($table)->where("userId = '" . $userid . "'");
        $this->db->getQuery();

        #execute the query
        $result = $this->db->execute($qury);

        #fetching the array element and putting in a varible $pwd
        $pwd = mysqli_fetch_array($result);

        #store the particular column data
        $pwd1 = $pwd['password'];

        #check curr_pwd is equal to database user password
        if ($pwd1 != $curr_pwd) {
            #echo "Please enter correct password";
            return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Correct Password'));
        } else {
            $new_pwd = $this->db->real_escape_string($new_pwd);
            
            #data to pass in update command that is new password
            $data = array("password" => $new_pwd);

            #update the table by new password corresponding to the userid
            $query = $this->db->update($table, $data)->where("userId = '" . $userid . "' ");

            #get the query sentence
            $this->db->getQuery($query);

            #execute the query
            $result1 = $this->db->execute($query);

            #if query executed then
            if ($result1) {
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
        mysqli_close($con);
    }


    #check email id is velid or not 
    function isValidEmail($email) {
        return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email);
    }

    
    #function use to send code for verify email id 
    #function use Mandrill
    function send_verification_mail($new_emailid, $pwd) {
        $pwd = base64_encode($pwd);
        //code to create mail content   
        $mailData = <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="background:#ddd">
<body style="background:#ddd; width:100%;">
<div style="width:625px; margin:0 auto;  background:#fff;color:#000">
<!---------------header-------------------->
<div class="wrap"><div style="height:8px;background-color:#00B0F0;"></div></div>
<div id="header" align="center">
	<div class="wrap bgw">
    	
        
        <div id="head2" class="black "><h2 style="font-size:120px; margin:0;padding:0;">Phone<span style="color:#00B0F0;">91</span></h2></div>
        
        <div id="hcont" style="padding:20px;">
        		<span style="font-size:16px; color:#555;">We are an International voice calling solutionsprovider. You are now connected with the Company thatsends quality Voice <strong style="color:#000;">on 150+ telecom operators.</strong></span>
         </div>
    </div>
</div>
<!----------------main container-------------------->
<div id="main">
        <div class="wrap">
        <div style="border-top:1px solid #00B0F0; border-bottom:1px solid #00B0F0; color:#fff; text-align:left;">
        		<div id="mlink" style="background-color:#00B0F0; padding:20px; margin:1px 0;">
            	<h2 style="margin:0;padding:0; font-size:24px;">Please confirm your Email ID by clicking the link given below.</h2>
                <div id="link"><a href="http://voip91.com/verify_email.php?email=$new_emailid&confirmationCode=$pwd" style="color:#000; font-size:16px;">Confirm</a></div>
				<div style="margin:0;padding:0; margin-top:20px;">Or use this confirmation code   <span style="color:#000;">$pwd</span>    at the site from you have signup.</div>
            </div>
        </div>
        </div>
</div>
<!----------------queries container-------------------->
<div id="queries">
		<div class="wrap">
        	<div id="quriBox" style="padding:20px;">
				<div id="payh1" class="marb10"><h2 class="mar0">For Support :</h2></div>
            		<div id="qcont">
                    	<span style="margin:0;padding:0; font-size:18px; color:#777;">For any queries, please contact on below details and one of our friendly staff will reply you very soon.</span>
                    </div>
                    <div id="qsupport">
                        <div class="emal f14"><span class="grayclr ebox">Gtalk IM</span> <span class="ecbox">: support@phone91.com</span></div>
                        <div class="emal f14"><span class="grayclr ebox">Email</span> : <a href="#" class="black"><span class="ecbox" style="text-decoration:underline;">support@phone91.com</span></a></div>
                    </div>
            </div>
        </div>
</div>
<!----------------payment container-------------------->
<div id="payment">
		<div class="wrap">
        <div id="payCon" style="padding:20px;">
        		<div id="payh1" class="marb10"><h2 class="mar0">For Payment :</h2></div>
                <div style="margin:0;padding:0; font-size:18px; color:#777;">Online payment</div>
                <div class="grayclr lh f14 marb10">Login to your account, Click on "Pay Online", fill your billing details, choose the payment type and a recharge amount, and Click suitable online payment option from Paypal, debit card (ATM), Credit card orMooneybookers(Skrill). After successful payment, your account will be recharged automatically.</div>
                <div><strong>*We suggest that you should use Google Chrome for browsing our website and making payment.</strong></div>
        </div>
        </div>
</div>
<!----------------team container-------------------->
<div id="team">
	<div class="wrap">
    	<div id="teambox" style="padding:20px;">
        	<div id="thead"><span class="bold f12 ">Regards,</span><br><strong><span class="bold">Phone<span class="bclr">91 </span>Team</span></strong></div>
            <div id="icon" style="padding:15px 0px;"><span class="f14 bold">For updates of our services,follow us on:</span>
			<br />
			<a href="https://www.facebook.com/phone91" class=" bold f14" style="color:#3B5998; margin-right:20px; font-weight:bold; font-size:18px; text-decoration:none;">Facebook</a>
			<a href="https://twitter.com/phone91" class=" bold f14" style="color:#37B9E3; text-decoration:none; font-weight:bold; font-size:18px;">twitter</a></div>
        </div>
    </div>
</div>
<!----------------Footer container-------------------->
<div id="fbox">
	<div class="wrap">
      <div style="padding-top:5px; background-color:#FFF;">
    	<div id="footer" style="padding:20px; background:#f5f5f5;">
      
                    <div class="privacy marb10">
                        <h2>Privacy Statement</h2>
    <span class="f14 grayclr lh">We are happy to have you on our list, and since we want to keep you all to ourselves, we never share your Email address with anyone.</span>
                    </div>
                    
                    <div class="privacy marb10">
                        <h4 class="mar0 marb10 f14">Manage Your Subscription</h4>
    <span class="f14 grayclr lh">You are subscribed to <a href="http://voip91.com/">phone91.com</a> with the email address: $new_emailid</span>
                    </div>
                    
                    <div class="privacy">
                        <h4 class="mar0 marb10 f14">Unsubscribe or change your subscription</h4>
                    </div>
                    
                    <div id="copy"  style="margin-top:20px;">
                        <span class="f12">Copyright © 2013 <a href="http://voip91.com/" class="f14">phone91.com </a>, All rights reserved.</span>
                    </div>
          </div>
        </div>
    </div>
</div>
<div class="wrap"><div style="height:8px;background-color:#00B0F0;"></div></div>
</div>
</body>
</html>
EOF;
        //set api key and parameters
        require('Mandrill.php');
        Mandrill::setApiKey('zjlmyNcktAB5pnXO5TPdxg');
        $request_json["type"] = "messages";
        $request_json["call"] = "send";
        $req["html"] = $mailData;
        $req["subject"] = "verify user email";
        $req["from_email"] = "support@phone91.com";
        $req["from_name"] = "Phone91";
        $resTo["email"] = $new_emailid;
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


    function user_feedback($sub, $dis) {//rahulchordiya@gmail.com,
        $email = 'rahul@hostnsoft.com';
        $subject = $sub . " Feedback Phone91.com ";
        $message = "A <b>user " . $_SESSION['username'] . "</b> Send Feedback to admin of chapter91.com<br /><br /> Detail feedback of user is as follows:<br /><br />" . $dis;
        if (strlen($_SESSION['contact_no']) > 8) {//if user mobile number is confirm with chapter91
            $message .="<br /> and user mobile number is " . $_SESSION['contact_no'];
        }
        $header = 'MIME-Version: 1.0' . "\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
        if (strlen($_SESSION['email']) > 8) { //if user have his or her email then mail send by user email
            $header .= 'From: ' . $_SESSION['email'] . "\n";
        } else {
            $header .= 'To: Feedback@Phone91.com <autoreply@phone91.com>' . "\n";
        }
        mail($email, $subject, $message, $header);
        return "Your Feedback is submited Successfully thankyou for your precious time.";
    }

    function mobile_verification_api($mobile_no, $vcode) {
        $connect_url = 'https://voip91.com/phone91_verification/mobile_verify_api.php'; // Do not change
        $param["mobile_no"] = $mobile_no; //
        $param["vcode"] = $vcode; //
        $request = "";
        foreach ($param as $key => $val) {
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

    function mobile_verification_response($uid) {
        $connect_url = 'https://voip91.com/phone91_verification/mobile_verify_response.php'; // Do not change
        $param["uid"] = $uid; //
        foreach ($param as $key => $val) {
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


    function getUserIP() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !is_null($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function checkLoginFailed($userName) {
        $table = '91_loginFailed';
        $this->db->select('username')->from($table)->where("username='" . $userName . "' and date > DATE_SUB(now(), INTERVAL 4 MINUTE) ");
        $result = $this->db->execute();
        // processing the query result
        if ($result->num_rows > 0) {
            return $result->num_rows;
//		    foreach ($result->fetch_array(MYSQL_ASSOC) as $row) {
//			return  $row[0];
//		    }
        }
        else
            return 0;
    }

    function loginFailed($userName) {
        $data = array("username" => $userName, "ip" => $this->getUserIP());
        $table = '91_loginFailed';
        $this->db->insert($table, $data);
        $insertQry = ($this->db->getQuery());
        $insertresult = $this->db->execute();
    }

    

    //function to get random number
    function randomNumber($length) {
        $space = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        $spaceLen = strlen($space);
        for ($i = 0; $i < $length; $i++) {
            $str .= $space[mt_rand(0, $spaceLen - 1)];
        }

        return $str;
    }

    //function to update balance
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
     * @modified by sameer rathod 18-10-2013
     * @param integer $teriffId 
     * @description functoin to get recharge and talktime to show 
     */
    function getRechargeTalktime($terrifId) {
        $result = $this->selectData('recharge,talktime', 'rechargeByTerriff','terriffId='.$terrifId);
        return $result;
    }

//end of function getRechargeTalktime
    //function to get checksum
    function getchecksum($MerchantId, $Amount, $OrderId, $URL, $WorkingKey) {
        $str = "$MerchantId|$OrderId|$Amount|$URL|$WorkingKey";
        $adler = 1;
        $adler = $this->adler32($adler, $str);
        return $adler;
    }

    //function to verify checksum
    function verifychecksum($MerchantId, $OrderId, $Amount, $AuthDesc, $CheckSum, $WorkingKey) {
        $str = "$MerchantId|$OrderId|$Amount|$AuthDesc|$WorkingKey";
        $adler = 1;
        $adler = $this->adler32($adler, $str);

        if ($adler == $CheckSum)
            return "true";
        else
            return "false";
    }

    //
    function adler32($adler, $str) {
        $BASE = 65521;

        $s1 = $adler & 0xffff;
        $s2 = ($adler >> 16) & 0xffff;
        for ($i = 0; $i < strlen($str); $i++) {
            $s1 = ($s1 + ord($str[$i])) % $BASE;
            $s2 = ($s2 + $s1) % $BASE;
            //echo "s1 : $s1 <BR> s2 : $s2 <BR>";
        }
        return $this->leftshift($s2, 16) + $s1;
    }

    function leftshift($str, $num) {

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

    function cdec($num) {
        $dec = '';
        for ($n = 0; $n < strlen($num); $n++) {
            $temp = $num[$n];
            $dec = $dec + $temp * pow(2, strlen($num) - $n - 1);
        }

        return $dec;
    }

#find country name 

    
    
    function countryArray() {
        $url = "http://voip92.com/isoData.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $string1 = json_decode($data, true);
        for ($i = 0; $i < count($string1); $i++) {
            $country[$string1[$i]['CountryCode']] = $string1[$i]['Country'];
        }
        return $country;
    }

    function currencyArray() {//Only For net core 
        if ($row = apc_fetch('currencyArray')) {
            return $row;
        } else {

            $sql = "select currencyId,currency from 91_currencyDesc";

            $result = $this->db->query($sql);
//                var_dump($result);

            if ($result->num_rows > 0) {

                while ($rowData = $result->fetch_array(MYSQL_ASSOC)) {
//                                    var_dump($rowData);	
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
    #creation date 09/08/2013
    #function use for check user has confirm mobile no or not if yes then go to userhome page otherwise confirm mobile no page 

    function getConfirmNumber($userid) {

        //Code To redirect user to phone setting page if user do not have any confirmed mobile number
        include_once(CLASS_DIR . 'contact_class.php');

        #get all contact detail 
        $contactObj = new contact_class();

        #find verified contact number
        $vContactArr = $contactObj->getConfirmMobile($userid);
        return $vContactArr[0];
    }
    
    
     #created by sudhir pandey (sudhir@hostnsoft.com)
     #creation date 08/08/2013
     #function use for get last chainId    
     function getlastChainId($reseller_id){
      #insert login detail into login table database 
      $loginTable = '91_userBalance';
      
      #get chain id for user 
      $this->db->select('*')->from($loginTable)->where("resellerId = '" .$reseller_id. "' ORDER BY userId DESC limit 1 ");
      $this->db->getQuery();
      $result = $this->db->execute();
      if ($result->num_rows > 0) {
       $row = $result->fetch_array(MYSQL_ASSOC);
       $chainId = $row['chainId'];
      }else{
       $resellerChainId = $this->getUserChainId($reseller_id);  
       $chainId = $resellerChainId."1111";
      }
      
      return $chainId;
     }   
     
     
     #created by rahul sir
     #creation date 08-08-2013
     function generateId($a){
    
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
     
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 09/08/2013
    #function use for create new chain id by use of generateId function 
    function newChainId($lastChainId){
         
         #last chain id first part 
         $firstpart = substr($lastChainId,0,-4);
         #last chain id second part (currentuser chain id).
         $secondpart = substr($lastChainId,-4);
         
         #increment last chain id by generateId function 
         $incId = $this->generateId($secondpart);
         if($incId =='' || $incId == $secondpart){
           $this->sendErrorMail("rahul@hostnsoft.com", "Chain Id creation problem (either chain id is blank or same as last chain id).");
         }
         
         
         #new chain id
         $newChainId = $firstpart.$incId;
         return $newChainId;
         
    }
    
    function sendErrorMail($email, $mailData) {
        include_once(CLASS_DIR . 'awsSesMailClass.php');
        $sesObj = new awsSesMail();
        $from = "support@phone91.com";
        $subject = "Phone91 Error Report";
        $to = $email;
        $message = $mailData;
        $response = $sesObj->mailAwsSes($to, $subject, $message, $from);
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 27/08/2013
    #function use for get reseller chain id  
    function getResellerChainId($userid){
         
      $loginTable = '91_userBalance';
      
      #get chain id for user 
      $this->db->select('*')->from($loginTable)->where("userId = '" .$userid. "'");
      $this->db->getQuery();
      $result = $this->db->execute();
      if($result)
      {
        $row = $result->fetch_array(MYSQL_ASSOC);
        $chainId = $row['chainId'];

        #reseller chain id first part 
        $resellerChainId = substr($chainId,0,-4);

        return $resellerChainId;
      }
      else
          return false;
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 27/08/2013
    #function use for get user chain id  
    function getUserChainId($userid){
         
      $loginTable = '91_userBalance';
      
      #get chain id for user 
      $this->db->select('*')->from($loginTable)->where("userId = '" .$userid. "'");
      
      $result = $this->db->execute();
      if($result)
      {
        $row = $result->fetch_array(MYSQL_ASSOC);
        $chainId = $row['chainId'];
        return $chainId;
      }
      else
          return false;
         
    }
    
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 27/08/2013
    #function use for get reseller id  
    function getResellerId($userid){
         
      $loginTable = '91_userBalance';
      
      #get reseller id for user 
      $this->db->select('*')->from($loginTable)->where("userId = '" .$userid. "'");
      
      $result = $this->db->execute();
      if($result)
      {
        $row = $result->fetch_array(MYSQL_ASSOC);
        $resellerId = $row['resellerId'];
        return $resellerId;
      }
      else
          return false;
         
    }
    function insertLandingPage($url,$userId)
    {
        $table = "91_userLandingPage";
        $url = $this->db->real_escape_string($url);
        $userId = $this->db->real_escape_string($userId);
        $res = $this->db->query("INSERT INTO ".$table." (userId,url) values ('".$userId."','".$url."') ON DUPLICATE KEY UPDATE url = '".$url."'");
        if($res)
            return true;
        else
            return false;
        
    }
    function getLandingPage($userId)
    {
        $table = "91_userLandingPage";
        $result = $this->selectData("url", $table, "userId=".$userId);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return $row['url'];
    }
    
    
    public function getOutputCurrency($tariffId) {
        #GET OUTPUT CURRENCY OF PLAN FOR CURRENCY CONVERSION
        if(is_null($tariffId))
            return 0;
        $condition = "tariffId =" . trim($tariffId);
        $curRes = $this->selectData("outputCurrency", '91_plan', $condition);
        if ($curRes) {
            $curResRow = $curRes->fetch_array(MYSQL_ASSOC);
            return $curResRow['outputCurrency'];
        }
        else
            return 0;
    }

    
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 23/10/2013
     * @detail function use to make log of account manager (save all log into 91_adminlog)
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
        #insert query (insert data into 91_personalInfo table )
        $this->db->insert($logTable, $data);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        #check data inserted or not 
        if (!$result) {
             return json_encode(array("status" => "error", "msg" => "add log detail ! "));
        }
        
        
    }

}

$funobj = new fun(); //class object
?>