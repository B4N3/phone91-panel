<?php

//var_dump($_REQUEST);

 $counter = 0;
 if(isset($_REQUEST['contact']))
{
   $counter = count($_REQUEST['contact']);
  
}
    
?>
<div id="add-contact-dialog" class="dn" title="Import New Contact">
				</div>
      </div>
<script type="text/javascript" src="/js/contact.js"></script>       
<script type="text/javascript">


var count = 0;
count = <?php if(isset($counter))echo (int)$counter;else echo 0; ?>;



if(count == 0)
{   show_message('No contacts found in your gmail account!','error');
    window.location.href = "#!contact.php";
}

if(count != 0)
{
    var contactObj = <?php if(isset($_REQUEST['contact']))echo json_encode($_REQUEST['contact']);else echo json_encode(array()) ?>;
console.log(contactObj);

var nameObj = <?php if(isset($_REQUEST['name']))echo json_encode($_REQUEST['name']);else echo json_encode(array()) ?>;
 
var emailObj = <?php if(isset($_REQUEST['email']))echo json_encode($_REQUEST['email']);else echo json_encode(array()); ?>; 
console.log(nameObj);
console.log(emailObj);


var contactArr = $.map(contactObj, function(value, index) {
    return [value];
});

var nameArr = $.map(nameObj, function(value, index) {
    return [value];
});

var emailArr = $.map(emailObj, function(value, index) {
    return [value];
});
console.log(contactArr);
//create dialog to import contact



var str = ''
 
     $.ajax({
        url: "action_layer.php?action=addMoreRowDetail",
        type: "POST",
        dataType: "json",
        success: function(text) { 
	    
	var template = '<form id="contact_detail" action="javascript:;">';
	var importCounter = 1;
	    for(var i = 0 ; i< contactArr.length ; i++){
		
		str = addContactDD(importCounter);
    
		 template +='<div class="addCntrow clear bdrB addrows"><div class="child">\
                         <input placeholder="Name" value="'+nameArr[i]+'" type="text" class="name" name="name[]" />\
                         </div>\
                         <div class="child">\
                         <!--country flags with code-->\
                         <div class="countryWrap">\
                         <div class="selwrpa">\
                         <div class="currencySelectDropdownAddcnt cntry" onclick="showCountryForAdd(this);">\
                         <span class="pickDown setCountry"></span>\
                         <span id="setFlagWebCall" flagId="IN" class="flag-24 IN setFlag"></span>\
                         </div>\
                         <ul  class="bgW" style="display: none;">';
                        
                        
                         $.each(text,function(key,value){
                             
                             var ccode = value.ISO.split('/');
                             template +='<li  countryCode="'+value.CountryCode+'" countryName="'+value.Country+'" countryFlags="'+ccode[0]+'" onClick="SetValueAddCnt(this)">\
                                                <a class="clear" href="javascript:void(0)">\
                                <span class="flag-24 '+ccode[0]+'"></span><span code="'+value.CountryCode+'" class="fltxt">'+value.Country+'</span>\
                                </a>\
                                </li>'; 
                             
                         })

                        

		template +='</ul>\
                      </div>\
                      <div class="codeInput">\
                      <input name="code[]" type="text" id="code" class="min code" value="91" readonly/>\
                      <input class="pr contact" name="contact[]" id="mobileNumber" value="'+contactArr[i]+'" type="text" placeholder="Contact No" />\
                      </div>\
                      </div>\
                      </div>\
                      <div class="child accCont">\
                      <a onclick="uiDrop(this,\'#accessBox_'+importCounter+'\', \'true\');renderCurrentRow('+importCounter+')" id="currentAccessNumber_'+importCounter+'" class="accLink themeLink tdu" href="javascript:;">Assign access Number</a>\
                      <div id="accParent_'+importCounter+'" class="accParent">'+str+'<!--dialog content will load here--></div>\
                      </div>\
                      <div class="child">\
                      <i class="ic-24 close cp" onclick="closeResetObj(this,'+importCounter+')";></i>\
                      </div></div>';
			  
		    importCounter++;  

    
	}
	template +='<a class="btn btn-medium btn-blue mr2" onclick="addcontact();" href="javascript:void(0);">Done</a></form>'
	
	$('#add-contact-dialog').append(template);
	
	for(var j = 1; j < importCounter; j++ )
	{
	    getCountries(j);
	}

    
    }
     });





$("#add-contact-dialog").dialog({modal: true, resizable: false, width: 720, height: 600, 'title':'Import Contacts',
close : function(event, ui) {
                     dedicatedAN = [];
                     hashObj = [];
                    window.location.href = "#!contact.php";
                }});
	    
	    
		

    
    
}


</script>

