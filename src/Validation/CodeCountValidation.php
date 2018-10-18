<?php

namespace App\Validation;


use App\Validation\Validator\ValidatorInterface;

class CodeCountValidation implements ValidationInterface
{
	private $errors;
	private $validator;

	public function __construct(ValidatorInterface $validator)
	{
		$this->errors = [];
		$this->validator = $validator;
	}

	public function isValid($param): bool
	{
		$this->errors = $this->validator->validate($param);

		return count($this->errors) ? false: true;
	}

	public function getErrors(): array
	{
		return $this->errors;
	}
}
