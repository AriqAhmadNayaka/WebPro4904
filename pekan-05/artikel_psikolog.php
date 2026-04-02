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
                    <form method="POST" action="create.php" enctype="multipart/form-data"> <!--membuat form-->
                        <input type="text" name="isi_artikel" id="isi_artikel" placeholder="Buat sebuah artikel"> <!--megisi artikel yang akan di posting-->
                        <hr>
                        <br>
                        <input type="file" name="foto" id="foto" accept="/image*"> <!--mengupload file gambar-->
                        <div class="post-actions">
                            <i class="fas fa-image post-icon"></i>
                            <button type="submit" name="submit" class="btn-posting">Posting</button> <!--mengirim data artikel-->
                        </div>
                    </form>
                    </div>
                </div>
            </div>

                <br>

            <?php
                include "koneksi.php"; //menghubungkan ke database
                $sql = "SELECT * FROM artikel"; //mengambil data artikel dari database
                $query = mysqli_query($koneksi, $sql); //menjalankan query dan juga koneksi
                while ($data = mysqli_fetch_assoc($query)) { //melakukan perulangan untuk menampilkan data artikel yang diambil dari database
          ?>
            <div class="article-card">
                <div class="article-info">
                    <h3>Psikolog</h3>
                    <p class="date">27-12-2025</p>
                    <img class="article-image" src="uploads/<?php echo $data['foto']; ?>" > <!--menampilkan foto artikel yang diambil dari database dengan nama file yang disimpan di folder uploads-->
                    <p class="isi"><?php echo $data['isi_artikel']; ?></p> <!--menampilkan isi artikel yang diambil dari database -->
                    <button class="btn-posting"><a href="delete.php?id_artikel=<?php echo $data['id_artikel']; ?>">Hapus</a></button> <!--menampilkan tombol hapus untuk menghapus artikel 
                    yang diambil dari database dengan mengirim id_artikel ke file delete.php-->
                    <button class="btn-posting"><a href="update.php?id_artikel=<?php echo $data['id_artikel']; ?>">Edit</a></button> <!--menampilkan tombol edit untuk mengedit artikel yang
                     diambil dari database dengan mengirim id_artikel ke file edit.php-->
                </div>
            </div>

            <div class="article-card">
                <div class="article-info">
                    <h3>KemenPPPA</h3>
                    <div class="article-image"></div>
                    <p class="date">10-11-2025</p>
                    <p class="isi">Tuliskan artikel disini dengan banyak banyak teks ya kannn. Tuliskan artikel disini dengan
                         banyak banyak teks ya kannn. Tuliskan artikel disini dengan banyak banyak teks ya kannn. Tuliskan artikel disini dengan banyak banyak teks ya kannn.</p>
                </div>
            </div>

            <div class="article-card">
                <div class="article-info">
                    <h3>Psikolog</h3>
                    <p class="date">27-12-2025</p>
                    <p class="isi">Menuliskan artikel disini dengan bahasa yang baik dan benar. Tuliskan artikel disini 
                        dengan banyak banyak teks ya kannn. Menuliskan artikel disini dengan bahasa yang baik dan benar. Tuliskan artikel disini dengan banyak banyak teks ya kannn.</p>
                </div>
            </div>

            <?php }?>  <!--penutup while loop untuk menampilkan artikel yang diambil dari database -->

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