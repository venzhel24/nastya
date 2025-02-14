<?php

namespace App\Controller;

use App\Dto\LoginCheckMessage;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/login', name: 'app_login')]
    public function login(Request $request, UserService $userService, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirect('/');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $password = $request->request->get('password');

            $userService->checkLogin($name, $password);

            return $this->render('auth/login_wait.html.twig', [
                'name' => $name,
                'password' => $password,
            ]);
        }

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserService $userService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userService->registerUser($user, $form->get('plainPassword')->getData());
            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/login_check', name: 'app_login_check')]
    public function loginCheck(): void
    {
        throw new LogicException('This method should not be reached.');
    }
}
