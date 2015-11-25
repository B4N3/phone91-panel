<?php

include dirname(dirname(__FILE__)) . '/config.php';

if (!$funobj->login_validate() || !$funobj->check_admin()) 
{
   $funobj->redirect("/index.php");
}

#include reseller_class.php file 
include_once CLASS_DIR.'account_manager_class.php';

#create object of reseller_class
$acmObj = new Account_manager_class();

//if account manager then redirect to panel
//if($acmObj->loginAcmValidate())
//{
//    $funobj->redirect("/admin/index.php#!manage-client.php|manage-client-setting.php");
//}

$page="1";
if(isset($_REQUEST['pageNo'])  && !empty($_REQUEST['pageNo']))
{
    $page = $_REQUEST['pageNo'];
}

#call function manageClients and return json data clientJson
$acmJson = $acmObj->allBlockUserList($_REQUEST,$_SESSION);
?><div id="transactionTable" class="tablflip-scroll">
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="blockUserTable" class="cmntbl alR boxsize">
            <thead>
                <tr>
                    <th width="15%"><a id="addRowAdminBtn" href="javascript:void(0);" title="" onclick="addRowAdmin();"><span class="ic-16 add"></span></a>Ip,Number,E-mail</th>
                    <th width="8%">Account Manager/Admin</th>
                    <th width="5%">Reason</th>
                    <th width="5%">Date</th>
                    <th width="5%">UnBlock</th>
                </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>          
    <div id="pagination" style="width: 100%; height: 15px; padding-left: 55px;"></div>
</div>
<script>
  
  var blockIpInfo = $.parseJSON( '<?php echo $acmJson;  ?>' );
  designBlockUsersList(blockIpInfo); 
  
  function designBlockUsersList(blockIpInfo) 
  {
    blockIpIpArray =  blockIpInfo.detail;
    
    var str = "";
    if(blockIpIpArray != undefined)
		$.each( blockIpIpArray, function(key, item ) 
		{
		   var trId = key+1;
			
		   str+='<tr id="user'+item.sNo+'" class="hvrParent">\
				   <td>'+item.particular+'</td>\n\
				   <td>'+item.acmName+'</td>\n\
				   <td>'+item.reason+'</td>\n\
				   <td>'+item.dateTime+'</td>\n\
				   <td><input type="button" value="UnBlock" class="mrT btn btn-medium btn-primary" onclick="unblockIp('+item.sNo+')"></td>\
		   </tr>';
			
		});
    
    $('#blockUserTable').append(str);
    
    
    
    pagination(blockIpInfo.pages,'<?php echo $page; ?>','#pagination');
}

function unblockIp(ths)
{
     $.ajax({
            type: 'POST',
            url: "../action_layer.php?action=unblocUserIp",
            data: "BlockId="+ths,   // I WANT TO ADD EXTRA DATA + SERIALIZE DATA
            dataType: 'json',
            success: function(response)
            {
                if(response.status == 1)
                {
                    status = "success";
                    $('#user'+ths).hide();
                }
                else
                {
                    status = "error";
                }
                show_message(response.msg,status);
            }
        });
}

function addRowAdmin()
{
    $('.addUserIpDiv').show();
    /* @AUTHOR :SAMEER 
     * @DESC : ADD THE EXTRA ROW TO THE TABLE IN MANUAL ENTRY OPTION */	
		
    var i = $('#addUserIpTable tbody tr').eq($('#addUserIpTable tbody tr').size()-1).attr('rowIndex');
	if(i == undefined)
		i = 1;
	else 
		i++;
            
    var row;
    row = '<tr class="dynamicRow" rowIndex="'+i+'">\
            <td width="10%"><input type="text" value="" class="userInfo isInput150" id="userIpAddress'+i+'" name="userIpAddress[]" class=""/></td>\
			<td width="10%"></td>\
            <td width="10%"><input type="text" value="" class="userInfo isInput150" id="reason'+i+'" name="reason[]" class=""/></td>\
			<td width="10%"></td>\
            <td class="noBorder"  width="2%"><span class="ic-24 delete cp" title="Delete" onclick="removeRow($(this))"></span></td>\
    </tr>';
	var newRowFooter = '<tr id="newRowFooter">\
            <td colspan="5"><input type="button" class="btn btn-medium btn-primary fl" name="append" id="submitUserInfo" value="Block" title="Done" onclick="submitUserInfo()" ></td>\
    </tr>';		
	if($('.dynamicRow').length == 0)
    	$('#blockUserTable tbody').prepend(newRowFooter);
			
    $('#blockUserTable tbody').prepend(row);
	
    /*UPDATE THE VALUE THIS IS USED TO ITERATE THROUGH ALL THE VALUED DURING INSERTION OF THE TARIFF*/
    $('#sizeOfRow').val((i));
}

function removeRow(ths)
{
	if($('.dynamicRow').length == 1)
		$('#newRowFooter').remove();
	$('#sizeOfRow').val($('#sizeOfRow').val()-1);
    ths.closest('tr').remove();
	
}


function submitUserInfo()
{
    $.ajax({
            type: 'POST',
            url: "../action_layer.php?action=addBlockUserInfo",
            data: $('.userInfo').serialize(),   // I WANT TO ADD EXTRA DATA + SERIALIZE DATA
            dataType: 'json',
            success: function(response)
            {
                console.log(response);
                if(response.status == 1)
                {
                    $("#blockUserTable > tbody").html("");
                    
                    var blockIpInfo = $.parseJSON( response.content );
                    designBlockUsersList(blockIpInfo) 
                    status = "success";
                }
                else
                {
                    status = "error";
                }
                show_message(response.msg,status);
            }
        });
}



</script>