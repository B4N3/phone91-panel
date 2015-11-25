<?php
/**
 * @Author Rahul <rahul@hostnsoft.com>
 * @createdDate 03-06-13
 * 
 */
include dirname(dirname(__FILE__)).'/config.php';
class subsite_class extends fun
{
	function update_theme($subsite_pid,$subsite_style)
	{
		$sql="UPDATE ms_subsite SET subsite_style='".$subsite_style."' WHERE subsite_pid='".$subsite_pid."'";
		$dbh=$this->connect_db();
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if($result)
			return "Theme Updated";
		else
			return "Error While Updating Theme";
	}
	
	function manage_subsite()
	{
		if(!$this->check_empty($_REQUEST['dname'],"Domain Name"))
		{
			echo 'Please Provide Domain Name';
			exit();
		}//for empty domain name
		$domain=$_REQUEST['dname'];
		if(substr($domain,0,4)=="www.")
			$domain2=substr($domain,4);
		else
			$domain2=$domain;
		$dbh=$this->connect_db();
		$sql="select * from ms_subsite where (subsite_dname='".$domain."' or subsite_dname='".$domain2."') and subsite_userid!='".$_SESSION['id']."'";
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if((!$result)||(mysql_num_rows($result)>0)) //Redirect Back Dont Allow Further Updattion
		{
			return "Domain Already Exists.";
			exit();
		}
		if(isset($_REQUEST['Language']) && strlen($_REQUEST['Language'])>0)
		{
			$language=$_REQUEST['Language'];
		}
		else
		{
			$language='';
		}
		$dbh=$this->connect_db();
		$sql="insert into ms_subsite (subsite_userid, subsite_dname, subsite_cname, subsite_copyright, subsite_language) values ('".$_SESSION['id']."','".$_REQUEST['dname']."','".$_REQUEST['cname']."','".$_REQUEST['copyright']."','".$language."')";
		$result=mysql_query($sql,$dbh);
		if($result)
		{
			$subsite_id=  mysql_insert_id($dbh);
		}
		mysql_close($dbh);		
		if(!$result)
		{
			return "Your Subsite Details Could Not Be Added";
		}
		else
		{
			if($this->check_empty($_FILES['logo']['name'],''))
			{
//				$dbh=$this->connect_db();
//				$sql="select * from ms_subsite where subsite_userid='".$_SESSION['id']."' order by subsite_pid desc limit 1";
//				$result=mysql_query($sql,$dbh);
//				mysql_close($dbh);
//				$row=mysql_fetch_assoc($result);
//				if(!$result)
//				{
//					$msg="Your Subsite Has Been Added. But Logo Not Entered";
//				}
//				else
//				{
					$subsite_id=$row['subsite_pid'];
					$logo=$this->upload_file($_FILES['logo'],panelDbType."_".$subsite_id,1);
					if($ip=='76.74.251.177')
						$logo='TOSMS'.$logo;
					if($logo=='')
					{
						return "Your Subsite Has Been Added. But Logo Not Entered";
					}
					$dbh=$this->connect_db();
					$sql="update ms_subsite set subsite_logo='".$logo."' where subsite_pid='".$subsite_id."'";
					$result=mysql_query($sql,$dbh);
					mysql_close($dbh);
					if(!$result)
					{
						$msg="Your Subsite Has Been Added. But Logo Not Entered";					
					}
					else
					{
						$msg="Subsite Added Sucessfully";
					}
				//}
			}
			else
			{
				$msg="Subsite Added Sucessfully";
			}
		}
		return $msg;
	}
        
        function manage_context(){
            $dbh=$this->connect_db();           
		            
        $sql = "INSERT INTO ms_subsite_details(user_id,user_name,address,email,number,subsite_pid,tnc) VALUES(".$_SESSION['id'].",'".$_REQUEST['name']."','".$_REQUEST['address']."','".$_REQUEST['email']."','".$_REQUEST['number']."',".$_REQUEST['subsite_pid'].",'".$_REQUEST['tnc']."') 
             ON DUPLICATE KEY UPDATE user_name='".$_REQUEST['name']."',address='".$_REQUEST['address']."',email='".$_REQUEST['email']."',number='".$_REQUEST['number']."',tnc='".$_REQUEST['tnc']."'";
                $result=mysql_query($sql,$dbh);
		mysql_close($dbh);		
		if(!$result)
		{
			return "Your Context Details Could Not Be Added";
                }
                else
                {
                     return "Your Context Details has Updated";
                }
            
        }
	
	function get_language($domain_name)
	{
		$dbh=$this->connect_db();
		$sql="select subsite_language from ms_subsite where subsite_dname like '%".$domain_name."%' limit 1";
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if((!$result)||(mysql_num_rows($result)<=0))
		{
			return '';
		}	
		else
		{
			$row=mysql_fetch_row($result);
			return $row[0];
		}
	}
	function get_subsite_detail($field,$domain_name)
	{
		$dbh=$this->connect_db();
		$sql="select $field from ms_subsite where subsite_dname like '%".$domain_name."%' limit 1";
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if((!$result)||(mysql_num_rows($result)<=0))
		{
			return '';
		}	
		else
		{
			$row=mysql_fetch_row($result);
			return $row[0];
		}
	}
	function delete_subsite()
	{
		$dbh=$this->connect_db();
		$sql="select * from ms_subsite where subsite_pid='".$_REQUEST['subsiteid']."' and subsite_userid='".$_SESSION['id']."'";
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if((!$result)||(mysql_num_rows($result)<=0))
		{
			return "The Subsite Does Not Belong To You. Subsite Not Deleted";
		}
		$dbh=$this->connect_db();
		$sql="delete from ms_subsite where subsite_pid='".$_REQUEST['subsiteid']."'";
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if(!$result)
		{
			return  "Subsite Could Not Be Deleted";
		}
		else
		{
			return "Subsite Deleted Sucessfully";
		}
	}
	
	function edit_subsite()
	{
		if(!$this->check_empty($_REQUEST['dname'],"Domain Name"))
		{
			return  'Please Provide Domain Name';
			exit();
		}
		$domain=$_REQUEST['dname'];
		if(substr($domain,0,4)=="www.")
			$domain2=substr($domain,4);
		else
			$domain2=$domain;
		$dbh=$this->connect_db();
		$sql="select * from ms_subsite where (subsite_dname='".$domain."' or subsite_dname='".$domain2."') and subsite_userid!='".$_SESSION['id']."'";
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if((!$result)||(mysql_num_rows($result)>0)) //Redirect Back Dont Allow Further Updattion
		{
			return "Domain Already Exists.";
			exit();
		}
		if(strcmp($_REQUEST['dname_old'],$_REQUEST['dname'])!=0)
		{
			$domain=$_REQUEST['dname'];
			if(substr($domain,0,4)=="www.")
				$domain2=substr($domain,4);
			else
				$domain2=$domain;
			$dbh=$this->connect_db();
			$sql="select * from ms_subsite where (subsite_dname='".$domain."' or subsite_dname='".$domain2."') and subsite_sdname='".$_REQUEST['sdname']."' and subsite_userid='".$_SESSION['id']."'";
			$result=mysql_query($sql,$dbh);
			mysql_close($dbh);
			if((!$result)||(mysql_num_rows($result)>0))
			{
				echo $_SESSION['msg']="The Conbination of This Domain and Subdomain Already Exists In Your Account";
				exit();
			}
		}//End Domain Change
		else
		{
		}//End Else
		if(!$this->check_empty($_FILES['logo']['name'],''))
			$logo=$_REQUEST['logo_old'];
		else
		{
			//$logo=$this->upload_file($_FILES['logo'],$_REQUEST['id'],1);
			$logo=$this->upload_file($_FILES['logo'],panelDbType."_".$_REQUEST['id'],1);
			if($logo=='')
			{
				return "Your Subsite Has Been Added. But Logo Not Entered";
				exit();
			}
		}
		
		if(isset($_REQUEST['Language']) && strlen($_REQUEST['Language'])>0)
		{
			$language=$_REQUEST['Language'];
		}
		else
		{
			$language='';
		}
		
		$dbh=$this->connect_db();
		$signup_enabled=0;
		if(isset($_REQUEST['signup_enabled']))
		{
			$signup_enabled=1;
		}
		$sql="UPDATE ms_subsite SET subsite_dname='".$_REQUEST['dname']."', subsite_cname='".$_REQUEST['cname']."', subsite_logo='".$logo."', subsite_copyright='".$_REQUEST['copyright']."',subsite_language='".$language."',signup_enabled ='".$signup_enabled."' WHERE subsite_pid='".$_REQUEST['id']."'";
		$result=mysql_query($sql,$dbh);
		mysql_close($dbh);
		if(!$result)
			return "Subsite Data Could Not Be Updated";
		else
			return "Subsite Data Updated Sucessfully";
	}
function setResellerSession($domain)
{
        if(!isset($domain))
            $domain=$_SERVER['HTTP_HOST'];
        
	if(substr($domain,0,4)=="www.")
		$domain2=substr($domain,4);
	else
		$domain2=$domain;
               
	$table = '91_subsite';
		$this->db->select('*')->from($table)->where(" subsite_dname='".$domain."' or subsite_dname='".$domain2."' or subsite_dname='http://".$domain."' or subsite_dname='http://".$domain2."' ");
		//echo $this->db->getQuery();
		
		$batch_result = $this->db->execute();
		///var_dump($batch_result);
		// processing the query result
		if ($batch_result->num_rows > 0) {		
			while ($row= $batch_result->fetch_array(MYSQL_ASSOC) ) {
				//var_dump($row);
			if(!isset($row['subsite_userid']))
				$_SESSION['res_id']=2;
			else
			{
				$_SESSION['res_id']=$row['subsite_userid'];
			}
			if(!isset($row['subsite_logo']))
				$_SESSION['logo']='no_logo';
			else
				$_SESSION['logo']=$row['subsite_logo'];

			if(!isset($row['subsite_cname']))
				$_SESSION['cname']=$domain2;
			else
				$_SESSION['cname']=$row['subsite_cname'];
			if(!isset($row['subsite_copyright']))
				$_SESSION['copyright']=$domain2;
			else
				$_SESSION['copyright']=$row['subsite_copyright'];

				 $_SESSION['style']  = 'style1';		 
				if(isset($row["subsite_style"])) {
					$_SESSION['style']  = 'style' . $row["subsite_style"];
				}
		
			}
		}
	else{
		$_SESSION['res_id']=2;
		$_SESSION['logo']='no_logo';
		$_SESSION['cname']=$domain2;
		$_SESSION['copyright']=$domain2;
	}
	

}
function total_subsites($user_id)
{
    $dbh=$this->connect_db();
    $sql="select * from ms_subsite where subsite_userid='".$user_id."'";
    $result=mysql_query($sql,$dbh);
    $Noofrecords=mysql_num_rows($result);
    mysql_close($dbh);
    if(!$result)
    return $Noofrecords;
    else
    return $Noofrecords;
}
function load_subsites($user_id,$start_limit,$limit)
{
    $dbh=$this->connect_db();
    $sql="select * from ms_subsite where subsite_userid='".$user_id."' limit ".$start_limit.",".$limit;;
    $result=mysql_query($sql,$dbh);
    mysql_close($dbh);
    if(!$result)
    return ;
    else
    return $result;
}
function search_subsites($user_id,$search)
{
	$dbh=$this->connect_db();
	$sql="Select * from ms_subsite where subsite_userid='".$user_id."' and subsite_cname like '%".$search."%' ";
	$result=mysql_query($sql,$dbh) or $error= (mysql_error());
	mysql_close($dbh);
	if (!$result)
		die ("Fatal Error in Loading Subsite List # 3");
	return $result;
}
function load_subsite_details($subsite_id,$user_id)
{
   $dbh=$this->connect_db();
    $sql="select * from ms_subsite where subsite_userid='".$user_id."' and subsite_pid='".$subsite_id."'";
    $result=mysql_query($sql,$dbh);
    mysql_close($dbh);
    if(!$result)
    die("Fatal Error In Loading Subsite Data");
    if(mysql_num_rows($result)<=0)
    die("Restricted Area. The Subsite Does Not Belong To You");
    return $result;
}



function load_subsite_context($subsite_id,$user_id)
{
   $dbh=$this->connect_db();
    $sql="select * from ms_subsite_details where user_id='".$user_id."' and subsite_pid='".$subsite_id."'";
    $result=mysql_query($sql,$dbh);
    mysql_close($dbh);
      return $result;
}

    function create_image($image_string, $width, $height, $bg, $tc)
    {
            header("Content-type: image/png");
            $image = imagecreate($width,$height) or die("Cannot Initialize new GD image stream");
            $bg_red=$bg['red'];
            $bg_green=$bg['green'];
            $bg_blue=$bg['blue'];
            $st_red=$tc['red'];
            $st_green=$tc['green'];
            $st_blue=$tc['blue'];
            if(strlen($image_string)>=0&&strlen($image_string)<=6)
            $font_size=imageloadfont('gd_fonts/verdana_28.gdf');
            else if(strlen($image_string)>=7&&strlen($image_string)<=8)
            $font_size=imageloadfont('gd_fonts/verdana_20.gdf');
            else if(strlen($image_string)>=9&&strlen($image_string)<=12)
            $font_size=imageloadfont('gd_fonts/verdana_16.gdf');
            else if(strlen($image_string)>=13&&strlen($image_string)<=15)
            $font_size=imageloadfont('gd_fonts/verdana_14.gdf');
            else if(strlen($image_string)>=16&&strlen($image_string)<=18)
            $font_size=imageloadfont('gd_fonts/verdana_10.gdf');
            else
            $font_size=5;
            $background_color = imagecolorallocate($image, $bg_red, $bg_green, $bg_blue);
            $text_color = imagecolorallocate($image, $st_red, $st_green, $st_blue);
            $text_width = imagefontwidth($font_size)*strlen($image_string);
            $text_height = imagefontheight($font_size);
            $center = ceil($width / 2);
            $x = $center - (ceil($text_width/2));
            $center = ceil($height/2);
            $y=$center - (ceil($text_height/2));
            imagestring($image, $font_size, $x, $y,  $image_string, $text_color);
            imagepng($image);
            imagedestroy($image);
    }
    function get_logo($cname)
    {
            $bg['red']=255;
            $bg['green']=255;
            $bg['blue']=255;
            $bg2['red']=255;
            $bg2['green']=255;
            $bg2['blue']=255;
            $this->create_image(strtoupper($cname),161,40,$bg,$bg2);
    }
    function image_compress($filename,$width,$height)
    {
        //backTrackCommBuss(__FUNCTION__.'.csv');
            $filename_ext=$filename;
            $filename_ext = strtolower($filename_ext) ;
            $exts = explode(".", $filename_ext) ;
            $n = count($exts)-1;
            $exts = $exts[$n];
            if($exts=='jpg')
            header('Content-type: image/jpg');
            if($exts=='png')
            header('Content-type: image/png');
            if($exts=='jpeg')
            header('Content-type: image/jpeg');
            if($exts=='gif')
            header('Content-type: image/gif');
            list($width_orig, $height_orig) = getimagesize($filename);
            $ratio_orig = $width_orig/$height_orig;
            if ($width/$height > $ratio_orig) 
            {
               $width = $height*$ratio_orig;
            } else 
            {
               $height = $width/$ratio_orig;
            }
            // Resample
            $image_p = imagecreatetruecolor($width, $height);
            if($exts=='jpg')
            $image = imagecreatefromjpeg($filename);
            if($exts=='png')
            $image = imagecreatefrompng($filename);
            if($exts=='jpeg')
            $image = imagecreatefromjpeg($filename);
            if($exts=='gif')
            $image = imagecreatefromgif($filename);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            if($exts=='jpg')
            imagejpeg($image_p);
            if($exts=='png')
            imagepng($image_p);
            if($exts=='jpeg')
            imagejpeg($image_p);
            if($exts=='gif')
            imagegif($image_p);
            // Free up memory
            imagedestroy($image_p);
    }
     
    function search_autocomplete_subsites($user_id, $search) {
        $dbh = $this->connect_db();
        $sql = "Select * from ms_subsite where subsite_userid='" . $user_id . "' and (subsite_cname LIKE '%" . $search . "%' or subsite_dname LIKE '%" . $search . "%')";
        $result = mysql_query($sql, $dbh) or $error = (mysql_error());
        mysql_close($dbh);
        if (!$result)
            die("Fatal Error in Loading User List # 3");
        return $result;
    }

}