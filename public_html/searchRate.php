<?php  include('config.php');
$isDetail = 0;

if(isset($_REQUEST["action"]) && $_REQUEST["action"]=="loadDetails")
    $isDetail=1;
if(isset($_REQUEST["term"]))
{
    
        if(!preg_match('/^[a-zA-Z0-9\_\-\s]+/', $_REQUEST["term"]))
        {
        return;
        }   
    
    
	$cntry=0;
	$cntry = strtolower($_REQUEST["term"]);
	$reg_ex = '/^0+[1-9]*/';
	if(preg_match($reg_ex,$cntry))								
	$mobile_no[$i]= preg_replace('/0*/','',$mobile_no[$i],1);
	
	if (!$cntry) return;
	$dbh=$funobj->connecti();
	$search_qry;
        
        #check tariff id selected by user for search rates otherwise check session tariff id 
        if( (isset($_REQUEST['currency']) && $_REQUEST['currency']!='')){
          
             if (!preg_match("/^[0-9]+$/", $_REQUEST['currency'])) {
                return;
            }
               $cur=$_REQUEST['currency'];	
        }else
          if(isset($_SESSION['id_tariff']))  //&& ($_SESSION['id_tariff']==84 || $_SESSION['id_tariff']==9 || $_SESSION['id_tariff']==7))
	{
             $cur=$_SESSION['id_tariff'];
        }else
            $cur = 84;
        
        
        
        if(!is_numeric($cntry))
        {
                $search_qry="select distinct description as countryName,prefix,voiceRate,operator from 91_tariffs where (countryName like '".$cntry."%' || description like '".$cntry."%') and tariffId like '".$cur."' ";

                #find all rates in ascending order
                $search_qry.="ORDER BY voiceRate ASC";
        }
        else
        {
//           $search_qry="select distinct countryName,prefix,voiceRate,operator from 91_tariffs where tariffId like '".$cur."' and prefix like '".$cntry."%'";
            $cntry = (int)$cntry;
            $len_max=strlen($cntry);
            $search_qry="select distinct description as countryName,prefix,voiceRate,operator from 91_tariffs where tariffId like '".$cur."' and ( ";
            for ($exts_i = $len_max; $exts_i >= 1; $exts_i--) { //To Get The Number of digits from starting of mobile number entered
                $search_qry.="prefix='" . substr($cntry, 0, $exts_i) . "' OR ";
            }
            $search_qry = substr($search_qry, 0, -4);
            $search_qry = $search_qry. ")";
            
            #if contact no length is greter then 7 then find actual rate of contact no. 
            if($len_max > 7){
                $search_qry.=" order by length(prefix) desc limit 1";
            }else
                $search_qry.="ORDER BY voiceRate ASC";

            
        } 
        
        
        
        $exe_qry=mysqli_query($dbh,$search_qry) or die(mysqli_error());

	if(mysqli_num_rows($exe_qry)>0)
	{
	    
		$ct=0;
		while($res=mysqli_fetch_array($exe_qry))
		{
			//$arr = array ('id'=>'6','error'=>"Profile Updated Successfully ");
			//echo json_encode($arr);
			//exit();
			//echo $res['description']." (".$res['prefix'].") |".$res['description']."|".$res['prefix']."|".$res['voice_rate']."\n";
			
			
//			$descArray=  explode("-", $res['description']);
                    
                        #if action is not loadDetails then 1 oterwise 0 
			if($isDetail!=1)
			{
                            if(!is_numeric($cntry))
                            {
                                #item value is a country name 
                                $item=  trim($res['countryName']);
                                $final[]=$item;
                            }else{
                                #item value is a country name 
                                $item=  trim($res['countryName']);
                                $final[]=$item;
                            }
                            
			}
			else
			{
			    #operator name 
                            $operat = $res['operator'];
                            
                            #if operator name is not present then set country name as a operator 
                            if(!isset($res['operator']))
					$operat=$res['countryName'];
                                
                                
			    $desc = $operat;
                            $item[$desc]=($res['voiceRate'] * 100);
			    $final=$item;
			}

		}
	}
	else
	{

            $item ="No result found.";
            $final[]=$item;
	}
	mysqli_close($dbh);
        
        if($isDetail==1) {   
            $a= "";
            
        #get currency name 
        include_once CLASS_DIR.'plan_class.php';
        $planObj = new plan_class();
        #call function manageClients and return json data clientJson
        $curency=$planObj->getOutputCurrency($cur);
        $funobj = new fun();
        $currencyName = $funobj->getCurrencyViaApc($curency,1);
        
        $final['currency'] = $currencyName;
            
        }else
            $final=  array_values(array_unique($final));

        

        
        if(isset($_GET["callback"])){
             echo $_GET["callback"]."(".json_encode($final).")"; 
        }else
            echo json_encode($final);
	//exit();
}

?>