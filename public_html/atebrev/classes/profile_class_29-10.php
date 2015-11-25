<?php
include dirname(dirname(__FILE__)).'/config.php';

include_once("classes/general_class.php");
//include_once("classes/validation_class.php");

class profile_class extends fun
{
        #modified by sudhir pandey (sudhir@hostnsoft.com)
        #modify date 24-07-2013
        #function use for get currency name form currency table 
	function getCurrency($id_currency)
	{
            #table name 
            $table = '91_currencyDesc';
	    $this->db->select('currency')->from($table)->where("currencyId = '" . $id_currency . "' ");
            $this->db->getQuery();
	    $result = $this->db->execute();
	    if ($result->num_rows > 0) {
                while($row= $result->fetch_array(MYSQL_ASSOC)) {
                    $currencyName = $row['currency'];
                }
            }
                
		return $currencyName;
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
	function loadTotalUsers($utype,$user_id)
	{
            
            //@Author : Rahul
            
            		$table = '91_userBalance';
			$this->db->select('count(1)')->from($table)->where("resellerId = '" . $user_id . "' ");
//                        echo $this->db->getQuery();
			$result = $this->db->execute();
			//	    var_dump($result);
			// processing the query result
			if ($result->num_rows > 0) {
				while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
                                    $count=$row["count(1)"];       
                                }
                          }
			else
			    $count=0;
	
//                //query to count users for reseller
//		$sql="SELECT COUNT(*) FROM clientsshared WHERE id_reseller='".$user_id."' ";
//		//create connection
//		$dbh=$this->connect_db();
//		$result=mysql_query($sql,$dbh);
//		mysql_close($dbh);
//		if (!$result)
//			die ("Fatal Error in Loading Total User List");
//		$row=mysql_fetch_row($result);
		return $count;
	}
	
	
	# Function created by Rohan Kumar for phone880 new search client
	#------------------------------------------------------------------------------
	# Function Used for counting total number of searched records
	function loadTotalSearchedUsers($user_id,$q, $unique = null)
	{       $condition = "like '%".$q."%'";
		if($unique)
		    $condition = "='".$q."'";
		$sql="Select count(*) from 91_manageClient where resellerId='".$user_id."' and lower(userName)".$condition ." or lower(name) ".$condition ;
		
                $result = $this->db->query($sql);
                
		if (!$result)
			die ("Fatal Error in Loading Total Number of User");
                else{
                while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
                                    $resultArray[]=$row;					
				}
                }
		return $row;
	}
        # Function Used for searched records with start limit and limit
	function loadSearchedUsers($user_id,$q,$start=0,$limit=10, $unique)
	    {   $condition = "like '%".$q."%'";
		if($unique)
			$condition = "='".$q."'";
//		$dbh=$this->connect_db();
            
                if(is_numeric($q))
                {
                    $sql_number = "select * FROM 91_tempNumbers where tempNumber ".$condition;
                    
                    
		
                $resNum = $this->db->query($sql_number);
                
                
                if ($resNum->num_rows > 0) {
				
				while ($row0= $resNum->fetch_array(MYSQL_ASSOC) ) {
                                    $resultArray[]=$row0;					
				}
			}
                    else
			die ("Fatal Error in Loading Total Number of User");
                    
//                    $row0 = mysql_fetch_row();
                    
                    $sql="SELECT * FROM 91_manageClient where resellerId = $user_id AND userId = $row0[0] UNION DISTINCT Select * from 91_manageClient where resellerId='".$user_id."' and lower(userName)".$condition." order by userName asc limit $start, $limit";
                }
                elseif(strpos($q,"@"))
                {
                    
                  $sql_mail = "select * FROM  91_verifiedEmails where email like '%".$q."%'";
                   $resMail = $this->db->query($sql_mail);
                
                
                if ($resNum->num_rows > 0) {
				
				while ($row0= $resNum->fetch_array(MYSQL_ASSOC) ) {
                                    $resultArray[]=$row0;					
				}
			}
                   
                   // else//
			//die ("Fatal Error in Loading Total Number of User");
//                    $row0 = mysql_fetch_row($resNum);
                    
//                    $sql="SELECT * FROM clientsshared where id_reseller = $user_id AND id_client = $row0[0] UNION DISTINCT Select * from clientsshared where id_reseller='".$user_id."' and lower(login)".$condition." order by login asc limit $start, $limit";  
                $sql="SELECT * FROM 91_manageClient where resellerId = $user_id AND userId = $row0[0] UNION DISTINCT Select * from 91_manageClient where resellerId='".$user_id."' and lower(userName)".$condition." order by userName asc limit $start, $limit";
                    
                }
                elseif(strlen($q)>0)
                {
                    $sql="Select * from 91_manageClient where resellerId='".$user_id."' and lower(userName) ".$condition."  or lower(name) ".$condition ." order by userName asc limit $start, $limit";
                }
//		$sql="Select * from clientsshared where id_reseller='".$user_id."' and lower(login) like '%".$q."%' order by login asc limit $start, $limit";
//                echo $sql;	
                
                 $result = $this->db->query($sql);
                
                
                if ($resNum->num_rows > 0) {
				
				while ($row0= $resNum->fetch_array(MYSQL_ASSOC) ) {
                                    $resultArray[]=$row0;					
				}
			}
                        
		
//		mysql_close($dbh);
		if (!$result)
			die ("Fatal Error in Loading Total User List");
		//$row=mysql_fetch_row($result);
		return $result;
	}
	# Function Used for searched records with start limit and limit
	function load_searched_users1($user_id,$q,$start=0,$limit=10)
	{
            $dbh=$this->connect_db();
                
            //if search by number
            if(is_numeric($q))
            {
                //query for contact no.
                $sql_number = "select * FROM contact where contact_no like '%".$q."%'";
                $resNum = mysql_query($sql_number,$dbh);
                if (!$resNum)
                    die ("Fatal Error in Loading Total Number of User");
                $row0 = mysql_fetch_row($resNum);
                //subquery that will use in most of queries
                $subQuery = "SELECT * FROM clientsshared WHERE id_reseller = $user_id AND";
                //query if no. in login id
                $sql="$subQuery id_client = $row0[0] UNION DISTINCT $subQuery lower(login) LIKE '%".$q."%' ORDER BY login ASC LIMIT $start, $limit";
            }
            elseif(strpos($q,"@"))//search by mail
            {
                $sql_mail = "SELECT * FROM contact WHERE email LIKE '%".$q."%'";
                $resMail = mysql_query($sql_mail,$dbh);
                if (!$resMail)
                    die ("Fatal Error in Loading Total Number of User");
                $row0 = mysql_fetch_row($resMail);
                //if login id contain @ symbol
                $sql="$subQuery id_client = $row0[0] UNION DISTINCT $subQuery lower(login) LIKE '%".$q."%' ORDER BY login ASC LIMIT $start, $limit";  
            }
            elseif(strlen($q)>0)
            {
                $sql="$subQuery lower(login) LIKE '%".$q."%' ORDER BY login ASC LIMIT $start, $limit";
            }
		
            $result=mysql_query($sql,$dbh);
            mysql_close($dbh);
            if (!$result)
                    die ("Fatal Error in Loading Total User List");
            return $result;
	}
        
        
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
	function loadUsers($q,$user_id,$start_limit,$limit,$sort)
	{
            
            $condition='';
                if($sort==0)
			$order="lower(userName)";
		else if($sort==1)
			$order="balance";
		else if($sort==2)
			$order="balance desc";	
		else if($sort==3)
			$order="  userId  desc ";
                if(isset($q) &&  strlen($q)>1)
                        $condition=" and lower(userName) like '%".$q."%' ";
		
		$sql="Select * from 91_manageClient where resellerId='".$user_id."' $condition order by ".$order." limit ".$start_limit.",".$limit;
		
                $result = $this->db->query($sql);
                
                
                if ($result->num_rows > 0) {
				
				while ($row= $result->fetch_array(MYSQL_ASSOC) ) {
                                    $resultArray[]=$row;					
				}
			}
                return $resultArray;
	}
        
        #function to load latest signups
        function loadLatestsignup($utype,$user_id,$start_limit,$limit,$sort)
        {
                //set order by sort value
		if($sort==0)
			$order="id_client DESC";
		else if($sort==1)
			$order="account_state DESC";
		else if($sort==2)
			$order="account_state ASC";
                else if($sort==3)
                        $order="id_currency ASC";
                else if($sort==4)
                        $order="id_currency DESC";
		
                //query to fetch data
		$sql="SELECT * FROM clientsshared WHERE id_reseller='".$user_id."' ORDER BY ".$order."  LIMIT ".$start_limit.",".$limit;
		//create connection to db
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if (!$result)
			die ("Fatal Error in Loading User List # 3");
		
		return $result;
	}
	
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

        #modified by sudhir pandey (sudhir@hostnsoft.com)
        #modify date 24-07-2013
        #function use for find user balance and currency name (by call getcurrecy funtion)
 	function getUserBalance($user_id)//return user balance from new ms_user_balance Table
	{
            #table name 
            $table = '91_userBalance';
            
	    $this->db->select('*')->from($table)->where("userId = '" . $user_id . "' ");
            $this->db->getQuery();
	    $result = $this->db->execute();
	    if ($result->num_rows > 0) {
                while($row= $result->fetch_array(MYSQL_ASSOC)) {
                    $balance = $row['balance'];
                    $currencyId = $row['currencyId'];
                }
            }
            #find currency name form currency name table 
            $currencyName = $this->getCurrency($currencyId);
            
            return $balance;
            
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
            $dbFunObj = new fun();
            
		$substr="";
		if($select_type!=""&&$select_field!="")
			$substr="order by ".$select_field." ".$select_type;
		$q_rp_detail="select * from 91_tariffs where tariffId=".$id_tariff." ".$substr." limit ".$first_limit.",".$limit;
                $r_rp_detail = $dbFunObj->db->query($q_rp_detail);
		return $r_rp_detail;
	}


	function total_tariff_rates($id_tariff)
	{
            $dbFunObj = new fun();
            $q_rp_detail="select count(*) as cnt from 91_tariffs where tariffId=".$id_tariff;

            $r_rp_detail = $dbFunObj->db->query($q_rp_detail);
            
            

    //		$dbh=$this->connect_db();
    //		$r_rp_detail=mysql_query($q_rp_detail,$dbh);
    //		mysql_close($dbh);
            $row_rp_detail=$r_rp_detail->fetch_array(MYSQLI_BOTH);
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