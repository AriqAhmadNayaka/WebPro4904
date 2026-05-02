<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfileFileManager
{
    private string $baseDirectory;
    private string $uploadDirectory;
    private int $maxFileSize = 2097152;
    private array $allowedExtensions = array('jpg', 'jpeg', 'png', 'webp');
    private array $allowedMimeTypes = array('image/jpeg', 'image/png', 'image/webp');

    public function __construct(string $baseDirectory)
    {
        $this->baseDirectory = rtrim($baseDirectory, DIRECTORY_SEPARATOR);
        $this->uploadDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile';
    }

    public function getDefaultPhoto(): string
    {
        return 'FOTO/profil.jpeg';
    }

    public function getDisplayPath(?string $photoPath): string
    {
        $normalizedPath = $this->normalizePath($photoPath);

        if ($normalizedPath !== '' && file_exists($this->baseDirectory . DIRECTORY_SEPARATOR . $normalizedPath)) {
            return str_replace(DIRECTORY_SEPARATOR, '/', $normalizedPath);
        }

        return $this->getDefaultPhoto();
    }

    public function upload(?array $uploadedFile, string $email, ?string $oldPhotoPath): ?string
    {
        if (!$uploadedFile || !isset($uploadedFile['error']) || $uploadedFile['error'] === UPLOAD_ERR_NO_FILE) {
            return $oldPhotoPath;
        }

        if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload foto profil gagal.');
        }

        if (($uploadedFile['size'] ?? 0) > $this->maxFileSize) {
            throw new RuntimeException('Ukuran foto profil maksimal 2 MB.');
        }

        $extension = strtolower(pathinfo((string) $uploadedFile['name'], PATHINFO_EXTENSION));
        $mimeType = mime_content_type((string) $uploadedFile['tmp_name']);

        if (!in_array($extension, $this->allowedExtensions, true) || !in_array($mimeType, $this->allowedMimeTypes, true)) {
            throw new RuntimeException('Format foto profil harus JPG, JPEG, PNG, atau WEBP.');
        }

        if (!is_dir($this->uploadDirectory) && !mkdir($this->uploadDirectory, 0777, true) && !is_dir($this->uploadDirectory)) {
            throw new RuntimeException('Folder upload tidak dapat dibuat.');
        }

        $safeFileName = 'profile_' . preg_replace('/[^a-zA-Z0-9]/', '_', $email) . '_' . time() . '.' . $extension;
        $absolutePath = $this->uploadDirectory . DIRECTORY_SEPARATOR . $safeFileName;

        if (!move_uploaded_file((string) $uploadedFile['tmp_name'], $absolutePath)) {
            throw new RuntimeException('Foto profil tidak bisa disimpan ke server.');
        }

        $this->remove($oldPhotoPath);

        return 'uploads/profile/' . $safeFileName;
    }

    public function remove(?string $photoPath): void
    {
        $normalizedPath = $this->normalizePath($photoPath);

        if ($normalizedPath === '') {
            return;
        }

        $absolutePath = $this->baseDirectory . DIRECTORY_SEPARATOR . $normalizedPath;
        $defaultPhotoPath = $this->baseDirectory . DIRECTORY_SEPARATOR . $this->normalizePath($this->getDefaultPhoto());

        if ($absolutePath !== $defaultPhotoPath && file_exists($absolutePath)) {
            unlink($absolutePath);
        }
    }

    private function normalizePath(?string $path): string
    {
        return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, trim((string) $path));
    }
}

class ProfileRepository
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function ensureSchema(): void
    {
        $requiredColumns = array(
            'nama_lengkap' => 'ALTER TABLE users ADD COLUMN nama_lengkap VARCHAR(100) NULL AFTER username',
            'no_hp' => 'ALTER TABLE users ADD COLUMN no_hp VARCHAR(20) NULL AFTER nama_lengkap',
            'tanggal_lahir' => 'ALTER TABLE users ADD COLUMN tanggal_lahir DATE NULL AFTER no_hp',
            'jenis_kelamin' => 'ALTER TABLE users ADD COLUMN jenis_kelamin VARCHAR(20) NULL AFTER tanggal_lahir',
            'alamat' => 'ALTER TABLE users ADD COLUMN alamat TEXT NULL AFTER jenis_kelamin',
            'kota' => 'ALTER TABLE users ADD COLUMN kota VARCHAR(100) NULL AFTER alamat',
            'pekerjaan' => 'ALTER TABLE users ADD COLUMN pekerjaan VARCHAR(100) NULL AFTER kota',
            'bio' => 'ALTER TABLE users ADD COLUMN bio TEXT NULL AFTER pekerjaan',
            'foto_profil' => 'ALTER TABLE users ADD COLUMN foto_profil VARCHAR(255) NULL AFTER bio',
        );

        foreach ($requiredColumns as $column => $sql) {
            $escapedColumn = mysqli_real_escape_string($this->connection, $column);
            $checkColumn = mysqli_query($this->connection, "SHOW COLUMNS FROM users LIKE '$escapedColumn'");

            if ($checkColumn && mysqli_num_rows($checkColumn) === 0) {
                mysqli_query($this->connection, $sql);
            }
        }
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');

        if (!$statement) {
            throw new RuntimeException('Query profil tidak dapat dipersiapkan.');
        }

        $statement->bind_param('s', $email);
        $statement->execute();
        $result = $statement->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        $statement->close();

        return $user ?: null;
    }

    public function usernameExistsForAnotherUser(string $username, string $email): bool
    {
        $statement = $this->connection->prepare('SELECT email FROM users WHERE username = ? AND email != ? LIMIT 1');

        if (!$statement) {
            throw new RuntimeException('Validasi username tidak dapat diproses.');
        }

        $statement->bind_param('ss', $username, $email);
        $statement->execute();
        $result = $statement->get_result();
        $exists = $result && $result->num_rows > 0;
        $statement->close();

        return $exists;
    }

    public function saveProfile(string $email, array $profileData): void
    {
        $statement = $this->connection->prepare(
            'UPDATE users SET username = ?, nama_lengkap = ?, no_hp = ?, tanggal_lahir = ?, jenis_kelamin = ?, alamat = ?, kota = ?, pekerjaan = ?, bio = ?, foto_profil = ? WHERE email = ? LIMIT 1'
        );

        if (!$statement) {
            throw new RuntimeException('Penyimpanan profil tidak dapat dipersiapkan.');
        }

        $statement->bind_param(
            'sssssssssss',
            $profileData['username'],
            $profileData['nama_lengkap'],
            $profileData['no_hp'],
            $profileData['tanggal_lahir'],
            $profileData['jenis_kelamin'],
            $profileData['alamat'],
            $profileData['kota'],
            $profileData['pekerjaan'],
            $profileData['bio'],
            $profileData['foto_profil'],
            $email
        );

        if (!$statement->execute()) {
            $error = $statement->error;
            $statement->close();
            throw new RuntimeException('Profil gagal diperbarui: ' . $error);
        }

        $statement->close();
    }

    public function deleteProfileData(string $email): void
    {
        $statement = $this->connection->prepare(
            'UPDATE users SET nama_lengkap = NULL, no_hp = NULL, tanggal_lahir = NULL, jenis_kelamin = NULL, alamat = NULL, kota = NULL, pekerjaan = NULL, bio = NULL, foto_profil = NULL WHERE email = ? LIMIT 1'
        );

        if (!$statement) {
            throw new RuntimeException('Penghapusan profil tidak dapat dipersiapkan.');
        }

        $statement->bind_param('s', $email);

        if (!$statement->execute()) {
            $error = $statement->error;
            $statement->close();
            throw new RuntimeException('Data profil gagal dihapus: ' . $error);
        }

        $statement->close();
    }

    public function hasSavedProfileData(array $profile): bool
    {
        $profileFields = array(
            'nama_lengkap',
            'no_hp',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat',
            'kota',
            'pekerjaan',
            'bio',
            'foto_profil',
        );

        foreach ($profileFields as $field) {
            if (!empty($profile[$field])) {
                return true;
            }
        }

        return false;
    }
}

class ProfileSessionManager
{
    public function requireLogin(): void
    {
        if (!isset($_SESSION['email'])) {
            redirect('login');
        }
    }

    public function syncFromProfile(array $profile, ProfileFileManager $fileManager): void
    {
        $_SESSION['email'] = $profile['email'];
        $_SESSION['username'] = !empty($profile['username']) ? $profile['username'] : $profile['email'];
        $_SESSION['nama_lengkap'] = !empty($profile['nama_lengkap']) ? $profile['nama_lengkap'] : $_SESSION['username'];
        $_SESSION['foto_profil'] = $fileManager->getDisplayPath($profile['foto_profil'] ?? '');
    }

    public function pullFlash(string $key): string
    {
        $message = isset($_SESSION[$key]) ? (string) $_SESSION[$key] : '';
        unset($_SESSION[$key]);

        return $message;
    }

    public function flash(string $key, string $message): void
    {
        $_SESSION[$key] = $message;
    }
}

class UserAuthService
{
    private mysqli $connection;
    private ProfileRepository $profileRepository;
    private ProfileSessionManager $sessionManager;
    private ProfileFileManager $fileManager;

    public function __construct(
        mysqli $connection,
        ProfileRepository $profileRepository,
        ProfileSessionManager $sessionManager,
        ProfileFileManager $fileManager
    ) {
        $this->connection = $connection;
        $this->profileRepository = $profileRepository;
        $this->sessionManager = $sessionManager;
        $this->fileManager = $fileManager;
    }

    public function login(string $email, string $password): bool
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');

        if (!$statement) {
            throw new RuntimeException('Proses login tidak dapat dipersiapkan.');
        }

        $statement->bind_param('s', $email);
        $statement->execute();
        $result = $statement->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        $statement->close();

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        $this->sessionManager->syncFromProfile($user, $this->fileManager);
        return true;
    }

    public function register(string $name, string $email, string $password): void
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $statement = $this->connection->prepare(
            'INSERT INTO users (username, nama_lengkap, email, password) VALUES (?, ?, ?, ?)'
        );

        if (!$statement) {
            throw new RuntimeException('Registrasi tidak dapat dipersiapkan.');
        }

        $statement->bind_param('ssss', $name, $name, $email, $hashedPassword);

        if (!$statement->execute()) {
            $error = $statement->error;
            $statement->close();
            throw new RuntimeException('Registrasi gagal: ' . $error);
        }

        $statement->close();
    }
}

class ProfileService
{
    private ProfileRepository $repository;
    private ProfileFileManager $fileManager;
    private ProfileSessionManager $sessionManager;

    public function __construct(
        ProfileRepository $repository,
        ProfileFileManager $fileManager,
        ProfileSessionManager $sessionManager
    ) {
        $this->repository = $repository;
        $this->fileManager = $fileManager;
        $this->sessionManager = $sessionManager;
    }

    public function boot(): array
    {
        $this->sessionManager->requireLogin();
        $this->repository->ensureSchema();

        $user = $this->repository->findByEmail((string) $_SESSION['email']);

        if (!$user) {
            session_destroy();
            redirect('login');
        }

        $this->sessionManager->syncFromProfile($user, $this->fileManager);

        return $user;
    }

    public function save(string $email, array $postData, ?array $uploadedFile, array $existingUser): string
    {
        $hadProfile = $this->repository->hasSavedProfileData($existingUser);
        $profileData = $this->sanitizeProfileData($postData);

        if ($profileData['username'] === '') {
            throw new RuntimeException('Username wajib diisi.');
        }

        if ($this->repository->usernameExistsForAnotherUser($profileData['username'], $email)) {
            throw new RuntimeException('Username sudah digunakan akun lain.');
        }

        $profileData['foto_profil'] = $this->fileManager->upload($uploadedFile, $email, $existingUser['foto_profil'] ?? null);

        $this->repository->saveProfile($email, $profileData);

        $updatedUser = $this->repository->findByEmail($email);
        if ($updatedUser) {
            $this->sessionManager->syncFromProfile($updatedUser, $this->fileManager);
        }

        return $hadProfile
            ? 'Profil berhasil diperbarui beserta file pendukungnya.'
            : 'Data profil berhasil ditambahkan beserta file pendukungnya.';
    }

    public function delete(string $email, array $existingUser): string
    {
        $this->fileManager->remove($existingUser['foto_profil'] ?? null);
        $this->repository->deleteProfileData($email);

        $updatedUser = $this->repository->findByEmail($email);
        if ($updatedUser) {
            $this->sessionManager->syncFromProfile($updatedUser, $this->fileManager);
        }

        return 'Data profil berhasil dihapus. Akun login Anda tetap aman.';
    }

    public function hasProfileData(array $profile): bool
    {
        return $this->repository->hasSavedProfileData($profile);
    }

    private function sanitizeProfileData(array $postData): array
    {
        return array(
            'username' => trim((string) ($postData['username'] ?? '')),
            'nama_lengkap' => $this->nullableValue($postData['nama_lengkap'] ?? null),
            'no_hp' => $this->nullableValue($postData['no_hp'] ?? null),
            'tanggal_lahir' => $this->nullableValue($postData['tanggal_lahir'] ?? null),
            'jenis_kelamin' => $this->nullableValue($postData['jenis_kelamin'] ?? null),
            'alamat' => $this->nullableValue($postData['alamat'] ?? null),
            'kota' => $this->nullableValue($postData['kota'] ?? null),
            'pekerjaan' => $this->nullableValue($postData['pekerjaan'] ?? null),
            'bio' => $this->nullableValue($postData['bio'] ?? null),
            'foto_profil' => null,
        );
    }

    private function nullableValue($value): ?string
    {
        $trimmed = trim((string) $value);
        return $trimmed === '' ? null : $trimmed;
    }
}
