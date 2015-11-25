<!--Quick Serach-->
<div class="quicKseachsec  subPageSrch">
<!--    <div class="quickSearch">
             <span class="ic-16 search icon" title="Search"></span> 
             <input type="text" id="search" placeholder="Search Account Manager" />
    </div>-->
</div>
<!--//Quick Serach-->

<!--Manage Website-->
   <div class="inner">
        <!--Left Section-->
		<div class="slideLeft contentHolder" id="leftsec">
            <ul class="mngClntList acmanager">
                        <li onclick="window.location.href='#!account-manager.php|edit-funds.php'" title="Edit funds">
                        	Edit funds
                      </li>
                      
                      <li onclick="window.location.href='#!account-manager.php|call-limit.php'" title="Call limit">
                        	Call limit
                      </li>
                      
                      <li onclick="window.location.href='#!account-manager.php|bandwidth-limit.php'" title="Bandwidth limit">
                        	Bandwidth limit
                      </li>
                      
                      <li onclick="window.location.href='#!account-manager.php|changeTeriff.php'" title="change Tariff">
                        	Change Tariff
                      </li>
                      
                      <li onclick="window.location.href='#!account-manager.php|changeAccManager.php'" title="change Account Manager">
                        	Change Account Manager
                      </li>
                     <li onclick="window.location.href='#!account-manager.php|changeUserStatus.php'" title="change user status">
                        	Change User Status
                      </li>
                      <li onclick="window.location.href='#!account-manager.php|deletedUser.php'" title="Deleted user">
                        	Delete User
                      </li>
                      <li onclick="window.location.href='#!account-manager.php|changeUserStatus.php?action=5'" title="Change User to Reseller">
                        	Change User to Reseller
                      </li>
<!--                      <li onclick="window.location.href='#!account-manager.php|ips-add.php'">
                        	IPs add
                      </li>
                      
                      <li onclick="window.location.href='#!account-manager.php|sip-permission.php'" title="SIP permission">
                        	SIP permission
                      </li>
                      
                      <li onclick="window.location.href='#!account-manager.php|route-change.php'" title="Route Change">
                        	Route Change
                      </li>
                      
                       <li onclick="window.location.href='#!account-manager.php|route-add.php'" title="Route add">
                        	Route add
                      </li>-->
              </ul>
		</div>
		<!--//Left Section-->    
    
        <!--Right  Section-->   
        <div class= "slideRight" id="rightsec">
        </div>
        <!--//Right Section-->
   </div>
<!--//Manage Website-->
<script type="text/javascript">
  	jQuery(document).ready(function ($) {
		"use strict";
		$('#leftsec, .scrolll ').perfectScrollbar();
	  });
	  current();
	current();
</script>

