<?php
/**
 * @author Rahul Chordiya <rahul@hostnsoft.com>
 * @updated Sameer Rathod <sameer@hostnsoft.com>
 * @Design : Lovey Gorahpuriya <lovey@hostnsoft.com>, Aadheesh Rajput <aadheesh@hostnsoft.com>
 * @since 07 Aug 2013
 * @last update 5-9-2013
 * @details First Page of user login Contains phonebook and calling ways tabs.
 */
include_once 'config.php';
include_once(CLASS_DIR."/phonebook_class.php");
include_once ROOT_DIR.'/config/whiteLabelConfig.php';
include_once("googleContactSync.php");


if (!$funobj->login_validate() ) {
    $funobj->redirect("index.php");
}

#get all contact detail
$userId = $_SESSION['userid'];

$pbookobj = new phonebook_class();

extract($pbookobj->getAllContact($userId));
$currency = $funobj->getCurrencyName($_SESSION['currencyId']);
$country = $funobj->countryAllDetail();

# check user has confirm mobile no or not if yes then go to current page otherwise confirm mobile number .  

//print_r($allcontact);

//die(2);
//echo json_encode($allcontact);
?>
<script type="text/javascript">
    // get all the contact of user in json format 
    currency = '<?php echo $currency; ?>';
    allcontact = <?php echo json_encode($allcontact); ?>;
</script>

<link rel="stylesheet" type="text/css" href="css/user-panel.css" />
<style>
/*internal*/
#container{bottom:0; left:0; right:0; top:52px;}
.innerSection{left:15px; right:15px; top:15px;}
.commRightSec{left:23%; top:0}
#leftsec{background:#eee; border-right:1px solid #e7e7e7; right:77%;}
#leftsec input[type="text"]{margin:15px 0;}
#edit-contact-wrap-dialog{overflow:visible !important}

/*custom tabbing style (for this page only)*/
.ui-tabs-panel{margin-bottom:60px; max-width:654px;}
.ui-tabs-nav{font-size:15px; padding:15px 20px 25px !important; border-bottom:1px solid #eee;}
.ui-tabs-nav .ic-32{margin:9px 35px 8px}
.ui-tabs-nav span{display:block; margin:0 auto; width:75px;}
.ui-tabs-nav .ui-state-default{background:#708090; border:none !important; margin-right:25px; width:101px; height:95px; text-align:center; transition:0.3s all}
.ui-tabs-nav .ui-tabs-active{background:#68bada;box-shadow:0 0 4px rgba(0,0,0,.3)}
.ui-tabs-nav .ui-state-hover{background:#425262;}
.ui-tabs-nav .ui-state-default .ui-tabs-anchor{line-height:20px; font-size:14px; padding:0; color:#fff; width:
100%; height:100%; font-weight:400;}
/*custom buttonset style (for this page only)*/
.ui-buttonset .ui-button{border:none; text-align:left;}
.btnlbl .ui-button{padding:0; width:50%; background:none; color:#fff;}
.btnlbl label.ui-state-active{background:rgba(104,186,218,0.8); border:none !important; pointer-events:none;}
/*.ui-button-text-only .ui-button-text{padding:8px;}*/
.btnlbl label.ui-state-hover{background:#696d74;}
.leftFoot .ui-button-text{padding:8px; margin-left:12%; text-indent:5px;}

/*responsive for this page*/
@media only screen and (max-width: 1024px) {
#container{top:56px}
#gameBox{display:none !important;}
}
@media (min-width: 601px) and (max-width: 1024px) {
.cntList{top:120px;}
}
@media only screen and (max-width: 600px) {	
#leftsec, .commRightSec{left:0; right:0}
.ui-tabs-nav .ui-state-default{width:100%; height:auto; margin-bottom:10px; text-align:left;}
.ui-tabs-nav span{width:100%;}
.ui-tabs-nav .ui-state-default .ui-tabs-anchor{line-height:50px;}
.cntList{top:0;}
}
</style>
<!--<script type="text/javascript" src="/js/SIPml-api.js"></script>-->
<script type="text/javascript" src="/js/contact.js"></script> 

<div style="visibility: hidden;" class="glass-panel" id="divGlassPanel"></div>
<!--Left Section-->
<div id="leftsec" class="cntLeft slideLeft">
	<!--Column Inner Section-->
	<div class="innerSection mrB4">
	    <a class="btn btn-medium btn-blue" onclick="addContactDialog();" href="javascript:void(0);" id="addNewCnt">
        Add Contact
		</a>
	    <a class="btn btn-medium btn-inverse" onclick="importContact();" href="javascript:void(0);" id="importCnt">
        Import Contacts
		</a>
        <input class="linp" type="text" placeholder="Search Contact" id="searchContact"/>
	  <?php if (count($allcontact) < 1) { ?>
                <?php if($_SERVER['HTTP_HOST'] == "voice.phone91.com" || $_SERVER['HTTP_HOST'] == "phone91.com"){ ?>
			<div class="box-sync mrB2">
				  <h2 class="h2 fwN">Sync Contacts</h2>
				  <div class="mrT2"><a class="btn btn-medium btn-danger" href="<?php echo $loginUrl; ?>" title="Gmail">Gmail</a></div>
				<!--<div class="mrT2"> <a class="btn btn-medium btn-info" href="javascript:void(0);" title="Outlook">Outlook</a></div>-->
	  </div>
	  <?php } } ?>
	  <!--Add List-->
      <div class="scrl">
          <ul class="cntList ln">
            <?php  foreach ($allcontact as $cont) { 
                    if(strtolower($cont['name']) == 'me'){
                        $meCntCode = (isset($cont['code']))? $cont['code'] : '';
                        $meCntNumber = $cont['contactNo'];
                    }
                
                ?>
              <li class="clear" contactId="<?php echo (string) $cont['contact_id']; ?>" >
                  <div class="cntAct fixed">
                    <div class="edtsiWrap">
                         <a class="clear alC" onclick="showContactEdit(this);" contactId="<?php echo (string) $cont['contact_id']; ?>" href="javascript:void(0);" >
                              <span class="ic-24 edit"></span> 
                         </a>
                     </div>
                  </div>
                  
                  <div class="cntInfo slideAndBack" onclick="dest('<?php echo $cont['contactNo']; ?>',this)">
                        <div class="innerCol clear">
                              <h3 class="h3 ellp"><?php echo $cont['name']; ?></h3>
                              <div class="fpinfo"> <i class="ic-16 call"></i>
                                 <label><?php echo (isset($cont['code']))? $cont['code'] : '';?><?php echo $cont['contactNo']; ?></label>
                              </div>
                              
                              <!--access number-->
                              <div class="fpinfo">
                                 <?php if(isset($cont['accessNo'])) {?>
                                      <label><i class="ic-16 callA"></i> <?php echo $cont['accessNo'];?></label>
                         <?php }else { ?>
                              <label onclick="showContactEdit(this);" contactId="<?php echo (string) $cont['contact_id']; ?>" class="green tdu cp action"><i class="ic-16 callA mrR"></i>Assign</label>
                         <?php } ?>
                              </div>
                        </div>      
                    </div>
                    
                </li>
            <?php } ?>
          </ul>
      </div>
	  <!--//Add List-->
	  <!--Add Contact Dialouge Box-->
	  <div id="add-contact-dialog" class="dn" title="Add New Contact">
		<form id="contact_detail" action="javascript:;">
		  <div id="add-cnt-inner">
			<div class="clear">
				<div class="add-cnt-form">
			<div class="addCntrow clear bdrB addrows">
             			<div class="child">
                      		<input placeholder="Name" type="text" class="name" name="name[]" />
                        </div>
                        <div class="child">
                        	<!--country flags with code-->
                            <div class="countryWrap">
                                 <div class="selwrpa">
                                
                                     
                                      <div class="currencySelectDropdownAddcnt cntry" onclick="showCountryForAdd(this);">
                                	<span class="pickDown setCountry"></span>
                                	<span id="setFlagWebCall" flagId="IN" class='flag-24 IN setFlag defaultFlag_1'></span>
                                       </div>
								
                                <ul class="bgW" style="display:none;">
                                   <?php 
                                       
                                       foreach($country as $key =>$countryNames)
                                       {
                                           $ccode = explode('/',$countryNames['ISO']);
                                           echo "<li  countryCode='".$countryNames['CountryCode']."' countryName='".$countryNames['Country']."' countryFlags='".$ccode[0]."' onClick='SetValueAddCnt(this,\"WebCall\")'>
                                           <a class='clear' href='javascript:void(0)'>
                                           <span class='flag-24 ".$ccode[0]."'></span><span code='".$countryNames['CountryCode']."' class='fltxt'>".$countryNames['Country']."</span>
                                           </a>
                                           </li>";
                                           
                                       }
                                   ?>


                                </ul>
                                     
                                 
                                     </div>
                                     <div class="codeInput">
                                            <input name="code[]" type="text" id="code" class="min code defaultCode_1" value="91" readonly/>
                                            <input class="pr contact" name="contact[]" id='mobileNumber' type="text" placeholder="Contact No" />
                                     </div>

                                 </div>
                      	</div>
                        <div class="child accCont">

                            <a onclick="uiDrop(this,'#accessBox_1', 'true');renderCurrentRow(1)" class="accLink themeLink tdu" id="currentAccessNumber_1" href="javascript:;">Assign Access Number</a>
                            <div id="accParent_1" class="accParent"><!--dialog content will load here--></div>                            

                       </div>
                       <div class="child">    
                   	   		<i class="ic-24 close cp"></i>
                       </div>
                  </div>
                  
				  <div class="mrT1 addMoreRow">
                                 
                  		<a onclick="addMoreRow();" class="themeLink tdu mrL2" title="Add More">Add More</a>
                  </div>
				  <div class="mr2">
						<a class="btn btn-medium btn-blue" onclick="addcontact();" href="javascript:void(0);">Done</a>
				  </div>
				</div>
			</div>
		  </div>
		</form>
	  </div>
	  <!--//Add Contact Dialouge Box-->
      
      <!--Import contact dialog box-->
      <div id="import-dialog" class="dn" title="Import New Contact">
                              
				<div class="syncontact">
                  	<div class="pd3 bgLit alC">
                  		<a class="btn btn-medium btn-danger f15" href="<?php echo $loginUrl; ?>">Sync via Gmail</a>
                    </div>
                    <div class="horLine bgLit"></div>
                    <i class="ic-im sync"></i>
                    <div class="imBg clear">
                    	<i class="ic-im yes mrR mrL2"></i>
                        <i class="ic-im yes mrR"></i>
                        <i class="ic-im no mrR"></i>
                        <i class="ic-im yes"></i>
                    </div>
                </div>
      </div>
      <!--//Import contact dialog box-->
      
      <div id="after-import-contact" class="dn" title="Select contacts to import">
			<div class="clear">
				<div class="add-cnt-form">
				  <div class="addCntrow clear bdrB">
                        <div class="child">    
                   	   		<i class="ic-24 close cp"></i>
                        </div>
             			<div class="child">
                      		<input placeholder="Name" type="text" class="name" name="name[]" value="Ankit Patidar"/>
                        </div>
                        <div class="child">
                        	<!--country flags with code-->
                            <div class="countryWrap">
                                 <div class="selwrpa">
                                       
                                    	<div class="currencySelectDropdown" onclick="uiDrop(this,'#flaglist', 'true')">
                                            <span id="setCountry" class="pickDown"></span>
                                            <span id="setFlag" flagId="IN" class='flag-24 IN'></span>
                                        </div>
        
                                        <ul id="flaglist" style="display:none;">
                                          <?php 
                                               foreach($country as $key =>$value)
                                               {
                                                   //$ccode = explode('/',$countryNames['ISO']);
                                                   echo "<li countryCode='".$key."' countryName='".$key."' countryFlags='".$value."' onClick='SetValue(this)'>
                                                   <a class='clear' href='javascript:void(0)'>
                                                   <span class='flag-24 ".$value."'></span><span code='".$key."' class='fltxt'>".$key."</span>
                                                   </a>
                                                   </li>";
                                                   //$count++;
                                               }
                                           ?> 
            							</ul>
                                    
                                 </div>
                                 <div class="codeInput">
                                        <input name='code' type="text" id="code" onKeyUp="selectOption($(this).val())" class="min" value="91" disabled/>
                                        <input class="pr contact" name="contact[]" id='mobileNumber' type="text" placeholder="Contact No" />
                                 </div>
                             </div>
                      	</div>
                       <div class="child">    
                   	   		<input class="pr email" name="email" id='email' type="text" value="bossboss@boss.com" />
                       </div>
                  </div>
                  
				  <div class="mrT2">
						<a class="btn btn-medium btn-blue" onclick="" href="javascript:void(0);">Done</a>
				  </div>
				</div>
              </div>
          </div>
          <!---//end of after Import pop--->
 </div>
 
 <!--leftFoot-->
 <div class="leftFoot clear btnlbl">
                    <input type="radio" value="fil1" id="filter1" name="filter" onclick="getPhonbookContact('0');" checked />
                    <label for="filter1"><i class="ic-16 pb"></i>All Contacts</label>
                    <input type="radio" value="fil2" id="filter2" onclick="getPhonbookContact('1');" name="filter" />
                    <label for="filter2"><i class="ic-16 callAW"></i>Access numbers</label>
                </div>
 <!--//Column Inner Section-->
</div>
<!--//Left Section-->

<!--Right Section-->
<div id="tabs" class="slideRight commRightSec dn">
	
    <div class="clear mr2">
		<a href="javascript:dynamicPageName('Contacts');" class="fl back btn iconBtn btn-blue hidden-desktop" title="Back"><i class="ic-16 callW mrR"></i>Back to Contacts</a>
    </div>
    <!--heading-->
    
    <h3 class="mrT1 mrL2 ligt">Make calls instantly via -</h3>
    	
    <!--tabbing-->
    <ul>	
        <li><a href="#tabs-1"><i class="ic-32 an"></i><span>Access Numbers</span></a></li>
        <li><a href="#tabs-2"><i class="ic-32 ma"></i><span>Mobile Apps</span></a></li>
        <li onclick="initilize();"><a href="#tabs-3"><i class="ic-32 oc"></i><span>Web Calling</span></a></li>
        <li onclick="recentCall();"><a href="#tabs-4"><i class="ic-32 tc"></i><span>Two-way Calling</span></a></li>
        <!--<li><a href="#tabs-5"><i class="ic-32 vc"></i>Video<br> Calling</a></li>-->
    </ul>
        
	<!--Tabs Section-->
    <!--tabs1-->
    <div>
        <div id="tabs-1" class="tabs">
            <div class="clear bdrB pdB2 pdT1">
            	<div class="accErr pd1 bdr clear mrB2">
                	<span class="ic-32 notif fl mrR1"></span>
                    <p class="f15 ligt mrT"> Sorry, access numbers are not available in your country. Sorry you have already used the available access numbers for your other contacts.</p>
                </div>
            	<div class="stepsGfx s5"></div>
        		<div id="accessGen" class="clear">
                	<h3 class="ligt mrT1 mrB2">Find your nearest access number</h3>

                    <select id="accessCountry_" onChange="getStatesByPrefix('');" name="country">
                    	

<!--                    <select class="mrR1 mrB1" id="accessCountry_" onChange="getStatesByPrefix('');" name="country">-->

                    </select>
                    <select id="accessState_" onChange="getAccessNumberByState('');" name="state">
                    
                    </select>
                </div>
                <div id="accessNumber" class="mrT1">
                	<p class="pd1 f15">Available access numbers</p>
                </div>
            </div>
            <div class="clear mrT2">
                <span class="ic-32 notif fl mrB"></span> 
                <h3 class="ligt mrT mrL1 fl">How it works?</h3>
            </div>
            <!--content div-->
            <div class="f14 ligt ddLit">
                <p class="mrT mrB2">An access number lets you <b>call without internet</b> at low cost. To make calls, you can dial via PIN or you can use PIN less dialing. </p>
                
                <p class="db mrB1"><b class="grayColor">Pinless dialing -</b> Save your nearest local access number in your Phone91 contact book to make calls without entering PIN.</p>
                
                <p class="db mrT1 mrB"><b class="grayColor">Dialing via PIN -</b> If you are a registered Phone91 user and you don't have your 4 digit PIN yet, SMS 'PIN' to your local access number and we will message you back your 4 digit PIN.</p>
                <ul>
                    <li>1. Dial your nearest local access number.</li>
                    <li>2. Enter your 4 digit PIN.</li>
                    <li>3. Enter the destination number with Country code.</li>
                </ul>
            </div>
        </div>
    </div>
    <!--//tabs1-->
    
    <!--tabs2-->
    <div>
        <div id="tabs-2" class="tabs">
        	<!--download links div for phone91-->
        	<div id="p91" class="dn">
                <div class="clear bdrB pdB1">
                    <h2 class="ligt mrB1">Download</h2>
                    <a href="https://itunes.apple.com/in/app/phone91/id793737582" target="_blank" class="dwnBtns ip mrR2 mrB1"></a>
                    <a href="https://play.google.com/store/apps/details?id=com.phone91.android&hl=en" target="_blank" class="dwnBtns gp"></a>
                </div>
                <div class="mrT2">
                    <div class="clear">
                        <span class="ic-32 notif fl mrB"></span> 
                        <h3 class="ligt mrT mrL1 fl">Start calling from Mobile Apps</h3>
                    </div>
                    <p class="f14 ligt ddLit mrT">Download Phone91 mobile application from Itunes or Google play store to <b>make calls directly from your mobile.</b><br> 
    Mobile application comes handy when you don't want to use many options and make instant calls. </p>
                </div>
            </div>
            
            <!--download links div for voip91-->
            <div id="v91">
                <div class="clear bdrB pdB1">
                    <h2 class="ligt mrB1">Download Zemplus dialer</h2>
                    <a href="https://itunes.apple.com/in/app/zem-dialer/id651404015?mt=8" target="_blank" class="dwnBtns ip mrR2 mrB1"></a>
                    <a href="https://play.google.com/store/apps/details?id=com.zemdialer" target="_blank" class="dwnBtns gp"></a>
                </div>
                <div class="mrT2">
                    <div class="clear">
                        <span class="ic-32 notif fl mrB"></span> 
                        <h3 class="ligt mrT mrL1 fl">Start calling from Dialers</h3>
                    </div>
                    <p class="f14 ligt ddLit mrT">Download Zemplus dialer from Itunes or Google play store to <b>make calls directly from your mobile.</b><br> 
    Mobile dialers come handy when you want to make instant calls.</p>
                </div>
            </div>
        </div>
    </div>
    <!--//tabs2-->
    
    <!--tabs3-->
    <div id="tab3">
        <div id="tabs-3" class="tabs">
            <div class="clear dn callCont">
            	<h4 id="wHd" class="f14 ligt mrB1">Enter Destination Number</h4>

                <!--<label id="txtCallStatus" align="center" ></label>-->
                <div class="wcBox twBox pr mrB2">
                	<!--contact field with flags-->
                    <div class="countryWrap">
                         <div class="selwrpa">
                               
                                <div class="currencySelectDropdownWebCall cntry" onclick="uiDrop(this,'#flaglistWebCall', 'true')">
                                	<span class="pickDown setCountry"></span>
                                	<span id="setFlagWebCall" flagId="IN" class='flag-24 IN setFlag'></span>
                                </div>
								
                                <ul id="flaglistWebCall" class="bgW" style="display:none;">
                                   <?php 
                                       
                                       foreach($country as $key =>$countryNames)
                                       {
                                           $ccode = explode('/',$countryNames['ISO']);
                                           echo "<li  countryCode='".$countryNames['CountryCode']."' countryName='".$countryNames['Country']."' countryFlags='".$ccode[0]."' onClick='SetValue(this,\"WebCall\")'>
                                           <a class='clear' href='javascript:void(0)'>
                                           <span class='flag-24 ".$ccode[0]."'></span><span code='".$countryNames['CountryCode']."' class='fltxt'>".$countryNames['Country']."</span>
                                           </a>
                                           </li>";
                                          // $count++;
                                       }
                                   ?>


                                </ul>
                            
                             </div>
                        <div id="details" style="display:none">
                            <input type="hidden" id="txtDisplayName" value="<?php  echo $_SESSION['username']; ?>"/>
                            <input type="hidden" id="txtPrivateIdentity" value="<?php echo $_SESSION['username']; ?>"/>
                            <input type="hidden" id="txtPublicIdentity" value="sip:<?php echo $_SESSION['username']; ?>@sip.phone91.com"/>
                            <input type="hidden" id="txtPassword" value="<?php  echo $_SESSION['passwd']; ?>"/>
                            <input type="hidden" id="txtRealm" value="sip.phone91.com"/>
                        </div>
                            <div class="codeInput">
                                    <input name='code' type="text" id="codeWebCall" class="min " value="91" disabled/>
                                    <input class="pr contact" maxlength="18" type="text" id="dialPadInput" >
                                  
                             </div>
                     </div>
                     
                 <!--dialpad-->
                  <div class="dialPad clear compact">
                        <div class="dials">
                        <ol>
                            <li class="digits">1</li>
                            <li class="digits">2</li>
                            <li class="digits">3</li>
                            <li class="digits">4</li>
                            <li class="digits">5</li>
                            <li class="digits">6</li>
                            <li class="digits">7</li>
                            <li class="digits">8</li>
                            <li class="digits">9</li>
                            <li class="digits lit">*</li>
                            <li class="digits">0</li>
                            <li class="digits lit">#</li>
                            
                            <li class="digits dn">Clear</li>
                            <li class="back"><i class="ic-24 bs"></i></li>
                            <li class="digits pad-action dn">Call</li>
                        </ol>
                    </div>
                 </div>
                    <!---//dialpad--->
                </div>
                             
                <button type="button" id="btnCall" title="Call" name="call" onclick="callStart()" class="btn btn-blue semi mrB2 callStart"><i class="ic-16 callW mrR"></i>Call</button>
                <div class="fl alC callEnd">
                    <button type="button" id="btnHangUp" onclick="sipHangUp(this);callEnd()" class="btn btn-danger semi pdR1"><i class="ic-16 callW mrR"></i>Disconnect</button>
                    <div id="divCallOptions">
                        <input type="hidden" id="btnHoldResume"  class="btn btn-danger semi pdR1" value="Hold"/>
                    </div>
                    <div class="statCnt">
                    	<div class="statLin"></div>
                        <div class="statBox">
                            <span id="txtRegStatus" class="callStatus f14"></span>
                            <span id="txtCallStatus" align="center" ></span><span class="dots"></span>

                            <h3 class="time">
				<span id="sw_h">00</span>:<span id="sw_m">00</span>:<span id="sw_s">00</span>
			    </h3>

                        </div>   
                    </div>
                </div>
            </div>
            <div id="loader">
            <span></span><span></span><span></span><span></span><span></span>
            </div>
            <!--error in calling-->
            <div class="clear pdB2 dn callErr">
                <div class="sketch fl" style="opacity:.6"></div>
              	<div class="mr2 fl">
               		<h3 class="red font28 mrB">Whoops!</h3>
                	<span class="ddLit">We couldn't establish the connection because of an error.<br> Please try again after some time.</span>
              	</div>
            </div>
            <!--//error in calling-->
            
            <div class="bdrT pdT2 clear"> 
                <div class="clear">
                    <span class="ic-32 notif fl mrB"></span> 
                    <h3 class="ligt mrT mrL1 fl">How it works?</h3>
                </div>
                
                <div class="f14 ligt ddLit">
                    <p class="mrT mrB">For web calling, you are going to need:</p>
                    <ul class="toast-title">
                        <li>&rarr; Web Browser</li>
                        <li>&rarr; A headphone with mic.</li>
                    </ul>
                    <p class="f14 ligt ddLit mrT">Now enter the destination number and you are all set to make calls.</p>
                </div>
            </div>
        </div>
    </div>
    <!--//tabs3-->
     <!-- Audios -->
    <audio id="audio_remote" type="audio/wave" autoplay="autoplay" />
    <audio id="ringtone" loop type="audio/wave" src="sounds/ringtone.wav" />
    <audio id="ringbacktone" loop type="audio/wave" src="sounds/ringbacktone.wav" />
    <audio id="dtmfTone" type="audio/wave" src="sounds/dtmf.wav" />

    <!--tabs4-->
    <div>
        <div id="tabs-4" class="tabs">            
            <!--two way fields box-->
            <div id="twCallBox" class="clear callCont">
                <div class="twBox mrB2">
                    <h4 id="sHd" class="f14 ligt mrB1">Your Number</h4>
                    <div class="countryWrap">
                         <div class="selwrpa">
                               
                               <div class="currencySelectDropdownSource cntry" onclick="uiDrop(this,'#flaglistSource', 'true')">
                                <span class="pickDown setCountry"></span>
                                <span id="setFlagSource" flagId="IN" class='flag-24 IN defaultFlag_twoWay'></span>

                                </div>
                                <ul id="flaglistSource" class="bgW" style="display:none;">
                                   <?php 
                                       
                                       foreach($country as $key =>$countryNames)
                                       {

                                           $ccode = explode('/',$countryNames['ISO']);
                                           echo "<li  countryCode='".$countryNames['CountryCode']."' countryName='".$countryNames['Country']."' countryFlags='".$ccode[0]."' onClick='SetValue(this,\"Source\")'>
                                           <a class='clear' href='javascript:void(0)'>
                                           <span class='flag-24 ".$ccode[0]."'></span><span code='".$countryNames['CountryCode']."' class='fltxt'>".$countryNames['Country']."</span>
                                           </a>
                                           </li>";
                                           //$count++;

                                       }
                                   ?> 


                                </ul>
                            
                             </div>
                             <div class="codeInput">
				 <input name='code' type="text" id="codeSource" class="min defaultCode_twoWay" value="91" readonly/>
                                  <input class="pr contact fl" onblur="validateNumber(this,0)" value="<?php echo $meCntNumber; ?>" id="source" type="text"/>


                                  
                             </div>
                            
                    </div>
                    <div class="statCnt mrT4 callEnd">
                    	<div class="statLin"></div>
                        <div class="statBox">
                        	<p class="callStatus twoCallFrom f14">Connecting<span class="dots"></span></p>
                            <h3 class="time" id="timeFrom"></h3>
                        </div>   
                    </div>
                </div>
                <div class="twBox">
                    <h4 id="dHd" class="f14 ligt mrB1">Destination Number</h4>
                    <div class="countryWrap">
                         <div class="selwrpa">
                               
                                <div class="currencySelectDropdownDest cntry" onclick="uiDrop(this,'#flaglistDest', 'true')">
                                <span class="pickDown setCountry"></span>
                                <span id="setFlagDest" flagId="IN" class='flag-24 IN defaultFlag_twoWay'></span>

                                </div>

                                <ul id="flaglistDest" class="bgW tempcls" style="display: none;">
                                   <?php 

                                       
                                       foreach($country as $key =>$countryNames)
                                       {
                                           $ccode = explode('/',$countryNames['ISO']);
                                           echo "<li  countryCode='".$countryNames['CountryCode']."' countryName='".$countryNames['Country']."' countryFlags='".$ccode[0]."' onClick='SetValue(this,\"Dest\")'>
                                           <a class='clear' href='javascript:void(0)'>
                                           <span class='flag-24 ".$ccode[0]."'></span><span code='".$countryNames['CountryCode']."' class='fltxt'>".$countryNames['Country']."</span>
                                           </a>
                                           </li>";
                                           //$count++;
                                       }
                                   ?> 


                                </ul>
                            
                             </div>
                             <div class="codeInput">
                                    <input name='code' type="text" id="codeDest"  class="min defaultCode_twoWay " value="91" readonly/>
                                    <input class="pr contact fl" id="dest" onblur="validateNumber(this,1)" onkeyup="callcostKeyup();" type="text" />
                             </div>
                    </div>
                    <div class="statCnt mrT4 callEnd">
                    	<div class="statLin  toDestination"></div>
                        <div class="statBox  toDestination">
                        	<p class="callStatus twoCallTo f14">Connecting<span class="dots"></span></p>
                            <h3 class="time" id="timeTo"></h3>
                        </div>   
                    </div>
                </div>
                
                <button type="button" id="call" name="call" onclick="clicktocall();" class="btn btn-blue semi callStart"><i class="ic-16 callW mrR"></i>Call</button>
                <button type="button" onclick="callEnd()" class="btn btn-danger semi callEnd"><i class="ic-16 callW mrR"></i>Disconnect</button>
            	<label id="callrateDtl" class="mrR2 db fr"></label>
            </div>
            
            <div id="response" class="mrB1 tTc"><!--err massage--></div>
            
            <!--recent calls-->
            <div id="recentCallsWrp" class="mrT2 pdT2 bdrT "></div>
              
            <div class="mrT2 clear">
                <div class="clear">
                    <span class="ic-32 notif fl mrB"></span>
                    <h3 class="ligt mrT mrL1 fl">How it works?</h3>
                </div>
                <!--content-->
                <div class="f14 ligt ddLit">
                    <p class="mrT mrB1">As soon as you enter your number and the destination number, your phone will ring first. Please wait for a few seconds, because then the destination number will ring, and you'll be connected.</p>
    				<p><b class="red">Note:</b> Charges will be applicable for two calls - one for the destination number and one for your number.</p>
				</div>
            </div>
        </div>
    </div>
    <!--//Tabs4-->
    
    <!--Tabs5
    <div>
        <div id="tabs-5" class="tabs">
        Video Calling content will come here!
        </div>
    </div>-->
    
    <!--Game-->
    
    <div id="gameBox">
    	<!--<div class="tpHd pa"><i class="fl ic-24 gm mrR"></i>Beat the last highest score and <span class="red">make this call free!</span></div>-->
        <a href="javascript:void(0);" id="gameOpen" class="btn btn-medium btn-danger h3 fr mrT2" style="display:none">Play the game</a>
    	<div id="canvasCnt">
        	<i class="ic-24 closeR" id="gameClose"></i>
            <canvas id="canvas">
                Upgrade your browser to play a game between this call!
            </canvas>
    
            <div id="mainMenu">
                <h1>doodle jump</h1>
                <p class="info">
                    use
                    <span class="key left">←</span>
                    <span class="key right">→</span>
                    to move and space to (re) start...
                </p>
                <a class="button" href="javascript:init()">Play</a>
            </div>
            
            <div id="gameOverMenu">
                <h1>game over!</h1>
                <h3 id="go_score">you scored 0 points</h3>
                <a class="button" href="javascript:reset()">Restart</a>
                <a id="tweetBtn" target="_blank" class="button tweet" href="#">Tweet score</a>
                <a id="fbBtn" target="_blank" class="button fb" href="#">Post on FB</a>
            </div>
            
            <!-- Preloading image ;) -->
            <img id="sprite" src="images/2WEhF.png"/>
    
            <div id="scoreBoard">
                <p id="score">0</p>
            </div>
		</div>
	</div>
    <!--//Game-->
    
    <!--right payment footer-->
<!--    <div class="rightFoot">
    	<p class="fl ligt mrR1 lh22">Payment accepeted via -</p><div class="payVia b"></div>
    </div>-->
</div><!--//right section ends-->



<div id="edit-contact-wrap-dialog" class="dn"></div>
<script type="text/javascript" >
//    basket.require(
//            { url: '/js/contact.js' }
//    );

</script> 
<script type="text/javascript"> 

// basket.require(
//            { url: '/js/contact.js' }
//    ).then(function(){
// 
// 
//    });
 //global parameters   
var addMoreRowCounter = 1;   
var addMoreRowCountArr = [1];
 
var li = $(".tempcls , .tempcls li ");

$(document).keypress(function(event) {
    

     li
      .filter(function() {
         
         
         return console.log(String.fromCharCode(event.which).toLowerCase());
         
      });
});
 
 
 
$('.ic-dia').tipTip({delay:0});



$('.accLink').click(function(){
	if($(this).hasClass('active'))
	{
	    $(this).addClass('active');
	}
	else
	    $(this).removeClass('active');
	});


function openDialers(dialer,ts){
	var title = $(ts).text();
	$('.dia-comm').hide();
	$('.'+dialer).show();
	$("#dialersDialog").dialog({modal: true, resizable: false, 'title':title,
		close: function( event, ui ){
			$("#dialersDialog").dialog( "destroy" );
		}
	});
}




function SetValue(ths,idpost)
{
    
    var countryName = $(ths).attr('countryName');
    var countryCode = $(ths).attr('countryCode');
    var countryFlags = $(ths).attr('countryFlags');

    $('.setCountry').html('');
    
    $('#setFlag'+idpost).removeClass($('#setFlag'+idpost).attr('flagId')); //flagId

    $('#setFlag'+idpost).addClass(countryFlags);
    $('#setFlag'+idpost).attr('flagId' , countryFlags);
    $("#code"+idpost).val(countryCode.replace(/ /g, ''));
    $('#flaglist'+idpost).hide();
}

function dest(val,ts){
	$('#dest').val(val);
	$('.cntList li').removeClass('active');
	$(ts).parent('li').addClass('active');
}
dynamicPageName('Contacts')
slideAndBack('.slideLeft','.slideRight');
$('.slideAndBack').click(function(){
	dynamicPageName('Contact Name');
})

    function addContactDialog()
    {
	 var originaldata =''; 
            $("#add-contact-dialog").dialog({modal: true, resizable: false, width: 720, height: 600, 'title':'Add New Contact',
                open : function(event,ui){
                    originaldata = $("#add-contact-dialog").html();
                },close : function(event, ui) {
                     dedicatedAN = [];
                     hashObj = [];
                     $("#add-contact-dialog").html(originaldata);
                }});
			
			var str = addContactDD(1);
			//$('.accParent').html('');
			$('#accParent_1').append(str);
			if(countryByIp.countryCode != '' && countryByIp.countryCode != undefined)
			{
			    setDefaultFlageByIp(1);
			}
			getCountries('1');
			renderSelect();
    }

    function importContact()
    {
	 $("#import-dialog").dialog({modal: true, resizable: false, width: 400, height: 320, 'title':'Import Contacts'});
    }

    $(function() {
       	
		
		$('#syncGmail').click(function() {
            $("#after-import-contact").dialog({modal: true, resizable: false, width: 860, height: 400, 'title':'Select contacts to import'});
        })

        $('#searchContact').quicksearch('.cntList li');
    });



                                        
            function recallApi(Id)
            {
                // function is called when user click on recall button from the recent call list 
                // it will call the click to call api
                $("#source").val($('#callFrom'+Id).val());
                $("#dest").val($('#callTo'+Id).val());
                console.log($('#callFrom'+Id).val());
                console.log($('#callTo'+Id).val());
                callcostKeyup();
                clicktocall(1);
            }
           

	    

          
	    
	   

	    /**
	    * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
	    * @since 6/8/2014
	    * @param int counter
	    * @uses function to render accessNumber html and hashObj
	    * @returns {undefined}	     */
	    function renderAccessNumberHtml(counter)
	    {
		console.log(addMoreRowCounter);
		for(i = 1; i <= addMoreRowCounter ; i++)
		{
		    if(i != counter)
		    {
			
			if($('#displayHashDiv_'+counter).val() ==0)
			{
			    if($.inArray($('#accessNumber_'+counter).val(),dedicatedAN) == -1)
			    {
				dedicatedAN.push($('#accessNumber_'+counter).val());
				
			    }
			}
			else
			{
			    $.each(hashObj,function(key,value){
			    
			    
			    if($('#accessNumber_'+i).val() == value.accessNumber)
			    {
				
				if($('#hashExt_'+counter).val() == $('#hashExt_'+i).val())
				{
				    $('#hashExt_'+i).val(0);
				    
				}
				
			    }
			});
			    
			}
			
		    }
		}
		
		renderSelect();
		 
		
	    }

	   
	    
	    
            $(document).ready(function(){
		
		
               
                getCountries('');
             $( "#tabs" ).tabs();
			 $( ".leftFoot" ).buttonset();
            })

var widF = $('#leftsec').outerWidth(true)
$('.leftFoot').css({width: widF});

function showAss(ths){
    
    $(ths).next().toggleClass('dn')}

//initialize custom scroll
$(".scrl").slimscroll()
//initialize height of slimScrollDiv
var _slH = $('.slimScrollDiv').height()-100;
$(".slimScrollDiv").css("height",_slH);

</script>
<script src="/js/game.js" type="text/javascript"></script>
<!--<script type="text/javascript">
    basket.require({url: '/js/game.js'});
</script>-->
<!--<script src="/js/webphone.js" type="text/javascript"></script>-->

<!--<script type="text/javascript" src="/js/SIPml-api.js"></script>-->
<?php // if($_SESSION['username'] == 'sameer293'){ ?>
<script>
    
    
    function recentCall(){
    setDefaultFlageByIp('twoWay');
    //console.log(allcontact);
    $.ajax({
        //function fetches the recent call list of the user 
        // return type is json which is iterated 3 times to get only three recent calls 
        url:"controller/userCallLog.php?call=recentCall",
        type:"POST",
        dataType:"JSON",
        success:function(msg)
        {
            if(msg != null)
            {
            var str ='<h4 id="recentCallHead" class="f15 pdB1">Recent Calls</h4>';
            var i = 0; // loop counter
			
            //.each api to iterate thought the response json
            $.each(msg,function(key,value){
                if(value.record != "")
                {
                var callerIdName ="Unknown"; // Name of the caller 
                var calledName = "Unknown";  // Name of the contact to which the call is done 
                
                // allcontact is a global variable which consist of all the contact numbers of user in json format 
                // this is use to get the name to which the source and destination number is assoiciated if the number is 
                // not found in the user contacts then by default it will show the name unknown to the user
               
                    $.each(allcontact,function(k,v)
                    {
                        if(v.contactNo == value.record.caller_id)
                            callerIdName = v.name;
                        else if(v.contactNo == value.record.called_number)
                            calledName = v.name;
                    });
                    
                    $str += '<ul id="recentCallUl" class="recCallList ln mrB2">\
                    <li class="clear bdr">\
                    <div class="col-25">\
                    <h3 class="ellp">'+callerIdName+'</h3>\
                    <div class="clear fpinfo">\
                    <i class="ic-16 call"></i>\
                    <label>'+value.record.caller_id+'</label>\
                    <input type="hidden" value="'+value.record.caller_id+'" id="callFrom'+value.record.uniqueId+'" name="callFrom">\
                    </div>\
                    </div>\
                    <div class="col-25">\
                    <h3 class="ellp">'+calledName+'</h3>\
                        <div class="clear fpinfo">\
                        <i class="ic-16 call"></i>\
                        <label>'+value.record.called_number+'</label>\
                        <input type="hidden" name="callTo" id ="callTo'+value.record.uniqueId+'" value="'+value.record.called_number+'"/>\
                        </div>\
                        </div>\
                        <div class="col-25">\
                        <h3 class="ellp">Call Rate</h3>\
                        <label>'+value.balance+' '+currency+'/Min</label>\
                        <i class="ic-16 arR-g"></i>\
                        </div>\
                        <div>\
                        <div class="recall">\
                        <a href="javascript:void(0);" class="btn btn-medium btn-blue mrT">\
                        <div class="tryc">\
                        <span class="ic-16 callW mrR"></span>\
                        <span onclick="recallApi(\''+value.record.uniqueId+'\')">Recall</span>\
                        </div>\
                        </a>\
                        </div>\
                        </div></li></ul>';
      		//check to iterate only three times 
                    if(i == 2)
                    {
                        return false;
                    }
                        i++;
                    }
                })
		
                $('#recentCallsWrp').html(str);
            }
		
        }
                                          
    });
    
    }
    
    
    function initilize(){
        $("#loader").show();
        basket
    .require({ url: '/js/SIPml-api.js' })
    .then(function () {
//        setTimeout(function(){
        basket.require({ url: '/js/webphone.js' }).then(function(){
        console.log("called");
         sipRegister();
         $("#loader").hide();
    });    
//        },1000)
    
    });
    

//    $.getScript("/js/SIPml-api.js",function(){
//        console.log(1,SIPml);
//      $.getScript("/js/webphone.js",function(){
////        
//        sipRegister();
//        
//      }); 
//    })
    }
//  initilize();  
</script>
<?php // } ?>
<!--<script src="/js/webphone.js" type="text/javascript"></script>-->

