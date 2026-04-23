<?php
include 'koneksi.php';
$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if (isset($_POST['update'])) {
    $n = $_POST['nama']; $e = $_POST['email'];
    $conn->query("UPDATE users SET nama='$n', email='$e' WHERE id=$id");
    header("Location: CRUD_event.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User - WeBandoo+</title>
    <style>
        body { background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif; }
        .card { background: white; padding: 40px; border-radius: 20px; width: 350px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; }
        h2 { color: #2196F3; margin-bottom: 25px; }
        input { width: 100%; padding: 15px; margin-bottom: 15px; border-radius: 10px; border: 1px solid #ddd; box-sizing: border-box; }
        button { width: 100%; padding: 15px; background: #2196F3; color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Edit Akun</h2>
        <form method="POST">
            <input type="text" name="nama" value="<?= $user['nama'] ?>" required>
            <input type="email" name="email" value="<?= $user['email'] ?>" required>
            <button type="submit" name="update">Perbarui Data</button>
        </form>
        <a href="index.php" style="display:block; margin-top:20px; color:#999; text-decoration:none;">Batal</a>
    </div>
</body>
</html>