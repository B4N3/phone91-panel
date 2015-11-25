<?php require('config.php');


include_once("classes/validation_class.php");


//if(!$val_obj->check_reseller())
//	$val_obj->expire();
//$limit=10;
//$page=$_SERVER['PHP_SELF'];
//$pageName=$_SERVER['PHP_SELF'];
/*if(isset($_REQUEST['id']))
	$chk_reseller=$val_obj->check_parent_reseller($_REQUEST['id']);

if(isset($_REQUEST['page_number']))
{
	$pg_num=$_REQUEST['page_number'];
	$first_limit=($_REQUEST['page_number']-1)*$limit;
}
else
	$first_limit=0;

if(!isset($_REQUEST['submit2']))
{
	$total_rows=$pro_obj->load_total_users2(0,$_SESSION['id']);
}

if(isset($_REQUEST['submit2']))
{
	$limit=200;
	if(isset($_REQUEST['user_name']) && $_REQUEST['user_name']!='')
		$s_uname=$_REQUEST['user_name'];
	else if(isset($_REQUEST['user_name2']))
		$s_uname=$_REQUEST['user_name2'];
	else
		$s_uname='';
	
	$s_name=$_REQUEST['user_name'];

	$s_mobno=$_REQUEST['user_name'];
	
	$total_rows=$pro_obj->load_total_users2(0,$_SESSION['id']);
	if($total_rows==0)
	{		
		$result=$pro_obj->load_users2(0,$_SESSION['id'],$first_limit,$limit,0);
	}
	else
	{
		$result=$pro_obj->load_filtered_users($s_name,$s_uname,$s_mobno,$_REQUEST['utype'],$first_limit,$limit,0);	$total_rows=mysql_num_rows($result);
	}
	if($total_rows>0)
	{
		//$user_pid4=mysql_result($result,0,'id_client');
		//$page=$pageName.'?id='.$user_pid4.'&submit2=submit&s_name='.$_REQUEST['s_name'].'&s_uname='.$_REQUEST['s_uname'].'&s_mobno='.$_REQUEST['s_mobno'].'&utype='.$_REQUEST['utype'];
		//if(isset($_REQUEST['page_number']))
			//$page.='&page='.$_REQUEST['page_number'];
			
	}
}
else
{
	$s_name='By Name';
	$s_uname='By Username';
	$s_mobno='By Mobile';
	//sorting code added by sapna
	$sort=0;
	if(isset($_REQUEST['select_type']) && isset($_REQUEST['select_field']))
	{
		$select_type=$_REQUEST['select_type'];$select_field=$_REQUEST['select_field'];
		$result=$pro_obj->load_sorted_user(0,$_SESSION['id'],$first_limit,$limit,$select_field,$select_type);
	}
	else
	{
	
		$result=$pro_obj->load_users2(0,$_SESSION['id'],$first_limit,$limit,$sort);
		if($total_rows>0)
		{
			//$user_pid4=mysql_result($result,0,'user_pid');
			//$user_pid4=mysql_result($result,0,'id_client');
			//$page=$pageName."?";
			//if(isset($_REQUEST['page_number']))
				//$page=$page."page_number=".$_REQUEST['page_number'];
		}
	}
}

if(isset($_REQUEST['id']))
	$result=$pro_obj->load_user_details($_REQUEST['id']);

$pages=ceil($total_rows/$limit);*/
?>


<div class="shell">
    <div class="header">
  
         <div class="title_wrapper">
            <h2>Manage Clients</h2>
                       <span class="title_wrapper_right" style="display: block; "></span></div>
    <div class="hdr-sec">
    <div class="src">
    
    <div class="srcbg"><input type="text" name="filter_string" id="user_name" value="" style="border:none;" /></div>
    <!--<form action="" class="search_form" method="post">
    <div class="srcbg">
    <input type="text" name="filter_string" id="user_name" value="" />
    <input type="hidden" value="" name="user_name" id="client_user_name" />
    </div>
    <input type="submit" name="submit" value="" />
    </form>-->   
    </div><!--END SRC BOX-->    
    </div><div class="clf"></div>
    </div>                        
    <div id="box1-tabular" class="content" style="display: block; ">
    <div class="outer">
      <a onclick="add_client()" href="javascript:;" class="small awesome">Add Client</a> 
     <!--<a onclick="export_client()" href="javascript:;" class="small awesome">Export</a>  
       <a href="javascript:;" class="small awesome" onclick="makeFrame('exe_summary_home.php');"> Executive Summary </a>-->
      <!--<a  href="../action_layer.php?action=63" class="small awesome">Export</a>-->
     </div>
        <form class="plain" action="" name="form1" method="post" enctype="multipart/form-data">								
        <div class="table_wrapper">
                        <div class="table_wrapper_inner">
                            <table id="results" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
                              
            	<tr class="smlinput" id="addClientTr" style="display:none; ">
                    <td colspan="10" class="activebg padding0">
                    <form>
                        <h3 class="whitehd">Add New Client</h3>
                        <div class="fltlt outer">
                            <label>User Name</label>            
                            <div class="thefield"><input name="adduser_name" type="text" id="adduser_name" value="" /></div>
                            <label>Password</label>
                            <div class="thefield"><input name="pwd" type="text" id="pwd" value="" /></div>
                            <label>Country</label>
                            <div class="thefield">
                            <select style="width:200px" onchange="$('#ccode').val(this.value);" id="location" name="location">
                               <option value="">Choose your country</option>
                            </select>
                            </div>
                            <label>Mobile</label>
                            <div class="thefield"><input name="ccode" id="ccode" maxlength="10" type="text" readonly="readonly" /><input name="mob_no" type="text" id="mob_no" value="" /></div>
                            <div class="thefield"><input type="button" class="medium green awesome" value="Add New Client" onclick="add_client_submit()"/></div>
                        </div>
                        <label>Balance</label>
                        <div class="thefield"><input name="balance" type="text" id="balance" value="" /></div>
                        <label>User Type</label>
                        <div class="thefield">
                        <select name="utype" id="utype">
                            <option value="3">User</option>
                            <option value="2">Reseller</option>
                        </select>
                        </div>            
                        <label>Email</label>
                        <div class="thefield"><input name="user_email" type="text" id="user_email" value="" /></div>
                    </form>
                </td>
            	</tr>
			</thead>
            <tfoot></tfoot>
<!--            <tbody id="tbodyClientData">
		 <?php
                                include_once("/home/voip91/public_html/newapi/user_function_class.php");

                                $rowArray = $user_obj->getClient_Details('clientsshared ', '10', '*', 'account_state > 0');
                                if ($rowArray != '') {
                                    
                                    while ($rows = mysql_fetch_array($rowArray)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $rows['login'] ?></td>
                                            
                                            <td><?php echo $rows['account_state'] ?></td>
                                            <td><?php echo $rows['id_currency'] ?></td>
                                            <td><?php echo $rows['id_tariff'] ?></td>
                                            <td><?php echo $rows['client_type'] ?></td>
                                             <?php // $page_link='edit_context.php?id='.$id; ?>
                    <td class="wd2 toggle_tab" onClick="">
                        <span class="icon icon_edit"></span></td>  
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "if is not excuting";
                                }                                ?>		
            </tbody>-->
        </table>	
                        </div>
        </div>
    	</form>
        <div id="paging">
    <?php 
		if($pages>1) 
		{ 
			if(isset($_REQUEST['submit2'])) 
			{
				$page=$pageName."?s_uname=".$_REQUEST['s_uname']."&s_name=".$_REQUEST['s_name']."&s_mobno=".$_REQUEST['s_mobno']."&utype=".$_REQUEST['utype']."&submit2=submit";
			}
			else
			{
				if(strpos($page,"?") === FALSE)
					$page=$page."?";
				else
					$page=$page."&";
				$page=$page."rand=".rand(100000,1000000);
			}
			if(isset($_REQUEST['page_number']))
			{
				$start_page=$_REQUEST['page_number']-7;
				$end_page=$_REQUEST['page_number']+7;
			}
			else
			{
				$start_page=1;
				$end_page=15;
			}
			if($start_page<=0)
				$start_page=1;
			if(($end_page-$start_page)<14)
				$end_page=15;
			if($end_page>$pages)
				$end_page=$pages; 
			 
            $pageStr='<div class="pagination" id="pagination"><ul>';
			for($i=$start_page;$i<=$end_page;$i++) 
			{ 
				if($i==1) 
				{ 
					$pageStr.='<li><a id="mng_client1" href="javascript:;">';
					if((!isset($_REQUEST['page_number']))||($_REQUEST['page_number']<=1))  
						$pageStr.= '<b>1</b>'; 
					else $pageStr.='1';
                    $pageStr.='</a></li>';
					$pageStr.="<script type='text/javascript'> $('#mng_client1').click(function(){ $('#midright').load('".$pageName."'); }); </script>";
				} 
				else 
				{ 
                    $pageStr.='<li ><a id="mng_client'.$i.'" href="javascript:;">';
					if($_REQUEST['page_number']==$i) 
						$pageStr.= '<b>'.$i.'</b>';
					else $pageStr.= $i; 
                    $pageStr.='</a>
                    </li>';
                   
	$pageStr.= "<script type='text/javascript'> $('#mng_client".$i."').click(function(){ $('#midright').load('".$page."&page_number=".$i."'); }); </script>";
				}
			} 
			$pageStr.='</ul></div>';
			echo $pageStr;		
		} 
		?>
        </div>
<input type="hidden" name="page" value="<?php echo $page; ?>" />
    </div>
    
    
     <div class="table_wrapper">
                    <div class="table_wrapper_inner" >
                        <table class="export_results" border="0" cellspacing="0" cellpadding="0" width="100%">
                            <thead><tr>
                            <th class="tip userInfoTd" 
                            title="
                            	<fieldset class='userDetail'>
                                	<legend>Reseller of this User</legend>
									<div class='bigUname'><span class='Reseller'>Reseller</span> / <span class='User'>User</span></div>
                                    <div>Name:</div>                            
                                    <div>Contact:</div>
									<div>Email:</div>
								</fieldset>"
                            >User Details </th>                            
                            <!--<th>Reseller</th>  -->
                            <th>Action</th>                           
                            <th></th>
                            </tr>
                            </thead> 
                            <tbody>                            
                            <?php   
                            
                           
                                include_once("/home/voip91/public_html/newapi/user_function_class.php");

                                $rowArray = $user_obj->getClient_Details('clientsshared ', '10', '*', 'account_state > 0');
                                if ($rowArray != '') {
                                    
                                    while ($rows = mysql_fetch_array($rowArray)) {
                                       
                        
                                             $user=$rows['login'];
                                             $balance=$rows['account_state'];
                                            $cur_ty=$rows['id_currency'];
                                           $triff=$rows['id_tariff'];
                                           $client_type=$rows['client_type'];                                                                   
                                            $id=$rows['id_client'];                                                                   
                                            
                            
                             ?>
                                <tr>
                                  <td class="txt userInfoTd">
                                <fieldset class="userDetail" >
                              
                                    <legend>Under : voip91</legend>
                              
                                    
                                <div class="bigUname" style="color: #CC00CC;"  tip" title="<?php echo $utype ?>">
                                <?php echo $user; ?>
                                </div>
                                <div>Name: <?php echo $user; ?></div>                                  
                                <div>balance: <?php echo $balance;?></div>
                               
                                <div>Currency Type: <?php echo $cur_ty;?></div>
                                <div>Triff Type: <?php echo $triff;?></div>
                                <div>Client Type: <?php echo $client_type;?></div>
                                 <div>Client ID: <?php echo $id;?></div>
                                
                                <?php       
                                
//                                echo "<div><span class='icon icon_reseller'></span><a href='../action_layer.php?action=87&id=$id' class='button black small'>&laquo; Login as</a>";
                                //if(currentIP==vterminationIP || currentIP==online160IP)
                              ?>
                                </fieldset>
                            </td>   
                              <td class="actions_menu">
                                
      
                  <div>
            <a onClick="load_details(<?php echo $id; ?>,'../user/manage_funds.php?form_id=<?php echo $id; ?>',this)"  href="javascript:;" class="fund">Fund</a>
        </div>							
        <div>
            <a onClick="load_details(<?php echo $id; ?>,'change_client_password.php?form_id=<?php echo $id; ?>',this)"  href="javascript:;" class="pass">Password</a>
        </div>
        <div>
            <a onClick="edit_client('<?php echo $id; ?>','<?php echo "edit_manageadmin.php?id=" . $id; ?>','this')"href="javascript:;" class="edit">edit</a>
        </div>
        <div>
            <a onClick="delete_client('<?php echo $id; ?>')" class="delete" href="javascript:;">Delete</a>									
        </div>
            
        <div>
            <a onClick="load_details('<?php echo $id; ?>','<?php echo "edit_Expiry.php?id=".$id; ?>','this')"href="javascript:;" class="exdate">Expiry Date</a>
        </div> 
        
    </td>
                        
                            <tr>
                                  <tr>
                            <td colspan="20" class="ajxcontent ajxActive" id="ajaxcontent<?php echo $id; ?>"></td>                       
                             </tr> 
                          <?php } } ?>  
                            
                            
                           
                            
                        </table>
                    </div>
     </div>
    
    
    
    
    
    
    
    
    
</div>
<link rel="stylesheet" href="../css/jquery.autocomplete.css" />
<script>
    
    
    
    function load_details(id,page,ths){
	if($(ths).hasClass('active'))
	{
		$('.activebg').hide();	
		$(ths).removeClass('active');			
	}
	else
	{
		$('.toggle_tab').removeClass('active')
		$('.activebg').hide();
		$(ths).addClass('active')			
		$("#ajaxcontent"+id).show();
		$('#loading').show();
		$("#ajaxcontent"+id).load(page,function() {$('#loading').hide();});			
	}	
}		
/*Client status enable/disable*/
function loadClientData(pageNo)
{
    $("#loading").show();  
	var page='';
	if(pageNo!='')
		page='?page_number='+pageNo;
	//$('#loading').show();
	var q=$('#user_name').val();
	//if(q!='')
		//page+='?q='+q;
	$.ajax({
		type: "POST",
		url: 'search_client_new.php'+page,
		data: {q:q},
		dataType:"json",
		success: function(data){
			$('#loading').hide();
			//$("#midright").html(msg);
			$("#tbodyClientData").html(data.trdata);
			$("#paging").html(data.paging);
		}
	});
}
/*Add client*/
function add_client()
{
	var display="none";
	if($("#addClientTr").css("display")=='none')
		display="table-row";
	$("#addClientTr").css("display",display);
	if(display!='none')// fetch in case if table row show
	{
		$.ajax({
			type: "POST",
			url: "country_select_options.php",
			success: function(data){
				$("#location").append(data);		
			}
		});
	}
	//$("#midright").load("manage_resellers_new.php?add=yes");
}


var globalTimeout = null;
$('#user_name').keyup(function() {
	var q=$(this).val();
	if(globalTimeout != null) clearTimeout(globalTimeout);	
	$('#loading').show();		
	globalTimeout=setTimeout(function(){
		$.ajax({
		type: "POST",
		url: 'search_client_new.php',
		data: {q:q},
		dataType:"json",
		success: function(data){
			$('#loading').hide();
			//$("#midright").html(msg);
			$("#tbodyClientData").html(data.trdata);
			$("#paging").html(data.paging);
			$(".cb-enable").click(function(){
				var parent = $(this).parents(".switch");
				$(".cb-disable",parent).removeClass("selected");
				$(this).addClass("selected");
				$(".checkbox",parent).attr("checked", true);
			});
			$(".cb-disable").click(function(){
				var parent = $(this).parents(".switch");
				$(".cb-enable",parent).removeClass("selected");
				$(this).addClass("selected");
				$(".checkbox",parent).attr("checked", false);
			});
		}
	});			
	},600);		
});

$(document).ready( function(){ 
	loadClientData('');
	$(".cb-enable").click(function(){
		var parent = $(this).parents('.switch');
		$('.cb-disable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('.checkbox',parent).attr('checked', true);
	});
	$(".cb-disable").click(function(){
		var parent = $(this).parents('.switch');
		$('.cb-enable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('.checkbox',parent).attr('checked', false);
	});
	
});
</script>
<script type="text/javascript">
/*autocomplete search*/
function log(event, data, formatted){
	$("<li>").html( !data ? "No match!" : "Selected: " + formatted).appendTo("#result");
}
function formatItem(row){
	return row[0] + " (<strong>id: " + row[1] + "</strong>)";
}
function formatResult(row){
	return row[0].replace(/(<.+?>)/gi, '');
}




//$("#user_name").autocomplete("search_client.php",{width:400,matchContains:true,max:25,scroll:true,matchCase:false,		scrollHeight:200,minChars:1,delay:0,multiple: false,mustMatch:false,autoFill:false,selectFirst:true,timeout:1000});


//$('#user_name').result(function(event, data, formatted){
//	if (data)
//	{		
//		$("#client_user_name").val(data[1]);
//		$(".search_form").submit();
//	}
//});
$(".search_form").submit(function() {	
	$("#loading").show();  
	u = $("#client_user_name").val();
	if(u=='' || $.trim(u).length<1 )
	{
		show_message("Please Provide Input","warning");
		$("#user_name").focus();
		$('#loading').hide();
		return false;
	}
	else{
		u2 = $("#user_name").val();
		frm_data=$(this).serialize();
		$.ajax({
			type: "POST",
			url: "<?=$pageName?>?submit2=submit",
			data: {user_name:u,user_name2:u2},
			success: function(msg){
				$('#loading').hide();
				//$("#midright").html(msg);
				
			}
		});
	}
	return false;
});


function add_client_submit()
{
	var user_name=$("#adduser_name").val();
	var mob_no=$("#mob_no").val();
	var pwd=$("#pwd").val();
	var code=$("#location").val();
	var balance=$("#balance").val();
	var utype=$("#utype").val();
	var user_email=$("#user_email").val();
	var update_detail=1;
	if(user_name=='' || $.trim(user_name).length<5 || $.trim(user_name).length>15)
	{
		show_message("Please Enter User Name(Minimum length 5 Character Maximun 15 Character) ","error");
		$("#adduser_name").focus();return false;
	}
	//if($("#adduser_name").val().length<5)
	//{error_message("Username Should Atleast Have 5 Characters");return false;}
	if(mob_no==''|| $.trim(mob_no).length<9 || isNaN(mob_no))
	{
		if(!isNaN(mob_no))
			show_message("Please Enter Proper Mobile Number","error");
		else
		{
			show_message("Please Enter Valid Mobile Number","error");
			$("#mob_no").val('');
		}
		$("#mob_no").focus();
		return false;
	}
	
	/*if(expiry=='' || $.trim(expiry).length<1 )
	{show_message("Please Select Expiry Date","error");
	$("#expiry").focus();return false;}*/
	if(utype=='')
	{
		show_message("Please Select User Type","error");
		$("#utype").focus();
		return false;
	}
	if(balance=='' || $.trim(balance).length<1)
	{
		show_message("Please Enter Balance","error");
		$("#balance").focus();
		return false;
	}
	
	if(user_email=='' || $.trim(user_email).length<1  )
	{
		show_message("Please Enter Valid Email id","error");
		$("#user_email").focus();
		return false;
	}
	else
	{
		$('#loading').show();
		$.ajax({
			type: "POST",
			url: "../action_layer.php?action=signup",
			data: "update_detail="+update_detail+"&username="+user_name+"&pwd="+pwd+"&code="+code+"&mobileNumber="+mob_no+"&balance="+balance+"&client_type="+utype+"&email="+user_email,
			success: function(msg){
				//alert(msg);
				if(msg=='User Sucessfully Registered')
				{
					show_message(msg,"success");
					$("#midright").load("<?=$pageName?>");
					$('#loading').hide(); 
				}
				else 
				{
					show_message(msg,"error");
					//$("#midright").load("manage_resellers_new.php?add=yes");
					$('#loading').hide(); 
				}
					/*$("#notifications").load("notifications.php");
					window.setTimeout("hide_message()",2000);*/					
			}
		});
	}
}
	
/*$(function(){			
	$('#expiry').datepicker({  minDate: '0D' ,changeYear: true, changeMonth: true, dateFormat: 'yy-mm-dd' });          
});*/

/*Export*/
function Check_Uncheck(source, targets) {
	$source = $(source);
	$targets = $('input[name="'+ targets + '"]');

	if ($source.is(':checked')) {
		$targets.attr('checked',true);
	} else {
		$targets.removeAttr('checked');
	}
	return true;
}	
function export_client()
{
	id=new Array()
	a=0;
	$("input.check_client:checked").each(function(){
		id[a]=$(this).val();
		a++;
	});
	if(id.length<1)
	{
		show_message("please select atleast one client to export","warning");
		return false;
	}
	window.location="../action_layer.php?action=63&check_id="+id;
}
/*Exe summary*/
function makeFrame(url) {
	ifrm = document.createElement("IFRAME");
	ifrm.setAttribute("src", url);
	ifrm.style.width = "100%";
	ifrm.style.height = "640px";
	$("#box1-tabular").html(ifrm);
}		

/*sort*/
function order_selection(selection_type,selection_field)
{
	var submitt="submit";
	$("#loading").show();
	$("#midright").load("<?=$pageName?>?select_type="+selection_type+"&select_field="+selection_field);
    $("#loading").hide();
}
	
/*Delete Client*/
function delete_client(id,type)
{
	if(confirm("Are You Sure Want To Delete This Client")==true)
	{
		if ($.browser.msie && $.browser.version.substr(0,1)<8)			
			$('.list'+id).hide();
		else
			$('.list'+id).css('background','#c00').fadeOut();
		$.ajax({
			type: "POST",
			url: "../action_layer.php?action=delete_client",//58
			data: "id="+id+"&type="+type,
			success: function(msg){
				if(msg=='Client could not be deleted')
				{ 				
					show_message(msg,"error");					
				}
				else if(msg=='Client deleted successfully and balance updated ')
				{
					show_message(msg,"success");
					<?php if(isset($pg_num) && $pg_num>0) {?>
					$("#midright").load("<?=$pageName?>?page_number="+<?php echo $pg_num;?>);
					<?php }else {?>					 
					$("#midright").load("<?=$pageName?>");
					<?php }?>
				}
			}
		});
	}
}


function client_status(cid,status)
{
	$.ajax({
		type: "POST",
		url: "../action_layer.php?action=updateStatus",
		data: "cid="+cid+"&cstatus="+status,
		success: function(msg){
			//alert(msg);
			if(msg='Client status updated.')
				show_message(msg,"success");
			else
				show_message(msg,"error");  
		}
	});
}
	
/*Settings*/

function edit_client_settings(form_id)
{
	var email=$("#email_"+form_id).val();
	var dob=$("#dob_"+form_id).val();
	var country=$("#country_"+form_id).val();
	var state=$("#state_"+form_id).val();
	var city=$("#city_"+form_id).val();
	var phone=$("#phone_"+form_id).val();
	var addr=$("#addr_"+form_id).val();
	var occup=$("#occup_"+form_id).val();
	
	$('#loading').show();  
	$.ajax({
		type: "POST",
		url: "../action_layer.php?action=41",
		data: "id="+form_id+"&update_detail=2&email="+email+"&dob="+dob+"&country="+country+"&state="+state+"&city="+city+"&phone="+phone+"&addr="+addr+"&occup="+occup,
		success: function(msg){
			// alert(msg);
			if(msg=='Update Successful')
			{
				show_message(msg,"success");
				$("#midright").load("<?=$pageName?>");
				$('#loading').hide();
			}
			else if(msg=='Please enter a Vaild Email Address <br>')
			{
				show_message(msg,"error");
				$('#loading').hide();
				$("#email_"+form_id).focus();
			}
			else if(msg=='Please enter valid phone.')
			{ 
				show_message(msg,"error");
				$('#loading').hide();
				$("#phone_"+form_id).focus();
			}
			else {
				show_message(msg,"error");
				$('#loading').hide(); 
			}
		}
	});			
}

/*funds*/	
function update_my_balance()
{
	$('#loading').show();
	$.ajax({
		type: "get",url: "../action_layer.php?action=73", dataType: "json",
		contentType: "application/json; charset=utf-8",
		success: function(data){
			$("#main_balance").html(data.main);
			$("#voice_balance").html(data.voice);
			$('#loading').hide();
		}
	});
}

function edit_funds_submit(form_id)
{
	var type;
	$('#button_text').attr('disabled',true);
	if(document.getElementById("type_"+form_id).checked==true)
		type=1;
	else
		type=2;
	//var expiry=$("#amt_"+form_id).val();
	//var sms=$("#sms_"+form_id).val();
	var amount=$("#amt_"+form_id).val();
	//var plan=$("#plan_"+form_id).val();
	var description=$("#description_"+form_id).val();
	//var transaction_type=$("#trans_sms_"+form_id).val();
	$.ajax({
		type: "POST",
		url: "../action_layer.php?action=update_profile",
		data: "id="+form_id+"&amount="+amount+"&type="+type+"&description='"+description+"'&update_detail=2",
		success: function(msg){
			if(msg=='Update Successful')
			{
				// load_next('manage_admin.php');
				$("#ajaxcontent"+form_id).hide();
				show_message(msg,"success");
			}
			else
			{
				show_message(msg,"error");
				$('#button_text').attr('disabled',false);
			}
		}
	});
                        
}
/*Password*/
function change_password(form_id)
{
	$('#loading').show();
	var new_pass=$("#new_pass_"+form_id).val();
	if(new_pass=='' || $.trim(new_pass).length<5 )
	{
		show_message("Please Enter Password(Minimum length 5 Character) ","error");
		$("#new_pass_"+form_id).focus();
		$('#loading').hide();
		return false;
	}
	else
	{
		$.ajax({
			type: "POST",
			url: "../action_layer.php?action=41",
			data: "id="+form_id+"&update_detail=4&new_pass="+new_pass,
			success: function(msg){
				show_message("New Password Saved","success");
				//$("#notifications").load("notifications.php");
				//$("#midright").load("manage_resellers_new.php");
				//window.setTimeout("hide_message()",2000);
				$("#midright").load("<?=$pageName?>");
				$('#loading').hide();
			}
		});
	}
	return false;
}

/*pagination*/
/*function load_next(id,num)
	{$("#loading").show();
		$("#midright").load("manage_resellers_new.php?submit2=submit&page_number="+num+"&id="+id,function() {$('#loading').hide();});
	}*/
</script>