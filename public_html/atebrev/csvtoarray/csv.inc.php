<?php
class csv_uploder{
	private $counter;
    private $handler;
	private $length;
	private $file;
	private $seprator;
	private $csvData = array();
	function __construct($file = "", $length = 1000, $seprator = ','){
		$this->counter = 0; 
		$this->length = $length; 
		$this->file = $file; 
		$this->seprator =  $seprator;
		$this->handler = fopen($this->file, "r");
	}

	function getCsv(){
		$getCsvArr = array();
		$csvDataArr = array();
		while(($data = fgetcsv($this->handler, $this->length, $this->seprator)) != FALSE){
			$num = count($data);
			$getCsvArr[$this->counter] = $data;
			$this->counter++;
		}
		if(count($getCsvArr) > 0){
			$csvDataArr = array_shift($getCsvArr);
			$counter = 0;
			foreach($getCsvArr as $csvValue){
				$totalRec = count($csvValue);
				for($i = 0; $i < $totalRec ; $i++){
					$this->csvData[$counter][$csvDataArr[$i]] = $csvValue[$i];
				}
				$counter++;
			}
		}
		return $this->csvData;
	}

	function __destruct(){
		fclose($this->handler);
	}
}
?> 
