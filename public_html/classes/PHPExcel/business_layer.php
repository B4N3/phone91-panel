<?php
//ver 1.188
//-- Latest Change on April 03, 2009 ; New http api for sending sms
//-- Latest Change on April 04, 2009 ; Distinct Number While Sending SMS
//-- Latest Change on June 26, 2009 ; New API

session_start();
// This is a Business Layer contatining most of the logic of the code
switch ($_REQUEST['action'])
{
case 1:   // Check Login
check_login();
break;

case 2: // Add New Group
create_group();
break;

case 3: // Edit Group
edit_grp_name();
break;

case 4: // Delete Group
del_group();
break;

case 5: // Add Saved Message
add_saved_message();
break;

case 6: // Edit Saved Message
edit_saved_message();
break;

case 7: // Del Saved Message
delete_saved_msg();
break;

case 8://Add entry in Address Book
add_addr_book();
break;

case 9: //Edit entry in Address Book
edit_addr_book();
break;

case 10: //Change Password
change_password();
break;

case 11: //Change Password
change_user_password();
break;

case 12: //Manage Users
manage_users($_REQUEST['enable'],$_REQUEST['ban'],$_REQUEST['delete'],$_REQUEST['mng']);
break;

case 13: //egister User
register_user();
break;

case 14: //Edit User Details
edit_user_detail();
break;

case 15: //Edit User Balance
update_balance();
break;

case 16: // Get User Balance
get_user_balance();
break;

case 20://Update Admin Balance
update_admin_balance();
break;

case 21: //Send SMS
broadcast_sms();
break;

case 22: // Del Address Book
del_addr_book();
break;

case 23: //Change Sender ID
change_senderid();
break;

case 24: //Update Subsite Data
update_data();
break;

case 25: //Update Subsite Details
update_subsite();
break;

case 26:
forgot_password();
break;

case 30:
logout();
break;

}


// Function to Generate Unique Session ID
//------------------------------------------------------------------------
function generate_unique_id() {
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$hour = date('H', time());
$min = date('i', time());
$sec = date('s', time());
return sprintf("%02d%04d%02d-%02d%02d%04d-%04d-%02d%04d", $sec, rand(0, 9999),$hour, $month, $min, rand(0, 9999), rand(0, 9999), $day, $year);
}
//------------------------------------------------------------------------

//------------------------------------------------------------------------
// Function to Calculate time in Micro Second to Determine Script Execution Time
function run_time()
{
$mtime = microtime(); 
$mtime = explode(' ', $mtime); 
$mtime = $mtime[1] + $mtime[0]; 
return $mtime; 
}
//------------------------------------------------------------------------

//Function to connect the database and this returns the connection handler
function connect_db()
{   //connecting the database
$dbh=mysql_connect("localhost","digitals_iAdmin","kebk8tfv23955");
//$dbh=mysql_connect("localhost","root","abcd");
	if(!$dbh)
    die("Unable to connect Database. Kindly contact Support");
    else {
    mysql_select_db("digitals_digitals");
    return $dbh; }
}//End of connect database Function
//----------------------------------------------------------------------------------------------

//Function to check whether the entered field is empty or not
function check_empty($field_name,$name)
{
if (isset($field_name))
{
if (trim($field_name)!="")
return true;
}
if ($name!="")
$_SESSION['msg']=$_SESSION['msg']."Please Enter ".$name."<br>";
return false;
}//End of Check Empty Function
//----------------------------------------------------------------------------------------------

//Function to check whether the enetered email is a valid email or not
function check_email($email_id)
{
if (strcmp($email_id,"admin")==0)
return true;
$result_email=ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$",$email_id);
if (!$result_email)
{
$_SESSION['msg']=$_SESSION['msg'].$email_id." is an Invalid Email Address, Please enter a Vaild Email Address <br>";
return false;
}
else
return true;
}//End of Check email function
//-----------------------------------------------------------------------------------------------

//Function to check whether the session is valid and a user is logged in
function check_user()
{
// If session user exists then continue otherwise redirect back to login page
if (!isset($_SESSION['id']))
{
if(isset($_SESSION['uty'])&&$_SESSION['uty']==2)
return false;
else if(isset($_SESSION['uty'])&&$_SESSION['uty']==1)
return false;
return false;
//header("location: login.php");
}
else
return true;
}//End of Check User Function
//--------------------------------------------------------------------------------------------------

//Function to check whether Reseller is logged in
function check_reseller()
{
// If session user exists then continue otherwise redirect back to login page
if ((!isset($_SESSION['uty']))||($_SESSION['uty']!=2)) //uty is user type
{
return false;
//header("location: login.php");
}
else if(isset($_SESSION['id'])&&$_SESSION['id']==1)
//$_SESSION['msg']=$_SESSION['msg']."<BR> You need to Login to access your dist account";
return false;
else
return true;
}//End of Check Distributor Function
//--------------------------------------------------------------------------------------------------

//Function to check whether admin is logged in
function check_admin()
{
// If session user exists then continue otherwise redirect back to login page
if ((!isset($_SESSION['id']))||($_SESSION['id']!=1))
{
//$_SESSION['msg']=$_SESSION['msg']."<BR> You need to Login to access your account";
return false;
//header("Location: login.php");
}
else
return true;
}//End of Check Admin Function
//--------------------------------------------------------------------------------------------------

function expire()
{
$_SESSION['msg']=$_SESSION['msg']."<BR>You need to login to access your account";
header("Location: login.php");
exit();
}
//Function to load the users
function load_users($req)
{
if($req==1)
$sql="Select * from D_REG where REG_UTYPE!=2 and REG_UTYPE!=1 order by REG_FNAME asc";
else if($req==2)
$sql="select * from D_REG where REG_REGID='".$_SESSION['id']."'";
$dbh=connect_db();
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
die ("Fatal Error in Loading User List");
return $result;
}//End of Load User Function
//--------------------------------------------------------------------------------------------------

//Function to load all members
function load_members($req)
{
$dbh=connect_db();
if($req==1)
$sel_qry="select REG_PID, REG_FNAME, REG_LNAME, REG_EMAIL, REG_UTYPE from D_REG where REG_UTYPE!=1 and REG_REGID=1";
else if($req==2)
$sel_qry="select REG_PID, REG_FNAME, REG_LNAME, REG_EMAIL, REG_UTYPE from D_REG where REG_UTYPE!=1 and REG_REGID='".$_SESSION['id']."'";
else if($req==3)
$sel_qry="select * from D_REG where REG_UTYPE=2";
$result=mysql_query($sel_qry,$dbh);
mysql_close($dbh);
return $result;
}//End of Load Members Function
//------------------------------------------------------------------------------------------------
function load_dist_user($id)
{
if(isset($id)&&$id!=0)
{
$dbh=connect_db();
$sql="select * from D_REG where REG_REGID='".$id."'";
$result=mysql_query($sql,$dbh);
if((!$result)||(mysql_num_rows($result)<=0))
die("Invalid Distributor to view Users");

return $result;
}
else
die("Invalid Distributor to view Users");
}

function load_nonactivated_users($type)
{
$dbh=connect_db();
if($type==1)//Load all users as user is admin
$sql="select * from D_REG where REG_STATUS='0'";
else if($type==2)
$sql="select * from D_REG where REG_STATUS='0' and REG_REGID='".$_SESSION['id']."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
die("Fatal Error in loading User Data. Kindly contact Support");
return $result;
}
//Function to load members
function load_member_data($req)
{
$dbh=connect_db();
if($req==1)
$sel_qry="select * from D_REG where REG_PID='".$_REQUEST['id']."' and REG_REGID=1";
else if($req==2)
$sel_qry="select * from D_REG where REG_PID='".$_REQUEST['id']."' and REG_REGID='".$_SESSION['id']."'";
else if($req==3)
$sel_qry="select * from D_REG where REG_PID='".$_SESSION['id']."'";
$result=mysql_query($sel_qry,$dbh);
mysql_close($dbh);
if(mysql_num_rows($result)==0)
{
$_SESSION['msg']="Invalid choice of User to be Edited. Please try again";
header("Location: edit_user_profile.php");
exit();
}
return $result;
}//End of Load members data function
//------------------------------------------------------------------------------------------------
//Function to load reports
function load_reports($id)
{
$dbh=connect_db();
$sel_req="select * from D_REQ where REQ_REGID='".$id."' order by REQ_DATE desc";
$result=mysql_query($sel_req,$dbh);
mysql_close($dbh);
if(!$result)
$_SESSION['msg']="You do not have any records";
else if(mysql_num_rows($result)<=0)
$_SESSION['msg']="You do not have any records";
return $result;
}//End of Load Reports Function
//-------------------------------------------------------------------------------------------------

//Function to Load Repot Details
function load_reports_detail()
{
$dbh=connect_db();
$sel_rep="select * from D_SEND where SEND_REQUQID='".$_REQUEST['id']."' and SEND_REGID='".$_SESSION['id']."' order by SEND_DATE desc";
$result=mysql_query($sel_rep,$dbh);
mysql_close($dbh);
if(mysql_num_rows($result)==0)
{
$_SESSION['msg']="Invalid choice of Report to be Viewed. Please try again";
header("Location: reports.php");
exit();
}
return $result;
}//End of Load eports Detail Function
//-------------------------------------------------------------------------------------------------

//Function to upload file
function upload_file($file_name_path,$type)
{
// Where the file is going to be placed 
if ($type==1)
$target_path = "addr/";
if ($type==2)
$target_path = "logos/";
if ($type==3)
$target_path = "pictures/";
if (($file_name_path["type"] == "image/gif")
|| ($file_name_path["type"] == "image/jpeg")
|| ($file_name_path["type"] == "image/pjpeg")
|| ($file_name_path["type"] == "image/jpg"))
{
if($type==2)
{
$target_path = $target_path.basename($_SESSION['id']);
if ($file_name_path["type"] == "image/gif")
$target_path = $target_path.".gif";
if ($file_name_path["type"] == "image/jpeg")
$target_path = $target_path.".jpg";
if ($file_name_path["type"] == "image/pjpeg")
$target_path = $target_path.".jpg";
}
else if ($type==3)
{
$target_path = $target_path.basename($_SESSION['id']);
if ($file_name_path["type"] == "image/gif")
$target_path = $target_path.".gif";
if ($file_name_path["type"] == "image/jpeg")
$target_path = $target_path.".jpg";
if ($file_name_path["type"] == "image/pjpeg")
$target_path = $target_path.".jpg";
}
else
{
$target_path = $target_path.basename($file_name_path['name']);
/*if ($file_name_path["type"] == "image/gif")
$target_path = $target_path.".gif";
if ($file_name_path["type"] == "image/jpeg")
$target_path = $target_path.".jpg";
if ($file_name_path["type"] == "image/pjpeg")
$target_path = $target_path.".jpg";*/
}
if(move_uploaded_file($file_name_path['tmp_name'],$target_path)){
} else{
die ("There was an error uploading the file, please try again!");
}
}
else die ("File Type Not Allowed");
return $target_path;
}//End of Upload File Function
//-----------------------------------------------------------------------------------------

//Function to check the user login is valid or not
function check_login()
{
// If login is sucess redirect via header else Session msg = Login Failed and Redirect back to main page
$validate_form_falg=1;
if (!check_empty($_REQUEST['login'],"Login Name"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['pass'],"Password"))
$validate_form_falg=0;
if ($validate_form_falg==1)
{
$dbh=connect_db();
// * Why are you selecting all fields ?? ; Also Login Id is not going to email .. please correct this
$sql="Select REG_PID,REG_FNAME,REG_LNAME,REG_EMAIL,REG_PWD,REG_STATUS, REG_UTYPE,REG_REGID from D_REG where REG_UNAME='".$_REQUEST['login']."' And REG_PWD='".$_REQUEST['pass']."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
$_SESSION['msg']=$_SESSION['msg']."<BR> Username and Password Do Not Match. Please Try Again";
//$_SESSION['msg']=$_SESSION['msg'].$sql;
header("Location: login.php");
}
else
{
if (mysql_num_rows($result)>0)
{
//check whether its a valid login
if (mysql_result($result,0,'REG_STATUS')==0)
$_SESSION['msg']=$_SESSION['msg']."<BR> This User is not Activated. Please Contact Administrator";
//check whether its a Banned login
if (mysql_result($result,0,'REG_STATUS')==2)
$_SESSION['msg']=$_SESSION['msg']."<BR> This User is Banned. Please Contact Administrator";

// Insert the Login Histroy code here
$dbh=connect_db();
$sql="INSERT INTO D_LOGIN (LOGIN_REGID, LOGIN_DATE, LOGIN_IP) VALUES ('".mysql_result($result,0,'REG_PID')."','".date('Y-m-d H:i:s')."','".getenv("REMOTE_ADDR")."')";
$result2=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result2)
die ("Error in Logging into The System");

if ((mysql_result($result,0,'REG_STATUS')==1))
{
$_SESSION['id']=mysql_result($result,0,'REG_PID');
$_SESSION['name']=mysql_result($result,0,'REG_FNAME')." ".mysql_result($result,0,'REG_LNAME');
$_SESSION['uty']=mysql_result($result,0,'REG_UTYPE');
if ($_SESSION['id']==1)
header("Location: admin.php");
else if($_SESSION['uty']==2)
header("Location: register.php");
else
{
if(!isset($_SESSION['res_id']))
{
$_SESSION['comp_name']="Digital SMS";
$_SESSION['logo']="logos/digital.jpg";
}
else
{
$dbh=connect_db();
$sel_resdata="select * from D_SUB where SUB_REGID='".$_SESSION['res_id']."'";
$sel_res=mysql_query($sel_resdata,$dbh);
mysql_close($dbh);
if(!$sel_resdata)
die("Fatal Error in Login. Kindly contact support");
if(isset($_SESSION['res_id']))
{
//$_SESSION['res_id']=mysql_result($result,0,'REG_REGID');
if(mysql_result($sel_res,0,'SUB_CNAME')!="")
$_SESSION['comp_name']=mysql_result($sel_res,0,'SUB_CNAME');
else
$_SESSION['comp_name']="DigitalSMS";
if(mysql_result($sel_res,0,'SUB_LOGO')!="")
$_SESSION['logo']=mysql_result($sel_res,0,'SUB_LOGO');
else
$_SESSION['logo']="logos/digital.jpg";
}
else
{
$_SESSION['comp_name']="Digital SMS";
$_SESSION['logo']="logos/digital.jpg";
}
}
header("Location: send_sms.php");
}
//else
//header("Location: send.php");
}
else
header("Location: login.php");
}
else
{
$_SESSION['msg']=$_SESSION['msg']."<BR> Username and Password Do Not Match. Please Try Again";
//$_SESSION['msg']=$_SESSION['msg'].$sql;
header("Location: login.php");
}
}
}
else
header("location: login.php");
}//End of Check Login Function
//---------------------------------------------------------------------------------------

function load_subsite_details($id)
{
$dbh=connect_db();
$sel_resdata="select * from D_SUB where SUB_REGID='".$id."'";
$sel_res=mysql_query($sel_resdata,$dbh);
mysql_close($dbh);
/*if(!$sel_res)
{
$_SESSION['comp_name']="Digital SMS";
$_SESSION['logo']="logos/digital.jpg";
return;
}*/
return $sel_res;
}
//Function to add a new entry in the address book of the particuilar user
function add_addr_book()
{
$validate_form_falg=1;
if (!check_empty($_REQUEST['name'],"Name"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['mob_no'],"Mobile Number"))
$validate_form_falg=0;
if(!isset($_REQUEST['group_id'])&&$_REQUEST['group_id']!="")
$validate_form_falg=0;
if(trim($_REQUEST['email'])!="")
{
if (!check_email($_REQUEST['email'],"Email"))
echo "Entered Email is Invalid";
return;
}

if($validate_form_falg==0)
{
echo "Please enter all fields";
return;
/*header("location: add_addr_book.php");
exit();*/
}
$dbh=connect_db();
$sel_mob_no="select * from D_ADDBK where ADDBK_MOBNO='".$_REQUEST['mob_no']."' and ADDBK_REGID='".$_SESSION['id']."'";
$res=mysql_query($sel_mob_no,$dbh);
mysql_close($dbh);
if(mysql_num_rows($res)>0)
{
echo "Mobile Number you entered already exists in address Book";
return;
}


$dbh=connect_db();
$ins_qry="insert into D_ADDBK (ADDBK_REGID,ADDBK_NAME,ADDBK_MOBNO,ADDBK_GRPID, ADDBK_CITY, ADDBK_OCCUP, ADDBK_EMAIL) VALUES ('".$_SESSION['id']."','".$_REQUEST['name']."','".$_REQUEST['mob_no']."','".$_REQUEST['group_id']."','".$_REQUEST['city']."','".$_REQUEST['occupation']."','".$_REQUEST['email']."')";
$result=mysql_query($ins_qry,$dbh);
mysql_close($dbh);
if(!$result)
{
echo "Enty in Address Book not successful. Kindly try again.";
//header("Location: add_addr_book.php");
}
else
{
echo "Entry in Address Book successful.";
//header("Location: add_addr_book.php");
}
}//End of Add Addressbook function
//-----------------------------------------------------------------------------------------

//Function to edit an existing Address book entry fo the user
function edit_addr_book()
{
$validate_form_falg=1;
if (!check_empty($_REQUEST['namez'],"Name"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['mob_no'],"Mobile Number"))
$validate_form_falg=0;
if(trim($_REQUEST['email'])!="")
{
if (!check_email($_REQUEST['email'],"Email"))
$validate_form_falg=0;
}
if(!isset($_REQUEST['grp_id'])&&$_REQUEST['grp_id']!="")
$validate_form_falg=0;


if($validate_form_falg==0)
{
header("location: add_addr_book.php");
exit();
}

$dbh=connect_db();
$upt_qry="update D_ADDBK set ADDBK_NAME='".$_REQUEST['namez']."',ADDBK_MOBNO='".$_REQUEST['mob_no']."', ADDBK_CITY='".$_REQUEST['city']."',ADDBK_OCCUP='".$_REQUEST['occupation']."',ADDBK_EMAIL='".$_REQUEST['email']."',ADDBK_GRPID='".$_REQUEST['grp_id']."' where ADDBK_PID='".$_REQUEST['id']."' and ADDBK_REGID='".$_SESSION['id']."'";
$result=mysql_query($upt_qry,$dbh);
mysql_close($dbh);
if(!$result)
{
$_SESSION['msg']="Address book entry not modified. You selected an Invalid User to Edit";
header("Location: add_addr_book.php");
}
else
{
$_SESSION['msg']="Address book entry modified successfully";
header("Location: add_addr_book.php");
}
}//End of Edit Address book entry function
//-----------------------------------------------------------------------------

//Function to delete existing entries of Address Book
function del_addr_book()
{
if (isset($_REQUEST['addbk_id']))
{
$dbh=connect_db();
$sql="Delete from D_ADDBK where ADDBK_PID='".$_REQUEST['addbk_id']."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
echo ("Error in Deleting Record");
}
else
echo ("Record Sucessfully Deleted");
}
else echo "No Record Selected";

}//End of Delete Address Book entry function


// This function returns the data of address book 
function load_addressbook_data($id,$grp_id,$search_string)
{
$dbh=connect_db();
// Nothing is Set
if (((!isset($grp_id))||($grp_id==''))&&((!isset($search_string))||($search_string=='')))
$sql="Select * from D_ADDBK where ADDBK_REGID='".$id."'";
// Grp Id is Set but Search is not set
if (((isset($grp_id))&&($grp_id!=''))&&((!isset($search_string))||($search_string=='')))
$sql="Select * from D_ADDBK where ADDBK_REGID='".$id."' and ADDBK_GRPID='".$grp_id."'";
// Search is Set & Grp Id is not set
if (((!isset($grp_id))||($grp_id==''))&&((isset($search_string))&&($search_string!='')))
$sql="Select * from D_ADDBK where ADDBK_NAME like '".$search_string."%' and ADDBK_REGID='".$id."'";

// Both Search and Grp Id is set
//if (isset($search_string)&&($search_string!=''))
//echo $sql;
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
die ("Unable to Fetch Address Book Data");
}
return $result;
}

// This function returns Phone Numbers Based on Groups
function load_addressbook_phonedata($id,$grp_id)
{
$dbh=connect_db();
if ($grp_id==0)
$sql="Select ADDBK_PID,ADDBK_NAME,ADDBK_MOBNO,ADDBK_GRPID from D_ADDBK where ADDBK_REGID='".$id."'";
else
$sql="Select ADDBK_PID,ADDBK_NAME,ADDBK_MOBNO,ADDBK_GRPID from D_ADDBK where ADDBK_REGID='".$id."' And ADDBK_GRPID='".$grp_id."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
die ("Unable to Fetch Address Book Data");
}
return $result;
}

//-------------------------------------------------------------------------------------------

//Function to Save a message and the message can only be of 160 characters
function add_saved_message()
{
//Both the stars have been done
// * Make it complimentry to AjaX interface remove exit and use echo to show eroor message to user
// * Also make the Max Saved Messages to 10 ; that is u can check via maX query
//checks whether the entered sms is more than 160 characters
if(strlen($_REQUEST['message'])>160)
{
echo "Your SMS message cannot be more than 160 chaacters.Kindly try again";
return;
}
$validate_form_falg=1;
if (!check_empty($_REQUEST['message'],"Message"))
$validate_form_falg=0;

if($validate_form_falg==0)
{
echo "Enter all fields";
/*header("location: add_draft.php");
exit();*/
return;
}

$dbh=connect_db();
$chk_drf="select * from D_DRAFT where DRAFT_REGID='".$_SESSION['id']."'";
$chk_drf_res=mysql_query($chk_drf,$dbh);
mysql_close($dbh);
if(mysql_num_rows($chk_drf_res)<10)
{
$dbh=connect_db();
$ins_qry="insert into D_DRAFT (DRAFT_REGID,DRAFT_MSG) VALUES ('".$_SESSION['id']."','".$_REQUEST['message']."')";
$result=mysql_query($ins_qry,$dbh);
mysql_close($dbh);
if(!$result)
echo "Message Could not be Saved";
else
echo "Message save succesfully";
}
else
echo "You have consumed you quota of Saved Messages. Maximum allowed is 10 per account";
}//End of Add Saved message Function
//--------------------------------------------------------------------------------------------

//Function to Load Saved Message Grid Data
function load_saved_messages_grid($id)
{
$dbh=connect_db();
$sql="Select * from D_DRAFT where DRAFT_REGID=".$id;
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
die ("Error #1 in Fetching Saved Messages Data");
}
return $result;
}

//Function to delete saved message
function delete_saved_msg()
{
$dbh=connect_db();
$update_qry="DELETE from D_DRAFT where DRAFT_PID='".$_REQUEST['msg_id']."'";
$result=mysql_query($update_qry,$dbh);
mysql_close($dbh);
if(!$result)
echo "Saved message could not be deleted";
else
echo "Saved message deleted successfully";
}//End of Delete Saved Message Function
//-----------------------------------------------------------------------------------------------

//Function to register a user
function register_user()
{
// Check all the form fields if empty
$validate_form_falg=1;
if (!check_empty($_REQUEST['First_Name'],"First Name"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Last_Name'],"Last Name"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['uname'],"User Name"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Password'],"Password"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Password2'],"Repeat Password"))
$validate_form_falg=0;
//Check if Both Password Fields Match
if (strcmp($_REQUEST['Password'],$_REQUEST['Password2'])!=0)
{
$validate_form_falg=0;
$_SESSION['msg']=$_SESSION['msg']." Both Password Fields Do Not Match <br>";
}
if (!check_empty($_REQUEST['Date_of_Birth'],"Date of Birth"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Email'],"Email"))
$validate_form_falg=0;
else  //Check if Email is in Valid Format
if (!check_email($_REQUEST['Email'],"Email"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Country'],"Country"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['State'],"State"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['City'],"City"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Mobile_Number'],"Mobile Number"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Phone_Number'],"Phone Number"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Address'],"Address"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Balance'],"Balance"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['User_Type'],"User Type"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Occupation'],"Occupation"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['sender_id'],"Sender ID"))
$validate_form_falg=0;
//Code to check whether the enetered mobile number is valid or not
if(strlen($_REQUEST['Mobile_Number'])<10)
{
$validate_form_falg=0;
$_SESSION['msg']=$_SESSION['msg']."Mobile Number should have atleast 10 Digits<br>";
}
else if(strcmp($_REQUEST['Mobile_Number'][0],"9")!=0)
{
$validate_form_falg=0;
$_SESSION['msg']=$_SESSION['msg']."Mobile Number Entered is Invalid<br>";
}
else if(!((strcmp($_REQUEST['Mobile_Number'],"0")>0)&&(strcmp($_REQUEST['Mobile_Number'],"9999999999")<=0)))
{
$validate_form_falg=0;
$_SESSION['msg']=$_SESSION['msg']."Mobile Number Entered is Invalid<br>";
}
if(strlen($_REQUEST['uname'])<5)
{
$validate_form_falg=0;
$_SESSION['msg']=$_SESSION['msg']."User Name should have atleast 5 Characters<br>";
}
//Check whether the user has selected atleast 1 Address Proof and has selected the corresponding Address Proof File
//The following code should be put out of Comments before the final upload

if(($_REQUEST['Addr_Proof1']=="")&&($_REQUEST['Addr_Proof2']==""))
{
$_SESSION['msg']=$_SESSION['msg']."Please Give atleast 1 Address Proof<br />";
$validate_form_falg=0;
}
if(($_REQUEST['Addr_Proof1']!="")&&($_FILES['Addr_Proof1_File']['name']==""))
{
$_SESSION['msg']=$_SESSION['msg']."Please Give the Address Proof Copy of the Address Proof Entered only<br />";
$validate_form_falg=0;
}
if(($_REQUEST['Addr_Proof2']!="")&&($_FILES['Addr_Proof2_File']['name']==""))
{
$_SESSION['msg']=$_SESSION['msg']."Please Give the Address Proof Copy of the Address Proof Entered only<br />";
$validate_form_falg=0;
}

if($validate_form_falg!=0)
{
//Check which file is selected by the user to be as his/he Address Proof and then handling the file upload accordingly
//The following code should be put out of Comments befoe the final upload

if($_FILES['Addr_Proof1_File']['name']!="")
{
$addr_proof1=$_REQUEST['Addr_Proof1'];
$addr_proof2="";
$file_name1=upload_file($_FILES['Addr_Proof1_File'],1);
$file_name2="";
}
else if($_FILES['Addr_Proof2_File']['name']!="")
{
$addr_proof2=$_REQUEST['Addr_Proof2'];
$addr_proof1="";
$file_name2=upload_file($_FILES['Addr_Proof2_File'],1);
$file_name1="";
}
else if(($_FILES['Addr_Proof1File']['name']!="")&&($_FILES['Addr_Proof2_File']['name']!=""))
{
$addr_proof1=$_REQUEST['Addr_Proof1'];
$addr_proof2=$_REQUEST['Addr_Proof2'];
$file_name1=upload_file($_FILES['Addr_Proof1_File'],1);
$file_name2=upload_file($_FILES['Addr_Proof2_File'],1);
}
//Connect the database
$dbh=connect_db();
// Check if the primary field already exists & check email syntax
$sql="Select REG_UNAME from D_REG where REG_UNAME='".$_REQUEST['uname']."'";
$result=mysql_query($sql,$dbh);
if (!$result)
{
die ("Fatal Error in Registering User");
}
else if (mysql_num_rows($result)>0)
{
$_SESSION['msg']=$_SESSION['msg'].$_REQUEST['uname']." is already Registered, Please enter a Different Email Address <br>";
if(check_admin())
header("location: register_admin.php");
else if(check_reseller())
header("location: register.php");
exit();
}
else
{
$chk_qry="select REG_MOBNO from D_REG where REG_MOBNO='".$_REQUEST['Mobile_Number']."' and REG_UTYPE='".$_REQUEST['User_Type']."'";
$chk_res=mysql_query($chk_qry,$dbh);
if(mysql_num_rows($chk_res)>0)
{
$_SESSION['msg']="Mobile Number you entered is already registered. Kindly enter a different Mobile Number for Registration";
if(check_admin())
header("location: register_admin.php");
else if(check_reseller())
header("location: register.php");
exit();
}
$sel_bal="select REG_BAL from D_REG where REG_PID='".$_SESSION['id']."'";
$sel_bal_res=mysql_query($sel_bal,$dbh);
$par_bal=mysql_result($sel_bal_res,0,'REG_BAL');
if($par_bal<$_REQUEST['Balance'])
{
$_SESSION['msg']=$_SESSION['msg']."<BR>You do not have enough balance in your account. User Registration Failed";
if(check_admin())
header("location: register_admin.php");
else if(check_reseller())
header("location: register.php");
exit();
}
// Insert into database
$sql="Insert into D_REG(REG_FNAME,REG_LNAME,REG_ADDR,REG_CONNO,REG_MOBNO,REG_EMAIL,REG_PWD,REG_UTYPE,REG_BAL,REG_OCCUP,REG_DOB,REG_CITY,REG_STATE,REG_CONT,REG_ADRPRFO,REG_ADRPRFT,REG_FILEO,REG_FILET, REG_DATE, REG_REGID, REG_STATUS, REG_UNAME, REG_GSMID, REG_CDMAID) Values ('".$_REQUEST['First_Name']."','".$_REQUEST['Last_Name']."','".$_REQUEST['Address']."','".$_REQUEST['Phone_Number']."','".$_REQUEST['Mobile_Number']."','".$_REQUEST['Email']."','".$_REQUEST['Password']."','".$_REQUEST['User_Type']."','".$_REQUEST['Balance']."','".$_REQUEST['Occupation']."','".$_REQUEST['Date_of_Birth']."','".$_REQUEST['City']."','".$_REQUEST['State']."','".$_REQUEST['Country']."','".$addr_proof1."','".$addr_proof2."','".$file_name1."','".$file_name2."','".date("y-m-d H:i:s")."','".$_SESSION['id']."','0','".$_REQUEST['uname']."','".$_REQUEST['sender_id']."','".$_REQUEST['Mobile_Number']."')";
$result=mysql_query($sql,$dbh);
$final_par_bal=$par_bal-$_REQUEST['Balance'];
//Update Parent Account Balance
$upt_qry="update D_REG set REG_BAL='".$final_par_bal."' where REG_PID='".$_SESSION['id']."'";
$upt_res=mysql_query($upt_qry,$dbh);
mysql_close($dbh);
if(!$upt_res)
die("Fatal Error in updating User Balance");
if (!$result)
{
die ("<BR> Fatal Error While Registering User ! Please Contact Support");
}
else
{
//insert the transaction in the transaction table
$dbh=connect_db();
$sel_last_usr="select REG_PID from D_REG where REG_REGID='".$_SESSION['id']."' order by REG_PID desc";
$res_sel=mysql_query($sel_last_usr,$dbh);
$lst_reg_id=mysql_result($res_sel,0,'REG_PID');
$ins_trans="insert into D_TRANS (TRANS_FREGID, TRANS_TREGID, TRANS_AMT, TRANS_DATE, TRANS_SMS) values ('".$_SESSION['id']."','".$lst_reg_id."','".$_REQUEST['amount']."','".date("y-m-d H:i:s")."','".$_REQUEST['Balance']."')";
$ins_res=mysql_query($ins_trans,$dbh);
mysql_close($dbh);
//Check whether distributor and if distributor Create a subsite for that distributor
if($_REQUEST['User_Type']==2)
{
$dbh=connect_db();
$ins_subdata="insert into D_SUB (SUB_REGID) values ('".$lst_reg_id."')";
$subdata=mysql_query($ins_subdata,$dbh);
if(!$subdata)
$_SESSION['msg']=$_SESSION['msg']."Subsite could not be created. Kinldy Contact Admministrator<BR>";
$sel_page="select * from D_PAGE";
$page_res=mysql_query($sel_page,$dbh);
if(!page_res)
$_SESSION['msg']=$_SESSION['msg']."Subsite could not be created. Kinldy Contact Admministrator<BR>";
$flag=0;
for($i=0;$i<mysql_num_rows($page_res);$i++)
{
for($j=0;$j<2;$j++)
{
if($j==0) $data='Heading';
if($j==1) $data='Matter';
$ins_sub="insert into D_CLM (CLM_PAGEID, CLM_NAME, CLM_DATA, CLM_REGID) values ('".mysql_result($page_res,$i,'PAGE_PID')."','".($j+1)."','".$data."','".$lst_reg_id."')";
$result=mysql_query($ins_sub,$dbh);
if(!$result)
$flag=1;
}
}
if($flag==1)
$_SESSION['msg']=$_SESSION['msg']."Subsite Creation Failed. Kinldy contact Admin<BR>";
mysql_close($dbh);
}
if(!$ins_res)
die("Fatal Error in registering User. Kindly contact Support");
else
$_SESSION['msg']=$_SESSION['msg']."<BR> User Registered successfully";
}

// Send email to USER
//The following code should be put out of Comments before the final upload

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: Digital SMS <info@digitalsms.in>' . "\r\n";
$to = $_REQUEST['Email'];
$subject = "Digital SMS Registeration Confirmation";
$body = "<html><head><title>Registeration Sucessful</title></head><body><p class='txt'>"."Thank You for Registerating with digitalSMS !"."</p>
</body></html>";
if (mail($to, $subject, $body,$headers)) 
{
if(check_admin())
header("location: register_admin.php");
else if(check_reseller())
header("location: register.php");
 } else 
 {
  die("<p>Email delivery failed...</p>");
 }
if(check_admin())
header("location: register_admin.php");
else if(check_reseller())
header("location: register.php");
}
}
else if ($validate_form_falg==0)
{
if(check_admin())
header("location: register_admin.php");
else if(check_reseller())
header("location: register.php");
}
}//End of register user funtion
//------------------------------------------------------------------------------------------------

//Function to load Address Book of User
function load_addr_book_user()
{
$dbh=connect_db();
$sel_qry="select * from D_ADDBK WHERE ADDBK_PID='".$_REQUEST['id']."' and ADDBK_REGID='".$_SESSION['id']."'";
$result=mysql_query($sel_qry,$dbh);
mysql_close($dbh);
if(mysql_num_rows($result)==0)
{
$_SESSION['msg']="You entered an Invalid User to Edit. Please try again";
header("Location : add_addr_book.php");
exit();
}
return $result;
}//End of Load Address Boo User
//------------------------------------------------------------------------------------------------

//Function to Load Groups Grid Data
function load_groups_grid($id)
{
$dbh=connect_db();
$sql="Select * from D_GRP where GRP_REGID='".$id."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
die ("Error #1 in Fetching Group Data");
}
return $result;
}

//Function to load the group name
function load_group($id)
{
$dbh=connect_db();
$sql="select * from D_GRP where GRP_PID='".$id."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
die ("Error #1 in Fetching Group Data");
}
return $result;
}


//Function to create a user group
//Done the star
// * Make a Check for Already Existing Groups retrun 4 in that case both in create and update group
function create_group()
{
$validate_form_falg=1;
if (!check_empty($_REQUEST['group_name'],"Group Name"))
$validate_form_falg=0;

if($validate_form_falg==0)
{
echo "Enter all fields";
return;
//header("Location: create_group.php");
//exit();
}

//Connect Database
$dbh=connect_db();
$chk_qry="select * from D_GRP where GRP_NAME='".$_REQUEST['group_name']."'";
$chk_res=mysql_query($chk_qry,$dbh);
mysql_close($dbh);
if(mysql_num_rows($chk_res)<=0)
{
$dbh=connect_db();
$ins_qry="Insert into D_GRP (GRP_REGID, GRP_NAME) VALUES ('".$_SESSION['id']."','".$_REQUEST['group_name']."')";
$result=mysql_query($ins_qry,$dbh);
mysql_close($dbh);
if(!$result)
echo "Your group could not be created";
else
echo "Group Created Succesfully";
}
else
echo "Group Name already exists. Kindly enter a diffeent Group Name";

}//End of Create Group Function
//-----------------------------------------------------------------------------------------------

//Function to Edit Group Name
// * recheck the function I have made modifications
function edit_grp_name()
{
$validate_form_falg=1;
if (!check_empty($_REQUEST['group_name'],"Group Name"))

$validate_form_falg=0;

// * what is the use of this if condition down below action will still get executed
if($validate_form_falg==0)
{
echo "Enter all fields";
//header("Location: edit_group.php?id=".$_REQUEST['grp_id']);
//exit();
}

$dbh=connect_db();
$chk_qry="select * from D_GRP where GRP_NAME='".$_REQUEST['group_name']."' and GRP_REGID='".$_SESSION['id']."'";
$chk_res=mysql_query($chk_qry,$dbh);
mysql_close($dbh);
if(mysql_num_rows($chk_res)<=0)
{
$dbh=connect_db();
$upt_qry="update D_GRP set GRP_NAME='".$_REQUEST['group_name']."' where GRP_PID='".$_REQUEST['grp_id']."'";
$result=mysql_query($upt_qry,$dbh);
mysql_close($dbh);
if(!$result)
echo "Group details could not be Edited. Please try Again.";
else
echo "Group Name Edited Succesfully";
}
else
echo "Group Name already exists. Kindly enter a different Group Name";
}//End of Edit Group Function
//-------------------------------------------------------------------------------------------------

//Function to delete the selected groups
function del_group()
{
// * make a query to delete group and if numbers exists in group then show the message that number exists cannot delete group
$dbh=connect_db();
$chk_qry="select * from D_ADDBK where ADDBK_GRPID='".$_REQUEST['group_id']."'";
$chk_res=mysql_query($chk_qry,$dbh);
mysql_close($dbh);
if(mysql_num_rows($chk_res)<=0)
{
$dbh=connect_db();
$del_qry="delete from D_GRP where GRP_PID='".$_REQUEST['group_id']."'";
$result=mysql_query($del_qry,$dbh);
mysql_close($dbh);
if(!$result)
echo "Group not deleted";
else
echo "Goup Deleted";
}
else
echo "Please empty the group in order to delete it";
}//End of Delete Group Function
//-------------------------------------------------------------------------------------------------

//Function to logout of the system
function logout()
{
session_destroy();
// Check if Excel File Exists; If exists delete it
header("Location: login.php");
}//End of logout Function
//-------------------------------------------------------------------------------------------------

//Function to Edit User Detail
function edit_user_detail()
{
$validate_form_falg=1;
if (!check_empty($_REQUEST['First_Name'],"First Name"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Last_Name'],"Last Name"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Date_of_Birth'],"Date of Birth"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Country'],"Country"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['State'],"State"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['City'],"City"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Phone_Number'],"Phone Number"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Address'],"Address"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Balance'],"Balance"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['Occupation'],"Occupation"))
$validate_form_falg=0;

if($validate_form_falg==0)
{
if(check_admin())
header("Location: edit_user_profile.php");
else if(check_reseller())
header("Location: edit_user_profile_admin.php");
exit();
}


$dbh=connect_db();
/*
//Query to select the Balance of the Parent Account who is editing the details
$chk_par_bal="select REG_BAL from D_REG where REG_PID='".$_SESSION['id']."'";
$chk_par=mysql_query($chk_par_bal,$dbh);
//Parent Account Balance
$par_bal=mysql_result($chk_par,0,'REG_BAL');
//Condition to check whether the parent Account has enough balance to give it to the user
if($par_bal<$_REQUEST['Balance'])
{
$_SESSION['msg']="You do not have enough balance in your account";
}*/
if($_SESSION['id']!=$_REQUEST['id'])
{
//Query to select the user balance whose Balance and details has to be edited
$chk_usr_bal="select REG_BAL,REG_REGID from D_REG where REG_PID='".$_REQUEST['id']."'";
$chk_res=mysql_query($chk_usr_bal,$dbh);
//User Balance
$usr_bal=mysql_result($chk_res,0,'REG_BAL');
$par_id=mysql_result($chk_res,0,'REG_REGID');
//Query to select the balance of the parent account
$chk_par_bal="select REG_BAL from D_REG where REG_PID='".$par_id."'";
$chk_par=mysql_query($chk_par_bal,$dbh);
//Parent Account Balance
$par_bal=mysql_result($chk_par,0,'REG_BAL');
$final_balance=$_REQUEST['Balance']-$usr_bal;
//Check whether the parent account has enough balance
if($par_bal<$final_balance)
{
$_SESSION['msg']="You do not have enough balance in you account";
if(check_admin())
header("Location: edit_user_profile.php?id=".$_REQUEST['id']);
else if(check_reseller())
header("Location: edit_user_profile_admin.php?id=".$_REQUEST['id']);
exit();
}
$balance=$_REQUEST['Balance'];
}
else
$balance=$_REQUEST['Balance'];
//Check whether the entered Balance is less then the previous balance.If yes then the reduced SMS ae cedited to the Distributor back
if($usr_bal>$_REQUEST['Balance'])
{
$rem_bal_par=$usr_bal-$_REQUEST['Balance'];
$final_bal=$rem_bal_par+$par_bal;
$upt_par="update D_REG set REG_BAL='".$final_bal."' where REG_PID='".$_SESSION['id']."'";
}
else if($usr_bal<$_REQUEST['Balance'])
{
$bal=$_REQUEST['Balance'];
$final_bal=(($par_bal+$usr_bal)-$bal);
$upt_par="update D_REG set REG_BAL='".$final_bal."' where REG_PID='".$_SESSION['id']."'";
}
//Check before final upload
$upt_res=mysql_query($upt_par,$dbh);
if(!upt_res)
die("Unable to update details. Kindly contact Support");
$upt_qry="update D_REG set REG_FNAME='".$_REQUEST['First_Name']."', REG_LNAME='".$_REQUEST['Last_Name']."', REG_ADDR='".$_REQUEST['Address']."', REG_BAL='".$_REQUEST['Balance']."', REG_CONT='".$_REQUEST['Country']."', REG_STATE='".$_REQUEST['State']."', REG_CITY='".$_REQUEST['City']."', REG_OCCUP='".$_REQUEST['Occupation']."', REG_DOB='".$_REQUEST['Date_of_Birth']."', REG_CONNO='".$_REQUEST['Phone_Number']."', REG_BAL='".$balance."' where REG_PID='".$_REQUEST['id']."'";
$result=mysql_query($upt_qry,$dbh);
mysql_close($dbh);
if(!$result)
{
$_SESSION['msg']="Your details could not be edited";
if(isset($_REQUEST['id'])&&($_REQUEST['id']!=$_SESSION['id']))
{
if(check_admin())
header("Location: edit_user_profile.php?id=".$_REQUEST['id']);
else if(check_reseller())
header("Location: edit_user_profile_admin.php?id=".$_REQUEST['id']);
}
else
{
if(check_admin())
header("Location: edit_user_profile.php?id=".$_REQUEST['id']);
else if(check_reseller())
header("Location: edit_user_profile_admin.php?id=".$_REQUEST['id']);
}
}
else
{
$_SESSION['msg']="Your edited details have been saved";
if(isset($_REQUEST['id'])&&($_REQUEST['id']!=$_SESSION['id']))
{
if(check_admin())
header("Location: edit_user_profile.php?id=".$_REQUEST['id']);
else if(check_reseller())
header("Location: edit_user_profile_admin.php?id=".$_REQUEST['id']);
}
else
{
if(check_admin())
header("Location: edit_user_profile.php?id=".$_REQUEST['id']);
else if(check_reseller())
header("Location: edit_user_profile_admin.php?id=".$_REQUEST['id']);
}
}
}//End of Edit User Detail Function
//-------------------------------------------------------------------------------------------------

//Function to manage users 
function manage_users($enable,$ban,$delete,$mng)
{
$no_set==0;
//Enable
if ((isset($enable))||(count($enable)>0))
{
$no_set=1;
$dbh=connect_db();
for ($i=0;$i<count($enable);$i++)
{
$sql="Update D_REG Set REG_STATUS=1 where REG_PID='".$enable[$i]."'";
$result=mysql_query($sql,$dbh);
if (!$result)
die ("Fatal Error in Validating Users");
}
mysql_close($dbh);
$_SESSION['msg']="Requested Actions have been performed";
}

//ban
if ((isset($ban))||(count($ban)>0))
{
$no_set=1;
$dbh=connect_db();
for ($i=0;$i<count($ban);$i++)
{
$sql="Update D_REG Set REG_STATUS=2 where REG_PID='".$ban[$i]."'";
$result=mysql_query($sql,$dbh);
if (!$result)
die ("Fatal Error in Banning Users");
}
mysql_close($dbh);
$_SESSION['msg']="Requested Actions have been performed";
}

//delete
if ((isset($delete))||(count($delete)>0))
{
$no_set=1;
$dbh=connect_db();
for ($i=0;$i<count($delete);$i++)
{
// Set Status 3 in User
$sql="Update D_REG Set REG_STATUS=3 where REG_PID='".$delete[$i]."'";
$result=mysql_query($sql,$dbh);
if (!$result)
die ("Fatal Error in Deleting Users");
$sql2="delete from D_GRP where GRP_REGID='".$delete[$i]."'";
$res2=mysql_query($sql2,$dbh);
if (!$res2)
die ("Fatal Error in Deleting Users");
$sql3="delete from D_ADDBK where ADDBK_REGID='".$delete[$i]."'";
$res3=mysql_query($sql3,$dbh);
if (!$res3)
die ("Fatal Error in Deleting Users");
$sql4="delete from D_DRAFT where DRAFT_REGID='".$delete[$i]."'";
$res4=mysql_query($sql4,$dbh);
if (!$res4)
die ("Fatal Error in Deleting Users");
}
mysql_close($dbh);
$_SESSION['msg']="Requested Actions have been performed";
}

if ($no_set==0)
$_SESSION['msg']="Please Select Some Action";

if($mng==1)
header ("location: dist_manage.php");

if($mng==2)
if(check_admin())
header ("location: user_manage_admin.php");
else if(check_reseller())
header ("location: user_manage_dist.php");
if($mng==3)
header ("location: usermanage_admin_filter.php?dist_id=".$_REQUEST['dist_id']);


}//End of Manage Users Function
//-----------------------------------------------------------------------------------------------

//Function to edit saved message
function edit_saved_message()
{
if(strlen($_REQUEST['msg_new'])>160)
{
echo "Your SMS message cannot be more than 160 chaacters.Kindly try again";
return;
}
$validate_form_falg=1;
if(!check_empty($_REQUEST['msg_new'],"New Message"))
$validate_form_falg=0;
if($validate_form_falg!=0)
{
$dbh=connect_db();
$upt_qry="update D_DRAFT set DRAFT_MSG='".$_REQUEST['msg_new']."' where DRAFT_PID='".$_REQUEST['msg_id']."'";
$result=mysql_query($upt_qry,$dbh);
mysql_close($dbh);
if(!result)
echo "Saved Message not edited";
else
echo "Saved Message Edited successfully";
}
else
{
echo "Please enter all fields";
}
}//End of Edit Saved Message Function
//-----------------------------------------------------------------------------------------------

//Function to change the password
function change_password()
{
$validate_form_falg=1;
if (!check_empty($_REQUEST['old_pass'],"Old Password"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['new_pass'],"New Password"))
$validate_form_falg=0;
if (!check_empty($_REQUEST['new_pass2'],"Re-enter New Password"))
$validate_form_falg=0;
if (strcmp($_REQUEST['new_pass'],$_REQUEST['new_pass2'])!=0)
{
$validate_form_falg=0;
$_SESSION['msg']=$_SESSION['msg']."<BR> New Passwords Do Not Match, Please Enter Again";
}
//if (validate_form_falg==0)
//header("location: change_password.php");

$dbh=connect_db();
$sql="Select REG_PID,REG_PWD from D_REG where REG_PID='".$_SESSION['id']."' and REG_PWD='".$_REQUEST['old_pass']."'";
$result=mysql_query($sql,$dbh);
if (($result)&&(mysql_num_rows($result)>0))
{
$sql="Update D_REG Set REG_PWD='".$_REQUEST['new_pass']."' where REG_PID='".$_SESSION['id']."'";
$result2=mysql_query($sql,$dbh);
if (!$result2)
$_SESSION['msg']=$_SESSION['msg']."<BR> Fatal Error In Changing Password, Password cannot be Changed";
else
$_SESSION['msg']=$_SESSION['msg']."<BR> Password Changed Sucessfully";
}
else
$_SESSION['msg']=$_SESSION['msg']."<BR> Old Password Do Not Match, Please Enter Again";
if(check_admin())
header("location: change_admin_password.php");
else if(check_reseller())
header("location: change_dist_password.php");
else if(check_user())
header("location: change_password.php");
}//End of Change Password Function
//---------------------------------------------------------------------------------------------

//Function to Load Login History
function load_history($id)
{
$dbh=connect_db();
$sql="select DATE_FORMAT(LOGIN_DATE,'%d-%m-%y') as dat,time_format(LOGIN_DATE,'%H:%i:%S') as tim, date_format(LOGIN_DATE,'%W') as day from D_LOGIN where LOGIN_REGID='".$id."' order by LOGIN_DATE desc";
$result=mysql_query($sql,$dbh);
if(!$result)
die("Fatal Error in showing Login History");
return $result;
}
//Function to change the user passwords
function change_user_password()
{
if (isset($_REQUEST['pass'])&&(isset($_REQUEST['user_id']))&&($_REQUEST['pass']!=''))
{
$dbh=connect_db();
$sql="Update D_REG Set REG_PWD='".$_REQUEST['pass']."' where REG_PID='".$_REQUEST['user_id']."'";
$result2=mysql_query($sql,$dbh);
if (!$result2)
$_SESSION['msg']=$_SESSION['msg']."<BR> Fatal Error In Changing Password, Password cannot be Changed";
else
$_SESSION['msg']=$_SESSION['msg']."<BR> Password Changed Sucessfully";
}
else
$_SESSION['msg']=$_SESSION['msg']."<BR> Please Input all Fields ! Password not Changed";

if(check_admin())
header("location: change_userpass_admin.php");
if(check_reseller())
header("location: change_userpass_dist.php");
}//End of change user Password function
//------------------------------------------------------------------------------------------------

//Function to get user balance // Is used with AjAX code
function get_user_balance()
{
$id=$_REQUEST['id'];
$dbh=connect_db();
$sql="select REG_BAL from D_REG where REG_PID=".$id;
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
echo ("Unable to Fetch User Balance");
else 
echo ("The Current SMS Balance is :".mysql_result($result,0,'REG_BAL'));
}


//Function to update user balance
function update_balance()
{
$validate_form_falg=1;
if(!check_empty($_REQUEST['sms'],"No. of SMS"))
$validate_form_falg=0;
if(!check_empty($_REQUEST['amt'],"Amount Deposited"))
$validate_form_falg=0;

if($validate_form_falg==0)
{
if(check_admin())
header("Location: update_balance_admin.php");
if(check_reseller())
header("Location: update_balance_dist.php");
exit();
}

$dbh=connect_db();
//Select distributor balance
$sel_bal="select REG_BAL from D_REG where REG_PID='".$_SESSION['id']."'";
$sel_res=mysql_query($sel_bal,$dbh);
mysql_close($dbh);
if(!$sel_res)
die("Fatal Error. Kindly contact support");
//Distibutor Balance
$dist_bal=mysql_result($sel_res,0,'REG_BAL');
$dbh=connect_db();
//Select User current balance whose balance is to be update
$sel_usr_bal="select REG_BAL from D_REG where REG_PID='".$_REQUEST['user']."'";
$sel_usr_res=mysql_query($sel_usr_bal,$dbh);
mysql_close($dbh);
if(!sel_usr_res)
die("Fatal Error # 2!Kondly contact Support");
//User Balance
$usr_bal=mysql_result($sel_usr_res,0,'REG_BAL');
//Check whether dist has sufficient balance in his account
if($dist_bal<$_REQUEST['sms'])
{
$_SESSION['msg']="You do not have enough balance in you account. Kindly try again";
if(check_admin())
header("Location: update_balance_admin.php");
if(check_reseller())
header("Location: update_balance_dist.php");
exit();
}
//Total SMS in user account after updation
$total_sms=$usr_bal+$_REQUEST['sms'];
//Total SMS in dist balance after updation
$total_dist_sms=$dist_bal-$_REQUEST['sms'];
$dbh=connect_db();
//Update user balance
$upt_usr_bal="update D_REG set REG_BAL='".$total_sms."' where REG_PID='".$_REQUEST['user']."'";
$upt_usr_res=mysql_query($upt_usr_bal,$dbh);
mysql_close($dbh);
if(!$upt_usr_res)
{
$_SESSION['msg']="User Balance Updation failed. Kindly try again";
if(check_admin())
header("Location: update_balance_admin.php");
if(check_reseller())
header("Location: update_balance_dist.php");
exit();
}
else
{
$dbh=connect_db();
//Update dist balance
$upt_dist_bal="update D_REG set REG_BAL='".$total_dist_sms."' where REG_PID='".$_SESSION['id']."'";
$upt_dist_res=mysql_query($upt_dist_bal,$dbh);
mysql_close($dbh);
if(!$upt_dist_res)
{
$_SESSION['msg']="Your balance could not be updated";
if(check_admin())
header("Location: update_balance_admin.php");
if(check_reseller())
header("Location: update_balance_dist.php");
exit();
}
else
{
$dbh=connect_db();
$ins_qry="insert into D_TRANS (TRANS_FREGID, TRANS_TREGID, TRANS_DATE, TRANS_SMS, TRANS_AMT) values ('".$_SESSION['id']."','".$_REQUEST['user']."','".date("y-m-d H:i:s")."','".$_REQUEST['sms']."','".$_REQUEST['amt']."')";
$ins_res=mysql_query($ins_qry,$dbh);
mysql_close($dbh);
if(!ins_res)
{
$_SESSION['msg']="Transaction could not be recorded";
if(check_admin())
header("Location: update_balance_admin.php");
if(check_reseller())
header("Location: update_balance_dist.php");
exit();
}
$_SESSION['msg']="User balance Updated successfully";
if(check_admin())
header("Location: update_balance_admin.php");
if(check_reseller())
header("Location: update_balance_dist.php");
}
}
}//End of Update Balance Function
//------------------------------------------------------------------------------------------------
// Accounting Related Functions
// This function Returns the Sum of Account
function sum_debtors_creditors($id,$type)
{
//In terms of amount
$dbh=connect_db();
if ($type==0) //debtors
$sql="select Sum(TRANS_SMS),Sum(TRANS_AMT) from D_TRANS where TRANS_TREGID=".$id;
if ($type==1) //creditors
$sql="select Sum(TRANS_SMS),Sum(TRANS_AMT) from D_TRANS where TRANS_FREGID=".$id;
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
die ("Error Loading Creditors Account Details");
}
return $result;
}

// This function Loads the User Balance

function load_user_balance($id)
{
$dbh=connect_db();
$sql="select REG_BAL from D_REG where REG_PID='".$id."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
die ("Error Loading User Balance");
}
return mysql_result($result,0,'REG_BAL');
}

// This function Loads the Username

function load_user_name($id)
{
$dbh=connect_db();
$sql="select REG_FNAME,REG_LNAME from D_REG where REG_PID=".$id;
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if ((!$result)||(mysql_num_rows($result)<=0))
{
die ("Error Loading User Data");
}
return mysql_result($result,0,'REG_FNAME')." ".mysql_result($result,0,'REG_LNAME');
}

function load_user_data($id)
{
$dbh=connect_db();
$sql="select * from D_REG where REG_PID='".$id."' and REG_UTYPE!=1";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
die("Fatal Error in Loading User Data");
else
return $result;
}

function load_gsm_senderid($id)
{
$dbh=connect_db();
$sql="select REG_GSMID from D_REG where REG_PID='".$id."' and REG_UTYPE!=2 and REG_UTYPE!=1";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if((!$result)||(mysql_num_rows($result)<=0))
{
$_SESSION['msg']="Fatal Error in Loading User Sender ID. Kindly contact Support";
header("Location: change_user_senderid.php");
}
else
return mysql_result($result,0,'REG_GSMID');
}


function load_cdma_senderid($id)
{
$dbh=connect_db();
$sql="select REG_CDMAID from D_REG where REG_PID='".$id."' and REG_UTYPE!=2 and REG_UTYPE!=1";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if((!$result)||(mysql_num_rows($result)<=0))
{
$_SESSION['msg']="Fatal Error in Loading User Sender ID. Kindly contact Support";
header("Location: change_user_senderid.php");
}
else
return mysql_result($result,0,'REG_CDMAID');
}

// This function Loads the Ledger Data
function load_ledger($l_id,$o_id)
{
//In terms of amount
$dbh=connect_db();
if ($o_id==0)
$sql="select * from D_TRANS where TRANS_FREGID=".$l_id." or TRANS_TREGID=".$l_id;
else
$sql="select * from D_TRANS where TRANS_FREGID=".$l_id." and TRANS_TREGID=".$o_id." or TRANS_FREGID=".$o_id." and TRANS_TREGID=".$l_id;
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
die ("Error Loading Account Details");
}
return $result;
}

//------------------------------------------------------------------------------------------------
//SMS Functions


function broadcast_sms()
{
//Code to calculate the execution time of the script
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime; 


// Get the Total Number of Mobile Numbers
// Get them into this mobile_nos array
$mobile_nos=array();
$total_mobile_nos=0;
$valid_flag=1;
$send_status=0;

//----------------------------------------------------------
// Check is Message Box is Empty 
if (!check_empty($_REQUEST['message'],""))
{
$_SESSION['msg']="SMS Message Cannot be Empty, Please Type a Message <br>";
$valid_flag=0;
}
// If Message Length is Greater than 160 Or 70 characters
if (isset($_REQUEST['hindi_check'])&&($_REQUEST['hindi_check']==1))
{
if (strlen($_REQUEST['message'])>70)
{
$_SESSION['msg']="Hindi SMS Message Cannot be Greater than 70 Characters <br>";
$valid_flag=0;
}
}
else
if (strlen($_REQUEST['message'])>160)
{
$_SESSION['msg']="SMS Message Cannot be Greater than 160 Characters <br>";
$valid_flag=0;
}
//----------------------------------------------------------
// Get Mobile Numbers from mobile_no text box
if (check_empty($_REQUEST['mobile_no'],""))
{ 
$temp_mobiles_nos=split(",",$_REQUEST['mobile_no']);
for ($count=0;$count<count($temp_mobiles_nos);$count++)
{
$mobile_nos[$total_mobile_nos]=$temp_mobiles_nos[$count];
$total_mobile_nos++;
}
}
//----------------------------------------------------------
// Get Mobile Numbers from Excel File
// Check is there a Excel File For Upload
if ($_FILES['contact_file']['name']!="")
{
// Lets Load the Excel Reader Code here 
if(!require_once ("Excel/reader.php"))
die("Error in Excell Reader");
$data = new Spreadsheet_Excel_Reader(); 
$data->setOutputEncoding('CP1251');
error_reporting(E_ALL ^ E_NOTICE);

if (!$data)
{
$valid_flag=0;
$_SESSION['msg']=$_SESSION['msg']." Unable to Read Excel File <br>";
}
$data->setOutputEncoding('CP1251');
$data->read( $_FILES["contact_file"]["tmp_name"]);

for ($count = 0; $count < $data->sheets[0]['numRows']; $count++) {
$mobile_nos[$total_mobile_nos]=$data->sheets[0]['cells'][$count+1][1]; // reading first col
$total_mobile_nos++; }
unlink ($_FILES["contact_file"]["tmp_name"]);
}
//----------------------------------------------------------
// Get Mobile Numbers from Group
if (check_empty($_REQUEST['contact'],""))
{
//This is for building the query
for ($count=0;$count<count($_REQUEST['contact']);$count++)
{
// Build the Select Query Here // This is query Builder :)
if ($count==0)
$sql="select ADDBK_MOBNO from D_ADDBK where ADDBK_PID=".$_REQUEST['contact'][$count];
else
$sql.=" or ADDBK_PID=".$_REQUEST['contact'][$count];
}
// Run the Query
$dbh=connect_db();
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result)
{
$_SESSION['msg']=$_SESSION['msg']."Fatal Error ! Unable to Fetch Address Book Data <BR>";
$valid_flag=0;
}
// Now Load the Data from Query
for ($count=0;$count<mysql_num_rows($result);$count++)
{
$mobile_nos[$total_mobile_nos]=mysql_result($result,$count,'ADDBK_MOBNO');
$total_mobile_nos++;
}
}
//----------------------------------------------------------
// Check if less than 10 Lacs or Max Limit of Mobile Numbers ;This check will be implemented later

// Remove Duplicate Entries from Mobile Number
$mobile_nos=array_unique($mobile_nos);
$total_mobile_nos=count($mobile_nos);
//Get Registered GSM and CDMA Sender Id & Balance
$start_time=run_time();
$dbh=connect_db();
$sql="Select REG_GSMID, REG_CDMAID, REG_BAL from D_REG where REG_PID='".$_SESSION['id']."'";
$result=mysql_query($sql,$dbh);
$counter=0;
$mobiles_string="";
mysql_close($dbh);
if (!$result)
{
$_SESSION['msg']=$_SESSION['msg']."Fatal Error ! Unable to Fetch User Data <BR>";
$valid_flag=0;
}
$acc_balance=(int)mysql_result($result,0,'REG_BAL');
$gsm_id=mysql_result($result,0,'REG_GSMID');
$cdma_id=mysql_result($result,0,'REG_CDMAID');
// Check Balance
if ($acc_balance<$total_mobile_nos)
{
$_SESSION['msg']=$_SESSION['msg']."Not Enough Balance to Send SMS <BR>";
$valid_flag=0;
}

// Check if Mobile Numbers is Empty 
if ($total_mobile_nos==0)
{
$_SESSION['msg']=$_SESSION['msg']."Please Enter at Least 1 Mobile Number to Send SMS <BR>";
$valid_flag=0;
}

// Break the SMS Requests in Packets of 5000/ Request // To Be Implemented Later
// Send SMS  ;; Will also generate Insert Query ALongway for Database Updation
if ($valid_flag==1)
{
$sent_sms=0;
// Generate a Unique Id
$unique_id=generate_unique_id();
// Insert into Database Request Details
$dbh=connect_db();
$sql="Insert into D_REQ(REQ_UQID,REQ_REGID,REQ_NOFSMS,REQ_NOSENT,REQ_DATE) Values ('".$unique_id."','".$_SESSION['id']."','".$total_mobile_nos."','0',NOW())";
$result2=mysql_query($sql,$dbh);
mysql_close($dbh);
if (!$result2)
{
$_SESSION['msg']=$_SESSION['msg']." Fatal Error in Generating Request Id <br>";
}
else
{
// Make the Basics For Multiple Insert Query
$ins_sql="Insert into D_SEND (SEND_REGID,SEND_MSG,SEND_MOB,SEND_GSMID,SEND_CDMAID,SEND_DATE,SEND_STATUS,SEND_REQUQID) values ";
// For Loop TO Send SMS
for ($i=0;$i<$total_mobile_nos;$i++)
{
if (isset($_REQUEST['hindi_check'])&&($_REQUEST['hindi_check']==1))
{
$coding="2";
}
else
$coding="1";
// Check Mobile number Type CDMA or GSM
//--------------------------------------------------
/* Not Required
$me_too=substr($mobile_nos[$i],0,2);
if (($me_too=="94")||($me_too=="96")||($me_too=="97")||($me_too=="98")||($me_too=="99"))
$gcid=$gsm_id;
else
$gcid=$cdma_id; */
//--------------------------------------------------
// Check if Mobile Number is of Valid Type and Length
$mobile_nos[$i]=trim($mobile_nos[$i]);
if ((substr($mobile_nos[$i],0,1)=="9")&&(strlen($mobile_nos[$i])==10))  // if 1
{
$counter++;
$sent_sms++;
$send_status=1;
$mobiles_string=$mobiles_string.",".$mobile_nos[$i];
if ($counter==150) // Send SMS Now
{
//Send SMS Now
$request_check=send_multiple_sms($mobiles_string,$_REQUEST['message'],$gsm_id,$cdma_id,$coding);
$counter=0;
$mobiles_string="";
if ($request_check==0)
{
$_SESSION['msg']=$_SESSION['msg']."SMS Sending Request Failed";
$valid_flag=0;
$send_status=0;
}
}
}
else $send_status=0;  // else 1
// Insert Query
$ins_sql=$ins_sql."('".$_SESSION['id']."','".$_REQUEST['message']."','".$mobile_nos[$i]."','".$gsm_id."','".$cdma_id."',NOW(),'".$send_status."','".$unique_id."')";
// Add ","
if ($i<($total_mobile_nos-1))
$ins_sql=$ins_sql.",";
} // End For 
// Check if any Message are Still Left
if (($counter>0))
{
//send message Again
$request_check=send_multiple_sms($mobiles_string,$_REQUEST['message'],$gsm_id,$cdma_id,$coding);
if ($request_check==0)
{
$_SESSION['msg']=$_SESSION['msg']."SMS Sending Request Failed";
$valid_flag=0;
$send_status=0;
}
}
// Update the Sending Table Record
$dbh=connect_db();
$result=mysql_query($ins_sql,$dbh);
if (!$result)
{
echo mysql_error($dbh);
$_SESSION['msg']=$_SESSION['msg']."Fatal Error in Updating Reports Table <BR>";
$valid_flag=0;
}
if ($request_check==0)
{
$sql="Update D_SEND set SEND_STATUS=0 WHERE SEND_REQUQID='".$unique_id."'";
$result=mysql_query($sql,$dbh);
if (!$result)
{
$valid_flag=0;
$_SESSION['msg']=$_SESSION['msg']." Fatal Error ! Status Update Failed II <br>";
}
}
mysql_close($dbh);

if ($request_check!=0)
{ // Start If
// Deduct the Balance ; Update in Reports and Show Message to User
$user_balance=$acc_balance-$sent_sms;
// Run the Update Query
$dbh=connect_db();
$sql="Update D_REG set REG_BAL=".$user_balance." where REG_PID='".$_SESSION['id']."'";
$result=mysql_query($sql,$dbh);
if (!$result)
{
$_SESSION['msg']=$_SESSION['msg']."Fatal Error in Updating User Balance <BR>";
$valid_flag=0;
}
// Update the Request Table with Total Number of Sent SMS
$sql="Update D_REQ set REQ_NOSENT='".$sent_sms."' where REQ_UQID='".$unique_id."'";
$result=mysql_query($sql,$dbh);
// Close the Connection
mysql_close($dbh);
if (!$result)
{
$_SESSION['msg']=$_SESSION['msg']."Fatal Error in Updating Total SMS SENT <BR>";
$valid_flag=0;
}
}
//$execution_time=(run_time()-$start_time);

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);

if ($valid_flag==1)
$_SESSION['msg']=$_SESSION['msg']." ".$sent_sms." SMS Out Of ".$total_mobile_nos." Unique SMS Sent Sucessfully in ".$totaltime." Seconds";
}
//End Function
}// End IF
header("location: send_sms.php");
}


function get_address($to)
{
$me_too=substr($to,0,2);
if (($me_too=="94")||($me_too=="96")||($me_too=="97")||($me_too=="98")||($me_too=="99"))
$address_info = sprintf('<ADDRESS FROM="%s" TO="%s" SEQ="%s" />',"test",$to,1);
return $address_info;
}
 

function postdata($url,$data)
{
//The function uses CURL for posting data to server
        $objURL = curl_init($url);
        curl_setopt($objURL, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($objURL,CURLOPT_POST,1);
        curl_setopt($objURL, CURLOPT_POSTFIELDS,$data);
        $retval = trim(curl_exec($objURL));
        curl_close($objURL);
        return $retval;
}


function send_multiple_sms($mobile_numbers,$message,$gsm_id,$cdma_id,$coding)
{
$xmlstr ='<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" >
<MESSAGE VER="1.2">
<USER USERNAME="digitalsms" PASSWORD="digapi0609"/>
<SMS  UDH="0" CODING="%s" TEXT="%s" PROPERTY="0" ID="2">
 %s
</SMS>
</MESSAGE>';
$url = 'http://api.myvaluefirst.com/psms/servlet/psms.Eservice2';
$uid = 'digitalsms';	//Your Username provided by ValueFirst
$pwd = 'digapi0609';	
if($message!='' )
{
$message = stripslashes($message);
$message = htmlentities($message,ENT_COMPAT);
//Convert to Unicode Hex
if ($coding==2)
{
$message2=str_replace("#","",$message);
$message2=str_replace("x","",$message2);
$message3=split(";",$message2);
for ($h_count=0;$h_count<count($message3)-1;$h_count++)
{
//Count Spaces
$spaces=count(explode(" ",$message3[$h_count]));
$hexa=dechex(trim($message3[$h_count]));
if ($spaces>1)
{
for ($space_counter=1;$space_counter<$spaces;$space_counter++)
$hexa2="0020".$hexa;
}
else
$hexa2=$hexa;
$message4=$message4.$hexa2;
}
$message=$message4;
}
$mobile_numbers=split(",",$mobile_numbers);
for ($icount=0;$icount<count($mobile_numbers);$icount++)
{
$me_too=substr($mobile_numbers[$icount],0,2);
if (($me_too=="91")||($me_too=="94")||($me_too=="96")||($me_too=="97")||($me_too=="98")||($me_too=="99"))
$address_info.= sprintf('<ADDRESS FROM="%s" TO="%s" SEQ="%s" />',$gsm_id,$mobile_numbers[$icount],$icount);
else
$address_info.= sprintf('<ADDRESS FROM="%s" TO="%s" SEQ="%s" />',$cdma_id,$mobile_numbers[$icount],$icount);
}
//echo htmlentities($address_info,ENT_COMPAT);
$xmldata = sprintf($xmlstr,$coding,$message,$address_info);
$data='data='. urlencode($xmldata);
$action='action=send';
$str_response = postdata($url,$action.'&'.$data);    
if ($str_response=="")
{
$str_response = "REQUEST FAILED \t";
return 0;
}
//echo htmlentities($str_response,ENT_COMPAT);
}
else
{
return 0;
}
return 1;
}



// Function to Send Multiple Sms
function send_multiple_sms2($mobile_numbers,$message,$gsm_id,$cdma_id,$coding)
{
$url = 'http://api.myvaluefirst.com/psms/servlet/psms.Eservice2';
$uid = 'digitalsms';	//Your Username provided by ValueFirst
$pwd = 'digapi0609';	
// Determine the mobile_number is CDMA or GSM
$me_too=substr($mobile_numbers,0,2);
if (($me_too=="94")||($me_too=="96")||($me_too=="97")||($me_too=="98")||($me_too=="99"))
$address_info='<ADDRESS FROM="'.$gsm_id.'" TO="'.$mobile_numbers.'" SEQ="1" />';
else
$address_info='<ADDRESS FROM="'.$cdma_id.'" TO="'.$mobile_numbers.'" SEQ="1" />';
//$xml_data='&lt;?xml version="1.0" encoding="ISO-8859-1"?&gt;';
$xml_data='<?xml version="1.0" encoding="ISO-8859-1"?>';
$xml_data=$xml_data.'<!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd">';
$xml_data=$xml_data.'<'.'MESSAGE VER="1.2"'.'>';
$xml_data=$xml_data.'<USER USERNAME="'.$uid.'" PASSWORD="'.$pwd.'"/>';
$xml_data=$xml_data.'<SMS  UDH="0" CODING="1" TEXT="'.$message.'" PROPERTY="0" ID="1">';
$xml_data=$xml_data.$address_info;
$xml_data=$xml_data.'</SMS></MESSAGE>';

$xml_data='<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" >
<MESSAGE VER="1.2">
<USER USERNAME="digitalsms" PASSWORD="digapi0609"/>
<SMS  UDH="0" CODING="1" TEXT="Test 1" PROPERTY="0" ID="1">
<ADDRESS FROM="Tester" TO="9826317282" SEQ="1" />
</SMS>
</MESSAGE>
';



//echo $url;
$xml_data=urlencode($xml_data);


echo $xml_data;
$handle=fopen($url."?action=send&data=".$xml_data, "rb");
echo "<p>";
echo $url."?action=send&data=".$xml_data;
echo "<p>";
$str_response = '';
while (!feof($handle)) {
//$str_response = do_post_request($url."?data=",$xml_data,"");//postdata($url,$xml_data);    
$str_response .= fread($handle, 4048);
}
fclose($handle);
echo "<p><strong>".htmlentities($str_response,ENT_COMPAT)."</strong>";
//echo htmlentities($xml_data,ENT_COMPAT);
urlencode(postdata($url,'action=send&data='.$xml_data));
return 1;
}



//This functions sends the SMS
function send_sms($mobile_number,$message,$gcid,$coding)
{
$message = stripslashes($message);
$sms_url="http://mobile.konsoleindya.com/pushsms.php?username=demo&password=786092&sender=".$gcid."&cdmasender=".$gcid."&to=".$mobile_number;
// Add Country Code
// For Hindi Unicode
if ($coding=="2"){
//$message2=str_replace("&#","",$message);
//$message2=str_replace(";","",$message);
$sms_url=$sms_url."&Unicode=1";
//for ($h_count=0;$h_count<count($message3)-1;$h_count++)
//{
//$hexa=dechex((int)$message3[$h_count]);
//$hexa2="0".$hexa;
//$message4=$message4.$hexa2;
//}
//$message=($message2);
}
else
$message=urlencode($message);
$sms_url=$sms_url."&message=".$message;
$handle=fopen($sms_url, "rb");
$str_response = '';
while (!feof($handle)) {
  $str_response .= fread($handle, 1024);
}
fclose($handle);

//echo $sms_url;
//echo $str_response;
//$str_response =postdata($serverURL,$action.'&'.$data);    

/*if( $fp=fopen('smsmessagesResponse.txt','a+') ){	
fwrite($fp,$str_response. "\t" . date ("l dS of F Y h:i:s A")."\n" );
fclose($fp);
}*/
if ($str_response=="")
{
//$str_response = "REQUEST FAILED \t";
return false;
}
return true;
}

//------------------------------------------------------------------------------------------------

//------------------Start XML Parser Code-----------------------------------------------------
$parser=xml_parser_create();
function start($parser,$element_name,$element_attrs)
  {
  switch($element_name)
    {
    case "GUID":
    echo "GUID:";
    break; 
    case "ERROR":
    echo "ERROR: ";
    break;  
    }
  }
function stop($parser,$element_name)
  {
  echo "<br />";
  }
function char($parser,$data)
  {
  echo $data;
  }

//------------------End XML Parser Code---------------------------------------------


//Function to update admin balance
function update_admin_balance()
{
$validate_form_falg=1;
if(!check_empty($_REQUEST['balance'],"Balance"))
$validate_form_falg=0;

if($validate_form_falg==0)
{
header("Location: update_admin_balance.php");
exit();
}

$dbh=connect_db();
$sql="update D_REG set REG_BAL='".$_REQUEST['balance']."' where REG_PID='1'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
$_SESSION['msg']="Balance could not be updated";
else
$_SESSION['msg']="Balance Updated";
header("Location: update_admin_balance.php");
}//End of Update Admin Balance Function
//-----------------------------------------------------------------------------------------------

//Function to change Sender ID
function change_senderid()
{
$validate_form_falg=1;
if(!check_empty($_REQUEST['gsm_sender_id'],"GSM Sender ID"))
$validate_form_falg=0;
if(!check_empty($_REQUEST['cdma_sender_id'],"CDMA Sender ID"))
$validate_form_falg=0;

if($validate_form_falg==0)
{
header("Location: change_user_senderid.php");
exit();
}

$dbh=connect_db();
$sql="update D_REG set REG_GSMID='".$_REQUEST['gsm_sender_id']."', REG_CDMAID='".$_REQUEST['cdma_sender_id']."' where REG_PID='".$_REQUEST['id']."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
$_SESSION['msg']="Sender ID's could not be changed";
else
$_SESSION['msg']="Sender ID changed Successgully";
header("Location: change_user_senderid.php");
}//End of Change Sender ID Function
//----------------------------------------------------------------------------------------------

function load_pages()
{
$dbh=connect_db();
$sql="select PAGE_NAME, PAGE_PID from D_PAGE";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
{
$_SESSION['msg']="You do not have any subsite";
header("Location: set_subsite.php");
}
else
return $result;
}

function load_page_name($id)
{
$dbh=connect_db();
$sql="select PAGE_NAME from D_PAGE where PAGE_PID='".$id."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if((!$result)||(mysql_num_rows($result)<=0))
return "Invalid Page Name";
//die("Error in selecting Page Name");

return mysql_result($result,0,'PAGE_NAME');
}

function load_column_name($column_id, $page_id)
{
$dbh=connect_db();
$sql="select CLM_NAME from D_CLM where CLM_PID='".$column_id."' and CLM_PAGEID='".$page_id."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if((!$result)||(mysql_num_rows($result)<=0))
return "Invalid Colum Name";
//die("Error in showing Column Name");

return
mysql_result($result,0,'CLM_NAME');
}

function load_columns($id)
{
$dbh=connect_db();
$sql="select CLM_PID, CLM_NAME from D_CLM where CLM_PAGEID='".$id."' and CLM_REGID='".$_SESSION['id']."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
die("No columns to select");

return $result;
}


function load_data($page_id, $column_id)
{
$dbh=connect_db();
$sql="select CLM_DATA from D_CLM where CLM_PAGEID='".$page_id."' and CLM_PID='".$column_id."' and CLM_REGID='".$_SESSION['id']."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if((!$result)||(mysql_num_rows($result)<=0))
{
return;
/*$_SESSION['msg']="You entered and invalid column or row to edit";
header("Location: set_subsite.php");
exit();*/
}
return mysql_result($result,0,'CLM_DATA');
}

function load_subsitedata($id)
{
$dbh=connect_db();
$sql="select * from D_SUB where SUB_REGID='".$id."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
die("Error in Fetching Subsite Data");

return $result;

}


function update_data()
{
$dbh=connect_db();
$sql="update D_CLM set CLM_DATA='".$_REQUEST['test1']."' where CLM_PAGEID='".$_REQUEST['page']."' and CLM_PID='".$_REQUEST['column']."' and CLM_REGID='".$_SESSION['id']."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
$_SESSION['msg']="Entry could not be updated. Kindly try agian";
else
$_SESSION['msg']="Entry updated sucessfully";
header("Location: set_subsite.php");
}

function update_subsite()
{
$validate_form_falg=1;
if(!check_empty($_REQUEST['domain_name'],"Domain Name"))
$validate_form_falg=0;
if(!check_empty($_REQUEST['comp_name'],"Company Name"))
$validate_form_falg=0;
if($validate_form_falg==0)
{
header("Location: set_subsite.php");
exit();
}

$dbh=connect_db();
if($_FILES['logofile']['name']!="")
{
$file_name=upload_file($_FILES['logofile'],2);
$sql="update D_SUB set SUB_DNAME='".$_REQUEST['domain_name']."', SUB_CNAME='".$_REQUEST['comp_name']."', SUB_LOGO='".$file_name."' where SUB_REGID='".$_SESSION['id']."'";
}
else
$sql="update D_SUB set SUB_DNAME='".$_REQUEST['domain_name']."', SUB_CNAME='".$_REQUEST['comp_name']."' where SUB_REGID='".$_SESSION['id']."'";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
$_SESSION['msg']="Error in updating Subsite Data";
else
$_SESSION['msg']="Subsite Data Updated successfully";
header("Location: set_subsite.php");
}

function load_aboutus_data($id)
{
$dbh=connect_db();
$sql="select * from D_CLM where CLM_REGID='".$id."' and CLM_PAGEID='1' order by CLM_NAME asc";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
die("Fatal Error in Slecting Distributor Content");
else
return $result;
}

function load_services_data($id)
{
$dbh=connect_db();
$sql="select * from D_CLM where CLM_REGID='".$id."' and CLM_PAGEID='2' order by CLM_NAME asc";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
die("Fatal Error in Slecting Distributor Content");
else
return $result;
}

function load_contactus_data($id)
{
$dbh=connect_db();
$sql="select * from D_CLM where CLM_REGID='".$id."' and CLM_PAGEID='3' order by CLM_NAME asc";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
die("Fatal Error in Slecting Distributor Content");
else
return $result;
}

function load_testimonial_data($id)
{
$dbh=connect_db();
$sql="select * from D_CLM where CLM_REGID='".$id."' and CLM_PAGEID='4' order by CLM_NAME asc";
$result=mysql_query($sql,$dbh);
mysql_close($dbh);
if(!$result)
die("Fatal Error in Slecting Distributor Content");
else
return $result;
}

function distributor_name($id)
{
$dbh=connect_db();
$load_dist="select REG_FNAME, REG_LNAME from D_REG where REG_PID='".$id."'";
$result=mysql_query($load_dist,$dbh);
mysql_close($dbh);
if(!$result)
die("Fatal Error in Loading Deistributor Name");
return mysql_result($result,0,'REG_FNAME')." ".mysql_result($result,0,'REG_LNAME');
}

function get_resid()
{
if(!isset($_SESSION['res_id']))
{
if(!isset($_REQUEST['res_id']))//&&$_REQUEST['res_id']<0)
{
$domain=$_ENV['HTTP_HOST'];
if(substr($domain,0,4)=='www.')
{
$domain=substr($domain,4);
$dbh=connect_db();
$sel_resid="select SUB_REGID from D_SUB where SUB_DNAME='".$domain."'";
$result=mysql_query($sel_resid,$dbh);
mysql_close($dbh);
if((!$result)||(mysql_num_rows($result)<=0))
{
echo $_SESSION['res_id'];
unset($_SESSION['res_id']);
}
else
{
$_SESSION['res_id']=mysql_result($result,0,'SUB_REGID');
}
}
}
else
$_SESSION['res_id']=$_REQUEST['res_id'];
}
/*echo $domain;
echo $_SESSION['res_id'];*/
}

function forgot_password()
{
$validate_form_falg=1;
if(!check_empty($_REQUEST['email'],"Email"))
$validate_form_falg=0;
if(!check_empty($_REQUEST['mob_no'],"Mobile No"))
$validate_form_falg=0;

if($validate_form_falg==0)
{
header("Location: forgot_password.php");
exit();
}

$dbh=connect_db();
$sel_det="select * from D_REG where REG_MOBNO='".$_REQUEST['mob_no']."' and REG_EMAIL='".$_REQUEST['email']."'";
$result=mysql_query($sel_det,$dbh);
mysql_close($dbh);
if((!$result)||(mysql_num_rows($result)<=0))
{
$_SESSION['msg']="The details you entered does not match our records. Kindly try again";
header("Location: forgot_password.php");
exit();
}
$to = mysql_result($result,0,'REG_EMAIL');
$subject = "Your Password";
$message = "Your Password at DigitalSMS is ".mysql_result($result,0,'REG_PWD');
$from = "info@digitalsms.in";
$headers = "From: $from";
if(!mail($to,$subject,$message,$headers))
$_SESSION['msg']="Email Delivery Failed. Login Details could not be emailed";
else
$_SESSION['msg']= 'Your login details have been emailed.'; 
header("Location: forgot_password.php");
}
?>