<?php

print_r($me);
die();
//
// include('config.php');
// include('dbconfig.php');
// 
//    #user is by session
//    $userid=$_SESSION['userid'];
//    #function object to sql query function
//    $fun_obj=new fun();   
//    #query to get the chain id of the user
//  
//   #query to ge3t the chain id of the user
//   $sql1 = $fun_obj->db->select('chainId')->from('91_userBalance')->where("userId='".$userid."'");
//   $fun_obj->db->getQuery($sql1);
//   
//    
//   #execute the query 
//   $result1 = $fun_obj->db->execute($sql1);
//   $row1=mysqli_fetch_row($result1); 
//   $parentId=$row1[0];
//   echo $parentId . "<br/>";
//  
//    #to get the parent reseller id
//    $parentid =  substr($parentId,-8,-4);
//    echo $parentid."<br/>";
//    
//    #query to get the userid having the same resellerid as parent
//    $sql2 = $fun_obj->db->select('userId')->from('91_userBalance')->where("chainId LIKE '%".$parentid."%'");
//    $fun_obj->db->getQuery($sql2);
//    
//    #execute the query
//    $result2 = $fun_obj->db->execute($sql2);
//    #store the elements in an array 
//    while( $row2 = mysqli_fetch_array($result2))
//       {
//        $users[] = $row2['userId']; 
//        echo $users[]."<br/>";
//       }
//   
//    #query to get the tempNumbers from the table 91_tempNumbers
//    $sql3 = $fun_obj->db->select('tempNumber')->from('91_tempNumbers')->where("userId = '".$users[]."'");
//    echo $fun_obj->db->getQuery($sql3);
//  /*  var_dump($sql3);
//    #execute the query
//    $result3 = $fun_obj->db->execute($sql3);
//    #if the number exists then in 
//    if($result3->num_rows > 0)
//        {
//        $row3 = $result->fetch_array(MYSQL_ASSOC);
//        
//       echo "not possible to insert";
//    }
//    else "insert";
//  
//    } */
//    

?>
