<?php

namespace App\Controller;

use App\Entity\Group;
use App\Service\RoleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RoleController extends AbstractController
{
    private RoleService $roleService;
    private EntityManagerInterface $em;

    public function __construct(RoleService $roleService, EntityManagerInterface $em)
    {
        $this->roleService = $roleService;
        $this->em = $em;
    }

    #[Route('/admin/roles', name: 'admin_roles')]
    public function editRoles(AuthorizationCheckerInterface $authChecker): Response
    {
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Доступ запрещен');
        }

        $roles = $this->em->getRepository(Group::class)->findAll();

        return $this->render('admin/roles/roles.html.twig', [
            'roles' => $roles,
        ]);
    }

    #[Route('/admin/roles/add', name: 'add_role', methods: ['POST'])]
    public function addRole(Request $request): Response
    {
        $roleName = $request->request->get('role_name');
        $this->roleService->addRole($roleName);

        return $this->redirectToRoute('admin_roles');
    }

    #[Route('/admin/roles/edit/{id}', name: 'edit_role', methods: ['GET', 'POST'])]
    public function editRole(int $id, Request $request): Response
    {
        $roleName = $request->request->get('role_name');
        if ($request->isMethod('POST')) {
            $this->roleService->editRole($id, $roleName);
            return $this->redirectToRoute('admin_roles');
        }

        $role = $this->em->getRepository(Group::class)->find($id);
        if (!$role) {
            throw $this->createNotFoundException('Роль не найдена');
        }

        return $this->render('admin/roles/edit_role.html.twig', [
            'role' => $role,
        ]);
    }

    #[Route('/admin/roles/delete/{id}', name: 'delete_role', methods: ['POST'])]
    public function deleteRole(int $id): Response
    {
        $this->roleService->deleteRole($id);
        return $this->redirectToRoute('admin_roles');
    }
}
