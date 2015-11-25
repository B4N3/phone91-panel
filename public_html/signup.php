<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */

include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';



include_once $_SERVER['DOCUMENT_ROOT'].'/config/whiteLabelConfig.php';


if(!is_dir(ROOT_DIR."/themes/"._DOMAIN_THEME_) || !file_exists(ROOT_DIR."/themes/"._DOMAIN_THEME_."/signup.php"))
{
        echo "default page";
        exit();
}
else
{
    if(isset($_REQUEST["submit"]))
    {
        extract($_REQUEST);
    }
    $country = $funobj->countryArray();
    include_once CLASS_DIR.'reseller_class.php';
    $resellerObj = new reseller_class();
    $currencyArray=$resellerObj->currencyArray();
    
    include_once(ROOT_DIR."/themes/"._DOMAIN_THEME_."/signup.php");
    exit();
}

?>