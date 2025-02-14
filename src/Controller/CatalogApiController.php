<?php

namespace App\Controller;

use App\Entity\CatalogItem;
use App\Enum\CatalogType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;

#[Route('/api/catalog')]
#[OA\Tag(name: "Каталог")]
class CatalogApiController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', methods: ['GET'])]
    #[OA\Get(summary: "Получить список товаров", responses: [new OA\Response(response: 200, description: "Список товаров")])]
    public function index(): Response
    {
        $catalogItems = $this->entityManager->getRepository(CatalogItem::class)->findAll();
        return $this->json($catalogItems);
    }

    #[Route('/{id}', methods: ['GET'])]
    #[OA\Get(summary: "Получить товар по ID", responses: [new OA\Response(response: 200, description: "Информация о товаре")])]
    public function show(CatalogItem $catalogItem): Response
    {
        return $this->json($catalogItem);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        summary: "Создать новый товар",
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "name", type: "string", example: "Товар 1"),
                        new OA\Property(property: "description", type: "string", example: "Описание товара"),
                        new OA\Property(property: "type", type: "string", example: "electronics"),
                        new OA\Property(property: "advantages", type: "array", items: new OA\Items(type: "string")),
                        new OA\Property(property: "publishedAt", type: "string", format: "date-time", example: "2024-01-01T12:00:00Z"),
                        new OA\Property(property: "photo", type: "string", example: "image.jpg")
                    ]
                )
            )
        ),
        responses: [new OA\Response(response: 201, description: "Товар создан")]
    )]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $catalogItem = new CatalogItem();
        $catalogItem->setName($data['name'] ?? '')
            ->setDescription($data['description'] ?? '')
            ->setType(CatalogType::tryFrom($data['type']) ?? throw new \InvalidArgumentException('Invalid catalog type'))
            ->setAdvantages($data['advantages'] ?? [])
            ->setPublishedAt(new DateTime($data['publishedAt'] ?? 'now'));

        $this->entityManager->persist($catalogItem);
        $this->entityManager->flush();

        return $this->json($catalogItem, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(summary: "Обновить товар", responses: [new OA\Response(response: 200, description: "Товар обновлен")])]
    public function edit(Request $request, CatalogItem $catalogItem): Response
    {
        $data = json_decode($request->getContent(), true);
        $catalogItem->setName($data['name'] ?? $catalogItem->getName())
            ->setDescription($data['description'] ?? $catalogItem->getDescription())
            ->setType($data['type'] ?? $catalogItem->getType())
            ->setAdvantages($data['advantages'] ?? $catalogItem->getAdvantages())
            ->setPublishedAt(new DateTime($data['publishedAt'] ?? $catalogItem->getPublishedAt()->format('Y-m-d H:i:s')));

        $this->entityManager->flush();
        return $this->json($catalogItem);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(summary: "Удалить товар", responses: [new OA\Response(response: 204, description: "Товар удален")])]
    public function delete(CatalogItem $catalogItem): Response
    {
        $this->entityManager->remove($catalogItem);
        $this->entityManager->flush();
        return $this->json(null, 204);
    }
}
