<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;

// Service ini menangani proses bisnis untuk fitur kelola peserta.
final class ParticipantService
{
    // Constructor menerima repository data dan service upload foto.
    public function __construct(
        private UserRepository $userRepository,
        private ParticipantPhotoService $photoService
    ) {
    }

    // Memastikan struktur tabel peserta siap digunakan.
    public function canManageParticipants(): bool
    {
        $this->userRepository->ensureFotoColumn();

        return $this->userRepository->canManageParticipants();
    }

    // Mengambil seluruh data peserta dari repository.
    public function getParticipants(): array
    {
        return $this->userRepository->getParticipants();
    }

    // Menangani request form peserta untuk tambah, edit, atau hapus.
    public function handleForm(array $post, array $files): array
    {
        // Ambil aksi form, default-nya tambah.
        $action = $post["aksi"] ?? "tambah";

        // Jika aksi hapus, langsung proses penghapusan data.
        if ($action === "hapus") {
            return $this->deleteParticipant((int)($post["id"] ?? 0), trim((string)($post["foto_lama"] ?? "")));
        }

        // Siapkan data peserta dari input form.
        $payload = [
            "id" => (int)($post["id"] ?? 0),
            "nama" => trim((string)($post["nama"] ?? "")),
            "umur" => trim((string)($post["umur"] ?? "")),
            "jenis_kelamin" => trim((string)($post["jenis_kelamin"] ?? "")),
            "pelatihan" => trim((string)($post["pelatihan"] ?? "")),
            "foto_lama" => trim((string)($post["foto_lama"] ?? "")),
        ];

        // Validasi field wajib sebelum proses simpan data.
        if ($payload["nama"] === "" || $payload["umur"] === "" || $payload["jenis_kelamin"] === "" || $payload["pelatihan"] === "") {
            return [false, "Nama, umur, jenis kelamin, dan pelatihan wajib diisi.", false];
        }

        // Cek apakah struktur tabel mendukung fitur peserta.
        if (!$this->userRepository->canManageParticipants()) {
            return [false, "Struktur tabel user belum lengkap untuk kelola peserta.", false];
        }

        // Tandai apakah proses saat ini adalah tambah data baru.
        $isCreate = $action === "tambah";
        // Jalankan proses upload foto peserta.
        [$uploadSuccess, $uploadMessage, $photoName] = $this->photoService->upload($files["foto"] ?? [], $isCreate);

        // Hentikan proses jika upload gagal.
        if (!$uploadSuccess) {
            return [false, $uploadMessage, false];
        }

        // Gunakan foto baru jika ada, atau pertahankan foto lama saat edit.
        $payload["foto"] = $photoName !== "" ? $photoName : $payload["foto_lama"];
        // Simpan data ke repository sesuai aksi tambah atau edit.
        $result = $isCreate
            ? $this->userRepository->createParticipant($payload)
            : $this->userRepository->updateParticipant($payload);

        // Hapus file baru jika penyimpanan database gagal.
        if (!$result[0] && $photoName !== "") {
            $this->photoService->delete($photoName);
        }

        // Hapus foto lama jika edit berhasil dan user mengganti fotonya.
        if ($result[0] && !$isCreate && $photoName !== "" && $payload["foto_lama"] !== "" && $payload["foto_lama"] !== $photoName) {
            $this->photoService->delete($payload["foto_lama"]);
        }

        // Kembalikan status proses, pesan, dan penanda redirect.
        return [$result[0], $result[1], $result[0]];
    }

    // Menghapus data peserta beserta foto lamanya.
    private function deleteParticipant(int $id, string $oldPhoto): array
    {
        $result = $this->userRepository->deleteParticipant($id);

        if ($result[0]) {
            $this->photoService->delete($oldPhoto);
        }

        return [$result[0], $result[1], $result[0]];
    }
}
