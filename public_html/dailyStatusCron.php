<?php

/* @author: Sameer Rathod
 * @created : 27-8-2013
 * @desc : get the details of the calls on daily basis group by user and status 
 */
include_once ('config.php');
//include_once (CLASS_DIR.'db_class.php');
//$dbClsObj = new db_class();
class statusCron extends fun
{
    function fetchRecords($con = Null)
    {
        /* @desc : fetch the data from 91_calls table for a today
         */
        $date = date("Y-m-d");
        $condition = "call_start between '$date 00:00:00' AND '$date 23:59:59' ";
        if(!is_null($con))
            $condition = $condition.$con;
        $this->db->select("id_client,status,id_chain,call_start,call_type,called_number")->from("91_calls")->where($condition);
        $query = $this->db->getQuery();
        $res = $this->db->execute();
        return $res;
    }
    function renderResult($res)
    {
        /* @desc: function used to render the data returned from my sql 
         * @return : return an array of data 
         */
        while($row = $res->fetch_array(MYSQLI_ASSOC))
        {
         
            if($row['status'] == "ANSWERED")
            $finalRes[$row['id_client']]["ANSWERED"]++;
            else
            $finalRes[$row['id_client']]["FAILED"]++;
            $finalRes[$row['id_client']]["chainId"] = $row['id_chain'];
            $finalRes[$row['id_client']]["date"] = $row['call_start'];
            $finalRes[$row['id_client']][$row["call_type"]]++ ;
            $finalRes[$row['id_client']]['number'][] = $row["called_number"] ;
        }
        return $finalRes;
    }
    function insertCronArr($res)
    {
        /* @desc: insert the final value in the cron tables for both  status and call via graph
         */
        #get the final array of value from the db
        $finalRes = $this->renderResult($res);
        #query for status graph cron
        $query = "INSERT INTO 91_statuscron (userID,chainId,answered,failed,date) values ";
        #query for call via graph cron
        $callViaQuery = "INSERT INTO 91_callviacron (userID,chainId,gtalk,c2c,date) values ";
        $str =""; 
        $callviastr ="";
        $i=1;
        $num = count($finalRes);
        
        foreach($finalRes as $key => $value)
        {
            #prepare multiple value string for status cron
            $str .= "('".$key."','".$value['chainId']."','".$value['ANSWERED']."','".$value['FAILED']."','".$value['date']."'),";
            #prepare multiple value string for callvia cron
            $callviastr .= "('".$key."','".$value['chainId']."','".$value['gtalk']."','".$value['C2C']."','".$value['date']."'),";
            
            #if the iteration is exceede for more than 500 hundred the execute the query and reinitialize all variable
            if($i == 500 || $i == $num)
            {
                #status
                $str = substr($str ,0,-1);
                $query = $query.$str;
                $res = $this->db->query($query);
                
                #callvia
                $callviastr = substr($callviastr ,0,-1);
                $callViaQuery = $callViaQuery.$callviastr;
                $callViaRes = $this->db->query($callViaQuery);
                
                
                if(!$res || !$callViaRes)
                {
                    return false;
                }
                $i = 1;
            }
            $i++;
        }
            return true;
    }
   
     
    function gettotal($column,$table)
    {
        /* @desc : count the data for each reseller per day wise ie it fetches the data inserted by the 
         *         first cron and calulate the total value by a reseller and insert it into another tabel 
         * @param : $column : array of all the column 
         *          $table : table name from where the data is fetched
         */
        #check if the coloum if not empty
        if(!is_array($column)  || is_null($column) )//|| !is_array($dbColoumn)  || is_null($dbColoumn) )
            die("please sepcify coloumn name");
        
        #query for fetching data from cron table 
        $query = "select * from ".$table."cron";
        #execute the query
        $result = $this->db->query($query);
        
        #prepare the list of column name 
        for($i=0; $i<count($column); $i++)
        {
            $colName .= $column[$i].",";
        }
        #render the result
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $chainId[] =$row['chainId'] ;
            for($i=0; $i<count($column); $i++)
            {
                $resultArr[$row['chainId']][$column[$i]] += $row[$column[$i]];
            }
            $resultArr[$row['chainId']]['date'] = $row['date'];
            $resultArr[$row['chainId']]['userId'] = $row['userID'];

        }

        #usort sort the chain ID  in desecending order of length
        usort($chainId,function($a,$b){return strlen($a) < strlen($b); });

        foreach($chainId as $chn)
        {
            foreach($resultArr as $key => $value)
            {
                $chainStr = substr($chn,0,-4); 
                $keyStr = substr($key,0,-4);
    
                if(substr($chn,0,-4) == substr($key,0,-4))
                {
                    for($i=0; $i<count($column); $i++)
                        $insertArr[$chainStr][$column[$i]] = $insertArr[$chainStr][$column[$i]] + $value[$column[$i]];
                    
                }
                $insertArr[$chainStr][$keyStr] = $value['userId'];
                $date = $value['date'];
            }
        }
        #get onlye date remove the time 
        $date = explode(" ", $date);
        $queryStr = "INSERT INTO $table (userID,".$colName."date) values ";
        foreach($insertArr as $k => $val)
        {
            $str .= "('".$val["$k"]."',";
            #loop is used for number of column this will aurtomaticaly render 
            #if in future column count is increased
            for($i=0; $i<count($column); $i++)
            {
                $col .= "'".$val[$column[$i]]."',";
            }       
            $str .=$col;
            $str .="'".$date[0]."'),";
        }
         $str = substr($str, 0,-1);
        
         #prepare full query
        $queryStr = $queryStr.$str;
        $resultStr = $this->db->query($queryStr);
        
    }
    
    function getCountryCode()
    {
        /* @desc : function fetched the country code by hitiing url 
         */
         // create curl resource 
        $ch = curl_init(); 
        // set url 
        curl_setopt($ch, CURLOPT_URL, "http://voip92.com/isoData.php"); 
        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // $output contains the output string 
        $output = curl_exec($ch); 
        
        // close curl resource to free up system resources 
        curl_close($ch);
        return json_decode($output);
    }
    function compareConCode($numArr,$cCodeArr)
    {
        /* @desc: function is used to match tcountry code with number
         *        return and array of country code with counter of number 
         */
        
        #number array consist of the number for which country name should be found
        foreach($numArr as $num)
        {
            #country code array 
            foreach($cCodeArr as $value)
            {
                if($value->CountryCode != "")
                {
                    #compare if the country code exactly matches then it will return 0 
                    #other wise a negative or a positive value gtreated than 0 is returned
                    #if return 0 then the number counter is increased by 1
                    $cCode = strncmp($num,$value->CountryCode,  strlen($value->CountryCode));
                    if($cCode == 0)
                    {
                        #count the number per country 
                        $resArray[$value->CountryCode]++;
                        break;
                    }
                }
            }
        }
       return $resArray; 
    }
    function getCountryFromNumber()
    {
        /* @desc : function is used to get the total number called per country on daily basis 
         */
        #get records from the database
        $res = $this->fetchRecords(" AND status = 'ANSWERED'");
        while($row = $res->fetch_array(MYSQLI_ASSOC))
        {
            #number array fetched from databse only answered call is fetched
            $numArr[] = $row['called_number'];

            #remove the time from the date
            $date = explode(" ",$row['call_start']);
            $date = $date[0];
        }
        
        #get the country code array
        $cCodeArr = $this->getCountryCode();
        
        # get the country to which the number belongs 
        $response = $this->compareConCode($numArr,$cCodeArr);
        
        foreach($response as $key => $value)
        {
            $str .= "('".$key."','".$value."','".$date."'),";
        }
        $str = substr($str,0,-1);
        $query = "INSERT INTO 91_countryLog (countryCode,count,cronDate) values ".$str;
        $result = $this->db->query($query);
    }
    function getAllData()
    {
        /* @desc : main function to be callled 
         */
        $res = $this->fetchRecords();
        $result = $this->insertCronArr($res);
//        if(!$result)
//            mail
    }
}
$obj = new statusCron();
$columnStatus = array('answered','failed');
$tableStatus = "91_status";
$columnCallVia = array('gtalk','c2c');
$tableCallVia = "91_callvia";
//$obj->gettotal($columnStatus,$tableStatus);
$obj->getCountryFromNumber();
//$obj->gettotal($columnCallVia,$tableCallVia);

?>
