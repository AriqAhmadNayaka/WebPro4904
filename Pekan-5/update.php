<?php
include 'config.php';
session_start();

$id = $_GET["id"];
$sql = "SELECT * FROM articles WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];

    $filePath = $article["file_path"];
    if (!empty($_FILES["file"]["name"])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFile = $targetDir . time() . "_" . $fileName;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            if (file_exists($filePath)) unlink($filePath);
            $filePath = $targetFile;
        }
    }

    $sql = "UPDATE articles SET title=?, content=?, file_path=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $content, $filePath, $id);
    $stmt->execute();
    header("Location: home.php");
    exit();
}
?>
<form method="post" enctype="multipart/form-data">
    Judul: <input type="text" name="title" value="<?php echo $article['title']; ?>"><br>
    Konten: <textarea name="content"><?php echo $article['content']; ?></textarea><br>
    Upload File Baru: <input type="file" name="file"><br>
    <button type="submit">Update</button>
</form>
