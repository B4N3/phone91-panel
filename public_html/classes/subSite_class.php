<?php
include dirname(dirname(__FILE__)).'/config.php';
class subSite_class extends fun{
    
    //get all subsite
    function getSubsite(){
	$this->db->select('*')->from('ms_subsite');
	$data = $this->db->execute();
	if($data->num_rows > 0){
	    while($rows = $data->fetch_array(MYSQL_ASSOC)){
		$company_name = $rows['subsite_cname'];
		$domain = $rows['subsite_dname'];
		$logo = $rows['subsite_logo'];
		$copywrite = $rows['subsite_copyright'];
		$language = $rows['subsite_language'];
		$col_id = $rows['subsite_pid'];
		$user_id = $rows['subsite_userid'];
		$sub_domain = $rows['subsite_sdname'];
		$result[] = array($company_name, $domain, $logo, $copywrite, $language, $col_id, $user_id, $sub_domain);
	    }
	    $site['values'] = $result;
	}else
	    $site[] = '';
	return json_encode($site);
    }
    
    //edit a subsite
    function editSubsite($fields){
	$data =  $this->getData($fields);
	$this->db->update('ms_subsite', $data)->where("subsite_pid=".$fields['subsite_pid']);		
	 return $this->db->execute();
    }
    
    //add a subsite
    function addSubsite($request, $session){
	$data = array('subsite_cname'=>$request['com_name'], 'subsite_dname'=>$request['dom_name'], 'subsite_copyright'=> $request['CopyWrite'], 'subsite_language' => $request['language'], 'subsite_userid'=>$session['userid']);
	$this->db->insert('ms_subsite', $data);
	return $this->db->execute();
    }
    
    //delete a subsite
    function deleteSubsite($id){
	if($this->db->query("DELETE FROM ms_subsite WHERE subsite_pid =".$id))
	    return true;
	else
	    return false;
    }
    
    function getData($request){
	$data = array();
	foreach($request as $k=>$v){
	    $data[$k]=$v;
	}
	return $data;
	
    }
}
?>