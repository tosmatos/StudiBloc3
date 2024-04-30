<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Offer;
use App\Entity\OrderOffer;
use App\Form\OfferType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Image;


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
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$offer` variable has also been updated
            $offer = $form->getData();

            $image = $form->get("image")->getData();

            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $saveFilename = $slugger->slug($originalFilename);
            $newFilename = $saveFilename.'-'.uniqid().'.'.$image->guessExtension();

            try {
                $image->move(
                    $this->getParameter('offer_images'),
                    $newFilename,
                );
            } catch (FileException $e) {
                //Handle exception 
            }

            $offer->setImage($newFilename);

            $entityManager->persist($offer);

            $entityManager->flush();
            
            return $this->redirectToRoute('manage_offers');
        }

        return $this->render('admin/new_offer.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/gerer_offres', name: 'manage_offers')]
    public function manage(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repo = $entityManager->getRepository(Offer::class);
        $offerOrderRepo = $entityManager->getRepository(OrderOffer::class);

        $offers = $repo->findAll();
        $offersSales = [];

        foreach ($offers as $offer) {
            $foundOffers = $offerOrderRepo->findBy(['Offer' => $offer->getId()]);
            array_push($offersSales, count($foundOffers));
        }

        return $this->render('admin/manage_offers.html.twig', [
            'offers' => $offers,
            'sales' => $offersSales,
         //'form' => $form,
        ]);
    }

    #[Route('/admin/modifier_offre/{id}', name: 'edit_offer')]
    public function edit(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, int $id): Response
    {
        $repo = $entityManager->getRepository(Offer::class);

        $offer = $repo->find($id);

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$offer` variable has also been updated
            $offer = $form->getData();

            $image = $form->get("image")->getData();

            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $saveFilename = $slugger->slug($originalFilename);
            $newFilename = $saveFilename.'-'.uniqid().'.'.$image->guessExtension();

            try {
                $image->move(
                    $this->getParameter('offer_images'),
                    $newFilename,
                );
            } catch (FileException $e) {
                //Handle exception 
            }

            $offer->setImage($newFilename);

            $entityManager->flush();
            
            return $this->redirectToRoute('manage_offers');
        }

        return $this->render('admin/new_offer.html.twig', [
            'form' => $form,
            'offer' => $offer,
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'delete_offer')]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $repo = $entityManager->getRepository(Offer::class);

        $offer = $repo->find($id);

        $entityManager->remove($offer);

        $entityManager->flush();

        return $this->redirectToRoute('manage_offers');
    }
}
