<?php
/**
 * @Author sudhir pandey <sudhir@hostnsoft.com>
 * @createdDate 12-07-13
 * 
 */
include dirname(dirname(__FILE__)).'/config.php';
include_once("classes/db_class.php");        
class phonebook_class {
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use for add contact no and email id into phonebook 
    function addContact($parm){
     
      
    
    #get all contact detail 
    $dbobj = new db_class();
    
    $collectionName='phonebook';
    
    $condition = array('userid'=>1002);
    $result =$dbobj->mongo_count($collectionName,$condition);
    if ($result <= 0){
        
     $data=array("userid"=>1002,"emp_id"=>"sudhir@hostnsoft.com","name"=>"sudhir pandey");
     $dbobj->mongo_insert($collectionName,$data);  
        
    }
    
    
      
        
    
       #all name array   
       $allname = $parm['name'];
       #all email array
       $allemail = $parm['email'];
       #all contact
       $allcontact = $parm['contact'];
       $collectionName='phonebook';
       
       $msg = $this->checkContactValidation($allemail,$allcontact);
       if($msg != "success"){
          return json_encode(array("status"=>"error","msg"=>$msg));
       }
       
       for($i=0;$i < count($allemail);$i++){
          
          #check for contact no. is already inserted in table
          $condition = array('contact.contactNo'=>$allcontact[$i]);
          $result =$dbobj->mongo_count($collectionName,$condition);
          if ($result <= 0){
          #update contact detail 
          $data = array('$push'=>array("contact"=> array("contact_id"=>new mongoId(),"name"=> htmlentities($allname[$i],ENT_QUOTES,'UTF-8'),"email"=>$allemail[$i],"contactNo"=>$allcontact[$i]))); //    htmlentities($comment,ENT_QUOTES,'UTF-8'))));
          
          $status = $dbobj->mongo_update($collectionName,array('userid'=>1002),$data);
         
          }
       }  
       
       return json_encode(array("status"=>"success","msg"=>"sucessfuly add new contact ! "));
    }
    
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use for update contact no and email id into phonebook 
    function updateContact($parm){
       
        
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
       if($allemail!='' || $allemail!=null){    
       if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $allemail)){       
       return json_encode(array("status"=>"error","msg"=>"email id is not valid !"));
       }   
       }
       
       if (!preg_match("/^[0-9]{8,15}$/", $allcontact)){  
           
       return json_encode(array("status"=>"error","msg"=>"contact no. are not valid!"));
       }
       
       $collectionName='phonebook';
       #delete contact no 
       $condition = array('contact.contact_id'=>new mongoId($contactId));
       $data = array('$pull'=>array('contact'=>array('contact_id'=>new mongoId($contactId))));
       $result = $dbobj->mongo_update($collectionName,$condition,$data);
       
       #insert edit contact no.         
       $data = array('$push'=>array("contact"=> array("contact_id"=>new mongoId(),"name"=> htmlentities($allname,ENT_QUOTES,'UTF-8'),"email"=>$allemail,"contactNo"=>$allcontact))); //    htmlentities($comment,ENT_QUOTES,'UTF-8'))));
       $status = $dbobj->mongo_update($collectionName,array('userid'=>1002),$data);
       return json_encode(array("status"=>"success","msg"=>"contact no. updated !"));   
          
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
    function deleteContact($parm){
             
       $dbobj = new db_class();
       $contactId = $parm['contactId'];
       $collectionName='phonebook';
       #delete contact no 
       $condition = array('contact.contact_id'=>new mongoId($contactId));
       $data = array('$pull'=>array('contact'=>array('contact_id'=>new mongoId($contactId))));
       $result = $dbobj->mongo_update($collectionName,$condition,$data);
       return json_encode(array("status"=>"success","msg"=>"contact no. successfuly deleted!"));
        
    }
    
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 12-07-2013
    #function use for get all contact no and email from phonebook 
    function getAllContact(){
          $collectionName='phonebook';
          $dbobj = new db_class();
          #check for contact no. is already inserted in table
          $condition = array('userid'=>1002);
          $result =$dbobj->mongo_find($collectionName,$condition);
          foreach($result as $res){
              $allcontact = $res['contact'];
           }
          
          return array("allcontact"=>$allcontact);
        
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 15/07/2013
    #function use for show data for edit contact detail 
    function showEditContact($parm){
        
        #collection name 
        $collectionName='phonebook';
        $dbobj = new db_class();
        #check for contact no. is already inserted in table
        $condition = array('contact.contact_id'=>new mongoId($parm['contactId']));
        $result =$dbobj->mongo_find($collectionName,$condition,array('contact.$'=>1));
        #check data is present or not          
          if($result->count()>0){
            $res=$result->getNext();
            }
         $contactData = $res['contact'];
         #loop for contact array (fetch name ,email ,contact no.) 
         foreach($contactData as $con){
          $name = $con['name'];
          $email = $con['email'];
          $contactNo = $con['contactNo'];
           }
        $str='<div id="edit-contact-dialog" title="edit Contact">
                <form id="contact_edit_form">
                <div id="add-cnt-inner">
                
                	<div class="clear">
                    	<div class="col-1-3 add-cnt-form">
                        
                        	<div class="clear row">
                            	<div class="child">
                                	<p class="mrB">Name</p>
                                    <div class="">
                                    <input type="hidden" name="contactId" value="'.$parm['contactId'].'">    
                                    <input type="text" name="name" value="'.$name.'"/>
                                            
                                    </div>
                                </div>
                                
                                <div class="child">
                                	<p class="mrB">Contact</p>
                                    <div class="">
                                        <input type="text" name="contact" value="'.$contactNo.'"/>
                                    </div>
                                </div>
                                <div class="child">
                                	<p class="mrB">Email</p>
                                    <div class="">
                                        <input type="text" name="email" value="'.$email.'"/>
                                    </div>
                                </div>    
                                
                                
                            </div>
                             	<div class="mrT2"> <a class="btn btn-medium btn-primary" onclick="editcontact(this);" contactId="'.$parm['contactId'].'"  href="javascript:void(0);">Edit</a></div>
                                <div class="mrT2"> <a class="btn btn-medium btn-primary" onclick="deletecontact(this);" contactId="'.$parm['contactId'].'"  href="javascript:void(0);">Delete</a></div>
                            
                        
                        </div>
                        
                        
                        
                        
                    </div>
                    
                    
                </div>
                </form>    
            </div>';
        return $str;
    }
    
    #created by sudhir pandey (sudhir@hostnsoft.com)
    #creation date 17/07/2013
    #function use for check email id and contact no is valid or not 
    function checkContactValidation($allemail,$allcontact){
       
       #msg variable use for return message (if return success then email and contact no is valide otherwise msg send )
       $msg = '';
       for($i=0;$i < count($allemail);$i++){
       #check email id valid
       if($allemail[$i]!='' || $allemail[$i]!=null){    
        if (!preg_match("/^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/ix", $allemail[$i])){       
        return $msg = "email id is not valid !";
        }   
       }
      
       #check contact is valid or not 
       if (!preg_match("/^[0-9]{8,15}$/", $allcontact[$i])){       
       return $msg = "contact no. are not valid!";
       }
       
       
       }
       
       return $msg = "success";
       
    }
    
    
    
}
$pbookobj = new phonebook_class();
?>