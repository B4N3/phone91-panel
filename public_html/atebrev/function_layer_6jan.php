<?php
/*
 * @author rahul <rahul@hostnsoft.com>
 * @package Phone91
 * class use for containt all general function  
 * 
 */
include_once 'SuperMySQLi.php';

class fun extends SuperMySQLi 
{
    var  $errorHandler;
    var  $lastInsertId;
    var  $querry;
    
    public function __construct() 
    {
//        $this->db = new SuperMySQLi('localhost', 'voipswitchuser', '+4H8ZXcSyWn7CuX*', 'voipswitch');
        $this->db = new SuperMySQLi('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8', 'voip91_switch');
    }
    
    function __destruct()
    {
        unset($this->db);
    }

    
    
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
        $con = mysql_connect("localhost", "voip91_switch", 'yHqbaw4zRWrUWtp8') or die("Couldnot connect to the server" . mysql_error());
        mysql_select_db("voip91_switch", $con) or die(" Database Not Found ");
        return $con;
    }

    function connecti() 
    {
        $con = mysqli_connect('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8', 'voip91_switch') or die("Couldnot connect to the server" . mysqli_connect_error());
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
            if($_SESSION['loginFlag'] == 2)
                return 1;
            
            if(isset($_SESSION['domain']))
            {
                header("location:"."http://".$_SESSION['domain']."/signup-step.php");
            }else
                return 1;
            //header("location: /beforeLogin.php");
            exit();
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
                
                if(isset($_SESSION['domain']))
                {
                    header("location:"."http://".$_SESSION['domain']."/signup-step.php");
                }else
                    return 1;
                    //header("location: /beforeLogin.php");
                exit();
            }
            else
                return 0;
         }
         
        
    }
    #function use to check user login or not 
    function login_validate() 
    {
        
        
        if (isset($_SESSION['id']) && strlen($_SESSION['id']) > 0)
        {
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
     * @method general function for mysql to select data
     * @param string $columns
     * @param string $table
     * @param string $condition
     * @return mysqli resource
     */
    public function selectData($columns, $table, $condition = "1") 
    {
        $this->db->select($columns)->from($table)->where($condition);
        $query = $this->db->getQuery();
        
       
        
        $this->querry = $query;
        $result = $this->db->execute();
        
        if(!$result)
        {
            //log errors
            trigger_error('problem while get values from '.$table.' query:'.$query.'backTrace:'.json_encode(debug_backtrace()));
            $this->errorHandler = $query;
        }
        return $result;
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
        if(!is_array($apcArray) && $apcArray == "")
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
     * @method use to delete the data from the table,This function will be tarns fered to the function layer
     * @param string $table
     * @param string $condition
     * @return boolean
     */
    public function deleteData($table, $condition = 1) 
    {
        
        $this->db->delete($table)->where($condition);
        $query = $this->db->getQuery(); 
        $this->querry = $query;
        $delRes = $this->db->execute();
        if($delRes)
            return true;
        else
        {
            trigger_error('Problem while delete from '.$table.' query:'.$query.' backTrace:'.  debug_backtrace());
            return false; 
        }
   }
    
     
    /**
     * @author 
     * @uses for insert data
     * @param array $data
     * @param string $table
     * @return int | mysqli resource
     */
    public function insertData($data, $table) 
    {
        
        $this->db->insert($table, $data);
        $query = $this->db->getQuery();
        $this->querry = $query; 
        $result = $this->db->execute();
        if($result)
        {
            $this->lastInsertId = $this->db->insert_id;
            return $result;
        }
        else
        {
            //log errors
            trigger_error('problem while insert data in '.$table.' query:'.$query.' back trace'.  json_encode(debug_backtrace()));
            return 0;
        }
   }

    
   /**
    * @method use for updat data into database
    * @param array $data
    * @param string $table
    * @param string $condition
    * @return int
    */
    public function updateData($data, $table, $condition = 1) 
    {
        $this->db->update($table, $data)->where($condition);
        $query = $this->db->getQuery();
        $this->querry = $query;
        $result = $this->db->execute(); 
        
        //log errors
        if(!$result)
        {
            trigger_error('problem while update '.$table.' query:'.$query.' backTrace:'.  debug_backtrace());
        
            return 0;
        }
        return $result;
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
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @uses function to convert amount to particular currency 
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return float
     */
    function currencyConvert($from, $to, $amount) 
    {
        $url = "https://voip91.com/currency/index.php?from=$from&to=$to&amount=$amount";  //nedd to change after 1500 request per month
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
        $result = $this->selectData('*',$table,"userId = '" . $userid . "' ");
        
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            extract($row);          //userId,userName,password,isBlocked,type      

            # set session username
            $_SESSION['username'] = $userName;
            # set session userid
            $_SESSION['id'] = $userId;
            $_SESSION['userid'] = $userId;
            
            $_SESSION['contact_no'] = '';
    
            $_SESSION['client_type'] = $type;

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
            }

        }
    }

    
    /**
     * @uses to get user email detail by email id 
     * @param string $email
     * @return boolean | array
     */
    function getUserFromEmail($email) 
    {
        $table = '91_verifiedEmails';
        $result =  $this->selectData('userid',$table,"email = '" . $email . "' ");
        
        // processing the query result
        if ($result->num_rows > 0) 
        {
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
    function checkEmail($email) 
    {
        $userid = $this->getUserFromEmail($email);
        
//        echo ' user '.$userid;
        
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
     * last updated by sudhir pandey <sudhir@hostnsoft.com> 
     */
    function checkLogin($userName, $pwd) 
    {

        if(preg_match('/[^a-zA-Z0-9\.\_\@]+/', $userName) ||$userName== "")
        {
            return 0;
        }
        $userName = $this->db->real_escape_string($userName);
        $pwd = $this->db->real_escape_string($pwd);
        # get all detail of login user like (isBlock status ,user deleted or not etc)
        $result = $this->selectData('userId,userName,password,isBlocked,deleteFlag,type,resellerId','91_manageClient',"userName='" . $userName . "' and password='" . $pwd . "'");
//       echo $this->querry;
        return $result;

    }

    
  
    /**
     * @uses to login user 	 
     * @param int $userid
     * @param string $pwd
     * @param type $remember_me
     * @param string $host
     * @param int $signup
     * @return boolean
     */
  function login_user($userId, $pwd, $remember_me,$host = NULL,$signUp = 0) 
  {
      
        session_start();
        session_unset();
        session_destroy();
        session_start();

        //set current host
        $_SESSION['currentHost'] = $host;
        $uid = '';
        
        #check login failed time 
        $loginAttampt = $this->checkLoginFailed($userId);
        if (($loginAttampt > 10)) 
        {			
               $_SESSION['error'] = "Maximum Number of request exceed.";    
               if($host != NULL)
               {
                   header("location:"."http://".$host."/sign-in.php?error=".$_SESSION['error']);
               }
               else
               header("location:index.php");
               exit();
        }

        //call function to check login
        $result = $this->checkLogin($userId, $pwd);
        
        trigger_error('user details:'.$userId.' pass:'.$pwd.' num:'.$result->num_rows);
        
        if(!$result)
        {
            $_SESSION['error'] = "Invalid User please try again with valid user.";   
            if($host != NULL)
            {
                header("location:"."http://".$host."/sign-in.php?error=".$_SESSION['error']);
            }
            else
                header("location:index.php");
            exit();
        }
        
        //get num rows
        $res = $result->num_rows;
        if ($res == '0') 
        {
            $this->loginFailed($userId);
            $_SESSION['error'] = "Sorry Username and Password are not matched. Please Try with proper details.";
            
            if($host != NULL)
            {
                header("location:"."http://".$host."/sign-in.php?error=".$_SESSION['error']);
            }
            else
                header("location:index.php");
        } 
        else 
        {
            $getUserInfo = $result->fetch_array(MYSQLI_ASSOC);
            
            if ($getUserInfo["isBlocked"] != 1) 
            {
                $_SESSION['error'] = "Account Blocked.";
                if($host != NULL)
                {
                    header("location:"."http://".$host."/sign-in.php?error=".$_SESSION['error']);
                }
                else
                    header("location:index.php");
                
                exit();
            }
            
            if ($getUserInfo["deleteFlag"] > 0) 
            {
                $_SESSION['error'] = "Account Deleted.";
                if($host != NULL)
                {
                    header("location:"."http://".$host."/sign-in.php?error=".$_SESSION['error']);
                }
                else
                    header("location:index.php");
                
                exit();
            }

            #function call for assign value of session 
            $this->initiateSession($getUserInfo["userId"]);
            if($host != NULL)
            {
                $_SESSION['domain'] = $host;
            }
             //call function to save user details like ip browser, last login time
//             $userResp = $this->saveUserSystemDetails($get_userinfo["userId"]);
           
            if($signUp == 1)
            {
                return TRUE; 
                exit();
             
            }
            
            #get the url which is set by the user at the llast logout             
            $redirectUrl = "";
//                $redirectUrl = $this->getLandingPage($get_userinfo["userId"]);
//            var_dump($redirectUrl);
            if ($_SESSION['client_type'] == 1) 
            {
                $_SESSION['isAdmin'] = 1;
                $redirectUrl = "admin/index.php#!manage-client.php|manage-client-setting.php";
                header("location: ".$redirectUrl);
            }
            else 
            {
                if($redirectUrl == "")
                    $redirectUrl = "userhome.php#!contact.php";
//                else
//                    $redirectUrl = "userhome.php#".$redirectUrl;
                header("location: ".$redirectUrl);
            }
//            if ($remember_me) {
//                setcookie('usern', $userid, time() + 60 * 60 * 24 * 30);
//                setcookie('passn', $pwd, time() + 60 * 60 * 24 * 30);
//            } else {
//                setcookie('usern', $userid, time() - 100);
//                setcookie('passn', $pwd, time() - 100);
//            }
        }
       
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
            $url = "../admin/index.php";
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
    function is_admin() 
    {  
        if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) 
        {
            return true;
        }
        
        //get admin id
        $result = $this->selectData('adminId','91_adminUser',"adminId='" . $_SESSION['id'] . "'");
        
//        $dbh = $this->db_connect();
//        $sql = "select adminId from 91_adminUser where adminId='" . $_SESSION['id'] . "'";
//        $result = mysql_query($sql, $dbh);
//        mysql_close($dbh);
          
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
    function check_admin() 
    {
        $admin = $this->is_admin();
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
        if (!isset($_SESSION['id']))
            return false;
    
        if ($_SESSION['client_type'] != 2 && $_SESSION['client_type'] != 1 && $_SESSION['client_type'] != 4)
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
        if (!isset($_SESSION['id']))
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
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === "XMLHttpRequest") 
        {
            echo "<script>window.top.location.href='$url'</script>";
        } 
        else 
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
     */
    function generatePassword($length = 4) 
    {
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
        //prepare details for curl
        $param["to"] = $tempparam['to'];
        $param["text"] = urlencode($tempparam['text']);
       
        $connect_url = "https://api.clickatell.com/http/sendmsg?user=phone91&password=NCeOUcgYOeJOeU&api_id=3451976&to=".$param["to"]."&text=".$param["text"].""; // 
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
        $connect_url = "http://india.msg91.com/sendhttp.php"; // Do not change
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
     */
    function verifyCode($code,$number,$type=NULL) 
    {
        
        if(!is_numeric($code) || !is_numeric($number)&& $type !='EMAIL' )
            return json_encode(array("msg"=>"Invalid confirm code  ","status"=>"error"));
        
        $count = 0;
        //query to check code exist in contact table
        
        if($type == 'EMAIL')
          $result = $this->selectData("userId","91_verifiedEmails","email='".$number."' and confirm_code=".$code);
        else
           $result = $this->selectData("userId"," 91_verifiedNumbers","CONCAT(countryCode,verifiedNumber) = '".$number."' and confirmCode=".$code);
        
       // $result = $this->selectData("userId",$tableName,"CONCAT(countryCode,verifiedNumber) = '".$number."' and confirmCode=".$code);
        ///echo ' Query '.$this->querry;
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
                 $resultTemp = $this->selectData("userId"," 91_tempEmails","email= '".$number."' and confirm_code=".$code);
            else
                 $resultTemp = $this->selectData("userId","91_tempNumbers","CONCAT(countryCode,tempNumber) = '".$number."' and confirmCode=".$code);
            
            if($resultTemp)
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
        
        if(preg_match('/[^a-zA-Z0-9\@\.\_]+/',$userName) || $userName == "")
        {
            return json_encode(array("msg"=>"Invalid Input please provide proper User Name","status"=>"error"));
        }

        if (strlen($userName) > 5) 
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
                            $nine['sender'] = "Phonee";
                            $nine['mobiles'] = $contact_no; // mobile number without 91
                            $nine['message'] = "Enter this confirmation code " . $confirmCode . " to reset your password."; // sms text for usd
                            //Call function
                            if ($code == "91")
                            {
                                 echo "there";
                                $this->SendSMS91($nine);
                            }
                            else
                                $this->SendSMSUSD($d);
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

    private function sendSmsCall($contactNo,$countryCode,$confirmCode,$smsCallFlag , $userName = NULL)
    {
        
        $contact = $countryCode . $contactNo;
        //Assign Variables for sending sms to user Username is ".$userName." and
        if ($smsCallFlag == "SMS") {
            $msg = "TaDa! You have been successfully registered at Phone 91. Your  verification code is ".$confirmCode.". For anything else, we are only a mail away at: support@phone91.com. Happy sharing! "; // sms text for usd					                                        
            $d['sender'] = "Phone91";
            $d['message'] = $msg;
            $d['mobiles'] = $contact;
            //Assign Variables for sending sms to 91 user
            $nine['sender'] = "Phonee";
            $nine['mobiles'] = $contactNo; // mobile number without 91
            $nine['message'] = $msg;
            //Call function
            if ($countryCode == "91")
            {
                $this->SendSMS91($nine);
            }
            else
                $this->SendSMSUSD($d);
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
        
        $serverName = $this->db->real_escape_string($_SERVER['HTTP_HOST']);
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
                    $showMsg = "Error process request pelase contact provider";
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
    
    
    public function forgotPassword($userName,$smsCall ,$countryCode = NULL)
    {
        if(preg_match('/[^a-zA-Z0-9\@\.\_]+/',$userName) || $userName == "")
        {
            return json_encode(array( "msg" => "This UserName ".$userName." Is Not Registered With Us","status" => "error" , "type" => 0 ));
        }
        
        if($smsCall == 'MAIL')
            $userIdField = 'userid';        
        else
            $userIdField = 'userId';
        
        $serverName = $this->db->real_escape_string($_SERVER['HTTP_HOST']);
        $serverResellerIdResult = $this->selectData('resellerId', '91_domainDetails',"domainName like '".$serverName."'");
        
        if(!$serverResellerIdResult || $serverResellerIdResult->num_rows <= 0)
        {
            $showMsg = "Internal server error please contact support";
            $status = "error";
            return json_encode(array("msg"=>$showMsg,"status"=>$status , "type" => 0));
        }
        else 
        {
            $fetchServerResult = $serverResellerIdResult->fetch_array(MYSQLI_ASSOC);
            $resellerIdServer = $fetchServerResult['resellerId'];
        }
        
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

            $resultVerSel = $this->selectData('userId,verifiedNumber,countryCode,confirmCode', '91_verifiedNumbers',"CONCAT(countryCode,verifiedNumber)='".$userName."' and domainResellerId = '".$resellerIdServer."'");
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
            
            $resultVerSel = $this->selectData('verifiedEmail_id,userid,email,confirm_code,default_email', '91_verifiedEmails',"email='".$userName."'");
            $verifiedCount = $resultVerSel->num_rows;
           

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
               $resellerIdServer = $fetchServerResult['resellerId'];
               $resultVerSel = $this->selectData('verifiedNumber,countryCode,confirmCode,'.$userIdField, '91_verifiedNumbers',"".$userIdField."='" . $userId . "' and domainResellerId = '".$resellerIdServer."'");
               
               $verifiedCount = $resultVerSel->num_rows;
           }
        }
        
        if($resultVerSel && $verifiedCount > 0)
        {
           
            if($verifiedCount == 1)
            {
                $numberArr = $resultVerSel->fetch_array(MYSQLI_ASSOC);
                
//                if($smsCall == 'MAIL')
//                {
//                    $userId = $numberArr['userid'];
//                }
//                else
                $userId = $numberArr[$userIdField];
                
                $confirmCode = $this->generatePassword();
                
                if(strlen($confirmCode) < 4)
                {
                    echo $confirmCode;
                }    
              //  echo ' sms '.$smsCall;
                if($smsCall == 'MAIL')
                {
                    $condition = "userId='" . $userId . "'  and email='".$userName."'";
                    $data = array("confirm_code"=>$confirmCode);
                      
                    
                    
                    $varifiedId = $userName;
                    $updRes = $this->updateData($data, "91_verifiedEmails" , $condition);
               
                  // echo  ' QUery '.$this->querry;
                    
                    if($updRes && $this->db->affected_rows > 0)
                    {
                        $this->sendSmsCall( $varifiedId, $numberArr['countryCode'], $confirmCode,$smsCall );

                        $responseArr['type'] = "1";
                        $responseArr['id'] = $userId;
                        $responseArr['contact'] = $userName;
                        $responseArr['status'] = 'success';

                        return json_encode($responseArr);
                    }
                    else 
                    {
                        $showMsg = "Error process request pelase contact provider";
                        $status = "error"; 
                    }
                    
                }
                else
                {
                    $data = array("confirmCode"=>$confirmCode);
                    $condition = "userId='" . $userId . "' and domainResellerId = '".$resellerIdServer."' and verifiedNumber='".$numberArr['verifiedNumber']."'";
                    $varifiedId = $numberArr['verifiedNumber'];
                    $updRes = $this->updateData($data, "91_verifiedNumbers" , $condition);
                    
                    //echo $this->querry;
                    if($updRes && $this->db->affected_rows > 0)
                    {
                        $this->sendSmsCall( $varifiedId, $numberArr['countryCode'], $confirmCode,$smsCall );

                        $responseArr['type'] = "1";
                        $responseArr['id'] = $userId;
                        $responseArr['contact'] = array($numberArr['countryCode'].'-'.$numberArr['verifiedNumber']);
                        $responseArr['status'] = 'success';

                        return json_encode($responseArr);
                    }
                    else 
                    {
                        $showMsg = "Error process request pelase contact provider ";
                        $status = "error"; 
                    }
                    
                }

            }
            else
            {
                while($numberArr = $resultVerSel->fetch_array(MYSQLI_ASSOC))
                {
                    $verifiedNumberResArr[] = $numberArr['countryCode']."-".$numberArr['verifiedNumber'];
                }
                $responseArr['type'] = "2";
                $responseArr['id'] = $userId;
                $responseArr['contact'] = $verifiedNumberResArr;
                $responseArr['status'] = 'success';
                return json_encode($responseArr);
            }
        }
        else
        {
           // $showMsg = "Error fetching details please try again later";
            ///$status = "error";
        }
                    return json_encode(array("msg"=>$showMsg,"status"=>$status,"type"=>"0"));
    }
    
    /**
     * last updated by Balachandra<balachandra@hostnsoft.com> on 29/07/2013 
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
        
        if($type != 1)
        {
            if(preg_match('/[^a-zA-Z0-9\@\$\}\{\.]+/', $currPwd) || $currPwd == "")
            {
                return json_encode(array('msgtype' => 'error', 'msg' => 'Please Enter Vlaid Current Password'));
            }
            #access password by database of the current user
            $result = $this->selectData('password',$table,"userId = '" . $userid . "'");
            

            #fetching the array element and putting in a varible $pwd
            $pwd = $result->fetch_array(MYSQLI_ASSOC);

            #store the particular column data
            if ($pwd['password'] != $currPwd) 
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
        
        $result1 = $this->updateData($data,$table,"userId = '" . $userId . "' ");
      
        
        #if query executed then
        if ($result1 && $this->db->affected_rows > 0) {
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
        return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email);
    }

    
    
    #function use to send code for verify email id 
    #function use Mandrill
    function send_verification_mail($newEmailid, $pwd,$balance = NULL,$currencyId = NULL , $type = NULL ) 
    {
        
        if($type == 1)
        {
            $mailData = <<<EOF
            <div style="width:1000px;">
            <div style="margin-bottom:10px;font-size:23px"><a>Howdy! </a> </div>
                    <div style="text-align:center; border:2px solid #000; font-size:18px;"> 
                    Your Verification Code Is $pwd
                    
                    </div>
EOF;
        }
        else 
        {
        $currName = $this->getCurrencyViaApc($currencyId,1);
        $pwd = base64_encode($pwd);
        //code to create mail content   
        $mailData = <<<EOF
<div style="width:1000px;">
<div style="margin-bottom:10px;font-size:23px">
<a>Howdy! </a> 
</div>
<div style="font-size:18px; margin-bottom:14px;"> <a>Thank you for joining the Phone 91 family. Your account has been recharged with an initial amount of <strong> $balance  $currName</strong> so that you can start calling right away. Staying connected at Phone 91 is simple with Two-way calling, Voice conferencing and making International calls from our local number.</a> </div>

<div style="text-align:center; border:2px solid #000; font-size:18px;">

<div> To get started, please click the below link to validate your E-mail address: </div>
<div><a href="http://voip91.com/verify_email.php?email=$newEmailid&confirmationCode=$pwd" title="Email Verification " target="_blank">Validate </a>  </div>
<div style="font-size:25 px; margin-bottom:12px; margin-top:12px;" > OR </div>
You can alternatively verify it by entering the confirmation code: <strong> $pwd </strong>in your account settings option.

</div>
<div style="font-size:18px; margin-bottom:12px; margin-top:12px;"> Once done with verification, you can experience all the awesome stuff we have for you </div>
<div style="font-size:18px; list-style-type:disc;">
<ul>
<li style="margin-bottom:5px;"> Your Phone91 account enables you to add multiple IM accounts and manage your call log at ease.</li>
<li style="margin-bottom:5px;"> You can use the buy credits option on top to recharge your Phone91 account and manage your entire transaction log. </li>
<li style="margin-bottom:5px;" > Whenever you recharge your account and share it on facebook, your account will be immediately credited with 10% extra of your recharge amount. </li>
</ul>
</div>
<div style="font-size:18px; margin-bottom:12px; margin-top:12px;">
Stay connected with us on <a href="fb.om" title="phone91 facebook" target="_blank">Facebook</a> ,  <a href="Twitter.om" title="phone91 Twitter" target="_blank">Twitter</a>  and on our <a href="blog.phone91.com" title="phone91 blog" target="_blank">blog</a>  to  always be notified about the interesting offers we introduce from time to time. For anything else, we are just a mail away at: <strong>support@phone91.com</strong>.
</div>
<div style="font-size:18px; margin-bottom:12px;">
Thanks once again, we hope to help you always to stay closer to the ones you love!
</div>

<div >
Stay closer </br> Team Phone 91 
</div>
</div>
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
        $connect_url = 'https://voip91.com/phone91_verification/mobile_verify_api.php'; // Do not change
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
        $json = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * 
     * @param int $uid
     */
    function mobile_verification_response($uid) 
    {
        $connect_url = 'https://voip91.com/phone91_verification/mobile_verify_response.php'; // Do not change
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


    function getUserIP() 
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !is_null($_SERVER['HTTP_X_FORWARDED_FOR'])) 
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } 
        else 
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
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
    function randomNumber($length) 
    {
        $space = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        $spaceLen = strlen($space);
        for ($i = 0; $i < $length; $i++) {
            $str .= $space[mt_rand(0, $spaceLen - 1)];
        }

        return $str;
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

#find country name 

    
    
    function countryArray() 
    {
        $url = "http://voip92.com/isoData.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $string1 = json_decode($data, true);
        
        $count = count($string1);
        
        for ($i = 0; $i < $count; $i++) 
        {
            $country[$string1[$i]['CountryCode']] = $string1[$i]['Country'];
        }
        asort($country);
        return $country;
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
    function sendErrorMail($email, $mailData) 
    {
       //set parameters to send mail
        $from = "support@phone91.com";
        $subject = "Phone91 Error Report";
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
    function getResellerId($userid)
    {
         
      $loginTable = '91_userBalance';
      
      #get reseller id for user 
      $result = $this->selectData('*',$loginTable,"userId = '" .$userid. "'");
      
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
        $url = $this->db->real_escape_string($url);
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
            return $row['url'];
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
      $result = $this->selectData('managerId,managerName',$managerTable,"userId = ".$userId." ");
      
      if($result->num_rows > 0)
      {
          $res = $result->fetch_array(MYSQLI_ASSOC);
          $managerId = $res['managerId'];
          $managerName = $res['managerName'];
          
          return array("managerId" => $managerId,
                       "managerName" => $managerName);
      }
      else
          return array("managerId" => 2,
                       "managerName"=>"Voipreseller");
             
    }
    /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @param user id to get ip detail 
     * @return array 
     */
    function getUserSystemDetail($userId)
    {
      
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
                    'Browser' => $userBrowser);
       
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
        #condition for find username and pin detail 
        $condition = "userId = '" . $userId . "' ";

        #find user name of given id (we can not use session name because userid will change).
        $info = "91_personalInfo";
        $userInfo = $this->selectData('*',$info,$condition);
        
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
    function getUserId($userName)
    {
        
        if(preg_match('/[^a-zA-Z0-9\@\.\_]+/', $userName))
        {
            return json_encode(array("msg"=>"Invalid user name please enter a valid name","status"=>"error"));
        }
                
        $userName = $this->db->real_escape_string($userName);
        $condition = "userName = '" . $userName . "' ";

        #find userId of given name 
        $manageClient = "91_manageClient";
        $userinfo = $this->selectData('userId', $manageClient,$condition);
        
        if ($userinfo->num_rows > 0) 
        {
            $user = $userinfo->fetch_array(MYSQLI_ASSOC);
        
            return $userId = $user['userId'];             
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
                    $userName = $batchId.substr(md5(microtime()*3),0,$length);		
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
            $phone = $res['phone'];
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
                 echo "<script>window.location.href='#!setting.php|email.php'</script>";
            }   
        }
        
     
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
        
        if(preg_match('/[^a-zA-Z0-9\@\_\.\-]+/', $domianName) || $domianName == "")
                return 0;
        
        
        $result = $this->selectData("*", "91_domainDetails","domainName like '".$domianName."'");
        
        if($result && $result->num_rows > 0)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if($type == 1)
                $result = $row['resellerId'];
            elseif($type == 2)
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

    function enableSip($userId,$action)
    {
        if(!$this->check_admin())
            return json_encode (array("msg"=>"This feature is only for admin","status"=>"error"));
        
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

        
        $result = $this->selectData("userName,password", "91_userLogin","userId=".$userId);
        
        if($result)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $userName  = $row['userName']; 
            $password  = $row['password']; 
            $data = array("sipFlag"=>"1");
            $table = "91_userLogin";
            $condition = "userId=".$userId;
            if($res)
            {
                if($action == 1)
                {
                    sip_add($userName,$password);
                }
                elseif($action == 0)
                {
                    sip_delete($userName,$password);
                }
                
                return json_encode (array("msg"=>"Sip updated successfuly","status"=>"success"));
            }
        }
        else
           return json_encode (array("msg"=>"No data found for this user please contact provider","status"=>"error"));
    }

}

$funobj = new fun(); //class object
?>