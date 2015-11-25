<?php

include dirname(dirname(__FILE__)) . '/config.php';

class reseller_class extends fun {

    function getChiildList($userId) {
        //$userid
        $limit = 30;
        if (isset($page_number)) {
            $start = ($page_number - 1) * $limit;
        }
        else
            $start = 0;
        $table = "clientsshared";
        $this->db->select('*')->from($table)->where("id_reseller = '" . $userId . "' ")->limit($limit)->offset($start);
        $result = $this->db->execute();
        //var_dump($result);
        // processing the query result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                $returnResult["id"] = $row["id_client"];
                $returnResult["login"] = $row["login"];
                $response[] = $returnResult;
            }
        } else {
            $response[] = "";
        }
        return json_encode($response);
    }

    function searchChiildList($userId, $q) {
        //$userid
        $limit = 30;
        if (isset($page_number)) {
            $start = ($page_number - 1) * $limit;
        }
        else
            $start = 0;
        if (strlen($q) < 1) {
            $returnResult["value"] = "Empty Query";
            $returnResult["lable"] = "Empty Query";
            $response[] = $returnResult;
            return json_encode($response);
        }
        $table = "91_manageClient";
        $this->db->select('*')->from($table)->where("resellerId = '" . $userId . "' and userName like '" . $q . "%' ")->limit($limit)->offset($start);
//        var_dump($this->db->getQuery());
        $result = $this->db->execute();
        //var_dump($result);
        // processing the query result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                $returnResult["lable"] = $row["userId"];
                $returnResult["value"] = $row["userName"];
                $response[] = $returnResult;
            }
        } else {
            $response[] = "";
        }
        return json_encode($response);
    }

    function changeResellerSettings($request, $userid) {


        extract($request);
        if (($key == 'mobile' || $key == 'email') && ($value == 1 || $value == 0)) {
            $table = '91_reseller_setting';
            $data = array($key => $value);
            $condition = " userid=" . $userid . " ";
            $this->db->update($table, $data)->where($condition);
//                var_dump($this->db->getQuery());
            if ($result = $this->db->execute()) {
//                    var_dump($result);
                if ($result) {
                    $response["msg"] = "Update Successfully";
                    $response["msg_type"] = "success";
                }
            }
        } else {
//              $response[]="";	
            $response["msg"] = "Update";
            $response["msg_type"] = "error";
        }
        return json_encode($response);
    }

    function checkParentReseller($request, $session) {
        /**
         * @author Rahul
         * @since 03 Aug 2013
         * @param array $request Contains ["id"]//Client Id
         * @param array $request Contains ["password"] new password to set
         * @param array $session Contains Reseller Id 
         */
        $table = '91_userBalance';
        $this->db->select('*')->from($table)->where("userId = '" . $request['clientId'] . "' and resellerId=" . $session['id']);
        $sql = $this->db->getQuery();
        $result = $this->db->execute();
        if ($result->num_rows > 0) {
            return true;
        } else {
            die("You Are Not Authorized To View This Page");
        }
    }

    function resetClientPassword($request, $session) {
        /**
         * @author Rahul
         * @since 03 Aug 2013
         * @param array $request Contains ["id"]//Client Id
         * @param array $request Contains $request["newPass"] new password to set
         * @param array $session Contains Reseller Id 
         */
        //need code to verify password here $request["newPass"]

        $table = '91_userLogin';
        $data = array("password" => $request["newPass"]);
        $condition = " userId=" . $request['clientId'] . " ";

        $this->db->update($table, $data)->where($condition);

        $this->db->getQuery();

        $result = $this->db->execute();
//                    var_dump($result);
        if ($result) {
            $response["msg"] = "Update Sccessfully";
            $response["msg_type"] = "success";
        } else {
//              $response[]="";	
            $response["msg"] = "Update";
            $response["msg_type"] = "error";
        }
        return json_encode($response);
    }

    function manageClients($request, $session) {

//            var_dump($request);
//            
//            var_dump($session);
        $userid = $session["userid"];
        extract($request);
        //$jade["_rndr"] = "true";


        $jade["isSearchResult"] = "false";
        $jade["searchQuery"] = "";
        include_once("classes/profile_class.php");
        $pro_obj = new profile_class();
        $limit = 10;
        $pageName = 'search_client_new.php';
        $page = 'search_client_new.php';
        $page_number = $page_number;
        if (isset($page_number)) {
            $pg_num = $page_number;
            $first_limit = ($page_number - 1) * $limit;
            //tmp $jade["_rndr"] = "false";
        }
        else
            $first_limit = 0;
        
        
        

        //In case of default page open
        if (!isset($submit2)) {
            $total_record = $pro_obj->loadTotalUsers(0, $userid);
        }



        //In case of user search
        $unique = 0;
        if (isset($q) and trim($q) != '') {
            //$q = '';
            if (isset($q) && $q != '')
                $q = strtolower($q);
            if (isset($request['unique']))
                $unique = 1;
//            $total_record = $pro_obj->loadTotalSearchedUsers($userid, $q, $unique);
//            $result = $pro_obj->loadSearchedUsers($userid, $q, $first_limit, $limit, $unique);
            $result = $pro_obj->loadUsers($q, $userid, $first_limit, $limit, 3);
            //tmp $jade["_rndr"] = "false";

            $jade["isSearchResult"] = "true";
            $jade["searchQuery"] = $q;
        }
        else {

//            $sort = 0;
//            if (isset($_REQUEST['select_type']) && isset($_REQUEST['select_field'])) {
//                $select_type = $_REQUEST['select_type'];
//                $select_field = $_REQUEST['select_field'];
//                $result = $pro_obj->load_sorted_user(0, $userid, $first_limit, $limit, $select_field, $select_type);
//            } else {
            
            $result = $pro_obj->loadUsers(0, $userid, $first_limit, $limit, 3);
//            }
//            var_dump($result);
//die();            
        }

        
        //If user id is passed
        if (isset($_REQUEST['id']))
            $result = $pro_obj->load_user_details($request['id']);

        //Count total pages
        $pages = ceil($total_record / $limit);
//	echo "ssss";
//        echo count($result);
//        die();
        //if no record found
        $str = '';
//	if(count($result)==0)
//	{
//	    $jade["client"][] = "No Record Found";
//	}
//        while ($row = mysql_fetch_assoc($result)) {
//for ($i=0;$i<mysql_num_rows($result);$i++)
        
        foreach ($result as $row) {
            $id = $row['userId'];
            

//        $this->db->select('*')->from('user_profile')->where("userid = '" . $id . "' ");	
//        $profile_result = $this->db->execute();
//        // processing the query result
//        if ($profile_result->num_rows ==1) {
//	        
//            $rowData =$profile_result->fetch_array(MYSQL_ASSOC) ;	
//	    $name = $rowData["name"];
//        }
//        else if ($profile_result->num_rows == 0)
//	{
//		$name="";
//
//	}
            $contact_no = '';
/*
            $confirm = 'No';
            $this->db->select('*')->from('91_verifiedNumbers')->where("userId = '" . $id . "' and isDefault=1 ");
//        echo "ssss".$this->db->getQuery();
            $contact_result = $this->db->execute();
//        var_dump($email_result);  
            // processing the query result
            if ($contact_result->num_rows == 1) {
                $rowNumber = $contact_result->fetch_array(MYSQL_ASSOC);
//            var_dump($rowEmail);
//	    $email  = $rowEmail["email"];
//	    $ccode  = $rowEmail["cntry_code"];
                $contact_no = $rowNumber["countryCode"] . $rowNumber["verifiedNumber"];
                $confirm = "Yes";
            } else if ($email_result->num_rows == 0) {
                $email = "";
            }
*/

            $uname = $row['userName'];

            $name = $row['name'];
            $client_type = $row['type'];
            $id_currency = $row['currencyId'];
            $planName = $row['planName'];
            //$contact_no=$row['user_mobno'];
            //$ccode=$row['user_country_code'];
            //$email=$row['user_email'];
            //$user_type=$row['user_status'];
            //$type=$row['user_type'];
            //$expiry=$row['user_expiry'];
            $balance = $row['balance'];
            //$status = $row['status'];
//            $is_confirm = $pro_obj->isConfirm($id);
//            $status1 = '';
//            $status2 = '';
//            $status3 = '';
//            switch ($status) {
//                case 0: $status2 = 'selected';
//                    break;
//                case 1: $status1 = 'selected';
//                    $status3 = 'checked="checked"';
//                    break;
//                case 2: $status2 = 'selected';
//                    break;
//                case 3: $status2 = 'selected';
//                    break;
//            }
//            if (is_array($data))
//                unset($data);
            $data["id"] = $id;
            $data["name"] = $name;
            $data["uname"] = $uname;
            $data["id_tariff_desc"] = $planName;
            //$data["ccode"] = ;

            $data["contact_no"] = $contact_no;


            //Also Work Same As
//	    /$pro_obj->getCurrency($id_currency, "name");

            if ($id_currency == 147) {
                $currency = "USD";
            } else if ($id_currency == 63) {
                $currency = "INR";
            } else if ($id_currency == 1) {
                $currency = "AED";
            }

            $data["id_currency_name"] = $currency; //
            if ($client_type == 3)
                $data["client_type"] = "user";
            else if ($client_type == 2)
                $data["client_type"] = "reseller";
            else if ($client_type == 1)
                $data["client_type"] = "Admin";



//            $data["id_tariff_desc"] = $pro_obj->getTarrif($id_tariff, "description");
            $data["balance"] = $balance;

           // $data["confirm"] = $confirm;

           // $data["status"] = $status;
//	$data["status2"] = $status2;
//	$data["status3"] = $status3;
//	$data["id_currency"] = $id_currency;
           // $data["id_tariff"] = $id_tariff;

           
           
            $jade["client"][] = $data;
//            var_dump($data);
        }
        
      /*  
        $tableHeading[] = "name";
        $tableHeading[] = "uname";
        $tableHeading[] = "ccode";
        $tableHeading[] = "contact no";
        $tableHeading[] = "client_type";
        $tableHeading[] = "balance ";
        $tableHeading[] = "id_currency ";
        $tableHeading[] = "id_tariff ";
        $tableHeading[] = "id_currency_name ";
        $tableHeading[] = "id_tariff_desc ";
        $tableHeading[] = "confirm ";
        $tableHeading[] = "status1 ";
        $tableHeading[] = "status2 ";
        $tableHeading[] = "status3 ";
        $jade["thead"] = $tableHeading;
        $pageStr = '';
        $jade["totalpage"] = $pages;
        if ($pages > 1) {
            if (isset($_REQUEST['submit2'])) {
                $page = $pageName . "?s_uname=" . $_REQUEST['s_uname'] . "&s_name=" . $_REQUEST['s_name'] . "&s_mobno=" . $_REQUEST['s_mobno'] . "&utype=" . $_REQUEST['utype'] . "&submit2=submit";
            } else {
                if (strpos($page, "?") === FALSE)
                    $page = $page . "?";
                else
                    $page = $page . "&";
                $page = $page . "rand=" . rand(100000, 1000000);
            }
            if (isset($_REQUEST['page_number'])) {
                $start_page = $_REQUEST['page_number'] - 7;
                $end_page = $_REQUEST['page_number'] + 7;
            } else {
                $start_page = 1;
                $end_page = 15;
            }
            if ($start_page <= 0)
                $start_page = 1;
            if (($end_page - $start_page) < 14)
                $end_page = 15;
            if ($end_page > $pages)
                $end_page = $pages;
            $pageStr = '<div class="pagination" id="pagination"><ul>';
            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == 1) {
                    $pageStr.='<li><a id="mng_client1" href="javascript:;" onclick="loadClientData(' . $i . ')">';
                    if ((!isset($_REQUEST['page_number'])) || ($_REQUEST['page_number'] <= 1))
                        $pageStr.= '<b>1</b>';
                    else
                        $pageStr.='1';
                    $pageStr.='</a></li>';
                }
                else {
                    $pageStr.='<li ><a id="mng_client' . $i . '" href="javascript:;" onclick="loadClientData(' . $i . ')">';
                    if ($_REQUEST['page_number'] == $i)
                        $pageStr.= '<b>' . $i . '</b>';
                    else
                        $pageStr.= $i;
                    $pageStr.='</a></li>';
                }
            }
            $pageStr.='</ul></div>';
            //echo $pageStr;		
            //$jade["pageParam"] = $pageStr;
        }
        $jade["startpage"] = $start_page;
        $jade["endpage"] = $end_page;
//echo json_encode(array('trdata'=>$str,'paging'=>$pageStr));*/
       
        return json_encode($jade);
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 29-07-2013
    #function use for add client detial

    function addNewClient($parm, $resellerid) {
        
       
        #check username is blank or not
        if ($parm['username'] == '' || $parm['username'] == NULL) {
            return json_encode(array("status" => "error", "msg" => "Please insert user name ."));
        }

        #check country name is selected or not  
        if ($parm['country'] == "select_country") {
            return json_encode(array("status" => "error", "msg" => "Please Select Country Name"));
        }

        #check contact no is valid or not 
        if (!preg_match("/^[0-9]{8,15}$/", $parm['contactNumber'])) {
            return json_encode(array("status" => "error", "msg" => "contact no. are not valid!"));
        }

        #check email id is valid or not 
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $parm['email'])) {
            return json_encode(array("status" => "error", "msg" => "email id is not valid !"));
        }

        #check tariff paln is selected or not 
        if ($parm['tariff'] == "select") {
            return json_encode(array("status" => "error", "msg" => "Please Select Tariff Plan ! "));
        }

        #chech payment type is selected or not 
        if ($parm['type'] == "select") {
            return json_encode(array("status" => "error", "msg" => "Please Select Payment Type ! "));
        }

        #check total no of pins is numeric or not 
        if (!preg_match("/^[0-9]+$/", $parm['clientBalance'])) {
            return json_encode(array("status" => "error", "msg" => "Numeric value required in balance field ! "));
        }

        //to check if phoneno existes or not
        $table = '91_verifiedNumbers';
        $this->db->select('*')->from($table)->where("verifiedNumber = '" . $parm['contactNumber'] . "' and countryCode = '" . $parm['contactNo_code'] . "'");
        $this->db->getQuery();
        $result = $this->db->execute();
        if ($result->num_rows > 0) {
            return json_encode(array("status" => "error", "msg" => "Phone number already in use by another user!"));
        }

        //to check  email address already exists or not 
        $table = '91_verifiedEmails';
        $this->db->select('*')->from($table)->where("email = '" . $parm['email'] . "'");
        $this->db->getQuery();
        $result = $this->db->execute();
        if ($result->num_rows > 0) {
            return json_encode(array("status" => "error", "msg" => "This email address already registered!"));
        }

        #insert userdetail into database 
        $loginTable = '91_userLogin';
        $this->db->select('*')->from($loginTable)->where("userName = '" . $parm['username'] . "'");
        $this->db->getQuery();
        $result = $this->db->execute();
        if ($result->num_rows > 0) {
            return json_encode(array("status" => "error", "msg" => "sorry username already registered!"));
        }
        

        $name = $this->db->real_escape_string($parm['username']);
        $data = array("name" => $name);
        #insert query (insert data into 91_personalInfo table )
        $this->db->insert($personalTable, $data);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        //var_dump($result);
        #check data inserted or not 
        if (!$result) {
            $this->sendErrorMail("sudhir@hostnsoft.com", "insert query fail : $qur ");
            return json_encode(array("status" => "error", "msg" => "add user process fail! $qur"));
        }

        #user id 
        $userid = $this->db->insert_id;

        #insert login detail into login table database 
        $loginTable = '91_userLogin';
        $pass = $this->db->real_escape_string($parm['password']);
        $data = array("userId" => (int) $userid, "userName" => $name, "password" => $pass, "isBlocked" => 1, "type" => 3);

        #insert query (insert data into 91_userLogin table )
        $this->db->insert($loginTable, $data);
        $qur = $this->db->getQuery();
        $result = $this->db->execute();
        //var_dump($result);
        #check data inserted or not 
        if (!$result) {
            $this->sendErrorMail("sudhir@hostnsoft.com", "insert query fail : $qur ");
            return json_encode(array("status" => "error", "msg" => "add user process fail ! $qur"));
        }


        #user balance from plan table  
        $balance = $parm['clientBalance'];
        #puls 
        $puls = 60;
        #currency id 
        $currency_id = 2;
        #call limit 
        $call_limit = 2;
        #payment type (cash,memo,bank).
        $paymentType = $parm['type'];
        #description
        $description = '';

        $funobj = new fun();
        
        #get last chain id from user balance table  
        $lastchainId = $funobj->getlastChainId($resellerid);
        
               
        #new chain id (incremented id of lastchain id )
        $chainId = $funobj->newChainId($lastchainId);
      
        
        #insert login detail into login table database 
        $loginTable = '91_userBalance';
        $data = array("userId" => (int) $userid,"chainId"=>$chainId, "tariffId" => (int) $parm['tariff'], "balance" => 0, "currencyId" => (int) $currency_id, "callLimit" => (int) $call_limit, "resellerId" => (int) $resellerid);

        #insert query (insert data into 91_userLogin table )
        $this->db->insert($loginTable, $data);
        $tempsql = $this->db->getQuery();
        $result = $this->db->execute();
        //var_dump($result);
        if (!$result) {
            $this->sendErrorMail("sudhir@hostnsoft.com", "insert query fail : $tempsql ");
            return json_encode(array("status" => "error", "msg" => "add user process fail! $tempsql"));
        }



        #variable country code and phone no use for store contact no into 91_tempcontact table
        $country_code = $parm['contactNo_code'];
        $phone = $parm['contactNumber'];
        #contact no. store into tempcomtact table
        include_once("contact_class.php");
        $contact_obj = new contact_class();
        $msg = $contact_obj->update_newcontact($country_code, $phone, $userid);



        #email id store into tempemail table and send varification code into email 
        
        $msg = $contact_obj->addnew_emailid($parm['email'], $userid);




         #add taransaction detail into taransation log table 
        include_once("transaction_class.php");
        $transaction_obj = new transaction_class();
        
        /* CALL ADD TRANSACTIONAL FUNCTION FOR ADD TRANSACTION  : 
         * 
         * $resellerid : FromUser
         * $userid : toUser
         * $balance : amount for credit or debit
         * $balance : talktime amount 
         * $paymentType : cash,memo,bank
         * $description : description of transaction 
         * type : prepaid ,postpaid , partial
         * 
         */
        
        $msg = $transaction_obj->addTransactional($resellerid, $userid, $balance,$balance, $paymentType, $description, "prepaid"); //$fromUser,$toUser,$amount,$paymentType,$description,$type
        
        #get current balance form 91_userBalance table
        $currBalance = $transaction_obj->getcurrentbalance($userid);
        $currentBalance = ((int)$currBalance + (int)$balance);
        
        #update current balance of user in userbalance table 
        $transaction_obj->updateUserBalance($userid,$currentBalance);

//        $transactionlog = "91_transactionLog";       
//        $data=array("userId"=>(int)$userid,"date"=>date('Y-m-d H:i:s'),"amount"=>$balance,"credit"=>1,"debit"=>0,"paymentType"=>(int)$parm['type']); 
// 
//        #insert query (insert data into 91_tempEmails table )
//        $this->db->insert($transactionlog, $data);	
//        $this->db->getQuery();
//        $savedata = $this->db->execute();
//        //var_dump($savedata);
        
        //include_once("classes/profile_class.php");
        //$pro_obj = new profile_class();
        //$resellerClient = $pro_obj->loadUsers(0, $resellerid, 0, 10, 3);
        return json_encode(array('status' => 'success', 'msg' => 'successsully client added'));//,"resellerClient"=>$resellerClient
    }

    function loadUserDetails($user_id, $fields = '*', $resellerId) {

        $sql = "select " . $fields . " from 91_manageClient where userId='" . $user_id . "' and resellerId=" . $resellerId;
        $result = $this->db->query($sql);
//                var_dump($result);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_array(MYSQL_ASSOC)) {
//                                    var_dump($row);	
                return $row;
            }
        }
        if (!$result)
            return ("Unable To Fetch User Data");
//		return $result;
    }

    function sendErrorMail($email, $mailData) {
        require('awsSesMailClass.php');
        $sesObj = new awsSesMail();
        $from = "support@phone91.com";
        $subject = "Phone91 Error Report";
        $to = $email;
        $message = $mailData;
        $response = $sesObj->mailAwsSes($to, $subject, $message, $from);
    }
    
    
    #created by sudhir pandey <sudhir@hostnsoft.com>
    #creation date 27/08/2013
    #function use for edit fund of user 
    function editFund($parm,$userid){
        
      $funobj = new fun();
      
      #check permission for edit fund or not 
      $resellerId = $funobj->getResellerId($parm['toUserEditFund']);  
      
      if($resellerId != $userid){
          return json_encode(array("status" => "error", "msg" => "you have no permission for edit fund ."));
      }
        
      #include transaction class   
      include_once("transaction_class.php");
      
      #object of transaction class
      $transaction_obj = new transaction_class();
      
      //********* update closing Amount 
      
      #get closing Amount of user 
      $amount = $transaction_obj->getClosingBalance($parm['toUserEditFund']);  
      
      #check amount add or reduce in closing amount 
      if($parm['changefunderEditFund'] == "add"){
        #new updated amount current amount + given amount  
        $updatedAmount = $amount + $parm['fundAmount']; 
      }else
        $updatedAmount = $amount - $parm['fundAmount']; 
        
      
      
      
      //********** update balance of user 
      
      $balance = $transaction_obj->getcurrentbalance($parm['toUserEditFund']);
      
      #check balance add or reduce in currentbalance 
      if($parm['changefunderEditFund'] == "add"){
        #new updated amount current amount + given amount  
        $updatedBalance = $balance + $parm['balance']; 
      }else
        $updatedBalance = $balance - $parm['balance']; 
        
      
      
      
      //********** entry in transaction log table
      
      #add transaction in case of voip91(payment type).
      $result = $transaction_obj->addTransactional($userid,$parm['toUserEditFund'],$parm['fundAmount'],$parm['balance'],$parm['fundPaymentType'],$parm['fundDescription'],$parm['pType'],$parm['partialAmt']);
      
      #update user balance table 91_userbalance table
      $transaction_obj->updateUserBalance($parm['toUserEditFund'],$updatedBalance); 
      
      #update user closing Amount into 91_closingAmount table
      $transaction_obj->updateClosingBalance($parm['toUserEditFund'],$updatedAmount); 
      
      if($result == 1){
          return json_encode(array("status" => "success", "msg" => "successfully update user fund ."));
      }
      
      
    }

    
    #created by sudhir pandey <sudhir@hostnsoft.com> 
    #creation date 07/08/2013
    #function use to edit client information 
    function editClientInfo($parm,$userId){
        
//    [action] => editClientInfo
//    [clientName] => molu
//    [clientId] => 76597
//    [callLimit] => 2
//    [currenctTariff] => 171
         
        
        
//        #check contact no is valid or not 
//        if (!preg_match("/^[0-9]{8,15}$/", $parm['contactNo'])) {
//            return json_encode(array("status" => "error", "msg" => "contact no. are not valid!"));
//        }
//        
//        #check contact no is valid or not 
//        if (!preg_match("/^[0-9]{8,15}$/", $parm['contactNo'])) {
//            return json_encode(array("status" => "error", "msg" => "contact no. are not valid!"));
//        }
        
            
        
         #table name 
         $table = "91_userBalance";
         #update balance amount of user 
         $data=array("callLimit"=>$parm['callLimit'],"tariffId"=>$parm['currenctTariff']); 
         $condition = "userId=".$parm['clientId']." ";
         $this->db->update($table, $data)->where($condition);	
         #get update sql query 
         $qur = $this->db->getQuery();
         $results = $this->db->execute();
         if($results){
           return json_encode(array("status" => "success", "msg" => "successfully user information updated ."));
         }  else {
           return json_encode(array("status" => "", "msg" => "user information not update.".$qur));
         }
        
         
        
        
        
   
      
        
        
        
    }
}

//end of class
?>