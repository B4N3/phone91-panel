<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/config/whiteLabelConfig.php';
if(!is_dir(ROOT_DIR."/themes/"._DOMAIN_THEME_) || !file_exists(ROOT_DIR."/themes/"._DOMAIN_THEME_."/contact.php"))
{
        echo "default page";
        exit();
}
else
{
    include_once(ROOT_DIR."/themes/"._DOMAIN_THEME_."/contact.php");
    exit();
}
?>
