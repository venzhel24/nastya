<?php

namespace App\Dto;

readonly class LoginCheckMessage
{
    public function __construct(
        private string $name,
        private string $password
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
