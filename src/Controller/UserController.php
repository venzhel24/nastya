<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;
    private EntityManagerInterface $em;

    public function __construct(UserService $userService, EntityManagerInterface $em)
    {
        $this->userService = $userService;
        $this->em = $em;
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function index(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        $groups = $this->em->getRepository(Group::class)->findAll();

        return $this->render('admin/users/users.html.twig', [
            'users' => $users,
            'groups' => $groups,
        ]);
    }

    #[Route('/admin/users/add', name: 'admin_add_user', methods: ['POST'])]
    public function add(Request $request): Response
    {
        $email = $request->request->get('email');
        $name = $request->request->get('name');
        $password = $request->request->get('password');
        $groupIds = $request->request->get('groups', []);

        if ($email && $name && $password) {
            $this->userService->addUser($email, $name, $password, $groupIds);
            $this->addFlash('success', 'Пользователь успешно добавлен!');
            return $this->redirectToRoute('admin_users');
        }

        $this->addFlash('error', 'Пожалуйста, заполните все поля.');
        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/users/edit/{id}', name: 'admin_edit_user', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        if ($request->isMethod('POST')) {
            $user->setEmail($request->request->get('email'));
            $user->setName($request->request->get('name'));

            if ($password = $request->request->get('password')) {
                $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); // Если не используешь encoder
            }

            // Обновление ролей
            $selectedRoles = $request->request->all('roles');
            $user->getGroups()->clear();

            if (!empty($selectedRoles)) {
                $groupRepo = $em->getRepository(Group::class);
                foreach ($selectedRoles as $roleId) {
                    $role = $groupRepo->find($roleId);
                    if ($role) {
                        $user->addGroup($role);
                    }
                }
            }

            $em->flush();
            $this->addFlash('success', 'Пользователь успешно обновлён!');
            return $this->redirectToRoute('admin_users');
        }

        $groups = $em->getRepository(Group::class)->findAll();

        return $this->render('admin/users/edit_user.html.twig', [
            'user' => $user,
            'groups' => $groups
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'admin_delete_user', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $result = $this->userService->deleteUser($id);
        if ($result) {
            $this->addFlash('success', 'Пользователь успешно удален!');
        } else {
            $this->addFlash('error', 'Пользователь не найден');
        }

        return $this->redirectToRoute('admin_users');
    }
}
