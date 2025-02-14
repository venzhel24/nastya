<?php

namespace App\MessageHandler;

use App\Dto\NewCatalogItemMessage;
use App\Repository\UserRepository;
use App\Service\CatalogService;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class NewCatalogItemHandler
{
    public function __construct(
        private UserRepository  $userRepository,
        private MailerInterface $mailer,
        private CatalogService  $catalogService,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(NewCatalogItemMessage $message): void
    {
        sleep(3);
        $item = $this->catalogService->getItemById($message->getItemId());

        if (!$item) {
            return;
        }

        $admins = $this->userRepository->findByRole('ROLE_ADMIN');

        foreach ($admins as $admin) {
            $email = (new TemplatedEmail())
                ->from('no-reply@example.com')
                ->to($admin->getEmail())
                ->subject('Новый элемент каталога')
                ->htmlTemplate('emails/new_catalog_item.html.twig')
                ->context(['item' => $item]);

            $this->mailer->send($email);

            $this->logger->info('Письмо отправлено, id созданного товара: ' . $message->getItemId());
        }
    }
}
