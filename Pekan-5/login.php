<?php
session_start(); //memulai session
include("Koneksi.php");

//mencek apakah ada cookie "user_email", jika ada tampilkan di input email
$saved_email = isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : "";
//mengambil data yang diketik user di form
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    //mencari data di data base
    $sql = "SELECT * FROM user1 WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    //mencocokan yang sudah ada di database
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();

        if($user['password'] == $password){
            // menyimpan data user ke SESSION
            $_SESSION['user'] = $user;

            //simpan email selama 30 hari dengan jika remember dicentang
            if(isset($_POST['remember'])){
                setcookie("user_email", $email, time() + (86400 * 30), "/"); 
            } else {
                //hapus cookie jika tidak dicentang
                setcookie("user_email", "", time() - 3600, "/");
            }
            //pindah ke dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
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