<?php

namespace App\Service;

use Exception;
use League\Flysystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    private Filesystem $filesystem;

    public function __construct(Filesystem $minioStorage)
    {
        $this->filesystem = $minioStorage;
    }

    public function uploadFile(UploadedFile $file): string
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        try {
            $this->filesystem->write($fileName, file_get_contents($file->getPathname()));
            return $fileName;
        } catch (Exception $e) {
            throw new \RuntimeException('Ошибка при загрузке файла: ' . $e->getMessage());
        }
    }

    public function getFileUrl(string $fileName): string
    {
        return $this->filesystem->publicUrl($fileName);
    }
}
