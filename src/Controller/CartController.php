<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Order;
use App\Entity\OrderOffer;
use DateTime;
use Doctrine\Common\Cache\MultiGetCache;
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

        if (!in_array($itemType, $validTypes, true)) {
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

        array_splice($cart, $offerIndex, 1);

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/buy', name: 'cart_buy')]
    public function buy(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();

        if (!$session->has('cart')) {
            return $this->redirectToRoute('offers');
        }

        $cart = $session->get('cart');

        $order = new Order();
        $order->setUserId($this->getUser()->getId());
        $order->setStatus('confirmée');

        $totalprice = 0;

        foreach ($cart as [$offer, $itemType]) {
            $orderOffer = new OrderOffer();

            $multiplier = 0;
            switch ($itemType) {
                case 'solo':
                    $multiplier = 1;
                    break;
                case 'duo':
                    $multiplier = 2;
                    break;
                case 'famille':
                    $multiplier = 4;
                    break;
            }
            $totalprice += $offer->getPrice() * $multiplier;

            $orderOffer->setOffer($entityManager->getReference(Offer::class, $offer->getId()));
            $orderOffer->setOrderr($order);
            $orderOffer->setType($multiplier);
            $order->addOrderOffer($orderOffer);
            $entityManager->persist($orderOffer);
        }

        $order->setTotalPrice($totalprice);
        $order->setDate(new DateTime(date("Y-m-d h:m:s")));

        $order->setKey(substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(50 / strlen($x)))), 1, 50));

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('account_orders');
    }
}
