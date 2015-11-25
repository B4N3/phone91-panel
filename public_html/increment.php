<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


include_once('config.php');
include_once CLASS_DIR.'reseller_class.php';
$resellerObj = new reseller_class();

$a="1110";
function generateId($a,&$wxyz){
    
     $firstTwo=substr($a,0,2);
     $firstThree=substr($a,0,3);
    echo " ";
      $first=substr($a,0,1);
    echo " ";
      $second=substr($a,1,1);
    echo " ";
      $third=substr($a,2,1);
    echo " ";
      $last=substr($a,3,1);
    echo " ";

    
   if($last=="9")
   {
        $last="a";
        $wxyz=$first.$second.$third.$last;
        return $wxyz;
   }
   
   if($last=="z")
   {
        if($third=="9")
        {
            $third="a";
            $last="1";
            $wxyz=$first.$second.$third.$last;
            return $wxyz;
        }
        if($third=="z")
        {
            if($second=="9")
            {
                $second="a";
                $last="1";
                $third="1";
                $wxyz=$first.$second.$third.$last;
                return $wxyz;
            }
            if($second=="z")
            {
               if($first=="9")
                {
                    $first="a";
                    $last="1";
                    $third="1";
                    $second="1";
                    $wxyz=$first.$second.$third.$last;
                    return $wxyz;
                } 
                
                ++$first;
                $second="1";
                $third="1";
                $last="1";
                $wxyz=$first.$second.$third.$last;
                return $wxyz;
            }
            
            ++$second;
            $third="1";
            $last="1";
            $wxyz=$first.$second.$third.$last;
            return $wxyz;
        }
        ++$third;
        $last="1";
        $wxyz=$first.$second.$third.$last;
        return $wxyz;
   }
   
   ++$last;
     $wxyz=$first.$second.$third.$last;
    return $wxyz;
}


//$a='xzz';
for($i=1;$i<4000;$i++)
{
    
        $a=generateId($a);
    $table = '91_userBalance';
    $data = array("chainId" => "1111".$a);
    $condition = " resellerId=2 and slNo=$i";
    $result=$resellerObj->db->update($table, $data)->where($condition);
    $resellerObj->db->getQuery();
    $result = $resellerObj->db->execute();
//    var_dump($result);
    var_dump($resellerObj->db);
    
    
    echo "
        <br />";
//    $a++;
}



?>
