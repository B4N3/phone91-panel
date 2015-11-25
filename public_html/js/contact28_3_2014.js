

function addcontact() {
    var allcontact = $('#contact_detail').serialize();
 //p91Loader('start');
    $.ajax({
        url: "action_layer.php?action=addContact",
        type: "POST", dataType: "json",
        data: allcontact,
        success: function(text) {
            var randomNumber = Math.floor(Math.random() * 6) + 1;
            show_message(text.msg, text.status);
            $('.name').each(function(){
                $(this).val("");
            })
            $('.contact').each(function(){
                $(this).val("");
            })
            $('.email').each(function(){
                $(this).val("");
            })
//                $('.contact').val("");
//                $('.email').val("");
            if (text.status == "success") {
                
               

//                $('.name').val("");
//                $('.contact').val("");
//                $('.email').val("");
//                $('.name').remove();
//                $('.contact').remove();
//                $('.email').remove();
                
                $("#add-contact-dialog").dialog('destroy');
                window.location.href = "#!contact.php?id="+randomNumber;
//                $('.cntList').html('');
//                $('.cntList').html(text.str);
                

            }
            //p91Loader('stop');
            removeThis('.close');

        }
    })
}
function showContactEdit(ths) {
    var contactId = $(ths).attr('contactId');

    $.ajax({
        url: "action_layer.php?action=showEditContact",
        type: "POST",
        data: {contactId: contactId},
    }).done(function(msg) {
        $('#edit-contact-wrap-dialog').html(msg);
        $("#edit-contact-wrap-dialog").dialog({modal: true, resizable: false, width: 600, height: 400,title:"Edit Contact"});

    })


}
/*Modified By Lovey at 4/9/2013*/
function addMoreRow() {

    var templateAddmore = '<div class="clear row">\
    <div class="child">\
    <div class="">\
    <input type="text" class="name" name="name[]"/>\
    </div>\
    </div>\
    <div class="child">\
    <div class="">\
    <input type="text"  class="contact" name="contact[]" />\
    </div>\
    </div>\
    <div class="child">\
    <div class="">\
    <input type="text" class="email" name="email[]" />\
    </div>\
    </div>\
    <div class="child ie">\
    <a class="clear alC" onclick="removeThis(this);" href="javascript:void(0);">\
    <div class="clear tryc tr1 rowClose">\
    	<span class="ic-16 close"></span>\
    </div>\
    </a>\
    </div>\
    </div>';
    $('.addmorelink').before(templateAddmore);

}
function removeThis(ths) {
    var thisrow = $(ths).parentsUntil('.add-cnt-form');
    $(thisrow).remove();
}

function editcontact(ths) {
    var allcontact = $('#contact_edit_form').serialize();
    $.ajax({
        url: "action_layer.php?action=updateContact",
        type: "POST", dataType: "json",
        data: allcontact,
        success: function(text) {
            show_message(text.msg, text.status);
			
            if (text.status == "success") {
                $("#edit-contact-wrap-dialog").dialog('destroy');
                $('.cntList').html('');
                $('.cntList').html(text.str);

            }
        }
    })

}
function confirmDelete(ths)
{
     $( "#dialog-confirm" ).dialog({
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
                    deletecontact(ths);
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

function deletecontact(ths) {
    var contactId = $(ths).attr('contactId');
//    if (confirm("Are You Sure To Delete This Contact.")) {
        $.ajax({
            url: "action_layer.php?action=deleteContact",
            type: "POST", dataType: "json",
            data: {contactId: contactId},
            success: function(text) {
                show_message(text.msg, text.status);
                if (text.status == "success") {
                    $("#edit-contact-wrap-dialog").dialog('close');
                    $('.cntList').html('');
                    $('.cntList').html(text.str);

                }
            }
        })


//    }
}

function clicktocall()
{
   
    //var domain=window.location;
    var interval;
    var result = null;
    var s = $("#source").val();
    var d = $("#dest").val();
    if (d.length < 8 || isNaN(d) || d.length > 18)
    {
        if (isNaN(d))
        {
            $("#dest").addClass("error_red").attr('value', '');
            $("#response").show().addClass("error_red").html('Please Provide proper Source number');
        }
        else
        {
            $("#dest").addClass("error_red");
            $("#response").show().addClass("error_red").html("please enter number (minimum length 11 , maximum length 18)");
        }
        $("#dest").focus();
        return false;
    }
    else
    {
        $("#response").html('');
        $("#dest").removeClass("error_red");
        $("#dest").addClass("error_green");
    }
    if (s.length < 11 || isNaN(s) || d.length > 18)
    {
        if (isNaN(s))
        {
            $("#source").addClass("error_red").attr('value', '');
            $("#response").show().addClass("error_red").html('Please Provide proper Destination number');
        }
        else
        {
            $("#source").addClass("error_red");
            $("#response").show().addClass("error_red").html("Please enter number ( Minimum length 11, Maximum length 18)");
        }
        $("#source").focus();
        return false;
    }
    else
    {
        $("#source").addClass("error_green");
    }
    var url1 = "clicktocall.php";

    url1 = url1 + "?q=" + d + "&d=" + s;
     $("#response").show().html();
     $("#response").show().html("We are connecting your call...");
     
    $.ajax({type: "GET", url: url1, dataType: "json", success: function(msg) {
            //$("#guid").attr('value',msg);
            
            if (msg.status == "success") {
                $("#response").show().html("We are connecting first Number ");
                show_message("Connect Successfully", msg.status);
                
                wooYayIntervalId = setInterval("checkStatus('" + msg.msgid1 + "','" + msg.msgid2 + "')", 5000);
                //clearInterval ( wooYayIntervalId ); 
                $("#response").show().html();
            }
            else {
                show_message(msg.msg, msg.status);
                //js.notification(msg.status,msg.msg)
                $("#response").show().html(msg.msg);

            }

            $("#connectcall").css("visibility", "hidden")
        }});
    return false;

}

function clicktocall_ankit()
{
   
    //var domain=window.location;
    var interval;
    var result = null;
    var s = $("#source").val();
    var d = $("#dest").val();
    if (d.length < 8 || isNaN(d) || d.length > 18)
    {
        if (isNaN(d))
        {
            $("#dest").addClass("error_red").attr('value', '');
            $("#response").show().addClass("error_red").html('Please Provide proper Source number');
        }
        else
        {
            $("#dest").addClass("error_red");
            $("#response").show().addClass("error_red").html("please enter number (minimum length 11 , maximum length 18)");
        }
        $("#dest").focus();
        return false;
    }
    else
    {
        $("#response").html('');
        $("#dest").removeClass("error_red");
        $("#dest").addClass("error_green");
    }
    if (s.length < 11 || isNaN(s) || d.length > 18)
    {
        if (isNaN(s))
        {
            $("#source").addClass("error_red").attr('value', '');
            $("#response").show().addClass("error_red").html('Please Provide proper Destination number');
        }
        else
        {
            $("#source").addClass("error_red");
            $("#response").show().addClass("error_red").html("Please enter number ( Minimum length 11, Maximum length 18)");
        }
        $("#source").focus();
        return false;
    }
    else
    {
        $("#source").addClass("error_green");
    }
    var url1 = "clicktocall_ankit.php";

    url1 = url1 + "?q=" + d + "&d=" + s;
     $("#response").show().html();
     $("#response").show().html("We are connecting your call...");
     
    $.ajax({type: "GET", url: url1, dataType: "json", success: function(msg) {
            //$("#guid").attr('value',msg);
            
            if (msg.status == "success") {
                $("#response").show().html("We are connecting first Number ");
                show_message("Connect Successfully", msg.status);
                
                wooYayIntervalId = setInterval("checkStatus('" + msg.msgid1 + "','" + msg.msgid2 + "')", 5000);
                //clearInterval ( wooYayIntervalId ); 
                $("#response").show().html();
            }
            else {
                show_message(msg.msg, msg.status);
                //js.notification(msg.status,msg.msg)
                $("#response").show().html(msg.msg);

            }

            $("#connectcall").css("visibility", "hidden")
        }});
    return false;

}



function checkStatus(id,id2)
{
    
    $('#response2').html(" Source number status :: ");
    if(id == null)
    {
        id=id2;
        $('#response2').html(" Destination number status :: ");
    }
    
    var url1 = "checkRespnse.php";
    url1 = url1 + "?uniqueId=" + id;
    $.ajax({type: "GET", url: url1, dataType: "json", success: function(msg) {
            //$("#guid").attr('value',msg);

            if (msg.status == "success") {
                //show_message("Connect Successfully",msg.status);
                //wooYayIntervalId = setInterval ( "checkStatus()", 1000 );
                //clearInterval ( wooYayIntervalId ); 
               if(msg.msg == 'ANSWER' )
               {
                   
                   if(id != null)
                    clearInterval(wooYayIntervalId);
                   wooYayIntervalId = setInterval("checkStatus(null,'" + id2 + "')", 5000);
               }
                $("#response").show().html(msg.msg);
               if(msg.msg != 'ANSWER' && msg.msg != 'DIALING')
               {
                    clearInterval(wooYayIntervalId);
                    
                    if(msg.msg == 'ANSWERED')
                       $("#response").show().html("Call Ended");
               }               
            }
            else {
                show_message(msg.msg, msg.status);
                //js.notification(msg.status,msg.msg)
//               if(msg.msg == 'ANSWER' )
//               {
//                   console.log("success");
//                   if(id != null)
//                    clearInterval(wooYayIntervalId);
//                   wooYayIntervalId = setInterval("checkStatus(null,'" + id2 + "')", 5000);
//               }
//                   
//               $("#response").show().html(msg.msg);
//               $("#response").show().html();
//               if(msg.msg != 'ANSWER' && msg.msg != 'DIALING')
//               {
//                    clearInterval(wooYayIntervalId);
////                    $("#response").show().html("Call Ended");
//               }              
            }
        }
    })
}

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 05-08-2013
function showcosts() {

    var s = $("#source").val();
    var d = $("#dest").val();
    var Regx = /^[0-9]{8,15}$/;
    if (!Regx.test(s)) {
        show_message("Source number are not valid !", "error");
        return;
    }
    if (!Regx.test(d)) {
        show_message("Destination number are not valid !", "error");
        return;
    }


    $.ajax({
        url: "action_layer.php?action=seeCallRate",
        type: "POST", dataType: "json",
        data: {source: s, destination: d},
        success: function(text) {
            if (text.status == "error") {
                show_message(text.msg, text.status);
            } else {
                $('#callrateDtl').html("");
                $('#callrateDtl').html("<span class='font25'>" + text.rate + "</span>" + " USD/min");
            }


        }

    })

}

function callcostKeyup() {
    var s = $("#source").val();
    var d = $("#dest").val();
    var Regx = /^[0-9]{8,15}$/;
    if (!Regx.test(s)) {
        show_message("Source number are not valid !", "error");
        return;
    }
if(d.length >=5){ 
    $.ajax({
        url: "action_layer.php?action=seeCallRate",
        type: "POST", dataType: "json",
        data: {source: s, destination: d},
        success: function(text) {
            if (text.status == "error") {
                show_message(text.msg, text.status);
            } else {
                $('#callrateDtl').html("");
                $('#callrateDtl').html("<span class='font25'>" + text.rate + "</span>  " + text.currencyName +"/min");
            }


        }

    })
}
}