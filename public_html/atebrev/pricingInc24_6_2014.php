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
        <p class="search">Enter Country name or full mobile number to know the exact price</p>
        <div id="searchWrap" class="clear">
			<!--<input id="seachCountry" type="text" placeholder="Enter Destination..." />-->
            <input type="text" id="search" name="" placeholder="Type Country Name" value="" class="srchPrice" />
            <input type="hidden" id="tariff" name="tariff" value="<?php echo $tariffId;?>" />
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
