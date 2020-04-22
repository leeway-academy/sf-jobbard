<?php

namespace App\Controller;

use App\Entity\Offer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OfferController
 * @package App\Controller
 * @Route("/offer")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/", name="offers")
     * @ParamConverter("company", class="App\Entity\Company")
     */
    public function index()
    {
        return $this->render('offer/index.html.twig');
    }

    /**
     * @Route("/offer/{id}", name="offer_show")
     * @ParamConverter("offer", class="App\Entity\Offer")
     */
    public function show(Offer $offer)
    {
        return $this->render('offer/show.html.twig',
        [
            'offer' => $offer,
        ]);
    }
}
