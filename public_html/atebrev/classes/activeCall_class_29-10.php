<?php
/* @author : sameer
 * @created : 09-09-2013
 * @desc : 
 */
include dirname(dirname(__FILE__)).'/config.php';
class activeCall_class extends fun
{
    function getNameFromChainID($cahinIdArr)
    {
        $chainId = implode("','",$cahinIdArr);
        $this->db->select(" name,chainId ")->from("91_manageClient")->where("chainId IN ('".$chainId."')");
        $this->db->getQuery();
        $res = $this->db->execute();
        while($row = $res->fetch_array(MYSQLI_ASSOC))
        {
            $userNameArr[$row['chainId']] = $row['name'];
        }
        return $userNameArr;
    }
    function getActiveCalls($resId =NULL)
    {   
        $this->db->select(" * ")->from("91_currentcalls")->where("id_chain LIKE '".$_SESSION['chainId']."%'");
        $res = $this->db->execute();
        if(!$res)
            mail("sameer@hostnsoft.com","".__FILE__,"".__FUNCTION__."   ".$this->db->error);
        $i = 0 ;
        while($row = $res->fetch_array(MYSQLI_ASSOC))
        {
            $resultant[$i]['chainId'] = substr($row['id_chain'], 0, 8);
            $resultant[$i]['number'] = $row['dialed_number'];
            
            $date = strtotime(date("Y-m-d H:i:s"));
            $duration =  date("Y-m-d H:i:s",($date - strtotime($row['call_start'])));
            $resultant[$i]['callDuration'] = $duration;
            $chainIdArray['chainId'][] = substr($row['id_chain'], 0, 8);
            $resultant[$i]['starttime'] = date("Y-m-d H:i:s A",strtotime($row['call_start']));
            $i++;
        }
        $nameArr = $this->getNameFromChainID($chainIdArray['chainId']);
        
        foreach($resultant as $key => $chId)
        {

            $resultant[$key]['name'] = $nameArr[$chId['chainId']];
        }
        return $resultant;
    }
}


?>
