<?php

namespace App\Controller;

use DateTime;
use App\Entity\Voyage;
use App\Form\ComentType;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{

 
    /**
     * @Route("/add_comment/?voyage_id={id}", name="commentaire", methods={"GET|POST"})
     */
    public function commentaire(Voyage $voyage, Request $request, EntityManagerInterface $entityManager): Response
    {

        $commentaire = new Commentaire();
        $form = $this->createForm(ComentType::class, $commentaire)->handleRequest($request);
        // dd($commentaire);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->getData();

            $commentaire->setUser($this->getUser());
            $commentaire->setCreatedAt(new DateTime());

            $commentaire->setVoyage($voyage);

            $entityManager->persist($commentaire);
            $entityManager->flush();


            $this->addFlash('success', 'Vous avez commenté le voyage ');
            return $this->redirectToRoute('single_voyage',[
                'id'=>$voyage->getId()
            ]);
        }
        return $this->render('rendered/form_com.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
