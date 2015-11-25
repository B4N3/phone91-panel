<?php
/**
 * @author sudhir pandey  <sudhir@hostnsoft.com>
 * @updated by SAMEER RATHOD <sameer@hostnsoft.com> 
 * @since 02-Oct-2013
 * @package Phone91
 * @details add website page and edit website page  
 */
include_once('config.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/config/whiteLabelConfig.php';
#create object of reseller_class
$websiteObj = new websiteClass();

$result = $funobj->getDomainResellerIdViaApc(_DOMAIN_NAME_,2);
$domainDetailsResult = $result;

if($_SESSION['id'] != $domainDetailsResult['resellerId']){
?>
<!--<script>
    $('#webList li:first').trigger('click');
</script>-->
<?php } ?>

<?php
include_once(_MANAGE_PATH_."main.php");
include_once(_MANAGE_PATH_."generalData.php");
include_once(_MANAGE_PATH_."home.php");
include_once(_MANAGE_PATH_."about.php");
include_once(_MANAGE_PATH_."pricing.php");
include_once(_MANAGE_PATH_."contact.php");

//var_dump(get_defined_vars());
//die();


if(isset($_REQUEST['id'])){
     
    
    
    
    if($_SESSION['id'] == $domainDetailsResult['resellerId'])
    {
        $resellerDefaultCurrencyResult = $funobj->getResellerDefaultCurrency($_SESSION['id'] ,"",2,$domainDetailsResult['id']);	
    }
    
}

$_SESSION['captcha'] = rand(1000,9999);
?>
<!--<script type="text/javascript" src="https://www.google.com/jsapi"></script>-->

<!--<script src="http://malsup.github.com/jquery.form.js"></script>-->


<?php if(!isset($_REQUEST['id'])){
    ?>

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
        <div class="fields">
            <label>Company Email (eg.example@company.com)</label>
            <input type="text" id="compEmail" name="compEmail" />
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
                        <input type="hidden" name="identificationNumber" id="identificationNumber" value="<?php echo $_SESSION['captcha']; ?>"/>
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
          <div id="checkB" style="float:left;"><label>Enable Unicode</label><input type="checkbox" id="checkboxId" onclick="checkboxClickHandler()"></div>
          
          <div id="transControl"></div>
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
                            <input type="text" name="companyName" id="cName" value="<?php echo base64_decode($domainDetailsResult['companyName']); ?>" />
                        </div>  
                        <div class="inprow">
                            <label class="lblLeft">Domain Name</label>
                            <input type="text" name="domainName" id="domainName" value="<?php echo $domainDetailsResult['domainName']; ?>"/>
                        </div>
                        <div class="inprow">
                            <label class="lblLeft">Company Email</label>
                            <input type="text" name="compEmail" id="compEmail" value="<?php echo $domainDetailsResult['compEmail']; ?>"/>
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
                        <input type="text" name="facebook" id="facebook" value="<?php echo $socialLinks_facebook;?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">Twitter</label>
                        <input type="text" name="twitter" id="twitter" value="<?php echo $socialLinks_twitter;?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">linkedin</label>
                        <input type="text" name="linkedin" id="linkedin" value="<?php echo $socialLinks_linkedin;?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">Gplus</label>
                        <input type="text" name="gplus" id="gplus"  value="<?php echo $socialLinks_gplus;?>" />
                   </div>
                </fieldset>
                <fieldset class="fieldset"> 
                             <legend class="legend">Contact Details</legend>
                            <div class="inprow">
                                <label class="lblLeft">Address</label>
                                <input type="text" name="address" id="address"  value="<?php echo $contact_address;?>" />
                            </div>
                            <div class="inprow">
                                <label class="lblLeft">Phone No.</label>
                                <input type="text" name="phoneNo" id="phoneNo"  value="<?php echo $contact_phoneNo;?>" />
                            </div>
                            <div class="inprow">
                                <label class="lblLeft">Email ID</label>
                                <input type="text" name="emailId" id="emailId"  value="<?php echo $contact_email;?>"/>
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
                      <input type="text" name="title" id="homeTitle" value="<?php echo $homeMeta_title; ?>"/>
                 </div>
                <div class="rowsTabs">
                    <label class="lblBlock">Meta Tags keyword</label>
                    <textarea class="mtag" id="homeKeyword" name ="mKeyword"><?php echo $homeMeta_mKeyword; ?></textarea>
                    <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
                    <!--<input type="hidden" name ="welImage" id="welImage" value ="<?php echo isset($generalData['welcomeImage'])? $generalData['welcomeImage'] : "welcomeDefault.gif" ; ?>"/>-->
                
                </div>
                <div class="rowsTabs">
                        <label class="lblBlock">Meta Tags Description</label>
                        <textarea class="mtag" id="homeDescription" name ="mDescription" ><?php echo $homeMeta_mDescription; ?></textarea>
                 </div>
                <fieldset class="fieldset">
                <legend class="legend">Banner</legend>                    
				  <label class="lblBlock">Welcome Image (file should be in .jpg, .png, .gif - formats and max. size 200kb and resoulution is 100X100 in pixels)</label>
				  <input type="file" name="file" class="mrB1"/>                    
                    <div class="inprow">
                            <label class="lblLeft">Heading</label>
                            <input type="text" name="heading" id="homeHeading" value="<?php echo $homebannerDetail_heading; ?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Sub heading</label>
                            <input type="text" name="subHeading" id="homeSubHeading" value="<?php echo $homebannerDetail_subHeading; ?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Banner button text</label>
                            <input type="text" name="text"  id="homeBannerText" value="<?php echo $homebannerDetail_text; ?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Banner button url</label>
                            <input type="text" name="link" value="<?php echo $homebannerDetail_link; ?>">
                    </div>
                 </fieldset>
                <div class="rowsTabs">
                        <label class="lblBlock">Welcome Content</label>
                        <textarea id="welcomeContent" name="welcomeContent"><?php echo $welcomeContent; ?></textarea>
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
                      <input type="text" name="title" id="aboutTitle" value="<?php echo $aboutMeta_title; ?>"/>
            </div>
        	<div class="rowsTabs">
                    <label class="lblBlock">Meta Tags keyword</label>
                    <textarea class="mtag" id="aboutKeyword" name="mKeyword"><?php echo $aboutMeta_mKeyword; ?></textarea>
                     <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
             </div>
            <div class="rowsTabs">
                    <label class="lblBlock">Meta Tags Description</label>
                    <textarea class="mtag" id="aboutDescription" name="mDescription"><?php echo $aboutMeta_mDescription; ?></textarea>
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
                        <input type="text" id="bannerHeading" name="heading" value="<?php echo $aboutbannerDetail_heading; ?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">Banner Sub-heading</label>
                        <input type="text" id="bannerSubHead" name="subHeading" value="<?php echo $aboutbannerDetail_subHeading; ?>" />
                    </div>
                    <div class="inprow">
                        <label class="lblLeft">Banner button text</label>
                            <input type="text" id="buttonText" name ="text" value="<?php echo $aboutbannerDetail_text; ?>" />
                   </div>
                  <div class="inprow">
                  		<label class="lblLeft">Banner button url</label>
                                <input type="text" id="buttonLink" name="link"  value="<?php echo $aboutbannerDetail_link; ?>"/>
                  </div>
            </div>
            </fieldset>
             <div class="rowsTabs">
                    <label class="lblBlock">Who you are</label>
                    <textarea id="whoUR" name="whoUR"><?php echo $whoUR; ?></textarea>
            </div>
            <div class="rowsTabs">
                <label class="lblBlock">Your Vision</label>
                <textarea id="vision" name="vision"><?php echo $vision; ?></textarea>
             </div>
            <div class="rowsTabs">
                <label class="lblBlock">Your Mission</label>
                <textarea id="mission" name="mission"><?php echo $mission; ?></textarea>
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
                      <input type="text" name="title" id="pricingTitle" value="<?php echo $pricingMeta_title;?>"/>
                </div>
                <div class="rowsTabs">
                        <label class="lblBlock">Meta Tags keyword</label>
                        <textarea class="mtag" id="pricingKeyword" name="mKeyword"><?php echo $pricingMeta_mKeyword;?></textarea>
                         <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
                </div>
                <div class="rowsTabs">
                        <label class="lblBlock">Meta Tags Description</label>
                        <textarea class="mtag" id="pricingDescription" name="mDescription"><?php echo $pricingMeta_mDescription; ?></textarea>
                </div>                
                <fieldset class="fieldset">
                <legend class="legend">Banner</legend>
                     <div class="rowsTabs">
                          <label class="lblBlock">Welcome Image (file should be in .jpg, .png, .gif - formats and max. size 200kb and resoulution is 100X100 in pixels)</label>
                            <input type="file" name="file">
                     </div>
                    <div class="inprow">
                            <label class="lblLeft">Heading</label>
                            <input type="text" name="heading" id="pricingHeading" value="<?php echo $pricingbannerDetail_heading;?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Sub heading</label>
                            <input type="text" name="subHeading" id="pricingSubHeading" value="<?php echo $pricingbannerDetail_subHeading;?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Banner button text</label>
                            <input type="text" name="text" id="pricingBannerbuttonText" value="<?php echo $pricingbannerDetail_text; ?>">
                    </div>
                    <div class="inprow">
                            <label class="lblLeft">Banner button url</label>
                            <input type="text" name="link" value="<?php echo $pricingbannerDetail_link;?>">
                    </div>
             </fieldset>
                 
         	
            
            <fieldset class="fieldset">   
                <legend class="legend">Bank Details</legend>
                <?php foreach ($detailAr as $key => $value){?>
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
                      <input type="text" name="title" id="contactTitle" value="<?php echo $contactMeta_title;?>"/>
             </div>
        	<div class="rowsTabs">  
                    <label class="lblBlock">Meta Tags keyword</label>
                    <textarea class="mtag" id="contactKeyword" name="mKeyword"><?php echo $contactMeta_mKeyword;?></textarea>
                     <input type="hidden" name ="domainId" id="domainId" value ="<?php echo $_REQUEST['id'];?>"/>
             </div>
            <div class="rowsTabs">  
                    <label class="lblBlock">Meta Tags Description</label>
                   <textarea class="mtag" id="contactDescription" name="mDescription"><?php echo $contactMeta_mDescription;?></textarea>
            </div>

				<fieldset class="fieldset">
<!--                <legend class="legend">Banner Setting</legend>
                    <div class="inprow">
                           <input type="checkbox" name="cntBnrStatus" id="cntBnrStatus" <?php // echo ($contactPageData['cntbannerStatus'] == 1)? "checked=checked" : '';?> class="enableCheck mrT1"/>
                            <label class="lblLeft">Visible Banner</label>
                      </div>-->
                      
              	  <div class="banner-data">
                      <div class="inprow"><label class="lblLeft">Banner heading</label><input type="text" name="heading" id="contactHeading" value="<?php echo $contactbannerDetail_heading;?>" /></div>
                      <div class="inprow"><label class="lblLeft">Banner Sub-heading</label><input type="text" name="subHeading" id="contactSubHeading" value="<?php echo $contactbannerDetail_subHeading;?>" /></div>
                      <div class="inprow"><label class="lblLeft">Banner button text</label><input type="text" name="text" id="contactBtnText" value="<?php echo $contactbannerDetail_text;?>" /></div>
                      <div class="inprow"><label class="lblLeft">Banner button url</label><input type="text" name="link" id="contactBtnLink" value="<?php echo $contactbannerDetail_link;?>" /></div>
                </div>
                </fieldset>
            
            <fieldset class="fieldset">
<!--                <legend class="legend">Contact Form</legend>
                <div class="inprow">
                        <input type="checkbox" class="enableCheck mrT1" name="contactFormStatus" id="contactFormStatus" <?php echo ($contactFormStatus == 1)? "checked=checked" : '';?> /><label class="lblLeft">Contact Form</label>
                </div>-->
                <div class="inprow">
                        <label class="lblLeft">Your Email ID</label> 
                        <input type="text" name="contactFormEmail" id="contactFormEmail" value="<?php echo $contactFormEmail;?>"/>
                  </div>
               
            </fieldset>
            
           <fieldset class="fieldset">
<!--               <legend class="legend">Location</legend>
               <div class="inprow">
                	 <input type="checkbox" name="mapLocationStatus" id="mapLocationStatus" <?php echo ($mapLocationStatus == 1)? "checked=checked" : '';?> class="enableCheck mrT1" />
                     <label class="lblLeft">Map Location</label>
                </div>-->
                <label class="lblBlock">Google map embeded code</label>
                <div>
                    <input type="text" name="gMapEmbededCode" id="gMapEmbededCode" value="<?php echo $gMapEmbededCode;?>"/>
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
				<div style="background-image:url(images/tmpl2.png)" class="tmpl">
					<div class="overlay"></div>
					<div class="tmplAct">
                                            <a class="btn btn-medium btn-warning " href="javascript:void(0);" onclick="updateThemes('<?php echo $_REQUEST['id']; ?>','1')">Select</a>
<!--						<a class="btn btn-medium btn-primary" href="javascript:void(0);">Preview</a>-->
					</div>
				</div>
				<div style="background-image:url(images/tmpl1.png)" class="tmpl">
					<div class="overlay"></div>
					<div class="tmplAct">
						<a class="btn btn-medium btn-warning " href="javascript:void(0);" onclick="updateThemes('<?php echo $_REQUEST['id']; ?>','2')">Select</a>
						<!--<a class="btn btn-medium btn-primary" href="javascript:void(0);">Preview</a>-->
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

<script type="text/javascript">
var control;
var _jsFlag =false;
var ids = ['contactSubHeading','contactBtnText','contactHeading','contactDescription','contactKeyword','contactTitle','pricingBannerbuttonText','pricingSubHeading','pricingHeading',
        'pricingDescription','pricingKeyword','pricingTitle','mission','vision','whoUR','buttonText','bannerSubHead','bannerHeading','aboutDescription','aboutKeyword','aboutTitle','welcomeContent',
        'homeBannerText','homeSubHeading','homeHeading','homeDescription','homeKeyword','homeTitle','address','cName'];    
control='';
function loadJSAPI(){
$.getScript('/js/jsapi.js').done(function(){/*
    if (!$("#transliterationcss").length)
    {
        $("head").append("<link id='transliterationcss'>");

        css = $("head").children(":last");
        css.attr({
            rel: "stylesheet",
            type: "text/css",
            href: "/css/transliteration.css"
        });
    }
       $.getScript('/js/transliteration.js').done(function(){
            
           
// Load the Google Transliterate API
google.load("elements", "1", {
	packages: "transliteration"
});

//var ids = ['transliterateTextarea','transliterateTextarea1'];
     
//      function onLoad() {
          
         
        var options = {
            sourceLanguage:
                'en',
            destinationLanguage:
            ['hi','am','ar','bn','zh','el','gu','kn','ml','mr','ne','or','fa','pa','ru','sa','si','sr','ta','te','ti','ur'],
//            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        };

        // Create an instance on TransliterationControl with the required
        // options.
        control = new google.elements.transliteration.TransliterationControl(options);
    
       
            
        var destinationLanguage = control.getLanguagePair().destinationLanguage;

        
        control.makeTransliteratable(ids);
        console.log($("#transControl").children().length);
//        if (!$("#transControl").children().length)
            control.showControl('transControl');
        
         control.addEventListener(
            google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED,transliterateStateChangeHandler);
        // Set the checkbox to the correct state.
        document.getElementById('checkboxId').checked = control.isTransliterationEnabled();
        
        
        
//      };
     
       // Handler for checkbox's click event.  Calls toggleTransliteration to toggle
//      // the transliteration state.
      
      

// Handler for STATE_CHANGED event which makes sure checkbox status
      // reflects the transliteration enabled or disabled status.
      function transliterateStateChangeHandler(e) {
        document.getElementById('checkboxId').checked = e.transliterationEnabled;
      }
      
      
      _jsFlag = true;
//      function languageChangeHandler() {
//        var dropdown = document.getElementById('languageDropDown');
//        control.setLanguagePair(
//            google.elements.transliteration.LanguageCode.ENGLISH,
//            dropdown.options[dropdown.selectedIndex].value);
//        }


//google.setOnLoadCallback(onLoad);
})


*/
if (!$("#transliterationcss").length)
    {
        $("head").append("<link id='transliterationcss'>");

        css = $("head").children(":last");
        css.attr({
            rel: "stylesheet",
            type: "text/css",
            href: "/css/transliteration.css"
        });
    }
       
    $.getScript('/js/transliteration.js').done(function(script, textStatus) {
        var options = {
            sourceLanguage: 'en',
          
            destinationLanguage: ['hi','am','ar','bn','zh','el','gu','kn','ml','mr','ne','or','fa','pa','ru','sa','si','sr','ta','te','ti','ur'],
           
            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        };

        // Create an instance on TransliterationControl with the required
        // options.
        var control =
                new google.elements.transliteration.TransliterationControl(options);


        // Enable transliteration in the editable DIV with id
        // 'transliterateDiv'.
        control.makeTransliteratable(ids);
        if (!$("#transControl").children().length)
            control.showControl('transControl');

        //$('#ss-loader').hide();
    })

}); 

}
function checkboxClickHandler() {
    
    
    if(_jsFlag == true){
        control.toggleTransliteration();
        $("#transControl").toggle();
    }
    else
        loadJSAPI();
}

$(function() {
	$( "#tabs" ).tabs({
		create: function(event, ui) {},
		select: function(event, ui) {}
	});

});
</script>
<script type="text/javascript" src="js/website.js"></script> 

