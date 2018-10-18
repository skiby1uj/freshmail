<?php

namespace App\Validation\Validator;


class IntegerValidator implements ValidatorInterface
{
	public function validate($param): array
	{
		$errors = [];

		if (!is_numeric($param)) {
			$errors[] = "Must be numeric";
		}
		else if (!ctype_digit($param)) {
			$errors[] = "The number must be integer";
		}

		return $errors;
	}
}
