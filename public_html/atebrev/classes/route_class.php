<?php

/*  * 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
include dirname(dirname(__FILE__)) . '/config.php';
class route_class extends fun{
    function getRoute()
    {
        $res = $this->selectData('routeId,route', "91_route");
        if(!$res)
            return false;
        while($row = $res->fetch_array(MYSQLI_ASSOC))
        {
            $data[$row['routeId']] = $row['route'];
        }
        return $data;
    }
}