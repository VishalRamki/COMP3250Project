<?php

/**
 **		Template Class
 **
 **		11:27 AM - 17/03/15
 **
 **		- Added is_assoc()
 **			This allows the code to test and see if an associative array was passed in. This lets the code
 **			pick the correct build function to pass in the data to, this code is used in and affects
 **			buildHashFromArray();
 **
 **		- Optimized And Included A Clean Up Procedure in output()
 **			A small optimization code was incorporated.
 **
 **/


class Template {
	protected $file;
	protected $values = array();
	
	public function __construct($file) {
		$this->file = $file;
	}
	
	public function set($key, $value) {
		$this->values[$key] = $value;
	}
	
	public function output() {
		if (!file_exists($this->file)) {
			return "Error loading template file ($this->file).";
		}
		
		$output = file_get_contents($this->file);
		ob_start();
		foreach ($this->values as $key => $value) {
			$tagToReplace = "{".$key."}";
			$output = str_replace($tagToReplace, $value, $output);
		}
		ob_end_clean();
		
		return $output;
	}
	
	// Customer Built Functions:
	
	public function is_assoc($array) {
		/*
		 *	CODE BY @Captain kurO 
		 *	FROM http://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential;
		 */
		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}
	
	public function buildHashFromArray($array, $params) {
		 
		if (count($array) == count($params)) {
			foreach($params as $param => $value) {				
				if ($this->is_assoc($array) != 0) {
					$this->set($value, $array[$value]);
				} else {
					$this->set($value, $array[$param]);
				}
			}
		} else {
			echo $this->errors('array_params_size_error');	
		}
	}
	
	public function errors($str) {
		$error = NULL;
		switch($str) {
		
			case "array_params_size_error":
				$error = "Parameter And Array Sizes Do Not Match.";
				break;
			default:
				$error = "Some Error, who knows.";
				break;
		}
		
		return $error;
	}

}

?>
