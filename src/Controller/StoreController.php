<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class StoreController extends AbstractController
{
    /**
     * @Route("/store", name="store")
     */
    public function index(ProductRepository $repo): Response
    {
        $prods = $repo->findAll();
        return $this->render('store/index.html.twig', [
            'prods' => $prods,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('store/home.html.twig');
    }

    /**
     * @Route("/store/delete/{id}", name="delete_prod")
     */
    public function deleteProduct(Product $prod, EntityManagerInterface $manager) {
        try {
            // Méthode 1 de contrôle d'accès
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            // Accès autorisé => on continue
            // Sinon une AccessDeniedException est levée
            $manager->remove($prod);
            $manager->flush();
        }
        catch(AccessDeniedException $ex) {
            // Envoyer un message d'erreur pour la route 'store'
            $this->addFlash('error', "Vous n'avez pas les droits suffisants pour cette opération");
        }
        finally {
            return $this->redirectToRoute('store');
        }
    }

    /**
     * @Route ("/store/edit/{id}", name="edit_prod")
     * @Route("/store/new", name="new_prod")
     * @IsGranted("ROLE_ADMIN", statusCode=401, message="Accès refusé")
     */
    public function editProduct(Product $product=null, Request $req, EntityManagerInterface $manager){
        if(!$product) {
            $product = new Product();
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setPrice(str_replace(",", "", $product->getPrice()));
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('store');
        }

        return $this->render("store/edit_prod.html.twig", [
            'form' => $form->createView(),
            'mode' => $product->getId() != null, 
        ]);
    }
}
