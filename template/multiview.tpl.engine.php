<?php

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

?>
