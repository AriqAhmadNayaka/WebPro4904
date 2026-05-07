<?php
require_once "classes/Article.php";
session_start();
if (!isset($_SESSION["currentUser"])) {
    header("Location: login.php");
    exit();
}

$article = new Article();

// CREATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article->create($_POST["title"], $_POST["content"], $_FILES["file"]);
}

$result = $article->readAll();
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
<h2>Selamat Datang, <?php echo $_SESSION["currentUser"]["name"]; ?>!</h2>

<form method="post" enctype="multipart/form-data">
    Judul: <input type="text" name="title" required><br>
    Konten: <textarea name="content" required></textarea><br>
    Upload File: <input type="file" name="file" required><br>
    <button type="submit">Simpan</button>
</form>

<h3>Daftar Artikel</h3>
<table border="1">
<tr><th>ID</th><th>Judul</th><th>Konten</th><th>File</th><th>Aksi</th></tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row["id"]; ?></td>
    <td><?php echo $row["title"]; ?></td>
    <td><?php echo $row["content"]; ?></td>
    <td><a href="<?php echo $row["file_path"]; ?>" target="_blank">Lihat File</a></td>
    <td>
        <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a> |
        <a href="delete.php?id=<?php echo $row['id']; ?>">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>
</body>
</html>
