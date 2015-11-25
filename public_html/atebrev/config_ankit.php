<?php 

/**
 * @author Rahul Chordiya <rahul@hostnsoft.com>
 * @since 07 Aug 2013
 * @details file use to include all necessary files
 */

error_reporting(-1);
//Include session file so we can maintain session for each page
include_once 'session.php';

//Include path page so that we can define all constant for our system
include_once 'definePath.php';

//include all necessary function
require_once "function_layer_ankit.php";


/************ Error Log Code start Here *************/
//Include Error talk class use to log each and every error warning notice
include_once CLASS_DIR."class.errortalk.php";
errorTalk::initialize(); 
errorTalk::errorTalk_Open();
/************ Error Log Code end Here *************/


?>