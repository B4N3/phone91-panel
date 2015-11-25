<?php	include('config.php');
if(isset($_REQUEST['submit']))
{
	$userid=$funobj->sql_safe_injection($_REQUEST['uname']);
	$pwd=$funobj->sql_safe_injection($_REQUEST['pwd']);
	$funobj->login_user($userid,$pwd);
	exit();	
}


function countryArray() 
{
        $url = "https://voice.phone91.com/isoData.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $string1 = json_decode($data, true);
        for ($i = 0; $i < count($string1); $i++) {
            $country[$string1[$i]['CountryCode']] = $string1[$i]['Country'];
        }
        return $country;
}
    
$country = countryArray();



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
		<?php include_once('inc/incHeader.php'); ?>
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
<!--                                <span class="callinratesg selectCurr"></span>-->
                            </div>
                            <span></span>
                       </div>
                                                
                    </div><!--/end row-->
                    
                    <div class="form-row clear">
                        <div>
                        <label class="lbl">Select top countries</label>
                       <select class="cmnsel smallSel" name="country[]">
                            <?php 
                            foreach($country as $key =>$countryNames){                
                                echo "<option value='$countryNames'>$countryNames</option>";
                            }?>  
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
                        <label id="addb" onclick="shownextDiv(this)">+</label>
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
                    <button class="btn btn-large" id="resellerSubmit">Submit</button>
                </div><!--/end row-->
           </form>     
           
                <div style="display:none" id="mes">
                    <h3 class="h3g ligt">Thank you for sending us your requirements. One of our representatives will get in touch with you within 24 hours.</h3>
                    <figure class="cmnsprt-"></figure>
                </div>
                
            </div><!--/end of form inner di-->
            
        </div><!--/end of form wrap-->
        
        
    </div><!--/end of wrapper-->
</section>
	<!-- //Container --> 
	
	<!-- Footer -->
	<?php //include_once('/inc/footer.php');?>
	<?php include_once('inc/incFooter.php');?>
	<!-- //Footer --> 
<script language="javascript" type="text/javascript" src="/js/jquery.form.js"></script>	
<script type="text/javascript">
function chaneCurrency(ths){
    var currency = $(ths).val();
    if(currency == 0){
        currency ='';
    }
    $('.selectCurr').html(currency);
}

function showResDiv(ths){
	var elem, _preVal, _preText;
	elem = $(ths);
	_preVal = elem.find(':selected').val();
	_preText = elem.find(':selected').text();

	if (_preVal == 'whitelabelsolutions'){
		$('#whitelabelsolutions-data').slideDown('fast');
	}
	else{
		$('#whitelabelsolutions-data').slideUp('fast');
	}
	
	if(_preVal=='callingcards'){
		$('#callingcards-data').slideDown('fast');
	}
	else{
		$('#callingcards-data').slideUp('fast');
	}
        
        $('#appendDiv').html('');
	
}

function shownextDiv(ths){
    var curr = $('#dealCurrency').val();
    if(curr == 0){
        curr ='';
    }
    var str ='<div class="form-row clear">\
              <div><label class="lbl">Select top countries</label>\
              <select  class="cmnsel smallSel" name="country[]">';
               <?php foreach($country as $key =>$countryNames){ ?>                
                     str +="<option value='<?php echo $countryNames; ?>'><?php echo $countryNames; ?></option>";
               <?php }?>  
                     str +='</select>\
                     <div class="estimtVol"><input placeholder="Est. vol. (in min)" name="overAllVolume[]" onblur="checkAllEstVolume(this);" type="text" class="cmninpt smallinput" /><spab></span></div>\
				     <div class="estimtVol per"><div class="perate"><input placeholder="per call rate"  name="callrate[]"  onblur="checkCallRate(this);" type="text" class="cmninpt" /><span class="selectCurr">'+curr+'</span></div><span></span></div>&nbsp;\<label id="addb" onclick="hideDiv(this)" class="closedb"><i class="cmnsprt-16 closeW"></i></label></div>\</div>';
       $('#appendDiv').append(str);
}

function hideDiv(ths){
    $(ths).parent().parent().remove();
 
}

function checkAllEstVolume(ths){
   
    var estVolume = $(ths).val();
    var regEx=/^[0-9]+(\.[0-9]{1,4})?$/;
    if(!regEx.test(estVolume)){
        $(ths).next().addClass('error_red').html('Please enter valid number');
        $(ths).removeClass('valid').addClass('error_red');
        //$(ths).next().next().html('Please enter valid interger');
       
     }
	 else{
		 $(ths).next().addClass('error_red').html('');
                 $(ths).removeClass('error_red').addClass('valid');
		 }
}

function checkCallRate(ths){   
    var estVolume = $(ths).val();
    var regEx=/^[0-9]+(\.[0-9]{1,4})?$/;
    if(!regEx.test(estVolume)){
         $(ths).removeClass('valid').addClass('error_red');
         $(ths).parent().removeClass('error_green').addClass('error_red');
         $(ths).parent().next().removeClass('error_green').addClass('error_red').html('Please enter valid number'); 
         return 0
     }else{
        $(ths).removeClass('error_red').addClass('valid');
        $(ths).parent().removeClass('error_red').addClass('error_green');
        $(ths).parent().next().removeClass('error_red').addClass('error_green').html(''); 
        return 1;
	 }
 }


function ChangeResellerVia(){  
     var resellerVia = $('#resellerVia').val();
     if(resellerVia == "callingcards"){
         return checkCallRate('#estimatedVolume');
     }
	 else 
     	if(resellerVia =="whitelabelsolutions"){
        	return checkCallRate('#volume');
     	}
}
 
function showRequest(formData, jqForm, options){
   $('#resellerSubmit').attr('disabled','disabled');
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
$(document).ready(function() {
            // validate the comment form when it is submitted	
            $("#reseller-query").validate({ 
                    onfocusout: function (element) {
                            $(element).valid();
                        },
                    rules: {
                            fullName :{
                                
                           // onkeyup: true,
				required: true,
				minlength: 5,
                                maxlength: 25
                                     },
                            emailId :{
				required: true,
                                email: true,
                                maxlength: 30
				       },   
                            contactNo :{
				required: true,
				number: true,
                                minlength: 8,
                                maxlength: 15
                                     },
                            message :{
				required: true,
				minlength: 5,
                                 maxlength: 250
                                     },
                           dealCurrency :{
                                selectcheck: true
                                    },
                           resellerVia :{
                                selectcheck: true
                                    }       
                             }
            })
          })  
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
         
         $('#resellerSubmit').removeAttr('disabled');  
}

var options = { 
	  url : "/action_layer.php?action=addFeedBackAndRequirements",
	  type: "POST",dataType: "json",
	  beforeSubmit:  showRequest, 
	  onkeyup :  showRequest, 
		// pre-submit callback 
	  success:showResponse  // post-submit callback 
}; 
$('#reseller-query').ajaxForm(options);


</script>