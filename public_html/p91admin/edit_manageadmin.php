<?php
if(isset($_REQUEST['id']))
{
include_once('/home/voip91/public_html/newapi/general_class.php');
$genObj= new general_function();
if(!$genObj->check_admin())
$genObj->expire();
$dbh=$genObj->connect_db();

$result=mysql_query("select * from ms_user where user_pid='".$_REQUEST['id']."'");
$sender=mysql_result($result,0,'user_sender');
$type=mysql_result($result,0,'user_type');
$uname=mysql_result($result,0,'user_uname');
$expiryDate=  mysql_result($result,0,'user_expiry')
?>

<form>
      <tr >
       <td colspan="20">
       <h3 class="whitehd">Edit <?php echo $uname; ?></h3>
       <div class="fltlt outer">
       <label>First Name</label>            
       <div class="thefield"><input name="fname" type="text" id="fname" value="<?php echo mysql_result($result,0,'user_fname'); ?>" /></div>
                                
                                <label>Last Name</label>
                                <div class="thefield"><input name="lname" type="text" id="lname" value="<?php if(mysql_result($result,0,'user_lname')=="") echo "Last Name"; else echo mysql_result($result,0,'user_lname'); ?>" /></div>
                                
                                <label>User Name</label>
                                <div class="thefield"><input name="user_name2" type="text" id="user_name2" value="<?php echo $uname; ?>" disabled="disabled"/></div>
                                <div class="thefield">
                                <input type="button" class="medium green awesome" value="Save Changes" onClick="edit_client_submit('<?php echo $_REQUEST['id'];?>')"/></div>
                            </div>
                            
                            <div class="fltlt outer">
                                <label>Mobile No</label>
                                <div class="thefield"><input name="mob_no" type="text" id="mob_no" value="<?php echo mysql_result($result,0,'user_mobno'); ?>" /></div>
                                
                          <!--      <label>Expiry Date</label>
                                <div class="thefield"><input name="expiry" type="text" id="expiry" value="<?php  //echo $expiryDate; ?>" class="date" /></div>
                            -->
                                <label>Sender ID</label>
                                <div class="thefield"><input name="sender" type="text" id="sender" value="<?php if($sender!="") echo $sender; else echo $sender; ?>" /></div>
                                <label>User Type</label>
                                <div class="thefield">
                                <select name="utype2" id="utype2">
                                    <?php if($type==2) { ?>
                                <option value="2" selected="selected">Reseller</option>
                                <?php } else if($type==3){ ?>
                                <option value="3" selected="selected">User</option>
                                <option value="2">Reseller</option>
                                <?php } ?>
                                </select></div>    
                            </div>
                                   	
                            </td>
                                        
                            </tr>
                            </form>
                           
                            <?php	
                             }
							 ?>