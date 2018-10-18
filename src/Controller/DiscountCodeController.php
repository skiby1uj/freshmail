<?php

namespace App\Controller;


use App\Model\DiscountCodeGeneratorModel;
use App\Validation\CodeCountValidation;
use App\Validation\CodeLengthValidation;
use App\Validation\Validator\CodeCountValidator;
use App\Validation\Validator\CodeLengthValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class DiscountCodeController extends AbstractController
{
    /**
     * @Route("/", name="discount_code")
     */
    public function indexAction()
    {
        return $this->render('discount_code/index.html.twig');
    }

	/**
	 * @Route("/download", name="discount_download")
	 */
    public function downloadAction(Request $request)
	{
		$codeCount = $request->request->get('code_count');
		$codeCountErrors = $this->checkCodeCount($codeCount);

		$codeLength = $request->request->get('code_length');
		$codeLengthErrors = $this->checkLengthCode($codeLength);

		if (empty($codeCountErrors) && empty($codeLengthErrors)) {
			return $this->createResponse($codeCount, $codeLength);
		}
		else {
			return $this->render('discount_code/index.html.twig', [
				'codeCountErrors' => $codeCountErrors,
				'codeLengthErrors' => $codeLengthErrors,
			]);
		}
	}

	private function createResponse(int $codeCount, int $codeLength)
	{
    	$response = null;

		$discountCodeGenerator = new DiscountCodeGeneratorModel();

		if ($discountCodeGenerator->generateCode($codeCount, $codeLength)) {
			$directory = "download/discount_code.txt";

			if ($discountCodeGenerator->saveInDirectory($directory)) {
				$response = new BinaryFileResponse($directory);
				$response->setContentDisposition(
					ResponseHeaderBag::DISPOSITION_ATTACHMENT,
					'discount_code.txt'
				);
			}
			else {
				$response = new Response("Something went wrong. Please try again", 500);
			}
		}
		else {
			$errorMsg = implode(PHP_EOL, $discountCodeGenerator->getErrors());

			$response = new Response($errorMsg, 400);
		}

		return $response;
	}

	private function checkCodeCount($codeCount): array
	{
		$codeCountValidation = new CodeCountValidation(new CodeCountValidator());
		$codeCountErrors = [];

		if (!$codeCountValidation->isValid($codeCount)) {
			$codeCountErrors = $codeCountValidation->getErrors();
		}
		return $codeCountErrors;
	}

	private function checkLengthCode($codeLength): array
	{
		$codeLengthValidation = new CodeLengthValidation(new CodeLengthValidator());
		$codeLengthErrors = [];


		if (!$codeLengthValidation->isValid($codeLength)) {
			$codeLengthErrors = $codeLengthValidation->getErrors();
		}
		return $codeLengthErrors;
	}
}
