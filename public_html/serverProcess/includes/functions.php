<?php

	function validData($string){
		return mysql_real_escape_string(trim($string));
	}


?>