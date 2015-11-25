<?php
/* @AUTHOR : SAMEER RATHOD 
 * @DESC : PLAN CLASS CONTAIN ALL THE FUCNTION FOR MANAGE PLAN FEATURE 
 * @FUNCTIONS INCLUDED : 
 *      #insertPlan : function insert the plan details in the 91_plan table
 *      #searchExistingPlans : search the plan if it already exist or not 
 *      #uploadFile : upload the file to the server
 *      #getConvertedCurency : convert the currency into desired currency
 *      #importTariffRates : import the tariff rate from excel file 
 *      #getOutPutCurrency  : get the output currency by tariff id from 91_plan table 
 *      #addPlan  : it consist of functionality to add and edit paln and tariff for manage plan
 *      #searchTarriff  : function to search details within tariff table 
 *      #deletePlanTariffs  : delete the tariff and backit up before deleting 
 *      #deletePlan  : used to delete the plan and backit up before deleting 
 *      #editTariff  : function call individual tariff row is to be edited 
 *      #getUserId   : function fetch the id of the user who created the plan 
 *      #countData   : genral function used to count the data from tha table 
 *      #getPlans   : fetch the plan details from 91_plan table 
 *      #getTarrifDetails   : fetch the tariff details from 91_tariff table 
 *      #getPlanName   : fetch the plan name and output currency from 91_plan table 
 *      #getUserDefaultPlan   : fetch the default plan details of the user from 91_plan which is assigned by the reseller 
 */
include dirname(dirname(__FILE__)) . '/config.php';

class plan_class extends fun {

    var $last_id;
    var $country;
    var $msg;
    var $status = 'error';
    var $errorCode;
    var $loadTariffRowCounter = 0;
    var $numberOfRowsValid = 0;

    private function insertPlan($request,$isAdmin= 0,$type = Null) 
    {
        /* @desc : funtion is used to insert plan details 
         * @return : returns the last insert id 
         */
        $table = "91_plan";
        $currency = trim($request['currency']);
        
        
        if(preg_match('/[^0-9]+/', $currency) || $currency == "") {
                $this->msg = "Invalid output currency Please select a valid output currency";
                $this->status = "error";
                $this->errorCode = "1001";
                return false;
            }
        
        $data = array("planName" => trim($request['planName']), "billingInSeconds" => trim($request['billingSec']), "outputCurrency" => $currency);
        
        if($type == "update")
        {
            $condition = "";
            if($isAdmin != 1)
                $condition =  "userId = ".$request['userId']." and ";   
            
            $condition .= " tariffId = ".$request['tariffId']."" ;
            
            $res = $this->updateData($data, $table,$condition);
            if($res)
                return true;
            else
            {
                $this->msg = "Error Updating Plan Please try again";
                $this->status = "error";
                $this->errorCode = "1002";
                return false;
            }
        }
        else 
        {
            $data['userId'] = $request['userId'];
            
            $res = $this->insertData($data, $table);
            if($res)
                return $this->db->insert_id;
            else
            {
                $this->msg = "Error inserting plan please try again later";
                $this->status = "error";
                $this->errorCode = "1003";
                return FALSE;
            }
                
        }
        
    }

    public function searchExistingPlans($planName, $userId, $like = 0, $query = NULL ,$isAdmin = 0) 
    {
        /* @desc : funtion is used to search existing plan 
         * @return : returns the result in mysqli object from  
         */
        if(preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $planName) )
                return false;
        
        $planName = $this->db->real_escape_string($planName);
        $userId = $this->db->real_escape_string($userId);
        
        if (is_null($query)) 
        {
            if( $planName == "")
                return false;
            $querySel = "SELECT SQL_CALC_FOUND_ROWS *  FROM 91_plan WHERE ";
            
            if ($like == 1)
                $querySel .=  " planName LIKE '" . $planName . "%' ";
            else
                $querySel .= " planName = '" . $planName . "' ";
            
            if($isAdmin != 1)
                $querySel .= " AND userId =" . $userId . "";
            
            $querySel .= " ORDER BY date DESC";
            
            $result = $this->db->query($querySel);
        }
        else
            $result = $this->db->query($query);
        
        return $result;
    }

    public function uploadFile($file) 
    {
        /* @desc : funtion is used to upload the file 
         * @return : returns true if success else false
         */
        $extension = substr($file['name'], strrpos($file['name'],".")+1);
       
        
        
        if (( $extension == 'xls' || $extension == 'xlsx') && (!$file['error'])) 
        {
           
            if (move_uploaded_file($file['tmp_name'], "../uploads/" . $file['name']))
                return true;
            else{
              return false;
            }
        }
        else{
            
          return false;
        }
            
    }

    public function getConvertedCurency($from, $to) 
    {
        $from = $this->getCurrencyName(trim($from));
        $to = $this->getCurrencyName(trim($to));
        
        if ($from == "" || $to == "")
            die("Invalid Currency");

//        $url = "http://".$_SERVER['HTTP_HOST']."/currency/index.php?from=$from&to=$to&amount=1";
        $url = "http://voice.phone91.com/currency/index.php?from=$from&to=$to&amount=1";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_result = curl_exec($ch);
        $curlError = curl_error($ch);
        
        if ($curlError != false || $curl_result == 0)
            mail("sameer@hostnsoft.com,sudhir@hostnsoft.com", "api not working", print_R($curlError) . " result " . $curl_result);
        curl_close($ch);
        
        
        return $curl_result;
    }

    
    
    public function importTariffRates($file, $amount, $curVal) 
    {
        /* @desc : funtion is used to import the tariff rate from xls file
         * @return : returns true if success 
         */
        
        #- INCREASE THE MAXIMUM EXECUTION TIME OF THE SCRIPT 
        
        ini_set('max_execution_time', 30000);
        error_reporting(-1);
        require_once 'PHPExcel.php';
        require_once 'PHPExcel/IOFactory.php';
        $t_id = $this->last_id;
        
        #- IF LAST INSERT ID OF THE TARIFF PLAN IS NOT SET THE THEN RETURN FALSE
        if (is_null($t_id) || $t_id == "")
            return false;

        #- GET THE FILE NAME AND EXTENSION
        ini_set('memory_limit', '512M');
        
        $contact_file = "../uploads/" . $file['name'];
        $filename_ext = $contact_file;
        $filename_ext = strtolower($filename_ext);
        $exts = split("[/\\.]", $filename_ext);

        #- SET THE TYPE OF EXCEL ACCORDING TO EXTENSION
        $n = count($exts) - 1;
        $exts = $exts[$n];
        if ($exts == "xls")
            $type = "Excel5";
        else if ($exts == "xlsx")
            $type = "Excel2007";
        else
            return false;

        #- CREATE A OBJECT READER 
        $objReader = PHPExcel_IOFactory::createReader($type);

        #- SET OBJECT READER TO READ DATA ONLY
        $objReader->setReadDataOnly(true);

        #- LOAD THE CONTACT FILE TO OBJECT READER 
        $objPHPExcel = $objReader->load($contact_file);

        #- GET THE NAME OF THE ACTIVE SHEET THIS IS USED IF MULTIPLE SHEETS ARE PRESENT 
        $objWorksheet = $objPHPExcel->getActiveSheet();

        #- INITLIZE THE ROW COUNTER
        $row_counter = 0;
        
        #- INITLIZE $J FOR ITERATOR COUNT
        $j = 0;

        #- PREPARE SQL COMMAND FOR TARIFF INSERTION 
        $sql = "insert into 91_tariffs (tariffId,description, prefix, voiceRate,operator) values ";

        #GET THE NUMBER OF ROWS FORM THE SHEET
        $countAll = $objWorksheet->getHighestRow();
        $numberOfRowsRequested = $countAll;
        $startTime = microtime(true);
        foreach ($objWorksheet->getRowIterator() as $row) {
            $row_counter = $row_counter + 1;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $counter = 0;
//            if ($row_counter != 1) {
            $valid = 0;
            foreach ($cellIterator as $cell) 
            {
                $counter = $counter + 1;
                
                #- Modified by nidhi <nidhi@walkover.in> Date: 20/12/2013
                switch($counter)
                {
                    case '1' :
                            # GET THE COUNTRY CODE FROM THE FIRST COLOUMN
                            $ccode = $cell->getValue();
                        break;
                    
                    case '2' : 
                            # COUNTRY NAME FROM THE SECOND COLOUMN
                            $cntry = $cell->getValue();
                        break;
                    
                    case '3':
                            # OPERATOR FROM THE THIRD COLOUMN
                            $operator = $cell->getValue();
                        break;
                    
                    case '4' : 
                            # GET THE TARIFF RATE VALUE FROM FOURTH COLOUMN
                            $teriff_rate = $cell->getValue();
                            if(is_numeric($teriff_rate))
                            {
                                #CONVERT THE RATE ACCORDING TO CURRENCY 
                                $teriff_rate = ($teriff_rate * $curVal);
                                # ADJUST THE TARIF RATE ACCORDING TO THE PERCENT GIVEN BY USER
                                $teriff_rate = $teriff_rate + ($teriff_rate * $amount);
                            }
                        break;
                    
                }
                
                #- VALIDATE ALL THE FIELDS IF CORRECT THEN SET VALID 1 ELSE 0 TO DISCARD THE ROW
                if (is_numeric($teriff_rate))//&& preg_match('/[0-9]/',trim($ccode)))
                     $valid = 1;
                
                $cntry = trim($cntry);
                $operator = trim($operator);
                $cntry = preg_replace('/[^A-Za-z\s]/', " ", $cntry);
                $operator = preg_replace('/[^A-Za-z\s]/', " ", $operator);
                
                
//                if (isset($operator) && $operator != "" && preg_match('/[^a-zA-Z\s]+/', $operator))
//                    $valid = 0;
                if (!is_numeric($ccode) || $ccode < 1 || strlen(trim($ccode)) < 1)
                    $valid = 0;
               
            }/* end of cell iterator */

            if($valid == 0)
            {
                $countAll--;
            }
            #IF VALID IS SET TO ONE THEN INSSERT EH ROW INTO THE TABLE 
            if ($valid == 1) 
            {  
                $sql .= "('" . $t_id . "','" . trim($cntry) . "','" . trim($ccode) . "','" . trim($teriff_rate) . "','" . $operator . "'),";
                
                #- For every 1000 iteration a single query will be executed 
                #- This is to manage the length of the query 
                
                if (($j % 1000 == 1 && $j != 1) || $j == ($countAll - 1)) {
                    
                    $sql = substr($sql, 0, -1);
                    $sqlExtend = "on DUPLICATE KEY UPDATE voiceRate=VALUES(voiceRate),description=VALUES(description),operator=VALUES(operator)";
                    $sql = $sql . $sqlExtend;
                    if ($this->db->query($sql))
                        $sqlRes = 1;
                    else {
                        echo $this->db->error;
                        $sqlRes = 0;
                        break;
                    }
                    $sql = "insert into 91_tariffs (tariffId,description, prefix, voiceRate,operator) values ";
                }
                $j++;
            }/* end of isvalid condition */
//            }/*end of row counter if*/
        }/* end of for loop */
        
        
        $this->numberOfRowsValid = $numberOfRowsRequested - $countAll;
        
        $endTIme = microtime(true);
        $totalTime = $endTIme - $startTime;
        #after completion of work unlink the file as it is not needed further 
        unlink($contact_file);
        if ($sqlRes)
            return true;
        else
            return false;
    }

    public function getOutputCurrency($tariffId) 
    {
        #GET OUTPUT CURRENCY OF PLAN FOR CURRENCY CONVERSION
        if(is_null($tariffId) || preg_match('/[^0-9]+/',$tariffId) || $tariffId == "")
            return 0;
        
        $condition = "tariffId =" . trim($tariffId);
        $curRes = $this->selectData("outputCurrency", '91_plan', $condition);
        
        if ($curRes) 
        {
            $curResRow = $curRes->fetch_array(MYSQL_ASSOC);
            return $curResRow['outputCurrency'];
        }
        else
            return 0;
    }
    
    public function validateAddPlanRequest($request)
    {
        #VALIDATE THE PLAN NAME SHOULD BE ALPHABETIC ONLY 
        if (isset($request['planName']) && !isset($request['append']) && (preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $request['planName']) || strlen(trim($request['planName'])) < 1 || strlen(trim($request['planName'])) > 55)) 
        {
            $responseArr["msg"] = "please select a valid plan name only alphanumeric ,space and  ('@','_','-')  are allowed";
            $responseArr["status"] = "error";
            $responseArr["errorCode"] = "1004";
            return json_encode($responseArr);
        }

        if (isset($request['import']) && $request['import'] == 3) 
        {
            
            
            /*FOR IMPORT TYPE 3 IE WHEN USER INSERT TARIFF MANUALLY*/
            foreach ($request['countryCode'] as $key => $value) 
            {
                $isInValid = 0;
                
                #- REPLACE THE ASCII CHARACTER 
                $rate = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '',trim($request['rate'][$key]," "));
                $countryName = trim($request['countryName'][$key]);
                $operator = trim($request['operator'][$key]);
                $countryCode = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u',"",trim($request['countryCode'][$key]," "));
                
                #- VALIDATE THE TARIFF RATE SHOULD BE NUMERIC ONLY  
                if ($rate == "" || !is_numeric($rate) || $rate <= 0 || strlen($rate) > 9) 
                {
                    $isInValid = 1;
                }
                
                #- VALIDATE THE COUNTRY NAME SHOULD BE ALPHABETIC ONLY
                if ($countryName == "" || preg_match('/[^a-zA-Z\-\_\s]+/', $countryName) || strlen($countryName) > 55) 
                {
                    $isInValid = 1;
                }
                
                #- VALIDATE THE OPERATOR SHOULD BE ALPHABETIC ONLY 
                if ($operator != "" && preg_match('/[^a-zA-Z\-\_\s]+/', $operator)) 
                {
                    $isInValid = 1;
                }
                
                #- VALIDATE COUNTRY CODE SHOULD BE NUMERIC ONLY 
                if ($countryCode == "" || preg_match('/[^0-9]+/', $value) || strlen($value) > 7 || $countryCode < 1) 
                {
                    $isInValid = 1;
                }

                #- UNSET THE ROW WHICH IS NOT VALID
                if ($isInValid) 
                {
                    unset($request['countryCode'][$key]);
                    unset($request['countryName'][$key]);
                    unset($request['rate'][$key]);
                    unset($request['operator'][$key]);
                }
            }
            
            #- IF ALL THE ROWS ARE INVALID THEN RETURN ERROR
            if (count($request['countryCode']) < 1) 
            {
                $responseArr["msg"] = "Invalid Tariff value Please enter atleast one correct entry";
                $responseArr["status"] = "error";
                $responseArr["errorCode"] = "1005";
                return json_encode($responseArr);
                
            }
        } 
        elseif (isset($request['import']) && $request['import'] == 1) 
        {
            $extension = substr($_FILES['file']['name'], strrpos($_FILES['file']['name'],".")+1);
             
            
            
            if(!isset($request['fileCurrency']) || preg_match("/[^0-9]+/", $request['fileCurrency']) || $request['fileCurrency'] == "")
            {
                $responseArr["msg"] = "Invalid file currency please please select a valid currency";
                $responseArr["status"] = "error";
                $responseArr["errorCode"] = "1006";
                return json_encode($responseArr);
            }
            
            #VALIDATE FILE EXTENSION ONLY XLS ARE ALLOWED
            if (isset($_FILES) && ( ($extension != 'xls' && $extension != 'xlsx') || $_FILES['file']['name'] == "")) {
                $responseArr["msg"] = "Invalid file please select a proper excel file";
                $responseArr["status"] = "error";
                $responseArr["errorCode"] = "1007";
                return json_encode($responseArr);
                
            }


            #VALIDATE PERCENTAGE TO INCREASE IN THE TRARIFF RATE 
            if (isset($request['importWith']) && ($request['importWith'] == 'on' || $request['importWith'] == '1')) {
                if (isset($request['importValue']) && (!is_numeric($request['importValue']) || $request['importValue'] < 1 || $request['importValue'] > 100)) {
                    $responseArr["msg"] = "Invalid rate % Please select a value between 1-100";
                    $responseArr["status"] = "error";
                    $responseArr["errorCode"] = "1008";
                    return json_encode($responseArr);
                    
                }
                
                if ( $request['rateAction'] != 'planInc' && $request['rateAction'] != 'planDec') {
                    $responseArr["msg"] = "Invalid rate action ";
                    $responseArr["status"] = "error";
                    $responseArr["errorCode"] = "1009";
                    return json_encode($responseArr);                    
                }
            }
        } elseif (isset($request['import']) && $request['import'] == 2) {
            #VALIDATE SELECT THIS IS REQUIRED FIELD VALIDATOR
            if (isset($request['plantype']) && trim($request['plantype']) == "Select") {
                $responseArr["msg"] = "Please Select a plan";
                $responseArr["status"] = "error";
                $responseArr["errorCode"] = "1010";
                return json_encode($responseArr);
                
            }
            
            
            #VALIDATE PERCENT TO INCREASE OR DECREASE
            if (isset($request['planWith']) && ($request['planWith'] == 'on' || $request['planWith'] == '1')) {
                if ((!is_numeric($request['planValue']) || $request['planValue'] < 1 || $request['planValue'] > 100)) {
                    $responseArr["msg"] = "Invalid rate % Please select a value between 1-100";
                    $responseArr["status"] = "error";
                    $responseArr["errorCode"] = "1011";
                    return json_encode($responseArr);                    
                }
                if ( $request['selRateAction'] != 'planInc' && $request['selRateAction'] != 'planDec') {
                    $responseArr["msg"] = "Invalid rate action ";
                    $responseArr["status"] = "error";
                    $responseArr["errorCode"] = "1012";
                    return json_encode($responseArr);                    
                }
            }
        } else{
                $responseArr["msg"] = "Invalid import type please provide a valid input";
                $responseArr["status"] = "error";
                $responseArr["errorCode"] = "1013";
                return json_encode($responseArr);                    
        }
        

        
        
        return $request;
    }
    public function addTariffPlan($request, $userId) {
        /* @desc : function is used to add the tariffs of plan to the database
         * @return : returns true if success 
         * @Request Param Description :
         * @[plantype] : (int) plan type is the id of the plan from which the tariff is to copied for the new plan 
         * @[filecurrency] : (string) file currency  is the currency of the tariif in the file during import from file option  
         * @[currency] : (string) this is the output currency name 
         * @[tariffId] : (int) tariff id of the plan to edit 
         * @[rep] : (string) replace all param if isset then all the tariff will be deleted and new one is inserted
         * @[import] : (int) defines the type of import id if 1 then tariff are imported from file 
         *              else 2 then it will be from other plan and 3 then tariif is inserted manualy from user
         * @[importWith] : (bool) defines the if a request to increment/decrement the existing tariff rate is true or false
         * @[importValue] : (bool) defines the whether to increment or decrement 
         * 
         */

        
        /*         * ********INITAILIZE VARIABLES*************** */
        #VAR IS TO 0 WHEN THE EXISTING TARIIF SHOULD BE FLUSED AND 1 WHEN TARIFFS ARE APPENDED TO THE PLAN 
        $append = 0;
        # THIS VARIABLE IS USED TO CHECK IF THE QUERY IS EXECUTED FOR DIFFERENT CASES 
        $execution = 0;
        # DAFAULT CURRENCY VALUE
        $curVal = 1;
        # REPALL FLAG INITLIZE
        $repAll = 0;
        #FILE CURRENCY VARIABLE INITAILIZATION
        $fileCurrency = isset($request['fileCurrency']) ? trim($request['fileCurrency']) : "";
        #OUTPUT CURRENCY VARIABLE INITAILIZATION
        $outputCurrency = isset($request['currency']) ? trim($request['currency']) : "";
        # INITIALIZE MSG VARIABLE
        $msg = "";
        
        #INITILIZE ISADMIN VARIABLE IF THE USER IS ADMIN THEN THIS FLAG WILL BE ONE 
        $isAdmin = $_SESSION['isAdmin'];
        
        
        /*****added by sameer as billing currency is removed from reseller******/
        (!isset($request['billingSec']) || $request['billingSec'] == "")?$request['billingSec'] = 60:"";
        
        /* ******************************************* */

        
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
        }
        
        if (isset($userId) && !is_null($userId) && $userId != "")
            $request['userId'] = $userId;
        else
            return json_encode (array("msg"=>"Invalid User Please try with a valid user","status"=>"error","errorCode"=>"1014"));

        
        
        #VALIDATE REQUEST PARAMETERS
        $request = $this->validateAddPlanRequest($request);
            
        
        
        #IF THE RETURN VARIABLE TYPE IS NOT AN ARRAY 
        #THEN EXIT THE FUNCTION WITH THE ERROR MESSAGE IN JSON FORMAT            
        if(!is_array($request))
            return $request;

        if (isset($request['append'])) {
            $append = 1;
            
            #IF TARIFF ID IS NOT SET THEN RETURN ERROR 
            if(!isset($request['tariffId']) || $request['tariffId'] == "")
                return json_encode (array("msg"=>"Please select a plan to edit","status"=>"error","errorCode"=>"1015"));
            
            
            
            #GET THE DEFAULT TARIFF PLAN OF THE USER 
            $userDefaultTariff = $this->getUserDefaultPlan($userId);
            
            #IF USER TRY TO EDIT DEFAULT TARIFF THEN EXIT 
            if($request['tariffId'] == $userDefaultTariff)
                return json_encode (array("msg"=>"You cannot Edit the Default Tariff Plan","status"=>"error","errorCode"=>"1016"));
            
            #SET THE TARIFF ID AS LAST INSERT ID IN CASE OF APPEND
            $this->last_id = str_replace('%20', '', $request['tariffId']);
            
            #SET THE OUTPUT TARIFF ID TO GET THE CURRENCY
            $outputTariifId = $request['tariffId'];
            
//            #IF IMPORT TYPE IS FROM PLAN THEN OUTPUT TARIFF ID WILL BE OF PLANID 
//            if($request['import'] == 2)
//                $outputTariifId = $request['plantype'];
            
            #IN CASE OF EDIT OUTPUT CURRENCY WILL BE SAME AS PLAN CURRENCY
        } else {
            
            #VALIDATE BILLING SECCOND COMMON FOR ALL FORM 
            if ((preg_match('/[^0-9]+/', $request['billingSec']) || $request['billingSec'] < 1 || $request['billingSec'] > 600)) {
                $responseArr["msg"] = "Invalid billing seconds Please select a value between 1-600";
                $responseArr["status"] = "error";
                $responseArr["errorCode"] = "1017";
                return json_encode($responseArr);
            }
            
            #IF APPEND IS NOT SET THE SEARCH THE EXSISTING PLAN 
            $result = $this->searchExistingPlans($request['planName'], $userId, 0,NULL,$isAdmin);
            if ($result && $result->num_rows == 0) {
                $insertPlanRes = $this->insertPlan($request);
                if(!$insertPlanRes)
                    return json_encode(array("msg" => $this->msg, "status" => $this->status,"errorCode"=>$this->errorCode));
                else
                    $this->last_id = $insertPlanRes;
                #incase of new plan
                $repAll = 1;
                $outputTariifId = $request['plantype'];
            }
            else
                return json_encode(array("msg" => "Plan already exist please try with different name", "status" => "error","errorCode"=>"1018"));
        }

        /* **************Get the plan currency***************** */

        #GET OUTPUT CURRENCY OF PLAN FOR CURRENCY CONVERSION
        $planCurrency = $this->getOutputCurrency($outputTariifId);
        #IN CASE OF AAPEND THE WILL BE THE CURRENCY OF THE EXISTING PLAN IN WHICH CHANGE IS REQUESTED 
        #AND FILE CURRENCY WILL BE THE PLAN CURRENCY FROM WHICH TARIFF IS BEING IMPORTED 
        if ($append == 1) {
            $outputCurrency = $planCurrency;
        }
        /* **************************************************** */

       
        #THIS CONDITION IS FOR REMOVING THE EXISTING TARIFF FORM THE PLAN 
        #AND TO INSERT THE NEW ONE AS PER USER REQUEST
        if (isset($request['rep']) && $request['rep'] == "all") {
            #if import with case 2 ie select a plan then this condition restrict the user to 
            #edit the same plan other wise it will be deleted and throwh an error 
            if ($request['plantype'] == $request['tariffId'] && $request['import'] == 2) {
                return json_encode(array("msg" => "Cannot Edit the same plan", "status" => "error","errorCode"=>"1019"));
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
                    $amount = ($request['importWith']) ? ($request['rateAction']) ? ($request['rateAction'] == 'planInc') ? trim($request['importValue'] / 100) : -trim($request['importValue'] / 100)  : 0  : 0;

                    if ($fileCurrency != "" && $outputCurrency != "") {
                        #CURRENCY CONVERSION
                        $curVal = round($this->getConvertedCurency($fileCurrency, $outputCurrency), 5);
                        if ($curVal == 0)
                            return json_encode(array("msg" => "Technical error please contact provider", "status" => "error","errorCode"=>"1020"));
                    }
                    else
                        return json_encode(array("msg" => "Technical Error please contact provider", "status" => "error","errorCode"=>"1021"));
                    
                    if (!$this->importTariffRates($file, $amount, $curVal)) {
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


                    $amount = ($request['planWith'] == 'on' || $request['planWith'] == '1') ? ($request['selRateAction']) ? ($request['selRateAction'] == 'planInc') ? trim($request['planValue'] / 100) : -trim($request['planValue'] / 100)  : 0  : 0;
                    $columns = 'prefix, `description`, voiceRate,operator';
                    $condition = "tariffId =" . trim($request['plantype']);
                    
                                        
                    #IN THIS CASE THE PLAN CURRENCY WITH OUTPUTTARIFFID EQUAL TO PLAN TYPE WILL BE THE FILE CURRENCY
                    $fileCurrency = $planCurrency;
                    
                    #INCASE OF EDIT PLAN FILE CURRENCY WILL BE THE CURRENCY OS THE PLAN TO BE IMPORTED 
                    if($append == 1)
                        $fileCurrency = $this->getOutputCurrency($request['plantype']);
                    
                    if ($outputCurrency != "" && $fileCurrency != "") {
                        #CURRENCY CONVERSION
                        $curVal = round($this->getConvertedCurency($fileCurrency, $outputCurrency), 5);
                        if ($curVal == 0)
                            return json_encode(array("msg" => "Technical error please contact provider", "status" => "error","errorCode"=>"1022"));
                    }
                    else
                        return json_encode(array("msg" => "Technical Error please contact provider", "status" => "error","errorCode"=>"1023"));

                    # GET THE DATA FORM THE OTHER PLAN IN THE DATABASE 
                    $resultsSelTariff = $this->selectData($columns, '91_tariffs', $condition);
                    $countall = $resultsSelTariff->num_rows;
                    $sqlSel = "INSERT INTO 91_tariffs (tariffId,description,prefix,voiceRate,operator) values";
                    if ($resultsSelTariff && $countall > 0) {
                        $j = 0;
                        


                        while ($row = $resultsSelTariff->fetch_array(MYSQL_ASSOC)) {
                            $vRate = (float) $row['voiceRate'];
                            #MULTIPLY THE VOICE RATE WITH CURRENCY TO GET IT CONVERTED INTO DESIRED CURRENCY E.G.( USD IS CONVERTED TO AED )
                            $voiceRate = ($vRate * $curVal);
                            
                            #THIS IS FOR INCREASING OR DECREASING THE RATE IF USER REQUEST 
                            #THE CHANGE IS GIVEN IN PRECENTAGE THAT WHY WE CALCULATE THE %
                            #CHANGE IN VOICE RATE 
                            $sum = ($voiceRate + ($voiceRate * $amount));

                            $sqlSel .= "('" . $this->last_id . "','" . $row['description'] . "','" . $row['prefix'] . "','" . $sum . "','" . $row['operator'] . "'),";

                          
                            
                            #IF REPALL FLAG IF SET THEN ENTRY WILL BE INSERTED IN BULK 
                            if (($j % 1000 == 1 && $j != 1) || $j == ($countall - 1)) {
                                $sqlSel = substr($sqlSel, 0, -1);
                                $sqlExtend = "on DUPLICATE KEY UPDATE voiceRate=VALUES(voiceRate),description=VALUES(description),operator=VALUES(operator)";
                                $sqlSel = $sqlSel . $sqlExtend;
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
                        }/* END OF WHILE */
                        
                      $this->numberOfRowsValid = $countall;
                    }/* END OF RESULT IF */ else {
                        $msg = "Error importing tariff plan";
                        $execution = 7;
                    }
                    
                }/* END OF MAIN IF CASE 2 */
                break;
            case 3:
                $sqlMan = "INSERT INTO 91_tariffs (tariffId,description,prefix,voiceRate,operator) values ";
                $numberOfrows = count($request['countryCode']);
                
                for ($i = 0; $i <= $numberOfrows; $i++) {
                    #Loop through each input field to get the value
                    if ($request['countryCode'][$i]) {
                        
                        $cName = preg_replace("/[^a-zA-Z\s]+/"," ",trim($request['countryName'][$i]));
                        $oprtor = preg_replace("/[^a-zA-Z\s]+/"," ",trim($request['operator'][$i]));
                        $prefix = str_replace('+', '', trim($request['countryCode'][$i]));
                        
                        $sqlMan .= " ('" . $this->last_id . "','" . $cName . "','" . $prefix . "','" . trim($request['rate'][$i]) . "','" . $oprtor . "'),";
                    }
                }
                $sqlMan = substr($sqlMan, 0, -1);
                $sqlExtend = " on DUPLICATE KEY UPDATE voiceRate=VALUES(voiceRate),description=VALUES(description),operator=VALUES(operator)";
                $sqlMan = $sqlMan . $sqlExtend;
               
                if ($this->db->query($sqlMan))
                {
                    $this->numberOfRowsValid = $numberOfrows;
                    $execution = 1;
                }
                else {
                    $msg = "Error inserting rates Please contact Provider";
                    $execution = 8;
                    break;
                }
                break;
        }

        # IF TARIFFS ARE NO INSERTED PROPERLY THEN DELETE THE PLAN WHICH IS INSERTED 
        if ($execution != 1 && $append == 0) {
            $condition = "tariffId = " . $this->last_id;
            # DELETE THE PLAN IN CASE OF ERROR 
            $this->deleteData('91_plan', $condition);
            return json_encode(array("msg" => ($msg == ""?"Error inserting plan please try again":$msg), "status" => "error", "type" => $execution,"errorCode"=>"1024"));
        } elseif ($execution != 1 && $append == 1) {
            return json_encode(array("msg" => ($msg == ""?"Error inserting plan please try again":$msg), "status" => "error", "type" => $execution,"errorCode"=>"1025"));
        } else {
            
            $_SESSION['captcha'] = rand(1000,9999);
            return json_encode(array("msg" => "Plan Inserted successfuly, Total $this->numberOfRowsValid  Records Inserted", "status" => "success", "insertId" => $this->last_id,"errorCode"=>"1026"));
        }
    }

   

    public function deletePlanTariffs($request, $userId,$isAdmin=0) {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @desc : function is used to delete the tariff  from the table
         */
        # condition for selecting the data 
        
        if(preg_match('/[^0-9]+/', $request['tariffId']) || $request['tariffId'] == "")
                return json_encode (array("msg"=> "Invalid tariff please select a proper tariff","staus"=>"error"));
        
        $request['tariffId'] = $this->db->real_escape_string($request['tariffId']);
        
        $selCon = "tariffId=" . $request['tariffId'] . "";
        if($isAdmin != 1)
            $selCon .= " and userId=" . $userId;
        
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
            
            $slno = $this->db->real_escape_string($slno);
            //inserting for backup this is for taking the backup before deleting the plan 
            $condition = "slNo=" . $slno . " and " . $conditionPlanTable;
//            print_R("INSERT IGNORE INTO 91_backupTariffs SELECT * FROM 91_tariffs where " . $condition);
            if ($this->db->query("INSERT IGNORE INTO 91_backupTariffs SELECT * FROM 91_tariffs where " . $condition)) {
                $resultTariff = $this->deleteData('91_tariffs', $condition);
        
                if (!$resultTariff) {
                    $response['msg'] = "Cannot delete tariff please try again";
                    $response['status'] = "error";
                    return json_encode($response);
                }
                $numRows += $this->db->affected_rows;
            } else {
                $response['msg'] = "Cannot delete tariff please try again";
                $response['status'] = "error";
            }
        }
        
        if ($resultTariff) {
            $response['msg'] = $numRows." tariff deleted succefully";
            $response['status'] = "success";
        }
        return json_encode($response);
    }

    public function deletePlan($request, $userId,$isAdmin=0) {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @desc : function is used to delete the plan from the table
         */
        
        if(preg_match('/[^0-9]+/', $request['tariffId']) || $request['tariffId'] == "")
                return json_encode (array("msg"=> "Invalid tariff please select a proper tariff","staus"=>"error"));
        
        $request['tariffId'] = $this->db->real_escape_string($request['tariffId']);
       
        
        $conditionWithUser = $condition = "tariffId=" . $request['tariffId'] . "";
        
        if($isAdmin != 1)
        {
            $conditionWithUser = $condition." and userId=" . $userId;
        }
        
        $selRes = $this->selectData("tariffId", "91_plan", $conditionWithUser);
        
        
        
        if (!$selRes || $selRes->num_rows == 0) {
            $response['msg'] = "Either You have no permission or plan doesn't exist please contact provider";
            $response['status'] = "error";
            return json_encode($response);
        }
        
        # insert plan in the backup table before deleting 
        $backUpQueryRes = $this->db->query("INSERT IGNORE INTO 91_backupTariffs SELECT * FROM 91_tariffs where " . $condition);
        
       
        if($backUpQueryRes)
        {
            $resultTariff = $this->deleteData('91_tariffs', $condition);
        
            # if tariff is deleted succesfuly hten only delete the plan else not 
            if ($resultTariff) {
                if ($this->db->query("INSERT IGNORE INTO 91_backupPlan SELECT * FROM 91_plan where " . $conditionWithUser)) {
                    $resultPlan = $this->deleteData('91_plan', $conditionWithUser);
                    $response['msg'] = "Plan Deleted Succesfuly";
                    $response['status'] = "success";
                }
                # condtion if unable to delete the plan for any reason then ot will the tariif back from the back table 
                if (!$resultPlan) {
                    $this->db->query("INSERT IGNORE INTO 91_tariffs SELECT * FROM 91_backupTariffs where " . $condition);
                    $response['msg'] = "Error deleting plan please try again 101";
                    $response['status'] = "error";
                }
            } else {
                $response['msg'] = "Error deleting plan please try again 202";
                $response['status'] = "error";
            }
        }
        else {
                $response['msg'] = "Error deleting plan please try again 302";
                $response['status'] = "error";
            }
        return json_encode($response);
    }

    public function editTariffDetails($request, $userId,$isAdmin=0) {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @desc : function is used to edit the triff rate 
         */
        
        #VALIDATE PREFIX MUST BE NUMERIC ONLY 
        if (!isset($request['prefix']) || preg_match('/[^0-9]+/', trim($request['prefix'])) || strlen(trim($request['prefix'])) > 15) {
            $responseArr["msg"] = "Invalid country code please insert proper interger value";
            $responseArr["status"] = "error";
            return json_encode($responseArr);
        }
        
        #VAILDATE COUNTY MUST BE ALPHABETIC AND CAN HAVE SPACE AND - AND _ ONLY
        if (!isset($request['country']) || preg_match('/[^a-zA-z\s\-\_]+/', trim($request['country'])) || strlen(trim($request['country'])) > 55) {
            $responseArr["msg"] = "Invalid Country name please insert proper Alphabetic value";
            $responseArr["status"] = "error";
            return json_encode($responseArr);
        }
        #VALIDATE RATE MUST BE NUMERIC OR FLOAT 
        if (!isset($request['rate']) || !is_numeric($request['rate']) || strlen(trim($request['rate'])) > 9 || $request['rate'] < 0) {
            $responseArr["msg"] = "Invalid Rate please insert proper rates ";
            $responseArr["status"] = "error";
            return json_encode($responseArr);
        }
        #as per discussion with shubhendra sir 
        if(isset($request['operator']) && (preg_match('/[^a-zA-z\s\-\_]+/',trim($request['operator'])) || strlen(trim($request['operator'])) > 18 ))
        {
            $responseArr["msg"] = "Invalid Operator Name please provide proper operator name";
            $responseArr["status"] = "error";
            echo json_encode($responseArr);
            exit();
        }
        #VALIDATE PLAN ID A PLAN SHOULD BE SELECTED FOR WHICH THE TARIFF ROW IS TO EDITED
        if (!isset($request['pid']) || !is_numeric($request['pid'])) {
            $responseArr["msg"] = "Invalid plan please select a plan to edit the tarrif";
            $responseArr["status"] = "error";
            return json_encode($responseArr);
        }
        

        if (!isset($userId) || is_null($userId) || $userId == "")
            return json_encode (array("msg"=>"Invalid User Please try with a valid user","status"=>"error"));
        
        
        #GET THE DEFAULT TARIFF PLAN OF THE USER 
        $userDefaultTariff = $this->getUserDefaultPlan($userId);
            
        #IF USER TRY TO EDIT DEFAULT TARIFF THEN EXIT 
        if($request['pid'] == $userDefaultTariff)
            return json_encode (array("msg"=>"You cannot Edit the Default Tariff Plan","status"=>"error"));
        
        
        
        # GET THE USER ID FROM 91_PLAN TABLE TO CHECK IF THE PLAN IS CRATED BY THE USER
        $planUserId = $this->getPlanUserId($request['pid']);
        if ($planUserId == $userId || $isAdmin == 1) {
            $data = array("prefix" => trim($request['prefix']), "description" => trim($request['country']), "voiceRate" => trim($request['rate']), "operator" => trim($request['operator']));
            if ($this->updateData($data, '91_tariffs', 'slNo=' . trim($request['id']).' and tariffId='.trim($request['pid'])))
                return json_encode(array('msg' => "Updated Successfuly", "status" => "success"));
            else
                return json_encode(array('msg' => "Error Updating Contact Please Try Again", "status" => "error"));
        }else
            return json_encode(array('msg' => "Please Login", "status" => "error"));
    }

    public function getPlanUserId($tariffId) {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @desc : get the user id from 91_plan by tariff id 
         */
        if(preg_match('/[^0-9]+/', $tariffId) || $tariffId == "")
                return 0;
        
        $tariffId = $this->db->real_escape_string($tariffId);
        $result = $this->selectData('userId', '91_plan', "tariffId =" .$tariffId );
        if ($result) {
            while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                return $row['userId'];
            }
        }
        else
            return 0;
    }

    public function countData($table, $condition) {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @desc function is used to count the data from 
         * 
         */
        $result = $this->selectData('count(*) as counter', $table, $condition);
        if($result)
        {
            while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                return $row['counter'];
            }
        }
        else
            return 0;
    }

    public function getPlans($request, $userId,$isAdmin = Null) {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @desc : function fetches the details of plan from 91_plan table and return the result in json format 
         */
        
        extract($request);
        
        /*****************************************/ 
        #IF IS ADMIN THEN SET THE ADMIN FLAG 
        if(is_null($isAdmin))
            $isAdmin = $_SESSION['isAdmin'];
       /*****************************************/
        
        


        #SET THE HEADING IN THE RESULT 
        $jade["heading"] = "Manage Tarrif Plans";
        //get limit if not set
        if (!isset($limit))
            $limit = 10;
        if (isset($request['limit']))
            $limit = $request['limit'];


        //get start if page no. set
        if (isset($pageNo))
            $skip = ($pageNo - 1) * $limit;
        else
        {
            $skip = 0;
            $pageNo = 1;
        }

        
                
        #THIS PARAM IS SET WHEN FUNCTION IS USED IN SEARCHING THE EXISTING PLAN
        if (empty($request['mnpln']))
            $request['mnpln'] = "DESC";

        #QUERY TO GET CALL HISTORY
        if ($request['mnpln'] == 'search' && $request['planName'] != "") {
            if(preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $request['planName']) || $request['planName'] == "")
                return json_encode (array("msg"=>"Invalid Plan Name Please provide a valid name alphabets , Numeber,space and ('@','_','-') are allowed ","status"=>"error","errorCode"=>"1026"));
            $request['name'] = $request['desc'];
            $resultSearch = $this->searchExistingPlans($request['planName'], $userId, 1,NULL,$isAdmin);
            
            $value['search'] = $request['desc'];
        } else {
            $userId  = $this->db->real_escape_string($userId);
            $mnpl  = "DESC";
            $skip  = $this->db->real_escape_string($skip);
            $limit  = $this->db->real_escape_string($limit);
            $sqlQuery = "select SQL_CALC_FOUND_ROWS * from 91_plan ";
            
            #get my default plan id
            
            
            if(!$isAdmin)
                $sqlQuery .= "where userId=" . $userId . " or tariffId=".$request['tariffId']."";
            
            
            $sqlQuery .= "  ORDER BY date " . $mnpl . " LIMIT " . $limit . " OFFSET " . $skip;

            
//            if($pageNo != 1)
//            {
//              var_dump($isAdmin) ;
//                echo $sqlQuery; 
//            }       
            $resultSearch = $this->searchExistingPlans("", $userId, 0, $sqlQuery,$isAdmin);
            $value['search'] = 'search';
        }
        
        if(!$resultSearch)
                return json_encode (array("msg"=>"Error getting plan please try again later","status"=>"error","errorCode"=>'1027'));
        
         //get total rows for above query
        $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
        if(!$resultCount)
            return json_encode (array("msg"=>"Error fetching total records","status"=>"error","errorCode"=>'1028'));
        
        $countRes = mysqli_fetch_assoc($resultCount);

        $currency = array();
        if ($resultSearch) 
        {
            while ($rows = $resultSearch->fetch_array(MYSQLI_ASSOC)) 
            {
                if (is_array(isset($tableRow)))
                    unset($tableRow);
                $tariff = $rows['tariffId'];
                
                if($tariff == $request['tariffId']){
                    $planName = "My Plan";
                }else               
                $planName = $rows['planName'];
                
                $date = $rows['date'];
                $billInSec = $rows['billingInSeconds'];
                $plan_currency = $this->getCurrencyViaApc($rows['outputCurrency'],1);
                $data['id'] = $tariff;
                $data['value'] = $planName;
                $data['date'] = $date;
                $data['billInSec'] = $billInSec;
                $data['currency'] = $plan_currency;
                $value['allvalue'][] = $data;
            }

            //find total pages for found entries
//            $value['thead'] = '';
            $value['pageNo'] = $pageNo;
//            $value['allvaluess'] = '';
//            $value['pid'] = '';
            if (isset($totalCount) && isset($limit))
                $pages = ceil($totalCount / $limit);

//            $value['count'] = $this->countData('91_plan', " userId=" . $userId . " ");
            $value['count'] = $countRes['totalRows'];
            
            $value['pages'] = ceil($countRes['totalRows']/$limit);
            $value['limit'] = $limit;
            if (!isset($value['allvalue']))
                $value['allvalue'] = '';
        }

        return json_encode($value);
    }

    
    
    private function loadTariffRates($tariffId, $first_limit, $limit,$search = 0,$keyword="", $select_type = NULL, $select_field = NULL,$export=0) {

        
        $substr = "";
        if ($select_type != "" && $select_field != "")
            $substr = "order by " . $select_field . " " . $select_type;

        if(preg_match(NOTNUM_REGX, $tariffId) || $tariffId == "")
        {
            $this->msg = "Invalid plan please select a valid plan first";
            $this->status = "error";
            return false;
        }   
        
//        $tariffId = $this->db->real_escape_string($tariffId);
        $substr = $this->db->real_escape_string($substr);
        $first_limit = $this->db->real_escape_string($first_limit);
        $limit = $this->db->real_escape_string($limit);

        $condition = "tariffId=" . $tariffId . " ";
        
        if($search == 1 && $keyword != "")
        {
            if(preg_match(NOTALPHANUMSPACE_REGX,$keyword))
            {
                $this->msg = "Invalid input to search please provide a valid data";
                $this->status = "error";
                return false;
            }
            
            if (is_numeric($keyword))
                $condition .= " AND prefix LIKE '$keyword%' ";
            else
                $condition .= " AND description LIKE '$keyword%' ";
        };

        $condition .= $substr ;
        
        if(!$export)
         $condition .= " limit " . $first_limit . "," . $limit;
        
        $r_rp_detail = $this->selectData(" SQL_CALC_FOUND_ROWS *", "91_tariffs", $condition);
//        echo $this->querry;
        if(!$r_rp_detail)
        {
            $this->msg = "Error fetching details please try again later";
            $this->status = "error";
            return false;
        }
        elseif($r_rp_detail && $r_rp_detail->num_rows < 1)
        {
            $this->msg = "Error No Records found ";
            $this->status = "error";
            return false;
        }
        
        
        $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
        if(!$resultCount)
            return json_encode (array("msg"=>"Error fetching total records","status"=>"error","errorCode"=>'1029'));
        
        $countRes = mysqli_fetch_assoc($resultCount);
        $this->loadTariffRowCounter = $countRes['totalRows'];

        return $r_rp_detail;
    }

    public function countTotalTariffs($tariffId)
    {
        if(preg_match(NOTNUM_REGX,$tariffId) || $tariffId == "")
            return false;
        
        $tariffId = $this->db->real_escape_string($tariffId);
        $result = $this->selectData("count(*) as cnt", "91_tariffs","tariffId=".$tariffId);
        $row = $result->fetch_array(MYSQLI_ASSOC);
            return $row['cnt'];
    }
    
    
     public function searchTarriff($keyword, $tariffId,$userId,$isAdmin = 0,$userDefaultTariffId,$limit = 20,$pageNumber = 0) 
    {
        /* @author : SAMEER
         * @created : 16-09-2013
         * @desc : search the tarrif rate 
         */
//        $sql = "SELECT slNo,tariffId,voiceRate,prefix,description,operator FROM 91_tariffs WHERE ";
//        if (is_numeric($keyword))
//            $sql .= " tariffId = $tariffId AND prefix LIKE '$keyword%' limit 0,20";
//        else
//            $sql .= " tariffId = $tariffId AND description LIKE '$keyword%' limit 0,20";

        if(!isset($tariffId) || preg_match(NOTNUM_REGX, $tariffId) || $tariffId == "")
                return json_encode(array("msg"=>"Invalid Tariff Id please select a tariff","status"=>"error"));
         
        $planUserId = $this->getPlanUserId($tariffId);

        
        if($userDefaultTariffId != $tariffId){       
            if (!($planUserId && ($userId == $planUserId)) && $isAdmin != 1) {
  
                return json_encode(array("msg"=>"Invalid Plan","status"=>"error"));
                
            }
        }
         
//        #SET THE LIMIT OF THE RECORDS TO ME FETCHED 
//        if (isset($request['limit']))
//            $limit = $request['limit'];
//        else
//            $limit = 20;        
        
        
        
        # CONDITION FOR PAGINATON 
        if ($pageNumber != 0)
            $first_limit = ($pageNumber - 1) * $limit;
        else
            $first_limit = $pageNumber;
        
        
       
        if( preg_match('/[^0-9]+/', $first_limit) )
                return json_encode(array("msg"=>"Invalid Input_1","status"=>"error"));
        if( preg_match('/[^0-9]+/', $limit) )
                return json_encode(array("msg"=>"Invalid Input_2","status"=>"error"));
        
         
//        $totalRows = $this->countTotalTariffs($tariffId);

        
        
        
        $res = $this->loadTariffRates($tariffId, $first_limit, $limit,1,$keyword);
//        $res = $this->db->query($sql);
        if(!$res)
        {
            return json_encode(array('msg'=>  $this->msg,'status'=>  $this->status));
        }
        
        # GET THE TOTAL TARIFF RATES
        $pages = ceil($this->loadTariffRowCounter / $limit);
        
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            $row['id_tariffs_key'] = $row['slNo'];
            $resultant['data'][] = $row;
        }
            $resultant['pages'] = $pages;
        return json_encode($resultant);
    }

    
    public function getTarrifDetails($request,$userDefaultTariffId, $userId,$isAdmin = 0,$export=0) {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @parm : $request & $session 
         * @desc : function takes the request and set the limit variable 
         *         and pagenumber get the tariff details from 91_tariff
         */
       

        $tariffId =trim($request['pid']);
        
        
        if(!isset($tariffId) || preg_match('/[^0-9]+/', $tariffId) || $tariffId == "")
                return json_encode(array("msg"=>"Invalid Tariff Id please select a tariff","status"=>"error"));
               
        # THE TARIFF ID TO BE FETCHED 
        if (!isset($tariffId))
            $tariffId = 8;
        
        $planUserId = $this->getPlanUserId($tariffId);

        if($userDefaultTariffId != $tariffId){       
            if (!($planUserId && ($userId == $planUserId)) && $isAdmin != 1) {
  
                    $msgArr['allvaluess'] = "Invalid Plan";

                return json_encode($msgArr);
                
            }
        }
        
        
        #SET THE LIMIT OF THE RECORDS TO ME FETCHED 
        if (isset($request['limit']))
            $limit = $request['limit'];
        else
            $limit = 20;        
        
        
        
        # CONDITION FOR PAGINATON 
        if (isset($request['page_number']))
            $first_limit = ($request['page_number'] - 1) * $limit;
        else
            $first_limit = 0;
        
        
       
        if( preg_match('/[^0-9]+/', $first_limit) )
                return json_encode(array("msg"=>"Invalid Input_1","status"=>"error"));
        if( preg_match('/[^0-9]+/', $limit) )
                return json_encode(array("msg"=>"Invalid Input_2","status"=>"error"));
        
        # GET THE TOTAL TARIFF RATES 
//        $totalRows = $this->countTotalTariffs($tariffId);


//        $pages = ceil($totalRows / $limit);
        
        
        #FETCH THE TARIFF RATES FROM DB  
        $r_rp_detail = $this->loadTariffRates($tariffId, $first_limit, $limit,0,"",null,null,$export);
        
        if(!$r_rp_detail)
            return json_encode(array("msg"=>$this->msg,"status"=>$this->status));
        
          # GET THE TOTAL TARIFF RATES
        $pages = ceil($this->loadTariffRowCounter / $limit);
        
        while ($rw_rp_detail = $r_rp_detail->fetch_array(MYSQLI_ASSOC)) {
            $pid = $rw_rp_detail['tariffId'];
//            $countryCode = $rw_rp_detail['countryCode'];
            $prefix = $rw_rp_detail['prefix'];
            $description = $rw_rp_detail['description'];
            $rate = $rw_rp_detail['voiceRate'];
            $operator = $rw_rp_detail['operator'];
            $id_tariffs_key = $rw_rp_detail['slNo'];
            $arrval[] = array('prefix' => $prefix, 'description' => $description,'operator' => $operator , 'voiceRate' => $rate, 'id_tariffs_key' => $id_tariffs_key, 'pid' => $pid );
        }

        $jade['allvaluess'] = $arrval;
        $jade['pages'] = $pages;
        $tariffId = $this->db->real_escape_string($tariffId);
        $jade['count'] = $this->countData("91_tariffs", "tariffId=" . $tariffId);

        if (isset($tariffId))
            $jade['pid'] = $tariffId;
        
      
        return json_encode($jade);
    }

    
    /**
     * @author : Sameer Rathod <sameer@hostnsoft.com>
     * @desc : fetches the plan name from 91_plan
     * @param : 
     *         #$fields : consist of string of fiels name to be fetchted  
     *         #$userId : userId of the logged in user
     *         #$userId : type decide from where the request come and 
     *                    what type of result id to be returned for 
     *                    type 1 only the specific plan details will be returned 
     *                    type 2 all plan details will be returned
     *         #$tariffId : tariffId consist of the plan id by defalut it is null 
     * @abstract : 1) used from api for getplanlist api
     */
        
    public function getPlanName($fields, $userId,$type,$tariffId = null,$isAdmin=0) 
    {
        
        
        $fields = trim($fields);
        #IF USER ID IS  NUL THEN EXIT
        if(is_null($userId) || is_null($fields) || preg_match('/[^a-zA-Z\,]/', $fields))
            return json_encode (array("msg"=>"Error Invalid data User","status"=>"error","code"=>501));
        
        if(preg_match('/[^0-9]+/', $tariffId))
            return json_encode (array("msg"=>"Error Invalid tariff Id ","status"=>"error","code"=>502));
        
        $condition = "";
        
        #IF TARIFF ID IS NOT NUL THEN CONDITION WILL FETCH THE SEPECIFIC TARIFF ID DETAILS 
        $tariffId = $this->db->real_escape_string($tariffId);
        
        
        if(!is_null($tariffId) && $tariffId != "" && $isAdmin == 1)
        {
            $condition .= 'tariffId=' . $tariffId . '';
        }
        elseif(!is_null($tariffId) && $tariffId != "" && $isAdmin != 1)
        {
            $condition .= 'tariffId=' . $tariffId . ' and ';
        }
        elseif((is_null($tariffId)|| $tariffId == "") && $isAdmin == 1)
            $condition = "1";
        
        $defaultTariffId = $this->getUserDefaultPlan($userId);
        if(!$defaultTariffId)
            return array("msg" => "Error Unable to fetch the user defautl plan","status"=>"error","code"=>503);    
            
        if($isAdmin != 1)
        {
            $condition .= ' userId=' . $userId;
          if($defaultTariffId > 0 && $type != 1)
                     $condition .= ' or tariffId='.$defaultTariffId;
        }
//        echo $condition;
        $result = $this->selectData($fields, '91_plan', $condition);
        
        if ($result)
        {
            if($type == 1)
            {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                if($tariffId == $defaultTariffId){
                    $planNameArr['planName'] = "My Plan";
                }else
                $planNameArr['planName'] = $row['planName'];
                
                $planNameArr['billingInSeconds'] = $row['billingInSeconds'];
                $planNameArr['currencyId'] = $row['outputCurrency'];        
                $planNameArr['currency'] = $this->getCurrencyViaApc($row['outputCurrency'],1);        
                return $planNameArr;
            }
            elseif($type == 2)
            {
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
                {
                    if($row['tariffId'] == $defaultTariffId)
                        $row['planName'] = "My Plan";
                    $row['currency'] = $this->getCurrencyViaApc($row['outputCurrency'],1);
                    $planNameArr[] = $row;
                }
                return json_encode($planNameArr);
            }
        }
        else                    
        return json_encode (array("msg"=>"No data found","status"=>"error","code"=>504));
        
    }
    
    public function getUserDefaultPlan($userId) 
    {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @desc : fetches the user Defult plan form userbalance
         * 
         */
        
        if(is_null($userId) || preg_match(NOTNUM_REGX, $userId) || $userId == "")
            return 0;
        
        $result = $this->selectData('tariffId', '91_userBalance', 'userId=' . $userId);
        
        if ($result) 
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            return $row['tariffId'];
        }
        
        return 0;
    }
    
    public function editPlanName($tariffId,$planName,$userId) 
    {
        /* @author : Sameer Rathod <sameer@hostnsoft.com>
         * @desc : edit the plan name from 91_plan
         * 
         */
        
        if($planName == "" || preg_match(NOTPLANNAME_REGX, $planName))
                return json_encode (array("msg"=>"Invalid Plan Name Please Enter a Valid Plan Name","status"=>"error"));
        
        if($tariffId == "" || preg_match( NOTNUM_REGX , $tariffId ))
                return json_encode (array("msg"=>"Invalid Plan please Select A Plan","status"=>"error"));
        
        
        if($userId != "" && is_numeric($userId))
        {
            $data = array("planName"=>$planName);
            $this->db->update('91_plan',$data)->where("userId = ".$userId." AND tariffId = ".$tariffId."");
            
            $result = $this->db->execute();
            
            if ($result) 
            {
                $msg = "successfuly updated the plan name";
                $status = "success";
            }
            else
            {
                $msg = "Error updating plan name";
                $status = "error";
            }
            return json_encode(array("msg"=>$msg,"status"=>$status));
        }
    }
    
    public function addPlanFromAdmin($planName,$outputCurr,$billingSec,$userId,$type = 'add',$tariffId=Null)
    {
        $isAdmin = $_SESSION['isAdmin'];
        $planName = trim($planName);
        
        if(preg_match('/[^0-9a-zA-Z\s]+/', $planName) || $planName == "" || strlen($planName) < 1 || strlen($planName) > 55)
                return json_encode(array("msg"=>"Invalid plan name please enter a valid plan name only alphabets, number and space is allowed","status"=>"error"));
        if(preg_match(NOTNUM_REGX, $outputCurr) || $outputCurr == "")
                return json_encode(array("msg"=>"Invalid Output Currency Please Select a Valid Output Currency","status"=>"error"));
        if(preg_match(NOTNUM_REGX, $billingSec) || $billingSec == "" || $billingSec <1 || $billingSec > 600)
                return json_encode(array("msg"=>"Invalid Billing seconds Please enter a valid Billing second ","status"=>"error"));
        if(preg_match(NOTNUM_REGX, $userId) || $userId == "")
                return json_encode(array("msg"=>"Invalid user please login ","status"=>"error"));
        
        if($isAdmin != 1)
            return json_encode(array("msg"=>"Invalid user premission only for admin","status"=>"error"));
        
        $request['currency'] = $outputCurr;
        $request['planName'] = $planName;
        $request['billingSec'] = $billingSec;
        $request['userId'] = $userId;
        $request['tariffId'] = $tariffId;
        
        if($type == 'edit')
        {
            if(is_null($tariffId) || preg_match('/[^0-9]+/', $tariffId) || $tariffId == "")
                 return json_encode(array("msg"=>"Invalid tariff Id Please select a tariff","status"=>"error"));   
            
            $updatePlanRes = $this->insertPlan($request,$isAdmin,"update");
            
            if($updatePlanRes)
                return json_encode(array("msg"=>"Successfuly updated plan ","status"=>"success"));
            else
               return json_encode(array("msg" => "Error updating Plan Please try again", "status" => "error")); 
        }
        else
        {
            $result = $this->searchExistingPlans($planName, $userId, 0,NULL,$isAdmin);

            if ($result->num_rows == 0) 
            {
                $insertPlanRes = $this->insertPlan($request,$isAdmin);
                if($insertPlanRes)
                {
                    $request['currency'] = $this->getCurrencyViaApc($outputCurr);
                    $request['lastId'] = $this->db->insert_id;
                    unset($request['userId']);
                    return json_encode(array("msg"=>"Successfuly inserted plan ","status"=>"success","lastInsertedData"=>$request));
                }
                else
                    return json_encode(array("msg" => "Error Inserting Plan Please try again", "status" => "error"));
            }
            else
                return json_encode(array("msg" => "Plan already exist please try with different name", "status" => "error"));
        }
         
    }

    public function exportTariffRates($tariffArray,$type)
    {
        if(empty($tariffArray) || !is_array($tariffArray) || $tariffArray == "")
        {
            $this->msg = "Error Unable to fetch details please try again later or contact provider";
            $this->status = "error";
            return false;
        }
        
        if($type == "csv")
        {
            $timeStamp =date('d_m_y_H_i_s');
            $fileName = dirname(dirname(__FILE__))."/exportFiles/".$timeStamp.".csv";
            
           
            if(file_exists($fileName))
                return $fileName;
            
            $fp = fopen($fileName, "w");
            
            foreach ($tariffArray as $innerArray)
            {
                unset($innerArray['id_tariffs_key']);
                unset($innerArray['pid']);
                 fputcsv($fp, $innerArray);
            }
            fclose($fp);
            return $fileName;
        }
        elseif($type == "xlsx")
        {
                ini_set('max_execution_time', 30000);
                
                require_once 'PHPExcel.php';
                
                require_once 'PHPExcel/Writer/Excel2007.php';
        
                 #- GET THE FILE NAME AND EXTENSION
                ini_set('memory_limit', '512M');

                $objPHPExcel = new PHPExcel();
                // Set properties
           
                $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Tariff Rate");
                $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Tariff Rate");
        


            // Add some data
        
                $objPHPExcel->setActiveSheetIndex(0);
                $i = 1;
                foreach($tariffArray as $innerArray)
                {

                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $innerArray['prefix']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $innerArray['description']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $innerArray['operator']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $innerArray['voiceRate']);
                    $i++;
                }

            // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('Tariff');


            // Save Excel 2007 file
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//                $objWriter =  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                
//                $objWriter->setOffice2003Compatibility(true);
                $timeStamp =date('d_m_y_H_i_s');
                $fileName = dirname(dirname(__FILE__))."/exportFiles/".$timeStamp.".xlsx";
//                $fileName = $timeStamp.".xlsx";
                
                $res = $objWriter->save($fileName);
                
                
                return $fileName;
        }
        else {
                $this->msg = "Error Invalid format can not export the file";
                $this->status = "error";
                return false;
        }
    }
    
    public function downloadFile($filename)
    {
         

        // required for IE, otherwise Content-disposition is ignored
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        // addition by Jorg Weske
        $file_extension = strtolower(end(explode(".",$filename)));
         

        if ($filename == "") {
            echo "<html><title> Download </title><body>ERROR: download file NOT SPECIFIED. </body></html>";
            exit;
        } elseif (!file_exists($filename)) {
            echo "<html><title> Download </title><body>ERROR: File not found. </body></html>";
            exit;
        }
        
        switch ($file_extension) {
            case "pdf": $ctype = "application/pdf";
                break;
            case "exe": $ctype = "application/octet-stream";
                break;
            case "zip": $ctype = "application/zip";
                break;
            case "doc": $ctype = "application/msword";
                break;
            case "xls": $ctype = "application/vnd.ms-excel";
                break;
            case "xlsx": $ctype = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                break;
            case "ppt": $ctype = "application/vnd.ms-powerpoint";
                break;
            case "gif": $ctype = "image/gif";
                break;
            case "png": $ctype = "image/png";
                break;
            case "jpeg":
            case "jpg": $ctype = "image/jpg";
                break;
            default: $ctype = "application/force-download";
        }
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers 
        header("Content-Type: $ctype");
// change, added quotes to allow spaces in filenames, by Rajkumar Singh
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($filename));
        readfile("$filename");
        exit();
    }
    
    
}
