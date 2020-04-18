<?php

namespace App\Controller;

use App\Form\OfferType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Offer;

/**
 * Class OfferController
 * @package App\Controller
 * @Route("/offer")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/", name="offer_index")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $offers = $entityManager
            ->getRepository(Offer::class)
            ->findAll();

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/new", name="offer_new")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(OfferType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer = new Offer();
            $offer
                ->setOwner()
                ->setTitle($form['title']->getData())
            ;
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('offer_index');
        }

        return $this->render(

        );
    }
}
