<?php
include_once 'SuperMySQLi.php';
class commonFunction extends SuperMySQLi
{
     var  $errorHandler;
     var  $querry;
	//define class variable

    public function __construct() 
    {

            $this->db = new SuperMySQLi(CONN_HOSTNAME, CONN_USER, CONN_PASSWORD, CONN_DBNAME);
    }

    function __destruct()
    {
        unset($this->db);
    }
    
	/**
	*@author Ankit Patidar <ankitpatidar@hostnsoft.com>
	*@since 14/05/2014
	*@param int $userId
	*@uses function use to get beforeLoginFlag
	*@return int
	*/
	function getBeforeLoginFlag($userId)
	{

		if(empty($userId) || preg_match(NOTNUM_REGX, $userId))
			return 0;

		$userId = $this->db->real_escape_string($userId);

		$result = $this->selectData("beforeLoginFlag", "91_userLogin"," userId = ".$userId);

                
                
        if($result && $result->num_rows > 0)
        {  
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $loginFlag = $row['beforeLoginFlag'];
            if($loginFlag == 2)
            	return 1;
            else
            	return 0;
        }
        else
            return 0;

	}

	/**
	*@author Ankit Patidar <ankitpatidar@hostnsoft.com>
	*@since 14/05/2014
	*@param int $userId
	*@uses function use to get payment gateway details
	*@return int
	*/
	function getPGDetails($userId)
	{
		$data = array();
		if(empty($userId) || preg_match(NOTNUM_REGX,$userId)) 
	    {
	      return $data;
	    }

	    $table = '91_PGDetails';

	    $result = $this->selectData('merchantId,status',$table,'userId='.$userId.' and type = 1');

	    
	    if(!$result || $result->num_rows == 0)
	    {
	    
	      return $data;
	    }

	    $row = $result->fetch_array(MYSQLI_ASSOC);


	    $data['merchantId'] = $row['merchantId'];
	    $data['status'] = $row['status'];

	    return $data;

	}

	/**
	*@author Ankit Patidar <ankitpatidar@hostnsoft.com>
    *@since 12/6/2014
    *@abstract from api_sameer
    *@param int $userId
    *@param int $type
    *@return string/array
	*/
	function getResellerDomain($userId,$type=1)
	{
        if(preg_match(NOTNUM_REGX, $userId) || $userId == "")
                return 0;
        
        
        $result = $this->selectData("*", "91_domainDetails","resellerId=".$userId.' limit 1');
        
       // echo $this->querry;
        
        if($result && $result->num_rows > 0)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if($type == 1)
                $result = $row['domainName'];
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


    /**
    *@author Ankit Patidar <ankitpatidar@hostnsoft.com>
    *@abstract called from api_sameer
    *@param int $userId 
    *@param int $type
    *@return int
    *@uses funtion to get domainResellerId of user
    */
    function getDomainResellerIdFromVerifiedNum($userId,$type=1)
    {
        if(preg_match(NOTNUM_REGX, $userId) || $userId == "")
                return 0;
        
        
        $result = $this->selectData("*", "91_verifiedNumbers","userId=".$userId);
        
       // echo $this->querry;
        
        if($result && $result->num_rows > 0)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if($type == 1)
                $result = $row['domainResellerId'];
            elseif($type == 2)
                $result = $row;
            else
                $result = 0;
        }
        else 
        {
            trigger_error('problem while get domainResellerId,qur:'.$this->querry);
            $result = 0;
        }
              
        return $result;

    }



	  /**
     * @author sudhir pandey <sudhir@hostnsoft.com>
     * @since 02-08-2013
     * @filesource
     * @modified by: nidhi<nidhi@walkover.in> 
     * @date  18/12/2013
     * @details ::insert userdetail into database  .
     * @return name,userName,currencyId
     */
    
    function getNameAndUserName($toUser)
    {
        #- Insert userdetail into database 
        $table = '91_manageClient';
        
        #- Condition For finding user detail
        $condition = "userId = '" . $toUser . "'";

        $result = $this->selectData( '*', $table, $condition );

        if($result->num_rows > 0)  #- log error
        {
            $row = $result->fetch_array(MYSQL_ASSOC);
            isset($row['name'])? $name = $row['name'] : $name ='';
            isset($row['userName'])? $userName = $row['userName'] : $userName ='';
            isset($row['currencyId'])? $currencyId = $row['currencyId'] : $currencyId ='';
            isset($row['resellerId'])? $resellerId = $row['resellerId'] : $resellerId ='';
            
            return array( "name" => $name, "userName" => $userName , "currencyId" => $currencyId,"resellerId" =>$resellerId);
        }
        else
        {
          trigger_error('Problen while get details for manage client,condition:'.$condition);  
        }
    }
    
       /**
     *@author Ankit Patidar <ankitpatidar@hostnsoft.com> 
     *@since 14/4/2014
     *@param int userType 0 for user and 1 for account manager
     *@filesource
     *@return string 
     */
    function getUserOrAcmUserName($userType = 0,$userId)
    {
        if(!is_numeric($userId))
        {
            return array();
        }
        
        
        if($userType == 0)
        {
            //get userName
            $userDetail = $this->getNameAndUserName($userId);
            $userData['userName'] = $userDetail['userName'];
            $userData['name'] = $userDetail['name'];
            return $userData;
        }
        else if($userType == 1)
        {
            //get account managet username
            include_once(CLASS_DIR.'account_manager_class.php');
            $acmObj = new Account_manager_class();
            $resultAcm = $acmObj->getAcmName($userId);
            $userData['userName'] = $resultAcm['userName']."(A/c Manager)";
            $userData['name'] = $resultAcm['fullName']."(A/c Manager)";
            return $userData;
        }
        else
            return array();
        
        
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
    
    
    function getLocationInfoByIp()
    {

	    $ip  = $this->getUserIP();

	    $result  = array('countryCode'=>'','countryIso'=>'', 'city'=>'','state' =>'');

	    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));   

	    if($ip_data && $ip_data->geoplugin_countryName != null)
	    {

		//get country code
		$res = $this->selectData('prefix',"91_countries","iso LIKE '$ip_data->geoplugin_countryCode%'");
		
		$result['countryCode'] = '';
		
		if($res && $res->num_rows == 1)
		{
		    $response = $res->fetch_array(MYSQLI_ASSOC);
		    $result['countryCode'] = $response['prefix'] ;
		}
		
		
		$result['countryIso'] =  $ip_data->geoplugin_countryCode;

		$result['state'] = $ip_data->geoplugin_regionName;	

		$result['city'] = $ip_data->geoplugin_city;

	    }

	    return json_encode($result);

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
    * @method use for update data into database
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
        $this->errorHandler = $this->db->error;
        
        //log errors
        if(!$result)
        {
            trigger_error('problem while update '.$table.' query:'.$query.' backTrace:'.  debug_backtrace());
        
            return 0;
        }
        
        return $result;
    }
    
    
    public function insertWithEncryption($table,$data)
    {
	if(empty($data) || empty($table))
	    return 0;
	$query = 'INSERT INTO '.$table;
	$columns = array();
	$values = array();
	foreach ($data as $key => $value) 
	{
	    $columns[]=$key;
	    if($key == 'password')
		$values[]='AES_ENCRYPT(\''.$value.'\',\''.ENCRYPT_KEY.'\')';
	    else
		$values[]="'$value'";
	}
	
	$query.='('.implode(',', $columns).') VALUES(';
	$query.=implode(',',$values).')';
	
	$res = $this->db->query($query);
	
	return $res;
	
	
    }
    
    
    function updateWithEncryption($table,$data,$where="1")
    {
	if(empty($data) || empty($table))
	    return 0;
	$values = 'UPDATE '.$table.' SET';
	
	
	foreach ($data as $key => $value) 
	{
	    
	    if($key == 'password' || $key == 'passwd' || $key == 'acmCode')
		$values.=' '.$key.'=AES_ENCRYPT(\''.$value.'\',\''.ENCRYPT_KEY.'\'),';
	    else
		$values.=' '.$key."='$value',";
	}
	
	$query=substr($values,0, -1);
	
	$query.=' WHERE '.$where;
	$this->querry = $query;
	$res = $this->db->query($query);
	
	return $res;
	
	
    }
	

}

 //class object


?>