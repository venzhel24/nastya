<?php

namespace App\Controller;

use App\Dto\UserRequest;
use App\Service\UserService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
#[OA\Tag(name: "Пользователи")]
class UserApiController extends AbstractController
{
    public function __construct(private readonly UserService $userService) {}

    #[Route('', name: 'api_add_user', methods: ['POST'])]
    #[OA\Post(
        summary: 'Добавить нового пользователя',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'password', type: 'string', example: 'strongpassword123'),
                    new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2])
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Пользователь добавлен', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'string'),
                    new OA\Property(property: 'id', type: 'integer')
                ]
            )),
            new OA\Response(response: 400, description: 'Ошибка запроса')
        ]
    )]
    public function addUser(Request $request): JsonResponse
    {
        $dto = UserRequest::fromRequest($request);
        $user = $this->userService->addUser($dto);

        return $this->json(['success' => 'Пользователь добавлен!', 'id' => $user->getId()]);
    }

    #[Route('/{id}', name: 'api_edit_user', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Редактировать пользователя',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'updated@example.com'),
                    new OA\Property(property: 'name', type: 'string', example: 'Updated Name'),
                    new OA\Property(property: 'password', type: 'string', nullable: true, example: 'newpassword123'),
                    new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'integer'), example: [2, 3])
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Пользователь обновлен', content: new OA\JsonContent(
                properties: [new OA\Property(property: 'success', type: 'string')]
            )),
            new OA\Response(response: 404, description: 'Пользователь не найден')
        ]
    )]
    public function editUser(int $id, Request $request): JsonResponse
    {
        $dto = UserRequest::fromRequest($request);
        $this->userService->editUser($id, $dto);

        return $this->json(['success' => 'Пользователь обновлен!']);
    }

    #[Route('/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Удалить пользователя',
        responses: [
            new OA\Response(response: 200, description: 'Пользователь удален', content: new OA\JsonContent(
                properties: [new OA\Property(property: 'success', type: 'string')]
            )),
            new OA\Response(response: 404, description: 'Пользователь не найден')
        ]
    )]
    public function deleteUser(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);
        return $this->json(['success' => 'Пользователь удален!']);
    }
}
