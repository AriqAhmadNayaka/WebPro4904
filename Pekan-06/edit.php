<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/session_init.php";
require_once __DIR__ . "/koneksi.php";

/* ================= CEK LOGIN ================= */

if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$error = "";

/* ================= CEK ID ================= */

if (!isset($_GET["id"])) {
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET["id"]);

/* ================= AMBIL DATA ================= */

$query = mysqli_query($conn, "SELECT * FROM datauser WHERE id = $id");

if (!$query) {
    die("Query error: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($query);

if (!$user) {
    header("Location: dashboard.php");
    exit;
}

/* ================= UPDATE DATA ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = mysqli_real_escape_string($conn, $_POST["nama"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    /* ===== CEK EMAIL ===== */

    $cek = mysqli_query(
        $conn,
        "SELECT id FROM datauser 
         WHERE email='$email' 
         AND id!=$id"
    );

    if (mysqli_num_rows($cek) > 0) {

        $error = "Email sudah digunakan!";

    } else {

        $sql = "UPDATE datauser SET 
                name='$nama',
                email='$email'";

        /* ===== PASSWORD ===== */

        if (!empty($password)) {

            if (strlen($password) < 6) {

                $error = "Password minimal 6 karakter!";

            } else {

                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql .= ", password='$hash'";
            }
        }

        /* ===== FOTO ===== */

        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {

            $namaFile = $_FILES["foto"]["name"];
            $tmp = $_FILES["foto"]["tmp_name"];

            $extValid = ["jpg","jpeg","png","webp"];
            $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

            if (!in_array($ext, $extValid)) {

                $error = "Format foto harus jpg/png/webp";

            } else {

                if (!is_dir("img")) {
                    mkdir("img");
                }

                $namaBaru = uniqid() . "." . $ext;

                if (move_uploaded_file($tmp, "img/" . $namaBaru)) {

                    if (!empty($user["foto"]) && file_exists("img/" . $user["foto"])) {
                        unlink("img/" . $user["foto"]);
                    }

                    $sql .= ", foto='$namaBaru'";

                } else {

                    $error = "Upload foto gagal!";
                }
            }
        }

        $sql .= " WHERE id=$id";

        if ($error == "") {

            if (mysqli_query($conn, $sql)) {

                header("Location: dashboard.php");
                exit;

            } else {

                $error = "Gagal update: " . mysqli_error($conn);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<title>Edit User - CyberVault</title>

<style>

body{
    background:#0a0a0f;
    color:white;
    font-family:Arial;
}

.container{
    width:400px;
    margin:80px auto;
    background:#11111d;
    padding:30px;
    border-radius:10px;
    border:1px solid #00d4ff;
}

h2{
    text-align:center;
    color:#00d4ff;
}

input{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    background:#0a0a0f;
    border:1px solid #333;
    color:white;
}

button{
    width:100%;
    padding:12px;
    background:#00d4ff;
    border:none;
    font-weight:bold;
    cursor:pointer;
}

.error{
    color:red;
    margin-bottom:10px;
}

img{
    width:80px;
    height:80px;
    border-radius:50%;
    display:block;
    margin:auto;
    margin-bottom:15px;
}

.back{
    display:block;
    text-align:center;
    margin-top:15px;
    color:#aaa;
    text-decoration:none;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit User</h2>

<?php if($error!=""): ?>
<div class="error"><?= $error ?></div>
<?php endif; ?>

<img src="img/<?= !empty($user["foto"]) ? $user["foto"] : "default.png" ?>">

<form method="POST" enctype="multipart/form-data">

<label>Nama</label>
<input type="text" name="nama" value="<?= $user["name"] ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?= $user["email"] ?>" required>

<label>Password Baru</label>
<input type="password" name="password" placeholder="Kosongkan jika tidak diubah">

<label>Foto</label>
<input type="file" name="foto">

<button type="submit">Update</button>

</form>

<a href="dashboard.php" class="back">Kembali ke Dashboard</a>

</div>

</body>
</html>