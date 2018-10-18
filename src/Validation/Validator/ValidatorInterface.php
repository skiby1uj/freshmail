<?php

namespace App\Validation\Validator;


interface ValidatorInterface {
	public function validate($param): array;
}
