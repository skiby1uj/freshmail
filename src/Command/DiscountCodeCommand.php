<?php

namespace App\Command;


use App\Model\DiscountCodeGeneratorModel;
use App\Validation\CodeCountValidation;
use App\Validation\CodeLengthValidation;
use App\Validation\Validator\CodeCountValidator;
use App\Validation\Validator\CodeLengthValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DiscountCodeCommand extends Command
{
	public function configure()
	{
		$this->setName('app:discount-code');

		$this->addOption('numberOfCodes', 'How many codes generate', InputOption::VALUE_REQUIRED);
		$this->addOption('lengthOfCode', 'Length of the one code', InputOption::VALUE_REQUIRED);
		$this->addOption('file', 'Path and file name where codes should be save', InputOption::VALUE_REQUIRED);

		$this->setDescription('Generate discount code');

		$this->setHelp('--numberOfCodes	how many code create'.PHP_EOL.
						'--lengthOfCode	how length of the one code'.PHP_EOL.
						'--file		path and file name where codes should be save');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$options = $input->getOptions();

		$msgOptions = $this->checkRequiredOptions($options);

		if (count($msgOptions)) {
			$output->writeln($msgOptions);
		}
		else {
			$discountCodeGenerator = new DiscountCodeGeneratorModel();
			if ($discountCodeGenerator->generateCode($options['numberOfCodes'], $options['lengthOfCode'])) {
				$discountCodeGenerator->saveInDirectory($options['file']);

				$output->writeln("Success");
			}
			else {
				$output->writeln($discountCodeGenerator->getErrors());
			}
		}
	}

	private function checkRequiredOptions(array $options): array
	{
		$errors = [];

		if (empty($options['numberOfCodes'])) {
			$errors[] = "You must write numberOfCodes option";
		}
		else {
			$codeCountValidation = new CodeCountValidation(new CodeCountValidator());
			$errors = ($codeCountValidation->isValid($options['numberOfCodes'])) ? [] : $codeCountValidation->getErrors();
		}

		if (empty($options['lengthOfCode'])) {
			$errors[] = "You must write lengthOfCode option";
		}
		else {
			$codeLengthValidation = new CodeLengthValidation(new CodeLengthValidator());
			$errors = ($codeLengthValidation->isValid($options['lengthOfCode'])) ? [] : $codeLengthValidation->getErrors();
		}

		if (empty($options['file'])) {
			$errors[] = "You must write file option";
		}

		return $errors;
	}
}