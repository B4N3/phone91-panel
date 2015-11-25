<!--Secondary Index page menus-->
<script src="../js/highcharts.js"></script>
<script src="../js/jquery.highchartTable-min.js"></script>
<script src="../js/json-to-table.js"></script>
<script src="js/script.js"></script>
<div class="secondaryMenu">
    <ul class="clear oh">
            <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=31995'">
                    		<span class="ic-tranLog"></span>
                            <p>Transactional</p>
                    </a>
            </li>
            <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=31995'">
                        <span class="ic-editfund "></span>
                        <p> Edit Fund</p>
                    </a>
            </li>
            <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=31995'">
                         <span class="ic-addsip "></span>
                        <p> Add SIP</p>
                    </a>
             </li>
			<li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=31995'">
                         <span class="ic-setting"></span>
                        <p> Settings</p>
                    </a>
             </li>
             <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=31995'">
                        <span class="ic-latestinfo"></span>
                        <p> Latest info</p>
                    </a>
             </li>
    </ul>
	<!--//Secondary Index page menus-->   
  		 <div class="callnstatus clear">
        			<div class="box-widgets fwid">
                   		<p class="head">
                        		<span class="fl">Call Log</span>
                                <span class="fr f12">Total Calls 2500</span>
                        </p>
                        <div id="getStatusDetailsBarGraphContainer"></div>
                    </div>
                    
                    <div class="box-widgets fwid2">
                    		<p class="head">
                        		<span class="fl">Status</span>
                                <span class="fr f12">Total Calls 2500</span>
                        </p>
                        <div id="getStatusDetailsPieGraphContainer"></div>
                    </div>
        </div>
    	<div class="box-widgets timeline">
                <p class="head">
                    <span class="fl">Timeline</span>
                    <span class="fr f12">Total Calls 2500</span>
                </p>
        </div>	
</div>
<script type="text/javascript">
//call function to draw graph
getDetails('userCallLogsForChart');


</script>