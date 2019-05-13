<?php
namespace App\Validator;

class IdValidator extends \Illuminate\Validation\Validator {
	public function validateId($attribute, $value, $parameters) {
		return $this->validateInteger($attribute, $value) && $value >= 1;
	}
}
?>