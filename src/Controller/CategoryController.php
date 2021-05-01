<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(CategoryRepository $repo): Response
    {
        $cats = $repo->findAll();
        return $this->render('category/index.html.twig', [
            'cats' => $cats,
        ]);
    }

/**
     * @Route("/category/delete/{id}", name="delete_cat")
     */
    public function deleteCategory(Category $cat, EntityManagerInterface $manager) {
        $manager->remove($cat);
        $manager->flush();
        return $this->redirectToRoute('category');
    }

    /**
     * @Route ("/category/edit/{id}", name="edit_cat")
     * @Route("/category/new", name="new_cat")
     */
    public function editCategory(Category $category=null, Request $req, EntityManagerInterface $manager){
        if(!$category) {
            $category = new Category();
        }

        $form = $this->createFormBuilder($category)
            ->add('name')
            ->getForm();

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('category');
        }

        return $this->render('category/edit_cat.html.twig', [
            'form' => $form->createView(),
            'mode' => $category->getId() != null, 
        ]);
    }

    // Pour ne pas être en conflit avec 
    //la route "/category/{id}"
    /**
     * @Route("/category/{id}", name="detail_cat")
     */
    public function detailCategory(Category $category)
    {
        return $this->render('category/show_products.html.twig', [
            'cat' => $category
        ]);
    }
}
