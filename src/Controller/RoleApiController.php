<?php

namespace App\Controller;

use App\Service\RoleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/roles')]
class RoleApiController extends AbstractController
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    #[Route('', name: 'api_add_role', methods: ['POST'])]
    public function addRole(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['role_name'])) {
            return new JsonResponse(['error' => 'Необходимо указать имя роли.'], 400);
        }

        $role = $this->roleService->addRole($data['role_name']);

        return new JsonResponse(['success' => 'Роль успешно добавлена!', 'id' => $role->getId()]);
    }

    #[Route('/{id}', name: 'api_edit_role', methods: ['PUT'])]
    public function editRole(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $roleName = $data['role_name'] ?? null;

        if (!$roleName) {
            return new JsonResponse(['error' => 'Необходимо указать имя роли.'], 400);
        }

        $role = $this->roleService->editRole($id, $roleName);
        if (!$role) {
            return new JsonResponse(['error' => 'Роль не найдена'], 404);
        }

        return new JsonResponse(['success' => 'Роль успешно обновлена!']);
    }

    #[Route('/{id}', name: 'api_delete_role', methods: ['DELETE'])]
    public function deleteRole(int $id): JsonResponse
    {
        $result = $this->roleService->deleteRole($id);
        if (!$result) {
            return new JsonResponse(['error' => 'Роль не найдена'], 404);
        }

        return new JsonResponse(['success' => 'Роль успешно удалена!']);
    }
}
