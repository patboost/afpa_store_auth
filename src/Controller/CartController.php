<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $prodRepo): Response
    {
        $panier = $session->get('panier', []);

        // Récupérer les données produits pour affichage
        // *********************************************
        $panierData = [];
        foreach($panier as $prodId => $quantity) {
            $panierData[] = [
                'product' => $prodRepo->find($prodId),
                'qty' => $quantity
            ];
        }
        //dd($panierData);

        $panierTotal = 0;
        foreach($panierData as $item) {
            $panierTotal += $item['product']->getPrice() * $item['qty'];
        }

        return $this->render('cart/index.html.twig', [
            'items' => $panierData,
            'total' => $panierTotal,
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_cart")
     */
    // V2 avec sessionInterface
    public function add($id, SessionInterface $session) {

        // Remplacer $session par $cartService dans les params
        $panier = $session->get('panier', []); // Récupérer panier existant sinon créer un panier (tableau) vide

        // A remplacer par 
        // cartService->add($id)
        if(!empty($panier[$id])) {
            $panier[$id]++;
        }
        else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);
        //dd($session->get('panier'));
        // .........

        return $this->redirectToRoute('store');

    }
    // V1 avec requête
    // public function add($id, Request $req) {
    //     $session = $req->getSession();
    //     $panier = $session->get('panier', []); // Récupérer panier existant sinon créer un panier (tzbleau) vide

    //     if(!empty($panier[$id])) {
    //         $panier[$id]++;
    //     }
    //     else {
    //         $panier[$id] = 1;
    //     }

    //     $session->set('panier', $panier);

    //     dd($session->get('panier'));

    // }

    /**
     * @Route("/cart/delete/{id}", name="delete_cart")
     */
    public function delete($id, SessionInterface $session) {
        
        // Remplacer $session par $cartService dans les params
        
        // A remplacer par 
        // cartService->delete($id)
        $panier = $session->get('panier', []);

        if(!empty($panier)){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);
        // .....................................
        
        return $this->redirectToRoute('cart');
    }
}
