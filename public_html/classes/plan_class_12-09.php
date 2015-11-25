<?php

include dirname(dirname(__FILE__)) . '/config.php';

class plan_class extends fun {

    var $last_id;
    var $country;

//    public function selectData($columns, $table, $condition = 1) {
//        $this->db->select($columns)->from($table)->where($condition);
//        $result = $this->db->execute();
//        return $result;
//    }
//
//    public function insertData($data, $table) {
//        $this->db->insert($table, $data);
//        return $this->db->execute();
//    }
//
//    public function updateData($data, $table, $condition = 1) {
//        $this->db->update($table, $data)->where($condition);
//        return $this->db->execute();
//    }

//    public function insertTariffsNames($request){
//	$table = "tariffsnames";
//	$data = array("description"=>trim($request['name']), "id_currency"=> $this->getCurrency(trim($request['currency'])));
//	$this->insertData($data, $table);
//	return $this->db->insert_id;
//    }
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

//    public function insertTariffReseller($session){
//	$data = array('id_tariff'=>$this->last_id, 'id_reseller'=>$session['userid']);
//	$this->insertData($data, 'tariffreseller');
//    }

    public function searchExistingPlans($request, $session, $query = Null) {
        /* @desc : funtion is used to search existing plan 
         * @return : returns the result in mysqli object from  
         */
        if (is_null($query))
            $result = $this->db->query("SELECT *  FROM 91_plan WHERE planName = '" . $request['planName'] . "' AND userId =" . $session['userid']);
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
        
        $url = "http://voip91.com/currency/index.php?from=$from&to=$to&amount=1";
        $ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curl_result = curl_exec($ch);
		curl_close($ch);
                return $curl_result;
    }
    public function import_tariff_rates($file, $amount,$curVal) {
        /* @desc : funtion is used to import the tariff rate from xls file
         * @return : returns true if success 
         */
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

        $objReader = PHPExcel_IOFactory::createReader($type);


        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($contact_file);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        
        $row_counter = 0;

        foreach ($objWorksheet->getRowIterator() as $row) {
            $row_counter = $row_counter + 1;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $counter = 0;
            if ($row_counter != 1) {
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
                    if (is_numeric($teriff_rate) && preg_match('/[A-Za-z ]/', trim($cntry)))//&& preg_match('/[0-9]/',trim($ccode)))
                        $valid = 1;
                    if(isset($operator) && $operator !="" && preg_match('/[^a-zA-Z]+/', $operator))
                        $valid = 0;
                    if(!is_numeric($ccode) || $ccode < 1 || strlen(trim($ccode)) < 1 )
                        $valid = 0;
                }

                #IF VALID IS SET TO ONE THEN INSSERT EH ROW INTO THE TABLE 
                if ($valid == 1) {
                    $sql = "insert into 91_tariffs (tariffId,description, prefix, voiceRate,operator) values ('" . $t_id . "','" . trim($cntry) . "','" . trim($ccode) . "','" . trim($teriff_rate) . "','".$operator."') on DUPLICATE KEY UPDATE voiceRate='" . $teriff_rate . "',description='" . $cntry . "',operator='" . $operator . "'";
                    $sqlRes = $this->db->query($sql);
                }
            }
        }
        unlink($contact_file);
        if ($sqlRes)
            return true;
        else
            return false;
    }

//    public function readFile($file, $request) {
//
//        include_once ('excel_reader2.php');
//        ini_set('memory_limit', '500M');
//        $xls = new Spreadsheet_Excel_Reader("uploads/" . $file['name'], false);
//
//        if ($xls != "The filename uploads/" . $file['name'] . " is not readable") {
//            $rows = $xls->rowcount();
//            $cols = $xls->colcount();
//            $country = $code = $rate = array();
//            $amount = ($request['importWith']) ? ($request['importFile']) ? ($request['importFile'] == 'importInc') ? trim($request['importValue'] / 100) : -trim($request['importValue'] / 100) : 0 : 0;
//            for ($r = 1; $r < $rows; $r++) {
//                for ($c = 1; $c < $cols; $c++) {
//                    $val = trim($xls->val($r, $c));
//                    if (!is_numeric($val))
//                        array_push($country, $val);
//                    elseif (strpos($val, '.'))
//                        array_push($rate, $val);
//                    else
//                        array_push($code, $val);
//                }
//            }while ($country || $code || $rate) {
//                $v_rate = array_pop($rate);
//                $add = ($v_rate + $amount);
//                $data = array('tariffId' => $this->last_id, 'description' => array_pop($country), 'prefix' => array_pop($code), 'rate' => $add);
//                print_r($data);
//                die();
//                $this->insertData($data, '91_tariffs');
//            }
//            return true;
//        }else
//            return false;
//    }

    public function addPlan($request, $session) {
        /* @desc : funtion is used to aad the plan to the database
         * @return : returns true if success 
         */
        #VAR IS TO 0 WHEN THE EXISTING TARIIF SHOULD BE FLUSED AND 1 WHEN TARIFFS ARE APPENDED TO THE PLAN 
        $append = 0; 
        # THIS VARIABLE IS USED TO CHECK IF THE QUERY IS EXECUTED FOR DIFFERENT CASES 
        $execution = 0;
        # dafault currency value
        $curVal = 1;
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
        }

        if (isset($session['id']))
            $request['userId'] = $session['id'];

        
        if (isset($request['append'])) {
            $append = 1;
    //  number007  do not delete  
    //  $request['pid'] = $request['tariffId'];
        }
        else
            #IF APPEND IS NOT SET THE SEARCH THE EXSISTING PLAN 
            $result = $this->searchExistingPlans($request, $session);


        if ($result->num_rows == 0 || $append) {
            
            if (!isset($request['pid']) && $append == 0) {
                #INSERT ONLY IF THE NEW PLAN IS CREATED 
                $this->last_id = $this->insertPlan($request);
            }else
                $this->last_id = str_replace('%20', '', $request['tariffId']);
            #IF IN ANY CASE $REQUEST[TARIFFID] CREATES ANY PROBLEM 
            #THEN USE PID INSTEAD OF TARIFF ID AND UNCOMMENT ABOVE
            #LINE WITH NUMBER007 WRITTEN IN FRONT OF IT 
            #CURRENTLY THE PURPOSE OF PID IS NOT CLEAR IT WILL REMOVED IN FUTURE 

            #THIS CONDITION IS FOR REMOVING THE EXISTING TARIFF FORM THE PLAN 
            #AND TO INSERT THE NEW ONE AS PER USER REQUEST
            if (isset($request['rep']) && $request['rep'] == "all") {
                $condition = "tariffId=" . $request['tariffId'];
                $resultTariff = $this->deleteData('91_tariffs', $condition);
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
                        }
                        if (!$this->import_tariff_rates($file, $amount,$curVal)) {
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
                        }
                        
                        # GET THE DATA FORM THE OTHER PLAN IN THE DATABSE 
                        $results = $this->selectData($columns, '91_tariffs', $condition);

                        if ($results) {
                            while ($row = $results->fetch_array(MYSQL_ASSOC)) {
                                $voiceRate = $curVal* $row['voiceRate'];
                                $sum = ($row['voiceRate'] + ($row['voiceRate'] * $amount));
                                //$data = array('tariffId'=>$this->last_id, 'description' => trim($row['description']), 'prefix' => trim($row['prefix']),'voiceRate'=>$sum);
                                //if($this->insertData($data, '91_tariffs'))
                                $sqlSel = "INSERT INTO 91_tariffs (tariffId,description,prefix,voiceRate,operator) values ('" . $this->last_id . "','" . $row['description'] . "','" . $row['prefix'] . "','" . $sum . "','" . $row['operator'] . "') on DUPLICATE KEY UPDATE tariffId = '" . $this->last_id . "', description = '" . trim($row['description']) . "', prefix = '" . trim($row['prefix']) . "', voiceRate='" . $sum . "'";
                                if ($this->db->query($sqlSel))
                                    $execution = 1;
                                else {
                                    $msg = "Error inserting rates Please contact Provider";
                                    $execution = 8;
                                    break;
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
                        
                        if ($request['countryCode'][$i]) {
                            $prefix = str_replace('+', '', trim($request['countryCode'][$i]));
//			   $data = array('tariffId'=>$this->last_id, 'description' => trim($request['countryName'.$i]), 'prefix' => str_replace('+', '',trim($request['countryCode'.$i])), 'voiceRate'=>trim($request['rate'.$i]));

                           $sqlMan = "INSERT INTO 91_tariffs (tariffId,description,prefix,voiceRate,operator) values ('" . $this->last_id . "','" . trim($request['countryName'][$i]) . "','" . str_replace('+', '', trim($request['countryCode'][$i])) . "','" . trim($request['rate'][$i]) . "','" . trim($request['operator'][$i]) . "') on DUPLICATE KEY UPDATE tariffId = '" . $this->last_id . "', description = '" . trim($request['countryCode'][$i]) . "', prefix = '" . str_replace('+', '', trim($request['countryCode'][$i])) . "', voiceRate='" . trim($request['rate'][$i]) . "'";
                           
//                           if($this->insertData($data, '91_tariffs'))
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
            $execution;
            if ($execution != 1 && $append == 0) {
                $condition = "tariffId = " . $this->last_id;
                $this->deleteData('91_plan', $condition);
//		$this->deleteData('tariffsnames', $condition);
                return json_encode(array("msg"=>"Error inserting plan please try again","status"=>"error"));
            }else
                return json_encode(array("msg"=>"Plan Inserted successfuly","status"=>"success"));
        }else
            return json_encode(array("msg"=>"Plan already exist please try with different name","status"=>"error"));
    }

//    public function getCurrency($currency_name) {
//        /* @desc : get the currency id by name from 91_curencyDes
//         * 
//         */
//        $result = $this->selectData('currencyId', '91_currencyDesc', "currency = '" . trim($currency_name) . "'");
//        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
//            return $row['currencyId'];
//        }
//    }
//    
//
//    public function getCurrencyName($currency_id) {
//        $result = $this->selectData('currency', '91_currencyDesc', "currencyId = '" . trim($currency_id) . "'");
//        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
//            return $row['currency'];
//        }
//    }

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
                return 1;
            else
                return 0;
        }else
            echo 2;
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
        if (empty($_GET['mnpln']))
            $_GET['mnpln'] = 1;

        //query to get call history
        if ($_GET['mnpln'] == 'search') {
            $request['name'] = $request['desc'];
            $resultSearch = $this->searchExistingPlans($request, $session);
            $value['search'] = $request['desc'];
        } else {
            $sqlQuery = "select * from 91_plan where userId=" . $session['id'] . "  ORDER BY " . $_GET['mnpln'] . " LIMIT " . $start . "," . $limit;
            $resultSearch = $this->searchExistingPlans($request, $session, $sqlQuery);
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
        $returnJson = $this->getPlans($result, $session);
        return $returnJson;
    }

    public function getTarrifDetails($request, $session) {
        /* @parm : $request & $session 
         * @desc : function takes the request and set the limit variable 
         *         and pagenumber get the tariff details from 91_tariff
         */
        include_once("profile_class_sameer.php");
        
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
        $tableHeading[] = "Country Code";
        $tableHeading[] = "Country Name";
        $tableHeading[] = "Rate Plan";
        $tableHeading[] = "Deletes";
        $jade["thead"] = $tableHeading;
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

    public function manageTariff($request, $session) {
        /* @desc : set the limit and page number and call get tariff details function to fetch the tariff details 
         */
        $result['pid'] = $request['pid'];
        if (isset($request['page_number']))
            $result['page_number'] = $request['page_number'];
        $result['no_of_records'] = 25;
        return $this->getTarrifDetails($result, $session);
    }

    public function selectPlan($request, $session) {
        return $this->getPlans($result = array(), $session);
    }

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