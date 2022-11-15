<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/compte/modifier-mot-de-passe", name="app_modify_password")
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = '';
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            //is password from the form equal to current password 
            if ($passwordHasher->isPasswordValid($user, $formPassword)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
                $this->entityManager->flush();
                $notification = 'Votre mot de passe a été mis à jour';
                return $this->render('account/account.html.twig', [
                    'notification' => $notification
                ]);
            } else {
                $notification = "Le mot de passe actuel que vous avez saisi n'est pas correct. Essayez de nouveau";
                return $this->render('account/modifyAccountPassword.html.twig', [
                    'form' => $form->createView(),
                    'notification' => $notification
                ]);
            }
        }

        return $this->render('account/modifyAccountPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
