<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\CompanyType;
use App\Form\OfferType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="company")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $companies = $entityManager
            ->getRepository(Company::class)
            ->findAll();

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/company/new", name="company_new")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class);
        $form->setData($company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $form->getData();

            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company');
        }

        return $this->render('company/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/company/{id}/offers", name="company_offers")
     * @ParamConverter("company", class="App\Entity\Company")
     * @IsGranted("ROLE_COMPANY")
     */
    public function showOffers(Company $company)
    {
        return $this->render('company/show_offers.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @param Company $company
     * @param Request $request
     * @Route("/company/{id}/offers/new", name="new_offer")
     * @ParamConverter("company", class="App\Entity\Company")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newOffer(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        $offer = new Offer();
        $offer->setOwner($company);

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('offers', ['id' => $company->getId()]);
        }

        return $this->render('offer/new.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }
}
