<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ManufacturerService;
use App\Entity\Manufacturer;
use OpenApi\Attributes as OA;

/**
 * Контроллер для работы с API производителей.
 */
#[Route('/api/manufacturers')]
#[OA\Tag(name: "Производители")]
class ManufacturerApiController extends AbstractController
{
    public function __construct(private ManufacturerService $manufacturerService)
    {
    }

    /**
     * Получить список всех производителей.
     */
    #[Route('', methods: ['GET'])]
    #[OA\Get(summary: "Получить список всех производителей", responses: [new OA\Response(response: 200, description: "Список производителей")])]
    public function index(): JsonResponse
    {
        return $this->json($this->manufacturerService->getAll());
    }

    /**
     * Получить информацию о конкретном производителе по ID.
     */
    #[Route('/{id}', methods: ['GET'])]
    #[OA\Get(summary: "Получить производителя по ID", responses: [new OA\Response(response: 200, description: "Данные производителя"), new OA\Response(response: 404, description: "Не найдено")])]
    public function show(int $id): JsonResponse
    {
        $manufacturer = $this->manufacturerService->getById($id);
        if (!$manufacturer) {
            return $this->json(['error' => 'Not found'], 404);
        }
        return $this->json($manufacturer);
    }

    /**
     * Создать нового производителя.
     */
    #[Route('', methods: ['POST'])]
    #[OA\Post(
        summary: "Создать нового производителя",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Apple")
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Производитель создан")
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $manufacturer = new Manufacturer();
        $manufacturer->setName($data['name'] ?? '');

        $this->manufacturerService->create($manufacturer);
        return $this->json($manufacturer, 201);
    }

    /**
     * Удалить производителя по ID.
     */
    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(summary: "Удалить производителя", responses: [new OA\Response(response: 204, description: "Удалено"), new OA\Response(response: 404, description: "Не найдено")])]
    public function delete(int $id): JsonResponse
    {
        $manufacturer = $this->manufacturerService->getById($id);
        if (!$manufacturer) {
            return $this->json(['error' => 'Not found'], 404);
        }

        $this->manufacturerService->delete($manufacturer);
        return $this->json(null, 204);
    }
}