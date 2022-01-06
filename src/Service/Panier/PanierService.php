<?php

namespace App\Service\Panier;

use App\Repository\VoyageRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    public $session;
    public $voyageRepository;

    public function __construct(SessionInterface $session, VoyageRepository $voyageRepository)
    {
        $this->session = $session;
        $this->voyageRepository = $voyageRepository;

    }

    public function add(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (empty($panier[$id])):
            $panier[$id] = 1;
        else:
            $panier[$id]++;
        endif;

        $this->session->set('panier', $panier);
    }

    public function remove(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id]) && $panier[$id] !== 1):
            $panier[$id]--;
        else:
            unset($panier[$id]);  //$this->session->remove($panier[$id])
        endif;

        $this->session->set('panier', $panier);
    }

    public function delete(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])):
            unset($panier[$id]);
        endif;

        $this->session->set('panier', $panier);
    }

    public function getFullCart(): array
    {
        $panier = $this->session->get('panier', []);

        $panierDetail = [];

        foreach ($panier as $id => $indice):
            // dd($panier);
            $panierDetail[]=[
                'voyage'=>$this->voyageRepository->find($id),
                
            ];

        endforeach;
        return $panierDetail;
    }

    public function getTotal()
    {
        $panier = $this->getFullCart();
        // dd($panier, );

        $total=0;
        foreach ($panier as $item => $value):
            // dump($item, $value);
            // dd($value['product']);
            $total += $value['voyage']->getPrix();  
        endforeach;

        // dd($total);
        return $total;
    }
}