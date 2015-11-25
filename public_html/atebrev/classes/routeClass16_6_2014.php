<?php
/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
include dirname(dirname(__FILE__)) . '/config.php';
class routeClass extends fun
{
    var $msg = "";
    var $validateFlag = false;
    private $defaultEmail = 'ankitpatidar@hostnsoft.com';
    private $routeId;
    private $userId;
   /**
     * updated by Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @param array $request
     * @return int
     * @since 26/05/2014
     * @abstract callingPlaces functions of current class #addRoute and #editRouteInfo
     */
    function validateRouteParam($request){
        if(empty($request['routeName']) || preg_match('/[^a-zA-Z0-9]+/',$request['routeName']))
        {
            $this->msg = "Error Invalid Route Name Please Enter a Valid Route Name. Only Aplphabet and Numbers are Allowed";
            return 0;
        }
//        if(preg_match('/[^0-9]+/',$request['routeQuality']))
//        {
//            $this->msg = "Error Invalid Route Quality Value";
//            return 0;
//        }
//        if(preg_match(NOTUSERNAME_REGX,$request['routeUserName']))
//        {
//            $this->msg = "Error Invalid Route User Name Please Enter a Valid Data";
//            return 0;
//        }
//        if(preg_match(NOTPASSWORD_REGX,$request['routePassword']))
//        {
//            $this->msg = "Error Invalid Route Password Please Enter only alphaNumber and (@,$,},{,.,_,-,(,),],[,:)";
//            return 0;
//        }
        
       // $countIps = count($request['routeIps']);
        
        //for($i = 0; $i < $countIps ; $i++)
        //{
            if(preg_match('/[^0-9\.]+/',$request['routeIps']) || strlen($request['routeIps']) < 7 || strlen($request['routeIps']) >15 || !filter_var($request['routeIps'], FILTER_VALIDATE_IP) )
            {
                $this->msg = "Error Invalid Route Ip please enter a valid ip address";
                return 0;
            }
        //}
        
//        if(preg_match('/[^0-9]+/',$request['routeCallLimit']) || $request['routeCallLimit'] < 1 || $request['routeCallLimit'] > 10)
//        {
//            $this->msg = "Error Invalid Route Call Limit please enter a valid number between 1 and 10";
//            return 0;
//        }
        if(!empty($request['routePrefix']) && preg_match('/[^0-9\*\#\+]/',$request['routePrefix']))
        {
            $this->msg = "Error Invalid Route prefix please enter a valid prefix you can use numeric character and (*,#,+) only";
            return 0;
        }
        
        if(empty($request['tariff']) || $request['tariff'] == 'Select')
        {
            $this->msg = "Error Invalid tariff please select a tariff!!!";
            return 0;
        }
        
       
        $this->validateFlag = true;
        return 1;
    }
    
    /**
     * 
     * @param array $request
     * @return \json@author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 26/05/2014
     * @param array $request contains required param
     * @return json
     * @filesource
     * @abstract calling places from routeController.php
     */
    function addRoute($request){
        
       
        $validateResult = $this->validateRouteParam($request);        
        if(!$validateResult || $this->validateFlag == false)
            return json_encode(array("msg"=>$this->msg,"status"=>"error"));
        
            
        $routeName = $this->db->real_escape_string($request['routeName']);
        //code to check for unique route name
        $routeTable = '91_route';
        $checkres= $this->selectData('*',$routeTable,"route='$routeName'");
        
       
        if(!$checkres)
        {
             trigger_error('Problem while route add:'.json_encode($request).' query:'.$this->querry);
            return json_encode(array("status"=>"error","msg"=>"Problem while route add!"));
        }
        else if($checkres->num_rows > 0)
            return json_encode(array("status"=>"error","msg"=>"Route already exists!!!"));
        
        //apply sql injection
        
        if(!empty($request['routePrefix']))
            $routePrefix = $this->db->real_escape_string($request['routePrefix']);
        else
            $routePrefix = '';

        $routeTariff = $this->db->real_escape_string($request['tariff']);
        $routeIp = $this->db->real_escape_string($request['routeIps']);
        
        #insert routedetail into database       
        $data=array("route"=>$routeName,
                    "optPrefix"=> $routePrefix,
                    'ip' => $routeIp,
                    'tariffId' => $routeTariff,
		    'routeCredits' => 0
                    );
      
        
      #insert query (insert data into 91_personalInfo table )
      $result = $this->insertData($data, $routeTable);

      
       if(!$result){
          trigger_error('Problem while route add:'.json_encode($data).' query:'.$this->querry);
          
        return json_encode(array("status"=>"error","msg"=>"Problem while route add!"));
          
      }
      
      $routeId = $this->db->insert_id;
      //insert in diverted route table
      $divertData = array('routeId' => $routeId,
                          'divertedRouteId' => $routeId);
      
      $divertedResult = $this->insertData($divertData, '91_divertedRoute');
      
      if(!$divertedResult || $this->db->affected_rows == 0)
      {
          trigger_error('Problem while diverted route add:'.json_encode($divertData).' query:'.$this->querry);
          
        return json_encode(array("status"=>"error","msg"=>"Problem while route add!"));
          
      }
      
      $outPutCurr = $this->getOutputCurrency($routeTariff);
      
      //insert in route balance table
//      $routeBalArr = array('routeId' => $routeId,
//                          'tariffId' => $routeTariff,
//			  'balance' => 0,
//			  'currencyId' => $outPutCurr);
//      
//      $routeBalRes = $this->insertData($routeBalArr, '91_routeBalance');
//      
//      if(!$routeBalRes || $this->db->affected_rows == 0)
//      {
//	  trigger_error('problem while insert in route balance,qur'.$this->querry);
//	  return json_encode(array('status' => 'error' ,"msg" => 'Problem while route add!!!'));
//      }
	

      $currName = $this->getCurrencyViaApc($outPutCurr,1);
    
      if(!$currName )
      {
	  trigger_error('problem while get currency name');
	  return json_encode(array('status' => 'error' ,"msg" => 'Problem while route add!!!'));
      }
      
      
      return json_encode(array('status' => 'success' ,"msg" => 'Route successfully added','lastId' => $routeId,'currency' =>$currName));
        
    }
    
     /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 27/05/2014
     * @param array $request
     * @uses function to edit route info
     * @abstract calling from routeContoller
     * @return type
     */
    function editRouteInfo($request)
    {
        if(empty($request['routeId']) || preg_match(NOTNUM_REGX,$request['routeId']))
                return json_encode(array("msg"=>'Session expired please login again',"status"=>"error"));
               
        $routeId= $this->db->real_escape_string($request['routeId']);
        
        $validateResult = $this->validateRouteParam($request);        
        if(!$validateResult || $this->validateFlag == false)
            return json_encode(array("msg"=>$this->msg,"status"=>"error"));
        
        $routeName = $this->db->real_escape_string($request['routeName']);
        
        $updateDataArr = array();
        
        if($request['routeName'] != $request['oldRouteName'])
        {
            $checkRouteExists = $this->checkRouteExists($request);
        
            if($checkRouteExists == 0)
            return json_encode(array("msg"=> 'route name already exists!!!',"status"=>"error"));
            
          $updateDataArr['route'] =  $routeName; 
        }
        //var_dump($request);
        //apply sql injection
        
        if(!empty($request['routePrefix']))
            $routePrefix = $this->db->real_escape_string($request['routePrefix']);
        else
            $routePrefix = '';

        $routeTariff = $this->db->real_escape_string($request['tariff']);
        $routeIp = $this->db->real_escape_string($request['routeIps']);
        
        //update data
        $updateDataArr['optPrefix']=$routePrefix;
        $updateDataArr['ip']=$routeIp;
        $updateDataArr['tariffId']=$routeTariff;
        $updateRes = $this->updateData($updateDataArr,'91_route','routeId='.$routeId);
       
        //apply error handling
        if(!$updateRes)
        {
            trigger_error('problem while route detail updation ,qur'.$this->querry);
            return json_encode(array("msg"=> 'problem while updating details!!!',"status"=>"error"));
        }
        else if ($updateRes && $this->db->affected_rows == 0)
        {
            return json_encode(array("msg"=> 'Route detail not updated!!!',"status"=>"error"));
        }
        
        return json_encode(array("msg"=> 'Route detail successfully updated!!!',"status"=>"success"));
        
    }
    
    /**
     * 
     * @param type $amount
     * @param type $currentBalance
     * @param type $paymentType
     * @param type $debit
     * @param type $credit
     * @param type $closingBalance
     * @param type $description
     * @param type $currency
     * @return int@author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 
     * @uses to add transaction log entry
     * @abstract calling places from current class fucntions addRouteTransactional function 
     */
    function addRouteTransactional_sub($amount,$currentBalance,$paymentType,$debit,$credit,$closingBalance,$description,$currency=1)
    {
	
	 #- Converting current debit and credit  amount To USers Base Currency.
        $debitCntAmt = $this->routeClosingBalCurrencyCnvt($this->routeId,$currency,$debit);
        
        $creditCntAmt = $this->routeClosingBalCurrencyCnvt($this->routeId,$currency,$credit);
        #add taransaction detail into taransation log table 
        $transactionlog = "91_routeTransactionLog";  
        
        $paymentType = $this->db->real_escape_string($paymentType);
        $description = $this->db->real_escape_string($description);
        $data=array("fromUser" => $this->userId,
		    "routeId" => $this->routeId,
		    "date" => date('Y-m-d H:i:s'),
		    "amount" => $amount,
		    "currentBalance" => $currentBalance,
		    "debit" => $debit,
		    "credit" => $credit,
		    "paymentType" => $paymentType,
		    "closingBalance" => $closingBalance,
		    "description" => $description,
		    "debitConvert" => $debitCntAmt, 
                    "creditConvert" => $creditCntAmt,
		    "currency" => $currency
		); 
        
	
        #if transaction done by account manager.
        if(isset($_SESSION['acmId']) || $_SESSION['acmId'] != ''){
            $data['changedBy'] = 1;
            $data['accManagerId'] = $_SESSION['acmId'];
        }
	
	
        #insert query (insert data into 91_tempEmails table )
        $res = $this->db->insert($transactionlog, $data);	
        $qur = $this->db->getQuery();
	
        $savedata = $this->db->execute();
        if(!$savedata){
         $this->sendErrorMail($this->defaultEmail,"Problem while insert query fail : $qur ");
        return 0;
        }else
        return 1;
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @uses convert amount in perticuler currency
     * @abstract calling places from current class functions addRouteTransactional,updateRouteClosingBalance,addRouteTransactional_sub
     * @param type $routeId
     * @param type $currency
     * @param type $amount
     * @return int
     */
    function routeClosingBalCurrencyCnvt($routeId,$currency,$amount)
    {
	 if(empty($routeId) || preg_match(NOTNUM_REGX,$routeId) )
               return 0;
	
        if( $amount == 0 )
             return 0;
        
	$param['routeId'] = $routeId;
        #- Getting currency Id.
        $routeDetailJson = $this->getRoutedetail($param,$this->userId);
        
	$routeDetail = json_decode($routeDetailJson,TRUE);
            
        if($currency != $routeDetail['currencyId'])
        {
            $paymentCurrency = $this->getCurrencyViaApc($currency, 1);
            $userCurrency = $this->getCurrencyViaApc( $routeDetail['currencyId'] , 1 );
            $debitCntAmt = $this->currencyConvert($paymentCurrency, $userCurrency, $amount); 
           
        }
        else
        {
            $debitCntAmt = $amount;
        }
        
	
        return $debitCntAmt;
    }
    
    
   /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 28/05/2014
     * @abstract calling places from current class funtions addRouteTransactional,updateRouteClosingBalance
     * @filesource
     * @param int $id
     * @param int $type
     * @return int
     */
    function getRouteClosingBalance( $id , $type = NULL)
    {
	 if(empty($id) || preg_match(NOTNUM_REGX,$id) )
               return 0;
	
        #- Table name 
        $table = '91_routeClosingAmt';
        
        #- Condition to Find Closing Balance
        $condition = "routeId = '" . $id . "'";

        #- Function To Fetch Records( This Is Common function. currently fetching closingAmount )
        $result =  $this->selectData( 'closingAmt', $table, $condition );
        
        if(!$result)
	{
            trigger_error('problem while get route closing balance detail ,condition:'.$condition);
	    return 0;
	}
        #- Getting closingAmount
        if( $result->num_rows > 0 ) 
        {	
            if($type == 1)
                $balanceResponse = $result->num_rows ; #- returnin number of rows in some cases.
            else
            {
                while($row = $result->fetch_array(MYSQL_ASSOC) ) 
                {
                    $balanceResponse = $row["closingAmt"];
                }
            }
        }
        else #- No Records Found
        {
            $balanceResponse = 0;
        }

        return $balanceResponse;
    }
    
 /**
    * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
    * @since 28/05/2014 
    * @param float $amount
    * @param int $talktime
    * @param string $paymentType
    * @param string $description
    * @param string $type
    * @param float $partialAmt
    * @param int $currency
    * @param  int $partialCurrency
    * @param string $status
    * @param int $addreduceTrans
    * @abstract calling places from current class functions editFundRoute,addReduceTransactionRoute
    * @return int  */
    function addRouteTransactional($amount,$talktime,$paymentType,$description,$type,$partialAmt = 0,$currency = 0,$partialCurrency = 0 , $status = NULL,$addreduceTrans = 0)
    { 
        #- Check amount limit if amount is greaterthen 1000 then mail send to admin 
        if($talktime > 1000)
        {
            $this->sendErrorMail($this->defaultEmail,"amount is greater then 1000 rs in transaction log .");
        }
        
        #- Find closing amount form 91_closingAmount table
        $getBalance = $this->getRouteClosingBalance($this->routeId);
      
        $clsamount = $this->routeClosingBalCurrencyCnvt($this->routeId,$currency,$amount);
        
        #- Calculating closing balance
        #- Check for amount add or reduce in transaction 
        if($status == "add")
        {
            $debit = 0;
            $credit = $amount;
            $closingBalance = ((float)$getBalance - (float)$clsamount);
        }
        else
        {
            $debit = $amount;
            $credit = 0;      
            $closingBalance = ((float)$getBalance + (float)$clsamount);
        }
        
        #- Get current balance form 91_routeBalance table
        $currBalance = $this->getCurrentRouteBalance($this->routeId);
        
        if($addreduceTrans == 1){
            $transValue = $paymentType;
        }else
            $transValue = "voip"; 
        
        #- Add transaction in case of voip91(payment type).
        $result = $this->addRouteTransactional_sub($talktime,$currBalance,$transValue,$debit,$credit,$closingBalance,$description,$currency);    
        
        #- If type is prepaid (advance) 
        if($type == "prepaid")
        {
            $closingBalance = ((float)$closingBalance - (float)$clsamount);  
          
            #- Add transaction with given payment type (cash,memo,bank or other).
            $closingBalanceResult = $this->addRouteTransactional_sub(0,$currBalance,$paymentType,0,$amount,$closingBalance,$description,$currency);       
        }
        else if($type == "partial")  #- If type is partial
        {
            $partialbal = $this->routeClosingBalCurrencyCnvt($this->routeId,$partialCurrency,$partialAmt);
	    trigger_error('partial convertino:partialCurr:'.$partialCurrency.' partialAmt:'.$partialAmt.' routeId:'.$this->routeId.' partialBal:'.$partialbal.' closingBal:'.$closingBalance);
            $closingBalance = ((float)$closingBalance - (float)$partialbal);
            
            #- Add  partial transaction with given payment type (cash,memo,bank or other).
            $closingBalanceResult = $this->addRouteTransactional_sub(0,$currBalance,$paymentType,0,$partialAmt,$closingBalance,$description,$partialCurrency);
        }
        
        #- Update closing balance of user 
        $updateClosing = $this->updateRouteClosingBalance($this->routeId,$closingBalance);
        
        if(!$updateClosing)
            trigger_error('problem while get closing balance detail ,routeId:'.$this->routeId.' closeBal:'.$closingBalance);
             
        if($result == 1 || $closingBalanceResult == 1)
        {
            return 1;
        } 
        else
            return 0;
        
    }
    
    
    
     /**
     *@author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @abstract calling places from current class functions addRouteTransactional
     * @param type $id
     * @param type $amount 
     */
     function updateRouteClosingBalance($id,$amount)
    {
         if(empty($id) || preg_match(NOTNUM_REGX,$id) )
               return 0;
	 
	 $param['routeId'] = $id;
        #get user currency id and reseller id 
        $routeDetailJson = $this->getRouteDetail($param,$this->userId);
        
	$routeDetail = json_decode($routeDetailJson,TRUE);
	
        $currencyId = $routeDetail['currencyId'];
        //$resellerid = $userDetail['resellerId'];
        
        #get reseller converted amount (reseller get amount in his currency)
        $resellerCurrAmt = $this->routeClosingBalCurrencyCnvt($id,$currencyId,$amount);
        
        #- Getting entry of this user in table exists or not.
        $userExists = $this->getRouteClosingBalance( $id ,1);
        
        $table = '91_routeClosingAmt';
        
        #- If user closing balance present then update closing balance otherwise add closing balance into table
        if ($userExists > 0) 
        {	    
            #update closing amount of user 
            $data = array("closingAmt" => $amount , 
		"lastUpdate" => date('Y-m-d H:i:s'));   
            $condition = "routeId = ".$id;
            $this->db->update( $table, $data )->where($condition);
        }
        else
        {
            #- Insert closing amount of user
            $data = array( "routeId" => (int)$id , 
			    "closingAmt" => $amount , 
			    "lastUpdate" => date('Y-m-d H:i:s'),
			 );
            $this->db->insert( $table, $data );
        }
        
        $query = $this->db->getQuery();
        $result = $this->db->execute();   
        
        if(!$result)
        {
            trigger_error('problem while get route closing balance detail ,data:'.json_encode($data));
	    return 0;
        }
	
	return 1;
    }
    
   /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @abstract calling Places getRoute,editFundRoute,addRouteTransactional
     * @return number
     */
     function getCurrentRouteBalance($id)
    {
	 if(empty($id) || preg_match(NOTNUM_REGX,$id) )
               return 0;
        #- Table name 
        $table = '91_route';
        
        $condition = "routeId = '" . $id . "'";
       
        #- Find current balance of user 
        $result =  $this->selectData( 'routeCredits', $table, $condition );
        
        #- Variable balance use for store current balance data
        if ($result->num_rows > 0) 
        {	
            while ($row= $result->fetch_array(MYSQL_ASSOC) ) 
            {
                $currentBalance = $row["routeCredits"];
            }
        }
        else
        {
            $currentBalance = 0;
            trigger_error('problem while get route balance,condition:'.$condition);
        }
        return $currentBalance;
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 26/05/2014
     * @uses check route name exists or not
     * @abstract calling Places from current class function checkRouteExists and from routeController 
     * @filesource
     */
  function checkRouteExists($request)
  {
      
      if(empty($request['routeName']) || preg_match(NOTUSERNAME_NORMAL_REGX,$request['routeName']))
        return 0;
      
      $routeName = $request['routeName'];
        
      $table = '91_route';
      $this->db->select('route')->from($table)->where("route = '$routeName' ");

      $result = $this->db->execute();

        // processing the query result
        if ($result->num_rows > 0) {
            return 0; //echo "Sorry username already in use";
            
        }
        else
        {
            return 1;
            
        }

  }
    
    /**
   * 
   * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
   * @uses function to get route list 
   * @abstract calling places from current class functions getRouteDetail ,routeController from dailplan-setting.php
   * @return array
   */
    function getRouteList()
    {
         $selRes = $this->selectData("*", "91_route",'1');
       
        if(!$selRes || $selRes->num_rows < 1)
        {
            return array();
        }
        
        $data = array();
        while($row = $selRes->fetch_array(MYSQLI_ASSOC))
        {
            $data[$row['routeId']] = $row['route'];
        }
        
        return $data;
    }
  
   /**
   * @author Ankit patidar<ankitpatidar@hostnsoft.com>
   * @param type $request
   * @param type $userId
   * @abstract calling Places from routeController and from manage-route-setting.php
   * @return boolean
   * @since 26/05/2014
   */
    function getRoute($request,$userId)
    {
        $q='';
      
        if(!empty($request['q']))
            $q = $request['q'];
        
        
        if(empty($userId) || preg_match(NOTNUM_REGX,$userId) )
               return false;
        
        //get tariff id and name
        $tariffArr = $this->getTariffIdandName($userId);
        
       
        if(empty($tariffArr))
            return false;
        
        $currListJson = $this->getCurrencyList();
        $currArr = json_decode($currListJson,TRUE);
  
	
        if(empty($currArr))
            return false;
        
        if($q != '')
            $condition = "route LIKE '%$q%'";
        else
            $condition = '1';
        
        $selRes = $this->selectData("*", "91_route",$condition);
       
        if(!$selRes || $selRes->num_rows < 1)
        {
            return json_encode(array());
        }
    
        $detailArr = array();
       
	
        while($row = $selRes->fetch_array(MYSQLI_ASSOC))
        {
	    
            $data['id'] = $row['routeId'];
            
	    $data['routeName'] = $row['route'];
            $data['planName'] = $tariffArr[$row['tariffId']];

            //get tariff output currency
            $outPutCurr = $this->getOutputCurrency($row['tariffId']);
           
            if(empty($outPutCurr) || !is_numeric($outPutCurr))
                return false;
	    
	    $balance =  $this->getCurrentRouteBalance($row['routeId']);
	    
            $data['balance'] = $balance;
            $data['currency'] =$currArr[$outPutCurr] ;

            $detailArr[] = $data;

            unset($data);
            unset($row);
        }

       
        return json_encode($detailArr);

       
    }
    
    /**
     * 
     * @param type $routeId
     * @return int@author Ankit Patidar <ankitpatidar@hostnsoft.com> 
     * @since 28/05/2014
     * @uses to get diverted route
     * @abstract calling places fromcurrent class function getRouteDetail
     * @return int
     */
    function getDivertedRoute($routeId)
    {
       if(empty($routeId) || preg_match(NOTNUM_REGX,$routeId) )
               return 0; 
       
       $routeId = $this->db->real_escape_string($routeId);
       
       $condition = "routeId=".$routeId;
        
        $selRes = $this->selectData("*", "91_divertedRoute",$condition);
       
         if(!$selRes || $selRes->num_rows < 1)
        {
            return 0;
        }
        
        $row = $selRes->fetch_array(MYSQLI_ASSOC);
      
        return $row['divertedRouteId'];
    }
    
    /**
     * @author Ankit Patidar <ankitPatidar@hostnsoft.com>
     * @since 28/05/2014
     * @abstract calling places from routeController
     * @return json
     */
    function editDivertedRoute($request,$session)
    {
       if(empty($request['oldDivertedRoute']) || preg_match(NOTNUM_REGX,$request['oldDivertedRoute']) )
            return json_encode(array('status' => 'error' ,'msg' => 'Invalid route selected'));   
       
       if(empty($request['divertedRoute']) || preg_match(NOTNUM_REGX,$request['divertedRoute']) )
            return json_encode(array('status' => 'error' ,'msg' => 'Invalid route selected'));   
    
       $fromRoute = $request['oldDivertedRoute'];
       $toRoute = $request['divertedRoute'];
       
       if(empty($session['client_type']) || $session['client_type'] != 1)
           return json_encode(array('status' => 'error' ,'msg' => 'You dont have permission to edit route!!!'));
       
       $result = $this->db->query("call 91_divertRoute($fromRoute,$toRoute)");
       
      if(!$result)
      {
        trigger_error('problem while diverted route updation:'."call 91_divertRoute($fromRoute,$toRoute)");
           return json_encode(array('status' => 'error' ,'msg' => 'Probelm While diverted route updation!!!'));
      }
       
      return json_encode(array('status' => 'success' ,'msg' => 'Diverted Route Successfully updated!!!'));
    }
    
    
    
    /**
     * @author Ankit Patidar
     * @param array $request it contains requires parameters
     * @param int $userId
     * @uses function to get route details
     * @abstract calling places from current class functions getRouteTransaction,routeClosingBalCurrencyCnvt,updateRouteClosingBalance
     * @return json/boolean
     * 
     */
    function getRouteDetail($request,$userId)
    {
        if(empty($userId) || preg_match(NOTNUM_REGX,$userId) )
               return false;
        
        if(empty($request['routeId']) || preg_match(NOTNUM_REGX,$request['routeId']) )
               return false;
        
        $routeId = $this->db->real_escape_string($request['routeId']);
         //get tariff id and name
        $tariffArr = $this->getTariffIdandName($userId);
        
        if(empty($tariffArr))
            return false;
        
        $currListJson = $this->getCurrencyList();
        $currArr = json_decode($currListJson,TRUE);
        if(empty($currArr))
            return false;
        
        $condition = "routeId=".$routeId;
        
        $selRes = $this->selectData("*", "91_route",$condition);
       
         if(!$selRes || $selRes->num_rows < 1)
        {
            return json_encode(array());
        }
    
        $data = array();
        
        $row = $selRes->fetch_array(MYSQLI_ASSOC);
      
        $data['id'] = $row['routeId'];
        $data['routeName'] = $row['route'];
        $data['planName'] = $tariffArr[$row['tariffId']];
        $data['routeIp'] = $row['ip'];
        $data['prefix'] = $row['optPrefix'];
        $data['tariffId'] = $row['tariffId'];
        //get tariff output currency
        $outPutCurr = $this->getOutputCurrency($row['tariffId']);

        if(empty($outPutCurr) || !is_numeric($outPutCurr))
            return false;
        $data['balance'] = $row['routeCredits'];
        $data['currency'] =$currArr[$outPutCurr] ;
        $data['currencyId'] =$outPutCurr ;
        $data['routeList'] = $this->getRouteList();
        
        //get diverted route
        $data['divertedRoute'] = $this->getDivertedRoute($row['routeId']);
        return json_encode($data);

    }
    
    
    function getRouteAndDialPlanList($param,$userId,$userType){
        
        $routeData = array();
        $dialPlanData = array();
        if($userType !=1){
             return json_encode(array("msg"=>"you have no permission to get Route and dial plan list","status"=>"error"));
        }
        if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
        
        if(preg_match('/[^0-9]+/', $param['clientId']) || $param['clientId'] == "")
             return json_encode(array("msg"=>"Error Invalid client selected please try again","status"=>"error"));
        
        $selRes = $this->selectData("*", "91_route");
        if($selRes)
        {
            while($row = $selRes->fetch_array(MYSQLI_ASSOC))
            {
                $routeData[$row['routeId']] = $row['route'];
            }
        }
        
        $DialRes = $this->selectData("*", "91_dialPlanDetail","userId=".$userId);
        if($DialRes)
        {
            while($dialrow = $DialRes->fetch_array(MYSQLI_ASSOC))
            {
                $dialPlanData[$dialrow['id']] = $dialrow['planName'];
            }
        }
        
        $userInfo = $this->getUserBalanceInfo($param['clientId']);
        if($userInfo['routeId'] == '' || $userInfo['routeId'] == NULL){
            $userInfo['routeId'] = 0;
        }
        if($userInfo['isDialPlan'] == '' || $userInfo['isDialPlan'] == NULL){
            $userInfo['isDialPlan'] = 0;
        }
        $isDialPlan = $userInfo['isDialPlan'];
        $routeId = $userInfo['routeId'];
       return json_encode(array("status"=>"success","routeData"=>$routeData,"dialPlanData"=>$dialPlanData,"isDialPlan"=>$isDialPlan,"routeId"=>$routeId));
             
    }
    
    function setUserDialPlanOrRoute($param,$userId,$userType){
        
       if($userType !=1){
             return json_encode(array("msg"=>"you have no permission to get Route and dial plan list","status"=>"error"));
       }
        
       if(preg_match('/[^0-9]+/', $userId) || $userId == "")
             return json_encode(array("msg"=>"Error Invalid user please login again","status"=>"error"));
       
       if(preg_match('/[^0-9]+/', $param['clientId']) || $param['clientId'] == "")
             return json_encode(array("msg"=>"you have no permission to update route or dial plan id ","status"=>"error"));
       
      #check permission for update route and dial plan 
      $resellerId = $this->getResellerId($param['clientId']);  
      
     
      if($resellerId != $userId)
      {
          return json_encode(array("status" => "error", "msg" => "you have no permission to change route or dial plan."));
      }
      
       
       #get user old status
       $userInfo = $this->getUserBalanceInfo($param['clientId']);
        if($userInfo['routeId'] == '' || $userInfo['routeId'] == NULL){
            $userInfo['routeId'] = 0;
        }
        if($userInfo['isDialPlan'] == '' || $userInfo['isDialPlan'] == NULL){
            $userInfo['isDialPlan'] = 0;
        }
       
        $oldStatus = $userInfo['routeId'].",".$userInfo['isDialPlan']; 
       
        #include reseller class to get all user list of chain  
        include_once(CLASS_DIR."reseller_class.php");
        
        $res_obj = new reseller_class(); 
        
        $condition = $res_obj->getResellerAllUser($param['clientId']);
        
       if($param['routeDialplan'] == "route"){
           
           $result = $this->setUserRoute($condition,$param['routeList'],0);  
           $newStatus = $param['routeList'].",0";
           $this->accountManagerLog($param['clientId'],13,$oldStatus,$newStatus,$userId,"update route and dial plan");
       
           
       }elseif ($param['routeDialplan'] == "dialPlan") {
           
           $result = $this->setUserRoute($condition,$param['dialPlanList'],1);
           $newStatus = $param['dialPlanList'].",1";
           $this->accountManagerLog($param['clientId'],13,$oldStatus,$newStatus,$userId,"update route and dial plan");
      
           }
       
       
       
       
       if($result == 0){
           return json_encode(array("msg"=>"request not updated..","status"=>"error")); 
       }
       
       return json_encode(array("msg"=>"request successfully updated..","status"=>"success")); 
       
    }
    
    
  function setUserRoute($condition,$routeId,$isDialPlan){
         
       if(preg_match('/[^0-9]+/', $routeId) || $routeId == "")
             return 0;
       
       $data = array("isDialPlan"=>$isDialPlan,"routeId"=>$routeId);
       $table = "91_userBalance";
       $res = $this->updateData($data, $table,$condition);
       
       if(!$res)
           return 0;
       
       return 1;
      
  }
  
  /**
   * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
   * @abstract calling places from routeController
   * @param type $parm
   * @param type $userid
   * @return type
   */
  function editFundRoute($parm,$session)
    {      
      
      
      if(empty($session['client_type']) || $session['client_type'] != 1)
	  return json_encode(array("status" => "error", "msg" => "You dont have permission to edit fund!!!"));
      
      if (empty($parm['toRouteEditFund']) || preg_match("/[^0-9]+/", $parm['toRouteEditFund']) ) 
      {
            return json_encode(array("status" => "error", "msg" => "Invalid route please select a route to transfer fund"));
      }
     
      #check user have permission for edit fund or not  
      if($parm['fundAmount'] < 0)
      {
          return json_encode(array("status" => "error", "msg" => "please enter valid fund ."));
      }
        
      if($parm['balance'] < 0){
          return json_encode(array("status" => "error", "msg" => "please enter valid balance ."));
      }
      
      if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $parm['fundAmount'])) {
            return json_encode(array("status" => "error", "msg" => "fund amount are not valid ! "));
      }
      
      if (preg_match("/[^0-9]+/", $parm['fundCurrency']) || strlen($parm['fundCurrency']) < 1 ) {
            return json_encode(array("status" => "error", "msg" => "fund currency is not valid ! "));
      }
      
      
        
      if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $parm['balance'])) {
            return json_encode(array("status" => "error", "msg" => "please enter valid balance ! "));
        }  
      
      if(strlen(trim($parm['balance'])) > 8){
                return json_encode(array("status" => "error", "msg" => "please enter balance no more than 8 characters. ! "));
            }  
       
      if(strlen(trim($parm['fundAmount'])) > 8){
                return json_encode(array("status" => "error", "msg" => "please enter fund amount no more than 8 characters. ! "));
            }        
      
     
      if($parm['pType'] == "partial"){
           
           if (!preg_match("/^[0-9]+(\.[0-9]{1,3})?$/", $parm['partialAmt'])) {
            return json_encode(array("status" => "error", "msg" => "please enter valid partial amount ! "));
            }
            
            if(strlen(trim($parm['partialAmt'])) > 8){
                return json_encode(array("status" => "error", "msg" => "please enter partial amount no more than 8 characters. ! "));
            }
            
            if (preg_match("/[^0-9]+/", $parm['partialCurrency']) || strlen($parm['partialCurrency']) < 1 ) {
            return json_encode(array("status" => "error", "msg" => "partial currency is not valid ! "));
      }
            
       }
       
       
       if($parm['changefunderEditFund'] != "add" && $parm['changefunderEditFund'] != "reduce" )
       {
           return json_encode(array("status" => "error", "msg" => "Invalid input please contact support"));
       }
       
       if(preg_match("/[^a-zA-Z]+/",$parm['fundPaymentType']) || $parm['fundPaymentType'] == "")
               return json_encode(array("status" => "error", "msg" => "please select a proper fund type"));
       
//       if(preg_match("/[^a-zA-Z0-9\@\$\%\^\&\*\(\)\:\<\>\?\\\/\,\.\_\-\|]+/",$param['fundDescription']))
       if(preg_match("/[\"\'\#\<\>]+/",$parm['fundDescription']))
               return json_encode(array("status" => "error", "msg" => "description invalid user of quotes or < > are prohibited"));
       
       if($parm['pType'] != 'postpaid')
       {
	    if(isset($parm['fundPaymentType']) && $parm['fundPaymentType'] == "Other")
	    {
		if(preg_match("/[^a-zA-Z\s]+/",$parm['otherPaymentType']) || $parm['otherPaymentType'] == "")
		    return json_encode(array("status" => "error", "msg" => "please provide a valid other payment type value"));
	    }
       }
        
    
      
//      # variable fundAmount use to which amount will be update
      $fundAmount = $parm['fundAmount'];
      $talktime = $parm['balance'];
      #variable ptype : Payment Type (partial ,postpaid,prepaid) 
      $pType = $parm['pType'];
      
     
      #check balance add or reduce in currentbalance 
      if($parm['changefunderEditFund'] != "add")
      {
            $talktime = ((int)-$parm['balance']); 

            // fund amount 
            $fundAmount = ((int)-$parm['fundAmount']);
            $pType = ""; 

            $sign = '-';
      }
      else
            $sign = '+';
      
       
      //*** entry in transaction log table
      if($parm['fundPaymentType'] == "Other")
      {
            $fundpaymentType = $this->db->real_escape_string($parm['otherPaymentType']);     
      }
      else
            $fundpaymentType = $parm['fundPaymentType'];
      
      
      
//      //set touser and from user
      $this->routeId = $parm['toRouteEditFund'];
      $this->userId = $session['userid'];
      
      #update user balance table 91_routeBalance table
      $this->updateRouteBalance($this->routeId,$parm['balance'],$sign); 
//      
      
//      #add transaction in case of voip91(payment type advance).
      $result = $this->addRouteTransactional($fundAmount,$talktime,$fundpaymentType,$parm['fundDescription'],$pType,$parm['partialAmt'],$parm['fundCurrency'],$parm['partialCurrency']);
      $returnBalanace = $this->getCurrentRouteBalance($this->routeId);
     
     
      if($result == 1)
      {
          return json_encode(array("status" => "success", "msg" => "successfully update route fund .","balance"=>$returnBalanace));
      }
      else
          return json_encode(array("status" => "error", "msg" => "problem while update route fund ."));
      
    }
    
   /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @param int $routeId
     * @param float $talktime
     * @parm string $sign
     * @abstract called from current class function editFundRoute 
     * @return int
     */
     function updateRouteBalance( $routeId, $talktime, $sign = '+')
    {
	  if(empty($routeId) || preg_match(NOTNUM_REGX,$routeId) )
               return 0;
        #- This is for sql injection
        $talktime = $this->db->real_escape_string($talktime);
        
        if($talktime > 0)
        {
	    if($sign == '+' || $sign == '-')
		$updateBalance = "UPDATE 91_route SET routeCredits=routeCredits".$sign.$talktime." WHERE routeId=".$routeId."" ;
            else
		return 0;
	
            $result = mysqli_query( $this->db, $updateBalance );
            
            if(!$result || mysqli_affected_rows($this->db) == 0)
            {
                trigger_error('problem while update route balance  ,query:'.$updateBalance.' error'.mysqli_error($this->db)); 
                return 0;
            }
        }
        else
            return 0;
        
        return 1; 
    }
    

    /**
     * 
     * @param int $routeId
     * @param int $userId
     * @param int $pageNo
     * @return type@author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @param int $routeId
     * @param int $userId
     * @param int $pageNo
     * @abstract called from addReduceTransactionRoute and from routeController
     * @return array
     */
    function getRouteTransaction($routeId,$userId,$pageNo=1)
    {  
	if(empty($routeId) || preg_match(NOTNUM_REGX,$routeId) || empty($userId) || preg_match(NOTNUM_REGX,$userId))
		return json_encode(array());
	
	$this->routeId = $routeId;
	$this->userId = $userId;
      #- Get user currency id 
	$param['routeId'] = $this->routeId;
        $routeDetailJson = $this->getRouteDetail($param,$this->userId);
        
	$routeDetail = json_decode($routeDetailJson,TRUE);
	
        $currencyId = $routeDetail['currencyId'];
            
      #- Get currency name 
      $currencyName = $this->getCurrencyViaApc( $routeDetail['currencyId'] , 1 );  
        
      #- Table name   
      $table = '91_routeTransactionLog';
      
      
      $condition = "fromUser=$this->userId and  routeId = '" .$this->routeId. "'order by date asc";
      
      //take limit to show
      $limit = 20; 
       //get skip for pagination
      $skip = $limit*($pageNo-1); 
      #- Get data form transaction log table where form user and touser are given
      //$result =  $this->selectData( '*', $table, $condition );
      $this->db->select('SQL_CALC_FOUND_ROWS *')->from($table)->where($condition)->limit($limit)->offset($skip);
      $SQL= $this->db->getQuery();
      $result = $this->db->execute();
      if(!$result)
            trigger_error('Problem while get details from route transaction log,condition:'.$condition);
      
       $resultCount = $this->db->query('SELECT FOUND_ROWS() as totalRows');
	if(!$resultCount)
	    return json_encode ($transactionData);

	$countRes = mysqli_fetch_assoc($resultCount);
      
      #- Check data total no of row is greater then 0 or not 
      if ($result->num_rows > 0)
      {
          while ($row = $result->fetch_array(MYSQL_ASSOC) ) 
          { 
//              switch($resellerTrans)
//              {
//                  case '0' : 
                      $userDetail = $this->getNameAndUserName($row['fromUser']); // name and userName     
                      #check update by  account manager or reseller
                      if($row['changedBy'] == 1){
                        $userDetail = $this->getUserOrAcmUserName($row['changedBy'],$row['accManagerId']);
                      }else
                        $userDetail = $this->getUserOrAcmUserName($row['changedBy'],$row['fromUser']);
                      
                      if(!empty($userDetail))
                      {
                          $data['userName'] = $userDetail['userName'];
                          $data['name'] = $userDetail['name'];
                      }
                      else
                      {
                          trigger_error('problem while get user detail'.json_encode($row));
                          $data['name'] = $userDetail['name'];
                          $data['userName'] = $userDetail['userName'];     
                      }
//                      
//                      
//                      break;
//                  
//                  case '1':
//                      //$userDetail = $this->getNameAndUserName($row['toUser']);
//                     // $data['name'] = $userDetail['name'];
//                      //$data['userName'] = $userDetail['userName'];
//                      $userDetail = $this->getUserOrAcmUserName($row['changedBy'],$row['toUser']);
//                      if(!empty($userDetail))
//                      {
//                          $data['userName'] = $userDetail['userName'];
//                          $data['name'] = $userDetail['name'];
//                      }
//                      else
//                      {
//                          trigger_error('problem while get user detail'.json_encode($row));
//                          $data['name'] = $userDetail['name'];
//                          $data['userName'] = $userDetail['userName'];     
//                      }
//                      
//                      
//                      
//                      break;    
//              }
              
              $data['routeId'] = $row['routeId'];
              $data['date'] = $row['date'];
              $data['amount'] = $row['amount'];
              $data['currentBalance'] = $row['currentBalance'];
              $data['credit'] = $row['credit'];
              $data['debit'] = $row['debit'];
              $data['paymentType'] = $row['paymentType'];
              $data['closingBalance'] = $row['closingBalance'];
              $data['description'] = $row['description']; 
              $data['currency'] = $row['currency']; 
              
              $currencyViaApc = $this->getCurrencyViaApc( $data['currency'] , 1 );
              
              if($currencyViaApc == '' || $currencyViaApc == null)
              {
                 $data['currencyName'] = $currencyName;
              }
              else
                $data['currencyName'] = $currencyViaApc;
              
	     
              if($currencyName == $data['currencyName'])
              {
                $data['creditActualCurrency'] = $data['credit']; 
                $data['debitActualCurrency'] =  $data['debit'];
              }
              else
              {
                $data['creditActualCurrency'] = round($row['creditConvert'],3);
                $data['debitActualCurrency'] = round($row['debitConvert'],2);
              }
              
              $transactionData['detail'][] = $data;
	}
      }
      else
      {
          $transactionData = array();
      }
      
      
	//get total count
	$count = $countRes['totalRows'];
	$transactionData['totalCount'] = ceil($count/$limit);

	
	return json_encode($transactionData);
        
    }
    
     /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since
     * @abstract calling places from routeController
     * @param type $parm
     * @param type $userid
     * @param type $type
     * @return type
     */
     function addReduceTransactionRoute($parm,$session,$type=1)
    {
        #- calling function To check validation
        $validateRes = $this->checkTransactionValidation($parm);
        
         if($validateRes != 1)
            return $validateRes;

        

        if(empty($session['client_type']) || $session['client_type'] != 1)
        {
          return json_encode(array("status" => "error", "msg" => "you have no permission for add transaction ."));
        }
	
	$userId = $session['userid'];
        
        $paymentType = $parm['transType'];

        if($parm['transType'] == "Other")
        {
            $paymentType = $parm['transTypeOther'];
        }
        
        $this->userId = $userId;
        $this->routeId = $parm['routeId'];
	
	
        $result = $this->addRouteTransactional($parm['amount'],0,$paymentType,$parm['description'],'',0,$parm['currency'], 0, $parm['status'],1 ); 

        if($result)
        {
            if($type == 1)
                $transData = $this->getRouteTransaction($this->routeId,$this->userId);
//            }else
//                $transData = $this->getTransactionLogDetail($this->fromUser,$this->toUser);
//            
            $str = json_decode($transData,TRUE);
            return json_encode(array("status"=>"success","msg"=>"Successfully Transaction Updated !","str"=>$str));   
        }
    }
    
    
    /**
     * @author ankit patidar <ankitpatidar@hostnsoft.com>
     * @abstract calling places from current class functions addReduceTransactionRoute
     * @param type $parm
     * @return int
     */
     function checkTransactionValidation($parm)
    {
	  if(empty($parm['routeId']) || preg_match(NOTNUM_REGX,$parm['routeId']))
		return json_encode(array('status' => 'error' ,'msg' => 'Invalid route!!'));
        #- Checking for valid transaction type 
        if(isset($parm['transType']) && (preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $parm['transType']) || strlen(trim($parm['transType'])) < 1 || strlen(trim($parm['transType'])) > 55))
        {
           return json_encode(array("status"=>"error","msg"=>"please enter a valid Transaction Type must not containg any spacial character other than '@','_','-'"));
        }

        #- Checking for valid description 
        if(isset($parm['description']) && (preg_match('/[^a-zA-Z0-9\@\_\-\s]+/', $parm['description']) || strlen(trim($parm['description'])) < 1 || strlen(trim($parm['description'])) > 200))
        {
           return json_encode(array("status"=>"error","msg"=>"please enter a valid Description must not containg any spacial character other than '@','_','-'"));
        }

        #- Checking for valid amount 
        if(isset($parm['amount']) && (!preg_match('/^[0-9]+/', $parm['amount'])))
        {
           return json_encode(array("status"=>"error","msg"=>"please enter a valid amount !"));
        }
        
        return 1;
        
    }
    
    /**
     * 
     * @param array $parm
     * @param type $session
     * @return type@author Ankit patidar <ankitpatidar@hostnsoft.com>
     * @since 06/06/2014
     * @param array $parm contains all required parameters
     * @filesource
     * @param array $session
     * @uses function add or update support details
     * @return json
     */
    function addEditRouteSupportDtl($parm,$session)
    {
	
	if(!empty($parm['routeEmails']))
	{
	    foreach($parm['routeEmails'] as $emailVal)
	    {
		if(!empty($emailVal) && !preg_match(EMAIL_REGX,$emailVal))
			return json_encode(array('status'=> 'error' ,'msg' => 'Please enter valid email address,ex:example@exp.com!!!'));
		
	    }
	    
	}
	
	if(!empty($parm['routeContacts']))
	{
	    foreach($parm['routeContacts'] as $contactVal)
	    {
		if(!empty($contactVal) && !preg_match(NOTMOBNUM_REGX,$contactVal))
			return json_encode(array('status'=> 'error' ,'msg' => 'Please enter valid contact number,ex:91569856325!!!'));
		
	    }
	    
	}
	
	if(!empty($parm['']) && !preg_match(EMAIL_REGX,$emailVal))
	    return json_encode(array('status'=> 'error' ,'msg' => 'Please enter valid email address,ex:example@exp.com!!!'));
	
	
	
	var_dump($parm);
	die('protocol');
    }
    
     /**
     * 
     * @param array $parm
     * @param type $session
     * @return type@author Ankit patidar <ankitpatidar@hostnsoft.com>
     * @since 06/06/2014
     * @param array $parm contains all required parameters
     * @filesource
     * @param array $session
     * @uses function add or update support details
     * @return json
     */
    function addEditRouteEmailContact($parm,$session)
    {
	if(!empty($parm['routeEmails']))
	{
	    foreach($parm['routeEmails'] as $emailVal)
	    {
		if(!empty($emailVal) && !preg_match(EMAIL_REGX,$emailVal))
			return json_encode(array('status'=> 'error' ,'msg' => 'Please enter valid email address,ex:example@exp.com!!!'));
		
	    }
	    
	}
	
	if(!empty($parm['routeContacts']))
	{
	    foreach($parm['routeContacts'] as $contactVal)
	    {
		if(!empty($contactVal) && preg_match(NOTMOBNUM_REGX,$contactVal))
			return json_encode(array('status'=> 'error' ,'msg' => 'Please enter valid contact number,ex:91569856325!!!'));
		
	    }
	    
	}
	
	var_dump($parm);
	die('fhelo');
	
	//prepare query for multiple insert
	
    }
}
?>