<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\HelperClasses\CreateSlug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminProductController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/administration/produits", name="app_admin_products")
     */
    public function index(): Response
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        return $this->render('admin_product/admin_product.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/administration/produits/ajouter", name="app_admin_add_product")
     */
    public function add(Request $request, CreateSlug $createSlug): Response
    {
        $message1 = 'Ajouter un produit';
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stringToSlug = $product->getName();
            $slug = $createSlug->createSlug($stringToSlug);
            $product->setSlug($slug);
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin_product/admin_product_manage.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'message1' => $message1
        ]);
    }

    /**
     * @Route("/administration/produits/modifier/{id}", name="app_admin_update_product")
     */
    public function update($id, Request $request): Response
    {
        $message2 = 'Modifier le produit';
        $product = $this->entityManager->getRepository(Product::class)->findOneById($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin_product/admin_product_manage.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'message2' => $message2
        ]);
    }

    /**
     * @Route("/administration/produits/supprimer/{id}", name="app_admin_remove_product")
     */
    public function remove($id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneById($id);
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_admin_products');
    }

    /**
     * @Route("/administration/produits/categorie/{categoryId}", name="app_admin_products_category", options={"expose"=true})
     */
    public function ajax($categoryId): Response
    {
        $productsData = $this->entityManager->getRepository(Product::class)->productsByCategory($categoryId);
        $productsJson = [];

        foreach($productsData as $key => $product) {
            $productsJson[$key]['id'] = $product->getId();
            $productsJson[$key]['name'] = $product->getName();
            $productsJson[$key]['slug'] = $product->getSlug();
            $productsJson[$key]['image'] = $product->getImage();
            $productsJson[$key]['subtitle'] = $product->getSubtitle();
            $productsJson[$key]['description'] = $product->getDescription();
            $productsJson[$key]['price'] = $product->getPrice();
            $productsJson[$key]['categoryName'] = $product->getCategory()->getName();
        }
        $products = new JsonResponse($productsJson);
        return $products;
    }
}
