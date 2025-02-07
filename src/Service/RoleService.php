<?php

namespace App\Service;

use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;

class RoleService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addRole(string $roleName): Group
    {
        $role = new Group();
        $role->setName($roleName);
        $this->em->persist($role);
        $this->em->flush();

        return $role;
    }

    public function editRole(int $id, string $roleName): ?Group
    {
        $role = $this->em->getRepository(Group::class)->find($id);
        if (!$role) {
            return null;
        }

        $role->setName($roleName);
        $this->em->flush();

        return $role;
    }

    public function deleteRole(int $id): bool
    {
        $role = $this->em->getRepository(Group::class)->find($id);
        if (!$role) {
            return false;
        }

        $this->em->remove($role);
        $this->em->flush();

        return true;
    }
}
