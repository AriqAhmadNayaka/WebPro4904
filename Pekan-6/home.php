<?php
include 'config.php';
session_start();
if (!isset($_SESSION["currentUser"])) {
    header("Location: login.php");
    exit();
}

// CREATE + UPLOAD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $fileName = basename($_FILES["file"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        $sql = "INSERT INTO articles (title, content, file_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $content, $targetFile);
        $stmt->execute();
        $stmt->close();
    }
}

// READ
$result = $conn->query("SELECT * FROM articles");
?>
<!DOCTYPE html>
<html>
<head><title>Home</title></head>
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
    <td>
        <?php if ($row["file_path"]) { ?>
            <a href="<?php echo $row["file_path"]; ?>" target="_blank">Lihat File</a>
        <?php } ?>
    </td>
    <td>
        <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a> |
        <a href="delete.php?id=<?php echo $row['id']; ?>">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>
</body>
</html>