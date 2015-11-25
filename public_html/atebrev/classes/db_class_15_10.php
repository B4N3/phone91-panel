
<?php

class db_class
{
	function connect_db()
	{	
		$i=0;
		do
		{
			//$dbh=mysql_connect("216.245.201.194","voipswitchuser",'+4H8ZXcSyWn7CuX*');
			$dbh=mysql_connect("192.168.1.174","voipswitchuser",'+4H8ZXcSyWn7CuX*');
			$i++;
			if($i==100)
			{
				die("<strong>Unable to connect to database</strong>");
			}
		}
		while(!$dbh);
		if(!mysql_select_db("voipswitch"))
			die("<strong>Unable to Select Database</strong>");
		return $dbh;
	}
        

        
        
 /*********************** mongo db functions ****************/       
        
        
function connectMongoDb($type=NULL) 
{
    
// $m = new Mongo('mongodb://runtask:runtask#123@localhost:30000/runtask');
//$db=$m->runtask;
                
$m = new Mongo('mongodb://127.0.0.1:27017/phone91');
$db=$m->phone91;

return $db;


		
}
	
	
# Function is used to get autoincrement number
/*
 *
 * 3 parameters are passed here 
 * '$collection' is the collection name in which all increment numbers are stored. Name like 'incrementNumbers'
 * '$countValue' is the auto increment field name in '$collection'.
 * '$field' is the field in '$collection' storing the next value like next auto increment number.
 *
 */
function mongo_increment($collection,$countValue,$field)
{
        $db=$this->connectMongoDb();
        $next =$db->command(
                                  array(
                                        "findAndModify" => $collection,
                                        "query" => array("count"=> $countValue),
                                        "update" => array('$inc'=> array($field=> 1))
                                  )
                                );
        $chk=array(0,'','null',NULL);
        if(in_array($next['value'][$field],$chk))
        {
                $db->$collection->insert(array("count"=> $countValue,$field=>1));
                $next =$db->command(
                                  array(
                                        "findAndModify" => $collection,
                                        "query" => array("count"=> $countValue),
                                        "update" => array('$inc'=> array($field=> 1))
                                  )
                                );

        }
        $inc=$next['value'][$field];
        if(in_array($inc,$chk))
                $inc=0;
        return $inc;
}
# Function is used to insert data in mongodb
/*
 *
 * 2 parameters are passed here 
 * '$collectionName' is the collection in which we want to insert
 * '$dataArray' is array to insert.
 *
 */
function getCollectionName($colName)
{

}
function mongo_insert($collectionName,$dataArray)
{
        $db=$this->connectMongoDb();
        $db->$collectionName->insert($dataArray);
        $status=$db->Command(array('getlasterror'=>1));
        return $status;//return status of current operation
        //Array ( [n] => 0 [connectionId] => 37 [err] => [ok] => 1 ) 
}
# Function is used to update data in mongodb
/*
 *
 * 3 parameters are passed here 
 * '$collectionName' is the collection in which we want to insert
 * '$conditionArray' is condition.
 * '$dataArray' is array to update.
 *
 */
function mongo_update($collectionName,$conditionArray,$dataArray)
{
        $db=$this->connectMongoDb();
        $db->$collectionName->update($conditionArray,$dataArray,array('w' => 1));
        $status=$db->Command(array('getlasterror'=>1));
        return $status;//return status of current operation
        //Array ( [updatedExisting] => 1 [n] => 1 [connectionId] => 36 [err] => [ok] => 1 ) 
}

# Function is used to delete data in mongodb
/*
 *
 * 2 parameters are passed here 
 * '$collectionName' is the collection from which we want to delete data
 * '$conditionArray' is condition to delete.
 *
 */
function mongo_delete($collectionName,$conditionArray)
{
        $db=$this->connectMongoDb();
        $db->$collectionName->remove($conditionArray);
        $status=$db->Command(array('getlasterror'=>1));
        return $status;//return status of current operation
        //Array ( [n] => 1 [connectionId] => 36 [err] => [ok] => 1 ) 
}
# Function is used to find data from mongodb
/*
 *
 * 3 parameters are passed here 
 * '$collectionName' is the collection in which we want to insert
 * '$conditionArray' is condition to fetch data.
 * '$fetchArray' is the array to fetch selected items.
 *
 */
function mongo_find($collectionName,$conditionArray=array(),$fetchArray=array())
{
        $db=$this->connectMongoDb();

        $result=$db->$collectionName->find($conditionArray,$fetchArray);//show all field in collection
        return $result;
}

//column name is '$Taskno';

function mongo_aggregation($collectionName)
{
        $db=$this->connectMongoDb();

        $result=$db->$collectionName->aggregate(array('$match'=>array('taskcreatTime'=>array('$gt'=>new MongoDate(strtotime("2013-06-22 00:00:00")) ))),array('$group'=>array('_id'=>null,total=> array('$sum'=>'$TaskID'))));//show all field in collection
        return $result;
}
# Function is used to count data in mongodb
/*
 *
 * 2 parameters are passed here 
 * '$collectionName' is the collection in which we want to insert
 * '$conditionArray' is condition to count data.
 *
 */
function mongo_count($collectionName,$conditionArray)
{
        $db=$this->connectMongoDb();
        $count=$db->$collectionName->find($conditionArray)->count();
        return $count;
}

}

$db_obj	=new db_class();//class object
$db_obj->connectMongoDb($type=NULL) 
?>

