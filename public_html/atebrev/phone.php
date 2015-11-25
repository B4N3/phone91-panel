<?php
//Include Common Configuration File First
include_once('config.php');
//Validate Login with the help of this function 
if (!$funobj->login_validate()) {
    $funobj->redirect("index.php");
}
include_once(CLASS_DIR . 'contact_class.php');
#get all contact detail 
$contactObj = new contact_class();
#find unverify contact numver of user 
$unverifiedContact = $contactObj->getUnconfirmMobile($_SESSION["userid"]);
#find verified contact number
$vContactArr = $contactObj->getConfirmMobile($_SESSION["userid"]);
//var_dump($vContactArr);
$country = $funobj->countryArray();
//var_dump($country);
//print_r($vContactArr);
?>
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<!--Phone Wrapper-->
<div id="addemails">
    <!--Inner Container-->
    <div class="setContainer">
        <!--Left Phone side-->
        <div class="leftPhone settRightSec fl mrR1">
			<a class="btn  btn-primary btn-medium clear alC phoneaddNo" href="javascript:showAddAccbox()" title="Add Phone Number">
			<div class="clear tryc">
				<span class="ic-24 addW"></span>
				<span>Add Phone Number</span>
			</div>
			</a>
			<form id="verify_contact">
                <div id="addAccbox">
                    <p class="mrB">Choose Country</p>
                    <div class="">
                        <select name="country_code" id="countrySelect">
                            <option value="selectCountry">Select Country</option>
                            <?php
                            foreach ($country as $key => $countryNames) {
                                echo "<option value='$key'>$countryNames</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <p class="mrT2 mrB">Contact #</p>
                    <div id="mobwrap">
                        <input type="text" name="contact_code" id="country_code" onblur="selectOption($(this).val())"/>
                        <input type="text" name="contact_no" id="numb"/>
                    </div>
                    <input type="button" name="register" class="btn  btn-medium btn-primary mrT2 clear alC" id="register" onclick="updateAnotherContact();" value="Add" title="Add"/>
                    <!--<a class="mrT2 mrB2 btn btn-large btn-primary btn-block clear alC" onclick="">
                            <div class="tryc">
                                <span class="ic-24 addW"></span>
                                <span>Add</span>
                            </div>
                    </a>-->
                </div>
            </form>
			
            <ul class="ln listInSett" id="numbersList" >
                <?php
                foreach ($vContactArr as $vContact) {
                    //            var_dump($vContact);
                    $isDefault = ($vContact['isDefault'] == 1) ? "Default" : "Make It Default";
                    
                    $makeDefaultAction = ($vContact['isDefault'] == 1) ? "Default" : "makeDefault('" . $vContact['verifiedNumber'] . "')";
                    if ($vContact > 0) {
                        ?>
                        <li class="default" id="idc<?php echo $vContact['varifiedNumber_id']; ?>">
                            <p class="idname"><?php echo $vContact['countryCode']; ?><?php echo $vContact['verifiedNumber']; ?></p>
                            <div class="mailact pr mrT1">
								<div class="alR fr" >
                                    <a onclick="makeDefault(this);" id="contact<?php echo $vContact['varifiedNumber_id']; ?>" class="contactstatus cp" contactid="<?php echo $vContact['varifiedNumber_id']; ?>" > <?php echo $isDefault; ?></a>
                                </div>
                                <i class="ic-16 correct"></i>
                                <label>Verified Number</label>                                
                            </div>
                            <span class="ic-24 actdelC cp" onclick="deletephone(this,<?php echo $vContact['varifiedNumber_id']; ?>);" contactid="<?php echo $vContact['verifiedNumber_id']; ?>" title="Delete"> </span>
                        </li>
                    <?php }
                } ?>

<?php if (isset($unverifiedContact['tempNumber'])) { ?>
                    <li class="unverify" id="unverify<?php echo $unverifiedContact['tempNumber']; ?>" >
                        <span class="ic-24 actdelC cp" onclick="deletephoneunverify(this,<?php echo $unverifiedContact['tempNumber']; ?>);" tempid="<?php echo $unverifiedContact['tempNumber']; ?>" ></span> 
                        <p class="idname"><?php echo $unverifiedContact['countryCode']; ?><?php echo $unverifiedContact['tempNumber']; ?></p>
                        <div class="mailact pr mrT1">
                            <i class="ic-16 wrong"></i>
                            <label>Unverified Number</label>
                            <span class="alR"></span>
                        </div>
                        <div id="veribox">
                            <p class="mrT1 mrB">
                                <!--didn't get the code? resend code via-->  
                                	<span class="smallF">Resend Code via</span>
                                    <a class="themeLink" id="callCode" href="javascript:callme_code();">call</a>
                                    or
                                    <a class="themeLink" id="smsSend" href="javascript:resend_code();">sms</a>
                            </p>
                            <div id="pinbox" class="clear">
                                <input type="hidden" id="resend_code" value="<?php echo $unverifiedContact['countryCode']; ?>"/>
                                <input type="hidden" id="resend_phone" value="<?php echo $unverifiedContact['tempNumber']; ?>"/>
                                <input type="hidden" id="resend_phone1" value="<?php echo $unverifiedContact['tempNumber']; ?>"/>
                                <input type="text" name="key" id="key" />
                                <input class="btn  btn-medium btn-primary" type="button" name="verify" id="verify" value="Verify" onclick="verifyNumber();" title="Verify"/>
                            </div>
                        </div>

                    </li>
			<?php } ?>
            </ul>
        </div>
        <!--//Left Phone side-->

        
        <!--<div class="rightPhone fixed fl">
            
        </div>-->
        

        <div id="dialog-deleteConf" title="Confirm" style="display : none;">Are you sure you want to delete this number </div>
        
    </div>  
    <!--//Inner Container-->
</div>
<!--Phone Wrapper-->
<script type="text/javascript"> 
 dynamicPageName('Phone Numbers')
 slideAndBack('.slideLeft','.slideRight');
    function selectOption(valu)
    {
        $('#countrySelect option[value="'+valu+'"]').prop('selected',true);
    }
    function updateAnotherContact(){
        var formData = $('#verify_contact').serialize();
        if(validate() == true){
            $.ajax({
                url : "action_layer.php?action=update_newcontact",
                type: "POST",dataType: "json",
                data: formData,
            success:function(text){
                console.log(text);
                show_message(text.msg,text.msgtype);
                if(text.msgtype == "success"){
                    var str = designUnVarifiedcontact(text.unverifiedContact);
                     $('#numbersList').append(str);   
                }
            }
        }) 
    }
    }
    function designUnVarifiedcontact(contact){
        var str = '<li class="unverify" id="unverify'+contact.tempNumber+'" >\
        <span class="ic-24 actdelC cp" onclick="deletephoneunverify(this,'+contact.tempNumber+')" tempid="'+contact.tempNumber +'" ></span>\
               	<p class="idname">'+contact.countryCode +''+ contact.tempNumber +'</p>\
                    <div class="mailact pr">\
                     <i class="ic-16 wrong"></i>\
                     <label>Unverified Number</label>\
                     <span class="alR"></span>\
                    </div>\
                    <div id="veribox">\
                           <p class="mrT2 mrB">\
                        	 Did not get the code? resend code via <a class="themeLink" id="callCode" href="javascript:callme_code();">call</a> or \
                        <a class="themeLink" id="smsSend" href="javascript:resend_code();">sms</a>\
                        </p>\
                        <div id="pinbox" class="clear">\
							<input type="hidden" id="resend_code" value="'+contact.countryCode+'"/>\
							 <input type="hidden" id="resend_phone" value="'+ contact.tempNumber +'"/>\
							<input type="hidden" id="resend_phone1" value="'+contact.tempNumber+'"/>\
							<input type="text" name="key" id="key" />\
                   			<input class="btn mrL1 btn-medium btn-primary" type="button" name="verify" id="verify" value="Verify" onclick="verifyNumber();"/>\
                    </div>\
                    </div>\
                                </li>';
                            return str;
                        }
                        function verifyNumber(){
                            var key = $('#key').val();
                            if(key ==""){
                            show_message('Please provide code ','error');
                                return;
                            }
                            $.ajax({
                                url : "action_layer.php?action=verifyNumber",
                                type: "POST",dataType: "json",
                                data: {key:key},
                                success:function(text){
                                 show_message(text.msg,text.msgtype);   
                                 
                                var str = '';
                                if(text.msgtype == "success"){
                                 $.each( text.confirmNo, function(key, item ) {
                                 var isDefault = (item.isDefault == 1) ? "Default" : "Make It Default";
                                 var makeDefaultAction = (item.isDefault == 1) ? "Default" : "makeDefault('" +item.verifiedNumber+"')";
                                 str +='<li class="default" id="idc'+item.varifiedNumber_id+'">\
                                        <p class="idname">'+item.countryCode+''+item.verifiedNumber+'</p>\
                                        <div class="mailact pr">\
                                        <i class="ic-16 correct"></i>\
                                        <label>Verified Number</label>\
                                        <span class="alR " >\
                                        <a onclick="makeDefault(this);" id="contact'+item.varifiedNumber_id+'" class="contactstatus cp" contactid="'+item.varifiedNumber_id+'" > '+isDefault+'</a>\
                                        </span>\
                                        </div>\
                                        <span class="ic-24 actdelC cp" onclick="deletephone(this,'+item.varifiedNumber_id+');" contactid="'+item.verifiedNumber_id+'" title="Delete"> </span>\
                                        </li>';
                   
                                    })
                                    
                                     $('#numbersList').html('');
                                     $('#numbersList').html(str);
                                    }
                                 
                                 
                                 
                                 
                                }
	    
                            })
                        }
                        function makeDefault(ths){
                         if($.trim($(ths).html()) == "Default")
                            return false;
                            var contactId = $(ths).attr('contactid');
                            $.ajax({
                                url : "action_layer.php?action=makeDefaultNumber",
                                type: "POST",
                                data: {contactId:contactId},
                                success: function(text) {
                                    $('.contactstatus').html('');
                                    $('.contactstatus').html('Make It Default')
                                    $('#contact'+contactId).html('');
                                    $('#contact'+contactId).html('Default')
                
                                }
                            });
                        }
                        //$(document).on(events, selector, data, handler);
                        $("#countrySelect").on('change',function(event){
                            $("#country_code").val($(this).val().replace(/ /g,''));
  
                        })
</script>
<script language="javascript" type="text/javascript">
    $(document).ready(function() {

        //function to resend code
        resend_code = function() {
            $('#resend_me_code').html('Please Wait...');
            var code = $("#resend_code").val();
            var phone = $("#resend_phone1").val();
          
            
            $('#smsSend').attr('href','javascript:void(0)');
          
            $.ajax({
                type: "POST",
                url: "action_layer.php?action=resendConfirm_code",  //"update_contact.php",
                data: {country_code: code, resend_phone: phone},
                dataType: "JSON",
                success: function(msg)
                {

                    $('#resend_me_code').unbind('click');
                    show_message(msg.msg,msg.status)
                    if(msg.status == "success"){
                    setTimeout(function(){ $('#smsSend').attr('href','javascript:resend_code()') },10000);
                    }else
                      $('#smsSend').attr('href','javascript:resend_code()');  
                }
            });
    };

    //code to resend voice code
    callme_code = function() {
        $('#call_me_code').html('Please Wait...');
        var code = $("#resend_code").val();
        var phone = $("#resend_phone1").val();

        $('#callCode').attr('href','javascript:void(0)');
        console.log(code + phone);
        $.ajax({
            type: "POST",
            url: "action_layer.php?action=callmeConfirm_code", 
            data: {country_code: code, resend_voice: phone},
            dataType:"JSON",
            success: function(msg)
            {

                $('#call_me_code').unbind('click');
                //check if msg greater than zero,then so remaining attemtps
                show_message(msg.msg,msg.msgtype);
                if(msg.msgtype == "success"){
                    setTimeout(function(){ $('#callCode').attr('href','javascript:callme_code()') },10000);
                    }else
                      $('#callCode').attr('href','javascript:callme_code()');  
            }
        });
    };

    //call function on click
    $("#resend_me_code1").click(resend_code);
    $("#call_me_code").click(callme_code);
    $('#new_contact').ajaxForm({beforeSubmit: validate, success: jsonResponse});

    $('#confirm_form1').ajaxForm({success: showResponse});
});

function jsonResponse(responseText, statusText, xhr, $form)
{

    $("#midright").html("<img src='images/loading.gif' />").load("inc/my_setting.php?active=contact");
}
function showResponse(responseText, statusText, xhr, $form)
{
    //alert(responseText);
    $("#midright").html("<img src='images/loading.gif' />").load("inc/my_setting.php?active=contact");
}

function confirmdelete()
{
    //if(document.getElementById("delete").value=="nocountry")
    if(confirm("Are you sure to delete")){
        delete_phoneno($("#unverified").val()); 
        $("#unverifiedDiv").replaceWith('<div class="fs2 mrB">Add  Contact</div><form><label class="" >Phone Number</label><input type="text" style="width:50px; margin-right:10px;" id="code" value="" placeholder="code" name="code"><div class="mrL1 fl lh30"></div><input type="text" name="mobileNumber" id="mobileNumber" value="" placeholder="mobileNumber"/><div class="mrL1 fl lh30"></div><input type="button" class="btn grnBtn" value="Update" id="register" name="register" onclick="updateContact(1);" >');
    }else
        return false;
}
    
    
//created by Balachandra<balachandra@hostnsoft.com>
//date: 05-08-2013

function deletephone(ths, id)
{
    
      $( "#dialog-deleteConf" ).dialog({
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            "Sure":  {
                text:"Sure",
                "class":"btn  btn-primary btn-medium",
				title:"Sure",
                click:function(){
                    $( this ).dialog( "close");
                    deletephoneNo(ths,id);
                }
            },
            Cancel:  {
                text:"Cancel",
                "class":"btn btn-danger btn-medium",
				title:"Cancel",
                click:function(){
                $( this ).dialog( "close");
                }
            }
            
    }
        });
 
}

function deletephoneNo(ths,id){

        $.ajax({
            url : "action_layer.php?action=deletephone",
            type: "POST",
            dataType: "json",
            data: {id:id},
            success: function(text)
            {
                show_message(text.msg,text.msgtype);
                if(text.msgtype == "success"){
                    //connecting the id=ide and email id number 
                    $('#idc'+id).remove();

                }

            }
        });
    
}

function deletephoneunverify(ths,tempid){
    if(confirm("Are you sure to delete")){

        $.ajax({
            url : "action_layer.php?action=deleteunverifyphone",
            type: "POST",
            dataType: "json",
            data: {tempid:tempid},
            success: function(text)
            {
                show_message(text.msg,text.msgtype);
                if(text.msgtype == "success"){
                    //connecting the id=ide and email id number 
                    $('#unverify'+tempid).remove();

                }

            }
        });
    }
}


function validate()
{
    //access the country variable from the form
    var country=$('#country_code').val();
    //access the number from the form
    var numb=$('#numb').val();
    //regular expression for number
    var reg=/^[0-9]+$/;
    //if country field is not empty
    if( country !== "")
    {
        //if numb field is not empty and matching with reg
        if(numb !== "" && reg.test(numb))
        {
            return true;
        }
        else  show_message('Enter The Proper Contact Number','error');
    }
    else { show_message('Please Select Country ','error'); }
        
    return false;

}
</script>

