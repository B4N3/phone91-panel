<?php

include dirname(dirname(__FILE__)) . '/config.php';

class plan_class extends fun {

    var $last_id;
    var $country;


    public function insertPlan($request) {
        /* @desc : funtion is used to insert plan details 
         * @return : returns the last insert id 
         */
        $table = "91_plan";
        $currency = trim($request['currency']);
        $data = array("planName" => trim($request['planName']), "billingInSeconds" => trim($request['billingSec']), "outputCurrency" => $currency, 'userId' => $request['userId']);
        $this->insertData($data, $table);
        return $this->db->insert_id;
    }


    public function searchExistingPlans($request, $session,$like =0, $query = Null) {
        /* @desc : funtion is used to search existing plan 
         * @return : returns the result in mysqli object from  
         */
        if (is_null($query))
        {
            if($like == 1)
                $querySel = "SELECT *  FROM 91_plan WHERE planName LIKE '" . $request['planName'] . "%' AND userId =" . $session['userid']."";
            else
                $querySel = "SELECT *  FROM 91_plan WHERE planName = '" . $request['planName'] . "' AND userId =" . $session['userid']."";
            
            $result = $this->db->query($querySel);
        }
        else
            $result = $this->db->query($query);
        return $result;
    }
    

    public function uploadFile($file) {
        /* @desc : funtion is used to upload the file 
         * @return : returns true if success else false
         */
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (( $extension == 'xls' || $extension == 'xlsx') && (!$file['error'])) {
            if (move_uploaded_file($file['tmp_name'], "../uploads/" . $file['name']))
                return true;
            else
                return false;
        }else
            return false;
    }
    public function getConvertedCurency($from, $to) {
        $from = $this->getCurrencyName(trim($from));;
        $to = $this->getCurrencyName(trim($to));
        if($from =="" || $to =="")
            die("Invalid Currency");
        
        $url = "http://voip92.com/currency/index.php?from=$from&to=$to&amount=1";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_result = curl_exec($ch);
        $curlError = curl_error($ch) ;
        if($curlError != false || $curl_result == 0)
            mail("sameer@hostnsoft.com,sudhir@hostnsoft.com","api not working",print_R($curlError)." result ".$curl_result);
        curl_close($ch);
        return $curl_result;
    }
    public function import_tariff_rates($file, $amount,$curVal,$repAll) {
        /* @desc : funtion is used to import the tariff rate from xls file
         * @return : returns true if success 
         */
        # INCREASE THE MAXIMUM EXECUTION TIME OF THE SCRIPT 
        ini_set('maximum_execution_time', 6000);
        error_reporting(-1);
        require_once 'PHPExcel.php';
        require_once 'PHPExcel/IOFactory.php';
        $t_id = $this->last_id;
        #IF LAST INSERT ID OF THE TARIFF PLAN IS NOT SET THE THEN RETURN FALSE
        if (is_null($t_id) || $t_id == "")
            return false;

        #GET THE FILE NAME AND EXTENSION
        ini_set('memory_limit', '512M');
        $contact_file = "../uploads/" . $file['name'];
        $filename_ext = $contact_file;
        $filename_ext = strtolower($filename_ext);
        $exts = split("[/\\.]", $filename_ext);

        $n = count($exts) - 1;
        $exts = $exts[$n];
        if ($exts == "xls")
            $type = "Excel5";
        else if ($exts == "xlsx")
            $type = "Excel2007";
        else
            return false;
        
        #create a object reader 
        $objReader = PHPExcel_IOFactory::createReader($type);

        
        $objReader->setReadDataOnly(true);
        #load the contact file to object reader 
        $objPHPExcel = $objReader->load($contact_file);

        #get the name of the active sheet this is used if multiple sheets are present 
        $objWorksheet = $objPHPExcel->getActiveSheet();
        
        $row_counter = 0;
        $sql = "insert into 91_tariffs (tariffId,description, prefix, voiceRate,operator) values ";
        $j = 0;
        #get the number of rows form the sheet
        $countAll = $objWorksheet->getHighestRow();
        foreach ($objWorksheet->getRowIterator() as $row) {
            $row_counter = $row_counter + 1;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $counter = 0;
//            if ($row_counter != 1) {
                $valid = 0;
                foreach ($cellIterator as $cell) {
                    $counter = $counter + 1;
                    if ($counter == 1)
                        $ccode = $cell->getValue(); # GET THE COUNTRY CODE FROM THE FIRST COLOUMN
                    else if ($counter == 2)
                        $cntry = $cell->getValue(); # COUNTRY NAME FROM THE SECOND COLOUMN
                    else if ($counter == 3) 
                        $operator = $cell->getValue(); # OPERATOR FROM THE THIRD COLOUMN
                    else if ($counter == 4) {
                         $teriff_rate = $cell->getValue();
                        
                        #convert the currency into user desired currency
                        $teriff_rate = $curVal*$teriff_rate;
                        $teriff_rate = $teriff_rate + ($teriff_rate * $amount); # ADJUST THE TARIF RATE ACCORDING TO THE PERCENT GIVEN BY USER
                    }
                    #VALIDATE ALL THE FIELDS IF CORRECT THEN SET VALID 1 ELSE 0 TO DISCARD THE ROW
                    if (is_numeric($teriff_rate) && preg_match('/[A-Za-z\s]/', trim($cntry)))//&& preg_match('/[0-9]/',trim($ccode)))
                        $valid = 1;
                    if(isset($operator) && $operator !="" && preg_match('/[^a-zA-Z\s]+/', $operator))
                        $valid = 0;
                    if(!is_numeric($ccode) || $ccode < 1 || strlen(trim($ccode)) < 1 )
                        $valid = 0;
                }
                
                
                #IF VALID IS SET TO ONE THEN INSSERT EH ROW INTO THE TABLE 
                if ($valid == 1) {
                    
                    if($repAll)
                    {
                       $sql .= "('" . $t_id . "','" . trim($cntry) . "','" . trim($ccode) . "','" . trim($teriff_rate) . "','".$operator."'),";
                       #for every 250 iteration a single query will be executed 
                       #this is to manage the length of the query 
                       if(($j % 250 == 1 && $j != 1) || $j == ($countAll - 1))
                        {
                            $sql = substr($sql, 0,-1);

                            if ($this->db->query($sql))
                                $sqlRes = 1;
                            else {
                                $sqlRes = 0;
                                break;
                            }
                            $sql = "insert into 91_tariffs (tariffId,description, prefix, voiceRate,operator) values ";
                        }
                        $j++;
                       
                    }
                    else
                    {
                        # if the $repall id replace all flag is not set then sigle2 query will be executed 
                        $sql = $sql." ('" . $t_id . "','" . trim($cntry) . "','" . trim($ccode) . "','" . trim($teriff_rate) . "','".$operator."') on DUPLICATE KEY UPDATE voiceRate='" . $teriff_rate . "',description='" . $cntry . "',operator='" . $operator . "'";
                        $sqlRes = $this->db->query($sql);
                    }
                }
//            }
        }
        #after completion of work unlink the file as it is not needed further 
        unlink($contact_file);
        if ($sqlRes)
            return true;
        else
            return false;
    }


    public function addPlan($request, $session) {
        /* @desc : funtion is used to aad the plan to the database
         * @return : returns true if success 
         */
       
        
        /**********INITAILIZE VARIABLES****************/
        #VAR IS TO 0 WHEN THE EXISTING TARIIF SHOULD BE FLUSED AND 1 WHEN TARIFFS ARE APPENDED TO THE PLAN 
        $append = 0; 
        # THIS VARIABLE IS USED TO CHECK IF THE QUERY IS EXECUTED FOR DIFFERENT CASES 
        $execution = 0;
        # dafault currency value
        $curVal = 1;
        # repall flag initlize
        $repAll = 0; 
        /*********************************************/
        
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
        }

        if (isset($session['id']))
            $request['userId'] = $session['id'];

        
        if (isset($request['append'])) {
            $append = 1;
            $this->last_id = str_replace('%20', '', $request['tariffId']);
            //  number007  do not delete  
            //  $request['pid'] = $request['tariffId'];
        }
        else
        {
            #IF APPEND IS NOT SET THE SEARCH THE EXSISTING PLAN 
            $result = $this->searchExistingPlans($request, $session,0);
            if ($result->num_rows == 0)
            {
                $this->last_id = $this->insertPlan($request);
                #incase of new plan
                $repAll = 1;
            }
            else
               return json_encode(array("msg"=>"Plan already exist please try with different name","status"=>"error"));
        }

//        if ($result->num_rows == 0 || $append) {
            
    /******* section commented by sameer during optimization **********/
//            if (!isset($request['pid']) && $append == 0) {
                #INSERT ONLY IF THE NEW PLAN IS CREATED 
//                $this->last_id = $this->insertPlan($request);
//            }else
//                $this->last_id = str_replace('%20', '', $request['tariffId']);
    /*****************************************************************/
            
            
            
            #IF IN ANY CASE $REQUEST[TARIFFID] CREATES ANY PROBLEM 
            #THEN USE PID INSTEAD OF TARIFF ID AND UNCOMMENT ABOVE
            #LINE WITH NUMBER007 WRITTEN IN FRONT OF IT 
            #CURRENTLY THE PURPOSE OF PID IS NOT CLEAR IT WILL REMOVED IN FUTURE 

            #THIS CONDITION IS FOR REMOVING THE EXISTING TARIFF FORM THE PLAN 
            #AND TO INSERT THE NEW ONE AS PER USER REQUEST
            if (isset($request['rep']) && $request['rep'] == "all") {
                #if import with case 2 ie select a plan then this condition restrict the user to 
                #edit the same plan other wise it will be deleted and throwh an error 
                if($request['plantype'] == $request['tariffId'] && $request['import'] == 2)
                {
                    return json_encode(array("msg"=>"Cannot Edit the same plan","status"=>"error"));
                }
                $condition = "tariffId=" . $request['tariffId'];
                $resultTariff = $this->deleteData('91_tariffs', $condition);
                #set repall flag to 1
                $repAll = 1;
            }
            
            
            
            #THERE ARE THE WAYS TO IMPORT THE TARIFF
            #CASE 1 : USER CAN UPLOAD THE FILE 
            #CASE 2 : USER CAN SELECT FROM EXIXTING PLAN
            #CASE 3 : USER CAN DO MANNUAL ENTRY FOR EACH TARIFF ROW 
            
            switch ($request['import']) {
                case 1:
                    if ($this->uploadFile($file)) {
                        # CALCULATE THE AMOUNT WHEN USER WANTS TO INCREASE OR DECREASE RATE IN PERCENTAGE 
                        $amount = ($request['importWith']) ? ($request['rateAction']) ? ($request['rateAction'] == 'planInc') ? trim($request['importValue'] / 100) : -trim($request['importValue'] / 100) : 0 : 0;
                        if(isset($request['fileCurrency']) && isset($request['currency']) && $request['currency'] !="" && $request['fileCurrency'] != "")
                        {                         
                            $curVal = round($this->getConvertedCurency($request['fileCurrency'], $request['currency']),5);
                            if($curVal == 0)
                                return json_encode (array("msg"=>"Technical error please contact provider","status"=>"error"));
                        }
                        
                        if (!$this->import_tariff_rates($file, $amount,$curVal,$repAll)) {
                            $msg = "Error importing rates";
                            $execution = 5;
                        }
                        else
                            $execution = 1;
                    }
                    else {
                        $msg = "Cannot upload file";
                        $execution = 6;
                    }
                    break;
                case 2:
                    if ($request['plantype'] != 'Select') {
                        
                        
                        $amount = ($request['planWith']) ? ($request['selRateAction']) ? ($request['selRateAction'] == 'planInc') ? trim($request['planValue'] / 100) : -trim($request['planValue'] / 100) : 0 : 0;
                        $columns = 'prefix, `description`, voiceRate,operator';
                        $condition = "tariffId =" . trim($request['plantype']);
                        
                        #GET OUTPUT CURRENCY FOR CURRENCY CONVERSION
                        $curRes = $this->selectData("outputCurrency", '91_plan', $condition);
                        $curResRow = $curRes->fetch_array(MYSQL_ASSOC);
                        $currenctCurrencyId = $curResRow['outputCurrency'];
                        
                        if( isset($request['currency']) && $request['currency'] !="" )
                        {
                            $curVal = round($this->getConvertedCurency($currenctCurrencyId, $request['currency']),5);
                            if($curVal == 0)
                                return json_encode (array("msg"=>"Technical error please contact provider","status"=>"error"));
                        }
                        
                        # GET THE DATA FORM THE OTHER PLAN IN THE DATABASE 
                        $results = $this->selectData($columns, '91_tariffs', $condition);
                        $sqlSel = "INSERT INTO 91_tariffs (tariffId,description,prefix,voiceRate,operator) values";
                        if ($results) {
                            $j = 0;
                            $countall = $results->num_rows;
                           
                            if($repAll)
                            {
                                while ($row = $results->fetch_array(MYSQL_ASSOC)) {
                                    $vRate = (float)$row['voiceRate'];
                                    $voiceRate = ($curVal * $vRate);
                                    $sum = ($voiceRate + ($voiceRate * $amount));
                                                                        
                                    $sqlSel .= "('" . $this->last_id . "','" . $row['description'] . "','" . $row['prefix'] . "','" . $sum . "','" . $row['operator'] . "'),";
                                    
                                    #IF REPALL FLAG IF SET THEN ENTRY WILL BE INSERTED IN BULK 
                                    if(($j % 250 == 1 && $j != 1) || $j == ($countall - 1))
                                    {
                                        $sqlSel = substr($sqlSel, 0,-1);
                                     
                                        if ($this->db->query($sqlSel))
                                            $execution = 1;
                                        else {
                                            $msg = "Error inserting rates Please contact Provider";
                                            $execution = 8;
                                            break;
                                        }
                                        $sqlSel = "INSERT INTO 91_tariffs (tariffId,description,prefix,voiceRate,operator) values";
                                    }
                                    $j++;
                                }
                            }
                            else {
                                #IF REPALL FALG IS SET TO 0 THEN SIGLE2 QUERY WILL BE EXECUTED AS 
                                #IF THERE IS  A DUPLICATE ENTRY THEN IT WILL BE UPDATED OTHERWISE INSERTED 
                                while ($row = $results->fetch_array(MYSQL_ASSOC)) {
                                    $voiceRate = $curVal* $row['voiceRate'];
                                    $sum = ($voiceRate + ($voiceRate * $amount));
                                    $sqlSel = "INSERT INTO 91_tariffs (tariffId,description,prefix,voiceRate,operator) values ('" . $this->last_id . "','" . $row['description'] . "','" . $row['prefix'] . "','" . $sum . "','" . $row['operator'] . "') on DUPLICATE KEY UPDATE tariffId = '" . $this->last_id . "', description = '" . trim($row['description']) . "', prefix = '" . trim($row['prefix']) . "', voiceRate='" . $sum . "'";
                                    if ($this->db->query($sqlSel))
                                        $execution = 1;
                                    else {
                                        $msg = "Error inserting rates Please contact Provider";
                                        $execution = 8;
                                        break;
                                    }
                                }
                            }
                        
                        } else {
                            $msg = "Error importing tariff plan";
                            $execution = 7;
                        }
                    }
                    break;
                case 3:
                    for ($i = 0; $i <= $request['sizeOfRow']; $i++) {
                        #Loop through each input field to get the value
                        if ($request['countryCode'][$i]) {
                            $prefix = str_replace('+', '', trim($request['countryCode'][$i]));
                            $sqlMan = "INSERT INTO 91_tariffs (tariffId,description,prefix,voiceRate,operator) values ('" . $this->last_id . "','" . trim($request['countryName'][$i]) . "','" . str_replace('+', '', trim($request['countryCode'][$i])) . "','" . trim($request['rate'][$i]) . "','" . trim($request['operator'][$i]) . "') on DUPLICATE KEY UPDATE tariffId = '" . $this->last_id . "', description = '" . trim($request['countryName'][$i]) . "', prefix = '" . str_replace('+', '', trim($request['countryCode'][$i])) . "', voiceRate='" . trim($request['rate'][$i]) . "', operator='" . trim($request['operator'][$i]) . "'";
                            if ($this->db->query($sqlMan))
                                $execution = 1;
                            else {
                                $msg = "Error inserting rates Please contact Provider";
                                $execution = 8;
                                break;
                            }
                        }
                    }
                    break;
            }
            
            # IF TARIFFS ARE NO INSERTED PROPERLYTHEN DELETE THE PLAN WHICH IS INSERTED 
            
            if ($execution != 1 && $append == 0) {
                $condition = "tariffId = " . $this->last_id;
                # DELETE THE PLAN IN CASE OF ERROR 
                $this->deleteData('91_plan', $condition);
                return json_encode(array("msg"=>"Error inserting plan please try again","status"=>"error","type"=>$execution));
            }
            elseif($execution != 1 && $append == 1) {
                return json_encode(array("msg"=>"Error inserting plan please try again","status"=>"error","type"=>$execution));
            }
            else
            {
                if(isset($request['currency']) && $request['currency'] != "" && $append == 1 )
                {
                    #UPDATE THE PLAN FOR NEW BILLING SECONDS 
                    $billingSec = trim($request['billingSec']);
                    $userId = trim($request['userId']);
                    #UPDATE QUERY FOR BILLING SECONDS
                    $updQuery = "UPDATE 91_plan SET billingInSeconds = ".$billingSec." WHERE tariffId = ".$this->last_id."";
                    $updRes = $this->db->query($updQuery);
                    
                    
                    if(!$updRes)
                        return json_encode(array("msg"=>"error updating billing second Plan Updated sucessfuly","status"=>"error","insertId"=>$this->last_id));
                }
                return json_encode(array("msg"=>"Plan Inserted successfuly","status"=>"success","insertId"=>$this->last_id));
            }
//        }else
//            return json_encode(array("msg"=>"Plan already exist please try with different name","status"=>"error"));
    }


    public function searchTarriff($keyword,$tariffId)
    {
        /* @author : SAMEER
         * @created : 16-09-2013
         * @desc : search the tarrif rate 
         */
        if(is_numeric($keyword))    
            $sql = "SELECT slNo,tariffId,voiceRate,prefix,description,operator FROM 91_tariffs WHERE tariffId = $tariffId AND prefix LIKE '$keyword%' limit 0,20";
        else
            $sql = "SELECT slNo,tariffId,voiceRate,prefix,description,operator FROM 91_tariffs WHERE tariffId = $tariffId AND description LIKE '$keyword%' limit 0,20";
        
       
        $res = $this->db->query($sql);
        
        while($row  = $res->fetch_array(MYSQLI_ASSOC))
        {
            $resultant[] = $row; 
        }
        return json_encode($resultant);
    }
    public function deleteData($table, $condition = 1) {
        /* @desc : function is used to delete the data from the table
         */
        if ($this->db->query("DELETE FROM " . $table . " WHERE " . $condition))
            return true;
        else
            return false;
    }

    public function deletePlanTariffs($request, $session) {
        /* @desc : function is used to delete the tariff  from the table
         */
        # condition for selecting the data 
        $selCon = "tariffId=" . $request['tariffId'] . " and userId=" . $session['id'];
        $selRes = $this->selectData("tariffId", "91_plan", $selCon);
        if (!$selRes || $selRes->num_rows == 0) {
            $response['msg'] = "Error plan doesn't exist please contact provider";
            $response['status'] = "error";
            return json_encode($response);
        }
        $conditionPlanTable = "tariffId=" . $request['tariffId'];

        /* Do not delete this code this is needed when deleting whole plan */
//         if(isset($request['deleteAll']) && $request['deleteAll'] == 1)
//         {
//          $condition = "tariffId=".$request['tariffId'];
//          if($this->db->query("INSERT IGNORE INTO 91_backupTariffs SELECT * FROM 91_tariffs where ".$condition))
//              $resultTariff = $this->deleteData('91_tariffs', $condition);
//         
//            if($this->db->query("INSERT IGNORE INTO 91_backupPlan SELECT * FROM 91_plan where ".$conditionPlanTable))
//                $resultPlan = $this->deleteData('91_plan', $conditionPlanTable);
//            
//            if($resultPlan && $resultTariff)
//                return 1;
//            else
//                return 0;
//         }
//        else

        /* section end here */

        foreach ($request['idArr'] as $slno) {
            //inserting for backup this is for taking the backup before deleting the plan 
            $condition = "slNo=" . $slno . " and " . $conditionPlanTable;
            if ($this->db->query("INSERT IGNORE INTO 91_backupTariffs SELECT * FROM 91_tariffs where " . $condition)) {
                $resultTariff = $this->deleteData('91_tariffs', $condition);
                if (!$resultTariff) {
                    $response['msg'] = "Cannot delete tariff please try again";
                    $response['status'] = "error";
                    return json_encode($response);
                }
            } else {
                $response['msg'] = "Cannot delete tariff please try again";
                $response['status'] = "error";
            }
        }
        if ($resultTariff) {
            $response['msg'] = "Tariff deleted succefully";
            $response['status'] = "success";
        }
        return json_encode($response);
    }

    public function deletePlan($request, $session) {
        /* @desc : function is used to delete the plan from the table
         */
        $selCon = "tariffId=" . $request['tariffId'] . " and userId=" . $session['id'];
        $selRes = $this->selectData("tariffId", "91_plan", $selCon);
        if (!$selRes || $selRes->num_rows == 0) {
            $response['msg'] = "Error plan doesn't exist please contact provider";
            $response['status'] = "error";
            return json_encode($response);
        }

        $condition = "tariffId=" . $request['tariffId'];
        $conditionPlanTable = $condition . " and userId=" . $session['id'];
        
        # insert plan in the backup table before deleting 
        if ($this->db->query("INSERT IGNORE INTO 91_backupTariffs SELECT * FROM 91_tariffs where " . $condition))
            $resultTariff = $this->deleteData('91_tariffs', $condition);
        # if tariff is deleted succesfuly hten only delete the plan else not 
        if ($resultTariff) {
            if ($this->db->query("INSERT IGNORE INTO 91_backupPlan SELECT * FROM 91_plan where " . $selCon)) {
                $resultPlan = $this->deleteData('91_plan', $selCon);
                $response['msg'] = "Plan Deleted Succesfuly";
                $response['status'] = "success";
            }
            # condtion if unable to delete the plan for any reason then ot will the tariif back from the back table 
            if (!$resultPlan) {
                $this->db->query("INSERT IGNORE INTO 91_tariffs SELECT * FROM 91_backupTariffs where " . $condition);
                $response['msg'] = "Error deleting plan please try again";
                $response['status'] = "error";
            }
        } else {
            $response['msg'] = "Error deleting plan please try again";
            $response['status'] = "error";
        }
        return json_encode($response);

//	$user_id = $this->getUserId($request);
//	if($user_id == $session['userid']){
//	    if($this->deleteData('91_tariffs',"slNo = ".$request['id']))
//		 return 1;
//	    else
//		return 0;
//
//	}else
//		return 2;
    }

    public function editTariff($request, $session) {
        /* @desc : function is used to edit the triff rate 
         */
        # get the user id from 91_plan table to check if the plan is crated by the user
        $user_id = $this->getUserId($request);
        if ($user_id == $session['id']) {
            $data = array("prefix" => trim($request['prefix']), "description" => trim($request['country']), "voiceRate" => trim($request['rate']), "operator" => trim($request['operator']));
            if ($this->updateData($data, '91_tariffs', 'slNo=' . trim($request['id'])))
                return json_encode(array('msg'=>"Contact Updated Successfuly","status"=>"success"));
            else
                return json_encode(array('msg'=>"Error Updating Contact Please Try Again","status"=>"error"));
        }else
            return json_encode(array('msg'=>"Please Login","status"=>"error"));
    }

    public function getUserId($request) {
        /* @desc : get the user id from 91_plan by tariff id 
         */
        $result = $this->selectData('userId', '91_plan', "tariffId =" . $request['pid']);
        if($result)
        {
            while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                return $row['userId'];
            }
        }
        else
            return 0;
    }

    public function countData($table, $condition) {
        /* @desc function is used to count the data from 
         * 
         */
        $result = $this->selectData('count(*)', $table, $condition);
        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            return $row['count(*)'];
        }
    }

    public function getPlans($request, $session) {
        /* @desc : fucntion fetches the details of plan from 91_plan table and return the result in json format 
         */
        # GET THE USER ID FROM SESSION
        $userid = $session["userid"];
        extract($request);

        #SET THE HEADING IN THE RESULT 
        $jade["heading"] = "Manage Tarrif Plans";
        //get limit if not set
        if (!isset($limit))
            $limit = 10;
        if (isset($request['limit']))
            $limit = $request['limit'];
        //get start if page no. set
        if (isset($page_number))
            $start = ($page_number - 1) * $limit;
        else
            $start = 0;

        
        #THIS PARAM IS SET WHEN FUNCTION IS USED IN SEARCHING THE EXISTING PLAN
        if (empty($request['mnpln']))
            $request['mnpln'] = "DESC";

        //query to get call history
        if ($request['mnpln'] == 'search') {
            $request['name'] = $request['desc'];
            $resultSearch = $this->searchExistingPlans($request, $session,1);
            $value['search'] = $request['desc'];
        } else {
            $sqlQuery = "select * from 91_plan where userId=" . $session['id'] . "  ORDER BY date " . $request['mnpln'] . " LIMIT " . $start . "," . $limit;
        
            $resultSearch = $this->searchExistingPlans($request, $session,0, $sqlQuery);
            $value['search'] = 'search';
        }

        $currency = array();
        if($resultSearch)
        {
            while ($rows = $resultSearch->fetch_array(MYSQLI_ASSOC)) {
                if (is_array(isset($tableRow)))
                    unset($tableRow);
                $tariff = $rows['tariffId'];
                $planName = $rows['planName'];
                $date = $rows['date'];
                $billInSec = $rows['billingInSeconds'];
                $plan_currency = $this->getCurrencyName($rows['outputCurrency']);
                $data['id'] = $tariff;
                $data['value'] = $planName;
                $data['date'] = $date;
                $data['billInSec'] = $billInSec;
                $data['currency'] = $plan_currency;
                $value['allvalue'][] = $data;
            }
            //find total pages for found entries
            $value['thead'] = '';
            $value['page'] = '';
            $value['allvaluess'] = '';
            $value['pid'] = '';
            if (isset($totalCount) && isset($limit))
                $pages = ceil($totalCount / $limit);

            $value['count'] = $this->countData('91_plan', " userId=" . $session['id'] . " ");
            $value['limit'] = $limit;
            if (!isset($value['allvalue']))
                $value['allvalue'] = '';
        }
        
        return json_encode($value);
    }

    public function managePlan($request,$session) {
        /* @parm : $request & $session 
         * @desc : function takes the request and set the limit variable 
         *         and pagenumber and call get plan function for fetching the list of plan from 91_plan table 
         */
        if (isset($request['limit']))
            $result['limit'] = $request['limit'];
        else
            $result = array();
        if (isset($request['page_number']))
            $result['page_number'] = $request['page_number'];
        
        //here result will be send as a request varible 
        $returnJson = $this->getPlans($request, $session);
        return $returnJson;
    }

    public function getTarrifDetails($request, $session) {
        /* @parm : $request & $session 
         * @desc : function takes the request and set the limit variable 
         *         and pagenumber get the tariff details from 91_tariff
         */
        include_once("profile_class.php");
        
        #set the limit of the records to me fetched 
        if (isset($request['no_of_records']))
            $limit = $request['no_of_records'];
        else
            $limit = 6;
        
        # the tariff id to be fetched 
        if (!isset($request['pid']))
            $request['pid'] = 8;
        # condition for paginaton 
        if (isset($request['page_number']))
            $first_limit = ($request['page_number'] - 1) * $limit;
        else
            $first_limit = 0;
        
        
        # get the total tariff rates 
        $total_rows = $pro_obj->total_tariff_rates($request['pid']);
        $pages = ceil($total_rows / $limit);
        
        
        #fetch the tariff rates from db  
        $r_rp_detail = $pro_obj->load_tariff_rates($request['pid'], $first_limit, $limit);
        /************Commented by sameer do not remove this *****************/
//        $tableHeading[] = "Country Code";
//        $tableHeading[] = "Country Name";
//        $tableHeading[] = "Rate Plan";
//        $tableHeading[] = "Deletes";
//        $jade["thead"] = $tableHeading;
        /******************************************************************/
        while ($rw_rp_detail = $r_rp_detail->fetch_array(MYSQLI_ASSOC)) {
            $pid = $rw_rp_detail['tariffId'];
            $countryCode = $rw_rp_detail['countryCode'];
            $prefix = $rw_rp_detail['prefix'];
            $description = $rw_rp_detail['description'];
            $rate = $rw_rp_detail['voiceRate'];
            $operator = $rw_rp_detail['operator'];
            $id_tariffs_key = $rw_rp_detail['slNo'];
            $arrval[] = array('prefix' => $prefix, 'description' => $description, 'voiceRate' => $rate, 'id_tariffs_key' => $id_tariffs_key, 'pid' => $pid, 'countryCode' => $countryCode,'operator' => $operator);
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
        $jade['count'] = $this->countData("91_tariffs", "tariffId=" . $request['pid']);
        $jade['value'] = json_decode($this->getPlans(null, $session));
        if (isset($request['pid']))
            $jade['pid'] = $request['pid'];
        return json_encode($jade);
    }

    #NOT USED ANY MORE
//    public function manageTariff($request, $session) {
//        /* @desc : set the limit and page number and call get tariff details function to fetch the tariff details 
//         */
//        
//        $request['no_of_records'] = 20;
//        return $this->getTarrifDetails($request, $session);
//    }

    #NOT USED ANY MORE
//    public function selectPlan($request, $session) {
//        return $this->getPlans($request, $session);
//    }

    public function getPlanName($tariffId, $session) {
        /* @desc : fetches the plan plan name from9_plan
         * 
         */
        $result = $this->selectData('planName,outputCurrency', '91_plan', 'tariffId=' . $tariffId . ' and userId=' . $session['id']);
        if($result)
        {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $planNameArr['planName'] = $row['planName'];
                $row['outputCurrency'];

                $planNameArr['currency'] = $this->getCurrencyName($row['outputCurrency']);
            }
        }
        
        return $planNameArr;
    }

}