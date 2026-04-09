<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Pasien</title>
    <link rel="stylesheet" href="inputdatastyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        
         <aside class="sidebar">
            <a href="nuids.html" class="sidebar-logo"><img src="logo Nuids..png" width="50px" style="margin-left: -1px;"></i></a>
            <nav class="sidebar-nav">
                <a href="nuids.html" class="nav-item"><i class="fa-solid fa-house"></i></a>
                <a href="inputdata.html" class="nav-item active"><i class="fa-solid fa-file"></i></a>
                <a href="chatbox.html" class="nav-item"><i class="fa-solid fa-comment"></i></a>
                <a href="#" class="nav-item"><i class="fa-solid fa-file-lines"></i></a>
            </nav>
            <div class="sidebar-bottom">
                <a href="#" class="nav-item"><i class="fa-solid fa-gear"></i></a>
                <div class="user-avatar-small">
                    <img src="IMG_1386.JPG" alt="Avatar Haikal">
                </div>
            </div>
        </aside>

        <main class="main-content">
            <h1 class="page-title">Input Data Pasien</h1>

            <section class="glass-section input-form-section">
                <form class="input-grid" id="formPasien" method="POST" action="proses.php" enctype="multipart/form-data"> 

                    <div class="input-group">
                        <label>Nama</label>
                        <input type="text" name="nama" required> 
                    </div>

                    <div class="input-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal" required> 
                    </div>

                    <div class="input-group">
                        <label>Jenis Kelamin</label>
                        <input type="text" name="jk" required> 
                    </div>

                    <div class="input-group">
                        <label>Berat Badan</label>
                        <input type="text" name="berat"> 
                    </div>

                    <div class="input-group">
                        <label>Tinggi Badan</label>
                        <input type="text" name="tinggi"> 
                    </div>

                    <div class="input-group">
                        <label>Lingkar Kepala</label>
                        <input type="text" name="lingkar"> 
                    </div>

                    <div class="input-group">
                        <label>Upload Foto / File</label>
                        <input type="file" name="file"> 
                    </div>

                    <div class="input-group">
                        <button type="submit" class="btn-submit"> 
                            Submit Data
                        </button>
                    </div>

                </form>
            </section>
            
            <section class="glass-section data-table-section">
                <h4 class="section-title">Data Pasien</h4>
                <div class="table-responsive">
                    <table class="data-pasien-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tanggal Lahir/Usia</th>
                                <th>Jenis Kelamin</th>
                                <th>Berat Badan</th>
                                <th>Tinggi Badan</th>
                                <th>Lingkar Kepala</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "koneksi.php"; //Menghubungkan file koneksi.php
                            include "Pasien.php"; //Menghubungkan file Pasien.php

                            $db = new Koneksi(); //Membuat objek koneksi database
                            $pasien = new Pasien($db->conn); //Membuat objek Pasien dengan koneksi database

                            $data = $pasien->tampil(); //Mengambil semua data pasien dari database
                            while($row = $data->fetch_assoc()){ //Perulangan untuk mengambil data satu per satu dalam bentuk array
                            ?>
                            <tr>
                                <td><?= $row['nama'] ?></td> //Menampilkan data nama pasien
                                <td><?= $row['tanggal'] ?></td> //Menampilkan data tanggal
                                <td><?= $row['jk'] ?></td> //Menampilkan jenis kelamin
                                <td><?= $row['berat'] ?></td> //Menampilkan berat badan
                                <td><?= $row['tinggi'] ?></td> //Menampilkan tinggi badan
                                <td><?= $row['lingkar'] ?></td> //Menampilkan lingkar kepala

                                <td>
                                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> | //Link untuk edit data, mengirim id ke halaman edit.php
                                    <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a> //mengirim id ke hapus.php
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
    <script>
        document.getElementById("formPasien").addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch("proses.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert("Data berhasil disimpan!");
        location.reload();
    })
    .catch(err => console.log(err));
});
    </script>
    <script src="main.js"></script>

    <?php
// include "koneksi.php";
// include "Pasien.php";

// $db = new Koneksi();
$pasien = new Pasien($db->conn);

$data = $pasien->tampil();
while($row = $data->fetch_assoc()){
?>
<tr>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['tanggal'] ?></td>
    <td><?= $row['jk'] ?></td>
    <td><?= $row['berat'] ?></td>
    <td><?= $row['tinggi'] ?></td>
    <td><?= $row['lingkar'] ?></td>
    <td>
        <a href="hapus.php?id=<?= $row['id'] ?>">Hapus</a>
    </td>
</tr>
<?php } ?>

</body>
</html>