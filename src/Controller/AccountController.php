<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\ConnectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    #[Route('/compte/nouveau', name: 'account_new')]
    public function new_account(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$offer` variable has also been updated
            $user = $form->getData();

            $hashedPass = $passwordHasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hashedPass);
            $user->setKey(substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(50 / strlen($x)))), 1, 50));

            $entityManager->persist($user);

            $entityManager->flush();
            
            return $this->redirectToRoute('account_connection');
        }

        return $this->render('account/new_account.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/compte/connection', name: 'account_connection')]
    public function account_connection(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/connexion.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/compte', name: 'account')]
    public function myaccount(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        return $this->render('account/myaccount.html.twig', [
            'account' => $user,
        ]);
    }

    #[Route('/compte/commandes', name: 'account_orders')]
    public function orders(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $orderRepo = $entityManager->getRepository(Order::class);

        $orders = $orderRepo->findBy(['user_id' => $user->getId()]);

        return $this->render('account/orders.html.twig', [
            'orders' => $orders,
        ]);
    }
}
 