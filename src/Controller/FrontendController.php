<?php

namespace App\Controller;

use App\Service\CrmApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class FrontendController extends AbstractController
{
    var $arrAllowedSubscriberList = ["London", "Birmingham", "Edinburgh"];



    #[Route('/', name: 'app_home')]
    public function index(CrmApiClient $apiClient): Response
    {
        $arrData = [];
		$arrSubscriberList = $apiClient->getSubcriberList();
		if (!empty($arrSubscriberList) && isset($arrSubscriberList["lists"])) {
			foreach ($arrSubscriberList["lists"] as $Subscriber) {
			// As per specification, we only want these subscriber list. Anything else isn't allowed.
				if (!in_array($Subscriber["name"], $this->arrAllowedSubscriberList))
					continue;

				$arrData["arrSubscriber"][$Subscriber["id"]] = $Subscriber["name"];
			}
		}

		return $this->render('frontend/index.html.twig', $arrData);
    }

    #[Route('/enquiry', name: 'app_enquiry')]
    public function enquiry(   
        #[MapQueryParameter] string $subscriberId,
        CrmApiClient $apiClient
        ): Response
    {
        $arrData = [];

        if (!empty($subscriberId))
            $arrData["subscriberId"] = $subscriberId;

        return $this->render('frontend/enquiry.html.twig', $arrData);
    }
}
