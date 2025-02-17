<?php

namespace App\MessageHandler;

use App\Dto\ClearCacheMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Process\Process;

#[AsMessageHandler]
readonly class ClearCacheHandler
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function __invoke(ClearCacheMessage $message): void
    {
        $this->logger->info('Очистка кеша запущена...');

        sleep(5);

        $process = new Process(['php', 'bin/console', 'cache:pool:clear', 'cache.app']);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->logger->info('Ошибка при очистке кеша: ' . $process->getErrorOutput());
        } else {
            $this->logger->info('Кеш очищен.');
        }
    }
}
