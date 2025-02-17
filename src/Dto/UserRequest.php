<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class UserRequest
{
    public function __construct(
        public string $email,
        public string $name,
        public ?string $password,
        public array $groupIds = []
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->request->get('email', ''),
            name: $request->request->get('name', ''),
            password: $request->request->get('password'),
            groupIds: $request->request->all('groups')
        );
    }

    public static function fromJsonRequest(Request $request): self
    {
        $data = json_decode($request->getContent(), true) ?? [];

        return new self(
            email: $data['email'] ?? '',
            name: $data['name'] ?? '',
            password: $data['password'] ?? null,
            groupIds: $data['groups'] ?? []
        );
    }
}
