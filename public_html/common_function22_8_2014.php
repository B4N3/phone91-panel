<?php
include_once 'SuperMySQLi.php';
class commonFunction extends SuperMySQLi 
{

	//define class variable
	 var  $errorHandler;
     var  $querry;

      public function __construct() 
    {
//        $this->db = new SuperMySQLi('localhost', 'voipswitchuser', '+4H8ZXcSyWn7CuX*', 'voipswitch');
        
        if(HOST_NAME == TESTING_SERVER_NAME || HOST_NAME == TESTING_SERVER_NAME1 )
            $this->db = new SuperMySQLi('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8', 'voip91_switch');
        else
            $this->db = new SuperMySQLi('localhost', 'phone91', 'yHqbaw4zRWrUWtp8', 'voip');
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
	*
	*/
	function getDomainEmail()
	{

		
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




}




?>