<?php

namespace App\Controller;

use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(Offer::class);
        $lastOffers = $repo->findLastThree();

        return $this->render('accueil/index.html.twig', [
            'lastOffers' => $lastOffers,
        ]);
    }
}
