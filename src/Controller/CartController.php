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
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add', name: 'cart_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offerId = $request->request->get('offerId');
        $itemType = $request->request->get('itemType');

        $validTypes = ['solo', 'duo', 'famille'];

        if(!in_array($itemType, $validTypes, true)){
            return $this->redirectToRoute('offer', ['id' => $offerId]);
        }

        $offer = $entityManager->getRepository(Offer::class)->find($offerId);

        $session = $request->getSession();

        $cart = $session->has('cart') ? $session->get('cart') : [];

        array_push($cart, [$offer, $itemType]);

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove', name: 'cart_remove')]
    public function remove(Request $request): Response
    {
        $offerIndex = $request->request->get('offerIndex');

        $session = $request->getSession();

        $cart = $session->get('cart');

        unset($cart[$offerIndex]);

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }
}
