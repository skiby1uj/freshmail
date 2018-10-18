<?php

namespace App\Validation\Validator;


class CodeCountValidator implements ValidatorInterface
{
	public function validate($param): array
	{
		$errors = [];
		
		if (empty($param)) {
			$errors[] = "Can't be empty";
		}
		else {
			$integerValidator = new IntegerValidator();
			$integerValidatorErrors = $integerValidator->validate($param);

			if (count($integerValidatorErrors) == 0 && $param <= 0) {
				$errors[] = "The number must be greater than 0";
			}
			else {
				$errors = array_merge($errors, $integerValidatorErrors);
			}
		}
		return $errors;
	}
}
