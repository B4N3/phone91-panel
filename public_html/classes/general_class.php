<?php
include_once("db_class.php");
class general_class extends db_class
{
	//Function to return the current date and Time as per IST
	function current_date($type)
	{
		$date=date("d");
		$month=date("m");
		$year=date("y");
		$hr= date("G");
		$min= date("i");
		$hr=$hr;
		$min=$min;
		//$hr=$hr+5;
		//$min=$min+30;
		if($min>=60)
		{
			$hr=$hr+1;
			$min=$min-60;
		}
		if($hr>=24)
		{
			$hr=$hr-24;
			$date=$date+1;
		}
		if(($month==1)&&($date>31))
		{
			$month=$month+1;
			$date=$date-31;
		}
		if(($month==2)&&($date>29))
		{
			$month=$month+1;
			$date=$date-29;
		}
		if(($month==3)&&($date>31))
		{
			$month=$month+1;
			$date=$date-31;
		}
		if(($month==4)&&($date>30))
		{
			$month=$month+1;
			$date=$date-30;
		}
		if(($month==5)&&($date>31))
		{
			$month=$month+1;
			$date=$date-31;
		}
		if(($month==6)&&($date>30))
		{
			$month=$month+1;
			$date=$date-30;
		}
		if(($month==7)&&($date>31))
		{
			$month=$month+1;
			$date=$date-31;
		}
		if(($month==8)&&($date>31))
		{
			$month=$month+1;
			$date=$date-31;
		}
		if(($month==9)&&($date>30))
		{
			$month=$month+1;
			$date=$date-30;
		}
		if(($month==10)&&($date>31))
		{
			$month=$month+1;
			$date=$date-31;
		}
		if(($month==11)&&($date>30))
		{
			$month=$month+1;
			$date=$date-30;
		}
		if(($month==12)&&($date>31))
		{
			$month=$month+1;
			$date=$date-31;
		}
		if($month>12)
		{
			$year=$year+1;
			$month=$month-12;
		}
		if(strlen($date)<2)
			$date="0".$date;
		if(strlen($hr)<2)
			$hr="0".$hr;
		if(strlen($min)<2)
			$min="0".$min;
		if($type==1)
			$date_time=$year."-".$month."-".$date." ".$hr.":".$min.":00";
		if($type==2)
			$date_time=$year."-".$month."-".$date;
		if($type==3)
			$date_time=$hr.":".$min.":00";
		return $date_time;
	}//End Function
	
}

$general_obj	=	new general_class();//class object
?>