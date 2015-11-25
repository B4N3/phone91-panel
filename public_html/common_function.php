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
     * @last updated by Ankit Patidar <ankitpatidar@hostnsoft.com> on 10/10/2014 validation added
     * @details ::insert userdetail into database  .
     * @return name,userName,currencyId
     */
    
    function getNameAndUserName($toUser)
    {
	 if(!preg_match('/^[0-9]+$/', $toUser))
        {
            return array("status"=>"error","msg"=>"Please select a valid user!");
        } 
	
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
	  return array("status"=>"error","msg"=>"Record not found for this user!");
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
            $this->sendErrorMail("sudhir@hostnsoft.com", "if:".$ip);
        } 
        else 
        {
            $ip = $_SERVER['REMOTE_ADDR'];
            $this->sendErrorMail("sudhir@hostnsoft.com", "else:".$ip);
        }
        return $ip;
    }
    
    
    function getLocationInfoByIp()
    {

	   $ip  = $this->getUserIP();
          // $ip = '111.118.250.236';
        
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
		
		$result['countryName'] =  $ip_data->geoplugin_countryName;
		$result['countryIso'] =  $ip_data->geoplugin_countryCode;

		$result['state'] = $ip_data->geoplugin_regionName;	

		$result['city'] = $ip_data->geoplugin_city; //geoplugin_countryName

	    }
                $this->sendErrorMail("sudhir@hostnsoft.com", "ip :".$ip.json_encode($result));
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
     * @author sudhir pandey and ankit patidar
     * @since 4-11-2014
     * @desc function use to get user currect time according to time zone
     * @param $date Time :  YYYY-mm-dd HH:ii:ss  , $userTimeZone :  +3:40 
     * 
     */
    function convertToUserTimeZone($dateTime, $userTimeZone = NULL) {
                
        $sign = substr($userTimeZone, 0, 1);

        $time = substr($userTimeZone, 1);
        $explTime = explode(':', $time);
        $offset = ($explTime[0] * 3600) + ($explTime[1] * 60);
               
        if ($sign == "-")
            $timeNdate = gmdate(strtotime($dateTime) - $offset);
        else
            $timeNdate = gmdate(strtotime($dateTime) + $offset);

        return date('Y-m-d H:i:s',$timeNdate);
    }
    
    function curl_file_get_contents($url)
    {
	$curl = curl_init();
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

	curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.	

	curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
	curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//To stop cURL from verifying the peer's certificate.

	$contents = curl_exec($curl);
	curl_close($curl);
	return $contents;
    }


    /**
     * @author ANkit Patidar <ankitpatidar@hostnsoft.com>
     * @since 20/11/2014
     * @param array $postData
     * @uses fucntion to get short url
     * @return json
     */
    function getShortUrl($postData)
    {
        $curlObj = curl_init();

        $jsonData = json_encode($postData);

        curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($curlObj);

        //change the response json string to object
        $json = json_decode($response);
        curl_close($curlObj);

        return $json;
    }
    
    function getResponseByCurl($url,$header=array(),$postFields = array(),$cred=array())
    {
	
	$curl = curl_init();
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
	$postString = '';
	curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
	
	if(!empty($header))
	{
	    //curl_setopt($curl,CURLOPT_HEADER,TRUE);
	    curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
	}
	
	if(!empty($postFields))
	{
	     //url-ify the data for the POST
	    foreach($postFields as $key=>$value) 
	    { 
		$postString .= $key.'='.$value.'&'; 
		
	    }
//	    rtrim($postString, '&');
	    $postString = substr($postString, 0, -1);
	   
	    curl_setopt($curl,CURLOPT_POST, count($postFields));
	    curl_setopt($curl,CURLOPT_POSTFIELDS, "$postString");
	}
	
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	if(!empty($cred))
	{
	    curl_setopt($curl, CURLOPT_USERPWD, $cred[0].":".$cred[1]);
	}
	
	curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.	

	curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
	//curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//To stop cURL from verifying the peer's certificate.

	$contents = curl_exec($curl);
	curl_close($curl);
	return $contents;
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 8/12/2014
     * @uses function to get recharge amount range
     * @param type $idTarrif
     * @return int
     */
    function getRechargeRange($idTarrif)
    {
	$this->status = 'error';
	$this->msg = 'whoops,internal error,Please try again later!';
	if(preg_match(NOTNUM_REGX, $idTarrif))
	{
	   trigger_error('Invalid tariff id'); 
	   return 0; 
	}
	
	//get couttency id
	$res = $this->getOutputCurrency($idTarrif);
	
	
	if($res == 0)
	{
	    trigger_error('Problem while get output currency!');
	    return 0;
	}
	
	//get range
	 $result = $this->selectData('minimum,maximum,step', '91_rechargeAmt','currencyId='.$res);
	 
	 if(!$result || $result->num_rows == 0)
	 {
	    trigger_error('Problem while get recharge amount,querry:!'.$this->querry);
	    return 0;
	 }
	 
        return $result;
	
    }

}

 //class object


?>