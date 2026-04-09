<?php

require_once __DIR__ . '/AppConfig.php';

class FileUploader
{
    private string $uploadDirectory;
    private array $allowedExtensions;

    public function __construct()
    {
        $this->uploadDirectory = AppConfig::UPLOAD_DIR;
        $this->allowedExtensions = AppConfig::ALLOWED_EXTENSIONS;

        if (!is_dir($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0777, true);
        }
    }

    public function upload(array $file): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ['success' => true, 'filename' => ''];
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Upload file gagal diproses.'];
        }

        $originalName = $file['name'] ?? '';
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $this->allowedExtensions, true)) {
            return ['success' => false, 'message' => 'Format file tidak didukung.'];
        }

        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '-', pathinfo($originalName, PATHINFO_FILENAME));
        $finalName = date('YmdHis') . '-' . $safeName . '.' . $extension;
        $target = $this->uploadDirectory . $finalName;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            return ['success' => false, 'message' => 'File gagal disimpan ke folder upload.'];
        }

        return ['success' => true, 'filename' => $finalName];
    }

    public function delete(string $filename): void
    {
        if ($filename !== '' && file_exists($this->uploadDirectory . $filename)) {
            unlink($this->uploadDirectory . $filename);
        }
    }
}
