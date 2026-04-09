<?php

class FileUploader
{
    private string $targetDirectory;
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private int $maxSize = 2097152;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = rtrim($targetDirectory, DIRECTORY_SEPARATOR);
    }

    public function upload(array $file): ?string
    {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload file gagal diproses.');
        }

        if (($file['size'] ?? 0) > $this->maxSize) {
            throw new RuntimeException('Ukuran file maksimal 2 MB.');
        }

        $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions, true)) {
            throw new RuntimeException('Format file harus JPG, JPEG, PNG, GIF, atau WEBP.');
        }

        if (!is_dir($this->targetDirectory) && !mkdir($concurrentDirectory = $this->targetDirectory, 0777, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException('Folder upload tidak dapat dibuat.');
        }

        $fileName = uniqid('anak_', true) . '.' . $extension;
        $destination = $this->targetDirectory . DIRECTORY_SEPARATOR . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('File gagal dipindahkan ke folder upload.');
        }

        return 'uploads/' . $fileName;
    }

    public function delete(?string $relativePath, string $projectRoot): void
    {
        if (!$relativePath) {
            return;
        }

        $fullPath = rtrim($projectRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
