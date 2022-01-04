<?php

namespace App\Controller;

use App\Entity\Voyage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SingleVoyageController extends AbstractController
{

    public function __construct(EntityManagerInterface $EntityManager) 
    {
        $this->entityManager=$EntityManager;
    }
    /**
     * @Route("/view/voyage/{id}", name="single_voyage")
     */

    public function singleVoyage($id): Response

    {
        $singleVoyage = $this->entityManager->getRepository(Voyage::class)->findBy(['id' => $id]);

        return $this->render('voyage/singleVoyage.html.twig', [

            'singleVoyage' => $singleVoyage,
        ]);
    }
}
