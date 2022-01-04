<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VoyageController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/createVoyage", name="create_voyage")
     */
    public function createVoyage(Request $request, SluggerInterface $slugger): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $voyage = $form->getData();
           
            $file = $form->get('picture')->getData(); 
        
            if($file){
                    // $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                    $extension = '.' . $file->guessExtension();
                    
                    // $safeFilename = $slugger->slug($originalFilename);
                    $safeFilename = $slugger->slug($voyage->getDestination());
                  
                    $newFilename = $safeFilename . '_' . uniqid() . $extension;
                   
   
                    try {  
                        $file->move($this->getParameter('uploads_dir'), $newFilename);
                        $voyage->setPicture($newFilename);
                    } catch (FileException $exception) {
                       
                    }
            }
            $this->entityManager->persist($voyage);
            $this->entityManager->flush();

            $this->addFlash('success','Le voyage a été ajouté!');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/form_voyage.html.twig',[
            'form' => $form->createView()
        ]);
    }

    
}
