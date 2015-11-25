<?php

/**
 * @Author sudhir pandey <sudhir@hostnsoft.com>
 * @createdDate 12-07-13
 * 
 */
include_once dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."/db_class.php");

class phonebook_class extends fun
{
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use to add contact no and email id into phonebook 

    function addContact($parm, $userid , $type = 0 ) 
    {
        #get all contact detail 
        $dbobj = new db_class();
 
        $allname = $parm['name'];
        
        $allcode = $parm['code'];
        $allcontact = $parm['contact']; 
        $allAccessNumber = $parm['conAccessNumber'];
        $allHash = $parm['extensionNumber'];
        
        if(isset($parm['email']))
        $allemail = $parm['email'];
        else
        $allemail = array();   
        
        #all contact
        $collectionName = 'phonebook';
        
        $countTotalSave = 0;
        $alreadyExist = 0;
        $alreadyExistHash = 0;
        
        
        $msg = $this->checkContactNull($allcode,$allcontact,$allemail);
        
        if ($msg != "success") 
        {
            return json_encode(array("status" => "error", "msg" => $msg));
        }
        
        
        $errorKey = $this->checkContactValidation($allcode,$allemail, $allcontact , $allname);
        
        
        
        $count = count($allcontact);

        $validContacts = array();
        $accessErrorKey = array();
        $hashArray = array();
        
   
        if(!empty($allAccessNumber) && !empty($allAccessNumber[0]))
        {
            $accessError = $this->checkAccessNumber( $allAccessNumber , $allHash ,$userid );
            $accessErrorKey = $accessError['errorKey'];
            $hashArray = $accessError['hashArray'];
        }
        
        for ($i = 0; $i < $count; $i++) 
        {
            if(!in_array($i, $errorKey))
            {
                #check for contact no. is already inserted in table
                $condition = array('code'=>$allcode[$i], 'contactNo' => $allcontact[$i], 'userId' => $userid);
                $result = $dbobj->mongo_count($collectionName, $condition);
                
                $existContact = 0;
                
               
                $key = array_keys($allcontact, $allcontact[$i]);
                
              
                
                if(count($key) > 1)
                {
                    foreach ($key as $chkKey)
                    {
                        if($allcode[$chkKey] == $allcode[$i] )
                        {
                            $existContact++;
                        }
                    }
                }
                  
                //echo 'already exist '.$exist;
                
                if ($result <= 0  && $existContact == 0 ) 
                {
                    #update contact detail 
                    
                    if(!in_array($i, $accessErrorKey) )
                    {
                        $data = array( "contact_id" => new mongoId(),
                                       "name" => htmlentities($allname[$i], ENT_QUOTES, 'UTF-8'), 
                                       "email" => $allemail[$i], 
                                       "code" => $allcode[$i],
                                       "contactNo" => $allcontact[$i] ,'userId' => $userid); //    htmlentities($comment,ENT_QUOTES,'UTF-8'))));

                        if( !empty ($allAccessNumber[$i]) && !empty($hashArray[$i]) )
                        {
                            $data['accessNo'] = $allAccessNumber[$i];
                            $data['hash'] = $hashArray[$i];
                        }
                        
                        $validContacts[] = $data;
                    }
                    else 
                    {
                        $alreadyExistHash+=1;
                    }
                    
                }
                else
                {
                    $alreadyExist += 1;  
                }
            }
           
        }
        
       
        
        
        if(!empty($validContacts))
        {
            try
            {
                $status = $dbobj->mongoBulkInsert($collectionName,$validContacts );
                
            } 
            catch (MongoCursorException $ex) 
            {
                trigger_error('Problem While Inserting contact '.$ex);
                return 0;
            }
            
            $this->addLastUpdateTime($userid);
            
            
            if ( isset($status['ok'])  ) 
            { 
                $message = '';
              //  echo $existContact;
                if( $existContact > 0)
                {
                    $message = 'You can not assign same contact number to different contacts.';
                }
                else if($alreadyExist > 0)
                {
                    $message = $alreadyExist.'contact number already exists!'; 
                }
                
                if( $alreadyExistHash > 0 )
                {
                      $message.= ' You can not Assign same access number with same hash to multiple contacts. and can not dedicate already assigned access numbers.';
                }
                
                $str = '';
                
                if(!$type)
                $str = $this->allContactlist( $userid );
                
               
                return json_encode(array("status" => "success", "msg" => "Contact added successfully!!".$message , "str" => $alreadyExistHash));
            }
            else
            {
                if($alreadyExist > 0)
                {
                    return json_encode(array("status" => "error", "msg" => "This contact number already exists!"));  
                }
                else
                    return json_encode(array("status" => "error", "msg" => "Invalid contact number please provide atleast one valid contact number !")); 
            }   
        }
        else 
        {
                $message = "";
                
                if( $alreadyExistHash > 0 )
                {
                    $message = ' You can not Assign same access number with same hash to multiple contacts. and can not dedicate already assigned access numbers.';
                }
                if($existContact >  0 )
                {
                    //$message = 'You can not assign same contact number to different contacts.'; 
                    return json_encode(array("status" => "error", "msg" => "You can not assign same contact number to different contacts.")); 
                }else if($alreadyExist > 0)
                {
                    return json_encode(array("status" => "error", "msg" => "Contact number already exists !"));  
                }
                else
                    return json_encode(array("status" => "error", "msg" => "Invalid contact number or name. please provide atleast one valid Detail !!".$message)); 
        }
    }
    
    
    
    /*
     * This Function is to last update time of contacts in table.
     * This is done for api purpose.
     */
    function addLastUpdateTime($userId)
    {
        
        if(!is_numeric($userId))
            return 0;
        
        $table = "91_contactTimeLog";
        
        $data = array( "userId" => $userId );
        
        $accNoResult = $this->selectData( "count(*)" , $table , "userId = $userId" );
 
        if($accNoResult->num_rows > 0)
        {
             $updateBalance = "UPDATE 91_contactTimeLog SET dateTime=now() WHERE userId=".$userId."" ;
            
            $result = mysqli_query( $this->db, $updateBalance );
        }
        else       
        {
            $response = $this->insertData($data, $table);
        }
        
        if (!$response)
        {
            return 0;
        }
        else 
        {
            return 1;
        }
          
    }
    
    
    function getLastUpdateTime($userId)
    {
        if(!is_numeric($userId))
            return 0;

        $table = "91_contactTimeLog";

        $data = array( "userId" => $userId);

        $result =  $this->selectData( 'dateTime', $table, "userId = $userId");
        
       //echo $this->querry;
        
        $date = 0;
        
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $date = $row['dateTime'];
        }
        
        return $date;
        
    }
    
    /*
    
    function updateContactAPI($param,$userid , $type = '0')
    {
       
        $dbobj = new db_class();
        #contact id
        $contactId = $param['contactId'];
        #all name array   
        $allname = $param['name'];
        #all email array
        $allemail = $param['email'];
        #all contact
        $allcontact = $param['contact'];     
        $accessNo  = $param['accessNo'];
        $hash = $param['hash'];
        
        
        #check email id valid
        if ($allemail != '' || $allemail != null) 
        {
            if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $allemail)) 
            {
                return json_encode(array("status" => "error", "msg" => "email id is not valid !"));
            }
        }

        
      
        
        
        if (!preg_match("/^[0-9]{8,15}$/", $allcontact)) 
        {

            return json_encode(array("status" => "error", "msg" => "contact no. is not valid!"));
        }
        
        $collectionName = 'phonebook';
        
        #check number already exit or not 
        $subcondition = array('$ne' => new mongoId($contactId));
        
        
        
        $condition = array( 'contactNo' => $allcontact , 'userId' => $userid ,'contact_id' => $subcondition);
        
        $result = $dbobj->mongo_count($collectionName , $condition);
        
        if ($result > 0) 
        {
            return json_encode(array("status" => "error", "msg" => "Contact no already exist !"));
        }
        
        
        if( isset($accessNo) && !empty($accessNo) )
        {
            
//            $val = $this->checkValidAccNo($parm['accessNo']);
//            
//            if(!$val)
//            {
//                return json_encode(array("status" => "error", "msg" => "Invalid Access Nuber!!" ));
//            }
            
            $listcon = $this->getContactInfo( $userid , $accessNo , '2' , $contactId  = $contactId );
            
            $contactsArr = $listcon['allcontact'];
            
            //print_r($contactsArr);
            
            if(empty($hash))
            {
                 $hash = $this->generateNewHash( $contactsArr[$accessNo] ,  0 );
            }
            
            if(in_array($hash, $contactsArr[$accessNo]))
            {
                return json_encode(array("status" => "error", "msg" => "You can not assign same access number with hash to multiple contacts." ));
            } 
            
        }
        
        $condition = array( 'contactNo' => $allcontact , 
                                'userId' => $userid , 
                                'name' => $allname ,
                                'email' => $allemail , 
                                "accessNo" => $accessNo,
                                'hash' => $hash );
        
       
        
        $result = $dbobj->mongo_count( $collectionName , $condition ) ;
         
        if ($result > 0) 
        {
            return json_encode(array("status" => "error", "msg" => "Nothing to update !"));
        }
        
         
        $data = array( "name" => htmlentities($allname, ENT_QUOTES, 'UTF-8'), "email" => $allemail, "contactNo" => $allcontact,"accessNo" => $accessNo , "hash" => $hash  ,'userId' => $userid ); 
        
        

        $dataArray= array('$set' => $data );

        $conditionArray = array("contact_id" => new mongoId($contactId));
        $status = $dbobj->mongo_update($collectionName, $conditionArray ,$dataArray);
        
        if(!$status) 
        {
             trigger_error('Problem While update phonebook!!!');
        }

        $str= '';
        
        if(!$type)
            $str = $this->allContactlist($userid);
        
        return json_encode(array("status" => "success", "msg" => "contact no. updated !", "str" => $str)); 

       
    }
    */
    # @author nidhi<nidhi@walkover.in>
    #- @param :: accessNumbers , hash . 
    function checkAccessNumber( $allAccessNumber , $allHash , $userId )
    {
        $count = count($allAccessNumber);  
        
        $errorKey = array();
        
        $validAccessNo = array();
        
        for($i = 0; $i < $count; $i++)
        {
            if ($allAccessNumber[$i] != '' || $allAccessNumber[$i] != null  || preg_match("/^[0-9]{8,15}$/", $allAccessNumber[$i]) ) 
            { 
                if( isset( $validAccessNo[$allAccessNumber[$i]] ) && in_array( '100' ,  $validAccessNo[$allAccessNumber[$i]] ) )
                {
                    $errorKey[] = $i; 
                }
                else 
                {
                    
                    # If hash numbers comes duplicate then generate new Hash number. 
                    
                    if ($allHash[$i] != '' || $allHash[$i] != null  || preg_match("/^[0-9]{1,5}$/", $allHash[$i]) ) 
                     { 
                    $hashvalue = $allHash[$i];
                    

                    if(isset( $validAccessNo[$allAccessNumber[$i]] ) && in_array( $hashvalue ,  $validAccessNo[$allAccessNumber[$i]] )  || !$hashvalue )
                    {
                        $errorKey[] = $i; 
                    }   
                    else 
                    {
                        #- now checking in da tabase for that user id finding accessNumber and hash.
                        #- gettin all access number values with hash for that number.
                        
                        $accessNumberArr = $this->getContactInfo($userId , $allAccessNumber[$i] , '2');
                        $accessNumberArr = $accessNumberArr['allcontact'];
                       
                        if(isset($accessNumberArr[$allAccessNumber[$i]])  && in_array('100', $accessNumberArr[$allAccessNumber[$i]]) || in_array( $hashvalue , $accessNumberArr[$allAccessNumber[$i]])  )
                        {
                             $errorKey[] = $i; 
                        }
                        else 
                        {
                            if($hashvalue == '100' && count($accessNumberArr[$allAccessNumber[$i]]) > 0 )
                            {
                                 $errorKey[] = $i; 
                            }else { 
                            
                            $validAccessNo[$allAccessNumber[$i]][] = $hashvalue;
                            $allHash[$i] = $hashvalue;
                            }
                        }        
                        
                        
                    }
                  }else
                    $errorKey[] = $i; 
                }
                
            }
            else
            {
                 $errorKey[] = $i; 
            }
        }
        
        return array( 'errorKey' => $errorKey , 'hashArray' => $allHash );
    }
    
    
    function generateNewHash($listOfAccessNumber , $hash , $new = NULL)
    { 
        #- Generating new random hash.
        
        if(empty($hash))
        {
            $hash = rand(10,99); 
        }
        
        
        if(!in_array($hash, $listOfAccessNumber))
        {
            return $hash;
        }
        else
        {
            $hash =  $this->generateNewHash($listOfAccessNumber , 0 ,$new);
            return $hash;
        }
    }
    

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 05-08-2013
    #fuction use to add Me contact no into phonebook table 

    function addMeContact($userid) 
    {
        #db_class obj for mongo connection 
        $dbobj = new db_class();

        #collectionName 
        $collectionName = 'phonebook';

        $condition = array('userId' => $userid);
        $result = $dbobj->mongo_count($collectionName, $condition);

        if ($result <= 0) 
        {
            #get email id form validateEmail table by ues contact_class function 

            include_once(CLASS_DIR."contact_class.php");

            $cont_obj = new contact_class();
            $cont = $cont_obj->getUnConfirmEmail($userid);
            $emailId = $cont['email'];

            #check verified mobile no.
            $confirmNo = $cont_obj->getConfirmMobile($userid);

            if($confirmNo[0]['verifiedNumber'] == '' || $confirmNo[0]['verifiedNumber'] == NULL)
            {
                #get contactno form validnumber table 
                $contactno = $cont_obj->getUnconfirmMobile($userid);
                $meContact = $contactno['tempNumber'];
            }
            else
                $meContact = $confirmNo[0]['verifiedNumber'];    


            $data = array("userId" => $userid, "emailId" => $emailId , "contact_id" => new mongoId() ,"name" => htmlentities("me", ENT_QUOTES, 'UTF-8'), "email" => $emailId, "contactNo" => $meContact );

            
            
            try
            {
                $status =  $dbobj->mongo_insert($collectionName, $data);
               
            } 
            catch (MongoCursorException $ex) 
            {
                trigger_error('Problem While Inserting Me contact '.$ex);
                return 0;
            }
            
             $this->addLastUpdateTime($userid);
            

            if(!$status)
                trigger_error ('Problem While update phonebook!!!');
        }
    }


    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use to update contact no and email id into phonebook 


    function updateContact($parm, $userId,$type='0',$agentEdit='0') 

    {
     
        $dbobj = new db_class();
        #contact id
        $contactId = $parm['contactId'];
        
        #all name array   
        $allname = $parm['name'];
        #all email array
        $allemail = $parm['email'];
        
        #all contact and code
        $code = $parm['code'];
        $allcontact = $parm['contact'];
        $accessNo = $parm['conAccessNumber'];
        $hash = $parm['extensionNumber'];    
        $contactsArr = array();
        #check email id valid
        if ($allemail != '' || $allemail != null) 
        {
            if (!preg_match(EMAIL_REGX, $allemail)) 
            {
                return json_encode(array("status" => "error", "msg" => "This Email ID is not valid."));
            }
        }

        if (!preg_match(PHNNUM_REGX, $allcontact)) 
        {

            return json_encode(array("status" => "error", "msg" => "Contact number is not valid."));
        }
        
        if (!preg_match('/^[0-9]{1,5}$/', $code)) 
        {

            return json_encode(array("status" => "error", "msg" => "Please select a valid country code."));
        }
        if ($allname != '' || $allname != null) 
        {
            if (!preg_match('/^[a-zA-Z0-9\s\_\@\.]+$/', $allname)) 
            {
                return json_encode(array("status" => "error", "msg" => "Please enter a proper name."));
            }
        }
        $collectionName = 'phonebook';
        
        $subcondition = array('$ne' => new mongoId($contactId));
        
        

        $condition = array( 'contactNo' => $allcontact ,'code' =>$code , 'userId' => $userId ,'contact_id' => $subcondition);

        
        $result = $dbobj->mongo_find($collectionName , $condition);
        
        if(iterator_count($result) > 0) 
        {
            return json_encode(array("status" => "error", "msg" => "Contact no already exist!"));
        }
        

        if( isset($accessNo) && !empty($accessNo) && $accessNo != '' && $accessNo != NULL )

        {
            
            $val = $this->checkValidAccNo($accessNo);
            
            if(!$val)
            {
                return json_encode(array("status" => "error", "msg" => "Invalid access number!" ));
            }
            
            $listcon = $this->getContactInfo( $userId , $accessNo , '1' , $subcondition );
            
            $contactsArr = $listcon['allcontact'];
            
            //print_r($contactsArr);
            
            if(empty($hash) || $hash == '' || $hash == NULL)
            {
                return json_encode(array("status" => "error", "msg" => "Please select a valid extension number." ));
            }
            
	    
            if(isset($contactsArr[$accessNo]) && in_array($hash, $contactsArr[$accessNo]))
            {
                return json_encode(array("status" => "error", "msg" => "You cannot assign same extension access number to multiple contacts." ));
            } 
            
        }
        
      $condition = array( 'contactNo' => $allcontact ,
                            'userId' => $userId , 
                            'name' => $allname ,
                            'email' => $allemail , 
                            "accessNo" => $accessNo,
			    "code" => $code,
                            'hash' => $hash );
        
        $result = $dbobj->mongo_find( $collectionName , $condition ) ;
         
        if (iterator_count($result) > 0) 
        {
            return json_encode(array("status" => "error", "msg" => "There’s nothing to update!"));
        }
        
        try
        {
            

            if($agentEdit == 1){
                $data = array( "name" => htmlentities($allname, ENT_QUOTES, 'UTF-8'),
                             "contactNo" => $allcontact,
                             'userId' => $userId,
                             "code" => $code);
            }else
            {
                $data = array( "name" => htmlentities($allname, ENT_QUOTES, 'UTF-8'),
                           "email" => $allemail, 
                           "contactNo" => $allcontact,
                           "accessNo" => $accessNo ,
                           "hash" => $hash ,
                           'userId' => $userId,
                           "code" => $code);
            }


            $dataArray= array('$set' => $data );

            $conditionArray = array("contact_id" => new mongoId($contactId));
            $status = $dbobj->mongo_update($collectionName, $conditionArray ,$dataArray);
            
            
        } 
        catch (MongoCursorException $ex) 
        {
            trigger_error('Problem While Updating contact '.$ex);
            return 0;
        } 
        

        $this->addLastUpdateTime($userId);

            
        if(!$status) 
        {
            trigger_error('Problem While update phonebook!!!');
        }


        
        return json_encode(array("status" => "success", "msg" => "Contact number updated!"));    


    }


    function checkValidAccNo($accessNo,$type=0){
      
      if (!preg_match(PHNNUM_REGX, $accessNo))
        { 
          return 0;
        }
      $table = '91_longCodeNumber';
      
      #get all access number from 91_longCodeNumber table  
      $result = $this->selectData('*',$table,"longCodeNo=".$accessNo);
      
      if($result->num_rows > 0)
      {
          if($type == 1){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            return $row;
          }else
           return 1;
      }else  
      return 0;
        
    }
    
    /**
    *@author Ankit Patidar <ankitpatidar@hostnsoft.com> 
    *@since 23/05/2014
    *Last modified 25/8/2014 
    *@abstract called from phonebookcontroller and api_sameer.php 
    *@uses to get country array with counrty code in index
    */
    function getCountriesWithPrefix($resId)
    {

         if(empty($resId) || preg_match(NOTNUM_REGX, $resId))
        {
            return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again! '));
        }

         //get reseller Chain id
        $chainId = $this->getUserChainId($resId);

        if(!$chainId)
         {
            trigger_error('chain id not found,resId'.$resId);
           return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again! '));
         }   

        $table = '91_longCodeNumber';
      
        #get all access number from 91_longCodeNumber table  
        $result = $this->selectData('*',$table,'resellerChainId="'.$chainId.'" and hidden = 0  group by prefix');
      
      
        
        if(!$result || $result->num_rows == 0)
        {
                trigger_error('problem while get country list by prefix,Qur:'.$this->querry);
               return json_encode(array('countryList' => array()));

        }

        $data = array();
	$conDtl = array();
        $usersCountry = 0;
       $countryInfo = $this->getLocationInfoByIp();
       $countryInfo = get_object_vars(json_decode($countryInfo))  ;
      // print_r($countryInfo);
       
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
	    //this will use in panel
            $data[trim($row['prefix'])] = $row['country']; 
        
	    //this will use in api
	    $conDtl[] = array('countryCode' => $row['prefix'],
              		      'CountryName' => $row['country']);
            
           
            if( strtolower($row['country'])  == strtolower($countryInfo['countryName']) )
            {
                $usersCountry = $row['country'];
            }
            
	}
//        print_r($conDtls);
        return json_encode(array('countryList' => $data, 'countryDetail' => $conDtl , "usersCountry" => $usersCountry ));
    }

    /**
    *@author Ankit Patidar <ankitpatidar@hostnsoft.com>
    *@since 23/05/2014
    *@uses get get states by prefix
    *@param int $resId
    *@param int $prefix
    *@return json
    */
    function getStatesByPrefix($resId,$prefix,$userId = '')
    {

        if(empty($resId) || preg_match(NOTNUM_REGX, $resId))
        {
            return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again!'));
        }

        if(empty($prefix) || preg_match(NOTNUM_REGX, $prefix))
        {
            return json_encode(array('status' => 'error','msg' => 'Please select a country first!'));
        }

        //get reseller Chain id
        $chainId = $this->getUserChainId($resId);

        if(!$chainId)
         {
             trigger_error('chain id not found,resId'.$resId);
             return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again! '));
         } 

        $table = '91_longCodeNumber';
      
        #get all access number from 91_longCodeNumber table type  2-callAccessNumber,1-smsAccessNumber,3-didforward
        $result = $this->selectData('*',$table,"prefix='$prefix' and hidden=0 and  type=2 and resellerChainId='$chainId'");
      
        if(!$result || $result->num_rows == 0)
        {
                trigger_error('problem while get country list by prefix,Qur:'.$this->querry);
               return json_encode(array('status' => 'error' ,'msg' => 'Problem while getting states list! '));

        }

        $stateDetail = array();
        $data = array();
        $index = 0;
	$existsFlag = false;
        if($userId == ''){
            $userId = $_SESSION['id']; 
        }
        $allUserContact = $this->getAllContact($userId,'1');
        
       $hashNumbers = $allUserContact['allcontact'];
       
      
      
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
	    
	   
	    $existsFlag = false;
	    if(empty($stateDetail))
	    {
		$stateDetail[$index]['state'] = $row['state']; 
		//$stateDetail[$index]['hashValue'][$row['longCodeNo']][] = $hashNumbers[$row['longCodeNo']]; 
	    }
	    
	   
	    foreach($stateDetail as $key => $value)
	    {
		
		if($value['state'] == $row['state'])
		{
		    $index = $key;
		    $existsFlag = true;

		}

	    }
	   
	     if($existsFlag)
	    {
		if(!isset($stateDetail[$index]['hashValue'][$row['longCodeNo']]))
		{
		    if(isset($hashNumbers[$row['longCodeNo']]))
			$stateDetail[$index]['hashValue'][$row['longCodeNo']] = $hashNumbers[$row['longCodeNo']];
		    else
			$stateDetail[$index]['hashValue'][$row['longCodeNo']] = array();
		}
		$existsFlag = false;
	    }
	    else
	    {	
		$count = count($stateDetail);
		$stateDetail[$count]['state'] = $row['state']; 
		if(!isset($stateDetail[$count]['hashValue'][$row['longCodeNo']]))
		{
		    if(isset($hashNumbers[$row['longCodeNo']]))
			$stateDetail[$count]['hashValue'][$row['longCodeNo']] = $hashNumbers[$row['longCodeNo']];
		    else
			$stateDetail[$count]['hashValue'][$row['longCodeNo']] = array();
		}
	    }
	    

            unset($row);
        }
        
	
        return json_encode(array('status' => 'success' ,'msg' => 'Successfully found records!','stateDetail' => $stateDetail ,
             "userContacts" => $allUserContact ));     
    }

    /**
     * @author Ankit Patidar <ankitPatidar@hostnsoft.com>
     * @since 25/8/2014
     * @abstract called from phonebookController and api_sameer.php
     * @param int $resId
     * @param int $prefix
     * @return json
     */
    function getOneCountryDetail($resId,$prefix,$userId = '')
    {
	
	if(empty($resId) || preg_match(NOTNUM_REGX, $resId))
        {
            return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again! '));
        }

        if(empty($prefix) || preg_match(NOTNUM_REGX, $prefix))
        {
            return json_encode(array('status' => 'error','msg' => 'Please select a country first.'));
        }

        //get reseller Chain id
        $chainId = $this->getUserChainId($resId);

        if(!$chainId)
         {
             trigger_error('chain id not found,resId'.$resId);
             return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again! '));
         } 

        $table = '91_longCodeNumber';
      
        #get all access number from 91_longCodeNumber table type  2-callAccessNumber,1-smsAccessNumber,3-didforward
        $result = $this->selectData('*',$table,"prefix='$prefix' and hidden=0 and  type=2 and resellerChainId='$chainId'");
      
        if(!$result || $result->num_rows == 0)
        {
                trigger_error('problem while get country list by prefix,Qur:'.$this->querry);
               return json_encode(array('status' => 'error' ,'msg' => 'Problem while getting states list!'));

        }

	if($userId == ''){
            $userId = $_SESSION['id']; 
        }
        $stateDetail = array();
        $data = array();
        $index = 0;
	$existsFlag = false;
        $allUserContact = $this->getAllContact($userId ,'1');
        
       $hashNumbers = $allUserContact['allcontact'];
       
      
      
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
	    
	    $existsFlag = false;
	    
	    foreach($stateDetail as $key => $value)
	    {
		
		if($value['StateName'] == $row['state'])
		{
		    $index = $key;
		    $existsFlag = true;

		}

	    }
	   
	     if($existsFlag)
	    {
		if(!isset($stateDetail[$index]['AccessNumber'][$row['longCodeNo']]))
		{
		    if(isset($hashNumbers[$row['longCodeNo']])){
			
			$stateDetail[$index]['AccessNumber'][] =  array('accessNumber' => $row['longCodeNo'],
									'extensionNumber' => $hashNumbers[$row['longCodeNo']]);
			
		    }else
		    {
			 $stateDetail[$index]['AccessNumber'][] =  array('accessNumber' => $row['longCodeNo'],
									'extensionNumber' => array());
			
		    }
			
		}
		$existsFlag = false;
	    }
	    else
	    {	
		$count = count($stateDetail);
		$stateDetail[$count]['StateName'] = $row['state']; 
		if(!isset($stateDetail[$count]['AccessNumber'][$row['longCodeNo']]))
		{
		    if(isset($hashNumbers[$row['longCodeNo']]))
		    {
			 $stateDetail[$count]['AccessNumber'][] =  array('accessNumber' => $row['longCodeNo'],
									'extensionNumber' => $hashNumbers[$row['longCodeNo']]);//$row['longCodeNo'];
			
		    }else
		    {
			$stateDetail[$count]['AccessNumber'][] = array('accessNumber' => $row['longCodeNo'],
									'extensionNumber' => array()); 
			
		    }
		}
	    }
	    unset($row);
        }
        
        return json_encode(array('status' => 'success' ,'msg' => 'Successfully found records! ','stateDetail' => $stateDetail));
    }
    
    
    /**
     * @author Ankit Patidar <ankitpatiar@hostnsoft.com>
     * @since 7/2014
     * @param int $resId
     * @param int $prefix
     * @return json
     */
    function getStates($resId,$prefix)
    {
	$data = array();
	 if(empty($resId) || preg_match(NOTNUM_REGX, $resId))
        {
            return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again!','data' => $data));
        }

        if(empty($prefix) || preg_match(NOTNUM_REGX, $prefix))
        {
            return json_encode(array('status' => 'error','msg' => 'Problem while getting country list. ','data' => $data));
        }

        //get reseller Chain id
        $chainId = $this->getUserChainId($resId);

        if(!$chainId)
         {
             trigger_error('chain id not found,resId'.$resId);
             return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again! ','data' => $data));
         } 

        $table = '91_longCodeNumber';
      
        #get all access number from 91_longCodeNumber table  
        $result = $this->selectData('*',$table,"prefix='$prefix' and hidden=0 and  type=2 and resellerChainId='$chainId'");
	
	
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
	     $data[] = $row['state'];
	}
	
	if(count($data) > 0)
	    return json_encode(array('status' => 'success','msg' => 'record not found!','data' => $data));
	else
	    return json_encode(array('status' => 'error','msg' => 'record found!'));
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 5/8/2014
     * @abstract called from phonebookController
     * @param int $resId
     * @param string $state
     * @return json
     */
    function getAccessNumberBystate($resId,$state,$userId = '')
    {
     
        
	 if(empty($resId) || preg_match(NOTNUM_REGX, $resId))
        {
            return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again!','data' => $data));
        }
	
	 if(empty($state) || preg_match(NOTALPHABATESPACE_REGX, $state))
        {
            return json_encode(array('status' => 'error','msg' => 'Please select a state first! ','data' => $data));
        }
	 //get reseller Chain id
        $chainId = $this->getUserChainId($resId);

        if(!$chainId)
         {
             trigger_error('chain id not found,resId'.$resId);
             return json_encode(array('status' => 'error','msg' => 'Your login session just expired. Please login again! ','data' => $data));
         } 

        $table = '91_longCodeNumber';
      
        #get all access number from 91_longCodeNumber table  
        $result = $this->selectData('longCodeNo',$table,"hidden=0 and  type=2 and resellerChainId='$chainId' and state='$state'");
	
	
	 if(!$result || $result->num_rows == 0)
        {
                trigger_error('problem while get access number by prefix,Qur:'.$this->querry);
               return json_encode(array('status' => 'error' ,'msg' => 'Access numbers unavailable.'));

        }

        $stateDetail = array();
        $data = array();
        
        if($userId == '')
        {
             $userId = $_SESSION['id'];
        }
       $allUserContact = $this->getAllContact($userId ,'1','1');
       
       $hashNumbers = $allUserContact['allcontact'];
       
       while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            if( key_exists( $row['longCodeNo'] , $hashNumbers) )
            {
                if(!in_array('100',$hashNumbers[$row['longCodeNo']])) 
                {
                    
                    $data['number'][] = $row['longCodeNo'];
                    //$data['hash'] = '2';
                    $data['hashValue'][$row['longCodeNo']] = $hashNumbers[$row['longCodeNo']];
                }      
            } 
            else 
            {
              
                $data['number'][] = $row['longCodeNo'];
                //$data['hash'] = '3';
                 $data['hashValue'][$row['longCodeNo']] = array();
            } 
            
           
            unset($row);
        }
        
        $status = "success";
        if(empty($data))
        $status = "error";
	
	 return json_encode(array('status' => $status  ,'msg' => 'Successfully found records! ','data' => $data)); 
	
    }
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use to delete contact no and email id form phonebook tabel 

    function deleteContact($parm, $userid) 
    {

        $dbobj = new db_class();
        $contactId = $parm['contactId'];
        $collectionName = 'phonebook';
        #delete contact no 
        $condition = array('contact_id' => new mongoId($contactId));
        
        try
        {
            $result = $dbobj->mongo_delete($collectionName, $condition );
        } 
        catch (MongoCursorException $ex) 
        {
            trigger_error('Problem While deleting contact '.$ex);
            return 0;
        }
        
        $this->addLastUpdateTime($userid);
         
        //log errors
        if(!$result)
            trigger_error ('Problem While update phonebook!!!');
        
        
        $str = $this->allContactlist($userid);
        return json_encode(array("status" => "success", "msg" => "Contact number deleted successfully!", "str" => $str));
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use to get all contact no and email from phonebook 
    #type : return only access number list who already used.
    #accStatus: 1 show contct with access number and where access nuber not assign contact not show 
    # 2 show contact without access number 

    function getAllContact($userid = '0' , $type = '0' ,$accStatus ='0',$searchStr ='') 
    {
        $allcontact = array();
        
        #check userid is valid or not 
        if (!preg_match("/^[0-9]+$/", $userid)) {
            return array("allcontact" => $allcontact);
        }
        
        if(empty($userid))
           return array("allcontact" => $allcontact);
        
        $collectionName = 'phonebook';
        $dbobj = new db_class();
        
        
        
        if($accStatus == 1)
        {            
            $subCondition = array('$exists' => true);
            $condition = array('userId' => $userid,'accessNo'=>$subCondition);
        }
        else if($accStatus == 2)
        {
            $subCondition = array('$exists' => false);
            $condition = array('userId' => $userid,'accessNo'=>$subCondition);   
        }
        else
        {      
            $subCondition=array('$exists' => true);    
            $condition = array('userId' => $userid,'contact_id' => $subCondition);
	}
	
	$condition['contact'] = array('$exists' => false);
        
        if($searchStr != '')
        {      ///^bar$/i   { $regex: 'acme.*corp', $options: 'i' } 
            // $cond[] = array('name' =>  array('$regex' =>"^$searchStr.*" , $options => 'i'));
            $cond[] = array('name' => array('$regex' => $searchStr , '$options'   => 'i'  ));
            $cond[] = array('contactNo' => array('$regex' => $searchStr , '$options'   => 'i'  ));
            $cond[] = array('email' =>  array( '$regex' => $searchStr , '$options'   => 'i')  );
            $condition['$or'] = $cond;
        }
       
      //  echo json_encode($condition);
        try
        {
            $result = $dbobj->mongo_find($collectionName, $condition);

            //log errors
            if(!$result)
                trigger_error ('Problem While get details from phonebook!!!');
        
            
            foreach ($result as $res) 
            {
                if($type == '1')
                {
                   if(!empty($res['accessNo']))
                    $allcontact[$res['accessNo']][]= $res['hash'];
                }
                else
                $allcontact[] = $res;
            }

            return array("allcontact" => $allcontact );
        } 
        catch (MongoCursorException $ex) 
        {
            trigger_error('Problem While getting all contacts '.$ex);
            return 0;
        } 
    }
    
     /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 31/03/2014
     * @param int $userid
     * @param string $q search string for number,email and name
     * @return array
     */
     function getMatchedContact($request,$session) {
        $collectionName = 'phonebook';
        $dbobj = new db_class();
       
         //check user id
            if(!isset($request['userId']) || !is_numeric($request['userId']) || $request['userId']== null || $request['userId'] =='')
                return json_encode(array('status' => 0,'msg' => 'InValid userId!!!'));
            else
                $userId = $request['userId'];
           
            //check user id
            if(!isset($request['term']) || $request['term']== null || $request['term'] =='')
                $q = '';
            else
                $q = $request['term'];
           
           
        $search = array();
        $cond['or'][] = array('name' =>  array('$regex' =>"^$q"));
        $cond['or'][] = array('contactNo' => array('$regex' =>"^$q"));
        $cond['or'][] = array('email' =>  array('$regex' => "^$q"));
        #check for contact no. is already inserted in table
        $condition = array('userId' => $userId,'$or' => $cond['or']);
       
        try
        {
            $result = $dbobj->mongo_find($collectionName, $condition);

            //var_dump($result);
            $data = array();
            foreach ($result as $value)
            {

                // foreach($res['contact'] as $value)
                {
                   //check for number,name and email
                   if(preg_match("/^$q/",$value['name']) || preg_match("/^$q/",$value['contactNo']) || preg_match("/^$q/",$value['email']))
                   {
                        $data['label']= $value['name'];
                        $data['value']=$value['contactNo'];


                        $allcontact[] = $data;
                   }

                }


                unset($res);
                unset($data);
            }

            return json_encode($allcontact);
        
        } 
        catch (MongoCursorException $ex) 
        {
            trigger_error('Problem While getting matched contacts '.$ex);
            return 0;
        }
    }
    

    
     /*
     * @author nidhi<nidhiWalkover.in>
     * this function returns contact details of a particular contact.
     * 
     */
    
    function getContactInfo($userid , $contactNo ,  $type = '0' , $subcondition  = NULL )
    {
        $collectionName = 'phonebook';
        $dbobj = new db_class();
        
        #check for contact no. is already inserted in table
        
        switch ($type)
        {
            case '1':
                $condition = array('userId' => $userid , "accessNo" => $contactNo , 'contact_id' => $subcondition );
                break;
            
            case '2':
                $condition = array('userId' => $userid , "accessNo" => $contactNo);
                break;
            
            default:
                $condition = array('userId' => $userid , "contactNo" => $contactNo);  
        }
        
        try
        {
	   
            $result = $dbobj->mongo_find($collectionName, $condition);
            
            if(!$result)
            trigger_error ('Problem While get details from phonebook!!!');

            $allcontact = array();
            foreach ($result as $key=>$res) 
            {
		
                switch ($type)
                {
                    case '1':
                        $allcontact[$res['accessNo']][] = $res['hash'];
                        break;

                    case '2':
                         $allcontact[$res['accessNo']][] = $res['hash'];
                        break;

                    default:
                        $allcontact['accessNo'] = $res['accessNo'];
                        $allcontact['hash'] = $res['hash'];
                        $allcontact['contactId'] = (string)$res['contact_id'];
                } 
            }
	    
	   
            return array("allcontact" => $allcontact);
        } 
        catch (MongoCursorException $ex) 
        {
            trigger_error('Problem While Getting Contact Info '.$ex);
            return 0;
        }
    }

    
    
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 15/07/2013
    #function use to show data for edit contact detail 

    function showEditContact($parm,$userId) 
    {

        
        #collection name 
        $collectionName = 'phonebook';
        $dbobj = new db_class();
        $data = array();
        
        //54080cb5fb63e459288b4568
         if (!preg_match("/^[a-zA-Z0-9]+$/", $parm['contactId'])) 
            {
                return json_encode(array("status" => "error", "msg" => "Please select valid contact."));
            }
        
        #check for contact no. is already inserted in table
        $condition = array('contact_id' => new mongoId($parm['contactId']));
       
        
        $result = $dbobj->mongo_find($collectionName, $condition);
        
        //log errors
        if(!$result)
            trigger_error ('Problem While get details from phonebook!!!');
        #check data is present or not          
        if ($result->count() > 0) 
        {
            $res = $result->getNext();
            $data['contactId'] = $parm['contactId'];
            $data['name'] = $res['name'];
            $data['email'] = $res['email'];
            $data['contactNo'] = $res['contactNo'];
            if(isset($res['code']))
                $data['code'] = $res['code'];
            else
                $data['code'] ='';
            $data['accessNo'] = $res['accessNo'];

            if(isset($res['hash']))
                $data['assignHash'] = $res['hash'];
            
            $accessData =  $this->checkValidAccNo($res['accessNo'],1); 
            $data['accessCountry'] = $accessData['prefix'];
            $data['accState'] = $accessData['state'];
        }
        
        $data['country'] = $this->countryAllDetail();
        
             
        return json_encode($data);

    }

    
     /*
     * @auth sudhir pandey <sudhir@hostnsoft.com>
     * @desc function use to get user access number ( who used by user ) from mongo table 
     */
    function getUserAccessNumbers($userId , $contactNo=NULL)
    {
       
        $userAccessNo = array();
        extract($this->getAllContact($userId)); //$allcontact
        
        $hashArray = array();
        $allAccNo = array();
        
        foreach ($allcontact as $res) 
        {
            if(isset($res['accessNo']) && $res['hash'] == 100 )
            {
               $userAccessNo[] = $res['accessNo'];
            }
            $allAccNo[] = $res['accessNo'];
            if(isset( $res['hash']))
            {
                $hashArray[] =  $res['hash'];
            }
            
        }
       
        return array("accessNumber" => $userAccessNo, "hash" => $hashArray , "allAccessNo" => $allAccNo);
        
    }
    
    
     /*
     * @auth sudhir pandey <sudhir@hostnsoft.com>
     * @desc function use to get user access number ( who used by user ) from mongo table 
     */
    function getUserAccessNo($userId){

        $userAccessNo = array();
        extract($this->getAllContact($userId)); //$allcontact
        foreach ($allcontact as $res) 
        {
            if(isset($res['accessNo'])){
               $userAccessNo[] = $res['accessNo'];
            }
           
        }
       
        return $userAccessNo;
        
    }
    
    /*
     * @auth sudhir pandey <sudhir@hostnsoft.com>
     * @desc function use to get all access number from longcode table 
     */
    function allAccessNo( $status = "1", $type = "2" )
    {
        
      $allAccNo = array();
      $table = '91_longCodeNumber';
      
      #get all access number from 91_longCodeNumber table  
      #- last modified by nidhi nidhi@walkover.in
      #- I have added two more parameters status and type.
      #- status 0 - call , 1- sms .
      #- type 0- desable, 1-enable.
      
       if (!preg_match("/^[0-9]+$/", $status)) {
            return $allAccNo;
        }
        
        if (!preg_match("/^[0-9]+$/", $type)) {
        return $allAccNo;
       }
      $result = $this->selectData('*',$table,"resellerId = 2 and status =".$status." and type=".$type);
      
      if($result->num_rows > 0)
      {
           while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $allAccNo[] = $row['longCodeNo'];
           }
      }
        
      return $allAccNo;
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 17/07/2013
    #function use to check email id and contact no is valid or not 

    function checkContactValidation($allcode,$allemail, $allcontact , $allname) 
    {

        #msg variable use for return message (if return success then email and contact no is valid otherwise msg send )
        $msg = '';$errorKey = array();
        for ($i = 0; $i < count($allcontact); $i++) 
        {
            #check email id valid
            if ($allemail[$i] != '' || $allemail[$i] != null) 
            {
                if (!preg_match("/^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/ix", $allemail[$i])) 
                {
                   $errorKey[] = $i; 
                }
            }

            #check country code is valid or not 
            if (!preg_match("/^[0-9]{1,5}$/", $allcode[$i])) 
            {
                $errorKey[] = $i;
            }
            
            #check contact is valid or not 
            if (!preg_match("/^[0-9]{8,15}$/", $allcontact[$i])) 
            {
                $errorKey[] = $i;
            }

            if (preg_match(NOTALPHANUM_REGX_CON, $allname[$i])  ||  strlen( $allname[$i]) < 1 ||  strlen( $allname[$i]) > 30 )
            {
                 $errorKey[] = $i;
                 
            }
            
            
            
            
            
        }

        return $errorKey;
    }

    #created by sudhir pandey (sudhir@hostnsot.com)
    #creation date 05/09/2013
    function checkContactNull($allcode,$allcontact,$allemail )
    {
       #check first contact no is valid or not
        if(count($allcontact) == 1)
        {
            
            #check contact is valid or not 
            if ($allcode[0] == ''|| $allcode[0]== NULL) 
            {
                return $msg = "Please select country."; 
            }
             #check contact is valid or not 
            if (!preg_match("/^[0-9]{1,5}$/", $allcode[0])) 
            {
                 return $msg = "Please enter valid country code.";
            }
            #check contact is valid or not 
            if ($allcontact[0] == ''|| $allcontact[0]== NULL) 
            {
                return $msg = "please enter contact number"; 
            }
             #check contact is valid or not 
            if (!preg_match("/^[0-9]{8,15}$/", $allcontact[0])) 
            {
                 return $msg = "Please provide a valid contact number.";
            }
            
            if ($allemail[0] != '' || $allemail[0] != null) 
            {
                if (!preg_match("/^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/ix", $allemail[0])) 
                {
                    return $msg = "please enter valid email";
                }
            }
            
        }
//        for ($i = 0; $i < count($allcontact); $i++) {
//            #check contact is valid or not 
//            if ($allcontact[$i] == ''|| $allcontact[$i]== NULL) {
//                return $msg = "please enter contact number"; 
//             }
//        }
        return $msg = "success";
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 18/07/2013
    #function use to searching contact detail. 

    function allContactlist($userid) 
    {
        $str = '';

        extract($this->getAllContact($userid)); //$allcontact
     
        
        foreach ($allcontact as $res) 
        {
            $accessNo = 'Assign';
            if(isset($res['accessNo']))
            {
                $accessNo = $res['accessNo'];
            }                
            $str.='<li class="clear" contactId="'.$res['contact_id'].'">
			  <div class="cntAct fixed">
				<div class="edtsiWrap">
					 <a class="clear alC" onclick="showContactEdit(this);" contactid="'.$res['contact_id'].'" href="javascript:void(0);">
						  <span class="ic-24 edit"></span>  
					 </a> 
				 </div>				
			  </div>
			  <div class="cntInfo slideAndBack" onclick="dest(\''.$res['contactNo'].'\',this)">
					<div class="innerCol clear">
						  <h3 class="h3 ellp fwN">'.$res['name'].'</h3>
						  <div class="fpinfo"> <i class="ic-16 call"></i>
							 <label>'.$res['contactNo'].'</label>
						  </div>
                          <div class="fpinfo"> <i class="call"></i>
							 <label onclick="showContactEdit(this);" contactId="'.$res['contact_id'].'"  class="green tdu cp action" ><i class="ic-16 callA mrR"></i>'.$accessNo.'</label>
						  </div>
					</div>
				</div>		      
			</li>';            
        }
        return $str;
    }
    
    /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 14/03/2014
     * @filesource
     * @uses to get access numbers
     */
    function getAccessNumberDetails($request)
    {
        if(isset($request['voiceJsonp']) && $request['voiceJsonp'] != '')
                $callBack = 1;
           else
                $callBack = 0;
         $table = '91_longCodeNumber';
        
         //if type 1 means smsAccess number or 2 for callAccess numbers
         //$type = (isset($request['type']) && $request['type'] == 1)?1:0;
         
        $result = $this->selectData('*',$table,'resellerId=2 and hidden = 0');
        
        
        //validate result
        if(!$result)
        {
            $json = json_encode(array('status' => 0,'msg' => 'Problem while getting Access Number details!!!'));
            if(!$callBack)
               return $json;
            else
                return $request['voiceJsonp'].'('.$json.')';
        }
          
        if($result->num_rows == 0)
        {
            $json = json_encode(array('status' => 0,'msg' => 'Record Not Found!!!'));
             if(!$callBack)
               return $json;
            else
                return $request['voiceJsonp'].'('.$json.')';
            
            return json_encode(array('status' => 0,'msg' => 'Record Not found!!!'));
        }
        
         $callData = array();
         $smsdata = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            if($row['type'] == 2)
            {
                $dtl['accessNumber'] =   $row['longCodeNo'];
                $dtl['country'] =  $row['country'];
                $dtl['state'] =  $row['state'];
                $callData[]= $dtl;
            }
            else if($row['type'] == 1)
            {
                $dtl['accessNumber'] =   $row['longCodeNo'];
                $dtl['country'] =  $row['country'];
                $dtl['state'] =  $row['state'];
                $smsData[]= $dtl;
           
            }
            unset($dtl);
            unset($row);
        }
        
        $json =  json_encode(array('status' => 1,'msg' => 'Record Found!!!','callAccess' => $callData,'smsAccess' =>$smsData));
        
        if(!$callBack)
            return $json;
        else
            return $request['voiceJsonp'].'('.$json.')';
        
    }
    
    function getAllAccessNumberOfPrefix($request,$session)
    {
	
	
	$resutl =$this->getStatesByPrefix($session['resellerId'],$request['prefix']);
    }
    
    /**
     * @author Ankit Patidar <ankitpatiar@hostnsoft.com>
     * @since 20/09/2014
     * @abstract called from same class
     * @see Note: all parameters passed to this function are prevalidated dont call this function direct or call with apply validation
     * @uses this function will check the number for existens for exists then number use update by this function and if not exists then insert
     */
    function addAndUpdateContactfromAgent($codeArr,$numberArr,$nameArr,$userId)
    {
	
	if(empty($codeArr) || empty($numberArr) || empty($nameArr) || preg_match(NOTNUM_REGX, $userId))
	    return false;
	#code to get existed contacts
	$collectionName = 'phonebook';
        $dbobj = new db_class();
        
        $condition = array('userId' => $userId,'contactNo' => array('$in' => array(implode(',', $numberArr)) ));
      
        $result = $dbobj->mongo_find($collectionName, $condition);

	
        //log errors
        if(!$result)
	{
            trigger_error ('Problem While get details from phonebook!!!');
	
	    return false;
	}   
	
	 
	  
	$updateArr = array();
	$insertArr = array();
	$updateNameArr = array();
	$updateCodeArr = array();
	$status = false;
	//prepare update array
	foreach($result as $key =>  $contactObj)
	{
	   
	    if(in_array($contactObj['contactNo'],$numberArr))
	    {
		$updateArr[$key] = $contactObj['contactNo'];
		
		$index = array_search($contactObj['contactNo'], $numberArr);
		
		$updateNameArr[$key] = $nameArr[$index];
		$updateCodeArr[$key] = $codeArr[$index];
	    }
	   	    
	}
        
	
	//update mongo db if any any number found in array
	if(!empty($updateArr))
	{
	    foreach ($updateArr as $key => $value) {
		$updateCondition = array('userId' => $userId,'contactNo' => $value );
		
		$updateData = array('agent' => 1,
				    'name' => $updateNameArr[$key],
				    'code' => $updateCodeArr[$key]);
		
		$dataArray = array('$set' => $updateData);
		
		$updatestatus = $dbobj->mongo_update($collectionName, $updateCondition ,$dataArray);
		if($updatestatus)
		    $status = true; 
	    }
	    
	}
	 
	 
	//get array difference
	$insertArr = array_diff($numberArr, $updateArr);
	$insertData = array();
	
	if(!empty($insertArr))
	{
	    foreach ($insertArr as $key => $value) 
	    {
		$index = array_search($value, $numberArr);

		$insertData[] = array('contact_id' => new MongoId(),
				      'code' => $codeArr[$index],
				      'contactNo' => $value,
				      'name' => $nameArr[$index],
				      'agent' => 1,
				       'userId' => $userId);

	    }
	
	    $insertStatus = $dbobj->mongoBulkInsert($collectionName,$insertData );
	    if($insertStatus)
		$status = true;
	}
	
	return $status;
	
    }

}

$pbookobj = new phonebook_class();

//$response = $pbookobj->getAllContact('33097'); 
//
//print_r($response);


?>