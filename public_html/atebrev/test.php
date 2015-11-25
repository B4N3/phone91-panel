<?php

include_once 'config.php';
include_once(CLASS_DIR."/phonebook_class.php");
include_once ROOT_DIR.'/config/whiteLabelConfig.php';
include_once("googleContactSync.php");


echo '  Location :: '.$funobj->getLocationInfoByIp(); 

die();
?>
