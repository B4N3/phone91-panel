<?php

include("MPDF60/mpdf.php");
 
//echo htmlentities(file_get_contents('credit.php'));

$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0); 
 
$mpdf->SetDisplayMode('fullpage');
 
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
 
$mpdf->WriteHTML(file_get_contents('credit.php'));
         
$mpdf->Output('newpdfs.pdf','F');

?>