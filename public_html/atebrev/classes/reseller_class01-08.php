<?php
include dirname(dirname(__FILE__)).'/config.php';
class reseller_class extends fun
{
	function getChiildList($userId){
		//$userid
		$limit=30;
		if (isset($page_number))
		{
		    $start = ($page_number - 1) * $limit;
		}
		else
			$start = 0;
		$table="clientsshared";
		$this->db->select('*')->from($table)->where("id_reseller = '" . $userId . "' ")->limit($limit)->offset($start);
		$result = $this->db->execute();
		//var_dump($result);
		// processing the query result
		if ($result->num_rows > 0) {	
			while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
				$returnResult["id"]=$row["id_client"];
				$returnResult["login"]=$row["login"];
				$response[]=$returnResult;
			}
		}
		else {
		$response[]="";	
		}
		return json_encode($response);
		
	}
	function searchChiildList($userId,$q){
		//$userid
		$limit=30;
		if (isset($page_number))
		{
		    $start = ($page_number - 1) * $limit;
		}
		else
			$start = 0;
		if(strlen($q)<1)
		{
			$returnResult["value"]="Empty Query";
			$returnResult["lable"]="Empty Query";
			$response[]=$returnResult;
			return json_encode($response);
		}
		$table="clientsshared";
		$this->db->select('*')->from($table)->where("id_reseller = '" . $userId . "' and login like '".$q."%' ")->limit($limit)->offset($start);
		$result = $this->db->execute();
		//var_dump($result);
		// processing the query result
		if ($result->num_rows > 0) {	
			while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
				$returnResult["value"]=$row["id_client"];
				$returnResult["lable"]=$row["login"];
				$response[]=$returnResult;
			}
		}
		else {
		$response[]="";	
		}
		return json_encode($response);
		
	}
        function changeResellerSettings($request,$userid){
            extract($request);
            if(($key=='mobile' || $key=='email') && ($value==1 || $value==0))
            {
                $table='91_reseller_setting';
                $data = array($key=>$value);
                $condition = " userid=".$userid." ";
                $this->db->update($table, $data)->where($condition);	
//                var_dump($this->db->getQuery());
                if($result = $this->db->execute()){
//                    var_dump($result);
                if($result){
                    $response["msg"]="Update Sccessfully";
                    $response["msg_type"]="success";                    
                }
                }
            }
            else{
//              $response[]="";	
                $response["msg"]="Update";
                $response["msg_type"]="error";                    
            }
		return json_encode($response);
        }
        
        function manageClients($request, $session) {
	    
	$userid = $session["userid"];
        extract($request);
	//$jade["_rndr"] = "true";
	$jade["isSearchResult"] = "false";
	$jade["searchQuery"] = "";
        include_once("classes/profile_class.php");
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
        if (!isset($submit2)) {
            $total_rows = $pro_obj->load_total_users2(0, $_SESSION['id']);
        }
	$unique = 0;
        if (isset($q) and trim($q) != '') {
            //$q = '';
            if (isset($q) && $q != '')
                $q = strtolower($q);
	    if(isset($_REQUEST['unique']))
		$unique = 1;
            $total_rows = $pro_obj->load_total_searched_users($userid, $q, $unique);
            $result = $pro_obj->load_searched_users($userid, $q, $first_limit, $limit, $unique);
	    //tmp $jade["_rndr"] = "false";
	    
	    $jade["isSearchResult"] = "true";
	    $jade["searchQuery"] = $q;
	    
        }
        else {
            $s_name = 'By Name';
            $s_uname = 'By Username';
            $s_mobno = 'By Mobile';
            //sorting code added by sapna
            $sort = 0;
            if (isset($_REQUEST['select_type']) && isset($_REQUEST['select_field'])) {
                $select_type = $_REQUEST['select_type'];
                $select_field = $_REQUEST['select_field'];
                $result = $pro_obj->load_sorted_user(0, $_SESSION['id'], $first_limit, $limit, $select_field, $select_type);
            } else {
                $result = $pro_obj->load_users2(0, $_SESSION['id'], $first_limit, $limit, 3);
            }
        }
        if (isset($_REQUEST['id']))
            $result = $pro_obj->load_user_details($_REQUEST['id']);
	
        $pages = ceil($total_rows / $limit);
	
        $str = '';
	if(mysql_num_rows($result)==0)
	{
	    $jade["client"][] = "No Record Found";
	}
//        while ($row = mysql_fetch_assoc($result)) {
//for ($i=0;$i<mysql_num_rows($result);$i++)
            foreach($result as $row){
            $id = $row['id_client'];
	    
	
        $this->db->select('*')->from('user_profile')->where("userid = '" . $id . "' ");	
        $profile_result = $this->db->execute();
        // processing the query result
        if ($profile_result->num_rows ==1) {
	        
            $rowData =$profile_result->fetch_array(MYSQL_ASSOC) ;	
	    $name = $rowData["name"];
        }
        else if ($profile_result->num_rows == 0)
	{
		$name="";

	}
	$contact_no='';
        
        $this->db->select('*')->from('91_verifiedNumbers')->where("userId = '" . $id . "' and is_default=1 ");
//        echo "ssss".$this->db->getQuery();
        $email_result = $this->db->execute();        
//        var_dump($email_result);  
        // processing the query result
        if ($email_result ->num_rows ==1) {
            $rowEmail=$email_result ->fetch_array(MYSQL_ASSOC) ;	
//            var_dump($rowEmail);
//	    $email  = $rowEmail["email"];
//	    $ccode  = $rowEmail["cntry_code"];
	    $contact_no  = $rowEmail["verifiedNumber"];
	    
        }
        else if ($email_result->num_rows == 0)
	{
		$email ="";

	}
	
		
	$uname = $row['login'];
	
            //$name = $row['login'];
            $client_type = $row['client_type'];
            $id_currency = $row['id_currency'];
            $id_tariff = $row['id_tariff'];
            //$contact_no=$row['user_mobno'];
            //$ccode=$row['user_country_code'];
            //$email=$row['user_email'];
            //$user_type=$row['user_status'];
            //$type=$row['user_type'];
            
            //$expiry=$row['user_expiry'];
            $balance = $row['account_state'];
            $status = $row['status'];
            $is_confirm = $pro_obj->isConfirm($id);
            $confirm = 'No';
            if ($is_confirm == 1)
                $confirm = "Yes";
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
            if (is_array($data))
                unset($data);
            $data["id"] = $id;
            $data["name"] = $name;
            $data["uname"] = $uname;
            //$data["ccode"] = ;
            
            $data["contact_no"] = $ccode.$contact_no;
            
            
            //Also Work Same As
//	    /$pro_obj->getCurrency($id_currency, "name");
	    
	if ($id_currency == 1) {
		$currency = "USD";
	} else if ($id_currency == 2) {
		$currency = "INR";
	} else if ($id_currency == 3) {
		$currency = "AED";
	}

            $data["id_currency_name"] = $currency;//
	if($client_type==3)		
		$data["client_type"] = "user";
	else if($client_type==2)		
		$data["client_type"] = "reseller";
	else if($client_type==1)		
		$data["client_type"] = "Admin";
	
	    

            $data["id_tariff_desc"] = $pro_obj->getTarrif($id_tariff, "description");
	    $data["balance"] = $balance;
	    
            $data["confirm"] = $confirm;
            
	$data["status"] = $status;
//	$data["status2"] = $status2;
//	$data["status3"] = $status3;
//	$data["id_currency"] = $id_currency;
            $data["id_tariff"] = $id_tariff;
	
            $jade["client"][] = $data;
        }
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
//echo json_encode(array('trdata'=>$str,'paging'=>$pageStr));
        return json_encode($jade);
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 29-07-2013
    #function use for add client detial
    function addNewClient($parm,$resellerid){
        
        #check username is blank or not
        if($parm['username'] == '' || $parm['username'] == NULL){
            return json_encode(array("status"=>"error","msg"=>"Please insert user name ."));
        }
        
        #check country name is selected or not  
        if ($parm['country'] == "select_country"){  
            return json_encode(array("status"=>"error","msg"=>"Please Select Country Name"));
        }
        
        #check contact no is valid or not 
        if (!preg_match("/^[0-9]{8,15}$/", $parm['contactNo'])){  
            return json_encode(array("status"=>"error","msg"=>"contact no. are not valid!"));
        }
        
        #check email id is valid or not 
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $parm['email'])){       
            return json_encode(array("status"=>"error","msg"=>"email id is not valid !"));
        }
        
        #check tariff paln is selected or not 
        if($parm['tariff'] == "select"){
            return json_encode(array("status"=>"error","msg"=>"Please Select Tariff Plan ! "));
        }
        
        #chech payment type is selected or not 
        if($parm['type'] == "select"){
            return json_encode(array("status"=>"error","msg"=>"Please Select Payment Type ! "));
        }
        
        #check total no of pins is numeric or not 
        if (!preg_match("/^[0-9]+$/", $parm['balance'])){  
            return json_encode(array("status"=>"error","msg"=>"Numeric value required in balance field ! "));
        }
        
       //to check if phoneno existes or not
      $table = '91_verifiedNumbers';
      $this->db->select('*')->from($table)->where("verifiedNumber = '" . $parm['contactNo'] . "' and countryCode = '".$parm['contactNo_code']."'");
      $this->db->getQuery();
      $result = $this->db->execute();
      if ($result->num_rows > 0){
          return json_encode(array("status"=>"error","msg"=>"Phone number already in use by another user!"));
      }
      
      //to check  email address already exists or not 
      $table = '91_verifiedEmails';
      $this->db->select('*')->from($table)->where("email = '" . $parm['email'] . "'");
      $this->db->getQuery();
      $result = $this->db->execute();
      if ($result->num_rows > 0){
          return json_encode(array("status"=>"error","msg"=>"This email address already registered!"));
      }
      
      
      
      #insert userdetail into database 
      $personalTable = '91_personalInfo';
      $data=array("name"=>$parm['username'],"contact_no"=>$parm['contactNo'],"emailId"=>$parm['email'],"countryCode"=>(int)$parm['contactNo_code']); 

      #insert query (insert data into 91_personalInfo table )
      $this->db->insert($personalTable, $data);	
      $qur = $this->db->getQuery();
      $result = $this->db->execute();
      //var_dump($result);
      #check data inserted or not 
      if(!$result){
          mail("sudhir@hostnsoft.com", "Phone91 add new user personal info table query fail", "query " . $qur);
         return json_encode(array("status"=>"","msg"=>"add user process fail! $qur"));
     }
     
       #user id 
      $userid = $this->db->insert_id;
      
      #insert login detail into login table database 
      $loginTable = '91_userLogin';
      $data=array("userId"=>(int)$userid,"userName"=>$parm['username'],"password"=>$parm['password'],"isBlocked"=>1,"type"=>3); 

      #insert query (insert data into 91_userLogin table )
      $this->db->insert($loginTable, $data);	
      $qur = $this->db->getQuery();
      $result = $this->db->execute();
      //var_dump($result);
      #check data inserted or not 
      if(!$result){
          mail("sudhir@hostnsoft.com", "Phone91 add new user userlogin  table query fail", "query " . $qur);
         return json_encode(array("status"=>"error","msg"=>"add user process fail ! $qur"));
          
      }
      
      
      #user balance from plan table  
      $balance = $parm['balance'];
      #puls 
      $puls = 60;
      #currency id 
      $currency_id = 2;
      #call limit 
      $call_limit = 2;
      
      #insert login detail into login table database 
      $loginTable = '91_userBalance';
      $data=array("userId"=>(int)$userid,"tariffId"=>(int)$parm['tariff'],"balance"=>$balance,"pulse"=>$puls,"currencyId"=>(int)$currency_id,"callLimit"=>(int)$call_limit,"resellerId"=>(int)$resellerid); 
      
      #insert query (insert data into 91_userLogin table )
      $this->db->insert($loginTable, $data);	
      $tempsql = $this->db->getQuery();
      $result = $this->db->execute();
      //var_dump($result);
      if (!$result){
        mail("sudhir@hostnsoft.com", "Phone91 add new user 91_userBalance query fail", "query " . $tempsql);
        return json_encode(array("status"=>"error","msg"=>"add user process fail! $tempsql"));  
      }
      
      #add taransaction detail into taransation log table 
        $transactionlog = "91_transactionLog";       
        $data=array("userId"=>(int)$userid,"date"=>date('Y-m-d H:i:s'),"amount"=>$balance,"credit"=>1,"debit"=>0,"paymentType"=>(int)$parm['type']); 
 
        #insert query (insert data into 91_tempEmails table )
        $this->db->insert($transactionlog, $data);	
        $this->db->getQuery();
        $savedata = $this->db->execute();
        //var_dump($savedata);

      
      return json_encode(array("status"=>"success","msg"=>"successfuly add new user.")); 
    }
    
    
}//end of class
?>