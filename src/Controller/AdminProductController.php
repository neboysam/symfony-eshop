<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        return $this->render('admin_product/admin_product.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/administration/produits/ajouter", name="app_admin_add_produit")
     */
    public function add(Request $request): Response
    {
        $message1 = 'Ajouter un produit';
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stringToSlug = $product->getSlug();
            $delimiter = '-';
            $unwantedLettersArray = ['é'=>'e', 'à' => 'a', 'ç' => 'c', 'Ç' => 'c', 'Ê' => 'e', 'ê' => 'e', 'À' => 'q', 'Ô' => 'o', 'ô' => 'o', 'È' => 'e', 'è' => 'e', 'É' => 'e']; // French letters
            $str = strtr( $stringToSlug, $unwantedLettersArray );

            $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
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
}
