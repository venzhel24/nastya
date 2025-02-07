<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FileUploader
{
    private string $uploadDirectory;
    private Filesystem $filesystem;

    public function __construct(string $uploadDirectory)
    {
        $this->uploadDirectory = $uploadDirectory;
        $this->filesystem = new Filesystem();
    }

    public function uploadFile(UploadedFile $file): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII', $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $uploadDirectory = $this->uploadDirectory;

        try {
            $file->move($uploadDirectory, $newFilename);
        } catch (IOExceptionInterface $exception) {
            return null;
        }

        return '/upload/' . $newFilename;
    }
}
