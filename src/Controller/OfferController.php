<?php

namespace App\Controller;

use App\Entity\Applicant;
use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $offers = $entityManager->getRepository(Offer::class)->findAll();

        return $this->render('offer/index.html.twig',
        [
            'offers' => $offers,
        ]);
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

    /**
     * @Route("/offer/{id}/apply", name="offer_apply")
     * @ParamConverter("offer", class="App\Entity\Offer")
     * @IsGranted("ROLE_APPLICANT")
     */
    public function apply(Offer $offer, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        if ( $applicant = $entityManager->getRepository(Applicant::class)->findOneBy(
            [
                'user' => $user,
            ]
        ) ) {
            $offer->addApplicant($applicant);
            $entityManager->persist($offer);

            try {
                $entityManager->flush();
                $this->addFlash('success', 'Solicitud recibida!');
            } catch (\Exception $exception) {
                $this->addFlash('danger', 'La solicitud no pudo almacenarse. Por favor intente nuevamente.');
            }

            return $this->redirectToRoute('offers');
        }
    }
}
