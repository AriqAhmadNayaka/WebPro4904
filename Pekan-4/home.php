<?php
include 'config.php';
session_start();
if (!isset($_SESSION["currentUser"])) {
    header("Location: login.php");
    exit();
}

// CREATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $sql = "INSERT INTO articles (title, content) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();
    $stmt->close();
}

// READ
$result = $conn->query("SELECT * FROM articles");
?>
<!DOCTYPE html>
<html>
<head><title>Home</title></head>
<body>
<h2>Selamat Datang, <?php echo $_SESSION["currentUser"]["name"]; ?>!</h2>

<form method="post">
    Judul: <input type="text" name="title" required><br>
    Konten: <textarea name="content" required></textarea><br>
    <button type="submit">Tambah Artikel</button>
</form>

<h3>Daftar Artikel</h3>
<table border="1">
<tr><th>ID</th><th>Judul</th><th>Konten</th><th>Aksi</th></tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row["id"]; ?></td>
    <td><?php echo $row["title"]; ?></td>
    <td><?php echo $row["content"]; ?></td>
    <td>
        <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a> |
        <a href="delete.php?id=<?php echo $row['id']; ?>">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>
</body>
</html>
