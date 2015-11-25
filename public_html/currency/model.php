<?php

class models
{
	function __construct()
	{
	}
     
        function connectMongoDb($type=NULL) 
	{
//             $m = new Mongo('mongodb://runtask:runtask#123@localhost:30000/runtask');
//	     $db=$m->runtask;
//             return $db;
            
            $m = new Mongo('mongodb://127.0.0.1:27017/phone91');
            $db=$m->phone91;

            return $db;
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
               	$db->$collectionName->update($conditionArray,$dataArray);
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
        
        function mongo_count($collectionName,$conditionArray=array())
	{
		$db=$this->connectMongoDb();
		$count=$db->$collectionName->find($conditionArray)->count();
		return $count;
	}

}
?>
