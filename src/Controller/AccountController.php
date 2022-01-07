<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Commande;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account", name="account")
     */
    public function account(PanierService $cart): Response
    {
        $commandes = $this->entityManager->getRepository(Commande::class)->findAll();
     
        return $this->render('account/account.html.twig', [
            'commandes' => $commandes,
         ]);    
    }
}