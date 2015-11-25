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
}//end of class
?>