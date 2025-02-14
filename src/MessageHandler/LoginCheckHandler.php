<?php

namespace App\MessageHandler;

use App\Dto\LoginCheckMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
readonly class LoginCheckHandler
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(LoginCheckMessage $message): void
    {
        sleep(3);

        $this->logger->info('Проверка username: ' . $message->getName());

        // В реальности тут можно было бы делать запрос к внешнему API
    }
}
