<?php
session_start();
if (!isset($_SESSION["currentUser"])) {
    header("Location: login.php");
    exit();
}

// Simpan data artikel di session
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST["title"]);
    $content = htmlspecialchars($_POST["content"]);
    $_SESSION["articles"][] = ["title" => $title, "content" => $content];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
<div class="menu-section">
    <h2 class="home-label">Selamat Datang, <?php echo $_SESSION["currentUser"]["name"]; ?>!</h2>

    <!-- Form Create -->
    <form method="post">
        <input type="text" name="title" placeholder="Judul Artikel" required><br><br>
        <textarea name="content" placeholder="Isi Artikel" required></textarea><br><br>
        <button type="submit" class="btn-view-all">Tambah Artikel</button>
    </form>

    <!-- Read: Tampilkan Artikel -->
    <div class="recipe-cards-grid">
        <?php
        if (!empty($_SESSION["articles"])) {
            foreach ($_SESSION["articles"] as $article) {
                echo "<div class='recipe-card-home'>
                        <div class='recipe-card-body'>
                            <h3>{$article['title']}</h3>
                            <p>{$article['content']}</p>
                        </div>
                      </div>";
            }
        } else {
            echo "<p>Belum ada artikel.</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
