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
 $url = "https://voip91.com/isoData.php";
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
$before = columnDivide($variable,$country);
$after =  substr($variable,strlen($before)); 
$str.="$csv_line[0],$before,$after,$csv_line[2],$csv_line[3],$csv_line[4]\n";

//echo $csv_line[0];
//echo "  ";
//echo $before."   ***  ".$after;
//echo "     ".$csv_line[2] ."     ".$csv_line[3]."     ".$csv_line[4];
//echo "</br></br>";
    
}#- end of if condition if email in CSV is present in database
  echo $str; 
}


#created by sudhir pandey (sudhir@hostnsot.com)
#creation date 24/06/2013
#function use for find country array to given string 
function countryArray($data){
   
//http://phone91.com/isoData.php

#given string     
//$str='[{"Country":"Afghanistan","CountryCode":"93","ISO":"AF \/ AFG "},{"Country":"Albania","CountryCode":"355","ISO":"AL \/ ALB "},{"Country":"Algeria","CountryCode":"213","ISO":"DZ \/ DZA "},{"Country":"American Samoa","CountryCode":"1 684","ISO":"AS \/ ASM "},{"Country":"Andorra","CountryCode":"376","ISO":"AD \/ AND "},{"Country":"Angola","CountryCode":"244","ISO":"AO \/ AGO "},{"Country":"Anguilla","CountryCode":"1 264","ISO":"AI \/ AIA "},{"Country":"Antarctica","CountryCode":"672","ISO":"AQ \/ ATA "},{"Country":"Antigua and Barbuda","CountryCode":"1 268","ISO":"AG \/ ATG "},{"Country":"Argentina","CountryCode":"54","ISO":"AR \/ ARG "},{"Country":"Armenia","CountryCode":"374","ISO":"AM \/ ARM "},{"Country":"Aruba","CountryCode":"297","ISO":"AW \/ ABW "},{"Country":"Australia","CountryCode":"61","ISO":"AU \/ AUS "},{"Country":"Austria","CountryCode":"43","ISO":"AT \/ AUT "},{"Country":"Azerbaijan","CountryCode":"994","ISO":"AZ \/ AZE "},{"Country":"Bahamas","CountryCode":"1 242","ISO":"BS \/ BHS "},{"Country":"Bahrain","CountryCode":"973","ISO":"BH \/ BHR "},{"Country":"Bangladesh","CountryCode":"880","ISO":"BD \/ BGD "},{"Country":"Barbados","CountryCode":"1 246","ISO":"BB \/ BRB "},{"Country":"Belarus","CountryCode":"375","ISO":"BY \/ BLR "},{"Country":"Belgium","CountryCode":"32","ISO":"BE \/ BEL "},{"Country":"Belize","CountryCode":"501","ISO":"BZ \/ BLZ "},{"Country":"Benin","CountryCode":"229","ISO":"BJ \/ BEN "},{"Country":"Bermuda","CountryCode":"1 441","ISO":"BM \/ BMU "},{"Country":"Bhutan","CountryCode":"975","ISO":"BT \/ BTN "},{"Country":"Bolivia","CountryCode":"591","ISO":"BO \/ BOL "},{"Country":"Bosnia and Herzegovina","CountryCode":"387","ISO":"BA \/ BIH "},{"Country":"Botswana","CountryCode":"267","ISO":"BW \/ BWA "},{"Country":"Brazil","CountryCode":"55","ISO":"BR \/ BRA "},{"Country":"British Indian Ocean Territory","CountryCode":"","ISO":"IO \/ IOT "},{"Country":"British Virgin Islands","CountryCode":"1 284","ISO":"VG \/ VGB "},{"Country":"Brunei","CountryCode":"673","ISO":"BN \/ BRN "},{"Country":"Bulgaria","CountryCode":"359","ISO":"BG \/ BGR "},{"Country":"Burkina Faso","CountryCode":"226","ISO":"BF \/ BFA "},{"Country":"Burma (Myanmar)","CountryCode":"95","ISO":"MM \/ MMR "},{"Country":"Burundi","CountryCode":"257","ISO":"BI \/ BDI "},{"Country":"Cambodia","CountryCode":"855","ISO":"KH \/ KHM "},{"Country":"Cameroon","CountryCode":"237","ISO":"CM \/ CMR "},{"Country":"Canada","CountryCode":"1","ISO":"CA \/ CAN "},{"Country":"Cape Verde","CountryCode":"238","ISO":"CV \/ CPV "},{"Country":"Cayman Islands","CountryCode":"1 345","ISO":"KY \/ CYM "},{"Country":"Central African Republic","CountryCode":"236","ISO":"CF \/ CAF "},{"Country":"Chad","CountryCode":"235","ISO":"TD \/ TCD "},{"Country":"Chile","CountryCode":"56","ISO":"CL \/ CHL "},{"Country":"China","CountryCode":"86","ISO":"CN \/ CHN "},{"Country":"Christmas Island","CountryCode":"61","ISO":"CX \/ CXR "},{"Country":"Cocos (Keeling) Islands","CountryCode":"61","ISO":"CC \/ CCK "},{"Country":"Colombia","CountryCode":"57","ISO":"CO \/ COL "},{"Country":"Comoros","CountryCode":"269","ISO":"KM \/ COM "},{"Country":"Cook Islands","CountryCode":"682","ISO":"CK \/ COK "},{"Country":"Costa Rica","CountryCode":"506","ISO":"CR \/ CRC "},{"Country":"Croatia","CountryCode":"385","ISO":"HR \/ HRV "},{"Country":"Cuba","CountryCode":"53","ISO":"CU \/ CUB "},{"Country":"Cyprus","CountryCode":"357","ISO":"CY \/ CYP "},{"Country":"Czech Republic","CountryCode":"420","ISO":"CZ \/ CZE "},{"Country":"Democratic Republic of the Congo","CountryCode":"243","ISO":"CD \/ COD "},{"Country":"Denmark","CountryCode":"45","ISO":"DK \/ DNK "},{"Country":"Djibouti","CountryCode":"253","ISO":"DJ \/ DJI "},{"Country":"Dominica","CountryCode":"1 767","ISO":"DM \/ DMA "},{"Country":"Dominican Republic","CountryCode":"1 809","ISO":"DO \/ DOM "},{"Country":"Ecuador","CountryCode":"593","ISO":"EC \/ ECU "},{"Country":"Egypt","CountryCode":"20","ISO":"EG \/ EGY "},{"Country":"El Salvador","CountryCode":"503","ISO":"SV \/ SLV "},{"Country":"Equatorial Guinea","CountryCode":"240","ISO":"GQ \/ GNQ "},{"Country":"Eritrea","CountryCode":"291","ISO":"ER \/ ERI "},{"Country":"Estonia","CountryCode":"372","ISO":"EE \/ EST "},{"Country":"Ethiopia","CountryCode":"251","ISO":"ET \/ ETH "},{"Country":"Falkland Islands","CountryCode":"500","ISO":"FK \/ FLK "},{"Country":"Faroe Islands","CountryCode":"298","ISO":"FO \/ FRO "},{"Country":"Fiji","CountryCode":"679","ISO":"FJ \/ FJI "},{"Country":"Finland","CountryCode":"358","ISO":"FI \/ FIN "},{"Country":"France","CountryCode":"33","ISO":"FR \/ FRA "},{"Country":"French Polynesia","CountryCode":"689","ISO":"PF \/ PYF "},{"Country":"Gabon","CountryCode":"241","ISO":"GA \/ GAB "},{"Country":"Gambia","CountryCode":"220","ISO":"GM \/ GMB "},{"Country":"Gaza Strip","CountryCode":"970","ISO":" \/ "},{"Country":"Georgia","CountryCode":"995","ISO":"GE \/ GEO "},{"Country":"Germany","CountryCode":"49","ISO":"DE \/ DEU "},{"Country":"Ghana","CountryCode":"233","ISO":"GH \/ GHA "},{"Country":"Gibraltar","CountryCode":"350","ISO":"GI \/ GIB "},{"Country":"Greece","CountryCode":"30","ISO":"GR \/ GRC "},{"Country":"Greenland","CountryCode":"299","ISO":"GL \/ GRL "},{"Country":"Grenada","CountryCode":"1 473","ISO":"GD \/ GRD "},{"Country":"Guam","CountryCode":"1 671","ISO":"GU \/ GUM "},{"Country":"Guatemala","CountryCode":"502","ISO":"GT \/ GTM "},{"Country":"Guinea","CountryCode":"224","ISO":"GN \/ GIN "},{"Country":"Guinea-Bissau","CountryCode":"245","ISO":"GW \/ GNB "},{"Country":"Guyana","CountryCode":"592","ISO":"GY \/ GUY "},{"Country":"Haiti","CountryCode":"509","ISO":"HT \/ HTI "},{"Country":"Holy See (Vatican City)","CountryCode":"39","ISO":"VA \/ VAT "},{"Country":"Honduras","CountryCode":"504","ISO":"HN \/ HND "},{"Country":"Hong Kong","CountryCode":"852","ISO":"HK \/ HKG "},{"Country":"Hungary","CountryCode":"36","ISO":"HU \/ HUN "},{"Country":"Iceland","CountryCode":"354","ISO":"IS \/ IS "},{"Country":"India","CountryCode":"91","ISO":"IN \/ IND "},{"Country":"Indonesia","CountryCode":"62","ISO":"ID \/ IDN "},{"Country":"Iran","CountryCode":"98","ISO":"IR \/ IRN "},{"Country":"Iraq","CountryCode":"964","ISO":"IQ \/ IRQ "},{"Country":"Ireland","CountryCode":"353","ISO":"IE \/ IRL "},{"Country":"Isle of Man","CountryCode":"44","ISO":"IM \/ IMN "},{"Country":"Israel","CountryCode":"972","ISO":"IL \/ ISR "},{"Country":"Italy","CountryCode":"39","ISO":"IT \/ ITA "},{"Country":"Ivory Coast","CountryCode":"225","ISO":"CI \/ CIV "},{"Country":"Jamaica","CountryCode":"1 876","ISO":"JM \/ JAM "},{"Country":"Japan","CountryCode":"81","ISO":"JP \/ JPN "},{"Country":"Jersey","CountryCode":"","ISO":"JE \/ JEY "},{"Country":"Jordan","CountryCode":"962","ISO":"JO \/ JOR "},{"Country":"Kazakhstan","CountryCode":"7","ISO":"KZ \/ KAZ "},{"Country":"Kenya","CountryCode":"254","ISO":"KE \/ KEN "},{"Country":"Kiribati","CountryCode":"686","ISO":"KI \/ KIR "},{"Country":"Kosovo","CountryCode":"381","ISO":" \/ "},{"Country":"Kuwait","CountryCode":"965","ISO":"KW \/ KWT "},{"Country":"Kyrgyzstan","CountryCode":"996","ISO":"KG \/ KGZ "},{"Country":"Laos","CountryCode":"856","ISO":"LA \/ LAO "},{"Country":"Latvia","CountryCode":"371","ISO":"LV \/ LVA "},{"Country":"Lebanon","CountryCode":"961","ISO":"LB \/ LBN "},{"Country":"Lesotho","CountryCode":"266","ISO":"LS \/ LSO "},{"Country":"Liberia","CountryCode":"231","ISO":"LR \/ LBR "},{"Country":"Libya","CountryCode":"218","ISO":"LY \/ LBY "},{"Country":"Liechtenstein","CountryCode":"423","ISO":"LI \/ LIE "},{"Country":"Lithuania","CountryCode":"370","ISO":"LT \/ LTU "},{"Country":"Luxembourg","CountryCode":"352","ISO":"LU \/ LUX "},{"Country":"Macau","CountryCode":"853","ISO":"MO \/ MAC "},{"Country":"Macedonia","CountryCode":"389","ISO":"MK \/ MKD "},{"Country":"Madagascar","CountryCode":"261","ISO":"MG \/ MDG "},{"Country":"Malawi","CountryCode":"265","ISO":"MW \/ MWI "},{"Country":"Malaysia","CountryCode":"60","ISO":"MY \/ MYS "},{"Country":"Maldives","CountryCode":"960","ISO":"MV \/ MDV "},{"Country":"Mali","CountryCode":"223","ISO":"ML \/ MLI "},{"Country":"Malta","CountryCode":"356","ISO":"MT \/ MLT "},{"Country":"Marshall Islands","CountryCode":"692","ISO":"MH \/ MHL "},{"Country":"Mauritania","CountryCode":"222","ISO":"MR \/ MRT "},{"Country":"Mauritius","CountryCode":"230","ISO":"MU \/ MUS "},{"Country":"Mayotte","CountryCode":"262","ISO":"YT \/ MYT "},{"Country":"Mexico","CountryCode":"52","ISO":"MX \/ MEX "},{"Country":"Micronesia","CountryCode":"691","ISO":"FM \/ FSM "},{"Country":"Moldova","CountryCode":"373","ISO":"MD \/ MDA "},{"Country":"Monaco","CountryCode":"377","ISO":"MC \/ MCO "},{"Country":"Mongolia","CountryCode":"976","ISO":"MN \/ MNG "},{"Country":"Montenegro","CountryCode":"382","ISO":"ME \/ MNE "},{"Country":"Montserrat","CountryCode":"1 664","ISO":"MS \/ MSR "},{"Country":"Morocco","CountryCode":"212","ISO":"MA \/ MAR "},{"Country":"Mozambique","CountryCode":"258","ISO":"MZ \/ MOZ "},{"Country":"Namibia","CountryCode":"264","ISO":"NA \/ NAM "},{"Country":"Nauru","CountryCode":"674","ISO":"NR \/ NRU "},{"Country":"Nepal","CountryCode":"977","ISO":"NP \/ NPL "},{"Country":"Netherlands","CountryCode":"31","ISO":"NL \/ NLD "},{"Country":"Netherlands Antilles","CountryCode":"599","ISO":"AN \/ ANT "},{"Country":"New Caledonia","CountryCode":"687","ISO":"NC \/ NCL "},{"Country":"New Zealand","CountryCode":"64","ISO":"NZ \/ NZL "},{"Country":"Nicaragua","CountryCode":"505","ISO":"NI \/ NIC "},{"Country":"Niger","CountryCode":"227","ISO":"NE \/ NER "},{"Country":"Nigeria","CountryCode":"234","ISO":"NG \/ NGA "},{"Country":"Niue","CountryCode":"683","ISO":"NU \/ NIU "},{"Country":"Norfolk Island","CountryCode":"672","ISO":" \/ NFK "},{"Country":"North Korea","CountryCode":"850","ISO":"KP \/ PRK "},{"Country":"Northern Mariana Islands","CountryCode":"1 670","ISO":"MP \/ MNP "},{"Country":"Norway","CountryCode":"47","ISO":"NO \/ NOR "},{"Country":"Oman","CountryCode":"968","ISO":"OM \/ OMN "},{"Country":"Pakistan","CountryCode":"92","ISO":"PK \/ PAK "},{"Country":"Palau","CountryCode":"680","ISO":"PW \/ PLW "},{"Country":"Panama","CountryCode":"507","ISO":"PA \/ PAN "},{"Country":"Papua New Guinea","CountryCode":"675","ISO":"PG \/ PNG "},{"Country":"Paraguay","CountryCode":"595","ISO":"PY \/ PRY "},{"Country":"Peru","CountryCode":"51","ISO":"PE \/ PER "},{"Country":"Philippines","CountryCode":"63","ISO":"PH \/ PHL "},{"Country":"Pitcairn Islands","CountryCode":"870","ISO":"PN \/ PCN "},{"Country":"Poland","CountryCode":"48","ISO":"PL \/ POL "},{"Country":"Portugal","CountryCode":"351","ISO":"PT \/ PRT "},{"Country":"Puerto Rico","CountryCode":"1","ISO":"PR \/ PRI "},{"Country":"Qatar","CountryCode":"974","ISO":"QA \/ QAT "},{"Country":"Republic of the Congo","CountryCode":"242","ISO":"CG \/ COG "},{"Country":"Romania","CountryCode":"40","ISO":"RO \/ ROU "},{"Country":"Russia","CountryCode":"7","ISO":"RU \/ RUS "},{"Country":"Rwanda","CountryCode":"250","ISO":"RW \/ RWA "},{"Country":"Saint Barthelemy","CountryCode":"590","ISO":"BL \/ BLM "},{"Country":"Saint Helena","CountryCode":"290","ISO":"SH \/ SHN "},{"Country":"Saint Kitts and Nevis","CountryCode":"1 869","ISO":"KN \/ KNA "},{"Country":"Saint Lucia","CountryCode":"1 758","ISO":"LC \/ LCA "},{"Country":"Saint Martin","CountryCode":"1 599","ISO":"MF \/ MAF "},{"Country":"Saint Pierre and Miquelon","CountryCode":"508","ISO":"PM \/ SPM "},{"Country":"Saint Vincent and the Grenadines","CountryCode":"1 784","ISO":"VC \/ VCT "},{"Country":"Samoa","CountryCode":"685","ISO":"WS \/ WSM "},{"Country":"San Marino","CountryCode":"378","ISO":"SM \/ SMR "},{"Country":"Sao Tome and Principe","CountryCode":"239","ISO":"ST \/ STP "},{"Country":"Saudi Arabia","CountryCode":"966","ISO":"SA \/ SAU "},{"Country":"Senegal","CountryCode":"221","ISO":"SN \/ SEN "},{"Country":"Serbia","CountryCode":"381","ISO":"RS \/ SRB "},{"Country":"Seychelles","CountryCode":"248","ISO":"SC \/ SYC "},{"Country":"Sierra Leone","CountryCode":"232","ISO":"SL \/ SLE "},{"Country":"Singapore","CountryCode":"65","ISO":"SG \/ SGP "},{"Country":"Slovakia","CountryCode":"421","ISO":"SK \/ SVK "},{"Country":"Slovenia","CountryCode":"386","ISO":"SI \/ SVN "},{"Country":"Solomon Islands","CountryCode":"677","ISO":"SB \/ SLB "},{"Country":"Somalia","CountryCode":"252","ISO":"SO \/ SOM "},{"Country":"South Africa","CountryCode":"27","ISO":"ZA \/ ZAF "},{"Country":"South Korea","CountryCode":"82","ISO":"KR \/ KOR "},{"Country":"Spain","CountryCode":"34","ISO":"ES \/ ESP "},{"Country":"Sri Lanka","CountryCode":"94","ISO":"LK \/ LKA "},{"Country":"Sudan","CountryCode":"249","ISO":"SD \/ SDN "},{"Country":"Suriname","CountryCode":"597","ISO":"SR \/ SUR "},{"Country":"Svalbard","CountryCode":"","ISO":"SJ \/ SJM "},{"Country":"Swaziland","CountryCode":"268","ISO":"SZ \/ SWZ "},{"Country":"Sweden","CountryCode":"46","ISO":"SE \/ SWE "},{"Country":"Switzerland","CountryCode":"41","ISO":"CH \/ CHE "},{"Country":"Syria","CountryCode":"963","ISO":"SY \/ SYR "},{"Country":"Taiwan","CountryCode":"886","ISO":"TW \/ TWN "},{"Country":"Tajikistan","CountryCode":"992","ISO":"TJ \/ TJK "},{"Country":"Tanzania","CountryCode":"255","ISO":"TZ \/ TZA "},{"Country":"Thailand","CountryCode":"66","ISO":"TH \/ THA "},{"Country":"Timor-Leste","CountryCode":"670","ISO":"TL \/ TLS "},{"Country":"Togo","CountryCode":"228","ISO":"TG \/ TGO "},{"Country":"Tokelau","CountryCode":"690","ISO":"TK \/ TKL "},{"Country":"Tonga","CountryCode":"676","ISO":"TO \/ TON "},{"Country":"Trinidad and Tobago","CountryCode":"1 868","ISO":"TT \/ TTO "},{"Country":"Tunisia","CountryCode":"216","ISO":"TN \/ TUN "},{"Country":"Turkey","CountryCode":"90","ISO":"TR \/ TUR "},{"Country":"Turkmenistan","CountryCode":"993","ISO":"TM \/ TKM "},{"Country":"Turks and Caicos Islands","CountryCode":"1 649","ISO":"TC \/ TCA "},{"Country":"Tuvalu","CountryCode":"688","ISO":"TV \/ TUV "},{"Country":"Uganda","CountryCode":"256","ISO":"UG \/ UGA "},{"Country":"Ukraine","CountryCode":"380","ISO":"UA \/ UKR "},{"Country":"United Arab Emirates","CountryCode":"971","ISO":"AE \/ ARE "},{"Country":"United Kingdom","CountryCode":"44","ISO":"GB \/ GBR "},{"Country":"United States","CountryCode":"1","ISO":"US \/ USA "},{"Country":"Uruguay","CountryCode":"598","ISO":"UY \/ URY "},{"Country":"US Virgin Islands","CountryCode":"1 340","ISO":"VI \/ VIR "},{"Country":"Uzbekistan","CountryCode":"998","ISO":"UZ \/ UZB "},{"Country":"Vanuatu","CountryCode":"678","ISO":"VU \/ VUT "},{"Country":"Venezuela","CountryCode":"58","ISO":"VE \/ VEN "},{"Country":"Vietnam","CountryCode":"84","ISO":"VN \/ VNM "},{"Country":"Wallis and Futuna","CountryCode":"681","ISO":"WF \/ WLF "},{"Country":"West Bank","CountryCode":"970","ISO":" \/ "},{"Country":"Western Sahara","CountryCode":"","ISO":"EH \/ ESH "},{"Country":"Yemen","CountryCode":"967","ISO":"YE \/ YEM "},{"Country":"Zambia","CountryCode":"260","ISO":"ZM \/ ZMB "},{"Country":"Zimbabwe","CountryCode":"263","ISO":"ZW \/ ZWE "}]';
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

?>