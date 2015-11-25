<?php

if(!isset($_REQUEST['clientId'])){
    $_REQUEST['clientId'] = 28409;
}

?>

<!--Manage Client Settings-->
<script src="../js/highcharts.js"></script>
<!--<script src="../js/jquery.highchartTable-min.js"></script>-->
<script src="../js/json-to-table.js"></script>
<script src="js/script.js"></script>
<div class="secondaryMenu">
  		<ul class="clear oh mrB2">
            <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=<?php echo $_REQUEST['clientId'];?>'">
                    		<span class="ic-tranLog"></span>
                            <p>Transactional</p>
                    </a>
            </li>
            <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=<?php echo $_REQUEST['clientId'];?>'">
                        <span class="ic-editfund "></span>
                        <p> Edit Fund</p>
                    </a>
            </li>
            <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=<?php echo $_REQUEST['clientId'];?>'">
                         <span class="ic-addsip "></span>
                        <p> Add SIP</p>
                    </a>
             </li>
			<li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=<?php echo $_REQUEST['clientId'];?>'">
                         <span class="ic-setting"></span>
                        <p> Settings</p>
                    </a>
             </li>
             <li>
            		<a href="javascript:void(0)" onclick="window.location.href='#!manage-client.php|transactional.php?clientId=<?php echo $_REQUEST['clientId'];?>'">
                        <span class="ic-latestinfo"></span>
                        <p> Latest info</p>
                    </a>
             </li>
    </ul>
  		<div class="callnstatus clear">
        			<div class="box-widgets fwid">
                   		<p class="head">
                        		<span class="fl">Call Log</span>
                                <span class="fr f12">Total Calls 2500</span>
                        </p>
                       <div class="content">
                      	  <div id="getStatusDetailsBarGraphContainer"></div>
                        </div>
                    </div>
                    
                    <div class="box-widgets fwid2">
                    		<p class="head">
                        		<span class="fl">Status</span>
                                <span class="fr f12">Total Calls 2500</span>
                        </p>
                         <div class="content">
                        	<div id="getStatusDetailsPieGraphContainer"></div>
                         </div>
                    </div>
        </div>
    	<div class="box-widgets timeline">
                <p class="head">
                    <span class="fl">Timeline</span>
                    <span class="fr f12">Total Calls 2500</span>
                </p>
                 <div class="content">
                 </div>
        </div>	
</div>
<!--//Manage Client Settings-->
<script type="text/javascript">
//call function to draw graph
getDetails('userCallLogsForChart','<?php echo $_REQUEST['clientId'];?>',1);

getDetails('getStatusDetails','<?php echo $_REQUEST['clientId'];?>',1);
getDetails('userCallLogsForTimeLine','<?php echo $_REQUEST['clientId'];?>',1);
</script>