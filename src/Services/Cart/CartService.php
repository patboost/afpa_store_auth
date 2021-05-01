<?php

namespace App\Services\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;
    protected $prodRepo;

    public function __construct(SessionInterface $session, ProductRepository $repo)
    {
        $this->session = $session;
        $this->prodRepo = $repo;
    }

    public function add(int $id){
        $panier = $this->session->get('panier', []); // Récupérer panier existant sinon créer un panier (tzbleau) vide

        if(!empty($panier[$id])) {
            $panier[$id]++;
        }
        else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    public function delete(int $id) {
        $panier = $this->session->get('panier', []);

        if(!empty($panier)){
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }

    public function getFullCart() : array {
        $panier = $this->session->get('panier', []);

        // Récupérer les données produits pour affichage
        // *********************************************
        $panierData = [];
        foreach($panier as $prodId => $quantity) {
            $panierData[] = [
                'product' => $this->prodRepo->find($prodId),
                'qty' => $quantity
            ];
        }
        return $panierData;
    }

    public function getTotalCart() : float {
        $panierTotal = 0;
        foreach($this->getFullCart() as $item) {
            $panierTotal += $item['product']->getPrice() * $item['qty'];
        }

        return $panierTotal;
    }
}