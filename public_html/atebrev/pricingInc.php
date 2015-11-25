<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */

if(isset($_SESSION['id_tariff']) && $_SESSION['id_tariff']!=''){
    $tariffId  = $_SESSION['id_tariff'];
}else
    $tariffId = 84;
?>

<section id="search-container">
	<div class="wrapper clear">
        <div id="searchWrap" class="clear">
			<!--<input id="seachCountry" type="text" placeholder="Enter Destination..." />-->
            <div class="pr">
            	<i class="ic-24 search pa" style="top:4px; left:6px;"></i><input type="text" id="search" name="" placeholder="Search country or country code" value="" class="srchPrice" />
            </div>
            <!--<input type="hidden" id="tariff" name="tariff" value="<?php echo $tariffId;?>" />-->
             <?php if (!$funobj->login_validate()) { ?>

            <select id="tariff" name="tariff"/>
            
            </select>
             <?php }else{ ?>
            <input type="hidden" id="tariff" name="tariff" value="<?php echo $tariffId;?>" />
             <?php }?>
            <div id="selwrpa">
                <!--<div class="currencySelectDropdown">
                    <span class="pickDown" style=""></span>
                    <div id="pickedCurrency">EUR</div>
                </div>-->
                <!--<select name="currency" id="currency" onchange="showprice();" >
                    <option value="9">AED</option>
                    <option value="7">INR</option>
                    <option value="84">USD</option>
                </select>-->
                </select>
            </div>
            <!--flag list start this div is visible when user search country-->

        </div><!--/end of search div-->
        
        <!--suggested country data-->
        <div id="stcnt"></div>
        <!--/end of suggested country data-->
    </div><!--/end of wrapper-->
</section><!--/end of section-->

<section id="result-container" class="hgPart showhideDiv" style="display:none;">
	<!--dyamic content comes here don't change this--> 
</section>
