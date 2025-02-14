<?php

namespace App\Command;

use App\Entity\CatalogItem;
use App\Entity\CatalogItemPropertyName;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:init-catalog-properties',
    description: 'Инициализирует таблицу catalog_item_property_names с названиями свойств.',
)]
class InitCatalogPropertiesCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $propertyNames = [
            'name' => 'Название',
            'description' => 'Описание',
            'type' => 'Тип',
            'advantages' => 'Преимущества',
            'publishedAt' => 'Дата публикации',
            'photoPath' => 'Фото',
            'manufacturers' => 'Производители',
            'country' => 'Страна',
            'section' => 'Раздел',
        ];

        $repo = $this->entityManager->getRepository(CatalogItemPropertyName::class);

        foreach ($propertyNames as $propertyKey => $displayName) {
            if (!$repo->findOneBy(['propertyKey' => $propertyKey])) {
                $property = new CatalogItemPropertyName();
                $property->setPropertyKey($propertyKey);
                $property->setDisplayName($displayName);
                $this->entityManager->persist($property);

                $output->writeln("Добавлено свойство: $propertyKey -> $displayName");
            }
        }

        $this->entityManager->flush();
        $output->writeln('Все свойства сохранены.');
        return Command::SUCCESS;
    }
}
