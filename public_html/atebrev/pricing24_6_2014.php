<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/config/whiteLabelConfig.php';


if(!is_dir(ROOT_DIR."/themes/"._DOMAIN_THEME_) || !file_exists(ROOT_DIR."/themes/"._DOMAIN_THEME_."/pricing.php"))
{
        echo "default page";
        exit();
}
else
{
    include_once(ROOT_DIR."/themes/"._DOMAIN_THEME_."/pricing.php");
//    exit();
}

?>
<input type="hidden" id="teriffPlan" value="<?php echo _TARIFF_PLAN_; ?>">
<script type="text/javascript" src="js/priceInc.js"></script>
<script type="text/javascript">
    
    renderBankDetails(<?php echo _BANK_DETAIL_; ?>);
    searchPrice(<?php echo _TARIFF_PLAN_; ?>);
</script>