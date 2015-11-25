<?php

if(isset($_REQUEST['pdfKey']) && is_numeric($_REQUEST['pdfKey'])){  

$fileName = "pdfFolder/".$_REQUEST['pdfKey'].".pdf";

// We'll be outputting a PDF
header('Content-type: application/pdf');

// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="'.$fileName.'"');

// The PDF source is in original.pdf
readfile($fileName);

echo "successfully download.";
}else
    echo "please enter valid key";
?> 