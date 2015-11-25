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
     
    $domainDetailsResult = $funobj->getDomainResellerId($_REQUEST['id'],2);
    
    if($_SESSION['id'] == $domainDetailsResult['resellerId'])
    {
    
//        var_dump($domainDetailsResult['id']);
        $resellerDefaultCurrencyResult = $funobj->getResellerDefaultCurrency($_SESSION['id'] ,"",2,$domainDetailsResult['id']);	

//        var_dump($resellerDefaultCurrencyResult);
    //    die();
        $genData = $websiteObj->getGeneralData($_REQUEST['id'],$_SESSION['id']);
        $generalData = json_decode($genData, true);  

        
        $aboutData = $websiteObj->getAboutData($_REQUEST['id'],$_SESSION['id']);
        $aboutPageData = json_decode($aboutData, true); 

        $contactData = $websiteObj->getContactPageData($_REQUEST['id'],$_SESSION['id']);
        $contactPageData = json_decode($contactData, true); 

        $pricingData = $websiteObj->getPricingPageData($_REQUEST['id'],$_SESSION['id']);
        $pricingPageData = json_decode($pricingData, true); 
    }
    
}
?>
<!--<script src="http://malsup.github.com/jquery.form.js"></script>-->
<script type="text/javascript" src="js/website.js"></script> 

<!--Add Manage Website-->
<a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>


<?php if(!isset($_REQUEST['id'])){?>
<form id ="addWebsite" action="">
<div class="addWebForm">
        <div class="reSellerhead">Add Website</div>
        <div class="fields">
            <label>IP Address (Point your website to this IP)</label>
        	 <span><?php echo $_SERVER['SERVER_ADDR']; ?></span>
         </div>
		<div class="fields">
            <label>Company Name</label>
        	 <input type="text" id="companyName" name="companyName" />
         </div>
        <div class="fields">
        	<label>Domain name (eg.example.com)</label>
      	    <input type="text" id="domainName" name="domainName" />
        </div>
<!--         <div class="fields">
        	<label>Theme</label>
      	   <input type="text" id="theme" name="theme" />
        </div>-->

        <div class="fields">
        	<label>Choose Language</label>
      	   <select class="bdr col3" name="language" id="language">
                <option value="English">English</option>
                <option value="French">French</option>
                <option value="German">German</option>
   			</select>
        </div>
        <div class="fl">
            <table class="cmntbl">
                <thead>
                    <tr>
                    
                    <th>Tariff Plan</th>
                    <th>Default Balance</th>
                    <th>Currency</th>
					<th>Action</th>
                    </tr>
                </thead>
                <tbody id="tariffTableTbody">					
                    <tr class="even">
						<td>
							<select class="selPlan" name="tariffPlan[]" onchange="showCurrency($(this))"></select>
						</td> 
						<td>
							<input type="text" name="balance[]" value="" class="balance" />
						</td> 
						<td>
							<label class="lbl">...</label>
							<input type="hidden" class="curr" name="currency[]" value=""/>
						</td> 
						<td>
							<input class="btn btn-medium btn-danger" type="button"  onclick="deleteRow($(this),'tariffTableTbody')" value="Delete"/>
						</td>
					</tr>
                </tbody>
            </table>						
			<div class="cl"></div>
			<a href="javascript:void(0);" class="themeLink pd db fr" onclick="addNewRow('tariffTableTbody')">Add Row</a>
			<input class="mrB1 btn btn-medium btn-primary"  value="Add Website" type="submit" title="Add Website" /></div>
        </div>
		
<!--<a href="javascript:void(0)" class="mrT2 btn btn-medium btn-primary alC">Add Website</a>-->
</form>    
<?php }elseif($_SESSION['id'] == $domainDetailsResult['resellerId']){?>      
<!--Add Wesbite Content-->
<div  id="web-data-form">
	  <div class="reSellerhead">Edit Website</div>
  	  <p class="subline">You can upload data page wise by default you get 4 pages in your website</p>

	<!--Tabs Wrapper-->
    <div id="tabs">
        <ul>
            <li><a href="#tabs-0">Domain Data</a></li>
            <li><a href="#tabs-1">General Data</a></li>
            <li><a href="#tabs-2">Home</a></li>
            <li><a href="#tabs-3">About</a></li>
            <li><a href="#tabs-4">Pricing</a></li>
            <li><a href="#tabs-5">Contact</a></li>
            <li><a href="#tabs-6">Templates</a></li>
        </ul>
        
        <div id="tabs-0" class="tabs">
            <form id ="domainDataForm" action="">
                <fieldset class="fieldset">
                       <legend class="legend">General</legend>
                        <div class="inprow">
                            <label class="lblLeft">Company Name</label>
                            <input type="text" name="companyName" value="<?php echo htmlentities($domainDetailsResult['companyName']); ?>" />
                        </div>  
                        <div class="inprow">
                            <label class="lblLeft">Domain Name</label>
                            <input type="text" name="domainName" value="<?php echo htmlentities($domainDetailsResult['domainName']); ?>"/>
                        </div>
                       <input type="hidden" name="domainId"  value="<?php echo $domainDetailsResult['id']; ?>" />
                       
<!--                        <div class="inprow">
                            <label class="lblLeft">Copyright</label>
                            <input type="text" name="copyright" value="<?php // echo $domainDetailsResult['']; ?>"/>
                        </div>-->
<!--                        <div class="inprow">
                         <label class="lblLeft">Signup</label>
                         <input type="checkbox" class="enableCheck mrT1" name="signupFlag" id="signupFlag"><label for="signupFlag"> Enable</label>
                        </div>-->
                </fieldset>
                <input class="mrB2 btn btn-medium btn-primary"  value="Update" type="submit" title="Update" />
            </form>
            <form id ="tariffDetailsForm" action="" enctype="multipart/form-data">
                <fieldset class="fieldset">
                    <div class="fl">
                    
                    <table class="cmntbl">
                        <thead>
                                <tr>
                                    <th>Tariff Plan</th>
                                    <th>Default Balance</th>
                                    <th>Currency</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        <tbody id="tariffTbody">
                            <?php 
//                            var_dump($resellerDefaultCurrencyResult);
                            
                            if($resellerDefaultCurrencyResult->num_rows > 0){
                            while($row = $resellerDefaultCurrencyResult->fetch_array(MYSQLI_ASSOC)){
//                                print_r($row);
                                ?>
                                
                            
                            <tr class="even">
                                <td>
                                    <select class="selPlan" select="<?php echo $row['tariffId']?>" name="tariffPlan[]" onchange="showCurrency($(this))">
                                        
                                    </select>
                                </td> 
                                <td>
                                    <input type="text" name="balance[]" value="<?php echo $row['balance']; ?>" class="balance" />
                                </td> 
                                <td>
                                    <label class="lbl"><?php echo $funobj->getCurrencyViaApc($row['currencyId'],1); ?></label>
                                    <input type="hidden" class="curr" name="currency[]" value="<?php echo $row['currencyId']; ?>"/>
                                </td> 
                                <td>
                                    <input class="btn btn-medium btn-danger" type="button"  onclick="deleteRow($(this),'tariffTbody',2,<?php echo $row['sno']; ?>)" value="Delete"/>
                                </td>
                            </tr>
                            
                            <?php }                            
                            } 
                            else
                            { ?>
                                <tr class="even">
                                <td>
                                    <select class="selPlan" name="tariffPlan[]" onchange="showCurrency($(this))"></select>
                                </td> 
                                <td>
                                    <input type="text" name="balance[]" value="" class="balance" />
                                </td> 
                                <td>
                                    <label class="lbl">...</label>
                                    <input type="hidden" class="curr" name="currency[]" value=""/>
                                </td> 
                                <td>
                                    <input class="btn btn-medium btn-danger" type="button"  onclick="deleteRow($(this),'tariffTbody')" value="Delete"/>
                                </td>
                            </tr>
                           <?php }
                            ?>
                        </tbody>
                   </table>
                    <input type="hidden" name="domainName" id="dName" value="<?php echo $_REQUEST['id']; ?>" />
                        <div class="cl"></div>
			<a href="javascript:void(0);" class="themeLink pd db fr" onclick="addNewRow('tariffTbody')">Add Row</a>
                    </div>
                </fieldset>
                <input class="mrB2 btn btn-medium btn-primary"  value="Update" type="submit" title="Update" />
            </form>
        </div>
        
        <!--1st Tabs-->
        <div id="tabs-1" class="tabs">
            <form id ="generalDataForm" action="" method="post" name="generalDataForm" enctype="multipart/form-data">
                <fieldset class="fieldset">
                       <legend class="legend">Logo</legend>
                       <label class="lblBlock">Logo (file should be in .jpg, .png, .gif - formats and max. size 200kb)</label>
                        <!--class="fileWrap"--><div><input type="file" name="file"/></div>
                        <!--<input type="hidden" name ="logoimg" id="logoimg" value ="<?php echo isset($generalData['logoimage'])? $generalData['logoimage'] : "default.php" ; ?>"/>-->
                        <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
                </fieldset>
               
                <fieldset class="fieldset">
                    <legend class="legend">Social Links</legend>
                    <div class="inprow">
                        <label class="lblLeft">Facebook</label>
                        <input type="text" name="facebook" id="facebook" value="<?php echo htmlentities($generalData['socialLinks']['facebook']);?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">Twitter</label>
                        <input type="text" name="twitter" id="twitter" value="<?php echo htmlentities($generalData['socialLinks']['twitter']);?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">linkedin</label>
                        <input type="text" name="linkedin" id="linkedin" value="<?php echo htmlentities($generalData['socialLinks']['linkedin']);?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">Gplus</label>
                        <input type="text" name="gplus" id="gplus"  value="<?php echo htmlentities($generalData['socialLinks']['gplus']);?>" />
                   </div>
                </fieldset>
                <fieldset class="fieldset"> 
                             <legend class="legend">Contact Details</legend>
                            <div class="inprow">
                                <label class="lblLeft">Address</label>
                                <input type="text" name="address" id="address"  value="<?php echo htmlentities($generalData['contact']['address']);?>" />
                            </div>
                            <div class="inprow">
                                <label class="lblLeft">Phone No.</label>
                                <input type="text" name="phoneNo" id="phoneNo"  value="<?php echo htmlentities($generalData['contact']['phoneNo']);?>" />
                            </div>
                            <div class="inprow">
                                <label class="lblLeft">Email ID</label>
                                <input type="text" name="emailId" id="emailId"  value="<?php echo htmlentities($generalData['contact']['email']);?>"/>
                            </div>
                    </fieldset>
                <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="Update" />
            </form>
        </div>
        <!--//1st Tabs-->
        
        <!--2nd Tabs-->
        <div id="tabs-2" class="tabs">
             <form id ="homeDataForm" action="" enctype="multipart/form-data">
                <div class="rowsTabs">
                      <label class="lblBlock">Title</label>
                      <input type="text" name="title" value="<?php echo htmlentities($generalData['title']); ?>"/>
                 </div>
                <div class="rowsTabs">
                    <label class="lblBlock">Meta Tags keyword</label>
                    <textarea class="mtag" id="homeKeyword" name ="mKeyword"><?php echo htmlentities($generalData['mKeyword']); ?></textarea>
                    <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
                    <!--<input type="hidden" name ="welImage" id="welImage" value ="<?php echo isset($generalData['welcomeImage'])? $generalData['welcomeImage'] : "welcomeDefault.gif" ; ?>"/>-->
                
                </div>
                <div class="rowsTabs">
                        <label class="lblBlock">Meta Tags Description</label>
                        <textarea class="mtag" id="homeDescription" name ="mDescription" ><?php echo htmlentities($generalData['mDescription']); ?></textarea>
                 </div>
                <fieldset class="fieldset">
                <legend class="legend">Banner</legend>                    
				  <label class="lblBlock">Welcome Image (file should be in .jpg, .png, .gif - formats and max. size 200kb and resoulution is 100X100 in pixels)</label>
				  <input type="file" name="file" class="mrB1"/>                    
                    <div class="inprow">
                            <label class="lblLeft">Heading</label>
                            <input type="text" name="heading" value="<?php echo htmlentities($generalData['heading']); ?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Sub heading</label>
                            <input type="text" name="subHeading" value="<?php echo htmlentities($generalData['subHeading']); ?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Banner button text</label>
                            <input type="text" name="text" value="<?php echo htmlentities($generalData['text']); ?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Banner button url</label>
                            <input type="text" name="link" value="<?php echo htmlentities($generalData['link']); ?>">
                    </div>
                 </fieldset>
                <div class="rowsTabs">
                        <label class="lblBlock">Welcome Content</label>
                        <textarea id="welcomeContent" name="welcomeContent"><?php echo htmlentities($generalData['welcomeContent']); ?></textarea>
                </div>
                <!--<fieldset class="fieldset">
                    <legend class="legend">Feautures List (you can select max. 3)</legend>
                   <div class="features">
                            <?php  for($i=0; $i<=10; $i++) { ?>
                            <div class="inprow">
                            	<input type="checkbox" />
                                <label class="lblLeft">Feature 1</label>
                                <p>Lorem ipsume dummy text come</p>
                            </div> <?php }?>
                   </div>
              </fieldset>-->
           	  <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="Update" />
             </form>
        </div>
        <!--//2nd Tabs-->
        
        <!--3rd Tabs-->
        <div id="tabs-3" class="tabs">
            <form id ="aboutDataForm" action="" enctype="multipart/form-data">
             <div class="rowsTabs">
                      <label class="lblBlock">Title</label>
                      <input type="text" name="title" value="<?php echo htmlentities($aboutPageData['title']); ?>"/>
            </div>
        	<div class="rowsTabs">
                    <label class="lblBlock">Meta Tags keyword</label>
                    <textarea class="mtag" id="aboutKeyword" name="mKeyword"><?php echo htmlentities($aboutPageData['mKeyword']); ?></textarea>
                     <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
             </div>
            <div class="rowsTabs">
                    <label class="lblBlock">Meta Tags Description</label>
                    <textarea class="mtag" id="aboutDescription" name="mDescription"><?php echo htmlentities($aboutPageData['mDescription']); ?></textarea>
            </div>
            <fieldset class="fieldset">
            	<legend class="legend">Banner Setting</legend>
                <div class="rowsTabs">
                          <label class="lblBlock">Welcome Image (file should be in .jpg, .png, .gif - formats and max. size 200kb and resoulution is 100X100 in pixels)</label>
                            <input type="file" name="file">
                     </div>
<!--                 <div class="inprow">
                    <input type="checkbox" id="bannerStatus" name="bannerStatus" <?php echo ($aboutPageData['bannerStatus'] == 1)? "checked=checked" : "";?> class="enableCheck mrT1"/>
                    <label class="lblLeft">Visible Banner</label>
                 </div>-->
         	   	 <div class="banner-data">
                    <div class="inprow">
                        <label class="lblLeft">Banner heading</label>
                        <input type="text" id="bannerHeading" name="heading" value="<?php echo htmlentities($aboutPageData['bannerDetail']['heading']); ?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">Banner Sub-heading</label>
                        <input type="text" id="bannerSubHead" name="subHeading" value="<?php echo htmlentities($aboutPageData['bannerDetail']['subHeading']); ?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">Banner button text</label>
                        <input type="text" id="buttonText" name ="text" value="<?php echo htmlentities($aboutPageData['bannerDetail']['text']); ?>" />
                   </div>
                  <div class="inprow">
                  		<label class="lblLeft">Banner button url</label>
                                <input type="text" id="buttonLink" name="link"  value="<?php echo htmlentities($aboutPageData['bannerDetail']['link']); ?>"/>
                  </div>
            </div>
            </fieldset>
             <div class="rowsTabs">
                    <label class="lblBlock">Who you are</label>
                    <textarea id="whoUR" name="whoUR"><?php echo htmlentities($aboutPageData['whoUR']); ?></textarea>
            </div>
            <div class="rowsTabs">
                <label class="lblBlock">Your Vision</label>
                <textarea id="vision" name="vision"><?php echo htmlentities($aboutPageData['vision']); ?></textarea>
             </div>
            <div class="rowsTabs">
                <label class="lblBlock">Your Mission</label>
                <textarea id="mission" name="mission"><?php echo htmlentities($aboutPageData['mission']); ?></textarea>
           </div>
            <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="Update" />
         </form>   
        </div>
        <!--//3rd Tabs-->
        
        <!--4th Tabs-->
        <div id="tabs-4" class="tabs">
            <form id ="pricingDataForm" action="" enctype="multipart/form-data">
                <div class="rowsTabs">
                      <label class="lblBlock">Title</label>
                      <input type="text" name="title" value="<?php echo htmlentities($pricingPageData['title']);?>"/>
                </div>
                <div class="rowsTabs">
                        <label class="lblBlock">Meta Tags keyword</label>
                        <textarea class="mtag" id="pricingKeyword" name="mKeyword"><?php echo htmlentities($pricingPageData['mKeyword']);?></textarea>
                         <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
                </div>
                <div class="rowsTabs">
                        <label class="lblBlock">Meta Tags Description</label>
                        <textarea class="mtag" id="pricingDescription" name="mDescription"><?php echo htmlentities($pricingPageData['mDescription']); ?></textarea>
                </div>                
                <fieldset class="fieldset">
                <legend class="legend">Banner</legend>
                     <div class="rowsTabs">
                          <label class="lblBlock">Welcome Image (file should be in .jpg, .png, .gif - formats and max. size 200kb and resoulution is 100X100 in pixels)</label>
                            <input type="file" name="file">
                     </div>
                    <div class="inprow">
                            <label class="lblLeft">Heading</label>
                            <input type="text" name="heading" value="<?php echo htmlentities($pricingPageData['heading']);?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Sub heading</label>
                            <input type="text" name="subHeading" value="<?php echo htmlentities($pricingPageData['subHeading']);?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Banner button text</label>
                            <input type="text" name="text" value="<?php echo htmlentities($pricingPageData['text']);?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Banner button url</label>
                            <input type="text" name="link" value="<?php echo htmlentities($pricingPageData['link']);?>">
                    </div>
             </fieldset>
                 
         	
            
            <fieldset class="fieldset">   
                <legend class="legend">Bank Details</legend>
                <?php foreach ($pricingPageData['bankDetail'] as $key => $value){?>
                <div class="bankdetail-data fl">
                    <div class="inprow">
                            <label class="lblLeft">Bank Name</label>
                            <input type="text" name="bankName[]" class="bankName" value="<?php echo $value['BankName'];?>"/>
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">IFSC Code</label>
                            <input type="text" name="ifsc[]" class="ifsc" value="<?php echo $value['ifsc'];?>"/>
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Account No.</label>
                            <input type="text" name="accountNo[]" class="accountNo" value="<?php echo $value['accountNo'];?>"/>
                   </div>
                    <div class="inprow">
                            <label class="lblLeft">Account Name</label>
                            <input type="text" name="accountName[]" class="accountName" value="<?php echo $value['accountName'];?>" />
                   </div>
                    <div class="actwrap fr" onclick="deleteBankDiv(this);"><i class="ic-24 delR"></i></div>
                </div>
				<div class="cl"></div>
                <?php }?>
                 <a onclick="addMoreDetail();" class="themeLink addmoreDetaillink" title="Add More Bank Detail">Add More</a>
            </fieldset>
             <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="Update" />
            </form>
        </div>
        <!--//4th Tabs-->
        
        <!--5th Tabs-->
        <div id="tabs-5" class="tabs">
            <form id ="contactDataForm" action="" enctype="multipart/form-data">
          		   <div class="rowsTabs">
                      <label class="lblBlock">Title</label>
                      <input type="text" name="title" value="<?php echo htmlentities($contactPageData['title']);?>"/>
             </div>
        	<div class="rowsTabs">  
                    <label class="lblBlock">Meta Tags keyword</label>
                    <textarea class="mtag" id="contactKeyword" name="mKeyword"><?php echo htmlentities($contactPageData['mKeyword']);?></textarea>
                     <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
             </div>
            <div class="rowsTabs">  
                    <label class="lblBlock">Meta Tags Description</label>
                   <textarea class="mtag" id="contactDescription" name="mDescription"><?php echo htmlentities($contactPageData['mDescription']);?></textarea>
            </div>

				<fieldset class="fieldset">
<!--                <legend class="legend">Banner Setting</legend>
                    <div class="inprow">
                           <input type="checkbox" name="cntBnrStatus" id="cntBnrStatus" <?php // echo ($contactPageData['cntbannerStatus'] == 1)? "checked=checked" : '';?> class="enableCheck mrT1"/>
                            <label class="lblLeft">Visible Banner</label>
                      </div>-->
                      
              	  <div class="banner-data">
                      <div class="inprow"><label class="lblLeft">Banner heading</label><input type="text" name="heading" id="contactHeading" value="<?php echo htmlentities($contactPageData['cntbannerDetail']['heading']);?>" /></div>
                      <div class="inprow"><label class="lblLeft">Banner Sub-heading</label><input type="text" name="subHeading" id="contactSubHeading" value="<?php echo htmlentities($contactPageData['cntbannerDetail']['subHeading']);?>" /></div>
                      <div class="inprow"><label class="lblLeft">Banner button text</label><input type="text" name="text" id="contactBtnText" value="<?php echo htmlentities($contactPageData['cntbannerDetail']['text']);?>" /></div>
                      <div class="inprow"><label class="lblLeft">Banner button url</label><input type="text" name="link" id="contactBtnLink" value="<?php echo htmlentities($contactPageData['cntbannerDetail']['link']);?>" /></div>
                </div>
                </fieldset>
            
            <fieldset class="fieldset">
<!--                <legend class="legend">Contact Form</legend>
                <div class="inprow">
                        <input type="checkbox" class="enableCheck mrT1" name="contactFormStatus" id="contactFormStatus" <?php echo ($contactPageData['contactFormStatus'] == 1)? "checked=checked" : '';?> /><label class="lblLeft">Contact Form</label>
                </div>-->
                <div class="inprow">
                        <label class="lblLeft">Your Email ID</label> 
                        <input type="text" name="contactFormEmail" id="contactFormEmail" value="<?php echo htmlentities($contactPageData['contactFormEmail']);?>"/>
                  </div>
               
            </fieldset>
            
           <fieldset class="fieldset">
<!--               <legend class="legend">Location</legend>
               <div class="inprow">
                	 <input type="checkbox" name="mapLocationStatus" id="mapLocationStatus" <?php echo ($contactPageData['mapLocationStatus'] == 1)? "checked=checked" : '';?> class="enableCheck mrT1" />
                     <label class="lblLeft">Map Location</label>
                </div>-->
                <label class="lblBlock">Google map embeded code</label>
                <div>
                    <textarea name="gMapEmbededCode" id="gMapEmbededCode"><?php echo htmlentities($contactPageData['gMapEmbededCode']);?></textarea>
                </div>
            </fieldset>
            <input class="mrT2 btn btn-medium btn-primary"  value="Update" type="submit" title="Update"/>
            </form>
        </div>
        <!--//5th Tabs-->
        
         <!--6th Tabs-->
         <div id="tabs-6" class="tabs">
         	<form action="">
				<div class="tmplWrp">
				<div style="background-image:url(images/tmpl1.png)" class="tmpl">
					<div class="overlay"></div>
					<div class="tmplAct">
                                            <a class="btn btn-medium btn-warning " href="javascript:void(0);" onclick="updateThemes('<?php echo $_REQUEST['id']; ?>','1')">Select</a>
						<a class="btn btn-medium btn-primary" href="javascript:void(0);">Preview</a>
					</div>
				</div>
				<div style="background-image:url(images/tmpl2.png)" class="tmpl">
					<div class="overlay"></div>
					<div class="tmplAct">
						<a class="btn btn-medium btn-warning " href="javascript:void(0);" onclick="updateThemes('<?php echo $_REQUEST['id']; ?>','2')">Select</a>
						<a class="btn btn-medium btn-primary" href="javascript:void(0);">Preview</a>
					</div>
				</div>
<!--				<div style="background-image:url(images/tmpl3.png)" class="tmpl">
					<div class="overlay"></div>
					<div class="tmplAct">
						<a class="btn btn-medium btn-warning " href="javascript:void(0);" onclick="updateThemes('<?php echo $_REQUEST['id']; ?>','3')">Select</a>
						<a class="btn btn-medium btn-primary" href="javascript:void(0);">Preview</a>
					</div>
				</div>-->
			</div>
			<div class="cl"></div>			
			</form>
         </div>
         <!--//6th Tabs-->
        
    </div> 
    <!--//Tabs Wrapper-->
</div>



<?php }?>
<!--//Add Wesbite Content-->
<script type="text/javascript">
selectPlan();
</script>
<script type="text/javascript">
/*AY - add new blank BANK detail fields - website.js */
addMoreDetail();
/**/  
function addNewRow(tbody)
{
    console.log(tbody);
    var tr = $('#'+tbody+' tr:last').clone();
	console.log($('#'+tbody+' tr:last').clone());
	if(tr.hasClass('even'))
		tr.removeClass('even').addClass('odd');
	else
		tr.removeClass('odd').addClass('even');
	
    $('.balance',tr).val('');
	$('.lbl',tr).html('...');
	
	tr.appendTo($('#'+tbody));
}

function deleteRow(ths,id,type,domainId)
{
	var tr = ths.parents('tr');
	if($('#'+id+' tr').length > 1)
	{		
		tr.remove();
	}
	else
	{
		$('.balance',tr).val('');
		$('.lbl',tr).html('...');
		$('select',tr).val('Select').prop('selected',true);
	}
        if(type == 2)
        {
            $.ajax({
                url:"controller/websiteController.php",
                data:{"action":"deleteResellerTariff","id":domainId},
                dataType:"JSON",
                success:function(response){
                    console.log(response);
                }
            })
        }
}

function showCurrency(ths)
{
	var findIn = ths.parents('tr');
    $('.lbl',findIn).html(_planCurrencyVariable[ths.val()][1]);
    $('.curr',findIn).val(_planCurrencyVariable[ths.val()][0]);
}

function updateThemes(domainName,themeId)
{
    $.ajax({
        url:"controller/websiteController.php",
        type:"POST",
        data:{"domainName":domainName,"themeId":themeId,"action":"updateThemeDetails"},
        dataType:"JSON",
        success:function(response){
            show_message(response.msg,response.status);
        }
    })
}

//$(document).ready(function(){
//    $('.id_100 option[value=val2]').attr('selected','selected');
//})
<?php if(!isset($_REQUEST['id']) || $_REQUEST['id'] == ""){ ?>
//    $('#webList li:first').addClass('active').trigger('click');
<?php } ?>

</script>
