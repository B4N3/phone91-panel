<?php
include('config.php');
/**
 * 
 * @author : Rahul <rahul@hostnsoft.com>
 * 
 */

if($_SESSION['client_type']==2)
    error_reporting(-1);
class json extends fun {
    public $reseller;
    function getUserChain($user_id) {
	$sql_clients = "select id_client,client_type,login from clientsshared where id_reseller='" . $user_id . "'";	
	$dbh  = $this->connect();
	$result_clients = mysql_query($sql_clients, $dbh) or die(mysql_error($dbh) . $sql_clients);
	mysql_close($dbh);

	if ($result_clients) {
	    while ($row_clients = mysql_fetch_array($result_clients)) {
		    $this->reseller[]=$row_clients['id_client'];
		    $this->user[$row_clients['id_client']]=$row_clients['login'];
		    if($row_clients["client_type"]!=3)
			    $this->getUserChain($row_clients['id_client']);
	    }
	}
    }
    function callHistory($request, $session) {
	
        $userid = $session["userid"];
        extract($request);
	
	
       
        //$jade["heading"] = "Call History";
	//$jade["_rndr"] = "true";
        //get limit if not set
        if (!isset($limit))
            $limit = 15;
        //get start if page no. set
        if (isset($page_number))
	{
	    $start = ($page_number - 1) * $limit;
	    //$jade["_rndr"] = "false";
	}
        else
            $start = 0;
        //query to get call history
	
	if($_SESSION['client_type']==2)
	{
	    $this->getUserChain($userid);
	    $chain = implode(' , ', $this->reseller);
	    {
		$clint_id_con  ="id_client in (" . $chain . ") " ;
	    }
	}
	else
	    $clint_id_con  ="id_client='" . $userid . "' " ;
	$condition = 1;
	if(isset($request['num'])){
	    $request['to'] = $request['to'] == ''?date("y-m-d"):$request['to'];
	    $condition = "called_number like '%".$request['num']."%' AND call_start BETWEEN '".$request['from']."%' AND '20".$request['to']."%'";
	    $sql = "select * from calls where ".$condition."limit ". $start . ",10";  
	    $jade['search'] = $request['num'];
	}else{
	    $jade['search'] = '';
	    $condition = $clint_id_con;
	    $sql = "select * from calls where $clint_id_con and called_number NOT LIKE '0000%' order by call_start desc limit " . $start . ",10";
	}
	$con = $this->connect();
        $result = mysql_query($sql) or die("Error In Sql: " . mysql_error());
        //query to count all records
        $sqlForCount = "SELECT COUNT(*) FROM calls WHERE ". $condition;
	
        $countResult = mysql_query($sqlForCount);
        $countArray = mysql_fetch_array($countResult);
        $totalCount = $countArray['COUNT(*)'];
        if (mysql_num_rows($result) == 0) {
            $jade["table"] = "No Record Found";
	    $jade["totalpage"] = 0;
        } else {
            $jade["table"] = "";
            $tableHeading[] = "Called Number";
            $tableHeading[] = "Time";
            $tableHeading[] = "Price";
            $tableHeading[] = "Duration";
            $tableHeading[] = "Tariff";
            $tableHeading[] = "Status";
            $tableHeading[] = "Call Via";
	    if($_SESSION['client_type']==2)
	    {
		$tableHeading[] = "UserName";	
	    }
            $table["thead"] = $tableHeading;
            //set class to color odd even rows
            while ($rows = mysql_fetch_array($result)) {
                if (isset($tableRow) && is_array($tableRow))
                    unset($tableRow);
                $tableRow[] = $rows['called_number'];
                $tableRow[] = $rows['call_start'];
                $tableRow[] = $rows['cost'];
                $tableRow[] = $rows['duration'];
                $tableRow[] = $rows['tariffdesc'];
                $tableRow[] = $rows['reason'];
                $tableRow[] = $rows['call_type'];
		if($_SESSION['client_type']==2)
		{
		    $tableRow[] = $this->user[$rows['id_client']];
		    
		}
                $tbody[] = $tableRow;
            }
            $table["tbody"] = $tbody;
            //find total pages for found entries
            $pages = ceil($totalCount / $limit);
            //code for pagination
            $jade["totalpage"] = $pages;
	    //$start_page=0;
	    //$end_page=0;
	    
	    if(!isset($page_number) && $pages>0)
	    {
		
		if(isset($page_number))
		{
			$startPage = $page_number-7;
			$endPage = $page_number+7;
		}
		else//set start page ,end page and page number
		{
			$startPage = 1;
			$endPage = 15;
			$page_number = 1;
		}
		//set start page and end page
		if($startPage <= 0)
			$startPage = 1;
		if(($endPage-$startPage) < 14)
			$endPage = 15;
		if($endPage > $pages)
			$endPage = $pages;
		
		if (isset($page_number)) {
		    $start_page = $page_number - 7;
		    $end_page = $page_number + 7;
		} else {
		    $start_page = 1;
		    $end_page = 15;
		}
		
		$jade["startpage"] = $start_page;
		$jade["endpage"] = $end_page;
		 
	    }
	    
	   
            $jade["table"] = $table;
        }
        echo json_encode($jade);
    }
    function managePlanData($request, $session) {
        include_once("classes/profile_class.php");
        if (isset($request['rand'])) {
            
            $limit = 6;
          if(!isset($request['pid']))
              $request['pid'] = 8;
          $pid = $request['pid'];
            if (isset($request['page_number']))
                $first_limit = ($request['page_number'] - 1) * $limit;
            else
                $first_limit = 0;
            
            $total_rows = $pro_obj->total_tariff_rates($request['pid']);
            
            $pages = ceil($total_rows / $limit);
            $r_rp_detail = $pro_obj->load_tariff_rates($request['pid'], $first_limit, $limit);
            $tableHeading[] = "Country Code";
            $tableHeading[] = "Country Name";
            $tableHeading[] = "Rate Plan";
            $tableHeading[] = "Deletes";
            $jade["thead"] = $tableHeading;
            
            while ($rw_rp_detail = mysql_fetch_array($r_rp_detail)) {
                $prefix = $rw_rp_detail['prefix'];
                $description = $rw_rp_detail['description'];
                $rate = $rw_rp_detail['voice_rate'];
                $id_tariffs_key = $rw_rp_detail['id_tariffs_key'];
                $arrval[] = array($prefix, $description, $rate, $id_tariffs_key);
            }
            
            $jade['allvaluess'] = $arrval;
            $start_page = 0;
            $end_page = 0;
            if ($pages > 1) {
                if ($pages > 15) {
                    if (isset($request['page_number'])) {
                        $start_page = $request['page_number'] - 7;
                        $end_page = $request['page_number'] + 7;
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
                }
                else {
                    $start_page = 0;
                    $end_page = 0;
                }
            }
            $jade['pid'] = $pid;
            $jade['page'] = $end_page;
            
            $jade['allvalue'] = '';
            
           
            echo json_encode($jade);
        } else {
            $this->managePlans($request, $session);
        }
    }
    function managePlans($request, $session) {
        $userid = $session["userid"];
        extract($request);
        $con = $this->connect();
        $jade["heading"] = "Manage Tarrif Plans";
        //get limit if not set
        if (!isset($limit))
            $limit = 10;
	if(isset($request['limit']))
	    $limit = $request['limit'];
        //get start if page no. set
        if (isset($page_number))
            $start = ($page_number - 1) * $limit;
        else
            $start = 0;

	    if(empty($_GET['mnpln']))
		    $_GET['mnpln'] = 1;
	    
        //query to get call history
	if($_GET['mnpln'] == 'search'){
	    $sql = "select * from tariffreseller, tariffsnames where tariffreseller.id_reseller=" . $_SESSION['id'] . " and tariffreseller.id_tariff=tariffsnames.id_tariff and tariffsnames.description like '".$_REQUEST['val']."%'";
	    $value['search'] = $_REQUEST['val'];
	}else{
	    $sql = "select * from tariffreseller, tariffsnames where tariffreseller.id_reseller=" . $_SESSION['id'] . " and tariffreseller.id_tariff=tariffsnames.id_tariff ORDER BY ". $_GET['mnpln']." LIMIT ".$start.",".$limit;
	     $value['search'] = 'search';
	}$result = mysql_query($sql) or die("Error In Sql: " . mysql_error());
	$currency = array();
	$currency_sql = "SELECT * from currency_names";
	$currency_query = mysql_query($currency_sql) or die("Error In Sql: " . mysql_error());
	while($curr_result = mysql_fetch_array($currency_query))
		$currency[$curr_result['id']] = $curr_result['name'];
        while ($rows = mysql_fetch_array($result)) {
            if (is_array(isset($tableRow)))
                unset($tableRow);
            $tariff = $rows['id_tariff'];
            $description = $rows['description'];
	    $date = $rows['date_created'];
	    $plan_currency = $currency[$rows['id_currency']];
            $data['id'] = $tariff;
            $data['value'] = $description;
	    $data['date'] = $date;
	    $data['currency'] = $plan_currency;
            $value['allvalue'][] = $data;
        }
        //find total pages for found entries
        $value['thead'] = '';
        $value['page'] = '';
        $value['allvaluess'] = '';
	$value['pid'] = '';
        if(isset($totalCount) && isset($limit))
            $pages = ceil($totalCount / $limit);
	$value['count'] = $this->count_data("tariffreseller, tariffsnames where tariffreseller.id_reseller=" . $_SESSION['id'] . " and tariffreseller.id_tariff=tariffsnames.id_tariff");
	$value['limit'] = $limit;
	if(!isset($value['allvalue']))
	   $value['allvalue'] = ''; 
        return json_encode($value);
    }
    
    function count_data($condition){
	$con = $this->connect();
	$count_sql = "select count(*) from ".$condition;
	$count_query = mysql_query($count_sql) or die("Error In Sql: " . mysql_error());
	$count_data = mysql_fetch_array($count_query);
	return $count_data['count(*)'];
    }
    
    function managePlanData1($request, $session) {
        include_once("classes/profile_class.php");
	if(isset($request['no_of_records']))
	    $limit = $request['no_of_records'];
	else
	    $limit = 6;
        if (!isset($request['pid']))
            $request['pid'] = 8;
        if (isset($request['page_number']))
            $first_limit = ($request['page_number'] - 1) * $limit;
        else
            $first_limit = 0;
        $total_rows = $pro_obj->total_tariff_rates($request['pid']);
        $pages = ceil($total_rows / $limit);
        $r_rp_detail = $pro_obj->load_tariff_rates($request['pid'], $first_limit, $limit);
        $tableHeading[] = "Country Code";
        $tableHeading[] = "Country Name";
        $tableHeading[] = "Rate Plan";
        $tableHeading[] = "Deletes";
        $jade["thead"] = $tableHeading;
        while ($rw_rp_detail = mysql_fetch_array($r_rp_detail)) {
	    $pid = $rw_rp_detail['id_tariff'];
            $prefix = $rw_rp_detail['prefix'];
            $description = $rw_rp_detail['description'];
            $rate = $rw_rp_detail['voice_rate'];
            $id_tariffs_key = $rw_rp_detail['id_tariffs_key'];
            $arrval[] = array($prefix, $description, $rate, $id_tariffs_key,$pid);
        }
        $jade['allvaluess'] = $arrval;
        $start_page = 0;
        $end_page = 0;
        if ($pages > 1) {
            if ($pages > 15) {
                if (isset($request['page_number'])) {
                    $start_page = $request['page_number'] - 7;
                    $end_page = $request['page_number'] + 7;
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
            }
            else {
                $start_page = 0;
                $end_page = 0;
            }
        }
        $jade['page'] = $end_page;
	$jade['count'] = $this->count_data("tariffs where id_tariff=".$request['pid']);
	$jade['value'] =  json_decode($this->managePlans(null,$_SESSION));
	if(isset($_REQUEST['pid']))
	     $jade['pid'] = $_REQUEST['pid'];
        echo json_encode($jade);
    }
    function activeCall($request, $session) {
        $userid = $session["userid"];
        extract($request);
        $con = $this->connect();
        $jade["heading"] = "Active Call Detals";
        if ($_SESSION['client_type'] == 1) {
            $sql = "select * from currentcalls order by call_start desc limit 0,10";
            $result = mysql_query($sql) or die("Error In Sql: " . mysql_error());
        } else {
            $sql = "select * from currentcalls where id_client='" . $userid . "' and dialed_number NOT LIKE '0000%' order by call_start desc limit 0,10";
            $result = mysql_query($sql) or die("Error In Sql: " . mysql_error());
        }
        mysql_close($con);
        if (mysql_num_rows($result) == 0) {
            $jade["table"] = "No Record Found";
        } else {
            $tableHeading[] = "Called Number";
            $tableHeading[] = "Time";
            $tableHeading[] = "Region";
            $table["thead"] = $tableHeading;
            while ($rows = mysql_fetch_array($result)) {
                if (is_array($tableRow))
                    unset($tableRow);
                $tableRow[] = $rows['dialed_number'];
                $tableRow[] = $rows['call_start'];
                $tableRow[] = $rows['tariffdesc'];
                $table["tbody"][] = $tableRow;
            }
            //$table["paging"]= $str;  
            $jade["table"] = $table;
        }
        echo json_encode($jade);
    }
    function rechargeByPin(){
	return json_encode(array());
    }
    
    function rechargeHistory($request, $session) {
        $userid = $session["userid"];
        extract($request);
        $con = $this->connect();        
        if (!isset($limit))
            $limit = 10;
        //get start if page no. set
        if (isset($page_number))
            $start = ($page_number - 1) * $limit;
        else
            $start = 0;
        $sql = "SELECT money,data,description FROM payments WHERE id_client='" . $userid . "' ORDER BY data DESC LIMIT " . $start . ",10";
        //query to count all records
        $sqlForCount = "SELECT COUNT(*) FROM payments WHERE id_client='" . $userid . "' ";
        $countResult = mysql_query($sqlForCount);
        $countArray = mysql_fetch_array($countResult);
        $totalCount = $countArray['COUNT(*)'];
        $result = mysql_query($sql) or die("Error In Sql: " . mysql_error());
        if (mysql_num_rows($result) == 0) {
            $jade["table"] = "No Record Found";
        } else {
            $jade["table"] = "";
            $tableHeading[] = "Date";
            $tableHeading[] = "Amount";
            $tableHeading[] = "Done By";
	    
            $table["thead"] = $tableHeading;
            while ($rows = mysql_fetch_array($result)) {
                if (isset($tableRow) && is_array($tableRow))
                    unset($tableRow);
                $tableRow[] = $rows['data'];
                $tableRow[] = $rows['money'];
                $tableRow[] = $rows['description'];
                $table["tbody"][] = $tableRow;
            }
            //find total pages for found entries
            $pages = ceil($totalCount / $limit);
            //code for pagination
            $jade["totalpage"] = $pages;
            $jade["table"] = $table;
        }
        echo json_encode($jade);
    }
    function twowayCalling($request, $session) {
        $userid = $session["userid"];
        extract($request);
        $table = 'contact';
        $this->db->select('contact_no')->from($table)->where("userid = '" . $userid . "' ");
        $result = $this->db->execute();
        // processing the query result
        if ($result->num_rows > 0) {
            foreach ($result->fetch_array(MYSQL_ASSOC) as $row) {
                $contact = $row;
            }
        }
        else
            $contact = 0;
        //$contact = strip_tags($this->user_contact());
        /* $contact = preg_replace('^<script(.*?)>(.*?)</script>$', '', $contact); */
        //$contact = preg_replace('/<script.+?<\/script>/im', "", $contact);
        if ($contact == 0) {
            $jade["action"] = "Redirect";
            $jade["reason"] = "Number Not confirmed";
        } else {
            $jade["action"] = "ShowData";
            $jade["reason"] = "Number confirmed";
        }
        $jade["myNumber"] = $contact;
        echo json_encode($jade);
    }
//    function pay($request, $session) {
//        include 'pay.php';
//    }
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
                $result = $pro_obj->load_users2(0, $_SESSION['id'], $first_limit, $limit, $sort);
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
        while ($row = mysql_fetch_assoc($result)) {
//for ($i=0;$i<mysql_num_rows($result);$i++)
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
	
        $this->db->select('*')->from('contact')->where("userid = '" . $id . "' ");
        $email_result = $this->db->execute();
        // processing the query result
        if ($email_result ->num_rows ==1) {
            $rowEmail=$email_result ->fetch_array(MYSQL_ASSOC) ;	
	    $email  = $rowEmail["email"];
	    $ccode  = $rowEmail["cntry_code"];
	    $contact_no  = $rowEmail["contact_no"];
	    
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
        echo json_encode($jade);
    }
    //get user profile settings
    function profileSettings(){
	$con = $this->connect();
	$settings_sql = "SELECT * FROM profile_settings WHERE user_id = ".$_SESSION['userid'];
	$excute_sql = mysql_query($settings_sql,$con);
	while($result = mysql_fetch_array($excute_sql))
		return $result;
    }
    function Rates() {
        echo json_encode(array());
    }
    function pin($request, $session){
	include_once("classes/pin_class.php");
	error_reporting(-1);	
	$pin_obj = new pin_class();
	echo $pin_obj->getPinDetails($request,$session);
    }
    function addPin() {
         echo $this->managePlans($result = array(), $_SESSION);  
    }
    function managePin($request, $session) {
	$userid = $session["userid"];
	extract($request);
	$limit=30;
	if (isset($page_number))
	{
	    $start = ($page_number - 1) * $limit;
	}
	else
		$start = 0;
	include_once("classes/pin_class.php");
	error_reporting(-1);
	$pin_obj = new pin_class();
	$pin = (isset($request['pin']))?$request['pin']:null;
	echo $pin_obj->getMyPin($_SESSION["id"],$start,$limit);

    }
    function userSettings() {
        echo json_encode($this->profileSettings());
    }
    function addPlan() {
       echo $this->managePlans($result = array(), $_SESSION);  
    }
    function managePlan() {
	if(isset($_GET['limit']))
	    $result['limit'] = $_GET['limit'];
	else
	    $result = array();
	if(isset($_GET['page_number']))
	    $result['page_number'] = $_GET['page_number'];
	echo $this->managePlans($result, $_SESSION); 
    }
     
   function manageTariff() {
	$result['pid'] = $_GET['pid'];
	if(isset($_GET['page_number']))
	    $result['page_number'] = $_GET['page_number'];
	$result['no_of_records'] = 25;
        $this->managePlanData1($result, $_SESSION);
    }
    function changeSettings(){
	$con = $this->connect();
	$value = ($_REQUEST['value'] == 0)?1:0;
	echo $update_sql = "UPDATE profile_settings SET ".$_REQUEST['key']."=".$value." WHERE user_id =".$_SESSION['userid'];
	mysql_query($update_sql,$con);
    }
    function getPinDetails($request, $session) {
//	    $userid = $session["userid"];
	extract($request);
	$limit=10;
	if (isset($page_number))
	{
	    $start = ($page_number - 1) * $limit;
	}
	else
		$start = 0;
	include_once("classes/pin_class.php");
	error_reporting(-1);	
	$pin_obj = new pin_class();
	echo $pin_obj->getPinDetails($batchId,$start,$limit);	
}

    function manageClientstest($request, $session) {
        include_once("classes/profile_class.php");
        $limit = 10;
        $pageName = 'search_client_new.php';
        $page = 'search_client_new.php';
        $page_number = $_REQUEST['page_number'];
        if (isset($page_number)) {
            $pg_num = $page_number;
            $first_limit = ($page_number - 1) * $limit;
        }
        else
            $first_limit = 0;
        if (!isset($_REQUEST['submit2'])) {
            $total_rows = $pro_obj->load_total_users2(0, $_SESSION['id']);
        }
        if (isset($_REQUEST['q']) and trim($_REQUEST['q']) != '') {
            $q = '';
            if (isset($_REQUEST['q']) && $_REQUEST['q'] != '')
                $q = strtolower($_REQUEST['q']);
            $total_rows = $pro_obj->load_total_searched_users($_SESSION['id'], $q);
            $result = $pro_obj->load_searched_users($_SESSION['id'], $q, $first_limit, $limit);
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
                $result = $pro_obj->load_users2(0, $_SESSION['id'], $first_limit, $limit, $sort);
            }
        }
        if (isset($_REQUEST['id']))
            $result = $pro_obj->load_user_details($_REQUEST['id']);
        $pages = ceil($total_rows / $limit);
        $str = '';
        $tableHeading[] = "id";
        $tableHeading[] = "name";
        $tableHeading[] = "uname";
        $tableHeading[] = "ccode";
        $tableHeading[] = "contact_no";
        $tableHeading[] = "client_type";
        $tableHeading[] = "balance";
        $tableHeading[] = "id_currency";
        $tableHeading[] = "id_tariff";
        $tableHeading[] = "id_currency_name";
        $tableHeading[] = "id_tariff_desc";
        $tableHeading[] = "confirm";
        $tableHeading[] = "status1";
        $tableHeading[] = "status2";
        $tableHeading[] = "status3";
        while ($row = mysql_fetch_assoc($result)) {
            $id = $row['id_client'];
            $name = $row['login'];
            $client_type = $row['client_type'];
            $id_currency = $row['id_currency'];
            $id_tariff = $row['id_tariff'];
            //$contact_no=$row['user_mobno'];
            //$ccode=$row['user_country_code'];
            //$email=$row['user_email'];
            //$user_type=$row['user_status'];
            //$type=$row['user_type'];
            $uname = $row['login'];
            //$expiry=$row['user_expiry'];
            $balance = $row['account_state'];
            $status = $row['status'];
            $is_confirm = $pro_obj->isConfirm($id);
            $confirm = 'No';
            if ($is_confirm == 1)
                $confirm = "Yes";
            $status1 = '';
            $status2 = '';
            $status3 = '';
            switch ($status) {
                case 0: $status2 = 'selected';
                    break;
                case 1: $status1 = 'selected';
                    $status3 = 'checked="checked"';
                    break;
                case 2: $status2 = 'selected';
                    break;
                case 3: $status2 = 'selected';
                    break;
            }
            if (is_array($data))
                unset($data);
            $arrval[] = array($id, $name, $uname, $ccode, $contact_no, $client_type, $balance, $id_currency, $id_tariff, $pro_obj->getCurrency($id_currency, "name"), $pro_obj->getTarrif($id_tariff, "description"), $confirm, $status1, $status2, $status3);
        }
        $value = $arrval;
        $jade["thead"] = $tableHeading;
        $jade["values"] = $value;
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
            $jade["pageParam"] = $pageStr;
        }
        $jade["totalpage"] = $start_page;
        $jade["totalpage"] = $end_page;
//echo json_encode(array('trdata'=>$str,'paging'=>$pageStr));
        echo json_encode($jade);
    }
    
    function profile($request, $session)
    {
	$userid = $session["userid"];
        extract($request);
	$table = 'user_profile';
        $this->db->select('*')->from($table)->where("userid = '" . $userid . "' ");
        $result = $this->db->execute();
        // processing the query result
        if ($result->num_rows ==1) {
	    $jade["exist"] = "1";	    
            $jade["details"] =$result->fetch_array(MYSQL_ASSOC) ;		
        }
        else if ($result->num_rows == 0)
	{
	    $jade["exist"] = "0";
	    $jade["details"] =array("name"=>"","dob"=>'',"city"=>'',"country"=>"","zip"=>'',"ocupation"=>"","address"=>'',"sex"=>'');
	}
        echo json_encode($jade);
    }
    
    
}
$jsonObj = new json(); //class object
$action = $_REQUEST['action'];
echo $jsonObj->$action($_REQUEST, $_SESSION);
//$jsonObj->callHistory($_SESSION['userid'],$_REQUEST['limit'],$_REQUEST['page_number']);
?>