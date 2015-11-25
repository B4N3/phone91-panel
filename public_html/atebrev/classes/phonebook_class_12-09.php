<?php

/**
 * @Author sudhir pandey <sudhir@hostnsoft.com>
 * @createdDate 12-07-13
 * 
 */
include dirname(dirname(__FILE__)) . '/config.php';
include_once(CLASS_DIR."/db_class.php");

class phonebook_class {
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use for add contact no and email id into phonebook 

    function addContact($parm, $userid) {


        #get all contact detail 
        $dbobj = new db_class();

        #all name array   
        $allname = $parm['name'];
        #all email array
        $allemail = $parm['email'];
        #all contact
        $allcontact = $parm['contact'];
        $collectionName = 'phonebook';

        
        $msg = $this->checkContactNull($allcontact);
        if ($msg != "success") {
            return json_encode(array("status" => "error", "msg" => $msg));
        }
        $errorKey = $this->checkContactValidation($allemail, $allcontact);
        for ($i = 0; $i < count($allemail); $i++) {

            if(!in_array($i, $errorKey)){
            #check for contact no. is already inserted in table
            $condition = array('contact.contactNo' => $allcontact[$i],'userId'=>$userid);
            $result = $dbobj->mongo_count($collectionName, $condition);
            if ($result <= 0) {
                #update contact detail 
                $data = array('$push' => array("contact" => array("contact_id" => new mongoId(), "name" => htmlentities($allname[$i], ENT_QUOTES, 'UTF-8'), "email" => $allemail[$i], "contactNo" => $allcontact[$i]))); //    htmlentities($comment,ENT_QUOTES,'UTF-8'))));

                $status = $dbobj->mongo_update($collectionName, array('userId' => $userid), $data);
            }
            }
        }
        if ($status) {
            $str = $this->allContactlist($userid);
            return json_encode(array("status" => "success", "msg" => "sucessfuly add new contact ! ", "str" => $str));
        }
        else
            return json_encode(array("status" => "error", "msg" => "Contact Number already used !"));
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 05-08-2013
    #fuction use for add Me contact no into phonebook table 

    function addMeContact($userid) {

        #db_class obj for mongo connection 
        $dbobj = new db_class();

        #collectionName 
        $collectionName = 'phonebook';

        $condition = array('userId' => $userid);
        $result = $dbobj->mongo_count($collectionName, $condition);
        if ($result <= 0) {
            #get email id form validateEmail table by ues contact_class function 
            include_once("contact_class.php");
            $cont_obj = new contact_class();
            $cont = $cont_obj->getUnConfirmEmail($userid);
            $emailId = $cont['email'];

            #check verified mobile no.
            $confirmNo = $cont_obj->getConfirmMobile($userid);
            
            if($confirmNo[0]['verifiedNumber'] == '' || $confirmNo[0]['verifiedNumber'] == NULL){
            #get contactno form validenumber table 
            $contactno = $cont_obj->getUnconfirmMobile($userid);
            $meContact = $contactno['tempNumber'];
            }else
            $meContact = $confirmNo[0]['verifiedNumber'];    

           
            $data = array("userId" => $userid, "emailId" => $emailId);
            $dbobj->mongo_insert($collectionName, $data);

            #insert first contact no. into phonebook table (name= "me");
            $data = array('$push' => array("contact" => array("contact_id" => new mongoId(), "name" => htmlentities("me", ENT_QUOTES, 'UTF-8'), "email" => $emailId, "contactNo" => $meContact))); //    htmlentities($comment,ENT_QUOTES,'UTF-8'))));
            $status = $dbobj->mongo_update($collectionName, array('userId' => $userid), $data);
        }
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use for update contact no and email id into phonebook 

    function updateContact($parm, $userid) {


        $dbobj = new db_class();
        #contact id
        $contactId = $parm['contactId'];
        #all name array   
        $allname = $parm['name'];
        #all email array
        $allemail = $parm['email'];
        #all contact
        $allcontact = $parm['contact'];

        #check email id valid
        if ($allemail != '' || $allemail != null) {
            if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $allemail)) {
                return json_encode(array("status" => "error", "msg" => "email id is not valid !"));
            }
        }

        if (!preg_match("/^[0-9]{8,15}$/", $allcontact)) {

            return json_encode(array("status" => "error", "msg" => "contact no. are not valid!"));
        }

        $collectionName = 'phonebook';
        #delete contact no 
        $condition = array('contact.contact_id' => new mongoId($contactId));
        $data = array('$pull' => array('contact' => array('contact_id' => new mongoId($contactId))));
        $result = $dbobj->mongo_update($collectionName, $condition, $data);

        #insert edit contact no.         
        $data = array('$push' => array("contact" => array("contact_id" => new mongoId(), "name" => htmlentities($allname, ENT_QUOTES, 'UTF-8'), "email" => $allemail, "contactNo" => $allcontact))); //    htmlentities($comment,ENT_QUOTES,'UTF-8'))));
        $status = $dbobj->mongo_update($collectionName, array('userId' => $userid), $data);
        $str = $this->allContactlist($userid);
        return json_encode(array("status" => "success", "msg" => "contact no. updated !", "str" => $str));

//          #check for contact no. is already inserted in table
//          $condition = array('contact.contact_id'=>new mongoId($contactId));
//          $data=array('contact.email'=>$allemail,"contact.name"=>$allname);
//          $dataArr=array('$set'=>$data);
//          $result = $dbobj->mongo_update($collectionName,$condition,$dataArr);
//          print_r($result);  
//     
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use for delete contact no and email id form phonebook tabel 

    function deleteContact($parm, $userid) {

        $dbobj = new db_class();
        $contactId = $parm['contactId'];
        $collectionName = 'phonebook';
        #delete contact no 
        $condition = array('contact.contact_id' => new mongoId($contactId));
        $data = array('$pull' => array('contact' => array('contact_id' => new mongoId($contactId))));
        $result = $dbobj->mongo_update($collectionName, $condition, $data);
        $str = $this->allContactlist($userid);
        return json_encode(array("status" => "success", "msg" => "contact no. successfuly deleted!", "str" => $str));
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use for get all contact no and email from phonebook 

    function getAllContact($userid) {
        $collectionName = 'phonebook';
        $dbobj = new db_class();
        #check for contact no. is already inserted in table
        $condition = array('userId' => $userid);
        $result = $dbobj->mongo_find($collectionName, $condition);
        foreach ($result as $res) {
            $allcontact = $res['contact'];
        }

        return array("allcontact" => $allcontact);
    }
    

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 15/07/2013
    #function use for show data for edit contact detail 

    function showEditContact($parm) {

        #collection name 
        $collectionName = 'phonebook';
        $dbobj = new db_class();
        #check for contact no. is already inserted in table
        $condition = array('contact.contact_id' => new mongoId($parm['contactId']));
        $result = $dbobj->mongo_find($collectionName, $condition, array('contact.$' => 1));
        #check data is present or not          
        if ($result->count() > 0) {
            $res = $result->getNext();
        }
        $contactData = $res['contact'];
        #loop for contact array (fetch name ,email ,contact no.) 
        foreach ($contactData as $con) {
            $name = $con['name'];
            $email = $con['email'];
            $contactNo = $con['contactNo'];
        }
		/*Modified by Lovey at 4/sep/2013*/
        $str = '<div id="dialog-confirm" title="Confirm" style="display : none;">Are You Sure You Want to Delete This Entry</div><div id="edit-contact-dialog" title="Edit Contact">
                <form id="contact_edit_form">
                  <div id="add-cnt-inner">
                    	<div class="pd2  add-cnt-form editContact">
                            	<div class="child">
                                	<p class="mrB">Name</p>
                                    <div class="">
                                    <input type="hidden" name="contactId" value="' . $parm['contactId'] . '">    
                                    <input type="text" name="name" value="' . $name . '"/>
                                    </div>
                                </div>
                                
                                <div class="child">
                                	<p class="mrB">Contact</p>
                                    <div class="">
                                        <input type="text" name="contact" value="' . $contactNo . '"/>
                                    </div>
                                </div>
								
                                <div class="child">
                                	<p class="mrB">Email</p>
                                    <div class="">
                                        <input type="text" name="email" value="' . $email . '"/>
                                    </div>
                                </div> 
								<div class="actionS"> 
									<a class="btn btn-medium btn-primary" onclick="editcontact(this);" contactId="' . $parm['contactId'] . '"  href="javascript:void(0);" title="Edit">Edit</a>
									<a class="btn btn-medium btn-primary" onclick="confirmDelete(this);" contactId="' . $parm['contactId'] . '"  href="javascript:void(0);" title="Delete">Delete</a></div>
                            </div>
                </div>
                </form>    
            </div>';
        return $str;
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 17/07/2013
    #function use for check email id and contact no is valid or not 

    function checkContactValidation($allemail, $allcontact) {

        #msg variable use for return message (if return success then email and contact no is valide otherwise msg send )
        $msg = '';$errorKey = array();
        for ($i = 0; $i < count($allemail); $i++) {
            #check email id valid
            if ($allemail[$i] != '' || $allemail[$i] != null) {
                if (!preg_match("/^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/ix", $allemail[$i])) {
                   $errorKey[] = $i; 
                }
            }

            #check contact is valid or not 
            if (!preg_match("/^[0-9]{8,15}$/", $allcontact[$i])) {
                $errorKey[] = $i;
            }
            
        }

        return $errorKey;
    }

    #created by sudhir pandey (sudhir@hostnsot.com)
    #creation date 05/09/2013
    function checkContactNull($allcontact){
        for ($i = 0; $i < count($allcontact); $i++) {
            #check contact is valid or not 
            if ($allcontact[$i] == ''|| $allcontact[$i]== NULL) {
                return $msg = "please enter contact number"; 
             }
        }
        return $msg = "success";
    }

    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 18/07/2013
    #function use for searching contact detail. 

    function allContactlist($userid) {
        $str = '';

        extract($this->getAllContact($userid)); //$allcontact

        foreach ($allcontact as $res) {
            $str.='<li class="clear default"  contactId="' . $res['contact_id'] . '">
          <div class="col-1-3">
            <div class="innerCol">
              <h3 class="h3 ellp fwN">' . $res['name'] . '</h3>
              <div class="clear fpinfo"> <i class="ic-16 call"></i>
                <label>' . $res['contactNo'] . '</label>
              </div>
            </div>
          </div>
          <div class="col-3-4">
            <div class="edtsiWrap"> <a class="btn btn-medium btn-primary btn-block clear alC" style="cursor:inherit; background:inherit"  href="javascript:void(0);" > <span class="ic-32 edit"></span> </a> </div>
            <div class="hoveredtsiWrap"> <a class="btn btn-medium btn-primary btn-block clear alC" onclick="showContactEdit(this);" contactId="' . $res['contact_id'] . '" href="javascript:void(0);">
                <span class="ic-32 edit"></span>
              
                </a>
                </div>
          </div>
        </li>';
//            $str.='<li class="clear default"  contactId="' . $res['contact_id'] . ' >
//                	<div class="col-1-3">
//                    	<h3 class="h3 ellp fwN">' . $res['name'] . '</h3>
//                        <div class="clear fpinfo">
//                        	<i class="ic-16 call"></i>
//                            <label>' . $res['contactNo'] . '</label>
//                        </div>
//                    </div>
//                    
//                    <div class="col-3-4">
//                    	<div class="alC edtsiWrap">
//                        	<div class="tryc"><i class="ic-24 edit"></i></div>
//                        </div>
//                        <div class="hoveredtsiWrap">
//                            <a class="btn btn-medium btn-primary btn-block clear alC" onclick="showContactEdit(this);" contactId="' . $res['contact_id'] . '" href="javascript:void(0);">
//                                <div class="clear tryc tr1">
//                                    <span class="ic-24 editW"></span>
//                                </div>
//                            </a>
//                        </div>
//                    </div>
//                    
//                </li>';
        }
        return $str;
    }

}

$pbookobj = new phonebook_class();
?>