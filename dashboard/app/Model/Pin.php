<?php
namespace App\Model;

class Pin {
	public function __construct() {
		$this->alphabet = range('0', '9');
	}

	public function generate($length = 5) {
		$generatedPin = '';
		for($i = 0; $i < $length;$i++) {
			$generatedPin .= $this->alphabet[rand(0, count($this->alphabet)-1)];
		}
		return $generatedPin;
	}
}

?>