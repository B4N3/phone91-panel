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
                      	<p class="fl">Manage Client </p>
                        <span class="ic-12 ldiL fl">  </span> 
                 </h1>
               
            </div>  
                      
            <!--Top Right-->
            <ul id="topRightAct" class="ln topmenu">
              <li class="bdrTrns pr setting">
                <div class="clear cp" onclick="uiDrop(this,'#showSetting', 'true')"> 
                      <p id="namewrap" class="fl">
                      		<span class="semi fl">Lovey Gorakhpyriya</span>
                            <span class="ic-12 dowpdn fl mrL"></span>
                     </p> 
               </div>
                <ul class="dropmenu boxsize ln" id="showSetting">
                  <li class="clear" onclick="window.location.href='#!setting.php|buymore.php'"> 
                    <span title="Setting">Settings</span> 
                  </li>
                  <li class="clear"> 
                      <span  title="Logout" onclick="var url = window.location.hash;window.location.href='/logout.php?url='+url.substring(1)">Logout</span> 
                  </li>
                </ul>
              </li>
               <li>
             		<a href="javascript:void(0)" tice:repeating="Send SMS" class="btn btn-mini btn-info mrR">Send SMS</a>
                    <a href="javascript:void(0)" title="Send Mail" class="btn btn-mini btn-info">Send Mail</a>
             </li>
            </ul>
            <!--//Top Right--> 
            
        </div>
        <!-- //Desktop Menus--> 
</div>
<!--//Header--> 