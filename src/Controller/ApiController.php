<?php

namespace App\Controller;

use App\Service\CrmApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
		return new JsonResponse([
				'status' => 'success',
				'message' => 'Subscription created successfully.',
			],
			JsonResponse::HTTP_CREATED);
	}
}