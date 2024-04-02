<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Offer;
use App\Form\OfferType;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/nouvelle_offre', name: 'admin_new_offer')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$offer` variable has also been updated
            $offer = $form->getData();

            $entityManager->persist($offer);

            $entityManager->flush();
            
            return $this->redirectToRoute('app_offer');
        }

        return $this->render('admin/new_offer.html.twig', [
            'form' => $form,
        ]);
    }
}
