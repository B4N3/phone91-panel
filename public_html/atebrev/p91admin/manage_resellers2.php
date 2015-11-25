<?php //include_once("../business_layer.php");
include_once('/home/voip91/public_html/newapi/user_function_class.php');
//$comm_obj	=	new general_function();
//if(!$user_obj->check_admin())
//$user_obj->expire();

//$limit=15;
//$page=$_SERVER['PHP_SELF'];
//by pushpendra
error_reporting(0);
//include_once('/home/voip91/public_html/newapi/user_function_class.php');
//
//$dbh=$user_obj->connect_db(1);
//$countryCodes=$dbObj->select_fields(" *","country",'','','',$dbh);
//mysql_close($dbh);
//$strCodes='';
//while($row = mysql_fetch_array($countryCodes))
//{  
//    if($row['code']!=0)
//    {
//        $strCodes.="<option  value=".$row['code']." >".$row['country']."</option>";
//    }
//}
//function user_search_admin($data,$limit)	//created by pushpendra for searching users in admin panel
//{
//    $user_obj=new user_function_class();//class object
//    $data=strtolower($data);
//    if(is_numeric($limit) && $limit>0)
//        $limit=" limit ".$limit;
//    else
//        $limit=" limit 50";
//    if (strpos($data, " ")){			//if have any space, search only from first name or last name
//        $newdata = explode(" ",str_replace("\n", " ", $data));
//        for($i=0;$i<count($newdata);$i++){
//        if(strlen($newdata[$i])>1)
//        {
//                if($substr!="")
//                $substr.=" AND ";
//                $substr.=" (LOWER(user_fname) like '%".strtolower($newdata[$i])."%' OR LOWER(user_lname) like '%".strtolower($newdata[$i])."%')";
//                }
//        }
//	if(strlen($substr)>0)
//	$query= "select * from ms_user where". $substr." AND user_type!=1 and user_status!=3 $limit";
////echo $query; die();
//	}
//    //search if data is a number- search from mobile+ search from username
//    elseif (is_numeric($data)){		
//    $query= "select * from ms_user where user_mobno like '%".$data."%'AND user_type!=1 and user_status!=3 UNION DISTINCT select * from ms_user where lower(user_uname) like '%".$data."%' AND user_type!=1 and user_status!=3 $limit";
//    }
//    //search if @, email + username
//    elseif (strpos($data, "@")){
//    $query= "select * from ms_user where lower(user_email) like '%".$data."%'AND user_type!=1 and user_status!=3 UNION DISTINCT select * from ms_user where lower(user_uname) like '%".$data."%' AND user_type!=1 and user_status!=3 $limit";
//    }
//    // search from username + name
//    else{
//       
//        //Temporary commented
//        $query = "(select * from ms_user where lower(user_uname) = '".$data."' AND user_type!=1 and user_status!=3) UNION DISTINCT(select * from ms_user where lower(user_uname) like '%".$data."%' AND user_type!=1 and user_status!=3) UNION DISTINCT (select * from ms_user where (lower(user_fname) like '%".$data."%' OR lower(user_lname) like '%".$data."%') AND user_type!=1 and user_status!=3) $limit";
//    }
//    $dbh=$user_obj->connect_db();
//            //mail("indoreankita@gmail.com","admin q1",$query);
//    $result=mysql_query($query, $dbh) or $err=mysql_error();
//    mysql_close($dbh);
//    unset($user_obj);
//    return $result;
//}

function searchUser($SString,$searchBy,$isStrict)
{	
    $user_obj=new user_function_class();//class object
	if($_SESSION['id']==1 || $user_obj->is_admin())
		$sql="select * from ms_user where user_type!=1 and user_status!=3 and ";
	else 
		$sql="select * from ms_user where user_type!=1 and user_status!=3 and user_userid='".$_SESSION['id']."' and ";
	if(isset($isStrict) && $isStrict=='true')
	$key='';
	else {
		$key='%';
	}
	if(strlen($SString)>0 && $searchBy=='2')
	$sql.="(lower(user_uname) like '".$key.$SString.$key."')";
	
	else if(strlen($SString)>0 && $searchBy=='1')
	$sql.="(lower(user_fname) like '".$key.$SString.$key."' or lower(user_lname) like '".$key.$SString.$key."')";
	
	else if(strlen($SString)>0 && $searchBy=='3')
	$sql.="(user_mobno like '".$key.$SString.$key."')";
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
		$s_name=strtolower($string[0]);
		if($string[1]=='('){
			if(strlen($string[2])>0)
			$s_mobno=$string[2];
			if(strlen($string[5])>0)
			$s_uname=$string[5];
			if(strlen($string[6])>1)
			$s_uname=$string[6];
			if(strlen($string[7])>0)
			$s_uname=$string[7];
		}else{
			$string=explode("(",$_REQUEST['user_name2']);
			$s_name=strtolower($string[0]);		
			$string2=explode(")",$string[1]);
			$s_mobno=trim($string2[0]);	
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
	$s_name='';
	$s_uname=$_REQUEST['user_name'];
	$s_mobno='';
}
$SString=$s_uname;

{
	
	if(isset($_REQUEST['search_by']))
	{
		$serch_by=$_REQUEST['search_by'];
		if($serch_by==1)
		{
			$SString=$s_name=$s_uname;
			$s_mobno='';
			$s_uname='';
		}
		if($serch_by==2)
		{
			$s_mobno='';
			$s_name='';
		}
		if($serch_by==3)
		{
			$SString=$s_mobno=$s_uname;
			$s_name='';
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
		//$result=load_filtered_users($s_name,$s_uname,$s_mobno,0,$first_limit,$limit,0);
	//$total_rows=load_total_filtered_users($s_name,$s_uname,$s_mobno,0);
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

$dbh=$user_obj->connect_db();
$routesql="select * from ms_route";
$routelistresult=mysql_query($routesql,$dbh);
mysql_close($dbh);
if(!$routelistresult)
die("Fatal Error In Loading Routes");
$routes= $routelistresult;

$allRoute=array();
if(currentIP!=netcoreIP && currentIP!=worldIP)
{
    $curRouteList=$user_obj->getActiveCurrentRoutes();
    $cntr=1;
    while($curRouetRow=  mysql_fetch_row($curRouteList))
    {
        $allRoute[$cntr]=$curRouetRow[0];
        $cntr++;
    }
}
?>

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
		<form><table><tr><td>
                    <h3 class="whitehd">Add New Client</h3>
                    <div class="fltlt outer">
                    <label class="lbl">First Name</label>
                    <div class="thefield"><input name="fname" type="text" id="fname" value="" /></div>			
                    <label class="lbl">Last Name</label>
                    <div class="thefield"><input name="lname" type="text" id="lname" value="" /></div>			
                    <label class="lbl">User Name</label>            
                    <div class="thefield"><input name="adduser_name" type="text" id="adduser_name" value="" /></div>			
                    <label class="lbl">Mobile</label>
                    <div class="thefield">
                    <?php // if(currentIP==vterminationIP && $_SESSION['id']==1) { ?>
                    <input type="text" readonly="" id="ccode" name="ccode" class="uField fields" style="width:18%; text-align:right;"/>
                    <?php // } ?>
                    <input name="mob_no" type="text" id="mob_no" value="" />
                    </div>			
                    <div class="thefield"><input type="button" class="small green button" value="Add New Client" onClick="add_client_submit()"/></div>
                    </div></td>
                    <td colspan="20">
                    <div class="fltlt outer">
                    <label class="lbl">Expiry</label>
                    <div class="thefield"><input name="expiry" type="text" id="expiry" value="" /></div>				
                    <label class="lbl">Balance</label>
                    <div class="thefield"><input name="balance" type="text" id="balance" value="" /></div>				
                    <label class="lbl">User Type</label>
                    <div class="thefield">
                    <select name="utype" id="utype">
                    <option value="3">User</option>
                    <option value="2">Reseller</option>
                    </select></div> 
                    <label class="lbl">Email</label>
                    <div class="thefield"><input name="email" type="text" id="email" value="" /></div>
                    <?php // if(currentIP==vterminationIP && $_SESSION['id']==1) { ?>
                    <label class="lbl">Country</label>
                    <div class="thefield"> <select id="country" name="country" onchange="getCcode();">
                        
                        </select>
                    </div> </div><?php // } ?>
                    </td>            
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
                        while($row=mysql_fetch_assoc($result))
                        {
                                $voice_bal=0;
                                $id=$row['user_pid'];
                                $name=$row['user_fname']." ".$row['user_lname'];
                                $contact_no=$row['user_mobno'];
                                $email=$row['user_email'];
                                $user_type=$row['user_status'];
                                $type=$row['user_type'];
                                $uname=$row['user_uname'];
                                $expiry=$row['user_expiry'];
                                //$balance=$row['user_bal'];
                               // $balance=$user_obj->getUserBalance($id,1);
                                $sender=$row['user_sender'];
                                $gateway=$row['user_route'];
                                $dnd=$row['user_dnd'];                                
                                $resellerid=$row['user_userid'];
                                //$reseller_name=$user_obj->getUserName($resellerid);
                                $del_per=$row['user_delivery'];
                                $ratio=$row['user_fakedel'];
                                if(isset($row['user_dialplan']))
                                    $dial_plan=$row['user_dialplan'];
                                else
                                    $dial_plan=0;//Default
                                $sender_option=$row['user_sender_option'];
                                if(isset($row['user_voice_bal']))
                                $voice_bal=$row['user_voice_bal'];
                                if(isset($row['user_tariff']))
                                $tariff_plan=$row['user_tariff'];
                               // if(currentIP==gcomIP)
                               /// $dnd_bal=$user_obj->getUserBalance($id,2);
                              //  if(currentIP==vterminationIP || currentIP==wwmkIP)
                              //  $template_bal=$user_obj->getUserBalance($id,4);
                                switch($type) { case 2: $utype="Reseller"; break; case 3: $utype="User"; break; }
                            if(isset($_REQUEST['submit2'])) 
                            {
								
                            $page="manage_admin.php?submit2=submit";
                            }
                            else
                            {
                            $page="manage_admin.php?rand=893475";
                            }
                            ?>
                            <tr>
                            <!--<td ><input type="checkbox" name="check_list[]" value="<?php echo $id; ?>" class="check_client"></td>-->											
                            <td class="txt userInfoTd">
                                <fieldset class="userDetail" >
                                <?php if(currentIP==vterminationIP && $resellerid==2){ ?>
                                    <legend>Under : msg91</legend>
                                <?php }else {?>
                                    <legend>Under : other Reseller</legend>
                                    <?php }?>
                                <div class="bigUname <?php echo $utype ?> tip" title="<?php echo $utype ?>">
                                <?php echo $uname; ?>
                                </div>
                                <div>Name: <?php echo $name; ?></div>                                  
                                <div>Contact: <?php echo $contact_no;?></div>
                                <?php if($email!='Feed Email')?>
                                <div>Email: <?php echo $email;?></div>
                                <div>UserId: <?php echo $id;?></div>
                                
                                <?php       
                                
                                echo "<div><span class='icon icon_reseller'></span><a href='../action_layer.php?action=87&id=$id' class='button black small'>&laquo; Login as</a>";
                                //if(currentIP==vterminationIP || currentIP==online160IP)
                                if(isUsingMongo)
                                {
                                        echo "<span class='button black small'  onclick='loadpage(\"trackMongoMsg.php?uname=$uname\",\"#ajax_content\")'>Track SMS</span>";
                                }
                                else
                                echo "<span class='button black small'  onclick='loadpage(\"track_messages.php?uname=$uname\",\"#ajax_content\")'>Track SMS</span>"; 
			if(isUsingVoicePanel==1)
			{
				echo  "<span class='button black small'  onclick='loadpage(\"track_messages.php?action=voice&uname=$uname\",\"#ajax_content\")'>Track Voice</span>"; 
			}
                                ?>
                                </fieldset>
                            </td>                            
                            <!--<td class="txt"><?php //echo $reseller_name; ?></td>--> 
                            <td class="actions_menu">
                                
        <?php
        //if($_SESSION['id']==4585) {
            $status_dec = 'Disabled';
            $class = 'red';
            $title = 'Click to Enable';
            switch ($user_type) {
                case 0:$ch_str = '';
                    break;
                case 1: $ch_str = 'checked="checked"';
                    $status_dec = 'Enabled';
                    $class = 'green';
                    $title = 'Click to Disable';
                    break;
                case 2: $ch_str = '';
                    break;
                case 3: $ch_str = '';
                    break;
            }
            if(currentIP==vterminationIP) {
            ?>
                                <textarea style="display:none;" id="comment_<?php echo $id; ?>" name="comment_<?php echo $id; ?>" rows="2" cols="20" onFocus="clearComment('<?php echo $id; ?>');"></textarea>
            <input style="position:absolute;" type="checkbox" id="status<?php echo $id; ?>" <?php echo $ch_str; ?> onChange="showComment('<?php echo $id; ?>');" class="button white" />
            <label style="width:50px;" for="status<?php echo $id; ?>" id="statusLabel<?php echo $id; ?>"  class="statusLabel button <?php echo $class; ?> tip" title="<?php echo $title; ?>">
    <?php echo $status_dec; ?></label>
<?php //}
 } else { ?> 
            <input style="position:absolute;" type="checkbox" id="status<?php echo $id; ?>" <?php echo $ch_str; ?> onChange="client_status(<?php echo $id; ?>);" class="button white" />
            <label style="width:50px;" for="status<?php echo $id; ?>" id="statusLabel<?php echo $id; ?>" class="statusLabel button <?php echo $class; ?> tip" title="<?php echo $title; ?>">
    <?php echo $status_dec; ?></label>
     <?php } ?>
        <div>
            <a onClick="load_details(<?php echo $id; ?>,'../user/manage_funds.php?form_id=<?php echo $id; ?>',this)"  href="javascript:;" class="fund">Fund</a>
        </div>							
        <div>
            <a onClick="load_details(<?php echo $id; ?>,'../user/change_client_password.php?form_id=<?php echo $id; ?>',this)"  href="javascript:;" class="pass">Password</a>
        </div>
        <div>
            <a onClick="edit_client('<?php echo $id; ?>','<?php echo "edit_manageadmin.php?id=" . $id; ?>','this')"href="javascript:;" class="edit">edit</a>
        </div>
        <div>
            <a onClick="delete_client('<?php echo $id; ?>')" class="delete" href="javascript:;">Delete</a>									
        </div>
              <?php// if(currentIP==vterminationIP && $_SESSION['id']==34188) { ?>
        <div>
            <a onClick="load_details('<?php echo $id; ?>','<?php echo "edit_Expiry.php?id=".$id; ?>','this')"href="javascript:;" class="exdate">Expiry Date</a>
        </div> 
          <?php //}?>
        <?php 
                if (currentIP == vterminationIP) {
                    ?>
            <div id='rand_sender_div' style="display:none">
                <select id="sender<?php echo $id; ?>" onChange="sender_option(<?php echo $id; ?>);" >							
                    <option value="1" <?php
                        switch ($sender_option) {
                            case 0: break;
                            case 1: echo 'selected="selected"';
                                break;
                        }
                        ?>>Enable</option>
                    <option value="0" <?php
                        switch ($sender_option) {
                            case 0: echo 'selected="selected"';
                            case 1: break;
                        }
                        ?>>Disable</option>
                </select> 
            </div> <?php } ?>
    </td>
                   
    
                <td id="NextAjaxContent<?php echo $id; ?>" >
                 <?php echo "<span class='button black small'  onclick='loadpage(\"manage_admin_step2.php?user_pid=$id\",\"#NextAjaxContent$id\")'>More >> </span>"; ?>
                </td>
                            </tr>                            
                            <tr>
                            <td colspan="20" class="ajxcontent ajxActive" id="ajaxcontent<?php echo $id; ?>"></td>                            </tr>           
                            <?php 
                            } 
                            ?></tbody>                     
                       </table>
                    </div><!--end table_wrapper_inner-->
                </div><!--end table_wrapper-->								
            </form>
        <?php 		
        if($pages>1) 
        { 
        if(isset($_REQUEST['submit2'])) 
        {
			if(isset($_REQUEST['user_name2']))
			$page=$page."&user_name2=".urlencode($_REQUEST['user_name2']);
			else if(isset($_REQUEST['user_name']))
			$page=$page."&user_name=".$_REQUEST['user_name'];
            
        }
        else
        {
        $page="manage_admin.php?rand=893475";
        }
		if(isset($_REQUEST['search_by']))
			$page=$page."&search_by=".$_REQUEST['search_by'];
        //if($pages>15) 
        { 
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
        ?>
        </div><!--end of sct_right-->
        </div><!--end of sct-->
    <table>
        <tfoot>
		<tr>
        <td colspan="20" class="first last"><div class="pagination" id="pagination">
        <ul class="pag_list">
        <?php 	
        for($i=$start_page;$i<=$end_page;$i++) 
        { 
        if($i==1) 
        { 
        ?>
        <li onClick="load_next('<?php echo $page."&page_number=1";?>')">
        <?php //echo "here1"; ?>
        <a id="mng_client1"href="javascript:;"><?php if((!isset($_REQUEST['page_number']))||($_REQUEST['page_number']<=1))  echo '<strong>1</strong>'; else echo '1'; ?></a>
        </li>
        <?php 
        } 
        else 
        { 
        ?>
        <li onClick="load_next('<?php echo $page."&page_number=".$i;?>')">
        <a id="mng_client<?php echo $i; ?>"href="javascript:;">
        <?php if(isset($_REQUEST['page_number']) && $_REQUEST['page_number']==$i) echo '<strong>'.$i.'</strong>';else echo $i; ?></a>
        </li>
        <?php 
        }
        } ?>
        </ul>
        </div></td></tr></tfoot></table> 	
    <?php 
    }    
    } 
}//End of if num rows greater zero
?>
<input type="hidden" name="page" value="<?php echo $page; ?>" />
</div><!--End section_content -->
</div><!--End table_section -->
<?php unset($user_obj);?>