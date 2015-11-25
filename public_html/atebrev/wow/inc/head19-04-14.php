<!--Header-->
<div id="header" class="clear"> 
		<a href="#menu-left"></a> 
        <a href="#menu-right" class="friends right"></a>
 		 <div class="pageName hidden-desktop">page name dynamic</div>
         
        <!-- Desktop Menus-->
        <div class="deskMenu  clear"> 
            <div id="topLeftAct">
           		 <h1> 
                        <span class="ic-12 ldiR fl">  </span>
                      	<p class="fl headingTitle">Manage Client </p>
                        <span class="ic-12 ldiL fl">  </span> 
                 </h1>
               
            </div>  
                      
            <!--Top Right-->
            <ul id="topRightAct" class="ln topmenu">
              <li class="bdrTrns pr setting">
                <div id="namewrap" class="clear cp" onclick="uiDrop(this,'#showSetting', 'true')">
                	<span class="semi fl"><?php echo $_SESSION['username']; ?></span>
                    <span class="ic-12 dowpdn fl mrL"></span>
				</div>
                <ul class="dropmenu boxsize ln" id="showSetting">
                  <li class="clear" onclick="window.location.href='#!setting.php|buymore.php'"> 
                    <span title="Setting">Settings</span> 
                  </li>
                  <li class="clear"> 
                      <span  title="Logout" onclick="var url = window.location.hash;window.location.href='/admin/logout.php?url='+url.substring(1)">Logout</span> 
                  </li>
                </ul>
              </li>
               <li>
             		<a onclick="showSmsDialog('sms');" tice:repeating="Send SMS" class="btn btn-mini btn-info mrR">Send SMS</a>
                    <a onclick="showSmsDialog('mail');" title="Send Mail" class="btn btn-mini btn-info">Send Mail</a>
             </li>
            </ul>
            <!--//Top Right--> 
            
        </div>
        <!-- //Desktop Menus--> 
<div id="mail-dialog" class="dn actM" title="Send Mail">
    <form id="bulkmailsend" class="">
        <div class="send-inner">
            <p class="mrB">To</p>			
            <div class="clear irow">
                <input id="checkUsersToMail" type="checkbox" name="sendUser" />
                <label for="checkUsersToMail"><strong>Users (1705)</strong></label> &nbsp;
                <input id="checkResellersToMail" type="checkbox" class="mrL1" name="sendReseller" />
                <label for="checkResellersToMail" class="danger">Contains 120 reseller ids</label>
        	</div>
            <p class="srchrow mrT1 mrB1"><strong>1825 IDs available to mail</strong></p>
            <p class="mrB">Subject</p>
            <input type="text" name="subject" id="subject" />
            <p class="mrB mrT2">Mail Body (Even HTML can be put)</p>
            <textarea name="message" id="message" cols="50" rows="4"></textarea>
            <div>
                <input class="mrT2 btn btn-medium btn-primary"  type="submit" name="sendMail" id="sendMail" value="Send Mail" title="Send Mail"/>
            </div>    
    	</div>
    </form>
</div>

    <div id="sms-dialog" class="dn actM" title="Send SMS">
        <form id="bulkSmsSend" class="">
            <div class="send-inner">
            	<p class="mrB">To</p>			
                <div class="clear irow">
                    <input id="checkUsersToSms" type="checkbox" name="sendUserSms"/>
                    <label for="checkUsersToSms"><strong>Users (1705)</strong></label>
                    <input id="checkResellersToSms" type="checkbox" class="mrL1" name="sendResellerSms"/>
                    <label for="checkResellersToSms" class="danger">Contains 120 reseller ids</label>
                    <input id="CheckChainToSms" type="checkbox" class="mrL1" name="sendAllChain"/>
                    <label for="CheckChainToSms" class="danger">Contains 120 all chain ids</label>
                </div>
                <p class="srchrow mrT1 mrB1"><strong>0 Numbers selected to SMS</strong></p>
                <p class="mrB">Sender ID</p>
                <input type="text" id ="senderId" name="senderId"/>
                <p class="mrB mrT2">Content (160 Character)</p>
                <textarea id="content" name="content" cols="50" rows="4"></textarea>
                <div>
               		<input class="mrT2 btn btn-medium btn-primary"  type="submit" name="sendSms" id="sendSms" value="Send SMS" title="Send SMS"/>
                </div>
            </div>
        </form>
    </div>
    
</div>
<!--//Header--> 