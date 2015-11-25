<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
if($_SERVER['HTTP_HOST'] != "voip91.com")
{
    echo $_SERVER['HTTP_HOST'];
    $result = $funobj->getDomainResellerId($_SERVER['HTTP_HOST'],2);
    print_R($result);
    echo  $theme = $result['theme'];
    include_once($theme."/contact.php");
    exit();
}
?>
