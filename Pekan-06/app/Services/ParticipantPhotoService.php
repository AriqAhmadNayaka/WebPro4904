<?php

declare(strict_types=1);

namespace App\Services;

// Service ini menangani upload dan hapus file foto peserta.
final class ParticipantPhotoService
{
    // Menerima folder upload sebagai lokasi penyimpanan file.
    public function __construct(private string $uploadDirectory)
    {
    }

    // Mengunggah file foto peserta dan mengembalikan status hasil upload.
    public function upload(array $file, bool $required = true): array
    {
        // Jika file tidak dipilih, kembalikan error saat upload wajib.
        if (($file["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $required ? [false, "Foto peserta wajib diupload.", ""] : [true, "", ""];
        }

        // Hentikan proses jika upload file gagal.
        if (($file["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return [false, "Upload gambar gagal.", ""];
        }

        // Pastikan file yang diupload benar-benar berupa gambar.
        if (@getimagesize($file["tmp_name"]) === false) {
            return [false, "File yang diupload harus berupa gambar.", ""];
        }

        // Batasi ekstensi file gambar yang boleh diupload.
        $validExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
        $extension = strtolower(pathinfo($file["name"] ?? "", PATHINFO_EXTENSION));
        if (!in_array($extension, $validExtensions, true)) {
            return [false, "Format gambar harus jpg, jpeg, png, gif, atau webp.", ""];
        }

        // Batasi ukuran file maksimal 2 MB.
        if (($file["size"] ?? 0) > 2 * 1024 * 1024) {
            return [false, "Ukuran gambar maksimal 2 MB.", ""];
        }

        // Buat folder upload jika belum tersedia.
        if (!is_dir($this->uploadDirectory) && !mkdir($this->uploadDirectory, 0777, true)) {
            return [false, "Folder uploads tidak bisa dibuat.", ""];
        }

        // Buat nama file unik agar tidak bentrok dengan file lain.
        $newFilename = uniqid("peserta_", true) . "." . $extension;
        $targetPath = $this->uploadDirectory . DIRECTORY_SEPARATOR . $newFilename;

        // Pindahkan file dari folder sementara ke folder upload.
        if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
            return [false, "Gagal memindahkan gambar ke folder uploads.", ""];
        }

        // Kembalikan status sukses beserta nama file baru.
        return [true, "", $newFilename];
    }

    // Menghapus file foto peserta jika file-nya ada.
    public function delete(string $filename): void
    {
        if ($filename === "") {
            return;
        }

        $filePath = $this->uploadDirectory . DIRECTORY_SEPARATOR . $filename;
        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}
