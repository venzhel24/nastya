<?php

namespace App\Controller;

use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadS3Controller extends AbstractController
{
    private UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    #[Route('/upload', name: 'upload_file', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'Файл не был загружен.'], 400);
        }

        try {
            $fileName = $this->uploadService->uploadFile($file);
            $fileUrl = $this->uploadService->getFileUrl($fileName);

            return new JsonResponse([
                'message' => 'Файл успешно загружен.',
                'url' => $fileUrl,
            ]);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/upload-form', name: 'upload_form', methods: ['GET'])]
    public function showForm(): Response
    {
        return $this->render('upload/form.html.twig');
    }
}
