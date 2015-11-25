<?php 
unset($pages);unset($page_number);unset($page);
unset($total_rows);unset($limit);unset($p);unset($i);

include_once('/home/voip91/public_html/newapi/user_function_class.php');
include_once("/home/voip91/public_html/newapi/general_class.php");
$genClsObj = new general_function();

if (!$genClsObj->check_reseller() && !$genClsObj->check_user() && !$genClsObj->check_admin())
{$genClsObj->expire();}


$limit=15;
$page=$_SERVER['PHP_SELF'];
//by pushpendra
error_reporting(0);
function user_search_admin($data,$limit)	
{
    $user_obj=new user_function_class();//class object
    $data=strtolower($data);
    if(is_numeric($limit) && $limit>0)
        $limit=" limit ".$limit;
    else
        $limit=" limit 50";
    if (strpos($data, " ")){			//if have any space, search only from first name or last name
        $newdata = explode(" ",str_replace("\n", " ", $data));
        for($i=0;$i<count($newdata);$i++){
        if(strlen($newdata[$i])>1)
        {
                if($substr!="")
                $substr.=" AND ";
                $substr.=" (LOWER(login) like '%".strtolower($newdata[$i])."%' OR LOWER(login) like '%".strtolower($newdata[$i])."%')";
                }
        }
	if(strlen($substr)>0)
	$query= "select * from clientsshared where". $substr." AND client_type!=1  $limit";
//echo $query; die();
	}
    //search if data is a number- search from mobile+ search from username
    elseif (is_numeric($data)){		
    $query= "select * from clientsshared where id_client like '%".$data."%'AND client_type!=1 UNION DISTINCT select * from clientsshared where lower(login) like '%".$data."%' AND client_type!=1  $limit";
    }
    //search if @, email + username
//    elseif (strpos($data, "@")){
//    $query= "select * from clientsshared where lower(user_email) like '%".$data."%'AND client_type!=1 and user_status!=3 UNION DISTINCT select * from ms_user where lower(user_uname) like '%".$data."%' AND client_type!=1 and user_status!=3 $limit";
//    }
    // search from username + name
    else{
       
        //Temporary commented
        $query = "(select * from clientsshared where lower(login) = '".$data."' AND client_type!=1) UNION DISTINCT(select * from clientsshared where lower(login) like '%".$data."%' AND client_type!=1 ) UNION DISTINCT (select * from clientsshared where (lower(login) like '%".$data."%' OR lower(id_client) like '%".$data."%') AND client_type!=1 ) $limit";
    }
    $dbh=$user_obj->connect_db();
            //mail("indoreankita@gmail.com","admin q1",$query);
    $result=mysql_query($query, $dbh) or $err=mysql_error();
    mysql_close($dbh);
    unset($user_obj);
    return $result;
}


function searchUser($SString,$searchBy,$isStrict)
{	
    $user_obj=new user_function_class();//class object
	if($_SESSION['id']==1 || $user_obj->is_admin())
		$sql="select * from clientsshared where client_type!=1 and ";
	
	if(isset($isStrict) && $isStrict=='true')
	$key='';
	else {
		$key='%';
	}
	if(strlen($SString)>0 && $searchBy=='2')
	$sql.="(lower(login) like '".$key.$SString.$key."')";
	
	else if(strlen($SString)>0 && $searchBy=='1')
	$sql.="(lower(id_client) like '".$key.$SString.$key."' or lower(type) like '".$key.$SString.$key."')";
	
	else if(strlen($SString)>0 && $searchBy=='3')
	$sql.="(id_reseller like '".$key.$SString.$key."')";
	//$sql.="(lower(user_fname) like '".$key.$SString.$key."' or lower(user_lname) like '".$key.$SString.$key."')";
	
	//echo $sql;
	$dbh=$user_obj->connect_db();
	$result=mysql_query($sql,$dbh);
	mysql_close($dbh);
        unset($user_obj);
	if (!$result)
	die ("Fatal Error in Loading Filtered User List");
	return $result;
}

if(isset($_REQUEST['page_number']))
$first_limit=($_REQUEST['page_number']-1)*$limit;
else
$first_limit=0;


if(isset($_REQUEST['submit2']))
{

if(isset($_REQUEST['user_name2']))
{
	$string=explode(" ",$_REQUEST['user_name2']);
	if(count($string)>5)
	{
		if(strlen($string[0])>0)
		$s_=strtolower($string[0]);
		if($string[1]=='('){
			if(strlen($string[2])>0)
			$s_clid=$string[2];
			if(strlen($string[5])>0)
			$s_login=$string[5];
			if(strlen($string[6])>1)
			$s_login=$string[6];
			if(strlen($string[7])>0)
			$s_login=$string[7];
		}else{
			$string=explode("(",$_REQUEST['user_name2']);
			$s_login=strtolower($string[0]);		
			$string2=explode(")",$string[1]);
			$s_clid=trim($string2[0]);	
			$string3=explode(":",$string2[1]);
			$s_uname=trim($string3[1]);
		}
	}
	else
	{
		if(isset($_REQUEST['user_name']) && $_REQUEST['user_name']!='')
		$s_uname=$_REQUEST['user_name'];
		else
		$s_uname=$_REQUEST['user_name2'];
	}
	
}
else
{
	$s_login='';
	$s_uname=$_REQUEST['user_name'];
	$s_clid='';
}
$SString=$s_uname;

{
	
	if(isset($_REQUEST['search_by']))
	{
		$serch_by=$_REQUEST['search_by'];
		if($serch_by==1)
		{
			$SString=$s_login=$s_uname;
			$s_clid='';
			$s_uname='';
		}
		if($serch_by==2)
		{
			$s_clid='';
			$s_login='';
		}
		if($serch_by==3)
		{
			$SString=$s_clid=$s_uname;
			$s_login='';
			$s_uname='';
		}

	}
	
	
	if(isset($_REQUEST['strict']) && $_REQUEST['strict']=='true')
	{
		$result=searchUser($SString,$serch_by,$_REQUEST['strict']);
		$total_rows=mysql_num_rows($result);
	}
	else{
		
		$result=user_search_admin($s_uname,10);
		$total_rows=mysql_num_rows($result);
		//$result=load_filtered_users($s_login,$s_uname,$s_clid,0,$first_limit,$limit,0);
	//$total_rows=load_total_filtered_users($s_login,$s_uname,$s_clid,0);
	}
}
}
else
{
    if($total_rows>0)
    {
    if(isset($_REQUEST['page_number']))
                            $page="manage_admin.php?&page_number=".$_REQUEST['page_number'];
                    else
                            $page="manage_admin.php";
    }
    else
    {
        $result=user_search_admin('',0);
	//echo ' rahu  '.$total_rows=mysql_num_rows($result);
    }
}

if(isset($_REQUEST['id']))
{
	$result=$user_obj->load_user_details($_REQUEST['id']);
	if($result)
	$total_rows=mysql_num_rows($result);
	else {
		echo 'No Record Found';
		
	}	
}
$pages=ceil($total_rows/$limit);


?>


<script type="text/javascript">
     $(document).ready(function() {
        getCcode();
    });
$(function () {			
            $('#expiry').datepicker({  minDate: '0D' ,changeYear: true, changeMonth: true, dateFormat: 'yy-mm-dd' });          
			$(".tip").tipTip();
             
        });
       
    function getCcode()
    {
        $("#ccode").val('+'+$("#country").val());
    }
 function clearComment(id)
 {
     if($('#comment_'+id).val()=='Enter Your Comment')
         {
             $('#comment_'+id).val('');
         }
 }
 function changeClientStatus()
 {                
       var status='';//$('#status'+cid).attr('checked');
       clientId=arguments[0].cid;
       cmnt=arguments[0].comment;
	if($('#status'+clientId).attr('checked'))
		status=1;
	else
		status=2;
		
	if(status==1)
	$('#statusLabel'+clientId).removeClass('red').addClass('green').text('Enabled');
	else
	$('#statusLabel'+clientId).removeClass('green').addClass('red').text('Disabled');
	
			$.ajax({
		   type: "POST",
		   url: "../action_layer.php?action=74",
		   data: "cid="+clientId+"&cstatus="+status+"&comment="+cmnt,
		   success: function(msg){
			   if(msg='Client Status Updated Successfully')
			   {				   
			   	show_message(msg,"success");
			   }
			   else
			   show_message(msg,"error");  
		   }
		 });
 }                 
function showComment(cid)
{
	//Commented by at 29 dec 3:54  Rahul Chordiya
	var commentBox="comment_"+cid;
	var btnname="status"+cid;
        var comment=$("#comment_"+cid).val();
	document.getElementById(commentBox).style.display="block";          
       // document.getElementById(btnname).style.display="none";
        if( $.trim(comment)=='' || $.trim(comment.toLowerCase())=='enter your comment')
        {
            if($('#status'+cid).attr('checked'))
                $('#status'+cid).attr('checked',false);
            else
                $('#status'+cid).attr('checked',true);
            
            $('#comment_'+cid).val('Enter Your Comment');
        }
        else
            {
            changeClientStatus({cid : cid,comment : comment});
            $('#comment_'+cid).val('');
            document.getElementById(commentBox).style.display="none";          
            //document.getElementById(btnname).style.display="block";
            }
         
}
</script>
<div class="section table_section">
<!--[if !IE]>start title wrapper<![endif]-->
    <div class="title_wrapper">
    <h2>Manage Clients</h2>	
    <a onClick="add_client()"href="javascript:;" class="fltrt button white tip" title="+ Add New Client">+</a>						
    <span class="title_wrapper_left"></span>
    <span class="title_wrapper_right" style="display: block; "></span>
    </div><!--end title_wrapper-->
	<!--[if !IE]>end title wrapper<![endif]-->
	<!--[if !IE]>start section content<![endif]-->
    <div class="section_content">
    <!--[if !IE]>start section content top<![endif]-->
        <div class="sct">        
            <div class="sct_right" >            	
		 <?php if(isset($_REQUEST['add']))
		{
                     
		?>
               
		<div class="table_wrapper">
                    <div class="table_wrapper_inner" >
                        <form name="f" id="myForm" method="post" action="action_layer.php?action=signup">
                            <table><tr><td>
                    <h3 class="whitehd">Add New Client</h3>
                    <div class="fltlt outer">
                       
	<label>Choose Username</label>
    <div class="clf"></div>
    <input name="username" id="username" type="text" class="username fltlt" />
    <input name="check" id="check_btn" type="button" class="small green awesome fltlt" onClick="check_user_exist(); return false;" value="Check Availablity" style="line-height:23px;" />
    <div class="clf"></div>
    <img src="images/loading.gif" title="image" id="loading_img"  style="display:none" />
                        
                        <label>Choose your country</label> <div class="clf"></div>
<select tabindex="1" onchange="getCode();" name="location" id="location" class="uField valid">
 <option value="93">Afghanistan</option><option value="355">Albania</option><option value="213">Algeria</option><option value="376">Andorra</option><option value="244">Angola</option><option value="1">Antigua and Barbuda</option><option value="54">Argentina</option><option value="374">Armenia</option><option value="61">Australia</option><option value="43">Austria</option><option value="994">Azerbaijan</option><option value="973">Bahrain</option><option value="880">Bangladesh</option><option value="1">Barbados</option><option value="32">Belgium</option><option value="591">Bolivia</option><option value="387">Bosnia and Herzegovina</option><option value="55">Brazil</option><option value="44">British Indian Ocean Territory</option><option value="359">Bulgaria</option><option value="1">Canada</option><option value="235">Chad</option><option value="56">Chile</option><option value="86">China</option><option value="57">Colombia</option><option value="682">Cook Islands</option><option value="506">Costa Rica</option><option value="385">Croatia</option><option value="53">Cuba</option><option value="357">Cyprus</option><option value="420">Czech Republic</option><option value="45">Denmark</option><option value="1">Dominican Republic</option><option value="593">Ecuador</option><option value="20">Egypt</option><option value="503">El Salvador</option><option value="372">Estonia</option><option value="358">Finland</option><option value="33">France</option><option value="49">Germany</option><option value="233">Ghana</option><option value="350">Gibraltar</option><option value="30">Greece</option><option value="590">Guadeloupe</option><option value="502">Guatemala</option><option value="44">Guernsey</option><option value="509">Haiti</option><option value="504">Honduras</option><option value="36">Hungary</option><option value="354">Iceland</option><option value="91">India</option><option value="98">Iran</option><option value="964">Iraq</option><option value="353">Ireland</option><option value="972">Israel</option><option value="39">Italy</option><option value="225">Ivory Coast</option><option value="1">Jamaica</option><option value="81">Japan</option><option value="44">Jersey</option><option value="962">Jordan</option><option value="254">Kenya</option><option value="686">Kiribati</option><option value="965">Kuwait</option><option value="856">Laos</option><option value="961">Lebanon</option><option value="231">Liberia</option><option value="352">Luxembourg</option><option value="261">Madagascar</option><option value="60">Malaysia</option><option value="356">Malta</option><option value="596">Martinique</option><option value="230">Mauritius</option><option value="52">Mexico</option><option value="212">Morocco</option><option value="977">Nepal</option><option value="31">Netherlands</option><option value="64">New Zealand</option><option value="505">Nicaragua</option><option value="234">Nigeria</option><option value="47">Norway</option><option value="968">Oman</option><option value="92">Pakistan</option><option value="507">Panama</option><option value="595">Paraguay</option><option value="51">Peru</option><option value="63">Philippines</option><option value="48">Poland</option><option value="351">Portugal</option><option value="974">Qatar</option><option value="262">Reunion</option><option value="40">Romania</option><option value="7">Russia</option><option value="250">Rwanda</option><option value="966">Saudi Arabia</option><option value="221">Senegal</option><option value="381">Serbia</option><option value="65">Singapore</option><option value="421">Slovakia</option><option value="386">Slovenia</option><option value="252">Somalia</option><option value="27">South Africa</option><option value="34">Spain</option><option value="94">Sri Lanka</option><option value="46">Sweden</option><option value="41">Switzerland</option><option value="963">Syria</option><option value="886">Taiwan</option><option value="66">Thailand</option><option value="1">Trinidad and Tobago</option><option value="216">Tunisia</option><option value="90">Turkey</option><option value="256">Uganda</option><option value="380">Ukraine</option><option value="971">United Arab Emirates</option><option value="44">United Kingdom</option><option value="1">United States</option><option value="1">United States Minor Outlying Islands</option><option value="598">Uruguay</option><option value="58">Venezuela</option><option value="84">Vietnam</option><option value="967">Yemen</option><option value="263">Zimbabwe</option>
</select>
                        <br> 
    <label>Phone Number</label><br />
    <table><tr><td><input name='code' value="code" type="text" id="code" /></td>
    <td style="padding:0 0 0 5px;"><input type="text" name='mobileNumber' id='mobileNumber' onFocus="if (this.value == 'Phone number') { this.value = ''; }" value="Phone number" />
    </td></tr>
    <tr><td colspan="2"><div id="moberror"></div></td></tr></table>
    
    <label>Email</label>
    <input type="text" name='email' id='email' onFocus="if (this.value=='Email address') { this.value=''; }" value="Email address" />
    <div id="emailerror"></div>
                        
                        
               <label>Choose Password</label>
	<input type="password" name='password' id='password' />
	<label>Re-Enter Password</label>
	<input type="password" name='repassword' id='repassword' />		
	<label>Choose Currency</label>
     <select name="currency" id="currency">
            <option value="0">Select Currency</option>
            <option value="1">USD</option>
            <option value="2">INR</option>
            <option value="3">AED</option>
          </select>
        <div style="display: none">
    <label>Reseller/User</label>
     <select name="client_type" id="client_type">
            <option value="3" selected>User</option>
            <option value="2">Reseller</option>
      </select>
        </div>
    <input type="submit" class="large blue awesome" value="I Agree & Register"/>
                
                        
                        
                        
                        
                        
                        
                        
                        
                        
                    <?php // include_once('../register.php');
                    ?>
                        <?php // include_once('../inc/req_form.php');?>
                   
                    </div></td>
                    
		</tr></table></form>
		</div></div>
		<?php } if(mysql_num_rows($result)>0){?>
            <form class="plain" action="" name="form1" method="post">
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
                     
                                  while($rows=mysql_fetch_assoc($result)){
                                        $cur_type=$rows['id_currency'];
                                        switch ($cur_type)
                                            {
                                            case 1:
                                                   $cur_txt="USD"; 
                                            break;
                                            case 2:
                                                     $cur_txt="INR"; 
                                            break;
                                            case 3:
                                                     $cur_txt="AED"; 
                                            break;
                                            default:
                                                    $cur_txt="none"; 
                                            }
                        
                                             $user=$rows['login'];
                                             $balance=$rows['account_state'];
                                            $cur_ty=$cur_txt;
                                           $triff=$rows['id_tariff'];
                                           $client_type=$rows['client_type'];                                                                   
                                            $id=$rows['id_client'];                                                                   
                                   
                           if(isset($_REQUEST['submit2'])) 
                            {								
                            $page="manage_admin.php?submit2=submit";
                            }
                            else
                            {
                            $page="manage_admin.php?rand=893475";
                            }?>
                            
                             
                          
                                                                  
                        
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
            <a onClick="load_details(<?php echo $id; ?>,'manage_funds.php?form_id=<?php echo $id; ?>',this)"  href="javascript:;" class="fund">Fund</a>
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
          <td colspan="20" class="ajxcontent ajxActive" id="ajaxcontent<?php echo $id; ?>"></td> 
          
          
                            </tr>
                            
                              <?php    
                          }  ?>  
                            
                            
                                </tbody>
                            
                        </table>
                    </div><!--end table_wrapper_inner-->
                </div><!--end table_wrapper-->								
            </form>
      
        </div><!--end of sct_right-->
        </div><!--end of sct-->
   
      
      
 <?php   
 
 
}//End of if num rows greater zero
?>
<input type="hidden" name="page" value="<?php echo $page; ?>" />
</div><!--End section_content -->
</div><!--End table_section -->
<?php unset($genClsObj);unset($page);unset($page_number);

unset($total_rows);unset($limit);unset($p);unset($i);
unset($_POST); unset($_GET); unset($data);
unset($val); unset($result); unset($id);
unset($first_limit);
unset($_REQUEST);
?>