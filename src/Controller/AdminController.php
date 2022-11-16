<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/administration", name="app_admin")
     */
    public function index(): Response
    {
        $userNumber = $this->entityManager->getRepository(User::class)->countUsers();
        $categoryNumber = $this->entityManager->getRepository(Category::class)->countCategories();
        return $this->render('admin/index.html.twig', [
            'userNumber' => $userNumber,
            'categoryNumber' => $categoryNumber
        ]);
    }
}
