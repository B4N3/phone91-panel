<?php
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @since 02-Oct-2013
 * @package Phone91
 * @details add website page and edit website page  
 */
include_once('config.php');
include_once CLASS_DIR.'websiteClass.php';
#create object of reseller_class
$websiteObj = new websiteClass();

if(isset($_REQUEST['id'])){
    
    $genData = $websiteObj->getGeneralData($_REQUEST['id'],$_SESSION['id']);
    $generalData = json_decode($genData, true);  
       
    $aboutData = $websiteObj->getAboutData($_REQUEST['id'],$_SESSION['id']);
    $aboutPageData = json_decode($aboutData, true); 
    
    $contactData = $websiteObj->getContactPageData($_REQUEST['id'],$_SESSION['id']);
    $contactPageData = json_decode($contactData, true); 
    
    $pricingData = $websiteObj->getPricingPageData($_REQUEST['id'],$_SESSION['id']);
    $pricingPageData = json_decode($pricingData, true); 
    
    #get plan name and id 
    include_once CLASS_DIR.'plan_class.php';
    $planObj = new plan_class();
    $planDetail=$planObj->getPlans(null, $_SESSION['userid']);
    $planData= json_decode($planDetail);
    
}
?>
<script src="http://malsup.github.com/jquery.form.js"></script>

<!--Add Manage Website-->
<a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<script type="text/javascript" src="js/website.js"></script> 

<?php if(!isset($_REQUEST['id'])){?>
<form id ="addWebsite" action="">
<div class="addWebForm">
        <div class="reSellerhead">Add Website</div>
        <div class="fields">
            <label>Company Name</label>
        	 <input type="text" id="companyName" name="companyName" />
         </div>
        <div class="fields">
        	<label>Domain name (eg.example.com)</label>
      	    <input type="text" id="domainName" name="domainName" />
        </div>
         <div class="fields">
        	<label>Theme</label>
      	   <input type="text" id="theme" name="theme" />
        </div>
        <div class="fields">
        	<label>Choose Language</label>
      	   <select class="bdr col3" name="language" id="language">
                <option value="English">English</option>
                <option value="French">French</option>
                <option value="German">German</option>
   			</select>
        </div>
    <input class="mrT1 btn btn-medium btn-primary"  value="Add Website" type="submit" title="Add Website" /></div>
<!--<a href="javascript:void(0)" class="mrT2 btn btn-medium btn-primary alC">Add Website</a>-->
</form>    
<?php }else{?>      
<!--Add Wesbite Content-->
<div  id="web-data-form">
	  <div class="reSellerhead">Add Website</div>
  	  <p class="subline"><strong>You can upload data page wise by default you get 4 pages in your website</strong></p>

	<!--Tabs Wrapper-->
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">General Data</a></li>
            <li><a href="#tabs-2">Home</a></li>
            <li><a href="#tabs-3">About</a></li>
            <li><a href="#tabs-4">Pricing</a></li>
            <li><a href="#tabs-5">Contact</a></li>
        </ul>
        
        <!--1st Tabs-->
        <div id="tabs-1" class="tabs">
            <form id ="generalDataForm" action="" enctype="multipart/form-data">
            <h4>General Data</h4>
            <p>Logo (file should be in .jpg, .png, .gif - formats and max. size 200kb)</p>
            <div class="fileWrap"><input type="file" name="logoFile"/></div>
            
            <input type="hidden" name ="logoimg" id="logoimg" value ="<?php echo isset($generalData['logoimage'])? $generalData['logoimage'] : "default.php" ; ?>"/>
            <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
            
            <div class="contactLeft">
                <h4>Social Links</h4>
                <div class="inprow"><label>Facebook url#</label><input type="text" name="facebook" id="facebook" value="<?php echo $generalData['socialLinks']['facebook'];?>" /></div>
                <div class="inprow"><label>Twitter url#</label><input type="text" name="twitter" id="twitter" value="<?php echo $generalData['socialLinks']['twitter'];?>" /></div>
                <div class="inprow"><label>linkedin url#</label><input type="text" name="linkedin" id="linkedin" value="<?php echo $generalData['socialLinks']['linkedin'];?>" /></div>
                <div class="inprow"><label>Gplus url#</label><input type="text" name="gplus" id="gplus"  value="<?php echo $generalData['socialLinks']['gplus'];?>" /></div>
            </div>
            
            <div class="contactRight">
                <h4>Contact Details</h4>
                <div class="inprow"><label>Address</label><input type="text" name="address" id="address"  value="<?php echo $generalData['contact']['address'];?>" /></div>
                <div class="inprow"><label>Phone No.</label><input type="text" name="phoneNo" id="phoneNo"  value="<?php echo $generalData['contact']['phoneNo'];?>" /></div>
                <div class="inprow"><label>Email ID</label><input type="text" name="emailId" id="emailId"  value="<?php echo $generalData['contact']['email'];?>"/></div>
           </div>
            <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="General Data" />
            </form>
        </div>
        <!--//1st Tabs-->
        
        <!--2nd Tabs-->
        <div id="tabs-2" class="tabs">
             <form id ="homeDataForm" action="" enctype="multipart/form-data">
                 
                 
                 
        	<div class="rowsTabs">
                <p>Meta Tags keyword</p>
                <textarea class="mtag" id="homeKeyword" name ="homeKeyword"><?php echo $generalData['homeKeyword']; ?></textarea>
                <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
                <input type="hidden" name ="welImage" id="welImage" value ="<?php echo isset($generalData['welcomeImage'])? $generalData['welcomeImage'] : "welcomeDefault.gif" ; ?>"/>
            
            </div>
            <div class="rowsTabs">
    				<p>Meta Tags Description</p>
    				<textarea class="mtag" id="homeDescription" name ="homeDescription" ><?php echo $generalData['homeDescription']; ?></textarea>
             </div>
             <div class="rowsTabs">
          		  <p>Welcome Image (file should be in .jpg, .png, .gif - formats and max. size 200kb and resoulution is 100X100 in pixels)</p>
                      <div class="fileWrap"><input type="file" name="welcomeImg"/></div>
             </div>
            <div class="rowsTabs">
                    <p>Welcome Content</p>
                   <textarea id="welcomeContent" name="welcomeContent"><?php echo $generalData['welcomeContent']; ?></textarea>
            </div>
            <h4 class="mrT3">Feautures List (you can select max. 3)</h4>
           <div class="features">
                    <div class="inprow"><label>Feature 1</label><input type="checkbox" /></div>
                    <div class="inprow"><label>Feature 1</label><input type="checkbox" /></div>
                    <div class="inprow"><label>Feature 1</label><input type="checkbox" /></div>
                    <div class="inprow"><label>Feature 1</label><input type="checkbox" /></div>
                    <div class="inprow"><label>Feature 1</label><input type="checkbox" /></div>
                    <div class="inprow"><label>Feature 1</label><input type="checkbox" /></div>
                    <div class="inprow"><label>Feature 1</label><input type="checkbox" /></div>
                    <div class="inprow"><label>Feature 1</label><input type="checkbox" /></div>
                    <div class="inprow"><label>Feature 1</label><input type="checkbox" /></div>
           </div>
            
            <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="Home Data" />
            
            
             </form>
        </div>
        <!--//2nd Tabs-->
        
        <!--3rd Tabs-->
        <div id="tabs-3" class="tabs">
            <form id ="aboutDataForm" action="" enctype="multipart/form-data">
        	<div class="rowsTabs">
                    <p>Meta Tags keyword</p>
                    <textarea class="mtag" id="aboutKeyword" name="aboutKeyword"><?php echo $aboutPageData['aboutKeyword']; ?></textarea>
                     <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
             </div>
            <div class="rowsTabs">
                    <p>Meta Tags Description</p>
                    <textarea class="mtag" id="aboutDescription" name="aboutDescription"><?php echo $aboutPageData['aboutDescription']; ?></textarea>
            </div>

            <h4 class="mrT3">Banner Setting</h4>
            <div class="inprow"><label>Visible Banner</label><input type="checkbox" id="bannerStatus" name="bannerStatus" <?php echo ($aboutPageData['bannerStatus'] == 1)? "checked=checked" : "";?>/></div>
            <div class="banner-data">
            	<div class="inprow"><label>Banner heading</label><input type="text" id="bannerHeading" name="bannerHeading" value="<?php echo $aboutPageData['bannerDetail']['bannerHeading']; ?>" /></div>
                <div class="inprow"><label>Banner Sub-heading</label><input type="text" id="bannerSubHead" name="bannerSubHead" value="<?php echo $aboutPageData['bannerDetail']['bannerSubHead']; ?>" /></div>
                <div class="inprow"><label>Banner Button Text</label><input type="text" id="buttonText" name ="buttonText" value="<?php echo $aboutPageData['bannerDetail']['buttonText']; ?>" /></div>
                <div class="inprow"><label>Banner Button Link</label><input type="text" id="buttonLink" name="buttonLink"  value="<?php echo $aboutPageData['bannerDetail']['buttonLink']; ?>"/></div>
            </div>
             <div class="rowsTabs">
                    <p>Who you are</p>
                    <textarea id="whoUR" name="whoUR"><?php echo $aboutPageData['whoUR']; ?></textarea>
            </div>
            <div class="rowsTabs">
    				<p>Your Vision</p>
    				<textarea id="vision" name="vision"><?php echo $aboutPageData['vision']; ?></textarea>
             </div>
            <div class="rowsTabs">
                <p>Your Mission</p>
                <textarea id="mission" name="mission"><?php echo $aboutPageData['mission']; ?></textarea>
           </div>
            <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="About Data" />
         </form>   
        </div>
        <!--//3rd Tabs-->
        
        <!--4th Tabs-->
        <div id="tabs-4" class="tabs">
          
            <form id ="pricingDataForm" action="" enctype="multipart/form-data">
        	<div class="rowsTabs">
                    <p>Meta Tags keyword</p>
                    <textarea class="mtag" id="pricingKeyword" name="pricingKeyword"><?php echo $pricingPageData['pricingKeyword'];?></textarea>
                     <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
            </div>
            <div class="rowsTabs">
                    <p>Meta Tags Description</p>
                  <textarea class="mtag" id="pricingDescription" name="pricingDescription"><?php echo  $pricingPageData['pricingDescription']; ?></textarea>
            </div>
          <div class="rowsTabs">  
                    <p>Select Tariff Plan</p>
                    <select name ="tariffPlan" id="tariffPlan">
                        <?php 
                        if(isset($planData->allvalue)>0){
                        foreach( $planData->allvalue as $tariffPlan){
                            
                        if($tariffPlan->id == $pricingPageData['tariffPlan']) $selectplan ="selected = selected"; else $selectplan='';     
                        echo '<option value="'.$tariffPlan->id.'"'.$selectplan.' >'.$tariffPlan->value.'</option>';}
                        } ?>
                    </select>
            </div>
            
            <h4 class="mrT3">Bank Details</h4>
            <?php foreach ($pricingPageData['bankDetail'] as $key => $value){?>
            <div class="bankdetail-data">
            	<div class="inprow"><label>Bank Name</label><input type="text" name="bankName[]" class="bankName" value="<?php echo $value['BankName'];?>"/></div>
                <div class="inprow"><label>IFSC Code</label><input type="text" name="ifsc[]" class="ifsc" value="<?php echo $value['ifsc'];?>"/></div>
                <div class="inprow"><label>Account No.</label><input type="text" name="accountNo[]" class="accountNo" value="<?php echo $value['accountNo'];?>"/></div>
                <div class="inprow"><label>Account Name</label><input type="text" name="accountName[]" class="accountName" value="<?php echo $value['accountName'];?>" /></div>
                <div class="actwrap" onclick="deleteBankDiv(this);"><i class="ic-24 delR"></i></div>
            </div>
            <?php }?>
             <a onclick="addMoreDetail();" class="themeLink addmoreDetaillink" title="Add More Bank Detail">Add More</a>
            
             <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="Pricing Data" />
            
            </form>
        </div>
        <!--//4th Tabs-->
        
        <!--5th Tabs-->
        <div id="tabs-5" class="tabs">
            <form id ="contactDataForm" action="" enctype="multipart/form-data">
        	<div class="rowsTabs">  
                    <p>Meta Tags keyword</p>
                    <textarea class="mtag" id="contactKeyword" name="contactKeyword"><?php echo $contactPageData['contactKeyword'];?></textarea>
                     <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
           
           
                </div>
            <div class="rowsTabs">  
                    <p>Meta Tags Description</p>
                   <textarea class="mtag" id="contactDescription" name="contactDescription"><?php echo $contactPageData['contactDescription'];?></textarea>
            </div>
            
            <h4 class="mrT3">Banner Setting</h4>
            <div class="inprow"><label>Visible Banner</label><input type="checkbox" name="cntBnrStatus" id="cntBnrStatus" <?php echo ($contactPageData['cntbannerStatus'] == 1)? "checked=checked" : '';?> /></div>
            <div class="banner-data">
            	<div class="inprow"><label>Banner heading</label><input type="text" name="contactHeading" id="contactHeading" value="<?php echo $contactPageData['cntbannerDetail']['contactHeading'];?>" /></div>
                <div class="inprow"><label>Banner Sub-heading</label><input type="text" name="contactSubHeading" id="contactSubHeading" value="<?php echo $contactPageData['cntbannerDetail']['contactSubHeading'];?>" /></div>
                <div class="inprow"><label>Banner Button Text</label><input type="text" name="contactBtnText" id="contactBtnText" value="<?php echo $contactPageData['cntbannerDetail']['contactBtnText'];?>" /></div>
                <div class="inprow"><label>Banner Button Link</label><input type="text" name="contactBtnLink" id="contactBtnLink" value="<?php echo $contactPageData['cntbannerDetail']['contactBtnLink'];?>" /></div>
            </div>
            
            <h4 class="mrT3">Contact Form</h4>
            <div class="inprow"><label>Contact Form</label><input type="checkbox" name="contactFormStatus" id="contactFormStatus" <?php echo ($contactPageData['contactFormStatus'] == 1)? "checked=checked" : '';?> /></div>
            <div class="inprow"><label>Your Email ID</label><input type="text" name="contactFormEmail" id="contactFormEmail" value="<?php echo $contactPageData['contactFormEmail'];?>"/></div>
            <div class="inprow"><label>Map Location</label><input type="checkbox" name="mapLocationStatus" id="mapLocationStatus" <?php echo ($contactPageData['mapLocationStatus'] == 1)? "checked=checked" : '';?> /></div>
            <p>Google map embeded code</p>
    		<div>
            <textarea name="gMapEmbededCode" id="gMapEmbededCode"><?php echo $contactPageData['gMapEmbededCode'];?></textarea></div>
            <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="contact Data"/>
            </form>
        </div>
        <!--//5th Tabs-->
    </div> 
    <!--//Tabs Wrapper-->
</div>

<?php }?>
<!--//Add Wesbite Content-->
<script type="text/javascript">
$(document).ready(function()
{
		$('.back').click(function() {
				if ( $(window).width() <1024) {
					$('.slideRight').animate({"right": "-1000px"}, "slow");
					$('.slideLeft').fadeIn(2000);
			}
		});
	});
</script>
