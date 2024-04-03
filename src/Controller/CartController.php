<?php

namespace App\Controller;

use App\Entity\Offer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        $cart = $session->has('cart') ? $session->get('cart') : [];

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/cart/{id}', name: 'cart_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $offer = $entityManager->getRepository(Offer::class)->find($id);

        $session = $request->getSession();

        $cart = $session->has('cart') ? $session->get('cart') : [];

        $cart->add([$offer, 'hey']);

        return $this->redirectToRoute('cart');
    }
}
