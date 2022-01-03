<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Voyage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    
    /**
     * 
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {
        $voyage = $this->entityManager->getRepository(Voyage::class)->findAll();
        $user = $this->entityManager->getRepository(User::class)->findAll();


        return $this->render('dashboard/dashboard.html.twig', [
           'voyage' => $voyage,
           'user' => $user
        ]);
    }
}
