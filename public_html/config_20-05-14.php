<?php 

/**
 * @author Rahul Chordiya <rahul@hostnsoft.com>
 * @since 07 Aug 2013
 * @details file use to include all necessary files
 */
date_default_timezone_set('Asia/Calcutta');
error_reporting(0);
//Include session file so we can maintain session for each page
include_once 'session.php';

//Include path page so that we can define all constant for our system
include_once 'definePath.php';

//Include regex constant page so that we can define all constant for our system
include_once 'defineConstant.php';


//include all necessary function

include_once "function_layer.php";
include_once "logmonitor.php";


/************ Error Log Code start Here *************/
//Include Error talk class use to log each and every error warning notice
include_once CLASS_DIR."class.errortalk.php";
errorTalk::initialize(); 
errorTalk::errorTalk_Open();
/************ Error Log Code end Here *************/


?>
