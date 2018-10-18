<?php

namespace App\Model;


class DiscountCodeGeneratorModel
{
	private $arrCode;
	private $errors;

	public function __construct()
	{
		$this->arrCode = [];
		$this->errors = [];
	}

	public function generateCode(int $count, int $length): bool
	{
		$arrTmpCode = [];

		if ($this->isPossibleGenerated($count, $length)) {
			while ($count > count($arrTmpCode)) {
				$code = $this->codeRandomGenerator($length);
				$arrTmpCode[$code] = null;
			}

			$this->arrCode = array_keys($arrTmpCode);

			return true;
		}
		else {
			return false;
		}
	}

	public function getArrCode(): array
	{
		return $this->arrCode;
	}

	public function saveInDirectory(string $directory): bool
	{
		$handler = fopen($directory, "w");

		if (!$handler) {
			return false;
		}
		else {
			fwrite($handler, $this->prepareCodeToSave());
			fclose($handler);

			return true;
		}
	}

	public function getErrors(): array
	{
		return $this->errors;
	}

	private function isPossibleGenerated(int $count, int $length): bool
	{
		$numberOfCombination = 1;
		$tmp = 62;
		for ($i = 1; $i <= $length; $i++) {
			$numberOfCombination *= $tmp;

			if ($numberOfCombination >= $count) {
				return true;
			}
		}

		$this->errors[] = "Too small length of code to generate ".$count." codes";

		return false;
	}

	private function codeRandomGenerator(int $length): string
	{
		$code = '';

		while ($length > strlen($code)) {
			$code .= md5(random_bytes($length));
		}

		$code = substr($code, 0, $length);

		for ($i = 0; $i < $length; $i++) {
			if (!is_numeric($code[$i]) && rand(0,1) == 1) {
					$code[$i] = strtoupper($code[$i]);
			}
		}

		return $code;
	}

	private function prepareCodeToSave(): string
	{
		return implode(PHP_EOL, $this->arrCode);
	}
}
