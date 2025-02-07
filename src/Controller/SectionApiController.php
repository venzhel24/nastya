<?php

namespace App\Controller;

use App\Entity\CatalogSection;
use App\Service\SectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

/**
 * Контроллер для работы с API разделов каталога.
 */
#[Route('/api/sections')]
#[OA\Tag(name: "Разделы")]
class SectionApiController extends AbstractController
{
    public function __construct(private readonly SectionService $catalogSectionService) {}

    /**
     * Получить список всех разделов каталога.
     */
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        summary: "Получить список всех разделов",
        responses: [
            new OA\Response(response: 200, description: "Список разделов")
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->json($this->catalogSectionService->getAll());
    }

    /**
     * Получить информацию о конкретном разделе по ID.
     */
    #[Route('/{id}', methods: ['GET'])]
    #[OA\Get(
        summary: "Получить раздел по ID",
        responses: [
            new OA\Response(response: 200, description: "Данные раздела"),
            new OA\Response(response: 404, description: "Не найдено")
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $section = $this->catalogSectionService->getById($id);
        return $section ? $this->json($section) : $this->json(['error' => 'Not found'], 404);
    }

    /**
     * Создать новый раздел.
     */
    #[Route('', methods: ['POST'])]
    #[OA\Post(
        summary: "Создать новый раздел",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Электроника"),
                    new OA\Property(property: "parent_id", type: "integer", nullable: true, example: 1)
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Раздел создан"),
            new OA\Response(response: 400, description: "Некорректные данные")
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $section = new CatalogSection();
        $section->setName($data['name'] ?? '');

        if (!empty($data['parent_id'])) {
            $parentSection = $this->catalogSectionService->getById($data['parent_id']);
            if (!$parentSection) {
                return $this->json(['error' => 'Parent section not found'], 400);
            }
            $section->setParent($parentSection);
        }

        $this->catalogSectionService->create($section);
        return $this->json($section, 201);
    }

    /**
     * Удалить раздел по ID.
     */
    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Удалить раздел",
        responses: [
            new OA\Response(response: 204, description: "Удалено"),
            new OA\Response(response: 404, description: "Не найдено")
        ]
    )]
    public function delete(int $id): JsonResponse
    {
        $section = $this->catalogSectionService->getById($id);
        if (!$section) {
            return $this->json(['error' => 'Not found'], 404);
        }

        $this->catalogSectionService->delete($section);
        return $this->json(null, 204);
    }
}
