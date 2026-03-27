<?php
//koneksi database
include("Koneksi.php");
session_start();

if(isset($_POST['tambah'])){//cek tombol tambah
echo "test";
//mengamnbil data dari form
    $email = $_POST["email"];
    $password = $_POST["password"];
    $nama = $_POST["nama"];
    $umur = $_POST["umur"];
    $notlp = $_POST["notlp"];

    //ini adalah query untuk menambahkan user
    $sql = "INSERT INTO users (email, password, nama, umur, notlp) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }

    //menjalankan, jika berhasil masuk database
    $stmt->bind_param("sssss", $email, $password, $nama, $umur, $notlp);

    if ($stmt->execute()) {
        echo "Data berhasil disimpan";
    } else {
        echo "Execute gagal: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    $data = [
        "email" => $_POST['email'],
        "password" => $_POST['password'],
        "nama" => $_POST['nama'],
        "umur" => $_POST['umur'],
        "notlp" => $_POST['notlp'],
    ];

}
?>

<!DOCTYPE html>
<html>
<head>
<title>LifeTrack Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container vh-100 d-flex justify-content-center align-items-center">
<div class="card p-4" style="width:360px">

<h4 class="fw-bold text-success">Login LifeTrack</h4>

<?php if(isset($error) && $error != ""){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button type="submit" name="login" class="btn btn-success w-100">
Login
</button>

</form>

<p class="mt-3 text-center">
Belum punya akun? <a href="regis.php">Daftar</a>
</p>

</div>
</div>

</body>
</html>