<?php

class commonFunction extends fun
{

	//define class variable


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



}




?>