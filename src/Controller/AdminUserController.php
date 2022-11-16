<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/administration/utilisateurs", name="app_admin_users")
     */
    public function index(UserRepository $repository): Response
    {
        $loggedInUser = $this->getUser();
        $users = $repository->findAll();
        return $this->render('admin_user/index.html.twig', [
            'users' => $users,
            'loggedInUser' => $loggedInUser
        ]);
    }
}
