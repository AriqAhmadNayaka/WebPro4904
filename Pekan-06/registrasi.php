<?php
include "koneksi.php"; // Menghubungkan ke file koneksi untuk akses database

class UserAuth { // Kelas untuk mengelola pendaftaran pengguna
    private $db; // Variabel untuk menyimpan koneksi database
    public function __construct($connection) { // Konstruktor untuk menerima koneksi database saat objek dibuat
        $this->db = $connection; // Menyimpan koneksi database ke dalam variabel kelas
    }

    public function daftar($data) { // Fungsi untuk mendaftarkan pengguna baru, menerima data dari form
        $role     = $data['role'] ?? ""; // Mengambil peran dari data, jika tidak ada maka kosong
        $name     = trim($data['name'] ?? ""); // Mengambil nama dari data, jika tidak ada maka kosong, dan menghapus spasi di awal/akhir
        $email    = trim($data['email'] ?? ""); //  Mengambil email dari data, jika tidak ada maka kosong, dan menghapus spasi di awal/akhir
        $password = trim($data['password'] ?? ""); // Mengambil password dari data, jika tidak ada maka kosong, dan menghapus spasi di awal/akhir

        if ($role == "" || $name == "" || $email == "" || $password == "") { // Cek jika ada field yang kosong
            return ["status" => "danger", "msg" => "Semua field wajib diisi!"]; // Jika ada yang kosong, kembalikan status error dan pesan
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password menggunakan algoritma default (bcrypt) untuk keamanan
        $query = "INSERT INTO users (role, name, email, password) VALUES ('$role', '$name', '$email', '$hashed_password')"; // Query SQL untuk memasukkan data pengguna baru ke dalam tabel users

         // Eksekusi query dan cek hasilnya
        if (mysqli_query($this->db, $query)) { // Jika query berhasil, kembalikan status sukses dan pesan
            return ["status" => "success", "msg" => "Akun berhasil dibuat! Silakan login."];
        } else { // Jika query gagal, kembalikan status error dan pesan dengan detail error dari database
            return ["status" => "danger", "msg" => "Gagal: " . mysqli_error($this->db)];
        }
    }
}

$auth = new UserAuth($conn); // Membuat objek UserAuth dengan koneksi database yang sudah dibuat di koneksi.php
$message = ""; $type = ""; $role = ""; $name = ""; $email = ""; // Inisialisasi variabel untuk menyimpan pesan, tipe pesan, dan nilai form agar tidak error saat form pertama dimuat

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Cek jika form disubmit dengan metode POST
    $role = $_POST['role'] ?? ""; // Mengambil nilai role dari form, jika tidak ada maka kosong
    $name = $_POST['name'] ?? ""; // Mengambil nilai name dari form, jika tidak ada maka kosong
    $email = $_POST['email'] ?? ""; // Mengambil nilai email dari form, jika tidak ada maka kosong
    $result = $auth->daftar($_POST); // Memanggil fungsi daftar dari objek UserAuth dengan data yang dikirimkan dari form, dan menyimpan hasilnya ke dalam variabel result
    $message = $result['msg']; //   Mengambil pesan dari hasil fungsi daftar dan menyimpan ke dalam variabel message untuk ditampilkan di form
    $type = $result['status'];// Mengambil status dari hasil fungsi daftar dan menyimpan ke dalam variabel type untuk menentukan jenis alert yang akan ditampilkan di form

     // Jika pendaftaran berhasil, redirect ke halaman login setelah 2 detik
    if($type == "success") { header("refresh:2;url=login.php"); } 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LifeTrack - Buat Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; min-height: 100vh; display: flex; flex-direction: column; }
        .signin-wrapper { flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px 0; }
        .signin-card { border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); background: white; width:360px; }
        .btn-lifetrack { background: #0f766e; border: none; padding: 10px; color: white; }
        .text-lifetrack { color: #0f766e; }
        footer { background: #0f766e; color: white; text-align: center; padding: 24px; }
    </style>
</head>
<body>
<div class="signin-wrapper">
    <div class="card signin-card p-4">
        <h4 class="fw-bold text-lifetrack mb-1">Buat Akun</h4>
        <p class="text-muted mb-4">Daftar untuk mulai menggunakan LifeTrack</p>

        <?php if ($message != "") : ?>
            <div class="alert alert-<?= $type; ?> py-2 small"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Daftar sebagai</label>
                <select name="role" class="form-select">
                    <option value="">Pilih peran</option>
                    <option value="pasien" <?= $role == "pasien" ? "selected" : ""; ?>>Pasien</option>
                    <option value="tenaga_medis" <?= $role == "tenaga_medis" ? "selected" : ""; ?>>Tenaga Medis</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-lifetrack w-100 fw-bold">Buat Akun</button>
        </form>
        <div class="text-center mt-3"><small>Sudah punya akun? <a href="login.php" class="text-lifetrack fw-bold text-decoration-none">Login</a></small></div>
    </div>
</div>
<footer>LifeTrack © 2025 — Membantu Anda hidup lebih sehat</footer>
</body>
</html>