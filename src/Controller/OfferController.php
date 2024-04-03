<?php

namespace App\Controller;

use App\Form\OfferType;
use App\Entity\Offer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OfferController extends AbstractController
{
    #[Route('offres', name: 'offers')]
    public function offers(EntityManagerInterface $entityManager)
    {
        $repo = $entityManager->getRepository(Offer::class);

        $offers = $repo->findAll();

        return $this->render('offer/offers.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('offre/{id}', name: 'offer')]
    public function offer(EntityManagerInterface $entityManager, int $id)
    {
        $repo = $entityManager->getRepository(Offer::class);

        $offer = $repo->find($id);

        return $this->render('offer/offer.html.twig', [
            'offer' => $offer,
        ]);
    }
}
