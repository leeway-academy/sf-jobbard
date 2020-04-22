<?php

namespace App\Controller;

use App\Entity\Company;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OfferController
 * @package App\Controller
 * @IsGranted("ROLE_COMPANY")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/company/{id}/offers", name="offers")
     * @ParamConverter("company", class="App\Entity\Company")
     */
    public function index(Company $company)
    {
        return $this->render('offer/index.html.twig', [
            'offers' => $company->getOffers(),
        ]);
    }
}
