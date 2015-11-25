<?php


if (file_exists('/RECORD/'.$_REQUEST['file'])) 
{
    //echo "The file  exists";
} 
else 
{
    echo "The file  does not exist"; die();
}

 $file="/RECORD/".$_REQUEST['file']; //file location
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Content-Length: ' . filesize($file));
        readfile($file);

?>