<?php
include 'config.php';
include_once("classes/general_class.php");
include_once("classes/validation_class.php");
class profile_class extends validation_class
{
	function getCurrency($id_currency,$field)
	{
		$sql="Select * from currency_names where id='".$id_currency."' limit 1";
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result) die ("Error while fetching currency data.");
		$row=mysql_fetch_array($result);
		return $row[$field];
	}
	function getTarrif($id_tariff,$field)
	{
		$sql="Select * from tariffsnames where id_tariff='".$id_tariff."' limit 1";
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result) die ("Error while fetchinf tarrif data.");
		$row=mysql_fetch_array($result);
		return $row[$field];
	}
	function getContact($userid)
	{
		$sql="Select cntry_code,contact_no from contact where userid=".$userid;
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result) die ("Error while fetching contact detils.");
		//$row=mysql_fetch_array($result);
		return $result;
	}
	function load_total_users2($utype,$user_id)
	{
                //query to count users for reseller
		$sql="SELECT COUNT(*) FROM clientsshared WHERE id_reseller='".$user_id."' ";
		//create connection
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result)
			die ("Fatal Error in Loading Total User List");
		$row=mysql_fetch_row($result);
		return $row[0];
	}
	
        #function to count total rows for selected currency
//        function load_total_users2_ankit($utype,$user_id,$currency = NULL )
//	{
//                //if currency set then set condition for query
//                if($currency != NULL)
//                {
//                    $idCurr = $_SESSION['currency'][$currency];
//                    $cond="AND id_currency= $idCurr";
//                }
//                else//set blank if currency not set
//                    $cond = '';
//                //query to count users for reseller
//		$sql="SELECT COUNT(*) FROM clientsshared WHERE id_reseller='".$user_id."' $cond ";
//		//create connection
//		$dbh=$this->connect_db();
//		$result=mysql_query($sql,$dbh);
//		mysql_close($dbh);
//		if (!$result)//if result not found
//			die ("Fatal Error in Loading Total User List");
//		$row=mysql_fetch_row($result);
//		return $row[0];
//	}
	
	# Function created by Rohan Kumar for phone880 new search client
	#------------------------------------------------------------------------------
	# Function Used for counting total number of searched records
	function load_total_searched_users($user_id,$q)
	{
               
		$sql="Select count(*) from clientsshared where id_reseller='".$user_id."' and lower(login) like '%".$q."%'";
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result)
			die ("Fatal Error in Loading Total Number of User");
		$row=mysql_fetch_row($result);
		return $row[0];
	}
        
        //function to get count of total users for selected currency
        function load_total_searched_users_new($user_id,$q,$currency = NULL)
	{
                //if currency set then set condition for query
                 if($currency != NULL)
                {
                    $idCurr = $_SESSION['currency'][$currency];
                    $cond="AND id_currency= $idCurr";
                }
                else//set blank
                    $cond = '';
                if($q != "")
                    $sql = "SELECT COUNT(*) FROM clientsshared WHERE id_reseller='".$user_id."' AND lower(login) LIKE '%".$q."%' $cond";
                else //query to count users for reseller
                    $sql = "SELECT COUNT(*) FROM clientsshared WHERE id_reseller='".$user_id."' $cond ";
		
                    
		$dbh = $this->connect_db();
		$result = mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result)//check for result
                {
                    mail("ankitpatidar@hostnsoft.com","result not found while count total users in load_total_searched_users_new  ","in profile_class_latest.php");
                    die ("Fatal Error in Loading Total Number of User");
                }
                
                $row=mysql_fetch_row($result);
		return $row[0];
	}
        # Function Used for searched records with start limit and limit
	function load_searched_users($user_id,$q,$start=0,$limit=10)
	{
            $dbh=$this->connect_db();
            
                if(is_numeric($q))
                {
                    $sql_number = "select * FROM contact where contact_no like '%".$q."%'";
                    $resNum = mysql_query($sql_number,$dbh);
                    if (!$resNum)
			die ("Fatal Error in Loading Total Number of User");
                    $row0 = mysql_fetch_row($resNum);
                   
                    $sql="SELECT * FROM clientsshared where id_reseller = $user_id AND id_client = $row0[0] UNION DISTINCT Select * from clientsshared where id_reseller='".$user_id."' and lower(login) like '%".$q."%' order by login asc limit $start, $limit";
                }
                elseif(strpos($q,"@"))
                {
                  $sql_mail = "select * FROM contact where email like '%".$q."%'";
                  $resMail = mysql_query($sql_number,$dbh);
                    if (!$resNum)
			die ("Fatal Error in Loading Total Number of User");
                    $row0 = mysql_fetch_row($resNum);
                    
                    $sql="SELECT * FROM clientsshared where id_reseller = $user_id AND id_client = $row0[0] UNION DISTINCT Select * from clientsshared where id_reseller='".$user_id."' and lower(login) like '%".$q."%' order by login asc limit $start, $limit";  
                }
                elseif(strlen($q)>0)
                {
                    $sql="Select * from clientsshared where id_reseller='".$user_id."' and lower(login) like '%".$q."%' order by login asc limit $start, $limit";
                }
//		$sql="Select * from clientsshared where id_reseller='".$user_id."' and lower(login) like '%".$q."%' order by login asc limit $start, $limit";
//                echo $sql;		
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result)
			die ("Fatal Error in Loading Total User List");
		//$row=mysql_fetch_row($result);
		return $result;
	}
        
	# Function Used for searched records with start , limit ,or search by search string
	function load_searched_users1($user_id,$q,$start=0,$limit=10,$sort,$currency = NULL)
	{
            #@para $q is search string
            #last updated by "ankit" <ankitpatidar@hostnsoft.com> on 23/2/2013
              
             //subquery that will use in most of queries
            $subQuery = "SELECT a.id_client, a.login, a.client_type, a.id_currency, a.id_tariff, a.account_state, a.status, b.date
FROM `clientsshared` a LEFT JOIN `register_info` b ON a.id_client = b.userid WHERE a.id_reseller = $user_id ";
            
            
            //set order by sort value
            if($sort==0)
                    $order="a.id_client DESC";
            else if($sort==1)
                    $order="a.account_state DESC";
            else if($sort==2)
                    $order="a.account_state ASC";
            else if($sort==3)
                    $order="a.id_currency ASC";
            else if($sort==4)
                    $order="a.id_currency DESC";
            //create connection to database
            $dbh=$this->connect_db();
            //if search by number
            if(is_numeric($q))
            {
                //query for contact no.
                $sql_number = "SELECT * FROM contact WHERE contact_no LIKE '%".$q."%'";
                $resNum = mysql_query($sql_number,$dbh);
                if (!$resNum)//if error to load result for above query
                {
                    mail("AnkitPatidar@hostnsoft.com","result not found from contact table in load_searched_users1 function","in profile_class_latest.php".$sql_number);
                    die("Fatal Error in Loading Total Number of User");
                }
               
                //if rows exists
                 if(mysql_num_rows($resNum))
                 {    
                    //set string to use IN operator in query
                    $str = "a.id_client IN (";
                    //get all user ids
                    while($rows = mysql_fetch_assoc($resNum))
                    {
                        $str.=$rows['userid'].",";
                    }
                    //remove last comma
                    $strsub = substr($str,0,  strlen($str)-1);
                    $strsub.=") OR";
                 }//end of if,for mysql_num_rows
                 else
                      $strsub = "";
                //query if no. in login id
                $sql="$subQuery AND ($strsub lower(a.login) LIKE '%".$q."%') ORDER BY $order  LIMIT $start, $limit";
               
            }//end of if ,for numeric search string
            else if(strpos($q,"@"))//search by mail
            {
                $sql_mail = "SELECT * FROM contact WHERE email LIKE '%".$q."%'";
                $resMail = mysql_query($sql_mail,$dbh);
                if (!$resMail)
                {
                    mail("AnkitPatidar@hostnsoft.com","result not found from contact table in load_searched_users1 function  ","in profile_class_latest.php".$sql_mail);
                    die("Fatal Error in Loading Total Number of User");
                }
                
                //if rows exists
                if(mysql_num_rows($resMail))
                {
                  //generate string to use IN operator in query
                  $str = "a.id_client IN(";
                  //get all user ids
                  while($rows = mysql_fetch_assoc($resMail))
                  {
                      $str.=$rows['userid'].",";
                  }
                  //remove last comma
                  $strsub = substr($str,0,strlen($str)-1);
                  $strsub.=") OR";
                }//end of if for mysql_num_rows
                else
                    $strsub = "";
                //if login id contain @ symbol
                $sql="$subQuery  AND ($strsub lower(a.login) LIKE '%".$q."%')  ORDER BY $order LIMIT $start, $limit";  
            }//end of else in search string contains @
            else if(strlen($q) > 0)
            {
                $sql = "$subQuery  AND lower(a.login) LIKE '%".$q."%'  ORDER BY $order LIMIT $start, $limit";
                
            }
            else//default case
            {
                //if currency set then set condition for query
                if($currency != NULL)
                {
                    $idCurr = $_SESSION['currency'][$currency];
                    $cond = "AND a.id_currency= $idCurr";
                }
                else
                    $cond = '';
                $sql = "$subQuery $cond ORDER BY $order LIMIT $start,$limit ";
            }
		
            $result = mysql_query($sql,$dbh);
            mysql_close($dbh);//close db connection
            if (!$result)
            {
                mail("AnkitPatidar@hostnsoft.com","result not found while search in load_searched_users1 function","in profile_class_latest.php".$sql);
                die("Fatal Error in Loading Total User detail");
            }
            return $result;
	}//end of function load_searched_users1()
	#---------------------------------------------------------------------------------
	function load_user_details($user_id,$fields = '*')
	{
		$dbh=$this->connect_db();
		$sql="select ".$fields." from clientsshared where id_client='".$user_id."'";
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if(!$result)
			die("Unable To Fetch User Data");
		return $result;
	}
        
	function load_contact_details($id)
        {
            $dbh=$this->connect_db();
            $sql = "SELECT * FROM contact where userid = '".$id."'";
            $result = mysql_query($sql,$dbh);
            if(mysql_num_rows($result)>0)
            {
                mysql_close($dbh);
                return $result;
            }
            else
            {
                $sql_temp_tbl = "SELECT * FROM tempcontact where userid = '".$id."'";
                $result_temp = mysql_query($sql_temp_tbl,$dbh);
                mysql_close($dbh);
                return $result_temp;
            }
        }
	function load_users2($utype,$user_id,$start_limit,$limit,$sort)
	{
		if($sort==0)
			$order="lower(login)";
		else if($sort==1)
			$order="account_state";
		else if($sort==2)
			$order="account_state desc";	
		
		$sql="Select * from clientsshared where id_reseller='".$user_id."' order by ".$order." limit ".$start_limit.",".$limit;
		
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result)
			die ("Fatal Error in Loading User List # 3");
		
		return $result;
	}
        
        #function to load latest signups
//        function loadLatestsignup($user_id,$start_limit,$limit,$sort)
//        {
//                //set order by sort value
//		if($sort==0)
//			$order="id_client DESC";
//		else if($sort==1)
//			$order="account_state DESC";
//		else if($sort==2)
//			$order="account_state ASC";
//                else if($sort==3)
//                        $order="id_currency ASC";
//                else if($sort==4)
//                        $order="id_currency DESC";
//		
//                //query to fetch data
//		//$sql="SELECT * FROM clientsshared WHERE id_reseller='".$user_id."' ORDER BY ".$order."  LIMIT ".$start_limit.",".$limit;
//              $sql = " SELECT a.id_client, a.login, a.client_type, a.id_currency, a.id_tariff, a.account_state, a.status, b.date
//FROM  `clientsshared` a LEFT JOIN  `register_info` b ON a.id_client = b.userid
//WHERE a.id_reseller ='".$user_id."' ORDER BY ".$order." LIMIT ".$start_limit.",".$limit ;
//                
//		//create connection to db
//		$dbh=$this->connect_db();
//		$result=mysql_query($sql,$dbh);
//		mysql_close($dbh);
//		if (!$result)
//			die ("Fatal Error in Loading User List # 3");
//		
//		return $result;
//	}
	
	function load_filtered_users($s_name,$s_uname,$s_mobno,$utype,$start_limit,$limit,$sort)
	{
		$s_name=strtolower($s_name);
		$s_uname=strtolower($s_uname);
		if($sort==0)
			$order="lower(login)";
		else if($sort==1)
			$order="account_state";
		else if($sort==2)
			$order="account_state desc";
		if(!$this->check_empty($s_name,'')&&!$this->check_empty($s_uname,'')&&!$this->check_empty($s_mobno,''))
		return $this->load_users2($utype,$_SESSION['id'],$start_limit,$limit,$sort);
		/*if($utype==0)
		$uty="2 or user_type=3 or user_type=4";
		else
		$uty=$utype;*/
		//if($_SESSION['id']==1)
		/*if($_SESSION['id']==1 || is_admin())
			$sql="select * from ms_user where user_type!=1 and user_status!=3 and ";
		else 
			$sql="select * from ms_user where user_type!=1 and user_status!=3 and user_userid='".$_SESSION['id']."' and ";
			
		if(!check_empty($s_uname,'')&&!check_empty($s_name,''))
		$sql.="(user_mobno like '%".$s_mobno."%') and (user_type=".$uty.") order by ".$order." limit ".$start_limit.",".$limit;
		else if(!check_empty($s_uname,'')&&!check_empty($s_mobno,''))
		$sql.="(lower(user_fname) like '%".$s_name."%' or lower(user_lname) like '%".$s_name."%') and (user_type=".$uty.") order by ".$order." limit ".$start_limit.",".$limit;
		else if(!check_empty($s_name,'')&&!check_empty($s_mobno,''))
		$sql.="(lower(user_uname) like '%".$s_uname."%') and (user_type=".$uty.") order by ".$order." limit ".$start_limit.",".$limit;
		else if(check_empty($s_uname,'')&&check_empty($s_name,'')&&!check_empty($s_mobno,''))
		$sql.="(lower(user_fname) like '%".$s_name."%' or lower(user_lname) like '%".$s_name."' or lower(user_uname) like '%".$s_uname."%') and (user_type=".$uty.") order by ".$order." limit ".$start_limit.",".$limit;
		else if(check_empty($s_uname,'')&&!check_empty($s_name,'')&&check_empty($s_mobno,''))
		$sql.="(lower(user_uname) like '%".$s_uname."%' or user_mobno like '%".$s_mobno."%') and (user_type=".$uty.") order by ".$order." limit ".$start_limit.",".$limit;
		else if(!check_empty($s_uname,'')&&check_empty($s_name,'')&&check_empty($s_mobno,''))
		$sql.="(lower(user_fname) like '%".$s_name."%' or lower(user_lname) like '%".$s_name."' or user_mobno like '%".$s_mobno."%') and (user_type=".$uty.") order by ".$order." limit ".$start_limit.",".$limit;
		else if(check_empty($s_uname,'')&&check_empty($s_name,'')&&check_empty($s_mobno,''))
		$sql.="(lower(user_fname) like '%".$s_name."%' or lower(user_lname) like '%".$s_name."' or lower(user_uname) like '%".$s_uname."%' or user_mobno like '%".$s_mobno."%') and (user_type=".$uty.") order by ".$order." limit ".$start_limit.",".$limit;*/
		$sql="select * from clientsshared where id_reseller='".$_SESSION['id']."' and lower(login) like '%".$s_uname."%' order by ".$order." limit ".$start_limit.",".$limit;
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result)
			die ("Fatal Error in Loading Filtered User List");
		return $result;
	}

	function getUserBalance($user_id)//return user balance from new ms_user_balance Table
	{
		$dbh=$this->connect_db();
		
		$sql="select account_state from clientsshared where id_client='".$user_id."'";
		$result=mysql_query($sql,$dbh) or $error=mysql_error();
		if($result)
		{
			$row=mysql_fetch_row($result);
		}
 	   	mysql_close($dbh);
		if(!$result)
		{
			$a=debug_backtrace(); //backtrace the parent functions and pages
            $str='';
			$str.="file=>". $a[0]['file']."<br/>";
			$str.="function=>".$a[0]['function']."<br/>";
			$str.="line=>".$a[0]['line']."<br/>";
			$str.="file=>". $a[1]['file']."<br/>";
			$str.="function=>".$a[1]['function']."<br/>";
			$str.="line=>".$a[1]['line']."<br/>";
			$str.="file=>". $a[2]['file']."<br/>";
			$str.="function=>".$a[2]['function']."<br/>";
			$str.="line=>".$a[2]['line']."<br/>";
			mail("indoreankita@gmail.com","error in getUserBalance function".currentIP,$error."::".$sql."::".$str);
			return '';
		}
		else
		{
			return $row[0];
		}
	}

	function update_client_details(){
		switch($_REQUEST['update_detail']) {			
			case 1 :	
					$valid = 0;
				if (!$this -> check_empty($_REQUEST['callsLimit'], "callsLimit"))
					$valid = 1;
				if (!$this -> check_empty($_REQUEST['utype2'], "user type"))
					$valid = 1;
					if ($valid == 1) {
					echo $_SESSION['msg'];
					return;
				}
				
				$sql = "update clientsshared set callsLimit='" . $_REQUEST['callsLimit'] . "',client_type='" . $_REQUEST['utype2'] . "' where id_client='" . $_REQUEST['id'] . "'";
				$dbh = $this -> connect_db();
				$result = mysql_query($sql)
					or $error=mysql_error();
				mysql_close($dbh);
			
			//to update contact details of user	
			$contact_res=$this->getContact($_REQUEST['id']);
			$contact_row=mysql_fetch_array($contact_res);
			$cntry_code=$contact_row['cntry_code'];
			$contact_no=$contact_row['contact_no'];
			
			if($cntry_code!=$_REQUEST['cntry_code']||$contact_no!=$_REQUEST['contact_no']){
				$sql = "update contact set cntry_code='" . $_REQUEST['cntry_code'] . "',contact_no='" . $_REQUEST['contact_no'] . "',confirm=0 where userid='" . $_REQUEST['id'] . "'";
				$dbh = $this -> connect_db();
				$upt_result = mysql_query($sql)
					or $error=mysql_error();
				mysql_close($dbh);
			}
				if (!$result) {
					$_SESSION['msg_type'] = 3;
					echo $_SESSION['msg'] = "Client Details could not be updated.";
					return;
				} else {
					$_SESSION['msg_type'] = 1;
					echo $_SESSION['msg'] = "Update Successful";
				}
			case 2: 
		if($this->check_empty($_REQUEST['amount'],'')) //If User Balance is to be updated
		{
			$child_bal=$this->getUserBalance($_REQUEST['id']);
			if(!$this->check_empty($child_bal,''))
			{
				$_SESSION['msg_type']=3;
				echo $_SESSION['msg']="User Balance Could Not Be Loaded. Balance Not Transferred";
				return;
			}
			if($_REQUEST['type']==1)
				$new_child_bal=$child_bal+$_REQUEST['amount'];
			else
				$new_child_bal=$child_bal-$_REQUEST['amount'];
			$dbh=$this->connect_db();
			$sql="insert into payments (id_client,client_type,money,data,type,description,actual_value) values ('".$_REQUEST['id']."',32,'".$_REQUEST['amount']."',now(),'".$_REQUEST['type']."','".$_REQUEST['description']."',".$child_bal.")";
			$result=mysql_query($sql,$dbh);
			mysql_close($dbh);
			$child_bal=$new_child_bal;
			$this->updateUserBalance($_REQUEST['id'],$child_bal);
			$msg='Update Successful';
			return $msg;
		}
		break;
                
                case 3:
                    if($this->check_empty($_REQUEST['id'],'') && isset($_REQUEST['viewClientDetails']))
                    {
                        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                        if(!preg_match($regex, $_REQUEST['email']))
                        {
                            $_SESSION['msg_type']=3;
                            echo $_SESSION['msg']="Invalid Email Id";
                            return;
                        }
                        if(!is_numeric($_REQUEST['contact_no']) && !is_numeric($_REQUEST['cntry_code']))
                        {
                            $_SESSION['msg_type']=3;
                            echo $_SESSION['msg']="Invalid Contact Number";
                            return;
                        }
                        
                        $sql = "UPDATE contact SET contact_no = ".$_REQUEST['contact_no'].",email = '".$_REQUEST['email']."',cntry_code = ".$_REQUEST['cntry_code']." WHERE userid = ".$_REQUEST['id']."";
                        $dbh = $this->connect_db();
                        $result = mysql_query($sql,$dbh) or $error  = mysql_error().$sql;
                        if(mysql_affected_rows() == 0)
                        {
                            $sql1 = "UPDATE tempcontact SET contact_no = ".$_REQUEST['contact_no'].",email = '".$_REQUEST['email']."',cntry_code = ".$_REQUEST['cntry_code']." WHERE userid = ".$_REQUEST['id']."";
                            $result = mysql_query($sql1,$dbh) or $error  = mysql_error().$sql1;
                            
                        }
                        if(!$result)
                        {
                             $_SESSION['msg_type']=3;
                            echo $_SESSION['msg']="Error Update fails";
                            return;
                            
                        }
                    }
		}//end of switch
	}
	
	function updateUserBalance($userId,$newUserBal)
	{
		$sql="update clientsshared set account_state='".$newUserBal."' where id_client='".$userId."'";
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh) or $error=mysql_error();
		mysql_close($dbh);
		if($result)
			return true;
		else 
		{
			mail("indoreankita@gmail.com","error in updateUserBalance function",$error."::".$sql);
			return false;
		}
	}
	 
	 
	function load_ledger($l_id,$o_id,$start_limit,$limit)
	{
		//In terms of amount
		$sql="select * from payments where id_client=".$o_id." order by data desc limit ".$start_limit.",".$limit;
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result)
			return "Error Loading Transaction Details or No details available";
		return $result;
	}

	//function created by Sapna 03/03.2011
	function search_tariff_rates($str,$lim,$t_id)
	{
		$arr=explode("(",$str);
		$str=$arr[0];
		if($t_id==0)
		{
			$sql="select * from tariffreseller, tariffsnames where tariffreseller.id_reseller=".$_SESSION['id']." and tariffreseller.id_tariff=tariffsnames.id_tariff";
			
                                                            $dbh=$this->connect_db();
			$result=mysql_query($sql,$dbh);
			mysql_close($dbh);
			//select * from ms_tariffs where (id_tariff=67 or id_tariff=68) AND (country like '%in%' or prefix like'91%') LIMIT 10 
			$q_rp_detail="select * from tariffs where (id_tariff="; 
			while($arr=mysql_fetch_array($result))
			{
				$t_id=$arr['id_tariff'];
				$q_rp_detail.=$t_id." or id_tariff=";
			}
			if($t_id==0)
				$q_rp_detail.=$t_id." or id_tariff=";
			$q_rp_detail=rtrim($q_rp_detail,"or id_tariff=");
			$q_rp_detail.=") AND (prefix like'$str%') LIMIT $lim"; 	
			$dbh=$this->connect_db();
//                        mail("sameer@hostnsoft.com","searchquery1",$q_rp_detail);
			$r_rp_details=mysql_query($q_rp_detail,$dbh);
			mysql_close($dbh);
			return $r_rp_details;
		}
		else
		{
			$q_rp_detail="select * from tariffs where id_tariff='".$t_id."' AND (prefix LIKE '$str%') LIMIT $lim";
			$dbh=$this->connect_db();
			$r_rp_details=mysql_query($q_rp_detail,$dbh);
			mysql_close($dbh);
			return $r_rp_details;
		}
	}
	
	function load_tariff_rates($id_tariff,$first_limit,$limit,$select_type=NULL,$select_field=NULL)
	{
		$substr="";
		if($select_type!=""&&$select_field!="")
			$substr="order by ".$select_field." ".$select_type;
		$q_rp_detail="select * from tariffs where id_tariff=".$id_tariff." ".$substr." limit ".$first_limit.",".$limit;
		//echo $q_rp_detail;
		$dbh=$this->connect_db();
		$r_rp_detail=mysql_query($q_rp_detail,$dbh) or die("Error rateplan");;
		mysql_close($dbh);
		return $r_rp_detail;
	}


	function total_tariff_rates($id_tariff)
	{
		$q_rp_detail="select count(*) as cnt from tariffs where id_tariff=".$id_tariff;
		$dbh=$this->connect_db();
		$r_rp_detail=mysql_query($q_rp_detail,$dbh);
		mysql_close($dbh);
		$row_rp_detail=mysql_fetch_array($r_rp_detail);
		return $row_rp_detail['cnt'];
	}
	function upload_excell_file($file_name_path)
	{
		// Where the file is going to be placed 
		$filename_ext=$file_name_path['name'];
		$filename_ext = strtolower($filename_ext) ;
		$exts = explode(".", $filename_ext) ;
		$n = count($exts)-1;
		$exts = $exts[$n];
		//$target_path='/var/www/html/phone880/';
		if(($exts=='xls')||($exts=='xlsx'))
		{
			$target_path = $target_path.basename($_SESSION['id']);
			if ($exts== "xls")
				$target_path = $target_path.".xls";
			if ($exts == "xlsx")
				$target_path = $target_path.".xlsx";
		}
		else
		{
			$_SESSION['msg']="File Type Not Allowed # 2";
			return '';
			//die ("File Type Not Allowed");
		}
		if(move_uploaded_file($file_name_path['tmp_name'],$target_path))
		{
			//echo '<br/>ok<br/><br/>';
		} 
		else
		{
			$_SESSION['msg'].="There was an error uploading the file, please try again!";
			//die ("There was an error uploading the file, please try again!");
			return '';
		}
		return $target_path;
	}
	function import_tariff_rates($t_id)
	{
		error_reporting(0);
		require_once 'PHPExcel.php';
		require_once 'PHPExcel/IOFactory.php';
		
		if(!$this->check_user() && !$this->check_reseller() && !$this->check_admin())
			$this->expire();
		//echo $_SESSION['id'];
		//echo $_FILES['tariff_rates']['name'];
		if(isset($_REQUEST['rep']))
		{
			$q_del_prev="delete from tariff where id_tariff=".$t_id;
			mysql_query($q_del_prev);
		}
		//echo $_SESSION['msg'];
		$contact_file=$this->upload_excell_file($_FILES['tariff_rates']);
		
		ini_set('memory_limit', '512M');
		$filename_ext=$contact_file;
		$filename_ext = strtolower($filename_ext) ;
		$exts = split("[/\\.]", $filename_ext) ;
		
		$n = count($exts)-1;
		$exts = $exts[$n];
		if ($exts=="xls")
			$type="Excel5";
		else if($exts=="xlsx")
			$type="Excel2007";//die();
		
		$objReader = PHPExcel_IOFactory::createReader($type);
		
		
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($contact_file);
		
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$row_counter=0;
		foreach ($objWorksheet->getRowIterator() as $row) 
		{
			$row_counter=$row_counter+1;
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
			$counter=0;
			if($row_counter!=1)
			{	
				$valid=0;	
				foreach ($cellIterator as $cell) 
				{
					$counter=$counter+1;
					if($counter==1)
						echo $cntry=$cell->getValue();
					else if($counter==2)
						echo $ccode=$cell->getValue();
					else if($counter==3)
						echo $teriff_rate=$cell->getValue();
					if(is_numeric($teriff_rate) && preg_match('/[A-Za-z ]/',$cntry) && preg_match('/[0-9]/',$ccode))
						$valid=1;
					
				}
				if($valid==1)
				{
					$q_check_exist="select * from tariffs where id_tariff='".$t_id."' and prefix='".$ccode."' limit 1";
					$r_check_exist=mysql_query($q_check_exist);
					if(mysql_num_rows($r_check_exist)==0)
					{
						$sql="insert into tariffs (id_tariff,description, prefix, voice_rate) values ('".$t_id."','".$cntry."','".$ccode."','".$teriff_rate."')";
						mysql_query($sql) or $err= mysql_error();
					}
					else
					{
						$rw=mysql_fetch_array($r_check_exist);
						$sql="update tariffs set voice_rate='".$teriff_rate."',description='".$cntry."' where id_tariff='".$t_id."' and prefix='".$ccode."'";
						mysql_query($sql);
					}
				}
				
			}
		}
		unlink($contact_file);
		//die(); 
	}
	
	function download_tariff_rates($t_id)
	{
		error_reporting(0);
		require_once 'PHPExcel.php';
		if(!$this->check_user() && !$this->check_reseller() && !$this->check_admin())
			$this->expire();
		
		ini_set('memory_limit', '512M');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Country')->setCellValue('B1', 'Country Code')
					->setCellValue('C1', 'Tariff Rate');
		$objPHPExcel->getActiveSheet()->setTitle('Tariff Rates');
		$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);// for Country Names
		$objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFont()->applyFromArray(array('name'=>'Arial','bold'=> true,'size'=> 10));
		$q_check_exist="select * from tariffs where id_tariff='".$t_id."'";
		$r_check_exist=mysql_query($q_check_exist);
		if(mysql_num_rows($r_check_exist))
		{
			$i=2;
			while($row=mysql_fetch_array($r_check_exist))
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$i", $row['description'])->setCellValue("B$i", $row['prefix'])
					->setCellValue("C$i", $row['voice_rate']);
				$i++;
			}
		}
		header('Content-Type: application/x-msexcel ');//$ctype="application/vnd.excel";x-msexcel 
		header('Content-Disposition: attachment;filename=rateplan.xls');
		header('Cache-Control: max-age=0');
		// Redirect output to a client's web browser (Excel5)	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//Excel5
		//ob_end_clean();
		$objWriter->save('php://output');
		 
		exit();
	}
	
	function updateSta($id, $val)
	{
		if($id!="11"||$id!="22"||$id!="20"){
			$dbh=$this->connect_db();
			$query="update clientsshared set status=".$val." where id_client=".$id;
			$s_up = mysql_query($query,$dbh) or die(mysql_error());
			if(!$s_up)
				echo $_SESSION['msg']="Client Status Could Not Be Updated";
			else
				echo $_SESSION['msg']="Client Status Updated Successfully";
			$query="select id_client from clientsshared where id_reseller = ".$id;
			$s_sel = mysql_query($query,$dbh) or die(mysql_error());
			mysql_close($dbh);
			while ( $row_sel = mysql_fetch_array($s_sel))
			{
				if(mysql_num_rows($s_sel) > 0)
					$this->updateSta($row_sel['id_client'], $val);
			}
		}
	
	}
	
	function isConfirm($userid){
		$dbh=$this->connect_db();
		$result=mysql_query("select confirm from contact where userid='".$userid."' and confirm =1",$dbh) or die(mysql_error());
		if(mysql_num_rows($result)>0) //If information exists
		{
			$get_userinfo=mysql_fetch_array($result);
			$confirm=$get_userinfo['confirm'];
		}
		else
			$confirm=0;
		mysql_close($dbh);	
		return $confirm;
	}
	
	function edit_default_route()
	{
		if(!$this->check_admin())
			$this->expire();
		if(isset($_REQUEST['submit']))
		{
			$sql_check_unique="select * from dialplan where dialplan_planid='".$_REQUEST['dial_plan']."' and dialplan_prefix='".$_REQUEST['prefix']."'";
			mail("indoreankita@gmail.com","select dialplan",$sql_check_unique);
			$dbh=$this->connect_db();
			$result_check_unique=mysql_query($sql_check_unique,$dbh);
			mysql_close($dbh);
			mail("indoreankita@gmail.com","dial count",mysql_num_rows($result_check_unique));
			if(mysql_num_rows($result_check_unique)==0)
			{
				$sql="insert into dialplan(dialplan_planid,dialplan_prefix,dialplan_routeid) values('".$_REQUEST['dial_plan']."','".$_REQUEST['prefix']."','".$_REQUEST['route']."') on duplicate key update dialplan_routeid='".$_REQUEST['route']."'";
				mail("indoreankita@gmail.com","insert dialplan",$sql);
				$dbh=$this->connect_db();
				$result=mysql_query($sql,$dbh);
				mysql_close($dbh);
				if(!$result)
					echo "Prefix could not be added/updated.";
				else
					echo "Prefix Added/Update Successfully.";
			}
			else
				echo "Duplicate Entry.";			
		}
		else if(isset($_REQUEST['delete']))
		{
			$sql_del="delete from dialplan where dialplan_pid=".$_REQUEST['id'];
			$dbh=$this->connect_db();
			$res_del=mysql_query($sql_del,$dbh);
			if(!$res_del)
				echo "Record could not be deleted.";
			else
				echo "Record deleted successfully.";
		}
		else if(isset($_REQUEST['update']) && $_REQUEST['update']==1)
		{
			$sql_upd="update dialplan set dialplan_routeid=".$_REQUEST['route']." where dialplan_pid=".$_REQUEST['dp_pid'];
			mail("indoreankita@gmail.com","test query",$sql_upd);
			$dbh=$this->connect_db();
			$res_upd=mysql_query($sql_upd,$dbh);
			if(!$res_upd)
				echo "Record could not be updated.";
			else
				echo "Record updated successfully.";
		}
	}
	
	function load_route_name($q,$limit)
	{
		$dbh=$this->connect_db();
		$sql="Select route_name from newroutes where route_name like '".$q."%' or route_name like '%".$q."' or route_name like '%".$q."%' limit ".$limit;
		$result=mysql_query($sql,$dbh) or $error= (mysql_error());
		mysql_close($dbh);
		if (!$result)
			die ("Fatal Error in Loading User List # 3");	
		return $result;
	}

}//end of class

$pro_obj	=	new profile_class();//class object
?>