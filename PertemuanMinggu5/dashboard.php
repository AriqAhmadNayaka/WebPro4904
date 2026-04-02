<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_init.php";
require_once "koneksi.php";
global $conn;

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";
$role = $_SESSION["role"];
$session_email = $_SESSION["email"];

// --- LOGIKA UTAMA: SIMPAN DATA & UPLOAD FILE ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_action"])) {
    
    $nama = mysqli_real_escape_string($conn, trim($_POST["nama"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    
    // Inisialisasi variabel file
    $namaFile = $_FILES['foto']['name'];
    $tmpName = $_FILES['foto']['tmp_name'];
    $errorFile = $_FILES['foto']['error'];

    if ($errorFile === 4) {
        $error = "Silakan pilih file foto terlebih dahulu!";
    } else {
        $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ekstensiFile, $ekstensiValid)) {
            $error = "Format file tidak didukung (Gunakan JPG/PNG)!";
        } else {
            // Generate nama unik dan pindahkan ke folder img/
            $namaFileBaru = uniqid() . '.' . $ekstensiFile;
            
            if (move_uploaded_file($tmpName, 'img/' . $namaFileBaru)) {
                
                if ($role === 'admin') {
                    // LOGIKA ADMIN: Tambah User Baru (Create)
                    $passwordInput = trim($_POST["password"]);
                    $passwordHash = password_hash($passwordInput, PASSWORD_DEFAULT);
                    
                    // Cek Email Duplikat
                    $cek = mysqli_query($conn, "SELECT email FROM datauser WHERE email = '$email'");
                    if (mysqli_num_rows($cek) > 0) {
                        $error = "Email tersebut sudah terdaftar di sistem!";
                    } else {
                        $sql = "INSERT INTO datauser (name, email, password, role, gambar) 
                                VALUES ('$nama', '$email', '$passwordHash', 'user', '$namaFileBaru')";
                        if (mysqli_query($conn, $sql)) $success = "User baru dan foto berhasil ditambahkan!";
                    }
                } else {
                    // LOGIKA USER: Update Foto Profil Sendiri (Update)
                    $sql = "UPDATE datauser SET gambar = '$namaFileBaru' WHERE email = '$session_email'";
                    if (mysqli_query($conn, $sql)) $success = "Foto profil Anda berhasil diperbarui!";
                }
            } else {
                $error = "Gagal memindahkan file. Pastikan folder 'img' sudah dibuat!";
            }
        }
    }
}

// Ambil Data untuk Tabel (Read)
$result = mysqli_query($conn, "SELECT * FROM datauser ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CyberVault - Dashboard Management</title>
    <link rel="stylesheet" href="HomePage.css">
    <style>
        body { background-color: #0a0a0f; color: #e0e6ed; font-family: sans-serif; margin: 0; }
        .main-header { background: #050508; padding: 15px 50px; border-bottom: 1px solid #00d4ff; display: flex; justify-content: space-between; align-items: center; }
        .main-content { padding: 40px; max-width: 1200px; margin: 0 auto; }
        .welcome-card { background: rgba(0, 212, 255, 0.05); border-left: 4px solid #00d4ff; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .crud-box { background: #11111d; border: 1px solid #1a1a2e; padding: 25px; border-radius: 12px; }
        
        /* Form Styling */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px; align-items: end; }
        .input-group { display: flex; flex-direction: column; gap: 5px; }
        .input-group label { color: #00d4ff; font-size: 12px; font-weight: bold; }
        .input-group input { background: #050508; border: 1px solid #2a2a40; padding: 10px; border-radius: 6px; color: #fff; }
        
        .btn-submit { background: #00d4ff; color: #000; border: none; padding: 11px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        
        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 12px; color: #00d4ff; border-bottom: 2px solid #1a1a2e; }
        td { padding: 12px; border-bottom: 1px solid #1a1a2e; font-size: 14px; }
        .user-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #00d4ff; }
        .btn-action { text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 12px; margin-right: 5px; }
        .edit { color: #00ffaa; border: 1px solid #00ffaa; }
        .hapus { color: #ff4757; border: 1px solid #ff4757; }
    </style>
</head>
<body>

    <header class="main-header">
        <div style="font-size: 20px; font-weight: bold; color: #00d4ff;">CyberVault</div>
        <a href="logout.php" style="color: #ff4757; text-decoration: none; font-size: 14px;">Logout</a>
    </header>

    <main class="main-content">
        <div class="welcome-card">
            <h2>Halo, <?= htmlspecialchars($_SESSION["nama"]); ?>!</h2>
            <p>Role Anda: <strong style="color: #00d4ff;"><?= strtoupper($role); ?></strong></p>
        </div>

        <?php if($error): ?> <div style="color: #ff4757; margin-bottom: 20px;"><?= $error; ?></div> <?php endif; ?>
        <?php if($success): ?> <div style="color: #00ffaa; margin-bottom: 20px;"><?= $success; ?></div> <?php endif; ?>

        <div class="crud-box">
            <h3 style="margin-top: 0; color: #00d4ff;">
                <?= ($role === 'admin') ? "Tambah Anggota Baru" : "Update Foto Profil Anda"; ?>
            </h3>

            <form action="" method="POST" enctype="multipart/form-data" class="form-grid">
                <?php if ($role === 'admin'): ?>
                    <div class="input-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" required>
                    </div>
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                <?php else: ?>
                    <div class="input-group" style="grid-column: span 3;">
                        <p style="color: #8892b0; margin: 0;">Gunakan form ini untuk memperbarui foto profil keamanan Anda di database.</p>
                        <input type="hidden" name="nama" value="<?= $_SESSION['nama']; ?>">
                        <input type="hidden" name="email" value="<?= $_SESSION['email']; ?>">
                    </div>
                <?php endif; ?>

                <div class="input-group">
                    <label>Pilih File Foto</label>
                    <input type="file" name="foto" required>
                </div>
                
                <button type="submit" name="submit_action" class="btn-submit">
                    <?= ($role === 'admin') ? "Simpan & Upload User" : "Update Foto Saya"; ?>
                </button>
            </form>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <img src="img/<?= $row['gambar'] ? $row['gambar'] : 'default.png'; ?>" class="user-img">
                            </td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><span style="font-size: 11px; color: #00d4ff; border: 1px solid #00d4ff; padding: 2px 6px; border-radius: 10px;"><?= strtoupper($row['role']); ?></span></td>
                            <td>
                                <?php if ($role === 'admin'): ?>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn-action edit">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-action hapus" onclick="return confirm('Hapus user?')">Hapus</a>
                                <?php else: ?>
                                    <span style="color: #444;">Read Only</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <footer class="cyber-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3 style="color: #00d4ff; margin: 0;">CyberVault</h3>
                <p style="max-width: 300px;">Platform edukasi keamanan siber terpercaya untuk melindungi data dan privasi Anda.</p>
            </div>
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p>Bandung, Indonesia<br>support@cybervault.id</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <p>Instagram<br>GitHub</p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px; font-size: 11px; color: #444;">
            &copy; 2026 CyberVault Project. All Rights Reserved.
        </div>
    </footer>

</body>
</html>