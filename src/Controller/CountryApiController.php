<?php

namespace App\Controller;

use App\Service\CountryService;
use App\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/countries')]
#[OA\Tag(name: "Страны")]
class CountryApiController extends AbstractController
{
    public function __construct(private readonly CountryService $countryService) {}

    #[Route('', methods: ['GET'])]
    #[OA\Get(
        summary: "Получить список всех стран",
        responses: [
            new OA\Response(response: 200, description: "Список стран")
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->json($this->countryService->getAll());
    }

    #[Route('/{id}', methods: ['GET'])]
    #[OA\Get(
        summary: "Получить страну по ID",
        responses: [
            new OA\Response(response: 200, description: "Данные страны"),
            new OA\Response(response: 404, description: "Не найдено")
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $country = $this->countryService->getById($id);
        return $country ? $this->json($country) : $this->json(['error' => 'Not found'], 404);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        summary: "Создать новую страну",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "France")
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Страна создана")
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $country = new Country();
        $country->setName($data['name'] ?? '');

        $this->countryService->create($country);
        return $this->json($country, 201);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Удалить страну",
        responses: [
            new OA\Response(response: 204, description: "Удалено"),
            new OA\Response(response: 404, description: "Не найдено")
        ]
    )]
    public function delete(int $id): JsonResponse
    {
        $country = $this->countryService->getById($id);
        if (!$country) {
            return $this->json(['error' => 'Not found'], 404);
        }

        $this->countryService->delete($country);
        return $this->json(null, 204);
    }
}
