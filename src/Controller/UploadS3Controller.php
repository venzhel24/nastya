<?php

namespace App\Controller;

use League\Flysystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadS3Controller extends AbstractController
{
    private Filesystem $filesystem;

    public function __construct(
        #[Autowire(service: 'minio.storage')]
        Filesystem $minioStorage
    ) {
        $this->filesystem = $minioStorage;
    }

    #[Route('/upload', name: 'upload_file', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        // Получаем файл из запроса
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'Файл не был загружен.'], 400);
        }

        // Генерируем уникальное имя файла
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        try {
            // Сохраняем файл в MinIO
            $this->filesystem->write($fileName, file_get_contents($file->getPathname()));

            // Получаем URL файла (если MinIO настроен для публичного доступа)
            $fileUrl = $this->filesystem->publicUrl($fileName);

            // Возвращаем успешный ответ
            return new JsonResponse([
                'message' => 'Файл успешно загружен.',
                'url' => $fileUrl,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ошибка при загрузке файла: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/upload-form', name: 'upload_form', methods: ['GET'])]
    public function showForm(): Response
    {
        return $this->render('upload/form.html.twig');
    }
}