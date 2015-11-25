/*
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  25 sep 2013
 * @package Phone91 / js 
 * 
 */

$(document).ready(function() { 
             
    // ajax request for add company name and domain name 
    var options = { 

            url:"controller/websiteController.php?action=addWebsite", 
            dataType: 'json',
            type:'POST',
            beforeSubmit:  addWebsiteRequest,  // pre-submit callback 
            success:function(text)
			{
				show_message(text.msg,text.status);
				if(text.status == "success")
                                {
                                    var domainName = $('#domainName').val();
                                    var companyName = $('#companyName').val();
                                    var language = $('#language').val();
                                    
                                   var str ='<li id="web_'+text.id+'" onclick="selectedWebLi(\''+domainName+'\',event,\''+text.id+'\')">\
                                    <div class="jh clear">\
                                    <p>'+companyName+'</p>\
                                    <p>'+language+'</p>\
                                    </div>\
                                    <h3 class="ellp">'+domainName+'</h3>\
                                    <p class="">Default Theme</p>\
                                    <div class="actwrap delBtn" onclick="DeleteWebsite(this);" websiteId="'+domainName+'">\
                                    <i class="ic-24 delR"></i>\
                                    </div>\
                                    </li>';
                                    
                                    
                                    
                                    $('#webList').prepend(str);
                                    selectedWebLi($('#domainName').val(),'',text.id);
                                }
			}
    }

    $("#addWebsite").ajaxForm(options); 
    // ajax request for add company name and domain name 
    var resoptions = { 

            url:"controller/websiteController.php?action=updateResellerTariff", 
            dataType: 'json',
            type:'POST',
//            beforeSubmit:  addWebsiteRequest,  // pre-submit callback 
            success:     
                    function(text)
                    {
                    show_message(text.msg,text.status);
//                    if(text.status == "success")
//                        selectedWebLi($('#domainName').val());
                    }
    }

    $("#tariffDetailsForm").ajaxForm(resoptions); 
    
    
     $().ready(function() {
        // validate the comment form when it is submitted   
        $("#domainDataForm").validate({
                rules: {
                        companyName :{
                            required: true,
                            maxlength: 40
                        },
                        domainName :{
                            required: true,
                            maxlength: 40
                        },
                        compEmail :{
                            required: true,
                            maxlength: 40
                        }
                        
                       }
        })
        $('#cName,#domainName,#compEmail').blur(function(){
    
            $("#"+$(this).attr('id')).valid();
        });

        
    })
    //ajex request for add general data of manage website 
    var domainOpt = { 

            url:"controller/websiteController.php?action=updateDomainDetails", 
            type:"POST",        
            dataType: 'json',
            beforeSubmit:  domainDataRequest,  // pre-submit callback 
            success:function(text)
            {
                show_message(text.msg,text.status);
            }
    }

    $("#domainDataForm").ajaxForm(domainOpt); 
    
    //ajex request for add general data of manage website 
    var generalOpt = { 

            url:"controller/websiteController.php?action=addGeneralData", 
            type:"POST",        
            dataType: 'json',
            beforeSubmit:  addGeneralRequest,  // pre-submit callback 
            success:     
			function(text)
			{
				show_message(text.msg,text.status);
			}
    }

    $("#generalDataForm").ajaxForm(generalOpt); 
    
    
     var homeOpt = { 

            url:"controller/websiteController.php?action=addHomeData", 
            type:"POST",        
            dataType: 'json',
            beforeSubmit: addHomeRequest,
            success:     
                    function(text)
                    {
                    show_message(text.msg,text.status);
                    }
    }

    $("#homeDataForm").ajaxForm(homeOpt); 
    
    
    
    var aboutOpt = { 

            url:"controller/websiteController.php?action=addAboutData", 
            type:"POST",        
            dataType: 'json',
            beforeSubmit: addAboutRequest,
            success:     
			function(text)
			{
			show_message(text.msg,text.status);
			}
    }

    $("#aboutDataForm").ajaxForm(aboutOpt); 
    
    
    
    
    
    var contactOpt = { 

            url:"controller/websiteController.php?action=addContacPageData", 
            type:"POST",        
            dataType: 'json',
            beforeSubmit: addContactRequest,
            success:     
                    function(text)
                    {
                    show_message(text.msg,text.status);
                    }
    }

    $("#contactDataForm").ajaxForm(contactOpt); 
    
    
    
    
    var pricingOpt = { 

            url:"controller/websiteController.php?action=addPricingData", 
            type:"POST",        
            dataType: 'json',
            beforeSubmit: addPricingRequest,
            success:     
                    function(text)
                    {
                    show_message(text.msg,text.status);
                    }
    }

    $("#pricingDataForm").ajaxForm(pricingOpt); 
    
    
});


function textOnlyValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
     if(/[^a-zA-Z ]+/.test(value))
         return false;
     else
         return true;
}
function textValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
//    console.log(/[^a-zA-Z0-9\.\_\-\@\$\%\&\|\s\,\:\?\!\;]+/.test(value));
     if(/[^a-zA-Z0-9\.\_\-\@\$\%\&\|\s\,\:\?\!\;]+/.test(value))
         return false;
     else
         return true;
}
function addressValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
     if(/[^a-zA-Z0-9\/\.\_\-\@\s]+/.test(value))
         return false;
     else
         return true;
}
function emailValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
     if(!(/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value)))
         return false;
     else
         return true;
}
$.validator.addMethod("textOnly", textOnlyValidation, "Please enter only alpha characters( a-z ).");
$.validator.addMethod("address", addressValidation, "Please enter only alphabets ands characters( /._-@ ).");
$.validator.addMethod("email", emailValidation, "Please enter proper email id ");
$.validator.addMethod("validText", textValidation, "Please enter valid text ");

$(document).ready(function() {
        // validate the comment form when it is submitted   
        $("#addWebsite").validate({
                rules: {
                        companyName :{
                            required: true,
                            maxlength: 40
                        },
                        domainName :{
                            required: true,
                            maxlength: 40
                        },
                        compEmail:{
                            required:true,
                            maxlength:40
                        }
//                        theme :{
//                            required: true,
//                            maxlength: 20
//                        }
                        
                       }
        });
        $('#companyName,#domainName,#compEmail').blur(function(){
    
            $("#"+$(this).attr('id')).valid();
        });


    });
/*
 * created by sudhir pandey <sudhir@hostnsoft.com>
 * creation date 25-09-2013
 * function use for check all velidation of add website form before submit ajex form
 */        
function addWebsiteRequest(formData, jqForm, options){
  
            if($("#addWebsite").valid())
                    return true; 
            else
                    return false;

} 
function domainDataRequest(formData, jqForm, options){
  
  $.validator.setDefaults({
  submitHandler: function() { console.log("submitted!"); }
  });
   
           
            if($("#domainDataForm").valid())
                    return true; 
            else
                    return false;

} 



/**
 * @author <sameer rathod>
 * @param {type} formData
 * @param {type} jqForm
 * @param {type} options
 * @returns {Boolean}
 */
function addGeneralRequest(formData, jqForm, options){
    
  $.validator.setDefaults({
  	submitHandler: function() { console.log("submitted!"); }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#generalDataForm").validate({
                rules: {
                        facebook :{
                            //required: true
                            url: true,
                            maxlength:80
                            },
                        twitter :{
                            //required: true
                            url: true,
                            maxlength:80
                            },
                        linkedin :{
                            //required: true
                            url: true,
                            maxlength:80
                            },
                        gplus :{
                            //required: true
                            url: true,
                            maxlength:80
                            },
                        address :{
                            //required: true
                            address:true,
                            maxlength:200
                            },
                        phoneNo :{
                            //required: true
                            number:true,
                            minlength:7,
                            maxlength:18
                            },
                        emailId :{
                            //required: true
                            email:true,
                            maxlength:40
                            }
                        
                        
                       }
        })
        
    })
           
            if($("#generalDataForm").valid())
                    return true; 
            else
                    return false;
}

/**
 * @author <sameer rathod>
 * @param {type} formData
 * @param {type} jqForm
 * @param {type} options
 * @returns {Boolean}
 */
function addHomeRequest(formData, jqForm, options){
    
    $.validator.setDefaults({
  submitHandler: function() { console.log("submitted!"); }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#homeDataForm").validate({
                rules: {
                        title :{
                            //required: true
//                            validText: true,
                            maxlength:80
                            },
                        mKeyword :{
                            //required: true
//                            validText: true,
                            maxlength:300
                            },
                        mDescription :{
                            //required: true
//                            validText: true,
                            maxlength:250
                            },
                        heading :{
                            //required: true
//                            validText: true,
                            maxlength:80
                            },
                        subHeading :{
                            //required: true
//                            validText:true,
                            maxlength:250
                            },
                        text :{
                            //required: true
//                            validText:true,
                             maxlength: 300
                            
                            },
                        link :{
                            //required: true
                            url:true,
                            maxlength:80
                            },
                        welcomeContent :{
                            //required: true
//                            validText:true,
                             maxlength: 300
                            }
                        
                        
                       }
        })
        
    })
           
            if($("#homeDataForm").valid())
                    return true; 
            else
                    return false;
}

/**
 * @author <sameer rathod>
 * @param {type} formData
 * @param {type} jqForm
 * @param {type} options
 * @returns {Boolean}
 */
function addAboutRequest(formData, jqForm, options){
    
    $.validator.setDefaults({
  submitHandler: function() { console.log("submitted!"); }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#aboutDataForm").validate({
                rules: {
                        title :{
                            //required: true
//                            validText: true,
                            maxlength:80
                            },
                        mKeyword :{
                            //required: true
//                            validText: true,
                            maxlength:300
                            },
                        mDescription :{
                            //required: true
//                            validText: true,
                            maxlength:250
                            },
                        heading :{
                            //required: true
//                            validText: true,
                            maxlength:80
                            },
                        subHeading :{
                            //required: true
//                            validText:true,
                            maxlength:250
                            },
                        text :{
                            //required: true
//                            validText:true,
                             maxlength: 300
                            },
                        link :{
                            //required: true
                            url:true,
                            maxlength:80
                            },
                        whoUR :{
                            //required: true
//                            validText:true,
                             maxlength: 300
                            },
                        vision :{
                            //required: true
//                            validText:true,
                             maxlength: 300
                            },
                        mission :{
                            //required: true
//                            validText:true,
                             maxlength: 300
                            }
                        
                        
                       }
        })
        
    })
           
            if($("#aboutDataForm").valid())
                    return true; 
            else
                    return false;
}

/**
 * @author sameer rathod
 * @param {type} formData
 * @param {type} jqForm
 * @param {type} options
 * @returns {Boolean}
 */
function addPricingRequest(formData, jqForm, options){
    
    $.validator.setDefaults({
  submitHandler: function() { console.log("submitted!"); }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#pricingDataForm").validate({
                rules: {
                        title :{
                            //required: true
//                            validText: true,
                            maxlength:80
                            },
                        mKeyword :{
                            //required: true
//                            validText: true,
                            maxlength:300
                            },
                        mDescription :{
                            //required: true
//                            validText: true,
                            maxlength:250
                            },
                        heading :{
                            //required: true
//                            validText: true,
                            maxlength:80
                            },
                        subHeading :{
                            //required: true
//                            validText:true,
                            maxlength:250
                            },
                        text :{
                            //required: true
//                            validText:true,
                             maxlength: 300
                            },
                        link :{
                            //required: true
                            url:true,
                            maxlength:80
                            }
                        
                        
                       }
        })
        
    })
           
            if($("#pricingDataForm").valid())
                    return true; 
            else
                    return false;
}
/**
 * @author sameer rathod
 * @param {type} formData
 * @param {type} jqForm
 * @param {type} options
 * @returns {Boolean}
 */
function addContactRequest(formData, jqForm, options){
    
    $.validator.setDefaults({
  submitHandler: function() { console.log("submitted!"); }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#contactDataForm").validate({
                rules: {
                        title :{
                            //required: true
//                            validText: true,
                            maxlength:80
                            },
                        mKeyword :{
                            //required: true
//                            validText: true,
                            maxlength:300
                            },
                        mDescription :{
                            //required: true
//                            validText: true,
                            maxlength:250
                            },
                        heading :{
                            //required: true
//                            validText: true,
                            maxlength:80
                            },
                        subHeading :{
                            //required: true
//                            validText:true,
                            maxlength:250
                            },
                        text :{
                            //required: true
//                            validText:true,
                             maxlength: 300
                            },
                        link :{
                            //required: true
                            url:true,
                            maxlength:80
                            },
                        contactFormEmail :{
                            //required: true
                            email:true,
                            maxlength:40
                            },
                        gMapEmbededCode :{
                            //required: true
//                            validText:true,
                             url:true,
                             maxlength: 400
                            }
                        
                        
                       }
        })
        
    })
           
            if($("#contactDataForm").valid())
                    return true; 
            else
                    return false;
}


function deleteBankDiv(ths){
	if($('.bankdetail-data').length > 1)
	   $(ths).parent().remove();
	else
		$('input',$(ths).parent()).val('');
}


function addMoreDetail(){
    
    var divdesign = '<div class="bankdetail-data fl">\
                    <div class="inprow"><label class="lblLeft">Bank Name</label><input type="text" name="bankName[]" class="bankName" /></div>\
                    <div class="inprow"><label class="lblLeft">IFSC Code</label><input type="text" name="ifsc[]" class="ifsc"/></div>\
                    <div class="inprow"><label class="lblLeft">Account No.</label><input type="text" name="accountNo[]" class="accountNo"/></div>\
                    <div class="inprow"><label class="lblLeft">Account Name</label><input type="text" name="accountName[]" class="accountName" /></div>\
                    <div class="actwrap fr" onclick="deleteBankDiv(this);"><i class="ic-24 delR"></i></div>\
                    </div><div class="cl"></div>';
    
    $('.addmoreDetaillink').before(divdesign);
    
}
        
        