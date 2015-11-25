<?php
//Include Common Configuration File First
include_once('config.php');
include_once(CLASS_DIR.'contact_class.php');

#get all contact detail 
$contactObj= new contact_class();

#find unvarify contact numver of user 
$unverifiedContact=$contactObj->getUnconfirmMobile($_SESSION["userid"]);

#find varified contact number
$vContactArr=$contactObj->getConfirmMobile($_SESSION["userid"]);
//var_dump($vContactArr);

#find country name 
function get_data($url) {
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

#find country name array
$url = "https://voip91.com/isoData.php";
$data = get_data($url);

function countryArray($data){
   
$string1 = json_decode($data, true);
for($i=0;$i<count($string1);$i++){
    $country[$string1[$i]['CountryCode']]=$string1[$i]['Country'];
    
} 
return $country;
}

$country = countryArray($data);
//var_dump($country);
?>
<div id="addemails" class="pd2">
<div class="setContainer">
    <h2>My Phone Number</h2>
    <ul class="ln mrT2" id="emailIdsList">
        <?php if(isset($unverifiedContact['contact_no'])){?>
        
        <li class="">
        	<span class="ic-24 actdelC cp"></span>
        	<p class="idname"><?php echo $unverifiedContact['contact_no'];?></p>
            <div class="mailact pr">
            	<i class="ic-16 wrong"></i>
            	<label>Unverified Number</label>
                <span class="alR"><a  href="javascript:void(0);"></a></span>
            </div>
            
            <div id="veribox">
                <p class="mrT2 mrB">
                	didn't get the code? resend code via <a class="themeLink" href="javascript:resendCode('call');">call</a> or
                    <a class="themeLink" href="javascript:resendCode('sms');">sms</a>
                </p>
                <div id="pinbox" class="clear">
                    <input type="text" name="key" id="key" />
                    <input class="btn mrL1 btn-medium btn-primary" type="button" name="verify" id="verify" value="Verify" onclick="varifyNumber();"/>
                </div>
            </div>
            
        </li>
        <?php }?>
        <?php 
        foreach($vContactArr as $vContact){
        $isDefault = ($vContact['defalult_no']==1) ? "Default" : "Make It Default";;
        $makeDefaultAction = ($vContact['defalult_no']==1) ? "Default" : "makeDefault(.'".$vContact['contact_no']."')";
?>
        <li class="default">
        	<p class="idname"><?php  echo $vContact['contact_no'] ;?></p>
            <div class="mailact pr">
            	<i class="ic-16 correct"></i>
            	<label>Verified Number</label>
                <span class="alR" id="contact<?php echo $vContact['contact_id']; ?>"><a onclick="makeDefault(this);" contactid="<?php echo $vContact['contact_id'];?>" ><?php echo $isDefault;?></a></span>
            </div>
            
            <span class="ic-24  cp"></span>
        </li>
      
        <?php }?>
        
        
    </ul>
    
    
    <a class="mrT2 mrB2 btn btn-large btn-primary btn-block clear alC" href="javascript:void(0)">
    	<div class="clear tryc">
            <span class="ic-24 addW"></span>
            <span>Add Phone Number</span>
        </div>
    </a>
    <form id="varify_contact">
					
    <div id="addAccbox">
    	<p class="mrT2 mrB">Choose Country</p>
        <div class="">
            <select name="country_code">
            	<option value="selectCountry">Select Country</option>
                <?php 
                foreach($country as $key =>$countryNames){                
                echo "<option value='$key'>$countryNames</option>";
                }?>
            </select>
        </div>
        <p class="mrT2 mrB">Contact #</p>
        <div id="mobwrap">
        	<input type="text" name="contact_code" />
            <input type="text" name="contact_no"/>
        </div>
        
        <input type="button" name="register" class="mrT2 mrB2 btn btn-large btn-primary btn-block clear alC" id="register" onclick="updateAnotherContact();" value="Update"/>
<!--        <a class="mrT2 mrB2 btn btn-large btn-primary btn-block clear alC" onclick="">
            <div class="tryc">
                <span class="ic-24 addW"></span>
                <span>Add</span>
            </div>
        </a>-->
    </div>
        </form>
</div>  
</div>
<script>
function updateAnotherContact(){
    var formData = $('#varify_contact').serialize();
       $.ajax({
	    url : "action_layer.php?action=update_newcontact",
	    type: "POST",dataType: "json",
	    data: formData,
	    
	}).done(function(text){
             show_message(text.msg,text.msgtype);
        }) 

        
}

function varifyNumber(){
var key = $('#key').val();
 $.ajax({
	    url : "action_layer.php?action=varifyNumber",
	    type: "POST",dataType: "json",
	    data: {key:key},
	    
	}).done(function(text){
             show_message(text.msg,text.msgtype);
         }) 

}
function makeDefault(ths){
var contactId = $(ths).attr('contactid');
$.ajax({
	    url : "action_layer.php?action=makeDefaultNumber",
	    type: "POST",dataType: "json",
	    data: {contactId:contactId},
	    
	}).done(function(text){
             show_message(text.msg,text.msgtype);
         }) 

}
</script>