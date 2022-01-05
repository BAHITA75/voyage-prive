<?php

namespace App\Controller;

use App\Entity\Voyage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $voyage = $this->entityManager->getRepository(Voyage::class)->findAll();
        
        return $this->render('home/home.html.twig', [
           'voyage' => $voyage
        ]);
    }
}
