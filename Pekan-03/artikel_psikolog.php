<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel | Srikandi</title>
    <link rel="stylesheet" href="artikel_psikolog.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> 
</head>
<body>
    <div>
        <div class="header">
        <h1>Srikandi</h1>
        <div class="user-profile">
            <img src="../img/pp.jpeg" alt="User Profile">
        </div>
    </div>

    <div class="sidebar">
        <div class="sidebar-logo">Srikandi</div>
        <nav>
            <a href="#">
                <span class="material-icons">home</span>
                Beranda
            </a>
            <a href="#">
                <span class="material-icons">chat</span>
                Konsultasi
            </a>
            <a href="artikel_psikolog.html">
                <span class="material-icons">article</span>
                Artikel
            </a>
        </nav>
        <div class="logout">
             <a href="#">
                <span class="material-icons">exit_to_app</span>
                Keluar
            </a>
        </div>
    </div>

    <div class="main-container">
        <div class="content">
            <div class="content-header">
                <h2>Artikel</h2>
                <div class="search-bar">
                    <span class="material-icons">search</span>
                    <input type="text" placeholder="Ketikkan pencarian mu di sini">
                </div>
            </div>

            <div class="article-card">
                <div class="header-post">
                    <div class="user-profile">
                        <img src="../img/pp.jpeg" alt="User Profile">
                    </div>
                    <div class="isi-post">
                    <form method="POST" action="">
                        <input type="text" name="artikel" id="artikel" placeholder="Buat sebuah artikel">
                        <hr>
                        <div class="post-actions">
                            <i class="fas fa-image post-icon"></i>
                            <button class="btn-posting">Posting</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") { //cek apakah form nya sudah di isi apa belum
                $artikel = htmlspecialchars(($_POST["artikel"])); //bersihin data dari spesial karakter yang ga di inginkan
                echo "<h3>Artikel berhasil dikirim</h3>"; //pesan data sudah berhasil di kirim
                echo "<br>"; // baris baru
                echo "<h3>Psikolog</h3>"; //judul artikel
                echo "<p>$artikel</p>";} //isi artikel yang udah di input tadi
                echo "<br>"; //baris baru
            ?>

            <div class="article-card">
                <div class="article-info">
                    <h3>KemenPPPA</h3>
                    <div class="article-image"></div>
                    <p class="date">10-11-2025</p>
                    <p class="isi">Tuliskan artikel disini dengan banyak banyak teks ya kannn. Tuliskan artikel disini dengan banyak banyak teks ya kannn. Tuliskan artikel disini dengan banyak banyak teks ya kannn. Tuliskan artikel disini dengan banyak banyak teks ya kannn.</p>
                </div>
            </div>

            <div class="article-card">
                <div class="article-info">
                    <h3>Psikolog</h3>
                    <p class="date">27-12-2025</p>
                    <p class="isi">Menuliskan artikel disini dengan bahasa yang baik dan benar. Tuliskan artikel disini dengan banyak banyak teks ya kannn. Menuliskan artikel disini dengan bahasa yang baik dan benar. Tuliskan artikel disini dengan banyak banyak teks ya kannn.</p>
                </div>
            </div>
            
            </div>

        <div class="kanan">
            <h3>Kategori</h3>
            <div class="category-list">
                <p>Kesehatan mental</p>
                <p>Edukasi</p>
                <p>Penanganan kekerasan</p>
            </div>
        </div>
    </div>

</body>
</html>