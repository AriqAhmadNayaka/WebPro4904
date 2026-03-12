<?php
session_start();

/* Cek apakah sudah login */
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

/* Inisialisasi data */
if(!isset($_SESSION['data'])){
    $_SESSION['data'] = [];
}

/* CREATE */
if(isset($_POST['tambah'])){
    $judul = $_POST['judul'];

    if($judul != ""){
        $_SESSION['data'][] = $judul;
    }
}

/* LOGOUT */
if(isset($_POST['logout'])){
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Dashboard</h2>
<p>Selamat datang, <?php echo $_SESSION['email']; ?></p>

<hr>

<h3>CREATE (Tambah Data)</h3>
<form method="POST">
    Masukkan Data: 
    <input type="text" name="judul" required>
    <button type="submit" name="tambah">Tambah</button>
</form>

<hr>

<h3>READ (Tampilkan Data)</h3>

<?php
if(count($_SESSION['data']) > 0){
    echo "<ul>";
    foreach($_SESSION['data'] as $item){
        echo "<li>" . htmlspecialchars($item) . "</li>";
    }
    echo "</ul>";
} else {
    echo "Belum ada data.";
}
?>

<hr>

<form method="POST">
    <button type="submit" name="logout">Logout</button>
</form>

</body>
</html>