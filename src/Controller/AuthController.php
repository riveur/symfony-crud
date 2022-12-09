<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ) {

        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }

        if ($request->isMethod('POST')) {

            $data = (array) $request->request->getIterator();

            if ($data['password'] !== $data['password_confirm']) {
                return $this->render('auth/register.html.twig', [
                    'errors' => 'The password fields not match.'
                ]);
            }

            $user = new User();

            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);

            $user
                ->setUsername($data['username'])
                ->setPassword($hashedPassword);

            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return $this->render('auth/register.html.twig', [
                    'errors' => 'Some errors.'
                ]);
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/register.html.twig', [
            'errors' => null
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }

        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/logout', 'app_logout', methods: ['GET'])]
    public function logout()
    {
    }
}
