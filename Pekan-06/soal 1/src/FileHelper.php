<?php

/**
 * Class FileHelper
 * Utilitas untuk mengelola operasi file (upload dan delete).
 */
class FileHelper {
    private static $uploadPath = 'uploads/';
    private static $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];

    /**
     * Menangani upload file tunggal ke server.
     * @return array Status dan nama file atau pesan error.
     */
    public static function upload($fileData) {
        // Cek jika tidak ada file yang dipilih (error code 4)
        if ($fileData['error'] === 4) {
            return ['status' => 'error', 'msg' => 'Pilih file terlebih dahulu!'];
        }

        $fileName = $fileData['name'];
        $fileSize = $fileData['size'];
        $tmpName = $fileData['tmp_name'];

        $ekstensiFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validasi ekstensi
        if (!in_array($ekstensiFile, self::$allowedExtensions)) {
            return ['status' => 'error', 'msg' => 'Format file tidak didukung! (Hanya JPG, PNG, PDF, DOCX)'];
        }

        // Validasi ukuran (Maks 2MB)
        if ($fileSize > 2000000) {
            return ['status' => 'error', 'msg' => 'Ukuran file terlalu besar! (Maksimal 2MB)'];
        }

        // Generate nama unik
        $namaFileBaru = uniqid() . '.' . $ekstensiFile;

        // Buat folder jika belum ada
        if (!is_dir(self::$uploadPath)) {
            mkdir(self::$uploadPath, 0777, true);
        }

        // Pindahkan file dari temp ke folder tujuan
        if (move_uploaded_file($tmpName, self::$uploadPath . $namaFileBaru)) {
            return ['status' => 'success', 'filename' => $namaFileBaru];
        }

        return ['status' => 'error', 'msg' => 'Gagal mengupload file ke server.'];
    }

    /**
     * Menghapus file fisik dari server.
     */
    public static function delete($fileName) {
        $filePath = self::$uploadPath . $fileName;
        if (!empty($fileName) && file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Menangani proses pembaruan file (upload baru dan hapus yang lama).
     */
    public static function handleUpdate($fileInput, $oldFileName, $defaultFile = 'default.png') {
        if ($fileInput['error'] !== 4) {
            $uploadResult = self::upload($fileInput);
            if ($uploadResult['status'] === 'success') {
                // Hapus file lama jika ada dan bukan file default
                if ($oldFileName !== $defaultFile && !empty($oldFileName)) {
                    self::delete($oldFileName);
                }
                return ['status' => 'success', 'filename' => $uploadResult['filename']];
            } else {
                return ['status' => 'error', 'msg' => $uploadResult['msg']];
            }
        }
        // Jika tidak ada upload baru, gunakan file lama
        return ['status' => 'no_file', 'filename' => $oldFileName];
    }
}
