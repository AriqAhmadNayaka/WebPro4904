<?php
session_start();

if(isset($_POST['login'])){
    if($_POST['username'] == $_SESSION['username'] &&
       $_POST['password'] == $_SESSION['password']){
        $_SESSION['login'] = true;
        header("Location: Soal02.php");
        exit;
    }
}

if(!isset($_SESSION['data'])){
    $_SESSION['data'] = [];
}

if(isset($_POST['tambah'])){
    $_SESSION['data'][] = $_POST['nama'];
}
?>

<h2>Dashboard</h2>
<p>Selamat datang, <?php echo $_SESSION['user']['username']; ?>!</p>

<h3>Tambah Data</h3>
<form method="POST">
    Nama: <input type="text" name="nama" required>
    <button type="submit" name="tambah">Tambah</button>
</form>

<h3>Data Tersimpan (READ)</h3>
<ul>
<?php
foreach($_SESSION['data'] as $item){
    echo "<li>$item</li>";
}
?>
</ul>

<a href="logout.php">Logout</a>