<?php
/**
 * @Author Rahul <rahul@hostnsoft.com>
 * @createdDate 03-06-13
 * @modified by sudhir <sudhir@hostnsoft.com>
 * @details class use to reseller manage pin (create pin,batch pin generate,recharge by pin etc.)  
 * 
 */
include dirname(dirname(__FILE__)).'/config.php';
class pin_class extends fun
{
      
        /**
         * @last modified by <sudhir@hostnsoft.com> on 19/07/2013
         * @description function use to add pin batch
         * @param array $request
         * @param int $userid
         * @return json
         */
        function generateBatch($request,$userid)
	{
		
		extract($request); //$bname,$totalPins,$expiry_date,$amountPerPin,$pType,$partialAmount,$currency,$tariff_Plan
                
                #check total no of pins is numeric or not 
                if (!preg_match("/^[0-9]+$/", $totalPins))
                {  
                    $response["msg"]="Number Of PINs Are Not Numeric !";
                    $response["msgtype"]="error";
                    return json_encode($response);
                }
                
                if (strlen($totalPins) > 3)
                {
                    $response["msg"]="max limit of generate pin is 3 digits!";
                    $response["msgtype"]="error";
                    return json_encode($response);
                   
                }
                if($totalPins < 1){
                    $response["msg"]="pin should be greater then 0!";
                    $response["msgtype"]="error";
                    return json_encode($response);
                }
                
                #check amount of  per pins is numeric or not 
                if (!preg_match("/^[0-9]+$/", $amountPerPin))
                {  
                    $response["msg"]="Amount Per PIN are Not Numeric or negative value!";
                    $response["msgtype"]="error";
                    return json_encode($response);
                }
                
                if (strlen($amountPerPin) > 4)
                {
                    $response["msg"]="max limit of Amount per pin is 4 digits!";
                    $response["msgtype"]="error";
                    return json_encode($response);
                   
                }
                
                #check batch name validation
                if($bname == '')
                {
                    $response["msg"]="Batch Name Required !";
                    $response["msgtype"]="error";
                    return json_encode($response);
                }
                if (!preg_match("/^[a-zA-Z_@-]{2,25}$/", $bname))
                {  
                    $response["msg"]="Please Enter valide batch name !";
                    $response["msgtype"]="error";
                    return json_encode($response);
                    
                }
                
                if((int)$tariff_Plan == 0)
                {
                    $response["msg"]="Select Tariff Plan !";
                    $response["msgtype"]="error";
                    return json_encode($response);
                }
                
                if(strtotime(date('Y-m-d H:i:s',strtotime($expiry_date))) < strtotime(date('Y-m-d H:i:s'))){
                    $response["msg"]="Please Select valid expiry date!";
                    $response["msgtype"]="error";
                    return json_encode($response);
                }
                
                if(isset($listenRemainingTime) && $listenRemainingTime =='on'){
                    $listenTime = 1;
                }else
                    $listenTime = 0;
                
                $bname = $this->db->real_escape_string($bname);
                $pinFormate = $this->db->real_escape_string($pinFormate);
                
                
                #value store in database 
                $data=array("userId"=>(int)$userid,"batchName"=>$bname,"noOfPins"=>(int)$totalPins,"pinFormat"=>$pinFormate,"amountPerPin"=>(int)$amountPerPin,"tariffId"=>(int)$tariff_Plan,"expiryDate"=>date('Y-m-d H:i:s',strtotime($expiry_date)),"paymentType"=>$pType,"partialAmount"=>(int)$partialAmount,"listenTime"=>$listenTime); 
                #table name 
                $table = '91_pin';
		#insert query (insert data into 91_pin table )
                $this->db->insert($table, $data);	
                $qur = $this->db->getQuery();
		$result = $this->db->execute();
                
                #save total no of pin with pincode 
		$batchId="";
		if($result)
		{
                        #last inserted id 
			$batchId=$this->db->insert_id;
                        #variabel use to store total no to row inserted in pindetail table 	
			$totalInsertedRow=0;
			for($totalPins;$totalPins>0;$totalPins--)
			{
				$pinCode= $this->createNumeric_pin(14);
                                #data for store in pin details  into table 
				$data = array("batchId"=>(int)$batchId, "pincode"=>$pinCode,"status"=>0);
				$table = '91_pinDetails';
		                #insert data into details table
				$this->db->insert($table, $data);
                                $qur = $this->db->getQuery();
				$result = $this->db->execute();
                                
                                //log errors
                                if(!$result)
                                    trigger_error('Problem while get pin details,userid:'.$userid);
                                    
				$totalInsertedRow +=$this->db->affected_rows;
			}
                        $pindata = $this->getMyPin($userid);
                        $pinData = json_decode($pindata, true);  

                        $str = $pinData['myPins'];
                        $userName = $pinData['userName'];
                        
                        #array for return response
			$response["msg"]="Batch Created Successfully";
			$response["msgtype"]="success";
			$response["batchId"]=$batchId;
			$response["totalInsertedRow"]=$totalInsertedRow;
                        $response["batch"]=$str;
                        $response["userName"] = $userName;
                        
		}
		else
		{
                        //log error 
                        trigger_error('Error While Creating Batch,userid:'.$userid);
			$response["msg"]="Error While Creating Batch";
			$response["msgtype"]="error";
		}
		
		return json_encode($response);
		
	}
        
        #created by sudhir pandey (sudhir@hostnsoft.com)
        #creation date 20/07/2013
        #function use to edit pin batch detail 
        function editPinBatch($parm,$userid,$isAcm = 0)
        {
            
            extract($parm); //$bname,$totalPins,$expiry_date,$amountPerPin,$pType,$partialAmount,$currency,$tariff_Plan
            $table='91_pin';
            
            if (!preg_match("/^[a-zA-Z_@-]{2,20}$/", $batchName))
            {  
                   return json_encode(array("msgtype"=>"error","msg"=>"Please Enter valide batch name!"));
            }
            
            if(strtotime(date('Y-m-d H:i:s',strtotime($batchExpiry))) < strtotime(date('Y-m-d H:i:s'))){
                return json_encode(array("msgtype"=>"error","msg"=>"Please Select valid expiry date!"));
            }
            
            if(isset($listenRemainingTime) && $listenRemainingTime =='on'){
                $listenTime = 1;
            }else
                $listenTime = 0;
            
            
            /* if any pin are used from batch then only pin batch name and expiry date will be update 
             * otherwise all details update like (amount per pin, plan ,batch name etc.)
             */
            if(isset($amountperpin))
            {
                #check tariff plan is selected or not 
                if((int)$tariffPlan == 0)
                {
                     return json_encode(array("msgtype"=>"error","msg"=>"Select Tariff Plan !"));
                }
                #check amount of  per pins is numeric or not 
                if (!preg_match("/^[0-9]+$/", $amountperpin))
                {  
                   return json_encode(array("msgtype"=>"error","msg"=>"Please Enter Numeric Value For Amount!"));
                }
                
                if (strlen($amountperpin) > 4)
                {  
                   return json_encode(array("msgtype"=>"error","msg"=>"max limit of Amount per pin is 4 digits!"));
                }
            
                
                $batchName = $this->db->real_escape_string($batchName);




                #value for store in database 
                $data=array("batchName"=>$batchName,"amountPerPin"=>(int)$amountperpin,"tariffId"=>(int)$tariffPlan,"expiryDate"=>date('Y-m-d H:i:s',strtotime($batchExpiry)),"listenTime"=>$listenTime);     
            }
            else
            {            
                #value for store in database 
                $data=array("batchName"=>$batchName,"expiryDate"=>date('Y-m-d H:i:s',strtotime($batchExpiry)),"listenTime"=>$listenTime); 
            }
            
            
          
            if($isAcm == 1){
                $condition ="batchId = '".$batchid."'";
            }else
                $condition = "userId=".$userid." and batchId=".$batchid." ";
            $this->db->update($table, $data)->where($condition);	
            $qur = $this->db->getQuery();
            $result = $this->db->execute();

            $this->updatePinLog($parm,$userid);
            
            //log errors
            if(!$result || $this->db->affected_rows == 0){
                  trigger_error('problem while edit pin batch,query:'.$qur);
                     return json_encode(array("msgtype"=>"error","msg"=>"batch not updated !"));
            }
            
            $pindata = $this->getMyPin($userid);
            $pinData = json_decode($pindata, true);  

            $str = $pinData['myPins'];
            $userName = $pinData['userName'];
            
             return json_encode(array("msgtype"=>"success","msg"=>"Batch Updated Successfully !","batch"=>$str,"userName"=>$userName));
        }
        
        function updatePinLog($parm,$userid){
           
            if($parm['batchName'] != $parm['oldBatchName']){
                $this->accountManagerLog($parm['batchid'],9,$parm['oldBatchName'],$parm['batchName'],$userid,"update pin batch name");
            }
            
            if(strtotime($parm['batchExpiry']) != strtotime($parm['oldBatchExpiry'])){
                $this->accountManagerLog($parm['batchid'],10,$parm['oldBatchExpiry'],$parm['batchExpiry'],$userid,"update pin batch expiry date");
            }
            
            if(isset($parm['tariffPlan'])){
                if($parm['tariffPlan'] != $parm['oldTariffPlan']){
                    $this->accountManagerLog($parm['batchid'],11,$parm['oldTariffPlan'],$parm['tariffPlan'],$userid,"update pin batch plan");
                }
            }
            
            if(isset($parm['amountperpin'])){
                if($parm['amountperpin'] != $parm['oldamountperpin']){
                    $this->accountManagerLog($parm['batchid'],12,$parm['oldamountperpin'],$parm['amountperpin'],$userid,"update amount per pin of pin batch");
                }
            }
            
            
        }
        
        
        #modified by sudhir pandey (sudhir@hostnsoft.com)
        #modify date 19/07/2013
        #function use to get all pin details batch id and userid given 
	function getPinDetails($batchid,$userid,$pageNo = 1,$export = 0,$isAcm = 0)
	{
            
                #array for store all detail of pin 
		$response["batchId"]=$batchid;
		
                #table name 
		$table = '91_pin';
                
                if($isAcm == 1){
                 $condition ="batchId = '".$batchid."'";
                }else
                 $condition ="userId = '" . $userid . "' and batchId = '".$batchid."'";
                #get pin batch detail 
                $this->db->select('*')->from($table)->where($condition);
		$qur = $this->db->getQuery();
                $batch_result = $this->db->execute();
                
                //log errors
                if(!$batch_result)
                    trigger_error('problem while get batch detail,$query:'.$qur);
                
		if ($batch_result->num_rows > 0) 
                {		
			while ($batch_row= $batch_result->fetch_array(MYSQL_ASSOC) ) 
                        {

				$returnResult["id"]=$batch_row["batchId"];
                                $returnResult["userId"]=$batch_row["userId"]; 
				$returnResult["batch_name"]=$batch_row["batchName"];
				$returnResult["total_pin"]=$batch_row["noOfPins"];	
                                $returnResult["tariffId"]=$batch_row["tariffId"];
                                	
				
				$returnResult["created_date"]=$batch_row["createDate"];
				$returnResult["expire_date"]=$batch_row["expiryDate"];
				$returnResult["paymentType"]=$batch_row["paymentType"];
                                $returnResult["amountPerPin"]=$batch_row["amountPerPin"];
                                $returnResult["listenTime"]=$batch_row["listenTime"];
                               
                             
				$batch_details[] = $returnResult;
			}
		}
		else
			$batch_details=array();
                
                #store batch details array data to respinse array 
		$response["batch_details"]=$batch_details;
		
                //take limit to show
                $limit = 15; 
                //get skip for pagination
                $skip = $limit*($pageNo-1); 
                
		#get all pin detail of batch by batchid 
		$table = '91_pinDetails';
		$this->db->select('*')->from($table)->where("batchId = '" . $batchid . "' ORDER BY status ASC")->limit($limit)->offset($skip);
                $qur = $this->db->getQuery();
		$result = $this->db->execute();
                
                 //log errors
                if(!$result)
                    trigger_error('problem while get pin detail,$query:'.$qur);
                
                
                //get total pages
                $this->db->select('*')->from($table)->where("batchId = '" . $batchid . "' ORDER BY status ASC");
                $sql = $this->db->getQuery();
		$resultCnt = $this->db->execute();
                
                if(!$resultCnt)
                    trigger_error('problem while get pin detail count,$query:'.$sql);
                
                $response['totalCount'] = ceil($resultCnt->num_rows/$limit);
               
                if($export == 1){
                    $result = $resultCnt;
                }
                if($result->num_rows > 0) 
                {	
                        while ($row= $result->fetch_array(MYSQL_ASSOC) ) 
                        {
                                $returnResult2["pin_code"]=$row["pincode"];
                                //$returnResult["amount"]=$row["amount"];
                                $returnResult2["status"]=$row["status"];

                                #if status is 1 then pin are used otherwise pin not use
                                if($row["status"]==1)
                                {
                                        $returnResult2["status"]="Used";
                                        $returnResult2["used_date"]=$row["usedDate"];
                                        $usedBy = $this->getuserName($row["usedBy"]);
                                        $returnResult2["userBy"] = $usedBy;
                                }
                                elseif($row["status"]==0){
                                        $returnResult2["status"]="Valid";
                                        $returnResult2["used_date"]=" - ";
                                        $returnResult2["userBy"] = " - ";
                                }

                                        $pin[] = $returnResult2;
                        }
                }
                else
                    $pin=array();			
		
		$response["myPins"]=$pin;
                
		return json_encode($response);
	}
        
        #created by sudhir pandey (sudhir@hostnsoft.com)
        #creation date 11-09-2013
        #function use to get username by userid 
        function getuserName($userId)
        {
            #condition for find username and pin detail 
            $condition = "userId = '" . $userId . "' ";

            #find user name of given id (we can not use session name because userid will change).
            $info = "91_personalInfo";
            $this->db->select('*')->from($info)->where($condition);
            $userInfo = $this->db->execute();
            
            //log errors
            if(!$userInfo)
                trigger_error('problem while get personal info detail,condition:'.$condition);
                
            if ($userInfo->num_rows > 0) 
            {
                $user = $userInfo->fetch_array(MYSQL_ASSOC);
                $userName = $user['name'];             
            } 
            return $userName;
            
        }
	
        #created by sudhir pandey (sudhir@hostnsoft.com)
        #creation  date 08-08-2013
        #function use to searching batch list by batch name
        function searchBatch($parm,$userid,$allbatch = 0)
        {
            
           
            #table name  
            $table = '91_pin';

            //set limit and step
            $limit = 10;
            if(isset($parm['pageNo']) and is_numeric($parm['pageNo']))
            {
                $pageNo = $parm['pageNo'];
            }
            else 
                $pageNo = 1;

            //get skip value
            $skip = $limit*($pageNo-1);

            
            if($allbatch == 0)
            $precondition ="userId = " . $userid;
            else
            $precondition = "1";
            
                
            #check for data is not null
            if(isset($parm['data']))
            {
                if(strlen($parm['data'])>0)
                {
                     $condition = $precondition. " and batchName like '%".$parm['data']."%' ORDER BY batchId DESC ";
                }
                else 
                    $condition = $precondition. " ORDER BY batchId DESC ";
                
                $this->db->select('SQL_CALC_FOUND_ROWS *')->from($table)->where($condition)->limit($limit)->offset($skip);
                $this->db->getQuery();
                
                $result = $this->db->execute();

                //log errors
                if(!$result)
                    trigger_error('problem while get pin detail,condition:'.$condition);


                 //get total rows for above query
                $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
                $countRes = mysqli_fetch_assoc($resultCount);     

                //get total pages
                $pages = ceil($countRes['totalRows']/$limit);

                #call function for get all batch detail by result 
                $batch = $this->searchBatch_sub($result,$userid,$allbatch);

             }
             else
             {
                 $batch = array();
                 $pages = 1;
             }
             

             //return $batch;
             return json_encode(array("batch"=>$batch,'pages' => $pages));
         }
            
                
        
        #created by sudhir pandey (sudhir@hostnsoft.com)
        #creation date 08/08/2013
        #function use for find all batch detail by result  
        function searchBatch_sub($result,$userId,$allbatch = 0)
        {
            if($allbatch == 0){
                #get username by userid because in admin panel username change when all pin batch show 
                $userInfo = $this->getUserInformation($userId,1);
                $userName = $userInfo['userName'];
            }
            
            
            if ($result->num_rows > 0) 
            {
                while ($row= $result->fetch_array(MYSQLI_ASSOC) ) 
                {

                    $returnResult["id"]=$row["batchId"];
                    $returnResult["batch_name"]=$row["batchName"];
                    $returnResult["total_pin"]=$row["noOfPins"];				
                    $returnResult["tariffId"] = $row["tariffId"];
                    #if tariff id given then find plan name 
                    if(isset($row["tariffId"]))
                    {

                       #find tariff plan name 
                       $condition = "tariffId = '".$row['tariffId']."'";  
                       $table="91_plan";
                       $this->db->select('planName')->from($table)->where($condition);
                       $tariffInfo = $this->db->execute();
                       
                       //log error
                       if(!$tariffInfo)
                           trigger_error('problem while get plan info detail,condition:'.$condition);
                       
                       if ($tariffInfo->num_rows > 0) 
                       {
                            $tariff = $tariffInfo->fetch_array(MYSQL_ASSOC);
                            $returnResult["tariff"] = $tariff['planName'];             
                       }
                       
                       #get currency of pin by tariff id 
                       $funobj = new fun();
                       $currency_id = $funobj->getOutputCurrency($row['tariffId']);
                       $returnResult["currency"] = $funobj->getCurrencyViaApc($currency_id,1);

                    }else
                      $returnResult["tariff"]="Plan Name Not Found";

                    $returnResult["created_date"]=$row["createDate"];
                    $returnResult["expire_date"]=$row["expiryDate"];
                    $returnResult["amountPerPin"]=$row["amountPerPin"];
                    $returnResult["paymentType"]=$row["paymentType"];

                    $returnResult["action"] = $row["action"];
                    $returnResult["amountStatus"] = $row["amountStatus"];
                    
                   
                    # if show all batch detail then user name according to created user 
                    if($allbatch == 0){
                       $returnResult["userName"] =  $userName;
                    }else
                    {
                    #get username by userid because in admin panel username change when all pin batch show 
                    $userInfo = $this->getUserInformation($row['userId'],1);
                    $returnResult["userName"] = $userInfo["userName"];
                    }
                    
                    #find total no of pin used
                    $pindetail = '91_pinDetails';
                    
                    $condition = "batchId = '" . $row["batchId"] . "' and status = 1";
                    
                    $this->db->select('*')->from($pindetail)->where($condition);
                    $this->db->getQuery();
                    $usedPin = $this->db->execute();
                    
                    //log error
                    if(!$tariffInfo)
                         trigger_error('problem while get plan info detail,condition:'.$condition);
                       
                    
                    $returnResult['used_pin'] = $usedPin->num_rows;

                    $pin[] = $returnResult;
            }
        }
        else
                $pin=array();

        return $pin;
        }
        
        #modified by sudhir pandey <sudhir@hostnsoft.com>
        #modified date 10-09-2013
        #function use to get pin detail.       
	function getMyPin($userId,$pageNo=1,$allpin = 0)
	{
		
        //take limit to show
        $limit = 10; 
        //get skip for pagination
        $skip = $limit*($pageNo-1); 
        
        
	
      
        #find pin detail 
        $table = '91_pin';
        if($allpin == 1){
	$condition = "ORDER BY batchId DESC";
        }else
        $condition = "userId = '" . $userId . "'ORDER BY batchId DESC";   
        
        
        $this->db->select('SQL_CALC_FOUND_ROWS *')->from($table)->where($condition)->limit($limit)->offset($skip);
	
        $result = $this->db->execute();
            
        //log error 
        if(!$result)
        {
            trigger_error('problem while get pin detail,condition:'.$condition);
        }
          
         //get total rows for above query
        $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
        
        $countRes = mysqli_fetch_assoc($resultCount);       
		// processing the query result
		if ($result->num_rows > 0) 
        {	
			$pages = ceil($countRes['totalRows'] / $limit);
			$response["pages"]=$pages;
					
			
			
//                        var_dump($result->fetch_array(MYSQLI_ASSOC));
//                        die('paek');
                        trigger_error('pin detail,condition:'.$condition.' SQL:'.$sql);
                         //log error 
                        if(!$result)
                        {
                            trigger_error('problem while get pin detail,condition:'.$condition);
                        }
                        
                        
			$pin = $this->searchBatch_sub($result,$userId);
		}
		else
                {
			$pin=array();
			$response["totalpage"]=0;
		}
		$response["myPins"]=$pin;
                       
		return json_encode($response);
	}
	
	public function findByPin($pin, $start)
        {
	    
	    $totalresult = $this->db->query("SELECT recharge_pin. * , recharge_pin_code.pin_code, tariffsnames.description
					    FROM recharge_pin_code 
					    INNER JOIN recharge_pin ON recharge_pin.id = recharge_pin_code.batch_id
					    INNER JOIN tariffsnames ON recharge_pin.tariffid = tariffsnames.id_tariff
					    WHERE recharge_pin_code.pin_code LIKE '".$pin."%' LIMIT ".$start.",10");
	    $response["myPins"] = $this->getPin($totalresult);
	    $response["totalpage"] = ceil($totalresult->num_rows /10);
	    return json_encode($response);
	}
	
        /**
         * 
         */
	public function getpin($result)
        {
	    $pin = array();
	    if ($result->num_rows > 0) 
            {
		while ($row= $result->fetch_array(MYSQL_ASSOC) ) 
                {
		    
			$returnResult["id"]=$row["id"];
			$returnResult["batch_name"]=$row["batch_name"];
			$returnResult["total_pin"]=$row["total_pin"];				
			$returnResult["reseller_id"]=$row["reseller_id"];

			if(isset($tariffArray[$row["tariffid"]]))
				$returnResult["tariff"]=$tariffArray[$row["tariffid"]];
			else
				$returnResult["tariff"]="Name Not Found. id =".$row["tariffid"];

			$returnResult["created_date"]=$row["created_date"];
			$returnResult["expire_date"]=$row["expire_date"];
			$returnResult["paid_type_flag"]="Postpaid";
			$returnResult["b_status"] = $row["b_status"];
			$returnResult["tariff"] = $row["description"];
			if($row["paid_type_flag"]==1)
				$returnResult["paid_type_flag"]="Prepaid";

			$pin[] = $returnResult;
		}
	    }
	    return $pin;
	}
	
	# modified by sudhir pandey <sudhir@hostnsoft.com>
        # date :  02/09/2013
        # function use to recharge user balance by pin 
        function rechargeByPin($parm,$userid,$userTariff)
	{
            # check pin valid or not 
            if(!isset($parm['pin']) || strlen($parm['pin'])<5)
		{
		 return json_encode(array('status'=>'error','msg'=>'Invalide pin!')); 
		}	
            
            # get pin status (1 for used or 0 for unused).
            $table = '91_pinDetails';
            
            $condition = "pincode ='".$parm['pin']."'";
            #selecting the item from table 91_pinDetails
            $this->db->select('*')->from($table)->where($condition);
            $this->db->getQuery();
            
            #execute query
            $result=$this->db->execute();

            //log error 
            if(!$result)
            {
                trigger_error('problem while get pin detail,condition:'.$condition);
            }

            
            #check the resulting value exists or not 
            if($result->num_rows == 0)
            {
               return json_encode(array('status'=>'error','msg'=>'Invalide pin!')); 
            }
              
            $row = $result->fetch_array(MYSQL_ASSOC);
            if($row['status'] == 1)
            {
                return json_encode(array('status'=>'error','msg'=>'pin already used by another user!')); 
            }
                
            #get ResellerId of user 
            $funobj = new fun();
            $resellerId = $funobj->getResellerId($userid);
            
                       
            #find pin generateor id
            $pinTable = '91_pin';
            $condition = "batchId = '" . $row['batchId'] . "' and (userId= '".$resellerId."') "; //userId= '".$userid."' or 
            $this->db->select('*')->from($pinTable)->where($condition);
            $batchResult = $this->db->execute();

             //log error 
            if(!$batchResult)
            {
                trigger_error('problem while get pin detail,condition:'.$condition);
            }
            
            // processing the query result
            if ($batchResult->num_rows == 0) 
            {	
                return json_encode(array('status'=>'error','msg'=>'You Have No Permission To Use This Pin!')); 
                
            }
            
            $batchDetail = $batchResult->fetch_array(MYSQL_ASSOC);
               
            if(strtotime(date('Y-m-d',strtotime($batchDetail['expiryDate']))) < strtotime(date('Y-m-d')))
            {
                 return json_encode(array('status'=>'error','msg'=>'Pin are expired !')); 
            }
            
            if($batchDetail['action'] == 0){
                return json_encode(array('status'=>'error','msg'=>'Pin status blocked please contact to provider !')); 
            }
            
            #find pin and user currency (call function_layer.php function) 
            $pinCurr = $this->getOutputCurrency($batchDetail['tariffId']);
            $userCurr = $this->getOutputCurrency($userTariff);
            
            if($pinCurr != $userCurr)
            {
                 return json_encode(array('status'=>'error','msg'=>'you can not use this pin because pin currency not match.')); 
            }
            
            #update pin status 
            $data=array("usedDate"=>date('Y-m-d H:i:s'),"status"=>1,"usedBy"=>$userid); 
            $condition = "pincode='".$parm['pin']."'";
            $this->db->update($table, $data)->where($condition);	
            $this->db->getQuery();
            $result = $this->db->execute();
            
            #recharge pin entry in transaction log 
            $amountPerPin = $batchDetail['amountPerPin'];
            
            
            include_once("transaction_class.php");
            $transactionObj = new transaction_class();
            
            $getBalance = $transactionObj->getClosingBalance($userid);
            
            //get updated balance
            $updateClosingBal = $getBalance + $amountPerPin;
            
            //update closing balance
            $transactionObj->updateClosingBalance($userid,$updateClosingBal);
            
            #update current balance of user in userbalance table 
            $transactionObj->updateUserBalance($userid,$amountPerPin,'+');
            
            $currBalance = $transactionObj->getcurrentbalance($userid);
            
            $transactionObj->fromUser = $resellerId;
            $transactionObj->toUser = $userid;
            $msg = $transactionObj->addTransactional_sub($amountPerPin,$currBalance,"Pin",$amountPerPin,0,$updateClosingBal,"Recharge by Pin");
            
            //free object space
            unset($transactionObj);
            
            #add recharge pin detail into 91_pinRechargeDetail for get 10% extra balance 
            $this->pinRechargeDetail($userid,$resellerId,$amountPerPin,$parm['pin']);
            
            
            if($result)
            {
                return json_encode(array('status'=>'success','msg'=>'successfully recharge!')); 
            }
            else
            {
                //log error 
                trigger_error('problem while update pin detail,condition:'.$condition);
                return json_encode(array('status'=>'','msg'=>'error in recharge by pin!')); 
            }
            	
	}
        
        #created by sudhir pandey <sudhir@hostnsoft.com>
        #creation date 21-11-2013
        #function use to save pin recharge detail
        function pinRechargeDetail($userid,$resellerId,$amountPerPin,$pin)
        {
            
            $pin = $this->db->real_escape_string($pin);
            #value store in database 
            $data=array("userId"=>(int)$userid,"date"=>date('Y-m-d H:i:s'),"pinAmount"=>$amountPerPin,"resellerId"=>$resellerId,"pin"=>$pin);          
            #table name 
            $table = '91_pinRechargeDetail';
            #insert query (insert data into 91_pinRechargeDetail table )
            $this->db->insert($table, $data);	
            $result = $this->db->execute();
            
             //log error 
            if(!$result)
            {
                trigger_error('Problem while insert pin recharge detail:'.json_encode($data));
            }
            
        }
        
	function batchStatus($request)
    {
	    $explode_value = explode("_",$request['value']);
	    $col = ($explode_value[0] == 'paid' || $explode_value[0] == 'unPaid')? 'paid_type_flag':'b_status';
	    $value = (($explode_value[0] == 'paid') || ($explode_value[0] == 'resume'))?1:0;
	    $data = array($col=>$value);
	    $table = "recharge_pin";
            
            $condition = "id =".$explode_value[1];
	    $this->db->update($table, $data)->where($condition);		
	    $result = $this->db->execute();
            
             //log error 
            if(!$result)
            {
                trigger_error('problem while update recharge pin,condition:'.$condition);
            }
            
	    return $result;
	}
	
        

        /**
        * @author sudhir pandey <sudhir@hostnsoft.com>
        * @since 07-08-2013
        * @param array $para
        * @param int $userId
        * @method function used to change pin batch action enable or disable (1 for enable and 0 for disable)     
        */
        function changeBatchAction($parm,$userId){
            
            
            #get batch pin id for request 
            $batchId = $parm['batchId'];
            
            #get action for update batchpin 1 for enable and 0 for disable
            $action = $parm['batchAction'];
            
            #check permission for change action or not , if pin generated by login user then change pin action otherwise can't change 
            $table = '91_pin';
            $condition = "batchId = '" . $batchId . "' ";
            $this->db->select('*')->from($table)->where($condition);
            $totallresult = $this->db->execute();

            //log error
            if(!$totallresult)
                trigger_error('problem while get pin detail,condition:'.$condition);
                
            // processing the query result
            if ($totallresult->num_rows > 0) 
            {	
                $row= $totallresult->fetch_array(MYSQL_ASSOC);
                $batchCreator = $row['userId'];
            }
            
            if($batchCreator != $userId)
            {
                 return json_encode(array('status'=>'error','msg'=>'You have no permission for change Batch Pin action!')); 
            }
            
            #update action in batch pin (enable or disable )
            $data=array("action"=>$action); 
            $condition = "batchId=".$batchId." ";
            $this->db->update($table, $data)->where($condition);	
            $this->db->getQuery();
            $result = $this->db->execute();         
            
            if(!$result)
            {
                trigger_error('problem while update pin,condition:'.$condition);
                return json_encode(array('status'=>'error','msg'=>'Batch Pin action not updated!')); 
            }
            else
            {
                 
                return json_encode(array('status'=>'success','msg'=>'successfully update Batch Pin action!')); 
            }
        }
        
        
        /**
        * @author Balachandra <balachandra@hostnsoft.com>
        * @since 31/01/2013 
        * @param int $batchid
        * @param int $userid
        * @method function use to delete the Batchpin 
        */
        function deleteBatchPin($batchid,$userid)
        {
            
            #$table name of the table in database
            $table = '91_pinDetails';
            
            $condition = "batchId ='".$batchid."' and status = 1";
            #selecting the item from table 91_pinDetails where batchid must be matching and status will be 1
            $this->db->select('*')->from($table)->where($condition);
            $this->db->getQuery();
            
            #execute query
            $result=$this->db->execute();

            if(!$result)
                trigger_error('problem while get pin detail,condition:'.$condition);
            
            
            #check the resulting value exists or not 
            if($result->num_rows > 0)
            {
                  #return the message in json format
                  return json_encode(array('msgtype'=>'error','msg'=>'Pin Batch Can Not Be Deleted Because Some PINs Are Used'));
            }
            
            #then no data undergoing if condition
            else 
              {
                    $condition = "batchId = '" . $batchid."'" ;
                    #delete the data from the table 91_pinDetails with matching batchid         
                    $this->db->delete($table)->where($condition);
                    $this->db->getQuery();
                    #execute the query
                    $result1 = $this->db->execute(); 

                    //log error
                    if(!$result1)
                    {
                        trigger_error('problem while get pin details,condition:'.$condition);
                    }
                    
                    #$temp contains the table name 91_pin 
                    $temp='91_pin';
                    
                    
                    $condition = "userid = '" .$userid."' and  batchId = '".$batchid."'";
                    #delete the data from table 91_pin
                    $this->db->delete($temp)->where($condition);
                    $this->db->getQuery();
                    
                    #execute the query
                    $result2 = $this->db->execute();
                    
                    //log error
                    if(!$result2)
                    {
                        trigger_error('problem while delete batch pin ,condition:'.$condition);
                    }
                    
                    #listing all pindata by call getmypin function
                    $pindata = $this->getMyPin($userid);
                    $pinData = json_decode($pindata, true);  
               
                    $str = $pinData['myPins'];
                    $userName = $pinData['userName'];
                    if($result1 && $result2)
                    {
                        #return in the json format
                        return json_encode(array('msgtype'=>'success','msg'=>'Deleted Successfully',"batch"=>$str,"userName"=>$userName));
                    } 
                    else 
                    {
                        #deletion condition failed
                        return json_encode(array('msgtype'=>'error','msg'=>'Not Possible To Delete')); 
                    }
              }
                        
                    

         }
         
        #created by 
        #creation date 
        #

        /**
        * @author sudhir pandey <sudhir@hostnsoft.com>
        * @since 12-08-2013
        * @param array $parm
        * @param int $userId
        * @method function change pin batch amount status paid or unpaid (1 for paid and 0 for unpaid).
        */ 
        function pinBatchAmountStatus($parm,$userId)
        {
            #get batch pin id for request 
            $batchId = $parm['batchId'];
            
            #get action for update batchpin 1 for paid and 0 for unpaid
            $amountStatus = $parm['amountStatus'];
            
            #check permission for change batch status or not , if pin generated by login user then change pin action otherwise can't change 
            $table = '91_pin';
            $condition = "batchId = '" . $batchId . "' ";
            $this->db->select('*')->from($table)->where($condition);
            $totallresult = $this->db->execute();

             //log error
            if(!$totallresult)
            {
                trigger_error('problem while get batch pin detail ,condition:'.$condition);
            }

            // processing the query result
            if ($totallresult->num_rows > 0) 
            {	
                $row= $totallresult->fetch_array(MYSQL_ASSOC);
                $batchCreator = $row['userId'];
            }
            
            if($batchCreator != $userId)
            {
                 return json_encode(array('status'=>'error','msg'=>'You have no permission for change Batch status!')); 
            }
            
            #update status of batch pin (paid or unpaid)
            $data=array("amountStatus"=>$amountStatus); 
            $condition = "batchId=".$batchId." ";
            $this->db->update($table, $data)->where($condition);	
            $this->db->getQuery();
            $result = $this->db->execute();
            
            if(!$result)
            {
                trigger_error('problem while update batch pin status ,condition:'.$condition);
                return json_encode(array('status'=>'error','msg'=>'Batch Pin status not updated!')); 
            }
            else
            {
                return json_encode(array('status'=>'success','msg'=>'successfully update Batch Pin status!')); 
            }
        }
        
        
        /**
         * @author sudhir pandey <sudhir@hostnsoft.com>
         * @since 05-03-2014
         * @param int $batchId  
         */
        function exportPinList($batchId,$userid){
            $batchPinjson = $this->getPinDetails($batchId,$userid,1,1);
            $batchPinData = json_decode($batchPinjson, true);   
            $str = $this->pinExportDesign($batchPinData);
            
            $this->exportCsv($str);
        }
        
        function exportCsv($str)
        {
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=pinList.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            echo $str; 
            
        }
        function pinExportDesign($batchPinData){
            $str='';
            
            foreach($batchPinData['batch_details'] as $batchDetails){ 
                $str.=' Pin Batch Name :  "'.$batchDetails['batch_name'].'"';
                $str.="\n";
                $str.='Created on "'.$batchDetails['created_date'].'" | Expiry : "'.date('Y-m-d',strtotime($batchDetails['expire_date'])).'"';
           }
           $str.="\n\n";
           
           $str.='Pin Code , Used Date , Used By';
           $str.="\n";
            foreach($batchPinData['myPins'] as $pinDetails){
                $str.='"'.$pinDetails['pin_code'].'","'.$pinDetails['used_date'].'","'.$pinDetails['userBy'].'"';
                $str.="\n";
            }
           
           
           
           return $str;
            
        }
        
         /**
         * @author  Rahul <rahul@hostnsoft.com>
         * @method Pin Generator
         * @param int length
         * @return string
         */
        function create_pin($length=5)
        {		
		
    		$new = false;
    		while($new == false)
    		{
                        $pin = substr(md5(microtime()),0,$length);		
                        $table = '91_pinDetails';
                        
                        $condition  = "pinCode = '" . $pin . "' ";
                        $this->db->select('pinCode')->from($table)->where();
                        $result = $this->db->execute();
                       
                        //log errors
                        if(!$result)
                            trigger_error('problem while get batch pin detail ,condition:'.$condition);
                        
                        // processing the query result
                        if ($result->num_rows > 0) 
                        {
                            $new=false;		    
                        }
                        else
                            $new=true;
    		}
                    
    		return $pin;	
	}
       

    /**
     * @author  Rahul <rahul@hostnsoft.com>
     * @package Pin Generator
     * @return int
     */        
    function createNumeric_pin($length=5)
    {		
	
    	$new = false;
    	while($new == false)
    	{
    		$pin = $this->generatePassword($length);		
    		$table = '91_pinDetails';
                        
            $condition = "pinCode = '" . $pin . "' ";
    		$this->db->select('pinCode')->from($table)->where($condition);
    		$result = $this->db->execute();
    		
            if(!$result)
                trigger_error('problem while get pin detail ,condition:'.$condition);
            
    		// processing the query result
    		if ($result->num_rows > 0) 
            {
    		    $new=false;		    
    		}
    		else
    		    $new=true;
    	}
    	return $pin;	
} 
         
 }//end of class
?>