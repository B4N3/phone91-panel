<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("/home/voip91/public_html/newapi/general_class.php");
include_once("/home/voip91/public_html/newapi/panel_config.php");
include_once("/home/voip91/public_html/newapi/function_layer.php");

if (isset($_REQUEST['action']))
{
    
    switch ($_REQUEST['action'])
    {
                    case 1:
                    {
                        $id=$_REQUEST['id'];
                        $psd=$_REQUEST['new_pass'];
                        include_once("/home/voip91/public_html/newapi/function_layer.php");

                            $con = $funobj->connect();
                            $query = "UPDATE clientsshared SET  password='" .$psd . "' where id_client=" .$id. ";";
                                    $result = mysql_query($query,$con);
                                    if($result) {
                                        echo "done";
                                    }   
                                    mysql_close($con);
                                    break;
                            }

                
                 case 2:
                     {
                        $id=$_REQUEST['id'];
                        $amount=$_REQUEST['amount'];
                        include_once("/home/voip91/public_html/newapi/function_layer.php");

                            $con = $funobj->connect();
                            $query = "UPDATE clientsshared SET account_state = '".$amount ."' WHERE id_client = " .$id. ";";


                                    $result = mysql_query($query,$con);
                                    if($result) {
                                        echo "done";
                                    }                         
                                    unset($id);   
                                    mysql_close($con);
                                    break;
                                    
                            }
                            
                 case 3:
                     {
                        $id=$_REQUEST['id'];
                        $oldpsd=$_REQUEST['oldpsd'];
                        $newpsd=$_REQUEST['newpsd'];
                        include_once("/home/voip91/public_html/newapi/function_layer.php");

                            $con = $funobj->connect();
                            $query = "UPDATE clientsshared SET password = '".$newpsd ."' WHERE id_client = " .$id. " and password = '". $oldpsd ."';";


                                    $result = mysql_query($query,$con);
                                    if($result) {
                                        echo "done";
                                    }                         
                                       unset($id);   
                                    mysql_close($con);
                                    break;
                            }
		
    }
    
    
}

?>
