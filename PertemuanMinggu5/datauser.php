<?php
session_start();

// Cek login
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "cybervault");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses tambah data
if(isset($_POST['tambah'])){
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek email sudah ada
    $cek = mysqli_query($conn, "SELECT email FROM datauser WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $sql = "INSERT INTO datauser (nama, email, password) VALUES ('$nama', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            $success = "Data user berhasil ditambahkan!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Ambil data untuk tabel
$query = "SELECT id, nama, email FROM datauser ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$success = isset($success) ? $success : "";
$error = isset($error) ? $error : "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - CyberVault</title>
    <link rel="stylesheet" href="HomePage.css">
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="datauser.php">Data User</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="table-container">
        <h2>Manajemen User</h2>

        <?php if($success != ""){ ?>
            <div style="background: rgba(40, 167, 69, 0.2); color: #90ee90; padding: 0.75rem; border-radius: 10px; margin-bottom: 1rem; text-align: center; border: 1px solid #28a745;">
                <?php echo $success; ?>
            </div>
        <?php } ?>

        <?php if($error != ""){ ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Form Tambah User -->
        <form action="" method="POST" style="margin-bottom: 2rem;">
            <div style="display: flex; gap: 1rem; align-items: flex-end;">
                <div style="flex: 1;">
                    <label for="nama" style="display: block; margin-bottom: 0.5rem; color: #e0e6ed;">Nama</label>
                    <input type="text" id="nama" name="nama" required style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 10px; color: #e0e6ed;">
                </div>
                <div style="flex: 1;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; color: #e0e6ed;">Email</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 10px; color: #e0e6ed;">
                </div>
                <div style="flex: 1;">
                    <label for="password" style="display: block; margin-bottom: 0.5rem; color: #e0e6ed;">Password</label>
                    <input type="password" id="password" name="password" required style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 10px; color: #e0e6ed;">
                </div>
                <button type="submit" name="tambah" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #00d4ff, #0099cc); border: none; border-radius: 10px; color: #0a0a0f; font-weight: 600; cursor: pointer;">Tambah</button>
            </div>
        </form>

        <!-- Tabel Data User -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) == 0){ ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #8892b0;">Belum ada data user</td>
                    </tr>
                <?php } else { ?>
                    <?php while($user = mysqli_fetch_assoc($result)){ ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['nama']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn-action btn-edit">Edit</a>
                                <a href="hapus.php?id=<?php echo $user['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>