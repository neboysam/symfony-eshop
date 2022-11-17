<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCategoryController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/administration/categories", name="app_admin_categories")
     */
    public function index(): Response
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        return $this->render('admin_category/admin_category.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/administration/categories/ajouter", name="app_admin_add_category")
     */
    public function add(Request $request): Response
    {
        $message1 = 'Ajouter une categorie';
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin_category/admin_category_manage.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
            'message1' => $message1
        ]);
    }

    /**
     * @Route("/administration/categories/modifier/{id}", name="app_admin_update_category")
     */
    public function update($id, Request $request): Response
    {
        $message2 = 'Modifier la categorie';
        $category = $this->entityManager->getRepository(Category::class)->findOneById($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin_category/admin_category_manage.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
            'message2' => $message2
        ]);
    }

    /**
     * @Route("/administration/categories/supprimer/{id}", name="app_admin_remove_category")
     */
    public function remove($id): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->findOneById($id);
        $this->entityManager->remove($category);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_admin_categories');
    }
}
