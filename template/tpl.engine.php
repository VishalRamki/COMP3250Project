<?php

	/*
	 *	Template Engine: 0.0 ALPHA
	 *	
	 *	NB: TEMPLATE ENGINE 0.0 ALPHA IS BASED UPON THESE FOLLOWING RESOURCES:
	 *		1. http://www.broculos.net/2008/03/how-to-make-simple-html-template-engine.html#.VQcj4uGnboc
	 *		2. http://www.gabrielemittica.com/cont/guide/how-you-can-create-a-light-and-useful-template-engine-for-php/10/1.html
	 *		3. http://stackoverflow.com/questions/5540828/how-to-make-a-php-template-engine
	 *
	 *
	 *	These codes are being used as a base to build the template engine upwards. 
	 *
	 *	11:55 PM - 16/03/15
	 *
	 *	Template Engine Phase One Completed;
	 *	The Template Engine can take array and 2d arrays and push the data onto a template file.
	 *	Additional Work required to prepare template files as well as other features of the 
	 *	template.
	 *
	 */

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
		
		foreach ($this->values as $key => $value) {
			$tagToReplace = "{".$key."}";
			$output = str_replace($tagToReplace, $value, $output);
		}
		
		
		return $output;
	}
	
	// Customer Built Functions:
	
	public function buildHashFromArray($array, $params) {
		/*
		 *  Needs To Check For 2-D Arrays.
		 */
		
		if (count($array) == count($params)) {
			foreach($params as $param => $value) {
				$this->set($value, $array[$param]);
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

class MultiView extends Template {
	
	public $stackLot = array();
	
	public function buildMultiStack($stack, $stackParams) {
		
		foreach($stack as $block => $value) {
			$row = new Template($this->file);
			$row->buildHashFromArray($value, $stackParams);
			$this->stackLot[] = $row;
		}
	}
	
	public function mergeMultiStack() {
		$output = '';
		foreach($this->stackLot as $row) {
			$output .= $row->output();
		}
		return $output;
	}
	
}

$profile = new Template("default/userprofile.php");
$multiProfile = new MultiView("default/userprofiles.php");

$multiProfile->buildMultiStack(array(array("TE", "Xtra Xtra, Read All About it", "21", "UWI"), array("TR", "QWERTY", '33', "UWI")), array("username", "name", "age", "location"));

$profile->set("users", $multiProfile->mergeMultiStack());
$profile->buildHashFromArray(array("Team Cusine's Mega Awesome App (MAA)"), array("site-name"));

echo $profile->output();


?>
