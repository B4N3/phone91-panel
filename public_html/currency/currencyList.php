<?php

echo currencyList();


function currencyList(){

     $mysqli=new mysqli('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8', 'voip91_switch');
     $sql = "select currencyId,currency from 91_currencyDesc";
     $result = $mysqli->query($sql);
     if ($result->num_rows > 0) {

                while ($rowData = $result->fetch_array(MYSQL_ASSOC)) {
                $currencyArr[] = $rowData;
                }
            }
            
     return json_encode($currencyArr);

}
?>
