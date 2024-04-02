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
            
            return $this->redirectToRoute('offers');
        }

        return $this->render('admin/new_offer.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/gerer_offres', name: 'manage_offers')]
    public function manage(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repo = $entityManager->getRepository(Offer::class);

        $offers = $repo->findAll();

        return $this->render('admin/manage_offers.html.twig', [
            'offers' => $offers,
         //'form' => $form,
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'delete_offer')]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $id)
    {
        $repo = $entityManager->getRepository(Offer::class);

        $offer = $repo->find($id);

        $entityManager->remove($offer);

        $entityManager->flush();

        return $this->redirectToRoute('manage_offers');
    }
}
