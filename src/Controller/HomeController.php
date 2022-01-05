<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Voyage;
use App\Entity\Commande;
use App\Service\Panier\PanierService;
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

    /**
     * @Route("/addCart/{id}/{route}", name="addCart")
     *
     */
    public function addCart($id, PanierService $panierService, $route)
    {
      
        $panierService->add($id);
        $panierService->getFullCart();

        if ($route == 'home'):
            $this->addFlash('success', 'produit ajouté au panier');
            return $this->redirectToRoute('home');
        else:
            $this->addFlash('success', 'produit ajouté au panier');
            return $this->redirectToRoute('fullCart');
        endif;

    }

    /**
     * @Route("/deleteCart/{id}", name="deleteCart")
     *
     */
    public function deleteCart($id, PanierService $panierService)
    {
        $panierService->delete($id);
        return $this->redirectToRoute('fullCart');


    }

    /**
     * @Route("/fullCart", name="fullCart")
     * @Route("/order/{param}", name="order")
     *
     */
    public function fullCart(PanierService $panierService,  $param = null)
    {



        $fullCart = $panierService->getFullCart();

        $total=$panierService->getTotal();

        return $this->render('home/fullCart.html.twig', [
            'fullCart' => $fullCart,
            'total'=>$total


        ]);

 
   }

   /**
     *
     * @Route("/finalOrder", name="finalOrder")
     *
     */
    public function order( PanierService $panierService, EntityManagerInterface $manager)
    {



            $commandes = new Commande();
            $commandes->setDateenregistrement(new \DateTime())->setUser($this->getUser());
            $panier = $panierService->getFullCart();

//            $delivery=new Delivery();
//
//            $delivery->setOrder($order)->setStreet($request->request->get('street'));
//
//                $manager->persist($delivery);

            foreach ($panier as $item):

                $cart = new Cart();
                $manager->persist($cart);
                $panierService->delete($item['voyage']->getId());
            endforeach;
            $manager->persist($commandes);
            $manager->flush();
            $this->addFlash('success', "Merci pour votre achat");
            return $this->redirectToRoute('home');




    }
}
