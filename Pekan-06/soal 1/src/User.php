<?php

/**
 * Class User
 * Model untuk mengelola data pada tabel 'users'.
 */
class User extends BaseModel {
    protected $table = 'users';

    /**
     * Mencari user berdasarkan alamat email.
     */
    public function findByEmail($email) {
        $result = $this->all(['email' => $email]);
        return (!empty($result)) ? $result[0] : null;
    }

    /**
     * Mendaftarkan user baru dengan hashing password otomatis.
     */
    public function register($name, $email, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);
    }
}
