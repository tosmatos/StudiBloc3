<?php

namespace App\Controller;

use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Visitor;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(Offer::class);
        $lastOffers = $repo->findLastThree();

        $visitorCounter = $em->getRepository(Visitor::class)->find(1);

        $this->incrementVisitors($visitorCounter);

        $em->flush();

        return $this->render('accueil/index.html.twig', [
            'lastOffers' => $lastOffers,
        ]);
    }

    public function incrementVisitors(Visitor $visitor): void
    {
        $counter = $visitor->getCounter();

        $counter += 1;

        $visitor->setCounter($counter);
    }
}
