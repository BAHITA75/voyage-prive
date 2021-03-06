<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Commentaire;
use App\Entity\User;
use App\Entity\Voyage;
use App\Form\EditCommandeType;
use App\Form\EditUserType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DashboardController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {   
        return $this->render('dashboard/dashboard.html.twig');
    }

    /************************************* AFFICHAGE DES MEMBRES **********************************/
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/dashboard/gestion-membre", name="membre")
     */
    public function gestionMembre(): Response
    {  
        $users = $this->entityManager->getRepository(User::class)->findAll();
        
        return $this->render('dashboard/gestionMembre.html.twig', [
           
           'users' => $users
        ]);
    }

    /********************************** AFFICHAGE DES VOYAGES **************************************/
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/dashboard/gestion-voyage", name="voyage")
     */
    public function gestionVoyage(): Response
    {
        $voyages = $this->entityManager->getRepository(Voyage::class)->findAll();
        
        return $this->render('dashboard/gestionVoyage.html.twig', [
           'voyages' => $voyages,
        ]);
    }

    /********************************** AFFICHAGE DES COMMANDES **************************************/
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/dashboard/gestion-commandes", name="commande")
     */
    public function gestionCommande(): Response
    {
        $commandes = $this->entityManager->getRepository(Commande::class)->findAll();
        
        //dd($commandes);
        
        return $this->render('dashboard/gestionCommandes.html.twig', [
           'commandes' => $commandes,
        ]);
    }

      /********************************** AFFICHAGE DES COMMENTAIRES **************************************/
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/dashboard/gestion-commentaires", name="commentary")
     */
    public function gestionCommentaires(): Response
    {
        $commentaires = $this->entityManager->getRepository(Commentaire::class)->findAll();
        
        // dd($commandes);
        
        return $this->render('dashboard/gestionAvis.html.twig', [
           'commentaires' => $commentaires,
        ]);
    }

    /************************************ AJOUTER UN USER *******************************************/
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("admin/ajouter/user", name="add_user")
     */
    public function addUser(Request $request): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success','L\'utilisateur '. $user->getLastname() .' a ??t?? ajout?? !');
            return $this->redirect($request->get('redirect') ?? '/dashboard/gestion-membre');
        }

        return $this->render('dashboard/form_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**************************************** MODOFIER UN USER *****************************************/

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/edit/user/{id}", name="edit_user")
     */
    public function editUser($id, Request $request): Response
    {

        $users = $this->entityManager->getRepository(User::class)->find($id);

        $form = $this->createForm(EditUserType::class, $users);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users->setPassword($this->passwordHasher->hashPassword($users, $users->getPassword()));
            $this->entityManager->persist($users);
            $this->entityManager->flush();

            $this->addFlash('success','L\'utilisateur N?? '. $users->getId() .' a ??t?? modifi?? !');
            return $this->redirect($request->get('redirect') ?? '/dashboard/gestion-membre');
        }
        return $this->render('dashboard/edit_user.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /****************************************** SUPRIMMER UN USER *******************************************/
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/supprimer/user/{id}", name="delete_user")
     */
    public function deleteUser(User $user): Response
    {
          $this->entityManager->remove($user);
          $this->entityManager->flush();

          $this->addFlash('success','L\'utilisateur a ??t?? supprim?? !');

          return $this->redirectToRoute('membre');
    }

    /**************************************** MODOFIER UNE COMMANDE *****************************************/

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/edit/commande/{id}", name="edit_commande")
     */
    public function editCommande(Commande $commandes, Request $request): Response
    {
  

        $form = $this->createForm(EditCommandeType::class, $commandes);
        $form->handleRequest($request);
        $commandes->getDateenregistrement(new \DateTime());
        if ($form->isSubmitted() && $form->isValid()) {

            $commandes->setDateenregistrement(new \DateTime());
            $this->entityManager->persist($commandes);
            $this->entityManager->flush();

            $this->addFlash('success','La commande N?? '. $commandes->getId() .' a ??t?? modifi?? !');
            return $this->redirect($request->get('redirect') ?? '/dashboard/gestion-commandes');
        }
        return $this->render('dashboard/edit_commande.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**************************************** SUPPRIMER UNE COMMANDE *****************************************/

     /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/supprimer/commande/{id}", name="delete_commande")
     */
    public function deleteCommande(Commande $commandes): Response
    {
          $this->entityManager->remove($commandes);
          $this->entityManager->flush();

          $this->addFlash('success','Votre commande a ??t?? supprim??e !');

          return $this->redirectToRoute('commande');
    }


  /**************************************** SUPPRIMER UN COMMENTAIRE *****************************************/

     /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/supprimer/commentaire/{id}", name="delete_commentaire")
     */
    public function deleteCommentaire(Commentaire $commentaires): Response
    {
          $this->entityManager->remove($commentaires);
          $this->entityManager->flush();

          $this->addFlash('success','Le commentaire a ??t?? supprim??e !');

          return $this->redirectToRoute('commentaire');
    }
}