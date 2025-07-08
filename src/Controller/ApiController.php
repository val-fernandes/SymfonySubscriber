<?php

namespace App\Controller;

use App\Service\CrmApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use DateTime;

class ApiController extends AbstractController
{
    /**
     * Handle enquiry submission.
     */
    #[Route('/api/user-enquiry', name: 'api_send_enquiry', methods: ['POST'])]
    public function sendEnquiry(Request $request, CrmApiClient $apiClient): JsonResponse
    {
		return new JsonResponse([
				'status' => 'success',
				'message' => 'Your enquiry has been submitted successfully.',
			],
			JsonResponse::HTTP_CREATED);
	}

    /**
     * Handle the subscriber submission.
     */
    #[Route('/api/signup-subscriber', name: 'api_signup_subscriber', methods: ['POST'])]
    public function handleSubmission(Request $request, CrmApiClient $apiClient): JsonResponse
    {
		$data = $request->toArray();

		$this->validateInput($data);

		$subscriberResponse = $apiClient->createSubscriber([
			'email_address' => $data['email'],
			'first_name' => $data['firstName'] ?? null,
			'last_name' => $data['lastName'] ?? null,
			'date_of_birth' => $data['dob'],
			'marketing_consent' => $data['marketingConsent'],
			'lists' => $data['lists'],
		]);

		if (!isset($subscriberResponse['subscriber']['id'])) {
			throw new Exception('Failed to create subscriber: API did not return an ID.');
		}
		$subscriberId = $subscriberResponse['subscriber']['id'];

		return new JsonResponse([
				'status' => 'success',
				'message' => 'Subscription created successfully.',
				'subscriberId' => $subscriberId,
			],
			JsonResponse::HTTP_CREATED);
	}

    /**
     * Validates the incoming request data.
     *
     */
    private function validateInput(array $data): void
    {
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('A valid email address is mandatory.', JsonResponse::HTTP_BAD_REQUEST);
        }

        if (empty($data['dob'])) {
            throw new Exception('Date of birth is mandatory.', JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $birthDate = new DateTime($data['dob']);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;

            if ($age < 18) {
                throw new Exception('You must be at least 18 years old to subscribe.', JsonResponse::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            throw new Exception('Invalid date of birth provided or age requirement not met.', JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}