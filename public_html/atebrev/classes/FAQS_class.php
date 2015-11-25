<?php

/* @author: sameer 
 * @created: 16-08-2013
 * @desc : the class consist of all the functions required for call log and recent calls 
 */
include dirname(dirname(__FILE__)) . '/config.php';
class FAQS_class extends fun{
   
    
     /**
     * @author Ankit patidar <ankitpatidar@hostnsoft.com>
     * @since 13/03/2014
     * @filesource
     * @uses to get details from  FAQS table
     * @param array $request: contains searching parameters q,
     * 
     */
    function faqsDetails($request)
    {
        
        //set date in required format
//        $sDate = (isset($request['sDate']) && $request['sDate'] != '')?date('Y-m-d H:i:s',strtotime($request['sDate'])):date('Y-m-d 00:00:00');
//        $eDate = (isset($request['eDate']) && $request['eDate'] != '')?date('Y-m-d H:i:s',strtotime($request['eDate'])):date('Y-m-d 23:59:59');
       
        $qString = (isset($request['q']) && $request['q']) != ''?$this->db->real_escape_string($request['q']):'';
        
        if(isset($request['voiceJsonp']) && $request['voiceJsonp'] != '')
                $callBack = 1;
           else
                $callBack = 0;
        
        if($qString !='')
        {
            $likeQ = "subject LIKE '%$qString%' or question LIKE '%$qString%' and answer LIKE '%$qString%'";
        }
        else
            $likeQ = '1';
        
        $table = '91_FAQS';
        
       
        
        $result = $this->selectData('*',$table,$likeQ);
        
        
        //validate result
        if(!$result)
        {
            $json = json_encode(array('status' => 0,'msg' => 'Problem while getting FAQS details!!!'));
            if(!$callBack)
               return $json;
            else
                return $request['voiceJsonp'].'('.$json.')';
        }
          
        if($result->num_rows == 0)
        {
            $json = json_encode(array('status' => 0,'msg' => 'Record Not found!!!'));
             if(!$callBack)
               return $json;
            else
                return $request['voiceJsonp'].'('.$json.')';
            
            return json_encode(array('status' => 0,'msg' => 'Record Not found!!!'));
        }
        
        $data = array();
        
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $ques['question'] =  utf8_encode ($row['question']);
            $ques['answer'] = utf8_encode ($row['answer']);
            $ques['subject'] = utf8_encode ($row['subject']);
            $data[]= $ques;
            unset($ques);
            unset($row);
        }
        
       
        $json =  json_encode(array('status' => 1,'msg' => 'Record Found!!!','FAQS' => $data));
        
             if(!$callBack)
               return $json;
            else
                return $request['voiceJsonp'].'('.$json.')';
       
    } //end of function callFailedErrorLog()
    
}
?>
