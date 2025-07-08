<?php

namespace App\Controller;

use App\Service\CrmApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class FrontendController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
		return $this->render('frontend/index.html.twig');
    }

    #[Route('/enquiry', name: 'app_enquiry')]
    public function enquiry(): Response
	{
        return $this->render('frontend/enquiry.html.twig');
    }
}