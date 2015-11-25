<?php

error_reporting(0);

include_once 'config.php';
include_once(CLASS_DIR."phonebook_class.php");
$phoneObj = new  phonebook_class();
            
$collectionName = 'phonebook_backup';
$dbobj = new db_class();
#check for contact no. is already inserted in table
$condition = array('userId' => '34464' , 'contact.accessNo' => '16465064987' );

$db = $dbobj->connectMongoDb();

$result = $db->$collectionName->findOne( $condition );//show all field in collection

print_r($result);
die(0);

//$result = $dbobj->mongo_find($collectionName, $condition, array('contact.$.accessNo' => 1));


foreach($result as $val )
{
    foreach($val['contact'] as $values )
    {
        print_r($values);
        
        echo "<br><br>";
    }
}


die('1');




error_reporting(0);
include_once 'config.php';

require "classes/phonebook_class.php";
$pbookobj = new phonebook_class();

$dbobj = new db_class();

$result = $dbobj->mongo_find('phonebook', array());
 
$contactArray = array();

$singleCon = array();

foreach($result as $value) 
{
    if(!empty($value['userId']))
    {
        foreach($value['contact'] as $key => $contactArr)
        {
            var_dump($value['userId']);
            echo '<br>';
            
            if($value['userId'] == 30779)
            {
                print_r($contactArr);
                echo '<br><br>';
            }
            
          foreach($contactArr as $key=> $con)
          {
              $contactArray[$key] = $con;
          }
          $contactArray['userId'] = $value['userId'];
          
          if(!empty($contactArray))
        {
            //print_r($contactArray);
            
            echo '<br><br>';
            $singleCon[] = $contactArray;
        } 
        }
          
    }
}

$collectionName = 'phonebook2';
$db = $dbobj->connectMongoDb();

//print_r($singleCon);

//$response = $db->$collectionName->batchInsert(array(array("name" => "nidhi") ,array("name" => "nidhi")  ,array("name" => "nidhi") ));

$response = $db->$collectionName->batchInsert($singleCon );


//print_r($response);

echo '<br><br><br>';

//print_r($singleCon);


die(2);

die(1);
 



die(1);

if($_REQUEST['action'] == 'userName')
{
    $response = $funobj->getUserInformation($_REQUEST['userName']);
    
    print_r($response);
}

if($_REQUEST['action'] == 'code')
{
    $result = $funobj->selectData("*", "91_tempNumbers"," tempNumber='".$_REQUEST['number']."'");
    
    if($result && $result->num_rows > 0)
    {
      $userDetail = $result->fetch_array(MYSQLI_ASSOC);
    }
    else 
      $userDetail = 0;

    print_r($userDetail);
    
}


if($_REQUEST['action'] == 'updateUser')
{
    $data = array( "userName" => $_REQUEST['userName'] );
    $condition = "userName='nidhi@walkover.in'";
    
    $result = $funobj->updateData($data, '91_userLogin', $condition );   
}


?>