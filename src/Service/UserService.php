<?php

namespace App\Service;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordEncoder;
    }

    public function addUser(string $email, string $name, string $password, array $groupIds): void
    {
        $user = new User();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $groupRepo = $this->em->getRepository(Group::class);
        foreach ($groupIds as $groupId) {
            $group = $groupRepo->find($groupId);
            if ($group) {
                $user->addGroup($group);
            }
        }

        $this->em->persist($user);
        $this->em->flush();
    }

    public function editUser(int $id, string $email, string $name, string $password, array $groupIds): void
    {
        $user = $this->em->getRepository(User::class)->find($id);
        if (!$user) {
            return;
        }

        $user->setEmail($email);
        $user->setName($name);
        if (!empty($password)) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        }

        $user->getGroups()->clear();
        $groupRepo = $this->em->getRepository(Group::class);
        foreach ($groupIds as $groupId) {
            $group = $groupRepo->find($groupId);
            if ($group) {
                $user->addGroup($group);
            }
        }

        $this->em->flush();
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->em->getRepository(User::class)->find($id);
        if (!$user) {
            return false;
        }

        $this->em->remove($user);
        $this->em->flush();
        return true;
    }
}
