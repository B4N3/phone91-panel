<?php 

include dirname(dirname(__FILE__)) . '/config.php';

include_once(CLASS_DIR.'account_manager_class.php');
//create fun obj
$funObj = new fun();
$country = $funObj->countryArrayNew();

//get request id
$acmId = (isset($_REQUEST['acmId']) && $_REQUEST['acmId']!= '' && is_numeric($_REQUEST['acmId']))? $_REQUEST['acmId']:'';

if($acmId == '')
{
    echo 'Invalid Account Manager!!!';
    exit();
}

//create object
$acmObj = new Account_manager_class();
$acmDtl = $acmObj->getAcmAdmin($acmId);

if($_SESSION['id'] != $acmDtl['admin'])
{
    echo 'Permission Denied!!!';
    exit();
}

//get features
$resultArr= $acmObj->getAcmFetures($acmId);

$editFund = '';
$readOnly = '';
foreach($resultArr as $feature)
{
    
    if(isset($feature['featureName']) && $feature['featureName']== 'edit Fund' && $feature['status'] == 1)
    {
        $editFund = 'checked';
    }
    
    if(isset($feature['featureName']) && trim($feature['featureName'])== 'read Only' && $feature['status'] == 1)
    {
        $readOnly = 'checked';
    }
    
}

?>


    <!--2nd Tabs Content-->
    	<form class="formElemt" id="AccountManagerForm">
            <div class="fields">
                    <label>Full Name:</label>
                    <div class="fl"><input type="text" id="fullName" name="fullName" value="<?php echo $acmDtl['fullName']; ?>" placeholder="Full name" class="clientBal small"/>  </div>
                    
            </div>
            <div class="fields">
                    <label>Username:</label>
                    <div class="fl"><input type="text" id="username" name="username" value="<?php echo $acmDtl['userName']; ?>"  onblur="checkAccountManagerExists()"  placeholder="username"  class="clientBal small"/>
                    <div class="msg"></div></div>
                    
            </div>
            <div class="fields">
                    <label>password:</label>
                    <div class="fl"><input type="password" value="<?php echo $acmDtl['password']; ?>" id="password" name="password" placeholder="********" class="clientBal small"/></div>
                    
            </div>
       		      
            <div class="fields ">
                    <label>Select Country:</label>
                    <select id="country" name="country" onchange="setcCode(this)">
                        <option value="">select country</option>
                        <?php foreach ($country as $key=>$countryname) {
                            ?>
                        <option value="<?php echo $key; ?>" <?php if($acmDtl['cCode'] == $key) echo 'selected="true"' ; ?>><?php echo $countryname; ?></option>
                            <?php  } ?>
                           
                    </select>
            </div>
            <div id="mobile" class=" fields">
                    <label>Contact:</label>
                    <input type="text" name="cCode" id="cCode" readonly value="<?php echo $acmDtl['cCode']; ?>" placeholder ="code"><input type="text" value="<?php echo $acmDtl['number']; ?>" name="number" placeholder="Number">
            </div>
                       
            <div class="fields">
                    <label>Email:</label>
                        <input type="text" name="email" id="email" value="<?php echo $acmDtl['email']; ?>" placeholder="email">
            </div>  
            <div class="fields">
                    <label>Features:</label>
                        <input type="checkbox" name="editFund" <?php echo $editFund; ?>  id="editFundF"> Edit Fund
                        <input type="checkbox" name="readOnly" <?php echo $readOnly; ?> id="readOnlyF"> Read Only
            </div>  
            <input type="submit" title="Done" value="Done" id="save" name="Done" class="mrT btn btn-medium btn-primary">
          <input type="hidden" name="type" value="1" >
          <input type ="hidden" name="acmId" value="<?php echo $acmId;?>">
        </form>
   
<script type="text/javascript">
 $(function() {
	
        
       
$("#clientTrstable tbody tr:visible:even").addClass("even"); 
$("#clientTrstable tbody tr:visible:odd").addClass("odd");

$("#transType").on('change',function(event){
   if(this.value=='Other')
       $("#transotherType").show();
   else
       $("#transotherType").hide();
 })
        
});

function toggleState(ths,type)
{
    
    ths.toggleClass('disable');
    if($('#changefunder'+type).val() == "reduce")
    {
       $('#changefunder'+type).val("add");
    }
    else
    {
        $('#changefunder'+type).val("reduce");
    }
}



function showNext(id,event,status){
    if(status)
	$( "#"+id ).show();
    else
        $( "#"+id ).hide();
   
    if(event == "postpaid"){
        $("#cashMemoBank").hide();
    }else{
        $("#cashMemoBank").show();
    }
}

$(function() {
	$( "#paymentType, #BatchType" ).buttonset();
});

//************* add reduce transaction****

function addAdditionTransaction(){
   
   $('#saveadditionTrs').attr( 'disabled' , 'disabled' );

 // status variable use for status of transaction add / reduce
 var status = $('#changefunderTrans').val();
 // var transType use for transaction type (cash,bank,voip91,other).
 var transType = $('#transType').val();
 var description = $('#description').val();
 var amount = $('#transAmount').val();
 var toUser = $('#toUser').val();
 var transTypeOther = $('#transTypeOther').val();
 var currency = $('#currency').val();
 var reg=/^[a-zA-Z0-9\@\_\-\s]+$/;
 var reg2 = /^[0-9]+$/;
 
 //check transaction type validation 
 if(reg.test(transType)){
    if(reg.test(description)){
        if(reg2.test(amount)){
            if(amount.length <= 7) 		
	    {
            if(transTypeOther.length <= 20){
       $.ajax({
                   url : "/action_layer.php?action=addReduceTransaction",
                   type: "POST", 
                   data:{status:status,currency:currency,transType:transType,description:description,amount:amount,toUser:toUser,transTypeOther:transTypeOther},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                       if(text.status == "success"){
                            var str = designTransactionLog(text.str);                       
                            $('#transactionTable').html('');
                            $('#transactionTable').html(str);
                            $("#clientTrstable tbody tr:visible:even").addClass("even"); 
                            $("#clientTrstable tbody tr:visible:odd").addClass("odd");
                           $('#transType').val('');
                           $('#description').val('');
                           $('#transAmount').val('');
                           $("#transotherType").hide();
                       }
                        $('#saveadditionTrs').removeAttr('disabled');
                   }
        })
             }else 
             show_message("please enter valid other type ,no more then 20 characters.! ","error");
            }else
            show_message("please enter amount no more then 7 digits ! ","error");
        }else
        show_message("please enter valid amount! ","error");
    }else
       show_message("please enter valid description !","error");
    }else
       show_message("please enter valid transaction type! ","error");
}

//created by sudhir pandey (sudhir@hostnsoft.com)
//creation date 04/09/2013
//function use for design transaction log 
function designTransactionLog(text){
 var str = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize" id="clientTrstable">\
                <thead>\
                    <tr>\
                        <th width="10%">Date</th>\
                        <th width="15%">A/C Manager</th>\
                        <th width="10%">Type</th>\
                        <th width="5%" class="alR">Amount</th>\
                        <th width="5%" class="alR">Balance</th>\
                        <th width="30%">Description</th>\
                        <th width="5%" class="alR">Debit</th>\
                        <th width="5%" class="alR">Credit</th>\
                        <th width="20%" class="alR">Closing Balance</th>\
                    </tr>\
                </thead>\
          		<tbody>';    
  
 $.each( text.detail, function(key, item ) {
  str += '<tr class="">\
                    <td>'+item.date+'</td>\
                    <td>'+item.name+'</td>\
                    <td>'+item.paymentType+'</td>\
                    <td class="alR">'+item.amount+'</td>\
                    <td class="alR">'+item.currentBalance+'</td>\
                    <td>'+item.description+'</td>\
                    <td class="alR"><span class="debit">('+ item.debit +' '+item.currencyName +') '+ item.debitActualCurrency+'</span></td>\
                    <td class="alR">('+ item.credit +' '+item.currencyName +') '+item.creditActualCurrency+'</td>\
                    <td class="alRcloseBalance">'+item.closingBalance+'</td>\
                </tr>';
   
    })
    
    str+= '</tbody></table>';
     
     return str;
    
}

//***********edit fund*****        
//$(document).ready(function() { 
//    
// 
//		var options = { 
//                     
//                        url:"/action_layer.php?action=editFund", 
//                        type:'POST',        
//			dataType: 'json',
//			beforeSubmit:  showEditFundRequest,  // pre-submit callback 
//			success:     
//                                function(text)
//                                {
//                                show_message(text.msg,text.status);
//                                if(text.status == "success"){
//                                   $('#fundAmount,#balance,#partialAmt,#fundDescription').val('');
//                                   
//                                }
//                                 $('#save').removeAttr('disabled');
//                                }
//		};
//		$('#editFundform').ajaxForm(options); 
//	}); 
        
function beforeAddAcm(formData, jqForm, options){
//$('#save').attr('disabled','disabled');
  $.validator.setDefaults({
  submitHandler: function() {
      
  }
  });
    $().ready(function() {
        // validate the comment form when it is submitted	
        $("#AccountManagerForm").validate({
                rules: {
                        fullName :{
                            required: true,
                            maxlength: 40
                          
                        },
                        username :{
                            required: true,
                            maxlength: 40,
                            minlength:5   
                        },
                        password:{
                            required: true,
                            maxlength: 30,
                            minlength:8
                        },
                        number:{
                            //required: true,
                            maxlength: 30,
                            minlength:8,
                            number:true
                        },
                        email:{
                            maxlength: 30,
                            email:true
                        },
                        country:{
                            required:true
                        }
                       }
        })
        
    })
            $("#loading").show();
            if($("#AccountManagerForm").valid())
                    return true; 
            else
                    return false;
}




//*************Add Account manager*****
var acmId = <?php echo $acmId; ?>

$(document).ready(function() { 

		var options = { 
                     
                        url:"/action_layer.php?action=addAcm", 
			dataType: 'json',
			type:'POST', 
                       // data:$('#AccountManagerForm').serialize()+'&type=1&acmId='+acmId,
			beforeSubmit:  beforeAddAcm,  // pre-submit callback 
			success:     
                                function(text)
                                {
                                    
                                    console.log(text);
                                    var status ;
                                    if(text.status ==1)
                                        status = 'success';
                                    else
                                        status= 'error';
                                    show_message(text.msg,status);
                                   
                                }
		};
                
                
                
		$('#AccountManagerForm').ajaxForm(options); 
	}); 
 

//$(document).ready(function() { 
//   
//          var options = { 
//                  dataType:  'json',
//                  //target:        '#response',   // target element(s) to be updated with server response 
//                  beforeSubmit:  showRequest,  // pre-submit callback 
//                  success:       showResponse  // post-submit callback 
//          }; 
//          $('#chagngeClientPasswordForm').ajaxForm(options); 
//  }); 
  function showRequest(formData, jqForm, options) { 
		
        $().ready(function() {
            // validate the comment form when it is submitted	
            $("#chagngeClientPasswordForm").validate({
                    rules: {
                            newPass :{
				required: true,
				minlength: 5,
                                maxlength: 18
			}
                    }
            })
           
        })
		$("#loading").show();
		if($("#chagngeClientPasswordForm").valid())
			return true; 
		else
			return false;
	} 
	// post-submit callback 
	function showResponse(response, statusText, xhr, $form)  {
//            console.log(responseText)
//            console.log(statusText)
		if(response.msg_type == "success"){
		show_message("Successfully Updated","success");
		}
                else{
                    show_message("An Error Occur while update "+response.msg,"error");
                }
		$("#loading").hide();
		//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.'); 
	}
        
  //***************
  
  function getUserSysDetail(userId){
      $.ajax({
                   url : "/controller/adminManageClientCnt.php?action=getUserSysDetail", 
                   type: "POST", 
                   data:{userId:userId},
                   dataType: "json",
                   success:function (text)
                   {
                       var str = ipDetailDesign(text);  
                       $('#systemInfo').html('');
                       $('#systemInfo').html(str);
//                      
                   }
  });
  }
  function ipDetailDesign(text){
                                var str ='<thead> \
                                <tr> \
                                <th width="20%">Latest Login Time  </th>\
                                <th width="20%">Ips</th>\
                                <th width="20%">Browser</th>\
                                <th width="60%">&nbsp;</th>\
                                        </tr>\
                                        </thead>\
                                        <tbody>\
                              ';
  $.each( text, function(key, item ) {
  str += '<tr class="even">\
                <td>'+item.date+'</td>\
                <td>'+item.ipAddress+'</td>\
                <td>'+item.browser+'</td>\
                <td>&nbsp;</td>\
          </tr>';
                            
        str+='</tbody>';                    
   
    })
    return str;
      
  }
  
  // function use to change user to reseller 
  function changeUserToReseller(userId){
      
       $.ajax({
                   url : "/controller/adminManageClientCnt.php?action=changeUserToReseller", 
                   type: "POST", 
                   data:{userId:userId},
                   dataType: "json",
                   success:function (text)
                   {
                       show_message(text.msg,text.status);
                                             
                   }
       });
      
      
  }
  
  function changeSipSetting(userId)
  {
      if($('#changeSip').is(':checked'))
          var actionType = "enable";
      else
          var actionType = "disable";
      
      
      if(/[^0-9]/.test(userId) || userId == "" || userId == null)
      {
          show_message("Invalid User please select a valid user","error");
          return false;
      }
      
      $.ajax({
            url : "/controller/adminManageClientCnt.php", 
            type: "POST", 
            data:{forUser:userId,action:"changeSipSetting",actionType:actionType},
            dataType: "json",
            success:function (text)
            {
                show_message(text.msg,text.status);

            }
      })
  }
  
 </script>
 
<script type="text/JavaScript">
$.extend({
  password: function (length, special) {
    var iteration = 0;
    var password = "";
    var randomNumber;
    if(special == undefined){
        var special = false;
    }
    while(iteration < length){
        randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
        if(!special){
            if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
            if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
            if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
            if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
        }
        iteration++;
        password += String.fromCharCode(randomNumber);
    }
    return password;
  }
});
 $('#generatePassword').click(function(e){
    password = $.password(10,true);
    $('#newPass').val(password);
    e.preventDefault();
});

/**
 * added by Ankit Patidar from user panel
 * 
 */
checkAccountManagerExists = function ()						
    {
	    var u= $("#username").val();
            var reg=/^[a-zA-Z0-9\@\_\-\s]*[A-Za-z][a-zA-Z0-9\@\_\-\s]*$/;
	    u = jQuery.trim(u);
	    if(u.length >=5) 		
	    {
                
                //apply validation for character length
                if(u.length >= 25)
                {
                    $("#username").next().addClass("error_red").html("Username Must Less than 25 character");	
                    $("#username").removeClass("error_green");
                    $("#username").addClass("error_red");
                    return false;
                }
                
                if(!reg.test(u)){
                    $("#username").val();
                    $("#username").next().removeClass("error_green").addClass("error_red").html("Username not valid");	
                    $("#username").removeClass("error_green");
                    $("#username").addClass("error_red");
                   usernameMsg = 0;
                   	
                    return false;
                }
		
		$("#username").css({'background':'url(images/loading.gif) no-repeat','background-position':'right center'})
		$.ajax({type: "GET",url: "/action_layer.php?action=checkAcmExists",data: { username: u},
		success: function(msg)
		{ 
			$("#username").css({'background':'#fff'})
			if(msg==0) 
			{
				$("#username").val();
//				$("#username").focus();
				$("#username").next().removeClass("error_green").addClass("error_red").html("Already In use");	
				$("#username").removeClass("error_green");
				$("#username").addClass("error_red");
			}
			if(msg==1)
			{
				$("#username").next().addClass("error_green").html("You can choose this username. Available");	
				$("#username").removeClass("error_red");
				$("#username").addClass("error_green");
			}
			usernameMsg = msg;
		}});	
	    }
	    else
	    {
		$("#username").next().addClass("error_red").html("Username Must Contain 5 character");						
                $("#username").addClass("error_red");
		//$("#username").focus();
	    }

    } 


function setcCode(code)
{
    console.log(code.value);
    $('#cCode').val(code.value);
}
</script>