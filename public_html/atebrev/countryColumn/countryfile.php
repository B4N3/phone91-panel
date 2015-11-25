<?php
#- file use for normaliz data of table (break column according to given array of country)
#- read upload csv file and break column data to new csv file  
#- author- sudhir pandey (sudhir@hostnsoft.com)
#- date- 24/06/2013

#function for get data of given url 
function get_data($url) {
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}


#find country name array
$url = "https://voice.phone91.com/isoData.php";
$data = get_data($url);

if ( isset($_POST["submit"]) ) {

   if ( isset($_FILES["file"])) {

       
            //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
             //if file already exists
             if (file_exists("csvfiles/" . $_FILES["file"]["name"])) {
                 echo "file already exists";
             }
             else {
                    
           
            #- finding extension of file
            $extension = (explode(".", $_FILES["file"]["name"]));
            #- if file extension is not csv return
            if($extension[1] != 'csv')
            return json_encode(array("msgtype"=>"error","msg"=>"Only Csv Files Are allowed.","fileName"=> ""));
            #new file name 
            $newFileName = $extension[0].time().".".$extension[1];
            
            echo "csvfiles/" . $newFileName;
            
            move_uploaded_file($_FILES["file"]["tmp_name"], "csvfiles/" . $newFileName);
            
            }
        }
     } else {
             echo "No file selected <br />";
     }
     
     if($fp = fopen("csvfiles/".$newFileName,'r')){
         #find country array 
         $country = countryArray($data);
         
         #call uploadCsvF
         echo tableNormalized($country,$fp);
     }  else {
     echo "file not found.";    
     }
     
}



#- function to normaliz data of table (break column according to given array of country)
#- author- sudhir pandey (sudhir@hostnsoft.com)
#- date- 24/06/2013
function tableNormalized($country,$fp)
{
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=newtable.csv");
header("Pragma: no-cache");
header("Expires: 0");
$str='';
# get csv data 
while($csv_line = fgetcsv($fp,1024))
{
    
#data of column where country given
$variable=$csv_line[1];

#variable convert country and remaining data before use for country and after for remaining data
extract(columnDivide($variable,$country)); //$before,$after
//$after =  substr($variable,strlen($before)); 
$str.="$csv_line[0],$before,$after,$csv_line[2],$csv_line[3],$csv_line[4],$csv_line[5],$csv_line[6],$csv_line[7]\n";


    
}#- end of if condition if email in CSV is present in database
  echo $str; 
}


#created by sudhir pandey (sudhir@hostnsot.com)
#creation date 24/06/2013
#function use for find country array to given string 
function countryArray($data){
   
$string1 = json_decode($data, true);
for($i=0;$i<count($string1);$i++){
    $country[]=$string1[$i]['Country'];
} 
return $country;
}

#created by sudhir pandey (sudhir@hostnsot.com)
#creation date 24/06/2013
#function use for break column according to given array of country (country name return )
function columnDivide($variable,$array)
{
    $before == '';
    for($i=0;$i<count($array);$i++){
       if (preg_match("/$array[$i]/", $variable))
        {
           $before = $array[$i];
           $after = preg_replace("/$array[$i]/",'', $variable);
	   break;
        }
    }
    #if country not match in variable
    if($before == ''){
           $after = $variable;
    }
    if($variable == "Destination Name"){
        $before = "Country";
    }
    return array("before"=>$before,"after"=>$after);
}




/*


#created by sudhir pandey (sudhir@hostnsot.com)
#creation date 24/06/2013
#function use for break column according to given array of country (country name return )
function columnDivide($variable,$array)
{
 
 #remove word after last space of variable    
 extract(removeLastString($variable));
 
 #check before data is country or not 
 if(in_array($before,$array)){
     #return country 
     return $before;
 }else
 {
     #check before is not null if yes then call same funtion with "before" parameter
     if($before != '')         
     return columnDivide($before,$array);
     else
       return $before;
 }  
}


#created by sudhir pandey (sudhir@hostnsot.com)
#creation date 24/06/2013
#function use for remove word after last space of variable    
function removeLastString($variable){
#find last space posion 
$lastSpace = strrpos($variable, ' ');

#find after and before string of space 
$before = substr($variable,0, $lastSpace); // 'put returns between'
$after = substr($variable,$lastSpace); // ' paragraphs' (note the leading whitespace)
return array("before"=>$before,"after"=>$after);

}
*/
?>