<?php

namespace App\Service;

use App\Dto\LoginCheckMessage;
use App\Dto\UserRequest;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder, private readonly MessageBusInterface $bus)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordEncoder;
    }

    public function addUser(UserRequest $userRequest): void
    {
        $user = new User();
        $user->setEmail($userRequest->email);
        $user->setName($userRequest->name);
        if ($userRequest->password) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $userRequest->password));
        }

        $groupRepo = $this->em->getRepository(Group::class);
        foreach ($userRequest->groupIds as $groupId) {
            $group = $groupRepo->find($groupId);
            if ($group) {
                $user->addGroup($group);
            }
        }

        $this->em->persist($user);
        $this->em->flush();
    }

    public function editUser(int $id, UserRequest $userRequest): void
    {
        $user = $this->em->getRepository(User::class)->find($id);
        if (!$user) {
            return;
        }

        $userPass = new User();
        $userPass->setEmail($userRequest->email);
        $userPass->setName($userRequest->name);

        $user->setEmail($userRequest->email);
        $user->setName($userRequest->name);
        if ($userRequest->password) {
            $user->setPassword($this->passwordHasher->hashPassword($userPass, $userRequest->password));
        }

        $user->getGroups()->clear();
        $groupRepo = $this->em->getRepository(Group::class);
        foreach ($userRequest->groupIds as $groupId) {
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

    public function getAllUsers(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    public function getUserById(int $id): User
    {
        return $this->em->getRepository(User::class)->find($id);
    }

    public function getAllGroups(): array
    {
        return $this->em->getRepository(Group::class)->findAll();
    }

    /**
     * @throws ExceptionInterface
     */
    public function checkLogin(UserRequest $user): void
    {
        $this->bus->dispatch(new LoginCheckMessage($user->name, $user->password));
    }

    public function registerUser(User $user, string $plainPassword): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $plainPassword)
        );

        $this->em->persist($user);
        $this->em->flush();
    }
}
