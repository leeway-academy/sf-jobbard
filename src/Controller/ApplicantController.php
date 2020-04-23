<?php

namespace App\Controller;

use App\Entity\Applicant;
use App\Form\ApplicantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApplicantController extends AbstractController
{
    /**
     * @Route("/applicant", name="applicant")
     */
    public function index()
    {
        return $this->render('applicant/index.html.twig', [
            'controller_name' => 'ApplicantController',
        ]);
    }

    /**
     * @Route("/applicant/new", name="applicant_new")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $applicant = new Applicant();
        $applicant->setUser($this->getUser());
        $form = $this->createForm(ApplicantType::class, $applicant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($applicant);
            $entityManager->flush();

            return $this->redirectToRoute('offer_apply', [
                'id' => $request->get('offerId'),
            ]);
        }

        return $this->render('applicant/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
