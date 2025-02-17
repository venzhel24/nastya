<?php

namespace App\Controller;

use App\Dto\UserRequest;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function index(): Response
    {
        $users = $this->userService->getAllUsers();
        $groups = $this->userService->getAllGroups();

        return $this->render('admin/users/users.html.twig', [
            'users' => $users,
            'groups' => $groups,
        ]);
    }

    #[Route('/admin/users/add', name: 'admin_add_user', methods: ['POST'])]
    public function add(Request $request): Response
    {
        $userRequest = UserRequest::fromRequest($request);

        try {
            $this->userService->addUser($userRequest);
            $this->addFlash('success', 'Пользователь успешно добавлен!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Ошибка при добавлении пользователя: ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/users/edit/{id}', name: 'admin_edit_user', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        try {
            if ($request->isMethod('POST')) {
                $userRequest = UserRequest::fromRequest($request);
                $this->userService->editUser($id, $userRequest);
                $this->addFlash('success', 'Пользователь успешно обновлён!');
                return $this->redirectToRoute('admin_users');
            }

            $user = $this->userService->getUserById($id);

            $groups = $this->userService->getAllGroups();
            return $this->render('admin/users/edit_user.html.twig', [
                'user' => $user,
                'groups' => $groups
            ]);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Ошибка: ' . $e->getMessage());
            return $this->redirectToRoute('admin_users');
        }
    }

    #[Route('/admin/users/delete/{id}', name: 'admin_delete_user', methods: ['POST'])]
    public function delete(int $id): Response
    {
        try {
            if ($this->userService->deleteUser($id)) {
                $this->addFlash('success', 'Пользователь успешно удален!');
            } else {
                $this->addFlash('error', 'Пользователь не найден');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Ошибка при удалении пользователя: ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();

        return $this->render('auth/user.html.twig', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }
}
