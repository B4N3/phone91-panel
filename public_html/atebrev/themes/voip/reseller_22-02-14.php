<?php	include('config.php');
if(isset($_REQUEST['submit']))
{
	$userid=$funobj->sql_safe_injection($_REQUEST['uname']);
	$pwd=$funobj->sql_safe_injection($_REQUEST['pwd']);
	$funobj->login_user($userid,$pwd);
	exit();	
}	
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reseller with Phone91.com |International calling with Phone91</title>
<meta name="keywords" content="Phone91 is a leading International call provider. Phone91 provides various medium for making cheap international calls and long distance calls." />
<meta name="description" content="cheap international calls,  long distance calls." />
<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]-->
<!--[if !IE]><!--><!-- COMMENT on 15 april <link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /> --><!--<![endif]-->
<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php include_once(_THEME_PATH_. '/inc/incHead.php'); ?>
</head>
<body>

	<!-- Header -->
		<?php include_once(_THEME_PATH_. '/inc/incHeader.php'); ?>
	<!-- //Header --> 

	<!-- Features -->
	<div class="mainFeaturesWrapper">
	  <section id="featuresWrap" class="noBanner">
	    <section class="innerBanner pr">
              <h1 class="mianHead">
                    <div>Phone91 reseller opportunity</div>
                    <span>As to expand our business, we are looking for our channel partners who can resell our VOIP services to their region.</span>
     		  </h1>
    		   <div class="met_short_split"><span></span></div>
	      <div class="cl db pa backLinks">
	        <?php include_once("inc/login_header.php") ?>
	      </div>
	      <span class="clr"></span> </section>
	  </section>
	</div>
	<!-- Features --> 

	<!-- Container -->
	
	<section id="req-container" class="hgPart">
	<div id="getStartedForm"></div>
	<div class="cnthead alC">Let us know about  <span class="themeClr">your requirements</span></div>
    <p class="alC ligt fs18 mrB4">We would be able to help you better.</p>
	<div class="wrapper">
    	<div id="formwrap" class="clear pr">
        	<span class="themeBg" id="frmcircle"><i class="cmnsprt-36 resW"></i></span>
            
            
            <div id="forminner">
            <form id="reseller-query" action="javascript:void(0)" novalidate="novalidate">
                <h5 class="cnthead-sub" id="resellerResponseData"></h5>
            	<div class="form-row clear">
                	<label class="lbl">Your full name</label>
                        <input type="text" class="cmninpt" id="fullName" name="fullName"> 
                      
                </div><!--/end row-->
                
                <div class="form-row clear">
                	<label class="lbl">Your contact number</label>
                    <input type="tel" class="cmninpt" id="contactNo" name="contactNo"> <div class="msg "></div>
                </div><!--/end row-->
                
                <div class="form-row clear">
                	<label class="lbl">Your Email ID</label>
                    <input type="email" class="cmninpt" id="emailId" name="emailId"> <div class="msg "></div>
                </div><!--/end row-->
                
                 <div class="form-row clear">
                        <label class="lbl">You want to deal</label>
                        <select class="cmnsel" name="dealCurrency" id="dealCurrency" onchange="chaneCurrency(this)">
                            <option value="0">In which Currency</option>
                            <option value="INR">INR</option>
                            <option value="USD">USD</option>
                            <option value="AED">AED</option>
                        </select>
                    </div><!--/end row-->
                    
                <div class="form-row clear">
                	<label class="lbl">You wish to be a Reseller via</label>
                    <select class="cmnsel" name="resellerVia" id="resellerVia" onchange="showResDiv(this)">
                    	<option value="0">Select</option>
                        <option value="callingcards">Calling Cards</option>
                        <option value="whitelabelsolutions">White Label Solutions</option>
                    <!--	<option selected value="user">User</option>
                        <option value="reseller">Reseller</option>-->
                    </select>
                </div><!--/end row-->
                
                <!--below div visible when user select to be whitelabel-->
                <div id="whitelabelsolutions-data" style="display:none">
                    <div class="form-row clear">
                        <label class="lbl">Overall estimated volume</label>
                        <div class="callingAmount">
                        	<div class="rateamount">
                                <input type="text" class="cmninpt" placeholder="in Minutes" id="volume" name="volume" onblur="checkCallRate(this);">
                                <span class="callinratesg selectCurr"></span>
                            </div>
                            <span></span>
                       </div>
                                                
                    </div><!--/end row-->
                    
                    <div class="form-row clear">
                        <div>
                        <label class="lbl">Select top countries</label>
                        <select class="cmnsel smallSel" name="country[]">
                            <option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Norfolk Island">Norfolk Island</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Aruba">Aruba</option><option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Brazil">Brazil</option><option value="Western Sahara">Western Sahara</option><option value="British Virgin Islands">British Virgin Islands</option><option value="Brunei">Brunei</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Myanmar">Myanmar</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Usa">Usa</option><option value="Cape Verde">Cape Verde</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option><option value="China">China</option><option value="Colombia">Colombia</option><option value="Comoros">Comoros</option><option value="Cook Islands">Cook Islands</option><option value="Costa Rica">Costa Rica</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Falkland Islands">Falkland Islands</option><option value="Faroe Islands">Faroe Islands</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="French Polynesia">French Polynesia</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="West Bank">West Bank</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guam">Guam</option><option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guinea-Bissau">Guinea-Bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Italy">Italy</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indonesia">Indonesia</option><option value="Iran">Iran</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="United Kingdom">United Kingdom</option><option value="Israel">Israel</option><option value="Ivory Coast">Ivory Coast</option><option value="Jamaica">Jamaica</option><option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Russia">Russia</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Serbia">Serbia</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Laos">Laos</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libya">Libya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macau">Macau</option><option value="Macedonia">Macedonia</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Micronesia">Micronesia</option><option value="Moldova">Moldova</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montenegro">Montenegro</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option><option value="Netherlands">Netherlands</option><option value="Netherlands Antilles">Netherlands Antilles</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="North Korea">North Korea</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option><option value="Palau">Palau</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Pitcairn Islands">Pitcairn Islands</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Qatar">Qatar</option><option value="Republic of the Congo">Republic of the Congo</option><option value="Romania">Romania</option><option value="Rwanda">Rwanda</option><option value="Saint Barthelemy">Saint Barthelemy</option><option value="Saint Helena">Saint Helena</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Martin">Saint Martin</option><option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option><option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option><option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="South Korea">South Korea</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option><option value="Suriname">Suriname</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syria">Syria</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania">Tanzania</option><option value="Thailand">Thailand</option><option value="Timor-Leste">Timor-Leste</option><option value="Togo">Togo</option><option value="Tokelau">Tokelau</option><option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks and Caicos Islands">Turks and Caicos Islands</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="Uruguay">Uruguay</option><option value="US Virgin Islands">US Virgin Islands</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Venezuela">Venezuela</option><option value="Vietnam">Vietnam</option><option value="Wallis and Futuna">Wallis and Futuna</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option><option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>  
                        </select>
                        <div class="estimtVol">
                             <input placeholder="Est. vol. (in min)" name="overAllVolume[]" onblur="checkAllEstVolume(this);" type="text" class="cmninpt smallinput">
                             <span></span>
                         </div>
                         <div class="estimtVol per">
                            <div class="perate">
                                    <input placeholder="per call rate" name="callrate[]" onblur="checkCallRate(this);" type="text" class="cmninpt">
                                    <span class="selectCurr"></span>
                            </div>
                             <span></span>
                          </div>
                        <label id="addb" onclick="shownextDiv(this)"><i class="cmnsprt-16 addW"></i></label>
                        </div>
                    </div><!--/end row-->
                    <div id="appendDiv"> </div>
                </div><!--/end of whitelabel data-->
                
                
                <!--below div visible when user select to be callingcards-->
                <div id="callingcards-data" style="display:none">
                    <div class="form-row clear">
                        <label class="lbl">Estimated volume</label>
                        <div class="callingAmount">
                        	<div class="rateamount">
                                <input type="text" class="cmninpt" placeholder="Amount" id="estimatedVolume" name="estimatedVolume" onblur="checkCallRate(this);">
                                <span class="callinratesg selectCurr"></span>
                            </div>
                            <span></span>
                       </div>
                       
                    </div><!--/end row-->
                </div>
                <!--//callingcards-->
                
                <div class="form-row clear">
                	<label class="lbl">Share with us</label>
                    <textarea class="cmntxt" placeholder="any query or comment" id="message" name="message"></textarea>
                     <input type="hidden" name="type" value="2">
                </div><!--/end row-->
                
                <div class="form-row clear">
                	<label class="lbl">&nbsp;</label>
                    <button class="btn btn-large">Submit</button>
                </div><!--/end row-->
           </form>     
           
                <div style="display:none" id="mes">
                    <h3 class="h3g ligt">Thank you for sending us your requirements. One of our representatives will get in touch with you within 24 hours.ssss</h3>
                    <figure class="cmnsprt-"></figure>
                </div>
                
            </div><!--/end of form inner di-->
            
        </div><!--/end of form wrap-->
        
        
    </div><!--/end of wrapper-->
</section>
	<!-- //Container --> 
	
	<!-- Footer -->
	<?php //include_once('/inc/footer.php');?>
	<?php include_once(_THEME_PATH_. '/inc/incFooter.php');?>
	<!-- //Footer --> 
<script language="javascript" type="text/javascript" src="/js/jquery.form.js"></script>	
<script type="text/javascript">
function ChangeResellerVia(){
     
     var resellerVia = $('#resellerVia').val();
     if(resellerVia == "callingcards"){
         return checkCallRate('#estimatedVolume');
     }else 
     if(resellerVia =="whitelabelsolutions"){
         return checkCallRate('#volume');
     }
 }
 
function showRequest(formData, jqForm, options){ 
   
        ChangeResellerVia();
    
        jQuery.validator.addMethod('selectcheck', function (value) {
                return (value != '0');
            }, "Please Select proper value!"); 
            
        if($("#reseller-query").valid()){
            if(ChangeResellerVia() == 1)
                return true; 
            else
                return false;
        }else
                return false;
  }
  
  function showResponse(response, statusText, xhr, $form){
    
    if(response.status == "success"){
        $('#reseller-query').fadeOut('slow').delay(400);
	$('#mes').delay(400).fadeIn(3000);  
         $(':input','#reseller-query')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked');
    }
     else{
       $('#resellerResponseData').removeClass("error_green").addClass("error_red");
       $('#resellerResponseData').html(response.message);
     }
    
   
}

var options = { 
	  url : "action.php?action=addFeedBackAndRequirements",
	  type: "POST",dataType: "json",
	  beforeSubmit:  showRequest, 
	  onkeyup :  showRequest, 
		// pre-submit callback 
	  success:showResponse  // post-submit callback 
}; 
$('#reseller-query').ajaxForm(options);


</script>