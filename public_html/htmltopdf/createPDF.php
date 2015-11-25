<?php


        define('DB_HOST', 'localhost');
        define('DB_NAME', 'htmlToPdf');
        define('DB_USER','pdfuser');
        define('DB_PASSWORD','J1glF5XbJ7FJ1');
        $con=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("Failed to connect to MySQL: " . mysql_error()); 
        $db=mysql_select_db(DB_NAME,$con) or die("Failed to connect to MySQL: " . mysql_error());
        
        $query = mysql_query("SELECT * FROM userLogin where authKey = '".$_REQUEST['authToken']."'") or die(mysql_error());
        
        $num_rows = mysql_num_rows($query);
     
        
        if($num_rows > 0){
            
            $row = mysql_fetch_array($query) or die(mysql_error());
            $userId = $row['userId'];
            $query2 = mysql_query("SELECT * FROM userHtmlTemplate where userId = '".$userId."' and templateId='".$_REQUEST['htmlTempId']."'") or die(mysql_error());
            
            $totalcount = mysql_num_rows($query2);
            if($totalcount > 0){
                
                #save template in pdf form 
                include_once("templateClass.php");
                $tempObj = new templateClass();
                $template = $tempObj->saveTemplateInPdf($_REQUEST,1);

                echo json_encode(array("status"=>"success","templateId"=>$template));
                
            }else
                 echo json_encode(array("status"=>"error","message"=>"SORRY... YOU ENTERD WRONG TEMPLATE, PLEASE RETRY...")); 
          
            }else
            {
               echo json_encode(array("status"=>"error","message"=>"SORRY... YOU ENTERD WRONG AUTH KEY, PLEASE RETRY...")); 
            }
       
       
  
?> 