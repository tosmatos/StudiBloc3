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
    public function offers(EntityManagerInterface $entityManagerInterface)
    {
        $repo = $entityManagerInterface->getRepository(Offer::class);

        $offers = $repo->findAll();

        return $this->render('offer/offers.html.twig', [
            'offers' => $offers,
        ]);
    }
}
