

function addcontact() {
    var allcontact = $('#contact_detail').serialize();

    $.ajax({
        url: "action_layer.php?action=addContact",
        type: "POST", dataType: "json",
        data: allcontact,
        success: function(text) {
            show_message(text.msg, text.status);
            if (text.status == "success") {
                $("#add-contact-dialog").dialog('close');
                $('.cntList').html('');
                $('.cntList').html(text.str);

            }
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
        $("#edit-contact-wrap-dialog").dialog({modal: true, resizable: false, width: 600, height: 400});

    })


}
function addMoreRow() {

    var templateAddmore = '<div class="clear row">\
    <div class="child">\
    <p class="mrB">Name</p>\
    <div class="">\
    <input type="text" name="name[]"/>\
    </div>\
    </div>\
    <div class="child">\
    <p class="mrB">Contact</p>\
    <div class="">\
    <input type="text" name="contact[]" />\
    </div>\
    </div>\
    <div class="child">\
    <p class="mrB">Email</p>\
    <div class="">\
    <input type="text" name="email[]" />\
    </div>\
    </div>\
    <div class="child ie">\
    <p class="mrB">&nbsp;</p>\
    <a class="clear alC" onclick="removeThis(this);" href="javascript:void(0);">\
    <div class="clear tryc tr1">\
    <span class="ic-24 close"></span>\
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
                $("#edit-contact-wrap-dialog").dialog('close');
                $('.cntList').html('');
                $('.cntList').html(text.str);

            }
        }
    })

}
function deletecontact(ths) {
    var contactId = $(ths).attr('contactId');
    if (confirm("Are You Sure To Delete This Contact.")) {
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


    }
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
                
                wooYayIntervalId = setInterval("checkStatus('" + msg.msgid1 + "')", 5000);
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

function checkStatus(id)
{
    
    var url1 = "checkRespnse.php";
    url1 = url1 + "?uniqueId=" + id;
    $.ajax({type: "GET", url: url1, dataType: "json", success: function(msg) {
            //$("#guid").attr('value',msg);

            if (msg.status == "success") {
                //show_message("Connect Successfully",msg.status);
                //wooYayIntervalId = setInterval ( "checkStatus()", 1000 );
                //clearInterval ( wooYayIntervalId ); 
                $("#response").show().html(msg.msg);
            }
            else {
//                show_message(msg.msg, msg.status);
                //js.notification(msg.status,msg.msg)
               
                $("#response").show().html(msg.msg);
                $("#response").show().html();
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
                $('#callrateDtl').html(text.rate + " USD/min");
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

    $.ajax({
        url: "action_layer.php?action=seeCallRate",
        type: "POST", dataType: "json",
        data: {source: s, destination: d},
        success: function(text) {
            if (text.status == "error") {
                show_message(text.msg, text.status);
            } else {
                $('#callrateDtl').html("");
                $('#callrateDtl').html(text.rate + " USD/min");
            }


        }

    })
}