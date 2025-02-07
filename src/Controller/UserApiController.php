<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class UserApiController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('', name: 'api_add_user', methods: ['POST'])]
    public function addUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['name']) || empty($data['password'])) {
            return new JsonResponse(['error' => 'Необходимо указать email, имя и пароль.'], 400);
        }

        $user = $this->userService->addUser($data['email'], $data['name'], $data['password']);

        return new JsonResponse(['success' => 'Пользователь успешно добавлен!', 'id' => $user->getId()]);
    }

    #[Route('/{id}', name: 'api_edit_user', methods: ['PUT'])]
    public function editUser(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['name']) || empty($data['password'])) {
            return new JsonResponse(['error' => 'Необходимо указать email, имя и пароль.'], 400);
        }

        $user = $this->userService->editUser($id, $data['email'], $data['name'], $data['password']);
        if (!$user) {
            return new JsonResponse(['error' => 'Пользователь не найден'], 404);
        }

        return new JsonResponse(['success' => 'Пользователь успешно обновлен!']);
    }

    #[Route('/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    public function deleteUser(int $id): JsonResponse
    {
        $result = $this->userService->deleteUser($id);
        if (!$result) {
            return new JsonResponse(['error' => 'Пользователь не найден'], 404);
        }

        return new JsonResponse(['success' => 'Пользователь успешно удален!']);
    }
}
