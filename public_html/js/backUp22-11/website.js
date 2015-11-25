/*
 * @author Sudhir Pandey <sudhir@hostnsoft.com>
 * @since  25 sep 2013
 * @package Phone91 / js 
 * 
 */
 

$(function() {
	$( "#tabs" ).tabs({
		create: function(event, ui) {console.log($('.ui-tabs-selected a',this).text())},
		select: function(event, ui) {
		console.log(ui.tab.innerText)}
	});

});


$(document).ready(function() { 
                
    // ajex request for add company name and domain name 
    var options = { 

            url:"controller/websiteController.php?action=addWebsite", 
            dataType: 'json',
            beforeSubmit:  addWebsiteRequest,  // pre-submit callback 
            success:     
                    function(text)
                    {
                    show_message(text.msg,text.status);
                    }
    }

    $("#addWebsite").ajaxForm(options); 
    
    
    
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
            success:     
                    function(text)
                    {
                    show_message(text.msg,text.status);
                    }
    }

    $("#pricingDataForm").ajaxForm(pricingOpt); 
    
    
});


/*
 * created by sudhir pandey <sudhir@hostnsoft.com>
 * creation date 25-09-2013
 * function use for check all velidation of add website form before submit ajex form
 */        
function addWebsiteRequest(formData, jqForm, options){
  
  $.validator.setDefaults({
  submitHandler: function() { console.log("submitted!"); }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#addWebsite").validate({
                rules: {
                        companyName :{
                            required: true,
                            maxlength: 20
                        },
                        domainName :{
                            required: true,
                            maxlength: 20
                        },
                        theme :{
                            required: true,
                            maxlength: 20
                        }
                        
                       }
        })
        
    })
           
            if($("#addWebsite").valid())
                    return true; 
            else
                    return false;

} 

/*
 * created by sudhir pandey <sudhir@hostnsoft.com>
 * creation date 28-09-2013
 * function use for check all velidation of add website form before submit ajex form
 */    
function addGeneralRequest(formData, jqForm, options){
    
    $.validator.setDefaults({
  submitHandler: function() { console.log("submitted!"); }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#generalDataForm").validate({
                rules: {
                        logoFile :{
                            //required: true
                            }
                        
                        
                       }
        })
        
    })
           
            if($("#generalDataForm").valid())
                    return true; 
            else
                    return false;
}


function deleteBankDiv(ths){
   $(ths).parent().remove();
}


function addMoreDetail(){
    
    var divdesign = '<div class="bankdetail-data">\
                    <div class="inprow"><label>Bank Name</label><input type="text" name="bankName[]" class="bankName" /></div>\
                    <div class="inprow"><label>IFSC Code</label><input type="text" name="ifsc[]" class="ifsc"/></div>\
                    <div class="inprow"><label>Account No.</label><input type="text" name="accountNo[]" class="accountNo"/></div>\
                    <div class="inprow"><label>Account Name</label><input type="text" name="accountName[]" class="accountName" /></div>\
                    <div class="actwrap" onclick="deleteBankDiv(this);"><i class="ic-24 delR"></i></div>\
                    </div>';
    
    $('.addmoreDetaillink').before(divdesign);
    
}
        
        