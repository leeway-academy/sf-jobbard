<?php

namespace App\Controller;

use App\Entity\Applicant;
use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OfferController
 * @package App\Controller
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
    public function apply(Offer $offer, EntityManagerInterface $entityManager, MailerInterface $mailer)
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

                $email = (new Email())
                    ->from('jobboard@platzi.com')
                    ->to($offer->getOwner()->getEmail())
                    ->subject('Se recibió un nuevo postulante para '.$offer->getTitle().'!')
                    ->html('<p>'.$applicant->getName().' está interesado/a. Su correo es '.$applicant->getEmail().'</p>')
                ;
                $mailer->send($email);
            } catch (\Exception $exception) {
                $this->addFlash('danger', 'La solicitud no pudo almacenarse. Por favor intente nuevamente.');
            }

            return $this->redirectToRoute('offers');
        } else {
            return $this->redirectToRoute('applicant_new',
            [
                'offerId' => $offer->getId(),
            ]);
        }
    }
}
