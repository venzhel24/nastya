<?php

namespace App\Service;

use App\Dto\NewCatalogItemMessage;
use App\Entity\CatalogItem;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

readonly class CatalogService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusInterface $bus,
        private CacheInterface $cache,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function getAllItems(): array
    {
        return $this->cache->get('catalog_all_items', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->em->getRepository(CatalogItem::class)->findAll();
        });
    }

    public function getItemById(int $id): ?CatalogItem
    {
        return $this->em->getRepository(CatalogItem::class)->find($id);
    }

    /**
     * @throws ExceptionInterface
     */
    public function saveItem(CatalogItem $item): void
    {
        $this->em->persist($item);
        $this->em->flush();

        $this->bus->dispatch(new NewCatalogItemMessage($item->getId()));
    }

    public function deleteItem(CatalogItem $item): void
    {
        $this->em->remove($item);
        $this->em->flush();
    }

    public function isValidForm(FormInterface $form): bool
    {
        return $form->isSubmitted() && $form->isValid();
    }
}
