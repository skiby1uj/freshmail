<?php

namespace App\Validation;


interface ValidationInterface {
	public function isValid($param): bool;
	public function getErrors(): array;
}
