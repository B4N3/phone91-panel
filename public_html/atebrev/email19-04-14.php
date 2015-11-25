<?php

//Include Common Configuration File First
include_once('config.php');
include_once(CLASS_DIR.'contact_class.php');

#get all contact detail 
$contactObj= new contact_class();

#find unverify contact numver of user 
$unverifiedEmali=$contactObj->getUnConfirmEmail($_SESSION["userid"]);

#find verified contact number
$vContactArr=$contactObj->getConfirmEmail($_SESSION["userid"]);

?>
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<div id="addemails">
<div class="setContainer">
    		
			  
    <!--Left Phone side-->
    
   <div class="leftPhone settRightSec fl mrR1">
	   <a class="btn  btn-primary btn-medium clear alC phoneaddNo email" href="javascript:showAddAccbox()" title="Add E-mail">
			<div class="clear tryc">
					<span class="ic-24 addW"></span>
					<span>Add E-mail</span>
			</div>
	  </a>
   	<div id="addAccbox">
        <form id="otherEmail">
<!--    	<p class="mrT2 mrB">Type</p>
        <div class="">
            <select name="">
            	<option>Select Account</option>
                <option>Skype</option>
                <option>Gtalk</option>
                <option>Bingo</option>
            </select>
        </div>-->
                
                <p class="mrB">New Email</p>
        <div class="">
            <input type="text"  id="newemail" name="newEmail"/>
        </div>
        <p class="mrT2 mrB">Confirm New Email</p>
        <div class="">
            <input type="text" id="confirmemail"  name="confirmEmail" />
        </div>
        
                <a class="mrT2 btn  btn-medium btn-primary  clear alC IsInput30" onclick="otherEmailAdd();" title="Add" id="addEmailBtn">
            <div class="tryc tr2">
                <span>Add</span>
            </div>
        </a>
        </form>
    </div>
            	<ul class="ln listInSett" id="emailIdsList">
        <?php 
        if (count($vContactArr) != 0){
        foreach($vContactArr as $vContact){
            
        $vContactEmail = $vContact['email'];
        $isDefault = ($vContact['default_email']==1) ? "Default" : "Make It Default";;
     
  ?>
       <li class="idn" id="ide<?php echo $vContact['verifiedEmail_id'];?>" >
       <p class="idname"  id="vContact"><?php  echo $vContact['email'];  ?></p> 
            <div class="mailact pr mrT1">
				<div class="alR fr">
					<a onclick="makeDefaultemail(this);" id="email<?php echo $vContact['verifiedEmail_id'];  ?>" class="emailstatus cp" listid="<?php echo $vContact['verifiedEmail_id'];?>" ><?php echo $isDefault; ?></a>
			   </div>
								   
            	<!--<p class="acType">Gtalk</p>-->
            	<i class="ic-16 correct"></i>
       <label>Verified email</label> 
                                    
       </div> 
                
                
     <!--modified by balachandra<balachandra@hostnsoft.com>
      date 02/08/2013 -->
                            <span class="ic-24 actdelC cp"  onclick="deleteEmail(this);" listid="<?php echo $vContact['verifiedEmail_id'];?>" title="Delete"></span> 
            </li>
        <?php }}?>
            <?php if(isset($unverifiedEmali['email'])){?>
        <li class="unverify" id="unverify" >
            
        	<span class="ic-24 actdelC cp" onclick="deleteunverifyEmail(this);" tempid="<?php echo $unverifiedEmali['email']; ?>"></span>
        	<p class="idname"><?php echo $unverifiedEmali['email']; ?></p>
            <div class="mailact pr">
            	<!--<p class="acType">Gtalk</p>-->
            	<i class="ic-16 wrong"></i>
            	<label>Unverified email</label>
                <span class="alR"></span>
            </div>
            <input type="hidden" id="resend_emailid" value="<?php echo $unverifiedEmali['email']; ?>"/>
            <div id="veribox">
                <p class="mrT2 mrB">didn't get the code <a class="themeLink" onclick="resendEmailVCode()">resend</a></p>
                <div id="pinbox" class="clear">
                    <input type="text" name="emailkey" id="emailkey" />
                    <input class="btn mrL1 btn-medium btn-primary" type="button" name="verify" id="verify" value="Verify" onclick="verifyemail();"/>
                </div>
            </div>
           
        </li>
         <?php } ?>
    </ul>
        </div>
    	<!--//Left Phone side-->
        <!--Right Phone side-->
        <div class="rightPhone fixed fl"> 
    
</div>  
</div>
    <!--//Inner Container-->
</div>
<!--//Email Wrapper-->
<script type="text/javascript">
dynamicPageName('Email IDs');
slideAndBack('.slideLeft','.slideRight');
function otherEmailAdd(){
    var formData = $('#otherEmail').serialize();
   if( validate() == true )
   {
       $.ajax({
	    url : "action_layer.php?action=update_newEmail",
	    type: "POST",dataType: "json",
	    data: formData,
	    success:function(text){
             show_message(text.msg,text.msgtype);
             if(text.msgtype == "success"){
             var str =' <li class="unverify" id="unverify" >\
              <span class="ic-24 actdelC cp" onclick="deleteunverifyEmail(this);" tempid="'+text.unverifiedEmail.email+'"></span>\
                        <p class="idname">'+text.unverifiedEmail.email+'</p>\
                        <div class="mailact pr">\
                        <!--<p class="acType">Gtalk</p>-->\
                        <i class="ic-16 wrong"></i>\
                        <label>Unverified email</label>\
                        <span class="alR"></span>\
                        </div>\
                        <input type="hidden" id="resend_emailid" value="'+text.unverifiedEmail.email+'"/>\
                        <div id="veribox">\
                        <p class="mrT2 mrB">did not get the code <a class="themeLink" onclick="resendEmailVCode()">resend</a></p>\
                        <div id="pinbox" class="clear">\
                            <input type="text" name="emailkey" id="emailkey" />\
                       <input class="btn mrL1 btn-medium btn-primary" type="button" name="verify" id="verify" value="Verify" onclick="verifyemail();"/>\
                       </div>\
                       </div>\
                       </li>';
                                           
                
                $('#emailIdsList').append(str);
                                           
               }                            
             
        }
	})
   }
}
function verifyemail(){
var key = $('#emailkey').val();

 $.ajax({
	    url : "action_layer.php?action=verifyEmailid",
	    type: "POST",dataType: "json",
	    data: {key:key},
	    success: function(text){
                show_message(text.msg,text.msgtype);
                
                var str = '';
                if(text.msgtype == "success"){
                 $.each( text.confirmEmail, function(key, item ) {
                 var isDefault = (item.default_email == 1) ? "Default" : "Make It Default";
                 
                 str += '<li class="idn" id="'+item.verifiedEmail_id+'">\
                         <p class="idname"  id="vContact">'+item.email+'</p>\
                         <div class="mailact pr">\
                         <i class="ic-16 correct"></i>\
                             <label>Verified email</label>\
                             <span class="alR">\
                             <a onclick="makeDefaultemail(this);" id="email'+item.verifiedEmail_id+'" class="emailstatus cp" listid="'+item.verifiedEmail_id+'" >'+ isDefault+'</a>\
                             </span>\
                             </div>\
                             <span class="ic-24 actdelC cp"  onclick="deleteEmail(this);" listid="'+item.verifiedEmail_id+'" title="Delete"></span>\
                             </li>';
                    })

                     $('#emailIdsList').html('');
                     $('#emailIdsList').html(str);
                    }
                                 
                                 
                                 
                
            }
	})
}
function makeDefaultemail(ths){
var emailId = $(ths).attr('listid');
$.ajax({
	    url : "action_layer.php?action=makeDefaultemail",
	    type: "POST",
	    data: {emailId:emailId},
	    success: function(text) {
                $('.emailstatus').html('');
                $('.emailstatus').html('Make It Default')
                $('#email'+emailId).html('');
                $('#email'+emailId).html('Default')
                
            }
	});

}

function resendEmailVCode(){
 var email = $("#resend_emailid").val();
   $.ajax({
		type: "POST",
		url: "action_layer.php?action=resendEmailConfirm_code",  //"update_contact.php",
		data: {email: email}, dataType: "json",
		success: function(text)
		{
 		    show_message(text.msg,text.msgtype);

		}
	    });
	

}


// created by Balachandra<balachandra@hostnsoft.com>
//date 02/08/2013


function deleteEmail(ths)
{
    
//getting the id of each emailaddress   
var emailId = $(ths).attr('listid');


$.ajax({
	    url : "action_layer.php?action=deleteEmailId",
	    type: "POST",
            dataType: "json",
	    data: {emailId:emailId},
	    success: function(text)
            {
                show_message(text.msg,text.msgtype);
                //if deleted then take hide action
                if(text.msgtype == "success")
                {
                    //connecting the id=ide and email id number 
                    $('#ide'+emailId).remove();
                    
                    
                    
                }
            
            }
       }); 
}

function deleteunverifyEmail(ths)
{
    
//getting the id of each emailaddress   
var unverifyid = $(ths).attr('tempid');


$.ajax({
	    url : "action_layer.php?action=deleteunverifyemail",
	    type: "POST",
            dataType: "json",
	    data: {unverifyid:unverifyid},
	    success: function(text)
            {
                show_message(text.msg,text.msgtype);
                //if deleted then take hide action
                if(text.msgtype == "success")
                {
                    //connecting the id=ide and email id number 
                    $('#unverify').remove();
                    
                    
                    
                }
            
            }
       }); 
}
//created by Balachandra<balachandra@hostnsoft.com>
//date: 05-08-2013
//validation from the client side
function validate()
{
    //entered new email address
    var newemail=$('#newemail').val();
    // regular expression for email
    var reg=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    // entered confirm email address
    var confirmemail=$('#confirmemail').val();
    //if new mail id and confirm mail id present 
    if(newemail !="" && confirmemail !="")
        {
          if(newemail.length > 40)
          {
              show_message('Maximum length for email is 40 characters!!!','error');
              return false;
          }    
          //if regular expression  matched  
          if(reg.test(newemail) && reg.test(confirmemail))
                {
                  //both mail ids are same   
                  if(newemail === confirmemail)
                  {
                    return true; 
                  }
                 
                  else show_message('Email Mismatch !','error');
                }
          else show_message('Please Provide Valid Email ','error');
        }
     else show_message('Please Enter Email','error');   
    
    return false;
    
}
</script>

